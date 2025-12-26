<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_A5_L.php");
    include_once("../info/routine.php");

    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class weightslip extends swappreport
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
        $this->pdf->SetSubject('Weight Slip');
        $this->pdf->SetKeywords('WTSLP_000.MR');
                // Display image on full page
               // Render the image
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
    function startreport()
    {
        $this->newpage(true);
        $this->detail(1);
        $this->newpage(true);
        $this->detail(2);
        $this->newpage(true);
        $this->detail(3);
        $this->detail(4);
    }
    function endreport()
    {
        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output('WTSLP_000.pdf', 'I');
    }
	function pageheader()
    {
        //$this->pdf->Image("../img/kadwawatermark.png", 60, 35, 70, 70, '', '', '', false, 300, '', false, false, 0);
        $this->liney = 15;
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

    function detail($copycode)
    {
        //$this->newrow();
        
        $query = "select t.seasoncode,t.fieldslipnumber,to_char(t.fieldslipdate,'DD/MM/YYYY') as fieldslipdate
        ,t.plotnumber,w.weightslipnumber,to_char(w.weighmentdate,'DD/MM/YYYY') as weighmentdate,w.shiftcode
        ,t.farmercategorycode,c.farmercategorynameuni,t.farmercode,f.farmernameuni,f.farmernameeng
        ,sv.villagecode as memvillagecode,sv.villagenameuni as memvillagenameuni,sv.villagenameeng as memvillagenameeng,sc.circlecode,sc.circlenameuni,sc.circlenameeng,sd.divisionnameuni,sd.divisionnameeng
        ,t.villagecode,t.subvillagecode,v.villagenameuni,v.villagenameeng,s.subvillagenameuni,s.subvillagenameeng,h.gutnumber
        ,t.vehiclecategorycode,vc.vehiclecategorynameuni,t.vehiclecode,t.tyregadicode,vl.vehiclenumber,tg.tyregadinumber
        ,t.hrsubcontractorcode,t.hrtrsubcontractorcode,t.trsubcontractorcode
        ,t.caneconditioncode,t.slipboycode,t.distance
        ,w.dieselquantity,w.dieseldistance
        ,tr.subcontractornameuni as transporternameuni,tr.subcontractornameeng as transporternameeng
        ,hr.subcontractornameuni as harvesternameuni,hr.subcontractornameeng as harvesternameeng,ht.harvestersubcategorynameuni as htharvestersubcategorynameuni,ht.harvestersubcategorynameeng as htharvestersubcategorynameeng
        ,hrtr.subcontractornameuni as harvestertransporternameuni,hrtr.subcontractornameeng as harvestertransporternameeng,bh.harvestersubcategorynameuni as bhharvestersubcategorynameuni,bh.harvestersubcategorynameeng as bhharvestersubcategorynameeng
        ,v.distancetrucktractor as distancetrucktractor_tr,v.distancetyregadi as distancetyregadi_tr
        ,v.distancetrucktractor as distancetrucktractor_bl,v.distancetyregadi as distancetrucktractor_bl
        ,vr.varietynameuni,vr.varietynameeng 
        ,cd.caneconditionnameeng,cd.caneconditionnameuni
        ,ly.layernameeng,ly.layernameuni
        ,ph.plantationhangamnameuni,ph.plantationhangamnameeng
        ,ig.irrigationsourcenameuni,ig.irrigationsourcenameeng
        ,im.irrigationmethodnameuni,im.irrigationmethodnameeng
        ,pc.plantationcategorynameuni,pc.plantationcategorynameeng
        ,cs.caneseedcategorynameuni,cs.caneseedcategorynameeng
        ,w.loadweight,w.emptyweight,w.bindingmaterial,w.netweight,sb.slipboynameuni
        ,W.loadusercode,w.emptyusercode
        ,to_char(w.loaddatetime,'dd/MM/yyyy HH24:mi:ss') as loaddatetime
        ,to_char(w.emptydatetime,'dd/MM/yyyy HH24:mi:ss') as emptydatetime
        ,to_char(t.uploadeddatetime,'dd/MM/yyyy HH24:mi:ss') as uploadeddatetime
        ,ss.subvillagecode as todsubvillagecode,ss.subvillagenameuni as todsubvillagenameuni
        ,tg.gadiwannameuni,vl.drivernameuni
        from weightslip w,fieldslip t,plantationheader h
        ,farmer f,farmercategory c
        ,village v,subvillage s
        ,village sv
        ,circle sc
        ,division sd
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
        ,harvestersubcategory ht
        ,harvestersubcategory bh
        ,canecondition cd
        ,layer ly
        ,slipboy sb
        ,todslip ts
        ,subvillage ss
        where w.seasoncode=t.seasoncode
        and w.fieldslipnumber=t.fieldslipnumber
        and t.seasoncode=h.seasoncode 
        and t.plotnumber=h.plotnumber
        and t.farmercode=f.farmercode
        and t.farmercategorycode=c.farmercategorycode
        and t.villagecode=v.villagecode
        and t.subvillagecode=s.subvillagecode(+)
        and f.villagecode=sv.villagecode
        and sv.circlecode=sc.circlecode
        and sc.divisioncode=sd.divisioncode
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
        and hr.harvestersubcategorycode=ht.harvestersubcategorycode(+)
        and hrtr.harvestersubcategorycode=bh.harvestersubcategorycode(+)
        and h.varietycode=vr.varietycode 
        and h.plantationhangamcode=ph.plantationhangamcode
        and h.irrigationsourcecode=ig.irrigationsourcecode 
        and h.irrigationmethodcode=im.irrigationmethodcode
        and h.plantationcategorycode=pc.plantationcategorycode
        and h.caneseedcategorycode=cs.caneseedcategorycode
        and t.caneconditioncode=cd.caneconditioncode
        and t.layercode=ly.layercode
        and t.slipboycode=sb.slipboycode(+)
        and ts.villagecode=ss.villagecode(+)
        and ts.subvillagecode=ss.subvillagecode(+)
        and t.seasoncode=ts.seasoncode (+)
        and t.todslipnumber=ts.todslipnumber (+)   
        and w.transactionnumber=".$this->transactionnumber;
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
            $this->liney = 18;
        if ($copycode<=3)
        {
            $this->textbox('वजन स्लिप',180,10,'S','C',1,'siddhanta',13);
        }
        elseif ($row['VEHICLECATEGORYCODE']!=3)
        {
            $this->textbox('डिझेल स्लिप',180,10,'S','C',1,'siddhanta',13);
        }
        if ($copycode==1)
        {
            $this->textbox('वाहतुकदार काॅपी',50,150,'S','R',1,'siddhanta',10); 
        }
        elseif ($copycode==2)
        {
            $this->textbox('शेतकरी काॅपी',50,150,'S','R',1,'siddhanta',10); 
        }
        elseif ($copycode==3)
        {
            $this->textbox('कारखाना काॅपी',50,150,'S','R',1,'siddhanta',10); 
        }
        elseif ($copycode==4 and $row['VEHICLECATEGORYCODE']!=3)
        {
            $this->newpage(true);
            $this->textbox('अकौंट काॅपी',50,150,'S','R',1,'siddhanta',10); 
        }
        else
        {
            return;
        }
        $this->newrow(7);
        $this->hline(10,210,$this->liney,'C');
        $this->newrow(3);
            if ($copycode<=3)
            {
            $this->textbox('हंगाम : '.$row['SEASONCODE'],50,10,'S','L',1,'siddhanta',11);
            $this->textbox('वजन स्लिप नंबर : '.$row['WEIGHTSLIPNUMBER'],50,70,'S','L',1,'siddhanta',11);
            $this->textbox('वजन स्लिप दिनांक : '.$row['WEIGHMENTDATE'],80,130,'S','L',1,'siddhanta',11);
            $this->newrow();
            $this->textbox('शिफ्ट : '.$row['SHIFTCODE'],50,10,'S','L',1,'siddhanta',11);
            $this->textbox('फिल्ड स्लिप नंबर : '.$row['FIELDSLIPNUMBER'],70,70,'S','L',1,'siddhanta',11);
            $this->textbox('फिल्ड स्लिप दिनांक : '.$row['FIELDSLIPDATE'],80,130,'S','L',1,'siddhanta',11);
            /* $this->textbox('उपगाव : '.$row['SUBVILLAGECODE'].' '.$row['SUBVILLAGENAMEUNI'],80,130,'S','L',1,'siddhanta',11);
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
            } */
            $this->newrow();
            $this->textbox('सभासद प्रकार : '.$row['FARMERCATEGORYCODE'].'-'.$row['FARMERCATEGORYNAMEUNI'],70,10,'S','L',1,'siddhanta',11);
            $this->textbox('ऊस उत्पादक : '.$row['FARMERCODE'].'-'.$row['FARMERNAMEUNI'],100,70,'S','L',1,'siddhanta',11);
            if ($row['VEHICLECATEGORYCODE']==1 or $row['VEHICLECATEGORYCODE']==2)
            {
                $this->textbox('अंतर : '.$row['DISTANCETRUCKTRACTOR_TR'],80,170,'S','L',1,'siddhanta',11);
            }
            elseif ($row['VEHICLECATEGORYCODE']==3 or $row['VEHICLECATEGORYCODE']==4)
            {
                $this->textbox('अंतर : '.$row['DISTANCETRUCKTRACTOR_BL'],80,170,'S','L',1,'siddhanta',11);
            }
            $this->newrow();
            $this->textbox('विभाग : '.$row['DIVISIONNAMEUNI'],50,10,'S','L',1,'siddhanta',11);
            $this->textbox('गट : '.$row['CIRCLECODE'].'-'.$row['CIRCLENAMEUNI'],80,70,'S','L',1,'siddhanta',11);
            $this->textbox('गाव : '.$row['MEMVILLAGECODE'].'-'.$row['MEMVILLAGENAMEUNI'],80,150,'S','L',1,'siddhanta',11);
            $this->newrow();
            $this->textbox('प्लॉट नंबर : '.$row['PLOTNUMBER'],50,10,'S','L',1,'siddhanta',11);
            $this->textbox('शिवार : '.$row['VILLAGECODE'].'-'.$row['VILLAGENAMEUNI'].' '.$row['TODSUBVILLAGECODE'].' '.$row['TODSUBVILLAGENAMEUNI'],80,70,'S','L',1,'siddhanta',11);
            $this->textbox('सर्वे नं. : '.$row['GUTNUMBER'],60,150,'S','L',1,'siddhanta',11);
            $this->newrow();
            $this->textbox('वाहन प्रकार : '.$row['VEHICLECATEGORYCODE'].'-'.$row['VEHICLECATEGORYNAMEUNI'],70,10,'S','L',1,'siddhanta',11);
            if ($row['VEHICLECATEGORYCODE']==3)
            {
                $this->textbox('टायरगाडी : '.$row['TYREGADICODE'].'-'.$row['TYREGADINUMBER'],70,70,'S','L',1,'siddhanta',11);
                $this->textbox('गाडीवान : '.$row['GADIWANNAMEUNI'],70,150,'S','L',1,'siddhanta',11);
            }
            else 
            {
                $this->textbox('वाहन नंबर : '.$row['VEHICLECODE'].'-'.$row['VEHICLENUMBER'],70,70,'S','L',1,'siddhanta',11);
            }
            $this->newrow();
            if ($row['VEHICLECATEGORYCODE']==1 or $row['VEHICLECATEGORYCODE']==2)
            {
                $this->textbox('वाहतूकदार : '.$row['TRSUBCONTRACTORCODE'].'-'.$row['TRANSPORTERNAMEUNI'],70,10,'S','L',1,'siddhanta',11);
                $this->textbox('तोडणीदार : '.$row['HRSUBCONTRACTORCODE'].'-'.$row['HARVESTERNAMEUNI'],70,80,'S','L',1,'siddhanta',11);
                $this->textbox('तोडणीसेंटर : '.$row['HTHARVESTERSUBCATEGORYNAMEUNI'],60,150,'S','L',1,'siddhanta',11);
                $this->newrow();
            }
            elseif ($row['VEHICLECATEGORYCODE']==3 or $row['VEHICLECATEGORYCODE']==4)
            {
                if ($row['HRTRSUBCONTRACTORCODE']!='')
                $this->textbox('तोडणीदार वाहतूकदार : '.$row['HRTRSUBCONTRACTORCODE'].'-'.$row['HARVESTERTRANSPORTERNAMEUNI'],100,10,'S','L',1,'siddhanta',11);
                else
                {
                    $this->textbox('वाहतूकदार : '.$row['TRSUBCONTRACTORCODE'].'-'.$row['TRANSPORTERNAMEUNI'],70,10,'S','L',1,'siddhanta',11);
                    $this->textbox('तोडणीदार : '.$row['HRSUBCONTRACTORCODE'].'-'.$row['HARVESTERNAMEUNI'],70,80,'S','L',1,'siddhanta',11);    
                }
                $this->textbox('तोडणीसेंटर : '.$row['BHHARVESTERSUBCATEGORYNAMEUNI'],60,150,'S','L',1,'siddhanta',11);
                $this->newrow();
            }
            if ($copycode<=3)
            {
                $this->textbox('ऊस जात : '.$row['VARIETYNAMEUNI'],50,10,'S','L',1,'siddhanta',11);
                $this->textbox('ऊस हंगाम : '.$row['PLANTATIONHANGAMNAMEUNI'],50,70,'S','L',1,'siddhanta',11);
                //$this->textbox('सिंचन स्त्रोत : '.$row['IRRIGATIONSOURCENAMEUNI'],80,130,'S','L',1,'siddhanta',11);
                $this->newrow();
                //$this->textbox('सिंचन पद्धत : '.$row['IRRIGATIONMETHODNAMEUNI'],50,10,'S','L',1,'siddhanta',11);
                //$this->textbox('लागवड प्रकार : '.$row['PLANTATIONCATEGORYNAMEUNI'],50,70,'S','L',1,'siddhanta',11);
                //$this->textbox('ऊस बेणे प्रकार : '.$row['CANESEEDCATEGORYNAMEUNI'],80,130,'S','L',1,'siddhanta',11);
                //$this->newrow();
                //$this->newrow();
                $this->textbox('स्पेशल केस : '.$row['CANECONDITIONNAMEUNI'],50,10,'S','L',1,'siddhanta',11);
                $this->textbox('थर : '.$row['LAYERNAMEUNI'],80,70,'S','L',1,'siddhanta',11);
                $this->newrow();
            }
            }
            if ($copycode<=3)
            {
            $this->textbox('भरगाडी वजन : '.number_format($row['LOADWEIGHT'],3,'.',''),50,10,'S','L',1,'siddhanta',11);
            $this->textbox('रिकामी गाडी वजन : '.number_format($row['EMPTYWEIGHT'],3,'.',''),50,70,'S','L',1,'siddhanta',11);
            $this->textbox('बायंडिंग मटेरिअल : '.number_format($row['BINDINGMATERIAL'],3,'.',''),80,130,'S','L',1,'siddhanta',11);
            $this->newrow();
            $this->textbox('निव्वळ वजन : '.number_format($row['NETWEIGHT'],3,'.',''),80,10,'S','L',1,'siddhanta',11);
            if ($copycode==1 and $row['DIESELQUANTITY']>0)
            {
                $this->vline($this->liney+2,$this->liney+60,130,$type='D');
            }
            }
            if ($copycode==1 and $row['DIESELQUANTITY']>0)
            {
                
                $this->newrow(5);
                $this->textbox('डिझेल स्लिप',50,160,'S','L',1,'siddhanta',10);
                $this->newrow(5);
                $this->textbox('वजन स्लिप नंबर : '.$row['WEIGHTSLIPNUMBER'],50,130,'S','L',1,'siddhanta',10);
                $this->newrow(5);
                $this->textbox('वजन स्लिप दिनांक : '.$row['WEIGHMENTDATE'],80,130,'S','L',1,'siddhanta',10);
                $this->newrow(5);
                $this->textbox('वाहन प्रकार : '.$row['VEHICLECATEGORYCODE'].'-'.$row['VEHICLECATEGORYNAMEUNI'],110,130,'S','L',1,'siddhanta',10);
                $this->newrow(5);
                $this->textbox('वाहन नंबर : '.$row['VEHICLECODE'].'-'.$row['VEHICLENUMBER'],110,130,'S','L',1,'siddhanta',10);
                $this->newrow(5);
                $this->textbox('डिझेल -'.$row['DIESELQUANTITY'].'लि',100,130,'S','L',1,'siddhanta',12);
                $this->textbox('('.$row['DIESELDISTANCE'].' किमी अंतरासाठी)',100,165,'S','L',1,'siddhanta',8);
                $this->newrow(10);
                $this->textbox('डिझेल घेणार',50,140,'S','L',1,'siddhanta',10);
                $this->textbox('डिझेल देणार',50,180,'S','L',1,'siddhanta',10);
                $this->newrow(-30);

                //$this->newrow();
                //$this->hline(130,300,$this->liney+33,'D');  
            }
            elseif ($copycode>=4 and $row['DIESELQUANTITY']>0)
            {
                
                //$this->newrow(5);
                //$this->textbox('डिझेल स्लिप',100,10,'S','L',1,'siddhanta',10);
                $this->newrow(10);
                $this->hline(10,200,$this->liney-2,'C');
                $this->setfieldwidth(25,10);
                $this->vline($this->liney-2,$this->liney+7,$this->x);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox('स्लिप नंबर',$this->w,$this->x,'S','L',1,'siddhanta',12);
                $this->setfieldwidth(25);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox('स्लिप दिनांक',$this->w,$this->x,'S','L',1,'siddhanta',12);
                $this->setfieldwidth(25);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox('वाहन प्रकार',$this->w,$this->x,'S','L',1,'siddhanta',12);
                $this->setfieldwidth(35);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox('वाहन नंबर',$this->w,$this->x,'S','L',1,'siddhanta',12);
                $this->setfieldwidth(60);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox('वाहतुकदार',$this->w,$this->x,'S','L',1,'siddhanta',12);
                $this->setfieldwidth(20);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox('डिझेल',$this->w,$this->x,'S','L',1,'siddhanta',12);
                $this->newrow(5);
                $this->hline(10,200,$this->liney,'C');
                $this->setfieldwidth(25,10);
                $this->vline($this->liney-2,$this->liney+7,$this->x);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox($row['WEIGHTSLIPNUMBER'],$this->w,$this->x,'S','L',1,'siddhanta',10);
                $this->setfieldwidth(25);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox($row['WEIGHMENTDATE'],$this->w,$this->x,'S','L',1,'siddhanta',10);
                $this->setfieldwidth(25);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox($row['VEHICLECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10);
                $this->setfieldwidth(35);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox($row['VEHICLENUMBER'],$this->w,$this->x,'S','L',1,'siddhanta',10);
                $this->setfieldwidth(60);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox($row['TRANSPORTERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10);
                $this->setfieldwidth(20);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox($row['DIESELQUANTITY'],$this->w,$this->x,'S','L',1,'siddhanta',10);
                $this->newrow(10);
                $this->hline(10,200,$this->liney-3,'C');
                $this->newrow(10);
                $this->textbox('डिझेल घेणार',50,10,'S','L',1,'siddhanta',10);
                $this->textbox('डिझेल देणार',50,80,'S','L',1,'siddhanta',10);
                //$this->newrow(-30);
                $this->newrow(5);
                $this->hline(10,200,$this->liney+2,'D');
                $this->newrow(5);

                $this->pdf->SetFont('siddhanta', 'B', 12);
                $this->pdf->multicell(0,15,'नाशिक सहकारी साखर कारखाना लि.,पळसे',0,'C',false,1,16,75,true,0,false,true,10);
                $this->pdf->SetFont('siddhanta', 'B', 10);
                $this->pdf->multicell(0,15,'श्री संत जनार्दन स्वामी नगर, ता. जि. नाशिक',0,'C',false,1,16,80,true,0,false,true,10);
                $this->newrow(20);
                $this->textbox('डिझेल स्लिप',180,10,'S','C',1,'siddhanta',13);
                $this->textbox('वाहतूकदार काॅपी',50,150,'S','R',1,'siddhanta',10); 
                $this->newrow(10);
                $this->hline(10,200,$this->liney-2,'C');
                $this->setfieldwidth(25,10);
                $this->vline($this->liney-2,$this->liney+7,$this->x);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox('स्लिप नंबर',$this->w,$this->x,'S','L',1,'siddhanta',12);
                $this->setfieldwidth(25);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox('स्लिप दिनांक',$this->w,$this->x,'S','L',1,'siddhanta',12);
                $this->setfieldwidth(25);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox('वाहन प्रकार',$this->w,$this->x,'S','L',1,'siddhanta',12);
                $this->setfieldwidth(35);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox('वाहन नंबर',$this->w,$this->x,'S','L',1,'siddhanta',12);
                $this->setfieldwidth(60);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox('वाहतुकदार',$this->w,$this->x,'S','L',1,'siddhanta',12);
                $this->setfieldwidth(20);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox('डिझेल',$this->w,$this->x,'S','L',1,'siddhanta',12);
                $this->newrow(5);
                $this->hline(10,200,$this->liney,'C');
                $this->setfieldwidth(25,10);
                $this->vline($this->liney-2,$this->liney+7,$this->x);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox($row['WEIGHTSLIPNUMBER'],$this->w,$this->x,'S','L',1,'siddhanta',10);
                $this->setfieldwidth(25);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox($row['WEIGHMENTDATE'],$this->w,$this->x,'S','L',1,'siddhanta',10);
                $this->setfieldwidth(25);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox($row['VEHICLECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10);
                $this->setfieldwidth(35);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox($row['VEHICLENUMBER'],$this->w,$this->x,'S','L',1,'siddhanta',10);
                $this->setfieldwidth(60);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox($row['TRANSPORTERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10);
                $this->setfieldwidth(20);
                $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->textbox($row['DIESELQUANTITY'],$this->w,$this->x,'S','L',1,'siddhanta',10);
                $this->newrow(10);
                $this->hline(10,200,$this->liney-3,'C');
                $this->newrow(10);
                $this->textbox('डिझेल घेणार',50,10,'S','L',1,'siddhanta',10);
                $this->textbox('डिझेल देणार',50,80,'S','L',1,'siddhanta',10);
                $this->newrow(-30);

                //$this->newrow();
                //$this->hline(130,300,$this->liney+33,'D');  
            }
            else
            {

            }
            $this->newrow();
        }
        if ($copycode==1 and $row['DIESELQUANTITY']>0)
        {
            //$this->hline(10,130,$this->liney+5,'C');  
            $this->hline(130,300,$this->liney-15,'D');  
        }
        else
        {
            //$this->hline(10,300,$this->liney,'C');  
        }
        $this->newrow();
        
        if ($copycode>3)
        {
            $this->newrow(-15);
        }
        if ($copycode<=3)
        {
            $this->textbox($row['SLIPBOYNAMEUNI'],40,10,'S','L',1,'SakalMarathiNormal922',9,'','','','B');  
            $this->textbox($this->username($row['LOADUSERCODE']),40,50,'S','L',1,'siddhanta',9); 
            $this->textbox($this->username($row['EMPTYUSERCODE']),40,90,'S','L',1,'siddhanta',9); 
            $this->newrow();
            $this->textbox('स्लीप बॉय',40,10,'S','L',1,'siddhanta',9); 
            $this->textbox('भरगाडी क्लार्क',40,50,'S','L',1,'siddhanta',9);
            $this->textbox('रिकामीगाडी क्लार्क',40,90,'S','L',1,'siddhanta',9);
            $this->newrow(5);
            $this->textbox($row['UPLOADEDDATETIME'],40,10,'S','L',1,'siddhanta',8); 
            $this->textbox($row['LOADDATETIME'],40,50,'S','L',1,'siddhanta',8);
            $this->textbox($row['EMPTYDATETIME'],40,90,'S','L',1,'siddhanta',8);
            //$this->hline(10,300,$this->liney,'C'); 
        }   
    }

    function username($userid)
    {
        $username="root";
        $password="sandee1976";
        $database="nasaka_db";
        $hostname = "localhost";
        // Opens a connection to a MySQL server
        $connection1=mysqli_connect($hostname, $username, $password, $database);
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Communication Error";
            exit;
        }
        $connection1->autocommit(FALSE);
        $query = "SELECT m.misuserid,m.misusername FROM misuser m WHERE m.misuserid=".$userid;
        $result = mysqli_query($connection1,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            $name=$row['misusername'];
        }
        else
        {
            $name='';
        }
        return $name;
    }
}    
?>