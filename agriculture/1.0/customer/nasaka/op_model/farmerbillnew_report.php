<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a5_l.php");
    include_once("../info/routine.php");
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class farmerbill extends swappreport
{
    public $circlecode;
    public $villagecode;
    public $farmercode;
    public $billperiodtransnumber;
    public $msubtitle;
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
        $group_query_mm =
        "select b.billperiodtransnumber
        from billperiodheader b,farmerbillheader f
       where b.billperiodtransnumber=f.billperiodtransnumber
       and b.seasonyear=".$_SESSION['yearperiodcode']."       
        and b.payeecategorycode=1
       and f.farmercode=".$this->farmercode."
       order by b.billperiodtransnumber";
       $group_result_mm = oci_parse($this->connection, $group_query_mm);
       $rmm = oci_execute($group_result_mm);
       while ($group_row_mm = oci_fetch_array($group_result_mm,OCI_ASSOC+OCI_RETURN_NULLS))
       {
        $this->billperiodtransnumber=$group_row_mm['BILLPERIODTRANSNUMBER'];
        $this->newpage(True);

        $pageheader_query_1="select BILLPERIODDESC(".$this->billperiodtransnumber.") as desct from dual t";
        $pageheader_result_1 = oci_parse($this->connection, $pageheader_query_1);
        $r = oci_execute($pageheader_result_1); 
        if ($pageheader_row_1 = oci_fetch_array($pageheader_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
         $this->msubtitle  =  $pageheader_row_1['DESCT'];
        }
        $this->group();
        
       }
       $this->endreport();
    }

    function group()
    {
        $cond='';
        if ($this->farmercode!='' and $this->farmercode!='0')
        {
            if ($cond=='')
            {
                $cond = " and f.farmercode=".$this->farmercode;
            }
            else
            {
                $cond = $cond." and f.farmercode=".$this->farmercode;
            }
        }
        
            $group_query_1 ="select  t.transactionnumber,t.billnumber,to_char(h.billdate,'dd/MM/yyyy') billdate,f.farmercode,f.farmernameuni,v.villagenameuni,t.noofslips
            ,t.netcanetonnage,t.rateperton,t.grossamount ,t.grossdeduction
            ,t.netcanetonnage,t.rateperton,t.grossamount,t.grossdeduction
            , t.netamount,t.accountnumber,b.bankbranchnameuni,r.banknameuni
            ,c.circlenameuni,f.farmercategorycode,y.farmercategorynameuni
            from farmerbillheader t,farmer f, village v
            ,billperiodheader h,bankbranch b,bank r,circle c,farmercategory y
            where t.farmercode=f.farmercode
            and f.villagecode=v.villagecode
            and v.circlecode=c.circlecode
            and t.billperiodtransnumber=h.billperiodtransnumber
            and t.farmercategorycode=y.farmercategorycode
            and t.bankbranchcode=b.bankbranchcode(+)
            and b.bankcode=r.bankcode(+)
            and t.billperiodtransnumber=".$this->billperiodtransnumber." 
            ".$cond.
            " order by
            v.circlecode 
            ,v.villagecode
            ,t.farmercategorycode
            ,t.billnumber";
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
           // $this->newpage(True);
            
            $this->groupheader_1($group_row_1['BILLNUMBER'],$group_row_1['BILLDATE']
            ,$group_row_1['FARMERCODE'],$group_row_1['FARMERNAMEUNI'],$group_row_1['CIRCLENAMEUNI'],$group_row_1['VILLAGENAMEUNI']
            ,$group_row_1['NOOFSLIPS'],$group_row_1['NETCANETONNAGE'],$group_row_1['RATEPERTON'],$group_row_1['GROSSAMOUNT']
            ,$group_row_1['GROSSDEDUCTION'],$group_row_1['FARMERCATEGORYNAMEUNI']);
             
            $this->detail($group_row_1['TRANSACTIONNUMBER']);
            
            $this->groupfooter_1($group_row_1['GROSSDEDUCTION']
            ,$group_row_1['NETAMOUNT'],$group_row_1['ACCOUNTNUMBER'],$group_row_1['BANKBRANCHNAMEUNI'],$group_row_1['BANKNAMEUNI']);
        }
        //$this->reportfooter();
    }
    
    function groupheader_1($billnumber,$billdate,$farmercode,$farmername,$circlename,$villagename,$noofslips,$netcanetonnage,$rateperton,$grossamount,$grossdeduction,$farmercategorynameuni )
    {
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        //$this->pageheader();
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->setfieldwidth(180,10);
        $this->textbox('ऊस बिल',$this->w,$this->x,'S','C',1,'siddhanta',13,'','','','B');
        $this->newrow(4);
        $this->setfieldwidth(40,40);
        $this->textbox('बिल नंबर:'.$billnumber,$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(70,150);
        $this->textbox('बिल दिनांक:'.$billdate,$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->setfieldwidth(200,20);
        $this->textbox($this->msubtitle,$this->w,$this->x,'S','L',1,'siddhanta',8,'','','','');
        $this->newrow();
        $this->hline(10,195,$this->liney,'C');
        $this->setfieldwidth(100,10);
        $this->textbox('नाव : '.$farmercode.' '.$farmername,$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(40);
        $this->textbox('गट : '.$circlename,$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(40);
        $this->textbox('गाव : '.$villagename,$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->newrow();
        $this->setfieldwidth(60,10);
        $this->textbox('एकूण ऊस (मे.टन) : '.number_format_indian($netcanetonnage,3),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(60);
        $this->textbox('दरप्रती मे.टन रु. : '.$rateperton,$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(80);
        $this->textbox('रक्कम : '.number_format_indian($grossamount,2),$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->newrow();
        $this->hline(10,195,$this->liney,'C');
    }

    function pageheader()
    {
        $this->pdf->Image("../img/kadwawatermark.png", 70, 45, 70, 70, '', '', '', false, 300, '', false, false, 0);
        /*  ob_flush();
        ob_start();
       
        $this->liney = 13;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->newrow(4);
        $this->setfieldwidth(200);
        $this->textbox('ऊस बिल',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B'); */
        //$this->textbox('द्वारा अष्टलक्ष्मी शुगर इथॅनॉल अॅन्ड एनर्जी्स',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B'); 
    }
    //$group_row_1['TRANSACTIONNUMBER']
    function detail($transactionnumber)
    { 
        $this->setfieldwidth(50,10);
        $this->textbox('कपाती :',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        $this->newrow();
        $detail_query_1 = "select t.serialnumber
        ,trim(trim(d.deductionnameuni) ||' '|| trim(t.dedseasonyear)) dednameuni
        ,trim(k.banknameuni)||' '||trim(b.bankbranchnameuni) bankbranchname
        ,t.deductionamount
         from FARMERBILLDEDUCTIONDETAIL t
         ,deduction d
         ,bankbranch b
         ,bank k
        where t.deductioncode=d.deductioncode 
        and t.branchcode=b.bankbranchcode(+) 
        and b.bankcode=k.bankcode(+)
        and t.billtransactionnumber=".$transactionnumber."order by serialnumber";
        $detail_result_1 = oci_parse($this->connection, $detail_query_1);
        $r = oci_execute($detail_result_1);
        $islastodd=0;
        while ($detail_row_1 = oci_fetch_array($detail_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($detail_row_1['SERIALNUMBER']%2) 
            {
                $this->setfieldwidth(07,10);
                $this->textbox($detail_row_1['SERIALNUMBER'].')',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(60);
                $this->textbox($detail_row_1['DEDNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                $this->newrow(4);
                $this->textbox($detail_row_1['BANKBRANCHNAME'],$this->w,$this->x+10,'S','L',1,'siddhanta',8,'','','','');
                $this->newrow(-4);
                $this->setfieldwidth(25);
                $this->textbox($detail_row_1['DEDUCTIONAMOUNT'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
                $this->vline($this->liney-2,$this->liney+5,102);
                $islastodd=1;
            }
            else
            {
                $this->setfieldwidth(07,104);
                $this->textbox($detail_row_1['SERIALNUMBER'].')',$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',10,'','','','');
                $this->setfieldwidth(60);
                $this->textbox($detail_row_1['DEDNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                $this->newrow(4);
                $this->textbox($detail_row_1['BANKBRANCHNAME'],$this->w,$this->x+10,'S','L',1,'siddhanta',10,'','','','');
                $this->newrow(-4);
                $this->setfieldwidth(25);
                $this->textbox($detail_row_1['DEDUCTIONAMOUNT'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
                //$this->vline($this->liney,$this->liney+5,105);
                $this->newrow();
                $islastodd=0;
            }
            
            if ($this->isnewpage(10))
            {
                //$this->newrow();
                //$this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            else
            {
                //$this->newrow();
                //$this->hline(10,195,$this->liney-2,'C'); 
            }
        }
        if ($islastodd==1) 
        {
            $this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
    }

    function groupfooter_1($grossdeduction,$netamount,$accountnumber,$bankbranchnameuni,$banknameuni)
    {        
        $this->liney = 90;
        $this->hline(10,195,$this->liney-2,'C');
        $this->setfieldwidth(30,137);
        $this->textbox('एकूण कपात ->',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(25); 
        $this->textbox($grossdeduction,$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');  
        $this->newrow();
        $this->hline(10,195,$this->liney-2,'C'); 
        $this->setfieldwidth(137,10);
        $this->textbox('अक्षरी->',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(112,25);
        $this->textbox(NumberToWords($netamount,1),$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');  
       
        $this->setfieldwidth(30);
        $this->textbox('निव्वळ देय ->',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($netamount,$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');  
        $this->newrow();
        $this->hline(10,195,$this->liney-2,'C'); 
        $this->setfieldwidth(15,10);
        $this->textbox('शाखा ->',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(130);
        $this->textbox($banknameuni.', '.$bankbranchnameuni,$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');  
        $this->newrow();
        $this->setfieldwidth(50,10);
        $this->textbox('चूकभूल देणे घेणे',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->hline(10,195,$this->liney-2,'C'); 
        $this->newrow(15);
        $this->setfieldwidth(20,40);
        $this->textbox('क्लार्क',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(30);
        $this->textbox('केन अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(40);
        $this->textbox('चिफ अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(55);
        $this->textbox('जनरल मॅनेजर',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');

    }
    
    function pagefooter($islastpage=false)
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