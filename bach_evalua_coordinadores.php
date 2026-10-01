<?php
	session_start();
	require_once 'lib/config.php';
	if (!isset($_SESSION['zez_a_nombre'])) {
		header('Location: salir.php');
	} elseif (($_SESSION['zez_a_nivel'] != "BACHILLERATO") OR (!strpos(" ALUMNO PROFESOR COORDINADOR VICERRECTOR",$_SESSION['zez_a_nivel_acceso'])) OR (!$_POST) OR (date("Y-m-d") < $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) OR (date("Y-m-d") > $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin'])) {
		header('Location: index.php');
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
	// ########### INICIO DE BLOQUE INICIALIZAR VARIABLES
	if ($_SESSION['zez_a_sexo']=="FEMENINO") { $AuO = "a"; } else { $AuO = "o"; }
	unset($cerrada_total_valores);
	$cerrada_total_valores[1] = 5;
	unset($cerrada_valores);
	$cerrada_valores[1][1] = "Nunca";
	$cerrada_valores[1][2] = "Casi nunca";
	$cerrada_valores[1][3] = "A veces";
	$cerrada_valores[1][4] = "Casi siempre";
	$cerrada_valores[1][5] = "Siempre";
	unset($secciones);
	$secciones[1][0]['texto'] = "Coordinador Académico";
	$secciones[1][1]['inicia'] = 1;
	$secciones[1][1]['termina'] = 3;
	$secciones[1][1]['cerrada'] = true;
	$secciones[1][2]['inicia'] = 4;
	$secciones[1][2]['termina'] = 5;
	$secciones[1][2]['cerrada'] = false;
	$secciones[2][0]['texto'] = "Coordinadora de Pastoral";
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
	$secciones[4][0]['texto'] = "Coordinador del Departamento Psicopedagógico";
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
	// ########### FIN DE BLOQUE INICIALIZAR VARIABLES
	// ########### INICIO BLOQUE RECUPERAR RESPUESTAS
	if (isset($_POST['regresada'])) {
		for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
			for ($contador_secciones_internas=1; $contador_secciones_internas<=$total_secciones_internas[$contador_secciones]; $contador_secciones_internas++) {
				if ($secciones[$contador_secciones][$contador_secciones_internas]['cerrada']) {
					for ($contador_items=$secciones[$contador_secciones][$contador_secciones_internas]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_secciones_internas]['termina']; $contador_items++) {
						if (isset($_POST[$numero_pregunta[$contador_items]])) {
							$fondo[$contador_items] = "";
						} else {
							$fondo[$contador_items] = " class='rojo'";
						}
					}
				} else {
					for ($contador_items=$secciones[$contador_secciones][$contador_secciones_internas]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_secciones_internas]['termina']; $contador_items++) {
						$respuestas[$contador_items] = $_POST[$numero_pregunta[$contador_items]];
						if (mb_strlen(pone_comillas($respuestas[$contador_items]), 'UTF-8') > $maximo_caracteres) {
							$fondo[$contador_items] = " class='amarillo'";
						}
					}
				}
			}
		}
	}
	// ########### FIN BLOQUE RECUPERAR RESPUESTAS
	// ########### INICIO DEL CUESTIONARIO
?>

<html>
	<?php include_once HEADER; ?>
	<script type="text/javascript" src="lib/functions.js"></script>

	<div class="d-flex fondo-azul-marista">
		<div><a class="btn text-light" onclick="redirigir('mis_evaluaciones.php#evaluaciones');"><strong>Mis Evaluaciones</strong></a></div>
		<div><a class="btn btn-warning text-dark"><strong>Evaluación Actual</strong></a></div>
			<?php
				if (strpos(" COORDINADOR VICERRECTOR",$_SESSION['zez_a_nivel_acceso'])!= FALSE) { ?>
						<div><a class="btn text-light" onclick="redirigir('mis_reportes.php');"><strong>Mis Reportes</strong></a></div><?php
				}
			?>
		<div class="ml-auto">
			<a class="btn text-light" onclick="redirigir('salir.php');"><strong>Salir</strong></a>
		</div>
	</div>

	<body>
		<div class="container">
			<div class="card">
				<div class="card-header">
					<h1>
						<img src="lib/img/blanco.png" class="img-form-left" />
						Coordinadores, Asistente de Dirección y Director
					</h1>
				</div>
				<div class="card-body">
					<?php
						if (isset($_POST['regresada'])) {
							if (strpos($_POST['regresada'], "Incompleta") !== false) {
								echo "<p class='rojo'>Necesitamos que completes tu evaluación contestando las preguntas marcadas en rojo, gracias.</p><br />";
							}
							if (strpos($_POST['regresada'], "Larga") !== false) {
								echo "<p class='rojo'>Por favor reduce el tamaño de las respuestas marcadas en amarillo, gracias.</p><br />";
							}
						}
					?>
					<form action="bach_procesa_evaluacion_coordinadores.php" method=post autocomplete="off" accept-charset="UTF-8" onsubmit="return confirm('¿Confirmas que quieres enviar esta evaluación?')">
						<?php
							// ============== INICIO BLOQUE SECCIONES
							for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
								if ($secciones[$contador_secciones][0]['texto']!="") {
									echo "<div class='seccion'><span>" . a_romano($contador_secciones) . "</span>" . $secciones[$contador_secciones][0]['texto'] . "</div>";
								}
								echo "<div class='contenedor-interno'>";
								echo "<table class='table table-bordered table-sm'>";
								// ============== INICIO BLOQUE SECCIONES INTERNAS
								for ($contador_secciones_internas=1; $contador_secciones_internas<=$total_secciones_internas[$contador_secciones]; $contador_secciones_internas++) {
									// ============== INICIO BLOQUE ITEMS
									for ($contador_items=$secciones[$contador_secciones][$contador_secciones_internas]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_secciones_internas]['termina']; $contador_items++) {
										echo "<tr>";
										echo "<td class='gris'style='font-weight:bold;width:5%;text-align:center;'>$contador_items</td>";
										if ($secciones[$contador_secciones][$contador_secciones_internas]['cerrada']) {
											echo "<td" . $fondo[$contador_items] . " style='width:45%;text-align:left;'>" . $texto_pregunta[$contador_items] . "</td>";
											for ($contador_respuestas=1; $contador_respuestas<=$cerrada_total_valores[1]; $contador_respuestas++) {
												if ($contador_respuestas<$cerrada_total_valores[1]) {
													$ancho = round((50/$cerrada_total_valores[1]), 0, PHP_ROUND_HALF_DOWN);
												} else {
													$ancho = 50 - (round((50/$cerrada_total_valores[1]), 0, PHP_ROUND_HALF_DOWN) * ($cerrada_total_valores[1] - 1));
												}
												echo "<td style='width:$ancho%;text-align:center;'>";
												echo "
													<div class='custom-control custom-radio'>
														<input type='radio' class='custom-control-input' name='" . $numero_pregunta[$contador_items] . "' id='". $numero_pregunta[$contador_items] . "o" . $contador_respuestas . "' value=" . $contador_respuestas;
															if (isset($_POST[$numero_pregunta[$contador_items]])) {
																if ($_POST[$numero_pregunta[$contador_items]]==$contador_respuestas) {
																	echo " checked='yes'";
																}
															}echo "
														/>
														<label class='custom-control-label' for='". $numero_pregunta[$contador_items] . "o" . $contador_respuestas . "'>" . $cerrada_valores[1][$contador_respuestas] . "</label>
													</div>
												";
											}
										} else {
											echo "<td" . $fondo[$contador_items] . " style='width:45%;text-align:left;'>";
											echo "" . $texto_pregunta[$contador_items] . "<br />";
											if (isset($_POST['regresada'])) {
												if (strpos($_POST['regresada'], "Larga") !== false) {
													echo "(actualmente la respuesta tiene " . mb_strlen(pone_comillas($respuestas[$contador_items]), 'UTF-8') . " caracteres y el máximo permitido es de $maximo_caracteres)";
												} else {
													echo "(máximo $maximo_caracteres caracteres)";
												}
											} else {
												echo "(máximo $maximo_caracteres caracteres)";
											}
											echo "</td>";
											echo "<td colspan='" . $cerrada_total_valores[1] . "' style='width:50%;text-align:left;'>";

											echo "<textarea name='" . $numero_pregunta[$contador_items] . "' class='form-control'>";
											if (isset($_POST['regresada'])) {
												echo pone_comillas($respuestas[$contador_items]);
											}
											echo "</textarea>";
										}
										echo "</td>";
										echo "</tr>";
									}
									// ============== TERMINA BLOQUE ITEMS
								}
								// ============== TERMINA BLOQUE SECCIONES INTERNAS
								echo "</table>";
								echo "</div>";
							}
							// ============== TERMINA BLOQUE SECCIONES
						?>
						<p align="center"><input type=submit value="ENVIAR LA EVALUACIÓN" style="display:block; margin:auto;" /></p>
					</form>
				</div>
				<?php mysqli_close($base_de_datos); ?>
			</div>
		</div>
	</body>
</html>