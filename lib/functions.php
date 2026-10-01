<?php
	if (session_status() === PHP_SESSION_NONE) { session_start(); }
	function limpiar_campo($dirty){
		unset($config);
		unset($purifier);
		unset($clean);
		$config = HTMLPurifier_Config::createDefault();
		$config->set('HTML.Allowed', '');
		$purifier = new HTMLPurifier($config);
		$clean = $purifier->purify($dirty);
		return $clean;
	}

	function obtener_ip_cliente() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	function encriptar($cadena){
		$secreto = $_SESSION['zez_a_id'] . $_SESSION['zez_a_matricula'] . $_SESSION['zez_a_evaluados'] . $_SESSION['zez_a_num_evaluados'] . $_SESSION['zez_a_ultimo_acceso'];
		$metodoCipher = 'AES-256-CBC';
		$separador = '::';
		$ivLongitud = openssl_cipher_iv_length($metodoCipher);
		$llave = base64_decode($secreto);
		$iv = base64_encode(openssl_random_pseudo_bytes($ivLongitud));
		$iv = substr($iv, 0, $ivLongitud);
		$datosEncriptados = openssl_encrypt($cadena, $metodoCipher, $llave, 0, $iv);
		return base64_encode($datosEncriptados.$separador.$iv);
	}

	function desencriptar($cadena){
		$secreto = $_SESSION['zez_a_id'] . $_SESSION['zez_a_matricula'] . $_SESSION['zez_a_evaluados'] . $_SESSION['zez_a_num_evaluados'] . $_SESSION['zez_a_ultimo_acceso'];
		$metodoCipher = 'AES-256-CBC';
		$separador = '::';
		$ivLongitud = openssl_cipher_iv_length($metodoCipher);
		$llave = base64_decode($secreto);
		list($datosEncriptados, $iv) = explode($separador, base64_decode($cadena), 2);
		$iv = substr($iv, 0, $ivLongitud);
		return openssl_decrypt($datosEncriptados, $metodoCipher, $llave, 0, $iv);
	}

	function a_romano($decimalInteger) {
		$n = intval($decimalInteger);
		$res = '';
		$roman_numerals = array(
			'M'  => 1000,
			'CM' => 900,
			'D'  => 500,
			'CD' => 400,
			'C'  => 100,
			'XC' => 90,
			'L'  => 50,
			'XL' => 40,
			'X'  => 10,
			'IX' => 9,
			'V'  => 5,
			'IV' => 4,
			'I'  => 1);
		foreach ($roman_numerals as $roman => $numeral) {
			$matches = intval($n / $numeral);
			$res .= str_repeat($roman, $matches);
			$n = $n % $numeral;
		}
		return $res;
	}

	function pone_comillas($texto){ return str_replace('çÇç', '"', $texto); }

	function a_letras($decimalInteger) {
		$n = intval($decimalInteger);
		$res = '';
		if ($n>0) {
			if ($n<=26) {
				$res = chr($n+64);
			} else {
				$res = chr(intval(($n-1)/26)+64);
				$res .= chr(($n-(intval(($n-1)/26)*26))+64);
			}
		}
		return $res;
	}

	function quita_comillas($texto){ return str_replace('"', 'çÇç', $texto); }

	function limpiarcampo($dirty){
		unset($config);
		unset($purifier);
		unset($clean);
		$config = HTMLPurifier_Config::createDefault();
		$config->set('HTML.Allowed', '');
		$purifier = new HTMLPurifier($config);
		$clean = $purifier->purify($dirty);
		return $clean;
	}

	function ahora($tipo){
    $hoy = getdate();
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
    }      
    return $actual;
  }
  
  function transforma_fecha($fecha){
    $dato = explode("-",$fecha);
    $ano=$dato[0];
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
    $dia=$dato[2];
    $referencia=mb_strtoupper($dia." de ".$mes." del ".$ano);
    return $referencia;
  }
?>
