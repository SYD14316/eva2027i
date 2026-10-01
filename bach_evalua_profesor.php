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
?>
<html>
	<?php include_once HEADER; ?>
	<script type="text/javascript" src="lib/functions.js"></script>

	<div class="d-flex fondo-azul-marista">
		<div><a class="btn text-light" onclick="redirigir('mis_evaluaciones.php#evaluaciones');"><strong>Mis Evaluaciones</strong></a></div>
		<div><a class="btn btn-warning text-dark"><strong>Evaluación Actual</strong></a></div>

			<?php
				if (strpos(" COORDINADOR VICERRECTOR FIDCO",$_SESSION['zez_a_nivel_acceso'])!= FALSE) { ?>
						<div><a class="btn btn-warning text-dark" onclick="redirigir('mis_reportes.php');"><strong>Mis Reportes</strong></a></div><?php
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
						<?php echo $_POST['prof_nombre']; ?>
					</h1>
					<span><?php echo $_POST['prof_materia']; ?></span>
				</div>
				<div class="card-body">
					<?php
						// ########### INICIO DE BLOQUE INICIALIZAR VARIABLES
							if ($_SESSION['zez_a_sexo']=="FEMENINO") { $AuO = "a"; } else { $AuO = "o"; }

							if ($_POST['prof_sexo']=="MASCULINO") {
								$LAoEL = "el";
								$LAoEL_mayus = "El";
								$profe_o_profa = "del profesor";
								$AuOprof = "o";
							} else {
								$LAoEL = "la";
								$LAoEL_mayus = "La";
								$profe_o_profa = "de la profesora";
								$AuOprof = "a";
							}

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
							$texto_pregunta[1] = "$LAoEL_mayus docente inicia y finaliza puntualmente cada una de sus clases o sesiones de trabajo.";
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
							$texto_pregunta[12] = "¿Qué aspectos consideras que $LAoEL docente podría mejorar para fortalecer el aprendizaje y desarrollo de sus alumnos? (Sé específico/a: piensa en explicaciones, dinámicas, trato, claridad, organización, etc. Házlo de forma objetiva.)";
							$texto_pregunta[13] = "¿Cuáles son las principales cualidades y fortalezas $LAoEL docente que contribuyen al aprendizaje y desarrollo de sus alumnos? (Piensa en su forma de enseñar, su trato, su actitud, su manera de acompañarte, etc.)";
							unset($respuestas);
						// ########### FIN DE BLOQUE INICIALIZAR VARIABLES
						// ########### INICIO BLOQUE RECUPERAR RESPUESTAS
							if (isset($_POST['regresada'])) {
								for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
									for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
										if ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="CERRADA") {
											for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
												if (isset($_POST[$numero_pregunta[$contador_items]])) {
													$fondo[$contador_items] = "";
												} else {
													$fondo[$contador_items] = " class='rojo'";
												}
											}
										} elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="ABIERTA") {
											for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
												$respuestas[$contador_items] = $_POST[$numero_pregunta[$contador_items]];
												if (mb_strlen(pone_comillas($respuestas[$contador_items]), 'UTF-8') > $maximo_caracteres) {
													$fondo[$contador_items] = " class='amarillo'";
												}
											}
										}
									}
								}
								if (strpos($_POST['regresada'], "Incompleta") !== false) {
									echo "<p class='rojo'>Necesitamos que completes tu evaluación contestando las preguntas marcadas en rojo, gracias.</p><br />";
								}
								if (strpos($_POST['regresada'], "Larga") !== false) {
									echo "<p class='rojo'>Por favor reduce el tamaño de las respuestas marcadas en amarillo, gracias.</p><br />";
								}
							}
						// ########### FIN BLOQUE RECUPERAR RESPUESTAS
						// ########### INICIO DEL CUESTIONARIO ?>
							<p class="text-center"><strong>Contesta de forma responsable y honesta sobre el despempeño <?php echo $profe_o_profa; ?> que estás evaluando.</strong></p>
							<br />
							<br />
							<form action="bach_procesa_evaluacion_profesor.php" method=post autocomplete="off" accept-charset="UTF-8" onsubmit="return confirm('¿Confirmas que quieres enviar esta evaluación?')">
								<input type=hidden name="prof_id" value="<?php echo $_POST['prof_id']; ?>" />
								<input type=hidden name="prof_nivel" value="<?php echo $_POST['prof_nivel']; ?>" />
								<input type=hidden name="prof_grupo" value="<?php echo $_POST['prof_grupo']; ?>" />
								<input type=hidden name="prof_nombre" value="<?php echo $_POST['prof_nombre']; ?>" />
								<input type=hidden name="prof_materia" value="<?php echo $_POST['prof_materia']; ?>" />
								<input type=hidden name="prof_sexo" value="<?php echo $_POST['prof_sexo']; ?>" />
								<?php
									// ============== INICIO BLOQUE SECCIONES
										for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
											if ($secciones[$contador_secciones][0]['texto']!="") {
												echo "<div class='seccion'><span>" . a_romano($contador_secciones) . "</span>" . $secciones[$contador_secciones][0]['texto'] . "</div>";
											}
											echo "<div class='contenedor-interno'>";
											echo "<table class='table table-bordered table-sm'>";
														// ============== INICIO BLOQUE SECCIONES INTERNAS
															for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
																// ============== INICIO BLOQUE ITEMS
																	for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
																		echo "<tr>";
																		if ($secciones[$contador_secciones][$contador_subsecciones]['cero']=="") {
																			$inicio_contador_respuestas = 1;
																			$incremento_respuestas = 0;
																		} else {
																			$inicio_contador_respuestas = 0;
																			$incremento_respuestas = 1;
																		}
																		if (($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]+$incremento_respuestas)>$valores_por_renglon) {
																			$rowspan = " rowspan='" . ceil(($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]+$incremento_respuestas)/$valores_por_renglon) . "'";
																		} else {
																			$rowspan = "";
																		}
																		echo "<td$rowspan class='gris' style='font-weight:bold;width:5%;text-align:center;'>$contador_items</td>";
																		if (($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]+$incremento_respuestas)>=$valores_por_renglon) {
																			$ancho_multiplicador = 1;
																			$colspan = "";
																		} else {
																			if (floor($valores_por_renglon/($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]+$incremento_respuestas))>1) {
																				$ancho_multiplicador = floor($valores_por_renglon/($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]+$incremento_respuestas));
																				$colspan = " colspan='" . $ancho_multiplicador . "'";
																			} else {
																				$ancho_multiplicador = 1;
																				$colspan = "";
																			}
																		}
																		if ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="CERRADA") {
																			echo "<td$rowspan" . $fondo[$contador_items] . " style='width:45%;text-align:left;'>" . $texto_pregunta[$contador_items] . "</td>";
																			for ($contador_respuestas=$inicio_contador_respuestas; $contador_respuestas<=$tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]; $contador_respuestas++) {
																				if (($contador_respuestas+$incremento_respuestas)<$valores_por_renglon) {
																					$ancho = ceil((50/$valores_por_renglon)) * $ancho_multiplicador;
																				} else {
																					$ancho = 50 - (ceil((50/$valores_por_renglon)) * ($valores_por_renglon - 1));
																				}
																				if (($contador_respuestas>1) AND ((($contador_respuestas-1+$incremento_respuestas) % $valores_por_renglon) == 0)) {
																					echo "</tr>";
																					echo "<tr>";
																				}
																				echo "
																					<td$colspan style='width:$ancho%;text-align:center;'>
																						<div class='custom-control custom-radio'>
																							<input type='radio' class='custom-control-input' id='". $numero_pregunta[$contador_items] . "o" . $contador_respuestas . "' name='" . $numero_pregunta[$contador_items] . "' value=" . $contador_respuestas;
																								if (isset($_POST[$numero_pregunta[$contador_items]])) {
																									if ($_POST[$numero_pregunta[$contador_items]]==$contador_respuestas) {
																										echo " checked='yes'";
																									}
																								}echo " 
																							/>
	      																			<label class='custom-control-label' for='". $numero_pregunta[$contador_items] . "o" . $contador_respuestas . "'>" . $set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']][$contador_respuestas] . "</label>
	    																			</div>
    																			</td>
																				";
																			}
																		} elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="MULTIPLE") {
																			echo "<td$rowspan" . $fondo[$contador_items] . " style='width:45%;text-align:left;'>" . $texto_pregunta[$contador_items] . "</td>";
																			for ($contador_respuestas=$inicio_contador_respuestas; $contador_respuestas<=$tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]; $contador_respuestas++) {
																				if (($contador_respuestas+$incremento_respuestas)<$valores_por_renglon) {
																					$ancho = ceil((50/$valores_por_renglon)) * $ancho_multiplicador;
																				} else {
																					$ancho = 50 - (ceil((50/$valores_por_renglon)) * ($valores_por_renglon - 1));
																				}
																				if (($contador_respuestas>1) AND ((($contador_respuestas-1+$incremento_respuestas) % $valores_por_renglon) == 0)) {
																					echo "</tr>";
																					echo "<tr>";
																				}
																				echo "
																					<td$colspan style='width:$ancho%;text-align:center;'>
																						<div class='custom-control custom-checkbox mb-3'>
																				      <input type='checkbox' class='custom-control-input' id='". $numero_pregunta[$contador_items] . "o" . $contador_respuestas . "' name='" . $numero_pregunta[$contador_items] . "_" . $contador_respuestas . "' value='" . $set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']][$contador_respuestas] . "'";
																								if (isset($_POST[$numero_pregunta[$contador_items]."_".$contador_respuestas])) {
																									echo " checked='cheked'";
																								}echo " 
																							/>
																				      <label class='custom-control-label' for='". $numero_pregunta[$contador_items] . "o" . $contador_respuestas . "'>" . $set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']][$contador_respuestas] . "</label>
																				    </div>
																					</td>
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
																			echo "
																				</td>
																				<td$colspan style='width:50%;text-align:left;'>
																					<textarea name='" . $numero_pregunta[$contador_items] . "' class='form-control'>";
																						if (isset($_POST['regresada'])) {
																						echo pone_comillas($respuestas[$contador_items]);
																						} echo "</textarea>
																				</td>
																			";
																		}
																		if (((($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]+$incremento_respuestas) * $ancho_multiplicador) % $valores_por_renglon) > 0) {
																			echo "<td class='gris' colspan='" . ($valores_por_renglon - ((($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]+$incremento_respuestas) * $ancho_multiplicador) % $valores_por_renglon)) . "'>&nbsp;</td>";
																		}
																		echo "</tr>";
																	}
																// ============== TERMINA BLOQUE ITEMS
															}
														// ============== TERMINA BLOQUE SECCIONES INTERNAS
														echo "
													</table>
												</div>
											";
										}
									// ============== TERMINA BLOQUE SECCIONES
								?>
								<p align="center"><input type=submit class="btn btn-success btn-block" value="ENVIAR LA EVALUACIÓN" style="display:block; margin:auto;" /></p>
							</form> <?php
						// ########### FIN DEL CUESTIONARIO
						mysqli_close($base_de_datos);
					?>
				</div>
			</div>
		</div>
	</body>
</html>