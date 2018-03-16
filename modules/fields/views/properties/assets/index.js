$(function () {
    "use strict";

    let grid = $('#properties-pjax').grid();

    // This handler will trigger after modal loads
    $(document).on('loaded.Modal', '#property-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $modal.find('#property-form').Form();

        // Set focus
        $modal.find('input:text:first').focus();

        // Enable dropdowns
        $modal.find('.form-group_dropdown > .form-group__input').dropDownInput();

        // Enable code editor
        $modal.find('[data-toggle="editor"]').each(function (index, item) {
            let cm = CodeMirror.fromTextArea(item, {
                    lineNumbers: true,
                    lineWrapping: true
                });

            cm.on('blur', function () {
                cm.save();
            });
        });

        $(document).on('click.select', '[data-toggle="select"]', function () {
            let selected = $(this).data('value');

            $.ajax({
                url: 'get-file-info',
                type: 'GET',
                data: {'tree_uuid': selected.uuid},
                success: function (response) {
                    $modal.find('.file-info').toggleClass('file-info_empty', false).html(response);
                    $('#resultproperty-value').val($(response).find('[data-file]').data('file'));
                }
            });
        });

        $modal.on('click', '.file-info__clear', function (e) {
            e.preventDefault();

            $modal.find('.file-info').toggleClass('file-info_empty', true).html('<em class="text_center">No file selected. Click "Select" button<br>to select file from library.</em>');
            $('#resultproperty-value').val('');
        });

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#property-form')
            .on('afterSubmit.Form', '#property-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#properties-pjax');
            });
    });

});
