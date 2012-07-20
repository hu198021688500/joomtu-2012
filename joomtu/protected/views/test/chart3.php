<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />
        <style type="text/css">
            body {
                margin: 0px;
                padding: 0px;
            }
            #container {
                width : 600px;
                height: 384px;
                margin: 8px auto;
            }
        </style>
    </head>
    <body>
        <div id="container"></div><br />
        <div id="container1"></div>
        <!--[if IE]>
        <script type="text/javascript" src="/static/lib/FlashCanvas/bin/flashcanvas.js"></script>
        <![endif]-->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/flotr2.min.js"></script>
        <script type="text/javascript">
            (function () {

                var container = document.getElementById('container'), start = (new Date).getTime(), data, graph, offset, i;
                // Draw a sine curve at time t
                function animate (t) {
                    data = [];
                    offset = 2 * Math.PI * (t - start) / 10000;
                    // Sample the sine function
                    for (i = 0; i < 4 * Math.PI; i += 0.2) {
                        data.push([i, Math.sin(i - offset)]);
                    }
                    // Draw Graph
                    graph = Flotr.draw(container, [ data ], {
                        yaxis : {
                            max : 2,
                            min : -2
                        }
                    });
                    // Animate
                    setTimeout(function () {
                        animate((new Date).getTime());
                    }, 50);
                }
                animate(start);
            })();


            (function basic(container) {
                var d1 = [
                    [0, 3],
                    [4, 8],
                    [8, 5],
                    [9, 13]
                ],
                i, graph;

                graph = Flotr.draw(container, [d1, d2], {
                    xaxis: {
                        minorTickFreq: 4
                    },
                    grid: {
                        minorVerticalLines: true
                    }
                });
            })(document.getElementById("container1"));
        </script>
    </body>
</html>