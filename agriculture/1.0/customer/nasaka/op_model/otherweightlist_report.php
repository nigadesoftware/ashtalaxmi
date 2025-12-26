<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class otherweightlist extends reportbox
{	
    public $othmatcategorycode;
    public $purchasercode;
    public $fromdate;
    public $todate;
    public $summary;
    Public $othmatcategorynameuni;
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
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Other Weight List');
        $this->pdf->SetKeywords('OTHWTLST_000.MR');
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));
       // $title = str_pad(' ', 30).'Rajaramnagar, Tal - Dindori Dist - Nashik';
    	//$this->pdf->SetHeaderData('', 0,str_pad(' ', 21).'Twentyone Sugars Limited' ,$title);
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
        $this->pdf->Output('OTHWTLST_000.pdf', 'I');
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
        $this->newrow(7);
        $this->textbox('इतर वजन यादी '.$this->othmatcategorynameuni,260,10,'S','C',1,'siddhanta',13);
        $this->newrow(2);
        $this->hline(10,280,$this->liney+6,'C');
        $this->newrow(7);

        $this->setfieldwidth(20,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('पावती नं ',$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('दिनांक',$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(60);
        $this->textbox('पुरवठादार / ग्राहक',$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->textbox('ठिकाण',$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox('वाहन नंबर',$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox('भरगाडी वजन',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox('रिकामीगाडी वजन',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox('निव्वळ वजन',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(40);
        $this->textbox('शेरा',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->hline(10,280,$this->liney+6,'C');
        $this->newrow();
        //$this->drawlines($limit);
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
        if ($this->othmatcategorycode!=0 and $this->othmatcategorycode!='')
        {
            $cond=$cond." and w.othmatcategorycode=".$this->othmatcategorycode;
        }
        if ($this->fromdate!='' and $this->todate!='')
        {
            $fromdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $todt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $cond=$cond." and trunc(w.weighmentdate)>='".$fromdt."' and trunc(w.weighmentdate)<='".$todt."'";
        }
        if ($this->purchasercode!='')
        {
            $cond=$cond." and purchasercode=".$this->purchasercode;
        }
        $group_query_1 ="select w.othmatcategorycode,transactionnumber, yearperiodcode, receiptnumber, weighmentdate, partycustomer, place, vehiclenumber, loadweight, emptyweight, netweight, userid, narration
        ,m.othmatcategorynameeng,m.othmatcategorynameuni
        from otherweight w,othermaterial m
        where w.othmatcategorycode=m.othmatcategorycode
        and {$cond}
        order by w.othmatcategorycode,w.weighmentdate,receiptnumber";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        //$this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->hline(10,280,$this->liney,'D'); 
            $last_row=$group_row_1;
            $i++;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
    }
    
    function groupheader_1(&$group_row_1)
    {
        $this->othmatcategorynameuni=$group_row_1['OTHMATCATEGORYNAMEUNI'];
        $summary['NETWEIGHT']=0;
        $this->newpage(true);
    }


    function groupheader_2(&$group_row_1)
    {
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
        if ($this->isnewpage(20))
        {
            $this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }  
        $this->setfieldwidth(20,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($group_row_1['RECEIPTNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $dt = DateTime::createFromFormat('d-M-y',$group_row_1['WEIGHMENTDATE'])->format('d/m/Y');
        $this->setfieldwidth(25);
        $this->textbox($dt ,$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(60);
        $this->textbox($group_row_1['PARTYCUSTOMER'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->textbox($group_row_1['PLACE'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox($group_row_1['VEHICLENUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['LOADWEIGHT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['EMPTYWEIGHT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['NETWEIGHT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(40);
        $this->textbox($group_row_1['NARRATION'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->summary['NETWEIGHT']=$this->summary['NETWEIGHT']+$group_row_1['NETWEIGHT'];
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,280,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            //$this->hline(10,400,$this->liney-2,'C'); 
        }
    }
    
    function groupfooter_1(&$group_row_1)
    { 
        if ($this->isnewpage(20))
        {
            $this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }  
        $this->setfieldwidth(20,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(25);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(60);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox('आज',$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox('एकूण',$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox($this->summary['NETWEIGHT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(40);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(6);
        $this->hline(10,280,$this->liney,'C'); 


        if ($this->isnewpage(20))
        {
            $this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }  
        $this->setfieldwidth(20,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(25);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(60);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox('आज अखेर',$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox('एकूण',$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $todt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
        $group_query_1 ="select sum(netweight) netweight_todate
        from otherweight w,othermaterial m
        where w.othmatcategorycode=m.othmatcategorycode
        and trunc(w.weighmentdate)<='{$todt}' and w.othmatcategorycode=".$group_row_1['OTHMATCATEGORYCODE']."
        order by w.othmatcategorycode,w.weighmentdate,receiptnumber";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        //$this->newpage(true);
        if ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->summary['NETWEIGHT_TODATE']=$group_row_1['NETWEIGHT_TODATE'];
        }
        $this->setfieldwidth(20);
        $this->textbox($this->summary['NETWEIGHT_TODATE'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(40);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(6);
        $this->hline(10,280,$this->liney,'C'); 
        $this->summary['NETWEIGHT']=0;
    }
    function groupfooter_2(&$group_row_2)
    {  
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
    }

}    
?>
