<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class varietycrushing extends swappreport
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
        $this->pdf->SetSubject('Shift');
        $this->pdf->SetKeywords('SHIFT_000.MR');
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));
       // $title = str_pad(' ', 30).'Rajaramnagar, Tal - Dindori Dist - Nashik';
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

    function endreport()
    {
        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output('SECCRUSH_000.pdf', 'I');
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
        $this->textbox('दैनिक ऊस गाळप ऊसजातनिहाय लागवड हंगामनिहाय',195,10,'S','C',1,'siddhanta',13);
        $this->newrow(7);
        $this->textbox('दिनांक : '.$this->slipdate,195,10,'S','C',1,'siddhanta',12);
        $this->newrow(7);
        $this->hline(10,195,$this->liney,'C');
        $this->setfieldwidth(25,10);
        $this->textbox('ऊस जात',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox('आडसाली',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox('पु.हंगामी',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox('सुरु',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox('खॊडवा',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->setfieldwidth(25); 
        $this->textbox('एकूण',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->setfieldwidth(25); 
        $this->textbox('आजअखॆर',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->newrow(7);
        $this->hline(10,195,$this->liney-2,'C'); 
    }
   
    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        $this->hline(10,195,$this->liney+6,'C'); 
        $liney = $this->liney;
        $this->liney = 48;
        $this->vline($this->liney-12,$this->liney+$limit,10);
        $this->vline($this->liney-12,$this->liney+$limit,40);
        $this->vline($this->liney-12,$this->liney+$limit,65);
        $this->vline($this->liney-12,$this->liney+$limit,90);
        $this->vline($this->liney-12,$this->liney+$limit,115);
        $this->vline($this->liney-12,$this->liney+$limit,140);
        $this->vline($this->liney-12,$this->liney+$limit,165);
        $this->vline($this->liney-12,$this->liney+$limit,195);
        $this->liney = $liney;
    }

    function pagefooter($islastpage=false)
    {
    }

    function detail()
    { 
        //$this->newrow();
        $this->liney = 42;
        $dt = DateTime::createFromFormat('d/m/Y',$this->slipdate)->format('d-M-Y');
        $query = "select * from (select tt.*,(select nvl(sum(t.netweight),0) as netweight
        from weightslip t,shift s,fieldslip f,plantationheader p,plantationhangam h,variety v
        where t.shiftcode= s.shiftcode and 
        t.seasoncode=f.seasoncode and
        t.fieldslipnumber=f.fieldslipnumber
        and f.seasoncode=p.seasoncode
        and f.plotnumber=p.plotnumber
        and p.plantationhangamcode=h.plantationhangamcode
        and p.varietycode=v.varietycode
        and t.weighmentdate<='".$dt."'
        and t.seasoncode=".$_SESSION['yearperiodcode']."
        and v.varietycode=tt.varietycode
        and nvl(t.netweight,0)>0
        ) as todate_tot from (
        select varietycode,varietynameuni
        ,nvl(sum(adsali_ton),0)  adsali_ton
        ,nvl(sum(purvahangami_ton),0)  purvahangami_ton
        ,nvl(sum(suru_ton),0)  suru_ton
        ,nvl(sum(khodawa_ton),0)  khodawa_ton
        from (
        select v.varietycode,v.varietynameuni
        ,sum(case when h.commissinorhangamcode = 1 then t.netweight else 0 end)  adsali_ton
        ,sum(case when h.commissinorhangamcode = 2 then t.netweight else 0 end)  purvahangami_ton
        ,sum(case when h.commissinorhangamcode = 3 then t.netweight else 0 end)  suru_ton
        ,sum(case when h.commissinorhangamcode >3 then t.netweight else 0 end)  khodawa_ton
        from weightslip t,shift s,fieldslip f,plantationheader p,plantationhangam h,variety v
        where t.shiftcode= s.shiftcode and 
        t.seasoncode=f.seasoncode and
        t.fieldslipnumber=f.fieldslipnumber
        and f.seasoncode=p.seasoncode
        and f.plotnumber=p.plotnumber
        and p.plantationhangamcode=h.plantationhangamcode
        and p.varietycode=v.varietycode
        and t.weighmentdate='".$dt."' 
        and nvl(t.netweight,0)>0
        group by v.varietycode,v.varietynameuni
        union all
        select v.varietycode,v.varietynameuni
        ,0  adsali_ton
        ,0  purvahangami_ton
        ,0  suru_ton
        ,0  khodawa_ton
        from variety v
        )
        group by varietycode,varietynameuni
        )tt) where todate_tot>0";
        $adsali_ton=0;
        $purvahangami_ton=0;
        $suru_ton=0;
        $khodawa_ton=0;
        $today_tot=0;
        $todate_tot=0;
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        while ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
            $this->setfieldwidth(30,10);
            $this->textbox($row['VARIETYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['ADSALI_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['PURVAHANGAMI_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['SURU_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['KHODAWA_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['ADSALI_TON']+$row['PURVAHANGAMI_TON']+$row['SURU_TON']+$row['KHODAWA_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(30); 
            $this->textbox($this->numformat($row['TODATE_TOT'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C');
            $adsali_ton=$adsali_ton+$row['ADSALI_TON'];
            $purvahangami_ton=$purvahangami_ton+$row['PURVAHANGAMI_TON'];
            $suru_ton=$suru_ton+$row['SURU_TON'];
            $khodawa_ton=$khodawa_ton+$row['KHODAWA_TON'];
            $today_tot=$today_tot+$row['ADSALI_TON']+$row['PURVAHANGAMI_TON']+$row['SURU_TON']+$row['KHODAWA_TON'];
            $todate_tot=$todate_tot+$row['TODATE_TOT'];
        }
        $this->setfieldwidth(30,10);
        $this->textbox('एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($adsali_ton,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($purvahangami_ton,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($suru_ton,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($khodawa_ton,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($today_tot,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(30); 
        $this->textbox($this->numformat($todate_tot,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        //hangamwise
        $query1 = "select nvl(sum(adsali_ton),0)  adsali_ton
        ,nvl(sum(purvahangami_ton),0)  purvahangami_ton
        ,nvl(sum(suru_ton),0)  suru_ton
        ,nvl(sum(khodawa_ton),0)  khodawa_ton
        from (select sum(case when h.commissinorhangamcode = 1 then t.netweight else 0 end)  adsali_ton
        ,sum(case when h.commissinorhangamcode = 2 then t.netweight else 0 end)  purvahangami_ton
        ,sum(case when h.commissinorhangamcode = 3 then t.netweight else 0 end)  suru_ton
        ,sum(case when h.commissinorhangamcode >3 then t.netweight else 0 end)  khodawa_ton
        from weightslip t,shift s,fieldslip f,plantationheader p,plantationhangam h,variety v
        where t.shiftcode= s.shiftcode and 
        t.seasoncode=f.seasoncode and
        t.fieldslipnumber=f.fieldslipnumber
        and f.seasoncode=p.seasoncode
        and f.plotnumber=p.plotnumber
        and p.plantationhangamcode=h.plantationhangamcode
        and p.varietycode=v.varietycode
        and t.weighmentdate<='".$dt."' 
        and t.seasoncode=".$_SESSION['yearperiodcode']."
        and nvl(t.netweight,0)>0
        union all
        select 0  adsali_ton
        ,0  purvahangami_ton
        ,0  suru_ton
        ,0  khodawa_ton
        from dual
        )";
        $result = oci_parse($this->connection, $query1);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C');
            $this->setfieldwidth(30,10);
            $this->textbox('आजअखेर',$this->w,$this->x,'S','L',1,'siddhanta',11);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['ADSALI_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['PURVAHANGAMI_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['SURU_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['KHODAWA_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(26);
            $this->textbox($this->numformat($row['ADSALI_TON']+$row['PURVAHANGAMI_TON']+$row['SURU_TON']+$row['KHODAWA_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        }
        $this->drawlines($this->liney-35); 
        // todate total End
    }
}    
?>