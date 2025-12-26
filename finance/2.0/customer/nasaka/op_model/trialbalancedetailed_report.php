<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_legal_l.php");
    include_once("../info/routine.php");
    include_once("../ip_model/accounthead_db_oracle.php");
    include_once("../ip_model/voucherheader_db_oracle.php");
  
class trialbalancedetailed extends swappreport
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
	    $this->pdf = new MYPDF('L', PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Trial Balance Detail');
        $this->pdf->SetKeywords('TRBLDT_000.MR');
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
                    $this->drawlines(160);
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
                $this->drawlines(160);
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
        $this->pdf->multicell(60,10,'तेरीज पत्रक',0,'R',false,1,135,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+7;
        $this->pdf->SetFont('siddhanta', '', 13, '', true);
        $frdt = DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y');
        $todt = DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y');
        $this->pdf->multicell(100,10,'दिनांक '.$frdt.' पासून '.' दिनांक '.$todt.' पर्यंत',0,'R',false,1,125,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+7;
        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
        $this->pdf->line(10,$this->liney,340,$this->liney);
        $this->liney = $this->liney+2;
       	$this->pdf->multicell(60,10,'खाते तपशील',0,'L',false,1,60,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(70,10,'आरंभीची शिल्लक',0,'L',false,1,150,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(70,10,'कालावधीतील',0,'L',false,1,220,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(70,10,'अखेरची शिल्लक',0,'L',false,1,290,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+5;
       	$this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
        $this->pdf->line(10,$this->liney,340,$this->liney);
        $this->pdf->multicell(20,10,'कोड',0,'L',false,1,10,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(100,10,'खाते',0,'L',false,1,30,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(35,10,'नावे',0,'R',false,1,130,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(35,10,'जमा',0,'R',false,1,165,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(35,10,'नावे',0,'R',false,1,200,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(35,10,'जमा',0,'R',false,1,235,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(35,10,'नावे',0,'R',false,1,270,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(35,10,'जमा',0,'R',false,1,305,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+5;
        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
        $this->pdf->line(10,$this->liney,340,$this->liney);
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
    $this->pdf->line(200,$this->liney-12,200,$this->liney+$limit);
    $this->pdf->line(235,$this->liney-5,235,$this->liney+$limit);
    $this->pdf->line(270,$this->liney-12,270,$this->liney+$limit);
    $this->pdf->line(305,$this->liney-5,305,$this->liney+$limit);
    $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
    $this->pdf->line(340,$this->liney-12,340,$this->liney+$limit);
    $this->pdf->line(10,$this->liney+$limit,340,$this->liney+$limit);
    $this->liney = $liney;
}

function pagefooter($islastpage=false)
    {
        $this->drawlines($this->liney-41);
        $this->liney = $this->liney+15; 
        $this->pdf->SetFont('siddhanta', '', 12, '', true);
        $this->pdf->multicell(300,10,'                   तयार करणार                          तपासणार                            चिफ अकौंटंट                            जनरल मॅनेजर ',0,'L',false,1,5,$this->liney,true,0,false,true,10);
    }


	function detail()
    {
        $query = "select accountcode,accountnameuni,accountnameeng,
                case when openingbalance<0 then abs(openingbalance) else 0 end openingbalance_cr
                ,case when openingbalance>0 then openingbalance else 0 end openingbalance_dr
                ,credit
                ,debit
                ,case when closingbalance<0 then abs(closingbalance) else 0 end closingbalance_cr
                ,case when closingbalance>0 then closingbalance else 0 end closingbalance_dr 
                from(
                select g.groupnaturecode,g.grouptypecode,a.groupcode,a.sequencenumber,t.accountcode,h.accountnameuni,h.accountnameeng,sum(openingbalance) as openingbalance,sum(credit) as credit,sum(debit) as debit,sum(closingbalance) as closingbalance
                from (
                select accountcode,nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) as openingbalance,0 as credit,0 as debit,0 as closingbalance
                from (
                select a.accountcode,nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance 
                from accountopening a,accounthead h,accountgroup g,grouptype t
                    where a.accountcode=h.accountcode
                    and h.groupcode=g.groupcode and g.grouptypecode=t.grouptypecode
                    and t.groupnaturecode=1
                and a.yearperiodcode=".$this->yearcode." 
                union all
                select d.accountcode,nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance 
                from voucherheader t,voucherdetail d
                where t.transactionnumber=d.transactionnumber 
                and t.yearperiodcode=".$this->yearcode." 
                and t.voucherdate<'".$this->fromdate."'
                and t.approvalstatus=9
                group by d.accountcode)
                group by accountcode
                union all
                select d.accountcode,0 as openingbalance,nvl(sum(d.credit),0) as credit,0 as debit,0 as closingbalance
                from voucherheader t,voucherdetail d
                where t.transactionnumber=d.transactionnumber 
                and t.yearperiodcode=".$this->yearcode." and t.voucherdate>='".$this->fromdate."' and t.voucherdate<='".$this->todate."'
                and t.approvalstatus=9 and nvl(d.credit,0)>0
                group by accountcode
                union all
                select d.accountcode,0 as openingbalance,0 as credit,nvl(sum(d.debit),0) as debit,0 as closingbalance
                from voucherheader t,voucherdetail d
                where t.transactionnumber=d.transactionnumber 
                and t.yearperiodcode=".$this->yearcode." and t.voucherdate>='".$this->fromdate."' and t.voucherdate<='".$this->todate."'
                and t.approvalstatus=9 and nvl(d.debit,0)>0
                group by accountcode
                union all      
                select accountcode,0 as openingbalance,0 as credit,0 as debit,nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) as closingbalance
                from 
                (
                select a.accountcode,nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance 
                from accountopening a,accounthead h,accountgroup g,grouptype t
                    where a.accountcode=h.accountcode
                    and h.groupcode=g.groupcode and g.grouptypecode=t.grouptypecode
                    and t.groupnaturecode=1
                and a.yearperiodcode=".$this->yearcode." 
                union all
                select d.accountcode,nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance 
                from voucherheader t,voucherdetail d
                where t.transactionnumber=d.transactionnumber 
                and t.yearperiodcode=".$this->yearcode." 
                and t.voucherdate<='".$this->todate."'
                and t.approvalstatus=9
                group by d.accountcode
                )
                group by accountcode)t,accounthead h,accountgroup a,grouptype g
                where t.accountcode=h.accountcode
                and h.groupcode=a.groupcode and a.grouptypecode=g.grouptypecode
                group by g.groupnaturecode,g.grouptypecode,a.sequencenumber,a.groupcode,t.accountcode,h.accountnameuni,h.accountnameeng)
                order by groupnaturecode,grouptypecode,groupcode,sequencenumber,accountcode";
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
                if ($row['OPENINGBALANCE_CR']==0 and $row['OPENINGBALANCE_DR']==0 and $row['DEBIT']==0 and $row['CREDIT']==0 and $row['CLOSINGBALANCE_CR']==0 and $row['CLOSINGBALANCE_DR']==0)
                {
                    $a=0;
                }
                else
                {
                    $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
                    $this->pdf->SetFont('siddhanta', '', 11, '', true);
                    $height = $this->height($row['ACCOUNTNAMEUNI'],100);
                    if ($this->isnewpage($height))
                    {
                        $this->newpage(True);
                    }
                    $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
                    $this->pdf->multicell(25,10,$row['ACCOUNTCODE'],0,'L',false,1,10,$this->liney,true,0,false,true,10);
                    $height = $this->height($row['ACCOUNTNAMEUNI'],100);
                    $this->pdf->SetFont('siddhanta', '', 11, '', true);
                    $this->pdf->multicell(100,10,$row['ACCOUNTNAMEUNI'],0,'L',false,1,32,$this->liney,true,0,false,true,$height);
                    $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
                    $this->pdf->multicell(35,10,$this->moneyFormatIndia($row['OPENINGBALANCE_CR']),0,'R',false,1,165,$this->liney,true,0,false,true,10);
                    $this->pdf->multicell(35,10,$this->moneyFormatIndia($row['OPENINGBALANCE_DR']),0,'R',false,1,130,$this->liney,true,0,false,true,10);
                    $this->pdf->multicell(35,10,$this->moneyFormatIndia($row['CREDIT']),0,'R',false,1,235,$this->liney,true,0,false,true,10);
                    $this->pdf->multicell(35,10,$this->moneyFormatIndia($row['DEBIT']),0,'R',false,1,200,$this->liney,true,0,false,true,10);
                    $this->pdf->multicell(35,10,$this->moneyFormatIndia($row['CLOSINGBALANCE_CR']),0,'R',false,1,305,$this->liney,true,0,false,true,10);
                    $this->pdf->multicell(35,10,$this->moneyFormatIndia($row['CLOSINGBALANCE_DR']),0,'R',false,1,270,$this->liney,true,0,false,true,10);
                    $this->liney = $this->liney+$height+1;
                    $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
                    $this->pdf->line(10,$this->liney,340,$this->liney);
                    $openingbalance_cr=$openingbalance_cr+$row['OPENINGBALANCE_CR']; 
                    $openingbalance_dr=$openingbalance_dr+$row['OPENINGBALANCE_DR']; 
                    $credit=$credit+$row['CREDIT']; 
                    $debit=$debit+$row['DEBIT']; 
                    $closingbalance_cr=$closingbalance_cr+$row['CLOSINGBALANCE_CR'];
                    $closingbalance_dr=$closingbalance_dr+$row['CLOSINGBALANCE_DR'];
                }
            }
            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            if ($this->isnewpage(20))
            {
                $this->newpage(True);
            }

            $this->pdf->multicell(100,10,'एकूण',0,'L',false,1,32,$this->liney,true,0,false,true,10);
            $this->pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
            $this->pdf->multicell(35,10,$this->moneyFormatIndia($openingbalance_cr),0,'R',false,1,165,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(35,10,$this->moneyFormatIndia($openingbalance_dr),0,'R',false,1,130,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(35,10,$this->moneyFormatIndia($credit),0,'R',false,1,235,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(35,10,$this->moneyFormatIndia($debit),0,'R',false,1,200,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(35,10,$this->moneyFormatIndia($closingbalance_cr),0,'R',false,1,305,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(35,10,$this->moneyFormatIndia($closingbalance_dr),0,'R',false,1,270,$this->liney,true,0,false,true,10);
            $this->liney = $this->liney+5;
            $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
            $this->pdf->line(10,$this->liney,340,$this->liney);
            $this->pagefooter(true);
    }
    function export()
    {
           $filename='tbdetail';
           //$fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');
           $fp1=fopen('php://memory', 'w');
           $query = $query = "select accountcode,accountnameuni,
           case when openingbalance<0 then abs(openingbalance) else 0 end openingbalance_cr
           ,case when openingbalance>0 then openingbalance else 0 end openingbalance_dr
           ,credit
           ,debit
           ,case when closingbalance<0 then abs(closingbalance) else 0 end closingbalance_cr
           ,case when closingbalance>0 then closingbalance else 0 end closingbalance_dr 
           from(
           select t.accountcode,h.accountnameuni,h.accountnameeng,sum(openingbalance) as openingbalance,sum(credit) as credit,sum(debit) as debit,sum(closingbalance) as closingbalance
           from (
           select accountcode,nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) as openingbalance,0 as credit,0 as debit,0 as closingbalance
           from (
           select a.accountcode,nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance 
           from accountopening a,accounthead h,accountgroup g,grouptype t
                    where a.accountcode=h.accountcode
                    and h.groupcode=g.groupcode and g.grouptypecode=t.grouptypecode
                    and t.groupnaturecode=1
           and a.yearperiodcode=".$this->yearcode." 
           union all
           select d.accountcode,nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance 
           from voucherheader t,voucherdetail d
           where t.transactionnumber=d.transactionnumber 
           and t.yearperiodcode=".$this->yearcode." 
           and t.voucherdate<'".$this->fromdate."'
           and t.approvalstatus=9
           group by d.accountcode)
           group by accountcode
           union all
           select d.accountcode,0 as openingbalance,nvl(sum(d.credit),0) as credit,0 as debit,0 as closingbalance
           from voucherheader t,voucherdetail d
           where t.transactionnumber=d.transactionnumber 
           and t.yearperiodcode=".$this->yearcode." and t.voucherdate>='".$this->fromdate."' and t.voucherdate<='".$this->todate."'
           and t.approvalstatus=9 and nvl(d.credit,0)>0
           group by accountcode
           union all
           select d.accountcode,0 as openingbalance,0 as credit,nvl(sum(d.debit),0) as debit,0 as closingbalance
           from voucherheader t,voucherdetail d
           where t.transactionnumber=d.transactionnumber 
           and t.yearperiodcode=".$this->yearcode." and t.voucherdate>='".$this->fromdate."' and t.voucherdate<='".$this->todate."'
           and t.approvalstatus=9 and nvl(d.debit,0)>0
           group by accountcode
           union all      
           select accountcode,0 as openingbalance,0 as credit,0 as debit,nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) as closingbalance
           from 
           (
           select a.accountcode,nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance 
           from accountopening a,accounthead h,accountgroup g,grouptype t
                    where a.accountcode=h.accountcode
                    and h.groupcode=g.groupcode and g.grouptypecode=t.grouptypecode
                    and t.groupnaturecode=1
           and a.yearperiodcode=".$this->yearcode." 
           union all
           select d.accountcode,nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance 
           from voucherheader t,voucherdetail d
           where t.transactionnumber=d.transactionnumber 
           and t.yearperiodcode=".$this->yearcode." 
           and t.voucherdate<='".$this->todate."'
           and t.approvalstatus=9
           group by d.accountcode
           )
           group by accountcode)t,accounthead h
           where t.accountcode=h.accountcode
           group by t.accountcode,h.accountnameuni,h.accountnameeng)
           order by accountcode";
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $response = array();
           fputcsv($fp1, array('Account Code','Account Name','Op Bal(Cr)','Op Bal(Dr)','Credit','Debit','Cl Bal(Cr)','Cl Bal(Dr)'));
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                fputcsv($fp1, $row, $delimiter = ',', $enclosure = '"');
           }
           // reset the file pointer to the start of the file
            fseek($fp1, 0);
            // tell the browser it's going to be a csv file
            header('Content-Type: application/csv');
            // tell the browser we want to save it instead of displaying it
            header('Content-Disposition: attachment; filename="'.$filename.'";');
            // make php send the generated csv lines to the browser
            fpassthru($fp1); 
            //fclose($fp1);
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