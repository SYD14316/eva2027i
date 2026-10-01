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
	}
	$post_carrera = $parametros[1];
	$post_evaluador = $parametros[2];
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
	// Establece funciones
	if ($_SESSION['zez_a_sexo']=="FEMENINO") {$AuO = "a";} else {$AuO = "o";}
?>
<html>
	<?php include_once HEADER; ?>
	<script type="text/javascript" src="lib/functions.js"></script>
	<body class="formulario-maristas">
	<body>
		<div class="container">
			<div class="card">
				<div class="card-header">
					<h1>
						<img src="lib/img/blanco.png" class="img-form-left" />
						Reporte de Formación Integral
					</h1>
					<span>Ciclo <?php echo $ciclo['LICENCIATURA']; ?></span>
				</div>
				<div class="card-body">
					<?php
						// █▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀█
						// █ ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ INICIA TABLA DE RESULTADOS ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ █
						// █▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄█
						// ┌────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
						// │                                                           INICIALIZAR VARIABLES                                                            │
						// └────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘
						if ($_SESSION['zez_a_sexo']=="FEMENINO") { $AuO = "a"; } else { $AuO = "o"; }
						unset($tamano_set_valores);
						unset($set_valores);
						unset($secciones);
						unset($numero_pregunta);
						unset($numero_comentario);
						unset($texto_pregunta);	
						unset($respuestas);
						require_once("lic_fidco.php");
						for ($contador_items=1; $contador_items<=$total_reactivos; $contador_items++) {
							$numero_pregunta[$contador_items] = "r" . str_pad($contador_items, 2, "0", STR_PAD_LEFT);
							$fondo[$contador_items] = "";
							$numero_comentario[$contador_items] = $numero_pregunta[$contador_items] . "c";
							$fondo_comentario[$contador_items] = "";
						}
						$usar_contador_interno = false;
						for ($contador_secciones=1;$contador_secciones<=$total_secciones;$contador_secciones++) {
							for ($contador_subsecciones=0; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
								if ($secciones[$contador_secciones][$contador_subsecciones]['limitada']!="") $usar_contador_interno = true;
							}
						}
						// ┌────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
						// │                                                             MOSTRAR REACTIVOS                                                              │
						// └────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘
						// ============== INICIO BLOQUE SECCIONES
						$secciones_visibles = 0;
						$preguntas_visibles = 0;
						for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
							$secciones_visibles++;
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
							echo "<table  class='table table-bordered table-sm'>";
							// ============== INICIO BLOQUE SECCIONES INTERNAS
							for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
								// ============== INICIO BLOQUE ITEMS
								for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
									$preguntas_visibles++;
									echo "<tr>";
									$inicio_contador_respuestas = 1;
									$incremento_respuestas = 0;
									if (($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]+$incremento_respuestas)>$valores_por_renglon) {
										if ($secciones[$contador_secciones][$contador_subsecciones]['comentario']) {
											$rowspan1 = " rowspan='" . (ceil(($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]+$incremento_respuestas)/$valores_por_renglon)+1) . "'";;
											$rowspan2 = " rowspan='" . ceil(($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]+$incremento_respuestas)/$valores_por_renglon) . "'";
										} else {
											$rowspan1 = " rowspan='" . ceil(($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]+$incremento_respuestas)/$valores_por_renglon) . "'";
											$rowspan2 = $rowspan1;
										}
									} else {
										if ($secciones[$contador_secciones][$contador_subsecciones]['comentario']) {
											$rowspan1 = " rowspan='2'";
											$rowspan2 = "";
										} else {
											$rowspan1 = "";
											$rowspan2 = $rowspan1;
										}
									}
									echo "<td$rowspan1 class='gris' style='font-weight:bold;width:5%;text-align:center;'>";
									if($usar_contador_interno) { echo $preguntas_visibles; } else { echo $contador_items; }
									echo "</td>";
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
										if(is_numeric($set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']][1]['texto'])) {
											echo "<td" . $fondo[$contador_items] . " style='width:45%;text-align:left;'>" . preg_replace('/\s/', ' ', $texto_reactivo[$contador_items]) . "</td>";
											echo "<td colspan='$valores_por_renglon' style='text-align:center;'>";
											$orden_sql = "SELECT avg($numero_pregunta[$contador_items]) ";
											$orden_sql .= "FROM lic_fidco ";
											if ($parametros[0]=="GENERAL") {
												if ($post_evaluador=="--Alumnos--") {
													$orden_sql .= "WHERE carrera<>'PROFESORES'";
												} elseif ($post_evaluador=="--Profesores--") {
													$orden_sql .= "WHERE carrera='PROFESORES'";
												} else {
													$orden_sql .= "WHERE carrera<>'QWERTY'";
												}
											} else {
												$orden_sql .= "WHERE carrera='$post_carrera'";
											}
											if ($secciones[$contador_secciones][$contador_subsecciones]['cero']=="NA") {
												$orden_sql .= " AND " . $numero_pregunta[$contador_items] . "<>'0'";
											}
											$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
											if ($resultado_busqueda) {
												if (mysqli_num_rows($resultado_busqueda) > 0) {
													// Si hubo resultado genera la tabla
													while($registro = mysqli_fetch_array($resultado_busqueda)) {
														if ($registro[0]==0) {
															if ($secciones[$contador_secciones][$contador_subsecciones]['cero']=="NA") {
																echo "NA";
															} else {
																echo "" . number_format(0,1) . "";
															}
														} else {
															echo "" . number_format(round($registro[0],2),2) . "";
														}
													}
												} else {
													if ($secciones[$contador_secciones][$contador_subsecciones]['cero']=="NA") {
														echo "NA";
													} else {
														echo "" . number_format(0,1) . "";
													}
												}
												// Libera el conjunto de resultados
												mysqli_free_result($resultado_busqueda);
											} else {
												if ($secciones[$contador_secciones][$contador_subsecciones]['cero']=="NA") {
													echo "NA";
												} else {
													echo "" . number_format(0,1) . "";
												}
											}
											echo "</td>";
										} else {
											echo "<td$rowspan2" . $fondo[$contador_items] . " style='width:45%;text-align:left;'>" . preg_replace('/\s/', ' ', $texto_reactivo[$contador_items]) . "</td>";
											$orden_sql = "SELECT count($numero_pregunta[$contador_items]) ";
											$orden_sql .= "FROM lic_fidco ";
											if ($parametros[0]=="GENERAL") {
												if ($post_evaluador=="--Alumnos--") {
													$orden_sql .= "WHERE carrera<>'PROFESORES'";
												} elseif ($post_evaluador=="--Profesores--") {
													$orden_sql .= "WHERE carrera='PROFESORES'";
												} else {
													$orden_sql .= "WHERE carrera<>'QWERTY'";
												}
											} else {
												$orden_sql .= "WHERE carrera='$post_carrera'";
											}
											if ($secciones[$contador_secciones][$contador_subsecciones]['cero']=="NA") {
												$orden_sql .= " AND " . $numero_pregunta[$contador_items] . "<>'0'";
											}
											$total_respuestas = 0;
											$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
											if ($resultado_busqueda) {
												if (mysqli_num_rows($resultado_busqueda) > 0) {
													// Si hubo resultado genera la tabla
													while($registro = mysqli_fetch_array($resultado_busqueda)) {
														$total_respuestas = $registro[0];
													}
												}
												// Libera el conjunto de resultados
												mysqli_free_result($resultado_busqueda);
											}
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
												echo "<td$colspan style='width:$ancho%;text-align:center;'>";
												echo "<label for='". $numero_pregunta[$contador_items] . "o" . $contador_respuestas . "' style='display:block; margin:auto; font-size:80%;'>" . $set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']][$contador_respuestas]['texto'] . "</label>";
												$orden_sql = "SELECT count($numero_pregunta[$contador_items]) ";
												$orden_sql .= "FROM lic_fidco ";
												if ($parametros[0]=="GENERAL") {
													if ($post_evaluador=="--Alumnos--") {
														$orden_sql .= "WHERE carrera<>'PROFESORES'";
													} elseif ($post_evaluador=="--Profesores--") {
														$orden_sql .= "WHERE carrera='PROFESORES'";
													} else {
														$orden_sql .= "WHERE carrera<>'QWERTY'";
													}
												} else {
													$orden_sql .= "WHERE carrera='$post_carrera'";
												}
												$orden_sql .= " AND $numero_pregunta[$contador_items]='" . $set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']][$contador_respuestas]['valor'] . "'";
												$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
												if ($resultado_busqueda) {
													if (mysqli_num_rows($resultado_busqueda) > 0) {
														// Si hubo resultado genera la tabla
														while($registro = mysqli_fetch_array($resultado_busqueda)) {
															if(($registro[0]==0) OR ($total_respuestas==0)) {
																echo "0";
															} else {
																echo "" . (round($registro[0]/$total_respuestas, 2)*100) . "%";
															}
														}
													}
													// Libera el conjunto de resultados
													mysqli_free_result($resultado_busqueda);
												}
												echo "</td>";
											}
										}
									} elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="MULTIPLE") {
										echo "<td$rowspan2" . $fondo[$contador_items] . " style='width:45%;text-align:left;'>" . preg_replace('/\s/', ' ', $texto_reactivo[$contador_items]) . "</td>";
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
											echo "<td$colspan style='width:$ancho%;text-align:center;'>";
											echo "<label for='". $numero_pregunta[$contador_items] . "o" . $contador_respuestas . "' style='display:block; margin:auto; font-size:80%;'>" . $set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']][$contador_respuestas]['texto'] . "</label>";
											echo "<input type=checkbox name='" . $numero_pregunta[$contador_items] . "_" . $contador_respuestas . "' id='". $numero_pregunta[$contador_items] . "o" . $contador_respuestas . "' value='" . $set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']][$contador_respuestas]['texto'] . "'";
											if (isset($_POST[$numero_pregunta[$contador_items]."_".$contador_respuestas])) {
												echo " checked='cheked'";
											}
											echo " />";
											echo "</td>";
										}
									} else {
										echo "<td" . $fondo[$contador_items] . " style='width:45%;text-align:left;'>";
										echo "" . preg_replace('/\s/', ' ', $texto_reactivo[$contador_items]) . "<br />";
										echo "</td>";
										echo "<td$colspan style='width:50%;text-align:left;'>";
										$orden_sql = "SELECT $numero_pregunta[$contador_items] ";
										$orden_sql .= "FROM lic_fidco ";
										if ($parametros[0]=="GENERAL") {
											if ($post_evaluador=="--Alumnos--") {
												$orden_sql .= "WHERE carrera<>'PROFESORES'";
											} elseif ($post_evaluador=="--Profesores--") {
												$orden_sql .= "WHERE carrera='PROFESORES'";
											} else {
												$orden_sql .= "WHERE carrera<>'QWERTY'";
											}
										} else {
											$orden_sql .= "WHERE carrera='$post_carrera'";
										}
										$orden_sql .= " AND $numero_pregunta[$contador_items]<>'' ";
										$orden_sql .= "AND $numero_pregunta[$contador_items]<>'.' ";
										$orden_sql .= "AND $numero_pregunta[$contador_items]<>'N/A' ";
										$orden_sql .= "ORDER BY $numero_pregunta[$contador_items] ASC";
										$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
										if ($resultado_busqueda) {
											if (mysqli_num_rows($resultado_busqueda) > 0) {
												// Si hubo resultado genera la tabla
												while($registro = mysqli_fetch_array($resultado_busqueda)) {
													echo "<li>" . $registro[$numero_pregunta[$contador_items]] . "</li>";
												}
											}
											// Libera el conjunto de resultados
											mysqli_free_result($resultado_busqueda);
										}
										echo "</td>";
									}
									if ((((($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]+$incremento_respuestas) * $ancho_multiplicador) % $valores_por_renglon) > 0) AND (!is_numeric($set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']][1]['texto']))) {
										echo "<td class='gris' colspan='" . ($valores_por_renglon - ((($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]+$incremento_respuestas) * $ancho_multiplicador) % $valores_por_renglon)) . "'>&nbsp;</td>";
									}
									echo "</tr>";
									if ($secciones[$contador_secciones][$contador_subsecciones]['comentario']) {
										echo "<tr>";
										echo "<td" . $fondo_comentario[$contador_items] . " style='width:45%;text-align:left;'>";
										echo "Comentario sobre esta pregunta...<br />";
										echo "</td>";
										echo "<td colspan='$valores_por_renglon' style='width:50%;text-align:left;'>";
										$orden_sql = "SELECT " . $numero_pregunta[$contador_items] . "c ";
										$orden_sql .= "FROM lic_fidco ";
										if ($parametros[0]=="GENERAL") {
											if ($post_evaluador=="--Alumnos--") {
												$orden_sql .= "WHERE carrera<>'PROFESORES'";
											} elseif ($post_evaluador=="--Profesores--") {
												$orden_sql .= "WHERE carrera='PROFESORES'";
											} else {
												$orden_sql .= "WHERE carrera<>'QWERTY'";
											}
										} else {
											$orden_sql .= "WHERE carrera='$post_carrera'";
										}
										$orden_sql .= " AND " . $numero_pregunta[$contador_items] . "c<>'' ";
										$orden_sql .= "AND " . $numero_pregunta[$contador_items] . "c<>'.' ";
										$orden_sql .= "AND " . $numero_pregunta[$contador_items] . "c<>'N/A' ";
										$orden_sql .= "ORDER BY " . $numero_pregunta[$contador_items] . "c ASC";
										$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
										if ($resultado_busqueda) {
											if (mysqli_num_rows($resultado_busqueda) > 0) {
												// Si hubo resultado genera la tabla
												while($registro = mysqli_fetch_array($resultado_busqueda)) {
													echo "<li>" . $registro[$numero_pregunta[$contador_items] . "c"] . "</li>";
												}
											}
											// Libera el conjunto de resultados
											mysqli_free_result($resultado_busqueda);
										}
										echo "</td>";
										echo "</tr>";
									}
								}
								// ============== TERMINA BLOQUE ITEMS
							}
							// ============== TERMINA BLOQUE SECCIONES INTERNAS
							echo "</table>";
							echo "</div>";
						}
						// ============== TERMINA BLOQUE SECCIONES
						// █▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀█
						// █ ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ TERMINA TABLA DE RESULTADOS ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ █
						// █▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄█
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