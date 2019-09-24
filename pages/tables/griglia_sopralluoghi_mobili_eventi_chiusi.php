<?php
session_start();
include '/home/local/COMGE/egter01/emergenze-pcge_credenziali/conn.php';

//require('../check_evento.php');

// Filtro per tipologia di criticità
$getfiltri=$_GET["f"];
//echo $getfiltri;

if ($getfiltri == 'prima_pagina'){
	$filter = ' WHERE id_stato_sopralluogo=2 ';
} else {
	require('./filtri_segnalazioni.php'); //contain the function filtro used in the following line
	$filter=filtro($getfiltri);
}


if(!$conn) {
    die('Connessione fallita !<br />');
} else {
	//$idcivico=$_GET["id"];
	$query="SELECT id, descrizione, descrizione_uo, data_ora_invio,
	id_stato_sopralluogo From segnalazioni.v_sopralluoghi_mobili_eventi_chiusi_last_update ".$filter." ORDER BY id_stato_sopralluogo;";
    
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

