$(function () {
    "use strict";

    let grid = $('#statuses-pjax').grid();

    // This handler will trigger after `#statuses-modal` loads
    $(document).on('loaded.Modal', '#statuses-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $('#statuses-form').Form();

        // Enable field selector
        $('[data-toggle="field-selector"]').fieldSelector();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#statuses-form')
            .on('afterSubmit.Form', '#statuses-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#statuses-pjax');
            });
    });
});
