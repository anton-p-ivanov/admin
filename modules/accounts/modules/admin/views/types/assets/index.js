$(function () {
    "use strict";

    let grid = $('#types-pjax').grid();

    // This handler will trigger after `#types-modal` loads
    $(document).on('loaded.Modal', '#types-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $('#types-form').Form();

        // Enable field selector
        $('[data-toggle="field-selector"]').fieldSelector();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#types-form')
            .on('afterSubmit.Form', '#types-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#types-pjax');
            });
    });
});
