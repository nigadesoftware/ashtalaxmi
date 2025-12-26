<?php
    include_once("../api_oracle/contract_db_oracle.php");
	//include_once("../api_oracle/area_db_oracle.php");
	include_once("../api_oracle/contracttransportdetail_db_oracle.php");
	include_once("../api_oracle/contractharvestdetail_db_oracle.php");
	include_once("../api_oracle/contracttransporttrailerdetail_db_oracle.php");
	include_once("../api_oracle/contractguarantordetail_db_oracle.php");
	include_once("../api_oracle/servicecontractor_db_oracle.php");
	include_once("../api_oracle/contractperformancedetail_db_oracle.php");
    include_once("../api_oracle/contractnomineedetail_db_oracle.php");
    include_once("../api_oracle/contractadvancedetail_db_oracle.php");
class contract_5
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
			$pdf->SetFont('siddhanta', '', 15, '', true);
            $pdf->multicell(150,10,'अॅडव्हान्स मागणी अर्ज',0,'L',false,1,85,$liney,true,0,false,true,10);
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $curdate = date('d/m/Y');
			$pdf->multicell(50,10,'दिनांक:'.$curdate,0,'L',false,1,160,$liney,true,0,false,true,10);
			$pdf->multicell(50,10,'प्रति,',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+7;
            
   			$contracttransportdetail1 = new contracttransportdetail($this->connection);
			$contracttransportdetail1 = $this->contracttransportdetail($this->connection,$contract1->contractid);
			$servicecontractor1 = new servicecontractor($this->connection);
			$servicecontractor1->fetch($contract1->servicecontractorid);

            $contractharvestdetail1 = new contractharvestdetail($this->connection);
			$contractharvestdetail1 = $this->contractharvestdetail($this->connection,$contract1->contractid);
            
            $pdf->multicell(70,10,'मा.कार्यकारी संचालक / अध्यक्ष,',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->multicell(150,10,'जय भवानी सर्व संघ (ट्रस्ट)',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->multicell(150,10,'श्री संत जनार्दन स्वामी नगर, ता.नाशिक, जि.नाशिक',0,'L',false,1,15,$liney,true,0,false,true,10);
            
            $liney = $liney+7;
        
            $pdf->multicell(50,10,'अर्जदार:',0,'L',false,1,15,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,'श्री '.$servicecontractor1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+2;
            $pdf->multicell(25,10,'वय: '.$contract1->age,0,'L',false,1,30,$liney,true,0,false,true,10);
            $pdf->multicell(10,10,'धंदा:',0,'L',false,1,100,$liney,true,0,false,true,10);
            if ($contract1->contractcategoryid == 521478963)
            {
                $pdf->multicell(30,10,'वहातूकदार',0,'L',false,1,120,$liney,true,0,false,true,10);
            }
            else if($contract1->contractcategoryid == 785415263)
            {
                $pdf->multicell(30,10,'बैलगाडी मुकादम',0,'L',false,1,120,$liney,true,0,false,true,10);
            }
            else if($contract1->contractcategoryid == 947845153)
            {
                $pdf->multicell(30,10,'तोडणीदार',0,'L',false,1,120,$liney,true,0,false,true,10);
            }
            $liney = $liney+5;
            $pdf->line(37,$liney,100,$liney);
            $pdf->line(110,$liney,200,$liney);
            $liney = $liney+2;
            $pdf->multicell(15,10,'मु.पो.:',0,'L',false,1,30,$liney,true,0,false,true,10);
            $pdf->multicell(120,10,$servicecontractor1->address_unicode,0,'L',false,1,45,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(43,$liney,200,$liney);
            $liney = $liney+2;
            $liney = $liney+5;
            $pdf->SetFont('siddhanta', '', 13, '', true);
            $pdf->multicell(50,10,'विषय:',0,'L',false,1,15,$liney,true,0,false,true,10);
            $pdf->multicell(150,10,'ऊस तोडणी वाहतूक करारापोटी अॅडव्हान्स मिळणेबाबत',0,'L',false,1,30,$liney,true,0,false,true,10);
            
            $liney = $liney+7;
            $contractadvancedetail1 = new contractadvancedetail($this->connection);
            $contractadvancedetail1 = $this->contractadvancedetail($this->connection,$contractid,1);
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $wrdamt = NumberToWords(number_format_indian($contractadvancedetail1->advancedemandamount,0,false,false),1);
            $html = '<span style="text-align:justify;">महाशय,<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;मी 
            अापणाकडे श्री छत्रपति सहकारी साखर कारखाना लि., , 
            ता.इंदापूर, जि.पुणे यांचे गळीत हंगाम <u>'.$contract1->seasonname_unicode.'</u> 
            करिता ऊस तोडणी वाहतुकीसाठी <u>'.$contract1->contractcategoryname_unicode.'</u>चा 
            करार करून दिलेला आहे. त्यासाठी मला '.$contract1->harvestlabourcategoryname_unicode.' 
            अॅडव्हान्स(उचल) देणेकरिता आपले नियमाप्रमाणे <u>Rs'.$contractadvancedetail1->advancedemandamount.' 
            (अक्षरी '.$wrdamt.')</u> हप्त्याप्रमाणे अॅडव्हान्स मिळावा ही विनंती. सदर अॅडव्हान्सची रक्कम माझे होणाऱ्या
            तोडणी वाहतुकीच्या बिलातून, डिपॉझिटमधून किंवा देय असणाऱ्या रकमेतून वसूल करून घ्यावी. 
            </span>';
            $pdf->writeHTML($html, true, 0, true, true);
            $liney = $liney+50;

            $pdf->multicell(30,10,'आपला विश्वासू',0,'L',false,1,125,$liney,true,0,false,true,10);
            $liney = $liney+14;
            $pdf->multicell(100,10,'('.$servicecontractor1->name_unicode.')',0,'L',false,1,125,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->line(15,$liney,200,$liney);
            $liney = $liney+14;

                $pdf->multicell(70,10,'मा.कार्यकारी संचालक / अध्यक्ष,',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+7;
                $pdf->multicell(150,10,'जय भवानी सर्व संघ (ट्रस्ट)',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+7;
                $pdf->multicell(150,10,'श्री संत जनार्दन स्वामी नगर, ता.नाशिक, जि.नाशिक',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+7;

                $pdf->SetFont('siddhanta', '', 11);
                
                $pdf->multicell(15,10,'श्री.',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(120,10,$servicecontractor1->name_unicode,0,'L',false,1,25,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(25,$liney,200,$liney);
                $liney = $liney+2;
                $pdf->multicell(15,10,'मु.पो.:',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(120,10,$servicecontractor1->address_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(30,$liney,200,$liney);
                $liney = $liney+2;
                // set UTF-8 Unicode font
                $pdf->SetFont('siddhanta', '', 11);
                $html = '<span style="text-align:justify;">यांनी आपले कारखान्याकडे 
                सन '.$contract1->seasonname_unicode.' करिता ऊस तोडणी / वाहतुकीसाठी 
                '.$contract1->contractcategoryname_unicode.' करार केलेला असून 
                त्यांना या करारापोटी अॅडव्हान्स रक्कम <u>Rs'.$contractadvancedetail1->approvedamount.'</u> देणेत यावा.</span>';
                // output the HTML content
                $pdf->writeHTML($html, true, 0, true, true);
                
                $liney = $liney+30;
                $pdf->multicell(50,10,'स्थळ:',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(100,10,'शेतकी अधिकारी',0,'L',false,1,125,$liney,true,0,false,true,10);
                $liney = $liney+7;
                $pdf->multicell(100,10,'दि.'.$contractadvancedetail1->approveddatetime,0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(100,10,'श्री छत्रपति सह. सा. का. लि.',0,'L',false,1,115,$liney,true,0,false,true,10);
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

    function contractnomineedetail(&$connection,$contractid,$sequencenumber)
    {
        $contractnomineedetail1 = new contractnomineedetail($connection);
        $query = "select d.contractnomineedetailid from contract c,contractnomineedetail d,servicecontractor t where c.active=1 and d.active=1 and t.active=1 and c.contractid=d.contractid and d.nomineeid=t.servicecontractorid and c.contractid=".$contractid." order by d.contractnomineedetailid";
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contractnomineedetail1->fetch($row['CONTRACTNOMINEEDETAILID']);
                return $contractnomineedetail1;
            }
            else
            {
                $i++;
            }
        }
    }

    function contractadvancedetail(&$connection,$contractid,$sequencenumber)
    {
        $contractadvancedetail1 = new contractadvancedetail($connection);
        $query = "select d.contractadvancedetailid from contract c,contractadvancedetail d where c.active=1 and d.active=1 and c.contractid=d.contractid and c.contractid=".$contractid." order by d.contractadvancedetailid";
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contractadvancedetail1->fetch($row['CONTRACTADVANCEDETAILID']);
                return $contractadvancedetail1;
            }
            else
            {
                $i++;
            }
        }
    }

}
?>