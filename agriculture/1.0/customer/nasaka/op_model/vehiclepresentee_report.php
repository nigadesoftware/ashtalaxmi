<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class vehiclepresentee extends reportbox
{
    Public $fromdate;
    Public $todate;
    Public $contractorcategorycode;
    Public $contractcategorycode;
    Public $servicetrhrcategorycode;
    Public $serialnumber;
    Public $contractsummary;
    Public $servicetrhrsummary;
    Public $contractorsummary;

    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7,$subject='',$pdffilename='',$papersize='A3',$orientation='L')
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
        $this->group();
        $this->reportfooter();
    }

    function pageheader()
    {
        ob_flush();
        ob_start();
       
        $this->liney = 15;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->textbox('तोडणी वहातूक टनेज',380,10,'S','C',1,'siddhanta',12);
        $this->newrow(7);
        if ($this->fromdate!='' and $this->fromdate !='')
        {
            $fromdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $todt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $this->textbox('हंगाम : '.$_SESSION['yearperiodcode'].' दिनांक '.$this->fromdate.' पासून दिनांक '.$this->todate.' पर्यंत',380,10,'S','C',1,'siddhanta',11);
        }
        else
        {
            $this->textbox('हंगाम : '.$_SESSION['yearperiodcode'],380,10,'S','C',1,'siddhanta',11);
        }
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->newrow(7);
        $this->setfieldwidth(10,10);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('अ.न.',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->setfieldwidth(60);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('सब कंत्राटदार',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('वाहन',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(15);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'C');
        $this->textbox('वाहन प्रकार',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(9);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('01',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('02',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('03',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('04',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('05',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('06',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('07',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('08',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('09',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('10',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('11',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('12',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('13',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('14',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('15',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('16',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('17',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('18',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('19',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('20',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('21',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('22',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('23',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('24',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('25',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('26',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('27',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('28',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('29',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('30',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('31',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
        $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox('Tot',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'C');
        $this->hline(10,414,$this->liney,'C');
        $this->newrow();
        $this->hline(10,414,$this->liney,'C');
    }

    function group()
    {
        $this->totalgroupcount=3;
        $this->summary['NETCANETONNAGE']=0;
        $this->summary['GROSSAMOUNT']=0;
        $cond='1=1';
        if ($this->fromdate != '')
            $fdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
        if ($this->todate != '')
            $tdt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
        /* if ($fdt !='' and $tdt !='')
            $cond = $cond." and t.weighmentdate>= '{$fdt}' and t.weighmentdate<= '{$tdt}'"; */
        if ($this->contractorcategorycode!='' and $this->contractorcategorycode!=0)
            $cond = $cond.' and t.contractorcategorycode='.$this->contractorcategorycode;
        if ($this->contractcategorycode!='' and $this->contractcategorycode!=0)
            $cond = $cond.' and t.contractcategorycode='.$this->contractcategorycode;
        if ($this->servicetrhrcategorycode!='' and $this->servicetrhrcategorycode!=0)
            $cond = $cond.' and t.servicetrhrcategorycode='.$this->servicetrhrcategorycode;
        

            $group_query_1 ="            with presentee as
            (select dayofperiod,c.seasoncode,t.CONTRACTORCATEGORYCODE,cc.contractorcategorynameuni,t.SERVICETRHRCATEGORYCODE,st.servicetrhrcatnameuni
            ,t.CONTRACTCATEGORYCODE,ct.contractcategorynameuni
            ,sc.subcontractorcode,sc.subcontractornameuni,c.vehiclecode,c.vehiclenumber,vc.vehiclecategorynameuni
            from  (select day as dayofperiod
            from (select to_date('".$fdt."', 'dd-mon-yyyy') - 1 + level day
            from dual
            connect by level <= to_date('".$tdt."', 'dd-mon-yyyy') -
                   to_date('".$fdt."', 'dd-mon-yyyy') + 1))tt,contractorcontract t,subcontractor sc,vehicle c
                   ,vehiclecategory vc,contractorcategory cc,contractcategory ct,servicetrhrcategory st
            where t.seasoncode=sc.seasoncode and t.contractcode=sc.contractcode
            and sc.seasoncode=c.seasoncode and sc.subcontractorcode=c.subcontractorcode and c.vehiclecategorycode=vc.vehiclecategorycode
            and t.CONTRACTORCATEGORYCODE=cc.contractorcategorycode and t.SERVICETRHRCATEGORYCODE=st.servicetrhrcategorycode 
            and t.CONTRACTCATEGORYCODE=ct.contractcategorycode and {$cond}
            union all
            select dayofperiod,c.seasoncode,t.CONTRACTORCATEGORYCODE,cc.contractorcategorynameuni,t.SERVICETRHRCATEGORYCODE,st.servicetrhrcatnameuni
            ,t.CONTRACTCATEGORYCODE,ct.contractcategorynameuni
            ,sc.subcontractorcode,sc.subcontractornameuni,c.tyregadicode,c.tyregadinumber,'बैलगाडी' vehiclecategorynameuni
            from  (select day as dayofperiod
            from (select to_date('".$fdt."', 'dd-mon-yyyy') - 1 + level day
            from dual
            connect by level <= to_date('".$tdt."', 'dd-mon-yyyy') -
                   to_date('".$fdt."', 'dd-mon-yyyy') + 1))tt,contractorcontract t,subcontractor sc,tyregadi c
                   --,vehiclecategory vc
                   ,contractorcategory cc,contractcategory ct,servicetrhrcategory st
            where t.seasoncode=sc.seasoncode and t.contractcode=sc.contractcode
            and sc.seasoncode=c.seasoncode and sc.subcontractorcode=c.subcontractorcode 
            --and c.vehiclecategorycode=vc.vehiclecategorycode
            and t.CONTRACTORCATEGORYCODE=cc.contractorcategorycode and t.SERVICETRHRCATEGORYCODE=st.servicetrhrcategorycode 
            and t.CONTRACTCATEGORYCODE=ct.contractcategorycode and {$cond}
            ) 
            select * from (
            select p.CONTRACTORCATEGORYCODE,p.SERVICETRHRCATEGORYCODE,p.CONTRACTCATEGORYCODE
            ,p.contractorcategorynameuni,p.servicetrhrcatnameuni,p.contractcategorynameuni
            ,p.seasoncode,p.subcontractorcode,p.subcontractornameuni,p.vehiclecode,p.vehiclenumber,vehiclecategorynameuni,EXTRACT( DAY FROM p.dayofperiod ) As  dy,case when t.transportationtonnage is null then 0 else 1 end preentee
            from presentee p,ht_tonnage t
            where p.seasoncode=t.SEASONCODE(+) and p.vehiclecode=t.VEHICLECODE(+) and dayofperiod=t.weighmentdate(+)
            and p.SEASONCODE=".$_SESSION['yearperiodcode']."
            union all
            select p.CONTRACTORCATEGORYCODE,p.SERVICETRHRCATEGORYCODE,p.CONTRACTCATEGORYCODE
            ,p.contractorcategorynameuni,p.servicetrhrcatnameuni,p.contractcategorynameuni
            ,p.seasoncode,p.subcontractorcode,p.subcontractornameuni,p.vehiclecode,p.vehiclenumber,vehiclecategorynameuni,EXTRACT( DAY FROM p.dayofperiod ) As  dy,case when t.transportationtonnage is null then 0 else 1 end preentee
            from presentee p,ht_tonnage t
            where p.seasoncode=t.SEASONCODE(+) 
            and (p.vehiclecode=t.tyregadiCODE(+))
            and dayofperiod=t.weighmentdate(+)
            and p.SEASONCODE=".$_SESSION['yearperiodcode']."
            )
            PIVOT
            (
              max(preentee)
              FOR dy IN (
                1 AS D1,
                2 As D2,
                3 As D3,
                4 AS D4,
                5 AS D5,
                6 AS D6,
                7 AS D7,
                8 As D8,
                9 As D9,
                10 AS D10,
                11 AS D11,
                12 AS D12,
                13 AS D13,
                14 As D14,
                15 AS D15,
                16 As D16,
                17 As D17,
                18 AS D18,
                19 AS D19,
                20 AS D20,
                21 AS D21,
                22 As D22,
                23 As D23,
                24 AS D24,
                25 AS D25,
                26 AS D26,
                27 AS D27,
                28 As D28,
                29 As D29,
                30 AS D30,
                31 AS D31
              )
            )";
        

        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $this->serialnumber = 0;
        $this->newpage(True);
        $nname=0;
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($this->code==1)
            {
                $nname=50;
            }
            elseif ($this->code==2)
            {
                $nname=10;
            }
            elseif ($this->code==3)
            {
                $nname=0;
            }
            ///====================

            $total = 0;
            for ($i = 1; $i <= 31; $i++)
            {
                $key = 'D' . $i; // Dynamically generate the key, e.g., 'D1', 'D2', ..., 'D31'
                if (isset($group_row_1[$key])) 
                { // Check if the key exists to avoid undefined index errors
                    $total += $group_row_1[$key];
                }
            }
            ///====================
            if($total==$nname)
            {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->hline(10,414,$this->liney,'D'); 
            $last_row=$group_row_1;
            }
            elseif($total<$nname)
            {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->hline(10,414,$this->liney,'D'); 
            $last_row=$group_row_1;
            }
            elseif($total==0)
            {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->hline(10,414,$this->liney,'D'); 
            $last_row=$group_row_1;
            }
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
    }
    
    function groupheader_1(&$group_row_1)
    {
        if ($this->isnewpage(20))
        {
            $this->newrow();
            //$this->hline(10,200,$this->liney,'D');  
            $this->newpage(True);
        }
        $this->contractortrhrsummary['TRANSPORTATIONTONNAGE'] = 0;
        $this->contractortrhrsummary['HARVESTINGTONNAGE'] = 0;

    }

    function groupheader_2(&$group_row_1)
    {
        if ($this->isnewpage(20))
        {
            $this->newrow();
            //$this->hline(10,200,$this->liney,'D');  
            $this->newpage(True);
        }
        $this->servicetrhrsummary['TRANSPORTATIONTONNAGE'] = 0;
        $this->servicetrhrsummary['HARVESTINGTONNAGE'] = 0;

    }

    function groupheader_3(&$group_row_1)
    {
        if ($this->isnewpage(20))
        {
            $this->newrow();
            //$this->hline(10,200,$this->liney,'D');  
            $this->newpage(True);
        }
        $this->contracttrhrsummary['TRANSPORTATIONTONNAGE'] = 0;
        $this->contracttrhrsummary['HARVESTINGTONNAGE'] = 0;
        $this->setfieldwidth(10,10);
        $this->vline($this->liney,$this->liney+7,$this->x,'C');
        $this->setfieldwidth(105);
        $this->textbox($group_row_1['CONTRACTORCATEGORYNAMEUNI'].' - '.$group_row_1['SERVICETRHRCATNAMEUNI'].' - '.$group_row_1['CONTRACTCATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'C');
        $this->setfieldwidth(9);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('02',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('03',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('04',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('05',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('06',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('07',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('08',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('09',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('10',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('11',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('12',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('13',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('14',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('15',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('16',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('17',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('18',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('19',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('20',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('21',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('22',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('23',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('24',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('25',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('26',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('27',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('28',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('29',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        //$this->textbox('30',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'C');
        //$this->textbox('31',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
        $this->newrow();
        $this->hline(10,414,$this->liney,'C');
        
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
            //$this->hline(10,200,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney,'C'); 
        }
        //$this->hline(10,200,$this->liney,'D'); 
        $this->serialnumber=$this->serialnumber+1;
        $this->setfieldwidth(10,10);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox( $this->serialnumber,$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->setfieldwidth(60);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($group_row_1['SUBCONTRACTORNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(30);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($group_row_1['VEHICLENUMBER'],$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->setfieldwidth(15);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'C');
        if ($group_row_1['VEHICLENUMBER']!='')
        $this->textbox($group_row_1['VEHICLECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        else
        $this->textbox('',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D1'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D2'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D3'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D4'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D5'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D6'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D7'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D8'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D9'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D10'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D11'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D12'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D13'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D14'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->decode($this->numformat($group_row_1['D15'],0,true),0,"-",1,"P"),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D16'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D17'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D18'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D19'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D20'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D21'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D22'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D23'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D24'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D25'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D26'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D27'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D28'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D29'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D30'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
         $this->setfieldwidth(9);        
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'D');
        $this->textbox($this->decode($this->numformat($group_row_1['D31'],0,true),0,"-",1,"P"),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9,'','','','B');
        
        $total = 0;
        for ($i = 1; $i <= 31; $i++) {
            $key = 'D' . $i; // Dynamically generate the key, e.g., 'D1', 'D2', ..., 'D31'
            if (isset($group_row_1[$key])) 
            { // Check if the key exists to avoid undefined index errors
                $total += $group_row_1[$key];
            }
            
        }
        
        $this->setfieldwidth(9);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w,'C');
        $this->textbox($this->numformat($total,0,true),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',9,'','','','B');
       

        
        if ($this->isnewpage(20))
        {
            $this->newrow();
            //$this->hline(10,200,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            //$this->hline(10,195,$this->liney,'C'); 
        }
        
    }
    function decode($data,$data1,$data2,$data3,$data4)
    {
        if ($data==$data1)
            return $data2;
        elseif ($data==$data3)
            return $data4;
  
    }
    function groupfooter_1(&$group_row_1)
    {     
        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            //$this->hline(10,200,$this->liney,'C'); 
        }
        /* $this->setfieldwidth(15,10);
        $this->setfieldwidth(110);
        $this->textbox($group_row_1['CONTRACTORCATEGORYNAMEUNI'].' एकूण',$this->w,$this->x,'S','R',1,'siddhanta',9,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox($this->contractortrhrsummary['TRANSPORTATIONTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->contractortrhrsummary['HARVESTINGTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(6);
        $this->hline(10,200,$this->liney,'C');
        $this->newrow(3);
        $this->newpage(True); */

    }

    function groupfooter_2(&$group_row_1)
    {      
        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            //$this->hline(10,200,$this->liney,'C'); 
        }
        /* $this->setfieldwidth(15,10);
        $this->setfieldwidth(110);
        $this->textbox($group_row_1['CONTRACTORCATEGORYNAMEUNI'].' - '.$group_row_1['SERVICETRHRCATNAMEUNI'].' एकूण',$this->w,$this->x,'S','R',1,'siddhanta',9,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox($this->servicetrhrsummary['TRANSPORTATIONTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->servicetrhrsummary['HARVESTINGTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(6);
        $this->hline(10,200,$this->liney,'C');
        $this->newrow(3); */

        
    }
    function groupfooter_3(&$group_row_1)
    {      
        if ($this->isnewpage(20))
        {
            $this->newrow();
            //$this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            //$this->hline(10,200,$this->liney,'C'); 
        }
        /* $this->setfieldwidth(15,10);
        $this->setfieldwidth(110);
        $this->textbox($group_row_1['CONTRACTORCATEGORYNAMEUNI'].' - '.$group_row_1['SERVICETRHRCATNAMEUNI'].' - '.$group_row_1['CONTRACTCATEGORYNAMEUNI'].' एकूण',$this->w,$this->x,'S','R',1,'siddhanta',9,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox($this->contracttrhrsummary['TRANSPORTATIONTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->contracttrhrsummary['HARVESTINGTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->hline(10,200,$this->liney,'C');
        $this->newrow(6);
        $this->hline(10,200,$this->liney,'C');
        $this->newrow(3); */
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
            //$this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }   
        /* else
        {
            $this->newrow();
            $this->hline(10,200,$this->liney,'C'); 
        } */
        
        $this->newrow(15);
        $this->setfieldwidth(20,60);
        $this->textbox('क्लार्क',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('केन अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
    
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