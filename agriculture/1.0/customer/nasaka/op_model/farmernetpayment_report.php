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
class farmernetpayment extends reportbox
{
    public $bankcode;
    public $bankbranchcode;
    public $farmercode;
    public $billperiodtransnumber;
    public $msubtitle;
    public $bankname;
    public $bankbranchname;
    public $farmercategoryname;
    public $srno;
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
        $this->totalgroupcount=2;
        $pageheader_query_1="select BILLPERIODDESC(".$this->billperiodtransnumber.") as desct from dual t";
        $pageheader_result_1 = oci_parse($this->connection, $pageheader_query_1);
        $r = oci_execute($pageheader_result_1); 
        if ($pageheader_row_1 = oci_fetch_array($pageheader_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
         $this->msubtitle  =  $pageheader_row_1['DESCT'];
        }
        $this->group();
    }

    function pageheader()
    {
        ob_flush();
        ob_start();
       
        $this->liney = 13;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->newrow(4);
        $this->setfieldwidth(190,0);
        $this->textbox('ऊस पेमेंट निव्व्ळ देय यादी',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->setfieldwidth(190,10);
        $this->textbox($this->msubtitle,$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->newrow();
        $this->setfieldwidth(195,10);
        $this->textbox('बँक : '.$this->bankname.'  ब्रांच : '.$this->bankbranchname.'  '.$this->farmercategoryname,$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        $this->newrow();
        $this->hline(10,195,$this->liney,'C');
        $this->setfieldwidth(10,10);
        $this->textbox('अ.नं',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(15);
        $this->textbox('कोड',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(70);
        $this->textbox('ऊस उत्पादक नाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox('गाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox('खाते नंबर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox('देय रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->hline(10,195,$this->liney,'D');
    }

    function group()
    {
        if ($this->bankbranchcode!=0 )
        {
            $group_query_1 ="select 
            c.bankcode
            ,t.bankbranchcode
            ,c.banknameuni
            ,b.bankbranchnameuni
            ,t.transactionnumber
            ,t.billnumber
            ,to_char(h.paymentdate,'dd/MM/yyyy') paymentdate
            ,f.farmercode
            ,f.farmernameuni
            ,v.villagenameuni
            ,t.netamount
            ,t.accountnumber
            ,b.bankbranchnameuni
            from farmerbillheader t,farmer f, village v
            ,billperiodheader h,bankbranch b, bank c,Bankcategory r
            where t.farmercode=f.farmercode
            and f.villagecode=v.villagecode
            and t.billperiodtransnumber=h.billperiodtransnumber
            and c.bankcategorycode=r.bankcategorycode
            and b.bankcode=c.bankcode
            and t.bankbranchcode=b.bankbranchcode(+)
            and nvl(t.netamount,0)>0
            and t.billperiodtransnumber=".$this->billperiodtransnumber."  
            and b.bankbranchcode=".$this->bankbranchcode. 
            "order by c.bankcode,t.bankbranchcode
            ,v.circlecode,v.villagecode,f.farmernameuni";
        }
        elseif ($this->bankcode!=0 )
        {
            $group_query_1 ="select 
            c.bankcode
            ,t.bankbranchcode
            ,c.banknameuni
            ,b.bankbranchnameuni
            ,t.transactionnumber
            ,t.billnumber
            ,to_char(h.paymentdate,'dd/MM/yyyy') paymentdate
            ,f.farmercode
            ,f.farmernameuni
            ,v.villagenameuni
            ,t.netamount
            ,t.accountnumber
            ,b.bankbranchnameuni
            from farmerbillheader t,farmer f, village v
            ,billperiodheader h,bankbranch b, bank c,Bankcategory r
            where t.farmercode=f.farmercode
            and f.villagecode=v.villagecode
            and t.billperiodtransnumber=h.billperiodtransnumber
            and c.bankcategorycode=r.bankcategorycode
            and b.bankcode=c.bankcode
            and t.bankbranchcode=b.bankbranchcode(+)
            and nvl(t.netamount,0)>0
            and t.billperiodtransnumber=".$this->billperiodtransnumber."  
            and b.bankcode=".$this->bankcode.
            "order by c.bankcode,t.bankbranchcode
            ,v.circlecode,v.villagecode,f.farmernameuni";
        }
        elseif ($this->bankcode==0 and $this->bankbranchcode==0)
        {
            $group_query_1 ="select 
            c.bankcode
            ,t.bankbranchcode
            ,c.banknameuni
            ,b.bankbranchnameuni
            ,t.transactionnumber
            ,t.billnumber
            ,to_char(h.paymentdate,'dd/MM/yyyy') paymentdate
            ,f.farmercode
            ,f.farmernameuni
            ,v.villagenameuni
            ,t.netamount
            ,t.accountnumber
            ,b.bankbranchnameuni
            from farmerbillheader t,farmer f, village v
            ,billperiodheader h,bankbranch b, bank c,Bankcategory r
            where t.farmercode=f.farmercode
            and f.villagecode=v.villagecode
            and t.billperiodtransnumber=h.billperiodtransnumber
            and c.bankcategorycode=r.bankcategorycode
            and b.bankcode=c.bankcode
            and t.bankbranchcode=b.bankbranchcode(+)
            and nvl(t.netamount,0)>0
            and t.billperiodtransnumber=".$this->billperiodtransnumber."  
            order by c.bankcode,t.bankbranchcode
            ,v.circlecode,v.villagecode,f.farmernameuni";
        }
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
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
        $this->bankname=$group_row['BANKNAMEUNI'];
        $this->bankbranchname=$group_row['BANKBRANCHNAMEUNI'];
    }

    function groupheader_2(&$group_row)
    {
        $this->bankbranchname=$group_row['BANKBRANCHNAMEUNI'];
        $this->srno=0;
        $this->newpage(true);
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
        if ($this->isnewpage(20))
            {
                $this->newrow();
                $this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
        //$this->hline(10,195,$this->liney-2,'D'); 
        $this->setfieldwidth(10,10);
        $this->textbox(++$this->srno,$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['FARMERCODE'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(70);
        $this->textbox($group_row_1['FARMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['ACCOUNTNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow();
    }


    function groupfooter_1(&$last_row)
    {      
    }
    function groupfooter_2(&$last_row)
    {    
        $groupfooter_query_2 ="select  
         b.bankbranchcode
        ,b.bankbranchnameuni
        ,sum(t.netamount) netamount
        from farmerbillheader t,farmer f, village v
        ,billperiodheader h,bankbranch b, bank c,Bankcategory r
        where t.farmercode=f.farmercode
        and f.villagecode=v.villagecode
        and t.billperiodtransnumber=h.billperiodtransnumber
        and c.bankcategorycode=r.bankcategorycode
        and b.bankcode=c.bankcode
        and t.bankbranchcode=b.bankbranchcode(+)
        and t.billperiodtransnumber=".$this->billperiodtransnumber."
        and b.bankbranchcode=".$last_row['BANKBRANCHCODE']." 
        group by b.bankbranchcode
        ,b.bankbranchnameuni";
        
        $groupfooter_result_2 = oci_parse($this->connection, $groupfooter_query_2);
        $r = oci_execute($groupfooter_result_2);
        if ($groupfooter_row_2 = oci_fetch_array($groupfooter_result_2,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))  
        {
            $this->setfieldwidth(20,130);
            $this->textbox('ब्रांच एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
            $this->setfieldwidth(47);
            $this->textbox($groupfooter_row_2['NETAMOUNT'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',11,'','','','B');
            $this->newrow();
            $this->setfieldwidth(137,10);
            $this->textbox('अक्षरी->',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
            $this->setfieldwidth(112,25);
            $this->textbox(NumberToWords($groupfooter_row_2['NETAMOUNT'],1),$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');  
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
            $this->newrow(10);
            $this->setfieldwidth(20,60);
            $this->textbox('क्लार्क',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
            $this->setfieldwidth(70);
            $this->textbox('केन अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
           
        }
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