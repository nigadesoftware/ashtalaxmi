<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A5_L_smart.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class smartcardprinting extends reportbox
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
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueType', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalBharati.ttf', 'TrueType', '', 32);
    	// create new PDF document
        $resolution= array(255, 162);
	    $this->pdf = new MYPDF('L', PDF_UNIT, $resolution, true, 'UTF-8', false);
        
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
        $this->pdf->SetAutoPageBreak(false, 0);
        // set margins
        $this->pdf->SetMargins(0, 0, 0);
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
        $this->pdf->Output('SCPRN_000.pdf', 'I');
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
        $this->totalgroupcount=0;
        $cond="";
        $group_query_1 ="select r.districtnameuni
        ,k.talukanameuni
        ,c.circlenameuni
        ,v.villagenameuni
        ,t.farmercode
        ,t.farmernameuni
        ,t.farmernameeng
        from farmer t,village v,circle c,division d,
        farmercategory m,taluka k,district r
        where t.villagecode=v.villagecode and v.circlecode=c.circlecode
        and c.divisioncode=d.divisioncode and t.farmercategorycode=m.farmercategorycode
        and v.talukacode=k.talukacode(+)
        and k.districtcode=r.districtcode(+)
        and t.isphotoverified=1 and t.issmartcardprinted is null 
        order by d.divisioncode
        ,c.circlecode
        ,v.villagecode
        ,m.farmercategorycode,farmernameeng";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        //$this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            //$this->hline(10,400,$this->liney,'D'); 
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
        $this->newpage(True);
        //Image( $file, $x = '', $y = '', $w = 0, $h = 0, $type = '', $link = '', $align = '', $resize = false, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0, $fitbox = false, $hidden = false, $fitonpage = false, $alt = false, $altimgs = array() )
        $this->pdf->Image("../img/smartcardbkg.jpg", 0, 0,255,162,'', '', '', true, 300, '', false, false, 0);
       // $this->pdf->Image("../img/KADWA 12.png", 0, 0,255,162,'', '', '', true, 300, '', false, false, 0);
        //$this->pdf->Image("../img/nigadesoftwaretechnologies_logo_3.png", 0, 0,250,150, '', '', '', true, 300, '', false, false, 0,true,false,true);
        
        $this->newrow(72);
        $this->pdf->Image("../memberphoto/".$group_row_1['FARMERCODE']."_0001.jpg", 15, 84, 50, 60, '', '', '', false, 300, '', false, false, 0);
        $this->pdf->write2DBarcode(nigsimencrypt($group_row_1['FARMERCODE']), 'QRCODE,H', 190, 120, 30, 30, $style, 'N');
        $this->setfieldwidth(15,70);
        //$this->vline($this->liney-1,$this->liney+6,$this->x);
        //$this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(150,75);
        $this->newrow(10);
        $this->textbox($group_row_1['FARMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',33,'','Y');
        $this->textbox($group_row_1['FARMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',33,'','Y');
        $this->newrow(13);
        $this->setfieldwidth(150,75);
        $this->textbox('सभासद कोड नं.: '.$group_row_1['FARMERCODE'],$this->w,$this->x,'S','L',1,'siddhanta',30,'','Y');
        $this->textbox('सभासद कोड नं.: '.$group_row_1['FARMERCODE'],$this->w,$this->x,'S','L',1,'siddhanta',30,'','Y');
        $this->newrow(13);
        $this->setfieldwidth(150,75);
        $this->textbox('गाव : '.$group_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',25,'','Y');
        $this->newrow(10);
        $this->setfieldwidth(150,75);
        $this->textbox('गट  : '.$group_row_1['CIRCLENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',25,'','Y');
        
        $this->newrow(10);
        $this->setfieldwidth(150,75);
        $this->textbox('तालुका : '.$group_row_1['TALUKANAMEUNI'].'   जिल्हा : '.$group_row_1['DISTRICTNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',22,'','Y');
        

        if ($this->isnewpage(15))
        {
            $this->newrow();
            //$this->hline(10,400,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
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
