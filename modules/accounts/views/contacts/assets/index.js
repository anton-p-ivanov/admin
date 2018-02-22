$(function () {
    "use strict";

    let grid = $('#contacts-pjax').grid();

    // This handler will trigger after `#contacts-modal` loads
    $(document).on('loaded.Modal', '#contacts-modal', function (e) {
        let $modal = $(e.currentTarget),
            prefix = '#accountcontact';

        // Enable interactive form
        $modal.find('#contacts-form').Form();

        // Enable dropdowns
        $modal.find('[data-type-ahead]').dropDownInput();

        // Updating fields with user account selected
        $modal.on('change', '[name="AccountContact[user_uuid]"]:hidden', function() {
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
});
