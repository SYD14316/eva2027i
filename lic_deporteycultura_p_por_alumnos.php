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
	$secciones[1][0]['texto'] = $parametros[5]; // El profesor… ó La profesora…
	$secciones[1][0]['limitada'] = ""; // Una palabra de una carrera limita a que solo esa carrera pueda acceder, si son varias separar por espacios. Dejar cadena vacía para que accedan todas.
	$secciones[1][1]['inicia'] = 1;  // Número de reactivo con el que inicia la subsección
	$secciones[1][1]['termina'] = 14;  // Número de reactivo con el que termina la subsección
	$secciones[1][1]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
	$secciones[1][1]['set_valores'] = 1; // En abierta poner 0
	$secciones[1][1]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
	$secciones[1][1]['comentario'] = false; // Indica si después del reactivo debe de haber un espacio para el comentario. Valores true o false.
	$secciones[1][1]['limitada'] = ""; // Una palabra de una carrera limita a que solo esa carrera pueda acceder, si son varias separar por espacios. Dejar cadena vacía para que accedan todas.
	$secciones[2][0]['numeracion'] = "B";
	$secciones[2][0]['texto'] = 'Comentarios';
	$secciones[2][0]['limitada'] = "";
	$secciones[2][1]['inicia'] = 15;
	$secciones[2][1]['termina'] = 15;
	$secciones[2][1]['tipo'] = "ABIERTA";
	$secciones[2][1]['set_valores'] = 0;
	$secciones[2][1]['cero'] = "";
	$secciones[2][1]['comentario'] = false;
	$secciones[2][1]['limitada'] = "";
	// Establece el total de secciones y subsecciones así como de reactivos
	$total_secciones = 2;
	$total_subsecciones[1] = 1;
	$total_subsecciones[2] = 1;
	$total_reactivos = 15;
	// Establece el número de respuestas posibles por renglón
	$valores_por_renglon = 10;
	// Establece el texto previo a los reactivos
	$previo['numeracion'] = "";
	$previo['encabezado'] = "Indicaciones";
	$previo['cuerpo'][] = "Evalúa del 1 al 10, donde 1 es la calificación mínima y 10 la máxima, el desempeñado de" . $parametros[6] . ", con relación a cada uno de los siguientes aspectos:";
	// Establece el texto de los reactivos
	$texto_reactivo[1] = 'Muestra dominio en el uso de plataformas digitales y recursos tecnológicos diversos para el desarrollo del programa deportivo o cultural';
	$texto_reactivo[2] = 'Utiliza herramientas digitales para facilitar la comunicación interpersonal con los estudiantes';
	$texto_reactivo[3] = 'Utiliza un lenguaje claro y accesible que favorece el intercambio y el diálogo';
	$texto_reactivo[4] = 'Demuestra profundo conocimiento del programa deportivo o cultural que imparte';
	$texto_reactivo[5] = 'Señala los aprendizajes esperados del programa que aportan a la formación personal de los estudiantes';
	$texto_reactivo[6] = 'Especifica los tiempos de las actividades de aprendizaje del programa';
	$texto_reactivo[7] = 'Cumple con los tiempos y actividades de aprendizaje programados para el logro de la competencia';
	$texto_reactivo[8] = 'Utiliza diversos recursos audiovisuales y/o materiales que facilitan el aprendizaje significativo';
	$texto_reactivo[9] = 'Implementa actividades de aprendizaje que relacionan la teoría con la práctica';
	$texto_reactivo[10] = 'Utiliza una evaluación diagnóstica ( inicio de semestre), continua (mitad de semestre) y sumaria ( final de semestre) para conocer el avance del aprendizaje y de la adquisición de la competencia';
	$texto_reactivo[11] = 'Realiza retroalimentación permanente de las actividades de aprendizaje señaladas en el programa';
	$texto_reactivo[12] = 'Favorece el establecimiento de relaciones interpersonales cercanas basadas en el respeto y la tolerancia';
	$texto_reactivo[13] = 'Crea un ambiente de confianza y apertura para externar y resolver las dudas e inquietudes';
	$texto_reactivo[14] = 'Muestra empatía y comprensión con las necesidades y dificultades presentadas en el proceso de aprendizaje';
	$texto_reactivo[15] = 'Comentarios y sugerencias orientadas a la mejora de su quehacer docente';
	// Define el texto del encabezado de la evaluación
	$encabezado[1] = $parametros[2]; // Taller
	$encabezado[2] = $parametros[3]; // Nombre del profesor
	// Define la secuencia de campos a registrar en la base de datos
	// Nombre del profesor, Taller, Sexo del profesor, Carrera del alumno, Grado del alumno
	$secuencia_sql[1] = "nombre, taller, sexo, carrera, grado"; // Campos de la tabla adicionales a las meras respuestas
	$secuencia_sql[2] =  $parametros[3] . "', '" . $parametros[2] . "', '" . $parametros[4] . "', '" . $_SESSION['zez_a_carrera'] . "', '" . $_SESSION['zez_a_grado'];
?>