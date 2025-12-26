<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_A4_P.php");
    include_once("../info/routine.php");
//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class village extends swappreport
{	
    public $circlecode;
    public $fromdate;
    public $todate;
    Public $distance;
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
        $this->textbox('गाव यादी ',250,10,'S','C',1,'siddhanta',13);
        $this->newrow(2);
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow(7);
        $this->textbox('कोड नं ',20,10,'S','L',1,'siddhanta',10);
        $this->textbox('नाव',50,35,'S','L',1,'siddhanta',10);
        $this->textbox('ट्रक अंतर',20,80,'S','R',1,'siddhanta',10);
        $this->textbox('बैलगाडी अंतर',30,100,'S','R',1,'siddhanta',10);
        $this->textbox('गट',25,130,'S','R',1,'siddhanta',10);
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow();
        //$this->drawlines($limit);
    }
   
    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        $liney = $this->liney;
        $this->liney = 85;
        $this->hline(10,200,$this->liney-12);
        $this->vline($this->liney-12,$this->liney+$limit,10);
        $this->vline($this->liney-12,$this->liney+$limit,20);
        $this->vline($this->liney-12,$this->liney+$limit,75);
        $this->vline($this->liney-12,$this->liney+$limit,90);
        $this->vline($this->liney-12,$this->liney+$limit,110);
        $this->vline($this->liney-12,$this->liney+$limit,140);
        $this->vline($this->liney-5,$this->liney+$limit,150);
        $this->vline($this->liney-12,$this->liney+$limit,170);
        $this->vline($this->liney-5,$this->liney+$limit,180);
        $this->vline($this->liney-12,$this->liney+$limit,200);
        $this->hline(10,200,$this->liney+$limit);
        $this->hline(10,200,$this->liney+$limit);
        $this->hline(140,200,$this->liney-5);
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
        $cond='1=1';
        if ($this->distance !='')
        {
            $cond = $cond.' and (nvl(distancetrucktractor,0)='.$this->distance
            .' or nvl(distancetyregadi,0)='.$this->distance.')';
        }
        if ($this->circlecode==0)
        {
            $query ="select v.villagecode
        ,v.villagenameuni
        ,v.distancetrucktractor
        ,v.distancetyregadi
        ,s.circlenameuni 
        from village v,circle s 
        where v.circlecode=s.circlecode 
        and {$cond} order by s.circlecode,v.villagenameuni";
        }
        else
        {
            $query ="select v.villagecode
        ,v.villagenameuni
        ,v.distancetrucktractor
        ,v.distancetyregadi
        ,s.circlenameuni 
        from village v,circle s 
        where v.circlecode=s.circlecode  
        and s.circlecode={$this->circlecode}
        and {$cond}
        order by s.circlecode,v.villagenameuni";
        }
        
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->textbox($row['VILLAGECODE'],20,10,'S','L',1,'siddhanta',12);
            $this->textbox($row['VILLAGENAMEUNI'],70,30,'S','L',1,'siddhanta',12);
            $this->textbox(moneyFormatIndia($row['DISTANCETRUCKTRACTOR']),20,75,'S','R',1,'SakalMarathiNormal922',12);
            $this->textbox(moneyFormatIndia($row['DISTANCETYREGADI']),20,115,'S','L',1,'SakalMarathiNormal922',12);
            $this->textbox($row['CIRCLENAMEUNI'],50,140,'S','L',1,'siddhanta',12);
            $this->newrow(5);    
        }
        $this->hline(10,200,$this->liney+$limit);
    }
}    
?>
