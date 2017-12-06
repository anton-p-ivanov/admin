(function ($) {
    "use strict";

    let DateTimePicker, defaultOptions, selector, weekdays, $picker,
        __currentDate, __selectedDate, __year, __month,
        todayButton, closeButton,
        __bind;

    __bind = function (fn, me) {
        return function () {
            return fn.apply(me, arguments);
        };
    };

    // Current date
    __currentDate = new Date();

    // Selected date
    __selectedDate = __currentDate;

    // Default selector
    selector = '[data-toggle="dt-picker"]';

    // Plugin default options.
    defaultOptions = {
        type: 'datetime',
        locale: $('html').attr('lang') || 'en-US',
        date: new Date(),
        format: 'DD.MM.YYYY HH:mm'
    };

    // Weekdays
    weekdays = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];

    // i18n
    let i18n = {
        'ru-RU': {
            weekdays: ['В', 'П', 'В', 'С', 'Ч', 'П', 'С'],
            'Today': 'Сегодня',
            'Cancel': 'Отмена',
            'Choose date': 'Выберите дату',
            'Choose hours and minutes': 'Выберите часы и минуты'
        }
    };

    // Buttons template
    todayButton = '<button class="btn btn_default" data-toggle="today">Today</button>';
    closeButton = '<button class="btn btn_default" data-dismiss="dt-picker">Cancel</button>';

    DateTimePicker = (function () {
        function DateTimePicker(handler, options) {
            this.handler = handler;

            // Extend default options.
            $.extend(true, this, defaultOptions, options);

            // Bind methods.
            this.init = __bind(this.init, this);
            this.render = __bind(this.render, this);
            this.renderPicker = __bind(this.renderPicker, this);
            this.renderNav = __bind(this.renderNav, this);
            this.renderHeader = __bind(this.renderHeader, this);
            this.renderCalendar = __bind(this.renderCalendar, this);
        }

        // Main method.
        DateTimePicker.prototype.init = function () {
            let plugin = this,
                handler = plugin.handler,
                date = {};

            handler
                .on('focus.DateTimePicker', function (e) {
                    let pos = $(this).offset(),
                        top = pos.top + $(this).outerHeight(),
                        target = e.target;

                    __selectedDate = $(target).data('date') ? new Date($(target).data('date')) : __currentDate;

                    // Remove previously opened pickers. Only one picker instance is allowed.
                    if ($picker) {
                        $picker.remove();
                    }

                    date = {};

                    plugin.renderPicker(__selectedDate);

                    $picker.on('click.DateTimePicker', '[data-toggle]', function (e) {
                        e.preventDefault();
                        let date;

                        switch ($(this).data('toggle')) {
                            case 'dt-prev':
                                date = new Date(__year, __month-1, 1);
                                break;
                            case 'dt-next':
                                date = new Date(__year, __month+1, 1);
                                break;
                            default:
                                date = __currentDate;
                        }

                        plugin.renderDatePicker(date);
                    });

                    $picker.on('click.DateTimePicker', '[data-dismiss]', function (e) {
                        e.preventDefault();
                        $picker.remove();
                    });

                    $picker.on('click.DateTimePicker', '[data-date]', function (e) {
                        e.preventDefault();

                        let self = $(this);

                        date['date'] = self.data('date');

                        if (plugin.type === 'date') {
                            $(target).val(moment(date['date']).format(plugin.format)).data('date', date['date']).trigger('blur');
                            $picker.remove();
                        }
                        else {
                            plugin.renderTimePicker(__selectedDate, 'am');
                        }
                    });

                    $picker.on('click.DateTimePicker', '[data-switch]', function (e) {
                        e.preventDefault();
                        plugin.renderTimePicker(__currentDate, $(this).data('switch'));
                    });

                    $picker.on('click.DateTimePicker', '[data-hours],[data-minutes]', function (e) {
                        e.preventDefault();

                        let self = $(this),
                            attr = self.data('minutes'),
                            value;

                        self.closest('ul').find('.selected').toggleClass('selected', false);
                        self.toggleClass('selected', true);

                        if (typeof attr !== typeof undefined && attr !== false) {
                            date['minutes'] = self.data('minutes');

                            if (typeof date['date'] !== typeof undefined &&
                                typeof date['minutes'] !== typeof undefined &&
                                typeof date['hours'] !== typeof undefined
                            ) {
                                value = date['date'] + ' ' + date['hours'] + ':' + date['minutes'];
                                $(target).val(moment(value).format(plugin.format)).data('date', value).trigger('blur');
                                $picker.remove();
                            }
                        }
                        else {
                            date['hours'] = self.data('hours');
                            $picker.find('.dt-clock__minutes').toggleClass('hidden', false);
                        }
                    });

                    // Position picker to target input
                    $picker.css({'left': pos.left, 'top': top});

                    // Display picker
                    $('body').append($picker);

                    let height = $picker.height();

                    // If picker is out of window bounds update it position
                    if (top + height > $(window).height() && top - height > 0) {
                        $picker.css({'top': top - height});
                    }
                    else {
                        $picker.css({'top': 0});
                    }

                    // Remove picker on outside click
                    $(document)
                        .off('click.DateTimePicker')
                        .on('click.DateTimePicker', function (e) {
                            let $t = $(e.target);
                            if ($t.closest('.dt-picker, .dt-nav, .dt-calendar, .dt-clock').length === 0 && !$t.is(plugin.handler)) {
                                $picker.remove();
                            }
                        });
                });

            handler.addClass('dt-picker-input').attr('readonly', true);
        };

        DateTimePicker.prototype.renderPicker = function (date) {
            let $todayButton = $(todayButton).text(i18n[this.locale][$(todayButton).text()]),
                $closeButton = $(closeButton).text(i18n[this.locale][$(closeButton).text()]);

            $picker = $('<div class="dt-picker">')
                .append('<div class="dt-picker__header"></div>')
                .append('<div class="dt-picker__body"></div>')
                .append('<div class="dt-picker__footer"></div>');

            $picker.find('.dt-picker__footer').append($todayButton).append($closeButton);

            if (this.type === 'time') {
                this.renderTimePicker(date, 'am');
            }
            else {
                this.renderDatePicker(date);
            }
        };

        DateTimePicker.prototype.renderDatePicker = function (date) {

            this.renderHeader(date);

            $picker.find('.dt-picker__body').html('')
                .append(this.renderNav(date))
                .append(this.renderCalendar(date));
        };

        // Renders picker header
        DateTimePicker.prototype.renderHeader = function () {
            $picker.find('.dt-picker__header').html('')
                .append($('<div>').text(i18n[this.locale]['Choose date']).addClass('dt-header__date'));
        };

        // Renders picker navigation
        DateTimePicker.prototype.renderNav = function (date) {
            let $prevButton, $nextButton, $title, $nav, $icon;

            $nav = $('<div class="dt-nav">');
            $icon = $('<i class="material-icons">');

            $prevButton = $('<button>')
                .attr({'class': 'dt-nav__btn', 'data-toggle': 'dt-prev'})
                .append($icon.clone().text('keyboard_arrow_left'));

            $nextButton = $('<button>')
                .attr({'class': 'dt-nav__btn', 'data-toggle': 'dt-next'})
                .append($icon.clone().text('keyboard_arrow_right'));

            $title = $('<div class="dt-nav__title">').text(
                date.toLocaleString(this.locale, {'month': 'long'}) + ' ' + date.getFullYear()
            );

            return $nav.append($prevButton).append($title).append($nextButton);
        };

        // Renders picker calendar
        DateTimePicker.prototype.renderCalendar = function (date) {
            let $calendar = $('<table class="dt-calendar">'),
                month = date.getMonth(),
                year = date.getFullYear(),
                firstDayOfMonth = (new Date(year, month, 1)).getDay(),
                lastDayOfMonth = (new Date(year, month + 1, 0)).getDay(),
                lastDateOfMonth = (new Date(year, month + 1, 0)).getDate();

            __month = month;
            __year = year;

            $calendar
                .append($('<thead>').append($('<tr>')))
                .append($('<tbody>'));

            for (let i = 0; i < weekdays.length; i++) {
                $calendar.find('thead > tr:eq(0)').append($('<th>').text(i18n[this.locale].weekdays[i]));
            }

            let i, j, temp = [], chunk = 7, days = [];

            if (firstDayOfMonth > 0) {
                let lastDateOfPreviousMonth = (new Date(year,month,0)).getDate();
                for (i = firstDayOfMonth; i > 0; i--) {
                    days.push({
                        'text': lastDateOfPreviousMonth - i,
                        'class': 'disabled'
                    });
                }
            }

            for (i = 1; i <= lastDateOfMonth; i++) {
                let today = (i === __currentDate.getDate()
                        && month === __currentDate.getMonth()
                        && year === __currentDate.getFullYear()),
                    selected = (i === __selectedDate.getDate()
                        && month === __selectedDate.getMonth()
                        && year === __selectedDate.getFullYear()),
                    strDate = [year, month + 1, ("00" + i).slice(-2)].join('-');

                days.push({
                    'text': $('<a>').text(i).attr({'href': '#', 'data-date': strDate, 'class': selected ? 'selected' : null}),
                    'class': today ? 'today' : null,
                });
            }

            if (lastDayOfMonth < 7) {
                let firstDateOfNextMonth = (new Date(year, month + 1, 1)).getDate();
                for (i = 6, j = 0; i > lastDayOfMonth; i--) {
                    days.push({
                        'text': firstDateOfNextMonth + j,
                        'class': 'disabled'
                    });
                    j++;
                }
            }

            // Split days array into chunks by 7 items
            for (i = 0, j = days.length; i < j; i += chunk) {
                temp.push(days.slice(i, i + chunk));
            }

            days = temp;

            let $row;
            for (i = 0; i < days.length; i++) {
                $row = $('<tr>').append(days[i].map(function (day) {
                    let $cell = typeof day.text === 'object' ? day.text : $('<span>').text(day.text);
                    return $('<td>').append($cell).addClass(day.class || null);
                }));

                $calendar.find('tbody').append($row);
            }

            return $calendar;
        };

        DateTimePicker.prototype.renderTimePicker = function (date, ampm) {

            this.renderTimeHeader(date);

            $picker.find('.dt-picker__body').html('')
                .append(this.renderClock(date, ampm));
        };

        DateTimePicker.prototype.renderSwitch = function (date, pm) {
            let $switch = $('<ul class="dt-clock__switch">');

            $switch.append($('<li>').append($('<a>').text('am').attr({
                'href': '#',
                'data-switch': 'am',
                'class': !pm ? 'selected' : null
            })));

            $switch.append($('<li>').append($('<a>').text('pm').attr({
                'href': '#',
                'data-switch': 'pm',
                'class': pm ? 'selected' : null
            })));

            return $switch;
        };

        DateTimePicker.prototype.renderTimeHeader = function () {
            $picker.find('.dt-picker__header').html('')
                .append($('<div>').text(i18n[this.locale]['Choose hours and minutes']).addClass('dt-header__date'));
        };

        DateTimePicker.prototype.renderClock = function (date, ampm) {
            let $clock = $('<div class="dt-clock">'),
                $hours = $('<ul class="dt-clock__hours">'),
                $minutes = $('<div class="dt-clock__minutes hidden">'),
                pm = (typeof ampm !== 'undefined') ? ampm === 'pm' : date.getHours() > 12;

            let i, j;

            for (i = 0; i < 12; i++) {
                j = pm ? i + 12 : i;
                j = j < 23 ? j : -1;

                $hours.append($('<li>').append($('<a>').text(j+1).attr({
                    'href': '#',
                    'data-hours' : j + 1,
                })));

                j = i * 5 + 5;
                j = j < 60 ? j : 0;

                $minutes.append($('<li>').append($('<a>').text(j).attr({
                    'href': '#',
                    'data-minutes': j,
                })));
            }

            return $clock.append($hours).append($minutes)
                .append(this.renderSwitch(date, pm));
        };

        return DateTimePicker;
    })();

    $.fn.DateTimePicker = function (options) {
        // Init plugin
        (new DateTimePicker(this, options || {})).init();

        // Display items (if hidden) and return jQuery object to maintain chainability.
        return this;
    };

    $(selector).DateTimePicker();

})(jQuery);
