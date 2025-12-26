<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class farmerlist extends reportbox
{	
    public $divisioncode;
    public $circlecode;
    public $villagecode;
    public $farmercategorycode;
    public $bankbranchcode;
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
	    $this->pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
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
        $this->newrow(7);
        $this->textbox('ऊस उत्पादक यादी ',170,10,'S','C',1,'siddhanta',13);
        $this->newrow(2);
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow(7);

        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('कोड नं ',$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(50);
        $this->textbox('नाव',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('मोबाईल',$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->textbox('खाते नंबर',$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(65);
        $this->textbox('बँक',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->hline(10,200,$this->liney+6,'C');
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
        $this->totalgroupcount=4;
        $cond="";
        if ($this->divisioncode!=0)
        {
            if ($cond=="")
                $cond="d.divisioncode=".$this->divisioncode;
            else
                $cond=" and d.divisioncode=".$this->divisioncode;
        }
        if ($this->circlecode!=0)
        {
            if ($cond=="")
                $cond=" c.circlecode=".$this->circlecode;
            else
                $cond=$cond." and c.circlecode=".$this->circlecode;
        }
        if ($this->villagecode!=0)
        {
            if ($cond=="")
                $cond=" v.villagecode=".$this->villagecode;
            else
                $cond=$cond." and v.villagecode=".$this->villagecode;
        }
        if ($this->farmercategorycode!=0)
        {
            if ($cond=="")
                $cond=" m.farmercategorycode=".$this->farmercategorycode;
            else
                $cond=$cond." and m.farmercategorycode=".$this->farmercategorycode;
        }
        if ($this->bankbranchcode!=0)
        {
            if ($cond=="")
            $cond=" b.bankbranchcode=".$this->bankbranchcode;
            else
            $cond=$cond." and b.bankbranchcode=".$this->bankbranchcode;
        }
        $group_query_1 ="select 
        d.divisioncode
        ,c.circlecode
        ,v.villagecode
        ,m.farmercategorycode
        ,d.divisionnameuni
        ,c.circlenameuni
        ,v.villagenameuni
        ,m.farmercategorynameuni
        ,t.farmercode
        ,t.farmernameuni
        ,t.farmernameeng
        ,t.mobilenumber
        ,t.bankbranchcode
        ,b.bankbranchnameuni
        ,b.bankbranchnameeng
        ,k.banknameuni
        ,t.accountnumber
        from farmer t,village v,circle c,division d,
        farmercategory m,bankbranch b,bank k
        where t.villagecode=v.villagecode and v.circlecode=c.circlecode
        and c.divisioncode=d.divisioncode and t.farmercategorycode=m.farmercategorycode
        and t.bankbranchcode=b.bankbranchcode(+)
        and b.bankcode=k.bankcode
        and {$cond} 
        order by d.divisioncode
        ,c.circlecode
        ,v.villagecode
        ,m.farmercategorycode,farmernameeng";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        $this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->hline(10,400,$this->liney,'D'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
    }
    
    function groupheader_1(&$group_row_1)
    {
    }


    function groupheader_2(&$group_row_1)
    {
    }

    function groupheader_3(&$group_row_1)
    {
    }
    function groupheader_4(&$group_row)
    {
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,400,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        $this->setfieldwidth(190,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('विभाग :'.$group_row['DIVISIONNAMEUNI'].' गट : '.$group_row['CIRCLENAMEUNI'].' गाव : '.$group_row['VILLAGENAMEUNI'].' सभासदत्व : '.$group_row['FARMERCATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->hline(10,400,$this->liney,'D'); 
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
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($group_row_1['FARMERCODE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(50);
        $this->textbox($group_row_1['FARMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['MOBILENUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->textbox($group_row_1['ACCOUNTNUMBER'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(65);
        $this->textbox($group_row_1['BANKBRANCHNAMEUNI'].', '.$group_row_1['BANKNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,400,$this->liney-2,'C'); 
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
