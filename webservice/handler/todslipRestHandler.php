<?php
require_once("../base/SimpleRest.php");
require_once("../info/connection.php");		
require_once("../data/todslipforfieldslip.php");

class todslipRestHandler extends SimpleRest 
{
	function posttodslip($view,$slipboyuserid,$input)
	{	
		$insertareadetailstatuscode=0;
		if ($view == 'upload')
		{
			$connection = agriculture_connection();
			$todslip1 = new todslipforfieldslip($connection);
            $i=0;
			foreach($input as $obj) 
        	{
                $seasoncode = $obj["seasoncode"];
                $todslipnumber = $obj["todslipnumber"];
				$result1=$todslip1->updatetodslip($seasoncode,$todslipnumber);
                if ($result1==1)
                {
                    $insertareadetailstatuscode=1;
                }
                else
                {
                    $insertareadetailstatuscode=0;
                    break;
                }
			}
			if ($insertareadetailstatuscode==1)
			{
				oci_commit($connection);
			}
			else
			{
				oci_rollback($connection);
			}
		}

		if($insertareadetailstatuscode==1)
		{
			$statusCode = 201;
		} 
		else
		{
			$statusCode = 500;
		} 
		return $statusCode;
		//$requestContentType = $_SERVER['HTTP_ACCEPT'];
		//$this ->setHttpHeaders($requestContentType, $statusCode);
		
		/*  if(strpos($requestContentType,'application/json') !== false)
		{
			$response = $this->encodeJson($statusCode);
			echo $response;
		} 
		else if(strpos($requestContentType,'text/html') !== false)
		{
			$response = $this->encodeHtml($statusCode);
			echo $response;
		} 
		else if(strpos($requestContentType,'application/xml') !== false)
		{
			$response = $this->encodeXml($statusCode);
			echo $response;
		} */
	}
	public function encodeHtml($responseData)
	{
		$htmlResponse = "<table border='1'>";
		foreach($responseData as $key=>$value) {
    			$htmlResponse .= "<tr><td>". $key. "</td><td>". $value. "</td></tr>";
		}
		$htmlResponse .= "</table>";
		return $htmlResponse;		
	}
	
	public function encodeJson($responseData) 
	{
		$jsonResponse = json_encode($responseData);
		return $jsonResponse;		
	}
	
	public function encodeXml($responseData)
	{
		// creating object of SimpleXMLElement
		$xml = new SimpleXMLElement('<?xml version="1.0"?><onlinecrushing></onlinecrushing>');
		foreach($responseData as $key=>$value)
		{
			$xml->addChild($key, $value);
		}
		return $xml->asXML();
	}
}
?>