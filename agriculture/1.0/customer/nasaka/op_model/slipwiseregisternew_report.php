<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class slipregister extends reportbox
{	
   
    public $fromdate;
    public $todate;
    public $contractorcode;

    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueType', '', 32);
    	// create new PDF document
	    $this->pdf = new MYPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
        
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Sale Register');
        $this->pdf->SetKeywords('SLRG_000.EN');
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
        $this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

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
        $this->groupfield1='NETWEIGHT';
        $this->resetgroupsummary(0);
        $this->group();
        //$this->reportfooter();
    }
    function endreport()
    {
        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output('SLRG_000.pdf', 'I');
    }
	function pageheader()
    { 
        $this->liney = 10;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
       // $this->textbox($this->ftransactionnumber['FARMERCOUNT'],175,10,'S','C',1,'siddhanta',13);
       
        $this->newrow(10);
        $this->textbox('वाहन वाईज रजिस्टर',250,10,'S','C',1,'siddhanta',13);
        $this->newrow(7);
        
        if ($this->fromdate!='' and $this->todate!='')
        {
            //$this->newrow(7);
            $this->textbox('दिनांक '.$this->fromdate.' पासून दिनांक '.$this->todate.' पर्यंत',250,10,'S','C',1,'siddhanta',12);
        }
        $this->hline(5,291,$this->liney+6,'C');
        $this->newrow();
         
        $this->setfieldwidth(11,5);   
        $this->vline($this->liney-1,$this->liney+14,$this->x);   
        $this->textbox('अनु. क्र.',$this->w,$this->x,'N','R',1,'siddhanta',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+12,$this->x+$this->w);

        $this->setfieldwidth(15);
       // $this->vline($this->liney-1,$this->liney+15,$this->x);
        $this->textbox('स्लिप नं',$this->w,$this->x,'S','L',1,'siddhanta',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+12,$this->x+$this->w);

        $this->setfieldwidth(22);
        $this->textbox('दिनांक',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-1,$this->liney+12,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->textbox('शेतकऱ्याचे नाव',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-1,$this->liney+12,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('गट',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-1,$this->liney+12,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('गाव',$this->w,$this->x,'N','L',1,'siddhanta',10,'','','','');
        $this->vline($this->liney-1,$this->liney+12,$this->x+$this->w);
        
        $this->setfieldwidth(15);
        $this->textbox('वाहन प्रकार',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-1,$this->liney+12,$this->x+$this->w);

      
        $this->setfieldwidth(28);
        $this->textbox('वाहन नं',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-1,$this->liney+12,$this->x+$this->w);

               
        $this->setfieldwidth(35);
        $this->textbox('तोडणीदाराचे नाव',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->newrow();
        $this->textbox('वाह्तुकदाराचे नाव',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-8,$this->liney+5,$this->x+$this->w);
        $this->newrow(-7);

        $this->setfieldwidth(36,195);
        $this->textbox('गाडीवान नाव',$this->w,$this->x,'S','C',1,'siddhanta',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+12,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('वजन',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-1,$this->liney+12,$this->x+$this->w);
       
        $this->setfieldwidth(15);
        $this->textbox('अंतर',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-1,$this->liney+12,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('वाहतूक दर',$this->w,$this->x,'N','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-1,$this->liney+12,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('तोडणी दर',$this->w,$this->x,'N','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-1,$this->liney+13,$this->x+$this->w);
        $this->newrow(6);
       
        $this->hline(5,291,$this->liney+5,'C');

        $this->newrow(8);
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

    function group()
    {
        $this->totalgroupcount=1;
        $cond="1=1";
        $cond1=" c.seasoncode=".$_SESSION['yearperiodcode'];     
        if ($this->fromdate!='' and $this->todate!='')
        {
            $frdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $todt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $cond=$cond." and w.weighmentdate>='".$frdt."' and w.weighmentdate<='".$todt."'";
        }
        if ($this->contractorcode !='')
        {
            $cond1=$cond1." and c.contractorcode=".$this->contractorcode;
        }
        $group_query_1 =" 
        select vehiclenumber,weightslipnumber,row_number()over(partition by vehiclenumber order by vehiclenumber)Sr_no
        ,ddate,fieldslipnumber,vfarmer,villagecode,villagenameuni,circlecode,circlenameuni
        ,vehiclecategorynameuni,vtransporter,vharvester,vgadiwan,netweight
        ,vehiclecategorycode,distancetrucktractor,transportrate,harvestrate 
        ,vehiclecode,transportercode,harvestercode    
        from(
        select w.weightslipnumber
        ,row_number()over( order by weighmentdate,weightslipnumber)Sr_no
        ,to_char(w.weighmentdate,'dd/mm/yyyy')ddate
        ,w.fieldslipnumber
        ,fa.farmernameuni vfarmer
        ,f.villagecode
        ,v.villagenameuni
        ,v.circlecode
        ,c.circlenameuni
        ,ve.vehiclecategorynameuni
        ,case when ve.vehiclecategorycode in (1,2,4) 
        then vv.vehiclenumber else ty.tyregadinumber end vehiclenumber
        ,case when ve.vehiclecategorycode in (1,2,4) 
        then s.subcontractornameuni when ve.vehiclecategorycode in (3) then
        sss.subcontractornameuni end vtransporter
        ,case when ve.vehiclecategorycode in (1,2,4) 
        then s.subcontractorcode when ve.vehiclecategorycode in (3) then
        sss.subcontractorcode end transportercode
        ,case when ve.vehiclecategorycode in (1,2) 
        then ss.subcontractornameuni 
        when ve.vehiclecategorycode in (3) then
        sss.subcontractornameuni 
        when ve.vehiclecategorycode in (4) and f.hrsubcontractorcode is null then
        s.subcontractornameuni
        when ve.vehiclecategorycode in (4) and f.hrsubcontractorcode is not null then
        ss.subcontractornameuni  
        end vharvester
        ,case when ve.vehiclecategorycode in (1,2) 
        then ss.subcontractorcode 
        when ve.vehiclecategorycode in (3) then
        sss.subcontractorcode 
        when ve.vehiclecategorycode in (4) and f.hrsubcontractorcode is null then
        s.subcontractorcode
        when ve.vehiclecategorycode in (4) and f.hrsubcontractorcode is not null then
        ss.subcontractorcode  
        end harvestercode
        ,case when ve.vehiclecategorycode in (3) then 
        ty.gadiwannameuni
        when ve.vehiclecategorycode in (4) then
          vv.drivernameuni
          end vgadiwan
         ,w.netweight
         ,f.vehiclecategorycode
         ,v.distancetrucktractor
         ,case when ve.vehiclecategorycode in (1,2) then
               htdistancerate(w.seasoncode,1,ssss.transportersubcategorycode,v.distancetrucktractor,w.weighmentdate) 
         when ve.vehiclecategorycode in (4) then
               htdistancerate(w.seasoncode,1,ssss.transportersubcategorycode,v.distancetyregadi,w.weighmentdate) 
         when ve.vehiclecategorycode in (3) then
               htdistancerate(w.seasoncode,1,sss.transportersubcategorycode,v.distancetyregadi,w.weighmentdate)           
         end  transportrate
         ,case when ve.vehiclecategorycode in (1,2,4) and f.hrsubcontractorcode is not null then
               htharvestingrate(w.seasoncode,1,ss.harvestersubcategorycode,w.weighmentdate)
         when ve.vehiclecategorycode in (4) and f.hrsubcontractorcode is null then
               htharvestingrate(w.seasoncode,1,ssss.harvestersubcategorycode,w.weighmentdate)
         when ve.vehiclecategorycode in (3) then
              htharvestingrate(w.seasoncode,1,sss.harvestersubcategorycode,w.weighmentdate)
         end  harvestrate 
         ,case when ve.vehiclecategorycode in (1,2,4) then vv.vehiclecode 
         else ty.tyregadicode end vehiclecode  
         from WEIGHTSLIP w,fieldslip f,farmer fa
        ,village v,circle c,VEHICLECATEGORY ve
        ,vehicle vv,subcontractor s,subcontractor sss,subcontractor ss,subcontractor ssss
        ,tyregadi ty
        where {$cond}
        and w.seasoncode=f.seasoncode 
        and w.fieldslipnumber=f.fieldslipnumber 
        and f.farmercode=fa.farmercode
        and f.villagecode=v.villagecode
        and v.circlecode=c.circlecode
        and f.vehiclecategorycode=ve.vehiclecategorycode      
        and vv.seasoncode=s.seasoncode(+)
        and vv.subcontractorcode=s.subcontractorcode(+)      
        and ty.seasoncode=sss.seasoncode(+)
        and ty.subcontractorcode=sss.subcontractorcode(+)
        and f.seasoncode=ss.seasoncode(+)
        and f.hrsubcontractorcode=ss.subcontractorcode(+)      
        and f.seasoncode=vv.seasoncode(+)
        and f.vehiclecode=vv.vehiclecode(+)   
        and f.seasoncode=ty.seasoncode(+)
        and f.tyregadicode=ty.tyregadicode(+) 
        and vv.seasoncode=ssss.seasoncode(+)
        and vv.subcontractorcode=ssss.subcontractorcode(+)      
        and w.netweight>0
        and ((s.seasoncode,s.contractcode) in 
        (select c.seasoncode,c.contractcode 
        from contractorcontract c 
        where {$cond1}) or (ss.seasoncode,ss.contractcode) in 
        (select c.seasoncode,c.contractcode 
        from contractorcontract c 
        where {$cond1}) or (sss.seasoncode,sss.contractcode) in 
        (select c.seasoncode,c.contractcode 
        from contractorcontract c 
        where {$cond1}))
        order by w.weighmentdate,w.weightslipnumber  
        )order by vehiclenumber,weightslipnumber ,ddate    ";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        $this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->sumgroupsummary($group_row_1,0);
            $this->sumgroupsummary($group_row_1,1);
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
        
    }
    
    function groupheader_1(&$group_row_1)
    {  
        if ($this->isnewpage(25))
        {
            $this->newrow();
            $this->hline(10,285,$this->liney,'D');  
            $this->newpage(True);
        }
               
        $this->resetgroupsummary(1);
        $this->setfieldwidth(150,5);  
        $this->vline($this->liney-1,$this->liney+7,$this->x);  
        $this->textbox(' Vehicle No - '.$group_row_1['VEHICLENUMBER'].'('.$group_row_1['VEHICLECODE'].')',$this->w,$this->x,'S','L',1,'siddhanta',13,'','','','B'); 
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w+136);
        if ($this->isnewpage(25))
        {
            $this->newrow();
            $this->hline(5,291,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(5,291,$this->liney-1,'C'); 
        }    
            
    }

    function groupheader_2(&$group_row_1)
    {
    }

    function groupheader_3(&$group_row_1)
    {
    }
    function groupheader_4(&$group_row_1)
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
      
              
        $this->setfieldwidth(11,5);
        $this->vline($this->liney-1,$this->liney+14,$this->x);
        $this->textbox($group_row_1['SR_NO'],$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney-1,$this->liney+14,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['WEIGHTSLIPNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney-1,$this->liney+14,$this->x+$this->w);

        $this->setfieldwidth(22);
        $this->textbox($group_row_1['DDATE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney-1,$this->liney+14,$this->x+$this->w);
       
        $this->setfieldwidth(35);
        $this->textbox($group_row_1['VFARMER'],$this->w,$this->x,'N','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+14,$this->x+$this->w);
       
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['CIRCLENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+14,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'N','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+14,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['VEHICLECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+14,$this->x+$this->w);

        $this->setfieldwidth(28);
        $this->textbox($group_row_1['VEHICLENUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney-1,$this->liney+14,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->textbox($group_row_1['VHARVESTER'].' ('.$group_row_1['HARVESTERCODE'].')',$this->w,$this->x,'N','L',1,'siddhanta',9);
        $this->newrow();
        $this->textbox($group_row_1['VTRANSPORTER'].' ('.$group_row_1['TRANSPORTERCODE'].')',$this->w,$this->x,'N','L',1,'siddhanta',9);
        $this->vline($this->liney-8,$this->liney+7,$this->x+$this->w);
        $this->newrow(-7);

        $this->setfieldwidth(30,201);
       // $this->setfieldwidth(30);
        $this->textbox($group_row_1['VGADIWAN'],$this->w,$this->x,'N','C',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+14,$this->x+$this->w);
       
        $this->setfieldwidth(15);
        $this->textbox($this->numformat($group_row_1['NETWEIGHT'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+14,$this->x+$this->w);
           
        $this->setfieldwidth(15);
        $this->textbox($this->numformat($group_row_1['DISTANCETRUCKTRACTOR'],0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+14,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($this->numformat($group_row_1['TRANSPORTRATE'],2),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+14,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($this->numformat($group_row_1['HARVESTRATE'],2),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+14,$this->x+$this->w);

        $this->newrow();

        if ($this->isnewpage(120))
        {
            $this->newrow();
            $this->hline(5,291,$this->liney-1,'D'); 
            $this->newpage(True);
           
          
            //$this->hline(5,291,$this->liney,'D'); 
        }   
        else
        {
            $this->newrow();
           $this->hline(5,291,$this->liney,'D'); 
        }
    }
    
    function groupfooter_1(&$group_row_1)
    { 
        $this->vline($this->liney-1,$this->liney+7,$this->x-271);
        $this->setfieldwidth(50,185);
        $this->textbox('वाहन टनेज',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(15,230);       
        $this->textbox($this->numformat($this->showgroupsummary(1,'NETWEIGHT'),3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
       
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w+1);

        if ($this->isnewpage(50))
        {
            $this->newrow();
            $this->hline(5,291,$this->liney-2,'C'); 
            $this->newpage(True);
         
        }   
        else
        {
            $this->newrow();
           $this->hline(5,291,$this->liney-2,'C'); 
        }
    }
    function groupfooter_2(&$group_row_1)
    {  
    }

    function groupfooter_3(&$group_row_1)
    {     
    }
    function groupfooter_4(&$group_row_1)
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

    function reportfooter()
    {
        $this->vline($this->liney-1,$this->liney+7,$this->x-271);
        $this->setfieldwidth(50,185);
        $this->textbox('एकूण टनेज',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(15,230);       
       $this->textbox($this->numformat($this->showgroupsummary(0,'NETWEIGHT'),3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
       
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w+1);

        $this->newrow();
        $this->hline(5,291,$this->liney,'C'); 
      //  $this->newrow();
    }

}    
?>
