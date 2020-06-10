(function () {

    var distance = function (x1, y1, x2, y2) {
        return Math.sqrt(Math.pow(x1 - x2, 2) + Math.pow(y1 - y2, 2));
    }

    // The Dot object used to scaffold the dots
    var Dot = function (x,y,color) {
        this.x = x;
        this.y = y;
        this.color = color;
        this.node = (function () {
            var n = document.createElement("div");
            n.className = "trail";
            document.body.appendChild(n);
            return n;
        }());
    };
    // The Dot.prototype.draw() method sets the position of 
    // the object's <div> node
    Dot.prototype.draw = function () {
        this.node.style.left = this.x + "px";
        this.node.style.top = this.y + "px";
        this.node.style.opacity = this.opacity;
        this.node.style.backgroundColor = this.color;
    };


    var Trail = function (options) {

        this.from = options.from;
        this.to = options.to;
        this.color = options.color;

        this.init();
        this.animate();

    }
    Trail.prototype.init = function () {
        this.dots = [],
        this.x = this.from.x
        this.y = this.from.y
        this.endX = this.to.x;
        this.endY = this.to.y;
        this.easing = 0.035;
        this.fadeOut = false;
        this.circles = 25;

        // Creates the Dot objects, populates the dots array
        for (var i = 0; i < this.circles; i++) {
            this.dots.push(new Dot(this.x, this.y, this.color));
        }
    }
    Trail.prototype.animate = function () {
        var x = this.x;
        var y = this.y;
        var endX = this.endX;
        var endY = this.endY;
        var easing = this.easing;
        var self = this;

        var loop = function () {
            var lastDot = self.dots[self.dots.length - 1];
            var d = distance(self.x, self.y, self.endX, self.endY);

            if (d <= 10) {
                self.destroy();
                return;
            }

            var xd = (endX - x) * easing;
            var yd = (endY - y) * easing;

            self.updateTrails(self.x, self.y);
            self.x += xd;
            self.y += yd;

            requestAnimationFrame(loop)
        }
        loop();
    }

    Trail.prototype.updateTrails = function (x, y) {
        var self = this;
        this.dots.forEach(function (dot, index, dots) {
            var nextDot = dots[index + 1] || dots[0];
            var d = distance(x, y, self.endX, self.endY)

            dot.x = x;
            dot.y = y;
            dot.draw();
            x += (nextDot.x - dot.x) * .1;
            y += (nextDot.y - dot.y) * .1;
            dot.opacity = (1 - (index + 1) / dots.length)
        });
    }
    Trail.prototype.destroy = function () {
        var trails = document.querySelectorAll('.trail');
        trails.forEach(function (e) { 
            e.className += ' fadeOut';
        });
        setTimeout(function(){
            trails.forEach(function (e) { 
                e.parentNode.removeChild(e) 
            });
        }, 300);
    }
    define(function(){
        return Trail;
    })
}());