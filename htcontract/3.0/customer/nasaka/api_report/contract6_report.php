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
class contract_6
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
            $pdf->multicell(150,10,'संमती पत्र',0,'L',false,1,85,$liney,true,0,false,true,10);
            $pdf->SetFont('siddhanta', '', 11, '', true);
			$curdate = date('d/m/Y');
			$pdf->multicell(50,10,'दिनांक:'.$curdate,0,'L',false,1,160,$liney,true,0,false,true,10);
			$pdf->multicell(50,10,'प्रति,',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+7;
            $pdf->multicell(70,10,'मा.कार्यकारी संचालक / अध्यक्ष,',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+7;
            $pdf->multicell(150,10,'जय भवानी सर्व संघ (ट्रस्ट)',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+7;
            $pdf->multicell(150,10,'श्री संत जनार्दन स्वामी नगर, ता.नाशिक, जि.नाशिक',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+7;
			$contracttransportdetail1 = new contracttransportdetail($this->connection);
			$contracttransportdetail1 = $this->contracttransportdetail($this->connection,$contract1->contractid);
            $contractharvestdetail1 = new contractharvestdetail($this->connection);
			$contractharvestdetail1 = $this->contractharvestdetail($this->connection,$contract1->contractid);
            /* $servicecontractor_md1 = new servicecontractor($this->connection);
			$servicecontractor_md1->fetch(6);
            $pdf->multicell(100,10,'तर्फे मा. कार्यकारी संचालक श्री '.$servicecontractor_md1->name_unicode,0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+5;
			$pdf->line(50,$liney,200,$liney);
			$liney = $liney+5;
            $pdf->multicell(25,10,'वय: '.$servicecontractor_md1->age,0,'L',false,1,50,$liney,true,0,false,true,10);
			$pdf->multicell(10,10,'धंदा:',0,'L',false,1,100,$liney,true,0,false,true,10);
			$pdf->multicell(30,10,$servicecontractor_md1->professionname_unicode,0,'L',false,1,120,$liney,true,0,false,true,10);
			$liney = $liney+5;
            $pdf->line(57,$liney,100,$liney);
            $pdf->line(110,$liney,200,$liney);
            $liney = $liney+2;
			$pdf->multicell(150,10,'रा.श्री संत जनार्दन स्वामी नगर, ता.नाशिक, जि.नाशिक',0,'L',false,1,50,$liney,true,0,false,true,10);
			$liney = $liney+7;
			$pdf->multicell(100,10,'यांसी',0,'L',false,1,150,$liney,true,0,false,true,10);
			$liney = $liney+7;
			$servicecontractor1 = new servicecontractor($this->connection);
			$servicecontractor1->fetch($contract1->servicecontractorid);
            $pdf->multicell(100,10,'श्री '.$servicecontractor1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
			$liney = $liney+5;
			$pdf->line(35,$liney,200,$liney);
			$liney = $liney+2;
            $pdf->multicell(25,10,'वय: '.$servicecontractor1->age,0,'L',false,1,30,$liney,true,0,false,true,10);
			$pdf->multicell(10,10,'धंदा:',0,'L',false,1,100,$liney,true,0,false,true,10);
			$pdf->multicell(30,10,$servicecontractor1->professionname_unicode,0,'L',false,1,120,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(37,$liney,100,$liney);
            $pdf->line(110,$liney,200,$liney);
            $liney = $liney+2; */
            /* $area1 = new area($this->connection);
			$area1->fetch($contract1->areaid); */
            
            
            /* $pdf->multicell(15,10,'मु.पो.:',0,'L',false,1,30,$liney,true,0,false,true,10);
			$pdf->multicell(30,10,$servicecontractor1->address_unicode,0,'L',false,1,45,$liney,true,0,false,true,10); */

			/* $pdf->multicell(10,10,'ता.:',0,'L',false,1,100,$liney,true,0,false,true,10);
			$pdf->multicell(30,10,$area1->subdistrictname_unicode,0,'L',false,1,110,$liney,true,0,false,true,10);

			$pdf->multicell(10,10,'जि.:',0,'L',false,1,150,$liney,true,0,false,true,10);
			$pdf->multicell(40,10,$area1->districtname_unicode,0,'L',false,1,160,$liney,true,0,false,true,10);
 */
   			/* $liney = $liney+5;
			$pdf->line(43,$liney,100,$liney);
			$pdf->line(105,$liney,145,$liney);
            $pdf->line(157,$liney,200,$liney);
            $liney = $liney+2;
            $liney = $liney+5; */
            $contractadvancedetail1 = new contractadvancedetail($this->connection);
			$contractadvancedetail1 = $this->contractadvancedetail($this->connection,$contractid,1);
            $contracttransportdetail1 = new contracttransportdetail($this->connection);
            $list1 = $contract1->transportlist();
            if (count($list1)>0)
            {
                $val = intval($list1[0]);
                $contracttransportdetail1->fetch($val);
            }
            if ($contract1->contractcategoryid == 521478963)
            {
                $pdf->SetFont('siddhanta', '', 11, '', true);
                $wrdamt = NumberToWords(number_format_indian($contractadvancedetail1->approvedamount,0,false,false),1);
                $html = '<span style="text-align:justify;">महोदय,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;मी स्वखुशीने संमतीपत्र लिहून देतो ऐसा जे की मी  
                जय भवानी सर्व संघ (ट्रस्ट) यांचेकडे गळीत हंगाम <u>'.$contract1->seasonname_unicode.'</u> 
                तोडणी / वाहतूकीचा करार केला असुन करारापोटी जय भवानी सर्व संघ (ट्रस्ट)
                कडून रक्कम रुपये <u>Rs'.$contractadvancedetail1->approvedamount.'</u> 
                (अक्षरी <u>'.$wrdamt.'</u>) उचल घेतली आहे. मी संघाकडून घेतलेल्या ॲडव्हान्सची 
                परतफेड करणार आहे. परंतु माझ्याकडून काही कारण ॲडव्हान्सची परतफेड झाली नाही 
                किंवा त्यापैकी काही रक्कम माझेकडे येणे बाकी राहिली तर त्यासाठी मी माझे <u>'.$contracttransportdetail1->bankbranchname_unicode.'</u>
                खाते क्रमांक <u>'.$contracttransportdetail1->chequenumber.'</u>
                वरील चेक क्रमांक <u>'.$contracttransportdetail1->chequenumber.'</u> 
                हा चेक जय भवानी सर्व सेवा संघास देत आहे. याबाबत माझी कोणतीही तक्रार नाही.<br>कळावे</p> </span>';
                $pdf->writeHTML($html, true, 0, true, true);
                $liney = $liney+60;
            }
            elseif ($contract1->contractcategoryid == 785415263)
            {
                $pdf->SetFont('siddhanta', '', 11, '', true);
                $wrdamt = NumberToWords(number_format_indian($contractadvancedetail1->approvedamount,0,false,false),1);
                $html = '<span style="text-align:justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;मी कारणे स्वखुशीने संमतीपत्र लिहून देतो ऐसा जे की मी  
                जय भवानी सर्व संघ (ट्रस्ट). '.$contract1->contractcategoryname_unicode.' 
                तोडणी / वाहतूकीचा करार केला असुन त्यापोटी मला एकूण रुपये <u>Rs'.$contractadvancedetail1->approvedamount.'</u> 
                (अक्षरी '.$wrdamt.') इतकी रक्कम उचल म्हणून अदा केली आहे. सदरची रक्कम मला माझे बँक खातेवर/चेकने/रोख 
                प्राप्त झालेली असुन मला त्याबाबत कोणतीही हरकत वा तक्रार नाही. कारखान्याशी मी लिहुन दिलेल्या करारानुसार 
                कारखाना सिझन सुरुवातीपासुन ते संपेपर्यंत काम करणेचे कायदेशीर बंधन माझ्यावर आहे.
                तथापि मी मध्येच काम सोडून गेल्यास वा संपूर्ण हंगामामध्ये मी केलेल्या कामाचे रक्कमेमधून मी कारखान्याकडून घेतलेल्या
                उचल रक्कम रुपये <u>Rs'.$contractadvancedetail1->approvedamount.'</u> ची वसुली /परत फेड न झाल्यास या कामी होणारा कारखान्याचा खर्च व व्याज कारखान्याने माझ्याकडून कायदेशीर कारवाई करून वसूल करावा. हे संमतीपत्र मी स्वतः स्वखुशीने व अक्कल हुशारीने व कोणाच्या दडपणाशिवाय बळी न पडता खालील साक्षीदारांच्या समोर करून दिले असे.</p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;येणे प्रमाणे संमतीपत्र असे.</p> </span>';
                $pdf->writeHTML($html, true, 0, true, true);
                $liney = $liney+80;
            }
            elseif ($contract1->contractcategoryid == 947845153)
            {
                $pdf->SetFont('siddhanta', '', 11, '', true);
                $wrdamt = NumberToWords(number_format_indian($contractadvancedetail1->approvedamount,0,false,false),1);
                $html = '<span style="text-align:justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;मी कारणे स्वखुशीने संमतीपत्र लिहून देतो ऐसा जे की मी  
                जय भवानी सर्व संघ (ट्रस्ट). '.$contract1->contractcategoryname_unicode.' 
                तोडणी / वाहतूकीचा करार केला असुन त्यापोटी मला एकूण रुपये <u>Rs'.$contractadvancedetail1->approvedamount.'</u> 
                (अक्षरी '.$wrdamt.') इतकी रक्कम उचल म्हणून अदा केली आहे. सदरची रक्कम मला माझे बँक खातेवर/चेकने/रोख 
                प्राप्त झालेली असुन मला त्याबाबत कोणतीही हरकत वा तक्रार नाही. कारखान्याशी मी लिहुन दिलेल्या करारानुसार 
                कारखाना सिझन सुरुवातीपासुन ते संपेपर्यंत काम करणेचे कायदेशीर बंधन माझ्यावर आहे.
                तथापि मी मध्येच काम सोडून गेल्यास वा संपूर्ण हंगामामध्ये मी केलेल्या कामाचे रक्कमेमधून मी आपलेकडून घेतलेल्या उचलेची 
                परतफेड करीन त्यामधून वसूल न झालेस ती रक्कम तुम्ही आमचेवर कायदेशीर कारवाई करून
                सव्याज खर्चासह वसूल करावी.</p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;येणे प्रमाणे संमतीपत्र असे.</p> </span>';
                $pdf->writeHTML($html, true, 0, true, true);
                $liney = $liney+80;
            }

			/* $contractguarantordetail1 = new contractguarantordetail($this->connection);
			$contractguarantordetail1 = $this->contractguarantordetail($this->connection,$contractid,1,1);
 */			$pdf->SetFont('siddhanta', '', 11);
			/* $area2 = new area($this->connection);
			$area2->fetch($servicecontractor_guarantor1->areaid); */
            
            $contractguarantordetail1 = new contractguarantordetail($this->connection);
            $list1 = $contract1->guarantorcontractorlist();
            $i=0;
            foreach ($list1 as $value)
            {
                $val = intval($list1[$i]);
                $contractguarantordetail1->fetch($val,1);
                $servicecontractor_guarantor1 = new servicecontractor($this->connection);
                $servicecontractor_guarantor1->fetch($contractguarantordetail1->servicecontractorid);
                
                
                $pdf->multicell(35,10,++$i.')साक्षीदार नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(100,10,$servicecontractor_guarantor1->name_unicode,0,'L',false,1,45,$liney,true,0,false,true,10);
                $pdf->multicell(60,10,'सही',0,'L',false,1,130,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(40,$liney,130,$liney);
                $pdf->line(140,$liney,200,$liney);
                $liney = $liney+2;
                $liney = $liney+5;
            }
            $liney = $liney+7;
            $pdf->multicell(100,10,'अा.वि.',0,'L',false,1,150,$liney,true,0,false,true,10);
            $liney = $liney+10;
			$pdf->line(140,$liney,200,$liney);
			$liney = $liney+2;
			//$pdf->multicell(60,10,'सही',0,'L',false,1,15,$liney,true,0,false,true,10);
			/* $pdf->multicell(100,10,'मुख्य शेतकी अधिकारी',0,'L',false,1,100,$liney,true,0,false,true,10);
			$liney = $liney+7;			
			$pdf->multicell(100,10,'श्री सोमेश्वर सह. सा. का. लि.',0,'L',false,1,100,$liney,true,0,false,true,10); */
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