$(function () {
    "use strict";

    let grid = $('#values-pjax').grid();

    // This handler will trigger after `#values-modal` loads
    $(document).on('loaded.Modal', '#values-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $('#values-form').Form();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#values-form')
            .on('afterSubmit.Form', '#values-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#values-pjax');
            });
    });
});
