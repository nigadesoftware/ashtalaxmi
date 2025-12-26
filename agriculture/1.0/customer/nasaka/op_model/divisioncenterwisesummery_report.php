<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_P.php");
    include_once("../info/routine.php");
    
    ini_set('max_execution_time', '0'); 
class tonnagesmry extends reportbox
{
    public $circlesummary;
  
   
    public $farmercategorycode;
    public $name;
    public $mm;
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
	   // $this->pdf = new MYPDF($this->orientation, PDF_UNIT, $this->papersize, true, 'UTF-8', false);
       $this->pdf = new MYPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
      
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
        $this->groupfield1='PLANTATIONKSHETRA';
        $this->groupfield2='PLANTATIONTONNAGE';
        $this->groupfield3='WT_AREA';
        $this->groupfield4='NET_WT';
        $this->groupfield5='AVAILABLE_AREA';
        $this->groupfield6='AVAILABLE_WT';
        $this->groupfield7='FARMER';

        $this->resetgroupsummary(0);
      

        $this->totalgroupcount=3;
        $this->newpage(True);
        $this->group();
    }

    function pageheader()
    {
        ob_flush();
        ob_start();
       
        $this->liney = 13;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->newrow();
        $this->setfieldwidth(290,10);
        $this->textbox('शिल्लक क्षेत्र व ऊस - कार्यक्षेत्र आणि गेटकेन ',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B');
        $this->newrow();        
        $this->textbox('सिझन :'.$_SESSION['yearperiodcode'],$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');  
            
        $this->newrow();
        $this->hline(10,285,$this->liney,'C');

        $this->setfieldwidth(15,10);
        $this->vline($this->liney,$this->liney+7,$this->x); 
        $this->textbox('अनु क्र',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 


        $this->setfieldwidth(20);
        $this->textbox('सेंटर कोड ',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 


        $this->setfieldwidth(40);
        $this->textbox('सेंटर',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

      
        $this->setfieldwidth(50);
        $this->textbox('उस नोंद ',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(50);
        $this->textbox('तोडणी ',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(50);
        $this->textbox('शिल्लक ऊस ',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(50);
        $this->textbox('शेतकरी संख्या',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 
        
        $this->newrow();
        $this->hline(10,285,$this->liney,'C');

        $this->setfieldwidth(15,10);
        $this->vline($this->liney,$this->liney+7,$this->x); 
       // $this->textbox('अनु क्र',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
       // $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 


        $this->setfieldwidth(20);
        //$this->textbox('सेंटर कोड ',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        //$this->vline($this->liney,$this->liney+7,$this->x+$this->w); 


        $this->setfieldwidth(40);
        //$this->textbox('सेंटर',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

      
        $this->setfieldwidth(20);
        $this->textbox('क्षेत्र',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(30);
        $this->textbox('टनेज ',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(20);
        $this->textbox('क्षेत्र',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(30);
        $this->textbox('टनेज ',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(20);
        $this->textbox('क्षेत्र',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(30);
        $this->textbox('टनेज ',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(50);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);
       /*  $this->setfieldwidth(20);
        $this->textbox('क्षेत्र',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(30);
        $this->textbox('टनेज ',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); */ 

        
       
        $this->newrow();
        $this->hline(10,285,$this->liney,'C');
    }

    function group()
    {
        $this->totalgroupcount=1;
        $cond="T.SEASONCODE=".$_SESSION['yearperiodcode'] ;
       
       
        if ($this->contractorcode!=0)
        {
            if ($cond=="")
                $cond="t.CONTRACTORCODE=".$this->contractorcode;
            else
                $cond=$cond." and t.CONTRACTORCODE=".$this->contractorcode;
        }
        
        
        $group_query_1 = "select tt.talukacode,c.centrecode,
        tt.talukanameuni,c.centrenameuni
        ,count(distinct(farmer))farmer
        ,sum(plantationkshetra)plantationkshetra
        ,round(sum(plantationkshetra)*50,3)plantationtonnage
        ,round(sum(wt_area),2)wt_area,round(sum(net_wt),3)net_wt
        ,round(sum(plantationkshetra)-sum(wt_area),2)available_area
        ,round((sum(plantationkshetra)*50)-sum(net_wt),3)available_wt
        from(
        select 0 farmer,t.villagecode,t.area plantationkshetra,0 wt_area,0 net_wt from plantationheader t
        where t.seasoncode=".$_SESSION['yearperiodcode']."
        union all
        select 0 farmer,f.villagecode,0 plantationkshetra,0 wt_area,w.netweight net_wt from weightslip w,fieldslip f 
        where w.seasoncode=f.seasoncode 
        and w.fieldslipnumber=f.fieldslipnumber
        and w.seasoncode=".$_SESSION['yearperiodcode']."
        union all
        select p.farmercode farmer,p.villagecode,0 plantationkshetra,p.area wt_area,0 net_wt
        from plantationheader p
        where p.seasoncode=".$_SESSION['yearperiodcode']." 
        and p.plotnumber in(select distinct(f.plotnumber)
        from weightslip w,fieldslip f 
        where w.seasoncode=f.seasoncode 
        and w.fieldslipnumber=f.fieldslipnumber
        and w.seasoncode=".$_SESSION['yearperiodcode']." and w.netweight>0)
        )m,village v,centre c,division d,taluka tt
        where m.villagecode=v.villagecode
        and v.centrecode=c.centrecode
        and v.talukacode=tt.talukacode
        and c.divisioncode=d.divisioncode
        group by tt.talukacode,tt.talukanameuni,c.centrecode,
        c.centrenameuni
        order by tt.talukacode,c.centrecode
          
           ";   
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->sumgroupsummary($group_row_1,0);        
            $this->sumgroupsummary($group_row_1,1);          
            $last_row=$group_row_1;
            $this->mm=0;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
    }

    function groupheader_1(&$group_row_1)
    {
        $this->resetgroupsummary(1);
        $this->setfieldwidth(275,10);
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox('तालुका :'.$group_row_1['TALUKANAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');  
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);  
        $this->newrow();
        $this->hline(10,285,$this->liney,'C');
        $this->sr_no=1;
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
       
        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox($this->sr_no,$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['CENTRECODE'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        
        $this->setfieldwidth(40);
        $this->textbox($group_row_1['CENTRENAMEUNI'],$this->w,$this->x,'N','L',1,'siddhanta',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);


        $this->setfieldwidth(20);
        $this->textbox(number_format($group_row_1['PLANTATIONKSHETRA'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox(number_format($group_row_1['PLANTATIONTONNAGE'],3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(number_format($group_row_1['WT_AREA'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox(number_format($group_row_1['NET_WT'],3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(number_format($group_row_1['AVAILABLE_AREA'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox(number_format($group_row_1['AVAILABLE_WT'],3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(number_format($group_row_1['FARMER'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        //$this->textbox(number_format($group_row_1['PLANTATIONTONNAGE'],3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);


        
        

        $this->sr_no=$this->sr_no+1;
        
        if ($this->isnewpage(150))
        {
            $this->newrow();
            $this->hline(10,285,$this->liney,'C');
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(10,285,$this->liney,'C');
        }
       
    }

    function groupfooter_1(&$last_row)
    {  
        if ($this->isnewpage(30))
        {
            $this->newrow();
            $this->hline(10,285,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,395,$this->liney-2,'C'); 
        }
        $this->hline(10,285,$this->liney,'C');  
       
        $this->setfieldwidth(75,10);   
        $this->vline($this->liney,$this->liney+7,$this->x);   
        $this->textbox('तालुका एकूण :',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->numformat($this->showgroupsummary(1,'PLANTATIONKSHETRA'),2),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox($this->numformat($this->showgroupsummary(1,'PLANTATIONTONNAGE'),3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->numformat($this->showgroupsummary(1,'WT_AREA'),2),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox($this->numformat($this->showgroupsummary(1,'NET_WT'),3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->numformat($this->showgroupsummary(1,'AVAILABLE_AREA'),2),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox($this->numformat($this->showgroupsummary(1,'AVAILABLE_WT'),3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->numformat($this->showgroupsummary(1,'FARMER'),2),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        //$this->textbox($this->numformat($this->showgroupsummary(1,'AVAILABLE_WT'),3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);
        
        $this->newrow();
        $this->hline(10,285,$this->liney,'C');  
             
    }

    function groupfooter_2(&$last_row)
    {      
       
    }
    
    function groupfooter_3(&$last_row_1)
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
            $this->hline(10,285,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,395,$this->liney-2,'C'); 
        }
        $this->hline(10,285,$this->liney,'C');  
       
        $this->setfieldwidth(75,10);   
        $this->vline($this->liney,$this->liney+7,$this->x);   
        $this->textbox('एकूण :',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->numformat($this->showgroupsummary(0,'PLANTATIONKSHETRA'),2),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox($this->numformat($this->showgroupsummary(0,'PLANTATIONTONNAGE'),3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->numformat($this->showgroupsummary(0,'WT_AREA'),2),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox($this->numformat($this->showgroupsummary(0,'NET_WT'),3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->numformat($this->showgroupsummary(0,'AVAILABLE_AREA'),2),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox($this->numformat($this->showgroupsummary(0,'AVAILABLE_WT'),3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->numformat($this->showgroupsummary(0,'FARMER'),2),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        //$this->textbox($this->numformat($this->showgroupsummary(1,'AVAILABLE_WT'),3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);
       
        
        $this->newrow();
        $this->hline(10,285,$this->liney,'C');  
         
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