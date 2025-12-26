<?php

    function enstrasc($string)
    {
		$asciiString = '';
		for($i = 0; $i <= strlen($string); $i++)
		{

		     $asciiString .= str_pad(ord($string[$i]), 3, '0', STR_PAD_LEFT);

		}
		return $asciiString;

	}

	function enascchr($string)
    {
	   $chrString = '';
	   $string=strrev($string);
	for($i = 0; $i <= strlen($string); $i++)
	{

	   if (($i%3 == 0) and ($i%6 == 0))
	    {
	     	$chrString .= chr(70+$string[$i]);
	 	}
	 	else if (($i%3 == 0) and ($i%9 == 0))
	    {
	     	$chrString .= chr(53+$string[$i]);
	 	}
	 	else if (($i%3 == 0) and ($i%6 != 0) and ($i%9 != 0))
	    {
	     	$chrString .= chr(65-$string[$i]);
	 	}
	 	else if ($i%3 == 1)
	 	{
	 		$chrString .= chr(103-$string[$i]);	
	 	}
	 	else if ($i%3 == 2) 
	 	{
	 		$chrString .= chr(107-$string[$i]);	
	 	}	
	}
	return trim($chrString);

	}

    function fnEncrypt_old($strQString)
	{
	return enascchr(enstrasc(enascchr(enstrasc($strQString))));
	}


function dechrasc($string)
    {
	   $chrString = '';
	for($i = 0; $i <= strlen($string); $i++)
	{

	   if (($i%3 == 0) and ($i%6 == 0))
	    {
	     	$chrString .= abs(ord($string[$i])-70);
	 	}
	 	else if (($i%3 == 0) and ($i%9 == 0))
	    {
	     	$chrString .= abs(ord($string[$i])-53);
	 	}
	 	else if (($i%3 == 0) and ($i%6 != 0) and ($i%9 != 0))
	    {
	     	$chrString .= abs(65-ord($string[$i]));
	 	}
	 	else if ($i%3 == 1)
	 	{
	 		$chrString .= abs(103-ord($string[$i]));	
	 	}
	 	else if ($i%3 == 2) 
	 	{
	 		$chrString .= abs(107-ord($string[$i]));	
	 	}	
	}
	$chrString=strrev($chrString);
	return trim($chrString);
	}

function deascstr($string)
    {
		$asciiString = '';
		if (strlen($string) % 3 == 0)
		{
			for($i = 0; $i <= strlen($string); $i+=3)
			{
				$asciiString .= chr(substr($string, $i,3));
			}
		}
		else
		{
			$asciiString = '';
		}
		return $asciiString;

	}

function fnDecrypt_old($strQString)
	{
		 try
    		{
				return deascstr(dechrasc(deascstr(dechrasc($strQString))));
			}
		catch (Exeception $Ex)
		{
			echo 'Communication Error';
		}
	}

?>