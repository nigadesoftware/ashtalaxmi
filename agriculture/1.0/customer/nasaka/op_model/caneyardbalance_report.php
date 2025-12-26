<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class caneyardbalance extends swappreport
{
    public $balancedate;
    public $balancehour;
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
	    $this->pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Caneyard Balance');
        $this->pdf->SetKeywords('CNYRDBL_000.MR');
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
        $this->pdf->Output('CNYRDBL_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 15;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->textbox('दैनिक कॆनयार्ड शिल्लक ',195,10,'S','C',1,'siddhanta',13);
        $this->newrow(7);
        $this->textbox('दिनांक : '.$this->balancedate.' वॆळ :'.$this->balancehour.' आखॆर',195,10,'S','C',1,'siddhanta',12);
        $this->newrow(7);
        $this->hline(10,100,$this->liney,'C');
        $this->setfieldwidth(30,10);
        $this->textbox('वाहन प्रकार',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox('संख्या',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->setfieldwidth(35);
        $this->textbox('मॆ.टन',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->newrow(7);
        $this->hline(10,100,$this->liney-2,'C'); 
    }
   
    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        $this->hline(10,100,$this->liney+6,'C'); 
        $liney = $this->liney;
        $this->liney = 41;
        $this->vline($this->liney-12,$this->liney+$limit,10);
        $this->vline($this->liney-12,$this->liney+$limit,40);
        $this->vline($this->liney-12,$this->liney+$limit,65);
        $this->vline($this->liney-12,$this->liney+$limit,100);
        $this->liney = $liney;
    }

    function pagefooter($islastpage=false)
    {
    }

    function detail()
    { 
        //$this->newrow();
        $this->liney = 35;
        $dt = DateTime::createFromFormat('d/m/Y',$this->balancedate)->format('d-M-Y').' '.$this->balancehour;
        $query = "select z.vehiclecategorycode,c.vehiclecategorynameuni,z.sankhya,z.sankhya*c.avgtonage as tonnage
        from (select b.vehiclecategorycode, count(b.vehiclecategorycode) sankhya
         from (
        select case when nvl(v.vehiclecategorycode,0)>0  then v.vehiclecategorycode else 3 end vehiclecategorycode from caneyardtoken k,vehicle v
        where k.transactionnumber in(
        select t.transactionnumber from  caneyardtoken t where t.seasoncode=".$_SESSION['yearperiodcode']." and t.tokendate<= to_date('".$dt."','dd-mm-yyyy HH24')
        minus
        select w.tokentransactionnumber transactionnumber from weightslip w where  w.seasoncode=".$_SESSION['yearperiodcode']." and nvl(w.netweight,0)>0  and w.emptydatetime<= to_date('".$dt."','dd-mm-yyyy HH24')
        group by w.tokentransactionnumber)
        and k.vehiclecode=v.vehiclecode(+)
        and k.seasoncode=v.seasoncode(+)) b
        group by   b.vehiclecategorycode)z,vehiclecategory c
        where z.vehiclecategorycode=c.vehiclecategorycode";
        $sankhya_tot=0;
        $tonnage_tot=0;
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        while ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
            $this->setfieldwidth(30,10);
            $this->textbox($row['VEHICLECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
            $this->setfieldwidth(25);
            $this->textbox($this->numformat($row['SANKHYA'],0),$this->w,$this->x,'S','R',1,'siddhanta',11);
            $this->setfieldwidth(35);
            $this->textbox($this->numformat($row['TONNAGE'],3),$this->w,$this->x,'S','R',1,'siddhanta',11);
            $this->newrow();
            $this->hline(10,100,$this->liney-2,'C');
            $sankhya_tot=$sankhya_tot+$row['SANKHYA'];
            $tonnage_tot=$tonnage_tot+$row['TONNAGE'];
        }
        $this->setfieldwidth(30,10);
        $this->textbox('एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($sankhya_tot,0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(35);
        $this->textbox($this->numformat($tonnage_tot,3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->drawlines($this->liney-35); 
        // todate total End
    }
}    
?>