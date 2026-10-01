<?php
	//Inicio la sesion
	session_start();
	//Almaceno variables de sesion en variables estaticas
	$zez_a_nivel_acceso=$_SESSION['zez_a_nivel_acceso'];
	$zez_a_nombre=$_SESSION['zez_a_nombre'];
	$zez_a_nivel=$_SESSION['zez_a_nivel'];
	//Incluyo el archivo de configuracion 
	require_once 'lib/config.php';
	if (!isset($_SESSION['zez_a_nombre'])) {
		//Evaluo si la variable de sesion de nombre esta declarada
		header('Location: salir.php');
		//Si la variable de sesion del nombre no esta actiuva, se sale de la aplicacion
	} elseif (strpos(" INGLES ACADEMICO DIRECTOR COORDINADOR REVISOR VICERRECTOR FIDCO ADMINISTRADOR SUPERUSUARIO",$_SESSION['zez_a_nivel_acceso'])== FALSE) {
		//Evaluo si en la varible de sesion para el nivel de acceso contiene algunod elos niveles mencionados
		if (date("Y-m-d") < $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) {
			//Evaluo si la fecha actual es menor a la fecha asignada para el nivel educativo y el nivel de acceso
			header('Location: no_iniciada.php');
			//Mando a la pagina de evaluacion no iniicada
		} elseif (date("Y-m-d") > $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin']) {
			//Evaluo si la fecha actual es mayor a la fecha asignada para el nivel educativo y el nivel de acceso
			header('Location: cerrada.php');
			//Mando a la pagina de evaluacion cerrada
		} else {
			//Si la fecha esta denro del rango de evaluacion para el nivel educativo y el nivel de acceso
			header('Location: mis_evaluaciones.php');
			//Se manda a la pagina de evaluaciones
		}
	}
	//Defino la variable de parametros validos en falso
	$parametros_validos = false;
	//Se Eliminan las variables y se libera la memoria que ocupaban
	unset($parametros);
	//defino el string de permisos en vacio
	$string_parametros = "";
	if (isset($_POST['parametros'])) {
		//Evaluo si se enviaron los datos de los parametros
		$string_parametros = desencriptar($_POST['parametros']);
		//Se desencriptan y almacenan los parametros enviados
		if ((substr($string_parametros,0,2)=="%%") AND (substr($string_parametros,(strlen($string_parametros)-2),2)=="%%")) {
			//Evaluo si la cadena $string_parametros comienza y termina con "%%"
			$string_parametros = substr($string_parametros,2,strlen($string_parametros)-4);
			//Se elimina los dos primeros caracteres y los dos últimos caracteres y se almacena en la variable de parametros
			$parametros_validos = true;
			//Se define la variable de parametros validos como verdadera
			if(strpos($string_parametros,"¬")===FALSE) {
				//Se evalua si la raviable contiene el caracter ¬
				$parametros[] = $string_parametros;
				//Se asigna el valor de la variable de parametros al arreglo de parametros
			} else {
				//Si no existe el caracter en la variable de parametros
				do {
					// Busca la posición del primer "¬" en la cadena
					$posicion_delimitador = strpos($string_parametros, "¬");
					// Extrae la subcadena desde el inicio hasta el primer "¬" y la agrega a $parametros
					$parametros[] = substr($string_parametros, 0, $posicion_delimitador);
					// Actualiza $string_parametros para que comience después del primer "¬" y dos caracteres adicionales
					$string_parametros = substr($string_parametros, $posicion_delimitador + 2);
				} while (!($posicion_delimitador === FALSE));
				//Se realiza el ciclo mientras el valor del delimitador no sea falso
				$parametros[] = $string_parametros;
				//Se asigna el valor de la variable de parametros al arreglo de parametros
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
		<!-- Boton para volver a los reportes -->
			<div>
				<a class="btn text-light" onclick="redirigir('mis_reportes.php');">
					<strong>Mis Reportes</strong>
				</a>
			</div>
		<!-- Marcador del reporte actual -->
			<div>
				<a class="btn btn-warning text-dark">
					<strong>Reporte Actual</strong>
				</a>
			</div>
		<!-- Boton para imprimir la pagina o cerrar la sesion -->
			<div class="ml-auto">
				<a class="btn text-light" onclick="imprime_pagina();">
					<strong>Imprimir</strong>
				</a>
				<a class="btn text-light" onclick="redirigir('salir.php');">
					<strong>Salir</strong>
				</a>
			</div>
		<!-- -->
	</div>
	<body>
		<div class="container">
			<div class="card">
				<div class="card-header">
					<img src="lib/img/blanco.png" class="img-form-left" />
					<h1>UNIVERSIDAD MARISTA DE GUADALAJARA</h1>
					<h3>PROCESO DE EVALUACIÓN DOCENTE</h3>
					<h4>REPORTE A2</h4>
				</div>
				<div class="card-body">
					<?php
						//obtengo la cantidad de alumnos registrados en el grupo
							$orden_sql = "SELECT count(id) as alumnos FROM grupos WHERE grupo='$parametros[2]'";
							$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
							if ($resultado_busqueda) {
								if (mysqli_num_rows($resultado_busqueda) > 0) {
									while($registro = mysqli_fetch_array($resultado_busqueda)) {
										$total_alumnos_grupo=$registro['alumnos'];
									}
								}
								mysqli_free_result($resultado_busqueda);
							}
						//obtengo la cantidad de alumnos que realizaron la evaluacion
							$orden_sql = "SELECT count(id) as alumnos FROM lic_profesores_por_alumnos WHERE grupo='$parametros[2]' and materia='$parametros[3]' and aplicacion=1";
							$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
							if ($resultado_busqueda) {
								if (mysqli_num_rows($resultado_busqueda) > 0) {
									while($registro = mysqli_fetch_array($resultado_busqueda)) {
										$total_alumnos_evaluaron=$registro['alumnos'];
									}
								}
								mysqli_free_result($resultado_busqueda);
							}
						//Calculo el porcentaje de efectividad
							$porcentaje_efectividad=(round($total_alumnos_evaluaron/$total_alumnos_grupo, 4)*100);
						//Calculo la puntuacion maxima por reactivo yla maxima general
							$maxima=5*$total_alumnos_evaluaron;
							$maxima_general=$maxima*6;

							$maxima_autoevaluacion=5;
							$maxima_general_autoevaluacion=$maxima_autoevaluacion*5;

							for ($i=1; $i < 7 ; $i++) {
								$reactivo='r0'.$i;
								for ($j=0; $j < 6 ; $j++) { 
									$valor_reactivo[$reactivo][$j]['sumatoria_valor']=Null;
									$valor_reactivo[$reactivo][$j]['cuenta_valor']=Null;
								}
							}

					?>
					<!-- Primera parte (informacion inicial) -->
						<div class="contenedor-interno">
							<!-- Bloque de la tabla principal que miestra los datos estadisticos -->
								<table class="table table-bordered table-striped table-sm" style="width:auto;">
									<tr>
										<th class="fondo-marista" style="text-align:right;">Ciclo escolar</th>
										<td style="text-align:left;"><?php echo $ciclo[$parametros[0]]; ?></td>
										<th class="fondo-marista" style="text-align:right;">Periodo de evaluacion</th>
										<td style="text-align:left;">A</td>
									</tr>
									<tr>
										<th class="fondo-marista" style="text-align:right;">Fecha del reporte</th>
										<td colspan="3" style="text-align:left;"><?php echo transforma_fecha(ahora(1)); ?></td>
									</tr>
									<tr>
										<th class="fondo-marista" style="text-align:right;">Nombre del (la) docente</th>
										<td colspan="3" style="text-align:left;"><?php echo $parametros[1]; ?></td>
									</tr>
									<tr>
										<th class="fondo-marista" style="text-align:right;">Materia</th>
										<td colspan="3" style="text-align:left;"><?php echo $parametros[3]; ?></td>
									</tr>
									<tr>
										<th class="fondo-marista" style="text-align:right;">Carrera</th>
										<td colspan="3" style="text-align:left;"><?php echo $parametros[5]; ?></td>
									</tr>
									<tr>
										<th class="fondo-marista" style="text-align:right;">Poblacion total del grupo</th>
										<td style="text-align:left;"><?php echo $total_alumnos_grupo; ?></td>
										<th class="fondo-marista" style="text-align:right;">Alumnos que evaluaron</th>
										<td style="text-align:left;"><?php echo $total_alumnos_evaluaron; ?></td>
									</tr>
									<tr>
										<th class="fondo-marista" style="text-align:right;">Resultado % de efectividad</th>
										<td colspan="3" style="text-align:left;"><strong><div id="porcentaje_final_alumnos"></div></strong></td>
									</tr>
									<tr>
										<th class="fondo-marista" style="text-align:right;">Resultado % de autoevaluacion docente</th>
										<td colspan="3" style="text-align:left;"><strong><div id="porcentaje_final_autoevaluacion"></div></strong></td>
									</tr>
								</table>
							<!-- -->
						</div>
						<br>
					<!-- Evaluacion de alumnos a docenes -->
						<div class="seccion"><span>1</span>Encuesta de percepción estudiantil</div>
						<div class="contenedor-interno">
							<table class="table table-bordered table-striped table-sm" id="resultados_profesores">
								<tr>
									<th class="fondo-marista">#</th>
									<th class="fondo-marista">Reactivo</th>
									<th class="fondo-marista">1</th>
									<th class="fondo-marista">2</th>
									<th class="fondo-marista">3</th>
									<th class="fondo-marista">4</th>
									<th class="fondo-marista">5</th>
									<th class="fondo-marista">% de efectividad</th>
								</tr>
								<?php
									//Incluyo el archivo que tiene los reactivos
									include_once 'lic_profesores_por_alumnos.php';
									$valor_reactivo=null;
									//Creeo un ciclo for para recorrer la cantidad de reactivos y obtener sus respuestas
									for ($i=1; $i < $total_reactivos ; $i++) {
										echo "
											<tr>
												<td>$i</td>
												<td>$texto_reactivo[$i]</td>";
												$reactivo="r0".$i;
												//Defino un array en 0 y una variable de sumatoria
												for ($j=1; $j <= 5 ; $j++) {
											        $valor_reactivo[$reactivo][$j]['sumatoria_valor'] = 0;
											        $valor_reactivo[$reactivo][$j]['cuenta_valor'] = 0;
											    }
											    $sumatoria=0;
												//Verifico si el nivel de acceso que se tiene es de coordinador
												$orden_sql = "SELECT $reactivo FROM lic_profesores_por_alumnos WHERE grupo='$parametros[2]' and materia='$parametros[3]' and aplicacion=1";
												// Ejecuta la consulta SQL
												$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
												if ($resultado_busqueda) {
													if (mysqli_num_rows($resultado_busqueda) > 0) {
														// Si hubo resultado obtiene los datos de la sesión y activa la bandera
														while($registro = mysqli_fetch_array($resultado_busqueda)) {
															//Defino el valor del reactivo como un entero
																$VDR=intval($registro[$reactivo]);
															//Realizo la sumatoria en el array correspondiente
																$valor_reactivo[$reactivo][$VDR]['sumatoria_valor']+=$VDR;
															//Aumento el contador en la posicion del array correspondiente
																$valor_reactivo[$reactivo][$VDR]['cuenta_valor']++;
															//
														}
													}
													// Libera el conjunto de resultados
													mysqli_free_result($resultado_busqueda);
												}
												//Creeo un ciclo para revisar todos los datos
												for ($j=1; $j <6 ; $j++) {
													if ($valor_reactivo[$reactivo][$j]['cuenta_valor']<1) {
														echo "<td></td>";
													}else{
														echo "<td>".$valor_reactivo[$reactivo][$j]['cuenta_valor']."</td>";
													}
													$sumatoria+=$valor_reactivo[$reactivo][$j]['sumatoria_valor'];
												}
												echo "<td>".(round($sumatoria/$maxima, 4)*100)." %</td>

											<tr>
										";
										$sumatoria=0;
									}
									$suma_total[]=null;
									$sumatoria_general=null;
									//Se comienza a realizar el ciclo for final que va a recorrer todos los reactivos
									//Defino array en 0
									for ($j=1; $j < 6 ; $j++) { 
										for ($i=1; $i < $total_reactivos ; $i++) {
											$suma_total[$j]=0;
											$sumatoria_general=0;
										}
									}
									//
									for ($j=1; $j < 6 ; $j++) { 
										for ($i=1; $i < $total_reactivos ; $i++) {
											$reactivo='r0'.$i;
											$suma_total[$j]+=$valor_reactivo[$reactivo][$j]['cuenta_valor'];
											$sumatoria_general+=$valor_reactivo[$reactivo][$j]['sumatoria_valor'];
										}
									}
									echo "
										<tr>
											<th class='bg-secondary text-light' colspan='2'>Totales</th>";
											for ($j=1; $j < 6 ; $j++) {
												//$sumatoria_general+=$suma_total[$j];
												echo "<th class='bg-secondary text-light'>".$suma_total[$j]."</th>";
											}
											echo "
											<th class='bg-secondary text-light'> </th>
											<script>$('#porcentaje_final_alumnos').html('".(round($sumatoria_general/$maxima_general, 4)*100)." %');</script>
										</tr>
									";
								?>
								<tr>
									<th class="fondo-marista" colspan="8">Comentarios</th>
								</tr>
								<?php
									
									$orden_sql = "SELECT r07 FROM lic_profesores_por_alumnos WHERE grupo='$parametros[2]' and materia='$parametros[3]' and r07<>'' and aplicacion=1";
									// Ejecuta la consulta SQL
									$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
									if ($resultado_busqueda) {
										if (mysqli_num_rows($resultado_busqueda) > 0) {
											// Si hubo resultado obtiene los datos de la sesión y activa la bandera
											while($registro = mysqli_fetch_array($resultado_busqueda)) {
												echo "<tr><td colspan='8'>".$registro['r07']."</td></tr>";
											}
										}
										// Libera el conjunto de resultados
										mysqli_free_result($resultado_busqueda);
									}
								?>
							</table>
						</div>
						<br>
					<!-- Evaluacion de alumnos a docenes -->
						<div class="seccion"><span>2</span>Autoevaluación docente</div>
						<div class="contenedor-interno">
							<table class="table table-bordered table-striped table-sm" id="resultados_profesores">
								<tr>
									<th class="fondo-marista">#</th>
									<th class="fondo-marista">Reactivo</th>
									<th class="fondo-marista">1</th>
									<th class="fondo-marista">2</th>
									<th class="fondo-marista">3</th>
									<th class="fondo-marista">4</th>
									<th class="fondo-marista">5</th>
									<th class="fondo-marista">% de efectividad</th>
								</tr>
								<?php
									//Incluyo el archivo que tiene los reactivos
									include_once 'lic_profesores_por_profesores.php';
									$valor_reactivo[]=null;
									unset($valor_reactivo);
									unset($sumatoria);
									unset($suma_total);
									//Creeo un ciclo for para recorrer la cantidad de reactivos y obtener sus respuestas
									for ($i=1; $i < $total_reactivos ; $i++) {
										echo "
											<tr>
												<td>$i</td>
												<td>$texto_reactivo[$i]</td>";
												$reactivo="r0".$i;
												//Defino un array en 0 y una variable de sumatoria
												for ($j=1; $j <= 5 ; $j++) {
											        $valor_reactivo[$reactivo][$j]['sumatoria_valor'] = 0;
											        $valor_reactivo[$reactivo][$j]['cuenta_valor'] = 0;
											    }
											    $sumatoria=0;

												//Verifico si el nivel de acceso que se tiene es de coordinador
												$orden_sql = "SELECT $reactivo FROM lic_profesores_por_profesores WHERE grupo='$parametros[2]' and materia='$parametros[3]' and aplicacion=1";
												// Ejecuta la consulta SQL
												$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
												if ($resultado_busqueda) {
													if (mysqli_num_rows($resultado_busqueda) > 0) {
														// Si hubo resultado obtiene los datos de la sesión y activa la bandera
														while($registro = mysqli_fetch_array($resultado_busqueda)) {
															//Defino el valor del reactivo como un entero
																$VDR=intval($registro[$reactivo]);
															//Realizo la sumatoria en el array correspondiente
																$valor_reactivo[$reactivo][$VDR]['sumatoria_valor']+=$VDR;
															//Aumento el contador en la posicion del array correspondiente
																$valor_reactivo[$reactivo][$VDR]['cuenta_valor']++;
															//
														}
													}
													// Libera el conjunto de resultados
													mysqli_free_result($resultado_busqueda);
												}
												//Creeo un ciclo para revisar todos los datos
												for ($j=1; $j <6 ; $j++) {
													if ($valor_reactivo[$reactivo][$j]['cuenta_valor']<1) {
														echo "<td></td>";
													}else{
														echo "<td>".$valor_reactivo[$reactivo][$j]['cuenta_valor']."</td>";
													}
													$sumatoria=$sumatoria+$valor_reactivo[$reactivo][$j]['sumatoria_valor'];
												}
												echo "<td>".(round($sumatoria/$maxima_autoevaluacion, 4)*100)." %</td>
											<tr>
										";
										$sumatoria=0;
									}
									$suma_total[]=null;
									$sumatoria_general=null;
									//Se comienza a realizar el ciclo for final que va a recorrer todos los reactivos
									//Defino array en 0
									for ($j=1; $j < 6 ; $j++) { 
										for ($i=1; $i < $total_reactivos ; $i++) {
											$suma_total[$j]=0;
											$sumatoria_general=0;
										}
									}
									//
									for ($j=1; $j < 6 ; $j++) { 
										for ($i=1; $i < $total_reactivos ; $i++) {
											$reactivo='r0'.$i;
											$suma_total[$j]+=$valor_reactivo[$reactivo][$j]['cuenta_valor'];
											$sumatoria_general+=$valor_reactivo[$reactivo][$j]['sumatoria_valor'];
										}
									}
									echo "
										<tr>
											<th class='bg-secondary text-light' colspan='2'>Totales</th>";
											for ($j=1; $j < 6 ; $j++) {
												//$sumatoria_general+=$suma_total[$j];
												echo "<th class='bg-secondary text-light'>".$suma_total[$j]."</th>";
											}
											echo "
											<th class='bg-secondary text-light'> </th>
											<script>$('#porcentaje_final_autoevaluacion').html('".(round($sumatoria_general/$maxima_general_autoevaluacion, 4)*100)." %');</script>
										</tr>
									";
								?>
								<tr>
									<th class="fondo-marista" colspan="8">Comentarios</th>
								</tr>
								<?php
									
									$orden_sql = "SELECT r06 FROM lic_profesores_por_profesores WHERE grupo='$parametros[2]' and materia='$parametros[3]' and r06<>'' and aplicacion=1";
									// Ejecuta la consulta SQL
									$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
									if ($resultado_busqueda) {
										if (mysqli_num_rows($resultado_busqueda) > 0) {
											// Si hubo resultado obtiene los datos de la sesión y activa la bandera
											while($registro = mysqli_fetch_array($resultado_busqueda)) {
												echo "<tr><td colspan='8'>".$registro['r06']."</td></tr>";
											}
										}
										// Libera el conjunto de resultados
										mysqli_free_result($resultado_busqueda);
									}
								?>
							</table>
						</div>
					<!-- -->
				</div>
			</div>
		</div>
	</body>
	<?php mysqli_close($base_de_datos); ?>
	<script type="text/javascript">
		function imprime_pagina() { window.print(); }
	</script>
</html>