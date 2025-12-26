<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a3_l.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class dailycrushing extends swappreport
{
    public $slipdate;
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

    function endreport()
    {
        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output('CIRCRUSH_000_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 12;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->newrow(4);
        $this->textbox('दैनिक ऊस गाळप',70,60,'S','C',1,'siddhanta',13);
        $this->textbox('दिनांक : '.$this->slipdate,50,10,'S','L',1,'siddhanta',12);
        $dt = DateTime::createFromFormat('d/m/Y',$this->slipdate)->format('d-M-Y');
        $query = "select getcrushingday(".$_SESSION['yearperiodcode'].",'".$dt."') crushingday from dual";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
            $crushingday=$row['CRUSHINGDAY'];
        }
        $this->textbox('गळीत दिवस : '.$crushingday,50,350,'S','L',1,'siddhanta',12);
    }
   
    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        $this->hline(10,405,$this->liney+6,'C'); 
        $liney = $this->liney;
        $this->liney = 44;
        $this->setfieldwidth(40,10);
        $this->vline($this->liney-12,$this->liney+$limit-5,$this->x);
        $this->setfieldwidth(25);
        $this->vline($this->liney-12,$this->liney+$limit-5,$this->x);
        $this->setfieldwidth(25);
        $this->vline($this->liney-7,$this->liney+$limit-5,$this->x);
        $this->setfieldwidth(25);
        $this->vline($this->liney-7,$this->liney+$limit-5,$this->x);
        $this->setfieldwidth(25);
        $this->vline($this->liney-12,$this->liney+$limit-5,$this->x);
        $this->setfieldwidth(25);
        $this->vline($this->liney-12,$this->liney+$limit-5,$this->x);
        $this->setfieldwidth(25);
        $this->vline($this->liney-7,$this->liney+$limit-5,$this->x);
        $this->setfieldwidth(25);
        $this->vline($this->liney-7,$this->liney+$limit-5,$this->x);
        $this->setfieldwidth(25);
        $this->vline($this->liney-7,$this->liney+$limit-5,$this->x);
        $this->setfieldwidth(25);
        $this->vline($this->liney-12,$this->liney+$limit-5,$this->x);
        $this->setfieldwidth(25);
        $this->vline($this->liney-7,$this->liney+$limit-5,$this->x);
        $this->setfieldwidth(25);
        $this->vline($this->liney-7,$this->liney+$limit-5,$this->x);
        $this->setfieldwidth(25);
        $this->vline($this->liney-7,$this->liney+$limit-5,$this->x);
        $this->setfieldwidth(25);
        $this->vline($this->liney-12,$this->liney+$limit-5,$this->x);
        $this->setfieldwidth(30);
        $this->vline($this->liney-12,$this->liney+$limit-5,$this->x);
        $this->setfieldwidth(35);
        $this->vline($this->liney-12,$this->liney+$limit-5,$this->x);
        $this->liney = $liney;
        
        
    }

    function pagefooter($islastpage=false)
    {
        //$this->drawlines($this->liney-30);
    }

    function detail()
    { 
        //$this->newrow();
        $this->liney = 25;
        $query0 = "update fieldslip f
        set f.farmercategorycode=(select p.farmercategorycode from farmer p 
        where p.farmercode=f.farmercode)
        ,flag=1
        where f.flag=0";
        $result = oci_parse($this->connection, $query0);
        if (oci_execute($result,OCI_NO_AUTO_COMMIT))
        {
            oci_commit($this->connection);
        }
        else
        {
            oci_rollback($this->connection);
        }
        $dt = DateTime::createFromFormat('d/m/Y',$this->slipdate)->format('d-M-Y');
        $query = "
        with today as (select circlecode,circlenameuni
        ,sum(netweight) netweight from(select c.circlecode,c.circlenameuni
        ,netweight
        from weightslip t,shift s,fieldslip f
        ,village v,circle c,farmer p,plantationheader h,plantationhangam g
        where t.shiftcode= s.shiftcode and 
        t.seasoncode=f.seasoncode and
        t.fieldslipnumber=f.fieldslipnumber
        and p.villagecode=v.villagecode
        and v.circlecode=c.circlecode
        and f.farmercode=p.farmercode
        and f.seasoncode=h.seasoncode
        and f.plotnumber=h.plotnumber
        and h.plantationhangamcode=g.plantationhangamcode
        and t.weighmentdate='{$dt}' 
        and t.seasoncode=".$_SESSION['yearperiodcode']." 
        and nvl(t.netweight,0)>0
        union all
        select c.circlecode,c.circlenameuni
        ,0  membenetweight
        from circle c
        )group by circlecode,circlenameuni)
        ,todate as (select circlecode,circlenameuni
        ,sum(netweight) netweight from(select c.circlecode,c.circlenameuni
        ,netweight
        from weightslip t,shift s,fieldslip f
        ,village v,circle c,farmer p,plantationheader h,plantationhangam g
        where t.shiftcode= s.shiftcode and 
        t.seasoncode=f.seasoncode and
        t.fieldslipnumber=f.fieldslipnumber
        and p.villagecode=v.villagecode
        and v.circlecode=c.circlecode
        and f.farmercode=p.farmercode
        and f.seasoncode=h.seasoncode
        and f.plotnumber=h.plotnumber
        and h.plantationhangamcode=g.plantationhangamcode
        and t.weighmentdate<='{$dt}' 
        and t.seasoncode=".$_SESSION['yearperiodcode']." 
        and nvl(t.netweight,0)>0
        union all
        select c.circlecode,c.circlenameuni
        ,0  membenetweight
        from circle c
        )group by circlecode,circlenameuni)
        ,summary  as (select sum(y.netweight) todaytonnage,sum(t.netweight) todatetonnage 
        from todate t,today y
        where t.circlecode=y.circlecode
        having sum(t.netweight)>0)
        ,daily as (select t.circlecode,t.circlenameuni
        ,sum(y.netweight) as todaytonnage
        ,sum(t.netweight) as todatetonnage
        from todate t,today y
        where t.circlecode=y.circlecode
        having sum(t.netweight)>0
        group by t.circlecode,t.circlenameuni)
        select d.circlecode,d.circlenameuni
        ,d.todaytonnage,case when s.todaytonnage<>0 then round(d.todaytonnage*100/s.todaytonnage,0) else 0 end todaycontriper
        ,d.todatetonnage,case when s.todatetonnage<>0 then round(d.todatetonnage*100/s.todatetonnage,0) else 0 end todatecontriper
        from daily d, summary s
        order by circlecode,circlenameuni
        ";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        
        $this->setfieldwidth(27,10);
        $this->vline($this->liney-2,$this->liney+4,$this->x);
        $this->textbox('विभाग',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(20);
        $this->textbox('आज',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w,'D');
        $this->setfieldwidth(8);
        $this->textbox('%',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
        $this->setfieldwidth(27); 
        $this->textbox('आजअखेर',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w,'D');
        $this->setfieldwidth(8); 
        $this->textbox('%',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
        $this->hline(10,100,$this->liney-2,'C'); 
        $this->newrow(6);
        $this->hline(10,100,$this->liney,'C');
        $todaytonnage=0;
        $todatetonnage=0;
        while ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
            $this->setfieldwidth(27,10);
            $this->vline($this->liney-2,$this->liney+4,$this->x);
            $this->textbox($row['CIRCLENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
            $this->setfieldwidth(20);
            $this->textbox($this->numformat($row['TODAYTONNAGE'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w,'D');
            $this->setfieldwidth(8);
            $this->textbox($this->numformat($row['TODAYCONTRIPER'],0),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
            $this->setfieldwidth(27);
            $this->textbox($this->numformat($row['TODATETONNAGE'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w,'D');
            $this->setfieldwidth(8);
            $this->textbox($this->numformat($row['TODATECONTRIPER'],0),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
            $this->newrow(5);
            $this->hline(10,100,$this->liney-1,'D'); 
            $todaytonnage=$todaytonnage+$row['TODAYTONNAGE'];
            $todatetonnage=$todatetonnage+$row['TODATETONNAGE'];
        }
        $this->setfieldwidth(27,10);
        $this->vline($this->liney-2,$this->liney+4,$this->x);
        $this->textbox('विभाग एकूण',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
        $this->setfieldwidth(20);
        $this->textbox($this->numformat($todaytonnage,3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w,'D');
        $this->setfieldwidth(8);
        $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
        $this->setfieldwidth(27);
        $this->textbox($this->numformat($todatetonnage,3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w,'D');
        $this->setfieldwidth(8);
        $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
        $this->hline(10,100,$this->liney-1,'C'); 
        $this->newrow(5);
        $this->hline(10,100,$this->liney-1,'C'); 
        $this->newrow(3);

        $query = "
        with today as (select varietycode,varietynameuni
        ,sum(netweight) netweight from(select c.varietycode,c.varietynameuni
        ,netweight
        from weightslip t,shift s,fieldslip f
        ,village v,variety c,farmer p,plantationheader h,plantationhangam g
        where t.shiftcode= s.shiftcode and 
        t.seasoncode=f.seasoncode and
        t.fieldslipnumber=f.fieldslipnumber
        and p.villagecode=v.villagecode
        and h.varietycode=c.varietycode
        and f.farmercode=p.farmercode
        and f.seasoncode=h.seasoncode
        and f.plotnumber=h.plotnumber
        and h.plantationhangamcode=g.plantationhangamcode
        and t.weighmentdate='{$dt}' 
        and t.seasoncode=".$_SESSION['yearperiodcode']." 
        and nvl(t.netweight,0)>0
        union all
        select c.varietycode,c.varietynameuni
        ,0  membenetweight
        from variety c
        )group by varietycode,varietynameuni)
        ,todate as (select varietycode,varietynameuni
        ,sum(netweight) netweight from(select c.varietycode,c.varietynameuni
        ,netweight
        from weightslip t,shift s,fieldslip f
        ,village v,variety c,farmer p,plantationheader h,plantationhangam g
        where t.shiftcode= s.shiftcode and 
        t.seasoncode=f.seasoncode and
        t.fieldslipnumber=f.fieldslipnumber
        and p.villagecode=v.villagecode
        and h.varietycode=c.varietycode
        and f.farmercode=p.farmercode
        and f.seasoncode=h.seasoncode
        and f.plotnumber=h.plotnumber
        and h.plantationhangamcode=g.plantationhangamcode
        and t.weighmentdate<='{$dt}' 
        and t.seasoncode=".$_SESSION['yearperiodcode']." 
        and nvl(t.netweight,0)>0
        union all
        select c.varietycode,c.varietynameuni
        ,0  membenetweight
        from variety c
        )group by varietycode,varietynameuni)
        ,summary  as (select sum(y.netweight) todaytonnage,sum(t.netweight) todatetonnage 
        from todate t,today y
        where t.varietycode=y.varietycode
        having sum(t.netweight)>0)
        ,daily as (select t.varietycode,t.varietynameuni
        ,sum(y.netweight) as todaytonnage
        ,sum(t.netweight) as todatetonnage
        from todate t,today y
        where t.varietycode=y.varietycode
        having sum(t.netweight)>0
        group by t.varietycode,t.varietynameuni)
        select d.varietycode,d.varietynameuni
        ,d.todaytonnage,case when s.todaytonnage<>0 then round(d.todaytonnage*100/s.todaytonnage,0) else 0 end todaycontriper
        ,d.todatetonnage,case when s.todatetonnage<>0 then round(d.todatetonnage*100/s.todatetonnage,0) else 0 end todatecontriper
        from daily d, summary s
        order by varietycode,varietynameuni
        ";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        
        $this->setfieldwidth(27,10);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox('ऊसजात',$this->w,$this->x,'S','L',1,'siddhanta',9,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->setfieldwidth(20);
        $this->textbox('आज',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w,'D');
        $this->setfieldwidth(8);
        $this->textbox('%',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->setfieldwidth(27); 
        $this->textbox('आजअखेर',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w,'D');
        $this->setfieldwidth(8); 
        $this->textbox('%',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->hline(10,100,$this->liney-2,'C'); 
        $this->newrow(5);
        $this->hline(10,100,$this->liney,'C');
        while ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
            $this->setfieldwidth(27,10);
            $this->vline($this->liney-2,$this->liney+4,$this->x);
            $this->textbox($row['VARIETYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','Y','','');
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
            $this->setfieldwidth(20);
            $this->textbox($this->numformat($row['TODAYTONNAGE'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w,'D');
            $this->setfieldwidth(8);
            $this->textbox($this->numformat($row['TODAYCONTRIPER'],0),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
            $this->setfieldwidth(27);
            $this->textbox($this->numformat($row['TODATETONNAGE'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w,'D');
            $this->setfieldwidth(8);
            $this->textbox($this->numformat($row['TODATECONTRIPER'],0),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
            $this->newrow(5);
            $this->hline(10,100,$this->liney-1,'D'); 
        }
        $this->setfieldwidth(27,10);
        $this->vline($this->liney-2,$this->liney+4,$this->x);
        $this->textbox('ऊसजात एकूण',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
        $this->setfieldwidth(20);
        $this->textbox($this->numformat($todaytonnage,3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w,'D');
        $this->setfieldwidth(8);
        $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
        $this->setfieldwidth(27);
        $this->textbox($this->numformat($todatetonnage,3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w,'D');
        $this->setfieldwidth(8);
        $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
        $this->hline(10,100,$this->liney-1,'C'); 
        $this->newrow(5);
        $this->hline(10,100,$this->liney-1,'C'); 
        $this->newrow(5);
        $this->setfieldwidth(50,10);
        $this->textbox('केनयार्ड सुपरवायजर',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(50);
        $this->textbox('मुख्य शेतकी अधिकारी',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');


        $query = "
        with today as (select talukacode,talukanameuni
        ,sum(netweight) netweight from(select c.talukacode,c.talukanameuni
        ,netweight
        from weightslip t,shift s,fieldslip f
        ,village v,taluka c,farmer p,plantationheader h,plantationhangam g
        where t.shiftcode= s.shiftcode and 
        t.seasoncode=f.seasoncode and
        t.fieldslipnumber=f.fieldslipnumber
        and p.villagecode=v.villagecode
        and v.talukacode=c.talukacode
        and f.farmercode=p.farmercode
        and f.seasoncode=h.seasoncode
        and f.plotnumber=h.plotnumber
        and h.plantationhangamcode=g.plantationhangamcode
        and t.weighmentdate='{$dt}' 
        and t.seasoncode=".$_SESSION['yearperiodcode']." 
        and nvl(t.netweight,0)>0
        union all
        select c.talukacode,c.talukanameuni
        ,0  membenetweight
        from taluka c
        )group by talukacode,talukanameuni)
        ,todate as (select talukacode,talukanameuni
        ,sum(netweight) netweight from(select c.talukacode,c.talukanameuni
        ,netweight
        from weightslip t,shift s,fieldslip f
        ,village v,taluka c,farmer p,plantationheader h,plantationhangam g
        where t.shiftcode= s.shiftcode and 
        t.seasoncode=f.seasoncode and
        t.fieldslipnumber=f.fieldslipnumber
        and p.villagecode=v.villagecode
        and v.talukacode=c.talukacode
        and f.farmercode=p.farmercode
        and f.seasoncode=h.seasoncode
        and f.plotnumber=h.plotnumber
        and h.plantationhangamcode=g.plantationhangamcode
        and t.weighmentdate<='{$dt}' 
        and t.seasoncode=".$_SESSION['yearperiodcode']." 
        and nvl(t.netweight,0)>0
        union all
        select c.talukacode,c.talukanameuni
        ,0  membenetweight
        from taluka c
        )group by talukacode,talukanameuni)
        ,summary  as (select sum(y.netweight) todaytonnage,sum(t.netweight) todatetonnage 
        from todate t,today y
        where t.talukacode=y.talukacode
        having sum(t.netweight)>0)
        ,daily as (select t.talukacode,t.talukanameuni
        ,sum(y.netweight) as todaytonnage
        ,sum(t.netweight) as todatetonnage
        from todate t,today y
        where t.talukacode=y.talukacode
        having sum(t.netweight)>0
        group by t.talukacode,t.talukanameuni)
        select d.talukacode,d.talukanameuni
        ,d.todaytonnage,case when s.todaytonnage<>0 then round(d.todaytonnage*100/s.todaytonnage,0) else 0 end todaycontriper
        ,d.todatetonnage,case when s.todatetonnage<>0 then round(d.todatetonnage*100/s.todatetonnage,0) else 0 end todatecontriper
        from daily d, summary s
        order by talukacode,talukanameuni
        ";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $this->liney = 25;
        $this->setfieldwidth(17,110);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox('तालुका',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->setfieldwidth(10);
        $this->textbox('संख्या',$this->w,$this->x,'S','R',1,'siddhanta',8,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w,'D');
        $this->setfieldwidth(20);
        $this->textbox('आज',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w,'D');
        $this->setfieldwidth(8);
        $this->textbox('%',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->setfieldwidth(27); 
        $this->textbox('आजअखेर',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w,'D');
        $this->setfieldwidth(8); 
        $this->textbox('%',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->hline(110,200,$this->liney-2,'C'); 
        $this->newrow(5);
        $this->hline(110,200,$this->liney,'C');
        while ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
            $query1 = "select sum(cnt) as cnt from 
            (select f.vehiclecode,f.tyregadicode,count(*) as cnt
            from weightslip t,shift s,fieldslip f
            ,village v,taluka c,farmer p,plantationheader h,plantationhangam g
            where t.shiftcode= s.shiftcode and 
            t.seasoncode=f.seasoncode and
            t.fieldslipnumber=f.fieldslipnumber
            and p.villagecode=v.villagecode
            and v.talukacode=c.talukacode
            and f.farmercode=p.farmercode
            and f.seasoncode=h.seasoncode
            and f.plotnumber=h.plotnumber
            and h.plantationhangamcode=g.plantationhangamcode
            and t.weighmentdate='{$dt}'
            and t.seasoncode=".$_SESSION['yearperiodcode']."  
            and nvl(t.netweight,0)>0
            and v.talukacode=".$row['TALUKACODE']."
            group by f.vehiclecode,f.tyregadicode)";
            $result1 = oci_parse($this->connection, $query1);
            $r1 = oci_execute($result1);
            if ($row1 = oci_fetch_array($result1,oci_assoc+OCI_RETURN_NULLS))
            {
                $talukacount=$row1['CNT'];
            }
            else 
            {
                $talukacount=0;
            }
            $this->setfieldwidth(17,110);
            $this->vline($this->liney-2,$this->liney+4,$this->x);
            $this->textbox($row['TALUKANAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
            $this->setfieldwidth(10);
            $this->textbox($talukacount,$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',9);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w,'D');
            $this->setfieldwidth(20);
            $this->textbox($this->numformat($row['TODAYTONNAGE'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w,'D');
            $this->setfieldwidth(8);
            $this->textbox($this->numformat($row['TODAYCONTRIPER'],0),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
            $this->setfieldwidth(27);
            $this->textbox($this->numformat($row['TODATETONNAGE'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w,'D');
            $this->setfieldwidth(8);
            $this->textbox($this->numformat($row['TODATECONTRIPER'],0),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            $this->newrow(5);
            $this->hline(110,200,$this->liney-1,'D'); 
        }
        $this->setfieldwidth(27,110);
        $this->vline($this->liney-2,$this->liney+4,$this->x);
        $this->textbox('तालुका एकूण',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
        $this->setfieldwidth(20);
        $this->textbox($this->numformat($todaytonnage,3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w,'D');
        $this->setfieldwidth(8);
        $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
        $this->setfieldwidth(27);
        $this->textbox($this->numformat($todatetonnage,3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w,'D');
        $this->setfieldwidth(8);
        $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
        $this->hline(110,200,$this->liney-1,'C'); 
        $this->newrow(5);
        $this->hline(110,200,$this->liney-1,'C'); 
        $this->newrow(1);

        $query = "
        with today as (select vehiclecategorycode,vehiclecategorynameuni
        ,sum(netweight) netweight from(select c.vehiclecategorycode,c.vehiclecategorynameuni
        ,netweight
        from weightslip t,shift s,fieldslip f
        ,village v,vehiclecategory c,farmer p,plantationheader h,plantationhangam g
        where t.shiftcode= s.shiftcode and 
        t.seasoncode=f.seasoncode and
        t.fieldslipnumber=f.fieldslipnumber
        and p.villagecode=v.villagecode
        and f.vehiclecategorycode=c.vehiclecategorycode
        and f.farmercode=p.farmercode
        and f.seasoncode=h.seasoncode
        and f.plotnumber=h.plotnumber
        and h.plantationhangamcode=g.plantationhangamcode
        and t.weighmentdate='{$dt}' 
        and t.seasoncode=".$_SESSION['yearperiodcode']." 
        and nvl(t.netweight,0)>0
        union all
        select c.vehiclecategorycode,c.vehiclecategorynameuni
        ,0  membenetweight
        from vehiclecategory c
        )group by vehiclecategorycode,vehiclecategorynameuni)
        ,todate as (select vehiclecategorycode,vehiclecategorynameuni
        ,sum(netweight) netweight from(select c.vehiclecategorycode,c.vehiclecategorynameuni
        ,netweight
        from weightslip t,shift s,fieldslip f
        ,village v,vehiclecategory c,farmer p,plantationheader h,plantationhangam g
        where t.shiftcode= s.shiftcode and 
        t.seasoncode=f.seasoncode and
        t.fieldslipnumber=f.fieldslipnumber
        and p.villagecode=v.villagecode
        and f.vehiclecategorycode=c.vehiclecategorycode
        and f.farmercode=p.farmercode
        and f.seasoncode=h.seasoncode
        and f.plotnumber=h.plotnumber
        and h.plantationhangamcode=g.plantationhangamcode
        and t.weighmentdate<='{$dt}' 
        and t.seasoncode=".$_SESSION['yearperiodcode']." 
        and nvl(t.netweight,0)>0
        union all
        select c.vehiclecategorycode,c.vehiclecategorynameuni
        ,0  membenetweight
        from vehiclecategory c
        )group by vehiclecategorycode,vehiclecategorynameuni)
        ,summary  as (select sum(y.netweight) todaytonnage,sum(t.netweight) todatetonnage 
        from todate t,today y
        where t.vehiclecategorycode=y.vehiclecategorycode
        having sum(t.netweight)>0)
        ,daily as (select t.vehiclecategorycode,t.vehiclecategorynameuni
        ,sum(y.netweight) as todaytonnage
        ,sum(t.netweight) as todatetonnage
        from todate t,today y
        where t.vehiclecategorycode=y.vehiclecategorycode
        having sum(t.netweight)>0
        group by t.vehiclecategorycode,t.vehiclecategorynameuni)
        select d.vehiclecategorycode,d.vehiclecategorynameuni
        ,d.todaytonnage,case when s.todaytonnage<>0 then round(d.todaytonnage*100/s.todaytonnage,0) else 0 end todaycontriper
        ,d.todatetonnage,case when s.todatetonnage<>0 then round(d.todatetonnage*100/s.todatetonnage,0) else 0 end todatecontriper
        from daily d, summary s
        order by vehiclecategorycode,vehiclecategorynameuni
        ";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $this->setfieldwidth(27,110);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox('वाहन',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->setfieldwidth(20);
        $this->textbox('आज',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w,'D');
        $this->setfieldwidth(8);
        $this->textbox('%',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->setfieldwidth(27); 
        $this->textbox('आजअखेर',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w,'D');
        $this->setfieldwidth(8); 
        $this->textbox('%',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->hline(110,200,$this->liney-1,'C'); 
        $this->newrow(5);
        $this->hline(110,200,$this->liney,'C');
        while ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
            $this->setfieldwidth(27,110);
            $this->vline($this->liney-2,$this->liney+4,$this->x);
            $this->textbox($row['VEHICLECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','Y','','');
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
            $this->setfieldwidth(20);
            $this->textbox($this->numformat($row['TODAYTONNAGE'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w,'D');
            $this->setfieldwidth(8);
            $this->textbox($this->numformat($row['TODAYCONTRIPER'],0),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
            $this->setfieldwidth(27);
            $this->textbox($this->numformat($row['TODATETONNAGE'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w,'D');
            $this->setfieldwidth(8);
            $this->textbox($this->numformat($row['TODATECONTRIPER'],0),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            $this->newrow(5);
            $this->hline(110,200,$this->liney-1,'D'); 
        }
        $this->setfieldwidth(27,110);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox('वाहन एकूण',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->setfieldwidth(20);
        $this->textbox($this->numformat($todaytonnage,3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w,'D');
        $this->setfieldwidth(8);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->setfieldwidth(27);
        $this->textbox($this->numformat($todatetonnage,3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w,'D');
        $this->setfieldwidth(8);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->hline(110,200,$this->liney-1,'C'); 
        $this->newrow(5);
        $this->hline(110,200,$this->liney-1,'C'); 
        $this->newrow(1);


        $query = "
        
        with today as (select sum(a) d_a,sum(b) d_b, sum(c) d_c,sum(d) d_d, sum(e) d_e,sum(f) d_f,sum(g) d_g
        from (select case when nvl(distance,0) between 0 and 10 then  netweight end as a,
           case     when nvl(distance,0) between 11 and 20 then netweight end b,
           case     when nvl(distance,0) between 21 and 30 then netweight end c,
           case     when nvl(distance,0) between 31 and 40 then netweight end d,
           case     when nvl(distance,0) between 41 and 50 then netweight end e, 
           case     when nvl(distance,0) between 51 and 60 then netweight end f, 
           case     when nvl(distance,0) >=61 then netweight end g 
           from 
        (
        select distance
        ,sum(netweight) netweight from 
        (select f.vehiclecategorycode
        ,case when nvl(v.distancetrucktractor,0)>0 then 
            case when f.vehiclecategorycode in (1,2) then v.distancetrucktractor
            else v.distancetyregadi end
        when nvl(v.distancetyregadi,0)>0 then
            case when f.vehiclecategorycode in (1,2) then v.distancetrucktractor
            else v.distancetyregadi end
        else  0
        end distance,netweight
        from weightslip t,fieldslip f
        ,village v,shift c,farmer p,plantationheader h,plantationhangam g       
        where t.seasoncode=f.seasoncode and
        t.fieldslipnumber=f.fieldslipnumber
        and p.villagecode=v.villagecode
        and t.shiftcode=c.shiftcode
        and f.farmercode=p.farmercode
        and f.seasoncode=h.seasoncode
        and f.plotnumber=h.plotnumber
        and h.plantationhangamcode=g.plantationhangamcode       
       and t.weighmentdate='{$dt}'
        and t.seasoncode=".$_SESSION['yearperiodcode']." 
        and nvl(t.netweight,0)>0)group by distance)))
        ,todate as (select sum(a) t_a,sum(b) t_b, sum(c) t_c,sum(d) t_d, sum(e) t_e,sum(f) t_f,sum(g) t_g
    from (select case when nvl(distance,0) between 0 and 10 then  netweight end as a,
           case     when nvl(distance,0) between 11 and 20 then netweight end b,
           case     when nvl(distance,0) between 21 and 30 then netweight end c,
           case     when nvl(distance,0) between 31 and 40 then netweight end d,
           case     when nvl(distance,0) between 41 and 50 then netweight end e, 
           case     when nvl(distance,0) between 51 and 60 then netweight end f, 
           case     when nvl(distance,0) >=61 then netweight end g 
           from 
        (select distance
        ,sum(netweight) netweight from 
        (select f.vehiclecategorycode
        ,case when nvl(v.distancetrucktractor,0)>0 then 
            case when f.vehiclecategorycode in (1,2) then v.distancetrucktractor
            else v.distancetyregadi end
        when nvl(v.distancetyregadi,0)>0 then
            case when f.vehiclecategorycode in (1,2) then v.distancetrucktractor
            else v.distancetyregadi end
        else  0
        end distance,netweight
        from weightslip t,fieldslip f
        ,village v,shift c,farmer p,plantationheader h,plantationhangam g       
        where t.seasoncode=f.seasoncode and
        t.fieldslipnumber=f.fieldslipnumber
        and p.villagecode=v.villagecode
        and t.shiftcode=c.shiftcode
        and f.farmercode=p.farmercode
        and f.seasoncode=h.seasoncode
        and f.plotnumber=h.plotnumber
        and h.plantationhangamcode=g.plantationhangamcode       
       and t.weighmentdate<='{$dt}'
        and t.seasoncode=".$_SESSION['yearperiodcode']." 
        and nvl(t.netweight,0)>0)group by distance)))
        ,data as (select D_A,D_B,D_C,D_D,D_E,D_F,D_G
        ,NVL(D_A,0)+NVL(D_B,0)+NVL(D_C,0)+NVL(D_D,0)+NVL(D_E,0)+NVL(D_F,0)+NVL(D_G,0) D_TOT
        ,T_A,T_B,T_C,T_D,T_E,T_F,T_G
        ,NVL(T_A,0)+NVL(T_B,0)+NVL(T_C,0)+NVL(T_D,0)+NVL(T_E,0)+NVL(T_F,0)+NVL(T_G,0) T_TOT
        from today d,todate t)
        select '0 ते 10' PERIOD,D_A todaytonnage,case when D_TOT<>0 then round(D_A*100/D_TOT,0) else 0 end todaycontriper
        ,T_A todatetonnage,case when T_TOT<>0 then round(T_A*100/T_TOT,0) else 0 end todatecontriper 
        from data
        union all
        select '11 ते 20' PERIOD,D_B todaytonnage,case when D_TOT<>0 then round(D_B*100/D_TOT,0) else 0 end todaycontriper
        ,T_B todatetonnage,case when T_TOT<>0 then round(T_B*100/T_TOT,0) else 0 end todatecontriper 
        from data
        union all
        select '21 ते 30' PERIOD,D_C todaytonnage,case when D_TOT<>0 then round(D_C*100/D_TOT,0) else 0 end todaycontriper
        ,T_C todatetonnage,case when T_TOT<>0 then round(T_C*100/T_TOT,0) else 0 end todatecontriper 
        from data
        union all
        select '31 ते 40' PERIOD,D_D todaytonnage,case when D_TOT<>0 then round(D_D*100/D_TOT,0) else 0 end todaycontriper
        ,T_D todatetonnage,case when T_TOT<>0 then round(T_D*100/T_TOT,0) else 0 end todatecontriper 
        from data
        union all
        select '41 ते 50' PERIOD,D_E todaytonnage,case when D_TOT<>0 then round(D_E*100/D_TOT,0) else 0 end todaycontriper
        ,T_E todatetonnage,case when T_TOT<>0 then round(T_E*100/T_TOT,0) else 0 end todatecontriper 
        from data
        union all
        select '51 ते 60' PERIOD,D_F todaytonnage,case when D_TOT<>0 then round(D_F*100/D_TOT,0) else 0 end todaycontriper
        ,T_f todatetonnage,case when T_TOT<>0 then round(T_F*100/T_TOT,0) else 0 end todatecontriper 
        from data
        union all
        select '>=61' PERIOD,D_G todaytonnage,case when D_TOT<>0 then round(D_G*100/D_TOT,0) else 0 end todaycontriper
        ,T_g todatetonnage,case when T_TOT<>0 then round(T_G*100/T_TOT,0) else 0 end todatecontriper 
        from data
       
        ";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $this->setfieldwidth(27,110);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox('अंतर',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->setfieldwidth(20);
        $this->textbox('आज',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w,'D');
        $this->setfieldwidth(8);
        $this->textbox('%',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->setfieldwidth(27); 
        $this->textbox('आजअखेर',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w,'D');
        $this->setfieldwidth(8); 
        $this->textbox('%',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->hline(110,200,$this->liney-2,'C'); 
        $this->newrow(5);
        $this->hline(110,200,$this->liney,'C');
        while ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
            $this->setfieldwidth(27,110);
            $this->vline($this->liney-2,$this->liney+4,$this->x);
            $this->textbox($row['PERIOD'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','Y','','');
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
            $this->setfieldwidth(20);
            $this->textbox($this->numformat($row['TODAYTONNAGE'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w,'D');
            $this->setfieldwidth(8);
            $this->textbox($this->numformat($row['TODAYCONTRIPER'],0),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w);
            $this->setfieldwidth(27);
            $this->textbox($this->numformat($row['TODATETONNAGE'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+4,$this->x+$this->w,'D');
            $this->setfieldwidth(8);
            $this->textbox($this->numformat($row['TODATECONTRIPER'],0),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            $this->newrow(5);
            $this->hline(110,200,$this->liney-1,'D'); 
        }
        $this->setfieldwidth(27,110);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox('अंतर एकूण',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->setfieldwidth(20);
        $this->textbox($this->numformat($todaytonnage,3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w,'D');
        $this->setfieldwidth(8);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->setfieldwidth(27);
        $this->textbox($this->numformat($todatetonnage,3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w,'D');
        $this->setfieldwidth(8);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->hline(110,200,$this->liney-1,'C'); 
        $this->newrow(5);
        $this->hline(110,200,$this->liney-1,'C'); 
        //$this->newrow(5);

    }
}    
?>