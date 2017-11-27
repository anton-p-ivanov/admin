(function ($) {
    "use strict";

    let Modal, defaultOptions, selector, __bind;

    __bind = function (fn, me) {
        return function () {
            return fn.apply(me, arguments);
        };
    };

    // Default selector
    selector = '[data-toggle="modal"]';

    // Plugin default options.
    defaultOptions = {
        remoteUrl: null,
        forceReload: false
    };

    Modal = (function () {

        // Class constructor
        function Modal(handler, options) {
            this.handler = handler;

            // Extend default options.
            $.extend(true, this, defaultOptions, options);

            // Bind methods.
            this.hide = __bind(this.hide, this);
            this.show = __bind(this.show, this);
            this.init = __bind(this.init, this);

            this.init();
        }

        // Main method.
        Modal.prototype.init = function () {
            let self = this,
                $target = $(self.handler);

            if (!$target.data('init')) {
                $target
                    .on('click.Modal', function (e) {
                        if ((e.target === $target.get(0) && $target.is('.opened') && !$target.data('persistent'))
                            || $(e.target).data('dismiss') === 'modal'
                            || $(e.target).parents('[data-dismiss]').length > 0
                        ) {
                            e.preventDefault();
                            self.hide($target);
                        }
                    })
                    .data('init', true);
            }
        };

        // Hiding modal
        Modal.prototype.hide = function () {
            $(this.handler).toggleClass('opened', false).trigger($.Event('hidden.Modal'));
            if (!$('.modal.opened').length) {
                $('body').toggleClass('no-scroll', false);
            }
        };

        // Showing modal
        Modal.prototype.show = function () {
            let $target = $(this.handler),
                url = this.remoteUrl;

            if (typeof url !== 'undefined' && url !== null && (!$target.data('loaded') || this.forceReload)) {
                $.ajax({
                    url: url,
                    success: function (response) {
                        $target.html(response).data('loaded', true);
                        $target.trigger($.Event('loaded.Modal', {url: url}));
                    }
                });
            }

            if ($target.not('.opened')) {
                $('body').toggleClass('no-scroll', true);
                $target.toggleClass('opened', true).trigger($.Event('shown.Modal'));
            }
        };

        return Modal;
    })();

    $.fn.Modal = function (options) {
        return (new Modal(this, options || {}));
    };

    $(document).off('click.Modal', selector);
    $(document).on('click.Modal', selector, function (e) {
        e.preventDefault();

        let self = $(this),
            options = {
                'remoteUrl': self.attr('href') || self.data('remote-url'),
                'forceReload': self.data('reload')
            },
            target = self.data('target'),
            $target = $(target);

        if ($target.length === 0 && target.indexOf('#') === 0) {
            $target = $('<div>').attr({
                'class': 'modal',
                'id': target.substring(1),
                'role': 'dialog',
            }).data({'persistent': self.data('persistent') || false});

            $('body').append($target);
        }

        $target
            .Modal(options)
            .show();
    });

})(jQuery);
