<?php 
	include_once "funciones.php";
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PROFESORES POR ALUMNOS</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous" >

	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
	<div class="card">
		<div class="card-header"><h1>RESULTADO DE PROFESORES POR ALUMNOS</h1></div>
		<div class="card-body">
			<table class="table table-bordered table-striped" id="tabla">
				<thead>
					<tr>
						<th>#</th>
						<th>GRUPO</th>
						<th>NOMBRE</th>
						<th>MATERIA</th>
						<th>TIPO</th>
						<th>SEXO</th>
						<th>CARRERA</th>
						<th>GRADO</th>
						<th>1.- El docente domina la materia, explica, da ejemplos, resuelve dudas</th>
							<th>2.- El docente sabe relacionar su experiencia y saberes con mi formación profesional marista</th>
							<th>3.- El docente sabe enseñar y aprendo cosas útiles que me harán más competente en mi profesión</th>
							<th>4.- El docente me evalúa no solo los conocimientos sino también las habilidades, es decir, lo que debo saber hacer respecto a mi profesión</th>
							<th>5.- El docente me invita a vivir los valores propios de mi profesión</th>
							<th>6.- El docente promueve un ambiente respetuoso, motivante y confiable, lo que permite el intercambio de ideas y la sana convivencia</th>
							<th>7.- Comentarios, felicitaciones, sugerencias al docente.</th>
					</tr>
				</thead>
				<tbody>
					<?php
						//Defino la sentencia a ejecutar
							$sentencia="SELECT * FROM lic_profesores_por_alumnos";
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
	                  	$grupo=$tabla['grupo'];
	                  	$nombre=$tabla['nombre'];
	                  	$materia=$tabla['materia'];
	                  	$sexo=$tabla['sexo'];
	                  	$carrera=$tabla['carrera'];
	                  	$grado=$tabla['grado'];
	                  	$r01=$tabla['r01'];
	                  	$r02=$tabla['r02'];
	                  	$r03=$tabla['r03'];
	                  	$r04=$tabla['r04'];
	                  	$r05=$tabla['r05'];
	                  	$r06=$tabla['r06'];
	                  	$r07=$tabla['r07'];
	                  	if ($tabla['sello']==1) {
	                  		$tipo='MATERIA INSTITUCIONAL';
	                  	}ELSE{
	                  		$tipo='GENERAL';
	                  	}
	                 	//Imprimo los resultados
	                  	echo "
	                  		<tr>
	                  			<td>$id</td>
	                  			<td>$grupo</td>
	                  			<td>$nombre</td>
	                  			<td>$materia</td>
	                  			<td>$tipo</td>
	                  			<td>$sexo</td>
	                  			<td>$carrera</td>
	                  			<td>$grado</td>
	                  			<td>$r01</td>
	                  			<td>$r02</td>
	                  			<td>$r03</td>
	                  			<td>$r04</td>
	                  			<td>$r05</td>
	                  			<td>$r06</td>
	                  			<td>$r07</td>
	                  		</tr>
	                  	";
	                  //
                  }
                //
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