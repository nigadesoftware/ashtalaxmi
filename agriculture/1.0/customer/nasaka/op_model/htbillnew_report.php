<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class htbill extends swappreport
{
    public $billperiodtransnumber;
    public $msubtitle;
    public $billsummary;
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
    
    function startreport($billperiodtransnumber)
    {
        $pageheader_query_1="select BILLPERIODDESC(".$billperiodtransnumber.") as desct from dual t";
        $pageheader_result_1 = oci_parse($this->connection, $pageheader_query_1);
        $r = oci_execute($pageheader_result_1); 
        if ($pageheader_row_1 = oci_fetch_array($pageheader_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
         $this->msubtitle  =  $pageheader_row_1['DESCT'];
        }
       // $this->group();
    }

    function group()
    {
        $this->totalgroupcount=1;  
        $query = "select t.fromdate,t.todate from billperiodheader t where t.billperiodtransnumber=".$this->billperiodtransnumber;
        $result_1 = oci_parse($this->connection, $query);
        $r = oci_execute($result_1);
        if ($row_1 = oci_fetch_array($result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $fromdate=$row_1['FROMDATE'];
            $todate=$row_1['TODATE'];
        }
        $group_query_1 = "with data as (
            select tt.billtransactionnumber,count(*) as cnt
            from htbillheader hh,htbillslipdetail tt,weightslip ww
            where hh.transactionnumber=tt.billtransactionnumber and tt.seasoncode=ww.seasoncode and tt.wttransactionnumber=ww.transactionnumber
            and hh.contractorcode=".$this->contractorcode." 
            group by tt.billtransactionnumber
            )
            select t.transactionnumber,f.contractorcode
            ,s.subcontractorcode
            ,t.transactionnumber
            ,t.billperiodtransnumber
            ,t.billnumber,to_char(nvl(h.billdate,h.paymentdate),'dd/MM/yyyy') billdate
            ,to_char(h.paymentdate,'dd/MM/yyyy') paymentdate
            ,f.contractornameuni,s.subcontractornameuni,hs.harvestersubcategorynameuni
            ,t.hrtonnage,t.trtonnage,t.hrtrtonnage,t.incentiveamount,t.grossamount,t.grossdeduction,T.DIESELRATEDIFFERENCEAMOUNT
            ,t.depositamount,t.commissionamount,t.labourcramount,t.noworkcramount,t.netgrossamount
            ,t.netamount,t.accountnumber,b.bankbranchnameuni,bk.banknameuni,bk.shortname
            ,h.servicetrhrcategorycode,sc.servicetrhrcatnameuni
            ,v.vehiclecode,v.vehiclenumber,nvl(prvbillhrcramount,0)+nvl(prvbilltrcramount,0) prvbillcr
            ,d.cnt as tripcount,h.billcategorycode ,businessamount,
            --,nst_nasaka_petrolpump.diesel_qty(p_seasoncode =>v.seasoncode ,p_vehiclecode =>v.vehiclecode,p_fromdate =>'".$fromdate."',p_todate =>'".$todate."') dieselqty
            (select nvl(sum(m.quantity),0) from pumpdieseltransaction m
            where m.seasoncode=v.seasoncode and m.vehiclecode=v.vehiclecode
            and m.transactiondate>=h.fromdate and m.transactiondate<=h.todate)dieselqty
            from htbillheader t,contractorcontract c,contractor f, subcontractor s
            ,billperiodheader h,bankbranch b,bank bk,harvestersubcategory hs
            ,vehicle v,servicetrhrcategory sc,data d
            where t.seasoncode=c.seasoncode
            and c.seasoncode=s.seasoncode
            and c.contractorcode=f.contractorcode
            and c.seasoncode=s.seasoncode
            and c.contractcode=s.contractcode
            and t.seasoncode=s.seasoncode
            and t.subcontractorcode=s.subcontractorcode
            and t.contractorcode=f.contractorcode
            and h.servicetrhrcategorycode=sc.servicetrhrcategorycode
            and t.seasoncode=v.seasoncode(+)
            and t.vehiclecode=v.vehiclecode(+)
            and t.billperiodtransnumber=h.billperiodtransnumber
            and b.bankcode=bk.bankcode(+) 
            and t.bankbranchcode=b.bankbranchcode(+)
            and s.harvestersubcategorycode=hs.harvestersubcategorycode(+)
            and t.contractorcode=".$this->contractorcode." 
            and h.seasonyear=".$_SESSION['yearperiodcode']." 
            and t.transactionnumber=d.billtransactionnumber(+)
            order by t.transactionnumber";
            
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->newpage(True);
            $this->startreport($group_row_1['BILLPERIODTRANSNUMBER']);
            $this->groupheader_1($group_row_1);
            $this->detail_1($group_row_1['TRANSACTIONNUMBER']);
            $this->groupfooter_1($group_row_1);
        }
        //$this->reportfooter();
    }
    
    function groupheader_1(&$group_row_1)
    {
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        //$this->pageheader();
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->setfieldwidth(180,10);
        $this->textbox('तोडणी वहातूक बिल',$this->w,$this->x,'S','C',1,'siddhanta',13,'','','','B');
        $this->newrow(6);
        $this->setfieldwidth(40,40);
        $this->textbox('बिल नंबर:'.$group_row_1['BILLNUMBER'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(70,80);
        $this->textbox($group_row_1['SERVICETRHRCATNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(70,150);
        $this->textbox('बिल दिनांक:'.$group_row_1['BILLDATE'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->setfieldwidth(200,20);
        $this->textbox($this->msubtitle,$this->w,$this->x,'S','L',1,'siddhanta',8,'','','','');
        $this->newrow();
        $this->hline(10,195,$this->liney,'C');
        $this->setfieldwidth(75,10);
        $this->textbox('कंत्राटदार : '.$group_row_1['CONTRACTORCODE'].' '.$group_row_1['CONTRACTORNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(75);
        $this->textbox('सब कंत्राटदार : '.$group_row_1['SUBCONTRACTORCODE'].' '.$group_row_1['SUBCONTRACTORNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(50);
        $this->textbox('वाहन : '.$group_row_1['VEHICLENUMBER'].' '.$group_row_1['VEHICLECATEGORYNAMEUNI'],$this->w,$this->x,'N','L',1,'siddhanta',8,'','','','B');
        $this->newrow(3);
        $this->setfieldwidth(50,135);
        $this->textbox('वाहन कोड: '.$group_row_1['VEHICLECODE'],$this->w,$this->x,'S','L',1,'siddhanta',8,'','','','');
        $this->newrow(-3);
        $this->newrow();
        $this->hline(10,195,$this->liney,'D');
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        if ($group_row_1['BILLCATEGORYCODE']==1)
        {
            $this->setfieldwidth(40,10);
            $this->textbox('शेतकरी',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
            $this->setfieldwidth(10);
            $this->textbox('खेपा',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
            $this->setfieldwidth(25);
            $this->textbox('गाव',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
            //$this->setfieldwidth(10,80);
            //$this->textbox('डिझेल',$this->w,$this->x,'S','L',1,'siddhanta',8,'','','','B');
            $this->setfieldwidth(10);
            $this->textbox('अंतर',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
            $this->setfieldwidth(20);
            $this->textbox('टनेज',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
            $this->setfieldwidth(20);
            $this->textbox('वहा.दर',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
            $this->setfieldwidth(20);
            $this->textbox('वहा.रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
            $this->setfieldwidth(20);
            $this->textbox('तो.दर',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
            $this->setfieldwidth(20);
            $this->textbox('तो.रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
            $this->newrow(5);
        }
        $this->hline(10,195,$this->liney,'D');
        $this->billsummary['TRAMOUNT']=0;
        $this->billsummary['HRAMOUNT']=0;
        $this->billsummary['SLIPTONNAGE']=0;
        $this->billsummary['DIESELQUANTITY']=0;
    }

    function pageheader()
    {
        $this->pdf->Image("../img/kadwawatermark.png", 60, 95, 70, 70, '', '', '', false, 300, '', false, false, 0);
    }
    function detail_1($transactionnumber)
    { 
        $detail_query_1 = "select h.servicehrtrcategorycode,h.transactionnumber,v.villagecode,v.villagenameuni,sv.subvillagecode,sv.subvillagenameuni
        ,f.farmercode,f.farmernameuni,d.distance
        ,d.trrate,d.hrrate,count(*) cnt
        ,sum(d.tramount) tramount,sum(d.hramount) hramount,sum(d.sliptonnage) sliptonnage
        from htbillheader h,htbillslipdetail d,village v,farmer f,fieldslip t,weightslip w,subvillage sv
        where h.transactionnumber=d.billtransactionnumber
        and d.villagecode=v.villagecode
        and d.farmercode=f.farmercode
        and d.wttransactionnumber=w.transactionnumber
        and w.seasoncode=t.seasoncode
        and w.fieldslipnumber=t.fieldslipnumber
        and t.villagecode=sv.villagecode(+)
        and t.subvillagecode=sv.subvillagecode(+)
        group by h.servicehrtrcategorycode,h.transactionnumber,v.villagecode,v.villagenameuni,sv.subvillagecode,sv.subvillagenameuni,f.farmercode,f.farmernameuni,d.distance,d.trrate,d.hrrate
        having h.transactionnumber=".$transactionnumber;
        $detail_result_1 = oci_parse($this->connection, $detail_query_1);
        $r = oci_execute($detail_result_1);
        $islastodd=0;
        while ($detail_row_1 = oci_fetch_array($detail_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($this->isnewpage(10))
            {
                $this->newrow();
                $this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            $this->setfieldwidth(40,10);
            $this->textbox($detail_row_1['FARMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
            $this->setfieldwidth(10);
            $this->textbox($this->nvl($detail_row_1['CNT']),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(38);
            $this->textbox($detail_row_1['VILLAGENAMEUNI'].'-'.$detail_row_1['SUBVILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',8,'','','','');
            //$this->setfieldwidth(10);
            //$this->textbox(number_format_indian($detail_row_1['DIESELQUANTITY'],0),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','');

            $this->setfieldwidth(10,90);
            if ($detail_row_1['SERVICEHRTRCATEGORYCODE']!=2)
            $this->textbox($this->nvl($detail_row_1['DISTANCE']),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(20);
            $this->textbox(number_format_indian($detail_row_1['SLIPTONNAGE'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(20);
            $this->textbox($this->nvl($detail_row_1['TRRATE']),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(20);
            $this->textbox($this->nvl($detail_row_1['TRAMOUNT']),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(20);
            $this->textbox($this->nvl($detail_row_1['HRRATE']),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(20);
            $this->textbox($this->nvl($detail_row_1['HRAMOUNT']),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(5);
            $this->billsummary['TRAMOUNT'] = $this->billsummary['TRAMOUNT']+$detail_row_1['TRAMOUNT'];
            $this->billsummary['HRAMOUNT'] = $this->billsummary['HRAMOUNT']+$detail_row_1['HRAMOUNT'];
            $this->billsummary['SLIPTONNAGE'] = $this->billsummary['SLIPTONNAGE']+$detail_row_1['SLIPTONNAGE'];
        // $this->billsummary['DIESELQUANTITY']=$this->billsummary['DIESELQUANTITY']+$detail_row_1['DIESELQUANTITY'];
        }
    }

    function detail_2($transactionnumber)
    { 
        $this->setfieldwidth(50,10);
        $this->textbox('कपाती :',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        $this->newrow();
        $detail_query_1 = "select t.serialnumber
        ,trim(trim(d.deductionnameuni) ||' ('|| trim(t.dedseasonyear)||') '||trim(b.bankbranchnameuni)) dednameuni
        ,t.deductionamount
         from HTBILLDEDUCTIONDETAIL t
         ,deduction d
         ,bankbranch b
        where t.deductioncode=d.deductioncode 
        and t.branchcode=b.bankbranchcode(+) 
        and t.billtransactionnumber=".$transactionnumber." order by serialnumber";
        $detail_result_1 = oci_parse($this->connection, $detail_query_1);
        $r = oci_execute($detail_result_1);
        $islastodd=0;
        $deductionamount=0;
        while ($detail_row_1 = oci_fetch_array($detail_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($this->isnewpage(10))
            {
                $this->newrow();
                $this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            if ($detail_row_1['SERIALNUMBER']%3==1) 
            {
                $this->setfieldwidth(07,10);
                $this->textbox($detail_row_1['SERIALNUMBER'].')',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(60);
                $this->textbox($detail_row_1['DEDNAMEUNI'].' '.$detail_row_1['DEDUCTIONAMOUNT'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                //$this->setfieldwidth(25);
                //$this->textbox($detail_row_1['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->vline($this->liney-2,$this->liney+5,77);
                $islastodd=1;
            }
            elseif ($detail_row_1['SERIALNUMBER']%3==2) 
            {
                $this->setfieldwidth(07);
                $this->textbox($detail_row_1['SERIALNUMBER'].')',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(60);
                $this->textbox($detail_row_1['DEDNAMEUNI'].' '.$detail_row_1['DEDUCTIONAMOUNT'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                //$this->setfieldwidth(25);
                //$this->textbox($detail_row_1['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->vline($this->liney-2,$this->liney+5,134);
                $islastodd=1;
            }
            else
            {
                $this->setfieldwidth(7);
                $this->textbox($detail_row_1['SERIALNUMBER'].')',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(60);
                $this->textbox($detail_row_1['DEDNAMEUNI'].' '.$detail_row_1['DEDUCTIONAMOUNT'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                //$this->setfieldwidth(25);
                //$this->textbox($detail_row_1['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                //$this->vline($this->liney,$this->liney+5,105);
                $this->newrow(4);
                $islastodd=0;
            }
            $deductionamount=$deductionamount+$detail_row_1['DEDUCTIONAMOUNT'];
            if ($this->isnewpage(10))
            {
                //$this->newrow();
                //$this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            else
            {
                //$this->newrow();
                //$this->hline(10,195,$this->liney-2,'C'); 
            }
        }
        if ($islastodd==1) 
        {
            $this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
    }

    function groupfooter_1(&$group_row_1)
    {        
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        $group_query_1 = "select nvl(sum(m.quantity),0) quantity 
        from pumpdieseltransaction m,billperiodheader h
            where m.seasoncode=".$_SESSION['yearperiodcode']." 
            and m.vehiclecode=".$group_row_1['VEHICLECODE']."
            and m.transactiondate>=h.fromdate 
            and m.transactiondate<=h.todate
            and h.billperiodtransnumber=".$this->billperiodtransnumber;
            
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        if ($group_row_2 = oci_fetch_array($group_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $quantity = $group_row_2['QUANTITY'];
        }
        else
        {
            $quantity = 0;
        }
        $this->newrow(2);
        $this->hline(10,195,$this->liney-2,'D');
        $this->newrow(-2);
        $this->setfieldwidth(35,10);
        $this->textbox('एकूण ',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(10);
        if ($group_row_1['BILLCATEGORYCODE']==1)
        {
            //$this->textbox('खेपा',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
            $this->setfieldwidth(15);
            $this->textbox($group_row_1['TRIPCOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(35);
            $this->textbox('डिझेल '.$quantity.' लि.',$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            //$this->setfieldwidth(15);
            $this->setfieldwidth(15);
            $this->textbox(number_format_indian($this->billsummary['SLIPTONNAGE'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(20);
            $this->setfieldwidth(20);
            $this->textbox($this->nvl($this->billsummary['TRAMOUNT']),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(15);
            $this->setfieldwidth(25);
            $this->textbox($this->nvl($this->billsummary['HRAMOUNT']),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        }
        else
        {
            $tonnage=$group_row_1['HRTONNAGE']+$group_row_1['TRTONNAGE']+$group_row_1['HRTRTONNAGE'];
            $this->setfieldwidth(25);
            $this->textbox('टनेज',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
            $this->setfieldwidth(20);
            $this->textbox(number_format_indian($tonnage,3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(20);
            $this->textbox('धंदा',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
            $this->setfieldwidth(20);
            $this->textbox($group_row_1['BUSINESSAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(15);
            $this->setfieldwidth(25);
            $this->textbox('',$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        }    
        $this->newrow(5);
        $this->setfieldwidth(20,10);
        $this->textbox('जादा',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox('मागिल फरक देय',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox('कमिशन',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox('लेबर खर्च',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox('बस पाळी',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');

        $this->setfieldwidth(30);
        if ($group_row_1['BILLCATEGORYCODE']==1)
        {
            //$this->textbox('डिझेल फरक',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
            $this->setfieldwidth(30);
           // $this->textbox('डिझेल लि.',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        }
        else
        {
            $this->textbox('डिपॉझिट',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
            $this->setfieldwidth(30);
            $this->textbox('',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        }
       
        $this->setfieldwidth(30,165);
        $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->newrow(5);
        $this->setfieldwidth(20,10);
        $this->textbox($group_row_1['INCENTIVEAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['PRVBILLCR'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['COMMISSIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['LABOURCRAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['LABOURCRAMOUNT'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');

        $this->setfieldwidth(30);
        if ($group_row_1['BILLCATEGORYCODE']==1)
        {
            //$this->textbox($group_row_1['DIESELRATEDIFFERENCEAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
            //$this->setfieldwidth(30);
            //$this->textbox($group_row_1['DIESELQTY'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        }
        else
        {
            $this->textbox($group_row_1['DEPOSITAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
            $this->setfieldwidth(30);
            $this->textbox('',$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        }
       
        $this->setfieldwidth(30,165);
        $this->textbox($group_row_1['NETGROSSAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->newrow(6);
        $this->newrow(2);
        $this->hline(10,195,$this->liney-2,'D');

        $this->detail_2($group_row_1['TRANSACTIONNUMBER']);
        $this->newrow();
        $this->hline(10,195,$this->liney-2,'D'); 
        $this->setfieldwidth(135,10);
        $this->setfieldwidth(20);
        $this->textbox('एकूण कपात',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['GROSSDEDUCTION'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->newrow(6);
        
        $this->hline(10,195,$this->liney-2,'C'); 
        $this->setfieldwidth(137,10);
        $this->textbox('अक्षरी->',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(112,25);
        $this->textbox(NumberToWords($this->nvl($group_row_1['NETAMOUNT'],0),1),$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');  
       
        $this->setfieldwidth(33);
        $this->textbox('निव्वळ देय ->',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['NETAMOUNT'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');  
        $this->newrow();
        $this->hline(10,195,$this->liney-2,'C'); 
        $this->setfieldwidth(15,10);
        $this->textbox('शाखा ->',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(180);
        $this->textbox($group_row_1['BANKNAMEUNI'].', '.$group_row_1['BANKBRANCHNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');  
        $this->newrow();
        $this->hline(10,195,$this->liney-2,'C'); 
        $this->newrow(15);
        $this->setfieldwidth(20,60);
        $this->textbox('क्लार्क',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(30);
        $this->textbox('केन अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(30);
        $this->textbox('चिफ अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(30);
        $this->textbox('जनरल मॅनेजर',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');

    }
    
    function pagefooter($islastpage=false)
    {
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
}    
?>