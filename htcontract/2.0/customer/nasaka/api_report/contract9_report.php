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
    include_once("../api_oracle/contractreceiptdetail_db_oracle.php");
class contract_9
{	
	public $contractid;
    public $connection;
    
	public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

    function printpageheader(&$pdf,&$liney,$contractid,$contractadvancedetailid)
    {
    	require("../info/phpsqlajax_dbinfo.php");
    	// Opens a this->connection to a MySQL server
        $this->connection=mysqli_connect($hostname_rawmaterial, $username_rawmaterial, $password_rawmaterial, $database_rawmaterial);
        // Check this->connection
        if (mysqli_connect_errno())
        {
            echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication error1</span>';
            exit;
        }
        mysqli_query($this->connection,'SET NAMES UTF8');
        $contract1 = new contract($this->connection);

		if ($contract1->fetch($contractid))
		{
			$pdf->SetFont('siddhanta', '', 15, '', true);
            $contracttransportdetail1 = new contracttransportdetail($this->connection);
			$contracttransportdetail1 = $this->contracttransportdetail($this->connection,$contract1->contractid);
			$servicecontractor1 = new servicecontractor($this->connection);
			$servicecontractor1->fetch($contract1->servicecontractorid);
            $contractreceiptdetail1 = new contractreceiptdetail($this->connection);
			$contractreceiptdetail1 = $this->contractreceiptdetail($this->connection,$contract1->contractid,1);

            $pdf->multicell(150,10,'वचनचिठ्ठी',0,'L',false,1,85,$liney,true,0,false,true,10);
            $liney = $liney+30;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $html = '<span style="text-align:justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            आम्ही श्री सोमेश्वर सह. सा.का.लि.,, ता.बारामती, जि.पुणे 
            यांचेकडील सन <u>'.$contract1->seasonname_unicode.'</u> अखेर गाळप हंगामासाठी 
            आमचे / माझे मालकीच्या <u>'.$contract->contractcategoryname_unicode.'</u>
            नंबर <u>'.$contracttransportdetail1->vehiclenumber.'</u> चा वाहतुकीचा करार केलेला असून 
            तो आमचेवर पुर्णपणे बंधनकारक असून कारखान्याने आम्हांस सदर करारापोटी रक्कम
            <u> Rs'.$contractreceiptdetail1->chequeamount.'</u> इतकी रक्कम उचल म्हणून 
            दिलेली आहे. ती रक्कम प्राप्त झालेली आहे. त्याची परतफेड करण्याची कायदेशीर 
            जबाबदारी आमची आहे. तरी रक्कम परतफेड करण्याचे वचन आम्ही या स्वतंत्र 
            वचनचिठ्ठीने कारखान्यास देत आहोत व त्यास आम्ही वैयक्तिक व सामुदायिकरित्या जबाबदार राहू.
            अॅडव्हान्स रक्कम कामामधून परतफेड न झाल्यास अथवा काम सोडून 
            गेल्यास किंवा कामावर न आल्यास उचल रकमेची आम्ही व्याज व खर्चासह परतफेड 
            करणेचे वचन देत आहोत. त्याचप्रमाणे कारखान्याचे होणारे नुकसानीस कायदेशीर 
            कार्यवाहीस आम्ही पात्र राहू. याची आम्हाला जाणीव आहे. ही वचनचिठ्ठी खालील साक्षीदारांच्या समक्ष 
            करून दिली असे. ही वचनचिठ्ठी कराराचा भाग समजण्यात यावा.</span>';
            $pdf->writeHTML($html, true, 0, true, true);
            $liney = $liney+35;
            
            $pdf->multicell(40,10,'स्थळ: ',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $curdate = date('d/m/Y');
			$pdf->multicell(50,10,'दिनांक:'.$curdate,0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+7;

            $contractguarantordetail1 = new contractguarantordetail($this->connection);
			$contractguarantordetail1 = $this->contractguarantordetail($this->connection,$contract1->contractid,1);
			$servicecontractor_guarantor1 = new servicecontractor($this->connection);
			$servicecontractor_guarantor1->fetch($contractguarantordetail1->servicecontractorid);

            $area1 = new area($this->connection);
            $area1->fetch($servicecontractor_guarantor1->areaid);

            $contractguarantordetail2 = new contractguarantordetail($this->connection);
			$contractguarantordetail2 = $this->contractguarantordetail($this->connection,$contract1->contractid,2);
			$servicecontractor_guarantor2 = new servicecontractor($this->connection);
			$servicecontractor_guarantor2->fetch($contractguarantordetail2->servicecontractorid);

            $area2 = new area($this->connection);
            $area2->fetch($servicecontractor_guarantor2->areaid);

			$contractguarantordetail3 = new contractguarantordetail($this->connection);
			$contractguarantordetail3 = $this->contractguarantordetail($this->connection,$contract1->contractid,3);
			$servicecontractor_guarantor3 = new servicecontractor($this->connection);
			$servicecontractor_guarantor3->fetch($contractguarantordetail3->servicecontractorid);

            $area3 = new area($this->connection);
            $area3->fetch($servicecontractor_guarantor3->areaid);

            $area4 = new area($this->connection);
            $area4->fetch($servicecontractor1->areaid);

            $pdf->SetFont('siddhanta', '', 13, '', true);
            $pdf->multicell(35,10,'साक्षीदार',0,'L',false,1,15,$liney,true,0,false,true,10);
            $pdf->multicell(50,10,'वचनचिठ्ठी लिहून देणार',0,'L',false,1,110,$liney,true,0,false,true,10);
            $liney = $liney+10;
            $pdf->SetFont('siddhanta', '', 11, '', true);
			$pdf->multicell(60,10,'१)सही',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(60,10,'सही:',0,'L',false,1,110,$liney,true,0,false,true,10);
            $pdf->rect(60,$liney,10,10);
			$pdf->rect(150,$liney,10,10);
            $liney = $liney+15;
            $pdf->multicell(100,10,'नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,$servicecontractor_guarantor1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,'नाव:',0,'L',false,1,110,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,$servicecontractor1->name_unicode,0,'L',false,1,125,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $pdf->line(125,$liney,200,$liney);
            $liney = $liney+2;

			$pdf->multicell(100,10,'वय:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$servicecontractor_guarantor1->age,0,'L',false,1,30,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,'वय:',0,'L',false,1,110,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$servicecontractor1->age,0,'L',false,1,125,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $pdf->line(125,$liney,200,$liney);
            $liney = $liney+2;
            $pdf->multicell(100,10,'धंदा:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$servicecontractor_guarantor1->professionname_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,'धंदा:',0,'L',false,1,110,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$servicecservicecontractor1ontractor_guarantor2->professionname_unicode,0,'L',false,1,125,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $pdf->line(125,$liney,200,$liney);
            $liney = $liney+2;
            $pdf->multicell(100,10,'मु.पो.:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$area1->areaname_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,'मु.पो.:',0,'L',false,1,110,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$area4->areaname_unicode,0,'L',false,1,125,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $pdf->line(125,$liney,200,$liney);
            $liney = $liney+2;
            $pdf->multicell(100,10,'ता.:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$area1->subdistrictname_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,'जि.:',0,'L',false,1,60,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,$area1->districtname_unicode,0,'L',false,1,70,$liney,true,0,false,true,10);
            
            $pdf->multicell(100,10,'ता.:',0,'L',false,1,110,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$area4->subdistrictname_unicode,0,'L',false,1,125,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,'जि.:',0,'L',false,1,150,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,$area4->districtname_unicode,0,'L',false,1,160,$liney,true,0,false,true,10);
            
            $liney = $liney+5;
            $pdf->line(30,$liney,60,$liney);
            $pdf->line(70,$liney,100,$liney);
            $pdf->line(125,$liney,150,$liney);
            $pdf->line(160,$liney,200,$liney);
            $liney = $liney+7;
			

            $pdf->SetFont('siddhanta', '', 11, '', true);
			$pdf->multicell(60,10,'२)सही',0,'L',false,1,15,$liney,true,0,false,true,10);
            $pdf->rect(60,$liney,10,10);
			$liney = $liney+14;
            $pdf->multicell(100,10,'नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,$servicecontractor_guarantor2->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+2;

			$pdf->multicell(100,10,'वय:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$servicecontractor_guarantor2->age,0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+2;
            $pdf->multicell(100,10,'धंदा:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$servicecontractor_guarantor2->professionname_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+2;
            $pdf->multicell(100,10,'मु.पो.:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$area2->areaname_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+2;
            $pdf->multicell(100,10,'ता.:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$area2->subdistrictname_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,'जि.:',0,'L',false,1,60,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,$area2->districtname_unicode,0,'L',false,1,70,$liney,true,0,false,true,10);
            
            $liney = $liney+5;
            $pdf->line(30,$liney,60,$liney);
            $pdf->line(70,$liney,100,$liney);
			$liney = $liney+7;

			$pdf->SetFont('siddhanta', '', 11, '', true);
            $pdf->multicell(60,10,'३)सही',0,'L',false,1,15,$liney,true,0,false,true,10);
            $pdf->rect(60,$liney,10,10);
			$liney = $liney+14;
			$pdf->multicell(100,10,'नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,$servicecontractor_guarantor3->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+2;

			$pdf->multicell(100,10,'वय:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$servicecontractor_guarantor3->age,0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+2;
            $pdf->multicell(100,10,'धंदा:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$servicecontractor_guarantor3->professionname_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,'समक्ष',0,'L',false,1,115,$liney,true,0,false,true,10);
			$liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+2;
            $pdf->multicell(100,10,'मु.पो.:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$area3->areaname_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,'मुख्य शेतकी अधिकारी',0,'L',false,1,115,$liney,true,0,false,true,10);
			$liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+2;
            $pdf->multicell(100,10,'ता.:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$area3->subdistrictname_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,'जि.:',0,'L',false,1,60,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,$area3->districtname_unicode,0,'L',false,1,70,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,'श्री सोमेश्वर सह.सा.का. लि.,',0,'L',false,1,115,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,60,$liney);
            $pdf->line(70,$liney,100,$liney);

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

    function contractreceiptdetail(&$connection,$contractid,$sequencenumber)
    {
        $contractreceiptdetail1 = new contractreceiptdetail($connection);
        $query = "select d.contractreceiptdetailid from contract c,contractreceiptdetail d where c.active=1 and d.active=1 and c.contractid=d.contractid and c.contractid=".$contractid." order by d.contractreceiptdetailid";
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contractreceiptdetail1->fetch($row['CONTRACTRECEIPTDETAILID']);
                return $contractreceiptdetail1;
            }
            else
            {
                $i++;
            }
        }
    }
    
}
?>