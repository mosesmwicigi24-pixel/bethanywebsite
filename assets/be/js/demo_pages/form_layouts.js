/* ------------------------------------------------------------------------------
 *
 *  # Form layouts
 *
 *  Demo JS code for form layouts pages
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var FormLayouts = function() {


    //
    // Setup module components
    //

    // Select2
    var _componentSelect2 = function() {
        if (!$().select2) {
            console.warn('Warning - select2.min.js is not loaded.');
            return;
        };

        // Basic example
        $('.form-control-select2').select2({
            allowClear: true
        });
        $('.form-control-select3').select2({
            allowClear: true,
            templateResult: iconFormat,
            templateSelection: iconFormat,
            escapeMarkup: function(m) { return m; }
        });
        $('.form-control-select4').select2({
            allowClear: true,
            templateResult: iconFormat2,
            templateSelection: iconFormat2,
            escapeMarkup: function(m) { return m; }
        });
        $('.select-fixed-multiple').select2({
            minimumResultsForSearch: Infinity,
        });


        //
        // Select with icons
        //

        // Format icon
        function iconFormat(icon) {
            var originalOption = icon.element;
            if (!icon.id) { return icon.text; }
            var $icon = "<i class='icon-" + $(icon.element).data('icon') + "'></i>" + icon.text;

            return $icon;
        }
        function iconFormat2(icon) {
            var originalOption = icon.element;
            if (!icon.id) { return icon.text; }
            var $icon = "<i class='icon-" + $(icon.element).data('icon') + "' style='color:" + $(icon.element).data('colo') + "'></i>" + icon.text;

            return $icon;
        }

        // Initialize with options
        $('.form-control-select2-icons').select2({
            templateResult: iconFormat,
            minimumResultsForSearch: Infinity,
            templateSelection: iconFormat,
            escapeMarkup: function(m) { return m; }
        });
    };

    // Uniform
    var _componentUniform = function() {
        if (!$().uniform) {
            console.warn('Warning - uniform.min.js is not loaded.');
            return;
        }

        // Initialize
        $('.form-input-styled').uniform({
            fileButtonClass: 'action btn bg-primary'
        });
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _componentSelect2();
            _componentUniform();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    FormLayouts.init();
});
