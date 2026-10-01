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
		$secciones[1][0]['texto'] = "<strong>Indicaciones:</strong> Comparte tu percepción respecto al desempeño del coordinador(a) en cada rubro, considerando 5 la puntuación máxima y 1 la mínima";
		$secciones[1][0]['limitada'] = ""; // Una palabra de una carrera limita a que solo esa carrera pueda acceder, si son varias separar por espacios. Dejar cadena vacía para que accedan todas.
		$secciones[1][1]['inicia'] = 1; // Número de reactivo con el que inicia la subsección
		$secciones[1][1]['termina'] = 10; // Número de reactivo con el que termina la subsección
		$secciones[1][1]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
		$secciones[1][1]['set_valores'] = 1; // En abierta poner 0
		$secciones[1][1]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
		$secciones[1][1]['comentario'] = false; // Indica si después del reactivo debe de haber un espacio para el comentario. Valores true o false.
		$secciones[1][1]['limitada'] = ""; // Una palabra de una carrera limita a que solo esa carrera pueda acceder, si son varias separar por espacios. Dejar cadena vacía para que accedan todas.
		$secciones[1][1]['retroalimentacion'] = "";
		$secciones[1][2]['inicia'] = 11;
		$secciones[1][2]['termina'] = 11;
		$secciones[1][2]['tipo'] = "ABIERTA";
		$secciones[1][2]['set_valores'] = 0;
		$secciones[1][2]['cero'] = "";
		$secciones[1][2]['comentario'] = false;
		$secciones[1][2]['limitada'] = "";
	// Establece el total de secciones y subsecciones así como de reactivos
		$total_secciones = 1;
		$total_subsecciones[1] = 2;
		$total_reactivos = 11;
	// Establece el número de respuestas posibles por renglón
		$valores_por_renglon = 5;//10
	// Establece el texto previo a los reactivos
		$previo['numeracion'] = "";
		$previo['encabezado'] = "";
		$previo['cuerpo'][] = "";
	// Establece el texto de los reactivos
	$texto_reactivo[1] = "Supervisa y acompaña la elaboración e implementación de la planeación didáctica.";
	$texto_reactivo[2] = "Da seguimiento al trabajo de los docentes a su cargo y establece lineamientos para asegurar la calidad educativa.";
	$texto_reactivo[3] = "Organiza y fomenta eventos que promueven y posicionan la carrera a su cargo (congresos, seminarios, conferencias, visitas, etc.).";
	$texto_reactivo[4] = "Mantiene un trato cordial, respetuoso y de apertura al diálogo.";
	$texto_reactivo[5] = "Resuelve conflictos o situaciones emergentes de manera oportuna e imparcial.";
	$texto_reactivo[6] = "Proporciona retroalimentación oportuna sobre los resultados de la evaluación del desempeño docente e impulsa acciones de mejora continua.";
	$texto_reactivo[7] = "Convoca y realiza reuniones de trabajo colegiado eficientes y productivas.";
	$texto_reactivo[8] = "Promueve la formación y actualización de la plantilla docente. ";
	$texto_reactivo[9] = "Se actualiza y conoce las necesidades emergentes del campo profesional de la carrera a su cargo. ";
	$texto_reactivo[10] = "De manera general, la calificación que le otorgo con respecto a su desempeño como Coordinador es...";
	$texto_reactivo[11] = "Comentarios:";
	// Define el texto del encabezado de la evaluación
	$encabezado[1] = $parametros[3];
	$encabezado[2] = "COORDINACION DE " . $parametros[2];
	// Define la secuencia de campos a registrar en la base de datos (solo 3)
	$secuencia_sql[1] = "carrera, nombre"; // Campos de la tabla adicionales a las meras respuestas
	$secuencia_sql[2] =  $parametros[2] . "', '" . $parametros[3];
?>