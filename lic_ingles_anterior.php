<?php
	// Establece el tamaño de los distitntos sets de valores que se utilizarán, siempre el set 0 será de valor 1 porque es para las respuestas abiertas
	$tamano_set_valores[0] = 1;
	$tamano_set_valores[1] = 10;
	// Establece el set de valores para las respuestas cerradas y las de opción múltiple
	$set_valores[1][1]['texto'] = "0";
	$set_valores[1][1]['valor'] = 0;
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
	$secciones[1][2]['inicia'] = 10;
	$secciones[1][2]['termina'] = 10;
	$secciones[1][2]['tipo'] = "ABIERTA";
	$secciones[1][2]['set_valores'] = 0;
	$secciones[1][2]['cero'] = "";
	$secciones[1][2]['comentario'] = false;
	$secciones[1][2]['limitada'] = "";
	// Establece el total de secciones y subsecciones así como de reactivos
	$total_secciones = 1;
	$total_subsecciones[1] = 2;
	$total_reactivos = 10;
	// Establece el número de respuestas posibles por renglón
	$valores_por_renglon = 10;
	// Establece el texto previo a los reactivos
	$previo['numeracion'] = "";
	$previo['encabezado'] = "Indicaciones";
	$previo['cuerpo'][] = "Evalúa el tus clases de inglés en cada uno de los siguientes aspectos, donde 1 es la calificación mínima y 10 la máxima.";
	// Establece el texto de los reactivos
	$texto_reactivo[1] = "El nivel de exigencia del profesor(a) detona el esfuerzo de los estudiantes para el aprendizaje del idioma inglés.";
	$texto_reactivo[2] = "El profesor(a) habla en inglés durante la clase para favorecer el aprendizaje del idioma.";
	$texto_reactivo[3] = "La plataforma de trabajo en internet fue una herramienta utilizada por el profesor (a) de inglés.";
	$texto_reactivo[4] = "El profesor es puntual en el salón de clases para iniciar la clase.";
	$texto_reactivo[5] = "El trabajo realizado en clase y las tareas son retroalimentados periódicamente por el profesor de inglés.";
	$texto_reactivo[6] = "Es evidente que las clases de inglés están previamente planeadas por el profesor (a).";
	$texto_reactivo[7] = "El libro de texto utilizado es el adecuado para el aprendizaje del idioma inglés.";
	$texto_reactivo[8] = "La forma de trabajo propuesta por el profesor (a) de este semestre en el estudio del idioma inglés me hizo sentir acompañado en el aprendizaje de esta lengua.";
	$texto_reactivo[9] = "De manera global califico la enseñanza del idioma inglés de este semestre como una propuesta adecuada para mi aprendizaje.";
	$texto_reactivo[10] = "Escribe comentarios sobre la enseñanza del idioma que ayuden a mejorar este servicio educativo:";
	// Define el texto del encabezado de la evaluación
	$encabezado[1] = "Evaluación de las clases de Inglés";
	$encabezado[2] = "En las instalaciones de la UMG";
	// Define la secuencia de campos a registrar en la base de datos (solo 3)
	$secuencia_sql[1] = "carrera, grado, sexo, situacion"; // Campos de la tabla adicionales a las meras respuestas
	$secuencia_sql[2] =  $_SESSION['zez_a_carrera'] . "', '" . $_SESSION['zez_a_grado'] . "', '" . $_SESSION['zez_a_sexo'] . "', '" . $_SESSION['zez_a_situacion'];
?>