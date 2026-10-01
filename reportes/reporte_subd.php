<?php 
    //Se incluye el archivo de funciones
	    include_once "funciones.php";
    //Se inicia la sesion
	    session_start();
    //Muestro los errores
        /*error_reporting(E_ALL);
        ini_set("display_errors", 1);*/
    //

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>REPORTE DE DOCENTES PARA SUBDIRECCION</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous" >

	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .vertical-text {
            writing-mode: vertical-rl; /* Cambia la dirección del texto a vertical */
            transform: rotate(180deg); /* Opcional: rota el texto si es necesario */
            text-align: center; /* Centra el texto */
            vertical-align: middle; /* Alinea verticalmente */
            white-space: nowrap; /* Evita que el texto se divida en varias líneas */
        }
</style>
</head>
<body>
    <div class="card">
        <div class="card-header text-center">
            <h1 class="text-center">REPORTE DE DOCENTES PARA SUBDIRECCION</h1>
            <h2>CICLO 2026-II</h2>
        </div>
        <div class="card-body">
            <!-- Botón para imprimir la página -->
                <div class="text-right mb-3">
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
            <!--Informacion principal-->
                <table class="table table-bordered table-sm text-center">
                    <tr>
                        <td>FECHA</td>
                        <td><?PHP echo mb_strtoupper(transforma_fecha(ahora(1),1," DE ")); ?></td>
                    </tr>
                    <tr>
                        <td>CALIFICACION GENERAL</td>
                        <td id="calificacion_general"></td>
                    </tr>
                </table>
            <!--Titulo de participacion-->
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <td class="text-center"><h3>PARTICIPACION</h3></td>
                        </tr>
                    </thead>
                </table>
            <!--Tabla de evaluacion por conceptos-->
                <table class="table table-bordered table-sm" id="tabla_participacion">
                    <thead>
                        <tr>
                            <th>CARRERA</th>
                            <th>PORCENTAJE DE PARTICIPACIÓN DE ESTUDIANTES</th>
                            <th>PORCENTAJE DE PARTICIPACIÓN DE DOCENTES</th>
                            <th>PORCENTAJE DE DOCENTES EVALUADOS POR LA COORDINACIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            //Defino la sentencia de busqueda de carreras
                                $sentencia_carreras="SELECT carrera FROM carreras order by carrera asc;";
                            //Ejecuto la sentencia y almaceno lo obtenido en una variable
                                $resultado_sentencia=retorna_datos($sentencia_carreras);
                            //Identifico si el reultado no es vacio
                                if ($resultado_sentencia['rowCount'] > 0) {
                                    //Almaceno los datos obtenidos
                                        $resultado = $resultado_sentencia['data'];
                                    // Recorrer los datos y llenar las filas
                                        foreach ($resultado as $tabla) {
                                            //Almaceno los resultados en variables
                                                $carrera=$tabla['carrera'];
                                            //Cantidad de alumnos en la carrera
                                                $sentencia_cantidad_alumnos="SELECT COUNT(id) as exist FROM participantes WHERE carrera like '%$carrera%';";
                                                $cantidad_alumnos=busca_existencia($sentencia_cantidad_alumnos);
                                            //Cantidad de alumnos que participaron
                                                $sentencia_cantidad_alumnos_participaron="SELECT COUNT(id) as exist FROM participantes WHERE carrera like '%$carrera%' and num_evaluados<>0;";
                                                $cantidad_alumnos_participaron=busca_existencia($sentencia_cantidad_alumnos_participaron);
                                            //Cantidad de docentes en la carrera
                                                $sentencia_cantidad_docentes="SELECT DISTINCT COUNT(nombre) as exist FROM profesores WHERE carrera like '%$carrera%';";
                                                $cantidad_docentes=busca_existencia($sentencia_cantidad_docentes);
                                            //Cantidad de docentes que realizaron su autoevaluacion
                                                $sentencia_cantidad_docentes_participaron="SELECT COUNT(id) as exist FROM lic_profesores_por_profesores WHERE carrera like '%$carrera%';";
                                                $cantidad_docentes_participaron=busca_existencia($sentencia_cantidad_docentes_participaron);
                                            //Cantidad de docentes evaluados por el coordinador
                                                $sentencia_cantidad_coordinadores_participaron="SELECT COUNT(id) as exist FROM lic_profesores_por_coordinadores WHERE carrera like '%$carrera%';";
                                                $cantidad_coordinadores_participaron=busca_existencia($sentencia_cantidad_coordinadores_participaron);
                                            //Calculo el porcentaje de participacion de alumnos
                                                if(($cantidad_alumnos_participaron==0)|| ($cantidad_alumnos==0)){
                                                    $participacion_alumnos=0;
                                                }else{
                                                    $participacion_alumnos=($cantidad_alumnos_participaron/$cantidad_alumnos)*100;
                                                }
                                            //Calculo el porcentaje de participacion de docentes
                                                if(($cantidad_docentes_participaron==0)|| ($cantidad_docentes==0)){
                                                    $participacion_docentes=0;
                                                }else{
                                                    $participacion_docentes=($cantidad_docentes_participaron/$cantidad_docentes)*100;
                                                }
                                            //Calculo el porcentaje de docentes evaluados por el coordinador
                                                $participacion_coordinadores=($cantidad_coordinadores_participaron/$cantidad_docentes)*100;
                                            //Imprimo los resultados
                                                echo "<tr>";
                                                    echo "<td>$carrera</td>";
                                                    echo "<td>".number_format($participacion_alumnos,2)."%</td>";
                                                    echo "<td>".number_format($participacion_docentes,2)."%</td>";
                                                    echo "<td>".number_format($participacion_coordinadores,2)."%</td>";
                                                echo "</tr>";
                                            //
                                        }
                                    //
                                }
                            //
                        ?>
                    </tbody>
                </table>
            <!--Titulo de resultados-->
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <td class="text-center"><h3>RESULTADOS</h3></td>
                        </tr>
                    </thead>
                </table>
            <!--Presentacion-->
                <table class="table table-bordered table-sm" id="tabla_resultados">
                    <thead>
                        <tr>
                            <th>COMPETENCIA</th>
                            <?php
                                //Defino una variable de contador que inicia en 0
                                    $contador=0;
                                //Defino una variable de array para almacenar los resultados
                                    $tabla_2=array();
                                //Defino la sentencia de busqueda de carreras
                                    $sentencia_carreras="SELECT carrera FROM carreras order by carrera asc;";
                                //Ejecuto la sentencia y almaceno lo obtenido en una variable
                                    $resultado_sentencia=retorna_datos($sentencia_carreras);
                                //Identifico si el reultado no es vacio
                                    if ($resultado_sentencia['rowCount'] > 0) {
                                        //Almaceno los datos obtenidos
                                            $resultado = $resultado_sentencia['data'];
                                        // Recorrer los datos y llenar las filas
                                            foreach ($resultado as $tabla) {
                                                //Almaceno los resultados en variables
                                                    $carrera=$tabla['carrera'];
                                                //Imprimo los resultados
                                                    echo "<th class='vertical-text'>$carrera</th>";
                                                //Obtengo los datos de la evaluacion integrada
                                                    $c07 = busca_existencia("SELECT AVG(r08) AS exist FROM lic_profesores_por_coordinadores WHERE carrera like '%$carrera%'");
                                                    $c08 = busca_existencia("SELECT AVG(r09) AS exist FROM lic_profesores_por_coordinadores WHERE carrera like '%$carrera%'");
                                                    $c09 = busca_existencia("SELECT AVG(r10) AS exist FROM lic_profesores_por_coordinadores WHERE carrera like '%$carrera%'");
                                                //Almaceno los datos
                                                    $tabla_2[$contador]['7C']=$c07;
                                                    $tabla_2[$contador]['8C']=$c08;
                                                    $tabla_2[$contador]['9C']=$c09;
                                                //Obtengo los promedios de las evaluaciones de los alumnos para las materias de la carrera del profesor
                                                    $sentencia_promedio_alumnos="
                                                        SELECT 
                                                            ROUND(AVG(respuestas.r01),2) as r01,
                                                            ROUND(AVG(respuestas.r02),2) as r02,
                                                            ROUND(AVG(respuestas.r03),2) as r03,
                                                            ROUND(AVG(respuestas.r04),2) as r04,
                                                            ROUND(AVG(respuestas.r05),2) as r05
                                                        FROM 
                                                            lic_profesores_por_alumnos as respuestas
                                                            JOIN profesores as profesor on respuestas.nombre=profesor.nombre and respuestas.materia=profesor.materia
                                                        WHERE 
                                                            profesor.carrera like '%$carrera%'
                                                        ;
                                                    ";
                                                //Ejecuto la sentencia y almaceno lo obtenido en una variable
                                                    $resultado_sentencia=retorna_datos($sentencia_promedio_alumnos);
                                                //Identifico si el reultado no es vacio
                                                    if ($resultado_sentencia['rowCount'] > 0) {
                                                        //Almaceno los datos obtenidos
                                                            $resultado = $resultado_sentencia['data'];
                                                        // Recorrer los datos y llenar las filas
                                                            foreach ($resultado as $tabla) {
                                                                //Almaceno los resultados en variables
                                                                    $avg_e01=($tabla['r01']/4)*100;
                                                                    $avg_e02=($tabla['r02']/4)*100;
                                                                    $avg_e03=($tabla['r03']/4)*100;
                                                                    $avg_e04=($tabla['r04']/4)*100;
                                                                    $avg_e05=($tabla['r05']/4)*100;

                                                                    $e01=$tabla['r01'];
                                                                    $e02=$tabla['r02'];
                                                                    $e03=$tabla['r03'];
                                                                    $e04=$tabla['r04'];
                                                                    $e05=$tabla['r05'];
                                                                //
                                                            }
                                                        //
                                                    }
                                                //Obtengo el promedio general de la evaluacion de los aplumnos
                                                    $promedio_evaluacion_alumnos=((($avg_e01+$avg_e02+$avg_e03+$avg_e04+$avg_e05)/5)*20)/100;
                                                //Almaceno los datos
                                                    $tabla_2[$contador]['promedio_evaluacion_alumnos']=$promedio_evaluacion_alumnos;
                                                //Obtengo los promedios de las autovaluaciones del profesor en la carrera
                                                    $sentencia_autoevaluacion="
                                                        SELECT 
                                                            ROUND(AVG(r01),2) as r01,
                                                            ROUND(AVG(r02),2) as r02,
                                                            ROUND(AVG(r03),2) as r03,
                                                            ROUND(AVG(r04),2) as r04,
                                                            ROUND(AVG(r05),2) as r05,
                                                            ROUND(AVG(r06),2) as r06,
                                                            ROUND(AVG(r07),2) as r07,
                                                            ROUND(AVG(r08),2) as r08,
                                                            ROUND(AVG(r09),2) as r09,
                                                            ROUND(AVG(r10),2) as r10
                                                        FROM  lic_profesores_por_profesores WHERE carrera like '%$carrera%'
                                                        ;
                                                    ";
                                                //Ejecuto la sentencia y almaceno lo obtenido en una variable
                                                    $resultado_sentencia=retorna_datos($sentencia_autoevaluacion);
                                                //Identifico si el reultado no es vacio
                                                    if ($resultado_sentencia['rowCount'] > 0) {
                                                        //Almaceno los datos obtenidos
                                                            $resultado = $resultado_sentencia['data'];
                                                        // Recorrer los datos y llenar las filas
                                                            foreach ($resultado as $tabla) {
                                                                //Almaceno los resultados en variables
                                                                    $avg_d01=($tabla['r01']/4)*100;
                                                                    $avg_d02=($tabla['r02']/4)*100;
                                                                    $avg_d03=($tabla['r03']/4)*100;
                                                                    $avg_d04=($tabla['r04']/4)*100;
                                                                    $avg_d05=($tabla['r05']/4)*100;
                                                                    $avg_d06=($tabla['r06']/4)*100;
                                                                    $avg_d07=($tabla['r07']/4)*100;
                                                                    $avg_d08=($tabla['r08']/4)*100;
                                                                    $avg_d09=($tabla['r09']/4)*100;
                                                                    $avg_d10=($tabla['r10']/4)*100;

                                                                    $d01=$tabla['r01'];
                                                                    $d02=$tabla['r02'];
                                                                    $d03=$tabla['r03'];
                                                                    $d04=$tabla['r04'];
                                                                    $d05=$tabla['r05'];
                                                                    $d06=$tabla['r06'];
                                                                    $d07=$tabla['r07'];
                                                                    $d08=$tabla['r08'];
                                                                    $d09=$tabla['r09'];
                                                                    $d10=$tabla['r10'];
                                                                //
                                                            }
                                                        //
                                                    }
                                                //Obtengo el promedio general de la evaluacion de los aplumnos
                                                    $promedio_autoevaluacion=((($avg_d01+$avg_d02+$avg_d03+$avg_d04+$avg_d05+$avg_d06+$avg_d07+$avg_d08+$avg_d09+$avg_d10)/10)*10)/100;
                                                //Almaceno los datos
                                                    $tabla_2[$contador]['promedio_autoevaluacion']=$promedio_autoevaluacion;
                                                //Obtengo los promedios de las evaluaciones de los coordinadores para las materias de la carrera del profesor
                                                    $sentencia_promedio_coordinadores="
                                                        SELECT 
                                                            ROUND(AVG(r01),2) as r01,
                                                            ROUND(AVG(r02),2) as r02,
                                                            ROUND(AVG(r03),2) as r03,
                                                            ROUND(AVG(r04),2) as r04,
                                                            ROUND(AVG(r05),2) as r05
                                                        FROM 
                                                            lic_profesores_por_coordinadores WHERE carrera like '%$carrera%'
                                                        ;
                                                    ";
                                                //Ejecuto la sentencia y almaceno lo obtenido en una variable
                                                    $resultado_sentencia=retorna_datos($sentencia_promedio_coordinadores);
                                                //Identifico si el reultado no es vacio
                                                    if ($resultado_sentencia['rowCount'] > 0) {
                                                        //Almaceno los datos obtenidos
                                                            $resultado = $resultado_sentencia['data'];
                                                        // Recorrer los datos y llenar las filas
                                                            foreach ($resultado as $tabla) {
                                                                //Almaceno los resultados en variables
                                                                    $avg_c01=($tabla['r01']/4)*100;
                                                                    $avg_c02=($tabla['r02']/4)*100;
                                                                    $avg_c03=($tabla['r03']/4)*100;
                                                                    $avg_c04=($tabla['r04']/4)*100;
                                                                    $avg_c05=($tabla['r05']/4)*100;

                                                                    $c01=$tabla['r01'];
                                                                    $c02=$tabla['r02'];
                                                                    $c03=$tabla['r03'];
                                                                    $c04=$tabla['r04'];
                                                                    $c05=$tabla['r05'];
                                                                //
                                                            }
                                                        //
                                                    }
                                                //Obtengo el promedio general de la evaluacion de los aplumnos
                                                    $promedio_evaluacion_coordinadores=((($avg_c01+$avg_c02+$avg_c03+$avg_c04+$avg_c05)/5)*25)/100;
                                                //Almaceno los datos
                                                    $tabla_2[$contador]['promedio_evaluacion_coordinadores']=$promedio_evaluacion_coordinadores;
                                                //Hago la sumatoria de los datos
                                                    $porcentaje_total=$c07+$c08+$c09+$promedio_evaluacion_alumnos+$promedio_autoevaluacion+$promedio_evaluacion_coordinadores;
                                                
                                                //Creeo algunas sumatorias de concatenacion
                                                    $prom_com_1=number_format((($avg_e01+$avg_d01)/2),2);
                                                    $tabla_2[$contador]['prom_com_1']=$prom_com_1;
                                                    $prom_com_2=number_format((($avg_e02+$avg_d02)/2),2);
                                                    $tabla_2[$contador]['prom_com_2']=$prom_com_2;
                                                    $prom_com_3=number_format($avg_d03,2);
                                                    $tabla_2[$contador]['prom_com_3']=$prom_com_3;
                                                    $prom_com_4=number_format((($avg_e03+$avg_d05)/2),2);
                                                    $tabla_2[$contador]['prom_com_4']=$prom_com_4;
                                                    $prom_com_5=number_format((($avg_e04+$avg_d06)/2),2);
                                                    $tabla_2[$contador]['prom_com_5']=$prom_com_5;
                                                    $prom_com_6=number_format((($avg_e05+$avg_d07+$avg_c01)/3),2);
                                                    $tabla_2[$contador]['prom_com_6']=$prom_com_6;
                                                    $com_7_doc=number_format((($avg_d04+$avg_d08+$avg_d09+$avg_d10)/4),2);
                                                    $tabla_2[$contador]['com_7_doc']=$com_7_doc;
                                                    $com_7_coo=number_format((($avg_c02+$avg_c03+$avg_c04+$avg_c05)/4),2);
                                                    $tabla_2[$contador]['com_7_coo']=$com_7_coo;
                                                    $prom_com_7=number_format((($com_7_doc+$com_7_coo)/2),2);
                                                    $tabla_2[$contador]['prom_com_7']=$prom_com_7;
                                                    $com_tot_est=number_format((($avg_e01+$avg_e02+$avg_e03+$avg_e04+$avg_e05)/5),2);
                                                    $tabla_2[$contador]['com_tot_est']=$com_tot_est;
                                                    $com_tot_doc=number_format((($avg_d01+$avg_d02+$avg_d03+$avg_d05+$avg_d06+$avg_d07+$com_7_doc)/7),2);
                                                    $tabla_2[$contador]['com_tot_doc']=$com_tot_doc;
                                                    $com_tot_coo=number_format((($avg_c01+$com_7_coo)/2),2);
                                                    $tabla_2[$contador]['com_tot_coo']=$com_tot_coo;
                                                    $prom_com_total=number_format((($prom_com_1+$prom_com_2+$prom_com_3+$prom_com_4+$prom_com_5+$prom_com_6+$prom_com_7)/7),2);
                                                    $tabla_2[$contador]['prom_com_total']=$prom_com_total;
                                                //Aumento el contador
                                                    $contador++;
                                                //
                                                }
                                        //
                                    }
                                //
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            //Obtengo la cantidad de columnas
                                $cantidad_columnas=count($tabla_2);
                            //Abro la linea 
                                echo "<tr>";
                            //Defino la prinera linea
                                echo "<td>CAPACITACION</td>";
                                //Recorro el array y muestro los resultados
                                    for ($i=0; $i < $contador; $i++) { 
                                        //Almaceno el resultado en una variable
                                            $resultado=$tabla_2[$i]['7C'];
                                        //Imprimo el resultado
                                            echo "<td>".number_format($resultado,2)."</td>";
                                        //
                                    }
                                //
                            //Cierro la linea
                                echo "</tr>";
                            //Abro la linea 
                                echo "<tr>";
                            //Defino la prinera linea
                                echo "<td>ENTREGA Y EVALUACION DE LA PLANEACION DIDACTICA</td>";
                                //Recorro el array y muestro los resultados
                                    for ($i=0; $i < $contador; $i++) { 
                                        //Almaceno el resultado en una variable
                                            $resultado=$tabla_2[$i]['8C'];
                                        //Imprimo el resultado
                                            echo "<td>".number_format($resultado,2)."</td>";
                                        //
                                    }
                                //
                            //Cierro la linea
                                echo "</tr>";
                            //Abro la linea 
                                echo "<tr>";
                            //Defino la prinera linea
                                echo "<td>ACOMPAÑAMIENTO DEL PROCESO ENSEÑANZA-APRENDIZAJE</td>";
                                //Recorro el array y muestro los resultados
                                    for ($i=0; $i < $contador; $i++) { 
                                        //Almaceno el resultado en una variable
                                            $resultado=$tabla_2[$i]['9C'];
                                        //Imprimo el resultado
                                            echo "<td>".number_format($resultado,2)."</td>";
                                        //
                                    }
                                //
                            //Cierro la linea
                                echo "</tr>";
                            //Abro la linea 
                                echo "<tr>";
                            //Defino la prinera linea
                                echo "<td>EVALUACIÓN POR PARTE DE ESTUDIANTES</td>";
                                //Recorro el array y muestro los resultados
                                    for ($i=0; $i < $contador; $i++) { 
                                        //Almaceno el resultado en una variable
                                            $resultado=$tabla_2[$i]['promedio_evaluacion_alumnos'];
                                        //Imprimo el resultado
                                            echo "<td>".number_format($resultado,2)."</td>";
                                        //
                                    }
                                //
                            //Cierro la linea
                                echo "</tr>";
                                //Abro la linea 
                                echo "<tr>";
                                //Defino la prinera linea
                                    echo "<td>AUTOEVALUACION</td>";
                                    //Recorro el array y muestro los resultados
                                        for ($i=0; $i < $contador; $i++) { 
                                            //Almaceno el resultado en una variable
                                                $resultado=$tabla_2[$i]['promedio_autoevaluacion'];
                                            //Imprimo el resultado
                                                echo "<td>".number_format($resultado,2)."</td>";
                                            //
                                        }
                                    //
                                //Cierro la linea
                                    echo "</tr>";
                            //Abro la linea 
                                echo "<tr>";
                            //Defino la prinera linea
                                echo "<td>EVALUACION POR PARTE DE LA COORDINACION</td>";
                                //Recorro el array y muestro los resultados
                                    for ($i=0; $i < $contador; $i++) { 
                                        //Almaceno el resultado en una variable
                                            $resultado=$tabla_2[$i]['promedio_evaluacion_coordinadores'];
                                        //Imprimo el resultado
                                            echo "<td>".number_format($resultado,2)."</td>";
                                        //
                                    }
                                //
                            //Cierro la linea
                                echo "</tr>";
                            //Abro la linea 
                            echo "<tr>";
                            //Defino la prinera linea
                                echo "<td>1.- DOMINIO DE LA DISCIPLINA</td>";
                                //Recorro el array y muestro los resultados
                                    for ($i=0; $i < $contador; $i++) { 
                                        //Almaceno el resultado en una variable
                                            $resultado=$tabla_2[$i]['prom_com_1'];
                                        //Imprimo el resultado
                                            echo "<td>".number_format($resultado,2)."</td>";
                                        //
                                    }
                                //
                            //Cierro la linea
                                echo "</tr>";
                            //Abro la linea 
                                echo "<tr>";
                            //Defino la prinera linea
                                echo "<td>2.- VINCULACION PROFESIONAL</td>";
                                //Recorro el array y muestro los resultados
                                    for ($i=0; $i < $contador; $i++) { 
                                        //Almaceno el resultado en una variable
                                            $resultado=$tabla_2[$i]['prom_com_2'];
                                        //Imprimo el resultado
                                            echo "<td>".number_format($resultado,2)."</td>";
                                        //
                                    }
                                //
                            //Cierro la linea
                                echo "</tr>";
                            //Abro la linea 
                                echo "<tr>";
                            //Defino la prinera linea
                                echo "<td>3.- COMUNICACION</td>";
                                //Recorro el array y muestro los resultados
                                    for ($i=0; $i < $contador; $i++) { 
                                        //Almaceno el resultado en una variable
                                            $resultado=$tabla_2[$i]['prom_com_3'];
                                        //Imprimo el resultado
                                            echo "<td>".number_format($resultado,2)."</td>";
                                        //
                                    }
                                //
                            //Cierro la linea
                                echo "</tr>";
                            //Abro la linea 
                                echo "<tr>";
                            //Defino la prinera linea
                                echo "<td>4.- MEDIACION DE LOS APRENDIZAJES</td>";
                                //Recorro el array y muestro los resultados
                                    for ($i=0; $i < $contador; $i++) { 
                                        //Almaceno el resultado en una variable
                                            $resultado=$tabla_2[$i]['prom_com_4'];
                                        //Imprimo el resultado
                                            echo "<td>".number_format($resultado,2)."</td>";
                                        //
                                    }
                                //
                            //Cierro la linea
                                echo "</tr>";
                            //Abro la linea 
                                echo "<tr>";
                            //Defino la prinera linea
                                echo "<td>5.- EVALUACION DE LOS APRENDIZAJES</td>";
                                //Recorro el array y muestro los resultados
                                    for ($i=0; $i < $contador; $i++) { 
                                        //Almaceno el resultado en una variable
                                            $resultado=$tabla_2[$i]['prom_com_5'];
                                        //Imprimo el resultado
                                            echo "<td>".number_format($resultado,2)."</td>";
                                        //
                                    }
                                //
                            //Cierro la linea
                                echo "</tr>";
                            //Abro la linea 
                                echo "<tr>";
                            //Defino la prinera linea
                                echo "<td>6.- GENERACION DE AMBIENTES SEGUROS</td>";
                                //Recorro el array y muestro los resultados
                                    for ($i=0; $i < $contador; $i++) { 
                                        //Almaceno el resultado en una variable
                                            $resultado=$tabla_2[$i]['prom_com_6'];
                                        //Imprimo el resultado
                                            echo "<td>".number_format($resultado,2)."</td>";
                                        //
                                    }
                                //
                            //Cierro la linea
                                echo "</tr>";
                            //Abro la linea 
                                echo "<tr>";
                            //Defino la prinera linea
                                echo "<td>7.- COMPROMISO INSTITUCIONAL</td>";
                                //Recorro el array y muestro los resultados
                                    for ($i=0; $i < $contador; $i++) { 
                                        //Almaceno el resultado en una variable
                                            $resultado=$tabla_2[$i]['prom_com_7'];
                                        //Imprimo el resultado
                                            echo "<td>".number_format($resultado,2)."</td>";
                                        //
                                    }
                                //
                            //Cierro la linea
                                echo "</tr>";
                            //
                        ?>
                    </tbody>
                </table>
            <!---->
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
            var table = $('#tabla_participacion').DataTable( {
                responsive: true,
                // Mantener el orden del HTML tal cual: desactivar ordenamiento automático
                        ordering: false,
                        order: [],
                // Mostrar más filas por página y ofrecer opciones
                        pageLength: 25,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
                language: {
                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "Ningún dato disponible en esta tabla",
                    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sSearch":         "Buscar:",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
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
  //Funcion para la tabla
    $(document).ready( function () {
            var table = $('#tabla_resultados').DataTable( {
                responsive: true,
                // Mantener el orden del HTML tal cual: desactivar ordenamiento automático
                        ordering: false,
                        order: [],
                // Mostrar más filas por página y ofrecer opciones
                        pageLength: 25,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
                language: {
                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "Ningún dato disponible en esta tabla",
                    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sSearch":         "Buscar:",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
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