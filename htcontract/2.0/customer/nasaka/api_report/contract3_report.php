<?php
    include_once("../api_oracle/contract_db_oracle.php");
	//include_once("../api_oracle/area_db_oracle.php");
	include_once("../api_oracle/contract_db_oracle.php");
	//include_once("../api_oracle/area_db_oracle.php");
	include_once("../api_oracle/contract_db_oracle.php");
	//include_once("../api_oracle/area_db_oracle.php");
	include_once("../api_oracle/contracttransportdetail_db_oracle.php");
	include_once("../api_oracle/contractharvestdetail_db_oracle.php");
	include_once("../api_oracle/contracttransporttrailerdetail_db_oracle.php");
	include_once("../api_oracle/contractguarantordetail_db_oracle.php");
	include_once("../api_oracle/servicecontractor_db_oracle.php");
	include_once("../api_oracle/contractperformancedetail_db_oracle.php");
	include_once("../api_oracle/contractitemloandetail_db_oracle.php");
	include_once("../api_oracle/contractapproverdetail_db_oracle.php");
	
class contract_3
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
			$pdf->SetFont('siddhanta', '', 13, '', true);
			$pdf->multicell(70,10,'करार मागणी अर्ज',0,'L',false,1,85,$liney,true,0,false,true,10);
			$pdf->SetFont('siddhanta', '', 11, '', true);
			$liney = $liney+15;
			$curdate = date('d/m/Y');
			$pdf->multicell(50,10,'दिनांक:'.$curdate,0,'L',false,1,160,$liney,true,0,false,true,10);
            $pdf->multicell(50,10,'प्रति,',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+5;
            $pdf->multicell(70,10,'मा.कार्यकारी संचालक साहेब,',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+5;
            $pdf->multicell(150,10,'नाशिक सहकारी साखर कारखाना लि., पळसे',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+5;
            $pdf->multicell(150,10,'श्री संत जनार्दन स्वामी नगर, ता.नाशिक, जि.नाशिक',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+7;

			$contracttransportdetail1 = new contracttransportdetail($this->connection);
			$contracttransportdetail1 = $this->contracttransportdetail($this->connection,$contract1->contractid);
			$servicecontractor1 = new servicecontractor($this->connection);
			$servicecontractor1->fetch($contract1->servicecontractorid);
			
			$contractharvestdetail1 = new contractharvestdetail($this->connection);
			$contractharvestdetail1 = $this->contractharvestdetail($this->connection,$contract1->contractid);
			if ($contract1->contractcategoryid == 521478963)
			{
				$subject = 'विषय - गळीत हंगाम '.$contract1->seasonname_unicode.' हंगामा करिता ट्रक / ट्रॅक्टर करारनामा मिळणेबाबत.';
				$pdf->multicell(150,10,$subject,0,'L',false,1,30,$liney,true,0,false,true,10);
				$liney = $liney+7;
				$pdf->line(43,$liney,170,$liney);
				$liney = $liney+2;
				$pdf->multicell(100,10,'अर्जदार: '.$servicecontractor1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
				$pdf->multicell(70,10,'रा.:'.$contract1->address,0,'L',false,1,130,$liney,true,0,false,true,10);
				$liney = $liney+5;
				$pdf->line(45,$liney,103,$liney);
				$pdf->line(135,$liney,200,$liney);
				$liney = $liney+2;

				$pdf->multicell(120,10,'वाहन नंबर : '.$contracttransportdetail1->vehiclenumber,0,'L',false,1,30,$liney,true,0,false,true,10);
				$liney = $liney+5;
				$pdf->line(30,$liney,120,$liney);
				$liney = $liney+7;
			}
			else
			{
				$subject = 'विषय - गळीत हंगाम '.$contract1->seasonname_unicode.' हंगामा करिता लेबर पुरवण्याचा करार मिळणेबाबत.';
				$pdf->multicell(150,10,$subject,0,'L',false,1,30,$liney,true,0,false,true,10);
				$liney = $liney+7;
				$pdf->line(43,$liney,170,$liney);
				$liney = $liney+7;
			}
			
			
			if ($contract1->contractcategoryid == 521478963)
			{
				$pdf->SetFont('siddhanta', '', 11, '', true);
				// create some HTML content
				$html = '<span style="text-align:justify;">महोदय,<br>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;मी ट्रक / ट्रॅक्टर मालक विनंतीपूर्वक अर्ज करतो कि, मला ट्रक / ट्रॅक्टर 
				नंबर <u>'.$contracttransportdetail1->vehiclenumber.'</u> चा 
				सन <u>'.$contract1->seasonname_unicode.'</u> गळीत 
				हंगामामध्ये ऊस वाहतूक करारनामा हंगाम संपेपर्यंत 
				करावयाचा आहे. मी मागील गळीत हंगामात (सन _______) माझा ट्रक / ट्रॅक्टर 
				वाहतुकीवर नियमित हजर होती. तसेच माझी ट्रक / ट्रॅक्टर येत्या गळीत हंगामात 
				नियमित ऊस वाहतुकीवर हजर राहील. तरी सन <u>'.$contract1->seasonname_unicode.'</u> गळीत हंगामासाठी माझ्या 
				ट्रक / ट्रॅक्टरचा ऊस वाहतूक करारनामा  कारखाना नियमाप्रमाणे करणेस मंजुरी 
				मिळावी ही विनंती.
				<br><br>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;कळावे, 
				</span>';
				// set UTF-8 Unicode font
				$pdf->SetFont('siddhanta', '', 11);
				// output the HTML content
				$pdf->writeHTML($html, true, 0, true, true);
				$liney = $liney+50;
			}
			else
			{
				$contractharvestdetail1 = new contractharvestdetail($this->connection);
				$contractharvestdetail1 = $this->contractharvestdetail($this->connection,$contract1->contractid);
				// create some HTML content
				$html = '<span style="text-align:justify;">महोदय,<br>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;मी खालील सही करणार <u>'.$servicecontractor1->name_unicode.'</u> रा.<u>'.$contract1->address.'</u>
				कंत्राटदार विभाग <u>'.$contract1->contractorzonename_unicode.'</u> विनंतीपूर्वक अर्ज करतो की, गळीत हंगाम <u>'.$contract1->seasonname_unicode.'</u> चे
				करिता <u>'.$contract1->harvesteuptolist().'</u> करिता आपले कारखान्याकडे ऊस तोडीचे व वाहतुकीचे काम करण्यास तयार आहे.
				<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;आपल्या कारखान्याच्या तोडणी / वाहतूक करिता असणाऱ्या ज्या अटी व नियम 
				असतील ते मला मान्य राहतील.मी आपल्या कारखान्याचा हंगाम संपेपर्यंत
				नियमित काम करीन व त्यानंतर आपण सांगाल त्या कारखान्यास उसतोड/वाहतूक करण्यासाठी जाईन.
				तरी मला आपल्या कारखान्यामार्फत सदर कामासाठी पात्र करार मिळावा
				ही विनंती.<br><br>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;कळावे, <br>
				</span>';
				// set UTF-8 Unicode font
				// set UTF-8 Unicode font
				$pdf->SetFont('siddhanta', '', 11);
				// output the HTML content
				$pdf->writeHTML($html, true, 0, true, true);
				$liney = $liney+30;
			}
			$liney = $liney+10;
			$pdf->multicell(100,10,'आपला विश्वासू,',0,'L',false,1,125,$liney,true,0,false,true,10);
			$liney = $liney+15;
			$pdf->multicell(100,10,'सही / अंगठा',0,'L',false,1,100,$liney,true,0,false,true,10);
			$liney = $liney+5;
			$pdf->line(100,$liney,200,$liney);
			$liney = $liney+2;
			if ($contract1->contractcategoryid == 521478963)
			{
				$pdf->multicell(100,10,'नाव: '.$servicecontractor1->name_unicode,0,'L',false,1,100,$liney,true,0,false,true,10);
				$liney = $liney+5;
			}
			else
			{
				$pdf->multicell(100,10,'नाव: '.$servicecontractor1->name_unicode,0,'L',false,1,100,$liney,true,0,false,true,10);
				$liney = $liney+5;
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

    function contractperformancedetail(&$connection,$contractid)
    {
        $contractperformancedetail1 = new contractperformancedetail($connection);
        $query = "select d.contractperformancedetailid from contract c,contractperformancedetail d where c.active=1 and d.active=1 and c.contractid=d.contractid and c.contractid=".$contractid;
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $contractperformancedetail1->fetch($row['CONTRACTPERFORMANCEDETAILID']);
            return $contractperformancedetail1;
        }
    }

    function contractguarantordetail(&$connection,$contractid,$sequencenumber)
    {
        $contractguarantordetail1 = new contractguarantordetail($connection);
        $query = "select d.contractguarantordetailid from contract c,contractguarantordetail d,servicecontractor t where c.active=1 and d.active=1 and t.active=1 and c.contractid=d.contractid and d.servicecontractorid=t.servicecontractorid and c.contractid=".$contractid." order by t.servicecontractorcategoryid desc,d.contractguarantordetailid";
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contractguarantordetail1->fetch($row['CONTRACTGUARANTORDETAILID']);
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