<?php 
    //Se incluye el archivo de funciones
	    include_once "funciones.php";
    //Se inicia la sesion
	    session_start();
    //Recibo los datos enviados por el formulario
	    $nombre_docente=$_GET['nombre'];
    //Verifico si e docente es hombre o mujer
        $sexo=busca_existencia("SELECT sexo AS exist FROM profesores where nombre='$nombre_docente'");
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
    //Obtengo los datos de la evaluacion integrada
        $trc_1 = busca_existencia("SELECT IFNULL(AVG(CASE WHEN TRIM(r08) REGEXP '^[0-9]+([.][0-9]+)?$' THEN CAST(TRIM(r08) AS DECIMAL(10,2)) END),0) AS exist FROM lic_profesores_por_coordinadores where nombre='$nombre_docente'");
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
            WHERE nombre='$nombre_docente'
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
                nombre='$nombre_docente'
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
        $header="REPORTE INTEGRAL - $nombre_docente";
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
                <!--Tabla de encabezado-->
                    <div class="card">
                        <div class="card-body text-center">
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
                                    <td><strong>RESULTADO INTEGRADO</strong></td>
                                    <td><?PHP echo $total_evaluacion; ?>%</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                <!--Comentario principal-->
                    <div class="card">
                        <div class="card-body text-center">
                            <p>En este reporte se presentan los resultados obtenidos por la o el docente considerando las valoraciones hechas en todas las materias y grupos en los que imparte clases. </p>
                        </div>
                    </div>
                <!--tabla de resultados principal-->
                    <div class="card">
                        <div class="card-body text-center">
                            <table class="table table-bordered table-stripped table-sm text-center">
                                <thead>
                                    <tr class="text-center">
                                        <th colspan="7">COMPETENCIAS</th>
                                    </tr>
                                    <tr>
                                        <th>1.DOMINIO DE LA DISCIPLINA</th>
                                        <th>2.VINCULACION PROFESIONAL</th>
                                        <th>3.COMUNICACION</th>
                                        <th>4.MEDIACION DE LOS APRENDIZAJES</th>
                                        <th>5.EVALUACION DE LOS APRENDIZAJES</th>
                                        <th>6.GENERACION DE AMBIENTES SEGUROS</th>
                                        <th>7.COMROMISO INSTITUCIONAL </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php
                                            //RESULTADO 1
                                                //Busqueda de resultados
                                                    $avg_1e = busca_existencia("SELECT IFNULL((AVG(r01) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where nombre='$nombre_docente'");                                    //Resultado de DOCENTE
                                                    $avg_1d = busca_existencia("SELECT IFNULL((AVG(r01) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where nombre='$nombre_docente'");
                                                //Calculo del resultado por competencia (promedio horizontal solo de celdas activas)
                                                    $valores_competencia_1 = [$avg_1e, $avg_1d];
                                                    $resultado_competencia_1 = count($valores_competencia_1) > 0 ? round(array_sum($valores_competencia_1) / count($valores_competencia_1), 1) : 0;
                                                    echo "<td><strong>" . number_format($resultado_competencia_1, 1) . " %</strong></td>";
                                                //
                                            //RESULTADO 2
                                                //Busqueda de resultados
                                                    $avg_2e = busca_existencia("SELECT IFNULL((AVG(r02) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where nombre='$nombre_docente'");
                                                    $avg_2d = busca_existencia("SELECT IFNULL((AVG(r02) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where nombre='$nombre_docente'");
                                                //Calculo del resultado por competencia (promedio horizontal solo de celdas activas)
                                                    $valores_competencia_2 = [$avg_2e, $avg_2d];
                                                    $resultado_competencia_2 = count($valores_competencia_2) > 0 ? round(array_sum($valores_competencia_2) / count($valores_competencia_2), 1) : 0;
                                                    echo "<td><strong>" . number_format($resultado_competencia_2, 1) . " %</strong></td>";
                                                //
                                            //RESULTADO 3
                                                //Busqueda de resultados
                                                    $avg_3d = busca_existencia("SELECT IFNULL((AVG(r03) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where nombre='$nombre_docente'");
                                                //Calculo del resultado por competencia (promedio horizontal solo de celdas activas)
                                                    $valores_competencia_3 = [$avg_3d];
                                                    $resultado_competencia_3 = count($valores_competencia_3) > 0 ? round(array_sum($valores_competencia_3) / count($valores_competencia_3), 1) : 0;
                                                    echo "<td><strong>" . number_format($resultado_competencia_3, 1) . " %</strong></td>";
                                                //
                                            //RESULTADO 4
                                                //Busqueda de resultados
                                                    $avg_3e = busca_existencia("SELECT IFNULL((AVG(r03) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where nombre='$nombre_docente'");
                                                    $avg_5d = busca_existencia("SELECT IFNULL((AVG(r05) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where nombre='$nombre_docente'");
                                                //Calculo del resultado por competencia (promedio horizontal solo de celdas activas)
                                                    $valores_competencia_4 = [$avg_3e, $avg_5d];
                                                    $resultado_competencia_4 = count($valores_competencia_4) > 0 ? round(array_sum($valores_competencia_4) / count($valores_competencia_4), 1) : 0;
                                                    echo "<td><strong>" . number_format($resultado_competencia_4, 1) . " %</strong></td>";
                                                //
                                            //RESULTADO 5
                                                //Busqueda de resultados
                                                    $avg_4e = busca_existencia("SELECT IFNULL((AVG(r04) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where nombre='$nombre_docente'");
                                                    $avg_6d = busca_existencia("SELECT IFNULL((AVG(r06) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where nombre='$nombre_docente'");
                                                //Calculo del resultado por competencia (promedio horizontal solo de celdas activas)
                                                    $valores_competencia_4 = [$avg_4e, $avg_6d];
                                                    $resultado_competencia_4 = count($valores_competencia_4) > 0 ? round(array_sum($valores_competencia_4) / count($valores_competencia_4), 1) : 0;
                                                    echo "<td><strong>" . number_format($resultado_competencia_4, 1) . " %</strong></td>";
                                                //
                                            //RESULTADO 6
                                                //Busqueda de resultados
                                                    $avg_5e = busca_existencia("SELECT IFNULL((AVG(r05) / 4) * 100,0) AS exist FROM lic_profesores_por_alumnos where nombre='$nombre_docente'");
                                                    $avg_7d = busca_existencia("SELECT IFNULL((AVG(r07) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where nombre='$nombre_docente'");
                                                    $avg_1c = busca_existencia("SELECT IFNULL((AVG(r01) / 4) * 100,0) AS exist FROM lic_profesores_por_coordinadores where nombre='$nombre_docente'");
                                                //Calculo del resultado por competencia (promedio horizontal solo de celdas activas)
                                                    $valores_competencia_4 = [$avg_5e, $avg_7d, $avg_1c];
                                                    $resultado_competencia_4 = count($valores_competencia_4) > 0 ? round(array_sum($valores_competencia_4) / count($valores_competencia_4), 1) : 0;
                                                    echo "<td><strong>" . number_format($resultado_competencia_4, 1) . " %</strong></td>";
                                                //
                                            //RESULTADO 7
                                                //Resultado de DOCENTE
                                                    $avg_8d = busca_existencia("SELECT IFNULL((((AVG(r04) + AVG(r08) + AVG(r09) + AVG(r10)) / 4) / 4) * 100,0) AS exist FROM lic_profesores_por_profesores where nombre='$nombre_docente'");
                                                    $avg_6c = busca_existencia("SELECT IFNULL((((AVG(r02) + AVG(r03) + AVG(r04) + AVG(r05)) / 4) / 4) * 100,0) AS exist FROM lic_profesores_por_coordinadores where nombre='$nombre_docente'");
                                                //Calculo del resultado por competencia (promedio horizontal solo de celdas activas)
                                                    $valores_competencia_4 = [$avg_8d, $avg_6c];
                                                    $resultado_competencia_4 = count($valores_competencia_4) > 0 ? round(array_sum($valores_competencia_4) / count($valores_competencia_4), 1) : 0;
                                                    echo "<td><strong>" . number_format($resultado_competencia_4, 1) . " %</strong></td>";
                                                //
                                            //
                                        ?>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered table-stripped table-sm text-center">
                                <thead>
                                    <tr class="text-center">
                                        <th colspan="5">EVALUACION INTEGRADA</th>
                                    </tr>
                                    <tr>
                                        <th>CAPACITACION</th>
                                        <th>ESTUDIANTES</th>
                                        <th>AUTOEVALUACION</th>
                                        <th>COORDINACION</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong><?php echo number_format($trc_1, 1); ?>%</strong></td>
                                        <td><strong><?php echo number_format($promedio_evaluacion_alumnos,1); ?>%</strong></td>
                                        <td><strong><?php echo number_format($promedio_autoevaluacion,1); ?>%</strong></td>
                                        <td><strong><?php echo number_format($promedio_evaluacion_coordinadores,1); ?>%</strong></td>
                                        <td><strong><?php echo number_format($total_evaluacion,1); ?>%</strong></td>
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
                                        <th>CLAVE</th>
                                        <th>FORTALEZAS</th>
                                        <th>AREAS DE MEJORA</th>
                                        <th>ADICIONALES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        //Obtengo el promedio general de todas las respuestas de estudiantes para esta materia
                                            $sentencia_promedio_alumnos="SELECT * FROM lic_profesores_por_profesores WHERE nombre='$nombre_docente';";
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
                                                            echo "<tr>";
                                                                echo "<td>{$tabla['grupo']}</td>";
                                                                echo "<td>{$tabla['r11']}</td>";
                                                                echo "<td>{$tabla['r12']}</td>";
                                                                echo "<td>{$tabla['r13']}</td>";
                                                            echo "</tr>";
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
                <!--Comentarios de la coordinacion-->
                    <div class="card">
                        <div class="card-header text-center">
                            <h3>COMENTARIOS DE LA COORDINACIÓN</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="text-center">
                                        <th>CARRERA</th>
                                        <th>FORTALEZAS</th>
                                        <th>AREAS DE MEJORA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        //Obtengo el promedio general de todas las respuestas de estudiantes para esta materia
                                            $sentencia_promedio_alumnos="SELECT * FROM lic_profesores_por_coordinadores WHERE nombre='$nombre_docente';";
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
                                                            echo "<tr>";
                                                                echo "<td>{$tabla['carrera']}</td>";
                                                                echo "<td>{$tabla['r06']}</td>";
                                                                echo "<td>{$tabla['r07']}</td>";
                                                            echo "</tr>";
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
                <!--Comentarios del estudiantado-->
                    <div class="card">
                        <div class="card-header text-center">
                            <h3>COMENTARIOS DEL ESTUDIANTADO</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>GRUPO</th>
                                        <th>COMENTARIO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        //Obtengo los comentarios de los alumnos cuando la respuesta es mayor a 1 caracter
                                            $sentencia_promedio_coordinadores="SELECT grupo,r06 FROM  lic_profesores_por_alumnos WHERE nombre='$nombre_docente' AND CHAR_LENGTH(r06) > 1;";
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
                                                        //Almaceno el dato
                                                            $r06 = $tabla['r06'];
                                                            $grupo = $tabla['grupo'];
                                                        //Imprimo la fila con el comentario
                                                            echo "
                                                                <tr>
                                                                    <td>$grupo</td>
                                                                    <td>$r06</td>
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
                <!--Comentaio general de reflexion-->
                    <div class="card">
                        <div class="card-body text-center">
                            <p>
                                Le invitamos a reflexionar sobre estos resultados y a identificar posibles áreas de mejora como una oportunidad para fortalecer su práctica docente. En la parte inferior encontrará un espacio destinado a la formulación de sus compromisos para la mejora integral.
                                <BR><BR>
                                Agradecemos profundamente su compromiso con la excelencia educativa. Quedamos a su disposición para brindarle el apoyo adicional que considere necesario.
                            </p>
                        </div>
                    </div>
                <!--Firma del coordinador-->
                    <div class="card">
                        <div class="card-body text-center">
                            <?php
                                //Imprimo una linea para la firma y el nombre del coordinador
                                    echo "
                                        <br><br><br><br><br><br><br>
                                        <p>________________________________________________________________________________</p>
                                        <p>
                                            <strong>DIRECCION GENERAL EDUCATVA</strong>
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
