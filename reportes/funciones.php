<?php
  //Devuelve la fecha en palabras
    function transforma_fecha($fecha,$tipo=0,$separador = "-"){
      //Obtenemos la fecha y la separamos por su guion medio
        $dato = explode("-",$fecha);
      //Almaceno cada parte en una variable
        $ano=$dato[0];
        $mes=$dato[1];
        $dia=$dato[2];
      //Evaluamos si se sequiere que se convierta a dexto el mes
        if ($tipo==1) {
          //Obtengo el mes y lo paso a texto
            switch ($dato[1]) {
              case '1':
                $mes='Enero';
              break;
              case '2':
                $mes='Febrero';
              break;
              case '3':
                $mes='Marzo';
              break;
              case '4':
                $mes='Abril';
              break;
              case '5':
                $mes='Mayo';
              break;
              case '6':
                $mes='Junio';
              break;
              case '7':
                $mes='Julio';
              break;
              case '8':
                $mes='Agosto';
              break;
              case '9':
                $mes='Septiembre';
              break;
              case '10':
                $mes='Octubre';
              break;
              case '11':
                $mes='Noviembre';
              break;
              case '12':
                $mes='Diciembre';
              break;
            }
          //
        }
      //Concateno los datos con el separador definido
        $texto=$dia.$separador.$mes.$separador.$ano;
      //Retorno el dato obtenido
        return $texto;
      //
    }
  //Devuelve la fecha y hora actuales
    function ahora($tipo){
      //Se obtiene el timepo actual
        $hoy = getdate();
      //Se evalua el tipo de dato que se desea obtener
        switch ($tipo) {
          //Se solicita la fecha
            case '1':
              $actual=date('Y-m-d');
            break;
          //Se solicita la hora
            case '2':
              $actual=date('H:i:s');
            break;
          //Se solicitan el timestamp actual
            case '3':
              $actual=date('Y-m-d H:i:s');
            break;
          //
        }
      //Retorno el dato formateado
        return $actual;
      //
    }
  
  //Funcion para poner o quitar comillas de un texto
    function pone_comillas($texto){ return str_replace('çÇç', '"', $texto); }
  //Función para obtener los datos de una sentencia ejecutada en la BD central
    function retorna_datos($sentencia) {
      include "connection.php";
      try {
        //Preparo la sentencia a ejecutar
        $sql = $conn->prepare($sentencia);
        //Ejecutar la sentencia
        $sql->execute();
        // Obtener el número de filas afectadas
        $rowCount = $sql->rowCount();
        // Obtener los datos de la tabla
        $datos = array();
        while ($fila = $sql->fetch(PDO::FETCH_ASSOC)) {
            $datos[] = $fila;
        }
        // Cerrar el cursor
        //$sql->closeCursor();
        // Retornar el resultado
        return array('data' => $datos, 'rowCount' => $rowCount);;
        //
      } catch (PDOException $e) {
        //Almaceno el error en una variabLe
        echo $error=$e->getMessage();
        //Detengo el procedimiento
        die();
      }
    }
    //Función para buscar la cantidad de registros existentes
    function busca_existencia($sentencia) {
      include "connection.php";
      try {
        //Preparo la sentencia a ejecutar
        $sql = $conn->prepare($sentencia);
        //Ejecutar la sentencia
        $sql->execute();
        //Asocio los datos de la tabla obtenidos
        $tabla=$sql->fetch(PDO::FETCH_ASSOC);
        //Retorna el valor obtenido
        return $tabla['exist'];
        //finalizo el cursor
        $sql->CloseCursor();
        //
      } catch (PDOException $e) {
        //Almaceno el error en una variabLe
        echo $error=$e->getMessage();
        //Detengo el procedimiento
        die();
      }
    }
  //Función para ejecutar sentencias dentro de la base de datos de la plataforma
    function ejecuta_sentencia($sentencia,$mensaje) {
      include "connection.php";
      try {
        //Preparo la sentencia a ejecutar
        $sql=$conn->prepare($sentencia);
        //ejecuto la sentencia
        $res=$sql->execute();
        //finalizo el cursor
        $sql->CloseCursor();
        //Retorna el valor de mensaje dado
        return $mensaje;
        //
      } catch (PDOException $e) {
        //Almaceno el error en una variabLe
        echo $error=$e->getMessage();
        //Detengo el procedimiento
        die();
      }
    }
  //
?>