$(function () {
    "use strict";

    let grid = $('#properties-pjax').grid();

    // This handler will trigger after modal loads
    $(document).on('loaded.Modal', '#property-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $modal.find('#property-form').Form();

        // Set focus
        $modal.find('input:text:first').focus();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#property-form')
            .on('afterSubmit.Form', '#property-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#properties-pjax');
            });
    });
});
