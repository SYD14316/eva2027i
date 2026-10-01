<?php
	//Inicio la sesion
	session_start();
	//Extraigo el archivo de configuracion
	require_once 'lib/config.php';
	//Reviso si la variable de sesion
	if (!isset($_SESSION['zez_a_nombre'])) {
		//Salgo de la aplicacion
		header('Location: salir.php');
	} elseif (
		//si el niver es igual a licenciatura
		($_SESSION['zez_a_nivel'] != "LICENCIATURA") OR 
		//Si el nivel de acceso corresponde a alguno de los definidos
		(!strpos(" ALUMNO PROFESOR COORDINADOR VICERRECTOR FIDCO",$_SESSION['zez_a_nivel_acceso'])) OR 
		//Si la variable post est vacia
		(!$_POST) OR 
		//Si la fecha actual es menor a la fecha de inicio de la evaluacion
		(date("Y-m-d") < $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) OR 
		//Si la fecha actual es mayor a la fecha de fin de la evaluacion
		(date("Y-m-d") > $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin'])
	) {
		//Redirecciono al inicio de la pagina
		header('Location: index.php');
	}
	//Se creea una variable para los parametros validos
	$parametros_validos = false;
	//Se destrulle la variable "parametros"
	unset($parametros);
	//Se define una vriable vacia para el string de los parametros
	$string_parametros = "";
	//Compruebo si se ha enviado un valor a través del método POST con el nombre "parametros"
	if (isset($_POST['parametros'])) {
		//Desencripto el valor enviado
		$string_parametros = desencriptar($_POST['parametros']);
		// Se verifica si los primeros 2 caracteres y los ultimos 2 caracteres de la cadena "string_parametros", son gual a "%%"
		if ((substr($string_parametros,0,2)=="%%") AND (substr($string_parametros,(strlen($string_parametros)-2),2)=="%%")) {
			//Redefino la cadena eliminando las primero y ultimos "%%"
			$string_parametros = substr($string_parametros,2,strlen($string_parametros)-4);
			//Defino la variable "parametros_validos" como verdadera
			$parametros_validos = true;
			// Comprueba si la cadena $string_parametros contiene el carácter "¬"
			if (strpos($string_parametros, "¬") === FALSE) {
			    // Si no lo contiene, simplemente agrega la cadena completa como un único parámetro al arreglo $parametros
			    $parametros[] = $string_parametros;
			} else {
			    // Si la cadena contiene el carácter "¬", se realiza un proceso de separación de parámetros
			    do {
			        // Extrae el primer parámetro desde el inicio de la cadena hasta el primer "¬" encontrado
			        $parametros[] = substr($string_parametros, 0, strpos($string_parametros, "¬"));
			        // Actualiza la cadena $string_parametros eliminando el primer parámetro y el "¬" correspondiente
			        $string_parametros = substr($string_parametros, strpos($string_parametros, "¬") + 2);
			    } while (!(strpos($string_parametros, "¬") === FALSE));
			    // Agrega el último parámetro que queda en la cadena después de la separación
			    $parametros[] = $string_parametros;
			}
		}
	}
	// Verifica si la variable $parametros_validos es falsa (o nula)
	if (!$parametros_validos) {
	    // Si $parametros_validos es falsa, realiza una redirección a la página "index.php"
	    header('Location: index.php');
	}
	// Verifica si la variable $_POST['opcion_evaluacion'] está definida en la solicitud POST
	if (isset($_POST['opcion_evaluacion'])) {
	    // Si está definida, verifica si su valor es igual a "0"
	    if ($_POST['opcion_evaluacion'] == "0") {
	        // Si el valor es "0", redirige al usuario a la página "mis_evaluaciones.php#evaluaciones"
	        header('Location: mis_evaluaciones.php#evaluaciones');
	        // Después de realizar la redirección, se utiliza die() para detener la ejecución del script actual.
	        die();
	    } elseif ($_POST['opcion_evaluacion'] == "SOLO_REGISTRAR") {
	        // Si el valor es "SOLO_REGISTRAR", se ejecuta el siguiente bloque de código:
	        // Conectarse al servidor de la base de datos (BDD) utilizando los valores de conexión proporcionados
	        $base_de_datos = mysqli_connect($bdd_servidor, $bdd_usuario, $bdd_clave, $bdd_nombre);
	        // Verificar la conexión a la base de datos
	        if (mysqli_connect_errno()) {
	            // Si la conexión falla, muestra un mensaje de error y detiene la ejecución del script
	            printf("Falló la conexión: %s", mysqli_connect_error());
	            exit();
	        }
	        // Cambiar el conjunto de caracteres de la base de datos a utf8
	        if (!mysqli_set_charset($base_de_datos, "utf8")) {
	            // Si hay un error al cambiar el conjunto de caracteres, muestra un mensaje de error y detiene la ejecución del script
	            printf("Error cargando el conjunto de caracteres utf8: %s", mysqli_error($base_de_datos));
	            exit();
	        }
	        // Registra la evaluación en el usuario actual
	        $_SESSION['zez_a_evaluados'] = $_SESSION['zez_a_evaluados'] . $parametros[1];
	        // Actualiza la base de datos con la información de la evaluación en el campo "evaluados"
	        $orden_sql = "UPDATE participantes SET evaluados='" . $_SESSION['zez_a_evaluados'] . "' WHERE id='". $_SESSION['zez_a_id'] . "'";
	        mysqli_query($base_de_datos, $orden_sql);
	        // Realiza una operación con str_replace para validar la cantidad (no se muestra el resultado aquí)
	        str_replace('#','',$parametros[1],$cantidad_validada);
	        // Actualiza el número de evaluados en la base de datos
	        $_SESSION['zez_a_num_evaluados'] += ($cantidad_validada/2);
	        $orden_sql = "UPDATE participantes SET num_evaluados='" . $_SESSION['zez_a_num_evaluados'] . "' WHERE id='". $_SESSION['zez_a_id'] . "'";
	        mysqli_query($base_de_datos, $orden_sql);
	        // Redirige al usuario a la página "mis_evaluaciones.php#evaluaciones"
	        header('Location: mis_evaluaciones.php#evaluaciones');
	        // Cierra la conexión a la base de datos
	        mysqli_close($base_de_datos);
	    }
	}
	// Conectarse al servidor de la base de datos (BDD)
	$base_de_datos = mysqli_connect($bdd_servidor,$bdd_usuario,$bdd_clave,$bdd_nombre);
	// Verificar la conexión
	if (mysqli_connect_errno()) {
		//se imprime un mensaje de error
		printf("Falló la conexión: %s", mysqli_connect_error());
		//Se detiene la ejecucion del script
		exit();
	}
	// Cambiar el conjunto de caracteres a utf8
	if (!mysqli_set_charset($base_de_datos, "utf8")) {
		//se imprime un mensaje de error
		printf("Error cargando el conjunto de caracteres utf8: %s", mysqli_error($base_de_datos));
		//Se detiene la ejecucion del script
		exit();
	}
	// ########### INICIO DE BLOQUE INICIALIZAR VARIABLES
	//Identifico si es un masculino o femenino y dependiendo, le agrego las letra a (femenino) u la letra o (masculino)
	if ($_SESSION['zez_a_sexo']=="FEMENINO") { $AuO = "a"; } else { $AuO = "o"; }
		//Se eliminan las siguientes variables
		unset($tamano_set_valores);
		unset($set_valores);
		unset($secciones);
		unset($numero_pregunta);
		unset($numero_comentario);
		unset($texto_pregunta);	
		unset($respuestas);
		//
		require_once($parametros[0] . ".php");
		//
		for ($contador_items = 1; $contador_items <= $total_reactivos; $contador_items++) {
		    // Crear el número de pregunta con formato "r01", "r02", etc.
		    $numero_pregunta[$contador_items] = "r" . str_pad($contador_items, 2, "0", STR_PAD_LEFT);

		    // Inicializar el fondo relacionado con la pregunta como una cadena vacía
		    $fondo[$contador_items] = "";

		    // Crear el número de comentario con formato "r01c", "r02c", etc.
		    $numero_comentario[$contador_items] = $numero_pregunta[$contador_items] . "c";

		    // Inicializar el fondo relacionado con el comentario como una cadena vacía
		    $fondo_comentario[$contador_items] = "";
		}
		//Se define la variable "usar_contador_interno" como falsa
		$usar_contador_interno = false;
		for ($contador_secciones=1;$contador_secciones<=$total_secciones;$contador_secciones++) {
			for ($contador_subsecciones=0; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
				if ($secciones[$contador_secciones][$contador_subsecciones]['limitada']!="") $usar_contador_interno = true;
			}
		}
		// Bucle exterior para iterar a través de las secciones
		for ($contador_secciones = 1; $contador_secciones <= $total_secciones; $contador_secciones++) {
		    // Bucle interior para iterar a través de las subsecciones de la sección actual
		    for ($contador_subsecciones = 0; $contador_subsecciones <= $total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
		        // Verifica si la propiedad 'limitada' de la subsección actual no está vacía
		        if ($secciones[$contador_secciones][$contador_subsecciones]['limitada'] != "") {
		            // Si la propiedad 'limitada' no está vacía, establece la variable $usar_contador_interno en true
		            $usar_contador_interno = true;
		        }
		    }
		}
		// La variable $usar_contador_interno se establecerá en true si al menos una subsección tiene 'limitada' no vacío ?>
	<html>
		<?php include_once HEADER; ?>
		<script type="text/javascript" src="lib/functions.js"></script>
		<div class="d-flex fondo-azul-marista">
			<div><a class="btn text-light" onclick="redirigir('mis_evaluaciones.php#evaluaciones');"><strong>Mis Evaluaciones</strong></a></div>
			<div><a class="btn btn-warning text-dark"><strong>Evaluación Actual</strong></a></div>
				<?php
					if (strpos(" COORDINADOR VICERRECTOR FIDCO",$_SESSION['zez_a_nivel_acceso'])!= FALSE) {
						echo "<div><a class=\"btn text-light\" onclick=\"redirigir('mis_reportes.php');\"><strong>Mis Reportes</strong></a></div>";
					}
				?>
			<div class="ml-auto">
				<a class="btn text-light" onclick="redirigir('salir.php');"><strong>Salir</strong></a>
			</div>
		</div>
		<body>
			<div class="container-fluid">
				<div class="card">
					<div class="card-header">
						<h1>
							<img src="lib/img/blanco.png" class="img-form-left" />
							<?php echo preg_replace('/\s/', ' ', $encabezado[1]); ?>
						</h1>
						<span><?php echo preg_replace('/\s/', ' ', $encabezado[2]); ?></span>
						<?php 
						if ($parametros[0]=='lic_profesores_por_profesores') {
							echo "<br><span>".preg_replace('/\s/', ' ', $encabezado[3])."</span>";
						}
						?>
					</div>
					<div class="card-body">
						<?php
							// ########### INICIO BLOQUE RECUPERAR RESPUESTAS
								if (isset($_POST['regresada'])) {
								    // Se verifica si se ha enviado el formulario con el campo 'regresada'
								    for ($contador_secciones = 1; $contador_secciones <= $total_secciones; $contador_secciones++) {
								        // Bucle para recorrer las secciones
								        for ($contador_subsecciones = 1; $contador_subsecciones <= $total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
								            // Bucle para recorrer las subsecciones dentro de cada sección
								            for ($contador_items = $secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items <= $secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
								                // Bucle para recorrer los items (preguntas o elementos) dentro de cada subsección
								                if ($secciones[$contador_secciones][$contador_subsecciones]['tipo'] == "CERRADA") {
								                    // Si el tipo de elemento es "CERRADA" (pregunta cerrada)
								                    if (isset($_POST[$numero_pregunta[$contador_items]])) {
								                        // Si se ha marcado la pregunta cerrada, elimina cualquier clase de resaltado (rojo)
								                        $fondo[$contador_items] = "";
								                    } else {
								                        // Si no se ha marcado la pregunta cerrada, agrega una clase de resaltado (rojo)
								                        $fondo[$contador_items] = " class='rojo'";
								                    }
								                } elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo'] == "ABIERTA") {
								                    // Si el tipo de elemento es "ABIERTA" (pregunta abierta)
								                    // Obtiene la respuesta del usuario para esta pregunta abierta
								                    $respuestas[$contador_items] = $_POST[$numero_pregunta[$contador_items]];
								                    // Verifica si la longitud de la respuesta supera un límite (amarillo)
								                    if (mb_strlen(pone_comillas($respuestas[$contador_items]), 'UTF-8') > $maximo_caracteres) {
								                        $fondo[$contador_items] = " class='amarillo'";
								                    }
								                }
								                if ($secciones[$contador_secciones][$contador_subsecciones]['comentario']) {
								                    // Si esta pregunta tiene un campo de comentario asociado
								                    // Obtiene el comentario del usuario para esta pregunta
								                    $comentarios[$contador_items] = $_POST[$numero_comentario[$contador_items]];
								                    // Verifica si la longitud del comentario supera un límite (amarillo)
								                    if (mb_strlen(pone_comillas($comentarios[$contador_items]), 'UTF-8') > $maximo_caracteres) {
								                        $fondo_comentario[$contador_items] = " class='amarillo'";
								                    }
								                }
								            }
								        }
								    }
								    if (strpos($_POST['regresada'], "Incompleta") !== false) {
								        // Si el formulario fue marcado como "Incompleto", muestra un mensaje en rojo
								        echo "<p class='rojo'>Necesitamos que completes tu evaluación contestando las preguntas marcadas en rojo, gracias.</p><br />";
								    }
								    if (strpos($_POST['regresada'], "Larga") !== false) {
								        // Si el formulario fue marcado como "Largo", muestra un mensaje en rojo
								        echo "<p class='rojo'>Por favor reduce el tamaño de las respuestas marcadas en amarillo, gracias.</p><br />";
								    }
								}

							// ########### FIN BLOQUE RECUPERAR RESPUESTAS
							// ########### INICIO DEL CUESTIONARIO
						?>
						<form action="procesa.php" method="post" autocomplete="off" accept-charset="UTF-8" onsubmit="return confirm('¿Confirmas que quieres enviar esta evaluación?')">
							<input type="hidden" name="parametros" value="<?php echo $_POST['parametros']; ?>" />
							<?php
								// ============== PONE EL TEXTO PREVIO A LOS REACTIVOS
									if ($previo['cuerpo'][0] != "") {
									    // Verifica si el primer elemento del arreglo 'cuerpo' no está vacío
									    if ($previo['encabezado'] != "") {
									        // Si 'encabezado' no está vacío, significa que hay contenido de encabezado
									        if ($previo['numeracion'] != "") {
									            // Si 'numeracion' no está vacío, significa que hay contenido de numeración
									            echo "<div class='seccion'><span>" . preg_replace('/\s/', ' ', $previo['numeracion']) . "</span>" . $previo['encabezado'] . "</div>";
									        } else {
									            // Si no hay 'numeracion', muestra el 'encabezado' sin numeración
									            echo "<div class='seccion'>" . preg_replace('/\s/', ' ', $previo['encabezado']) . "</div>";
									        }
									    }
									    // Muestra el contenido del arreglo 'cuerpo' dentro de un contenedor
									    echo "<div class='contenedor-interno'>";
									    foreach ($previo['cuerpo'] as $renglon) {
									        echo "<p>$renglon</p>"; // Genera párrafos con el contenido de cada elemento en 'cuerpo'
									    }
									    echo "</div>"; // Cierra el contenedor interno
									}
								// ============== INICIO BLOQUE SECCIONES
								//Se definen las secciones y preguntas visibles en 0
								$secciones_visibles = 0;
								$preguntas_visibles = 0;
								//Iterar a través de las secciones
								for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
									$puede_evaluar_seccion = false;
									//Obtiene el valor de la clave 'limitada' en el arreglo
									$limitante = $secciones[$contador_secciones][0]['limitada'];
									//Se evalua la limitante
									if ($limitante == "") {
										// Si no hay limitante, se puede evaluar la sección
										$puede_evaluar_seccion = true;
									} else {
										//Se obtiene la carrera
										$carrera = $_SESSION['zez_a_carrera'];
										//Realiza una búsqueda de la primera aparición del carácter de espacio en la cadena de texto almacenada en la variable "$limitante".
										$varios_limitantes = strpos($limitante, ' ');
										//Se evalua la variable "varios limitantes"
										if ($varios_limitantes === false) {
											// Si hay un solo limitante, verifica si está en la carrera
											$pos = strpos($carrera, $limitante);
											// Si se encuentra una coincidencia, establece $puede_evaluar_seccion en true
										    if ($pos !== false) { $puede_evaluar_seccion = true; }
										} else {
											// Si hay varios limitantes, verifica cada uno de ellos
											do {
											    // Extrae una parte de la cadena $limitante desde el inicio hasta la posición de $varios_limitantes
											    $compara = substr($limitante, 0, $varios_limitantes);
											    // Busca la posición de la subcadena $compara dentro de la cadena $carrera
											    $pos = strpos($carrera, $compara);
											    // Si se encuentra una coincidencia, establece $puede_evaluar_seccion en true
											    if ($pos !== false) { $puede_evaluar_seccion = true; }
											    // Recorta la cadena $limitante para eliminar la parte ya procesada y el espacio en blanco
											    $limitante = substr($limitante, ($varios_limitantes + 1));
											    // Busca la próxima posición de espacio en blanco en la cadena $limitante
											    $varios_limitantes = strpos($limitante, ' ');
											// Continúa ejecutando el bucle mientras se encuentren más espacios en blanco en $limitante
											} while ($varios_limitantes !== false);
											// Verifica el último limitante
											$pos = strpos($carrera, $limitante);
											// Si se encuentra una coincidencia, establece $puede_evaluar_seccion en true
										    if ($pos !== false) { $puede_evaluar_seccion = true; }
										}
									}
									//Verifica si se puede evaluar la seccion
									if ($puede_evaluar_seccion) {
										//Se incrementa el contador de secciones visibles
										$secciones_visibles++;
										if ($secciones[$contador_secciones][0]['texto']!="") {
											// Verifica si existe texto para mostrar en la sección actual.
											if($usar_contador_interno) {
												// Si se debe usar un contador interno (valor de $usar_contador_interno es verdadero), muestra la sección con el contador en letras.
												echo "<div class='seccion'><span>" . a_letras($secciones_visibles) . "</span>" . preg_replace('/\s/', ' ', $secciones[$contador_secciones][0]['texto']) . "</div>";
											} else {
												// Si no se debe usar un contador interno, muestra la sección sin el contador en letras.
												if ($secciones[$contador_secciones][0]['numeracion']!="") {
													// Verifica si hay numeración definida para la sección.
													echo "<div class='seccion'><span>" . $secciones[$contador_secciones][0]['numeracion'] . "</span>" . preg_replace('/\s/', ' ', $secciones[$contador_secciones][0]['texto']) . "</div>";
												} else {
													// Si no hay numeración, muestra la sección sin numeración.
													echo "<div class='seccion'>" . preg_replace('/\s/', ' ', $secciones[$contador_secciones][0]['texto']) . "</div>";
												}
											}
										}
										echo "<div class='contenedor-interno'>";
										//(COMENTADO) echo "<table class='table table-bordered'>";
										// ============== INICIO BLOQUE SECCIONES INTERNAS
										for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
											$puede_evaluar_subseccion = false;
											$limitante = $secciones[$contador_secciones][$contador_subsecciones]['limitada'];
											if ($limitante == "") {
												// Si la variable $limitante está vacía, significa que no hay restricciones para evaluar la subsección.
												$puede_evaluar_subseccion = true;
											} else {
												// Si $limitante no está vacía, se verifica si se aplican restricciones.
												$carrera = $_SESSION['zez_a_carrera'];
												// Se obtiene el valor de la carrera desde la sesión actual.
												$varios_limitantes = strpos($limitante, ' ');
												// Se busca la primera aparición del espacio en la cadena $limitante.
												if ($varios_limitantes === false) {
													// Si no se encuentra ningún espacio en $limitante, se trata de un único limitante.
													$pos = strpos($carrera, $limitante);
													// Se busca la posición de $limitante dentro de la cadena de la carrera.
													if ($pos !== false) {
														// Si se encuentra $limitante en la carrera, se permite evaluar la subsección.
														$puede_evaluar_subseccion = true;
													}
												} else {
													// Si hay varios limitantes separados por espacios en $limitante, se procesan uno por uno.
													do {
														$compara = substr($limitante, 0, $varios_limitantes);
														// Se obtiene el primer limitante.
														$pos = strpos($carrera, $compara);
														// Se busca la posición de $compara en la carrera.
														if ($pos !== false) {
															// Si se encuentra $compara en la carrera, se permite evaluar la subsección.
															$puede_evaluar_subseccion = true;
														}
														$limitante = substr($limitante, ($varios_limitantes + 1));
														// Se elimina el primer limitante de $limitante.
														$varios_limitantes = strpos($limitante, ' ');
														// Se busca el siguiente espacio en $limitante.
													} while ($varios_limitantes !== false);
													// Se repite el proceso hasta procesar todos los limitantes o hasta que no se encuentre más espacio en $limitante.
													$pos = strpos($carrera, $limitante);
													// Se verifica el último limitante.
													if ($pos !== false) {
														// Si se encuentra el último limitante en la carrera, se permite evaluar la subsección.
														$puede_evaluar_subseccion = true;
													}
												}
											}
											
											if ($puede_evaluar_subseccion) {
												// ============== INICIO BLOQUE ITEMS
												for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
													$preguntas_visibles++;
													//(COMENTADO) echo "<tr>";
													if ($secciones[$contador_secciones][$contador_subsecciones]['cero'] == "") {
														// Si la clave 'cero' está vacía, se establece el inicio del contador de respuestas en 1.
														// El incremento del contador se establece en 0.
														$inicio_contador_respuestas = 1;
														$incremento_respuestas = 0;
													} else {
														// Si la clave 'cero' no está vacía, se establece el inicio del contador de respuestas en 0.
														// El incremento del contador se establece en 1.
														$inicio_contador_respuestas = 0;
														$incremento_respuestas = 1;
													}													
													if (($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']] + $incremento_respuestas) > $valores_por_renglon) {
														// Si la suma del tamaño del conjunto de valores y el incremento de respuestas es mayor que la cantidad de valores por renglón
														if ($secciones[$contador_secciones][$contador_subsecciones]['comentario']) {
															// Si hay un comentario en esta sección
															// Se calcula el rowspan1 como la cantidad de filas necesarias para acomodar todas las respuestas y comentarios
															$rowspan1 = " rowspan='" . (ceil(($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']] + $incremento_respuestas) / $valores_por_renglon) + 1) . "'";
															// El rowspan2 será una fila menos que rowspan1
															$rowspan2 = " rowspan='" . ceil(($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']] + $incremento_respuestas) / $valores_por_renglon) . "'";
														} else {
															// Si no hay comentario en esta sección
															// Ambos rowspan serán iguales y se calculan como la cantidad de filas necesarias para acomodar todas las respuestas
															$rowspan1 = " rowspan='" . ceil(($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']] + $incremento_respuestas) / $valores_por_renglon) . "'";
															$rowspan2 = $rowspan1;
														}
													} else {
														// Si la suma del tamaño del conjunto de valores y el incremento de respuestas no supera la cantidad de valores por renglón
														if ($secciones[$contador_secciones][$contador_subsecciones]['comentario']) {
															// Si hay comentario en esta sección
															// rowspan1 será "2" (2 filas)
															$rowspan1 = " rowspan='2'";
															// rowspan2 estará vacío
															$rowspan2 = "";
														} else {
															// Si no hay comentario en esta sección
															// Ambos rowspan serán vacíos
															$rowspan1 = "";
															$rowspan2 = $rowspan1;
														}
													}
													//(COMENTADO) echo "<td$rowspan1 class='gris' style='font-weight:bold;width:5%;text-align:center;'>";
													// Se genera una celda de tabla (<td>) con el atributo rowspan1 y algunos estilos CSS
													if ($usar_contador_interno) {
														echo $preguntas_visibles; // Se muestra el valor de $preguntas_visibles
													} else {
														echo "<div class='seccion'><span>$contador_items</span></div>"; // Se muestra el valor de $contador_items
													}
													//(COMENTADO) echo "</td>";
													if (($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']] + $incremento_respuestas) >= $valores_por_renglon) {
														// Si la suma del tamaño del conjunto de valores y el incremento de respuestas supera o es igual a la cantidad de valores por renglón
														$ancho_multiplicador = 1;
														$colspan = ""; // No se especifica el atributo colspan en este caso
													} else {
														// Si no se cumple la condición anterior
														if (floor($valores_por_renglon / ($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']] + $incremento_respuestas)) > 1) {
															// Si el resultado de la división redondeada es mayor que 1
															$ancho_multiplicador = floor($valores_por_renglon / ($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']] + $incremento_respuestas));
															$colspan = " colspan='" . $ancho_multiplicador . "'"; // Se especifica el atributo colspan con el valor calculado
														} else {
															$ancho_multiplicador = 1;
															$colspan = ""; // No se especifica el atributo colspan en este caso
														}
													}

													// Verifica si la sección es de tipo "CERRADA"
													if ($secciones[$contador_secciones][$contador_subsecciones]['tipo'] == "CERRADA") {

														echo "<strong>".preg_replace('/\s/', ' ', $texto_reactivo[$contador_items])."</strong><br>";

														// Bucle para generar las opciones de respuesta
														for ($contador_respuestas = $inicio_contador_respuestas; $contador_respuestas <= $tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]; $contador_respuestas++) {
															// Cálculo del ancho de la celda de respuesta en función del número de respuestas por renglón
															if (($contador_respuestas + $incremento_respuestas) < $valores_por_renglon) {
																$ancho = round((50 / $valores_por_renglon), 4) * $ancho_multiplicador;
															} else {
																$ancho = 50 - (round((50 / $valores_por_renglon), 4) * ($valores_por_renglon - 1));
															}

															// Si es necesario, se cierra la fila actual de la tabla y se abre una nueva fila
															if (($contador_respuestas > 1) && ((($contador_respuestas - 1 + $incremento_respuestas) % $valores_por_renglon) == 0)) { }

															echo "
																<div class='form-check-inline'>
																	<div class='custom-control custom-radio'>
																		<input type='radio' class='custom-control-input' id='" . $numero_pregunta[$contador_items] . "o" . $contador_respuestas . "' name='" . $numero_pregunta[$contador_items] . "' value=" . $contador_respuestas;
															
																			// Se verifica si esta opción de respuesta estaba marcada en el formulario anterior
																			if (isset($_POST[$numero_pregunta[$contador_items]])) {
																				if ($_POST[$numero_pregunta[$contador_items]] == $contador_respuestas) { echo " checked='yes'"; }
																			}

																			echo " 
																		/>
																		<label class='custom-control-label' for='" . $numero_pregunta[$contador_items] . "o" . $contador_respuestas . "'>" . $set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']][$contador_respuestas]['texto'] . "</label>
																	</div>
																</div>
															";
														}
													} elseif ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="MULTIPLE") {

														echo "<strong>".preg_replace('/\s/', ' ', $texto_reactivo[$contador_items])."</strong><br>";

														for ($contador_respuestas=$inicio_contador_respuestas; $contador_respuestas<=$tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]; $contador_respuestas++) {
															if (($contador_respuestas+$incremento_respuestas)<$valores_por_renglon) {
																$ancho = round((50/$valores_por_renglon),4) * $ancho_multiplicador;
															} else {
																$ancho = 50 - (round((50/$valores_por_renglon),4) * ($valores_por_renglon - 1));
															}
															if (($contador_respuestas>1) AND ((($contador_respuestas-1+$incremento_respuestas) % $valores_por_renglon) == 0)) { }

															echo "
																<div class='form-check-inline'>
																	<div class='custom-control custom-checkbox mb-3'>
																		<input type='checkbox' class='custom-control-input' name='" . $numero_pregunta[$contador_items] . "_" . $contador_respuestas . "' id='". $numero_pregunta[$contador_items] . "o" . $contador_respuestas . "' value='" . $set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']][$contador_respuestas]['texto'] . "'";
																			if (isset($_POST[$numero_pregunta[$contador_items]."_".$contador_respuestas])) { echo " checked='cheked'"; }echo " 
																		/>
																		<label class='custom-control-label' for='". $numero_pregunta[$contador_items] . "o" . $contador_respuestas . "'>" . $set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']][$contador_respuestas]['texto'] . "</label>
															    	</div>
																</div>
															";

														}
													} else {

														echo "<strong>".preg_replace('/\s/', ' ', $texto_reactivo[$contador_items])."</strong><br>";
														if (isset($_POST['regresada'])) {
															if (strpos($_POST['regresada'], "Larga") !== false) {
																echo "(actualmente la respuesta tiene " . mb_strlen(pone_comillas($respuestas[$contador_items]), 'UTF-8') . " caracteres y el máximo permitido es de $maximo_caracteres)";
															} else {
																echo "(máximo $maximo_caracteres caracteres)";
															}
														} else {
															echo "(máximo $maximo_caracteres caracteres)";
														}
														echo "
															<textarea name='" . $numero_pregunta[$contador_items] . "' class='form-control'>";
																if (isset($_POST['regresada'])) { echo pone_comillas($respuestas[$contador_items]); } echo "</textarea>
														";
													}


													if (((($tamano_set_valores[$secciones[$contador_secciones][$contador_subsecciones]['set_valores']]+$incremento_respuestas) * $ancho_multiplicador) % $valores_por_renglon) > 0) {
													}
													if ($secciones[$contador_secciones][$contador_subsecciones]['comentario']) {

														echo "<strong>".preg_replace('/\s/', ' ', $texto_reactivo[$contador_items])."</strong><br>";

														echo "Comentario sobre esta pregunta...<br />";
														if (isset($_POST['regresada'])) {
															if (strpos($_POST['regresada'], "Larga") !== false) {
																echo "(actualmente el comentario tiene " . mb_strlen(pone_comillas($comentarios[$contador_items]), 'UTF-8') . " caracteres y el máximo permitido es de $maximo_caracteres)";
															} else {
																echo "(máximo $maximo_caracteres caracteres)";
															}
														} else {
															echo "(máximo $maximo_caracteres caracteres)";
														}
														echo "
															<textarea name='" . $numero_comentario[$contador_items] . "' class='form-control'>";
																if (isset($_POST['regresada'])) { echo pone_comillas($comentarios[$contador_items]); } echo "</textarea>
														";
													}
												}
												// ============== TERMINA BLOQUE ITEMS
											} else {
												// ============== INICIO BLOQUE ITEMS
												for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
													if ($secciones[$contador_secciones][$contador_subsecciones]['tipo']=="CERRADA") {
														echo "<input type=hidden name='" . $numero_pregunta[$contador_items] . "' value='987' />";
													} else {
														echo "<input type=hidden name='" . $numero_pregunta[$contador_items] . "' value='' />";
													}
												}
												// ============== TERMINA BLOQUE ITEMS
											}
										}
										// ============== TERMINA BLOQUE SECCIONES INTERNAS
										//(COMENTADO) echo "</table>";
										echo "</div>";
									} else {
										// ============== INICIA BLOQUE SECCIONES INTERNAS
										for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
											// ============== INICIO BLOQUE ITEMS
											for ($contador_items=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_items<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_items++) {
												if ($secciones[$contador_secciones]['cerrada']) {
													echo "<input type=hidden name='" . $numero_pregunta[$contador_items] . "' value='987' />";
												} else {
													echo "<input type=hidden name='" . $numero_pregunta[$contador_items] . "' value='' />";
												}
											}
											// ============== TERMINA BLOQUE ITEMS
										}
										// ============== TERMINA BLOQUE SECCIONES INTERNAS
									}
								}
								// ============== TERMINA BLOQUE SECCIONES
							?>
							<p align="center"><input type=submit class="btn btn-success btn-block" value="ENVIAR LA EVALUACIÓN" style="display:block;margin:auto;" /></p>
						</form>
					</div>
					<?php mysqli_close($base_de_datos); ?>
				</div>
			</div>
		</body>
	</html>