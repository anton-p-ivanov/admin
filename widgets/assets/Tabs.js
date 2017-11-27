$(function () {

    $.fn.tabs = function () {
        return this.each(function () {
            $(this).on('click', function (e) {
                e.preventDefault();

                let self = $(this),
                    target = self.data('target'),
                    container = self.closest('.tabs');

                if (self.is('.disabled')) {
                    return;
                }

                // Disable active states
                container.find('.tabs-content:first > .tabs-pane').toggleClass('active', false);
                container.find('.tabs-nav:first .tabs-nav__link').toggleClass('active', false);

                // Enable active states for selected tab
                $(target).toggleClass('active', true);
                //$(this).parent().toggleClass('active', true);
                self.toggleClass('active', true);

                if (self.data('remote')) {
                    $(target).load(self.attr('href'), function () {
                        self.data('remote', false);
                        $(target).trigger($.Event('loaded.Tabs'));
                    });
                }
            });
        });
    };

    $('[data-toggle="tab"]').tabs();
});