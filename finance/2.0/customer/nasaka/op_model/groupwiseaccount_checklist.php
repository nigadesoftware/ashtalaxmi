<?php
    include_once("../swappbase/mypdf_a4_p.php");
class groupwiseaccount
{	
    public $connection;
    public $liney;
    public $maxlines;
    public $pdf;
    //
  

    public function __construct(&$connection,$maxlines)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
    	// create new PDF document
	    $this->pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Account Head Checklist');
        $this->pdf->SetKeywords('ACHDCK_000.MR');
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));

        $title = str_pad(' ', 30).'श्री संत जनार्दन स्वामी नगर, ता.नाशिक, जि.नाशिक';
    	$this->pdf->SetHeaderData('', 0,str_pad(' ', 21).'नाशिक सहकारी साखर कारखाना लि.,' ,$title);
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
        $lg['w_page'] = 'पान - ';
        // set some language-dependent strings (optional)
	    $this->pdf->setLanguageArray($lg);
	}

	public function isnewpage($projected)
    {
        if ($this->liney+$projected>=$this->maxlines)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function newpage($force=false)
    {
        if ($force==false)
        {
            if ($this->liney >= $this->maxlines)
            {
                $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
                $this->pdf->line(15,$this->liney,300,$this->liney);
                $this->liney = 20;
                $this->pdf->AddPage();
                // set color for background
                $this->pdf->SetFillColor(0, 0, 0);
                // set color for text
                $this->pdf->SetTextColor(0, 0, 0);
                $this->pageheader();
            }
        }
        else
        {
            $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
            $this->pdf->line(15,$this->liney,300,$this->liney);
            $this->liney = 20;
            $this->pdf->AddPage();
            // set color for background
            $this->pdf->SetFillColor(0, 0, 0);
            // set color for text
            $this->pdf->SetTextColor(0, 0, 0);
            $this->pageheader();
        }
    }
    function endreport()
    {
        // reset pointer to the last page*/
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->Output('DYSHSL_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 15;
        $this->pdf->SetFont('siddhanta', '', 13, '', true);
        $this->pdf->multicell(0,15,'ग्रुपवार खाते चेकलिस्ट',0,'C',false,1,0,$this->liney,true,0,false,true,10);
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->liney = $this->liney+7;
    	$this->pdf->line(15,$this->liney,200,$this->liney);
        $this->liney = $this->liney+2;
        $this->pdf->multicell(20,10,'खाते कोड',0,'L',false,1,15,$this->liney,true,0,false,true,10);
		$this->pdf->multicell(80,10,'खाते (युनिकोड)',0,'L',false,1,35,$this->liney,true,0,false,true,10);
		$this->pdf->multicell(65,10,'खाते (इंग्रजी)',0,'L',false,1,115,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(20,10,'सबलेजर',0,'L',false,1,180,$this->liney,true,0,false,true,10);
        
        $this->liney = $this->liney+7;
        $this->pdf->line(15,$this->liney,200,$this->liney);
        $this->liney = $this->liney+3;
    }

	function detail()
    {
        $group_query_0 = "select d.divisioncode,d.divisionnameuni
        from division d
        order by d.divisioncode,d.divisionnameuni";
        $group_result_0 = oci_parse($this->connection, $group_query_0);             
        $group_r_0 = oci_execute($group_result_0);
        $i=0;
        while ($group_row_0 = oci_fetch_array($group_result_0,OCI_ASSOC+OCI_RETURN_NULLS))
        {  
            if ($i>0)
            {
                $this->newpage(true);
            }
            if ($this->isnewpage(10))
                {
                    $this->newpage();
                }
            $this->pdf->SetFont('siddhanta', '', 12, '', true);
            $this->pdf->multicell(200,10,$group_row_0['DIVISIONCODE'].' '.$group_row_0['DIVISIONNAMEUNI'],0,'L',false,1,15,$this->liney,true,0,false,true,10);
            $this->liney = $this->liney+7;
            $group_query = "select g.groupcode,g.groupnameeng,g.groupnameuni,g.refcode  
            from accountgroup g
            where g.divisioncode=".$group_row_0['DIVISIONCODE']." 
            order by grouptypecode,groupcode";
            $group_result = oci_parse($this->connection, $group_query);             
            $group_r = oci_execute($group_result);
            while ($group_row = oci_fetch_array($group_result,OCI_ASSOC+OCI_RETURN_NULLS))
            {  
                if ($this->isnewpage(10))
                {
                    $this->newpage();
                }
                $this->pdf->SetFont('siddhanta', '', 11, '', true);
                $schedule_query = "select g.schedulenumber  
                from accountschedule g
                where g.groupcode=".$group_row['GROUPCODE']."
                and g.subgroupcode is null
                ";
                $schedule_result = oci_parse($this->connection, $schedule_query);             
                $schedule_r = oci_execute($schedule_result);
                if ($schedule_row = oci_fetch_array($schedule_result,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    $this->pdf->multicell(200,10,$group_row['REFCODE'].' G'.$group_row['GROUPCODE'].' '.$group_row['GROUPNAMEUNI'].' ('.$group_row['GROUPNAMEENG'].' ) शेड्यूल नंबर - '.$schedule_row['SCHEDULENUMBER'],0,'L',false,1,15,$this->liney,true,0,false,true,10);
                }
                else
                {
                    $this->pdf->multicell(200,10,$group_row['REFCODE'].' G'.$group_row['GROUPCODE'].' '.$group_row['GROUPNAMEUNI'].' ('.$group_row['GROUPNAMEENG'].' )',0,'L',false,1,15,$this->liney,true,0,false,true,10);
                }
                $this->liney = $this->liney+7;

                $groupaccount_query = "select s.accountcode,s.accountnameeng,s.accountnameuni,s.issubledgerallowed,s.refcode  
                from accounthead s
                where s.groupcode=".$group_row['GROUPCODE'].
                " and s.subgroupcode is null
                    and s.subsubgroupcode is null
                    order by accountcode";
                $groupaccount_result = oci_parse($this->connection, $groupaccount_query);             
                $groupaccount_r = oci_execute($groupaccount_result);
                while ($groupaccount_row = oci_fetch_array($groupaccount_result,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    if ($this->isnewpage(10))
                    {
                        $this->newpage();
                    }
                    $this->pdf->SetFont('siddhanta', '', 10, '', true);
                    $this->pdf->multicell(20,10,$groupaccount_row['ACCOUNTCODE'],0,'L',false,1,15,$this->liney,true,0,false,true,10);
                    $this->pdf->multicell(80,10,$groupaccount_row['ACCOUNTNAMEUNI'],0,'L',false,1,35,$this->liney,true,0,false,true,10);
                    $this->pdf->SetFont('siddhanta', '', 9, '', true);
                    $this->pdf->multicell(65,10,$groupaccount_row['ACCOUNTNAMEENG'],0,'L',false,1,115,$this->liney,true,0,false,true,10);
                    $this->pdf->SetFont('siddhanta', '', 10, '', true);
                    if ($groupaccount_row['ISSUBLEDGERALLOWED']==248805611)
                    {
                        $this->pdf->multicell(20,10,'नाही',0,'L',false,1,180,$this->liney,true,0,false,true,10);
                    }
                    elseif ($groupaccount_row['ISSUBLEDGERALLOWED']==248805511)
                    {
                        $this->pdf->multicell(20,10,'अाहे',0,'L',false,1,180,$this->liney,true,0,false,true,10);
                    }
                    else
                    {
                        $this->pdf->multicell(20,10,'-',0,'L',false,1,180,$this->liney,true,0,false,true,10);
                    }
                    $this->pdf->multicell(15,10,$groupaccount_row['REFCODE'],0,'L',false,1,190,$this->liney,true,0,false,true,10);
                    if ($this->isnewpage(10))
                    {
                        $this->newpage();
                    }
                    $this->liney = $this->liney+7;
                }
                $this->liney = $this->liney+3;
                $subgroup_query = "select s.subgroupcode,s.subgroupnameeng,s.subgroupnameuni  
                from accountsubgroup s
                where s.groupcode=".$group_row['GROUPCODE'].
                " order by s.subgroupcode";
                $subgroup_result = oci_parse($this->connection, $subgroup_query);             
                $subgroup_r = oci_execute($subgroup_result);
                while ($subgroup_row = oci_fetch_array($subgroup_result,OCI_ASSOC+OCI_RETURN_NULLS))
                {  
                    if ($this->isnewpage(10))
                    {
                        $this->newpage();
                    }
                    $this->pdf->SetFont('siddhanta', '', 11, '', true);
                    $schedule_query = "select g.schedulenumber  
                    from accountschedule g
                    where g.groupcode=".$group_row['GROUPCODE']."
                    and g.subgroupcode=".$subgroup_row['SUBGROUPCODE'];
                    $schedule_result = oci_parse($this->connection, $schedule_query);             
                    $schedule_r = oci_execute($schedule_result);
                    if ($schedule_row = oci_fetch_array($schedule_result,OCI_ASSOC+OCI_RETURN_NULLS))
                    {
                        $this->pdf->multicell(200,10,'G'.$group_row['GROUPCODE'].'S'.$subgroup_row['SUBGROUPCODE'].' '.$subgroup_row['SUBGROUPNAMEUNI'].' ('.$subgroup_row['SUBGROUPNAMEENG'].' ) शेड्यूल नंबर - '.$schedule_row['SCHEDULENUMBER'],0,'L',false,1,15,$this->liney,true,0,false,true,10);
                    }
                    else
                    {
                        $this->pdf->multicell(200,10,'G'.$group_row['GROUPCODE'].'S'.$subgroup_row['SUBGROUPCODE'].' '.$subgroup_row['SUBGROUPNAMEUNI'].' ('.$subgroup_row['SUBGROUPNAMEENG'].' )',0,'L',false,1,15,$this->liney,true,0,false,true,10);
                    }
                    $this->liney = $this->liney+7;
                    $subgroupaccount_query = "select s.accountcode,s.accountnameeng,s.accountnameuni,s.issubledgerallowed,s.refcode  
                    from accounthead s
                    where s.groupcode=".$group_row['GROUPCODE'].
                    " and s.subgroupcode=".$subgroup_row['SUBGROUPCODE']."
                        and s.subsubgroupcode is null
                        order by accountcode";
                    $subgroupaccount_result = oci_parse($this->connection, $subgroupaccount_query);             
                    $subgroupaccount_r = oci_execute($subgroupaccount_result);
                    while ($subgroupaccount_row = oci_fetch_array($subgroupaccount_result,OCI_ASSOC+OCI_RETURN_NULLS))
                    {
                        if ($this->isnewpage(10))
                        {
                            $this->newpage();
                        }
                        $this->pdf->SetFont('siddhanta', '', 10, '', true);
                        $this->pdf->multicell(20,10,$subgroupaccount_row['ACCOUNTCODE'],0,'L',false,1,15,$this->liney,true,0,false,true,10);
                        $this->pdf->multicell(80,10,$subgroupaccount_row['ACCOUNTNAMEUNI'],0,'L',false,1,35,$this->liney,true,0,false,true,10);
                        $this->pdf->SetFont('siddhanta', '', 9, '', true);
                        $this->pdf->multicell(65,10,$subgroupaccount_row['ACCOUNTNAMEENG'],0,'L',false,1,115,$this->liney,true,0,false,true,10);
                        $this->pdf->SetFont('siddhanta', '', 10, '', true);
                        if ($subgroupaccount_row['ISSUBLEDGERALLOWED']==248805611)
                        {
                            $this->pdf->multicell(20,10,'नाही',0,'L',false,1,180,$this->liney,true,0,false,true,10);
                        }
                        elseif ($subgroupaccount_row['ISSUBLEDGERALLOWED']==248805511)
                        {
                            $this->pdf->multicell(20,10,'अाहे',0,'L',false,1,180,$this->liney,true,0,false,true,10);
                        }
                        else
                        {
                            $this->pdf->multicell(20,10,'-',0,'L',false,1,180,$this->liney,true,0,false,true,10);
                        }
                        $this->pdf->multicell(15,10,$subgroupaccount_row['REFCODE'],0,'L',false,1,190,$this->liney,true,0,false,true,10);
                        if ($this->isnewpage(10))
                        {
                            $this->newpage();
                        }
                        $this->liney = $this->liney+7;
                    }
                    $this->liney = $this->liney+3;
                    $subsubgroup_query = "select s.subsubgroupcode,s.subsubgroupnameeng,s.subsubgroupnameuni  
                    from accountsubsubsubgroup s
                    where s.groupcode=".$group_row['GROUPCODE'].
                    " and s.subgroupcode=".$subgroup_row['SUBGROUPCODE'].
                    " order by subsubgroupcode";
                    $subsubgroup_result = oci_parse($this->connection, $subsubgroup_query);             
                    $subsubgroup_r = oci_execute($subsubgroup_result);
                    while ($subsubgroup_row = oci_fetch_array($subsubgroup_result,OCI_ASSOC+OCI_RETURN_NULLS))
                    {  
                        if ($this->isnewpage(10))
                        {
                            $this->newpage();
                        }
                        $this->pdf->SetFont('siddhanta', '', 11, '', true);
                        $this->pdf->multicell(200,10,'G'.$group_row['GROUPCODE'].'S'.$subgroup_row['SUBGROUPCODE'].'S'.$subsubgroup_row['SUBSUBGROUPCODE'].' '.$subgroup_row['SUBSUBGROUPNAMEUNI'].' ('.$subgroup_row['SUBSUBGROUPNAMEENG'].' )',0,'L',false,1,15,$this->liney,true,0,false,true,10);
                        $this->liney = $this->liney+7;

                        $subsubgroupaccount_query = "select s.accountcode,s.accountnameeng,s.accountnameuni,s.issubledgerallowed,s.refcode  
                        from accounthead s
                        where s.groupcode=".$group_row['GROUPCODE'].
                        " and s.subgroupcode=".$subgroup_row['SUBGROUPCODE'].
                        " and s.subsubgroupcode=".$subsubgroup_row['SUBSUBGROUPCODE'].
                        " order by accountcode";
                        $subsubgroupaccount_result = oci_parse($this->connection, $subsubgroup_query);             
                        $subsubgroupaccount_r = oci_execute($subsubgroupaccount_result);
                        while ($subsubgroupaccount_row = oci_fetch_array($subsubgroupaccount_result,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            if ($this->isnewpage(10))
                            {
                                $this->newpage();
                            }
                            $this->pdf->SetFont('siddhanta', '', 10, '', true);
                            $this->pdf->multicell(20,10,$subsubgroupaccount_row['ACCOUNTCODE'],0,'L',false,1,15,$this->liney,true,0,false,true,10);
                            $this->pdf->multicell(80,10,$subsubgroupaccount_row['ACCOUNTNAMEUNI'],0,'L',false,1,35,$this->liney,true,0,false,true,10);
                            $this->pdf->SetFont('siddhanta', '', 9, '', true);
                            $this->pdf->multicell(65,10,$subsubgroupaccount_row['ACCOUNTNAMEENG'],0,'L',false,1,115,$this->liney,true,0,false,true,10);
                            $this->pdf->SetFont('siddhanta', '', 10, '', true);
                            if ($subsubgroupaccount_row['ISSUBLEDGERALLOWED']==248805611)
                            {
                                $this->pdf->multicell(20,10,'नाही',0,'L',false,1,180,$this->liney,true,0,false,true,10);
                            }
                            elseif ($subsubgroupaccount_row['ISSUBLEDGERALLOWED']==248805511)
                            {
                                $this->pdf->multicell(20,10,'अाहे',0,'L',false,1,180,$this->liney,true,0,false,true,10);
                            }
                            else
                            {
                                $this->pdf->multicell(20,10,'-',0,'L',false,1,175,$this->liney,true,0,false,true,10);
                            }
                            $this->pdf->multicell(15,10,$subsubgroupaccount_row['REFCODE'],0,'L',false,1,190,$this->liney,true,0,false,true,10);
                            if ($this->isnewpage(10))
                            {
                                $this->newpage();
                            }
                            $this->liney = $this->liney+7;
                        }
                        $this->liney = $this->liney+3;
                    }    
                }
            }
            $i++;
        }
    }
}
?>