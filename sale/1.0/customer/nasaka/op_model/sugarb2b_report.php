<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_l.php");
    include_once("../info/routine.php");
    include_once("../ip_model/saleinvoiceheader_db_oracle.php");
    include_once("../ip_model/goodspurchaser_db_oracle.php");
//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class sugarb2b extends swappreport
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
	    $this->pdf = new MYPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
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
        //$this->drawlines($limit);
    }
   
    function drawlines($limit)
    {
    
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
    
    }

    function export()
    {
           $name='b2b';
           $fp = fopen('../../../../../../exportb2b/'.$name.'.json', 'w');
           $fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');
           $query = "select 
           b.gst_pan
           ,b.invoicenumber
           ,b.invoicedate
           ,sum(b.grossamount) grossamount
           ,b.statename
           ,b.rc
           ,b.type
           ,b.ecom
           ,rate
           ,sum(b.taxableamount) taxableamount
           ,sum(b.cessamount) cessamount 
           from
                (select 
                case when p.gstinnumber is null then p.pannumber else p.gstinnumber end gst_pan
                ,i.invoicenumberpresuf invoicenumber
                ,to_char(i.invoicedate,'dd-mon-yyyy') invoicedate
                ,d.grossamount
                ,to_char(p.statecode)||'-'||trim(s.statenameeng) statename
                ,'N' rc
                ,'Regular' type
                ,null ecom
                ,nvl(d.cgstrate,0)+nvl(d.sgstrate,0)+nvl(d.igstrate,0)+nvl(d.ugstrate,0) rate
                ,nvl(d.amount,0) taxableamount
                ,0 as cessamount
                from saleinvoiceheader i
                ,saleinvoicedetail d
                ,goodspurchaser p
                ,nst_nasaka_db.state s
                where i.transactionnumber=d.transactionnumber 
                and i.invoicedate>='".$this->fromdate."' 
                and i.invoicedate<='".$this->todate."'
                and i.purchasercode= p.purchasercode 
                and to_number(p.statecode) = s.statecode
                and p.purchasercode not in (13,14,15,17)) b
                group by b.gst_pan,b.invoicenumber,b.invoicedate,b.statename,b.rc,b.type, b.ecom,rate
                order by b.invoicenumber";
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $response = array();
           fputcsv($fp1, array('GSTIN/UIN of Recipient','Invoice Number','Invoice date','Invoice Value','Place Of Supply','Reverse Charge','Invoice Type','E-Commerce GSTIN','Rate','Taxable Value','Cess Amount'));
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                $response[] = array('GSTIN/UIN of Recipient'=>$row['GST_PAN']
                ,'Invoice Number'=>$row['INVOICENUMBER']
                ,'Invoice date'=>$row['INVOICEDATE']
                ,'Invoice Value'=>$row['GROSSAMOUNT']
                ,'Place Of Supply'=>$row['STATENAME']
                ,'Reverse Charge'=>$row['RC']
                ,'Invoice Type'=>$row['TYPE']
                ,'E-Commerce GSTIN'=>$row['ECOM']
                ,'Rate'=>$row['RATE']
                ,'Taxable Value'=>$row['TAXABLEAMOUNT']
                ,'Cess Amount'=>$row['CESSAMOUNT']
                );
                fputcsv($fp1, $row, $delimiter = ',', $enclosure = '"');
           } 
            fwrite($fp, json_encode($response));
            fclose($fp);
            fclose($fp1);
    }
 
}    
?>
