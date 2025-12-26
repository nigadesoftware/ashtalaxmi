<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_l.php");
    include_once("../info/routine.php");
    include_once("../ip_model/saleinvoiceheader_db_oracle.php");
    include_once("../ip_model/goodspurchaser_db_oracle.php");
//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class invoicedespatch extends swappreport
{	
    public $goodscategorycode;
    public $fromdate;
    public $todate;
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
	    $this->pdf = new MYPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Invoice Despatch Report');
        $this->pdf->SetKeywords('INVDSPREP_000.EN');
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
        $this->pdf->Output('INVDSPREP_000.pdf', 'I');
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
        $this->newrow(5);
        $this->textbox('Sugar Despatched',250,10,'S','C',1,'verdana',13);
        $this->newrow(8);
        $this->textbox('for the period from '.DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y').' to '.DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y'),250,10,'S','C',1,'verdana',11);
        $this->newrow(10);
        $this->hline(10,300,$this->liney,'C');
        $this->textbox('Desp. Date',30,10,'S','L',1,'verdana',11);
        $this->textbox('Inv. No',30,40,'S','L',1,'verdana',11);
        $this->textbox('Inv. Date',30,75,'S','L',1,'verdana',11);
        $this->textbox('Purchaser',70,100,'S','L',1,'verdana',11);
        $this->textbox('Destination',70,200,'S','L',1,'verdana',11);
        $this->textbox('Quantity(qtl)',35,260,'S','L',1,'verdana',11);
        $this->hline(10,300,$this->liney+6,'C');
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
        $query ="select h.transactionnumber,h.purchasercode,goodscategorycode,brokercode,shippingpartycode
        from saleinvoiceheader h
        where h.invoicedate>='".$this->fromdate."'
        and h.invoicedate<='".$this->todate."'
        order by invoicenumber";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $qty=0;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $saleinvoiceheader1 = new saleinvoiceheader($this->connection);
            $saleinvoiceheader1->transactionnumber = $row['TRANSACTIONNUMBER'];
            $saleinvoiceheader1->fetch();

            $goodspurchaser1 = new goodspurchaser($this->connection);
            $goodspurchaser1->purchasercode = $row['PURCHASERCODE'];
            $goodspurchaser1->goodscategorycode = $row['GOODSCATEGORYCODE'];
            $goodspurchaser1->fetch();

            $broker1 = new goodspurchaser($this->connection);
            $broker1->purchasercode = $row['BROKERCODE'];
            $broker1->goodscategorycode = $row['GOODSCATEGORYCODE'];
            $broker1->fetch();

            $shippingparty1 = new goodspurchaser($this->connection);
            $shippingparty1->purchasercode = $row['SHIPPINGPARTYCODE'];
            $shippingparty1->goodscategorycode = $row['GOODSCATEGORYCODE'];
            $shippingparty1->fetch();

            $this->textbox($saleinvoiceheader1->removaltime,30,10,'S','L',1,'verdana',8);
            $this->textbox($saleinvoiceheader1->invoicenumberpresuf,35,40,'S','L',1,'verdana',8);
            $this->textbox($saleinvoiceheader1->invoicedate,25,75,'S','L',1,'verdana',9);
            $this->textbox($goodspurchaser1->purchasercode.' '.$goodspurchaser1->purchasernameeng,90,100,'S','L',1,'verdana',8);
            $this->textbox($shippingparty1->address,70,200,'S','L',1,'verdana',8);
            $q=$saleinvoiceheader1->quantity();
            $qty=$qty+$q;
            $this->textbox($q,30,250,'S','R',1,'verdana',8);

            $this->newrow();
            $this->hline(10,300,$this->liney,'C');    
        }
        $this->textbox('Total',30,240,'S','L',1,'verdana',10);
        $this->textbox($qty,30,250,'S','R',1,'verdana',10);
        $this->newrow();
        $this->hline(10,300,$this->liney,'C');
    }
}    
?>