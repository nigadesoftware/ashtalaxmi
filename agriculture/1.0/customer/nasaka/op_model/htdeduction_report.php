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
class htdeduction extends reportbox
{
    public $dedcode;
    //public $bankbranchcode;
    public $farmercode;
    public $billperiodtransnumber;
    public $msubtitle;
    public $dedname;
    public $branchname;
    public $dedseasonyear;
    public $farmercategoryname;
    public $srno;
    public $deductionsummary;
    public $branchsummary;
    public $dedseasonyearsummary;
    Public $firstpage;
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
        $this->setfieldwidth(190,10);
        $this->textbox($this->servicetrhrcategory().' पेमेंट कपात यादी',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->setfieldwidth(190,10);
        $this->textbox($this->msubtitle,$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','');
        $this->newrow();
        $this->setfieldwidth(195,10);
        if ($this->branchname!='' and $this->dedseasonyear!='')
        {
            $dedname='कपात : '.$this->dedname.' shakha :'.$this->branchname.' hangam :'.$this->dedseasoneyar;
        }
        elseif ($this->branchname!='' and $this->dedseasonyear=='')
        {
            $dedname='कपात : '.$this->dedname.' shakha :'.$this->branchname;
        }
        elseif ($this->branchname=='' and $this->dedseasonyear!='')
        {
            $dedname='कपात : '.$this->dedname.' hangam :'.$this->dedseasoneyar;
        }
        elseif ($this->branchname=='' and $this->dedseasonyear=='')
        {
            $dedname='कपात : '.$this->dedname;
        }
        $this->textbox($dedname,$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        
        $this->newrow();
        $this->hline(10,195,$this->liney,'C');
        $this->setfieldwidth(10,10);
        $this->textbox('अ.नं',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(15);
        $this->textbox('कोड',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(125);
        $this->textbox('नाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(35);
        $this->textbox('कपात  रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->hline(10,195,$this->liney,'D');
    }

    function group()
    {
        $this->totalgroupcount=3;
        if ($this->dedcode!=0 )
        {
            $cond = " and d.deductioncode=".$this->dedcode;
        }
        $group_query_1 ="select 
            d.deductioncode
            ,d.branchcode
            ,d.dedseasonyear
            ,m.deductionnameuni
            ,f.contractorcode
            ,f.contractornameuni
            ,v.subcontractorcode
            ,v.subcontractornameuni
            ,c.banknameuni
            ,b.bankbranchnameuni
            ,d.deductionamount deductionamount
            from htbillheader t,htbilldeductiondetail d
            ,contractor f, subcontractor v
            ,billperiodheader h,deduction m
            ,bankbranch b, bank c
            where t.transactionnumber= d.billtransactionnumber
            and d.deductioncode= m.deductioncode
            and t.contractorcode=f.contractorcode
            and t.seasoncode=v.seasoncode
            and t.subcontractorcode=v.subcontractorcode
            and b.bankcode=c.bankcode(+)
            and d.branchcode=b.bankbranchcode(+)
            and t.billperiodtransnumber=h.billperiodtransnumber
            and nvl(d.deductionamount,0)>0 
            ".$cond."
            and t.billperiodtransnumber=".$this->billperiodtransnumber." 
            order by d.deductioncode
            ,d.branchcode
            ,d.dedseasonyear
            ,m.deductionnameuni
            ,f.contractornameuni
            ,f.contractorcode
            ,v.subcontractornameuni";
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
        $this->deductionsummary['DEDUCTIONAMOUNT']=0;
        $this->dedname=$group_row['DEDUCTIONNAMEUNI'];
        $this->srno=0;
        //if ($this->firstpage==1)
        //{
            $this->newpage(true);
            $this->firstpage=0;
        //}
    }

    function groupheader_2(&$group_row_1)
    {
        $this->branchsummary['DEDUCTIONAMOUNT']=0;
        if ($group_row_1['BANKBRANCHNAMEUNI']!='')
        {
            $this->setfieldwidth(100,10);
            $this->textbox($group_row_1['BANKBRANCHNAMEUNI'],$this->w,$this->x,'N','L',1,'siddhanta',11,'','','','');
            $this->newrow();
        }    
    }
    function groupheader_3(&$group_row_1)
    {
        $this->dedseasonyearsummary['DEDUCTIONAMOUNT']=0;
        if ($group_row_1['DEDSEASONYEAR']!='')
        {
            $this->setfieldwidth(70,10);
            $this->textbox($group_row_1['DEDSEASONYEAR'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
            $this->newrow();
        }
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
        $this->setfieldwidth(10,10);
        $this->textbox(++$this->srno,$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['CONTRACTORCODE'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(125);
        $this->textbox($group_row_1['CONTRACTORNAMEUNI'].' -'.$group_row_1['SUBCONTRACTORCODE'].' '.$group_row_1['SUBCONTRACTORNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(35);
        $this->textbox($group_row_1['DEDUCTIONAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow();
        $this->deductionsummary['DEDUCTIONAMOUNT']=$this->deductionsummary['DEDUCTIONAMOUNT']+$group_row_1['DEDUCTIONAMOUNT'];
        $this->branchsummary['DEDUCTIONAMOUNT']=$this->branchsummary['DEDUCTIONAMOUNT']+$group_row_1['DEDUCTIONAMOUNT'];
        $this->dedseasonyearsummary['DEDUCTIONAMOUNT']=$this->dedseasonyearsummary['DEDUCTIONAMOUNT']+$group_row_1['DEDUCTIONAMOUNT'];
    }

    function groupfooter_1(&$last_row)
    {     
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
           $this->setfieldwidth(30,104);
           $this->textbox('कपात एकूण',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
           $this->setfieldwidth(60);
           $this->textbox($this->deductionsummary['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','B');
           $this->newrow();
           $this->setfieldwidth(137,10);
           $this->textbox('अक्षरी->',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
           $this->setfieldwidth(112,25);
           $this->textbox(NumberToWords($this->deductionsummary['DEDUCTIONAMOUNT'],1),$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');  
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
           $this->setfieldwidth(40);
           $this->textbox('केन अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
           $this->setfieldwidth(40);
           $this->textbox('चिफ अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
           //$this->newpage(true);

      // }   
    }
    function groupfooter_2(&$last_row)
    {    
        if ($last_row['BANKBRANCHNAMEUNI']!='')
        {
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
            $this->setfieldwidth(30,104);
            $this->textbox('शाखा एकूण',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
            $this->setfieldwidth(47);
            $this->textbox($this->branchsummary['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','B');
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
    }

    function groupfooter_3(&$last_row)
    {     
        if ($last_row['DEDSEASONYEAR']!='')
        {
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
            $this->setfieldwidth(40,94);
            $this->textbox($last_row['DEDSEASONYEAR'].' एकूण',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
            $this->setfieldwidth(47);
            $this->textbox($this->dedseasonyearsummary['DEDUCTIONAMOUNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','B');
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C');
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
    function servicetrhrcategory()
    {
        $query = "select servicetrhrcatnameuni,servicetrhrcatnameeng from billperiodheader b,servicetrhrcategory s where payeecategorycode=2 and b.servicetrhrcategorycode=s.servicetrhrcategorycode and b.billperiodtransnumber=".$this->billperiodtransnumber;
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $i=1;
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))  
        {
            return $row['SERVICETRHRCATNAMEUNI'];
        }
        else
        {
            return '';
        }
    }
}    
?>