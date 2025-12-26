<?php
    function fnEncrypt($strQString)
	{
		try
      	{
			$key = 'omshreeganeshaynamah';
			$enc = urlencode(openssl_encrypt($strQString, 'AES-128-ECB', $key));
			return $enc;
		}
		catch (Exception $ex)
		{
			die('Communication error');
		}
	}

	function fnDecrypt($strQString)
	{
		try
      	{
			$key = 'omshreeganeshaynamah';
			$dec = openssl_decrypt($strQString, 'AES-128-ECB', $key);
			return $dec;
		}
		catch (Exception $ex)
		{
			die('Communication error');
		}
	}
?>