<?php
	session_start();
	require_once 'lib/config.php';
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
		header('Location: exit.php');
		exit;
	}
	if(!isset($parametros[8])) {
		header('Location: exit.php');
		exit;
	}
	if ($parametros[1]!="NULO") $_POST['coordinador_anterior'] = $parametros[1];
	if ($parametros[2]!="NULO") $_POST['coordinador'] = $parametros[2];
	if ($parametros[3]!="NULO") $_POST['carrera'] = $parametros[3];
	if ($parametros[4]!="NULO") $_POST['datos_jefes'] = $parametros[4];
	if ($parametros[5]!="NULO") $_POST['datos_profesores'] = $parametros[5];
	if ($parametros[6]!="NULO") $_POST['datos_alumnos'] = $parametros[6];
	if ($parametros[7]!="NULO") $_POST['datos_coordinadores'] = $parametros[7];
	if ($parametros[8]!="NULO") $_POST['ver_comentarios'] = $parametros[8];
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
	if (!isset($_POST['coordinador_anterior'])) {
		if (isset($_POST['carrera'])) {
			$post_carrera = $_POST['carrera'];
		} else {
			$post_carrera = 0;
		}
	} else {
		if($_POST['coordinador'] != $_POST['coordinador_anterior']) {
			$post_carrera = 0;
		} else {
			if (isset($_POST['carrera'])) {
				$post_carrera = $_POST['carrera'];
			} else {
				$post_carrera = 0;
			}
		}
	}
	if ($post_carrera!=0) {
		$contador_de_coordinadores = 0;
		$contador_de_carreras = 0;
		if (("COORDINADOR"==$_SESSION['zez_a_nivel_acceso']) or ("FIDCO"==$_SESSION['zez_a_nivel_acceso'])) {
			$orden_sql = "SELECT DISTINCT `coordinador` ";
			$orden_sql .= "FROM `departamentos` D ";
			$orden_sql .= "RIGHT JOIN `carreras` C ";
			$orden_sql .= "ON D.`departamento` = C.`departamento` ";
			$orden_sql .= "WHERE D.`jefe`='" . $_SESSION['zez_a_nombre'] . "' ";
			$orden_sql .= "AND D.`jefe`<>C.`coordinador` ";
			$orden_sql .= "ORDER BY `coordinador`";
		} else {
			$orden_sql = "SELECT DISTINCT `coordinador` ";
			$orden_sql .= "FROM `carreras` ";
			$orden_sql .= "ORDER BY `coordinador`";
		}
		$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
		if ($resultado_busqueda) {
			if (mysqli_num_rows($resultado_busqueda) > 0) {
				unset($listado_de_coordinadores);
				while($registro = mysqli_fetch_array($resultado_busqueda)) {
					$contador_de_coordinadores++;
					$listado_de_coordinadores[$contador_de_coordinadores] = $registro['coordinador'];
				}
			}
			mysqli_free_result($resultado_busqueda);
		}
		// Genera la orden SQL para hacer la consulta a la tabla de la evaluación general de licenciaturas
		$orden_sql = "SELECT `carrera` ";
		$orden_sql .= "FROM `carreras` ";
		$orden_sql .= "WHERE `coordinador`='" . $listado_de_coordinadores[$_POST['coordinador']] . "' ";
		$orden_sql .= "ORDER BY `carrera`";
		$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
		if ($resultado_busqueda) {
			if (mysqli_num_rows($resultado_busqueda) > 0) {
				unset($listado_de_carreras);
				while($registro = mysqli_fetch_array($resultado_busqueda)) {
					$contador_de_carreras++;
					$listado_de_carreras[$contador_de_carreras] = $registro['carrera'];
				}
			}
			mysqli_free_result($resultado_busqueda);
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
						<?php echo $listado_de_coordinadores[$_POST['coordinador']]; ?>
					</h1>
					<span>CICLO <?php echo $listado_de_carreras[$_POST['carrera']] . " - " . $ciclo[$parametros[0]]; ?></span>
				</div>
				<div class="card-body">
					<div class="contenedor-interno">
						<?php
							if ('DEPORTE Y CULTURA' != $listado_de_carreras[$post_carrera]) {
								if ('MATERIAS INSTITUCIONALES' != $listado_de_carreras[$post_carrera]) {
									$total_bloques = 4;
									$nombre_tabla[1] = "jefes";
									$ponderacion[1] = 0.4;
									$inicio_reactivos[1] = 1;
									$fin_reactivos[1] = 20;
									$inicio_nas[1] = 1; // si no hay reactivos con NA poner un número mayor al número de preguntas
									$nombre_tabla[2] = "profesores";
									$ponderacion[2] = 0.25;
									$inicio_reactivos[2] = 1;
									$fin_reactivos[2] = 15;
									$inicio_nas[2] = 1; // si no hay reactivos con NA poner un número mayor al número de preguntas
									$nombre_tabla[3] = "alumnos";
									$ponderacion[3] = 0.25;
									$inicio_reactivos[3] = 1;
									$fin_reactivos[3] = 18;
									$inicio_nas[3] = 1; // si no hay reactivos con NA poner un número mayor al número de preguntas
									$nombre_tabla[4] = "coordinadores";
									$ponderacion[4] = 0.1;
									$inicio_reactivos[4] = 1;
									$fin_reactivos[4] = 31;
									$inicio_nas[4] = 1; // si no hay reactivos con NA poner un número mayor al número de preguntas
									unset($encabezado);
									$encabezado[1][1] = "Participó en cursos, foros, congresos o seminarios para actualizarse en su formación profesional y pedagógica.";
									$encabezado[1][2] = "Promovió la investigación entre los estudiantes de la(s) Carrera(s) a su cargo.";
									$encabezado[1][3] = "Promovió la revisión de los planes de estudio en las reuniones de trabajo de los colegios de profesores.";
									$encabezado[1][4] = "Planeó los procesos propios de la(s) licenciatura(s) a su cargo en las fechas marcadas por la institución.";
									$encabezado[1][5] = "Conformó el equipo docente de la(s) Carrera(s) a su cargo antes de iniciar el semestre, de acuerdo a los criterios establecidos por la institución.";
									$encabezado[1][6] = "Coordinó las actividades y eventos con el departamento administrativo.";
									$encabezado[1][7] = "Participó activamente en actividades de promoción de su(s) carrera(s) en eventos organizados por el departamento de Comunicación e Imagen Institucional.";
									$encabezado[1][8] = "Organizó eventos que mejoraron la formación profesional de los estudiantes y que posicionaron la carrera (congresos, seminarios, conferencias, etc.).";
									$encabezado[1][9] = "Vinculó la(s) Carrera(s) con los sectores empresarial, gubernamental, no gubernamental, educativo o religioso a través de convenios y acuerdos.";
									$encabezado[1][10] = "Fomentó la generación de proyectos solidarios a favor de los menos favorecidos.";
									$encabezado[1][11] = "Diseñó programas y actividades de Educación Continua para egresados y profesionales relacionados con la Carrera.";
									$encabezado[1][12] = "Promovió la vinculación con empleadores para retroalimentar los programas de estudio de la(s) Carrera(s).";
									$encabezado[1][13] = "Estuvo atento al proceso de aprendizaje, asistencia y resultados académicos de sus estudiantes.";
									$encabezado[1][14] = "Mantuvo una comunicación constante con los estudiantes, para mantenerlos informados oportunamente mediante el uso de varios medios (redes sociales, correo electrónico, información impresa, etc.).";
									$encabezado[1][15] = "Promovió actividades encaminadas al desarrollo de valores maristas en los estudiantes (amor al trabajo, espíritu de familia, relaciones cordiales, acompañamiento, etc.).";
									$encabezado[1][16] = "Proporcionó atención oportuna y respetuosa a sus profesores cuando fue necesaria o cuando se le requirió.";
									$encabezado[1][17] = "Convocó a reuniones de trabajo a los colegios de profesores de su(s) Carrera(s).";
									$encabezado[1][18] = "Dio seguimiento a los proyectos integradores de la(s) Carrera(s) a su cargo.";
									$encabezado[1][19] = "De manera general, la calificación que le otorgo con respecto al desempeño como Coordinador es...";
									$encabezado[2][1] = "Promovió la participación de los docentes de la(s) carrera(s) a su cargo en talleres y cursos de capacitación propuestos por la Universidad.";
									$encabezado[2][2] = "Supervisó el trabajo de los profesores de la(s) carrera(s) para asegurar la calidad en la formación académica de los estudiantes.";
									$encabezado[2][3] = "Dio información general sobre el proceso de internacionalización en la(s) carrera(s) a su cargo (intercambios, eventos informativos, conferencias, etc.).";
									$encabezado[2][4] = "Promovió proyectos integradores que implicaron la multidisciplinariedad e interdisciplinariedad.";
									$encabezado[2][5] = "Dio seguimiento a los proyectos integradores de la(s) Carrera(s) a su cargo.";
									$encabezado[2][6] = "Convocó a reuniones de trabajo a los colegios de profesores de su(s) Carrera(s).";
									$encabezado[2][7] = "Promovió la revisión de los planes de estudio en las reuniones de trabajo de los colegios de profesores.";
									$encabezado[2][8] = "Planeó los procesos propios de la(s) licenciatura(s) a su cargo en las fechas marcadas por la Institución.";
									$encabezado[2][9] = "Entregó en tiempo la calendarización académica del semestre.";
									$encabezado[2][10] = "Se realizaron las actividades programadas en el calendario académico, respetando las fechas dadas.";
									$encabezado[2][11] = "Nos informó de la vinculación de la(s) Carrera(s) con los sectores empresarial, gubernamental, no gubernamental, educativo o religioso a través de convenios y acuerdos, para el desarrollo de proyectos conjuntos.";
									$encabezado[2][12] = "Nos informó de la vinculación con empleadores para retroalimentar los programas de estudio de la(s) Carrera(s) a su cargo.";
									$encabezado[2][13] = "Recibí atención oportuna y respetuosa cuando fue necesario o cuando la requerí.";
									$encabezado[2][14] = "De manera general, la calificación que le otorgo con respecto a su desempeño como Coordinador es...";
									$encabezado[3][1] = "Promovió proyectos de investigación entre los estudiantes de la(s) Carrera(s) a su cargo.";
									$encabezado[3][2] = "Dio información general sobre el proceso de internacionalización en la(s) carrera(s) a su cargo (intercambios, eventos informativos, conferencias, etc.).";
									$encabezado[3][3] = "Promovió proyectos integradores al interior de tu carrera.";
									$encabezado[3][4] = "Promovió proyectos multidisciplinarios (se involucraron dos o más carreras).";
									$encabezado[3][5] = "Promovió la participación de los estudiantes en concursos, congresos y/o diplomados propios de la(s) carrera(s) a su cargo.";
									$encabezado[3][6] = "Dio seguimiento al desempeño académico y formación integral de los estudiantes.";
									$encabezado[3][7] = "Promovió la integración y convivencia entre los alumnos de la(s) carrera(s) a su cargo.";
									$encabezado[3][8] = "Acompañó a las diferentes generaciones en su trayecto universitario (Ingreso – Desarrollo – Egreso).";
									$encabezado[3][9] = "Organizó eventos que mejoraron la formación profesional de los estudiantes y que posicionaron la(s) Carrera(s) a su cargo (congresos, seminarios, conferencias, visitas, etc.).";
									$encabezado[3][10] = "Nos mantuvo informados, a los estudiantes, sobre los convenios y acuerdos realizados con los sectores empresarial, gubernamental, no gubernamental, educativo o religioso.";
									$encabezado[3][11] = "Estuvo atento a mi proceso de aprendizaje, asistencia y resultados académicos.";
									$encabezado[3][12] = "Mantuvo una comunicación constante conmigo, para mantenerme informado oportunamente mediante el uso de varios medios (redes sociales, correo electrónico, información impresa, etc.).";
									$encabezado[3][13] = "Promovió actividades encaminadas al desarrollo de valores maristas en los estudiantes (amor al trabajo, espíritu de familia, relaciones cordiales, acompañamiento, etc.).";
									$encabezado[3][14] = "Recibí atención y trato respetuoso cuando requerí el apoyo de mi Coordinador.";
									$encabezado[3][15] = "Respetó los horarios que tiene establecidos para atención a los alumnos.";
									$encabezado[3][16] = "Fomentó la generación de proyectos solidarios a favor de los menos favorecidos.";
									$encabezado[3][17] = "De manera general, la calificación que le otorgo con respecto a su desempeño como Coordinador es...";
									$encabezado[4][1] = "Participé en cursos, foros, congresos o seminarios para actualizarme en mi formación profesional y/o pedagógica.";
									$encabezado[4][2] = "Promoví la investigación entre los estudiantes en coordinación con el responsable del Desarrollo de Habilidades de Investigación en los estudiantes (Dr. Alfonso Ascencio Rubio).";
									$encabezado[4][3] = "Promoví el trabajo colegiado entre los docentes a mi cargo convocando a reuniones de trabajo en el semestre.";
									$encabezado[4][4] = "Promoví la participación de los docentes, en talleres y cursos de capacitación propuestos por la Universidad.";
									$encabezado[4][5] = "Supervisé el trabajo de los profesores para asegurar la calidad en la formación académica de los estudiantes.";
									$encabezado[4][6] = "Di información general sobre el proceso de internacionalización en la(s) carrera(s) a mi cargo (intercambios, eventos informativos, conferencias, etc.)";
									$encabezado[4][7] = "Promoví proyectos integradores al interior de mi carrera.";
									$encabezado[4][8] = "Promoví proyectos integradores multidisciplinarios que implicaron a dos o más carreras.";
									$encabezado[4][9] = "Promoví la revisión de los planes de estudio en las reuniones de trabajo de los Colegios de Profesores.";
									$encabezado[4][10] = "Entregué en tiempo la calendarización académica del semestre.";
									$encabezado[4][11] = "Las actividades programadas en el calendario académico, correspondientes a mi área, se realizaron respetando las fechas dadas.";
									$encabezado[4][12] = "Conformé el equipo docente de la(s) carrera(s) a mi cargo antes de iniciar el semestre, de acuerdo a los criterios establecidos por la institución.";
									$encabezado[4][13] = "Coordiné las actividades y eventos con el departamento administrativo.";
									$encabezado[4][14] = "Participé activamente en actividades de promoción de mi(s) carrera(s) en eventos organizados por el departamento de Comunicación e Imagen Institucional.";
									$encabezado[4][15] = "Promoví la participación de los estudiantes en concursos y/o eventos relacionados con la(s) carrera(s) a mi cargo.";
									$encabezado[4][16] = "Di seguimiento a los representantes de grupo de la(s) carrera(s) a mi cargo, para promover actividades sugeridas por ellos acordes con la filosofía de la institución.";
									$encabezado[4][17] = "Promoví la integración y convivencia entre los alumnos en la(s) carrera(s) a mi cargo.";
									$encabezado[4][18] = "Estuve al tanto de los avances de los grupos a mi cargo.";
									$encabezado[4][19] = "Acompañé a las diferentes generaciones en su trayecto universitario (Ingreso – desarrollo – egreso).";
									$encabezado[4][20] = "Organicé eventos que mejoraron la formación profesional de los estudiantes y que posicionaron la(s) carrera(s) a mi cargo, (congresos, seminarios, conferencias, etc.).";
									$encabezado[4][21] = "Vinculé la(s) carrera(s) a mi cargo con los sectores empresarial, gubernamental, no gubernamental, educativo o religioso a través de convenios y acuerdos.";
									$encabezado[4][22] = "Di seguimiento a las generaciones de egresados en la(s) carrera(s) a mi cargo.";
									$encabezado[4][23] = "Diseñé programas y actividades de Educación Continua para egresados y profesionales relacionados con la(s) carrera(s) a mi cargo.";
									$encabezado[4][24] = "Promoví la vinculación con empleadores para retroalimentar los programas de estudio de la(s) carrera(s) a mi cargo.";
									$encabezado[4][25] = "Estuve atento al proceso de aprendizaje, asistencia y resultados académicos de los estudiantes a mi cargo.";
									$encabezado[4][26] = "Mantuve una comunicación constante con los estudiantes, para mantenerlos informados oportunamente, mediante el uso de varios medios (redes sociales, correo electrónico, información impresa, etc.)";
									$encabezado[4][27] = "Promoví actividades encaminadas al desarrollo de valores maristas en los estudiantes (amor al trabajo, espíritu de familia, relaciones cordiales, acompañamiento, etc.)";
									$encabezado[4][28] = "Proporcioné atención y trato respetuosos a los estudiantes cuando requirieron mi intervención.";
									$encabezado[4][29] = "Fomenté la generación de proyectos solidarios a favor de los menos favorecidos.";
									$encabezado[4][30] = "De manera general, la calificación que me otorgo con respecto a mi desempeño como Coordinador es...";
									unset($nombre_reactivo);
									for ($contador_tmp1=1; $contador_tmp1<=$total_bloques; $contador_tmp1++) {
										for ($contador_tmp2=$inicio_reactivos[$contador_tmp1]; $contador_tmp2<=$fin_reactivos[$contador_tmp1]; $contador_tmp2++) {
											$nombre_reactivo[$contador_tmp1][$contador_tmp2] = "r" . str_pad($contador_tmp2, 2, "0", STR_PAD_LEFT);
										}
									}
									// ##################### OBTENER RESULTADOS
									for ($contador_tmp1=1; $contador_tmp1<=$total_bloques; $contador_tmp1++) {
										$hay_respuestas[$contador_tmp1] = false;
										// Genera la orden SQL para hacer la consulta a la tabla de la evaluación general de licenciaturas
										$orden_sql = "SELECT * ";
										$orden_sql .= "FROM `lic_coordinadores_por_" . $nombre_tabla[$contador_tmp1] . "` ";
										$orden_sql .= "WHERE `nombre`='" . $listado_de_coordinadores[$_POST['coordinador']] . "' AND `carrera`='" . $listado_de_carreras[$post_carrera] . "' ";
										$orden_sql .= "ORDER BY `carrera`";
										if ($nombre_tabla[$contador_tmp1]=="alumnos") $orden_sql .= ", `grado`";
										// Ejecuta la consulta SQL
										$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
										if ($resultado_busqueda) {
											if (mysqli_num_rows($resultado_busqueda) > 0) {
												$hay_respuestas[$contador_tmp1] = true;
												if ($contador_tmp1==1) $resultado_busqueda_jefes = mysqli_query($base_de_datos, $orden_sql);
												if ($contador_tmp1==2) $resultado_busqueda_profesores = mysqli_query($base_de_datos, $orden_sql);
												if ($contador_tmp1==3) $resultado_busqueda_alumnos = mysqli_query($base_de_datos, $orden_sql);
												if ($contador_tmp1==4) $resultado_busqueda_coordinadores = mysqli_query($base_de_datos, $orden_sql);
											}
											// Libera el conjunto de resultados
											mysqli_free_result($resultado_busqueda);
										}
									}
								} else {
									$total_bloques = 4;
									$nombre_tabla[1] = "jefes";
									$ponderacion[1] = 0.4;
									$inicio_reactivos[1] = 1;
									$fin_reactivos[1] = 12;
									$inicio_nas[1] = 999; // si no hay reactivos con NA poner un número mayor al número de preguntas
									$nombre_tabla[2] = "profesores";
									$ponderacion[2] = 0.25;
									$inicio_reactivos[2] = 1;
									$fin_reactivos[2] = 12;
									$inicio_nas[2] = 999; // si no hay reactivos con NA poner un número mayor al número de preguntas
									$nombre_tabla[3] = "alumnos";
									$ponderacion[3] = 0.25;
									$inicio_reactivos[3] = 1;
									$fin_reactivos[3] = 8;
									$inicio_nas[3] = 999; // si no hay reactivos con NA poner un número mayor al número de preguntas
									$nombre_tabla[4] = "coordinadores";
									$ponderacion[4] = 0.1;
									$inicio_reactivos[4] = 1;
									$fin_reactivos[4] = 12;
									$inicio_nas[4] = 999; // si no hay reactivos con NA poner un número mayor al número de preguntas
									unset($encabezado);
									$encabezado[1][1] = "Apoyó en la elaboración colegiada del Instrumento de Planeación del Aprendizaje por Competencias de las materias de los distintos ejes institucionales.";
									$encabezado[1][2] = "Dio a conocer en tiempo a los docentes los horarios, salones, y la calendarización académica del semestre.";
									$encabezado[1][3] = "Convoca a reuniones de trabajo a los docentes de los distintos ejes institucionales.";
									$encabezado[1][4] = "Da seguimiento a la implementación de la Planeación del Aprendizaje por Competencias.";
									$encabezado[1][5] = "Es amable y cordial en el trato con los docentes.";
									$encabezado[1][6] = "Escucha y da seguimiento a las peticiones y situaciones que los docentes plantean con respecto a su materia y al alumnado.";
									$encabezado[1][7] = "Vive los valores maristas: solidaridad, amor al trabajo, espíritu de familia y/o apertura a los demás.";
									$encabezado[1][8] = "Demuestra experiencia y dominio de su puesto como coordinadora de materias institucionales.";
									$encabezado[1][9] = "Favorece el adecuado funcionamiento de las materias institucionales.";
									$encabezado[1][10] = "Media y/o da solución a los conflictos que surgen entre el alumnado y los docentes de materias institucionales.";
									$encabezado[1][11] = "De manera general, la calificación que le otorgo a la coordinadora es…";
									$encabezado[1][12] = "Comentarios y sugerencias orientadas a la mejora del desempeño de la coordinadora de materias institucionales.";
									$encabezado[2][1] = "Apoyó en la elaboración colegiada del Instrumento de Planeación del Aprendizaje por Competencias.";
									$encabezado[2][2] = "Dio a conocer en “tiempo y forma” los horarios, salones, y la calendarización académica del semestre.";
									$encabezado[2][3] = "Convoca a reuniones de trabajo a los docentes de los distintos ejes institucionales.";
									$encabezado[2][4] = "Da seguimiento a la implementación de la Planeación del Aprendizaje por Competencias.";
									$encabezado[2][5] = "Es amable y cordial en el trato con los docentes.";
									$encabezado[2][6] = "Escucha y da seguimiento a las peticiones y situaciones que los docentes plantean con respecto a su materia y al alumnado.";
									$encabezado[2][7] = "Vive los valores maristas: solidaridad, amor al trabajo, espíritu de familia y/o apertura a los demás.";
									$encabezado[2][8] = "Demuestra experiencia y dominio de su puesto como coordinadora de materias institucionales.";
									$encabezado[2][9] = "Favorece el adecuado funcionamiento de las materias institucionales.";
									$encabezado[2][10] = "Media y/o da solución a los conflictos que surgen entre el alumnado y los docentes de estas materias.";
									$encabezado[2][11] = "De manera general, la calificación que le otorgo a la coordinadora es…";
									$encabezado[2][12] = "Comentarios y sugerencias orientadas a la mejora del desempeño de la coordinadora de materias institucionales.";
									$encabezado[3][1] = "Es amable y cordial en el trato con los estudiantes.";
									$encabezado[3][2] = "Acompaña, da seguimiento y resuelve las situaciones que los estudiantes plantean en lo referido a las materias institucionales.";
									$encabezado[3][3] = "Da atención personal y trato respetuoso al alumnado cuando requieren su apoyo.";
									$encabezado[3][4] = "Vive los valores maristas: solidaridad, amor al trabajo, espíritu de familia y/o apertura a los demás.";
									$encabezado[3][5] = "Demuestra experiencia y dominio de su puesto como coordinadora de materias institucionales.";
									$encabezado[3][6] = "Media y/o da solución a los conflictos que surgen entre el alumnado y los docentes de materias institucionales";
									$encabezado[3][7] = "De manera general, la calificación que le otorgo a la coordinadora es…";
									$encabezado[3][8] = "Comentarios y sugerencias orientadas a la mejora del desempeño de la coordinadora de materias institucionales.";
									$encabezado[4][1] = "Apoyé en la elaboración colegiada del Instrumento de Planeación del Aprendizaje por Competencias de las materias de los distintos ejes institucionales.";
									$encabezado[4][2] = "Di a conocer en tiempo a los docentes los horarios, salones, y la calendarización académica del semestre.";
									$encabezado[4][3] = "Convoco a reuniones de trabajo a los docentes de los distintos ejes institucionales.";
									$encabezado[4][4] = "Doy seguimiento a la implementación de la Planeación del Aprendizaje por Competencias.";
									$encabezado[4][5] = "Soy amable y cordial en el trato con los docentes.";
									$encabezado[4][6] = "Escucho y doy seguimiento a las peticiones y situaciones que los docentes plantean con respecto a su materia y al alumnado.";
									$encabezado[4][7] = "Vivo los valores maristas: solidaridad, amor al trabajo, espíritu de familia y/o apertura a los demás.";
									$encabezado[4][8] = "Demuestro experiencia y dominio de su puesto como coordinadora de materias institucionales.";
									$encabezado[4][9] = "Favorezco el adecuado funcionamiento de las materias institucionales.";
									$encabezado[4][10] = "Medio y/o doy solución a los conflictos que surgen entre el alumnado y los docentes de materias institucionales.";
									$encabezado[4][11] = "De manera general, la calificación que me otorgo como coordinadora es…";
									$encabezado[4][12] = "Comentarios en relación a mi desempeño como coordinadora de materias institucionales.";
									unset($nombre_reactivo);
									for ($contador_tmp1=1; $contador_tmp1<=$total_bloques; $contador_tmp1++) {
										for ($contador_tmp2=$inicio_reactivos[$contador_tmp1]; $contador_tmp2<=$fin_reactivos[$contador_tmp1]; $contador_tmp2++) {
											$nombre_reactivo[$contador_tmp1][$contador_tmp2] = "r" . str_pad($contador_tmp2, 2, "0", STR_PAD_LEFT);
										}
									}
									// ##################### OBTENER RESULTADOS
									for ($contador_tmp1=1; $contador_tmp1<=$total_bloques; $contador_tmp1++) {
										$hay_respuestas[$contador_tmp1] = false;
										// Genera la orden SQL para hacer la consulta a la tabla de la evaluación general de licenciaturas
										$orden_sql = "SELECT * ";
										$orden_sql .= "FROM `lic_materiasi_por_" . $nombre_tabla[$contador_tmp1] . "` ";
										$orden_sql .= "WHERE 1";
										if ($nombre_tabla[$contador_tmp1]=="alumnos") $orden_sql .= " ORDER BY `carrera`, `grado`";
										// Ejecuta la consulta SQL
										$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
										if ($resultado_busqueda) {
											if (mysqli_num_rows($resultado_busqueda) > 0) {
												$hay_respuestas[$contador_tmp1] = true;
												if ($contador_tmp1==1) $resultado_busqueda_jefes = mysqli_query($base_de_datos, $orden_sql);
												if ($contador_tmp1==2) $resultado_busqueda_profesores = mysqli_query($base_de_datos, $orden_sql);
												if ($contador_tmp1==3) $resultado_busqueda_alumnos = mysqli_query($base_de_datos, $orden_sql);
												if ($contador_tmp1==4) $resultado_busqueda_coordinadores = mysqli_query($base_de_datos, $orden_sql);
											}
											// Libera el conjunto de resultados
											mysqli_free_result($resultado_busqueda);
										}
									}
								}
								// =========================================================================================================
								//   OBTIENE LOS DATOS DE LA TABLA PONDERADA
								// =========================================================================================================
								$estan_todos_los_aportes = true;
								$gran_total = 0;
								for ($contador_tmp1=1; $contador_tmp1<=$total_bloques; $contador_tmp1++) {
									$aporte[$contador_tmp1] = -1;
									if ($hay_respuestas[$contador_tmp1]) {
										if ($contador_tmp1==1) $resultado_busqueda = $resultado_busqueda_jefes;
										if ($contador_tmp1==2) $resultado_busqueda = $resultado_busqueda_profesores;
										if ($contador_tmp1==3) $resultado_busqueda = $resultado_busqueda_alumnos;
										if ($contador_tmp1==4) $resultado_busqueda = $resultado_busqueda_coordinadores;
										$calificacion_total = 0;
										$descartados = 0;
										unset($calificaciones);
										for ($contador_tmp2=$inicio_reactivos[$contador_tmp1]; $contador_tmp2<=($fin_reactivos[$contador_tmp1]-1); $contador_tmp2++) {
											$calificaciones[$contador_tmp2] = 0;
											$numero_respuestas[$contador_tmp2] = 0;
											$nas[$contador_tmp2] = 0;
										}
										while($registro = mysqli_fetch_array($resultado_busqueda)) {
											for ($contador_tmp2=$inicio_reactivos[$contador_tmp1]; $contador_tmp2<=($fin_reactivos[$contador_tmp1]-1); $contador_tmp2++) {
												$calificaciones[$contador_tmp2] += $registro[$nombre_reactivo[$contador_tmp1][$contador_tmp2]];
												$numero_respuestas[$contador_tmp2]++;
												if ($registro[$nombre_reactivo[$contador_tmp1][$contador_tmp2]]==0) $nas[$contador_tmp2]++;
											}
										}
										for ($contador_tmp2=$inicio_reactivos[$contador_tmp1]; $contador_tmp2<=($fin_reactivos[$contador_tmp1]-1); $contador_tmp2++) {
											if ($calificaciones[$contador_tmp2] == 0) $descartados++;
											if (($numero_respuestas[$contador_tmp2] - $nas[$contador_tmp2])>0) $calificacion_total += $calificaciones[$contador_tmp2] / ($numero_respuestas[$contador_tmp2] - $nas[$contador_tmp2]);
										}
										$calificacion_total = $calificacion_total / (($fin_reactivos[$contador_tmp1]-1)-$descartados);
										//$aporte[$contador_tmp1] = number_format(round($calificacion_total, 2),2);
										$aporte[$contador_tmp1] = round($calificacion_total, 2);
										$gran_total += $aporte[$contador_tmp1] * $ponderacion[$contador_tmp1];
									} else { $estan_todos_los_aportes = false; }
								}
								$gran_total = round($gran_total,2);
								// =========================================================================================================
								//   DESPLEGAR TABLA PONDERADA
								// ========================================================================================================= ?>
								<div class="seccion">CALIFICACIÓN GENERAL</div>
								<div class="contenedor-interno">
									<table class="table table-bordered table-striped table-sm"style="font-size:80%;margin-left:auto;margin-right:auto;">
										<tr>
											<th class='fondo-marista'>Evaluación</th>
											<?php
												for ($contador_tmp1=1; $contador_tmp1<=$total_bloques; $contador_tmp1++) {
													$texto_bloque = ucfirst($nombre_tabla[$contador_tmp1]);
													if ('Coordinadores'==$texto_bloque) $texto_bloque = 'Autoevaluación';
													if ('Jefes'==$texto_bloque) $texto_bloque = 'Jefe de área';
													echo "<th class='fondo-marista' style='background:#1f497d;color:#ffffff;width:20%;'>$texto_bloque</th>";
												}
											?>
										</tr>
										<tr>
											<th class='fondo-marista'>Calificación</th>
											<?php
												for ($contador_tmp1=1; $contador_tmp1<=$total_bloques; $contador_tmp1++) {
													echo "<td";
													if ($aporte[$contador_tmp1]<0) echo " style='background:#ffff66;'";
													echo ">";
													if ($aporte[$contador_tmp1]>=0) {
														echo number_format($aporte[$contador_tmp1],2);
													} else {
														echo "SIN DATOS";
													}
													echo "</td>";
												}
											?>
										</tr>
										<tr>
											<th class='fondo-marista'>Ponderación</th>
											<?php
												for ($contador_tmp1=1; $contador_tmp1<=$total_bloques; $contador_tmp1++) {
													echo "<th class='fondo-marista' style='background:#1f497d;color:#ffffff;'>" . ($ponderacion[$contador_tmp1]*100) . "%</th>";
												}
											?>
										</tr>
										<tr>
											<th class='fondo-marista'>Aporte a CG</th>
											<?php
												for ($contador_tmp1=1; $contador_tmp1<=$total_bloques; $contador_tmp1++) {
													echo "<td";
													if ($aporte[$contador_tmp1]<0) echo " style='background:#ffff66;'";
													echo ">";
													if ($aporte[$contador_tmp1]>=0) {
														echo number_format(round($aporte[$contador_tmp1]*$ponderacion[$contador_tmp1],4),4);
													} else {
														echo "SIN DATOS";
													}
													echo "</td>";
												}
											?>
										</tr>
										<tr>
											<th class='fondo-marista'>Calificación General</th>
											<td colspan="4" style="font-weight:bold;font-size:100%;<?php if (!$estan_todos_los_aportes) echo "background:#ff6666;"; ?>"><?php if ($estan_todos_los_aportes) { echo number_format($gran_total,2); } else { echo "INCOMPLETO"; } ?></td>
										</tr>
									</table>
								</div> <?php
								if ('MATERIAS INSTITUCIONALES' != $listado_de_carreras[$post_carrera]) {
									// ##################### OBTENER RESULTADOS
									for ($contador_tmp1=1; $contador_tmp1<=$total_bloques; $contador_tmp1++) {
										$hay_respuestas[$contador_tmp1] = false;
										// Genera la orden SQL para hacer la consulta a la tabla de la evaluación general de licenciaturas
										$orden_sql = "SELECT * ";
										$orden_sql .= "FROM `lic_coordinadores_por_" . $nombre_tabla[$contador_tmp1] . "` ";
										$orden_sql .= "WHERE `nombre`='" . $listado_de_coordinadores[$_POST['coordinador']] . "' AND `carrera`='" . $listado_de_carreras[$post_carrera] . "' ";
										$orden_sql .= "ORDER BY `carrera`";
										if ($nombre_tabla[$contador_tmp1]=="alumnos") $orden_sql .= ", `grado`";
										// Ejecuta la consulta SQL
										$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
										if ($resultado_busqueda) {
											if (mysqli_num_rows($resultado_busqueda) > 0) {
												$hay_respuestas[$contador_tmp1] = true;
												if ($contador_tmp1==1) $resultado_busqueda_jefes = mysqli_query($base_de_datos, $orden_sql);
												if ($contador_tmp1==2) $resultado_busqueda_profesores = mysqli_query($base_de_datos, $orden_sql);
												if ($contador_tmp1==3) $resultado_busqueda_alumnos = mysqli_query($base_de_datos, $orden_sql);
												if ($contador_tmp1==4) $resultado_busqueda_coordinadores = mysqli_query($base_de_datos, $orden_sql);
											}
											// Libera el conjunto de resultados
											mysqli_free_result($resultado_busqueda);
										}
									}
								} else {
									// ##################### OBTENER RESULTADOS
									for ($contador_tmp1=1; $contador_tmp1<=$total_bloques; $contador_tmp1++) {
										$hay_respuestas[$contador_tmp1] = false;
										// Genera la orden SQL para hacer la consulta a la tabla de la evaluación general de licenciaturas
										$orden_sql = "SELECT * ";
										$orden_sql .= "FROM `lic_materiasi_por_" . $nombre_tabla[$contador_tmp1] . "` ";
										$orden_sql .= "WHERE 1";
										if ($nombre_tabla[$contador_tmp1]=="alumnos") $orden_sql .= " ORDER BY `carrera`, `grado`";
										// Ejecuta la consulta SQL
										$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
										if ($resultado_busqueda) {
											if (mysqli_num_rows($resultado_busqueda) > 0) {
												$hay_respuestas[$contador_tmp1] = true;
												if ($contador_tmp1==1) $resultado_busqueda_jefes = mysqli_query($base_de_datos, $orden_sql);
												if ($contador_tmp1==2) $resultado_busqueda_profesores = mysqli_query($base_de_datos, $orden_sql);
												if ($contador_tmp1==3) $resultado_busqueda_alumnos = mysqli_query($base_de_datos, $orden_sql);
												if ($contador_tmp1==4) $resultado_busqueda_coordinadores = mysqli_query($base_de_datos, $orden_sql);
											}
											// Libera el conjunto de resultados
											mysqli_free_result($resultado_busqueda);
										}
									}
								}
								// =========================================================================================================
								//   DESPLEGAR LOS RESULTADOS
								// =========================================================================================================
								unset($comentarios);
								unset($contador_comentarios);
								$vacio = 0;
								for ($contador_tmp1=1; $contador_tmp1<=$total_bloques; $contador_tmp1++) {
									$contador_comentarios[$contador_tmp1] = 0;
									if(isset($_POST['datos_' . $nombre_tabla[$contador_tmp1]])) {
										$texto_bloque = strtoupper($nombre_tabla[$contador_tmp1]);
										if ('COORDINADORES'==$texto_bloque) $texto_bloque = 'AUTOEVALUACIÓN';
										if ('JEFES'==$texto_bloque) $texto_bloque = 'JEFE DE ÁREA';
										echo "<div class='seccion'>$texto_bloque</div>";
										echo "<div class='contenedor-interno'>";
										if ($hay_respuestas[$contador_tmp1]) {
											if ($contador_tmp1==1) $resultado_busqueda = $resultado_busqueda_jefes;
											if ($contador_tmp1==2) $resultado_busqueda = $resultado_busqueda_profesores;
											if ($contador_tmp1==3) $resultado_busqueda = $resultado_busqueda_alumnos;
											if ($contador_tmp1==4) $resultado_busqueda = $resultado_busqueda_coordinadores;
											$calificacion_total = 0;
											$descartados = 0;
											unset($calificaciones);
											for ($contador_tmp2=$inicio_reactivos[$contador_tmp1]; $contador_tmp2<=($fin_reactivos[$contador_tmp1]-1); $contador_tmp2++) {
												$calificaciones[$contador_tmp2] = 0;
												$numero_respuestas[$contador_tmp2] = 0;
												$nas[$contador_tmp2] = 0;
											}
											while($registro = mysqli_fetch_array($resultado_busqueda)) {
												for ($contador_tmp2=$inicio_reactivos[$contador_tmp1]; $contador_tmp2<=($fin_reactivos[$contador_tmp1]-1); $contador_tmp2++) {
													$calificaciones[$contador_tmp2] += $registro[$nombre_reactivo[$contador_tmp1][$contador_tmp2]];
													$numero_respuestas[$contador_tmp2]++;
													if ($registro[$nombre_reactivo[$contador_tmp1][$contador_tmp2]]==0) $nas[$contador_tmp2]++;
												}
												if ($registro[$nombre_reactivo[$contador_tmp1][$fin_reactivos[$contador_tmp1]]]!="") {
													$contador_comentarios[$contador_tmp1]++;
													$comentarios[$contador_tmp1][$contador_comentarios[$contador_tmp1]] = $registro[$nombre_reactivo[$contador_tmp1][$fin_reactivos[$contador_tmp1]]];
												}
											}
											for ($contador_tmp2=$inicio_reactivos[$contador_tmp1]; $contador_tmp2<=($fin_reactivos[$contador_tmp1]-1); $contador_tmp2++) {
												if ($calificaciones[$contador_tmp2] == 0) $descartados++;
												if (($numero_respuestas[$contador_tmp2] - $nas[$contador_tmp2])>0) $calificacion_total += $calificaciones[$contador_tmp2] / ($numero_respuestas[$contador_tmp2] - $nas[$contador_tmp2]);
											}
											$calificacion_total = $calificacion_total / (($fin_reactivos[$contador_tmp1]-1)-$descartados); ?>
											<table class="table table-bordered table-striped table-sm"style="font-size:80%;width:100%;">
												<tr>
													<th class='fondo-marista' style="background:#1f497d;color:#ffffff;width:33px;">No.</th>
													<th class='fondo-marista' style="background:#1f497d;color:#ffffff;">Reactivo</th>
													<th class='fondo-marista' style="background:#1f497d;color:#ffffff;width:50px;">Calif. base 10</th>
												</tr> <?php
												for ($contador_tmp2=$inicio_reactivos[$contador_tmp1]; $contador_tmp2<=($fin_reactivos[$contador_tmp1]-1); $contador_tmp2++) { ?>
													<tr>
														<th class='fondo-marista'><?php echo $contador_tmp2; ?></th>
														<td style="text-align:left;background:#c5d9f1;">
															<?php echo $encabezado[$contador_tmp1][$contador_tmp2]; ?>
														</td>
														<td style="font-size:100%;">
															<?php if (($calificaciones[$contador_tmp2]==0) and ($contador_tmp2>=$inicio_nas[$contador_tmp1])) { echo "NA"; } else { echo number_format(round($calificaciones[$contador_tmp2] / ($numero_respuestas[$contador_tmp2] - $nas[$contador_tmp2]), 2),2); } ?>
														</td>
													</tr> <?php
												} ?>
												<tr style="font-size:150%;">
													<th class='fondo-marista' colspan="2">CALIFICACIÓN TOTAL</th>
													<td style="font-weight:bold;"><?php echo number_format(round($calificacion_total, 2),2); ?></td>
												</tr>
											</table> <?php
										} else { echo "<p>No hay respuestas registradas para esta sección.</p>"; }
										echo "</div>";
									} else { $vacio++; }
								}
								if ($vacio == 4) {
									echo "<div class='contenedor-interno'>";
									echo "<p>No hay información para mostrar.</p>";
									echo "</div>";
								}
								// ####### DESPLIEGA LOS COMENTARIOS
								if (isset($_POST['ver_comentarios'])) {
									for ($contador_tmp1=1; $contador_tmp1<=$total_bloques; $contador_tmp1++) {
										if(isset($_POST['datos_' . $nombre_tabla[$contador_tmp1]])) {
											$texto_bloque = strtoupper($nombre_tabla[$contador_tmp1]);
											if ('COORDINADORES'==$texto_bloque) $texto_bloque = 'AUTOEVALUACIÓN';
											if ('JEFES'==$texto_bloque) $texto_bloque = 'JEFE DE ÁREA';
											echo "<div class='seccion'>COMENTARIOS - $texto_bloque</div>";
											echo "<div class='contenedor-interno'>";
											if ($contador_comentarios[$contador_tmp1]==0) {
												echo "<p>No se hicieron comentarios.</p>";
											} else { ?>
												<table class="table table-bordered table-striped table-sm"style="font-size:80%;width:100%;">
													<tr>
														<th class='fondo-marista' style="width:33px;">No.</th>
														<th class='fondo-marista'>Comentario</th>
													</tr>
													<?php
														for ($contador_tmp2=1; $contador_tmp2<=$contador_comentarios[$contador_tmp1]; $contador_tmp2++) {
															echo "<tr>";
															echo "<td>" . $contador_tmp2 . "</td>";
															echo "<td style='text-align:left;'>" . $comentarios[$contador_tmp1][$contador_tmp2] . "</td>";
															echo "</tr>";
														}
													?>
												</table> <?php
											}
											echo "</div>";
										}
									}
								}
							} else {
								include 'lic_deporteycultura_c_general.php';
								$calificacion_general = 0;
								for ($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) {
									$promedio_secciones[$contador_secciones] = 0;
									for ($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
										$promedio_subsecciones[$contador_secciones][$contador_subsecciones] = 0;
										for ($contador_reactivos=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_reactivos<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_reactivos++) {
											$promedio_reactivos[$contador_secciones][$contador_reactivos] = 0;
											$orden_sql = 'SELECT AVG(`r' . (($contador_reactivos<10) ? '0': '') . $contador_reactivos . '`) AS `promedio` FROM `' . $secciones[$contador_secciones][0]['tabla'] . '`';
											$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
											if ($resultado_busqueda) {
												if (mysqli_num_rows($resultado_busqueda) > 0) {
													$registro = mysqli_fetch_array($resultado_busqueda);
													$promedio_reactivos[$contador_secciones][$contador_reactivos] = round($registro['promedio'],4);
													$promedio_subsecciones[$contador_secciones][$contador_subsecciones] += $promedio_reactivos[$contador_secciones][$contador_reactivos];
												}
												// Libera el conjunto de resultados
												mysqli_free_result($resultado_busqueda);
											}
										}
										$promedio_subsecciones[$contador_secciones][$contador_subsecciones] = round($promedio_subsecciones[$contador_secciones][$contador_subsecciones] / ($secciones[$contador_secciones][$contador_subsecciones]['termina'] - $secciones[$contador_secciones][$contador_subsecciones]['inicia'] + 1),4);
										$promedio_secciones[$contador_secciones] += $promedio_subsecciones[$contador_secciones][$contador_subsecciones];
									}
									$promedio_secciones[$contador_secciones] = round($promedio_secciones[$contador_secciones] / $total_subsecciones[$contador_secciones],4);
									$calificacion_general += $promedio_secciones[$contador_secciones] * $secciones[$contador_secciones][0]['ponderacion'];
								} ?>
								<div class="contenedor-interno">
									<table class="table table-bordered table-striped table-sm"style="font-size:80%;margin-left:auto;margin-right:auto;">
										<tr>
											<th class='fondo-marista'>Evaluación</th><?php
											for($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) { ?>
												<th class='fondo-marista' style="background:#1f497d;color:#ffffff;width:<?php echo (int)(80/$total_secciones); ?>%;"><?php echo $secciones[$contador_secciones][0]['encabezado']; ?></th> <?php
											} ?>
										</tr>
										<tr>
											<th class='fondo-marista'>Calificación</th> <?php
											for($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) { ?>
												<td><?php echo (0==$promedio_secciones[$contador_secciones]) ? 'Sin datos' : number_format($promedio_secciones[$contador_secciones],4); ?></td> <?php
											} ?>
										</tr>
										<tr>
											<th class='fondo-marista'>Ponderación</th> <?php
											for($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) { ?>
												<th class='fondo-marista' style="background:#1f497d;color:#ffffff;"><?php echo number_format($secciones[$contador_secciones][0]['ponderacion'] * 100,2); ?>%</th> <?php
											} ?>
										</tr>
										<tr>
											<th class='fondo-marista'>Aporte a CG</th> <?php
											for($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) { ?>
												<td><?php echo (0==$promedio_secciones[$contador_secciones]) ? 'Sin datos' : number_format($promedio_secciones[$contador_secciones] * $secciones[$contador_secciones][0]['ponderacion'],4); ?></td> <?php
											} ?>
										</tr>
										<tr>
											<th class='fondo-marista'>Calificación General</th>
											<td colspan="<?php echo $total_secciones; ?>" style="font-weight:bold;font-size:100%;"><?php echo (0==$calificacion_general) ? 'Sin datos' : number_format($calificacion_general,4); ?></td>
										</tr>
									</table>
								</div> <?php
								for($contador_secciones=1; $contador_secciones<=$total_secciones; $contador_secciones++) { ?>
									<div class="seccion"><?php echo $secciones[$contador_secciones][0]['texto'];?></div>
									<div class="contenedor-interno">
										<table class="table table-bordered table-striped table-sm"style="font-size:80%;margin-left:auto;margin-right:auto;">
											<tr>
												<th class='fondo-marista' style="width:7%;">No.</th>
												<th class='fondo-marista' style="width:79%;">Pregunta</th>
												<th class='fondo-marista' style="width:14%;">Promedio</th>
											</tr>
											<?php
												for($contador_subsecciones=1; $contador_subsecciones<=$total_subsecciones[$contador_secciones]; $contador_subsecciones++) {
													for($contador_reactivos=$secciones[$contador_secciones][$contador_subsecciones]['inicia']; $contador_reactivos<=$secciones[$contador_secciones][$contador_subsecciones]['termina']; $contador_reactivos++) {
														echo "<tr>";
														echo "<td><strong>" . $contador_reactivos . "</strong></td>";
														echo "<td style='text-align:left;'>" . $texto_reactivo[$contador_secciones][$contador_reactivos] . "</td>";
														echo "<td>" . ((0==$promedio_reactivos[$contador_secciones][$contador_reactivos]) ? 'Sin datos' : number_format($promedio_reactivos[$contador_secciones][$contador_reactivos],4)) . "</td>";
														echo "</tr>";
													}
													if ($secciones[$contador_secciones][$contador_subsecciones]['comentario']) {
														echo "<tr>";
														echo "<td colspan='3' style='text-align:left;'>";
														echo "<strong>Comentarios:</strong><br />";
														$sin_comentarios = true;
														$orden_sql = 'SELECT `r' . ((($secciones[$contador_secciones][$contador_subsecciones]['termina']+1)<10) ? '0': '') . ($secciones[$contador_secciones][$contador_subsecciones]['termina']+1) . '` AS `comentario` FROM `' . $secciones[$contador_secciones][0]['tabla'] . '`';
														$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
														if ($resultado_busqueda) {
															if (mysqli_num_rows($resultado_busqueda) > 0) {
																while($registro = mysqli_fetch_array($resultado_busqueda)) {
																	$sin_comentarios = false;
																	if ((''!=trim($registro['comentario'])) and (strlen(trim($registro['comentario']))>1)) echo "<li>" . trim($registro['comentario']) . "";
																}
															}
															// Libera el conjunto de resultados
															mysqli_free_result($resultado_busqueda);
														}
														if ($sin_comentarios) echo "Sin comentarios.";
														echo "</td>";
														echo "</tr>";
													}
												}
											?>
										</table>
									</div><?php
								}
							}
						?>
					</div>
					<div class="nota-verde">
						<table class="table table-bordered table-striped table-sm"style="border:none;text-align:left;">
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
	<?php
		}
		mysqli_close($base_de_datos); 
	?>
	<script type="text/javascript"> window.print(); </script>
</html>