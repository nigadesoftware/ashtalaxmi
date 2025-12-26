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
class slipboynondsummary extends reportbox
{
    public $centrecode;
    public $fromdate;
    public $todate;
    public $centresummary;
    public $divisionsummary;
    public $summary;
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
        $this->newpage(True);
        $this->group();
        $this->reportfooter();
    }

    function reportheader()
    {
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->textbox('सेंटरनिहाय स्लीपबाॅय ऊसनोंद समरी',180,10,'S','C',1,'siddhanta',13);
        $this->newrow(7);
        if ($this->fromdate!='' and $this->todate!='')
        {
            $this->textbox('हंगाम: '.$_SESSION['yearperiodcode'].' दिनांक '.DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y').' पासून ते दिनांक '.DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y').' पर्यंत',180,10,'S','C',1,'siddhanta',12);
        }
        else
        {
            $this->textbox('हंगाम: '.$_SESSION['yearperiodcode'],180,10,'S','C',1,'siddhanta',12);
        }
        $this->summary['NOOFPLOTS']+=$group_row_1['NOOFPLOTS']=0;
        $this->summary['AREA']+=$group_row_1['AREA']=0;
        $this->summary['AREABYGPS']+=$group_row_1['AREABYGPS']=0;
        $this->summary['MEASUREDNOOFPLOTS']+=$group_row_1['MEASUREDNOOFPLOTS']=0;
        $this->summary['ISSELFIEUPLOADED']+=$group_row_1['ISSELFIEUPLOADED']=0;
        $this->summary['ISIDUPLOADED']+=$group_row_1['ISIDUPLOADED']=0;
        $this->summary['ISPBUPLOADED']+=$group_row_1['ISPBUPLOADED']=0;
    }

	function pageheader()
    {
        ob_flush();
        ob_start();
        $this->liney = 15;
        if ($this->pdf->getPage()==1)
        {
            $this->reportheader();
        }
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->newrow(7);
        $this->hline(10,195,$this->liney,'C');
        $this->setfieldwidth(40,10);
        $this->vline($this->liney,$this->liney+13,$this->x);
        $this->textbox('सेंटर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+13,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('नोंद संख्या',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+13,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('नोंद क्षेत्र',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+13,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('जीपीएस नोंद संख्या',$this->w,$this->x,'S','R',1,'siddhanta',11,'','Y','','B');
        $this->vline($this->liney,$this->liney+13,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox('जीपीएस नोंद क्षेत्र',$this->w,$this->x,'S','R',1,'siddhanta',11,'','Y','','B');
        $this->vline($this->liney,$this->liney+13,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('सेल्फी',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+13,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox('ओळखपत्र',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+13,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('पासबुक',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+13,$this->x+$this->w);

        $this->newrow(15);
        $this->hline(10,195,$this->liney-2,'C');

    }
    function uploadeddataupdate()
    {
        $query = "update plantationheader h
        set h.ISSELFIEUPLOADED=0,h.ISIDUPLOADED=0,h.ISPBUPLOADED=0
        where h.seasoncode=".$_SESSION['yearperiodcode'].
        $result = oci_parse($this->connection, $query);
        if (oci_execute($result,OCI_NO_AUTO_COMMIT))
        {

        }
        $query_1 = "select plotnumber from plantationheader p 
        where p.seasoncode=".$_SESSION['yearperiodcode'];
        " order by plotnumber";
        $result_1 = oci_parse($this->connection, $query_1);
        $r = oci_execute($result_1);
        while ($row_1 = oci_fetch_array($result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if (file_exists("../../../../../webservice/upload/".$_SESSION['yearperiodcode']."_".$row_1['PLOTNUMBER'].'.jpeg'))
            {
                $dt = date("d/m/Y", filectime("../../../../../webservice/upload/".$_SESSION['yearperiodcode']."_".$row_1['PLOTNUMBER'].'.jpeg'));
                $dt = DateTime::createFromFormat('d/m/Y',$dt)->format('d-M-Y');
                $query = "update plantationheader h
                set h.ISSELFIEUPLOADED=1,measurementdate='".$dt."'
                where h.seasoncode=".$_SESSION['yearperiodcode']
                ." and h.plotnumber=".$row_1['PLOTNUMBER'];
                $result = oci_parse($this->connection, $query);
                if (oci_execute($result,OCI_NO_AUTO_COMMIT))
                {

                }
                else
                {
                    return 0;
                }
            }
            if (file_exists("../../../../../webservice/upload/id".$_SESSION['yearperiodcode']."_".$row_1['PLOTNUMBER'].'.jpeg'))
            {
                $query = "update plantationheader h
                set h.ISIDUPLOADED=1
                where h.seasoncode=".$_SESSION['yearperiodcode']
                ." and h.plotnumber=".$row_1['PLOTNUMBER'];
                $result = oci_parse($this->connection, $query);
                if (oci_execute($result,OCI_NO_AUTO_COMMIT))
                {

                }
                else
                {
                    return 0;
                }
            }
            if (file_exists("../../../../../webservice/upload/pb".$_SESSION['yearperiodcode']."_".$row_1['PLOTNUMBER'].'.jpeg'))
            {
                $query = "update plantationheader h
                set h.ISPBUPLOADED=1
                where h.seasoncode=".$_SESSION['yearperiodcode']
                ." and h.plotnumber=".$row_1['PLOTNUMBER'];
                $result = oci_parse($this->connection, $query);
                if (oci_execute($result,OCI_NO_AUTO_COMMIT))
                {

                }
                else
                {
                    return 0;
                }
            }
        }
        return 1;
    }
    function group()
    {
        $this->totalgroupcount=2;
        $cond = "seasoncode=".$_SESSION['yearperiodcode'];
        if ($this->fromdate!='' and $this->todate!='')
        {
            $cond = $cond ." and trunc(t.measurementdate)>='".$this->fromdate."'
            and trunc(t.measurementdate)<='".$this->todate."'";
        }
        $group_query_1 ="select d.divisioncode,e.centrecode,divisionnameuni,e.centrenameuni,slipboynameuni,sum(noofplots) noofplots,sum(area) area,sum(areabygps) areabygps,sum(measurednoofplots) measurednoofplots
        ,sum(isselfieuploaded) isselfieuploaded,sum(isiduploaded) isiduploaded,sum(ispbuploaded) ispbuploaded
        from (
        select s.slipboynameuni,c.centrecode,c.centrenameuni,count(*) noofplots,sum(area) area,sum(areabygps) areabygps,0 measurednoofplots,0 isselfieuploaded,0 isiduploaded,0 ispbuploaded
        from plantationheader t,slipboy s,village v,centre c
        where {$cond} 
        and t.slipboycode=s.userid and t.villagecode=v.villagecode and v.centrecode=c.centrecode
        group by t.slipboycode,s.slipboynameuni,c.centrecode,c.centrenameuni
        union all
        select s.slipboynameuni,c.centrecode,c.centrenameuni,0 noofplots,0 area,0 areabygps,count(*) measurednoofplots,0 isselfieuploaded,0 isiduploaded,0 ispbuploaded
        from plantationheader t,slipboy s,village v,centre c
        where {$cond}  
        and t.areabygps is not null
        and t.measurementuserid=s.userid and t.villagecode=v.villagecode and v.centrecode=c.centrecode
        group by t.slipboycode,s.slipboynameuni,c.centrecode,c.centrenameuni
        union all
        select s.slipboynameuni,c.centrecode,c.centrenameuni,0 noofplots,0 area,0 areabygps,0 measurednoofplots
        ,count(t.isselfieuploaded) isselfieuploaded,count(t.isiduploaded) isiduploaded,count(t.ispbuploaded) ispbuploaded
        from plantationheader t,slipboy s,village v,centre c
        where {$cond}  
        and t.measurementuserid=s.userid and t.villagecode=v.villagecode 
        and v.centrecode=c.centrecode
        group by c.centrecode,c.centrenameuni,t.slipboycode,s.slipboynameuni
        )t,centre e,division d
        where t.centrecode=e.centrecode and e.divisioncode=d.divisioncode
        group by d.divisioncode,divisionnameuni,e.centrecode,e.centrenameuni,slipboynameuni
        order by d.divisioncode,centrecode,divisionnameuni,centrenameuni,slipboynameuni";

        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            //$this->hline(10,405,$this->liney,'D'); 
            $this->centresummary['NOOFPLOTS']+=$group_row_1['NOOFPLOTS'];
            $this->centresummary['AREA']+=$group_row_1['AREA'];
            $this->centresummary['AREABYGPS']+=$group_row_1['AREABYGPS'];
            $this->centresummary['MEASUREDNOOFPLOTS']+=$group_row_1['MEASUREDNOOFPLOTS'];
            $this->centresummary['ISSELFIEUPLOADED']+=$group_row_1['ISSELFIEUPLOADED'];
            $this->centresummary['ISIDUPLOADED']+=$group_row_1['ISIDUPLOADED'];
            $this->centresummary['ISPBUPLOADED']+=$group_row_1['ISPBUPLOADED'];


            $this->divisionsummary['NOOFPLOTS']+=$group_row_1['NOOFPLOTS'];
            $this->divisionsummary['AREA']+=$group_row_1['AREA'];
            $this->divisionsummary['AREABYGPS']+=$group_row_1['AREABYGPS'];
            $this->divisionsummary['MEASUREDNOOFPLOTS']+=$group_row_1['MEASUREDNOOFPLOTS'];
            $this->divisionsummary['ISSELFIEUPLOADED']+=$group_row_1['ISSELFIEUPLOADED'];
            $this->divisionsummary['ISIDUPLOADED']+=$group_row_1['ISIDUPLOADED'];
            $this->divisionsummary['ISPBUPLOADED']+=$group_row_1['ISPBUPLOADED'];

            $this->summary['NOOFPLOTS']+=$group_row_1['NOOFPLOTS'];
            $this->summary['AREA']+=$group_row_1['AREA'];
            $this->summary['AREABYGPS']+=$group_row_1['AREABYGPS'];
            $this->summary['MEASUREDNOOFPLOTS']+=$group_row_1['MEASUREDNOOFPLOTS'];
            $this->summary['ISSELFIEUPLOADED']+=$group_row_1['ISSELFIEUPLOADED'];
            $this->summary['ISIDUPLOADED']+=$group_row_1['ISIDUPLOADED'];
            $this->summary['ISPBUPLOADED']+=$group_row_1['ISPBUPLOADED'];
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
    }

    function groupheader_1(&$group_row_1)
    {
        $this->divisionsummary['NOOFPLOTS']=0;
        $this->divisionsummary['AREA']=0;
        $this->divisionsummary['AREABYGPS']=0;
        $this->divisionsummary['MEASUREDNOOFPLOTS']=0;
        $this->divisionsummary['ISSELFIEUPLOADED']=0;
        $this->divisionsummary['ISIDUPLOADED']=0;
        $this->divisionsummary['ISPBUPLOADED']=0;

        $this->setfieldwidth(40,10);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox($group_row_1['DIVISIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        
        $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20); 
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->setfieldwidth(25);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            if ($this->isnewpage(10))
            {
                $this->newrow();
                $this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            else
            {
                $this->newrow();
                $this->hline(10,195,$this->liney-2,'C'); 
            }
    }
    function groupheader_2(&$group_row_1)
    {
        $this->centresummary['NOOFPLOTS']=0;
        $this->centresummary['AREA']=0;
        $this->centresummary['AREABYGPS']=0;
        $this->centresummary['MEASUREDNOOFPLOTS']=0;
        $this->centresummary['ISSELFIEUPLOADED']=0;
        $this->centresummary['ISIDUPLOADED']=0;
        $this->centresummary['ISPBUPLOADED']=0;

        $this->setfieldwidth(40,10);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox($group_row_1['CENTRENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        
        $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20); 
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->setfieldwidth(25);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            if ($this->isnewpage(10))
            {
                $this->newrow();
                $this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            else
            {
                $this->newrow();
                $this->hline(10,195,$this->liney-2,'C'); 
            }

    }
    function groupheader_3(&$group_row_1)
    {
    }
    function groupheader_4(&$group_row_1)
    {
    }
    function groupheader_5(&$group_row_1)
    {
    }
    function groupheader_6(&$group_row_1)
    {
    }
    function groupheader_7(&$group_row_1)
    {
    }
    function detail_1($group_row_1)
    { 
            $this->setfieldwidth(40,10);
            $this->vline($this->liney-2,$this->liney+5,$this->x);
            $this->textbox($group_row_1['SLIPBOYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',9,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($group_row_1['NOOFPLOTS'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($group_row_1['AREA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($group_row_1['MEASUREDNOOFPLOTS'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20); 
            $this->textbox($group_row_1['AREABYGPS'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($group_row_1['ISSELFIEUPLOADED'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(20);
            $this->textbox($group_row_1['ISIDUPLOADED'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->setfieldwidth(25);
            $this->textbox($group_row_1['ISPBUPLOADED'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            if ($this->isnewpage(10))
            {
                $this->newrow();
                $this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            else
            {
                $this->newrow();
                $this->hline(10,195,$this->liney-2,'C'); 
            }
    }
    function groupfooter_1(&$group_row)
    {
        $this->setfieldwidth(40,10);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox($group_row['DIVISIONNAMEUNI'].' एकूण',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->divisionsummary['NOOFPLOTS'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->divisionsummary['AREA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->divisionsummary['MEASUREDNOOFPLOTS'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox($this->divisionsummary['AREABYGPS'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->divisionsummary['ISSELFIEUPLOADED'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox($this->divisionsummary['ISIDUPLOADED'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($this->divisionsummary['ISPBUPLOADED'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
        }
    }
    function groupfooter_2(&$group_row)
    {
        $this->setfieldwidth(40,10);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox($group_row['CENTRENAMEUNI'].' एकूण',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->centresummary['NOOFPLOTS'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->centresummary['AREA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->centresummary['MEASUREDNOOFPLOTS'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox($this->centresummary['AREABYGPS'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->centresummary['ISSELFIEUPLOADED'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox($this->centresummary['ISIDUPLOADED'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($this->centresummary['ISPBUPLOADED'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
        }
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

    function reportfooter()
    {
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        $this->setfieldwidth(40,10);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->summary['NOOFPLOTS'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->summary['AREA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->summary['MEASUREDNOOFPLOTS'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox($this->summary['AREABYGPS'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->summary['ISSELFIEUPLOADED'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox($this->summary['ISIDUPLOADED'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($this->summary['ISPBUPLOADED'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
        }

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