<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_l.php");
    include_once("../info/routine.php");
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class fertilizersale extends reportbox
{
    public $fromdate;
    public $todate;
    public $farmercode;
    public $msubtitle;
    public $msubtitle2;
    public $farmercategoryname;
    public $srno;
    public $salecategorycode;
    public $subcategorycode;
    public $salecategorysummary;
    public $centresummary;
    public $villagesummary;
    public $summary;
    public $circlecode;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7,$subject='',$pdffilename='',$papersize='A4',$orientation='L')
	{
        $this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $this->subject= $subject;
        $this->pdffilename= $pdffilename;
        $this->papersize=strtoupper($papersize);
        $this->orientation=strtoupper($orientation);
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueType', '', 32);
        // create new PDF document
	    $this->pdf = new MYPDF($this->orientation, PDF_UNIT, $this->papersize, true, 'UTF-8', false);
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
        $this->summary['SALEQUANTITY']=0;
        $this->summary['AMOUNT']=0;
        $this->totalgroupcount=0;
        $pageheader_query_1="select trim(t.subcategorynameuni) subcategorynameuni from goodssubcategory t where t.subcategorycode=".$this->subcategorycode;
        $pageheader_result_1 = oci_parse($this->connection, $pageheader_query_1);
        $r = oci_execute($pageheader_result_1); 
        if ($pageheader_row_1 = oci_fetch_array($pageheader_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
         $this->msubtitle  =  $pageheader_row_1['SUBCATEGORYNAMEUNI'].' विक्री यादी';
        }

        //$this->msubtitle  =  'विक्री यादी';
        $this->msubtitle2  ='कालावधी '.$this->fromdate.' ते '.$this->todate;
        $this->newpage(true);
        $this->group();
    }

    function pageheader()
    {
        ob_flush();
        ob_start();
        
        $this->liney = 13;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->newrow(4);
        $this->setfieldwidth(270,10);
        $this->textbox($this->msubtitle,$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->setfieldwidth(270,10);
        $this->textbox($this->msubtitle2,$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->newrow();
        $this->hline(10,270,$this->liney,'C');
        $this->setfieldwidth(35,10);
        $this->textbox('बिल नं.',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('बिल दिनांक.',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        //$this->setfieldwidth(15);
        //$this->textbox('कोड',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox('कोड नं',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(65);
        $this->textbox('सभासदाचे नाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('गाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(35);
        $this->textbox('तपशील',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(15);
        $this->textbox('संख्या',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(15);
        $this->textbox('दर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        
        $this->newrow();
        $this->hline(10,270,$this->liney,'D');
    }

    function group()
    {
        $this->totalgroupcount=3;
        $cond='h.yearperiodcode='.$_SESSION['yearperiodcode']." and g.subcategorycode=".$this->subcategorycode;
        if ($this->fromdate!='' and $this->todate!='')
        {
            $frdt=DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $trdt=DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            if ($cond!='')
            $cond=$cond." and h.invoicedate>='".$frdt."' and h.invoicedate<='".$trdt."'";
            else
            $cond="h.invoicedate>='".$frdt."' and h.invoicedate<='".$trdt."'";
        }
        if ($this->salecategorycode!='0')
        {
            if ($cond!='')
                $cond=$cond." and h.salecategorycode=".$this->salecategorycode;
            else
                $cond="h.salecategorycode=".$this->salecategorycode;
        }
        if ($this->centrecode!='0')
        {
            if ($cond!='')
                $cond=$cond." and r.centrecode=".$this->centrecode;
            else
                $cond="r.centrecode=".$this->circleccentrecodeode;
        }
        
            $group_query_1 ="select 
            h.salecategorycode
            ,v.centrecode
            ,v.villagecode
            ,h.invoicenumberpresuf
            ,h.invoicedate
            ,h.purchasercode
            ,f.farmercode
            ,f.farmernameuni
            ,v.villagenameuni
            ,mv.villagenameuni farmervillagenameuni
            ,r.centrenameuni
            ,d.finishedgoodscode
            ,g.finishedgoodsnameuni
            ,c.salecategorynameeng
            ,c.salecategorynameuni
            ,d.salequantity
            ,d.salerate
            ,d.amount
            from saleinvoicedetail d
            ,saleinvoiceheader h
            ,salecategory c
            ,nst_nasaka_agriculture.farmer f
            ,nst_nasaka_agriculture.village v
            ,finishedgoods g
            ,nst_nasaka_agriculture.centre r
            ,nst_nasaka_agriculture.plantationheader p
            ,nst_nasaka_agriculture.village mv
            where h.transactionnumber=d.transactionnumber(+)
            and h.salecategorycode=c.salecategorycode(+)
            and h.purchasercode=f.farmercode(+)
            and p.villagecode=v.villagecode(+)
            and d.finishedgoodscode=g.finishedgoodscode(+)
            and v.centrecode=r.centrecode(+)
            and h.crushingseasonyear=p.seasoncode(+)
            and h.plotnumber=p.plotnumber(+)
            and f.villagecode=mv.villagecode
            and ".$cond."
            order by v.centrecode,v.villagecode,invoicedate,invoicenumber";
       
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);              
            $this->hline(10,270,$this->liney,'D'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
        
    }
    
    function groupheader_1(&$group_row)
    {
        $this->salecategorysummary['SALEQUANTITY']=0;
        $this->salecategorysummary['AMOUNT']=0;
        
        $this->setfieldwidth(80,10);
        $this->textbox('विक्री प्रकार - '.$group_row['SALECATEGORYNAMEUNI'],$this->w,$this->x,'N','L',1,'Siddhanta',11,'','','','');
        $this->newrow();
        $this->hline(10,270,$this->liney-2,'C'); 
    }

    function groupheader_2(&$group_row)
    {
        $this->centresummary['SALEQUANTITY']=0;
        $this->centresummary['AMOUNT']=0;
        
        $this->setfieldwidth(80,10);
        $this->textbox('सेंटर - '.$group_row['CENTRENAMEUNI'],$this->w,$this->x,'N','L',1,'Siddhanta',11,'','','','');
        $this->newrow();
        $this->hline(10,270,$this->liney-2,'C'); 
  
    }
    function groupheader_3(&$group_row)
    {
        $this->villagesummary['SALEQUANTITY']=0;
        $this->villagesummary['AMOUNT']=0;

        $this->setfieldwidth(80,10);
        $this->textbox('शिवार - '.$group_row['VILLAGENAMEUNI'],$this->w,$this->x,'N','L',1,'Siddhanta',11,'','','','');
        $this->newrow();
        $this->hline(10,270,$this->liney-2,'C'); 
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
        
        $dt=DateTime::createFromFormat('d-M-y',$group_row_1['INVOICEDATE'])->format('d/m/Y');
        //$this->hline(10,270,$this->liney-2,'D'); 
        $this->setfieldwidth(35,10);
        $this->textbox($group_row_1['INVOICENUMBERPRESUF'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($dt,$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
        //$this->setfieldwidth(15);
        //$this->textbox($group_row_1['PURCHASERCODE'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['FARMERCODE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(65);
        $this->textbox($group_row_1['FARMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['FARMERVILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(35);
        $this->textbox($group_row_1['FINISHEDGOODSNAMEUNI'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['SALEQUANTITY'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['SALERATE'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['AMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->salecategorysummary['SALEQUANTITY']=$this->salecategorysummary['SALEQUANTITY']+$group_row_1['SALEQUANTITY'];
        $this->salecategorysummary['AMOUNT']=$this->salecategorysummary['AMOUNT']+$group_row_1['AMOUNT'];
        $this->villagesummary['SALEQUANTITY']=$this->villagesummary['SALEQUANTITY']+$group_row_1['SALEQUANTITY'];
        $this->villagesummary['AMOUNT']=$this->villagesummary['AMOUNT']+$group_row_1['AMOUNT'];
        $this->centresummary['SALEQUANTITY']=$this->centresummary['SALEQUANTITY']+$group_row_1['SALEQUANTITY'];
        $this->centresummary['AMOUNT']=$this->centresummary['AMOUNT']+$group_row_1['AMOUNT'];
        $this->summary['SALEQUANTITY']=$this->summary['SALEQUANTITY']+$group_row_1['SALEQUANTITY'];
        $this->summary['AMOUNT']=$this->summary['AMOUNT']+$group_row_1['AMOUNT'];
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,270,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
        }
    }

    function groupfooter_1(&$last_row)
    {
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,270,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(10,270,$this->liney-2,'C');
        }
        $this->setfieldwidth(35,10);
        $this->setfieldwidth(25);
        $this->setfieldwidth(75);
        $this->setfieldwidth(35);
        $this->textbox($last_row['SALECATEGORYNAMEUNI'].' एकुण ',$this->w,$this->x,'S','L',1,'Siddhanta',10,'','','','');
        $this->setfieldwidth(35);
        $this->setfieldwidth(15);
        $this->textbox($this->salecategorysummary['SALEQUANTITY'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',8,'','','','');
        $this->setfieldwidth(15);
        $this->setfieldwidth(25);
        $this->textbox($this->salecategorysummary['AMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,270,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else if ($this->islastpage==0)
        {
            $this->newrow();
            $this->hline(10,270,$this->liney-2,'C');
            $this->newpage(True);
        }
    }
    function groupfooter_2(&$last_row)
    {    
        $this->setfieldwidth(35,10);
        $this->setfieldwidth(25);
        $this->setfieldwidth(75);
        $this->setfieldwidth(35);
        $this->textbox($last_row['CENTRENAMEUNI'].' सेंटर एकुण ',$this->w,$this->x,'S','L',1,'Siddhanta',10,'','','','');
        $this->setfieldwidth(35);
        $this->setfieldwidth(15);
        $this->textbox($this->centresummary['SALEQUANTITY'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',8,'','','','');
        $this->setfieldwidth(15);
        $this->setfieldwidth(25);
        $this->textbox($this->centresummary['AMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,270,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else if ($this->islastpage==0 and $this->subcategorycode!=3)
        {
            $this->newrow();
            $this->hline(10,270,$this->liney-2,'C');
            $this->newpage(True);
        }
    }
    function groupfooter_3(&$last_row)
    { 
        $this->hline(10,270,$this->liney,'C'); 
        $this->setfieldwidth(35,10);
        $this->setfieldwidth(25);
        $this->setfieldwidth(75);
        $this->setfieldwidth(35);
        $this->textbox($last_row['VILLAGENAMEUNI'].' गांव एकुण ',$this->w,$this->x,'S','L',1,'Siddhanta',10,'','','','');
        $this->setfieldwidth(35);
        $this->setfieldwidth(15);
        $this->textbox($this->villagesummary['SALEQUANTITY'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',8,'','','','');
        $this->setfieldwidth(15);
        $this->setfieldwidth(25);
        $this->textbox($this->villagesummary['AMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,270,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(10,270,$this->liney-2,'C');
        }     
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
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,270,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(10,270,$this->liney-2,'C');
        }
        $this->setfieldwidth(35,10);
        $this->setfieldwidth(25);
        $this->setfieldwidth(75);
        $this->setfieldwidth(35);
        $this->textbox('एकुण ',$this->w,$this->x,'S','L',1,'Siddhanta',10,'','','','');
        $this->setfieldwidth(30);
        $this->setfieldwidth(21);
        $this->textbox($this->summary['SALEQUANTITY'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(15);
        $this->setfieldwidth(24);
        $this->textbox($this->summary['AMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,270,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(10,270,$this->liney-2,'C'); 
            $this->newrow(13);
        }
        $this->setfieldwidth(20,70);
        $this->textbox('क्लार्क',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(90);
        $this->textbox('ऊस विकास अधिकारी ',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');

          
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