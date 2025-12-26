<?php
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
class daywiseshiftwise extends swappreport
{	
    public $connection;
    public $liney;
    public $maxlines;
    public $pdf;
    //
    public $petrolpumpcode;
    public $pumpcode;
    public $shiftcode;
    public $cashcreditcode;
    public $customertypecode;
    public $fromdate;
    public $todate;
    

    public function __construct(&$connection,$maxlines)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
    	// create new PDF document
	    $this->pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Daywise Shiftwise Sale Report');
        $this->pdf->SetKeywords('DYSHSL_000.MR');
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));

        $title = str_pad(' ', 30).'श्री संत जनार्दन स्वामी नगर, ता.नाशिक, जि.नाशिक';
    	$this->pdf->SetHeaderData('', 0,str_pad(' ', 21).'नाशिक सहकारी साखर कारखाना लि.,' ,$title);
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
        $lg['w_page'] = 'पान - ';
        // set some language-dependent strings (optional)
	    $this->pdf->setLanguageArray($lg);
	}

	public function isnewpage($projected)
    {
        if ($this->liney+$projected>=$this->maxlines)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function newpage($force=false)
    {
        if ($force==false)
        {
            if ($this->liney >= $this->maxlines)
            {
                $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
                $this->pdf->line(15,$this->liney,300,$this->liney);
                $this->liney = 20;
                $this->pdf->AddPage();
                // set color for background
                $this->pdf->SetFillColor(0, 0, 0);
                // set color for text
                $this->pdf->SetTextColor(0, 0, 0);
                $this->pageheader();
            }
        }
        else
        {
            $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
            $this->pdf->line(15,$this->liney,300,$this->liney);
            $this->liney = 20;
            $this->pdf->AddPage();
            // set color for background
            $this->pdf->SetFillColor(0, 0, 0);
            // set color for text
            $this->pdf->SetTextColor(0, 0, 0);
            $this->pageheader();
        }
    }
    function endreport()
    {
        // reset pointer to the last page*/
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->Output('DYSHSL_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 15;
        $this->pdf->SetFont('siddhanta', '', 13, '', true);
        $dt = $fromdate = DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y');
        $this->pdf->multicell(0,15,'         '.$dt.' रोजीचा दैनिक विक्री रिपोर्ट',0,'C',false,1,0,$this->liney,true,0,false,true,10);
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->liney = $this->liney+7;
        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
    	$this->pdf->line(15,$this->liney,200,$this->liney);
        $this->liney = $this->liney+2;
        $this->pdf->multicell(20,10,'बिल नं',0,'L',false,1,15,$this->liney,true,0,false,true,10);
		$this->pdf->multicell(15,10,'कोड',0,'L',false,1,35,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(50,10,'वाहतूकदार',0,'L',false,1,50,$this->liney,true,0,false,true,10);
		$this->pdf->multicell(25,10,'वाहन नंबर',0,'L',false,1,115,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(20,10,'स्लीप नं',0,'L',false,1,100,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(20,10,'लिटर',0,'R',false,1,135,$this->liney,true,0,false,true,10);
		$this->pdf->multicell(20,10,'दर',0,'R',false,1,155,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(30,10,'रक्कम',0,'R',false,1,170,$this->liney,true,0,false,true,10);
        
        $this->liney = $this->liney+7;
        $this->pdf->line(15,$this->liney,200,$this->liney);
        $this->liney = $this->liney+3;
    }

	function detail()
    {
        if ($this->shiftcode == 0)
        {
            $shift_cond='';
        }
        else
        {
            $shift_cond=' and shiftcode='.$this->shiftcode;
        }
        if ($this->petrolpumpcode == 0)
        {
            $petrolpump_cond='';
        }
        else
        {
            $petrolpump_cond=' and d.petrolpumpcode='.$this->petrolpumpcode;
        }
        if ($this->pumpcode == 0)
        {
            $pump_cond='';
        }
        else
        {
            $pump_cond=' and pumpcode='.$this->pumpcode;
        }
        if ($this->cashcreditcode == 0)
        {
            $cashcredit_cond='';
        }
        elseif ($this->cashcreditcode == 1)
        {
            $cashcredit_cond=' and customertypecode in (248803647)';
        }
        elseif ($this->cashcreditcode == 2)
        {
            $cashcredit_cond=' and customertypecode not in (248803647)';
        }
        if ($this->customertypecode == 0)
        {
            $customertype_cond='';
        }
        else
        {
            $customertype_cond=' and customertypecode='.$this->customertypecode;
        }
        $shift_query = "select h.shiftcode,d.name_unicode 
        from saleheader h,nst_nasaka_db.namedetail d,pump d
        where h.shiftcode=d.namedetailid and h.pumpcode=d.pumpcode
        and h.invoicedate='".$this->fromdate."'
        ".$shift_cond."
        group by h.shiftcode,d.name_unicode";
        $shift_result = oci_parse($this->connection, $shift_query);             
        $shift_r = oci_execute($shift_result);
        //shift
        while ($shift_row = oci_fetch_array($shift_result,OCI_ASSOC+OCI_RETURN_NULLS))
        {  
            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            $this->pdf->multicell(50,10,''.$shift_row['NAME_UNICODE'],0,'L',false,1,15,$this->liney,true,0,false,true,10);
            if ($this->isnewpage(7))
            {
                $this->newpage();
            }
            $this->liney = $this->liney+7;
            if ($this->shiftcode == 0)
            {
                $shift_cond='';
            }
            else
            {
                $shift_cond=' and shiftcode='.$this->shiftcode;
            }
            
            $pump_query = "select h.pumpcode,d.pumpname_uni,n.name_unicode
            from saleheader h,pump d,nst_nasaka_db.namedetail n
            where h.pumpcode=d.pumpcode
            and d.petrolpumpcode=n.namedetailid
            and h.invoicedate='".$this->fromdate."'
            and h.shiftcode=".$shift_row['SHIFTCODE']."
            ".$pump_cond."
            ".$petrolpump_cond."
            group by h.pumpcode,d.pumpname_uni,n.name_unicode";
            $pump_result = oci_parse($this->connection, $pump_query);             
            $pump_r = oci_execute($pump_result);
            //pump
            while ($pump_row = oci_fetch_array($pump_result,OCI_ASSOC+OCI_RETURN_NULLS))
            {  
                $this->pdf->SetFont('siddhanta', '', 11, '', true);
                $this->pdf->multicell(100,10,$pump_row['NAME_UNICODE'].' - '.$pump_row['PUMPNAME_UNI'],0,'L',false,1,15,$this->liney,true,0,false,true,10);
                if ($this->isnewpage(7))
                {
                    $this->newpage();
                }
                $this->liney = $this->liney+7;

                    $customertype_query = "select c.customertypecode,d.name_unicode 
                    from saleheader h,nst_nasaka_db.namedetail d,customer c
                    where c.customertypecode=d.namedetailid
                    and h.customercode=c.customercode
                    and h.invoicedate='".$this->fromdate."'
                    and h.pumpcode=".$pump_row['PUMPCODE']."
                    and h.shiftcode=".$shift_row['SHIFTCODE']."
                    ".$customertype_cond."
                    ".$cashcredit_cond."
                    group by c.customertypecode,d.name_unicode";
                    $customertype_result = oci_parse($this->connection, $customertype_query);             
                    $customertype_r = oci_execute($customertype_result);
                    //customertype
                    while ($customertype_row = oci_fetch_array($customertype_result,OCI_ASSOC+OCI_RETURN_NULLS))
                    {  
                        $this->pdf->SetFont('siddhanta', '', 11, '', true);
                        /*$this->pdf->multicell(50,10,''.$customertype_row['NAME_UNICODE'],0,'L',false,1,15,$this->liney,true,0,false,true,10);
                        $this->liney = $this->liney+7;*/
                        $item_query = "select i.itemcode,i.itemnameuni 
                        from saleheader h,saledetail d,item i,customer c
                        where h.transactionid= d.reftransactionid
                        and d.itemcode=i.itemcode
                        and h.customercode=c.customercode
                        and h.invoicedate='".$this->fromdate."'
                        and h.pumpcode=".$pump_row['PUMPCODE']."
                        and h.shiftcode=".$shift_row['SHIFTCODE']."
                        and c.customertypecode=".$customertype_row['CUSTOMERTYPECODE']."
                        group by i.itemcode,i.itemnameuni";
                        $item_result = oci_parse($this->connection, $item_query);             
                        $item_r = oci_execute($item_result);
                        //shift
                        while ($item_row = oci_fetch_array($item_result,OCI_ASSOC+OCI_RETURN_NULLS))
                        {  
                            $this->pdf->SetFont('siddhanta', '', 11, '', true);
                            $this->pdf->multicell(100,10,$customertype_row['NAME_UNICODE'].' - '.$item_row['ITEMNAMEUNI'],0,'L',false,1,15,$this->liney,true,0,false,true,10);
                            if ($this->isnewpage(7))
                            {
                                $this->newpage();
                            }
                            $this->liney = $this->liney+7;
                            $detail_query = "select 
                            h.invoicenumber
                            ,c.customercode
                            ,c.customernameuni
                            ,d.wbslipnumber
                            ,c.vehiclenumber
                            ,d.qty
                            ,d.rate
                            ,d.amount
                            from saleheader h,saledetail d,item i,customer c
                            where h.transactionid= d.reftransactionid
                            and d.itemcode=i.itemcode
                            and h.customercode=c.customercode
                            and h.invoicedate='".$this->fromdate."'
                            and h.pumpcode=".$pump_row['PUMPCODE']."
                            and h.shiftcode=".$shift_row['SHIFTCODE']."
                            and c.customertypecode=".$customertype_row['CUSTOMERTYPECODE']."
                            and i.itemcode=".$item_row['ITEMCODE'].
                            " order by h.invoicenumber";
                            $detail_result = oci_parse($this->connection, $detail_query);             
                            $detail_r = oci_execute($detail_result);
                            $item_qty =0;
                            $item_amount =0;
                            //shift
                            while ($detail_row = oci_fetch_array($detail_result,OCI_ASSOC+OCI_RETURN_NULLS))
                            {  
                                $this->pdf->SetFont('siddhanta', '', 10, '', true);
                                $this->pdf->multicell(20,10,$detail_row['INVOICENUMBER'],0,'L',false,1,15,$this->liney,true,0,false,true,10);
                                $this->pdf->multicell(15,10,$detail_row['CUSTOMERCODE'],0,'L',false,1,35,$this->liney,true,0,false,true,10);
                                $this->pdf->multicell(50,10,$detail_row['CUSTOMERNAMEUNI'],0,'L',false,1,50,$this->liney,true,0,false,true,10);
                                $this->pdf->SetFont('times', '', 10, '', true);
                                $this->pdf->multicell(30,10,strtoupper($detail_row['VEHICLENUMBER']),0,'L',false,1,115,$this->liney,true,0,false,true,10);
                                $this->pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
                                $this->pdf->multicell(15,10,$detail_row['WBSLIPNUMBER'],0,'L',false,1,100,$this->liney,true,0,false,true,10);
                                $this->pdf->multicell(20,10,$detail_row['QTY'],0,'R',false,1,135,$this->liney,true,0,false,true,10);
                                $this->pdf->multicell(20,10,$detail_row['RATE'],0,'R',false,1,155,$this->liney,true,0,false,true,10);
                                $this->pdf->multicell(30,10,$this->moneyFormatIndia($detail_row['AMOUNT']),0,'R',false,1,170,$this->liney,true,0,false,true,10);
                                $item_qty=$item_qty+$detail_row['QTY'];
                                $item_amount=$item_amount+$detail_row['AMOUNT'];
                                $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
                                $this->pdf->line(15,$this->liney,200,$this->liney);
                                if ($this->isnewpage(7))
                                {
                                    $this->newpage();
                                }
                                $this->liney = $this->liney+7;
                            }
                            $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
                            $this->pdf->line(15,$this->liney,200,$this->liney);
                            
                            $this->pdf->multicell(100,10,$customertype_row['NAME_UNICODE'].' - '.$item_row['ITEMNAMEUNI'].' एकूण',0,'R',false,1,15,$this->liney,true,0,false,true,10);
                            $this->pdf->multicell(20,10,$item_qty,0,'R',false,1,135,$this->liney,true,0,false,true,10);
                            $this->pdf->multicell(30,10,$this->moneyFormatIndia($item_amount),0,'R',false,1,170,$this->liney,true,0,false,true,10);
                            if ($this->isnewpage(5))
                            {
                                $this->newpage();
                            }
                            $this->liney = $this->liney+5;
                            $this->pdf->line(15,$this->liney,200,$this->liney);
                        }
                    }
                }
            }
        }
    }    
?>