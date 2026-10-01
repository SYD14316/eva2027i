<?php
	//Imprimo los errores
	error_reporting(E_ALL);
  	ini_set("display_errors", 1);
  	//Reviso si la sesion se encuentra iniciada y sino, se inicia
  	if (session_status() === PHP_SESSION_NONE) {session_start();}
  	//Si la variable de sesion esta definida, se envia a la pagina de mis reportes
  	if (isset($_SESSION['zez_a_nombre'])) { header('Location: mis_reportes.php'); }
  	//Se manda a llamar el archivo de configuracion
  	require_once 'lib/config.php';
  	//Si se recibio algo desde benito
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
		//Se define la variable de participante encontrado como falsa
		$participante_encontrado = false;
		//Se almacena lo recibido por benito en la variable respuesta
		$respuesta=$_POST['resultadoingreso'];
		//Se desencripta
		$usuario = desencriptar_ligero($respuesta);
		//Se busca al participante mientras su carera sea diferente a deporte y cultura
		$orden_sql = "SELECT * FROM participantes WHERE correo = '$usuario' and carrera!='DEPORTE Y CULTURA' ";
		//Se ejecuta la sentencia y se almacenan los datos
		$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
		//Se verifica que exista un resultado
		if ($resultado_busqueda) {
			//Se verifica que el resultado obtenido sea mayor a 0
			if (mysqli_num_rows($resultado_busqueda) > 0) {
				//Se define la variable de participante encontrado como verdadera
				$participante_encontrado = true;
				//Mientras existan datos dentro del arreglo obtenido
				while($registro = mysqli_fetch_array($resultado_busqueda)) {
					//Se almacena el dato de cada columna en su respectiva variable de sesion
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
					//Se evalua si el nivel es diferente de vacio
					if ($registro['nivel']!="") {
						//Defino la variable del ciclo en la variable de sesion
						$_SESSION['zez_a_ciclo'] = $ciclo[$registro['nivel']];
					} else {
						//Defino la variable de sesion del ciclo como una union del ciclo de licenciatura y bachillerato
						$_SESSION['zez_a_ciclo'] = $ciclo['LICENCIATURA'] . " / " . $ciclo['BACHILLERATO'];
					}
					//Defino la variable de sesion "sello" como 0
					$_SESSION['zez_a_sello'] = '0';
					//Si el nivel de acceso es de coordinador
					if ("COORDINADOR"==$_SESSION['zez_a_nivel_acceso']) {
						//Busco las carreras en las que el usuario es coordinador y que sean sello
						$orden_sql2 = "SELECT * FROM carreras WHERE coordinador='" . $registro['nombre'] . "' AND sello!='0'";
						//Se ejecuta la sentencia y se almacenan los datos
						$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
						//Se verifica que exista un resultado
						if ($resultado_busqueda2) {
							//Se verifica que el resultado obtenido sea mayor a 0
							if (mysqli_num_rows($resultado_busqueda2) > 0) {
								//Mientras existan datos dentro del arreglo obtenido
								while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
									//Se almacena el valor de sello en la variable de sesion correspondiente
									$_SESSION['zez_a_sello']  = $registro2['sello'];
								}
							}
							// Libera el conjunto de resultados
							mysqli_free_result($resultado_busqueda2);
						}
					}
					//Si el nivel de acceso es de FIDCO, se define la variable de sesion sello como 1
					if ("FIDCO"==$_SESSION['zez_a_nivel_acceso']) $_SESSION['zez_a_sello']  = '1';
					//Se define la variable de sesion jefatura como falsa
					$_SESSION['zez_a_jefatura'] = false;
					//Si el nivel de acceso es de coordinador o FIDCO
					if (("COORDINADOR"==$_SESSION['zez_a_nivel_acceso']) or ("FIDCO"==$_SESSION['zez_a_nivel_acceso'])) {
						//Se buscan los departamentos es los que el nombre del jefe sea igual al del usuario
						$orden_sql2 = "SELECT *  FROM departamentos WHERE jefe='" . $registro['nombre'] . "'";
						//Se ejecuta la sentencia y se almacenan los datos
						$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
						//Se verifica que exista un resultado
						if ($resultado_busqueda2) {
							//Se verifica que el resultado obtenido sea mayor a 0
							if (mysqli_num_rows($resultado_busqueda2) > 0) {
								//Mientras existan datos dentro del arreglo obtenido
								while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
									//Se define como verdadera la variable de sesion de jefatura
									$_SESSION['zez_a_jefatura']  = true;
								}
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
		//Si se encontro el participantes
		if ($participante_encontrado) {
			//Se buscan todos los grupos en los que la matricula del usuario aprezca
			$orden_sql = "SELECT grupo FROM grupos WHERE matricula = '".$_SESSION['zez_a_matricula']."'";
			//Se ejecuta la sentencia y se almacenan los datos
			$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);	
			//Se verifica que exista un resultado
			if ($resultado_busqueda) {
				//Se verifica que el resultado obtenido sea mayor a 0
				if (mysqli_num_rows($resultado_busqueda) > 0) {
					//Mientras existan datos dentro del arreglo obtenido
					while($registro = mysqli_fetch_array($resultado_busqueda)) {
						//Se guardan los grupos resultantes en un arreglo de sesion de los grupos
						$_SESSION['zez_a_grupos'][] = $registro['grupo'];
					}
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
			} elseif (strpos(" INGLES VICERRECTOR FIDCO",$_SESSION['zez_a_nivel_acceso'])!= FALSE) {
				if ((date("Y-m-d") < $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) OR (date("Y-m-d") > $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin'])) {
					header('Location: mis_reportes.php');
				} else {
					header('Location: mis_evaluaciones.php');
				}
			} elseif (strpos(" SUPERUSUARIO ADMINISTRADOR REVISOR DIRECTOR COORDINADOR ACADEMICO ACUDE",$_SESSION['zez_a_nivel_acceso'])!= FALSE) {
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
