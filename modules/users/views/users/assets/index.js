$(function () {
    "use strict";

    let grid = $('#users-pjax').grid();

    // This handler will trigger after `#users-modal` loads
    $(document).on('loaded.Modal', '#users-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $('#users-form').Form();

        // Enable tabs
        $('[data-toggle="tab"]').tabs();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#users-form')
            .on('afterSubmit.Form', '#users-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#users-pjax');
            });
    });

    // This handler will trigger after `#passwords-modal` loads
    $(document).on('loaded.Modal', '#passwords-modal', function (e) {
        let $modal = $(e.currentTarget),
            form = '#passwords-form',
            $dtPickerFields = $('#userpassword-expired_date'),
            dateTimeFormat = 'DD.MM.YYYY HH:mm';

        // Enable interactive form
        $(form).Form();

        // Enable datepicker
        $dtPickerFields.DateTimePicker({'format': dateTimeFormat})
            .closest('.form-group')
            .addClass('form-group__dt-picker');

        // After submit form handler
        $modal
            .off('afterSubmit.Form', form)
            .on('afterSubmit.Form', form, function () {
                // Close modal window
                $modal.Modal().hide();
            });
    });

    //
    //
    // // This handler will trigger after `#data-modal` loads
    // $(document).on('loaded.Modal', '#data-modal', function (e) {
    //     let $modal = $(e.currentTarget),
    //         form = '#data-form',
    //         $dtPickerFields = $('[data-picker="date"],[data-picker="time"],[data-picker="datetime"]'),
    //         dateFormat = 'DD.MM.YYYY',
    //         timeFormat = 'HH:mm';
    //
    //     // Enable interactive form
    //     $(form).Form();
    //
    //     // Enable datepicker
    //     $dtPickerFields.each(function (index, picker) {
    //         $(picker).DateTimePicker({
    //             'type': $(picker).data('picker'),
    //             'format': {
    //                 'date': $(picker).data('date-format') || dateFormat,
    //                 'time': $(picker).data('time-format') || timeFormat
    //             }
    //         })
    //         .closest('.form-group')
    //         .addClass('form-group__dt-picker');
    //     });
    //
    //     // Enable dropdowns
    //     $modal.find('[data-type-ahead]').dropDownInput();
    //
    //     // After submit form handler
    //     $modal
    //         .off('afterSubmit.Form', form)
    //         .on('afterSubmit.Form', form, function () {
    //             // Reload view
    //             grid.reload('#data-pjax');
    //             // Close modal window
    //             $modal.Modal().hide();
    //         });
    // });
});
