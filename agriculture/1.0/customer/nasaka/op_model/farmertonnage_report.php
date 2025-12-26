<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class farmertonnage extends reportbox
{
    public $fromdate;
    public $todate;
    public $farmercode;
    public $farmernameuni;
    public $circlenameuni;
    public $villagenameuni;
    public $datesummary;
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
	    $this->pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Farmer Tonnage');
        $this->pdf->SetKeywords('FARTON_000.MR');
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
        $this->textbox('ऊस उत्पादक निहाय टनेज',180,10,'S','C',1,'siddhanta',12);
        $this->newrow(7);
        if ($this->fromdate!='' and $this->fromdate !='')
        {
            $fromdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $todt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $this->textbox('हंगाम : '.$_SESSION['yearperiodcode'].' दिनांक '.$this->fromdate.' पासून दिनांक '.$this->todate.' पर्यंत',180,10,'S','C',1,'siddhanta',11);
        }
        else
        {
            $this->textbox('हंगाम : '.$_SESSION['yearperiodcode'],180,10,'S','C',1,'siddhanta',11);
        }

        $this->newrow(7);
        $this->textbox('उत्पादक : '.$this->farmercode.' '.$this->farmernameuni.' गट : '.$this->circlenameuni.' गाव : '.$this->villagenameuni.$this->shivar,180,10,'S','C',1,'siddhanta',11);
        $this->newrow(7);
        $this->hline(10,405,$this->liney,'C');
        $this->setfieldwidth(25,10);
        $this->textbox('स्लिप नं ',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(30);
        $this->textbox('सर्वे नं.',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(35);
        $this->textbox('ट्रक',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(35);
        $this->textbox('ट्रॅक्टर',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(35);
        $this->textbox('बैलगाडी',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(35);
        $this->textbox('जुगाड',$this->w,$this->x,'S','L',1,'siddhanta',11); 
        $this->newrow(7);
        $this->setfieldwidth(15,60);
        $this->textbox('संख्या',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox('टनेज',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(15);
        $this->textbox('संख्या',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox('टनेज',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(15);
        $this->textbox('संख्या',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox('टनेज',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(15);
        $this->textbox('संख्या',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox('टनेज',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->newrow();
        $this->hline(10,200,$this->liney-2,'C');
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        //$this->drawlines($limit);
    }
   

    function group()
    {
        $this->totalgroupcount=2;
        $this->summary['NETWEIGHT']=0;

        $cond = '';
        if ($this->fromdate!='' and $this->todate !='')
        {
            $fromdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $todt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $cond = " and w.weighmentdate>='".$fromdt."' and w.weighmentdate<='".$todt."'";
        }
            $cond = $cond." and w.seasoncode=".$_SESSION['yearperiodcode']." and f.farmercode=".$this->farmercode;

        $group_query_1 = "select PV.villagecode plantationvillagecode
            ,to_char(w.weighmentdate,'DD/MM/YYYY') as weighmentdate
            ,r.circlecode,v.villagecode
            ,r.circlenameuni,v.villagenameuni
            ,h.gutnumber
            ,t.seasoncode,t.fieldslipnumber,to_char(t.fieldslipdate,'DD/MM/YYYY') as fieldslipdate
            ,t.plotnumber,w.weightslipnumber,w.shiftcode
            ,t.farmercategorycode,c.farmercategorynameuni,t.farmercode,f.farmernameuni,f.farmernameeng
            ,t.villagecode,t.subvillagecode,v.villagenameuni,v.villagenameeng,s.subvillagenameuni,s.subvillagenameeng
            ,pv.villagenameuni plantationvillagenameuni,pv.villagenameeng plantationvillagenameeng
            ,w.netweight
            ,case when t.vehiclecategorycode=1 then 1 else 0 end truck_cnt
            ,case when t.vehiclecategorycode=2 then 1 else 0 end tractor_cnt
            ,case when t.vehiclecategorycode=3 then 1 else 0 end bailgadi_cnt
            ,case when t.vehiclecategorycode=4 then 1 else 0 end jugad_cnt
            ,case when t.vehiclecategorycode=1 then w.netweight else 0 end truck_tonnage
            ,case when t.vehiclecategorycode=2 then w.netweight else 0 end tractor_tonnage
            ,case when t.vehiclecategorycode=3 then w.netweight else 0 end bailgadi_tonnage
            ,case when t.vehiclecategorycode=4 then w.netweight else 0 end jugad_tonnage
            ,1 as cnt
            ,cc.caneconditionnameuni
            from weightslip w,fieldslip t,plantationheader h
            ,farmer f,farmercategory c
            ,village v,subvillage s,circle r,village pv,canecondition cc
            where w.seasoncode=t.seasoncode
            and w.fieldslipnumber=t.fieldslipnumber
            and t.seasoncode=h.seasoncode 
            and t.plotnumber=h.plotnumber
            and t.farmercode=f.farmercode
            and t.farmercategorycode=c.farmercategorycode
            and f.villagecode=v.villagecode
            and v.circlecode=r.circlecode
            and t.villagecode=pv.villagecode
            and t.caneconditioncode=cc.caneconditioncode
            and t.subvillagecode=s.subvillagecode(+)
            {$cond} 
            order by PV.villagecode,w.weighmentdate,fieldslipnumber";
        
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
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
        $this->farmernameuni=$group_row_1['FARMERNAMEUNI'];
        $this->circlenameuni=$group_row_1['CIRCLENAMEUNI'];
        $this->villagenameuni=$group_row_1['VILLAGENAMEUNI'];
        $this->newpage(True);
        $this->setfieldwidth(40,10);
        $this->textbox('शिवार : '.$group_row_1['PLANTATIONVILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->shivarsummary['NETWEIGHT']=0;
        $this->shivarsummary['CNT']=0;
    }

    function groupheader_2(&$group_row_1)
    {
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
        $this->setfieldwidth(40,10);
        $this->textbox($group_row_1['WEIGHMENTDATE'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->datesummary['TRUCK_TONNAGE']=0;
        $this->datesummary['TRUCK_CNT']=0;
        $this->datesummary['TRACTOR_TONNAGE']=0;
        $this->datesummary['TRACTOR_CNT']=0;
        $this->datesummary['BAILGADI_TONNAGE']=0;
        $this->datesummary['BAILGADI_CNT']=0;
        $this->datesummary['JUGAD_TONNAGE']=0;
        $this->datesummary['JUGAD_CNT']=0;
        $this->datesummary['NETWEIGHT']=0;
        $this->datesummary['CNT']=0;

    }

    function groupheader_3(&$group_row_1)
    {
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
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
        $this->setfieldwidth(25,10);
        $this->textbox($group_row_1['FIELDSLIPNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['GUTNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['TRUCK_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['TRUCK_TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['TRACTOR_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['TRACTOR_TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['BAILGADI_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['BAILGADI_TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['JUGAD_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['JUGAD_TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['CANECONDITIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',8,'','Y');
        $this->newrow(8);
        $this->shivarsummary['NETWEIGHT']=$this->shivarsummary['NETWEIGHT']+$group_row_1['NETWEIGHT'];
        $this->shivarsummary['CNT']=$this->shivarsummary['CNT']+$group_row_1['CNT'];

        $this->shivarsummary['TRUCK_TONNAGE']=$this->shivarsummary['TRUCK_TONNAGE']+$group_row_1['TRUCK_TONNAGE'];
        $this->shivarsummary['TRUCK_CNT']=$this->shivarsummary['TRUCK_CNT']+$group_row_1['TRUCK_CNT'];

        $this->shivarsummary['TRACTOR_TONNAGE']=$this->shivarsummary['TRACTOR_TONNAGE']+$group_row_1['TRACTOR_TONNAGE'];
        $this->shivarsummary['TRACTOR_CNT']=$this->shivarsummary['TRACTOR_CNT']+$group_row_1['TRACTOR_CNT'];

        $this->shivarsummary['BAILGADI_TONNAGE']=$this->shivarsummary['BAILGADI_TONNAGE']+$group_row_1['BAILGADI_TONNAGE'];
        $this->shivarsummary['BAILGADI_CNT']=$this->shivarsummary['BAILGADI_CNT']+$group_row_1['BAILGADI_CNT'];

        $this->shivarsummary['JUGAD_TONNAGE']=$this->shivarsummary['JUGAD_TONNAGE']+$group_row_1['JUGAD_TONNAGE'];
        $this->shivarsummary['JUGAD_CNT']=$this->shivarsummary['JUGAD_CNT']+$group_row_1['JUGAD_CNT'];

        $this->shivarsummary['NETWEIGHT']=$this->shivarsummary['NETWEIGHT']+$group_row_1['NETWEIGHT'];
        $this->shivarsummary['CNT']=$this->shivarsummary['CNT']+$group_row_1['CNT'];

        
        $this->datesummary['TRUCK_TONNAGE']=$this->datesummary['TRUCK_TONNAGE']+$group_row_1['TRUCK_TONNAGE'];
        $this->datesummary['TRUCK_CNT']=$this->datesummary['TRUCK_CNT']+$group_row_1['TRUCK_CNT'];

        $this->datesummary['TRACTOR_TONNAGE']=$this->datesummary['TRACTOR_TONNAGE']+$group_row_1['TRACTOR_TONNAGE'];
        $this->datesummary['TRACTOR_CNT']=$this->datesummary['TRACTOR_CNT']+$group_row_1['TRACTOR_CNT'];

        $this->datesummary['BAILGADI_TONNAGE']=$this->datesummary['BAILGADI_TONNAGE']+$group_row_1['BAILGADI_TONNAGE'];
        $this->datesummary['BAILGADI_CNT']=$this->datesummary['BAILGADI_CNT']+$group_row_1['BAILGADI_CNT'];

        $this->datesummary['JUGAD_TONNAGE']=$this->datesummary['JUGAD_TONNAGE']+$group_row_1['JUGAD_TONNAGE'];
        $this->datesummary['JUGAD_CNT']=$this->datesummary['JUGAD_CNT']+$group_row_1['JUGAD_CNT'];

        $this->datesummary['NETWEIGHT']=$this->datesummary['NETWEIGHT']+$group_row_1['NETWEIGHT'];
        $this->datesummary['CNT']=$this->datesummary['CNT']+$group_row_1['CNT'];


        $this->summary['TRUCK_TONNAGE']=$this->summary['TRUCK_TONNAGE']+$group_row_1['TRUCK_TONNAGE'];
        $this->summary['TRUCK_CNT']=$this->summary['TRUCK_CNT']+$group_row_1['TRUCK_CNT'];

        $this->summary['TRACTOR_TONNAGE']=$this->summary['TRACTOR_TONNAGE']+$group_row_1['TRACTOR_TONNAGE'];
        $this->summary['TRACTOR_CNT']=$this->summary['TRACTOR_CNT']+$group_row_1['TRACTOR_CNT'];

        $this->summary['BAILGADI_TONNAGE']=$this->summary['BAILGADI_TONNAGE']+$group_row_1['BAILGADI_TONNAGE'];
        $this->summary['BAILGADI_CNT']=$this->summary['BAILGADI_CNT']+$group_row_1['BAILGADI_CNT'];

        $this->summary['JUGAD_TONNAGE']=$this->summary['JUGAD_TONNAGE']+$group_row_1['JUGAD_TONNAGE'];
        $this->summary['JUGAD_CNT']=$this->summary['JUGAD_CNT']+$group_row_1['JUGAD_CNT'];

        
        $this->summary['NETWEIGHT']=$this->summary['NETWEIGHT']+$group_row_1['NETWEIGHT'];
        $this->summary['CNT']=$this->summary['CNT']+$group_row_1['CNT'];
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
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
        if ($this->isnewpage(15))
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

        $this->hline(10,200,$this->liney-2,'D'); 
        $this->setfieldwidth(40,10);
        $this->textbox($group_row_1['PLANTATIONVILLAGENAMEUNI'].' एकूण',$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->setfieldwidth(15);
        $this->textbox($this->shivarsummary['TRUCK_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($this->shivarsummary['TRUCK_TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(15);
        $this->textbox($this->shivarsummary['TRACTOR_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($this->shivarsummary['TRACTOR_TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(15);
        $this->textbox($this->shivarsummary['BAILGADI_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($this->shivarsummary['BAILGADI_TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(15);
        $this->textbox($this->shivarsummary['JUGAD_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($this->shivarsummary['JUGAD_TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(25);
        $this->textbox($this->shivarsummary['CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox(number_format($this->shivarsummary['NETWEIGHT'],3,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->newrow();
        $this->hline(10,200,$this->liney-2,'D'); 
    }

    function groupfooter_2(&$group_row_1)
    {      
        if ($this->isnewpage(15))
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow(2);
            //$this->hline(10,405,$this->liney-2,'C'); 
        }
        $this->hline(10,200,$this->liney-2,'D'); 
        $this->setfieldwidth(40,10);
        $this->textbox($group_row_1['WEIGHMENTDATE'].' एकूण',$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->setfieldwidth(15);
        $this->textbox($this->datesummary['TRUCK_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($this->datesummary['TRUCK_TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(15);
        $this->textbox($this->datesummary['TRACTOR_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($this->datesummary['TRACTOR_TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(15);
        $this->textbox($this->datesummary['BAILGADI_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($this->datesummary['BAILGADI_TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(15);
        $this->textbox($this->datesummary['JUGAD_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($this->datesummary['JUGAD_TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(25);
        $this->textbox($this->datesummary['CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->setfieldwidth(25);
        $this->textbox(number_format($this->datesummary['NETWEIGHT'],3,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->newrow();
        $this->hline(10,200,$this->liney-2,'D'); 
    }
    function groupfooter_3(&$group_row_2)
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
        if ($this->isnewpage(20))
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


        $this->hline(10,200,$this->liney-2,'D'); 
        $this->setfieldwidth(40,10);
        $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->setfieldwidth(15);
        $this->textbox($this->summary['TRUCK_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($this->summary['TRUCK_TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(15);
        $this->textbox($this->summary['TRACTOR_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($this->summary['TRACTOR_TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(15);
        $this->textbox($this->summary['BAILGADI_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($this->summary['BAILGADI_TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(15);
        $this->textbox($this->summary['JUGAD_CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($this->summary['JUGAD_TONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(25);
        $this->textbox($this->summary['CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow();
        $this->hline(10,200,$this->liney-2,'C'); 
        $this->setfieldwidth(60,50);
        $this->textbox('उत्पादक एकूण टनेज',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(35);
        $this->textbox(number_format($this->summary['NETWEIGHT'],3,'.',''),$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11,'','','','B');
        $this->newrow();
        $this->hline(10,200,$this->liney-2,'D'); 



        if ($this->isnewpage(15))
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
        $this->newrow(15);
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