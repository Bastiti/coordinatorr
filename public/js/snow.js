// Gentle snowfall behind the page content (Christmas theme)
(function () {
    var canvas = document.getElementById('snow');

    //respect users who asked for less motion
    if (!canvas || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    var ctx = canvas.getContext('2d');
    var flakes = [];
    var width = 0;
    var height = 0;
    var last = 0;

    function makeFlake(anywhere) {
        var radius = 0.8 + Math.random() * 2.4;
        return {
            x: Math.random() * width,
            y: anywhere ? Math.random() * height : -10,
            r: radius,
            speed: 0.25 + radius * 0.3, // bigger flakes are closer and fall faster
            phase: Math.random() * Math.PI * 2,
            opacity: 0.35 + Math.random() * 0.5
        };
    }

    function resize() {
        var ratio = Math.min(window.devicePixelRatio || 1, 2);
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = width * ratio;
        canvas.height = height * ratio;
        ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

        //about one flake per 9000 px², at most 140
        var count = Math.min(140, Math.round(width * height / 9000));
        while (flakes.length < count) {
            flakes.push(makeFlake(true));
        }
        flakes.length = count;
    }

    function draw(time) {
        //move at the same speed on 60 Hz and 120 Hz screens
        var step = last ? Math.min((time - last) / 16.7, 3) : 1;
        last = time;

        ctx.clearRect(0, 0, width, height);
        ctx.fillStyle = '#fff';

        for (var i = 0; i < flakes.length; i++) {
            var flake = flakes[i];
            flake.phase += 0.01 * step;
            flake.y += flake.speed * step;
            flake.x += Math.sin(flake.phase) * 0.35 * step;

            if (flake.y > height + 10) {
                flake = flakes[i] = makeFlake(false);
            }

            ctx.globalAlpha = flake.opacity;
            ctx.beginPath();
            ctx.arc(flake.x, flake.y, flake.r, 0, Math.PI * 2);
            ctx.fill();
        }

        window.requestAnimationFrame(draw);
    }

    window.addEventListener('resize', resize);
    resize();
    window.requestAnimationFrame(draw);
})();
