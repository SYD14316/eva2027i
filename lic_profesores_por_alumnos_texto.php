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
	//Definicion de total de reactivos
		$total_reactivos = 6;
	// Establece el número de respuestas posibles por renglón
		$valores_por_renglon = 5;//10
	// Establece el texto de los reactivos
		$texto_reactivo[1] = "(1E) $txt_sx_may docente domina la materia, explica, da ejemplos, resuelve mis dudas.";
		$texto_reactivo[2] = "(2E) $txt_sx_may docente sabe relacionar su experiencia y saberes con mi formación profesional y me invita a vivir los valores maristas.";
		$texto_reactivo[3] = "(3E) $txt_sx_may docente sabe enseñar y aprendo cosas útiles que me harán más competente en mi profesión.";
		$texto_reactivo[4] = "(4E) $txt_sx_may docente me evalúa no solo los conocimientos sino también las habilidades; es decir, lo que debo saber hacer respecto a mi profesión.";
		$texto_reactivo[5] = "(5E) $txt_sx_may docente promueve un ambiente respetuoso, motivante y confiable, lo que permite el intercambio de ideas y la sana convivencia.";
		$texto_reactivo[6] = "(6E) Comentarios, felicitaciones, sugerencias a $txt_sx_min docente.";
	//
?>