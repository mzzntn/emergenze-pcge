<!DOCTYPE html>
<html>
<head>


<meta http-equiv="content-type" content="text/html; charset=UTF8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title> Error </title>

</head>
<body>
<?php

session_start();

include '/home/local/COMGE/egter01/emergenze-pcge_credenziali/conn.php';

// controllo CF
$query_cf= "SELECT cf FROM users.personale_volontario;";
$result_cf = pg_query($conn, $query_cf);
while($r_cf = pg_fetch_assoc($result_cf)) {
    if("'".$r_cf['cf']."'"== "'".$_POST['CF']."'") {
        echo "Codice Fiscale già esistente. <br><br>";
        echo "<a href=\"add_volontario.php\"> Torna indietro </a>";
        exit;
    }
}

//echo $query_via;

#echo $_POST['nome']; 
echo "<br>";


$query = "INSERT INTO users. personale_volontario(cf,
        cognome,
        nome,
        nazione_nascita,
        data_nascita,
        comune_residenza,
        telefono1,
        mail";

if ($_POST['indirizzo']!=null){
    $query=$query.",indirizzo";
}

if ($_POST['CAP']!=null){
    $query=$query.",cap";
}

if ($_POST['telefono2']!=null){
    $query=$query.",telefono2";
}

if ($_POST['fax']!=null){
    $query=$query.",fax";
}
if ($_POST['numero_gg']!=null){
    $query=$query.",numero_gg";
}

$query=$query.") VALUES ('".$_POST['CF']."' ,'".$_POST['cognome']."' ,'".$_POST['nome']."' ,'".$_POST['naz']."' ,'".$_POST['yyyy']."-".$_POST['mm']."-".$_POST['dd']."' ,'".$_POST['comune']."', '".$_POST['telefono1']."','".$_POST['mail']."'";

if ($_POST['indirizzo']!=null){
    $query=$query.",'".$_POST['indirizzo']."'";
}

if ($_POST['CAP']!=null){
    $query=$query.",'".$_POST['cap']."'";
}

if ($_POST['telefono2']!=null){
    $query=$query.",'".$_POST['telefono2']."'";
}

if ($_POST['fax']!=null){
    $query=$query.",'".$_POST['fax']."'";
}
if ($_POST['numero_gg']!=null){
    $query=$query.",'".$_POST['numero_gg']."'";
}
$query=$query.");";


echo $query;
#exit;

$result = pg_query($conn, $query);


$query_log= "INSERT INTO varie.t_log (schema,operatore, operazione) VALUES ('users','".$_SESSION["Utente"] ."', 'Insert volontario ".$_POST['cognome']." ".$_POST['nome']." - CF: ".$_POST['CF']."');";
$result = pg_query($conn, $query_log);

//$idfascicolo=str_replace('A','',$idfascicolo);
//$idfascicolo=str_replace('B','',$idfascicolo);
echo "<br>";
echo $query_log;
exit;
header("location: lista_volontari.php");
?>

</body>
</html>