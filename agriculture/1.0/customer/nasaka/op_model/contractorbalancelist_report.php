<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class contractorbalancelist extends reportbox
{
    public $fromdate;
    public $todate;
    public $circlesummary;
    public $summary;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueType', '', 32);
    	// create new PDF document
	    $this->pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Circle');
        $this->pdf->SetKeywords('CIRCRUSH_000.MR');
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));
       // $title = str_pad(' ', 25).'Rajaramnagar, Tal - Dindori Dist - Nashik';
    	//$this->pdf->SetHeaderData('', 0,str_pad(' ', 21).'Kadwa S.S.K. Ltd.' ,$title);
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
        $lg['a_meta_language'] = 'mr';
        $lg['w_page'] = 'Page - ';
        // set some language-dependent strings (optional)
	    $this->pdf->setLanguageArray($lg);
	}

    function startreport()
    {
        $this->groupfield1='DR_AMT';
        $this->groupfield2='CR_AMT';
        $this->groupfield3='BAL';
        $this->resetgroupsummary(0);
        $this->group();
        $this->reportfooter();
    }

	function pageheader()
    {
        $this->liney = 18;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->newrow(5);
        $this->textbox('कपातवाईज कंत्राटदार लिस्ट '.$_SESSION['yearperiodcode'],180,10,'S','C',1,'siddhanta',13);
       
        
        $this->newrow();
        
        $this->hline(10,195,$this->liney-1,'C');
        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);       
        $this->textbox('अनु.क्र',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('कोड',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(55);
        $this->textbox('नाव',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->textbox('वाहन नं.',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('नावे',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('जमा',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('शिल्लक',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->newrow(7);
        $this->hline(10,195,$this->liney-1,'C'); 
    }
   
    function group()
    {
        $this->totalgroupcount=1;
       $cond="1=1";
        $cond='m.seasonyearcode='.$_SESSION['yearperiodcode'];
        
        if ($this->deductioncode!=0)
        {
            if ($cond=="")
                $cond="m.deductioncode=".$this->deductioncode;
            else
                $cond=$cond." and m.deductioncode=".$this->deductioncode;
        }
        $group_query_1 = " select m.seasonyearcode,m.deductioncode,m.subcontractorcode,dd.deductionnameuni
        ,s.subcontractornameuni
        ,row_number()over(partition by m.deductioncode order by m.subcontractorcode)Sr_no
        ,v.vehiclenumber,dr_amt,cr_amt,bal from
        (
        select seasonyearcode,contractorcode,subcontractorcode,vehiclecode,deductioncode
        ,sum(nvl(dr_amt,0))dr_amt,sum(nvl(cr_amt,0))cr_amt,sum(nvl(dr_amt,0))-sum(nvl(cr_amt,0)) bal from
        (
        select t.seasonyearcode,t.contractorcode,t.subcontractorcode,t.vehiclecode,t.deductioncode,
        t.claimamount dr_amt,0 cr_amt  from farmerdeductionclaim t where t.payeecategorycode=2
        union all
        select t.seasoncode,t.contractorcode,t.subcontractorcode,t.vehiclecode,d.deductioncode,0 ndr_amt
        ,d.deductionamount ncr_amt
        from htbillheader t,htbilldeductiondetail d where t.transactionnumber=d.billtransactionnumber
        )group by seasonyearcode,contractorcode,subcontractorcode,vehiclecode,deductioncode   
        )m ,contractor c,subcontractor s,vehicle v,deduction dd
        where {$cond} and 
         m.contractorcode=c.contractorcode(+)  
        and m.seasonyearcode=s.seasoncode(+)
        and m.subcontractorcode=s.subcontractorcode(+)
        and m.seasonyearcode=v.seasoncode(+)
        and m.vehiclecode=v.vehiclecode(+)
        and m.deductioncode=dd.deductioncode(+)
        order by m.deductioncode,m.subcontractorcode,v.vehiclecode";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->sumgroupsummary($group_row_1,0);
            $this->sumgroupsummary($group_row_1,1);
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
    }
    function groupheader_1(&$group_row_1)
    {
        
        $this->resetgroupsummary(1);
        $this->setfieldwidth(190,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('कपात कोड :'.$group_row_1['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w-5);
        $this->newrow();
        $this->hline(10,195,$this->liney-1,'C');        
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
    function detail_1(&$group_row_1)
    {
        ob_flush();
        ob_start();
        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($group_row_1['SR_NO'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
       
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['SUBCONTRACTORCODE'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(55);
        $this->textbox($group_row_1['SUBCONTRACTORNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->textbox($group_row_1['VEHICLENUMBER'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['DR_AMT'],$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['CR_AMT'],$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['BAL'],$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->newrow();
        if ($this->isnewpage(10))
        {
            $this->hline(10,195,$this->liney-1,'C');        
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
        }
    }


    function groupfooter_1(&$group_row_1)
    {   
        $this->setfieldwidth(40,95);
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox($group_row_1['DEDUCTIONNAMEUNI'].' एकूण',$this->w,$this->x,'N','L',1,'siddhanta',11,'','','','');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
     
        $this->setfieldwidth(20);    
        $this->textbox(number_format($this->showgroupsummary(1,'DR_AMT'),0,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
         $this->textbox(number_format($this->showgroupsummary(1,'CR_AMT'),0,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
         $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        
         $this->setfieldwidth(20);  
          $this->textbox(number_format($this->showgroupsummary(1,'BAL'),0,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
          $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
  
        
        $this->hline(95,195,$this->liney+7,'C');   
        $this->newrow();
        $this->newpage(True);

    }

    function groupfooter_2(&$group_row_1)
    {      
    }
    function groupfooter_3(&$group_row_2)
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

    function reportfooter()
    { 
       /*  $this->setfieldwidth(40,95);
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox('एकूण एकंदर ',$this->w,$this->x,'N','L',1,'siddhanta',11,'','','','');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
     
        $this->setfieldwidth(20);    
        $this->textbox(number_format($this->showgroupsummary(0,'DR_AMT'),0,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
         $this->textbox(number_format($this->showgroupsummary(0,'CR_AMT'),0,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
         $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        
         $this->setfieldwidth(20);  
          $this->textbox(number_format($this->showgroupsummary(0,'BAL'),0,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
          $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
  
        
        $this->hline(95,195,$this->liney+7,'C');   
        $this->newpage(True); */
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