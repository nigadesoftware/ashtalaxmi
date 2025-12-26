<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class indentlist extends reportbox
{	
    public $transactionnumber;
    public $ftransactionnumber;
    public $fissuedate;
    public $fissuenumber;
    public $fsection;
    public $femployee;
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
        $this->textbox('Indent Pass',175,10,'S','C',1,'siddhanta',13);
        $this->newrow(10);
        $this->hline(10,175,$this->liney+6,'C');
        $this->newrow(7);


        //$this->drawlines($limit);
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
        $this->totalgroupcount=1;
        $cond="1=1";
        if ($this->transactionnumber!=0)
        {
            if ($cond=="")
                $cond="h.transactionnumber=".$this->transactionnumber;
            else
                $cond=$cond." and h.transactionnumber=".$this->transactionnumber;
        }
      
        $group_query_1 ="select 
        h.transactionnumber,
        h.indentnumber,
        TO_CHAR(h.indentdate,'dd/MM/yyyy')issuedate,
        se.sectioncode,
        se.sectionnameeng,
        e.employeenameeng,
        d.serialnumber,
        d.itemcode,
        u.unitnameeng,
        i.itemnameeng,
        d.quantity,
        h.purpose
        from 
        indentheader h,indentitemdetail d,
        employee e,section se,
        item i,unit u
        where {$cond} and
        h.transactionnumber=d.transactionnumber
        and h.indentbyemployeecode=e.employeecode
        and h.indentbysectioncode=se.sectioncode
        and d.itemcode=i.itemcode
        and i.unitcode=u.unitcode
        order by h.transactionnumber,
         h.indentnumber,d.serialnumber";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        $this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->hline(10,175,$this->liney-1,'C'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
        
    }
    
    function groupheader_1(&$group_row_1)
    {
       
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,175,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
       $this->setfieldwidth(175,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Tran.No :'.$group_row_1['TRANSACTIONNUMBER'].' Indent Date : '.$group_row_1['INDENTNUMBER'].' Indent Date: '.$group_row_1['ISSUEDATE'],$this->w,$this->x,'S','L',1,'siddhanta',11); 
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w-10);
        $this->newrow();
     //   $this->hline(10,175,$this->liney,'D');

        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Section : '.$group_row_1['SECTIONNAMEENG'].'   Employee Name: '.$group_row_1['EMPLOYEENAMEENG'],$this->w,$this->x,'S','L',1,'siddhanta',11); 
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w-10);
        $this->newrow();
        $this->hline(10,175,$this->liney-1,'C');
        
        $this->setfieldwidth(20,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Sr.No.',$this->w,$this->x,'S','C',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Item Code',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(70);
        $this->textbox('Item Name',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Unit',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Issue Qty',$this->w,$this->x,'S','R',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
  
         $this->hline(10,175,$this->liney+6,'C');
        $this->newrow();

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
        
         
        $this->setfieldwidth(20,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($group_row_1['SERIALNUMBER'],$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['ITEMCODE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(70);
        $this->textbox($group_row_1['ITEMNAMEENG'],$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['UNITNAMEENG'],$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['QUANTITY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->ftransactionnumber['FARMERCOUNT']=$group_row_1['QUANTITY'];
       
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,175,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            //$this->hline(10,400,$this->liney-2,'C'); 
        }
    }
    
    function groupfooter_1(&$group_row_1)
    { 
        if ($this->isnewpage(30))
        {
            $this->newrow();
            $this->hline(10,175,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow(25);
            //$this->hline(10,400,$this->liney-2,'C'); 
        }
        $this->setfieldwidth(40,15);
        $this->textbox('Received by',$this->w,$this->x,'S','L',1,'siddhanta',9);
     

        $this->setfieldwidth(40);
        $this->textbox('Issued by',$this->w,$this->x,'S','L',1,'siddhanta',9);
      //  $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->textbox('Store clerk',$this->w,$this->x,'S','L',1,'siddhanta',9);
       // $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->textbox('Store Keeper',$this->w,$this->x,'S','L',1,'siddhanta',9);
       // $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

       if ($this->isnewpage(20))
       {
           $this->newrow();
           $this->hline(10,175,$this->liney-2,'C'); 
           $this->newpage(True);
       }   
       else
       {
          // $this->newrow();
          $this->newpage(True);
       }
       
 
    }
    function groupfooter_2(&$group_row_2)
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
