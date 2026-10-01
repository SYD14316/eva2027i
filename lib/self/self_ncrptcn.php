<?php



	// ╔════════════════════════════════════════════════════════╗

	// ║               FUNCIONES DE ENCRIPTACIÓN                ║

	// ╠════════════════════════════════════════════════════════╣

	// ║    PROGRAMADO POR: ING. OSCAR ALEJANDRO RUIZ GARCÍA    ║

	// ║             VERSIÓN 1.0   /   FEBRERO 2022             ║

	// ╚════════════════════════════════════════════════════════╝



	function _codifica($original, $inicio=0){

		$procesada = md5(sha1($original));

		$resultado = "";

		$inicio--;

		$largo = strlen($original);

		for ($contador=0; $contador<$largo; $contador++) {

			$caracter = ord(substr($original, $contador, 1));

			if ($contador<$inicio) {

				$resultado .= chr($caracter);

			} else {

				if (($caracter >= 48) and ($caracter<=56)) {

					$resultado .= chr($caracter+1);

				} elseif ($caracter == 57) {

					$resultado .= chr(48);

				} elseif (($caracter >= 98) and ($caracter<=122)) {

					$resultado .= chr($caracter-1);

				} elseif ($caracter == 97) {

					$resultado .= chr(122);

				} else {

					$resultado .= chr($caracter);

				}

			}

		}

		return $resultado;

	}



	function _decodifica($codificado, $inicio=0){

		$resultado = "";

		$inicio--;

		$largo = strlen($codificado);

		for ($contador=0; $contador<$largo; $contador++) {

			$caracter = ord(substr($codificado, $contador, 1));

			if ($contador<$inicio) {

				$resultado .= chr($caracter);

			} else {

				if (($caracter >= 49) and ($caracter<=57)) {

					$resultado .= chr($caracter-1);

				} elseif ($caracter == 48) {

					$resultado .= chr(57);

				} elseif (($caracter >= 97) and ($caracter<=121)) {

					$resultado .= chr($caracter+1);

				} elseif ($caracter == 122) {

					$resultado .= chr(97);

				} else {

					$resultado .= chr($caracter);

				}

			}

		}

		return $resultado;

	}



	function encriptar_ligero($cadena, $sal = ''){

		$secreto = ('' == $sal) ? 'Marcelino Champagnat #2981. Salt únicamente usada para el inicio.' : $sal;

		$metodoCipher = 'AES-256-CBC';

		$separador = '::';

		$ivLongitud = openssl_cipher_iv_length($metodoCipher);

		$llave = base64_decode($secreto);

		$iv = base64_encode(openssl_random_pseudo_bytes($ivLongitud));

		$iv = substr($iv, 0, $ivLongitud);

		$datosEncriptados = openssl_encrypt(('17-3-76'.$cadena), $metodoCipher, $llave, 0, $iv);

		return _codifica(base64_encode($datosEncriptados.$separador.$iv));

	}



	function desencriptar_ligero($cadena, $sal = ''){

		$secreto = ('' == $sal) ? 'Marcelino Champagnat #2981. Salt únicamente usada para el inicio.' : $sal;

		$metodoCipher = 'AES-256-CBC';

		$separador = '::';

		$ivLongitud = openssl_cipher_iv_length($metodoCipher);

		$llave = base64_decode($secreto);

		list($datosEncriptados, $iv) = explode($separador, base64_decode(_decodifica($cadena)), 2);

		$iv = substr($iv, 0, $ivLongitud);

		return substr(openssl_decrypt($datosEncriptados, $metodoCipher, $llave, 0, $iv),7);

	}
?>