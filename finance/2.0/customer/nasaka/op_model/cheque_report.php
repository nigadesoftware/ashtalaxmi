<?php
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a5_l.php");
    //include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
  
class cheque extends swappreport
{	
    public $connection;
    public $liney;
    public $maxlines;
    public $pdf;
    public $ispagebig;
    //
    public $transactionnumber;
    public $billperiodtransnumber;

    public function __construct(&$connection,$maxlines,$isbig=0)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        // create new PDF document
        $this->ispagebig = $isbig;
        $this->pdf = new MYPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Cheque');
        $this->pdf->SetKeywords('CHEQUE_000.MR');
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
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
                $resolution= array(220, 110);
                $this->pdf->addpage('L',$resolution);
                // set color for background
                $this->pdf->SetFillColor(0, 0, 0);
                // set color for text
                $this->pdf->SetTextColor(0, 0, 0);
                $this->pageheader();
            }
        }
        else
        {
            $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
           // $this->pdf->line(15,$this->liney,100,$this->liney);
            $this->liney = 20;
            $resolution= array(220, 110);
            $this->pdf->addpage('L',$resolution);
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
        ob_end_clean();
        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->Output('CHEQUE_000.pdf', 'I');
    }
	function pageheader()
    {
    }


function pagefooter($islastpage = false)
    {
    }


	function detail()
    {
            
        $query2 = "select t.bankaccountcode
        ,h.accountnameuni
        ,f.funddocumentnameuni
        ,t.funddocumentdate
        ,t.funddocumentnumber
        ,t.funddocumentamount
        ,t.draweebank
        ,t.payee
        from voucherheader h,voucherchequedddetail t,funddocumenttype f,accounthead h
        where h.transactionnumber=t.transactionnumber 
        and t.funddocumentcode=f.funddocumentcode
        and t.bankaccountcode=h.accountcode
        and t.transactionnumber =".$this->transactionnumber;
        $result2 = oci_parse($this->connection, $query2);
        $r2 = oci_execute($result2);
        if ($row2 = oci_fetch_array($result2,OCI_ASSOC+OCI_RETURN_NULLS))
        {  
            $this->liney = 23;
            $this->pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
            $this->pdf->StartTransform();
            $this->pdf->Rotate(45);
            $this->pdf->MultiCell(20, 5, "A/c Payee", 1, 'L', false, 1, 23, 35, true, 0, false, true, 0, "T", false, true);
            $this->pdf->StopTransform();
            //$this->liney = 7;
            //$this->pdf->SetFont('siddhanta', '', 11, '', true);
            $this->liney = 22;
            $this->pdf->SetFont('SakalMarathiNormal922', '', 12, '', true);
            $dts = DateTime::createFromFormat('d-M-y',$row2['FUNDDOCUMENTDATE'])->format('dmY');	
            $this->pdf->multicell(40,10,substr($dts,0,1).' '.substr($dts,1,1).'/'.substr($dts,2,1).' '.substr($dts,3,1).'/'.substr($dts,4,1).' '.substr($dts,5,1).' '.substr($dts,6,1).' '.substr($dts,7,1),0,'L',false,1,170,$this->liney-7,true,0,false,true,10);
            $this->liney = 33;
            $this->pdf->SetFont('siddhanta', '', 13, '', true);
            $this->pdf->multicell(170,10,$row2['PAYEE'],0,'L',false,1,39,$this->liney-5,true,0,false,true,10);
            $this->pdf->SetFont('SakalMarathiNormal922', '', 13, '', true);
            $this->liney = 42;
            $this->pdf->multicell(35,10,$this->moneyFormatIndia($row2['FUNDDOCUMENTAMOUNT']),0,'L',false,1,175,$this->liney,true,0,false,true,10);
            $this->pdf->SetFont('siddhanta', '', 13, '', true);
            $whole = floor($row2['FUNDDOCUMENTAMOUNT']);      // 1
            $fraction = round(($row2['FUNDDOCUMENTAMOUNT']-$whole)*100); // .25
            $this->liney = 36;
            $this->pdf->SetFont('siddhanta', '', 12, '', true);
            
            if ($fraction==0)
            {
                
                $this->pdf->multicell(140,20,''.NumberToWords($whole,1).' फक्त',0,'L',false,1,50,$this->liney,true,0,false,true,10);
            }
            else
            {
                $a=NumberToWords($whole,1);
                $b=NumberToWords($fraction,1);
                $this->pdf->multicell(140,20,''.$a.'आणि '.$b.'पैसे फक्त',0,'L',false,1,50,$this->liney,true,0,false,true,10);
            }
            $this->liney = 55;
            $this->pdf->SetFont('siddhanta', '', 9, '', true);
            $this->pdf->multicell(100,20,'For Nashik Sahakari Sakhar Karkhana Ltd.',0,'L',false,1,140,$this->liney,true,0,false,true,10);
            $this->liney = 68;
            $this->pdf->multicell(100,20,'Managing Director',0,'L',false,1,150,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(100,20,'Secretary',0,'L',false,1,150,$this->liney+5,true,0,false,true,10);
            $this->pdf->multicell(100,20,'Cheif Accountant',0,'L',false,1,150,$this->liney+10,true,0,false,true,10);
            $this->pdf->multicell(100,20,'Chairman',0,'L',false,1,190,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(100,20,'Vice Chairman',0,'L',false,1,190,$this->liney+5,true,0,false,true,10);
            $this->pdf->multicell(100,20,'Director',0,'L',false,1,190,$this->liney+10,true,0,false,true,10);
        }
    }

public function isbankaccountcode($accountcode)
    {
        $query = "select count(*) cnt
       from accounthead a,accountcontroltable t 
       where ((a.groupcode=t.bankgroupcode
       and a.subgroupcode=t.banksubgroupcode) 
       or (a.groupcode=t.bankgroupcode1)
       or (a.groupcode=t.bankgroupcode2))
       and a.accountcode=".$accountcode ;
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {  
            return $row['CNT'];
        }
        else
        {
            return 0;
        }
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


    public function billperiodtransnumber()
    {
        $query = "select refbillperiodtrnno
       from voucherheader v 
       where transactionnumber=".$this->transactionnumber;
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {  
            return $row['REFBILLPERIODTRNNO'];
        }
        else
        {
            return 0;
        }
    }

}    
?>