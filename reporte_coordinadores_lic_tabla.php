<?php
	session_start();
	require_once 'lib/config.php';
	if ((!isset($_SESSION['zez_a_nombre'])) OR (!isset($_POST['parametros']))) {
		header('Location: salir.php');
		exit;
	} elseif (strpos(" REVISOR VICERRECTOR SUPERUSUARIO",$_SESSION['zez_a_nivel_acceso'])== FALSE) {
		if (date("Y-m-d") < $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) {
			header('Location: no_iniciada.php');
		} elseif (date("Y-m-d") > $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin']) {
			header('Location: cerrada.php');
			die();
		} else {
			header('Location: mis_evaluaciones.php');
			die();
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
				} while (!(strpos($string_parametros,"¬")===FALSE));
				$parametros[] = $string_parametros;
			}
		}
	}
	if(!$parametros_validos) { header('Location: index.php'); }
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
			if ((strpos(" VICERRECTOR FIDCO",$_SESSION['zez_a_nivel_acceso'])!= FALSE) AND (date("Y-m-d") >= $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) AND (date("Y-m-d") <= $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin'])) { ?>
				<div class=""><a class="btn btn-warning text-dark"onclick="redirigir('mis_evaluaciones.php');"><strong>Mis Evaluaciones</strong></a></div><?php
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
						TABLA DE EVALUACIÓN DE LOS COORDINADORES
					</h1>
					<span>CICLO <?php echo $ciclo[$parametros[0]]; ?></span>
				</div>
				<div class="card-body">
					<?php
						$contador_de_coordinadores = 0;
						// Genera la orden SQL para hacer la consulta a la tabla de la evaluación general de licenciaturas
						$orden_sql = "SELECT DISTINCT `coordinador`, `sello` FROM `carreras` WHERE `carrera` <> 'DEPORTE Y CULTURA' ORDER BY `sello`, `coordinador`";
						// Ejecuta la consulta SQL
						$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
						if ($resultado_busqueda) {
							if (mysqli_num_rows($resultado_busqueda) > 0) {
								// Si hubo resultado obtiene los datos de la sesión y activa la bandera
								unset($listado_de_coordinadores);
								while($registro = mysqli_fetch_array($resultado_busqueda)) {
									$contador_de_coordinadores++;
									$listado_de_coordinadores[$contador_de_coordinadores]['nombre'] = $registro['coordinador'];
									if ('0' == $registro['sello']) {
										$listado_de_coordinadores[$contador_de_coordinadores]['sello'] = false;
									} else { $listado_de_coordinadores[$contador_de_coordinadores]['sello'] = true; }
								}
							}
							mysqli_free_result($resultado_busqueda);
						}
						// Cierra la BDD
						mysqli_close($base_de_datos);
						if (isset($_POST['orden'])) {
							if ($_POST['orden']=="Ordenar por calificación") { ?>

								<form action="reporte_coordinadores_lic_tabla.php" method="post">
									<input type="hidden" name="parametros" value="<?php echo encriptar("%%LICENCIATURA%%"); ?>" />
									<input type="submit" class="btn btn-primary" name="orden" value="Ordenar por nombre" />
								</form><?php

							} else { ?>

								<form action="reporte_coordinadores_lic_tabla.php" method="post">
									<input type="hidden" name="parametros" value="<?php echo encriptar("%%LICENCIATURA%%"); ?>" />
									<input type="submit" class="btn btn-primary" name="orden" value="Ordenar por calificación" />
								</form><?php
							}
						} else { ?>

							<form action="reporte_coordinadores_lic_tabla.php" method="post">
								<input type="hidden" name="parametros" value="<?php echo encriptar("%%LICENCIATURA%%"); ?>" />
								<input type="submit" class="btn btn-primary" name="orden" value="Ordenar por calificación" />

							</form><?php

						}
						echo "<hr />";
						$total_bloques = 4;
						$nombre_tabla[1] = "jefes";
						$ponderacion[1] = 0.4;
						$inicio_reactivos[1] = 1;
						$fin_reactivos[1] = 20;
						$inicio_reactivos_mi[1] = 1;
						$fin_reactivos_mi[1] = 12;
						$inicio_nas[1] = 1; // si no hay reactivos con NA poner un número mayor al número de preguntas
						$inicio_nas_mi[1] = 999;
						$nombre_tabla[2] = "profesores";
						$ponderacion[2] = 0.25;
						$inicio_reactivos[2] = 1;
						$fin_reactivos[2] = 15;
						$inicio_reactivos_mi[2] = 1;
						$fin_reactivos_mi[2] = 12;
						$inicio_nas[2] = 1; // si no hay reactivos con NA poner un número mayor al número de preguntas
						$inicio_nas_mi[2] = 999;
						$nombre_tabla[3] = "alumnos";
						$ponderacion[3] = 0.25;
						$inicio_reactivos[3] = 1;
						$fin_reactivos[3] = 18;
						$inicio_reactivos_mi[3] = 1;
						$fin_reactivos_mi[3] = 8;
						$inicio_nas[3] = 1; // si no hay reactivos con NA poner un número mayor al número de preguntas
						$inicio_nas_mi[3] = 999;
						$nombre_tabla[4] = "coordinadores";
						$ponderacion[4] = 0.1;
						$inicio_reactivos[4] = 1;
						$fin_reactivos[4] = 31;
						$inicio_reactivos_mi[4] = 1;
						$fin_reactivos_mi[4] = 12;
						$inicio_nas[4] = 1; // si no hay reactivos con NA poner un número mayor al número de preguntas
						$inicio_nas_mi[4] = 999;
						unset($nombre_reactivo); // Genera nombres de registro para hasta 100 respuestas
						for ($contador_tmp1=1; $contador_tmp1<=100; $contador_tmp1++) { $nombre_reactivo[$contador_tmp1] = "r" . str_pad($contador_tmp1, 2, "0", STR_PAD_LEFT); }
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
						// ##################### OBTENER RESULTADOS
						for ($contador_tmp1=1; $contador_tmp1<=$contador_de_coordinadores; $contador_tmp1++) {
							for ($contador_tmp2=1; $contador_tmp2<=$total_bloques; $contador_tmp2++) {
								if ($listado_de_coordinadores[$contador_tmp1]['sello']) {
									$listado_de_coordinadores[$contador_tmp1][$nombre_tabla[$contador_tmp2]] = -1;
									// Genera la orden SQL para hacer la consulta a la tabla de la evaluación general de licenciaturas
									$orden_sql = "SELECT * ";
									$orden_sql .= "FROM `lic_materiasi_por_" . $nombre_tabla[$contador_tmp2] . "` ";
									$orden_sql .= "WHERE 1";
									if ($nombre_tabla[$contador_tmp2]=="alumnos") $orden_sql .= " ORDER BY `carrera`, `grado`";
									$hay_respuestas[$contador_tmp2] = false;
									// Ejecuta la consulta SQL
									$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
									if ($resultado_busqueda) {
										if (mysqli_num_rows($resultado_busqueda) > 0) {
											$calificacion_total = 0;
											$descartados = 0;
											unset($calificaciones);
											for ($contador_tmp3=$inicio_reactivos_mi[$contador_tmp2]; $contador_tmp3<=($fin_reactivos_mi[$contador_tmp2]-1); $contador_tmp3++) {
												$calificaciones[$contador_tmp3] = 0;
												$numero_respuestas[$contador_tmp3] = 0;
												$nas[$contador_tmp3] = 0;
											}
											while($registro = mysqli_fetch_array($resultado_busqueda)) {
												for ($contador_tmp3=$inicio_reactivos_mi[$contador_tmp2]; $contador_tmp3<=($fin_reactivos_mi[$contador_tmp2]-1); $contador_tmp3++) {
													$calificaciones[$contador_tmp3] += $registro[$nombre_reactivo[$contador_tmp3]];
													$numero_respuestas[$contador_tmp3]++;
													if ($registro[$nombre_reactivo[$contador_tmp3]]==0) $nas[$contador_tmp3]++;
												}
											}
											for ($contador_tmp3=$inicio_reactivos_mi[$contador_tmp2]; $contador_tmp3<=($fin_reactivos_mi[$contador_tmp2]-1); $contador_tmp3++) {
												if ($calificaciones[$contador_tmp3] == 0) $descartados++;
												if (($numero_respuestas[$contador_tmp3] - $nas[$contador_tmp3])>0) $calificacion_total += $calificaciones[$contador_tmp3] / ($numero_respuestas[$contador_tmp3] - $nas[$contador_tmp3]);
											}
											$listado_de_coordinadores[$contador_tmp1][$nombre_tabla[$contador_tmp2]] = $calificacion_total / (($fin_reactivos_mi[$contador_tmp2]-1)-$descartados-($inicio_reactivos_mi[$contador_tmp2]-1));
										}
										// Libera el conjunto de resultados
										mysqli_free_result($resultado_busqueda);
									}
								} else {
									$listado_de_coordinadores[$contador_tmp1][$nombre_tabla[$contador_tmp2]] = -1;
									// Genera la orden SQL para hacer la consulta a la tabla de la evaluación general de licenciaturas
									$orden_sql = "SELECT * ";
									$orden_sql .= "FROM `lic_coordinadores_por_" . $nombre_tabla[$contador_tmp2] . "` ";
									$orden_sql .= "WHERE `nombre`='" . $listado_de_coordinadores[$contador_tmp1]['nombre'] . "' ";
									$orden_sql .= "ORDER BY `carrera`";
									if ($nombre_tabla[$contador_tmp2]=="alumnos") $orden_sql .= ", `grado`";
									$hay_respuestas[$contador_tmp2] = false;
									// Ejecuta la consulta SQL
									$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
									if ($resultado_busqueda) {
										if (mysqli_num_rows($resultado_busqueda) > 0) {
											$calificacion_total = 0;
											$descartados = 0;
											unset($calificaciones);
											for ($contador_tmp3=$inicio_reactivos[$contador_tmp2]; $contador_tmp3<=($fin_reactivos[$contador_tmp2]-1); $contador_tmp3++) {
												$calificaciones[$contador_tmp3] = 0;
												$numero_respuestas[$contador_tmp3] = 0;
												$nas[$contador_tmp3] = 0;
											}
											while($registro = mysqli_fetch_array($resultado_busqueda)) {
												for ($contador_tmp3=$inicio_reactivos[$contador_tmp2]; $contador_tmp3<=($fin_reactivos[$contador_tmp2]-1); $contador_tmp3++) {
													$calificaciones[$contador_tmp3] += $registro[$nombre_reactivo[$contador_tmp3]];
													$numero_respuestas[$contador_tmp3]++;
													if ($registro[$nombre_reactivo[$contador_tmp3]]==0) $nas[$contador_tmp3]++;
												}
											}
											for ($contador_tmp3=$inicio_reactivos[$contador_tmp2]; $contador_tmp3<=($fin_reactivos[$contador_tmp2]-1); $contador_tmp3++) {
												if ($calificaciones[$contador_tmp3] == 0) $descartados++;
												if (($numero_respuestas[$contador_tmp3] - $nas[$contador_tmp3])>0) $calificacion_total += $calificaciones[$contador_tmp3] / ($numero_respuestas[$contador_tmp3] - $nas[$contador_tmp3]);
											}
											$listado_de_coordinadores[$contador_tmp1][$nombre_tabla[$contador_tmp2]] = $calificacion_total / (($fin_reactivos[$contador_tmp2]-1)-$descartados-($inicio_reactivos[$contador_tmp2]-1));
										}
										// Libera el conjunto de resultados
										mysqli_free_result($resultado_busqueda);
									}
						
								}
							}
							$listados_ok = true;
							for ($contador_tmp2=1; $contador_tmp2<=$total_bloques; $contador_tmp2++) {
								if ($listado_de_coordinadores[$contador_tmp1][$nombre_tabla[$contador_tmp2]]<0) {
									$listados_ok = false;
								}
							}
							if ($listados_ok) {
								$listado_de_coordinadores[$contador_tmp1]['final'] = 0;
								$total_ponderacion = 0;
								for ($contador_tmp2=1; $contador_tmp2<=$total_bloques; $contador_tmp2++) {
									$listado_de_coordinadores[$contador_tmp1]['final'] += round($listado_de_coordinadores[$contador_tmp1][$nombre_tabla[$contador_tmp2]], 2) * $ponderacion[$contador_tmp2];
									$total_ponderacion += $ponderacion[$contador_tmp2];
								}
								$listado_de_coordinadores[$contador_tmp1]['final'] = round($listado_de_coordinadores[$contador_tmp1]['final'] / $total_ponderacion, 2);
							} else {
								$listado_de_coordinadores[$contador_tmp1]['final'] = -1;
							}
						}
						//============== INICIA ORDENAMIENTO DEL ARREGLO
						$sortArray = array();
						foreach($listado_de_coordinadores as $person){
							foreach($person as $key=>$value){
								if(!isset($sortArray[$key])){
									$sortArray[$key] = array();
								}
								$sortArray[$key][] = $value;
							}
						} 
						$orden['por'] = "nombre";
						$oredn['tipo'] = SORT_ASC;
						if (isset($_POST['orden'])) {
							if ($_POST['orden']=="Ordenar por calificación") {
								$orden['por'] = "final";
								$oredn['tipo'] = SORT_DESC;
							}
						}
						array_multisort($sortArray[$orden['por']],$oredn['tipo'],$listado_de_coordinadores);
						//============== TERMINA ORDENAMIENTO DEL ARREGLO
						// =========================================================================================================
						//   DESPLEGAR LOS RESULTADOS
						// ========================================================================================================= ?>
						<table class="table table-bordered table-striped table-sm" style="font-size:80%;margin-left:auto;margin-right:auto;">
							<tr>
								<th style="background:#1f497d;color:#ffffff;width:5%;">No.</th>
								<th style="background:#1f497d;color:#ffffff;width:30%;">Coordinador</th>
								<?php
									for ($contador_tmp1=1; $contador_tmp1<=$total_bloques; $contador_tmp1++) {
										echo "<th style='background:#1f497d;color:#ffffff;width:10%;'>" . ucfirst($nombre_tabla[$contador_tmp1]) . "<br />" . ($ponderacion[$contador_tmp1]*100) . "%</th>";
									}
								?>
								<th style="background:#1f497d;color:#ffffff;width:10%">CF</th>
							</tr> <?php
							for ($contador_tmp1=1; $contador_tmp1<=$contador_de_coordinadores; $contador_tmp1++) { ?>
								<tr>
									<th><?php echo $contador_tmp1; ?></th>
									<th style="font-weight:bold;background:#538ed5;"><?php echo $listado_de_coordinadores[$contador_tmp1-1]['nombre']; ?></th> <?php
									for ($contador_tmp2=1; $contador_tmp2<=$total_bloques; $contador_tmp2++) {
										echo "<td";
										if ($listado_de_coordinadores[$contador_tmp1-1][$nombre_tabla[$contador_tmp2]]<0) echo " style='background:#ffff66;'";
										echo">";
										if ($listado_de_coordinadores[$contador_tmp1-1][$nombre_tabla[$contador_tmp2]]>=0) {
											echo number_format(round($listado_de_coordinadores[$contador_tmp1-1][$nombre_tabla[$contador_tmp2]], 2),2);
										} else { echo "SIN DATOS"; }
										echo "</th>";
									} ?>
									<td style="font-weight:bold;font-size:100%;<?php if ($listado_de_coordinadores[$contador_tmp1-1]['final']<0) echo "background:#ff6666;"; ?>"><?php if ($listado_de_coordinadores[$contador_tmp1-1]['final']>=0) { echo number_format(round($listado_de_coordinadores[$contador_tmp1-1]['final'], 2),2); } else { echo "INCOMPLETO"; } ?>
									</td>
								</tr> <?php
							} ?>
						</table>
						<br /><?php
						$orden_sql = "SELECT `coordinador` FROM `carreras` WHERE `carrera` = 'DEPORTE Y CULTURA'";
						// Ejecuta la consulta SQL
						$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
						if ($resultado_busqueda) {
							if (mysqli_num_rows($resultado_busqueda) > 0) {
								$registro = mysqli_fetch_array($resultado_busqueda);
								$coordinador_deporteycultura = $registro['coordinador'];
							}
							mysqli_free_result($resultado_busqueda);
						}
						// Cierra la BDD
						mysqli_close($base_de_datos);
					?>
				</div>
			</div>
		</div>
	</body>
</html>