<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class advancecrushing extends swappreport
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
        $this->pdf->Output('SHIFT_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 15;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->textbox('दैनिक ऊस गाळप अडव्हांस',195,10,'S','C',1,'siddhanta',15);
        $this->newrow(7);
        $this->textbox('दिनांक : '.$this->slipdate,195,10,'S','C',1,'SakalMarathiNormal922',13);
        $this->newrow(7);
        $this->hline(10,195,$this->liney,'C');
        $this->setfieldwidth(30,35);
        $this->textbox('अडव्हांस',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(35);
        $this->textbox('बिगर अडव्हांस',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(35); 
        $this->textbox('एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(20,10);
        $this->textbox('शिफ्ट',$this->w,$this->x,'S','L',1,'siddhanta',11);
        // Bullockcart
        $this->newrow(7);
        $this->hline(28,195,$this->liney,'C');
        $this->setfieldwidth(17,25);
        $this->textbox('संख्या',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox('मे.टन',$this->w,$this->x,'S','R',1,'siddhanta',11);
        //  Jugad
        $this->setfieldwidth(12);
        $this->textbox('संख्या',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox('मे.टन',$this->w,$this->x,'S','R',1,'siddhanta',11);
        // Truck
        $this->setfieldwidth(12);
        $this->textbox('संख्या',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox('मे.टन',$this->w,$this->x,'S','R',1,'siddhanta',11);
        
         //$this->drawlines($limit);
    }
   
    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        $this->hline(10,195,$this->liney+6,'C'); 
        $liney = $this->liney;
        $this->liney = 41;
        $this->setcolwidth(0,10);
        $this->vline($this->liney-12,$this->liney+$limit,$this->x);
        $this->setcolwidth(18);
        $this->vline($this->liney-12,$this->liney+$limit,$this->x);
        $this->setcolwidth(14);
        $this->vline($this->liney-5,$this->liney+$limit,$this->x);
        $this->setcolwidth(20);
        $this->vline($this->liney-12,$this->liney+$limit,$this->x);
        $this->setcolwidth(12);
        $this->vline($this->liney-5,$this->liney+$limit,$this->x);
        $this->setcolwidth(20);
        $this->vline($this->liney-12,$this->liney+$limit,$this->x);
        $this->setcolwidth(12);
        $this->vline($this->liney-5,$this->liney+$limit,$this->x);
        $this->vline($this->liney-5,$this->liney+$limit,$this->x);
        $this->setcolwidth(25);
        $this->vline($this->liney-12,$this->liney+$limit,$this->x);
        $this->liney = $liney;
        $this->setcolwidth(0,10);
    }

    function pagefooter($islastpage=false)
    {
    }

    function detail()
    { 
        //$this->newrow();
        $this->liney = 45;
        $dt = DateTime::createFromFormat('d/m/Y',$this->slipdate)->format('d-M-Y');
        $query = "select s.shiftcode,s.shiftnameuni
        ,sum(case when c.contractcategorycode = 1 then t.netweight else 0 end)  adv_ton
        ,sum(case when c.contractcategorycode <> 1 then t.netweight else 0 end)  nonadv_ton
        ,sum(case when c.contractcategorycode = 1 then 1 else 0 end)  adv_cnt
        ,sum(case when c.contractcategorycode <> 1 then 1 else 0 end)  nonadv_cnt
        ,sum(1) tot_cnt
        ,sum(t.netweight) tot_ton 
        from weightslip t,shift s,fieldslip f,subcontractor sc,contractorcontract c 
        where t.shiftcode= s.shiftcode and 
        t.seasoncode=f.seasoncode and
        t.fieldslipnumber=f.fieldslipnumber
        and f.seasoncode=sc.seasoncode
        and (f.hrsubcontractorcode=sc.subcontractorcode or f.hrtrsubcontractorcode=sc.subcontractorcode)
        and sc.seasoncode=c.seasoncode and sc.contractcode=c.contractcode
        and t.weighmentdate='".$dt."' 
        and nvl(t.netweight,0)>0
        group by s.shiftcode,s.shiftnameuni
        order by shiftcode";
    
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        while ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
            $this->setfieldwidth(20,10);
            //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')
            $this->textbox($row['SHIFTNAMEUNI'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(12);
            // Advance
            $this->textbox($row['ADV_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(20);
            $this->textbox($this->numformat($row['ADV_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            //  Non Advance
            $this->setfieldwidth(12);
            $this->textbox($row['NONADV_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(20);
            $this->textbox($this->numformat($row['NONADV_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            // Total
             $this->setfieldwidth(12);
             $this->textbox($row['TOT_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
             $this->setfieldwidth(25);
             $this->textbox($this->numformat($row['TOT_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);    
            $this->newrow();
           $this->hline(10,195,$this->liney-2,'C'); 
        }
        //$this->drawlines($this->liney-35); 
        // Day Total  start
        $query = "select sum(case when c.contractcategorycode = 1 then t.netweight else 0 end)  adv_ton
        ,sum(case when c.contractcategorycode <> 1 then t.netweight else 0 end)  nonadv_ton
        ,sum(case when c.contractcategorycode = 1 then 1 else 0 end)  adv_cnt
        ,sum(case when c.contractcategorycode <> 1 then 1 else 0 end)  nonadv_cnt
        ,sum(t.netweight) tot_ton 
        from weightslip t,shift s,fieldslip f,subcontractor sc,contractorcontract c 
        where t.shiftcode= s.shiftcode and 
        t.seasoncode=f.seasoncode and
        t.fieldslipnumber=f.fieldslipnumber
        and f.seasoncode=sc.seasoncode
        and (f.hrsubcontractorcode=sc.subcontractorcode or f.hrtrsubcontractorcode=sc.subcontractorcode)
        and sc.seasoncode=c.seasoncode and sc.contractcode=c.contractcode
        and t.weighmentdate='".$dt."' 
        and nvl(t.netweight,0)>0
        and t.seasoncode=".$_SESSION['yearperiodcode']." 
        and nvl(t.netweight,0)>0";
    
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $this->setfieldwidth(15,10);
        $this->textbox("एकूण",$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        while ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
            $this->setfieldwidth(20,10);
            $this->textbox($row['SHIFTNAMEUNI'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(12);
            // Bullockcart
            $this->textbox($row['ADV_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(20);
            $this->textbox($this->numformat($row['ADV_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            //  Jugad
            $this->setfieldwidth(12);
            $this->textbox($row['NONADV_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(20);
            $this->textbox($this->numformat($row['NONADV_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            // Total
             $this->setfieldwidth(12);
             $this->textbox($row['TOT_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
             $this->setfieldwidth(25);
             $this->textbox($this->numformat($row['TOT_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);    
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
        }
       // $this->drawlines($this->liney-35); 
        // day total Ened
        // Todate Total  start
        $query = "select sum(case when c.contractcategorycode = 1 then t.netweight else 0 end)  adv_ton
        ,sum(case when c.contractcategorycode <> 1 then t.netweight else 0 end)  nonadv_ton
        ,sum(case when c.contractcategorycode = 1 then 1 else 0 end)  adv_cnt
        ,sum(case when c.contractcategorycode <> 1 then 1 else 0 end)  nonadv_cnt
        ,sum(t.netweight) tot_ton 
        from weightslip t,shift s,fieldslip f,subcontractor sc,contractorcontract c 
        where t.shiftcode= s.shiftcode and 
        t.seasoncode=f.seasoncode and
        t.fieldslipnumber=f.fieldslipnumber
        and f.seasoncode=sc.seasoncode
        and (f.hrsubcontractorcode=sc.subcontractorcode or f.hrtrsubcontractorcode=sc.subcontractorcode)
        and sc.seasoncode=c.seasoncode and sc.contractcode=c.contractcode
        and t.weighmentdate<='".$dt."' 
        and nvl(t.netweight,0)>0
        and t.seasoncode=".$_SESSION['yearperiodcode']." 
        and nvl(t.netweight,0)>0";
    
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $this->setfieldwidth(20,10);
        $this->textbox("आजअखेर",$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        while ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
            $this->setfieldwidth(20,10);
            $this->textbox($row['SHIFTNAMEUNI'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(12);
            // Bullockcart
            $this->textbox($row['ADV_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(20);
            $this->textbox($this->numformat($row['ADV_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            //  Jugad
            $this->setfieldwidth(12);
            $this->textbox($row['NONADV_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(20);
            $this->textbox($this->numformat($row['NONADV_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
            // Total
             $this->setfieldwidth(12);
             $this->textbox($row['TOT_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
             $this->setfieldwidth(25);
             $this->textbox($this->numformat($row['TOT_TON'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);    
           // $this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }                                                                                                                    
        $this->drawlines($this->liney-35); 
        // todate total End
    }
}    
?>