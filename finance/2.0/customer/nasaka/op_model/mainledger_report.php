<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_legal_l.php");
    include_once("../info/routine.php");
    include_once("../ip_model/accounthead_db_oracle.php");
    include_once("../ip_model/voucherheader_db_oracle.php");
  
class mainledger extends swappreport
{	
    public $connection;
    public $liney;
    public $maxlines;
    public $pdf;
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
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Main Ledger');
        $this->pdf->SetKeywords('MNLED_000.MR');
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));

        $title = str_pad(' ', 30).'श्री संत जनार्दन स्वामी नगर, ता.नाशिक, जि.नाशिक';
    	$this->pdf->SetHeaderData('', 0,str_pad(' ', 21).'नाशिक स.सा.का.लि.,' ,$title);
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
                //$this->pdf->line(15,$this->liney,80,$this->liney);
                $this->liney = 20;
                //$resolution= array(80, 100);
                if ($this->currentpage==$this->totalpages)
                {
                    $this->drawlines(231);
                    $this->pdf->addpage('L',$resolution);
                    $this->totalpages = $this->pdf->getNumPages();
                    $this->currentpage = $this->totalpages;
                }
                else
                {
                    $this->currentpage++;
                    $this->pdf->setpage($this->currentpage);
                    $this->liney = 38;
                }
                
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
            //$this->pdf->line(15,$this->liney,100,$this->liney);
            $this->liney = 20;
            //$resolution= array(80, 100);
            if ($this->currentpage==$this->totalpages)
            {
                $this->drawlines(231);
                $this->pdf->addpage();
                $this->totalpages = $this->pdf->getNumPages();
                $this->currentpage = $this->totalpages;
            }
            else
            {
                $this->currentpage++;
                $this->pdf->setpage($this->currentpage);
                $this->liney = 38;
            }
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
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output('TRBLDT_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 15;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->pdf->SetFont('siddhanta', '', 18, '', true);
        $this->pdf->multicell(60,10,'मेन लेजर',0,'R',false,1,50,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+7;
        $this->pdf->SetFont('siddhanta', '', 13, '', true);
        $accounthead1 = new accounthead($this->connection);
        $accounthead1->accountcode = $this->accountcode;
        $accounthead1->fetch();
        $this->pdf->Cell(170, 0, $accounthead1->accountnameuni, 0, $this->liney, 'C', 0, '', 0, false, 'C', 'C');
        //$this->pdf->multicell(240,10,$accounthead1->accountnameuni,0,'C',false,1,10,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+7;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $frdt = DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y');
        $todt = DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y');
        //$this->pdf->Cell(170, 0, 'दिनांक '.$frdt.' पासून '.' दिनांक '.$todt.' पर्यंत', 0, $this->liney, 'C', 0, '', 0, false, 'C', 'C');
        $this->pdf->multicell(100,10,'दिनांक '.$frdt.' पासून '.' दिनांक '.$todt.' पर्यंत',0,'R',false,1,50,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+7;
        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
        $this->pdf->line(10,$this->liney,189,$this->liney);
        $this->liney = $this->liney+2;
        $this->pdf->multicell(25,10,'दिनांक',0,'L',false,1,10,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(40,10,'आरंभीची शिल्लक',0,'R',false,1,35,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(35,10,'नावे',0,'R',false,1,74,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(35,10,'जमा',0,'R',false,1,109,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(35,10,'अखेरची शिल्लक',0,'R',false,1,153,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+5;
        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
        $this->pdf->line(10,$this->liney,189,$this->liney);
    }

function drawlines($limit)
{
    $liney = $this->liney;
    $this->liney = 48;
    $this->pdf->line(10,$this->liney-12,10,$this->liney+$limit);
    $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
    $this->pdf->line(35,$this->liney-12,35,$this->liney+$limit);
    $this->pdf->line(74,$this->liney-12,74,$this->liney+$limit);
    $this->pdf->line(110,$this->liney-12,110,$this->liney+$limit);
    $this->pdf->line(145,$this->liney-12,145,$this->liney+$limit);
    $this->pdf->line(189,$this->liney-12,189,$this->liney+$limit);
    $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
    $this->pdf->line(10,$this->liney+$limit,189,$this->liney+$limit);
    $this->liney = $liney;
}

function pagefooter($islastpage = false)
    {
        $this->drawlines($this->liney-41);
        $this->liney = $this->liney+15; 
        $this->pdf->SetFont('siddhanta', '', 12, '', true); 
        $this->pdf->multicell(300,10,'         तयार करणार          तपासणार          चिफ अकौंटंट         जनरल मॅनेजर ',0,'L',false,1,5,$this->liney,true,0,false,true,10);
    }


	function detail()
    {
        $query = "select * from (select voucherdate,abs(openingbalance) as openingbalance,abs(closingbalance) as closingbalance, 
        case when openingbalance<0 then 'Cr' else 'Dr' end openingbalancetype
        ,credit
        ,debit
        ,case when closingbalance<0 then 'Cr' else 'Dr' end closingbalancetype
        from (
        select t.voucherdate,
        accountopeningbalance(".$this->yearcode.",".$this->accountcode.",t.voucherdate) as openingbalance,
        credit,
        debit,
        accountclosingbalance(".$this->yearcode.",".$this->accountcode.",t.voucherdate) as closingbalance
        from (select t.voucherdate,sum(d.credit) as credit,sum(d.debit) as debit
        from voucherheader t,voucherdetail d
        where t.transactionnumber=d.transactionnumber 
        and d.accountcode=".$this->accountcode."
        and voucherdate>='".$this->fromdate."'
        and voucherdate<='".$this->todate."'
        and t.approvalstatus=9
        group by t.voucherdate
        order by t.voucherdate) t
        ))";
        //        and t.approvalstatus=9
            $result = oci_parse($this->connection, $query);
            $r = oci_execute($result);
            $credit=0; 
            $debit=0; 
            while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
                $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
                $dt = DateTime::createFromFormat('d-M-y',$row['VOUCHERDATE'])->format('d/m/Y');
                $this->pdf->multicell(25,10,$dt,0,'R',false,1,10,$this->liney,true,0,false,true,10);
                $this->pdf->multicell(35,10,$this->moneyFormatIndia($row['OPENINGBALANCE']),0,'R',false,1,35,$this->liney,true,0,false,true,10);
                $this->pdf->SetFont('SakalMarathiNormal922', '', 9, '', true);
                $this->pdf->multicell(7,10,$row['OPENINGBALANCETYPE'],0,'R',false,1,67,$this->liney,true,0,false,true,10);
                $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
                $this->pdf->multicell(35,10,$this->moneyFormatIndia($row['CREDIT']),0,'R',false,1,110,$this->liney,true,0,false,true,10);
                $this->pdf->multicell(35,10,$this->moneyFormatIndia($row['DEBIT']),0,'R',false,1,75,$this->liney,true,0,false,true,10);
                $this->pdf->multicell(35,10,$this->moneyFormatIndia($row['CLOSINGBALANCE']),0,'R',false,1,150,$this->liney,true,0,false,true,10);
                $this->pdf->SetFont('SakalMarathiNormal922', '', 9, '', true);
                $this->pdf->multicell(7,10,$row['CLOSINGBALANCETYPE'],0,'R',false,1,182,$this->liney,true,0,false,true,10);
                if ($this->isnewpage(5))
                {
                    $this->newpage(True);
                }
                $this->liney = $this->liney+5;
                $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
                $this->pdf->line(10,$this->liney,189,$this->liney);
                $credit=$credit+$row['CREDIT']; 
                $debit=$debit+$row['DEBIT']; 
            }
            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            if ($this->isnewpage(20))
            {
                $this->newpage(True);
            }

            $this->pdf->multicell(20,10,'एकूण',0,'R',false,1,55,$this->liney,true,0,false,true,10);
            $this->pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
            $this->pdf->multicell(35,10,$this->moneyFormatIndia($credit),0,'R',false,1,110,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(35,10,$this->moneyFormatIndia($debit),0,'R',false,1,75,$this->liney,true,0,false,true,10);
            $this->liney = $this->liney+5;
            $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
            $this->pdf->line(10,$this->liney,189,$this->liney);
            $this->pagefooter();
    }

        public function height($data,$width)
    {
        // store current object
        $this->pdf->startTransaction();
        // store starting values
        $start_y = $this->pdf->GetY();
        $start_page = $this->pdf->getPage();
        // call your printing functions with your parameters
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        $this->pdf->MultiCell($w=$width, $h=0, $data, $border=1, $align='L', $fill=false, $ln=1, $x='', $y='',     $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // get the new Y
        $end_y = $this->pdf->GetY();
        $end_page = $this->pdf->getPage();
        // calculate height
        $height = 0;
        if ($end_page == $start_page) {
            $height = $end_y - $start_y;
        } else {
            for ($page=$start_page; $page <= $end_page; ++$page) {
                $this->pdf->setPage($page);
                if ($page == $start_page) {
                    // first page
                    $height = $this->pdf->h - $start_y - $this->pdf->bMargin;
                } elseif ($page == $end_page) {
                    // last page
                    $height = $end_y - $this->pdf->tMargin;
                } else {
                    $height = $this->pdf->h - $this->pdf->tMargin - $this->pdf->bMargin;
                }
            }
        }
        // restore previous object
        $this->pdf = $this->pdf->rollbackTransaction();
        return $height;
    }


    //NumberToWords(number,1)
    function moneyFormatIndia($num)
    {
        $explrestunits = "" ;
        $num=preg_replace('/,+/', '', $num);
        $words = explode(".", $num);
        $des="00";
        if(count($words)<=2)
        {
            $num=$words[0];
            if(count($words)>=2)
            {
                $des=$words[1];
            }
            if(strlen($des)<2)
            {
                $des=$des."0";
            }
            else
            {
                $des=substr($des,0,2);
            }
        }
        if(strlen($num)>3)
        {
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++)
            {
                // creates each of the 2's group and adds a comma to the end
                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
                }
                else
                {
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } 
        else 
        {
            $thecash = $num;
        }
        return "$thecash.$des"; // writes the final format where $currency is the currency symbol.
    }
}    
?>