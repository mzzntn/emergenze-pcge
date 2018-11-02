<?php 

$subtitle="Elenco segnalazioni pervenute (eventi attivi e/o in fase di chiusura)";


$getfiltri=$_GET["f"];
$filtro_evento_attivo=$_GET["a"];

//echo $filtro_evento_attivo; 


$uri=basename($_SERVER['REQUEST_URI']);
//echo $uri;

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

require('./tables/filtri_segnalazioni.php');
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
                    <h1 class="page-header">Elenco segnalazioni</h1>
                </div>
            </div-->

            
            <br><br>
            <div class="row">

<p>
            <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            <i class="fas fa-filter"></i>  Filtra per criticità
          </a>
           <?php if ($filtro_evento_attivo < 1){?>
      	
          <a class="btn btn-primary" href="<?php echo $uri;?>?a=1">
            <i class="fas fa-play"></i> Vedi solo eventi attivi
          </a>
          <?php } ?>
        </p>
        <div class="collapse" id="collapseExample">
          <div class="card card-body">
          
          <form action="./tables/decodifica_filtro0.php" method="post">
            <?php



            $query='SELECT * FROM segnalazioni.tipo_criticita where valido=\'t\';';
            $result = pg_query($conn, $query);
	         #echo $result;
	         //exit;
	         //$rows = array();
            //echo '<div class="form-check form-check-inline">';
            echo '<div class="row">';
	         while($r = pg_fetch_assoc($result)) {
					echo '<div class="form-check col-md-3">';
	            echo '  <input class="form-check-input" type="checkbox" name="filter'.$r['id'].'" value=1" >';
	            echo '  <label class="form-check-label" for="inlineCheckbox1">'.$r['descrizione'].'</label>';
	            echo "</div>";
	            
            }
            echo "</div>";

        ?>
        <hr>
			<button type="submit" class="btn btn-primary"> Nuovo filtro </button>
			

        </form>
          </div>
        </div>
        <hr>
			<?php
			if (strpos($getfiltri, '1') !== false) {
			    echo '<i class="fas fa-filter"></i> I dati visualizzati sono filtrati per criticità, per modificare il filtro usa i dati qua sopra';
			?>
			<br><br>
			<a class="btn btn-primary" href="elenco_segnalazioni.php">
            <i class="fas fa-redo-alt"></i> Torna a visualizzare tutte le segnalazioni
          </a>
          
			<?php			
			} else {
				echo ' <i class="fas fa-list-ul"></i> Dati completi (tutte le criticità)';
			}
			
			?>

		<hr>	
        <div id="toolbar">
            <select class="form-control">
                <option value="">Esporta i dati visualizzati</option>
                <option value="all">Esporta tutto (lento)</option>
                <option value="selected">Esporta solo selezionati</option>
            </select>
        </div>
        
      	<?php if ($filtro_evento_attivo == 1){
      	?>
        <table  id="segnalazioni" class="table-hover" style="word-break:break-all; word-wrap:break-word;" data-toggle="table" data-url="./tables/griglia_segnalazioni_eventi_attivi.php?f=<?php echo $getfiltri;?>" data-height="900" data-show-export="true" data-search="true" data-click-to-select="true" data-pagination="true" data-sidePagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-toolbar="#toolbar">
      	<?php } else { ?>
        <table  id="segnalazioni" class="table-hover" style="word-break:break-all; word-wrap:break-word;" data-toggle="table" data-url="./tables/griglia_segnalazioni.php?f=<?php echo $getfiltri;?>" data-height="900" data-show-export="true" data-search="true" data-click-to-select="true" data-pagination="true" data-sidePagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-toolbar="#toolbar">
			<?php } ?>

        
        
<thead>

 	<tr>
            <th data-field="state" data-checkbox="true"></th>
            <th data-field="in_lavorazione" data-sortable="false" data-formatter="nameFormatter" data-visible="true" ></th> 
            <th data-field="rischio" data-sortable="true" data-formatter="nameFormatterRischio" data-visible="true">Persone<br>a rischio</th>
            <th style="word-break:break-all; word-wrap:break-word;" data-field="criticita" data-sortable="true"   data-visible="true">Tipo criticità</th>
            <th data-field="id_evento" data-sortable="true"  data-visible="true">Id<br>evento</th>
            <th style="word-break:break-all; word-wrap:break-word;" data-field="tipo_evento" data-sortable="true"  data-visible="true">Tipo evento</th>
            <th style="word-break:break-all; word-wrap:break-word;" data-field="data_ora" data-sortable="true"  data-visible="true">Data e ora</th>
            <th style="word-break:break-all; word-wrap:break-word;" data-field="descrizione" data-sortable="true"  data-visible="true">Descrizione</th>
            <th data-field="note" data-sortable="false" data-visible="true" >Note</th>
            <th data-field="id" data-sortable="false" data-formatter="nameFormatterEdit" data-visible="true" >Dettagli</th>            

    </tr>
</thead>

</table>


<script>
    // DA MODIFICARE NELLA PRIMA RIGA L'ID DELLA TABELLA VISUALIZZATA (in questo caso t_volontari)
    var $table = $('#segnalazioni');
    $(function () {
        $('#toolbar').find('select').change(function () {
            $table.bootstrapTable('destroy').bootstrapTable({
                exportDataType: $(this).val()
            });
        });
    })
</script>

<br><br>

<script>


 function nameFormatter(value) {
        if (value=='t'){
        		return '<i class="fas fa-play" style="color:#5cb85c"></i>';
        } else if (value=='f') {
        	   return '<i class="fas fa-stop"></i>';
        } else {
        	   return '<i class="fas fa-pause" style="color:#ff0000"></i>';;
        }

    }
    
 function nameFormatterEdit(value) {
        
		return '<a class="btn btn-warning" href=./dettagli_segnalazione.php?id='+value+'> <i class="fas fa-edit"></i> </a>';
 
    }

  function nameFormatterRischio(value) {
        //return '<i class="fas fa-'+ value +'"></i>' ;
        
        if (value=='t'){
        		return '<i class="fas fa-exclamation-triangle" style="color:#ff0000"></i>';
        } else if (value=='f') {
        	   return '<i class="fas fa-check" style="color:#5cb85c"></i>';
        }
        else {
        		return '<i class="fas fa-question" style="color:#505050"></i>';
        }
    }


</script>





            </div>
            <!-- /.row -->
    </div>
    <!-- /#wrapper -->



<?php 

require('./footer.php');

require('./req_bottom.php');


?>


    
</body>



</html>