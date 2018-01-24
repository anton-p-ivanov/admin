$(function () {
    "use strict";

    let $form = $('#questions-form');
    
    $('#questions-pjax').grid();

    $(document).on('click', '[data-toggle="select"]', function (e) {
        e.preventDefault();

        $('#questions-pjax').find('[name^="selection"]')
            .prop({'checked': $(this).data('state')});

        send($(this).attr('href'));
    });

    let events = ['afterChangeState.CheckboxColumn'];
    $(document).on(events.join(' '), 'input[name^="selection"]:checkbox', function (e) {
        e.preventDefault();

        send($form.attr('action'));
    });

    function send(url) {
        $.ajax({
            url: url,
            type: 'POST',
            data: $form.serializeArray()
        });
    }
});
