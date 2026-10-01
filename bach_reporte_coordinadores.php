<?php
	session_start();
	if (!isset($_SESSION['zez_a_nivel_acceso'])) { // Si no está logueado lo manda al inicio
		session_destroy();
		header('Location: index.php');
	} else { // Si sí está logueado...
		require_once 'lib/config.php'; ?>
		<html>
			<?php include_once HEADER; ?>
			<script type="text/javascript" src="lib/functions.js"></script>
			<body>
				<div class="container">
					<div class="card">
						<div class="card-header">
							<h1>
								<img src="lib/img/blanco.png" class="img-form-left" />
								Evaluación del Desempeño Docente y Servicios
							</h1>
							<span>Ciclo <?php echo $ciclo['BACHILLERATO']; ?> : COORDINADORES</span>
						</div>
						<div class="card-body">
							<?php
								if (($_SESSION['zez_a_nivel'] != "BACHILLERATO") AND ($_SESSION['zez_a_nivel_acceso'] != 30) AND ($_SESSION['zez_a_nivel_acceso'] < 100)) {
									// OJO: El número es el grupo mínimo que puede tener derecho a entrar
									// Si no tiene derecho a está página... 
									echo $_SESSION['zez_a_nombre']; ?> no estás autorizad<?php if ($_SESSION['zez_a_sexo']=="FEMENINO") { echo "a"; } else { echo "o"; } ?> para ingresar a esta página.<br /><br />
									Para regresar al menú principal haz clic <a href="index.php">aquí</a>. <?php
								} else { 
									// ##### Si sí tiene derecho a esta página ingresar código desde aquí #####
									// ########### INICIO DE BLOQUE INICIALIZAR VARIABLES
										unset($cerrada_total_valores);
										$cerrada_total_valores[1] = 5;
										unset($cerrada_valores);
										$cerrada_valores[1][1] = "Nunca";
										$cerrada_valores[1][2] = "Casi nunca";
										$cerrada_valores[1][3] = "A veces";
										$cerrada_valores[1][4] = "Casi siempre";
										$cerrada_valores[1][5] = "Siempre";
										$total_valores_cerrada = 1;
										unset($secciones);
										$secciones[1][0]['texto'] = "Coordinador Académico";
										$secciones[1][1]['inicia'] = 1;
										$secciones[1][1]['termina'] = 3;
										$secciones[1][1]['cerrada'] = 1;
										$secciones[1][2]['inicia'] = 4;
										$secciones[1][2]['termina'] = 5;
										$secciones[1][2]['cerrada'] = 0;
										$secciones[2][0]['texto'] = "Coordinador de Pastoral";
										$secciones[2][1]['inicia'] = 6;
										$secciones[2][1]['termina'] = 8;
										$secciones[2][1]['cerrada'] = 1;
										$secciones[2][2]['inicia'] = 9;
										$secciones[2][2]['termina'] = 10;
										$secciones[2][2]['cerrada'] = 0;
										$secciones[3][0]['texto'] = "Coordinador de ACUDE (Actividades CUlturales y Deportivas)";
										$secciones[3][1]['inicia'] = 11;
										$secciones[3][1]['termina'] = 13;
										$secciones[3][1]['cerrada'] = 1;
										$secciones[3][2]['inicia'] = 14;
										$secciones[3][2]['termina'] = 16;
										$secciones[3][2]['cerrada'] = 0;
										$secciones[4][0]['texto'] = "Coordinadora del Departamento Psicopedagógico";
										$secciones[4][1]['inicia'] = 17;
										$secciones[4][1]['termina'] = 19;
										$secciones[4][1]['cerrada'] = 1;
										$secciones[4][2]['inicia'] = 20;
										$secciones[4][2]['termina'] = 21;
										$secciones[4][2]['cerrada'] = 0;
										$secciones[5][0]['texto'] = "Asistente del Director";
										$secciones[5][1]['inicia'] = 22;
										$secciones[5][1]['termina'] = 24;
										$secciones[5][1]['cerrada'] = 1;
										$secciones[5][2]['inicia'] = 25;
										$secciones[5][2]['termina'] = 26;
										$secciones[5][2]['cerrada'] = 0;
										$secciones[6][0]['texto'] = "Director";
										$secciones[6][1]['inicia'] = 27;
										$secciones[6][1]['termina'] = 29;
										$secciones[6][1]['cerrada'] = 1;
										$secciones[6][2]['inicia'] = 30;
										$secciones[6][2]['termina'] = 30;
										$secciones[6][2]['cerrada'] = 0;
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
										unset($texto_pregunta);
										$texto_pregunta[1] = "La atención que has recibido del coordinador académico (Édgar Chans) para resolver conflictos o situaciones académicas ha sido excelente";
										$texto_pregunta[2] = "El trato del coordinador académico (Édgar Chans) hacia ti ha sido respetuoso";
										$texto_pregunta[3] = "Cuando has acudido con el coordinador académico (Édgar Chans) a plantearle algún problema o situación académica (calificaciones, exámenes, problemas con maestros-as, entre otros asuntos) se ha resuelto";
										$texto_pregunta[4] = "Desde tu apreciación, ¿qué funciones desempeña el coordinador académico (Édgar Chans)?";
										$texto_pregunta[5] = "¿Qué sugerencia(s), agradecimiento y/o petición harías al coordinador académico (Édgar Chans) para mejorar la atención a los alumnos?";
										$texto_pregunta[6] = "La atención que has recibido del coordinador de pastoral (Sarah Lagarda) para resolver conflictos o situaciones académicas ha sido excelente";
										$texto_pregunta[7] = "El trato del coordinador de pastoral (Sarah Lagarda) hacia ti ha sido respetuoso";
										$texto_pregunta[8] = "Las actividades pastorales (semana vocacional, misas, campamentos, campañas de solidaridad, entre otras) te han dejado aprendizajes importantes";
										$texto_pregunta[9] = "Desde tu apreciación, ¿qué funciones desempeña el coordinador de pastoral (Sarah Lagarda)?";
										$texto_pregunta[10] = "¿Qué sugerencia(s), agradecimiento y/o petición harías al coordinador de pastoral (Sarah Lagarda) para mejorar la atención a los alumnos?";
										$texto_pregunta[11] = "La atención que has recibido del coordinador de ACUDE (Miguel Ángel Navarro) para resolver conflictos o situaciones de esta área ha sido excelente";
										$texto_pregunta[12] = "El trato del coordinador de ACUDE (Miguel Ángel Navarro) hacia ti ha sido respetuoso";
										$texto_pregunta[13] = "Cuando has acudido con el coordinador de ACUDE (Miguel Ángel Navarro) a plantearle algún problema o situación (calificaciones, exámenes, problemas con maestros-as, entre otros asuntos) se ha resuelto";
										$texto_pregunta[14] = "Desde tu apreciación, ¿qué funciones desempeña el coordinador de ACUDE (Miguel Ángel Navarro)?";
										$texto_pregunta[15] = "¿Qué sugerencia(s), agradecimiento y/o petición harías al coordinador de ACUDE (Miguel Ángel Navarro) para mejorar la atención a los alumnos?";
										$texto_pregunta[16] = "De tus Actividades Culturales y Deportivas (ACUDE) ¿qué aspecto es necesario mejorar, mantener o agradecer del trabajo realizado por tu maestro-entrenador? Escribe el nombre del maestro y la actividad deportiva y/o cultural de la que deseas comentar algo";
										$texto_pregunta[17] = "La atención que has recibido de la coordinadora del departamento psicopedagógico (Yaneli Díaz) para tratar asuntos personales ha sido excelente";
										$texto_pregunta[18] = "El trato de la coordinadora del departamento psicopedagógico (Yaneli Díaz) hacia ti ha sido respetuoso";
										$texto_pregunta[19] = "Cuando has acudido con de la coordinadora del departamento psicopedagógico (Yaneli Díaz) a plantearle alguna situación personal has quedado satisfech$AuO";
										$texto_pregunta[20] = "Desde tu apreciación, ¿qué asuntos atiende el departamento psicopedagógico?";
										$texto_pregunta[21] = "¿Qué sugerencia(s), agradecimiento y/o petición harías a la coordinadora del psicopedagógico (Yaneli Díaz) para mejorar la atención a los alumnos en este servicio?";
										$texto_pregunta[22] = "La atención que has recibido de la asistente del director (Jessica Conde) ha sido excelente";
										$texto_pregunta[23] = "El trato de la asistente del director (Jessica Conde) hacia ti ha sido respetuoso";
										$texto_pregunta[24] = "Cuando has acudido con la asistente del director (Jessica Conde) a plantearle algún problema o situación (dudas, solicitar información, comunicación con tus papás, préstamo de materiales, entre otros asuntos) se ha resuelto";
										$texto_pregunta[25] = "Desde tu apreciación, ¿qué funciones desempeña de la asistente del director (Jessica Conde)?";
										$texto_pregunta[26] = "Qué sugerencia(s), agradecimiento y/o petición harías de la asistente del director (Jessica Conde) para mejorar la atención a los alumnos";
										$texto_pregunta[27] = "La atención que has recibido del Director (H. Hugo Pablo) ha sido excelente";
										$texto_pregunta[28] = "El trato de la asistente del Director (H. Hugo Pablo) hacia ti ha sido respetuoso";
										$texto_pregunta[29] = "Cuando has acudido con el Director (H. Hugo Pablo) a plantearle algún problema o situación (dudas, solicitar información, aclaración, permiso, conflictos con maestros-as, entre otros asuntos) se ha resuelto";
										$texto_pregunta[30] = "Qué sugerencia(s), agradecimiento y/o petición harías al Director (H. Hugo Pablo) para mejorar la atención a los alumnos";
										unset($respuestas);
										for ($contador_items=1; $contador_items<=$total_preguntas; $contador_items++) {
											$respuestas[$contador_items][0] = "";
											for ($contador_cerradas=1; $contador_cerradas<=$total_valores_cerrada; $contador_cerradas++) {
												for ($contador_opciones=1; $contador_opciones<=$cerrada_total_valores[$contador_cerradas]; $contador_opciones++) {
													$respuestas[$contador_items][$contador_cerradas][$contador_opciones] = 0;
												}
											}
										}
									// ########### FIN DE BLOQUE INICIALIZAR VARIABLES
									// ########### INICIO BLOQUE RECUPERAR RESPUESTAS
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
										// Genera la orden SQL para hacer la consulta a la tabla de la evaluación general de licenciaturas
										$orden_sql = "SELECT * ";
										$orden_sql .= "FROM bach_coaddi ";
										$orden_sql .= "ORDER BY grado";
										// Ejecuta la consulta SQL
										$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
										$total_respuestas = 0;
										if ($resultado_busqueda) {
											if (mysqli_num_rows($resultado_busqueda) > 0) {
												while($registro = mysqli_fetch_array($resultado_busqueda)) {
													$total_respuestas++;
													for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
														for ($contador_secciones_internas=1; $contador_secciones_internas<=$total_secciones_internas[$contador_secciones]; $contador_secciones_internas++) {
															for ($contador_items=$secciones[$contador_secciones][$contador_secciones_internas]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_secciones_internas]['termina']; $contador_items++) {
																if ($secciones[$contador_secciones][$contador_secciones_internas]['cerrada']>0) {
																	$respuestas[$contador_items][$secciones[$contador_secciones][$contador_secciones_internas]['cerrada']][$registro[$numero_pregunta[$contador_items]]]++;
																} else {
																	if ($registro[$numero_pregunta[$contador_items]] != "") {
																		if ($respuestas[$contador_items][0]!="") {
																			$respuestas[$contador_items][0] .= "<br />-------<br />";
																		}
																		$respuestas[$contador_items][0] .= $registro[$numero_pregunta[$contador_items]];
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
										// Cierra la BDD
										mysqli_close($base_de_datos);
									// ########### FIN DE BLOQUE RECUPERAR RESPUESTAS
									// ########### INICIO BLOQUE DESPLEGAR RESULTADOS
										if ($total_respuestas > 0) { ?>
											<form action="reportes.php" method="post">
												<input type="submit" value="Regresar" />
											</form>
											<hr />
											<table class="table table-bordered table-striped table-sm" style="margin-left:auto;margin-right:auto;width:100%;">
												<?php
													// ============== INICIO BLOQUE SECCIONES
														for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
															echo "<tr>";
															echo "<th style='width:5%;'>" . a_romano($contador_secciones) . "</th>";
															echo "<th style='text-align:left;width:45%;'>" . $secciones[$contador_secciones][0]['texto'] . "</th>";
															echo "<th colspan='" . $cerrada_total_valores[1] . "' style='text-align:left;width:50%;'>&nbsp;</th>";
															echo "</tr>";
															// ============== INICIO BLOQUE SECCIONES INTERNAS
															for ($contador_secciones_internas=1; $contador_secciones_internas<=$total_secciones_internas[$contador_secciones]; $contador_secciones_internas++) {
																// ============== INICIO BLOQUE ITEMS
																for ($contador_items=$secciones[$contador_secciones][$contador_secciones_internas]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_secciones_internas]['termina']; $contador_items++) {
																	echo "<tr>";
																	echo "<td style='background-color:#bfbfbf;font-weight:bold;width:5%;text-align:center;'>$contador_items</td>";
																	if ($secciones[$contador_secciones][$contador_secciones_internas]['cerrada']>0) {
																		echo "<td style='width:45%;" . $fondo[$contador_items] . "'>" . $texto_pregunta[$contador_items] . "</td>";
																		for ($contador_respuestas=1; $contador_respuestas<=$cerrada_total_valores[1]; $contador_respuestas++) {
																			if ($contador_respuestas<$cerrada_total_valores[1]) {
																				$ancho = round((50/$cerrada_total_valores[1]), 0, PHP_ROUND_HALF_DOWN);
																			} else {
																				$ancho = 50 - (round((50/$cerrada_total_valores[1]), 0, PHP_ROUND_HALF_DOWN) * ($cerrada_total_valores[1] - 1));
																			}
																			echo "<td style='width:$ancho%;text-align:center;" . $fondo[$contador_items] . "'>";
																			echo "" . $cerrada_valores[1][$contador_respuestas] . "";
																			echo "<br />";
																			echo "" . round((($respuestas[$contador_items][$secciones[$contador_secciones][$contador_secciones_internas]['cerrada']][$contador_respuestas] / $total_respuestas) * 100), 2, PHP_ROUND_HALF_DOWN) . "%";
																		}
																	} else {
																		echo "<td style='width:45%;" . $fondo[$contador_items] . "'>";
																		echo "" . $texto_pregunta[$contador_items] . "<br />";
																		echo "</td>";
																		echo "<td colspan='" . $cerrada_total_valores[1] . "' style='width:50%;text-align:left;" . $fondo[$contador_items] . "'>";
																		echo "" . $respuestas[$contador_items][0] . "";
																	}
																	echo "</td>";
																	echo "</tr>";
																}
																// ============== TERMINA BLOQUE ITEMS
															}
															// ============== TERMINA BLOQUE SECCIONES INTERNAS
														}
													// ============== TERMINA BLOQUE SECCIONES
												?>
											</table> <?php
										} else { ?>
											<h3>SIN RESPUESTAS</h3>
											<p>No se han obtenido respuestas sobre este tema.</p>
											<br /> <?php
										}
									// ########### FIN BLOQUE DESPLEGAR RESULTADOS
								} 
								// ##### Si sí tiene derecho a esta página ingresar código hasta aquí ##### 
							?>
							<br />
							<hr />
							<br />
							<form action="reportes.php" method="post">
								<input type="submit" value="Regresar" />
							</form>
						</div>
					</div>
				</div>
			</body>
		</html> <?php
	}
?>