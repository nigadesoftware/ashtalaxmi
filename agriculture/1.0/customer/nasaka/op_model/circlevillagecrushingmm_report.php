<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class circlevillagecrushing extends reportbox
{
    public $fromdate;
    public $todate;
    public $circlesummary;
    public $villagesummary;
    public $summary;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueType', '', 32);
    	// create new PDF document
	    $this->pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Circle');
        $this->pdf->SetKeywords('CIRCRUSH_000.MR');
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));
       // $title = str_pad(' ', 25).'Rajaramnagar, Tal - Dindori Dist - Nashik';
    	//$this->pdf->SetHeaderData('', 0,str_pad(' ', 21).'Kadwa S.S.K. Ltd.' ,$title);
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
        $lg['a_meta_language'] = 'mr';
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
        $this->liney = 18;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->textbox('गटनिहाय गाववार ऊस गाळप '.$_SESSION['yearperiodcode'],180,10,'S','C',1,'siddhanta',13);
        if ($this->fromdate!='' and $this->todate!='')
        {
            $this->newrow(7);
            $this->textbox('दिनांक '.$this->fromdate.' पासून दिनांक '.$this->todate.' पर्यंत',100,50,'S','L',1,'siddhanta',12);
        }
        
        $this->newrow(7);
        
        $this->hline(10,200,$this->liney,'C');
        $this->setfieldwidth(20,10);
        $this->textbox('अनु क्र',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox('कोड',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(50);
        $this->textbox('सभासदाचे नाव',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('मोबाईल न.',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('टनेज',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
       
        $this->newrow(7);
        $this->hline(10,200,$this->liney-2,'C'); 
    }
   
    function group()
    {
        $this->totalgroupcount=2;
        $this->summary['tonnage']=0;
        
        $cond="1=1";
        $cond= $cond.' and t.seasoncode='.$_SESSION['yearperiodcode'];
       
        if ($this->fromdate!='' and $this->todate!='')
        {
            $frdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $todt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $cond=$cond." and weighmentdate>='".$frdt."' and weighmentdate<='".$todt."'";
        }
        if ($this->circlecode!=0)
        {
            if ($cond=="")
                $cond="c.circlecode=".$this->circlecode;
            else
                $cond=$cond." and c.circlecode=".$this->circlecode;
        }
        if ($this->villagecode!=0)
        {
            if ($cond=="")
                $cond="v.villagecode=".$this->villagecode;
            else
                $cond=$cond." and v.villagecode=".$this->villagecode;
        }
       
        $group_query_1 = "select c.circlecode,v.villagecode
        ,c.circlenameuni,v.villagenameuni
        ,row_number() over(partition by v.villagecode order by f.farmercode)sr_no
        ,f.farmercode,p.farmernameuni,p.mobilenumber
        ,sum(t.netweight)tonnage
        from weightslip t,fieldslip f
        ,village v,circle c,farmer p
        where {$cond} and
        t.seasoncode=f.seasoncode(+) and
        t.fieldslipnumber=f.fieldslipnumber(+)
        and p.villagecode=v.villagecode(+)
        and v.circlecode=c.circlecode(+)
        and f.farmercode=p.farmercode(+)
        and nvl(t.netweight,0)>0
        group by c.circlecode,v.villagecode
        ,c.circlenameuni,v.villagenameuni
        ,f.farmercode,p.farmernameuni,p.mobilenumber
        order by c.circlecode,v.villagecode,f.farmercode
        ";
        
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
        $this->circlename=$group_row_1['CIRCLENAMEUNI'];
        $this->circlesummary['TONNAGE']=0;
       
        $this->setfieldwidth(30,10);
        $this->textbox('गट-'.$group_row_1['CIRCLENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->newrow();
    }

    function groupheader_2(&$group_row_1)
    {
       
        $this->villagesummary['TONNAGE']=0;
         $this->setfieldwidth(30,10);
        $this->textbox('गाव-'.$group_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->newrow();
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
        ob_flush();
        ob_start();
        //$this->hline(10,405,$this->liney-2,'D'); 
        $this->setfieldwidth(20,10);
        //$this->textbox($group_row_1['CIRCLENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['SR_NO'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['FARMERCODE'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(50);
        $this->textbox($group_row_1['FARMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['MOBILENUMBER'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['TONNAGE'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->newrow(5);
        $this->hline(10,200,$this->liney,'D'); 
       
        $this->circlesummary['TONNAGE']=$this->circlesummary['TONNAGE']+$group_row_1['TONNAGE'];
        $this->summary['TONNAGE']=$this->summary['TONNAGE']+$group_row_1['TONNAGE'];
        $this->villagesummary['TONNAGE']=$this->circlesummary['TONNAGE'];
       
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
    }


    function groupfooter_1(&$group_row_1)
    {     
        if ($this->isnewpage(10))
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

        $this->setfieldwidth(20,10);
        $this->setfieldwidth(30);
        $this->textbox('गट एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(160,10);
        $this->textbox($this->numformat($this->circlesummary['TONNAGE'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
       
        $this->newrow();
        $this->hline(10,200,$this->liney-2,'C');  

    }

    function groupfooter_2(&$group_row_1)
    {      
        $this->setfieldwidth(20,10);
        $this->setfieldwidth(30);
        $this->textbox('गाव एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(160,10);
        $this->textbox($this->numformat($this->villagesummary['TONNAGE'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
       
        $this->newrow();
        $this->hline(10,200,$this->liney-2,'C');  
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
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
        }

        $this->setfieldwidth(20,10);
        $this->setfieldwidth(30);
        $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox($this->numformat($this->summary['TONNAGE'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
         $this->newrow();
        $this->hline(10,200,$this->liney-2,'C'); 
        $this->newrow(10);
        $this->setfieldwidth(20,60);
        $this->textbox('क्लार्क',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('केन अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','');
    
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