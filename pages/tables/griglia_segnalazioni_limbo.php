<?php
session_start();
include '/home/local/COMGE/egter01/emergenze-pcge_credenziali/conn.php';

require('../check_evento.php');




//echo $filter;



// Filtro per tipologia di criticità
$getfiltri=$_GET["f"];
//echo $getfiltri;

require('./filtri_segnalazioni.php'); //contain the function filtro used in the following line
$filtro_c=filtro($getfiltri);

if ($filtro_c=='' and $filter!=''){
	$filter_completo = " WHERE ".$filter." and in_lavorazione is null";
} else if ($filtro_c != '' and $filter!=''){
	$filter_completo = $filtro_c." AND (".$filter .") and in_lavorazione is null" ;
} else if ($filtro_c!='' and $filter==''){
	$filter_completo = $filtro_c ." and in_lavorazione is null";
} else if ($filtro_c=='' and $filter==''){
	$filter_completo = " WHERE in_lavorazione is null ";
}

if(!$conn) {
    die('Connessione fallita !<br />');
} else {
	//$idcivico=$_GET["id"];
	$query="SELECT id, data_ora, id_segnalante, descrizione, id_criticita, criticita, 
       rischio, id_evento, tipo_evento, id_civico, id_municipio, id_operatore, 
       note, in_lavorazione From segnalazioni.v_segnalazioni ".$filter_completo." ;";
	//echo $query;
	// vecchia query per evento attivo.
	/*$query="SELECT id, data_ora, id_segnalante, descrizione, id_criticita, criticita, 
       rischio, id_evento, id_civico, id_municipio, id_operatore, 
       note, lavorazione From segnalazioni.v_segnalazioni WHERE ".$filter .";";*/
    
   //echo $query;
	$result = pg_query($conn, $query);
	#echo $query;
	#exit;
	$rows = array();
	while($r = pg_fetch_assoc($result)) {
    		$rows[] = $r;
    		//$rows[] = $rows[]. "<a href='puntimodifica.php?id=" . $r["NAME"] . "'>edit <img src='../../famfamfam_silk_icons_v013/icons/database_edit.png' width='16' height='16' alt='' /> </a>";
	}
	pg_close($conn);
	#echo $rows ;
	if (empty($rows)==FALSE){
		//print $rows;
		print json_encode(array_values(pg_fetch_all($result)));
	} else {
		echo "[{\"NOTE\":'No data'}]";
	}
}

?>

