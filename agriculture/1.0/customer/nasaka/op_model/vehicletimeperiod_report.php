<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_p_ht.php");
    include_once("../info/routine.php");
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class vehicletimeperiod extends reportbox
{
    public $fromdate;
    public $todate;
    public $circlecode;
    public $vehiclecategorycode;
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
        $this->group();
    }

    function pageheader()
    {
        ob_flush();
        ob_start();
       
        $this->liney = 13;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->newrow(4);
        $this->setfieldwidth(190,10);
        $this->textbox('वाहन वेळ यादी',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->setfieldwidth(195,10);
        
        $this->newrow();
        $this->hline(10,195,$this->liney,'C');
        $this->setfieldwidth(30,10);
        $this->textbox('गटाचे नाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox('वाहन प्रकार',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(35);
        $this->textbox('वाहन नंबर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(35);
        $this->textbox('गेटपास तारीख',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow(4);
        $this->textbox('यार्ड आ.तारीख',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow(-4);
        $this->setfieldwidth(35);
        $this->textbox('भर तारीख',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow(4);
        $this->textbox('खाली तारीख',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow(-4);
        $this->setfieldwidth(20);
        $this->textbox('टनेज मे.ट.',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->newrow(10);
        $this->hline(10,195,$this->liney,'D');
    }

    function group()
    {
        $this->newpage(True);
        $this->totalgroupcount=0;
        /* if ($this->dedcode!=0 )
        {
            $cond = " and d.deductioncode=".$this->dedcode;
        } */
        $fdt=DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
        $tdt=DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
        if ($this->fromdate !='' and $this->todate !='')
        {
            $cond = " and trunc(weighmentdate)>='".$fdt."' and trunc(weighmentdate)<='".$tdt."'";
        }
        if ($this->vehiclecategorycode !=0)
        {
            $cond = $cond." and vc.vehiclecategorycode=".$this->vehiclecategorycode;
        }
        if ($this->circlecode !=0)
        {
            $cond = $cond." and c.circlecode=".$this->circlecode;
        }

        $group_query_1 ="
        select c.circlenameuni,vc.vehiclecategorynameuni,v.vehiclenumber,r.tyregadinumber,t.weightslipnumber,t.weighmentdate,t.netweight
        ,TO_CHAR(f.fieldslipdate, 'DD/MM/YYYY HH24:MI:SS') fieldslipdate
        ,TO_CHAR(k.tokendate, 'DD/MM/YYYY HH24:MI:SS') tokendate
        ,TO_CHAR(t.loaddatetime, 'DD/MM/YYYY HH24:MI:SS') loaddatetime
        ,TO_CHAR(t.emptydatetime, 'DD/MM/YYYY HH24:MI:SS') emptydatetime
        ,case when f.fieldslipdate is not null and k.tokendate is not null then 
        round(24 * (k.tokendate - f.fieldslipdate),1) else 0 end tokenhours
        ,case when f.fieldslipdate is not null and t.loaddatetime is not null then 
        round(24 * (t.loaddatetime - k.tokendate),1) else 0 end loadhours
        ,case when t.emptydatetime is not null and t.loaddatetime is not null then 
        round(24 * (t.emptydatetime - t.loaddatetime),1) else 0 end emptyhours
        from WEIGHTSLIP t,fieldslip f,token k,vehicle v,tyregadi r,village vl,circle c,vehiclecategory vc
        where t.seasoncode=f.seasoncode 
        and t.fieldslipnumber=f.fieldslipnumber
        and f.seasoncode=k.seasoncode
        and f.fieldslipnumber=k.fieldslipnumber
        and f.villagecode=vl.villagecode
        and vl.circlecode=c.circlecode
        and f.vehiclecategorycode=vc.vehiclecategorycode
        and f.seasoncode=v.seasoncode(+)
        and f.vehiclecode=v.vehiclecode(+)
        and f.seasoncode=r.seasoncode(+)
        and f.tyregadicode=r.tyregadicode(+)
        and nvl(t.netweight,0) >0 
        {$cond} 
        order by c.circlenameuni,vc.vehiclecategorynameuni,t.weighmentdate,t.weightslipnumber";
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $this->firstpage=1;
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);              
            $this->hline(10,195,$this->liney,'D'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
        
    }
    
    function groupheader_1(&$group_row)
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
        ob_flush();
        ob_start();
        //$this->hline(10,195,$this->liney-2,'D'); 
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
        $this->setfieldwidth(30,10);
        $this->textbox($group_row_1['CIRCLENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['VEHICLECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(35);
        if ($group_row_1['VEHICLENUMBER'] !='')
            $this->textbox($group_row_1['VEHICLENUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11,'','','','');
        else
            $this->textbox($group_row_1['TYREGADINUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(35);
        $this->textbox($group_row_1['FIELDSLIPDATE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',8,'','','','');
        $this->newrow(4);
        $this->textbox($group_row_1['TOKENDATE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',8,'','','','');
        $this->newrow(4);
        $this->textbox(number_format($group_row_1['TOKENHOURS'],1,'.',','),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(-8);
        $this->setfieldwidth(35);
        $this->textbox($group_row_1['LOADDATETIME'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',8,'','','','');
        $this->newrow(4);
        $this->textbox(number_format($group_row_1['LOADHOURS'],1,'.',','),$this->w,$this->x,'','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(4);
        $this->textbox($group_row_1['EMPTYDATETIME'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',8,'','','','');
        $this->newrow(4);
        $this->textbox(number_format($group_row_1['EMPTYHOURS'],1,'.',','),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(-12);
        $this->setfieldwidth(20);
        $this->textbox(number_format($group_row_1['NETWEIGHT'],3,'.',','),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',8,'','','','');
        $this->newrow(16);
        //$this->deductionsummary['DEDUCTIONAMOUNT']=$this->deductionsummary['DEDUCTIONAMOUNT']+$group_row_1['DEDUCTIONAMOUNT'];
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