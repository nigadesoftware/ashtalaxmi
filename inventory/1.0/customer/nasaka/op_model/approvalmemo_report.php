<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_P.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class approvalmemo extends reportbox
{	
    Public $transactionnumber;
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
        //$resolution= array(80, 130);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Approval Memo');
        $this->pdf->SetKeywords('APMEMO_000.EN');
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
        $this->pdf->Output('APRMEM_000.pdf', 'I');
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
        
        $group_query_1 ="select h.transactionnumber,h.goodsreceiptnoteprefixnumber,to_char(h.goodreceiptdate,'dd/MM/yyyy')goodreceiptdate
        ,s.suppliercode,s.suppliernameeng,i.itemcode,i.itemnameeng,u.unitnameeng
        ,purchesorderfrefixnumber--,purchesorderdate
          ,to_char(purchesorderdate,'dd/MM/yyyy')purchesorderdate
        ,d.quantity,d.acceptedquantity
        ,h.challannumber--challandate
        ,to_char(h.challandate,'dd/MM/yyyy')challandate
        ,h.gateinwardnumber--,gateinwarddate
        ,to_char(h.gateinwarddate,'dd/MM/yyyy')gateinwarddate
        ,h.vehiclenumber
        ,row_number() over (order by serialnumber,d.itemcode) serialnumber
        from GOODSRECEIPTHEADER h,goodsreceiptitemdetail d
        ,item i,supplier s,unit u
        ,purchesorderheader ph
        where h.transactionnumber=d.transactionnumber
        and d.itemcode=i.itemcode
        and h.suppliercode=s.suppliercode
        and i.unitcode=u.unitcode
        and h.purchaseordertransnumber=ph.transactionnumber(+)
        and h.transactionnumber=".$this->transactionnumber."
        order by h.transactionnumber"; 
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        //$this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->hline(10,200,$this->liney,'D'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
    }
    
    function groupheader_1(&$group_row_1)
    {
        $this->setfieldwidth(70,70);
        $this->textbox('Material Approval Memo',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->hline(10,200,$this->liney+7,'C');
        $this->newrow(7);
        $this->setfieldwidth(60,10);
        $this->textbox('GRN No :'.$group_row_1['GOODSRECEIPTNOTEPREFIXNUMBER'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->setfieldwidth(60,130);
        $this->textbox('Challan No :'.$group_row_1['CHALLANNUMBER'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->newrow(7);
        $this->setfieldwidth(60,10);
        if ($group_row_1['GOODRECEIPTDATE']!='')
        $this->textbox('GRN Date :'.$group_row_1['GOODRECEIPTDATE'],$this->w,$this->x,'S','L',1,'verdana',10);
        else
        $dt='';
        
        $this->textbox('GRN Date :'.$dt,$this->w,$this->x,'S','L',1,'verdana',10);
        $this->setfieldwidth(60,130);
        if ($group_row_1['CHALLANDATE']!='')
       // $dt=DateTime::createFromFormat('d-M-Y',$group_row_1['CHALLANDATE'])->format('d/m/Y');
       $this->textbox('Challan Date :'.$group_row_1['CHALLANDATE'],$this->w,$this->x,'S','L',1,'verdana',10);
        else
        $dt='';
        $this->textbox('Challan Date :'.$group_row_1['CHALLANDATE'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->newrow(7);
        $this->setfieldwidth(70,10);
        $this->textbox('Supplier :'.$group_row_1['SUPPLIERCODE'].' '.$group_row_1['SUPPLIERNAMEENG'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->setfieldwidth(60,130);
        $this->textbox('Department :',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->newrow(7);
        $this->setfieldwidth(60,10);
        $this->textbox('Gate Inward No :'.$group_row_1['GATEINWARDNUMBER'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->setfieldwidth(60,130);
        $this->textbox('PO No :'.$group_row_1['PURCHESORDERFREFIXNUMBER'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->newrow(7);
        $this->setfieldwidth(60,10);
        if ($group_row_1['GATEINWARDDATE']!='')
            //$inwdt=DateTime::createFromFormat('d-M-Y',$group_row_1['GATEINWARDDATE'])->format('d/m/Y');
            $this->textbox('Gate Inward Date :'.$group_row_1['GATEINWARDDATE'],$this->w,$this->x,'S','L',1,'verdana',10);
           // $this->textbox('G',$this->w,$this->x,'S','L',1,'verdana',10);
        else
            $inwdt='';
       // $this->textbox('Gate Inward Date :'.$inwdt,$this->w,$this->x,'S','L',1,'verdana',10);
       /*  if ($group_row_1['GOODRECEIPTDATE']!='')
           // $podt=DateTime::createFromFormat('d-M-Y',$group_row_1['GOODRECEIPTDATE'])->format('d/m/Y');
            $this->textbox('PO Date :'.$group_row_1['GOODRECEIPTDATE'],$this->w,$this->x,'S','L',1,'verdana',10);
        else
            $podt=''; */
        $this->setfieldwidth(60,130);
        $this->textbox('PO Date :'.$group_row_1['PURCHESORDERDATE'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->newrow(7);
        $this->setfieldwidth(60,10);
        $this->textbox('Vehicle :'.$group_row_1['VEHICLENUMBER'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->newrow(7);
        $this->hline(10,200,$this->liney,'C');
        $this->setfieldwidth(15,10);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('Serial No',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('Item Code',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(95);
        $this->textbox('Item Name',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('Unit',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('Rec.Qty',$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('Acc.Qty',$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);
        $this->newrow(7);

        $this->hline(10,200,$this->liney,'C');
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
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            //$this->hline(10,400,$this->liney,'C'); 
        }
        $ht=7;
        $ht=$this->height($group_row_1['ITEMNAMEENG'],95);
        if ($this->isnewpage($ht))
        {
            $this->newrow(6);
            $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow(6);
            //$this->hline(10,400,$this->liney,'C'); 
        }
        $this->setfieldwidth(15,10);
        $this->vline($this->liney,$this->liney+$ht,$this->x);
        $this->textbox($group_row_1['SERIALNUMBER'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['ITEMCODE'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(95);
        $ht=$this->textbox($group_row_1['ITEMNAMEENG'],$this->w,$this->x,'S','L',1,'verdana',10,'','Y');
        $this->vline($this->liney,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['UNITNAMEENG'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['QUANTITY'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney,$this->liney+$ht,$this->x+$this->w);
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['ACCEPTEDQUANTITY'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney,$this->liney+$ht,$this->x+$this->w);
        $this->newrow($ht);

        if ($this->isnewpage(15))
        {
            $this->newrow(6);
            $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow(6);
            //$this->hline(10,400,$this->liney,'C'); 
        }
    }
    
    function groupfooter_1(&$group_row_1)
    { 
        $this->hline(10,200,$this->liney+2,'C');
        if ($this->isnewpage(25))
        {
            $this->newrow(6);
            $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }  
        $this->newrow(10);
        $this->setfieldwidth(90,10);
        $this->textbox('Goods Checked and Received as Above',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->setfieldwidth(90,120);
        $this->textbox('Material Received as Above',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->newrow(15);
        $this->setfieldwidth(40,10);
        $this->textbox('Store Cleck',$this->w,$this->x,'S','L',1,'verdana',10);

        $this->setfieldwidth(40,80);
        $this->textbox('Store Keeper',$this->w,$this->x,'S','L',1,'verdana',10);

        $this->setfieldwidth(40,150);
        $this->textbox('Head of Department',$this->w,$this->x,'S','L',1,'verdana',10);
        
        

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

}    
?>
