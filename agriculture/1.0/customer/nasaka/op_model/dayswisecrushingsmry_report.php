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
        $this->textbox('डे वाईज टनेज रेपोर्ट '.$_SESSION['yearperiodcode'],180,10,'S','C',1,'siddhanta',13);
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
        $this->textbox('दिनांक',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox('टनेज',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');        
        $this->setfieldwidth(25);
        $this->textbox('रिमार्क',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
       
        $this->newrow(7);
        $this->hline(10,200,$this->liney-2,'C'); 
    }
   
    function group()
    {
        $this->totalgroupcount=1;
        $this->summary['WT']=0;
        $this->summary['nocane']=0;
        
        $cond="1=1";
        $cond= $cond.' and t.seasoncode='.$_SESSION['yearperiodcode'];
       
        if ($this->fromdate!='' and $this->todate!='')
        {
            $frdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $todt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $cond=$cond." and weighmentdate>='".$frdt."' and weighmentdate<='".$todt."'";
        }
         
        //////////////////////////////

        $group_query_0 ="
        select generated_date,to_char(generated_date,'dd/MM/yyyy')mmdate from
        (
       WITH date_range AS (
          SELECT to_date('".$frdt."') AS start_date, to_date('".$todt."') AS end_date FROM dual
        )
        ,qr as (SELECT start_date + LEVEL - 1 AS generated_date
        FROM date_range
        CONNECT BY LEVEL <= end_date - start_date + 1)
        select * from qr
        )";
        $group_result_0 = oci_parse($this->connection, $group_query_0);
        $r0 = oci_execute($group_result_0);
        $i=1;
        //$this->newpage(true);
        while ($group_row_0 = oci_fetch_array($group_result_0,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
        ///////////////////////////////
        $mmdt =$group_row_0['MMDATE'];// DateTime::createFromFormat('d/m/Y',$group_row_0['GENERATED_DATE'])->format('d-M-Y');
        $group_query_1 = "select $i sr_no,'$mmdt' as mmdate, round(nvl(sum(w.netweight),0),3)wt from weightslip w
        where w.weighmentdate='".$group_row_0['GENERATED_DATE']."'
        ";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
           // $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
           // $last_row=$group_row_1;
        }
        $i=$i+1;
    }
        $this->grouptrigger($group_row_1,$last_row,'E');
    }
    function groupheader_1(&$group_row_1)
    {
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
        ob_flush();
        ob_start();
       
        $this->setfieldwidth(20,10);
        $this->textbox($group_row_1['SR_NO'],$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['MMDATE'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['WT'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');       
       
        if($group_row_1['WT']<1250)
       
       { $this->setfieldwidth(100);
        $this->textbox('No Cane/Technical Problem',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->summary['nocane']=$this->summary['nocane']+1;
        }
        $this->newrow(5);
        $this->hline(10,200,$this->liney,'D'); 
       
       
        $this->summary['WT']=$this->summary['WT']+$group_row_1['WT'];
       
       
        if ($this->isnewpage(10))
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
          //  $this->newrow();
           // $this->hline(10,195,$this->liney-2,'C'); 
        }
    }


    function groupfooter_1(&$group_row_1)
    {

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
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
        }

        $this->setfieldwidth(20,10);
     //   $this->setfieldwidth(30);
        $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(50);
        $this->textbox($this->numformat($this->summary['WT'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->setfieldwidth(50);
        $this->textbox('Total Nocane:  '.$this->numformat($this->summary['nocane'],0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');

        
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