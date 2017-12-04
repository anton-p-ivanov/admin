$(function () {
    "use strict";

    let _pjaxUrls = {'#forms-pjax': window.location.href};

    // Enable PJAX for main grid
    $(document).pjax('#forms-pjax a:not([data-pjax="false"])', {
        container: '#forms-pjax',
        fragment: '#forms-pjax',
        push: true
    });

    $(document).on('pjax:success', function (e, a, b, c, d) {
        _pjaxUrls[d.container] = d.url;
    });

    /**
     * Reloads file versions grid.
     */
    function reloadGrid(selector) {
        $.pjax({
            container: selector,
            fragment: selector,
            url: _pjaxUrls[selector],
            push: false,
        });
    }

    // This handler will trigger on-demand update of `pjax` containers
    $(document).on('click', '[data-toggle="pjax"]', function (e) {
        e.preventDefault();
        reloadGrid($(this).data('target'));
    });

    // Toggle action activation
    $(document).on('click', '[data-toggle="action"]', function (e) {
        e.preventDefault();
        let self = $(this),
            $modal = $('#confirm-modal'),
            formSelector = '#confirm-form',
            container = '#' + self.parents('[data-pjax-container]').attr('id');

        if (self.data('confirm')) {
            // Showing confirmation modal
            $modal.Modal().show();

            // Remove previously appended selections
            $(formSelector).find('[name="selection[]"]').remove();

            // Append selected items to confirm form
            let $selected = $(container).find('[name="selection[]"]:checked').clone();
            $(formSelector).append($selected.prop('type', 'hidden'));

            // Setting up confirmation form
            $(formSelector).attr({
                'method': self.data('http-method') || 'post',
                'action': self.attr('href')
            });

            $modal
                .off('afterSubmit.Form', formSelector)
                .on('afterSubmit.Form', formSelector, function () {
                    // Reload versions table content
                    reloadGrid(container);

                    // Close modal window
                    $modal.Modal().hide();
                });
        }
    });

    // This handler will trigger after `#forms-modal` loads
    $(document).on('loaded.Modal', '#forms-modal', function (e) {
        let $modal = $(e.currentTarget),
            $active = $('#form-active'),
            $useTemplate = $('#form-template_active'),
            $templateField = $('#form-template'),
            $dtPickerFields = $('.field-form-active_dates input[type="text"]'),
            state = $active.is(':checked'),
            dateTimeFormat = 'DD.MM.YYYY HH:mm';

        // Enable interactive form
        $('#forms-form').Form();

        // Enable tabs
        $('[data-toggle="tab"]').tabs();

        // Enable datepicker
        $dtPickerFields.DateTimePicker({'format': dateTimeFormat})
            .attr('disabled', !state)
            .closest('.form-group')
            .addClass('form-group__dt-picker')
            .toggleClass('disabled', !state);

        $active.on('change', function () {
            let state = $(this).is(':checked');
            $dtPickerFields.attr('disabled', !state).closest('.form-group').toggleClass('disabled', !state);
        });

        state = $useTemplate.is(':checked');
        $templateField.attr('disabled', !state)
            .closest('.form-group')
            .toggleClass('disabled', !state);

        $useTemplate.on('change', function () {
            let state = $(this).is(':checked');
            $templateField.attr('disabled', !state).closest('.form-group').toggleClass('disabled', !state);
        });

        $modal
            .off('change', '#form-event:hidden')
            .on('change', '#form-event:hidden', function (e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).data('url'),
                    data: {'type_uuid': $(this).val()},
                    success: function (response) {
                        let input = $('#form-mail_template_uuid'),
                            dropdown = input.closest('.form-group').find('ul.dropdown');

                        // Clearing values
                        input.val('');
                        dropdown.find('li').remove();

                        for (let i in response) {
                            if (response.hasOwnProperty(i)) {
                                dropdown.append($('<li>').append(
                                    $('<a>').attr({'href': '#', 'data-value': i}).text(response[i])
                                ));
                            }
                        }
                    }
                });
            });

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#forms-form')
            .on('afterSubmit.Form', '#forms-form', function () {
                // Close modal window
                $modal.Modal().hide();
            });
    });

    // This handler will trigger after `#forms-modal` was hidden
    $(document).on('hidden.Modal', '#forms-modal', function () {
        reloadGrid('#forms-pjax');
    });

    // This handler will trigger after `#fields-modal` loads
    $(document).on('loaded.Modal', '#fields-modal', function (e) {
        let $modal = $(e.currentTarget),
            maxHeight = Math.max.apply(Math, $modal.find('.tabs-pane').map(function () {
                return $(this).outerHeight();
            }));

        $modal.find('.tabs-pane').css({'min-height': maxHeight + 'px'});

        // Enable interactive form
        $('#fields-form').Form();

        // Enable tabs
        $modal.find('[data-toggle="tab"]').tabs();

        // Trigger on change field type
        $modal.on('change', '#field-type:hidden', function () {
            let self = $(this),
                value = self.val(),
                parent = self.closest('.tabs'),
                condition = value < 3 || value > 4;

            parent.find('ul.tabs-nav [data-values]').toggleClass('disabled', condition);
            parent.find('[name="Field[multiple]"]:checkbox')
                .attr('disabled', condition)
                .closest('.form-group').toggleClass('disabled', condition);
            parent.find('[name="Field[list]"]:checkbox')
                .attr('disabled', parseInt(value) !== 1)
                .closest('.form-group').toggleClass('disabled', parseInt(value) !== 1);
        });

        $modal.find('#field-type:hidden').trigger('change');

        // $(document).on('confirm.success', '[data-toggle="confirm"]', function () {
        //     $('#forms-fields-validators-toolbar').find('[data-trigger="refresh"]').trigger('click');
        //     $('#forms-fields-values-toolbar').find('[data-trigger="refresh"]').trigger('click');
        // });

        $('#field-type').trigger('change');

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#fields-form')
            .on('afterSubmit.Form', '#fields-form', function () {
                // Close modal window
                $modal.Modal().hide();
            });
    });

    // This handler will trigger after `#fields-modal` was hidden
    $(document).on('hidden.Modal', '#fields-modal', function () {
        reloadGrid('#fields-pjax');
    });

    // This handler will trigger after `#settings-modal` loads
    $(document).on('loaded.Modal', '#settings-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $('#settings-form').Form();

        $modal.on('click', 'button:submit', function (e) {
            e.preventDefault();

            $modal.find('[name="action"]:hidden').val($(this).val());
            $modal.find('form').submit();
        });

        // After submit form handler
        $modal.on('afterSubmit.Form', '#settings-form', function () {
            // Reload forms table content
            $.pjax.reload('#forms-pjax');

            // Close modal window
            $modal.Modal().hide();
        });
    });

    // This handler will trigger after `#filter-modal` loads
    $(document).on('loaded.Modal', '#filter-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $('#filter-form').Form();

        $modal
            .off('click', 'button:submit,button:reset')
            .on('click', 'button:submit,button:reset', function (e) {
                e.preventDefault();

                $modal.find('[name="action"]:hidden').val($(this).val());
                $modal.find('form').submit();
            });

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#filter-form')
            .on('afterSubmit.Form', '#filter-form', function (e) {
                // Reload storage table content
                $.pjax({
                    container: '#forms-pjax',
                    fragment: '#forms-pjax',
                    url: e.response.url,
                    push: true,
                });

                // Close modal window
                $modal.Modal().hide();
            });
    });

    let modals = ['#statuses-modal', '#results-modal', '#values-modal', '#validators-modal'];

    // This handler will trigger after modal loads
    $(document).on('loaded.Modal', modals.join(','), function (e) {
        let $modal = $(e.currentTarget),
            name = $modal.get(0).id.split('-')[0];

        // Enable interactive form
        $('#' + name + '-form').Form();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#' + name + '-form')
            .on('afterSubmit.Form', '#' + name + '-form', function () {
                // Reload parent table content
                reloadGrid('#' + name + '-pjax');

                // Close modal window
                $modal.Modal().hide();
            });
    });

    // This handler will trigger after `#confirm-modal` was shown
    $(document).on('shown.Modal', '#confirm-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Set focus to password field
        $modal.find('input:password').val('').focus();
        $modal.find('.form-group.error').toggleClass('error', false);
        $modal.find('.form-group__error').html('');
    });

    // This handler will trigger after `#results-modal` was loaded
    $(document).on('loaded.Modal', '#results-modal', function (e) {
        $(e.currentTarget).find('.modal__body').css({'max-height': ($(window).height() * .75) + 'px'});
    });

    // This handler will trigger after listed tabs` content loaded
    $(document).on('loaded.Tabs', '[data-remote]', function (e) {
        let $pjaxContainer = $(e.target).find('[data-pjax-container]'), selector;

        if ($pjaxContainer.length === 0) {
            return;
        }

        selector = '#' + $pjaxContainer.get(0).id;

        $(document).pjax(selector + ' a:not([data-pjax="false"])', {
            container: selector,
            fragment: selector,
            push: false
        });

        _pjaxUrls[selector] = $(selector).data('pjax-url');
    });
});
