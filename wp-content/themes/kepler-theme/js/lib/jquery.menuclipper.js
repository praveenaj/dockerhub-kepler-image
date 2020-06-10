// Adapted from  https://codepen.io/lukejacksonn/pen/BowbWE
(function($, window, document, undefined) {
    'use strict';
    
    var $menu = $("#main-nav .menu");
    var $btn = $('#main-nav .menu-toggle');
    var $vlinks = $('#main-nav .menu > ul');
    var $hlinks = $('#main-nav .hidden-links');
    var $vlinksClone = $vlinks.clone()

    var numOfItems = 0;
    var totalSpace = 0;
    var breakWidths = [];

    $menu.css('opacity', 0)

    function reset() {

        $btn = $('#main-nav .menu-toggle');
        $vlinks = $('#main-nav .menu > ul');
        $hlinks = $('#main-nav .hidden-links');


        numOfItems = 0;
        totalSpace = 0;
        breakWidths = [];


        $hlinks.empty()
        $vlinks.empty()

        $vlinksClone.children().each(function() {
            var clone = $(this).clone();
            clone.appendTo($vlinks);
        });



    }

    function init() {

        

        // Get initial state
        $vlinks.children().outerWidth(function(i, w) {
            totalSpace += w;
            numOfItems += 1;
            breakWidths.push(totalSpace);
        });
    }
    

    var availableSpace, numOfVisibleItems, requiredSpace;

    function debounce(func, wait, immediate) {
        // 'private' variable for instance
        // The returned function will be able to reference this due to closure.
        // Each call to the returned function will share this common timer.
        var timeout;
      
        // Calling debounce returns a new anonymous function
        return function() {
          // reference the context and args for the setTimeout function
          var context = this,
            args = arguments;
      
          // Should the function be called now? If immediate is true
          //   and not already in a timeout then the answer is: Yes
          var callNow = immediate && !timeout;
      
          // This is the basic debounce behaviour where you can call this 
          //   function several times, but it will only execute once 
          //   [before or after imposing a delay]. 
          //   Each time the returned function is called, the timer starts over.
          clearTimeout(timeout);
      
          // Set the new timeout
          timeout = setTimeout(function() {
      
            // Inside the timeout function, clear the timeout variable
            // which will let the next execution run when in 'immediate' mode
            timeout = null;
      
            // Check if the function already ran with the immediate flag
            if (!immediate) {
              // Call the original function with apply
              // apply lets you define the 'this' object as well as the arguments 
              //    (both captured before setTimeout)
              func.apply(context, args);
            }
          }, wait);
      
          // Immediate mode and no wait timer? Execute the function..
          if (callNow) func.apply(context, args);
        }
    }
      
    function check() {

       
        // Get instant state
        availableSpace = $vlinks.width() - 100;
        numOfVisibleItems = $vlinks.children().length;
        requiredSpace = breakWidths[numOfVisibleItems - 1];

        // There is not enought space
        if (requiredSpace > availableSpace) {
            $vlinks.children().last().prependTo($hlinks);
            numOfVisibleItems -= 1;
            check();
        // There is more than enough space
        } else if (availableSpace > breakWidths[numOfVisibleItems]) {
            $hlinks.children().first().appendTo($vlinks);
            numOfVisibleItems += 1;
        }
        // Update the button accordingly
        $btn.attr("count", numOfItems - numOfVisibleItems);
        if (numOfVisibleItems === numOfItems) {
            $btn.addClass('hidden');
            $menu.removeClass('has-more');
        } else {
            $menu.addClass('has-more');
            $btn.removeClass('hidden');
        }
    }

    function onResize() {
        reset();
        init();
        check();
    }
    

    var debouncedMouseMove = debounce(onResize, 100);

    // Window listeners
    $(window).resize(debouncedMouseMove);

    $btn.on('click touch', function(e) {
        e.stopPropagation()
        $hlinks.toggleClass('hidden');
    });

    $(document).on('click touch', function(e){
        $('.hidden-links').addClass('hidden');
    })


    $(window).load(function(){
        onResize();
        $menu.css('opacity', 1)
    });

})(jQuery, window, document);