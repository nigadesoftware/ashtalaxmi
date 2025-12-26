<?php
require_once("..\base\SimpleRest.php");
require_once("..\data\onlineplantationvillage.php");
require_once("..\info\connection.php");		
class onlineplantationRestHandler extends SimpleRest 
{
	function getonlineplantationrecord($seasonyear,$villagecode,$offset,$rowsperpage,$view)
	{	
		$connection = production_connection();
		$onlineplantation1 = new onlineplantation($connection);
		$onlineplantation1->seasonyear = $seasonyear;
		$onlineplantation1->villagecode = $villagecode;
		$onlineplantation1->offset = $offset;
		$onlineplantation1->rowsperpage = $rowsperpage;
		if ($view == 'onlineplantation')
		{
			$rawData = $onlineplantation1->getonlineplantationrecord();
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
	function gettotalrecords($seasonyear,$villagecode,$view)
	{	
		$connection = production_connection();
		$onlineplantation1 = new onlineplantation($connection);
		$onlineplantation1->seasonyear = $seasonyear;
		$onlineplantation1->villagecode = $villagecode;
		if ($view == 'totalrecords')
		{
			$rawData = $onlineplantation1->gettotalrecords();
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
	function getsectvillhanrecord($seasonyear,$villagecode,$view)
	{	
		$connection = production_connection();
		$onlineplantation1 = new onlineplantation($connection);
		$onlineplantation1->seasonyear = $seasonyear;
		$onlineplantation1->villagecode = $villagecode;
		if ($view == 'sectvillhan')
		{
			$rawData = $onlineplantation1->getsectvillhanrecord();
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
	function getsecthanrecord($seasonyear,$view)
	{	
		$connection = production_connection();
		$onlineplantation1 = new onlineplantation($connection);
		$onlineplantation1->seasonyear = $seasonyear;
		if ($view == 'secthan')
		{
			$rawData = $onlineplantation1->getsecthanrecord();
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
	function getvillagelist($view)
	{	
		$connection = production_connection();
		$onlineplantation1 = new onlineplantation($connection);
		if ($view == 'villagelist')
		{
			$rawData = $onlineplantation1->getvillagelist();
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
	function getvillagebysectionlist($sectioncode,$view)
	{	
		$connection = production_connection();
		$onlineplantation1 = new onlineplantation($connection);
		if ($view == 'villagebysectionlist')
		{
			$onlineplantation1->sectioncode = $sectioncode;
			$rawData = $onlineplantation1->getvillagebysectionlist();
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
	function getseasonyearlist($view)
	{	
		$connection = production_connection();
		$onlineplantation1 = new onlineplantation($connection);
		if ($view == 'seasonyearlist')
		{
			$rawData = $onlineplantation1->getseasonyearlist();
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
		$xml = new SimpleXMLElement('<?xml version="1.0"?><onlineplantation></onlineplantation>');
		foreach($responseData as $key=>$value)
		{
			$xml->addChild($key, $value);
		}
		return $xml->asXML();
	}
}
?>