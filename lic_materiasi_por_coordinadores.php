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
	$secciones[1][0]['texto'] = 'Yo…';
	$secciones[1][0]['limitada'] = ""; // Una palabra de una carrera limita a que solo esa carrera pueda acceder, si son varias separar por espacios. Dejar cadena vacía para que accedan todas.
	$secciones[1][1]['inicia'] = 1;  // Número de reactivo con el que inicia la subsección
	$secciones[1][1]['termina'] = 11;  // Número de reactivo con el que termina la subsección
	$secciones[1][1]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
	$secciones[1][1]['set_valores'] = 1; // En abierta poner 0
	$secciones[1][1]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
	$secciones[1][1]['comentario'] = false; // Indica si después del reactivo debe de haber un espacio para el comentario. Valores true o false.
	$secciones[1][1]['limitada'] = ""; // Una palabra de una carrera limita a que solo esa carrera pueda acceder, si son varias separar por espacios. Dejar cadena vacía para que accedan todas.
	$secciones[2][0]['numeracion'] = "B";
	$secciones[2][0]['texto'] = 'Comentarios';
	$secciones[2][0]['limitada'] = "";
	$secciones[2][1]['inicia'] = 12;
	$secciones[2][1]['termina'] = 12;
	$secciones[2][1]['tipo'] = "ABIERTA";
	$secciones[2][1]['set_valores'] = 0;
	$secciones[2][1]['cero'] = "";
	$secciones[2][1]['comentario'] = false;
	$secciones[2][1]['limitada'] = "";
	// Establece el total de secciones y subsecciones así como de reactivos
	$total_secciones = 2;
	$total_subsecciones[1] = 1;
	$total_subsecciones[2] = 1;
	$total_reactivos = 12;
	// Establece el número de respuestas posibles por renglón
	$valores_por_renglon = 10;
	// Establece el texto previo a los reactivos
	$previo['numeracion'] = "";
	$previo['encabezado'] = "Indicaciones";
	$previo['cuerpo'][] = "Evalúa del 1 al 10, donde 1 es la calificación mínima y 10 la máxima, tu desempeñado como coordinador" . $parametros[2] . " de materias institucionales, con relación a cada uno de los siguientes aspectos:";
	// Establece el texto de los reactivos
	$texto_reactivo[1] = "Apoyé en la elaboración colegiada del Instrumento de Planeación del Aprendizaje por Competencias de las materias de los distintos ejes institucionales.";
	$texto_reactivo[2] = "Di a conocer en tiempo a los docentes los horarios, salones, y la calendarización académica del semestre.";
	$texto_reactivo[3] = "Convoco a reuniones de trabajo a los docentes de los distintos ejes institucionales.";
	$texto_reactivo[4] = "Doy seguimiento a la implementación de la Planeación del Aprendizaje por Competencias.";
	$texto_reactivo[5] = "Soy amable y cordial en el trato con los docentes.";
	$texto_reactivo[6] = "Escucho y doy seguimiento a las peticiones y situaciones que los docentes plantean con respecto a su materia y al alumnado.";
	$texto_reactivo[7] = "Vivo los valores maristas: solidaridad, amor al trabajo, espíritu de familia y/o apertura a los demás.";
	$texto_reactivo[8] = "Demuestro experiencia y dominio de su puesto como coordinador" . $parametros[2] . " de materias institucionales.";
	$texto_reactivo[9] = "Favorezco el adecuado funcionamiento de las materias institucionales.";
	$texto_reactivo[10] = "Medio y/o doy solución a los conflictos que surgen entre el alumnado y los docentes de materias institucionales.";
	$texto_reactivo[11] = "De manera general, la calificación que me otorgo como coordinador" . $parametros[2] . " es…";
	$texto_reactivo[12] = "Comentarios en relación a mi desempeño como coordinador" . $parametros[2] . " de materias institucionales.";
	// Define el texto del encabezado de la evaluación
	$encabezado[1] = 'AUTOEVALUACIÓN';
	$encabezado[2] = "Coordinación de Materias Institucionales";
	// Define la secuencia de campos a registrar en la base de datos (solo 3)
	$secuencia_sql[1] = ""; // Campos de la tabla adicionales a las meras respuestas
	$secuencia_sql[2] =  "";
?>