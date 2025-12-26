<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    include_once("../ip_model/goodspurchaser_db_oracle.php");
//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class tenderwisesaledetail extends swappreport
{	
    public $goodscategorycode;
    public $fromdate;
    public $todate;
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
        $this->pdf->SetSubject('Brokerwise Tenderwise Balance');
        $this->pdf->SetKeywords('BRTDBL_000.EN');
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
        $todate = DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y');
        $this->textbox('Brokerwise Tenderwise Balance Detail As on Date '.$todate,180,10,'S','C',1,'verdana',11);
        $this->newrow(10);
        $this->hline(10,300,$this->liney+6,'C');
        $this->newrow(7);
        $this->textbox('Tender No.',15,10,'S','L',1,'verdana',10);
        $this->textbox('Tender Dt',20,25,'S','L',1,'verdana',10);
        $this->textbox('Grade',20,55,'S','L',1,'verdana',10);
        $this->textbox('Prod Season',20,75,'S','L',1,'verdana',10);
        $this->textbox('Lifting Dt',20,95,'S','L',1,'verdana',10);
        $this->textbox('Order Rate',25,115,'S','R',1,'verdana',10);
        $this->textbox('Order Qty',20,140,'S','R',1,'verdana',10);
        $this->textbox('Sale Qty',20,160,'S','R',1,'verdana',10);
        $this->textbox('Balance Qty',20,180,'S','R',1,'verdana',10);
        $this->hline(10,300,$this->liney+6,'C');
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
        $query ="select b.tendertransactionnumber
                ,b.tendernumberpresuf
                ,b.tenderdate
        from brokerwiseliftedunlifted b
        where tenderdate>='".$this->fromdate."'
        and tenderdate<='".$this->todate."'
        group by b.tendertransactionnumber,b.tendernumberpresuf
                ,b.tenderdate
        order by b.tenderdate,b.tendernumberpresuf";
      
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->textbox($row['TENDERNUMBERPRESUF'],20,10,'S','L',1,'verdana',11);
            $this->textbox(DateTime::createFromFormat('d-M-y',$row['TENDERDATE'])->format('d/m/Y'),30,30,'S','L',1,'verdana',11);
            $this->newrow();
            $query1 = "select b.brokercode
                      ,p.purchasernameeng
                      from brokerwiseliftedunlifted b,goodspurchaser p
                      where b.brokercode=p.purchasercode
                      and b.tendertransactionnumber=".$row['TENDERTRANSACTIONNUMBER']." 
                      group by b.brokercode
                      ,p.purchasernameeng
                      order by purchasernameeng";
            $result1 = oci_parse($this->connection, $query1);
            $r1 = oci_execute($result1);
            while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                $this->textbox($row1['BROKERCODE'],15,20,'S','L',1,'verdana',10);
                $this->textbox($row1['PURCHASERNAMEENG'],80,35,'S','L',1,'verdana',10);
                $this->newrow();
                $query2 ="select b.finishedgoodscode
                ,d.shortname
                ,b.productionyearcode
                ,b.validdateoflifting
                ,b.orderquantity
                ,b.orderrate
                ,b.salequantity
                ,b.balancequantity
                from brokerwiseliftedunlifted b,finishedgoods d
                where b.finishedgoodscode=d.finishedgoodscode
                and b.tendertransactionnumber=".$row['TENDERTRANSACTIONNUMBER']."
                and b.brokercode=".$row1['BROKERCODE'].
                "order by d.shortname,b.productionyearcode";               
               
                $result2 = oci_parse($this->connection, $query2);
                $r2 = oci_execute($result2);
                while ($row2 = oci_fetch_array($result2,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    $this->textbox($row2['SHORTNAME'],20,55,'S','L',1,'verdana',9);
                    $this->textbox($row2['PRODUCTIONYEARCODE'],20,75,'S','L',1,'verdana',9);
                    $this->textbox(DateTime::createFromFormat('d-M-y',$row2['VALIDDATEOFLIFTING'])->format('d/m/Y'),25,95,'S','L',1,'verdana',9);
                    $this->textbox($row2['ORDERRATE'],25,115,'S','R',1,'verdana',9);
                    $this->textbox($row2['ORDERQUANTITY'],20,140,'S','R',1,'verdana',9);
                    $this->textbox($row2['SALEQUANTITY'],20,160,'S','R',1,'verdana',9);
                    $this->textbox($row2['BALANCEQUANTITY'],20,180,'S','R',1,'verdana',9);
                    $this->newrow();

                    $query3 ="select p.purchasercode
                        ,p.purchasernameeng
                        from saleinvoiceheader i,saleinvoicedetail d
                        ,salememodetail h,saletenderheader t,goodspurchaser p
                        where i.transactionnumber=d.transactionnumber
                        and i.salememotransactionnumber=h.transactionnumber
                        and h.tendertransactionnumber = t.transactionnumber
                        and i.purchasercode=p.purchasercode
                        and d.finishedgoodscode=".$row2['FINISHEDGOODSCODE']."
                        and d.productionyearcode=".$row2['PRODUCTIONYEARCODE']."
                        and d.salerate=".$row2['ORDERRATE']."
                        and i.brokercode=".$row1['BROKERCODE']."
                        and h.tendertransactionnumber=".$row['TENDERTRANSACTIONNUMBER']."
                        group by p.purchasercode
                        ,p.purchasernameeng
                        order by p.purchasernameeng";

                    $result3 = oci_parse($this->connection, $query3);
                    $r3 = oci_execute($result3);
                    $isinvoiced=false;
                    while ($row3 = oci_fetch_array($result3,OCI_ASSOC+OCI_RETURN_NULLS))
                    {    
                        $isinvoiced=true;
                        $this->textbox($row3['PURCHASERCODE'],20,75,'S','L',1,'verdana',9);
                        $this->textbox($row3['PURCHASERNAMEENG'],80,95,'S','L',1,'verdana',9);
                        $this->newrow(5);
                        $query4 ="select  p.purchasernameeng, i.invoicenumberpresuf
                        ,i.invoicedate
                        ,d.salequantity
                        from saleinvoiceheader i,saleinvoicedetail d
                        ,salememodetail h,saletenderheader t,goodspurchaser p
                        where i.transactionnumber=d.transactionnumber
                        and i.purchasercode=p.purchasercode
                        and i.salememotransactionnumber=h.transactionnumber
                        and h.tendertransactionnumber = t.transactionnumber
                        and d.finishedgoodscode=h.finishedgoodscode
                        and d.finishedgoodscode=".$row2['FINISHEDGOODSCODE']."
                        and d.productionyearcode=".$row2['PRODUCTIONYEARCODE']."
                        and d.salerate=".$row2['ORDERRATE']."
                        and i.purchasercode=".$row3['PURCHASERCODE']." 
                        and i.brokercode=".$row1['BROKERCODE']."
                        and h.tendertransactionnumber=".$row['TENDERTRANSACTIONNUMBER'].
                        " group by p.purchasernameeng, i.invoicenumberpresuf,i.invoicenumber
                        ,i.invoicedate
                        ,d.salequantity order by i.invoicedate,i.invoicenumber";

                        $result4 = oci_parse($this->connection, $query4);
                        $r4 = oci_execute($result4);
                        while ($row4 = oci_fetch_array($result4,OCI_ASSOC+OCI_RETURN_NULLS))
                        {                   
                            //$this->textbox($row4['PURCHASERNAMEENG'],50,50,'S','L',1,'verdana',9);
                            $this->textbox($row4['INVOICENUMBERPRESUF'],40,100,'S','L',1,'verdana',8);
                            $this->textbox(DateTime::createFromFormat('d-M-y',$row4['INVOICEDATE'])->format('d/m/Y'),25,140,'S','L',1,'verdana',9);
                            $this->textbox($row4['SALEQUANTITY'],20,160,'S','R',1,'verdana',9);
                            $this->newrow(5);
                        }
                    }
                    if ($isinvoiced==true)
                        $this->hline(75,180,$this->liney,'D');
                }
                $this->hline(10,300,$this->liney,'C');  
            }  
        }
        $this->newrow(15);
        $this->textbox('Sugar Sale Accountant',50,50,'S','C',1,'verdana',11);
        $this->textbox('Chief Accountant',50,130,'S','C',1,'verdana',11);
    }
}    
?>
