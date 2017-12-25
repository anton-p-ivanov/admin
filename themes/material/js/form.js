(function ($) {
    "use strict";

    let Form, defaultOptions, selector, __bind;

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

    // i18n
    let i18n = {
        'en-US': {
            'VALIDATION_ERRORS_MESSAGE': 'Some fields have invalid values. Check them and try again.',
        },
        'ru-RU': {
            'VALIDATION_ERRORS_MESSAGE': 'Некоторые поля содержат ошибки. Пожалуйста, исправьте их.',
        }
    };

    Form = (function () {
        function Form(handler, options) {
            this.handler = handler;

            // Extend default options.
            $.extend(true, this, defaultOptions, options);

            // Bind methods.
            this.init = __bind(this.init, this);
        }

        // Cleans all form errors
        function cleanErrors(form) {
            form.find('[data-validation]').remove();
            form.find('.form-group.error').toggleClass('error', false);
            form.find('.form-group__error').html('');
        }

        // Inserts text at cursor position
        function insertTextAtCursor(text) {
            let sel, range;
            if (window.getSelection) {
                sel = window.getSelection();
                if (sel.getRangeAt && sel.rangeCount) {
                    range = sel.getRangeAt(0);
                    range.deleteContents();
                    range.insertNode(document.createTextNode(text));
                }
            } else if (document.selection && document.selection.createRange) {
                document.selection.createRange().text = text;
            }
        }

        Form.prototype.cleanField = function (e) {
            e.preventDefault();
            $(this).parent().find('.form-group__input,input:hidden').val('').text('').trigger('change').trigger('blur');
        };

        Form.prototype.submit = function (e) {
            e.preventDefault();

            let $form = $(e.target);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serializeArray(),
            })
            .done(function(r, statusText, xhr) {

                cleanErrors($form);

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

                    let message = i18n[defaultOptions.locale]['VALIDATION_ERRORS_MESSAGE'],
                        button = '<i class="material-icons">close</i>';

                    let alert = $('<div>').attr({'class': 'modal__alert alert alert_error', 'data-validation': 'false'})
                        .append($('<div class="alert__content">').text(message))
                        .append($('<a class="alert__dismiss" href="#" data-toggle="dismiss">').html(button));

                    $form.find('.modal__body').prepend(alert);
                    $form.trigger($.Event('afterValidate.Form'));
                }
                else if (xhr.status === 200) {
                    $form.trigger($.Event('afterSubmit.Form', {'response': r}));
                }
            });
        };

        Form.prototype.focus = function () {
            let self = $(this),
                isFocused = self.is(':focus'),
                value = self.is('input') ? self.val() : self.text();

            // if (!value && self.is('[readonly]')) return;

            self.closest('.form-group')
                .toggleClass('active', isFocused || value.length > 0)
                .toggleClass('focused', isFocused)
        };

        Form.prototype.updateEditableField = function () {
            $($(this).data('target')).val($(this).text());
        };

        Form.prototype.selectDropdownItem = function (e) {
            e.preventDefault();

            let self = $(this),
                input = self.parents('.form-group_dropdown').find('input:hidden'),
                text = self.parents('.form-group_dropdown').find('input:text'),
                value = self.data('value');

            self.parents('ul').find('.active').toggleClass('active', false);
            self.parent().toggleClass('active', true);

            input.val(value).trigger('change');
            text.val(self.text()).trigger('blur');
        };

        // Main method.
        Form.prototype.init = function () {

            $(this.handler).find('.form-group__input')
                .filter(function() { return $(this).val() || $(this).text(); })
                .closest('.form-group').toggleClass('active', true);

            $(this.handler).find('[data-editable="true"]').prop('contenteditable', true);

            $(this.handler)
                .on('paste', '[contenteditable]', function(e) {
                    e.preventDefault();
                    let text;
                    if (e.originalEvent.clipboardData && e.originalEvent.clipboardData.getData) {
                        text = e.originalEvent.clipboardData.getData("text/plain");
                        document.execCommand("insertHTML", false, text);
                    }
                    else if (window.clipboardData && window.clipboardData.getData) {
                        text = window.clipboardData.getData("Text");
                        insertTextAtCursor(text);
                    }
                })
                .on('focus', '[contenteditable]', function() {
                    let $this = $(this);
                    $this.data('before', $this.html());
                    return $this;
                })
                .on('blur keyup paste input', '[contenteditable]', function() {
                    let $this = $(this);
                    if ($this.data('before') !== $this.html()) {
                        $this.data('before', $this.html());
                        $this.trigger('change.Form');
                    }
                    return $this;
                });

            $(this.handler)
                .on('submit.Form', this.submit)
                .on('click.Form', '[data-toggle="clean"]', this.cleanField)
                .on('focus.Form blur.Form', '.form-group__input', this.focus)
                .on('change.Form', '[data-editable="true"]', this.updateEditableField)
                .on('click.Form', '.form-group_dropdown .dropdown a', this.selectDropdownItem);

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
