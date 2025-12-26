<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a3_l.php");
    include_once("../info/routine.php");
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class varietymonthlysummarynew extends reportbox
{
    public $divisioncode;
    public $fromdate;
    public $todate;
    public $lowyr;
    public $curyr;
    public $divisionname;
    public $plantationcategoryname;
    public $hangamsummary;
    public $varietysummary;
    public $summary;
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
        $this->lowyr=($_SESSION['yearperiodcode']-20002)%100;
        $this->curyr=($_SESSION['yearperiodcode']-10001)%100;
        $this->grossrowtotal=0;
        $this->newpage(True); 
        $this->group();
        $this->reportfooter();
    }

    function reportheader()
    {
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->textbox('ऊसजातवार महिनावार ऊसनोंद गोषवारा',390,10,'S','C',1,'siddhanta',13);
        $this->newrow(7);
        if ($this->fromdate!='' and $this->todate!='')
        {
            $this->textbox('हंगाम: '.$_SESSION['yearperiodcode'].' दिनांक '.DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y').' पासून ते दिनांक '.DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y').' पर्यंत'.' क्षेत्र - '.$this->divisionname.' लागण/खोडवा - '.$this->plantationcategoryname,390,10,'S','C',1,'siddhanta',12);
        }
        else
        {
            $this->textbox('हंगाम: '.$_SESSION['yearperiodcode'].' क्षेत्र - '.$this->divisionname.' लागण/खोडवा - '.$this->plantationcategoryname,390,10,'S','C',1,'siddhanta',12);
        }

        $this->summary[$this->lowyr.'06_AREA']=0;
        $this->summary[$this->lowyr.'06_NONMEMBER']=0;
        $this->summary[$this->lowyr.'06_GATECANE']=0;

        $this->summary[$this->lowyr.'07_AREA']=0;
        $this->summary[$this->lowyr.'07_NONMEMBER']=0;
        $this->summary[$this->lowyr.'07_GATECANE']=0;

        $this->summary[$this->lowyr.'08_AREA']=0;
        $this->summary[$this->lowyr.'08_NONMEMBER']=0;
        $this->summary[$this->lowyr.'08_GATECANE']=0;

        $this->summary[$this->lowyr.'09_AREA']=0;
        $this->summary[$this->lowyr.'09_NONMEMBER']=0;
        $this->summary[$this->lowyr.'09_GATECANE']=0;

        $this->summary[$this->lowyr.'10_AREA']=0;
        $this->summary[$this->lowyr.'10_NONMEMBER']=0;
        $this->summary[$this->lowyr.'10_GATECANE']=0;

        $this->summary[$this->lowyr.'11_AREA']=0;
        $this->summary[$this->lowyr.'11_NONMEMBER']=0;
        $this->summary[$this->lowyr.'11_GATECANE']=0;

        $this->summary[$this->lowyr.'12_AREA']=0;
        $this->summary[$this->lowyr.'12_NONMEMBER']=0;
        $this->summary[$this->lowyr.'12_GATECANE']=0;

        $this->summary[$this->curyr.'01_AREA']=0;
        $this->summary[$this->curyr.'01_NONMEMBER']=0;
        $this->summary[$this->curyr.'01_GATECANE']=0;

        $this->summary[$this->curyr.'02_AREA']=0;
        $this->summary[$this->curyr.'02_NONMEMBER']=0;
        $this->summary[$this->curyr.'02_GATECANE']=0;

        $this->summary[$this->curyr.'03_AREA']=0;
        $this->summary[$this->curyr.'03_NONMEMBER']=0;
        $this->summary[$this->curyr.'03_GATECANE']=0;

        $this->summary[$this->curyr.'04_AREA']=0;
        $this->summary[$this->curyr.'04_NONMEMBER']=0;
        $this->summary[$this->curyr.'04_GATECANE']=0;

        $this->summary[$this->curyr.'05_AREA']=0;
        $this->summary[$this->curyr.'05_NONMEMBER']=0;
        $this->summary[$this->curyr.'05_GATECANE']=0;

        $this->summary[$this->curyr.'06_AREA']=0;
        $this->summary[$this->curyr.'06_NONMEMBER']=0;
        $this->summary[$this->curyr.'06_GATECANE']=0;

        $this->summary['ROWTOTAL']=0;
        

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
        $this->pdf->SetFont('siddhanta', '', 12, '', true);
        $this->newrow(7);
        $this->hline(10,395,$this->liney,'C');
        $this->setfieldwidth(41,10);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('ऊसजात',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox("<=06-".$this->lowyr,$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',12,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox("07-".$this->lowyr,$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',12,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24); 
        $this->textbox("08-".$this->lowyr,$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',12,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox("09-".$this->lowyr,$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',12,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24); 
        $this->textbox("10-".$this->lowyr,$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',12,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox("11-".$this->lowyr,$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',12,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox("12-".$this->lowyr,$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',12,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox("01-".$this->curyr,$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',12,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox("02-".$this->curyr,$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',12,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox("03-".$this->curyr,$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',12,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox("04-".$this->curyr,$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',12,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox("05-".$this->curyr,$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',12,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox(">=06-".$this->curyr,$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',12,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);


        $this->setfieldwidth(32);
        $this->textbox('एकूण',$this->w,$this->x,'S','C',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->newrow(9);
        $this->hline(10,395,$this->liney-2,'C');

    }
    function group()
    { 
        $this->totalgroupcount=1;
        $cond='1=1';
        if ($this->divisioncode!=0)
        {
            $cond=$cond." and cl.divisioncode=".$this->divisioncode;
        }
        if ($this->plantationcategorycode!=0)
        {
            $cond=$cond." and p.plantationcategorycode=".$this->plantationcategorycode;
        }
        if ($this->fromdate!='' and $this->todate!='')
        {
            $cond=$cond." and p.plantationdate>='".$this->fromdate."' 
            and p.plantationdate<='".$this->todate."'";
        }
            $group_query_1 ="select * from (SELECT varietycode,varietynameuni
            ,seasoncode,case when to_number(mth)<= to_number(to_char(mod(seasoncode-20002,100))||'06') then
            to_char(mod(seasoncode-20002,100))||'06' 
            when to_number(mth)>= to_number(to_char(mod(seasoncode-10001,100))||'06') then
            to_char(mod(seasoncode-10001,100))||'06'
            else
            mth 
            end mth,sum(area) area
            from (
            SELECT p.seasoncode,v.varietycode,v.varietynameuni
            ,to_char(p.plantationdate,'YYMM') as mth,area
                        ,case when c.farmercategorycode =1 then p.area else 0 end as memberf
                        ,case when c.farmercategorycode =2 then p.area else 0 end as nonmember
                        ,case when c.farmercategorycode =3 then p.area else 0 end as gatecane
            FROM plantationheader p,variety v,plantationhangam h
            ,farmer f,farmercategory c,village v,circle cl
            where p.varietycode=v.varietycode 
            and p.plantationhangamcode=h.plantationhangamcode
            and p.farmercode=f.farmercode
            and f.farmercategorycode=c.farmercategorycode
            and p.villagecode=v.villagecode
            and v.circlecode=cl.circlecode
            and seasoncode=".$_SESSION['yearperiodcode']."
                and {$cond}
            )
            group by seasoncode,varietycode,varietynameuni
            ,mth)
            pivot
            (
                sum(area) as area
                for mth 
                in (".$this->lowyr."06,".$this->lowyr."07,".$this->lowyr."08
                ,".$this->lowyr."09,".$this->lowyr."10,".$this->lowyr."11
                ,".$this->lowyr."12,".$this->curyr."01,".$this->curyr."02
                ,".$this->curyr."03,".$this->curyr."04,".$this->curyr."05,".$this->curyr."06)
            )
            order by seasoncode,varietycode,varietynameuni";
        
            $group_result_1 = oci_parse($this->connection, $group_query_1);
            $r = oci_execute($group_result_1);
            while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
            {
                $this->grouptrigger($group_row_1,$last_row);
                $this->detail_1($group_row_1);
                $last_row=$group_row_1;
            }
            $this->grouptrigger($group_row_1,$last_row,'E');
        //$this->reportfooter();
    }
    
    function groupheader_1(&$group_row_1)
    {
        $this->setfieldwidth(41,10);
        $this->vline($this->liney-2,$this->liney+7,$this->x);
        $this->textbox($group_row_1['VARIETYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->varietysummary[$this->lowyr.'06_AREA']=0;

        $this->varietysummary[$this->lowyr.'07_AREA']=0;

        $this->varietysummary[$this->lowyr.'08_AREA']=0;

        $this->varietysummary[$this->lowyr.'09_AREA']=0;

        $this->varietysummary[$this->lowyr.'10_AREA']=0;

        $this->varietysummary[$this->lowyr.'11_AREA']=0;

        $this->varietysummary[$this->lowyr.'12_AREA']=0;

        $this->varietysummary[$this->curyr.'01_AREA']=0;

        $this->varietysummary[$this->curyr.'02_AREA']=0;

        $this->varietysummary[$this->curyr.'03_AREA']=0;

        $this->varietysummary[$this->curyr.'04_AREA']=0;

        $this->varietysummary[$this->curyr.'05_AREA']=0;

        $this->varietysummary[$this->curyr.'06_AREA']=0;

        $this->varietysummary['ROWTOTAL']=0;

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
    function groupheader_5(&$group_row_1)
    {
    }
    function groupheader_6(&$group_row_1)
    {
    }
    function groupheader_7(&$group_row_1)
    {
    }
    function detail_1($group_row_1)
    { 
        $this->setfieldwidth(41,10);
            $this->vline($this->liney-2,$this->liney+7,$this->x);
            //$this->textbox($group_row_1['VARIETYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
            $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
    
            //$this->setfieldwidth(20);
            //$this->textbox($group_row_1['PLANTATIONHANGAMNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
            $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
            
            //$this->setfieldwidth(41);
            //$this->textbox('सभासद',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
            //$this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

            $this->setfieldwidth(24);
            $this->textbox($this->numformat($group_row_1[$this->lowyr.'06_AREA'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
    
            $this->setfieldwidth(24);
            $this->textbox($this->numformat($group_row_1[$this->lowyr.'07_AREA'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

            $this->setfieldwidth(24);
            $this->textbox($this->numformat($group_row_1[$this->lowyr.'08_AREA'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

            $this->setfieldwidth(24);
            $this->textbox($this->numformat($group_row_1[$this->lowyr.'09_AREA'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
    
            $this->setfieldwidth(24);
            $this->textbox($this->numformat($group_row_1[$this->lowyr.'10_AREA'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

            $this->setfieldwidth(24);
            $this->textbox($this->numformat($group_row_1[$this->lowyr.'11_AREA'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
            
            $this->setfieldwidth(24);
            $this->textbox($this->numformat($group_row_1[$this->lowyr.'12_AREA'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

            $this->setfieldwidth(24);
            $this->textbox($this->numformat($group_row_1[$this->curyr.'01_AREA'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

            $this->setfieldwidth(24);
            $this->textbox($this->numformat($group_row_1[$this->curyr.'02_AREA'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

            $this->setfieldwidth(24);
            $this->textbox($this->numformat($group_row_1[$this->curyr.'03_AREA'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

            $this->setfieldwidth(24);
            $this->textbox($this->numformat($group_row_1[$this->curyr.'04_AREA'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

            $this->setfieldwidth(24);
            $this->textbox($this->numformat($group_row_1[$this->curyr.'05_AREA'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
            
            $this->setfieldwidth(24);
            $this->textbox($this->numformat($group_row_1[$this->curyr.'06_AREA'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
            $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
            $membertotal=$this->numifnulltozero($group_row_1[$this->lowyr.'06_AREA'])
            +$this->numifnulltozero($group_row_1[$this->lowyr.'07_AREA'])
            +$this->numifnulltozero($group_row_1[$this->lowyr.'08_AREA'])
            +$this->numifnulltozero($group_row_1[$this->lowyr.'09_AREA'])
            +$this->numifnulltozero($group_row_1[$this->lowyr.'10_AREA'])
            +$this->numifnulltozero($group_row_1[$this->lowyr.'11_AREA'])
            +$this->numifnulltozero($group_row_1[$this->lowyr.'12_AREA'])
            +$this->numifnulltozero($group_row_1[$this->curyr.'01_AREA'])
            +$this->numifnulltozero($group_row_1[$this->curyr.'02_AREA'])
            +$this->numifnulltozero($group_row_1[$this->curyr.'03_AREA'])
            +$this->numifnulltozero($group_row_1[$this->curyr.'04_AREA'])
            +$this->numifnulltozero($group_row_1[$this->curyr.'05_AREA'])
            +$this->numifnulltozero($group_row_1[$this->curyr.'06_AREA']);
            $this->setfieldwidth(32);
            $this->textbox($this->numformat($membertotal,2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
            $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
    

            if ($this->isnewpage(10))
            {
                $this->newrow();
                $this->hline(10,395,$this->liney,'C'); 
                $this->newpage(True);
            }
            else
            {
                $this->newrow();
                $this->hline(10,395,$this->liney-2,'D'); 
            }


            $this->rowtotal=$membertotal+$nonmembertotal+$gatecanetotal;
            
            $this->hangamsummary[$this->lowyr.'06_AREA']+=$group_row_1[$this->lowyr.'06_AREA'];
            $this->hangamsummary[$this->lowyr.'06_NONMEMBER']+=$group_row_1[$this->lowyr.'06_NONMEMBER'];
            $this->hangamsummary[$this->lowyr.'06_GATECANE']+=$group_row_1[$this->lowyr.'06_GATECANE'];
    
            $this->hangamsummary[$this->lowyr.'07_AREA']+=$group_row_1[$this->lowyr.'07_AREA'];
            $this->hangamsummary[$this->lowyr.'07_NONMEMBER']+=$group_row_1[$this->lowyr.'07_NONMEMBER'];
            $this->hangamsummary[$this->lowyr.'07_GATECANE']+=$group_row_1[$this->lowyr.'07_GATECANE'];
    
            $this->hangamsummary[$this->lowyr.'08_AREA']+=$group_row_1[$this->lowyr.'08_AREA'];
            $this->hangamsummary[$this->lowyr.'08_NONMEMBER']+=$group_row_1[$this->lowyr.'08_NONMEMBER'];
            $this->hangamsummary[$this->lowyr.'08_GATECANE']+=$group_row_1[$this->lowyr.'08_GATECANE'];
    
            $this->hangamsummary[$this->lowyr.'09_AREA']+=$group_row_1[$this->lowyr.'09_AREA'];
            $this->hangamsummary[$this->lowyr.'09_NONMEMBER']+=$group_row_1[$this->lowyr.'09_NONMEMBER'];
            $this->hangamsummary[$this->lowyr.'09_GATECANE']+=$group_row_1[$this->lowyr.'09_GATECANE'];
    
            $this->hangamsummary[$this->lowyr.'10_AREA']+=$group_row_1[$this->lowyr.'10_AREA'];
            $this->hangamsummary[$this->lowyr.'10_NONMEMBER']+=$group_row_1[$this->lowyr.'10_NONMEMBER'];
            $this->hangamsummary[$this->lowyr.'10_GATECANE']+=$group_row_1[$this->lowyr.'10_GATECANE'];
    
            $this->hangamsummary[$this->lowyr.'11_AREA']+=$group_row_1[$this->lowyr.'11_AREA'];
            $this->hangamsummary[$this->lowyr.'11_NONMEMBER']+=$group_row_1[$this->lowyr.'11_NONMEMBER'];
            $this->hangamsummary[$this->lowyr.'11_GATECANE']+=$group_row_1[$this->lowyr.'11_GATECANE'];
    
            $this->hangamsummary[$this->lowyr.'12_AREA']+=$group_row_1[$this->lowyr.'12_AREA'];
            $this->hangamsummary[$this->lowyr.'12_NONMEMBER']+=$group_row_1[$this->lowyr.'12_NONMEMBER'];
            $this->hangamsummary[$this->lowyr.'12_GATECANE']+=$group_row_1[$this->lowyr.'12_GATECANE'];
    
            $this->hangamsummary[$this->curyr.'01_AREA']+=$group_row_1[$this->curyr.'01_AREA'];
            $this->hangamsummary[$this->curyr.'01_NONMEMBER']+=$group_row_1[$this->curyr.'01_NONMEMBER'];
            $this->hangamsummary[$this->curyr.'01_GATECANE']+=$group_row_1[$this->curyr.'01_GATECANE'];
    
            $this->hangamsummary[$this->curyr.'02_AREA']+=$group_row_1[$this->curyr.'02_AREA'];
            $this->hangamsummary[$this->curyr.'02_NONMEMBER']+=$group_row_1[$this->curyr.'02_NONMEMBER'];
            $this->hangamsummary[$this->curyr.'02_GATECANE']+=$group_row_1[$this->curyr.'02_GATECANE'];
    
            $this->hangamsummary[$this->curyr.'03_AREA']+=$group_row_1[$this->curyr.'03_AREA'];
            $this->hangamsummary[$this->curyr.'03_NONMEMBER']+=$group_row_1[$this->curyr.'03_NONMEMBER'];
            $this->hangamsummary[$this->curyr.'03_GATECANE']+=$group_row_1[$this->curyr.'03_GATECANE'];
    
            $this->hangamsummary[$this->curyr.'04_AREA']+=$group_row_1[$this->curyr.'04_AREA'];
            $this->hangamsummary[$this->curyr.'04_NONMEMBER']+=$group_row_1[$this->curyr.'04_NONMEMBER'];
            $this->hangamsummary[$this->curyr.'04_GATECANE']+=$group_row_1[$this->curyr.'04_GATECANE'];
    
            $this->hangamsummary[$this->curyr.'05_AREA']+=$group_row_1[$this->curyr.'05_AREA'];
            $this->hangamsummary[$this->curyr.'05_NONMEMBER']+=$group_row_1[$this->curyr.'05_NONMEMBER'];
            $this->hangamsummary[$this->curyr.'05_GATECANE']+=$group_row_1[$this->curyr.'05_GATECANE'];
    
            $this->hangamsummary[$this->curyr.'06_AREA']+=$group_row_1[$this->curyr.'06_AREA'];
            $this->hangamsummary[$this->curyr.'06_NONMEMBER']+=$group_row_1[$this->curyr.'06_NONMEMBER'];
            $this->hangamsummary[$this->curyr.'06_GATECANE']+=$group_row_1[$this->curyr.'06_GATECANE'];
            
            $this->hangamsummary['ROWTOTAL']+=$this->rowtotal;

            $this->varietysummary[$this->lowyr.'06_AREA']+=$group_row_1[$this->lowyr.'06_AREA'];
            $this->varietysummary[$this->lowyr.'06_NONMEMBER']+=$group_row_1[$this->lowyr.'06_NONMEMBER'];
            $this->varietysummary[$this->lowyr.'06_GATECANE']+=$group_row_1[$this->lowyr.'06_GATECANE'];
    
            $this->varietysummary[$this->lowyr.'07_AREA']+=$group_row_1[$this->lowyr.'07_AREA'];
            $this->varietysummary[$this->lowyr.'07_NONMEMBER']+=$group_row_1[$this->lowyr.'07_NONMEMBER'];
            $this->varietysummary[$this->lowyr.'07_GATECANE']+=$group_row_1[$this->lowyr.'07_GATECANE'];
    
            $this->varietysummary[$this->lowyr.'08_AREA']+=$group_row_1[$this->lowyr.'08_AREA'];
            $this->varietysummary[$this->lowyr.'08_NONMEMBER']+=$group_row_1[$this->lowyr.'08_NONMEMBER'];
            $this->varietysummary[$this->lowyr.'08_GATECANE']+=$group_row_1[$this->lowyr.'08_GATECANE'];
    
            $this->varietysummary[$this->lowyr.'09_AREA']+=$group_row_1[$this->lowyr.'09_AREA'];
            $this->varietysummary[$this->lowyr.'09_NONMEMBER']+=$group_row_1[$this->lowyr.'09_NONMEMBER'];
            $this->varietysummary[$this->lowyr.'09_GATECANE']+=$group_row_1[$this->lowyr.'09_GATECANE'];
    
            $this->varietysummary[$this->lowyr.'10_AREA']+=$group_row_1[$this->lowyr.'10_AREA'];
            $this->varietysummary[$this->lowyr.'10_NONMEMBER']+=$group_row_1[$this->lowyr.'10_NONMEMBER'];
            $this->varietysummary[$this->lowyr.'10_GATECANE']+=$group_row_1[$this->lowyr.'10_GATECANE'];
    
            $this->varietysummary[$this->lowyr.'11_AREA']+=$group_row_1[$this->lowyr.'11_AREA'];
            $this->varietysummary[$this->lowyr.'11_NONMEMBER']+=$group_row_1[$this->lowyr.'11_NONMEMBER'];
            $this->varietysummary[$this->lowyr.'11_GATECANE']+=$group_row_1[$this->lowyr.'11_GATECANE'];
    
            $this->varietysummary[$this->lowyr.'12_AREA']+=$group_row_1[$this->lowyr.'12_AREA'];
            $this->varietysummary[$this->lowyr.'12_NONMEMBER']+=$group_row_1[$this->lowyr.'12_NONMEMBER'];
            $this->varietysummary[$this->lowyr.'12_GATECANE']+=$group_row_1[$this->lowyr.'12_GATECANE'];
    
            $this->varietysummary[$this->curyr.'01_AREA']+=$group_row_1[$this->curyr.'01_AREA'];
            $this->varietysummary[$this->curyr.'01_NONMEMBER']+=$group_row_1[$this->curyr.'01_NONMEMBER'];
            $this->varietysummary[$this->curyr.'01_GATECANE']+=$group_row_1[$this->curyr.'01_GATECANE'];
    
            $this->varietysummary[$this->curyr.'02_AREA']+=$group_row_1[$this->curyr.'02_AREA'];
            $this->varietysummary[$this->curyr.'02_NONMEMBER']+=$group_row_1[$this->curyr.'02_NONMEMBER'];
            $this->varietysummary[$this->curyr.'02_GATECANE']+=$group_row_1[$this->curyr.'02_GATECANE'];
    
            $this->varietysummary[$this->curyr.'03_AREA']+=$group_row_1[$this->curyr.'03_AREA'];
            $this->varietysummary[$this->curyr.'03_NONMEMBER']+=$group_row_1[$this->curyr.'03_NONMEMBER'];
            $this->varietysummary[$this->curyr.'03_GATECANE']+=$group_row_1[$this->curyr.'03_GATECANE'];
    
            $this->varietysummary[$this->curyr.'04_AREA']+=$group_row_1[$this->curyr.'04_AREA'];
            $this->varietysummary[$this->curyr.'04_NONMEMBER']+=$group_row_1[$this->curyr.'04_NONMEMBER'];
            $this->varietysummary[$this->curyr.'04_GATECANE']+=$group_row_1[$this->curyr.'04_GATECANE'];
    
            $this->varietysummary[$this->curyr.'05_AREA']+=$group_row_1[$this->curyr.'05_AREA'];
            $this->varietysummary[$this->curyr.'05_NONMEMBER']+=$group_row_1[$this->curyr.'05_NONMEMBER'];
            $this->varietysummary[$this->curyr.'05_GATECANE']+=$group_row_1[$this->curyr.'05_GATECANE'];
    
            $this->varietysummary[$this->curyr.'06_AREA']+=$group_row_1[$this->curyr.'06_AREA'];
            $this->varietysummary[$this->curyr.'06_NONMEMBER']+=$group_row_1[$this->curyr.'06_NONMEMBER'];
            $this->varietysummary[$this->curyr.'06_GATECANE']+=$group_row_1[$this->curyr.'06_GATECANE'];
            $this->varietysummary['ROWTOTAL']+=$this->rowtotal;

            $this->summary[$this->lowyr.'06_AREA']+=$group_row_1[$this->lowyr.'06_AREA'];
            $this->summary[$this->lowyr.'06_NONMEMBER']+=$group_row_1[$this->lowyr.'06_NONMEMBER'];
            $this->summary[$this->lowyr.'06_GATECANE']+=$group_row_1[$this->lowyr.'06_GATECANE'];
    
            $this->summary[$this->lowyr.'07_AREA']+=$group_row_1[$this->lowyr.'07_AREA'];
            $this->summary[$this->lowyr.'07_NONMEMBER']+=$group_row_1[$this->lowyr.'07_NONMEMBER'];
            $this->summary[$this->lowyr.'07_GATECANE']+=$group_row_1[$this->lowyr.'07_GATECANE'];
    
            $this->summary[$this->lowyr.'08_AREA']+=$group_row_1[$this->lowyr.'08_AREA'];
            $this->summary[$this->lowyr.'08_NONMEMBER']+=$group_row_1[$this->lowyr.'08_NONMEMBER'];
            $this->summary[$this->lowyr.'08_GATECANE']+=$group_row_1[$this->lowyr.'08_GATECANE'];
    
            $this->summary[$this->lowyr.'09_AREA']+=$group_row_1[$this->lowyr.'09_AREA'];
            $this->summary[$this->lowyr.'09_NONMEMBER']+=$group_row_1[$this->lowyr.'09_NONMEMBER'];
            $this->summary[$this->lowyr.'09_GATECANE']+=$group_row_1[$this->lowyr.'09_GATECANE'];
    
            $this->summary[$this->lowyr.'10_AREA']+=$group_row_1[$this->lowyr.'10_AREA'];
            $this->summary[$this->lowyr.'10_NONMEMBER']+=$group_row_1[$this->lowyr.'10_NONMEMBER'];
            $this->summary[$this->lowyr.'10_GATECANE']+=$group_row_1[$this->lowyr.'10_GATECANE'];
    
            $this->summary[$this->lowyr.'11_AREA']+=$group_row_1[$this->lowyr.'11_AREA'];
            $this->summary[$this->lowyr.'11_NONMEMBER']+=$group_row_1[$this->lowyr.'11_NONMEMBER'];
            $this->summary[$this->lowyr.'11_GATECANE']+=$group_row_1[$this->lowyr.'11_GATECANE'];
    
            $this->summary[$this->lowyr.'12_AREA']+=$group_row_1[$this->lowyr.'12_AREA'];
            $this->summary[$this->lowyr.'12_NONMEMBER']+=$group_row_1[$this->lowyr.'12_NONMEMBER'];
            $this->summary[$this->lowyr.'12_GATECANE']+=$group_row_1[$this->lowyr.'12_GATECANE'];
    
            $this->summary[$this->curyr.'01_AREA']+=$group_row_1[$this->curyr.'01_AREA'];
            $this->summary[$this->curyr.'01_NONMEMBER']+=$group_row_1[$this->curyr.'01_NONMEMBER'];
            $this->summary[$this->curyr.'01_GATECANE']+=$group_row_1[$this->curyr.'01_GATECANE'];
    
            $this->summary[$this->curyr.'02_AREA']+=$group_row_1[$this->curyr.'02_AREA'];
            $this->summary[$this->curyr.'02_NONMEMBER']+=$group_row_1[$this->curyr.'02_NONMEMBER'];
            $this->summary[$this->curyr.'02_GATECANE']+=$group_row_1[$this->curyr.'02_GATECANE'];
    
            $this->summary[$this->curyr.'03_AREA']+=$group_row_1[$this->curyr.'03_AREA'];
            $this->summary[$this->curyr.'03_NONMEMBER']+=$group_row_1[$this->curyr.'03_NONMEMBER'];
            $this->summary[$this->curyr.'03_GATECANE']+=$group_row_1[$this->curyr.'03_GATECANE'];
    
            $this->summary[$this->curyr.'04_AREA']+=$group_row_1[$this->curyr.'04_AREA'];
            $this->summary[$this->curyr.'04_NONMEMBER']+=$group_row_1[$this->curyr.'04_NONMEMBER'];
            $this->summary[$this->curyr.'04_GATECANE']+=$group_row_1[$this->curyr.'04_GATECANE'];
    
            $this->summary[$this->curyr.'05_AREA']+=$group_row_1[$this->curyr.'05_AREA'];
            $this->summary[$this->curyr.'05_NONMEMBER']+=$group_row_1[$this->curyr.'05_NONMEMBER'];
            $this->summary[$this->curyr.'05_GATECANE']+=$group_row_1[$this->curyr.'05_GATECANE'];
    
            $this->summary[$this->curyr.'06_AREA']+=$group_row_1[$this->curyr.'06_AREA'];
            $this->summary[$this->curyr.'06_NONMEMBER']+=$group_row_1[$this->curyr.'06_NONMEMBER'];
            $this->summary[$this->curyr.'06_GATECANE']+=$group_row_1[$this->curyr.'06_GATECANE'];

            $this->summary['ROWTOTAL']+=$this->rowtotal;
            
            /* if ($this->isnewpage(10))
            {
                $this->newrow();
                $this->hline(10,395,$this->liney,'C'); 
                $this->newpage(True);
            }
            else
            {
                $this->newrow(7);
                $this->hline(60,395,$this->liney-2,'C'); 
            } */

    }
    function groupfooter_1(&$group_row)
    {
/*         $this->hline(10,395,$this->liney-2,'C'); 
        $this->setfieldwidth(41,10);
        $this->vline($this->liney-7,$this->liney+7,$this->x);
        $this->textbox('एकूण',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-7,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        //$this->textbox('Total',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(41);
        //$this->textbox('सभासद',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->varietysummary[$this->lowyr.'06_AREA']+$this->varietysummary[$this->lowyr.'06_NONMEMBER']+$this->varietysummary[$this->lowyr.'06_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->varietysummary[$this->lowyr.'07_AREA']+$this->varietysummary[$this->lowyr.'07_NONMEMBER']+$this->varietysummary[$this->lowyr.'07_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->varietysummary[$this->lowyr.'08_AREA']+$this->varietysummary[$this->lowyr.'08_NONMEMBER']+$this->varietysummary[$this->lowyr.'08_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->varietysummary[$this->lowyr.'09_AREA']+$this->varietysummary[$this->lowyr.'09_NONMEMBER']+$this->varietysummary[$this->lowyr.'09_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->varietysummary[$this->lowyr.'10_AREA']+$this->varietysummary[$this->lowyr.'10_NONMEMBER']+$this->varietysummary[$this->lowyr.'10_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->varietysummary[$this->lowyr.'11_AREA']+$this->varietysummary[$this->lowyr.'11_NONMEMBER']+$this->varietysummary[$this->lowyr.'11_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->varietysummary[$this->lowyr.'12_AREA']+$this->varietysummary[$this->lowyr.'12_NONMEMBER']+$this->varietysummary[$this->lowyr.'12_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->varietysummary[$this->curyr.'01_AREA']+$this->varietysummary[$this->curyr.'01_NONMEMBER']+$this->varietysummary[$this->curyr.'01_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->varietysummary[$this->curyr.'02_AREA']+$this->varietysummary[$this->curyr.'02_NONMEMBER']+$this->varietysummary[$this->curyr.'02_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->varietysummary[$this->curyr.'03_AREA']+$this->varietysummary[$this->curyr.'03_NONMEMBER']+$this->varietysummary[$this->curyr.'03_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->varietysummary[$this->curyr.'04_AREA']+$this->varietysummary[$this->curyr.'04_NONMEMBER']+$this->varietysummary[$this->curyr.'04_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->varietysummary[$this->curyr.'05_AREA']+$this->varietysummary[$this->curyr.'05_NONMEMBER']+$this->varietysummary[$this->curyr.'05_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->varietysummary[$this->curyr.'06_AREA']+$this->varietysummary[$this->curyr.'06_NONMEMBER']+$this->varietysummary[$this->curyr.'06_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(32);
        $this->textbox($this->numformat($this->varietysummary['ROWTOTAL'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);


        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,395,$this->liney,'C'); 
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(10,395,$this->liney-2,'C'); 
        }
 */
    }
    function groupfooter_2(&$group_row)
    {
        $this->hline(10,395,$this->liney-2,'C');
        $this->setfieldwidth(41,10);
        $this->vline($this->liney-7,$this->liney+7,$this->x);
        //$this->textbox($group_row_1['VARIETYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        //$this->vline($this->liney-7,$this->liney+7,$this->x+$this->w);

        //$this->setfieldwidth(20);
        $this->textbox('एकूण',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
        
        //$this->setfieldwidth(41);
        //$this->textbox('सभासद',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        //$this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->hangamsummary[$this->lowyr.'06_AREA']+$this->hangamsummary[$this->lowyr.'06_NONMEMBER']+$this->hangamsummary[$this->lowyr.'06_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->hangamsummary[$this->lowyr.'07_AREA']+$this->hangamsummary[$this->lowyr.'07_NONMEMBER']+$this->hangamsummary[$this->lowyr.'07_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->hangamsummary[$this->lowyr.'08_AREA']+$this->hangamsummary[$this->lowyr.'08_NONMEMBER']+$this->hangamsummary[$this->lowyr.'08_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->hangamsummary[$this->lowyr.'09_AREA']+$this->hangamsummary[$this->lowyr.'09_NONMEMBER']+$this->hangamsummary[$this->lowyr.'09_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->hangamsummary[$this->lowyr.'10_AREA']+$this->hangamsummary[$this->lowyr.'10_NONMEMBER']+$this->hangamsummary[$this->lowyr.'10_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->hangamsummary[$this->lowyr.'11_AREA']+$this->hangamsummary[$this->lowyr.'11_NONMEMBER']+$this->hangamsummary[$this->lowyr.'11_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->hangamsummary[$this->lowyr.'12_AREA']+$this->hangamsummary[$this->lowyr.'12_NONMEMBER']+$this->hangamsummary[$this->lowyr.'12_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->hangamsummary[$this->curyr.'01_AREA']+$this->hangamsummary[$this->curyr.'01_NONMEMBER']+$this->hangamsummary[$this->curyr.'01_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->hangamsummary[$this->curyr.'02_AREA']+$this->hangamsummary[$this->curyr.'02_NONMEMBER']+$this->hangamsummary[$this->curyr.'02_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->hangamsummary[$this->curyr.'03_AREA']+$this->hangamsummary[$this->curyr.'03_NONMEMBER']+$this->hangamsummary[$this->curyr.'03_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->hangamsummary[$this->curyr.'04_AREA']+$this->hangamsummary[$this->curyr.'04_NONMEMBER']+$this->hangamsummary[$this->curyr.'04_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->hangamsummary[$this->curyr.'05_AREA']+$this->hangamsummary[$this->curyr.'05_NONMEMBER']+$this->hangamsummary[$this->curyr.'05_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->hangamsummary[$this->curyr.'06_AREA']+$this->hangamsummary[$this->curyr.'06_NONMEMBER']+$this->hangamsummary[$this->curyr.'06_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(32);
        $this->textbox($this->numformat($this->hangamsummary['ROWTOTAL'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);


        
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,395,$this->liney,'C'); 
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(41,395,$this->liney-2,'C'); 
        }

    }
    function groupfooter_3(&$group_row)
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
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,395,$this->liney,'C'); 
            $this->newpage(True);
        }

        $this->hline(41,395,$this->liney-2,'C');
        $this->setfieldwidth(41,10);
        $this->vline($this->liney-7,$this->liney+7,$this->x);
        $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-7,$this->liney+7,$this->x+$this->w);

        //$this->setfieldwidth(20);
        //$this->textbox('एकूण',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        //$this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
        
        //$this->setfieldwidth(41);
        //$this->textbox('सभासद',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','');
        //$this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);


        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->summary[$this->lowyr.'06_AREA']+$this->summary[$this->lowyr.'06_NONMEMBER']+$this->summary[$this->lowyr.'06_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->summary[$this->lowyr.'07_AREA']+$this->summary[$this->lowyr.'07_NONMEMBER']+$this->summary[$this->lowyr.'07_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->summary[$this->lowyr.'08_AREA']+$this->summary[$this->lowyr.'08_NONMEMBER']+$this->summary[$this->lowyr.'08_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->summary[$this->lowyr.'09_AREA']+$this->summary[$this->lowyr.'09_NONMEMBER']+$this->summary[$this->lowyr.'09_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->summary[$this->lowyr.'10_AREA']+$this->summary[$this->lowyr.'10_NONMEMBER']+$this->summary[$this->lowyr.'10_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->summary[$this->lowyr.'11_AREA']+$this->summary[$this->lowyr.'11_NONMEMBER']+$this->summary[$this->lowyr.'11_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->summary[$this->lowyr.'12_AREA']+$this->summary[$this->lowyr.'12_NONMEMBER']+$this->summary[$this->lowyr.'12_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->summary[$this->curyr.'01_AREA']+$this->summary[$this->curyr.'01_NONMEMBER']+$this->summary[$this->curyr.'01_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->summary[$this->curyr.'02_AREA']+$this->summary[$this->curyr.'02_NONMEMBER']+$this->summary[$this->curyr.'02_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->summary[$this->curyr.'03_AREA']+$this->summary[$this->curyr.'03_NONMEMBER']+$this->summary[$this->curyr.'03_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->summary[$this->curyr.'04_AREA']+$this->summary[$this->curyr.'04_NONMEMBER']+$this->summary[$this->curyr.'04_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->summary[$this->curyr.'05_AREA']+$this->summary[$this->curyr.'05_NONMEMBER']+$this->summary[$this->curyr.'05_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(24);
        $this->textbox($this->numformat($this->summary[$this->curyr.'06_AREA']+$this->summary[$this->curyr.'06_NONMEMBER']+$this->summary[$this->curyr.'06_GATECANE'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(32);
        $this->textbox($this->numformat($this->summary['ROWTOTAL'],2,true),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
        $this->newrow();
        $this->hline(10,395,$this->liney,'C'); 

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

    function export()
    {
        $cond='1=1';
        if ($this->divisioncode!=0)
        {
            $cond=$cond." and cl.divisioncode=".$this->divisioncode;
        }
        if ($this->plantationcategorycode!=0)
        {
            $cond=$cond." and p.plantationcategorycode=".$this->plantationcategorycode;
        }
        if ($this->fromdate!='' and $this->todate!='')
        {
            $cond=$cond." and p.plantationdate>='".$this->fromdate."' 
            and p.plantationdate<='".$this->todate."'";
        }
            $query ="select * from (SELECT varietycode,varietynameuni
            ,seasoncode,case when to_number(mth)<= to_number(to_char(mod(seasoncode-20002,100))||'06') then
            to_char(mod(seasoncode-20002,100))||'06' 
            when to_number(mth)>= to_number(to_char(mod(seasoncode-10001,100))||'06') then
            to_char(mod(seasoncode-10001,100))||'06'
            else
            mth 
            end mth,sum(area) area
            from (
            SELECT p.seasoncode,v.varietycode,v.varietynameuni
            ,to_char(p.plantationdate,'YYMM') as mth,area
                        ,case when c.farmercategorycode =1 then p.area else 0 end as memberf
                        ,case when c.farmercategorycode =2 then p.area else 0 end as nonmember
                        ,case when c.farmercategorycode =3 then p.area else 0 end as gatecane
            FROM plantationheader p,variety v,plantationhangam h
            ,farmer f,farmercategory c,village v,circle cl
            where p.varietycode=v.varietycode 
            and p.plantationhangamcode=h.plantationhangamcode
            and p.farmercode=f.farmercode
            and f.farmercategorycode=c.farmercategorycode
            and p.villagecode=v.villagecode
            and v.circlecode=cl.circlecode
            and seasoncode=".$_SESSION['yearperiodcode']."
                and {$cond}
            )
            group by seasoncode,varietycode,varietynameuni
            ,mth)
            pivot
            (
                sum(area) as area
                for mth 
                in (".$this->lowyr."06,".$this->lowyr."07,".$this->lowyr."08
                ,".$this->lowyr."09,".$this->lowyr."10,".$this->lowyr."11
                ,".$this->lowyr."12,".$this->curyr."01,".$this->curyr."02
                ,".$this->curyr."03,".$this->curyr."04,".$this->curyr."05,".$this->curyr."06)
            )
            order by seasoncode,varietycode,varietynameuni";
        
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $response = array();
           $filename='varietymonthlysummarynew.csv';
           //$fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');
           $fp1=fopen('php://memory', 'w');
           fputcsv($fp1, array('start','Variety',"<=06-".$this->lowyr,"07-".$this->lowyr,"08-".$this->lowyr,"09-".$this->lowyr,"10-".$this->lowyr,"11-".$this->lowyr,"12-".$this->lowyr,"01-".$this->curyr,"02-".$this->curyr,"03-".$this->curyr,"04-".$this->curyr,"05-".$this->curyr,">=06-".$this->curyr,'end'));
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                fputcsv($fp1, array('#',$row['VARIETYNAMEUNI'],$this->numformat($row[$this->lowyr.'06_AREA'],2,true),$this->numformat($row[$this->lowyr.'07_AREA'],2,true),$this->numformat($row[$this->lowyr.'08_AREA'],2,true),$this->numformat($row[$this->lowyr.'09_AREA'],2,true),$this->numformat($row[$this->lowyr.'10_AREA'],2,true),$this->numformat($row[$this->lowyr.'11_AREA'],2,true),$this->numformat($row[$this->lowyr.'12_AREA'],2,true),$this->numformat($row[$this->curyr.'01_AREA'],2,true),$this->numformat($row[$this->curyr.'02_AREA'],2,true),$this->numformat($row[$this->curyr.'03_AREA'],2,true),$this->numformat($row[$this->curyr.'04_AREA'],2,true),$this->numformat($row[$this->curyr.'05_AREA'],2,true),$this->numformat($row[$this->curyr.'06_AREA'],2,true),'#'), $delimiter = ',', $enclosure = '"');
           }
           // reset the file pointer to the start of the file
            fseek($fp1, 0);
            // tell the browser it's going to be a csv file
            header('Content-Type: application/csv');
            // tell the browser we want to save it instead of displaying it
            header('Content-Disposition: attachment; filename="'.$filename.'";');
            // make php send the generated csv lines to the browser
            fpassthru($fp1); 
            //fclose($fp1);

    }
}    
?>