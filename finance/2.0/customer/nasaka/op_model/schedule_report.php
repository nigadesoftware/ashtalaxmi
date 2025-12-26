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
class schedule extends swappreport
{	
    public $todatecur;
    public $todatepre;
    public $yearcodecur;
    public $yearcodepre;
    public $schedulenameuni;
    public $schedulenameeng;
    public $grouptypecode;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
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
        $this->pdf->SetSubject('Schedule');
        $this->pdf->SetKeywords('SCH_000.MR');
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
        $this->pdf->Output('SCH_000.pdf', 'I');
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
        $this->textbox('शेड्युल',190,10,'S','C',1,'siddhanta',13);
        $this->newrow();
        $this->textbox($this->schedulenameuni,190,10,'S','C',1,'siddhanta',12);
        $this->newrow();
        //$frdt = DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y');
        $todt = DateTime::createFromFormat('d-M-Y',$this->todatecur)->format('d/m/Y');
        $this->textbox('दिनांक '.$todt.' पर्यंत',190,10,'S','C',1,'siddhanta',11);
        $this->newrow();
        $this->hline(10,200);
        $this->newrow(2);
        $this->textbox('मागील शिल्लक',60,10,'S','C',1,'siddhanta',12);
        $this->textbox('तपशील',70,70,'S','C',1,'siddhanta',12);
        $this->textbox('चालू शिल्लक',60,140,'S','C',1,'siddhanta',12);
        $this->hline(10,70,$this->liney+6,'D');
        $this->hline(140,200,$this->liney+6,'D');
        $this->newrow();
        $this->textbox('नावे',30,10,'S','R',1,'siddhanta',11);
        $this->textbox('जमा',30,40,'S','R',1,'siddhanta',11);
        $this->textbox('नावे',30,140,'S','R',1,'siddhanta',11);
        $this->textbox('जमा',30,170,'S','R',1,'siddhanta',11);
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow();
    }

    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        $liney = $this->liney;
        $this->liney = 48;
        $this->vline($this->liney-12,$this->liney+$limit,10);
        $this->vline($this->liney-4,$this->liney+$limit,40,'D');
        $this->vline($this->liney-12,$this->liney+$limit,70,'D');
        $this->vline($this->liney-12,$this->liney+$limit,140,'D');
        $this->vline($this->liney-4,$this->liney+$limit,170,'D');
        $this->vline($this->liney-12,$this->liney+$limit,200);
        $this->hline(10,200,$this->liney+$limit);
        $this->liney = $liney;
    }

    function pagefooter($islastpage = false)
    {
        $this->drawlines($this->liney-48);
        $this->liney = $this->liney+15; 
        $this->pdf->SetFont('siddhanta', '', 12, '', true);
        $this->pdf->multicell(170,10,'       तयार करणार        तपासणार         चिफ अकौंटंट          जनरल मॅनेजर ',0,'L',false,1,5,$this->liney,true,0,false,true,10);
    }

    function schedule()
    {
        $query_schedule ="select schedulecode
        ,schedulenumber
        ,schedulenameuni
        ,schedulenameeng
        ,case when scheduleclosingbalancecur<0 then abs(scheduleclosingbalancecur) else 0 end scheduleclosingbalancecur_cr
        ,case when scheduleclosingbalancecur>0 then scheduleclosingbalancecur else 0 end scheduleclosingbalancecur_dr
        ,case when scheduleclosingbalancepre<0 then abs(scheduleclosingbalancepre) else 0 end scheduleclosingbalancepre_cr
        ,case when scheduleclosingbalancepre>0 then scheduleclosingbalancepre else 0 end scheduleclosingbalancepre_dr
        from (
        select schedulecode
        ,schedulenumber
        ,schedulenameuni
        ,schedulenameeng
        ,scheduleclosingbalance(".$this->yearcodecur.",g.schedulecode,'".$this->todatecur."') as scheduleclosingbalancecur 
        ,scheduleclosingbalance(".$this->yearcodepre.",g.schedulecode,'".$this->todatepre."') as scheduleclosingbalancepre 
        from accountschedule g where g.grouptypecode=".$this->grouptypecode.")
        where not(scheduleclosingbalancecur=0 and scheduleclosingbalancepre=0)
        order by schedulecode";
        $result_schedule = oci_parse($this->connection, $query_schedule);
        $r = oci_execute($result_schedule);
        while ($row_schedule = oci_fetch_array($result_schedule,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->schedulenameuni=$row_schedule['SCHEDULENUMBER'].' '.$row_schedule['SCHEDULENAMEUNI'];
            $this->newpage(True);
            //$this->();
            $query_subschedule = "select * 
            from accountsubschedule g 
            where g.schedulecode=".$row_schedule['SCHEDULECODE']." 
            and not(nvl(subscheduleclosingbalance(".$this->yearcodecur.",g.subschedulecode,'".$this->todatecur."'),0) =0
            and nvl(subscheduleclosingbalance(".$this->yearcodepre.",g.subschedulecode,'".$this->todatepre."'),0)=0)
            order by subschedulecode";
            $result_subschedule = oci_parse($this->connection, $query_subschedule);
            $r = oci_execute($result_subschedule);
            $subnorec=0;
            $subsrno=1;
            $ret=0;
            while ($row_subschedule = oci_fetch_array($result_subschedule,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                $this->hline(10,200,$this->liney,'D');
                $this->textbox(' '.$subsrno++.')'.$row_subschedule['SUBSCHEDULENAMEUNI'],70,70,'S','L',1,'siddhanta',11);
                $this->newrow(5);
                $subnorec++;
                $query_subsubschedule ="select * from accountsubsubschedule g 
                where g.schedulecode=".$row_schedule['SCHEDULECODE']." 
                and g.subschedulecode=".$row_subschedule['SUBSCHEDULECODE']." 
                and not(nvl(subsubscheduleclosingbalance(".$this->yearcodecur.",g.subsubschedulecode,'".$this->todatecur."'),0) =0
                and nvl(subsubscheduleclosingbalance(".$this->yearcodepre.",g.subsubschedulecode,'".$this->todatepre."'),0)=0)
                order by subsubschedulecode";
                $result_subsubschedule = oci_parse($this->connection, $query_subsubschedule);
                $r = oci_execute($result_subsubschedule);
                $subsubnorec=0;
                $subsubsrno=1;
                while ($row_subsubschedule = oci_fetch_array($result_subsubschedule,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    $this->hline(10,200,$this->liney,'D');
                    $this->textbox('  '.$subsubsrno++.')'.$row_subsubschedule['SUBSUBSCHEDULENAMEUNI'],70,70,'S','L',1,'siddhanta',11);
                    $this->newrow(5);
                    $subsubnorec++;
                    $ret = $this->detail($row_schedule['SCHEDULECODE'],$row_subschedule['SUBSCHEDULECODE'],$row_subsubschedule['SUBSUBSCHEDULECODE']);
                    $this->liney = $this->liney+5;
                    $this->hline(10,200,$this->liney,'D');
                    //$this->schedulefooter($row_schedule['SCHEDULECODE'],$row_subschedule['SUBSCHEDULECODE'],$row_subsubschedule['SUBSUBSCHEDULECODE']);
                }
                if ($subsubnorec==0)
                {
                    $ret = $this->detail($row_schedule['SCHEDULECODE'],$row_subschedule['SUBSCHEDULECODE'],'');
                    $this->newrow(5);
                    $this->hline(10,200,$this->liney,'D');
                }
                if ($subsubnorec>=1)
                {
                    $this->newrow(5);
                    $this->schedulefooter($row_schedule['SCHEDULECODE'],$row_subschedule['SUBSCHEDULECODE'],'');
                }
            }
            if ($subnorec==0)
            {
                $ret=$this->detail($row_schedule['SCHEDULECODE'],'','');
            }
            $this->newrow(5);
            $this->hline(10,200,$this->liney,'C');
            $this->schedulefooter($row_schedule['SCHEDULECODE'],'','');
            if ($row_schedule['SCHEDULECLOSINGBALANCEPRE_CR']>0)
            {
                $this->textbox($row_schedule['SCHEDULECLOSINGBALANCEPRE_CR'],30,40,'C','R',1,'SakalMarathiNormal922',9);
            }
            else
            {
                $this->textbox($row_schedule['SCHEDULECLOSINGBALANCEPRE_DR'],30,10,'C','R',1,'SakalMarathiNormal922',9);
            }
            $ret = $this->textbox('शेड्युल शिल्लक',70,70,'S','R',1,'siddhanta',12,'','Y');
            if ($row_schedule['SCHEDULECLOSINGBALANCECUR_CR']>0)
            {
                $this->textbox($row_schedule['SCHEDULECLOSINGBALANCECUR_CR'],30,170,'C','R',1,'SakalMarathiNormal922',9);
            }
            else
            {
                $this->textbox($row_schedule['SCHEDULECLOSINGBALANCECUR_DR'],30,140,'C','R',1,'SakalMarathiNormal922',9);
            }
            $this->newrow(5);
        }
        $this->pagefooter();
    }

	function detail($schedulecode,$subschedulecode,$subsubschedulecode)
    {
        $query = "select * from (select accountcode,accountnameuni,closingbalancecur,closingbalancepre,
        case when closingbalancepre<0 then abs(closingbalancepre) else 0 end closingbalancepre_cr
        ,case when closingbalancepre>0 then closingbalancepre else 0 end closingbalancepre_dr
        ,case when closingbalancecur<0 then abs(closingbalancecur) else 0 end closingbalancecur_cr
        ,case when closingbalancecur>0 then closingbalancecur else 0 end closingbalancecur_dr
        from (
            select t.accountcode,accountnameuni,closingbalancecur,closingbalancepre
            from (select accountcode,(nvl(sum(debitbalancecur),0)-nvl(sum(creditbalancecur),0)) as closingbalancecur,(nvl(sum(debitbalancepre),0)-nvl(sum(creditbalancepre),0)) as closingbalancepre
                  from (
                  select a.accountcode,nvl(a.creditbalance,0) as creditbalancecur,nvl(a.debitbalance,0) as debitbalancecur,0 as creditbalancepre,0 as debitbalancepre  
                  from accountopening a
                  where a.yearperiodcode=".$this->yearcodecur." 
                  union all
                  select d.accountcode,nvl(sum(d.credit),0) as creditbalancecur,nvl(sum(d.debit),0) as debitbalancecur,0 as creditbalancepre,0 as debitbalancepre  
                  from voucherheader t,voucherdetail d
                  where t.transactionnumber=d.transactionnumber 
                  and t.yearperiodcode=".$this->yearcodecur." 
                  and t.voucherdate<='".$this->todatecur."'
                  and t.approvalstatus=9
                  group by d.accountcode
                  union all
                  select a.accountcode,0 as creditbalancecur,0 as debitbalancecur,nvl(a.creditbalance,0) as creditbalancepre,nvl(a.debitbalance,0) as debitbalancepre 
                  from accountopening a
                  where a.yearperiodcode=".$this->yearcodepre." 
                  union all
                  select d.accountcode,0 as creditbalancecur,0 as debitbalancecur,nvl(sum(d.credit),0) as creditbalancepre,nvl(sum(d.debit),0) as debitbalancepre
                  from voucherheader t,voucherdetail d
                  where t.transactionnumber=d.transactionnumber 
                  and t.yearperiodcode=".$this->yearcodepre." 
                  and t.voucherdate<='".$this->todatepre."'
                  and t.approvalstatus=9
                  group by d.accountcode)
                  group by accountcode
                  )tt,accounthead t where
                  tt.accountcode=t.accountcode and schedulecode".$this->invl($schedulecode,true,false,'=').
        " and subschedulecode".$this->invl($subschedulecode,true,false,'=').
        " and subsubschedulecode".$this->invl($subsubschedulecode,true,false,'=').")
        where not(closingbalancecur=0
        and closingbalancepre=0)
        )
        order by accountcode";
      /* "select * from (select accountcode,accountnameuni,closingbalancecur,closingbalancepre,
        case when closingbalancepre<0 then abs(closingbalancepre) else 0 end closingbalancepre_cr
        ,case when closingbalancepre>0 then closingbalancepre else 0 end closingbalancepre_dr
        ,case when closingbalancecur<0 then abs(closingbalancecur) else 0 end closingbalancecur_cr
        ,case when closingbalancecur>0 then closingbalancecur else 0 end closingbalancecur_dr
        from (
        select t.accountcode,accountnameuni,
        accountclosingbalance(".$this->yearcodecur.",t.accountcode,'".$this->todatecur."') as closingbalancecur,
        accountclosingbalance(".$this->yearcodepre.",t.accountcode,'".$this->todatepre."') as closingbalancepre
        from accounthead t where schedulecode".$this->invl($schedulecode,true,false,'=').
        " and subschedulecode".$this->invl($subschedulecode,true,false,'=').
        " and subsubschedulecode".$this->invl($subsubschedulecode,true,false,'=').")
        where not(closingbalancecur=0
        and closingbalancepre=0)
        )
        order by accountcode"; */
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $openingbalance_cr=0; 
        $openingbalance_dr=0; 
        $closingbalance_cr=0;
        $closingbalance_dr=0;
        $acsrno=0;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->hline(10,200,$this->liney,'D');
            $this->textbox($row['CLOSINGBALANCEPRE_CR'],30,40,'C','R',1,'SakalMarathiNormal922',10);
            $this->textbox($row['CLOSINGBALANCEPRE_DR'],30,10,'C','R',1,'SakalMarathiNormal922',10);
            $ret = $this->textbox('   '.++$acsrno.') '.$row['ACCOUNTNAMEUNI'].' ('.$row['ACCOUNTCODE'].')',70,70,'S','L',1,'siddhanta',10,'','Y');
            $this->textbox($row['CLOSINGBALANCECUR_CR'],30,170,'C','R',1,'SakalMarathiNormal922',10);
            $this->textbox($row['CLOSINGBALANCECUR_DR'],30,140,'C','R',1,'SakalMarathiNormal922',10);
            if ($ret>$this->rowheight)
            {
                $this->newrow($ret);
            }
            else
            {
                $this->newrow(5);
            }
            
        }
        //$this->newrow(-5);
        //$this->hline(10,200,$this->liney,'D');
        return $acsrno;
    }
    public function schedulefooter($schedulecode,$subschedulecode,$subsubschedulecode)
    {
        if ($subsubschedulecode == '' and $subschedulecode == '')
        {
            $query = "select 
            nvl(sum(closingbalancecur_cr),0) as closingbalancecur_cr 
            ,nvl(sum(closingbalancecur_dr),0) as closingbalancecur_dr 
            ,nvl(sum(closingbalancepre_cr),0) as closingbalancepre_cr
            ,nvl(sum(closingbalancepre_dr),0) as closingbalancepre_dr
            from (select 
            case when closingbalancecur<0 then abs(closingbalancecur) else 0 end closingbalancecur_cr
            ,case when closingbalancecur>0 then closingbalancecur else 0 end closingbalancecur_dr
            ,case when closingbalancepre<0 then abs(closingbalancepre) else 0 end closingbalancepre_cr
            ,case when closingbalancepre>0 then closingbalancepre else 0 end closingbalancepre_dr
            from (
            select 
            accountclosingbalance(".$this->yearcodecur.",t.accountcode,'".$this->todatecur."') as closingbalancecur
            ,accountclosingbalance(".$this->yearcodepre.",t.accountcode,'".$this->todatepre."') as closingbalancepre
            from accounthead t where schedulecode".$this->invl($schedulecode,true,false,'=')."))
            ";
        }
        else if ($subschedulecode != '' and $subsubschedulecode == '')
        {
            $query = "select 
            nvl(sum(closingbalancecur_cr),0) as closingbalancecur_cr 
            ,nvl(sum(closingbalancecur_dr),0) as closingbalancecur_dr 
            ,nvl(sum(closingbalancepre_cr),0) as closingbalancepre_cr
            ,nvl(sum(closingbalancepre_dr),0) as closingbalancepre_dr
            from (select 
            case when closingbalancecur<0 then abs(closingbalancecur) else 0 end closingbalancecur_cr
            ,case when closingbalancecur>0 then closingbalancecur else 0 end closingbalancecur_dr
            ,case when closingbalancepre<0 then abs(closingbalancepre) else 0 end closingbalancepre_cr
            ,case when closingbalancepre>0 then closingbalancepre else 0 end closingbalancepre_dr
            from (
            select 
            accountclosingbalance(".$this->yearcodecur.",t.accountcode,'".$this->todatecur."') as closingbalancecur
            ,accountclosingbalance(".$this->yearcodepre.",t.accountcode,'".$this->todatepre."') as closingbalancepre
            from accounthead t where schedulecode".$this->invl($schedulecode,true,false,'=').
            " and subschedulecode".$this->invl($subschedulecode,true,false,'=')."))
            ";
        }
        else if ($subschedulecode != '' and $subsubschedulecode != '')
        {
            $query = "select 
            nvl(sum(closingbalancecur_cr),0) as closingbalancecur_cr 
            ,nvl(sum(closingbalancecur_dr),0) as closingbalancecur_dr 
            ,nvl(sum(closingbalancepre_cr),0) as closingbalancepre_cr
            ,nvl(sum(closingbalancepre_dr),0) as closingbalancepre_dr
            from (select 
            case when closingbalancecur<0 then abs(closingbalancecur) else 0 end closingbalancecur_cr
            ,case when closingbalancecur>0 then closingbalancecur else 0 end closingbalancecur_dr
            ,case when closingbalancecur<0 then abs(closingbalancecur) else 0 end closingbalancepre_cr
            ,case when closingbalancecur>0 then closingbalancecur else 0 end closingbalancepre_dr
            from (
            select 
            accountclosingbalance(".$this->yearcodecur.",t.accountcode,'".$this->todatecur."') as closingbalancecur
            ,accountclosingbalance(".$this->yearcodepre.",t.accountcode,'".$this->todatepre."') as closingbalancepre
            from accounthead t where schedulecode".$this->invl($schedulecode,true,false,'=').
            " and subschedulecode".$this->invl($subschedulecode,true,false,'=').
            " and subsubschedulecode".$this->invl($subsubschedulecode,true,false,'=')."))
            ";
        }
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $openingbalance_cr=0; 
        $openingbalance_dr=0; 
        $credit=0; 
        $debit=0; 
        $closingbalance_cr=0;
        $closingbalance_dr=0;
        if ($subschedulecode=='' and $subsubschedulecode=='')
        {
            $rel = 'एकूण एकंदर';
            $this->hline(10,200,$this->liney,'D');
        }
        elseif ($subschedulecode!='' and $subsubschedulecode=='')
        {
            $rel = 'एकूण';
            $this->newrow(-5);
            $this->hline(10,200,$this->liney,'D');
        }
        elseif ($subschedulecode!='' and $subsubschedulecode!='')
        {
            $rel = 'एकूण';
            $this->newrow(-5);
            $this->hline(10,200,$this->liney,'D');
        }
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->textbox($row['CLOSINGBALANCEPRE_CR'],30,40,'C','R',1,'SakalMarathiNormal922',9);
            $this->textbox($row['CLOSINGBALANCEPRE_DR'],30,10,'C','R',1,'SakalMarathiNormal922',9);
            $this->textbox($rel,70,70,'S','R',1,'siddhanta',11);
            $this->textbox($row['CLOSINGBALANCECUR_CR'],30,170,'C','R',1,'SakalMarathiNormal922',9);
            $this->textbox($row['CLOSINGBALANCECUR_DR'],30,140,'C','R',1,'SakalMarathiNormal922',9);
            $this->newrow(5);
            $this->hline(10,200,$this->liney,'D');
        }
    }
}    
?>