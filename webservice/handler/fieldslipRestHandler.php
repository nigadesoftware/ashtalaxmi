<?php
require_once("../base/SimpleRest.php");
require_once("../info/connection.php");		
require_once("../data/fieldslip_db_oracle.php");
require_once("../data/tripdetail_db_oracle.php");
require_once("../data/tripheader_db_oracle.php");

class fieldslipRestHandler extends SimpleRest 
{
	function postfieldslip($view,$slipboyuserid,$input)
	{	
		$insertareadetailstatuscode=0;
		if ($view == 'upload')
		{
			$connection = agriculture_connection();
			$tripheader1 = new tripheader($connection);			
            $i=0;
			foreach($input as $obj) 
        	{
				$fieldslip1 = new fieldslip($connection);
				$fieldslip1->seasoncode = $obj["seasoncode"];
                $fieldslip1->fieldslipnumber = $obj["fieldslipnumber"];
                $fieldslip1->fieldslipdate = $obj["fieldslipdate"];
                $fieldslip1->plotnumber = $obj["plotnumber"];
                $fieldslip1->farmercategorycode = $obj["farmercategorycode"];
				$fieldslip1->villagecode = $obj["villagecode"];
				if (isset($obj["subvillagecode"]))
				{
					$fieldslip1->subvillagecode = $obj["subvillagecode"];
				}
				$fieldslip1->vehiclecategorycode = $obj["vehiclecategorycode"];
				if (isset($obj["vehiclecode"]))
				{
					$fieldslip1->vehiclecode = $obj["vehiclecode"];
				}
				if (isset($obj["tyregadicode"]))
				{
					$fieldslip1->tyregadicode = $obj["tyregadicode"];
				}
                $fieldslip1->hrsubcontractorcode = $obj["hrsubcontractorcode"];
                $fieldslip1->trsubcontractorcode = $obj["trsubcontractorcode"];
                $fieldslip1->hrtrsubcontractorcode = $obj["hrtrsubcontractorcode"];
                $fieldslip1->caneconditioncode = $obj["caneconditioncode"];
				//$fieldslip1->slipboycode = $obj["slipboycode"];
				$fieldslip1->slipboycode = $slipboyuserid;
				if (isset($obj["distance"]))
				{
					$fieldslip1->distance = $obj["distance"];
				}
				$fieldslip1->layercode = $obj["layercode"];
				if (isset($obj["trailornumber"]))
				{
					$fieldslip1->trailornumber = $obj["trailornumber"];
				}
				$fieldslip1->containercode = $obj["containercode"];
				if (isset($obj["viadistance"]))
				{
					$fieldslip1->viadistance = $obj["viadistance"];
				}
				$fieldslip1->todslipnumber = $obj["todslipnumber"];
				if ($i==0)
				{
					if ($tripheader1->fetch($fieldslip1->seasoncode,$fieldslip1->fieldslipnumber)==false)
					{
						$tripheader1->seasoncode = $fieldslip1->seasoncode;
						$tripheader1->vehiclecategorycode = $fieldslip1->vehiclecategorycode;
						$tripheader1->vehiclecode = $fieldslip1->vehiclecode;
						$tripheader1->tyregadicode = $fieldslip1->tyregadicode;
						$tripheader1->slipboycode = $fieldslip1->slipboycode;
						$result=$tripheader1->insert();
					}
					else
					{
						$result=1;
					}
					$i++;
				}
				else
				{
					$result=1;
				}
				if ($result==1)
				{
					$result1=$fieldslip1->delete();
					$result2=$fieldslip1->insert();
					$tripdetail1 = new tripdetail($connection);   
					$tripdetail1->seasoncode = $tripheader1->seasoncode;
					$tripdetail1->tripnumber = $tripheader1->tripnumber;
					$tripdetail1->fieldslipnumber = $fieldslip1->fieldslipnumber;
					//$result3=$tripdetail1->delete();
					$result3=$tripdetail1->insert();
					if ($result1==1 and $result2==1 and $result3==1)
					{
						$insertareadetailstatuscode=1;
					}
					else
					{
						$insertareadetailstatuscode=0;
						break;
					}
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