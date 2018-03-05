$(function () {
    "use strict";

    let grid = $('#versions-pjax').grid();

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

    // This handler will trigger after `#versions-modal` loads
    $(document).on('loaded.Modal', '#versions-modal', function (e) {
        let $modal = $(e.currentTarget);

        // Enable interactive form
        $('#versions-form').Form();

        // Focus on first input
        $modal.find('input:text:first').focus();

        // After submit form handler
        $modal
            .off('afterSubmit.Form', '#versions-form')
            .on('afterSubmit.Form', '#versions-form', function () {
                // Reload versions table content
                grid.reload('#versions-pjax');

                // Close modal window
                $modal.Modal().hide();
            });
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
