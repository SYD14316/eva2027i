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
					<h4>REPORTE GENERAL</h4>
				</div>
				<div class="card-body">
					<?php
						//obtengo la cantidad de alumnos registrados en el grupo
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
						//obtengo la cantidad de profesores registrados
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
						//Obtengo la cantidad de evaluaciones a realizar
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
						//Obtengo la cantidad de evaluaciones a realizar
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
						//obtengo la cantidad de alumnos que realizaron la evaluacion
							$orden_sql = "SELECT count(id) as alumnos FROM lic_profesores_por_alumnos where aplicacion=1";
							$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
							if ($resultado_busqueda) {
								if (mysqli_num_rows($resultado_busqueda) > 0) {
									while($registro = mysqli_fetch_array($resultado_busqueda)) {
										$total_alumnos_evaluaron=$registro['alumnos'];
									}
								}
								mysqli_free_result($resultado_busqueda);
							}
						//obtengo la cantidad de alumnos que realizaron la evaluacion
							$orden_sql = "SELECT count(id) as profesores FROM lic_profesores_por_profesores where aplicacion=1";
							$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
							if ($resultado_busqueda) {
								if (mysqli_num_rows($resultado_busqueda) > 0) {
									while($registro = mysqli_fetch_array($resultado_busqueda)) {
										$total_profesores_evaluaron=$registro['profesores'];
									}
								}
								mysqli_free_result($resultado_busqueda);
							}
						//Calculo el porcentaje de efectividad
							$porcentaje_efectividad=(round($total_alumnos_evaluaron/$total_alumnos_grupo, 4)*100);
							$porcentaje_efectividad_profesores=(round($total_profesores_evaluaron/$total_profesores_grupo, 4)*100);
						//Calculo la puntuacion maxima por reactivo yla maxima general
							$maxima=5*$total_alumnos_evaluaron;
							$maxima_general=$maxima*5;

							$maxima_profesores=5*$total_profesores_evaluaron;
							$maxima_general_profesores=$maxima_profesores*10;

							for ($i=1; $i < 7 ; $i++) {
								$reactivo='r0'.$i;
								for ($j=0; $j < 6 ; $j++) { 
									$valor_reactivo[$reactivo][$j]['sumatoria_valor']=Null;
									$valor_reactivo[$reactivo][$j]['cuenta_valor']=Null;
								}
							}

					?>
					<!-- Primera parte (informacion inicial) -->
						<div class="contenedor-interno">
							<!-- Bloque de la tabla principal que miestra los datos estadisticos -->
								<table class="table table-bordered table-striped table-sm" style="width:auto;">
									<tr>
										<th class="fondo-marista" style="text-align:right;">Ciclo escolar</th>
										<td style="text-align:left;"><?php echo $ciclo[$parametros[0]]; ?></td>
										<th class="fondo-marista" style="text-align:right;">Periodo de evaluacion</th>
										<td colspan=""  style="text-align:left;">A</td>
									</tr>
									<tr>
										<th class="fondo-marista" style="text-align:right;">Fecha del reporte</th>
										<td colspan="3" style="text-align:left;"><?php echo transforma_fecha(ahora(1)); ?></td>
									</tr>
									<!--tr>
										<th class="fondo-marista" style="text-align:right;">Poblacion total de alumnos</th>
										<td style="text-align:left;"><?php echo $total_alumnos_grupo; ?></td>
										<th class="fondo-marista" style="text-align:right;">Evaluaciones totales</th>
										<td style="text-align:left;"><?php echo $total_evaluaciones; ?></td>
										<th class="fondo-marista" style="text-align:right;">Evaluaciones registradas</th>
										<td style="text-align:left;"><?php echo $total_alumnos_evaluaron; ?></td>
									</tr>
									<tr>
										<th class="fondo-marista" style="text-align:right;">Poblacion total de profesores</th>
										<td style="text-align:left;"><?php echo $total_profesores_grupo; ?></td>
										<th class="fondo-marista" style="text-align:right;">Evaluaciones totales</th>
										<td style="text-align:left;"><?php echo $total_evaluaciones_profesores; ?></td>
										<th class="fondo-marista" style="text-align:right;">Evaluaciones registradas</th>
										<td style="text-align:left;"><?php echo $total_profesores_evaluaron; ?></td>
									</tr-->
									<tr>
										<th class="fondo-marista" style="text-align:right;">Resultado % de efectividad de alumnos</th>
										<td colspan="3" style="text-align:left;"><strong><div id="porcentaje_final_alumnos"></div></strong></td>
									</tr>
									<tr>
										<th class="fondo-marista" style="text-align:right;">Resultado % de efectividad de profesores</th>
										<td colspan="3" style="text-align:left;"><strong><div id="porcentaje_final_profesores"></div></strong></td>
									</tr>
								</table>
							<!-- -->
						</div>
					<!-- Percepción estudiantil -->
						<div class="seccion"><span>1</span>Percepción estudiantil</div>
						<div class="contenedor-interno">
														<table class="table table-bordered table-striped table-sm" id="resultados_profesores_por_alumnos">
																<thead>
																	<tr>
																		<th class="fondo-marista">#</th>
																		<th class="fondo-marista">Reactivo</th>
																		<th class="fondo-marista">1</th>
																		<th class="fondo-marista">2</th>
																		<th class="fondo-marista">3</th>
																		<th class="fondo-marista">4</th>
																		<th class="fondo-marista">5</th>
																		<th class="fondo-marista">% de efectividad</th>
																	</tr>
																</thead>
																<tbody>
																<?php
									//Incluyo el archivo que tiene los reactivos
									include_once 'lic_profesores_por_alumnos_texto.php';
									// Inicializar el arreglo de valores por reactivo para evitar warnings
									$valor_reactivo = array();
									for ($k = 1; $k < $total_reactivos; $k++) {
										$r = 'r0'.$k;
										for ($m = 1; $m <= 5; $m++) {
											$valor_reactivo[$r][$m]['sumatoria_valor'] = 0;
											$valor_reactivo[$r][$m]['cuenta_valor'] = 0;
										}
									}
									//Creeo un ciclo for para recorrer la cantidad de reactivos y obtener sus respuestas
									for ($i=1; $i < $total_reactivos ; $i++) {

										echo "
											<tr>
												<td>$i</td>
												<td>$texto_reactivo[$i]</td>";
												$reactivo="r0".$i;
												//Verifico si el nivel de acceso que se tiene es de coordinador
												$orden_sql = "SELECT $reactivo FROM lic_profesores_por_alumnos";
												// Ejecuta la consulta SQL
												$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
												if ($resultado_busqueda) {
													if (mysqli_num_rows($resultado_busqueda) > 0) {
														// Si hubo resultado obtiene los datos de la sesión y activa la bandera
														while($registro = mysqli_fetch_array($resultado_busqueda)) {
															switch ($registro[$reactivo]) {
																case '1':
																	$valor_reactivo[$reactivo][1]['sumatoria_valor']=$valor_reactivo[$reactivo][1]['sumatoria_valor']+$registro[$reactivo];
																	$valor_reactivo[$reactivo][1]['cuenta_valor']=$valor_reactivo[$reactivo][1]['cuenta_valor']+1;
																	break;
																case '2':
																	$valor_reactivo[$reactivo][2]['sumatoria_valor']=$valor_reactivo[$reactivo][2]['sumatoria_valor']+$registro[$reactivo];
																	$valor_reactivo[$reactivo][2]['cuenta_valor']=$valor_reactivo[$reactivo][2]['cuenta_valor']+1;
																	break;
																case '3':
																	$valor_reactivo[$reactivo][3]['sumatoria_valor']=$valor_reactivo[$reactivo][3]['sumatoria_valor']+$registro[$reactivo];
																	$valor_reactivo[$reactivo][3]['cuenta_valor']=$valor_reactivo[$reactivo][3]['cuenta_valor']+1;
																	break;
																case '4':
																	$valor_reactivo[$reactivo][4]['sumatoria_valor']=$valor_reactivo[$reactivo][4]['sumatoria_valor']+$registro[$reactivo];
																	$valor_reactivo[$reactivo][4]['cuenta_valor']=$valor_reactivo[$reactivo][4]['cuenta_valor']+1;
																	break;
																case '5':
																	$valor_reactivo[$reactivo][5]['sumatoria_valor']=$valor_reactivo[$reactivo][5]['sumatoria_valor']+$registro[$reactivo];
																	$valor_reactivo[$reactivo][5]['cuenta_valor']=$valor_reactivo[$reactivo][5]['cuenta_valor']+1;
																	break;
															}
														}
													}
													// Libera el conjunto de resultados
													mysqli_free_result($resultado_busqueda);
												}
												//Creeo un ciclo para revisar todos los datos
												$sumatoria = 0; // inicializa antes de usar
												for ($j=1; $j <6 ; $j++) {
													if (!isset($valor_reactivo[$reactivo][$j]['cuenta_valor']) || $valor_reactivo[$reactivo][$j]['cuenta_valor']<1) {
														echo "<td>0</td>";
													}else{
														echo "<td>".$valor_reactivo[$reactivo][$j]['cuenta_valor']."</td>";
													}
													$sumatoria += isset($valor_reactivo[$reactivo][$j]['sumatoria_valor']) ? $valor_reactivo[$reactivo][$j]['sumatoria_valor'] : 0;
												}
												echo "<td>".(round($sumatoria/$maxima, 4)*100)." %</td>\n";
											echo "</tr>";
										$sumatoria=0;
									}
									$suma_total = array();
									$sumatoria_general = 0;
									//Se comienza a realizar el ciclo for final que va a recorrer todos los reactivos
									for ($j=1; $j < 6 ; $j++) {
										// inicializar el acumulador para evitar warnings de clave indefinida
										$suma_total[$j] = 0;
										for ($i=1; $i < $total_reactivos ; $i++) {
											$reactivo='r0'.$i;
											$suma_total[$j] += isset($valor_reactivo[$reactivo][$j]['cuenta_valor']) ? $valor_reactivo[$reactivo][$j]['cuenta_valor'] : 0;
											$sumatoria_general += isset($valor_reactivo[$reactivo][$j]['sumatoria_valor']) ? $valor_reactivo[$reactivo][$j]['sumatoria_valor'] : 0;
										}
									}
									echo "
										<script>$('#porcentaje_final_alumnos').html('".(round($sumatoria_general/$maxima_general, 4)*100)." %');</script>
									";
								?>
								</tbody>
							</table>
                        
						<?php
								?>
						</div>
					<!-- Autoevaluación docente -->
						<div class="seccion"><span>2</span>Autoevaluación docente</div>
						<div class="contenedor-interno">
							<table class="table table-bordered table-striped table-sm" id="resultados_profesores_por_profesores">
								<thead>
									<tr>
										<th class="fondo-marista">#</th>
										<th class="fondo-marista">Reactivo</th>
										<th class="fondo-marista">1</th>
										<th class="fondo-marista">2</th>
										<th class="fondo-marista">3</th>
										<th class="fondo-marista">4</th>
										<th class="fondo-marista">5</th>
										<th class="fondo-marista">% de efectividad</th>
									</tr>
								</thead>
								<tbody>
									<?php
										//Incluyo el archivo que tiene los reactivos (autoevaluación)
										include_once 'lic_profesores_por_profesores_texto.php';
										// Reinicializar acumuladores para la sección de profesores
										$valor_reactivo = array();
										for ($k = 1; $k < $total_reactivos-1; $k++) {
											if($k>9){
												$r = 'r'.$k;
											}else{
												$r = 'r0'.$k;
											}
											for ($m = 1; $m <= 5; $m++) {
												$valor_reactivo[$r][$m]['sumatoria_valor'] = 0;
												$valor_reactivo[$r][$m]['cuenta_valor'] = 0;
											}
										}
										$sumatoria = 0;
										$suma_total = array();
										//Creeo un ciclo for para recorrer la cantidad de reactivos y obtener sus respuestas
										for ($i=1; $i < $total_reactivos-1 ; $i++) {
											echo "
												<tr>
													<td>$i</td>
													<td>$texto_reactivo[$i]</td>";
													if($i>9){
														$reactivo = 'r'.$i;
													}else{
														$reactivo = 'r0'.$i;
													}
													//Verifico si el nivel de acceso que se tiene es de coordinador
													$orden_sql = "SELECT $reactivo FROM lic_profesores_por_profesores";
													// Ejecuta la consulta SQL
													$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
													if ($resultado_busqueda) {
														if (mysqli_num_rows($resultado_busqueda) > 0) {
															// Si hubo resultado obtiene los datos de la sesión y activa la bandera
															while($registro = mysqli_fetch_array($resultado_busqueda)) {
																switch ($registro[$reactivo]) {
																	case '1':
																		$valor_reactivo[$reactivo][1]['sumatoria_valor']=$valor_reactivo[$reactivo][1]['sumatoria_valor']+$registro[$reactivo];
																		$valor_reactivo[$reactivo][1]['cuenta_valor']=$valor_reactivo[$reactivo][1]['cuenta_valor']+1;
																		break;
																	case '2':
																		$valor_reactivo[$reactivo][2]['sumatoria_valor']=$valor_reactivo[$reactivo][2]['sumatoria_valor']+$registro[$reactivo];
																		$valor_reactivo[$reactivo][2]['cuenta_valor']=$valor_reactivo[$reactivo][2]['cuenta_valor']+1;
																		break;
																	case '3':
																		$valor_reactivo[$reactivo][3]['sumatoria_valor']=$valor_reactivo[$reactivo][3]['sumatoria_valor']+$registro[$reactivo];
																		$valor_reactivo[$reactivo][3]['cuenta_valor']=$valor_reactivo[$reactivo][3]['cuenta_valor']+1;
																		break;
																	case '4':
																		$valor_reactivo[$reactivo][4]['sumatoria_valor']=$valor_reactivo[$reactivo][4]['sumatoria_valor']+$registro[$reactivo];
																		$valor_reactivo[$reactivo][4]['cuenta_valor']=$valor_reactivo[$reactivo][4]['cuenta_valor']+1;
																		break;
																	case '5':
																		$valor_reactivo[$reactivo][5]['sumatoria_valor']=$valor_reactivo[$reactivo][5]['sumatoria_valor']+$registro[$reactivo];
																		$valor_reactivo[$reactivo][5]['cuenta_valor']=$valor_reactivo[$reactivo][5]['cuenta_valor']+1;
																		break;
																}
															}
														}
														// Libera el conjunto de resultados
														mysqli_free_result($resultado_busqueda);
													}
													//Creeo un ciclo para revisar todos los datos
													for ($j=1; $j <6 ; $j++) {
														if ($valor_reactivo[$reactivo][$j]['cuenta_valor']<1) {
															echo "<td>0</td>";
														}else{
															echo "<td>".$valor_reactivo[$reactivo][$j]['cuenta_valor']."</td>";
														}
														$sumatoria=$sumatoria+$valor_reactivo[$reactivo][$j]['sumatoria_valor'];
													}
													echo "<td>".(round($sumatoria/$maxima_profesores, 4)*100)." %</td>\n";
												echo "</tr>";
											$sumatoria=0;
										}
										// Calcular sumas y totales usando el rango real de reactivos impresos
										$suma_total = array();
										$sumatoria_general = 0;
										// Después del bucle for anterior, $i contiene el siguiente índice; el último reactivo impreso fue $i-1
										$prof_first = 1;
										$prof_last = isset($i) ? ($i - 1) : ($total_reactivos - 2);
										if ($prof_last < $prof_first) { $prof_last = $prof_first; }
										$num_reactivos_profesores = $prof_last - $prof_first + 1;
										for ($j=1; $j < 6 ; $j++) {
											// inicializar el acumulador para evitar warnings de clave indefinida
											$suma_total[$j] = 0;
											for ($ii=$prof_first; $ii <= $prof_last ; $ii++) {
												// Formato del nombre del reactivo (r0X o rX para >9)
												if ($ii > 9) { $reactivo = 'r'.$ii; } else { $reactivo = 'r0'.$ii; }
												$suma_total[$j] += isset($valor_reactivo[$reactivo][$j]['cuenta_valor']) ? $valor_reactivo[$reactivo][$j]['cuenta_valor'] : 0;
												$sumatoria_general += isset($valor_reactivo[$reactivo][$j]['sumatoria_valor']) ? $valor_reactivo[$reactivo][$j]['sumatoria_valor'] : 0;
											}
										}
										// Calcular la máxima posible total en función del número real de reactivos
										$maxima_general_profesores = 5 * $total_profesores_evaluaron * $num_reactivos_profesores;
										echo "
											<script>$('#porcentaje_final_profesores').html('".(($maxima_general_profesores>0) ? (round($sumatoria_general/$maxima_general_profesores, 4)*100) : 0)." %');</script>
										";
									?>
								</tbody>
							</table>
						</div>
					<!-- Comentarios de alumnos -->
						<div class="seccion"><span>3</span>Comentarios de alumnos</div>
						<div class="contenedor-interno">
							<table class="table table-bordered table-striped table-sm" id="comentarios_alumnos">
								<thead>
									<tr><th class="fondo-marista">Comentarios</th></tr>
								</thead>
								<tbody>
									<?php
										$orden_sql = "SELECT CONCAT('<strong>',nombre,' - ',materia,'</strong> - ', r06 ) AS r06 FROM lic_profesores_por_alumnos WHERE r06<>''";
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
								</tbody>
							</table>
						</div>
					<!-- FORTALEZAS SEGUN EL DOCENTE -->
						<div class="seccion"><span>4</span>FORTALEZAS SEGUN EL DOCENTE</div>
						<div class="contenedor-interno">
							<table class="table table-bordered table-striped table-sm" id="comentarios_fortalezas">
								<thead>
									<tr><th class="fondo-marista">Comentarios</th></tr>
								</thead>
								<tbody>
									<?php
										$orden_sql = "SELECT CONCAT('<strong>',nombre,' - ',materia,'</strong> - ', r11 ) AS r11 FROM lic_profesores_por_profesores WHERE r11<>''";
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
								</tbody>
							</table>
						</div>
					<!-- AREAS DE MEJORA SEGUN EL DOCENTE -->
						<div class="seccion"><span>5</span>AREAS DE MEJORA SEGUN EL DOCENTE</div>
						<div class="contenedor-interno">
							<table class="table table-bordered table-striped table-sm" id="comentarios_mejoras">
								<thead>
									<tr><th class="fondo-marista">Comentarios</th></tr>
								</thead>
								<tbody>
									<?php
										$orden_sql = "SELECT CONCAT('<strong>',nombre,' - ',materia,'</strong> - ', r12 ) AS r12 FROM lic_profesores_por_profesores WHERE r12<>''";
										// Ejecuta la consulta SQL
										$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
										if ($resultado_busqueda) {
											if (mysqli_num_rows($resultado_busqueda) > 0) {
												// Si hubo resultado obtiene los datos de la sesión y activa la bandera
												while($registro = mysqli_fetch_array($resultado_busqueda)) {
													echo "<tr><td>".$registro['r12']."</td></tr>";
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
	</body>
	<?php mysqli_close($base_de_datos); ?>
	<script type="text/javascript">
		function imprime_pagina() { window.print(); }
	</script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/cr-1.5.6/date-1.1.2/fc-4.1.0/fh-3.2.4/kt-2.7.0/r-2.3.0/rg-1.2.0/rr-1.2.8/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/sr-1.1.1/datatables.min.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/cr-1.5.6/date-1.1.2/fc-4.1.0/fh-3.2.4/kt-2.7.0/r-2.3.0/rg-1.2.0/rr-1.2.8/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/sr-1.1.1/datatables.min.js"></script>
<script type="text/javascript">
	(function(){
		// Opciones comunes para las tablas
		var commonOptions = {
			responsive: true,
			// Usar objeto de idioma inline para evitar peticiones XHR a CDN y problemas de CORS
			language: {
				"sProcessing":     "Procesando...",
				"sLengthMenu":     "Mostrar _MENU_ registros",
				"sZeroRecords":    "No se encontraron resultados",
				"sEmptyTable":     "Ningún dato disponible en esta tabla",
				"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
				"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
				"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
				"sSearch":         "Buscar:",
				"oPaginate": {
					"sFirst":    "Primero",
					"sLast":     "Último",
					"sNext":     "Siguiente",
					"sPrevious": "Anterior"
				},
				"oAria": {
					"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				}
			},
			info: true,
				// Mostrar más de 10 filas por página: ajustar pageLength y lengthMenu
				pageLength: 25,
				lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
			pagingType: "full_numbers",
			dom: 'Bfrtip',
			buttons: {
				buttons: [
					{ extend: 'excelHtml5', text: 'DESCARGAR EXCEL', orientation: 'landscape' },
					{ extend: 'print', text: 'IMPRIMIR' },
					{ extend: 'copy', text: 'COPIAR' }
				]
			}
		};

		// Intentar inicializar con reintentos si jQuery/DataTables aún no están cargados
		function attemptInit(selector, opts, triesLeft, delayMs) {
			triesLeft = (typeof triesLeft === 'number') ? triesLeft : 10;
			delayMs = (typeof delayMs === 'number') ? delayMs : 300;

			if (typeof jQuery === 'undefined') {
				if (triesLeft <= 0) {
					console.error('jQuery no está disponible. DataTables no se inicializará para', selector);
					return;
				}
				return setTimeout(function(){ attemptInit(selector, opts, triesLeft-1, delayMs); }, delayMs);
			}
			var $ = jQuery;
			if (!$.fn || !$.fn.DataTable) {
				if (triesLeft <= 0) {
					console.error('DataTables no está cargado. Asegúrate de incluir datatables.min.js. Selector:', selector);
					return;
				}
				return setTimeout(function(){ attemptInit(selector, opts, triesLeft-1, delayMs); }, delayMs);
			}

			var $table = $(selector);
			if ($table.length === 0) {
				console.warn('Tabla no encontrada:', selector);
				return;
			}

			// Verificar consistencia: número de cabeceras vs número de celdas en cada fila
			var headerCount = 0;
			var $thead = $table.find('thead');
			if ($thead.length) {
				headerCount = $thead.find('th').length;
			} else {
				// si no hay thead, tomar la primera fila como cabecera
				headerCount = $table.find('tr').first().find('th,td').length;
			}
			if (headerCount === 0) {
				console.error('No se encontraron cabeceras para la tabla', selector);
				return;
			}

			var inconsistent = false;
			$table.find('tbody tr').each(function(idx, tr){
				var tdCount = $(tr).find('td').length;
				if (tdCount !== headerCount) {
					console.error('Fila con columnas inconsistentes en', selector, 'fila', idx+1, 'esperado', headerCount, 'encontrado', tdCount);
					inconsistent = true;
					return false; // romper each
				}
			});
			if (inconsistent) {
				console.error('Se detectaron filas con diferente número de celdas que las cabeceras. No se inicializará', selector);
				return;
			}
			// Evitar re-inicializar si ya está inicializada
			if ($.fn.dataTable.isDataTable($table)) {
				return;
			}

			try {
				var table = $table.DataTable(opts);
				table.on('responsive-resize', function (e, datatable, columns) {
					var count = columns.reduce(function (a,b) { return b === false ? a+1 : a; }, 0 );
					console.log(count + ' column(s) are hidden for ' + selector);
				});
			} catch (err) {
				console.error('Error inicializando DataTable para', selector, err);
			}
		}

		// Inicializar tablas objetivo al cargar DOM
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', function(){
				attemptInit('#resultados_profesores_por_profesores', commonOptions, 10, 300);
				attemptInit('#resultados_profesores_por_alumnos', commonOptions, 10, 300);
				attemptInit('#comentarios_alumnos', commonOptions, 10, 300);
				attemptInit('#comentarios_fortalezas', commonOptions, 10, 300);
				attemptInit('#comentarios_mejoras', commonOptions, 10, 300);
			});
		} else {
			attemptInit('#resultados_profesores_por_alumnos', commonOptions, 10, 300);
			attemptInit('#resultados_profesores_por_profesores', commonOptions, 10, 300);
			attemptInit('#comentarios_alumnos', commonOptions, 10, 300);
			attemptInit('#comentarios_fortalezas', commonOptions, 10, 300);
			attemptInit('#comentarios_mejoras', commonOptions, 10, 300);
		}
	})();
</script>
</html>