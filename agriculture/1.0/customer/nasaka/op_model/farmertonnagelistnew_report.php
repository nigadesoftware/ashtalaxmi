<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class tonnage extends reportbox
{
    public $fromdate;
    public $todate;
    public $sr_no;
    public $pumpcode;
    public $exportcsvfile;
    public $pumpsummary;
    public $vehiclesummary;
    public $summary;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7,$subject='',$pdffilename='',$papersize='A3',$orientation='P')
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
        $this->pdf->SetSubject('Pump Periodical Summary');
        $this->pdf->SetKeywords('PUMPERSUM_000.MR');
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));
       // $title = str_pad(' ', 30).'Rajaramnagar, Tal - Dindori Dist - Nashik';
    	//$this->pdf->SetHeaderData('', 0,str_pad(' ', 21).'Twentyone Sugars Limited' ,$title);
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
        $this->group();
        $this->reportfooter();
    }

	function pageheader()
    {
        $this->liney = 15;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->newrow(7);
        $this->textbox('ऊस नोंद असून गाळपासाठी न आलेल्या शेतकरी लिस्ट',180,10,'S','C',1,'siddhanta',12);
        $this->newrow(7);
        if ($this->fromdate!='' and $this->fromdate !='')
        {
            $fromdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $todt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $this->textbox('हंगाम : '.$_SESSION['yearperiodcode'].' दिनांक '.$this->fromdate.' पासून दिनांक '.$this->todate.' पर्यंत',180,10,'S','C',1,'siddhanta',11);
        }
        else
        {
            $this->textbox('हंगाम : '.$_SESSION['yearperiodcode'],180,10,'S','C',1,'siddhanta',11);
        }

        $this->newrow(7);
        $this->hline(10,190,$this->liney,'C');
        $this->setfieldwidth(15,10);
        $this->textbox('अनु.क्र.',$this->w,$this->x,'S','L',1,'siddhanta',10);
        $this->setfieldwidth(25);
        $this->textbox('सभासद कोड',$this->w,$this->x,'S','L',1,'siddhanta',10);
        $this->setfieldwidth(50);
        $this->textbox('सभासद नाव',$this->w,$this->x,'S','L',1,'siddhanta',10);
        $this->setfieldwidth(25);
        $this->textbox('गाव',$this->w,$this->x,'S','L',1,'siddhanta',10);
        $this->setfieldwidth(25);
        $this->textbox('गट',$this->w,$this->x,'S','L',1,'siddhanta',10); 

        $this->setfieldwidth(25);
        $this->textbox('नोंद(हे)',$this->w,$this->x,'S','L',1,'siddhanta',10); 
        $this->setfieldwidth(30);
        $this->textbox(' टनेज',$this->w,$this->x,'S','L',1,'siddhanta',10); 
        
        $this->newrow();
        $this->hline(10,190,$this->liney-2,'C');
        
    }
   

    function group()
    {
        $this->totalgroupcount=0;
        $this->summary['WT']=0;
        $this->summary['AREA']=0;
        $this->sr_no=1;
        $cond = '1=1';

        if ($this->contractorcode!=0)
        {
            if ($cond=="")
                $cond="d.contractorcode=".$this->contractorcode;
            else
                $cond=$cond." and d.contractorcode=".$this->contractorcode;
        } 
        if ($this->fromdate!='' and $this->todate !='')
        {
            $fromdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $todt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $cond = $cond." and w.weighmentdate>='".$fromdt."' and w.weighmentdate<='".$todt."'";
        }
        
        $cond = $cond." and w.seasoncode=".$_SESSION['yearperiodcode'];

       
        $group_query_1 = " select c.centrecode,c.centrenameuni
        ,v.villagecode,v.villagenameuni
        ,f.farmercode,f.farmernameuni
        ,sum(area)area 
        from plantationheader h
        ,farmer f,village v,centre c
        where h.farmercode=f.farmercode
        and f.villagecode=v.villagecode
        and v.centrecode=c.centrecode
        and h.seasoncode=".$_SESSION['yearperiodcode']."
        and h.plotnumber not in(select distinct(f.plotnumber) from fieldslip f
         where f.seasoncode=".$_SESSION['yearperiodcode'].")
        group by c.centrecode,c.centrenameuni
        ,v.villagecode,v.villagenameuni
        ,f.farmercode,f.farmernameuni
        order by c.centrecode,v.villagecode,f.farmercode";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
    }
    function groupheader_1(&$group_row_1)
    {
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
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
       // $dt=DateTime::createFromFormat('d-M-y',$group_row_1['TRANSACTIONDATE'])->format('d/m/y');

       //$ht=$this->height($group_row_1['NNAME'],40);

        $this->setfieldwidth(15,10);
        $this->textbox($this->sr_no,$this->w,$this->x,'N','C',1,'siddhanta',10);
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['FARMERCODE'],$this->w,$this->x,'N','L',1,'siddhanta',10);
        $this->setfieldwidth(50);
        $this->textbox($group_row_1['FARMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10);
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'N','L',1,'siddhanta',10);
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['CENTRENAMEUNI'],$this->w,$this->x,'N','L',1,'siddhanta',10); 
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($group_row_1['AREA'],2),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',10); 
        $this->setfieldwidth(30);
        $this->textbox($this->numformat($group_row_1['WT'],3),$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',10); 
       
      
        $this->summary['WT']= $this->summary['WT']+$group_row_1['WT'];
        $this->summary['AREA']= $this->summary['AREA']+$group_row_1['AREA'];
        $this->sr_no=$this->sr_no+1;
        
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney,'C');  
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
        }
    }


    function groupfooter_1(&$group_row_1)
    { 
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
        
        if ($this->isnewpage(25))
        {
            //$this->newrow();
            //$this->hline(10,200,$this->liney-2,'C'); 
            $this->newpage(True,True);
        }   
        else
        {
            //$this->newrow();
            //$this->hline(10,200,$this->liney-2,'C'); 
        }

        $this->setfieldwidth(15,10);      
        $this->setfieldwidth(25);        
        $this->setfieldwidth(50);        
        $this->setfieldwidth(25);
        $this->setfieldwidth(25);
        $this->textbox('एकूण एकंदर ',$this->w,$this->x,'N','L',1,'siddhanta',10); 
        $this->setfieldwidth(25);
        $this->textbox($this->numformat( $this->summary['AREA'],2),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10); 
        $this->setfieldwidth(30);
        $this->textbox($this->numformat( $this->summary['WT'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10); 
       
        $this->newrow(15);
        $this->setfieldwidth(20,60);

        $this->textbox('क्लार्क',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('केन अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','');
    
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

    function export()
    {   
        if ($this->contractorcode!=0)
        {
            if ($cond=="")
                $cond="d.contractorcode=".$this->contractorcode;
            else
                $cond=$cond." and d.contractorcode=".$this->contractorcode;
        } 
        if ($this->fromdate!='' and $this->todate !='')
        {
            $fromdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $todt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $cond = $cond." and w.weighmentdate>='".$fromdt."' and w.weighmentdate<='".$todt."'";
        }
        
        $cond = $cond." and w.seasoncode=".$_SESSION['yearperiodcode'];

       
        $group_query_1 = " select c.centrecode,c.centrenameeng
        ,v.villagecode,v.villagenameeng
        ,f.farmercode,f.farmernameeng
        ,sum(area)area 
        from plantationheader h
        ,farmer f,village v,centre c
        where h.farmercode=f.farmercode
        and f.villagecode=v.villagecode
        and v.centrecode=c.centrecode
        and h.seasoncode=".$_SESSION['yearperiodcode']."
        and h.plotnumber not in(select distinct(f.plotnumber) from fieldslip f
         where f.seasoncode=".$_SESSION['yearperiodcode'].")
        group by c.centrecode,c.centrenameeng
        ,v.villagecode,v.villagenameeng
        ,f.farmercode,f.farmernameeng
        order by c.centrecode,v.villagecode,f.farmercode";
            
           $result = oci_parse($this->connection, $group_query_1);
           $r = oci_execute($result);
           $response = array();
           $this->sr_no=1;
           //$fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');
           $fp1=fopen('php://memory', 'w');
           fputcsv($fp1, array('Sr No','Farmer Code','Farmer Name','Village','Center','Area'));
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
               
                fputcsv($fp1, array( $this->sr_no,$row['FARMERCODE'],$row['FARMERNAMEENG'],$row['VILLAGENAMEENG'],$row['CENTRENAMEENG'],$row['AREA']), $delimiter = ',', $enclosure = '"');
                $this->sr_no= $this->sr_no+1;
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