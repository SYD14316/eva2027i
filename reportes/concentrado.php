<?php 
	include_once "funciones.php";
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CONCENTRADO DE RESULTADO</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous" >

	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
	<div class="card">
		<div class="card-header"><h1>CONCENTRADO DE RESULTADO</h1></div>
		<div class="card-body">
			<table class="table table-bordered table-striped" id="tabla">
				<thead>
					<tr>
						<th>Matricula</th>
						<th>Nombre</th>
						<!-- Evaluacion de alumnos-->
							<th>Cantidad de evaluaciones de alumnos registradas</th>
							<th>1.- El docente domina la materia, explica, da ejemplos, resuelve dudas</th>
							<th>2.- El docente sabe relacionar su experiencia y saberes con mi formación profesional marista</th>
							<th>3.- El docente sabe enseñar y aprendo cosas útiles que me harán más competente en mi profesión</th>
							<th>4.- El docente me evalúa no solo los conocimientos sino también las habilidades, es decir, lo que debo saber hacer respecto a mi profesión</th>
							<th>5.- El docente me invita a vivir los valores propios de mi profesión</th>
							<th>6.- El docente promueve un ambiente respetuoso, motivante y confiable, lo que permite el intercambio de ideas y la sana convivencia</th>
							<th>Cantidad maxima de puntos posibles de evaluaciones de alumnos</th>
							<th>Cantidad de puntos obtenidos de evaluaciones de alumnos</th>
							<th>% Evaluacion de alumnos</th>
							<th>% Evaluacion general de alumnos </th>
						<!-- Autoevaluacion --->
							<th>Cantidad de Autoevaluaciones</th>
							<th>1.- Tengo dominio sobre los contenidos de aprendizaje de la materia a mi cargo:  conceptos, teorías, modelos, principios, prácticas, dentro de mi campo profesional</th>
							<th>2.- Relaciono mi experiencia profesional con los procesos de aprendizaje para la inserción laboral o social de las y los estudiantes</th>
							<th>3.- Me comunico eficientemente con mis estudiantes. Si la materia así lo exige, utilizo tecnologías de la información y comunicación</th>
							<th>4.- Comunico e invito a mis estudiantes a conducirse con ética profesional</th>
							<th>5.- Aplico estrategias metodológicas acordes con las características de los estudiantes y los objetivos de aprendizaje</th>
							<th>6.- Aplico estrategias, criterios y mecanismos de evaluación de forma integral y con perspectiva formativa</th>
							<th>7.- Promuevo con mis estudiantes un ambiente respetuoso, motivante y confiable, lo que permite el intercambio de ideas, el diálogo académico y la sana convivencia</th>
							<th>8.- Me conduzco eficaz y oportunamente en los entornos físicos, en las relaciones interpersonales y en los procesos relacionados con la función docente atendiendo las disposiciones institucionales y contribuyendo en los procesos de mejora continua</th>
							<th>9.- Me conduzco con honorabilidad, respeto a los derechos humanos y sentido de contribución con el logro de los valores institucionales</th>
							<th>10.- Participo en las reuniones de colegio de docentes. Si por razones de causa mayor no puedo asistir, solicito la información compartida</th>
							<th>Cantidad maxima de puntos posibles de autoevaluacion</th>
							<th>Cantidad de puntos obtenidos de autoevaluacion</th>
							<th>% Autoevaluacion</th>
							<th>% Evaluacion general</th>
						<!-- Evaluacion de coordinador -->
							<th>Cantidad de evaluaciones de coordinadores</th>
							<th>1.- El docente se conduce de manera respetuosa al relacionarse con las personas de todas las áreas de la Universidad, sin tener registro a la fecha de algún reporte de comportamiento inadecuado</th>
							<th>2.- El docente se conduce siempre bajo los principios de integridad académica</th>
							<th>3.- El docente cumple asiduamente sus compromisos como prestador de servicios: puntualidad/asistencia, atención a solicitudes administrativas como entrega de documentación, etc</th>
							<th>4.- El docente cumple asiduamente sus compromisos en procesos académicos, como captura de calificaciones, entrega de planeaciones, entre otros</th>
							<th>5.- El docente participa en reuniones del colegio de carrera</th>
							<th>Cantidad maxima de puntos posibles de evaluaciones de coordinadores</th>
							<th>Cantidad de puntos obtenidos de evaluaciones de coordinadores</th>
							<th>% Evaluacion de coordinador</th>
							<th>% Evaluacion general</th>
						<!-- Preevaluaciones -->
							<th>Calificación de capacitación</th>
							<th>% Evaluacion general</th>
							<th>Calificación de planeación</th>
							<th>% Evaluacion general</th>
							<th>Calificación de acompañamiento</th>
							<th>% Evaluacion general</th>
							<th>% General final</th>
						<!-- -->
					</tr>
				</thead>
				<tbody>
					<?php
						/* Identifico a cada uno de los profesores de licenciaturas*/
						//Defino la sentencia a ejecutar
							$sentencia="SELECT * FROM participantes where nivel_acceso='PROFESOR' AND nivel='LICENCIATURA'";
					 	//Ejecuto la sentencia y almaceno lo obtenido en una variable
              $resultado_sentencia=retorna_datos($sentencia);
            //Identifico si el reultado no es vacio
              if ($resultado_sentencia['rowCount'] > 0) {
                //Almaceno los datos obtenidos
                  $resultado = $resultado_sentencia['data'];
                // Recorrer los datos y llenar las filas
                  foreach ($resultado as $tabla) {
                  	//Almaceno los resultados en variables
	                  	$id=$tabla['id'];
	                  	$nombre_docente=$tabla['nombre'];
	                  	$matricula_docente=$tabla['matricula'];
	                 	//Comienzo la impresion de la tabla
	                  	echo "<tr>";
	                  //Imprimo los datos principáles
	                  	echo "
                  			<td>$matricula_docente</td>
                  			<td>$nombre_docente</td>
	                  	";
	                  //Lote de evaluaciones de alumnos
	                  	//Defino una variable de sumatoria en 0
	                  		$sumatoria_alumnos=0;
	                  	//Cantidad de evaluaciones registradas a su nombre
	                  		$cantidad_evaluaciones_alumnos=busca_existencia("SELECT count(id) as exist from lic_profesores_por_alumnos where nombre='$nombre_docente';");
	                  	//Imprimo la cantidad de evaluaciones de alumnos registradas
	                  		echo "<td>$cantidad_evaluaciones_alumnos</td>";
	                  	//Obtengo el puntaje maximo por reactivo
                  			$maxima_reactivo_alumnos=$cantidad_evaluaciones_alumnos*4;
                  		//Obtengo el puntaje maximo del docente
                  			$maxima_alumnos=$maxima_reactivo_alumnos*6;
                  		//Comienzo la busqueda de los datos
                  			for ($i=1; $i < 7; $i++) { 
                  				//Obtengo el valor del reactivo
                  					$reactivo_alumnos=busca_existencia("SELECT sum(r0$i) as exist from lic_profesores_por_alumnos where nombre='$nombre_docente';");
                  				//IObtengo la sumatoria de cada reactivo obtenido por el profesor
                  					$sumatoria_alumnos+=$reactivo_alumnos;
                  				//Imprimo el puntaje del reativo
                  					echo "<td>$reactivo_alumnos</td>";
                  				//Vacio el valor del reactivo
                  					$reactivo_alumnos=0;
                  				//
                  			}
                  		//Obento el porcentaje obtenido por el rofesor
                  			$parcial_alumnos=round((($sumatoria_alumnos/$maxima_alumnos)*100),2);
                  		//Obtengo el porcentaje equivalente en la evaluacion
                  			$general_alumnos=round((0.20*$parcial_alumnos),2);
                  		//Imprimo la cantidad de puntos posibles
                  			echo "<td>$maxima_alumnos</td>";
                  		//Imprimo la cantidad de puntos obtenidos
                  			echo "<td>$sumatoria_alumnos</td>";
                  		//Imprimo el resultado
                  			echo "<td>$parcial_alumnos</td>";
                  			echo "<td>$general_alumnos</td>";
                  		//
                  	//Lote de evaluaciones de profesores
	                  	//Defino una variable de sumatoria en 0
	                  		$sumatoria_profesores=0;
	                  	//Cantidad de evaluaciones registradas a su nombre
	                  		$cantidad_evaluaciones_profesores=busca_existencia("SELECT count(id) as exist from lic_profesores_por_profesores where nombre='$nombre_docente';");
	                  	//Imprimo la cantidad de autoevaluaciones registradas
	                  		echo "<td>$cantidad_evaluaciones_profesores</td>";
	                  	//Obtengo el puntaje maximo por reactivo
                  			$maxima_reactivo_profesores=$cantidad_evaluaciones_profesores*4;
                  		//Obtengo el puntaje maximo del docente
                  			$maxima_profesores=$maxima_reactivo_profesores*10;
                  		//Comienzo la busqueda de los datos
                  			for ($i=1; $i < 11; $i++) {
                  				if ($i<10) { $casilla="r0$i"; }else{ $casilla="r$i"; }
                  				//Obtengo el valor del reactivo
                  					$reactivo_profesores=busca_existencia("SELECT sum($casilla) as exist from lic_profesores_por_profesores where nombre='$nombre_docente';");
                  				//IObtengo la sumatoria de cada reactivo obtenido por el profesor
                  					$sumatoria_profesores+=$reactivo_profesores;
                  				//Imprimo el puntaje del reativo
                  					echo "<td>$reactivo_profesores</td>";
                  				//Vacio el valor del reactivo
                  					$reactivo_profesores=0;
                  				//
                  			}
                  		//Obento el porcentaje obtenido por el rofesor
                  			if ($sumatoria_profesores>0) {
                  				$parcial_profesores=round((($sumatoria_profesores/$maxima_profesores)*100),2);
                  			}else{
                  				$parcial_profesores=0;
                  			}
                  		//Obtengo el porcentaje equivalente en la evaluacion
                  			if ($parcial_profesores>0) {	
                  				$general_profesores=round((0.10*$parcial_profesores),2);
                  			}else{
                  				$general_profesores=0;
                  			}
                  		//Imprimo la cantidad de puntos posibles
                  			echo "<td>$maxima_profesores</td>";
                  		//Imprimo la cantidad de puntos obtenidos
                  			echo "<td>$sumatoria_profesores</td>";
                  		//Imprimo el resultado
                  			echo "<td>$parcial_profesores</td>";
                  			echo "<td>$general_profesores</td>";
                  		//
                  	//Lote de evaluaciones de coordinadores
	                  	//Defino una variable de sumatoria en 0
	                  		$sumatoria_coordinadores=0;
	                  	//Cantidad de evaluaciones registradas a su nombre
	                  		$cantidad_evaluaciones_coordinadores=busca_existencia("SELECT count(id) as exist from lic_profesores_por_coordinadores where nombre='$nombre_docente';");
	                  	//Imprimo la cantidad de evaluaciones de coordinadores registradas
	                  		echo "<td>$cantidad_evaluaciones_coordinadores</td>";
	                  	//Obtengo el puntaje maximo por reactivo
                  			$maxima_reactivo_coordinadores=$cantidad_evaluaciones_coordinadores*4;
                  		//Obtengo el puntaje maximo del docente
                  			$maxima_coordinadores=$maxima_reactivo_coordinadores*6;
                  		//Comienzo la busqueda de los datos
                  			for ($i=1; $i < 6; $i++) {
                  				if ($i<10) { $casilla="r0$i"; }else{ $casilla="r$i"; }
                  				//Obtengo el valor del reactivo
                  					$reactivo_coordinadores=busca_existencia("SELECT sum($casilla) as exist from lic_profesores_por_coordinadores where nombre='$nombre_docente';");
                  				//IObtengo la sumatoria de cada reactivo obtenido por el profesor
                  					$sumatoria_coordinadores+=$reactivo_coordinadores;
                  				//Imprimo el puntaje del reativo
                  					echo "<td>$reactivo_coordinadores</td>";
                  				//Vacio el valor del reactivo
                  					$reactivo_coordinadores=0;
                  				//
                  			}
                  		//Obento el porcentaje obtenido por el rofesor
                  			if ($sumatoria_coordinadores>0) {
                  				$parcial_coordinadores=round((($sumatoria_coordinadores/$maxima_coordinadores)*100),2);
                  			}else{
                  				$parcial_coordinadores=0;
                  			}
                  		//Obtengo el porcentaje equivalente en la evaluacion
                  			if ($parcial_coordinadores>0) {	
                  				$general_coordinadores=round((0.25*$parcial_coordinadores),2);
                  			}else{
                  				$general_coordinadores=0;
                  			}
                  		//Imprimo la cantidad de puntos posibles
                  			echo "<td>$maxima_coordinadores</td>";
                  		//Imprimo la cantidad de puntos obtenidos
                  			echo "<td>$sumatoria_coordinadores</td>";
                  		//Imprimo el resultado
                  			echo "<td>$parcial_coordinadores</td>";
                  			echo "<td>$general_coordinadores</td>";
                  		//
                  	//Lote de reactivo_1
	                  	//Obtengo el puntaje maximo por reactivo
	                			$maxima_reactivo_coordinadores_1=$cantidad_evaluaciones_coordinadores*100;
		          				//Obtengo el valor del reactivo
		          					$sumatoria_coordinadores_1=busca_existencia("SELECT sum(r07) as exist from lic_profesores_por_coordinadores where nombre='$nombre_docente';");
		          				//Imprimo el puntaje del reativo
		          					echo "<td>$sumatoria_coordinadores_1</td>";
	                		//Obento el porcentaje obtenido por el rofesor
	                			if ($sumatoria_coordinadores_1>0) {
	                				$parcial_coordinadores_1=round((($sumatoria_coordinadores_1/$maxima_reactivo_coordinadores_1)*100),2);
	                			}else{
	                				$parcial_coordinadores_1=0;
	                			}
	                		//Obtengo el porcentaje equivalente en la evaluacion
	                			if ($parcial_coordinadores_1>0) {	
	                				$general_coordinadores_1=round((0.10*$parcial_coordinadores_1),2);
	                			}else{
	                				$general_coordinadores_1=0;
	                			}
	                		//Imprimo el resultado
	                			echo "<td>$general_coordinadores_1</td>";
	                		//
	                	//Lote de reactivo_2
	                  	//Obtengo el puntaje maximo por reactivo
	                			$maxima_reactivo_coordinadores_2=$cantidad_evaluaciones_coordinadores*100;
		          				//Obtengo el valor del reactivo
		          					$sumatoria_coordinadores_2=busca_existencia("SELECT sum(r08) as exist from lic_profesores_por_coordinadores where nombre='$nombre_docente';");
		          				//Imprimo el puntaje del reativo
		          					echo "<td>$sumatoria_coordinadores_2</td>";
	                		//Obento el porcentaje obtenido por el rofesor
	                			if ($sumatoria_coordinadores_2>0) {
	                				$parcial_coordinadores_2=round((($sumatoria_coordinadores_2/$maxima_reactivo_coordinadores_2)*100),2);
	                			}else{
	                				$parcial_coordinadores_2=0;
	                			}
	                		//Obtengo el porcentaje equivalente en la evaluacion
	                			if ($parcial_coordinadores_2>0) {	
	                				$general_coordinadores_2=round((0.15*$parcial_coordinadores_2),2);
	                			}else{
	                				$general_coordinadores_2=0;
	                			}
	                		//Imprimo el resultado
	                			echo "<td>$general_coordinadores_2</td>";
	                		//
	                	//Lote de reactivo_3
	                  	//Obtengo el puntaje maximo por reactivo
	                			$maxima_reactivo_coordinadores_3=$cantidad_evaluaciones_coordinadores*100;
		          				//Obtengo el valor del reactivo
		          					$sumatoria_coordinadores_3=busca_existencia("SELECT sum(r09) as exist from lic_profesores_por_coordinadores where nombre='$nombre_docente';");
		          				//Imprimo el puntaje del reativo
		          					echo "<td>$sumatoria_coordinadores_3</td>";
	                		//Obento el porcentaje obtenido por el rofesor
	                			if ($sumatoria_coordinadores_3>0) {
	                				$parcial_coordinadores_3=round((($sumatoria_coordinadores_3/$maxima_reactivo_coordinadores_3)*100),2);
	                			}else{
	                				$parcial_coordinadores_3=0;
	                			}
	                		//Obtengo el porcentaje equivalente en la evaluacion
	                			if ($parcial_coordinadores_3>0) {	
	                				$general_coordinadores_3=round((0.20*$parcial_coordinadores_3),2);
	                			}else{
	                				$general_coordinadores_3=0;
	                			}
	                		//Imprimo el resultado
	                			echo "<td>$general_coordinadores_3</td>";
	                		//
              			//Calificacion final
                  		$final=round(($general_coordinadores_3+$general_coordinadores_2+$general_coordinadores_1+$general_coordinadores+$general_profesores+$general_alumnos),2);
	                	//Imprimo el resultado final
                  		echo "<td>$final</td>";
                		//Finalizo la impresion de la tabla
                			echo "</tr>";
                		//
                  }
              }
            //
					?>
				</tbody>
			</table>
		</div>
	</div>
</body>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/cr-1.5.6/date-1.1.2/fc-4.1.0/fh-3.2.4/kt-2.7.0/r-2.3.0/rg-1.2.0/rr-1.2.8/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/sr-1.1.1/datatables.min.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/cr-1.5.6/date-1.1.2/fc-4.1.0/fh-3.2.4/kt-2.7.0/r-2.3.0/rg-1.2.0/rr-1.2.8/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/sr-1.1.1/datatables.min.js"></script>
<script type="text/javascript">
  //Funcion para la tabla
    $(document).ready( function () {
      var table = $('#tabla').DataTable( {
        responsive: true,
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        "info": true,
        "pagingType":"full_numbers",
        dom: 'Bfrtip',
        buttons:{
          buttons:[
            { 
              extend: 'excelHtml5',
              text:'DESCARGAR EXCEL',
              orientation: 'landscape'
            },
            { extend: 'print', text:'IMPRIMIR' },{ extend: 'copy', text:'COPIAR' },
          ],
        },
      } );
      table.on( 'responsive-resize', function ( e, datatable, columns ) {
        var count = columns.reduce( function (a,b) {
          return b === false ? a+1 : a;
        }, 0 );
        console.log( count +' column(s) are hidden' );
      } );
    } );
  //
</script>


</html>