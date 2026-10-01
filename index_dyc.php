<?php
	$debug=false;
	if (isset($_GET['debug'])) {
		if ($_GET['debug']=='usuario_local') {
			$debug=true;
		}
	}
	session_start();
	if (isset($_SESSION['zez_a_nombre'])) {
		header('Location: mis_reportes.php');
	}
	require_once 'lib/config.php';
	if ($_POST) {
		// Establece la zona horaria local
		date_default_timezone_set($zona_horaria);
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

		// Buscar al participante
		$participante_encontrado = false;
		$orden_sql = "SELECT * FROM participantes WHERE matricula='" . strtolower(limpiar_campo($_POST['campo11'])) . "' AND clave='" . md5(sha1(limpiar_campo($_POST['campo12']))) . "'";
		$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
		if ($resultado_busqueda) {
			if (mysqli_num_rows($resultado_busqueda) > 0) {
				$participante_encontrado = true;
				while($registro = mysqli_fetch_array($resultado_busqueda)) {
					$_SESSION['zez_a_id'] = $registro['id'];
					$_SESSION['zez_a_nivel'] = $registro['nivel'];
					$_SESSION['zez_a_matricula'] = $registro['matricula'];
					$_SESSION['zez_a_nombre'] = $registro['nombre'];
					$_SESSION['zez_a_carrera'] = $registro['carrera'];
					$_SESSION['zez_a_grado'] = $registro['grado'];
					$_SESSION['zez_a_sexo'] = strtoupper($registro['sexo']);
					$_SESSION['zez_a_situacion'] = strtoupper($registro['situacion']);
					$_SESSION['zez_a_evaluados'] = $registro['evaluados'];
					$_SESSION['zez_a_num_evaluados'] = $registro['num_evaluados'];
					$_SESSION['zez_a_total_evaluaciones'] = $registro['total_evaluaciones'];
					$_SESSION['zez_a_ultimo_acceso'] = $registro['ultimo_acceso'];
					$_SESSION['zez_a_nivel_acceso'] = $registro['nivel_acceso'];
					if ($registro['nivel']!="") {
						$_SESSION['zez_a_ciclo'] = $ciclo[$registro['nivel']];
					} else {
						$_SESSION['zez_a_ciclo'] = $ciclo['LICENCIATURA'] . " / " . $ciclo['BACHILLERATO'];
					}
					$_SESSION['zez_a_sello'] = '0';
					if ("COORDINADOR"==$_SESSION['zez_a_nivel_acceso']) {
						$orden_sql2 = "SELECT * ";
						$orden_sql2 .= "FROM carreras ";
						$orden_sql2 .= "WHERE coordinador='" . $registro['nombre'] . "' ";
						$orden_sql2 .= "AND sello!='0'";
						$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
						if ($resultado_busqueda2) {
							if (mysqli_num_rows($resultado_busqueda2) > 0) {
								while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
									$_SESSION['zez_a_sello']  = $registro2['sello'];
								}
							}
							// Libera el conjunto de resultados
							mysqli_free_result($resultado_busqueda2);
						}
					}
					if ("FIDCO"==$_SESSION['zez_a_nivel_acceso']) $_SESSION['zez_a_sello']  = '1';
					$_SESSION['zez_a_jefatura'] = false;
					if (("COORDINADOR"==$_SESSION['zez_a_nivel_acceso']) or ("FIDCO"==$_SESSION['zez_a_nivel_acceso'])) {
						$orden_sql2 = "SELECT * ";
						$orden_sql2 .= "FROM departamentos ";
						$orden_sql2 .= "WHERE jefe='" . $registro['nombre'] . "'";
						$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
						if ($resultado_busqueda2) {
							if (mysqli_num_rows($resultado_busqueda2) > 0) {
								while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
									$_SESSION['zez_a_jefatura']  = true;
								}
							}
							// Libera el conjunto de resultados
							mysqli_free_result($resultado_busqueda2);
						}
					}
				}
			}
			// Libera el conjunto de resultados
			mysqli_free_result($resultado_busqueda);
		}

		if (!$participante_encontrado) {
			// Genera la orden SQL para hacer la consulta a la tabla de usuarios
			$orden_sql = "SELECT * ";
			$orden_sql .= "FROM participantes ";
			$orden_sql .= "WHERE matricula='" . strtoupper(limpiar_campo($_POST['campo11'])) . "' ";
			$orden_sql .= "AND clave='TOMAR DEL SND'";
			// Ejecuta la consulta SQL
			$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
			// Inicializa las variables usadas para los resultados
			if ($resultado_busqueda) {
				if (mysqli_num_rows($resultado_busqueda) > 0) {
					// Si hubo resultado obtiene los datos de la sesión y activa la bandera
					while($registro = mysqli_fetch_array($resultado_busqueda)) {
						if ((limpiar_campo($_POST['campo11'])!="") AND (limpiar_campo($_POST['campo12'])!="")) {
							$dato1 = limpiar_campo($_POST['campo11']);
							$dato2 = limpiar_campo($_POST['campo12']);
							if (strlen($dato2) > 15) {
								$dato2 = substr($dato2,0,15);
							}
							$parametros = array();
							$parametros[] = "dato1=" . urlencode($dato1);
							$parametros[] = "dato2=" . urlencode($dato2);
							$ch = curl_init("https://snd.umg.edu.mx/umg/vusnd.asp");
							curl_setopt($ch, CURLOPT_POST, true);
							curl_setopt($ch, CURLOPT_POSTFIELDS, implode('&', $parametros));
							curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
							curl_setopt($ch, CURLOPT_HEADER, 0);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
							$resultado = (int) curl_exec($ch);
							curl_close($ch);
						}
						if (($resultado!=0) AND (($resultado % 7)==0)) {
							$participante_encontrado = true;
							$_SESSION['zez_a_id'] = $registro['id'];
							$_SESSION['zez_a_nivel'] = $registro['nivel'];
							$_SESSION['zez_a_matricula'] = $registro['matricula'];
							$_SESSION['zez_a_nombre'] = $registro['nombre'];
							$_SESSION['zez_a_carrera'] = $registro['carrera'];
							$_SESSION['zez_a_grado'] = $registro['grado'];
							$_SESSION['zez_a_sexo'] = strtoupper($registro['sexo']);
							$_SESSION['zez_a_situacion'] = strtoupper($registro['situacion']);
							$_SESSION['zez_a_evaluados'] = $registro['evaluados'];
							$_SESSION['zez_a_num_evaluados'] = $registro['num_evaluados'];
							$_SESSION['zez_a_total_evaluaciones'] = $registro['total_evaluaciones'];
							$_SESSION['zez_a_ultimo_acceso'] = $registro['ultimo_acceso'];
							$_SESSION['zez_a_nivel_acceso'] = $registro['nivel_acceso'];
							if ($registro['nivel']!="") {
								$_SESSION['zez_a_ciclo'] = $ciclo[$registro['nivel']];
							} else {
								$_SESSION['zez_a_ciclo'] = $ciclo['LICENCIATURA'] . " / " . $ciclo['BACHILLERATO'];
							}
							$_SESSION['zez_a_sello']  = '0';
							if ("COORDINADOR"==$_SESSION['zez_a_nivel_acceso']) {
								$orden_sql2 = "SELECT * ";
								$orden_sql2 .= "FROM carreras ";
								$orden_sql2 .= "WHERE coordinador='" . $registro['nombre'] . "' ";
								$orden_sql2 .= "AND sello!='0'";
								$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
								if ($resultado_busqueda2) {
									if (mysqli_num_rows($resultado_busqueda2) > 0) {
										while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
											$_SESSION['zez_a_sello']  = $registro2['sello'];;
										}
									}
									// Libera el conjunto de resultados
									mysqli_free_result($resultado_busqueda2);
								}
							}
							if ("FIDCO"==$_SESSION['zez_a_nivel_acceso']) $_SESSION['zez_a_sello']  = '1';
							$_SESSION['zez_a_jefatura'] = false;
							if (("COORDINADOR"==$_SESSION['zez_a_nivel_acceso']) or ("FIDCO"==$_SESSION['zez_a_nivel_acceso'])) {
								$orden_sql2 = "SELECT * ";
								$orden_sql2 .= "FROM departamentos ";
								$orden_sql2 .= "WHERE jefe='" . $registro['nombre'] . "'";
								$resultado_busqueda2 = mysqli_query($base_de_datos, $orden_sql2);
								if ($resultado_busqueda2) {
									if (mysqli_num_rows($resultado_busqueda2) > 0) {
										while($registro2 = mysqli_fetch_array($resultado_busqueda2)) {
											$_SESSION['zez_a_jefatura']  = true;
										}
									}
									// Libera el conjunto de resultados
									mysqli_free_result($resultado_busqueda2);
								}
							}
						}
					}
				}
				// Libera el conjunto de resultados
				mysqli_free_result($resultado_busqueda);
			}
		}

		if ($participante_encontrado) {
			$orden_sql = "SELECT grupo ";
			$orden_sql .= "FROM grupos ";
			$orden_sql .= "WHERE matricula='" . strtoupper(limpiar_campo($_POST['campo11'])) . "'";
			$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);	
			if ($resultado_busqueda) {
				if (mysqli_num_rows($resultado_busqueda) > 0) {
					while($registro = mysqli_fetch_array($resultado_busqueda)) {
						$_SESSION['zez_a_grupos'][] = $registro['grupo'];
					}
				}
				// Libera el conjunto de resultados
				mysqli_free_result($resultado_busqueda);
			}
			// Registra el último acceso
			date_default_timezone_set($zona_horaria);
			$orden_sql = "UPDATE participantes ";
			$orden_sql .= "SET ultimo_acceso='" . date("Y-m-d") . "' ";
			$orden_sql .= "WHERE id='". $_SESSION['zez_a_id'] . "'";
			mysqli_query($base_de_datos, $orden_sql);
			$_SESSION['zez_a_ultimo_acceso'] = date("Y-m-d");
			if ("COORDINADOR" == $_SESSION['zez_a_nivel_acceso']) {
				$orden_sql = "SELECT * ";
				$orden_sql .= "FROM `carreras` ";
				$orden_sql .= "WHERE `coordinador`='" . $_SESSION['zez_a_nombre'] . "' ";
				$orden_sql .= "ORDER BY `carrera`";
				// Ejecuta la consulta SQL
				$resultado_busqueda = mysqli_query($base_de_datos, $orden_sql);
				if ($resultado_busqueda) {
					if (mysqli_num_rows($resultado_busqueda) > 0) {
						// Si hubo resultado genera la tabla
						while($registro = mysqli_fetch_array($resultado_busqueda)) {
							$_SESSION['zez_a_carrera'] = $registro['carrera'];
						}
					}
					mysqli_free_result($resultado_busqueda);
				}	
			}
			// Cierra la BDD
			mysqli_close($base_de_datos);
			// Si encontró el participante carga el menú principal que le corresponda
			date_default_timezone_set($zona_horaria);
			if (strpos(" ALUMNO PROFESOR",$_SESSION['zez_a_nivel_acceso'])!= FALSE) {
				if (date("Y-m-d") < $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) {
					header('Location: no_iniciada.php');
				} elseif (date("Y-m-d") > $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin']) {
					header('Location: cerrada.php');
				} else {
					header('Location: mis_evaluaciones.php');
				}
			} elseif (strpos(" INGLES COORDINADOR VICERRECTOR FIDCO",$_SESSION['zez_a_nivel_acceso'])!= FALSE) {
				if ((date("Y-m-d") < $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['inicio']) OR (date("Y-m-d") > $fechas[$_SESSION['zez_a_nivel']][$_SESSION['zez_a_nivel_acceso']]['fin'])) {
					header('Location: mis_reportes.php');
				} else {
					header('Location: mis_evaluaciones.php');
				}
			} elseif (strpos(" SUPERUSUARIO ADMINISTRADOR REVISOR DIRECTOR ACADEMICO ACUDE",$_SESSION['zez_a_nivel_acceso'])!= FALSE) {
				header('Location: mis_reportes.php');
			} else {
				header('Location: salir.php');
			}
			exit;
		} else {
			// Cierra la BDD
			mysqli_close($base_de_datos);
			// Enviar al error
			header('Location: error_ingreso.php');
		}
	} else {
?>
<!DOCTYPE html>
<html lang="es">
<?php include_once HEADER; ?>
	<body>
		<div class="container text-center">
			<div class="card">
				<div class="card-header"><?php include_once HEAD2; ?></div>
				<div class="card-body">
					<?php if ($debug==true) { ?>
						<form enctype="multipart/form-data" class="form py-2" method="post" name="loginform" id="loginform" action="index.php" autocomplete="off" accept-charset="UTF-8">
							<div class="form-floating ">
								<label for="floatingInput"><strong>Correo</strong></label>
								<input type="email" class="form-control" id="floatingInput" name="campo11" placeholder="buzon@umg.edu.mx" required="required"/>
							</div>
							<div class="form-floating py-2">
								<label for="floatingPassword"><strong>Contraseña</strong></label>
								<input type="password" class="form-control" id="floatingPassword" name="campo12" placeholder="Contraseña" required="required" />
							</div>
							<div class="form-floating py-2">
								<input type="submit" class='form-control btn btn-primary btn-block text-light' name="enviar" value="Entrar" />
							</div>
						</form>
					<?php }else{ 
				    $protocol = $_SERVER['PROTOCOL'] = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
				    $_SESSION['location']=$protocol . '://' . $_SERVER['HTTP_HOST']. '/'.UBI.'/login_dyc.php';
				    $_SESSION['logout']=$protocol . '://' . $_SERVER['HTTP_HOST']. '/'.UBI.'/logout.php';
				    // Redirecciones remotas
				    $uri_g_bclb = 'https://benito.marista.mx/g-bclb.php?location='.encriptar_ligero($_SESSION['location']).'&logout='.encriptar_ligero($_SESSION['logout']);
				    $uri_g_umg = 'https://benito.marista.mx/g-umg.php?location='.encriptar_ligero($_SESSION['location']).'&logout='.encriptar_ligero($_SESSION['logout']);
				    $uri_m_bclb = 'https://benito.marista.mx/m-bclb.php?location='.encriptar_ligero($_SESSION['location']).'&logout='.encriptar_ligero($_SESSION['logout']);
				    $uri_m_umg = 'https://benito.marista.mx/m-umg.php?location='.encriptar_ligero($_SESSION['location']).'&logout='.encriptar_ligero($_SESSION['logout']);
				    
				    if (isset($_SESSION['error'])) {
				      echo "<script>$(#respuesta_iniciar_sesion).html(".$_SESSION['error'].");</script>";
				    }
						?>
						<div class="row ">
		          <div class="col-md-2"></div>
		          <div class="col-md-8">
		            <div id="respuesta_iniciar_sesion"></div>
		            <div class="row">
		              <div class="col-md-12">
		                <h5 class="h5 mb-3 fw-normal"><strong>INGRESA CON</strong></h5>
		                <label></label>
		              </div>
		              <!--div class="col-md-6">
		                <div class="form-group">
		                  <a class='btn btn-danger btn-block' href="<?php echo $uri_g_bclb ?>"><strong><i class='fab fa-google'></i> Google BCLB</strong></a>
		                </div>
		              </div-->
		              <div class="col-md-6">
		                <div class="form-group">
		                  <a class='btn btn-danger btn-block' href="<?php echo $uri_g_umg ?>"><strong><i class='fab fa-google'></i> Google UMG</strong></a>
		                </div>
		              </div>
		              <!--div class="col-md-6">
		                <div class="form-group">
		                  <a class='btn btn-primary btn-block'  href="<?php echo $uri_m_bclb ?>" ><strong><i class='fab fa-windows'></i> Microsoft BCLB</strong></a>
		                </div>
		              </div-->
		              <div class="col-md-6">
		                <div class="form-group">
		                  <a class='btn btn-primary btn-block'  href="<?php echo $uri_m_umg ?>" ><strong><i class='fab fa-windows'></i> Microsoft UMG</strong></a>
		                </div>
		              </div>
		            </div>
		          </div>
		          <div class="col-md-2"></div>
		        </div>
					<?php } ?>

				</div>
			</div>
		</div>
	</body>

	
</html>
<?php
	}
?>
