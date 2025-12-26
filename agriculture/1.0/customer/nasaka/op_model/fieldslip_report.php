<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_A5_L.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class fieldslip extends swappreport
{
    public $transactionnumber;
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
	    $this->pdf = new MYPDF('L', PDF_UNIT, 'A5', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Field Slip');
        $this->pdf->SetKeywords('FLDSLP_000.MR');
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
        $this->pdf->Output('FLDSLP_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 10;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        //$this->drawlines($limit);
    }
   
    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        $liney = $this->liney;
        $this->liney = 85;
        $this->hline(10,200,$this->liney-12);
        $this->vline($this->liney-12,$this->liney+$limit,10);
        $this->vline($this->liney-12,$this->liney+$limit,20);
        $this->vline($this->liney-12,$this->liney+$limit,75);
        $this->vline($this->liney-12,$this->liney+$limit,90);
        $this->vline($this->liney-12,$this->liney+$limit,110);
        $this->vline($this->liney-12,$this->liney+$limit,140);
        $this->vline($this->liney-5,$this->liney+$limit,150);
        $this->vline($this->liney-12,$this->liney+$limit,170);
        $this->vline($this->liney-5,$this->liney+$limit,180);
        $this->vline($this->liney-12,$this->liney+$limit,200);
        $this->hline(10,200,$this->liney+$limit);
        $this->hline(10,200,$this->liney+$limit);
        $this->hline(140,200,$this->liney-5);
        $this->liney = $liney;
    }

    function pagefooter($islastpage=false)
    {
        if ($islastpage==false)
        { 
            //$this->drawlines(130-48);
        }
        else
        {
            //$this->drawlines($this->liney-48);
        }
    }

    function detail()
    {
        //$this->newrow(10);
        $this->liney = 15;
        $this->textbox('फिल्ड स्लिप',180,10,'S','C',1,'siddhanta',13);
        $this->newrow(7);
        $this->hline(10,210,$this->liney,'C');
        $this->newrow(3);
        $query = "select t.seasoncode,t.fieldslipnumber,to_char(t.fieldslipdate,'DD/MM/YYYY') as fieldslipdate,t.plotnumber
        ,t.farmercategorycode,c.farmercategorynameuni,t.farmercode,f.farmernameuni,f.farmernameeng
        ,t.villagecode,t.subvillagecode,v.villagenameuni,v.villagenameeng,s.subvillagenameuni,s.subvillagenameeng
        ,t.vehiclecategorycode,vc.vehiclecategorynameuni,t.vehiclecode,t.tyregadicode,vl.vehiclenumber,tg.tyregadinumber
        ,t.hrsubcontractorcode,t.hrtrsubcontractorcode,t.trsubcontractorcode
        ,t.caneconditioncode,t.slipboycode,t.distance
        ,tr.subcontractornameuni as transporternameuni,tr.subcontractornameeng as transporternameeng
        ,hr.subcontractornameuni as harvesternameuni,hr.subcontractornameeng as harvesternameeng
        ,hrtr.subcontractornameuni as harvestertransporternameuni,hrtr.subcontractornameeng as harvestertransporternameeng
        ,v.distancetrucktractor as distancetrucktractor_tr,v.distancetyregadi as distancetyregadi_tr
        ,v.distancetrucktractor as distancetrucktractor_bl,v.distancetyregadi as distancetrucktractor_bl
        ,vr.varietynameuni,vr.varietynameeng 
        ,ph.plantationhangamnameuni,ph.plantationhangamnameeng
        ,ig.irrigationsourcenameuni,ig.irrigationsourcenameeng
        ,im.irrigationmethodnameuni,im.irrigationmethodnameeng
        ,pc.plantationcategorynameuni,pc.plantationcategorynameeng
        ,cs.caneseedcategorynameuni,cs.caneseedcategorynameeng
        ,ss.subvillagecode as todsubvillagecode,ss.subvillagenameuni as todsubvillagenameuni
        from fieldslip t,plantationheader h
        ,farmer f,farmercategory c
        ,village v,subvillage s
        ,vehiclecategory vc,vehicle vl
        ,tyregadi tg
        ,subcontractor tr
        ,subcontractor hr
        ,subcontractor hrtr
        ,variety vr
        ,plantationhangam ph
        ,irrigationsource ig
        ,irrigationmethod im
        ,plantationcategory pc
        ,caneseedcategory cs
        ,todslip ts
        ,subvillage ss
        where t.seasoncode=h.seasoncode 
        and t.plotnumber=h.plotnumber
        and t.farmercode=f.farmercode
        and t.farmercategorycode=c.farmercategorycode
        and t.villagecode=v.villagecode
        and t.subvillagecode=s.subvillagecode(+)
        and t.vehiclecategorycode=vc.vehiclecategorycode
        and t.seasoncode=vl.seasoncode(+)
        and t.vehiclecode=vl.vehiclecode(+)
        and t.seasoncode=tg.seasoncode(+)
        and t.tyregadicode=tg.tyregadicode(+)
        and t.seasoncode=tr.seasoncode(+)
        and t.trsubcontractorcode=tr.subcontractorcode(+)
        and t.seasoncode=hrtr.seasoncode(+)
        and t.hrtrsubcontractorcode=hrtr.subcontractorcode(+)
        and t.seasoncode=hr.seasoncode(+)
        and t.hrsubcontractorcode=hr.subcontractorcode(+)
        and h.varietycode=vr.varietycode 
        and h.plantationhangamcode=ph.plantationhangamcode
        and h.irrigationsourcecode=ig.irrigationsourcecode 
        and h.irrigationmethodcode=im.irrigationmethodcode
        and h.plantationcategorycode=pc.plantationcategorycode
        and h.caneseedcategorycode=cs.caneseedcategorycode
        and t.seasoncode=ts.seasoncode and t.todslipnumber=ts.todslipnumber and t.fieldslipnumber=ts.fieldslipnumber 
        and ts.villagecode=ss.villagecode(+) and ts.subvillagecode=ss.subvillagecode(+)
        and t.transactionnumber=".$this->transactionnumber;
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
            $this->textbox('हंगाम : '.$row['SEASONCODE'],50,10,'S','L',1,'siddhanta',11);
            $this->textbox('फिल्ड स्लिप नंबर : '.$row['FIELDSLIPNUMBER'],50,70,'S','L',1,'siddhanta',11);
            $this->textbox('फिल्ड स्लिप दिनांक : '.$row['FIELDSLIPDATE'],80,130,'S','L',1,'siddhanta',11);
            $this->newrow(10);
            $this->textbox('प्लॉट नंबर : '.$row['PLOTNUMBER'],50,10,'S','L',1,'siddhanta',11);
            $this->textbox('गाव : '.$row['VILLAGECODE'].' '.$row['VILLAGENAMEUNI'],50,70,'S','L',1,'siddhanta',11);
            $this->textbox('उपगाव : '.$row['TODSUBVILLAGECODE'].' '.$row['TODSUBVILLAGENAMEUNI'],80,130,'S','L',1,'siddhanta',11);
            if ($row['SUBVILLAGECODE']=='')
            {
                if ($row['VEHICLECATEGORYCODE']==1 or $row['VEHICLECATEGORYCODE']==2)
                {
                    $this->textbox('अंतर : '.$row['DISTANCETRUCKTRACTOR_TR'],80,170,'S','L',1,'siddhanta',11);
                }
                elseif ($row['VEHICLECATEGORYCODE']==3 or $row['VEHICLECATEGORYCODE']==4)
                {
                    $this->textbox('अंतर : '.$row['DISTANCETRUCKTRACTOR_BL'],80,170,'S','L',1,'siddhanta',11);
                }
            }
            else if ($row['SUBVILLAGECODE']!='')
            {
                $this->textbox('अंतर : '.$row['SUBVILLAGECODE'].' '.$row['SUBVILLAGENAMEUNI'],80,130,'S','L',1,'siddhanta',11);
            }
            $this->newrow(10);
            $this->textbox('सभासद प्रकार : '.$row['FARMERCATEGORYCODE'].' '.$row['FARMERCATEGORYNAMEUNI'],70,10,'S','L',1,'siddhanta',11);
            $this->textbox('ऊस उत्पादक : '.$row['FARMERCODE'].' '.$row['FARMERNAMEUNI'],100,70,'S','L',1,'siddhanta',11);
            $this->newrow(10);
            $this->textbox('वाहन प्रकार : '.$row['VEHICLECATEGORYCODE'].' '.$row['VEHICLECATEGORYNAMEUNI'],70,10,'S','L',1,'siddhanta',11);
            $this->textbox('वाहन नंबर : '.$row['VEHICLECODE'].' '.$row['VEHICLENUMBER'],70,70,'S','L',1,'siddhanta',11);
            $this->newrow(10);
            if ($row['VEHICLECATEGORYCODE']==1 or $row['VEHICLECATEGORYCODE']==2)
            {
                $this->textbox('वाहतूकदार : '.$row['TRSUBCONTRACTORCODE'].' '.$row['TRANSPORTERNAMEUNI'],100,10,'S','L',1,'siddhanta',11);
                $this->textbox('तोडणीदार : '.$row['HRSUBCONTRACTORCODE'].' '.$row['HARVESTERNAMEUNI'],100,110,'S','L',1,'siddhanta',11);
                $this->newrow(10);
            }
            elseif ($row['VEHICLECATEGORYCODE']==3 or $row['VEHICLECATEGORYCODE']==4)
            {
                $this->textbox('तोडणीदार वाहतूकदार : '.$row['HRTRSUBCONTRACTORCODE'].' '.$row['HARVESTERTRANSPORTERNAMEUNI'],100,10,'S','L',1,'siddhanta',11);
                $this->newrow(10);
            }
            $this->textbox('ऊस जात : '.$row['VARIETYNAMEUNI'],50,10,'S','L',1,'siddhanta',11);
            $this->textbox('ऊस हंगाम : '.$row['PLANTATIONHANGAMNAMEUNI'],50,70,'S','L',1,'siddhanta',11);
            $this->textbox('सिंचन स्त्रोत : '.$row['IRRIGATIONSOURCENAMEUNI'],80,130,'S','L',1,'siddhanta',11);
            $this->newrow(10);
            $this->textbox('सिंचन पद्धत : '.$row['IRRIGATIONMETHODNAMEUNI'],50,10,'S','L',1,'siddhanta',11);
            $this->textbox('लागवड प्रकार : '.$row['PLANTATIONCATEGORYNAMEUNI'],50,70,'S','L',1,'siddhanta',11);
            $this->textbox('ऊस बेणे प्रकार : '.$row['CANESEEDCATEGORYNAMEUNI'],80,130,'S','L',1,'siddhanta',11);
            $this->newrow(10);
        }
        $this->hline(10,300,$this->liney,'C');  
        $this->newrow(10);
        $this->textbox('क्लार्क',55,60,'S','L',1,'siddhanta',12);  
        //$this->hline(10,300,$this->liney,'C');    
    }
}    
?>