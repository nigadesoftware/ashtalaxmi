<?php
    function fnEncrypt($strQString)
	{
		$key = 'shree'.substr($_SESSION["cursession"],14,-7).'ganeshaynamah';
		$enc = urlencode(openssl_encrypt($strQString, 'AES-128-ECB', $key));
		return $enc;
	}

	function fnDecrypt($strQString)
	{
		$key = 'shree'.substr($_SESSION["cursession"],14,-7).'ganeshaynamah';
		$dec = openssl_decrypt($strQString, 'AES-128-ECB', $key);
		return $dec;
	}
	function fnEncryptpass($strQString)
	{
		$enc = openssl_encrypt($strQString, 'AES-128-ECB', 'ghghyguyuybhbhbhjhjy');
		return $enc;
	}

	function fnDecryptpass($strQString)
	{
		$dec = openssl_decrypt($strQString, 'AES-128-ECB', 'ghghyguyuybhbhbhjhjy');
		return $dec;
	}
?>