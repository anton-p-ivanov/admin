$(function () {
    "use strict";

    let grid = $('#storage-pjax').grid();

    // This handler will trigger after `#locations-modal` loads
    $(document).on('loaded.Modal', '#locations-modal', function (e) {

        let $modal = $(e.currentTarget),
            methods = {
                // Set modal body maximum height
                'setModalHeight': function () {
                    $modal.find('.modal__body').css({'max-height': (
                            $modal.find('.modal__container').outerHeight() -
                            $modal.find('.modal__header').outerHeight() -
                            $modal.find('.modal__footer').outerHeight() - 24
                        ) + 'px'});
                },
                // Set data table maximum height and enable vertical scrolling
                'setTableHeight': function () {
                    $('#locations-grid').css({'max-height': (
                            $modal.find('.modal__body').outerHeight() -
                            $modal.find('.toolbar').outerHeight()
                        ) + 'px'});
                }
            };

        grid.pjaxUrls.add('#locations-pjax', e.url);

        methods.setModalHeight();
        methods.setTableHeight();

        $modal.on('pjax:complete', function () {
            methods.setTableHeight();
        });

        // Form handlers
        $modal
            .on('change', '[name="selection[]"]', function () {
                // Only one checkbox can be selected
                $modal.find('[name="selection[]"]:checked').not(this).prop('checked', false);

                let state = $modal.find('[name="selection[]"]:checked').length === 0;
                $modal.find('[data-toggle="select"]').attr('disabled', state);
            })
            .on('click', '[data-toggle="select"]', function (e) {
                e.preventDefault();

                $(this).data('value', JSON.parse($modal.find('[name="selection[]"]:checked:eq(0)').val()));
                $(this).trigger($.Event('select'));

                $modal.Modal().hide();
            })
            .on('click', '[data-toggle="locations-clear"]', function (e) {
                e.preventDefault();
                $modal.find('[name^="Storage[locations]"]').val('');
            });
    });
});
