<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class districttalukavillageareatonnage extends reportbox
{	
    public $seasoncode;
    public $divisioncode;
    Public $districtsummary;
    Public $talukasummary;
    Public $summary;
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
        $this->pdf->SetSubject('District Taluka Village Area Tonnage');
        $this->pdf->SetKeywords('DISTALAR_000.EN');
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
        $this->newrow(4);
        
        
        $this->newrow(5);
        if ($this->divisioncode=='0')
            $title = 'संपूर्ण';
        elseif ($this->divisioncode==1)
            $title ='कार्यक्षेत्रातील';
        elseif ($this->divisioncode==2)
            $title ='कार्यक्षेत्राबाहेरील';
        


        $this->textbox($title.' जिल्हा तालूका क्षेत्र टनेज',170,30,'S','C',1,'siddhanta',13);
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow(7);
        $this->setfieldwidth(40,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('जिल्हा',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->textbox('तालुका',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->textbox('गाव',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->textbox('क्षेत्र',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->textbox('अपेक्षित टनेज',$this->w,$this->x,'S','L',1,'siddhanta',11);
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
        $this->totalgroupcount=2;
        $cond="";
        $this->summary['AREA']=0;
        $this->summary['EXPECTEDHARVESTINGTONNAGE']=0;
        if ($this->seasoncode!=0)
        {
            if ($cond=="")
                $cond=" and t.seasoncode=".$this->seasoncode;
            else
                $cond=" and t.seasoncode=".$this->seasoncode;
        }
        if ($this->divisioncode!=0)
        {
            if ($cond=="")
                $cond=" and s.divisioncode=".$this->divisioncode;
            else
                $cond=" and s.divisioncode=".$this->divisioncode;
        }
        $group_query_1 ="select d.districtcode,l.talukacode,v.villagecode
        ,d.districtnameuni,l.talukanameuni,v.villagenameuni,sum(t.area) area,sum(t.area*nvl(s.expectedtonnage,0)) expectedharvestingtonnage 
        from PLANTATIONHEADER t,village v,taluka l,district d,circle c,SEASONDIVISIONTONNAGE s
        where t.villagecode=v.villagecode
        and v.talukacode=l.talukacode
        and l.districtcode=d.districtcode
        and v.circlecode=c.circlecode
        and c.divisioncode=s.divisioncode(+)
        and s.seasoncode=".$this->seasoncode." 
         {$cond}
        group by d.districtcode,l.talukacode,v.villagecode,d.districtnameuni,l.talukanameuni,v.villagenameuni
        order by d.districtcode,l.talukacode,v.villagenameuni";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        $this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->hline(10,200,$this->liney,'D'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
    }
    
    function groupheader_1(&$group_row_1)
    {
        $this->districtsummary['AREA']=0;
        $this->districtsummary['EXPECTEDHARVESTINGTONNAGE']=0;
    }


    function groupheader_2(&$group_row_1)
    {
        $this->talukasummary['AREA']=0;
        $this->talukasummary['EXPECTEDHARVESTINGTONNAGE']=0;
        $this->setfieldwidth(40,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($group_row_1['DISTRICTNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(40);
        $this->textbox($group_row_1['TALUKANAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

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
        $this->vline($this->liney-1,$this->liney+6,10);
        $this->setfieldwidth(40,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(40);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(40);
        $this->textbox($group_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->textbox(number_format($group_row_1['AREA'],2),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->textbox(number_format_indian($group_row_1['EXPECTEDHARVESTINGTONNAGE'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->districtsummary['AREA']=$this->districtsummary['AREA']+$group_row_1['AREA'];
        $this->districtsummary['EXPECTEDHARVESTINGTONNAGE']=$this->districtsummary['EXPECTEDHARVESTINGTONNAGE']+$group_row_1['EXPECTEDHARVESTINGTONNAGE'];
        $this->talukasummary['AREA']=$this->talukasummary['AREA']+$group_row_1['AREA'];
        $this->talukasummary['EXPECTEDHARVESTINGTONNAGE']=$this->talukasummary['EXPECTEDHARVESTINGTONNAGE']+$group_row_1['EXPECTEDHARVESTINGTONNAGE'];
        $this->summary['AREA']=$this->summary['AREA']+$group_row_1['AREA'];
        $this->summary['EXPECTEDHARVESTINGTONNAGE']=$this->summary['EXPECTEDHARVESTINGTONNAGE']+$group_row_1['EXPECTEDHARVESTINGTONNAGE'];


        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney-2,'C'); 
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
/*         if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            //$this->hline(10,400,$this->liney-2,'C'); 
        }
        $this->vline($this->liney-1,$this->liney+6,10);
        $this->setfieldwidth(40,10);
        $this->textbox($group_row_1['DISTRICTNAMEUNI'].' जिल्हा एकूूण',$this->w,$this->x,'S','R',1,'siddhanta',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(40);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(40);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->textbox(number_format_indian($this->districtsummary['AREA'],2),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->textbox(number_format_indian($this->districtsummary['EXPECTEDHARVESTINGTONNAGE'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            //$this->hline(10,400,$this->liney-2,'C'); 
        }
 */    
    }
    function groupfooter_2(&$group_row_2)
    {  
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            $this->hline(10,200,$this->liney-1,'C'); 
        }
        $this->vline($this->liney-1,$this->liney+6,10);
        $this->setfieldwidth(40,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(40);
        $this->textbox($group_row_2['TALUKANAMEUNI'].' तालूका एकूूण',$this->w,$this->x,'S','R',1,'siddhanta',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(40);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->textbox(number_format_indian($this->talukasummary['AREA'],2),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->textbox(number_format_indian($this->talukasummary['EXPECTEDHARVESTINGTONNAGE'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        //if ($this->isnewpage(15))
        //{
            $this->newrow();
            $this->hline(10,200,$this->liney-2,'C'); 
            $this->newpage(True);
        //}   
        //else
        //{
        //    $this->newrow();
        //    $this->hline(10,200,$this->liney-2,'C'); 
        //}
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
        /* if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            $this->hline(10,200,$this->liney-1,'C'); 
        }
        $this->vline($this->liney-1,$this->liney+6,10);
        $this->setfieldwidth(40,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(40);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(40);
        $this->textbox('एकूूण एकंदर',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->textbox(number_format_indian($this->summary['AREA'],2),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->textbox(number_format_indian($this->summary['EXPECTEDHARVESTINGTONNAGE'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->hline(10,200,$this->liney-2,'C');  */
    }

}    
?>
