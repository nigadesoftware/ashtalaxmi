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
class farmerpaysheet extends reportbox
{
    public $circlecode;
    public $villagecode;
    public $farmercode;
    public $billperiodtransnumber;
    public $msubtitle;
    public $circlename;
    public $villagename;
    public $farmercategoryname;
    public $farmercategorycode;
    public $farmercategorysummary;
    public $circlesummary;
    public $villagesummary;
    public $summary;
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
        $this->setfieldwidth(60,185);
        if ($this->farmercategorycode==0)
        $this->textbox('ऊस पेमेंट रजिस्टर',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        elseif ($this->farmercategorycode==1)
        $this->textbox('सभासद ऊस पेमेंट रजिस्टर',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        elseif ($this->farmercategorycode==2)
        $this->textbox('बिगर सभासद ऊस पेमेंट रजिस्टर',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        elseif ($this->farmercategorycode==3)
        $this->textbox('गेटकेन ऊस पेमेंट रजिस्टर',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        $this->newrow();
        $this->setfieldwidth(100,10);
        $this->textbox($this->farmercategoryname.' गट : '.$this->circlename,$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        $this->setfieldwidth(395,10);
        $this->textbox($this->msubtitle,$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','');
        $this->newrow();
        $this->hline(10,405,$this->liney,'C');
        
        $this->setfieldwidth(20,10);
        $this->textbox('बिल नंबर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox('कोड',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(70);
        $this->textbox('ऊस उत्पादक नाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox('टनेज',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('दर',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox('रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox('कपात रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox('देय रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(5);
        $this->setfieldwidth(110);
        $this->textbox('बँक शाखा',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox('खाते नंबर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->hline(10,405,$this->liney,'C');
    }

    function group()
    {
        $this->totalgroupcount=3;
        $this->summary['NETCANETONNAGE']=0;
        $this->summary['GROSSAMOUNT']=0;
        $this->summary['GROSSDEDUCTION']=0;
        $this->summary['NETAMOUNT']=0;
        $cond='';
        if ($this->farmercode!='' and $this->farmercode!=0)
            $cond = $cond.' and farmercode='.$this->farmercode;
        if ($this->villagecode!='' and $this->villagecode!=0)
            $cond = $cond.' and villagecode='.$this->villagecode;
        if ($this->circlecode!='' and $this->circlecode!=0)
            $cond = $cond.' and r.circlecode='.$this->circlecode;
        if ($this->farmercategorycode!=0)
        {
            $cond = $cond." and f.farmercategorycode=".$this->farmercategorycode;
        }
        /* if ($cond!='')
            $cond = ' and '.$cond; */

            $group_query_1 ="select  
            c.farmercategorycode
            ,r.circlecode
            ,v.villagecode
            ,r.circlenameuni
            ,v.villagenameuni,
            t.transactionnumber,t.billnumber,
            to_char(h.paymentdate,'dd/MM/yyyy') paymentdate
            ,f.farmercode,f.farmernameuni,v.villagenameuni,t.noofslips
            ,t.netcanetonnage,t.rateperton,t.grossamount ,t.grossdeduction
            ,t.netcanetonnage,t.rateperton,t.grossamount,t.grossdeduction
            ,t.netamount,t.accountnumber,b.bankbranchnameuni,bk.banknameuni,bk.shortname 
            ,c.farmercategorynameuni
            from farmerbillheader t,farmer f, village v,circle r
            , billperiodheader h,bankbranch b,farmercategory c,bank bk
            where t.farmercode=f.farmercode
            and f.villagecode=v.villagecode
            and t.billperiodtransnumber=h.billperiodtransnumber
            and t.farmercategorycode=c.farmercategorycode
            and v.circlecode=r.circlecode
            and b.bankcode=bk.bankcode(+) 
            and t.bankbranchcode=b.bankbranchcode(+)
            and t.billperiodtransnumber=".$this->billperiodtransnumber." 
            {$cond} 
            order by 
            c.farmercategorycode
            ,r.circlecode
            ,v.villagecode
            ,t.billnumber";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
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
        $this->farmercategoryname=$group_row_1['FARMERCATEGORYNAMEUNI'];
        $this->farmercategorysummary['NETCANETONNAGE']=0;
        $this->farmercategorysummary['GROSSAMOUNT']=0;
        $this->farmercategorysummary['GROSSDEDUCTION']=0;
        $this->farmercategorysummary['NETAMOUNT']=0;
        /* if ($this->isfirstpage == '')
        {
            $this->circlename=$group_row_1['CIRCLENAMEUNI'];
            $this->newpage(True);
            $this->isfirstpage=1;
        } */
    }

    function groupheader_2(&$group_row_1)
    {
        //if ($this->isfirstpage != '')
        //{
            $this->circlename=$group_row_1['CIRCLENAMEUNI'];
            $this->newpage(True);
        //}
        $this->circlesummary['NETCANETONNAGE']=0;
        $this->circlesummary['GROSSAMOUNT']=0;
        $this->circlesummary['GROSSDEDUCTION']=0;
        $this->circlesummary['NETAMOUNT']=0;
    }

    function groupheader_3(&$group_row_1)
    {
        $this->villagename=$group_row_1['VILLAGENAMEUNI'];
        $this->setfieldwidth(40,10);
        $this->textbox($this->villagename,$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->villagesummary['NETCANETONNAGE']=0;
        $this->villagesummary['GROSSAMOUNT']=0;
        $this->villagesummary['GROSSDEDUCTION']=0;
        $this->villagesummary['NETAMOUNT']=0;
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
        $this->setfieldwidth(20,10);
        $this->textbox($group_row_1['BILLNUMBER'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['FARMERCODE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(70);
        $this->textbox($group_row_1['FARMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['NETCANETONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['RATEPERTON'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['GROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['GROSSDEDUCTION'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->pdf->SetTextColor(150, 0, 0);
        $this->textbox($group_row_1['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->pdf->SetTextColor(0, 0, 0);
        $this->setfieldwidth(5);
        $this->setfieldwidth(100);
        $this->textbox($group_row_1['BANKNAMEUNI'].', '.$group_row_1['BANKBRANCHNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(45);
        $this->textbox($group_row_1['ACCOUNTNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10,'','','','');
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
        $this->farmercategorysummary['NETCANETONNAGE']=$this->farmercategorysummary['NETCANETONNAGE']+$group_row_1['NETCANETONNAGE'];
        $this->farmercategorysummary['GROSSAMOUNT']=$this->farmercategorysummary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->farmercategorysummary['GROSSDEDUCTION']=$this->farmercategorysummary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->farmercategorysummary['NETAMOUNT']=$this->farmercategorysummary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        $this->circlesummary['NETCANETONNAGE']=$this->circlesummary['NETCANETONNAGE']+$group_row_1['NETCANETONNAGE'];
        $this->circlesummary['GROSSAMOUNT']=$this->circlesummary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->circlesummary['GROSSDEDUCTION']=$this->circlesummary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->circlesummary['NETAMOUNT']=$this->circlesummary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        $this->villagesummary['NETCANETONNAGE']=$this->villagesummary['NETCANETONNAGE']+$group_row_1['NETCANETONNAGE'];
        $this->villagesummary['GROSSAMOUNT']=$this->villagesummary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->villagesummary['GROSSDEDUCTION']=$this->villagesummary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->villagesummary['NETAMOUNT']=$this->villagesummary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        $this->summary['NETCANETONNAGE']=$this->summary['NETCANETONNAGE']+$group_row_1['NETCANETONNAGE'];
        $this->summary['GROSSAMOUNT']=$this->summary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->summary['GROSSDEDUCTION']=$this->summary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->summary['NETAMOUNT']=$this->summary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        
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
         from FARMERBILLDEDUCTIONDETAIL t
         ,deduction d
         ,bankbranch b
         ,bank k
        where t.deductioncode=d.deductioncode 
        and b.bankcode=k.bankcode(+)
        and t.branchcode=b.bankbranchcode(+) 
        and t.billtransactionnumber=".$transactionnumber." order by serialnumber";
        $detail_result_2 = oci_parse($this->connection, $detail_query_2);
        $r = oci_execute($detail_result_2);
        $islastodd=0;
        $this->pdf->SetTextColor(0, 0, 150);
        //$this->newrow(-2);
        while ($detail_row_2 = oci_fetch_array($detail_result_2,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($detail_row_2['SERIALNUMBER']%3==1) 
            {
                //$this->setfieldwidth(07,10);
                //$this->textbox($detail_row_2['SERIALNUMBER'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(97,10);
                $this->textbox($detail_row_2['DEDNAMEUNI'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
                $this->setfieldwidth(25);
                $this->textbox($detail_row_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
                $islastodd=1;
            }
            elseif ($detail_row_2['SERIALNUMBER']%3==2) 
            {
                //$this->setfieldwidth(07,141);
                //$this->textbox($detail_row_2['SERIALNUMBER'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->vline($this->liney,$this->liney+5,$this->x+$this->w,'D');
                $this->setfieldwidth(97,141);
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
                $this->setfieldwidth(97,272);
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
    function groupfooter_1(&$group_row_3)
    {     
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
        
        
        $this->setfieldwidth(55,50);
        $this->textbox($group_row_3['FARMERCATEGORYNAMEUNI'].' एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(35);
        $this->textbox($this->farmercategorysummary['NETCANETONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(10);
        $this->setfieldwidth(30);
        $this->textbox($this->farmercategorysummary['GROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->farmercategorysummary['GROSSDEDUCTION'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->farmercategorysummary['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow();

        $groupfooter_query_1_2 = "select tt.deductioncode
        ,dd.deductionnameuni
        ,sum(tt.deductionamount) as deductionamount
         from FARMERBILLDEDUCTIONDETAIL tt
         ,deduction dd
         ,bankbranch bb
         ,farmerbillheader t,farmer f, village v
        ,billperiodheader h,farmercategory c,circle r
        where tt.deductioncode=dd.deductioncode 
        and tt.branchcode=bb.bankbranchcode(+) 
        and t.transactionnumber=tt.billtransactionnumber
        and t.farmercode=f.farmercode
        and f.villagecode=v.villagecode
        and t.billperiodtransnumber=h.billperiodtransnumber
        and f.farmercategorycode=c.farmercategorycode
        and v.circlecode=r.circlecode
        and t.billperiodtransnumber=".$this->billperiodtransnumber."  
        and t.farmercategorycode=".$group_row_3['FARMERCATEGORYCODE']."
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
            else
            {
                //$this->newrow();
                //$this->hline(10,405,$this->liney-2,'C'); 
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

    }

    function groupfooter_2(&$group_row_1)
    {      
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
        
        $this->setfieldwidth(55,50);
        $this->textbox($group_row_1['CIRCLENAMEUNI'].' गट एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(35);
        $this->textbox($this->circlesummary['NETCANETONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(10);
        $this->setfieldwidth(30);
        $this->textbox($this->circlesummary['GROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->circlesummary['GROSSDEDUCTION'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->circlesummary['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow();

        $groupfooter_query_1_2 = "select tt.deductioncode
        ,dd.deductionnameuni
        ,sum(tt.deductionamount) as deductionamount
         from FARMERBILLDEDUCTIONDETAIL tt
         ,deduction dd
         ,bankbranch bb
         ,farmerbillheader t,farmer f, village v
        ,billperiodheader h,farmercategory c,circle r
        where tt.deductioncode=dd.deductioncode 
        and tt.branchcode=bb.bankbranchcode(+) 
        and t.transactionnumber=tt.billtransactionnumber
        and t.farmercode=f.farmercode
        and f.villagecode=v.villagecode
        and t.billperiodtransnumber=h.billperiodtransnumber
        and f.farmercategorycode=c.farmercategorycode
        and v.circlecode=r.circlecode
        and t.billperiodtransnumber=".$this->billperiodtransnumber." 
        and t.farmercategorycode=".$group_row_1['FARMERCATEGORYCODE']." 
        and v.circlecode=".$group_row_1['CIRCLECODE']."
        group by tt.deductioncode
        ,dd.deductionnameuni
        order by tt.deductioncode
        ,dd.deductionnameuni";
        $groupfooter_result_1_2 = oci_parse($this->connection, $groupfooter_query_1_2);
        $r = oci_execute($groupfooter_result_1_2);
        $i=1;
        while ($groupfooter_row_1_2 = oci_fetch_array($groupfooter_result_1_2,OCI_ASSOC+OCI_RETURN_NULLS))  
        {
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
    }
    function groupfooter_3(&$group_row_2)
    {      
        /* if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
        }

        $this->setfieldwidth(70,200);
        $this->textbox('गाव एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox($this->villagesummary['NETCANETONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
        $this->setfieldwidth(30);
        $this->textbox($this->villagesummary['GROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->villagesummary['GROSSDEDUCTION'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->villagesummary['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(); */

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
        if ($this->isnewpage(30))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
        }
        
        $this->setfieldwidth(55,50);
        $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(35);
        $this->textbox($this->summary['NETCANETONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
        $this->setfieldwidth(30);
        $this->textbox($this->summary['GROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->summary['GROSSDEDUCTION'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->summary['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow();

        $groupfooter_query_1_2 = "select tt.deductioncode
        ,dd.deductionnameuni
        ,sum(tt.deductionamount) as deductionamount
         from FARMERBILLDEDUCTIONDETAIL tt
         ,deduction dd
         ,bankbranch bb
         ,farmerbillheader t,farmer f, village v
        ,billperiodheader h,farmercategory c,circle r
        where tt.deductioncode=dd.deductioncode 
        and tt.branchcode=bb.bankbranchcode(+) 
        and t.transactionnumber=tt.billtransactionnumber
        and t.farmercode=f.farmercode
        and f.villagecode=v.villagecode
        and t.billperiodtransnumber=h.billperiodtransnumber
        and f.farmercategorycode=c.farmercategorycode
        and v.circlecode=r.circlecode
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

        $this->newrow(15);
        $this->setfieldwidth(20,60);
        $this->textbox('क्लार्क',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(100);
        $this->textbox('केन अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(100);
        $this->textbox('चीफ अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
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