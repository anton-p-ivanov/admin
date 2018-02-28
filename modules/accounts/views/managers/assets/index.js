$(function () {
    "use strict";

    let grid = $('#managers-pjax').grid();

    // This handler will trigger after `#managers-modal` loads
    $(document).on('loaded.Modal', '#managers-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $modal.find('#managers-form').Form();

        // Enable dropdowns
        $modal.find('[data-type-ahead]').dropDownInput();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#managers-form')
            .on('afterSubmit.Form', '#managers-form', function () {
                // Close modal window
                $modal.Modal().hide();

                // Reload grid view
                grid.reload('#managers-pjax');
            });
    });
});
