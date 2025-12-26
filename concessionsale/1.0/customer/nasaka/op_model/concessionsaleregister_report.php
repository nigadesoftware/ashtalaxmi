<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class concessionsaleregister extends reportbox
{
    public $fromdate;
    public $todate;
    public $seasoncode;
    public $customercategorysummary;
    public $summary;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7,$subject='',$pdffilename='',$papersize='A4',$orientation='P')
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $this->pdffilename= $pdffilename;
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
        $this->pdf->SetSubject('Sugar Allotment Register');
        $this->pdf->SetKeywords('SUGALTREG_000.MR');
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
        $this->newpage(True);
        $this->group();
        $this->reportfooter();
    }

	function pageheader()
    {
        //$this->pdf->Image("../img/kadwawatermark.png", 60, 35, 70, 70, '', '', '', false, 300, '', false, false, 0);
        $this->pdf->SetFont('helvetica', 'I', 8);
        //$this->pdf->Text(170, 40, $_SESSION['yearperiodcode'].str_pad($this->farmercode, 5, '0', STR_PAD_LEFT));
        $this->liney = 15;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->textbox('सभासद साखर विक्री रजिस्टर',180,10,'S','C',1,'siddhanta',12);
        $this->newrow(7);
        $this->textbox('वाटप वर्ष : '.$_SESSION['yearperiodcode'].' दिनांक '.$this->fromdate.' पासून दिनांक '.$this->todate.' पर्यंत',180,10,'S','C',1,'siddhanta',11);
        $this->newrow(7);
        $this->hline(10,300,$this->liney,'C');
        $this->setfieldwidth(25,10);
        $this->textbox('रोख मेमो नं',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox('कोड',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(50);
        $this->textbox('नाव',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox('दिलेली साखर',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox('साखर रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','Y');
        $this->setfieldwidth(20);
        $this->textbox('बारदाणा संख्या',$this->w,$this->x,'S','R',1,'siddhanta',11,'','Y');
        $this->setfieldwidth(25);
        $this->textbox('बारदाणा रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','Y');
        $this->newrow(10);
        $this->hline(10,300,$this->liney,'C');
        if ($this->villagename!='')
        {
            $this->textbox('गाव : '.$this->villagename,180,20,'S','L',1,'siddhanta',11);
            $this->newrow(7);
            $this->hline(10,300,$this->liney,'D');
        }
    }
   

    function group()
    {
        $this->totalgroupcount=1;

        $cond = "t.yearcode=".$_SESSION['yearperiodcode'];
        if ($this->fromdate!='')
        {
            $frdate = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $cond = $cond." and t.invoicedate>='".$frdate."'"; 
        }
        
        if ($this->todate!='')
        {
            $trdate = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $cond = $cond." and t.invoicedate<='".$trdate."'"; 
        }

        
        $this->summary['QUANTITY']=0;
        $this->summary['AMOUNT']=0;
        $this->summary['BAGQUANTITY']=0;
        $this->summary['BAGAMOUNT']=0;

        
        

        $group_query_1 = "select t.customercategorycode,t.invoicenumberprefix,y.customercategorynameuni,c.refcode,c.customernameuni,d.quantity,d.amount,d.bagquantity,d.bagquantity*d.bagrate bagamount
        from CONCESSIONSALEHEADER t,CONCESSIONSALEDETAIL d,customer c,customercategory y
        where t.transactionnumber=d.transactionnumber
        and t.customercode=c.customercode 
        and c.customercategorycode=y.customercategorycode and {$cond}
        order by customercategorycode,invoicenumber";
        
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
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newpage(True);
        }
        $this->customercategorysummary['QUANTITY']=0;
        $this->customercategorysummary['AMOUNT']=0;
        $this->customercategorysummary['BAGQUANTITY']=0;
        $this->customercategorysummary['BAGAMOUNT']=0;

        $this->textbox($group_row_1['CUSTOMERCATEGORYNAMEUNI'],180,10,'S','L',1,'siddhanta',11);
        $this->newrow(7);
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
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
            //$this->newpage(True);
        }
        $this->setfieldwidth(25,10);
        $this->textbox($group_row_1['INVOICENUMBERPREFIX'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['REFCODE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(50);
        $this->textbox($group_row_1['CUSTOMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['QUANTITY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['AMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','Y');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['BAGQUANTITY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','Y');
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['BAGAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','Y');
        $this->newrow();


        $this->customercategorysummary['QUANTITY']=$this->customercategorysummary['QUANTITY']+$group_row_1['QUANTITY'];
        $this->customercategorysummary['AMOUNT']=$this->customercategorysummary['AMOUNT']+$group_row_1['AMOUNT'];
        $this->customercategorysummary['BAGQUANTITY']=$this->customercategorysummary['BAGQUANTITY']+$group_row_1['BAGQUANTITY'];
        $this->customercategorysummary['BAGAMOUNT']=$this->customercategorysummary['BAGAMOUNT']+$group_row_1['BAGAMOUNT'];

        
        $this->summary['QUANTITY']=$this->summary['QUANTITY']+$group_row_1['QUANTITY'];
        $this->summary['AMOUNT']=$this->summary['AMOUNT']+$group_row_1['AMOUNT'];
        $this->summary['BAGQUANTITY']=$this->summary['BAGQUANTITY']+$group_row_1['BAGQUANTITY'];
        $this->summary['BAGAMOUNT']=$this->summary['BAGAMOUNT']+$group_row_1['BAGAMOUNT'];
        
        
        if ($this->isnewpage(15))
        {
            //$this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            $this->hline(10,300,$this->liney,'D'); 
        }
    }


    function groupfooter_1(&$group_row_1)
    {     
        if ($this->isnewpage(15))
        {
            //$this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            $this->hline(10,300,$this->liney,'C'); 
        }
        $this->setfieldwidth(25,10);
        $this->setfieldwidth(20);
        $this->setfieldwidth(50);
        $this->textbox('एकूण',$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(25);
        $this->textbox($this->customercategorysummary['QUANTITY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(25);
        $this->textbox($this->customercategorysummary['AMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','Y');
        $this->setfieldwidth(20);
        $this->textbox($this->customercategorysummary['BAGQUANTITY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','Y');
        $this->setfieldwidth(25);
        $this->textbox($this->customercategorysummary['BAGAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','Y');
        $this->newrow();

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
        if ($this->isnewpage(15))
        {
            //$this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            $this->hline(10,300,$this->liney,'C'); 
        }
        $this->setfieldwidth(25,10);
        $this->setfieldwidth(20);
        $this->setfieldwidth(50);
        $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(25);
        $this->textbox($this->summary['QUANTITY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(25);
        $this->textbox($this->summary['AMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','Y');
        $this->setfieldwidth(20);
        $this->textbox($this->summary['BAGQUANTITY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','Y');
        $this->setfieldwidth(25);
        $this->textbox($this->summary['BAGAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','Y');
        $this->newrow();
        $this->hline(10,300,$this->liney,'C');
        $this->setfieldwidth(25,10);
        $this->setfieldwidth(20);
        $this->setfieldwidth(50);
        $this->textbox('एकूण भरणा रक्कम -',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox($this->summary['AMOUNT']+$this->summary['BAGAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',12,'','Y','','B');
        $this->setfieldwidth(20);
        $this->setfieldwidth(25);
        $this->newrow();

        $this->hline(10,300,$this->liney,'C');
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