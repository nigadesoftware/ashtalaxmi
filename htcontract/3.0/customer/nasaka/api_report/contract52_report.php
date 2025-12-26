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
class contract_52
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
            $pdf->multicell(150,10,'संमतीपत्र',0,'L',false,1,95,$liney,true,0,false,true,10);
            $liney = $liney+15;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            
            $pdf->SetFont('siddhanta', '', 11, '', true);

            $html = '<span style="text-align:justify;">
१. ट्रॅक्टर कंत्राटदार यांनी ऊस वाहतुकीसाठी फक्त लाकडी तांबाचा वापर करण्यात यावा. लोखंडी तांबाचा ऊस वाहतुकीसाठी वापर करू नये.<br>
२. ट्रॅक्टर कंत्राटदार यांनी प्रत्येक यासाठी कमीत कमी आठ-दहा ऊस तोडणी कोयते आणावीत. कमी कोयते असणार यावर त्याप्रमाणात ॲडव्हान्स आकारणी केली जाईल.<br>
३. एका टोळी पाठीमागे एकच ट्रॅक्टरने ऊस वाहतूक करता येईल.<br>
४. एका टोळी पाठीमागे 2 ट्रॅक्टर चालवता येणार नाहीत चालवलेस एका ट्रॅक्टरच्या ॲडव्हान्सला व्याजाची आकारणी केली जाईल त्याबाबत कंत्राटदाराची काही असणार नाही.<br>
५. कार्यक्षेत्राबाहेरील ट्रॅक्टर कंत्राटदारांना कमीत कमी दोन महिने कार्यक्षेत्रात ऊस वाहतूक करावी लागेल.<br>
६. कारखाना सुरू झाल्यापासून कारखाना बंद होईपर्यंत वाहतूक केलेसच लेबर सोडण्याचे वाट खर्ची डिझेल देणेत येईल. कारखाना बंद होण्यापूर्वी लेबर गावाकडे घालवलेस डिझेल/ भाडे मिळणार नाही. हे आम्हास मान्य आहे.<br>
७. एकाच कुटुंबातील 2 ट्रॅक्टर करारामध्ये असलेस दोन्ही ट्रॅक्टरने वेगवेगळे प्लॉटमध्ये ऊस तोडणी करावी लागेल. एकाच प्लॉटमध्ये तोडणी केलेस एकाच वाहनाचे लेबर सोडविण्यासाठी डीझेल/  रोख रक्कम मिळेल. एका वाहनाचे ॲडव्हान्सला व्याजाची आकारणी केली जाईल. हे मला मान्य आहे.v
८. जे ट्रॅक्टर कंत्राटदार सुरुवातीचे १/ १-२ ते २ महिने काळात जवळचे अंतरात (१-५) मध्ये ऊस तोडणी वाहतूक करतील त्यांना प्राधान्याने लांबचे अंतरात (  गेटकेन) ऊस तोडण्यासाठी पाठवले  जाईल.<br>
९. ऊस तोडणी करताना कारखान्याची रोज चिट्ठी घेणे बंधनकारक राहील. रोज चिठ्ठीशिवाय तोडलेला कारखान्याने  नाकारल्यास त्याची सर्व जबाबदारी वाहन मालक व टोळीवर  राहील.<br>
१०.कारखाना सुरू झाल्यानंतर वाहन मध्येच बंद झालेस अथवा इतर कारखान्यास गेलेस संपूर्ण ॲडव्हान्स  रक्कमेस व्याज आकारले जाईल व तोडणी वाहतुकीचे कमिशन व भाडे दिले जाणार नाही.<br>
११.बिगर ॲडव्हान्स करार धारकांना सुरुवातीपासून कारखाना बंद होईपर्यंत तोडणी वाहतूक केले तरच ठरलेले कमिशन व भाडे मिळेल.<br>
१२.कारखान्याचा गाळत होता सुरू झाल्यानंतर तोडणी मजूर हजर न झालेस ॲडव्हान्स रक्कम व्याजासह भरणा करण्याची जबाबदारी आमचेवर  राहील. <br>
</span>';
            $pdf->writeHTML($html, true, 0, true, true);
            $liney = 150;
            $pdf->SetFont('siddhanta', '', 11, '', true);
			
            $liney = $liney+5;
            $liney = $liney+2;
			$pdf->multicell(60,10,'सही:',0,'L',false,1,150,$liney,true,0,false,true,10);
			$liney = $liney+7;
            $pdf->line(150,$liney,200,$liney);
            $pdf->multicell(60,10,'ट्रॅक्टर कंत्राटदार'.$contract1->servicecontractorname_unicode,0,'L',false,1,150,$liney,true,0,false,true,10);
			

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