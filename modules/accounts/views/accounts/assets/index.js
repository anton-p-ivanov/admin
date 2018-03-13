$(function () {
    "use strict";

    let grid = $('#accounts-pjax').grid();

    // This handler will trigger after `#accounts-modal` loads
    $(document).on('loaded.Modal', '#accounts-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $modal.find('#accounts-form').Form();

        // Enable tabs
        $modal.find('[data-toggle="tab"]').tabs();

        // Enable dropdowns
        $modal.find('.form-group_dropdown input:text').dropDownInput();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#accounts-form')
            .on('afterSubmit.Form', '#accounts-form', function () {
                // Close modal window
                $modal.Modal().hide();

                // Reload main grid
                grid.reload('#accounts-pjax');
            });
    });
});
