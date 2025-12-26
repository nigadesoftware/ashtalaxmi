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
class harvestingprogram extends reportbox
{
    public $programnumber;
    public $centrecode;
    public $villagecode;
    public $centrenameuni;
    public $villagenameuni;
    public $membercategorynameuni;
    Public $villagesummary;
    Public $circlesummary;
    Public $summary;
    public $i=0;
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
	    $this->pdf = new MYPDF('L', PDF_UNIT, 'A3', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('plantation register');
        $this->pdf->SetKeywords('PLREG_000.MR');
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
        $this->pdf->Output('HARPROG_000.pdf', 'I');
    }
	function pageheader()
    {
        ob_flush();
        ob_start();
        $this->liney = 15;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->textbox('ऊस तोडणी प्रोग्राम',410,10,'S','C',1,'siddhanta',13);
        $this->newrow(7);
        $this->textbox('हंगाम: '.$_SESSION['yearperiodcode'].' प्रोग्राम नंबर: '.$this->programnumber,410,10,'S','C',1,'siddhanta',12);
        $this->newrow(7);
        $this->setfieldwidth(150,10);
        $this->textbox('सेंटर :'.$this->centrenameuni,$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow(7);
        $this->hline(10,410,$this->liney,'C');
        
        $this->setfieldwidth(15,10);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('अ.नं',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('प्लाॅट नं',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        /* $this->setfieldwidth(25);
        $this->textbox('स.प्रकार',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);
 */
        $this->setfieldwidth(63);
        $this->textbox('ऊस उत्पादक नाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('स.गाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('ला.गाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('कोड',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox('स.नं',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        
        $this->setfieldwidth(27);
        $this->textbox('ला.दिनांक',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('ला.हंगाम',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('क्षेत्र(सा)',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('अं. टनेज',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);


        $this->setfieldwidth(25);
        $this->textbox('ऊस जात',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(27);
        $this->textbox('प.दिनांक',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(23);
        $this->textbox('मोबाईल',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('तो.चा.दिनांक',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('तो.बं.दिनांक',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->newrow(9);
        $this->hline(10,410,$this->liney-2,'C');

    }
   
    function drawlines($limit)
    {
    }

    function pagefooter($islastpage=false)
    {
        //$this->drawlines($this->liney-31);
    }

    function group()
    {
        $this->totalgroupcount=2;
        $cond="p.seasoncode=".$_SESSION['yearperiodcode'];
        if ($this->programnumber!='')
        {
            $cond=$cond." and p.programnumber=".$this->programnumber;
        }
        if ($this->centrecode!=0)
        {
            $cond=$cond." and c.centrecode=".$this->centrecode;
        }
        if ($this->villagecode!='')
        {
            $cond=$cond." and v.villagecode=".$this->villagecode;
        }
        if ($this->plantationhangamcode!='')
        {
            $cond=$cond." and p.plantationhangamcode=".$this->plantationhangamcode;
        }
        $group_query_1 ="select centrecode,villagecode,programnumber
        ,plotnumber,plantationdate,area,areabygps,farmercode,farmernameuni
        ,villagenameuni,membervillagename,centrenameuni,mobilenumber,varietynameuni
        ,plantationhangamcode,plantationhangamnameuni,irrigationsourcenameuni,irrigationmethodnameuni
        ,plantationcategorynameuni,caneseedcategorynameuni,plantationmethodnameuni,orggutnumber
        ,caneseedsourcenameuni,maturitydate,farmercategorynameuni,expectedharvestingtonnage,gutnumber,row_number() over (partition by programnumber,centrecode order by programnumber,centrecode,villagecode,maturitydate,gutnumber,plotnumber) srnumber
        from (select programnumber,centrecode,villagecode
        ,plotnumber,plantationdate,area,areabygps,farmercode,farmernameuni
        ,villagenameuni,membervillagename,centrenameuni,mobilenumber,varietynameuni
        ,plantationhangamcode,plantationhangamnameuni,irrigationsourcenameuni,irrigationmethodnameuni
        ,plantationcategorynameuni,caneseedcategorynameuni,plantationmethodnameuni
        ,caneseedsourcenameuni,maturitydate,farmercategorynameuni,expectedharvestingtonnage,gutnumber as orggutnumber
        ,case when isnumeric(gutnumber)=1 then to_number(gutnumber) else 99999 end as gutnumber 
        from (
        select p.programnumber,c.centrecode,v.villagecode
        ,p.plotnumber,p.plantationdate,p.area,p.areabygps,f.farmercode,f.farmernameuni
        ,v.villagenameuni,c.centrenameuni,f.mobilenumber,vv.varietynameuni
        ,h.plantationhangamcode,h.plantationhangamnameuni,i.irrigationsourcenameuni,r.irrigationmethodnameuni
        ,t.plantationcategorynameuni,s.caneseedcategorynameuni,m.plantationmethodnameuni
        ,u.caneseedsourcenameuni,p.expectedharvestingtonnage,p.maturitydate,(
            case when instr(to_char(p.gutnumber),'/',1)>0 then 
            substr(to_char(p.gutnumber),1,instr(to_char(p.gutnumber),'/',1)-1)
            when instr(to_char(p.gutnumber),'.',1)>0 then 
            substr(to_char(p.gutnumber),1,instr(to_char(p.gutnumber),'.',1)-1)
            when instr(to_char(p.gutnumber),',',1)>0 then 
            substr(to_char(p.gutnumber),1,instr(to_char(p.gutnumber),',',1)-1)
            when instr(to_char(p.gutnumber),' ',1)>0 then 
            substr(to_char(p.gutnumber),1,instr(to_char(p.gutnumber),' ',1)-1)
            else
              to_char(p.gutnumber)
            end) gutnumber,fc.farmercategorynameuni,mv.villagenameuni as membervillagename
        from plantationheader p,farmer f,village v,centre c
        ,variety vv,plantationhangam h,irrigationsource i,irrigationmethod r,plantationcategory t
        ,caneseedcategory s,plantationmethod m,caneseedsource u
        ,farmercategory fc,village mv
        where p.farmercode=f.farmercode
        and p.villagecode=v.villagecode
        and v.centrecode=c.centrecode
        and f.farmercategorycode=fc.farmercategorycode
        and f.villagecode=mv.villagecode
        and p.varietycode=vv.varietycode(+)
        and p.plantationhangamcode=h.plantationhangamcode(+)
        and p.irrigationsourcecode=i.irrigationsourcecode(+)
        and p.irrigationmethodcode=r.irrigationmethodcode(+)
        and p.plantationcategorycode=t.plantationcategorycode(+)
        and p.caneseedcategorycode=s.caneseedcategorycode(+)
        and p.plantationmethodcode=m.plantationmethodcode(+)
        and p.caneseedsourcecode=u.caneseedsourcecode(+)
        and ".$cond."
        )t)
        order by centrecode,villagecode,plantationhangamcode,maturitydate,gutnumber,plotnumber";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        $this->summary['AREA']=0;
        $this->summary['EXPECTEDHARVESTINGTONNAGE']=0;
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->hline(10,410,$this->liney,'D'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
    }
    
    function groupheader_1(&$group_row_1)
    {
        $this->centrenameuni=$group_row_1['CENTRENAMEUNI'];
        $this->villagename=$group_row_1['VILLAGENAMEUNI'];
        //if ($this->i==0)
        $this->newpage(true);
        //$this->i++;
        $a=0;
        $this->circlesummary['AREA']=0;
        $this->circlesummary['EXPECTEDHARVESTINGTONNAGE']=0;
    }


    function groupheader_2(&$group_row_1)
    {
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,410,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        $this->villagename=$group_row_1['VILLAGENAMEUNI'];
        $this->villagesummary['AREA']=0;
        $this->villagesummary['EXPECTEDHARVESTINGTONNAGE']=0;
        $a=0;
        $this->newrow(-2);
        $this->setfieldwidth(35,10);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->newrow(2);
        $this->textbox($this->villagename,$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(63);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);


        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(27);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);


        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(27);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(23);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);


        $this->newrow(7);

        $this->hline(10,410,$this->liney-2,'C'); 

        /*$this->villagesummary['NETCANETONNAGE']=0;
        $this->villagesummary['GROSSAMOUNT']=0;
        $this->villagesummary['GROSSDEDUCTION']=0;
        $this->villagesummary['NETAMOUNT']=0; */
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
        $this->setfieldwidth(15,10);
        $this->vline($this->liney-2,$this->liney+7,$this->x);
        $this->textbox($group_row_1['SRNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->vline($this->liney-2,$this->liney+7,$this->x);
        $this->textbox($group_row_1['PLOTNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        /* $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+7,$this->x);
        $this->textbox($group_row_1['FARMERCATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
 */
        $this->setfieldwidth(63);
        $this->textbox($group_row_1['FARMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['MEMBERVILLAGENAME'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['FARMERCODE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox($group_row_1['ORGGUTNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);


        $this->setfieldwidth(27);
        $dt=DateTime::createFromFormat('d-M-y',$group_row_1['PLANTATIONDATE'])->format('d/m/Y');
        $this->textbox($dt,$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['PLANTATIONHANGAMNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(number_format($group_row_1['AREA'],2,'.',','),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(number_format($group_row_1['EXPECTEDHARVESTINGTONNAGE'],0,'.',','),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['VARIETYNAMEUNI'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(27);
        if($group_row_1['MATURITYDATE']!='')
        {
        $dt=DateTime::createFromFormat('d-M-y',$group_row_1['MATURITYDATE'])->format('d/m/Y');
        }
        $this->textbox($dt,$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(23);
        $this->textbox($group_row_1['MOBILENUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        //$this->textbox($group_row_1['MOBILENUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        //$this->textbox($group_row_1['MOBILENUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);


        $this->villagesummary['AREA']=$this->villagesummary['AREA']+$group_row_1['AREA'];
        $this->villagesummary['EXPECTEDHARVESTINGTONNAGE']=$this->villagesummary['EXPECTEDHARVESTINGTONNAGE']+$group_row_1['EXPECTEDHARVESTINGTONNAGE'];
        $this->circlesummary['AREA']=$this->circlesummary['AREA']+$group_row_1['AREA'];
        $this->circlesummary['EXPECTEDHARVESTINGTONNAGE']=$this->circlesummary['EXPECTEDHARVESTINGTONNAGE']+$group_row_1['EXPECTEDHARVESTINGTONNAGE'];
        $this->summary['AREA']=$this->summary['AREA']+$group_row_1['AREA'];
        $this->summary['EXPECTEDHARVESTINGTONNAGE']=$this->summary['EXPECTEDHARVESTINGTONNAGE']+$group_row_1['EXPECTEDHARVESTINGTONNAGE'];
        
        /*$this->setfieldwidth(23);
        $this->textbox($this->villagesummary['EXPECTEDHARVESTINGTONNAGE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);*/
        
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,410,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            //$this->hline(10,410,$this->liney-2,'C'); 
        }
    }
    
    function groupfooter_1(&$group_row_1)
    { 
        $this->newrow(1);
        $this->setfieldwidth(35,10);
        $this->vline($this->liney-2,$this->liney+7,$this->x);
        $this->newrow(1);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(63);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(27);
        //$this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('सेंटर एकूण',$this->w,$this->x,'S','R',1,'siddhanta',12);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->circlesummary['AREA'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->circlesummary['EXPECTEDHARVESTINGTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);


        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(27);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(23);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);


        $this->newrow();

        $this->hline(10,410,$this->liney,'C'); 

    }
    function groupfooter_2(&$group_row_2)
    {  
        $this->newrow(1);
        $this->setfieldwidth(35,10);
        $this->vline($this->liney-2,$this->liney+7,$this->x);
        $this->newrow(1);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(63);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(27);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('गाव एकूण',$this->w,$this->x,'S','R',1,'siddhanta',12);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->villagesummary['AREA'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->villagesummary['EXPECTEDHARVESTINGTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);


        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(27);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(23);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);


        $this->newrow();

        $this->hline(10,410,$this->liney,'C'); 


    }

    function groupfooter_3(&$group_row_3)
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

    function reportfooter()
    {
        $this->newrow(1);
        $this->setfieldwidth(35,10);
        $this->vline($this->liney-2,$this->liney+7,$this->x);
        $this->newrow(1);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(63);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(27);
        //$this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->summary['AREA'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->summary['EXPECTEDHARVESTINGTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);


        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(27);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(23);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);


        $this->newrow();

        $this->hline(10,410,$this->liney,'C'); 
    }



}    
?>