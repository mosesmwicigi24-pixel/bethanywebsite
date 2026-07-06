"use strict";
function _createForOfIteratorHelper(e, t) {
    var r;
    if ("undefined" == typeof Symbol || null == e[Symbol.iterator]) {
        if (Array.isArray(e) || (r = _unsupportedIterableToArray(e)) || (t && e && "number" == typeof e.length)) {
            r && (e = r);
            var o = 0,
                n = function () {};
            return {
                s: n,
                n: function () {
                    return o >= e.length ? { done: !0 } : { done: !1, value: e[o++] };
                },
                e: function (e) {
                    throw e;
                },
                f: n,
            };
        }
        throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
    }
    var a,
        i = !0,
        s = !1;
    return {
        s: function () {
            r = e[Symbol.iterator]();
        },
        n: function () {
            var e = r.next();
            return (i = e.done), e;
        },
        e: function (e) {
            (s = !0), (a = e);
        },
        f: function () {
            try {
                i || null == r.return || r.return();
            } finally {
                if (s) throw a;
            }
        },
    };
}
function _unsupportedIterableToArray(e, t) {
    if (e) {
        if ("string" == typeof e) return _arrayLikeToArray(e, t);
        var r = Object.prototype.toString.call(e).slice(8, -1);
        return "Object" === r && e.constructor && (r = e.constructor.name), "Map" === r || "Set" === r ? Array.from(e) : "Arguments" === r || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r) ? _arrayLikeToArray(e, t) : void 0;
    }
}
function _arrayLikeToArray(e, t) {
    (null == t || t > e.length) && (t = e.length);
    for (var r = 0, o = new Array(t); r < t; r++) o[r] = e[r];
    return o;
}
function _typeof(e) {
    return (_typeof =
        "function" == typeof Symbol && "symbol" == typeof Symbol.iterator
            ? function (e) {
                  return typeof e;
              }
            : function (e) {
                  return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e;
              })(e);
}
!(function (e) {
    var t, r, o;
    "function" == typeof define && define.amd && (define(e), (t = !0)),
        "object" === ("undefined" == typeof exports ? "undefined" : _typeof(exports)) && ((module.exports = e()), (t = !0)),
        t ||
            ((r = window.Cookies),
            ((o = window.Cookies = e()).noConflict = function () {
                return (window.Cookies = r), o;
            }));
})(function () {
    function s() {
        for (var e = 0, t = {}; e < arguments.length; e++) {
            var r = arguments[e];
            for (var o in r) t[o] = r[o];
        }
        return t;
    }
    function l(e) {
        return e.replace(/(%[0-9A-Z]{2})+/g, decodeURIComponent);
    }
    return (function e(d) {
        function i() {}
        function r(e, t, r) {
            if ("undefined" != typeof document) {
                "number" == typeof (r = s({ path: "/" }, i.defaults, r)).expires && (r.expires = new Date(+new Date() + 864e5 * r.expires)), (r.expires = r.expires ? r.expires.toUTCString() : "");
                try {
                    var o = JSON.stringify(t);
                    /^[\{\[]/.test(o) && (t = o);
                } catch (e) {}
                (t = d.write ? d.write(t, e) : encodeURIComponent(String(t)).replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent)),
                    (e = encodeURIComponent(String(e))
                        .replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent)
                        .replace(/[\(\)]/g, escape));
                var n = "";
                for (var a in r) r[a] && ((n += "; " + a), !0 !== r[a] && (n += "=" + r[a].split(";")[0]));
                return (document.cookie = e + "=" + t + n);
            }
        }
        function t(e, t) {
            if ("undefined" != typeof document) {
                for (var r = {}, o = document.cookie ? document.cookie.split("; ") : [], n = 0; n < o.length; n++) {
                    var a = o[n].split("="),
                        i = a.slice(1).join("=");
                    t || '"' !== i.charAt(0) || (i = i.slice(1, -1));
                    try {
                        var s = l(a[0]),
                            i = (d.read || d)(i, s) || l(i);
                        if (t)
                            try {
                                i = JSON.parse(i);
                            } catch (e) {}
                        if (((r[s] = i), e === s)) break;
                    } catch (e) {}
                }
                return e ? r[e] : r;
            }
        }
        return (
            (i.set = r),
            (i.get = function (e) {
                return t(e, !1);
            }),
            (i.getJSON = function (e) {
                return t(e, !0);
            }),
            (i.remove = function (e, t) {
                r(e, "", s(t, { expires: -1 }));
            }),
            (i.defaults = {}),
            (i.withConverter = e),
            i
        );
    })(function () {});
}),
    (function (d, l) {
        var u = l("body"),
            c = l("head"),
            f = "#skin-default",
            m = ".nk-sidebar",
            p = ".nk-header",
            i = ["demo2", "ecommerce"],
            a = ["style", "aside", "header", "skin", "mode"],
            n = "theme-light",
            y = "theme-light",
            s = ".nk-opt-item",
            h = ".nk-opt-list",
            v = { demo2: { aside: "is-theme", header: "is-theme", style: "ui-default" }, ecommerce: { aside: "is-theme", header: "is-theme", style: "ui-default" } };
        (d.Demo = {
            save: function (e, t) {
                Cookies.set(d.Demo.apps(e), t);
            },
            remove: function (e) {
                Cookies.remove(d.Demo.apps(e));
            },
            current: function (e) {
                return Cookies.get(d.Demo.apps(e));
            },
            apps: function (e) {
                var t,
                    r,
                    o = window.location.pathname.split("/").map(function (e, t, r) {
                        return e.replace("-", "");
                    }),
                    n = _createForOfIteratorHelper(i);
                try {
                    for (n.s(); !(r = n.n()).done; ) {
                        var a = r.value;
                        0 <= o.indexOf(a) && (t = a);
                    }
                } catch (e) {
                    n.e(e);
                } finally {
                    n.f();
                }
                return e ? e + "_" + t : t;
            },
            style: function (e, t) {
                var r = { mode: n + " " + y, style: "ui-default ui-bordered", aside: "is-light is-default is-theme is-dark", header: "is-light is-default is-theme is-dark" };
                return "all" === e ? (r[t] ? r[t] : "") : "any" === e ? r.mode + " " + r.style + " " + r.aside + " " + r.header : "body" === e ? r.mode + " " + r.style : "is-default" === e || "ui-default" === e ? "" : e;
            },
            skins: function (e) {
                return !e || "default" === e ? "theme" : "theme-" + e;
            },
            defs: function (e) {
                var t = d.Demo.apps(),
                    r = v[t][e] ? v[t][e] : "";
                return d.Demo.current(e) ? d.Demo.current(e) : r;
            },
            apply: function () {
                d.Demo.apps();
                var e,
                    t = _createForOfIteratorHelper(a);
                try {
                    for (t.s(); !(e = t.n()).done; ) {
                        var r,
                            o,
                            n = e.value;
                        ("aside" !== n && "header" !== n && "style" !== n) ||
                            ((r = d.Demo.defs(n)), l((o = "aside" === n ? m : "header" === n ? p : u)).removeClass(d.Demo.style("all", n)), "ui-default" !== r && "is-default" !== r && l(o).addClass(r)),
                            "mode" === n && d.Demo.update(n, d.Demo.current("mode")),
                            "skin" === n && d.Demo.update(n, d.Demo.current("skin"));
                    }
                } catch (e) {
                    t.e(e);
                } finally {
                    t.f();
                }
            },
            update: function (e, t, r) {
                var o,
                    n,
                    a,
                    i = d.Demo.style(t, e),
                    s = d.Demo.style("all", e);
                d.Demo.apps();
                ("aside" !== e && "header" !== e) || (l((o = "header" == e ? p : m)).removeClass(s), l(o).addClass(i)),
                    "mode" === e && (u.removeClass(s).removeAttr("theme"), i === y && u.addClass(i).attr("theme", "dark")),
                    "style" === e && (u.removeClass(s), u.addClass(i)),
                    "skin" === e &&
                        ((n = d.Demo.skins(i)),
                        (a = l("#skin-default")
                            .attr("href")
                            .replace("theme", "skins/" + n)),
                        "theme" === n ? l(f).remove() : 0 < l(f).length ? l(f).attr("href", a) : c.append('<link id="skin-theme" rel="stylesheet" href="' + a + '">')),
                    !0 === r && d.Demo.save(e, t);
            },
            reset: function () {
                var t = d.Demo.apps();
                u.removeClass(d.Demo.style("body")).removeAttr("theme"), l(s).removeClass("active"), l(f).remove(), l(m).removeClass(d.Demo.style("all", "aside")), l(p).removeClass(d.Demo.style("all", "header"));
                var e,
                    r = _createForOfIteratorHelper(a);
                try {
                    for (r.s(); !(e = r.n()).done; ) {
                        var o = e.value;
                        l("[data-key='" + o + "']").each(function () {
                            var e = l(this).data("update");
                            ("aside" !== o && "header" !== o && "style" !== o) || (e === v[t][o] && l(this).addClass("active")), ("mode" !== o && "skin" !== o) || (e !== n && "default" !== e) || l(this).addClass("active");
                        }),
                            d.Demo.remove(o);
                    }
                } catch (e) {
                    r.e(e);
                } finally {
                    r.f();
                }
                d.Demo.apply();
            },
            load: function () {
                d.Demo.apply(),
                    0 < l(s).length &&
                        l(s).each(function () {
                            var e = l(this).data("update"),
                                t = l(this).data("key");
                            ("aside" !== t && "header" !== t && "style" !== t) || (e === d.Demo.defs(t) && (l(this).parent(h).find(s).removeClass("active"), l(this).addClass("active"))),
                                ("mode" !== t && "skin" !== t) || (e != d.Demo.current("skin") && e != d.Demo.current("mode")) || (l(this).parent(h).find(s).removeClass("active"), l(this).addClass("active"));
                        });
            },
            trigger: function () {
                l(s).on("click", function (e) {
                    e.preventDefault();
                    var t = l(this),
                        r = t.parent(h),
                        o = t.data("update"),
                        n = t.data("key");
                    d.Demo.update(n, o, !0), r.find(s).removeClass("active"), t.addClass("active");
                }),
                    l(".nk-opt-reset > a").on("click", function (e) {
                        e.preventDefault(), d.Demo.reset();
                    });
            },
            init: function () {
               // d.Demo.load(), d.Demo.trigger();
            },
        }),
            d.coms.docReady.push(d.Demo.init);
    })(NioApp, jQuery);
