(function ($) {
    "use strict";

    let Grid, __pjaxUrls, __bind, __methods;

    __pjaxUrls = {};

    __bind = function (fn, me) {
        return function () {
            return fn.apply(me, arguments);
        };
    };

    __methods = {
        'confirm': function (element, grid) {
            let self = $(element),
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
                        grid.reload(container);

                        // Close modal window
                        $modal.Modal().hide();
                    });
            }
        },
        'settings': function (element, grid) {
            let $modal = $(element);

            // Enable interactive form
            $('#settings-form').Form();

            $modal.on('click', 'button:submit', function (e) {
                e.preventDefault();

                $modal.find('[name="action"]:hidden').val($(this).val());
                $modal.find('form').submit();
            });

            // After submit form handler
            $modal.on('afterSubmit.Form', '#settings-form', function () {
                // Reload main grid
                // $.pjax.reload('#users-pjax');
                grid.reload();

                // Close modal window
                $modal.Modal().hide();
            });
        },
        'filter': function (element, grid) {
            let $modal = $(element);

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
                    // Reload main grid
                    grid.reload(null, e.response.url, true);
                    // $.pjax({
                    //     container: '#users-pjax',
                    //     fragment: '#users-pjax',
                    //     url: e.response.url,
                    //     push: true,
                    // });

                    // Close modal window
                    $modal.Modal().hide();
                });
        },
        'tabs': function (element) {
            let $pjaxContainer = $(element).find('[data-pjax-container]'), selector;

            if ($pjaxContainer.length === 0) {
                return;
            }

            $pjaxContainer.each(function(index) {
                selector = '#' + $pjaxContainer.get(index).id;

                $(document).pjax(selector + ' a:not([data-pjax="false"])', {
                    container: selector,
                    fragment: selector,
                    push: false
                });

                __pjaxUrls[selector] = $(selector).data('pjax-url');
            });
        },
        'onConfirmShown': function (element) {
            let $modal = $(element);

            // Set focus to password field
            $modal.find('input:password').val('').focus();
            $modal.find('.form-group.error').toggleClass('error', false);
            $modal.find('.form-group__error').html('');
        }
    };

    Grid = (function () {
        function Grid(handler) {
            this.handler = handler;

            // Bind methods.
            this.init = __bind(this.init, this);
        }

        Grid.prototype.reload = function (selector, url, push) {
            selector = selector ? selector : ('#' + this.handler.get(0).id);
            url = url ? url : __pjaxUrls[selector];
            push = push ? push : false;

            $.pjax({
                container: selector,
                fragment: selector,
                url: url,
                push: push,
            });
        };

        Grid.prototype.pjaxUrls = {
            'add': function (selector, url, push) {
                $(document).pjax(selector + ' a:not([data-pjax="false"])', {
                    container: selector,
                    fragment: selector,
                    push: push ? push : false
                });

                __pjaxUrls[selector] = url;
            }
        };

        // Main method.
        Grid.prototype.init = function () {
            let grid = this;

            grid.handler.each(function(index, item) {
                let selector = '#' + item.id;

                __pjaxUrls[selector] = window.location.href;

                // Enable PJAX for main grid
                $(document).pjax(selector + ' a:not([data-pjax="false"])', {
                    container: selector,
                    fragment: selector,
                    push: true
                });
            });

            $(document).on('pjax:success', function (e, a, b, c, d) {
                __pjaxUrls[d.container] = d.url;
            });

            // This handler will trigger on-demand update of `pjax` containers
            $(document).on('click.Grid', '[data-toggle="pjax"]', function (e) {
                // Prevent default behavior
                e.preventDefault();

                // Reload grid on demand
                this.reload(this.getAttribute('data-target'));
            });

            // Toggle action activation
            $(document).on('click.Grid', '[data-toggle="action"]', function (e) {
                // Prevent default behavior
                e.preventDefault();

                // Call event handler
                __methods.confirm(this, grid);
            });

            // This handler will trigger after `#settings-modal` loads
            $(document).on('loaded.Modal', '#settings-modal', function (e) {
                __methods.settings(e.currentTarget, grid);
            });

            // This handler will trigger after `#filter-modal` loads
            $(document).on('loaded.Modal', '#filter-modal', function (e) {
                __methods.filter(e.currentTarget, grid);
            });

            // This handler will trigger after `#confirm-modal` was shown
            $(document).on('shown.Modal', '#confirm-modal', function (e) {
                __methods.onConfirmShown(e.currentTarget);
            });

            // This handler will trigger after listed tabs` content loaded
            $(document).on('loaded.Tabs', '[data-remote]', function (e) {
                __methods.tabs(e.target);
            });
        };

        return Grid;
    })();

    $.fn.grid = function () {
        // Init plugin
        let g = (new Grid(this));
        g.init();

        // Return jQuery object to maintain chainability.
        return g;
    };

})(jQuery);
