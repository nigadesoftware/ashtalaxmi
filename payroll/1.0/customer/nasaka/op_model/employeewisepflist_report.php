<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class paysheet extends reportbox
{	
    public $basicamt;
    public $pfapplicableamt;
    public $employeepfamt;
    public $employeepensionamt;
   
   
  
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueType', '', 32);
    	// create new PDF document
	    $this->pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Sale Register');
        $this->pdf->SetKeywords('SLRG_000.EN');
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));
       // $title = str_pad(' ', 30).'Rajaramnagar, Tal - Dindori Dist - Nashik';
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
        $this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

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
        //$this->reportfooter();
    }
    function endreport()
    {
        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output('SLRG_000.pdf', 'I');
    }
	function pageheader()
    { 
        $this->liney = 10;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
       // $this->textbox($this->ftransactionnumber['FARMERCOUNT'],175,10,'S','C',1,'siddhanta',13);
       
         $this->newrow(10);
        $this->textbox($this->employeecategoryname.' कर्मचारी वाईज पी.एफ. यादी माहे-'.$this->monthname.' '.$_SESSION['yearperiodcode'],195,10,'S','C',1,'siddhanta',13);
             
               
        $this->newrow();
        $this->hline(10,195,$this->liney-1,'C'); 
        $this->setfieldwidth(15,10); 

        $this->vline($this->liney-1,$this->liney+6,$this->x);   
        $this->textbox('अनु क्र.',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('कर्मचारी कोड',$this->w,$this->x,'S','C',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(45);
        $this->textbox('कर्मचारी नाव',$this->w,$this->x,'S','C',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('बेसिक रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('पीएफ.लागू रक्कम',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('पीएफ. रक्कम',$this->w,$this->x,'S','C',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('पेंशन रक्कम',$this->w,$this->x,'S','C',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
     
      
        $this->newrow();

    }

    function pagefooter($islastpage=false)
    {
        if ($islastpage==false)
        { 
            //$this->drawlines(130-48);
        }
        else
        {
            //$this->drawlines($this->liney-48);
        }
    }

    function group()
    {
       $this->basicamt["amount"]=0;
     $this->pfapplicableamt["amount"]=0;
     $this->employeepfamt["amount"]=0;
     $this->employeepensionamt["amount"]=0;

        $this->totalgroupcount=1;
        $cond="1=1";
        $cond1=" p.financialyearcode=".$_SESSION['yearperiodcode'];  
        if ($this->monthcode!=0)
        {
            if ($cond=="")
                $cond="p.monthcode=".$this->monthcode;
            else
                $cond=$cond." and p.monthcode=".$this->monthcode;
        }
        if ($this->employeecategorycode!=0)
        {
            if ($cond=="")
                $cond="p.employeecategorycode=".$this->employeecategorycode;
            else
                $cond=$cond." and p.employeecategorycode=".$this->employeecategorycode;
        }
       
        $group_query_1 ="
        select 
        row_number() over( order by h.employeecode)sr_no
        ,h.employeecode
        ,e.employeenameuni
        ,h.basicdaamount
        ,h.pfableamount
        ,h.employerpfamount
        ,h.employerpensionamount
        from SALARYTRANSACTIONHEADER h,employeetransaction et
        ,salaryperiod p,employeemaster e
        where {$cond} and
        h.salaryperiodtransactionnumber=p.transactionnumber
        and h.employeecode=e.employeecode
        and h.employeecode=et.employeecode
        and p.employeecategorycode=et.employeecategorycode
        and h.employerpfamount>0
        order by sr_no,h.employeecode
       ";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        $this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
        
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
        $this->hline(10,195,$this->liney,'C'); 
        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox($group_row_1['SR_NO'],$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['EMPLOYEECODE'],$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(45);
        $this->textbox($group_row_1['EMPLOYEENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

       
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['BASICDAAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['PFABLEAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['EMPLOYERPFAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

       
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['EMPLOYERPENSIONAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->basicamt["amount"]+=$group_row_1['BASICDAAMOUNT'];
        $this->pfapplicableamt["amount"]+=$group_row_1['PFABLEAMOUNT'];
        $this->employeepfamt["amount"]+=$group_row_1['EMPLOYERPFAMOUNT'];
        $this->employeepensionamt["amount"]+=$group_row_1['EMPLOYERPENSIONAMOUNT'];


        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney,'C'); 
            $this->newpage(True);
         
        }   
        else
        {           
            $this->newrow();
           // $this->hline(10,125,$this->liney,'C'); 
        }
    }
    
    function groupfooter_1(&$group_row_1)
    { 
    }
    function groupfooter_2(&$group_row_1)
    {  
       
    }

    function groupfooter_3(&$group_row_3)
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

    function reportfooter()
    {
        $this->hline(10,195,$this->liney,'C'); 

        $this->setfieldwidth(45,50);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('एकूण ',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
     
        $this->setfieldwidth(25);    
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox(number_format($this->basicamt["amount"],0,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(25);    
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox(number_format($this->pfapplicableamt["amount"],0,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);    
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox(number_format($this->employeepfamt["amount"],0,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);    
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox(number_format($this->employeepensionamt["amount"],0,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->hline(50,195,$this->liney+7,'C');  
       
    }

}    
?>
