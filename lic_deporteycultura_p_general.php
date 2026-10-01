<?php
	// Establece las secciones de reactivos
	$secciones[1][0]['numeracion'] = 'A'; // Establece la numeración para esta sección, dejar en blanco para no numerar.
	$secciones[1][0]['encabezado'] = 'Alumnos';
	$secciones[1][0]['texto'] = 'Evaluación de los alumnos'; // Encabezado de la sección
	$secciones[1][0]['ponderacion'] = .5; // Aporte a la calificación total
	$secciones[1][0]['tabla'] = 'lic_deporteycultura_p_por_alumnos'; // Tabla de la base de datos que contiene las respuestas.
	$secciones[1][1]['inicia'] = 1;  // Número de reactivo con el que inicia la subsección
	$secciones[1][1]['termina'] = 14;  // Número de reactivo con el que termina la subsección
	$secciones[1][1]['tipo'] = 'CERRADA'; // Los valores posibles son 'CERRADA', 'MULTIPLE' o 'ABIERTA'
	$secciones[1][1]['multiplicador'] = 1; // Por cuánto hay que multiplicar el valor máximo para obntener 10
	$secciones[1][1]['cero'] = ''; // Si el valor de cero se tomará para no aplica poner 'NA', para insuficiente 'INS', si no hay valor cero poner ''
	$secciones[1][1]['comentario'] = true; // Indica si después de los reactivos hay uno más con comentarios. Valores true o false.
	$secciones[2][0]['numeracion'] = 'B';
	$secciones[2][0]['encabezado'] = 'Coordinador';
	$secciones[2][0]['texto'] = 'Evaluación del Coordinador';
	$secciones[2][0]['ponderacion'] = .4;
	$secciones[2][0]['tabla'] = 'lic_deporteycultura_p_por_coordinadores';
	$secciones[2][1]['inicia'] = 1;
	$secciones[2][1]['termina'] = 10;
	$secciones[2][1]['tipo'] = 'CERRADA';
	$secciones[2][1]['multiplicador'] = 1;
	$secciones[2][1]['cero'] = '';
	$secciones[2][1]['comentario'] = false;
	$secciones[3][0]['numeracion'] = 'C';
	$secciones[3][0]['encabezado'] = 'Autoevaluación';
	$secciones[3][0]['texto'] = 'Autoevaluación';
	$secciones[3][0]['ponderacion'] = .1;
	$secciones[3][0]['tabla'] = 'lic_deporteycultura_p_por_profesores';
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
	$texto_reactivo[1][1] = 'Muestra dominio en el uso de plataformas digitales y recursos tecnológicos diversos para el desarrollo del programa deportivo o cultural';
	$texto_reactivo[1][2] = 'Utiliza herramientas digitales para facilitar la comunicación interpersonal con los estudiantes';
	$texto_reactivo[1][3] = 'Utiliza un lenguaje claro y accesible que favorece el intercambio y el diálogo';
	$texto_reactivo[1][4] = 'Demuestra profundo conocimiento del programa deportivo o cultural que imparte';
	$texto_reactivo[1][5] = 'Señala los aprendizajes esperados del programa que aportan a la formación personal de los estudiantes';
	$texto_reactivo[1][6] = 'Especifica los tiempos de las actividades de aprendizaje del programa';
	$texto_reactivo[1][7] = 'Cumple con los tiempos y actividades de aprendizaje programados para el logro de la competencia';
	$texto_reactivo[1][8] = 'Utiliza diversos recursos audiovisuales y/o materiales que facilitan el aprendizaje significativo';
	$texto_reactivo[1][9] = 'Implementa actividades de aprendizaje que relacionan la teoría con la práctica';
	$texto_reactivo[1][10] = 'Utiliza una evaluación diagnóstica ( inicio de semestre), continua (mitad de semestre) y sumaria ( final de semestre) para conocer el avance del aprendizaje y de la adquisición de la competencia';
	$texto_reactivo[1][11] = 'Realiza retroalimentación permanente de las actividades de aprendizaje señaladas en el programa';
	$texto_reactivo[1][12] = 'Favorece el establecimiento de relaciones interpersonales cercanas basadas en el respeto y la tolerancia';
	$texto_reactivo[1][13] = 'Crea un ambiente de confianza y apertura para externar y resolver las dudas e inquietudes';
	$texto_reactivo[1][14] = 'Muestra empatía y comprensión con las necesidades y dificultades presentadas en el proceso de aprendizaje';
	$texto_reactivo[2][1] = 'Promueve la participación de los alumnos en las actividades deportivas o culturales a su cargo.';
	$texto_reactivo[2][2] = 'Participa en cursos de capacitación propuestos por la Universidad.';
	$texto_reactivo[2][3] = 'Participa en reuniones de trabajo del colegio de profesores.';
	$texto_reactivo[2][4] = 'Entregó su programa de trabajo en tiempo y forma.';
	$texto_reactivo[2][5] = 'Entrega sus reportes cuantitativos y cualitativos en tiempo y forma.';
	$texto_reactivo[2][6] = 'Su programa de trabajo promueve los valores institucionales, favoreciendo la formación integral de los estudiantes.';
	$texto_reactivo[2][7] = 'Muestra amabilidad y cordialidad hacia los alumnos, se preocupa por responder las dudas con interés en el aprendizaje.';
	$texto_reactivo[2][8] = 'Crea un ambiente de confianza y apertura.';
	$texto_reactivo[2][9] = 'Me brinda atención oportuna y respetuosa cuando es necesario o cuando la requiero.';
	$texto_reactivo[2][10] = 'De manera general, la calificación que le otorgo con respecto a su desempeño como profesor es...';
	$texto_reactivo[3][1] = 'Muestro dominio en el uso de plataformas digitales y recursos tecnológicos diversos para el desarrollo del programa deportivo o cultural';
	$texto_reactivo[3][2] = 'Utilizo herramientas digitales para facilitar la comunicación interpersonal con los estudiantes';
	$texto_reactivo[3][3] = 'Utilizo un lenguaje claro y accesible que favorece el intercambio y el diálogo';
	$texto_reactivo[3][4] = 'Demuestro profundo conocimiento de mi área profesional';
	$texto_reactivo[3][5] = 'Señalo los aprendizajes esperados del programa que aportan a la formación personal de los estudiantes';
	$texto_reactivo[3][6] = 'Especifico los tiempos de las actividades de aprendizaje del programa';
	$texto_reactivo[3][7] = 'Cumplo con los tiempos y actividades de aprendizaje programados para el logro de la competencia';
	$texto_reactivo[3][8] = 'Utilizo diversos recursos audiovisuales y materiales que facilitan el aprendizaje significativo';
	$texto_reactivo[3][9] = 'Implemento permanentemente actividades de aprendizaje que relacionan la teoría con la práctica';
	$texto_reactivo[3][10] = 'Utilizo una evaluación diagnóstica (inicio del semestre), continua (mitad de semestre) y sumaria (final de semestre) para conocer el avance del aprendizaje y de la adquisición de la competencia';
	$texto_reactivo[3][11] = 'Realizo retroalimentación permanente de las actividades de aprendizaje señaladas en el programa';
	$texto_reactivo[3][12] = 'Favorezco el establecimiento de relaciones interpersonales cercanas basadas en el respeto y la tolerancia';
	$texto_reactivo[3][13] = 'Creo un ambiente de confianza y apertura para externar y resolver las dudas e inquietudes';
	$texto_reactivo[3][14] = 'Muestro empatía y comprensión con las necesidades y dificultades presentadas en el proceso de aprendizaje';
	$texto_reactivo[3][15] = 'Muestro apertura a la autocrítica y a la retroalimentación de mi quehacer docente';
?>