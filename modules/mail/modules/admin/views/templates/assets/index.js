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

        let cm = {};

        $modal.on('click', '#template-format a', function (e) {
            e.preventDefault();

            let value = $(this).data('value'),
                fields = ['textBody', 'htmlBody'];

            for (let i = 0; i < fields.length; i++) {
                $('#template-format').find('[data-value="' + fields[i] + '"]').parent().toggleClass('active', value === fields[i]);
                $modal.find('.field-template-' + fields[i].toLowerCase()).toggleClass('form-group_hidden', value !== fields[i]);

                cm[i].refresh();
                cm[i].focus();
            }
        });

        // Enable code editor
        $modal.find('[data-toggle="editor"]').each(function (index, item) {
            cm[index] = CodeMirror.fromTextArea(item, {
                lineNumbers: true,
                lineWrapping: true
            });

            cm[index].on('blur', function () {
                cm[index].save();
            });
        });

        $modal.on('shown.Tabs', '#content', function () {
            cm[0].refresh();
            cm[0].focus();
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
