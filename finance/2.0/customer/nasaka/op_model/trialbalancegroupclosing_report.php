<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    include_once("../ip_model/accounthead_db_oracle.php");
    include_once("../ip_model/voucherheader_db_oracle.php");
  
class trialbalancegroupclosing extends swappreport
{	
    public $connection;
    public $liney;
    public $maxlines;
    public $pdf;
    public $fromdate;
    public $todate;
    public $yearcode;

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
        $this->pdf->SetSubject('Trial Balance Group Closing');
        $this->pdf->SetKeywords('TRBLGRCL_000.MR');
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
                $this->drawlines($this->liney-40);
                $this->liney = 20;
                //$resolution= array(80, 100);
                if ($this->currentpage==$this->totalpages)
                {
                    //$this->drawlines($this->liney-40);
                    $this->pdf->addpage('P',$resolution);
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
            $this->drawlines($this->liney-40);
            $this->liney = 20;
            //$resolution= array(80, 100);
            if ($this->currentpage==$this->totalpages)
            {
                //$this->drawlines($this->liney-40);
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
        $this->pdf->Output('TRBLGRCL_000.pdf', 'I');
        exit;
    }
	function pageheader()
    {
        $this->liney = 15;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->pdf->SetFont('siddhanta', '', 14, '', true);
        $this->pdf->multicell(0,10,'तेरीज पत्रक (ग्रुपवार)',0,'L',false,1,80,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+7;
        $this->pdf->SetFont('siddhanta', '', 12, '', true);
        $frdt = DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y');
        $todt = DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y');
        $this->pdf->multicell(0,10,'दिनांक '.$frdt.' पासून '.' दिनांक '.$todt.' पर्यंत',0,'L',false,1,50,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+7;
        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
        $this->pdf->line(10,$this->liney,202,$this->liney);
        $this->liney = $this->liney+2;
       	$this->pdf->multicell(60,10,'खाते तपशील',0,'L',false,1,60,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(70,10,'अखेरची शिल्लक',0,'L',false,1,150,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+5;
       	$this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
        $this->pdf->line(10,$this->liney,202,$this->liney);
        $this->pdf->multicell(20,10,'कोड',0,'L',false,1,10,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(100,10,'खाते',0,'L',false,1,30,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(35,10,'नावे',0,'R',false,1,130,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(35,10,'जमा',0,'R',false,1,165,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+5;
        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
        $this->pdf->line(10,$this->liney,202,$this->liney);
    }

function drawlines($limit)
{
    $liney = $this->liney;
    $this->liney = 41;
    $this->pdf->line(10,$this->liney-12,10,$this->liney+$limit);
    $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
    $this->pdf->line(30,$this->liney-5,30,$this->liney+$limit);
    $this->pdf->line(30,$this->liney-5,30,$this->liney+$limit);
    $this->pdf->line(130,$this->liney-12,130,$this->liney+$limit);
    $this->pdf->line(165,$this->liney-5,165,$this->liney+$limit);
    $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
    $this->pdf->line(202,$this->liney-12,202,$this->liney+$limit);
    $this->pdf->line(10,$this->liney+$limit,202,$this->liney+$limit);
    $this->liney = $liney;
}

function pagefooter($islastpage = false)
    {
        $this->drawlines($this->liney-41);
        $this->liney = $this->liney+15; 
        $this->pdf->SetFont('siddhanta', '', 12, '', true);
        $this->pdf->multicell(0,10,'                   तयार करणार                          तपासणार                            चिफ अकौंटंट                            जनरल मॅनेजर ',0,'L',false,1,5,$this->liney,true,0,false,true,10);
    }

    function detail()
    {
        $query="select * from (
            --main group start
            select ".$this->yearcode." as yearperiodcode,g.groupcode,null as subgroupcode,null as subsubgroupcode,1 as sequence,null as accountcode,max(g.groupnameuni) as accountnameuni
             ,0 as openingbalance_cr
             ,0 as openingbalance_dr
             ,0 as credit
             ,0 as debit
             ,0 as closingbalance_cr
             ,0 as closingbalance_dr
            from accounthead a,accountgroup g
            where a.groupcode=g.groupcode
            group by g.groupcode
            union all
            select yearperiodcode,groupcode,null as subgroupcode,null as subsubgroupcode,9 as sequence,null as accountcode,accountnameuni,
            case when openingbalance<0 then abs(openingbalance) else 0 end openingbalance_cr
                    ,case when openingbalance>0 then openingbalance else 0 end openingbalance_dr
                    ,credit
                    ,debit
                    ,case when closingbalance<0 then abs(closingbalance) else 0 end closingbalance_cr
                    ,case when closingbalance>0 then closingbalance else 0 end closingbalance_dr
            from (        
            select t.yearperiodcode,g.groupcode,g.groupnameuni as accountnameuni
                    ,sum(openingbalance) as openingbalance
                    ,sum(credit) as credit
                    ,sum(debit) as debit
                    ,nvl(sum(openingbalance),0)+nvl(sum(debit),0)-nvl(sum(credit),0) as closingbalance
                    from
                    (
                    select a.yearperiodcode,a.accountcode
                    ,(nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0)) as openingbalance
                    ,0 as credit
                    ,0 as debit
                    ,0 as closingbalance
                    from (
                    select a.yearperiodcode,a.accountcode,nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance
                    from accountopening a,accounthead h,accountgroup g,grouptype t
                    where a.accountcode=h.accountcode
                    and h.groupcode=g.groupcode and g.grouptypecode=t.grouptypecode
                    and t.groupnaturecode=1
                    union all
                    select t.yearperiodcode,d.accountcode,nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance
                    from voucherheader t,voucherdetail d
                    where t.transactionnumber=d.transactionnumber
                    and t.voucherdate<'".$this->fromdate."'
                    and t.approvalstatus=9
                    group by t.yearperiodcode,d.accountcode
                    )a
                    group by a.yearperiodcode,a.accountcode
                    union all
                    select t.yearperiodcode,d.accountcode,0 as openingbalance,nvl(sum(d.credit),0) as credit, 0 as debit,0 as closingbalance
                    from voucherheader t,voucherdetail d
                    where t.transactionnumber=d.transactionnumber
                    and t.voucherdate>='".$this->fromdate."' and t.voucherdate<='".$this->todate."'
                    and t.approvalstatus=9
                    group by t.yearperiodcode,d.accountcode
                    union all
                    select t.yearperiodcode,d.accountcode,0 as openingbalance,0 as credit,nvl(sum(d.debit),0) as debit,0 as closingbalance
                    from voucherheader t,voucherdetail d
                    where t.transactionnumber=d.transactionnumber
                    and t.voucherdate>='".$this->fromdate."' and t.voucherdate<='".$this->todate."'
                    and t.approvalstatus=9
                    group by t.yearperiodcode,d.accountcode
                    )t,accounthead h,accountgroup g
                    where t.accountcode=h.accountcode
                    and h.groupcode=g.groupcode
                    group by t.yearperiodcode,g.groupcode,g.groupnameuni
                    having yearperiodcode=".$this->yearcode." 
                    )
            union all
            --sub group start
            select ".$this->yearcode." as yearperiodcode,s.groupcode,s.subgroupcode,null as subsubgroupcode,2 as sequence,null as accountcode,max(s.subgroupnameuni) as accountnameuni
             ,0 as openingbalance_cr
             ,0 as openingbalance_dr
             ,0 as credit
             ,0 as debit
             ,0 as closingbalance_cr
             ,0 as closingbalance_dr
            from accounthead a,accountgroup g,accountsubgroup s
            where a.groupcode=s.groupcode and a.subgroupcode=s.subgroupcode and s.groupcode=g.groupcode
            group by s.groupcode,s.subgroupcode
            union all
            select yearperiodcode,groupcode,subgroupcode,null as subsubgroupcode,8 as sequence,null as accountcode,accountnameuni,
            case when openingbalance<0 then abs(openingbalance) else 0 end openingbalance_cr
                    ,case when openingbalance>0 then openingbalance else 0 end openingbalance_dr
                    ,credit
                    ,debit
                    ,case when closingbalance<0 then abs(closingbalance) else 0 end closingbalance_cr
                    ,case when closingbalance>0 then closingbalance else 0 end closingbalance_dr
            from (        
            select t.yearperiodcode,s.groupcode,s.subgroupcode,s.subgroupnameuni as accountnameuni
                    ,sum(openingbalance) as openingbalance
                    ,sum(credit) as credit
                    ,sum(debit) as debit
                    ,nvl(sum(openingbalance),0)+nvl(sum(debit),0)-nvl(sum(credit),0) as closingbalance
                    from
                    (
                    select a.yearperiodcode,a.accountcode
                    ,(nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0)) as openingbalance
                    ,0 as credit
                    ,0 as debit
                    ,0 as closingbalance
                    from (
                    select a.yearperiodcode,a.accountcode,nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance
                    from accountopening a,accounthead h,accountgroup g,grouptype t
                    where a.accountcode=h.accountcode
                    and h.groupcode=g.groupcode and g.grouptypecode=t.grouptypecode
                    and t.groupnaturecode=1
                    union all
                    select t.yearperiodcode,d.accountcode,nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance
                    from voucherheader t,voucherdetail d
                    where t.transactionnumber=d.transactionnumber
                    and t.voucherdate<'".$this->fromdate."'
                    and t.approvalstatus=9
                    group by t.yearperiodcode,d.accountcode
                    )a
                    group by a.yearperiodcode,a.accountcode
                    union all
                    select t.yearperiodcode,d.accountcode,0 as openingbalance,nvl(sum(d.credit),0) as credit, 0 as debit,0 as closingbalance
                    from voucherheader t,voucherdetail d
                    where t.transactionnumber=d.transactionnumber
                    and t.voucherdate>='".$this->fromdate."' and t.voucherdate<='".$this->todate."'
                    and t.approvalstatus=9
                    group by t.yearperiodcode,d.accountcode
                    union all
                    select t.yearperiodcode,d.accountcode,0 as openingbalance,0 as credit,nvl(sum(d.debit),0) as debit,0 as closingbalance
                    from voucherheader t,voucherdetail d
                    where t.transactionnumber=d.transactionnumber
                    and t.voucherdate>='".$this->fromdate."' and t.voucherdate<='".$this->todate."'
                    and t.approvalstatus=9
                    group by t.yearperiodcode,d.accountcode
                    )t,accounthead h,accountgroup g,accountsubgroup s
                    where t.accountcode=h.accountcode
                    and h.groupcode=s.groupcode and h.subgroupcode=s.subgroupcode and g.groupcode=s.groupcode
                    group by t.yearperiodcode,s.groupcode,s.subgroupcode,s.subgroupnameuni
                    having yearperiodcode=".$this->yearcode." 
                    )
            union all
            --sub sub group start
            select ".$this->yearcode." as yearperiodcode,r.groupcode,r.subgroupcode,r.subsubgroupcode,3 as sequence,null as accountcode,max(r.subsubgroupnameuni) as accountnameuni
             ,0 as openingbalance_cr
             ,0 as openingbalance_dr
             ,0 as credit
             ,0 as debit
             ,0 as closingbalance_cr
             ,0 as closingbalance_dr
            from accounthead a,accountsubsubgroup r
            where a.groupcode=r.groupcode and a.subgroupcode=r.subgroupcode and a.subsubgroupcode=r.subsubgroupcode
            group by r.groupcode,r.subgroupcode,r.subsubgroupcode
            union all
            select yearperiodcode,groupcode,subgroupcode,null as subsubgroupcode,7 as sequence,null as accountcode,accountnameuni,
            case when openingbalance<0 then abs(openingbalance) else 0 end openingbalance_cr
                    ,case when openingbalance>0 then openingbalance else 0 end openingbalance_dr
                    ,credit
                    ,debit
                    ,case when closingbalance<0 then abs(closingbalance) else 0 end closingbalance_cr
                    ,case when closingbalance>0 then closingbalance else 0 end closingbalance_dr
            from (        
            select t.yearperiodcode,r.groupcode,r.subgroupcode,r.subsubgroupnameuni as accountnameuni
                    ,sum(openingbalance) as openingbalance
                    ,sum(credit) as credit
                    ,sum(debit) as debit
                    ,nvl(sum(openingbalance),0)+nvl(sum(debit),0)-nvl(sum(credit),0) as closingbalance
                    from
                    (
                    select a.yearperiodcode,a.accountcode
                    ,(nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0)) as openingbalance
                    ,0 as credit
                    ,0 as debit
                    ,0 as closingbalance
                    from (
                    select a.yearperiodcode,a.accountcode,nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance
                    from accountopening a,accounthead h,accountgroup g,grouptype t
                    where a.accountcode=h.accountcode
                    and h.groupcode=g.groupcode and g.grouptypecode=t.grouptypecode
                    and t.groupnaturecode=1
                    union all
                    select t.yearperiodcode,d.accountcode,nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance
                    from voucherheader t,voucherdetail d
                    where t.transactionnumber=d.transactionnumber
                    and t.voucherdate<'".$this->fromdate."'
                    and t.approvalstatus=9
                    group by t.yearperiodcode,d.accountcode
                    )a
                    group by a.yearperiodcode,a.accountcode
                    union all
                    select t.yearperiodcode,d.accountcode,0 as openingbalance,nvl(sum(d.credit),0) as credit, 0 as debit,0 as closingbalance
                    from voucherheader t,voucherdetail d
                    where t.transactionnumber=d.transactionnumber
                    and t.voucherdate>='".$this->fromdate."' and t.voucherdate<='".$this->todate."'
                    and t.approvalstatus=9
                    group by t.yearperiodcode,d.accountcode
                    union all
                    select t.yearperiodcode,d.accountcode,0 as openingbalance,0 as credit,nvl(sum(d.debit),0) as debit,0 as closingbalance
                    from voucherheader t,voucherdetail d
                    where t.transactionnumber=d.transactionnumber
                    and t.voucherdate>='".$this->fromdate."' and t.voucherdate<='".$this->todate."'
                    and t.approvalstatus=9
                    group by t.yearperiodcode,d.accountcode
                    )t,accounthead h,accountsubsubgroup r
                    where t.accountcode=h.accountcode
                    and h.groupcode=r.groupcode and h.subgroupcode=r.subgroupcode 
                    and h.subsubgroupcode=r.subsubgroupcode
                    group by t.yearperiodcode,r.groupcode,r.subgroupcode,r.subsubgroupnameuni
                    having yearperiodcode=".$this->yearcode." 
                    )
            --end sub sub group
            union all
            --account start
            select yearperiodcode,groupcode,subgroupcode,subsubgroupcode,4 as sequence,accountcode,accountnameuni,
            case when openingbalance<0 then abs(openingbalance) else 0 end openingbalance_cr
                    ,case when openingbalance>0 then openingbalance else 0 end openingbalance_dr
                    ,credit
                    ,debit
                    ,case when closingbalance<0 then abs(closingbalance) else 0 end closingbalance_cr
                    ,case when closingbalance>0 then closingbalance else 0 end closingbalance_dr
            from (        
            select t.yearperiodcode,h.groupcode,h.subgroupcode,h.subsubgroupcode,t.accountcode,h.accountnameuni
                    ,sum(openingbalance) as openingbalance
                    ,sum(credit) as credit
                    ,sum(debit) as debit
                    ,nvl(sum(openingbalance),0)+nvl(sum(debit),0)-nvl(sum(credit),0) as closingbalance
                    from
                    (
                    select a.yearperiodcode,a.accountcode
                    ,(nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0)) as openingbalance
                    ,0 as credit
                    ,0 as debit
                    ,0 as closingbalance
                    from (
                    select a.yearperiodcode,a.accountcode,nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance
                    from accountopening a,accounthead h,accountgroup g,grouptype t
                    where a.accountcode=h.accountcode
                    and h.groupcode=g.groupcode and g.grouptypecode=t.grouptypecode
                    and t.groupnaturecode=1
                    union all
                    select t.yearperiodcode,d.accountcode,nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance
                    from voucherheader t,voucherdetail d
                    where t.transactionnumber=d.transactionnumber
                    and t.voucherdate<'".$this->fromdate."'
                    and t.approvalstatus=9
                    group by t.yearperiodcode,d.accountcode
                    )a
                    group by a.yearperiodcode,a.accountcode
                    union all
                    select t.yearperiodcode,d.accountcode,0 as openingbalance,nvl(sum(d.credit),0) as credit, 0 as debit,0 as closingbalance
                    from voucherheader t,voucherdetail d
                    where t.transactionnumber=d.transactionnumber
                    and t.voucherdate>='".$this->fromdate."' and t.voucherdate<='".$this->todate."'
                    and t.approvalstatus=9
                    group by t.yearperiodcode,d.accountcode
                    union all
                    select t.yearperiodcode,d.accountcode,0 as openingbalance,0 as credit,nvl(sum(d.debit),0) as debit,0 as closingbalance
                    from voucherheader t,voucherdetail d
                    where t.transactionnumber=d.transactionnumber
                    and t.voucherdate>='".$this->fromdate."' and t.voucherdate<='".$this->todate."'
                    and t.approvalstatus=9
                    group by t.yearperiodcode,d.accountcode
                    )t,accounthead h
                    where t.accountcode=h.accountcode
                    group by t.yearperiodcode,h.groupcode,h.subgroupcode,h.subsubgroupcode,t.accountcode,h.accountnameuni
                    having yearperiodcode=".$this->yearcode.")
                    )t,accountgroup a,grouptype g
                    where t.groupcode=a.groupcode and a.grouptypecode=g.grouptypecode
                    order by g.groupnaturecode,g.grouptypecode,a.sequencenumber,a.groupcode,nvl(subgroupcode,sequence)
                    ,nvl(subsubgroupcode,sequence),sequence,accountcode
                    ";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $openingbalance_cr=0; 
        $openingbalance_dr=0; 
        $credit=0; 
        $debit=0; 
        $closingbalance_cr=0;
        $closingbalance_dr=0;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            //$this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
            if ($row['CLOSINGBALANCE_CR']==0 and $row['CLOSINGBALANCE_DR']==0)
            {
                $a=0;
            }
            else
            {
                if ($row['SEQUENCE']==1 or $row['SEQUENCE']==9) 
                {
                    if ($row['SEQUENCE']==9)
                    {
                        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
                    }
                    else
                    {
                        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
                    }
                    $this->pdf->line(10,$this->liney,202,$this->liney);
                //$arrow='->';
                $arrow='';
                $this->pdf->SetFont('siddhanta', 'B', 13, '', true);
                }
                elseif ($row['SEQUENCE']==2 or $row['SEQUENCE']==8) 
                {
                    if ($row['SEQUENCE']==8)
                    {
                        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
                    }
                    else
                    {
                        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
                    }
                    $this->pdf->line(10,$this->liney,202,$this->liney);
                    $arrow='-->';
                $this->pdf->SetFont('siddhanta', 'B', 12, '', true);
                }
                elseif ($row['SEQUENCE']==3 or $row['SEQUENCE']==7) 
                {
                    if ($row['SEQUENCE']==7)
                    {
                        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
                    }
                    else
                    {
                        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
                    }
                    $this->pdf->line(10,$this->liney,202,$this->liney);
                    $arrow='--->';
                $this->pdf->SetFont('siddhanta', 'B', 11, '', true);
                }
                elseif ($row['SEQUENCE']==4) 
                {
                    $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
                    $this->pdf->line(10,$this->liney,202,$this->liney);
                    //$arrow='---->';
                
                $arrow='     ';
                }
                $height = $this->height($arrow.$row['ACCOUNTNAMEUNI'],100);
                if ($this->isnewpage($height))
                {
                    $this->newpage(True);
                }
                $this->pdf->SetFont('siddhanta', '', 10, '', true);
                $this->pdf->multicell(25,10,$row['ACCOUNTCODE'],0,'L',false,1,10,$this->liney,true,0,false,true,10);
                //$height = $this->height($row['ACCOUNTNAMEUNI'],100);
                //$this->pdf->SetFont('siddhanta', '', 11, '', true);
                if ($row['SEQUENCE']==7 or $row['SEQUENCE']==8 or $row['SEQUENCE']==9 or $row['SEQUENCE']==10) 
                $this->pdf->multicell(100,10,$arrow.'एकूण '.$row['ACCOUNTNAMEUNI'],0,'L',false,1,32,$this->liney,true,0,false,true,$height);
                else
                $this->pdf->multicell(100,10,$arrow.$row['ACCOUNTNAMEUNI'],0,'L',false,1,32,$this->liney,true,0,false,true,$height);
                if ($row['SEQUENCE']==4 or $row['SEQUENCE']==7 or $row['SEQUENCE']==8 or $row['SEQUENCE']==9) 
                {
                    $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
                    $this->pdf->multicell(35,10,$this->moneyFormatIndia($row['CLOSINGBALANCE_CR']),0,'R',false,1,165,$this->liney,true,0,false,true,10);
                    $this->pdf->multicell(35,10,$this->moneyFormatIndia($row['CLOSINGBALANCE_DR']),0,'R',false,1,130,$this->liney,true,0,false,true,10);
                }
                if ($row['SEQUENCE']==9)
                {
                    $openingbalance_cr = $openingbalance_cr+$row['OPENINGBALANCE_CR'];
                    $openingbalance_dr = $openingbalance_dr+$row['OPENINGBALANCE_DR'];
                    $credit=$credit+$row['CREDIT'];
                    $debit=$debit+$row['DEBIT'];
                    $closingbalance_cr=$closingbalance_cr+$row['CLOSINGBALANCE_CR'];
                    $closingbalance_dr=$closingbalance_dr+$row['CLOSINGBALANCE_DR'];
                }
                $this->liney = $this->liney+$height+1;
            }
        }
        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
         $this->pdf->line(10,$this->liney,202,$this->liney);
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $height = $this->height('एकूण एकंदर',100);
        if ($this->isnewpage($height+5))
        {
            $this->newpage(True);
        }
        //$this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
        //$this->pdf->multicell(25,10,$row['ACCOUNTCODE'],0,'L',false,1,10,$this->liney,true,0,false,true,10);
        //$height = $this->height($row['ACCOUNTNAMEUNI'],100);
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->pdf->multicell(100,10,'एकूण एकंदर',0,'L',false,1,32,$this->liney,true,0,false,true,10);

            $this->pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
            $this->pdf->multicell(35,10,$this->moneyFormatIndia($closingbalance_cr),0,'R',false,1,165,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(35,10,$this->moneyFormatIndia($closingbalance_dr),0,'R',false,1,130,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+$height+1;
        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
        $this->pdf->line(10,$this->liney,202,$this->liney);
        $this->drawlines($this->liney-40);
        //$this->pdf->SetFont('siddhanta', '', 11, '', true);
        /* if ($this->isnewpage(20))
        {
            $this->newpage(True);
        } */
    }
    public function groupfooter($groupcode,$subgroupcode,$subsubgroupcode)
    {
        if ($subsubgroupcode == '' and $subgroupcode == '')
        {
            $query = "select 
            sum(openingbalance_cr) as openingbalance_cr 
            ,sum(openingbalance_dr) as openingbalance_dr 
            ,sum(credit) as credit
            ,sum(debit) as debit
            ,sum(closingbalance_cr) as closingbalance_cr
            ,sum(closingbalance_dr) as closingbalance_dr
            from (select 
            case when openingbalance<0 then abs(openingbalance) else 0 end openingbalance_cr
            ,case when openingbalance>0 then openingbalance else 0 end openingbalance_dr
            ,credit
            ,debit
            ,case when closingbalance<0 then abs(closingbalance) else 0 end closingbalance_cr
            ,case when closingbalance>0 then closingbalance else 0 end closingbalance_dr
            from (
            select 
            accountopeningbalance(".$this->yearcode.",t.accountcode,'".$this->fromdate."') as openingbalance,
            accountcredit(".$this->yearcode.",t.accountcode,'".$this->fromdate."','".$this->todate."') as credit,
            accountdebit(".$this->yearcode.",t.accountcode,'".$this->fromdate."','".$this->todate."') as debit,
            accountclosingbalance(".$this->yearcode.",t.accountcode,'".$this->todate."') as closingbalance
            from accounthead t where groupcode".$this->invl($groupcode,true,false,'=')."))
            where not(openingbalance_cr=0 
            and openingbalance_dr=0 
            and credit=0 
            and debit=0 
            and closingbalance_cr=0
            and closingbalance_dr=0)";
        }
        else if ($subgroupcode != '' and $subsubgroupcode == '')
        {
            $query = "select 
            sum(openingbalance_cr) as openingbalance_cr 
            ,sum(openingbalance_dr) as openingbalance_dr 
            ,sum(credit) as credit
            ,sum(debit) as debit
            ,sum(closingbalance_cr) as closingbalance_cr
            ,sum(closingbalance_dr) as closingbalance_dr
            from (select 
            case when openingbalance<0 then abs(openingbalance) else 0 end openingbalance_cr
            ,case when openingbalance>0 then openingbalance else 0 end openingbalance_dr
            ,credit
            ,debit
            ,case when closingbalance<0 then abs(closingbalance) else 0 end closingbalance_cr
            ,case when closingbalance>0 then closingbalance else 0 end closingbalance_dr
            from (
            select 
            accountopeningbalance(".$this->yearcode.",t.accountcode,'".$this->fromdate."') as openingbalance,
            accountcredit(".$this->yearcode.",t.accountcode,'".$this->fromdate."','".$this->todate."') as credit,
            accountdebit(".$this->yearcode.",t.accountcode,'".$this->fromdate."','".$this->todate."') as debit,
            accountclosingbalance(".$this->yearcode.",t.accountcode,'".$this->todate."') as closingbalance
            from accounthead t where groupcode".$this->invl($groupcode,true,false,'=').
            " and subgroupcode".$this->invl($subgroupcode,true,false,'=')."))
            where not(openingbalance_cr=0 
            and openingbalance_dr=0 
            and credit=0 
            and debit=0 
            and closingbalance_cr=0
            and closingbalance_dr=0)";
        }
        else if ($subgroupcode != '' and $subsubgroupcode != '')
        {
            $query = "select 
            sum(openingbalance_cr) as openingbalance_cr 
            ,sum(openingbalance_dr) as openingbalance_dr 
            ,sum(credit) as credit
            ,sum(debit) as debit
            ,sum(closingbalance_cr) as closingbalance_cr
            ,sum(closingbalance_dr) as closingbalance_dr
            from (select 
            case when openingbalance<0 then abs(openingbalance) else 0 end openingbalance_cr
            ,case when openingbalance>0 then openingbalance else 0 end openingbalance_dr
            ,credit
            ,debit
            ,case when closingbalance<0 then abs(closingbalance) else 0 end closingbalance_cr
            ,case when closingbalance>0 then closingbalance else 0 end closingbalance_dr
            from (
            select 
            accountopeningbalance(".$this->yearcode.",t.accountcode,'".$this->fromdate."') as openingbalance,
            accountcredit(".$this->yearcode.",t.accountcode,'".$this->fromdate."','".$this->todate."') as credit,
            accountdebit(".$this->yearcode.",t.accountcode,'".$this->fromdate."','".$this->todate."') as debit,
            accountclosingbalance(".$this->yearcode.",t.accountcode,'".$this->todate."') as closingbalance
            from accounthead t where groupcode".$this->invl($groupcode,true,false,'=').
            " and subgroupcode".$this->invl($subgroupcode,true,false,'=').
            " and subsubgroupcode".$this->invl($subsubgroupcode,true,false,'=')."))
            where not(openingbalance_cr=0 
            and openingbalance_dr=0 
            and credit=0 
            and debit=0 
            and closingbalance_cr=0
            and closingbalance_dr=0)";
        }
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $openingbalance_cr=0; 
        $openingbalance_dr=0; 
        $credit=0; 
        $debit=0; 
        $closingbalance_cr=0;
        $closingbalance_dr=0;
        if ($subgroupcode=='' and $subsubgroupcode=='')
        {
            $rel = '';
        }
        elseif ($subgroupcode!='' and $subsubgroupcode=='')
        {
            $rel = '->';
        }
        elseif ($subgroupcode!='' and $subsubgroupcode!='')
        {
            $rel = '-->';
        }
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->pdf->multicell(100,10,$rel.' एकूण',0,'L',false,1,32,$this->liney,true,0,false,true,10);
            $this->pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
            $this->pdf->multicell(35,10,$this->moneyFormatIndia($row['CLOSINGBALANCE_CR']),0,'R',false,1,165,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(35,10,$this->moneyFormatIndia($row['CLOSINGBALANCE_DR']),0,'R',false,1,130,$this->liney,true,0,false,true,10);
            $this->liney = $this->liney+5;
            $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
            $this->pdf->line(10,$this->liney,202,$this->liney);
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

    public function iscashbankexists()
    {
        $query = "select nvl(sum(total),0) as total
        from (
        select voucherdate,total
        from vw_daybook_credit_account_sum 
        union all
        select voucherdate,total
        from vw_daybook_debit_account_sum)
        where voucherdate='".$this->daybookdate."'";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {  
            return $row['TOTAL'];
        }
        else
        {
            return 0;
        }
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