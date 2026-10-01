<?php
	//Inicio la sesion
	session_start();
	//Almaceno variables de sesion en variables estaticas
	$zez_a_nivel_acceso=$_SESSION['zez_a_nivel_acceso'];
	$zez_a_nombre=$_SESSION['zez_a_nombre'];
	$zez_a_nivel=$_SESSION['zez_a_nivel'];
	//Incluyo el archivo de configuracion 
	require_once 'lib/config.php';
	if (!isset($_SESSION['zez_a_nombre'])) {
		//Evaluo si la variable de sesion de nombre esta declarada
		header('Location: salir.php');
		//Si la variable de sesion del nombre no esta actiuva, se sale de la aplicacion
	} elseif (strpos(" INGLES ACADEMICO DIRECTOR COORDINADOR REVISOR VICERRECTOR FIDCO ADMINISTRADOR SUPERUSUARIO",$_SESSION['zez_a_nivel_acceso'])== FALSE) {
		//Evaluo si en la varible de sesion para el nivel de acceso contiene algunod elos niveles mencionados
		if (date("Y-m-d") < $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) {
			//Evaluo si la fecha actual es menor a la fecha asignada para el nivel educativo y el nivel de acceso
			header('Location: no_iniciada.php');
			//Mando a la pagina de evaluacion no iniicada
		} elseif (date("Y-m-d") > $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin']) {
			//Evaluo si la fecha actual es mayor a la fecha asignada para el nivel educativo y el nivel de acceso
			header('Location: cerrada.php');
			//Mando a la pagina de evaluacion cerrada
		} else {
			//Si la fecha esta denro del rango de evaluacion para el nivel educativo y el nivel de acceso
			header('Location: mis_evaluaciones.php');
			//Se manda a la pagina de evaluaciones
		}
	}
	//Defino la variable de parametros validos en falso
	$parametros_validos = false;
	//Se Eliminan las variables y se libera la memoria que ocupaban
	unset($parametros);
	//defino el string de permisos en vacio
	$string_parametros = "";
	if (isset($_POST['parametros'])) {
		//Evaluo si se enviaron los datos de los parametros
		$string_parametros = desencriptar($_POST['parametros']);
		//Se desencriptan y almacenan los parametros enviados
		if ((substr($string_parametros,0,2)=="%%") AND (substr($string_parametros,(strlen($string_parametros)-2),2)=="%%")) {
			//Evaluo si la cadena $string_parametros comienza y termina con "%%"
			$string_parametros = substr($string_parametros,2,strlen($string_parametros)-4);
			//Se elimina los dos primeros caracteres y los dos últimos caracteres y se almacena en la variable de parametros
			$parametros_validos = true;
			//Se define la variable de parametros validos como verdadera
			if(strpos($string_parametros,"¬")===FALSE) {
				//Se evalua si la raviable contiene el caracter ¬
				$parametros[] = $string_parametros;
				//Se asigna el valor de la variable de parametros al arreglo de parametros
			} else {
				//Si no existe el caracter en la variable de parametros
				do {
					// Busca la posición del primer "¬" en la cadena
					$posicion_delimitador = strpos($string_parametros, "¬");
					// Extrae la subcadena desde el inicio hasta el primer "¬" y la agrega a $parametros
					$parametros[] = substr($string_parametros, 0, $posicion_delimitador);
					// Actualiza $string_parametros para que comience después del primer "¬" y dos caracteres adicionales
					$string_parametros = substr($string_parametros, $posicion_delimitador + 2);
				} while (!($posicion_delimitador === FALSE));
				//Se realiza el ciclo mientras el valor del delimitador no sea falso
				$parametros[] = $string_parametros;
				//Se asigna el valor de la variable de parametros al arreglo de parametros
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
		<!-- Boton para volver a los reportes -->
			<div>
				<a class="btn text-light" onclick="redirigir('mis_reportes.php');">
					<strong>Mis Reportes</strong>
				</a>
			</div>
		<!-- Marcador del reporte actual -->
			<div>
				<a class="btn btn-warning text-dark">
					<strong>Reporte Actual</strong>
				</a>
			</div>
		<!-- Boton para cerrar la sesion -->
			<div class="ml-auto">
				<a class="btn text-light" onclick="redirigir('salir.php');">
					<strong>Salir</strong>
				</a>
			</div>
		<!-- -->
	</div>
	<body>
		<div class="container">
			<div class="card">
				<div class="card-header">
					<h1>
						<img src="lib/img/blanco.png" class="img-form-left" />
						<?php echo "RESULTADOS DE PROFESORES DE " . $parametros[0]; ?>
					</h1>
					<span>CICLO <?php echo $ciclo[$parametros[0]]; ?></span>
				</div>
				<div class="card-body">
					<!-- Primera parte (informacion inicial) -->
						<div class="seccion">
							<span>1</span>Estadísticas
						</div>
						<div class="contenedor-interno">
							<?php
								//Se Eliminan las variables y se libera la memoria que ocupaban
								unset($total_profesores);
								unset($profesores_ingresaron);
								unset($total_evaluaciones);
								unset($evaluaciones_contestadas);
								//Se define la variable como falsa
								$coordinador_materias_sello = false;
								//onsulta para determinar si es un coordinador de ,materias sello
								$orden_sql = "SELECT * FROM carreras WHERE carrera = 'MATERIAS SELLO'";
								// Ejecucion de la sentencia SQL
								$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
								//Se evalua si hubo un resultado
								if ($resultado_busqueda) {
									//Si el total de filas obtenidas es mayor a 0
									if (mysqli_num_rows($resultado_busqueda) > 0) {
										//mientras haya filas o registros disponibles en el resultado de la consulta
										while($registro = mysqli_fetch_array($resultado_busqueda)) {
											//Si el nombre del coordinador coresponde con el del usuario activo
											if($registro['coordinador']==$zez_a_nombre){
												//Se define la variable como verdadera
												$coordinador_materias_sello = true;
											}
										}
									}
									// Libera el conjunto de resultados
									mysqli_free_result($resultado_busqueda);
								}
								//Se definen variables con valor 0
								$total_profesores = 0;
								$profesores_ingresaron = 0;
								$total_evaluaciones = 0;
								$evaluaciones_contestadas = 0;
								//Verifico si el nivel de acceso que se tiene es de coordinador
								if (strpos(" COORDINADOR",$zez_a_nivel_acceso)!= FALSE) {
									//Genero un codigo de sql que me extraiga los datos de cada registro mientras el nombre del condinador en la tabla carreras o el nombre del jefe de departamento, corresponda con el nombre del usuario
									$orden_sql = "
										SELECT DISTINCT
											PA.nombre,
											PA.total_evaluaciones,
											PA.num_evaluados,
											PA.ultimo_acceso
										FROM participantes PA
											INNER JOIN profesores PR ON PA.nombre=PR.nombre  AND PA.nivel='$zez_a_nivel' 
											INNER JOIN carreras C  ON PR.carrera=C.carrera  AND PR.sello=C.sello 
											INNER JOIN departamentos D  ON C.departamento=D.departamento 
										WHERE 
											PA.nivel_acceso='PROFESOR' AND 
											(
												C.coordinador='$zez_a_nombre' OR 
												D.jefe='$zez_a_nombre'
											) 
										ORDER BY PA.nombre ASC
									";
								} else {
									//Selecciono todos los datos de la columna participantes mientras su nivel de acceso sea de profesor y los ordeno por su nombre
									$orden_sql = "
										SELECT * FROM participantes 
										WHERE 
											nivel='$parametros[0]' AND 
											nivel_acceso='PROFESOR' 
										ORDER BY nombre ASC";
								}
								// Ejecuta la consulta SQL
								$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
								//Mientras se tenga una respuesta
								if ($resultado_busqueda) {
									//Mientras el numero de filas obtenidas sea mayor de 0
									if (mysqli_num_rows($resultado_busqueda) > 0) {
										// Si hubo resultado obtiene los datos de la sesión y activa la bandera
										while($registro = mysqli_fetch_array($resultado_busqueda)) {
											//Aumento el numero de profesores en 1
											$total_profesores++;
											//Si el dato de ultimo acceso no es vacio
											if ($registro['ultimo_acceso']!="") {
												//Aumento la cantidad de profesores que ingresaron
												$profesores_ingresaron++;	
												//Voy sumando la cantidad de evaluaciones totales de los profesores que ingresaron
												$total_evaluaciones += $registro['total_evaluaciones'];
												//Voy sumando la cantidad de evaluaciones contestadas de los profesores que ingresaron
												$evaluaciones_contestadas += $registro['num_evaluados'];
											}
										}
									}
									// Libera el conjunto de resultados
									mysqli_free_result($resultado_busqueda);
								}
							?>
							<!-- Bloque de la tabla principal que miestra los datos estadisticos -->
								<table class="table table-bordered table-striped table-sm" style="width:auto;">
									<tr>
										<th class="fondo-marista" style="text-align:right;">Profesores que ingresaron:</th>
										<td style="text-align:left;">
											<?php 
												if ($total_profesores == 0){
													//Si el total de profesores es igual a 0 se escribe el valor 0
													echo "0";
												} else {
													//Si no, se redondea la cantidad de profsores que ingresaron entre los profesores totales a 4 decimales, se multiplica por 100 y se imprime el resultado
													echo (round($profesores_ingresaron/$total_profesores, 4)*100);
												}
												//Se imprimen los valores de referencia
												echo "%, $profesores_ingresaron de $total_profesores";
											?>
										</td>
									</tr>
									<tr>
										<th class="fondo-marista" style="text-align:right;">Evaluaciones contestadas:<br />(de los que ingresaron)</th>
										<td style="text-align:left;">
											<?php
												if ($total_evaluaciones == 0){
													//Si el total de evaluaciones es igual a 0 se escribe el valor 0
													echo "0";
												} else {
													//Si no, se redondea la cantidad de evaluaciones contestadas entre las evaluaciones totales a 4 decimales, se multiplica por 100 y se imprime el resultado
													echo (round($evaluaciones_contestadas/$total_evaluaciones, 4)*100);
												}
												//Se imprimen los valores de referencia
												echo "%, $evaluaciones_contestadas de $total_evaluaciones";
											?>
										</td>
									</tr>
									<tr>
										<th class="fondo-marista" style="text-align:right;">Índice de participación general:</th>
										<td style="text-align:left;">
											<?php
												if (($total_profesores == 0) or ($total_evaluaciones == 0)){
													//Si el total de evaluaciones o el total de profesores o el total de evaluaciones es igual a o
													echo "0";
												} else {
													//Se calcula el indice de participacion general multiplicando el porcentaje de profesores que ingrsaron por el porcentaje de evaluaciones contestadas
													echo round((round($profesores_ingresaron/$total_profesores, 4)*100) * round($evaluaciones_contestadas/$total_evaluaciones, 4), 2);
												}
												echo "%";
											?>
										</td>
									</tr>
								</table>
							<!-- -->
						</div>
					<!-- Segunda parte (tabla de resultados) -->
						<div class="seccion"><span>2</span>Tabla de profesores</div>
						<div class="contenedor-interno">
							<table class="table table-bordered table-striped table-sm" id="tabla">
								<thead>
									<tr>
										<th class="fondo-marista">Nombre</th>
										<th class="fondo-marista"></th>
									</tr>
								</thead>
								<tbody>
									<?php
										// Ejecuta la consulta SQL
										$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
										if ($resultado_busqueda) {
											if (mysqli_num_rows($resultado_busqueda) > 0) {
												// Si hubo resultado obtiene los datos de la sesión y activa la bandera
												while($registro = mysqli_fetch_array($resultado_busqueda)) {
													echo "
														<tr>
															<td>".$registro['nombre']."</td>"; ?>
															<td>
																<form action="grupos.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
																	<input type=hidden name="parametros" value="<?php echo encriptar("%%LICENCIATURA"."¬  ".$registro['nombre']."%%"); ?>" />
																	<input type=submit class='btn btn-primary' value="VER RESULTADOS" />
																</form>
															</td> <?php echo "
														</tr>
													";
												}
											}
											// Libera el conjunto de resultados
											mysqli_free_result($resultado_busqueda);
										}
									?>
								</tbody>
							</table>
						</div>
					<!-- -->
				</div>
			</div>
		</div>
		<script type="text/javascript">
		  //Funcio para la tabla
		    $(document).ready( function () {
		      var table = $('#tabla').DataTable( {
		        responsive: true,
		        "language": {
		          "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
		        },
		        "info": true,
		        "pagingType":"full_numbers",
		        dom: 'Bfrtip',
		        buttons:{
		          buttons:[],
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
		<?php require_once 'lib/datatables.php'; ?>
	</body>
	<?php mysqli_close($base_de_datos); ?>
</html>