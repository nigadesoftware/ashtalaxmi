<?php
    include_once("../swappbase/mypdf_a4_p.php");
class schedulewiseaccount
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
        $this->pdf->SetSubject('Schehdulewise Account Head Checklist');
        $this->pdf->SetKeywords('SCHACHDCK_000.MR');
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
        $this->pdf->multicell(0,15,'परिशिष्ठ वार खाते चेकलिस्ट',0,'C',false,1,0,$this->liney,true,0,false,true,10);
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
        $schedule_query_0 = "select d.divisioncode,d.divisionnameuni
        from division d
        order by d.divisioncode,d.divisionnameuni";
        $schedule_result_0 = oci_parse($this->connection, $schedule_query_0);             
        $schedule_r_0 = oci_execute($schedule_result_0);
        $i=0;
        while ($schedule_row_0 = oci_fetch_array($schedule_result_0,OCI_ASSOC+OCI_RETURN_NULLS))
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
            $this->pdf->multicell(200,10,$schedule_row_0['DIVISIONCODE'].' '.$schedule_row_0['DIVISIONNAMEUNI'],0,'L',false,1,15,$this->liney,true,0,false,true,10);
            $this->liney = $this->liney+7;
            $schedule_query = "select g.schedulecode,g.schedulenameeng,g.schedulenameuni 
            from accountschedule g,accountgroup a
            where g.groupcode=a.groupcode
            and a.divisioncode=".$schedule_row_0['DIVISIONCODE']." 
            order by g.grouptypecode,schedulecode";
            $schedule_result = oci_parse($this->connection, $schedule_query);             
            $schedule_r = oci_execute($schedule_result);
            while ($schedule_row = oci_fetch_array($schedule_result,OCI_ASSOC+OCI_RETURN_NULLS))
            {  
                if ($this->isnewpage(10))
                {
                    $this->newpage();
                }
                $this->pdf->SetFont('siddhanta', '', 11, '', true);
                $this->pdf->multicell(200,10,'H'.$schedule_row['SCHEDULECODE'].' '.$schedule_row['SCHEDULENAMEUNI'].' ('.$schedule_row['SCHEDULENAMEENG'].' )',0,'L',false,1,15,$this->liney,true,0,false,true,10);
                $this->liney = $this->liney+7;

                $scheduleaccount_query = "select s.accountcode,s.accountnameeng,s.accountnameuni,s.issubledgerallowed,s.refcode  
                from accounthead s
                where s.schedulecode=".$schedule_row['SCHEDULECODE'].
                " and s.subschedulecode is null
                    and s.subsubschedulecode is null
                    and s.subsubsubschedulecode is null
                    order by accountcode";
                $scheduleaccount_result = oci_parse($this->connection, $scheduleaccount_query);             
                $scheduleaccount_r = oci_execute($scheduleaccount_result);
                while ($scheduleaccount_row = oci_fetch_array($scheduleaccount_result,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    if ($this->isnewpage(10))
                    {
                        $this->newpage();
                    }
                    $this->pdf->SetFont('siddhanta', '', 10, '', true);
                    $this->pdf->multicell(20,10,$scheduleaccount_row['ACCOUNTCODE'],0,'L',false,1,15,$this->liney,true,0,false,true,10);
                    $this->pdf->multicell(80,10,$scheduleaccount_row['ACCOUNTNAMEUNI'],0,'L',false,1,35,$this->liney,true,0,false,true,10);
                    $this->pdf->SetFont('siddhanta', '', 9, '', true);
                    $this->pdf->multicell(65,10,$scheduleaccount_row['ACCOUNTNAMEENG'],0,'L',false,1,115,$this->liney,true,0,false,true,10);
                    $this->pdf->SetFont('siddhanta', '', 10, '', true);
                    if ($scheduleaccount_row['ISSUBLEDGERALLOWED']==248805611)
                    {
                        $this->pdf->multicell(20,10,'नाही',0,'L',false,1,180,$this->liney,true,0,false,true,10);
                    }
                    elseif ($scheduleaccount_row['ISSUBLEDGERALLOWED']==248805511)
                    {
                        $this->pdf->multicell(20,10,'अाहे',0,'L',false,1,180,$this->liney,true,0,false,true,10);
                    }
                    else
                    {
                        $this->pdf->multicell(20,10,'-',0,'L',false,1,180,$this->liney,true,0,false,true,10);
                    }
                    $this->pdf->multicell(15,10,$scheduleaccount_row['REFCODE'],0,'L',false,1,190,$this->liney,true,0,false,true,10);
                    if ($this->isnewpage(10))
                    {
                        $this->newpage();
                    }
                    $this->liney = $this->liney+7;
                }
                $this->liney = $this->liney+3;
                $subschedule_query = "select s.subschedulecode,s.subschedulenameeng,s.subschedulenameuni  
                from accountsubschedule s
                where s.schedulecode=".$schedule_row['SCHEDULECODE'].
                " order by s.subschedulecode";
                $subschedule_result = oci_parse($this->connection, $subschedule_query);             
                $subschedule_r = oci_execute($subschedule_result);
                while ($subschedule_row = oci_fetch_array($subschedule_result,OCI_ASSOC+OCI_RETURN_NULLS))
                {  
                    if ($this->isnewpage(10))
                    {
                        $this->newpage();
                    }
                    $this->pdf->SetFont('siddhanta', '', 11, '', true);
                    $this->pdf->multicell(200,10,'H'.$schedule_row['SCHEDULECODE'].'S'.$subschedule_row['SUBSCHEDULECODE'].' '.$subschedule_row['SUBSCHEDULENAMEUNI'].' ('.$subschedule_row['SUBSCHEDULENAMEENG'].' )',0,'L',false,1,15,$this->liney,true,0,false,true,10);
                    $this->liney = $this->liney+7;
                    $subscheduleaccount_query = "select s.accountcode,s.accountnameeng,s.accountnameuni,s.issubledgerallowed,s.refcode  
                    from accounthead s
                    where s.schedulecode=".$schedule_row['SCHEDULECODE'].
                    " and s.subschedulecode=".$subschedule_row['SUBSCHEDULECODE']."
                        and s.subsubschedulecode is null
                        order by accountcode";
                    $subscheduleaccount_result = oci_parse($this->connection, $subscheduleaccount_query);             
                    $subscheduleaccount_r = oci_execute($subscheduleaccount_result);
                    while ($subscheduleaccount_row = oci_fetch_array($subscheduleaccount_result,OCI_ASSOC+OCI_RETURN_NULLS))
                    {
                        if ($this->isnewpage(10))
                        {
                            $this->newpage();
                        }
                        $this->pdf->SetFont('siddhanta', '', 10, '', true);
                        $this->pdf->multicell(20,10,$subscheduleaccount_row['ACCOUNTCODE'],0,'L',false,1,15,$this->liney,true,0,false,true,10);
                        $this->pdf->multicell(80,10,$subscheduleaccount_row['ACCOUNTNAMEUNI'],0,'L',false,1,35,$this->liney,true,0,false,true,10);
                        $this->pdf->SetFont('siddhanta', '', 9, '', true);
                        $this->pdf->multicell(65,10,$subscheduleaccount_row['ACCOUNTNAMEENG'],0,'L',false,1,115,$this->liney,true,0,false,true,10);
                        $this->pdf->SetFont('siddhanta', '', 10, '', true);
                        if ($subscheduleaccount_row['ISSUBLEDGERALLOWED']==248805611)
                        {
                            $this->pdf->multicell(20,10,'नाही',0,'L',false,1,180,$this->liney,true,0,false,true,10);
                        }
                        elseif ($subscheduleaccount_row['ISSUBLEDGERALLOWED']==248805511)
                        {
                            $this->pdf->multicell(20,10,'अाहे',0,'L',false,1,180,$this->liney,true,0,false,true,10);
                        }
                        else
                        {
                            $this->pdf->multicell(20,10,'-',0,'L',false,1,180,$this->liney,true,0,false,true,10);
                        }
                        $this->pdf->multicell(15,10,$subscheduleaccount_row['REFCODE'],0,'L',false,1,190,$this->liney,true,0,false,true,10);
                        if ($this->isnewpage(10))
                        {
                            $this->newpage();
                        }
                        $this->liney = $this->liney+7;
                    }
                    $this->liney = $this->liney+3;
                    $subsubschedule_query = "select s.subsubschedulecode,s.subsubschedulenameeng,s.subsubschedulenameuni  
                    from accountsubsubschedule s
                    where s.schedulecode=".$schedule_row['SCHEDULECODE'].
                    " and s.subschedulecode=".$subschedule_row['SUBSCHEDULECODE'].
                    " order by subsubschedulecode";
                    $subsubschedule_result = oci_parse($this->connection, $subsubschedule_query);             
                    $subsubschedule_r = oci_execute($subsubschedule_result);
                    while ($subsubschedule_row = oci_fetch_array($subsubschedule_result,OCI_ASSOC+OCI_RETURN_NULLS))
                    {  
                        if ($this->isnewpage(10))
                        {
                            $this->newpage();
                        }
                        $this->pdf->SetFont('siddhanta', '', 11, '', true);
                        $this->pdf->multicell(200,10,'H'.$schedule_row['SCHEDULECODE'].'S'.$subschedule_row['SUBSCHEDULECODE'].'S'.$subsubschedule_row['SUBSUBSCHEDULECODE'].' '.$subsubschedule_row['SUBSUBSCHEDULENAMEUNI'].' ('.$subsubschedule_row['SUBSUBSCHEDULENAMEENG'].' )',0,'L',false,1,15,$this->liney,true,0,false,true,10);
                        $this->liney = $this->liney+7;

                        $subsubscheduleaccount_query = "select s.accountcode,s.accountnameeng,s.accountnameuni,s.issubledgerallowed,s.refcode  
                        from accounthead s
                        where s.schedulecode=".$schedule_row['SCHEDULECODE'].
                        " and s.subschedulecode=".$subschedule_row['SUBSCHEDULECODE'].
                        " and s.subsubschedulecode=".$subsubschedule_row['SUBSUBSCHEDULECODE'].
                        " order by accountcode";
                        $subsubscheduleaccount_result = oci_parse($this->connection, $subsubscheduleaccount_query);             
                        $subsubscheduleaccount_r = oci_execute($subsubscheduleaccount_result);
                        while ($subsubscheduleaccount_row = oci_fetch_array($subsubscheduleaccount_result,OCI_ASSOC+OCI_RETURN_NULLS))
                        {
                            if ($this->isnewpage(10))
                            {
                                $this->newpage();
                            }
                            $this->pdf->SetFont('siddhanta', '', 10, '', true);
                            $this->pdf->multicell(20,10,$subsubscheduleaccount_row['ACCOUNTCODE'],0,'L',false,1,15,$this->liney,true,0,false,true,10);
                            $this->pdf->multicell(80,10,$subsubscheduleaccount_row['ACCOUNTNAMEUNI'],0,'L',false,1,35,$this->liney,true,0,false,true,10);
                            $this->pdf->SetFont('siddhanta', '', 9, '', true);
                            $this->pdf->multicell(65,10,$subsubscheduleaccount_row['ACCOUNTNAMEENG'],0,'L',false,1,115,$this->liney,true,0,false,true,10);
                            $this->pdf->SetFont('siddhanta', '', 10, '', true);
                            if ($subsubscheduleaccount_row['ISSUBLEDGERALLOWED']==248805611)
                            {
                                $this->pdf->multicell(20,10,'नाही',0,'L',false,1,180,$this->liney,true,0,false,true,10);
                            }
                            elseif ($subsubscheduleaccount_row['ISSUBLEDGERALLOWED']==248805511)
                            {
                                $this->pdf->multicell(20,10,'अाहे',0,'L',false,1,180,$this->liney,true,0,false,true,10);
                            }
                            else
                            {
                                $this->pdf->multicell(20,10,'-',0,'L',false,1,175,$this->liney,true,0,false,true,10);
                            }
                            $this->pdf->multicell(15,10,$subsubscheduleaccount_row['REFCODE'],0,'L',false,1,190,$this->liney,true,0,false,true,10);
                            if ($this->isnewpage(10))
                            {
                                $this->newpage();
                            }
                            $this->liney = $this->liney+7;
                        }
                        $this->liney = $this->liney+3;
                        //
                        $subsubsubschedule_query = "select s.subsubsubschedulecode,s.subsubsubschedulenameeng,s.subsubsubschedulenameuni  
                        from accountsubsubsubschedule s
                        where s.schedulecode=".$schedule_row['SCHEDULECODE'].
                        " and s.subschedulecode=".$subschedule_row['SUBSCHEDULECODE'].
                        " and s.subsubschedulecode=".$subsubschedule_row['SUBSUBSCHEDULECODE'].
                        " order by subsubsubschedulecode";
                        $subsubsubschedule_result = oci_parse($this->connection, $subsubsubschedule_query);             
                        $subsubsubschedule_r = oci_execute($subsubsubschedule_result);
                        while ($subsubsubschedule_row = oci_fetch_array($subsubsubschedule_result,OCI_ASSOC+OCI_RETURN_NULLS))
                        {  
                            if ($this->isnewpage(10))
                            {
                                $this->newpage();
                            }
                            $this->pdf->SetFont('siddhanta', '', 11, '', true);
                            $this->pdf->multicell(200,10,'H'.$schedule_row['SCHEDULECODE'].'S'.$subschedule_row['SUBSCHEDULECODE'].'S'.$subsubschedule_row['SUBSUBSCHEDULECODE'].'S'.$subsubsubschedule_row['SUBSUBSUBSCHEDULECODE'].' '.$subsubsubschedule_row['SUBSUBSUBSCHEDULENAMEUNI'].' ('.$subsubsubschedule_row['SUBSUBSUBSCHEDULENAMEENG'].' )',0,'L',false,1,15,$this->liney,true,0,false,true,10);
                            $this->liney = $this->liney+7;

                            $subsubsubscheduleaccount_query = "select s.accountcode,s.accountnameeng,s.accountnameuni,s.issubledgerallowed,s.refcode  
                            from accounthead s
                            where s.schedulecode=".$schedule_row['SCHEDULECODE'].
                            " and s.subschedulecode=".$subschedule_row['SUBSCHEDULECODE'].
                            " and s.subsubschedulecode=".$subsubschedule_row['SUBSUBSCHEDULECODE'].
                            " and s.subsubsubschedulecode=".$subsubsubschedule_row['SUBSUBSUBSCHEDULECODE'].
                            " order by accountcode";
                            $subsubsubscheduleaccount_result = oci_parse($this->connection, $subsubsubscheduleaccount_query);             
                            $subsubsubscheduleaccount_r = oci_execute($subsubsubscheduleaccount_result);
                            while ($subsubsubscheduleaccount_row = oci_fetch_array($subsubsubscheduleaccount_result,OCI_ASSOC+OCI_RETURN_NULLS))
                            {
                                if ($this->isnewpage(10))
                                {
                                    $this->newpage();
                                }
                                $this->pdf->SetFont('siddhanta', '', 10, '', true);
                                $this->pdf->multicell(20,10,$subsubsubscheduleaccount_row['ACCOUNTCODE'],0,'L',false,1,15,$this->liney,true,0,false,true,10);
                                $this->pdf->multicell(80,10,$subsubsubscheduleaccount_row['ACCOUNTNAMEUNI'],0,'L',false,1,35,$this->liney,true,0,false,true,10);
                                $this->pdf->SetFont('siddhanta', '', 9, '', true);
                                $this->pdf->multicell(65,10,$subsubsubscheduleaccount_row['ACCOUNTNAMEENG'],0,'L',false,1,115,$this->liney,true,0,false,true,10);
                                $this->pdf->SetFont('siddhanta', '', 10, '', true);
                                if ($subsubsubscheduleaccount_row['ISSUBLEDGERALLOWED']==248805611)
                                {
                                    $this->pdf->multicell(20,10,'नाही',0,'L',false,1,180,$this->liney,true,0,false,true,10);
                                }
                                elseif ($subsubsubscheduleaccount_row['ISSUBLEDGERALLOWED']==248805511)
                                {
                                    $this->pdf->multicell(20,10,'अाहे',0,'L',false,1,180,$this->liney,true,0,false,true,10);
                                }
                                else
                                {
                                    $this->pdf->multicell(20,10,'-',0,'L',false,1,175,$this->liney,true,0,false,true,10);
                                }
                                $this->pdf->multicell(15,10,$subsubsubscheduleaccount_row['REFCODE'],0,'L',false,1,190,$this->liney,true,0,false,true,10);
                                if ($this->isnewpage(10))
                                {
                                    $this->newpage();
                                }
                                $this->liney = $this->liney+7;
                            }
                            $this->liney = $this->liney+3;
                        }    
                        //
                    }    
                }
            }
            $i++;
        }
    }
}
?>