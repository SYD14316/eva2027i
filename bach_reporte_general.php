<?php
	session_start();
	if (!isset($_SESSION['zez_a_nivel_acceso'])) {
		// Si no está logueado lo manda al inicio
		session_destroy();
		header('Location: index.php');
	} else {
		// Si sí está logueado...
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
							<span>Ciclo <?php echo $ciclo['BACHILLERATO']; ?> : SERVICIOS</span>
						</div>
						<div class="card-body">
							<?php
								if (($_SESSION['zez_a_nivel'] != "BACHILLERATO") AND ($_SESSION['zez_a_nivel_acceso'] != 10) AND ($_SESSION['zez_a_nivel_acceso'] != 30) AND ($_SESSION['zez_a_nivel_acceso'] < 50)) {
									// OJO: El número es el grupo mínimo que puede tener derecho a entrar
									// Si no tiene derecho a está página...
									echo $_SESSION['zez_a_nombre']; ?> no estás autorizad<?php if ($_SESSION['zez_a_sexo']=="FEMENINO") { echo "a"; } else { echo "o"; } ?> para ingresar a esta página.<br><br>
									Para regresar al menú principal haz clic <a href="index.php">aquí</a>. <?php
								} else {
									// ##### Si sí tiene derecho a esta página ingresar código desde aquí #####
									unset($resultado_busqueda);
									$resultado_busqueda = false;
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
									$orden_sql = "SELECT * FROM bach_general ORDER BY carrera, grado, sexo, situacion ASC";
									// Ejecuta la consulta SQL
									unset($resultado_busqueda);
									$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
									if ($resultado_busqueda) {
										if (mysqli_num_rows($resultado_busqueda) > 0) {
											// Si hubo resultado obtiene los datos de la sesión y activa la bandera ?>
											<p align="center">
											<form action="reportes.php" method="post" autocomplete="off" accept-charset="UTF-8">
												<p align="center"><input type="submit" value="REGRESAR" /></p>
											</form>
											<table class="table table-bordered table-striped table-sm">
												<tr>
													<th>Área</th>
													<th>Grado</th>
													<th>Sexo</th>
													<th>Situación</th>
													<th>Servicio de limpieza</th>
													<th>Sercivio de mantenimiento</th>
													<th>Administración<br />(Tesorería, Planta Física y Control Escolar)</th>
													<th>Informática y Telecomunicaciones<br />(salones de cómputo, red inalámbrica, impresiones, etc.)</th>
													<th>Cafetería y papelería</th>
													<th>Innovaciones sugeridas</th>
												</tr>
												<?php
													while($registro = mysqli_fetch_array($resultado_busqueda)) {
														echo "
															<tr style='font-size:0.7em;'>
																<td>" . $registro['carrera'] . "</td>
																<td>" . $registro['grado'] . "</td>
																<td>" . $registro['sexo'] . "</td>
																<td>" . $registro['situacion'] . "</td>
																<td>" . $registro['r01'] . "</td>
																<td>" . $registro['r02'] . "</td>
																<td>" . $registro['r03'] . "</td>
																<td>" . $registro['r04'] . "</td>
																<td>" . $registro['r05'] . "</td>
																<td>" . $registro['r06'] . "</td>
															</tr>
														";
													}
												?>
											</table>
											</p> <?php
										} else {
											unset($resultado_busqueda);
											$resultado_busqueda = false;
										}
										// Libera el conjunto de resultados
										mysqli_free_result($resultado_busqueda);
									} else {
										unset($resultado_busqueda);
										$resultado_busqueda = false;
									}
									// Cierra la BDD
									mysqli_close($base_de_datos);
								} // ##### Si sí tiene derecho a esta página ingresar código hasta aquí #####
								if (!$resultado_busqueda) { ?>
									<h3>SIN RESPUESTAS</h3>
									<p>No se han obtenido respuestas sobre este tema.</p>
									<br /> <?php
								}
							?>
							<form action="reportes.php" method="post" autocomplete="off" accept-charset="UTF-8">
								<input type="submit" value="REGRESAR" />
							</form>
						</div>
					</div>
				</div>
			</body>
		</html> <?php
	}
?>