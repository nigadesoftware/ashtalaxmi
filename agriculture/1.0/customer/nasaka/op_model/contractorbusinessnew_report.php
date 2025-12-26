<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_l.php");
    include_once("../info/routine.php");
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class contractorbusiness extends reportbox
{
    public $fromdate;
    public $todate;
    public $summary;
    public $srno;

    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7,$subject='',$pdffilename='',$papersize='A4',$orientation='P')
	{
        $this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $this->subject= $subject;
        $this->pdffilename= $pdffilename;
        $this->papersize=strtoupper($papersize);
        $this->orientation=strtoupper($orientation);
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueType', '', 32);
        // create new PDF document
	    $this->pdf = new MYPDF($this->orientation, PDF_UNIT, $this->papersize, true, 'UTF-8', false);
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject($this->subject);
        if ($this->languagecode==0)
        {
            $this->pdf->SetKeywords(strtoupper($this->pdffilename).'.EN');
        }
        elseif ($this->languagecode==1)
        {
            $this->pdf->SetKeywords(strtoupper($this->pdffilename).'.MR');
        }
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));
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
        if ($this->languagecode==0)
        {
            $lg['a_meta_language'] = 'en';
        }
        elseif ($this->languagecode==1)
        {
            $lg['a_meta_language'] = 'mr';
        }
        $lg['w_page'] = 'Page - ';
        // set some language-dependent strings (optional)
	    $this->pdf->setLanguageArray($lg);
    }
    
    function startreport()
    {
        $this->newpage(true);
        $this->group();
        $this->reportfooter();
    }

    function pageheader()
    {
        ob_flush();
        ob_start();
       
        $this->liney = 13;
        $this->pdf->SetFont('siddhanta', '', 10, '', true);
        $this->newrow(4);
        $this->setfieldwidth(60,125);
        $this->textbox('तोडणी वहातूक धंदा',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        $this->newrow();
        $this->setfieldwidth(100,100);
        $this->textbox('दिनांक '.$this->fromdate.' पासून दिनांक '.$this->todate.' पर्यंत',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->newrow();
        
        $this->hline(10,405,$this->liney,'C');
        
        $this->setfieldwidth(10,10);
        $this->textbox('अ.क्र.',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(10);
        $this->textbox('कोड',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(40);
        $this->textbox('कंत्राटदाराचे नाव',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox('तो.टनेज',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox('व.टनेज',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox('तो+व टनेज',$this->w,$this->x,'S','R',1,'siddhanta',10,'','Y','','B');
        $this->setfieldwidth(20);
        $this->textbox('धंदा',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox('कमिशन',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox('एकूण',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox('कपात',$this->w,$this->x,'S','R',1,'siddhanta',10,'','Y','','B');
        $this->setfieldwidth(20);
        $this->textbox('देय',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->newrow(10);
        $this->hline(10,405,$this->liney,'C');
    }

    function group()
    {
        $this->groupcount=0;
        $this->srno=1;
        $this->summary['GROSSAMOUNT']=0;
        $this->summary['COMMISSIONAMOUNT']=0;
        $this->summary['GROSSDEDUCTION']=0;
        $this->summary['DEPOSITAMOUNT']=0;
        $this->summary['NETAMOUNT']=0;
        $cond='';
        $frdate=DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
        $todate=DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
        //,(nvl(grossamount,0)+nvl(COMMISSIONAMOUNT,0)+nvl(GROSSDEDUCTION,0)) as NETAMOUNT
        $cond='1=1';
        $cond1='1=1';
        if ($this->billtypecode != '' and $this->billtypecode != 0)
        {
            $cond=$cond." and h.billcategorycode=".$this->billtypecode;
            $cond1=$cond1." and b.billcategorycode=".$this->billtypecode;
        }

        $group_query_1 ="select /*t.commissionrate,*/t.contractorcode,c.contractornameuni
        ,sum(hrtonnage) hrtonnage,sum(trtonnage) trtonnage,sum(hrtrtonnage) hrtrtonnage
        ,sum(depositamount) depositamount
        ,sum(advanceamount) advanceamount
        ,sum(dieselamount) dieselamount
        ,sum(grossamount) grossamount,sum(commissionamount) commissionamount 
        ,sum(netgrossamount) netgrossamount
        ,sum(grossdeduction) grossdeduction
        ,sum(netamount) netamount
        --,sum(COMMISSIONAMOUNT) COMMISSIONAMOUNT,sum(GROSSDEDUCTION) GROSSDEDUCTION
        --,nvl(sum(grossamount),0)+nvl(sum(COMMISSIONAMOUNT),0)+nvl(sum(GROSSDEDUCTION),0) NETAMOUNT
        from (
        select t.contractorcategorycode,t.servicehrtrcategorycode/*,rt.commissionrate*/,t.contractorcode
        ,sum(grossamount) grossamount,sum(commissionamount) commissionamount,sum(netgrossamount) netgrossamount
                            ,0 as depositamount
                            ,0 as advanceamount
                            ,0 as dieselamount
                            ,0 hrtonnage,0 trtonnage,0 hrtrtonnage
                            ,nvl(sum(t.grossdeduction),0) grossdeduction
                            ,sum(t.netamount) netamount
                            --,sum(t.commissionamount) COMMISSIONAMOUNT
                            --,case when nvl(sum(t.commissionamount),0)=0 then round(sum(t.grossamount)*rt.commissionrate/100) else 0 end GROSSDEDUCTION
                            from htbillheader t
                            ,billperiodheader h/*,depositcommissionrate rt*/
                            where {$cond} and
                            t.billperiodtransnumber=h.billperiodtransnumber
                            --and t.seasoncode=rt.seasoncode
                            --and t.contractorcategorycode=rt.contractorcategorycode
                            --and t.servicehrtrcategorycode=rt.servicehrtrcategorycode
                            and h.seasonyear=".$_SESSION['yearperiodcode']."
                            and h.fromdate>='".$frdate."' and h.todate<='".$todate."'
                            group by t.contractorcategorycode,t.servicehrtrcategorycode/*,rt.commissionrate*/,t.contractorcode
                            union all
                            select h.contractorcategorycode,h.servicehrtrcategorycode/*,rt.commissionrate*/,contractorcode
                            ,0 grossamount,0 commissionamount,0 netgrossamount
                            ,sum(d.deductionamount) depositamount
                            ,0 as advanceamount
                            ,0 as dieselamount
                            ,0 hrtonnage,0 trtonnage,0 hrtrtonnage
                            --,0 COMMISSIONAMOUNT
                            --,0 GROSSDEDUCTION
                            ,0 grossdeduction
                            ,0 netamount
                            from htbillheader h,htbilldeductiondetail d,billperiodheader b/*,depositcommissionrate rt*/
                            where {$cond1} and
                             h.transactionnumber=d.billtransactionnumber
                            and h.billperiodtransnumber=b.billperiodtransnumber
                            and h.seasoncode=b.seasonyear
                            and d.deductioncode=2001 
                            --and h.seasoncode=rt.seasoncode
                            --and h.contractorcategorycode=rt.contractorcategorycode
                            --and h.servicehrtrcategorycode=rt.servicehrtrcategorycode
                            and b.seasonyear=".$_SESSION['yearperiodcode']."
                            and b.fromdate>='".$frdate."' and b.todate<='".$todate."'
                            group by h.contractorcategorycode,h.servicehrtrcategorycode/*,rt.commissionrate*/,contractorcode
                            union all
                            select h.contractorcategorycode,h.servicehrtrcategorycode/*,rt.commissionrate*/,contractorcode
                            ,0 grossamount,0 commissionamount,0 netgrossamount
                            ,0 depositamount
                            ,sum(d.deductionamount) advanceamount
                            ,0 as dieselamount
                            ,0 hrtonnage,0 trtonnage,0 hrtrtonnage
                            --,0 COMMISSIONAMOUNT
                            --,0 GROSSDEDUCTION
                            ,0 grossdeduction
                            ,0 netamount
                            from htbillheader h,htbilldeductiondetail d,billperiodheader b/*,depositcommissionrate rt*/
                            where {$cond1} and
                             h.transactionnumber=d.billtransactionnumber
                            and h.billperiodtransnumber=b.billperiodtransnumber
                            and h.seasoncode=b.seasonyear
                            and d.deductioncode=2003 
                            --and h.seasoncode=rt.seasoncode
                            --and h.contractorcategorycode=rt.contractorcategorycode
                            --and h.servicehrtrcategorycode=rt.servicehrtrcategorycode
                            and b.seasonyear=".$_SESSION['yearperiodcode']."
                            and b.fromdate>='".$frdate."' and b.todate<='".$todate."'
                            group by h.contractorcategorycode,h.servicehrtrcategorycode/*,rt.commissionrate*/,contractorcode
                            union all
                            select h.contractorcategorycode,h.servicehrtrcategorycode/*,rt.commissionrate*/,contractorcode
                            ,0 grossamount,0 commissionamount,0 netgrossamount
                            ,0 depositamount
                            ,0 advanceamount
                            ,sum(d.deductionamount) as dieselamount
                            ,0 hrtonnage,0 trtonnage,0 hrtrtonnage
                            --,0 COMMISSIONAMOUNT
                            --,0 GROSSDEDUCTION
                            ,0 grossdeduction
                            ,0 netamount
                            from htbillheader h,htbilldeductiondetail d,billperiodheader b/*,depositcommissionrate rt*/
                            where {$cond1} and
                             h.transactionnumber=d.billtransactionnumber
                            and h.billperiodtransnumber=b.billperiodtransnumber
                            and h.seasoncode=b.seasonyear
                            and d.deductioncode=2002 
                            --and h.seasoncode=rt.seasoncode
                            --and h.contractorcategorycode=rt.contractorcategorycode
                            --and h.servicehrtrcategorycode=rt.servicehrtrcategorycode
                            and b.seasonyear=".$_SESSION['yearperiodcode']."
                            and b.fromdate>='".$frdate."' and b.todate<='".$todate."'
                            group by h.contractorcategorycode,h.servicehrtrcategorycode/*,rt.commissionrate*/,contractorcode
                            union all
                            select t.contractorcategorycode,t.servicehrtrcategorycode/*,rt.commissionrate*/,t.contractorcode
                            ,0 grossamount,0 commissionamount,0 netgrossamount
                            ,0 as depositamount
                            ,0 as advanceamount
                            ,0 as dieselamount
                            ,sum(t.hrtonnage) hrtonnage,sum(t.trtonnage) trtonnage,sum(t.hrtrtonnage) hrtrtonnage
                            --,0 COMMISSIONAMOUNT
                            --,0 GROSSDEDUCTION
                            ,0 grossdeduction
                            ,0 netamount
                            from htbillheader t
                            ,billperiodheader h/*,depositcommissionrate rt*/
                            where {$cond} and
                             t.billperiodtransnumber=h.billperiodtransnumber
                            --and t.seasoncode=rt.seasoncode
                            --and t.contractorcategorycode=rt.contractorcategorycode
                            --and t.servicehrtrcategorycode=rt.servicehrtrcategorycode
                            and h.billcategorycode=1
                            and h.seasonyear=".$_SESSION['yearperiodcode']."
                            and h.fromdate>='".$frdate."' and h.todate<='".$todate."'
                            group by t.contractorcategorycode,t.servicehrtrcategorycode/*,rt.commissionrate*/,t.contractorcode
                            )t,contractor c
                            where t.contractorcode=c.contractorcode
                            group by /*t.commissionrate,*/t.contractorcode,c.contractornameuni
                            order by /*t.commissionrate,*/t.contractorcode,c.contractornameuni";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->detail_2($group_row_1['CONTRACTORCODE']); 
            $this->hline(10,405,$this->liney,'D'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
    }
    
    function groupheader_1(&$group_row_1)
    {
        if ($this->isnewpage(15))
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
        }
        $this->setfieldwidth(25,10);
        $this->textbox($group_row_1['COMMISSIONRATE'].'%',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->newrow();
        $this->percentsummary['GROSSAMOUNT']=0;
        $this->percentsummary['COMMISSIONAMOUNT']=0;
        $this->percentsummary['GROSSDEDUCTION']=0;
        $this->percentsummary['DEPOSITAMOUNT']=0;
        $this->percentsummary['ADVANCEAMOUNT']=0;
        $this->percentsummary['DIESELAMOUNT']=0;
        $this->percentsummary['NETAMOUNT']=0;

    }

    function groupheader_2(&$group_row_1)
    {
    }

    function groupheader_3(&$group_row_1)
    {
      
    }
    function groupheader_4(&$group_row)
    {
    }
    function groupheader_5(&$group_row)
    {
    }
    function groupheader_6(&$group_row)
    {
    }
    function groupheader_7(&$group_row)
    {
    }
    function detail_1(&$group_row_1)
    {
        ob_flush();
        ob_start();
        if ($this->isnewpage(10))
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
        //$this->hline(10,405,$this->liney-2,'D'); 

        $this->setfieldwidth(10,10);
        $this->textbox($this->srno,$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(10);
        $this->textbox($group_row_1['CONTRACTORCODE'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(40);
        $this->textbox($group_row_1['CONTRACTORNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['HRTONNAGE'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['TRTONNAGE'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['HRTRTONNAGE'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
      /*   $this->setfieldwidth(20);
        $this->textbox($group_row_1['DEPOSITAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['ADVANCEAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['DIESELAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
     */        $this->setfieldwidth(20);
        $this->textbox($group_row_1['GROSSAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['COMMISSIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['NETGROSSAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['GROSSDEDUCTION'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['NETAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->srno++;
        $this->percentsummary['HRTONNAGE']=$this->percentsummary['HRTONNAGE']+$group_row_1['HRTONNAGE'];
        $this->percentsummary['TRTONNAGE']=$this->percentsummary['TRTONNAGE']+$group_row_1['TRTONNAGE'];
        $this->percentsummary['HRTRTONNAGE']=$this->percentsummary['HRTRTONNAGE']+$group_row_1['HRTRTONNAGE'];

        $this->percentsummary['GROSSAMOUNT']=$this->percentsummary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->percentsummary['COMMISSIONAMOUNT']=$this->percentsummary['COMMISSIONAMOUNT']+$group_row_1['COMMISSIONAMOUNT'];
        $this->percentsummary['GROSSDEDUCTION']=$this->percentsummary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->percentsummary['DEPOSITAMOUNT']=$this->percentsummary['DEPOSITAMOUNT']+$group_row_1['DEPOSITAMOUNT'];
        $this->percentsummary['ADVANCEAMOUNT']=$this->percentsummary['ADVANCEAMOUNT']+$group_row_1['ADVANCEAMOUNT'];
        $this->percentsummary['DEISELAMOUNT']=$this->percentsummary['DEISELAMOUNT']+$group_row_1['DEISELAMOUNT'];
        $this->percentsummary['NETAMOUNT']=$this->percentsummary['NETAMOUNT']+$group_row_1['NETAMOUNT'];

        $this->summary['HRTONNAGE']=$this->summary['HRTONNAGE']+$group_row_1['HRTONNAGE'];
        $this->summary['TRTONNAGE']=$this->summary['TRTONNAGE']+$group_row_1['TRTONNAGE'];
        $this->summary['HRTRTONNAGE']=$this->summary['HRTRTONNAGE']+$group_row_1['HRTRTONNAGE'];

        $this->summary['GROSSAMOUNT']=$this->summary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->summary['COMMISSIONAMOUNT']=$this->summary['COMMISSIONAMOUNT']+$group_row_1['COMMISSIONAMOUNT'];
        $this->summary['GROSSDEDUCTION']=$this->summary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->summary['DEPOSITAMOUNT']=$this->summary['DEPOSITAMOUNT']+$group_row_1['DEPOSITAMOUNT'];
        $this->summary['ADVANCEAMOUNT']=$this->summary['ADVANCEAMOUNT']+$group_row_1['ADVANCEAMOUNT'];
        $this->summary['DIESELAMOUNT']=$this->summary['DIESELAMOUNT']+$group_row_1['DIESELAMOUNT'];
        $this->summary['NETAMOUNT']=$this->summary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        if ($this->isnewpage(10))
        {
            $this->newrow();
           // $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
           // $this->hline(10,405,$this->liney,'D'); 
        }
    }
    function detail_2($contractorcode)
    { 
        $frdate=DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
        $todate=DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
        $cond='1=1';
        $cond1='1=1';
        if ($this->billtypecode != '' and $this->billtypecode != 0)
        {
            $cond=$cond." and h.billcategorycode=".$this->billtypecode;
            $cond1=$cond1." and b.billcategorycode=".$this->billtypecode;
        }
        $detail_query_2 = " select 
        row_number()over(order by d.deductioncode)SERIALNUMBER
        ,dd.deductionnameuni                           
        ,sum(d.deductionamount) DEDUCTIONAMOUNT
        from htbillheader h,htbilldeductiondetail d
        ,billperiodheader b,deduction dd
        where {$cond1} and
        h.transactionnumber=d.billtransactionnumber
        and h.billperiodtransnumber=b.billperiodtransnumber
        and h.seasoncode=b.seasonyear
        and d.deductioncode=dd.deductioncode
        and b.seasonyear=".$_SESSION['yearperiodcode']."
        and b.fromdate>='".$frdate."' and b.todate<='".$todate."'
        and h.contractorcode='".$contractorcode."'
        group by d.deductioncode,dd.deductionnameuni 
        order by d.deductioncode";
        $detail_result_2 = oci_parse($this->connection, $detail_query_2);
        $r = oci_execute($detail_result_2);
        $islastodd=0;
        $this->pdf->SetTextColor(0, 0, 150);
        //$this->newrow(-2);
        $this->setfieldwidth(50,10);
        $this->textbox('कपात',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        while ($detail_row_2 = oci_fetch_array($detail_result_2,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($this->isnewpage(20))
            {
                $this->newrow();
                $this->hline(10,405,$this->liney-2,'C'); 
                $this->newpage(True);
            }  
            if ($detail_row_2['SERIALNUMBER']%3==1) 
            {
                $this->setfieldwidth(50,10);
                $this->textbox($detail_row_2['DEDUCTIONNAMEUNI'].' '.$detail_row_2['DEDNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                $islastodd=1;
            }
            elseif ($detail_row_2['SERIALNUMBER']%3==2) 
            {
                $this->vline($this->liney,$this->liney+5,$this->x+$this->w,'D');
                $this->setfieldwidth(50);
                $this->textbox($detail_row_2['DEDUCTIONNAMEUNI'].' '.$detail_row_2['DEDNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                $islastodd=1;
            }
            else
            {
                $this->vline($this->liney,$this->liney+5,$this->x+$this->w,'D');
                $this->setfieldwidth(50);
                $this->textbox($detail_row_2['DEDUCTIONNAMEUNI'].' '.$detail_row_2['DEDNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                $islastodd=0;
                $this->newrow(4);
            }
            if ($this->isnewpage(20))
            {
               
                $this->newpage(True);
            }
            else
            {
                //$this->newrow();
                //$this->hline(10,195,$this->liney-2,'C'); 
            }
        }
        $this->newrow(2);
        $this->pdf->SetTextColor(0, 0, 0);
        if ($islastodd==1) 
        {
            $this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
    }
    function groupfooter_1(&$group_row_3)
    {     
        if ($this->isnewpage(15))
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
        }
        $this->setfieldwidth(80,10);
        $this->textbox($group_row_3['COMMISSIONRATE'].'% एकूण',$this->w,$this->x,'S','L',1,'siddhanta',8,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox($this->percentsummary['HRTONNAGE'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->percentsummary['TRTONNAGE'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->percentsummary['HRTRTONNAGE'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->percentsummary['DEPOSITAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->percentsummary['ADVANCEAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->percentsummary['COMMISSIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->percentsummary['GROSSAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->percentsummary['GROSSDEDUCTION'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->percentsummary['NETAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        if ($this->isnewpage(10))
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'D'); 
        }
        $this->newpage(True);
    }

    function groupfooter_2(&$group_row_1)
    {      
    }
    function groupfooter_3(&$group_row_2)
    {      
    }

    function groupfooter_4(&$group_row)
    {
    }
    function groupfooter_5(&$group_row)
    {
    }
    function groupfooter_6(&$group_row)
    {
    }
    function groupfooter_7(&$group_row)
    {
    }

    function subreportgroupheader(&$subreportgroup_row,$subreportnumber,$groupnumber)
    {
    }

    function subreportgroupfooter(&$subreportlast_row,$subreportnumber,$groupnumber)
    {
    }

    function pagefooter($islastpage=false)
    {
    }

    function reportfooter()
    {
        if ($this->isnewpage(15))
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
        }
        $this->setfieldwidth(60,10);
        $this->textbox('ए एकंदर',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox($this->summary['HRTONNAGE'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($this->summary['TRTONNAGE'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($this->summary['HRTRTONNAGE'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
       /*  $this->setfieldwidth(20);
        $this->textbox($this->summary['DEPOSITAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($this->summary['ADVANCEAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($this->summary['DIESELAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        */ $this->setfieldwidth(20);
        $this->textbox($this->summary['GROSSAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($this->summary['COMMISSIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($this->summary['NETGROSSAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($this->summary['GROSSDEDUCTION'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($this->summary['NETAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        if ($this->isnewpage(10))
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
        }
      
        $frdate=DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
        $todate=DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
        $cond='1=1';
        $cond1='1=1';
        if ($this->billtypecode != '' and $this->billtypecode != 0)
        {
            $cond=$cond." and h.billcategorycode=".$this->billtypecode;
            $cond1=$cond1." and b.billcategorycode=".$this->billtypecode;
        }
        $detail_query_2 = " select 
        row_number()over(order by d.deductioncode)SERIALNUMBER
        ,dd.deductionnameuni                           
        ,sum(d.deductionamount) DEDUCTIONAMOUNT
        from htbillheader h,htbilldeductiondetail d
        ,billperiodheader b,deduction dd
        where {$cond1} and
         h.transactionnumber=d.billtransactionnumber
        and h.billperiodtransnumber=b.billperiodtransnumber
        and h.seasoncode=b.seasonyear
        and d.deductioncode=dd.deductioncode
        and b.seasonyear=".$_SESSION['yearperiodcode']."
        and b.fromdate>='".$frdate."' and b.todate<='".$todate."'
        --and h.contractorcode='".$contractorcode."'
        group by d.deductioncode,dd.deductionnameuni 
        order by d.deductioncode";
        $detail_result_2 = oci_parse($this->connection, $detail_query_2);
        $r = oci_execute($detail_result_2);
        $islastodd=0;
        $this->pdf->SetTextColor(0, 0, 150);
        $this->setfieldwidth(50,10);
        $this->textbox('कपात एकूण',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        while ($detail_row_2 = oci_fetch_array($detail_result_2,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($this->isnewpage(20))
            {
                $this->newrow();
                $this->hline(10,405,$this->liney-2,'C'); 
                $this->newpage(True);
            }  
            if ($detail_row_2['SERIALNUMBER']%3==1) 
            {
                $this->setfieldwidth(50,10);
                $this->textbox($detail_row_2['DEDUCTIONNAMEUNI'].' '.$detail_row_2['DEDNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                $islastodd=1;
            }
            elseif ($detail_row_2['SERIALNUMBER']%3==2) 
            {
                $this->vline($this->liney,$this->liney+5,$this->x+$this->w,'D');
                $this->setfieldwidth(50);
                $this->textbox($detail_row_2['DEDUCTIONNAMEUNI'].' '.$detail_row_2['DEDNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                $islastodd=1;
            }
            else
            {
                $this->vline($this->liney,$this->liney+5,$this->x+$this->w,'D');
                $this->setfieldwidth(50);
                $this->textbox($detail_row_2['DEDUCTIONNAMEUNI'].' '.$detail_row_2['DEDNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                $islastodd=0;
                $this->newrow(4);
            }
            if ($this->isnewpage(20))
            {
               
                $this->newpage(True);
            }
            else
            {
                //$this->newrow();
                //$this->hline(10,195,$this->liney-2,'C'); 
            }
        }
        $this->newrow(2);
        $this->pdf->SetTextColor(0, 0, 0);
        if ($islastodd==1) 
        {
            $this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }

        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True,True);
        }
        else
        {
            $this->hline(10,405,$this->liney-2,'C');
        }  
        $this->newrow(15);
        $this->setfieldwidth(20,80);
        $this->textbox('क्लार्क',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(50);
        $this->textbox('केन अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(50);
        $this->textbox('चिफ अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
        
    
    }

    function endreport()
    {

        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output($this->pdffilename.'-'.currentindiandatetimenamestamp().'.pdf', 'I');
    }

    function export()
    {
        $cond='';
        $frdate=DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
        $todate=DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
        //,(nvl(grossamount,0)+nvl(COMMISSIONAMOUNT,0)+nvl(GROSSDEDUCTION,0)) as NETAMOUNT
        
        $cond='1=1';
        $cond1='1=1';
        if ($this->billtypecode != '' and $this->billtypecode != 0)
        {
            $cond=$cond." and h.billcategorycode=".$this->billtypecode;
            $cond1=$cond1." and b.billcategorycode=".$this->billtypecode;
        }
        $query ="select /*t.commissionrate,*/t.contractorcode,c.contractornameeng
        ,sum(hrtonnage) hrtonnage,sum(trtonnage) trtonnage,sum(hrtrtonnage) hrtrtonnage
        ,sum(depositamount) depositamount
        ,sum(advanceamount) advanceamount
        ,sum(dieselamount) dieselamount
        ,sum(grossamount) grossamount,sum(commissionamount) commissionamount 
        ,sum(netgrossamount) netgrossamount
        ,sum(grossdeduction) grossdeduction
        ,sum(netamount) netamount
        --,sum(COMMISSIONAMOUNT) COMMISSIONAMOUNT,sum(GROSSDEDUCTION) GROSSDEDUCTION
        --,nvl(sum(grossamount),0)+nvl(sum(COMMISSIONAMOUNT),0)+nvl(sum(GROSSDEDUCTION),0) NETAMOUNT
        from (
        select t.contractorcategorycode,t.servicehrtrcategorycode/*,rt.commissionrate*/,t.contractorcode
        ,sum(grossamount) grossamount,sum(commissionamount) commissionamount,sum(netgrossamount) netgrossamount
                            ,0 as depositamount
                            ,0 as advanceamount
                            ,0 as dieselamount
                            ,0 hrtonnage,0 trtonnage,0 hrtrtonnage
                            ,nvl(sum(t.grossdeduction),0) grossdeduction
                            ,sum(t.netamount) netamount
                            --,sum(t.commissionamount) COMMISSIONAMOUNT
                            --,case when nvl(sum(t.commissionamount),0)=0 then round(sum(t.grossamount)*rt.commissionrate/100) else 0 end GROSSDEDUCTION
                            from htbillheader t
                            ,billperiodheader h/*,depositcommissionrate rt*/
                            where {$cond} and
                            t.billperiodtransnumber=h.billperiodtransnumber
                            --and t.seasoncode=rt.seasoncode
                            --and t.contractorcategorycode=rt.contractorcategorycode
                            --and t.servicehrtrcategorycode=rt.servicehrtrcategorycode
                            and h.seasonyear=".$_SESSION['yearperiodcode']."
                            and h.fromdate>='".$frdate."' and h.todate<='".$todate."'
                            group by t.contractorcategorycode,t.servicehrtrcategorycode/*,rt.commissionrate*/,t.contractorcode
                            union all
                            select h.contractorcategorycode,h.servicehrtrcategorycode/*,rt.commissionrate*/,contractorcode
                            ,0 grossamount,0 commissionamount,0 netgrossamount
                            ,sum(d.deductionamount) depositamount
                            ,0 as advanceamount
                            ,0 as dieselamount
                            ,0 hrtonnage,0 trtonnage,0 hrtrtonnage
                            --,0 COMMISSIONAMOUNT
                            --,0 GROSSDEDUCTION
                            ,0 grossdeduction
                            ,0 netamount
                            from htbillheader h,htbilldeductiondetail d,billperiodheader b/*,depositcommissionrate rt*/
                            where {$cond1} and
                             h.transactionnumber=d.billtransactionnumber
                            and h.billperiodtransnumber=b.billperiodtransnumber
                            and h.seasoncode=b.seasonyear
                            and d.deductioncode=2001 
                            --and h.seasoncode=rt.seasoncode
                            --and h.contractorcategorycode=rt.contractorcategorycode
                            --and h.servicehrtrcategorycode=rt.servicehrtrcategorycode
                            and b.seasonyear=".$_SESSION['yearperiodcode']."
                            and b.fromdate>='".$frdate."' and b.todate<='".$todate."'
                            group by h.contractorcategorycode,h.servicehrtrcategorycode/*,rt.commissionrate*/,contractorcode
                            union all
                            select h.contractorcategorycode,h.servicehrtrcategorycode/*,rt.commissionrate*/,contractorcode
                            ,0 grossamount,0 commissionamount,0 netgrossamount
                            ,0 depositamount
                            ,sum(d.deductionamount) advanceamount
                            ,0 as dieselamount
                            ,0 hrtonnage,0 trtonnage,0 hrtrtonnage
                            --,0 COMMISSIONAMOUNT
                            --,0 GROSSDEDUCTION
                            ,0 grossdeduction
                            ,0 netamount
                            from htbillheader h,htbilldeductiondetail d,billperiodheader b/*,depositcommissionrate rt*/
                            where {$cond1} and
                             h.transactionnumber=d.billtransactionnumber
                            and h.billperiodtransnumber=b.billperiodtransnumber
                            and h.seasoncode=b.seasonyear
                            and d.deductioncode=2003 
                            --and h.seasoncode=rt.seasoncode
                            --and h.contractorcategorycode=rt.contractorcategorycode
                            --and h.servicehrtrcategorycode=rt.servicehrtrcategorycode
                            and b.seasonyear=".$_SESSION['yearperiodcode']."
                            and b.fromdate>='".$frdate."' and b.todate<='".$todate."'
                            group by h.contractorcategorycode,h.servicehrtrcategorycode/*,rt.commissionrate*/,contractorcode
                            union all
                            select h.contractorcategorycode,h.servicehrtrcategorycode/*,rt.commissionrate*/,contractorcode
                            ,0 grossamount,0 commissionamount,0 netgrossamount
                            ,0 depositamount
                            ,0 advanceamount
                            ,sum(d.deductionamount) as dieselamount
                            ,0 hrtonnage,0 trtonnage,0 hrtrtonnage
                            --,0 COMMISSIONAMOUNT
                            --,0 GROSSDEDUCTION
                            ,0 grossdeduction
                            ,0 netamount
                            from htbillheader h,htbilldeductiondetail d,billperiodheader b/*,depositcommissionrate rt*/
                            where {$cond1} and
                             h.transactionnumber=d.billtransactionnumber
                            and h.billperiodtransnumber=b.billperiodtransnumber
                            and h.seasoncode=b.seasonyear
                            and d.deductioncode=2002 
                            --and h.seasoncode=rt.seasoncode
                            --and h.contractorcategorycode=rt.contractorcategorycode
                            --and h.servicehrtrcategorycode=rt.servicehrtrcategorycode
                            and b.seasonyear=".$_SESSION['yearperiodcode']."
                            and b.fromdate>='".$frdate."' and b.todate<='".$todate."'
                            group by h.contractorcategorycode,h.servicehrtrcategorycode/*,rt.commissionrate*/,contractorcode
                            union all
                            select t.contractorcategorycode,t.servicehrtrcategorycode/*,rt.commissionrate*/,t.contractorcode
                            ,0 grossamount,0 commissionamount,0 netgrossamount
                            ,0 as depositamount
                            ,0 as advanceamount
                            ,0 as dieselamount
                            ,sum(t.hrtonnage) hrtonnage,sum(t.trtonnage) trtonnage,sum(t.hrtrtonnage) hrtrtonnage
                            --,0 COMMISSIONAMOUNT
                            --,0 GROSSDEDUCTION
                            ,0 grossdeduction
                            ,0 netamount
                            from htbillheader t
                            ,billperiodheader h/*,depositcommissionrate rt*/
                            where {$cond1} and
                             t.billperiodtransnumber=h.billperiodtransnumber
                            --and t.seasoncode=rt.seasoncode
                            --and t.contractorcategorycode=rt.contractorcategorycode
                            --and t.servicehrtrcategorycode=rt.servicehrtrcategorycode
                            and h.billcategorycode=1
                            and h.seasonyear=".$_SESSION['yearperiodcode']."
                            and h.fromdate>='".$frdate."' and h.todate<='".$todate."'
                            group by t.contractorcategorycode,t.servicehrtrcategorycode/*,rt.commissionrate*/,t.contractorcode
                            )t,contractor c
                            where t.contractorcode=c.contractorcode
                            group by /*t.commissionrate,*/t.contractorcode,c.contractornameeng
                            order by /*t.commissionrate,*/t.contractorcode,c.contractornameeng";

           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $response = array();
           $filename='contractorbusiness.csv';
           //$fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');
           $fp1=fopen('php://memory', 'w');

           fputcsv($fp1, array('Contractor Code','Contractor name','Gross Amount'
           ,'Hr Tonnage','Tr Tonnage','Hr Tr Tonnage'
           ,'Deposit Amount','Advance Recovery','Diesel Recovery','Gross Amount','Commission Amount Given','Net Gross','Gross Deduction'
           ,'NETAMOUNT'));
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                fputcsv($fp1, array($row['CONTRACTORCODE'],$row['CONTRACTORNAMEENG'],$row['GROSSAMOUNT']
                ,$row['HRTONNAGE'],$row['TRTONNAGE'],$row['HRTRTONNAGE']
                ,$row['DEPOSITAMOUNT'], $row['ADVANCEAMOUNT'], $row['DIESELAMOUNT']
                ,$row['GROSSAMOUNT']
                ,$row['COMMISSIONAMOUNT']
                ,$row['NETGROSSAMOUNT']
                ,$row['GROSSDEDUCTION'],$row['NETAMOUNT']), $delimiter = ',', $enclosure = '"');

                //----------------
               /*  $detail_query_2 = " select 
                row_number()over(order by d.deductioncode)SERIALNUMBER
                ,dd.deductionnameuni                           
                ,sum(d.deductionamount) DEDUCTIONAMOUNT
                from htbillheader h,htbilldeductiondetail d
                ,billperiodheader b,deduction dd
                where h.transactionnumber=d.billtransactionnumber
                and h.billperiodtransnumber=b.billperiodtransnumber
                and h.seasoncode=b.seasonyear
                and d.deductioncode=dd.deductioncode
                and b.seasonyear=".$_SESSION['yearperiodcode']."
                and b.fromdate>='".$frdate."' and b.todate<='".$todate."'
                and h.contractorcode='".$row['CONTRACTORCODE']."'
                group by d.deductioncode,dd.deductionnameuni 
                order by d.deductioncode";
                $detail_result_2 = oci_parse($this->connection, $detail_query_2);
                $r = oci_execute($detail_result_2);
                $islastodd=0;
                //$this->pdf->SetTextColor(0, 0, 150);
                //$this->newrow(-2);
                $this->setfieldwidth(50,10);
                $this->textbox('कपात',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
                while ($detail_row_2 = oci_fetch_array($detail_result_2,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    fputcsv($fp1, array($row['SERIALNUMBER'],$row['DEDUCTIONNAMEUNI']
                    ,$row['DEDUCTIONAMOUNT']), $delimiter = ',', $enclosure = '"');
                    
                }
 */

                 //------------------------------

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

}    
?>