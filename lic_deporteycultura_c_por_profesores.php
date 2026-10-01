<?php
	// Establece el tamaño de los distitntos sets de valores que se utilizarán, siempre el set 0 será de valor 1 porque es para las respuestas abiertas
	$tamano_set_valores[0] = 1;
	$tamano_set_valores[1] = 10;
	// Establece el set de valores para las respuestas cerradas y las de opción múltiple
	$set_valores[1][0]['texto'] = "No aplica";
	$set_valores[1][0]['valor'] = 0;
	$set_valores[1][1]['texto'] = "1";
	$set_valores[1][1]['valor'] = 1;
	$set_valores[1][2]['texto'] = "2";
	$set_valores[1][2]['valor'] = 2;
	$set_valores[1][3]['texto'] = "3";
	$set_valores[1][3]['valor'] = 3;
	$set_valores[1][4]['texto'] = "4";
	$set_valores[1][4]['valor'] = 4;
	$set_valores[1][5]['texto'] = "5";
	$set_valores[1][5]['valor'] = 5;
	$set_valores[1][6]['texto'] = "6";
	$set_valores[1][6]['valor'] = 6;
	$set_valores[1][7]['texto'] = "7";
	$set_valores[1][7]['valor'] = 7;
	$set_valores[1][8]['texto'] = "8";
	$set_valores[1][8]['valor'] = 8;
	$set_valores[1][9]['texto'] = "9";
	$set_valores[1][9]['valor'] = 9;
	$set_valores[1][10]['texto'] = "10";
	$set_valores[1][10]['valor'] = 10;
	// Establece las secciones de reactivos
	$secciones[1][0]['numeracion'] = "A"; // Establece la numeración para esta sección, dejar en blanco para no numerar.
	$secciones[1][0]['texto'] = $parametros[4];
	$secciones[1][0]['limitada'] = ""; // Una palabra de una carrera limita a que solo esa carrera pueda acceder, si son varias separar por espacios. Dejar cadena vacía para que accedan todas.
	$secciones[1][1]['inicia'] = 1;  // Número de reactivo con el que inicia la subsección
	$secciones[1][1]['termina'] = 9;  // Número de reactivo con el que termina la subsección
	$secciones[1][1]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
	$secciones[1][1]['set_valores'] = 1; // En abierta poner 0
	$secciones[1][1]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
	$secciones[1][1]['comentario'] = false; // Indica si después del reactivo debe de haber un espacio para el comentario. Valores true o false.
	$secciones[1][1]['limitada'] = ""; // Una palabra de una carrera limita a que solo esa carrera pueda acceder, si son varias separar por espacios. Dejar cadena vacía para que accedan todas.
	// Establece el total de secciones y subsecciones así como de reactivos
	$total_secciones = 1;
	$total_subsecciones[1] = 1;
	$total_reactivos = 9;
	// Establece el número de respuestas posibles por renglón
	$valores_por_renglon = 10;
	// Establece el texto previo a los reactivos
	$previo['numeracion'] = "";
	$previo['encabezado'] = "Indicaciones";
	$previo['cuerpo'][] = "Evalúa del 1 al 10, donde 1 es la calificación mínima y 10 la máxima, el desempeño de" . $parametros[5] . ", con relación a cada uno de los siguientes aspectos:";
	// Establece el texto de los reactivos
	$texto_reactivo[1] = 'Promueve la participación de los docentes de las actividades deportivas y culturales a su cargo en talleres y cursos de capacitación propuestos por la Universidad.';
	$texto_reactivo[2] = 'Supervisa el trabajo de los profesores de los talleres deportivos y culturales para asegurar la calidad en la formación integral de los estudiantes.';
	$texto_reactivo[3] = 'Convoca a reuniones de trabajo a los colegios de profesores.';
	$texto_reactivo[4] = 'Promueve la revisión de los programas en las reuniones de trabajo de los colegios de profesores.';
	$texto_reactivo[5] = 'Planea los procesos propios de las diferentes áreas a su cargo en las fechas marcadas por la Institución.';
	$texto_reactivo[6] = 'Entregó en tiempo la calendarización de las actividades previstas del semestre.';
	$texto_reactivo[7] = 'Realiza las actividades programadas en el calendario, respetando las fechas dadas.';
	$texto_reactivo[8] = 'Recibo atención oportuna y respetuosa cuando es necesario o cuando la requiero.';
	$texto_reactivo[9] = 'De manera general, la calificación que le otorgo con respecto a su desempeño como Coordinador'.$parametros[3].' es...';
	// Define el texto del encabezado de la evaluación
	$encabezado[1] = $parametros[2];
	$encabezado[2] = "COORDINACIÓN DE DEPORTE Y CULTURA";
	// Define la secuencia de campos a registrar en la base de datos (solo 3)
	$secuencia_sql[1] = ''; // Campos de la tabla adicionales a las meras respuestas
	$secuencia_sql[2] =  '';
?>