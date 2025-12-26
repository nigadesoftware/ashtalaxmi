<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_P.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class invoicebill  extends reportbox
{	
    public $transactionnumber;
    public $invoicenumber;
    public $amtsmry;
    public $cgstsmry;
    public $sgstsmry;
    public $igstsmry;
    public $tcssmry;

    public $discountsmry;
    public $pandfsmry;
    public $totalgstsmry;
    public $summary;

   
    public $totalround;
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
        $this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

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
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        $this->newrow(10);
        $this->textbox('Bill Passing Statement ',250,80,'S','C',1,'siddhanta',15);
        $this->newrow(10);
        //$this->hline(10,400,$this->liney+6,'C');
        $this->newrow(7);

     
        $this->hline(10,415,$this->liney+6,'C');
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
        $this->amtsmry['SUMMARY']=0;
        $this->cgstsmry['SUMMARY']=0;
        $this->sgstsmry['SUMMARY']=0;
        $this->igstsmry['SUMMARY']=0;
        $this->tcssmry['SUMMARY']=0;
        $this->summary['SUMMARY']=0;
        $this->discountsmry['SUMMARY']=0;
        $this->pandfsmry['SUMMARY']=0;
        $this->totalgstsmry['SUMMARY']=0;
        $this->taxbleamtsmry['SUMMARY']=0;
        $this->disamtsmry['SUMMARY']=0;

        $this->totalgroupcount=1;
        $cond=" 1=1";
        if ($this->transactionnumber!='')
        {
                $cond=$cond." and h.transactionnumber=".$this->transactionnumber;
        }
       
        $group_query_1 =" 
        select 
        h.invoicenumber
        ,d.itemcode
        ,i.itemnameeng
        ,TO_CHAR(h.transactiondate,'dd/MM/yyyy')invoicedate
        ,GROSSITEMTOTAL
        ,h.suppliercode
        ,s.suppliernameeng
        ,s.address
        ,s.gstin
        ,h.supplierinvoicenumber
        ,to_char(h.supplierinvoicedate,'dd/MM/yyyy')supplierinvoicedate
        ,supplierinvoicetotal
        ,(SELECT  LISTAGG(grntransactionnumber, '       ,') WITHIN GROUP (ORDER BY g.grntransactionnumber)  fROM invoicegrndetail g where g.transactionnumber=h.transactionnumber)AS grnnumber
        ,(SELECT LISTAGG(to_char(a.goodreceiptdate,'dd/MM/yyyy'), ' ,') WITHIN GROUP (ORDER BY a.transactionnumber) 
        fROM invoicegrndetail hh,goodsreceiptheader a where hh.grntransactionnumber=a.transactionnumber and hh.transactionnumber=h.transactionnumber )AS grndate
        ,(select LISTAGG(pp.purchesordernumber,'                     ,  ') WITHIN GROUP (ORDER BY ii.transactionnumber)
        from invoicegrndetail ii, GOODSRECEIPTHEADER tt,purchesorderheader pp
         where ii.grntransactionnumber=tt.transactionnumber and
         tt.purchaseordertransnumber=pp.transactionnumber and ii.transactionnumber=h.transactionnumber)purchaseordernumber
         ,(select LISTAGG(to_char(pp.purchesorderdate,'dd/MM/yyyy'),'  ,  ') WITHIN GROUP (ORDER BY ii.transactionnumber)
        from invoicegrndetail ii, GOODSRECEIPTHEADER tt,purchesorderheader pp
         where ii.grntransactionnumber=tt.transactionnumber and
         tt.purchaseordertransnumber=pp.transactionnumber and ii.transactionnumber=h.transactionnumber)purchaseorderdate
        ,(supplierinvoicetotal-GROSSITEMTOTAL)diff_amt
        ,h.grossitemtotal
        ,d.serialnumber
        ,i.unitcode
        ,u.unitnameeng  
        ,d.quantity
        ,d.rate
        ,d.amount
        ,d.discountamount
        ,d.packingforwardingamount
        ,d.gstrate
        ,d.cgstamount
        ,d.sgstamount
        ,d.igstamount
        ,d.totalgstamount
        ,d.effctiveitemcost
        ,h.roundoff    
        ,d.itemtotal 
        ,d.discountedamount
        ,d.taxableamount       
        from 
        invoiceheader h
        ,invoiceitemdetail d
       ,supplier s
       ,item i,unit u
        where 
        h.transactionnumber=d.transactionnumber
        and h.suppliercode=s.suppliercode
        and d.itemcode=i.itemcode
        and i.unitcode=u.unitcode
        and {$cond}
        order by  d.serialnumber
         ";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        $this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
           // $this->hline(10,400,$this->liney,'D'); 
            $last_row=$group_row_1;
             $this->hline(10,411,$this->liney,'D'); 
        }
        $this->hline(10,411,$this->liney,'C'); 
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
    }
    
    function groupheader_1(&$group_row_1)
    {

        //GRoup Information
        $this->setfieldwidth(100,10);
        $this->vline($this->liney-1,$this->liney+60,$this->x);
        $this->textbox('Supplier Name: ',$this->w,$this->x,'S','L',1,'siddhanta',13);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(7);
       
        $this->setfieldwidth(100,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($group_row_1['SUPPLIERNAMEENG'],$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
        $this->vline($this->liney-1,$this->liney+43,$this->x+$this->w);
       
        $this->setfieldwidth(100);
        $this->newrow(-7);
        $this->textbox('Invoice Number:                           '.$group_row_1['INVOICENUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',13,'','','','');
        $this->newrow(7);
        $this->textbox('Invoice Date:                            '.$group_row_1['INVOICEDATE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',13,'','','','');
        $this->vline($this->liney-8,$this->liney+20,$this->x+$this->w);
       
      
        $this->newrow(15);
        $this->textbox('Supplier Invoice Number:            '.$group_row_1['SUPPLIERINVOICENUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',13,'','','','');
        $this->newrow(7);
        $this->textbox('Supplier Invoice Date:            '.$group_row_1['SUPPLIERINVOICEDATE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',13,'','','','');
        $this->vline($this->liney-8,$this->liney+20,$this->x+$this->w);
          

        $this->setfieldwidth(101);
        $this->newrow(-30);
        $this->textbox('GRN Number:                          '.$group_row_1['GRNNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',13,'','','','');
        $this->newrow(7);
        $this->setfieldwidth(190,210);
        $this->textbox('GRN Date:                            '.$group_row_1['GRNDATE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',13,'','','','');


        $this->newrow(15);
        $this->textbox('Purchase Order Number:            '.$group_row_1['PURCHASEORDERNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',13,'','','','');
        $this->newrow(7);
        $this->textbox('Purchase Order Date:             '.$group_row_1['PURCHASEORDERDATE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',13,'','','','');
        $this->vline($this->liney-29,$this->liney+32,$this->x+$this->w+15);


        $this->newrow(-35);
        $this->newrow(22);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->setfieldwidth(100,10);
        $this->textbox($group_row_1['ADDRESS'],$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(7);
        $this->vline($this->liney-1,$this->liney,$this->x);
        $this->setfieldwidth(100,10);
        $this->textbox($group_row_1['GSTIN'],$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','');
        $this->newrow(15);
        $this->vline($this->liney-16,$this->liney,$this->x+$this->w);
        $this->newrow(15);
        $this->hline(10,411,$this->liney-2,'D'); 
        $this->newrow(7);
      
        //Page Header....
        $this->hline(10,410,$this->liney-2,'D');
        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+10,$this->x);
        $this->textbox('Sr.No ',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+10,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('Item Code',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+10,$this->x+$this->w);

        $this->setfieldwidth(80);
        $this->textbox('Item Name',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+10,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('Unit',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-2,$this->liney+10,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox('Qty',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+10,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('Rate',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+8,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Amount',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+10,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('Dis.Amt',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+10,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('P&F.Amt',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+10,$this->x+$this->w);

        $this->setfieldwidth(10);
        $this->textbox('GST Rate',$this->w,$this->x,'S','R',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+10,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('CGST Amt',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+10,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('SGST Amt',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+10,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('IGST Amt',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+10,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Total GST Amt',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+10,$this->x+$this->w);

        $this->setfieldwidth(25);
        //$this->textbox('TCS Amt',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+10,$this->x+$this->w);


        $this->setfieldwidth(35);
        $this->textbox('Item Total Amt',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney-2,$this->liney+10,$this->x+$this->w);
       
         $this->newrow(10);

        $this->hline(10,410,$this->liney-2,'D');
       
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
        
         $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($group_row_1['SERIALNUMBER'],$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',13);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(35,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($group_row_1['ITEMCODE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',12);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(80);
        $this->textbox($group_row_1['ITEMNAMEENG'],$this->w,$this->x,'S','L',1,'siddhanta',13);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['UNITNAMEENG'],$this->w,$this->x,'S','L',1,'siddhanta',13);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['QUANTITY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',13);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['RATE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',13);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['AMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',13);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['DISCOUNTAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',13);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
 
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['PACKINGFORWARDINGAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',13);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
 

        $this->setfieldwidth(10);
        $this->textbox($group_row_1['GSTRATE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',13);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['CGSTAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',13);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['SGSTAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',15);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['IGSTAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',13);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['TOTALGSTAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',13);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

             
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['TCSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',13);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);


        $this->setfieldwidth(35);
        $this->textbox($group_row_1['ITEMTOTAL'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',13);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->amtsmry['SUMMARY']+=$group_row_1['AMOUNT'];
        $this->disamtsmry['SUMMARY']+=$group_row_1['DISCOUNTEDAMOUNT'];
        $this->taxbleamtsmry['SUMMARY']+=$group_row_1['TAXABLEAMOUNT'];
        $this->cgstsmry['SUMMARY']+=$group_row_1['CGSTAMOUNT'];
        $this->sgstsmry['SUMMARY']+=$group_row_1['SGSTAMOUNT'];
        $this->igstsmry['SUMMARY']+=$group_row_1['IGSTAMOUNT'];
        $this->tcssmry['SUMMARY']+=$group_row_1['TCSAMOUNT'];
        $this->summary['SUMMARY']+=$group_row_1['ITEMTOTAL'];

        $this->discountsmry['SUMMARY']+=$group_row_1['DISCOUNTAMOUNT'];
        $this->pandfsmry['SUMMARY']+=$group_row_1['PACKINGFORWARDINGAMOUNT'];
        $this->totalgstsmry['SUMMARY']+=$group_row_1['TOTALGSTAMOUNT'];
        $this->totalround['SUMMARY']+=$group_row_1['ROUNDOFF'];
       
       


        if ($this->isnewpage(15))
        {
            $this->newrow();
           // $this->hline(10,400,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            //$this->hline(10,400,$this->liney-2,'C'); 
        } 
    }
    
    function groupfooter_1(&$group_row_1)
    {
        $this->newrow(4);
       // $this->hline(318,410,$this->liney-2,'C');
        ///summary
        
        $this->vline($this->liney-5,$this->liney+7,$this->x+$this->w-255);
        $this->vline($this->liney-5,$this->liney+7,$this->x+$this->w);
        $this->setfieldwidth(155,25);
        
        $this->textbox('Total',$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',14);
        $this->vline($this->liney-5,$this->liney+7,$this->x+$this->w);
        $this->setfieldwidth(25);
        $this->textbox($this->amtsmry['SUMMARY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',14,'','','','B');
        $this->vline($this->liney-5,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->discountsmry['SUMMARY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',14,'','','','B');
        $this->vline($this->liney-5,$this->liney+7,$this->x+$this->w);
        $this->newrow(7);
        $this->textbox($this->disamtsmry['SUMMARY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',14,'','','','B');
        $this->vline($this->liney-5,$this->liney+7,$this->x+$this->w);
        $this->newrow(-7);
        $this->setfieldwidth(20);
        $this->textbox($this->pandfsmry['SUMMARY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',14,'','','','B');
        $this->vline($this->liney-5,$this->liney+7,$this->x+$this->w);
        $this->newrow(7);
        $this->textbox($this->taxbleamtsmry['SUMMARY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',14,'','','','B');
        $this->vline($this->liney-5,$this->liney+7,$this->x+$this->w);
        $this->newrow(-7);

        $this->setfieldwidth(35);
        $this->textbox($this->cgstsmry['SUMMARY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',14,'','','','B');
        $this->vline($this->liney-5,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(25);
        $this->textbox($this->sgstsmry['SUMMARY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',14,'','','','B');
        $this->vline($this->liney-5,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($this->igstsmry['SUMMARY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',14,'','','','B');
        $this->vline($this->liney-5,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($this->totalgstsmry['SUMMARY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',14,'','','','B');
        $this->vline($this->liney-9,$this->liney+7,$this->x+$this->w);
       
        $this->setfieldwidth(25);
        $this->textbox($this->tcssmry['SUMMARY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',14,'','','','B');
        $this->vline($this->liney-5,$this->liney+7,$this->x+$this->w);


        $this->setfieldwidth(35);
        $this->textbox($this->summary['SUMMARY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',14,'','','','B');
        //$this->vline($this->liney-9,$this->liney+7,$this->x+$this->w);


        //$this->vline($this->liney-9,$this->liney+7,$this->x+$this->w);
        $this->newrow(9);
        $this->hline(160,415,$this->liney-2,'C');

        $cond=" 1=1";
        if ($this->transactionnumber!='')
        {
                $cond=$cond." and h.transactionnumber=".$this->transactionnumber;
        }
       
        $group_query_1 ="select
        * from invoiceheader h
        where {$cond}";
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        $this->newpage(true);
        if ($group_row_2 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
        //summary
        $this->newrow(15);
        $this->hline(318,415,$this->liney-2,'C');
        $this->setfieldwidth(355,320);
        $this->vline($this->liney-2,$this->liney+6,$this->x-2);
        $this->textbox('Supplier Amount',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->setfieldwidth(360,55);
        $this->textbox($group_row_2['SUPPLIERINVOICETOTAL'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',15);
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->setfieldwidth(350,320);
        $this->vline($this->liney-2,$this->liney+6,$this->x-2);
        $this->textbox('Discount Amt',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->setfieldwidth(360,55);
        $this->textbox($group_row_2['GROSSDISCOUNTAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',14,'','','','B');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->setfieldwidth(350,320);
        $this->vline($this->liney-2,$this->liney+6,$this->x-2);
        $this->textbox('Total p&f',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->setfieldwidth(360,55);
        $this->textbox($group_row_2['GROSSPACKINGFORWARDINGAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',14,'','','','B');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->setfieldwidth(350,320);
        $this->vline($this->liney-2,$this->liney+6,$this->x-2);
        $this->textbox('Total GST',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->setfieldwidth(360,55);
        $this->textbox($group_row_2['GROSSGSTTOTAL'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',14,'','','','B');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->setfieldwidth(350,320);
        $this->vline($this->liney-2,$this->liney+6,$this->x-2);
        $this->textbox('Transportation',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->setfieldwidth(360,55);
        $this->textbox($group_row_2['TRANSPORTATION'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',14,'','','','B');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->setfieldwidth(350,320);
        $this->vline($this->liney-2,$this->liney+6,$this->x-2);
        $this->textbox('TCS Amount',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->setfieldwidth(360,55);
        $this->textbox($tgroup_row_2['TCSAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',14,'','','','B');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);
        
        $this->newrow();
        $this->setfieldwidth(350,320);
        $this->vline($this->liney-2,$this->liney+6,$this->x-2);
        $this->textbox('Round Amount',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->setfieldwidth(360,55);
        $this->textbox(  $group_row_2['ROUNDOFF'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',15);
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->setfieldwidth(350,320);
        $this->vline($this->liney-2,$this->liney+15,$this->x-2);
        $this->textbox('Invoice Amount',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->setfieldwidth(360,55);
        $this->textbox($group_row_2['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',15);
        $this->vline($this->liney-2,$this->liney+15,$this->x+$this->w);
        
        $this->newrow(5);
        $this->setfieldwidth(350,320);
        $this->newrow(7);
        $this->hline(318,415,$this->liney-2,'C');
        $this->vline($this->liney-2,$this->liney+9,$this->x-2);
        $this->textbox('Diff. Amount',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->setfieldwidth(360,55);
        $this->textbox($group_row_2['SUPPLIERINVOICETOTAL']-$group_row_2['NETAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',15);
        $this->vline($this->liney-2,$this->liney+9,$this->x+$this->w);
        $this->newrow(10);
        $this->hline(10,415,$this->liney-1,'C');
        }    
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
        $this->newrow(25);
        $this->setfieldwidth(150,10);
        $this->textbox('Store Account',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->setfieldwidth(200);
        $this->textbox('Chief Account',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->setfieldwidth(50);
        $this->textbox('General Manager',$this->w,$this->x,'S','L',1,'siddhanta',12);
    }

}    
?>
