$(function () {
    "use strict";

    let grid = $('#fields-pjax').grid();

    // This handler will trigger after `#fields-modal` loads
    $(document).on('loaded.Modal', '#fields-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $('#fields-form').Form();

        // Enable tabs
        $('[data-toggle="tab"]').tabs();

        // Trigger on change field type
        $modal.on('change', '#field-type:hidden', function () {
            let self = $(this),
                value = self.val(),
                parent = self.closest('.tabs'),
                condition = value < 3 || value > 4;

            parent.find('[name="Field[multiple]"]:checkbox')
                .attr('disabled', condition)
                .closest('.form-group').toggleClass('disabled', condition);
            parent.find('[name="Field[list]"]:checkbox')
                .attr('disabled', parseInt(value) !== 1)
                .closest('.form-group').toggleClass('disabled', parseInt(value) !== 1);
        });

        $modal.find('#field-type:hidden').trigger('change');

        $('#field-type').trigger('change');

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#fields-form')
            .on('afterSubmit.Form', '#fields-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#fields-pjax');
            });
    });
});
