/*
* Tour Plugin 
*/
(function($) {
    'use strict';
    var $container,$steps,$backdrop, options

    var methods = {
        init : function(_options) {
            $container = $(this);
            options = $.extend(true, {}, $.fn.tour.defaults, _options);

            $backdrop = $('<div/>')
            $backdrop.addClass('kp-tour-backdrop')
            $container.append($backdrop)
            
            $steps = $container.find('.kp-tour-step')
            $container.find('[data-dismiss]').on('click', function() {
                $container.find('.tour-focus-anchor').remove()
                $(this).parents('[data-step]').fadeOut()
                $backdrop.hide()

                var index = $(this).data('index')
                options.steps[index].onClose && options.steps[index].onClose()
            })

            options.steps.forEach(function(step, i) {
                var el = $(step.selector)
                el.find('[data-dismiss]').attr('data-index', i)
            })
        },
        goToStep : function(step, _anchor, parent) {    
            $steps.fadeOut()

            var stepOptions = options.steps[step - 1]
            var $stepEl = $(stepOptions.selector)
            var $anchorEl = typeof stepOptions.anchor == 'string' ? $(stepOptions.anchor) : stepOptions.anchor

            if(_anchor) {
                $anchorEl = _anchor
            }

            if($anchorEl && $anchorEl.length){
                var anchorClientRect = $anchorEl.get(0).getBoundingClientRect()
                var parentLeftOffset = 0;
                var parentRightOffset = 0;
                var parentWidth = 0;

                if(parent && parent.length) {
                    var parentClientRect = parent.get(0).getBoundingClientRect() 
                    parentLeftOffset = parentClientRect.left 
                    parentRightOffset = parentClientRect.top
                    parentWidth = parentClientRect.width
                }

                $stepEl.css({
                    left: parentLeftOffset + anchorClientRect.left + anchorClientRect.width,
                    top: anchorClientRect.top
                })

                var $anchorClone = $('<div/>')
                .css({
                    position: 'absolute',
                    width: anchorClientRect.width,
                    height: anchorClientRect.height,
                    left: parentLeftOffset + anchorClientRect.left,
                    top: parentRightOffset + anchorClientRect.top,
                    zIndex: 99999
                })
                .appendTo($container)
                .addClass('tour-focus-anchor')
            }

            $stepEl.fadeIn().addClass('shake')

            if(stepOptions.backdrop === false) {
                $backdrop.hide()
            } else {
                $backdrop.show()
            }

        },
        dismiss: function(step) {
            var index = step - 1
            var stepOptions = options.steps[index]
            var $stepEl = $(stepOptions.selector)
            if($stepEl.is(":hidden")) { // if already hidden don't continue. 
                return;
            }

            $container.find('.tour-focus-anchor').remove()
            $stepEl.fadeOut()
            $backdrop.hide()

            options.steps[index].onClose && options.steps[index].onClose()
        }
    };

    $.fn.tour = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  methodOrOptions + ' does not exist on jQuery.tour' );
        }    
    };

    $.fn.tour.defaults = {
        onClose: function() {},
        backdrop: true
    }

})(window.jQuery);
