<?php

require('/home/local/COMGE/egter01/emergenze-pcge_credenziali/conn.php');

$cf=$_GET["id"];

$query_cs="SELECT matricola_cf FROM users.utenti_message_update where matricola_cf='".$cf."';";

$check=0; 

$result_cs = pg_query($conn, $query_cs);
while($r_cs = pg_fetch_assoc($result_cs)) {
	$check=1; 
}

if($check==0){
	$query_update="INSERT INTO users.utenti_message_update(matricola_cf) VALUES ('".$cf."');";
} else {
	$query_update="UPDATE users.utenti_message_update
	SET data_ora=now()
	WHERE matricola_cf='".$cf."';";
}
$result = pg_query($conn, $query_update);

//echo $query_update;

//exit;

header('Location: ' . $_SERVER['HTTP_REFERER']);

?> 