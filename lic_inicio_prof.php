<?php
	session_start();
	if (!isset($_SESSION['zez_a_nivel_acceso'])) { // Si no está logueado lo manda al inicio
		session_destroy();
		header('Location: index.php');
	} else { // Si sí está logueado...
	require_once 'lib/config.php';
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<title>Evaluación del Desempeño Docente y ServiciosS</title>
		<link rel="icon" type="image/png" href="img/favicon.ico" />
		<link rel="stylesheet" type="text/css" href="css/estilo.css" />
	</head>
	<body>
		<div>
			<table class="menu">
				<tr>
					<td class="menu"><img src="img/maristas_c.png" /></td>
				</tr>
				<tr>
					<td class="menu">
						<h2>Evaluación del Desempeño Docente y Servicios</h2>
						<h3 class="amarillo">Ciclo <?php echo $ciclo[$_SESSION['zez_a_nivel']]; ?></h3>
					</td>
				</tr>
			</table>
			<hr />
<?php
		if (($_SESSION['zez_a_nivel_acceso'] != 2) OR ($_SESSION['zez_a_nivel'] != "LICENCIATURA")) { // OJO: El número es el grupo mínimo que puede tener derecho a entrar
		// Si no tiene derecho a está página...
?>
			<?php echo $_SESSION['zez_a_nombre']; ?> no estás autorizad<?php if ($_SESSION['zez_a_sexo']=="FEMENINO") { echo "a"; } else { echo "o"; } ?> para ingresar a esta página.<br /><br />
			Para regresar al menú principal haz clic <a href="index.php">aquí</a>.
<?php
		} else { // ##### Si sí tiene derecho a esta página ingresar código desde aquí #####
?>
			<div style="width:100%;text-align:justify;">
				<h3>INTRODUCCIÓN</h3>
				<p>Estimad<?php if ($_SESSION['zez_a_sexo']=="FEMENINO") { echo "a Profesora"; } else { echo "o Profesor"; } ?>:</p>
				<p>¡Bienvenid<?php if ($_SESSION['zez_a_sexo']=="FEMENINO") { echo "a"; } else { echo "o"; } ?> a éste proceso de Auto-Evaluación de los Profesores de Licenciatura!</p>
				<p>Nos es muy grato en esta ocasión compartir contigo la satisfacción por la realización en este semestre, en nuestra Universidad, de los siguientes eventos, en ésta ocasión, solamente del área de Animación, Diseño y Arquitectura (ADA), ya que nos es imposible enlistar los de todas las áreas:</p>
				<ol type="1" start="1">
					<p>
						<li>Global Game Jam, para diseño de videojuegos para alumnos de Animación.</li>
					</p>
					<p>
						<li>Repentina de Arquitectura, para el diseño del museo Hno. Basilio Rueda.</li>
					</p>
					<p>
						<li>Repentina de Diseño de Arte para alumnos de Diseño Gráfico.</li>
					</p>
					<p>
						<li>Visita a Guachimontones para todos los alumnos interesados.</li>
					</p>
				</ol>
				<p>Siguiendo en esta tónica de crecimiento y mejora, te solicitamos tu honesta y profunda participación en esta Evaluación Docente 2017-II.</p>
				<p>Agradecemos mucho que leas cuidadosamente cada reactivo y que contestes con tu opinión de manera objetiva y añadiendo los comentarios que consideres pertinentes en cada uno de los rubros.</p>
				<p>Compartimos contigo la esperanza, dados los esfuerzos realizados, de obtener excelentes resultados en las ya próximas evaluaciones finales.</p>
				<p>Atentamente,</p>
				<p>Mtro. José Luis Olivares Lira<br />Director de Excelencia Operacional</p>
				<br />
				<h3>INSTRUCCIONES PARA RESPONDER EL CUESTIONARIO DE EVALUACIÓN DOCENTE</h3>
				<p>Este instrumento consta de dos tipos de reactivos: cerrados y abiertos.</p>
				<p>Los reactivos cerrados se contestan en escala de 1 a 10. Uno representa el extremo menos deseable de la percepción y 10 el extremo más deseable.</p>
				<p>En algunas preguntas cerradas también encontrarás la opción de No Aplica (NA) para que puedas dar una respuesta cuando no tengas datos para poner una calificación.</p>
				<p>Para contestar los reactivos abiertos, redacta en el espacio correspondiente la opinión que quieras manifestar. Asegúrate de que el tema de la respuesta coincida con el de la pregunta.</p>
				<p>Recuerda contestar lo más sincera y objetivamente posible.</p>
				<br />
				<h3>EVALUACIONES</h3>
			</div>
		</div>
<?php
			// Inicializa las variables usadas para los resultados
			$numero_de_evaluaciones = 0;
			$evaluaciones_mostradas = "";
?>
		<table style="margin-left:auto;margin-right:auto;width:80%;">
			<tr>
				<th><a id=evalua>CUESTIONARIO</a></th>
				<th>ESTADO</th>
			</tr>
			<!-- INICIO: Pone el renglón especial de la evaluación de director de área y docente de carrera -->
			<tr>
				<td>AUTOEVALUACION</td>
<?php
							$identificador = "#AUTOEVALUACION#";
							$numero_de_evaluaciones++; // Agrega la evaluación general al número de evaluaciones
							if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
								// Si ya está evaluado pone "OK"
?>
				<td bgcolor="#66FF66">OK</td>
<?php
							} else {
								// Si no está evaluado pone el botón de evaluación
?>
				<td bgcolor="#FFFF66">
					<div style="display: inline;">
						<form action="lic_evalua_docentes_prof.php" method=post style="display: inline; margin: 0;">
							<input type=hidden name="x" value="x" />
							<input type=submit value="AUTOEVALUAR" />
						</form>
					</div>
				</td>
<?php
							}
?>
			</tr>
		<!-- FIN: Pone el renglón especial de la evaluación de área y docente de carrera -->
<?php
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
			// Genera la orden SQL para hacer la consulta a la tabla de profesores
			$orden_sql = "SELECT DISTINCT carreras.* ";
			$orden_sql .= "FROM carreras ";
			$orden_sql .= "INNER JOIN profesores ON carreras.carrera=profesores.carrera ";
			$orden_sql .= "WHERE profesores.nombre = '" . $_SESSION['zez_a_nombre'] . "' ";
			$orden_sql .= "AND carreras.coordinador <> '" . $_SESSION['zez_a_nombre'] . "'";
			// Ejecuta la consulta SQL
			$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
			if ($resultado_busqueda) {
				if (mysqli_num_rows($resultado_busqueda) > 0) {
					// Si hubo resultado genera la tabla
					while($registro = mysqli_fetch_array($resultado_busqueda)) {
						$identificador = "#" . $registro['carrera'] . "#";
						if (!stristr($evaluaciones_mostradas, $identificador)) {
							$evaluaciones_mostradas = $evaluaciones_mostradas . $identificador;
							echo "<tr>";
							echo "<td>COORDINADOR";
							if (strtoupper($registro['sexo'])=="FEMENINO") {
								echo "A";
							}
							echo " DE " . $registro['carrera'] . "</td>";
							$identificador = "#" . $registro['carrera'] . "#";
							if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
								// Si ya está evaluado pone "OK"
?>
				<td bgcolor="#66FF66">OK</td>
<?php
							} else {
								// Si no está evaluado pone el botón de evaluación
?>
				<td bgcolor="#FFFF66">
					<div style="display: inline;">
						<form action="lic_evalua_coordinador_prof.php" method=post style="display: inline; margin: 0;" accept-charset="UTF-8">
							<input type=hidden name="carrera" value="<?php echo $registro['carrera']; ?>" />
							<input type=hidden name="nombre" value="<?php echo $registro['coordinador']; ?>" />
							<input type=hidden name="sexo" value="<?php echo strtoupper($registro['sexo']); ?>" />
							<input type=submit value="EVALUAR" />
						</form>
					</div>
				</td>
<?php
							}
							echo "</tr>";
							// Agrega el último registro al conteo de evaluaciones totales
							$numero_de_evaluaciones++;
						}
					}
				}
				// Libera el conjunto de resultados
				mysqli_free_result($resultado_busqueda);
			}
			// Cierra la BDD
			mysqli_close($base_de_datos);
?>
		<!-- INICIO: Pone el renglón especial de la evaluación de espacios, recursos y servicios -->
			<tr>
				<td>ESPACIOS, RECURSOS Y SERVICIOS</td>
<?php
			$identificador = "#ERyS#";
			$numero_de_evaluaciones++; // Agrega la evaluación general al número de evaluaciones
			if (stristr($_SESSION['zez_a_evaluados'], $identificador)) {
				// Si ya está evaluado pone "OK"
?>
				<td bgcolor="#66FF66">OK</td>
<?php
			} else {
				// Si no está evaluado pone el botón de evaluación
?>
				<td bgcolor="#FFFF66">
					<div style="display: inline;">
						<form action="lic_evalua_erys_prof.php" method=post style="display: inline; margin: 0;">
							<input type=hidden name="x" value="x" />
							<input type=submit value="EVALUAR" />
						</form>
					</div>
				</td>
<?php
			}
?>
			</tr>
		<!-- FIN: Pone el renglón especial de la evaluación de espacios, recursos y servicios -->
		</table>
<?php
			// Registra en la base de datos el número de evaluaciones que en total tiene este alumno
			if ($_SESSION['zez_a_total_evaluaciones']<>$numero_de_evaluaciones) {
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
				$orden_sql = "UPDATE alumnos SET total_evaluaciones='" . $numero_de_evaluaciones . "' WHERE id='". $_SESSION['zez_a_id'] . "'";
				mysqli_query($base_de_datos, $orden_sql);
				$_SESSION['zez_a_total_evaluaciones'] = $numero_de_evaluaciones;
				// Cierra la BDD
				mysqli_close($base_de_datos);
			}
?>
		<div style="margin-left:auto;margin-right:auto;width:80%;text-align:center;">
			<br />
			<br />
			<hr />
			<p>Si te vas a retirar es recomendable que des clic en el siguiente botón para que nadie más use tu cuenta.</p>
<?php
			// Si tiene evaluaciones pendientes pide una confirmación antes de salir
			$confirmacion = "";
			if ($_SESSION['zez_a_total_evaluaciones'] > $_SESSION['zez_a_num_evaluados']) {
				$confirmacion = $confirmacion . "return confirm('Tienes ";
				$confirmacion = $confirmacion . ($_SESSION['zez_a_total_evaluaciones'] - $_SESSION['zez_a_num_evaluados']);
				$confirmacion = $confirmacion . " evaluaciones pendientes y tu opinión nos ayudaría a mejorar los servicios que recibes ¿estas segur";
				if ($_SESSION['zez_a_sexo']=="FEMENINO") {
					$confirmacion = $confirmacion . "a";
				} else {
					$confirmacion = $confirmacion . "o";
				}
				$confirmacion = $confirmacion . " de que deseas salir?')";
			}
?>
			<form action="salir.php" method=post onsubmit="<?php echo $confirmacion ?>">
				<input type=submit value="SALIR" />
			</form>
		</div>
		<br />
<?php
		} // ##### Si sí tiene derecho a esta página ingresar código hasta aquí #####
?>
	</body>
</html>
<?php
	}
?>