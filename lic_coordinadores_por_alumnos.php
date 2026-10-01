<?php
	// Establece el tamaño de los distitntos sets de valores que se utilizarán, siempre el set 0 será de valor 1 porque es para las respuestas abiertas
		$tamano_set_valores[0] = 1;
		$tamano_set_valores[1] = 5;
	// Establece el set de valores para las respuestas cerradas y las de opción múltiple
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
	// Establece las secciones de reactivos
		$secciones[1][0]['numeracion'] = ""; // Establece la numeración para esta sección, dejar en blanco para no numerar.
		$secciones[1][0]['texto'] = "<strong>Indicaciones:</strong> Comparte tu percepción respecto al desempeño del coordinador(a) en cada rubro, considerando 5 la puntuación máxima y 1 la mínima.";
		$secciones[1][0]['limitada'] = ""; // Una palabra de una carrera limita a que solo esa carrera pueda acceder, si son varias separar por espacios. Dejar cadena vacía para que accedan todas.
		$secciones[1][1]['inicia'] = 1; // Número de reactivo con el que inicia la subsección
		$secciones[1][1]['termina'] = 7; // Número de reactivo con el que termina la subsección
		$secciones[1][1]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
		$secciones[1][1]['set_valores'] = 1; // En abierta poner 0
		$secciones[1][1]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
		$secciones[1][1]['comentario'] = false; // Indica si después del reactivo debe de haber un espacio para el comentario. Valores true o false.
		$secciones[1][1]['limitada'] = ""; // Una palabra de una carrera limita a que solo esa carrera pueda acceder, si son varias separar por espacios. Dejar cadena vacía para que accedan todas.
		$secciones[1][1]['retroalimentacion'] = "";
		$secciones[1][2]['inicia'] = 8;
		$secciones[1][2]['termina'] = 8;
		$secciones[1][2]['tipo'] = "ABIERTA";
		$secciones[1][2]['set_valores'] = 0;
		$secciones[1][2]['cero'] = "";
		$secciones[1][2]['comentario'] = false;
		$secciones[1][2]['limitada'] = "";
	// Establece el total de secciones y subsecciones así como de reactivos
		$total_secciones = 1;
		$total_subsecciones[1] = 2;
		$total_reactivos = 8;
	// Establece el número de respuestas posibles por renglón
		$valores_por_renglon = 5;//10
	// Establece el texto previo a los reactivos
		$previo['numeracion'] = "";
		$previo['encabezado'] = "";
		$previo['cuerpo'][] = "";
	// Establece el texto de los reactivos
		$texto_reactivo[1] = "Da seguimiento e impulsa mi desempeño académico (asistencia, acreditación de materias, motivación al aprendizaje, etc.).";
		$texto_reactivo[2] = "Organiza y fomenta la participación en eventos que contribuyen a mi formación profesional, y que posicionan a la carrera (proyectos integradores, congresos, seminarios, conferencias, visitas, prácticas profesionales, etc.).";
		$texto_reactivo[3] = "Promueve mi participación en eventos que contribuyen a mi formación personal (pastoral, materias institucionales, servicio social, vida estudiantil, etc.).";
		$texto_reactivo[4] = "Mantiene conmigo un trato cordial, respetuoso, y me genera confianza. ";
		$texto_reactivo[5] = "Comunica clara y oportunamente la información que requiero para mi proceso formativo (calendarios, trámites, reglamentos, servicios institucionales, etc.).";
		$texto_reactivo[6] = "Favorece la resolución de conflictos de manera oportuna e imparcial, cuando éstos entorpecen mi desarrollo académico.";
		$texto_reactivo[7] = "De manera general, qué calificación otorgas al desempeño del coordinador.";
		$texto_reactivo[8] = "Comentarios.";
	// Define el texto del encabezado de la evaluación
	$encabezado[1] = $parametros[2];
	$encabezado[2] = "COORDINACIÓN DE CARRERA";
	// Define la secuencia de campos a registrar en la base de datos (solo 3)
	$secuencia_sql[1] = "carrera, grado, nombre"; // Campos de la tabla adicionales a las meras respuestas
	$secuencia_sql[2] =  $_SESSION['zez_a_carrera'] . "', '" . $_SESSION['zez_a_grado'] . "', '" . $parametros[2];
?>