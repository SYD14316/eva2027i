<?php
	//Defino si el profesor a evaluar es masculino o femenino
		switch ($parametros[3]) {
			case 'FEMENINO':
				$txt_sx_may="La";
				$txt_sx_min="la";
			break;
			case 'MASCULINO':
				$txt_sx_may="El";
				$txt_sx_min="el";
			break;
		}
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
					<ul>
						<li>
							Recupera información previa respecto a $txt_sx_min docente, desde Coordinación Docentes u otras instancias proveedoras.
						</li>
						<li>
							Contesta la siguiente encuesta eligiendo la opción que refleja el nivel de cumplimiento de $txt_sx_min docente que estás evaluando. Considera para ello la siguiente escala:
							<br><strong>0 es el mínimo.</strong>
							<br><strong>4 es el máximo.</strong>
						</li>
						<li>Procura responder con objetividad, sin perder de vista que esta evaluación es para la mejora integral.</li>
						<li>
							<br>Considera: 
							<br><strong>Compromisos administrativos son:</strong>registrar entrada y salida en el sistema de control, reponer inasistencias programadas, cumplimiento de otros reglamentos como los del estacionamiento, biblioteca; entrega en tiempo y forma de la documentación, firma de contrato entre otros.
							<br><strong>Compromisos académicos son:</strong> socialización de la planeación didáctica (acuse), aplicar evaluaciones parciales, finales y extraordinarias, y retroalimentar a las y los estudiantes, así como registrar oportunamente sus inasistencias o retardos en el SND, firmar a tiempo acta de calificaciones, entre otros.
						</li>
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
		$secciones[1][2]['termina'] = 8;
		$secciones[1][2]['tipo'] = "ABIERTA";
		$secciones[1][2]['set_valores'] = 0;
		$secciones[1][2]['cero'] = "";
		$secciones[1][2]['comentario'] = false;
		$secciones[1][2]['limitada'] = "";
	// Establece el total de secciones y subsecciones así como de reactivos
		$total_secciones = 1;
		$total_subsecciones[1] = 2;
		$total_reactivos = 8;
	// Establece el número de respuestas posibles por renglón
		$valores_por_renglon = 5;//10
	// Establece el texto previo a los reactivos
		$previo['numeracion'] = "";
		$previo['encabezado'] = "";
		$previo['cuerpo'][] = "";
	// Establece el texto de los reactivos
		$texto_reactivo[1] = "(1C) $txt_sx_may docente se conduce de manera respetuosa al relacionarse con las personas de todas las áreas de la Universidad, sin tener registro a la fecha de algún reporte de comportamiento inadecuado.";
		$texto_reactivo[2] = "(2C) $txt_sx_may docente se conduce siempre bajo los principios de integridad académica.";
		$texto_reactivo[3] = "(3C) $txt_sx_may docente cumple asiduamente sus compromisos <strong>como prestador de servicios</strong>: puntualidad/asistencia, atención a solicitudes administrativas como entrega de documentación, etc.";
		$texto_reactivo[4] = "(4C) $txt_sx_may docente cumple asiduamente sus compromisos en <strong>procesos académicos</strong>, como captura de calificaciones, entrega de planeaciones, entre otros.";
		$texto_reactivo[5] = "(5C) $txt_sx_may docente participa en reuniones del colegio de carrera.";
		$texto_reactivo[6] = "Fortalezas de $txt_sx_min docente.";
		$texto_reactivo[7] = "Áreas de mejora de $txt_sx_min docente";
		$texto_reactivo[8] = "Resultado de evaluación de la capacitación (solo dígitos, máximo 15)";

		/*$texto_reactivo[9] = "Resultado de evaluación de la planeación didáctica (solo dígitos, máximo 15)";
		$texto_reactivo[10] = "Resultado de evaluación del acompañamiento proceso enseñanza aprendizaje (solo dígitos, máximo 20)";*/
		if(!isset($reporte)){
			// Define el texto del encabezado de la evaluación
				$encabezado[1] = $parametros[2];
				$encabezado[2] = "PROFESOR" . $parametros[4];
			// Define la secuencia de campos a registrar en la base de datos (solo 3)
				$secuencia_sql[1] = "nombre, sexo, carrera, sello"; // Campos de la tabla adicionales a las meras respuestas
				$secuencia_sql[2] =  $parametros[2] . "', '" . $parametros[3] . "', '" . $parametros[5] . "', '" . $_SESSION['zez_a_sello'];
			//
		}
	//
?>