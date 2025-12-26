<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    include_once("../ip_model/salememoheader_db_oracle.php");
    include_once("../ip_model/goodspurchaser_db_oracle.php");
//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class tenderallotment extends swappreport
{	
    public $goodscategorycode;
    public $fromdate;
    public $todate;
    public $tendertransactionnumber;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $this->tendertransactionnumber = 0;
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
        $this->pdf->SetSubject('Tender Allotment');
        $this->pdf->SetKeywords('TENALL_000.EN');
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
        $this->pdf->Output('TENALL_000.pdf', 'I');
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
        $this->newrow(15);
        $this->textbox('Tender Allotment Report',180,10,'S','C',1,'verdana',13);
        $this->newrow(8);
        $this->textbox('for the period from '.DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y').' to '.DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y'),180,10,'S','C',1,'verdana',11);
        $this->newrow(10);
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
        if ($this->tendertransactionnumber == 0)
        {
            $query ="select h.transactionnumber,h.tendernumberpresuf,h.tenderdate
            from saletenderheader h
            where h.tenderdate>='".$this->fromdate."'
            and h.tenderdate<='".$this->todate."'
            order by tenderdate,tendernumber";
        }
        else
        {
            $query ="select h.transactionnumber,h.tendernumberpresuf,h.tenderdate
            from saletenderheader h
            where h.tenderdate>='".$this->fromdate."'
            and h.tenderdate<='".$this->todate."' 
            and h.transactionnumber=".$this->tendertransactionnumber."
            order by tenderdate,tendernumber";
        }
        
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->textbox('Tender No:'.$row['TENDERNUMBERPRESUF'],50,10,'S','L',1,'verdana',11);
            $this->textbox('Tender Date:'.DateTime::createFromFormat('d-M-Y',$row['TENDERDATE'])->format('d/m/Y'),60,60,'S','L',1,'verdana',11);
            $this->hline(10,220,$this->liney+6,'C');
            $this->newrow();
            $this->textbox('Order No',20,10,'S','L',1,'verdana',10);
            $this->textbox('Code',15,30,'S','L',1,'verdana',10);
            $this->textbox('Broker',75,45,'S','L',1,'verdana',10);
            $this->textbox('Prod Sea',20,120,'S','L',1,'verdana',10);
            $this->textbox('Grade',25,140,'S','L',1,'verdana',10);
            $this->textbox('Rate',25,165,'S','R',1,'verdana',10);
            $this->textbox('Qty',25,180,'S','R',1,'verdana',10);
            $this->hline(10,300,$this->liney+6,'C');
            $this->newrow();

            $query1_0 ="select 
            d.productionyearcode
            ,f.finishedgoodscode
            from saleorderheader h
            ,saleorderdetail d
            ,goodspurchaser p
            ,finishedgoods f
            where h.transactionnumber=d.transactionnumber
            and h.purchasercode=p.purchasercode
            and d.finishedgoodscode=f.finishedgoodscode
            and h.tendertransactionnumber=".$row['TRANSACTIONNUMBER']."
            group by d.productionyearcode
            ,f.finishedgoodscode";
            $result1_0 = oci_parse($this->connection, $query1_0);
            $r1_0 = oci_execute($result1_0);
            $gqty=0;
            while ($row1_0 = oci_fetch_array($result1_0,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                $query1 ="select p.purchasercode
                ,p.purchasernameeng
                ,f.shortname
                ,d.productionyearcode
                ,h.saleordernumberpresuf
                ,d.orderrate
                ,d.orderquantity
                from saleorderheader h
                ,saleorderdetail d
                ,goodspurchaser p
                ,finishedgoods f
                where h.transactionnumber=d.transactionnumber
                and h.purchasercode=p.purchasercode
                and d.finishedgoodscode=f.finishedgoodscode
                and h.tendertransactionnumber=".$row['TRANSACTIONNUMBER']."
                and d.productionyearcode=".$row1_0['PRODUCTIONYEARCODE']." 
                and f.finishedgoodscode=".$row1_0['FINISHEDGOODSCODE']." 
                order by saleordernumber";
                $result1 = oci_parse($this->connection, $query1);
                $r1 = oci_execute($result1);
                $qty=0;
               
                while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    $this->textbox($row1['SALEORDERNUMBERPRESUF'],20,10,'S','L',1,'verdana',9);
                    $this->textbox($row1['PURCHASERCODE'],15,30,'S','L',1,'verdana',9);
                    $this->textbox($row1['PURCHASERNAMEENG'],75,45,'S','L',1,'verdana',9);
                    $this->textbox($row1['PRODUCTIONYEARCODE'],20,120,'S','L',1,'verdana',9);
                    $this->textbox($row1['SHORTNAME'],35,140,'S','L',1,'verdana',9);
                    $this->textbox($row1['ORDERRATE'],25,165,'S','R',1,'verdana',9);
                    $this->textbox($row1['ORDERQUANTITY'],25,180,'S','R',1,'verdana',9);
                    $qty=$qty+$row1['ORDERQUANTITY'];
                    $gqty=$gqty+$row1['ORDERQUANTITY'];
                    $this->newrow();
                }
                $this->hline(10,300,$this->liney,'C'); 
                $this->textbox('Total',75,45,'S','L',1,'verdana',9);
                $this->textbox($qty,25,180,'S','R',1,'verdana',9);
                $this->newrow();
                $this->hline(10,300,$this->liney,'C'); 

            }
            $this->textbox('Grand Total',75,45,'S','L',1,'verdana',9);
            $this->textbox($gqty,25,180,'S','R',1,'verdana',9);
            $this->newrow();
            $this->hline(10,300,$this->liney,'C'); 

        }
        $this->hline(10,300,$this->liney,'C');   
        $this->newrow(15);
        $this->textbox('Sugar Clerk         Sugarsale Incharge           Chief Accountant            General Manager',180,30,'S','L',1,'verdana',10); 
    }
}    
?>
