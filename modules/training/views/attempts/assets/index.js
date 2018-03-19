$(function () {
    "use strict";

    let grid = $('#attempts-pjax').grid();

    // This handler will trigger after `#attempts-modal` loads
    $(document).on('loaded.Modal', '#attempts-modal', function (e) {
        let $modal = $(e.currentTarget),
            $dtPickerFields = $('.field-attempt-dates input[type="text"]'),
            dateTimeFormat = 'DD.MM.YYYY HH:mm';

        // Enable interactive form
        $modal.find('#attempts-form').Form();

        // Enable dropdowns
        $modal.find('[data-type-ahead]').dropDownInput();

        // Enable datepicker
        $dtPickerFields.DateTimePicker({'format': dateTimeFormat});

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#attempts-form')
            .on('afterSubmit.Form', '#attempts-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#attempts-pjax');
            });
    });
});
