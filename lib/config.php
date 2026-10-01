<?php
	// Archivo de configuración
		if (session_status() === PHP_SESSION_NONE) {session_start();}
		define('UBI', 'eva2027i' );
		$_SESSION['ubi']=UBI;
		define( 'A_RAIZ', $_SERVER['DOCUMENT_ROOT'].'/'.UBI.'/' );
		define( 'A_LIB', A_RAIZ.'lib/' );
		define( 'HEADER', A_RAIZ.'vendor/header.php' );
		define( 'HEAD', A_RAIZ.'vendor/head.php' );
		define( 'HEAD2', A_RAIZ.'vendor/head2.php' );
		define( 'A_VIEW', A_RAIZ.'view/' );
		define( 'JS_SCRIPT', A_LIB.'functions.js' );
		define( 'NUMERO_APLICACION', '1' );
	//Incluimos las librerias
		require_once A_LIB.'purifier/HTMLPurifier.auto.php';
    	include_once A_LIB.'self/self_form_sender.php';
    	include_once A_LIB.'self/self_ncrptcn.php';
	// Evaluación
		$ciclo['LICENCIATURA'] = "2026-II";
		$ciclo['BACHILLERATO'] = "2026-II";
	// Servidor donde está alojada
		$bdd_servidor = "localhost";
	// Nombre de la base de datos
		//$bdd_nombre = "eva2027i";
		$bdd_nombre = "eva2027i";
	// Usuario que tiene acceso de lectura y escritura a la base de datos
		//$bdd_usuario = "evaluaciones";
		$bdd_usuario = "root";
	// Contraseña del usuario
		//$bdd_clave = "ERA1P@j@rillo";
		$bdd_clave = "";
	// Zona horaria
		$zona_horaria = "America/Mexico_City";
		date_default_timezone_set($zona_horaria);
	// Máximo de caraceteres en respuestas abiertas
		$maximo_caracteres = 700;
	// Fechas inicio y fin para evaluar
	// $fechas['NIVEL'][nivel_acceso]['inicio/fin'] = "AAAA-MM-DD";
	//SANDBOX//
		$fechas['BACHILLERATO']['ALUMNO']['inicio'] = "2026-01-01";
		$fechas['BACHILLERATO']['ALUMNO']['fin'] = "2026-12-31";

		$fechas['BACHILLERATO']['PROFESOR']['inicio'] = "2026-01-01";
		$fechas['BACHILLERATO']['PROFESOR']['fin'] = "2026-12-31";

		$fechas['LICENCIATURA']['ALUMNO']['inicio'] = "2026-01-01";
		$fechas['LICENCIATURA']['ALUMNO']['fin'] = "2026-12-31";

		$fechas['LICENCIATURA']['PROFESOR']['inicio'] = "2026-01-01";
		$fechas['LICENCIATURA']['PROFESOR']['fin'] = "2026-12-31";

		$fechas['LICENCIATURA']['COORDINADOR']['inicio'] = "2026-01-01";
		$fechas['LICENCIATURA']['COORDINADOR']['fin'] = "2026-12-31";

		$fechas['LICENCIATURA']['FIDCO']['inicio'] = "2026-01-01";
		$fechas['LICENCIATURA']['FIDCO']['fin'] = "2026-12-31";

		$fechas['LICENCIATURA']['VICERRECTOR']['inicio'] = "2026-01-01";
		$fechas['LICENCIATURA']['VICERRECTOR']['fin'] = "2026-12-31";
	//PRODUCCION//
	/*
		$fechas['BACHILLERATO']['ALUMNO']['inicio'] = "2026-05-14";
		$fechas['BACHILLERATO']['ALUMNO']['fin'] = "2026-05-19";

		$fechas['BACHILLERATO']['PROFESOR']['inicio'] = "2026-05-14";
		$fechas['BACHILLERATO']['PROFESOR']['fin'] = "2026-05-19";

		$fechas['LICENCIATURA']['ALUMNO']['inicio'] = "2026-04-17";
		$fechas['LICENCIATURA']['ALUMNO']['fin'] = "2026-04-26";

		$fechas['LICENCIATURA']['PROFESOR']['inicio'] = "2026-04-17";
		$fechas['LICENCIATURA']['PROFESOR']['fin'] = "2026-04-26";

		$fechas['LICENCIATURA']['COORDINADOR']['inicio'] = "2026-04-17";
		$fechas['LICENCIATURA']['COORDINADOR']['fin'] = "2026-04-26";

		$fechas['LICENCIATURA']['FIDCO']['inicio'] = "2026-04-17";
		$fechas['LICENCIATURA']['FIDCO']['fin'] = "2026-04-26";

		$fechas['LICENCIATURA']['VICERRECTOR']['inicio'] = "2026-04-17";
		$fechas['LICENCIATURA']['VICERRECTOR']['fin'] = "2026-04-26";
	*/
	// Tiempos para impresión de reportes
		define( 'TIEMPO_IMPRESION', '3000' );
		include_once 'functions.php';
	//
?>
