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
class subcontractorlist extends reportbox
{
    public $servicetrhrcategorycode;
    public $contractorcode;
    public $title;
    public $srno;
    public $isfirstpage;
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
        $this->isfirstpage=1;
        if ($this->contractorcode!=0)
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
        $this->textbox('सब कंत्राटदार यादी',180,10,'S','C',1,'siddhanta',13);
        $this->newrow(7);
        $this->textbox('हंगाम: '.$_SESSION['yearperiodcode'],180,10,'S','C',1,'siddhanta',12);
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
        if ($this->contractorcode==0)
        {
            $this->newrow(7);
            $this->setfieldwidth(150,10);
            $this->textbox('तो.व सेवा- '.$this->title,$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        }
        $this->newrow(7);
        $this->hline(10,205,$this->liney,'C');
        $this->setfieldwidth(100,10);
        //$this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('सब कंत्राटदार',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        //$this->vline($this->liney,$this->liney+7,$this->x+$this->w);
        $this->setfieldwidth(100);
        $this->textbox('कंत्राटदार',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        //$this->vline($this->liney,$this->liney+7,$this->x+$this->w);
        $this->newrow(9);
        $this->hline(10,205,$this->liney-2,'C');

    }
    function group()
    { 
        $this->totalgroupcount=2;
        $cond='1=1';
        if ($this->servicetrhrcategorycode!=0)
        {
            $cond=$cond." and y.servicetrhrcategorycode=".$this->servicetrhrcategorycode;
        }
        if ($this->contractorcode!=0)
        {
            $cond=$cond." and c.contractorcode=".$this->contractorcode;
        }
            $group_query_1 ="select 
            y.servicetrhrcategorycode
            ,s.subcontractorcode
            ,c.contractorcode
            ,y.servicetrhrcategorycode
            ,y.servicetrhrcatnameeng
            ,y.servicetrhrcatnameuni
            ,contractornameeng
            ,contractornameuni
            ,s.subcontractornameeng
            ,s.subcontractornameuni
            ,v.vehiclecode
            ,v.vehiclenumber
            ,l.vehiclecategorynameuni
            ,r.tyregadicode
            ,r.tyregadinumber
            ,r.gadiwannameuni
            ,h.harvestersubcategorynameuni
            from contractor c
            ,subcontractor s
            ,contractorcontract t
            ,servicetrhrcategory y
            ,vehicle v
            ,tyregadi r
            ,vehiclecategory l
            ,harvestersubcategory h
            where c.contractorcode=t.contractorcode 
            and t.contractcode=s.contractcode
            and t.seasoncode=s.seasoncode
            and t.servicetrhrcategorycode=y.servicetrhrcategorycode
            and s.seasoncode=v.seasoncode(+)
            and s.subcontractorcode=v.subcontractorcode(+)
            and v.vehiclecategorycode=l.vehiclecategorycode(+)
            and s.harvestersubcategorycode=h.harvestersubcategorycode(+)
            and s.seasoncode=r.seasoncode(+)
            and s.subcontractorcode=r.subcontractorcode(+)
            and s.seasoncode=".$_SESSION['yearperiodcode']."
            and ".$cond.
            " order by y.servicetrhrcategorycode
            ,y.servicetrhrcatnameeng
            ,c.contractorcode
            ,v.vehiclecode
            ,r.tyregadicode";
        
            $group_result_1 = oci_parse($this->connection, $group_query_1);
            $r = oci_execute($group_result_1);
            $i=0;
            while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
            {
                /* if ($i==0)
                   {$this->title=$group_row_1['SERVICETRHRCATNAMEUNI'];
                    $this->newpage(True);
                    $i++;
                    }
                else
                    $i++; */
                $this->grouptrigger($group_row_1,$last_row);
                $this->detail_1($group_row_1);
                $last_row=$group_row_1;
            }
            $this->grouptrigger($group_row_1,$last_row,'E');
        //$this->reportfooter();
    }
    
    function groupheader_1(&$group_row_1)
    {
        $this->title=$group_row_1['SERVICETRHRCATNAMEUNI'];
        if ($this->contractorcode==0)
        $this->newpage(True);
        else
        {
            $this->setfieldwidth(100,10);
            $this->textbox('तो.व सेवा- '.$this->title,$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
            $this->newrow();
        }
    }
    function groupheader_2(&$group_row_1)
    {
        $this->srno=1;
        $this->setfieldwidth(100,10);
        $this->textbox($group_row_1['SUBCONTRACTORCODE'].' - '.$group_row_1['SUBCONTRACTORNAMEUNI'].' - '.$group_row_1['HARVESTERSUBCATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(100);
        $this->textbox($group_row_1['CONTRACTORCODE'].' '.$group_row_1['CONTRACTORNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->newrow();
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
        if ($group_row_1['VEHICLECODE']!='')
        {
            if ($this->srno%2==1)
            {
                $this->setfieldwidth(15,20);
                $this->textbox($group_row_1['VEHICLECODE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10,'','','','');
                //$this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                
                $this->setfieldwidth(30);
                $this->textbox($group_row_1['VEHICLENUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11,'','','','');
                //$this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

                $this->setfieldwidth(25);
                $this->textbox($group_row_1['VEHICLECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
                //$this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
            }    
            elseif ($this->srno%2==0)
            {
                $this->setfieldwidth(15,120);
                $this->textbox($group_row_1['VEHICLECODE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10,'','','','');
                //$this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                
                $this->setfieldwidth(30);
                $this->textbox($group_row_1['VEHICLENUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11,'','','','');
                //$this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);

                $this->setfieldwidth(25);
                $this->textbox($group_row_1['VEHICLECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
                //$this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                $this->newrow(5);
            }
        }
        if ($group_row_1['TYREGADICODE']!='')
        {
            if ($this->srno%2==1)
            {
                $this->setfieldwidth(15,20);
                $this->textbox($group_row_1['TYREGADICODE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10,'','','','');
                //$this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                
                $this->setfieldwidth(15);
                $this->textbox($group_row_1['TYREGADINUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11,'','','','');
                //$this->vline($this->liney,$this->liney+5,$this->x+$this->w);

                $this->setfieldwidth(60);
                $this->textbox($group_row_1['GADIWANNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                $this->vline($this->liney,$this->liney+5,$this->x+$this->w);
            }
            elseif ($this->srno%2==0)
            {
                $this->setfieldwidth(15,115);
                $this->textbox($group_row_1['TYREGADICODE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10,'','','','');
                //$this->vline($this->liney-2,$this->liney+7,$this->x+$this->w);
                
                $this->setfieldwidth(20);
                $this->textbox($group_row_1['TYREGADINUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11,'','','','');
                //$this->vline($this->liney,$this->liney+5,$this->x+$this->w);

                $this->setfieldwidth(60);
                $this->textbox($group_row_1['GADIWANNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
                //$this->vline($this->liney,$this->liney+5,$this->x+$this->w);
                $this->newrow(5);
            }
            
        }
        $this->srno++;
            
            if ($this->isnewpage(15))
            {
                $this->newrow();
                $this->hline(10,205,$this->liney,'C'); 
                $this->newpage(True);
            }
            else
            {
                //$this->newrow(7);
                //$this->hline(60,395,$this->liney-2,'C'); 
            }

    }
    function groupfooter_1(&$group_row)
    {
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,205,$this->liney,'C'); 
            $this->newpage(True);
        }
    }
    function groupfooter_2(&$group_row)
    {
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,205,$this->liney,'C'); 
            $this->newpage(True);
        }
        elseif ($group_row['SERVICETRHRCATEGORYCODE']==2)
        {
            //$this->newrow(5);
            //$this->hline(10,395,$this->liney,'C'); 
        }
        else
        {
            $this->newrow(5);
            $this->hline(10,395,$this->liney,'C'); 
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
            $this->hline(10,205,$this->liney,'C'); 
            $this->newpage(True);
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