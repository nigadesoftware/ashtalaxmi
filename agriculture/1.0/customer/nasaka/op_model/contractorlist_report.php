<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class contractorlist extends swappreport
{	
    //public $bankcode;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7,$papersizecode=3,$orientationcode=2,$reportname='Report',$reportfile='report')
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $this->reportfile= $reportfile;
        $this->reportname= $reportname;
        if ($papersizecode==1)
        {
            $this->papersize = 'A4';
        }
        elseif ($papersizecode==2)
        {
            $this->papersize = 'A3';
        }
        elseif ($papersizecode==3)
        {
            $this->papersize = 'Legal';
        }
        if ($orientationcode==1)
        {
            $this->orientation = 'P';
        }
        elseif ($orientationcode==2)
        {
            $this->orientation = 'L';
        }
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueType', '', 32);
    	// create new PDF document
	    $this->pdf = new MYPDF($this->orientation, PDF_UNIT, $this->papersize, true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
          // set document information
        $this->pdf->SetCreator('SwappERP');
        $this->pdf->SetAuthor('SwappERP');
        $this->pdf->SetTitle($reportname);
        $this->pdf->SetSubject($reportname);
        if ($_SESSION['lng']=="English")
        {
            $this->pdf->SetKeywords($this->reportfile.'_000.EN');
        }
        else
        {
            $this->pdf->SetKeywords($this->reportfile.'_000.MR');
        }
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
        if ($_SESSION['lng']=="English")
        {
            $lg['a_meta_language'] = 'en';
            $lg['w_page'] = 'Page - ';
        }
        else
        {
            $lg['a_meta_language'] = 'mr';
            $lg['w_page'] = 'पान - ';
        }
        // set some language-dependent strings (optional)
	    $this->pdf->setLanguageArray($lg);
	}

    function endreport()
    {
        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output($this->reportfile.'_000.pdf', 'I');
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
        $this->textbox($this->reportname,280,10,'S','C',1,'siddhanta',13);
        $this->newrow(2);
        $this->hline(10,290,$this->liney+6,'C');
        $this->newrow(7);
        $this->setfieldwidth(20,10);
        $this->textbox('कोड नं ',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(70);
        $this->textbox('नाव',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(55);
        $this->textbox('पत्ता',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(30);
        $this->textbox('पॅन नंबर ',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(30);
        $this->textbox('आधार नंबर ',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(30);
        $this->textbox('खाते नंबर ',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(60);
        $this->textbox('बँक',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->hline(10,290,$this->liney+6,'C');
        $this->newrow();
        //$this->drawlines($limit);
    }
   
    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        $liney = $this->liney;
        $this->liney = 85;
        $this->hline(10,290,$this->liney-12);
        $this->vline($this->liney-12,$this->liney+$limit,10);
        $this->vline($this->liney-12,$this->liney+$limit,20);
        $this->vline($this->liney-12,$this->liney+$limit,75);
        $this->vline($this->liney-12,$this->liney+$limit,90);
        $this->vline($this->liney-12,$this->liney+$limit,110);
        $this->hline(10,290,$this->liney+$limit);
        $this->hline(10,290,$this->liney+$limit);
        $this->liney = $liney;
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

    function detail()
    {  
        //$this->textbox('विभाग :'.$row1['DIVISIONNAMEUNI'].' गट : '.$row2['CIRCLENAMEUNI'].' गाव : '.$row3['VILLAGENAMEUNI'].' सभासदत्व : '.$row4['FARMERCATEGORYNAMEUNI'],200,10,'S','L',1,'siddhanta',12);
        $query2 ="select t.contractorcode
            ,t.contractornameeng
            ,t.contractornameuni
            ,t.address
            ,t.pannumber
            ,t.aadharnumber
            ,b.bankbranchnameuni
            ,b.bankbranchnameeng
            ,accountnumber 
            from contractor t,nst_nasaka_agriculture.bankbranch b
            where t.bankbranchcode=b.bankbranchcode(+)
            order by t.contractornameuni";
        $result2 = oci_parse($this->connection, $query2);
        $r2 = oci_execute($result2);
        $cnt =0;
        while ($row2 = oci_fetch_array($result2,OCI_ASSOC+OCI_RETURN_NULLS))
        {     
            $this->setfieldwidth(20,10);
            $this->textbox($row2['CONTRACTORCODE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(70);
            $h1 = $this->textbox($row2['CONTRACTORNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','Y');
            $this->setfieldwidth(55);
            $h2= $this->textbox($row2['ADDRESS'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(30);
            $this->textbox($row2['PANNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(30);
            $this->textbox($row2['AADHARNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(30);
            $this->textbox($row2['ACCOUNTNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
            $this->setfieldwidth(60);
            $h3= $this->textbox($row2['BANKBRANCHNAMEUNI'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
            
            if ($h1>5)
            {
                $this->newrow($h1);
            }
            elseif ($h2>5)
            {
                $this->newrow($h2);
            }    
            elseif ($h3>5)
            {
                $this->newrow($h3);
            }
            else
            {
                    $this->newrow(5); 
            }
            
        }  
        $this->hline(10,290,$this->liney,'C');    
    }
}    
?>
