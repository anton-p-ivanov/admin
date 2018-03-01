$(function () {
    "use strict";

    let grid = $('#users-pjax').grid();

    // This handler will trigger after `#users-modal` loads
    $(document).on('loaded.Modal', '#users-modal', function (e) {
        let $modal = $(e.currentTarget),
            $dtPickerFields = $('#userpassword-expired_date'),
            dateTimeFormat = 'DD.MM.YYYY HH:mm';

        // Enable interactive form
        $('#users-form').Form();

        // Enable tabs
        $('[data-toggle="tab"]').tabs();

        // Enable datepicker
        $dtPickerFields.DateTimePicker({'type': 'date', 'format': dateTimeFormat});

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#users-form')
            .on('afterSubmit.Form', '#users-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#users-pjax');
            });
    });
});
