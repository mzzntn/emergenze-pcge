<?php 
// Start the session
session_start();
$_SESSION['user']="MRZRRT84B01D969U";

$id=$_GET["id"];
$subtitle="Dettagli segnalazione ricevuta n. ".$id;


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="roberto" >

    <title>Gestione emergenze</title>
<?php 
require('./req.php');

require('/home/local/COMGE/egter01/emergenze-pcge_credenziali/conn.php');

require('./check_evento.php');
?>
    
</head>

<body>

    <div id="wrapper">

        <?php 
            require('./navbar_up.php')
        ?>  
        <?php 
            require('./navbar_left.php')
        ?> 
            

        <div id="page-wrapper">
            <!--div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Titolo pagina</h1>
                </div>
            </div-->
            <!-- /.row -->
            
            <br><br>
            <div class="row">
            <div class="col-md-6">
				<?php
					$query= "SELECT *, st_x(st_transform(geom,4326)) as lon , st_y(st_transform(geom,4326)) as lat FROM segnalazioni.v_segnalazioni WHERE id=".$id.";";
					//echo $query
           
					$result=pg_query($conn, $query);
					while($r = pg_fetch_assoc($result)) {
					?>            
            
               <h4><br><b>Tipo criticità</b>: <?php echo $r['criticita']; ?></h4>
               <hr>
            	
						
						<?php 
            $id_lavorazione=$r['id_lavorazione'];
						$check_lav=0;
						if ($r['id_lavorazione'] !='' and $r['in_lavorazione']=='t') {
									$check_lav=1;
									echo '<h4> <i class="fas fa-play"></i> La segnalazione è in lavorazione </h4><hr>';
									
								} else if ($r['id_lavorazione'] !=''  and $r['in_lavorazione']=='f') {
									$check_lav=-1;
									echo '<h4> <i class="fas fa-stop"></i> La segnalazione è chiusa </h4><hr>';
						}
						
						
						if ($check_lav==1 OR $check_lav==-1 ){
								?>
								
								
								<div class="panel-group">
									  <div class="panel panel-warning">
									    <div class="panel-heading">
									      <h4 class="panel-title">
									        <a data-toggle="collapse" href="#storico"><i class="fa fa-clock"></i> Storico operazioni </a>
									      </h4>
									    </div>
									    <div id="storico" class="panel-collapse collapse">
									      <div class="panel-body"-->
										<?php
										// cerco l'id_lavorazione
										$query_storico="SELECT to_char(data_ora,'DD/MM/YY HH24:MI:SS')as data_ora,log_aggiornamento";
										$query_storico= $query_storico." FROM segnalazioni.t_storico_segnalazioni_in_lavorazione WHERE id_segnalazione_in_lavorazione=".$r['id_lavorazione'].";";
										//echo $query_storico;
										$result_storico=pg_query($conn, $query_storico);
										while($r_storico = pg_fetch_assoc($result_storico)) {
											echo "<hr>".$r_storico['data_ora'];
											echo " - " .$r_storico['log_aggiornamento'];
										}
										
							
							
										?>
									
									
									</div>
						    </div>
						  </div>
						</div>
						
						<?php 
						}
						if ($check_lav==1){ ?>
						<div style="text-align: center;">
						
				      <button type="button" class="btn btn-info"  data-toggle="modal" data-target="#new_incarico"><i class="fas fa-plus"></i> Assegna incarico (DEMO)</button>
						 - 
						<a class="btn btn-info" disabled=""> Crea sopralluogo (DEMO)</a>
						<hr>
						<?php
						
	   					echo '<button type="button" class="btn btn-danger"  data-toggle="modal" ';
	   					// andra messo un check sugli incarichi / sopralluoghi attivi
	   					/*if($check_allerte==1 OR $check_foc==1){
	   						echo 'disabled=""';
	   					}*/
	   					echo 'data-target="#chiudi"><i class="fas fa-times"></i> Chiudi segnalazione </button>';
	   					
						?>
						</div>
						
						
						
						<!-- Modal allerta-->
<div id="new_incarico" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nuovo incarico</h4>
      </div>
      <div class="modal-body">
      

        <form autocomplete="off" action="incarichi/nuovo_incarico.php?id=<?php echo $id_lavorazione; ?>&s=<?php echo $id; ?>" method="POST">
		
		<div class="form-group">
         <label for="tipo">Tipologia di incarico:</label> <font color="red">*</font>
			<select class="form-control" name="tipo" id="tipo" onChange="getUO(this.value);"  required="">
			   <option name="tipo" value="" >  </option>
         	<option name="tipo" value="comune" > Incarico a uffici periferici del comune </option>
         	<option name="tipo" value="esterni" > Incarico a Unità Operative esterne. </option>
        </select>
        </div>
             
                         <script>
            function getUO(val) {
	            $.ajax({
	            type: "POST",
	            url: "get_uo.php",
	            data:'cod='+val,
	            success: function(data){
		            $("#uo-list").html(data);
	            }
	            });
            }

            </script>

             
             
            <div class="form-group">
              <label for="id_civico">Seleziona l'Unità Operativa cui assegnare l'incarico:</label> <font color="red">*</font>
                <select class="form-control" name="uo" id="uo-list" class="demoInputBox" required="">
                <option value=""> ...</option>
            </select>         
             </div>       
             
            <div class="form-group">
					 <label for="descrizione"> Descrizione</label> <font color="red">*</font>
                <input type="text" name="descrizione" class="form-control" required="">
		      </div>            
                  



        <button  id="conferma" type="submit" class="btn btn-primary">Invia incarico</button>
            </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
      </div>
    </div>

  </div>
</div>
						
						
						
						
						
						
						
						
						<!-- Modal chiusura-->
						<div id="chiudi<?php echo $eventi_attivi[$i]; ?>" class="modal fade" role="dialog">
						  <div class="modal-dialog">
						
						    <!-- Modal content-->
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal">&times;</button>
						        <h4 class="modal-title">Chiudi segnalazione</h4>
						      </div>
						      <div class="modal-body">
						      
						
						        <form autocomplete="off" action="./segnalazioni/chiudi_segnalazione.php?id_lav=<?php echo $r['id_lavorazione'];?>&id=<?php echo $r['id'];?>" method="POST">
								Proseguendo chiuderai la lavorazione di questa segnalazione e di tutte quelle unite a questa.
								<br>Non sarà più possibile assegnare incarichi o sopralluoghi associati a questa segnalazione.
								<hr>
								<div class="form-group">
								  <label for="note">Note chiusura:</label>
								  <textarea class="form-control" rows="5" id="note" nama="note" required=""></textarea>
								</div>
								
								<div class="form-group">
								<label for="nome"> Segnalazione completamente risolta?</label> <font color="red">*</font><br>
								<label class="radio-inline"><input type="radio" name="risolta" value="t" required="">Sì</label>
								<label class="radio-inline"><input type="radio" name="risolta"value="f">No</label>
							</div>


							<div class="form-group">
								<label for="nome"> Pensi sia necessario inviarla a Manutenzioni/ LLPP?</label> <font color="red">*</font><br>
								<label class="radio-inline"><input type="radio" name="invio" value="man">Manutenzioni</label>
								<label class="radio-inline"><input type="radio" name="invio" value="llpp">LLPP</label>
							</div>

								<!--div class="form-group">
								<label for="cat" class="auto-length">
									<input type="checkbox" name="cat" id="cat">
									Cliccare qua per confermare la chiusura dell'evento 
								</label>
								</div-->

								<br><br>
						
						
						
						        <button id="conferma_chiudi" type="submit" class="btn btn-danger">Conferma chiusura segnalazione</button>
						            </form>
						
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
						      </div>
						    </div>
						
						  </div>
						</div> 
						
						
						
						
						<hr>
						<?php 
						} 
						
						
						
						
						// controllo se ci sono altre segnalazioni sullo stesso civico
						$check_civico=0;
						$query_civico="SELECT * FROM segnalazioni.v_segnalazioni where id_civico=".$r['id_civico']." and id !=".$id." and id_evento=".$r['id_evento'].";";
						$result_civico=pg_query($conn, $query_civico);
								while($r_civico = pg_fetch_assoc($result_civico)) {
									$check_civico=1;
									?>
									Altre segnalazioni sullo stesso civico:
									<div class="panel-group">
									  <div class="panel panel-info">
									    <div class="panel-heading">
									      <h4 class="panel-title">
									        <a data-toggle="collapse" href="#c_civico_s<?php echo $r_civico['id'];?>"> <?php echo $r_civico['criticita'];?></a>
									      									      <?php
									      if($r_civico['rischio'] =='t') {
												echo ' <i class="fas fa-circle fa-1x" style="color:#ff0000"></i>';
											} else if ($r_civico['rischio'] =='f') {
												echo ' <i class="fas fa-circle fa-1x" style="color:#008000"></i>';
											} else {
												echo ' <i class="fas fa-circle fa-1x" style="color:#ffd800"></i> ';
											}
											?>
									      
									      
									      </h4>
									    </div>
									    <div id="c_civico_s<?php echo $r_civico['id'];?>" class="panel-collapse collapse">
									      <div class="panel-body"-->
									<?php
										echo "<br>";
										if($r_civico['rischio'] =='t') {
											echo ' <i class="fas fa-circle fa-1x" style="color:#ff0000"></i> Persona a rischio';
										} else if ($r_civico['rischio'] =='f') {
											echo ' <i class="fas fa-circle fa-1x" style="color:#008000"></i> Non ci sono persone a rischio';
										} else {
											echo ' <i class="fas fa-circle fa-1x" style="color:#ffd800"></i> Non è specificato se ci siano persone a rischio';
										}
									?>
						
						
						
									<br><b>Data e ora inserimento</b>: <?php echo $r_civico['data_ora']; ?>
									<br><b>Descrizione</b>: <?php echo $r_civico['descrizione']; ?>
									
									<hr>
									<a class="btn btn-info" href="./dettagli_segnalazione.php?id=<?php echo $r_civico['id'];?>"> <i class="fas fa-angle-right"></i> Vai alla segnalazione </a>
									<br> <br>
									<?php
									if ($r_civico['id_lavorazione']!='' and $r['id_lavorazione']==''){
										echo '<a class="btn btn-info" href="./segnalazioni/unisci_segnalazione.php?id_from='.$id.'&id_to='.$r_civico['id'].'"><i class="fas fa-link"></i> Unisci segnalazione. </a>';
									} 
									?>
									</div>
						    </div>
						  </div>
						</div>
								<?php	
								}
						 if($check_civico==0 and $r['id_civico']!=''){
						 	echo "Non ci sono altre segnalazioni in corrispondenza dello stesso civico.<br><br>";
						 }
						 ?>
						 
						 
						 <?php 
						// controllo se ci sono altre segnalazioni nelle vicinanze
						$check_vic=0;
						$geom_s=$r['geom'];
						$id_evento_s=$r['id_evento'];
						if ($r['id_civico']!=''){
							$query_vic="SELECT * FROM segnalazioni.v_segnalazioni where st_distance(st_transform('".$r['geom']."'::geometry(point,4326),3003),st_transform(geom,3003))< 200 and id_evento=".$r['id_evento']." and (id_civico!=".$r['id_civico']." or id_civico is null) and id !=".$id.";";
						} else {
							$query_vic="SELECT * FROM segnalazioni.v_segnalazioni where st_distance(st_transform('".$r['geom']."'::geometry(point,4326),3003),st_transform(geom,3003))< 200 and id_evento=".$r['id_evento']." and id !=".$id.";";
						}
						//echo $query_vic."<br>";
						$result_vic=pg_query($conn, $query_vic);
								while($r_vic = pg_fetch_assoc($result_vic)) {
									$check_vic=1;
									?>
									Altre segnalazioni nelle vicinanze:
									<div class="panel-group">
									  <div class="panel panel-info">
									    <div class="panel-heading">
									      <h4 class="panel-title">
									        <a data-toggle="collapse" href="#c_civico_s<?php echo $r_vic['id'];?>"> <?php echo $r['criticita'].' (n. segn. '.$r_vic['id'].')';?></a>
									      <?php
									      if($r_vic['rischio'] =='t') {
												echo ' <i class="fas fa-circle fa-1x" style="color:#ff0000"></i>';
											} else if ($r_vic['rischio'] =='f') {
												echo ' <i class="fas fa-circle fa-1x" style="color:#008000"></i>';
											} else {
												echo ' <i class="fas fa-circle fa-1x" style="color:#ffd800"></i> ';
											}
											?>
									      </h4>
									    </div>
									    <div id="c_civico_s<?php echo $r_vic['id'];?>" class="panel-collapse collapse">
									      <div class="panel-body"-->
									<?php
										echo "<br>";
										if($r_vic['rischio'] =='t') {
											echo ' <i class="fas fa-circle fa-1x" style="color:#ff0000"></i> Persona a rischio';
										} else if ($r_vic['rischio'] =='f') {
											echo ' <i class="fas fa-circle fa-1x" style="color:#008000"></i> Non ci sono persone a rischio';
										} else {
											echo ' <i class="fas fa-circle fa-1x" style="color:#ffd800"></i> Non è specificato se ci siano persone a rischio';
										}
									?>
						
						
						
									<br><b>Data e ora inserimento</b>: <?php echo $r_vic['data_ora']; ?>
									<br><b>Descrizione</b>: <?php echo $r_vic['descrizione']; ?>
									
									<hr>
									<a class="btn btn-info" href="./dettagli_segnalazione.php?id=<?php echo $r_vic['id'];?>"> <i class="fas fa-angle-right"></i> Vai alla segnalazione </a>
									<br> <br>
									<?php
									if ($r_vic['id_lavorazione']!='' and $r['id_lavorazione']==''){
										echo '<a class="btn btn-info" href="./segnalazioni/unisci_segnalazione.php?id_from='.$id.'&id_to='.$r_vic['id'].'"><i class="fas fa-link"></i> Unisci segnalazione. </a>';
									} 
									?>
									
									
										</div>
							    </div>
							  </div>
							</div>
								<?php	
								}
								
						 if($check_vic==0){
						 	echo "Non ci sono altre segnalazioni nelle vicinanze.<br><br>";
						 }
						 ?>
						
						
						<div style="text-align: center;">
						<?php
						
						 if ($r['id_lavorazione']==''){ ?>
								<button type="button" class="btn btn-info"  data-toggle="modal" data-target="#lavorazione"> <i class="fas fa-plus"></i> 
								<?php
								 // solo se non ancora in lavorazione
									if ($check_civico==0 and $check_vic==0){
										echo "Elabora segnalazione";
									} else {
										echo "Elabora come nuova segnalazione";
									}
								
								?>
								</button>	
						<?php }?>

						</div>




<!-- Modal lavorazione-->
<div id="lavorazione" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Inizia ad elaborare la segnalazione</h4>
      </div>
      <div class="modal-body">
      

        <form autocomplete="off" action="./segnalazioni/import_lavorazione.php" method="POST">
        
         <input type="hidden" name="id" id="hiddenField" value="<?php echo $id ?>" />
         
			<div class="form-group">
					<label for="nome"> Chi si occuperà della gestione della segnalazione ?</label> <font color="red">*</font><br>
					<label class="radio-inline"><input type="radio" name="uo" required="" value="3">Invia alla centrale PC </label>
					<label class="radio-inline"><input type="radio" name="uo" required="" value="4">Invia alla centrale COA </label>
					<label class="radio-inline"><input type="radio" name="uo" required="" value="5">Elabora come Municipio  </label>
					<label class="radio-inline"><input type="radio" name="uo" required="" value="6">Elabora come Distretto PM </label>
				</div>
		
			<hr>
			! in assenza del login questa funzione è ancora in versione DEMO.. 
			<br>a seconda della scelta la segnalazione verrà presa in carico dal municipio o dalla centrale di PC 
			<hr>
			


        <button  id="conferma" type="submit" class="btn btn-primary" >Inserisci in lavorazione</button>
            </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
      </div>
    </div>

  </div>
</div>   



						<hr>
						<!--h4> Persona a rischio? </h4-->
						<h3><i class="fas fa-list-ul"></i> Dettagli segnalazione n. <?php echo $r['id'];?></h3>
						<?php 
						if($r['rischio'] =='t') {
							echo '<i class="fas fa-circle fa-1x" style="color:#ff0000"></i> Persona a rischio';
						} else if ($r['rischio'] =='f') {
							echo '<i class="fas fa-circle fa-1x" style="color:#008000"></i> Non ci sono persone a rischio';
						} else {
							echo '<i class="fas fa-circle fa-1x" style="color:#ffd800"></i> Non è specificato se ci siano persone a rischio';
						}
						?>
						<!--h4><i class="fas fa-list-ul"></i> Generalità </h4-->
						<br><b>Identificativo evento</b>: <?php echo $r['id_evento']; ?>
						<br><b>Descrizione</b>: <?php echo $r['descrizione']; ?>
						<br><b>Data e ora inserimento</b>: <?php echo $r['data_ora']; ?>
						<br><b>Operatore inserimento</b>: <?php echo $r['id_operatore']; ?>
						
						
						
						<!--div class="panel-group">
						  <div class="panel panel-default">
						    <div class="panel-heading">
						      <h4 class="panel-title">
						        <a data-toggle="collapse" href="#c_segnalante">Generalità segnalante</a>
						      </h4>
						    </div>
						    <div id="c_segnalante" class="panel-collapse collapse">
						      <div class="panel-body"-->
						      <hr>
						     <h4><i class="fas fa-user"></i> Segnalante </h4> 
						     <?php 
						     $query_segnalante="SELECT * FROM segnalazioni.v_segnalanti where id_segnalazione=".$id.";";
						     $result_segnalante=pg_query($conn, $query_segnalante);
								while($r_segnalante = pg_fetch_assoc($result_segnalante)) {
									echo "<br><b>Tipo</b>:".$r_segnalante['descrizione'];
									if ($r_segnalante['altro_tipo']!='') {
										echo "(".$r_segnalante['altro_tipo'].")";
									}
									echo "<br><b>Nome</b>:".$r_segnalante['nome_cognome'];
									echo "<br><b>Telefono</b>:".$r_segnalante['telefono'];
									echo "<br><b>Note segnalante</b>:".$r_segnalante['note'];
								}
						     ?>
						      <!--/div>
						      <div class="panel-footer">Panel Footer</div>
						    </div>
						  </div>
						</div-->
						
						
						<br>
						
						<br>
						</div> 
						<div class="col-md-6">
						<h4> <i class="fas fa-map-marker-alt"></i> Indirizzo </h4>
						<?php if($r['id_civico'] !='') {
								$queryc= "SELECT * FROM geodb.civici WHERE id=".$r['id_civico'].";";
								$resultc=pg_query($conn, $queryc);
								while($rc = pg_fetch_assoc($resultc)) {
									echo "<b>Indirizzo civico</b>:" .$rc['desvia'].", ".$rc['testo'].", ".$rc['cap'];
									echo "<br><b>Municipio</b>:" .$rc['desmunicipio'];
								}
						} else {
								$queryc= "SELECT desvia, testo, cap, st_distance(st_transform(geom,4326),'".$r['geom']."') as distance  FROM geodb.civici ORDER BY distance LIMIT 1;";
								//echo $queryc;
								$resultc=pg_query($conn, $queryc);
								while($rc = pg_fetch_assoc($resultc)) {
									echo "<b>Indirizzo civico (più prossimo)</b>:" .$rc['desvia'].", ".$rc['testo'].", ".$rc['cap'];
									//echo "<br><b>Municipio</b>:" .$rc['desmunicipio'];
								}
								$queryc= "SELECT * FROM geodb.municipi WHERE codice_mun='".$r['id_municipio']."';";
								//echo $queryc;
								$resultc=pg_query($conn, $queryc);
								while($rc = pg_fetch_assoc($resultc)) {
									echo "<br><b>Municipio</b>:" .$rc['nome_munic'];
									//echo "<br><b>Municipio</b>:" .$rc['desmunicipio'];
								}
								
						
						}
						$lon=$r['lon'];
						$lat=$r['lat'];
						?>
						<hr>
						<h4> <i class="fas fa-map-marked-alt"></i> Mappa </h4>
						<!--div id="map_dettaglio" style="width: 100%; padding-top: 100%;"></div-->
						
						<!--div style="width: 100%; padding-top: 100%;"-->
							<iframe class="embed-responsive-item" style="width:100%; padding-top:0%; height:600px;" src="./mappa_leaflet.php#16/<?php echo $lat;?>/<?php echo $lon;?>"></iframe>
						<!--/div-->
						<hr>
						<?php
						   $id_segnalazione=$id;
							include './segnalazioni/section_oggetto_rischio.php'; 
							// cerco l'oggetto a rischio
							/*$check_or=0;
							$query_or="SELECT * FROM segnalazioni.join_oggetto_rischio WHERE id_segnalazione=".$id." AND attivo='t';";
							$result_or=pg_query($conn, $query_or);
							while($r_or = pg_fetch_assoc($result_or)) {
								$check_or=1;
								$id_tipo_oggetto_rischio=$r_or['id_tipo_oggetto'];
								$id_oggetto_rischio=$r_or['id_oggetto'];
							}
							//echo $query_or;
							// cerco i dettagli dell'oggetto a rischio
							$query_or2="SELECT * from segnalazioni.tipo_oggetti_rischio where id= ".$id_tipo_oggetto_rischio.";";
							//echo $query_or2;
							$result_or2=pg_query($conn, $query_or2);
							while($r_or2 = pg_fetch_assoc($result_or2)) {
								$nome_tabella_oggetto_rischio=$r_or2['nome_tabella'];
								$descrizione_oggetto_rischio=$r_or2['descrizione'];
								$nome_campo_id_oggetto_rischio=$r_or2['campo_identificativo'];
							}
							if($check_or==1) {
								echo "<h4> Oggetto a rischio </h4>";
								echo "<b>Tipo oggetto a rischio</b>:".$descrizione_oggetto_rischio;
								echo "<br><b>Id oggetto a rischio </b>:".$id_oggetto_rischio;
							} else if ($check_or==0) {
								echo "<h4> Nessun oggetto a rischio segnalato.</h4>";
							}*/
							// eventualmente da tirare fuori altri dettagli
							//$query_or3="SELECT * from ".$nome_tabella_oggetto_rischio."  where ".$nome_campo_id_oggetto_rischio." = ".$id_oggetto_rischio.";";
							
 						?>	
						</div>
			
					<?php
					}
					?>


            </div>
            <!-- /.row -->
    </div>
    <!-- /#wrapper -->

<?php 

require('./footer.php');

require('./req_bottom.php');


?>


<script type="text/javascript">
						
		var lat=<?php echo $lat;?>;
		var lon=<?php echo $lon;?>;
		var mymap = L.map('map_dettaglio', {scrollWheelZoom:false}).setView([lat, lon], 16);
	
		L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
			maxZoom: 18,
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
				'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
				'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
			id: 'mapbox.streets'
		}).addTo(mymap);
	
		L.marker([lat, lon]).addTo(mymap)
    		.bindPopup('Segnalazione n. <?php echo $id;?>');
    		//.openPopup();
	
	
		
		var segn_non_lav = [
        
        <?php 
        $query_g="SELECT id, ST_AsGeoJson(geom) as geo, rischio, criticita, descrizione, note FROM segnalazioni.v_segnalazioni WHERE lavorazione=0 and st_distance(st_transform('<?php echo $geom_s;?>'::geometry(point,4326),3003),st_transform(geom,3003))< 200 and id_evento=<?php echo $id_evento;?;";


			// GeoJson Postgis: {"type":"Point","coordinates":[8.90092674245687,44.4828501691802]}
			

    		$i=0;
			$result_g = pg_query($conn, $query_g);
	      while($r_g = pg_fetch_assoc($result_g)) {
				if ($i==0){ 
					echo '{"type": "Feature","properties": {"id":'.$r_g["id"].', "rischio": "';
					echo $r_g["rischio"].'", "criticita": "'.$r_g["criticita"].'", "descrizione": "'.str_replace('"',' ',$r_g["descrizione"]).'"},"geometry":';
					echo $r_g["geo"].'}';
				} else {
					//echo ",". $r_g["geo"];
					echo ',{"type": "Feature","properties": {"id":'.$r_g["id"].', "rischio": "';
					echo $r_g["rischio"].'", "criticita": "'.$r_g["criticita"].'", "descrizione": "'.str_replace('"',' ',$r_g["descrizione"]).'"},"geometry":';
					echo $r_g["geo"].'}';
					
				}
				$i=$i+1;
			}
			?>
			];
			
			
			
			
			
			
			var stile_non_lavorazione = {
		    radius: 8,
		    fillColor: "#FFD700",
		    color: "#000",
		    weight: 1,
		    opacity: 1,
		    fillOpacity: 0.8
		};
		
		var stile_lavorazione = {
		    radius: 8,
		    fillColor: "#228B22",
		    color: "#000",
		    weight: 1,
		    opacity: 1,
		    fillOpacity: 0.8
		};
		/*var layer_v_segnalazioni_0 = new L.geoJson(geojsonFeature, {
		    pointToLayer: function (feature, latlng) {
		        return L.circleMarker(latlng, geojsonMarkerOptions);
		    }
		}).addTo(map);*/
        
        
        //var markers0 = L.markerClusterGroup();
        var markers1 = L.markerClusterGroup();   
		  
		  var layer_v_segnalazioni_0 = L.geoJson(segn_non_lav, {
		    pointToLayer: function (feature, latlng) {
		        return L.circleMarker(latlng, stile_non_lavorazione);
		    }
		    ,
			onEachFeature: function (feature, layer) {
				layer.bindPopup('<div align="right" style="color:grey"><i class="fas fa-pause-circle"></i> Da prendere in carico </div>'+
				'<h4><b>Tipo</b>: '+
				feature.properties.criticita+'</h4>'+
				'<a class="btn btn-primary active" role="button" target="_new" href="./dettagli_segnalazione.php?id='+
				feature.properties.id +
				'"> Dettagli segnalazione </a>' );
			}
		});
		
	   mymap.addLayer(layer_v_segnalazioni_0);
</script>


<script type="text/javascript" >

$('input[type=radio][name=invio]').attr('disabled', true);

(function ($) {
    'use strict';
    
    
    $('[type="radio"][name="risolta"][value="f"]').on('change', function () {
        if ($(this).is(':checked')) {
            $('input[type=radio][name=invio]').removeAttr('disabled');
            return true;
        }
    });
    
	$('[type="checkbox"][id="cat"]').on('change', function () {
        if ($(this).is(':checked')) {
            $('#conferma_chiudi').removeAttr('disabled');
            return true;
        }
        
    });
}(jQuery));



</script>  

</body>

</html>