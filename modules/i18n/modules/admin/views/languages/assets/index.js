$(function () {
    "use strict";

    let grid = $('#languages-pjax').grid();

    // This handler will trigger after `#languages-modal` loads
    $(document).on('loaded.Modal', '#languages-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $('#languages-form').Form();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#languages-form')
            .on('afterSubmit.Form', '#languages-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#languages-pjax');
            });
    });
});
