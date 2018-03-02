$(function () {
    "use strict";

    let grid = $('#questions-pjax').grid();

    // This handler will trigger after `#questions-modal` loads
    $(document).on('loaded.Modal', '#questions-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $modal.find('#questions-form').Form();

        // Enable tabs
        $modal.find('[data-toggle="tab"]').tabs();

        // Enable dropdowns
        $modal.find('.form-group_dropdown input:text').dropDownInput();

        // Focus on first input
        $modal.find('textarea:first').focus();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#questions-form')
            .on('afterSubmit.Form', '#questions-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#questions-pjax');
            });
    });
});
