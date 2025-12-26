<?php
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    //include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
  
class journalregister extends swappreport
{	
    public $connection;
    public $liney;
    public $maxlines;
    public $pdf;
    //
    public $fromdate;
    public $todate;
    public $yearcode;
    public $vouchersubtypecode;
 
    public function __construct(&$connection,$maxlines,$isbig=0)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        // create new PDF document
        $this->ispagebig = $isbig;
        $this->pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Journal Register');
        $this->pdf->SetKeywords('JRNREG_000.MR');
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
                $this->pdf->addpage('P',$resolution);
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
            //$this->pdf->line(15,$this->liney,100,$this->liney);
            $this->liney = 20;
            //$resolution= array(80, 100);
            $this->pdf->addpage();
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
        $this->pdf->Output('JRNREG_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 15;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->newrow(5);
        $this->textbox('जर्नल रजिस्टर',200,10,'S','C',1,'siddhanta',13);
        $this->newrow(5);
        $frdt = DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y');
        $todt = DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y');
        $this->textbox('दिनांक '.$frdt.' पासुन '.$todt.' पर्यंत',200,10,'S','C',1,'siddhanta',10);
        $this->newrow(5);
    	$this->pdf->line(5,$this->liney,200,$this->liney);
        //$this->liney = $this->liney+5;
        $this->pdf->SetFont('siddhanta', '', 12, '', true);
        $this->pdf->multicell(30,10,'व्हा.नं',0,'L',false,1,5,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(60,10,'तपशिल',0,'L',false,1,35,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(30,10,'नावे',0,'R',false,1,135,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(30,10,'जमा',0,'R',false,1,170,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+7;
        $this->pdf->line(5,$this->liney,200,$this->liney);
    }


function pagefooter($islastpage = false)
    {

    }

	function detail()
    {
        if ($this->vouchersubtypecode == 14)
        $query = "select r.transactionnumber,r.vouchernumberprefixsufix,r.voucherdate,r.narration
        from voucherheader r,vouchersubtype s,vouchertype v
        where r.vouchersubtypecode=s.vouchersubtypecode
        and s.vouchertypecode=v.vouchertypecode
        and r.voucherdate>='".$this->fromdate."'
        and r.voucherdate<='".$this->todate."'
        and r.yearperiodcode=".$this->yearcode." 
        and v.vouchertypecode in (4,5,6)
        and s.vouchersubtypecode =".$this->vouchersubtypecode."
        order by voucherdate,vouchernumber";
        else if ($this->vouchersubtypecode == 19)
        $query = "select r.transactionnumber,r.vouchernumberprefixsufix,r.voucherdate,r.narration
        from voucherheader r,vouchersubtype s,vouchertype v
        where r.vouchersubtypecode=s.vouchersubtypecode
        and s.vouchertypecode=v.vouchertypecode
        and r.voucherdate>='".$this->fromdate."'
        and r.voucherdate<='".$this->todate."'
        and r.yearperiodcode=".$this->yearcode." 
        and v.vouchertypecode in (4,5,6)
        and s.vouchersubtypecode not in (14) 
        order by voucherdate,vouchernumber";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $mainsrno=1;
        $totalcredit=0;
        $totaldebit=0;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {  
            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            if ($this->isnewpage(5))
            {
                $this->newpage();
            }
            $this->pdf->multicell(30,10,$row['VOUCHERNUMBERPREFIXSUFIX'],0,'L',false,1,5,$this->liney,true,0,false,true,10);
            $vdt = DateTime::createFromFormat('d-M-Y',$row['VOUCHERDATE'])->format('d/m/Y');
            $this->pdf->multicell(30,10,$vdt,0,'L',false,1,35,$this->liney,true,0,false,true,10);
            $this->liney = $this->liney+5;
            $query1 = "select * from (select drcr
            ,t.accountcode
            ,t.accountnameuni
            ,t.issubledgerallowed
            ,t.detailserialnumber
            ,sum(t.credit)credit
            ,sum(t.debit) debit
            from (
            select  case when credit is not null and v.vouchertypecode in (1,3,5) then 0 
                when debit is not null and v.vouchertypecode in (1,3,5) then 1
                when credit is not null and v.vouchertypecode in (2,4,6) then 1 
                when debit is not null and v.vouchertypecode in (2,4,6) then 0
                else 1 end as drcr
            ,t.accountcode
            ,h.accountnameuni
            ,h.issubledgerallowed
            ,t.detailserialnumber
            ,t.credit
            ,t.debit
            from voucherdetail t,accounthead h,voucherheader r,vouchersubtype s,vouchertype v
            where t.transactionnumber=r.transactionnumber
            and r.vouchersubtypecode=s.vouchersubtypecode
            and s.vouchertypecode=v.vouchertypecode
            and t.accountcode=h.accountcode 
            and r.transactionnumber=".$row['TRANSACTIONNUMBER']."
            and h.issubledgerallowed=248805611
            )t
            group by drcr,t.accountcode
            ,t.accountnameuni
            ,t.issubledgerallowed
            ,t.detailserialnumber
            union all
            select drcr
            ,t.accountcode
            ,t.accountnameuni
            ,t.issubledgerallowed
            ,0 as detailserialnumber
            ,sum(t.credit)credit
            ,sum(t.debit) debit
            from (
            select  case when credit is not null and v.vouchertypecode in (1,3,5) then 0 
                when debit is not null and v.vouchertypecode in (1,3,5) then 1
                when credit is not null and v.vouchertypecode in (2,4,6) then 1 
                when debit is not null and v.vouchertypecode in (2,4,6) then 0
                else 1 end as drcr
            ,t.accountcode
            ,h.accountnameuni
            ,h.issubledgerallowed
            ,t.detailserialnumber
            ,t.credit
            ,t.debit
            from voucherdetail t,accounthead h,voucherheader r,vouchersubtype s,vouchertype v
            where t.transactionnumber=r.transactionnumber
            and r.vouchersubtypecode=s.vouchersubtypecode
            and s.vouchertypecode=v.vouchertypecode
            and t.accountcode=h.accountcode 
            and r.transactionnumber=".$row['TRANSACTIONNUMBER']."
            and h.issubledgerallowed=248805511
            )t
            group by drcr,t.accountcode
            ,t.accountnameuni
            ,t.issubledgerallowed
            )t
            order by drcr,t.accountcode
            ,t.accountnameuni
            ,t.issubledgerallowed
            ,t.detailserialnumber";
            $result1 = oci_parse($this->connection, $query1);
            $r1 = oci_execute($result1);
            $mainsrno=1;
            $totalcredit=0;
            $totaldebit=0;
            while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
            {  
                $this->pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
                $this->pdf->multicell(20,10,$mainsrno,0,'L',false,1,5,$this->liney,true,0,false,true,10);
                $this->pdf->multicell(20,10,$row1['ACCOUNTCODE'],0,'R',false,1,15,$this->liney,true,0,false,true,10);
                $this->pdf->SetFont('siddhanta', '', 10, '', true);
                $this->pdf->multicell(100,10,$row1['ACCOUNTNAMEUNI'],0,'L',false,1,35,$this->liney,true,0,false,true,10);
                
                if ($row1['ISSUBLEDGERALLOWED']== 248805511)
                {
                    $query2 = "select * from (select case when credit is not null and v.vouchertypecode in (1,3,5) then 0 
                    when debit is not null and v.vouchertypecode in (1,3,5) then 1
                    when credit is not null and v.vouchertypecode in (2,4,6) then 1 
                    when debit is not null and v.vouchertypecode in (2,4,6) then 0
                    else 1 end as drcr,
                    t.detailserialnumber
                    ,t.accountcode
                    ,h.accountnameuni
                    ,t.subledgercode
                    ,s.subledgernameuni
                    ,t.credit
                    ,t.debit
                    ,h.issubledgerallowed
                    ,r.vouchersubtypecode
                    from voucherdetail t,accounthead h,accountsubledger s,voucherheader r,vouchersubtype s,vouchertype v
                    where t.accountcode=h.accountcode 
                    and t.subledgercode=s.subledgercode
                    and h.accountcode=s.accountcode
                    and t.accountcode=".$row1['ACCOUNTCODE']."
                    and t.transactionnumber=r.transactionnumber
                    and r.vouchersubtypecode=s.vouchersubtypecode
                    and s.vouchertypecode=v.vouchertypecode
                    and t.transactionnumber=".$row['TRANSACTIONNUMBER'].")
                    where drcr=".$row1['DRCR']."
                    order by detailserialnumber";
                    $result2 = oci_parse($this->connection, $query2);
                    $r2 = oci_execute($result2);
                    $subsrno=1;
                    while ($row2 = oci_fetch_array($result2,OCI_ASSOC+OCI_RETURN_NULLS))
                    {  
                        if ($this->isnewpage(5))
                        {
                            $this->newpage();
                        }
                        $this->liney = $this->liney+5;
                        $this->pdf->SetFont('SakalMarathiNormal922', '', 9, '', true);
                        $this->pdf->multicell(15,10,$mainsrno.".".$subsrno++,0,'L',false,1,8,$this->liney,true,0,false,true,10);
                        $this->pdf->multicell(20,10,$row2['SUBLEDGERCODE'],0,'R',false,1,20,$this->liney,true,0,false,true,10);
                        $this->pdf->SetFont('siddhanta', '', 9, '', true);
                        $this->pdf->multicell(80,10,$row2['SUBLEDGERNAMEUNI'],0,'L',false,1,40,$this->liney,true,0,false,true,10);
                        $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
                        if ($row2['CREDIT']!=0)
                        {
                            $this->pdf->multicell(30,10,$this->moneyFormatIndia($row2['CREDIT']),0,'R',false,1,170,$this->liney,true,0,false,true,10);
                            $totalcredit+=$row2['CREDIT'];
                        }
                        if ($row2['DEBIT']!=0)    
                        {
                            $this->pdf->multicell(30,10,$this->moneyFormatIndia($row2['DEBIT']),0,'R',false,1,135,$this->liney,true,0,false,true,10);
                            $totaldebit+=$row2['DEBIT'];
                        }
                    }
                    $this->liney = $this->liney+5;
                }
                else
                {
                    if ($this->isnewpage(5))
                    {
                        $this->newpage();
                    }
                    $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
                    if ($row1['CREDIT']!=0)
                    {
                        $this->pdf->multicell(30,10,$this->moneyFormatIndia($row1['CREDIT']),0,'R',false,1,170,$this->liney,true,0,false,true,10);
                        $totalcredit+=$row1['CREDIT'];
                    }
                    if ($row1['DEBIT']!=0)
                    {
                        $this->pdf->multicell(30,10,$this->moneyFormatIndia($row1['DEBIT']),0,'R',false,1,135,$this->liney,true,0,false,true,10);
                        $totaldebit+=$row1['DEBIT'];
                    }
                    $this->liney = $this->liney+5;
                }
                $mainsrno++;
                if ($this->isnewpage(5))
                {
                    $this->newpage();
                }
            }
            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            $height1 = $this->textbox($row['NARRATION'],120,5,'S','L',1,'siddhanta',8,'');
            if ($this->isnewpage($height1+10))
            {
                $this->newpage();
            }
           
            $this->liney = $this->liney+$height1+10;
            //$this->liney = $this->liney+5;
            $this->pdf->line(5,$this->liney,200,$this->liney);
            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            $this->pdf->multicell(15,10,'एकूण',0,'L',false,1,120,$this->liney,true,0,false,true,10);
            $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
            $this->pdf->multicell(30,10,$this->moneyFormatIndia($totalcredit),0,'R',false,1,170,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(30,10,$this->moneyFormatIndia($totaldebit),0,'R',false,1,135,$this->liney,true,0,false,true,10);
            $this->liney = $this->liney+5;
            $this->pdf->line(5,$this->liney,200,$this->liney);
            $this->liney = $this->liney+2;
            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            //$this->liney = $this->liney+5;
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
}    
?>