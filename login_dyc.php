<?php
	if (session_status() === PHP_SESSION_NONE) {session_start();}
	if (isset($_SESSION['zez_a_nombre'])) { header('Location: mis_reportes.php'); }
	require_once 'lib/config.php';
	if ($_POST['resultadoingreso']) {
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
		$participante_encontrado = false;
		$respuesta=$_POST['resultadoingreso'];
		$usuario = desencriptar_ligero($respuesta);
		// Buscar al participante
		$orden_sql = "SELECT * FROM participantes WHERE correo = '$usuario' and carrera='DEPORTE Y CULTURA' ";
		$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
		if ($resultado_busqueda) {
			if (mysqli_num_rows($resultado_busqueda) > 0) {
				$participante_encontrado = true;
				while($registro = mysqli_fetch_array($resultado_busqueda)) {
					$_SESSION['zez_a_id'] = $registro['id'];
					$_SESSION['zez_a_nivel'] = $registro['nivel'];
					$_SESSION['zez_a_matricula'] = $registro['matricula'];
					$_SESSION['zez_a_nombre'] = $registro['nombre'];
					$_SESSION['zez_a_carrera'] = $registro['carrera'];
					$_SESSION['zez_a_grado'] = $registro['grado'];
					$_SESSION['zez_a_sexo'] = strtoupper($registro['sexo']);
					$_SESSION['zez_a_situacion'] = strtoupper($registro['situacion']);
					$_SESSION['zez_a_evaluados'] = $registro['evaluados'];
					$_SESSION['zez_a_num_evaluados'] = $registro['num_evaluados'];
					$_SESSION['zez_a_total_evaluaciones'] = $registro['total_evaluaciones'];
					$_SESSION['zez_a_ultimo_acceso'] = $registro['ultimo_acceso'];
					$_SESSION['zez_a_nivel_acceso'] = $registro['nivel_acceso'];
					if ($registro['nivel']!="") {
						$_SESSION['zez_a_ciclo'] = $ciclo[$registro['nivel']];
					} else {
						$_SESSION['zez_a_ciclo'] = $ciclo['LICENCIATURA'] . " / " . $ciclo['BACHILLERATO'];
					}
					$_SESSION['zez_a_sello'] = '0';
					if ("COORDINADOR"==$_SESSION['zez_a_nivel_acceso']) {
						$orden_sql2 = "SELECT * FROM carreras WHERE coordinador='" . $registro['nombre'] . "' AND sello!='0'";
						$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
						if ($resultado_busqueda2) {
							if (mysqli_num_rows($resultado_busqueda2) > 0) {
								while($registro2 = mysqli_fetch_array($resultado_busqueda2)) { $_SESSION['zez_a_sello']  = $registro2['sello']; }
							}
							// Libera el conjunto de resultados
							mysqli_free_result($resultado_busqueda2);
						}
					}
					if ("FIDCO"==$_SESSION['zez_a_nivel_acceso']) $_SESSION['zez_a_sello']  = '1';
					$_SESSION['zez_a_jefatura'] = false;
					if (("COORDINADOR"==$_SESSION['zez_a_nivel_acceso']) or ("FIDCO"==$_SESSION['zez_a_nivel_acceso'])) {
						$orden_sql2 = "SELECT * ";
						$orden_sql2 .= "FROM departamentos ";
						$orden_sql2 .= "WHERE jefe='" . $registro['nombre'] . "'";
						$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
						if ($resultado_busqueda2) {
							if (mysqli_num_rows($resultado_busqueda2) > 0) {
								while($registro2 = mysqli_fetch_array($resultado_busqueda2)) { $_SESSION['zez_a_jefatura']  = true; }
							}
							// Libera el conjunto de resultados
							mysqli_free_result($resultado_busqueda2);
						}
					}
				}
			}
			// Libera el conjunto de resultados
			mysqli_free_result($resultado_busqueda);
		}

		if ($participante_encontrado) {
			$orden_sql = "SELECT grupo FROM grupos WHERE matricula = '".$_SESSION['zez_a_matricula']."'";
			$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);	
			if ($resultado_busqueda) {
				if (mysqli_num_rows($resultado_busqueda) > 0) {
					while($registro = mysqli_fetch_array($resultado_busqueda)) { $_SESSION['zez_a_grupos'][] = $registro['grupo']; }
				}
				// Libera el conjunto de resultados
				mysqli_free_result($resultado_busqueda);
			}
			// Registra el último acceso
			date_default_timezone_set($zona_horaria);
			$orden_sql = "UPDATE participantes SET ultimo_acceso='" . date("Y-m-d") . "' WHERE id='". $_SESSION['zez_a_id'] . "'";
			mysqli_query($base_de_datos, $orden_sql);
			$_SESSION['zez_a_ultimo_acceso'] = date("Y-m-d");
			if ("COORDINADOR" == $_SESSION['zez_a_nivel_acceso']) {
				$orden_sql = "SELECT * FROM `carreras` WHERE `coordinador`='" . $_SESSION['zez_a_nombre'] . "' ORDER BY `carrera`";
				// Ejecuta la consulta SQL
				$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
				if ($resultado_busqueda) {
					if (mysqli_num_rows($resultado_busqueda) > 0) {
						// Si hubo resultado genera la tabla
						while($registro = mysqli_fetch_array($resultado_busqueda)) {
							$_SESSION['zez_a_carrera'] = $registro['carrera'];
						}
					}
					mysqli_free_result($resultado_busqueda);
				}	
			}
			// Cierra la BDD
			mysqli_close($base_de_datos);
			// Si encontró el participante carga el menú principal que le corresponda
			date_default_timezone_set($zona_horaria);
			if (strpos(" ALUMNO PROFESOR",$_SESSION['zez_a_nivel_acceso'])!= FALSE) {
				if (date("Y-m-d") < $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) {
					header('Location: no_iniciada.php');
				} elseif (date("Y-m-d") > $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin']) {
					header('Location: cerrada.php');
				} else {
					header('Location: mis_evaluaciones.php');
				}
			} elseif (strpos(" INGLES COORDINADOR VICERRECTOR FIDCO",$_SESSION['zez_a_nivel_acceso'])!= FALSE) {
				if ((date("Y-m-d") < $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) OR (date("Y-m-d") > $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin'])) {
					header('Location: mis_reportes.php');
				} else {
					header('Location: mis_evaluaciones.php');
				}
			} elseif (strpos(" SUPERUSUARIO ADMINISTRADOR REVISOR DIRECTOR ACADEMICO ACUDE",$_SESSION['zez_a_nivel_acceso'])!= FALSE) {
				header('Location: mis_reportes.php');
			} else {
				header('Location: salir.php');
			}
			exit;
		} else {
			// Cierra la BDD
			mysqli_close($base_de_datos);
			// Enviar al error
			header('Location: error_ingreso.php');
		}
	}else{
		header('Location: index.php');
	}
?>
