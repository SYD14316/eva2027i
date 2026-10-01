<?php 
	include_once "funciones.php";
	include_once "connection.php";
	session_start();
  //Muestro los errores
	error_reporting(E_ALL);
  ini_set("display_errors", 1);
  //Se obtienen los datos de los campos
	$codigo=$_POST['codex'];
	$sql=$conn->prepare($codigo);
	//Ejecutar la sentencia
	$res=$sql->execute();
	//Asocio los datos de la tabla obtenidos
	$resultado=$sql->fetchAll();
  
	if($res === TRUE){
    if ($resultado != NULL) { ?>
			<div class="card">
				<div class="card-body">
					<table class="table table-bodered table-striped table-sm table-hover" id="tabla_exe">
						<thead>
							<?php
								$n_columnas=count($resultado[0]);
								$resultado2=array_keys($resultado[0]);
								for ($i=0; $i <$n_columnas ; $i++) {
									echo "<th>".$resultado2[$i]."</th>";
									$i=$i+1;
								}
							?>
						</thead>
						<tbody>
							<?php
								$n_registros=count($resultado);
								for ($i=0; $i < $n_registros ; $i++) { 
									echo "<tr>";
									for ($j=0; $j < $n_columnas ; $j++) { 
										echo "<td>".$resultado[$i][$resultado2[$j]]."</td>";
										$j=$j+1;
									}
									echo "</tr>";
								}
		          ?>
		      	</tbody>
					</table>
				</div>
			</div><?php
 		}
	}
?>
<script type="text/javascript">
  $(document).ready( function () {
    var table = $('#tabla_exe').DataTable( {
      responsive: true,
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        "info": true,
        "pagingType":"full_numbers",
        dom: 'Bfrtip',
        buttons:{
          buttons:[
            { 
              extend: 'excelHtml5',
              text:'DESCARGAR EXCEL',
              orientation: 'landscape'
            },
            { extend: 'print', text:'IMPRIMIR' },{ extend: 'copy', text:'COPIAR' },
          ],
        },
      } );
      table.on( 'responsive-resize', function ( e, datatable, columns ) {
        var count = columns.reduce( function (a,b) {
          return b === false ? a+1 : a;
        }, 0 );
        console.log( count +' column(s) are hidden' );
      } );
    } );
  //
</script>