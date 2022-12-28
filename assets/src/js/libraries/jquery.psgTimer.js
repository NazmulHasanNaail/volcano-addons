/**
 * @link https://github.com/ProsteerGroup/PsgTimer
 *
 */
(function ( $ ) {
    const callbacks = {
        onInit: function () {
        },
        onStart: function () {
        },
        onStop: function () {
        },
        onEnd: function () {
        },
        onChangeStart: function () {
        },
        onChangeEnd: function () {
        }
    };

    const base = {
        stopped: true,
        timezone: 0,
        diff: null,
        isEnd: false
    };

    const PsgTimer = function (selector, options) {
        const timer = this;
        if (selector.nodeType === Node.ELEMENT_NODE) {
            timer.container = $(selector);
        } else if (typeof selector === 'string') {
            timer.selector = selector;
            timer.container = $(timer.selector);
        } else if (typeof selector === 'object') {
            options = selector;
            timer.selector = options.selector;
            timer.container = $(timer.selector);
        }

        timer.options = $.extend({}, {
            selector: '#psgTimer',
            animation: false,
            multipleBlocks: false,
            endDateTime: undefined,
            // currentDateTime: window.serverTime['U'] * 1000 || Date.now(),
            currentDateTime: Date.now(),
            //labelsType: timer.container.attr( 'data-label-type' ) ? timer.container.attr( 'data-label-type' ) : 'block',
            labelPlacement: timer.container.attr( 'data-label-placement' ) ? timer.container.attr( 'data-label-placement' ) : 'outside',
            labels: {
                days: timer.container.attr('data-label-days') ? timer.container.attr('data-label-days') : false,
                hours: timer.container.attr('data-label-hours') ? timer.container.attr('data-label-hours') : false,
                minutes: timer.container.attr('data-label-minutes') ? timer.container.attr('data-label-minutes') : false,
                seconds: timer.container.attr('data-label-seconds') ? timer.container.attr('data-label-seconds') : false
            }
        }, options);

        timer.callbacks = timer.options.callbacks = $.extend({}, callbacks, timer.options.callbacks);
        timer.base = $.extend({}, base);

        if (typeof timer.options.endDateTime === 'string') {
            timer.options.endDateTime = setTimerEndFromString(timer, timer.options.endDateTime);
        }

        timer.container.length ? timer.init() : console.log('No timer element on this page');
    };

    PsgTimer.prototype.init = function () {
        const timer = this;
        const options = this.options;

        const timerEnd = timer.container.attr('data-timer-end');

        if (timerEnd !== undefined) {
            options.endDateTime = setTimerEndFromString(timer, timerEnd);
        }

        // options.endDateTime = options.endDateTime + (timer.base.timezone * 1000 * 60 * 60);

        timer.countdown = transformCountToArray(getCurrentCountDown(timer), options.multipleBlocks);

        timer.container.addClass('psgTimer').append(createMarkup(timer));
        if (options.animation) {
            timer.container.addClass('psgTimer_' + options.animation);
        }

        timer.query = setQueries(timer);
        timer.callbacks.onInit();

        if (!timer.base.isEnd) {
            timer.start();
        }
    };

    PsgTimer.prototype.start = function () {
        const timer = this;

        if (timer.base.stopped) {
            timer.base.stopped = false;

            timer.intervalId = setInterval(function () {
                updateCounter(timer);
            }, 1000);

            timer.callbacks.onStart();
        }
    };

    PsgTimer.prototype.restart = function () {
        const timer = this;
        timer.options.currentDateTime = Date.now();
        timer.start();
    };

    PsgTimer.prototype.stop = function () {
        const timer = this;
        timer.base.stopped = true;
        clearTimeout(timer.intervalId);

        timer.callbacks.onStop();
    };

    $.fn.psgTimer = function( options ) {
        $(this).each( function() {
            const self = $(this);
            let psgTimer = self.data('psgTimer');

            if ( ! options ) {
                throw new Error( 'Invalid Options' );
            }

            if ( psgTimer ) {
                if ( typeof options === 'string' ) {
                    switch ( options ) {
                        case 'start':
                            psgTimer.start();
                            break;
                        case 'stop':
                            psgTimer.stop();
                            break;
                        case 'restart':
                            psgTimer.restart();
                            break;
                        default:
                            throw new Error( 'Invalid Method' );
                    }

                    return;
                } else {
                    psgTimer.stop();
                }
            }

            psgTimer = new PsgTimer( self.get(0), options );

            self.data( 'psgTimer', psgTimer );
        } );

        return this;
    };

    const getCurrentCountDown = function (timer) {
        const options = timer.options;
        const base = timer.base;

        options.currentDateTime = options.currentDateTime + 1001;
        base.diff = options.endDateTime - options.currentDateTime;

        let seconds = 0;
        let minutes = 0;
        let hours = 0;
        let days = 0;

        if (base.diff > 0) {
            let total = parseFloat(((((base.diff / 1000.0) / 60.0) / 60.0) / 24.0).toString());
            days = parseInt(total.toString());
            total -= days;
            total *= 24.0;
            hours = parseInt(total.toString());
            total -= hours;
            total *= 60.0;
            minutes = parseInt(total.toString());
            total -= minutes;
            total *= 60;
            seconds = parseInt(total.toString());
        } else {
            timer.callbacks.onEnd();
            timer.stop();
            timer.base.isEnd = true;
        }

        return {
            days: {
                amount: days,
                max: Infinity,
                className: 'days'
            },
            hours: {
                amount: hours,
                max: 24,
                className: 'hours'
            },
            minutes: {
                amount: minutes,
                max: 60,
                className: 'minutes'
            },
            seconds: {
                amount: seconds,
                max: 60,
                className: 'seconds'
            }
        }
    };

    const transformCountToArray = function (count, multipleBlocks) {
        if (typeof count === 'object') {
            for (const unit in count) {
                if (count.hasOwnProperty(unit)) {
                    count[unit].amount = count[unit].amount.toString();

                    if (count[unit].amount.length < 2) {
                        count[unit].amount = '0' + count[unit].amount;
                    }

                    if (multipleBlocks) {
                        count[unit].amount = count[unit].amount.split('');
                    } else {
                        count[unit].amount = [count[unit].amount];
                    }
                }
            }
        }

        return count;
    };

    const getTimeZone = function (string) {
        let hours, minutes;
        const number = string.replace(/\D/g, '');
        const digit = string.replace(/[^+-]/g, '');
        const multiplier = digit === '-' ? (-1) : 1;

        if (number.length >= 3) {
            hours = Number(number.substr(0, number.length - 2));
            minutes = Number(number.substr(number.length - 2, 2));
        } else {
            hours = Number(number);
            minutes = 0;
        }

        return (hours + minutes/60) * multiplier;
    };

    const setTimerEndFromString = function (timer, endTimeString) {
        const timerDate = {};
        const timerEnd = endTimeString.split(' ');
        let endTime;

        const timeExp = /^([0-1]\d|2[0-3])(:[0-5]\d){1,2}$/;
        const dateExp = /(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d/;
        const zoneExp = /(UTC|GMT)[+-](\d{1,2}([:,.]?\d{2})?)/;

        for (let i = 0; i < timerEnd.length; i++) {
            if (timerEnd[i].match(timeExp)) {
                timerDate.time = timerEnd[i].split(':');
            } else if (timerEnd[i].match(dateExp)) {
                timerDate.date = timerEnd[i].split('.');
            } else if (timerEnd[i].match(zoneExp)) {
                timer.base.timezone = getTimeZone(timerEnd[i]);
            } else {
                console.log('Wrong end time.');
            }
        }

        timerDate.year = parseInt(timerDate.date[2]) || 0;
        timerDate.month = parseInt(timerDate.date[1]) - 1 || 0;
        timerDate.day = parseInt(timerDate.date[0]) || 0;
        timerDate.hours = parseInt(timerDate.time[0]) || 0;
        timerDate.minutes = parseInt(timerDate.time[1]) || 0;
        timerDate.seconds = parseInt(timerDate.time[2]) || 0;
        timerDate.miliseconds = parseInt(timerDate.time[3]) || 0;

        endTime = Date.UTC(timerDate.year, timerDate.month, timerDate.day, timerDate.hours, timerDate.minutes, timerDate.seconds, timerDate.miliseconds);

        return endTime;
    };

    const createMarkup = function (timer) {
        const countdown = timer.countdown;
        const markup = { unit: '' };
        const labels = timer.options.labels;
        let placement = timer.options.labelPlacement;
        if ( 'auto' === placement ) {
            placement = document.dir === 'rtl' ? 'before' : 'after';
        }
        for ( const unit in countdown) {
            if (countdown.hasOwnProperty(unit)) {
                let numberBlocks = '';
                countdown[unit].amount.forEach(function (num) {
                    numberBlocks += numberContainer(timer, num);
                });

                if ( placement !== "outside" ) {
                    const label = ( labels[unit] ? '<div class="psg-timer--label '+unit+'">' + labels[unit] + '</div>' : '');
                    markup.unit += '<div class="' + countdown[unit].className + ' psgTimer_unit">' + ('before' === placement ? label + numberBlocks : numberBlocks + label) + '</div>';
                } else {
                    markup.unit += '<div class="' + countdown[unit].className + ' psgTimer_unit">' + numberBlocks + ' </div>';
                }
            }
        }

        markup.numbers = '<div class="psgTimer_numbers">' + markup.unit + '</div>';

        markup.full = markup.numbers;

        if ( placement !== "outside" ) {
            return markup.full;
        }

        if (
            // timer.options.labels.days &&
            // timer.options.labels.hours &&
            // timer.options.labels.minutes &&
            // timer.options.labels.seconds
            Object.values(labels).filter( item => !! item).length
        ) {

            markup.labels = '<div class="psgTimer_labels">';
            for( const label of Object.keys( labels ) ) {
                markup.labels += '<div class="psg-timer--label '+label+'">' + labels[label] + '</div>';
            }
            markup.labels += '</div>';

            markup.full = markup.numbers + markup.labels;
        } else {
            markup.full = markup.numbers;
        }

        return markup.full;
    };

    const numberContainer = function (timer, num) {
        let data = 'data-number="' + num + '"';

        const numberBlock = '<div class="number" ' + data + '>' + num + '</div>';

        if (timer.options.animation === 'fade') {
            return '<div>' + numberBlock + '</div>';
        } else {
            return numberBlock;
        }
    };

    const setQueries = function (timer) {
        const countdown = timer.countdown;
        const query = {};

        for (const unit in countdown) {
            if (countdown.hasOwnProperty(unit)) {
                query[unit] = timer.container.find(numberSelector(timer, countdown[unit].className));
            }
        }

        return query;
    };

    const numberSelector = function (timer, className) {
        if (timer.options.animation === 'fade') {
            return '.' + className + ' .number';
        } else {
            return  '.' + className + ' .number';
        }
    };

    const updateCounter = function (timer) {
        timer.callbacks.onChangeStart();

        timer.countdown = transformCountToArray(getCurrentCountDown(timer), timer.options.multipleBlocks);

        for (const unit in timer.countdown) {
            if (timer.countdown.hasOwnProperty(unit)) {
                timer.countdown[unit].amount.forEach(function (number, index) {
                    if (timer.query[unit][index].getAttribute('data-number') !== number) {
                        animate(timer.query[unit][index], number, timer.options.animation);
                    }
                });
            }
        }

        timer.callbacks.onChangeEnd();
    };

    const animate = function (el, value, animationType) {
        const $el = $(el);
        $el.attr('data-number', value);

        if (animationType === 'fade') {
            animation.fade($el, value);
        } else {
            $el.html(value);
        }
    };

    const animation = {
        fade: function ($el, value) {
            const animDuration = 350;

            $el.css({
                'transition': 'opacity ' + animDuration + 'ms',
                'opacity': '0'
            });

            setTimeout(function () {
                $el.html(value).css('opacity', 1);
            }, animDuration + 10);
        }
    };

    window.PsgTimer = PsgTimer;
})( jQuery );
