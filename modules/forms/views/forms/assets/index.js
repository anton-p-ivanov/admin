$(function () {
    "use strict";

    let grid = $('#forms-pjax').grid();

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
        grid.reload('#forms-pjax');
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
        grid.reload('#fields-pjax');
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
                grid.reload('#' + name + '-pjax');

                // Close modal window
                $modal.Modal().hide();
            });
    });

    // This handler will trigger after `#results-modal` was loaded
    $(document).on('loaded.Modal', '#results-modal', function (e) {
        $(e.currentTarget).find('.modal__body').css({'max-height': ($(window).height() * .75) + 'px'});
    });
});
