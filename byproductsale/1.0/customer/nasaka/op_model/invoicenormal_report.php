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
class invoice extends swappreport
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
        $this->liney = 10;
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
        $this->newrow(8);
        $this->textbox('GSTN No : 27AAXCA2984P1ZJ PAN No : AAXCA2984P',180,10,'S','C',1,'verdana',8);
        //$this->textbox('PAN No : AAXCA2984P',80,150,'S','L',1,'verdana',10);
        $this->newrow(2);
        
        $this->newrow(2);
        $this->hline(10,200);
        if ($saleinvoiceheader1->goodscategorycode==1)
        {
            $this->textbox('Tax Invoice '.$saleinvoiceheader1->salecategorynameeng.' '
            .$saleinvoiceheader1->goodscategorynameeng.' Sale',180,10,'S','C',1,'verdana',13);
        }
        else
        {
            $this->textbox('Tax Invoice '.$saleinvoiceheader1->goodscategorynameeng.' Sale',180,10,'S','C',1,'verdana',13);
        }
        
        $this->newrow(10);
        $this->textbox('invoice Number : '.$saleinvoiceheader1->invoicenumberpresuf,80,10,'S','L',1,'verdana',10);
        $this->textbox('invoice Date : '.$saleinvoiceheader1->invoicedate,80,110,'S','L',1,'verdana',10);
        $this->newrow();
        $purchaser1 = new goodspurchaser($this->connection);
        $purchaser1->purchasercode = $saleinvoiceheader1->purchasercode;
        $purchaser1->goodscategorycode = $saleinvoiceheader1->goodscategorycode;
        $purchaser1->fetch();
        $this->statecode=$purchaser1->statecode;
        $broker1 = new goodspurchaser($this->connection);
        $broker1->purchasercode = $saleinvoiceheader1->brokercode;
        $broker1->goodscategorycode = $saleinvoiceheader1->goodscategorycode;
        $broker1->fetch();
        $shiftto1 = new goodspurchaser($this->connection);
        $shiftto1->purchasercode = $saleinvoiceheader1->shippingpartycode;
        $shiftto1->goodscategorycode = $saleinvoiceheader1->goodscategorycode;
        $shiftto1->fetch();
        
        $this->textbox('Purchaser : '.$purchaser1->purchasercode.' '.$purchaser1->purchasernameeng,100,10,'S','L',1,'verdana',10);
        //$this->textbox('Broker Name : '.$broker1->purchasercode.' '.$broker1->purchasernameeng,80,70,'S','L',1,'verdana',10);
        $this->textbox('Consignee : '.$shiftto1->purchasercode.' '.$shiftto1->purchasernameeng,100,110,'S','L',1,'verdana',10);
        $this->newrow();

        $this->textbox('Address : '.$purchaser1->address,100,10,'S','L',1,'verdana',8,'','Y');
        //$this->textbox('Address     : '.$broker1->address,80,70,'S','L',1,'verdana',10);
        $this->textbox('Address : '.$shiftto1->address,100,110,'S','L',1,'verdana',8,'','Y');
        $this->newrow(10);
        
        $this->textbox('State : '.$purchaser1->statecode.' '.$purchaser1->statenameeng,100,10,'S','L',1,'verdana',10);
        //$this->textbox('State       : '.$broker1->statecode.' '.$broker1->statenameeng,80,70,'S','L',1,'verdana',10);
        $this->textbox('State : '.$shiftto1->statecode.' '.$shiftto1->statenameeng,100,110,'S','L',1,'verdana',10);
        $this->newrow(4);

        $this->textbox('GST No : '.$purchaser1->gstinnumber,100,10,'S','L',1,'verdana',10);
        //$this->textbox('GST No      : '.$broker1->gstinnumber,80,70,'S','L',1,'verdana',10);
        $this->textbox('GST No : '.$shiftto1->gstinnumber,100,110,'S','L',1,'verdana',10);
        $this->newrow();
        //$this->textbox('Broker Name : '.$broker1->purchasercode.' '.$broker1->purchasernameeng,80,10,'S','L',1,'verdana',10);
        //$this->textbox('HSNCODE : '.$saleinvoiceheader1->hsncode(),100,110,'S','L',1,'verdana',10);
        $this->newrow();
        $this->textbox('Sr.',10,10,'S','L',1,'verdana',10);
        $this->textbox('Description of Goods',50,20,'S','L',1,'verdana',10);
        $this->textbox('Unit',10,75,'S','L',1,'verdana',10);
        $this->textbox('Qty',20,80,'S','R',1,'verdana',10);
        $this->textbox('Rate',15,100,'S','R',1,'verdana',10);
        $this->textbox('Taxable Value',20,120,'S','R',1,'verdana',10);
        if ($this->statecode == 27)
        {
            $this->textbox('CGST',30,130,'S','R',1,'verdana',10);
            $this->textbox('SGST',30,160,'S','R',1,'verdana',10);
        }
        elseif (in_array($statecode, array(4,7,25,26,31,34,35)))
        {
            $this->textbox('IGST',30,130,'S','R',1,'verdana',10);
            $this->textbox('UGST',30,160,'S','R',1,'verdana',10);
        }
        elseif (($this->statecode != 27))
        {
            $this->textbox('IGST',30,130,'S','R',1,'verdana',10);
            $this->textbox('UGST',30,160,'S','R',1,'verdana',10);
        }
        $this->newrow();
        //$this->textbox('M.Tonne',10,80,'S','R',1,'verdana',9);
        $this->textbox('%',10,137,'S','R',1,'verdana',9);
        $this->textbox('Amount',20,147,'S','R',1,'verdana',9);
        $this->textbox('%',15,162,'S','R',1,'verdana',9);
        $this->textbox('Amount',20,177,'S','R',1,'verdana',9);
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow();
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
        $this->vline($this->liney-12,$this->liney+$limit,85);
        $this->vline($this->liney-12,$this->liney+$limit,100);
        $this->vline($this->liney-12,$this->liney+$limit,120);
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
            $this->drawlines(130-48);
        }
        else
        {
            $this->drawlines($this->liney-48);
        }
    }

    function detail()
    {
        $query ="select d.serialnumber
                ,f.finishedgoodsnameeng
                ,f.finishedgoodsnameuni
                ,d.salequantity
                ,d.salerate
                ,d.amount
                ,d.cgstrate
                ,d.sgstrate
                ,d.igstrate
                ,d.ugstrate
                ,d.cgstamount
                ,d.sgstamount
                ,d.igstamount
                ,d.ugstamount
                ,f.unit,f.hsncode
                from saleinvoicedetail d,finishedgoods f
                where d.finishedgoodscode=f.finishedgoodscode
                and d.transactionnumber=".$this->transactionnumber."
                order by d.serialnumber";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->textbox($row['SERIALNUMBER'],10,10,'S','L',1,'verdana',9);
            $this->textbox($row['FINISHEDGOODSNAMEENG'],50,20,'S','L',1,'verdana',9);
            $this->textbox($row['UNIT'],10,75,'S','L',1,'verdana',9);
            $this->textbox($row['SALEQUANTITY'],15,85,'S','R',1,'verdana',9);
            $this->textbox($this->moneyFormatIndia($row['SALERATE']),20,100,'S','R',1,'verdana',9);
            $this->textbox($this->moneyFormatIndia($row['AMOUNT']),20,120,'S','R',1,'verdana',9);
            if ($this->statecode == 27)
            {
                $this->textbox($row['CGSTRATE'],10,140,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row['CGSTAMOUNT']),20,150,'S','R',1,'verdana',9);
                $this->textbox($row['SGSTRATE'],10,170,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row['SGSTAMOUNT']),20,180,'S','R',1,'verdana',9);
            }
            elseif (in_array($statecode, array(4,7,25,26,31,34,35)))
            {
                $this->textbox($row['IGSTRATE'],10,140,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row['IGSTAMOUNT']),20,150,'S','R',1,'verdana',9);
                $this->textbox($row['UGSTRATE'],10,170,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row['UGSTAMOUNT']),20,180,'S','R',1,'verdana',9);
            }
            elseif ($this->statecode != 27)
            {
                $this->textbox($row['IGSTRATE'],10,140,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row['IGSTAMOUNT']),20,150,'S','R',1,'verdana',9);
                $this->textbox($row['UGSTRATE'],10,170,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row['UGSTAMOUNT']),20,180,'S','R',1,'verdana',9);
            }
            $this->newrow(4);
            $this->textbox('[HSN-'.$row['HSNCODE'].']',30,40,'N','R',1,'verdana',9);
            $this->newrow(-4);
            $this->newrow(10);
         }
         $this->hline(10,200,$this->liney);
         $this->pagefooter(False);
                 // call header
        $this->liney = 167;
        $saleinvoiceheader1 = new saleinvoiceheader($this->connection);
        $saleinvoiceheader1->transactionnumber = $this->transactionnumber;
        $saleinvoiceheader1->fetch();
        $this->newrow(2);
        $this->textbox('Total Taxable Amount',50,140,'S','L',1,'verdana',9);
        $this->textbox($this->moneyFormatIndia($saleinvoiceheader1->taxableamount),30,170,'S','R',1,'verdana',9);
        $this->newrow();
        $this->hline(140,200,$this->liney+$limit);
        if ($saleinvoiceheader1->cgstamount>0)
        {
            $this->textbox('Total CGST Amount',50,140,'S','L',1,'verdana',9);
            $this->textbox($this->moneyFormatIndia($saleinvoiceheader1->cgstamount),30,170,'S','R',1,'verdana',10);
            $this->newrow();
            $this->hline(140,200,$this->liney+$limit);
        }
        if ($saleinvoiceheader1->sgstamount>0)
        {
            $this->textbox('Total SGST Amount',50,140,'S','L',1,'verdana',9);
            $this->textbox($this->moneyFormatIndia($saleinvoiceheader1->sgstamount),30,170,'S','R',1,'verdana',9);
            $this->newrow();
            $this->hline(140,200,$this->liney+$limit);
        }
        if ($saleinvoiceheader1->igstamount>0)
        {
            $this->textbox('Total IGST Amount',50,140,'S','L',1,'verdana',9);
            $this->textbox($this->moneyFormatIndia($saleinvoiceheader1->igstamount),30,170,'S','R',1,'verdana',9);
            $this->newrow();
            $this->hline(140,200,$this->liney+$limit);
        }
          if ($saleinvoiceheader1->ugstamount>0)
        {
            $this->textbox('Total UGST Amount',50,140,'S','L',1,'verdana',9);
            $this->textbox($this->moneyFormatIndia($saleinvoiceheader1->ugstamount),30,170,'S','R',1,'verdana',9);
            $this->newrow();
            $this->hline(140,200,$this->liney+$limit);
        }
        if ($saleinvoiceheader1->tcsamount>0)
        {
            $this->textbox('TCS',50,140,'S','L',1,'verdana',9);
            $this->textbox($this->moneyFormatIndia($saleinvoiceheader1->tcsamount),30,170,'S','R',1,'verdana',9);
            $this->newrow();
            $this->hline(140,200,$this->liney+$limit);
        }
        $this->textbox('Total  Amount',50,140,'S','L',1,'verdana',9);
        $this->textbox($this->moneyFormatIndia($saleinvoiceheader1->grossamount),30,170,'S','R',1,'verdana',9);
        $this->newrow();
        $this->vline($this->liney,160,140);
        $this->vline($this->liney,167,175);
        $this->vline($this->liney,160,200);
        $this->hline(140,200,$this->liney+$limit);
        $this->liney = 170;
        $this->textbox('Declaration:',135,10,'S','L',1,'verdana',9,'','Y');
        $this->newrow(5);
        $this->textbox('1. I/We declare that this invoice shows actual price of the goods and/or service described and that all particulars are true and',135,10,'S','L',1,'verdana',9,'','Y');
        $this->newrow(10);
        $this->textbox('2. Error and mission in this invoice shall be subjected to the jurisdiction of the Dindori',135,10,'S','L',1,'verdana',9,'','Y');
        $this->liney = 204;
        $this->textbox('Invoice Value inwords :',45,10,'S','L',1,'verdana',9);
        if ($saleinvoiceheader1->grossamount-intval($saleinvoiceheader1->grossamount)==0)
        {
            $this->textbox('Rs '.getStringOfAmount(intval($saleinvoiceheader1->grossamount),0).' Only',180,50,'S','L',1,'siddhanta',9,'','Y');
        }
        else
        {
            $paise = ($saleinvoiceheader1->grossamount-intval($saleinvoiceheader1->grossamount))*100;
            $paise = $paise%100;
            $wrd1 = getStringOfAmount(intval($saleinvoiceheader1->grossamount));
            $wrd2 = getStringOfAmount($paise);
            $wrd = 'Rs '.$wrd1.' and '.$wrd2.' Paise Only';
            $this->textbox($wrd,180,50,'S','L',1,'siddhanta',9,'','Y');
        }
        $this->newrow(10);
        $this->textbox('Booking Station :',45,10,'S','L',1,'verdana',9);
        $this->textbox($saleinvoiceheader1->bookingstation,45,50,'S','L',1,'verdana',9);
        $this->textbox('Preparation Date & Time :',45,110,'S','L',1,'verdana',9);
        $this->textbox($saleinvoiceheader1->preparationtime,45,160,'S','L',1,'verdana',9);
        $this->newrow(3);
        $this->textbox('Receiving Station :',45,10,'S','L',1,'verdana',9);
        $this->textbox($saleinvoiceheader1->receivingstation,45,50,'S','L',1,'verdana',9);
        $this->textbox('Removal Date & Time :',45,110,'S','L',1,'verdana',9);
        $this->textbox($saleinvoiceheader1->removaltime,45,160,'S','L',1,'verdana',9);
        $this->newrow(3);
        if ($saleinvoiceheader1->bookingstation =='')
        {
            $this->textbox('By Road',45,10,'S','L',1,'verdana',9);
        }
        else
        {
            $this->textbox('By Rail',45,10,'S','L',1,'verdana',9);
        }
        $this->newrow(4);
        $this->textbox('Amount Of Tax Subject To Reverse Charges',80,10,'S','L',1,'verdana',9);
        
        $this->newrow();
        $this->textbox('Driver name :',30,10,'S','L',1,'verdana',9);
        $this->textbox($saleinvoiceheader1->drivername,100,40,'S','L',1,'verdana',9);
        $this->newrow();
        $this->textbox('Vehicle Number :',30,10,'S','L',1,'verdana',9);
        $this->textbox($saleinvoiceheader1->vehiclenumber,30,40,'S','L',1,'verdana',9);
        $this->textbox('Authorised Signatory',70,150,'S','L',1,'verdana',9);
        $this->newrow(5);
        if ($saleinvoiceheader1->salecategorycode == 3)
        {
            $this->textbox('Export Under Quota of MIEQ Sugar Mill: Nashik Sahakari Sakhar Karkhana Ltd, Plant Name: Palase, Plant Code: 12001 At Post: Rajaram Nagar, Taluka: DINDORI Dist: NASHIK, PIN-422209, Maharashtra State, India.  GSTIN: 27AAXCA2984P1ZJ (as per GOI (Dept. of Food & Pub. Distribution) Circular File No. 1(4)/2018-SP-I dated 28.09.2018) EXPORT UNDER CONCESSIONAL RATE OF DUTY AS PER NOTI. 40/2017 & 41/2017 DT.23.10.17',120,10,'S','L',1,'verdana',7,'','Y');
        }
        $this->newrow(15);    
        $this->textbox('Receivers Signature with Stamp',70,10,'S','L',1,'verdana',9);  
        $this->textbox('For Nashik Sahakari Sakhar Karkhana Ltd',70,130,'S','L',1,'verdana',9);  
        //$this->newrow(10);    
        //$this->textbox('Authorised Signature',55,150,'S','L',1,'verdana',9);  
    }
}    
?>