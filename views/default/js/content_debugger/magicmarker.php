<?php if (false): ?><script type="text/javascript"><?php endif; ?>

/*
 * Magic marker
 *
 * Highlights selected element on screen
 * Can be applied hierarchically
 *
 */

(function($) {

    $.MagicMarker = function(element, options) {
        this.$element = $(element);
        this.options = options;

        this.mask = null;
        this.loaded = false;
        this.origzIndex = 0;

        this.init();
    };

    $.MagicMarker.prototype = {

            init: function() {
                // Save the magicmarker instance inside the DOM node
                this.$element.data('magicmarker', this);
            },

            viewport: function() {
                // the horror case
                if ($.browser.msie) {
                    // if there are no scrollbars then use window.height
                    var d = $(document).height(), w = $(window).height();
                    return [
                        window.innerWidth ||                             // ie7+
                        document.documentElement.clientWidth ||     // ie6
                        document.body.clientWidth,                     // ie6 quirks mode
                        d - w < 20 ? w : d
                    ];
                }
                // other well behaving browsers
                return [$(document).width(), $(document).height()];
            },

            show: function() {
                if (this.loaded) { return true; }

                var temp_element = this.$element;
                $.each(this.options.css_markers, function(key, value) {
                    temp_element.data('magicmarker' + key, temp_element.css(key));
                    temp_element.css(key, value);
                });

                if (this.options.apply_mask) {
                    if (!this.mask) {
                        this.mask = $('<div/>').attr("id", this.options.maskId);
                        this.$element.after(this.mask);
                    }

                    // set position and dimensions
                    var size = this.viewport();

                    this.mask.css({
                        display: 'none',
                        position:'fixed',
                        top: 0,
                        left: 0,
                        width: size[0],
                        height: size[1],
                        opacity: this.options.startOpacity,
                        backgroundColor: this.options.color,
                        zIndex: this.options.zIndex,
                        pointerEvents: 'none'
                    });

                    if (this.options.onShow() === false) {
                        return this;
                    }
                    this.origzIndex = this.$element.eq(0).css("zIndex");

                    $.each(this.$element, function() {
                        var element_part = $(this);
                        if (!/relative|absolute|fixed/i.test(element_part.css("position"))) {
                            element_part.css("position", "relative");
                        }
                    });

                    // make elements sit on top of the mask
                    this.$element.css({
                        zIndex: Math.max(this.options.zIndex + 5, (this.origzIndex === 'auto' ? 0 : this.origzIndex))
                    });

                    // reveal mask
                    this.mask.css({display: 'block'});
                    this.fit();
                }


                this.loaded = true;
            },

            hide: function() {
                if (this.loaded) {
                    var temp_element = this.$element;
                    $.each(this.options.css_markers, function(key, value) {
                        var origvalue = temp_element.data('magicmarker' + key).replace('magicmarker', '');
                        temp_element.css(key, origvalue);
                    });

                    if (this.mask) {
                        if (this.options.apply_mask) {
                            if (this.options.onHide() === false) {
                                return this;
                            }
                            this.mask.css({display: 'none'});
                            this.$element.css({zIndex: this.origzIndex});
                            $(window).unbind("resize.mask");
                        }
                    }
                    this.loaded = false;
                }

            },

            fit: function() {
                if (this.loaded && this.mask) {
                    var size = this.viewport();
                    this.mask.css({width: size[0], height: size[1]});
                }
            }
    };


    $.fn.magicmarker = function(options) {
        // If called with a function name, execute requested function
        if (typeof options === 'string') {
            return this.each(function() {
                var magicmarker = $(this).data('magicmarker');
                if (magicmarker && magicmarker[options])
                    magicmarker[options]();
            });
        }

        // Else initalize a new MagicMarker object
        var opts = $.extend({}, $.fn.magicmarker.defaults, options);
        return this.each(function() {
            new $.MagicMarker(this, opts);
        });

    };

    // default settings
    $.fn.magicmarker.defaults = {
        maskId :            'mmMask',
        zIndex :            1000,
        opacity :           0.3,
        startOpacity :      0.3,
        color :             '#000',
        onShow :            function() { return true; },
        onHide :            function() { return true; },
        css_markers :       {},
        apply_mask:         1
    };

})(jQuery);
<?php if (false): ?></script><?php endif; ?>
