<?php 
//--------------------------------------------------------------------------------------------
//TOP (YEAR-Buttons)
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

//----------------------------------------------------------------------------------------------
//LEFT (Left-Sidebar)
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
//------------------------------------------------------------------------------------------------------
// Right Sidebar
function createRightSidebar() {
    echo "<div id='right_sidebar'>";
    echo "<a href=\"javascript:void(0)\" class=\"closebtn\" onclick=\"closeRightSidebar()\">&times;</a>";
    echo "<ul id='rs_list'>";

    //TODO: CREATE HERE ALL DROPDOWNS YOU WANT --> automatically by paths and save them in an array or do it in the method itself?!
    //TODO: Create here assoziative arrays for each year (=folder).



    $points['_Hauptpunkt'] = "LINKzuObjekt"; //Hauptpunkte müssen mit einem Unterstrich beginnen, wird bei der Ausgabe aber nicht angezeigt.
    $points['Subpunkt 1'] = "LinkzuUnterobjekt";
    $points['Subpunkt 2'] = "LinkZuUnteremObjekt";
    createNewDropdown($points,"123");
    echo "</ul></div>";
}

function createNewDropdown($points, $unique_string) { //Erstelle neuen Hauptpunkt, der noch Unterpunkte hat
    /*z.B. Hauptpunkt = $points[0], restliche Indizes sind die Unterpunkte. Jeder Inhalt des Arrays ist selbst ein assoziatives Array.
    Der Parameter muss als assoziative Arrays in einem numerischen Array übergeben werden: bei $points[1]: SichtbarerNameDesMenuepunkts -> WertDahinter(z.B. Link zum Inhalt)
    UNQIUE_STRING = willkürlicher String, um Unterpunkte eines Hauptpunkts von einem anderen zu unterscheiden.
    */
    //$count_subs = count($points); //Zähle Anzahl der Unterpunkte

    if(!is_array($points)) {
        echo "ERROR: \$points is not an array!";
    }

    /* $mainpoint = 'IndexAlsString'->'WertHinterIndexAlsLayerOderObjektEtc'
    $subs = numerisches Array wobei z.B. $subs[0] = 'IndexAlsString'->'WertHinterIndexAlsLayerOderObjektEtc'
    */

    foreach($points as $point) {
        $value = $point;
        $name = ucfirst(array_search($point, $points));
        $pos_isMainpoint = strpos($name, '_');

        if ($pos_isMainpoint !== false && $pos_isMainpoint == 0) { //Punkt ist nur Oberpunkt wenn Unterstrich an erster Stelle
            //Hier Onclick-Funktion zum Ein- und Ausklappen der Punkte
            echo "<li class='mainpoint'><span onclick=rs_ShowHideSubpoints('".$unique_string."')> " . substr($name,1)."</span>"; //Gib Schlüssel des assoziativen Arrays zurück
        } else {
            echo "<li class='subpoint hidden " . $unique_string . "'><a href='$value'>" . $name . "</a></li>"; //Hier auch Schlüssel ausgeben, und Link a href mit Wert des assoziativen Arrays
            // Class 'hidden' = hide sub links
        }
    }
    echo "</li>";

}


?>