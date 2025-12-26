<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class purchasersaledetail extends swappreport
{
    public $fromdate;
    public $todate;
    public $purchasercode;
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
        $this->pdf->SetSubject('Shift');
        $this->pdf->SetKeywords('SHIFT_000.MR');
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
        $lg['a_meta_language'] = 'mr';
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
        $this->pdf->Output('SHIFT_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 15;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->textbox('Purchaserwise periodical sale details',175,10,'S','C',1,'verdana',13);
        $this->newrow(7);
        $this->textbox('From: '.$this->fromdate.' To:'.$this->todate,175,10,'S','C',1,'verdana',12);
        $this->newrow(7);
        $this->hline(10,190,$this->liney,'C');
        
        $this->setfieldwidth(20,10);
        $this->vline($this->liney,$this->liney+5,$this->x);
        $this->textbox('Inv.No. ',$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
        $this->vline($this->liney,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Inv.Date',$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
        $this->vline($this->liney,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('Qty(qtls)',$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');
        $this->vline($this->liney,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Season',$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');
        $this->vline($this->liney,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(30); 
        $this->textbox('Basic',$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');
        $this->vline($this->liney,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox('GST',$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');
        $this->vline($this->liney,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox('Total',$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');
        $this->vline($this->liney,$this->liney+5,$this->x+$this->w);

        $this->newrow();
        $this->hline(10,190,$this->liney-2,'C');

    }
   
    function drawlines($limit)
    {
    }

    function pagefooter($islastpage=false)
    {
        //$this->drawlines($this->liney-31);
    }

    function detail()
    { 
        $cond = " and h.invoicedate>='".$this->fromdate."'
        and h.invoicedate<='".$this->todate."'";
        if ($this->purchasercode !='')
            $cond = $cond . " and h.purchasercode=".$this->purchasercode;
        $group_query_1 ="select purchasercode,purchasernameeng
        ,sum(salequantity) as salequantity
        ,sum(taxableamount) as taxableamount
        ,sum(totaltaxamount) as totaltaxamount
        ,sum(grossamount) as grossamount
        from (
        select p.purchasercode,p.purchasernameeng
        ,(select nvl(sum(d.salequantity),0) from saleinvoicedetail d 
        where d.transactionnumber=h.transactionnumber) as salequantity
        ,h.taxableamount,h.totaltaxamount,h.grossamount
        from saleinvoiceheader h,goodspurchaser p
        where h.purchasercode=p.purchasercode 
        {$cond}
        )
        group by purchasercode,purchasernameeng";
    
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,oci_assoc+OCI_RETURN_NULLS))
        {
            $this->setfieldwidth(20,10);
            //$this->vline($this->liney-2,$this->liney+5,10);
            $this->textbox($group_row_1['PURCHASERCODE'],$this->w,$this->x,'S','L',1,'verdana',11);
            //$this->vline($this->liney-2,$this->liney+5,$this->x);
            $this->setfieldwidth(170);
            $this->textbox($group_row_1['PURCHASERNAMEENG'],$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
            //$this->vline($this->liney-2,$this->liney+5,$this->x);
            if ($this->isnewpage(10))
            {
                $this->newpage(True);
            }
            else
            {
                $this->newrow();
                $this->hline(10,190,$this->liney-2,'C'); 
            }
            $query ="select p.purchasercode,p.purchasernameeng,h.invoicenumber,h.invoicedate
            ,(select nvl(sum(d.salequantity),0) from saleinvoicedetail d where d.transactionnumber=h.transactionnumber) as salequantity
            ,(select min(d.productionyearcode) from saleinvoicedetail d where d.transactionnumber=h.transactionnumber) as productionyearcode
            ,h.taxableamount,h.totaltaxamount,h.grossamount
            from saleinvoiceheader h,goodspurchaser p
            where h.purchasercode=p.purchasercode
            and h.purchasercode=".$group_row_1['PURCHASERCODE']."
            and h.invoicedate>='".$this->fromdate."'
            and h.invoicedate<='".$this->todate."'
            order by p.purchasernameeng,h.invoicenumber";
        
            $result = oci_parse($this->connection, $query);
            $r = oci_execute($result);
            $lastpurchasercode=0;
            while ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
            {
                $this->setfieldwidth(20,10);
                $this->vline($this->liney-2,$this->liney+5,$this->x);
                $this->textbox($row['INVOICENUMBER'],$this->w,$this->x,'S','L',1,'verdana',10);
                $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
                $dt = DateTime::createFromFormat('d-M-y',$row['INVOICEDATE'])->format('d/m/Y');

                $this->setfieldwidth(25);
                $this->textbox($dt,$this->w,$this->x,'S','L',1,'verdana',10);
                $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
                
                $this->setfieldwidth(20);
                $this->textbox($row['SALEQUANTITY'],$this->w,$this->x,'S','R',1,'verdana',10);
                $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
                
                $this->setfieldwidth(25);
                $this->textbox($row['PRODUCTIONYEARCODE'],$this->w,$this->x,'S','R',1,'verdana',10);
                $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
                
                $this->setfieldwidth(30); 
                $this->textbox($this->numformat($row['TAXABLEAMOUNT'],2),$this->w,$this->x,'S','R',1,'verdana',10);
                $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
                
                $this->setfieldwidth(30);
                $this->textbox($this->numformat($row['TOTALTAXAMOUNT'],2),$this->w,$this->x,'S','R',1,'verdana',10);
                $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
                
                $this->setfieldwidth(30);
                $this->textbox($this->numformat($row['GROSSAMOUNT'],2),$this->w,$this->x,'S','R',1,'verdana',10);
                $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
                if ($this->isnewpage(10))
                {
                    $this->hline(10,190,$this->liney+5,'C'); 
                    $this->newpage(True);
                }
                else
                {
                    $this->newrow();
                    $this->hline(10,190,$this->liney-2,'C'); 
                }
            }
            $this->setfieldwidth(20,10);
            $this->vline($this->liney-2,$this->liney+5,$this->x);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->setfieldwidth(25);
            $this->textbox('Total',$this->w,$this->x,'S','R',1,'verdana',10,'','','','B');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(20);
            $this->textbox($group_row_1['SALEQUANTITY'],$this->w,$this->x,'S','R',1,'verdana',10,'','','','B');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(25);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(30); 
            $this->textbox($this->numformat($group_row_1['TAXABLEAMOUNT'],2),$this->w,$this->x,'S','R',1,'verdana',10,'','','','B');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(30);
            $this->textbox($this->numformat($group_row_1['TOTALTAXAMOUNT'],2),$this->w,$this->x,'S','R',1,'verdana',10,'','','','B');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(30);
            $this->textbox($this->numformat($group_row_1['GROSSAMOUNT'],2),$this->w,$this->x,'S','R',1,'verdana',10,'','','','B');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            if ($this->isnewpage(10))
            {
                $this->hline(10,190,$this->liney+5,'C'); 
                $this->newpage(True);
            }
            else
            {
                $this->newrow();
                $this->hline(10,190,$this->liney-2,'C'); 
            }
        }
    }
}    
?>