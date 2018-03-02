$(function () {
    "use strict";

    let grid = $('#lessons-pjax').grid();

    // This handler will trigger after `#lessons-modal` loads
    $(document).on('loaded.Modal', '#lessons-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $modal.find('#lessons-form').Form();

        // Enable tabs
        $modal.find('[data-toggle="tab"]').tabs();

        // Enable dropdowns
        $modal.find('.form-group_dropdown input:text').dropDownInput();

        // Focus on first input
        $modal.find('input:text:first').focus();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#lessons-form')
            .on('afterSubmit.Form', '#lessons-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#lessons-pjax');
            });
    });
});
