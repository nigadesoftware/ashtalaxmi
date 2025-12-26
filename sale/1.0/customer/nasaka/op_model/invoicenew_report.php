<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_pp.php");
    include_once("../info/routine.php");
    include_once("../ip_model/saleinvoiceheader_db_oracle.php");
    include_once("../ip_model/salememoheader_db_oracle.php");
    include_once("../ip_model/goodspurchaser_db_oracle.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class invoice extends reportbox
{	
    public $transactionnumber;
   
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
       // $this->textbox($this->ftransactionnumber['FARMERCOUNT'],175,10,'S','C',1,'siddhanta',13);
       
        $saleinvoiceheader1 = new saleinvoiceheader($this->connection);
        $saleinvoiceheader1->transactionnumber = $this->transactionnumber;
        $saleinvoiceheader1->fetch();
        $salememoheader1 = new salememoheader($this->connection);
        $salememoheader1->transactionnumber = $saleinvoiceheader1->salememotransactionnumber;

        $salememoheader1->fetch();
        $this->salecategorycode = $saleinvoiceheader1->salecategorycode;
        $this->grossamount=$saleinvoiceheader1->grossamount;
        $this->vehiclenumber=$saleinvoiceheader1->vehiclenumber;
        $this->memodate=$salememoheader1->memodate;

        $purchaser1 = new goodspurchaser($this->connection);
        $purchaser1->purchasercode = $saleinvoiceheader1->purchasercode;
        $purchaser1->fetch();
        $this->statecode=$purchaser1->statecode;
        $broker1 = new goodspurchaser($this->connection);
        $broker1->purchasercode = $saleinvoiceheader1->brokercode;
        $broker1->fetch();
        $shiftto1 = new goodspurchaser($this->connection);
        $shiftto1->purchasercode = $saleinvoiceheader1->shippingpartycode;
        $shiftto1->fetch();
        $img = $saleinvoiceheader1->getqrimage();
        // The '@' character is used to indicate that follows an image data stream and not an image file name
        //$this->pdf->Image('@'.$img,10,20,40,40);
      
        $this->newrow(45);
        $this->textbox('Tax Invoice',100,20,'S','R',1,'verdana',12,'','','','B');
        $this->textbox('(Original For Receipient)',175,20,'S','R',1,'verdana',9,'','','','');
        $this->newrow();

        $this->hline(10,200,$this->liney-1,'C');
        $this->setfieldwidth(100,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Astalaxmi Sugar Ethanol & Energy Limited-Unit1',$this->w,$this->x,'S','L',1,'verdana',11,'','','','B'); 
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->setfieldwidth(100,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Office:S-1 Renuka Plaza Opp-GPO Road Nashik-01',$this->w,$this->x,'S','L',1,'verdana',10,'','','',''); 
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->setfieldwidth(100,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Factory:Sant Janardhan Swami Nagar,Po Palse',$this->w,$this->x,'S','L',1,'verdana',10,'','','',''); 
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->setfieldwidth(100,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Palse,Tal& Dist:Nashik-422102',$this->w,$this->x,'S','L',1,'verdana',10,'','','',''); 
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->setfieldwidth(100,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('GSTIN/UIN:27AAXCA2984P1ZJ',$this->w,$this->x,'S','L',1,'verdana',10,'','','',''); 
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->setfieldwidth(100,10);
       // $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('E-Mail:nashiksugar.acct@gmail.com',$this->w,$this->x,'S','L',1,'verdana',10,'','','',''); 
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->hline(10,110,$this->liney-1,'C');
       
       
        $this->newrow(-42);
        $this->setfieldwidth(40,110);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Invoice Number '.$saleinvoiceheader1->invoicenumberpresuf,40,110,'N','L',1,'verdana',10,'','','','');
        $this->vline($this->liney-1,$this->liney+9,$this->x+$this->w);
        $this->setfieldwidth(50);
        $this->textbox('Invoice Date '.$saleinvoiceheader1->invoicedate,50,150,'N','L',1,'verdana',10,'','','','');
        $this->vline($this->liney-1,$this->liney+9,$this->x+$this->w);
       // $this->hline(200,110,$this->liney-1,'C');
        $this->newrow(10);
        $this->hline(200,110,$this->liney-1,'C');

        $this->setfieldwidth(40,110);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Memo No :'.$salememoheader1->memonumber,40,110,'N','L',1,'verdana',9,'','','','');
        $this->vline($this->liney-1,$this->liney+9,$this->x+$this->w);
        $this->setfieldwidth(50);
        $this->textbox('Mode of Payment ',40,150,'N','L',1,'verdana',10,'','','','');
        $this->vline($this->liney-1,$this->liney+9,$this->x+$this->w);
        $this->newrow(10);
        $this->hline(200,110,$this->liney-1,'C');

        $this->setfieldwidth(40,110);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Reference No & Date',40,110,'N','L',1,'verdana',10,'','','','');
        $this->vline($this->liney-1,$this->liney+9,$this->x+$this->w);
        $this->setfieldwidth(50);
        $this->textbox('Other Reference',40,150,'N','L',1,'verdana',10,'','','','');
        $this->vline($this->liney-1,$this->liney+9,$this->x+$this->w);
        $this->newrow();
        $this->hline(200,110,$this->liney-1,'C');

        $this->setfieldwidth(40,110);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Buyer Order No',40,110,'N','L',1,'verdana',10,'','','','');
        $this->vline($this->liney-1,$this->liney+9,$this->x+$this->w);
        $this->setfieldwidth(50);
        $this->textbox('Date',40,150,'N','L',1,'verdana',10,'','','','');
        $this->vline($this->liney-1,$this->liney+9,$this->x+$this->w);
        $this->newrow(10);
        $this->hline(200,110,$this->liney-1,'C');

        $this->setfieldwidth(40,110);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Dispatch Doc No',40,110,'N','L',1,'verdana',10,'','','','');
        $this->vline($this->liney-1,$this->liney+9,$this->x+$this->w);
        $this->setfieldwidth(50);
        $this->textbox('Memo Date ' .$salememoheader1->memodate,50,150,'N','L',1,'verdana',10,'','','','');
        $this->vline($this->liney-1,$this->liney+9,$this->x+$this->w);
        $this->newrow(10);
        $this->hline(200,110,$this->liney-1,'C');

        $this->setfieldwidth(40,110);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Dispatch Throuugh',40,110,'N','L',1,'verdana',10,'','','','');
        $this->vline($this->liney-1,$this->liney+9,$this->x+$this->w);
        $this->setfieldwidth(50);
        $this->textbox('Destination',40,150,'N','L',1,'verdana',10,'','','','');
        $this->vline($this->liney-1,$this->liney+9,$this->x+$this->w);
        $this->newrow(10);
        $this->hline(200,110,$this->liney-1,'C');

        $this->setfieldwidth(40,110);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Ladding Name :'.$saleinvoiceheader1->drivername,40,110,'N','L',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+9,$this->x+$this->w);
        $this->setfieldwidth(50);
        $this->textbox('Vehicle Number '.$this->vehiclenumber,50,150,'N','L',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+9,$this->x+$this->w);
        $this->newrow(10);
        $this->hline(200,110,$this->liney-1,'C');
         
        $this->setfieldwidth(40,110);
       // $this->vline($this->liney-1,$this->liney,$this->x);
        $this->textbox('Term Of Delivery',40,110,'S','L',1,'verdana',9);
       // $this->vline($this->liney-1,$this->liney+12,$this->x+$this->w);
        $this->setfieldwidth(50);
        //$this->textbox('Vehicle Number :'.$saleinvoiceheader1->vehiclenumber,30,40,'S','L',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+20,$this->x+$this->w);
        $this->newrow(10);
      //  $this->hline(200,110,$this->liney-1,'C');

        $this->newrow(-32);
        $this->setfieldwidth(100,10);
        $this->vline($this->liney-10,$this->liney+6,$this->x);
        $this->textbox('Buyer (Bill To)',100,10,'S','L',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+9,$this->x+$this->w);
        $this->newrow();
        $this->setfieldwidth(100,10);
        $this->vline($this->liney-10,$this->liney+6,$this->x);
        $this->textbox($purchaser1->purchasernameeng,100,10,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+9,$this->x+$this->w);
        
        $this->newrow();
        $this->setfieldwidth(100,10);
        $this->vline($this->liney-10,$this->liney+6,$this->x);
        $this->textbox('Address:'.$purchaser1->address,100,10,'N','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+9,$this->x+$this->w);

        $this->newrow(15);
        $this->setfieldwidth(100,10);
        $this->vline($this->liney-15,$this->liney+6,$this->x);
        $this->textbox('GSTIN NO:'.$purchaser1->gstinnumber,100,10,'S','L',1,'verdana',10);
        $this->vline($this->liney-6,$this->liney+9,$this->x+$this->w);


        $this->newrow();
        $this->setfieldwidth(100,10);
        $this->vline($this->liney-10,$this->liney+6,$this->x);
        $this->textbox('State : '.$purchaser1->statecode.' '.$purchaser1->statenameeng,100,10,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->hline(10,200,$this->liney-1,'C');
   
        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Sr.No.',$this->w,$this->x,'S','C',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(60);
        $this->textbox('Description Of Goods',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('HSN/SAC',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Qty',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Rate',$this->w,$this->x,'S','R',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('Per',$this->w,$this->x,'S','R',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(25);
        $this->textbox('Amount',$this->w,$this->x,'S','R',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->newrow();
        $this->hline(10,200,$this->liney-1,'C');
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
        $cond="1=1";
        if ($this->transactionnumber!=0)
        {
            if ($cond=="")
                $cond="h.TRANSACTIONNUMBERE=".$this->transactionnumber;
            else
                $cond=$cond." and h.TRANSACTIONNUMBERE=".$this->transactionnumber;
        }
      
        $group_query_1 ="select d.serialnumber
        ,f.finishedgoodsnameeng
        ,f.finishedgoodsnameuni
        ,d.salequantity
        ,d.salerate
        ,d.amount
        ,d.cgstrate
        ,d.sgstrate
        ,d.igstrate
        ,d.ugstrate
        ,d.cgstamount
        ,d.sgstamount
        ,d.igstamount
        ,d.ugstamount
        ,d.TOTALTAXAMOUNT
        ,d.productionyearcode
        ,y.periodname_eng
        from saleinvoicedetail d
        ,finishedgoods f
        ,nst_nasaka_db.YEARPERIOD y
        where d.finishedgoodscode=f.finishedgoodscode
        and d.transactionnumber=".$this->transactionnumber."
        and d.productionyearcode=y.yearperiodcode(+)
        order by d.serialnumber";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        $this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
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
        
         
        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($group_row_1['SERIALNUMBER'],$this->w,$this->x,'S','C',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(60);
        $this->textbox($group_row_1['FINISHEDGOODSNAMEENG'],$this->w,$this->x,'S','L',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('170114',$this->w,$this->x,'S','L',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['SALEQUANTITY'],$this->w,$this->x,'S','L',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['SALERATE'],$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('Knt',$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(25);
        $this->textbox($this->moneyFormatIndia($group_row_1['AMOUNT']),$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(20);

        if ($this->statecode == 27)
        {
           
            $this->setfieldwidth(75,10);
            $this->vline($this->liney-16,$this->liney+21,$this->x+15);
            $this->vline($this->liney-16,$this->liney+21,$this->x);
            $this->textbox('CGST Rate',75,10,'S','R',1,'verdana',9);
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->textbox($group_row_1['CGSTRATE'],$this->w,$this->x,'S','R',1,'verdana',9);   
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);       
            $this->setfieldwidth(15);
            $this->textbox('%',$this->w,$this->x,'S','R',1,'verdana',9);
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->textbox($this->moneyFormatIndia($group_row_1['CGSTAMOUNT']),$this->w,$this->x,'S','R',1,'verdana',9);
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);
            $this->newrow();
            $this->setfieldwidth(75,10);
            $this->vline($this->liney-16,$this->liney+21,$this->x+15);
            $this->vline($this->liney-26,$this->liney+21,$this->x);
            $this->textbox('SGST Rate',75,10,'S','R',1,'verdana',9);
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->vline($this->liney-6,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->vline($this->liney-6,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->textbox($group_row_1['SGSTRATE'],$this->w,$this->x,'S','R',1,'verdana',9);   
            $this->vline($this->liney-6,$this->liney+21,$this->x+$this->w);       
            $this->setfieldwidth(15);
            $this->textbox('%',$this->w,$this->x,'S','R',1,'verdana',9);
            $this->vline($this->liney-6,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->textbox($this->moneyFormatIndia($group_row_1['SGSTAMOUNT']),$this->w,$this->x,'S','R',1,'verdana',9);
            $this->vline($this->liney-6,$this->liney+21,$this->x+$this->w);

          

                 }
        elseif (in_array($statecode, array(4,7,25,26,31,34,35)))
        {
            $this->setfieldwidth(75,10);
            $this->vline($this->liney-16,$this->liney+21,$this->x+15);
            $this->vline($this->liney-16,$this->liney+21,$this->x);
            $this->textbox('IGST Rate',75,10,'S','R',1,'verdana',9);
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->textbox($group_row_1['IGSTRATE'],$this->w,$this->x,'S','R',1,'verdana',9);   
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);       
            $this->setfieldwidth(15);
            $this->textbox('%',$this->w,$this->x,'S','R',1,'verdana',9);
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->textbox($this->moneyFormatIndia($group_row_1['IGSTAMOUNT']),$this->w,$this->x,'S','R',1,'verdana',9);
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);
            $this->newrow();
            $this->setfieldwidth(75,10);
            $this->vline($this->liney-16,$this->liney+21,$this->x+15);
            $this->vline($this->liney-26,$this->liney+21,$this->x);
            $this->textbox('UGST Rate',75,10,'S','R',1,'verdana',9);
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->vline($this->liney-6,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->vline($this->liney-6,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->textbox($group_row_1['UGSTRATE'],$this->w,$this->x,'S','R',1,'verdana',9);   
            $this->vline($this->liney-6,$this->liney+21,$this->x+$this->w);       
            $this->setfieldwidth(15);
            $this->textbox('%',$this->w,$this->x,'S','R',1,'verdana',9);
            $this->vline($this->liney-6,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->textbox($this->moneyFormatIndia($group_row_1['UGSTAMOUNT']),$this->w,$this->x,'S','R',1,'verdana',9);
            $this->vline($this->liney-6,$this->liney+21,$this->x+$this->w);

          
            }
        elseif ($this->statecode != 27)
        {
         
            $this->setfieldwidth(75,10);
            $this->vline($this->liney-16,$this->liney+21,$this->x+15);
            $this->vline($this->liney-16,$this->liney+21,$this->x);
            $this->textbox('IGST Rate',75,10,'S','R',1,'verdana',9);
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->textbox($group_row_1['IGSTRATE'],$this->w,$this->x,'S','R',1,'verdana',9);   
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);       
            $this->setfieldwidth(15);
            $this->textbox('%',$this->w,$this->x,'S','R',1,'verdana',9);
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->textbox($this->moneyFormatIndia($group_row_1['IGSTAMOUNT']),$this->w,$this->x,'S','R',1,'verdana',9);
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);
            $this->newrow();
            $this->setfieldwidth(75,10);
            $this->vline($this->liney-16,$this->liney+21,$this->x+15);
            $this->vline($this->liney-26,$this->liney+21,$this->x);
            $this->textbox('UGST Rate',75,10,'S','R',1,'verdana',9);
            $this->vline($this->liney-16,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->vline($this->liney-6,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->vline($this->liney-6,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->textbox($group_row_1['UGSTRATE'],$this->w,$this->x,'S','R',1,'verdana',9);   
            $this->vline($this->liney-6,$this->liney+21,$this->x+$this->w);       
            $this->setfieldwidth(15);
            $this->textbox('%',$this->w,$this->x,'S','R',1,'verdana',9);
            $this->vline($this->liney-6,$this->liney+21,$this->x+$this->w);
            $this->setfieldwidth(25);
            $this->textbox($this->moneyFormatIndia($group_row_1['UGSTAMOUNT']),$this->w,$this->x,'S','R',1,'verdana',9);
            $this->vline($this->liney-6,$this->liney+21,$this->x+$this->w);

          
            }
        
        $this->newrow(15);

        $this->hline(10,200,$this->liney-1,'C'); 
        $this->setfieldwidth(75,10);
        $this->textbox('Total',$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
       
        $this->setfieldwidth(25);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['SALEQUANTITY'],$this->w,$this->x,'S','L',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(25);
        $this->textbox($this->moneyFormatIndia($this->grossamount),$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->newrow();    
        $this->hline(10,200,$this->liney-1,'C'); 


        //============================
        $this->setfieldwidth(190,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Amount Chargeable(in Word)',$this->w,$this->x,'S','L',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->setfieldwidth(190,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Rs '.getStringOfAmount(intval($this->grossamount),0).' Only',200,10,'S','L',1,'verdana',9,'','','','b');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow();    
        $this->hline(10,200,$this->liney-1,'C'); 

        $this->setfieldwidth(65,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('HSN/SAC',$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Taxable Value',$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Central Tax',$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('State Tax',$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(35);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Total Tax Amount',$this->w,$this->x,'S','L',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->newrow();
        $this->hline(105,200,$this->liney-1,'C');     
        $this->setfieldwidth(15,105);
        $this->vline($this->liney-1,$this->liney+6,$this->x-30);
        $this->vline($this->liney-1,$this->liney+6,$this->x-95);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Rate',$this->w,$this->x,'S','R',1,'verdana',8);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Amount',$this->w,$this->x,'S','R',1,'verdana',8);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Rate',$this->w,$this->x,'S','R',1,'verdana',8);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Amount',$this->w,$this->x,'S','R',1,'verdana',8);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w+35);
        $this->newrow();    
        $this->hline(10,200,$this->liney-1,'C'); 

        $this->setfieldwidth(65,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('170114',$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($this->moneyFormatIndia($group_row_1['AMOUNT']),$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

            if ($this->statecode == 27)
            {
                 
                $this->setfieldwidth(15);
                $this->vline($this->liney-1,$this->liney+6,$this->x);
                $this->textbox($this->moneyFormatIndia($group_row_1['CGSTRATE']),$this->w,$this->x,'S','R',1,'verdana',9);
                $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
                $this->setfieldwidth(15);
                $this->vline($this->liney-1,$this->liney+6,$this->x);
                $this->textbox($this->moneyFormatIndia($group_row_1['CGSTAMOUNT']),$this->w,$this->x,'S','R',1,'verdana',7);
                $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

                $this->setfieldwidth(15);
                $this->vline($this->liney-1,$this->liney+6,$this->x);
                $this->textbox($this->moneyFormatIndia($group_row_1['SGSTRATE']),$this->w,$this->x,'S','R',1,'verdana',9);
                $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
                $this->setfieldwidth(15);
                $this->vline($this->liney-1,$this->liney+6,$this->x);
                $this->textbox($this->moneyFormatIndia($group_row_1['SGSTAMOUNT']),$this->w,$this->x,'S','R',1,'verdana',7);
                $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
  
            }

            if ($this->statecode!= 27)
            {
                 
                $this->setfieldwidth(15);
                $this->vline($this->liney-1,$this->liney+6,$this->x);
                $this->textbox($this->moneyFormatIndia($group_row_1['IGSTRATE']),$this->w,$this->x,'S','R',1,'verdana',9);
                $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
                $this->setfieldwidth(15);
                $this->vline($this->liney-1,$this->liney+6,$this->x);
                $this->textbox($this->moneyFormatIndia($group_row_1['IGSTAMOUNT']),$this->w,$this->x,'S','R',1,'verdana',7);
                $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

                $this->setfieldwidth(15);
                $this->vline($this->liney-1,$this->liney+6,$this->x);
                $this->textbox($this->moneyFormatIndia($group_row_1['UGSTRATE']),$this->w,$this->x,'S','R',1,'verdana',9);
                $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
                $this->setfieldwidth(15);
                $this->vline($this->liney-1,$this->liney+6,$this->x);
                $this->textbox($this->moneyFormatIndia($group_row_1['UGSTAMOUNT']),$this->w,$this->x,'S','R',1,'verdana',7);
                $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
  
            }
        $this->setfieldwidth(35);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($this->moneyFormatIndia($group_row_1['TOTALTAXAMOUNT']),$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->hline(10,200,$this->liney-1,'C');  
        
        $this->setfieldwidth(190,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
       // $this->textbox('Rs '.getStringOfAmount(intval($group_row_1['TOTALTAXAMOUNT']),0).' Only',$this->w,$this->x,'S','L',1,'verdana',9,'','','','b');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->hline(110,200,$this->liney-1,'C'); 

        $this->setfieldwidth(100,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Declaration :',$this->w,$this->x,'S','L',1,'verdana',8,'','','','');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w+90);
        $this->setfieldwidth(100);
        $this->textbox('For Astalaxmi Sugar Ethanol & Energy Limited-Unit1',$this->w,$this->x,'S','L',1,'verdana',8,'','','','');
        $this->newrow();  
        $this->setfieldwidth(100,10);
        $this->vline($this->liney-1,$this->liney+11,$this->x);
        $this->textbox('We Declare that this invoice shows the actual price of goods described and that all perticulars are true and correct.  ',$this->w,$this->x,'N','L',1,'verdana',8,'','','','');
        $this->vline($this->liney-1,$this->liney+11,$this->x+$this->w);
       
        $this->newrow();  
        $this->setfieldwidth(90,100);
        $this->textbox('Authorised Signatory',$this->w,$this->x,'S','R',1,'verdana',8,'','','','');
        $this->vline($this->liney-8,$this->liney+4,$this->x+$this->w+10);
        $this->newrow(5);  
    
        $this->hline(10,200,$this->liney-1,'C'); 


       // $this->newrow(); 
        $this->newrow(2);
        $this->setfieldwidth(25,10);
        $this->textbox('Office:-',$this->w,$this->x,'N','L',1,'verdana',11,'','','','');
        $this->setfieldwidth(75,25);
        $this->textbox('S-1 Renuka Plaza Opp GPO Gajmal Signal Nashik 422001',$this->w,$this->x,'N','L',1,'verdana',10,'','','','');
       // $this->newrow(-7);
        $this->setfieldwidth(20,100);
        $this->textbox('Works :-',$this->w,$this->x,'N','L',1,'verdana',11,'','','','B');
        $this->setfieldwidth(80,120);
        $this->textbox('Unit-1,                                                   NASHIK Sahakari Sakhar Karkhana Ltd,Palse Shri Sant Janardhan Swami Nagar PO Palse,Tal & Dist Nashik-422102 Maharashtra ',$this->w,$this->x,'N','L',1,'verdana',10,'','','','');    
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

}    
?>
