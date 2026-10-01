<?php
	session_start();
	require_once 'lib/config.php';
	$parametros_validos = false;
	unset($parametros);
	$string_parametros = desencriptar($_GET['parametros']);
	if ((substr($string_parametros,0,2)=="%%") AND (substr($string_parametros,(strlen($string_parametros)-2),2)=="%%")) {
		$string_parametros = substr($string_parametros,2,strlen($string_parametros)-4);
		$parametros_validos = true;
		if(strpos($string_parametros,"¬")===FALSE) {
			$parametros[] = $string_parametros;
		} else {
			do {
				if(substr($string_parametros,0,strpos($string_parametros,"¬"))=="--SinProfesor--") $parametros[] = "--SinCarrera--";
				$parametros[] = substr($string_parametros,0,strpos($string_parametros,"¬"));
				$string_parametros = substr($string_parametros,strpos($string_parametros,"¬")+2);
			} while (!(strpos($string_parametros,"¬")===FALSE));
			$parametros[] = $string_parametros;
		}
	}
	if(!$parametros_validos) {
		header('Location: index.php');
		exit;
	}
	$post_carrera = $parametros[1];
	$post_profesor = $parametros[2]."¬".$parametros[3];
	$post_materia = $parametros[4];
	$post_desglosar = $parametros[5];
	$carrera_profesor = $parametros[2];
	$nombre_profesor = $parametros[3];
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
	if ($_SESSION['zez_a_sexo']=="FEMENINO") { $AuO = "a"; } else { $AuO = "o"; }
?>
<html>
	<?php include_once HEADER; ?>
	<script type="text/javascript" src="lib/functions.js"></script>

	<body>
		<div class="container">
			<div class="card">
				<div class="card-header">
					<h1>
						<img src="lib/img/blanco.png" class="img-form-left" />
						Reporte de Profesores
					</h1>
					<span>Ciclo <?php echo $ciclo['LICENCIATURA']; ?></span>
				</div>
				<div class="card-body">
					<?php
						$calificacion_general = 0;
						if ((('CARRERA'==$parametros[0]) and ('DEPORTE Y CULTURA'!=$post_carrera)) or (('PROFESOR'==$parametros[0]) and ('DEPORTE Y CULTURA'!=$carrera_profesor)) or ('TODOS'==$parametros[0])) {
							//#################################################################################################################################################################
							// ########### INICIO DE BLOQUE INICIALIZAR VARIABLES
							unset($tamano_set_valores);
							unset($set_valores);
							unset($secciones);
							unset($numero_pregunta);
							unset($numero_comentario);
							unset($texto_pregunta);	
							unset($respuestas);
							include_once 'lic_profesores_general.php';
							for ($contador_items=1; $contador_items<=$total_reactivos; $contador_items++) {
								$numero_pregunta[$contador_items] = "r" . str_pad($contador_items, 2, "0", STR_PAD_LEFT);
								$numero_comentario[$contador_items] = $numero_pregunta[$contador_items] . "c";
								$fondo_comentario[$contador_items] = "";
							}
							$usar_contador_interno = false;
							for ($contador_secciones=1;$contador_secciones<=$total_secciones;$contador_secciones++) {
								for ($contador_subsecciones=0; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
									if ($secciones[$contador_secciones][$contador_subsecciones]['limitada']!="") $usar_contador_interno = true;
								}
							}
							// ============== INICIA CONSULTA DE RESULTADOS
							$contador_competencias = 0;
							unset($resultados_competencias);
							unset($resultados_items);
							unset($listado_competencias);
							$competencia_actual = "qetuoadgjlzcbmwryipsfhkñxvn";
							for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
								for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
									if("CERRADA"==$secciones[$contador_secciones][$contador_subsecciones]['tipo']) {
										for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
											if ($competencia_actual!=$reactivo[$contador_items]['competencia']) {
												$listado_competencias[] = $reactivo[$contador_items]['competencia'];
												$competencia_actual = $reactivo[$contador_items]['competencia'];
												$resultados_competencias[$competencia_actual]['inicia'] = $contador_items;
												$resultados_competencias[$competencia_actual]['termina'] = $contador_items;
												$resultados_competencias[$competencia_actual]['conteo_ponderado'] = 1;
												$resultados_competencias[$competencia_actual]['conteo_alumnos'] = 0;
												$resultados_competencias[$competencia_actual]['conteo_coordinadores'] = 0;
												$resultados_competencias[$competencia_actual]['conteo_autoevaluacion'] = 0;
											} else {
												$resultados_competencias[$competencia_actual]['termina'] = $contador_items;
												$resultados_competencias[$competencia_actual]['conteo_ponderado']++;
											}
											// Obtiene los resultados de alumnos
											if (0!=$reactivo[$contador_items]['alumnos']) {
												$resultados_competencias[$competencia_actual]['conteo_alumnos']++;
												$resultados_items[$contador_items]['alumnos'] = 0;
												$orden_sql2 = "SELECT AVG(" . $numero_pregunta[$reactivo[$contador_items]['alumnos']] . ") ";
												$orden_sql2 .= "AS promedio ";
												$orden_sql2 .= "FROM `lic_profesores_por_alumnos` ";
												$orden_sql2 .= "WHERE ";
												if ("TODOS" == $parametros[0]) {
													// Reporte General
													$orden_sql2 .= "1";
												} elseif ("CARRERA" == $parametros[0]) {
													// Reporte por carrera
													if ('MATERIAS INSTITUCIONALES'==$post_carrera) {
														if('0'!=$_SESSION['zez_a_sello']) {
															$orden_sql2 .= "`sello`='" . $_SESSION['zez_a_sello'] . "'";
														} else {
															$orden_sql2 .= "`sello`<>'0'";
														}
													} else {
														$orden_sql2 .= "`carrera`='$post_carrera'";
													}
												} else {
													// Reporte por profesor
													$orden_sql2 .= "`nombre`='$nombre_profesor'";
													if ('MATERIAS INSTITUCIONALES'==$carrera_profesor) {
														if (('0'!=$_SESSION['zez_a_sello']) AND ('MATERIAS.INSTITUCIONALES'!=$_SESSION['zez_a_matricula'])) {
															$orden_sql2 .= " AND `sello`='" . $_SESSION['zez_a_sello'] . "'";
														} else {
															$orden_sql2 .= " AND `sello`<>'0'";
														}
													} else {
														if ('TODOS'!=$carrera_profesor) $orden_sql2 .= " AND `carrera`='$carrera_profesor'";
													}
												}
												$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
												if ($resultado_busqueda2) {
													if (mysqli_num_rows($resultado_busqueda2) > 0) {
														// Si hubo resultado genera la tabla
														while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
															$resultados_items[$contador_items]['alumnos'] = round($registro2['promedio'] * $multiplicador, 2);
														}
													}
													// Libera el conjunto de resultados
													mysqli_free_result($resultado_busqueda2);
												}
											}
											// Obtiene los resultados de coordinadores
											if (0!=$reactivo[$contador_items]['coordinadores']) {
												$resultados_competencias[$competencia_actual]['conteo_coordinadores']++;
												$resultados_items[$contador_items]['coordinadores'] = 0;
												$orden_sql2 = "SELECT AVG(" . $numero_pregunta[$reactivo[$contador_items]['coordinadores']] . ") ";
												$orden_sql2 .= "AS promedio ";
												$orden_sql2 .= "FROM `lic_profesores_por_coordinadores` ";
												$orden_sql2 .= "WHERE ";
												if ("TODOS" == $parametros[0]) {
													// Reporte General
													$orden_sql2 .= "1";
												} elseif ("CARRERA" == $parametros[0]) {
													// Reporte por carrera
													$orden_sql2 .= "`carrera` LIKE '%$post_carrera%'";
													if('0'!=$_SESSION['zez_a_sello']) $orden_sql2 .= " AND `sello`='" . $_SESSION['zez_a_sello'] . "'";
												} else {
													// Reporte por profesor
													$orden_sql2 .= "`nombre`='$nombre_profesor'";
													if ('TODOS'!=$carrera_profesor) $orden_sql2 .= " AND `carrera` LIKE '%$carrera_profesor%'";
													if (('0'!=$_SESSION['zez_a_sello']) AND ('MATERIAS.INSTITUCIONALES'!=$_SESSION['zez_a_matricula'])) $orden_sql2 .= " AND `sello`='" . $_SESSION['zez_a_sello'] . "'";
												}
												$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
												if ($resultado_busqueda2) {
													if (mysqli_num_rows($resultado_busqueda2) > 0) {
														// Si hubo resultado genera la tabla
														while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
															$resultados_items[$contador_items]['coordinadores'] = round($registro2['promedio'] * $multiplicador, 2);
														}
													}
													// Libera el conjunto de resultados
													mysqli_free_result($resultado_busqueda2);
												}
											}
											// Obtiene los resultados de autoevaluaciones
											if (0!=$reactivo[$contador_items]['autoevaluacion']) {
												$resultados_competencias[$competencia_actual]['conteo_autoevaluacion']++;
												$resultados_items[$contador_items]['autoevaluacion'] = 0;
												$orden_sql2 = "SELECT AVG(" . $numero_pregunta[$reactivo[$contador_items]['autoevaluacion']] . ") ";
												$orden_sql2 .= "AS promedio ";
												$orden_sql2 .= "FROM `lic_profesores_por_profesores` ";
												$orden_sql2 .= "WHERE ";
												if ("TODOS" == $parametros[0]) {
													// Reporte General
													$orden_sql2 .= "1";
												} elseif ("CARRERA" == $parametros[0]) {
													// Reporte por carrera
													$orden_sql2 .= "`carrera` LIKE '%$post_carrera%'";
												} else {
													// Reporte por profesor
													$orden_sql2 .= "`nombre`='$nombre_profesor'";
													if ('TODOS'!=$carrera_profesor) $orden_sql2 .= " AND `carrera` LIKE '%$carrera_profesor%'";
												}
												$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
												if ($resultado_busqueda2) {
													if (mysqli_num_rows($resultado_busqueda2) > 0) {
														// Si hubo resultado genera la tabla
														while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
															$resultados_items[$contador_items]['autoevaluacion'] = round($registro2['promedio'] * $multiplicador, 2);
														}
													}
													// Libera el conjunto de resultados
													mysqli_free_result($resultado_busqueda2);
												}
											}
										}
									} elseif("ABIERTA"==$secciones[$contador_secciones][$contador_subsecciones]['tipo']) {
										for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
											// Obtiene los resultados de alumnos
											if (0!=$reactivo[$contador_items]['alumnos']) {
												$resultados_items[$contador_items]['alumnos'] = "";
												$orden_sql2 = "SELECT " . $numero_pregunta[$reactivo[$contador_items]['alumnos']] . " ";
												$orden_sql2 .= "FROM `lic_profesores_por_alumnos` ";
												$orden_sql2 .= "WHERE " . $numero_pregunta[$reactivo[$contador_items]['alumnos']] . " != ''";
												if ("TODOS" == $parametros[0]) {
													// Reporte General
												} elseif ("CARRERA" == $parametros[0]) {
													// Reporte por carrera
													$orden_sql2 .= " AND `carrera`='$post_carrera'";
													if('0'!=$_SESSION['zez_a_sello']) $orden_sql2 .= " AND `sello`='" . $_SESSION['zez_a_sello'] . "'";
												} else {
													// Reporte por profesor
													$orden_sql2 .= " AND `nombre`='$nombre_profesor'";
													if ('TODOS'!=$carrera_profesor) $orden_sql2 .= " AND `carrera`='$carrera_profesor'";
													if ('0'!=$_SESSION['zez_a_sello']) $orden_sql2 .= " AND `sello`='" . $_SESSION['zez_a_sello'] . "'";
												}
												$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
												if ($resultado_busqueda2) {
													if (mysqli_num_rows($resultado_busqueda2) > 0) {
														// Si hubo resultado genera la tabla
														while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
															$resultados_items[$contador_items]['alumnos'] .= "<li>" . $registro2[$numero_pregunta[$reactivo[$contador_items]['alumnos']]] . "</li>";
														}
													}
													// Libera el conjunto de resultados
													mysqli_free_result($resultado_busqueda2);
												}
												if (""!=$resultados_items[$contador_items]['alumnos']) $resultados_items[$contador_items]['alumnos'] = "<p style='font-weight:bold;'>Alumnos</p><ol>" . $resultados_items[$contador_items]['alumnos'] . "</ol>";
											}
											// Obtiene los resultados de coordinadores
											if (0!=$reactivo[$contador_items]['coordinadores']) {
												$resultados_items[$contador_items]['coordinadores'] = "";
												$orden_sql2 = "SELECT " . $numero_pregunta[$reactivo[$contador_items]['coordinadores']] . " ";
												$orden_sql2 .= "FROM `lic_profesores_por_coordinadores` ";
												$orden_sql2 .= "WHERE " . $numero_pregunta[$reactivo[$contador_items]['coordinadores']] . " != '' ";
												if ("TODOS" == $parametros[0]) {
													// Reporte General
												} elseif ("CARRERA" == $parametros[0]) {
													// Reporte por carrera
													$orden_sql2 .= " AND `carrera` LIKE '%$post_carrera%'";
													if('0'!=$_SESSION['zez_a_sello']) $orden_sql2 .= " AND `sello`='" . $_SESSION['zez_a_sello'] . "'";
												} else {
													// Reporte por profesor
													$orden_sql2 .= " AND `nombre`='$nombre_profesor'";
													if ('TODOS'!=$carrera_profesor) $orden_sql2 .= " AND `carrera` LIKE '%$carrera_profesor%'";
													if ('0'!=$_SESSION['zez_a_sello']) $orden_sql2 .= " AND `sello`='" . $_SESSION['zez_a_sello'] . "'";
												}
												$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
												if ($resultado_busqueda2) {
													if (mysqli_num_rows($resultado_busqueda2) > 0) {
														// Si hubo resultado genera la tabla
														while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
															$resultados_items[$contador_items]['coordinadores'] .= "<li>" . $registro2[$numero_pregunta[$reactivo[$contador_items]['coordinadores']]] . "</li>";
														}
													}
													// Libera el conjunto de resultados
													mysqli_free_result($resultado_busqueda2);
												}
												if (""!=$resultados_items[$contador_items]['coordinadores']) $resultados_items[$contador_items]['coordinadores'] = "<p style='font-weight:bold;'>Coordinador</p><ol>" . $resultados_items[$contador_items]['coordinadores'] . "</ol>";
											}
											// Obtiene los resultados de autoevaluacion
											if (0!=$reactivo[$contador_items]['autoevaluacion']) {
												$resultados_items[$contador_items]['autoevaluacion'] = "";
												$orden_sql2 = "SELECT " . $numero_pregunta[$reactivo[$contador_items]['autoevaluacion']] . " ";
												$orden_sql2 .= "FROM `lic_profesores_por_profesores` ";
												$orden_sql2 .= "WHERE " . $numero_pregunta[$reactivo[$contador_items]['autoevaluacion']] . " != '' ";
												if ("TODOS" == $parametros[0]) {
													// Reporte General
												} elseif ("CARRERA" == $parametros[0]) {
													// Reporte por carrera
													$orden_sql2 .= " AND `carrera` LIKE '%$post_carrera%'";
												} else {
													// Reporte por profesor
													$orden_sql2 .= " AND `nombre`='$nombre_profesor'";
													if ('TODOS'!=$carrera_profesor) $orden_sql2 .= " AND `carrera` LIKE '%$carrera_profesor%'";
												}
												$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
												if ($resultado_busqueda2) {
													if (mysqli_num_rows($resultado_busqueda2) > 0) {
														// Si hubo resultado genera la tabla
														while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
															$resultados_items[$contador_items]['autoevaluacion'] .= "<li>" . $registro2[$numero_pregunta[$reactivo[$contador_items]['autoevaluacion']]] . "</li>";
														}
													}
													// Libera el conjunto de resultados
													mysqli_free_result($resultado_busqueda2);
												}
												if (""!=$resultados_items[$contador_items]['autoevaluacion']) $resultados_items[$contador_items]['autoevaluacion'] = "<p style='font-weight:bold;'>Autoevaluación</p><ol>" . $resultados_items[$contador_items]['autoevaluacion'] . "</ol>";
											}
										}
									}
								}
							}
							// Obtiene las ponderaciones de los items
							for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
								for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
									if("CERRADA"==$secciones[$contador_secciones][$contador_subsecciones]['tipo']) {
										for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
											$resultados_items[$contador_items]['ponderado'] = 0;
											$divisor_ponderaciones = 0;
											// Obtiene la parte de alumnos
											if (0!=$reactivo[$contador_items]['alumnos']) {
												$resultados_items[$contador_items]['ponderado'] += ($resultados_items[$contador_items]['alumnos'] * $ponderaciones['alumnos']);
												$divisor_ponderaciones += $ponderaciones['alumnos'];
											}
											// Obtiene la parte de coordinadores
											if (0!=$reactivo[$contador_items]['coordinadores']) {
												$resultados_items[$contador_items]['ponderado'] += ($resultados_items[$contador_items]['coordinadores'] * $ponderaciones['coordinadores']);
												$divisor_ponderaciones += $ponderaciones['coordinadores'];
											}
											// Obtiene la parte de autoevaluacion
											if (0!=$reactivo[$contador_items]['autoevaluacion']) {
												$resultados_items[$contador_items]['ponderado'] += ($resultados_items[$contador_items]['autoevaluacion'] * $ponderaciones['autoevaluacion']);
												$divisor_ponderaciones += $ponderaciones['autoevaluacion'];
											}
											// Obtiene las ponderaciones por item
											$resultados_items[$contador_items]['ponderado'] = round($resultados_items[$contador_items]['ponderado'] / $divisor_ponderaciones, 2);
										}
									}
								}
							}
							// Obtiene las ponderaciones de las competencias
							foreach ($listado_competencias as $competencia_actual) {
								$contador_competencias++;
								if (0!=$resultados_competencias[$competencia_actual]['conteo_alumnos']) {
									$resultados_competencias[$competencia_actual]['alumnos'] = 0;
									for ($contador_items=$resultados_competencias[$competencia_actual]['inicia']; $contador_items<=$resultados_competencias[$competencia_actual]['termina']; $contador_items++) {
										if (isset($resultados_items[$contador_items]['alumnos'])) $resultados_competencias[$competencia_actual]['alumnos'] += $resultados_items[$contador_items]['alumnos'];
									}
									$resultados_competencias[$competencia_actual]['alumnos'] = round($resultados_competencias[$competencia_actual]['alumnos'] / $resultados_competencias[$competencia_actual]['conteo_alumnos'], 2);
								}
								if (0!=$resultados_competencias[$competencia_actual]['conteo_coordinadores']) {
									$resultados_competencias[$competencia_actual]['coordinadores'] = 0;
									for ($contador_items=$resultados_competencias[$competencia_actual]['inicia']; $contador_items<=$resultados_competencias[$competencia_actual]['termina']; $contador_items++) {
										if(isset($resultados_items[$contador_items]['coordinadores'])) $resultados_competencias[$competencia_actual]['coordinadores'] += $resultados_items[$contador_items]['coordinadores'];
									}
									$resultados_competencias[$competencia_actual]['coordinadores'] = round($resultados_competencias[$competencia_actual]['coordinadores'] / $resultados_competencias[$competencia_actual]['conteo_coordinadores'], 2);
								}
								if (0!=$resultados_competencias[$competencia_actual]['conteo_autoevaluacion']) {
									$resultados_competencias[$competencia_actual]['autoevaluacion'] = 0;
									for ($contador_items=$resultados_competencias[$competencia_actual]['inicia']; $contador_items<=$resultados_competencias[$competencia_actual]['termina']; $contador_items++) {
										if(isset($resultados_items[$contador_items]['autoevaluacion'])) $resultados_competencias[$competencia_actual]['autoevaluacion'] += $resultados_items[$contador_items]['autoevaluacion'];
									}
									$resultados_competencias[$competencia_actual]['autoevaluacion'] = round($resultados_competencias[$competencia_actual]['autoevaluacion'] / $resultados_competencias[$competencia_actual]['conteo_autoevaluacion'], 2);
								}
								$resultados_competencias[$competencia_actual]['ponderado'] = 0;
								for ($contador_items=$resultados_competencias[$competencia_actual]['inicia']; $contador_items<=$resultados_competencias[$competencia_actual]['termina']; $contador_items++) {
									$resultados_competencias[$competencia_actual]['ponderado'] += $resultados_items[$contador_items]['ponderado'];
								}
								$resultados_competencias[$competencia_actual]['ponderado'] = round($resultados_competencias[$competencia_actual]['ponderado'] / $resultados_competencias[$competencia_actual]['conteo_ponderado'], 2);
								$calificacion_general += $resultados_competencias[$competencia_actual]['ponderado'];
							}
							$calificacion_general = round($calificacion_general / $contador_competencias, 2);
							// ============== TERMINA CONSULTA DE RESULTADOS
							// ########### FIN DE BLOQUE INICIALIZAR VARIABLES
							// ============== PONE EL TEXTO PREVIO A LOS REACTIVOS
							if ($previo['cuerpo'][0]!="") {
								if ($previo['encabezado']!="") {
									if ($previo['numeracion']!="") {
										echo "<div class='seccion'><span>" . preg_replace('/\s/', ' ', $previo['numeracion']) . "</span>" . $previo['encabezado'] . "</div>";
									} else {
										echo "<div class='seccion'>" . preg_replace('/\s/', ' ', $previo['encabezado']) . "</div>";
									}
								}
								echo "<div class='contenedor-interno'>";
								foreach ($previo['cuerpo'] as $renglon) {
									echo "<p>$renglon</p>";
								}
								echo "</div>";
							}
							// ============== INICIO BLOQUE SECCIONES
							$competencia_actual = "qetuoadgjlzcbmwryipsfhkñxvn";
							for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
								if ($secciones[$contador_secciones][0]['texto']!="") {
									if($usar_contador_interno) {
										echo "<div class='seccion'><span>" . a_letras($secciones_visibles) . "</span>" . preg_replace('/\s/', ' ', $secciones[$contador_secciones][0]['texto']) . "</div>";
									} else {
										if ($secciones[$contador_secciones][0]['numeracion']!="") {
											echo "<div class='seccion'><span>" . $secciones[$contador_secciones][0]['numeracion'] . "</span>" . preg_replace('/\s/', ' ', $secciones[$contador_secciones][0]['texto']) . "</div>";
										} else {
											echo "<div class='seccion'>" . preg_replace('/\s/', ' ', $secciones[$contador_secciones][0]['texto']) . "</div>";
										}
									}
								}
								echo "<div class='contenedor-interno'>";
								if (0!=$calificacion_general) {
									// ============== INICIO BLOQUE ENCABEZADO DE TABLA
									echo "
										<table class='table table-bordered table-striped table-sm'>
										<tr>
											<td style='border-top-style:hidden!important;border-left-style:hidden!important;width:80%;text-align:center;' colspan='2'>";
												if ("TODOS" == $parametros[0]) {
													echo "<b>REPORTE GENERAL</b>";
												} elseif ("CARRERA" == $parametros[0]) {
													echo "<b>REPORTE GENERAL DE $post_carrera</b>" ;
												} else {
													if ("TODOS"==$carrera_profesor) {
														echo "<b>PROFESOR:</b> $nombre_profesor";
													} else {
														echo "
															<b>PROFESOR:</b> $nombre_profesor
															<br /><br />
															<b>CARRERA:</b> $carrera_profesor
														";
													}
												}echo "
												<br /><br />
												<b>CALIFICACIÓN GENERAL:</b> " . number_format($calificacion_general,2) . "
											</td>
											<th class='rotacion'>Alumnos</th>
											<th class='rotacion'>Coordinadores</th>
											<th class='rotacion'>Autoevaluación</th>
											<th class='rotacion'>Ponderado</th>
										</tr>
									";
									// ============== FIN BLOQUE ENCABEZADO DE TABLA
									// ============== INICIO BLOQUE SECCIONES INTERNAS
									for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
										// ============== INICIO BLOQUE ITEMS
										for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
											if ($competencia_actual!=$reactivo[$contador_items]['competencia']) {
												$competencia_actual = $reactivo[$contador_items]['competencia'];
												$colspan = ("CERRADA"==$secciones[$contador_secciones][$contador_subsecciones]['tipo']) ? ' colspan="2"' : ' colspan="6"';
												echo "<tr>";
												echo "<td$colspan class='azul' style='font-weight:bold;text-align:left;'>$competencia_actual</td>";
												echo "</td>";
												if ("CERRADA"==$secciones[$contador_secciones][$contador_subsecciones]['tipo']) {
													echo "<td class='azul' style='text-align:center;width:30px;min-width:30px;max-width:30px;font-weight:bold;'>";
													echo "";
													echo (isset($resultados_competencias[$competencia_actual]['alumnos'])) ? number_format($resultados_competencias[$competencia_actual]['alumnos'],2) : "&nbsp;";
													echo "";
													echo "</td>";
													echo "<td class='azul' style='text-align:center;width:30px;min-width:30px;max-width:30px;font-weight:bold;'>";
													echo "";
													echo (isset($resultados_competencias[$competencia_actual]['coordinadores'])) ? number_format($resultados_competencias[$competencia_actual]['coordinadores'],2) : "&nbsp;";
													echo "";
													echo "</td>";
													echo "<td class='azul' style='text-align:center;width:30px;min-width:30px;max-width:30px;font-weight:bold;'>";
													echo "";
													echo (isset($resultados_competencias[$competencia_actual]['autoevaluacion'])) ? number_format($resultados_competencias[$competencia_actual]['autoevaluacion'],2) : "&nbsp;";
													echo "";
													echo "</td>";
													echo "<td class='azul' style='text-align:center;width:30px;min-width:30px;max-width:30px;font-weight:bold;'>";
													echo "";
													echo (isset($resultados_competencias[$competencia_actual]['ponderado'])) ? number_format($resultados_competencias[$competencia_actual]['ponderado'],2) : "&nbsp;";
													echo "";
													echo "</td>";
												}
												echo "</tr>";
											}
											echo "<tr>";
											if ($secciones[$contador_secciones][$contador_subsecciones]['cero']=="") {
												$inicio_contador_respuestas = 1;
												$incremento_respuestas = 0;
											} else {
												$inicio_contador_respuestas = 0;
												$incremento_respuestas = 1;
											}
											$rowspan = ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="CERRADA") ? '' : ' rowspan="2"';
											$colspan = ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="CERRADA") ? '' : ' colspan="5"';
											echo "<td$rowspan class='gris' style='width:30px;min-width:30px;max-width:30px;font-weight:bold;text-align:center;'>";
											if($usar_contador_interno) {
												echo $preguntas_visibles;
											} else {
												echo $contador_items;
											}
											echo "</td>";
											if ("CERRADA"==$secciones[$contador_secciones][$contador_subsecciones]['tipo']) {
												echo "<td$colspan style='width:75%;text-align:left;'>" . preg_replace('/\s/', ' ', $reactivo[$contador_items]['texto']) . "</td>";
												echo "<td style='text-align:center;width:30px;min-width:30px;max-width:30px;'>";
												echo "";
												echo (isset($resultados_items[$contador_items]['alumnos'])) ? number_format($resultados_items[$contador_items]['alumnos'],2) : "&nbsp;";
												echo "";
												echo "</td>";
												echo "<td style='text-align:center;width:30px;min-width:30px;max-width:30px;'>";
												echo "";
												echo (isset($resultados_items[$contador_items]['coordinadores'])) ? number_format($resultados_items[$contador_items]['coordinadores'],2) : "&nbsp;";
												echo "";
												echo "</td>";
												echo "<td style='text-align:center;width:30px;min-width:30px;max-width:30px;'>";
												echo "";
												echo (isset($resultados_items[$contador_items]['autoevaluacion'])) ? number_format($resultados_items[$contador_items]['autoevaluacion'],2) : "&nbsp;";
												echo "";
												echo "</td>";
												echo "<td style='text-align:center;width:30px;min-width:30px;max-width:30px;'>";
												echo "";
												echo (isset($resultados_items[$contador_items]['ponderado'])) ? number_format($resultados_items[$contador_items]['ponderado'],2) : "&nbsp;";
												echo "";
												echo "</td>";
											} else {
												echo "<td$colspan style='text-align:left;'>";
												echo "" . preg_replace('/\s/', ' ', $reactivo[$contador_items]['texto']) . "<br />";
												echo "</td>";
												echo "</tr>";
												echo "<tr>";
												echo "<td$colspan style='text-align:left;'>";
												if (""!=$resultados_items[$contador_items]['alumnos']) echo $resultados_items[$contador_items]['alumnos'];
												if (""!=$resultados_items[$contador_items]['coordinadores']) echo $resultados_items[$contador_items]['coordinadores'];
												if (""!=$resultados_items[$contador_items]['autoevaluacion']) echo $resultados_items[$contador_items]['autoevaluacion'];
												echo "";
												echo "</td>";
											}
											echo "</tr>";
											if ($secciones[$contador_secciones][$contador_subsecciones]['comentario']) {
												echo "<tr>";
												echo "<td" . $fondo_comentario[$contador_items] . " style='text-align:left;'>";
												echo "Comentario sobre esta pregunta...<br />";
												if (isset($_POST['regresada'])) {
													if (strpos($_POST['regresada'], "Larga") !== false) {
														echo "(actualmente el comentario tiene " . mb_strlen(pone_comillas($comentarios[$contador_items]), 'UTF-8') . " caracteres y el máximo permitido es de $maximo_caracteres)";
													} else {
														echo "(máximo $maximo_caracteres caracteres)";
													}
												} else {
													echo "(máximo $maximo_caracteres caracteres)";
												}
												echo "</td>";
												echo "<td colspan='$valores_por_renglon' style='text-align:left;'>";
												echo "<textarea name='" . $numero_comentario[$contador_items] . "'>";
												if (isset($_POST['regresada'])) {
													echo pone_comillas($comentarios[$contador_items]);
												}
												echo "</textarea>";
												echo "</td>";
												echo "</tr>";
											}
										}
										// ============== TERMINA BLOQUE ITEMS
									}
									// ============== TERMINA BLOQUE SECCIONES INTERNAS
									echo "</table>";
									if ((("PROFESOR" == $parametros[0])) AND ('Sí'==$post_desglosar)) {
										// ▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄
										// █                                                                                                   █
										// █                                    INICIA DESGLOSE DE MATERIAS                                    █
										// █                                                                                                   █
										// ▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀
										unset($listado_materias_profesor);
										if ('TODOS'!=$carrera_profesor) {
											$contador_materias = 0;
											if ('MATERIAS INSTITUCIONALES'==$carrera_profesor) {
												$orden_sql2 = "SELECT DISTINCT `materia` ";
												$orden_sql2 .= "FROM `lic_profesores_por_alumnos` ";
												$orden_sql2 .= "WHERE ";
												$orden_sql2 .= "`nombre`='$nombre_profesor'";
												if (('0'!=$_SESSION['zez_a_sello']) AND ('MATERIAS.INSTITUCIONALES'!=$_SESSION['zez_a_matricula'])) {
													$orden_sql2 .= " AND `sello`='" . $_SESSION['zez_a_sello'] . "'";
												} else {
													$orden_sql2 .= " AND `sello`<>'0'";
												}
												$orden_sql2 .= " ORDER BY `carrera`";
											} else {
												$orden_sql2 = "SELECT DISTINCT `materia`, `carrera` ";
												$orden_sql2 .= "FROM `lic_profesores_por_alumnos` ";
												$orden_sql2 .= "WHERE ";
												$orden_sql2 .= "`nombre`='$nombre_profesor'";
												$orden_sql2 .= " AND `carrera`='$carrera_profesor'";
												$orden_sql2 .= " ORDER BY `carrera`, `materia`";
											}
											$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
											if ($resultado_busqueda2) {
												if (mysqli_num_rows($resultado_busqueda2) > 0) {
													// Si hubo resultado genera la tabla
													while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
														$contador_materias++;
														if ('MATERIAS INSTITUCIONALES'!=$carrera_profesor) $listado_materias_profesor[$contador_materias]['carrera'] = $registro2['carrera'];
														$listado_materias_profesor[$contador_materias]['materia'] = $registro2['materia'];
													}
												}
												// Libera el conjunto de resultados
												mysqli_free_result($resultado_busqueda2);
											}
										} else {
											$contador_materias = 0;
											$orden_sql2 = "SELECT DISTINCT `materia`, `carrera` ";
											$orden_sql2 .= "FROM `lic_profesores_por_alumnos` ";
											$orden_sql2 .= "WHERE ";
											$orden_sql2 .= "`nombre`='$nombre_profesor' ";
											$orden_sql2 .= " AND `sello`='0' ";
											$orden_sql2 .= "ORDER BY `carrera`, `materia`";
											$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
											if ($resultado_busqueda2) {
												if (mysqli_num_rows($resultado_busqueda2) > 0) {
													// Si hubo resultado genera la tabla
													while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
														$contador_materias++;
														$listado_materias_profesor[$contador_materias]['carrera'] = $registro2['carrera'];
														$listado_materias_profesor[$contador_materias]['materia'] = $registro2['materia'];
													}
												}
												// Libera el conjunto de resultados
												mysqli_free_result($resultado_busqueda2);
											}
											$orden_sql2 = "SELECT DISTINCT `materia` ";
											$orden_sql2 .= "FROM `lic_profesores_por_alumnos` ";
											$orden_sql2 .= "WHERE ";
											$orden_sql2 .= "`nombre`='$nombre_profesor'";
											if (('0'!=$_SESSION['zez_a_sello']) AND ('MATERIAS.INSTITUCIONALES'!=$_SESSION['zez_a_matricula'])) {
												$orden_sql2 .= " AND `sello`='" . $_SESSION['zez_a_sello'] . "'";
											} else {
												$orden_sql2 .= " AND `sello`<>'0'";
											}
											$orden_sql2 .= " ORDER BY `carrera`";
											$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
											if ($resultado_busqueda2) {
												if (mysqli_num_rows($resultado_busqueda2) > 0) {
													// Si hubo resultado genera la tabla
													while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
														$contador_materias++;
														if ('MATERIAS INSTITUCIONALES'!=$carrera_profesor) $listado_materias_profesor[$contador_materias]['carrera'] = 'MATERIAS SELLO';
														$listado_materias_profesor[$contador_materias]['materia'] = $registro2['materia'];
													}
												}
												// Libera el conjunto de resultados
												mysqli_free_result($resultado_busqueda2);
											}
										}
										for($contador=1; $contador<=$contador_materias; $contador++) {
											// ########### INICIO DE BLOQUE INICIALIZAR VARIABLES
											if ('MATERIAS INSTITUCIONALES'!=$carrera_profesor) $carrera_desglosada = $listado_materias_profesor[$contador]['carrera'];
											$materia_desglosada = $listado_materias_profesor[$contador]['materia'];
											unset($numero_pregunta);
											unset($numero_comentario);
											unset($texto_pregunta);	
											unset($respuestas);
											for ($contador_items=1; $contador_items<=$total_reactivos; $contador_items++) {
												$numero_pregunta[$contador_items] = "r" . str_pad($contador_items, 2, "0", STR_PAD_LEFT);
												$numero_comentario[$contador_items] = $numero_pregunta[$contador_items] . "c";
												$fondo_comentario[$contador_items] = "";
											}
											$usar_contador_interno = false;
											for ($contador_secciones=1;$contador_secciones<=$total_secciones;$contador_secciones++) {
												for ($contador_subsecciones=0; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
													if ($secciones[$contador_secciones][$contador_subsecciones]['limitada']!="") $usar_contador_interno = true;
												}
											}
											$carrera_profesor = substr($post_profesor,0,strpos($post_profesor,"¬"));
											$nombre_profesor = substr($post_profesor,strpos($post_profesor,"¬")+2);
											// ============== INICIA CONSULTA DE RESULTADOS
											$calificacion_general = 0;
											$contador_competencias = 0;
											unset($resultados_competencias);
											unset($resultados_items);
											unset($listado_competencias);
											$competencia_actual = "qetuoadgjlzcbmwryipsfhkñxvn";
											for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
												for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
													if("CERRADA"==$secciones[$contador_secciones][$contador_subsecciones]['tipo']) {
														for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
															if ($competencia_actual!=$reactivo[$contador_items]['competencia']) {
																$listado_competencias[] = $reactivo[$contador_items]['competencia'];
																$competencia_actual = $reactivo[$contador_items]['competencia'];
																$resultados_competencias[$competencia_actual]['inicia'] = $contador_items;
																$resultados_competencias[$competencia_actual]['termina'] = $contador_items;
																$resultados_competencias[$competencia_actual]['conteo_ponderado'] = 1;
																$resultados_competencias[$competencia_actual]['conteo_alumnos'] = 0;
																$resultados_competencias[$competencia_actual]['conteo_coordinadores'] = 0;
																$resultados_competencias[$competencia_actual]['conteo_autoevaluacion'] = 0;
															} else {
																$resultados_competencias[$competencia_actual]['termina'] = $contador_items;
																$resultados_competencias[$competencia_actual]['conteo_ponderado']++;
															}
															// Obtiene los resultados de alumnos
															if (0!=$reactivo[$contador_items]['alumnos']) {
																$resultados_competencias[$competencia_actual]['conteo_alumnos']++;
																$resultados_items[$contador_items]['alumnos'] = 0;
																$orden_sql2 = "SELECT AVG(" . $numero_pregunta[$reactivo[$contador_items]['alumnos']] . ") ";
																$orden_sql2 .= "AS promedio ";
																$orden_sql2 .= "FROM `lic_profesores_por_alumnos` ";
																$orden_sql2 .= "WHERE ";
																$orden_sql2 .= "`materia`='$materia_desglosada' ";
																$orden_sql2 .= "AND `nombre`='$nombre_profesor'";
																if ('MATERIAS INSTITUCIONALES'==$carrera_profesor) {
																	if (('0'!=$_SESSION['zez_a_sello']) AND ('MATERIAS.INSTITUCIONALES'!=$_SESSION['zez_a_matricula'])) {
																		$orden_sql2 .= " AND `sello`='" . $_SESSION['zez_a_sello'] . "'";
																	} else {
																		$orden_sql2 .= " AND `sello`<>'0'";
																	}
																} else {
																	if ('TODOS'!=$carrera_profesor) $orden_sql2 .= " AND `carrera`='$carrera_profesor'";
																}
																$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
																if ($resultado_busqueda2) {
																	if (mysqli_num_rows($resultado_busqueda2) > 0) {
																		// Si hubo resultado genera la tabla
																		while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
																			$resultados_items[$contador_items]['alumnos'] = round($registro2['promedio'] * $multiplicador, 2);
																		}
																	}
																	// Libera el conjunto de resultados
																	mysqli_free_result($resultado_busqueda2);
																}
															}
															// Obtiene los resultados de coordinadores
															if (0!=$reactivo[$contador_items]['coordinadores']) {
																$resultados_competencias[$competencia_actual]['conteo_coordinadores']++;
																$resultados_items[$contador_items]['coordinadores'] = 0;
																$orden_sql2 = "SELECT AVG(" . $numero_pregunta[$reactivo[$contador_items]['coordinadores']] . ") ";
																$orden_sql2 .= "AS promedio ";
																$orden_sql2 .= "FROM `lic_profesores_por_coordinadores` ";
																$orden_sql2 .= "WHERE ";
																$orden_sql2 .= "`nombre`='$nombre_profesor'";
																if ('TODOS'!=$carrera_profesor) $orden_sql2 .= " AND `carrera` LIKE '%$carrera_profesor%'";
																if (('0'!=$_SESSION['zez_a_sello']) AND ('MATERIAS.INSTITUCIONALES'!=$_SESSION['zez_a_matricula'])) $orden_sql2 .= " AND `sello`='" . $_SESSION['zez_a_sello'] . "'";
																$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
																if ($resultado_busqueda2) {
																	if (mysqli_num_rows($resultado_busqueda2) > 0) {
																		// Si hubo resultado genera la tabla
																		while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
																			$resultados_items[$contador_items]['coordinadores'] = round($registro2['promedio'] * $multiplicador, 2);
																		}
																	}
																	// Libera el conjunto de resultados
																	mysqli_free_result($resultado_busqueda2);
																}
															}
															// Obtiene los resultados de autoevaluaciones
															if (0!=$reactivo[$contador_items]['autoevaluacion']) {
																$resultados_competencias[$competencia_actual]['conteo_autoevaluacion']++;
																$resultados_items[$contador_items]['autoevaluacion'] = 0;
																$orden_sql2 = "SELECT AVG(" . $numero_pregunta[$reactivo[$contador_items]['autoevaluacion']] . ") ";
																$orden_sql2 .= "AS promedio ";
																$orden_sql2 .= "FROM `lic_profesores_por_profesores` ";
																$orden_sql2 .= "WHERE ";
																$orden_sql2 .= "`nombre`='$nombre_profesor'";
																if ('TODOS'!=$carrera_profesor) $orden_sql2 .= " AND `carrera` LIKE '%$carrera_profesor%'";
																$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
																if ($resultado_busqueda2) {
																	if (mysqli_num_rows($resultado_busqueda2) > 0) {
																		// Si hubo resultado genera la tabla
																		while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
																			$resultados_items[$contador_items]['autoevaluacion'] = round($registro2['promedio'] * $multiplicador, 2);
																		}
																	}
																	// Libera el conjunto de resultados
																	mysqli_free_result($resultado_busqueda2);
																}
															}
														}
													} elseif("ABIERTA"==$secciones[$contador_secciones][$contador_subsecciones]['tipo']) {
														for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
															// Obtiene los resultados de alumnos
															if (0!=$reactivo[$contador_items]['alumnos']) {
																$resultados_items[$contador_items]['alumnos'] = "";
																$orden_sql2 = "SELECT " . $numero_pregunta[$reactivo[$contador_items]['alumnos']] . " ";
																$orden_sql2 .= "FROM `lic_profesores_por_alumnos` ";
																$orden_sql2 .= "WHERE " . $numero_pregunta[$reactivo[$contador_items]['alumnos']] . " != ''";
																$orden_sql2 .= " AND `materia`='$materia_desglosada'";
																$orden_sql2 .= " AND `nombre`='$nombre_profesor'";
																if ('TODOS'!=$carrera_profesor) $orden_sql2 .= " AND `carrera`='$carrera_profesor'";
																if ('0'!=$_SESSION['zez_a_sello']) $orden_sql2 .= " AND `sello`='" . $_SESSION['zez_a_sello'] . "'";
																$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
																if ($resultado_busqueda2) {
																	if (mysqli_num_rows($resultado_busqueda2) > 0) {
																		// Si hubo resultado genera la tabla
																		while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
																			$resultados_items[$contador_items]['alumnos'] .= "<li>" . $registro2[$numero_pregunta[$reactivo[$contador_items]['alumnos']]] . "</li>";
																		}
																	}
																	// Libera el conjunto de resultados
																	mysqli_free_result($resultado_busqueda2);
																}
																if (""!=$resultados_items[$contador_items]['alumnos']) $resultados_items[$contador_items]['alumnos'] = "<p style='font-weight:bold;'>Alumnos</p><ol>" . $resultados_items[$contador_items]['alumnos'] . "</ol>";
															}
															// Obtiene los resultados de coordinadores
															if (0!=$reactivo[$contador_items]['coordinadores']) {
																$resultados_items[$contador_items]['coordinadores'] = "";
																$orden_sql2 = "SELECT " . $numero_pregunta[$reactivo[$contador_items]['coordinadores']] . " ";
																$orden_sql2 .= "FROM `lic_profesores_por_coordinadores` ";
																$orden_sql2 .= "WHERE " . $numero_pregunta[$reactivo[$contador_items]['coordinadores']] . " != '' ";
																$orden_sql2 .= " AND `nombre`='$nombre_profesor'";
																if ('TODOS'!=$carrera_profesor) $orden_sql2 .= " AND `carrera` LIKE '%$carrera_profesor%'";
																if ('0'!=$_SESSION['zez_a_sello']) $orden_sql2 .= " AND `sello`='" . $_SESSION['zez_a_sello'] . "'";
																$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
																if ($resultado_busqueda2) {
																	if (mysqli_num_rows($resultado_busqueda2) > 0) {
																		// Si hubo resultado genera la tabla
																		while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
																			$resultados_items[$contador_items]['coordinadores'] .= "<li>" . $registro2[$numero_pregunta[$reactivo[$contador_items]['coordinadores']]] . "</li>";
																		}
																	}
																	// Libera el conjunto de resultados
																	mysqli_free_result($resultado_busqueda2);
																}
																if (""!=$resultados_items[$contador_items]['coordinadores']) $resultados_items[$contador_items]['coordinadores'] = "<p style='font-weight:bold;'>Coordinador</p><ol>" . $resultados_items[$contador_items]['coordinadores'] . "</ol>";
															}
															// Obtiene los resultados de autoevaluacion
															if (0!=$reactivo[$contador_items]['autoevaluacion']) {
																$resultados_items[$contador_items]['autoevaluacion'] = "";
																$orden_sql2 = "SELECT " . $numero_pregunta[$reactivo[$contador_items]['autoevaluacion']] . " ";
																$orden_sql2 .= "FROM `lic_profesores_por_profesores` ";
																$orden_sql2 .= "WHERE " . $numero_pregunta[$reactivo[$contador_items]['autoevaluacion']] . " != '' ";
																$orden_sql2 .= " AND `nombre`='$nombre_profesor'";
																if ('TODOS'!=$carrera_profesor) $orden_sql2 .= " AND `carrera` LIKE '%$carrera_profesor%'";
																$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
																if ($resultado_busqueda2) {
																	if (mysqli_num_rows($resultado_busqueda2) > 0) {
																		// Si hubo resultado genera la tabla
																		while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
																			$resultados_items[$contador_items]['autoevaluacion'] .= "<li>" . $registro2[$numero_pregunta[$reactivo[$contador_items]['autoevaluacion']]] . "</li>";
																		}
																	}
																	// Libera el conjunto de resultados
																	mysqli_free_result($resultado_busqueda2);
																}
																if (""!=$resultados_items[$contador_items]['autoevaluacion']) $resultados_items[$contador_items]['autoevaluacion'] = "<p style='font-weight:bold;'>Autoevaluación</p><ol>" . $resultados_items[$contador_items]['autoevaluacion'] . "</ol>";
															}
														}
													}
												}
											}
											// Obtiene las ponderaciones de los items
											for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
												for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
													if("CERRADA"==$secciones[$contador_secciones][$contador_subsecciones]['tipo']) {
														for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
															$resultados_items[$contador_items]['ponderado'] = 0;
															$divisor_ponderaciones = 0;
															// Obtiene la parte de alumnos
															if (0!=$reactivo[$contador_items]['alumnos']) {
																$resultados_items[$contador_items]['ponderado'] += ($resultados_items[$contador_items]['alumnos'] * $ponderaciones['alumnos']);
																$divisor_ponderaciones += $ponderaciones['alumnos'];
															}
															// Obtiene la parte de coordinadores
															if (0!=$reactivo[$contador_items]['coordinadores']) {
																$resultados_items[$contador_items]['ponderado'] += ($resultados_items[$contador_items]['coordinadores'] * $ponderaciones['coordinadores']);
																$divisor_ponderaciones += $ponderaciones['coordinadores'];
															}
															// Obtiene la parte de autoevaluacion
															if (0!=$reactivo[$contador_items]['autoevaluacion']) {
																$resultados_items[$contador_items]['ponderado'] += ($resultados_items[$contador_items]['autoevaluacion'] * $ponderaciones['autoevaluacion']);
																$divisor_ponderaciones += $ponderaciones['autoevaluacion'];
															}
															// Obtiene las ponderaciones por item
															$resultados_items[$contador_items]['ponderado'] = round($resultados_items[$contador_items]['ponderado'] / $divisor_ponderaciones, 2);
														}
													}
												}
											}
											// Obtiene las ponderaciones de las competencias
											foreach ($listado_competencias as $competencia_actual) {
												$contador_competencias++;
												if (0!=$resultados_competencias[$competencia_actual]['conteo_alumnos']) {
													$resultados_competencias[$competencia_actual]['alumnos'] = 0;
													for ($contador_items=$resultados_competencias[$competencia_actual]['inicia']; $contador_items<=$resultados_competencias[$competencia_actual]['termina']; $contador_items++) {
														if (isset($resultados_items[$contador_items]['alumnos'])) $resultados_competencias[$competencia_actual]['alumnos'] += $resultados_items[$contador_items]['alumnos'];
													}
													$resultados_competencias[$competencia_actual]['alumnos'] = round($resultados_competencias[$competencia_actual]['alumnos'] / $resultados_competencias[$competencia_actual]['conteo_alumnos'], 2);
												}
												if (0!=$resultados_competencias[$competencia_actual]['conteo_coordinadores']) {
													$resultados_competencias[$competencia_actual]['coordinadores'] = 0;
													for ($contador_items=$resultados_competencias[$competencia_actual]['inicia']; $contador_items<=$resultados_competencias[$competencia_actual]['termina']; $contador_items++) {
														if(isset($resultados_items[$contador_items]['coordinadores'])) $resultados_competencias[$competencia_actual]['coordinadores'] += $resultados_items[$contador_items]['coordinadores'];
													}
													$resultados_competencias[$competencia_actual]['coordinadores'] = round($resultados_competencias[$competencia_actual]['coordinadores'] / $resultados_competencias[$competencia_actual]['conteo_coordinadores'], 2);
												}
												if (0!=$resultados_competencias[$competencia_actual]['conteo_autoevaluacion']) {
													$resultados_competencias[$competencia_actual]['autoevaluacion'] = 0;
													for ($contador_items=$resultados_competencias[$competencia_actual]['inicia']; $contador_items<=$resultados_competencias[$competencia_actual]['termina']; $contador_items++) {
														if(isset($resultados_items[$contador_items]['autoevaluacion'])) $resultados_competencias[$competencia_actual]['autoevaluacion'] += $resultados_items[$contador_items]['autoevaluacion'];
													}
													$resultados_competencias[$competencia_actual]['autoevaluacion'] = round($resultados_competencias[$competencia_actual]['autoevaluacion'] / $resultados_competencias[$competencia_actual]['conteo_autoevaluacion'], 2);
												}
												$resultados_competencias[$competencia_actual]['ponderado'] = 0;
												for ($contador_items=$resultados_competencias[$competencia_actual]['inicia']; $contador_items<=$resultados_competencias[$competencia_actual]['termina']; $contador_items++) {
													$resultados_competencias[$competencia_actual]['ponderado'] += $resultados_items[$contador_items]['ponderado'];
												}
												$resultados_competencias[$competencia_actual]['ponderado'] = round($resultados_competencias[$competencia_actual]['ponderado'] / $resultados_competencias[$competencia_actual]['conteo_ponderado'], 2);
												$calificacion_general += $resultados_competencias[$competencia_actual]['ponderado'];
											}
											$calificacion_general = round($calificacion_general / $contador_competencias, 2);
											// ============== TERMINA CONSULTA DE RESULTADOS
											// ########### FIN DE BLOQUE INICIALIZAR VARIABLES
											// ============== PONE EL TEXTO PREVIO A LOS REACTIVOS
											if ($previo['cuerpo'][0]!="") {
												if ($previo['encabezado']!="") {
													if ($previo['numeracion']!="") {
														echo "<div class='seccion'><span>" . preg_replace('/\s/', ' ', $previo['numeracion']) . "</span>" . $previo['encabezado'] . "</div>";
													} else {
														echo "<div class='seccion'>" . preg_replace('/\s/', ' ', $previo['encabezado']) . "</div>";
													}
												}
												echo "<div class='contenedor-interno'>";
												foreach ($previo['cuerpo'] as $renglon) {
													echo "<p>$renglon</p>";
												}
												echo "</div>";
											}
											// ============== INICIO BLOQUE SECCIONES
											$competencia_actual = "qetuoadgjlzcbmwryipsfhkñxvn";
											for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
												echo "<br />";
												if (0!=$calificacion_general) {
													// ============== INICIO BLOQUE ENCABEZADO DE TABLA
													echo "
														<table class='table table-bordered table-striped table-sm'>
														<tr>
															<td style='border-top-style:hidden!important;border-left-style:hidden!important;width:80%;text-align:center;' colspan='2'>";
																if ("TODOS"==$carrera_profesor) {
																	echo "
																		<b>CARRERA:</b> " . $carrera_desglosada . "
																		<br /><br />";
																}echo "
																<b>MATERIA:</b> $materia_desglosada
																<br /><br />
																<b>CALIFICACIÓN:</b> $calificacion_general
															</td>
															<th class='rotacion'>Alumnos</th>
															<th class='rotacion'>Coordinadores</th>
															<th class='rotacion'>Autoevaluación</th>
															<th class='rotacion'>Ponderado</th>
															</tr>";
													for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
														// ============== INICIO BLOQUE ITEMS
														for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
															if ($competencia_actual!=$reactivo[$contador_items]['competencia']) {
																$competencia_actual = $reactivo[$contador_items]['competencia'];
																$colspan = ("CERRADA"==$secciones[$contador_secciones][$contador_subsecciones]['tipo']) ? ' colspan="2"' : ' colspan="6"';
																echo "<tr>";
																echo "<td$colspan class='azul' style='font-weight:bold;text-align:left;'>$competencia_actual</td>";
																echo "</td>";
																if ("CERRADA"==$secciones[$contador_secciones][$contador_subsecciones]['tipo']) {
																	echo "<td class='azul' style='text-align:center;width:30px;min-width:30px;max-width:30px;font-weight:bold;'>";
																	echo "";
																	echo (isset($resultados_competencias[$competencia_actual]['alumnos'])) ? number_format($resultados_competencias[$competencia_actual]['alumnos'],2) : "&nbsp;";
																	echo "";
																	echo "</td>";
																	echo "<td class='azul' style='text-align:center;width:30px;min-width:30px;max-width:30px;font-weight:bold;'>";
																	echo "";
																	echo (isset($resultados_competencias[$competencia_actual]['coordinadores'])) ? number_format($resultados_competencias[$competencia_actual]['coordinadores'],2) : "&nbsp;";
																	echo "";
																	echo "</td>";
																	echo "<td class='azul' style='text-align:center;width:30px;min-width:30px;max-width:30px;font-weight:bold;'>";
																	echo "";
																	echo (isset($resultados_competencias[$competencia_actual]['autoevaluacion'])) ? number_format($resultados_competencias[$competencia_actual]['autoevaluacion'],2) : "&nbsp;";
																	echo "";
																	echo "</td>";
																	echo "<td class='azul' style='text-align:center;width:30px;min-width:30px;max-width:30px;font-weight:bold;'>";
																	echo "";
																	echo (isset($resultados_competencias[$competencia_actual]['ponderado'])) ? number_format($resultados_competencias[$competencia_actual]['ponderado'],2) : "&nbsp;";
																	echo "";
																	echo "</td>";
																}
																echo "</tr>";
															}
															echo "<tr>";
															if ($secciones[$contador_secciones][$contador_subsecciones]['cero']=="") {
																$inicio_contador_respuestas = 1;
																$incremento_respuestas = 0;
															} else {
																$inicio_contador_respuestas = 0;
																$incremento_respuestas = 1;
															}
															$rowspan = ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="CERRADA") ? '' : ' rowspan="2"';
															$colspan = ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="CERRADA") ? '' : ' colspan="5"';
															echo "<td$rowspan class='gris' style='width:30px;min-width:30px;max-width:30px;font-weight:bold;text-align:center;'>";
															if($usar_contador_interno) {
																echo $preguntas_visibles;
															} else {
																echo $contador_items;
															}
															echo "</td>";
															if ("CERRADA"==$secciones[$contador_secciones][$contador_subsecciones]['tipo']) {
																echo "<td$colspan style='width:75%;text-align:left;'>" . preg_replace('/\s/', ' ', $reactivo[$contador_items]['texto']) . "</td>";
																echo "<td style='text-align:center;width:30px;min-width:30px;max-width:30px;'>";
																echo "";
																echo (isset($resultados_items[$contador_items]['alumnos'])) ? number_format($resultados_items[$contador_items]['alumnos'],2) : "&nbsp;";
																echo "";
																echo "</td>";
																echo "<td style='text-align:center;width:30px;min-width:30px;max-width:30px;'>";
																echo "";
																echo (isset($resultados_items[$contador_items]['coordinadores'])) ? number_format($resultados_items[$contador_items]['coordinadores'],2) : "&nbsp;";
																echo "";
																echo "</td>";
																echo "<td style='text-align:center;width:30px;min-width:30px;max-width:30px;'>";
																echo "";
																echo (isset($resultados_items[$contador_items]['autoevaluacion'])) ? number_format($resultados_items[$contador_items]['autoevaluacion'],2) : "&nbsp;";
																echo "";
																echo "</td>";
																echo "<td style='text-align:center;width:30px;min-width:30px;max-width:30px;'>";
																echo "";
																echo (isset($resultados_items[$contador_items]['ponderado'])) ? number_format($resultados_items[$contador_items]['ponderado'],2) : "&nbsp;";
																echo "";
																echo "</td>";
															} else {
																echo "<td$colspan style='text-align:left;'>";
																echo "" . preg_replace('/\s/', ' ', $reactivo[$contador_items]['texto']) . "<br />";
																echo "</td>";
																echo "</tr>";
																echo "<tr>";
																echo "<td$colspan style='text-align:left;'>";
																if (""!=$resultados_items[$contador_items]['alumnos']) echo $resultados_items[$contador_items]['alumnos'];
																if (""!=$resultados_items[$contador_items]['coordinadores']) echo $resultados_items[$contador_items]['coordinadores'];
																if (""!=$resultados_items[$contador_items]['autoevaluacion']) echo $resultados_items[$contador_items]['autoevaluacion'];
																echo "";
																echo "</td>";
															}
															echo "</tr>";
															if ($secciones[$contador_secciones][$contador_subsecciones]['comentario']) {
																echo "<tr>";
																echo "<td" . $fondo_comentario[$contador_items] . " style='text-align:left;'>";
																echo "Comentario sobre esta pregunta...<br />";
																if (isset($_POST['regresada'])) {
																	if (strpos($_POST['regresada'], "Larga") !== false) {
																		echo "(actualmente el comentario tiene " . mb_strlen(pone_comillas($comentarios[$contador_items]), 'UTF-8') . " caracteres y el máximo permitido es de $maximo_caracteres)";
																	} else {
																		echo "(máximo $maximo_caracteres caracteres)";
																	}
																} else {
																	echo "(máximo $maximo_caracteres caracteres)";
																}
																echo "</td>";
																echo "<td colspan='$valores_por_renglon' style='text-align:left;'>";
																echo "<textarea name='" . $numero_comentario[$contador_items] . "'>";
																if (isset($_POST['regresada'])) {
																	echo pone_comillas($comentarios[$contador_items]);
																}
																echo "</textarea>";
																echo "</td>";
																echo "</tr>";
															}
														}
														// ============== TERMINA BLOQUE ITEMS
													}
													// ============== TERMINA BLOQUE SECCIONES INTERNAS
													echo "</table>";
												} else {
													echo "<p>Sin evaluaciones.</p>";
												}
											}
										}
										// ============== TERMINA BLOQUE SECCIONES
										// ▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄
										// █                                                                                                    █
										// █                                    TERMINA DESGLOSE DE MATERIAS                                    █
										// █                                                                                                    █
										// ▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀
									}
								} else {
									echo "<p>Sin evaluaciones.</p>";
								}
								echo "</div>";
							}
							// ============== TERMINA BLOQUE SECCIONES
							//#################################################################################################################################################################
						} else {
							include 'lic_deporteycultura_p_general.php';
							$calificacion_general = 0;
							for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
								$promedio_secciones[$contador_secciones] = 0;
								for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
									$promedio_subsecciones[$contador_secciones][$contador_subsecciones] = 0;
									for ($contador_reactivos=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_reactivos<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_reactivos++) {
										$promedio_reactivos[$contador_secciones][$contador_reactivos] = 0;
										$orden_sql = 'SELECT AVG(`r' . (($contador_reactivos<10) ? '0': '') . $contador_reactivos . '`) AS `promedio` FROM `' . $secciones[$contador_secciones][0]['tabla'] . '`';
										if ('PROFESOR'==$parametros[0]) $orden_sql .= " WHERE `nombre`='$nombre_profesor'";
										$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
										if ($resultado_busqueda) {
											if (mysqli_num_rows($resultado_busqueda) > 0) {
												$registro = mysqli_fetch_array($resultado_busqueda);
												$promedio_reactivos[$contador_secciones][$contador_reactivos] = round($registro['promedio'],4);
												$promedio_subsecciones[$contador_secciones][$contador_subsecciones] += $promedio_reactivos[$contador_secciones][$contador_reactivos];
											}
											// Libera el conjunto de resultados
											mysqli_free_result($resultado_busqueda);
										}
									}
									$promedio_subsecciones[$contador_secciones][$contador_subsecciones] = round($promedio_subsecciones[$contador_secciones][$contador_subsecciones] / ($secciones[$contador_secciones][$contador_subsecciones]['termina'] - $secciones[$contador_secciones][$contador_subsecciones]['inicia'] + 1),4);
									$promedio_secciones[$contador_secciones] += $promedio_subsecciones[$contador_secciones][$contador_subsecciones];
								}
								$promedio_secciones[$contador_secciones] = round($promedio_secciones[$contador_secciones] / $total_subsecciones[$contador_secciones],4);
								$calificacion_general += $promedio_secciones[$contador_secciones] * $secciones[$contador_secciones][0]['ponderacion'];
							}
							if ('CARRERA'!=$parametros[0]) { ?>
								<div class="seccion"><?php echo $nombre_profesor; ?></div> <?php
							} ?>
							<div class="contenedor-interno">
								<table class="table table-bordered table-striped table-sm" style="font-size:80%;margin-left:auto;margin-right:auto;">
									<tr>
										<th>Evaluación</th> <?php
										for($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) { ?>
											<th style="background:#1f497d;color:#ffffff;width:<?php echo (int)(80/$total_secciones); ?>%;"><?php echo $secciones[$contador_secciones][0]['encabezado']; ?></th><?php
										} ?>
									</tr>
									<tr>
										<th>Calificación</th><?php
										for($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {?>
											<td><?php echo (0==$promedio_secciones[$contador_secciones]) ? 'Sin datos' : number_format($promedio_secciones[$contador_secciones],4); ?></td><?php
										}?>
									</tr>
									<tr>
										<th>Ponderación</th><?php
										for($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) { ?>
											<th style="background:#1f497d;color:#ffffff;"><?php echo number_format($secciones[$contador_secciones][0]['ponderacion'] * 100,2); ?>%</th> <?php
										} ?>
									</tr>
									<tr>
										<th>Aporte a CG</th> <?php
										for($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) { ?>
											<td><?php echo (0==$promedio_secciones[$contador_secciones]) ? 'Sin datos' : number_format($promedio_secciones[$contador_secciones] * $secciones[$contador_secciones][0]['ponderacion'],4); ?></td> <?php
										} ?>
									</tr>
									<tr>
										<th>Calificación General</th>
										<td colspan="<?php echo $total_secciones; ?>" style="font-weight:bold;font-size:100%;"><?php echo (0==$calificacion_general) ? 'Sin datos' : number_format($calificacion_general,4); ?></td>
									</tr>
								</table>
							</div> <?php
							for($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) { ?>
								<div class="seccion"><?php echo $secciones[$contador_secciones][0]['texto'];?></div>
								<div class="contenedor-interno">
									<table class="table table-bordered table-striped table-sm" style="font-size:80%;margin-left:auto;margin-right:auto;">
										<tr>
											<th style="width:7%;">No.</th>
											<th style="width:79%;">Pregunta</th>
											<th style="width:14%;">Promedio</th>
										</tr><?php
										for($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
											for($contador_reactivos=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_reactivos<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_reactivos++) {
												echo "<tr>";
												echo "<td><strong>" . $contador_reactivos . "</strong></td>";
												echo "<td style='text-align:left;'>" . $texto_reactivo[$contador_secciones][$contador_reactivos] . "</td>";
												echo "<td>" . ((0==$promedio_reactivos[$contador_secciones][$contador_reactivos]) ? 'Sin datos' : number_format($promedio_reactivos[$contador_secciones][$contador_reactivos],4)) . "</td>";
												echo "</tr>";
											}
											if ($secciones[$contador_secciones][$contador_subsecciones]['comentario']) {
												echo "<tr>";
												echo "<td colspan='3' style='text-align:left;'>";
												echo "<strong>Comentarios:</strong><br />";
												$sin_comentarios = true;
												$orden_sql = 'SELECT `r' . ((($secciones[$contador_secciones][$contador_subsecciones]['termina']+1)<10) ? '0': '') . ($secciones[$contador_secciones][$contador_subsecciones]['termina']+1) . '` AS `comentario` FROM `' . $secciones[$contador_secciones][0]['tabla'] . '`';
												if ('PROFESOR'==$parametros[0]) $orden_sql .= " WHERE `nombre`='$nombre_profesor'";
												$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
												if ($resultado_busqueda) {
													if (mysqli_num_rows($resultado_busqueda) > 0) {
														while($registro = mysqli_fetch_array($resultado_busqueda)) {
															$sin_comentarios = false;
															if ((''!=trim($registro['comentario'])) and (strlen(trim($registro['comentario']))>1)) echo "<li>" . trim($registro['comentario']) . "";
														}
													}
													// Libera el conjunto de resultados
													mysqli_free_result($resultado_busqueda);
												}
												if ($sin_comentarios) echo "Sin comentarios.";
												echo "</td>";
												echo "</tr>";
											}
										} ?>
									</table>
								</div><?php
							}
						}
					?>
					<div class="nota-verde">
						<table style="border:none;text-align:left;">
							<tr style="border:none;text-align:left;">
								<td style="border:none;text-align:left;"><img src="lib/img/planeta_casa.jpg" /></td>
								<td style="border:none;text-align:left;padding:0px 0px 0px 10px;">Cuidemos del medio ambiente.<br />Por favor no imprimas este reporte si no es necesario.</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</body>
	<?php mysqli_close($base_de_datos); ?>
	<script type="text/javascript"> window.print(); </script>
</html>