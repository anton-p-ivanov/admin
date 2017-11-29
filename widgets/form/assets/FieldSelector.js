$(function () {

    $.fn.fieldSelector = function () {
        return this.each(function () {
            $(this).on('click', function (e) {
                e.preventDefault();

                let self = $(this),
                    target = self.data('target'),
                    container = self.closest('.field-selector');

                container.find('.form-group').each(function (index, item) {
                    let condition = $(item).find('#' + target).length === 0;

                    $(item).toggleClass('form-group_hidden', condition);
                });
            });
        });
    };

    $('[data-toggle="field-selector"]').fieldSelector();
});