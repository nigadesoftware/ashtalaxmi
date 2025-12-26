<?php
require_once("../base/SimpleRest.php");
require_once("../data/plotmeasured.php");
require_once("../info/connection.php");		
class plotmeasuredRestHandler extends SimpleRest 
{
	Public $connection;
	function postmeasuredplot($view,$measurementuserid,$input)
	{	
		$insertareadetailstatuscode=1;
		if ($view == 'upload')
		{
			$this->connection = agriculture_connection();
			$plantationplotareadetail1 = new plantationplotareadetail($this->connection);
			$i=0;
			$result=0;
			$result1=0;
			$result2=0;
			$result3=0;
			foreach($input as $obj) 
        	{
				if ($obj["serialnumber"]!="99")
				{
					$plantationplotareadetail1->seasoncode = $obj["seasoncode"];
					$plantationplotareadetail1->plotnumber = $obj["plotnumber"];
					$plantationplotareadetail1->serialnumber = $obj["serialnumber"];
					$plantationplotareadetail1->latitude = $obj["latitude"];
					$plantationplotareadetail1->longitude = $obj["longitude"];
					$plantationplotareadetail1->measurementuserid = $measurementuserid;
					$result=$plantationplotareadetail1->delete();
					$result=$plantationplotareadetail1->insert();
				}
				else
				{
					$plantationplotareadetail1->seasoncode = $obj["seasoncode"];
					$plantationplotareadetail1->plotnumber = $obj["plotnumber"];
					$plantationplotareadetail1->measurementuserid = $measurementuserid;
					$obj["seasoncode"];
					$name=$obj["seasoncode"].'_'.$obj["plotnumber"];
					$hexpic=$obj["selfie"];
					$data = pack("H" . strlen($hexpic), $hexpic);
					$result1 = file_put_contents("../upload/".$name.".jpeg", $data);
					if ($obj["idproof"]!="")
					{
						$name=$obj["seasoncode"].'_'.$obj["plotnumber"];
						$hexpic=$obj["idproof"];
						$data = pack("H" . strlen($hexpic), $hexpic);
						$result2 = file_put_contents("../upload/id".$name.".jpeg", $data);
					}
					if ($obj["passbook"]!="")
					{
						$name=$obj["seasoncode"].'_'.$obj["plotnumber"];
						$hexpic=$obj["passbook"];
						$data = pack("H" . strlen($hexpic), $hexpic);
						$result3 = file_put_contents("../upload/pb".$name.".jpeg", $data);
					}
					$plantationplotareadetail1->updateheaderarea();
				}
				if ($result==0)
				{	
					$insertareadetailstatuscode=0;
				}
				else
				{
					$insertareadetailstatuscode++;
				}
				if ($result1>=1 or $result2>=2 or $result3>=1)
				{
					$insertareadetailstatuscode++;
				}
				/* elseif ($result==1)
				{
					$result=$plantationplotareadetail1->deletemeasurementplot();
					if ($result==0)
					{
						$insertareadetailstatuscode=0;
						break;
					}  
				} */
				$this->uploadeddataupdate($obj["seasoncode"],$obj["plotnumber"]);
			}
			if ($insertareadetailstatuscode>=1)
			{

				oci_commit($this->connection);
			}
			else
			{
				oci_rollback($this->connection);
			}
		}

		if($insertareadetailstatuscode>=1)
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
	function uploadeddataupdate($seasoncode,$plotnumber)
    {
        
		$query = "update plantationheader h
		set h.ISSELFIEUPLOADED=0,h.ISIDUPLOADED=0,h.ISPBUPLOADED=0
		where h.seasoncode=".$seasoncode." and h.plotnumber=".$plotnumber;
        $result = oci_parse($this->connection, $query);
        if (oci_execute($result,OCI_NO_AUTO_COMMIT))
        {

        }

		$query_1 = "select plotnumber from plantationheader p 
		where p.seasoncode=".$seasoncode." and plotnumber=".$plotnumber." order by plotnumber";

		$result_1 = oci_parse($this->connection, $query_1);
        $r = oci_execute($result_1);
        while ($row_1 = oci_fetch_array($result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if (file_exists("../upload/".$seasoncode."_".$row_1['PLOTNUMBER'].'.jpeg'))
            {
                $dt = date("d/m/Y", filectime("../upload/".$seasoncode."_".$row_1['PLOTNUMBER'].'.jpeg'));
                $dt = DateTime::createFromFormat('d/m/Y',$dt)->format('d-M-Y');
                $query = "update plantationheader h
                set h.ISSELFIEUPLOADED=1,measurementdate='".$dt."'
                where h.seasoncode=".$seasoncode
                ." and h.plotnumber=".$row_1['PLOTNUMBER'];
                $result = oci_parse($this->connection, $query);
                if (oci_execute($result,OCI_NO_AUTO_COMMIT))
                {

                }
                else
                {
                    return 0;
                }
            }
            if (file_exists("../upload/id".$seasoncode."_".$row_1['PLOTNUMBER'].'.jpeg'))
            {
                $query = "update plantationheader h
                set h.ISIDUPLOADED=1
                where h.seasoncode=".$seasoncode
                ." and h.plotnumber=".$row_1['PLOTNUMBER'];
                $result = oci_parse($this->connection, $query);
                if (oci_execute($result,OCI_NO_AUTO_COMMIT))
                {

                }
                else
                {
                    return 0;
                }
            }
            if (file_exists("../upload/pb".$seasoncode."_".$row_1['PLOTNUMBER'].'.jpeg'))
            {
                $query = "update plantationheader h
                set h.ISPBUPLOADED=1
                where h.seasoncode=".$seasoncode
                ." and h.plotnumber=".$row_1['PLOTNUMBER'];
                $result = oci_parse($this->connection, $query);
                if (oci_execute($result,OCI_NO_AUTO_COMMIT))
                {

                }
                else
                {
                    return 0;
                }
            }
        }
        return 1;
    }
}
?>