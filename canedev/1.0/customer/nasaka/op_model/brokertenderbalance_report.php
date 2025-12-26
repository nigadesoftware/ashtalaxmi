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
class brokertenderbalance extends swappreport
{	
    public $goodscategorycode;
    public $brokercode;
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
        $this->pdf->SetSubject('Brokerwise Tenderwise Balance');
        $this->pdf->SetKeywords('BRTDBL_000.EN');
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
        $this->pdf->Output('SLRG_000.pdf', 'I');
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
        $this->newrow(15);
        $this->textbox('Brokerwise Tenderwise Balance',250,10,'S','C',1,'verdana',13);
        $this->newrow(10);
        $this->hline(10,300,$this->liney+6,'C');
        $this->newrow(7);
        $this->textbox('Tender No.',20,10,'S','L',1,'verdana',10);
        $this->textbox('Tender Dt',50,30,'S','L',1,'verdana',10);
        $this->textbox('Grade',20,70,'S','R',1,'verdana',10);
        $this->textbox('Prod Season',20,70,'S','R',1,'verdana',10);
        $this->textbox('Lifting Dt',25,120,'S','R',1,'verdana',10);
        $this->textbox('Order Qty',30,90,'S','R',1,'verdana',10);
        $this->textbox('Sale Qty',25,170,'S','R',1,'verdana',10);
        $this->textbox('Balance Qty',20,195,'S','R',1,'verdana',10);
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
        $query ="select brokercode,p.purchasernameeng
        from brokerwiseliftedunlifted b,goodspurchaser p
        where b.brokercode=p.purchasercode and balancequantity>0
        group by brokercode,p.purchasernameeng";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->textbox($row['PURCHASERNAMEENG'],50,10,'S','L',1,'verdana',12);
            $this->newrow();
            $query1 ="select s.salecategorynameeng,f.finishedgoodsnameeng,t.*
            from
            (select t.salecategorycode,d.productionyearcode,g.finishedgoodscode,sum(d.salequantity) as salequantity
            ,sum(d.salequantity*100/g.conversionfactor) as bags
            ,sum(d.amount) as taxableamount
            ,sum(d.cgstamount) as cgstamount
            ,sum(d.sgstamount) as sgstamount
            ,sum(d.igstamount) as igstamount
            ,sum(d.ugstamount) as ugstamount
            ,sum(d.totaltaxamount) as totaltaxamount
            ,sum(d.grossamount) as grossamount
            ,sum(d.grossamount)/sum(d.salequantity) as avgrate
            from saleinvoiceheader t,saleinvoicedetail d,finishedgoods g
            where t.transactionnumber=d.transactionnumber 
            and t.invoicedate>='".$this->fromdate."'
            and t.invoicedate<='".$this->todate."'
            and t.salecategorycode=".$row['SALECATEGORYCODE']."
            and d.finishedgoodscode=g.finishedgoodscode
            group by t.salecategorycode,d.productionyearcode,g.finishedgoodscode) t, 
            finishedgoods f,salecategory s
            where t.finishedgoodscode=f.finishedgoodscode 
            and t.salecategorycode=s.salecategorycode";
            $result1 = oci_parse($this->connection, $query1);
            $r1 = oci_execute($result1);
            while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                $this->textbox($row1['PRODUCTIONYEARCODE'],20,10,'S','L',1,'verdana',9);
                $this->textbox($row1['FINISHEDGOODSNAMEENG'],50,30,'S','L',1,'verdana',9);
                $this->textbox($row1['BAGS'],20,70,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row1['TAXABLEAMOUNT']),30,90,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row1['CGSTAMOUNT']),25,120,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row1['SGSTAMOUNT']),25,145,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row1['IGSTAMOUNT']),25,170,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row1['UGSTAMOUNT']),20,195,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row1['TOTALTAXAMOUNT']),20,215,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row1['GROSSAMOUNT']),30,235,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row1['AVGRATE']),20,265,'S','R',1,'verdana',9);
                $this->newrow();
            }
            $this->hline(10,300,$this->liney,'C');    
        }
        $query2 ="select t.*
            from
            (select sum(d.salequantity) as salequantity
            ,sum(d.salequantity*100/g.conversionfactor) as bags
            ,sum(d.amount) as taxableamount
            ,sum(d.cgstamount) as cgstamount
            ,sum(d.sgstamount) as sgstamount
            ,sum(d.igstamount) as igstamount
            ,sum(d.ugstamount) as ugstamount
            ,sum(d.totaltaxamount) as totaltaxamount
            ,sum(d.grossamount) as grossamount
            ,sum(d.grossamount)/sum(d.salequantity) as avgrate
            from saleinvoiceheader t,saleinvoicedetail d,finishedgoods g
            where t.transactionnumber=d.transactionnumber 
            and t.invoicedate>='".$this->fromdate."'
            and t.invoicedate<='".$this->todate."'
            and d.finishedgoodscode=g.finishedgoodscode
            ) t";
            $result2 = oci_parse($this->connection, $query2);
            $r2 = oci_execute($result2);
            if ($row2 = oci_fetch_array($result2,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                $this->newrow();
                $this->textbox('Total',50,30,'S','L',1,'verdana',12);
                $this->textbox($row2['BAGS'],20,70,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row2['TAXABLEAMOUNT']),30,90,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row2['CGSTAMOUNT']),25,120,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row2['SGSTAMOUNT']),25,145,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row2['IGSTAMOUNT']),25,170,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row2['UGSTAMOUNT']),20,195,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row2['TOTALTAXAMOUNT']),20,215,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row2['GROSSAMOUNT']),30,235,'S','R',1,'verdana',9);
                $this->textbox($this->moneyFormatIndia($row2['AVGRATE']),20,265,'S','R',1,'verdana',9);
                $this->newrow();
            }
            $this->hline(10,300,$this->liney,'C');    
    }
}    
?>
