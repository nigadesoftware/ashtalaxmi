<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_L.php");
    include_once("../info/routine.php");
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class periodicalhttonnage extends reportbox
{
    Public $fromdate;
    Public $todate;
    Public $contractorcategorycode;
    Public $contractcategorycode;
    Public $servicetrhrcategorycode;
    Public $serialnumber;
    Public $contractsummary;
    Public $servicetrhrsummary;
    Public $contractorsummary;

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
        $this->group();
        $this->reportfooter();
    }

    function pageheader()
    {
        ob_flush();
        ob_start();
       
        $this->liney = 18;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->textbox('तोडणी वहातूक टनेज',270,10,'S','C',1,'siddhanta',12);
        $this->newrow(7);
        if ($this->fromdate!='' and $this->fromdate !='')
        {
            $fromdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $todt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $this->textbox('हंगाम : '.$_SESSION['yearperiodcode'].' दिनांक '.$this->fromdate.' पासून दिनांक '.$this->todate.' पर्यंत',270,10,'S','C',1,'siddhanta',11);
        }
        else
        {
            $this->textbox('हंगाम : '.$_SESSION['yearperiodcode'],180,10,'S','C',1,'siddhanta',11);
        }
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->newrow(7);
        $this->setfieldwidth(15,10);
        $this->textbox('अ.न.',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        $this->setfieldwidth(60);
        $this->textbox('सब कंत्राटदार',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox('वाहन',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox('वाहन प्रकार',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(15);
        $this->textbox('खेपा',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('वहा.टनेज',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('तो.टनेज',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox('खाते',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(70);
        $this->textbox('बँक',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->hline(10,270,$this->liney,'C');
        $this->newrow();
        $this->hline(10,270,$this->liney,'C');
    }

    function group()
    {
        $this->totalgroupcount=3;
        $this->summary['NETCANETONNAGE']=0;
        $this->summary['GROSSAMOUNT']=0;
        $cond='t.seasoncode='.$_SESSION['yearperiodcode'];
        if ($this->fromdate != '')
            $fdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
        if ($this->todate != '')
            $tdt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
        if ($fdt !='' and $tdt !='')
            $cond = $cond." and t.weighmentdate>= '{$fdt}' and t.weighmentdate<= '{$tdt}'";
        if ($this->contractorcategorycode!='' and $this->contractorcategorycode!=0)
            $cond = $cond.' and t.contractorcategorycode='.$this->contractorcategorycode;
        if ($this->contractcategorycode!='' and $this->contractcategorycode!=0)
            $cond = $cond.' and t.contractcategorycode='.$this->contractcategorycode;
        if ($this->servicetrhrcategorycode!='' and $this->servicetrhrcategorycode!=0)
            $cond = $cond.' and t.servicetrhrcategorycode='.$this->servicetrhrcategorycode;
        

            $group_query_1 ="select t.*
            ,gettripcount(".$_SESSION['yearperiodcode'].",t.SERVICETRHRCATEGORYCODE,t.SUBCONTRACTORCODE,t.vehiclecode,'{$fdt}','{$tdt}') nooftrips 
            from (select t.CONTRACTORCATEGORYCODE,t.SERVICETRHRCATEGORYCODE,t.CONTRACTCATEGORYCODE
            ,c.contractorcategorynameuni,t.SUBCONTRACTORCODE,s.servicetrhrcatnameuni,y.contractcategorynameuni
            ,t.SUBCONTRACTORNAMEUNI,t.VEHICLECODE,t.VEHICLENUMBER
            ,t.VEHICLECATEGORYCODE,v.vehiclecategorynameuni,r.accountnumber,bb.shortname,b.bankbranchnameuni,sum(t.transportationtonnage) transportationtonnage,sum(t.harvestingtonnage) harvestingtonnage 
            from HT_TONNAGE t,servicetrhrcategory s,contractorcategory c,contractcategory y, vehiclecategory v,contractor r,bankbranch b, bank bb
            where t.SERVICETRHRCATEGORYCODE=s.servicetrhrcategorycode and t.CONTRACTORCATEGORYCODE=c.contractorcategorycode 
            and t.CONTRACTCATEGORYCODE=y.contractcategorycode and t.VEHICLECATEGORYCODE=v.vehiclecategorycode 
            and t.contractorcode=r.contractorcode and r.bankbranchcode=b.bankbranchcode(+) and b.bankcode=bb.bankcode(+)
            and {$cond}
            group by t.CONTRACTORCATEGORYCODE,t.SERVICETRHRCATEGORYCODE,t.CONTRACTCATEGORYCODE,c.contractorcategorynameuni
            ,t.SUBCONTRACTORCODE,s.servicetrhrcatnameuni,y.contractcategorynameuni,t.SUBCONTRACTORNAMEUNI,t.VEHICLECODE,t.VEHICLENUMBER
            ,t.VEHICLECATEGORYCODE,v.vehiclecategorynameuni,r.accountnumber,bb.shortname,b.bankbranchnameuni
            order by t.CONTRACTORCATEGORYCODE,t.SERVICETRHRCATEGORYCODE,t.CONTRACTCATEGORYCODE,c.contractorcategorynameuni
            ,t.SUBCONTRACTORCODE,s.servicetrhrcatnameuni,y.contractcategorynameuni,t.SUBCONTRACTORNAMEUNI,t.VEHICLECODE,t.VEHICLENUMBER
            ,t.VEHICLECATEGORYCODE,v.vehiclecategorynameuni,r.accountnumber,bb.shortname,b.bankbranchnameuni)t";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $this->serialnumber = 0;
        $this->newpage(True);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->hline(10,270,$this->liney,'D'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
    }
    
    function groupheader_1(&$group_row_1)
    {
        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,270,$this->liney,'D');  
            $this->newpage(True);
        }
        $this->contractortrhrsummary['TRANSPORTATIONTONNAGE'] = 0;
        $this->contractortrhrsummary['HARVESTINGTONNAGE'] = 0;

    }

    function groupheader_2(&$group_row_1)
    {
        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,270,$this->liney,'D');  
            $this->newpage(True);
        }
        $this->servicetrhrsummary['TRANSPORTATIONTONNAGE'] = 0;
        $this->servicetrhrsummary['HARVESTINGTONNAGE'] = 0;

    }

    function groupheader_3(&$group_row_1)
    {
        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,270,$this->liney,'D');  
            $this->newpage(True);
        }
        $this->contracttrhrsummary['TRANSPORTATIONTONNAGE'] = 0;
        $this->contracttrhrsummary['HARVESTINGTONNAGE'] = 0;
        $this->setfieldwidth(15,10);
        $this->setfieldwidth(125);
        $this->textbox($group_row_1['CONTRACTORCATEGORYNAMEUNI'].' - '.$group_row_1['SERVICETRHRCATNAMEUNI'].' - '.$group_row_1['CONTRACTCATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->newrow();
        
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
            //$this->hline(10,200,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
        //$this->hline(10,200,$this->liney-2,'D'); 
        $this->serialnumber=$this->serialnumber+1;
        $this->setfieldwidth(15,10);
        $this->textbox( $this->serialnumber,$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(60);
        $this->textbox($group_row_1['SUBCONTRACTORNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['VEHICLENUMBER'],$this->w,$this->x,'S','L',1,'verdana',8,'','','','');
        $this->setfieldwidth(20);
        if ($group_row_1['VEHICLENUMBER']!='')
        $this->textbox($group_row_1['VEHICLECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        else
        $this->textbox('',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['NOOFTRIPS'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['TRANSPORTATIONTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['HARVESTINGTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['ACCOUNTNUMBER'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',8,'','','','');
        $this->setfieldwidth(70);
        $this->textbox($group_row_1['SHORTNAME'].','.$group_row_1['BANKBRANCHNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',8,'','','','');
        $this->servicetrhrsummary['TRANSPORTATIONTONNAGE'] = $this->servicetrhrsummary['TRANSPORTATIONTONNAGE']+$group_row_1['TRANSPORTATIONTONNAGE'];
        $this->servicetrhrsummary['HARVESTINGTONNAGE'] = $this->servicetrhrsummary['HARVESTINGTONNAGE']+$group_row_1['HARVESTINGTONNAGE'];
        $this->contracttrhrsummary['TRANSPORTATIONTONNAGE'] = $this->contracttrhrsummary['TRANSPORTATIONTONNAGE']+$group_row_1['TRANSPORTATIONTONNAGE'];
        $this->contracttrhrsummary['HARVESTINGTONNAGE'] = $this->contracttrhrsummary['HARVESTINGTONNAGE']+$group_row_1['HARVESTINGTONNAGE'];
        $this->contractortrhrsummary['TRANSPORTATIONTONNAGE'] = $this->contractortrhrsummary['TRANSPORTATIONTONNAGE']+$group_row_1['TRANSPORTATIONTONNAGE'];
        $this->contractortrhrsummary['HARVESTINGTONNAGE'] = $this->contractortrhrsummary['HARVESTINGTONNAGE']+$group_row_1['HARVESTINGTONNAGE'];
        if ($this->isnewpage(20))
        {
            $this->newrow();
            //$this->hline(10,270,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
        
    }

    function groupfooter_1(&$group_row_1)
    {     
        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,270,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            //$this->hline(10,200,$this->liney-2,'C'); 
        }
        $this->setfieldwidth(15,10);
        $this->setfieldwidth(110);
        $this->textbox($group_row_1['CONTRACTORCATEGORYNAMEUNI'].' एकूण',$this->w,$this->x,'S','R',1,'siddhanta',9,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->contractortrhrsummary['TRANSPORTATIONTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->contractortrhrsummary['HARVESTINGTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(6);
        $this->hline(10,270,$this->liney,'C');
        $this->newrow(3);
        $this->newpage(True);

    }

    function groupfooter_2(&$group_row_1)
    {      
        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,270,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            //$this->hline(10,200,$this->liney-2,'C'); 
        }
        $this->setfieldwidth(15,10);
        $this->setfieldwidth(110);
        $this->textbox($group_row_1['CONTRACTORCATEGORYNAMEUNI'].' - '.$group_row_1['SERVICETRHRCATNAMEUNI'].' एकूण',$this->w,$this->x,'S','R',1,'siddhanta',9,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->servicetrhrsummary['TRANSPORTATIONTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->servicetrhrsummary['HARVESTINGTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->newrow(6);
        $this->hline(10,270,$this->liney,'C');
        $this->newrow(3);

        
    }
    function groupfooter_3(&$group_row_1)
    {      
        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,270,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            //$this->hline(10,200,$this->liney-2,'C'); 
        }
        $this->setfieldwidth(15,10);
        $this->setfieldwidth(110);
        $this->textbox($group_row_1['CONTRACTORCATEGORYNAMEUNI'].' - '.$group_row_1['SERVICETRHRCATNAMEUNI'].' - '.$group_row_1['CONTRACTCATEGORYNAMEUNI'].' एकूण',$this->w,$this->x,'S','R',1,'siddhanta',9,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->contracttrhrsummary['TRANSPORTATIONTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->setfieldwidth(30);
        $this->textbox($this->contracttrhrsummary['HARVESTINGTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->hline(10,270,$this->liney,'C');
        $this->newrow(6);
        $this->hline(10,270,$this->liney,'C');
        $this->newrow(3);
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
            $this->hline(10,270,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            $this->hline(10,270,$this->liney-2,'C'); 
        }
        

        $this->setfieldwidth(20,60);
        $this->textbox('क्लार्क',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('केन अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
    
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
    function tslhtcontractortonnageexport()
    {
        $cond='t.SEASONCODE='.$_SESSION['yearperiodcode'].'';
        if ($this->fromdate != '')
            $fdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
        if ($this->todate != '')
            $tdt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
        if ($fdt !='' and $tdt !='')
            $cond = $cond." and t.weighmentdate>= '{$fdt}' and t.weighmentdate<= '{$tdt}'";
        if ($this->contractorcategorycode!='' and $this->contractorcategorycode!=0)
            $cond = $cond.' and t.contractorcategorycode='.$this->contractorcategorycode;
        if ($this->contractcategorycode!='' and $this->contractcategorycode!=0)
            $cond = $cond.' and t.contractcategorycode='.$this->contractcategorycode;
        if ($this->servicetrhrcategorycode!='' and $this->servicetrhrcategorycode!=0)
            $cond = $cond.' and t.servicetrhrcategorycode='.$this->servicetrhrcategorycode;
        

            $group_query_1 ="select t.CONTRACTORCATEGORYCODE,t.SERVICETRHRCATEGORYCODE,t.CONTRACTCATEGORYCODE
            ,c.contractorcategorynameeng,t.SUBCONTRACTORCODE,s.servicetrhrcatnameeng,y.contractcategorynameeng
            ,t.SUBCONTRACTORNAMEeng,t.VEHICLECODE,t.VEHICLENUMBER
            ,t.VEHICLECATEGORYCODE,v.vehiclecategorynameeng,r.accountnumber,bb.shortname,b.bankbranchnameeng,sum(t.transportationtonnage) transportationtonnage,sum(t.harvestingtonnage) harvestingtonnage 
            from HT_TONNAGE t,servicetrhrcategory s,contractorcategory c,contractcategory y, vehiclecategory v,contractor r,bankbranch b, bank bb
            where {$cond} and
            t.SERVICETRHRCATEGORYCODE=s.servicetrhrcategorycode and t.CONTRACTORCATEGORYCODE=c.contractorcategorycode 
            and t.CONTRACTCATEGORYCODE=y.contractcategorycode and t.VEHICLECATEGORYCODE=v.vehiclecategorycode 
            and t.contractorcode=r.contractorcode and r.bankbranchcode=b.bankbranchcode(+) and b.bankcode=bb.bankcode(+) 
            
            group by t.CONTRACTORCATEGORYCODE,t.SERVICETRHRCATEGORYCODE,t.CONTRACTCATEGORYCODE,c.contractorcategorynameeng
            ,t.SUBCONTRACTORCODE,s.servicetrhrcatnameeng,y.contractcategorynameeng,t.SUBCONTRACTORNAMEeng,t.VEHICLECODE,t.VEHICLENUMBER
            ,t.VEHICLECATEGORYCODE,v.vehiclecategorynameeng,r.accountnumber,bb.shortname,b.bankbranchnameeng
            order by t.CONTRACTORCATEGORYCODE,t.SERVICETRHRCATEGORYCODE,t.CONTRACTCATEGORYCODE,c.contractorcategorynameeng
            ,t.SUBCONTRACTORCODE,s.servicetrhrcatnameeng,y.contractcategorynameeng,t.SUBCONTRACTORNAMEeng,t.VEHICLECODE,t.VEHICLENUMBER
            ,t.VEHICLECATEGORYCODE,v.vehiclecategorynameeng,r.accountnumber,bb.shortname,b.bankbranchnameeng";
        
           $result = oci_parse($this->connection, $group_query_1);
           $r = oci_execute($result);
           $response = array();
           $filename='htcontractortonnagelist.csv';
           //$fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');
           $fp1=fopen('php://memory', 'w');
           $deductionnamelist="Start,Sr No,Contractor Category,Service Category,Contractor Code,Contractor Name,Sub Contractor Code,Sub Contractor,Vehicle Number,Vehicle Category,Tr Tonnage,Hr Tonnage,Branch,Account Number,End";
           fputcsv($fp1, array($deductionnamelist), $delimiter = ',', $enclosure = '#');
           $srno=1;
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                if ($row['VEHICLENUMBER']!='')
                    $vehiclecategory = $row['VEHICLECATEGORYNAMEENG'];
                else 
                    $vehiclecategory = "";
                $accountnumber= "'".$row['ACCOUNTNUMBER'];
                $rowstring="".",".$srno.",".$row['CONTRACTORCATEGORYNAMEENG'].",".$row['SERVICETRHRCATNAMEENG'].",".$row['CONTRACTORCODE'].",".$row['CONTRACTORNAMEENG'].",".$row['SUBCONTRACTORCODE'].",".$row['SUBCONTRACTORNAMEENG'].",".$row['VEHICLENUMBER'].",".$vehiclecategory.",".$row['TRANSPORTATIONTONNAGE'].",".$row['HARVESTINGTONNAGE'].",".$row['SHORTNAME'].' '.$row['BANKBRANCHNAMEENG'].",".$accountnumber.",";
                fputcsv($fp1, array($rowstring), $delimiter = ',', $enclosure = '#');
                $srno++;
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