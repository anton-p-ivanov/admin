$(function () {
    "use strict";

    let grid = $('#elements-pjax').grid(),
        locale = $('html').attr('lang') || 'en-US';

    // i18n
    let i18n = {
        'ru-RU': {
            'Catalog root': 'Корень каталога',
            'An element is placed under catalog`s root': 'Элемент расположен в корне каталога',
        }
    };

    // This handler will trigger after modal loads
    $(document).on('loaded.Modal', '#section-modal, #element-modal', function (e) {
        let $modal = $(e.currentTarget),
            $active = $('#element-active'),
            state = $active.is(':checked'),
            $dtPickerFields = $('.field-element-active_dates input[type="text"]'),
            dateTimeFormat = 'DD.MM.YYYY HH:mm';

        // Enable datepicker
        $dtPickerFields.DateTimePicker({'format': dateTimeFormat})
            .attr('disabled', !state)
            .closest('.form-group')
            .addClass('form-group__dt-picker')
            .toggleClass('disabled', !state);

        $active.on('change', function () {
            let state = $(this).is(':checked');
            $dtPickerFields.attr('disabled', !state).closest('.form-group').toggleClass('disabled', !state);
        });

        // Enable interactive form
        $modal.find('#elements-form').Form();

        // Set focus
        $modal.find('#element-title').focus();

        // Enable tabs
        $('[data-toggle="tab"]').tabs();

        $modal.on('click', '[data-toggle="location-remove"]', function (e) {
            e.preventDefault();

            let $$ = $(this);

            $('[name^="Element[locations]"]').filter('[value="' + $$.data('location') + '"]').remove();
            $$.parents('.locations-list__item').remove();
        });

        $modal.on('click', '[data-toggle="location-root"]', function (e) {
            e.preventDefault();

            let $$ = $(this);

            let value = {
                'uuid': $$.data('location'),
                'title': i18n[locale]['Catalog root']
            };
            let field = $('[name^="Element[locations]"]');
            let list = $('.locations-list');
            let clone = $modal.find('.locations-list_template > li:eq(0)').clone();

            clone.find('.locations-list__title').text(value.title);
            clone.find('.locations-list__action').data('location', value.uuid);
            clone.find('.locations-list__comment').html(i18n[locale]['An element is placed under catalog`s root']);

            field.parent().append(field.clone().val(value.uuid));
            list.append(clone);
        });

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#elements-form')
            .on('afterSubmit.Form', '#elements-form', function () {
                // Close modal window
                $modal.Modal().hide();

                grid.reload('#elements-pjax');
            });
    });

    // This handler will trigger after `#locations-modal` loads
    $(document).on('loaded.Modal', '#locations-modal', function (e) {

        let $modal = $(e.currentTarget);

        grid.pjaxUrls.add('#locations-pjax', e.url);

        // Form handlers
        $modal
            .on('change', '[name="selection[]"]', function () {
                let state = $modal.find('[name="selection[]"]:checked').length === 0;
                $modal.find('[data-toggle="select"]').attr('disabled', state);
            })
            .on('click', '[data-toggle="select"]', function (e) {
                e.preventDefault();

                let value = JSON.parse($modal.find('[name="selection[]"]:checked:eq(0)').val());
                let field = $('[name^="Element[locations]"]');
                let list = $('.locations-list');
                let clone = $('.locations-list_template > li:eq(0)').clone();

                clone.find('.locations-list__title').text(value.title);
                clone.find('.locations-list__action').data('location', value.uuid);
                clone.find('.locations-list__comment').html('<em class="blink">Loading full path info ...</em>');

                $.ajax({
                    url: 'get-canonical-path',
                    type: 'get',
                    data: {'tree_uuid': value.uuid},
                    success: function (response) {
                        let array = [], key;
                        for (key in response) {
                            if (response.hasOwnProperty(key)) {
                                array.push(response[key])
                            }
                        }

                        clone.find('.locations-list__comment').text('//' + array.join(' // '));
                    }
                });

                field.parent().append(field.clone().val(value.uuid));
                list.append(clone);

                $modal.Modal().hide();
            })
            .on('click', '[data-toggle="locations-clear"]', function (e) {
                e.preventDefault();
                $modal.find('[name^="Element[locations]"]').val('');
            });
    });
});
