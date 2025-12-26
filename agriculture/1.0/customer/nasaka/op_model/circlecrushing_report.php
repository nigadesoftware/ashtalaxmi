<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a3_l.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class circlecrushing extends swappreport
{
    public $slipdate;
    public $shiftcode;
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
	    $this->pdf = new MYPDF('L', PDF_UNIT, 'A3', true, 'UTF-8', false);
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
        $this->liney = 18;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->textbox('दैनिक ऊस गाळप गटनिहाय',390,10,'S','C',1,'siddhanta',13);
        
        $this->textbox('दिनांक : '.$this->slipdate.' शिफ्ट-'.$this->shiftcode,80,330,'S','L',1,'siddhanta',12);
        $this->newrow(7);
        $dt = DateTime::createFromFormat('d/m/Y',$this->slipdate)->format('d-M-Y');
        $query = "select getcrushingday(".$_SESSION['yearperiodcode'].",'".$dt."') crushingday from dual";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
            $crushingday=$row['CRUSHINGDAY'];
        }
        $this->textbox('गळीत दिवस : '.$crushingday,50,330,'S','L',1,'siddhanta',12);
        $this->newrow(7);
        $this->hline(10,385,$this->liney,'C');
        $this->setfieldwidth(25,10);
        $this->textbox('गट',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(30,60);
        $this->textbox('कार्यक्षेत्रातील',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(30,105);
        $this->textbox('कार्यक्षेत्राबाहेरील',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(25);
        $this->setfieldwidth(20);
        $this->setfieldwidth(20);
        $this->textbox('वाहन',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(30);
        $this->setfieldwidth(40);
        $this->setfieldwidth(40);
        $this->textbox('लागवड हंगाम',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->newrow(7);
        $this->hline(30,127,$this->liney-2,'C'); 
        $this->hline(117,205,$this->liney-2,'C');
        $this->hline(205,330,$this->liney-2,'C');
        $this->setfieldwidth(25,30);
        $this->textbox('सभासद',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('बि.सभासद',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('का.एकूण',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('गेटकेन',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('बैलगाडी',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('जुगाड',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('ट्रक',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('ट्रॅक्टर',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('आडसाली',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('पु.हंगामी',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('सुरु',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('खॊडवा',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('आज',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(30); 
        $this->textbox('आजअखेर',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->newrow(7);
        $this->hline(10,385,$this->liney-2,'C'); 
    }
   
    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        $this->hline(10,385,$this->liney+6,'C'); 
        $liney = $this->liney;
        $this->liney = 44;
        $this->setfieldwidth(20,10);
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
        $this->drawlines($this->liney-30);
    }

    function detail()
    { 
        //$this->newrow();
        $this->liney = 48;
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
        if ($this->shiftcode =='')
        {
            $cond=" and t.weighmentdate='".$dt."'";
            $cond1=" and tt.weighmentdate<='".$dt."'
            and tt.seasoncode= ".$_SESSION['yearperiodcode'];
        }
        else
        {
            $cond=" and t.weighmentdate='".$dt."' and t.shiftcode=".$this->shiftcode;
            $cond1=" and (tt.weighmentdate<'".$dt."' or (tt.weighmentdate='".$dt."' and tt.shiftcode<=".$this->shiftcode."))
            and tt.seasoncode= ".$_SESSION['yearperiodcode'];
        }
        $query = "select t.*,nvl(member_ton,0)+nvl(nonmember_ton,0)+nvl(gatecane_ton,0) as today_tot,
        (select nvl(sum(tt.netweight),0) as netweight
        from weightslip tt,shift ss,fieldslip ff,village vv,circle cc,farmer pp
        where tt.shiftcode= ss.shiftcode and 
        tt.seasoncode=ff.seasoncode and
        tt.fieldslipnumber=ff.fieldslipnumber
        and pp.villagecode=vv.villagecode
        and vv.circlecode=cc.circlecode
        and ff.farmercode=pp.farmercode
         {$cond1} 
        and cc.circlecode=t.circlecode
        and nvl(tt.netweight,0)>0
        ) as todate_tot from (
        select circlecode,circlenameuni
        ,sum(member_ton)  member_ton
        ,sum(nonmember_ton)  nonmember_ton
        ,sum(gatecane_ton)  gatecane_ton
        ,sum(bull_ton)  bull_ton
        ,sum(jugad_ton)  jugad_ton
        ,sum(truck_ton)  truck_ton
        ,sum(tractor_ton)  tractor_ton
        ,nvl(sum(adsali_ton),0)  adsali_ton
        ,nvl(sum(purvahangami_ton),0)  purvahangami_ton
        ,nvl(sum(suru_ton),0)  suru_ton
        ,nvl(sum(khodawa_ton),0)  khodawa_ton
        ,sum(total_ton) total_ton    
        from (    
            with data as (select c.circlecode,c.circlenameuni
            ,case when f.farmercategorycode = 1 then t.netweight else 0 end  member_ton
            ,case when f.farmercategorycode = 2 then t.netweight else 0 end  nonmember_ton
            ,case when f.farmercategorycode = 3 then t.netweight else 0 end  gatecane_ton
            ,case when f.vehiclecategorycode = 3 then t.netweight else 0 end  bull_ton
            ,case when f.vehiclecategorycode = 4 then t.netweight else 0 end  jugad_ton
            ,case when f.vehiclecategorycode = 1 then t.netweight else 0 end  truck_ton
            ,case when f.vehiclecategorycode = 2 then t.netweight else 0 end tractor_ton
            ,case when g.commissinorhangamcode = 1 then t.netweight else 0 end  adsali_ton
            ,case when g.commissinorhangamcode = 2 then t.netweight else 0 end  purvahangami_ton
            ,case when g.commissinorhangamcode = 3 then t.netweight else 0 end  suru_ton
            ,case when g.commissinorhangamcode >3 then t.netweight else 0 end  khodawa_ton
            ,t.netweight total_ton
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
             {$cond} 
            and nvl(t.netweight,0)>0
            union all
            select c.circlecode,c.circlenameuni
            ,0  member_ton
            ,0 nonmember_ton
            ,0  gatecane_ton
            ,0  bull_ton
            ,0  jugad_ton
            ,0  truck_ton
            ,0 tractor_ton
            ,0  adsali_ton
            ,0  purvahangami_ton
            ,0  suru_ton
            ,0  khodawa_ton
            ,0 total_ton
            from circle c
            )
            select circlecode,circlenameuni
            ,sum(member_ton) member_ton
            ,sum(nonmember_ton) nonmember_ton
            ,sum(gatecane_ton) gatecane_ton
            ,sum(bull_ton) bull_ton
            ,sum(jugad_ton) jugad_ton
            ,sum(truck_ton) truck_ton
            ,sum(tractor_ton) tractor_ton
            ,sum(adsali_ton) adsali_ton
            ,sum(purvahangami_ton) purvahangami_ton
            ,sum(suru_ton) suru_ton
            ,sum(khodawa_ton) khodawa_ton
            ,sum(total_ton) total_ton
            from data
            group by circlecode,circlenameuni
        )
        group by circlecode,circlenameuni)t
        order by circlecode";
        $member_ton=0;
        $nonmember_ton=0;
        $gatecane_ton=0;
        $bull_ton=0;
        $jugad_ton=0;
        $truck_ton=0;
        $tractor_ton=0;
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
            $this->textbox($row['CIRCLENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
            $this->setfieldwidth(25,30);
            $this->textbox($this->numformat($row['MEMBER_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['NONMEMBER_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['MEMBER_TON']+$row['NONMEMBER_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['GATECANE_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['BULL_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['JUGAD_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['TRUCK_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['TRACTOR_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['ADSALI_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['PURVAHANGAMI_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['SURU_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['KHODAWA_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['TODAY_TOT'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(30); 
            $this->textbox($this->numformat($row['TODATE_TOT'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            $this->newrow();
            $this->hline(10,385,$this->liney-2,'C'); 
            $member_ton=$member_ton+$row['MEMBER_TON'];
            $nonmember_ton=$nonmember_ton+$row['NONMEMBER_TON'];
            $gatecane_ton=$gatecane_ton+$row['GATECANE_TON'];
            $bull_ton=$bull_ton+$row['BULL_TON'];
            $jugad_ton=$jugad_ton+$row['JUGAD_TON'];
            $truck_ton=$truck_ton+$row['TRUCK_TON'];
            $tractor_ton=$tractor_ton+$row['TRACTOR_TON'];
            $adsali_ton=$adsali_ton+$row['ADSALI_TON'];
            $purvahangami_ton=$purvahangami_ton+$row['PURVAHANGAMI_TON'];
            $suru_ton=$suru_ton+$row['SURU_TON'];
            $khodawa_ton=$khodawa_ton+$row['KHODAWA_TON'];
            $today_tot=$today_tot+$row['TODAY_TOT'];
            $todate_tot=$todate_tot+$row['TODATE_TOT'];
        }
        $this->drawlines($this->liney-30); 
        $this->setfieldwidth(30,10);
        $this->textbox('आज एकूण',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(25,30);
        $this->textbox($this->numformat($member_ton,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($nonmember_ton,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($member_ton+$nonmember_ton,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($gatecane_ton,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($bull_ton,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($jugad_ton,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($truck_ton,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($tractor_ton,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($adsali_ton,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($purvahangami_ton,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($suru_ton,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($khodawa_ton,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($today_tot,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
        //$this->setfieldwidth(30); 
        //$this->textbox($this->numformat($todate_tot,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');

        //membertypewise
        $query1 = "select nvl(sum(member_ton),0)  member_ton
        ,nvl(sum(nonmember_ton),0)  nonmember_ton
        ,nvl(sum(gatecane_ton),0)  gatecane_ton
        ,nvl(sum(bull_ton),0)  bull_ton
        ,nvl(sum(jugad_ton),0)  jugad_ton
        ,nvl(sum(truck_ton),0)  truck_ton
        ,nvl(sum(tractor_ton),0)  tractor_ton
        ,nvl(sum(adsali_ton),0)  adsali_ton
        ,nvl(sum(purvahangami_ton),0)  purvahangami_ton
        ,nvl(sum(suru_ton),0)  suru_ton
        ,nvl(sum(khodawa_ton),0)  khodawa_ton
        from (select sum(case when f.farmercategorycode = 1 then tt.netweight else 0 end)  member_ton
        ,sum(case when f.farmercategorycode = 2 then tt.netweight else 0 end)  nonmember_ton
        ,sum(case when f.farmercategorycode = 3 then tt.netweight else 0 end)  gatecane_ton
        ,sum(case when f.vehiclecategorycode = 3 then tt.netweight else 0 end)  bull_ton
        ,sum(case when f.vehiclecategorycode = 4 then tt.netweight else 0 end)  jugad_ton
        ,sum(case when f.vehiclecategorycode = 1 then tt.netweight else 0 end)  truck_ton
        ,sum(case when f.vehiclecategorycode = 2 then tt.netweight else 0 end) tractor_ton
        ,sum(case when g.commissinorhangamcode = 1 then tt.netweight else 0 end)  adsali_ton
        ,sum(case when g.commissinorhangamcode = 2 then tt.netweight else 0 end)  purvahangami_ton
        ,sum(case when g.commissinorhangamcode = 3 then tt.netweight else 0 end)  suru_ton
        ,sum(case when g.commissinorhangamcode >3 then tt.netweight else 0 end)  khodawa_ton
        ,sum(tt.netweight) total_ton
        from weightslip tt,shift s,fieldslip f
        ,village v,circle c,farmer p,plantationheader h,plantationhangam g
        where tt.shiftcode= s.shiftcode and 
        tt.seasoncode=f.seasoncode and
        tt.fieldslipnumber=f.fieldslipnumber
        and p.villagecode=v.villagecode
        and v.circlecode=c.circlecode
        and f.farmercode=p.farmercode
        and f.seasoncode=h.seasoncode
        and f.plotnumber=h.plotnumber
        and h.plantationhangamcode=g.plantationhangamcode
         {$cond1}
        and nvl(tt.netweight,0)>0
        union all
        select 0  member_ton
        ,0  nonmember_ton
        ,0  gatecane_ton
        ,0 as total_ton
        ,0  bull_ton
        ,0  jugad_ton
        ,0  truck_ton
        ,0  tractor_ton
        ,0  adsali_ton
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
            $this->hline(10,385,$this->liney-2,'C');
            $this->setfieldwidth(30,10);
            $this->textbox('आजअखेर',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
            $this->setfieldwidth(25,30);
            $this->textbox($this->numformat($row['MEMBER_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['NONMEMBER_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['MEMBER_TON']+$row['NONMEMBER_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['GATECANE_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['BULL_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['JUGAD_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['TRUCK_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['TRACTOR_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['ADSALI_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['PURVAHANGAMI_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['SURU_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['KHODAWA_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
            $this->setfieldwidth(25);
            $this->setfieldwidth(30);
            $this->textbox($this->numformat($row['MEMBER_TON']+$row['NONMEMBER_TON']+$row['GATECANE_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10,'','','','B');
        }
        $this->drawlines($this->liney-33); 
        // todate total End
        $this->newrow(20);
        $this->setfieldwidth(50,250);
        $this->textbox('केनयार्ड सुपरवायजर',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(50);
        $this->textbox('मुख्य शेतकी अधिकारी',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
    }
}    
?>