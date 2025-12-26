<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class grandfarmerpaysheetsummary extends swappreport
{
    public $circlecode;
    public $billperiodtransnumber;
    public $msubtitle;
    public $circlename;
    public $villagename;
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
        $pageheader_query_1="select BILLPERIODDESC(".$this->billperiodtransnumber.") as desct from dual t";
        $pageheader_result_1 = oci_parse($this->connection, $pageheader_query_1);
        $r = oci_execute($pageheader_result_1); 
        if ($pageheader_row_1 = oci_fetch_array($pageheader_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
         $this->msubtitle  =  $pageheader_row_1['DESCT'];
        }
        $this->newpage(true);
        $this->reportfooter();
    }

    function pageheader()
    {
        ob_flush();
        ob_start();
       
        $this->liney = 13;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->newrow(4);
        $this->setfieldwidth(190,0);
        $this->textbox('ऊस पेमेंट समरी',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->setfieldwidth(190,10);
        $this->textbox($this->msubtitle,$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->newrow();
        $this->hline(10,195,$this->liney,'C');
    }


    function pagefooter($islastpage=false)
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
//-------------------
         $reportfooter_query_1 ="select  
        sum(t.netcanetonnage) as netcanetonnage
        ,sum(t.grossamount) as grossamount
        ,sum(t.grossdeduction) as grossdeduction
        ,sum(t.netamount) as netamount
        from farmerbillheader t,farmer f, village v
        ,billperiodheader h,farmercategory c,circle r
        where t.farmercode=f.farmercode
        and f.villagecode=v.villagecode
        and t.billperiodtransnumber=h.billperiodtransnumber
        and f.farmercategorycode=c.farmercategorycode
        and v.circlecode=r.circlecode
        and t.billperiodtransnumber=".$this->billperiodtransnumber."  
         having nvl(sum(t.netcanetonnage),0)>0";
        
        $reportfooter_result_1 = oci_parse($this->connection, $reportfooter_query_1);
        $r = oci_execute($reportfooter_result_1);
        $lastcirclecode=0;
        if ($reportfooter_row_1 = oci_fetch_array($reportfooter_result_1,OCI_ASSOC+OCI_RETURN_NULLS))  
        {
            
            $this->setfieldwidth(15,10);
            $this->textbox('टनेज : ',$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11,'','','','B');
            $this->setfieldwidth(40);
            $this->textbox(number_format($reportfooter_row_1['NETCANETONNAGE'],3),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
            $this->newrow();
            $this->setfieldwidth(25,50);
            $this->textbox('एकूण रक्कम :',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
            $this->setfieldwidth(45);
            $this->textbox($reportfooter_row_1['GROSSAMOUNT'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'D'); 
            $this->setfieldwidth(50,10);
            $this->textbox('कपाती',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');   
            $this->newrow();
            $reportfooter_query_1_2 = "select tt.deductioncode
            ,dd.deductionnameuni
            ,sum(tt.deductionamount) as deductionamount
            from FARMERBILLDEDUCTIONDETAIL tt
            ,deduction dd
            ,bankbranch bb
            ,farmerbillheader t,farmer f, village v
            ,billperiodheader h,farmercategory c,circle r
            where tt.deductioncode=dd.deductioncode 
            and tt.branchcode=bb.bankbranchcode(+) 
            and t.transactionnumber=tt.billtransactionnumber
            and t.farmercode=f.farmercode
            and f.villagecode=v.villagecode
            and t.billperiodtransnumber=h.billperiodtransnumber
            and f.farmercategorycode=c.farmercategorycode
            and v.circlecode=r.circlecode
            and t.billperiodtransnumber=".$this->billperiodtransnumber."  
            group by tt.deductioncode
            ,dd.deductionnameuni
            order by tt.deductioncode
            ,dd.deductionnameuni";
            $reportfooter_result_1_2 = oci_parse($this->connection, $reportfooter_query_1_2);
            $r = oci_execute($reportfooter_result_1_2);
            $i=1;
            while ($reportfooter_row_1_2 = oci_fetch_array($reportfooter_result_1_2,OCI_ASSOC+OCI_RETURN_NULLS))  
            {
                $this->setfieldwidth(35,50);
                    $this->textbox($reportfooter_row_1_2['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                    $this->setfieldwidth(35);
                    $this->textbox($reportfooter_row_1_2['DEDUCTIONAMOUNT'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
                    $this->newrow();
            }
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->setfieldwidth(36,55);
            $this->textbox('एकूण कपात रक्कम:',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');   
            $this->setfieldwidth(35);
            $this->textbox($reportfooter_row_1['GROSSDEDUCTION'],$this->w,$this->x,'C','L',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(20,135);
            $this->textbox('देय रक्कम ',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','',''); 
            $this->setfieldwidth(35);  
            $this->textbox($reportfooter_row_1['NETAMOUNT'],$this->w,$this->x,'C','L',1,'SakalMarathiNormal922',11,'','','','');
            $this->newrow();
            $this->setfieldwidth(15,10);  
            $this->textbox('अक्षरी->',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
            $this->setfieldwidth(112,25);
            $this->textbox(NumberToWords(abs($reportfooter_row_1['NETAMOUNT']),1),$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');  
        }
//----------------------
    
    }

    function endreport()
    {

        $this->newrow(20);
        $this->setfieldwidth(20,60);
        $this->textbox('केन अकौंटंट',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('चीफ अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
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