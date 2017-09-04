<?php 
//--------------------------------------------------------------------------------------------
function createYearTimeline() {
    $folders = glob('layers/*', GLOB_ONLYDIR);
    echo "<div id='yearButtons'>";

    $i=0; //tmp var fürs Durchzählen (Array)
    $year = array();
    foreach($folders as $folder) {
        $year[$i++] = substr($folder,-4); // Jahreszahl (last 4 character) rausfiltern
        //BUTTONS: aktiv bis Slider update
    }
    //convert php array to js array and send it to createSlider()
    echo "<script type='text/javascript'>$(function() {createSlider(".json_encode($year).");});</script>";


    echo "<div id='slider'></div>";

    countKmlFiles(0, false); //make hidden field with counted kml files
    countKmlFiles(0,true); //make hidden field with counted objects (kml files) for 

    echo "</div>";
    return $year;
}

//TOP (YEAR-Buttons)
function createYearButtons () {
	$folders = glob('layers/*', GLOB_ONLYDIR);
	echo "<div id='yearButtons'>";
	//echo "<form name='yearSelection' id='yearSelection' action='".$_SERVER['PHP_SELF']."' />";
	$i=0; //tmp var fürs Durchzählen (Array)
    $year = array();
	foreach($folders as $folder) {
		$year[$i++] = substr($folder,-4); // Jahreszahl (last 4 character) rausfiltern
		//BUTTONS: aktiv bis Slider update
        echo "<input type='button' onclick='rs_YearChange(".$year[$i-1].")' class='yearbutton' id='".$year[$i-1]."' value='".$year[$i-1]."'/>"; //Jahreszahlenbuttons zurückgeben
		}
    //SLIDER:
    /*echo "<script type='text/javascript'>createSlider();</script>";
    echo "<div id='#slider' onblur='openNewNav(".$year[$i-1].")'></div>";*/

	countKmlFiles(0, false); //make hidden field with counted kml files
    countKmlFiles(0,true); //make hidden field with counted objects (kml files) for rs_objects_layerconfig.js
	//echo "</form></div>";
    echo "</div>";
    return $year;
}

//----------------------------------------------------------------------------------------------
//LEFT (Left-Sidebar)
function createSitemap($year) { //ARRAY wird übergeben
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

    //Dadurch, dass Reload weg (nun Array ohne $_REQUEST verfügbar)
    echo "<script type='text/javascript'>openNewNav(".$year[0].");</script>"; //Lade aktuelles Jahr bei Erstaufruf, in OpenNewNav() wird auch rs_ChangeYear() aufgerufen um die rechte Sidebar anzuzeigen
}

function countKmlFiles($modus,$only_objects) {
    /*MODUS entscheidet ob ein Input-Feld oder nur ein Rückgabewert zurückgegeben wird.
    * ONLY_OBJECTS (bool) entscheidet, ob nur die Layer in der linken Sidebar oder nur in der rechten Sidebar berechnet werden sollen.
     */
    if ($only_objects == true) {
        $pattern = 'layers/*/additional_data/*.kml';
    } else {
        $pattern = 'layers/*/*.kml';
    }
    $counted = count(glob($pattern)); //Count it, maybe this value will be overwritten

    //var_dump(preg_grep('/^\d{6}__.+\.kml$/',glob($pattern), PREG_GREP_INVERT));
	if ($modus == 0 && $only_objects == true) {

	    $counted = 0;
	    foreach(glob($pattern) as $file) {
            if (!preg_match('/^[0-9]{6}__.+\.kml$/i', substr($file,28))) {
                $counted++;
            }
        }

        echo "<div id='object_kml_count' style='display:none;'>".$counted."</div>";
	} else if ($modus == 0) {
	    //echo "<script type='text/javascript'>console.log('div created')</script>";
		echo "<div id='countKmlFiles' style='display:none;'>".$counted."</div>";
	}
	return $counted;
}
//------------------------------------------------------------------------------------------------------
// Right Sidebar
function createRightSidebar() {
    echo "<div id='right_sidebar'>";
    echo "<a href=\"javascript:void(0)\" class=\"closebtn\" onclick=\"closeRightSidebar()\">&times;</a>";
    echo "<ul id='rs_list'>";

    //CREATE HERE ALL DROPDOWNS YOU WANT --> automatically by paths and save them in an array
    //Save KML-Files in additional_data folders within each year. Mainpoints (files with starting '_', Subpoints (normal))
    //Alphabetisch alle Layer von vorne nach hinten in ein Array speichern

    $all_object_links = glob('layers/*/additional_data/*.kml'); //speichere alle Layer/Objects rein
    $objectindex = 0;
    $prev_prefix = "ERROR!";
    foreach ($all_object_links as $link) {
        $objectindex++;
        $objectname = substr($link, 34, strlen(substr($link, 34)) - 4); //remove file extension and file path
        $objectyear = substr($link, 7, 4);
        $object_prefix = substr($link,28,6); //prefix length
        $objectid = $objectyear . "_" . $object_prefix . strtolower($objectname); //Create unique id for each point (independent from year)
        //echo "Name: " . $objectname . " / Jahr: " . $objectyear;

        //Create here assoziatives Array wie unten manuell erzeugt für jeden Hauptpunkt mit Unterpunkten
        //Prefix notwendig, damit entschieden werden kann, welcher Unterpunkt zu welchem Hauptpunkt gehört.
        if (substr($objectname,0,2) == '__' || ($objectindex == (count($all_object_links)))/*is last element*/) { //Es wird davon ausgegangen, dass durch Prefix und Unterstrich genau richtig sortiert wurde
            if ($objectindex == (count($all_object_links))) {
                $points[$objectname] = $link; //adde last point to list before creating Dropdown
                }
            if (!empty($points)) {
                /* WARNING: Created points won't be generated, if there is NO mainpoint (filename starting with '{prefix}__').
                If there are mainpoints but you forget one to mark as a mainpoint, then all real subpoints and the actual mainpoint
                will be handled as mainpoints!

                We have to use /$prev_prefix not the current object because that is already a new prefix in the iteration!*/
                createNewDropdown($points,$prev_prefix); //Other solution for prefix: md5(uniqid(rand(), true))
            }
            $points = array(); //Array leeren, soll aber Array bleiben (Egal ob var leer oder nicht.
        }
        //Wenn Mainpoint dann wird das auch als Objekt hier reingespeichert.
        $points[$objectname] = $link; //TODO: Derweil lediglich Link als String reingespeichert (evtl. später mit Jquery o.Ä. oder was anderes hier statt Link rein)
        $prev_prefix = $object_prefix; //save prev prefix for printing the dropdown menu

        //Hauptpunkt muss im Index '0' gespeichert werden, also erster Wert im assoziativen Array
        /* Static-Version:
        $points['_Hauptpunkt'] = "LINKzuObjekt"; //Hauptpunkte müssen mit einem Unterstrich beginnen, wird bei der Ausgabe aber nicht angezeigt.
        $points['Subpunkt 1'] = "LinkzuUnterobjekt";
        $points['Subpunkt 2'] = "LinkZuUnteremObjekt";
        createNewDropdown($points,"123"); //Standardmäßig werden alle zwar erstellt aber nicht angezeigt (inkl. Mainpoints)
        */

    }

    echo "</ul></div>";
}

function createNewDropdown($points, $unique_string) { //Erstelle neuen Hauptpunkt, der noch Unterpunkte hat
    /*z.B. Hauptpunkt = $points[0], restliche Indizes sind die Unterpunkte. Jeder Inhalt des Arrays ist selbst ein assoziatives Array.
    Der Parameter muss als assoziative Arrays in einem numerischen Array übergeben werden: bei $points[1]: SichtbarerNameDesMenuepunkts -> WertDahinter(z.B. Link zum Inhalt)
    UNQIUE_STRING = willkürlicher String, um Unterpunkte eines Hauptpunkts von einem anderen zu unterscheiden.
    */
    //$count_subs = count($points); //Zähle Anzahl der Unterpunkte

    //WICHTIG: Diese Funktion erstellt ALLE Main- und Subpoints aller Jahresfolder! Angezeigt werden Sie nach Auswählen eines Jahres.

    if(!is_array($points)) {
        echo "ERROR: \$points is not an array!";
    }

    /* $mainpoint = 'IndexAlsString'->'WertHinterIndexAlsLayerOderObjektEtc'
    $subs = numerisches Array wobei z.B. $subs[0] = 'IndexAlsString'->'WertHinterIndexAlsLayerOderObjektEtc'
    */

    $xth_subpoint = 0;
    foreach($points as $point) {
        $value = $point;
        $year = substr($value,7,4);
        $name = ucfirst(array_search($point, $points));
        $pos_isMainpoint = strpos($name, '__'); //Hauptpunkt = erster Mainpoint, wenn keiner vorhanden werden nur die Unterpunkte erstellt (Resultat: Alle Punkte werden wie ein Mainpoint behandelt, aber keine Unterpunkte mehr möglich)

        //empty will be interpreted as false
        if ($pos_isMainpoint !== false && $pos_isMainpoint == 0) { //Punkt ist nur Oberpunkt wenn doppelter Unterstrich vorhanden und an erster Stelle [da Name ja schon isoliert von Prefix] (nach Prefix)
            //Hier Onclick-Funktion zum Ein- und Ausklappen der Punkte // Maintpoint hidden (standardmässig), damit nur für jeweiliges Jahr angezeigt
            echo "<li class='".$year." rspoint mainpoint hidden' id='m_".$year. "_" . $unique_string . "'><span onclick=rs_ShowHideSubpoints('".$year."_".$unique_string."')> " . substr($name,2)."</span>"; //Gib Schlüssel des assoziativen Arrays zurück
            //Mainpoint darf den Präfix nicht als Klasse verwenden, da dieser sonst mit ein-/ausgeblendet wird beim Anklicken! Deshalb 'm_{prefix}', so auch dieser extra ansprechbar
            //rspoint wird in JavaScript benötigt beim Jahreswechsel // Jahresklasse nur bei Mainpoints, damit nur diese beim Jahreswechsel eingeblendet werden!!
        } else { //Bei Wechsel von Jahr diese Sidebar aktualisieren, indem andere Punkte auf hidden und angezeigt gestellt
            $ob_layergroup = $year. "_" . $unique_string;
            echo "<li class='rspoint subpoint hidden ".$ob_layergroup."'><a href='#' id='".$ob_layergroup."_".$xth_subpoint."'>" . substr($name,1) . "</a></li>"; //Hier auch Schlüssel ausgeben, und Link a href mit Wert des assoziativen Arrays
            // Class 'hidden' = hide links // Year in Kombination mit UniqueString verhindert, dass Subpoints anderer Jahre eingeblendet werden, wenn doch derselbe UniqueString verwendet wird.

            //Assign LayerOnclick-Property
            /*Make layers addable/removeable (layers assigned chronologically)
            Mainpoints have no layer! Do not add a layer to a mainpoint or all assigned layers will be wrong! */
            echo "<script type='text/javascript'>";
            echo "$(document).ready(function() {";
            echo "$('#".$ob_layergroup."_".$xth_subpoint."').on('click', function() {"; //If Subpoints have no mainpoint, here will come up an error!
            echo "var tmpelement = document.getElementById('".$ob_layergroup."_".$xth_subpoint."');";
            echo "if (tmpelement.className.indexOf('layerlink_active') !== -1) {"; //Prüfe ob Layer bereits gesetzt (über gesetzte Klasse, siehe addLayer
            echo "tmpelement.classList.remove('layerlink_active');";
            echo "window.map.removeLayer(all_objects[" . ($xth_subpoint) . "]);"; //careful with inkrement
            echo "} else {";
            echo "tmpelement.className += ' layerlink_active';";
            echo "window.map.addLayer(all_objects[" . ($xth_subpoint) . "]);";
            $xth_subpoint++; //Layerindex für nächsten Durchlauf erhöhen.
            echo "}});});";
            echo "</script>";
        }
    }
    echo "</li>"; //schließe mainpoint

}


?>