$(function () {
    "use strict";

    let grid = $('#results-pjax').grid();

    // This handler will trigger after `#results-modal` loads
    $(document).on('loaded.Modal', '#results-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $modal.find('#results-form').Form();

        // Enable dropdowns
        $modal.find('.form-group_dropdown input:text').dropDownInput();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#results-form')
            .on('afterSubmit.Form', '#results-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#results-pjax');
            });
    });
});
