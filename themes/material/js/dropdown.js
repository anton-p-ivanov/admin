(function ($) {
    "use strict";

    let Dropdown, defaultOptions, selector, __bind;

    __bind = function (fn, me) {
        return function () {
            return fn.apply(me, arguments);
        };
    };

    // Default selector
    selector = '[data-toggle="dropdown"]';

    // Plugin default options.
    defaultOptions = {};

    Dropdown = (function () {
        function Dropdown(handler, options) {
            this.handler = handler;

            // Extend default options.
            $.extend(true, this, defaultOptions, options);

            // Bind methods.
            this.onClick = __bind(this.onClick, this);
            this.init = __bind(this.init, this);
        }

        Dropdown.prototype.hide = function () {
            $(selector).each(function () {
                let $toggle = $(this),
                    $target = $($toggle.data('target') || $toggle.next()),
                    relatedTarget = {relatedTarget: this};

                $toggle.toggleClass('active', false);
                $target.toggleClass('opened', false).trigger($.Event('hidden.Dropdown', relatedTarget));
            });
        };

        Dropdown.prototype.toggle = function (e) {
            let $toggle = $(e.currentTarget),
                $target = $($toggle.data('target') || $toggle.next()),
                relatedTarget = {relatedTarget: e.currentTarget};

            if ($toggle.is('.disabled, :disabled')) return;

            Dropdown.prototype.hide();

            if ($target.not('.opened')) {
                $toggle.toggleClass('active', true);
                $target.toggleClass('opened', true).trigger($.Event('shown.Dropdown', relatedTarget));

                let pos = $target.offset(),
                    height = $target.outerHeight(),
                    wHeight = $(window).height();

                // If dropdown is out of window bounds update it position
                if (pos.top + height > wHeight && pos.top - height > 0) {
                    $target.css({'top': -height});
                }
            }

            return false;
        };

        // Main method.
        Dropdown.prototype.init = function () {
            $(document)
                .on('click.Dropdown', this.hide)
                .on('click.Dropdown', selector, this.toggle);
        };

        return Dropdown;
    })();

    $.fn.Dropdown = function (options) {
        // Init plugin
        (new Dropdown(this, options || {})).init();

        // Display items (if hidden) and return jQuery object to maintain chainability.
        return this;
    };

    $(selector).Dropdown();

})(jQuery);
