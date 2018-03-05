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
        $modal.find('[data-toggle="tab"]').tabs();

        // Enable dropdowns
        $modal.find('.form-group_dropdown input:text').dropDownInput();

        // Focus on element
        $('#form-title').focus();

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
            .off('change', '#form-event ~ input:hidden')
            .on('change', '#form-event ~ input:hidden', function (e) {
                e.preventDefault();

                let value = $(this).val(),
                    input = $('#form-mail_template_uuid'),
                    dropdown = input.closest('.form-group').find('ul.dropdown');

                if (!value) {
                    input.val('').attr('disabled', true);
                    dropdown.find('li').remove();
                }
                else {
                    $.ajax({
                        url: $(this).data('url'),
                        data: {'type_uuid': $(this).val()},
                        success: function (response) {
                            // Clearing values
                            input.val('').attr('disabled', false);
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
                }
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
});
