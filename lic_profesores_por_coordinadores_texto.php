<?php
	//Identifico si se envio el parametro de sexo
	if (isset($parametros[6])) {
		//Si se envio el parametro, continuo
	}else{
		//Si no se envio el parametro, asigno valor por defecto
			$parametros[6] = 'INDISTINTO';
	}
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
	$total_reactivos = 8;
	// Establece el número de respuestas posibles por renglón
	$valores_por_renglon = 5;
	// Establece el texto de los reactivos
		$texto_reactivo[1] = "(1C) $txt_sx_may docente se conduce de manera respetuosa al relacionarse con las personas de todas las áreas de la Universidad, sin tener registro a la fecha de algún reporte de comportamiento inadecuado.";
		$texto_reactivo[2] = "(2C) $txt_sx_may docente se conduce siempre bajo los principios de integridad académica.";
		$texto_reactivo[3] = "(3C) $txt_sx_may docente cumple asiduamente sus compromisos <strong>como prestador de servicios</strong>: puntualidad/asistencia, atención a solicitudes administrativas como entrega de documentación, etc.";
		$texto_reactivo[4] = "(4C) $txt_sx_may docente cumple asiduamente sus compromisos en <strong>procesos académicos</strong>, como captura de calificaciones, entrega de planeaciones, entre otros.";
		$texto_reactivo[5] = "(5C) $txt_sx_may docente participa en reuniones del colegio de carrera.";
		$texto_reactivo[6] = "Fortalezas de $txt_sx_min docente.";
		$texto_reactivo[7] = "Áreas de mejora de $txt_sx_min docente";
		$texto_reactivo[8] = "Resultado de evaluación de la capacitación (solo dígitos, máximo 15)";
	//
?>