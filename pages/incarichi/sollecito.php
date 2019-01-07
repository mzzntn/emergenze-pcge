<?php

session_start();

//echo $_SESSION['user'];

include '/home/local/COMGE/egter01/emergenze-pcge_credenziali/conn.php';





//$id=$_GET["id"];
$id=str_replace("'", "", $_GET['id']); //segnazione in lavorazione

//$uo= str_replace("'", "", $_GET["uo"]);

$uo=$_GET["u"];


echo "Incarico:".$id. "<br>";
echo "Unita_operativa:".$uo. "<br>";



//echo "<h2>La gestione degli incarichi e' attualmente in fase di test and debug. Ci scusiamo per il disagio</h2> <br> ";



echo "<br>";
//echo $query_log;

$query="SELECT mail FROM users.t_mail_incarichi WHERE cod='".$uo."';";
$result=pg_query($conn, $query);
$mails=array();
while($r = pg_fetch_assoc($result)) {
  array_push($mails,$r['mail']);
}

echo "<br>";
//echo $query;
//echo "<br>";
echo count($mails). " registrate a sistema";

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;

require '../../vendor/PHPMailer/src/Exception.php';
require '../../vendor/PHPMailer/src/PHPMailer.php';
require '../../vendor/PHPMailer/src/SMTP.php';


//echo "<br>OK 1<br>";
//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');
//require '../../vendor/autoload.php';
//Create a new PHPMailer instance
$mail = new PHPMailer;

//echo "<br>OK 1<br>";
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
//Set the hostname of the mail server

// host and port on the file credenziali_mail.php
require './credenziali_mail.php';


//Set who the message is to be sent from
$mail->setFrom('salaemergenzepc@comune.genova.it', 'Sala Emergenze PC Genova');
//Set an alternative reply-to address
$mail->addReplyTo('no-reply@comune.genova.it', 'No Reply');
//Set who the message is to be sent to

//$mails=array('vobbo@libero.it','roberto.marzocchi@gter.it');
while (list ($key, $val) = each ($mails)) {
  $mail->AddAddress($val);
}
//Set the subject line
$mail->Subject = 'Urgente - Nuovo incarico dalla Protezione Civile del Comune di Genova';
//$mail->Subject = 'PHPMailer SMTP without auth test';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->Body =  'Hai ricevuto questo messaggio come sollecito perch� la tua Unit� Operativa
 '.$uo_descrizione.' Non ha ancora risposto all\'assegnazione incarico.
  <br> Ti preghiamo di non rispondere a questa mail, ma di visualizzare i dettagli dell\'incarico accedendo 
 con le tue credenziali alla
 <a href="http://192.168.153.110/emergenze/pages/dettagli_incarico.php?id='.$id.'">pagina </a>
  del nuovo Sistema di Gestione delle Emergenze del Comune di Genova.
 <br> <br> Protezione Civile del Comune di Genova. <br><br>--<br> 
 Ricevi questa mail  in quanto il tuo indirizzo mail � registrato a sistema. 
 Per modificare queste impostazioni � possibile inviare una mail a salaemergenzepc@comune.genova.it ';


//$mail->Body =  'Corpo del messaggio';
//$mail->msgHTML(file_get_contents('E\' arrivato un nuovo incarico da parte del Comune di Genova. Visualizza lo stato dell\'incarico al seguente link e aggiornalo quanto prima. <br> Ti chiediamo di non rispondere a questa mail'), __DIR__);
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');
//send the message, check for errors
//echo "<br>OK 2<br>";
if (!$mail->send()) {
    echo "<h3>Problema nell'invio della mail: " . $mail->ErrorInfo;
	?>
	<script> alert(<?php echo "Problema nell'invio della mail: " . $mail->ErrorInfo;?>) </script>
	<?php
	echo '<br>L\'incarico � stato correttamente assegnato, ma si � riscontrato un problema nell\'invio della mail.';
	echo '<br>Entro 10" verrai re-indirizzato alla pagina della tua segnalazione, clicca al seguente ';
	echo '<a href="../dettagli_incarico.php?id='.$id.'">link</a> per saltare l\'attesa.</h3>' ;
	//sleep(30);
   header("refresh:30;url=../dettagli_incarico.php?id=".$id);
} else {
    echo "Message sent!";
	header("location: ../dettagli_incarico.php?id=".$id);
}



//exit;
//header("location: ../dettagli_segnalazione.php?id=".$segn);


?>
