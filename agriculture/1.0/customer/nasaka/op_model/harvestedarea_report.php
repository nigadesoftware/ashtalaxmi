<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    include_once("../ip_model/plantationhangam_db_oracle.php");
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class harvestedarea extends reportbox
{
    public $circlesummary;
    public $summary;
    public $fromdate;
    public $todate;
    public $plantationhangamcode;
    public $plantationhangamname;
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
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueType', '', 32);
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
        $this->totalgroupcount=2;
        $this->newpage(True);
        $this->group();
    }

    function pageheader()
    {
        ob_flush();
        ob_start();
       
        $this->liney = 13;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->newrow(4);
        $this->setfieldwidth(200,0);
        $this->textbox('गटनिहाय गाववार तुटलेले क्षेत्र',$this->w,$this->x,'S','C',1,'siddhanta',13,'','','','B');
        $this->newrow();
        if ($this->plantationhangamcode != '')
        {
            $plantationhangam = new plantationhangam($this->connection);
            $plantationhangam->plantationhangamcode = $this->plantationhangamcode; 
            $plantationhangam->fetch();
            $this->plantationhangamname = $plantationhangam->plantationhangamnameuni;
        }
        else
        {
            $this->plantationhangamname = '';
        }    
        if ($this->fromdate!='' and $this->todate !='')
            $this->textbox('लागवड '.$this->plantationhangamname.' दिनांक '.$this->fromdate.' पासून दिनांक '.$this->todate.' पर्यंत',$this->w,$this->x,'S','C',1,'siddhanta',13,'','','','B');
        elseif ($this->plantationhangamname!='')
            $this->textbox('लागवड '.$this->plantationhangamname,$this->w,$this->x,'S','C',1,'siddhanta',13,'','','','B');
        $this->newrow();
        $this->hline(10,200,$this->liney,'C');
        $this->setfieldwidth(20,10);
        $this->textbox('गट',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(45);
        $this->textbox('गाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox('लागवड क्षेत्र',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox('तुटलेले क्षेत्र',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox('तोड चालू क्षेत्र',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox('शिल्लक क्षेत्र',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->newrow();
        $this->hline(10,200,$this->liney,'D');
    }

    function group()
    {
        $this->totalgroupcount=1;
        $this->summary['PLANTATIONAREA']=0;
        $this->summary['HARVESTEDAREA']=0;
        $this->summary['HARVESTINGAREA']=0;
        $this->summary['BALANCEAREA']=0;
        $this->summary['HARVESTEDTONNAGE']=0;
        $this->summary['HARVESTINGTONNAGE']=0;
        $cond=' and h.seasoncode ='.$_SESSION['yearperiodcode'];
        if ($this->fromdate !='' and $this->todate !='')
        {
            $fdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $tdt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $cond=$cond." and plantationdate>='".$fdt."' and plantationdate<='".$tdt."'";
        }
        if ($this->plantationhangamcode !='' AND $this->plantationhangamcode!='0')
        {
            $cond = $cond . " and plantationhangamcode=". $this->plantationhangamcode;
        }
        $group_query_1 ="select t.circlecode,v.villagenameuni,c.circlenameuni,t.villagecode,sum(plantationarea) plantationarea
        ,sum(harvestedarea) harvestedarea,sum(harvestingarea) harvestingarea
        ,sum(plantationarea)-sum(harvestedarea)-sum(harvestingarea) balancearea
        ,sum(harvestedtonnage) harvestedtonnage
        ,sum(harvestingtonnage) harvestingtonnage
        from (
        select h.seasoncode,c.circlecode,v.villagecode,sum(area) as plantationarea,0 harvestedarea,0 harvestingarea, 0 harvestedtonnage,0 harvestingtonnage
        from plantationheader h,village v,circle c
        where h.villagecode=v.villagecode
        and v.circlecode=c.circlecode
        and area>0 
        $cond
        group by h.seasoncode,c.circlecode,v.villagecode
        union all
        select seasoncode,circlecode,villagecode,0 plantationarea,sum(area) as harvestedarea,0 harvestingarea,0 harvestedtonnage,0 harvestingtonnage
        from (
        select h.seasoncode,c.circlecode,v.villagecode,max(area) area
        from plantationheader h,village v,circle c,todslip t
        where h.villagecode=v.villagecode
        and v.circlecode=c.circlecode
        and h.seasoncode=t.seasoncode
        and h.plotnumber=t.plotnumber
        and nvl(t.isharvestingcompleted,0)=1 
        and (h.seasoncode,h.plotnumber) not in (
            select h.seasoncode,h.plotnumber
            from plantationheader h,village v,circle c,todslip t
            where h.villagecode=v.villagecode
            and v.circlecode=c.circlecode
            and h.seasoncode=t.seasoncode
            and h.plotnumber=t.plotnumber
            and nvl(t.isharvestingcompleted,0)=0
            $cond
            group by h.seasoncode,h.plotnumber)
        $cond
        group by h.seasoncode,c.circlecode,v.villagecode,h.plotnumber)
        group by seasoncode,circlecode,villagecode
        union all
        select h.seasoncode,c.circlecode,v.villagecode,0 plantationarea,0 as harvestedarea,0 harvestingarea,sum(netweight) harvestedtonnage,0 harvestingtonnage
        from plantationheader h,village v,circle c,todslip t
        ,fieldslip f,weightslip w
        where h.villagecode=v.villagecode
        and v.circlecode=c.circlecode
        and h.seasoncode=t.seasoncode
        and h.plotnumber=t.plotnumber
        and nvl(t.isharvestingcompleted,0)=1 
        and t.seasoncode=f.seasoncode
        and t.todslipnumber=f.todslipnumber
        and f.seasoncode=w.seasoncode
        and f.fieldslipnumber=w.fieldslipnumber
        and (h.seasoncode,h.plotnumber) not in (
                select h.seasoncode,h.plotnumber
                from plantationheader h,village v,circle c,todslip t
                where h.villagecode=v.villagecode
                and v.circlecode=c.circlecode
                and h.seasoncode=t.seasoncode
                and h.plotnumber=t.plotnumber
                and nvl(t.isharvestingcompleted,0)=0
                $cond
                group by h.seasoncode,h.plotnumber)
        $cond
        group by h.seasoncode,c.circlecode,v.villagecode
        union all
        select seasoncode,circlecode,villagecode,0 plantationarea,0 as harvestedarea,sum(area) as harvestingarea,0 harvestedtonnage,0 harvestingtonnage
        from (
        select h.seasoncode,c.circlecode,v.villagecode,max(area) area
        from plantationheader h,village v,circle c,todslip t
        where h.villagecode=v.villagecode
        and v.circlecode=c.circlecode
        and h.seasoncode=t.seasoncode
        and h.plotnumber=t.plotnumber
        and nvl(t.isharvestingcompleted,0)=0 
        $cond
        group by h.seasoncode,c.circlecode,v.villagecode,h.plotnumber)
        group by seasoncode,circlecode,villagecode
        union all
        select h.seasoncode,c.circlecode,v.villagecode,0 plantationarea,0 harvestedarea,0 as harvestingarea,0 harvestedtonnage,sum(netweight) harvestingtonnage
        from plantationheader h,village v,circle c,todslip t
        ,fieldslip f,weightslip w
        where h.villagecode=v.villagecode
        and v.circlecode=c.circlecode
        and h.seasoncode=t.seasoncode
        and h.plotnumber=t.plotnumber
        and nvl(t.isharvestingcompleted,0)=0 
        and t.seasoncode=f.seasoncode
        and t.todslipnumber=f.todslipnumber
        and f.seasoncode=w.seasoncode
        and f.fieldslipnumber=w.fieldslipnumber
        $cond
        group by h.seasoncode,c.circlecode,v.villagecode
        )t,village v,circle c
        where t.villagecode=v.villagecode
        and v.circlecode=c.circlecode
        group by seasoncode,t.circlecode,c.circlenameuni,t.villagecode,v.villagenameuni
        having seasoncode=".$_SESSION['yearperiodcode']."
        order by t.circlecode,v.villagenameuni,c.circlenameuni,t.villagecode
        ";
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);              
            $this->hline(30,200,$this->liney,'D'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
    }
    
    function groupheader_1(&$group_row)
    {
        if ($this->isnewpage(30))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,395,$this->liney-2,'C'); 
        }
        $this->circlesummary['PLANTATIONAREA']=0;
        $this->circlesummary['HARVESTEDAREA']=0;
        $this->circlesummary['HARVESTINGAREA']=0;
        $this->circlesummary['BALANCEAREA']=0;
        $this->circlesummary['HARVESTEDTONNAGE']=0;
        $this->circlesummary['HARVESTINGTONNAGE']=0;
        $this->setfieldwidth(60,10);
        $this->textbox($group_row['CIRCLENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','');
        $this->newrow();
    }

    function groupheader_2(&$group_row)
    {
    }

    function groupheader_3(&$group_row)
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
        if ($this->isnewpage(30))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,395,$this->liney-2,'C'); 
        }
        $this->setfieldwidth(40,30);
        $this->textbox($group_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox(number_format($group_row_1['PLANTATIONAREA'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox(number_format($group_row_1['HARVESTEDAREA'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(4);
        $this->textbox(number_format($group_row_1['HARVESTEDTONNAGE'],3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(-4);
        $this->setfieldwidth(30);
        $this->textbox(number_format($group_row_1['HARVESTINGAREA'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(4);
        $this->textbox(number_format($group_row_1['HARVESTINGTONNAGE'],3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(-4);
        $this->setfieldwidth(30);
        $this->textbox(number_format($group_row_1['BALANCEAREA'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(10);
        $this->circlesummary['PLANTATIONAREA']=$this->circlesummary['PLANTATIONAREA']+$group_row_1['PLANTATIONAREA'];
        $this->circlesummary['HARVESTEDAREA']=$this->circlesummary['HARVESTEDAREA']+$group_row_1['HARVESTEDAREA'];
        $this->circlesummary['HARVESTINGAREA']=$this->circlesummary['HARVESTINGAREA']+$group_row_1['HARVESTINGAREA'];
        $this->circlesummary['BALANCEAREA']=$this->circlesummary['BALANCEAREA']+$group_row_1['BALANCEAREA'];
        $this->circlesummary['HARVESTEDTONNAGE']=$this->circlesummary['HARVESTEDTONNAGE']+$group_row_1['HARVESTEDTONNAGE'];
        $this->circlesummary['HARVESTINGTONNAGE']=$this->circlesummary['HARVESTINGTONNAGE']+$group_row_1['HARVESTINGTONNAGE'];

        $this->summary['PLANTATIONAREA']=$this->summary['PLANTATIONAREA']+$group_row_1['PLANTATIONAREA'];
        $this->summary['HARVESTEDAREA']=$this->summary['HARVESTEDAREA']+$group_row_1['HARVESTEDAREA'];
        $this->summary['HARVESTINGAREA']=$this->summary['HARVESTINGAREA']+$group_row_1['HARVESTINGAREA'];
        $this->summary['BALANCEAREA']=$this->summary['BALANCEAREA']+$group_row_1['BALANCEAREA'];
        $this->summary['HARVESTEDTONNAGE']=$this->summary['HARVESTEDTONNAGE']+$group_row_1['HARVESTEDTONNAGE'];
        $this->summary['HARVESTINGTONNAGE']=$this->summary['HARVESTINGTONNAGE']+$group_row_1['HARVESTINGTONNAGE'];
        
    }


    function groupfooter_1(&$last_row)
    {      
        if ($this->isnewpage(30))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,395,$this->liney-2,'C'); 
        }
        $this->hline(10,200,$this->liney,'C');  
        $this->setfieldwidth(40,30);
        $this->textbox('गट एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox(number_format($this->circlesummary['PLANTATIONAREA'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox(number_format($this->circlesummary['HARVESTEDAREA'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(4);
        $this->textbox(number_format($this->circlesummary['HARVESTEDTONNAGE'],3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(-4);
        $this->setfieldwidth(30);
        $this->textbox(number_format($this->circlesummary['HARVESTINGAREA'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(4);
        $this->textbox(number_format($this->circlesummary['HARVESTINGTONNAGE'],3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(-4);
        $this->setfieldwidth(30);
        $this->textbox(number_format($this->circlesummary['BALANCEAREA'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(10);
        $this->hline(10,200,$this->liney,'C');  
        $this->newrow();
        /* $this->textbox('Clerk',$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('Cane Accountant',$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('Chief Accountant',$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('Managing Director',$this->w,$this->x,'S','R',1,'verdana',10,'','','',''); */
    }
    function groupfooter_2(&$last_row)
    {    
    }
    function groupfooter_3(&$last_row)
    {      
    }
    function groupfooter_4(&$last_row)
    {      
    }
    function groupfooter_5(&$last_row)
    {      
    }
    function groupfooter_6(&$last_row)
    {      
    }
    function groupfooter_7(&$last_row)
    {      
    }
    function pagefooter($islastpage=false)
    {
    }
    function subreportgroupheader(&$subreportgroup_row,$subreportnumber,$groupnumber)
    {
    }

    function subreportgroupfooter(&$subreportlast_row,$subreportnumber,$groupnumber)
    {
    }
    function reportfooter()
    {
        if ($this->isnewpage(30))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,395,$this->liney-2,'C'); 
        }
        $this->hline(10,200,$this->liney,'C');  
        $this->setfieldwidth(40,30);
        $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox(number_format($this->summary['PLANTATIONAREA'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox(number_format($this->summary['HARVESTEDAREA'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(4);
        $this->textbox(number_format($this->summary['HARVESTEDTONNAGE'],3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(-4);
        $this->setfieldwidth(30);
        $this->textbox(number_format($this->summary['HARVESTINGAREA'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(4);
        $this->textbox(number_format($this->summary['HARVESTINGTONNAGE'],3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(-4);
        $this->setfieldwidth(30);
        $this->textbox(number_format($this->summary['BALANCEAREA'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(10);
        $this->hline(10,200,$this->liney,'C');  
        $this->newrow();
        /* $this->textbox('Clerk',$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('Cane Development Officer',$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('Chief Agriculture Officer',$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('Managing Director',$this->w,$this->x,'S','R',1,'verdana',10,'','','',''); */
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
    function export($billperiodtransnumber,$paymentcategorycode)
    {
           $query ="select paymentcategorycode
            ,bankcode
            ,bankbranchcode
            ,banknameeng
            ,bankbranchnameeng
            ,transactionnumber
            ,billnumber
            ,paymentdate
            ,farmercode
            ,farmernameeng
            ,villagenameeng
            ,netamount
            ,accountnumber
            ,mobilenumber
            ,ifsc
            ,code
            ,verified
            from (
            select
            case when c.bankcode=29 then 1 
            when netamount>=200000 and netamount<500000 then 2 
            when netamount>=500000 then 4 
            else 3 end paymentcategorycode
            ,c.bankcode
            ,t.bankbranchcode
            ,c.banknameeng
            ,b.bankbranchnameeng
            ,t.transactionnumber
            ,t.billnumber
            ,to_char(h.paymentdate,'dd/MM/yyyy') paymentdate
            ,f.farmercode
            ,f.farmernameeng
            ,v.villagenameeng
            ,t.netamount
            ,t.accountnumber as accountnumber
            ,f.mobilenumber
            ,b.ifsc
            ,nvl(f.verified,0) verified
            ,case when t.farmercategorycode=1 then 'M'||to_char(t.farmercode)
        when t.farmercategorycode=2 then 'N'||to_char(t.farmercode)
        when t.farmercategorycode=3 then 'G'||to_char(t.farmercode) end code
            from farmerbillheader t,farmerwithadhikarpatra f, village v
            ,billperiodheader h,bankbranch b, bank c,Bankcategory r
            where t.farmercode=f.farmercode
            and h.seasonyear=f.seasoncode
            and f.villagecode=v.villagecode
            and t.billperiodtransnumber=h.billperiodtransnumber
            and c.bankcategorycode=r.bankcategorycode
            and b.bankcode=c.bankcode
            and t.bankbranchcode=b.bankbranchcode(+)
            and nvl(t.netamount,0)>0
            and t.bankbranchcode<>45
            and t.billperiodtransnumber=".$billperiodtransnumber.")t  
            where paymentcategorycode={$paymentcategorycode}
            order by paymentcategorycode,bankcode,bankbranchcode,farmernameeng
            ,accountnumber";
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $response = array();
           if ($paymentcategorycode==1)
           $filename='farmerrtgs_kotak.csv';
           elseif ($paymentcategorycode==2)
           $filename='farmerrtgs_above2lac.csv';
           elseif ($paymentcategorycode==3)
           $filename='farmerrtgs_below2lac.csv';
           //$fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');
           $fp1=fopen('php://memory', 'w');
           fputcsv($fp1, array('NEFT','Debit A/c','Amount','Name of Beneficiery','Type A/c','Address','Account No','IFSC Code','Mobile No.','Name of Bank','Code No.','Village'));
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                $acno="'".$row['ACCOUNTNUMBER'];
                if ($row['VERIFIED'] == 0)
                fputcsv($fp1, array('NEFT','4812021567',$row['NETAMOUNT'],$row['FARMERNAMEENG'],'*Saving','KSSK',$acno,$row['IFSC'],$row['MOBILENUMBER'],$row['BANKNAMEENG'].', '.$row['BANKBRANCHNAMEENG'],$row['CODE'],$row['VILLAGENAMEENG']), $delimiter = ',', $enclosure = '"');
                else
                fputcsv($fp1, array('NEFT','4812021567',$row['NETAMOUNT'],$row['FARMERNAMEENG'],'Saving','KSSK',$acno,$row['IFSC'],$row['MOBILENUMBER'],$row['BANKNAMEENG'].', '.$row['BANKBRANCHNAMEENG'],$row['CODE'],$row['VILLAGENAMEENG']), $delimiter = ',', $enclosure = '"');
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