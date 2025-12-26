<?php
    include_once("../api_oracle/contract_db_oracle.php");
	////include_once("../api_oracle/area_db_oracle.php");
	include_once("../api_oracle/contract_db_oracle.php");
	//include_once("../api_oracle/area_db_oracle.php");
	include_once("../api_oracle/contracttransportdetail_db_oracle.php");
	include_once("../api_oracle/contractharvestdetail_db_oracle.php");
	include_once("../api_oracle/contracttransporttrailerdetail_db_oracle.php");
	include_once("../api_oracle/contractguarantordetail_db_oracle.php");
	include_once("../api_oracle/servicecontractor_db_oracle.php");
	include_once("../api_oracle/contractdocumentdetail_db_oracle.php");
class contract_51
{	
	public $contractid;
    public $connection;
    
	public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

	function printpageheader(&$pdf,&$liney,$contractid)
    {
        $contract1 = new contract($this->connection);

		if ($contract1->fetch($contractid))
		{
            $servicecontractor1 = new servicecontractor($this->connection);
            $servicecontractor1->fetch($contract1->servicecontractorid);
            if ($contract1->contractcategoryid == 521478963)
            {
                $pdf->multicell(100,10,'जावक क्रमांक : ट्रस्ट/    /२०  -२०',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+7;
                $pdf->multicell(40,10,'प्रति, ',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+7;
                $pdf->multicell(40,10,'हार्वेस्टिंग विभाग',0,'L',false,1,30,$liney,true,0,false,true,10);
                $liney = $liney+7;
                $pdf->multicell(80,10,', ता.इंदापूर जि.पुणे',0,'L',false,1,30,$liney,true,0,false,true,10);
                $liney = $liney+7;
                $contracttransportdetail1 = new contracttransportdetail($this->connection);
                $list1 = $contract1->transportlist();
                $val = intval($list1[0]);
                $contracttransportdetail1->fetch($val);
                $contracttransporttrailerdetail1 = new contracttransporttrailerdetail($this->connection);
                $list2 = $contracttransportdetail1->trailerlist();
                $tlist='';
                $i=1;
                foreach ($list2 as $value)
                {
                    $val = intval($value);
                    $contracttransporttrailerdetail1->fetch($val);
                    if ($tlist=='')
                    {
                        $tlist= 'ट्रेलर -'.$i.'.'.$contracttransporttrailerdetail1->trailermfgname_unicode;
                        $tlist= $tlist.' नंबर -'.$contracttransporttrailerdetail1->trailernumber;
                    }
                    else
                    {
                        $tlist= $tlist.','.'ट्रेलर -'.$i.'.'.$contracttransporttrailerdetail1->trailermfgname_unicode;
                        $tlist= $tlist.' नंबर -'.$contracttransporttrailerdetail1->trailernumber;
                    }
                }
                $html = '<span style="text-align:justify;">
                श्री.'.$servicecontractor1->name_unicode.' 
                राहणार: '.$servicecontractor1->address_unicode.' 
                यांचा '.$contracttransportdetail1->transportationvehiclename_unicode.' 
                '.$contracttransportdetail1->vehiclemfgname_unicode.' 
                नंबर :'.$contracttransportdetail1->vehiclenumber.'
                </span>';
                // set UTF-8 Unicode font
                $pdf->SetFont('siddhanta', '', 11);
                // output the HTML content
                $pdf->writeHTML($html, true, 0, true, true);
                $liney = $liney+15;
                if ($tlist!='')
                {
                    $html = '<span style="text-align:justify;">
                तसेच '.$tlist.'</span>';
                // set UTF-8 Unicode font
                $pdf->SetFont('siddhanta', '', 11);
                // output the HTML content
                $pdf->writeHTML($html, true, 0, true, true);
                $liney = $liney+15;
                }
                $pdf->multicell(185,10,'ची पुस्तके पाहिली असून त्यांचा करार करुन घ्यावा.',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+20;
                $pdf->line(130,$liney-10,195,$liney-10);
                $pdf->multicell(50,10,'हार्वेस्टींग विभाग क्लार्क',0,'L',false,1,150,$liney,true,0,false,true,10);
                $liney = $liney+15;
            }
            $contractguarantordetail1 = new contractguarantordetail($this->connection);
            $pdf->multicell(40,10,'सन : '.$contract1->seasonname_unicode,0,'L',false,1,15,$liney,true,0,false,true,10);
            $pdf->multicell(40,10,'दिनांक : '.$contract1->applicationdatetime,0,'L',false,1,150,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->multicell(185,10,'ट्रॅक्टर ऊस तोडणी  वाहतूक करारासंबंधी ऊस क्षेत्राचे जामीनदार माहिती:
            ',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->SetFont('siddhanta', '', 12, '', true);
            $liney = $liney+7;
            $startliney = $liney;
            $liney = $liney+3;
            $pdf->multicell(20,10,'कोड',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(80,10,'नाव',0,'L',false,1,35,$liney,true,0,false,true,10);
            $pdf->multicell(60,20,'पत्ता',0,'L',false,1,115,$liney,true,0,false,true,10);
            $pdf->multicell(20,10,'क्षेत्र',0,'R',false,1,175,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->line(15,$liney,195,$liney);
            $liney = $liney+2;
            $pdf->multicell(40,10,'ट्रॅक्टर मालक :',0,'L',false,1,35,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(15,$liney,195,$liney);
            $liney = $liney+2;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $pdf->multicell(20,10,$servicecontractor1->servicecontractorid%10000,0,'L',false,1,15,$liney,true,0,false,true,10);
            $pdf->multicell(80,10,$servicecontractor1->name_unicode,0,'L',false,1,35,$liney,true,0,false,true,10);
            $pdf->multicell(60,20,$servicecontractor1->address_unicode,0,'L',false,1,115,$liney,true,0,false,true,10);
            $pdf->multicell(20,10,$contract1->fieldarea,0,'R',false,1,175,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->line(15,$liney,195,$liney);
            $liney = $liney+2;
            $pdf->multicell(80,10,'जामीनदार :',0,'L',false,1,35,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(15,$liney,195,$liney);
            $liney = $liney+2;
            $cnt = $contract1->gurantorcount(2);
			for ($i=1;$i<=$cnt;$i++)
			{
				$contractguarantordetail1 = $this->contractguarantordetail($this->connection,$contract1->contractid,2,$i);
				$pdf->SetFont('siddhanta', '', 11, '', true);
				$pdf->multicell(20,10,$contractguarantordetail1->servicecontractorid%10000,0,'L',false,1,15,$liney,true,0,false,true,10);
				$pdf->multicell(80,10,$contractguarantordetail1->servicecontractorname_unicode,0,'L',false,1,35,$liney,true,0,false,true,10);
                $pdf->multicell(60,20,$contractguarantordetail1->address_unicode,0,'L',false,1,115,$liney,true,0,false,true,10);
                $pdf->multicell(20,10,$contractguarantordetail1->fieldarea,0,'R',false,1,175,$liney,true,0,false,true,10);
                $liney = $liney+7;
                if ($i!=$cnt)
                {
                    $pdf->line(15,$liney,195,$liney);
                }
                $liney = $liney+2;
            }
            $endliney = $liney;
            $pdf->rect(15,$startliney,180,$endliney-$startliney);
            $pdf->line(35,$startliney,35,$endliney);
            $pdf->line(115,$startliney,115,$endliney);
            $pdf->line(180,$startliney,180,$endliney);
            $liney = $liney+7;
            $pdf->multicell(185,10,'वरील माहिती रुजवातीवरून दिली असून असून सदर क्षेत्र '.$contract1->seasonname_unicode.' हंगामात गळितास येणार आहे
            ',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+25;
            $pdf->line(130,$liney-10,195,$liney-10);
            $pdf->multicell(80,10,'चिटबाॅय / मुकादम / सुपरवायझर',0,'L',false,1,140,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->multicell(50,10,'शेतकी विभाग',0,'L',false,1,150,$liney,true,0,false,true,10);
        }
    }

    function contracttransportdetail(&$connection,$contractid)
    {
        $contracttransportdetail1 = new contracttransportdetail($connection);
        $query = "select d.contracttransportdetailid from contract c,contracttransportdetail d where c.active=1 and d.active=1 and c.contractid=d.contractid and c.contractid=".$contractid;
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $contracttransportdetail1->fetch($row['CONTRACTTRANSPORTDETAILID']);
            return $contracttransportdetail1;
        }
    }

    function contractharvestdetail(&$connection,$contractid)
    {
        $contractharvestdetail1 = new contractharvestdetail($connection);
        $query = "select d.contractharvestdetailid from contract c,contractharvestdetail d where c.active=1 and d.active=1 and c.contractid=d.contractid and c.contractid=".$contractid;
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $contractharvestdetail1->fetch($row['CONTRACTHARVESTDETAILID']);
            return $contractharvestdetail1;
        }
    }

    function contracttransporttrailerdetail(&$connection,$contracttransportdetailid,$sequencenumber)
    {
        $contracttransporttrailerdetail1 = new contracttransporttrailerdetail($connection);
        $query = "select t.contracttransporttrailerdetailid from contract c,contracttransportdetail d, contracttransporttrailerdetail t where c.active=1 and d.active=1 and t.active=1 and c.contractid=d.contractid and d.contracttransportdetailid=t.contracttransportdetailid and t.contracttransportdetailid=".$contracttransportdetailid;
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contracttransporttrailerdetail1->fetch($row['CONTRACTTRANSPORTTRAILERDETAILID']);
                return $contracttransporttrailerdetail1;
                exit;
            }
            else
            {
                $i++;	
            }
        }
    }

    function contractguarantordetail(&$connection,$contractid,$category,$sequencenumber)
    {
		$contractguarantordetail1 = new contractguarantordetail($connection);
		if ($category == 1)
		{
			$query = "select d.contractguarantordetailid from contract c,contractguarantordetail d,servicecontractor t where c.active=1 and d.active=1 and t.active=1 and c.contractid=d.contractid and d.servicecontractorid=t.servicecontractorid and d.iscultivator=0 and c.contractid=".$contractid." order by d.contractguarantordetailid";
		}
		elseif ($category == 2)
		{
			$query = "select d.contractguarantordetailid from contract c,contractguarantordetail d,cultivator t where c.active=1 and d.active=1 and c.contractid=d.contractid and d.servicecontractorid=t.cultivatorid and d.iscultivator=1 and c.contractid=".$contractid." order by d.contractguarantordetailid";
		}
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contractguarantordetail1->fetch($row['CONTRACTGUARANTORDETAILID'],$category);
                return $contractguarantordetail1;
            }
            else
            {
                $i++;
            }
        }
    }
}
?>