$(function () {
    "use strict";

    let grid = $('#catalogs-pjax').grid();

    // This handler will trigger after `#catalogs-modal` loads
    $(document).on('loaded.Modal', '#catalogs-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $modal.find('#catalogs-form').Form();

        // Enable tabs
        $modal.find('[data-toggle="tab"]').tabs();

        // Enable field selector
        $('[data-toggle="field-selector"]').fieldSelector();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#catalogs-form')
            .on('afterSubmit.Form', '#catalogs-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#catalogs-pjax');
            });
    });
});
