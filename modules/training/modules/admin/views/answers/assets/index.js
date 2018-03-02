$(function () {
    "use strict";

    let grid = $('#answers-pjax').grid();

    // This handler will trigger after `#answers-modal` loads
    $(document).on('loaded.Modal', '#answers-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $modal.find('#answers-form').Form();

        // Focus on first input
        $modal.find('text:input:first').focus();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#answers-form')
            .on('afterSubmit.Form', '#answers-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#answers-pjax');
            });
    });
});
