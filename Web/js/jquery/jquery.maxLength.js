(function ($) {
    jQuery.fn.maxlength = function (options) {

        var settings = jQuery.extend({
            onLimit: function () { },
            onEdit: function () { }
        }, options);

        // Event handler to limit the textarea
        var onEdit = function () {
            var textarea = jQuery(this);
            var maxlength = parseInt(textarea.data("maxlength"));

            var text = textarea.val();
            var len = 0;
            var val = '';
            var char = '';

            for (var i = 0; i < text.length; i++) {
                if (Math.ceil(len) >= maxlength) {
                    textarea.val(val).focus();

                    // Call the onlimit handler within the scope of the textarea
                    jQuery.proxy(settings.onLimit, this)();

                    break;
                }

                char = text.charCodeAt(i);
                val += text.substr(i, 1);

                if (!(char > 255)) {
                    len = len + .5;
                } else {
                    len = len + 1;
                }
            }

            // Call the onEdit handler within the scope of the textarea
            jQuery.proxy(settings.onEdit, this)(maxlength - Math.ceil(len));
        }

        this.each(onEdit);

        var obj_interval;

        if (jQuery.browser.msie && jQuery.browser.version < 10) {
            this.bind('focus', function () {
                var tb = $(this);
                obj_interval = setInterval(function () { tb.trigger('change'); }, 50);
            }).bind('blur', function () {
                if (obj_interval != null)
                    clearInterval(obj_interval);
            });
        }

        return this.keyup(onEdit)
                    .keydown(onEdit)
                    .focus(onEdit)
                    .bind('input paste change', onEdit);
    };
})(jQuery);