<?php
	session_start();
	require_once 'lib/config.php';
	if (!isset($_SESSION['zez_a_nombre'])) {
		header('Location: salir.php');
	} elseif (strpos(" ACADEMICO DIRECTOR REVISOR ADMINISTRADOR SUPERUSUARIO",$_SESSION['zez_a_nivel_acceso'])!= FALSE) {
		header('Location: mis_reportes.php');
	} elseif ((strpos(" COORDINADOR VICERRECTOR FIDCO",$_SESSION['zez_a_nivel_acceso'])!= FALSE) AND ((date("Y-m-d") < $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) OR (date("Y-m-d") > $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin']))) {
		header('Location: mis_reportes.php');
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
		<div><a class="btn btn-warning text-dark" onclick="redirigir('mis_evaluaciones.php');"><strong>Mis Evaluaciones</strong></a></div>

			<?php
				if (strpos(" COORDINADOR VICERRECTOR FIDCO",$_SESSION['zez_a_nivel_acceso'])!= FALSE) { ?>
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
						Mis Evaluaciones
					</h1>
					<span>Ciclo <?php echo $_SESSION['zez_a_ciclo']; ?></span>
				</div>
				<div class="card-body">
					<?php
						// Identificar el nivel
						if($_SESSION['zez_a_nivel'] == "BACHILLERATO") { 
							// ALUMNO
							// ============================================================== INICIA BACHILLERATO =================================================================== ?>
								<div class="seccion"><span>1</span>Indicaciones</div>
								<div class="contenedor-interno">
									<p>Estimad<?php if ($_SESSION['zez_a_sexo']=="FEMENINO") { echo "a"; } else { echo "o"; } ?> <?php echo $_SESSION['zez_a_nombre']; ?>:</p><br />
									<p>El departamento de Desarrollo Académico realiza una evaluación al docente con el objetivo de conocer y analizar aspectos significativos que puedan intervenir en el proceso enseñanza-aprendizaje y en el estudiante, para mejorar nuestra labor docente.</p><br />
									<p>Por tal motivo se necesita que respondas las preguntas con honestidad.</p><br />
									<p>De antemano agradecemos tu apoyo.</p>
								</div>
								<div class="seccion"><span>2</span><a id=evaluaciones>Evaluaciones</a></div>
								<div class="contenedor-interno">
									<table class='table table-bordered table-sm'>
										<tr>
											<th>PROFESOR</th> 
											<th>MATERIA</th>
											<th>ESTADO</th>
										</tr>
										<?php
											// Inicializa las variables usadas para los resultados
											$numero_de_evaluaciones = 0;
											$evaluaciones_mostradas = "";
											/*// ################# INICIA BLOQUE COORDINADORES, ASISTENTE DE DIRECCIÓN Y DIRECTOR
										?>
										<tr>
											<td class="gris"></td>
											<td>EVALUACIÓN DE COORDINADORES, ASISTENTE DE DIRECCIÓN Y DIRECTOR</td>
											<?php
												$identificador = "#COADDI#";
												if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
													// Si ya está evaluado pone "CONTESTADA" ?>
													<td class="completada">COMPLETADA</td> <?php
												} else {
													// Si no está evaluado pone el botón de evaluación ?>
													<td>
														<form action="bach_evalua_coordinadores.php" method=post style="margin-bottom:0em;">
															<input type=hidden name="x" value="x" />
															<input type=submit class='btn btn-primary' value="EVALUAR" />
														</form>
													</td><?php
												}
											?>
										</tr>
										<?php
											$numero_de_evaluaciones++; 
											// Agrega la evaluación al número de evaluaciones
											// ############## FINALIZA BLOQUE COORDINADORES, ASISTENTE DE DIRECCIÓN Y DIRECTOR*/
											/*/ ################# INICIA BLOQUE TITULAR
												foreach ($_SESSION['zez_a_grupos'] as $grupo) {
													$orden_sql = "SELECT * FROM titulares WHERE grupo='$grupo'";
													// Ejecuta la consulta SQL
													$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
													if ($resultado_busqueda) {
														if (mysqli_num_rows($resultado_busqueda) > 0) {
															// Si hubo resultado genera la tabla
															while($registro = mysqli_fetch_array($resultado_busqueda)) {
																$identificador = "#TITULAR" . $registro['id'] . "#";
																if (!stristr($evaluaciones_mostradas, $identificador)) {
																	$evaluaciones_mostradas = $evaluaciones_mostradas . $identificador;
																	echo "
																		<tr>
																			<td>" . $registro['nombre'] . "</td>
																			<td>EVALUACIÓN DEL DOCENTE TITULAR</td>";
																			if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
																				// Si ya está evaluado pone "CONTESTADA"
																				echo "<td class='completada'>COMPLETADA</td>";
																			} else {
																			// Si no está evaluado pone el botón de evaluación?>
																				<td>
																					<form action="bach_evalua_titular.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
																						<input type=hidden name="prof_id" value="<?php echo $registro['id']; ?>" />
																						<input type=hidden name="prof_grupo" value="<?php echo $registro['grupo']; ?>" />
																						<input type=hidden name="prof_nombre" value="<?php echo $registro['nombre']; ?>" />
																						<input type=hidden name="prof_sexo" value="<?php echo strtoupper($registro['sexo']); ?>" />
																						<input type=submit class='btn btn-primary' value="EVALUAR" />
																					</form>
																				</td><?php
																			}echo "
																		</tr>
																	";
																	// Agrega el último registro al conteo de evaluaciones totales
																	$numero_de_evaluaciones++;
																}
															}
														}
														// Libera el conjunto de resultados
														mysqli_free_result($resultado_busqueda);
													}
												}
											// ################# FINALIZA BLOQUE TITULAR*/
											// ################# INICIA BLOQUE MATERIAS
												foreach ($_SESSION['zez_a_grupos'] as $grupo) {
													// Genera la orden SQL para hacer la consulta a la tabla de profesores
													$orden_sql = "SELECT * FROM profesores WHERE grupo='" . $grupo . "'";
													// Ejecuta la consulta SQL
													$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
													if ($resultado_busqueda) {
														if (mysqli_num_rows($resultado_busqueda) > 0) {
															// Si hubo resultado genera la tabla
															while($registro = mysqli_fetch_array($resultado_busqueda)) {
																// Descartar ACUDE
																if (!stristr($registro['materia'], "ACTIVIDADES CULTURALES Y DEPORTIVAS")) {
																	$identificador = "#" . $registro['id'] . "#";
																	if (!stristr($evaluaciones_mostradas, $identificador)) {
																		$evaluaciones_mostradas = $evaluaciones_mostradas . $identificador;
																		echo "
																			<tr>
																				<td>" . $registro['nombre'] . "</td>
																				<td>" . $registro['materia'] . "</td>";
																				if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
																					// Si ya está evaluado pone "CONTESTADA"
																					echo "<td class='completada'>COMPLETADA</td>";
																				} else {
																					// Si no está evaluado pone el botón de evaluación?>
																					<td>
																						<form action="bach_evalua_profesor.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
																							<input type=hidden name="prof_id" value="<?php echo $registro['id']; ?>" />
																							<input type=hidden name="prof_nivel" value="<?php echo $registro['nivel']; ?>" />
																							<input type=hidden name="prof_grupo" value="<?php echo $registro['grupo']; ?>" />
																							<input type=hidden name="prof_nombre" value="<?php echo $registro['nombre']; ?>" />
																							<input type=hidden name="prof_materia" value="<?php echo $registro['materia']; ?>" />
																							<input type=hidden name="prof_sexo" value="<?php echo strtoupper($registro['sexo']); ?>" />
																							<input type=submit class='btn btn-primary' value="EVALUAR" />
																						</form>
																					</td><?php
																				}echo "
																			</tr>
																		";
																		// Agrega el último registro al conteo de evaluaciones totales
																		$numero_de_evaluaciones++;
																	}
																}
															}
														}
														// Libera el conjunto de resultados
														mysqli_free_result($resultado_busqueda);
													}
												}
											// ################# FINALIZA BLOQUE MATERIAS
											// ################# INICIA BLOQUE ACUDE
												$identificador = "#ACUDE#";
												if (!stristr($evaluaciones_mostradas, $identificador)) {
													$evaluaciones_mostradas = $evaluaciones_mostradas . $identificador;
													echo "
														<form action='bach_evalua_acude.php' method=post>
															<tr>
																<td class='gris'></td>
																<td>
																	ACTIVIDADES CULTURALES Y DEPORTIVAS";
																	if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
																		// Si ya está evaluado pone "CONTESTADA"
																		echo "
																			</td>
																			<td class='completada'>COMPLETADA</td>
																		";
																	} else {
																		echo "
																			<br />
																			<select name='actividad_id'>
																				<option value='0'>Selecciona tu actividad principal...</option>";
																				// Genera la orden SQL para hacer la consulta a la tabla de profesores
																				$orden_sql = "SELECT * FROM acude ORDER BY actividad, tipo, profesor";
																				// Ejecuta la consulta SQL
																				$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
																				if ($resultado_busqueda) {
																					if (mysqli_num_rows($resultado_busqueda) > 0) {
																						// Si hubo resultado genera la tabla
																						while($registro = mysqli_fetch_array($resultado_busqueda)) {
																							// Si no está evaluado pone el botón de evaluación
																							echo "<option value='" . $registro['id'] . "'>" . $registro['actividad'] . " / " . $registro['tipo'] . "</option>";
																						}
																					}
																					// Libera el conjunto de resultados
																					mysqli_free_result($resultado_busqueda);
																				}echo "
																			</select>
																			</td>
																			<td><input type=submit class='btn btn-primary' value='EVALUAR' /></td>
																		";
																		// Agrega el último registro al conteo de evaluaciones totales
																		$numero_de_evaluaciones++;
																	}echo "
															</tr>
														</form>
													";
												}
											// ################# TERMINA BLOQUE ACUDE
											// ################# INICIA BLOQUE SERVICIOS
										?>
										<tr>
											<td class="gris"></td>
											<td>EVALUACIÓN DE SATISFACCIÓN POR SERVICIOS</td>
											<?php
												$identificador = "#SERVICIOS#";
												if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
													// Si ya está evaluado pone "CONTESTADA"?>
													<td class="completada">COMPLETADA</td><?php
												} else {
													// Si no está evaluado pone el botón de evaluación?>
													<td>
														<form action="bach_evalua_general.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
															<input type=hidden name="x" value="x" />
															<input type=submit class='btn btn-primary' value="EVALUAR" />
														</form>
													</td><?php
												}
											?>
										</tr>
										<?php
											$numero_de_evaluaciones++;
											// Agrega la evaluación al número de evaluaciones
											// ############## FINALIZA BLOQUE SERVICIOS*/
										?>
									</table>
								</div><?php
							// ============================================================== TERMINA BACHILLERATO ===================================================================\\
						} else {
							// ============================================================== INICIA LICENCIATURA ====================================================================\\
								function poner_intro() {
									unset($logros_lic);
									require_once 'logros.php';
									echo "<p>¡Bienvenid";if ($_SESSION['zez_a_sexo']=="FEMENINO") { echo "a"; } else { echo "o"; }echo " a este proceso de Evaluación de los Profesores de Licenciatura!</p><br />";
									if(isset($logros_lic)) {
										echo "
										<p>Nos es muy grato en esta ocasión compartir contigo la satisfacción por los logros obtenidos en este semestre, en nuestra Universidad, en los siguientes eventos:</p>
											<ol type='1' start='1'>";
												for($contador=0;$contador<=(sizeof($logros_lic)-1);$contador++) {
													echo "<p><li>".$logros_lic[$contador]."</li></p>";
												}echo "
											</ol>
										<p>¡¡¡Felicidades a los alumnos y profesores que participan en estos concursos y  proyectos solidarios que honran a nuestra institución!!!</p><br />
										<p>Siguiendo en esta tónica de crecimiento y mejora, te solicitamos tu honesta y profunda participación en esta Evaluación Docente ".$_SESSION['zez_a_ciclo'].".</p><br />
										<p>Agradecemos mucho que leas cuidadosamente cada reactivo y que contestes con tu opinión de manera objetiva y añadiendo los comentarios que consideres pertinentes en cada uno de los rubros.</p><br />
										<p>Compartimos contigo la esperanza, dados los esfuerzos realizados, de obtener excelentes resultados al final de este semestre.</p><br />";
									} elseif(isset($solotexto_lic)) {
										for($contador=0;$contador<=(sizeof($solotexto_lic)-1);$contador++) {
											echo "<p>" . $solotexto_lic[$contador] . "</p><br />";
										}
									}
									echo "<p>Atentamente,</p>";
									if ($_SESSION['zez_a_nivel_acceso'] == "VICERRECTOR") { echo "<p>Desarrollo Académico</p>"; } else { echo "<p>$rubrica<br />Director de Excelencia Operacional $sexo_rubrica</p>"; }
								}
								function poner_indicaciones() {
									echo "
										<p>Solicitamos de tu apoyo respondiendo a los instrumentos de evaluación.</p><br />
										<p>Tu opinión y comentarios son para nosotros muy importantes, pues nos permiten detectar áreas de oportunidad, por ello te solicitamos contestar lo más sincera y objetivamente posible.</p>
									";
								}
								$contador_grupo_actual = 0;
								$numero_de_evaluaciones = 0;
								$evaluaciones_mostradas = "";
								if ($_SESSION['zez_a_nivel_acceso'] == "ALUMNO") {
									// ---------------------------------------------------------- INICIA ALUMNO LICENCIATURA ------------------------------------------------------------- \\
										// ############## INICIA BLOQUE INTRODUCCION ############## \\ ?>
											<div class="seccion"><span>1</span>Introducción</div>
											<div class="contenedor-interno">
												<p>Estimad<?php if ($_SESSION['zez_a_sexo']=="FEMENINO") { echo "a Alumna"; } else { echo "o Alumno"; } ?>:</p><br />
												<?php poner_intro(); ?>
											</div>
											<div class="seccion"><span>2</span>Indicaciones</div><?php
										// ############## FINALIZA BLOQUE INTRODUCCION ############## \\
										// ############## INICIA BLOQUE DE INDICACIONES ############## \\ ?>
											<div class="contenedor-interno"> <?php poner_indicaciones(); ?> </div><?php
										// ############## FINALIZA BLOQUE INDICACIONES ############## \\
										// ############## INICIA BLOQUE DE EVALUACIONES ############## \\ ?>
											<div class="seccion"><span>3</span><a id=evaluaciones>Evaluaciones</a></div>
											<div class="contenedor-interno">
												<table class='table table-bordered table-sm'>
													<tr>
														<th>PERSONA</th>
														<th>EVALUACION</th>
														<th>ESTADO</th>
													</tr><?php
													// ############## INICIA BLOQUE COORDINADOR DE CARRERA ############## \\
														$orden_sql = "SELECT * FROM carreras WHERE carrera='" . $_SESSION['zez_a_carrera'] . "'";
														// Ejecuta la consulta SQL
														$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
														if ($resultado_busqueda) {
															if (mysqli_num_rows($resultado_busqueda) > 0) {
																// Si hubo resultado genera la tabla
																while($registro = mysqli_fetch_array($resultado_busqueda)) {
																	$identificador = "#COORDINADOR#";
																	if (!stristr($evaluaciones_mostradas, $identificador)) {
																		if ($registro['sexo']=="FEMENINO") {
																			$titulo_seccion = "La coordinadora…";
																			$terminador_sexo = "a";
																		} else {
																			$titulo_seccion = "El coordinador…";
																			$terminador_sexo = "";
																		}
																		$evaluaciones_mostradas = $evaluaciones_mostradas . $identificador;
																		echo "
																			<tr>
																				<td>" . $registro['coordinador'] . "</td>
																				<td>COORDINACION DE CARRERA</td>";
																				if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
																					// Si ya está evaluado pone "CONTESTADA"
																					echo "<td class='completada'>COMPLETADA</td>";
																				} else {
																					// Si no está evaluado pone el botón de evaluación ?>
																					<td>
																						<form action="evalua.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
																							<input type=hidden name="parametros" value="<?php echo encriptar("%%lic_coordinadores_por_alumnos¬".$identificador."¬".$registro['coordinador']."¬".$titulo_seccion."¬".$terminador_sexo."%%"); ?>" />
																							<input type=submit class='btn btn-primary' value="EVALUAR" />
																						</form>
																					</td> <?php
																				} echo "
																			</tr>
																		";
																		// Agrega el último registro al conteo de evaluaciones totales
																		$numero_de_evaluaciones++;
																	}
																}
															}
															// Libera el conjunto de resultados
															mysqli_free_result($resultado_busqueda);
														}
													// ############## TERMINA BLOQUE COORDINADOR DE CARRERA ############## \\
													// ############## INICIA BLOQUE COORDINADOR DE MATERIAS INSTITUCIONALES ############## \\
														$orden_sql = "SELECT * FROM carreras WHERE carrera='MATERIAS INSTITUCIONALES'";
														// Ejecuta la consulta SQL
														$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
														if ($resultado_busqueda) {
															if (mysqli_num_rows($resultado_busqueda) > 0) {
																// Si hubo resultado genera la tabla
																while($registro = mysqli_fetch_array($resultado_busqueda)) {
																	$identificador = "#MATERIAS INSTITUCIONALES#";
																	if (!stristr($evaluaciones_mostradas, $identificador)) {
																		if ($registro['sexo']=="FEMENINO") {
																			$terminador_sexo = "a";
																			$trato1 = "La coordinadora…";
																			$trato2 = " la coordinadora";
																		} else {
																			$terminador_sexo = "";
																			$trato1 = "El coordinador…";
																			$trato2 = "l coordinador";
																		}
																		$evaluaciones_mostradas = $evaluaciones_mostradas . $identificador;
																		echo "
																			<tr>
																				<td>" . $registro['coordinador'] . "</td>
																				<td>COORDINACION DE MATERIAS INSTITUCIONALES</td>";
																				if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
																					// Si ya está evaluado pone "CONTESTADA"
																					echo "<td class='completada'>COMPLETADA</td>";
																				} else {
																					// Si no está evaluado pone el botón de evaluación ?>
																					<td>
																						<form action="evalua.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
																							<input type=hidden name="parametros" value="<?php echo encriptar("%%lic_materiasi_por_alumnos¬".$identificador."¬".$registro['coordinador']."¬".$terminador_sexo."¬".$trato1."¬".$trato2."%%"); ?>" />
																							<input type=submit class='btn btn-primary' value="EVALUAR" />
																						</form>
																					</td> <?php
																				} echo "
																			</tr>
																		";
																		// Agrega el último registro al conteo de evaluaciones totales
																		$numero_de_evaluaciones++;
																	}
																}
															}
															// Libera el conjunto de resultados
															mysqli_free_result($resultado_busqueda);
														}
													// ############## TERMINA BLOQUE COORDINADOR DE MATERIAS INSTITUCIONALES ############## \\
													// ############## INICIA BLOQUE PROFESORES ############## \\
														foreach ($_SESSION['zez_a_grupos'] as $grupo) {
															// Inicializa las variables usadas para los resultados
															$contador_grupo_actual++;
															// Genera la orden SQL para hacer la consulta a la tabla de profesores
															$orden_sql = "SELECT * FROM profesores WHERE grupo='" . $grupo . "'";
															// Ejecuta la consulta SQL
															$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
															if ($resultado_busqueda) {
																if (mysqli_num_rows($resultado_busqueda) > 0) {
																	// Si hubo resultado genera la tabla
																	while($registro = mysqli_fetch_array($resultado_busqueda)) {
																		$identificador = "#" . $registro['id'] . "#";
																		if (!stristr($evaluaciones_mostradas, $identificador)) {
																			$evaluaciones_mostradas = $evaluaciones_mostradas . $identificador;
																			echo "
																				<tr>
																					<td>" . $registro['nombre'] . "</td>
																					<td>" . $registro['materia'] . "</td>";
																					$identificador = "#" . $registro['id'] . "#";
																					if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
																						// Si ya está evaluado pone "COMPLETADA"
																						echo "<td class='completada'>COMPLETADA</td>";
																					} else {
																						// Si no está evaluado pone el botón de evaluación ?>
																						<td>
																							<form action="evalua.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
																								<input type=hidden name="parametros" value="<?php echo encriptar("%%lic_profesores_por_alumnos¬".$identificador."¬".$registro['nivel']."¬".$registro['grupo']."¬".$registro['nombre']."¬".$registro['materia']."¬".$registro['sexo']."¬".$registro['sello']."%%"); ?>" />
																								<input type=submit class='btn btn-primary' value="EVALUAR" />
																							</form>
																						</td> <?php
																					}echo "
																				</tr>
																			";
																			// Agrega el último registro al conteo de evaluaciones totales
																			$numero_de_evaluaciones++;
																		}
																	}
																}
																// Libera el conjunto de resultados
																mysqli_free_result($resultado_busqueda);
															}
														}
													// ############## TERMINA BLOQUE PROFESORES ############## \\
													// ############## INICIA BLOQUE INGLÉS ############## \\
														$identificador = "#INGLES#";
														if (!stristr($evaluaciones_mostradas, $identificador)) {
															$evaluaciones_mostradas = $evaluaciones_mostradas . $identificador;
															echo "
																<tr>
																	<form action='evalua.php' method=post accept-charset='UTF-8' style='margin-bottom:0em;'>
																		<td class='gris'>&nbsp;</td>
																		<td>";
																		if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
																			// Si ya está evaluado pone "CONTESTADA"
																			echo "
																				PROGRAMA DE APRENDIZAJE DE LENGUA EXTRANJERA</td>
																				<td class='completada'>COMPLETADA</td>
																			";
																		} else {
																			echo "
																				<p>PROGRAMA DE APRENDIZAJE DE LENGUA EXTRANJERA</p><br />
																				<p style='font-size:90%;'>Esta encuesta está dirigida a quienes durante este semestre estudiaron el inglés/alemán/francés en las instalaciones de la Universidad Marista de Guadalajara.</p><br />
																				<p style='font-size:90%;'>Si durante este semestre no estudiaste inglés por favor indícalo a continuación y presiona el botón CONTINUAR para tomar esta evaluación como completada...</p>
																				<br />
																				<select name='opcion_evaluacion'>
																					<option value='0'>Selecciona tu respuesta...</option>
																					<option value='EVALUAR'>Sí estudié inglés/alemán/francés en las instalaciones de la UMG</option>
																					<option value='SOLO_REGISTRAR'>No estudié inglés/alemán/francés en las instalaciones de la UMG</option>
																				</select>
																				<br />
																				</td>
																				<td>
																					<input type=hidden name='parametros' value='" . encriptar("%%lic_ingles¬".$identificador."%%") . "' />
																					<input type=submit class='btn btn-primary' value='CONTINUAR' />
																				</td>
																			";
																			// Agrega el último registro al conteo de evaluaciones totales
																			$numero_de_evaluaciones++;
																		}echo "
																	</form>
																</tr>
															";
														}
													// ############## TERMINA BLOQUE INGLÉS ############## \\
													// ################# INICIA BLOQUE 1 DE DEPORTE Y CULTURA ################# \\
														$identificador = "#CUyDE1#";
														if (!stristr($evaluaciones_mostradas, $identificador)) {
															$evaluaciones_mostradas = $evaluaciones_mostradas . $identificador;
															echo "
																<tr>
																	<form action='evalua_dyc.php' method=post accept-charset='UTF-8' style='margin-bottom:0em;'>
																		<td class='gris'>&nbsp;</td>
																		<td>";
																		if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
																			// Si ya está evaluado pone "CONTESTADA"
																			echo "
																				DEPORTE Y CULTURA (TALLER 1)</td>
																				<td class='completada'>COMPLETADA</td>
																			";
																		} else {
																			echo "
																				<p>DEPORTE Y CULTURA (TALLER 1)</p><br />
																				<select name='opcion_evaluacion'>
																					<option value='0'>Selecciona tu primer taller...</option>
																					<option value='SOLO_REGISTRAR'>NO TOMO NINGUN TALLER</option>";
																					// Genera la orden SQL para hacer la consulta a la tabla de profesores
																					$orden_sql = "SELECT * FROM `deporteycultura` ORDER BY `actividad`, `tipo`, `profesor`";
																					// Ejecuta la consulta SQL
																					$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
																					if ($resultado_busqueda) {
																						if (mysqli_num_rows($resultado_busqueda) > 0) {
																							// Si hubo resultado genera la tabla
																							while($registro = mysqli_fetch_array($resultado_busqueda)) {
																								// Si no está evaluado pone el botón de evaluación
																								echo "<option value='" . $registro['id'] . "'>" . $registro['actividad'] . " / " . $registro['tipo'] . "</option>";
																							}
																						}
																						// Libera el conjunto de resultados
																						mysqli_free_result($resultado_busqueda);
																					}echo "
																				</select>
																				<br />
																				</td>
																				<td>
																					<input type=hidden name='parametros' value='" . encriptar("%%lic_deporteycultura_p_por_alumnos¬".$identificador."%%") . "' />
																					<input type=submit class='btn btn-primary' value='EVALUAR' />
																				</td>
																			";
																			// Agrega el último registro al conteo de evaluaciones totales
																			$numero_de_evaluaciones++;
																		}echo "
																	</form>
																</tr>
															";
														}
													// ################# TERMINA BLOQUE 1 DE DEPORTE Y CULTURA ################# \\
													// ################# INICIA BLOQUE 2 DE DEPORTE Y CULTURA ################# \\
														$identificador = "#CUyDE2#";
														if (!stristr($evaluaciones_mostradas, $identificador)) {
															$evaluaciones_mostradas = $evaluaciones_mostradas . $identificador;
															echo "
																<tr>
																	<form action='evalua_dyc.php' method=post accept-charset='UTF-8' style='margin-bottom:0em;'>
																		<td class='gris'>&nbsp;</td>
																		<td>";
																		if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
																			// Si ya está evaluado pone "CONTESTADA"
																			echo "
																				DEPORTE Y CULTURA (TALLER 2)</td>
																				<td class='completada'>COMPLETADA</td>
																			";
																		} else {
																			echo "
																				<p>DEPORTE Y CULTURA (TALLER 2)</p><br />
																				<select name='opcion_evaluacion'>
																					<option value='0'>Selecciona tu segundo taller...</option>
																					<option value='SOLO_REGISTRAR'>NO TOMO UN SEGUNDO TALLER</option>";
																					// Genera la orden SQL para hacer la consulta a la tabla de profesores
																					$orden_sql = "SELECT * FROM `deporteycultura` ORDER BY `actividad`, `tipo`, `profesor`";
																					// Ejecuta la consulta SQL
																					$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
																					if ($resultado_busqueda) {
																						if (mysqli_num_rows($resultado_busqueda) > 0) {
																							// Si hubo resultado genera la tabla
																							while($registro = mysqli_fetch_array($resultado_busqueda)) {
																								// Si no está evaluado pone el botón de evaluación
																								if (!stristr($_SESSION['zez_a_evaluados'], '<'.$registro['id'].'>')) echo "<option value='" . $registro['id'] . "'>" . $registro['actividad'] . " / " . $registro['tipo'] . "</option>";
																							}
																						}
																						// Libera el conjunto de resultados
																						mysqli_free_result($resultado_busqueda);
																					}echo "
																				</select>
																				<br />
																				</td>
																				<td>
																			";
																			if (stristr($_SESSION['zez_a_evaluados'], "#CUyDE1")) {
																				echo "
																					<input type=hidden name='parametros' value='" . encriptar("%%lic_deporteycultura_p_por_alumnos¬".$identificador."%%") . "' />
																					<input type=submit class='btn btn-primary' value='EVALUAR' />
																				";
																			} else { echo "Contesta primero el Taller 1"; }
																			echo "</td>";
																			// Agrega el último registro al conteo de evaluaciones totales
																			$numero_de_evaluaciones++;
																		} echo "
																	</form>
																</tr>
															";
														}
													// ################# TERMINA BLOQUE 2 DE DEPORTE Y CULTURA ################# \\
													// ############## INICIA BLOQUE ESPACIOS, RECURSOS Y SERVICIOS ############## \\ ?>
														<tr>
															<td class="gris">&nbsp;</td>
															<td>ESPACIOS, RECURSOS Y SERVICIOS</td> <?php
															$identificador = "#ERyS#";
															$numero_de_evaluaciones++; // Agrega la evaluación general al número de evaluaciones
															if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
																// Si ya está evaluado pone "COMPLETADA"
																echo "<td class='completada'>COMPLETADA</td>";
															} else { ?>
																<td>
																	<form action="evalua.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
																		<input type=hidden name="parametros" value="<?php echo encriptar("%%lic_erys¬".$identificador."%%"); ?>" />
																		<input type=submit class='btn btn-primary' value="EVALUAR" />
																	</form>
																</td><?php
															} ?>
														</tr><?php
													// ############## TERMINA BLOQUE ESPACIOS, RECURSOS Y SERVICIOS ############## \\
													// ############## INICIA BLOQUE FIDCO (Formación Integral) ############## \\ ?>
														<tr>
															<td class="gris">&nbsp;</td>
															<td>FORMACION INTEGRAL</td><?php
															$identificador = "#FIDCO#";
															$numero_de_evaluaciones++; // Agrega la evaluación general al número de evaluaciones
															if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
																// Si ya está evaluado pone "COMPLETADA"
																echo "<td class='completada'>COMPLETADA</td>";
															} else { // Si no está evaluado pone el botón de evaluación ?>
																<td>
																	<form action="evalua.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
																		<input type=hidden name="parametros" value="<?php echo encriptar("%%lic_fidco¬".$identificador."%%"); ?>" />
																		<input type=submit class='btn btn-primary' value="EVALUAR" />
																	</form>
																</td><?php
															} ?>
														</tr><?php
													// ############## TERMINA BLOQUE FIDCO (Formación Integral) ############## \\ ?>
												</table>
											</div> <?php
										// ############## TERMINA BLOQUE DE EVALUACIONES ############## \\
									// ---------------------------------------------------------- TERMINA ALUMNO LICENCIATURA ------------------------------------------------------------ \\
								} elseif ($_SESSION['zez_a_nivel_acceso'] == "PROFESOR") {
									// ---------------------------------------------------------- INICIA PROFESOR LICENCIATURA ----------------------------------------------------------- \\
										// ############## INICIA BLOQUE INTRODUCCION ############## \\ ?>
											<div class="seccion"><span>1</span>Introducción</div>
											<div class="contenedor-interno">
												<p>Estimad<?php if ($_SESSION['zez_a_sexo']=="FEMENINO") { echo "a Profesora"; } else { echo "o Profesor"; } ?>:</p><br />
												<?php poner_intro(); ?>
											</div><?php
										// ############## FINALIZA BLOQUE INTRODUCCION ############## \\
										// ############## INICIA BLOQUE DE INDICACIONES ############## \\ ?>
											<div class="seccion"><span>2</span>Indicaciones</div>
											<div class="contenedor-interno">
												<?php poner_indicaciones(); ?>
											</div> <?php
										// ############## FINALIZA BLOQUE DE INDICACIONES ############## \\
										// ############## INICIA BLOQUE DE EVALUACIONES ############## \\ ?>
											<div class="seccion"><span>3</span><a id=evaluaciones>Evaluaciones</a></div>
											<div class="contenedor-interno">
												<table class='table table-bordered table-sm'>
													<tr>
														<th>CUESTIONARIO</th>
														<th>ESTADO</th>
													</tr> <?php
													// ############## INICIA BLOQUE AUTOEVALUACION
														echo "
															<tr>
																<td>AUTOEVALUACION</td>";
																$identificador = "#AUTOEVALUACION#";
																$numero_de_evaluaciones++; // Agrega la evaluación general al número de evaluaciones
																if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
																	// Si ya está evaluado pone "COMPLETADA"
																	echo "<td class='completada'>COMPLETADA</td>";
																} else {
																	// Si no está evaluado pone el botón de evaluación
																	if ('DEPORTE Y CULTURA'!=$_SESSION['zez_a_carrera']) {
																		$orden_sql = "SELECT DISTINCT `carrera` ";
																		$orden_sql .= "FROM `profesores` ";
																		$orden_sql .= "WHERE `nombre` = '" . $_SESSION['zez_a_nombre'] . "' ";
																		$orden_sql .= "AND `nivel` = '" . $_SESSION['zez_a_nivel'] . "' ";
																		$orden_sql .= "ORDER BY `carrera` ASC";
																		$carreras_profesor = "";
																		$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
																		if ($resultado_busqueda) {
																			if (mysqli_num_rows($resultado_busqueda) > 0) {
																				// Si hubo resultado genera la tabla
																				while($registro = mysqli_fetch_array($resultado_busqueda)) { $carreras_profesor .= " " . $registro['carrera']; }
																			}
																			mysqli_free_result($resultado_busqueda);
																		}
																		$destino = 'lic_profesores_por_profesores';
																	} else {
																		$carreras_profesor = ' DEPORTE Y CULTURA';
																		$destino = 'lic_deporteycultura_p_por_profesores';
																	} ?>
																	<td>
																		<form action="evalua.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
																			<input type=hidden name="parametros" value="<?php echo encriptar("%%".$destino."¬".$identificador."¬".$carreras_profesor."%%"); ?>" />
																			<input type=submit class='btn btn-primary' value="AUTOEVALUAR" />
																		</form>
																	</td> <?php
																}echo "
															</tr>
														";
													// ############## TERMINA BLOQUE AUTOEVALUACION ############## \\
													// ############## INICIA BLOQUE COORDINADOR(ES) ############## \\
														// Genera la orden SQL para hacer la consulta a la tabla de profesores
														if ('DEPORTE Y CULTURA'!=$_SESSION['zez_a_carrera']) {
															$orden_sql = "SELECT DISTINCT `carreras`.* ";
															$orden_sql .= "FROM `carreras` ";
															$orden_sql .= "INNER JOIN `profesores` ON `carreras`.`carrera`=`profesores`.`carrera` ";
															$orden_sql .= "WHERE `profesores`.`nombre` = '" . $_SESSION['zez_a_nombre'] . "' ";
															$orden_sql .= "AND `profesores`.`nivel` = '" . $_SESSION['zez_a_nivel'] . "' ";
															$orden_sql .= "AND `carreras`.`coordinador` <> '" . $_SESSION['zez_a_nombre'] . "'";
														} else {
															$orden_sql = "SELECT * ";
															$orden_sql .= "FROM `carreras` ";
															$orden_sql .= "WHERE `carrera`='DEPORTE Y CULTURA'";
														}
														// Ejecuta la consulta SQL
														$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
														if ($resultado_busqueda) {
															if (mysqli_num_rows($resultado_busqueda) > 0) {
																// Si hubo resultado genera la tabla
																while($registro = mysqli_fetch_array($resultado_busqueda)) {
																	$identificador = "#" . $registro['carrera'] . "#";
																	if ($registro['sexo']=="FEMENINO") {
																		$terminador_sexo = "a";
																		$trato1 = "La coordinadora…";
																		$trato2 = " la coordinadora";
																	} else {
																		$terminador_sexo = "";
																		$trato1 = "El coordinador…";
																		$trato2 = "l coordinador";
																	}
																	if ('0'==$registro['sello']) {
																		if ('DEPORTE Y CULTURA'!=$_SESSION['zez_a_carrera']) {
																			$parametros_evaluacion = encriptar("%%lic_coordinadores_por_profesores¬".$identificador."¬".$registro['carrera']."¬".$registro['coordinador']."¬".$terminador_sexo."¬".$trato1."%%");
																		} else { $parametros_evaluacion = encriptar("%%lic_deporteycultura_c_por_profesores¬".$identificador."¬".$registro['coordinador']."¬".$terminador_sexo."¬".$trato1."¬".$trato2."%%"); }
																	} else { $parametros_evaluacion = encriptar("%%lic_materiasi_por_profesores¬".$identificador."¬".$registro['coordinador']."¬".$terminador_sexo."¬".$trato1."¬".$trato2."%%"); }
																	if (!stristr($evaluaciones_mostradas, $identificador)) {
																		$evaluaciones_mostradas = $evaluaciones_mostradas . $identificador;
																		echo "
																			<tr>
																				<td>COORDINACION DE " . $registro['carrera'] . "</td>";
																				$numero_de_evaluaciones++; // Agrega la evaluación general al número de evaluaciones
																				if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
																					// Si ya está evaluado pone "COMPLETADA"
																					echo "<td class='completada'>COMPLETADA</td>";
																				} else {
																					// Si no está evaluado pone el botón de evaluación ?>
																					<td>
																						<form action="evalua.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
																							<input type=hidden name="parametros" value="<?php echo $parametros_evaluacion; ?>" />
																							<input type=submit class='btn btn-primary' value="EVALUAR" />
																						</form>
																					</td> <?php
																				} echo "
																			</tr>
																		";
																	}
																}
															}
															// Libera el conjunto de resultados
															mysqli_free_result($resultado_busqueda);
														}
													// ############## TERMINA BLOQUE COORDINADOR(ES) ############## \\
													// ############## INICIA BLOQUE ESPACIOS, RECURSOS Y SERVICIOS ############## \\
														if ('DEPORTE Y CULTURA'!=$_SESSION['zez_a_carrera']) { ?>
															<tr>
																<td>ESPACIOS, RECURSOS Y SERVICIOS</td><?php
																$identificador = "#ERyS#";
																$numero_de_evaluaciones++; // Agrega la evaluación general al número de evaluaciones
																if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
																	// Si ya está evaluado pone "COMPLETADA"
																	echo "<td class='completada'>COMPLETADA</td>";
																} else {
																	// Si no está evaluado pone el botón de evaluación ?>
																	<td>
																		<form action="evalua.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
																			<input type=hidden name="parametros" value="<?php echo encriptar("%%lic_erys_profesores¬".$identificador."%%"); ?>" />
																			<input type=submit class='btn btn-primary' value="EVALUAR" />
																		</form>
																	</td> <?php
																} ?>
															</tr> <?php
														}
													// ############## TERMINA BLOQUE ESPACIOS, RECURSOS Y SERVICIOS ############## \\ ?>
												</table>
											</div> <?php
										// ############## TERMINA BLOQUE DE EVALUACIONES ############## \\
									// ---------------------------------------------------------- TERMINA PROFESOR LICENCIATURA ----------------------------------------------------------
								} elseif (($_SESSION['zez_a_nivel_acceso'] == "COORDINADOR") OR ($_SESSION['zez_a_nivel_acceso'] == "FIDCO")) {
									// ---------------------------------------------------------- INICIA COORDINADOR LICENCIATURA --------------------------------------------------------
										// ############## INICIA BLOQUE INTRODUCCION ############## \\
											// Busca los departamentos en los que es jefe
											$orden_sql = "SELECT * ";
											$orden_sql .= "FROM `departamentos` ";
											$orden_sql .= "WHERE `jefe`='" . $_SESSION['zez_a_nombre'] . "'";
											// Ejecuta la consulta SQL
											$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql); ?>
											<div class="seccion"><span>1</span>Introducción</div>
											<div class="contenedor-interno">
												<p>Estimad<?php if ($_SESSION['zez_a_sexo']=="FEMENINO") { if (mysqli_num_rows($resultado_busqueda)>0) { echo "a Jefe de Departamento"; } else { echo "a Coordinadora"; } } else { if (mysqli_num_rows($resultado_busqueda)>0) { echo "o Jefe de Departamento"; }  else { echo "o Coordinador"; } } ?>:</p><br />
												<?php poner_intro(); ?>
											</div> <?php
										// ############## FINALIZA BLOQUE INTRODUCCION  ############## \\
										// ############## INICIA BLOQUE DE INDICACIONES  ############## \\ ?>
											<div class="seccion"><span>2</span>Indicaciones</div>
											<div class="contenedor-interno"><?php poner_indicaciones(); ?></div> <?php
										// ############## FINALIZA BLOQUE DE INDICACIONES ############## \\
										// ############## INICIA BLOQUE DE EVALUACIONES ############## \\ ?>
											<div class="seccion"><span>3</span><a id=evaluaciones>Evaluaciones</a></div>
											<div class="contenedor-interno">
												<table class='table table-bordered table-sm'>
													<tr>
														<th>CUESTIONARIO</th>
														<th>ESTADO</th>
													</tr> <?php
													// ************** INICIA AUTOEVALUACIONES ************** \\
														// Busca los departamentos en los que es coordinador
														$orden_sql = "SELECT * FROM `carreras` WHERE `coordinador`='" . $_SESSION['zez_a_nombre'] . "' ORDER BY `carrera`";
														// Ejecuta la consulta SQL
														$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
														if ($resultado_busqueda) {
															if (mysqli_num_rows($resultado_busqueda) > 0) {
																// Si hubo resultado genera la tabla
																while($registro = mysqli_fetch_array($resultado_busqueda)) {
																	$identificador = "#" . $registro['carrera'] . "#";
																	if (!stristr($evaluaciones_mostradas, $identificador)) {
																		$evaluaciones_mostradas = $evaluaciones_mostradas . $identificador;
																		$terminador_sexo = "";
																		if (strtoupper($registro['sexo'])=="FEMENINO") $terminador_sexo = "a";
																		echo "
																			<tr>
																				<td>COORDINACION DE " . $registro['carrera'] . "</td>";
																				$_SESSION['zez_a_carrera'] = $registro['carrera'];
																				if ('0' == $registro['sello']) {
																					if ('DEPORTE Y CULTURA'!=$_SESSION['zez_a_carrera']) {
																						$parametros_evaluacion = encriptar("%%lic_coordinadores_por_coordinadores¬".$identificador."¬".$registro['carrera']."%%");
																					} else { $parametros_evaluacion = encriptar("%%lic_deporteycultura_c_por_coordinadores¬".$identificador."¬".$terminador_sexo."%%"); }
																				} else { $parametros_evaluacion = encriptar("%%lic_materiasi_por_coordinadores¬".$identificador."¬".$terminador_sexo."%%"); }
																				if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
																					// Si ya está evaluado pone "COMPLETADA"
																					echo "<td class='completada'>COMPLETADA</td>";
																				} else {
																					// Si no está evaluado pone el botón de evaluación ?>
																					<td>
																						<form action="evalua.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
																							<input type=hidden name="parametros" value="<?php echo $parametros_evaluacion; ?>" />
																							<input type=submit class='btn btn-primary' value="AUTOEVALUAR" />
																						</form>
																					</td> <?php
																				}echo "
																			</tr>
																		";
																		// Agrega el último registro al conteo de evaluaciones totales
																		$numero_de_evaluaciones++;
																	}
																}
															}
															// Libera el conjunto de resultados
															mysqli_free_result($resultado_busqueda);
														}
													// ************** TERMINA AUTOEVALUACIONES ************** \\
													// ************** INICIA EVALUACIONES COORDINADORES DEPENDIENTES ************** \\
														// Busca los departamentos en los que es coordinador
														$orden_sql = "SELECT * FROM `departamentos` D RIGHT JOIN `carreras` C ON D.`departamento` = C.`departamento` WHERE D.`jefe`='" . $_SESSION['zez_a_nombre'] . "' AND D.`jefe`<>C.`coordinador` ORDER BY `carrera`";
														// Ejecuta la consulta SQL
														$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
														if ($resultado_busqueda) {
															if (mysqli_num_rows($resultado_busqueda) > 0) {
																// Si hubo resultado genera la tabla
																while($registro = mysqli_fetch_array($resultado_busqueda)) {
																	$identificador = "#" . $registro['carrera'] . "#";
																	if ($registro['sexo']=="FEMENINO") {
																		$terminador_sexo = "a";
																		$trato1 = "La coordinadora…";
																		$trato2 = " la coordinadora";
																	} else {
																		$terminador_sexo = "";
																		$trato1 = "El coordinador…";
																		$trato2 = "l coordinador";
																	}
																	if ('0' == $registro['sello']) {
																		if ('DEPORTE Y CULTURA'!=$registro['carrera']) {
																			$parametros_evaluacion = encriptar("%%lic_coordinadores_por_jefes¬".$identificador."¬".$registro['carrera']."¬".$registro['coordinador']."¬".$terminador_sexo."¬".$trato1."¬".$trato2."%%");
																		} else { $parametros_evaluacion = encriptar("%%lic_deporteycultura_c_por_jefes¬".$identificador."¬".$registro['coordinador']."¬".$terminador_sexo."¬".$trato1."¬".$trato2."%%"); }
																	} else { $parametros_evaluacion = encriptar("%%lic_materiasi_por_jefes¬".$identificador."¬".$registro['coordinador']."¬".$terminador_sexo."¬".$trato1."¬".$trato2."%%"); }
																	if (!stristr($evaluaciones_mostradas, $identificador)) {
																		$evaluaciones_mostradas = $evaluaciones_mostradas . $identificador;
																		echo "
																			<tr>
																				<td>COORDINACION DE " . $registro['carrera'] . "</td>";
																				if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
																					echo "<td class='completada'>COMPLETADA</td>";
																				} else { ?>
																					<td>
																						<form action="evalua.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
																							<input type=hidden name="parametros" value="<?php echo $parametros_evaluacion; ?>" />
																							<input type=submit class='btn btn-primary' value="EVALUAR" />
																						</form>
																					</td> <?php
																				} echo "
																			</tr>
																		";
																		// Agrega el último registro al conteo de evaluaciones totales
																		$numero_de_evaluaciones++;
																	}
																}
															}
															// Libera el conjunto de resultados
															mysqli_free_result($resultado_busqueda);
														}
													// ************** TERMINA EVALUACIONES COORDINADORES DEPENDIENTES ************** \\
													// ************** INICIA EVALUACIONES PROFESORES DEPENDIENTES ************** \\
														if ('DEPORTE Y CULTURA'!=$_SESSION['zez_a_carrera']) {
															$orden_sql = "SELECT DISTINCT P.`nombre`, P.`sexo` FROM `profesores` P JOIN `carreras` C ON P.`sello` = C.`sello` AND P.`carrera` = C.`carrera` WHERE C.`coordinador`='" . $_SESSION['zez_a_nombre'] . "' AND P.`nivel` = '" . $_SESSION['zez_a_nivel'] . "' ORDER BY P.`nombre`";
														} else {
															$orden_sql = "SELECT DISTINCT `profesor` AS `nombre`, `sexo` FROM `deporteycultura` ORDER BY `profesor`";
														}
														// Ejecuta la consulta SQL
														$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
														if ($resultado_busqueda) {
															if (mysqli_num_rows($resultado_busqueda) > 0) {
																// Si hubo resultado genera la tabla
																while($registro = mysqli_fetch_array($resultado_busqueda)) {
																	$identificador = "#" . $registro['nombre'] . "#";
																	if($registro['sexo']=="FEMENINO"){
																		$AoNA = "A ";
																		$la_o_el = "La profesora...";
																		$de_la_o_del = " la profesora";
																		$a_o_na = "a";
																	} else {
																		$AoNA = " ";
																		$la_o_el = "El profesor...";
																		$de_la_o_del = "l profesor";
																		$a_o_na = "";
																	}
																	if (!stristr($evaluaciones_mostradas, $identificador)) {
																		$evaluaciones_mostradas = $evaluaciones_mostradas . $identificador;
																		echo "
																			<tr>
																				<td>" . $registro['nombre'] . "</td>";
																				if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
																					// Si ya está evaluado pone "COMPLETADA"
																					echo "<td class='completada'>COMPLETADA</td>";
																				} else {
																					if ('DEPORTE Y CULTURA'!=$_SESSION['zez_a_carrera']) {
																						// Si no está evaluado pone el botón de evaluación
																						$orden_sql2 = "SELECT DISTINCT P.`carrera` FROM `profesores` P JOIN `carreras` C ON P.`sello` = C.`sello` AND P.`carrera` = C.`carrera` WHERE C.`coordinador`='" . $_SESSION['zez_a_nombre'] . "' AND P.`nivel` = '" . $_SESSION['zez_a_nivel'] . "' AND P.`nombre` = '" . $registro['nombre'] . "' ORDER BY P.`carrera`";
																						$carreras_profesor = "";
																						$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
																						if ($resultado_busqueda2) {
																							if (mysqli_num_rows($resultado_busqueda2) > 0) { while($registro2 = mysqli_fetch_array($resultado_busqueda2)) { $carreras_profesor .= " " . $registro2['carrera']; } }
																							mysqli_free_result($resultado_busqueda2);
																						}
																						$parametros_encriptados = encriptar("%%lic_profesores_por_coordinadores¬".$identificador."¬".$registro['nombre']."¬".$registro['sexo']."¬".$AoNA."¬".$carreras_profesor."%%");
																					} else { $parametros_encriptados = encriptar("%%lic_deporteycultura_p_por_coordinadores¬".$identificador."¬".$registro['nombre']."¬".$registro['sexo']."¬".$la_o_el."¬".$de_la_o_del."¬".$a_o_na."%%"); } ?>
																					<td>
																						<form action="evalua.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
																							<input type=hidden name="parametros" value="<?php echo $parametros_encriptados; ?>" />
																							<input type=submit class='btn btn-primary' value="EVALUAR" />
																						</form>
																					</td> <?php
																				} echo "
																			</tr>
																		";
																		// Agrega el último registro al conteo de evaluaciones totales
																		$numero_de_evaluaciones++;
																	}
																}
															}
															// Libera el conjunto de resultados
															mysqli_free_result($resultado_busqueda);
														}
													// ************** TERMINA EVALUACIONES PROFESORES DEPENDIENTES ************** \\ ?>
												</table>
											</div><?php
										// ############## TERMINA BLOQUE DE EVALUACIONES ############## \\
									// ---------------------------------------------------------- TERMINA COORDINADOR LICENCIATURA -------------------------------------------------------
								} elseif ($_SESSION['zez_a_nivel_acceso'] == "VICERRECTOR") {
									// ---------------------------------------------------------- INICIA VICERRECTOR LICENCIATURA --------------------------------------------------------
										// ############## INICIA BLOQUE INTRODUCCION ############## \\ ?>
											<div class="seccion"><span>1</span>Introducción</div>
											<div class="contenedor-interno">
												<p>Estimad<?php if ($_SESSION['zez_a_sexo']=="FEMENINO") { echo "a Vicerrectora"; } else { echo "o Vicerrector"; } ?>:</p><br />
												<?php poner_intro(); ?>
											</div> <?php
										// ############## FINALIZA BLOQUE INTRODUCCION ############## \\
										// ############## INICIA BLOQUE DE INDICACIONES ############## \\ ?>
											<div class="seccion"><span>2</span>Indicaciones</div>
											<div class="contenedor-interno"> <?php poner_indicaciones(); ?> </div> <?php
										// ############## FINALIZA BLOQUE DE INDICACIONES ############## \\
										// ############## INICIA BLOQUE DE EVALUACIONES ############## \\ ?>
											<div class="seccion"><span>3</span><a id=evaluaciones>Evaluaciones</a></div>
											<div class="contenedor-interno">
												<table class='table table-bordered table-sm'>
													<tr>
														<th>CUESTIONARIO</th>
														<th>ESTADO</th>
													</tr> <?php
													// Busca los departamentos en los que es coordinador
													$orden_sql = "SELECT * FROM departamentos D RIGHT JOIN carreras C ON D.departamento = C.departamento WHERE D.jefe=C.coordinador ORDER BY carrera, coordinador";
													// Ejecuta la consulta SQL
													$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
													if ($resultado_busqueda) {
														if (mysqli_num_rows($resultado_busqueda) > 0) {
															// Si hubo resultado genera la tabla
															while($registro = mysqli_fetch_array($resultado_busqueda)) {
																$identificador = "#" . $registro['carrera'] . "#";
																if (!stristr($evaluaciones_mostradas, $identificador)) {
																	$evaluaciones_mostradas = $evaluaciones_mostradas . $identificador;
																	echo "
																		<tr>
																			<td>COORDINACION DE " . $registro['carrera'];
																				if ("0" != $registro['sello']) {
																					echo ": " . $registro['coordinador'];
																				}echo "
																			</td>";
																			if (strtoupper($registro['sexo'])=="FEMENINO") {
																				$AoNA = "a ";
																				$csFrase1 = "La coordinadora...";
																				$csFrase2 = " la coordinadora";
																			} else {
																				$AoNA = " ";
																				$csFrase1 = "El coordinador...";
																				$csFrase2 = "l coordinador";
																			}
																			if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
																				echo "<td class='completada'>COMPLETADA</td>";
																			} else { ?>
																				<td>
																					<form action="evalua.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
																						<input type=hidden name="parametros" value="<?php echo encriptar("%%lic_coordinadores_por_jefes¬".$identificador."¬".$registro['carrera']."¬".$registro['coordinador']."¬".$AoNA."¬".$csFrase1."¬".$csFrase2."%%"); ?>" />
																						<input type=submit class='btn btn-primary' value="EVALUAR" />
																					</form>
																				</td> <?php
																			} echo "
																		</tr>
																	";
																	$numero_de_evaluaciones++;
																}
															}
														}
														mysqli_free_result($resultado_busqueda);
													} ?>
												</table>
											</div> <?php
										// ############## TERMINA BLOQUE DE EVALUACIONES  ############## \\
									// ---------------------------------------------------------- TERMINA VICERRECTOR LICENCIATURA -------------------------------------------------------
								}
							// ============================================================== TERMINA LICENCIATURA ====================================================================\\
						}
					?>
					<?php
						// Registra en la base de datos el número de evaluaciones que en total tiene este participante
						if ($_SESSION['zez_a_total_evaluaciones']==0) {
							$orden_sql = "UPDATE participantes SET total_evaluaciones='" . $numero_de_evaluaciones . "' WHERE id='". $_SESSION['zez_a_id'] . "'";
							mysqli_query($base_de_datos, $orden_sql);
							$_SESSION['zez_a_total_evaluaciones'] = $numero_de_evaluaciones;
						}
						// Cierra la BDD
						mysqli_close($base_de_datos);
					?>
				</div>
			</div>
		</div>
	</body>
</html>