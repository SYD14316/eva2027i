<?php
	session_start();
	require_once 'lib/config.php';
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
	if(!$parametros_validos) {
		header('Location: index.php');
	}
	unset($listado_reportes);
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

	<body>
		<div class="container" style="padding-top: 38px;">
			<div class="card">
				<div class="card-header">
					<h1>
						<img src="lib/img/blanco.png" class="img-form-left" />
						Tabla de Profesores
					</h1>
					<span>Ciclo <?php echo $ciclo['LICENCIATURA']; ?></span>
				</div>
				<div class="card-body">
					<?php
						// Genera la orden SQL para hacer la consulta a la tabla de la evaluación general de licenciaturas
						if (($_SESSION['zez_a_nivel_acceso']=="COORDINADOR") OR ($_SESSION['zez_a_nivel_acceso']=="FIDCO")) {
							$orden_sql = "SELECT DISTINCT P.`carrera` ";
							$orden_sql .= "FROM `profesores` P ";
							$orden_sql .= "INNER JOIN `carreras` C ";
							$orden_sql .= "ON P.`carrera`=C.`carrera` ";
							$orden_sql .= "INNER JOIN `departamentos` D ";
							$orden_sql .= "ON C.`departamento`=D.`departamento` ";
							$orden_sql .= "WHERE (C.`coordinador`='" . $_SESSION['zez_a_nombre'] . "' ";
							$orden_sql .= "OR D.`jefe`='" . $_SESSION['zez_a_nombre'] . "') ";
							$orden_sql .= "AND P.`sello` = '" . $_SESSION['zez_a_sello'] . "' ";
							// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
							$orden_sql .= "AND P.`carrera` <> 'DEPORTE Y CULTURA' ";
							// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
							$orden_sql .= "ORDER BY P.`carrera` ASC";
							$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
							$carreras = "";
							if ($resultado_busqueda) {
								if (mysqli_num_rows($resultado_busqueda) > 0) {
									while($registro = mysqli_fetch_array($resultado_busqueda)) {
										if (""!=$carreras) $carreras .= " OR ";
										$carreras .= "`carrera` LIKE '%" . $registro['carrera'] . "%'";
									}
								}
								// Libera el conjunto de resultados
								mysqli_free_result($resultado_busqueda);
							}
							$orden_sql = "SELECT DISTINCT P.`nombre` ";
							$orden_sql .= "FROM `profesores` P ";
							$orden_sql .= "INNER JOIN `carreras` C ";
							$orden_sql .= "ON P.`carrera`=C.`carrera` ";
							$orden_sql .= "INNER JOIN `departamentos` D ";
							$orden_sql .= "ON C.`departamento`=D.`departamento` ";
							$orden_sql .= "WHERE P.`sello` = '" . $_SESSION['zez_a_sello'] . "' ";
							$orden_sql .= "AND (C.`coordinador`='" . $_SESSION['zez_a_nombre'] . "' ";
							$orden_sql .= "OR D.`jefe`='" . $_SESSION['zez_a_nombre'] . "') ";
							// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
							$orden_sql .= "AND P.`carrera` <> 'DEPORTE Y CULTURA' ";
							// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
							$orden_sql .= "ORDER BY P.`nombre` ASC";
						} else {
							$orden_sql = "SELECT DISTINCT `nombre` ";
							$orden_sql .= "FROM `profesores` ";
							$orden_sql .= "WHERE `nivel`='LICENCIATURA' ";
							// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
							$orden_sql .= "AND `carrera` <> 'DEPORTE Y CULTURA' ";
							// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
							$orden_sql .= "ORDER BY `nombre` ASC";
						}
						$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
						if ($resultado_busqueda) {
							if (mysqli_num_rows($resultado_busqueda) > 0) {
								// Si hubo resultado genera la tabla
								if(isset($tabla_resultados)) unset($tabla_resultados);
								if(isset($encabezado_tabla_resultados)) unset($encabezado_tabla_resultados);
								$encabezado_tabla_resultados[] = "Profesor";
								$contador_profesores = 0;
								$primer_registro = true;
								unset($tamano_set_valores);
								unset($set_valores);
								unset($secciones);
								unset($numero_pregunta);
								unset($numero_comentario);
								unset($texto_pregunta);	
								unset($respuestas);
								include_once 'lic_profesores_general.php';
								unset($listado_competencias);
								$competencia_actual = 'ASDFGHJKLÑ';
								for ($contador_items=1; $contador_items<=$total_reactivos; $contador_items++) {
									$numero_pregunta[$contador_items] = "r" . str_pad($contador_items, 2, "0", STR_PAD_LEFT);
									if (($competencia_actual!=$reactivo[$contador_items]['competencia']) and ('COMENTARIOS GENERALES'!=$reactivo[$contador_items]['competencia'])) $listado_competencias[] = $competencia_actual = $reactivo[$contador_items]['competencia'];
								}
								while($registro = mysqli_fetch_array($resultado_busqueda)) {
									$contador_profesores++;
									$tabla_resultados[$contador_profesores]['nombre'] = $registro['nombre'];
									if(isset($contador_respuestas)) unset($contador_respuestas);
									for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
										for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
											if("CERRADA"==$secciones[$contador_secciones][$contador_subsecciones]['tipo']) {
												for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
													if(!isset($tabla_resultados[$contador_profesores][$reactivo[$contador_items]['competencia']])) $tabla_resultados[$contador_profesores][$reactivo[$contador_items]['competencia']] = 0;
													if(!isset($contador_respuestas[$reactivo[$contador_items]['competencia']])) $contador_respuestas[$reactivo[$contador_items]['competencia']] = 0;
													$contador_respuestas[$reactivo[$contador_items]['competencia']]++;
													$calificacion_ponderada = 0;
													$divisor = 0;
													// Obtiene los resultados de alumnos
													if (0!=$reactivo[$contador_items]['alumnos']) {
														$divisor += $ponderaciones['alumnos'];
														$promedio = 0;
														if (($_SESSION['zez_a_nivel_acceso']=="COORDINADOR") OR ($_SESSION['zez_a_nivel_acceso']=="FIDCO")) {
															$orden_sql2 = "SELECT AVG(`" . $numero_pregunta[$reactivo[$contador_items]['alumnos']] . "`) ";
															$orden_sql2 .= "AS `promedio` ";
															$orden_sql2 .= "FROM `lic_profesores_por_alumnos` ";
															$orden_sql2 .= "WHERE `nombre`='" . $registro['nombre'] . "' ";
															$orden_sql2 .= "AND `sello`='" . $_SESSION['zez_a_sello'] . "' ";
															$orden_sql2 .= "AND ($carreras)";
														} else {
															$orden_sql2 = "SELECT AVG(`" . $numero_pregunta[$reactivo[$contador_items]['alumnos']] . "`) ";
															$orden_sql2 .= "AS `promedio` ";
															$orden_sql2 .= "FROM `lic_profesores_por_alumnos` ";
															$orden_sql2 .= "WHERE `nombre`='" . $registro['nombre'] . "'";
														}
														$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
														if ($resultado_busqueda2) {
															if (mysqli_num_rows($resultado_busqueda2) > 0) {
																// Si hubo resultado genera la tabla
																$registro2 = mysqli_fetch_array($resultado_busqueda2);
																if (null!=$registro2['promedio']) {
																	$promedio = $registro2['promedio'];
																}
															}
															// Libera el conjunto de resultados
															mysqli_free_result($resultado_busqueda2);
														}
														$calificacion_ponderada += round($promedio,2) * $ponderaciones['alumnos'];
													}
													if (0!=$reactivo[$contador_items]['coordinadores']) {
														$divisor += $ponderaciones['coordinadores'];
														$promedio = 0;
														if (($_SESSION['zez_a_nivel_acceso']=="COORDINADOR") OR ($_SESSION['zez_a_nivel_acceso']=="FIDCO")) {
															$orden_sql2 = "SELECT AVG(`" . $numero_pregunta[$reactivo[$contador_items]['coordinadores']] . "`) ";
															$orden_sql2 .= "AS `promedio` ";
															$orden_sql2 .= "FROM `lic_profesores_por_coordinadores` ";
															$orden_sql2 .= "WHERE `nombre`='" . $registro['nombre'] . "' ";
															$orden_sql2 .= "AND `sello`='" . $_SESSION['zez_a_sello'] . "' ";
															$orden_sql2 .= "AND ($carreras)";
														} else {
															$orden_sql2 = "SELECT AVG(`" . $numero_pregunta[$reactivo[$contador_items]['coordinadores']] . "`) ";
															$orden_sql2 .= "AS `promedio` ";
															$orden_sql2 .= "FROM `lic_profesores_por_coordinadores` ";
															$orden_sql2 .= "WHERE `nombre`='" . $registro['nombre'] . "'";
														}
														$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
														if ($resultado_busqueda2) {
															if (mysqli_num_rows($resultado_busqueda2) > 0) {
																// Si hubo resultado genera la tabla
																$registro2 = mysqli_fetch_array($resultado_busqueda2);
																if (null!=$registro2['promedio']) {
																	$promedio = $registro2['promedio'];
																}
															}
															// Libera el conjunto de resultados
															mysqli_free_result($resultado_busqueda2);
														}
														$calificacion_ponderada += round($promedio,2) * $ponderaciones['coordinadores'];
													}
													if (0!=$reactivo[$contador_items]['autoevaluacion']) {
														$divisor += $ponderaciones['autoevaluacion'];
														$promedio = 0;
														$orden_sql2 = "SELECT `" . $numero_pregunta[$reactivo[$contador_items]['autoevaluacion']] . "` ";
														$orden_sql2 .= "AS `promedio` ";
														$orden_sql2 .= "FROM `lic_profesores_por_profesores` ";
														$orden_sql2 .= "WHERE `nombre`='" . $registro['nombre'] . "'";
														$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
														if ($resultado_busqueda2) {
															if (mysqli_num_rows($resultado_busqueda2) > 0) {
																// Si hubo resultado genera la tabla
																$registro2 = mysqli_fetch_array($resultado_busqueda2);
																$promedio = $registro2['promedio'];
															}
															// Libera el conjunto de resultados
															mysqli_free_result($resultado_busqueda2);
														}
														$calificacion_ponderada += round($promedio,2) * $ponderaciones['autoevaluacion'];
													}
													$tabla_resultados[$contador_profesores][$reactivo[$contador_items]['competencia']] += round(($calificacion_ponderada / $divisor) * $multiplicador, 2);
												}
											}
										}
									}
									$tabla_resultados[$contador_profesores]['ponderado'] = 0;
									foreach($listado_competencias as $competencia_actual) {
										$tabla_resultados[$contador_profesores][$competencia_actual] = round($tabla_resultados[$contador_profesores][$competencia_actual] / $contador_respuestas[$competencia_actual], 2);
										$tabla_resultados[$contador_profesores]['ponderado'] += $tabla_resultados[$contador_profesores][$competencia_actual];
									}
									$tabla_resultados[$contador_profesores]['ponderado'] = round($tabla_resultados[$contador_profesores]['ponderado'] / sizeof($listado_competencias),2);
								}
								for($contador=1; $contador<=$contador_profesores; $contador++) {
									if ($parametros[0]=="CALIFICACION") {
										$tabla_resultados_ordenada[$contador]['Calificación'] = $tabla_resultados[$contador]['ponderado'];
										$tabla_resultados_ordenada[$contador]['Profesor'] = $tabla_resultados[$contador]['nombre'];
									} else {
										$tabla_resultados_ordenada[$contador]['Profesor'] = $tabla_resultados[$contador]['nombre'];
										$tabla_resultados_ordenada[$contador]['Calificación'] = $tabla_resultados[$contador]['ponderado'];
									}
									for($cont_secciones=1; $cont_secciones<=(sizeof($encabezado_tabla_resultados)-3); $cont_secciones++){
										$tabla_resultados_ordenada[$contador][$encabezado_tabla_resultados[$cont_secciones]] = $tabla_resultados[$contador][$encabezado_tabla_resultados[$cont_secciones]];
									}
								}
								if ($parametros[0]=="CALIFICACION") {
									rsort($tabla_resultados_ordenada);
								}
							}
							// Libera el conjunto de resultados
							mysqli_free_result($resultado_busqueda);
						}
						echo "<div class='seccion text-center'>Tabla de resultados</div>";
						echo "<div class='contenedor-interno' id='tabla_resultados_original'>";
						if (isset($tabla_resultados_ordenada)) {
							echo "
								<table class='table table-bordered table-striped table-sm'>
									<tr>
										<th>Profesor(a)</th>
										<th>Calificación</th>
									</tr>
							";
							foreach($tabla_resultados_ordenada AS $profesor_ok) {
								echo "
									<tr>
										<td>" . $profesor_ok['Profesor'] . "</td>
										<td>" . $profesor_ok['Calificación'] . "</td>
									</tr>
								";
							}
							echo "</table>";
						} else {
							echo "<p>No se encontró evaluación alguna.</p>";
						}
						echo "</div>";
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