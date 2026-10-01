<?php
	session_start();
	require_once 'lib/config.php';
	if ((!isset($_SESSION['zez_a_nombre'])) OR (!isset($_POST['parametros']))) {
		header('Location: salir.php');
	} elseif (strpos(" REVISOR VICERRECTOR SUPERUSUARIO",$_SESSION['zez_a_nivel_acceso'])== FALSE) {
		if (date("Y-m-d") < $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) {
			header('Location: no_iniciada.php');
		} elseif (date("Y-m-d") > $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin']) {
			header('Location: cerrada.php');
		} else {
			header('Location: mis_evaluaciones.php');
		}
	}
	$parametros_validos = false;
	unset($parametros);
	$string_parametros = "";
	if (isset($_POST['parametros'])) {
		$string_parametros = desencriptar($_POST['parametros']);
		if ((substr($string_parametros,0,2)=="%%") AND (substr($string_parametros,(strlen($string_parametros)-2),2)=="%%")) {
			$string_parametros = substr($string_parametros,2,strlen($string_parametros)-4);
			$parametros_validos = true;
			if(strpos($string_parametros,"¬")===FALSE) {
				$parametros[] = $string_parametros;
			} else {
				do {
					$parametros[] = substr($string_parametros,0,strpos($string_parametros,"¬"));
					$string_parametros = substr($string_parametros,strpos($string_parametros,"¬")+2);
				} while (!(strpos($string_parametros,"¬")===FALSE));
				$parametros[] = $string_parametros;
			}
		}
	}
	if(!$parametros_validos) { header('Location: index.php'); }
	// Conectarse al servidor de la base de datos (BDD)
	$base_de_datos = mysqli_connect($bdd_servidor,$bdd_usuario,$bdd_clave,$bdd_nombre);
	// Verificar la conexión
	if (mysqli_connect_errno()) {
		printf("Falló la conexión: %s", mysqli_connect_error());
		exit();
	}
	// Cambiar el conjunto de caracteres a utf8
	if (!mysqli_set_charset($base_de_datos, "utf8")) {
		printf("Error cargando el conjunto de caracteres utf8: %s", mysqli_error($base_de_datos));
		exit();
	}
?>
<html>
	<?php include_once HEADER; ?>
	<script type="text/javascript" src="lib/functions.js"></script>

	<div class="d-flex fondo-azul-marista">
		<div><a class="btn text-light" onclick="redirigir('mis_reportes.php');"><strong>Mis Reportes</strong></a></div>

		<div><a class="btn btn-warning text-dark"><strong>Reporte Actual</strong></a></div>
		<?php
			if ((strpos(" VICERRECTOR FIDCO",$_SESSION['zez_a_nivel_acceso'])!= FALSE) AND (date("Y-m-d") >= $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) AND (date("Y-m-d") <= $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin'])) { ?>
				<div class=""><a class="btn btn-warning text-dark"onclick="redirigir('mis_evaluaciones.php');"><strong>Mis Evaluaciones</strong></a></div><?php
			}
		?>
		<div class="ml-auto">
			<a class="btn text-light" onclick="redirigir('salir.php');"><strong>Salir</strong></a>
		</div>
	</div>

	<body>
		<div class="container">
			<div class="card">
				<div class="card-header">
					<h1>
						<img src="lib/img/blanco.png" class="img-form-left" />
						PARTICIPACIÓN DE LOS COORDINADORES
					</h1>
					<span>CICLO <?php echo $ciclo[$parametros[0]]; ?></span>
				</div>
				<div class="card-body">
					<div class="contenedor-interno">
						<table class="table table-bordered table-striped table-sm" id="tabla">
							<thead>	
								<tr>
									<th class="fondo-marista">Nombre</th>
									<th class="fondo-marista">Sexo</th>
									<th class="fondo-marista">Evaluaciones a contestar</th>
									<th class="fondo-marista">Evaluaciones contestadas</th>
									<th class="fondo-marista">Último ingreso</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$orden_sql = "SELECT * FROM participantes WHERE nivel='LICENCIATURA' ORDER BY carrera, grado, nombre ASC";
									$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
									if ($resultado_busqueda) {
										if (mysqli_num_rows($resultado_busqueda) > 0) {
											while($registro = mysqli_fetch_array($resultado_busqueda)) {
												if (($registro['nivel_acceso']=="COORDINADOR") OR ($registro['nivel_acceso']=="FIDCO")) {
													echo "<tr>";
													echo "<td>" . $registro['nombre'] . "</td>";
													echo "<td>" . $registro['sexo'] . "</td>";
													if ($registro['total_evaluaciones']==0) {
														echo "<td class='rojo'>Dato no disponible</td>";
													} else {
														echo "<td";
														if ($registro['total_evaluaciones']>$registro['num_evaluados']) echo " class='amarillo'";
														echo ">" . $registro['total_evaluaciones'] . "</td>";
													}
													echo "<td";
													if ($registro['num_evaluados']==0) echo " class='rojo'";
													echo ">" . $registro['num_evaluados'] . "</td>";
													if ($registro['ultimo_acceso']=="") {
														echo "<td class='rojo'>Núnca ha ingresado</td>";
													} else {
														echo "<td>" . $registro['ultimo_acceso'] . "</td>";
													}
													echo "</tr>";
												}
											}
										}
										mysqli_free_result($resultado_busqueda);
									}
									mysqli_close($base_de_datos);
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<script>
		  //Funcion para la tabla_archivos
		    $(document).ready( function () {
		      var table = $('#tabla').DataTable( {
		        responsive: true,
		        "language": {
		          "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
		        },
		        "info": true,
		        "pagingType":"full_numbers",
		        dom: 'Bfrtip',
		        lengthMenu: [
		          [ 10, 25, 50, -1 ],
		          [ '10 Filas', '25 Filas', '50 Filas', 'Mostrar todo' ]
		        ],
		        buttons:{
		          buttons:[
		            { 
		              extend: 'excelHtml5',
		              text:'DESCARGAR EXCEL',
		              filename: 'PARTICIPACION DE COORDINADORES',
		              orientation: 'landscape'
		            },
		            { extend: 'copy', text:'COPIAR' },
		          ],
		        },
		      } );
		      table.on( 'responsive-resize', function ( e, datatable, columns ) {
		        var count = columns.reduce( function (a,b) {
		          return b === false ? a+1 : a;
		        }, 0 );
		        console.log( count +' columna(s) ocultas' );
		      } );
		    } );
		  //
		</script>
		<?php require_once 'lib/datatables.php'; ?>
	</body>
	<?php mysqli_close($base_de_datos); ?>
</html>