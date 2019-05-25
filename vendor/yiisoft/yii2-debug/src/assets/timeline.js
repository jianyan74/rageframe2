(function () {
    'use strict';

    var Timeline = function (options) {

        this.options = options;
        var self = this;
        this.init = function () {
            if (this.options.$focus) {
                this.options.$focus.focus();
                delete this.options.$focus;
            }
            self.options.$timeline.find('.debug-timeline-panel__item a')
                .on('show.bs.tooltip', function () {
                    var data = $(this).data('memory');
                    if (data) {
                        self.options.$memory.text(data[0]).css({'bottom': data[1]+'%'});
                    }
                })
                .tooltip();
            return self;
        };
        this.setFocus = function ($elem) {
            this.options.$focus = $elem;
            return $elem;
        };
        this.affixTop = function (refresh) {
            if (!this.options.affixTop || refresh) {
                this.options.affixTop = self.options.$header.offset().top;
            }
            return this.options.affixTop;
        };

        $(document).on('pjax:success', function () {
            self.init()
        });
        $(window).on('resize', function () {
            self.affixTop(true);
        });
        self.options.$header
            .on('dblclick', function () {
                self.options.$timeline.toggleClass('inline');
            })
            .on('click', 'button', function () {
                self.options.$timeline.toggleClass('inline');
            });
        self.options.$search.on('change', function () {
            self.setFocus($(this)).submit();
        });
        self.options.$timeline.affix({
            offset: {
                top: function () {
                    return self.affixTop()
                }
            }
        });
        this.init();
    };

    (new Timeline({
        '$timeline': $('.debug-timeline-panel'),
        '$header': $('.debug-timeline-panel__header'),
        '$search': $('.debug-timeline-panel__search input'),
        '$memory': $('.debug-timeline-panel__memory .scale')
    }));
})();