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
										echo $_SESSION['zez_a_nombre']; ?> no estás autorizad<?php if ($_SESSION['zez_a_sexo']=="FEMENINO") { echo "a"; } else { echo "o"; } ?> para ingresar a esta página.<br><br>
										Para regresar al menú principal haz clic <a href="index.php">aquí</a>. <?php
									} else {
										// ##### Si sí tiene derecho a esta página ingresar código desde aquí #####
										// Inicializa la variable de control
										$evaluacion_incompleta = false;
										$respuesta_larga = false;
										unset($secciones);
										$secciones[1][0]['texto'] = "Coordinador Académico";
										$secciones[1][1]['inicia'] = 1;
										$secciones[1][1]['termina'] = 3;
										$secciones[1][1]['cerrada'] = true;
										$secciones[1][2]['inicia'] = 4;
										$secciones[1][2]['termina'] = 5;
										$secciones[1][2]['cerrada'] = false;
										$secciones[2][0]['texto'] = "Coordinador de Pastoral";
										$secciones[2][1]['inicia'] = 6;
										$secciones[2][1]['termina'] = 8;
										$secciones[2][1]['cerrada'] = true;
										$secciones[2][2]['inicia'] = 9;
										$secciones[2][2]['termina'] = 10;
										$secciones[2][2]['cerrada'] = false;
										$secciones[3][0]['texto'] = "Coordinador de ACUDE (Actividades CUlturales y Deportivas)";
										$secciones[3][1]['inicia'] = 11;
										$secciones[3][1]['termina'] = 13;
										$secciones[3][1]['cerrada'] = true;
										$secciones[3][2]['inicia'] = 14;
										$secciones[3][2]['termina'] = 16;
										$secciones[3][2]['cerrada'] = false;
										$secciones[4][0]['texto'] = "Coordinadora del Departamento Psicopedagógico";
										$secciones[4][1]['inicia'] = 17;
										$secciones[4][1]['termina'] = 19;
										$secciones[4][1]['cerrada'] = true;
										$secciones[4][2]['inicia'] = 20;
										$secciones[4][2]['termina'] = 21;
										$secciones[4][2]['cerrada'] = false;
										$secciones[5][0]['texto'] = "Asistente del Director";
										$secciones[5][1]['inicia'] = 22;
										$secciones[5][1]['termina'] = 24;
										$secciones[5][1]['cerrada'] = true;
										$secciones[5][2]['inicia'] = 25;
										$secciones[5][2]['termina'] = 26;
										$secciones[5][2]['cerrada'] = false;
										$secciones[6][0]['texto'] = "Director";
										$secciones[6][1]['inicia'] = 27;
										$secciones[6][1]['termina'] = 29;
										$secciones[6][1]['cerrada'] = true;
										$secciones[6][2]['inicia'] = 30;
										$secciones[6][2]['termina'] = 30;
										$secciones[6][2]['cerrada'] = false;
										$total_secciones_internas[1] = 2;
										$total_secciones_internas[2] = 2;
										$total_secciones_internas[3] = 2;
										$total_secciones_internas[4] = 2;
										$total_secciones_internas[5] = 2;
										$total_secciones_internas[6] = 2;
										$total_secciones = 6;
										$total_preguntas = 30;
										unset($numero_pregunta);
										for ($contador_items=1; $contador_items<=$total_preguntas; $contador_items++) {
											$numero_pregunta[$contador_items] = "r" . str_pad($contador_items, 2, "0", STR_PAD_LEFT);
											$fondo[$contador_items] = "";
										}
										// Revisa todas las preguntas que deben de ser contestadas y que las preguntas abiertas no sean muy largas
										for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
											for ($contador_secciones_internas=1; $contador_secciones_internas<=$total_secciones_internas[$contador_secciones]; $contador_secciones_internas++) {
												for ($contador_items=$secciones[$contador_secciones][$contador_secciones_internas]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_secciones_internas]['termina']; $contador_items++) {
													if ($secciones[$contador_secciones][$contador_secciones_internas]['cerrada']) {
														if (!isset($_POST[$numero_pregunta[$contador_items]])) $evaluacion_incompleta = true;
													} else {
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
											<form name="Regresar" action="bach_evalua_coordinadores.php" method=post autocomplete="off" accept-charset="UTF-8">
												<?php
													for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
														for ($contador_secciones_internas=1; $contador_secciones_internas<=$total_secciones_internas[$contador_secciones]; $contador_secciones_internas++) {
															for ($contador_items=$secciones[$contador_secciones][$contador_secciones_internas]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_secciones_internas]['termina']; $contador_items++) {
																if ($secciones[$contador_secciones][$contador_secciones_internas]['cerrada']) {
																	if (isset($_POST[$numero_pregunta[$contador_items]])) {
																		echo "<input type=hidden name='" . $numero_pregunta[$contador_items] . "' value='" . $_POST[$numero_pregunta[$contador_items]] . "' />";
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
											$identificador = "#COADDI#";
											if (!stristr($_SESSION['zez_a_evaluados'], $identificador)) {
												// Registra la evaluación en la tabla de evaluaciones
												$orden_sql = "INSERT INTO bach_coaddi (carrera, grado, sexo, situacion";
												$orden_sql_parte2 = "";
												for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
													for ($contador_secciones_internas=1; $contador_secciones_internas<=$total_secciones_internas[$contador_secciones]; $contador_secciones_internas++) {
														for ($contador_items=$secciones[$contador_secciones][$contador_secciones_internas]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_secciones_internas]['termina']; $contador_items++) {
															$orden_sql .= ", " . $numero_pregunta[$contador_items];
															if ($secciones[$contador_secciones][$contador_secciones_internas]['cerrada']) {
																$orden_sql_parte2 .= "', '" . $_POST[$numero_pregunta[$contador_items]];
															} else {
																$orden_sql_parte2 .= "', '" . limpiarcampo($_POST[$numero_pregunta[$contador_items]]);
															}
														}
													}
												}
												$orden_sql .= ") VALUES ('". $_SESSION['zez_a_carrera'] . "', '" . $_SESSION['zez_a_grado'];
												$orden_sql .= "', '" . $_SESSION['zez_a_sexo'] . "', '" . $_SESSION['zez_a_situacion'];
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