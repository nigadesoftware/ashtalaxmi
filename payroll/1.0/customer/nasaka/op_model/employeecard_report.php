<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_p_card.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class employeecardlist extends reportbox
{	
    public $transactionnumber;
    public $certificatenumber;
  
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
        $this->liney = 20;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
       // $this->textbox($this->ftransactionnumber['FARMERCOUNT'],175,10,'S','C',1,'siddhanta',13);
       
        //$this->newrow(10);
        //$this->newrow(10);
       // $this->hline(10,100,$this->liney+6,'C');
        //$this->newrow(7);

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
        
        $group_query_1 ="
         select 
         e.nemp_code
        ,e.vemp_name
        ,e.vdesignation
        ,e.vdeprt_name 
        from a_emp_master e
        order by e.nemp_code";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=1;
        $this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            if ($i%2==1)
            $this->detail_1($group_row_1);
            else
            $this->detail_2($group_row_1);
           // $this->hline(10,100,$this->liney-10,'D'); 
            $last_row=$group_row_1;
            $i++;
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
      ///left  side
        $this->pdf->Image('../img/mdsign.jpg', 50, $this->liney+30, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $this->setfieldwidth(90,10);
        $this->hline(10,100,$this->liney-1,'C'); 
        $this->vline($this->liney-1,$this->liney+47,$this->x);
        $this->textbox('  NASHIK SAHAKARI SAKHAR KARKHANA LTD,PALASE',$this->w,$this->x,'S','L',1,'siddhanta',8); 
        $this->textbox('  NASHIK SAHAKARI SAKHAR KARKHANA LTD,PALASE',$this->w,$this->x,'S','L',1,'siddhanta',8); 
        $this->vline($this->liney-1,$this->liney+47,$this->x+$this->w);
              
        $this->newrow(4);
        $this->textbox('DWARA Ashtalaxmi Sugar Ethanol and Energy',$this->w,$this->x,'S','C',1,'siddhanta',9); 
        $this->textbox('DWARA Ashtalaxmi Sugar Ethanol and Energy',$this->w,$this->x,'S','C',1,'siddhanta',9); 
        $this->newrow(5);
        $this->hline(75,100,$this->liney,'C');
        $this->vline($this->liney,$this->liney+25,$this->x-25+$this->w);
        $this->newrow(10);
        $this->hline(75,100,$this->liney+15,'C');
        $this->textbox(' Name : '.$group_row_1['VEMP_NAME'],$this->w,$this->x,'S','L',1,'siddhanta',8); 
        $this->newrow(4);               
        $this->textbox(' Designation : '.$group_row_1['VDESIGNATION'],$this->w,$this->x,'S','L',1,'siddhanta',8); 
        $this->newrow(4);      
        $this->textbox(' Department  '.$group_row_1['VDEPRT_NAME'],$this->w,$this->x,'S','L',1,'siddhanta',8); 
       
        $this->hline(10,100,$this->liney+20,'C');
        $this->newrow(13);
        $this->setfieldwidth(90,38);
        $this->textbox('Managing Director ',$this->w,$this->x,'S','L',1,'siddhanta',8); 

        $this->newrow(14);
        
      //right side 


        if ($this->isnewpage(20))
        {
            $this->newpage(True);
        }   
        else
        {
           //$this->newrow(5);
 
        }
    }
    function detail_2(&$group_row_1)
    {
      
       
        //right side
        $this->newrow(-54);
        $this->pdf->Image('../img/mdsign.jpg', 150, $this->liney+30, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $this->setfieldwidth(90,110);
       /*  $this->hline(110,200,$this->liney-1,'C'); 
        $this->vline($this->liney-1,$this->liney+47,$this->x);
        $this->textbox('  NASHIK SAHAKARI SAKHAR KARKHANA LTD,PALASE',$this->w,$this->x,'S','L',1,'siddhanta',8); 
        $this->vline($this->liney-1,$this->liney+47,$this->x+$this->w);
 */
        $this->hline(110,200,$this->liney-1,'C'); 
        $this->vline($this->liney-1,$this->liney+47,$this->x);
        $this->textbox('  NASHIK SAHAKARI SAKHAR KARKHANA LTD,PALASE',$this->w,$this->x,'S','L',1,'siddhanta',8); 
        $this->textbox('  NASHIK SAHAKARI SAKHAR KARKHANA LTD,PALASE',$this->w,$this->x,'S','L',1,'siddhanta',8); 
        $this->vline($this->liney-1,$this->liney+47,$this->x+$this->w);
        $this->newrow(4);
        $this->textbox('DWARA Ashtalaxmi Sugar Ethanol and Energy',$this->w,$this->x,'S','C',1,'siddhanta',9); 
        $this->textbox('DWARA Ashtalaxmi Sugar Ethanol and Energy',$this->w,$this->x,'S','C',1,'siddhanta',9); 
        $this->newrow(5);
        $this->hline(175,200,$this->liney,'C');
        $this->vline($this->liney,$this->liney+25,$this->x-25+$this->w);
        $this->newrow(10);
        $this->hline(175,200,$this->liney+15,'C');
        $this->textbox(' Name : '.$group_row_1['VEMP_NAME'],$this->w,$this->x,'S','L',1,'siddhanta',8); 
        $this->newrow(4);               
        $this->textbox(' Designation : '.$group_row_1['VDESIGNATION'],$this->w,$this->x,'S','L',1,'siddhanta',8); 
        $this->newrow(4);      
        $this->textbox(' Department  '.$group_row_1['VDEPRT_NAME'],$this->w,$this->x,'S','L',1,'siddhanta',8); 
        $this->hline(110,200,$this->liney+20,'C');
        $this->newrow(13);
        $this->setfieldwidth(90,138);
        $this->textbox('Managing Director ',$this->w,$this->x,'S','L',1,'siddhanta',8); 

        $this->newrow(14);

      //right side 


        if ($this->isnewpage(25))
        {
            $this->newpage(True);
        }   
        else
        {
           $this->newrow(10);
 
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
       
            
            
    }

}    
?>
