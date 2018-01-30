$(function () {
    "use strict";

    let grid = $('#validators-pjax').grid();

    // This handler will trigger after `#validators-modal` loads
    $(document).on('loaded.Modal', '#validators-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $('#validators-form').Form();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#validators-form')
            .on('afterSubmit.Form', '#validators-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#validators-pjax');
            });
    });
});
