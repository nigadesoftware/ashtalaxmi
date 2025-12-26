<?php
include_once("../swappbase/reportbase.php");
include_once("../swappbase/mypdf_a4_p.php");
class periodicalcreditsalesum extends swappreport
{	
    public $connection;
    public $liney;
    public $maxlines;
    public $pdf;
    //
    public $fromdate;
    public $todate;
    public $vehiclecateogycode;
    public function __construct(&$connection,$maxlines)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->vehiclecateogycode=1;
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
        $this->pdf->SetSubject('Periodical Credit Sale Report');
        $this->pdf->SetKeywords('PRCRSLSM_000.MR');
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
            $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' =>0, 'color' => array(0,0,0)));
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
        $this->pdf->Output('PRCRSLSM_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 20;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $frdt = $fromdate = DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y');
        $todt = $todate = DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y');
        if ($this->vehiclecateogycode==1)
            $vehcategory='ट्रक/ट्रॅक्टर';
        elseif ($this->vehiclecateogycode==4)
            $vehcategory='जुगाड';    
        $this->pdf->multicell(0,15,'दिनांक '.$frdt.' ते '.$todt.' कालावधीतील '.$vehcategory.' ऊस वाहतूकदार उधार विक्री रिपोर्ट समरी',0,'C',false,1,0,$this->liney,true,0,false,true,10);
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->liney = $this->liney+7;
    	$this->pdf->line(15,$this->liney,200,$this->liney);
        $this->liney = $this->liney+2;
        $this->pdf->multicell(15,10,'अ.नं.',0,'L',false,1,15,$this->liney,true,0,false,true,10);
		$this->pdf->multicell(45,10,'ग्राहक',0,'L',false,1,25,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(30,10,'वाहन नंबर',0,'L',false,1,70,$this->liney,true,0,false,true,10);
		$this->pdf->multicell(20,10,'डिझेल',0,'R',false,1,100,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(25,10,'रक्कम',0,'R',false,1,120,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(20,10,'आ.रक्कम',0,'R',false,1,150,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(25,10,'सू.रक्कम',0,'R',false,1,170,$this->liney,true,0,false,true,10);
        
        $this->liney = $this->liney+7;
        $this->pdf->line(15,$this->liney,200,$this->liney);
        $this->liney = $this->liney+3;
    }

	function detail()
    {
        /* $query = "select h.customercode
        ,c.customernameuni
        ,c.vehiclenumber
        ,c.refcode
        ,nvl(sum(d.qty),0) as qty
        ,nvl(round(sum(d.amount),0),0) as amount
        from saleheader h,saledetail d,customer c,nst_nasaka_agriculture.vehicle
        where h.transactionid=d.reftransactionid 
        and h.customercode=c.customercode 
        and h.transactioncategoryid in (2)
        and c.customertypecode in (248767479,248804823)
        and h.invoicedate>='".$this->fromdate."'
        and h.invoicedate<='".$this->todate."' 
        group by h.customercode
        ,c.customernameuni
        ,c.vehiclenumber
        ,c.refcode
        order by c.customernameuni"; */
        $query1 = "select t.vehiclecategorycode 
        from nst_nasaka_agriculture.vehiclecategory t where vehiclecategorycode in (1,4) order by vehiclecategorycode";
        $result1 = oci_parse($this->connection, $query1);             
        $r1 = oci_execute($result1);
        $cond='';
        while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
        { 
            if ($row1['VEHICLECATEGORYCODE']==1 or $row1['VEHICLECATEGORYCODE']==2)
            {
                $cond =' and v.vehiclecategorycode in (1,2)';
                $this->vehiclecateogycode=1;
            }
            else if ($row1['VEHICLECATEGORYCODE']==4)
            {
                $cond =' and v.vehiclecategorycode in (4)';
                $this->vehiclecateogycode=4;
            }
            $query = "select t.customercode
            ,t.customernameuni
            ,t.vehiclenumber
            ,t.refcode,t.vehiclecode,sum(totalqty) as totalqty ,sum(amount) grossamount
            ,sum(case when v.vehiclecategorycode in (4) then  amount else 0*totalqty end) chargedamount,sum(amount)-sum(case when v.vehiclecategorycode in (4) then  amount else 0*totalqty end) concessionamount
            from (
            select customercode,customernameuni,vehiclenumber,refcode,trunc(vehicleid/100000) seasoncode,mod(vehicleid,100000) as vehiclecode,amount,totalqty
            from (
            select c.customercode,c.customernameuni,c.vehiclenumber,c.refcode,to_number(c.refcode) vehicleid
            , nvl(round(sum(d.amount),0),2) amount, sum(d.qty) as totalqty
            from nst_nasaka_petrolpump.saleheader h,nst_nasaka_petrolpump.saledetail d
            ,nst_nasaka_petrolpump.customer c
            where h.transactionid=d.reftransactionid
            and h.customercode=c.customercode
            and h.transactioncategoryid in (2)
            and c.customertypecode in (248767479,248804823)
            and h.invoicedate>='".$this->fromdate."'
            and h.invoicedate<='".$this->todate."'
            group by c.customercode,customernameuni,vehiclenumber,refcode,to_number(c.refcode))) t,nst_nasaka_agriculture.vehicle v,nst_nasaka_agriculture.subcontractor s,nst_nasaka_agriculture.contractorcontract r
            where t.seasoncode=v.seasoncode and t.vehiclecode=v.vehiclecode
            and s.seasoncode=r.seasoncode and s.contractcode=r.contractcode
            and s.seasoncode=v.seasoncode and s.subcontractorcode=v.subcontractorcode
             {$cond}
            group by customercode
            ,t.customernameuni
            ,t.vehiclenumber
            ,t.refcode,t.vehiclecode
            order by customernameuni";

            $result = oci_parse($this->connection, $query);             
            $r = oci_execute($result);
            //shift
            $qty_sum = 0;
            $amount_sum = 0;
            $concessionamount_sum = 0;
            $charged_sum = 0;
            $srno = 1;
            while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
            {  
                $this->pdf->SetFont('siddhanta', '', 10, '', true);
                $this->pdf->multicell(15,10,$srno,0,'L',false,1,15,$this->liney,true,0,false,true,10);
                //$this->pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
                //$this->pdf->multicell(35,10,$row['REFCODE'],0,'L',false,1,20,$this->liney,true,0,false,true,10);
                $this->pdf->SetFont('siddhanta', '', 10, '', true);
                $this->pdf->multicell(45,10,$row['CUSTOMERNAMEUNI'],0,'L',false,1,25,$this->liney,true,0,false,true,10);
                $this->pdf->SetFont('SakalMarathiNormal922', '', 9, '', true);
                $this->pdf->multicell(30,10,$row['VEHICLENUMBER'],0,'L',false,1,70,$this->liney,true,0,false,true,10);
                $this->pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
                $this->pdf->multicell(20,10,$this->moneyFormatIndia($row['TOTALQTY']),0,'R',false,1,100,$this->liney,true,0,false,true,10);
                $this->pdf->multicell(25,10,$this->moneyFormatIndia($row['GROSSAMOUNT']),0,'R',false,1,120,$this->liney,true,0,false,true,10);
                $this->pdf->multicell(25,10,$this->moneyFormatIndia($row['CHARGEDAMOUNT']),0,'R',false,1,145,$this->liney,true,0,false,true,10);
                $this->pdf->multicell(25,10,$this->moneyFormatIndia($row['CONCESSIONAMOUNT']),0,'R',false,1,170,$this->liney,true,0,false,true,10);
                $qty_sum=$qty_sum+$row['TOTALQTY'];
                $amount_sum=$amount_sum+$row['GROSSAMOUNT'];
                $charged_sum=$charged_sum+$row['CHARGEDAMOUNT'];
                $concessionamount_sum=$concessionamount_sum+$row['CONCESSIONAMOUNT'];
                if ($this->isnewpage(5))
                {
                    $this->newpage();
                }
                $srno++;
                $this->liney = $this->liney+5;
                $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
                $this->pdf->line(15,$this->liney,200,$this->liney);
            }
            if ($this->isnewpage(5))
            {
                $this->newpage();
            }
            $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
            $this->pdf->line(15,$this->liney,200,$this->liney);
            $this->pdf->multicell(35,10,'एकूण',0,'L',false,1,35,$this->liney,true,0,false,true,10);
            $this->pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
            $this->pdf->multicell(25,10,$this->moneyFormatIndia($qty_sum),0,'R',false,1,90,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(30,10,$this->moneyFormatIndia($amount_sum),0,'R',false,1,110,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(30,10,$this->moneyFormatIndia($charged_sum),0,'R',false,1,140,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(30,10,$this->moneyFormatIndia($concessionamount_sum),0,'R',false,1,170,$this->liney,true,0,false,true,10);

            $this->liney = $this->liney+5;
            $this->pdf->line(15,$this->liney,200,$this->liney);
            $this->newpage(true);
        }
    }
}    
?>