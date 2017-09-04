<html>
<head>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script text="text/javascript">
function createSlider() {
    //var values = [0, 500, 750, 1000, 1250, 1500, 2000, 2500, 75000, 100000, 150000, 200000, 250000, 300000, 350000, 400000, 500000, 1000000];
    var values = [1880,1900,1940,1950,1990,2016];
    var slider = $("#slider").slider({
        orientation: 'horizontal',
        range: false,
        min: 1880,
        max: 2016,
        values: [0],
        slide: function(event, ui) {
            var includeLeft = event.keyCode != $.ui.keyCode.RIGHT;
            var includeRight = event.keyCode != $.ui.keyCode.LEFT;
            var value = findNearest(includeLeft, includeRight, ui.value);
            if (ui.value == ui.values[0]) {
                slider.slider('values', 0, value);
            }
            /*else {
                slider.slider('values', 1, value);
            }*/
            /*$("#price-amount").html('$' + slider.slider('values', 0) + ' - $' + slider.slider('values', 1));*/
            return false;
        },
        change: function(event, ui) { 
            getHomeListings();
        }
    });
    function findNearest(includeLeft, includeRight, value) {
        var nearest = null;
        var diff = null;
        for (var i = 0; i < values.length; i++) {
            if ((includeLeft && values[i] <= value) || (includeRight && values[i] >= value)) {
                var newDiff = Math.abs(value - values[i]);
                if (diff == null || newDiff < diff) {
                    nearest = values[i];
                    diff = newDiff;
                }
            }
        }
        return nearest;
    }
}
</script>
</head>
<body>
<div id="slider"></div>
<div id="price-amount"></div>
</body>
</html>
