<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_legal_l.php");
    include_once("../info/routine.php");
    include_once("../ip_model/accounthead_db_oracle.php");
    include_once("../ip_model/voucherheader_db_oracle.php");
//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class pandl extends swappreport
{	
    public $fromdatecur;
    public $fromdatepre;
    public $todatecur;
    public $todatepre;    
    public $yearcodecur;
    public $yearcodepre;
    public $profitcur;
    public $losscur;
    public $profitpre;
    public $losspre;
    public $lastcreditpage;
    public $lastcreditlocation;
    public $lastdebitpage;
    public $lastdebitlocation;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
    	// create new PDF document
	    $this->pdf = new MYPDF('L', PDF_UNIT, 'A3', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Profit and Loss Statement');
        $this->pdf->SetKeywords('PANDL_000.MR');
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

    function endreport()
    {
        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output('PANDL_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 15;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        $this->textbox('नफातोटा पत्रक',400,10,'S','C',1,'siddhanta',15);
        $this->newrow();
        //$frdt = DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y');
        $todt = DateTime::createFromFormat('d-M-Y',$this->todatecur)->format('d/m/Y');
        $this->textbox('दिनांक '.$fromdt.' पासुन '.$todt.' पर्यंत',400,10,'S','C',1,'siddhanta',13);
        $this->newrow();
        $this->textbox('खर्च',100,50,'S','C',1,'siddhanta',15);
        $this->textbox('उत्पन्न',100,250,'S','C',1,'siddhanta',15);
        $this->newrow(7);
        $this->hline(10,400);
        $this->newrow(2);
        $this->textbox('मागील शिल्लक',40,10,'S','C',1,'siddhanta',13);
        $this->textbox('तपशील',100,50,'S','C',1,'siddhanta',13);
        $this->textbox('चालू शिल्लक',40,160,'S','C',1,'siddhanta',13);
        $this->textbox('मागील शिल्लक',40,210,'S','C',1,'siddhanta',13);
        $this->textbox('तपशील',100,250,'S','C',1,'siddhanta',13);
        $this->textbox('चालू शिल्लक',40,360,'S','C',1,'siddhanta',13);
        $this->hline(10,400,$this->liney+6,'C');
        $this->newrow();
    }

    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        $liney = $this->liney;
        $this->liney = 48;
        $this->vline($this->liney-12,$this->liney+$limit,10);
        $this->vline($this->liney-12,$this->liney+$limit,50,'D');
        $this->vline($this->liney-12,$this->liney+$limit,130,'D');
        $this->vline($this->liney-12,$this->liney+$limit,160,'D');
        $this->vline($this->liney-12,$this->liney+$limit,205);
        $this->hline(10,200,$this->liney+$limit);
        $this->vline($this->liney-12,$this->liney+$limit,250,'D');
        $this->vline($this->liney-12,$this->liney+$limit,330,'D');
        $this->vline($this->liney-12,$this->liney+$limit,360,'D');
        $this->vline($this->liney-12,$this->liney+$limit,400);
        $this->hline(10,400,$this->liney+$limit);
        $this->liney = $liney;
    }

    function pagefooter($islastpage = false)
    {
        $this->drawlines($this->liney-48);
        $this->liney = 270; 
        $this->pdf->SetFont('siddhanta', '', 12, '', true);
        $this->pdf->multicell(400,10,'                                              तयार करणार                         तपासणार                        चिफ अकौंटंट                          जनरल मॅनेजर ',0,'L',false,1,5,$this->liney,true,0,false,true,10);
    }

    function expenses()
    {
        $query_group ="select groupcode
        ,groupnameuni
        ,groupnameeng
        ,(nvl(groupclosingbalance(".$this->yearcodecur.",g.groupcode,'".$this->todatecur."') ,0)
        -nvl(groupopeningbalance(".$this->yearcodecur.",g.groupcode,'".$this->fromdatecur."'),0)) as groupclosingbalancecur
        ,nvl(groupclosingbalance(".$this->yearcodepre.",g.groupcode,'".$this->todatepre."'),0)
        - nvl(groupopeningbalance(".$this->yearcodepre.",g.groupcode,'".$this->fromdatepre."') ,0) as groupclosingbalancepre
        from accountgroup g where grouptypecode=3
        and groupcode not in(23,27)
        order by sequencenumber";
        $result_group = oci_parse($this->connection, $query_group);
        $r = oci_execute($result_group);
        $srno=1;
        while ($row_group = oci_fetch_array($result_group,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->textbox($row_group['GROUPCLOSINGBALANCEPRE'],40,10,'C','R',1,'SakalMarathiNormal922',11);
            $this->textbox(' '.$srno++.')'.$row_group['GROUPNAMEUNI'],100,50,'S','L',1,'siddhanta',11);
            $this->textbox($row_group['GROUPCLOSINGBALANCECUR'],40,160,'C','R',1,'SakalMarathiNormal922',11);  

            //$this->();
            $query_subgroup ="select subgroupcode
            ,subgroupnameuni
            ,subgroupnameeng
            ,nvl(subgroupclosingbalance(".$this->yearcodecur.",g.subgroupcode,'".$this->todatecur."'),0) 
             - nvl(subgroupopeningbalance(".$this->yearcodecur.",g.subgroupcode,'".$this->fromdatecur."') ,0) as subgroupclosingbalancecur
            ,nvl(subgroupclosingbalance(".$this->yearcodepre.",g.subgroupcode,'".$this->todatepre."') ,0)
             - nvl(subgroupopeningbalance(".$this->yearcodepre.",g.subgroupcode,'".$this->fromdatepre."'),0) as ssubgroupopeningbalancepre
            from accountsubgroup g 
            where g.groupcode=".$row_group['GROUPCODE']." 
            order by sequencenumber";
            $result_subgroup = oci_parse($this->connection, $query_subgroup);
            $r = oci_execute($result_subgroup);
            $subsrno=1;
            while ($row_subgroup = oci_fetch_array($result_subgroup,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                //$this->hline(10,200,$this->liney,'D');
                $this->newrow(7);
                $this->textbox($row_subgroup['SUBGROUPCLOSINGBALANCEPRE'],40,10,'C','R',1,'SakalMarathiNormal922',10);
                $this->textbox('    '.$subsrno++.')'.$row_subgroup['SUBGROUPNAMEUNI'],100,50,'S','L',1,'siddhanta',11);
                $this->textbox($row_subgroup['SUBGROUPCLOSINGBALANCECUR'],32,128,'C','R',1,'SakalMarathiNormal922',10);  
                $subnorec++;
            }
            $this->newrow(10);
        }
        if ($this->profitcur>0 or $this->profitpre>0) 
        {
            $this->textbox($this->profitpre,40,10,'C','R',1,'SakalMarathiNormal922',10);
            $this->textbox(''.$srno++.')'.'निव्वळ नफा',100,50,'S','L',1,'siddhanta',11);
            $this->textbox($this->profitcur,40,160,'C','R',1,'SakalMarathiNormal922',10);  
        }
        $this->lastcreditpage = $this->currentpage;
        $this->lastcreditlocation = $this->liney;
    }

  function income()
    {
        $query_group ="select groupcode
        ,groupnameuni
        ,groupnameeng
        ,(nvl(groupclosingbalance(".$this->yearcodecur.",g.groupcode,'".$this->todatecur."') ,0)
        -nvl(groupopeningbalance(".$this->yearcodecur.",g.groupcode,'".$this->fromdatecur."'),0))*-1 as groupclosingbalancecur
        ,(nvl(groupclosingbalance(".$this->yearcodepre.",g.groupcode,'".$this->todatepre."'),0)
        - nvl(groupopeningbalance(".$this->yearcodepre.",g.groupcode,'".$this->fromdatepre."') ,0))*-1 as groupclosingbalancepre
        from accountgroup g where grouptypecode=4
        and groupcode not in(23,27)
        order by sequencenumber";
        $result_group = oci_parse($this->connection, $query_group);
        $r = oci_execute($result_group);
        $srno=1;
        if ($this->totalpages>=1)
        {
            $this->currentpage=1;
        }
        $this->pdf->setpage($this->currentpage);
        $this->liney = 48;
        while ($row_group = oci_fetch_array($result_group,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->textbox($row_group['GROUPCLOSINGBALANCEPRE'],40,210,'C','R',1,'SakalMarathiNormal922',11);
            $this->textbox(' '.$srno++.')'.$row_group['GROUPNAMEUNI'],100,250,'S','L',1,'siddhanta',11);
            $this->textbox($row_group['GROUPCLOSINGBALANCECUR'],40,360,'C','R',1,'SakalMarathiNormal922',11);  

            //$this->();
            $query_subgroup ="select subgroupcode
            ,subgroupnameuni
            ,subgroupnameeng
            ,(nvl(subgroupclosingbalance(".$this->yearcodecur.",g.subgroupcode,'".$this->todatecur."'),0) 
             - nvl(subgroupopeningbalance(".$this->yearcodecur.",g.subgroupcode,'".$this->fromdatecur."') ,0))*-1 as subgroupclosingbalancecur
            ,(nvl(subgroupclosingbalance(".$this->yearcodepre.",g.subgroupcode,'".$this->todatepre."') ,0)
             - nvl(subgroupopeningbalance(".$this->yearcodepre.",g.subgroupcode,'".$this->fromdatepre."'),0))*-1 as subgroupopeningbalancepre
            from accountsubgroup g 
            where g.groupcode=".$row_group['GROUPCODE']." 
            order by sequencenumber";
            $result_subgroup = oci_parse($this->connection, $query_subgroup);
            $r = oci_execute($result_subgroup);
            $subsrno=1;
            while ($row_subgroup = oci_fetch_array($result_subgroup,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                //$this->hline(10,200,$this->liney,'D');
                $this->newrow(7);
                $this->textbox($row_subgroup['SUBGROUPCLOSINGBALANCEPRE'],40,210,'C','R',1,'SakalMarathiNormal922',10);
                $this->textbox('    '.$subsrno++.')'.$row_subgroup['SUBGROUPNAMEUNI'],100,250,'S','L',1,'siddhanta',11);
                $this->textbox($row_subgroup['SUBGROUPCLOSINGBALANCECUR'],32,328,'C','R',1,'SakalMarathiNormal922',10);  
                $subnorec++;
            }
            $this->newrow(10);
        }
        if ($this->losscur>0 or $this->losspre>0) 
        {
            $this->textbox($this->losspre,40,210,'C','R',1,'SakalMarathiNormal922',10);
            $this->textbox(''.$srno++.')'.'निव्वळ तोटा',100,250,'S','L',1,'siddhanta',11);
            $this->textbox($this->losscur,40,360,'C','R',1,'SakalMarathiNormal922',10);  
        }

        $this->lastdebitpage = $this->currentpage;
        $this->lastdebitlocation = $this->liney;

    }

    function summary()
    {
        if ($this->lastcreditpage>$this->lastdebitpage)
        {
            $this->pdf->setpage($this->lastcreditpage);
            $this->liney = $this->lastcreditlocation;
        }
        elseif ($this->lastcreditpage<$this->lastdebitpage)
        {
            $this->pdf->setpage($this->lastdebitpage);
            $this->liney = $this->lastdebitlocation;
        }
        elseif ($this->lastcreditpage==$this->lastdebitpage and $this->lastcreditlocation>=$this->lastdebitlocation)
        {
            $this->pdf->setpage($this->lastcreditpage);
            $this->liney = $this->lastcreditlocation;
        }
        elseif ($this->lastcreditpage==$this->lastdebitpage and $this->lastcreditlocation<$this->lastdebitlocation)
        {
            $this->pdf->setpage($this->lastdebitpage);
            $this->liney = $this->lastdebitlocation;
        }
        if ($this->isnewpage(31))
        {
            $this->newpage(True);
        } 
        $this->newrow(10);
        $this->vline($this->liney,$this->liney+10,10);
        $this->vline($this->liney,$this->liney+10,205);
        $this->vline($this->liney,$this->liney+10,400);
        $this->hline(10,400,$this->liney+10);  
        
        $query_sum ="select 
        sum(groupclosingbalancecur) as sumiecur
        ,sum(groupclosingbalancepre) as sumiepre
        from(select
         (nvl(groupclosingbalance(".$this->yearcodecur.",g.groupcode,'".$this->todatecur."') ,0)
        -nvl(groupopeningbalance(".$this->yearcodecur.",g.groupcode,'".$this->fromdatecur."'),0)) as groupclosingbalancecur
        ,nvl(groupclosingbalance(".$this->yearcodepre.",g.groupcode,'".$this->todatepre."'),0)
        - nvl(groupopeningbalance(".$this->yearcodepre.",g.groupcode,'".$this->fromdatepre."') ,0) as groupclosingbalancepre
        from accountgroup g where grouptypecode=3
        and groupcode not in(23,27))";
        $result_sum = oci_parse($this->connection, $query_sum);
        $r = oci_execute($result_sum);
        $srno=1;
        if ($row_sum = oci_fetch_array($result_sum,OCI_ASSOC+OCI_RETURN_NULLS))
        {
          $this->textbox($row_sum['SUMIEPRE']+$this->profitpre,40,10,'C','R',1,'SakalMarathiNormal922',10);
          $this->textbox('एकूण',100,50,'S','L',1,'siddhanta',12);
          $this->textbox($row_sum['SUMIECUR']+$this->profitcur,40,160,'C','R',1,'SakalMarathiNormal922',10);     
        }

        $query_sum ="select 
        sum(groupclosingbalancecur)*-1 as sumiecur
        ,sum(groupclosingbalancepre)*-1 as sumiepre
        from(select 
        (nvl(groupclosingbalance(".$this->yearcodecur.",g.groupcode,'".$this->todatecur."') ,0)
        -nvl(groupopeningbalance(".$this->yearcodecur.",g.groupcode,'".$this->fromdatecur."'),0)) as groupclosingbalancecur
        ,nvl(groupclosingbalance(".$this->yearcodepre.",g.groupcode,'".$this->todatepre."'),0)
        - nvl(groupopeningbalance(".$this->yearcodepre.",g.groupcode,'".$this->fromdatepre."') ,0) as groupclosingbalancepre
        from accountgroup g where grouptypecode=4
        and groupcode not in(23,27))";
        $result_sum = oci_parse($this->connection, $query_sum);
        $r = oci_execute($result_sum);
        $srno=1;
        if ($row_sum = oci_fetch_array($result_sum,OCI_ASSOC+OCI_RETURN_NULLS))
        {
          $this->textbox($row_sum['SUMIEPRE']+$this->losspre,40,210,'C','R',1,'SakalMarathiNormal922',10);
          $this->textbox('एकूण',100,250,'S','L',1,'siddhanta',12);
          $this->textbox($row_sum['SUMIECUR']+$this->losscur,40,360,'C','R',1,'SakalMarathiNormal922',10);     
        }
              
    }
}    
?>