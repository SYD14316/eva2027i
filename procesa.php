<?php
//Muestro los errores
        /*error_reporting(E_ALL);
        ini_set("display_errors", 1);*/
    //

	session_start();
	require_once 'lib/config.php';
	if (!isset($_SESSION['zez_a_nombre'])) {
		header('Location: salir.php');
	} elseif (($_SESSION['zez_a_nivel'] != "LICENCIATURA") OR (!strpos(" ALUMNO PROFESOR COORDINADOR VICERRECTOR FIDCO",$_SESSION['zez_a_nivel_acceso'])) OR (!$_POST) OR (date("Y-m-d") < $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) OR (date("Y-m-d") > $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin'])) {
		header('Location: index.php');
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
	if(!$parametros_validos) {
		header('Location: index.php');
	}
?>
<html>
	<?php include_once HEADER; ?>
	<script type="text/javascript" src="lib/functions.js"></script>
	<body onload="submitform()">
		<div class="container">
			<div class="card">
				<div class="card-body">
					<?php
						// Inicializa la variable de control
						$evaluacion_incompleta = false;
						$respuesta_larga = false;
						// Borra las variables que se utilizarán más adelante
						unset($tamano_set_valores);
						unset($set_valores);
						unset($secciones);
						unset($numero_pregunta);
						unset($numero_comentario);
						// Incluye un archivo PHP externo (se asume que contiene configuraciones o datos necesarios)
						require_once($parametros[0] . ".php");
						// Genera nombres de variables para preguntas y comentarios
						for ($contador_items = 1; $contador_items <= $total_reactivos; $contador_items++) {
						    $numero_pregunta[$contador_items] = "r" . str_pad($contador_items, 2, "0", STR_PAD_LEFT);
						    $numero_comentario[$contador_items] = $numero_pregunta[$contador_items] . "c";
						}
						// Revisa todas las preguntas que deben ser contestadas y que las preguntas abiertas no sean muy largas
						for ($contador_secciones = 1; $contador_secciones <= $total_secciones; $contador_secciones++) {
						    for ($contador_subsecciones = 1; $contador_subsecciones <= $total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
						        for ($contador_items = $secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items <= $secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
						            if ($secciones[$contador_secciones][$contador_subsecciones]['tipo'] == "CERRADA") {
						                // Verifica si no se ha recibido una respuesta para preguntas cerradas
						                if (!isset($_POST[$numero_pregunta[$contador_items]])) {
						                    $evaluacion_incompleta = true;
						                }
						            } elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo'] == "ABIERTA") {
						                // Verifica si las respuestas a preguntas abiertas son muy largas
						                if (mb_strlen($_POST[$numero_pregunta[$contador_items]], 'UTF-8') > $maximo_caracteres) {
						                    $respuesta_larga = true;
						                }
						            }
						            if ($secciones[$contador_secciones][$contador_subsecciones]['comentario']) {
						                // Verifica si los comentarios son muy largos
						                if (mb_strlen($_POST[$numero_comentario[$contador_items]], 'UTF-8') > $maximo_caracteres) {
						                    $respuesta_larga = true;
						                }
						            }
						        }
						    }
						}
						// Determina si la evaluación está incompleta o tiene respuesta muy larga
						$motivo_regreso = "";
						if ($evaluacion_incompleta) { $motivo_regreso .= "Incompleta"; }
						if ($respuesta_larga) { $motivo_regreso .= "Larga"; }
						// Si la evaluación está incompleta o alguna respuesta es demasiado larga, realiza una acción adicional
						if ($evaluacion_incompleta OR $respuesta_larga) { ?>
							<form name="Regresar" action="evalua.php" method="post" autocomplete="off" accept-charset="UTF-8">
							    <!-- Este campo oculto almacena los parámetros de la evaluación encriptados -->
							    <input type="hidden" name="parametros" value="<?php echo $_POST['parametros']; ?>" />
							    <?php
							    // Este bucle PHP genera campos de entrada ocultos en función de ciertas condiciones
							    for ($contador_secciones = 1; $contador_secciones <= $total_secciones; $contador_secciones++) {
							        for ($contador_subsecciones = 1; $contador_subsecciones <= $total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
							            for ($contador_items = $secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items <= $secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
							                if ($secciones[$contador_secciones][$contador_subsecciones]['tipo'] == "CERRADA") {
							                    // Si es una pregunta cerrada y ha sido contestada, se agrega un campo oculto con su valor
							                    if (isset($_POST[$numero_pregunta[$contador_items]])) {
							                        echo "<input type='hidden' name='" . $numero_pregunta[$contador_items] . "' value='" . $_POST[$numero_pregunta[$contador_items]] . "' />";
							                    }
							                } elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo'] == "MULTIPLE") {
							                    // Si es una pregunta de opciones múltiples
							                    if ($secciones[$contador_secciones][$contador_subsecciones]['cero'] == "") {
							                        $inicio_contador_respuestas = 1;
							                        $incremento_respuestas = 0;
							                    } else {
							                        $inicio_contador_respuestas = 0;
							                        $incremento_respuestas = 1;
							                    }
							                    
							                    // Se generan campos ocultos para cada respuesta seleccionada
							                    for ($contador_respuestas = $inicio_contador_respuestas; $contador_respuestas <= $tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]; $contador_respuestas++) {
							                        if (isset($_POST[$numero_pregunta[$contador_items] . "_" . $contador_respuestas])) {
							                            echo "<input type='hidden' name='" . $numero_pregunta[$contador_items] . "_$contador_respuestas' value='" . $_POST[$numero_pregunta[$contador_items] . "_" . $contador_respuestas] . "' />";
							                        }
							                    }
							                } else {
							                    // Para otros tipos de preguntas, se agrega un campo oculto con su valor
							                    echo "<input type='hidden' name='" . $numero_pregunta[$contador_items] . "' value='" . quita_comillas(limpiarcampo($_POST[$numero_pregunta[$contador_items]])) . "' />";
							                }
							                
							                // Si la pregunta tiene un comentario, se agrega un campo oculto para el comentario
							                if ($secciones[$contador_secciones][$contador_subsecciones]['comentario']) {
							                    echo "<input type='hidden' name='" . $numero_comentario[$contador_items] . "' value='" . quita_comillas(limpiarcampo($_POST[$numero_comentario[$contador_items]])) . "' />";
							                }
							            }
							        }
							    }
							    ?>
							    <!-- Este campo oculto almacena el motivo de regreso (Incompleta, Larga) -->
							    <input type="hidden" name="regresada" value="<?php echo $motivo_regreso; ?>" />
							</form>
							<script type="text/javascript" language="javascript">
								document.Regresar.submit();
							</script> <?php
						} else { // Si la evaluación está completa la registra
							// Conectarse al servidor de la base de datos (BDD)
							$base_de_datos = mysqli_connect($bdd_servidor, $bdd_usuario, $bdd_clave, $bdd_nombre);

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

							// Valida que no se haya registrado previamente esta evaluación
							if (!stristr($_SESSION['zez_a_evaluados'], $parametros[1])) {
							    // Registra la evaluación en la tabla de evaluaciones

							    // La variable $orden_sql se utiliza para construir la consulta SQL
							    $orden_sql = $secuencia_sql[1].", aplicacion";
							    // La variable $orden_sql_parte2 se utiliza para construir la segunda parte de la consulta SQL
							    $orden_sql_parte2 = "";
							    //Defino si la evaluacion es de primera o de segunda aplicacion
								$orden_sql_parte2 .= "', '".NUMERO_APLICACION;

							    for ($contador_secciones = 1; $contador_secciones <= $total_secciones; $contador_secciones++) {
							        for ($contador_subsecciones = 1; $contador_subsecciones <= $total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
							            for ($contador_items = $secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items <= $secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {

							                // Agrega el nombre del campo a la consulta SQL
							                $orden_sql .= ", " . $numero_pregunta[$contador_items];
							                
							                if ($secciones[$contador_secciones][$contador_subsecciones]['tipo'] == "CERRADA") {
							                    // Si es una pregunta cerrada
							                    if ("987" == $_POST[$numero_pregunta[$contador_items]]) {
							                        // Si el valor es "987", agrega ese valor directamente a la consulta
							                        $orden_sql_parte2 .= "', '987";
							                    } else {
							                        // De lo contrario, obtiene el valor correspondiente y lo agrega
							                        $orden_sql_parte2 .= "', '" . $set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']][$_POST[$numero_pregunta[$contador_items]]]['valor'];
							                    }
							                } elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo'] == "MULTIPLE") {
							                    // Si es una pregunta de opciones múltiples
							                    if ($secciones[$contador_secciones][$contador_subsecciones]['cero'] == "") {
							                        $inicio_contador_respuestas = 1;
							                        $incremento_respuestas = 0;
							                    } else {
							                        $inicio_contador_respuestas = 0;
							                        $incremento_respuestas = 1;
							                    }
							                    $valores_multiple = "";
							                    // Recorre las respuestas seleccionadas y las agrega a la consulta SQL
							                    for ($contador_respuestas = $inicio_contador_respuestas; $contador_respuestas <= $tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]; $contador_respuestas++) {
							                        if (isset($_POST[$numero_pregunta[$contador_items] . "_" . $contador_respuestas])) {
							                            $valores_multiple .= "#" . $_POST[$numero_pregunta[$contador_items] . "_" . $contador_respuestas] . "#";
							                        }
							                    }
							                    $orden_sql_parte2 .= "', '" . $valores_multiple;
							                } else {
							                    // Para otros tipos de preguntas, agrega el valor a la consulta SQL
							                    $orden_sql_parte2 .= "', '" . limpiarcampo($_POST[$numero_pregunta[$contador_items]]);
							                }
							                // Si la pregunta tiene un comentario, agrega el nombre del campo y su valor a la consulta SQL
							                if ($secciones[$contador_secciones][$contador_subsecciones]['comentario']) {
							                    $orden_sql .= ", " . $numero_comentario[$contador_items];
							                    $orden_sql_parte2 .= "', '" . limpiarcampo($_POST[$numero_comentario[$contador_items]]);
							                }
							            }
							        }
							    }
							    // Si no había campos en la consulta SQL original, se quitan las comas iniciales
							    if ('' == $secuencia_sql[1]) {
							        $orden_sql = substr($orden_sql, 2);
							    }

							    // Construye la consulta SQL completa
							    $orden_sql = "INSERT INTO " . $parametros[0] . " (" . $orden_sql;

							    if ('' != $secuencia_sql[2]) {
							        $orden_sql .= ") VALUES ('" . $secuencia_sql[2];
							    } else {
							        $orden_sql .= ") VALUES (";
							        $orden_sql_parte2 = substr($orden_sql_parte2, 3);
							    }

							    $orden_sql .= $orden_sql_parte2 . "')";
							    // Ejecuta la consulta SQL en la base de datos
							    mysqli_query($base_de_datos, $orden_sql);

							    // Registra la evaluación en el usuario
							    $_SESSION['zez_a_evaluados'] = $_SESSION['zez_a_evaluados'] . $parametros[1];
							    $orden_sql = "UPDATE participantes SET evaluados='" . $_SESSION['zez_a_evaluados'] . "' WHERE id='" . $_SESSION['zez_a_id'] . "'";
							    mysqli_query($base_de_datos, $orden_sql);

							    $_SESSION['zez_a_num_evaluados']++;

							    $orden_sql = "UPDATE participantes SET num_evaluados='" . $_SESSION['zez_a_num_evaluados'] . "' WHERE id='" . $_SESSION['zez_a_id'] . "'";
							    mysqli_query($base_de_datos, $orden_sql);
							}

							// Cierra la conexión a la base de datos
							mysqli_close($base_de_datos); ?>
							<form name="Regresar" action="mis_evaluaciones.php#evaluaciones" method=post autocomplete="off" accept-charset="UTF-8"></form>
							<script type="text/javascript" language="javascript">
								document.Regresar.submit();
							</script> <?php
						}
					?>
				</div>
			</div>
		</div>
	</body>
</html>