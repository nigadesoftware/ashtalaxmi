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
    include_once("../api_oracle/contractphotodetail_db_oracle.php");
    include_once("../api_oracle/contractfingerprintdetail_db_oracle.php");
class contract_11
{	
	public $contractid;
    public $connection;
    
	public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

    function printpageheader(&$pdf,&$liney,$contractid)
    {
    	/* require("../info/phpsqlajax_dbinfo.php");
    	// Opens a this->connection to a MySQL server
        $this->connection=mysqli_connect($hostname_rawmaterial, $username_rawmaterial, $password_rawmaterial, $database_rawmaterial);
        // Check this->connection
        if (mysqli_connect_errno())
        {
            echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication error1</span>';
            exit;
        }
        mysqli_query($this->connection,'SET NAMES UTF8'); */
        $contract1 = new contract($this->connection);

		if ($contract1->fetch($contractid))
		{
            $pdf->SetFont('siddhanta', '', 15, '', true);
            $pdf->multicell(150,10,'करार पत्र',0,'L',false,1,95,$liney,true,0,false,true,10);
            $liney = $liney+15;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $curdate = date('d/m/Y');
			$pdf->multicell(180,10,'दिनांक:'.$curdate,0,'L',false,1,135,$liney,true,0,false,true,10);
			$liney = $liney+7;
            $pdf->SetFont('siddhanta', '', 13, '', true);
            $pdf->multicell(70,10,'लिहून घेणार,',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->SetFont('siddhanta', '', 11, '', true);
            $liney = $liney+7;
            $pdf->multicell(150,10,'कार्यकारी संचालक, नाशिक सहकारी साखर कारखाना लि., पळसे, ',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+7;
            $pdf->multicell(150,10,'श्री संत जनार्दन स्वामी नगर, ता.नाशिक, जि.नाशिक',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+7;
			$contracttransportdetail1 = new contracttransportdetail($this->connection);
			$contracttransportdetail1 = $this->contracttransportdetail($this->connection,$contract1->contractid);
			$servicecontractor1 = new servicecontractor($this->connection);
			$servicecontractor1->fetch($contract1->servicecontractorid);
            $pdf->SetFont('siddhanta', '', 13, '', true);
            $pdf->multicell(70,10,'लिहून देणार,',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+7;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $pdf->multicell(100,10,'श्री '.$servicecontractor1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
			$liney = $liney+7;
            $pdf->multicell(25,10,'वय: '.$contract1->age,0,'L',false,1,30,$liney,true,0,false,true,10);
			$pdf->multicell(10,10,'धंदा:',0,'L',false,1,100,$liney,true,0,false,true,10);
			$pdf->multicell(30,10,$servicecontractor1->professionname_unicode,0,'L',false,1,120,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(37,$liney,100,$liney);
            $pdf->line(110,$liney,200,$liney);
            $liney = $liney+2;
            /* $area1 = new area($this->connection);
			$area1->fetch($contract1->areaid); */
            
            
            $pdf->multicell(15,10,'मु.पो.:',0,'L',false,1,30,$liney,true,0,false,true,10);
			$pdf->multicell(120,10,$servicecontractor1->address_unicode,0,'L',false,1,45,$liney,true,0,false,true,10);

			/* $pdf->multicell(10,10,'ता.:',0,'L',false,1,100,$liney,true,0,false,true,10);
			$pdf->multicell(30,10,$area1->subdistrictname_unicode,0,'L',false,1,110,$liney,true,0,false,true,10);

			$pdf->multicell(10,10,'जि.:',0,'L',false,1,150,$liney,true,0,false,true,10);
			$pdf->multicell(40,10,$area1->districtname_unicode,0,'L',false,1,160,$liney,true,0,false,true,10);
 */
   			$liney = $liney+5;
			$pdf->line(43,$liney,200,$liney);
            $liney = $liney+2;
            $liney = $liney+10;
            
            $pdf->SetFont('siddhanta', '', 11, '', true);

            $html = '<span style="text-align:justify;">
            ऊस तोडणी वाहतुकीच्या कामाचा खाली दिलेल्या शर्तीप्रमाणे व नियमाप्रमाणे करारनामा लिहून देत आहे.<br>
१)मी, मला नेमून दिलेला ऊस दररोज तोडून त्याची वाहतूक करीन व मजूर आणि बैलगाडी / ट्रॅक्टर ट्रेलर / डंपिंग ट्रॅक्टर 
अगर ट्रक कायम स्वरूपी कामावर ठेवण्याची व्यवस्था करीन.<br>
२)ऊस व्यवस्थित भुईसपाट तोडून त्याच्या मोळ्या एक मनुष्यास उचलता येतील अशा बांधीन. ऊसाचे कोणत्याही 
प्रकारचे नुकसान होऊ देणार नाही.<br>
३)ऊसाचे वाढे अर्धा हिस्सा मालकास देईन व अर्धा हिस्सा मी माझ्याकरिता व मजुरांकारिता ठेवीन.<br>
४)मजुरांची राहण्याची व्यवस्था मी करीन. मी माझे मजुरांकरिता दिलेले कोयते,कोपीचे सामान,
व ऊस बांधण्यासाठी दिलेले वायररोप व्यवस्थित वापरीन 
व गळीत हंगाम संपलेनंतर जाण्यापूर्वी ते परत करीन. गहाळ झालेल्या किंवा खराब झालेल्या सामानाचे पैसे भरीन.<br>
५)माझ्या ड्रायव्हर व क्लिनर आणि माझ्या मजुरांच्या वर्तणूकीबद्दल मी जबाबदार राहीन. कारखान्याच्या नोकर वर्गाशी तक्रार 
करणार नाहीत.<br>
६)अचानक व अनपेक्षित कारणाने कारखाना बंद पडल्यास नुकसान भरपाई मागणार नाही. कारखाना बंद पडले 
मुळे किंवा अन्य काही कारणामुळे वाहने रिकामी होण्यास उशीर लागल्यास त्यावेळी कारखान्याचे संबंधीत अधिकारी 
सांगतील त्याप्रमाणे वाहन रिकामे करून घेईन. अशा वेळी गडबड किंवा कारखान्याचे कामामध्ये व्यत्यय येईल 
असे कोणतेही कृत्य करणार नाही तसे माझेकडून किंवा माझे नोकराकडून घडल्यास माझे काम बंद करण्याचा 
अधिकार आपणाकडे राहील. त्याबद्दल तक्रार करणार नाही.<br>
७)मी माझ्या मजुरांचे पैसे वेळेवर व बिनचूक देईल व त्यांची कोणतीही तक्रार येऊ देणार नाही.<br>
८)मी सीझन संपेपर्यंत काम करीन, मध्येच काम सोडून गेलो तर नुकसान भरपाई देण्यास पात्र राहीन. माझे 
कमिशनवर हक्क सांगणार नाही व त्यापुढील हंगामात काम करण्यास अपात्र ठरेन.<br>
९)मला मिळालेला अॅडव्हान्स कारखाना सांगेल त्याप्रमाणे माझे होणारे बिलातून वसूल करून देईल.<br>
१०)कारखान्याचे तोडणी वाहतूकीचे दर व कमिशन मला मान्य आहे व त्याबद्दल काही तक्रार करणार नाही.<br>
११)सभासदांचे व फॅक्टरीचे कोणत्याही प्रकारचे नुकसान होऊ देणार नाही.<br>
१२)ट्रॅक्टर, ट्रेलर, ट्रकचे रोड परमिट वेळचेवेळी भरीन ट्रॅक्टर/ट्रकवर लायसेन्स असलेला ड्रायव्हर ठेवीन.
ड्रायव्हरकडून किंवा अन्य रितीने वाहनाचा अपघात झाल्यास त्यास मी जबाबदार राहीन त्याची तोषीस 
कारखान्यास लागू देणार नाही.<br>
१३)मी प्लॉटमध्ये कायम हजार राहून बैलगाडी / ट्रेलर अगर ट्रक माझे देखरेखीखाली ऊस तोडणीचे काम व्यवस्थित 
करून घेईल.<br>
१४)रस्ते दुरुस्तीसाठी पाचट लागल्यास मे.शेतकी अधिकारी यांचे हुकुमानुसार माझ्या वाहनाकडून कारखान्याच्या 
दराप्रमाणे पाचट वाहतूक करून घेईल.<br>
१५)माझे काम करणारे मजुरांकडून सभासदाचे ऊसाचे कोणत्याही प्रकारचे नुकसान झाल्यास व खाण्यासाठी ऊस 
घरी आणणे वगैरे गोष्टीस मी जबाबदार राहीन.<br>
१६)जुनेच गडी मी लावीन व ते न आल्यास त्यांचे ऐवजी नवे गडी आणीन. त्याबद्दल जुन्या गड्यांची येऊ 
देणार नाही.<br>
१७)कारखान्याकडे ऊस वाहतुकीसाठी असणाऱ्या सर्व वाहनानुसार व कारखान्याच्या दैनिक गळीतानुसार कारखान्याचे 
संबंधीत अधिकारी सांगतील त्याप्रमाणे ऊस पुरवठा करीन यात कोणत्याही प्रकारची कसुर करणार नाही.<br>
१८)माझ्या बैलगाडी / ट्रॅक्टर / ट्रेलर / डंपिंग ट्रॅक्टर /ट्रकने ऊस भरून आणलेनंतर कारखान्याचे सेवक सांगतील त्याठिकाणी मी माझे वाहन 
उभे करीन व संबंधिताकडून वाहन रिकामे होण्याच्या क्रमांकाचा बिल्ला घेईन. माझ्या वाहनाच्या रिकामे होण्याचा नंबर 
आल्यानंतर व संबंधीत सेवकांनी माझे ड्रायव्हरला वाहन वजन करून घेण्यासाठी बोलविल्यानंतरच माझे वाहन 
काट्यावर घेईन. बिगर नंबरचे किंवा मध्येच माझे वाहन काट्यावर घेतले जाणार नाही. काट्यावर किंवा 
गव्हाणीवर वाहन मुद्दाम उभे करून कारखान्याचे कोणत्याही कामात व्यत्यय आणणार नाही तसे माझेकडून 
किंवा माझे नोकरांकडून घडल्यास व त्यामुळे माझे वाहनाला काम नाकारल्यास त्याबद्दल तक्रार करणार नाही.<br>
१९)कारखान्याचे कामाचे सोईचे दृष्टीने कारखाना अधिकारी सांगतील त्या ठिकाणी व त्याच गटामध्ये ऊसाचे तोडणी 
वाहतूकीचे काम करीन.<br>
२०)कारखान्याचे गरजेनुसार क्रेनमध्ये (बैलगाडी / ट्रॅक्टर) आदला बदल करण्याचा संपूर्ण अधिकार 
कारखान्याचा राहील.<br>
२१)ऊस वाहतूक करीत असताना  माझे ट्रेलर - ट्रक - ट्रॅक्टर  गाडीस  लाकडी  डांबाचा वापर करीन. 
माझे वाहनातून गव्हाणामध्ये लोखंड, लोखंडी वायररोप गेल्यास त्यास मला सर्वस्वी जबाबदार धरून 
होणारी नुकसान भरपाई माझे तोडणी वाहतूक बिलातून परस्पर वसूल करावी. त्याबद्दल माझी काही तक्रार असणार नाही<br>
</span>';
            $pdf->writeHTML($html, true, 0, true, true);
            $liney = 90;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            /* $pdf->multicell(60,10,'करारनामा लिहून देणार',0,'L',false,1,110,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->multicell(40,10,'स्थळ: ',0,'L',false,1,15,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,'नाव:',0,'L',false,1,110,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,$servicecontractor1->name_unicode,0,'L',false,1,120,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(120,$liney,200,$liney);
            $liney = $liney+2;
            $pdf->multicell(40,10,'सही',0,'L',false,1,110,$liney,true,0,false,true,10);
            $curdate = date('d/m/Y');
            $pdf->multicell(50,10,'दिनांक:'.$curdate,0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(120,$liney,200,$liney);
            $liney = $liney+2; */
            $liney = $liney+7;
            
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $pdf->multicell(35,10,'साक्षीदार',0,'L',false,1,15,$liney,true,0,false,true,10);
            //$pdf->multicell(60,10,'करारनामा लिहून घेणार',0,'L',false,1,110,$liney,true,0,false,true,10);
            $liney = $liney+10;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            
            $list1 = $contract1->guarantorcontractorlist();
            $i=0;
            $contractguarantordetail1 = new contractguarantordetail($this->connection);
            $servicecontractor_guarantor1 = new servicecontractor($this->connection);
            foreach ($list1 as $value)
            {
                $val = intval($list1[$i]);
                $contractguarantordetail1->fetch($val,1);
                $servicecontractor_guarantor1->fetch($contractguarantordetail1->servicecontractorid);
            
                $pdf->multicell(100,10,++$i.')नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(100,10,$servicecontractor_guarantor1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
                $pdf->multicell(10,10,'सही:',0,'L',false,1,120,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(30,$liney,100,$liney);
                $pdf->line(120,$liney,200,$liney);
                $liney = $liney+5;

            }
			
            $liney = $liney+5;
/*             $pdf->line(30,$liney,60,$liney);
            $pdf->line(70,$liney,100,$liney); */
            $liney = $liney+2;
			$pdf->multicell(60,10,'सही:',0,'L',false,1,150,$liney,true,0,false,true,10);
			$liney = $liney+7;
            $pdf->line(150,$liney,200,$liney);

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