<?php
	session_start();
	require_once 'lib/config.php';
	if (!isset($_SESSION['zez_a_nombre'])) {
		header('Location: salir.php');
	} elseif (strpos(" ACADEMICO DIRECTOR ADMINISTRADOR SUPERUSUARIO",$_SESSION['zez_a_nivel_acceso'])== FALSE) {
		if (date("Y-m-d") < $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) {
			header('Location: no_iniciada.php');
			exit();
		} elseif (date("Y-m-d") > $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin']) {
			header('Location: cerrada.php');
			exit();
		} else {
			header('Location: mis_evaluaciones.php');
			exit();
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
	if(!$parametros_validos) { header('Location: index.php'); }
	if (isset($parametros[1])) {
		$parametros_imprimir = "%%".$parametros[0]."¬".$parametros[1]."%%";
		$ref = "abrir('bach_reporte_titulares_impresora.php?parametros=" . urlencode(encriptar($parametros_imprimir)) . "');";
	} else { $ref = "confirm('Por favor elije un titular antes de mandar a imprimir.');"; }
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
						<?php echo "Reporte de titulares"; ?>
					</h1>
					<span><?php echo "Ciclo " . $ciclo['BACHILLERATO']; ?></span>
				</div>
				<div class="card-footer">
					<div class="seccion">Opciones del reporte</div>
					<div class="contenedor-interno">
						<?php
							$contador_de_titulares = 0;
							// Genera la orden SQL para hacer la consulta a la tabla de la evaluación general de licenciaturas
							$orden_sql = "SELECT DISTINCT nombre, grupo FROM bach_titulares ORDER BY grupo";
							// Ejecuta la consulta SQL
							$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
							if ($resultado_busqueda) {
								if (mysqli_num_rows($resultado_busqueda) > 0) { ?>
									<form name="profesorFrm" id="profesorFrm" action="bach_reporte_profesores.php" method="post" autocomplete="off" accept-charset="UTF-8" >
										<div class="input-group mb-3 input-group-sm">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><strong>Titular:</strong></span>
                      </div>
											<select name="parametros" class="custom-select" onChange="document.titularFrm.submit()">
												<?php
													if (!isset($parametros[1])) { echo "<option>Seleccione un titular...</option>"; }
													// Si hubo resultado obtiene los datos de la sesión y activa la bandera
													unset($listado_de_titulares);
													while($registro = mysqli_fetch_array($resultado_busqueda)) {
														$contador_de_titulares++;
														$listado_de_titulares[$registro['nombre']] = $registro['nombre'];
														echo "<option value='" . encriptar("%%".$parametros[0]."¬".$registro['nombre']."%%")."'";
														if (isset($parametros[1])) { if ($registro['nombre']==$parametros[1]) { echo " selected='selected'"; } }
														echo ">". substr($registro['grupo'],(strlen($registro['grupo'])-2),2) . " - " . $registro['nombre'] . "</option>";
													}
												?>
											</select>
										</div>
									</form>
									<?php
								}
								mysqli_free_result($resultado_busqueda);
							}
						?>
					</div>
				</div>
				<div class="card-body">
					<?php
						if ($contador_de_titulares>0) {
							if (isset($parametros[1])) {
								// ########### INICIO DE BLOQUE INICIALIZAR VARIABLES
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
								$texto_pregunta[11] = "La entrevista que tuvo contigo el titular fue en un ambiente de confianza y discreción";
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
								// ########### FIN DE BLOQUE INICIALIZAR VARIABLES
								// ########### INICIO BLOQUE RECUPERAR RESPUESTAS
								// Genera la orden SQL para hacer la consulta a la tabla de la evaluación general de licenciaturas
								$orden_sql = "SELECT * FROM bach_titulares WHERE nombre='" . $listado_de_titulares[$parametros[1]] . "'";
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
								// ########### FIN BLOQUE RECUPERAR RESPUESTAS
								// ########### INICIO DEL CUESTIONARIO
								?>
								<div class="seccion">TITULAR</div>
								<div class="contenedor-interno">
									<table>
										<?php
											// ============== INICIO BLOQUE SECCIONES
											for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
												echo "<tr>";
												echo "<th style='width:5%;'>" . a_romano($contador_secciones) . "</th>";
												echo "<th style='text-align:left;width:41%;'>" . $secciones[$contador_secciones][0]['texto'] . "</th>";
												echo "<th colspan='" . $valores_por_renglon . "' style='text-align:left;width:50%;'>&nbsp;</th>";
												echo "</tr>";
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
														} else { $rowspan = ""; }
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
															echo "<td$rowspan" . $fondo[$contador_items] . " style='width:41%;text-align:left;'>" . $texto_pregunta[$contador_items] . "</td>";
															for ($contador_respuestas=$inicio_contador_respuestas; $contador_respuestas<=$tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]; $contador_respuestas++) {
																$ancho = floor((50/$valores_por_renglon)) * $ancho_multiplicador;
																if (($contador_respuestas>1) AND ((($contador_respuestas-1+$incremento_respuestas) % $valores_por_renglon) == 0)) {
																	echo "</tr>";
																	echo "<tr>";
																}
																echo "<td$colspan" . $fondo[$contador_items] . " style='width:$ancho%;text-align:center;'>";
																echo "<a style='font-size:0.7em;'>" . $set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']][$contador_respuestas] . "</a><br />";
																echo "<b>" . round(($respuestas[$contador_items][$contador_respuestas] / $contador_evaluaciones) * 100, 2) . "%</b>";
																echo "</td>";
															}
														} elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="MULTIPLE") {
															echo "<td$rowspan" . $fondo[$contador_items] . " style='width:41%;text-align:left;'>" . $texto_pregunta[$contador_items] . "</td>";
															for ($contador_respuestas=$inicio_contador_respuestas; $contador_respuestas<=$tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]; $contador_respuestas++) {
																$ancho = floor((50/$valores_por_renglon)) * $ancho_multiplicador;
																if (($contador_respuestas>1) AND ((($contador_respuestas-1+$incremento_respuestas) % $valores_por_renglon) == 0)) {
																	echo "</tr>";
																	echo "<tr>";
																}
																echo "<td$colspan" . $fondo[$contador_items] . " style='width:$ancho%;text-align:center;'>";
																echo "<a style='font-size:0.7em;'>" . $set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']][$contador_respuestas] . "</a><br />";
																echo "<b>" . round(($respuestas[$contador_items][$set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']][$contador_respuestas]] / $contador_evaluaciones) * 100, 2) . "%</b>";
																echo "</td>";
															}
														} else {
															echo "<td style='width:41%;text-align:left;" . $fondo[$contador_items] . "'>";
															echo "" . $texto_pregunta[$contador_items] . "";
															echo "</td>";
															echo "<td$colspan" . $fondo[$contador_items] . " style='width:50%;text-align:left;'>";
															echo "<ul>";
															for ($contador_respuestas=0; $contador_respuestas<=count($respuestas[$contador_items])-1; $contador_respuestas++) {
																if ($respuestas[$contador_items][$contador_respuestas]!=NULL) {
																	echo "<li>" . $respuestas[$contador_items][$contador_respuestas] . "</li>";
																}
															}
															echo "</ol>";
															echo "</td>";
														}
														if (((($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]+$incremento_respuestas) * $ancho_multiplicador) % $valores_por_renglon) > 0) {
															echo "<td class='gris' colspan='" . ($valores_por_renglon - ((($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]+$incremento_respuestas) * $ancho_multiplicador) % $valores_por_renglon)) . "'>&nbsp;</td>";
														}
														echo "</tr>";
													}
													// ============== TERMINA BLOQUE ITEMS
												}
												// ============== TERMINA BLOQUE SECCIONES INTERNAS
											}
											// ============== TERMINA BLOQUE SECCIONES
										?>
									</table>
								</div>
								<?php
								// ########### FIN DEL CUESTIONARIO
							}
						} else { echo "<p>No hay titulares evaluados.</p>"; }
					?>
				</div>
			</label>
	<?php mysqli_close($base_de_datos); ?>
</html>