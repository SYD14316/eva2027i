

<?php
	// Establece el tamaño de los distitntos sets de valores que se utilizarán, siempre el set 0 será de valor 1 porque es para las respuestas abiertas
		$tamano_set_valores[0] = 1;
		$tamano_set_valores[1] = 5;
	// Establece el set de valores para las respuestas cerradas y las de opción múltiple
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
				<li>Contesta la siguiente encuesta a partir de un ejercicio de reflexión en relación a tu vocación y práctica docente. Considera para ello la siguiente escala: 
					<br><strong>0 es el mínimo</strong>
					<br><strong>4 es el máximo</strong>
				</li>
				<li>
					Los resultados se tratarán de manera confidencial y servirán para fines de mejora integral.
					<br><strong>Aviso de privacidad:</strong> <a href='https://umg.edu.mx/portal/aviso-de-privacidad/ ' target='_blank'>https://umg.edu.mx/portal/aviso-de-privacidad/</a>

				</li>
			</small>
		";
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
		$secciones[1][2]['termina'] = 13;
		$secciones[1][2]['tipo'] = "ABIERTA";
		$secciones[1][2]['set_valores'] = 0;
		$secciones[1][2]['cero'] = "";
		$secciones[1][2]['comentario'] = false;
		$secciones[1][2]['limitada'] = "";
	// Establece el total de secciones y subsecciones así como de reactivos
		$total_secciones = 1;
		$total_subsecciones[1] = 2;
		$total_reactivos = 13;
	// Establece el número de respuestas posibles por renglón
		$valores_por_renglon = 5;//10
	// Establece el texto previo a los reactivos
		$previo['numeracion'] = "";
		$previo['encabezado'] = "";
		$previo['cuerpo'][] = "";
	// Establece el texto de los reactivos
		$texto_reactivo[1] = "(1D) Tengo dominio sobre los contenidos de aprendizaje de la(s) materia(s) a mi cargo:  conceptos, teorías, modelos, principios, prácticas, dentro de mi campo profesional.";
		$texto_reactivo[2] = "(2D) Relaciono mi experiencia profesional con los procesos de aprendizaje de las y los estudiantes.";
		$texto_reactivo[3] = "(3D) Me comunico eficientemente con mis estudiantes. Si la materia así lo exige, utilizo tecnologías de la información y comunicación.";
		$texto_reactivo[4] = "(4D) Comunico e invito a mis estudiantes a conducirse con ética profesional.";
		$texto_reactivo[5] = "(5D) Aplico estrategias metodológicas acordes con las características de las y los estudiantes y los objetivos de aprendizaje.";
		$texto_reactivo[6] = "(6D) Aplico estrategias, criterios y mecanismos de evaluación de forma integral y con perspectiva formativa.";
		$texto_reactivo[7] = "(7D) Promuevo con mis estudiantes un ambiente respetuoso, motivante y confiable, lo que permite el intercambio de ideas, el diálogo académico y la sana convivencia.";
		$texto_reactivo[8] = "(8D) Me conduzco eficaz y oportunamente en los entornos físicos, en las relaciones interpersonales y en los procesos relacionados con la función docente atendiendo las disposiciones institucionales y contribuyendo en los procesos de mejora continua.";
		$texto_reactivo[9] = "(9D) Me conduzco con honorabilidad, respeto a los derechos humanos y sentido de contribución con el logro de los valores institucionales.";
		$texto_reactivo[10] = "(10D) Participo en las reuniones de colegio de docentes. Si por razones de causa mayor no puedo asistir, solicito la información compartida.";
		$texto_reactivo[11] = "(11D) Mis fortalezas como docente son:";
		$texto_reactivo[12] = "(12D) Mis áreas de mejora como docente son:";
		$texto_reactivo[13] = "(13D) Comentarios adicionales (sugerencias de toda índole que favorezcan la práctica docente, el bienestar y los procesos de aprendizaje):";
	//
		if(!isset($reporte)){
			// Define el texto del encabezado de la evaluación
				$encabezado[1] = "Autoevaluación";
				$encabezado[2] = $_SESSION['zez_a_nombre'];
				$encabezado[3] = $parametros[2]." - ".$parametros[4]." - ".$parametros[3];
			// Define la secuencia de campos a registrar en la base de datos (solo 3)
				$secuencia_sql[1] = "nombre, sexo, carrera, materia, grupo"; // Campos de la tabla adicionales a las meras respuestas
				$secuencia_sql[2] =  $_SESSION['zez_a_nombre'] . "', '" . $_SESSION['zez_a_sexo'] . "', '" . $parametros[2] . "', '" . $parametros[3] . "', '" . $parametros[4];
			//
		}
	//
?>