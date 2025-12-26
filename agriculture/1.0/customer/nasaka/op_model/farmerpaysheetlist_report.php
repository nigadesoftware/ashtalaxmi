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
class farmerpaysheetlist extends reportbox
{
    public $circlecode;
    public $villagecode;
    public $farmercode;
    public $billperiodtransnumber;
    public $msubtitle;
    public $circlename;
    public $villagename;
    public $farmercategoryname;
    public $farmercategorysummary;
    public $circlesummary;
    public $villagesummary;
    public $summary;
    public $isfirstpage;

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
        $this->group();
        $this->reportfooter();
    }

    function pageheader()
    {
        ob_flush();
        ob_start();
       
        $this->liney = 13;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->newrow(4);
        $this->setfieldwidth(60,80);
        $this->textbox('ऊस पुरवठादार यादी',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        $this->newrow();
        $this->setfieldwidth(195,10);
        $this->textbox($this->msubtitle,$this->w,$this->x,'S','C',1,'siddhanta',8,'','','','');
        $this->newrow();
        $this->setfieldwidth(150,10);
        $this->textbox($this->farmercategoryname.' गट : '.$this->circlename,$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        
        $this->newrow();
        $this->hline(10,405,$this->liney,'C');
        
        $this->setfieldwidth(20,10);
        $this->textbox('अ.क्र.',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox('कोड',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(70);
        $this->textbox('ऊस उत्पादक नाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('गाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('टनेज',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox('रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->hline(10,405,$this->liney,'C');
    }

    function group()
    {
        $this->totalgroupcount=3;
        $this->summary['NETCANETONNAGE']=0;
        $this->summary['GROSSAMOUNT']=0;
        $this->summary['GROSSDEDUCTION']=0;
        $this->summary['NETAMOUNT']=0;
        $cond='';
        if ($this->farmercode!='' and $this->farmercode!=0)
            $cond = $cond.' and farmercode='.$this->farmercode;
        if ($this->villagecode!='' and $this->villagecode!=0)
            $cond = $cond.' and villagecode='.$this->villagecode;
        if ($this->circlecode!='' and $this->circlecode!=0)
            $cond = $cond.' and circlecode='.$this->circlecode;
        
        if ($cond!='')
            $cond = ' and '.$cond;

            $group_query_1 ="select  
            c.farmercategorycode
            ,r.circlecode
            ,v.villagecode
            ,r.circlenameuni
            ,v.villagenameuni,
            t.transactionnumber,t.billnumber,
            to_char(h.paymentdate,'dd/MM/yyyy') paymentdate
            ,f.farmercode,f.farmernameuni,v.villagenameuni,t.noofslips
            ,t.netcanetonnage,t.rateperton,t.grossamount ,t.grossdeduction
            ,t.netcanetonnage,t.rateperton,t.grossamount,t.grossdeduction
            ,t.netamount,t.accountnumber,b.bankbranchnameuni,bk.banknameuni,bk.shortname 
            ,c.farmercategorynameuni
            from farmerbillheader t,farmer f, village v,circle r
            , billperiodheader h,bankbranch b,farmercategory c,bank bk
            where t.farmercode=f.farmercode
            and f.villagecode=v.villagecode
            and t.billperiodtransnumber=h.billperiodtransnumber
            and t.farmercategorycode=c.farmercategorycode
            and v.circlecode=r.circlecode
            and b.bankcode=bk.bankcode(+) 
            and t.bankbranchcode=b.bankbranchcode(+)
            and t.billperiodtransnumber=".$this->billperiodtransnumber." 
            {$cond} 
            order by 
            c.farmercategorycode
            ,r.circlecode
            ,v.villagecode
            ,t.billnumber";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->detail_2($group_row_1['TRANSACTIONNUMBER']);  
            $this->hline(10,405,$this->liney,'D'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
    }
    
    function groupheader_1(&$group_row_1)
    {
        $this->farmercategoryname=$group_row_1['FARMERCATEGORYNAMEUNI'];
        $this->farmercategorysummary['NETCANETONNAGE']=0;
        $this->farmercategorysummary['GROSSAMOUNT']=0;
        $this->farmercategorysummary['GROSSDEDUCTION']=0;
        $this->farmercategorysummary['NETAMOUNT']=0;
        /* if ($this->isfirstpage == '')
        {
            $this->circlename=$group_row_1['CIRCLENAMEUNI'];
            $this->newpage(True);
            $this->isfirstpage=1;
        } */
    }

    function groupheader_2(&$group_row_1)
    {
        //if ($this->isfirstpage != '')
        //{
            $this->circlename=$group_row_1['CIRCLENAMEUNI'];
            $this->newpage(True);
        //}
        $this->circlesummary['NETCANETONNAGE']=0;
        $this->circlesummary['GROSSAMOUNT']=0;
        $this->circlesummary['GROSSDEDUCTION']=0;
        $this->circlesummary['NETAMOUNT']=0;
    }

    function groupheader_3(&$group_row_1)
    {
        //$this->newrow();
        $this->villagename=$group_row_1['VILLAGENAMEUNI'];
        $this->setfieldwidth(40,10);
        $this->textbox($this->villagename,$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->villagesummary['NETCANETONNAGE']=0;
        $this->villagesummary['GROSSAMOUNT']=0;
        $this->villagesummary['GROSSDEDUCTION']=0;
        $this->villagesummary['NETAMOUNT']=0;
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
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
        //$this->hline(10,405,$this->liney-2,'D'); 
        $this->setfieldwidth(20,10);
        $this->textbox($group_row_1['BILLNUMBER'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['FARMERCODE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(70);
        $this->textbox($group_row_1['FARMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['NETCANETONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['GROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['GROSSDEDUCTION'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->pdf->SetTextColor(150, 0, 0);
        $this->textbox($group_row_1['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->pdf->SetTextColor(0, 0, 0);
        $this->setfieldwidth(5);
        $this->setfieldwidth(100);
        $this->textbox($group_row_1['BANKNAMEUNI'].', '.$group_row_1['BANKBRANCHNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(45);
        $this->textbox($group_row_1['ACCOUNTNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10,'','','','');
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
        $this->farmercategorysummary['NETCANETONNAGE']=$this->farmercategorysummary['NETCANETONNAGE']+$group_row_1['NETCANETONNAGE'];
        $this->farmercategorysummary['GROSSAMOUNT']=$this->farmercategorysummary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->farmercategorysummary['GROSSDEDUCTION']=$this->farmercategorysummary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->farmercategorysummary['NETAMOUNT']=$this->farmercategorysummary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        $this->circlesummary['NETCANETONNAGE']=$this->circlesummary['NETCANETONNAGE']+$group_row_1['NETCANETONNAGE'];
        $this->circlesummary['GROSSAMOUNT']=$this->circlesummary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->circlesummary['GROSSDEDUCTION']=$this->circlesummary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->circlesummary['NETAMOUNT']=$this->circlesummary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        $this->villagesummary['NETCANETONNAGE']=$this->villagesummary['NETCANETONNAGE']+$group_row_1['NETCANETONNAGE'];
        $this->villagesummary['GROSSAMOUNT']=$this->villagesummary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->villagesummary['GROSSDEDUCTION']=$this->villagesummary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->villagesummary['NETAMOUNT']=$this->villagesummary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        $this->summary['NETCANETONNAGE']=$this->summary['NETCANETONNAGE']+$group_row_1['NETCANETONNAGE'];
        $this->summary['GROSSAMOUNT']=$this->summary['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->summary['GROSSDEDUCTION']=$this->summary['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->summary['NETAMOUNT']=$this->summary['NETAMOUNT']+$group_row_1['NETAMOUNT'];
        
    }


    function detail_2($transactionnumber)
    { 
    }
    function groupfooter_1(&$group_row_3)
    {     
        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
        }
        
        
        $this->setfieldwidth(85,50);
        $this->textbox($group_row_3['FARMERCATEGORYNAMEUNI'].' एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(35);
        $this->textbox($this->farmercategorysummary['NETCANETONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->farmercategorysummary['GROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow();
    }

    function groupfooter_2(&$group_row_1)
    {      
        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
        }
        
        $this->setfieldwidth(85,50);
        $this->textbox($group_row_1['CIRCLENAMEUNI'].' गट एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(35);
        $this->textbox($this->circlesummary['NETCANETONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(10);
        $this->setfieldwidth(20);
        $this->textbox($this->circlesummary['GROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->newrow();
    }
    function groupfooter_3(&$group_row_2)
    {      
        /* if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
        }

        $this->setfieldwidth(70,200);
        $this->textbox('गाव एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox($this->villagesummary['NETCANETONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
        $this->setfieldwidth(30);
        $this->textbox($this->villagesummary['GROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->villagesummary['GROSSDEDUCTION'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->villagesummary['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow(); */

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

    function reportfooter()
    {
        if ($this->isnewpage(30))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
        }
        
        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
       

        $this->setfieldwidth(85,50);
        $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(35);
        $this->textbox($this->summary['NETCANETONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->summary['GROSSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        

        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }  
        $this->newrow(15);
        $this->setfieldwidth(20,30);
        $this->textbox('क्लार्क',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(50);
        $this->textbox('केन अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(50);
        $this->textbox('चिफ अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        
    
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