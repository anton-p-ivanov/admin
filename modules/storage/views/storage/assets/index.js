$(function () {
    "use strict";

    let _pjaxUrls = {'#storage-pjax': window.location.href};

    // Enable PJAX for main storage grid
    $(document).pjax('#storage-pjax a:not([data-pjax="false"])', {
        container: '#storage-pjax',
        fragment: '#storage-pjax',
        push: true
    });

    $(document).on('pjax:success', function (e, a, b, c, d) {
        _pjaxUrls[d.container] = d.url;
    });

    /**
     * Formats file size to human readable format.
     *
     * @param value
     * @param sizeFormatBase
     * @returns {string}
     */
    function formatFileSize(value, sizeFormatBase) {
        let position = 0, units = ['Б', 'КБ', 'МБ', 'ГБ', 'ТБ'];
        sizeFormatBase = sizeFormatBase ? sizeFormatBase : 1024;

        do {
            if (Math.abs(value) < sizeFormatBase) {
                break;
            }
            value /= sizeFormatBase;
            position++;
        } while (position < 5);

        return value.toFixed(2) + '&nbsp;' + units[position];
    }

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

    // Activate file lookup
    $(document).on('click', '[data-toggle="upload"]', function (e) {
        e.preventDefault();

        if (!('File' in window &&
            'FileReader' in window &&
            'FileList' in window &&
            'Blob' in window)
        ) {
            alert('Ваш браузер не поддерживает загрузку файлов.');
        }

        $('input:file')
            .attr('data-upload-url', $(this).attr('href'))
            .attr('data-target', $(this).data('target'))
            .attr('data-pjax-url', $(this).data('url'))
            .trigger('click');
    });

    // This handler will trigger on-demand update of `pjax` containers
    $(document).on('click', '[data-toggle="pjax"]', function (e) {
        e.preventDefault();
        reloadGrid($(this).data('target'));
    });

    // Toggle version activation
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

    // This handler will trigger after `#storage-modal` loads
    $(document).on('loaded.Modal', '#storage-modal', function (e) {
        let $modal = $(e.currentTarget),
            maxHeight = Math.max.apply(Math, $modal.find('.tabs-pane').map(function () {
                return $(this).outerHeight();
            }));

        $modal.find('.tabs-pane').css({'min-height': maxHeight + 'px'});

        // Enable interactive form
        $('#storage-form').Form();

        // Enable tabs
        $('[data-toggle="tab"]').tabs();

        $(document).pjax('#versions-pjax a:not([data-pjax="false"])', {
            container: '#versions-pjax',
            fragment: '#versions-pjax',
            push: false
        });

        _pjaxUrls['#versions-pjax'] = $('#versions-pjax').data('pjax-url');

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#storage-form')
            .on('afterSubmit.Form', '#storage-form', function () {
                // Close modal window
                $modal.Modal().hide();
            });
    });

    // This handler will trigger after `#locations-modal` loads
    $(document).on('loaded.Modal', '#locations-modal', function (e) {

        let $modal = $(e.currentTarget);

        // Enable PJAX support for data grid
        $(document).pjax('#locations-pjax a:not([data-pjax="false"])', {
            container: '#locations-pjax',
            fragment: '#locations-pjax',
            push: false
        });

        _pjaxUrls['#locations-pjax'] = e.url;

        // Form handlers
        $modal
            .on('change', '[name="selection[]"]', function () {
                let state = $modal.find('[name="selection[]"]:checked').length === 0;
                $modal.find('[data-toggle="select"]').attr('disabled', state);
            })
            .on('click', '[data-toggle="select"]', function (e) {
                e.preventDefault();

                let value = JSON.parse($modal.find('[name="selection[]"]:checked:eq(0)').val());
                $('[name^="Storage[locations]"]:text').val(value.title);
                $('[name^="Storage[locations]"]:hidden').val(value.uuid);

                $modal.Modal().hide();
            })
            .on('click', '[data-toggle="locations-clear"]', function (e) {
                e.preventDefault();
                $modal.find('[name^="Storage[locations]"]').val('');
            });
    });

    // This handler will trigger after `#version-modal` loads
    $(document).on('loaded.Modal', '#version-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $('#version-form').Form();

        // After submit form handler
        $modal.on('afterSubmit.Form', '#version-form', function () {
            // Reload versions table content
            reloadGrid('#versions-pjax');

            // Close modal window
            $modal.Modal().hide();
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
            // Reload storage table content
            $.pjax.reload('#storage-pjax');

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
                    container: '#storage-pjax',
                    fragment: '#storage-pjax',
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

    // This handler will trigger after `#storage-modal` was hidden
    $(document).on('hidden.Modal', '#storage-modal', function () {
        reloadGrid('#storage-pjax');
    });

    // File upload
    $(document).on('click', 'input:file', function () {
        let self = $(this),
            modal = $('#upload-modal'),
            bar = modal.find('[data-file]'),
            index = 0,
            m = {
                addError: function (message) {
                    let error = $('<li>').text(message);
                    modal.find('.upload-errors').append(error).show();
                }
            };

        self.fileupload({
            dataType: 'json',
            maxChunkSize: 1000000,
            sequentialUploads: true,
            autoUpload: false,
            add: function (e, data) {
                modal.Modal().show();
                modal.find('[data-file-total]').text(data.originalFiles.length);
                data.submit();
            },
            start: function () {
                modal.find('.modal__footer button').prop('disabled', true);
                modal.find('.upload-errors > li').remove();
                modal.find('.upload-errors').hide();
            },
            stop: function () {
                modal.find('.modal__footer button').prop('disabled', false);
                modal.find('.modal__header b > .hidden').toggleClass('hidden');
            },
            send: function (e, data) {
                let text = data.files[0].name + ' (' + formatFileSize(data.files[0].size) + ')';

                bar.data('file', data.files[0].name);
                bar.find('.progress-bar__filename').html(text);

                if (index <= data.originalFiles.length) {
                    modal.find('[data-file-index]').data('file-index', index).text(index + 1);
                    bar.find('.progress-bar__ribbon').css({'width': '0'});
                    bar.find('.progress-bar__status').text('0%');
                }

                index++;
            },
            fail: function (e, data) {
                m.addError(data.files[0].name + ': ' + (data.errorThrown || 'ошибка загрузки файла'))
            },
            done: function (e, data) {
                // Files after uploading (contains real file size & mime type)
                let file = data.result.files[0];
                // Real uploaded file name (as selected from client file system)
                let name = data.files[0].name;

                $.ajax({
                    url: self.data('hash-url'),
                    type: 'get',
                    data: {'name': name},
                })
                .then(function (r) {
                    return $.ajax({
                        url: self.data('upload-url'),
                        type: 'put',
                        dataType: 'json',
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        data: {
                            'Storage': {
                                'title': name,
                                'files': JSON.stringify([{
                                    'name': name,
                                    'size': file.size,
                                    'type': file.type,
                                    'hash': r.hash
                                }])
                            }
                        },
                    });
                })
                .fail(function(r) {
                    m.addError('Ошибка загрузки файлов: ' + r.statusText);
                })
                .done(function (r) {
                    if (r.hasOwnProperty('file_uuid')) {
                        $.ajax({
                            url: self.data('move-url'),
                            type: 'post',
                            data: {'name': name, 'uuid': r['file_uuid']}
                        });
                    }

                    if (r.hasOwnProperty('errors')) {
                        let errors = r['errors'];
                        if ((errors.length > 0 || !$.isEmptyObject(errors))) {
                            for (let k in errors) {
                                if (errors.hasOwnProperty(k)) {
                                    for (let i = 0; i < errors[k].length; i++) {
                                        m.addError(errors[k][i]);
                                    }
                                }
                            }
                        }
                    }

                    // Reload storage table content
                    $.pjax({
                        container: self.data('target'),
                        fragment: self.data('target'),
                        url: self.data('pjax-url'),
                        push: false
                    });
                });
            },
            progress: function (e, data) {
                let progress = parseInt(data.loaded / data.total * 100, 10);
                bar.find('.progress-bar__ribbon').css({'width': progress + '%'});
                bar.find('.progress-bar__status').text(progress + '%');
            }
        });
    });

});
