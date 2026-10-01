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
				<a class="btn text-light" onclick="imprime_pagina();">
					<strong>Imprimir</strong>
				</a>
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
					<img src="lib/img/blanco.png" class="img-form-left" />
					<h1>UNIVERSIDAD MARISTA DE GUADALAJARA</h1>
					<h3>PROCESO DE EVALUACIÓN DOCENTE</h3>
					<h4>REPORTE FINAL GENERAL</h4>
				</div>
				<div class="card-body">
					<?php
						//Sentencia de obtención de datos
							//Total de alumnos
								$orden_sql = "SELECT count(id) as alumnos FROM participantes WHERE nivel='$parametros[0]' and nivel_acceso='ALUMNO'";
								$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
								if ($resultado_busqueda) {
									if (mysqli_num_rows($resultado_busqueda) > 0) {
										while($registro = mysqli_fetch_array($resultado_busqueda)) {
											$total_alumnos_grupo=$registro['alumnos'];
										}
									}
									mysqli_free_result($resultado_busqueda);
								}
							//Total de profesores
								$orden_sql = "SELECT count(id) as profesores FROM participantes WHERE nivel='$parametros[0]' and nivel_acceso='PROFESOR'";
								$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
								if ($resultado_busqueda) {
									if (mysqli_num_rows($resultado_busqueda) > 0) {
										while($registro = mysqli_fetch_array($resultado_busqueda)) {
											$total_profesores_grupo=$registro['profesores'];
										}
									}
									mysqli_free_result($resultado_busqueda);
								}
							//Total de coordinadores
								$orden_sql = "SELECT count(id) as coordinadores FROM carreras";
								$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
								if ($resultado_busqueda) {
									if (mysqli_num_rows($resultado_busqueda) > 0) {
										while($registro = mysqli_fetch_array($resultado_busqueda)) {
											$total_coordinadores_grupo=$registro['coordinadores'];
										}
									}
									mysqli_free_result($resultado_busqueda);
								}
							//
							//Total de evaluaciones que deben de realizar los alumnos
								$orden_sql = "SELECT sum(total_evaluaciones) as alumnos FROM participantes WHERE nivel='$parametros[0]' and nivel_acceso='ALUMNO'";
								$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
								if ($resultado_busqueda) {
									if (mysqli_num_rows($resultado_busqueda) > 0) {
										while($registro = mysqli_fetch_array($resultado_busqueda)) {
											$total_evaluaciones=$registro['alumnos'];
										}
									}
									mysqli_free_result($resultado_busqueda);
								}
							//Total de evaluaciones que deben de realizar los profesores
								$orden_sql = "SELECT sum(total_evaluaciones) as profesores FROM participantes WHERE nivel='$parametros[0]' and nivel_acceso='PROFESOR'";
								$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
								if ($resultado_busqueda) {
									if (mysqli_num_rows($resultado_busqueda) > 0) {
										while($registro = mysqli_fetch_array($resultado_busqueda)) {
											$total_evaluaciones_profesores=$registro['profesores'];
										}
									}
									mysqli_free_result($resultado_busqueda);
								}
							//Total de evaluaciones que deben de realizar los coordinadores
								$orden_sql = "SELECT count(id) as coordinadores FROM profesores WHERE nivel='$parametros[0]'";
								$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
								if ($resultado_busqueda) {
									if (mysqli_num_rows($resultado_busqueda) > 0) {
										while($registro = mysqli_fetch_array($resultado_busqueda)) {
											$total_evaluaciones_coordinadores=$registro['coordinadores'];
										}
									}
									mysqli_free_result($resultado_busqueda);
								}
							
							//Evaluaciones realizadas por alumnos en la aplicación A
								$orden_sql = "SELECT count(id) as alumnos FROM lic_profesores_por_alumnos where aplicacion=1;";
								$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
								if ($resultado_busqueda) {
									if (mysqli_num_rows($resultado_busqueda) > 0) {
										while($registro = mysqli_fetch_array($resultado_busqueda)) {
											$total_alumnos_evaluaron_1=$registro['alumnos'];
										}
									}
									mysqli_free_result($resultado_busqueda);
								}
							//Evaluaciones realizadas por alumnos en la aplicación B
								$orden_sql = "SELECT count(id) as alumnos FROM lic_profesores_por_alumnos where aplicacion=2;";
								$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
								if ($resultado_busqueda) {
									if (mysqli_num_rows($resultado_busqueda) > 0) {
										while($registro = mysqli_fetch_array($resultado_busqueda)) {
											$total_alumnos_evaluaron_2=$registro['alumnos'];
										}
									}
									mysqli_free_result($resultado_busqueda);
								}
							
							//Evaluaciones realizadas por profesores en la aplicación A
								$orden_sql = "SELECT count(id) as profesores FROM lic_profesores_por_profesores where aplicacion=1;";
								$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
								if ($resultado_busqueda) {
									if (mysqli_num_rows($resultado_busqueda) > 0) {
										while($registro = mysqli_fetch_array($resultado_busqueda)) {
											$total_profesores_evaluaron_1=$registro['profesores'];
										}
									}
									mysqli_free_result($resultado_busqueda);
								}
							//Evaluaciones realizadas por profesores en la aplicación B
								$orden_sql = "SELECT count(id) as profesores FROM lic_profesores_por_profesores where aplicacion=2;";
								$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
								if ($resultado_busqueda) {
									if (mysqli_num_rows($resultado_busqueda) > 0) {
										while($registro = mysqli_fetch_array($resultado_busqueda)) {
											$total_profesores_evaluaron_2=$registro['profesores'];
										}
									}
									mysqli_free_result($resultado_busqueda);
								}
							//
							//Evaluaciones realizadas por coordinadores en la aplicación A
								$orden_sql = "SELECT count(id) as coordinadores FROM lic_profesores_por_coordinadores where aplicacion=1;";
								$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
								if ($resultado_busqueda) {
									if (mysqli_num_rows($resultado_busqueda) > 0) {
										while($registro = mysqli_fetch_array($resultado_busqueda)) {
											$total_coordinadores_evaluaron_1=$registro['coordinadores'];
										}
									}
									mysqli_free_result($resultado_busqueda);
								}
							//Evaluaciones realizadas por coordinadores en la aplicación B
								$orden_sql = "SELECT count(id) as coordinadores FROM lic_profesores_por_coordinadores where aplicacion=2;";
								$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
								if ($resultado_busqueda) {
									if (mysqli_num_rows($resultado_busqueda) > 0) {
										while($registro = mysqli_fetch_array($resultado_busqueda)) {
											$total_coordinadores_evaluaron_2=$registro['coordinadores'];
										}
									}
									mysqli_free_result($resultado_busqueda);
								}
							//
						//Datos de alumnos
							//Total en la evaluación
								$total_alumnos_evaluaron=$total_alumnos_evaluaron_1+$total_alumnos_evaluaron_2;
							//Porcentajes de efectividad
								$porcentaje_efectividad_1=round(($total_alumnos_evaluaron_1/$total_alumnos_grupo), 2)*100;
								$porcentaje_efectividad_2=round(($total_alumnos_evaluaron_2/$total_alumnos_grupo), 2)*100;
							//Máxima puntuación en el reactivo
								$maxima_alumno_1=5*$total_alumnos_evaluaron_1;
								$maxima_alumno_2=5*$total_alumnos_evaluaron_2;
							//Máxima puntuación en la evaluación
								$maxima_general_alumno_1=$maxima_alumno_1*6;
								$maxima_general_alumno_2=$maxima_alumno_2*6;
							//
						//Datos de profesores
							//Total en la evaluación
								$total_profesores_evaluaron=$total_profesores_evaluaron_1+$total_profesores_evaluaron_2;
							//Porcentajes de efectividad
								$porcentaje_efectividad_profesores_1=round(($total_profesores_evaluaron_1/$total_profesores_grupo), 2)*100;
								$porcentaje_efectividad_profesores_2=round(($total_profesores_evaluaron_2/$total_profesores_grupo), 2)*100;
							//Máxima puntuación en el reactivo
								$maxima_profesor_1=5*$total_profesores_evaluaron_1;
								$maxima_profesor_2=5*$total_profesores_evaluaron_2;
							//Máxima puntuación en la evaluación
								$maxima_general_profesor_1=$maxima_profesor_1*5;
								$maxima_general_profesor_2=$maxima_profesor_2*5;
							//
						//Datos de coordinadores
							//Total en la evaluación
								$total_coordinadores_evaluaron=$total_coordinadores_evaluaron_1+$total_coordinadores_evaluaron_2;
							//porcentajes de efectividad
								$porcentaje_efectividad_coordinador_1=round(($total_coordinadores_evaluaron_1/$total_coordinadores_grupo), 2)*100;
								$porcentaje_efectividad_coordinador_2=round(($total_coordinadores_evaluaron_2/$total_coordinadores_grupo), 2)*100;
							//Máxima puntuación del reactivo
								$maxima_coordinador_1=10*$total_coordinadores_evaluaron_1;
								$maxima_coordinador_2=10*$total_coordinadores_evaluaron_2;
							//Máxima puntuación en la evaluación
								$maxima_general_coordinador_1=$maxima_coordinador_1*10;
								$maxima_general_coordinador_2=$maxima_coordinador_2*10;
							//
						//Inicio las variables de sumatorias en 0
							$valor_reactivo_1=array();
							$valor_reactivo_1=array();
							$valor_reactivo_2=array();
							$valor_reactivo_2=array();
						//
					?>
					<!-- Primera parte: Información principal -->
						<div class="contenedor-interno">
							<!-- Bloque de la tabla principal que miestra los datos estadisticos -->
								<table class="table table-bordered table-striped table-sm" style="width:auto;">
									<tr>
										<th class="fondo-marista" style="text-align:right;">Ciclo escolar</th>
										<td colspan="3"style="text-align:left;"><?php echo $ciclo[$parametros[0]]; ?></td>
									</tr>
									<tr>
										<th class="fondo-marista" style="text-align:right;">Fecha del reporte</th>
										<td colspan="3" style="text-align:left;"><?php echo transforma_fecha(ahora(1)); ?></td>
									</tr>
									<tr>
										<th class="fondo-marista" style="text-align:right;">Población total de alumnos</th>
										<th class="fondo-marista" style="text-align:center;">Evaluaciones aplicación A</th>
										<th class="fondo-marista" style="text-align:center;">Evaluaciones aplicación B</th>
										<th class="fondo-marista" style="text-align:center;">Total evaluaciones</th>
									</tr>
									<tr>
										<td style="text-align:center;"><?php echo $total_alumnos_grupo; ?></td>
										<td style="text-align:center;"><?php echo $total_alumnos_evaluaron_1; ?></td>
										<td style="text-align:center;"><?php echo $total_alumnos_evaluaron_2; ?></td>
										<td style="text-align:center;"><?php echo $total_alumnos_evaluaron; ?></td>
									</tr>
									<tr>
										<th class="fondo-marista" style="text-align:right;">Población total de profesores</th>
										<th class="fondo-marista" style="text-align:center;">Evaluaciones aplicación A</th>
										<th class="fondo-marista" style="text-align:center;">Evaluaciones aplicación B</th>
										<th class="fondo-marista" style="text-align:center;">Total evaluaciones</th>
									</tr>
									<tr>
										<td style="text-align:center;"><?php echo $total_profesores_grupo; ?></td>
										<td style="text-align:center;"><?php echo $total_profesores_evaluaron_1; ?></td>
										<td style="text-align:center;"><?php echo $total_profesores_evaluaron_2; ?></td>
										<td style="text-align:center;"><?php echo $total_profesores_evaluaron; ?></td>
									</tr>
									<tr>
										<th class="fondo-marista" style="text-align:right;">Población total de coordinadores</th>
										<th class="fondo-marista" style="text-align:center;">Evaluaciones aplicación A</th>
										<th class="fondo-marista" style="text-align:center;">Evaluaciones aplicación B</th>
										<th class="fondo-marista" style="text-align:center;">Total evaluaciones</th>
									</tr>
									<tr>
										<td style="text-align:center;"><?php echo $total_coordinadores_grupo; ?></td>
										<td style="text-align:center;"><?php echo $total_coordinadores_evaluaron_1; ?></td>
										<td style="text-align:center;"><?php echo $total_coordinadores_evaluaron_2; ?></td>
										<td style="text-align:center;"><?php echo $total_coordinadores_evaluaron; ?></td>
									</tr>
									<tr>
										<th class="fondo-marista" style="text-align:center;">Porcentajes</th>
										<th class="fondo-marista" style="text-align:center;">Aplicación A</th>
										<th class="fondo-marista" style="text-align:center;">Aplicación B</th>
										<th class="fondo-marista" style="text-align:center;">Final</th>
									</tr>
									<tr>
										<td style="text-align:left;"><strong>Efectividad de estudiantes</strong></td>
										<td style="text-align:center;"><strong><div id="porcentaje_final_alumno_1"></div></strong></td>
										<td style="text-align:center;"><strong><div id="porcentaje_final_alumno_2"></div></strong></td>
										<td style="text-align:center;"><strong><div id="porcentaje_final_alumno"></div></strong></td>
									</tr>
									<tr>
										<td style="text-align:left;"><strong>Efectividad de docente</strong></td>
										<td style="text-align:center;"><strong><div id="porcentaje_final_profesor_1"></div></strong></td>
										<td style="text-align:center;"><strong><div id="porcentaje_final_profesor_2"></div></strong></td>
										<td style="text-align:center;"><strong><div id="porcentaje_final_profesor"></div></strong></td>
									</tr>
									<tr>
										<td style="text-align:left;"><strong>Efectividad de coordinaciones</strong></td>
										<td style="text-align:center;"><strong><div>N/A</div></strong></td>
										<td style="text-align:center;"><strong><div id="porcentaje_final_coordinador_2"></div></strong></td>
										<td style="text-align:center;"><strong><div>N/A</div></strong></td>
									</tr>
									<tr>
										<td style="text-align:left;"><strong>GAP hacia el estudiante</strong></td>
										<td style="text-align:center;"><strong><div id="porcentaje_final_gap_1"></div></strong></td>
										<td style="text-align:center;"><strong><div id="porcentaje_final_gap_2"></div></strong></td>
										<td style="text-align:center;"><strong><div id="porcentaje_final_gap"></div></strong></td>
									</tr>
									<tr>
										<td style="text-align:left;"><strong>GAP hacia la coordinación</strong></td>
										<td style="text-align:center;"><strong><div>N/A</div></strong></td>
										<td style="text-align:center;"><strong><div id="porcentaje_final_gap_coordinador_2"></div></strong></td>
										<td style="text-align:center;"><strong><div>N/A</div></strong></td>
									</tr>
								</table>
							<!-- -->
						</div>
					<!-- Segunda parte: Evaluación de alumnos -->
						<div class="seccion"><span>1</span>Percepción estudiantil</div>
						<div class="contenedor-interno">
							<table class="table table-bordered table-striped table-sm" id="resultados_profesores">
								<tr>
									<th class="fondo-marista">#</th>
									<th class="fondo-marista">Reactivo</th>
									<th class="fondo-marista" style="text-align:center;">A</th>
									<th class="fondo-marista" style="text-align:center;">B</th>
									<th class="fondo-marista" style="text-align:center;">PROMEDIO</th>
								</tr>
								<?php
									//Incluyo el archivo que tiene los reactivos
										include_once 'lic_profesores_por_alumnos_texto.php';
									//Defino ca cantidad de activos
										$activos=$total_reactivos-1;
									//Defino variables iniciales
										//Variables de primera aplicación
											$sumatoria_alumno_1;
											$valor_reactivo_alumno_1;
											$suma_total_1[]=array();
											$sumatoria_general_1;
										//Variables de segunda aplicación
											$sumatoria_alumno_2;
											$valor_reactivo_alumno_2;
											$suma_total_2[]=array();
											$sumatoria_general_2;
										//
									//Creo un ciclo para recorrer la cantidad de reactivos y obtener sus respuestas
										for ($i=1; $i < $total_reactivos ; $i++) {
											//Inicio del registro
												echo "
													<tr>
														<td>$i</td>
														<td>$texto_reactivo[$i]</td>";
											//Defino la columna del reactivo
												if ($i<10) {
													$reactivo="r0$i";
												}else{
													$reactivo="r$i";
												}
											//Primera aplicación
												//Sentencia de consulta
													$orden_sql = "
														SELECT 
															$reactivo as valor, 
															count($reactivo) as cuenta, 
															sum($reactivo) as suma 
														FROM lic_profesores_por_alumnos WHERE aplicacion=1 
														GROUP by $reactivo
														ORDER by $reactivo asc;
													";
												// Ejecución de la consulta SQL
													$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
													if ($resultado_busqueda) {
														if (mysqli_num_rows($resultado_busqueda) > 0) {
															while($registro = mysqli_fetch_array($resultado_busqueda)) {
																//Defino el dato del reactivo
																	$dato="'".$reactivo."'";
																//Almaceno la suma de los resultados
																	$valor_reactivo_alumno_1[$dato][$registro['valor']]['sumatoria_valor']=$registro['suma'];
																//Almaceno la cuenta de los resultados
																	$valor_reactivo_alumno_1[$dato][$registro['valor']]['cuenta_valor']=$registro['cuenta'];
																//Realizo la sumatoria general
																	$sumatoria_alumno_1+=$registro['suma'];
																//
															}
														}
														// Libera el conjunto de resultados
														mysqli_free_result($resultado_busqueda);
													}
												//Datos segun el reactivo
													//Comienzo la suma de los porcentajes por reactivo de la evaluación
														$porcentaje_alumno_1+=round(($sumatoria_alumno_1/$maxima_alumno_1)*100, 2);
													//Imprimo el porcentaje del reactivo
														echo "<td class='text-align:center;'>".round(($sumatoria_alumno_1/$maxima_alumno_1)*100, 2)." %</td>";
													//
											//Primera aplicación
												//Sentencia de consulta
													$orden_sql = "
														SELECT 
															$reactivo as valor, 
															count($reactivo) as cuenta, 
															sum($reactivo) as suma 
														FROM lic_profesores_por_alumnos WHERE aplicacion=2 
														GROUP by $reactivo
														ORDER by $reactivo asc;
													";
												// Ejecución de la consulta SQL
													$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
													if ($resultado_busqueda) {
														if (mysqli_num_rows($resultado_busqueda) > 0) {
															while($registro = mysqli_fetch_array($resultado_busqueda)) {
																//Defino el dato del reactivo
																	$dato="'".$reactivo."'";
																//Almaceno la suma de los resultados
																	$valor_reactivo_alumno_2[$dato][$registro['valor']]['sumatoria_valor']=$registro['suma'];
																//Almaceno la cuenta de los resultados
																	$valor_reactivo_alumno_2[$dato][$registro['valor']]['cuenta_valor']=$registro['cuenta'];
																//Realizo la sumatoria general
																	$sumatoria_alumno_2+=$registro['suma'];
																//
															}
														}
														// Libera el conjunto de resultados
														mysqli_free_result($resultado_busqueda);
													}
												//Datos segun el reactivo
													//Comienzo la suma de los porcentajes por reactivo de la evaluación
														$porcentaje_alumno_2+=round(($sumatoria_alumno_2/$maxima_alumno_2)*100, 2);
													//Imprimo el porcentaje del reactivo
														echo "<td class='text-align:center;'>".round(($sumatoria_alumno_2/$maxima_alumno_2)*100, 2)." %</td>";
													//
												//
											//Calculo el porcentaje comparativo del reactivo
												$parcentaje_alumno_reactivo=round((round(($sumatoria_alumno_1/$maxima_alumno_1)*100, 2)+round(($sumatoria_alumno_2/$maxima_alumno_2)*100, 2))/2, 2);
											//Imprimo el porcentaje comparativo del reactivo
												echo "<td class='text-align:center;'>$parcentaje_alumno_reactivo %</td>";
											//Fin del registro
												echo "</tr>";
											//Retorno las sumatorias a 0
												$sumatoria_alumno_1=0;
												$sumatoria_alumno_2=0;
											//
										}
									//Divido los porcentajes de alumnos entre la cantidad de reactivos
										$final_alumno_1=$porcentaje_alumno_1/$activos;
										$final_alumno_2=$porcentaje_alumno_2/$activos;
									//Obtengo el promedio final de la evaluación
										$final_alumno=($final_alumno_1+$final_alumno_2)/2;
									//Asigno los datos a la tabla final
										echo "
											<tr>
												<script>$('#porcentaje_final_alumno_1').html('".(round($final_alumno_1, 2))." %');</script>
												<script>$('#porcentaje_final_alumno_2').html('".(round($final_alumno_2, 2))." %');</script>
												<script>$('#porcentaje_final_alumno').html('".(round($final_alumno, 2))." %');</script>
											</tr>
										";
									//
								?>
							</table>
						</div>
					<!-- Tercera parte: Autoevaluación docente -->
						<div class="seccion"><span>2</span>Autoevaluación docente</div>
						<div class="contenedor-interno">
							<table class="table table-bordered table-striped table-sm" id="resultados_profesores">
								<tr>
									<th class="fondo-marista">#</th>
									<th class="fondo-marista">Reactivo</th>
									<th class="fondo-marista" style="text-align:center;">A</th>
									<th class="fondo-marista" style="text-align:center;">B</th>
									<th class="fondo-marista" style="text-align:center;">PROMEDIO</th>
								</tr>
								<?php
									//Incluyo el archivo que tiene los reactivos
										include_once 'lic_profesores_por_profesores_texto.php';
									//Defino ca cantidad de activos
										$activos=$total_reactivos-1;
									//Defino variables iniciales
										//Variables de primera aplicación
											$sumatoria_profesor_1=null;
											$valor_reactivo_profesor_1=null;
											$suma_total_1[]=null;
											$sumatoria_general_1=null;
										//Variables de segunda aplicación
											$sumatoria_profesor_2=null;
											$valor_reactivo_profesor_2=null;
											$suma_total_2[]=null;
											$sumatoria_general_2=null;
										//
									//Creo un ciclo para recorrer la cantidad de reactivos y obtener sus respuestas
										for ($i=1; $i < $total_reactivos ; $i++) {
											//Inicio del registro
												echo "
													<tr>
														<td>$i</td>
														<td>$texto_reactivo[$i]</td>";
											//Defino la columna del reactivo
												if ($i<10) {
													$reactivo="r0$i";
												}else{
													$reactivo="r$i";
												}
											//Primera aplicación
												//Sentencia de consulta
													$orden_sql = "
														SELECT 
															$reactivo as valor, 
															count($reactivo) as cuenta, 
															sum($reactivo) as suma 
														FROM lic_profesores_por_profesores
														WHERE aplicacion=1
														GROUP by $reactivo
														ORDER by $reactivo asc;
													";
												// Ejecución de la consulta SQL
													$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
													if ($resultado_busqueda) {
														if (mysqli_num_rows($resultado_busqueda) > 0) {
															while($registro = mysqli_fetch_array($resultado_busqueda)) {
																//Defino el dato del reactivo
																	$dato="'".$reactivo."'";
																//Almaceno la suma de los resultados
																	$valor_reactivo_profesor_1[$dato][$registro['valor']]['sumatoria_valor']=$registro['suma'];
																//Almaceno la cuenta de los resultados
																	$valor_reactivo_profesor_1[$dato][$registro['valor']]['cuenta_valor']=$registro['cuenta'];
																//Realizo la sumatoria general
																	$sumatoria_profesor_1+=$registro['suma'];
																//
															}
														}
														// Libera el conjunto de resultados
														mysqli_free_result($resultado_busqueda);
													}
												//Datos segun el reactivo
													//Comienzo la suma de los porcentajes por reactivo de la evaluación
														$porcentaje_profesor_1+=round(($sumatoria_profesor_1/$maxima_profesor_1)*100, 2);
													//Imprimo el porcentaje del reactivo
														echo "<td class='text-align:center;'>".round(($sumatoria_profesor_1/$maxima_profesor_1)*100, 2)." %</td>";
													//
												//
											//Segunda aplicación
												//Sentencia de consulta
													$orden_sql = "
														SELECT 
															$reactivo as valor, 
															count($reactivo) as cuenta, 
															sum($reactivo) as suma 
														FROM lic_profesores_por_profesores
														WHERE aplicacion=2
														GROUP by $reactivo
														ORDER by $reactivo asc;
													";
												// Ejecución de la consulta SQL
													$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
													if ($resultado_busqueda) {
														if (mysqli_num_rows($resultado_busqueda) > 0) {
															while($registro = mysqli_fetch_array($resultado_busqueda)) {
																//Defino el dato del reactivo
																	$dato="'".$reactivo."'";
																//Almaceno la suma de los resultados
																	$valor_reactivo_profesor_2[$dato][$registro['valor']]['sumatoria_valor']=$registro['suma'];
																//Almaceno la cuenta de los resultados
																	$valor_reactivo_profesor_2[$dato][$registro['valor']]['cuenta_valor']=$registro['cuenta'];
																//Realizo la sumatoria general
																	$sumatoria_profesor_2+=$registro['suma'];
																//
															}
														}
														// Libera el conjunto de resultados
														mysqli_free_result($resultado_busqueda);
													}
												//Datos segun el reactivo
													//Comienzo la suma de los porcentajes por reactivo de la evaluación
														$porcentaje_profesor_2+=round(($sumatoria_profesor_2/$maxima_profesor_2)*100, 2);
													//Imprimo el porcentaje del reactivo
														echo "<td class='text-align:center;'>".round(($sumatoria_profesor_2/$maxima_profesor_2)*100, 2)." %</td>";
													//
												//
											//Calculo el porcentaje comparativo del reactivo
												$parcentaje_profesor_reactivo=round((round(($sumatoria_profesor_1/$maxima_profesor_1)*100, 2)+round(($sumatoria_profesor_2/$maxima_profesor_2)*100, 2))/2, 2);
											//Imprimo el porcentaje comparativo del reactivo
												echo "<td class='text-align:center;'>$parcentaje_profesor_reactivo %</td>";
											//Fin del registro
												echo "</tr>";
											//Retorno las sumatorias a 0
												$sumatoria_profesor_1=0;
												$sumatoria_profesor_2=0;
											//
										}
									//Divido los porcentajes de profesores entre la cantidad de reactivos
										$final_profesor_1=$porcentaje_profesor_1/$activos;
										$final_profesor_2=$porcentaje_profesor_2/$activos;
									//Obtengo el promedio final de la evaluación
										$final_profesor=($final_profesor_1+$final_profesor_2)/2;
									//Asigno los datos a la tabla final
										echo "
											<tr>
												<script>$('#porcentaje_final_profesor_1').html('".(round($final_profesor_1, 2))." %');</script>
												<script>$('#porcentaje_final_profesor_2').html('".(round($final_profesor_2, 2))." %');</script>
												<script>$('#porcentaje_final_profesor').html('".(round($final_profesor, 2))." %');</script>
											</tr>
										";
									//
								?>
							</table>
						</div>
					<!-- Cuarta parte: Evaluación de coordinador -->
						<div class="seccion"><span>3</span>Evaluación del coordinador</div>
						<div class="contenedor-interno">
							<table class="table table-bordered table-striped table-sm" id="resultados_coordinadores">
								<tr>
									<th class="fondo-marista">#</th>
									<th class="fondo-marista">Reactivo</th>
									<th class="fondo-marista" style="text-align:center;">A</th>
									<th class="fondo-marista" style="text-align:center;">B</th>
									<th class="fondo-marista" style="text-align:center;">PROMEDIO</th>
								</tr>
								<?php
									//Incluyo el archivo que tiene los reactivos
										include_once 'lic_profesores_por_coordinadores_texto.php';
									//Defino ca cantidad de activos
										$activos=$total_reactivos-1;
									//Defino variables iniciales
										//Variables de primera aplicación
											$sumatoria_coordinador_1=null;
											$valor_reactivo_coordinador_1=null;
											$suma_total_1[]=null;
											$sumatoria_general_1=null;
										//Variables de segunda aplicación
											$sumatoria_coordinador_2=null;
											$valor_reactivo_coordinador_2=null;
											$suma_total_2[]=null;
											$sumatoria_general_2=null;
										//
									//Creo un ciclo para recorrer la cantidad de reactivos y obtener sus respuestas
										for ($i=1; $i < $total_reactivos ; $i++) {
											//Inicio del registro
												echo "
													<tr>
														<td>$i</td>
														<td>$texto_reactivo[$i]</td>";
											//Defino la columna del reactivo
												if ($i<10) {
													$reactivo="r0$i";
												}else{
													$reactivo="r$i";
												}
											/*//Primera aplicación
												//Sentencia de consulta
													$orden_sql = "
														SELECT 
															$reactivo as valor, 
															count($reactivo) as cuenta, 
															sum($reactivo) as suma 
														FROM lic_profesores_por_coordinadores 
														WHERE aplicacion=1
														GROUP by $reactivo
														order by $reactivo asc;
													";
												// Ejecución de la consulta SQL
													$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
													if ($resultado_busqueda) {
														if (mysqli_num_rows($resultado_busqueda) > 0) {
															while($registro = mysqli_fetch_array($resultado_busqueda)) {
																//Defino el dato del reactivo
																	$dato="'".$reactivo."'";
																//Separo el reactivo 2 del 1
																	if ($reactivo=='r01') {
																		//Almaceno la suma de los resultados
																			$valor_reactivo_coordinador_1[$dato][$registro['valor']]['sumatoria_valor']=($registro['suma']*10);
																		//Almaceno la cuenta de los resultados
																			$valor_reactivo_coordinador_1[$dato][$registro['valor']]['cuenta_valor']=$registro['cuenta'];
																		//Realizo la sumatoria general
																			$sumatoria_coordinador_1+=($registro['suma']*10);
																		//
																	}else{
																		//Almaceno la suma de los resultados
																			$valor_reactivo_coordinador_1[$dato][$registro['valor']]['sumatoria_valor']=$registro['suma'];
																		//Almaceno la cuenta de los resultados
																			$valor_reactivo_coordinador_1[$dato][$registro['valor']]['cuenta_valor']=$registro['cuenta'];
																		//Realizo la sumatoria general
																			$sumatoria_coordinador_1+=$registro['suma'];
																		//
																	}
																//
															}
														}
														// Libera el conjunto de resultados
														mysqli_free_result($resultado_busqueda);
													}
												//Datos segun el reactivo
													//Comienzo la suma de los porcentajes por reactivo de la evaluación
														$porcentaje_coordinador_1+=round(($sumatoria_coordinador_1/$maxima_coordinador_1)*100, 2);
													//Imprimo el porcentaje del reactivo
														echo "<td class='text-align:center;'>".round(($sumatoria_coordinador_1/$maxima_coordinador_1)*100, 2)." %</td>";
													//
												//*/
												//Comienzo la suma de los porcentajes por reactivo de la evaluación
													$porcentaje_coordinador_1=0;
												//Imprimo el porcentaje del reactivo
													echo "<td class='text-align:center;'>N/A</td>";
												//
											//Segunda aplicación
												//Sentencia de consulta
													$orden_sql = "
														SELECT 
															$reactivo as valor, 
															count($reactivo) as cuenta, 
															sum($reactivo) as suma 
														FROM lic_profesores_por_coordinadores 
														WHERE aplicacion=2
														GROUP by $reactivo
														order by $reactivo asc;
													";
												// Ejecución de la consulta SQL
													$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
													if ($resultado_busqueda) {
														if (mysqli_num_rows($resultado_busqueda) > 0) {
															while($registro = mysqli_fetch_array($resultado_busqueda)) {
																//Defino el dato del reactivo
																	$dato="'".$reactivo."'";
																//Separo el reactivo 2 del 1
																	if ($reactivo=='r01') {
																		//Almaceno la suma de los resultados
																			$valor_reactivo_coordinador_2[$dato][$registro['valor']]['sumatoria_valor']=($registro['suma']*10);
																		//Almaceno la cuenta de los resultados
																			$valor_reactivo_coordinador_2[$dato][$registro['valor']]['cuenta_valor']=$registro['cuenta'];
																		//Realizo la sumatoria general
																			$sumatoria_coordinador_2+=($registro['suma']*10);
																		//
																	}else{
																		//Almaceno la suma de los resultados
																			$valor_reactivo_coordinador_2[$dato][$registro['valor']]['sumatoria_valor']=$registro['suma'];
																		//Almaceno la cuenta de los resultados
																			$valor_reactivo_coordinador_2[$dato][$registro['valor']]['cuenta_valor']=$registro['cuenta'];
																		//Realizo la sumatoria general
																			$sumatoria_coordinador_2+=$registro['suma'];
																		//
																	}
																//
															}
														}
														// Libera el conjunto de resultados
														mysqli_free_result($resultado_busqueda);
													}
												//Datos segun el reactivo
													//Comienzo la suma de los porcentajes por reactivo de la evaluación
														$porcentaje_coordinador_2+=round(($sumatoria_coordinador_2/$maxima_coordinador_2)*100, 2);
													//Imprimo el porcentaje del reactivo
														echo "<td class='text-align:center;'>".round(($sumatoria_coordinador_2/$maxima_coordinador_2)*100, 2)." %</td>";
													//
												//
											//Datos segun el reactivo
												//Calculo el porcentaje comparativo del reactivo
													$parcentaje_coordinador_reactivo=round(($sumatoria_coordinador_2/$maxima_coordinador_2)*100, 2);
												//Imprimo el porcentaje comparativo del reactivo
													echo "<td class='text-align:center;'>$parcentaje_coordinador_reactivo %</td>";
												//
											//Fin del registro
												echo "</tr>";
											//Retorno las sumatorias a 0
												$sumatoria_coordinador_1=0;
												$sumatoria_coordinador_2=0;
											//
										}
									//Divido los porcentajes de coordinadores entre la cantidad de reactivos
										$final_coordinador_1=$porcentaje_coordinador_1/$activos;
										$final_coordinador_2=$porcentaje_coordinador_2/$activos;
									//Obtengo el promedio final de la evaluación
										$final_coordinador=$porcentaje_coordinador_2/10;
									//Asigno los datos a la tabla final
										echo "
											<tr>
												<script>$('#porcentaje_final_coordinador_1').html('".(round($final_coordinador_1, 2))." %');</script>
												<script>$('#porcentaje_final_coordinador_2').html('".(round($final_coordinador_2, 2))." %');</script>
												<script>$('#porcentaje_final_coordinador').html('".(round($final_coordinador, 2))." %');</script>
											</tr>
										";
									//
								?>
							</table>
						</div>
					<!-- -->
						<?php
							$final_gap_1=$final_alumno_1-$final_profesor_1;
							$final_gap_coordinador_1=$final_coordinador_1-$final_profesor_1;
							$final_gap_2=$final_alumno_2-$final_profesor_2;
							$final_gap_coordinador_2=$final_coordinador_2-$final_profesor_2;
							$final_gap=$final_alumno-$final_profesor;
							$final_gap_coordinador=$final_coordinador-$final_profesor;
							echo "
								<tr>
									<script>$('#porcentaje_final_gap_1').html('".(round($final_gap_1, 2))." %');</script>
									<script>$('#porcentaje_final_gap_coordinador_1').html('".(round($final_gap_coordinador_1, 2))." %');</script>
									<script>$('#porcentaje_final_gap_2').html('".(round($final_gap_2, 2))." %');</script>
									<script>$('#porcentaje_final_gap_coordinador_2').html('".(round($final_gap_coordinador_2, 2))." %');</script>
									<script>$('#porcentaje_final_gap').html('".(round($final_gap, 2))." %');</script>
									<script>$('#porcentaje_final_gap_coordinador').html('".(round($final_gap_coordinador, 2))." %');</script>
								</tr>
							";
						?>
					<!-- Comentarios de alumnos -->
						<div class="seccion"><span>4</span>Comentarios de estudiantes</div>
						<div class="contenedor-interno">
							<table class="table table-bordered table-striped table-sm" id="comentarios_alumnos">
								<tr><th class="fondo-marista">Comentarios</th></tr>
								<?php
									$orden_sql = "
										SELECT r07 FROM lic_profesores_por_alumnos WHERE r07<>'';
									";
									// Ejecuta la consulta SQL
									$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
									if ($resultado_busqueda) {
										if (mysqli_num_rows($resultado_busqueda) > 0) {
											// Si hubo resultado obtiene los datos de la sesión y activa la bandera
											while($registro = mysqli_fetch_array($resultado_busqueda)) {
												echo "<tr><td>".$registro['r07']."</td></tr>";
											}
										}
										// Libera el conjunto de resultados
										mysqli_free_result($resultado_busqueda);
									}
								?>
							</table>
						</div>
					<!-- Comentarios de docentes -->
						<div class="seccion"><span>5</span>Comentarios de docentes</div>
						<div class="contenedor-interno">
							<table class="table table-bordered table-striped table-sm" id="comentarios_docentes">
								<tr><th class="fondo-marista">Comentarios</th></tr>
								<?php
									$orden_sql = "
										SELECT r06 FROM lic_profesores_por_profesores WHERE r06<>'';
									";
									// Ejecuta la consulta SQL
									$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
									if ($resultado_busqueda) {
										if (mysqli_num_rows($resultado_busqueda) > 0) {
											// Si hubo resultado obtiene los datos de la sesión y activa la bandera
											while($registro = mysqli_fetch_array($resultado_busqueda)) {
												echo "<tr><td>".$registro['r06']."</td></tr>";
											}
										}
										// Libera el conjunto de resultados
										mysqli_free_result($resultado_busqueda);
									}
								?>
							</table>
						</div>
					<!-- Comentarios de docentes -->
						<div class="seccion"><span>6</span>Comentarios de coordinación</div>
						<div class="contenedor-interno">
							<table class="table table-bordered table-striped table-sm" id="comentarios_docentes">
								<tr><th class="fondo-marista">Comentarios</th></tr>
								<?php

									$orden_sql = "SELECT r11 FROM lic_profesores_por_coordinadores WHERE r11<>'';";
									// Ejecuta la consulta SQL
									$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
									if ($resultado_busqueda) {
										if (mysqli_num_rows($resultado_busqueda) > 0) {
											// Si hubo resultado obtiene los datos de la sesión y activa la bandera
											while($registro = mysqli_fetch_array($resultado_busqueda)) {
												echo "<tr><td>".$registro['r11']."</td></tr>";
											}
										}
										// Libera el conjunto de resultados
										mysqli_free_result($resultado_busqueda);
									}
								?>
							</table>
						</div>
					<!-- -->
				</div>
			</div>
		</div>
	</body>
	<?php mysqli_close($base_de_datos); ?>
	<script type="text/javascript">
		function imprime_pagina() { window.print(); }
	</script>
</html>