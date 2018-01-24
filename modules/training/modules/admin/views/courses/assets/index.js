$(function () {
    "use strict";

    let grid = $('#courses-pjax').grid();

    // This handler will trigger after `#courses-modal` loads
    $(document).on('loaded.Modal', '#courses-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $modal.find('#courses-form').Form();

        // Enable tabs
        $modal.find('[data-toggle="tab"]').tabs();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#courses-form')
            .on('afterSubmit.Form', '#courses-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#courses-pjax');
            });
    });
});
