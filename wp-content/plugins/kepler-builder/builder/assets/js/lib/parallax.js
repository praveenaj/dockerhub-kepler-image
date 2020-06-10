/*
* Parallax Plugin 
*/
(function($) {
    'use strict';
    // PARALLAX CLASS DEFINITION
    // ======================

    var Parallax = function(element, options) {
        this.$element = $(element);
        this.body = $("#clientframe").contents().find("body");
        this.windowHeight = this.body.height();
        this.options = $.extend(true, {}, $.fn.parallax.defaults, this.$element.data());
        this.options.depth = ((this.options.depth / -10) * -1) / 2;
        this.intialTransform = this.getTransform();
        this.intialRect = this.$element[0].getBoundingClientRect();
        this.animate();
    }
    Parallax.VERSION = "1.0.0";

    Parallax.prototype.getTransform = function(){
        var element = this.$element[0];
        var style = window.getComputedStyle(element);
        var transform = style.getPropertyValue("-webkit-transform") ||
        style.getPropertyValue("-moz-transform") ||
        style.getPropertyValue("-ms-transform") ||
        style.getPropertyValue("-o-transform") ||
        style.getPropertyValue("transform");
        return transform;
    },

    Parallax.prototype.matrixToArray = function(transform,scrollPos){
        var values = transform.match(/([-+]?[\d\.]+)/g);
        var obj = {};
        if(values){
            //Matrix to CSS
            if(values.length == 6){
                var ypos = parseInt(values[5]) + (scrollPos * this.options.depth);
                obj.rotate = (Math.round(
                    Math.atan2(parseFloat(values[1]),parseFloat(values[0])) * (180/Math.PI)) || 0).toString() + 'deg';
                obj.translate = ypos ? values[4] + 'px, ' + ypos + 'px' : (values[4] ? values[4] + 'px' : '');
                obj.scale = parseFloat(Math.sqrt(values[0]*values[0] + values[1]*values[1])).toFixed(3);
                return obj;
            }
        }
    },

    Parallax.prototype.animate = function() {
        // console.log(this.options.depth);
        var rect = this.$element[0].getBoundingClientRect();
        var offset = rect.top;
        if(this.intialRect.top >= this.windowHeight){
            var scrollPos = Math.round(((offset - (this.windowHeight / 2) + this.intialRect.height) - this.$element.scrollTop()) * this.options.depth);
        }else{
            var scrollPos = Math.round(((offset - (this.windowHeight / 2) + this.intialRect.height) -  this.body.scrollTop()) * this.options.depth);
        }
        
        var obj = this.matrixToArray(this.intialTransform,scrollPos) || {};
        if(this.matrixToArray(this.intialTransform)){
            this.$element.css({
                'transform': 'translate('+obj.translate+') scale('+obj.scale+') rotate('+obj.rotate+') ',
                'transition':'none'
            });
        }else{
            this.$element.css({
                'transform': 'translateY(' + scrollPos + 'px)',
                'transition':'none'
            });
        }
    

    }

    // PARALLAX PLUGIN DEFINITION
    // =======================
    function Plugin(option) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data('kp.parallax');
            var options = typeof option == 'object' && option;

            if (!data) $this.data('kp.parallax', (data = new Parallax(this, options)));
            if (typeof option == 'string') data[option]();
        })
    }

    var old = $.fn.parallax

    $.fn.parallax = Plugin
    $.fn.parallax.Constructor = Parallax


    $.fn.parallax.defaults = {
        depth:1,
        scrollElement: window
    }

    // PARALLAX NO CONFLICT
    // ====================

    $.fn.parallax.noConflict = function() {
        $.fn.parallax = old;
        return this;
    }
})(window.jQuery);
