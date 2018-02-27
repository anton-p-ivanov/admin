$(function () {
    "use strict";

    let grid = $('#discounts-pjax').grid();

    // This handler will trigger after `#discounts-modal` loads
    $(document).on('loaded.Modal', '#discounts-modal', function (e) {
        let $modal = $(e.currentTarget),
            $dtPickerFields = $modal.find('.field-accountdiscount-dates input[type="text"]'),
            dateTimeFormat = 'DD.MM.YYYY HH:mm';

        // Enable interactive form
        $modal.find('#discounts-form').Form();

        // Enable datepicker
        $dtPickerFields.DateTimePicker({'format': dateTimeFormat});

        // Enable dropdowns
        $modal.find('#accountdiscount-discount_uuid').dropDownInput();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#discounts-form')
            .on('afterSubmit.Form', '#discounts-form', function () {
                // Close modal window
                $modal.Modal().hide();

                // Reload grid view
                grid.reload('#discounts-pjax');
            });
    });
});
