<?php 
    //Se incluye el archivo de funciones
	    include_once "funciones.php";
    //Se inicia la sesion
	    session_start();
    //Obtengo el nombre de la carrera
	    $carrera=$_POST['parametros'];
        if($carrera=="MATERIAS INSTITUCIONALES"){
            
        }else{}
    //Obtengo el nombre del coordinador
        $coordinador=busca_existencia("SELECT coordinador AS exist FROM carreras where  carrera='$carrera'");
    //Obtengo la jefatura a la que pertenece
        $jefatura=busca_existencia("SELECT departamento AS exist FROM carreras where  carrera='$carrera'");
    //Obtengo los datos de la evaluacion integrada
        $trc_1 = busca_existencia("SELECT IFNULL(AVG(CASE WHEN TRIM(r08) REGEXP '^[0-9]+([.][0-9]+)?$' THEN CAST(TRIM(r08) AS DECIMAL(10,2)) END),0) AS exist FROM lic_profesores_por_coordinadores where carrera LIKE '%$carrera%'");
    //Obtengo el promedio general de todas las respuestas de estudiantes para esta materia
        if($carrera=="MATERIAS INSTITUCIONALES"){
                $sentencia_promedio_alumnos="
                SELECT 
                    IFNULL(
                        (
                            AVG(respuestas.r01) +
                            AVG(respuestas.r02) +
                            AVG(respuestas.r03) +
                            AVG(respuestas.r04) +
                            AVG(respuestas.r05)
                        ) / 5,
                        0
                    ) as promedio_general
                FROM 
                    lic_profesores_por_alumnos as respuestas
                WHERE 
                    respuestas.sello=1
                ;
            ";
        }elseif($carrera=="IDIOMAS"){
            $sentencia_promedio_alumnos="
                SELECT 
                    IFNULL(
                        (
                            AVG(respuestas.r01) +
                            AVG(respuestas.r02) +
                            AVG(respuestas.r03) +
                            AVG(respuestas.r04) +
                            AVG(respuestas.r05)
                        ) / 5,
                        0
                    ) as promedio_general
                FROM 
                    lic_profesores_por_alumnos as respuestas
                WHERE 
                    respuestas.grupo LIKE 'TIDIOMAS_%'
                ;
            ";
        }else{
            $sentencia_promedio_alumnos="
                SELECT 
                    IFNULL(
                        (
                            AVG(respuestas.r01) +
                            AVG(respuestas.r02) +
                            AVG(respuestas.r03) +
                            AVG(respuestas.r04) +
                            AVG(respuestas.r05)
                        ) / 5,
                        0
                    ) as promedio_general
                FROM 
                    lic_profesores_por_alumnos as respuestas
                WHERE 
                    respuestas.carrera='$carrera'
                ;
            ";
        }
    //Ejecuto la sentencia y almaceno lo obtenido en una variable
        $resultado_sentencia=retorna_datos($sentencia_promedio_alumnos);
    //Valor por defecto cuando no hay datos
        $promedio_evaluacion_alumnos = 0;
    //Identifico si el resultado no es vacio
        if ($resultado_sentencia['rowCount'] > 0) {
            //Almaceno los datos obtenidos
                $resultado = $resultado_sentencia['data'];
            // Recorrer los datos y llenar las filas
                foreach ($resultado as $tabla) {
                    //Convierto al ponderado: promedio * 35 / 4, con 1 decimal
                        $promedio_evaluacion_alumnos = round(($tabla['promedio_general'] * 35) / 4, 1);
                    //
                }
            //
        }
    //Obtengo el promedio general de todas las respuestas de autoevaluacion para esta materia
        $sentencia_autoevaluacion="
            SELECT 
                IFNULL(
                    (
                        AVG(r01) +
                        AVG(r02) +
                        AVG(r03) +
                        AVG(r04) +
                        AVG(r05) +
                        AVG(r06) +
                        AVG(r07) +
                        AVG(r08) +
                        AVG(r09) +
                        AVG(r10)
                    ) / 10,
                    0
                ) as promedio_general
            FROM lic_profesores_por_profesores
            WHERE carrera='$carrera'
            ;
        ";
    //Ejecuto la sentencia y almaceno lo obtenido en una variable
        $resultado_sentencia=retorna_datos($sentencia_autoevaluacion);
    //Valor por defecto cuando no hay datos
        $promedio_autoevaluacion = 0;
    //Identifico si el resultado no es vacio
        if ($resultado_sentencia['rowCount'] > 0) {
            //Almaceno los datos obtenidos
                $resultado = $resultado_sentencia['data'];
            // Recorrer los datos y llenar las filas
                foreach ($resultado as $tabla) {
                    //Convierto al ponderado: promedio * 15 / 4, con 1 decimal
                        $promedio_autoevaluacion = round(($tabla['promedio_general'] * 15) / 4, 1);
                    //
                }
            //
        }
    //Obtengo el promedio general de todas las respuestas del coordinador para este docente en esta materia
        $sentencia_promedio_coordinadores="
            SELECT 
                IFNULL(
                    (
                        AVG(r01) +
                        AVG(r02) +
                        AVG(r03) +
                        AVG(r04) +
                        AVG(r05)
                    ) / 5,
                    0
                ) as promedio_general
            FROM 
                lic_profesores_por_coordinadores
            WHERE carrera LIKE '%$carrera%'
            ;
        ";
    //Ejecuto la sentencia y almaceno lo obtenido en una variable
        $resultado_sentencia=retorna_datos($sentencia_promedio_coordinadores);
    //Valor por defecto cuando no hay datos
        $promedio_evaluacion_coordinadores = 0;
    //Identifico si el resultado no es vacio
        if ($resultado_sentencia['rowCount'] > 0) {
            //Almaceno los datos obtenidos
                $resultado = $resultado_sentencia['data'];
            // Recorrer los datos y llenar las filas
                foreach ($resultado as $tabla) {
                    //Convierto al ponderado: promedio * 35 / 4, con 1 decimal
                        $promedio_evaluacion_coordinadores = round(($tabla['promedio_general'] * 35) / 4, 1);
                    //
                }
            //
        }
    //Suma total de los ponderados, con 1 decimal
        $total_evaluacion = round(
            $trc_1 +
            $promedio_evaluacion_alumnos +
            $promedio_autoevaluacion +
            $promedio_evaluacion_coordinadores,
            1
        );
    //Obtengo la cantidad de docentes ligados a la carrera
        $cantidad_docentes = busca_existencia("SELECT COUNT(DISTINCT nombre) AS exist FROM profesores WHERE carrera='$carrera'");
    //Cabecera del documento
        $header="RESULTADOS POR CARRERA PARA $carrera";
    //

?>
<!DOCTYPE html>
<html>
    <?php include_once "header.php"; ?>
    <body>
        <div class="card">
            <div class="card-body">
                <!--Botón para imprimir la página-->
                    <div class="text-right mb-3">
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print"></i> Imprimir
                        </button>
                    </div>

                <!--Tabla de datos principal-->
                    <table class="table table-bordered table-sm text-center">
                        <tr>
                            <td><strong>FECHA</strong></td>
                            <td><?PHP echo mb_strtoupper(transforma_fecha(ahora(1),1," DE ")); ?></td>
                        </tr>
                        <tr>
                            <td><strong>CARRERA</strong></td>
                            <td><?PHP echo $carrera; ?></td>
                        </tr>
                        <tr>
                            <td><strong>COORDINADOR O COORDINADORA</strong></td>
                            <td><?PHP echo $coordinador; ?></td>
                        </tr>
                        <tr>
                            <td><strong>TOTAL EVALUACIÓN INTEGRADA POR CARRERA</strong></td>
                            <td><?PHP echo $total_evaluacion; ?></td>
                        </tr>
                    </table>

                <!--Tabla de resultados de evaluacion integrada-->
                    <div class='card'>
                        <div class="card-text text-center">
                            <p>
                                EVALUACIÓN INTEGRADA
                                <br>
                                Se presentan los resultados obtenidos por el conjunto de las y los docentes que integran una misma carrera, considerando el promedio de valoraciones a partir de los resultados obtenidos según el agente evaluador y agregando de manera directa su resultado en la capacitación.
                            </p>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>CONCEPTO</th>
                                        <th>PONDERACION</th>
                                        <th>RESULTADO OBTENIDO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>CAPACITACION</strong></td>
                                        <td><strong>15%</strong></td>
                                        <td><strong><?php echo number_format($trc_1, 1); ?>%</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>ESTUDIANTES</strong></td>
                                        <td><strong>35%</strong></td>
                                        <td><strong><?php echo number_format($promedio_evaluacion_alumnos,1); ?>%</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>AUTOEVALUACION</strong></td>
                                        <td><strong>15%</strong></td>
                                        <td><strong><?php echo number_format($promedio_autoevaluacion,1); ?>%</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>COORDINACION</strong></td>
                                        <td><strong>35%</strong></td>
                                        <td><strong><?php echo number_format($promedio_evaluacion_coordinadores,1); ?>%</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>TOTAL EVALUACIÓN INTEGRADA POR CARRERA</strong></td>
                                        <td><strong>100%</strong></td>
                                        <td><strong><?php echo number_format($total_evaluacion,1); ?>%</strong></td>
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                <!--Tabla de resultados de las y los docentes-->
                    <div class='card'>
                        <div class="card-text text-center">
                            <p>
                                RESULTADOS DE LAS Y LOS DOCENTES
                                <br>
                                En caso de que un docente imparta clases en más de una materia o grupo pero dentro de una misma carrera, los resultados específicos se promediarán.
                            </p>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>NOMBRE</th>
                                        <th>CAPACITACION</th>
                                        <th>ESTUDIANTES</th>
                                        <th>DOCENTES</th>
                                        <th>COORDINACION</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        //Obtengo el promedio general de todas las respuestas del coordinador para este docente en esta materia
                                            $nombres_docentes="SELECT DISTINCT nombre FROM profesores WHERE carrera='$carrera'";
                                        //Ejecuto la sentencia y almaceno lo obtenido en una variable
                                            $resultado_sentencia=retorna_datos($nombres_docentes);
                                        //Arreglo para almacenar los resultados
                                            $resultados_docentes = [];
                                        //Identifico si el resultado no es vacio
                                            if ($resultado_sentencia['rowCount'] > 0) {
                                                //Almaceno los datos obtenidos
                                                    $resultado = $resultado_sentencia['data'];
                                                // Recorrer los datos y llenar las filas
                                                    foreach ($resultado as $tabla) {
                                                        //Convierto al ponderado: promedio * 35 / 4, con 1 decimal
                                                            $nombre_docente = $tabla['nombre'];
                                                        //Obtengo los datos de la evaluacion integrada
                                                            $res_capacitacion = busca_existencia("SELECT IFNULL(AVG(CASE WHEN TRIM(r08) REGEXP '^[0-9]+([.][0-9]+)?$' THEN CAST(TRIM(r08) AS DECIMAL(10,2)) END),0) AS exist FROM lic_profesores_por_coordinadores where nombre='$nombre_docente' and carrera LIKE '%$carrera%'");
                                                        //Obtengo el promedio general de todas las respuestas de estudiantes para esta materia
                                                            if($carrera=="MATERIAS INSTITUCIONALES"){
                                                                $sentencia_promedio_alumnos="
                                                                    SELECT 
                                                                        IFNULL(
                                                                            (
                                                                                AVG(respuestas.r01) +
                                                                                AVG(respuestas.r02) +
                                                                                AVG(respuestas.r03) +
                                                                                AVG(respuestas.r04) +
                                                                                AVG(respuestas.r05)
                                                                            ) / 5,
                                                                            0
                                                                        ) as promedio_general
                                                                    FROM 
                                                                        lic_profesores_por_alumnos as respuestas
                                                                    WHERE 
                                                                        respuestas.nombre='$nombre_docente' and respuestas.sello=1
                                                                    ;
                                                                ";
                                                            }elseif($carrera=="IDIOMAS"){
                                                                $sentencia_promedio_alumnos="
                                                                    SELECT 
                                                                        IFNULL(
                                                                            (
                                                                                AVG(respuestas.r01) +
                                                                                AVG(respuestas.r02) +
                                                                                AVG(respuestas.r03) +
                                                                                AVG(respuestas.r04) +
                                                                                AVG(respuestas.r05)
                                                                            ) / 5,
                                                                            0
                                                                        ) as promedio_general
                                                                    FROM 
                                                                        lic_profesores_por_alumnos as respuestas
                                                                    WHERE 
                                                                        respuestas.nombre='$nombre_docente' and respuestas.grupo LIKE 'TIDIOMAS_%'
                                                                    ;
                                                                ";
                                                            }else{
                                                                $sentencia_promedio_alumnos="
                                                                    SELECT 
                                                                        IFNULL(
                                                                            (
                                                                                AVG(respuestas.r01) +
                                                                                AVG(respuestas.r02) +
                                                                                AVG(respuestas.r03) +
                                                                                AVG(respuestas.r04) +
                                                                                AVG(respuestas.r05)
                                                                            ) / 5,
                                                                            0
                                                                        ) as promedio_general
                                                                    FROM 
                                                                        lic_profesores_por_alumnos as respuestas
                                                                    WHERE 
                                                                        respuestas.nombre='$nombre_docente' and respuestas.carrera LIKE '%$carrera%'
                                                                    ;
                                                                ";
                                                            }    
                                                        //Ejecuto la sentencia y almaceno lo obtenido en una variable
                                                            $resultado_sentencia=retorna_datos($sentencia_promedio_alumnos);
                                                        //Valor por defecto cuando no hay datos
                                                            $res_estudiantes = 0;
                                                        //Identifico si el resultado no es vacio
                                                            if ($resultado_sentencia['rowCount'] > 0) {
                                                                //Almaceno los datos obtenidos
                                                                    $resultado = $resultado_sentencia['data'];
                                                                // Recorrer los datos y llenar las filas
                                                                    foreach ($resultado as $tabla) {
                                                                        //Convierto al ponderado: promedio * 35 / 4, con 1 decimal
                                                                            $res_estudiantes = round(($tabla['promedio_general'] * 35) / 4, 1);
                                                                        //
                                                                    }
                                                                //
                                                            }
                                                        //Obtengo el promedio general de todas las respuestas de autoevaluacion para esta materia
                                                            $sentencia_autoevaluacion="
                                                                SELECT 
                                                                    IFNULL(
                                                                        (
                                                                            AVG(r01) +
                                                                            AVG(r02) +
                                                                            AVG(r03) +
                                                                            AVG(r04) +
                                                                            AVG(r05) +
                                                                            AVG(r06) +
                                                                            AVG(r07) +
                                                                            AVG(r08) +
                                                                            AVG(r09) +
                                                                            AVG(r10)
                                                                        ) / 10,
                                                                        0
                                                                    ) as promedio_general
                                                                FROM lic_profesores_por_profesores
                                                                WHERE nombre='$nombre_docente' and carrera LIKE '%$carrera%'
                                                                ;
                                                            ";
                                                        //Ejecuto la sentencia y almaceno lo obtenido en una variable
                                                            $resultado_sentencia=retorna_datos($sentencia_autoevaluacion);
                                                        //Valor por defecto cuando no hay datos
                                                            $res_docentes = 0;
                                                        //Identifico si el resultado no es vacio
                                                            if ($resultado_sentencia['rowCount'] > 0) {
                                                                //Almaceno los datos obtenidos
                                                                    $resultado = $resultado_sentencia['data'];
                                                                // Recorrer los datos y llenar las filas
                                                                    foreach ($resultado as $tabla) {
                                                                        //Convierto al ponderado: promedio * 15 / 4, con 1 decimal
                                                                            $res_docentes = round(($tabla['promedio_general'] * 15) / 4, 1);
                                                                        //
                                                                    }
                                                                //
                                                            }
                                                        //Obtengo el promedio general de todas las respuestas del coordinador para este docente en esta materia
                                                            $sentencia_promedio_coordinadores="
                                                                SELECT 
                                                                    IFNULL(
                                                                        (
                                                                            AVG(r01) +
                                                                            AVG(r02) +
                                                                            AVG(r03) +
                                                                            AVG(r04) +
                                                                            AVG(r05)
                                                                        ) / 5,
                                                                        0
                                                                    ) as promedio_general
                                                                FROM 
                                                                    lic_profesores_por_coordinadores
                                                                WHERE
                                                                    nombre='$nombre_docente' and carrera LIKE '%$carrera%'
                                                                ;
                                                            ";
                                                        //Ejecuto la sentencia y almaceno lo obtenido en una variable
                                                            $resultado_sentencia=retorna_datos($sentencia_promedio_coordinadores);
                                                        //Valor por defecto cuando no hay datos
                                                            $res_coordinacion = 0;
                                                        //Identifico si el resultado no es vacio
                                                            if ($resultado_sentencia['rowCount'] > 0) {
                                                                //Almaceno los datos obtenidos
                                                                    $resultado = $resultado_sentencia['data'];
                                                                // Recorrer los datos y llenar las filas
                                                                    foreach ($resultado as $tabla) {
                                                                        //Convierto al ponderado: promedio * 35 / 4, con 1 decimal
                                                                            $res_coordinacion = round(($tabla['promedio_general'] * 35) / 4, 1);
                                                                        //
                                                                    }
                                                                //
                                                            }
                                                        //Suma total de los ponderados, con 1 decimal
                                                            $total_evaluacion = round(
                                                                $res_capacitacion +
                                                                $res_estudiantes +
                                                                $res_docentes +
                                                                $res_coordinacion,
                                                                1
                                                            );
                                                        //Almaceno los resultados en el arreglo
                                                            $resultados_docentes[] = [
                                                                'nombre' => $nombre_docente,
                                                                'capacitacion' => $res_capacitacion,
                                                                'estudiantes' => $res_estudiantes,
                                                                'docentes' => $res_docentes,
                                                                'coordinacion' => $res_coordinacion,
                                                                'total' => $total_evaluacion
                                                            ];
                                                        //
                                                    }
                                                //Ordeno los resultados por total de forma descendente
                                                    usort($resultados_docentes, function($a, $b) {
                                                        return $b['total'] <=> $a['total'];
                                                    });
                                                //Imprimo las filas ordenadas
                                                    foreach ($resultados_docentes as $docente) {
                                                        echo "
                                                            <tr>
                                                                <td>" . $docente['nombre'] . "</td>
                                                                <td>" . number_format($docente['capacitacion'], 1) . " %</td>
                                                                <td>" . number_format($docente['estudiantes'], 1) . " %</td>
                                                                <td>" . number_format($docente['docentes'], 1) . " %</td>
                                                                <td>" . number_format($docente['coordinacion'], 1) . " %</td>
                                                                <td><strong>" . number_format($docente['total'], 1) . " %</strong></td>
                                                            </tr>
                                                        ";
                                                    }
                                                //
                                            }
                                        //
                                    ?>
                                </tbody>
                            </table> 
                        </div>
                    </div>

                <!--Tabla de evaluacion por competencias-->
                    <div class='card'>
                        <div class="card-header text-center">
                            <h3>RESULTADOS POR COMPETENCIAS </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <th>COMPETENCIA</th>
                                        <th>DESCRIPCION</th>
                                        <th>ESTUDIANTE</th>
                                        <th>DOCENTE</th>
                                        <th>COORDINACION</th>
                                        <th>RESULTADO POR COMPETENCIA</th>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <td>1.Dominio de la disciplina</td>
                                        <td>Conoce y explica a profundidad los conceptos, teorías, modelos, principios o prácticas del campo disciplinar de su especialidad.</td>
                                        <?php
                                            //Resultado de estudiante
                                                if($carrera=="MATERIAS INSTITUCIONALES"){
                                                    $avg_1e = busca_existencia("SELECT IFNULL((AVG(r01) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where sello=1");  
                                                }else if($carrera=="IDIOMAS"){
                                                    $avg_1e = busca_existencia("SELECT IFNULL((AVG(r01) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where grupo LIKE 'TIDIOMAS_%'");
                                                }else{
                                                    $avg_1e = busca_existencia("SELECT IFNULL((AVG(r01) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where carrera='$carrera'");
                                                }
                                                echo "<td>" . number_format($avg_1e, 1) . " %</td>";
                                            //Resultado de DOCENTE
                                                $avg_1d = busca_existencia("SELECT IFNULL((AVG(r01) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where carrera='$carrera'");
                                                echo "<td>" . number_format($avg_1d, 1) . " %</td>";
                                            //Celda deshabilitada (gris): no se toma en cuenta en el promedio horizontal
                                                echo "<td class='bg-light text-muted'>N/A</td>";
                                            //Resultado por competencia (promedio horizontal solo de celdas activas)
                                                $valores_competencia_1 = [$avg_1e, $avg_1d];
                                                $resultado_competencia_1 = count($valores_competencia_1) > 0 ? round(array_sum($valores_competencia_1) / count($valores_competencia_1), 1) : 0;
                                                echo "<td><strong>" . number_format($resultado_competencia_1, 1) . " %</strong></td>";    
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>2.Vinculación profesional</td>
                                        <td>Tiene experiencia en su campo profesional y la relaciona con los procesos de aprendizaje de las y los estudiantes.</td>
                                        <?php
                                            //Resultado de estudiante
                                                if($carrera=="MATERIAS INSTITUCIONALES"){
                                                    $avg_2e = busca_existencia("SELECT IFNULL((AVG(r02) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where sello=1");
                                                }else if($carrera=="IDIOMAS"){
                                                    $avg_2e = busca_existencia("SELECT IFNULL((AVG(r02) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where grupo LIKE 'TIDIOMAS_%'");
                                                }else{
                                                    $avg_2e = busca_existencia("SELECT IFNULL((AVG(r02) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where carrera='$carrera'");
                                                }
                                                
                                                echo "<td>" . number_format($avg_2e, 1) . " %</td>";
                                            //Resultado de DOCENTE
                                                $avg_2d = busca_existencia("SELECT IFNULL((AVG(r02) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where carrera='$carrera'");
                                                echo "<td>" . number_format($avg_2d, 1) . " %</td>";
                                            //Celda deshabilitada (gris): no se toma en cuenta en el promedio horizontal
                                                echo "<td class='bg-light text-muted'>N/A</td>";
                                            //Resultado por competencia (promedio horizontal solo de celdas activas)
                                                $valores_competencia_2 = [$avg_2e, $avg_2d];
                                                $resultado_competencia_2 = count($valores_competencia_2) > 0 ? round(array_sum($valores_competencia_2) / count($valores_competencia_2), 1) : 0;
                                                echo "<td><strong>" . number_format($resultado_competencia_2, 1) . " %</strong></td>";    
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>3.Comunicación</td>
                                        <td>Se comunica eficazmente con el grupo.</td>
                                        <?php
                                            //Resultado de estudiante
                                                echo "<td class='bg-light text-muted'>N/A</td>";
                                            //Resultado de DOCENTE
                                                $avg_3d = busca_existencia("SELECT IFNULL((AVG(r03) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where carrera='$carrera'");
                                                echo "<td>" . number_format($avg_3d, 1) . " %</td>";
                                            //Celda deshabilitada (gris): no se toma en cuenta en el promedio horizontal
                                                echo "<td class='bg-light text-muted'>N/A</td>";
                                            //Resultado por competencia (promedio horizontal solo de celdas activas)
                                                $valores_competencia_3 = [$avg_3d];
                                                $resultado_competencia_3 = count($valores_competencia_3) > 0 ? round(array_sum($valores_competencia_3) / count($valores_competencia_3), 1) : 0;
                                                echo "<td><strong>" . number_format($resultado_competencia_3, 1) . " %</strong></td>";    
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>4.Mediación de los aprendizajes</td>
                                        <td>En su actividad en el aula demuestra manejo de grupo, organización, liderazgo, capacidad de implementar actividades y recursos diversos y congruentes con los objetivos de aprendizaje.</td>
                                        <?php
                                            //Resultado de estudiante
                                                if($carrera=="MATERIAS INSTITUCIONALES"){
                                                    $avg_3e = busca_existencia("SELECT IFNULL((AVG(r03) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where sello=1");
                                                }else if($carrera=="IDIOMAS"){
                                                    $avg_3e = busca_existencia("SELECT IFNULL((AVG(r03) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where grupo LIKE 'TIDIOMAS_%'");
                                                }else{
                                                    $avg_3e = busca_existencia("SELECT IFNULL((AVG(r03) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where carrera='$carrera'");
                                                }
                                                echo "<td>" . number_format($avg_3e, 1) . " %</td>";
                                            //Resultado de DOCENTE
                                                $avg_5d = busca_existencia("SELECT IFNULL((AVG(r05) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where carrera='$carrera'");
                                                echo "<td>" . number_format($avg_5d, 1) . " %</td>";
                                            //Celda deshabilitada (gris): no se toma en cuenta en el promedio horizontal
                                                echo "<td class='bg-light text-muted'>N/A</td>";
                                            //Resultado por competencia (promedio horizontal solo de celdas activas)
                                                $valores_competencia_4 = [$avg_3e, $avg_5d];
                                                $resultado_competencia_4 = count($valores_competencia_4) > 0 ? round(array_sum($valores_competencia_4) / count($valores_competencia_4), 1) : 0;
                                                echo "<td><strong>" . number_format($resultado_competencia_4, 1) . " %</strong></td>";    
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>5.Evaluación de los aprendizajes</td>
                                        <td>Anticipa y presenta criterios de evaluación claros, evalúa de manera integral y brinda retroalimentación oportuna.</td>
                                        <?php
                                            //Resultado de estudiante
                                                if($carrera=="MATERIAS INSTITUCIONALES"){
                                                    $avg_4e = busca_existencia("SELECT IFNULL((AVG(r04) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where sello=1");
                                                }else if($carrera=="IDIOMAS"){
                                                    $avg_4e = busca_existencia("SELECT IFNULL((AVG(r04) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where grupo LIKE 'TIDIOMAS_%'");
                                                }else{
                                                    $avg_4e = busca_existencia("SELECT IFNULL((AVG(r04) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where carrera='$carrera'");
                                                }
                                                echo "<td>" . number_format($avg_4e, 1) . " %</td>";
                                            //Resultado de DOCENTE
                                                $avg_6d = busca_existencia("SELECT IFNULL((AVG(r06) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where carrera='$carrera'");
                                                echo "<td>" . number_format($avg_6d, 1) . " %</td>";
                                            //Celda deshabilitada (gris): no se toma en cuenta en el promedio horizontal
                                                echo "<td class='bg-light text-muted'>N/A</td>";
                                            //Resultado por competencia (promedio horizontal solo de celdas activas)
                                                $valores_competencia_4 = [$avg_4e, $avg_6d];
                                                $resultado_competencia_4 = count($valores_competencia_4) > 0 ? round(array_sum($valores_competencia_4) / count($valores_competencia_4), 1) : 0;
                                                echo "<td><strong>" . number_format($resultado_competencia_4, 1) . " %</strong></td>";    
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>6.Generación de ambientes seguros</td>
                                        <td>Promueve un ambiente respetuoso y libre de violencias, lo que favorece el diálogo y la sana convivencia. Se conduce con respeto hacia todas las personas de la comunidad educativa.</td>
                                        <?php
                                            //Resultado de estudiante
                                                if($carrera=="MATERIAS INSTITUCIONALES"){
                                                    $avg_5e = busca_existencia("SELECT IFNULL((AVG(r05) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where sello=1");
                                                }else if($carrera=="IDIOMAS"){
                                                    $avg_5e = busca_existencia("SELECT IFNULL((AVG(r05) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where grupo LIKE 'TIDIOMAS_%'");
                                                }else{
                                                    $avg_5e = busca_existencia("SELECT IFNULL((AVG(r05) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where carrera='$carrera'");
                                                }
                                                echo "<td>" . number_format($avg_5e, 1) . " %</td>";
                                            //Resultado de DOCENTE
                                                $avg_7d = busca_existencia("SELECT IFNULL((AVG(r07) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where carrera='$carrera'");
                                                echo "<td>" . number_format($avg_7d, 1) . " %</td>";
                                            //Resultado de DOCENTE
                                                $avg_1c = busca_existencia("SELECT IFNULL((AVG(r01) / 4) * 100,0) AS exist FROM lic_profesores_por_coordinadores where carrera LIKE '%$carrera%'");
                                                echo "<td>" . number_format($avg_1c, 1) . " %</td>";
                                            //Resultado por competencia (promedio horizontal solo de celdas activas)
                                                $valores_competencia_4 = [$avg_5e, $avg_7d, $avg_1c];
                                                $resultado_competencia_4 = count($valores_competencia_4) > 0 ? round(array_sum($valores_competencia_4) / count($valores_competencia_4), 1) : 0;
                                                echo "<td><strong>" . number_format($resultado_competencia_4, 1) . " %</strong></td>";
                                            //  
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>7.Compromiso institucional </td>
                                        <td>Promueve los valores maristas, se conduce con honorabilidad e integridad, participa en reuniones, cumple compromisos administrativos y académicos.</td>
                                        <?php
                                            //Resultado de estudiante
                                            //Celda deshabilitada (gris): no se toma en cuenta en el promedio horizontal
                                                echo "<td class='bg-light text-muted'>N/A</td>";
                                            //Resultado de DOCENTE
                                                $avg_8d = busca_existencia("SELECT IFNULL((((AVG(r04) + AVG(r08) + AVG(r09) + AVG(r10)) / 4) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where carrera='$carrera'");
                                                echo "<td>" . number_format($avg_8d, 1) . " %</td>";
                                            //Resultado de COORDINADOR
                                                $avg_6c = busca_existencia("SELECT IFNULL((((AVG(r02) + AVG(r03) + AVG(r04) + AVG(r05)) / 4) / 4) * 100,0) AS exist FROM lic_profesores_por_coordinadores where carrera LIKE '%$carrera%'");
                                                echo "<td>" . number_format($avg_6c, 1) . " %</td>";
                                            //Resultado por competencia (promedio horizontal solo de celdas activas)
                                                $valores_competencia_4 = [$avg_8d, $avg_6c];
                                                $resultado_competencia_4 = count($valores_competencia_4) > 0 ? round(array_sum($valores_competencia_4) / count($valores_competencia_4), 1) : 0;
                                                echo "<td><strong>" . number_format($resultado_competencia_4, 1) . " %</strong></td>";
                                            //  
                                        ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <!--Comentaio general de reflexion-->
                    <div class="card">
                        <div class="card-body text-center">
                            <p>
                                Estimada, estimado coordinador:
                                <br><br>
                                Te invito a reflexionar sobre estos resultados y a identificar posibles áreas de mejora como una oportunidad para fortalecer el seguimiento de las y los docentes. En la parte inferior encontrarás un espacio destinado a la formulación de tus compromisos para la mejora integral.
                                <br><br>
                                Agradezco profundamente tu compromiso con la excelencia educativa y quedo a tu disposición para brindarte el apoyo adicional que consideres necesario.
                                <br><br>
                                Atentamente.
                            </p>
                        </div>
                    </div>

                <!--Firma de la jefatura-->
                    <div class="card">
                        <div class="card-body text-center">
                            <?php
                                //Obtengo el nombre del jefe de la jefatura
                                    $jefe=busca_existencia("SELECT jefe AS exist FROM departamentos where departamento='$jefatura'");
                                //Imprimo una linea para la firma y el nombre del jefe
                                    echo "
                                        <br><br><br><br><br><br><br>
                                        <p>________________________________________________________________________________</p>
                                        <p>
                                            <strong>" . $jefe . "</strong>
                                            <br>
                                            JEFATURA DE $jefatura
                                        </p>
                                    ";
                                //
                            ?>
                        </div>
                    </div>

                <!--Area de compromisos-->
                    <div class="cardtext-center">
                        <div class="card-header text-center">
                            <h3>COMPROMISOS PARA LA MEJORA DE LA CARRERA</h3>
                        </div>
                        <div class="card-body">
                            <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                        </div>
                    </div>

                <!--Firma del docente-->
                    <div class="card">
                        <div class="card-body text-center">
                            <?php
                                //Imprimo una linea para la firma y el nombre del docente
                                    echo "
                                        <br><br><br><br><br><br><br>
                                        <p>________________________________________________________________________________</p>
                                        <p>
                                            <strong>$coordinador</strong>
                                        </p>
                                    ";
                                //
                            ?>
                        </div>
                    </div>

                <!---->
            </div>
        </div>
    </body>
    <?php include_once "footer.php"; ?>
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
