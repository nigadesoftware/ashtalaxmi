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
       $this->pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
      
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
        $this->groupfield1='WT';
        $this->groupfield2='KOYATE_WT';
       
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
        $this->setfieldwidth(200,10);
        $this->textbox('Agriculture Contractor wise Tonnage Summery Report',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->newrow();
        if ($this->fromdate !='' and $this->todate !='')
        {
            $this->textbox('From Date :'.$this->fromdate.' To Date :'.$this->todate,$this->w,$this->x,'S','C',1,'verdana',10,'','','','');  
        }     
        $this->newrow();
        $this->hline(10,205,$this->liney,'C');

        $this->setfieldwidth(15,10);
        $this->vline($this->liney,$this->liney+7,$this->x); 
        $this->textbox('Sr.No',$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 


        $this->setfieldwidth(20);
        $this->textbox('Code',$this->w,$this->x,'S','C',1,'verdana',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 


        $this->setfieldwidth(60);
        $this->textbox('Name',$this->w,$this->x,'S','C',1,'verdana',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

      
        $this->setfieldwidth(25);
        $this->textbox('Tonnage',$this->w,$this->x,'S','C',1,'verdana',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(25);
        $this->textbox('App. Koyate',$this->w,$this->x,'S','C',1,'verdana',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(25);
        $this->textbox('Act Koyate',$this->w,$this->x,'S','C',1,'verdana',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(25);
        $this->textbox('Difference',$this->w,$this->x,'S','C',1,'verdana',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 
        
       
        $this->newrow();
        $this->hline(10,205,$this->liney,'C');
    }

    function group()
    {
        $this->totalgroupcount=2;
        $cond="T.SEASONCODE=".$_SESSION['yearperiodcode'] ;
       
         if ($this->fromdate !='' and $this->todate !='')
        {
            $fdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $tdt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $cond=$cond." and t.weighmentdate>='".$fdt."' and t.weighmentdate<='".$tdt."'";
        } 

        if ($this->contractorcode!=0)
        {
            if ($cond=="")
                $cond="t.CONTRACTORCODE=".$this->contractorcode;
            else
                $cond=$cond." and t.CONTRACTORCODE=".$this->contractorcode;
        }
        if ($this->hodcode!=0)
        {
            if ($cond=="")
                $cond="c.hodcode=".$this->hodcode;
            else
                $cond=$cond." and c.hodcode=".$this->hodcode;
        }
        
        $group_query_1 =
         " select cc.circlecode,c.hodcode,h.hodnameeng,cc.circlenameeng
         ,t.CONTRACTORCODE,t.CONTRACTORNAMEENG 
          ,row_number() over (partition by cc.circlecode,c.hodcode order by t.CONTRACTORCODE) srnumber            
          ,sum(t.transportationtonnage) wt,round(sum(t.transportationtonnage)/2,0) koyate_wt
          from HT_TONNAGE_mm t,contractor c
          ,hodcode h,village v,circle cc
           where {$cond} and
          t.CONTRACTORCODE=c.contractorcode 
          and c.hodcode=h.hodcode
          and t.villagecode=v.villagecode
          and v.circlecode=cc.circlecode
          group by c.hodcode,h.hodnameeng,cc.circlecode,cc.circlenameeng
          ,t.CONTRACTORCODE,t.CONTRACTORNAMEENG            
          order by cc.circlecode,c.hodcode,t.CONTRACTORCODE
         
           ";   
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->sumgroupsummary($group_row_1,0);        
            $this->sumgroupsummary($group_row_1,1);   
            $this->sumgroupsummary($group_row_1,2);          
            $last_row=$group_row_1;
            $this->mm=0;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
    }

    function groupheader_1(&$group_row_1)
    {
        $this->resetgroupsummary(1);
        $this->setfieldwidth(195,10);
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox('Vibhag Name: '.$group_row_1['CIRCLECODE'].' - '.$group_row_1['CIRCLENAMEENG'],$this->w,$this->x,'S','L',1,'verdana',10,'','','','B');  
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);  
        $this->newrow();
        $this->hline(10,205,$this->liney,'C');
    }

    function groupheader_2(&$group_row_1)
    {
        $this->resetgroupsummary(2);
        $this->setfieldwidth(195,10);
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox('HOD Name: '.$group_row_1['HODCODE'].' - '.$group_row_1['HODNAMEENG'],$this->w,$this->x,'S','L',1,'verdana',10,'','','','');  
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);  
        $this->newrow();
        $this->hline(10,205,$this->liney,'C');
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
        $this->textbox($group_row_1['SRNUMBER'],$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['CONTRACTORCODE'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        
        $this->setfieldwidth(60);
        $this->textbox($group_row_1['CONTRACTORNAMEENG'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);


        $this->setfieldwidth(25);
        $this->textbox(number_format($group_row_1['WT'],3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['KOYATE_WT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(25);        
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);        
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,205,$this->liney,'C');
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(10,205,$this->liney,'C');
        }
       
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
        $this->hline(10,205,$this->liney,'C');  
       
        $this->setfieldwidth(95,10);   
        $this->vline($this->liney,$this->liney+7,$this->x);   
        $this->textbox('Vibhag Total :',$this->w,$this->x,'S','R',1,'verdana',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($this->numformat($this->showgroupsummary(1,'WT'),3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($this->numformat($this->showgroupsummary(1,'KOYATE_WT'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

       
        
        $this->newrow();
        $this->hline(10,205,$this->liney,'C');  
             
     }
    function groupfooter_2(&$last_row)
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
        $this->hline(10,205,$this->liney,'C');  
       
        $this->setfieldwidth(95,10);   
        $this->vline($this->liney,$this->liney+7,$this->x);   
        $this->textbox('Group Total :',$this->w,$this->x,'S','R',1,'verdana',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($this->numformat($this->showgroupsummary(2,'WT'),3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($this->numformat($this->showgroupsummary(2,'KOYATE_WT'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

       
        
        $this->newrow();
        $this->hline(10,205,$this->liney,'C'); 
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
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,395,$this->liney-2,'C'); 
        }
        $this->hline(10,205,$this->liney,'C');  
       
        $this->setfieldwidth(95,10);   
        $this->vline($this->liney,$this->liney+7,$this->x);   
        $this->textbox('Grand Total :',$this->w,$this->x,'S','R',1,'verdana',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($this->numformat($this->showgroupsummary(0,'WT'),3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($this->numformat($this->showgroupsummary(0,'KOYATE_WT'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);        
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);        
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);
       
        
        $this->newrow();
        $this->hline(10,205,$this->liney,'C');  
         
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