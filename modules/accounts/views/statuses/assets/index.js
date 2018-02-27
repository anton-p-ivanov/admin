$(function () {
    "use strict";

    let grid = $('#statuses-pjax').grid();

    // This handler will trigger after `#statuses-modal` loads
    $(document).on('loaded.Modal', '#statuses-modal', function (e) {
        let $modal = $(e.currentTarget),
            $dtPickerFields = $modal.find('.field-accountstatus-dates input[type="text"]'),
            dateTimeFormat = 'DD.MM.YYYY HH:mm';

        // Enable interactive form
        $modal.find('#statuses-form').Form();

        // Enable datepicker
        $dtPickerFields.DateTimePicker({'format': dateTimeFormat});

        // Enable dropdowns
        $modal.find('#accountstatus-status_uuid').dropDownInput();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#statuses-form')
            .on('afterSubmit.Form', '#statuses-form', function () {
                // Close modal window
                $modal.Modal().hide();

                // Reload grid view
                grid.reload('#statuses-pjax');
            });
    });
});
