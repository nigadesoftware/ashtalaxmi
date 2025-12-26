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
class contract_2
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
			$liney = $liney+10;
			$pdf->SetFont('siddhanta', '', 11, '', true);
			$pdf->multicell(50,10,'जावक क्र शेतकी/      /',0,'L',false,1,15,$liney,true,0,false,true,20);
			$pdf->multicell(30,10,'दि.:',0,'L',false,1,160,$liney,true,0,false,true,20);
			$pdf->SetFont('siddhanta', '', 13, '', true);
			$title = '';
			$pdf->multicell(35,10,$title,0,'L',false,1,85,$liney,true,0,false,true,10);
			$liney = $liney+5;
			$pdf->multicell(200,30,'गाळप हंगाम '.$contract1->seasonname_unicode,0,'L',false,1,85,$liney,true,0,false,true,20);
			$liney = $liney+10;
			$pdf->multicell(200,30,'ऊस तोडणी / वाहतूक करारासाठी कागदपत्रांची खालीलप्रमाणे',0,'L',false,1,60,$liney,true,0,false,true,20);
			$liney = $liney+17;
			$pdf->SetFont('siddhanta', '', 12, '', true);
			$contracttransportdetail1 = new contracttransportdetail($this->connection);
			$contracttransportdetail1 = $this->contracttransportdetail($this->connection,$contract1->contractid);
			$servicecontractor1 = new servicecontractor($this->connection);
			$servicecontractor1->fetch($contract1->servicecontractorid);
			$contractharvestdetail1 = new contractharvestdetail($this->connection);
			$contractharvestdetail1 = $this->contractharvestdetail($this->connection,$contract1->contractid);
			$contractdocumentdetail1 = new contractdocumentdetail($this->connection);
			$pdf->SetFont('siddhanta', '', 13, '', true);
			$pdf->multicell(100,10,'करारासाठी दाखल केलेली कागदपत्रे',0,'L',false,1,25,$liney,true,0,false,true,10);
			$pdf->SetFont('siddhanta', '', 11, '', true);
			$list2 = $contract1->documentlist();
			$tlist='';
			$i=1;
			$j=22;
			$liney=$liney+7;
			foreach ($list2 as $value)
			{
				$val = intval($value);
				$contractdocumentdetail1->fetch($val);
				$pdf->multicell(100,10,$i.')'.$contractdocumentdetail1->documentname_unicode.' [√]',0,'L',false,1,50,$liney,true,0,false,true,10);
				$i++;
				$liney=$liney+7;
			}
			$liney=$liney+7;
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