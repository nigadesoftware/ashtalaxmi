<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a3_l.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class daybookfieldslip extends swappreport
{
    public $fromdate;
    public $todate;
    public $slipboycode;
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
        $this->pdf->SetSubject('Fieldslip Day Book');
        $this->pdf->SetKeywords('FLDDYBK_000.MR');
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
        $this->pdf->Output('DAYBOOK_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 15;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->textbox('दैनिक फिल्ड स्लिप',400,10,'S','C',1,'siddhanta',15);
        //$this->newrow(7);
        //$this->textbox('दिनांक : '.$this->slipdate,400,10,'S','C',1,'SakalMarathiNormal922',13);
        $this->newrow(7);
        $this->hline(10,405,$this->liney,'C');
        $this->setfieldwidth(40,10);
        $this->textbox('फिल्डस्लिप',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox('प्लॉट',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(70);
        $this->textbox('ऊस उत्पादक',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox('प्रत',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox('ऊस हंगाम',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox('ऊस जात',$this->w,$this->x,'S','L',1,'siddhanta',11); 
        $this->setfieldwidth(35);
        $this->textbox('वाहन',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(60);
        $this->textbox('तोडणीदार / बैलगाडी',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(23);
        $this->textbox('भर(मे.ट.) ',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->setfieldwidth(23);
        $this->textbox('रिकामे(मे.ट.)',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox('बायडिंग(मे.ट.)',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox('निव्वळ(मे.ट.)',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->newrow(5);
        $this->setfieldwidth(20,10);
        $this->textbox('स्लिप नं ',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox('दि.',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox('शिफ्ट',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(145);
        $this->textbox('गाव',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(35);
        $this->textbox('वाहन नं.',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(60);
        $this->textbox('वाहतुकदार / गाडीवान',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->newrow();
        $this->hline(10,405,$this->liney-2,'C');
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
        //$this->newrow();
        $this->liney = 35;
        $cond='';
        if ($_SESSION['responsibilitycode']==123479028)
        {

        }
        else
        {
            $usercode=$_SESSION['usersid'];
        }
        if ($this->fromdate!='' and $this->todate=='')
        $this->todate=$this->fromdate;
        //$usercode='621754457526269';
        if ($this->fromdate=='' and $this->todate=='')
        {
            if ($usercode!='')
            {
                if ($cond=='')
                {
                    $cond = " and (t.slipboycode={$_SESSION['usersid']} or t.villagecode in (select villagecode
                    from village
                    where centrecode in (select c.centrecode from slipboy s,slipboycentre c,slipboyresponsibility r 
                    where s.slipboycode=r.slipboycode and r.uptodate is null 
                    and s.slipboycode=c.slipboycode and c.uptodate is null 
                    and r.responsibilitycode=123981887 and s.userid={$_SESSION['usersid']})))";
                }
            }
            
        }
        else
        {
            $fdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $tdt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            if ($cond=='')
                {
                    $cond = " and (trunc(t.fieldslipdate)>='".$fdt."' and trunc(t.fieldslipdate)<='".$tdt."') and (t.slipboycode={$_SESSION['usersid']} or t.villagecode in (select villagecode
                    from village
                    where centrecode in (select c.centrecode from slipboy s,slipboycentre c,slipboyresponsibility r 
                    where s.slipboycode=r.slipboycode and r.uptodate is null 
                    and s.slipboycode=c.slipboycode and c.uptodate is null 
                    and r.responsibilitycode=123981887 and s.userid={$_SESSION['usersid']})))";
                }
            else
                $cond = " and trunc(t.fieldslipdate)>='".$fdt."' and trunc(t.fieldslipdate)<='".$tdt."'";
        }
        $query = "select t.seasoncode,t.fieldslipnumber,to_char(t.fieldslipdate,'DD/MM/YY') as fieldslipdate
        ,t.plotnumber,w.weightslipnumber,to_char(w.weighmentdate,'DD/MM/YY') as weighmentdate,w.shiftcode
        ,t.farmercategorycode,c.farmercategorynameuni,t.farmercode,f.farmernameuni,f.farmernameeng
        ,t.villagecode,t.subvillagecode,v.villagenameuni,v.villagenameeng,s.subvillagenameuni,s.subvillagenameeng
        ,t.vehiclecategorycode,vc.vehiclecategorynameuni,t.vehiclecode,t.tyregadicode,vl.vehiclenumber,tg.tyregadinumber
        ,t.hrsubcontractorcode,t.hrtrsubcontractorcode,t.trsubcontractorcode
        ,t.caneconditioncode,cc.caneconditionnameuni,cc.caneconditionnameeng,t.slipboycode,t.distance
        ,tr.subcontractornameuni as transporternameuni,tr.subcontractornameeng as transporternameeng
        ,hr.subcontractornameuni as harvesternameuni,hr.subcontractornameeng as harvesternameeng
        ,hrtr.subcontractornameuni as harvestertransporternameuni,hrtr.subcontractornameeng as harvestertransporternameeng
        ,tg.gadiwannameuni,tg.gadiwannameeng
        ,v.distancetrucktractor as distancetrucktractor_tr,v.distancetyregadi as distancetyregadi_tr
        ,v.distancetrucktractor as distancetrucktractor_bl,v.distancetyregadi as distancetrucktractor_bl
        ,vr.varietynameuni,vr.varietynameeng 
        ,ph.plantationhangamnameuni,ph.plantationhangamnameeng
        ,ig.irrigationsourcenameuni,ig.irrigationsourcenameeng
        ,im.irrigationmethodnameuni,im.irrigationmethodnameeng
        ,pc.plantationcategorynameuni,pc.plantationcategorynameeng
        ,cs.caneseedcategorynameuni,cs.caneseedcategorynameeng
        ,w.katacode,w.shiftcode
        ,w.loadweight,w.emptyweight,w.bindingmaterial,w.netweight
        ,cn.containernameuni,ly.layernameuni
        ,sb.slipboynameuni
        from weightslip w,fieldslip t,plantationheader h
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
        ,canecondition cc
        ,layer ly
        ,container cn
        ,slipboy sb
        where w.seasoncode(+)=t.seasoncode
        and w.fieldslipnumber(+)=t.fieldslipnumber
        and t.seasoncode=h.seasoncode 
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
        and t.containercode=cn.containercode(+)
        and t.layercode=ly.layercode(+)
        and h.varietycode=vr.varietycode 
        and t.slipboycode=sb.userid
        and h.plantationhangamcode=ph.plantationhangamcode
        and h.irrigationsourcecode=ig.irrigationsourcecode 
        and h.irrigationmethodcode=im.irrigationmethodcode
        and h.plantationcategorycode=pc.plantationcategorycode
        and h.caneseedcategorycode=cs.caneseedcategorycode
        and t.caneconditioncode=cc.caneconditioncode
        {$cond}
        order by t.fieldslipdate,t.fieldslipnumber";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $loadweight=0;
        $emptyweight=0;
        $binding=0;
        $netweight=0;
        while ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
            $this->setfieldwidth(20,10);
            $this->textbox($row['FIELDSLIPNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(20);
            $this->textbox($row['FIELDSLIPDATE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(20);
            $this->textbox($row['PLOTNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(70);
            $this->textbox($row['FARMERCODE'].' '.$row['FARMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
            $this->setfieldwidth(25);
            $this->textbox($row['CANECONDITIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
            $this->setfieldwidth(25);
            $this->textbox($row['PLANTATIONHANGAMNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
            $this->setfieldwidth(25);
            $this->textbox($row['VARIETYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11); 
            $this->setfieldwidth(35);
            $this->textbox($row['VEHICLECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
            $this->setfieldwidth(60);
            if ($row['VEHICLECATEGORYCODE']==1 or $row['VEHICLECATEGORYCODE']==2)
            {
                $this->textbox($row['HRSUBCONTRACTORCODE'].' '.$row['HARVESTERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
            }
            elseif ($row['VEHICLECATEGORYCODE']==3 or $row['VEHICLECATEGORYCODE']==4)
            {
                $this->textbox($row['HRTRSUBCONTRACTORCODE'].' '.$row['HARVESTERTRANSPORTERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
            }
            $this->setfieldwidth(23);
            $this->textbox(number_format($row['LOADWEIGHT'],3,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(23);
            $this->textbox(number_format($row['EMPTYWEIGHT'],3,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(25);
            $this->textbox(number_format($row['BINDINGMATERIAL'],3,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(25);
            $this->textbox(number_format($row['NETWEIGHT'],3,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->newrow(5);
            $this->setfieldwidth(20,10);
            $this->textbox($row['WEIGHTSLIPNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(20);
            $this->textbox($row['WEIGHMENTDATE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(70);
            $this->textbox($row['VILLAGECODE'].' '.$row['VILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
            $this->setfieldwidth(30);
            $this->textbox($row['PLANTATIONCATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
            $this->setfieldwidth(45);
            $this->textbox($row['CONTAINERNAMEUNI'].' '.$row['LAYERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
            $this->setfieldwidth(35);
            if ($row['VEHICLECATEGORYCODE']==3)
            {
                $this->textbox($row['TYREGADINUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
            }
            else 
            {
                $this->textbox($row['VEHICLENUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
            }
            $this->setfieldwidth(60);
            if ($row['VEHICLECATEGORYCODE']==1 or $row['VEHICLECATEGORYCODE']==2)
            {
                $this->textbox($row['TRSUBCONTRACTORCODE'].' '.$row['TRANSPORTERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
            }
            elseif ($row['VEHICLECATEGORYCODE']==3)
            {
                $this->textbox($row['GADIWANNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
            }
            $this->setfieldwidth(60,300);
            $this->textbox($row['SLIPBOYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
            
            if ($this->isnewpage(15))
            {
                $this->newrow();
                $this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            else
            {
                $this->newrow();
            }
            $this->hline(10,405,$this->liney-2,'C');
            $loadweight=$loadweight+$row['LOADWEIGHT'];
            $emptyweight=$emptyweight+$row['EMPTYWEIGHT'];
            $binding=$binding+$row['BINDINGMATERIAL'];
            $netweight=$netweight+$row['NETWEIGHT']; 

        }
        $this->setfieldwidth(20);
        $this->setfieldwidth(20);
        $this->setfieldwidth(20);
        $this->setfieldwidth(70);
        $this->setfieldwidth(25);
        $this->setfieldwidth(25);
        $this->setfieldwidth(25);
        $this->setfieldwidth(36);
        $this->setfieldwidth(60);
        $this->setfieldwidth(23);
        $this->textbox(number_format($loadweight,3,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(23);
        $this->textbox(number_format($emptyweight,3,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(25);
        $this->textbox(number_format($binding,3,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(25);
        $this->textbox(number_format($netweight,3,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
    }
}    
?>