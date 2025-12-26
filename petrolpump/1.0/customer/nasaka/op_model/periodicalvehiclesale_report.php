<?php
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../ip_model/customer_db_oracle.php");
class periodicalvehiclesale extends swappreport
{	
    public $connection;
    public $liney;
    public $maxlines;
    public $pdf;
    //
    public $customercode;
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
        $this->pdf->SetSubject('Periodical Vehiclewise Sale Detail Report');
        $this->pdf->SetKeywords('PRVSD_000.MR');
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
        $this->pdf->Output('PRVSD_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 15;
        $this->pdf->SetFont('siddhanta', '', 13, '', true);
        $frdt = $fromdate = DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y');
        $todt = $todate = DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y');
        $customer1 = new customer($this->connection);
        $customer1->fetch($this->customercode);
        $this->liney = $this->liney+2;
        $this->pdf->SetFont('siddhanta', '', 12, '', true);
        $this->pdf->multicell(0,15,'दिनांक '.$frdt.' ते '.$todt.' कालावधीतील वाहनवार उधार विक्री रिपोर्ट',0,'C',false,1,15,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+7;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->pdf->multicell(0,15,'वाहतूकदार कोड: '.$customer1->refcode.' वाहतूकदाराचे नाव: '.$customer1->customernameuni.' वाहन नंबर: '.$customer1->vehiclenumber,0,'L',false,1,15,$this->liney,true,0,false,true,10);
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->liney = $this->liney+7;
        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
    	$this->pdf->line(15,$this->liney,200,$this->liney);
        $this->liney = $this->liney+2;
        $this->pdf->multicell(35,10,'बिल नं',0,'L',false,1,15,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(30,10,'दिनांक',0,'L',false,1,50,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(20,10,'स्लीप नं',0,'L',false,1,80,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(25,10,'डिझेल',0,'R',false,1,100,$this->liney,true,0,false,true,10);
		$this->pdf->multicell(20,10,'दर',0,'R',false,1,125,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(30,10,'रक्कम',0,'R',false,1,145,$this->liney,true,0,false,true,10);
        
        $this->liney = $this->liney+7;
        $this->pdf->line(15,$this->liney,200,$this->liney);
        $this->liney = $this->liney+2;
    }

	function detail()
    {
        $detail_query = "select 
        h.invoicenumber
        ,h.invoicenumber_suffpref
        ,h.invoicedate
        ,d.wbslipnumber
        ,d.qty
        ,d.rate
        ,d.amount
        from saleheader h,saledetail d
        where h.transactionid= d.reftransactionid
        and h.customercode=".$this->customercode."
        and h.invoicedate>='".$this->fromdate."'
        and h.invoicedate<='".$this->todate."'
        order by h.invoicedate";
        $detail_result = oci_parse($this->connection, $detail_query);             
        $detail_r = oci_execute($detail_result);
        $item_qty =0;
        $item_amount =0;
        //shift
        while ($detail_row = oci_fetch_array($detail_result,OCI_ASSOC+OCI_RETURN_NULLS))
        {  
            $this->pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
            $this->pdf->multicell(35,10,$detail_row['INVOICENUMBER_SUFFPREF'],0,'L',false,1,15,$this->liney,true,0,false,true,10);
            $invdt = DateTime::createFromFormat('d-M-Y',$detail_row['INVOICEDATE'])->format('d/m/Y');
            $this->pdf->multicell(30,10,$invdt,0,'L',false,1,50,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(20,10,$detail_row['WBSLIPNUMBER'],0,'L',false,1,80,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(25,10,$detail_row['QTY'],0,'R',false,1,100,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(20,10,$detail_row['RATE'],0,'R',false,1,125,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(30,10,$this->moneyFormatIndia($detail_row['AMOUNT']),0,'R',false,1,145,$this->liney,true,0,false,true,10);
            $item_qty=$item_qty+$detail_row['QTY'];
            $item_amount=$item_amount+$detail_row['AMOUNT'];
            $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
            if ($this->isnewpage(7))
            {
                $this->newpage();
            }
            $this->liney = $this->liney+5;
            $this->pdf->line(15,$this->liney,200,$this->liney);
            $this->liney = $this->liney+2;
        }
        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
        $this->pdf->line(15,$this->liney-2,200,$this->liney-2);
        
        $this->pdf->multicell(20,10,'एकूण',0,'L',false,1,80,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(25,10,$item_qty,0,'R',false,1,100,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(30,10,$this->moneyFormatIndia($item_amount),0,'R',false,1,145,$this->liney,true,0,false,true,10);
        if ($this->isnewpage(5))
        {
            $this->newpage();
        }
        $this->liney = $this->liney+5;
        $this->pdf->line(15,$this->liney,200,$this->liney);
    }
}    
?>