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
class businessledger extends reportbox
{
    public $circlecode;
    public $villagecode;
    public $farmercode;
    public $billtypemm;
    public $billperiodtransnumber;
    public $billperiodtransnumbermm;
    public $servicetrhrcategorycode;
    public $msubtitle;
    public $circlename;
    public $villagename;
    public $farmercategoryname;
    public $farmercategorysummary;
    public $circlesummary;
    public $villagesummary;
    public $fortrnightsummary;
    public $summary;
    public $harvestersubcategorysummary;
    public $isfirstpage;
    public $contractorcode;
    public $subcontractorcode;
    public $vehiclecode;
    public $vehiclesummary;

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
        
        $this->setfieldwidth(20,10);
        $this->textbox('नंबर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(45);
        $this->textbox('कंत्राटदाराचे नाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(55);
        $this->textbox('सब कंत्राटदाराचे नाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('वाहन',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('टनेज',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->newrow(5);
        if ($this->billcategorycode == 3 or $this->billcategorycode == 4 or $this->billcategorycode == 5)
        $this->textbox('धंदा',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        else
        $this->textbox('जादा रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->newrow(-5);
        $this->setfieldwidth(25);
        if ($this->billcategorycode == 3 or $this->billcategorycode == 5)
        $this->textbox('डिपाॅझिट',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        else
        $this->textbox('डिझेल फरक',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('कमिशन',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('ए.रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox('कपात रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('देय रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
       /*  $this->setfieldwidth(55);
        $this->textbox('बँक शाखा',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox('खाते नंबर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B'); */
        $this->newrow(10);
        $this->hline(10,405,$this->liney,'C');
    }

    function group()
    {
        $this->totalgroupcount=0;
        $this->summary['HRTONNAGE']=0;
        $this->summary['TRTONNAGE']=0;
        $this->summary['HRTRTONNAGE']=0;
        $this->summary['COMMISSIONAMOUNT']=0;
        $this->summary['GROSSAMOUNT']=0;
        $this->summary['GROSSDEDUCTION']=0;
        $this->summary['NETAMOUNT']=0;
        //$this->servicetrhrcategorycode();
        $cond=" and h.seasonyear=".$_SESSION['yearperiodcode'];

        if ($this->contractorcode != '' and $this->contractorcode != 0)
        {
            $cond=$cond." and f.contractorcode=".$this->contractorcode;
        }

        if ($this->subcontractorcode != '' and $this->subcontractorcode != 0)
        {
            $cond=$cond." and s.subcontractorcode=".$this->subcontractorcode;
        }
        $cond1=" and 1=1";
        if ($this->vehiclecode != '' and $this->vehiclecode != 0)
        {
            $cond1=$cond1." and v.vehiclecode=".$this->vehiclecode;
        }

            $this->totalgroupcount=3;
            $group_query_1 ="  select contractorcode,subcontractorcode,vehiclecode, b.billcategorycode
            ,b.billperiodnumber,bt.billtypenameuni
            ,transactionnumber,adhikarpatracontractornameuni
            ,t.billcategorycode,billnumber,t.billperiodtransnumber,t.paymentdate
            ,contractornameuni,subcontractornameuni,harvestersubcategorynameuni
            ,hrtonnage,trtonnage,hrtrtonnage,incentiveamount,grossamount,grossdeduction
            ,depositamount,commissionamount,dieselratedifferenceamount,netgrossamount,businessamount
            ,netamount,accountnumber,bankbranchnameuni,banknameuni,shortname
            ,prvbilltrcramount,prvbillhrcramount,t.servicetrhrcategorycode,vehiclenumber
             from (select f.contractorcode
            ,s.subcontractorcode
            ,t.transactionnumber
            ,h.billcategorycode
            ,t.billnumber
            ,t.billperiodtransnumber
            ,to_char(h.paymentdate,'dd/MM/yyyy') paymentdate
            ,f.contractornameuni,s.subcontractornameuni,hs.harvestersubcategorynameuni
            ,t.hrtonnage,t.trtonnage,t.hrtrtonnage,t.incentiveamount,t.grossamount,t.grossdeduction
            ,t.depositamount,t.commissionamount,t.dieselratedifferenceamount,t.netgrossamount,t.businessamount
            ,t.netamount,t.accountnumber,b.bankbranchnameuni,bk.banknameuni,bk.shortname
            ,t.prvbilltrcramount,t.prvbillhrcramount
            ,h.servicetrhrcategorycode
            ,v.vehiclenumber ,v.vehiclecode
            ,case when adhikarpatra=1 then k.contractornameuni end adhikarpatracontractornameuni
            from htbillheader t,contractorcontract c,contractor f, subcontractor s
            ,billperiodheader h,bankbranch b,bank bk,harvestersubcategory hs
            ,vehicle v,contractorwithadhikarpatra k
            where h.billcategorycode<>5
            and t.seasoncode=c.seasoncode
            and c.seasoncode=s.seasoncode
            and c.contractorcode=f.contractorcode
            and c.seasoncode=s.seasoncode
            and c.contractcode=s.contractcode
            and t.seasoncode=s.seasoncode
            and t.subcontractorcode=s.subcontractorcode
            and t.contractorcode=f.contractorcode
            and c.seasoncode=k.seasoncode
            and c.contractorcode=k.contractorcode
            and t.seasoncode=v.seasoncode(+)
            and t.vehiclecode=v.vehiclecode(+)
            and t.billperiodtransnumber=h.billperiodtransnumber
            and b.bankcode=bk.bankcode(+) 
            and t.bankbranchcode=b.bankbranchcode(+)
            and s.harvestersubcategorycode=hs.harvestersubcategorycode(+) 
              {$cond} {$cond1}
            )t,billperiodheader b,billtype bt
            where t.billperiodtransnumber=b.billperiodtransnumber
            and b.billcategorycode=bt.billtypecode  
           -- and b.billcategorycode=1
            order by contractorcode,subcontractorcode,vehiclecode,b.billcategorycode, billperiodnumber,billnumber";
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i == 0)
            {
                $this->billcategorycode = $group_row_1['BILLCATEGORYCODE'];
                $this->newpage(true);
                $i++;
            }
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->detail_2($group_row_1['TRANSACTIONNUMBER']);  
            $this->hline(10,405,$this->liney,'D'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
    }
    
    function groupheader_1(&$group_row_1)
    {
        $this->billtypemm=$group_row_1['BILLCATEGORYCODE'];
        //if ($this->servicetrhrcategorycode==2)
        //{
            $this->harvestersubcategorysummary['HRTONNAGE']=0;
            $this->harvestersubcategorysummary['TRTONNAGE']=0;
            $this->harvestersubcategorysummary['HRTRTONNAGE']=0;
            $this->harvestersubcategorysummary['GROSSAMOUNT']=0;
            $this->harvestersubcategorysummary['COMMISSIONAMOUNT']=0;
            $this->harvestersubcategorysummary['NETGROSSAMOUNT']=0;
            $this->harvestersubcategorysummary['GROSSDEDUCTION']=0;
            $this->harvestersubcategorysummary['NETAMOUNT']=0;
            $this->harvestersubcategorysummary['DEPOSITAMOUNT']=0;
            $this->harvestersubcategorysummary['BUSINESSAMOUNT']=0;
            if ($this->isnewpage(20))
            {
                $this->newrow();
                $this->hline(10,405,$this->liney-2,'C'); 
                $this->newpage(True);
            }  
            $this->setfieldwidth(200,10);
            $this->textbox('Contractor -'.$group_row_1['CONTRACTORNAMEUNI'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',13,'','','','B');
            $this->newrow();
       // }
        
    }

    function groupheader_2(&$group_row_1)
    {
        //if ($this->servicetrhrcategorycode==2)
        //{
            $this->billperiodtransnumbermm=$group_row_1['BILLPERIODNUMBER'];
            $this->fortrnightsummary['HRTONNAGE']=0;
            $this->fortrnightsummary['TRTONNAGE']=0;
            $this->fortrnightsummary['HRTRTONNAGE']=0;
            $this->fortrnightsummary['GROSSAMOUNT']=0;
            $this->fortrnightsummary['COMMISSIONAMOUNT']=0;
            $this->fortrnightsummary['NETGROSSAMOUNT']=0;
            $this->fortrnightsummary['GROSSDEDUCTION']=0;
            $this->fortrnightsummary['NETAMOUNT']=0;
            $this->fortrnightsummary['DEPOSITAMOUNT']=0;
            $this->fortrnightsummary['BUSINESSAMOUNT']=0;
            if ($this->isnewpage(20))
            {
                $this->newrow();
                $this->hline(10,405,$this->liney-2,'C'); 
                $this->newpage(True);
            }  
            $this->setfieldwidth(200,10);
            $this->textbox('Sub Contractor:-'.$group_row_1['SUBCONTRACTORNAMEUNI'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11,'','','','B');
            $this->newrow();
       // }
    }

    function groupheader_3(&$group_row_1)
    {
        $this->billperiodtransnumbermm=$group_row_1['BILLPERIODNUMBER'];
        $this->vehiclesummary['HRTONNAGE']=0;
        $this->vehiclesummary['TRTONNAGE']=0;
        $this->vehiclesummary['HRTRTONNAGE']=0;
        $this->vehiclesummary['GROSSAMOUNT']=0;
        $this->vehiclesummary['COMMISSIONAMOUNT']=0;
        $this->vehiclesummary['NETGROSSAMOUNT']=0;
        $this->vehiclesummary['GROSSDEDUCTION']=0;
        $this->vehiclesummary['NETAMOUNT']=0;
        $this->vehiclesummary['DEPOSITAMOUNT']=0;
        $this->vehiclesummary['BUSINESSAMOUNT']=0;
        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }  
        $this->setfieldwidth(200,10);
        $this->textbox('Vehicle Number:-'.$group_row_1['VEHICLENUMBER'],$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        $this->newrow();
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
        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
        //$this->hline(10,405,$this->liney-2,'D'); 
        $this->setfieldwidth(60,10);
        $this->textbox('Fortnight No -'.$group_row_1['BILLPERIODNUMBER'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
       
        
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['VEHICLENUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',9,'','','','');
        $this->setfieldwidth(20);
        if ($group_row_1['SERVICETRHRCATEGORYCODE']==5 or $group_row_1['SERVICETRHRCATEGORYCODE']==6)
        $this->textbox($group_row_1['HRTRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        elseif ($group_row_1['SERVICETRHRCATEGORYCODE']==1)
        $this->textbox($group_row_1['TRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        elseif ($group_row_1['SERVICETRHRCATEGORYCODE']==2 or $group_row_1['SERVICETRHRCATEGORYCODE']==12)
        $this->textbox($group_row_1['HRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        if ($group_row_1['PRVBILLHRCRAMOUNT']>0)
        {
            $this->newrow(4);
            $this->pdf->SetTextColor(0, 230, 0);
            $this->textbox('+'.$group_row_1['PRVBILLHRCRAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->pdf->SetTextColor(0, 0, 0);
            $this->newrow(-4);
        }
        elseif ($group_row_1['PRVBILLHRCRAMOUNT']<0)
        {
            $this->newrow(4);
            $this->pdf->SetTextColor(230, 0, 0);
            $this->textbox('-'.$group_row_1['PRVBILLHRCRAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->pdf->SetTextColor(0, 0, 0);
            $this->newrow(-4);
        }

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['GROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        if ($this->billcategorycode == 3  or $this->billcategorycode == 4 or $this->billcategorycode == 5)
        {
            $this->newrow(4);
            $this->pdf->SetTextColor(230, 0, 230);
            $this->textbox($group_row_1['BUSINESSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->pdf->SetTextColor(0, 0, 0);
            $this->newrow(-4);
        }
        elseif ($group_row_1['INCENTIVEAMOUNT']>0)
        {
            $this->newrow(4);
            $this->pdf->SetTextColor(230, 0, 230);
            $this->textbox($group_row_1['INCENTIVEAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->pdf->SetTextColor(0, 0, 0);
            $this->newrow(-4);
        }
        if ($group_row_1['PRVBILLTRCRAMOUNT']>0)
        {
            $this->newrow(4);
            $this->pdf->SetTextColor(0, 230, 0);
            $this->textbox('+'.$group_row_1['PRVBILLTRCRAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->pdf->SetTextColor(0, 0, 0);
            $this->newrow(-4);
        }
        elseif ($group_row_1['PRVBILLTRCRAMOUNT']<0)
        {
            $this->newrow(4);
            $this->pdf->SetTextColor(230, 0, 0);
            $this->textbox('-'.$group_row_1['PRVBILLTRCRAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->pdf->SetTextColor(0, 0, 0);
            $this->newrow(-4);
        }
        $this->setfieldwidth(25);
        if ($this->billcategorycode == 3 or $this->billcategorycode == 5)
            $this->textbox($group_row_1['DEPOSITAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        else
            $this->textbox($group_row_1['DIESELRATEDIFFERENCEAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['COMMISSIONAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['NETGROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['GROSSDEDUCTION'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
        $this->pdf->SetTextColor(150, 0, 0);
        $this->textbox($group_row_1['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->pdf->SetTextColor(0, 0, 0);
       /*  $this->setfieldwidth(55);
        $this->textbox($group_row_1['BANKNAMEUNI'].', '.$group_row_1['BANKBRANCHNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',8,'','','','');
        if ($group_row_1['ADHIKARPATRACONTRACTORNAMEUNI']!='')
        {
            $this->newrow(5);
            $this->textbox($group_row_1['ADHIKARPATRACONTRACTORNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',8,'','','','');
            $this->newrow(-5);
        }
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['ACCOUNTNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',8,'','','',''); */
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
        
        if ($group_row_1['BILLCATEGORYCODE']==1)
        {
            $this->summary['HRTONNAGE']=$this->summary['HRTONNAGE']+$group_row_1['HRTONNAGE'];
            $this->summary['TRTONNAGE']=$this->summary['TRTONNAGE']+$group_row_1['TRTONNAGE'];
            $this->summary['HRTRTONNAGE']=$this->summary['HRTRTONNAGE']+$group_row_1['HRTRTONNAGE'];
        }
        $this->summary['INCENTIVEAMOUNT']=$this->summary['INCENTIVEAMOUNT']+$group_row_1['INCENTIVEAMOUNT'];
        $this->summary['COMMISSIONAMOUNT']=$this->summary['COMMISSIONAMOUNT']+$group_row_1['COMMISSIONAMOUNT'];
        $this->summary['DIESELRATEDIFFERENCEAMOUNT']=$this->summary['DIESELRATEDIFFERENCEAMOUNT']+$group_row_1['DIESELRATEDIFFERENCEAMOUNT'];
        $this->summary['GROSSAMOUNT']=$this->summary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->summary['NETGROSSAMOUNT']=$this->summary['NETGROSSAMOUNT']+$group_row_1['NETGROSSAMOUNT'];
        $this->summary['GROSSDEDUCTION']=$this->summary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->summary['NETAMOUNT']=$this->summary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        $this->summary['DEPOSITAMOUNT']=$this->summary['DEPOSITAMOUNT']+$group_row_1['DEPOSITAMOUNT'];
        $this->summary['BUSINESSAMOUNT']=$this->summary['BUSINESSAMOUNT']+$group_row_1['BUSINESSAMOUNT'];

        $this->harvestersubcategorysummary['HRTONNAGE']=$this->harvestersubcategorysummary['HRTONNAGE']+$group_row_1['HRTONNAGE'];
        $this->harvestersubcategorysummary['TRTONNAGE']=$this->harvestersubcategorysummary['TRTONNAGE']+$group_row_1['TRTONNAGE'];
        $this->harvestersubcategorysummary['HRTRTONNAGE']=$this->harvestersubcategorysummary['HRTRTONNAGE']+$group_row_1['HRTRTONNAGE'];
        $this->harvestersubcategorysummary['INCENTIVEAMOUNT']=$this->harvestersubcategorysummary['INCENTIVEAMOUNT']+$group_row_1['INCENTIVEAMOUNT'];
        $this->harvestersubcategorysummary['GROSSAMOUNT']=$this->harvestersubcategorysummary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->harvestersubcategorysummary['COMMISSIONAMOUNT']=$this->harvestersubcategorysummary['COMMISSIONAMOUNT']+$group_row_1['COMMISSIONAMOUNT'];
        //$this->harvestersubcategorysummary['DIESELRATEDIFFERENCEAMOUNT']=$this->harvestersubcategorysummary['DIESELRATEDIFFERENCEAMOUNT']+$harvestersubcategorysummary['DIESELRATEDIFFERENCEAMOUNT'];
        $this->harvestersubcategorysummary['NETGROSSAMOUNT']=$this->harvestersubcategorysummary['NETGROSSAMOUNT']+$group_row_1['NETGROSSAMOUNT'];
        $this->harvestersubcategorysummary['GROSSDEDUCTION']=$this->harvestersubcategorysummary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->harvestersubcategorysummary['NETAMOUNT']=$this->harvestersubcategorysummary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        $this->harvestersubcategorysummary['DEPOSITAMOUNT']=$this->harvestersubcategorysummary['DEPOSITAMOUNT']+$group_row_1['DEPOSITAMOUNT'];
        $this->harvestersubcategorysummary['BUSINESSAMOUNT']=$this->harvestersubcategorysummary['BUSINESSAMOUNT']+$group_row_1['BUSINESSAMOUNT'];
    
        $this->fortrnightsummary['HRTONNAGE']=$this->fortrnightsummary['HRTONNAGE']+$group_row_1['HRTONNAGE'];
        $this->fortrnightsummary['TRTONNAGE']=$this->fortrnightsummary['TRTONNAGE']+$group_row_1['TRTONNAGE'];
        $this->fortrnightsummary['HRTRTONNAGE']=$this->fortrnightsummary['HRTRTONNAGE']+$group_row_1['HRTRTONNAGE'];
        $this->fortrnightsummary['INCENTIVEAMOUNT']=$this->fortrnightsummary['INCENTIVEAMOUNT']+$group_row_1['INCENTIVEAMOUNT'];
        $this->fortrnightsummary['GROSSAMOUNT']=$this->fortrnightsummary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->fortrnightsummary['COMMISSIONAMOUNT']=$this->fortrnightsummary['COMMISSIONAMOUNT']+$group_row_1['COMMISSIONAMOUNT'];
        $this->fortrnightsummary['NETGROSSAMOUNT']=$this->fortrnightsummary['NETGROSSAMOUNT']+$group_row_1['NETGROSSAMOUNT'];
        $this->fortrnightsummary['GROSSDEDUCTION']=$this->fortrnightsummary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->fortrnightsummary['NETAMOUNT']=$this->fortrnightsummary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        $this->fortrnightsummary['DEPOSITAMOUNT']=$this->fortrnightsummary['DEPOSITAMOUNT']+$group_row_1['DEPOSITAMOUNT'];
        $this->fortrnightsummary['BUSINESSAMOUNT']=$this->fortrnightsummary['BUSINESSAMOUNT']+$group_row_1['BUSINESSAMOUNT'];

        $this->vehiclesummary['HRTONNAGE']=$this->vehiclesummary['HRTONNAGE']+$group_row_1['HRTONNAGE'];
        $this->vehiclesummary['TRTONNAGE']=$this->vehiclesummary['TRTONNAGE']+$group_row_1['TRTONNAGE'];
        $this->vehiclesummary['HRTRTONNAGE']=$this->vehiclesummary['HRTRTONNAGE']+$group_row_1['HRTRTONNAGE'];
        $this->vehiclesummary['INCENTIVEAMOUNT']=$this->vehiclesummary['INCENTIVEAMOUNT']+$group_row_1['INCENTIVEAMOUNT'];
        $this->vehiclesummary['GROSSAMOUNT']=$this->vehiclesummary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->vehiclesummary['COMMISSIONAMOUNT']=$this->vehiclesummary['COMMISSIONAMOUNT']+$group_row_1['COMMISSIONAMOUNT'];
        $this->vehiclesummary['NETGROSSAMOUNT']=$this->vehiclesummary['NETGROSSAMOUNT']+$group_row_1['NETGROSSAMOUNT'];
        $this->vehiclesummary['GROSSDEDUCTION']=$this->vehiclesummary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->vehiclesummary['NETAMOUNT']=$this->vehiclesummary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        $this->vehiclesummary['DEPOSITAMOUNT']=$this->vehiclesummary['DEPOSITAMOUNT']+$group_row_1['DEPOSITAMOUNT'];
        $this->vehiclesummary['BUSINESSAMOUNT']=$this->vehiclesummary['BUSINESSAMOUNT']+$group_row_1['BUSINESSAMOUNT'];


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
        if ($transactionnumber>0)
        {
            $detail_query_2 = "select t.serialnumber
            ,case when t.dedseasonyear is null then 
            trim(trim(d.deductionnameuni) ||k.shortname||' '||trim(b.bankbranchnameuni)) 
            else
            trim(trim(d.deductionnameuni) ||'('|| trim(t.dedseasonyear)||') '||k.shortname||' '||trim(b.bankbranchnameuni)) 
            end dednameuni
            ,t.deductionamount
            ,case when c.contractornameuni is not null then '('||c.contractornameuni||')' else '' end as contractornameuni
            from HTBILLDEDUCTIONDETAIL t
            ,deduction d
            ,bankbranch b
            ,bank k
            ,contractor c
            where t.deductioncode=d.deductioncode 
            and b.bankcode=k.bankcode(+)
            and t.branchcode=b.bankbranchcode(+) 
            and t.garcontractorcode=c.contractorcode(+)
            and t.billtransactionnumber=".$transactionnumber." order by d.deductioncode";
        }
        else
        {
            $cond=" and h.seasoncode=".$_SESSION['yearperiodcode'];

            if ($this->contractorcode != '' and $this->contractorcode != 0)
            {
                $cond=$cond." and h.contractorcode=".$this->contractorcode;
            }

            if ($this->subcontractorcode != '' and $this->subcontractorcode != 0)
            {
                $cond=$cond." and h.subcontractorcode=".$this->subcontractorcode;
            }
            //$cond1=" and 1=1";
            if ($this->vehiclecode != '' and $this->vehiclecode != 0)
            {
                $cond=$cond." and h.vehiclecode=".$this->vehiclecode;
            }

                $detail_query_2 = "select  case when dedseasonyear is null then 
                trim(trim(deductionnameuni) ||shortname||' '||trim(bankbranchnameuni)) 
                else
                trim(trim(deductionnameuni) ||'('|| trim(dedseasonyear)||') '||shortname||' '||trim(bankbranchnameuni)) 
                end dednameuni
                ,deductionamount
                from (
                select d.deductionnameuni,k.shortname,b.bankbranchnameuni,t.dedseasonyear,sum(t.deductionamount)deductionamount
                from htbillheader h,HTBILLDEDUCTIONDETAIL t,BILLPERIODHEADER b
                ,deduction d
                ,bankbranch b
                ,bank k
                ,contractor c
                where h.transactionnumber=t.billtransactionnumber 
                and t.deductioncode=d.deductioncode 
                and b.billperiodtransnumber=billperiodtransnumber
                and b.billcategorycode<>5
                and b.bankcode=k.bankcode(+)
                and t.branchcode=b.bankbranchcode(+) 
                and t.garcontractorcode=c.contractorcode(+) 
                {$cond}
                 group by d.deductioncode,d.deductionnameuni,k.shortname,b.bankbranchnameuni,t.dedseasonyear
                order by d.deductioncode,d.deductionnameuni,k.shortname,b.bankbranchnameuni,t.dedseasonyear) 
                ";


        }
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
            if ($detail_row_2['SERIALNUMBER']%3==1) 
            {
                //$this->setfieldwidth(07,10);
                //$this->textbox($detail_row_2['SERIALNUMBER'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(80,10);
                $this->textbox($detail_row_2['CONTRACTORNAMEUNI'].' '.$detail_row_2['DEDNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                $islastodd=1;
            }
            elseif ($detail_row_2['SERIALNUMBER']%3==2) 
            {
                //$this->setfieldwidth(07,141);
                //$this->textbox($detail_row_2['SERIALNUMBER'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->vline($this->liney,$this->liney+5,$this->x+$this->w,'D');
                $this->setfieldwidth(80);
                $this->textbox($detail_row_2['CONTRACTORNAMEUNI'].' '.$detail_row_2['DEDNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                //$this->vline($this->liney,$this->liney+5,105);
                $islastodd=1;
            }
            else
            {
                //$this->setfieldwidth(07,141);
                //$this->textbox($detail_row_2['SERIALNUMBER'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->vline($this->liney,$this->liney+5,$this->x+$this->w,'D');
                $this->setfieldwidth(80);
                $this->textbox($detail_row_2['CONTRACTORNAMEUNI'].' '.$detail_row_2['DEDNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                //$this->vline($this->liney,$this->liney+5,105);
                $islastodd=0;
                $this->newrow(4);
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
       // if ($this->servicetrhrcategorycode==2)
       // {
            if ($this->isnewpage(20))
            {
                $this->newrow();
                $this->hline(10,405,$this->liney-2,'C'); 
                $this->newpage(True);
            }  
            $this->setfieldwidth(30,25);
            $this->textbox(' एकूण ',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
            $this->setfieldwidth(64);
            //if ($this->servicetrhrcategorycode==5 or $this->servicetrhrcategorycode==6)
            $this->textbox($this->harvestersubcategorysummary['HRTRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->textbox($this->harvestersubcategorysummary['HRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            //elseif ($this->servicetrhrcategorycode==1)
          //  $this->textbox($this->harvestersubcategorysummary['TRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            //elseif ($this->servicetrhrcategorycode==2 or $this->servicetrhrcategorycode==12)
            $this->setfieldwidth(25);
            $this->textbox($this->harvestersubcategorysummary['HRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(30);
            $this->textbox($this->harvestersubcategorysummary['GROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->newrow(4);
            if ($this->billcategorycode == 3 or $this->billcategorycode == 4 or $this->billcategorycode == 5)
            $this->textbox($this->harvestersubcategorysummary['BUSINESSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            else
            $this->textbox($this->harvestersubcategorysummary['INCENTIVEAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->newrow(-4);
            $this->setfieldwidth(25);
            if ($this->billcategorycode == 3 or $this->billcategorycode == 5)
                $this->textbox($this->harvestersubcategorysummary['DIESELAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            else
                $this->textbox($this->harvestersubcategorysummary['DIESELRATEDIFFERENCEAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->harvestersubcategorysummary['COMMISSIONAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->harvestersubcategorysummary['NETGROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->harvestersubcategorysummary['GROSSDEDUCTION'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->harvestersubcategorysummary['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->newrow();

            

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
           -- and t.billperiodnumber=".$this->billperiodtransnumbermm." 
            and t.contractorcode=".$this->contractorcode." 
            and h.seasonyear=".$_SESSION['yearperiodcode']."
           and h.billcategorycode=".$this->billtypemm." 
           and h.billcategorycode<>5 
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
    


       // }    
    }

    function groupfooter_2(&$group_row_1)
    {    
       // if ($this->servicetrhrcategorycode==2)
       // {
            if ($this->isnewpage(20))
            {
                $this->newrow();
                $this->hline(10,405,$this->liney-2,'C'); 
                $this->newpage(True);
            }  
            $this->setfieldwidth(30,25);
            $this->textbox(' एकूण ',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
            $this->setfieldwidth(64);
            //if ($this->servicetrhrcategorycode==5 or $this->servicetrhrcategorycode==6)
            $this->textbox($this->fortrnightsummary['HRTRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->textbox($this->fortrnightsummary['HRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(25);
          //  $this->textbox($this->fortrnightsummary['HRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            //elseif ($this->servicetrhrcategorycode==2 or $this->servicetrhrcategorycode==12)
            $this->textbox($this->fortrnightsummary['TRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(30);
            $this->textbox($this->fortrnightsummary['GROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->newrow(4);
            if ($this->billcategorycode == 3 or $this->billcategorycode == 4 or $this->billcategorycode == 5)
            $this->textbox($this->fortrnightsummary['BUSINESSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            else
            $this->textbox($this->fortrnightsummary['INCENTIVEAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->newrow(-4);
            $this->setfieldwidth(25);
            if ($this->billcategorycode == 3 or $this->billcategorycode == 5)
                $this->textbox($this->fortrnightsummary['DIESELAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            else
                $this->textbox($this->fortrnightsummary['DIESELRATEDIFFERENCEAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->fortrnightsummary['COMMISSIONAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->fortrnightsummary['NETGROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->fortrnightsummary['GROSSDEDUCTION'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(25);
            $this->textbox($this->fortrnightsummary['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->newrow();

            

            $groupfooter_query_1_21 = "select tt.deductioncode
            ,dd.deductionnameuni
            ,sum(tt.deductionamount) as deductionamount
             from HTBILLDEDUCTIONDETAIL tt
             ,deduction dd
             ,htbillheader t
            ,billperiodheader h
            where tt.deductioncode=dd.deductioncode 
            and h.billcategorycode<>5
            and t.transactionnumber=tt.billtransactionnumber
            and t.billperiodtransnumber=h.billperiodtransnumber
            --and h.billperiodnumber=".$this->billperiodtransnumbermm." 
            and t.contractorcode=".$this->contractorcode." 
            and h.seasonyear=".$_SESSION['yearperiodcode']."
           and h.billcategorycode=".$this->billtypemm."
           and t.subcontractorcode=".$group_row_1['SUBCONTRACTORCODE']." 
            group by tt.deductioncode
            ,dd.deductionnameuni
            order by tt.deductioncode
            ,dd.deductionnameuni";
            $groupfooter_result_1_21 = oci_parse($this->connection, $groupfooter_query_1_21);
            $r = oci_execute($groupfooter_result_1_21);
            $i=1;
            while ($groupfooter_row_1_21 = oci_fetch_array($groupfooter_result_1_21,OCI_ASSOC+OCI_RETURN_NULLS))  
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
                    $this->textbox($groupfooter_row_1_21['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                    $this->setfieldwidth(25);
                    $this->textbox($groupfooter_row_1_21['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                    $this->setfieldwidth(10);
                    $islastodd=0;
                }
                elseif ($i%6==2) 
                {
                    $this->setfieldwidth(30);
                    $this->textbox($groupfooter_row_1_21['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                    $this->setfieldwidth(25);
                    $this->textbox($groupfooter_row_1_21['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                    $this->setfieldwidth(10);
                    $islastodd=0;
                }
                elseif ($i%6==3) 
                {
                    $this->setfieldwidth(30);
                    $this->textbox($groupfooter_row_1_21['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                    $this->setfieldwidth(25);
                    $this->textbox($groupfooter_row_1_21['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                    $this->setfieldwidth(10);
                    $islastodd=0;
                }
                elseif ($i%6==4) 
                {
                    $this->setfieldwidth(30);
                    $this->textbox($groupfooter_row_1_21['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                    $this->setfieldwidth(25);
                    $this->textbox($groupfooter_row_1_21['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                    $this->setfieldwidth(10);
                    $islastodd=0;
                }
                elseif ($i%6==5) 
                {
                    $this->setfieldwidth(30);
                    $this->textbox($groupfooter_row_1_21['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                    $this->setfieldwidth(25);
                    $this->textbox($groupfooter_row_1_21['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                    $this->setfieldwidth(10);
                    $islastodd=0;
                }
                else
                {
                    $this->setfieldwidth(30);
                    $this->textbox($groupfooter_row_1_21['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                    $this->setfieldwidth(25);
                    $this->textbox($groupfooter_row_1_21['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
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
    


        //}
    }
    function groupfooter_3(&$group_row_1)
    {   
        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }  
        $this->setfieldwidth(30,25);
        $this->textbox(' एकूण ',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(64);
        //if ($this->servicetrhrcategorycode==5 or $this->servicetrhrcategorycode==6)
        $this->textbox($this->vehiclesummary['HRTRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->textbox($this->vehiclesummary['HRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
      //  $this->textbox($this->fortrnightsummary['HRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        //elseif ($this->servicetrhrcategorycode==2 or $this->servicetrhrcategorycode==12)
        $this->textbox($this->vehiclesummary['TRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->vehiclesummary['GROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(4);
        if ($this->billcategorycode == 3 or $this->billcategorycode == 4 or $this->billcategorycode == 5)
        $this->textbox($this->vehiclesummary['BUSINESSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        else
        $this->textbox($this->vehiclesummary['INCENTIVEAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(-4);
        $this->setfieldwidth(25);
        if ($this->billcategorycode == 3 or $this->billcategorycode == 5)
            $this->textbox($this->vehiclesummary['DIESELAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        else
            $this->textbox($this->vehiclesummary['DIESELRATEDIFFERENCEAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->vehiclesummary['COMMISSIONAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->vehiclesummary['NETGROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->vehiclesummary['GROSSDEDUCTION'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->vehiclesummary['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow();

        

        $groupfooter_query_1_21 = "select tt.deductioncode
        ,dd.deductionnameuni
        ,sum(tt.deductionamount) as deductionamount
         from HTBILLDEDUCTIONDETAIL tt
         ,deduction dd
         ,htbillheader t
        ,billperiodheader h
        where tt.deductioncode=dd.deductioncode 
        and h.billcategorycode<>5
        and t.transactionnumber=tt.billtransactionnumber
        and t.billperiodtransnumber=h.billperiodtransnumber
        --and h.billperiodnumber=".$this->billperiodtransnumbermm." 
        and t.contractorcode=".$this->contractorcode." 
        and h.seasonyear=".$_SESSION['yearperiodcode']."
       and h.billcategorycode=".$this->billtypemm."
       and t.subcontractorcode=".$group_row_1['SUBCONTRACTORCODE']." 
       and t.vehiclecode=".$group_row_1['VEHICLECODE']." 
        group by tt.deductioncode
        ,dd.deductionnameuni
        order by tt.deductioncode
        ,dd.deductionnameuni";
        $groupfooter_result_1_21 = oci_parse($this->connection, $groupfooter_query_1_21);
        $r = oci_execute($groupfooter_result_1_21);
        $i=1;
        while ($groupfooter_row_1_21 = oci_fetch_array($groupfooter_result_1_21,OCI_ASSOC+OCI_RETURN_NULLS))  
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
                $this->textbox($groupfooter_row_1_21['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($groupfooter_row_1_21['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(10);
                $islastodd=0;
            }
            elseif ($i%6==2) 
            {
                $this->setfieldwidth(30);
                $this->textbox($groupfooter_row_1_21['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($groupfooter_row_1_21['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(10);
                $islastodd=0;
            }
            elseif ($i%6==3) 
            {
                $this->setfieldwidth(30);
                $this->textbox($groupfooter_row_1_21['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($groupfooter_row_1_21['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(10);
                $islastodd=0;
            }
            elseif ($i%6==4) 
            {
                $this->setfieldwidth(30);
                $this->textbox($groupfooter_row_1_21['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($groupfooter_row_1_21['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(10);
                $islastodd=0;
            }
            elseif ($i%6==5) 
            {
                $this->setfieldwidth(30);
                $this->textbox($groupfooter_row_1_21['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($groupfooter_row_1_21['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(10);
                $islastodd=0;
            }
            else
            {
                $this->setfieldwidth(30);
                $this->textbox($groupfooter_row_1_21['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($groupfooter_row_1_21['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
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
                $this->newrow();
            }
        
        $this->setfieldwidth(55,60);
        $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(64);
        //if ($this->servicetrhrcategorycode==5 or $this->servicetrhrcategorycode==6)
        if ($this->summary['HRTRTONNAGE']>0)
        $this->textbox($this->summary['HRTRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        //elseif ($this->servicetrhrcategorycode==1)
        elseif ($this->summary['TRTONNAGE']>0)
        $this->textbox($this->summary['TRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        //elseif ($this->servicetrhrcategorycode==2 or $this->servicetrhrcategorycode==12)
        elseif ($this->summary['HRTONNAGE']>0)
        $this->textbox($this->summary['HRTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->summary['GROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(4);
        if ($this->billcategorycode == 3 or $this->billcategorycode == 4 or $this->billcategorycode == 5)
        $this->textbox($this->summary['BUSINESSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        else
        $this->textbox($this->summary['INCENTIVEAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(-4);
        $this->setfieldwidth(20);
        if ($this->billcategorycode == 3 or $this->billcategorycode == 5)
            $this->textbox($this->summary['DEPOSITAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        else
            $this->textbox($this->summary['DIESELRATEDIFFERENCEAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');

        $this->setfieldwidth(25);
        $this->textbox($this->summary['COMMISSIONAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->summary['NETGROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->summary['GROSSDEDUCTION'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->summary['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(10);

        //$this->detail_2(0);

        $groupfooter_query_1_2 = "select tt.deductioncode
        ,dd.deductionnameuni
        ,sum(tt.deductionamount) as deductionamount
         from HTBILLDEDUCTIONDETAIL tt
         ,deduction dd
         ,htbillheader t
        ,billperiodheader h
        where tt.deductioncode=dd.deductioncode 
        and h.billcategorycode<>5
        and t.transactionnumber=tt.billtransactionnumber
        and t.billperiodtransnumber=h.billperiodtransnumber
       -- and h.billperiodnumber=".$this->billperiodtransnumbermm." 
            and t.contractorcode=".$this->contractorcode." 
            and h.seasonyear=".$_SESSION['yearperiodcode']."
        --   and h.billcategorycode=".$this->billtypemm." 
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
            $this->newrow(20);
            //$this->hline(10,405,$this->liney-2,'C'); 
        }

        $this->setfieldwidth(20,100);
        $this->textbox('क्लार्क',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
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