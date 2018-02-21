$(function () {
    "use strict";

    let grid = $('#addresses-pjax').grid();

    // This handler will trigger after `#addresses-modal` loads
    $(document).on('loaded.Modal', '#addresses-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $modal.find('#addresses-form').Form();

        // Enable dropdowns
        $modal.find('.form-group_dropdown input:text').dropDownInput();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#addresses-form')
            .on('afterSubmit.Form', '#addresses-form', function () {
                // Close modal window
                $modal.Modal().hide();

                // Reload grid view
                grid.reload('#addresses-pjax');
            });
    });
});
