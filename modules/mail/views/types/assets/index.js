$(function () {
    "use strict";

    let _pjaxUrls = {'#types-pjax': window.location.href};

    // Enable PJAX for main grid
    $(document).pjax('#types-pjax a:not([data-pjax="false"])', {
        container: '#types-pjax',
        fragment: '#types-pjax',
        push: true
    });

    $(document).on('pjax:success', function (e, a, b, c, d) {
        _pjaxUrls[d.container] = d.url;
    });

    /**
     * Reloads file versions grid.
     */
    function reloadGrid(selector) {
        $.pjax({
            container: selector,
            fragment: selector,
            url: _pjaxUrls[selector],
            push: false,
        });
    }

    // This handler will trigger on-demand update of `pjax` containers
    $(document).on('click', '[data-toggle="pjax"]', function (e) {
        e.preventDefault();
        reloadGrid($(this).data('target'));
    });

    // Toggle action activation
    $(document).on('click', '[data-toggle="action"]', function (e) {
        e.preventDefault();
        let self = $(this),
            $modal = $('#confirm-modal'),
            formSelector = '#confirm-form',
            container = '#' + self.parents('[data-pjax-container]').attr('id');

        if (self.data('confirm')) {
            // Showing confirmation modal
            $modal.Modal().show();

            // Remove previously appended selections
            $(formSelector).find('[name="selection[]"]').remove();

            // Append selected items to confirm form
            let $selected = $(container).find('[name="selection[]"]:checked').clone();
            $(formSelector).append($selected.prop('type', 'hidden'));

            // Setting up confirmation form
            $(formSelector).attr({
                'method': self.data('http-method') || 'post',
                'action': self.attr('href')
            });

            $modal
                .off('afterSubmit.Form', formSelector)
                .on('afterSubmit.Form', formSelector, function () {
                    // Reload versions table content
                    reloadGrid(container);

                    // Close modal window
                    $modal.Modal().hide();
                });
        }
    });

    // This handler will trigger after `#types-modal` loads
    $(document).on('loaded.Modal', '#types-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $('#types-form').Form();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#types-form')
            .on('afterSubmit.Form', '#types-form', function () {
                // Close modal window
                $modal.Modal().hide();

                reloadGrid('#types-pjax');
            });
    });

    // This handler will trigger after `#settings-modal` loads
    $(document).on('loaded.Modal', '#settings-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $('#settings-form').Form();

        $modal.on('click', 'button:submit', function (e) {
            e.preventDefault();

            $modal.find('[name="action"]:hidden').val($(this).val());
            $modal.find('form').submit();
        });

        // After submit form handler
        $modal.on('afterSubmit.Form', '#settings-form', function () {
            // Reload forms table content
            reloadGrid('#types-pjax');

            // Close modal window
            $modal.Modal().hide();
        });
    });

    // This handler will trigger after `#filter-modal` loads
    $(document).on('loaded.Modal', '#filter-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $('#filter-form').Form();

        $modal
            .off('click', 'button:submit,button:reset')
            .on('click', 'button:submit,button:reset', function (e) {
                e.preventDefault();

                $modal.find('[name="action"]:hidden').val($(this).val());
                $modal.find('form').submit();
            });

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#filter-form')
            .on('afterSubmit.Form', '#filter-form', function (e) {
                // Reload storage table content
                $.pjax({
                    container: '#types-pjax',
                    fragment: '#types-pjax',
                    url: e.response.url,
                    push: true,
                });

                // Close modal window
                $modal.Modal().hide();
            });
    });

    // This handler will trigger after `#confirm-modal` was shown
    $(document).on('shown.Modal', '#confirm-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Set focus to password field
        $modal.find('input:password').val('').focus();
        $modal.find('.form-group.error').toggleClass('error', false);
        $modal.find('.form-group__error').html('');
    });
});
