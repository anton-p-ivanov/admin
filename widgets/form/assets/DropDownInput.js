$(function () {

    $.fn.dropDownInput = function () {

        /* Private vars */
        let __options = {},
            __selected = {},
            __prevValue,
            __timeout;

        /* Internationalization vars */
        let locale = $('html').attr('lang') || 'en-US',
            i18n = {
                'en-US': {
                    'NOT_FOUND': 'No elements found.',
                    'VALUE_TOO_SHORT': 'Please, enter 3 characters at least...',
                },
                'ru-RU': {
                    'NOT_FOUND': 'Элементы не найдены.',
                    'VALUE_TOO_SHORT': 'Введите не менее 3 символов...',
                }
            };

        /* Methods */
        let m = {
            'rebuildList': function (dd, options) {
                options = options ? options : {};
                dd.find('li').remove();
                if (!$.isEmptyObject(options)) {
                    for (let i in options) {
                        if (options.hasOwnProperty(i)) {
                            dd.append($('<li>').append($('<a>').attr({'data-value': i}).html(options[i])));
                        }
                    }
                }
                else {
                    let input = dd.closest('.form-group_dropdown').find('input:text'),
                        tooShort = input.val().length < 3,
                        message = i18n[locale]['NOT_FOUND'];

                    if (input.data('type-ahead') === true && tooShort) {
                        message = i18n[locale]['VALUE_TOO_SHORT'];
                    }

                    dd.append($('<li>').attr({'class': 'empty'}).text(message));
                }
            },
            'replace': function (text, value) {
                return text.replace(new RegExp("(" + value + ")", 'i'), '<span class="search-entry">$1</span>');
            },
            'clear': function (dd) {
                dd.find('li').remove();
                dd.append($('<li>').attr({'class': 'empty'}).text(i18n[locale]['VALUE_TOO_SHORT']));
            },
            'open': function (dd) {
                // Close all opened dropdowns
                $(document).find('.dropdown.opened').toggleClass('opened', false);

                // Open selected dropdown
                dd.toggleClass('opened', true);
            }
        };

        return this.each(function () {
            let self = $(this),
                id = self.attr('id'),
                isRemote = self.data('remote') === true,
                dd = self.closest('.form-group').find('ul.dropdown');

            __options[id] = {};

            if (isRemote) {
                $.ajax({
                    url: self.data('url'),
                    type: 'OPTIONS',
                    dataType: 'json',
                    success: function (response) {
                        if (!response.hasOwnProperty('count')) {
                            __options[id] = response;
                            isRemote = false;
                        }
                    }
                });
            }
            else {
                dd.find('li > a').each(function (index, item) {
                    if (!$(item).data('value')) return;
                    __options[id][$(item).data('value').toString()] = $(item).text();
                });
            }

            m.rebuildList(dd, __options[id]);

            // Handling key pressing
            self.on('keyup.dropDownInput', function () {
                let value = $(this).val().toUpperCase(), pos = -1, text;

                if (__timeout) {
                    clearTimeout(__timeout);
                }

                if (value === __prevValue) return;

                if (value.length >= 3) {
                    if (isRemote) {
                        __timeout = setTimeout(function() {
                            $.getJSON(self.data('url'), {'search': value}, function (data) {
                                __selected = data;

                                for (let i in __selected) {
                                    if (__selected.hasOwnProperty(i)) {
                                        text = __selected[i];
                                        __selected[i.toString()] = m.replace(text, value);
                                    }
                                }

                                m.rebuildList(dd, __selected);
                            });
                        }, 500);
                    }
                    else {
                        for (let i in __options[id]) {
                            if (__options[id].hasOwnProperty(i)) {
                                pos = __options[id][i].toUpperCase().indexOf(value);
                                if (pos === 0) {
                                    text = __options[id][i];
                                    __selected[i.toString()] = m.replace(text, value);
                                }
                            }
                        }

                        m.rebuildList(dd, __selected);
                    }
                }
                else {
                    if (value === ' ') {
                        __selected = __options[id];
                        m.rebuildList(dd, __selected);
                    }
                    else {
                        m.clear(dd);
                    }
                }

                if (dd.not('.opened')) {
                    m.open(dd);
                }

                __prevValue = value;
                __selected = {};
            });

            // Prevent hiding dropdown list immediate after it was shown
            self.on('click.dropDownInput', function (e) {
                e.stopPropagation();
            });

            // Showing dropdown list on field focus
            self.on('focus.dropDownInput', function () {
                if (dd.not('.opened')) {
                    m.open(dd);
                }
            });

            self.closest('.form-group_dropdown')

                // Apply selected dropdown item
                .on('click.dropDownInput', '.dropdown a', function (e) {
                    e.preventDefault();

                    let self = $(this),
                        parent = self.closest('.form-group_dropdown'),
                        input = parent.find('input:hidden'),
                        text = parent.find('input:text'),
                        value = self.data('value');

                    self.closest('ul').find('.active').toggleClass('active', false);
                    self.parent().toggleClass('active', true);

                    input.val(value).trigger('change');
                    text.val(self.text()).trigger('blur');
                })

                // Clear dropdown value(s)
                .on('click.dropDownInput', '[data-toggle="clean"]', function (e) {
                    e.preventDefault();

                    let self = $(this),
                        container = self.closest('.form-group_dropdown');

                    container
                        .find('input').val('').text('')
                        .trigger('change').trigger('blur');

                    container.find('.dropdown > li.active').toggleClass('active', false);

                    m.rebuildList(dd, __options[container.find('input:text').attr('id')]);
                });
        });
    };

    $('.form-group_dropdown input:text').dropDownInput();
});