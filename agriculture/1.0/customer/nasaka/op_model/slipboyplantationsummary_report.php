<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class slipboyplantationsummary extends reportbox
{
    public $centrecode;
    public $fromdate;
    public $todate;
    public $opareasummary;
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
        $this->textbox('सेंटरनिहाय हंगामवार ऊसनोंद गोषवारा',180,10,'S','C',1,'siddhanta',13);
        $this->newrow(7);
        if ($this->fromdate!='' and $this->todate!='')
        {
            $this->textbox('हंगाम: '.$_SESSION['yearperiodcode'].' दिनांक '.DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y').' पासून ते दिनांक '.DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y').' पर्यंत',180,10,'S','C',1,'siddhanta',12);
        }
        else
        {
            $this->textbox('हंगाम: '.$_SESSION['yearperiodcode'],180,10,'S','C',1,'siddhanta',12);
        }
        $this->summary['NETCANETONNAGE']=0;
        $this->summary['CNT']=0;
        $this->summary['ADSALI']=0;
        $this->summary['PRESEASONAL']=0;
        $this->summary['SURU']=0;
        $this->summary['KHODWA']=0;
        $this->summary['PRSURU']=0;
        $this->summary['TOTAL']=0;
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
        $this->textbox('सेंटर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('नोंद संख्या',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
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
        $this->textbox('एकूण (हे.आर)',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->newrow(9);
        $this->hline(10,195,$this->liney-2,'C');

    }

    function group()
    {
        $this->totalgroupcount=1;
        if ($this->fromdate!='' and $this->todate!='')
        {
            $group_query_1 = "SELECT d.userid,d.slipboynameuni
            ,vvv.centrecode,vvv.centrenameuni,sum(cnt) as cnt
            ,sum(preseasonal) preseasonal
            ,sum(suru) suru,sum(adsali) adsali,sum(khodwa) khodwa,sum(total) total
            from (
            SELECT p.slipboycode,v.centrecode,1 as cnt
            ,case when p.plantationhangamcode =1 then p.area else 0 end as preseasonal
            ,case when p.plantationhangamcode =2 then p.area else 0 end as suru
            ,case when p.plantationhangamcode =3 then p.area else 0 end as adsali
            ,case when p.plantationhangamcode =4 then p.area else 0 end as khodwa
            ,case when p.plantationhangamcode =5 then p.area else 0 end as prsuru
            ,p.area as total
            FROM plantationheader p,village v,slipboy d
            where p.villagecode=v.villagecode 
            and p.slipboycode=d.slipboycode(+)
            and trunc(p.plotregistrationdate)>='".$this->fromdate."'
            and trunc(p.plotregistrationdate)<='".$this->todate."'
            and seasoncode=".$_SESSION['yearperiodcode']."
            )ttt,centre vvv,slipboy d
            where ttt.centrecode=vvv.centrecode
            and ttt.slipboycode=d.userid
            group by d.userid,d.slipboynameuni,vvv.centrecode,vvv.centrenameuni
            order by d.userid,vvv.centrecode
            ";
        }
        else
        {
            $group_query_1 = "SELECT d.userid,d.slipboynameuni
            ,vvv.centrecode,vvv.centrenameuni,sum(cnt) as cnt
            ,sum(preseasonal) preseasonal
            ,sum(suru) suru,sum(adsali) adsali,sum(khodwa) khodwa,sum(total) total
            from (
            SELECT p.slipboycode,v.centrecode,1 as cnt
            ,case when p.plantationhangamcode =1 then p.area else 0 end as preseasonal
            ,case when p.plantationhangamcode =2 then p.area else 0 end as suru
            ,case when p.plantationhangamcode =3 then p.area else 0 end as adsali
            ,case when p.plantationhangamcode =4 then p.area else 0 end as khodwa
            ,case when p.plantationhangamcode =5 then p.area else 0 end as prsuru
            ,p.area as total
            FROM plantationheader p,village v,slipboy d
            where p.villagecode=v.villagecode 
            and p.slipboycode=d.slipboycode(+)
            and seasoncode=".$_SESSION['yearperiodcode']."
            )ttt,centre vvv,slipboy d
            where ttt.centrecode=vvv.centrecode
            and ttt.slipboycode=d.userid
            group by d.userid,d.slipboynameuni,vvv.centrecode,vvv.centrenameuni
            order by d.userid,vvv.centrecode
            ";
        }

        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            //$this->hline(10,405,$this->liney,'D'); 
            $this->opareasummary['NETCANETONNAGE']+=$group_row_1['NETCANETONNAGE'];
            $this->opareasummary['CNT']+=$group_row_1['CNT'];
            $this->opareasummary['ADSALI']+=$group_row_1['ADSALI'];
            $this->opareasummary['PRESEASONAL']+=$group_row_1['PRESEASONAL'];
            $this->opareasummary['SURU']+=$group_row_1['SURU'];
            $this->opareasummary['KHODWA']+=$group_row_1['KHODWA'];
            $this->opareasummary['PRSURU']+=$group_row_1['PRSURU'];
            $this->opareasummary['TOTAL']+=$group_row_1['TOTAL'];

            $this->summary['NETCANETONNAGE']+=$group_row_1['NETCANETONNAGE'];
            $this->summary['CNT']+=$group_row_1['CNT'];
            $this->summary['ADSALI']+=$group_row_1['ADSALI'];
            $this->summary['PRESEASONAL']+=$group_row_1['PRESEASONAL'];
            $this->summary['SURU']+=$group_row_1['SURU'];
            $this->summary['KHODWA']+=$group_row_1['KHODWA'];
            $this->summary['PRSURU']+=$group_row_1['PRSURU'];
            $this->summary['TOTAL']+=$group_row_1['TOTAL'];
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
    }

    function groupheader_1(&$group_row_1)
    {
        //$this->divisionname=$group_row_1['DIVISIONNAMEUNI'];
        $this->opareasummary['NETCANETONNAGE']=0;
        $this->opareasummary['CNT']=0;
        $this->opareasummary['ADSALI']=0;
        $this->opareasummary['PRESEASONAL']=0;
        $this->opareasummary['SURU']=0;
        $this->opareasummary['KHODWA']=0;
        $this->opareasummary['PRSURU']=0;
        $this->opareasummary['TOTAL']=0;

        $this->setfieldwidth(40,10);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox($group_row_1['SLIPBOYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        
        $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20); 
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->setfieldwidth(25);
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
            $this->setfieldwidth(40,10);
            $this->vline($this->liney-2,$this->liney+5,$this->x);
            $this->textbox($group_row_1['CENTRENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($group_row_1['CNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($group_row_1['ADSALI'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($group_row_1['PRESEASONAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20); 
            $this->textbox($group_row_1['SURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($group_row_1['KHODWA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(20);
            $this->textbox($group_row_1['PRSURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->setfieldwidth(25);
            $this->textbox($group_row_1['TOTAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
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
    function groupfooter_1(&$group_row)
    {
        $this->setfieldwidth(40,10);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox('एकूण',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->opareasummary['CNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->opareasummary['ADSALI'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->opareasummary['PRESEASONAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox($this->opareasummary['SURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->opareasummary['KHODWA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox($this->opareasummary['PRSURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($this->opareasummary['TOTAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
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
    function groupfooter_2(&$group_row)
    {
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
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        $this->setfieldwidth(40,10);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->summary['CNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->summary['ADSALI'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->summary['PRESEASONAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox($this->summary['SURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->summary['KHODWA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox($this->summary['PRSURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($this->summary['TOTAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
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