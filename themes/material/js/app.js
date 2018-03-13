$(function(){
    "use strict";

    // Turn-off mouse clicks on disabled elements
    $(document).on('click', '.disabled', function (e) {
        e.preventDefault();
    });

    // Dismiss alert messages
    $(document).on('click', '.alert [data-toggle="dismiss"]', function (e) {
        e.preventDefault();
        $(e.target).closest('.alert').remove();
    });

    $(document).on('resize', function (e) {
        $('.app-nav').css({'height': $(window).outerHeight() - $('.app-logo').outerHeight()});
    });

    $(document).trigger('resize');

    let $nav = $('nav'),
        isNavOpened = false;

    $('[data-toggle="menu"]').on('click', function (e) {
        e.preventDefault();

        $nav.animate({'left': isNavOpened ? (0 - $nav.width()) : 0 }, function () {
            isNavOpened = !isNavOpened;
        });
    });

    $(document).on('click.Nav', function (e) {
        let $target = $(e.target);

        if (isNavOpened && $target.closest('nav').length === 0) {
            $nav.animate({'left': isNavOpened ? (0 - $nav.width()) : 0 }, function () {
                isNavOpened = false;
            });
        }
    });
});