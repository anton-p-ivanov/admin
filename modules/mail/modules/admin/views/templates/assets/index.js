$(function () {
    "use strict";

    let grid = $('#templates-pjax').grid();

    // This handler will trigger after `#templates-modal` loads
    $(document).on('loaded.Modal', '#templates-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $('#templates-form').Form();

        // Enable tabs
        $modal.find('[data-toggle="tab"]').tabs();

        // Enable field selector
        $modal.find('[data-toggle="field-selector"]').fieldSelector();

        // Enable dropdowns
        $modal.find('.form-group_dropdown input:text').dropDownInput();

        // Focus on element
        $('#template-subject').focus();

        $modal.on('change', '#template-format input:radio', function () {
            let value = $modal.find('#template-format input:checked').val(),
                fields = ['text', 'html'];

            for (let i = 0; i < fields.length; i++) {
                $modal.find('.field-template-' + fields[i]).toggleClass('form-group_hidden', value !== fields[i]);
            }
        });

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#templates-form')
            .on('afterSubmit.Form', '#templates-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#templates-pjax');
            });
    });
});
