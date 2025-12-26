<?php
require_once("..\base\SimpleRest.php");
require_once("..\data\onlinecrushing.php");
require_once("..\info\connection.php");		
class onlinecrushingRestHandler extends SimpleRest 
{
	function getonlinecrushingrecord($fromdate,$todate,$farmercode,$offset,$rowsperpage,$view)
	{	
		$connection = production_connection();
		$onlinecrushing1 = new onlinecrushing($connection);
		$onlinecrushing1->fromdatetime = $fromdate;
		$onlinecrushing1->todatetime = $todate;
		$onlinecrushing1->farmercode = $farmercode;
		$onlinecrushing1->offset = $offset;
		$onlinecrushing1->rowsperpage = $rowsperpage;
		if ($view == 'onlinecrushing')
		{
			$rawData = $onlinecrushing1->getonlinecrushingrecord();
		}
		if(empty($rawData))
		{
			$statusCode = 404;
			$rawData = array('error' => 'No record found!');
		} 
		else
		{
			$statusCode = 200;
		}

		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		$this ->setHttpHeaders($requestContentType, $statusCode);
				
		if(strpos($requestContentType,'application/json') !== false)
		{
			$response = $this->encodeJson($rawData);
			echo $response;
		} 
		else if(strpos($requestContentType,'text/html') !== false)
		{
			$response = $this->encodeHtml($rawData);
			echo $response;
		} 
		else if(strpos($requestContentType,'application/xml') !== false)
		{
			$response = $this->encodeXml($rawData);
			echo $response;
		}
	}
	function gettotalrecords($fromdate,$todate,$farmercode,$view)
	{	
		$connection = production_connection();
		$onlinecrushing1 = new onlinecrushing($connection);
		$onlinecrushing1->fromdatetime = $fromdate;
		$onlinecrushing1->todatetime = $todate;
		$onlinecrushing1->farmercode = $farmercode;
		if ($view == 'totalrecords')
		{
			$rawData = $onlinecrushing1->gettotalrecords();
		}
		if(empty($rawData))
		{
			$statusCode = 404;
			$rawData = array('error' => 'No record found!');
		} 
		else
		{
			$statusCode = 200;
		}

		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		$this ->setHttpHeaders($requestContentType, $statusCode);
				
		if(strpos($requestContentType,'application/json') !== false)
		{
			$response = $this->encodeJson($rawData);
			echo $response;
		} 
		else if(strpos($requestContentType,'text/html') !== false)
		{
			$response = $this->encodeHtml($rawData);
			echo $response;
		} 
		else if(strpos($requestContentType,'application/xml') !== false)
		{
			$response = $this->encodeXml($rawData);
			echo $response;
		}
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