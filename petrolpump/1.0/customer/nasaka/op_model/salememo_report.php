<?php
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a7_l.php");
class salememo extends swappreport
{	
    public $connection;
    public $liney;
    public $maxlines;
    public $pdf;
    //
    public $transactionid;
 
    public function __construct(&$connection,$maxlines)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
    	// create new PDF document Envelop BL
	    $this->pdf = new MYPDF('P', PDF_UNIT, 'A6', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Sale Memo');
        $this->pdf->SetKeywords('SLME_000.MR');
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
                $this->pdf->line(15,$this->liney,80,$this->liney);
                $this->liney = 20;
                $resolution= array(105, 148);
                $this->pdf->addpage('P',$resolution);
                // set color for background
                $this->pdf->SetFillColor(0, 0, 0);
                // set color for text
                $this->pdf->SetTextColor(0, 0, 0);
                $this->pageheader(15);
            }
        }
        else
        {
            $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
            $this->pdf->line(15,$this->liney,100,$this->liney);
            $this->liney = 20;
            $resolution= array(105, 148);
            //$this->pdf->addpage();
            $this->pdf->addpage('P',$resolution);
            // set color for background
            $this->pdf->SetFillColor(0, 0, 0);
            // set color for text
            $this->pdf->SetTextColor(0, 0, 0);
            $this->pageheader(15);
        }
    }
    function endreport()
    {
        // reset pointer to the last page*/
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->Output('SLME_000.pdf', 'I');
    }
	function pageheader($start)
    {
        $this->liney = $start;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->liney = $this->liney+5;
    	$this->pdf->line(5,$this->liney,200,$this->liney);
        $this->liney = $this->liney+2;
        $query = "select vehiclenumber
        ,d.wbslipnumber
        ,c.customercode
        ,c.customernameuni
        ,c.customernameeng
        ,h.invoicenumber_suffpref
        ,h.invoicedate
        ,c.refcode 
        ,p.pumpname_uni
        ,n.name_unicode
        ,h.transactioncategoryid
        ,h.narration
        from saleheader h
        ,saledetail d
        ,customer c
        ,pump p
        ,nst_nasaka_db.namedetail n
        where h.transactionid=d.reftransactionid 
        and h.customercode=c.customercode 
        and h.pumpcode=p.pumpcode
        and h.shiftcode=n.namedetailid 
        and h.transactionid=".$this->transactionid;
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        //shift
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->pdf->SetFont('siddhanta', '', 13, '', true);
            //$dt = $fromdate = DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y');
            if ($row['TRANSACTIONCATEGORYID']==1)
            {
                $this->pdf->multicell(0,15,'         रोख विक्री मेमो',0,'C',false,1,0,$this->liney-7,true,0,false,true,10);
            }
            else 
            {
                $this->pdf->multicell(0,15,'         उधार विक्री मेमो',0,'C',false,1,0,$this->liney-7,true,0,false,true,10);
            }
            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            $this->pdf->multicell(35,10,$row['PUMPNAME_UNI'],0,'L',false,1,10,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(35,10,$row['NAME_UNICODE'],0,'L',false,1,55,$this->liney,true,0,false,true,10);
            $this->liney = $this->liney+5;
            $this->pdf->multicell(15,10,'मेमो नं:',0,'L',false,1,10,$this->liney,true,0,false,true,10);
            $this->pdf->SetFont('siddhanta', '', 10, '', true);
            $this->pdf->multicell(35,10,$row['INVOICENUMBER_SUFFPREF'],0,'L',false,1,23,$this->liney,true,0,false,true,10);
            $dt = DateTime::createFromFormat('d-M-Y',$row['INVOICEDATE'])->format('d/m/Y');	
		    $this->pdf->multicell(20,10,'मेमो दिनांक:',0,'L',false,1,55,$this->liney,true,0,false,true,10);            
            $this->pdf->multicell(30,10,$dt,0,'L',false,1,75,$this->liney,true,0,false,true,10);
            $this->liney = $this->liney+5;
            $this->pdf->multicell(20,10,'वाहन नंबर:',0,'L',false,1,10,$this->liney,true,0,false,true,10);
            if ($row['TRANSACTIONCATEGORYID']==2)
            {
                $this->pdf->multicell(50,10,strtoupper($row['VEHICLENUMBER']),0,'L',false,1,28,$this->liney,true,0,false,true,10);
            }
            $this->pdf->multicell(17,10,'स्लीप नं:',0,'L',false,1,55,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(15,10,$row['WBSLIPNUMBER'],0,'L',false,1,75,$this->liney,true,0,false,true,10);
            $this->liney = $this->liney+5;
            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            $this->pdf->multicell(15,10,'ग्राहक:',0,'L',false,1,10,$this->liney,true,0,false,true,10);
            $this->pdf->SetFont('siddhanta', '', 10, '', true);
            if ($row['TRANSACTIONCATEGORYID']==1)
            {
                $this->pdf->multicell(65,10,$row['NARRATION'],0,'L',false,1,20,$this->liney,true,0,false,true,10);
            }
            elseif ($row['TRANSACTIONCATEGORYID']==2)
            {
                $this->pdf->multicell(65,10,$row['REFCODE'].' '.$row['CUSTOMERNAMEUNI'],0,'L',false,1,20,$this->liney,true,0,false,true,10);
            }
        } 
        $this->liney = $this->liney+5;
        $this->pdf->line(5,$this->liney,200,$this->liney);
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->pdf->multicell(25,10,'तपशिल',0,'L',false,1,10,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(20,10,'लिटर',0,'R',false,1,30,$this->liney,true,0,false,true,10);
		$this->pdf->multicell(20,10,'दर',0,'R',false,1,50,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(30,10,'रक्कम',0,'R',false,1,70,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+5;
        $this->pdf->line(5,$this->liney,200,$this->liney);
    }

	function detail()
    {
        $query = "select i.itemcode,
        i.itemnameuni,i.itemnameeng,d.qty,d.rate,d.amount 
        from saleheader h,saledetail d,item i
        where h.transactionid=d.reftransactionid 
        and d.itemcode=i.itemcode 
        and h.transactionid=".$this->transactionid;
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        //shift
        $item_qty=0;
        $item_amount=0;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {  
            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            $this->pdf->multicell(50,10,$row['ITEMNAMEUNI'],0,'L',false,1,10,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(20,10,$row['QTY'],0,'R',false,1,30,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(20,10,$this->moneyFormatIndia($row['RATE']),0,'R',false,1,50,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(30,10,$this->moneyFormatIndia($row['AMOUNT']),0,'R',false,1,70,$this->liney,true,0,false,true,10);
            $item_qty=$item_qty+$row['QTY'];
            $item_amount=$item_amount+$row['AMOUNT'];
            if ($this->isnewpage(5))
            {
                $this->newpage();
            }
            $this->liney = $this->liney+5;
        }
        $this->pdf->line(5,$this->liney,200,$this->liney);
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->pdf->multicell(15,10,'एकूण',0,'L',false,1,10,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(30,10,$this->moneyFormatIndia($item_amount),0,'R',false,1,70,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+5;
        $this->pdf->line(5,$this->liney,200,$this->liney);
        $this->liney = $this->liney+10;
        $this->pdf->multicell(30,10,'ड्रायव्हरची सही',0,'R',false,1,10,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(50,10,'पंपमन '.$_SESSION["usersname"],0,'R',false,1,40,$this->liney,true,0,false,true,10);
    }
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