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
class httonnagechecklist extends reportbox
{
    public $billperiodtransnumber;
    public $servicetrhrcategorycode;
    public $fromdate;
    public $todate;
    public $contractorcode;
    public $subcontractorsummary;
    public $summary;

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
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->newrow(4);
        $this->setfieldwidth(60,70);
        $this->textbox('तोडणी वहातूक टनेज चेकलिस्ट',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        $this->newrow();
        $this->setfieldwidth(100,60);
        $this->textbox('दिनांक '.$this->fromdate.' पासून दिनांक '.$this->todate.' पर्यंत',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->newrow();
        
        $this->hline(10,405,$this->liney,'C');
        
        $this->setfieldwidth(25,10);
        $this->textbox('‌दिनांक',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(50);
        $this->textbox('ऊस उत्पादक नाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('गाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('उपगाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('गट',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(15);
        $this->textbox('अंतर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(15);
        $this->textbox('खेपा',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('वजन',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->hline(10,405,$this->liney,'C');
    }

    function group()
    {
        $this->totalgroupcount=1;
        $this->summary['NETWEIGHT']=0;
        $cond=' and t.seasoncode='.$_SESSION['yearperiodcode'];
        if ($this->fromdate!='' and $this->todate!='')
        {
            $frdate=DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $todate=DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $cond= $cond." and w.weighmentdate>='".$frdate."'
            and w.weighmentdate<='".$todate."'";
        }
        $cond= $cond." and t.servicetrhrcategorycode=".$this->servicetrhrcategorycode;
        if ($this->contractorcode!='')
        {
            $cond=$cond." and r.contractorcode=".$this->contractorcode;
        }
        if ($this->servicetrhrcategorycode==2)
        {
            $group_query_1 ="select * from (select subcontractorcode,subcontractornameuni
            ,contractorcode,contractornameuni
            ,weighmentdate,weightdate,farmercode,farmernameuni
            ,villagecode,villagenameuni
            ,subvillagecode,subvillagenameuni
            ,circlecode,circlenameuni
            ,distance
            ,sum(netweight) as netweight,count(*) as cnt
            from(select f.hrsubcontractorcode subcontractorcode,s.subcontractornameuni
            ,r.contractorcode,r.contractornameuni
            ,to_char(w.weighmentdate,'dd/MM/YYYY') as weighmentdate,w.weighmentdate weightdate,f.farmercode,m.farmernameuni
            ,f.villagecode,v.villagenameuni
            ,g.subvillagecode,g.subvillagenameuni
            ,c.circlecode,c.circlenameuni
            ,case when nvl(f.viadistance,0)>0 then f.viadistance
                               when g.subvillagecode is not null then g.distancetrucktractor
                               else v.distancetrucktractor end distance
            ,netweight
            from weightslip w,fieldslip f,farmer m,village v
            ,circle c,subcontractor s,contractorcontract t,contractor r
            ,subvillage g,todslip p
            where w.seasoncode=f.seasoncode
            and w.fieldslipnumber=f.fieldslipnumber
            and f.villagecode=v.villagecode
            and v.circlecode=c.circlecode
            and f.hrsubcontractorcode=s.subcontractorcode
            and s.seasoncode=t.seasoncode
            and s.contractcode=t.contractcode
            and t.contractorcode=r.contractorcode
            and f.farmercode=m.farmercode
            and f.seasoncode=p.seasoncode
            and f.todslipnumber=p.todslipnumber
            and p.villagecode=g.villagecode(+)
            and p.subvillagecode=g.subvillagecode(+) 
            {$cond})
            group by subcontractorcode,subcontractornameuni
            ,contractorcode,contractornameuni
            ,weighmentdate,weightdate
            ,farmercode,farmernameuni
            ,villagecode,villagenameuni
            ,subvillagecode,subvillagenameuni
            ,circlecode,circlenameuni
            ,distance)
            order by contractornameuni,subcontractornameuni,subcontractorcode,weightdate";
        }
        elseif ($this->servicetrhrcategorycode==5)
        {
            $group_query_1 ="select * from (select subcontractorcode,subcontractornameuni
            ,contractorcode,contractornameuni
            ,weighmentdate,weightdate,farmercode,farmernameuni
            ,villagecode,villagenameuni
            ,subvillagecode,subvillagenameuni
            ,circlecode,circlenameuni
            ,distance
            ,sum(netweight) as netweight,count(*) as cnt
            from(select f.hrtrsubcontractorcode subcontractorcode,s.subcontractornameuni
            ,r.contractorcode,r.contractornameuni
            ,to_char(w.weighmentdate,'dd/MM/YYYY') as weighmentdate,w.weighmentdate weightdate,f.farmercode,m.farmernameuni
            ,f.villagecode,v.villagenameuni
            ,g.subvillagecode,g.subvillagenameuni
            ,c.circlecode,c.circlenameuni
            ,case when nvl(f.viadistance,0)>0 then f.viadistance
                               when g.subvillagecode is not null then g.distancetyregadi
                               else v.distancetyregadi end distance
            ,netweight
            from weightslip w,fieldslip f,farmer m,village v
            ,circle c,subcontractor s,contractorcontract t,contractor r
            ,subvillage g,todslip p
            where w.seasoncode=f.seasoncode
            and w.fieldslipnumber=f.fieldslipnumber
            and f.villagecode=v.villagecode
            and v.circlecode=c.circlecode
            and f.hrtrsubcontractorcode=s.subcontractorcode
            and s.seasoncode=t.seasoncode
            and s.contractcode=t.contractcode
            and t.contractorcode=r.contractorcode
            and f.farmercode=m.farmercode
            and f.seasoncode=p.seasoncode
            and f.todslipnumber=p.todslipnumber
            and p.villagecode=g.villagecode(+)
            and p.subvillagecode=g.subvillagecode(+)
             {$cond})
            group by subcontractorcode,subcontractornameuni
            ,contractorcode,contractornameuni
            ,weighmentdate,weightdate
            ,farmercode,farmernameuni
            ,villagecode,villagenameuni
            ,subvillagecode,subvillagenameuni
            ,circlecode,circlenameuni,distance)
            order by contractornameuni,subcontractornameuni,subcontractorcode,weightdate";
        }
        elseif ($this->servicetrhrcategorycode==1)
        {
            $group_query_1 ="select * from(select vehiclecode,vehiclenumber,vehiclecategorynameuni,subcontractorcode,subcontractornameuni
            ,contractorcode,contractornameuni
            ,weighmentdate,weightdate
            ,farmercode,farmernameuni
            ,villagecode,villagenameuni
            ,subvillagecode,subvillagenameuni
            ,circlecode,circlenameuni
            ,distance
            ,sum(netweight) netweight,count(*) cnt 
            from (select h.vehiclecode,h.vehiclenumber,y.vehiclecategorynameuni,s.subcontractorcode,s.subcontractornameuni
            ,r.contractorcode,r.contractornameuni
            ,to_char(w.weighmentdate,'dd/MM/YYYY') as weighmentdate,weighmentdate weightdate
            ,f.farmercode,m.farmernameuni
            ,f.villagecode,v.villagenameuni
            ,g.subvillagecode,g.subvillagenameuni
            ,c.circlecode,c.circlenameuni
            ,case when nvl(f.viadistance,0)>0 then f.viadistance
                               when g.subvillagecode is not null and h.vehiclecategorycode=2 then g.distancetrucktractor+nvl(to_number(v.extradistancetractor),0)
                               when g.subvillagecode is null and h.vehiclecategorycode=2 then v.distancetrucktractor+nvl(to_number(v.extradistancetractor),0)
                               when g.subvillagecode is not null and h.vehiclecategorycode=1 then g.distancetrucktractor
                               else v.distancetrucktractor end distance
            ,netweight
            from weightslip w,fieldslip f,farmer m,village v
            ,circle c,subcontractor s,contractorcontract t,contractor r,vehicle h,vehiclecategory y,subvillage g,todslip p
            where w.seasoncode=f.seasoncode
            and w.fieldslipnumber=f.fieldslipnumber
            and f.villagecode=v.villagecode
            and v.circlecode=c.circlecode
            and f.trsubcontractorcode=s.subcontractorcode
            and f.vehiclecode=h.vehiclecode
            and s.seasoncode=t.seasoncode
            and s.contractcode=t.contractcode
            and t.contractorcode=r.contractorcode
            and s.seasoncode=h.seasoncode
            and s.seasoncode=h.seasoncode
            and f.seasoncode=h.seasoncode
            and f.vehiclecode=h.vehiclecode
            and h.vehiclecategorycode=y.vehiclecategorycode
            and f.farmercode=m.farmercode
            and f.seasoncode=p.seasoncode
            and f.todslipnumber=p.todslipnumber
            and p.villagecode=g.villagecode(+)
            and p.subvillagecode=g.subvillagecode(+)
            {$cond}
            )
            group by vehiclecode,vehiclenumber,vehiclecategorynameuni,subcontractorcode,subcontractornameuni
            ,contractorcode,contractornameuni
            ,weighmentdate,weightdate
            ,farmercode,farmernameuni
            ,villagecode,villagenameuni
            ,subvillagecode,subvillagenameuni
            ,circlecode,circlenameuni
            ,distance)
            order by contractornameuni,subcontractornameuni,subcontractorcode,vehiclecategorynameuni,vehiclecode,weightdate";
        }
        elseif ($this->servicetrhrcategorycode==6)
        {
            $group_query_1 ="select * from(select vehiclecode,vehiclenumber,vehiclecategorynameuni,subcontractorcode,subcontractornameuni
            ,contractorcode,contractornameuni
            ,weighmentdate,weightdate
            ,farmercode,farmernameuni
            ,villagecode,villagenameuni
            ,subvillagecode,subvillagenameuni
            ,circlecode,circlenameuni
            ,distance
            ,sum(netweight) netweight,count(*) cnt 
            from (select h.vehiclecode,h.vehiclenumber,y.vehiclecategorynameuni,s.subcontractorcode,s.subcontractornameuni
            ,r.contractorcode,r.contractornameuni
            ,to_char(w.weighmentdate,'dd/MM/YYYY') as weighmentdate,weighmentdate weightdate
            ,f.farmercode,m.farmernameuni
            ,f.villagecode,v.villagenameuni
            ,g.subvillagecode,g.subvillagenameuni
            ,c.circlecode,c.circlenameuni
            ,case when nvl(f.viadistance,0)>0 then f.viadistance
                               when g.subvillagecode is not null then g.distancetyregadi
                               else v.distancetyregadi end distance
            ,netweight
            from weightslip w,fieldslip f,farmer m,village v
            ,circle c,subcontractor s,contractorcontract t,contractor r,vehicle h,vehiclecategory y,subvillage g,todslip p
            where w.seasoncode=f.seasoncode
            and w.fieldslipnumber=f.fieldslipnumber
            and f.villagecode=v.villagecode
            and v.circlecode=c.circlecode
            and f.hrtrsubcontractorcode=s.subcontractorcode
            and f.vehiclecode=h.vehiclecode
            and s.seasoncode=t.seasoncode
            and s.contractcode=t.contractcode
            and t.contractorcode=r.contractorcode
            and s.seasoncode=h.seasoncode
            and s.seasoncode=h.seasoncode
            and f.seasoncode=h.seasoncode
            and f.vehiclecode=h.vehiclecode
            and h.vehiclecategorycode=y.vehiclecategorycode
            and f.farmercode=m.farmercode
            and f.seasoncode=p.seasoncode
            and f.todslipnumber=p.todslipnumber
            and p.villagecode=g.villagecode(+)
            and p.subvillagecode=g.subvillagecode(+)
            {$cond}
            )
            group by vehiclecode,vehiclenumber,vehiclecategorynameuni,subcontractorcode,subcontractornameuni
            ,contractorcode,contractornameuni
            ,weighmentdate,weightdate
            ,farmercode,farmernameuni
            ,villagecode,villagenameuni
            ,subvillagecode,subvillagenameuni
            ,circlecode,circlenameuni
            ,distance)
            order by contractornameuni,subcontractornameuni,subcontractorcode,vehiclecategorynameuni,vehiclecode,weightdate";

        }
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            //$this->hline(10,405,$this->liney,'D'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
    }
    
    function groupheader_1(&$group_row_1)
    {
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
        $this->setfieldwidth(180,10);
        if ($this->servicetrhrcategorycode==1)
        $this->textbox($group_row_1['CONTRACTORCODE'].' -'.$group_row_1['CONTRACTORNAMEUNI'].' '.$group_row_1['TRSUBCONTRACTORCODE'].' -'.$group_row_1['SUBCONTRACTORNAMEUNI'].' '.$group_row_1['VEHICLECODE'].' '.$group_row_1['VEHICLENUMBER'].' '.$group_row_1['VEHICLECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        elseif ($this->servicetrhrcategorycode==5)
        $this->textbox($group_row_1['CONTRACTORCODE'].' -'.$group_row_1['CONTRACTORNAMEUNI'].' '.$group_row_1['SUBCONTRACTORCODE'].' '.$group_row_1['SUBCONTRACTORNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        elseif ($this->servicetrhrcategorycode==2)
        $this->textbox($group_row_1['CONTRACTORCODE'].' -'.$group_row_1['CONTRACTORNAMEUNI'].' '.$group_row_1['HRSUBCONTRACTORCODE'].' '.$group_row_1['SUBCONTRACTORNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        elseif ($this->servicetrhrcategorycode==6)
        $this->textbox($group_row_1['CONTRACTORCODE'].' -'.$group_row_1['CONTRACTORNAMEUNI'].' '.$group_row_1['HRTRSUBCONTRACTORCODE'].' -'.$group_row_1['SUBCONTRACTORNAMEUNI'].' '.$group_row_1['VEHICLECODE'].' '.$group_row_1['VEHICLENUMBER'].' '.$group_row_1['VEHICLECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->subcontractorsummary['NETWEIGHT']=0;
    }

    function groupheader_2(&$group_row_1)
    {
    }

    function groupheader_3(&$group_row_1)
    {
        //$this->newrow();
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
        $this->setfieldwidth(25,10);
        $this->textbox($group_row_1['WEIGHMENTDATE'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(50);
        $this->textbox($group_row_1['FARMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(25);
        if ($group_row_1['DISTANCE']=='')
        {
            $this->pdf->SetTextColor(150, 0, 0);
            $this->textbox($group_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
            $this->pdf->SetTextColor(0, 0, 0);
        }
        else
        {
            $this->textbox($group_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        }
        $this->setfieldwidth(25);
        if ($group_row_1['DISTANCE']=='')
        {
            $this->pdf->SetTextColor(150, 0, 0);
            $this->textbox($group_row_1['SUBVILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
            $this->pdf->SetTextColor(0, 0, 0);
        }
        else
        {
            $this->textbox($group_row_1['SUBVILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        }
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['CIRCLENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['DISTANCE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(10);
        $this->textbox($group_row_1['CNT'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['NETWEIGHT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            $this->newrow(5);
            $this->hline(10,200,$this->liney,'D'); 
        }

        $this->subcontractorsummary['NETWEIGHT']=$this->subcontractorsummary['NETWEIGHT']+$group_row_1['NETWEIGHT'];
        $this->summary['NETWEIGHT']=$this->summary['NETWEIGHT']+$group_row_1['NETWEIGHT'];
        
    }


    function detail_2($transactionnumber)
    { 
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
        $this->setfieldwidth(25,140);
        $this->textbox('एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(35);
        $this->textbox($this->subcontractorsummary['NETWEIGHT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
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
        if ($this->isnewpage(20))
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
        
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
       
        $this->setfieldwidth(25,140);
        $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(35);
        $this->textbox($this->summary['NETWEIGHT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow();
        $frdate=DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
        $todate=DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
        if ($this->servicetrhrcategorycode==5)
        {
            $group_query_1 ="select * from (select f.villagecode,v.villagenameuni,c.circlecode,c.circlenameuni
            from weightslip w,fieldslip f,farmer m,village v
            ,circle c,subcontractor s,contractorcontract t,contractor r
            where w.seasoncode=f.seasoncode
            and w.fieldslipnumber=f.fieldslipnumber
            and f.villagecode=v.villagecode
            and v.circlecode=c.circlecode
            and f.hrtrsubcontractorcode=s.subcontractorcode
            and s.seasoncode=t.seasoncode
            and s.contractcode=t.contractcode
            and t.contractorcode=r.contractorcode
            and f.farmercode=m.farmercode
            and w.weighmentdate>='".$frdate."'
            and w.weighmentdate<='".$todate."'
            and t.servicetrhrcategorycode=".$this->servicetrhrcategorycode."
            and nvl(distancetyregadi,0)=0
            group by f.villagecode,v.villagenameuni,c.circlecode,c.circlenameuni)
            order by villagecode,villagenameuni,circlecode,circlenameuni";
        }
        elseif ($this->servicetrhrcategorycode==1)
        {
            $group_query_1 ="select * from (select f.villagecode,v.villagenameuni,c.circlecode,c.circlenameuni
            from weightslip w,fieldslip f,farmer m,village v
            ,circle c,subcontractor s,contractorcontract t,contractor r,vehicle h,vehiclecategory y
            where w.seasoncode=f.seasoncode
            and w.fieldslipnumber=f.fieldslipnumber
            and f.villagecode=v.villagecode
            and v.circlecode=c.circlecode
            and f.trsubcontractorcode=s.subcontractorcode
            and f.vehiclecode=h.vehiclecode
            and s.seasoncode=t.seasoncode
            and s.contractcode=t.contractcode
            and t.contractorcode=r.contractorcode
            and s.seasoncode=h.seasoncode
            and s.seasoncode=h.seasoncode
            and f.seasoncode=h.seasoncode
            and f.vehiclecode=h.vehiclecode
            and h.vehiclecategorycode=y.vehiclecategorycode
            and f.farmercode=m.farmercode
            and w.weighmentdate>='".$frdate."'
            and w.weighmentdate<='".$todate."'
            and t.servicetrhrcategorycode=".$this->servicetrhrcategorycode."
            and nvl(distancetrucktractor,0)=0
            group by f.villagecode,v.villagenameuni,c.circlecode,c.circlenameuni)
            order by villagecode,villagenameuni,circlecode,circlenameuni";
        }
        elseif ($this->servicetrhrcategorycode==6)
        {
            $group_query_1 ="select * from (select f.villagecode,v.villagenameuni,c.circlecode,c.circlenameuni
            from weightslip w,fieldslip f,farmer m,village v
            ,circle c,subcontractor s,contractorcontract t,contractor r,vehicle h,vehiclecategory y
            where w.seasoncode=f.seasoncode
            and w.fieldslipnumber=f.fieldslipnumber
            and f.villagecode=v.villagecode
            and v.circlecode=c.circlecode
            and f.hrtrsubcontractorcode=s.subcontractorcode
            and f.vehiclecode=h.vehiclecode
            and s.seasoncode=t.seasoncode
            and s.contractcode=t.contractcode
            and t.contractorcode=r.contractorcode
            and s.seasoncode=h.seasoncode
            and s.seasoncode=h.seasoncode
            and f.seasoncode=h.seasoncode
            and f.vehiclecode=h.vehiclecode
            and h.vehiclecategorycode=y.vehiclecategorycode
            and f.farmercode=m.farmercode
            and w.weighmentdate>='".$frdate."'
            and w.weighmentdate<='".$todate."'
            and nvl(distancetyregadi,0)=0
            and t.servicetrhrcategorycode=".$this->servicetrhrcategorycode."
            group by f.villagecode,v.villagenameuni,c.circlecode,c.circlenameuni)
            order by circlecode,circlenameuni,villagecode,villagenameuni";
        }
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        $this->pdf->SetTextColor(150, 0, 0);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($this->isnewpage(10))
            {
                //$this->newrow();
                //$this->hline(10,405,$this->liney-2,'C'); 
                $this->newpage(True,True);
            } 
            if ($i==0)
            {
                $this->setfieldwidth(40,10);
                $this->textbox('अंतर नसलेली गाव यादी',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');    
                $this->newrow();
                $i++;
            }
            if ($this->isnewpage(10))
            {
                $this->newrow();
                $this->newpage(True,True);
                $this->pdf->SetTextColor(150, 0, 0);
            } 
            $this->setfieldwidth(100,10);
            $this->textbox($group_row_1['VILLAGECODE'].'-'.$group_row_1['VILLAGENAMEUNI'].'-'.$group_row_1['CIRCLENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
            $this->newrow();
            if ($this->isnewpage(10))
            {
                $this->newrow();
                $this->newpage(True,True);
                $this->pdf->SetTextColor(150, 0, 0);
            }
        }
        $this->pdf->SetTextColor(0, 0, 0);
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True,True);
        }  
        $this->newrow(15);
        $this->setfieldwidth(20,30);
        $this->textbox('क्लार्क',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(50);
        $this->textbox('केन अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(50);
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

}    
?>