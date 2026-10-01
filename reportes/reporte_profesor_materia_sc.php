<?php 
    //Se incluye el archivo de funciones
	    include_once "funciones.php";
    //Se inicia la sesion
	    session_start();
    //Recibo los datos enviados por el formulario
	    $nombre_docente=$_GET['nombre'];
        $materia=$_GET['materia'];
	    $carrera=$_GET['carrera'];
    //Verifico si e docente es hombre o mujer
        $sexo=busca_existencia("SELECT sexo AS exist FROM profesores where nombre='$nombre_docente' and carrera='$carrera' and materia='$materia'");
    //Defino si el profesor a evaluar es masculino o femenino
		switch ($sexo) {
			case 'FEMENINO':
				$txt_sx_may="LA";
				$txt_sx_min="la";
			break;
			case 'MASCULINO':
				$txt_sx_may="EL";
				$txt_sx_min="el";
			break;
			default:
				$txt_sx_may="El/La";
				$txt_sx_min="el/la";
		}
    //Defino la sentencia para obtener el grupo
        $sentencia_grupo="select grupo from profesores where carrera='$carrera' and nombre='$nombre_docente' and materia='$materia'";
    //Ejecuto la sentencia y almaceno lo obtenido en una variable
        $resultado_sentencia=retorna_datos($sentencia_grupo);
    //Identifico si el reultado no es vacio
        if ($resultado_sentencia['rowCount'] > 0) {
            //Almaceno los datos obtenidos
                $resultado = $resultado_sentencia['data'];
            // Recorrer los datos y llenar las filas
                foreach ($resultado as $tabla) {
                    //Almaceno los resultados en variables
                        $grupo = $tabla['grupo'];
                    //
                }
            //
        }
    //Obtengo los datos de la evaluacion integrada
        $trc_1 = busca_existencia("SELECT IFNULL(AVG(CASE WHEN TRIM(r08) REGEXP '^[0-9]+([.][0-9]+)?$' THEN CAST(TRIM(r08) AS DECIMAL(10,2)) END),0) AS exist FROM lic_profesores_por_coordinadores where nombre='$nombre_docente' and carrera LIKE '%$carrera%'");
    //Obtengo el promedio general de todas las respuestas de estudiantes para esta materia
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
                respuestas.nombre='$nombre_docente'
                AND respuestas.materia='$materia'
            ;
        ";
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
            WHERE nombre='$nombre_docente' AND materia='$materia'
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
            WHERE
                nombre='$nombre_docente' and carrera LIKE '%$carrera%'
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
    //Cabecera del documento
        $header="$nombre_docente PARA LA MATERIA DE $materia";
    //
?>
<!DOCTYPE html>
<html>
    <?php include_once "header.php"; ?>
    <body>
        <div class="card">
            <div class="card-body">
                <!-- Botón para imprimir la página -->
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
                            <td><strong>DOCENTE</strong></td>
                            <td><?PHP echo $nombre_docente; ?></td>
                        </tr>
                        <tr>
                            <td><strong>CARRERA</strong></td>
                            <td><?PHP echo $carrera; ?></td>
                        </tr>
                        <tr>
                            <td><strong>MATERIA</strong></td>
                            <td><?PHP echo $materia; ?></td>
                        </tr>
                        <tr>
                            <td><strong>TOTAL</strong></td>
                            <td><?PHP echo $total_evaluacion; ?></td>
                        </tr>
                    </table>
                <!--tabla de resultados generales-->
                    <div class='card'>
                        <div class="card-header text-center">
                            <h3>TABLA DE RESULTADOS</h3>
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
                                        <td><strong>TOTAL</strong></td>
                                        <td><strong>100%</strong></td>
                                        <td><strong><?php echo number_format($total_evaluacion,1); ?>%</strong></td>
                                    </tr>
                                </tbody>
                            </table>    
                        </div>
                    </div>
                <!--Tabla de evaluacion por competencias-->
                    <div class='card'>
                        <div class="card-header text-center">
                            <h3>EVALUACION POR COMPETENCIAS</h3>
                        </div>
                        <div class="card-text text-center">
                            <p>Esta evaluación promedia los resultados obtenidos por cada una de las 7 competencias docentes, según el agente evaluador y las convierte a escala de 100.</p>
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
                                                $avg_1e = busca_existencia("SELECT IFNULL((AVG(r01) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where nombre='$nombre_docente' and materia='$materia'");
                                                echo "<td>" . number_format($avg_1e, 1) . " %</td>";
                                            //Resultado de DOCENTE
                                                $avg_1d = busca_existencia("SELECT IFNULL((AVG(r01) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where nombre='$nombre_docente' and materia='$materia'");
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
                                                $avg_2e = busca_existencia("SELECT IFNULL((AVG(r02) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where nombre='$nombre_docente' and materia='$materia'");
                                                echo "<td>" . number_format($avg_2e, 1) . " %</td>";
                                            //Resultado de DOCENTE
                                                $avg_2d = busca_existencia("SELECT IFNULL((AVG(r02) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where nombre='$nombre_docente' and materia='$materia'");
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
                                                $avg_3d = busca_existencia("SELECT IFNULL((AVG(r03) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where nombre='$nombre_docente' and materia='$materia'");
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
                                                $avg_3e = busca_existencia("SELECT IFNULL((AVG(r03) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where nombre='$nombre_docente' and materia='$materia'");
                                                echo "<td>" . number_format($avg_3e, 1) . " %</td>";
                                            //Resultado de DOCENTE
                                                $avg_5d = busca_existencia("SELECT IFNULL((AVG(r05) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where nombre='$nombre_docente' and materia='$materia'");
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
                                                $avg_4e = busca_existencia("SELECT IFNULL((AVG(r04) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where nombre='$nombre_docente' and materia='$materia'");
                                                echo "<td>" . number_format($avg_4e, 1) . " %</td>";
                                            //Resultado de DOCENTE
                                                $avg_6d = busca_existencia("SELECT IFNULL((AVG(r06) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where nombre='$nombre_docente' and materia='$materia'");
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
                                                $avg_5e = busca_existencia("SELECT IFNULL((AVG(r05) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where nombre='$nombre_docente' and materia='$materia'");
                                                echo "<td>" . number_format($avg_5e, 1) . " %</td>";
                                            //Resultado de DOCENTE
                                                $avg_7d = busca_existencia("SELECT IFNULL((AVG(r07) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where nombre='$nombre_docente' and materia='$materia'");
                                                echo "<td>" . number_format($avg_7d, 1) . " %</td>";
                                            //Resultado de DOCENTE
                                                $avg_1c = busca_existencia("SELECT IFNULL((AVG(r01) / 4) * 100,0) AS exist FROM lic_profesores_por_coordinadores where nombre='$nombre_docente' and carrera LIKE '%$carrera%'");
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
                                                $avg_8d = busca_existencia("SELECT IFNULL((((AVG(r04) + AVG(r08) + AVG(r09) + AVG(r10)) / 4) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where nombre='$nombre_docente' and materia='$materia'");
                                                echo "<td>" . number_format($avg_8d, 1) . " %</td>";
                                            //Resultado de COORDINADOR
                                                $avg_6c = busca_existencia("SELECT IFNULL((((AVG(r02) + AVG(r03) + AVG(r04) + AVG(r05)) / 4) / 4) * 100,0) AS exist FROM lic_profesores_por_coordinadores where nombre='$nombre_docente' and carrera LIKE '%$carrera%'");
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
                <!--Comentarios del docente-->
                    <div class="card">
                        <div class="card-header text-center">
                            <h3>COMENTARIOS DE <?php echo $txt_sx_may; ?> DOCENTE</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="text-center">
                                        <th>FORTALEZAS</th>
                                        <th>AREAS DE MEJORA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php 
                                                echo $cmt = busca_existencia("SELECT r11 as exist FROM lic_profesores_por_profesores where nombre='$nombre_docente' and materia='$materia'");
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                echo $cmt = busca_existencia("SELECT r12 as exist FROM lic_profesores_por_profesores where nombre='$nombre_docente' and materia='$materia'");
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                                <thead>
                                    <tr class="text-center">
                                        <th colspan="2">COMENTARIOS ADICIONALES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="2">
                                            <?php 
                                                echo $cmt = busca_existencia("SELECT r13 as exist FROM lic_profesores_por_profesores where nombre='$nombre_docente' and materia='$materia'");
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <!--Comentarios de la coordinacion-->
                    <div class="card">
                        <div class="card-header text-center">
                            <h3>COMENTARIOS DE LA COORDINACION</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="text-center">
                                        <th>FORTALEZAS</th>
                                        <th>AREAS DE MEJORA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo $cmt = busca_existencia("SELECT r06 as exist FROM lic_profesores_por_coordinadores where nombre='$nombre_docente' and carrera LIKE '%$carrera%'"); ?></td>
                                        <td><?php echo $cmt = busca_existencia("SELECT r07 as exist FROM lic_profesores_por_coordinadores where nombre='$nombre_docente' and carrera LIKE '%$carrera%'"); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <!--Comentaio general de reflexion-->
                    <div class="card">
                        <div class="card-body">
                            <p>
                                Le invitamos a reflexionar sobre estos resultados y a identificar posibles áreas de mejora como una oportunidad para fortalecer su práctica docente. En la parte inferior encontrará un espacio destinado a la formulación de sus compromisos para la mejora integral.
                                <br>
                                <br>
                                Agradecemos profundamente su compromiso con la excelencia educativa. Quedamos a su disposición para brindarle el apoyo adicional que considere necesario.
                            </p>
                        </div>
                    </div>
                <!--Firma del coordinador-->
                    <div class="card">
                        <div class="card-body text-center">
                            <?php
                                //Obtengo el nombre del coordinador de la carrera
                                    $coordinador=busca_existencia("SELECT coordinador AS exist FROM carreras where  carrera='$carrera'");
                                //Imprimo una linea para la firma y el nombre del coordinador
                                    echo "
                                        <br><br><br><br><br><br><br>
                                        <p>________________________________________________________________________________</p>
                                        <p>
                                            <strong>" . $coordinador . "</strong>
                                            <br>
                                            COORDINACION DE $carrera
                                        </p>
                                    ";
                                //
                            ?>
                        </div>
                    </div>
                <!--Area de compromisos-->
                    <div class="cardtext-center">
                        <div class="card-header text-center">
                            <h3>COMPROMISOS PARA LA MEJORA INTEGRAL:</h3>
                        </div
                        <div class="card-body ">
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
                                            <strong>$nombre_docente</strong>
                                            <br>
                                            FIRMA DE $txt_sx_may DOCENTE
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
