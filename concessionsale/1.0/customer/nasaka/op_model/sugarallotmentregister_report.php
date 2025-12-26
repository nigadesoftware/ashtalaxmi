<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_l.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class sugarallotmentregister extends reportbox
{
    public $srno;
    public $circlecode;
    public $seasoncode;
    public $summary;
    public $villagename;
    public $upddate;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7,$subject='',$pdffilename='',$papersize='A3',$orientation='P')
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $this->pdffilename= $pdffilename;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueType', '', 32);
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
        $this->pdf->SetSubject('Sugar Allotment Register');
        $this->pdf->SetKeywords('SUGALTREG_000.MR');
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
        $this->newpage(True);
        $this->group();
        $this->reportfooter();
    }

	function pageheader()
    {
        //$this->pdf->Image("../img/kadwawatermark.png", 60, 35, 70, 70, '', '', '', false, 300, '', false, false, 0);
        $this->pdf->SetFont('helvetica', 'I', 8);
        //$this->pdf->Text(170, 40, $_SESSION['yearperiodcode'].str_pad($this->farmercode, 5, '0', STR_PAD_LEFT));
        $this->liney = 15;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->textbox('सभासद साखर वाटप रजिस्टर',260,10,'S','C',1,'siddhanta',12);
        $this->newrow(7);
        if ($this->upddate == '')
            $this->textbox('वाटप वर्ष : '.$_SESSION['yearperiodcode'],260,10,'S','C',1,'siddhanta',11);
        else
        $this->textbox('वाटप वर्ष : '.$_SESSION['yearperiodcode'].' '.'तयार केल्याचा दिनांक :'.$this->upddate,260,10,'S','C',1,'siddhanta',11);
        $this->newrow(7);
        $this->hline(10,300,$this->liney,'C');
        $this->setfieldwidth(20,10);
        $this->textbox('अ.न.',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox('कार्ड नं',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox('कोड नं',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(50);
        $this->textbox('नाव',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox('शेअर संख्या',$this->w,$this->x,'S','R',1,'siddhanta',11,'','Y');
        $this->setfieldwidth(20);
        $this->textbox('शेअर रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','Y');
        $this->setfieldwidth(20);
        $this->textbox('शेअर साखर',$this->w,$this->x,'S','R',1,'siddhanta',11,'','Y');
        $this->setfieldwidth(20);
        $this->textbox('गळीत टनेज',$this->w,$this->x,'S','R',1,'siddhanta',11,'','Y');
        $this->setfieldwidth(20);
        $this->textbox('गळीत साखर',$this->w,$this->x,'S','R',1,'siddhanta',11,'','Y');
        $this->setfieldwidth(20);
        $this->textbox('बेणे गुंठे',$this->w,$this->x,'S','R',1,'siddhanta',11,'','Y');
        $this->setfieldwidth(20);
        $this->textbox('बेणे साखर',$this->w,$this->x,'S','R',1,'siddhanta',11,'','Y');
        $this->setfieldwidth(20);
        $this->textbox('एकूण साखर',$this->w,$this->x,'S','R',1,'siddhanta',11,'','Y');
        $this->newrow(10);
        $this->hline(10,300,$this->liney,'C');
        if ($this->villagename!='')
        {
            $this->textbox('गाव : '.$this->villagename,180,20,'S','L',1,'siddhanta',11);
            $this->newrow(7);
            $this->hline(10,300,$this->liney,'D');
        }
    }
   

    function group()
    {
        $this->totalgroupcount=2;

        $cond = "d.yearcode=".$_SESSION['yearperiodcode'];
        if ($this->circlecode!='' and $this->circlecode!=0)
        $cond = $cond." and c.circlecode=".$this->circlecode; 

        if ($this->upddate !='')
            $cond1 = "trunc(d.upddate) = '".DateTime::createFromFormat('d/m/Y',$this->upddate)->format('d-M-Y')."'";
        else
            $cond1 = "1=1";
        
        $this->summary['SHARESUGAR']=0;
        $this->summary['CANESUGAR']=0;
        $this->summary['BENESUGAR']=0;
        $this->summary['TOTALSUGAR']=0;

        $group_query_1 = "with data as (select t.yearcode,c.refcode farmercode,sum(sharesugar) sharesugar,sum(canesugar) canesugar,sum(benesugar) benesugar
        from (
        select yearcode,customercode
        ,case when concessioncategorycode=1 then quantity else 0 end sharesugar
        ,case when concessioncategorycode=2 then quantity else 0 end canesugar
        ,case when concessioncategorycode=4 then quantity else 0 end benesugar
        from (
            select t.yearcode,d.customercode,t.concessioncategorycode,sum(d.quantity) quantity
            from allotmentheader t,allotmentdetail d,customer c
            where t.transactionnumber=d.transactionnumber and t.customercategorycode=1 and d.customercode=c.customercode
            and duesflag is null and {$cond1} 
            group by t.yearcode,d.customercode,t.concessioncategorycode))t,customer c
        where t.customercode=c.customercode
        group by t.yearcode,c.refcode)
        select c.circlecode,v.villagecode,f.farmercode,sharesugar,shareamount,canesugar,benesugar,nvl(sharesugar,0)+nvl(canesugar,0)+nvl(benesugar,0) totalsugar,f.numberofshares,f.numberofbeneguntha,f.suppliedtonnage,c.circlenameuni,v.villagenameuni,r.farmernameuni 
        from data d,farmersugarbase f,nst_nasaka_agriculture.farmer r,nst_nasaka_agriculture.village v,nst_nasaka_agriculture.circle c
        where d.farmercode=r.farmercode and r.villagecode=v.villagecode and v.circlecode=c.circlecode
        and {$cond}
        and d.yearcode=f.yearcode(+) and d.farmercode=f.farmercode(+)
        order by c.circlecode,v.villagenameuni,v.villagecode,r.farmernameuni,f.farmercode
        ";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
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
        $this->villagename='';
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newpage(True);
        }
        $this->textbox('गट : '.$group_row_1['CIRCLENAMEUNI'],180,10,'S','L',1,'siddhanta',11);
        $this->newrow(7);
    }

    function groupheader_2(&$group_row_1)
    {
        $this->srno = 1;
        $this->villagename = $group_row_1['VILLAGENAMEUNI'];
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newpage(True);
        }
        $this->textbox('गाव : '.$group_row_1['VILLAGENAMEUNI'],180,20,'S','L',1,'siddhanta',11);
        $this->newrow(7);
        $this->hline(10,300,$this->liney,'D');
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
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
            //$this->newpage(True);
        }
        $this->setfieldwidth(15,10);
        $this->textbox($this->srno,$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',8);
        $this->setfieldwidth(25);
        $this->textbox($_SESSION['yearperiodcode'].str_pad($group_row_1['FARMERCODE'], 5, '0', STR_PAD_LEFT),$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',8);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['FARMERCODE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(50);
        $this->textbox($group_row_1['FARMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['NUMBEROFSHARES'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['SHAREAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['SHARESUGAR'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['SUPPLIEDTONNAGE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['CANESUGAR'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['NUMBEROFBENEGUNTHA'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['BENESUGAR'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['TOTALSUGAR'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        
        $this->summary['SHARESUGAR']=$this->summary['SHARESUGAR']+$group_row_1['SHARESUGAR'];
        $this->summary['CANESUGAR']=$this->summary['CANESUGAR']+$group_row_1['CANESUGAR'];
        $this->summary['BENESUGAR']=$this->summary['BENESUGAR']+$group_row_1['BENESUGAR'];
        $this->summary['TOTALSUGAR']=$this->summary['TOTALSUGAR']+$group_row_1['TOTALSUGAR'];
        $this->newrow();
        $this->srno++;
        
        if ($this->isnewpage(15))
        {
            //$this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            $this->hline(10,300,$this->liney,'D'); 
        }
    }


    function groupfooter_1(&$group_row_1)
    {     
        
    }

    function groupfooter_2(&$group_row_1)
    {      
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
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
        $this->hline(10,300,$this->liney,'C');
        $this->setfieldwidth(15,10);
        //$this->textbox($this->srno,$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',8);
        $this->setfieldwidth(25);
        $this->setfieldwidth(20);
        $this->setfieldwidth(50);
        $this->textbox('एकूण',$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->setfieldwidth(20);
        $this->setfieldwidth(20);
        $this->textbox($this->summary['SHARESUGAR'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->setfieldwidth(20);
        $this->textbox($this->summary['CANESUGAR'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->setfieldwidth(20);
        $this->textbox($this->summary['BENESUGAR'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(20);
        $this->textbox($this->summary['TOTALSUGAR'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->newrow();
        $this->hline(10,300,$this->liney,'C');
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