$(function () {
    "use strict";

    let grid = $('#roles-pjax').grid();

    // This handler will trigger after `#roles-modal` loads
    $(document).on('loaded.Modal', '#roles-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $('#roles-form').Form();

        // Enable field selector
        $('[data-toggle="field-selector"]').fieldSelector();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#roles-form')
            .on('afterSubmit.Form', '#roles-form', function () {
                // Close modal window
                $modal.Modal().hide();
                // Reload grid
                grid.reload('#roles-pjax')
            });
    });
});
