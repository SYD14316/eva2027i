<?php 
    //Se incluye el archivo de funciones
	    include_once "funciones.php";
    //Se inicia la sesion
	    session_start();
    //Recibo los datos enviados por el formulario
	    $nombre_docente=$_GET['nombre'];
        $carrera=$_GET['carrera'];
    //Obtengo el nombre de la jefatura por medio de la carrera
        $sentencia_nombre_jefatura="SELECT departamento AS exist FROM carreras WHERE carrera like '%$carrera%'";
        $nombre_jefatura=busca_existencia($sentencia_nombre_jefatura);
        $sentencia_nombre_jefe="SELECT jefe AS exist FROM departamentos WHERE departamento='$nombre_jefatura'";
        $nombre_jefe=busca_existencia($sentencia_nombre_jefe);
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
	<title><?PHP echo $nombre_docente; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous" >

	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="card">
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
                        <td>DOCENTE</td>
                        <td><?PHP echo $nombre_docente; ?></td>
                    </tr>
                    <tr>
                        <td>CARRERA</td>
                        <td><?PHP echo $carrera; ?></td>
                    </tr>
                    <tr>
                        <td>RESULTADO DE EVALUACION INTEGRADA</td>
                        <td id="resulta_evaluacion_total"></td>
                    </tr>
                </table>
                <?php
                    //Obtengo los datos de la evaluacion integrada
                        $c08 = busca_existencia("SELECT sum(r08) AS exist FROM lic_profesores_por_coordinadores WHERE nombre='$nombre_docente' AND carrera like '%$carrera%'");
                        $c09 = busca_existencia("SELECT sum(r09) AS exist FROM lic_profesores_por_coordinadores WHERE nombre='$nombre_docente' AND carrera like '%$carrera%'");
                        $c10 = busca_existencia("SELECT sum(r10) AS exist FROM lic_profesores_por_coordinadores WHERE nombre='$nombre_docente' AND carrera like '%$carrera%'");
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
                                profesor.nombre='$nombre_docente' AND profesor.carrera like '%$carrera%'
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
                            FROM  lic_profesores_por_profesores WHERE nombre='$nombre_docente' AND carrera like '%$carrera%'
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
                    //Obtengo los promedios de las evaluaciones de los coordinadores para las materias de la carrera del profesor
                        $sentencia_promedio_coordinadores="
                            SELECT 
                                ROUND(AVG(r01),2) as r01,
                                ROUND(AVG(r02),2) as r02,
                                ROUND(AVG(r03),2) as r03,
                                ROUND(AVG(r04),2) as r04,
                                ROUND(AVG(r05),2) as r05
                            FROM 
                                lic_profesores_por_coordinadores WHERE nombre='$nombre_docente' AND carrera like '%$carrera%'
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
                    //Hago la sumatoria de los datos
                        $porcentaje_total=$c08+$c09+$c10+$promedio_evaluacion_alumnos+$promedio_autoevaluacion+$promedio_evaluacion_coordinadores;
                    //
                        echo "
                            <script>
                                $('#resulta_evaluacion_total').html(".number_format($porcentaje_total,2)."+' %');
                            </script>
                        ";
                    //
                ?>
            <!--Presentacion-->
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <td class="text-center"><h1>EVALUACION INTEGRADA</h1></td>
                        </tr>
                        <tr>
                            <td>La evaluación integrada presenta los resultados obtenidos en cada una de las etapas de la Ruta de Desarrollo Integral Docente. Puede haber variaciones respecto a la evaluación de competencias debido a la ponderación diferenciada.</td>
                        </tr>
                    </thead>
                </table>
            <!--Tabla de evaluacion por conceptos-->
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <td><strong>CONCEPTO</strong></td>
                            <td><strong>PONDERACION</strong></td>
                            <td><strong>RESULTADO<BR>OBTENIDO</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>CAPACITACION</strong></td>
                            <td>10 %</td>
                            <td><?PHP ECHO number_format($c08,2); ?> %</td>
                        </tr>
                        <tr>
                            <td><strong>ENTREGA Y EVALUACION DE LA PLANEACION DIDACTICA</strong></td>
                            <td>15 %</td>
                            <td><?PHP ECHO number_format($c09,2); ?> %</td>
                        </tr>
                        <tr>
                            <td><strong>ACOMPAÑAMIENTO DEL PROCESO DE ENSEÑANZA APRENDIZAJE</strong></td>
                            <td>20 %</td>
                            <td><?PHP ECHO number_format($c10,2); ?> %</td>
                        </tr>
                        <tr>
                            <td><strong>EVALUACIÓN POR PARTE DE ESTUDIANTES</strong></td>
                            <td>20 %</td>
                            <td><?PHP ECHO number_format($promedio_evaluacion_alumnos,2); ?> %</td>
                        </tr>
                        <tr>
                            <td><strong>AUTOEVALUACIÓN</strong></td>
                            <td>10 %</td>
                            <td><?PHP ECHO  number_format($promedio_autoevaluacion,2); ?> %</td>
                        </tr>
                        <tr>
                            <td><strong>EVALUACIÓN POR PARTE DE LA COORDINACION</strong></td>
                            <td>25 %</td>
                            <td><?PHP ECHO  number_format($promedio_evaluacion_coordinadores,2); ?> %</td>
                        </tr>
                        <tr>
                            <td><strong>TOTAL</strong></td>
                            <td><strong>100 %</strong></td>
                            <td><strong><?PHP ECHO  number_format($porcentaje_total,2); ?> %</strong></td>
                        </tr>
                    </tbody>
                </table>
            <!--Tabla de evaluacion por competencias-->
                <?php
                    $prom_com_1=number_format((($avg_e01+$avg_d01)/2),2);
                    $prom_com_2=number_format((($avg_e02+$avg_d02)/2),2);
                    $prom_com_3=number_format($avg_d03,2);
                    $prom_com_4=number_format((($avg_e03+$avg_d05)/2),2);
                    $prom_com_5=number_format((($avg_e04+$avg_d06)/2),2);
                    $prom_com_6=number_format((($avg_e05+$avg_d07+$avg_c01)/3),2);
                    $com_7_doc=number_format((($avg_d04+$avg_d08+$avg_d09+$avg_d10)/4),2);
                    $com_7_coo=number_format((($avg_c02+$avg_c03+$avg_c04+$avg_c05)/4),2);
                    $prom_com_7=number_format((($com_7_doc+$com_7_coo)/2),2);
                    $com_tot_est=number_format((($avg_e01+$avg_e02+$avg_e03+$avg_e04+$avg_e05)/5),2);
                    $com_tot_doc=number_format((($avg_d01+$avg_d02+$avg_d03+$avg_d05+$avg_d06+$avg_d07+$com_7_doc)/7),2);
                    $com_tot_coo=number_format((($avg_c01+$com_7_coo)/2),2);
                    $prom_com_total=number_format((($prom_com_1+$prom_com_2+$prom_com_3+$prom_com_4+$prom_com_5+$prom_com_6+$prom_com_7)/7),2);
                ?>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <td class="text-center"><h1>EVALUACION POR COMPETENCIAS</h1></td>
                        </tr>
                    </thead>
                </table>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <td><strong>COMPETENCIA</strong></td>
                            <td><strong>DESCRIPCION</strong></td>
                            <td><strong>ESTUDIANTE</strong></td>
                            <td><strong>DOCENTE</strong></td>
                            <td><strong>COORDINACION</strong></td>
                            <td><strong>RESULTADOS POR COMPETENCIA</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1.Dominio de la disciplina.</td>
                            <td>Conoce y explica a profundidad los conceptos, teorías, modelos, principios o prácticas del campo disciplinar de su especialidad.</td>
                            <td><?php echo number_format($avg_e01,2); ?> %</td>
                            <td><?php echo number_format($avg_d01,2); ?> %</td>
                            <td class="bg-secondary"></td>
                            <td><?php echo $prom_com_1 ?> %</td>
                        </tr>
                        <tr>
                            <td>2.Vinculación profesional.</td>
                            <td>Tiene experiencia en su campo profesional y la relaciona con los procesos de aprendizaje de las y los estudiantes.</td>
                            <td><?php echo number_format($avg_e02,2); ?> %</td>
                            <td><?php echo number_format($avg_d02,2); ?> %</td>
                            <td class="bg-secondary"></td>
                            <td><?php echo $prom_com_2; ?> %</td>
                        </tr>
                        <tr>
                            <td>3.Comunicación.</td>
                            <td>Se comunica eficazmente con el grupo. </td>
                            <td class="bg-secondary"></td>
                            <td><?php echo number_format($avg_d03,2); ?> %</td>
                            <td class="bg-secondary"></td>
                            <td><?php echo $prom_com_3; ?> %</td>
                        </tr>
                        <tr>
                            <td>4.Mediación de los aprendizajes.</td>
                            <td>En su actividad en el aula demuestra manejo de grupo, organización, liderazgo, capacidad de implementar actividades y recursos diversos y congruentes con los objetivos de aprendizaje.</td>
                            <td><?php echo number_format($avg_e03,2); ?> %</td>
                            <td><?php echo number_format($avg_d05,2); ?> %</td>
                            <td class="bg-secondary"></td>
                            <td><?php echo $prom_com_4; ?> %</td>
                        </tr>
                        <tr>
                            <td>5.Evaluación de los aprendizajes.</td>
                            <td>Anticipa y presenta criterios de evaluación claros, evalúa de manera integral y brinda retroalimentación oportuna.</td>
                            <td><?php echo number_format($avg_e04,2); ?> %</td>
                            <td><?php echo number_format($avg_d06,2); ?> %</td>
                            <td class="bg-secondary"></td>
                            <td><?php echo $prom_com_5; ?> %</td>
                        </tr>
                        <tr>
                            <td>6.Generación de ambientes seguros.</td>
                            <td>Promueve un ambiente respetuoso y libre de violencias, lo que favorece el diálogo y la sana convivencia . Se conduce con respeto hacia todas las personas de la comunidad educativa.</td>
                            <td><?php echo number_format($avg_e05,2); ?> %</td>
                            <td><?php echo number_format($avg_d07,2); ?> %</td>
                            <td><?php echo number_format($avg_c01,2); ?> %</td>
                            <td><?php echo $prom_com_6; ?> %</td>
                        </tr>
                        <tr>
                            <td>7.Compromiso institucional</td>
                            <td>Promueve los valores maristas, se conduce con honorabilidad e integridad, participa en reuniones, cumple compromisos administrativos y académicos.</td>
                            <td class="bg-secondary"></td>
                            <td><?php echo $com_7_doc; ?> %</td>
                            <td><?php echo $com_7_coo; ?> %</td>
                            <td><?php echo $prom_com_7; ?> %</td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>TOTALES</strong></td>
                            <td><strong><?php echo $com_tot_est; ?> %</strong></td>
                            <td><strong><?php echo $com_tot_doc; ?> %</strong></td>
                            <td><strong><?php echo $com_tot_coo; ?> %</strong></td>
                            <td><strong><?php echo $prom_com_total; ?> %</strong></td>
                        </tr>
                    </tbody>
                </table>
            <!--Tabla de comentarios de alumnos-->
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <td class="text-center"><strong>COMENTARIOS DE ESTUDIANTES</strong></td>
                        </tr>
                    </thead>
                    <?php
                        //Obtengo los promedios de las evaluaciones de los alumnos para las materias de la carrera del profesor
                            $sentencia_promedio_alumnos="
                                SELECT DISTINCT
                                    respuestas.r06 as r06
                                FROM 
                                    lic_profesores_por_alumnos as respuestas
                                JOIN profesores as profesor on respuestas.nombre=profesor.nombre and respuestas.materia=profesor.materia
                                WHERE 
                                    profesor.nombre='$nombre_docente' AND profesor.carrera like '%$carrera%' and respuestas.r06<>''
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
                                            $e06=$tabla['r06'];
                                        //Imprimo la pantalla
                                            echo "
                                                <tr>
                                                    <td>$e06</td>
                                                </tr>
                                            ";
                                        //
                                    }
                                //
                            }
                        //
                    ?>
                </table>
            <!--Tabla de comentarios del docente-->
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <td class="text-center"><strong>FORTALEZAS</strong></td>
                        </tr>
                    </thead>
                    <?php
                        //Obtengo los promedios de las evaluaciones de los coordinadores para las materias de la carrera del profesor
                           $sentencia_autoevaluacion=" SELECT DISTINCT r11 FROM lic_profesores_por_profesores WHERE nombre='$nombre_docente' AND carrera like '%$carrera%';";
                        //Ejecuto la sentencia y almaceno lo obtenido en una variable
                            $resultado_sentencia=retorna_datos($sentencia_autoevaluacion);
                        //Identifico si el reultado no es vacio
                            if ($resultado_sentencia['rowCount'] > 0) {
                                //Almaceno los datos obtenidos
                                    $resultado = $resultado_sentencia['data'];
                                // Recorrer los datos y llenar las filas
                                    foreach ($resultado as $tabla) {
                                        //Almaceno los resultados en variables
                                            $d11=$tabla['r11'];
                                        //Imprimo la pantalla
                                            echo "
                                                <tr>
                                                    <td><strong>DOCENTE:</strong> $d11</td>
                                                </tr>
                                            ";
                                        //
                                    }
                                //
                            }
                        //
                        //Obtengo los promedios de las evaluaciones de los coordinadores para las materias de la carrera del profesor
                           $sentencia_autoevaluacion=" SELECT DISTINCT r06 FROM lic_profesores_por_coordinadores WHERE nombre='$nombre_docente' AND carrera like '%$carrera%';";
                        //Ejecuto la sentencia y almaceno lo obtenido en una variable
                            $resultado_sentencia=retorna_datos($sentencia_autoevaluacion);
                        //Identifico si el reultado no es vacio
                            if ($resultado_sentencia['rowCount'] > 0) {
                                //Almaceno los datos obtenidos
                                    $resultado = $resultado_sentencia['data'];
                                // Recorrer los datos y llenar las filas
                                    foreach ($resultado as $tabla) {
                                        //Almaceno los resultados en variables
                                            $d06=$tabla['r06'];
                                        //Imprimo la pantalla
                                            echo "
                                                <tr>
                                                    <td><strong>COORDINADOR:</strong> $d06</td>
                                                </tr>
                                            ";
                                        //
                                    }
                                //
                            }
                        //
                    ?>
                </table>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <td class="text-center"><strong>AREAS DE MEJORA</strong></td>
                        </tr>
                    </thead>
                    <?php
                        //Obtengo los promedios de las evaluaciones de los coordinadores para las materias de la carrera del profesor
                           $sentencia_autoevaluacion=" SELECT DISTINCT r12 FROM lic_profesores_por_profesores WHERE nombre='$nombre_docente' AND carrera like '%$carrera%';";
                        //Ejecuto la sentencia y almaceno lo obtenido en una variable
                            $resultado_sentencia=retorna_datos($sentencia_autoevaluacion);
                        //Identifico si el reultado no es vacio
                            if ($resultado_sentencia['rowCount'] > 0) {
                                //Almaceno los datos obtenidos
                                    $resultado = $resultado_sentencia['data'];
                                // Recorrer los datos y llenar las filas
                                    foreach ($resultado as $tabla) {
                                        //Almaceno los resultados en variables
                                            $d12=$tabla['r12'];
                                        //Imprimo la pantalla
                                            echo "
                                                <tr>
                                                    <td><strong>DOCENTE:</strong> $d12</td>
                                                </tr>
                                            ";
                                        //
                                    }
                                //
                            }
                        //
                        //Obtengo los promedios de las evaluaciones de los coordinadores para las materias de la carrera del profesor
                           $sentencia_autoevaluacion=" SELECT DISTINCT r07 FROM lic_profesores_por_coordinadores WHERE nombre='$nombre_docente' AND carrera like '%$carrera%';";
                        //Ejecuto la sentencia y almaceno lo obtenido en una variable
                            $resultado_sentencia=retorna_datos($sentencia_autoevaluacion);
                        //Identifico si el reultado no es vacio
                            if ($resultado_sentencia['rowCount'] > 0) {
                                //Almaceno los datos obtenidos
                                    $resultado = $resultado_sentencia['data'];
                                // Recorrer los datos y llenar las filas
                                    foreach ($resultado as $tabla) {
                                        //Almaceno los resultados en variables
                                            $d07=$tabla['r07'];
                                        //Imprimo la pantalla
                                            echo "
                                                <tr>
                                                    <td><strong>COORDINADOR:</strong> $d07</td>
                                                </tr>
                                            ";
                                        //
                                    }
                                //
                            }
                        //
                    ?>
                </table>
            <!--Tabla de comentarios del coordinador<>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <td class="text-center"><strong>COMENTARIOS DE LA COORDINACION</strong></td>
                        </tr>
                        <?php
                            //Obtengo los promedios de las evaluaciones de los coordinadores para las materias de la carrera del profesor
                                $sentencia_promedio_coordinadores="
                                    SELECT DISTINCT r06 FROM 
                                        lic_profesores_por_coordinadores WHERE nombre='$nombre_docente' AND carrera like '%$carrera%'
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
                                                $c06=$tabla['r06'];
                                            //Imprimo la pantalla
                                                echo "
                                                    <tr>
                                                        <td>$c06</td>
                                                    </tr>
                                                ";
                                            //
                                        }
                                    //
                                }
                            //
                        ?>
                    </thead>
                </table>
            <Despedida-->
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <td>
                                Le invitamos a reflexionar sobre estos resultados y a identificar posibles áreas de mejora como una oportunidad para fortalecer su práctica docente. En la parte inferior encontrará un espacio destinado a la formulación de sus compromisos para la mejora integral.
                                <br>
                                <br>
                                Agradecemos profundamente su compromiso con la excelencia educativa. Quedamos a su disposición para brindarle el apoyo adicional que considere necesario.
                                <br>
                                <br>
                                Atentamente.
                                <br>
                                <br>
                                <p class="text-center"><?php echo "$nombre_jefe<br><strong>JEFATURA DE $nombre_jefatura</strong>"; ?></p>
                            </td>
                        </tr>
                    </thead>
                </table>
            <!--Compromisos-->
                <table class="table table-bordered table-sm">
                    <tr>
                        <td>
                            Compromisos para la mejora integral:
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                          <br>
                          <br>
                          <br>
                          ____________________________________________________
                          <br>
                            <?php echo "<strong>$nombre_docente</strong>"; ?>
                          <br>
                            Firma del docente
                        </td>
                    </tr>
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