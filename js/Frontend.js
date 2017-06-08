/**
 * Created by kevin on 21.04.2017.
 */
 


// Create MAP
var map;
var countKmlFiles; //Create Var for counting kml files
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
}



//function createLayers() {
/* AVAILABLE LAYERS 
1. Add here all KML-Files as Layer, to make it available. 
2. This script searches for available layers and displays them, but to make them clickable you must add your files here manually. 
NOTE: If this script finds more KML-Files than added layers, it will display a warning. (hopefully)
*/
	//Name vars so that the script knows which year and type it is. evtl. im array?
	var all_layers=[];
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
		
	// --------------------------------------------------------------------------------------------	
	//Alert if amount of added layers (=array) and counted Kml-files in your directionary does not equal.
	if (countKmlFiles !== all_layers.length) {
		alert("WARNING [1]: \nYour layer-configuration is not up-to-date!");
	}

	/*
	//OLD Version: For Hidden Formfield instead of Div
	var name = "countKmlFiles";
	if (name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search)) {
		if (decodeURIComponent(name[1]) != all_layers.length) {
			alert("WARNING [1]: Your layer-configuration is not up-to-date!");
		}
	}*/
	
	
	//document.getElementById("layerarr").value = JSON.stringify(all_layers); //circular reference
	//im prinzip muss ich aber nur auf dieses Array im PHP-Skript zugreifen k�nnen. 
	
//}


// LEFT: Sidebar
/* Set the width of the side navigation to 250px and the left margin of the page content to 250px and add a black background color to body */
function refreshNav(year) {
	//Sidebar aktualisieren! evtl. Jahreszahl hierher �bergeben und aus dieser Funktion direkt die PHP-Funktion f�r createSitemap aufrufen, so kein 
	// Problem mit der Jahreszahl f�r den richtigen Ordner
	//BESSER: die createSitemap Funktion hier direkt als Javascript versuchen auszuf�hren (NICHT M�GLICH, Dateien nicht ansprechbar)
}


function openNewNav(year) {
	var yearbuttons = null;var alllinks = null; var seeablelinks = null;
    //Declare following arrays for openNewNav()-function.
    yearbuttons = document.getElementsByClassName('yearbutton');
    alllinks = document.getElementsByClassName('yearlink');
    seeablelinks = document.getElementsByClassName("link_"+year);
    console.log(yearbuttons);console.log(alllinks);console.log(seeablelinks);

//evtl. auch altes Jahr �bergeben ODER einfach bei allen anderen Buttons pr�fen ob vorhanden und entfernen
    document.getElementById("mySidenav").style.width = "250px";
    //document.getElementById("main").style.marginLeft = "250px"; //Auskommentiert, da main auskommentiert.


	for (var i = 0;i<yearbuttons.length;i++) {
		yearbuttons.item(i).style.backgroundColor = "rgba(0,0,0,1)";
	}

	//hide other links
	for (var j = 0; j<alllinks.length;j++) {
		//console.log(alllinks.item(j)); //TODO: Warum null?
		alllinks.item(j).style.display = "none"; //hide all clickable layers
	}
	for (var k = 0; k<seeablelinks.length;k++) {
		seeablelinks.item(k).style.display = "block";
	}

	document.getElementById(year).style.backgroundColor = "rgba(204,204,204,1)";    //Active zur Klasse hinzuf�gen (noch nicht getestet) // Hier auch active von vorherigem Button entfernen nicht bei close!
    document.body.style.backgroundColor = "rgba(0,0,0,0.4)";

}


function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
    //document.getElementById("main").style.marginLeft = "250px"; //Auskommentiert, da main auskommentiert. 
    document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
}

/* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
function closeNav(year) {
    document.getElementById("mySidenav").style.width = "0";
    //document.getElementById("main").style.marginLeft = "0"; //Auskommentiert, da main auskommentiert. 
    document.body.style.backgroundColor = "white";
    //document.getElementById(year).classList.remove = "active"; //Active von der Klasse entfernen
}