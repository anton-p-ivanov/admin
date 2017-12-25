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
        $modal.find('[data-type-ahead]').dropDownInput();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#accounts-form')
            .on('afterSubmit.Form', '#accounts-form', function (e) {
                if (e.response.hasOwnProperty('url')) {
                    // Update modal content
                    $modal.load(e.response.url, function () {
                        $modal.trigger('loaded.Modal');
                        grid.reload('#accounts-pjax');
                    });
                }
                else {
                    // Close modal window
                    $modal.Modal().hide();
                }
            });

        $('[data-target="#partnership"]:eq(0)').trigger('click');
    });

    // This handler will trigger after `#forms-modal` was hidden
    $(document).on('hidden.Modal', '#accounts-modal', function () {
        grid.reload('#accounts-pjax');
    });

    // This handler will trigger after `#contacts-modal` loads
    $(document).on('loaded.Modal', '#contacts-modal', function (e) {
        let $modal = $(e.currentTarget),
            prefix = '#accountcontact';

        // Enable interactive form
        $modal.find('#contacts-form').Form();

        // Enable dropdowns
        $modal.find('[data-type-ahead]').dropDownInput();

        // Updating fields with user account selected
        $modal.on('change', prefix + '-user_uuid', function() {
            let value = $(this).val(),
                url = '/users/users/get';

            if (value) {
                $.getJSON(url, {'uuid': value}, function (data) {
                    if (!$.isEmptyObject(data)) {
                        $(prefix + '-fullname').val(data['fullname']).prop('readonly', true).trigger('focus');
                        $(prefix + '-email').val(data['email']).prop('readonly', true).trigger('focus');
                    }
                });
            }
            else {
                $(prefix + '-fullname').prop('readonly', false);
                $(prefix + '-email').prop('readonly', false);
            }
        });

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#contacts-form')
            .on('afterSubmit.Form', '#contacts-form', function () {
                // Close modal window
                $modal.Modal().hide();

                // Reload grid view
                grid.reload('#contacts-pjax');
            });
    });

    // This handler will trigger after `#addresses-modal` loads
    $(document).on('loaded.Modal', '#addresses-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $modal.find('#addresses-form').Form();

        // Enable dropdowns
        $modal.find('[data-type-ahead]').dropDownInput();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#addresses-form')
            .on('afterSubmit.Form', '#addresses-form', function (e) {
                // Close modal window
                $modal.Modal().hide();

                // Reload grid view
                grid.reload('#addresses-pjax');
            });
    });

    // This handler will trigger after `#data-modal` loads
    $(document).on('loaded.Modal', '#data-modal', function (e) {
        let $modal = $(e.currentTarget),
            form = '#data-form',
            $dtPickerFields = $('[data-picker="date"],[data-picker="time"],[data-picker="datetime"]'),
            dateFormat = 'DD.MM.YYYY',
            timeFormat = 'HH:mm';

        // Enable interactive form
        $(form).Form();

        // Enable datepicker
        $dtPickerFields.each(function (index, picker) {
            $(picker).DateTimePicker({
                'type': $(picker).data('picker'),
                'format': {
                    'date': $(picker).data('date-format') || dateFormat,
                    'time': $(picker).data('time-format') || timeFormat
                }
            })
                .closest('.form-group')
                .addClass('form-group__dt-picker');
        });

        // Enable dropdowns
        $modal.find('[data-type-ahead]').dropDownInput();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', form)
            .on('afterSubmit.Form', form, function () {
                // Reload view
                grid.reload('#data-pjax');
                // Close modal window
                $modal.Modal().hide();
            });
    });

    $('[data-target="#accounts-modal"]:eq(1)').trigger('click');
});
