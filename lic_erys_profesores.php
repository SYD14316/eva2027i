<?php
	// Establece el tamaño de los distitntos sets de valores que se utilizarán, siempre el set 0 será de valor 1 porque es para las respuestas abiertas
	$tamano_set_valores[0] = 1;
	$tamano_set_valores[1] = 10;
	$tamano_set_valores[2] = 2;
	
	// Establece el set de valores para las respuestas cerradas y las de opción múltiple
	$set_valores[1][0]['texto'] = "NA";
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
	$set_valores[1][9]['valor'] = 8;
	$set_valores[1][10]['texto'] = "10";
	$set_valores[1][10]['valor'] = 10;

	$set_valores[2][0]['texto'] = "NA";
	$set_valores[2][0]['valor'] = 0;
	$set_valores[2][1]['texto'] = "Sí";
	$set_valores[2][1]['valor'] = 10;
	$set_valores[2][2]['texto'] = "No";
	$set_valores[2][2]['valor'] = 0;
	
	// Establece el multiplicador
	$multiplicador = 1; // Valor máximo entre Calificación otorgada máxima
	
	// Establece las secciones de reactivos
	// Seccion A
		$secciones[1][0]['numeracion'] = ""; // Establece la numeración para esta sección, dejar en blanco para no numerar.
		$secciones[1][0]['texto'] = "¿Cómo encuentras la limpieza de los siguientes espacios?"; // Texto de la sección
		$secciones[1][0]['limitada'] = ""; // Una palabra de una carrera limita a que solo esa carrera pueda acceder, si son varias separar por espacios. Dejar cadena vacía para que accedan todas.

		$secciones[1][1]['inicia'] = 1; // Número de reactivo con el que inicia la subsección
		$secciones[1][1]['termina'] = 10; // Número de reactivo con el que termina la subsección
		$secciones[1][1]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
		$secciones[1][1]['set_valores'] = 1; // En abierta poner 0
		$secciones[1][1]['cero'] = "NA"; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
		$secciones[1][1]['comentario'] = false; // Indica si después del reactivo debe de haber un espacio para el comentario. Valores true o false.
		$secciones[1][1]['limitada'] = ""; // Una palabra de una carrera limita a que solo esa carrera pueda acceder, si son varias separar por espacios. Dejar cadena vacía para que accedan todas.
	// Seccion B
		$secciones[2][0]['numeracion'] = "";
		$secciones[2][0]['texto'] = "¿Cómo calificas la calidad de las instalaciones y espacios de las siguientes áreas?";
		$secciones[2][0]['limitada'] = "";

		$secciones[2][1]['inicia'] = 11;
		$secciones[2][1]['termina'] = 20;
		$secciones[2][1]['tipo'] = "CERRADA";
		$secciones[2][1]['set_valores'] = 1;
		$secciones[2][1]['cero'] = "NA";
		$secciones[2][1]['comentario'] = false;
		$secciones[2][1]['limitada'] = "";
		
		$secciones[2][2]['inicia'] = 21;
		$secciones[2][2]['termina'] = 21;
		$secciones[2][2]['tipo'] = "CERRADA";
		$secciones[2][2]['set_valores'] = 1;
		$secciones[2][2]['cero'] = "";
		$secciones[2][2]['comentario'] = false;
		$secciones[2][2]['limitada'] = "";
	// Seccion C
		$secciones[3][0]['numeracion'] = "";
		$secciones[3][0]['texto'] = "¿Qué nuevo equipamiento requiere tu carrera para adaptarse a las necesidades del mercado laboral?";
		$secciones[3][0]['limitada'] = "";
	
		$secciones[3][1]['inicia'] = 22;
		$secciones[3][1]['termina'] = 22;
		$secciones[3][1]['tipo'] = "ABIERTA";
		$secciones[3][1]['set_valores'] = 0;
		$secciones[3][1]['cero'] = "";
		$secciones[3][1]['comentario'] = false;
		$secciones[3][1]['limitada'] = "";
	// Seccion D
		$secciones[4][0]['numeracion'] = "";
		$secciones[4][0]['texto'] = "¿El trato de las personas que te atendieron en estas áreas fue amable y cordial?";
		$secciones[4][0]['limitada'] = "";
	
		$secciones[4][1]['inicia'] = 23;
		$secciones[4][1]['termina'] = 34;
		$secciones[4][1]['tipo'] = "CERRADA";
		$secciones[4][1]['set_valores'] = 1;
		$secciones[4][1]['cero'] = "NA";
		$secciones[4][1]['comentario'] = false;
		$secciones[4][1]['limitada'] = "";
	
		$secciones[4][2]['inicia'] = 35;
		$secciones[4][2]['termina'] = 35;
		$secciones[4][2]['tipo'] = "CERRADA";
		$secciones[4][2]['set_valores'] = 1;
		$secciones[4][2]['cero'] = "NA";
		$secciones[4][2]['comentario'] = false;
		$secciones[4][2]['limitada'] = "PROFESORES";
	// Seccion E
		$secciones[5][0]['numeracion'] = "";
		$secciones[5][0]['texto'] = "La persona que te atendió, ¿contribuyó a la solución de tus dudas o problemáticas?";
		$secciones[5][0]['limitada'] = "";
	
		$secciones[5][1]['inicia'] = 36;
		$secciones[5][1]['termina'] = 47;
		$secciones[5][1]['tipo'] = "CERRADA";
		$secciones[5][1]['set_valores'] = 1;
		$secciones[5][1]['cero'] = "NA";
		$secciones[5][1]['comentario'] = false;
		$secciones[5][1]['limitada'] = "";	
	
		$secciones[5][2]['inicia'] = 48;
		$secciones[5][2]['termina'] = 48;
		$secciones[5][2]['tipo'] = "CERRADA";
		$secciones[5][2]['set_valores'] = 1;
		$secciones[5][2]['cero'] = "NA";
		$secciones[5][2]['comentario'] = false;
		$secciones[5][2]['limitada'] = "PROFESORES";
	
		$secciones[5][3]['inicia'] = 49;
		$secciones[5][3]['termina'] = 49;
		$secciones[5][3]['tipo'] = "ABIERTA";
		$secciones[5][3]['set_valores'] = 0;
		$secciones[5][3]['cero'] = "";
		$secciones[5][3]['comentario'] = false;
		$secciones[5][3]['limitada'] = "";
	// Seccion F
		$secciones[6][0]['numeracion'] = "";
		$secciones[6][0]['texto'] = "Biblioteca (Centro de Servicios Informativos)";
		$secciones[6][0]['limitada'] = "";
	
		$secciones[6][1]['inicia'] = 50;
		$secciones[6][1]['termina'] = 52;
		$secciones[6][1]['tipo'] = "CERRADA";
		$secciones[6][1]['set_valores'] = 1;
		$secciones[6][1]['cero'] = "NA";
		$secciones[6][1]['comentario'] = false;
		$secciones[6][1]['limitada'] = "";

		$secciones[6][1]['inicia'] = 53;
		$secciones[6][1]['termina'] = 53;
		$secciones[6][1]['tipo'] = "CERRADA";
		$secciones[6][1]['set_valores'] = 1;
		$secciones[6][1]['cero'] = "NA";
		$secciones[6][1]['comentario'] = false;
		$secciones[6][1]['limitada'] = "TODAS";
	
		$secciones[6][2]['inicia'] = 54;
		$secciones[6][2]['termina'] = 54;
		$secciones[6][2]['tipo'] = "ABIERTA";
		$secciones[6][2]['set_valores'] = 0;
		$secciones[6][2]['cero'] = "";
		$secciones[6][2]['comentario'] = false;
		$secciones[6][2]['limitada'] = "";
	// Seccion G
		$secciones[7][0]['numeracion'] = "";
		$secciones[7][0]['texto'] = "Plataforma TEAMS";
		$secciones[7][0]['limitada'] = "";
	
		$secciones[7][1]['inicia'] = 55;
		$secciones[7][1]['termina'] = 55;
		$secciones[7][1]['tipo'] = "CERRADA";
		$secciones[7][1]['set_valores'] = 2;
		$secciones[7][1]['cero'] = "";
		$secciones[7][1]['comentario'] = false;
		$secciones[7][1]['limitada'] = "";
	
		$secciones[7][2]['inicia'] = 56;
		$secciones[7][2]['termina'] = 59;
		$secciones[7][2]['tipo'] = "CERRADA";
		$secciones[7][2]['set_valores'] = 1;
		$secciones[7][2]['cero'] = "NA";
		$secciones[7][2]['comentario'] = false;
		$secciones[7][2]['limitada'] = "";
	
		$secciones[7][3]['inicia'] = 60;
		$secciones[7][3]['termina'] = 60;
		$secciones[7][3]['tipo'] = "ABIERTA";
		$secciones[7][3]['set_valores'] = 0;
		$secciones[7][3]['cero'] = "";
		$secciones[7][3]['comentario'] = false;
		$secciones[7][3]['limitada'] = "TODAS";
	// Establece el total de secciones y subsecciones así como de reactivos
	$total_secciones = 7;
	$total_subsecciones[1] = 1;
	$total_subsecciones[2] = 2;
	$total_subsecciones[3] = 1;
	$total_subsecciones[4] = 2;
	$total_subsecciones[5] = 3;
	$total_subsecciones[6] = 2;
	$total_subsecciones[7] = 3;
	$total_reactivos = 60;
	
	// Establece el número de respuestas posibles por renglón
	$valores_por_renglon = 11;
	
	// Establece el texto previo a los reactivos
	$previo['numeracion'] = "";
	$previo['encabezado'] = "Indicaciones";
	$previo['cuerpo'][] = "Contesta las siguientes preguntas que corresponden a espacios, recursos y servicios de la UMG.";
	$previo['cuerpo'][] = "Utiliza una escala del 1 al 10 y NA donde 1 es muy malo, 10 excelente y NA es que no aplica porque nunca se presentó esa situación.";
	
	// Establece el texto de los reactivos
	// ------------------------------------------------------------------------------ 01-01
	$texto_reactivo[1] = "Salones";
	$texto_reactivo[2] = "Pasillos";
	$texto_reactivo[3] = "Baños";
	$texto_reactivo[4] = "Ciberplaza (sombrillas)";
	$texto_reactivo[5] = "Salones de Cómputo";
	$texto_reactivo[6] = "Canchas";
	$texto_reactivo[7] = "Estacionamiento";
	$texto_reactivo[8] = "Auditorios";
	$texto_reactivo[9] = "Espacios propios de tu carrera (talleres, sala Gesell, otro)";
	$texto_reactivo[10] = "Espacios de usos múltiples (gimnasio, talleres deportivos y culturales, etc.)";
	
	// ------------------------------------------------------------------------------ 02-01
	$texto_reactivo[11] = "Salones";
	$texto_reactivo[12] = "Pasillos";
	$texto_reactivo[13] = "Baños";
	$texto_reactivo[14] = "Ciberplaza (sombrillas)";
	$texto_reactivo[15] = "Salones de Cómputo";
	$texto_reactivo[16] = "Canchas";
	$texto_reactivo[17] = "Estacionamiento";
	$texto_reactivo[18] = "Auditorios";
	$texto_reactivo[19] = "Espacios propios de tu carrera (talleres, sala Gesell, otro)";
	$texto_reactivo[20] = "Espacios de usos múltiples (gimnasio, talleres deportivos y culturales, etc.)";
	// ------------------------------------------------------------------------------ 02-02
	$texto_reactivo[21] = "Las instalaciones y espacios contribuyen al logro de la misión institucional";
	// ------------------------------------------------------------------------------ 03-01
	$texto_reactivo[22] = "Tus propuestas";
	// ------------------------------------------------------------------------------ 04-01
	$texto_reactivo[23] = "Administración";
	$texto_reactivo[24] = "Control Escolar";
	$texto_reactivo[25] = "Biblioteca";
	$texto_reactivo[26] = "Internacionalización (Intercambios)";
	$texto_reactivo[27] = "Informática y Telecomunicaciones";
	$texto_reactivo[28] = "Formación Integral";
	$texto_reactivo[29] = "DAFSI";
	$texto_reactivo[30] = "Orientación Educativa";
	$texto_reactivo[31] = "Enfermería";
	$texto_reactivo[32] = "Centro de Servicios (sala de firmas)";
	$texto_reactivo[33] = "Seguridad (vigilantes)";
	$texto_reactivo[34] = "Limpieza (intendencia)";
	// ------------------------------------------------------------------------------ 04-02
	$texto_reactivo[35] = "Atención a Profesores";
	// ------------------------------------------------------------------------------ 05-01
	$texto_reactivo[36] = "Administración";
	$texto_reactivo[37] = "Control Escolar";
	$texto_reactivo[38] = "Biblioteca";
	$texto_reactivo[39] = "Internacionalización (Intercambios)";
	$texto_reactivo[40] = "Informática y Telecomunicaciones";
	$texto_reactivo[41] = "Formación Integral";
	$texto_reactivo[42] = "Deporte y Cultura";
	$texto_reactivo[43] = "Orientación Educativa";
	$texto_reactivo[44] = "Enfermería";
	$texto_reactivo[45] = "Centro de Servicios (sala de firmas)";
	$texto_reactivo[46] = "Seguridad (vigilantes)";
	$texto_reactivo[47] = "Limpieza (intendencia)";
	// ------------------------------------------------------------------------------ 05-02
	$texto_reactivo[48] = "Atención a Profesores";
	// ------------------------------------------------------------------------------ 05-03
	$texto_reactivo[49] = "¿Qué sugieres para mejorar el servicio y la atención de estas áreas?";
	// ------------------------------------------------------------------------------ 06-01
	$texto_reactivo[50] = "¿Has utilizado la biblioteca, e-libro o las bases de datos durante el último ciclo escolar?";
	$texto_reactivo[51] = "¿Existe afinidad entre el material bibliográfico que te recomiendan tus profesores y el que se encuentra en la biblioteca, bases de datos o e-libro?";
	$texto_reactivo[52] = "¿Existe material adecuado en la biblioteca, bases de datos o e-libro sobre tu área de estudio?";
	$texto_reactivo[53] = "¿Cuándo usas la biblioteca, las bases de datos o e-libro encuentras lo que estabas buscando?";
	// ------------------------------------------------------------------------------ 06-02
	$texto_reactivo[54] = "¿Qué sugieres para mejorar el servicio que brinda la biblioteca (CSI)?";
	// ------------------------------------------------------------------------------ 07-01
	$texto_reactivo[55] = "¿Todas tus materias se encuentran activas en la plataforma TEAMS?";
	// ------------------------------------------------------------------------------ 07-02
	$texto_reactivo[56] = "La plataforma TEAMS cumple con su finalidad, que es ser un espacio virtual para el desarrollo del proceso de aprendizaje y logro de las competencias profesionales.";
	$texto_reactivo[57] = "Recibo asesoría y apoyo cuando tengo dudas sobre el uso de TEAMS.";
	$texto_reactivo[58] = "El uso que hacen los docentes de las aplicaciones de TEAMS (videoconferencias, formularios, Planner, etc) es el adecuado.";
	$texto_reactivo[59] = "Considero que la plataforma TEAMS favorece el logro de la Misión Institucional";
	// ------------------------------------------------------------------------------ 07-03
	$texto_reactivo[60] = "¿Qué sugerencias tienes para mejorar la experiencia educativa por medio de TEAMS?";
	// ------------------------------------------------------------------------------ FIN
	// Define el texto del encabezado de la evaluación
	$encabezado[1] = "Espacios, Recursos y Servicios";
	$encabezado[2] = "";
	// Define la secuencia de campos a registrar en la base de datos (solo 3)
	$secuencia_sql[1] = "carrera, grado, sexo, situacion"; // Campos de la tabla adicionales a las meras respuestas
	$secuencia_sql[2] =  $_SESSION['zez_a_carrera'] . "', '" . $_SESSION['zez_a_grado'] . "', '" . $_SESSION['zez_a_sexo'] . "', '" . $_SESSION['zez_a_situacion'];
?>