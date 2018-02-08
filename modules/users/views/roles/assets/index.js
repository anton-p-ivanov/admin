$(function () {
    "use strict";

    let grid = $('#roles-pjax').grid();

    // This handler will trigger after `#roles-modal` loads
    $(document).on('loaded.Modal', '#roles-modal', function (e) {
        let $modal = $(e.currentTarget),
            $dtPickerFields = $('.field-userrole-valid_dates input[type="text"]'),
            dateTimeFormat = 'DD.MM.YYYY HH:mm';

        // Enable interactive form
        $('#roles-form').Form();

        // Enable datepicker
        $dtPickerFields.DateTimePicker({'format': dateTimeFormat})
            .closest('.form-group')
            .addClass('form-group__dt-picker');

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#roles-form')
            .on('afterSubmit.Form', '#roles-form', function () {
                // Close modal window
                $modal.Modal().hide();
                // Reload grid
                grid.reload('#roles-pjax')
            });
    });
});
