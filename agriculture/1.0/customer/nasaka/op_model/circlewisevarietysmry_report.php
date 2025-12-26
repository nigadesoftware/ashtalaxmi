<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_P.php");
    include_once("../info/routine.php");    
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class circlewise extends reportbox
{
   
    public $farmercategorycode;
    public $name;
    public $mm;
    public $divisioncode;
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
        $this->groupfield1='NETWEIGHT';
        $this->groupfield2='PERCENTAGE';
        
       
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
        $this->textbox(' जातवार टनेज समरी',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','');
        $this->newrow();

        if ($this->fromdate !='' and $this->todate !='')
        {
            $this->textbox('दिनांक पासून'.$this->fromdate.' दिनांक '.$this->todate.'पर्यंत',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','');
        } 
             
        $this->newrow();
        $this->hline(20,130,$this->liney,'C');
        $this->setfieldwidth(20,20);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('अनु.क्र',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(40);
        $this->textbox('जात नाव',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(30);
        $this->textbox('टनेज',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(20);
        $this->textbox('टक्के(%)',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 
        
        
        $this->newrow();
        $this->hline(20,130,$this->liney,'C');
    }

    function group()
    {
        $this->totalgroupcount=2;
        $cond1='1=1';
        $cond='1=1';
         if ($this->fromdate !='' and $this->todate !='')
        {
            $fdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $tdt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $cond=$cond." and weighmentdate>='".$fdt."' and weighmentdate<='".$tdt."'";         
        }
           
        if ($this->circlecode!=0)
        {
            if ($cond1=="")
                $cond1="and c.circlecode=".$this->circlecode;
            else
                $cond1=$cond1." and c.circlecode=".$this->circlecode;
        } 
       
         $group_query_1 = " 
            
         select m.plantationhangamcode,c.circlecode,
         row_number() over(partition by m.plantationhangamcode,c.circlecode order by m.varietycode)sr_no
         ,m.varietycode,va.varietynameuni,hm.plantationhangamnameuni
         ,c.circlenameuni,sum(netweight)netweight
         ,round((sum(netweight)*100)/(select sum(t.netweight) from VW_ALL_TONNAGE_MM t
                                 where {$cond} --and
                                  --t.circlecode=c.circlecode
                                ),2)percentage
         from(select p.varietycode,f.villagecode,w.netweight,p.plantationhangamcode
         from weightslip w,fieldslip f,variety v,plantationheader p
         where {$cond} and
         w.seasoncode=f.seasoncode and w.fieldslipnumber=f.fieldslipnumber
         and f.seasoncode=p.seasoncode and f.plotnumber=p.plotnumber
         and p.varietycode=v.varietycode            
         )m,village vi,circle c,variety va,plantationhangam hm
          where {$cond1} and
         m.villagecode=vi.villagecode
         and m.plantationhangamcode=hm.plantationhangamcode
         and vi.circlecode=c.circlecode
         and m.varietycode=va.varietycode
         group by m.varietycode,va.varietynameuni
         ,c.circlecode,c.circlenameuni,m.plantationhangamcode,hm.plantationhangamnameuni
         order by plantationhangamcode,c.circlecode,m.varietycode
      
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
        $this->setfieldwidth(110,20);  
        $this->vline($this->liney,$this->liney+7,$this->x);  
        $this->textbox('हंगाम प्रकार- '.$group_row_1['PLANTATIONHANGAMNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B'); 
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);
      
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(20,130,$this->liney,'C');
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(20,130,$this->liney,'C');
        } 
    }

    function groupheader_2(&$group_row_1)
    {
        $this->resetgroupsummary(2);
        $this->setfieldwidth(110,20);  
        $this->vline($this->liney,$this->liney+7,$this->x);  
        $this->textbox('गट -'.$group_row_1['CIRCLENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B'); 
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);
      
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(20,130,$this->liney,'C');
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(20,130,$this->liney,'C');
        }
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
        $this->setfieldwidth(20,20);
        $this->vline($this->liney,$this->liney+7,$this->x); 
        $this->textbox($group_row_1['SR_NO'],$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(40);
        $this->textbox($group_row_1['VARIETYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(30);
        $this->textbox(number_format($group_row_1['NETWEIGHT'],3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',9,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(20);
        $this->textbox(number_format($group_row_1['PERCENTAGE'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',9,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 
        
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(20,130,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(20,130,$this->liney,'C'); 
        }
       
    }

    function groupfooter_1(&$group_row_1)
    {  
        $this->setfieldwidth(20,20);
       // $this->textbox($group_row_1['SR_NO'],$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x); 

        $this->setfieldwidth(40);
        $this->textbox($group_row_1['CIRCLENAMEUNI'].' एकूण:',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(30);
        $this->textbox($this->numformat($this->showgroupsummary(1,'NETWEIGHT'),3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 

        $this->setfieldwidth(20);
        $this->textbox($this->numformat($this->showgroupsummary(1,'PERCENTAGE'),2),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 
        $this->newrow();
        $this->hline(20,130,$this->liney,'C');      
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
        $this->setfieldwidth(20,20);
        // $this->textbox($group_row_1['SR_NO'],$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
         $this->vline($this->liney,$this->liney+7,$this->x); 
 
         $this->setfieldwidth(40);
         $this->textbox(' एकूण:',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 
 
         $this->setfieldwidth(30);
         $this->textbox($this->numformat($this->showgroupsummary(0,'NETWEIGHT'),3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 
 
         $this->setfieldwidth(20);
         $this->textbox($this->numformat($this->showgroupsummary(0,'PERCENTAGE'),2),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
         $this->vline($this->liney,$this->liney+7,$this->x+$this->w); 
         $this->newrow();
         $this->hline(20,130,$this->liney,'C');
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