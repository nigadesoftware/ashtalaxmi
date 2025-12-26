<?php
    include_once("../api_oracle/contract_db_oracle.php");
	include_once("../api_oracle/servicecontractor_db_oracle.php");
	include_once("../api_oracle/cultivator_db_oracle.php");
	include_once("../api_oracle/contracttransportdetail_db_oracle.php");
	include_once("../api_oracle/contractharvestdetail_db_oracle.php");
	include_once("../api_oracle/contracttransporttrailerdetail_db_oracle.php");
	include_once("../api_oracle/contractguarantordetail_db_oracle.php");
class contract_1
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
			$pdf->SetFont('siddhanta', '', 15, '', true);
			$title = '';
			if ($contract1->contractcategoryid == 947845153)
			{
				$pdf->multicell(200,10,'ऊस तोडणी व वाहतूक कॉन्ट्रॅक्टर्स यांच्या दरम्यानचा करारनामा कागदपत्र',0,'L',false,1,30,$liney,true,0,false,true,10);
			}
			else
			{
				$pdf->multicell(35,10,$title,0,'L',false,1,85,$liney,true,0,false,true,10);
			}
			$liney = $liney+15;
			$pdf->SetFont('siddhanta', '', 12, '', true);
			$pdf->multicell(70,10,'गाळप हंगाम '.$contract1->seasonname_unicode,0,'L',false,1,85,$liney,true,0,false,true,10);
			$liney = $liney+7;
			$pdf->SetFont('siddhanta', '', 12, '', true);

			$pdf->multicell(35,10,'कोड नंबर :',0,'L',false,1,15,$liney,true,0,false,true,10);
			
			$pdf->multicell(50,10,$contract1->servicecontractorid%10000,0,'L',false,1,40,$liney,true,0,false,true,10);
			$pdf->SetFont('siddhanta', '', 12, '', true);		
			
			$pdf->multicell(40,10,'करार नंबर :',0,'L',false,1,130,$liney,true,0,false,true,10);
			$pdf->multicell(70,10,$contract1->contractnumber_prefixsuffix,0,'L',false,1,155,$liney,true,0,false,true,10);
			$liney = $liney+7;

			$pdf->multicell(100,10,$contract1->contractcategoryname_unicode.' कंत्राटदाराचे नाव :',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$servicecontractor1->name_unicode,0,'L',false,1,100,$liney,true,0,false,true,10);
			$pdf->SetFont('siddhanta', '', 11, '', true);
			$liney = $liney+5;
			$pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
			$pdf->line(70,$liney,200,$liney);

			$liney = $liney+2;
			/* $area1 = new area($this->connection);
			$area1->fetch($contract1->areaid);
			$pdf->multicell(20,10,'मु.पो.:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(40,10,$area1->areaname_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);

			$pdf->multicell(10,10,'ता.:',0,'L',false,1,85,$liney,true,0,false,true,10);
			$pdf->multicell(40,10,$area1->subdistrictname_unicode,0,'L',false,1,95,$liney,true,0,false,true,10);

			$pdf->multicell(10,10,'जि.:',0,'L',false,1,140,$liney,true,0,false,true,10);
			$pdf->multicell(40,10,$area1->districtname_unicode,0,'L',false,1,150,$liney,true,0,false,true,10);
 */
			$pdf->multicell(20,10,'मु.पो.:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(120,10,$servicecontractor1->address_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);

			$pdf->SetFont('siddhanta', '', 11, '', true);
			$liney = $liney+5;
			$pdf->line(30,$liney,150,$liney);
/* 			$pdf->line(95,$liney,135,$liney);
			$pdf->line(150,$liney,200,$liney); */
			$liney = $liney+2;

			$pdf->multicell(50,10,'मोबाईल नंबर / फोन नंबर:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(40,10,$servicecontractor1->contactnumber,0,'L',false,1,65,$liney,true,0,false,true,10);
			
			$contracttransportdetail1 = new contracttransportdetail($this->connection);
			$contracttransportdetail1 = $this->contracttransportdetail($this->connection,$contract1->contractid);


			if ($contract1->contractcategoryid == 521478963 )
			{
				$pdf->multicell(60,10,$contract1->contractcategoryname_unicode.' नंबर:',0,'L',false,1,115,$liney,true,0,false,true,10);
			}
			elseif ($contract1->contractcategoryid == 947845153)
			{
				$pdf->multicell(60,10,$contracttransportdetail1->transportationvehiclename_unicode.' नंबर:',0,'L',false,1,115,$liney,true,0,false,true,10);
			}
			elseif ($contract1->contractcategoryid == 785415263)
			{
				$pdf->multicell(60,10,$contract1->contractcategoryname_unicode.' संख्या:',0,'L',false,1,115,$liney,true,0,false,true,10);
			}
			
			$contractharvestdetail1 = new contractharvestdetail($this->connection);
			$contractharvestdetail1 = $this->contractharvestdetail($this->connection,$contract1->contractid);

			$pdf->SetFont('siddhanta', '', 11, '', true);
			if ($contract1->contractcategoryid == 521478963 or $contract1->contractcategoryid == 947845153)
			{
				$pdf->SetFont('helvetica', '', 11, '', true);
				$pdf->multicell(40,10,$contracttransportdetail1->vehiclenumber,0,'L',false,1,162,$liney,true,0,false,true,10);
			}
			elseif ($contract1->contractcategoryid == 785415263)
			{
				$pdf->multicell(40,10,$contractharvestdetail1->noofvehicles,0,'L',false,1,162,$liney,true,0,false,true,10);
			}
			
			$liney = $liney+5;
			$pdf->line(60,$liney,115,$liney);
			$pdf->line(142,$liney,200,$liney);
			$liney = $liney+2;

			$pdf->SetFont('siddhanta', '', 11, '', true);
			$pdf->multicell(30,10,'पॅनकार्ड नंबर:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->SetFont('helvetica', '', 11, '', true);
			$pdf->multicell(30,10,$contract1->pannumber,0,'L',false,1,40,$liney,true,0,false,true,10);

			$contracttransporttrailerdetail1 = new contracttransporttrailerdetail($this->connection);
			$contracttransporttrailerdetail1 = $this->contracttransporttrailerdetail($this->connection,$contracttransportdetail1->contracttransportdetailid,1);
			$contracttransporttrailerdetail2 = new contracttransporttrailerdetail($this->connection);
			$contracttransporttrailerdetail2 = $this->contracttransporttrailerdetail($this->connection,$contracttransportdetail1->contracttransportdetailid,2);

			$pdf->SetFont('siddhanta', '', 11, '', true);
			if ($contract1->contractcategoryid == 521478963 and $contracttransportdetail1->transportationvehicleid == 248768236)
			{
				$pdf->multicell(30,10,'ट्रेलर नंबर: 1.',0,'L',false,1,75,$liney,true,0,false,true,10);
				$pdf->SetFont('helvetica', '', 11, '', true);
				$pdf->multicell(40,10,$contracttransporttrailerdetail1->trailernumber,0,'L',false,1,105,$liney,true,0,false,true,10);
				$pdf->SetFont('siddhanta', '', 11, '', true);
				$pdf->multicell(10,10,'2.',0,'L',false,1,145,$liney,true,0,false,true,10);
				$pdf->SetFont('helvetica', '', 11, '', true);
				$pdf->multicell(40,10,$contracttransporttrailerdetail2->trailernumber,0,'L',false,1,155,$liney,true,0,false,true,10);
				
				$liney = $liney+5;
				$pdf->line(37,$liney,70,$liney);
				if ($contracttransportdetail1->transportationvehicleid == 248768236)
				{
					$pdf->line(100,$liney,145,$liney);
					$pdf->line(150,$liney,200,$liney);
				}
				$liney = $liney+2;
			}
			else
			{
				$liney = $liney+7;
			}
			

			$pdf->SetFont('siddhanta', '', 11, '', true);
			$pdf->multicell(30,10,'आधारकार्ड नंबर:',0,'L',false,1,15,$liney,true,0,false,true,10);
			//$pdf->SetFont('helvetica', '', 11, '', true);
			$pdf->multicell(40,10,$contract1->aadharnumber,0,'L',false,1,50,$liney,true,0,false,true,10);
			$liney = $liney+5;
			$pdf->line(42,$liney,80,$liney);
			$liney = $liney+2;

			$contractharvestdetail1 = new contractharvestdetail($this->connection);
			$contractharvestdetail1 = $this->contractharvestdetail($this->connection,$contract1->contractid);

			$pdf->SetFont('siddhanta', '', 11, '', true);
			$pdf->multicell(40,10,'कोयत्यांची संख्या:',0,'L',false,1,15,$liney,true,0,false,true,10);
			//$pdf->SetFont('helvetica', '', 11, '', true);
			$pdf->multicell(30,10,$contractharvestdetail1->noofharvesterlabour,0,'L',false,1,55,$liney,true,0,false,true,10);
			$pdf->multicell(30,10,'टोळी प्रकार:',0,'L',false,1,80,$liney,true,0,false,true,10);
			$pdf->multicell(50,10,$contractharvestdetail1->transportationuptovehiclename_unicode,0,'L',false,1,110,$liney,true,0,false,true,10);
			$liney = $liney+5;
			$pdf->line(42,$liney,80,$liney);
			$pdf->line(105,$liney,200,$liney);

			$contractguarantordetail1 = new contractguarantordetail($this->connection);
			//$contractguarantordetail1 = $this->contractguarantordetail($this->connection,$contract1->contractid,1);

			$pdf->SetFont('siddhanta', '', 14, '', true);
			$liney = $liney+3;
			$pdf->multicell(40,10,'जामीनदार',0,'L',false,1,95,$liney,true,0,false,true,10);
			$liney = $liney+10;
			$cnt = $contract1->gurantorcount(1);
			for ($i=1;$i<=$cnt;$i++)
			{
				$contractguarantordetail1 = $this->contractguarantordetail($this->connection,$contract1->contractid,1,$i);
				$pdf->SetFont('siddhanta', '', 11, '', true);
				$pdf->multicell(30,10,'कोड नंबर:',0,'L',false,1,15,$liney,true,0,false,true,10);
				$pdf->multicell(30,10,$contractguarantordetail1->servicecontractorid%10000,0,'L',false,1,40,$liney,true,0,false,true,10);
				$pdf->multicell(20,10,'नाव:',0,'L',false,1,85,$liney,true,0,false,true,10);
				$pdf->multicell(50,10,$contractguarantordetail1->servicecontractorname_unicode,0,'L',false,1,95,$liney,true,0,false,true,10);
				$liney = $liney+5;
				$pdf->line(32,$liney,60,$liney);
				$pdf->line(95,$liney,200,$liney);
				$liney = $liney+2;

				$guarantorservicecontractor1 = new servicecontractor($this->connection);
				$guarantorservicecontractor1->fetch($contractguarantordetail1->servicecontractorid);
				$pdf->multicell(20,10,'मु.पो.:',0,'L',false,1,15,$liney,true,0,false,true,10);
				$pdf->multicell(120,10,$guarantorservicecontractor1->address_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);

				$liney = $liney+5;
				$pdf->line(30,$liney,150,$liney);

				$liney = $liney+2;
				$pdf->multicell(20,10,'मोबाईल:',0,'L',false,1,15,$liney,true,0,false,true,10);
				$pdf->multicell(40,10,$contractguarantordetail1->contactnumber,0,'L',false,1,30,$liney,true,0,false,true,10);
				$pdf->SetFont('siddhanta', '', 11, '', true);
				$pdf->multicell(30,10,'पॅनकार्ड नंबर:',0,'L',false,1,85,$liney,true,0,false,true,10);
				$pdf->SetFont('helvetica', '', 11, '', true);
				$pdf->multicell(40,10,$contractguarantordetail1->pannumber,0,'L',false,1,107,$liney,true,0,false,true,10);
				$pdf->SetFont('siddhanta', '', 11, '', true);
				$pdf->multicell(30,10,'आधारकार्ड नंबर:',0,'L',false,1,140,$liney,true,0,false,true,10);
				$pdf->multicell(40,10,$contractguarantordetail1->aadharnumber,0,'L',false,1,167,$liney,true,0,false,true,10);
				$liney = $liney+5;
				$pdf->line(30,$liney,80,$liney);
				$pdf->line(107,$liney,135,$liney);
				$pdf->line(167,$liney,200,$liney);
				$liney = $liney+2;
				$liney = $liney+10;
			}
			$pdf->SetFont('siddhanta', '', 14, '', true);
			$liney = $liney+3;
			$pdf->multicell(40,10,'क्षेत्र जामीनदार',0,'L',false,1,95,$liney,true,0,false,true,10);
			$liney = $liney+10;
			$cnt = $contract1->gurantorcount(2);
			for ($i=1;$i<=$cnt;$i++)
			{
				$contractguarantordetail1 = $this->contractguarantordetail($this->connection,$contract1->contractid,2,$i);
				$pdf->SetFont('siddhanta', '', 11, '', true);
				$pdf->multicell(30,10,'कोड नंबर:',0,'L',false,1,15,$liney,true,0,false,true,10);
				$pdf->multicell(30,10,$contractguarantordetail1->servicecontractorid%10000,0,'L',false,1,40,$liney,true,0,false,true,10);
				$pdf->multicell(20,10,'नाव:',0,'L',false,1,85,$liney,true,0,false,true,10);
				$pdf->multicell(50,10,$contractguarantordetail1->servicecontractorname_unicode,0,'L',false,1,95,$liney,true,0,false,true,10);
				$liney = $liney+5;
				$pdf->line(32,$liney,60,$liney);
				$pdf->line(95,$liney,200,$liney);
				$liney = $liney+2;

				$guarantorcultivator1 = new cultivator($this->connection);
				$guarantorcultivator1->fetch($contractguarantordetail1->servicecontractorid);
				$pdf->multicell(20,10,'मु.पो.:',0,'L',false,1,15,$liney,true,0,false,true,10);
				$pdf->multicell(120,10,$guarantorcultivator1->address_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);

				$liney = $liney+5;
				$pdf->line(30,$liney,150,$liney);

				$liney = $liney+2;
				$pdf->multicell(20,10,'मोबाईल:',0,'L',false,1,15,$liney,true,0,false,true,10);
				$pdf->multicell(40,10,$contractguarantordetail1->contactnumber,0,'L',false,1,30,$liney,true,0,false,true,10);
				$pdf->SetFont('siddhanta', '', 11, '', true);
				$pdf->multicell(30,10,'पॅनकार्ड नंबर:',0,'L',false,1,85,$liney,true,0,false,true,10);
				$pdf->SetFont('helvetica', '', 11, '', true);
				$pdf->multicell(40,10,$contractguarantordetail1->pannumber,0,'L',false,1,107,$liney,true,0,false,true,10);
				$pdf->SetFont('siddhanta', '', 11, '', true);
				$pdf->multicell(30,10,'आधारकार्ड नंबर:',0,'L',false,1,140,$liney,true,0,false,true,10);
				$pdf->multicell(40,10,$contractguarantordetail1->aadharnumber,0,'L',false,1,167,$liney,true,0,false,true,10);
				$liney = $liney+5;
				$pdf->line(30,$liney,80,$liney);
				$pdf->line(107,$liney,135,$liney);
				$pdf->line(167,$liney,200,$liney);
				$liney = $liney+2;
				$liney = $liney+10;
			}
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
        $query = "select t.contracttransporttrailerdetid from contract c,contracttransportdetail d, contracttransporttrailerdetail t where c.active=1 and d.active=1 and t.active=1 and c.contractid=d.contractid and d.contracttransportdetailid=t.contracttransportdetailid and t.contracttransportdetailid=".$contracttransportdetailid;
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contracttransporttrailerdetail1->fetch($row['CONTRACTTRANSPORTTRAILERDETID']);
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