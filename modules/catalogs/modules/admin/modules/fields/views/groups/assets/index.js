$(function () {
    "use strict";

    let grid = $('#groups-pjax').grid();

    // This handler will trigger after `#groups-modal` loads
    $(document).on('loaded.Modal', '#groups-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $modal.find('#groups-form').Form();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#groups-form')
            .on('afterSubmit.Form', '#groups-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#groups-pjax');
            });
    });
});
