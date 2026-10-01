<?php
	session_start();
	require_once 'lib/config.php';
	if (!isset($_SESSION['zez_a_nombre'])) {
		header('Location: salir.php');
	} elseif (strpos(" INGLES ACUDE ACADEMICO DIRECTOR COORDINADOR REVISOR VICERRECTOR FIDCO ADMINISTRADOR SUPERUSUARIO",$_SESSION['zez_a_nivel_acceso'])=== FALSE) {
		if (date("Y-m-d") < $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) {
			header('Location: no_iniciada.php');
		} elseif (date("Y-m-d") > $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin']) {
			header('Location: cerrada.php');
		} else {
			header('Location: mis_evaluaciones.php');
		}
	}
	//functios.php (encriptar,desencriptar)
?>
<html>
	<?php include_once HEADER; ?>
	<script type="text/javascript" src="lib/functions.js"></script>
	<div class="d-flex fondo-azul-marista">
		<div><a class="btn btn-warning text-dark" ><strong>Mis Reportes</strong></a></div>
		<?php
			if ((strpos(" COORDINADOR VICERRECTOR FIDCO",$_SESSION['zez_a_nivel_acceso'])!= FALSE) AND (date("Y-m-d") >= $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) AND (date("Y-m-d") <= $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin'])) { ?>
				<div class=""><a class="btn text-light"onclick="redirigir('mis_evaluaciones.php');"><strong>Mis Evaluaciones</strong></a></div><?php
			}
		?>
		<div class="ml-auto"><a class="btn text-light" onclick="redirigir('salir.php');"><strong>Salir</strong></a></div>
	</div>
	<body>
		<div class="container">
			<div class="card">
				<div class="card-header">
					<h1><img src="lib/img/blanco.png" class="img-form-left" />Mis Reportes</h1>
					<span>Ciclo <?php echo $_SESSION['zez_a_ciclo']; ?></span>
				</div>
				<div class="card-body">
					<?php
						//Tabla de bachillerato
						if (strpos(" ACUDE ACADEMICO DIRECTOR ADMINISTRADOR SUPERUSUARIO",$_SESSION['zez_a_nivel_acceso'])!= FALSE) { ?>
							<div class="seccion">
								<p><span>1</span>Bachillerato</p>
							</div>
							<div class="contenedor-interno">
								<table class="table table-bordered table-striped table-sm">
									<tr class="text-center">
										<th class="fondo-marista" style="width: 100%;">REPORTE</th>
										<th class="fondo-marista">IR</th>
									</tr><?php
									if (strpos(" ACADEMICO DIRECTOR ADMINISTRADOR SUPERUSUARIO",$_SESSION['zez_a_nivel_acceso'])!= FALSE) { ?>
										<tr>
											<td>PARTICIPACIÓN DE ALUMNOS</td>
											<td>
												<form action="reporte_participacion_alumnos.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
													<input class="btn btn-primary" type=hidden name="parametros" value="<?php echo encriptar("%%BACHILLERATO%%"); ?>" />
													<input class="btn btn-primary" type=submit value="IR" />
												</form>
											</td>
										</tr><?php
									}
									if (strpos(" ACUDE ACADEMICO DIRECTOR ADMINISTRADOR SUPERUSUARIO",$_SESSION['zez_a_nivel_acceso'])!= FALSE) {
										?>
										<tr>
											<td>REPORTE DE PROFESORES</td>
											<td>
												<form action="bach_reporte_profesores.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
													<input class="btn btn-primary" type=hidden name="parametros" value="<?php echo encriptar("%%BACHILLERATO%%"); ?>" />
													<input class="btn btn-primary" type=submit value="IR" />
												</form>
											</td>
										</tr>
										<tr>
											<td>REPORTE DE PROFESORES 2</td>
											<td>
												<form action="bach_reporte_profesores2.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
													<input class="btn btn-primary" type=hidden name="parametros" value="<?php echo encriptar("%%BACHILLERATO%%"); ?>" />
													<input class="btn btn-primary" type=submit value="IR" />
												</form>
											</td>
										</tr> <?php
									}
									if (strpos(" ACADEMICO DIRECTOR ADMINISTRADOR SUPERUSUARIO",$_SESSION['zez_a_nivel_acceso'])!= FALSE) { ?>
										<tr>
											<td>REPORTE DE TITULARES</td>
											<td>
												<form action="bach_reporte_titulares.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;">
													<input class="btn btn-primary" type=hidden name="parametros" value="<?php echo encriptar("%%BACHILLERATO%%"); ?>" />
													<input class="btn btn-primary" type=submit value="IR" />
												</form>
											</td>
										</tr> <?php
									} ?>
								</table>
							</div> <?php
						}
						//tabla de licenciaturas
						if (strpos(" INGLES COORDINADOR REVISOR VICERRECTOR FIDCO ADMINISTRADOR SUPERUSUARIO",$_SESSION['zez_a_nivel_acceso'])!= FALSE) { ?>
							<div class="seccion">
								Licenciatura
							</div>
							<div class="contenedor-interno">
								<table class="table table-bordered table-striped table-sm">
									<tr class="text-center">
										<th class="fondo-marista" style="width: 100%;">REPORTE</th>
										<th class="fondo-marista">IR</th>
									</tr><?php
									if ('DEPORTE Y CULTURA'!=$_SESSION['zez_a_carrera']) { ?>
										<tr>
											<td>PARTICIPACIÓN DE ESTUDIANTES</td>
											<td>
												<form action="reporte_participacion_alumnos.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;" target="_blank">
													<input class="btn btn-primary" type=hidden name="parametros" value="<?php echo encriptar("%%LICENCIATURA%%"); ?>" />
													<input class="btn btn-primary" type=submit value="IR" />
												</form>
											</td>
										</tr>
										<tr>
											<td>PARTICIPACIÓN DE DOCENTES</td>
											<td>
												<form action="reporte_participacion_profesores.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;" target="_blank">
													<input class="btn btn-primary" type=hidden name="parametros" value="<?php echo encriptar("%%LICENCIATURA%%"); ?>" />
													<input class="btn btn-primary" type=submit value="IR" />
												</form>
											</td>
										</tr>
										
										<tr>
											<td>RESULTADOS DE DOCENTES POR MATERIA</td>
											<td>
												<form action="reportes/listado_docentes.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;" target="_blank">
													<input class="btn btn-primary" type=hidden name="parametros" value="<?php echo encriptar("%%LICENCIATURA%%"); ?>" />
													<input class="btn btn-primary" type=submit value="IR" />
												</form>
											</td>
										</tr>

										<tr>
											<td>RESULTADOS DE DOCENTES POR CARRERA</td>
											<td>
												<form action="reportes/listado_docentes_carrera.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;" target="_blank">
													<input class="btn btn-primary" type=hidden name="parametros" value="<?php echo encriptar("%%LICENCIATURA%%"); ?>" />
													<input class="btn btn-primary" type=submit value="IR" />
												</form>
											</td>
										</tr>

										<tr>
											<td>RESULTADOS DE CARRERAS</td>
											<td>
												<form action="reporte_resultado_carrera.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;" target="_blank">
													<input class="btn btn-primary" type=hidden name="parametros" value="<?php echo encriptar("%%LICENCIATURA%%"); ?>" />
													<input class="btn btn-primary" type=submit value="IR" />
												</form>
											</td>
										</tr> <?php
									}
									if (strpos(" REVISOR VICERRECTOR SUPERUSUARIO",$_SESSION['zez_a_nivel_acceso'])!= FALSE) { ?>
										<tr>
											<td>RESULTADOS INTEGRALES POR DOCENTE</td>
											<td>
												<form action="reportes/listado_docentes_integral.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;" target="_blank">
													<input class="btn btn-primary" type=hidden name="parametros" value="<?php echo encriptar("%%LICENCIATURA%%"); ?>" />
													<input class="btn btn-primary" type=submit value="IR" />
												</form>
											</td>
										</tr>
										<tr>
											<td>RESULTADOS GENERALES</td>
											<td>
												<form action="reporte_resultado_general_a1.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;" target="_blank">
													<input class="btn btn-primary" type=hidden name="parametros" value="<?php echo encriptar("%%LICENCIATURA%%"); ?>" />
													<input class="btn btn-primary" type=submit value="IR" />
												</form>
											</td>
										</tr>

										<tr>
											<td>PARTICIPACIÓN DE COORDINADORES</td>
											<td>
												<form action="reporte_participacion_coordinadores.php" method=post accept-charset="UTF-8" style="margin-bottom:0em;" target="_blank">
													<input class="btn btn-primary" type=hidden name="parametros" value="<?php echo encriptar("%%LICENCIATURA%%"); ?>" />
													<input class="btn btn-primary" type=submit value="IR" />
												</form>
											</td>
										</tr>

										<?php
									} ?>
								</table>
							</div> <?php
						}
					?>
				</div>
			</div>
		</div>
	</body>
</html>