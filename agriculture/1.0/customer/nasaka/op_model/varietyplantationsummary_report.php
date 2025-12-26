<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a3_l.php");
    include_once("../info/routine.php");
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class varietyplantationsummary extends swappreport
{
    public $centrecode;
    public $fromdate;
    public $todate;
    public $reportfooter_row_1;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7,$subject='',$pdffilename='',$papersize='A4',$orientation='P')
	{
        $this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $this->subject= $subject;
        $this->pdffilename= $pdffilename;
        $this->papersize=strtoupper($papersize);
        $this->orientation=strtoupper($orientation);
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueType', '', 32);
        // create new PDF document
	    $this->pdf = new MYPDF($this->orientation, PDF_UNIT, $this->papersize, true, 'UTF-8', false);
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject($this->subject);
        if ($this->languagecode==0)
        {
            $this->pdf->SetKeywords(strtoupper($this->pdffilename).'.EN');
        }
        elseif ($this->languagecode==1)
        {
            $this->pdf->SetKeywords(strtoupper($this->pdffilename).'.MR');
        }
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));
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
        if ($this->languagecode==0)
        {
            $lg['a_meta_language'] = 'en';
        }
        elseif ($this->languagecode==1)
        {
            $lg['a_meta_language'] = 'mr';
        }
        $lg['w_page'] = 'Page - ';
        // set some language-dependent strings (optional)
	    $this->pdf->setLanguageArray($lg);
    }
    
    function startreport()
    {
        $this->newpage(True);
        $this->detail();
    }

    function reportheader()
    {
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->textbox('ऊसजातवार ऊसनोंद गोषवारा',400,10,'S','C',1,'siddhanta',13);
        $this->newrow(7);
        if ($this->fromdate!='' and $this->todate!='')
        {
            $this->textbox('हंगाम: '.$_SESSION['yearperiodcode'].' दिनांक '.DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y').' पासून ते दिनांक '.DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y').' पर्यंत',400,10,'S','C',1,'siddhanta',12);
        }
        else
        {
            $this->textbox('हंगाम: '.$_SESSION['yearperiodcode'],400,10,'S','C',1,'siddhanta',12);
        }
    }

	function pageheader()
    {
        ob_flush();
        ob_start();
        $this->liney = 15;
        if ($this->pdf->getPage()==1)
        {
            $this->reportheader();
        }
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->newrow(7);
        $this->hline(10,400,$this->liney,'C');
        $this->setfieldwidth(40,10);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('ऊसजात',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(105);
        $this->textbox('सभासद',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(105);
        $this->textbox('बि.सभासद',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(105);
        $this->textbox('गेटकेन',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->textbox('ए.एकंदर',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->newrow(5);
        $this->hline(50,365,$this->liney,'D');

        

        $this->setfieldwidth(40,10);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);
        $this->setfieldwidth(20);
        $this->textbox('आडसाली',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('पु.हंगामी',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox('सुरू',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('खोडवा',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('एकूण',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('आडसाली',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('पु.हंगामी',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox('सुरू',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('खोडवा',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('एकूण',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('आडसाली',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('पु.हंगामी',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox('सुरू',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('खोडवा',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('एकूण',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(35);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->newrow(7);
        $this->hline(10,400,$this->liney-2,'C');

    }

    function group()
    {
    }

    function groupheader_1($centrecode,$centrenameuni)
    {
    }

    function detail()
    { 
        $cond = " and seasoncode=".$_SESSION['yearperiodcode'];
        if ($this->fromdate!='' and $this->todate!='')
        {
            $cond = " and plantationdate>='".$this->fromdate."'
            and plantationdate<='".$this->todate."'";
        }
            $detail_query_1 = "SELECT varietycode,varietynameuni
            ,sum(member_suru) member_suru
            ,sum(member_adsali) member_adsali
            ,sum(member_khodwa) member_khodwa
            ,sum(member_preseasonal) member_preseasonal
            ,sum(member_total) member_total
            ,sum(nonmember_suru) nonmember_suru
            ,sum(nonmember_adsali) nonmember_adsali
            ,sum(nonmember_khodwa) nonmember_khodwa
            ,sum(nonmember_preseasonal) nonmember_preseasonal
            ,sum(nonmember_total) nonmember_total
            ,sum(gatecane_total) gatecane_total
            ,sum(gatecane_suru) gatecane_suru
            ,sum(gatecane_adsali) gatecane_adsali
            ,sum(gatecane_khodwa) gatecane_khodwa
            ,sum(gatecane_preseasonal) gatecane_preseasonal
            ,sum(gatecane_total) gatecane_total
            ,sum(member_total+nonmember_total+gatecane_total) gtotal
            from (
            SELECT vvv.varietycode,vvv.varietynameuni
                        ,case when farmercategorycode =1 then suru else 0 end as member_suru
                        ,case when farmercategorycode =1 then adsali else 0 end as member_adsali
                        ,case when farmercategorycode =1 then khodwa else 0 end as member_khodwa
                        ,case when farmercategorycode =1 then preseasonal else 0 end as member_preseasonal
                        ,case when farmercategorycode =1 then total else 0 end as member_total
                        
                        ,case when farmercategorycode =2 then suru else 0 end as nonmember_suru
                        ,case when farmercategorycode =2 then adsali else 0 end as nonmember_adsali
                        ,case when farmercategorycode =2 then khodwa else 0 end as nonmember_khodwa
                        ,case when farmercategorycode =2 then preseasonal else 0 end as nonmember_preseasonal
                        ,case when farmercategorycode =2 then total else 0 end as nonmember_total
                        
                        ,case when farmercategorycode =3 then suru else 0 end as gatecane_suru
                        ,case when farmercategorycode =3 then adsali else 0 end as gatecane_adsali
                        ,case when farmercategorycode =3 then khodwa else 0 end as gatecane_khodwa
                        ,case when farmercategorycode =3 then preseasonal else 0 end as gatecane_preseasonal
                        ,case when farmercategorycode =3 then total else 0 end as gatecane_total
                        
                        from (
                        SELECT v.varietycode
                        ,g.farmercategorycode,g.farmercategorynameuni
                        ,case when h.commissinorhangamcode =2 then round(p.area,2) else 0 end as preseasonal
                        ,case when h.commissinorhangamcode =3 then round(p.area,2) else 0 end as suru
                        ,case when h.commissinorhangamcode =1 then round(p.area,2) else 0 end as adsali
                        ,case when h.commissinorhangamcode =4 then round(p.area,2) else 0 end as khodwa
                        ,round(p.area,2) as total
                        FROM plantationheader p,variety v,farmer f,farmercategory g,plantationhangam h
                        where p.varietycode=v.varietycode 
                        and p.farmercode=f.farmercode
                        and f.farmercategorycode=g.farmercategorycode
                        and p.plantationhangamcode=h.plantationhangamcode
                        {$cond}
                        union all
                        select t.varietycode 
                        ,t.farmercategorycode,t.farmercategorynameuni
                        ,0 as preseasonal
                        ,0 as suru
                        ,0 as adsali
                        ,0 as khodwa
                        ,0 as total
                        from 
                        (select ff.farmercategorycode,fc.farmercategorynameuni,vv.varietycode,vv.varietynameuni,pp.farmercode 
                        from plantationheader pp,variety vv,farmer ff,farmercategory fc
                        where pp.varietycode=vv.varietycode 
                         {$cond}
                        and pp.farmercode=ff.farmercode and ff.farmercategorycode=fc.farmercategorycode
                        group by ff.farmercategorycode,fc.farmercategorynameuni,vv.varietycode,vv.varietynameuni,pp.farmercode)t 
                        group by t.farmercategorycode,t.farmercategorynameuni,t.varietycode,t.varietynameuni
                        )ttt,variety vvv
                        where ttt.varietycode=vvv.varietycode
                        )
                        group by varietycode,varietynameuni
                        order by varietycode
            ";
        $detail_result_1 = oci_parse($this->connection, $detail_query_1);
        $r = oci_execute($detail_result_1);
        while ($detail_row_1 = oci_fetch_array($detail_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->setfieldwidth(40,10);
            $this->vline($this->liney-2,$this->liney+5,$this->x);
            $this->textbox($detail_row_1['VARIETYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($detail_row_1['MEMBER_ADSALI'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($detail_row_1['MEMBER_PRESEASONAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20); 
            $this->textbox($detail_row_1['MEMBER_SURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($detail_row_1['MEMBER_KHODWA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(25);
            $this->textbox($detail_row_1['MEMBER_TOTAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->setfieldwidth(20);
            $this->textbox($detail_row_1['NONMEMBER_ADSALI'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($detail_row_1['NONMEMBER_PRESEASONAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20); 
            $this->textbox($detail_row_1['NONMEMBER_SURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($detail_row_1['NONMEMBER_KHODWA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(25);
            $this->textbox($detail_row_1['NONMEMBER_TOTAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->setfieldwidth(20);
            $this->textbox($detail_row_1['GATECANE_ADSALI'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($detail_row_1['GATECANE_PRESEASONAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20); 
            $this->textbox($detail_row_1['GATECANE_SURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($detail_row_1['GATECANE_KHODWA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(25);
            $this->textbox($detail_row_1['GATECANE_TOTAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->setfieldwidth(35);
            $this->textbox($detail_row_1['GTOTAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);


            $this->reportfooter_row_1['MEMBER_ADSALI']=$this->reportfooter_row_1['MEMBER_ADSALI']+$detail_row_1['MEMBER_ADSALI'];
            $this->reportfooter_row_1['MEMBER_PRESEASONAL']=$this->reportfooter_row_1['MEMBER_PRESEASONAL']+$detail_row_1['MEMBER_PRESEASONAL'];
            $this->reportfooter_row_1['MEMBER_SURU']=$this->reportfooter_row_1['MEMBER_SURU']+$detail_row_1['MEMBER_SURU'];
            $this->reportfooter_row_1['MEMBER_KHODWA']=$this->reportfooter_row_1['MEMBER_KHODWA']+$detail_row_1['MEMBER_KHODWA'];
            $this->reportfooter_row_1['MEMBER_TOTAL']=$this->reportfooter_row_1['MEMBER_TOTAL']+$detail_row_1['MEMBER_TOTAL'];
            $this->reportfooter_row_1['NONMEMBER_ADSALI']=$this->reportfooter_row_1['NONMEMBER_ADSALI']+$detail_row_1['NONMEMBER_ADSALI'];
            $this->reportfooter_row_1['NONMEMBER_PRESEASONAL']=$this->reportfooter_row_1['NONMEMBER_PRESEASONAL']+$detail_row_1['NONMEMBER_PRESEASONAL'];
            $this->reportfooter_row_1['NONMEMBER_SURU']=$this->reportfooter_row_1['NONMEMBER_SURU']+$detail_row_1['NONMEMBER_SURU'];
            $this->reportfooter_row_1['NONMEMBER_KHODWA']=$this->reportfooter_row_1['NONMEMBER_KHODWA']+$detail_row_1['NONMEMBER_KHODWA'];
            $this->reportfooter_row_1['NONMEMBER_TOTAL']=$this->reportfooter_row_1['NONMEMBER_TOTAL']+$detail_row_1['NONMEMBER_TOTAL'];
            $this->reportfooter_row_1['GATECANE_ADSALI']=$this->reportfooter_row_1['GATECANE_ADSALI']+$detail_row_1['GATECANE_ADSALI'];
            $this->reportfooter_row_1['GATECANE_PRESEASONAL']=$this->reportfooter_row_1['GATECANE_PRESEASONAL']+$detail_row_1['GATECANE_PRESEASONAL'];
            $this->reportfooter_row_1['GATECANE_SURU']=$this->reportfooter_row_1['GATECANE_SURU']+$detail_row_1['GATECANE_SURU'];
            $this->reportfooter_row_1['GATECANE_KHODWA']=$this->reportfooter_row_1['GATECANE_KHODWA']+$detail_row_1['GATECANE_KHODWA'];
            $this->reportfooter_row_1['GATECANE_TOTAL']=$this->reportfooter_row_1['GATECANE_TOTAL']+$detail_row_1['GATECANE_TOTAL'];
            $this->reportfooter_row_1['GTOTAL']=$this->reportfooter_row_1['GTOTAL']+$detail_row_1['GTOTAL'];
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);


            if ($this->isnewpage(10))
            {
                $this->newrow();
                $this->hline(10,400,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            else
            {
                $this->newrow();
                $this->hline(10,400,$this->liney-2,'C'); 
            }
        }
        $this->reportfooter();
    }
    
    function groupfooter_1($centrecode)
    {
    }
    
    function pagefooter($islastpage=false)
    {
    }

    function reportfooter()
    {
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,400,$this->liney-2,'C'); 
            $this->newpage(True);
        }
            $this->setfieldwidth(40,10);
            $this->vline($this->liney-2,$this->liney+5,$this->x);
            $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','R',1,'Siddhanta',10,'','','','B');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($this->reportfooter_row_1['MEMBER_ADSALI'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($this->reportfooter_row_1['MEMBER_PRESEASONAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20); 
            $this->textbox($this->reportfooter_row_1['MEMBER_SURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($this->reportfooter_row_1['MEMBER_KHODWA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(25);
            $this->textbox($this->reportfooter_row_1['MEMBER_TOTAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->setfieldwidth(20);
            $this->textbox($this->reportfooter_row_1['NONMEMBER_ADSALI'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($this->reportfooter_row_1['NONMEMBER_PRESEASONAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20); 
            $this->textbox($this->reportfooter_row_1['NONMEMBER_SURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($this->reportfooter_row_1['NONMEMBER_KHODWA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(25);
            $this->textbox($this->reportfooter_row_1['NONMEMBER_TOTAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->setfieldwidth(20);
            $this->textbox($this->reportfooter_row_1['GATECANE_ADSALI'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($this->reportfooter_row_1['GATECANE_PRESEASONAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20); 
            $this->textbox($this->reportfooter_row_1['GATECANE_SURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($this->reportfooter_row_1['GATECANE_KHODWA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(25);
            $this->textbox($this->reportfooter_row_1['GATECANE_TOTAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->setfieldwidth(35);
            $this->textbox($this->reportfooter_row_1['GTOTAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->newrow();
            $this->hline(10,400,$this->liney-2,'C'); 

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