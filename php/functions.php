<?php 

function createYearButtons () {
	$folders = glob('layers/*', GLOB_ONLYDIR);
	echo "<div class='yearButtons'>";
	//echo "<form name='yearSelection' id='yearSelection' action='".$_SERVER['PHP_SELF']."' />";
	$i=0; //tmp var fürs Durchzählen (Array)
    $year = array();
	foreach($folders as $folder) {
		$year[$i++] = substr($folder,-4); // Jahreszahl (last 4 character) rausfiltern
		//BUTTONS: aktiv bis Slider update
        echo "<input type='button' onclick='openNewNav(".$year[$i-1].")' class='yearbutton' id='".$year[$i-1]."' value='".$year[$i-1]."'/>"; //Jahreszahlenbuttons zurückgeben
		}
    //SLIDER:
    /*echo "<script type='text/javascript'>createSlider();</script>";
    echo "<div id='#slider' onblur='openNewNav(".$year[$i-1].")'></div>";*/

	$countedkml = countKmlFiles(0); //make hidden field with counted kml files
	//echo "</form></div>";
    echo "</div>";
    return $year;
}

function createSitemap($year) { //ARRAY wird übergeben
	//hier if class enthält active, dann id als Variable speichern
	/*if (empty($_REQUEST)) {
		$year = 1560;
	} else {
		$year = $_REQUEST['activeyear']; //Nicht zu prüfen ob bereits active, da nach Wechsel von Jahr ohnehin gewechselt wird. 
		
		//Erst hier Nav öffnen (per JS)
		echo "<script type='text/javascript'>openNewNav(".$year.");</script>";

	}*/

    //------------------------------------- NEU
    // Alphabetisch alle Layer von vorne nach hinten in ein oder zwei Arrays speichern (1. Array: Layername, 2. Array: Layer selbst (evtl. assoziatives Array, dann nur eins)
    //z.B. $all_layer_links['layername'] = "layer als objekt bzw. kml file";
    //mit key() function ist für ein aktuelles Array (foreach tmp variable) der Index also Layername rückgebbar
    //WICHTIG: Am besten obere Schleife erst danach über dieses Array, dann kann man genauso standardisiert sagen, was angezeigt werden soll.

    $all_layer_links = glob('layers/*/*.kml'); //speichere alle Layer rein
    $layerindex = 0;
    foreach ($all_layer_links as $link) {
        $layername = substr ($link,12,strlen(substr($link,12))-4); //remove file extension and file path
        $layeryear = substr ($link,7,4);
        $layerid = $layeryear."_".strtolower($layername);
        //echo "Name: ".$layername." / Jahr: ".$layeryear;
        echo "<a href='#' id='".$layerid."' class='yearlink link_".$layeryear."'>".$layername."</a>";

        //Make layers addable/removeable (layers assigned chronologically) NICHT LÖSCHEN MUSS JA LAYERS ADDEN KÖNNEN
        echo "<script type='text/javascript'>";
            echo "$(document).ready(function() {";
                echo "$('#".$layerid."').on('click', function() {";
                    echo "var tmpelement = document.getElementById('".$layerid."');";
                    echo "if (tmpelement.className.indexOf('layerlink_active') !== -1) {"; //Prüfe ob Layer bereits gesetzt (über gesetzte Klasse, siehe addLayer
                        echo "tmpelement.classList.remove('layerlink_active');";
                        echo "window.map.removeLayer(all_layers[" . ($layerindex) . "]);"; //careful with inkrement
                    echo "} else {";
                        echo "tmpelement.className += ' layerlink_active';";
                        echo "window.map.addLayer(all_layers[" . ($layerindex) . "]);";
                    $layerindex++; //Layerindex für nächsten Durchlauf erhöhen.
                    echo "}});});";
        echo "</script>";
    }

    /* OLD VERSION, did not work as expected

     for ($j=0;$j<sizeof($year)-1;$j++) {
        foreach (glob('layers/' . $year[$j] . '/*.kml') as $layer) {
            $layername = substr($layer, 12, strlen(substr($layer, 12)) - 4); //remove file extension and file-path
            echo "<a href='#' id='" . strtolower($layername) . "' class='yearlink link_".$year[$j]."'>" . $layername . "</a>";


            //------------------------------------- ALT
            //calculate layer for operating for each layer (also verschachtelte foreach)
            $folders = glob('layers/*', GLOB_ONLYDIR);
            $countfolders = 0;
            foreach ($folders as $folder) {
                if ($folder < $year[$j]) {
                    //Ausrechnen wie viele Layer in anderen Ordnern drin, die VOR unserem Jahr sind.
                    $countfolders += count(glob('layers/' . $folder . '/*.kml')); //Multiplikator errechnen, wie viele KML-Files sind in Jahresordner, der niedriger ist.
                }
            }
            //davon ausgehen, dass $layers sortiert (alphabetisch)
            //an welcher alphabetischen Reihenfolge steht aktueller Layer (also zusätzliche foreach, da für jeden Layer zu prüfen)
            unset($tmpArray);
            foreach (glob('layers/' . $year[$j] . '/*.kml') as $tmpLayer) {
                $tmpLayername = substr($tmpLayer, 12, strlen(substr($tmpLayer, 12)) - 4);

                //prüfen ob tmplayername und layername --> stelle reihenfolge --> in array speichern alle kml files und dann prüfen welcher inhalt mit layername übereinstimmt, dann index rauslesen
                $tmpArray[] = $tmpLayername;
            }

            $index = NULL;
            for ($i = 0; $i < count($tmpArray); $i++) {
                if ($tmpArray[$i] == $layername) {
                    $index = $i; //Save index, where our layer is
                }
            }

            if ($index === NULL) {
                echo "ERROR [2]: Failed to assign layer.";
            }*/

    //Dadurch, dass Reload weg (nun Array ohne $_REQUEST verfügbar)
    echo "<script type='text/javascript'>openNewNav(".$year[0].");</script>"; //Lade aktuelles Jahr bei Erstaufruf

}

function countKmlFiles($modus) {
	$counted = count(glob('layers/*/*.kml'));
	if ($modus == 0) {
	    //echo "<script type='text/javascript'>console.log('div created')</script>";
		echo "<div id='countKmlFiles' style='display:none;'>".$counted."</div>";
		return $counted; //vorbehaltlich mal zurückgeben
	} else {
		return $counted;
	}
}

?>