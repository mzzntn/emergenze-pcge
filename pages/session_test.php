<?php

echo "<h3> PHP List All HTTP headers variables</h3>";

# chiamata alla funzione per la raccolta dei request headers 
$headers = getallheaders();
# visualizzazione dei valori dell'array tramite ciclo
foreach ($headers as $name => $content)
{
  echo "[$name] = $content<br>";
	if ($name=='comge_codicefiscale'){
		$CF=$content;
	}

} 


echo "<h3>CF:".$CF."</h3>";

echo "<h3> _SERVER['HTTP_COMGE_NOME']</h3>";

echo $_SERVER['http_omge_codicefiscale'];

echo "<br>";

echo $_POST['SAMLResponse'];

    //session_start(bkj7hp5ldhjakn3clk4418otg5);
     echo "<h3> PHP List All SESSION Variables</h3>";
    foreach ($_SESSION as $key=>$val)
    echo $key." ".$val."<br/>";


     echo "<h3> PHP List All POST Variables</h3>";
    foreach ($_POSTPOST as $key=>$val)
    echo $key." ".$val."<br/>";

 	//echo session_id();

 echo "<h3> PHP List All GET Variables</h3>";
    foreach ($_GET as $key=>$val)
    echo $key." ".$val."<br/>";

    
    echo "<h3> PHP List All SERVER Variables</h3>";
    foreach ($_SERVER as $key=>$val)
    echo $key." ".$val."<br/>";
?>