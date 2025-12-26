<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_A4_P.php");
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
        $this->pdf->SetSubject('gatepass');
        $this->pdf->SetKeywords('gatepass_000.EN');
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
        $this->liney = 15;
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
        $this->newrow(5);
        $this->hline(10,200);
        $this->newrow(2);
        $this->textbox('गेटपास '.$saleinvoiceheader1->goodscategorynameuni,180,10,'S','C',1,'siddhanta',13);
        $this->newrow(15);
        $this->textbox('गेटकिपर यांस',80,10,'S','L',1,'siddhanta',10);
        $this->newrow(5);
        $this->textbox('पार्टीचे नाव -'.$saleinvoiceheader1->purchaser,180,10,'S','L',1,'siddhanta',10);
        $this->newrow(5);
        $this->textbox('ट्रक नंबर -'.$saleinvoiceheader1->vehiclenumber,80,20,'S','L',1,'siddhanta',10);
        $this->textbox('जीएसटी गेटपास नंबर -'.$saleinvoiceheader1->invoicenumberpresuf,80,70,'S','L',1,'siddhanta',10);
        $this->textbox('दिनांक -'.$saleinvoiceheader1->invoicedate,80,150,'S','L',1,'siddhanta',10);
        $this->newrow(5);
        $this->textbox('ड्रायव्हरचे नाव -'.$saleinvoiceheader1->drivername,80,20,'S','L',1,'siddhanta',10);
        $this->textbox('लायसन्स नं -'.$saleinvoiceheader1->driverlicenseno,80,120,'S','L',1,'siddhanta',10);
        $this->newrow(5);
        $this->textbox('यांचे बरोबर पुढील माल बाहेर जाऊ देणे.',80,20,'S','L',1,'siddhanta',10);
        $this->newrow(15);
        $this->hline(10,160,$this->liney);
        $this->textbox('माल',120,10,'S','L',1,'siddhanta',10);
        $this->textbox('वजन/संख्या',30,130,'S','R',1,'siddhanta',10);
        $this->newrow();
        $this->hline(10,160,$this->liney);
        //$this->drawlines($limit);
    }
   
    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        $liney = $this->liney;
        $this->liney = 85;
        $this->hline(10,160,$this->liney-12);
        $this->vline($this->liney-12,$this->liney+$limit,10);
        $this->vline($this->liney-12,$this->liney+$limit,130);
        $this->vline($this->liney-12,$this->liney+$limit,200);
        $this->hline(10,160,$this->liney+$limit);
        $this->hline(10,160,$this->liney+$limit);
        $this->hline(140,160,$this->liney-5);
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
        ,d.serialnumber
        ,f.finishedgoodsnameeng
        ,f.finishedgoodsnameuni
        ,d.salequantity
        ,f.conversionfactor
        ,f.unit
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
            $this->textbox($row['FINISHEDGOODSNAMEUNI'],120,10,'S','L',1,'siddhanta',11);
            $qtls = $qtls+$row['SALEQUANTITY'];
            if ($row['UNIT']!='Nos' and $row['UNIT']!='Bag')
            $this->textbox(number_format($row['SALEQUANTITY'],3).' '.$row['UNIT'],30,130,'S','R',1,'siddhanta',11);
            else
            $this->textbox($row['SALEQUANTITY'].' '.$row['UNIT'],30,130,'S','R',1,'siddhanta',11);
            $this->newrow(5);
        }
        $this->hline(10,160,$this->liney);
        $this->newrow(2);
        $this->textbox('एकूण',20,100,'S','R',1,'siddhanta',11);
        $this->textbox(number_format($qtls,3),30,130,'S','R',1,'siddhanta',11);
        $this->hline(130,160,$this->liney+5);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney-2,10);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney+5,130);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney+5,160);
        $this->pagefooter(False);
        $saleinvoiceheader1 = new saleinvoiceheader($this->connection);
        $saleinvoiceheader1->transactionnumber = $this->transactionnumber;
        $saleinvoiceheader1->fetch();
        $this->newrow(10);  
        if ($saleinvoiceheader1->netweight!=0)
        {
            $this->textbox('भरगाडी वजन :'.number_format($saleinvoiceheader1->loadweight,3),50,10,'S','L',1,'siddhanta',11);
            $this->textbox('रिकामी गाडी वजन :'.number_format($saleinvoiceheader1->emptyweight,3),60,60,'S','L',1,'siddhanta',11);
            $this->textbox('निव्वळ वजन :'.number_format($saleinvoiceheader1->netweight,3),50,120,'S','L',1,'siddhanta',11);
        
            $qtls=number_format($qtls,3);
            $paise = round(($qtls-intval($qtls))*1000);
            $paise = $paise%1000;
            $wrd1 = NumberToWords(intval($qtls),1);
            $wrd2 = NumberToWords($paise,1);
            $wrd = $wrd1.' मे.टन  आणि '.$wrd2.' किलो फक्त';
            $this->newrow(10);
            $this->textbox('अक्षरी :'.$wrd,100,10,'S','L',1,'siddhanta',11);
        }
        else
        {
            $this->newrow(10);
        }
        $this->newrow(6);    
        $this->textbox('वरील तपशिला प्रमाणे चांगला माल मी प्रत्यक्ष मोजून घेतला. वजन बरोबर आहे.',180,10,'S','L',1,'siddhanta',9);  
        $this->newrow(15);    
        $this->textbox('क्लार्क',55,50,'S','L',1,'siddhanta',9);  
        $this->textbox('ड्रायव्हरची सही',55,150,'S','L',1,'siddhanta',9);  
        //$this->liney = 10;
        //$this->newrow(5);
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        $this->newrow(10);
        $this->hline(10,200,$this->liney);
        //start of second part
        $this->newrow(2);
        $this->textbox('Nashik Sahakari Sakhar Karkhana Ltd.,',200,10,'S','C',1,'siddhanta',15);
        $this->newrow();
        $this->textbox('Shree Sant Janardan Swami Nagar, Tal-Nashik, Dist-Nashik',200,10,'S','C',1,'siddhanta',11);
        $this->newrow();
        $this->textbox('गेटपास '.$saleinvoiceheader1->goodscategorynameuni,180,10,'S','C',1,'siddhanta',13);
        $this->newrow(15);
        $this->textbox('गेटकिपर यांस',80,10,'S','L',1,'siddhanta',10);
        $this->newrow(5);
        $this->textbox('पार्टीचे नाव -'.$saleinvoiceheader1->purchaser,180,10,'S','L',1,'siddhanta',10);
        $this->newrow(5);
        $this->textbox('ट्रक नंबर -'.$saleinvoiceheader1->vehiclenumber,80,20,'S','L',1,'siddhanta',10);
        $this->textbox('जीएसटी गेटपास नंबर -'.$saleinvoiceheader1->invoicenumberpresuf,80,70,'S','L',1,'siddhanta',10);
        $this->textbox('दिनांक -'.$saleinvoiceheader1->invoicedate,80,150,'S','L',1,'siddhanta',10);
        $this->newrow(5);
        $this->textbox('ड्रायव्हरचे नाव -'.$saleinvoiceheader1->drivername,80,20,'S','L',1,'siddhanta',10);
        $this->textbox('लायसन्स नं -'.$saleinvoiceheader1->driverlicenseno,80,120,'S','L',1,'siddhanta',10);
        $this->newrow(5);
        $this->textbox('यांचे बरोबर पुढील माल बाहेर जाऊ देणे.',80,20,'S','L',1,'siddhanta',10);
        $this->newrow(15);
        $this->hline(10,160,$this->liney);
        $this->textbox('माल',120,10,'S','L',1,'siddhanta',10);
        $this->textbox('वजन/संख्या',30,130,'S','R',1,'siddhanta',10);
        $this->newrow();
        $this->hline(10,160,$this->liney);

        $query ="select d.productionyearcode
        ,d.serialnumber
        ,f.finishedgoodsnameeng
        ,f.finishedgoodsnameuni
        ,d.salequantity
        ,f.conversionfactor
        ,f.unit
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
            $this->textbox($row['FINISHEDGOODSNAMEUNI'],120,10,'S','L',1,'siddhanta',11);
            $qtls = $qtls+$row['SALEQUANTITY'];
            if ($row['UNIT']!='Nos' and $row['UNIT']!='Bag')
            $this->textbox(number_format($row['SALEQUANTITY'],3).' '.$row['UNIT'],30,130,'S','R',1,'siddhanta',11);
            else
            $this->textbox($row['SALEQUANTITY'].' '.$row['UNIT'],30,130,'S','R',1,'siddhanta',11);
            $this->newrow(5);
        }
        $this->hline(10,160,$this->liney);
        $this->newrow(2);
        $this->textbox('एकूण',20,100,'S','R',1,'siddhanta',11);
        $this->textbox(number_format($qtls,3),30,130,'S','R',1,'siddhanta',11);
        $this->hline(130,160,$this->liney+5);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney-2,10);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney+5,130);
        $this->vline($this->liney-($this->liney-$basey+9),$this->liney+5,160);
        $this->pagefooter(False);
        $saleinvoiceheader1 = new saleinvoiceheader($this->connection);
        $saleinvoiceheader1->transactionnumber = $this->transactionnumber;
        $saleinvoiceheader1->fetch();
        $this->newrow(10);  
        if ($saleinvoiceheader1->netweight!=0)
        {
            $this->textbox('भरगाडी वजन :'.number_format($saleinvoiceheader1->loadweight,3),50,10,'S','L',1,'siddhanta',11);
            $this->textbox('रिकामी गाडी वजन :'.number_format($saleinvoiceheader1->emptyweight,3),60,60,'S','L',1,'siddhanta',11);
            $this->textbox('निव्वळ वजन :'.number_format($saleinvoiceheader1->netweight,3),50,120,'S','L',1,'siddhanta',11);
            $qtls=number_format($qtls,3);
            $paise = round(($qtls-intval($qtls))*1000);
            $paise = $paise%1000;
            $wrd1 = NumberToWords(intval($qtls),1);
            $wrd2 = NumberToWords($paise,1);
            $wrd = $wrd1.' मे.टन  आणि '.$wrd2.' किलो फक्त';
            $this->newrow(10);
            $this->textbox('अक्षरी :'.$wrd,100,10,'S','L',1,'siddhanta',11);
        }
        else
        {
            $this->newrow(10);
        }
        $this->newrow(6);    
        $this->textbox('वरील तपशिला प्रमाणे चांगला माल मी प्रत्यक्ष मोजून घेतला. वजन बरोबर आहे.',180,10,'S','L',1,'siddhanta',9);  
        $this->newrow(15);    
        $this->textbox('क्लार्क',55,50,'S','L',1,'siddhanta',9);  
        $this->textbox('ड्रायव्हरची सही',55,150,'S','L',1,'siddhanta',9);  

        //end of second part
    }
}    
?>