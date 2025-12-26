<?php
require_once("..\base\SimpleRest.php");
require_once("..\info\marathi2english.php");
require_once("..\info\connection.php");		
class mtoeRestHandler extends SimpleRest 
{
	function convert($name) 
	{	
		$rawData = array();
        $engname = trim(MarathitoEnglish($name));
        $rawData[] = $engname;
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
		else if(strpos($requestContentType,'text/plain') !== false)
		{
			$response = $this->encodeText($rawData);
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
		$xml = new SimpleXMLElement('<?xml version="1.0"?><name></name>');
		foreach($responseData as $key=>$value) 
		{
			$xml->addChild($key, $value);
		}
		return $xml->asXML();
	}
	public function encodeText($responseData) 
	{
		$jsonResponse = implode(" ",$responseData);
		return $jsonResponse;		
	}
}
?>