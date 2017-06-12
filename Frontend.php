<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Frontend</title>
    
    <link rel="stylesheet" href="https://openlayers.org/en/v4.0.1/css/ol.css" type="text/css">
    <script src="https://openlayers.org/en/v4.0.1/build/ol.js" type="text/javascript"></script>
    <script src="js/jquery-3.2.1.min.js" type="text/javascript"></script> <!-- integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous" -->
	<link rel="stylesheet" href="css\Frontend.css" type="text/css"/>
    <script src="js/Frontend.js" type="text/javascript">
    </script>
    <?php include './php/functions.php'; ?>


    
    
</head>
<body onload="createMap();">
<?php

//Ordnerstruktur aufbauen, wo automatisch index.html/php-Files reingeladen werden (also Buttons werden automatisch hinzugefuegt.
 
	// Lade Jahreszahlen-Buttons
    $year = createYearButtons();
	

	
	
	
	// Lade linke Sidebar (Layer ausklappbar und auswählbar) Skript zum Ausklappen selbst in Frontend.JS
	/* TODO: Funktion aufrufen, um linke Sidebar zu aktualisieren (Inhalt von Jahresfolder auflisten und automatisch ausklappen)
	geschieht aber erst nach anklicken von einem oder mehreren Jahren, standardmäßig erstes Jahr anzeigen, erst nach anklicken von Burg/Landesgrenzen o.Ä. wird ein Layer
	hinzugefügt oder bei zweimaligem Klicken entfernt (wenn aktiv, dann sollte das erkenntlich sein)*/
	?>
	<div id="chooseYearLayers">
		<div id="mySidenav" class="sidenav">
 			 <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
 			  	<?php 
 			 		createSitemap($year); ?>
			 
		</div>

		<!-- Use any element to open the sidenav -->
		<span onclick="openNav()"><img src="img/open_sidebar_icon.png" id="openSidebar" alt="sidebar" title="Klick mich an" width="50px" height="auto"/></span>

		<!-- Add all page content inside this div if you want the side nav to push page content to the right (not used if you only want the sidenav to sit on top of the page 
		<div id="main">
			  
		</div>-->
	
	</div>
	
	<!-- DIV-Tag fuer Map -->
	<div id="map"></div>
    <div id="slider"></div>
	
	<?php
	// Lade rechte Sidebar (Objekte ausklappbar und anzeigen/ausblenden beim Anklicken (schon vorher laden, damit schneller)
	

?>








</body>
</html>