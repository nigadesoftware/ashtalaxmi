<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_A7_L.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class caneyardtoken extends swappreport
{
    public $transactionnumber;
    public $tokenbasecode;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueType', '', 32);
    	// create new PDF document
	    $this->pdf = new MYPDF('L', PDF_UNIT, 'A7', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Token');
        $this->pdf->SetKeywords('TOKEN_000.MR');
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));
       // $title = str_pad(' ', 30).'Rajaramnagar, Tal - Dindori Dist - Nashik';
    	//$this->pdf->SetHeaderData('', 0,str_pad(' ', 21).'Kadwa S.S.K. Ltd.' ,$title);
	// set margins
        $this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->pdf->SetHeaderMargin(9);
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
        $this->pdf->Output('TOKEN_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 10;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
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
        //$this->newrow(10);
        $this->liney = 20;
        //if ($this->tokenbasecode == 1)
        //{
            $this->textbox('टोकन',80,10,'S','C',1,'siddhanta',13);
        /* }
        elseif ($this->tokenbasecode == 2)
        {
            $this->textbox('बैलगाडी टोकन',80,10,'S','C',1,'siddhanta',13);
        } */
        $this->newrow(7);
        $this->hline(10,210,$this->liney,'C');
        $this->newrow(3);
        if ($this->tokenbasecode == 1)
        {
            $query = "select to_char(t.tokendate,'DD/MM/YYYY HH:MI:SS AM') as tokendate,v.vehiclecategorycode,c.vehiclecategorynameuni,v.vehiclenumber
            ,t.tokennumberprefix,t.tokennumber
            from caneyardtoken t,vehicle v,vehiclecategory c 
            where t.seasoncode=v.seasoncode and t.vehiclecode=v.vehiclecode and v.vehiclecategorycode=c.vehiclecategorycode
            and t.seasoncode=".$_SESSION['yearperiodcode']."
            and t.transactionnumber=".$this->transactionnumber;
        }
        elseif ($this->tokenbasecode == 2)
        {
            $query = "select to_char(t.tokendate,'DD/MM/YYYY HH:MI:SS AM') as tokendate,3 as vehiclecategorycode,'बैलगाडी' as vehiclecategorynameuni
            ,v.tyregadinumber as vehiclenumber,t.tokennumberprefix,t.tokennumber
            from caneyardtoken t,tyregadi v 
            where t.seasoncode=v.seasoncode and t.tyregadicode=v.tyregadicode 
            and t.seasoncode=".$_SESSION['yearperiodcode']."
            and t.transactionnumber=".$this->transactionnumber
            ." union all 
            select to_char(t.tokendate,'DD/MM/YYYY HH:MI:SS AM') as tokendate,v.vehiclecategorycode
            ,c.vehiclecategorynameuni,v.vehiclenumber,t.tokennumberprefix,t.tokennumber
            from caneyardtoken t,vehicle v,vehiclecategory c 
            where t.seasoncode=v.seasoncode and t.vehiclecode=v.vehiclecode and v.vehiclecategorycode=c.vehiclecategorycode
            and t.seasoncode=".$_SESSION['yearperiodcode']."
            and v.vehiclecategorycode=4
            and t.transactionnumber=".$this->transactionnumber;
        }
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->textbox('टोकन नंबर:',35,10,'S','L',1,'siddhanta',11);
            $this->newrow(-2);
            $this->textbox($row['TOKENNUMBERPREFIX'],100,35,'S','L',1,'siddhanta',15);
            $this->newrow(2);
            $this->newrow();
            $this->textbox('टोकन दिनांक व वेळ:'.$row['TOKENDATE'],170,10,'S','L',1,'siddhanta',11);
            $this->newrow();
            $this->textbox('वाहन प्रकार:'.$row['VEHICLECATEGORYNAMEUNI'],70,10,'S','L',1,'siddhanta',11);
            $this->newrow();
            //if ($this->tokenbasecode == 1)
            //{
                $this->textbox('वाहन/गाडी नंबर:',35,10,'S','L',1,'siddhanta',11);
                $this->textbox($row['VEHICLENUMBER'],70,45,'S','L',1,'siddhanta',13);
            //}
            //elseif ($this->tokenbasecode == 2)
            //{
                //$this->textbox('बैलगाडी नंबर:',70,10,'S','L',1,'siddhanta',11);
                //$this->textbox($row['VEHICLENUMBER'],70,35,'S','L',1,'siddhanta',15);
            //}
            $this->newrow();
        }
        $this->hline(10,300,$this->liney,'C');  
        $this->newrow();
        //$this->textbox('नंबर टेकर',55,60,'S','L',1,'siddhanta',12);  
        //$this->hline(10,300,$this->liney,'C');    
    }
}    
?>