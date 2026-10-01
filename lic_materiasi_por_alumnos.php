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
	$secciones[1][0]['texto'] = "<strong>Indicaciones:</strong> Comparte tu percepción respecto al desempeño de la coordinadora en cada rubro, considerando 5 la puntuación máxima y 1 la mínima.";
	$secciones[1][0]['limitada'] = ""; // Una palabra de una carrera limita a que solo esa carrera pueda acceder, si son varias separar por espacios. Dejar cadena vacía para que accedan todas.
	$secciones[1][1]['inicia'] = 1;  // Número de reactivo con el que inicia la subsección
	$secciones[1][1]['termina'] = 6;  // Número de reactivo con el que termina la subsección
	$secciones[1][1]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
	$secciones[1][1]['set_valores'] = 1; // En abierta poner 0
	$secciones[1][1]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
	$secciones[1][1]['comentario'] = false; // Indica si después del reactivo debe de haber un espacio para el comentario. Valores true o false.
	$secciones[1][1]['limitada'] = ""; // Una palabra de una carrera limita a que solo esa carrera pueda acceder, si son varias separar por espacios. Dejar cadena vacía para que accedan todas.

	$secciones[1][1]['retroalimentacion'] = "";
	$secciones[1][2]['inicia'] = 7;
	$secciones[1][2]['termina'] = 7;
	$secciones[1][2]['tipo'] = "ABIERTA";
	$secciones[1][2]['set_valores'] = 0;
	$secciones[1][2]['cero'] = "";
	$secciones[1][2]['comentario'] = false;
	$secciones[1][2]['limitada'] = "";
	// Establece el total de secciones y subsecciones así como de reactivos
	$total_secciones = 1;
	$total_subsecciones[1] = 2;
	$total_reactivos = 7;
	// Establece el número de respuestas posibles por renglón
	$valores_por_renglon = 5;
	// Establece el texto previo a los reactivos
	$previo['numeracion'] = "";
	$previo['encabezado'] = "";
	$previo['cuerpo'][] = "";
	// Establece el texto de los reactivos
	$texto_reactivo[1] = "Es amable y cordial en el trato con los estudiantes.";
	$texto_reactivo[2] = "Acompaña, da seguimiento y resuelve las situaciones que los estudiantes plantean en lo referido a las materias institucionales.";
	$texto_reactivo[3] = "Da atención personal y trato respetuoso al alumnado cuando requieren su apoyo.";
	$texto_reactivo[4] = "Demuestra experiencia y dominio de su puesto como coordinador" . $parametros[3] . " de materias institucionales.";
	$texto_reactivo[5] = "Media y/o da solución a los conflictos que surgen entre el alumnado y los docentes de materias institucionales";
	$texto_reactivo[6] = "De manera general, la calificación que le otorgo a" . $parametros[5] . " es…";
	$texto_reactivo[7] = "Comentarios";
	// Define el texto del encabezado de la evaluación
	$encabezado[1] = $parametros[2];
	$encabezado[2] = "COORDINACIÓN DE MATERIAS INSTITUCIONALES";
	// Define la secuencia de campos a registrar en la base de datos (solo 3)
	$secuencia_sql[1] = "carrera, grado"; // Campos de la tabla adicionales a las meras respuestas
	$secuencia_sql[2] =  $_SESSION['zez_a_carrera'] . "', '" . $_SESSION['zez_a_grado'];
?>