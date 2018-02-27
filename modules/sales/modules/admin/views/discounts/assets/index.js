$(function () {
    "use strict";

    let grid = $('#discounts-pjax').grid();

    // This handler will trigger after `#discounts-modal` loads
    $(document).on('loaded.Modal', '#discounts-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $modal.find('#discounts-form').Form();

        // Set focus on first field
        $modal.find('input:text:first').focus();

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
