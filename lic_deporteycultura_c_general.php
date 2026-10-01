<?php
	// Establece las secciones de reactivos
	$secciones[1][0]['numeracion'] = 'A'; // Establece la numeración para esta sección, dejar en blanco para no numerar.
	$secciones[1][0]['encabezado'] = 'Profesores';
	$secciones[1][0]['texto'] = 'Evaluación de los profesores'; // Encabezado de la sección
	$secciones[1][0]['ponderacion'] = .5; // Aporte a la calificación total
	$secciones[1][0]['tabla'] = 'lic_deporteycultura_c_por_profesores'; // Tabla de la base de datos que contiene las respuestas.
	$secciones[1][1]['inicia'] = 1;  // Número de reactivo con el que inicia la subsección
	$secciones[1][1]['termina'] = 9;  // Número de reactivo con el que termina la subsección
	$secciones[1][1]['tipo'] = 'CERRADA'; // Los valores posibles son 'CERRADA', 'MULTIPLE' o 'ABIERTA'
	$secciones[1][1]['multiplicador'] = 1; // Por cuánto hay que multiplicar el valor máximo para obntener 10
	$secciones[1][1]['cero'] = ''; // Si el valor de cero se tomará para no aplica poner 'NA', para insuficiente 'INS', si no hay valor cero poner ''
	$secciones[1][1]['comentario'] = false; // Indica si después de los reactivos hay uno más con comentarios. Valores true o false.
	$secciones[2][0]['numeracion'] = 'B';
	$secciones[2][0]['encabezado'] = 'Jefe';
	$secciones[2][0]['texto'] = 'Evaluación del Jefe';
	$secciones[2][0]['ponderacion'] = .4;
	$secciones[2][0]['tabla'] = 'lic_deporteycultura_c_por_jefes';
	$secciones[2][1]['inicia'] = 1;
	$secciones[2][1]['termina'] = 16;
	$secciones[2][1]['tipo'] = 'CERRADA';
	$secciones[2][1]['multiplicador'] = 1;
	$secciones[2][1]['cero'] = '';
	$secciones[2][1]['comentario'] = false;
	$secciones[3][0]['numeracion'] = 'C';
	$secciones[3][0]['encabezado'] = 'Autoevaluación';
	$secciones[3][0]['texto'] = 'Autoevaluación';
	$secciones[3][0]['ponderacion'] = .1;
	$secciones[3][0]['tabla'] = 'lic_deporteycultura_c_por_coordinadores';
	$secciones[3][1]['inicia'] = 1;
	$secciones[3][1]['termina'] = 15;
	$secciones[3][1]['tipo'] = 'CERRADA';
	$secciones[3][1]['multiplicador'] = 1;
	$secciones[3][1]['cero'] = '';
	$secciones[3][1]['comentario'] = true;
	// Establece el total de secciones y subsecciones así como de reactivos
	$total_secciones = sizeof($secciones);
	$total_reactivos = 0;
	for ($contador1=1; $contador1<=$total_secciones; $contador1++) {
		$total_subsecciones[$contador1] = sizeof($secciones[$contador1]) - 1;
		for ($contador2=1; $contador2<=$total_subsecciones[$contador1]; $contador2++) $total_reactivos += ($secciones[$contador1][$contador2]['termina'] - $secciones[$contador1][$contador2]['inicia'] + 1);
	}
	// Establece el texto de los reactivos
	$texto_reactivo[1][1] = 'Promueve la participación de los docentes de las actividades deportivas y culturales a su cargo en talleres y cursos de capacitación propuestos por la Universidad.';
	$texto_reactivo[1][2] = 'Supervisa el trabajo de los profesores de los talleres deportivos y culturales para asegurar la calidad en la formación integral de los estudiantes.';
	$texto_reactivo[1][3] = 'Convoca a reuniones de trabajo a los colegios de profesores.';
	$texto_reactivo[1][4] = 'Promueve la revisión de los programas en las reuniones de trabajo de los colegios de profesores.';
	$texto_reactivo[1][5] = 'Planea los procesos propios de las diferentes áreas a su cargo en las fechas marcadas por la Institución.';
	$texto_reactivo[1][6] = 'Entregó en tiempo la calendarización de las actividades previstas del semestre.';
	$texto_reactivo[1][7] = 'Realiza las actividades programadas en el calendario, respetando las fechas dadas.';
	$texto_reactivo[1][8] = 'Recibo atención oportuna y respetuosa cuando es necesario o cuando la requiero.';
	$texto_reactivo[1][9] = 'De manera general, la calificación que le otorgo con respecto a su desempeño en la coordinación es...';
	$texto_reactivo[2][1] = 'Promueve la participación de los docentes de las actividades deportivas y culturales a su cargo en talleres y cursos de capacitación propuestos por la Universidad para asegurar que conozcan la filosofía y pedagogía marista.';
	$texto_reactivo[2][2] = 'Capacita a los profesores y entrenadores de su área en los procedimientos institucionales relacionados con su quehacer, tales como: evaluaciones, desarrollo de planes de trabajo.';
	$texto_reactivo[2][3] = 'Supervisa el trabajo de los profesores de los talleres deportivos y culturales para asegurar la calidad en la formación integral de los estudiantes.';
	$texto_reactivo[2][4] = 'Diseña y consigue la aprobación de los programas y proyectos de las actividades artísticas y deportivas de la Institución.';
	$texto_reactivo[2][5] = 'Promueve y difunde entre la comunidad estudiantil las diferentes actividades propuestas en los programas y proyectos de su área.';
	$texto_reactivo[2][6] = 'Realiza las actividades programadas en el calendario, respetando las fechas dadas.';
	$texto_reactivo[2][7] = 'Reporta en tiempo y forma los Créditos de Formación y las horas ATI de los alumnos de Licenciatura que participan en las actividades de su área.';
	$texto_reactivo[2][8] = 'Proporciona a Tesorería en tiempo y forma la información requerida para el cobro de cuotas de recuperación y pago de eventos en actividades promovidas por su área.';
	$texto_reactivo[2][9] = 'Solicitó a Control Escolar, en tiempo y forma, la información requerida para la inscripción de los equipos y grupos en eventos culturales y deportivos locales, regionales o nacionales.';
	$texto_reactivo[2][10] = 'Participa con otras áreas de la Institución, para que mediante los eventos deportivos y culturales, se promueva a la institución y su posicionamiento en la sociedad.';
	$texto_reactivo[2][11] = 'Seleccionó a los profesores y entrenadores con el perfil idóneo, de las diferentes disciplinas de cada programa y, en su caso, obtuvo el visto bueno del Jefe de Formación Integral.';
	$texto_reactivo[2][12] = 'Garantiza que la información en los expedientes (académico y administrativo) de los profesores y entrenadores sea auténtica y esté completa.';
	$texto_reactivo[2][13] = 'Evalua el desempeño de profesores y entrenadores, para retroalimentar y desarrollar planes de mejora en su actividad.';
	$texto_reactivo[2][14] = 'Revisa y genera propuestas de mejora de las instalaciones deportivas y culturales.';
	$texto_reactivo[2][15] = 'Su actitud es de apertura, cordial y respetosa en las reuniones de trabajo.';
	$texto_reactivo[2][16] = 'De manera general, la calificación que le otorgo con respecto a su desempeño en la coordinación es...';
	$texto_reactivo[3][1] = 'Promuevo la participación de los docentes de las actividades deportivas y culturales a mi cargo en talleres y cursos de capacitación propuestos por la Universidad para asegurar que conozcan la filosofía y pedagogía marista.';
	$texto_reactivo[3][2] = 'Capacito a los profesores y entrenadores de cada área en los procedimientos institucionales relacionados con su quehacer, tales como: evaluaciones, desarrollo de planes de trabajo.';
	$texto_reactivo[3][3] = 'Superviso el trabajo de los profesores de los talleres deportivos y culturales para asegurar la calidad en la formación integral de los estudiantes.';
	$texto_reactivo[3][4] = 'Diseño y consigo la aprobación de los programas y proyectos de las actividades artísticas y deportivas de la Institución.';
	$texto_reactivo[3][5] = 'Promuevo y difundo entre la comunidad estudiantil las diferentes actividades propuestas en los programas y proyectos deportivos y culturales.';
	$texto_reactivo[3][6] = 'Realizo las actividades programadas en el calendario, respetando las fechas dadas.';
	$texto_reactivo[3][7] = 'Reporto en tiempo y forma los Créditos de Formación y las horas ATI de los alumnos de Licenciatura que participaron en las actividades de su área.';
	$texto_reactivo[3][8] = 'Proporciono a Tesorería en tiempo y forma la información requerida para el cobro de cuotas de recuperación y pago de eventos en actividades promovidas por su área.';
	$texto_reactivo[3][9] = 'Solicito a Control Escolar, en tiempo y forma, la información requerida para la inscripción de los equipos y grupos en eventos culturales y deportivos locales, regionales o nacionales.';
	$texto_reactivo[3][10] = 'Participo con otras áreas de la Institución para que, mediante los eventos deportivos y culturales, se promueva a la institución y su posicionamiento en la sociedad.';
	$texto_reactivo[3][11] = 'Selecciono a los profesores y entrenadores con el perfil idóneo, de las diferentes disciplinas de cada programa y, en su caso, obtengo el visto bueno del jefe de Formación Integral.';
	$texto_reactivo[3][12] = 'Garantizo que la información en los expedientes (académico y administrativo) de los profesores y entrenadores sea auténtica y esté completa.';
	$texto_reactivo[3][13] = 'Evalúo el desempeño de profesores y entrenadores para retroalimentar y desarrollar planes de mejora en su actividad.';
	$texto_reactivo[3][14] = 'Reviso y genero propuestas de mejora de las instalaciones deportivas y culturales.';
	$texto_reactivo[3][15] = 'Mi actitud es de apertura, cordialidad y de respeto en las reuniones de trabajo.';
?>