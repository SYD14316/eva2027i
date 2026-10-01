<?php
	// Establece el tamaño de los distitntos sets de valores que se utilizarán, siempre el set 0 será de valor 1 porque es para las respuestas abiertas

	$tamano_set_valores[0] = 1;

	$tamano_set_valores[1] = 5;

	// Establece el set de valores para las respuestas cerradas y las de opción múltiple

	$set_valores[1][0]['texto'] = "No aplica";
	$set_valores[1][0]['valor'] = 0;

	$set_valores[1][1]['texto'] = "Totalmente en desacuerdo";
	$set_valores[1][1]['valor'] = 1;

	$set_valores[1][2]['texto'] = "En Desacuerdo";
	$set_valores[1][2]['valor'] = 2;

	$set_valores[1][3]['texto'] = "Ni de acuerdo ni en desacuerdo";
	$set_valores[1][3]['valor'] = 3;

	$set_valores[1][4]['texto'] = "De acuerdo";
	$set_valores[1][4]['valor'] = 4;

	$set_valores[1][5]['texto'] = "Totalmente de acuerdo";
	$set_valores[1][5]['valor'] = 5;

	// Establece las secciones de reactivos
	$secciones[1][0]['numeracion'] = "";  // Establece la numeración para esta sección, dejar en blanco para no numerar.

	$secciones[1][0]['texto'] = "Evaluación";

	$secciones[1][0]['limitada'] = ""; // Una palabra de una carrera limita a que solo esa carrera pueda acceder, si son varias separar por espacios. Dejar cadena vacía para que accedan todas.

	$secciones[1][1]['inicia'] = 1; // Número de reactivo con el que inicia la subsección

	$secciones[1][1]['termina'] = 9; // Número de reactivo con el que termina la subsección

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
	$valores_por_renglon = 5;
	// Establece el texto previo a los reactivos
	$previo['numeracion'] = "";

	$previo['encabezado'] = "Indicaciones";

	$previo['cuerpo'][] = "Evalúa el tus clases de inglés en cada uno de los siguientes aspectos, donde 1 es la calificación mínima y 10 la máxima.";
	// Establece el texto de los reactivos
	$texto_reactivo[1] = "El horario y días de la clase de lengua extranjera (inglés/alemán/francés) es el adecuado.";

	$texto_reactivo[2] = "El/la docente de la clase de lengua extranjera (inglés/alemán/francés) cumple con el horario correspondiente de la clase.";

	$texto_reactivo[3] = "El/la docente de la clase de lengua extranjera (inglés/alemán/francés) se dirige con respeto a las y los estudiantes.";

	$texto_reactivo[4] = "La clase de lengua extranjera (inglés/alemán/francés) incrementa en cada nivel mi aprendizaje y uso de habilidades en el idioma.";

	$texto_reactivo[5] = "El/la docente de la clase de lengua extranjera (inglés/alemán/francés) aplica herramientas tecnológicas y el libro Y utiliza el libro en el periodo que dura el nivel durante el nivel.";

	$texto_reactivo[6] = "Los contenidos de la clase de lengua extranjera (inglés/alemán/francés) se relaciona con la relevancia y actualidad de la información.";

	$texto_reactivo[7] = "El estudiante cuenta con retroalimentación oportuna de dudas y aprovechamiento del conocimiento por parte del/la docente de la clase de lengua extranjera (inglés/alemán/francés).";

	$texto_reactivo[8] = "La rúbrica de calificación aplicada por parte del/la docente de la clase de lengua extranjera (inglés/alemán/francés) es congruente con el aprendizaje de los contenidos.";

	$texto_reactivo[9] = "El seguimiento de las solicitudes de reubicación de nivel, mejora en el servicio docente, y gestión del programa es óptimo desde la Coordinación Académica de CELE y la Oficina de Internacionalización y Movilidad Académica.";

	// Define el texto del encabezado de la evaluación

	$encabezado[1] = "Programa de aprendizaje de lengua extranjera";

	$encabezado[2] = "En las instalaciones de la UMG";

	// Define la secuencia de campos a registrar en la base de datos (solo 3)

	$secuencia_sql[1] = "carrera, grado, sexo, situacion"; // Campos de la tabla adicionales a las meras respuestas

	$secuencia_sql[2] =  $_SESSION['zez_a_carrera'] . "', '" . $_SESSION['zez_a_grado'] . "', '" . $_SESSION['zez_a_sexo'] . "', '" . $_SESSION['zez_a_situacion'];

?>