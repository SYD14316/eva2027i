<?php
	session_start();
	require_once 'lib/config.php';
	if (!isset($_SESSION['zez_a_nivel_acceso'])) { // Si no está logueado lo manda al inicio
		session_destroy();
		header('Location: index.php');
	} else { // Si sí está logueado...
		if (!$_POST) { header('Location: index.php'); } ?>
		<html>
			<?php include_once HEADER; ?>
			<script type="text/javascript" src="lib/functions.js"></script>
			<body onload="submitform()">
				<div class="container">
					<div class="card">
						<div class="card-body">
							<?php
									if (($_SESSION['zez_a_nivel_acceso'] != "ALUMNO") OR ($_SESSION['zez_a_nivel'] != "BACHILLERATO")) {
										// Si no tiene derecho a está página...
										echo $_SESSION['zez_a_nombre']; ?> no estás autorizad<?php if ($_SESSION['zez_a_sexo']=="FEMENINO") { echo "a"; } else { echo "o"; } ?> para ingresar a esta página.<br><br>
										Para regresar al menú principal haz clic <a href="index.php">aquí</a>. <?php
									} else { 
										// ##### Si sí tiene derecho a esta página ingresar código desde aquí 	#####
										// Inicializa la variable de control
										$evaluacion_incompleta = false;
										$respuesta_larga = false;

										unset($tamano_set_valores);

										$tamano_set_valores[0] = 1;
										$tamano_set_valores[1] = 5;

										unset($set_valores);

										$set_valores[1][1] = "Deficiente";
										$set_valores[1][2] = "Regular";
										$set_valores[1][3] = "Bueno";
										$set_valores[1][4] = "Muy Bueno";
										$set_valores[1][5] = "Excelente";

										unset($secciones);

										$secciones[1][0]['texto'] = "";
										$secciones[1][1]['inicia'] = 1;
										$secciones[1][1]['termina'] = 16;
										$secciones[1][1]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
										$secciones[1][1]['set_valores'] = 1; // En abierta poner 0
										$secciones[1][1]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""

										$secciones[1][2]['inicia'] = 17;
										$secciones[1][2]['termina'] = 18;
										$secciones[1][2]['tipo'] = "ABIERTA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
										$secciones[1][2]['set_valores'] = 0; // En abierta poner 0
										$secciones[1][2]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""

										$total_secciones = 1;
										$total_subsecciones[1] = 2;
										$total_preguntas = 18;
										$valores_por_renglon = 5;
										unset($numero_pregunta);
										for ($contador_items=1; $contador_items<=$total_preguntas; $contador_items++) {
											$numero_pregunta[$contador_items] = "r" . str_pad($contador_items, 2, "0", STR_PAD_LEFT);
											$fondo[$contador_items] = "";
										}
										$acude_id = $_POST['acude_id'];
										$acude_actividad = $_POST['acude_actividad'];
										$acude_tipo = $_POST['acude_tipo'];
										$acude_profesor = $_POST['acude_profesor'];
										$acude_sexo = $_POST['acude_sexo'];
										// Revisa todas las preguntas que deben de ser contestadas y que las preguntas abiertas no sean muy largas
										for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
											for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
												for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
													if ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="CERRADA") {
														if (!isset($_POST[$numero_pregunta[$contador_items]])) $evaluacion_incompleta = true;
													} elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="ABIERTA") {
														if (mb_strlen($_POST[$numero_pregunta[$contador_items]], 'UTF-8')>$maximo_caracteres) $respuesta_larga = true;
													}
												}
											}
										}
										// Determina si la evaluación está incompleta o tiene respuesta muy larga
										$motivo_regreso = "";
										if ($evaluacion_incompleta) { $motivo_regreso .= "Incompleta"; }
										if ($respuesta_larga) { $motivo_regreso .= "Larga"; }
										if ($evaluacion_incompleta OR $respuesta_larga) {
											// Si la evaluación está incompleta ó marcó más de 3 niveles de inglés la regresa ?>
											<form name="Regresar" action="bach_evalua_acude.php" method=post autocomplete="off" accept-charset="UTF-8">
												<input type=hidden name="acude_id" value="<?php echo $acude_id; ?>" />
												<input type=hidden name="acude_actividad" value="<?php echo $acude_actividad; ?>" />
												<input type=hidden name="acude_tipo" value="<?php echo $acude_tipo; ?>" />
												<input type=hidden name="acude_profesor" value="<?php echo $acude_profesor; ?>" />
												<input type=hidden name="acude_sexo" value="<?php echo $acude_sexo; ?>" />
												<?php
													for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
														for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
															for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
																if ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="CERRADA") {
																	if (isset($_POST[$numero_pregunta[$contador_items]])) {
																		echo "<input type=hidden name='" . $numero_pregunta[$contador_items] . "' value='" . $_POST[$numero_pregunta[$contador_items]] . "' />";
																	}
																} elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="MULTIPLE") {
																	if ($secciones[$contador_secciones][$contador_subsecciones]['cero']=="") {
																		$inicio_contador_respuestas = 1;
																		$incremento_respuestas = 0;
																	} else {
																		$inicio_contador_respuestas = 0;
																		$incremento_respuestas = 1;
																	}
																	for ($contador_respuestas=$inicio_contador_respuestas; $contador_respuestas<=$tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]; $contador_respuestas++) {
																		if (isset($_POST[$numero_pregunta[$contador_items]."_".$contador_respuestas])) {
																			echo "<input type=hidden name='" . $numero_pregunta[$contador_items] . "_$contador_respuestas' value='" . $_POST[$numero_pregunta[$contador_items]."_".$contador_respuestas] . "' />";
																		}
																	}
																} else {
																	echo "<input type=hidden name='" . $numero_pregunta[$contador_items] . "' value='" . quita_comillas($_POST[$numero_pregunta[$contador_items]]) . "' />";
																}
															}
														}
													}
												?>
												<input type=hidden name="regresada" value="<?php echo $motivo_regreso; ?>" />
											</form>
											<script type="text/javascript" language="javascript"> document.Regresar.submit(); </script> <?php
										} else { // Si la evaluación está completa la registra
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
											// Valida que no se haya registrado previamente esta evaluación
											$identificador = "#ACUDE#";
											if (!stristr($_SESSION['zez_a_evaluados'], $identificador)) {
												// Registra la evaluación en la tabla de evaluaciones
												$orden_sql = "INSERT INTO bach_profesores (grupo, nombre, materia, sexo, carrera_a, grado_a, sexo_a";
												$orden_sql_parte2 = "";
												for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
													for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
														for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
															$orden_sql .= ", " . $numero_pregunta[$contador_items];
															if ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="CERRADA") {
																$orden_sql_parte2 .= "', '" . $_POST[$numero_pregunta[$contador_items]];
															} elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="MULTIPLE") {
																if ($secciones[$contador_secciones][$contador_subsecciones]['cero']=="") {
																	$inicio_contador_respuestas = 1;
																	$incremento_respuestas = 0;
																} else {
																	$inicio_contador_respuestas = 0;
																	$incremento_respuestas = 1;
																}
																$valores_multiple = "";
																for ($contador_respuestas=$inicio_contador_respuestas; $contador_respuestas<=$tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]; $contador_respuestas++) {
																	if (isset($_POST[$numero_pregunta[$contador_items]."_".$contador_respuestas])) {
																		$valores_multiple .= "#" . $_POST[$numero_pregunta[$contador_items]."_".$contador_respuestas] . "#";
																	}
																}
																$orden_sql_parte2 .= "', '" . $valores_multiple;
															} else {
																$orden_sql_parte2 .= "', '" . limpiarcampo($_POST[$numero_pregunta[$contador_items]]);
															}
														}
													}
												}
												$orden_sql .= ") VALUES ('ACUDE". $acude_id . "', '" . $acude_profesor . "', '" . $acude_actividad . " - " . $acude_tipo . "', '" . $acude_sexo;
												$orden_sql .= "', '" . $_SESSION['zez_a_carrera'] . "', '" . $_SESSION['zez_a_grado'] . "', '" . $_SESSION['zez_a_sexo'];
												$orden_sql .= $orden_sql_parte2 . "')";
												mysqli_query($base_de_datos, $orden_sql);
												// Registra la evaluación en el usuario
												$_SESSION['zez_a_evaluados'] = $_SESSION['zez_a_evaluados'] . $identificador;
												$orden_sql = "UPDATE participantes SET evaluados='" . $_SESSION['zez_a_evaluados'] . "' WHERE id='". $_SESSION['zez_a_id'] . "'";
												mysqli_query($base_de_datos, $orden_sql);
												$_SESSION['zez_a_num_evaluados']++;
												$orden_sql = "UPDATE participantes SET num_evaluados='" . $_SESSION['zez_a_num_evaluados'] . "' WHERE id='". $_SESSION['zez_a_id'] . "'";
												mysqli_query($base_de_datos, $orden_sql);
											}
											// Cierra la BDD
											mysqli_close($base_de_datos); ?>
											<form name="Regresar" action="mis_evaluaciones.php#evaluaciones" method=post autocomplete="off" accept-charset="UTF-8">
											</form>
											<script type="text/javascript" language="javascript"> document.Regresar.submit(); </script> <?php
										}
									} // ##### Si sí tiene derecho a esta página ingresar código hasta aquí #####
							?>
						</div>
					</div>
				</div>
			</body>
		</html> <?php
	}
?>