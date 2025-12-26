<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");
    include_once("../ip_model/saleinvoiceheader_db_oracle.php");
    include_once("../ip_model/goodspurchaser_db_oracle.php");
//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class saleregisterdetail extends swappreport
{	
    public $goodscategorycode;
    public $fromdate;
    public $todate;
    public $purchasercode;
    public $finishedgoodscode;
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
        $this->pdf->SetSubject('Sale Register Detail');
        $this->pdf->SetKeywords('SLRGDT_000.EN');
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
        $this->pdf->Output('SLRGDT_000.pdf', 'I');
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
        $this->textbox('Sale Register Detail',250,10,'S','C',1,'verdana',13);
        $this->newrow(8);
        $this->textbox('for the period from '.DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y').' to '.DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y'),250,10,'S','C',1,'verdana',11);
        $this->newrow(10);
        $this->hline(10,300,$this->liney,'C');
        $this->textbox('Inv. No',30,10,'S','L',1,'verdana',11);
        $this->textbox('Inv. Date',30,40,'S','L',1,'verdana',11);
        $this->textbox('Sale Type',25,70,'S','L',1,'verdana',11);
        $this->textbox('Purchaser',70,90,'S','L',1,'verdana',11);
        $this->textbox('Shipping',70,160,'S','L',1,'verdana',11);
        $this->newrow(7);
        $this->textbox('Prod.Season',20,10,'S','L',1,'verdana',11);
        $this->textbox('Item',50,30,'S','L',1,'verdana',11);
        $this->textbox('Quantity',20,70,'S','R',1,'verdana',11);
        $this->textbox('Taxable',30,90,'S','R',1,'verdana',11);
        $this->textbox('CGST',20,120,'S','R',1,'verdana',11);
        $this->textbox('SGST',20,140,'S','R',1,'verdana',11);
        $this->textbox('IGST',20,160,'S','R',1,'verdana',11);
        $this->textbox('UGST',20,180,'S','R',1,'verdana',11);
        $this->textbox('Total Tax',20,200,'S','R',1,'verdana',11);
        $this->textbox('TCS',20,220,'S','R',1,'verdana',11);
        $this->textbox('Net Amt',30,240,'S','R',1,'verdana',11);
        $this->textbox('Rate',20,270,'S','R',1,'verdana',11);
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
        if ($this->purchasercode =="")
            $cond ="1=1";
        else
            $cond ="purchasercode=".$this->purchasercode;

        if ($this->finishedgoodscode !="" and $this->finishedgoodscode !=0)
            $cond =$cond." and d.finishedgoodscode=".$this->finishedgoodscode;

        $query0 ="select purchasercode
        from saleinvoiceheader h, salecategory s,saleinvoicedetail d
        where h.salecategorycode=s.salecategorycode
        and h.goodscategorycode=".$this->goodscategorycode."
        and h.invoicedate>='".$this->fromdate."'
        and h.invoicedate<='".$this->todate."'
        and h.transactionnumber=d.transactionnumber(+)
        and nvl(d.serialnumber,0)<=1
        and {$cond} 
        group by purchasercode";
        $result0 = oci_parse($this->connection, $query0);
        $r0 = oci_execute($result0);
        while ($row0 = oci_fetch_array($result0,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $goodspurchaser1 = new goodspurchaser($this->connection);
            $goodspurchaser1->purchasercode = $row0['PURCHASERCODE'];
            $goodspurchaser1->goodscategorycode = $this->goodscategorycode;
            $goodspurchaser1->fetch();
            $this->textbox($goodspurchaser1->purchasercode.' '.$goodspurchaser1->purchasernameeng,70,10,'S','L',1,'verdana',10,'','','','B');
            $this->newrow();
            
            $cond1="1=1";
            if ($this->finishedgoodscode !="" and $this->finishedgoodscode !=0)
            $cond1 =$cond1." and d.finishedgoodscode=".$this->finishedgoodscode;

            $query ="select h.transactionnumber
            ,s.salecategorycode
            ,salecategorynameeng
            ,h.goodscategorycode
            ,purchasercode
            ,shippingpartycode
            from saleinvoiceheader h, salecategory s,saleinvoicedetail d
            where h.salecategorycode=s.salecategorycode
            and h.transactionnumber=d.transactionnumber
            and nvl(d.serialnumber,0)=1
            and h.goodscategorycode=".$this->goodscategorycode."
            and h.invoicedate>='".$this->fromdate."'
            and h.invoicedate<='".$this->todate."'
            and h.purchasercode = ".$row0['PURCHASERCODE']." 
            and {$cond1} 
            order by invoicenumber";
            $result = oci_parse($this->connection, $query);
            $r = oci_execute($result);
            while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                $saleinvoiceheader1 = new saleinvoiceheader($this->connection);
                $saleinvoiceheader1->transactionnumber = $row['TRANSACTIONNUMBER'];
                $saleinvoiceheader1->fetch();

                

                $shippingparty1 = new goodspurchaser($this->connection);
                $shippingparty1->purchasercode = $row['SHIPPINGPARTYCODE'];
                $shippingparty1->goodscategorycode = $row['GOODSCATEGORYCODE'];
                $shippingparty1->fetch();

                $this->textbox($saleinvoiceheader1->invoicenumberpresuf,35,10,'S','L',1,'verdana',8);
                $this->textbox($saleinvoiceheader1->invoicedate,25,45,'S','L',1,'verdana',9);
                $this->textbox($saleinvoiceheader1->salecategorynameeng,20,70,'S','L',1,'verdana',10);
                $this->textbox($shippingparty1->purchasercode.' '.$shippingparty1->purchasernameeng,70,160,'S','L',1,'verdana',8);
                $this->newrow(5);
                //$cond ="1=1";
                                
                

                $query1 ="select t.salecategorycode
                ,d.productionyearcode
                ,g.finishedgoodscode
                ,g.finishedgoodsnameeng
                ,d.salequantity
                ,d.amount as taxableamount
                ,d.cgstamount
                ,d.sgstamount
                ,d.igstamount
                ,d.ugstamount
                ,d.totaltaxamount
                ,d.tcsamount
                ,d.grossamount
                ,d.salerate
                from saleinvoiceheader t,saleinvoicedetail d,finishedgoods g
                where t.transactionnumber=d.transactionnumber 
                and t.transactionnumber=".$row['TRANSACTIONNUMBER']."
                and {$cond} 
                and d.finishedgoodscode=g.finishedgoodscode";
                $result1 = oci_parse($this->connection, $query1);
                $r1 = oci_execute($result1);
                while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    $this->textbox($row1['PRODUCTIONYEARCODE'],20,10,'S','L',1,'verdana',9);
                    $this->textbox($row1['FINISHEDGOODSNAMEENG'],50,30,'S','L',1,'verdana',9);
                    $this->textbox($row1['SALEQUANTITY'],20,70,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row1['TAXABLEAMOUNT']),30,90,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row1['CGSTAMOUNT']),20,120,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row1['SGSTAMOUNT']),20,140,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row1['IGSTAMOUNT']),20,160,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row1['UGSTAMOUNT']),20,180,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row1['TOTALTAXAMOUNT']),20,200,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row1['TCSAMOUNT']),20,220,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row1['GROSSAMOUNT']),30,240,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row1['SALERATE']),20,270,'S','R',1,'verdana',9);
                    $this->newrow(5);
                }
                $this->hline(10,300,$this->liney,'C');    
            }
            $query2 ="select t.*
                from
                (select sum(d.salequantity) as salequantity
                ,sum(d.amount) as taxableamount
                ,sum(d.cgstamount) as cgstamount
                ,sum(d.sgstamount) as sgstamount
                ,sum(d.igstamount) as igstamount
                ,sum(d.ugstamount) as ugstamount
                ,sum(d.totaltaxamount) as totaltaxamount
                ,sum(d.tcsamount) as tcsamount
                ,sum(d.grossamount) as grossamount
                ,sum(d.amount)/sum(d.salequantity) as avgrate
                from saleinvoiceheader t,saleinvoicedetail d,finishedgoods g
                where t.transactionnumber=d.transactionnumber 
                and t.invoicedate>='".$this->fromdate."'
                and t.invoicedate<='".$this->todate."' 
                and t.purchasercode = ".$row0['PURCHASERCODE']." 
                and d.finishedgoodscode=g.finishedgoodscode
                and t.goodscategorycode=".$this->goodscategorycode."
                ) t";
                $result2 = oci_parse($this->connection, $query2);
                $r2 = oci_execute($result2);
                if ($row2 = oci_fetch_array($result2,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    //$this->newrow(5);
                    $this->textbox('Total',50,30,'S','L',1,'verdana',12);
                    $this->textbox($row2['SALEQUANTITY'],20,70,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row2['TAXABLEAMOUNT']),30,90,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row2['CGSTAMOUNT']),20,120,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row2['SGSTAMOUNT']),20,140,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row2['IGSTAMOUNT']),20,160,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row2['UGSTAMOUNT']),20,180,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row2['TOTALTAXAMOUNT']),20,200,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row2['TCSAMOUNT']),20,220,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row2['GROSSAMOUNT']),30,240,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row2['AVGRATE']),20,270,'S','R',1,'verdana',9);
                    $this->newrow(10);
                }
                $this->hline(10,300,$this->liney,'C');    
        }
        if ($this->purchasercode =="")
        {
            $query9 ="select t.*
                from
                (select sum(d.salequantity) as salequantity
                ,sum(d.amount) as taxableamount
                ,sum(d.cgstamount) as cgstamount
                ,sum(d.sgstamount) as sgstamount
                ,sum(d.igstamount) as igstamount
                ,sum(d.ugstamount) as ugstamount
                ,sum(d.totaltaxamount) as totaltaxamount
                ,sum(d.tcsamount) as tcsamount
                ,sum(d.grossamount) as grossamount
                ,sum(d.grossamount)/sum(d.salequantity) as avgrate
                from saleinvoiceheader t,saleinvoicedetail d,finishedgoods g
                where t.transactionnumber=d.transactionnumber 
                and t.invoicedate>='".$this->fromdate."'
                and t.invoicedate<='".$this->todate."' 
                and {$cond} 
                and d.finishedgoodscode=g.finishedgoodscode
                and t.goodscategorycode=".$this->goodscategorycode."
                ) t";
                $result9 = oci_parse($this->connection, $query9);
                $r9 = oci_execute($result9);
                if ($row9 = oci_fetch_array($result9,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    //$this->newrow(5);
                    $this->textbox('Report Total',50,30,'S','L',1,'verdana',12);
                    $this->textbox($row9['SALEQUANTITY'],20,70,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row9['TAXABLEAMOUNT']),30,90,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row9['CGSTAMOUNT']),20,120,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row9['SGSTAMOUNT']),20,140,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row9['IGSTAMOUNT']),20,160,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row9['UGSTAMOUNT']),20,180,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row9['TOTALTAXAMOUNT']),20,200,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row9['TCSAMOUNT']),20,220,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row9['GROSSAMOUNT']),30,240,'S','R',1,'verdana',9);
                    $this->textbox($this->moneyFormatIndia($row9['AVGRATE']),20,270,'S','R',1,'verdana',9);
                    $this->newrow(10);
                }
                $this->hline(10,300,$this->liney,'C');    
        }
    
    }
}    
?>