<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class sugarcaneseed extends reportbox
{
    public $from_date;
    public $to_date;
    public $farmercode;
    public $msubtitle;
    public $msubtitle2;
    public $farmercategoryname;
    public $srno;
    public $group_summary_1;
    public $report_summary_1;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7,$subject='',$pdffilename='',$papersize='A4',$orientation='P')
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
        $this->totalgroupcount=1;
        $pageheader_query_1="select trim(t.salecategorynameuni) salecategorynameuni from SALECATEGORY t where t.salecategorycode=1";
        $pageheader_result_1 = oci_parse($this->connection, $pageheader_query_1);
        $r = oci_execute($pageheader_result_1); 
        if ($pageheader_row_1 = oci_fetch_array($pageheader_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
         $this->msubtitle  =  $pageheader_row_1['SALECATEGORYNAMEUNI'].' बेणे वाटप आहवाल';
        }
        $this->msubtitle2  ='कालावधी '.$this->from_date.' ते '.$this->to_date;
        $this->reportheader();        
        $this->newpage(true);
        $this->group();
    }
    function reportheader()
    {
        $this->report_summary_1['QUANTITY']=0;
        $this->report_summary_1['AMOUNT']=0;    
    }
    function pageheader()
    {
        ob_flush();
        ob_start();
        
        $this->liney = 13;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->newrow(4);
        $this->setfieldwidth(195,10);
        $this->textbox($this->msubtitle,$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->setfieldwidth(195,10);
        $this->textbox($this->msubtitle2,$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->newrow();
        $this->hline(10,195,$this->liney,'C');
        $this->setfieldwidth(15,10);
        $this->textbox('अ,नं.',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(80);
        $this->textbox('सभासदाचे नाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('गाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(15);
        $this->textbox('संख्या',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(15);
        $this->textbox('दर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->hline(10,195,$this->liney,'D');
    }
    
    function group()
    {
        $this->totalgroupcount=1;
        $frdt=DateTime::createFromFormat('d/m/Y',$this->from_date)->format('d-M-Y');
        $trdt=DateTime::createFromFormat('d/m/Y',$this->to_date)->format('d-M-Y');

            $group_query_1 ="select 
            h.transactionnumber
            ,h.invoicenumberpresuf
            ,h.invoicedate
            ,f2.farmercode||'-'||trim(f2.farmernameuni) farmernameuni_h
            ,v2.villagenameuni villagenameuni_h
            ,d.serialnumber
            ,d.farmercode||'-'||trim(f.farmernameuni) farmernameuni
            ,v.villagenameuni
            ,d.quantity
            ,d.salerate
            ,d.amount 
            from saleinvoicecaneseeddetail d,saleinvoiceheader h,nst_nasaka_agriculture.farmer f,nst_nasaka_agriculture.farmer f2
            ,nst_nasaka_agriculture.village v ,nst_nasaka_agriculture.village v2
            where d.transactionnumber=h.transactionnumber
            and h.salecategorycode=3 
            and h.purchaserate>0
            and d.farmercode=f.farmercode
            and h.purchasercode=f2.farmercode
            and f2.villagecode=v2.villagecode
            and f.villagecode=v.villagecode
            and h.invoicedate>='".$frdt."'
            and h.invoicedate<='".$trdt."' 
            order by h.transactionnumber
            ,d.serialnumber";
       
       
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);              
            //$this->hline(10,195,$this->liney,'D'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
        
    }
    
    function groupheader_1(&$group_row)
    {  
        //$this->hline(10,195,$this->liney,'D');
        $this->group_summary_1['QUANTITY']=0;
        $this->group_summary_1['AMOUNT']=0;
        $this->setfieldwidth(30,10);
        $this->textbox($group_row['INVOICENUMBERPRESUF'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox($group_row['INVOICEDATE'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        //$this->newrow();
        $this->setfieldwidth(80);
        $this->textbox($group_row['FARMERNAMEUNI_H'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox($group_row['VILLAGENAMEUNI_H'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        //$this->hline(10,195,$this->liney,'D');
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
        }
           
    }

    function groupheader_2(&$group_row)
    {
  
    }
    function groupheader_3(&$group_row)
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
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
        }
        //$this->hline(10,195,$this->liney-2,'D'); 
        $this->setfieldwidth(15,10);
        $this->textbox($group_row_1['SERIALNUMBER'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(80);
        $this->textbox($group_row_1['FARMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['QUANTITY'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['SALERATE'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['AMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->group_summary_1['QUANTITY']=  $this->group_summary_1['QUANTITY']+$group_row_1['QUANTITY'];
        $this->group_summary_1['AMOUNT']=  $this->group_summary_1['AMOUNT']+$group_row_1['AMOUNT'];
        $this->report_summary_1['QUANTITY']=  $this->report_summary_1['QUANTITY']+$group_row_1['QUANTITY'];
        $this->report_summary_1['AMOUNT']=  $this->report_summary_1['AMOUNT']+$group_row_1['AMOUNT'];
        $this->newrow();
    }

    function groupfooter_1(&$last_row)
    {
        $this->hline(10,195,$this->liney,'D'); 
        $this->setfieldwidth(30,30);
        $this->textbox('बिल एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30,115);
        $this->textbox($this->group_summary_1['QUANTITY'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(35,150);
        $this->textbox($this->group_summary_1['AMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow();
        $this->hline(10,195,$this->liney,'D'); 
    }
    function groupfooter_2(&$last_row)
    {    
    }
    function groupfooter_3(&$last_row)
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
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
        }
        $this->hline(10,195,$this->liney,'D'); 
        $this->setfieldwidth(30,30);
        $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30,115);
        $this->textbox($this->report_summary_1['QUANTITY'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(35,150);
        $this->textbox($this->report_summary_1['AMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow();
        $this->hline(10,195,$this->liney,'D');
        $this->newrow(10);
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
        $this->pdf->Output($this->pdffilename.'.pdf', 'I');
    }
}    
?>