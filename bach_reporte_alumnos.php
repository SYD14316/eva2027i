<?php
	session_start();
	if (!isset($_SESSION['zez_a_nivel_acceso'])) {
		// Si no está logueado lo manda al inicio
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
							<span>Ciclo <?php echo $ciclo['BACHILLERATO']; ?> : PARTICIPACIÓN DE ALUMNOS</span>
						</div>
						<div class="card-body">
							<?php
								if (($_SESSION['zez_a_nivel'] != "BACHILLERATO") AND ($_SESSION['zez_a_nivel_acceso'] != 10) AND ($_SESSION['zez_a_nivel_acceso'] != 30) AND ($_SESSION['zez_a_nivel_acceso'] < 100)) { 
									// OJO: El número es el grupo mínimo que puede tener derecho a entrar
									// Si no tiene derecho a está página...
									echo $_SESSION['zez_a_nombre']; ?> no estás autorizad<?php if ($_SESSION['zez_a_sexo']=="FEMENINO") { echo "a"; } else { echo "o"; } ?> para ingresar a esta página.<br><br>
									Para regresar al menú principal haz clic <a href="index.php">aquí</a>.<?php
								} else {
									// ##### Si sí tiene derecho a esta página ingresar código desde aquí #####
									include 'lib/config.php'; ?>
									<p>
										<form action="reportes.php" method="post" autocomplete="off" accept-charset="UTF-8">
											<p><input type="submit" value="REGRESAR" /></p>
										</form>
									</p> <?php
									unset($total_alumnos);
									unset($alumnos_ingresaron);
									unset($total_evaluaciones);
									unset($evaluaciones_contestadas);
									$total_alumnos = 0;
									$alumnos_ingresaron = 0;
									$total_evaluaciones = 0;
									$evaluaciones_contestadas = 0;
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
									// Genera la orden SQL para hacer la consulta a la tabla de la evaluación general de licenciaturas
									$orden_sql = "SELECT * FROM alumnos WHERE nivel='BACHILLERATO' ORDER BY grado, nombre ASC";
									// Ejecuta la consulta SQL
									$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
									if ($resultado_busqueda) {
										if (mysqli_num_rows($resultado_busqueda) > 0) {
											// Si hubo resultado obtiene los datos de la sesión y activa la bandera
											while($registro = mysqli_fetch_array($resultado_busqueda)) {
												if ($registro['nivel_acceso']==1) {
													$total_alumnos++;
													if ($registro['ultimo_acceso']!="") {
														$alumnos_ingresaron++;	
														$total_evaluaciones += $registro['total_evaluaciones'];
														$evaluaciones_contestadas += $registro['num_evaluados'];
													}
												}
											}
										}
										// Libera el conjunto de resultados
										mysqli_free_result($resultado_busqueda);
									} ?>
									<table class="table table-bordered table-striped table-sm" style="width:auto;">
										<tr>
											<th style="text-align:right;">Alumnos que ingresaron:</th>
											<td style="text-align:left;"><?php echo (round($alumnos_ingresaron/$total_alumnos, 4)*100) . "%, $alumnos_ingresaron de $total_alumnos"; ?></td>
										</tr>
										<tr>
											<th style="text-align:right;">Evaluaciones contestadas:<br />(de los que ingresaron)</th>
											<td style="text-align:left;"><?php echo (round($evaluaciones_contestadas/$total_evaluaciones, 4)*100) . "%, $evaluaciones_contestadas de $total_evaluaciones"; ?></td>
										</tr>
										<tr>
											<th style="text-align:right;">Índice de participación general:</th>
											<td style="text-align:left;"><?php echo round((round($alumnos_ingresaron/$total_alumnos, 4)*100) * round($evaluaciones_contestadas/$total_evaluaciones, 4), 2) . "%"; ?></td>
										</tr>
									</table>
									<br />
									<table>
										<tr>
											<th>Grado</th>
											<th>Nombre</th>
											<th>Sexo</th>
											<th>Situación</th>
											<th>Evaluaciones contestadas</th>
											<th>Última vez que ingresó</th>
										</tr>
										<?php
											$orden_sql = "SELECT * FROM alumnos WHERE nivel='BACHILLERATO' AND nivel_acceso=1 ORDER BY grado, nombre ASC";
											// Ejecuta la consulta SQL
											$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
											if ($resultado_busqueda) {
												if (mysqli_num_rows($resultado_busqueda) > 0) {
													// Si hubo resultado obtiene los datos de la sesión y activa la bandera
													while($registro = mysqli_fetch_array($resultado_busqueda)) {
														echo "<tr>";
														echo "<td>" . $registro['grado'] . "</td>";
														echo "<td>" . $registro['nombre'] . "</td>";
														echo "<td>" . $registro['sexo'] . "</td>";
														echo "<td>" . $registro['situacion'] . "</td>";
														if ($registro['ultimo_acceso']=="") {
															echo "<td colspan='2' style='color:#ff0000;'>No ingresó a realizar la evaluación de este ciclo.</td>";
														} else {									
															echo "<td>" . $registro['num_evaluados'] . " de " . $registro['total_evaluaciones'] . "</td>";
															echo "<td>" . $registro['ultimo_acceso'] . "</td>";
														}
														echo "</tr>";
													}
												}
												// Libera el conjunto de resultados
												mysqli_free_result($resultado_busqueda);
											}
										?>
									</table>
									<form action="reportes.php" method="post" autocomplete="off" accept-charset="UTF-8">
										<p align="center"><input type="submit" value="REGRESAR" /></p>
									</form> <?php
									mysqli_close($base_de_datos);
								}
							?>
						</div>
					</div>
				</div>
			</body>
		</html> <?php
	}
?>