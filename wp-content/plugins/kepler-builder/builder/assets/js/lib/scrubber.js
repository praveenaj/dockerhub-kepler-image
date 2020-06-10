define([
    'jquery',
], function (
    $
) {
    return {
        initialize: function (options) {
            this.bindEvents();
        },
        bindEvents: function () {
            var input;
            var oldx = 0;
            var oldY = 0;
            var upperLimit = 100;
            var lowerLimit = 0;
            var step = 2;
            var float = false;
            var floatpoints = 1;
            var isFocus = false;
            var defaultValue = 0;
            var isInputDisabled = false;
            var isDragged = false;
            var disabledEvents = false;
            var enableDrag = false;
            $("body").on("mousedown", ".scrubber-input", function (e) {
                if (!disabledEvents) {
                    isFocus = true;
                    float = false;
                    step = 1;
                    lowerLimit = 0;
                    upperLimit = 100;
                    input = $(this).children("input");
                    attrmin = input.attr("min");
                    attrmax = input.attr("max");
                    attrstep = input.attr("step");
                    attrfloatpoints = input.attr("float-points");
                    attrfloat = input.attr("float");
                    attrdefaultValue = input.attr("data-default");
                    isInputDisabled = false;
                    if (attrmin) lowerLimit = attrmin;
                    if (attrmax) upperLimit = attrmax;
                    if (attrfloatpoints) floatpoints = parseInt(attrfloatpoints);
                    if (attrstep) step = parseFloat(attrstep);
                    if (attrfloat) float = attrfloat
                    if (attrdefaultValue) defaultValue = attrdefaultValue
                    $("body").append("<div class='overlay-fixed cursor-scrubber' id='scrubberOverlay' style='z-index: 999999;'></div>");

                    thresholdStartPositve = e.pageX + 45;
                    thresholdStartNegative = e.pageX - 45;
                    setTimeout(function () {
                        if (!isDragged) {
                            disabledEvents = true;
                            $("#scrubberOverlay").remove();
                            $("body").off("mousemove");
                            $(input).css('cursor', "text");
                            // console.log("TURNED OFF DRAG")
                        }
                    }, 250);

                    setTimeout(function () {
                        enableDrag = true;
                    }, 200);

                    $("body").on("blur", "input", function (e) {

                        $(input).removeAttr('style');
                        disabledEvents = false;
                    });

                    $("body").on("mouseup", function (e) {
                        isFocus = false;
                        oldx = 0;
                        oldy = 0;
                        $("#scrubberOverlay").remove();
                        isInputDisabled = false;
                        $(input).attr('disabled', false);
                        if (isDragged) {
                            $(input).select();
                            isDragged = false;
                        }
                        $("body").off("mouseup");
                        $("body").off("mousemove");
                    });


                    $("body").on("mousemove", function (e) {
                        if (isFocus && enableDrag) {
                            isDragged = true;
                            var val = 0;
                            var inputValue = input.val();

                            if (!inputValue) {
                                inputValue = defaultValue;
                            }
                            if (float) {
                                val = parseFloat(parseFloat(inputValue).toFixed(floatpoints));
                            } else {
                                val = parseInt(inputValue);
                            }

                            if (e.pageX < oldx) {
                                val = val - step;
                                input.trigger("change");
                            } else if (e.pageX > oldx) {
                                val = val + step;
                                input.trigger("change");
                            }




                            // if (e.pageY < oldY) {
                            //     val= val + step;  
                            //     input.trigger("change");
                            // } else if (e.pageY > oldY) {
                            //     val = val - step;
                            //     input.trigger("change");
                            // }

                            if (val > upperLimit) {
                                val = upperLimit;
                            }
                            if (val < lowerLimit) {
                                val = lowerLimit
                            }
                            if (!isInputDisabled) {
                                $(input).attr('disabled', true);
                                isInputDisabled = true;
                            }

                            oldx = e.pageX;
                            oldY = e.pageY;
                            input.val(val)
                            input.select();
                        }
                    });
                }
            });



        },
    };
});