// -----------------------------------------------------------------------
// eros@recoding.it
// jquery.seek.js  v1.0 
// - 03/06/2006 - some corrections
// - 14/05/2009 - first sketch
// Plugin to seek elements in the page
// requires jQuery 1.3.x
// Suggestion: also use the "jQuery Easing v1.3" plugin (http://gsgd.co.uk/sandbox/jquery/easing/) for amazing animation ease functions
//------------------------------------------------------------------------

(function($) {
    var opt;

    $.fn.seekIterate = function(i) { 
        var $els = this;
        $els.eq(i).seekExecute();
        setTimeout( function() { if (i < $els.length - 1) { $els.seekIterate(i + 1); } }, opt.multiple.interval);
    }
    
    $.fn.seekExecute = function() { 
        this.each( function(i) {
            var $el = jQuery(this);
            var $startFrom = opt.startFrom;
            var $hi = $("<div id='divHilight" + i + "' />");
            
            $hi.css({   width: $startFrom[0] == window ? $startFrom.width() - 10 : $startFrom.outerWidth(), 
                        height: $startFrom[0] == window ? $startFrom.height() - 10 : $startFrom.outerHeight(),
                        "background-color": opt.css.backgroundColor, 
                        "border-color": opt.css.borderColor, 
                        "border-style": opt.css.borderStyle, 
                        "border-width": opt.css.borderWidth, 
                        position: "absolute", 
                        top: $startFrom[0] == window ? 0 : $startFrom.offset().top,
                        bottom: 0, 
                        right: 0,
                        left: $startFrom[0] == window ? 0 : $startFrom.offset().left,
                        padding: "0px" })
            .appendTo("body")
            .animate({  top: $el.offset().top - $hi.css("border-top-width").replace("px", ""), 
                        left: $el.offset().left - $hi.css("border-left-width").replace("px", ""), 
                        width: $el.outerWidth(),
                        height: $el.outerHeight() }, 
                        opt.animation.speed, 
                        opt.animation.easeFunction,
                        function() { $hi.fadeOut(2000, function() { $hi.remove(); }); 
                    });
        });
    }

    $.fn.seek = function(options) {
        opt = $.extend({}, $.fn.seek.defaults, options);
        if (options && options.css) { opt.css = $.extend({}, $.fn.seek.defaults.css, options.css); }
        if (options && options.animation) { opt.animation = $.extend({}, $.fn.seek.defaults.animation, options.animation); }
        if (options && options.multiple) { opt.multiple = $.extend({}, $.fn.seek.defaults.multiple, options.multiple); }

        var $els = this;

        if (opt.multiple.simultaneous) { $els.not(opt.startFrom).seekExecute(); }
        else { $els.not(opt.startFrom).seekIterate(0); }
    }
    
    $.fn.seek.defaults = {
		css: {  backgroundColor: "Transparent", 
                borderColor: "#ff4444", 
                borderStyle: "solid", 
                borderWidth: "1px", 
                position: "absolute", 
                top: 0,
                bottom: 0,
                right: 0,
                left: 0 },
        startFrom: $(window),
        animation: { easeFunction: "swing",
                     speed: 500 },
        multiple: { simultaneous: true, 
                    interval: 800 }
	};
})(jQuery);