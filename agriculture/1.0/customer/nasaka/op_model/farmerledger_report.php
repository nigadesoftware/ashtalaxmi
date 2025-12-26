<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a5_l.php");
    include_once("../info/routine.php");
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class farmerledger extends reportbox
{
    public $seasonyear;
    public $farmercode;
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
        /* $pageheader_query_1="select BILLPERIODDESC(".$this->billperiodtransnumber.") as desct from dual t";
        $pageheader_result_1 = oci_parse($this->connection, $pageheader_query_1);
        $r = oci_execute($pageheader_result_1); 
        if ($pageheader_row_1 = oci_fetch_array($pageheader_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
         $this->msubtitle  =  $pageheader_row_1['DESCT'];
        } */
        $this->newpage(True);
        $this->group();
    }

    function group()
    {
        $this->balance=0;
        $this->totalgroupcount=1;
        $cond='h.seasonyear='.$this->seasonyear;
        if ($this->farmercode!='' and $this->farmercode!='0')
        {
            $cond = $cond." and f.farmercode=".$this->farmercode;
        }
            $group_query_1 ="select t.farmercode,t.transactionnumber,h.fromdate
            ,h.todate,h.billdate,f.farmernameuni,v.villagenameuni,c.circlenameuni
            ,case when t.bankbranchcode is not null then b.banknameuni||', '||b.bankbranchnameuni end bankbranchnameuni
            ,y.periodname_unicode,y.farmercategorynameuni
            ,t.netcanetonnage,t.rateperton,t.grossamount,t.netamount
            from FARMERBILLHEADER t,billperiodheader h,farmer f
            ,village v,circle c,VW_BANK_BRANCH b,nst_nasaka_db.yearperiod y
            ,farmercategory y
            where t.billperiodtransnumber=h.billperiodtransnumber
            and t.farmercode=f.farmercode and f.villagecode=v.villagecode 
            and v.circlecode=c.circlecode
            and t.farmercategorycode=y.farmercategorycode
            and t.bankbranchcode=b.bankbranchcode(+)
            and h.seasonyear=y.yearperiodcode
            and ".$cond." 
            order by t.farmercode,h.billdate,h.todate";
            $group_result_1 = oci_parse($this->connection, $group_query_1);
            $r = oci_execute($group_result_1);
            while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
            {
                $this->grouptrigger($group_row_1,$last_row);
                $this->detail_1($group_row_1);              
                $last_row=$group_row_1;
            }
            $this->grouptrigger($group_row_1,$last_row,'E');
        //$this->reportfooter();
    }
    
    function groupheader_1(&$group_row_1)
    {
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        $this->balance=0;
        $this->debit=0;
        $this->credit=0;
        $this->netcanetonnage=0;
        //$this->pageheader();
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->setfieldwidth(180,10);
        $this->textbox('शेतकरी लेजर (हंगाम : '.$group_row_1['PERIODNAME_UNICODE'].')',$this->w,$this->x,'S','C',1,'siddhanta',13,'','','','B');
        $this->newrow(7);
        $this->hline(10,195,$this->liney,'C');
        $this->setfieldwidth(105,10);
        $this->textbox('नाव : '.$group_row_1['FARMERCODE'].' - '.$group_row_1['FARMERNAMEUNI'].' - '.$group_row_1['FARMERCATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(40);
        $this->textbox('गट : '.$group_row_1['CIRCLENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(40);
        $this->textbox('गाव : '.$group_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->newrow(10);
        $this->hline(10,195,$this->liney,'C');
        $this->setfieldwidth(25,10);
        $this->textbox('दिनांक',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(80);
        $this->textbox('तपशिल',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox('नावे',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox('जमा',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox('शिल्लक',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->newrow();
        $this->hline(10,195,$this->liney,'C');
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
    function pageheader()
    {
        //$this->pdf->Image("../img/kadwawatermark.png", 60, 35, 70, 70, '', '', '', false, 300, '', false, false, 0);
        /*  ob_flush();
        ob_start();
       
        $this->liney = 13;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->newrow(4);
        $this->setfieldwidth(200);
        $this->textbox('ऊस बिल',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B'); */
    }
    //$group_row_1['TRANSACTIONNUMBER']
    function detail_1(&$group_row_1)
    { 
        $this->balance=$this->balance+$group_row_1['GROSSAMOUNT'];
        $this->credit=$this->credit+$group_row_1['GROSSAMOUNT'];
        $this->tonnage=$this->tonnage+$group_row_1['NETCANETONNAGE'];
        $this->setfieldwidth(25,10);
        if ($group_row_1['BILLDATE'] !='')
        {
            $dt=DateTime::createFromFormat('d-M-y',$group_row_1['BILLDATE'])->format('d/m/Y');
            $this->textbox($dt,$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',10,'','','','');
        }
        else
        {
            $dt=DateTime::createFromFormat('d-M-y',$group_row_1['TODATE'])->format('d/m/Y');
            $this->textbox($dt,$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',10,'','','','');
        }
        $frdt = DateTime::createFromFormat('d-M-y',$group_row_1['FROMDATE'])->format('d/m/Y');
        $todt = DateTime::createFromFormat('d-M-y',$group_row_1['TODATE'])->format('d/m/Y');
        $this->setfieldwidth(80);
        $this->textbox('दि.'.$frdt.' ते दि.'.$todt.' ऊस टनेज '.$group_row_1['NETCANETONNAGE'].'@'.$group_row_1['RATEPERTON'],$this->w,$this->x,'S','L',1,'siddhanta',8,'','Y','','');
        $this->setfieldwidth(25);
        //$this->textbox('',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['GROSSAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->balance,$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(7);
        $this->hline(10,195,$this->liney,'D');
        $detail_query_1 = "select t.transactionnumber,d.deductioncode,c.deductionnameuni,d.branchcode,case when b.bankbranchcode is not null then b.banknameuni||', '||b.bankbranchnameuni end bankbranchnameuni,d.dedseasonyear,d.deductionamount
        from FARMERBILLHEADER t,farmerbilldeductiondetail d,billperiodheader h,deduction c
        ,VW_BANK_BRANCH b
        where t.billperiodtransnumber=h.billperiodtransnumber
        and t.transactionnumber=d.billtransactionnumber
        and d.deductioncode=c.deductioncode
        and d.deductioncode=b.deductioncode(+)
        and d.branchcode=b.bankbranchcode(+)
        and t.transactionnumber=".$group_row_1['TRANSACTIONNUMBER']." order by d.deductioncode";
        $detail_result_1 = oci_parse($this->connection, $detail_query_1);
        $r = oci_execute($detail_result_1);
        $islastodd=0;
        while ($detail_row_1 = oci_fetch_array($detail_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->balance=$this->balance-$detail_row_1['DEDUCTIONAMOUNT'];
            $this->debit=$this->debit+$detail_row_1['DEDUCTIONAMOUNT'];
            $this->setfieldwidth(25,10);
            if ($group_row_1['BILLDATE'] !='')
            {
                $dt=DateTime::createFromFormat('d-M-y',$group_row_1['BILLDATE'])->format('d/m/Y');
                $this->textbox($dt,$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',10,'','','','');
            }
            else
            {
                $dt=DateTime::createFromFormat('d-M-y',$group_row_1['TODATE'])->format('d/m/Y');
                $this->textbox($dt,$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',10,'','','','');
            }
            $this->setfieldwidth(80);
            $frdt = DateTime::createFromFormat('d-M-y',$group_row_1['FROMDATE'])->format('d/m/Y');
            $todt = DateTime::createFromFormat('d-M-y',$group_row_1['TODATE'])->format('d/m/Y');
            if ($detail_row_1['BANKBRANCHNAMEUNI'] == '')
                $this->textbox('     '.$detail_row_1['DEDUCTIONNAMEUNI'].' वसुली',$this->w,$this->x,'S','L',1,'siddhanta',8,'','','','');
            else
                $this->textbox('     '.$detail_row_1['BANKBRANCHNAMEUNI'].' '.$detail_row_1['DEDUCTIONNAMEUNI'].' वसुली',$this->w,$this->x,'S','L',1,'siddhanta',8,'','Y','','');
            $this->setfieldwidth(25);
            $this->textbox($detail_row_1['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(25);
            //$this->textbox($group_row_1['NETAMOUNT'].$villagename,$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
            $this->setfieldwidth(30);
            $this->textbox($this->balance,$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->newrow(7);
            $this->hline(10,195,$this->liney,'D');
            
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
        if ($group_row_1['NETAMOUNT']>0)
        {
            $this->balance=$this->balance-$group_row_1['NETAMOUNT'];
            $this->debit=$this->debit+$group_row_1['NETAMOUNT'];
           
            $this->setfieldwidth(25,10);
            if ($group_row_1['BILLDATE'] !='')
            {
                $dt=DateTime::createFromFormat('d-M-y',$group_row_1['BILLDATE'])->format('d/m/Y');
                $this->textbox($dt,$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',10,'','','','');
            }
            else
            {
                $dt=DateTime::createFromFormat('d-M-y',$group_row_1['TODATE'])->format('d/m/Y');
                $this->textbox($dt,$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',10,'','','','');
            }
            $this->setfieldwidth(80);
            $this->textbox($group_row_1['BANKBRANCHNAMEUNI'].' बॅँकेत पेमेंट वर्ग',$this->w,$this->x,'S','L',1,'siddhanta',8,'','Y','','');
            $this->setfieldwidth(25);
            $this->textbox($group_row_1['NETAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(25);
            //$this->textbox($group_row_1['NETAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->setfieldwidth(30);
            $this->textbox($this->balance,$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->newrow(7);
            $this->hline(10,195,$this->liney,'D');
        }
    }

    function groupfooter_1(&$group_row_1)
    {    
        $this->hline(10,195,$this->liney,'C');
        $this->setfieldwidth(80,10);
        $this->textbox('एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->tonnage,$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->debit,$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($this->credit,$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox(($this->debit-$this->credit),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(7);
        $this->hline(10,195,$this->liney,'C');
    }
    function groupfooter_2(&$group_row)
    {
    }
    function groupfooter_3(&$group_row)
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