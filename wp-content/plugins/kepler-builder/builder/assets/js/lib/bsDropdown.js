
define([
    'jquery',
  ], function (
    $
  ) {
    function generateUID() {
      // I generate the UID from two parts here 
      // to ensure the random number provide enough bits.
      var firstPart = (Math.random() * 46656) | 0;
      var secondPart = (Math.random() * 46656) | 0;
      firstPart = ("000" + firstPart.toString(36)).slice(-3);
      secondPart = ("000" + secondPart.toString(36)).slice(-3);
      return firstPart + secondPart;
    }

    return {
        initialize: function (options) {
            this.repositionDropdowns();
        },
        repositionDropdowns: function () {
            // hold onto the drop down menu                                             
            var dropdownMenu;
            var backdrop; 
            var dropdownMenuClone;
            // and when you show it, move it to the body                                     
            $(window).on('show.bs.dropdown', function (e) {

                backdrop = $('<div class="drop-down-backdrop"></div>')
                backdrop.css({
                  position: 'fixed',
                  left: 0,
                  top: 0,
                  right: 0,
                  bottom: 0,
                  zIndex: 99999
                })
                $('body').append(backdrop);
                backdrop.click(function() {

                })
                // grab the menu        
                dropdownMenu = $(e.target).find('> .dropdown-menu');
                
                //assign unique ids to each <li> so that we can refer them to proxy events from cloned dropdown one to original one
                dropdownMenu.find('a').each(function(index) {
                  $(this).attr('data-dropdown-item-id', generateUID());
                })

                // using cloning instead of detaching to keep menu item events bound from backbone views
                dropdownMenuClone = dropdownMenu.clone(true,true)
                
                // detach it and append it to the body
                backdrop.append(dropdownMenuClone);
                
                var eOffset = null;
                var attach = dropdownMenu.data("dropdownAttach");
                var position = dropdownMenu.data("position");
                var $el = $(e.target)
                if(!position){
                  position = "bottom"
                }
                //Attach to child element 
                if(attach){
                  $el = $el.find(attach);
                }
                var eOffset = $el.offset();
                var top = Math.round(eOffset.top);
                var left = Math.round(eOffset.left);
                //Position
                if(position == "bottom") {
                  top = top + $el.height()
                }
                if(position == "right") {
                  left = left + $el.width()
                }
                dropdownMenuClone.css({
                  'display': 'block',
                  'top': parseInt(top),
                  'left': parseInt(left)
                });

                // check if dropdown is off-screen, if so translate it
                var menuRect = dropdownMenuClone.get(0).getBoundingClientRect()
                var translateX = 0;
                var translateY = 0;

                if(menuRect.left < 0) {
                  translateX = '100%'
                } else if((menuRect.left + menuRect.width) > window.innerWidth) {
                  translateX = '-100%'
                }

                if(menuRect.top < 0) {
                  translateY = '100%'
                } else if((menuRect.top + menuRect.height) > window.innerHeight) {
                  translateY = '-100%'
                }

                var transformVal = 'translate3d('+translateX+', '+translateY+',0)'; 
                dropdownMenuClone.css({
                  'transform': transformVal
                });

                // hide original dropdown
                dropdownMenu.css({
                  opacity: 0
                })

                // proxy events 
                
                dropdownMenuClone.on('click', '[data-dropdown-item-id]', function(e){
                  var index = $(this).data('dropdown-item-id')
                  dropdownMenu.find('[data-dropdown-item-id="'+index+'"]').click()
                  backdrop.remove();
                  $('.drop-down-backdrop').remove();// fail safe
                })

                dropdownMenuClone.find('li').hover(function(e){
                  if(!$(this).find('.dropdown-item').next().hasClass('dropdown-menu')) {
                    return;
                  }
                  
                  var subMenu = $(this).find('.dropdown-item').next()
                  var subMenuLeft; 

                  if((menuRect.right + subMenu.width()) > window.innerWidth) {
                    subMenuLeft = -dropdownMenuClone.width()
                  } else {
                    subMenuLeft = dropdownMenuClone.width()
                  }

                  subMenu.show().css({
                    'left': subMenuLeft,
                    'transform': 'translate3d(0, -31px, 0px)'
                  })

                  var subMenuRect = subMenu.get(0).getBoundingClientRect()
                  if(subMenuRect.bottom > window.innerHeight) {
                    subMenu.css({
                      'top': -(subMenuRect.bottom - window.innerHeight),
                      'transform': 'translate3d(0, -10px, 0px)'
                    })
                  }

                }, function(e){
                  var subMenu = $(this).find('.dropdown-item').next()
                  subMenu.hide().removeAttr('style');
                })

            });

            // and when you hide it, reattach the drop down, and hide it normally                                                   
            $(window).on('hide.bs.dropdown', function (e) {
                // $(e.target).append(dropdownMenu.detach());
                dropdownMenu.css({
                  opacity: 1
                });
                backdrop.remove();
                $('.drop-down-backdrop').remove();// fail safe
            });
        },
    };
  });