<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CSRF bootstrap — global token injection for the whole legacy app.
 *
 * Included once per page (be/pos/fe layout footers + the standalone
 * login/recover/register/outlet pages). Emits NOTHING unless
 * $config['csrf_protection'] is TRUE, so deploying this file is a
 * runtime no-op until the operator flips the host-mounted config
 * (see docs/CSRF.md for the flip runbook).
 *
 * When active it guarantees every same-origin POST carries the token:
 *   1. A hidden <input> is injected into every method="post" form at
 *      DOM-ready AND (capture-phase) on every submit event — so both
 *      native submits and handlers that build FormData($form) /
 *      $(form).serialize() pick the token up automatically.
 *   2. A jQuery $.ajaxPrefilter appends the token to every same-origin
 *      POST $.ajax()/$.post() call — FormData bodies, url-encoded
 *      string bodies, plain-object bodies and empty bodies.
 *   3. $.redirect() (jquery.redirect builds a form and calls the
 *      native form.submit(), which fires NO submit event — used by all
 *      the report/print pages and site search) is wrapped so its
 *      generated form gets the token too.
 *
 * The live token is always read from the CSRF cookie when it is
 * JS-readable (cookie_httponly FALSE — the CI3 default here), falling
 * back to the hash rendered at page load. That keeps long-lived tabs
 * working across token expiry, and works with csrf_regenerate FALSE
 * (recommended — see config.php.example).
 */
$CI =& get_instance();
if ($CI->config->item('csrf_protection')):
?>
<script>
(function () {
    'use strict';

    var CSRF_NAME   = <?php echo json_encode($CI->security->get_csrf_token_name()); ?>;
    var CSRF_COOKIE = <?php echo json_encode($CI->config->item('csrf_cookie_name')); ?>;
    var CSRF_BOOT_HASH = <?php echo json_encode($CI->security->get_csrf_hash()); ?>;

    function readCookie(name) {
        var parts = ('; ' + document.cookie).split('; ' + name + '=');
        if (parts.length < 2) { return null; }
        return decodeURIComponent(parts.pop().split(';').shift());
    }

    function csrfHash() {
        return readCookie(CSRF_COOKIE) || CSRF_BOOT_HASH;
    }

    function sameOrigin(url) {
        if (!url) { return true; } // relative / current page
        var a = document.createElement('a');
        a.href = url;
        // Relative URLs resolve to the current origin.
        return (a.protocol === window.location.protocol && a.host === window.location.host);
    }

    function ensureFormToken(form) {
        try {
            if (!form || !form.getAttribute) { return; }
            var method = (form.getAttribute('method') || 'get').toLowerCase();
            if (method !== 'post') { return; }
            if (!sameOrigin(form.getAttribute('action'))) { return; }
            var input = null;
            for (var i = 0; i < form.elements.length; i++) {
                if (form.elements[i].name === CSRF_NAME) { input = form.elements[i]; break; }
            }
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = CSRF_NAME;
                form.appendChild(input);
            }
            input.value = csrfHash();
        } catch (e) { /* never break a submit */ }
    }

    function injectAllForms() {
        var forms = document.getElementsByTagName('form');
        for (var i = 0; i < forms.length; i++) { ensureFormToken(forms[i]); }
    }

    // 1a. All forms present at DOM-ready (handlers that FormData($form) on a
    //     button click — no submit event — still pick the token up).
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', injectAllForms);
    } else {
        injectAllForms();
    }

    // 1b. Capture-phase submit: runs before any inline onsubmit handler, and
    //     covers forms added to the page after DOM-ready.
    document.addEventListener('submit', function (e) { ensureFormToken(e.target); }, true);

    // 2 + 3 need jQuery, which some surfaces load async — poll until present.
    var wiredAjax = false, wiredRedirect = false;
    function wireJquery() {
        var $ = window.jQuery;
        if (!$) { return false; }

        if (!wiredAjax && $.ajaxPrefilter) {
            $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
                try {
                    var type = (options.type || options.method || 'GET').toUpperCase();
                    if (type !== 'POST') { return; }
                    if (options.crossDomain === true || !sameOrigin(options.url)) { return; }

                    var hash = csrfHash();
                    var data = options.data;

                    if (window.FormData && data instanceof window.FormData) {
                        if (typeof data.has !== 'function' || !data.has(CSRF_NAME)) {
                            data.append(CSRF_NAME, hash);
                        }
                    } else if (typeof data === 'string') {
                        var pattern = new RegExp('(^|&)' + encodeURIComponent(CSRF_NAME) + '=');
                        if (!pattern.test(data)) {
                            options.data = (data.length ? data + '&' : '')
                                + encodeURIComponent(CSRF_NAME) + '=' + encodeURIComponent(hash);
                        }
                    } else if (data && typeof data === 'object') {
                        if (!(CSRF_NAME in data)) { data[CSRF_NAME] = hash; }
                    } else if (data === undefined || data === null || data === '') {
                        options.data = encodeURIComponent(CSRF_NAME) + '=' + encodeURIComponent(hash);
                    }
                } catch (e) { /* never break a request */ }
            });
            wiredAjax = true;
        }

        // jquery.redirect loads async on the storefront — keep polling for it.
        if (!wiredRedirect && $.redirect && $.redirect.getForm) {
            var origGetForm = $.redirect.getForm;
            $.redirect.getForm = function (url, values, method, target, traditional) {
                var generated = origGetForm.apply(this, arguments);
                try {
                    var m = (method || 'POST').toUpperCase();
                    if (m === 'POST' && sameOrigin(url) && generated && generated.form) {
                        ensureFormToken(generated.form[0] || generated.form);
                    }
                } catch (e) { /* never break a redirect */ }
                return generated;
            };
            wiredRedirect = true;
        }

        return wiredAjax && wiredRedirect;
    }

    if (!wireJquery()) {
        var tries = 0;
        var timer = window.setInterval(function () {
            // Stop once fully wired, or after ~30s. $.redirect may legitimately
            // never load on some pages — ajax wiring alone is fine there.
            if (wireJquery() || ++tries > 600) { window.clearInterval(timer); }
        }, 50);
    }

    // Exposed for debugging / any future hand-written caller.
    window.BH_CSRF = { name: CSRF_NAME, cookie: CSRF_COOKIE, hash: csrfHash };
})();
</script>
<?php endif; ?>
