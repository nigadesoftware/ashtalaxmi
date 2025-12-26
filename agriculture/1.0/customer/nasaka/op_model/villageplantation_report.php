<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../ip_model/farmercategory_db_oracle.php");
    include_once("../info/routine.php");
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class villageplantation extends swappreport
{
    public $centrecode;
    public $fromdate;
    public $todate;
    public $farmercategorycode;

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
        $this->group();
    }

    function reportheader()
    {
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->newrow(3);
        $this->textbox('गाववार ऊसनोंद',180,10,'S','C',1,'siddhanta',13);
        $this->newrow(-3);
        $this->newrow(7);
        $title='';
        if ($this->fromdate!='' and $this->todate!='')
        {
            $title=$title.' हंगाम: '.$_SESSION['yearperiodcode'].' दिनांक '.DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y').' पासून ते दिनांक '.DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y').' पर्यंत';
        }
        if ($this->farmercategorycode!='' and $this->farmercategorycode!='0')
        {
            $farmercategorycode1=new farmercategory($this->connection);
            $farmercategorycode1->farmercategorycode=$this->farmercategorycode;
            $farmercategorycode1->fetch();
            $title=$title.'  सभासदत्व: '.$farmercategorycode1->farmercategorynameuni;
        }
        $this->textbox($title,180,10,'S','C',1,'siddhanta',12);
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
        $this->hline(10,195,$this->liney,'C');
        $this->setfieldwidth(40,10);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('गाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('उ.संख्या',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
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

        $this->setfieldwidth(20); 
        $this->textbox('मा.व.सुरू',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('एकूण',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->newrow(9);
        $this->hline(10,195,$this->liney-2,'C');

    }

    function group()
    {
        $cond = '1=1';
        if ($this->centrecode!='0' and $this->centrecode!='')
        {
            $cond = $cond.' and centrecode='.$this->centrecode;
        }

            $group_query_1 ="select centrecode,centrenameuni
            from centre
            where {$cond}";

            $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->groupheader_1($group_row_1['CENTRECODE'],$group_row_1['CENTRENAMEUNI']);

            $this->detail($group_row_1['CENTRECODE']);

            $this->groupfooter_1($group_row_1['CENTRECODE']);
        }
        $this->reportfooter();
    }

    function groupheader_1($centrecode,$centrenameuni)
    {
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        $this->setfieldwidth(80,10);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox('सेंटर: '.$centrenameuni,80,10,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(105);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->newrow(7);
        $this->hline(10,195,$this->liney-2,'C'); 
    }

    function detail($centrecode)
    { 
        $cond = ' and 1=1 and seasoncode='.$_SESSION['yearperiodcode'];
        if ($this->fromdate!='' and $this->todate!='')
        {
            $cond = $cond." and p.plantationdate>='".$this->fromdate."'
            and p.plantationdate<='".$this->todate."'";
        }
        if ($centrecode!='0' and $centrecode!='')
        {
            $cond = $cond.' and v.centrecode='.$centrecode;
        }
        if ($this->farmercategorycode!='0' and $this->farmercategorycode!='')
        {
            $cond = $cond.' and farmercategorycode='.$this->farmercategorycode;
        }
        
            $detail_query_1 = "SELECT vvv.villagecode,vvv.villagenameuni,sum(cnt) as cnt
            ,sum(preseasonal) preseasonal
            ,sum(suru) suru,sum(adsali) adsali,sum(khodwa) khodwa,sum(total) total
            from (
            SELECT v.villagecode,0 as cnt
            ,case when p.plantationhangamcode =1 then p.area else 0 end as preseasonal
            ,case when p.plantationhangamcode =2 then p.area else 0 end as suru
            ,case when p.plantationhangamcode =3 then p.area else 0 end as adsali
            ,case when p.plantationhangamcode =4 then p.area else 0 end as khodwa
            ,case when p.plantationhangamcode =5 then p.area else 0 end as prsuru
            ,p.area as total
            FROM plantationheader p,village v,farmer f
            where p.villagecode=v.villagecode and p.farmercode=f.farmercode
            {$cond}
            union all
            select t.villagecode,count(*) as cnt 
            ,0 as preseasonal
            ,0 as suru
            ,0 as adsali
            ,0 as khodwa
            ,0 as prsuru
            ,0 as total
            from 
            (select v.villagecode,v.villagenameuni,pp.farmercode 
            from plantationheader pp,village v,farmer f
            where pp.villagecode=v.villagecode and pp.farmercode=f.farmercode
            {$cond}
            group by v.villagecode,v.villagenameuni,pp.farmercode)t 
            group by t.villagecode,t.villagenameuni
            )ttt,village vvv
            where ttt.villagecode=vvv.villagecode
            group by vvv.villagecode,vvv.villagenameuni
            order by vvv.villagecode
            ";
        
        $detail_result_1 = oci_parse($this->connection, $detail_query_1);
        $r = oci_execute($detail_result_1);
        while ($detail_row_1 = oci_fetch_array($detail_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->setfieldwidth(40,10);
            $this->vline($this->liney-2,$this->liney+5,$this->x);
            $this->textbox($detail_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($detail_row_1['CNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($detail_row_1['ADSALI'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($detail_row_1['PRESEASONAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20); 
            $this->textbox($detail_row_1['SURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($detail_row_1['KHODWA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(20);
            $this->textbox($detail_row_1['PRSURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->setfieldwidth(25);
            $this->textbox($detail_row_1['TOTAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            if ($this->isnewpage(10))
            {
                $this->newrow();
                $this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            else
            {
                $this->newrow();
                $this->hline(10,195,$this->liney-2,'C'); 
            }
        }
    }
    
    function groupfooter_1($centrecode)
    {
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        
        $cond = ' and 1=1 and seasoncode='.$_SESSION['yearperiodcode'];
        if ($this->fromdate!='' and $this->todate!='')
        {
            $cond = $cond." and p.plantationdate>='".$this->fromdate."'
            and p.plantationdate<='".$this->todate."'";
        }
        if ($centrecode!='0' and $centrecode!='')
        {
            $cond = $cond.' and v.centrecode='.$centrecode;
        }
        if ($this->farmercategorycode!='0' and $this->farmercategorycode!='')
        {
            $cond = $cond.' and farmercategorycode='.$this->farmercategorycode;
        }

            $groupfooter_query_1 = "SELECT sum(cnt) as cnt
            ,sum(preseasonal) preseasonal
            ,sum(suru) suru,sum(adsali) adsali,sum(khodwa) khodwa,sum(total) total
            from (
            SELECT v.villagecode,0 as cnt
            ,case when p.plantationhangamcode =1 then p.area else 0 end as preseasonal
            ,case when p.plantationhangamcode =2 then p.area else 0 end as suru
            ,case when p.plantationhangamcode =3 then p.area else 0 end as adsali
            ,case when p.plantationhangamcode =4 then p.area else 0 end as khodwa
            ,case when p.plantationhangamcode =5 then p.area else 0 end as prsuru
            ,p.area as total
            FROM plantationheader p,village v,farmer f
            where p.villagecode=v.villagecode and p.farmercode=f.farmercode
             {$cond}
            union all
            select t.villagecode,count(*) as cnt 
            ,0 as preseasonal
            ,0 as suru
            ,0 as adsali
            ,0 as khodwa
            ,0 as prsuru
            ,0 as total
            from 
            (select v.villagecode,v.villagenameuni,pp.farmercode 
            from plantationheader pp,village v,farmer f
            where pp.villagecode=v.villagecode and pp.farmercode=f.farmercode
             {$cond}
            group by v.villagecode,v.villagenameuni,pp.farmercode)t 
            group by t.villagecode,t.villagenameuni
            )ttt,village vvv
            where ttt.villagecode=vvv.villagecode
            
            ";
        $groupfooter_result_1 = oci_parse($this->connection, $groupfooter_query_1);
        $r = oci_execute($groupfooter_result_1);
        if ($groupfooter_row_1 = oci_fetch_array($groupfooter_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->setfieldwidth(40,10);
            $this->vline($this->liney-2,$this->liney+5,$this->x);
            $this->textbox('सेंटर एकूण',$this->w,$this->x,'S','R',1,'Siddhanta',10,'','','','B');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($groupfooter_row_1['CNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($groupfooter_row_1['ADSALI'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($groupfooter_row_1['PRESEASONAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20); 
            $this->textbox($groupfooter_row_1['SURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($groupfooter_row_1['KHODWA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(20);
            $this->textbox($groupfooter_row_1['PRSURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->setfieldwidth(25);
            $this->textbox($groupfooter_row_1['TOTAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        }

        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
        }
    }
    
    function pagefooter($islastpage=false)
    {
    }

    function reportfooter()
    {
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }

        $cond = ' and 1=1 and seasoncode='.$_SESSION['yearperiodcode'];
        if ($this->fromdate!='' and $this->todate!='')
        {
            $cond = $cond." and p.plantationdate>='".$this->fromdate."'
            and p.plantationdate<='".$this->todate."'";
        }
        if ($this->centrecode!='0' and $this->centrecode!='')
        {
            $cond = $cond.' and v.centrecode='.$this->centrecode;
        }
        if ($this->farmercategorycode!='0' and $this->farmercategorycode!='')
        {
            $cond = $cond.' and farmercategorycode='.$this->farmercategorycode;
        }
        
            $reportfooter_query_1 = "SELECT sum(cnt) as cnt
            ,sum(preseasonal) preseasonal
            ,sum(suru) suru,sum(adsali) adsali,sum(khodwa) khodwa,sum(total) total
            from (
            SELECT v.villagecode,0 as cnt
            ,case when p.plantationhangamcode =1 then p.area else 0 end as preseasonal
            ,case when p.plantationhangamcode =2 then p.area else 0 end as suru
            ,case when p.plantationhangamcode =3 then p.area else 0 end as adsali
            ,case when p.plantationhangamcode =4 then p.area else 0 end as khodwa
            ,case when p.plantationhangamcode =5 then p.area else 0 end as prsuru
            ,p.area as total
            FROM plantationheader p,village v,farmer f
            where p.villagecode=v.villagecode and p.farmercode=f.farmercode
            {$cond}
            union all
            select t.villagecode,count(*) as cnt 
            ,0 as preseasonal
            ,0 as suru
            ,0 as adsali
            ,0 as khodwa
            ,0 as prsuru
            ,0 as total
            from 
            (select v.villagecode,v.villagenameuni,pp.farmercode 
            from plantationheader pp,village v,farmer f
            where pp.villagecode=v.villagecode and pp.farmercode=f.farmercode
            {$cond}
            group by v.villagecode,v.villagenameuni,pp.farmercode)t 
            group by t.villagecode,t.villagenameuni
            )ttt,village vvv
            where ttt.villagecode=vvv.villagecode
            ";
        
        $reportfooter_result_1 = oci_parse($this->connection, $reportfooter_query_1);
        $r = oci_execute($reportfooter_result_1);
        if ($reportfooter_row_1 = oci_fetch_array($reportfooter_result_1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->setfieldwidth(40,10);
            $this->vline($this->liney-2,$this->liney+5,$this->x);
            $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','R',1,'Siddhanta',10,'','','','B');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($reportfooter_row_1['CNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($reportfooter_row_1['ADSALI'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($reportfooter_row_1['PRESEASONAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20); 
            $this->textbox($reportfooter_row_1['SURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($reportfooter_row_1['KHODWA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(20);
            $this->textbox($reportfooter_row_1['PRSURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->setfieldwidth(25);
            $this->textbox($reportfooter_row_1['TOTAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
        }
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