<?php
	session_start();
	require_once 'lib/config.php';
	if (!isset($_SESSION['zez_a_nombre'])) {
		header('Location: salir.php');
	} elseif (strpos(" INGLES ACADEMICO DIRECTOR COORDINADOR REVISOR VICERRECTOR FIDCO ADMINISTRADOR SUPERUSUARIO",$_SESSION['zez_a_nivel_acceso'])== FALSE) {
		if (date("Y-m-d") < $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) {
			header('Location: no_iniciada.php');
		} elseif (date("Y-m-d") > $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin']) {
			header('Location: cerrada.php');
		} else {
			header('Location: mis_evaluaciones.php');
		}
	}
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
	<body>
		<div class="container">
			<div class="card">
				<div class="card-header">
					<h1>
						<img src="lib/img/blanco.png" class="img-form-left" />
						<?php echo "PARTICIPACIÓN DE LOS ALUMNOS DE " . $parametros[0]; ?>
						<span>CICLO <?php echo $ciclo[$parametros[0]]; ?></span>
					</h1>
				</div>
				<div class="card-body">
					<div class="seccion"><p><span>1</span>Estadísticas</p></div>
					<div class="contenedor-interno">
						<?php
							unset($total_alumnos);
							unset($alumnos_ingresaron);
							unset($total_evaluaciones);
							unset($evaluaciones_contestadas);
							$total_alumnos = 0;
							$alumnos_ingresaron = 0;
							$total_evaluaciones = 0;
							$evaluaciones_contestadas = 0;
							// Determina si es el coordinador de materias sello
							$coordinador_materias_sello = false;
							$orden_sql = "SELECT * ";
							$orden_sql .= "FROM carreras ";
							$orden_sql .= "WHERE carrera = 'MATERIAS SELLO'";
							$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
							if ($resultado_busqueda) {
								if (mysqli_num_rows($resultado_busqueda) > 0) {
									while($registro = mysqli_fetch_array($resultado_busqueda)) {
										if($registro['coordinador']==$_SESSION['zez_a_nombre']) $coordinador_materias_sello = true;
									}
								}
								mysqli_free_result($resultado_busqueda);
							}
							// Genera la orden SQL para hacer la consulta a la tabla de la evaluación general de licenciaturas
							if ((strpos(" COORDINADOR",$_SESSION['zez_a_nivel_acceso'])!= FALSE) AND (!$coordinador_materias_sello)) {
								$orden_sql = "SELECT DISTINCT P.* ";
								$orden_sql .= "FROM participantes P ";
								$orden_sql .= "INNER JOIN carreras C ";
								$orden_sql .= "ON P.carrera=C.carrera ";
								$orden_sql .= "AND P.nivel='" . $_SESSION['zez_a_nivel'] . "' ";
								$orden_sql .= "INNER JOIN departamentos D ";
								$orden_sql .= "ON C.departamento = D.departamento ";
								$orden_sql .= "WHERE P.nivel_acceso='ALUMNO' ";
								$orden_sql .= "AND C.coordinador='" . $_SESSION['zez_a_nombre'] . "' ";
								$orden_sql .= "OR D.jefe='" . $_SESSION['zez_a_nombre'] . "' ";
							} else {
								if ('BACHILLERATO'!=$parametros[0]) {
									$orden_sql = "SELECT * ";
									$orden_sql .= "FROM `participantes` P ";
									$orden_sql .= "WHERE P.`nivel`='" . $parametros[0] . "' ";
									$orden_sql .= "AND P.`nivel_acceso`='ALUMNO' ";
								} else {
									$orden_sql = "SELECT P.*, T.grupo ";
									$orden_sql .= "FROM `participantes` P ";
									$orden_sql .= "INNER JOIN `grupos` G ";
									$orden_sql .= "ON P.`matricula`=G.`matricula` ";
									$orden_sql .= "INNER JOIN `titulares` T ";
									$orden_sql .= "ON G.`grupo`=T.`grupo` ";
									$orden_sql .= "WHERE `nivel`='BACHILLERATO' ";
									$orden_sql .= "AND `nivel_acceso`='ALUMNO'";
								}
							}
							// Ejecuta la consulta SQL
							$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
							if ($resultado_busqueda) {
								if (mysqli_num_rows($resultado_busqueda) > 0) {
									// Si hubo resultado obtiene los datos de la sesión y activa la bandera
									while($registro = mysqli_fetch_array($resultado_busqueda)) {
										$total_alumnos++;
										if ($registro['ultimo_acceso']!="") {
											$alumnos_ingresaron++;	
											$total_evaluaciones += $registro['total_evaluaciones'];
											$evaluaciones_contestadas += $registro['num_evaluados'];
										}
									}
								}
								mysqli_free_result($resultado_busqueda);
							}
						?>
						<table class="table table-bordered table-striped table-sm" style="width:auto;">
							<tr>
								<th class="fondo-marista" style="text-align:right;">Alumnos que ingresaron:</th>
								<td style="text-align:left;"><?php if ($total_alumnos == 0){ echo "0"; } else { echo (round($alumnos_ingresaron/$total_alumnos, 4)*100); } echo "%, $alumnos_ingresaron de $total_alumnos"; ?></td>
							</tr>
							<tr>
								<th class="fondo-marista" style="text-align:right;">Evaluaciones contestadas:<br />(de los que ingresaron)</th>
								<td style="text-align:left;"><?php if ($total_evaluaciones == 0){ echo "0"; } else { echo (round($evaluaciones_contestadas/$total_evaluaciones, 4)*100); } echo "%, $evaluaciones_contestadas de $total_evaluaciones"; ?></td>
							</tr>
							<tr>
								<th class="fondo-marista" style="text-align:right;">Índice de participación general:</th>
								<td style="text-align:left;"><?php if (($total_alumnos == 0) or ($total_evaluaciones == 0)){ echo "0"; } else { echo round((round($alumnos_ingresaron/$total_alumnos, 4)*100) * round($evaluaciones_contestadas/$total_evaluaciones, 4), 2); } echo "%"; ?></td>
							</tr>
						</table>
					</div>
					<div class="seccion"><p><span>2</span>Tabla de alumnos participantes</p></div>
					<div class="contenedor-interno">
						<table class="table table-bordered table-striped table-sm">
							<tr class="text-center">
								<?php if('BACHILLERATO'!=$parametros[0]) { ?>
									<th class="fondo-marista">Carrera</th>
									<th class="fondo-marista">Grado</th> <?php
								} else { ?>
									<th class="fondo-marista">Grupo</th> <?php
								} ?>
								<th class="fondo-marista">Nombre</th>
								<th class="fondo-marista">Evaluaciones contestadas</th>
								<th class="fondo-marista">Último ingreso</th>
							</tr>
							<?php
								if('BACHILLERATO'!=$parametros[0]) {
									$orden_sql .= "ORDER BY P.carrera, P.grado, P.nombre ASC";
								} else {
									$orden_sql .= "ORDER BY G.`grupo`, P.`nombre` ASC";
								}
								// Ejecuta la consulta SQL
								$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
								if ($resultado_busqueda) {
									if (mysqli_num_rows($resultado_busqueda) > 0) {
										// Si hubo resultado obtiene los datos de la sesión y activa la bandera
										while($registro = mysqli_fetch_array($resultado_busqueda)) {
											echo "<tr>";
											if('BACHILLERATO'!=$parametros[0]) {
												echo "<td>" . $registro['carrera'] . "</td>";
												echo "<td>" . $registro['grado'] . "</td>";
											} else {
												echo "<td>" . $registro['grupo'] . "</td>";
											}
											echo "<td style='text-align:left;'>" . $registro['nombre'] . "</td>";
											if ($registro['total_evaluaciones']!=0) {
												if ($registro['num_evaluados']==0) {
													echo "<td class='rojo'>Ninguna</td>";
												} else {
													echo "<td";
													if ($registro['total_evaluaciones']>$registro['num_evaluados']) echo " class='amarillo'";
													echo ">" . $registro['num_evaluados'] . " de " . $registro['total_evaluaciones'] . "</td>";
												}
											} else {
												echo "<td class='rojo'>Ninguna</td>";
											}
											if ($registro['ultimo_acceso']=="") {
												echo "<td colspan='3' class='rojo'>No ha ingresado</td>";
											} else {
												echo "<td>" . $registro['ultimo_acceso'] . "</td>";
											}
											echo "</tr>";
										}
									}
									mysqli_free_result($resultado_busqueda);
								}
							?>
						</table>
					</div>
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