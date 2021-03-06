/**
 * Created by kevin on 21.04.2017.
 */

// Create MAP
var map;
var countKmlFiles; //Create Var for counting kml files
var countKmlObjects; //Create Var for counting kml objects in additional data folders


function createMap() {
    map = new ol.Map({
        target: 'map',
        layers: [
            new ol.layer.Tile({ //Standardlayer (normale Karte)
                source: new ol.source.OSM()
            })
        ],
        view: new ol.View({
            center: ol.proj.fromLonLat([11.00, 47.82]), //hier noch vorherige Koordinaten �bergeben
            zoom: 3
        })
    });

    //createLayers(); //create all layers

    countKmlFiles = document.getElementById('countKmlFiles').innerHTML; //Save count kml files
    countKmlObjects = document.getElementById('object_kml_count').innerHTML; //Save count kml objects (for rs_objects_layerconfig.js)
}


//function createLayers() {
/* AVAILABLE LAYERS 
 1. Add here all KML-Files as Layer, to make it available.
 2. This script searches for available layers and displays them, but to make them clickable you must add your files here manually.
 NOTE: If this script finds more KML-Files than added layers, it will display a warning. (hopefully)
 */
//Name vars so that the script knows which year and type it is. evtl. im array?
var all_layers = [];
/* Here you can add your layers:
 ----------------------------------------------------------------------------------------------
 1. Add your layers to the array 'all_layers'.
 2. NOTE, that your layers are added chronologically. That means:
 You have some directories in your layers-Folder (for example):
 layers\1850\
 layers\1915\
 layers\2010\
 .. and each of those folders will contain several KML-files!
 This script assigns your layers automatically to available buttons.

 So, the first layer you add with 'all_layers.push()' should be a layer from the folder with
 the lowest year (1850, in this case). Additionally the first letter of the KML-file has to be
 alphabetically the first file in the concerning folder.

 Which means: all_layers[0] = Folder with lowest year (here: 1850) AND Kml-File starting with the
 letters which come before the starting letters of other Kml-files in the same folder. And please
 follow this scheme; if you do not, you will not get your results.
 */

/* IMPORTANT: Following layers must be added chronologically, which means that they need the same order as
 the KML-Files in your year folders (ASC-Order).
 */

//Layer 1
all_layers.push(new ol.layer.Tile({
    source: new ol.source.OSM()
}));

//Layer 2
all_layers.push(new ol.layer.Vector({
    source: new ol.source.Vector({
        url: "layers/1560/countries_world.kml",
        format: new ol.format.KML({
            extractStyles: true,
            extractAttributes: true,
            maxDepth: 2
        })
    })
}));

//Layer 3
all_layers.push(new ol.layer.Vector({
    source: new ol.source.Vector({
        url: "layers/1880/Landesgrenzen.kml",
        format: new ol.format.KML({
            extractStyles: true,
            extractAttributes: true,
            maxDepth: 2
        })
    })
}));

//Layer 4
all_layers.push(new ol.layer.Vector({
    source: new ol.source.Vector({
        url: "layers/2016/Burgen.kml",
        format: new ol.format.KML({
            extractStyles: true,
            extractAttributes: true,
            maxDepth: 2
        })
    })
}));

// --------------------------------------------------------------------------------------------
//Alert if amount of added layers (=array) and counted Kml-files in your directionary does not equal.
if (countKmlFiles !== all_layers.length) {
    alert("WARNING [1]: \nYour layer-configuration is not up-to-date!");
    console.warn("WARNING: You will get an Error, when you try to add a Layer, which is not configured! (e.g. 'Cannot read property 'ko' of undefined)")
}


// LEFT: Sidebar
/* Set the width of the side navigation to 250px and the left margin of the page content to 250px and add a black background color to body */
function openNewNav(year) { //will be called by page load and rs_YearChange()
    var yearbuttons = null;
    var alllinks = null;
    var seeablelinks = null;
    //Declare following arrays for openNewNav()-function.
    yearbuttons = document.getElementsByClassName('yearbutton');
    alllinks = document.getElementsByClassName('yearlink');
    seeablelinks = document.getElementsByClassName("link_" + year);
    //console.log(yearbuttons);console.log(alllinks);console.log(seeablelinks);

//evtl. auch altes Jahr �bergeben ODER einfach bei allen anderen Buttons pr�fen ob vorhanden und entfernen
    document.getElementById("mySidenav").style.width = "250px";
    //document.getElementById("main").style.marginLeft = "250px"; //Auskommentiert, da main auskommentiert.


    for (var i = 0; i < yearbuttons.length; i++) {
        yearbuttons.item(i).style.backgroundColor = "rgba(0,0,0,1)";
    }

    //hide other links
    for (var j = 0; j < alllinks.length; j++) {
        //console.log(alllinks.item(j));
        alllinks.item(j).style.display = "none"; //hide all clickable layers
    }
    for (var k = 0; k < seeablelinks.length; k++) {
        seeablelinks.item(k).style.display = "block";
    }

    //Remove all layers when year gets switched
    //removeAllLayers(); Eingestellt, siehe Funktion

    //If yearbuttons then aktiviere folgende Zeilen
    /*TMP: document.getElementById(year).style.backgroundColor = "rgba(204,204,204,1)";    //Active zur Klasse hinzuf�gen (noch nicht getestet) // Hier auch active von vorherigem Button entfernen nicht bei close!
    document.body.style.backgroundColor = "rgba(0,0,0,0.4)";*/
}


function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
    //document.getElementById("main").style.marginLeft = "250px"; //Auskommentiert, da leftSidebar genug Platz hat.
    document.body.style.backgroundColor = "rgba(0,0,0,0.4)";

    /*if (document.getElementsByClassName(year).length !== 0) {
        openRightSidebar();
    } //When clicked on the menu icon then open both navigations (when undesired, just comment out), menu will be only opened when additional data is available*/
}

/* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    //document.getElementById("main").style.marginLeft = "0"; //Auskommentiert, da main auskommentiert. 
    document.body.style.backgroundColor = "white";
    //document.getElementById(year).classList.remove = "active"; //Active von der Klasse entfernen
}

/*function removeAllLayers() {
 EINGESTELLT, da wenn Layers bei Jahreswechsel nicht entfernt: Jahresvergleich möglich

 /*for (var i = (map.layers.length - 1); i >= 0; i--) {
 map.removeLayer(map.layers[i]);
 }
 var activelayers = document.getElementsByClassName('layerlink_active'); //Reagiert nicht, keine Fehlermeldung
 for (var l of activelayers) {
 map.removeLayer(l);
 }
 }*/

// RIGHT SIDEBAR ----------------------------------------------------
function closeRightSidebar() {
    document.getElementById("right_sidebar").style.width = "0";
    document.body.style.backgroundColor = "white";
    document.getElementById("yearButtons").style.right = "0";
}

function openRightSidebar() {
    document.getElementById("right_sidebar").style.width = "250px";
    document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
    document.getElementById("yearButtons").style.right = "250px";
}

function rs_YearChange(year) { //Function will be called by Onclick-Yearbuttons
    openNewNav(year); //also open left sidebar when year gets changed

    //Nur Mainpoints einblenden, für Subpoints ist rs_ShowHideSubpoints() zuständig
    const ALLPOINTS = document.getElementsByClassName('rspoint'); /*$('rspoint');*/
    const YEARPOINTS = document.getElementsByClassName(year); //$(year);
    //var allsubpoints = document.getElementsByClassName('rssubpoint');


    //Hide all main- and subpoints by default.
    for (var a = 0; a < ALLPOINTS.length; a++) {
        var listeclass = ALLPOINTS.item(a).classList;
        if (!listeclass.contains('hidden')) {
            listeclass.add('hidden'); //only add when class not active
        }
    }

    if (YEARPOINTS.length === 0 && ALLPOINTS.length > 0) { //Wenn gar keine Daten vorhanden sind (für kein Jahr), dann andere Meldung.
        console.warn("WARNING: Selected year has NO additional data!");
        closeRightSidebar(); //close right sidebar (maybe it was opened before)
    } else if (YEARPOINTS.length === 0 && ALLPOINTS.length === 0) {
        console.warn("WARNING: Could not find any additional data. Maybe your configuration is not up-to-date!");
    } else {
        //Show only mainpoints of the year
        for (var b = 0; b < YEARPOINTS.length; b++) {
            YEARPOINTS.item(b).classList.remove('hidden');
        }

        //Only open right sidebar, when selected year has additional data
        openRightSidebar(); //After Year has been changed, open right sidebar (if not desired, just comment out)
    }
}

function rs_ShowHideSubpoints(uniqueyearstring) { //Ein-/Ausklappen der Unterpunkte in der rechten Sidebar.
    const SUB_POINTS = document.getElementsByClassName(uniqueyearstring);

    for (sub_point of SUB_POINTS) {
        if (sub_point.className.indexOf('hidden') !== -1) {
            sub_point.classList.remove('hidden');
        } else {
            sub_point.classList.add('hidden');
        }
    }
}


// SLIDER (ALPHA) --------------------------------------------------
function createSlider(values) {
    //Create Max/Min routines for slider assignments
    Array.prototype.max = function() {
        return Math.max.apply(null,this);
    }
    Array.prototype.min = function() {
        return Math.min.apply(null,this);
    }

    //var values = [0, 500, 750, 1000, 1250, 1500, 2000, 2500, 75000, 100000, 150000, 200000, 250000, 300000, 350000, 400000, 500000, 1000000];
    //var values = [1880,1900,1940,1950,1990,2016];

    var tooltip = function(event,ui) {
        var curValue = ui.value || values.min();
        var tooltip = '<div class="tooltip"><div class="tooltip-inner">' + curValue + '</div><div class="tooltip-arrow"></div></div>';
        $('.ui-slider-handle').html(tooltip); //attach tooltip to slider handle
    }

    var slider = $("#slider").slider({
        orientation: 'horizontal',
        range: false,
        min: values.min(),
        max: values.max(),
        values: [0],
        slide: function(event, ui) {
            var includeLeft = event.keyCode != $.ui.keyCode.RIGHT;
            var includeRight = event.keyCode != $.ui.keyCode.LEFT;
            var value = findNearest(includeLeft, includeRight, ui.value);
            if (ui.value == ui.values[0]) {
                slider.slider('values', 0, value);
            }
            /*$("#price-amount").html('$' + slider.slider('values', 0) + ' - $' + slider.slider('values', 1));*/
            
            return false;
        },
        change: tooltip,
        stop: function(event,ui) {
            rs_YearChange(ui.value);
        },
        create: tooltip
        /*change: function(event, ui) { 
            getHomeListings();
        }*/
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

    /*for (var value in values) {
        values.push(value.label);
    }
    var width = slider.width() / (values.length - 1);
    slider.after('<div class="ui-slider-legend"><p style="width:' + width + 'px;" class="slider-legends">' + values.join('</p><p style="width:' + width + 'px;" class="slider-legends">') +'</p></div>');*/


}
