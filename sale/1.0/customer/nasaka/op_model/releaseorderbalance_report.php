<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class releaseorderbalance extends swappreport
{	
    public $goodscategorycode;
    public $permissiontransactionnumber;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->brokercode=0;
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
        $this->pdf->SetSubject('Release Orderwise Tenderwise Balance');
        $this->pdf->SetKeywords('ROBL_000.EN');
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
        $lg['a_meta_language'] = 'en';
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
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        $this->newrow(5);
        $this->textbox('Release Orderwise Tenderwise Balance',180,10,'S','C',1,'verdana',11);
        $this->newrow(10);
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow(7);
        $this->textbox('Tender No.',30,10,'S','L',1,'verdana',10);
        $this->textbox('Tender Dt',30,40,'S','L',1,'verdana',10);
        $this->textbox('Tender Qty',30,70,'S','R',1,'verdana',10);
        $this->textbox('Sale Qty',30,100,'S','R',1,'verdana',10);
        $this->textbox('Balance Qty',30,130,'S','R',1,'verdana',10);
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
        $query ="select 
        transactionnumber permissiontransactionnumber
        ,t.permissionnumber
        ,t.permissiondate
        ,t.permissionquntity 
        from goodssalepermission t
        where transactionnumber=".$this->permissiontransactionnumber." order by t.permissiondate";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $tenderquantity=0;
        $salequantity=0;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->textbox('R.O.No.: '.$row['PERMISSIONNUMBER'],110,10,'S','L',1,'verdana',10);
            $this->textbox('Date: '.DateTime::createFromFormat('d-M-y',$row['PERMISSIONDATE'])->format('d/m/Y'),40,120,'S','L',1,'verdana',9);
            $this->textbox('Quantity: '.$row['PERMISSIONQUNTITY'],40,150,'S','R',1,'verdana',9);
            $this->newrow();
            $query1 ="select b.tendernumberpresuf
                    ,h.tenderdate
                    ,sum(b.orderquantity) tenderquantity
                    ,sum(b.salequantity) salequantity
                    ,sum(b.balancequantity) balancequantity
                from brokerwiseliftedunlifted b,finishedgoods d,saletenderheader h
                where b.finishedgoodscode=d.finishedgoodscode 
                and b.tendertransactionnumber=h.transactionnumber
                and h.permissiontransactionnumber=".$row['PERMISSIONTRANSACTIONNUMBER'].
                " group by b.tendernumberpresuf,h.tenderdate
                order by b.tendernumberpresuf";
               
            $result1 = oci_parse($this->connection, $query1);
            $r1 = oci_execute($result1);
            $sqty =0;
            $ssqty =0;
            $sbqty =0;
            while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                $this->textbox($row1['TENDERNUMBERPRESUF'],30,10,'S','L',1,'verdana',9);
                $this->textbox(DateTime::createFromFormat('d-M-y',$row1['TENDERDATE'])->format('d/m/Y'),30,40,'S','L',1,'verdana',9);
                $this->textbox($row1['TENDERQUANTITY'],30,70,'S','R',1,'verdana',9);
                $this->textbox($row1['SALEQUANTITY'],30,100,'S','R',1,'verdana',9);
                $this->textbox($row1['BALANCEQUANTITY'],30,130,'S','R',1,'verdana',9);
                $sqty=$sqty+$row1['TENDERQUANTITY'];
                $ssqty=$ssqty+$row1['SALEQUANTITY'];
                $sbqty=$sbqty+$row1['BALANCEQUANTITY'];
                $this->newrow();
                $tenderquantity=$tenderquantity+$row1['TENDERQUANTITY'];
                $salequantity=$salequantity+$row1['SALEQUANTITY'];

            }
            $this->hline(10,200,$this->liney,'C');    
            $this->textbox($sqty,30,70,'S','R',1,'verdana',9);
            $this->textbox($ssqty,30,100,'S','R',1,'verdana',9);
            $this->textbox($sbqty,30,130,'S','R',1,'verdana',9);
            $this->newrow();
            $this->hline(10,200,$this->liney,'C'); 
            $tenderallotmentbalance=$row['PERMISSIONQUNTITY']-$tenderquantity;
            $releaseordersalebalance=$row['PERMISSIONQUNTITY']-$salequantity;
            $this->newrow();
            $this->textbox('Tender Allotment Balance:',50,10,'S','L',1,'verdana',10);
            $this->textbox($tenderallotmentbalance,30,60,'S','R',1,'verdana',10);
            $this->newrow();    
            $this->textbox('Tender Quantity:',50,10,'S','L',1,'verdana',10);
            $this->textbox($tenderquantity,30,60,'S','R',1,'verdana',10);
            $this->newrow(); 
            $this->textbox('Sale Quantity:',50,10,'S','L',1,'verdana',10);
            $this->textbox($salequantity,30,60,'S','R',1,'verdana',10);
            $this->newrow();
            $this->textbox('Balance Quantity:',50,10,'S','L',1,'verdana',10);
            $this->textbox($releaseordersalebalance,30,60,'S','R',1,'verdana',10);
        }
        $this->newrow(15);
        $this->textbox('Sugar Sale Accountant',50,50,'S','C',1,'verdana',11);
        $this->textbox('Chief Accountant',50,130,'S','C',1,'verdana',11);
    }
}    
?>
