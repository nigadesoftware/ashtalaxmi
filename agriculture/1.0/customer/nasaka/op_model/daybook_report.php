<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a3_l.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class daybook extends reportbox
{
    public $slipdate;
    public $farmercategorycode;
    public $shiftcode;

    public $circlesummary;
    public $villagesummary;
    public $farmeresummary;
    public $summary;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7,$subject='',$pdffilename='',$papersize='A3',$orientation='P')
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
        $this->pdf->SetSubject('Day Book');
        $this->pdf->SetKeywords('DAYBOOK_000.MR');
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
        $this->group();
        $this->reportfooter();
    }

	function pageheader()
    {
        $this->liney = 15;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        if ($this->farmercategorycode==0)
        $this->textbox('दैनिक ऊस गाळप',400,10,'S','C',1,'siddhanta',15);
        elseif ($this->farmercategorycode==1)
        $this->textbox('सभासद दैनिक ऊस गाळप',400,10,'S','C',1,'siddhanta',15);
        elseif ($this->farmercategorycode==2)
        $this->textbox('बिगर सभासद दैनिक ऊस गाळप',400,10,'S','C',1,'siddhanta',15);
        elseif ($this->farmercategorycode==3)
        $this->textbox('गेटकेन दैनिक ऊस गाळप',400,10,'S','C',1,'siddhanta',15);
        $this->newrow(7);
        $this->textbox('दिनांक : '.$this->slipdate,400,10,'S','C',1,'SakalMarathiNormal922',13);
        $this->newrow(7);
        $this->hline(10,405,$this->liney,'C');
        $this->setfieldwidth(20,10);
        $this->textbox('स्लिप नं ',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox('काटा',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox('शिफ्ट',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox('फिल्डस्लिप',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(15);
        $this->textbox('प्लॉट',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(35);
        $this->textbox('गाव',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(15);
        $this->textbox('अंतर',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox('प्रत',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox('ऊस हंगाम',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox('ऊस जात',$this->w,$this->x,'S','L',1,'siddhanta',11); 
        $this->setfieldwidth(35);
        $this->textbox('वाहन',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(50);
        $this->textbox('तोडणीदार / बैलगाडी',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox('वाहन नं.',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(35);
        $this->textbox('वाहतुकदार / गाडीवान',$this->w,$this->x,'S','L',1,'siddhanta',11);
        /*$this->textbox('भर(मे.ट.) ',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->setfieldwidth(23);
        $this->textbox('रिकामे(मे.ट.)',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox('बायडिंग(मे.ट.)',$this->w,$this->x,'S','R',1,'siddhanta',11);*/
        
        $this->setfieldwidth(25);
        $this->textbox('निव्वळ(मे.ट.)',$this->w,$this->x,'S','R',1,'siddhanta',11);
        //$this->setfieldwidth(60);
        //$this->textbox('वाहतुकदार / गाडीवान',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->newrow();
        $this->hline(10,405,$this->liney-2,'C');
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        //$this->drawlines($limit);
    }
   
    /* function drawlines($limit)
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
    } */

    function group()
    {
        $this->totalgroupcount=3;
        $this->summary['NETWEIGHT']=0;
        $dt = DateTime::createFromFormat('d/m/Y',$this->slipdate)->format('d-M-Y');
        $cond = " and w.weighmentdate='".$dt."'";
        if ($this->farmercategorycode!=0)
        {
            $cond = $cond." and f.farmercategorycode=".$this->farmercategorycode;
        }
        if ($this->vehiclecategorycode!=0)
        {
            $cond = $cond." and t.vehiclecategorycode=".$this->vehiclecategorycode;
        }
        if ($this->shiftcode !='')
        {
            $cond = $cond." and w.shiftcode=".$this->shiftcode;
        }
        $group_query_1 = "select r.circlecode,v.villagecode,t.farmercode
            ,r.circlenameuni,v.villagenameuni
            ,t.seasoncode,t.fieldslipnumber,to_char(t.fieldslipdate,'DD/MM/YYYY') as fieldslipdate
            ,t.plotnumber,w.weightslipnumber,to_char(w.weighmentdate,'DD/MM/YYYY') as weighmentdate,w.shiftcode
            ,t.farmercategorycode,c.farmercategorynameuni,f.farmernameuni,f.farmernameeng
            ,t.villagecode,t.subvillagecode,v.villagenameuni,v.villagenameeng
            ,PV.villagecode plantationvillagecode,pv.villagenameuni plantationvillagenameuni
            ,s.subvillagenameuni plantationsubvillagenameuni,s.subvillagenameeng plantationsubvillagenameeng
            ,t.vehiclecategorycode,vc.vehiclecategorynameuni,t.vehiclecode,t.tyregadicode,vl.vehiclenumber,tg.tyregadinumber
            ,t.hrsubcontractorcode,t.hrtrsubcontractorcode,t.trsubcontractorcode
            ,t.caneconditioncode,cc.caneconditionnameuni,cc.caneconditionnameeng,t.slipboycode
            ,tr.subcontractornameuni as transporternameuni,tr.subcontractornameeng as transporternameeng
            ,hr.subcontractornameuni as harvesternameuni,hr.subcontractornameeng as harvesternameeng
            ,hrtr.subcontractornameuni as harvestertransporternameuni,hrtr.subcontractornameeng as harvestertransporternameeng
            ,tg.gadiwannameuni,tg.gadiwannameeng
            ,case when nvl(t.distance,0)>0 then t.distance
            when s.subvillagecode is not null and t.vehiclecategorycode in (3,4) then s.distancetyregadi
            when s.subvillagecode is not null and t.vehiclecategorycode in (1,2) then s.distancetrucktractor
            when s.subvillagecode is null and t.vehiclecategorycode in (3,4) then pv.distancetyregadi
            when s.subvillagecode is null and t.vehiclecategorycode in (1,2) then pv.distancetrucktractor end distance
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
            ,1 as cnt
            from weightslip w,fieldslip t,plantationheader h
            ,farmer f,farmercategory c
            ,village v,subvillage s,circle r,village pv
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
            where w.seasoncode=t.seasoncode
            and w.fieldslipnumber=t.fieldslipnumber
            and t.seasoncode=h.seasoncode 
            and t.plotnumber=h.plotnumber
            and t.farmercode=f.farmercode
            and t.farmercategorycode=c.farmercategorycode
            and f.villagecode=v.villagecode
            and v.circlecode=r.circlecode
            and t.villagecode=pv.villagecode
            and t.villagecode=s.villagecode(+)
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
            and t.caneconditioncode=cc.caneconditioncode 
            {$cond} order by r.circlecode,v.villagecode,t.farmercode,w.weightslipnumber";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
    }
    function groupheader_1(&$group_row_1)
    {
        $this->circlename=$group_row_1['CIRCLENAMEUNI'];
        $this->newpage(True);
        $this->setfieldwidth(40,10);
        $this->textbox('गट :'.$this->circlename,$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow(5);
        $this->circlesummary['NETWEIGHT']=0;
        $this->circlesummary['CNT']=0;
    }

    function groupheader_2(&$group_row_1)
    {
        $this->villagename=$group_row_1['VILLAGENAMEUNI'];
        $this->setfieldwidth(40,10);
        $this->textbox('गाव :'.$this->villagename,$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow(5);
        $this->villagesummary['NETWEIGHT']=0;
        $this->villagesummary['CNT']=0;
    }

    function groupheader_3(&$group_row_1)
    {
        $this->farmername=$group_row_1['FARMERNAMEUNI'];
        $this->setfieldwidth(70,10);
        $this->textbox($group_row_1['FARMERCODE'].' '.$this->farmername,$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow(5);
        $this->farmersummary['NETWEIGHT']=0;
        $this->farmersummary['CNT']=0;
    }
    function groupheader_4(&$group_row)
    {
    }
    function groupheader_5(&$group_row)
    {
    }
    function groupheader_6(&$group_row)
    {
    }
    function groupheader_7(&$group_row)
    {
    }
    function detail_1(&$group_row_1)
    {
        ob_flush();
        ob_start();
        //$this->hline(10,405,$this->liney-2,'D'); 
        $this->setfieldwidth(20,10);
        $this->textbox($group_row_1['WEIGHTSLIPNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['KATACODE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['SHIFTCODE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        //$this->setfieldwidth(70);
        //$this->textbox($group_row_1['FARMERCODE'].' '.$group_row_1['FARMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['FIELDSLIPNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['PLOTNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['PLANTATIONVILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['PLANTATIONSUBVILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(10);
        $this->textbox($group_row_1['DISTANCE'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['CANECONDITIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['PLANTATIONHANGAMNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['VARIETYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11); 
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['VEHICLECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(60);
        if ($group_row_1['VEHICLECATEGORYCODE']==1 or $group_row_1['VEHICLECATEGORYCODE']==2)
        {
            $this->textbox($group_row_1['HRSUBCONTRACTORCODE'].' '.$group_row_1['HARVESTERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        }
        elseif ($group_row_1['VEHICLECATEGORYCODE']==3 or $group_row_1['VEHICLECATEGORYCODE']==4)
        {
            $this->textbox($group_row_1['HRTRSUBCONTRACTORCODE'].' '.$group_row_1['HARVESTERTRANSPORTERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        }
        $this->setfieldwidth(25);
        //$this->textbox(number_format($group_row_1['LOADWEIGHT'],3,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        //$this->setfieldwidth(23);
        //$this->textbox(number_format($group_row_1['EMPTYWEIGHT'],3,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        //$this->setfieldwidth(25);
        //$this->textbox(number_format($group_row_1['BINDINGMATERIAL'],3,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        if ($group_row_1['VEHICLECATEGORYCODE']==3)
        {
            $this->textbox($group_row_1['TYREGADINUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
        }
        else 
        {
            $this->textbox($group_row_1['VEHICLENUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',8);
        }
        $this->setfieldwidth(35);
        if ($group_row_1['VEHICLECATEGORYCODE']==1 or $group_row_1['VEHICLECATEGORYCODE']==2)
        {
            $this->textbox($group_row_1['TRSUBCONTRACTORCODE'].' '.$group_row_1['TRANSPORTERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',9);
        }
        elseif ($group_row_1['VEHICLECATEGORYCODE']==3)
        {
            $this->textbox($group_row_1['GADIWANNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',9);
        }
        $this->setfieldwidth(25);
        $this->textbox(number_format($group_row_1['NETWEIGHT'],3,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        //$this->newrow(5);
        //$this->setfieldwidth(20,10);
        //$this->textbox($group_row_1['FIELDSLIPNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        //$this->setfieldwidth(20);
        //$this->textbox($group_row_1['PLOTNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        //$this->setfieldwidth(40);
        //$this->textbox($group_row_1['PLANTATIONVILLAGECODE'].' '.$group_row_1['PLANTATIONVILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        //$this->setfieldwidth(40);
        //$this->textbox($group_row_1['PLANTATIONSUBVILLAGECODE'].' '.$group_row_1['PLANTATIONSUBVILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        //$this->setfieldwidth(20);
        //$this->textbox(' -'.$group_row_1['DISTANCE'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        //$this->setfieldwidth(50);
        //$this->textbox($group_row_1['PLANTATIONCATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        //$this->setfieldwidth(25);
        //$this->setfieldwidth(35);
        
        /*$this->setfieldwidth(60);
        if ($group_row_1['VEHICLECATEGORYCODE']==1 or $group_row_1['VEHICLECATEGORYCODE']==2)
        {
            $this->textbox($group_row_1['TRSUBCONTRACTORCODE'].' '.$group_row_1['TRANSPORTERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        }
        elseif ($group_row_1['VEHICLECATEGORYCODE']==3)
        {
            $this->textbox($group_row_1['GADIWANNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        }*/
        $this->newrow(5);
        //$this->hline(10,405,$this->liney-2,'');
        $this->circlesummary['NETWEIGHT']=$this->circlesummary['NETWEIGHT']+$group_row_1['NETWEIGHT'];
        $this->villagesummary['NETWEIGHT']=$this->villagesummary['NETWEIGHT']+$group_row_1['NETWEIGHT'];
        $this->farmersummary['NETWEIGHT']=$this->farmersummary['NETWEIGHT']+$group_row_1['NETWEIGHT'];
        $this->circlesummary['CNT']=$this->circlesummary['CNT']+$group_row_1['CNT'];
        $this->villagesummary['CNT']=$this->villagesummary['CNT']+$group_row_1['CNT'];
        $this->farmersummary['CNT']=$this->farmersummary['CNT']+$group_row_1['CNT'];
        $this->summary['NETWEIGHT']=$this->summary['NETWEIGHT']+$group_row_1['NETWEIGHT'];
        $this->summary['CNT']=$this->summary['CNT']+$group_row_1['CNT'];
        if ($this->isnewpage(10))
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
    }


    function groupfooter_1(&$group_row_1)
    {     
        if ($this->isnewpage(10))
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
        }
        
        $dt = DateTime::createFromFormat('d/m/Y',$this->slipdate)->format('d-M-Y');
        $cond = " and w.weighmentdate<='".$dt."'";
        if ($this->farmercategorycode!=0)
        {
            $cond = $cond." and f.farmercategorycode=".$this->farmercategorycode;
        }

        $group_query_1 = "select sum(w.netweight) netweight
        from weightslip w,fieldslip t,plantationheader h
        ,farmer f,farmercategory c
        ,village v,subvillage s,circle r,village pv
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
        where w.seasoncode=t.seasoncode
        and w.fieldslipnumber=t.fieldslipnumber
        and t.seasoncode=h.seasoncode 
        and t.plotnumber=h.plotnumber
        and t.farmercode=f.farmercode
        and t.farmercategorycode=c.farmercategorycode
        and f.villagecode=v.villagecode
        and v.circlecode=r.circlecode
        and t.villagecode=pv.villagecode
        and t.villagecode=s.villagecode(+)
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
        and t.caneconditioncode=cc.caneconditioncode 
        and r.circlecode=".$group_row_1['CIRCLECODE']." 
        and w.seasoncode=".$_SESSION['yearperiodcode']." 
        {$cond} order by r.circlecode,v.villagecode,t.farmercode,w.weightslipnumber";
    
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $uptoweight=0;
        if ($group_row_2 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $uptoweight = $group_row_2['NETWEIGHT'];
        }

        $this->setfieldwidth(60,300);
        $this->textbox($group_row_1['CIRCLENAMEUNI'].' गट आज एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(15);
        $this->textbox($this->circlesummary['CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        //$this->setfieldwidth(15);
        $this->setfieldwidth(30);
        $this->textbox(number_format($this->circlesummary['NETWEIGHT'],3,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow();
        $this->hline(10,405,$this->liney-2,'D'); 

        $this->setfieldwidth(60,300);
        $this->textbox($group_row_1['CIRCLENAMEUNI'].' गट आजअखेर एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(15);
        //$this->setfieldwidth(15);
        $this->setfieldwidth(30);
        $this->textbox(number_format($uptoweight,3,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow();
        $this->hline(10,405,$this->liney-2,'D'); 
    }

    function groupfooter_2(&$group_row_1)
    {      
        if ($this->isnewpage(10))
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        
        $this->setfieldwidth(60,300);
        $this->textbox($group_row_1['VILLAGENAMEUNI'].' गाव एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(15);
        $this->textbox($this->villagesummary['CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        //$this->setfieldwidth(15);
        $this->setfieldwidth(30);
        $this->textbox(number_format($this->villagesummary['NETWEIGHT'],3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow();
        $this->hline(10,405,$this->liney-2,'D'); 
    }
    function groupfooter_3(&$group_row_2)
    {      
        $this->newrow(2);
        $this->hline(10,405,$this->liney-2,'');
        if ($this->isnewpage(10))
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        
        $this->setfieldwidth(60,300);
        $this->textbox($group_row_1['FARMERNAMEUNI'].' एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(15);
        $this->textbox($this->farmersummary['CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        //$this->setfieldwidth(15);
        $this->textbox(number_format($this->farmersummary['NETWEIGHT'],3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow();
        $this->hline(10,405,$this->liney-2,'C');
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
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
        }
        $dt = DateTime::createFromFormat('d/m/Y',$this->slipdate)->format('d-M-Y');
        $cond = " and w.weighmentdate<='".$dt."'";
        if ($this->farmercategorycode!=0)
        {
            $cond = $cond." and f.farmercategorycode=".$this->farmercategorycode;
        }
        $group_query_1 = "select sum(w.netweight) netweight
        from weightslip w,fieldslip t,plantationheader h
        ,farmer f,farmercategory c
        ,village v,subvillage s,circle r,village pv
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
        where w.seasoncode=t.seasoncode
        and w.fieldslipnumber=t.fieldslipnumber
        and t.seasoncode=h.seasoncode 
        and t.plotnumber=h.plotnumber
        and t.farmercode=f.farmercode
        and t.farmercategorycode=c.farmercategorycode
        and f.villagecode=v.villagecode
        and v.circlecode=r.circlecode
        and t.villagecode=pv.villagecode
        and t.villagecode=s.villagecode(+)
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
        and t.caneconditioncode=cc.caneconditioncode 
        and w.seasoncode=".$_SESSION['yearperiodcode']." 
        {$cond} order by r.circlecode,v.villagecode,t.farmercode,w.weightslipnumber";
    
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $uptoweight=0;
        if ($group_row_2 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $uptoweight = $group_row_2['NETWEIGHT'];
        }


        $this->setfieldwidth(60,300);
        $this->textbox('आज एकूण एकंदर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(15);
        $this->textbox($this->summary['CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(30);
        //$this->setfieldwidth(15);
        $this->textbox(number_format($this->summary['NETWEIGHT'],3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow();

        $this->setfieldwidth(60,300);
        $this->textbox('आजअखेर एकूण एकंदर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(15);
        //$this->setfieldwidth(15);
        $this->setfieldwidth(30);
        $this->textbox(number_format($uptoweight,3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow();
        $this->hline(10,405,$this->liney-2,'D'); 


        $this->setfieldwidth(20,60);
        $this->textbox('क्लार्क',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('केन अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','');
    
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