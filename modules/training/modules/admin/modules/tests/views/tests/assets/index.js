$(function () {
    "use strict";

    let grid = $('#tests-pjax').grid();

    // This handler will trigger after `#tests-modal` loads
    $(document).on('loaded.Modal', '#tests-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $modal.find('#tests-form').Form();

        // Enable tabs
        $modal.find('[data-toggle="tab"]').tabs();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#tests-form')
            .on('afterSubmit.Form', '#tests-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#tests-pjax');
            });
    });
});
