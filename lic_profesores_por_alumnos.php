<?php
	//Defino si el profesor a evaluar es masculino o femenino
		switch ($parametros[6]) {
			case 'FEMENINO':
				$txt_sx_may="La";
				$txt_sx_min="la";
			break;
			case 'MASCULINO':
				$txt_sx_may="El";
				$txt_sx_min="el";
			break;
			default:
				$txt_sx_may="El/La";
				$txt_sx_min="el/la";
		}
	// Establece el tamaño de los distitntos sets de valores que se utilizarán, siempre el set 0 será de valor 1 porque es para las respuestas abiertas
		$tamano_set_valores[0] = 1;
		$tamano_set_valores[1] = 5;
	// Establece el set de valores para las respuestas cerradas y las de opción múltiple
		/*$set_valores[1][1]['texto'] = "0";
		$set_valores[1][1]['valor'] = 0;
		$set_valores[1][2]['texto'] = "1";
		$set_valores[1][2]['valor'] = 1;
		$set_valores[1][3]['texto'] = "2";
		$set_valores[1][3]['valor'] = 2;
		$set_valores[1][4]['texto'] = "3";
		$set_valores[1][4]['valor'] = 3;
		$set_valores[1][5]['texto'] = "4";
		$set_valores[1][5]['valor'] = 4;*/

		$set_valores[1][1]['texto'] = "<strong>0</strong> - Nunca";
		$set_valores[1][1]['valor'] = 0;
		$set_valores[1][2]['texto'] = "<strong>1</strong> - Casi nunca";
		$set_valores[1][2]['valor'] = 1;
		$set_valores[1][3]['texto'] = "<strong>2</strong> - Regularmente";
		$set_valores[1][3]['valor'] = 2;
		$set_valores[1][4]['texto'] = "<strong>3</strong> - Casi siempre";
		$set_valores[1][4]['valor'] = 3;
		$set_valores[1][5]['texto'] = "<strong>4</strong> - Siempre";
		$set_valores[1][5]['valor'] = 4;

	// Establece las secciones de reactivos
		$secciones[1][0]['numeracion'] = ""; // Establece la numeración para esta sección, dejar en blanco para no numerar.
		$secciones[1][0]['texto'] = "
			<strong>Indicaciones:</strong>
			<small>
				<ul>
					<li>Contesta la siguiente encuesta eligiendo la opción que más se acerque a tu opinión. Considera para ello la siguiente escala:
						<br><strong>0 es el mínimo</strong>
						<br><strong>4 es el máximo</strong>
					</li>
					<li>Tu participación es anónima y los resultados se tratarán con absoluta confidencialidad.</li>
					<li>Procura ser objetivo(a) y considerar que esta evaluación es para la mejora integral de nuestros servicios educativos.</li>
					<li>No dejes de compartirnos tus comentarios y sugerencias. Son de alto valor para conocer las fortalezas y limitaciones que nos ayudarán a mejorar, ¡adelante!</li>
				</ul>
			</small>
		";
		$secciones[1][0]['limitada'] = ""; // Una palabra de una carrera limita a que solo esa carrera pueda acceder, si son varias separar por espacios. Dejar cadena vacía para que accedan todas.
		$secciones[1][1]['inicia'] = 1; // Número de reactivo con el que inicia la subsección
		$secciones[1][1]['termina'] = 5; // Número de reactivo con el que termina la subsección
		$secciones[1][1]['tipo'] = "CERRADA"; // Los valores posibles son "CERRADA", "MULTIPLE" o "ABIERTA"
		$secciones[1][1]['set_valores'] = 1; // En abierta poner 0
		$secciones[1][1]['cero'] = ""; // Si el valor de cero se tomará para no aplica poner "NA", para insuficiente "INS", si no hay valor cero poner ""
		$secciones[1][1]['comentario'] = false; // Indica si después del reactivo debe de haber un espacio para el comentario. Valores true o false.
		$secciones[1][1]['limitada'] = ""; // Una palabra de una carrera limita a que solo esa carrera pueda acceder, si son varias separar por espacios. Dejar cadena vacía para que accedan todas.
		$secciones[1][1]['retroalimentacion'] = "";
		$secciones[1][2]['inicia'] = 6;
		$secciones[1][2]['termina'] = 6;
		$secciones[1][2]['tipo'] = "ABIERTA";
		$secciones[1][2]['set_valores'] = 0;
		$secciones[1][2]['cero'] = "";
		$secciones[1][2]['comentario'] = false;
		$secciones[1][2]['limitada'] = "";
	// Establece el total de secciones y subsecciones así como de reactivos
		$total_secciones = 1;
		$total_subsecciones[1] = 2;
		$total_reactivos = 6;
	// Establece el número de respuestas posibles por renglón
		$valores_por_renglon = 5;//10
	// Establece el texto previo a los reactivos
		$previo['numeracion'] = "";
		$previo['encabezado'] = "";
		$previo['cuerpo'][] = "";
	// Establece el texto de los reactivos
		$texto_reactivo[1] = "(1E) $txt_sx_may docente domina la materia, explica, da ejemplos, resuelve mis dudas.";
		$texto_reactivo[2] = "(2E) $txt_sx_may docente sabe relacionar su experiencia y saberes con mi formación profesional y me invita a vivir los valores maristas.";
		$texto_reactivo[3] = "(3E) $txt_sx_may docente sabe enseñar y aprendo cosas útiles que me harán más competente en mi profesión.";
		$texto_reactivo[4] = "(4E) $txt_sx_may docente me evalúa no solo los conocimientos sino también las habilidades; es decir, lo que debo saber hacer respecto a mi profesión.";
		$texto_reactivo[5] = "(5E) $txt_sx_may docente promueve un ambiente respetuoso, motivante y confiable, lo que permite el intercambio de ideas y la sana convivencia.";
		$texto_reactivo[6] = "(6E) Comentarios, felicitaciones, sugerencias a $txt_sx_min docente.";
	//
		if(!isset($reporte)){
			// Define el texto del encabezado de la evaluación
				$encabezado[1] = $parametros[4];
				$encabezado[2] = $parametros[5];
			// Define la secuencia de campos a registrar en la base de datos (solo 3)
				$secuencia_sql[1] = "grupo, nombre, materia, sello, sexo, carrera, grado"; // Campos de la tabla adicionales a las meras respuestas
				$secuencia_sql[2] =  $parametros[3] . "', '" . $parametros[4] . "', '" . $parametros[5] . "', '" . $parametros[7] . "', '" . $parametros[6] . "', '" . $_SESSION['zez_a_carrera'] . "', '" . $_SESSION['zez_a_grado'];
			//
		}
	//
?>