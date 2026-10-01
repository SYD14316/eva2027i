<?php
	// Establecer la zona horaria
		date_default_timezone_set('America/Monterrey');
	// Obtengo y asigno los datos de la DB
		$user="root";
		//$user="evaluaciones";
		$passwd="";
		//$passwd="ERA1P@j@rillo";
		$host="localhost";
		$dbname="eva2027i";
		$port="3306";
	// Creo la cadena de conexión con charset utf8
		$dsn="mysql:host=$host;dbname=$dbname;port=$port;charset=utf8";
	// Trato de realizar la conexión y configuro el charset
		try {
			$conn = new PDO($dsn, $user, $passwd, [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
			]);
		} catch (PDOException $e) {
		   // Almaceno el error en una variable
			echo $error = $e->getMessage();
			echo "Error al conectar con la base de datos";
		}
	//
?>
