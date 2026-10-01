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
	if ($_POST['prof_sexo']=="MASCULINO") { $LAoEL = "el"; } else { $LAoEL = "la"; }
	unset($tamano_set_valores);
	$tamano_set_valores[0] = 1;
	$tamano_set_valores[1] = 5;
	$tamano_set_valores[2] = 2;
	$tamano_set_valores[3] = 9;
	unset($set_valores);
	$set_valores[1][0] = "Insuficiente";
	$set_valores[1][1] = "Mínimamente";
	$set_valores[1][2] = "Regularmente";
	$set_valores[1][3] = "Bien";
	$set_valores[1][4] = "De muy buena manera";
	$set_valores[1][5] = "Excelentemente";
	$set_valores[2][0] = "No aplica";
	$set_valores[2][1] = "Sí";
	$set_valores[2][2] = "No";
	$set_valores[3][1] = "Justicia";
	$set_valores[3][2] = "Honestidad";
	$set_valores[3][3] = "Respeto";
	$set_valores[3][4] = "Amor al trabajo";
	$set_valores[3][5] = "Espíritu crítico";
	$set_valores[3][6] = "Sencillez en el trato";
	$set_valores[3][7] = "Humildad";
	$set_valores[3][8] = "Espíritu de familia";
	$set_valores[3][9] = "Responsabilidad";
	unset($secciones);
	$secciones[1][0]['texto'] = "";
	$secciones[1][1]['inicia'] = 1;
	$secciones[1][1]['termina'] = 9;
	$secciones[1][1]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
	$secciones[1][1]['set_valores'] = 1; // En abierta poner 0
	$secciones[1][1]['cero'] = "INS"; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
	$secciones[1][2]['inicia'] = 10;
	$secciones[1][2]['termina'] = 10;
	$secciones[1][2]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
	$secciones[1][2]['set_valores'] = 2; // En abierta poner 0
	$secciones[1][2]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
	$secciones[1][3]['inicia'] = 11;
	$secciones[1][3]['termina'] = 11;
	$secciones[1][3]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
	$secciones[1][3]['set_valores'] = 2; // En abierta poner 0
	$secciones[1][3]['cero'] = "NA"; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
	$secciones[1][4]['inicia'] = 12;
	$secciones[1][4]['termina'] = 18;
	$secciones[1][4]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
	$secciones[1][4]['set_valores'] = 1; // En abierta poner 0
	$secciones[1][4]['cero'] = "INS"; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
	$secciones[1][5]['inicia'] = 19;
	$secciones[1][5]['termina'] = 19;
	$secciones[1][5]['tipo'] = "MULTIPLE"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
	$secciones[1][5]['set_valores'] = 3; // En abierta poner 0
	$secciones[1][5]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
	$secciones[1][6]['inicia'] = 20;
	$secciones[1][6]['termina'] = 21;
	$secciones[1][6]['tipo'] = "ABIERTA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
	$secciones[1][6]['set_valores'] = 0; // En abierta poner 0
	$secciones[1][6]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
	$total_secciones = 1;
	$total_subsecciones[1] = 6;
	$total_preguntas = 21;
	$valores_por_renglon = 6;
	unset($numero_pregunta);
	for ($contador_items=1; $contador_items<=$total_preguntas; $contador_items++) {
		$numero_pregunta[$contador_items] = "r" . str_pad($contador_items, 2, "0", STR_PAD_LEFT);
		$fondo[$contador_items] = "";
	}
	unset($texto_pregunta);
	$texto_pregunta[1] = "Realiza una breve oración al iniciar la clase para recordar que estamos en la presencia de Dios.";
	$texto_pregunta[2] = "Hace reflexiones en diferentes momentos del ciclo escolar con respecto a la realidad de nuestro país y del mundo y nos impulsa a la solidaridad con personas más pobres.";
	$texto_pregunta[3] = "Imparte la clase de formación (TIHC, TFHC o TIS) desde una postura crítica y reflexiva que ayuda a nuestra formación humana y cristiana.";
	$texto_pregunta[4] = "Presenta con claridad la forma de evaluar la materia de formación (TIHC, TFHC o TIS) al inicio de cada período.";
	$texto_pregunta[5] = "Crea un ambiente adecuado (orden, trabajo, respeto, participación) en su clase (TIHC, TFHC o TIS) para que los alumnos aprendamos diferentes conocimientos, actitudes y valores.";
	$texto_pregunta[6] = "Retroalimenta los trabajos, exámenes y proyectos de forma precisa, clara y oportuna.";
	$texto_pregunta[7] = "Realiza actividades para la integración del grupo (dinámicas, actividades fuera del aula, campamento, etc.).";
	$texto_pregunta[8] = "Da aclaraciones a los alumnos sobre su calificación con una actitud de escucha.";
	$texto_pregunta[9] = "Revisa junto con el grupo la hoja de evaluación para llegar a acuerdos.";
	$texto_pregunta[10] = "Has sido entrevistado por tu titular por lo menos una vez en este semestre.";
	$texto_pregunta[11] = "La entrevista que tuvo contigo $LAoEL titular fue en un ambiente de confianza y discreción";
	$texto_pregunta[12] = "Responsabiliza a comisiones o a algún alumno los diferentes servicios en el grupo.";
	$texto_pregunta[13] = "Contribuye a la solución de conflictos personales y grupales.";
	$texto_pregunta[14] = "Fomenta en el grupo un ambiente estable en el que los alumnos se sienten respetados, valorados y apreciados.";
	$texto_pregunta[15] = "Mantiene una presencia cercana con los alumnos en espacios fuera de clase (recesos, juegos, pasillos, etc.).";
	$texto_pregunta[16] = "Facilita la reflexión en sus alumnos para que conozcan sus habilidades y actitudes.";
	$texto_pregunta[17] = "Informa oportunamente del código de convivencia, actividades y otras disposiciones del Bachillerato.";
	$texto_pregunta[18] = "Promueve la mejora del grupo con un plan, estableciendo metas.";
	$texto_pregunta[19] = "Señala qué valores maristas observas en tu titular de grupo.";
	$texto_pregunta[20] = "¿Qué te agrada de la forma de trabajo de tu titular de grupo?";
	$texto_pregunta[21] = "¿Qué le sugieres o pides a tu titular para mejorar su desempeño en esta función?";
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
						Evaluación <?php if ($_POST['prof_sexo']=="MASCULINO") { echo "del"; } else { echo "de la"; } ?> Titular
					</h1>
					<span><?php echo $_POST['prof_nombre']; ?></span>
				</div>
				<div class="card-body">
					<form action="bach_procesa_evaluacion_titular.php" method=post autocomplete="off" accept-charset="UTF-8" onsubmit="return confirm('¿Confirmas que quieres enviar esta evaluación?')">
						<input type=hidden name="prof_id" value="<?php echo $_POST['prof_id']; ?>" />
						<input type=hidden name="prof_grupo" value="<?php echo $_POST['prof_grupo']; ?>" />
						<input type=hidden name="prof_nombre" value="<?php echo $_POST['prof_nombre']; ?>" />
						<input type=hidden name="prof_sexo" value="<?php echo $_POST['prof_sexo']; ?>" />
						<?php
							// ============== INICIO BLOQUE SECCIONES
							for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
								if ($secciones[$contador_secciones][0]['texto']!="") {
									echo "<div class='seccion'><span>" . a_romano($contador_secciones) . "</span>" . $secciones[$contador_secciones][0]['texto'] . "</div>";
								}
								echo "<div class='contenedor-interno'>";
								echo "<table class='table table-bordered table-striped table-sm'>";
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
															<input type='radio' class='custom-control-input' name='" . $numero_pregunta[$contador_items] . "' id='". $numero_pregunta[$contador_items] . "o" . $contador_respuestas . "' value=" . $contador_respuestas;
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
												      <input type='checkbox' class='custom-control-input' name='" . $numero_pregunta[$contador_items] . "_" . $contador_respuestas . "' id='". $numero_pregunta[$contador_items] . "o" . $contador_respuestas . "' value='" . $set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']][$contador_respuestas] . "'";
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
								echo "</table>";
								echo "</div>";
							}
							// ============== TERMINA BLOQUE SECCIONES
						?>
						<p align="center"><input type=submit class="btn btn-success btn-block" value="ENVIAR LA EVALUACIÓN" style="display:block; margin:auto;" /></p>
					</form>
					<?php mysqli_close($base_de_datos); ?>
				</div>
			</div>
		</div>
	</body>
</html>