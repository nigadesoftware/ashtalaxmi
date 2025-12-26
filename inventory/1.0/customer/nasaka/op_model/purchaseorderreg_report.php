<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class purchaseorderregister extends reportbox
{	
    Public $fromdate;
    Public $todate;
    Public $suppliercode;
    Public $employeecode;
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
	    $this->pdf = new MYPDF('L', PDF_UNIT, 'A3', true, 'UTF-8', false);
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
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        $this->newrow(10);
        $this->setfieldwidth(55,180);
        $this->textbox('Purchase Order Register',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->newrow(10);
        $this->hline(10,405,$this->liney+6,'C');
        $this->newrow(7);
        $this->setfieldwidth(30,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Trans. No.',$this->w,$this->x,'S','L',1,'verdana',11);
        //$this->newrow(2);
        //$this->hline(10,405,$this->liney+6,'C');
        //$this->newrow(7);

        $this->setfieldwidth(25);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Financial Year',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->textbox('PO Number',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->textbox('PO Date',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(55);
        $this->textbox('Prefix PO No',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->textbox('Quotation No',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(100);
        $this->textbox('Supplier',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Employee',$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->hline(10,405,$this->liney+6,'C');
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
        $this->totalgroupcount=1;
        $this->amount = 0;
        $cond='1=1';
        if ($this->fromdate != '' and $this->todate != '')
        {
         /*    $fdt=DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $tdt=DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
         */ 
        $frdt=DateTime::createFromFormat('Y-m-d',$this->fromdate)->format('d-M-Y');
        $todt=DateTime::createFromFormat('Y-m-d',$this->todate)->format('d-M-Y');
        $cond= $cond."and purchesorderdate>='".$frdt."' and purchesorderdate<='".$todt."'";
        }
            
        $group_query_1 ="select transactionnumber, financialyear, purchesordernumber, to_char(purchesorderdate,'dd/mm/yyyy') purchesorderdate, purchesorderfrefixnumber, quottransactionnumber, s.suppliercode, employeecode
        ,S.SUPPLIERNAMEENG 
        from purchesorderheader t,supplier s 
        where {$cond} 
        and t.suppliercode=s.suppliercode
        order by transactionnumber"; 
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        //$this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->hline(10,405,$this->liney,'D'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
    }
    
    function groupheader_1(&$group_row_1)
    {
        
    }


    function groupheader_2(&$group_row_1)
    {
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
        $this->setfieldwidth(30,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($group_row_1['TRANSACTIONNUMBER'],$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['FINANCIALYEAR'],$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->textbox($group_row_1['PURCHESORDERNUMBER'],$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->textbox($group_row_1['PURCHESORDERDATE'],$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);


        $this->setfieldwidth(55);
        $this->textbox($group_row_1['PURCHESORDERFREFIXNUMBER'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->textbox($group_row_1['QUOTTRANSACTIONNUMBER'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(100);
        $this->textbox($group_row_1['SUPPLIERCODE'].' '.$group_row_1['SUPPLIERNAMEENG'],$this->w,$this->x,'S','',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['EMPLOYEECODE'],$this->w,$this->x,'S','',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D'); 
        }
        $this->setfieldwidth(30,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Sr.No.',$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Item Code',$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(265);
        $this->textbox('Item Description',$this->w,$this->x,'S','L',1,'verdana',11,'','Y','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Quantity',$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Rate',$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Amount',$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->hline(10,405,$this->liney+6,'D');
        //$this->newrow();

        $group_query_2 ="select t.serialnumber,t.itemcode,i.itemnameeng,t.itemspcification
        ,u.unitnameeng,t.quantity,t.rate,t.amount
        from purchesorderitemdetail t,item i,unit u
        where t.transactionnumber=".$group_row_1['TRANSACTIONNUMBER']."
        and t.itemcode=i.itemcode
        and i.unitcode=u.unitcode(+)
        order by transactionnumber,serialnumber"; 
        
        $group_result_2 = oci_parse($this->connection, $group_query_2);
        $r = oci_execute($group_result_2);
        $i=0;
        //$this->newpage(true);
        $this->newrow(7);
        while ($group_row_2 = oci_fetch_array($group_result_2,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_2,$last_row_2);
            $this->hline(10,405,$this->liney+6,'D');
            
            //$this->setfieldwidth(30,10);
            //$this->vline($this->liney-1,$this->liney+6,$this->x);
            //$this->textbox('व्यवहार क्रमांक',$this->w,$this->x,'S','L',1,'verdana',11);
            //$this->newrow(2);
            //$this->hline(10,405,$this->liney+6,'C');
            //$this->newrow(7);

            $this->detail_2($group_row_2);
            //$this->hline(10,405,$this->liney,'D'); 
            $last_row_2=$group_row_2;
        }
        $this->setfieldwidth(30,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        //$this->textbox($group_row_1['SERIALNUMBER'],$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        //$this->textbox($group_row_1['ITEMCODE'],$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(265);
        $this->textbox('Total',$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        //$this->textbox($group_row_1['QUANTITY'],$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        //$this->textbox($group_row_1['RATE'],$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($this->amount,$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-1,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            //$this->hline(10,405,$this->liney,'D');
        }
    }
    function detail_2(&$group_row_1)
    {
        $this->setfieldwidth(30,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($group_row_1['SERIALNUMBER'],$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['ITEMCODE'],$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(265);
        $this->textbox($group_row_1['ITEMNAMEENG'].' '.$group_row_1['ITEMSPCIFICATION'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['QUANTITY'],$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['RATE'],$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['AMOUNT'],$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->amount+=$group_row_1['AMOUNT'];
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
        }
    }
    function groupfooter_1(&$group_row_1)
    { 
        

    }
    function groupfooter_2(&$group_row_2)
    {  
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
    }
    function export()
    {
        $cond='1=1';
        if ($this->fromdate != '' and $this->todate != '')
        {
          
            $fdt=DateTime::createFromFormat('Y-m-d',$this->fromdate)->format('d-M-Y');
            $tdt=DateTime::createFromFormat('Y-m-d',$this->todate)->format('d-M-Y');
            $cond= $cond.'and purchesorderdate>='.$fdt.' and purchesorderdate<='.$tdt;
        }

            $group_query_1 ="select ROW_NUMBER() Over (Order by h.purchesordernumber) As sr_no
            ,h.purchesordernumber        
            ,h.suppliercode
            ,s.suppliernameeng
            ,s.address
            ,s.mobilenumber
            ,s.contactpersion
            ,s.emailid
            from purchesorderheader h
            ,supplier s
            where h.suppliercode=s.suppliercode
            and h.financialyear=".$_SESSION['yearperiodcode']."
            and h.purchesordernumber>='".$this->frompo."'
            and h.purchesordernumber<=".$this->topo."
            order by h.purchesordernumber";
           $result = oci_parse($this->connection, $group_query_1);
           $r = oci_execute($result);
           $response = array();
           $filename='PurchaseOrderList.csv';
           //$fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');
           $fp1=fopen('php://memory', 'w');
           fputcsv($fp1, array('Serial No','PO Order Number','Supplier Code','Suppiler Name','Address','Contact Person','Mobile No','E-mail Id'));
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
               // $acno="'".$row['ACCOUNTNUMBER'];
                fputcsv($fp1, array($row['SR_NO'],$row['PURCHESORDERNUMBER'],$row['SUPPLIERCODE'],$row['SUPPLIERNAMEENG'],$row['ADDRESS'],$row['CONTACTPERSON'],$row['MOBILENUMBER'],$row['EMAILID']), $delimiter = ',', $enclosure = '"');
           }
           // reset the file pointer to the start of the file
            fseek($fp1, 0);
            // tell the browser it's going to be a csv file
            header('Content-Type: application/csv');
            // tell the browser we want to save it instead of displaying it
            header('Content-Disposition: attachment; filename="'.$filename.'";');
            // make php send the generated csv lines to the browser
            fpassthru($fp1); 
            //fclose($fp1);

    }

}    
?>
