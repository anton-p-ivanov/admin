(function ($) {
    "use strict";

    let CheckboxColumn, defaultOptions, __bind;

    __bind = function (fn, me) {
        return function () {
            return fn.apply(me, arguments);
        };
    };

    // Plugin default options.
    defaultOptions = {
        name: "selection[]",
        multiple: true,
        checkAll: "selection_all"
    };

    CheckboxColumn = (function () {

        // Class constructor
        function CheckboxColumn(handler, options) {
            this.handler = handler;

            // Extend default options.
            $.extend(true, this, defaultOptions, options);

            // Bind methods.
            this.init = __bind(this.init, this);

            this.init();
        }

        function countSelected(target, name) {
            let count = target.find('input[name="' + name + '"]:checked').length;

            target.parent().prev().find('.toolbar__selected').toggleClass('hidden', count === 0)
                .find('[data-selected]').text(count).data('selected', count);
        }

        // Main method.
        CheckboxColumn.prototype.init = function () {
            let name = this.name,
                checkAll = this.checkAll;

            $(document).on('change.CheckboxColumn', 'input[name="' + checkAll + '"]:checkbox', function (e) {
                e.preventDefault();

                let $target = $(e.target).parents('table');
                $target.find('input[name="' + name + '"]:checkbox').prop('checked', e.target.checked);

                countSelected($target, name);

                $(this).trigger($.Event('afterChangeState.CheckboxColumn'));
            });

            $(document).on('change.CheckboxColumn', 'input[name="' + name + '"]:checkbox', function (e) {
                e.preventDefault();

                let $target = $(e.target).parents('table'),
                    count = $target.find('input[name="' + name + '"]:checkbox').not(':checked').length;

                // Does not work! CheckboxColumn inits only once. For main grid.
                // if (!multiple) {
                //     $target.find('input[name="' + name + '"]:checked')
                //         .not(e.target)
                //         .prop('checked', false);
                // }

                $target.find('input[name="' + checkAll + '"]:checkbox').prop('checked', count === 0);

                countSelected($target, name);

                $(this).trigger($.Event('afterChangeState.CheckboxColumn'));
            });
        };

        return CheckboxColumn;
    })();

    $.fn.CheckboxColumn = function (options) {
        return (new CheckboxColumn(this, options || {}));
    };

})(jQuery);
