<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a3_l_eng.php");
    include_once("../info/routine.php");
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class farmerrtgs extends reportbox
{
    public $billperiodtransnumber;
    public $msubtitle;
    public $paymentcategory;
    public $paymentcategorysummary;
    public $summary;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7,$subject='',$pdffilename='',$papersize='A3',$orientation='L')
	{
        $this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $this->subject= $subject;
        $this->pdffilename= $pdffilename;
        $this->papersize=strtoupper($papersize);
        $this->orientation=strtoupper($orientation);
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueType', '', 32);
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
        $pageheader_query_1="select BILLPERIODDESC(".$this->billperiodtransnumber.",1) as desct from dual t";
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
        $this->pdf->SetFont('verdana', '', 11, '', true);
        $this->newrow(4);
        $this->setfieldwidth(390,0);
        $this->textbox('RTGS Saving List',$this->w,$this->x,'S','C',1,'verdana',10,'','','','B');
        $this->newrow();
        $this->setfieldwidth(390,10);
        $this->textbox($this->msubtitle,$this->w,$this->x,'S','C',1,'verdana',10,'','','','');
        $this->setfieldwidth(50,10);
        $this->textbox($this->paymentcategory,$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->newrow();
        $this->hline(10,395,$this->liney,'C');
        $this->setfieldwidth(12,10);
        $this->textbox('NEFT',$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->setfieldwidth(20);
        $this->textbox('Debit A/c',$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->setfieldwidth(30);
        $this->textbox('Amount',$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->setfieldwidth(60);
        $this->textbox('Name of Beneficiery',$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox('Type A/c',$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->setfieldwidth(10);
        $this->textbox('Address',$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->setfieldwidth(35);
        $this->textbox('Account No',$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->setfieldwidth(35);
        $this->textbox('IFSC Code',$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox('Mobile No.',$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->setfieldwidth(80);
        $this->textbox('Name of Bank',$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->setfieldwidth(15);
        $this->textbox('Code No.',$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox('Village',$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->newrow();
        $this->hline(10,395,$this->liney,'D');
    }

    function group()
    {
        $this->totalgroupcount=1;
        $this->summary['NETAMOUNT']=0;
        $group_query_1 ="select paymentcategorycode
        ,bankcode
        ,bankbranchcode
        ,banknameeng
        ,bankbranchnameeng
        ,transactionnumber
        ,billnumber
        ,paymentdate
        ,farmercode
        ,farmernameeng
        ,villagenameeng
        ,netamount
        ,accountnumber
        ,mobilenumber
        ,ifsc
        ,code
        ,verified
        from (
        select
        case when netamount>=200000 and netamount<500000 then 2 
        when netamount>=500000 then 4 
        else 3 end paymentcategorycode
        ,c.bankcode
        ,t.bankbranchcode
        ,c.banknameeng
        ,b.bankbranchnameeng
        ,t.transactionnumber
        ,t.billnumber
        ,to_char(h.paymentdate,'dd/MM/yyyy') paymentdate
        ,f.farmercode
        ,case when t.farmercategorycode=1 then 'M'||to_char(t.farmercode)
        when t.farmercategorycode=2 then 'N'||to_char(t.farmercode)
        when t.farmercategorycode=3 then 'G'||to_char(t.farmercode) end code
        ,f.farmernameeng
        ,v.villagenameeng
        ,t.netamount
        ,t.accountnumber
        ,f.mobilenumber
        ,b.ifsc
        ,nvl(f.verified,0) verified
        from farmerbillheader t
        , village v
        ,billperiodheader h,bankbranch b, bank c,Bankcategory r
        where f.villagecode=v.villagecode
        and t.billperiodtransnumber=h.billperiodtransnumber
        and c.bankcategorycode=r.bankcategorycode
        and b.bankcode=c.bankcode
        and t.bankbranchcode=b.bankbranchcode(+)
        and nvl(t.netamount,0)>0
        and t.bankbranchcode<>113
        and t.billperiodtransnumber=".$this->billperiodtransnumber.")t  
        order by paymentcategorycode,bankcode,bankbranchcode,farmernameeng
        ,accountnumber";
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);              
            $this->hline(10,395,$this->liney,'D'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
    }
    
    function groupheader_1(&$group_row)
    {
        $this->paymentcategorysummary['NETAMOUNT']=0;
        if ($group_row['PAYMENTCATEGORYCODE']==1)
            $this->paymentcategory='Kotak Mahindra';
        else if ($group_row['PAYMENTCATEGORYCODE']==2)
            $this->paymentcategory='Above two lacs and Below five lacs';
        else if ($group_row['PAYMENTCATEGORYCODE']==3)
            $this->paymentcategory='Below two lacs';
        else if ($group_row['PAYMENTCATEGORYCODE']==4)
        $this->paymentcategory='Above five lacs';    
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
        $this->setfieldwidth(12,10);
        $this->textbox('NEFT',$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->setfieldwidth(20);
        $this->textbox('4812021567',$this->w,$this->x,'S','L',1,'verdana',8,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['NETAMOUNT'],$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->setfieldwidth(70);
        $this->textbox($group_row_1['FARMERNAMEENG'],$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->setfieldwidth(15);
        if ($group_row_1['VERIFIED'] == 0)
            $this->textbox('* Saving',$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        else
            $this->textbox('Saving',$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->setfieldwidth(12);
        $this->textbox('KSSK',$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->setfieldwidth(45);
        $this->textbox($group_row_1['ACCOUNTNUMBER'],$this->w,$this->x,'S','L',1,'verdana',9,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['IFSC'],$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['MOBILENUMBER'],$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->setfieldwidth(80);
        $this->textbox($group_row_1['BANKNAMEENG'].', '.$group_row_1['BANKBRANCHNAMEENG'],$this->w,$this->x,'S','L',1,'verdana',8,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['CODE'],$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->setfieldwidth(45);
        $this->textbox($group_row_1['VILLAGENAMEENG'],$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->newrow();
        $this->paymentcategorysummary['NETAMOUNT']=$this->paymentcategorysummary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        $this->summary['NETAMOUNT']=$this->summary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
    }


    function groupfooter_1(&$last_row)
    {      
        if ($this->isnewpage(30))
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
        $this->setfieldwidth(22,10);
        $this->textbox('Total',$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->setfieldwidth(40);
        $this->textbox($this->paymentcategorysummary['NETAMOUNT'],$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->newrow(20);
        $this->setfieldwidth(20,100);
        $this->textbox('Clerk',$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('Cane Accountant',$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('Chief Accountant',$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('Managing Director',$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
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
        /* if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,395,$this->liney-2,'C'); 
        } */
        $this->newrow(-10);
        $this->setfieldwidth(22,10);
        $this->textbox('Gr.Total',$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->setfieldwidth(40);
        $this->textbox($this->summary['NETAMOUNT'],$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        /* $this->newrow(10);
        $this->setfieldwidth(20,100);
        $this->textbox('Clerk',$this->w,$this->x,'S','L',1,'verdana',10,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('Cane Accountant',$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('Chief Accountant',$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('Managing Director',$this->w,$this->x,'S','R',1,'verdana',10,'','','',''); */
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
    function export_old($billperiodtransnumber,$paymentcategorycode,$circlecode)
    {   
        $cond=" and 1=1";   
        if ($circlecode!=0)
           {
                $cond=$cond." and v.circlecode=".$circlecode;
           }
           if ($paymentcategorycode!=1)
           {
            $query ="select paymentcategorycode
            ,bankcode
            ,bankbranchcode
            ,banknameeng
            ,bankbranchnameeng
            ,transactionnumber
            ,billnumber
            ,paymentdate
            ,farmercode
            ,farmernameeng
            ,villagenameeng
            ,netamount
            ,netcanetonnage
            ,accountnumber
            ,mobilenumber
            ,ifsc
            ,code
            ,verified
            from (
            select
            case  
            when netamount>=200000 then 2 
            else 3 end paymentcategorycode
            ,c.bankcode
            ,t.bankbranchcode
            ,c.banknameeng
            ,b.bankbranchnameeng
            ,t.transactionnumber
            ,t.billnumber
            ,to_char(h.paymentdate,'dd/MM/yyyy') paymentdate
            ,f.farmercode
            ,f.farmernameeng
            ,v.villagenameeng
            ,t.netamount
            ,t.netcanetonnage
            ,t.accountnumber as accountnumber
            ,f.mobilenumber
            ,b.ifsc
            ,nvl(f.verified,0) verified
            ,t.farmercode code
            from farmerbillheader t,farmer f
            , village v
            ,billperiodheader h,bankbranch b
            , bank c,Bankcategory r
            where t.farmercode=f.farmercode 
            and f.villagecode=v.villagecode
            and t.billperiodtransnumber=h.billperiodtransnumber
            and c.bankcategorycode=r.bankcategorycode
            and b.bankcode=c.bankcode
            and t.bankbranchcode=b.bankbranchcode(+)
             {$cond}
            and nvl(t.netamount,0)>0
            and t.bankbranchcode<>113
            and t.billperiodtransnumber=".$billperiodtransnumber.")t  
            where paymentcategorycode={$paymentcategorycode}
            order by paymentcategorycode,bankcode,bankbranchcode,farmernameeng
            ,accountnumber";
           }
           else
           {
            $query ="select paymentcategorycode
            ,bankcode
            ,bankbranchcode
            ,banknameeng
            ,bankbranchnameeng
            ,transactionnumber
            ,billnumber
            ,paymentdate
            ,farmercode
            ,farmernameeng
            ,villagenameeng
            ,netamount
            ,netcanetonnage
            ,accountnumber
            ,mobilenumber
            ,ifsc
            ,code
            ,verified
            from (
            select
            case  
            when netamount>=200000 then 2 
            else 3 end paymentcategorycode
            ,c.bankcode
            ,t.bankbranchcode
            ,c.banknameeng
            ,b.bankbranchnameeng
            ,t.transactionnumber
            ,t.billnumber
            ,to_char(h.paymentdate,'dd/MM/yyyy') paymentdate
            ,f.farmercode
            ,f.farmernameeng
            ,v.villagenameeng
            ,t.netamount
            ,t.netcanetonnage
            ,t.accountnumber as accountnumber
            ,f.mobilenumber
            ,b.ifsc
            ,nvl(f.verified,0) verified
            ,t.farmercode code
            from farmerbillheader t,farmer f
            , village v
            ,billperiodheader h,bankbranch b
            , bank c,Bankcategory r
            where t.farmercode=f.farmercode 
            and f.villagecode=v.villagecode
            and t.billperiodtransnumber=h.billperiodtransnumber
            and c.bankcategorycode=r.bankcategorycode
            and b.bankcode=c.bankcode
            and t.bankbranchcode=b.bankbranchcode(+)
             {$cond}
            and nvl(t.netamount,0)>0
            and t.bankbranchcode<>113
            and t.billperiodtransnumber=".$billperiodtransnumber.")t  
            order by paymentcategorycode,bankcode,bankbranchcode,farmernameeng
            ,accountnumber";
           }
            
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $response = array();
           if ($circlecode!=0)
           {
            if ($paymentcategorycode==1)
            $filename='farmerrtgs_all_circle_'.$circlecode.'.csv';
            elseif ($paymentcategorycode==2)
            $filename='farmerrtgs_above2lac_circle_'.$circlecode.'.csv';
            elseif ($paymentcategorycode==3)
            $filename='farmerrtgs_below2lac_circle_'.$circlecode.'.csv';
            elseif ($paymentcategorycode==4)
            $filename='All List'.$circlecode.'.csv';
           }
           else
           {
            if ($paymentcategorycode==1)
            $filename='farmerrtgs_all.csv';
            elseif ($paymentcategorycode==2)
            $filename='farmerrtgs_above2lac_all.csv';
            elseif ($paymentcategorycode==3)
            $filename='farmerrtgs_below2lac_all.csv';
            elseif ($paymentcategorycode==4)
            $filename='All List'.$circlecode.'.csv';
           }
           //$fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');

           $fp1=fopen('php://memory', 'w');
           fputcsv($fp1, array('Beneficiary Bank IFSC Code','','','CBS Branch Code','GL Code','Ordering Customer Account No with Branch','Beneficiary Customer Account No','Beneficiary Account Holder Name','Beneficary Details','Beneficary Details','Beneficiary Bank Name','Beneficiary Bank Branch Name','Beneficiary Bank Branch Address Details in brief','Ordering Customer Mobile No MANDATORY','Ordering Customer Name'));
           fputcsv($fp1, array('IFSCCODE','AMT WITH Decimal','','BRCD','PRD','ACNO','BENDESC1','BENDESC2','3','4','ORDDESC3','ORDDESC4','EXTRA','MOBILEEMAI','ORDDESC2'));
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                $acno="'".$row['ACCOUNTNUMBER'];
                $accode="'0040107040000087";
                $brcode="'0040";
                fputcsv($fp1, array($row['IFSC'],number_format_indian($row['NETAMOUNT'],2),'',$brcode,'',$accode,$acno,$row['FARMERNAMEENG'],'','',$row['BANKNAMEENG'],$row['BANKBRANCHNAMEENG'],'','8208026346','Ashtalaxmi Sugar Ethanol and Energy Nasikroad'), $delimiter = ',', $enclosure = '"');
           }
           
           // reset the file pointer to the start of the file
            fseek($fp1, 0);
            // tell the browser it's going to be a csv file
            header('Content-Type: application/csv');
            // tell the browser we want to save it instead of displaying it
            header('Content-Disposition: attachment; filename="'.$filename.'";');
            // make php send the generated csv lines to the browser
            fpassthru($fp1); 
            //fclose($fp1);

    }
    function export($billperiodtransnumber,$paymentcategorycode,$circlecode)
    {   
        $cond=" and 1=1";   
        if ($circlecode!=0)
           {
                $cond=$cond." and v.circlecode=".$circlecode;
           }
           if ($paymentcategorycode!=1)
           {
            $query ="select paymentcategorycode
            ,bankcode
            ,bankbranchcode
            ,banknameeng
            ,bankbranchnameeng
            ,transactionnumber
            ,billnumber
            ,paymentdate
            ,farmercode
            ,farmernameeng
            ,villagenameeng
            ,netamount
            ,netcanetonnage
            ,accountnumber
            ,mobilenumber
            ,ifsc
            ,code
            ,verified
            from (
            select
            case  
            when netamount>=200000 then 2 
            else 3 end paymentcategorycode
            ,c.bankcode
            ,t.bankbranchcode
            ,c.banknameeng
            ,b.bankbranchnameeng
            ,t.transactionnumber
            ,t.billnumber
            ,to_char(h.paymentdate,'dd/MM/yyyy') paymentdate
            ,f.farmercode
            ,f.farmernameeng
            ,v.villagenameeng
            ,t.netamount
            ,t.netcanetonnage
            ,t.accountnumber as accountnumber
            ,f.mobilenumber
            ,b.ifsc
            ,nvl(f.verified,0) verified
            ,t.farmercode code
            from farmerbillheader t,farmer f
            , village v
            ,billperiodheader h,bankbranch b
            , bank c,Bankcategory r
            where t.farmercode=f.farmercode 
            and f.villagecode=v.villagecode
            and t.billperiodtransnumber=h.billperiodtransnumber
            and c.bankcategorycode=r.bankcategorycode
            and b.bankcode=c.bankcode
            and t.bankbranchcode=b.bankbranchcode(+)
             {$cond}
            and nvl(t.netamount,0)>0
            and t.bankbranchcode<>113
            and t.billperiodtransnumber=".$billperiodtransnumber.")t  
            where paymentcategorycode={$paymentcategorycode}
            order by paymentcategorycode,bankcode,bankbranchcode,farmernameeng
            ,accountnumber";
           }
           else
           {
            $query ="select paymentcategorycode
            ,bankcode
            ,bankbranchcode
            ,banknameeng
            ,bankbranchnameeng
            ,transactionnumber
            ,billnumber
            ,paymentdate
            ,farmercode
            ,farmernameeng
            ,villagenameeng
            ,netamount
            ,netcanetonnage
            ,accountnumber
            ,mobilenumber
            ,ifsc
            ,code
            ,verified
            from (
            select
            case  
            when netamount>=200000 then 2 
            else 3 end paymentcategorycode
            ,c.bankcode
            ,t.bankbranchcode
            ,c.banknameeng
            ,b.bankbranchnameeng
            ,t.transactionnumber
            ,t.billnumber
            ,to_char(h.paymentdate,'dd/MM/yyyy') paymentdate
            ,f.farmercode
            ,f.farmernameeng
            ,v.villagenameeng
            ,t.netamount
            ,t.netcanetonnage
            ,t.accountnumber as accountnumber
            ,f.mobilenumber
            ,b.ifsc
            ,nvl(f.verified,0) verified
            ,t.farmercode code
            from farmerbillheader t,farmer f
            , village v
            ,billperiodheader h,bankbranch b
            , bank c,Bankcategory r
            where t.farmercode=f.farmercode 
            and f.villagecode=v.villagecode
            and t.billperiodtransnumber=h.billperiodtransnumber
            and c.bankcategorycode=r.bankcategorycode
            and b.bankcode=c.bankcode
            and t.bankbranchcode=b.bankbranchcode(+)
             {$cond}
            and nvl(t.netamount,0)>0
            and t.bankbranchcode<>113
            and t.billperiodtransnumber=".$billperiodtransnumber.")t  
            order by paymentcategorycode,bankcode,bankbranchcode,farmernameeng
            ,accountnumber";
           }
            
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $response = array();
           if ($circlecode!=0)
           {
            if ($paymentcategorycode==1)
            $filename='farmerrtgs_all_circle_'.$circlecode.'.csv';
            elseif ($paymentcategorycode==2)
            $filename='farmerrtgs_above2lac_circle_'.$circlecode.'.csv';
            elseif ($paymentcategorycode==3)
            $filename='farmerrtgs_below2lac_circle_'.$circlecode.'.csv';
            elseif ($paymentcategorycode==4)
            $filename='All List'.$circlecode.'.csv';
           }
           else
           {
            if ($paymentcategorycode==1)
            $filename='farmerrtgs_all.csv';
            elseif ($paymentcategorycode==2)
            $filename='farmerrtgs_above2lac_all.csv';
            elseif ($paymentcategorycode==3)
            $filename='farmerrtgs_below2lac_all.csv';
            elseif ($paymentcategorycode==4)
            $filename='All List'.$circlecode.'.csv';
           }
           //$fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');

           $fp1=fopen('php://memory', 'w');
          // fputcsv($fp1, array('Beneficiary Bank IFSC Code','','','CBS Branch Code','GL Code','Ordering Customer Account No with Branch','Beneficiary Customer Account No','Beneficiary Account Holder Name','Beneficary Details','Beneficary Details','Beneficiary Bank Name','Beneficiary Bank Branch Name','Beneficiary Bank Branch Address Details in brief','Ordering Customer Mobile No MANDATORY','Ordering Customer Name'));
           fputcsv($fp1, array('Account Number','Amount','Tran Perticular','IFSC Code','BENEFICIARY ACCOUNT','BENEFICIARY NAME','Address','Branch'));
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                $acno="'".$row['ACCOUNTNUMBER'];
                $accode="'0849202100000813";
                $brcode="'0040";
                fputcsv($fp1, array( $accode,number_format_indian($row['NETAMOUNT'],2),'Cane Payment',$row['IFSC'],$acno,$row['FARMERNAMEENG'],$row['BANKNAMEENG'],$row['BANKBRANCHNAMEENG']), $delimiter = ',', $enclosure = '"');
           }
           
           // reset the file pointer to the start of the file
            fseek($fp1, 0);
            // tell the browser it's going to be a csv file
            header('Content-Type: application/csv');
            // tell the browser we want to save it instead of displaying it
            header('Content-Disposition: attachment; filename="'.$filename.'";');
            // make php send the generated csv lines to the browser
            fpassthru($fp1); 
            //fclose($fp1);

    }
}    
?>