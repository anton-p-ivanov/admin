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
            'rebuildList': function (dd) {
                dd.find('li').remove();
                if (!$.isEmptyObject(__selected)) {
                    for (let i in __selected) {
                        if (__selected.hasOwnProperty(i)) {
                            dd.append($('<li>').append($('<a>').attr({'data-value': i}).html(__selected[i])));
                        }
                    }
                }
                else {
                    dd.append($('<li>').attr({'class': 'empty'}).text(i18n[locale]['NOT_FOUND']));
                }
            },
            'replace': function (text, value) {
                return text.replace(new RegExp("(" + value + ")", 'i'), '<span class="search-entry">$1</span>');
            },
            'clear': function (dd) {
                dd.find('li').remove();
                dd.append($('<li>').attr({'class': 'empty'}).text(i18n[locale]['VALUE_TOO_SHORT']));
            }
        };

        return this.each(function () {
            let self = $(this),
                isRemote = self.data('remote') === true,
                dd = self.closest('.form-group').find('ul.dropdown');

            __options[self.id] = {};

            if (isRemote) {
                $.ajax({
                    url: self.data('url'),
                    type: 'OPTIONS',
                    dataType: 'json',
                    success: function (response) {
                        if (!response.hasOwnProperty('count')) {
                            __options[self.id] = response;
                            __selected = response;
                            isRemote = false;
                        }
                    }
                });
            }
            else {
                dd.find('li > a').each(function (index, item) {
                    if (!$(item).data('value')) return;
                    __options[self.id][$(item).data('value').toString()] = $(item).text();
                });
            }

            m.clear(dd);

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

                                m.rebuildList(dd);
                            });
                        }, 500);
                    }
                    else {
                        for (let i in __options[self.id]) {
                            if (__options[self.id].hasOwnProperty(i)) {
                                pos = __options[self.id][i].toUpperCase().indexOf(value);
                                if (pos === 0) {
                                    text = __options[self.id][i];
                                    __selected[i.toString()] = m.replace(text, value);
                                }
                            }
                        }

                        m.rebuildList(dd);
                    }
                }
                else {
                    if (value === ' ') {
                        __selected = __options[self.id];
                        m.rebuildList(dd);
                    }
                    else {
                        m.clear(dd);
                    }
                }

                if (dd.not('.opened')) {
                    dd.toggleClass('opened', true);
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
                    dd.toggleClass('opened', true);
                }
            });
        });
    };

    $('.form-group_dropdown [data-type-ahead]').dropDownInput();
});