<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    include_once("../ip_model/salememoheader_db_oracle.php");
    include_once("../ip_model/saletenderheader_db_oracle.php");
    include_once("../ip_model/goodspurchaser_db_oracle.php");
//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class memo extends swappreport
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
        $this->pdf->SetSubject('Memo');
        $this->pdf->SetKeywords('MEMO_000.EN');
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
        $this->pdf->Output('SUBLED_000.pdf', 'I');
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
        $salememoheader1 = new salememoheader($this->connection);
        $salememoheader1->transactionnumber = $this->transactionnumber;
        $salememoheader1->fetch();
        $this->salecategorycode = $salememoheader1->salecategorycode;
        $this->newrow(6);
        $this->textbox('GSTN No : 27AAXCA2984P1ZJ PAN No : AAXCA2984P',180,10,'S','C',1,'verdana',8);
        $this->newrow(5);
        $this->hline(10,200);
        $this->newrow(2);
        $this->textbox($salememoheader1->salecategorynameeng.' Sugar Sale Memo',180,10,'S','C',1,'verdana',13);
        $this->newrow(7);
        $this->textbox('Memo Number : '.$salememoheader1->memonumberpresuf,80,10,'S','L',1,'verdana',10);
        $this->textbox('Memo Date : '.$salememoheader1->memodate,80,110,'S','L',1,'verdana',10);
        $this->newrow();
        $purchaser1 = new goodspurchaser($this->connection);
        $purchaser1->purchasercode = $salememoheader1->purchasercode;
        $purchaser1->fetch();
        $this->statecode=$purchaser1->statecode;
        $broker1 = new goodspurchaser($this->connection);
        $broker1->purchasercode = $salememoheader1->brokercode;
        $broker1->fetch();
        $shiftto1 = new goodspurchaser($this->connection);
        $shiftto1->purchasercode = $salememoheader1->shippingpartycode;
        $shiftto1->fetch();

        $this->textbox('खरेदीदार : '.$purchaser1->purchasernameuni,100,10,'S','L',1,'siddhanta',11);
        $this->newrow();
        $this->textbox('Purchaser : '.$purchaser1->purchasercode.' '.$purchaser1->purchasernameeng,100,10,'S','L',1,'verdana',10);
        //$this->textbox('Broker Name : '.$broker1->purchasercode.' '.$broker1->purchasernameeng,80,70,'S','L',1,'verdana',10);
        $this->textbox('Consignee : '.$shiftto1->purchasercode.' '.$shiftto1->purchasernameeng,100,110,'S','L',1,'verdana',10);
        $this->newrow();

        $this->textbox('Address : '.$purchaser1->address,100,10,'S','L',1,'verdana',8,'','Y');
        //$this->textbox('Address     : '.$broker1->address,80,70,'S','L',1,'verdana',10);
        $this->textbox('Address : '.$shiftto1->address,100,110,'S','L',1,'verdana',8,'','Y');
        $this->newrow();
        
        $this->textbox('State : '.$purchaser1->statecode.' '.$purchaser1->statenameeng,100,10,'S','L',1,'verdana',10);
        //$this->textbox('State       : '.$broker1->statecode.' '.$broker1->statenameeng,80,70,'S','L',1,'verdana',10);
        $this->textbox('State : '.$shiftto1->statecode.' '.$shiftto1->statenameeng,100,110,'S','L',1,'verdana',10);
        $this->newrow();

        $this->textbox('GST No : '.$purchaser1->gstinnumber,100,10,'S','L',1,'verdana',10);
        //$this->textbox('GST No      : '.$broker1->gstinnumber,80,70,'S','L',1,'verdana',10);
        $this->textbox('GST No : '.$shiftto1->gstinnumber,100,110,'S','L',1,'verdana',10);
        $this->newrow();
        $this->textbox('Broker Name : '.$broker1->purchasercode.' '.$broker1->purchasernameeng,100,10,'S','L',1,'verdana',10);
        $this->textbox('HSNCODE : 170114',100,110,'S','L',1,'verdana',10);
        $this->newrow();
        $this->textbox('Sr.',10,10,'S','L',1,'verdana',10);
        $this->textbox('Description of Goods',50,20,'S','L',1,'verdana',10);
        $this->textbox('Qty',15,70,'S','R',1,'verdana',10);
        $this->textbox('Rate',15,80,'S','R',1,'verdana',10);
        $this->textbox('Taxable',30,90,'S','R',1,'verdana',10);
        if ($this->statecode == 27)
        {
            $this->textbox('CGST',30,112,'S','R',1,'verdana',10);
            $this->textbox('SGST',30,137,'S','R',1,'verdana',10);
        }
        elseif (in_array($statecode, array(4,7,25,26,31,34,35)))
        {
            $this->textbox('IGST',30,112,'S','R',1,'verdana',10);
            $this->textbox('UGST',30,137,'S','R',1,'verdana',10);
        }
        elseif (($this->statecode != 27))
        {
            $this->textbox('IGST',30,112,'S','R',1,'verdana',10);
            $this->textbox('UGST',30,137,'S','R',1,'verdana',10);
        }
        $this->textbox('Total',30,160,'S','R',1,'verdana',10);
        $this->newrow();
        $this->textbox('Qtls',15,70,'S','R',1,'verdana',9);
        $this->textbox('Value',30,90,'S','R',1,'verdana',10);
        $this->textbox('%',10,120,'S','R',1,'verdana',9);
        $this->textbox('Amount',20,127,'S','R',1,'verdana',9);
        $this->textbox('%',15,140,'S','R',1,'verdana',9);
        $this->textbox('Amount',20,154,'S','R',1,'verdana',9);
        $this->textbox('Value',30,160,'S','R',1,'verdana',10);
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow();
       
        //$this->drawlines($limit);
    }
   
    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        $liney = $this->liney;
        $this->liney = 92;
        $this->hline(10,200,$this->liney-12);
        $this->vline($this->liney-12,$this->liney+$limit,10);
        $this->vline($this->liney-12,$this->liney+$limit,20);
        $this->vline($this->liney-12,$this->liney+$limit,75);
        $this->vline($this->liney-12,$this->liney+$limit,85);
        $this->vline($this->liney-12,$this->liney+$limit,101);
        $this->vline($this->liney-12,$this->liney+$limit,125);
        $this->vline($this->liney-5,$this->liney+$limit,132);
        $this->vline($this->liney-12,$this->liney+$limit,150);
        $this->vline($this->liney-5,$this->liney+$limit,157);
        $this->vline($this->liney-12,$this->liney+$limit,175);
        $this->vline($this->liney-12,$this->liney+$limit,200);
        $this->hline(10,200,$this->liney+$limit);
        $this->hline(10,200,$this->liney+$limit);
        $this->hline(125,175,$this->liney-5);
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
                ,d.productionyearcode
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
                ,d.grossamount
                ,d.TENDERTRANSACTIONNUMBER
                ,f.goodscategorycode
                ,y.periodname_eng
                from salememodetail d
                ,finishedgoods f
                ,nst_nasaka_db.YEARPERIOD y
                where d.finishedgoodscode=f.finishedgoodscode
                and d.transactionnumber=".$this->transactionnumber."
                and d.productionyearcode=y.yearperiodcode(+)
                order by d.serialnumber";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->textbox($row['SERIALNUMBER'],10,10,'S','L',1,'verdana',9);
            $saletenderheader1 = new saletenderheader($this->connection);
            $saletenderheader1->transactionnumber = $row['TENDERTRANSACTIONNUMBER'];
            $saletenderheader1->goodscategorycode = $row['GOODSCATEGORYCODE'];
            $saletenderheader1->fetch();
            $this->textbox($row['FINISHEDGOODSNAMEENG'],50,20,'S','L',1,'verdana',9);
            $this->textbox($row['SALEQUANTITY'],15,70,'S','R',1,'verdana',9);
            $this->textbox($this->moneyFormatIndia($row['SALERATE']),20,81,'S','R',1,'verdana',9);
            $this->textbox($this->moneyFormatIndia($row['AMOUNT']),30,95,'S','R',1,'verdana',9);
            if ($this->statecode == 27)
            {
                $this->textbox($row['CGSTRATE'],10,122,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row['CGSTAMOUNT']),20,130,'S','R',1,'verdana',9);
                $this->textbox($row['SGSTRATE'],10,147,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row['SGSTAMOUNT']),20,155,'S','R',1,'verdana',9);
            }
            elseif (in_array($statecode, array(4,7,25,26,31,34,35)))
            {
                $this->textbox($row['IGSTRATE'],10,122,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row['IGSTAMOUNT']),20,130,'S','R',1,'verdana',9);
                $this->textbox($row['UGSTRATE'],10,147,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row['UGSTAMOUNT']),20,155,'S','R',1,'verdana',9);
            }
            elseif ($this->statecode != 27)
            {
                $this->textbox($row['IGSTRATE'],10,122,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row['IGSTAMOUNT']),20,130,'S','R',1,'verdana',9);
                $this->textbox($row['UGSTRATE'],10,147,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row['UGSTAMOUNT']),20,155,'S','R',1,'verdana',9);
            }
            $this->textbox($this->moneyFormatIndia($row['GROSSAMOUNT']),30,170,'S','R',1,'verdana',9);
            $this->newrow(5);
            $this->textbox('Prod Year:'.$row['PERIODNAME_ENG'].'('.$saletenderheader1->tendernumberpresuf.')',50,20,'S','L',1,'verdana',8);
            $this->newrow();
         }
         $this->hline(10,200,$this->liney);
         $this->pagefooter(False);
                 // call header
        $this->liney = 167;
        $salememoheader1 = new salememoheader($this->connection);
        $salememoheader1->transactionnumber = $this->transactionnumber;
        $salememoheader1->fetch();
        $this->newrow(9);
        $this->textbox('Total Taxable Amt',50,140,'S','L',1,'verdana',9);
        $this->textbox($this->moneyFormatIndia($salememoheader1->taxableamount),30,170,'S','R',1,'verdana',9);
        $this->newrow();
        $this->hline(140,200,$this->liney+$limit);
        if ($salememoheader1->cgstamount>0)
        {
            $this->textbox('Total CGST Amt',50,140,'S','L',1,'verdana',9);
            $this->textbox($this->moneyFormatIndia($salememoheader1->cgstamount),30,170,'S','R',1,'verdana',9);
            $this->newrow();
            $this->hline(140,200,$this->liney+$limit);
        }
        if ($salememoheader1->sgstamount>0)
        {
            $this->textbox('Total SGST Amt',50,140,'S','L',1,'verdana',9);
            $this->textbox($this->moneyFormatIndia($salememoheader1->sgstamount),30,170,'S','R',1,'verdana',9);
            $this->newrow();
            $this->hline(140,200,$this->liney+$limit);
        }
        if ($salememoheader1->igstamount>0)
        {
            $this->textbox('Total IGST Amt',50,140,'S','L',1,'verdana',9);
            $this->textbox($this->moneyFormatIndia($salememoheader1->igstamount),30,170,'S','R',1,'verdana',9);
            $this->newrow();
            $this->hline(140,200,$this->liney+$limit);
        }
          if ($salememoheader1->ugstamount>0)
        {
            $this->textbox('Total UGST Amt',50,140,'S','L',1,'verdana',9);
            $this->textbox($this->moneyFormatIndia($salememoheader1->ugstamount),30,170,'S','R',1,'verdana',9);
            $this->newrow();
            $this->hline(140,200,$this->liney+$limit);
        }
        if ($salememoheader1->tcs>0)
        {
            $this->textbox('+TCS',50,140,'S','L',1,'verdana',9);
            $this->textbox($this->moneyFormatIndia($salememoheader1->tcs),30,170,'S','R',1,'verdana',9);
            $this->newrow();
            $this->hline(140,200,$this->liney+$limit);
        }
        elseif ($salememoheader1->tds>0)
        {
            $this->textbox('-TDS',50,140,'S','L',1,'verdana',9);
            $this->textbox($this->moneyFormatIndia($salememoheader1->tds),30,170,'S','R',1,'verdana',9);
            $this->newrow();
            $this->hline(140,200,$this->liney+$limit);
        }
        $this->textbox('Total  Amt',50,140,'S','L',1,'verdana',9);
        $this->textbox($this->moneyFormatIndia($salememoheader1->grossamount),30,170,'S','R',1,'verdana',9);
        $this->newrow();
        $this->vline($this->liney,174,140);
        $this->vline($this->liney,174,175);
        $this->vline($this->liney,174,200);
        $this->hline(140,200,$this->liney+$limit);
        $this->liney = 170;
        //$this->textbox('',135,10,'S','L',1,'verdana',9,'','Y');
        $this->liney = 190;
        $this->textbox('Driver name :',30,10,'S','L',1,'verdana',9);
        $this->textbox($salememoheader1->drivername,100,40,'S','L',1,'verdana',10);
        $this->newrow();
        $this->textbox('Vehicle Number :',30,10,'S','L',1,'verdana',9);
        $this->textbox($salememoheader1->vehiclenumber,30,40,'S','L',1,'verdana',10);
        $this->newrow(20);
        //$this->textbox('Invoice Value inwords :',45,10,'S','L',1,'verdana',9);
        //$this->textbox(NumberToWords($salememoheader1->grossamount,0),100,50,'S','L',1,'siddhanta',10);
        $this->textbox('Invoice Value inwords :',45,10,'S','L',1,'verdana',9);
        if ($salememoheader1->grossamount-intval($salememoheader1->grossamount)==0)
        {
            //$this->textbox(NumberToWords(intval($salememoheader1->grossamount),0),150,50,'S','L',1,'siddhanta',9,'','Y');
            $this->textbox('Rs '.getStringOfAmount(intval($salememoheader1->grossamount),0).' Only',180,50,'S','L',1,'siddhanta',9,'','Y');
        }
        else
        {
            $paise = round(($salememoheader1->grossamount-intval($salememoheader1->grossamount))*100,0);
            $paise = $paise%100;
            $wrd1 = getStringOfAmount(intval($salememoheader1->grossamount));
            $wrd2 = getStringOfAmount($paise);
            $wrd = 'Rs '.$wrd1.' and '.$wrd2.' Paise Only';
            $this->textbox($wrd,150,50,'S','L',1,'siddhanta',9,'','Y');
        }
        $this->newrow(25);    
        $this->textbox('Sugar Sale Clerk',50,10,'S','L',1,'verdana',9);
        $this->textbox('Sugar Sale Accountant',55,50,'S','L',1,'verdana',9);  
        $this->textbox('Chief Accountant',55,100,'S','L',1,'verdana',9);  
        $this->textbox('General Manager',55,150,'S','L',1,'verdana',9);  
    }

}    
?>