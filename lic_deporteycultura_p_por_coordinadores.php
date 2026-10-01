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
	$secciones[1][1]['termina'] = 10;  // Número de reactivo con el que termina la subsección
	$secciones[1][1]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
	$secciones[1][1]['set_valores'] = 1; // En abierta poner 0
	$secciones[1][1]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
	$secciones[1][1]['comentario'] = false; // Indica si después del reactivo debe de haber un espacio para el comentario. Valores true o false.
	$secciones[1][1]['limitada'] = ""; // Una palabra de una carrera limita a que solo esa carrera pueda acceder, si son varias separar por espacios. Dejar cadena vacía para que accedan todas.
	// Establece el total de secciones y subsecciones así como de reactivos
	$total_secciones = 1;
	$total_subsecciones[1] = 1;
	$total_reactivos = 10;
	// Establece el número de respuestas posibles por renglón
	$valores_por_renglon = 10;
	// Establece el texto previo a los reactivos
	$previo['numeracion'] = '';
	$previo['encabezado'] = 'Indicaciones';
	$previo['cuerpo'][] = 'Evalúa del 1 al 10, donde 1 es la calificación mínima y 10 la máxima, el desempeño de' . $parametros[5] . ', con relación a cada uno de los siguientes aspectos:';
	// Establece el texto de los reactivos
	$texto_reactivo[1] = 'Promueve la participación de los alumnos en las actividades deportivas o culturales a su cargo.';
	$texto_reactivo[2] = 'Participa en cursos de capacitación propuestos por la Universidad.';
	$texto_reactivo[3] = 'Participa en reuniones de trabajo del colegio de profesores.';
	$texto_reactivo[4] = 'Entregó su programa de trabajo en tiempo y forma.';
	$texto_reactivo[5] = 'Entrega sus reportes cuantitativos y cualitativos en tiempo y forma.';
	$texto_reactivo[6] = 'Su programa de trabajo promueve los valores institucionales, favoreciendo la formación integral de los estudiantes.';
	$texto_reactivo[7] = 'Muestra amabilidad y cordialidad hacia los alumnos, se preocupa por responder las dudas con interés en el aprendizaje.';
	$texto_reactivo[8] = 'Crea un ambiente de confianza y apertura.';
	$texto_reactivo[9] = 'Me brinda atención oportuna y respetuosa cuando es necesario o cuando la requiero.';
	$texto_reactivo[10] = 'De manera general, la calificación que le otorgo con respecto a su desempeño como profesor' . $parametros[6] . ' es...';
	// Define el texto del encabezado de la evaluación
	$encabezado[1] = $parametros[2];
	$encabezado[2] = 'Profesor' . $parametros[6];
	// Define la secuencia de campos a registrar en la base de datos (solo 3)
	$secuencia_sql[1] = "nombre, sexo"; // Campos de la tabla adicionales a las meras respuestas
	$secuencia_sql[2] =  $parametros[2] . "', '" . $parametros[3];
?>