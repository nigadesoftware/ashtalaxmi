<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    include_once("../ip_model/accounthead_db_oracle.php");
    include_once("../ip_model/voucherheader_db_oracle.php");

class cashposition extends swappreport
{	
    public $connection;
    public $liney;
    public $maxlines;
    public $pdf;
    public $fromdate;
    public $todate;
    public $yearcode;
    public $closingbalance_cr;
    public $closingbalance_dr;
    public $closingbalance;

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
        $this->pdf->SetSubject('Cash Position');
        $this->pdf->SetKeywords('CHPOS_000.MR');
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
        $this->closingbalance_cr=0;
        $this->closingbalance_dr=0;
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
                    $this->drawlines(240);
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
                $this->drawlines(240);
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
        $this->pdf->Output('CHPOS_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 15;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->pdf->SetFont('siddhanta', '', 15, '', true);
        $this->pdf->multicell(60,10,'कॅश पोजिशन',0,'R',false,1,55,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+7;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $frdt = DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y');
        $todt = DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y');
        $this->pdf->multicell(50,10,'दिनांक '.$todt.' अखेर',0,'R',false,1,75,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+7;
        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
        $this->pdf->line(10,$this->liney,190,$this->liney);
        $this->liney = $this->liney+2;
       	$this->pdf->multicell(90,10,'खाते तपशील',0,'L',false,1,40,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(70,10,'अखेरची शिल्लक',0,'L',false,1,150,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+5;
       	$this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
        $this->pdf->line(130,$this->liney,190,$this->liney);
        $this->pdf->multicell(35,10,'नावे / बँकेतील शिल्लक',0,'R',false,1,125,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(35,10,'जमा / कर्ज',0,'R',false,1,155,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+10;
        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
        $this->pdf->line(10,$this->liney,190,$this->liney);
    }

function drawlines($limit)
{
    $liney = $this->liney;
    $this->liney = 41;
    $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
    $this->pdf->line(30,$this->liney+5,30,$this->liney+$limit);
    $this->pdf->line(160,$this->liney-5,160,$this->liney+$limit);
    $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
    $this->pdf->line(10,$this->liney-12,10,$this->liney+$limit);
    $this->pdf->line(130,$this->liney-12,130,$this->liney+$limit);
    $this->pdf->line(190,$this->liney-12,190,$this->liney+$limit);
    $this->pdf->line(10,$this->liney+$limit,190,$this->liney+$limit);
    $this->liney = $liney;
}

function pagefooter($islastpage = false)
    {
        $this->drawlines($this->liney-41);
        $this->grandfooter();
        $this->liney = $this->liney+10; 
        $this->pdf->SetFont('siddhanta', '', 12, '', true);
        $this->pdf->multicell(300,10,'              तयार करणार        तपासणार       चिफ अकौंटंट       जनरल मॅनेजर ',0,'L',false,1,5,$this->liney,true,0,false,true,10);
    }

    function group()
    {
        $query_group ="select * 
        from accountgroup a,accountcontroltable t 
        where a.groupcode in (7,30) and 
        nvl(groupclosingbalance(".$this->yearcode.",a.groupcode,'".$this->todate."'),0)<>0
        order by groupcode";
        $result_group = oci_parse($this->connection, $query_group);
        $r = oci_execute($result_group);
        while ($row_group = oci_fetch_array($result_group,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->pdf->SetFont('siddhanta', '', 13, '', true);
            $height = $this->height($row_group['GROUPNAMEUNI'],100);
            if ($this->isnewpage($height))
            {
                $this->newpage(True);
            }
            $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
            $this->pdf->multicell(25,10,$row_group['GROUPCODE'],0,'L',false,1,10,$this->liney,true,0,false,true,10);
            $this->pdf->SetFont('siddhanta', '', 13, '', true);
            $this->pdf->multicell(100,10,$row_group['GROUPNAMEUNI'],0,'L',false,1,32,$this->liney,true,0,false,true,$height);
            $this->liney = $this->liney+5;
            $query_subgroup ="select * from accountsubgroup g where g.groupcode=".$row_group['GROUPCODE']." order by subgroupcode";
            $result_subgroup = oci_parse($this->connection, $query_subgroup);
            $r = oci_execute($result_subgroup);
            $subnorec=0;
            while ($row_subgroup = oci_fetch_array($result_subgroup,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                /* $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
                $this->pdf->line(10,$this->liney,190,$this->liney);
                $this->pdf->SetFont('siddhanta', '', 11, '', true);
                $height = $this->height('->'.$row_subgroup['SUBGROUPNAMEUNI'],100);
                if ($this->isnewpage($height))
                {
                    $this->newpage(True);
                }
                $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
                $this->pdf->multicell(25,10,$row_subgroup['SUBGROUPCODE'],0,'L',false,1,10,$this->liney,true,0,false,true,10);
                $height = $this->height($row_subgroup['SUBGROUPNAMEUNI'],100);
                $this->pdf->SetFont('siddhanta', '', 11, '', true);
                $this->pdf->multicell(100,10,'->'.$row_subgroup['SUBGROUPNAMEUNI'],0,'L',false,1,32,$this->liney,true,0,false,true,$height);
                $this->liney = $this->liney+5; */
                $subnorec++;
                $query_subsubgroup ="select * from accountsubsubgroup g where g.groupcode=".$row_group['GROUPCODE']." and g.subgroupcode=".$row_subgroup['SUBGROUPCODE']." order by subsubgroupcode";
                $result_subsubgroup = oci_parse($this->connection, $query_subsubgroup);
                $r = oci_execute($result_subsubgroup);
                $subsubnorec=0;
                while ($row_subsubgroup = oci_fetch_array($result_subsubgroup,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    /* $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
                    $this->pdf->line(10,$this->liney,190,$this->liney);
                    $this->pdf->SetFont('siddhanta', '', 11, '', true);
                    $height = $this->height('  '.$row_subsubgroup['SUBSUBGROUPNAMEUNI'],100);
                    if ($this->isnewpage($height))
                    {
                        $this->newpage(True);
                    }
                    $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
                    $this->pdf->multicell(25,10,$row_subsubgroup['SUBSUBGROUPCODE'],0,'L',false,1,10,$this->liney,true,0,false,true,10);
                    $height = $this->height($row_subsubgroup['SUBSUBGROUPNAMEUNI'],100);
                    $this->pdf->SetFont('siddhanta', '', 11, '', true);
                    $this->pdf->multicell(100,10,'  '.$row_subsubgroup['SUBSUBGROUPNAMEUNI'],0,'L',false,1,32,$this->liney,true,0,false,true,$height);
                    $this->liney = $this->liney+5; */
                    $subsubnorec++;
                    $this->detail($row_group['GROUPCODE'],$row_subgroup['SUBGROUPCODE'],$row_subsubgroup['SUBSUBGROUPCODE']);
                    $this->liney = $this->liney+5;
                    $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
                    $this->pdf->line(10,$this->liney,190,$this->liney);
                    $this->groupfooter($row_group['GROUPCODE'],$row_subgroup['SUBGROUPCODE'],$row_subsubgroup['SUBSUBGROUPCODE']);
                }
                if ($subsubnorec==0)
                {
                    $this->detail($row_group['GROUPCODE'],$row_subgroup['SUBGROUPCODE'],'');
                    $this->liney = $this->liney+5;
                    $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
                    $this->pdf->line(10,$this->liney,190,$this->liney);
                    $this->liney = $this->liney-5;
                }
                $this->groupfooter($row_group['GROUPCODE'],$row_subgroup['SUBGROUPCODE'],'');
            }
            if ($subsubnorec==0)
            {
                $this->detail($row_group['GROUPCODE'],'','');
            }
            $this->groupfooter($row_group['GROUPCODE'],'','');
        }
        $this->pagefooter();
    }

	function detail($groupcode,$subgroupcode,$subsubgroupcode)
    {
        $query = "select * from (select accountcode,accountnameuni,
        case when openingbalance<0 then abs(openingbalance) else 0 end openingbalance_cr
        ,case when openingbalance>0 then openingbalance else 0 end openingbalance_dr
        ,credit
        ,debit
        ,case when closingbalance<0 then abs(closingbalance) else 0 end closingbalance_cr
        ,case when closingbalance>0 then closingbalance else 0 end closingbalance_dr
        from (
        select t.accountcode,accountnameuni,
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
        and closingbalance_dr=0)
        order by accountcode";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $openingbalance_cr=0; 
        $openingbalance_dr=0; 
        $credit=0; 
        $debit=0; 
        
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($row['CLOSINGBALANCE_DR']>0 or $row['CLOSINGBALANCE_CR']>0)
            {
                $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
                $this->pdf->line(10,$this->liney,190,$this->liney);
                $this->pdf->SetFont('siddhanta', '', 10, '', true);
                $height = $this->height('     '.$row['ACCOUNTNAMEUNI'],100);
                if ($this->isnewpage($height))
                {
                    $this->newpage(True);
                }
                $this->pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
                $this->pdf->multicell(25,10,$row['ACCOUNTCODE'],0,'L',false,1,10,$this->liney,true,0,false,true,10);
                $height = $this->height($row['ACCOUNTNAMEUNI'],100);
                $this->pdf->SetFont('siddhanta', '', 10, '', true);
                $this->pdf->multicell(100,10,'    '.$row['ACCOUNTNAMEUNI'],0,'L',false,1,32,$this->liney,true,0,false,true,$height);
                $this->pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
                $this->pdf->multicell(35,10,$this->moneyFormatIndia($row['CLOSINGBALANCE_DR']),0,'R',false,1,125,$this->liney,true,0,false,true,10);
                $this->pdf->multicell(35,10,$this->moneyFormatIndia($row['CLOSINGBALANCE_CR']),0,'R',false,1,155,$this->liney,true,0,false,true,10);
                $this->liney = $this->liney+$height+1;
            }
        }
        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
        $this->pdf->line(10,$this->liney,190,$this->liney);
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        if ($this->isnewpage(20))
        {
            $this->newpage(True);
        }
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
            $rel = ' ';
        }
        elseif ($subgroupcode!='' and $subsubgroupcode!='')
        {
            $rel = '  ';
        }
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($subgroupcode == '' and $subsubgroupcode == '')
            {
                $this->pdf->multicell(100,10,$rel.'एकूण',0,'L',false,1,32,$this->liney,true,0,false,true,10);
                $this->pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
                $this->pdf->multicell(35,10,$this->moneyFormatIndia($row['CLOSINGBALANCE_DR']),0,'R',false,1,125,$this->liney,true,0,false,true,10);
                $this->pdf->multicell(35,10,$this->moneyFormatIndia($row['CLOSINGBALANCE_CR']),0,'R',false,1,155,$this->liney,true,0,false,true,10);
                $this->liney = $this->liney+5;
                $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
                $this->pdf->line(10,$this->liney,190,$this->liney);
                $this->closingbalance_cr = $this->closingbalance_cr+$row['CLOSINGBALANCE_CR'];
                $this->closingbalance_dr = $this->closingbalance_dr+$row['CLOSINGBALANCE_DR'];
            }
        }
    }
    Public function grandfooter()
    {
        $this->pdf->multicell(100,10,$rel.'एकूण एकंदर',0,'L',false,1,32,$this->liney,true,0,false,true,10);
        $this->pdf->SetFont('SakalMarathiNormal922', '', 9, '', true);
        $this->pdf->multicell(35,10,$this->moneyFormatIndia($this->closingbalance_dr),0,'R',false,1,125,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(35,10,$this->moneyFormatIndia($this->closingbalance_cr),0,'R',false,1,155,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+5;
        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
        $this->pdf->line(10,$this->liney,190,$this->liney);
    }
    public function isbankaccountcode($accountcode)
    {
        $query = "select count(*) cnt
       from accounthead a,accountcontroltable t 
       where a.groupcode in (7,30) and 
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