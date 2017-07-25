/**
 * Created by kevin on 25.07.2017.
 */
/*
!! IMPORTANT, please notice: MAIN-Points will NOT be addable to your map! Mainpoints are only for grouping sub points (nevertheless you just have to add an empty KML-File with two underscores)

AVAILABLE Objects
 1. Add here all KML-Files as Layer, to make it available.
 2. This script searches for available layers and displays them, but to make them clickable you must add your files here manually.
 NOTE: If this script finds more KML-Files than added layers, it will display a warning. (hopefully)
 */
//all_objects[] is an assoziative array and contains several other arrays which group it by years.
var all_objects = []; //ONLY YEAR-Subarrays

// TODO: CONFIGURATION-STEPS
// 1. Create HERE for each year-folder an array! Important: Follow the name scheme! [--> y+{year}]
var y1560 = [];
var y1880 = [];
var y2016 = [];

//-----------------------------------------------------------
//2. Configure your objects and save it to your Year-Arrays (not an assoziative array)
//IMPORTANT: Same as in all_layers[] (Frontend.js), you MUST add your layers here chronologically (= ASC)
// MAINPOINTS MUST NOT BE ADDED HERE! Mainpoints are only for grouping subpoints and should be empty KML-Files.
y1560.push(new ol.layer.Tile({
    source: new ol.source.OSM()
}));

y1560.push(new ol.layer.Tile({
    source: new ol.source.OSM()
}));

y1880.push(new ol.layer.Vector({
    source: new ol.source.Vector({
        url: "layers/1560/countries_world.kml",
        format: new ol.format.KML({
            extractStyles: true,
            extractAttributes: true,
            maxDepth: 2
        })
    })
}));

//------------------------------------------------------------
//3. Add those Arrays into the grouping array all_objects[]
all_objects.push(y1560, y1880, y2016);


/* Here you can add your layers:
 ----------------------------------------------------------------------------------------------
 1. Add your layers to the array 'all_objects'.
 2. NOTE, that your layers are added chronologically. That means:
 You have some directories in your layers-Folder (for example):
 layers\1850\additional_data\
 layers\1915\additional_data\
 layers\2010\additional_data\
 .. and each of those folders will contain several KML-files!
 This script assigns your layers automatically to available links.


 IMPORTANT: Following layers must be added chronologically, which means that they need the same order as
 the KML-Files in your year folders (ASC-Order).
 */

// --------------------------------------------------------------------------------------------
//Alert if amount of added layers (=array) and counted Kml-files in your directionary does not equal.
//Count configured objects
var count_configuredObj = 0;
for (var yearobj of all_objects) {
    for (var k = 0; k < yearobj.length; k++) {
        count_configuredObj++;
    }
}

//Same amount of configured objects and available objects? (= kml Files)
if (document.getElementById('object_kml_count').innerHTML !== all_objects.length) {
    alert("WARNING [RS]: \nYour object-configuration is not up-to-date!");
    console.warn("WARNING: Also non configured-objects will produce errors (e.g. Cannot read property 'ko' of undefined.");
}

