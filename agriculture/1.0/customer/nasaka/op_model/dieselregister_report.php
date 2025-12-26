<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");


class dieselregister extends reportbox
{	
    public $dieselsmry;
   
    public $fromdate;
    public $todate;
   
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueType', '', 32);
    	
	    $this->pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        
      	
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
       
    }
    function endreport()
    {
        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output('DLRG_000.pdf', 'I');
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
        $this->textbox('डीझेल रजिस्टर',180,10,'S','C',1,'siddhanta',13);
        $this->newrow(7);
        
        if ($this->fromdate!='' and $this->todate!='')
        {
            $this->newrow(7);
            $this->textbox('दिनांक '.$this->fromdate.' पासून दिनांक '.$this->todate.' पर्यंत',150,50,'S','L',1,'siddhanta',12);
        }

        $this->newrow();
        
        $this->setfieldwidth(15,10);   
        $this->vline($this->liney-1,$this->liney+6,$this->x);   
        $this->textbox('अनु क्र.',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('तारीख',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('स्लीप नंबर',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('वाहन प्रकार.',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);


        $this->setfieldwidth(30);
        $this->textbox('वाहन नंबर',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(15);
        $this->textbox('कोड',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

      
        $this->setfieldwidth(40);
        $this->textbox('वाहतूकदार नाव',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

               
        $this->setfieldwidth(15);
        $this->textbox('अंतर',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);


        $this->setfieldwidth(15);
        $this->textbox('डीझेल',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->newrow(-7);
         $this->hline(10,195,$this->liney+6,'C');
         $this->newrow(7);
        $this->hline(10,195,$this->liney+6,'D');
         $this->newrow(7);
      
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
        $this->dieselsmry['diesel']=0;
        if ($this->fromdate!='' and $this->todate!='')
        {
            $frdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $todt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $cond=$cond." and weighmentdate>='".$frdt."' and weighmentdate<='".$todt."'";
        }
      
        $group_query_1 =" 
        select 
        row_number()over(order by w.weightslipnumber)Sr_no
        ,w.weightslipnumber
        ,TO_CHAR(w.weighmentdate,'dd/MM/yyyy')weighmentdate
        ,f.vehiclecategorycode
        ,c.vehiclecategorynameuni
        ,v.vehiclenumber
        ,s.subcontractorcode
        ,s.subcontractornameuni
        ,w.dieseldistance
        ,w.dieselquantity 
        from WEIGHTSLIP w,fieldslip f
        ,vehicle v,vehiclecategory c
        ,subcontractor s
        where w.seasoncode=f.seasoncode
        and w.fieldslipnumber=f.fieldslipnumber
        and f.seasoncode=v.seasoncode
        and f.vehiclecode=v.vehiclecode
        and v.seasoncode=s.seasoncode(+)
        and v.subcontractorcode=s.subcontractorcode(+)
        and f.vehiclecategorycode=c.vehiclecategorycode
        and w.dieselquantity>0
        {$cond} 
        order by w.weightslipnumber   
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
    function groupheader_4(&$group_row_1)
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
        $this->textbox($group_row_1['SR_NO'],$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['WEIGHMENTDATE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['WEIGHTSLIPNUMBER'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

       
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['VEHICLECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
//
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['VEHICLENUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['SUBCONTRACTORCODE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->textbox($group_row_1['SUBCONTRACTORNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['DIESELDISTANCE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['DIESELQUANTITY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

       
              
        $this->dieselsmry['diesel']+=$group_row_1['DIESELQUANTITY'];
      

        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney,'D'); 
            $this->newpage(True);
         
        }   
        else
        {
            $this->newrow();
           $this->hline(10,195,$this->liney,'D'); 
        }
    }
    
    function groupfooter_1(&$group_row_1)
    { 
       
    }
    function groupfooter_2(&$group_row_1)
    {  
    }

    function groupfooter_3(&$group_row_1)
    {     
    }
    function groupfooter_4(&$group_row_1)
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
        $this->setfieldwidth(24,150);
        $this->textbox('एकूण डीझेल',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(24,170);  
        $this->vline($this->liney-1,$this->liney+5,$this->x-45);     
        $this->textbox($this->dieselsmry['diesel'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-1,$this->liney+5,$this->x+$this->w+1);
        $this->newrow();
        $this->hline(125,195,$this->liney-2,'C'); 
      
    }

}    
?>
