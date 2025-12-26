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
class htpaysheetseasonsummary extends reportbox
{
    public $billcategorycode;
    public $msubtitle;
    public $servicetrhrcategorysummary;
    public $flagcode;
    public $contractcategorysummary;
    public $harvestersubcategorysummary;
    public $summary;

    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7,$subject='',$pdffilename='',$papersize='A3',$orientation='P')
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
        $pageheader_query_1="select billperioddescbybillperiodno(2,".$_SESSION['yearperiodcode'].",".$this->billcategorycode.",".$this->billperiodnumber.") as desct from dual t";
        $pageheader_result_1 = oci_parse($this->connection, $pageheader_query_1);
        $r = oci_execute($pageheader_result_1); 
        if ($pageheader_row_1 = oci_fetch_array($pageheader_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->msubtitle  =  $pageheader_row_1['DESCT'];
        }
        $this->newpage(true);
        $this->group();
        $this->reportfooter();
    }

    function pageheader()
    {
        ob_flush();
        ob_start();
       
        $this->liney = 13;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->newrow(4);
        $a=$this->servicetrhrcategory();
        if ($this->flagcode==1)
        {
            $this->setfieldwidth(120,115);
            $this->textbox($a.' ट्रक ट्रॅक्टर वहातूूक समरी',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        }
        else
        {
            $this->setfieldwidth(120,120);
            $this->textbox($a.' तोडणी वहातूूक समरी',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        }
        $this->newrow();
        $this->setfieldwidth(250,10);
        $this->textbox($this->msubtitle,$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->newrow();
        $this->hline(10,270,$this->liney,'C');
        
        $this->setfieldwidth(60,10);
        $this->textbox('तो.व सेवा',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox('टनेज',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('तोडणी रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(28);
        $this->textbox('वहातूक रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->newrow(5);
        $this->textbox('जादा रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->newrow(-5);
        $this->setfieldwidth(22);
        $this->textbox('एकूण धंदा',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->newrow(4);
        $this->textbox('डिपाॅझीट',$this->w,$this->x,'N','R',1,'siddhanta',10,'','','','B');
        $this->newrow(-4);
        $this->setfieldwidth(25);
        $this->textbox('एकूण कमिशन',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->newrow(5);
        $this->textbox('डिझेल फरक',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->newrow(-5);
        $this->setfieldwidth(25);
        $this->textbox('एकूण रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('एकूण कपात',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('निव्वळ देय',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->newrow(10);
        $this->hline(10,270,$this->liney,'C');
    }

    function group()
    {
        
        $this->summary['TONNAGE']=0;
        $this->summary['GROSSAMOUNT']=0;
        $this->summary['DIESELRATEDIFFERENCEAMOUNT']=0;
        $this->summary['COMMISSIONAMOUNT']=0;
        $this->summary['NETGROSSAMOUNT']=0;
        $this->summary['GROSSDEDUCTION']=0;
        $this->summary['NETAMOUNT']=0;
        $this->summary['DEPOSITAMOUNT']=0;
        $this->summary['PRVBILLHRCRAMOUNT']=0;
        $this->summary['PRVBILLTRCRAMOUNT']=0;
        $this->summary['HRAMOUNT']=0;
        $this->summary['TRAMOUNT']=0;


        if ($this->flagcode==1)
        {
            $this->totalgroupcount=2;
            $group_query_1 ="select t.servicetrhrcategorycode,contractcategorycode
            ,servicetrhrcatnameuni,contractcategorynameuni
            ,nvl(sum(hrtonnage),0) hrtonnage,nvl(sum(trtonnage),0) trtonnage,nvl(sum(hrtrtonnage),0) hrtrtonnage
            ,nvl(sum(dieselratedifferenceamount),0) dieselratedifferenceamount
            ,nvl(sum(INCENTIVEAMOUNT),0) INCENTIVEAMOUNT
            ,nvl(sum(grossamount),0) grossamount,nvl(sum(commissionamount),0) commissionamount
            ,nvl(sum(netgrossamount),0) netgrossamount
            ,nvl(sum(grossdeduction),0) grossdeduction,nvl(sum(netamount),0) netamount
            ,nvl(sum(hramount),0) hramount,nvl(sum(tramount),0) tramount
            ,nvl(sum(depositamount),0) depositamount
            ,nvl(sum(businessamount),0) businessamount
            ,nvl(sum(prvbillhrcramount),0) prvbillhrcramount
            ,nvl(sum(prvbilltrcramount),0) prvbilltrcramount
            from (
            select h.servicetrhrcategorycode,t.contractcategorycode,e.contractcategorynameuni 
            ,t.hrtonnage,t.trtonnage,t.hrtrtonnage,t.grossamount,t.commissionamount,t.dieselratedifferenceamount,t.INCENTIVEAMOUNT
            ,t.netgrossamount,t.grossdeduction,t.netamount,nvl((select sum(d.hramount) from htbillslipdetail d where d.billtransactionnumber=t.transactionnumber),0) hramount
            ,nvl((select sum(d.tramount) 
            from htbillslipdetail d where d.billtransactionnumber=t.transactionnumber),0) tramount
            ,nvl(depositamount,0) depositamount
            ,nvl(businessamount,0) businessamount
            ,nvl(prvbillhrcramount,0) prvbillhrcramount
            ,nvl(prvbilltrcramount,0) prvbilltrcramount
            from htbillheader t,contractorcontract c,contractor f, subcontractor s
            ,billperiodheader h,bankbranch b,bank bk,harvestersubcategory hs
            ,vehicle v,contractcategory e
            where t.seasoncode=c.seasoncode
            and c.seasoncode=s.seasoncode
            and c.contractorcode=f.contractorcode
            and c.seasoncode=s.seasoncode
            and c.contractcode=s.contractcode
            and t.seasoncode=s.seasoncode
            and t.subcontractorcode=s.subcontractorcode
            and t.contractorcode=f.contractorcode
            and t.contractcategorycode=e.contractcategorycode
            and t.seasoncode=v.seasoncode(+)
            and t.vehiclecode=v.vehiclecode(+)
            and t.billperiodtransnumber=h.billperiodtransnumber
            and b.bankcode=bk.bankcode(+) 
            and t.bankbranchcode=b.bankbranchcode(+)
            and s.harvestersubcategorycode=hs.harvestersubcategorycode(+)
            and h.seasonyear=".$_SESSION['yearperiodcode']." 
            and h.billcategorycode=".$this->billcategorycode." 
            )t, servicetrhrcategory s
            where t.servicetrhrcategorycode=s.servicetrhrcategorycode
            and s.servicetrhrcategorycode in (1,13)
            group by t.servicetrhrcategorycode,contractcategorycode
            ,servicetrhrcatnameuni,contractcategorynameuni
            order by t.servicetrhrcategorycode,contractcategorycode";        
        }
        else
        {
            $this->totalgroupcount=3;
            $group_query_1 ="select t.servicetrhrcategorycode,t.harvestersubcategorycode,contractcategorycode
            ,harvestersubcategorynameuni,servicetrhrcatnameuni,contractcategorynameuni
            ,nvl(sum(hrtonnage),0) hrtonnage,nvl(sum(trtonnage),0) trtonnage,nvl(sum(hrtrtonnage),0) hrtrtonnage
            ,nvl(sum(grossamount),0) grossamount,nvl(sum(commissionamount),0) commissionamount
            ,nvl(sum(INCENTIVEAMOUNT),0) INCENTIVEAMOUNT
            ,nvl(sum(netgrossamount),0) netgrossamount
            ,nvl(sum(grossdeduction),0) grossdeduction,nvl(sum(netamount),0) netamount
            ,nvl(sum(hramount),0) hramount,nvl(sum(tramount),0) tramount
            ,nvl(sum(depositamount),0) depositamount
            ,nvl(sum(businessamount),0) businessamount
            ,nvl(sum(prvbillhrcramount),0) prvbillhrcramount
            ,nvl(sum(prvbilltrcramount),0) prvbilltrcramount
            from (
            select h.servicetrhrcategorycode,hs.harvestersubcategorycode,hs.harvestersubcategorynameuni,c.contractcategorycode,e.contractcategorynameuni 
            ,t.hrtonnage,t.trtonnage,t.hrtrtonnage,t.grossamount,t.commissionamount,t.INCENTIVEAMOUNT
            ,t.netgrossamount,t.grossdeduction,t.netamount,nvl((select sum(d.hramount) from htbillslipdetail d where d.billtransactionnumber=t.transactionnumber),0) hramount
            ,nvl((select sum(d.tramount) 
            from htbillslipdetail d where d.billtransactionnumber=t.transactionnumber),0) tramount
            ,nvl(depositamount,0) depositamount
            ,nvl(businessamount,0) businessamount
            ,nvl(prvbillhrcramount,0) prvbillhrcramount
            ,nvl(prvbilltrcramount,0) prvbilltrcramount
            from htbillheader t,contractorcontract c,contractor f, subcontractor s
            ,billperiodheader h,bankbranch b,bank bk,harvestersubcategory hs
            ,vehicle v,contractcategory e
            where t.seasoncode=c.seasoncode
            and c.seasoncode=s.seasoncode
            and c.contractorcode=f.contractorcode
            and c.seasoncode=s.seasoncode
            and c.contractcode=s.contractcode
            and t.seasoncode=s.seasoncode
            and t.subcontractorcode=s.subcontractorcode
            and t.contractorcode=f.contractorcode
            and c.contractcategorycode=e.contractcategorycode
            and t.seasoncode=v.seasoncode(+)
            and t.vehiclecode=v.vehiclecode(+)
            and t.billperiodtransnumber=h.billperiodtransnumber
            and b.bankcode=bk.bankcode(+) 
            and t.bankbranchcode=b.bankbranchcode(+)
            and s.harvestersubcategorycode=hs.harvestersubcategorycode(+)
            and h.seasonyear=".$_SESSION['yearperiodcode']." 
            and h.billcategorycode=".$this->billcategorycode." 
            )t, servicetrhrcategory s
            where t.servicetrhrcategorycode=s.servicetrhrcategorycode
            and s.servicetrhrcategorycode not in (1,13)
            group by t.servicetrhrcategorycode,t.harvestersubcategorycode,contractcategorycode
            ,harvestersubcategorynameuni,servicetrhrcatnameuni,contractcategorynameuni
            order by servicetrhrcategorycode,t.harvestersubcategorycode,contractcategorycode";
        }
        //order by t.servicetrhrcategorycode,contractcategorycode
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->hline(10,270,$this->liney,'D'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
    }
    
    function groupheader_1(&$group_row_1)
    {
        $this->servicetrhrcategorysummary['TONNAGE']=0;
        $this->servicetrhrcategorysummary['HRAMOUNT']=0;
        $this->servicetrhrcategorysummary['TRAMOUNT']=0;
        $this->servicetrhrcategorysummary['DIESELRATEDIFFERENCEAMOUNT']=0;
        $this->servicetrhrcategorysummary['INCENTIVEAMOUNT']=0;
        $this->servicetrhrcategorysummary['GROSSAMOUNT']=0;
        $this->servicetrhrcategorysummary['BUSINESSAMOUNT']=0;
        $this->servicetrhrcategorysummary['COMMISSIONAMOUNT']=0;
        $this->servicetrhrcategorysummary['NETGROSSAMOUNT']=0;
        $this->servicetrhrcategorysummary['GROSSDEDUCTION']=0;
        $this->servicetrhrcategorysummary['NETAMOUNT']=0;
        $this->servicetrhrcategorysummary['DEPOSITAMOUNT']=0;
        $this->servicetrhrcategorysummary['PRVBILLHRCRAMOUNT']=0;
        $this->servicetrhrcategorysummary['PRVBILLTRCRAMOUNT']=0;
        if ($this->isnewpage(40))
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        $this->newrow(2);
        $this->setfieldwidth(65,10);
        $this->textbox($group_row_1['SERVICETRHRCATNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->newrow();
    }

    function groupheader_2(&$group_row_1)
    {
        if ($this->isnewpage(20))
            {
                //$this->newrow();
                //$this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
        if ($this->flagcode==1)
        {
            $this->contractcategorysummary['TONNAGE']=0;
            $this->contractcategorysummary['HRAMOUNT']=0;
            $this->contractcategorysummary['TRAMOUNT']=0;
            $this->contractcategorysummary['DIESELRATEDIFFERENCEAMOUNT']=0;
            $this->contractcategorysummary['INCENTIVEAMOUNT']=0;
            $this->contractcategorysummary['GROSSAMOUNT']=0;
            $this->contractcategorysummary['BUSINESSAMOUNT']=0;
            $this->contractcategorysummary['COMMISSIONAMOUNT']=0;
            $this->contractcategorysummary['NETGROSSAMOUNT']=0;
            $this->contractcategorysummary['GROSSDEDUCTION']=0;
            $this->contractcategorysummary['NETAMOUNT']=0;
            $this->contractcategorysummary['DEPOSITAMOUNT']=0;
            $this->contractcategorysummary['PRVBILLHRCRAMOUNT']=0;
            $this->contractcategorysummary['PRVBILLTRCRAMOUNT']=0;
            if ($this->isnewpage(20))
            {
                //$this->newrow();
                //$this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            $this->setfieldwidth(65,10);
            $this->textbox('  '.$group_row_1['CONTRACTCATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',9,'','','','B');
            $this->newrow();
        }
        elseif ($group_row_1['SERVICETRHRCATEGORYCODE']==2)
        {
            $this->harvestersubcategorysummary['TONNAGE']=0;
            $this->harvestersubcategorysummary['HRAMOUNT']=0;
            $this->harvestersubcategorysummary['TRAMOUNT']=0;
            $this->harvestersubcategorysummary['GROSSAMOUNT']=0;
            $this->harvestersubcategorysummary['COMMISSIONAMOUNT']=0;
            $this->harvestersubcategorysummary['INCENTIVEAMOUNT']=0;
            $this->harvestersubcategorysummary['NETGROSSAMOUNT']=0;
            $this->harvestersubcategorysummary['GROSSDEDUCTION']=0;
            $this->harvestersubcategorysummary['NETAMOUNT']=0;
            $this->harvestersubcategorysummary['DEPOSITAMOUNT']=0;
            $this->harvestersubcategorysummary['BUSINESSAMOUNT']=0;
            $this->harvestersubcategorysummary['PRVBILLHRCRAMOUNT']=0;
            $this->harvestersubcategorysummary['PRVBILLTRCRAMOUNT']=0;
            
            if ($this->isnewpage(20))
            {
                //$this->newrow();
                //$this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            $this->setfieldwidth(65,10);
            $this->textbox('  '.$group_row_1['HARVESTERSUBCATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',9,'','','','B');
            $this->newrow();
        }
        
    }

    function groupheader_3(&$group_row_1)
    {
        if ($this->isnewpage(20))
            {
                //$this->newrow();
                //$this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
        if ($this->flagcode!=1)
        {
            $this->contractcategorysummary['TONNAGE']=0;
            $this->contractcategorysummary['HRAMOUNT']=0;
            $this->contractcategorysummary['TRAMOUNT']=0;
            $this->contractcategorysummary['DIESELRATEDIFFERENCEAMOUNT']=0;
            $this->contractcategorysummary['GROSSAMOUNT']=0;
            $this->contractcategorysummary['COMMISSIONAMOUNT']=0;
            $this->contractcategorysummary['INCENTIVEAMOUNT']=0;
            $this->contractcategorysummary['NETGROSSAMOUNT']=0;
            $this->contractcategorysummary['GROSSDEDUCTION']=0;
            $this->contractcategorysummary['NETAMOUNT']=0;
            $this->contractcategorysummary['DEPOSITAMOUNT']=0;
            $this->contractcategorysummary['BUSINESSAMOUNT']=0;
            $this->contractcategorysummary['PRVBILLHRCRAMOUNT']=0;
            $this->contractcategorysummary['PRVBILLTRCRAMOUNT']=0;
            
            if ($this->isnewpage(20))
            {
                //$this->newrow();
                //$this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            $this->setfieldwidth(65,10);
            $this->textbox('  '.$group_row_1['CONTRACTCATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',9,'','','','');
        }
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
        
        $this->servicetrhrcategorysummary['TONNAGE']=$this->servicetrhrcategorysummary['TONNAGE']+$group_row_1['HRTONNAGE']+$group_row_1['TRTONNAGE']+$group_row_1['HRTRTONNAGE'];
        $this->servicetrhrcategorysummary['HRAMOUNT']=$this->servicetrhrcategorysummary['HRAMOUNT']+$group_row_1['HRAMOUNT'];
        $this->servicetrhrcategorysummary['TRAMOUNT']=$this->servicetrhrcategorysummary['TRAMOUNT']+$group_row_1['TRAMOUNT'];
        $this->servicetrhrcategorysummary['DIESELRATEDIFFERENCEAMOUNT']=$this->servicetrhrcategorysummary['DIESELRATEDIFFERENCEAMOUNT']+$group_row_1['DIESELRATEDIFFERENCEAMOUNT'];
        $this->servicetrhrcategorysummary['INCENTIVEAMOUNT']=$this->servicetrhrcategorysummary['INCENTIVEAMOUNT']+$group_row_1['INCENTIVEAMOUNT'];
        $this->servicetrhrcategorysummary['GROSSAMOUNT']=$this->servicetrhrcategorysummary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->servicetrhrcategorysummary['COMMISSIONAMOUNT']=$this->servicetrhrcategorysummary['COMMISSIONAMOUNT']+$group_row_1['COMMISSIONAMOUNT'];
        $this->servicetrhrcategorysummary['NETGROSSAMOUNT']=$this->servicetrhrcategorysummary['NETGROSSAMOUNT']+$group_row_1['NETGROSSAMOUNT'];
        $this->servicetrhrcategorysummary['GROSSDEDUCTION']=$this->servicetrhrcategorysummary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->servicetrhrcategorysummary['NETAMOUNT']=$this->servicetrhrcategorysummary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        $this->servicetrhrcategorysummary['DEPOSITAMOUNT']=$this->servicetrhrcategorysummary['DEPOSITAMOUNT']+$group_row_1['DEPOSITAMOUNT'];
        $this->servicetrhrcategorysummary['BUSINESSAMOUNT']=$this->servicetrhrcategorysummary['BUSINESSAMOUNT']+$group_row_1['BUSINESSAMOUNT'];
        $this->servicetrhrcategorysummary['PRVBILLHRCRAMOUNT']+=$group_row_1['PRVBILLHRCRAMOUNT'];
        $this->servicetrhrcategorysummary['PRVBILLTRCRAMOUNT']+=$group_row_1['PRVBILLTRCRAMOUNT'];

        $this->contractcategorysummary['TONNAGE']=$this->contractcategorysummary['TONNAGE']+$group_row_1['HRTONNAGE']+$group_row_1['TRTONNAGE']+$group_row_1['HRTRTONNAGE'];
        $this->contractcategorysummary['HRAMOUNT']=$this->contractcategorysummary['HRAMOUNT']+$group_row_1['HRAMOUNT'];
        $this->contractcategorysummary['TRAMOUNT']=$this->contractcategorysummary['TRAMOUNT']+$group_row_1['TRAMOUNT'];
        $this->contractcategorysummary['DIESELRATEDIFFERENCEAMOUNT']=$this->contractcategorysummary['DIESELRATEDIFFERENCEAMOUNT']+$group_row_1['DIESELRATEDIFFERENCEAMOUNT'];
        $this->contractcategorysummary['INCENTIVEAMOUNT']=$this->contractcategorysummary['INCENTIVEAMOUNT']+$group_row_1['INCENTIVEAMOUNT'];
        $this->contractcategorysummary['GROSSAMOUNT']=$this->contractcategorysummary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->contractcategorysummary['COMMISSIONAMOUNT']=$this->contractcategorysummary['COMMISSIONAMOUNT']+$group_row_1['COMMISSIONAMOUNT'];
        $this->contractcategorysummary['NETGROSSAMOUNT']=$this->contractcategorysummary['NETGROSSAMOUNT']+$group_row_1['NETGROSSAMOUNT'];
        $this->contractcategorysummary['GROSSDEDUCTION']=$this->contractcategorysummary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->contractcategorysummary['NETAMOUNT']=$this->contractcategorysummary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        $this->contractcategorysummary['DEPOSITAMOUNT']=$this->contractcategorysummary['DEPOSITAMOUNT']+$group_row_1['DEPOSITAMOUNT'];
        $this->contractcategorysummary['BUSINESSAMOUNT']=$this->contractcategorysummary['BUSINESSAMOUNT']+$group_row_1['BUSINESSAMOUNT'];
        $this->contractcategorysummary['PRVBILLHRCRAMOUNT']+=$group_row_1['PRVBILLHRCRAMOUNT'];
        $this->contractcategorysummary['PRVBILLTRCRAMOUNT']+=$group_row_1['PRVBILLTRCRAMOUNT'];



        $this->harvestersubcategorysummary['TONNAGE']=$this->harvestersubcategorysummary['TONNAGE']+$group_row_1['HRTONNAGE']+$group_row_1['TRTONNAGE']+$group_row_1['HRTRTONNAGE'];
        $this->harvestersubcategorysummary['HRAMOUNT']=$this->harvestersubcategorysummary['HRAMOUNT']+$group_row_1['HRAMOUNT'];
        $this->harvestersubcategorysummary['TRAMOUNT']=$this->harvestersubcategorysummary['TRAMOUNT']+$group_row_1['TRAMOUNT'];
        $this->harvestersubcategorysummary['GROSSAMOUNT']=$this->harvestersubcategorysummary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->harvestersubcategorysummary['COMMISSIONAMOUNT']=$this->harvestersubcategorysummary['COMMISSIONAMOUNT']+$group_row_1['COMMISSIONAMOUNT'];
        $this->harvestersubcategorysummary['INCENTIVEAMOUNT']=$this->harvestersubcategorysummary['INCENTIVEAMOUNT']+$group_row_1['INCENTIVEAMOUNT'];
        $this->harvestersubcategorysummary['NETGROSSAMOUNT']=$this->harvestersubcategorysummary['NETGROSSAMOUNT']+$group_row_1['NETGROSSAMOUNT'];
        $this->harvestersubcategorysummary['GROSSDEDUCTION']=$this->harvestersubcategorysummary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->harvestersubcategorysummary['NETAMOUNT']=$this->harvestersubcategorysummary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        $this->harvestersubcategorysummary['DEPOSITAMOUNT']=$this->harvestersubcategorysummary['DEPOSITAMOUNT']+$group_row_1['DEPOSITAMOUNT'];
        $this->harvestersubcategorysummary['BUSINESSAMOUNT']=$this->harvestersubcategorysummary['BUSINESSAMOUNT']+$group_row_1['BUSINESSAMOUNT'];
        $this->harvestersubcategorysummary['PRVBILLHRCRAMOUNT']+=$group_row_1['PRVBILLHRCRAMOUNT'];
        $this->harvestersubcategorysummary['PRVBILLTRCRAMOUNT']+=$group_row_1['PRVBILLTRCRAMOUNT'];

        

        $this->summary['TONNAGE']=$this->summary['TONNAGE']+$group_row_1['HRTONNAGE']+$group_row_1['TRTONNAGE']+$group_row_1['HRTRTONNAGE'];
        $this->summary['HRAMOUNT']=$this->summary['HRAMOUNT']+$group_row_1['HRAMOUNT'];
        $this->summary['TRAMOUNT']=$this->summary['TRAMOUNT']+$group_row_1['TRAMOUNT'];
        $this->summary['DIESELRATEDIFFERENCEAMOUNT']=$this->summary['DIESELRATEDIFFERENCEAMOUNT']+$group_row_1['DIESELRATEDIFFERENCEAMOUNT'];
        $this->summary['GROSSAMOUNT']=$this->summary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->summary['COMMISSIONAMOUNT']=$this->summary['COMMISSIONAMOUNT']+$group_row_1['COMMISSIONAMOUNT'];
        $this->summary['INCENTIVEAMOUNT']=$this->summary['INCENTIVEAMOUNT']+$group_row_1['INCENTIVEAMOUNT'];
        $this->summary['NETGROSSAMOUNT']=$this->summary['NETGROSSAMOUNT']+$group_row_1['NETGROSSAMOUNT'];
        $this->summary['GROSSDEDUCTION']=$this->summary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->summary['NETAMOUNT']=$this->summary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        $this->summary['DEPOSITAMOUNT']=$this->summary['DEPOSITAMOUNT']+$group_row_1['DEPOSITAMOUNT'];
        $this->summary['BUSINESSAMOUNT']=$this->summary['BUSINESSAMOUNT']+$group_row_1['BUSINESSAMOUNT'];
        $this->summary['PRVBILLHRCRAMOUNT']+=$group_row_1['PRVBILLHRCRAMOUNT'];
        $this->summary['PRVBILLTRCRAMOUNT']+=$group_row_1['PRVBILLTRCRAMOUNT'];

    }


    function detail_2(&$group_row_1)
    { 
    }
    function groupfooter_1(&$group_row_1)
    {     
            if ($this->isnewpage(20))
            {
                //$this->newrow();
                //$this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            //$this->summary['PRVBILLHRCRAMOUNT']+=$group_row_1['PRVBILLHRCRAMOUNT'];
            //$this->summary['PRVBILLTRCRAMOUNT']+=$group_row_1['PRVBILLTRCRAMOUNT'];

            $this->hline(10,270,$this->liney,'C');
            $this->setfieldwidth(65,10);
            $this->textbox('एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
            $this->setfieldwidth(25,65);
            $this->textbox($this->servicetrhrcategorysummary['TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->servicetrhrcategorysummary['HRAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(4);
            $this->textbox($this->servicetrhrcategorysummary['PRVBILLHRCRAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(-4);
            $this->setfieldwidth(25);
            $this->textbox($this->servicetrhrcategorysummary['TRAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(4);
            $this->textbox($this->servicetrhrcategorysummary['PRVBILLTRCRAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(-4);
/*             $this->newrow(4);
            $this->textbox($this->servicetrhrcategorysummary['INCENTIVEEAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(-4);
 */            $this->setfieldwidth(25);
            $this->textbox($this->servicetrhrcategorysummary['GROSSAMOUNT']+$this->servicetrhrcategorysummary['BUSINESSAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(4);
            $this->textbox($this->servicetrhrcategorysummary['DEPOSITAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(-4);
            $this->setfieldwidth(25);
            $this->textbox($this->servicetrhrcategorysummary['COMMISSIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(4);
            $this->textbox($this->servicetrhrcategorysummary['DIESELRATEDIFFERENCEAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(-4);
            $this->setfieldwidth(25);
            $this->textbox($this->servicetrhrcategorysummary['NETGROSSAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->servicetrhrcategorysummary['GROSSDEDUCTION'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->servicetrhrcategorysummary['NETAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(10);
            $this->hline(10,270,$this->liney,'D');

            if ($this->flagcode == 1)
        {
            $detail_query_2 = "select t.*,ROW_NUMBER() OVER (ORDER BY deductioncode,deductionnameuni) AS serialnumber
            from (select tt.deductioncode
            ,dd.deductionnameuni
            ,sum(tt.deductionamount) as deductionamount
            from HTBILLDEDUCTIONDETAIL tt
            ,deduction dd
            ,htbillheader t
            ,billperiodheader h
            where tt.deductioncode=dd.deductioncode 
            and t.transactionnumber=tt.billtransactionnumber
            and t.billperiodtransnumber=h.billperiodtransnumber
            and h.servicetrhrcategorycode in (1,13)
            and h.seasonyear=".$_SESSION['yearperiodcode']." 
            and h.billcategorycode=".$this->billcategorycode." 
            and h.servicetrhrcategorycode=".$group_row_1['SERVICETRHRCATEGORYCODE']."
            group by tt.deductioncode
            ,dd.deductionnameuni)t";
        }
        elseif ($group_row_1['SERVICETRHRCATEGORYCODE']==12)
        {
            $detail_query_2 = "select t.*,ROW_NUMBER() OVER (ORDER BY deductioncode,deductionnameuni) AS serialnumber
            from (select t.deductioncode
        ,deductionnameuni
        ,sum(t.deductionamount) as deductionamount from (select tt.deductioncode
        ,dd.deductionnameuni
        ,sum(tt.deductionamount) as deductionamount
         from HTBILLDEDUCTIONDETAIL tt
         ,deduction dd
         ,htbillheader t
        ,billperiodheader h
        where tt.deductioncode=dd.deductioncode 
        and t.transactionnumber=tt.billtransactionnumber
        and t.billperiodtransnumber=h.billperiodtransnumber
        and h.servicetrhrcategorycode not in (1,2)
        and h.seasonyear=".$_SESSION['yearperiodcode']." 
        and h.billcategorycode=".$this->billcategorycode." 
        and h.servicetrhrcategorycode=".$group_row_1['SERVICETRHRCATEGORYCODE']."
        group by tt.deductioncode,dd.deductionnameuni
        )t
        group by t.deductioncode,t.deductionnameuni)t";
        }
        else
        {
            $detail_query_2 = "select t.*,ROW_NUMBER() OVER (ORDER BY deductioncode,deductionnameuni) AS serialnumber
            from (select t.deductioncode
        ,deductionnameuni
        ,sum(t.deductionamount) as deductionamount from (select tt.deductioncode
        ,dd.deductionnameuni
        ,sum(tt.deductionamount) as deductionamount
         from HTBILLDEDUCTIONDETAIL tt
         ,deduction dd
         ,htbillheader t
        ,billperiodheader h
        where tt.deductioncode=dd.deductioncode 
        and t.transactionnumber=tt.billtransactionnumber
        and t.billperiodtransnumber=h.billperiodtransnumber
        and h.servicetrhrcategorycode not in (1,2)
        and h.seasonyear=".$_SESSION['yearperiodcode']." 
        and h.billcategorycode=".$this->billcategorycode." 
        and h.servicetrhrcategorycode=".$group_row_1['SERVICETRHRCATEGORYCODE']."
        group by tt.deductioncode,dd.deductionnameuni
        union all
        select tt.deductioncode
        ,dd.deductionnameuni
        ,sum(tt.deductionamount) as deductionamount
         from HTBILLDEDUCTIONDETAIL tt
         ,deduction dd
         ,htbillheader t
        ,billperiodheader h
        ,subcontractor s
        where tt.deductioncode=dd.deductioncode 
        and t.transactionnumber=tt.billtransactionnumber
        and t.billperiodtransnumber=h.billperiodtransnumber
        and t.seasoncode=s.seasoncode
        and t.subcontractorcode=s.subcontractorcode
        and s.harvestersubcategorycode+10=".$group_row_1['SERVICETRHRCATEGORYCODE']."
        and h.servicetrhrcategorycode in (2)
        and h.seasonyear=".$_SESSION['yearperiodcode']." 
        and h.billcategorycode=".$this->billcategorycode." 
        group by tt.deductioncode,dd.deductionnameuni
        )t
        group by t.deductioncode,t.deductionnameuni)t";
        };
        $detail_result_2 = oci_parse($this->connection, $detail_query_2);
        $r = oci_execute($detail_result_2);
        $islastodd=0;
        $this->pdf->SetTextColor(0, 0, 150);
        //$this->newrow(-2);
        while ($detail_row_2 = oci_fetch_array($detail_result_2,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($this->isnewpage(20))
            {
                //$this->newrow();
                //$this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            if ($detail_row_2['SERIALNUMBER']%3==1) 
            {
                //$this->setfieldwidth(07,10);
                //$this->textbox($detail_row_2['SERIALNUMBER'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(30,10);
                $this->textbox($detail_row_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                $islastodd=1;
            }
            elseif ($detail_row_2['SERIALNUMBER']%3==2) 
            {
                //$this->setfieldwidth(07,141);
                //$this->textbox($detail_row_2['SERIALNUMBER'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->vline($this->liney,$this->liney+5,$this->x+$this->w,'D');
                $this->setfieldwidth(30,65);
                $this->textbox($detail_row_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                //$this->vline($this->liney,$this->liney+5,105);
                $islastodd=1;
            }
            else
            {
                //$this->setfieldwidth(07,272);
                //$this->textbox($detail_row_2['SERIALNUMBER'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->vline($this->liney,$this->liney+5,$this->x+$this->w,'D');
                $this->setfieldwidth(30,120);
                $this->textbox($detail_row_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                //$this->vline($this->liney,$this->liney+5,105);
                $this->newrow(5);
                $islastodd=0;
            }
            if ($this->isnewpage(20))
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
        $this->newrow(2);
        $this->pdf->SetTextColor(0, 0, 0);
        if ($islastodd==1) 
        {
            $this->newrow(10);
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
        $this->hline(10,270,$this->liney-2,'C'); 
    }

    function groupfooter_2(&$group_row_1)
    {      
        if ($this->isnewpage(20))
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        if ($this->flagcode == 1)
        {
            $this->setfieldwidth(25,65);
            $this->textbox($this->contractcategorysummary['TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->contractcategorysummary['HRAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(4);
            $this->textbox($this->contractcategorysummary['PRVBILLHRCRAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(-4);
            $this->setfieldwidth(25);
            $this->textbox($this->contractcategorysummary['TRAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(4);
            $this->textbox($this->contractcategorysummary['PRVBILLTRCRAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(-4);
            /* $this->newrow(4);
            $this->textbox($this->contractcategorysummary['INCENTIVEAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(-4); */
            $this->setfieldwidth(25);
            $this->textbox($this->contractcategorysummary['GROSSAMOUNT']+$this->contractcategorysummary['BUSINESSAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(4);
            $this->textbox($this->contractcategorysummary['DEPOSITAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(-4);
            $this->setfieldwidth(25);
            $this->textbox($this->contractcategorysummary['COMMISSIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(4);
            $this->textbox($this->contractcategorysummary['DIESELRATEDIFFERENCEAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(-4);
            $this->setfieldwidth(25);
            $this->textbox($this->contractcategorysummary['NETGROSSAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->contractcategorysummary['GROSSDEDUCTION'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->contractcategorysummary['NETAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(10);
            $this->hline(10,270,$this->liney,'D');
        }    
        /* elseif ($group_row_1['SERVICETRHRCATEGORYCODE']==2)
        {
            $this->setfieldwidth(25,65);
            $this->textbox($this->harvestersubcategorysummary['TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->harvestersubcategorysummary['HRAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->harvestersubcategorysummary['TRAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->harvestersubcategorysummary['GROSSAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->harvestersubcategorysummary['COMMISSIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->harvestersubcategorysummary['NETGROSSAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->harvestersubcategorysummary['GROSSDEDUCTION'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->harvestersubcategorysummary['NETAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow();
            $this->hline(10,270,$this->liney,'D');
        } */
        if ($this->flagcode == 1)
        {
            $detail_query_2 = "select t.*,ROW_NUMBER() OVER (ORDER BY deductioncode,deductionnameuni) AS serialnumber
            from (select tt.deductioncode
            ,dd.deductionnameuni
            ,sum(tt.deductionamount) as deductionamount
            from HTBILLDEDUCTIONDETAIL tt
            ,deduction dd
            ,htbillheader t
            ,billperiodheader h
            where tt.deductioncode=dd.deductioncode 
            and t.transactionnumber=tt.billtransactionnumber
            and t.billperiodtransnumber=h.billperiodtransnumber
            and h.servicetrhrcategorycode in (1,13)
            and h.seasonyear=".$_SESSION['yearperiodcode']." 
            and h.billcategorycode=".$this->billcategorycode." 
            and h.servicetrhrcategorycode=".$group_row_1['SERVICETRHRCATEGORYCODE']."
            and t.contractcategorycode=".$group_row_1['CONTRACTCATEGORYCODE']."
            group by tt.deductioncode
            ,dd.deductionnameuni)t";
        }
        /* elseif ($group_row_1['SERVICETRHRCATEGORYCODE']==2)
        {
            $detail_query_2 = "select t.*,ROW_NUMBER() OVER (ORDER BY deductioncode,deductionnameuni) AS serialnumber
            from (select t.deductioncode
        ,deductionnameuni
        ,sum(t.deductionamount) as deductionamount from (select tt.deductioncode
        ,dd.deductionnameuni
        ,sum(tt.deductionamount) as deductionamount
         from HTBILLDEDUCTIONDETAIL tt
         ,deduction dd
         ,htbillheader t
        ,billperiodheader h
        ,subcontractor s
        where tt.deductioncode=dd.deductioncode 
        and t.transactionnumber=tt.billtransactionnumber
        and t.billperiodtransnumber=h.billperiodtransnumber
        and t.seasoncode=s.seasoncode and t.subcontractorcode=s.subcontractorcode
        and h.servicetrhrcategorycode not in (1)
        and h.seasonyear=".$_SESSION['yearperiodcode']." 
        and h.billcategorycode=".$this->billcategorycode." 
        and h.billperiodnumber=".$this->billperiodnumber."
        and h.servicetrhrcategorycode=".$group_row_1['SERVICETRHRCATEGORYCODE']."
        and s.harvestersubcategorycode=".$group_row_1['HARVESTERSUBCATEGORYCODE']."
        group by tt.deductioncode,dd.deductionnameuni
        )t
        group by t.deductioncode,t.deductionnameuni)t";
        };*/
        $detail_result_2 = oci_parse($this->connection, $detail_query_2);
        $r = oci_execute($detail_result_2);
        $islastodd=0;
        $this->pdf->SetTextColor(0, 0, 150);
        //$this->newrow(-2);
//        $dedtot=0;
        while ($detail_row_2 = oci_fetch_array($detail_result_2,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($this->isnewpage(20))
            {
                //$this->newrow();
                //$this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            if ($detail_row_2['SERIALNUMBER']%3==1) 
            {
                //$this->setfieldwidth(07,10);
                //$this->textbox($detail_row_2['SERIALNUMBER'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(30,10);
                $this->textbox($detail_row_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                $islastodd=1;
            }
            elseif ($detail_row_2['SERIALNUMBER']%3==2) 
            {
                //$this->setfieldwidth(07,141);
                //$this->textbox($detail_row_2['SERIALNUMBER'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->vline($this->liney,$this->liney+5,$this->x+$this->w,'D');
                $this->setfieldwidth(30,65);
                $this->textbox($detail_row_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                //$this->vline($this->liney,$this->liney+5,105);
                $islastodd=1;
            }
            else
            {
                //$this->setfieldwidth(07,272);
                //$this->textbox($detail_row_2['SERIALNUMBER'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->vline($this->liney,$this->liney+5,$this->x+$this->w,'D');
                $this->setfieldwidth(30,120);
                $this->textbox($detail_row_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                //$this->vline($this->liney,$this->liney+5,105);
                $this->newrow(5);
                $islastodd=0;
            }
            if ($this->isnewpage(20))
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
            //$dedtot+=$detail_row_2['DEDUCTIONAMOUNT'];
        }
        $this->newrow(2);
        $this->pdf->SetTextColor(0, 0, 0);
        if ($islastodd==1) 
        {
            $this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
    }
    function groupfooter_3(&$group_row_2)
    {      
        if ($this->isnewpage(20))
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        if ($flagcode != 1)
        {
            $this->setfieldwidth(25,65);
            $this->textbox($this->contractcategorysummary['TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->contractcategorysummary['HRAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(4);
            $this->textbox($this->contractcategorysummary['PRVBILLHRCRAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(-4);
            $this->setfieldwidth(25);
            $this->textbox($this->contractcategorysummary['TRAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(4);
            $this->textbox($this->contractcategorysummary['PRVBILLTRCRAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(-4);
/*             $this->newrow(4);
            $this->textbox($this->contractcategorysummary['INCENTIVEAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(-4); */
            $this->setfieldwidth(25);
            $this->textbox($this->contractcategorysummary['GROSSAMOUNT']+$this->contractcategorysummary['BUSINESSAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(4);
            $this->textbox($this->contractcategorysummary['DEPOSITAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(-4);
            $this->setfieldwidth(25);
            $this->textbox($this->contractcategorysummary['COMMISSIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(4);
            $this->textbox($this->contractcategorysummary['DIESELRATEDIFFERENCEAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(-4);
            $this->setfieldwidth(25);
            $this->textbox($this->contractcategorysummary['NETGROSSAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->contractcategorysummary['GROSSDEDUCTION'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->contractcategorysummary['NETAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(10);
            $this->hline(10,270,$this->liney,'D');

            $detail_query_2 = "select t.*,ROW_NUMBER() OVER (ORDER BY deductioncode,deductionnameuni) AS serialnumber
            from (select t.deductioncode
        ,deductionnameuni
        ,sum(t.deductionamount) as deductionamount from (select tt.deductioncode
        ,dd.deductionnameuni
        ,sum(tt.deductionamount) as deductionamount
         from HTBILLDEDUCTIONDETAIL tt
         ,deduction dd
         ,htbillheader t
        ,billperiodheader h
        ,subcontractor s
        where tt.deductioncode=dd.deductioncode 
        and t.transactionnumber=tt.billtransactionnumber
        and t.billperiodtransnumber=h.billperiodtransnumber
        and t.seasoncode=s.seasoncode and t.subcontractorcode=s.subcontractorcode
        and h.servicetrhrcategorycode not in (1,13)
        and h.seasonyear=".$_SESSION['yearperiodcode']." 
        and h.billcategorycode=".$this->billcategorycode." 
        and h.servicetrhrcategorycode=".$group_row_2['SERVICETRHRCATEGORYCODE']."
        and s.harvestersubcategorycode=".$group_row_2['HARVESTERSUBCATEGORYCODE']."
        and t.contractcategorycode=".$group_row_2['CONTRACTCATEGORYCODE']."
        group by tt.deductioncode,dd.deductionnameuni
        )t
        group by t.deductioncode,t.deductionnameuni)t";
        $detail_result_2 = oci_parse($this->connection, $detail_query_2);
        $r = oci_execute($detail_result_2);
        $islastodd=0;
        $this->pdf->SetTextColor(0, 0, 150);
        //$this->newrow(-2);
        while ($detail_row_2 = oci_fetch_array($detail_result_2,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($this->isnewpage(20))
            {
                //$this->newrow();
                //$this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            if ($detail_row_2['SERIALNUMBER']%3==1) 
            {
                //$this->setfieldwidth(07,10);
                //$this->textbox($detail_row_2['SERIALNUMBER'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(30,10);
                $this->textbox($detail_row_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                $islastodd=1;
            }
            elseif ($detail_row_2['SERIALNUMBER']%3==2) 
            {
                //$this->setfieldwidth(07,141);
                //$this->textbox($detail_row_2['SERIALNUMBER'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->vline($this->liney,$this->liney+5,$this->x+$this->w,'D');
                $this->setfieldwidth(30,65);
                $this->textbox($detail_row_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                //$this->vline($this->liney,$this->liney+5,105);
                $islastodd=1;
            }
            else
            {
                //$this->setfieldwidth(07,272);
                //$this->textbox($detail_row_2['SERIALNUMBER'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->vline($this->liney,$this->liney+5,$this->x+$this->w,'D');
                $this->setfieldwidth(30,120);
                $this->textbox($detail_row_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                //$this->vline($this->liney,$this->liney+5,105);
                $this->newrow(5);
                $islastodd=0;
            }
            if ($this->isnewpage(20))
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
        $this->newrow(2);
        $this->pdf->SetTextColor(0, 0, 0);
        if ($islastodd==1) 
        {
            $this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
        }
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
        if ($this->isnewpage(25))
        {
            $this->newrow(10);
            $this->hline(10,270,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        $this->hline(10,270,$this->liney-1,'C'); 
        $this->setfieldwidth(55,10);
        $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox($this->summary['TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->summary['HRAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(4);
        $this->textbox($this->summary['PRVBILLHRCRAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(-4);
        $this->setfieldwidth(25);
        $this->textbox($this->summary['TRAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(4);
        $this->textbox($this->summary['PRVBILLTRCRAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(-4);
        /* $this->newrow(4);
        $this->textbox($this->summary['INCENTIVEAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(-4); */
        $this->setfieldwidth(25);
        $this->textbox($this->summary['GROSSAMOUNT']+$this->summary['BUSINESSAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(4);
        $this->textbox($this->summary['DEPOSITAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(-4);
        $this->setfieldwidth(25);
        $this->textbox($this->summary['COMMISSIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(4);
        $this->textbox($this->summary['DIESELRATEDIFFERENCEAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(-4);
        $this->setfieldwidth(25);
        $this->textbox($this->summary['NETGROSSAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->summary['GROSSDEDUCTION'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->summary['NETAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(10);
        if ($this->flagcode == 1)
        {
            $groupfooter_query_1_2 = "select tt.deductioncode
            ,dd.deductionnameuni
            ,sum(tt.deductionamount) as deductionamount
            from HTBILLDEDUCTIONDETAIL tt
            ,deduction dd
            ,htbillheader t
            ,billperiodheader h
            where tt.deductioncode=dd.deductioncode 
            and t.transactionnumber=tt.billtransactionnumber
            and t.billperiodtransnumber=h.billperiodtransnumber
            and h.servicetrhrcategorycode in (1,13)
            and h.seasonyear=".$_SESSION['yearperiodcode']." 
            and h.billcategorycode=".$this->billcategorycode." 
            group by tt.deductioncode
            ,dd.deductionnameuni
            order by tt.deductioncode
            ,dd.deductionnameuni";
        }
        else
        {
            $groupfooter_query_1_2 = "select tt.deductioncode
        ,dd.deductionnameuni
        ,sum(tt.deductionamount) as deductionamount
         from HTBILLDEDUCTIONDETAIL tt
         ,deduction dd
         ,htbillheader t
        ,billperiodheader h
        where tt.deductioncode=dd.deductioncode 
        and t.transactionnumber=tt.billtransactionnumber
        and t.billperiodtransnumber=h.billperiodtransnumber
        and h.servicetrhrcategorycode not in (1,13)
        and h.seasonyear=".$_SESSION['yearperiodcode']." 
        and h.billcategorycode=".$this->billcategorycode." 
        group by tt.deductioncode
        ,dd.deductionnameuni
        order by tt.deductioncode
        ,dd.deductionnameuni";
            
        }
        $groupfooter_result_1_2 = oci_parse($this->connection, $groupfooter_query_1_2);
        $r = oci_execute($groupfooter_result_1_2);
        $i=1;
        while ($groupfooter_row_1_2 = oci_fetch_array($groupfooter_result_1_2,OCI_ASSOC+OCI_RETURN_NULLS))  
        {
            if ($i%4==1) 
            {
                $this->setfieldwidth(25,25);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(10);
                $islastodd=0;
            }
            elseif ($i%4==2) 
            {
                $this->setfieldwidth(25);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(10);
                $islastodd=0;
            }
            elseif ($i%4==3) 
            {
                $this->setfieldwidth(25);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(10);
                $islastodd=0;
            }
            else
            {
                $this->setfieldwidth(25);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(10);
                $islastodd=1;
                $this->newrow(5);
            }
            $i++;
        }
        if ($islastodd==0)
        {
            $this->newrow(10);
        }
        else
        {
            $this->newrow(5);
        }
        $this->hline(10,270,$this->liney-2,'D'); 
        if ($this->isnewpage(20))
        {
            $this->newrow(10);
            $this->hline(10,270,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow(10);
            //$this->hline(10,270,$this->liney-2,'C'); 
        }

        $this->setfieldwidth(20,20);
        $this->textbox('क्लार्क',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('केन अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('चिफ अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('जनरल मॅनेजर',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
    
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
    function servicetrhrcategory()
    {
        $query = "select servicetrhrcatnameuni,servicetrhrcatnameeng from billperiodheader b,servicetrhrcategory s where payeecategorycode=2 and b.servicetrhrcategorycode=s.servicetrhrcategorycode and b.billperiodtransnumber=".$this->billperiodtransnumber;
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $i=1;
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))  
        {
            return $row['SERVICETRHRCATNAMEUNI'];
        }
        else
        {
            return '';
        }
    }
}    
?>