<?php
	session_start();
	require_once 'lib/config.php';
	if (!isset($_SESSION['zez_a_nombre'])) {
		header('Location: salir.php');
	} elseif (($_SESSION['zez_a_nivel'] != "LICENCIATURA") OR (!strpos(" ALUMNO",$_SESSION['zez_a_nivel_acceso'])) OR (!$_POST) OR (date("Y-m-d") < $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) OR (date("Y-m-d") > $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin'])) {
		header('Location: index.php');
	}
	// Recuperar parámetros
	$parametros_validos = false;
	unset($parametros);
	$string_parametros = "";
	if (isset($_POST['parametros'])) {
		$string_parametros = desencriptar($_POST['parametros']);
		if ((substr($string_parametros,0,2)=="%%") AND (substr($string_parametros,(strlen($string_parametros)-2),2)=="%%")) {
			$string_parametros = substr($string_parametros,2,strlen($string_parametros)-4);
			$parametros_validos = true;
			if(strpos(($string_parametros),"¬")===FALSE) {
				$parametros[] = $string_parametros;
			} else {
				do {
					$parametros[] = substr($string_parametros,0,strpos($string_parametros,"¬"));
					$string_parametros = substr($string_parametros,strpos($string_parametros,"¬")+2);
				} while (!(strpos($string_parametros,"¬")===FALSE));
				$parametros[] = $string_parametros;
			}
		}
	}
	if((!$parametros_validos) or (!isset($_POST['opcion_evaluacion']))) {
		header('Location: index.php');
		die();
	}
	if (('SOLO_REGISTRAR'==$_POST['opcion_evaluacion']) or ('0'==$_POST['opcion_evaluacion'])) {
		$opcion_evaluacion = $_POST['opcion_evaluacion'];
		if ('#CUyDE1#'==$parametros[1]) $parametros[1] .= '#CUyDE2#';
		$parametros_enviar = encriptar("%%".$parametros[0]."¬".$parametros[1]."%%");
	} else {
		$opcion_evaluacion = (int)$_POST['opcion_evaluacion'];
		// Conectarse al servidor de la base de datos (BDD)
		$base_de_datos = mysqli_connect($bdd_servidor,$bdd_usuario,$bdd_clave,$bdd_nombre);
		// Verificar la conexión
		if (mysqli_connect_errno()) {
			printf("Falló la conexión: %s", mysqli_connect_error());
			exit();
		}
		// Cambiar el conjunto de caracteres a utf8
		if (!mysqli_set_charset($base_de_datos, "utf8")) {
			printf("Error cargando el conjunto de caracteres utf8: %s", mysqli_error($base_de_datos));
			exit();
		}
		$orden_sql = "SELECT * FROM `deporteycultura` WHERE `id`=" . $opcion_evaluacion;
		// Ejecuta la consulta SQL
		$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
		if ($resultado_busqueda) {
			if (mysqli_num_rows($resultado_busqueda) > 0) {
				// Si hubo resultado genera la tabla
				$registro = mysqli_fetch_array($resultado_busqueda);
				if ('FEMENINO'==$registro['sexo']) {
					$el_o_la = 'La profesora';
					$del_o_dela = 'l profesor';
				} else {
					$el_o_la = 'El profesor';
					$del_o_dela = ' la profesora';
				}
			}
			mysqli_free_result($resultado_busqueda);
		}
		$parametros_enviar = encriptar('%%'.$parametros[0].'¬'.$parametros[1].'<'.$opcion_evaluacion.'>¬'.$registro['actividad'].' - '.$registro['tipo'].'¬'.$registro['profesor'].'¬'.$registro['sexo'].'¬'.$el_o_la.'¬'.$del_o_dela.'%%');
	}
?>
<!DOCTYPE html>
<html>
	<?php include_once HEADER; ?>
	<script type="text/javascript" src="lib/functions.js"></script>
	<body>
		<div class="container">
			<div class="card">
				<form name="Regresar" id="Regresar" action="evalua.php" method=post accept-charset="UTF-8">
					<input type="hidden" name="parametros" value="<?php echo $parametros_enviar; ?>" />
					<input type="hidden" name="opcion_evaluacion" value="<?php echo $opcion_evaluacion; ?>" />
				</form>
				<script type="text/javascript" language="javascript">
					document.Regresar.submit();
				</script>
			</div>
		</div>
	</body>
</html>