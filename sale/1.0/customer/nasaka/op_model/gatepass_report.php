<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    include_once("../ip_model/saleinvoiceheader_db_oracle.php");
    include_once("../ip_model/goodspurchaser_db_oracle.php");
//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class gatepass extends swappreport
{	
    public $transactionnumber;
    public $statecode;
    public $salecategorycode;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueType', '', 32);
    	// create new PDF document
	    $this->pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('invoice');
        $this->pdf->SetKeywords('invoice_000.EN');
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));
       // $title = str_pad(' ', 30).'Rajaramnagar, Tal - Dindori Dist - Nashik';
    	//$this->pdf->SetHeaderData('', 0,str_pad(' ', 21).'Kadwa S.S.K. Ltd.' ,$title);
	// set margins
        $this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        // set auto page breaks
        $this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        // set default monospaced font
        $this->pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        
        // set auto page breaks
        //$this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language dependent data:
        $lg = Array();
        $lg['a_meta_charset'] = 'UTF-8';
        $lg['a_meta_dir'] = 'ltr';
        $lg['a_meta_language'] = 'en';
        $lg['w_page'] = 'Page - ';
        // set some language-dependent strings (optional)
	    $this->pdf->setLanguageArray($lg);
	}

    function endreport()
    {
        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output('INV_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 13;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        $saleinvoiceheader1 = new saleinvoiceheader($this->connection);
        $saleinvoiceheader1->transactionnumber = $this->transactionnumber;
        $saleinvoiceheader1->fetch();
        $this->salecategorycode = $saleinvoiceheader1->salecategorycode;
        $this->newrow(5);
        $this->textbox('GSTN No : 27AAXCA2984P1ZJ PAN No : AAXCA2984P',180,10,'S','C',1,'verdana',8);
        //$this->textbox('PAN No : AAXCA2984P',80,150,'S','L',1,'verdana',10);
        $this->newrow(4);
        $this->hline(10,200);
        $this->newrow(2);
        $this->textbox('साखर गेटपास '.$saleinvoiceheader1->goodscategorynameuni,180,10,'S','C',1,'siddhanta',13);
        $this->newrow(5);
        $this->textbox('गेटकिपर यांस',80,10,'S','L',1,'siddhanta',10);
        $this->newrow(4);
        $goodspurchaser1 = new goodspurchaser($this->connection);
        $goodspurchaser1->purchasercode = $saleinvoiceheader1->purchasercode;
        $goodspurchaser1->goodscategorycode = $saleinvoiceheader1->goodscategorycode;
        $goodspurchaser1->fetch();
        $this->textbox('पार्टी - '.$saleinvoiceheader1->purchaser.', '.$goodspurchaser1->location,180,20,'S','L',1,'siddhanta',10);
        $this->newrow(4);
        $this->textbox('ट्रक नंबर -'.$saleinvoiceheader1->vehiclenumber,80,20,'S','L',1,'siddhanta',10);
        $this->textbox('जीएसटी गेटपास नंबर -'.$saleinvoiceheader1->invoicenumberpresuf,80,70,'S','L',1,'siddhanta',10);
        $this->textbox('दिनांक -'.$saleinvoiceheader1->invoicedate,80,150,'S','L',1,'siddhanta',10);
        $this->newrow(4);
        $this->textbox('ड्रायव्हरचे नाव -'.$saleinvoiceheader1->drivername,80,20,'S','L',1,'siddhanta',10);
        $this->textbox('लायसन्स नं -'.$saleinvoiceheader1->driverlicenseno,80,120,'S','L',1,'siddhanta',10);
        $this->newrow(5);
        $this->textbox('यांचे बरोबर पुढील माल बाहेर जाऊ देणे.',180,20,'S','L',1,'siddhanta',10);
        $this->newrow(5);
        $this->hline(10,200,$this->liney);
        $this->textbox('उत्पादन हंगाम',20,10,'S','L',1,'siddhanta',10);
        $this->textbox('गो.नंबर',15,30,'S','L',1,'siddhanta',10);
        $this->textbox('साखरेचा प्रकार',80,50,'S','L',1,'siddhanta',10);
        $this->textbox('लॉट नंबर',20,130,'S','R',1,'siddhanta',10);
        $this->textbox('पोती संख्या',20,150,'S','R',1,'siddhanta',10);
        $this->textbox('वजन (क्वींटल)',30,170,'S','R',1,'siddhanta',10);
        $this->newrow();
        $this->hline(10,200,$this->liney);
        //$this->drawlines($limit);
    }
   
    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        $liney = $this->liney;
        $this->liney = 85;
        $this->hline(10,200,$this->liney-12);
        $this->vline($this->liney-12,$this->liney+$limit,10);
        $this->vline($this->liney-12,$this->liney+$limit,20);
        $this->vline($this->liney-12,$this->liney+$limit,75);
        $this->vline($this->liney-12,$this->liney+$limit,90);
        $this->vline($this->liney-12,$this->liney+$limit,110);
        $this->vline($this->liney-12,$this->liney+$limit,140);
        $this->vline($this->liney-5,$this->liney+$limit,150);
        $this->vline($this->liney-12,$this->liney+$limit,170);
        $this->vline($this->liney-5,$this->liney+$limit,180);
        $this->vline($this->liney-12,$this->liney+$limit,200);
        $this->hline(10,200,$this->liney+$limit);
        $this->hline(10,200,$this->liney+$limit);
        $this->hline(140,200,$this->liney-5);
        $this->liney = $liney;
    }

    function pagefooter($islastpage=false)
    {
        if ($islastpage==false)
        { 
            //$this->drawlines(130-48);
        }
        else
        {
            //$this->drawlines($this->liney-48);
        }
    }

    function detail()
    {
        $query ="select d.productionyearcode
        ,d.godownnumber
        ,d.lotnumber
        ,d.serialnumber
        ,f.finishedgoodsnameeng
        ,f.finishedgoodsnameuni
        ,d.salequantity
        ,f.conversionfactor
        from saleinvoicedetail d,finishedgoods f
        where d.finishedgoodscode=f.finishedgoodscode
        and d.transactionnumber=".$this->transactionnumber."
        order by d.serialnumber";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $noofbags=0;
        $qtls=0;
        $this->newrow(2);
        $basey=$this->liney;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->textbox($row['PRODUCTIONYEARCODE'],22,10,'S','L',1,'siddhanta',11);
            $this->textbox($row['GODOWNNUMBER'],15,33,'S','L',1,'siddhanta',11);
            $this->textbox($row['FINISHEDGOODSNAMEUNI'],80,50,'S','L',1,'siddhanta',11);
            $noofbags = $noofbags+$row['SALEQUANTITY']*100/$row['CONVERSIONFACTOR'];
            $qtls = $qtls+$row['SALEQUANTITY'];
            $this->textbox($row['LOTNUMBER'],20,125,'S','R',1,'siddhanta',11);
            $this->textbox($row['SALEQUANTITY']*100/$row['CONVERSIONFACTOR'],20,150,'S','R',1,'siddhanta',11);
            $this->textbox($row['SALEQUANTITY'],20,170,'S','R',1,'siddhanta',11);
            $this->newrow(5);
        }
        $this->hline(10,200,$this->liney);
        $this->newrow(2);
        $this->textbox('एकूण',20,125,'S','R',1,'siddhanta',11);
        $this->textbox($noofbags,20,150,'S','R',1,'siddhanta',11);
        $this->textbox($qtls,20,170,'S','R',1,'siddhanta',11);
        $this->hline(130,200,$this->liney+5);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney-2,10);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney-2,29);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney-2,45);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney+5,130);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney+5,150);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney+5,170);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney+5,200);
        $this->pagefooter(False);
        $this->textbox('अक्षरी :'.NumberToWords($noofbags,1),100,10,'S','L',1,'siddhanta',11);
        $this->newrow(6);    
        $this->textbox('वरील तपशिला प्रमाणे (ओली नसलेली व न फाटलेली) चांगली साखर पोती मी प्रत्यक्ष मोजून घेतली. पोती व वजन बरोबर आहे.',180,10,'S','L',1,'siddhanta',9);  
        $this->newrow(25);    
        $this->textbox('ड्रायव्हरची सही',55,40,'S','L',1,'siddhanta',9);  
        $this->textbox('गोडाऊन किपर',55,80,'S','L',1,'siddhanta',9);  
        $this->textbox('चिफ अकौंटंट',55,120,'S','L',1,'siddhanta',9);  
        $this->textbox('जनरल मॅनेजर',55,160,'S','L',1,'siddhanta',9);  

        //$this->liney = 10;
        //$this->newrow(5);
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        $saleinvoiceheader1 = new saleinvoiceheader($this->connection);
        $saleinvoiceheader1->transactionnumber = $this->transactionnumber;
        $saleinvoiceheader1->fetch();
        $this->salecategorycode = $saleinvoiceheader1->salecategorycode;
        $this->newrow(35);
        //$this->textbox('PAN No : AAXCA2984P',80,150,'S','L',1,'verdana',10);
        $this->newrow(5);
        $this->hline(10,200);
        $this->newrow(2);
        $this->textbox('Astalaxmi Sugar Ethanol And Energy Limited (Unit-1)',200,10,'S','C',1,'siddhanta',15);
        $this->newrow(5);
        $this->textbox('Nashik Sahakari Sakhar Karkhana Limited, Palse ',200,10,'S','C',1,'siddhanta',13);
        $this->newrow(5);
        $this->textbox('Shree Sant Janardan Swaminagar, Tal & Dist Nashik',200,10,'S','C',1,'siddhanta',11);
        $this->newrow(5);
        $this->hline(10,200,$this->liney);
        $this->textbox('साखर गेटपास '.$saleinvoiceheader1->goodscategorynameuni,180,10,'S','C',1,'siddhanta',13);
        $this->newrow(5);
        $this->textbox('गेटकिपर यांस',80,10,'S','L',1,'siddhanta',10);
        $this->newrow(4);
        $goodspurchaser1 = new goodspurchaser($this->connection);
        $goodspurchaser1->purchasercode = $saleinvoiceheader1->purchasercode;
        $goodspurchaser1->goodscategorycode = $saleinvoiceheader1->goodscategorycode;
        $goodspurchaser1->fetch();
        $this->textbox('पार्टी - '.$saleinvoiceheader1->purchaser.', '.$goodspurchaser1->location,180,20,'S','L',1,'siddhanta',10);
        $this->newrow(4);
        $this->textbox('ट्रक नंबर -'.$saleinvoiceheader1->vehiclenumber,80,20,'S','L',1,'siddhanta',10);
        $this->textbox('जीएसटी गेटपास नंबर -'.$saleinvoiceheader1->invoicenumberpresuf,80,70,'S','L',1,'siddhanta',10);
        $this->textbox('दिनांक -'.$saleinvoiceheader1->invoicedate,80,150,'S','L',1,'siddhanta',10);
        $this->newrow(5);
        $this->textbox('ड्रायव्हरचे नाव -'.$saleinvoiceheader1->drivername,80,20,'S','L',1,'siddhanta',10);
        $this->textbox('लायसन्स नं -'.$saleinvoiceheader1->driverlicenseno,80,120,'S','L',1,'siddhanta',10);
        $this->newrow(5);
        $this->textbox('यांचे बरोबर पुढील माल बाहेर जाऊ देणे.',180,20,'S','L',1,'siddhanta',10);
        $this->newrow(10);
        $this->hline(10,200,$this->liney);
        $this->textbox('उत्पादन हंगाम',20,10,'S','L',1,'siddhanta',10);
        $this->textbox('गो.नंबर',15,30,'S','L',1,'siddhanta',10);
        $this->textbox('साखरेचा प्रकार',80,50,'S','L',1,'siddhanta',10);
        $this->textbox('लॉट नंबर',20,130,'S','R',1,'siddhanta',10);
        $this->textbox('पोती संख्या',20,150,'S','R',1,'siddhanta',10);
        $this->textbox('वजन (क्वींटल)',30,170,'S','R',1,'siddhanta',10);
        $this->newrow();
        $this->hline(10,200,$this->liney);

        $query ="select d.productionyearcode
        ,d.godownnumber
        ,d.lotnumber
        ,d.serialnumber
        ,f.finishedgoodsnameeng
        ,f.finishedgoodsnameuni
        ,d.salequantity
        ,f.conversionfactor
        from saleinvoicedetail d,finishedgoods f
        where d.finishedgoodscode=f.finishedgoodscode
        and d.transactionnumber=".$this->transactionnumber."
        order by d.serialnumber";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $noofbags=0;
        $qtls=0;
        $this->newrow(2);
        $basey=$this->liney;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->textbox($row['PRODUCTIONYEARCODE'],22,10,'S','L',1,'siddhanta',11);
            $this->textbox($row['GODOWNNUMBER'],15,33,'S','L',1,'siddhanta',11);
            $this->textbox($row['FINISHEDGOODSNAMEUNI'],80,50,'S','L',1,'siddhanta',11);
            $noofbags = $noofbags+$row['SALEQUANTITY']*100/$row['CONVERSIONFACTOR'];
            $qtls = $qtls+$row['SALEQUANTITY'];
            $this->textbox($row['LOTNUMBER'],20,125,'S','R',1,'siddhanta',11);
            $this->textbox($row['SALEQUANTITY']*100/$row['CONVERSIONFACTOR'],20,150,'S','R',1,'siddhanta',11);
            $this->textbox($row['SALEQUANTITY'],20,170,'S','R',1,'siddhanta',11);
            $this->newrow(5);
        }
        $this->hline(10,200,$this->liney);
        $this->newrow(2);
        $this->textbox('एकूण',20,125,'S','R',1,'siddhanta',11);
        $this->textbox($noofbags,20,150,'S','R',1,'siddhanta',11);
        $this->textbox($qtls,20,170,'S','R',1,'siddhanta',11);
        $this->hline(130,200,$this->liney+5);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney-2,10);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney-2,29);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney-2,45);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney+5,130);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney+5,150);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney+5,170);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney+5,200);
        $this->pagefooter(False);
        $this->textbox('अक्षरी :'.NumberToWords($noofbags,1),100,10,'S','L',1,'siddhanta',11);
        $this->newrow(6);    
        $this->textbox('वरील तपशिला प्रमाणे (ओली नसलेली व न फाटलेली) चांगली साखर पोती मी प्रत्यक्ष मोजून घेतली. पोती व वजन बरोबर आहे.',180,10,'S','L',1,'siddhanta',9);  
        $this->newrow(25);    
        $this->textbox('ड्रायव्हरची सही',55,40,'S','L',1,'siddhanta',9);  
        $this->textbox('गोडाऊन किपर',55,80,'S','L',1,'siddhanta',9);  
        $this->textbox('चिफ अकौंटंट',55,120,'S','L',1,'siddhanta',9);  
        $this->textbox('जनरल मॅनेजर',55,160,'S','L',1,'siddhanta',9);  

       /*  $this->newrow(5);
        $this->hline(10,200);
        $this->newrow(2);
        $this->textbox('Nashik Sahakari Sakhar Karkhana Ltd., Palse',200,10,'S','C',1,'siddhanta',15);
        $this->newrow(5);
        $this->textbox('Leased By Ashtalaxmi Sugar Ethanol and Energy Nashik Road',200,10,'S','C',1,'siddhanta',13);
        $this->newrow(5);
        $this->textbox('Shree Sant Janardan Swaminagar, Tal & Dist Nashik',200,10,'S','C',1,'siddhanta',11);
        $this->newrow();
        $this->hline(10,200,$this->liney);
        $this->textbox('डीलीव्हरी स्लीप'.$saleinvoiceheader1->goodscategorynameuni,180,10,'S','C',1,'siddhanta',13);
        $this->newrow(5);
        $this->textbox('बिल नंबर -'.$saleinvoiceheader1->invoicenumberpresuf,80,10,'S','L',1,'siddhanta',10);
        $this->textbox('दिनांक -'.$saleinvoiceheader1->invoicedate,80,150,'S','L',1,'siddhanta',10);
        $this->newrow(5);
        $this->textbox('गोडाऊन क्लार्क,',80,10,'S','L',1,'siddhanta',10);
        $this->newrow(5);
        $this->textbox('वाहन क्रमांक -'.$saleinvoiceheader1->vehiclenumber.' मध्ये खालीलप्रमाणे साखर पोते देणे',150,20,'S','L',1,'siddhanta',10);
        $this->newrow(5);
        $this->hline(10,200,$this->liney);
        $this->textbox('उत्पादन हंगाम',20,10,'S','L',1,'siddhanta',10);
        $this->textbox('गो.नंबर',15,30,'S','L',1,'siddhanta',10);
        $this->textbox('साखरेचा प्रकार',80,50,'S','L',1,'siddhanta',10);
        $this->textbox('लॉट नंबर',20,130,'S','R',1,'siddhanta',10);
        $this->textbox('पोती संख्या',20,150,'S','R',1,'siddhanta',10);
        $this->textbox('वजन (क्वींटल)',30,170,'S','R',1,'siddhanta',10);
        $this->newrow();
        $this->hline(10,200,$this->liney);

        $query ="select d.productionyearcode
        ,d.godownnumber
        ,d.lotnumber
        ,d.serialnumber
        ,f.finishedgoodsnameeng
        ,f.finishedgoodsnameuni
        ,d.salequantity
        ,f.conversionfactor
        from saleinvoicedetail d,finishedgoods f
        where d.finishedgoodscode=f.finishedgoodscode
        and d.transactionnumber=".$this->transactionnumber."
        order by d.serialnumber";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $noofbags=0;
        $qtls=0;
        $this->newrow(2);
        $basey=$this->liney;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->textbox($row['PRODUCTIONYEARCODE'],22,10,'S','L',1,'siddhanta',11);
            $this->textbox($row['GODOWNNUMBER'],15,33,'S','L',1,'siddhanta',11);
            $this->textbox($row['FINISHEDGOODSNAMEUNI'],80,50,'S','L',1,'siddhanta',11);
            $noofbags = $noofbags+$row['SALEQUANTITY']*100/$row['CONVERSIONFACTOR'];
            $qtls = $qtls+$row['SALEQUANTITY'];
            $this->textbox($row['LOTNUMBER'],20,125,'S','R',1,'siddhanta',11);
            $this->textbox($row['SALEQUANTITY']*100/$row['CONVERSIONFACTOR'],20,150,'S','R',1,'siddhanta',11);
            $this->textbox($row['SALEQUANTITY'],20,170,'S','R',1,'siddhanta',11);
            $this->newrow(5);
        }
        $this->hline(10,200,$this->liney);
        $this->newrow(2);
        $this->textbox('एकूण',20,125,'S','R',1,'siddhanta',11);
        $this->textbox($noofbags,20,150,'S','R',1,'siddhanta',11);
        $this->textbox($qtls,20,170,'S','R',1,'siddhanta',11);
        $this->hline(10,200,$this->liney+5);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney+5,10);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney-2,29);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney+5,45);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney+5,130);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney+5,150);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney+5,170);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney+5,200);
        $this->pagefooter(False);
        $this->textbox('ट्रक मधील पोती संख्या',100,10,'S','L',1,'siddhanta',10);
        $this->newrow(6);
        $this->textbox('अक्षरी :'.NumberToWords($noofbags,1),100,10,'S','L',1,'siddhanta',11);
        $this->newrow(6);    
        $this->textbox('वरील तपशिला प्रमाणे (ओली नसलेली व न फाटलेली) चांगली साखर पोती मी प्रत्यक्ष मोजून घेतली. पोती व वजन बरोबर आहे.',180,10,'S','L',1,'siddhanta',9);  
        $this->newrow(8);    
        $this->textbox('ऑफीस गोडाऊन क्लार्क',55,50,'S','L',1,'siddhanta',9);  
        $this->textbox('गोडाऊन क्लार्क',55,100,'S','L',1,'siddhanta',9);  
        $this->textbox('माल ताब्यात घेणाराची सही',55,150,'S','L',1,'siddhanta',9);   */
    }

}    
?>