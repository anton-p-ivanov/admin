$(function () {
    "use strict";

    let grid = $('#sites-pjax').grid();

    // This handler will trigger after `#sites-modal` loads
    $(document).on('loaded.Modal', '#sites-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $('#sites-form').Form();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#sites-form')
            .on('afterSubmit.Form', '#sites-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#sites-pjax');
            });
    });
});
