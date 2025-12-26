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
class farmertypenetpaymentsummary extends reportbox
{
    public $billperiodtransnumber;
    public $msubtitle;
    public $summary;
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
        $this->totalgroupcount=0;
        $pageheader_query_1="select BILLPERIODDESC(".$this->billperiodtransnumber.") as desct from dual t";
        $pageheader_result_1 = oci_parse($this->connection, $pageheader_query_1);
        $r = oci_execute($pageheader_result_1); 
        if ($pageheader_row_1 = oci_fetch_array($pageheader_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
         $this->msubtitle  =  $pageheader_row_1['DESCT'];
        }
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
        $this->setfieldwidth(200,0);
        $this->textbox('बॅंक भरणा यादी',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','B');
        $this->newrow();
        $this->setfieldwidth(200,10);
        $this->textbox($this->msubtitle,$this->w,$this->x,'S','C',1,'siddhanta',8,'','','','');
        $this->newrow();
        $this->hline(10,200,$this->liney,'C');
        $this->setfieldwidth(40,10);
        $this->textbox('तपशिल',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(35);
        $this->textbox('एकूण रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(35);
        $this->textbox('वजा अनपेड',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(35);
        $this->textbox('जिल्हा बॅंक रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(40);
        $this->textbox('राष्ट्रीयकृत बॅंक रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
        $this->newrow();
        $this->hline(10,200,$this->liney,'D');
    }

    function group()
    {
        $this->totalgroupcount=0;
        $this->summary['NETAMOUNT']=0;
        $this->summary['UNPAIDCHEQUE']=0;
        $this->summary['DCCPAYMENT']=0;
        $this->summary['NBPAYMENT']=0;
        $group_query_1 ="select 
        farmercategorycode
        ,farmercategorynameuni
        ,nvl(sum(netamount),0) netamount
        ,nvl(sum(unpaidcheque),0) unpaidcheque
        ,nvl(sum(dccpayment),0) dccpayment
        ,nvl(sum(nbpayment),0) nbpayment
        from
        (select 
        t.farmercategorycode
        ,y.farmercategorynameuni
        ,t.netamount
        ,case when t.bankbranchcode=45 then t.netamount end unpaidcheque
        ,case when r.bankcategorycode=4 then t.netamount end dccpayment
        ,case when r.bankcategorycode<>4 and t.bankbranchcode<>45 then t.netamount end nbpayment
        from farmerbillheader t,farmerwithadhikarpatra f, village v
        ,billperiodheader h,bankbranch b, bank c,Bankcategory r,farmercategory y
        where t.farmercode=f.farmercode
        and h.seasonyear=f.seasoncode
        and f.villagecode=v.villagecode
        and t.farmercategorycode=y.farmercategorycode
        and t.billperiodtransnumber=h.billperiodtransnumber
        and c.bankcategorycode=r.bankcategorycode
        and b.bankcode=c.bankcode
        and t.bankbranchcode=b.bankbranchcode(+)
        and nvl(t.netamount,0)>0
        and t.billperiodtransnumber=".$this->billperiodtransnumber.")t  
        group by farmercategorycode,farmercategorynameuni
        order by farmercategorycode,farmercategorynameuni";
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);              
            $this->hline(10,200,$this->liney,'D'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
    }
    
    function groupheader_1(&$group_row)
    {
        
        $this->newpage(true);
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
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,395,$this->liney-2,'C'); 
        }
        $this->setfieldwidth(40,10);
        $this->textbox($group_row_1['FARMERCATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(35);
        $this->textbox($group_row_1['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(35);
        $this->textbox($group_row_1['UNPAIDCHEQUE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(35);
        $this->textbox($group_row_1['DCCPAYMENT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(35);
        $this->textbox($group_row_1['NBPAYMENT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow();
        $this->summary['NETAMOUNT']=$this->summary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        $this->summary['UNPAIDCHEQUE']=$this->summary['UNPAIDCHEQUE']+$group_row_1['UNPAIDCHEQUE'];
        $this->summary['DCCPAYMENT']=$this->summary['DCCPAYMENT']+$group_row_1['DCCPAYMENT'];
        $this->summary['NBPAYMENT']=$this->summary['NBPAYMENT']+$group_row_1['NBPAYMENT'];
    }


    function groupfooter_1(&$last_row)
    {      
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

        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,395,$this->liney-2,'C'); 
        }
        $this->setfieldwidth(40,10);
        $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(35);
        $this->textbox($this->summary['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(35);
        $this->textbox($this->summary['UNPAIDCHEQUE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(35);
        $this->textbox($this->summary['DCCPAYMENT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(35);
        $this->textbox($this->summary['NBPAYMENT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow();

        $this->newrow(20);
        $this->setfieldwidth(40,20);
        $this->textbox('क्लर्क',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(40);
        $this->textbox('केन अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(40);
        $this->textbox('चिफ अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(40);
        $this->textbox('जनरल मॅनेजर',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');

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