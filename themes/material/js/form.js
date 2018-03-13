(function ($) {
    "use strict";

    let Form,
        defaultOptions,
        selector,
        __bind;

    __bind = function (fn, me) {
        return function () {
            return fn.apply(me, arguments);
        };
    };

    // Default selector
    selector = '[data-type="active-form"]';

    // Plugin default options.
    defaultOptions = {
        locale: $('html').attr('lang') || 'en-US',
    };

    Form = (function () {
        function Form(handler, options) {
            this.handler = handler;

            // Extend default options.
            $.extend(true, this, defaultOptions, options);

            // Bind methods.
            this.init = __bind(this.init, this);
            this.clean = __bind(this.clean, this);
        }

        // Cleans all form errors
        Form.prototype.clean = function () {
            let self = $(this.handler);

            self.find('[data-validation]').remove();
            self.find('.form-group')
                .toggleClass('error', false)
                .children('.form-group__error').html('');
        };

        Form.prototype.submit = function (e) {
            // Prevent default form submitting
            e.preventDefault();

            // Clean previous errors if exist
            e.data.target.clean();

            let $form = $(e.target);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serializeArray(),
            })
            .done(function(r, statusText, xhr) {

                // Clean previous errors if exist
                e.data.target.clean();

                // If validation errors found
                if (xhr.status === 206) {
                    for (let attr in r) {
                        if (r.hasOwnProperty(attr)) {
                            let $field = $form.find('#' + attr);
                            $field.closest('.form-group')
                                .toggleClass('error', true)
                                .children('.form-group__error').html(r[attr][0]);
                        }
                    }

                    // Switch to tab with first error message (if exist)
                    let tabid = $form.find('.form-group.error:first').closest('.tabs-pane').attr('id');
                    if (tabid) {
                        $form.find('.tabs-nav [data-target="#' + tabid + '"]').trigger('click');
                    }

                    $form.trigger($.Event('afterValidate.Form'));
                }
                else if (xhr.status === 200) {
                    $form.trigger($.Event('afterSubmit.Form', {'response': r}));
                }
            });
        };

        Form.prototype.focus = function () {
            let self = $(this),
                isFocused = self.is(':focus');

            self.closest('.form-group').toggleClass('focused', isFocused);
        };

        // Main method.
        Form.prototype.init = function () {
            $(this.handler)
                .on('submit.Form', {'target': this}, this.submit)
                .on('focus.Form blur.Form', '.form-group__input', this.focus);
        };

        return Form;
    })();

    $.fn.Form = function (options) {
        // Init plugin
        (new Form(this, options || {})).init();

        // Return jQuery object to maintain chainability.
        return this;
    };

    $(selector).Form();

})(jQuery);
