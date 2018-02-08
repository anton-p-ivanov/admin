$(function () {
    "use strict";

    let grid = $('#accounts-pjax').grid();

    // This handler will trigger after `#accounts-modal` loads
    $(document).on('loaded.Modal', '#accounts-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $('#accounts-form').Form();

        // Enable dropdowns
        $modal.find('[data-type-ahead]').dropDownInput();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#accounts-form')
            .on('afterSubmit.Form', '#accounts-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#accounts-pjax');
            });
    });
});
