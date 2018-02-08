$(function () {
    "use strict";

    let grid = $('#sites-pjax').grid();

    // This handler will trigger after `#sites-modal` loads
    $(document).on('loaded.Modal', '#sites-modal', function (e) {
        let $modal = $(e.currentTarget),
            $dtPickerFields = $('.field-usersite-active_dates input[type="text"]'),
            dateTimeFormat = 'DD.MM.YYYY HH:mm';

        // Enable interactive form
        $('#sites-form').Form();

        // Enable datepicker
        $dtPickerFields.DateTimePicker({'format': dateTimeFormat})
            .closest('.form-group')
            .addClass('form-group__dt-picker');

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#sites-form')
            .on('afterSubmit.Form', '#sites-form', function () {
                // Close modal window
                $modal.Modal().hide();
                // Reload grid
                grid.reload('#sites-pjax')
            });
    });
});
