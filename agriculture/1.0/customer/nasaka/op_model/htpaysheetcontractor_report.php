<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a3_l.php");
    include_once("../info/routine.php");
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class htpaysheetcontractor extends reportbox
{
    public $circlecode;
    public $villagecode;
    public $farmercode;
    public $billperiodtransnumber;
    public $servicetrhrcategorycode;
    public $msubtitle;
    public $circlename;
    public $villagename;
    public $farmercategoryname;
    public $farmercategorysummary;
    public $circlesummary;
    public $villagesummary;
    public $summary;
    public $harvestersubcategorysummary;
    public $contractorsummary;
    public $isfirstpage;

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
        $pageheader_query_1="select BILLPERIODDESC(".$this->billperiodtransnumber.") as desct from dual t";
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
        $this->setfieldwidth(120,165);
        $a=$this->servicetrhrcategory();
        $this->textbox($a.' पेमेंट रजिस्टर',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        $this->newrow();
        $this->setfieldwidth(395,10);
        $this->textbox($this->msubtitle,$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','');
        $this->newrow();
        $this->hline(10,405,$this->liney,'C');
        
        $this->setfieldwidth(40,10);
        $this->textbox('कंत्राटदाराचे नाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox('टनेज',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox('TR.रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox('HR.रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('डिपाॅझिट',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('कमिशन',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox('ए.रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox('कपात रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('देय रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(55);
        $this->textbox('बँक शाखा',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox('खाते नंबर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->hline(10,405,$this->liney,'C');
    }

    function group()
    {
        $this->totalgroupcount=0;
        $this->summary['HRTONNAGE']=0;
        $this->summary['TRTONNAGE']=0;
        $this->summary['HRAMOUNT']=0;
        $this->summary['TRAMOUNT']=0;
        $this->summary['HRTRTONNAGE']=0;
        $this->summary['COMMISSIONAMOUNT']=0;
        $this->summary['GROSSAMOUNT']=0;
        $this->summary['GROSSDEDUCTION']=0;
        $this->summary['NETAMOUNT']=0;
        $this->servicetrhrcategorycode();
        if ($this->servicetrhrcategorycode==2)
        {
            $this->totalgroupcount=2;
            $group_query_1 ="select hs.harvestersubcategorycode
            ,f.contractorcode
            ,t.contractcategorycode
            ,hs.harvestersubcategorynameuni
            ,s.subcontractorcode
            ,t.transactionnumber
            ,t.billnumber
            ,to_char(h.paymentdate,'dd/MM/yyyy') paymentdate
            ,f.contractornameuni,s.subcontractornameuni,hs.harvestersubcategorynameuni
            ,t.hrtonnage,t.trtonnage,t.hrtrtonnage,t.incentiveamount
            ,mm.tramount,mm.hramount,t.grossamount,t.grossdeduction
            ,t.depositamount,t.commissionamount,t.netgrossamount
            ,t.netamount,t.accountnumber,b.bankbranchnameuni,bk.banknameuni,bk.shortname
            ,h.servicetrhrcategorycode
            ,v.vehiclenumber 
            from htbillheader t,contractorcontract c,contractor f, subcontractor s
            ,billperiodheader h,bankbranch b,bank bk,harvestersubcategory hs
            ,vehicle v,(select s.billtransactionnumber,nvl(sum(s.tramount),0)tramount,nvl(sum(s.hramount),0)hramount
            from htbillslipdetail s group by s.billtransactionnumber)mm
            where t.seasoncode=c.seasoncode
            and c.seasoncode=s.seasoncode
            and c.contractorcode=f.contractorcode
            and c.seasoncode=s.seasoncode
            and c.contractcode=s.contractcode
            and t.seasoncode=s.seasoncode
            and t.subcontractorcode=s.subcontractorcode
            and t.contractorcode=f.contractorcode
            and t.seasoncode=v.seasoncode(+)
            and t.vehiclecode=v.vehiclecode(+)
            and t.billperiodtransnumber=h.billperiodtransnumber
            and b.bankcode=bk.bankcode(+) 
            and t.bankbranchcode=b.bankbranchcode(+)
            and s.harvestersubcategorycode=hs.harvestersubcategorycode(+)
             AND t.transactionnumber=mm.billtransactionnumber(+)
            and t.billperiodtransnumber=".$this->billperiodtransnumber." 
            order by hs.harvestersubcategorycode,t.contractcategorycode,billnumber";
        }
        else
        {
            $this->totalgroupcount=2;
            $group_query_1 ="select     1 dummycode, f.contractorcode
            ,s.subcontractorcode
            ,t.transactionnumber
            ,t.billnumber
            ,to_char(h.paymentdate,'dd/MM/yyyy') paymentdate
            ,f.contractornameuni,s.subcontractornameuni,hs.harvestersubcategorynameuni
            ,t.hrtonnage,t.trtonnage,t.hrtrtonnage,t.incentiveamount
            ,mm.tramount,mm.hramount,t.grossamount,t.grossdeduction
            ,t.depositamount,t.commissionamount,t.netgrossamount
            ,t.netamount,t.accountnumber,b.bankbranchnameuni,bk.banknameuni,bk.shortname
            ,h.servicetrhrcategorycode
            ,v.vehiclenumber 
            from htbillheader t,contractorcontract c,contractor f, subcontractor s
            ,billperiodheader h,bankbranch b,bank bk,harvestersubcategory hs
            ,vehicle v
            ,(select s.billtransactionnumber,nvl(sum(s.tramount),0)tramount,nvl(sum(s.hramount),0)hramount
            from htbillslipdetail s group by s.billtransactionnumber)mm
            where t.seasoncode=c.seasoncode
            and c.seasoncode=s.seasoncode
            and c.contractorcode=f.contractorcode
            and c.seasoncode=s.seasoncode
            and c.contractcode=s.contractcode
            and t.seasoncode=s.seasoncode
            and t.subcontractorcode=s.subcontractorcode
            and t.contractorcode=f.contractorcode
            and t.seasoncode=v.seasoncode(+)
            and t.vehiclecode=v.vehiclecode(+)
            and t.billperiodtransnumber=h.billperiodtransnumber
            and b.bankcode=bk.bankcode(+) 
            and t.bankbranchcode=b.bankbranchcode(+)
            and s.harvestersubcategorycode=hs.harvestersubcategorycode(+)
            AND t.transactionnumber=mm.billtransactionnumber(+)
            and t.billperiodtransnumber=".$this->billperiodtransnumber." 
            order by billnumber";
        }
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
    }
    
    function groupheader_1(&$group_row_1)
    {
        if ($this->servicetrhrcategorycode==2)
        {
            $this->harvestersubcategorysummary['HRTONNAGE']=0;
            $this->harvestersubcategorysummary['TRTONNAGE']=0;
            $this->harvestersubcategorysummary['HRTRTONNAGE']=0;
            $this->harvestersubcategorysummary['HRAMOUNT']=0;
            $this->harvestersubcategorysummary['TRAMOUNT']=0;
            $this->harvestersubcategorysummary['GROSSAMOUNT']=0;
            $this->harvestersubcategorysummary['COMMISSIONAMOUNT']=0;
            $this->harvestersubcategorysummary['NETGROSSAMOUNT']=0;
            $this->harvestersubcategorysummary['GROSSDEDUCTION']=0;
            $this->harvestersubcategorysummary['NETAMOUNT']=0;
            if ($this->isnewpage(20))
            {
                $this->newrow();
                $this->hline(10,405,$this->liney-2,'C'); 
                $this->newpage(True);
            }  
            $this->setfieldwidth(40,10);
            $this->textbox($group_row_1['HARVESTERSUBCATEGORYNAMEUNI'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',13,'','','','B');
            $this->newrow();
        }
    }

    function groupheader_2(&$group_row_1)
    {
        $this->contractorsummary['HRTONNAGE']=0;
        $this->contractorsummary['TRTONNAGE']=0;
        $this->contractorsummary['HRTRTONNAGE']=0;
        $this->contractorsummary['HRAMOUNT']=0;
        $this->contractorsummary['TRAMOUNT']=0;
        $this->contractorsummary['GROSSAMOUNT']=0;
        $this->contractorsummary['INCENTIVEAMOUNT']=0;
        $this->contractorsummary['COMMISSIONAMOUNT']=0;
        $this->contractorsummary['NETGROSSAMOUNT']=0;
        $this->contractorsummary['GROSSDEDUCTION']=0;
        $this->contractorsummary['NETAMOUNT']=0;
        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }  
        //$this->newrow();
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
        $this->summary['HRTONNAGE']=$this->summary['HRTONNAGE']+$group_row_1['HRTONNAGE'];
        $this->summary['TRTONNAGE']=$this->summary['TRTONNAGE']+$group_row_1['TRTONNAGE'];
        $this->summary['HRTRTONNAGE']=$this->summary['HRTRTONNAGE']+$group_row_1['HRTRTONNAGE'];
        $this->summary['HRAMOUNT']=$this->summary['HRAMOUNT']+$group_row_1['HRAMOUNT'];
        $this->summary['TRAMOUNT']=$this->summary['TRAMOUNT']+$group_row_1['TRAMOUNT'];

        $this->summary['INCENTIVEAMOUNT']=$this->summary['INCENTIVEAMOUNT']+$group_row_1['INCENTIVEAMOUNT'];
        $this->summary['COMMISSIONAMOUNT']=$this->summary['COMMISSIONAMOUNT']+$group_row_1['COMMISSIONAMOUNT'];
        $this->summary['GROSSAMOUNT']=$this->summary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->summary['NETGROSSAMOUNT']=$this->summary['NETGROSSAMOUNT']+$group_row_1['NETGROSSAMOUNT'];
        $this->summary['GROSSDEDUCTION']=$this->summary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->summary['NETAMOUNT']=$this->summary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        
        $this->harvestersubcategorysummary['HRTONNAGE']=$this->harvestersubcategorysummary['HRTONNAGE']+$group_row_1['HRTONNAGE'];
        $this->harvestersubcategorysummary['TRTONNAGE']=$this->harvestersubcategorysummary['TRTONNAGE']+$group_row_1['TRTONNAGE'];
        $this->harvestersubcategorysummary['HRTRTONNAGE']=$this->harvestersubcategorysummary['HRTRTONNAGE']+$group_row_1['HRTRTONNAGE'];
        $this->harvestersubcategorysummary['HRAMOUNT']=$this->harvestersubcategorysummary['HRAMOUNT']+$group_row_1['HRAMOUNT'];
        $this->harvestersubcategorysummary['TRAMOUNT']=$this->harvestersubcategorysummary['TRAMOUNT']+$group_row_1['TRAMOUNT'];

        $this->harvestersubcategorysummary['INCENTIVEAMOUNT']=$this->harvestersubcategorysummary['INCENTIVEAMOUNT']+$group_row_1['INCENTIVEAMOUNT'];
        $this->harvestersubcategorysummary['GROSSAMOUNT']=$this->harvestersubcategorysummary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->harvestersubcategorysummary['COMMISSIONAMOUNT']=$this->harvestersubcategorysummary['COMMISSIONAMOUNT']+$group_row_1['COMMISSIONAMOUNT'];
        $this->harvestersubcategorysummary['NETGROSSAMOUNT']=$this->harvestersubcategorysummary['NETGROSSAMOUNT']+$group_row_1['NETGROSSAMOUNT'];
        $this->harvestersubcategorysummary['GROSSDEDUCTION']=$this->harvestersubcategorysummary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->harvestersubcategorysummary['NETAMOUNT']=$this->harvestersubcategorysummary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        
        $this->contractorsummary['HRTONNAGE']=$this->contractorsummary['HRTONNAGE']+$group_row_1['HRTONNAGE'];
        $this->contractorsummary['TRTONNAGE']=$this->contractorsummary['TRTONNAGE']+$group_row_1['TRTONNAGE'];
        $this->contractorsummary['HRTRTONNAGE']=$this->contractorsummary['HRTRTONNAGE']+$group_row_1['HRTRTONNAGE'];
        $this->contractorsummary['HRAMOUNT']=$this->contractorsummary['HRAMOUNT']+$group_row_1['HRAMOUNT'];
        $this->contractorsummary['TRAMOUNT']=$this->contractorsummary['TRAMOUNT']+$group_row_1['TRAMOUNT'];

        $this->contractorsummary['INCENTIVEAMOUNT']=$this->contractorsummary['INCENTIVEAMOUNT']+$group_row_1['INCENTIVEAMOUNT'];
        $this->contractorsummary['COMMISSIONAMOUNT']=$this->contractorsummary['COMMISSIONAMOUNT']+$group_row_1['COMMISSIONAMOUNT'];
        $this->contractorsummary['GROSSAMOUNT']=$this->contractorsummary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->contractorsummary['NETGROSSAMOUNT']=$this->contractorsummary['NETGROSSAMOUNT']+$group_row_1['NETGROSSAMOUNT'];
        $this->contractorsummary['GROSSDEDUCTION']=$this->contractorsummary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->contractorsummary['NETAMOUNT']=$this->contractorsummary['NETAMOUNT']+$group_row_1['NETAMOUNT'];


    }


    function detail_2($transactionnumber)
    { 
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
        $this->setfieldwidth(50,10);
        $detail_query_2 = "select t.serialnumber
        ,case when t.dedseasonyear is null then 
        trim(trim(d.deductionnameuni) ||k.shortname||' '||trim(b.bankbranchnameuni)) 
        else
        trim(trim(d.deductionnameuni) ||'('|| trim(t.dedseasonyear)||') '||k.shortname||' '||trim(b.bankbranchnameuni)) 
        end dednameuni
          ,t.deductionamount
         from HTBILLDEDUCTIONDETAIL t
         ,deduction d
         ,bankbranch b
         ,bank k
        where t.deductioncode=d.deductioncode 
        and b.bankcode=k.bankcode(+)
        and t.branchcode=b.bankbranchcode(+) 
        and t.billtransactionnumber=".$transactionnumber." order by d.deductioncode";
        $detail_result_2 = oci_parse($this->connection, $detail_query_2);
        $r = oci_execute($detail_result_2);
        $islastodd=0;
        $this->pdf->SetTextColor(0, 0, 150);
        //$this->newrow(-2);
        while ($detail_row_2 = oci_fetch_array($detail_result_2,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($this->isnewpage(20))
            {
                $this->newrow();
                $this->hline(10,405,$this->liney-2,'C'); 
                $this->newpage(True);
            }  
            if ($detail_row_2['SERIALNUMBER']%5==1) 
            {
                //$this->setfieldwidth(07,10);
                //$this->textbox($detail_row_2['SERIALNUMBER'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(50,10);
                $this->textbox($detail_row_2['DEDNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                $islastodd=1;
            }
            elseif ($detail_row_2['SERIALNUMBER']%5==2) 
            {
                //$this->setfieldwidth(07,141);
                //$this->textbox($detail_row_2['SERIALNUMBER'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->vline($this->liney,$this->liney+5,$this->x+$this->w,'D');
                $this->setfieldwidth(50,60);
                $this->textbox($detail_row_2['DEDNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                //$this->vline($this->liney,$this->liney+5,105);
                $islastodd=1;
            }
            elseif ($detail_row_2['SERIALNUMBER']%5==3) 
            {
                //$this->setfieldwidth(07,141);
                //$this->textbox($detail_row_2['SERIALNUMBER'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->vline($this->liney,$this->liney+5,$this->x+$this->w,'D');
                $this->setfieldwidth(50,110);
                $this->textbox($detail_row_2['DEDNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                //$this->vline($this->liney,$this->liney+5,105);
                $islastodd=1;
            }
            elseif ($detail_row_2['SERIALNUMBER']%5==4) 
            {
                //$this->setfieldwidth(07,141);
                //$this->textbox($detail_row_2['SERIALNUMBER'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->vline($this->liney,$this->liney+5,$this->x+$this->w,'D');
                $this->setfieldwidth(50,160);
                $this->textbox($detail_row_2['DEDNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
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
                $this->setfieldwidth(50,210);
                $this->textbox($detail_row_2['DEDNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
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
    function groupfooter_1(&$group_row_1)
    { 
        if ($this->servicetrhrcategorycode==2)
        {
            if ($this->isnewpage(20))
            {
                $this->newrow();
                $this->hline(10,405,$this->liney-2,'C'); 
                $this->newpage(True);
            }  
            $this->setfieldwidth(40,10);
            $this->textbox($group_row_1['HARVESTERSUBCATEGORYNAMEUNI'].' एकूण',$this->w,$this->x,'S','R',1,'siddhanta',13,'','','','B');
            $this->setfieldwidth(25);
            if ($this->servicetrhrcategorycode==5 or $this->servicetrhrcategorycode==6)
            $this->textbox($this->harvestersubcategorysummary['HRTRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            elseif ($this->servicetrhrcategorycode==1)
            $this->textbox($this->harvestersubcategorysummary['TRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            elseif ($this->servicetrhrcategorycode==2)
            $this->textbox($this->harvestersubcategorysummary['HRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(20);
            $this->textbox($this->harvestersubcategorysummary['TRAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(20);
            $this->textbox($this->harvestersubcategorysummary['HRAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');

            $this->setfieldwidth(30);
            $this->textbox($this->harvestersubcategorysummary['GROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->newrow(4);
            $this->textbox($this->harvestersubcategorysummary['INCENTIVEAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->newrow(-4);
            $this->setfieldwidth(25);
            $this->setfieldwidth(25);
            $this->textbox($this->harvestersubcategorysummary['COMMISSIONAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->harvestersubcategorysummary['NETGROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->harvestersubcategorysummary['GROSSDEDUCTION'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->harvestersubcategorysummary['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(55);
            $this->textbox($group_row_1['BANKNAMEUNI'].', '.$group_row_1['BANKBRANCHNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',8,'','','','');
            $this->setfieldwidth(30);
            $this->textbox($group_row_1['ACCOUNTNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',8,'','','','');
            $this->newrow();

            $groupfooter_query_1_2 = "select tt.deductioncode
            ,dd.deductionnameuni
            ,sum(tt.deductionamount) as deductionamount
             from HTBILLDEDUCTIONDETAIL tt
             ,deduction dd
             ,htbillheader t
            ,billperiodheader h
            ,contractorcontract c,contractor f, subcontractor s,harvestersubcategory hs
            where t.seasoncode=c.seasoncode
            and c.seasoncode=s.seasoncode
            and c.contractorcode=f.contractorcode
            and c.seasoncode=s.seasoncode
            and c.contractcode=s.contractcode
            and t.seasoncode=s.seasoncode
            and t.subcontractorcode=s.subcontractorcode
            and s.harvestersubcategorycode=hs.harvestersubcategorycode(+)
            and s.harvestersubcategorycode=".$this->group_row_1['HARVESTERSUBCATEGORYCODE']."
            and tt.deductioncode=dd.deductioncode 
            and t.transactionnumber=tt.billtransactionnumber
            and t.billperiodtransnumber=h.billperiodtransnumber
            and t.billperiodtransnumber=".$this->billperiodtransnumber." 
            and h.servicetrhrcategorycode=".$this->servicetrhrcategorycode." 
            group by tt.deductioncode
            ,dd.deductionnameuni
            order by tt.deductioncode
            ,dd.deductionnameuni";
            $groupfooter_result_1_2 = oci_parse($this->connection, $groupfooter_query_1_2);
            $r = oci_execute($groupfooter_result_1_2);
            $i=1;
            while ($groupfooter_row_1_2 = oci_fetch_array($groupfooter_result_1_2,OCI_ASSOC+OCI_RETURN_NULLS))  
            {
                if ($this->isnewpage(20))
                {
                    $this->newrow();
                    $this->hline(10,405,$this->liney-2,'C'); 
                    $this->newpage(True);
                }  
                if ($i%6==1) 
                {
                    $this->setfieldwidth(30,30);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                    $this->setfieldwidth(25);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                    $this->setfieldwidth(10);
                    $islastodd=0;
                }
                elseif ($i%6==2) 
                {
                    $this->setfieldwidth(30);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                    $this->setfieldwidth(25);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                    $this->setfieldwidth(10);
                    $islastodd=0;
                }
                elseif ($i%6==3) 
                {
                    $this->setfieldwidth(30);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                    $this->setfieldwidth(25);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                    $this->setfieldwidth(10);
                    $islastodd=0;
                }
                elseif ($i%6==4) 
                {
                    $this->setfieldwidth(30);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                    $this->setfieldwidth(25);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                    $this->setfieldwidth(10);
                    $islastodd=0;
                }
                elseif ($i%6==5) 
                {
                    $this->setfieldwidth(30);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                    $this->setfieldwidth(25);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                    $this->setfieldwidth(10);
                    $islastodd=0;
                }
                else
                {
                    $this->setfieldwidth(30);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                    $this->setfieldwidth(25);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                    $this->newrow();
                    $islastodd=1;
                }
                $i++;
            }
            if ($islastodd==0)
            {
                $this->newrow();
            }
            $this->hline(10,405,$this->liney-2,'C'); 
            if ($this->isnewpage(20))
            {
                $this->newrow();
                $this->hline(10,405,$this->liney-2,'C'); 
                $this->newpage(True);
            }   
            else
            {
                $this->newrow();
                //$this->hline(10,405,$this->liney-2,'C'); 
            }
        }    
    }

    function groupfooter_2(&$group_row_1)
    {      
            if ($this->isnewpage(20))
            {
                $this->newrow();
                $this->hline(10,405,$this->liney-2,'C'); 
                $this->newpage(True);
            }  
            $this->setfieldwidth(40,10);
            $this->textbox($group_row_1['CONTRACTORCODE'].' '.$group_row_1['CONTRACTORNAMEUNI'],$this->w,$this->x,'N','L',1,'siddhanta',13,'','','','B');
            $this->setfieldwidth(25);
            if ($this->servicetrhrcategorycode==5 or $this->servicetrhrcategorycode==6)
            $this->textbox($this->contractorsummary['HRTRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            elseif ($this->servicetrhrcategorycode==1)
            $this->textbox($this->contractorsummary['TRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            elseif ($this->servicetrhrcategorycode==2)
            $this->textbox($this->contractorsummary['HRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(20);
            $this->textbox($this->contractorsummary['TRAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(20);
            $this->textbox($this->contractorsummary['HRAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');

            $this->setfieldwidth(30);
            $this->textbox($this->contractorsummary['GROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->newrow(4);
            $this->textbox($this->contractorsummary['INCENTIVEAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->newrow(-4);
            $this->setfieldwidth(25);
            $this->setfieldwidth(25);
            $this->textbox($this->contractorsummary['COMMISSIONAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->contractorsummary['NETGROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->contractorsummary['GROSSDEDUCTION'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->contractorsummary['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(55);
            $this->textbox($group_row_1['BANKNAMEUNI'].', '.$group_row_1['BANKBRANCHNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',8,'','','','');
            $this->setfieldwidth(30);
            $this->textbox($group_row_1['ACCOUNTNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',8,'','','','');

            $this->newrow(10);

            if ($this->servicetrhrcategorycode==2)
            {
                $cond = " and s.harvestersubcategorycode=".$group_row_1['HARVESTERSUBCATEGORYCODE']."
                and f.contractorcode=".$group_row_1['CONTRACTORCODE'];
            }
            else
            {
                $cond = " and f.contractorcode=".$group_row_1['CONTRACTORCODE'];
            }

            $groupfooter_query_1_2 = "select tt.deductioncode
            ,dd.deductionnameuni
            ,sum(tt.deductionamount) as deductionamount
             from HTBILLDEDUCTIONDETAIL tt
             ,deduction dd
             ,htbillheader t
            ,billperiodheader h
            ,contractorcontract c,contractor f, subcontractor s,harvestersubcategory hs
            where t.seasoncode=c.seasoncode
            and c.seasoncode=s.seasoncode
            and c.contractorcode=f.contractorcode
            and c.seasoncode=s.seasoncode
            and c.contractcode=s.contractcode
            and s.harvestersubcategorycode=hs.harvestersubcategorycode(+) 
            and t.seasoncode=s.seasoncode
            and t.subcontractorcode=s.subcontractorcode
            {$cond}
            and tt.deductioncode=dd.deductioncode 
            and t.transactionnumber=tt.billtransactionnumber
            and t.billperiodtransnumber=h.billperiodtransnumber
            and t.billperiodtransnumber=".$this->billperiodtransnumber." 
            and h.servicetrhrcategorycode=".$this->servicetrhrcategorycode." 
            group by tt.deductioncode
            ,dd.deductionnameuni
            order by tt.deductioncode
            ,dd.deductionnameuni";
            $groupfooter_result_1_2 = oci_parse($this->connection, $groupfooter_query_1_2);
            $r = oci_execute($groupfooter_result_1_2);
            $i=1;
            while ($groupfooter_row_1_2 = oci_fetch_array($groupfooter_result_1_2,OCI_ASSOC+OCI_RETURN_NULLS))  
            {
                if ($this->isnewpage(20))
                {
                    $this->newrow();
                    $this->hline(10,405,$this->liney-2,'C'); 
                    $this->newpage(True);
                }  
                if ($i%6==1) 
                {
                    $this->setfieldwidth(30,30);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                    $this->setfieldwidth(25);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                    $this->setfieldwidth(10);
                    $islastodd=0;
                }
                elseif ($i%6==2) 
                {
                    $this->setfieldwidth(30);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                    $this->setfieldwidth(25);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                    $this->setfieldwidth(10);
                    $islastodd=0;
                }
                elseif ($i%6==3) 
                {
                    $this->setfieldwidth(30);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                    $this->setfieldwidth(25);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                    $this->setfieldwidth(10);
                    $islastodd=0;
                }
                elseif ($i%6==4) 
                {
                    $this->setfieldwidth(30);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                    $this->setfieldwidth(25);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                    $this->setfieldwidth(10);
                    $islastodd=0;
                }
                elseif ($i%6==5) 
                {
                    $this->setfieldwidth(30);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                    $this->setfieldwidth(25);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                    $this->setfieldwidth(10);
                    $islastodd=0;
                }
                else
                {
                    $this->setfieldwidth(30);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                    $this->setfieldwidth(25);
                    $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                    $this->newrow();
                    $islastodd=1;
                }
                $i++;
            }
            if ($islastodd==0)
            {
                $this->newrow();
            }
            $this->hline(10,405,$this->liney-2,'C'); 
            if ($this->isnewpage(20))
            {
                $this->newrow();
                $this->hline(10,405,$this->liney-2,'C'); 
                $this->newpage(True);
            }   
            else
            {
                //$this->newrow();
                //$this->hline(10,405,$this->liney-2,'C'); 
            }
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
        if ($this->isnewpage(40))
            {
                $this->newrow();
                $this->hline(10,405,$this->liney-2,'C'); 
                $this->newpage(True,True);
            }
            else
            {
                //$this->newrow();
            }
        
        $this->setfieldwidth(40,10);
        $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','R',1,'siddhanta',13,'','','','B');
        $this->setfieldwidth(25);
        if ($this->servicetrhrcategorycode==5 or $this->servicetrhrcategorycode==6)
        $this->textbox($this->summary['HRTRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        elseif ($this->servicetrhrcategorycode==1)
        $this->textbox($this->summary['TRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        elseif ($this->servicetrhrcategorycode==2)
        $this->textbox($this->summary['HRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($this->summary['TRAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($this->summary['HRAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');

        $this->setfieldwidth(30);
        $this->textbox($this->summary['GROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(4);
        $this->textbox($this->summary['INCENTIVEAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(-4);
        $this->setfieldwidth(20);
        $this->setfieldwidth(25);
        $this->textbox($this->summary['COMMISSIONAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->summary['NETGROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->summary['GROSSDEDUCTION'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->summary['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(10);

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
        and t.billperiodtransnumber=".$this->billperiodtransnumber."  
        group by tt.deductioncode
        ,dd.deductionnameuni
        order by tt.deductioncode
        ,dd.deductionnameuni";
        $groupfooter_result_1_2 = oci_parse($this->connection, $groupfooter_query_1_2);
        $r = oci_execute($groupfooter_result_1_2);
        $i=1;
        while ($groupfooter_row_1_2 = oci_fetch_array($groupfooter_result_1_2,OCI_ASSOC+OCI_RETURN_NULLS))  
        {
            if ($this->isnewpage(30))
            {
                $this->newrow();
                $this->hline(10,405,$this->liney-2,'C'); 
                $this->newpage(True,True);
            }  
            if ($i%6==1) 
            {
                $this->setfieldwidth(30,30);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(10);
                $islastodd=0;
            }
            elseif ($i%6==2) 
            {
                $this->setfieldwidth(30);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(10);
                $islastodd=0;
            }
            elseif ($i%6==3) 
            {
                $this->setfieldwidth(30);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(10);
                $islastodd=0;
            }
            elseif ($i%6==4) 
            {
                $this->setfieldwidth(30);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(10);
                $islastodd=0;
            }
            elseif ($i%6==5) 
            {
                $this->setfieldwidth(30);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(10);
                $islastodd=0;
            }
            else
            {
                $this->setfieldwidth(30);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($groupfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->newrow();
                $islastodd=1;
            }
            $i++;
        }
        if ($islastodd==0)
        {
            $this->newrow();
        }
        //$this->hline(10,405,$this->liney-2,'C'); 
        if ($this->isnewpage(20))
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True,True);
            $this->newrow(20);
        }   
        else
        {
            //$this->newrow(20);
            $this->hline(10,405,$this->liney-2,'C'); 
        }

        $this->newrow(20);
        $this->setfieldwidth(20,100);
        $this->textbox('क्लार्क',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('केन अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('चिफ अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
    
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
    function servicetrhrcategorycode()
    {
        $query = "select s.servicetrhrcategorycode,servicetrhrcatnameuni,servicetrhrcatnameeng from billperiodheader b,servicetrhrcategory s where payeecategorycode=2 and b.servicetrhrcategorycode=s.servicetrhrcategorycode and b.billperiodtransnumber=".$this->billperiodtransnumber;
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $i=1;
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))  
        {
            $this->servicetrhrcategorycode= $row['SERVICETRHRCATEGORYCODE'];
        }
    }
}    
?>