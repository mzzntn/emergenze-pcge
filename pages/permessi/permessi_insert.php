<?php

session_start();

include '/home/local/COMGE/egter01/emergenze-pcge_credenziali/conn.php';

$matr=str_replace("'", "", $_POST["matr"]);
$cf=str_replace("'", "", $_POST["cf"]);

echo "cf:".$cf."<br>";
$matr_cf=$matr;
$matr_cf=$matr_cf."".$cf;
echo "matricola_cf:".$matr_cf."<br>";
$profilo=$_POST["profilo"];
echo "profilo:".$profilo."<br>";

// verifico se necessario update o insert
$check_update=0;
$query0= "SELECT * FROM users.utenti_sistema where matricola_cf='".$matr_cf."' ;";
$result0 = pg_query($conn, $query0);
while($r0 = pg_fetch_assoc($result0)) {
    $check_update=1;
}
 
echo "check_update:".$check_update."<br>";


if ($profilo=='no' and $check_update==1 ){
	$query="DELETE FROM users.utenti_sistema WHERE matricola_cf='".$matr_cf."' ;";
	echo $query;
	$result = pg_query($conn, $query);
	$query_log= "INSERT INTO varie.t_log (schema,operatore, operazione) VALUES ('users','".$_SESSION["Utente"] ."', 'Rimossi permessi di : ".$matr_cf."');";
	$result = pg_query($conn, $query_log);


} else if ($profilo!='no' and $check_update==1){
	$query="UPDATE users.utenti_sistema SET id_profilo=".$_POST["profilo"]." where matricola_cf='".$matr_cf."' ;";
	echo $query;
	$result = pg_query($conn, $query);
	$query_log= "INSERT INTO varie.t_log (schema,operatore, operazione) VALUES ('users','".$_SESSION["Utente"] ."', 'Update permessi di : ".$matr_cf."');";
	$result = pg_query($conn, $query_log);


} else  if ($profilo!='no' and $check_update==0){
	$query="INSERT INTO users.utenti_sistema (matricola_cf, id_profilo) VALUES (";
	$query=$query. "'".$matr_cf."', ". $profilo.");";
	echo $query;
	$result = pg_query($conn, $query);
	$query_log= "INSERT INTO varie.t_log (schema,operatore, operazione) VALUES ('users','".$_SESSION["Utente"] ."', 'Aggiunta permessi utente : ".$matr_cf."');";
	$result = pg_query($conn, $query_log);
} else {
	echo "Non faccio niente <br>";
}





//$idfascicolo=str_replace('A','',$idfascicolo);
//$idfascicolo=str_replace('B','',$idfascicolo);
echo "<br>";
echo $query_log;

//exit;
if ($matr==''){
	header("location: ../lista_volontari.php");
} else {
	header("location: ../lista_dipendenti.php");
}
?>