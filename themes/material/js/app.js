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
});