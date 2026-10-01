<?php
	session_start();
	require_once 'lib/config.php';
	if (!isset($_SESSION['zez_a_nombre'])) {
		header('Location: salir.php');
	} elseif (strpos(" ACUDE ACADEMICO DIRECTOR ADMINISTRADOR SUPERUSUARIO",$_SESSION['zez_a_nivel_acceso'])== FALSE) {
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
				} while (strpos($string_parametros,"¬"));
				$parametros[] = $string_parametros;
			}
		}
	}
	if(!$parametros_validos) {
		header('Location: index.php');
	}
	if (isset($parametros[1])) {
		$parametros_imprimir = "%%".$parametros[0]."¬".$parametros[1];
		if(isset($_POST['pormaterias'])) {
			$parametros_imprimir .= "¬POR_MATERIAS%%";
		} else {
			$parametros_imprimir .= "%%";
		}
		$ref = "abrir('bach_reporte_profesores2_impresora.php?parametros=" . urlencode(encriptar($parametros_imprimir)) . "');";
	} else {
		$ref = "confirm('Por favor elije un profesor antes de mandar a imprimir.');";
	}
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
			if ((strpos(" COORDINADOR VICERRECTOR FIDCO",$_SESSION['zez_a_nivel_acceso'])!= FALSE) AND (date("Y-m-d") >= $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) AND (date("Y-m-d") <= $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin'])) { ?>
				<div class=""><a class="btn btn-warning text-dark"onclick="redirigir('mis_evaluaciones.php');"><strong>Mis Evaluaciones</strong></a></div><?php
			}
		?>
		<div class="ml-auto">
			<a class="btn text-light" onclick="<?php echo $ref?>"><strong>Imprimir</strong></a>
			<a class="btn text-light" onclick="redirigir('salir.php');"><strong>Salir</strong></a>
		</div>
	</div>
	<body>
		<div class="container">
			<div class="card">
				<div class="card-header">
					<h1>
						<img src="lib/img/blanco.png" class="img-form-left" />
						<?php echo "Reporte de profesores"; ?>
					</h1>
					<span><?php echo "Ciclo " . $ciclo['BACHILLERATO']; ?></span>
				</div>
				<div class="card-footer">
					<div class="seccion">Opciones del reporte</div>
					<div class="contenedor-interno">
						<?php
							$contador_de_profesores = 0;
							// Genera la orden SQL para hacer la consulta a la tabla de la evaluación general de licenciaturas
							$orden_sql = "SELECT DISTINCT nombre FROM bach_profesores ORDER BY nombre";
							// Ejecuta la consulta SQL
							$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
							if ($resultado_busqueda) {
								if (mysqli_num_rows($resultado_busqueda) > 0) { ?>
									<form name="profesorFrm" id="profesorFrm" action="bach_reporte_profesores2.php" method="post" autocomplete="off" accept-charset="UTF-8" >
										<div class="input-group mb-3 input-group-sm">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><strong>Profesor:</strong></span>
                        </div>
											<select name="parametros" class="custom-select" onChange="document.profesorFrm.submit()">
												<?php						
													if (!isset($parametros[1])) { echo "<option>Seleccione un profesor...</option>"; }
													// Si hubo resultado obtiene los datos de la sesión y activa la bandera
													unset($listado_de_profesores);
													unset($listado_de_materias);
													while($registro = mysqli_fetch_array($resultado_busqueda)) {
														$contador_de_profesores++;
														$listado_de_profesores[$registro['nombre']] = $registro['nombre'];
														echo "<option value='" . encriptar("%%".$parametros[0]."¬".$registro['nombre']."%%")."'";
														if (isset($parametros[1])) {
															if ($registro['nombre']==$parametros[1]) {
																echo " selected='selected'";
															}
														}
														echo ">" . $registro['nombre'] . "</option>";
														if(isset($_POST['pormaterias'])) {
															$orden_sql = "SELECT DISTINCT materia ";
															$orden_sql .= "FROM bach_profesores ";
															$orden_sql .= "WHERE nombre='" . $registro['nombre'] . "' ";
															$orden_sql .= "ORDER BY materia";
															// Ejecuta la consulta SQL
															$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql);
															if ($resultado_busqueda2) {
																if (mysqli_num_rows($resultado_busqueda2) > 0) {
																	while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
																		$listado_de_materias[$registro['nombre']][] = $registro2['materia'];
																	}
																}
															}
														} else {
															$listado_de_materias[$registro['nombre']][] = "==TODO==";
														}
													}
												?>
											</select>
										</div><?php
								}
								// Libera el conjunto de resultados
								mysqli_free_result($resultado_busqueda);
							}
							if (isset($parametros[1])) { ?>
								<div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="pormaterias" name="pormaterias" value="1" onChange="document.profesorFrm.submit()"<?php if(isset($_POST['pormaterias'])) echo " checked"; ?> />
                  <label class="custom-control-label" for="pormaterias">Separar por materias</Label>
                </div> <?php
              } else { ?>
								<input type=hidden name="pormaterias" value="1" /> <?php
							}
						?>
									</form>
					</div>
					<div class="contenedor-interno">
						<p style="text-align:center;"><a href="https://docs.google.com/document/d/1n09La7-l1Ce2bVftIRnBJoyzsBGJvNiAxJL1SVPfbN8/edit?usp=sharing">Acceso al instrumento de  la rúbrica de Evaluación de los alumnos a los docentes</a></p>
					</div>
				</div>
				<div class="card-body">
					<?php
						if ($contador_de_profesores>0) {
							if (isset($parametros[1])) {
								// ########### INICIO DE BLOQUE INICIALIZAR VARIABLES
								unset($tamano_set_valores);
								$tamano_set_valores[0] = 1;
								$tamano_set_valores[1] = 5;
								$tamano_set_valores[2] = 5;
								$tamano_set_valores[3] = 5;
								$tamano_set_valores[4] = 5;
								$tamano_set_valores[5] = 5;

								unset($set_valores);
								$set_valores[1][1] = "Totalmente a destiempo";
								$set_valores[1][2] = "Casi siempre es a destiempo";
								$set_valores[1][3] = "Regular";
								$set_valores[1][4] = "Muy buena";
								$set_valores[1][5] = "Totalmente a tiempo";

								$set_valores[2][1] = "Insuficiente";
								$set_valores[2][2] = "Poco";
								$set_valores[2][3] = "Regular";
								$set_valores[2][4] = "Muy bueno";
								$set_valores[2][5] = "Excelente";

								$set_valores[3][1] = "Nula";
								$set_valores[3][2] = "Poca";
								$set_valores[3][3] = "Regular";
								$set_valores[3][4] = "Muy buena";
								$set_valores[3][5] = "Excelente";

								$set_valores[4][1] = "Nulo";
								$set_valores[4][2] = "Poco";
								$set_valores[4][3] = "Regular";
								$set_valores[4][4] = "Muy bueno";
								$set_valores[4][5] = "Excelente";

								$set_valores[5][1] = "Nunca";
								$set_valores[5][2] = "Casi nunca";
								$set_valores[5][3] = "A veces";
								$set_valores[5][4] = "Casi siempre";
								$set_valores[5][5] = "Siempre";

								unset($secciones);
								$secciones[1][0]['texto'] = "";
								$secciones[1][1]['inicia'] = 1;
								$secciones[1][1]['termina'] = 1;
								$secciones[1][1]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
								$secciones[1][1]['set_valores'] = 1; // En abierta poner 0
								$secciones[1][1]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
								$secciones[1][2]['inicia'] = 2;
								$secciones[1][2]['termina'] = 2;
								$secciones[1][2]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
								$secciones[1][2]['set_valores'] = 2; // En abierta poner 0
								$secciones[1][2]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
								$secciones[1][3]['inicia'] = 3;
								$secciones[1][3]['termina'] = 4;
								$secciones[1][3]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
								$secciones[1][3]['set_valores'] = 3; // En abierta poner 0
								$secciones[1][3]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
								$secciones[1][4]['inicia'] = 5;
								$secciones[1][4]['termina'] = 5;
								$secciones[1][4]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
								$secciones[1][4]['set_valores'] = 4; // En abierta poner 0
								$secciones[1][4]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
								$secciones[1][5]['inicia'] = 6;
								$secciones[1][5]['termina'] = 6;
								$secciones[1][5]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
								$secciones[1][5]['set_valores'] = 3; // En abierta poner 0
								$secciones[1][5]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
								$secciones[1][6]['inicia'] = 7;
								$secciones[1][6]['termina'] = 7;
								$secciones[1][6]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
								$secciones[1][6]['set_valores'] = 4; // En abierta poner 0
								$secciones[1][6]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
								$secciones[1][7]['inicia'] = 8;
								$secciones[1][7]['termina'] = 11;
								$secciones[1][7]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
								$secciones[1][7]['set_valores'] = 5; // En abierta poner 0
								$secciones[1][7]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
								$secciones[1][8]['inicia'] = 12;
								$secciones[1][8]['termina'] = 13;
								$secciones[1][8]['tipo'] = "ABIERTA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
								$secciones[1][8]['set_valores'] = 0; // En abierta poner 0
								$secciones[1][8]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
								$total_secciones = 1;
								$total_subsecciones[1] = 8;
								$total_preguntas = 13;
								$valores_por_renglon = 5;
								unset($numero_pregunta);
								for ($contador_items=1; $contador_items<=$total_preguntas; $contador_items++) {
									$numero_pregunta[$contador_items] = "r" . str_pad($contador_items, 2, "0", STR_PAD_LEFT);
									$fondo[$contador_items] = "";
								}
								unset($texto_pregunta);
								$texto_pregunta[1] = "El/la docente inicia y finaliza puntualmente cada una de sus clases o sesiones de trabajo.";
								$texto_pregunta[2] = "Demuestra un alto nivel de conocimiento y dominio sobre los temas de la asignatura o taller que imparte.";
								$texto_pregunta[3] = "Fomenta un ambiente de respeto, equidad, confianza y compañerismo dentro del grupo.";
								$texto_pregunta[4] = "Escucha con atención y responde de manera respetuosa cuando los alumnos expresan sus ideas, dudas o comentarios.";
								$texto_pregunta[5] = "Relaciona los contenidos de la materia con situaciones o problemas reales de la vida cotidiana.";
								$texto_pregunta[6] = "Establece y mantiene normas claras y justas que favorecen la buena convivencia y el trabajo en equipo.";
								$texto_pregunta[7] = "Organiza adecuadamente el tiempo, los materiales y los recursos para crear un ambiente de aprendizaje positivo.";
								$texto_pregunta[8] = "Comunica con claridad los objetivos y propósitos de cada tema o actividad.";
								$texto_pregunta[9] = "Utiliza estrategias de enseñanza que resultan interesantes, retadoras y significativas para los estudiantes.";
								$texto_pregunta[10] = "Promueve el desarrollo del pensamiento crítico y la reflexión en sus clases.";
								$texto_pregunta[11] = "Evalúa de forma clara y justa, tomando en cuenta los conocimientos, habilidades y valores trabajados durante el curso.";
								$texto_pregunta[12] = "¿Qué aspectos consideras que el/la docente podría mejorar para fortalecer el aprendizaje y desarrollo de sus alumnos? (Sé específico/a: piensa en explicaciones, dinámicas, trato, claridad, organización, etc. Házlo de forma objetiva.)";
								$texto_pregunta[13] = "¿Cuáles son las principales cualidades y fortalezas de el/la docente que contribuyen al aprendizaje y desarrollo de sus alumnos? (Piensa en su forma de enseñar, su trato, su actitud, su manera de acompañarte, etc.)";

								if(isset($_POST['pormaterias'])) {
									$tope_materias = count($listado_de_materias[$parametros[1]])-1;
								} else {
									$tope_materias = 0;
								}
								// ########### FIN DE BLOQUE INICIALIZAR VARIABLES
								// ########### INICIO BLOQUE RECUPERAR RESPUESTAS GENERALES
								unset($respuestas);
								unset($promedio_respuestas_generales);
								unset($promedo_promedio_generales);
								$promedo_promedio_generales = 0;
								unset($contador_evaluaciones);
								$contador_evaluaciones = 0;
								for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
									for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
										if ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="CERRADA") {
											if ($secciones[$contador_secciones][$contador_subsecciones]['cero'] != "") {
												$inicio_valores = 0;
												$fin_valores = count($set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]) - 1;
											} else {
												$inicio_valores = 1;
												$fin_valores = count($set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]);
											}
											for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
												for ($contador_valores=$inicio_valores; $contador_valores<=$fin_valores; $contador_valores++) {
													$respuestas[$contador_items][$contador_valores] = 0;
												}
												$promedio_respuestas_generales[$contador_items] = 0;
											}
										} elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="MULTIPLE") {
											$inicio_valores = 1;
											$fin_valores = count($set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]);
											for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
												for ($contador_valores=$inicio_valores; $contador_valores<=$fin_valores; $contador_valores++) {
													$respuestas[$contador_items][$set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']][$contador_valores]] = 0;
													//$respuestas[$contador_items][$contador_valores] = 0;
												}
											}
										} elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="ABIERTA") {
											for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
												//$respuestas[$contador_items][0] = "==INICIO==";
											}
										}
									}
								}
								// Genera la orden SQL para hacer la consulta a la tabla de la evaluación general de licenciaturas
								$orden_sql = "SELECT * ";
								$orden_sql .= "FROM bach_profesores ";
								$orden_sql .= "ORDER BY materia";
								// Ejecuta la consulta SQL
								$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
								if ($resultado_busqueda) {
									if (mysqli_num_rows($resultado_busqueda) > 0) {
										// Inicializar variables
										while($registro = mysqli_fetch_array($resultado_busqueda)) {
											$contador_evaluaciones++;
											for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
												for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
													if ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="CERRADA") {
														for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
															$respuestas[$contador_items][$registro[$numero_pregunta[$contador_items]]]++;
														}
													} elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="MULTIPLE") {
														for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
															//$respuestas[$contador_items][$registro[$numero_pregunta[$contador_items]]]++;
															$texto = $registro[$numero_pregunta[$contador_items]];
															while (strlen($texto) > 2) {
																$texto = substr($texto, 1);
																$subtexto = substr($texto, 0, strpos($texto, "#"));
																if($subtexto != '') {
																	$respuestas[$contador_items][$subtexto]++;
																}
																$texto = substr($texto, strlen($subtexto));
															}
														}
													} elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="ABIERTA") {
														for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
															if ($registro[$numero_pregunta[$contador_items]] != "") {
																$respuestas[$contador_items][] = $registro[$numero_pregunta[$contador_items]];
															} else {
																$respuestas[$contador_items][] = NULL;
															}
														}
													}
												}
											}
										}
									}
									// Libera el conjunto de resultados
									mysqli_free_result($resultado_busqueda);
								}
								for ($contador_respuestas=1; $contador_respuestas<=5; $contador_respuestas++) {
									for ($contador_items=1; $contador_items<=11; $contador_items++) {
										$promedio_respuestas_generales[$contador_items] += (($respuestas[$contador_items][$contador_respuestas] / $contador_evaluaciones) * 100) * ($contador_respuestas - 1);
									}
								}
								for ($contador_items=1; $contador_items<=11; $contador_items++) {
									$promedo_promedio_generales += $promedio_respuestas_generales[$contador_items] / 4;
								}
								// ########### FIN BLOQUE RECUPERAR RESPUESTAS GENERALES
								// ########### INICIO BLOQUE RECUPERAR RESPUESTAS
								for ($contador_materias=0; $contador_materias<=$tope_materias; $contador_materias++) {
									unset($respuestas);
									unset($promedio_respuestas);
									unset($promedo_promedio);
									$promedo_promedio = 0;
									unset($contador_evaluaciones);
									$contador_evaluaciones = 0;
									for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
										for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
											if ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="CERRADA") {
												if ($secciones[$contador_secciones][$contador_subsecciones]['cero'] != "") {
													$inicio_valores = 0;
													$fin_valores = count($set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]) - 1;
												} else {
													$inicio_valores = 1;
													$fin_valores = count($set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]);
												}
												for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
													for ($contador_valores=$inicio_valores; $contador_valores<=$fin_valores; $contador_valores++) {
														$respuestas[$contador_items][$contador_valores] = 0;
													}
													$promedio_respuestas[$contador_items] = 0;
												}
											} elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="MULTIPLE") {
												$inicio_valores = 1;
												$fin_valores = count($set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]);
												for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
													for ($contador_valores=$inicio_valores; $contador_valores<=$fin_valores; $contador_valores++) {
														$respuestas[$contador_items][$set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']][$contador_valores]] = 0;
														//$respuestas[$contador_items][$contador_valores] = 0;
													}
												}
											} elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="ABIERTA") {
												for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
													//$respuestas[$contador_items][0] = "==INICIO==";
												}
											}
										}
									}
									// Genera la orden SQL para hacer la consulta a la tabla de la evaluación general de licenciaturas
									$orden_sql = "SELECT * ";
									$orden_sql .= "FROM bach_profesores ";
									$orden_sql .= "WHERE nombre='" . $listado_de_profesores[$parametros[1]] . "' ";
									if(isset($_POST['pormaterias'])) {
										$orden_sql .= "AND materia='" . $listado_de_materias[$parametros[1]][$contador_materias] . "' ";
									}
									$orden_sql .= "ORDER BY materia";
									// Ejecuta la consulta SQL
									$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
									if ($resultado_busqueda) {
										if (mysqli_num_rows($resultado_busqueda) > 0) {
											// Inicializar variables
											while($registro = mysqli_fetch_array($resultado_busqueda)) {
												$contador_evaluaciones++;
												for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
													for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
														if ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="CERRADA") {
															for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
																$respuestas[$contador_items][$registro[$numero_pregunta[$contador_items]]]++;
															}
														} elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="MULTIPLE") {
															for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
																//$respuestas[$contador_items][$registro[$numero_pregunta[$contador_items]]]++;
																$texto = $registro[$numero_pregunta[$contador_items]];
																while (strlen($texto) > 2) {
																	$texto = substr($texto, 1);
																	$subtexto = substr($texto, 0, strpos($texto, "#"));
																	if($subtexto != '') {
																		$respuestas[$contador_items][$subtexto]++;
																	}
																	$texto = substr($texto, strlen($subtexto));
																}
															}
														} elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="ABIERTA") {
															for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
																if ($registro[$numero_pregunta[$contador_items]] != "") {
																	$respuestas[$contador_items][] = $registro[$numero_pregunta[$contador_items]];
																} else {
																	$respuestas[$contador_items][] = NULL;
																}
															}
														}
													}
												}
											}
										}
										// Libera el conjunto de resultados
										mysqli_free_result($resultado_busqueda);
									}
									for ($contador_respuestas=1; $contador_respuestas<=5; $contador_respuestas++) {
										for ($contador_items=1; $contador_items<=11; $contador_items++) {
											$promedio_respuestas[$contador_items] += (($respuestas[$contador_items][$contador_respuestas] / $contador_evaluaciones) * 100) * ($contador_respuestas - 1);
										}
									}
									for ($contador_items=1; $contador_items<=11; $contador_items++) {
										$promedo_promedio += $promedio_respuestas[$contador_items] / 4;
									}
									// ########### FIN BLOQUE RECUPERAR RESPUESTAS
									// ########### INICIO DEL CUESTIONARIO
									if(isset($_POST['pormaterias'])) {
										echo "<div class='seccion'>" . $listado_de_materias[$parametros[1]][$contador_materias] . "</div>";
									} else {
										echo "<div class='seccion'>REPORTE GENERAL</div>";
									} ?>
									<div class="contenedor-interno">
										<table class="table table-bordered table-striped table-sm text-center">
											<?php
												// ============== INICIO BLOQUE SECCIONES
												echo "<tr>";
												echo "<th class='fondo-marista' style='width:7%;'>Escala</th>";
												for ($contador_items=1; $contador_items<=11; $contador_items++) { echo "<th class='fondo-marista' style='width:7%;'>" . $contador_items . "</th>"; }
												echo "<th class='fondo-marista' style='width:16%;'>Calificación final</th>";
												echo "</tr>";
												for ($contador_respuestas=1; $contador_respuestas<=5; $contador_respuestas++) {
													echo "<tr>";
													echo "<td class='gris' style='font-weight:bold;'>" . ($contador_respuestas-1) . "</td>";
													for ($contador_items=1; $contador_items<=11; $contador_items++) { echo "<td>" . number_format(round(($respuestas[$contador_items][$contador_respuestas] / $contador_evaluaciones) * 100, 2),2) . "</td>"; }
													if (1==$contador_respuestas) {
														echo "<td class='";
														if (round($promedo_promedio / 11, 1) >= 85) {
															echo "azul";
														} elseif (round($promedo_promedio / 11, 1) < 70) {
															echo "rojo";
														} else {
															echo "verde";
														}
														echo "' rowspan='6' style='font-weight:bold;font-size:2.5em;'>" . number_format(round($promedo_promedio / 11, 1), 1) . "</td>";
													}
													echo "</tr>";
												}
												echo "<tr>";
												echo "<td style='font-weight:bold;'>Totales</td>";
												for ($contador_items=1; $contador_items<=11; $contador_items++) {
													echo "<td class='";
													if (round($promedio_respuestas[$contador_items] / 4, 1) >= 85) {
														echo "azul";
													} elseif (round($promedio_respuestas[$contador_items] / 4, 1) < 70) {
														echo "rojo";
													} else {
														echo "verde";
													}
													echo "' style='font-weight:bold;'>" . number_format(round($promedio_respuestas[$contador_items] / 4, 1), 1) . "</td>";
												}
												echo "</tr>";
												echo "<tr>";
												echo "<td colspan='13'>&nbsp;</td>";
												echo "</tr>";
												echo "<tr>";
												echo "<td style='font-weight:bold;'>BCLB</td>";
												for ($contador_items=1; $contador_items<=11; $contador_items++) {
													echo "<td class='";
													if (round($promedio_respuestas_generales[$contador_items] / 4, 1) >= 85) {
														echo "azul";
													} elseif (round($promedio_respuestas_generales[$contador_items] / 4, 1) < 70) {
														echo "rojo";
													} else {
														echo "verde";
													}
													echo "' style='font-weight:bold;'>" . number_format(round($promedio_respuestas_generales[$contador_items] / 4, 1), 1) . "</td>";
												}
												echo "<td class='";
												if (round($promedo_promedio_generales / 11, 1) >= 85) {
													echo "azul";
												} elseif (round($promedo_promedio_generales / 11, 1) < 70) {
													echo "rojo";
												} else {
													echo "verde";
												}
												echo "' style='font-weight:bold;'>" . number_format(round($promedo_promedio_generales / 11, 1), 1) . "</td>";
												echo "</tr>";
												// Comentarios
												echo "<tr>";
												echo "<td colspan='13'>&nbsp;</td>";
												echo "</tr>";
												echo "<tr>";
												for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
													for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
														for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
															if ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="ABIERTA") {
																echo "<tr>";
																echo "<th class='fondo-marista'>" . $contador_items . "</td>";
																echo "<td colspan='12' style='text-align:left;'>";
																echo "<ul>";
																for ($contador_respuestas=0; $contador_respuestas<=count($respuestas[$contador_items])-1; $contador_respuestas++) { if ($respuestas[$contador_items][$contador_respuestas]!=NULL) { echo "<li>" . $respuestas[$contador_items][$contador_respuestas] . "</li>"; } }
																echo "<ul>";
																echo "</td>";
																echo "</tr>";
															}
														}
													}
												}
											?>
										</table>
									</div> <?php
									// ########### FIN DEL CUESTIONARIO
								}
							}
						} else { echo "<p>No hay profesores evaluados.</p>"; }
					?>
				</div>
			</div>
		</div>
	</body>
	<?php mysqli_close($base_de_datos); ?>
</html>