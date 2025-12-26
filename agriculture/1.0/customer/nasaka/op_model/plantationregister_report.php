<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a3_l.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class plantationregister extends reportbox
{
    public $centrecode;
    public $villagecode;
    public $fromdate;
    public $todate;
    public $centrenameuni;
    public $villagesummary;
    public $centresummary;
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
        $this->reportfooter();
    }

	function pageheader()
    {
          ob_flush();
        ob_start();
        $this->liney = 17;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->textbox('ऊसनोंद रजिस्टर',400,10,'S','C',1,'siddhanta',13);
        $this->newrow(7);
        if ($this->fromdate!='' and $this->todate!='')
        $this->textbox('हंगाम: '.$_SESSION['yearperiodcode'].' दिनांक '.$this->fromdate.' पासून ते दिनांक '.$this->todate.' पर्यंत',400,10,'S','C',1,'siddhanta',12);
        else
        $this->textbox('हंगाम: '.$_SESSION['yearperiodcode'],400,10,'S','C',1,'siddhanta',12);
        $this->newrow(7);
        $this->setfieldwidth(150,10);
        $this->textbox('सेंटर :'.$this->centrenameuni,$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow(7);
        $this->hline(10,400,$this->liney,'C');
        $this->setfieldwidth(20,10);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('प्लाॅट नं',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(27);
        $this->textbox('ला.दिनांक',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('कोड',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(63);
        $this->textbox('ऊस उत्पादक नाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->textbox('ला.गाव',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox('स.नं',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('क्षेत्र(सा)',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox('ला.हंगाम',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox('ऊस जात',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->textbox('बेणे स्रोत',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+5,$this->x+$this->w);
        
        $this->setfieldwidth(30);
        $this->textbox('बेणे प्रकार',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox('सिंचन स्रोत',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox('मोबाईल',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);
        
        $this->newrow(9);
        $this->hline(10,400,$this->liney-2,'C');
    }
   

    function group()
    {
        $this->totalgroupcount=3;
        $this->summary['AREA']=0;
        $this->summary['CNT']=0;
        
        $cond = " p.seasoncode=".$_SESSION['yearperiodcode'];
        if ($this->centrecode!=0)
        {
            $cond = $cond." and c.centrecode=".$this->centrecode;
        }
        if ($this->villagecode!=0)
        {
            $cond = $cond." and v.villagecode=".$this->villagecode;
        }
        if ($this->farmercategorycode!=0)
        {
            $cond = $cond." and f.farmercategorycode=".$this->farmercategorycode;
        }
        if ($this->plantationhangamcode!=0)
        {
            $cond = $cond." and p.plantationhangamcode=".$this->plantationhangamcode;
        }

        if ($this->fromdate!='' and $this->todate!='')
        {
            $frdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $trdt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $cond = $cond." and trunc(p.plotregistrationdate)>='".$frdt."'
                        and trunc(p.plotregistrationdate)<='".$trdt."'";
        }

        $group_query_1 = "select c.centrenameuni,v.villagenameuni,c.farmercategorynameuni
        ,f.farmernameuni,vv.varietynameuni
        ,h.plantationhangamnameuni,i.irrigationsourcenameuni,r.irrigationmethodnameuni
        ,t.plantationcategorynameuni,s.caneseedcategorynameuni,m.plantationmethodnameuni
        ,u.caneseedsourcenameuni
        ,f.mobilenumber,p.*
        from plantationheader p,farmer f,village v,centre c
        ,variety vv,plantationhangam h,irrigationsource i,irrigationmethod r,plantationcategory t
        ,caneseedcategory s,plantationmethod m,caneseedsource u,farmercategory c
        where p.farmercode=f.farmercode
        and p.villagecode=v.villagecode
        and v.centrecode=c.centrecode
        and p.varietycode=vv.varietycode(+)
        and p.plantationhangamcode=h.plantationhangamcode(+)
        and p.irrigationsourcecode=i.irrigationsourcecode(+)
        and p.irrigationmethodcode=r.irrigationmethodcode(+)
        and p.plantationcategorycode=t.plantationcategorycode(+)
        and p.caneseedcategorycode=s.caneseedcategorycode(+)
        and p.plantationmethodcode=m.plantationmethodcode(+)
        and p.caneseedsourcecode=u.caneseedsourcecode(+)
        and f.farmercategorycode=c.farmercategorycode(+) 
        and {$cond} 
        order by c.centrenameuni,v.villagenameuni,c.farmercategorycode,f.farmernameuni";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $k=0;
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
       if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,400,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            $this->hline(10,400,$this->liney-2,'C'); 
        }
        $this->centrenameuni = $group_row_1['CENTRENAMEUNI'];
        
        if ($this->pdf->getNumPages()==0)
        {
            $this->newpage(True);
        }
        
        /* $this->setfieldwidth(20,10);
            $this->vline($this->liney-2,$this->liney+5,$this->x);
            $this->textbox('गट :'.$group_row_1['CENTRENAMEUNI'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11,'','','','B');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w); */
            $this->centresummary['AREA']=0;
            $this->centresummary['CNT']=0;
    }

    function groupheader_2(&$group_row_1)
    {
        $this->villagesummary['AREA']=0;
        $this->villagesummary['CNT']=0;

    }

    function groupheader_3(&$group_row_1)
    {
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,400,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            $this->hline(10,400,$this->liney-2,'C'); 
        }

        $this->setfieldwidth(20,10);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox('गाव :',$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(27);
        $this->textbox($group_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(63);
        $this->textbox('सभासद प्रकार :'.$group_row_1['FARMERCATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        
        $this->setfieldwidth(30);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        $this->newrow();
        $this->hline(10,400,$this->liney-2,'C'); 

        
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
        //ob_flush();
        //ob_start();
        $this->setfieldwidth(20,10);
                        $this->vline($this->liney-2,$this->liney+5,$this->x);
                        $this->textbox($group_row_1['PLOTNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
                        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

                        $this->setfieldwidth(27);
                        $dt=DateTime::createFromFormat('d-M-y',$group_row_1['PLANTATIONDATE'])->format('d/m/Y');
                        $this->textbox($dt,$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
                        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

                        $this->setfieldwidth(15);
                        $this->textbox($group_row_1['FARMERCODE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
                        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

                        $this->setfieldwidth(63);
                        $this->textbox($group_row_1['FARMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
                        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

                        $this->setfieldwidth(35);
                        $this->textbox($group_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
                        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

                        $this->setfieldwidth(20); 
                        $this->textbox($group_row_1['GUTNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
                        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

                        $this->setfieldwidth(20);
                        $this->textbox(number_format($group_row_1['AREA'],2,'.',',').'/'.number_format($group_row_1['AREABYGPS'],2,'.',','),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
                        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

                        $this->setfieldwidth(30);
                        $this->textbox($group_row_1['PLANTATIONHANGAMNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
                        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

                        $this->setfieldwidth(30);
                        $this->textbox($group_row_1['VARIETYNAMEUNI'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
                        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

                        $this->setfieldwidth(40);
                        $this->textbox($group_row_1['CANESEEDSOURCENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
                        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
                        
                        $this->setfieldwidth(30);
                        $this->textbox($group_row_1['CANESEEDCATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
                        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

                        $this->setfieldwidth(30);
                        $this->textbox($group_row_1['IRRIGATIONSOURCENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
                        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

                        $this->setfieldwidth(30);
                        $this->textbox($group_row_1['MOBILENUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
                        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
                        
                        if ($this->isnewpage(20))
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
        //$this->hline(10,405,$this->liney-2,'');
        $this->centresummary['AREA']=$this->centresummary['AREA']+$group_row_1['AREA'];
        $this->villagesummary['AREA']=$this->villagesummary['AREA']+$group_row_1['AREA'];
        $this->centresummary['CNT']=$this->centresummary['CNT']+1;
        $this->villagesummary['CNT']=$this->villagesummary['CNT']+1;
        $this->summary['AREA']=$this->summary['AREA']+$group_row_1['AREA'];
        $this->summary['CNT']=$this->summary['CNT']+1;
    }


    function groupfooter_1(&$group_row_1)
    {     
        
        if ($this->isnewpage(10))
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        
        $this->setfieldwidth(60,110);
        $this->textbox($group_row_1['CENTRENAMEUNI'].' सेंटर एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox($this->centresummary['CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        //$this->setfieldwidth(15);
        $this->setfieldwidth(20);
        $this->textbox(number_format($this->centresummary['AREA'],3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow();
        $this->hline(10,400,$this->liney-2,'D'); 
        $this->newpage(True);
    }

    function groupfooter_2(&$group_row_1)
    {      
        if ($this->isnewpage(10))
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        
        $this->setfieldwidth(60,110);
        $this->textbox($group_row_1['VILLAGENAMEUNI'].' गाव एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(20);
        $this->textbox($this->villagesummary['CNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'','','','');
        //$this->setfieldwidth(15);
        $this->setfieldwidth(20);
        $this->textbox(number_format($this->villagesummary['AREA'],3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->newrow();
        $this->hline(10,400,$this->liney-2,'D'); 
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