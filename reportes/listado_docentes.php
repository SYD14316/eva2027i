<?php
	//Incluyo el archivo de conexion
		include_once "funciones.php";
	//Reviso e incio la sesion
		session_start();
	//Obtengo y alamecno datos de la persona
		$nombre = $_SESSION['zez_a_nombre'];
		//$nombre = 'TEJEDA DAVALOS FABIOLA';
		$nivel_acceso = $_SESSION['zez_a_nivel_acceso'];
	//Defino una variable para filtrar la consulta
		$where = Null;
	//Verifico que el nivel de acceso le permita ver este reporte
		if (($nivel_acceso == 'PROFESOR') || ($nivel_acceso == 'ALUMNO') || ($nivel_acceso == '')) {
			//Redirijo a la pagina de inicio
			header("Location: ../index.php");
			//Salgo del script
			exit();
			//
		}
	//Reviso si el usuario es un jefe de departamento
		$cuenta_departamentos = busca_existencia("SELECT count(id) as exist from departamentos where jefe='$nombre'");
	//Evaluo el resultado
		if ($cuenta_departamentos > 0) {
			//Agrego una pre sentencia al ciclo where
				$where="AND (";
			//Busco las carreras que coordina en pos de los departamentos de los cuales es jefatura
				$sentencia="SELECT carreras.carrera from carreras as carreras inner join departamentos as departamento on carreras.departamento=departamento.departamento where departamento.jefe='$nombre'";
			//Ejecuto la sentencia y almaceno lo obtenido en una variable
				$resultado_sentencia = retorna_datos($sentencia);
			//Identifico si el reultado no es vacio
				if ($resultado_sentencia['rowCount'] > 0) {
					//Almaceno los datos obtenidos
						$resultado = $resultado_sentencia['data'];
					// Recorrer los datos y llenar las filas
						foreach ($resultado as $tabla) {
							//El resultado lo voy concatenando con la 
								echo $where .= "carrera='".$tabla['carrera']."' OR ";
							//Imprimo los resultados
						//
					}
					//
				}
			//Elimino el ultimo OR y le agrego un parentesis de cierre
				$where = substr($where, 0, -4).")";
			//
		}
	//Reviso si el usuario es un coordinador de carrera
		$cuenta_carreras = busca_existencia("SELECT count(id) as exist from carreras where coordinador='$nombre'");
	//Evaluo el resultado
		if ($cuenta_carreras > 0) {
			//Agrego una pre sentencia al ciclo where
				$where=" AND (";
			//Busco las carreras que coordina en pos de los departamentos de los cuales es jefatura
				$sentencia="SELECT carreras.carrera from carreras as carreras where carreras.coordinador='$nombre'";
			//Ejecuto la sentencia y almaceno lo obtenido en una variable
				$resultado_sentencia = retorna_datos($sentencia);
			//Identifico si el reultado no es vacio
				if ($resultado_sentencia['rowCount'] > 0) {
					//Almaceno los datos obtenidos
						$resultado = $resultado_sentencia['data'];
					// Recorrer los datos y llenar las filas
						foreach ($resultado as $tabla) {
							//El resultado lo voy concatenando con la 
								$where .= "carrera='".$tabla['carrera']."' OR ";
							//Imprimo los resultados
						//
					}
					//
				}
			//Elimino el ultimo OR y le agrego un parentesis de cierre
				$where = substr($where, 0, -4).")";
			//
		}
	//Verificacion final
		if ($where == "A)") {
			$where = Null;
		}
	//
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>LISTADO DE DOCENTES</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
	<div class="card">
		<div class="card-header">
			<h1>LISTADO DE DOCENTES</h1>
		</div>
		<div class="card-body">
			<table class="table table-bordered table-striped" id="tabla">
				<thead>
					<tr>
						<th>NOMBRE</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php
						//Defino la sentencia a ejecutar
							$sentencia = "SELECT DISTINCT nombre FROM profesores where nivel='LICENCIATURA' $where group by nombre";
						//Ejecuto la sentencia y almaceno lo obtenido en una variable
							$resultado_sentencia = retorna_datos($sentencia);
						//Identifico si el reultado no es vacio
							if ($resultado_sentencia['rowCount'] > 0) {
								//Almaceno los datos obtenidos
								$resultado = $resultado_sentencia['data'];
								// Recorrer los datos y llenar las filas
									foreach ($resultado as $tabla) {
										//Almaceno los resultados en variables
											$nombre = $tabla['nombre'];
										//Imprimo los resultados
											echo "
												<tr>
													<td>$nombre</td>
													<td>";
														//Defino la sentencia a ejecutar
															$sentencia_1 = "SELECT materia,carrera FROM profesores where nombre='$nombre' AND nivel='LICENCIATURA' $where";
														//Ejecuto la sentencia y almaceno lo obtenido en una variable
															$resultado_sentencia_1 = retorna_datos($sentencia_1);
														//Identifico si el reultado no es vacio
															if ($resultado_sentencia_1['rowCount'] > 0) {
																//Almaceno los datos obtenidos
																$resultado_1 = $resultado_sentencia_1['data'];
																// Recorrer los datos y llenar las filas
																	foreach ($resultado_1 as $tabla_1) {
																		$materia = $tabla_1['materia'];
																		$carrera = $tabla_1['carrera'];
																		echo "
																			<table class=\"table\">
																				<tr>
																					<td>
																						<a href=\"reporte_profesor_materia_sc.php?nombre=$nombre&materia=$materia&carrera=$carrera\" class=\"btn btn-sm btn-block btn-primary\" target=\"_blank\"><strong>REPORTE PARA DOCENTE</strong></a>
																					<br>
																						<a href=\"reporte_profesor_materia_cc.php?nombre=$nombre&materia=$materia&carrera=$carrera\" class=\"btn btn-sm btn-block btn-primary\" target=\"_blank\"><strong>REPORTE PARA COORDINADOR</strong></a>
																					</td>
																					<td>
																						<strong>$carrera</strong>
																					</td>
																					<td>
																						<strong>$materia</strong>
																					</td>
																				</tr>
																			</table>
																		";
																	}
																//
															}
														//
														echo "
													</td>
												</tr>
											";
										//
									}
								//
							}
						//
					?>
				</tbody>
			</table>
		</div>
	</div>
</body>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/cr-1.5.6/date-1.1.2/fc-4.1.0/fh-3.2.4/kt-2.7.0/r-2.3.0/rg-1.2.0/rr-1.2.8/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/sr-1.1.1/datatables.min.css" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/cr-1.5.6/date-1.1.2/fc-4.1.0/fh-3.2.4/kt-2.7.0/r-2.3.0/rg-1.2.0/rr-1.2.8/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/sr-1.1.1/datatables.min.js"></script>
<script type="text/javascript">
	//Funcion para la tabla
	$(document).ready(function() {
		var table = $('#tabla').DataTable({
			responsive: true,
			"language": {
				"url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
			},
			"info": true,
			"pagingType": "full_numbers",
			dom: 'Bfrtip',
			buttons: {
				buttons: [{
						extend: 'excelHtml5',
						text: 'DESCARGAR EXCEL',
						orientation: 'landscape'
					},
					{
						extend: 'print',
						text: 'IMPRIMIR'
					}, {
						extend: 'copy',
						text: 'COPIAR'
					},
				],
			},
		});
		table.on('responsive-resize', function(e, datatable, columns) {
			var count = columns.reduce(function(a, b) {
				return b === false ? a + 1 : a;
			}, 0);
			console.log(count + ' column(s) are hidden');
		});
	});
	//
</script>


</html>