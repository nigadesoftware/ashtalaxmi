<?php
require_once("..\base\SimpleRest.php");
require_once("..\data\crushing.php");
require_once("..\info\connection.php");		
class crushingRestHandler extends SimpleRest 
{
	function getcrushingrecord($fromdate,$todate,$view)
	{	
		$connection = production_connection();
		$crushing1 = new crushing($connection);
		if ($view == 'sectioncrushing')
		{
			$crushing1->todatetime = $todate;
		}
		else
		{
			$crushing1->fromdatetime = $fromdate;
			$crushing1->todatetime = $todate;
		}
		if ($view == 'shiftwise')
		{
			$rawData = $crushing1->getshiftwisecrushingrecord();
		}
		elseif ($view == 'vehiclewise')
		{
			$rawData = $crushing1->getvehiclewisecrushingrecord();
		}
		elseif ($view == 'vehiclewiseshiftwise')
		{
			$rawData = $crushing1->getvehiclewiseshiftwisecrushingrecord();
		}
		elseif ($view == 'sectioncrushing')
		{
			$rawData = $crushing1->getsectioncrushingrecord();
		}
		elseif ($view == 'shiftvehiclecrushing')
		{
			$rawData = $crushing1->getshiftvehiclecrushingrecord();
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

	function getsectioncrushingrecord($todate,$view)
	{	
		$connection = production_connection();
		$crushing1 = new crushing($connection);
		$crushing1->todatetime = $todate;
		if ($view == 'sectioncrushing')
		{
			$rawData = $crushing1->getsectioncrushingrecord();
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

	function getshiftvehiclecrushingrecord($todate,$view)
	{	
		$connection = production_connection();
		$crushing1 = new crushing($connection);
		$crushing1->todatetime = $todate;
		if ($view == 'shiftvehiclecrushing')
		{
			$rawData = $crushing1->getshiftvehiclecrushingrecord();
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
		$xml = new SimpleXMLElement('<?xml version="1.0"?><crushing></crushing>');
		foreach($responseData as $key=>$value)
		{
			$xml->addChild($key, $value);
		}
		return $xml->asXML();
	}
}
?>