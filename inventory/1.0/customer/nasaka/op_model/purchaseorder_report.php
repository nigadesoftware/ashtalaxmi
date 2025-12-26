<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    require_once("../ip_model/purchaseordergeneraltermdetail_db_oracle.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class purchaseorder extends reportbox
{	
    Public $fromdate;
    Public $todate;
    Public $suppliercode;
    Public $employeecode;
    public $gstappicable;
    public $transactionnumber;
    public   $pocategory;
    public $purchaseordercategorynameeng;
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
        $this->pdf->SetSubject('Purchase Order');
        $this->pdf->SetKeywords('PO_000.EN');
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
        $this->pdf->Output('PO_000.pdf', 'I');
    }
	function pageheader()
    {
        $purchaseorderheader1 = new purchaseordergeneraltermdetail($this->connection);
        $purchaseorderheader1->transactionnumber=$this->transactionnumber;
        $purchaseorderheader1->fetchll();
        $this->gstappicable= $purchaseorderheader1->generaltermvaluecode;

        $this->liney = 10;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        $this->newrow(10);
        $this->setfieldwidth(100,80);
        $this->textbox($this->purchaseordercategorynameeng,$this->w,$this->x,'S','L',1,'verdana',11);
       // $this->newrow(10);
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow(7);

        $this->setfieldwidth(100,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Phone : 9527114455',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(90);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->textbox('GSTIN : 27AAXCA2984P1ZJ',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(5);

        $this->setfieldwidth(100,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Email : purchase.nashiksugar@gmail.com',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(90);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(5);

        $this->setfieldwidth(100,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Subject to Nashik Jurisdiction',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(90);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(5);
        $this->hline(10,200,$this->liney-1,'C');


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
        $cond='1=1';
        if ($this->fromdate != '' and $this->todate != '')
        {
           /*  $fdt=DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $tdt=DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            */ 
            $fdt=DateTime::createFromFormat('Y-m-d',$this->fromdate)->format('d-M-Y');
            $tdt=DateTime::createFromFormat('Y-m-d',$this->todate)->format('d-M-Y');
    
            $cond= $cond. "and purchesorderdate>='".$fdt."' and purchesorderdate<='".$tdt."'";
        }
        
        if ($this->transactionnumber != '')
        {
            $cond= $cond.' and t.transactionnumber='.$this->transactionnumber;
        }

        $group_query_1 ="select transactionnumber, financialyear, purchesordernumber, to_char(purchesorderdate,'dd/mm/yyyy') purchesorderdate, purchesorderfrefixnumber, quottransactionnumber, s.suppliercode, employeecode
        ,S.SUPPLIERNAMEENG,y.periodname_eng,s.address,s.mobilenumber,s.emailid,t.purchaseordercategorycode,c.purchaseordercategorynameeng
        ,s.gstin
        ,(select nvl(sum(amount),0) 
        from purchesorderitemdetail d 
        where d.transactionnumber=t.transactionnumber) ordervalue
        from purchesorderheader t,supplier s,nst_nasaka_db.yearperiod y 
        ,purchaseordercategory c
        where {$cond} 
        and t.financialyear=y.yearperiodcode
        and t.suppliercode=s.suppliercode
        and t.purchaseordercategorycode=c.purchaseordercategorycode
        order by transactionnumber"; 
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        //$this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->purchaseordercategorynameeng = $group_row_1['PURCHASEORDERCATEGORYNAMEENG'];
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
        $this->pocategory=$group_row_1['PURCHASEORDERCATEGORYCODE'];
        $this->newpage(true);
        $this->setfieldwidth(100,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Ref: NSSK /'.$group_row_1['PURCHESORDERFREFIXNUMBER'].' /'.$group_row_1['PERIODNAME_ENG'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(45);
        $this->vline($this->liney-1,$this->liney+4,$this->x+$this->w);
        $this->textbox($this->purchaseordercategorynameeng,$this->w,$this->x,'S','L',1,'verdana',8);
        //$this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(45);
        //$this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->textbox('Date:'.$group_row_1['PURCHESORDERDATE'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(5);
        $this->hline(10,200,$this->liney-1,'C');

        $this->setfieldwidth(100,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('To,',$this->w,$this->x,'S','L',1,'verdana',10);
      //  $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(90);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->textbox('',$this->w,$this->x,'S','L',1,'verdana',10);
      //  $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(5);

        $this->setfieldwidth(130,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('M/s.'.$group_row_1['SUPPLIERNAMEENG'],$this->w,$this->x,'N','L',1,'verdana',10);
      //  $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(60);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
       // $this->textbox('Your quotation on date:',$this->w,$this->x,'S','L',1,'verdana',10);
       $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(5);

        $this->setfieldwidth(130,10);
        $this->vline($this->liney-1,$this->liney+12,$this->x);
        $this->textbox($group_row_1['ADDRESS'],$this->w,$this->x,'N','L',1,'verdana',10,'','Y');
     //  $this->vline($this->liney-1,$this->liney+12,$this->x+$this->w);
        $this->setfieldwidth(60);
      //  $this->vline($this->liney-1,$this->liney+12,$this->x+$this->w);
        $this->textbox('',$this->w,$this->x,'S','L',1,'verdana',10);
       $this->vline($this->liney-1,$this->liney+12,$this->x+$this->w);
        $this->newrow(10);


        $this->setfieldwidth(150,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Email: '.$group_row_1['EMAILID'],$this->w,$this->x,'S','L',1,'verdana',10,'','Y');
      //  $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(40);
       // $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
      //  $this->textbox('Board Purchase Meeting on date:',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(5);

        $this->setfieldwidth(125,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Contact: '.$group_row_1['MOBILENUMBER'] .'     GSTIN:'.$group_row_1['GSTIN'],$this->w,$this->x,'N','L',1,'verdana',10);
       // $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(65);
      //  $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

      if($group_row_1['ORDERVALUE']!='')
      {
        $this->textbox('Order Value: '.$group_row_1['ORDERVALUE'],$this->w,$this->x,'S','L',1,'verdana',10);
      } 
      else
      {
        $this->textbox('Order Value: At actual',$this->w,$this->x,'S','L',1,'verdana',10);  
      }
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(5);

        $this->hline(10,200,$this->liney+1,'C');

        if ($group_row_1['ORDERVALUE']-intval($group_row_1['ORDERVALUE'])==0)
        {
            $wrd = 'Rupees '.getStringOfAmount(intval($group_row_1['ORDERVALUE']),0).' Only';
        }
        else
        {
            $paise = round(($group_row_1['ORDERVALUE']-intval($group_row_1['ORDERVALUE']))*100);
            $paise = $paise%100;
            $wrd1 = getStringOfAmount(intval($group_row_1['ORDERVALUE']));
            $wrd2 = getStringOfAmount($paise);
            $wrd = 'Rupees '.$wrd1.' and '.$wrd2.' Paise Only';
        }

        if($this->pocategory!=4)
        {
        $this->setfieldwidth(190,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);

        $this->textbox('Order Value in words :'.$wrd,$this->w,$this->x,'S','L',1,'verdana',10);
       
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        }
        else
        {
         if($group_row_1['ORDERVALUE']!='')
         {
            $this->setfieldwidth(190,10);
            $this->vline($this->liney-1,$this->liney+6,$this->x);
            $this->textbox('Order Value in words :'.$wrd,$this->w,$this->x,'S','L',1,'verdana',10);
         }
         else
         {
            $this->setfieldwidth(190,10);
            $this->vline($this->liney-1,$this->liney+6,$this->x);
            $this->textbox('Order Value in words :'.$wrd.' At Actual',$this->w,$this->x,'S','L',1,'verdana',10);
         }
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);


        }

        $this->newrow(5);
        $this->hline(10,200,$this->liney+1,'C');
        $this->setfieldwidth(190,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(5);

        $this->setfieldwidth(190,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Dear Sir,',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(5);

        $this->setfieldwidth(190,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('    With reference to your referred quotation and subsequent negotiation/discussion with you on',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(5);

        $this->setfieldwidth(190,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('we are pleased to place our order with you, subject to the terms and conditions specified as under.',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(5);

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
            
            $this->hline(10,200,$this->liney+6,'C');
            $this->setfieldwidth(190,10);
            $this->vline($this->liney-1,$this->liney+6,$this->x);            
            $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
            $this->newrow(7);            
            $this->vline($this->liney-1,$this->liney+6,$this->x);
            
            $this->setfieldwidth(15,10);
            $this->vline($this->liney-1,$this->liney+6,$this->x);
            $this->textbox('Sr.No.',$this->w,$this->x,'S','C',1,'verdana',10);
            $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

            $this->setfieldwidth(85);
            $this->textbox('Item Description',$this->w,$this->x,'S','C',1,'verdana',10,'','Y');
            $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

            $this->setfieldwidth(20);
            $this->textbox('Unit',$this->w,$this->x,'S','C',1,'verdana',10,'','Y');
            $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

            $this->setfieldwidth(20);
            $this->textbox('Quantity',$this->w,$this->x,'S','C',1,'verdana',10);
            $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

            $this->setfieldwidth(25);
            $this->textbox('Rate',$this->w,$this->x,'S','C',1,'verdana',10);
            $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

            $this->setfieldwidth(25);
            $this->textbox('Amount',$this->w,$this->x,'S','C',1,'verdana',10);
            $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
            
            $this->hline(10,200,$this->liney+6,'C');
            $this->newrow();

        $group_query_2 ="select t.serialnumber,t.itemcode,i.itemnameeng,t.itemspcification
        ,u.unitnameeng,t.quantity,t.rate,t.amount
        ,row_number() over (order by t.serialnumber,t.itemcode) serialnumber
        from purchesorderitemdetail t,item i,unit u
        where t.transactionnumber=".$group_row_1['TRANSACTIONNUMBER']."
        and t.itemcode=i.itemcode
        and i.unitcode=u.unitcode(+)
        order by transactionnumber,t.serialnumber"; 
        
        $group_result_2 = oci_parse($this->connection, $group_query_2);
        $r = oci_execute($group_result_2);
        $i=0;
        //$this->newpage(true);
        $this->amount=0;
        $this->textbox('',$this->w,$this->x,'S','L',1,'verdana',10);
        while ($group_row_2 = oci_fetch_array($group_result_2,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
        
            $h = $this->height($group_row_2['ITEMCODE'].' '.$group_row_2['ITEMNAMEENG'].' '.$group_row_2['ITEMSPCIFICATION'],85);
           
            $this->setfieldwidth(15,10);
            $this->vline($this->liney-1,$this->liney+$h,$this->x);
            $this->textbox($group_row_2['SERIALNUMBER'],$this->w,$this->x,'S','C',1,'verdana',10);
            $this->vline($this->liney-1,$this->liney+$h,$this->x+$this->w);
    
            $this->setfieldwidth(85);
            $this->textbox($group_row_2['ITEMCODE'].' '.$group_row_2['ITEMNAMEENG'].' '.$group_row_2['ITEMSPCIFICATION'],$this->w,$this->x,'S','L',1,'verdana',10,'','Y');
            $this->vline($this->liney-1,$this->liney+$h,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($group_row_2['UNITNAMEENG'],$this->w,$this->x,'S','C',1,'verdana',10,'','Y');
            $this->vline($this->liney-1,$this->liney+$h,$this->x+$this->w);

           /*  $this->setfieldwidth(25);
            $this->textbox($group_row_2['QUANTITY'].' '.$group_row_2['UNITNAMEENG'],$this->w,$this->x,'S','R',1,'verdana',10);
            $this->vline($this->liney-1,$this->liney+$h,$this->x+$this->w); */

            if($group_row_2['QUANTITY']=='')
            {
             $this->setfieldwidth(20);
             $this->textbox('At Actual',$this->w,$this->x,'S','C',1,'verdana',10);
             $this->vline($this->liney-1,$this->liney+$h,$this->x+$this->w);
            } 
            else
            {
                $this->setfieldwidth(20);
                $this->textbox($group_row_2['QUANTITY'],$this->w,$this->x,'S','R',1,'verdana',10);
                $this->vline($this->liney-1,$this->liney+$h,$this->x+$this->w);
            }
    
            $this->setfieldwidth(25);
            $this->textbox($group_row_2['RATE'],$this->w,$this->x,'S','R',1,'verdana',10);
            $this->vline($this->liney-1,$this->liney+$h,$this->x+$this->w);
    
            

            if($group_row_2['AMOUNT']=='')
                {
                $this->setfieldwidth(25);
                $this->textbox('At Actual',$this->w,$this->x,'S','C',1,'verdana',10);
                $this->vline($this->liney-1,$this->liney+$h,$this->x+$this->w);
                }
                else {
                    $this->setfieldwidth(25);
                    $this->textbox($group_row_2['AMOUNT'],$this->w,$this->x,'S','R',1,'verdana',10);
                    $this->vline($this->liney-1,$this->liney+$h,$this->x+$this->w);
                }
             
            if( $this->pocategory==4)
            {
                $this->newrow();
                $this->setfieldwidth(15,10);
                $this->vline($this->liney-1,$this->liney+$h,$this->x);
               // $this->textbox($group_row_2['SERIALNUMBER'],$this->w,$this->x,'S','C',1,'verdana',10);
                $this->vline($this->liney-1,$this->liney+$h,$this->x+$this->w);
        
                $this->setfieldwidth(100);
              //  $this->textbox($group_row_2['ITEMCODE'].' '.$group_row_2['ITEMNAMEENG'].' '.$group_row_2['ITEMSPCIFICATION'],$this->w,$this->x,'S','L',1,'verdana',10,'','Y');
                $this->vline($this->liney-1,$this->liney+$h,$this->x+$this->w);
        
               if($group_row_2['QUANTITY']=='')
               {
                $this->setfieldwidth(20);
                $this->textbox('At Actual',$this->w,$this->x,'S','C',1,'verdana',10);
                $this->vline($this->liney-1,$this->liney+$h,$this->x+$this->w);
               } 
                $this->setfieldwidth(25);
                //$this->textbox('At Actual',$this->w,$this->x,'S','L',1,'verdana',10);
                $this->vline($this->liney-1,$this->liney+$h,$this->x+$this->w);
               
                if($group_row_2['AMOUNT']=='')
                {
                $this->setfieldwidth(25);
                $this->textbox('At Actual',$this->w,$this->x,'S','C',1,'verdana',10);
                $this->vline($this->liney-1,$this->liney+$h,$this->x+$this->w);
                }
            }

            $this->amount+=$group_row_2['AMOUNT'];

            if ($this->isnewpage(50))
            {
                $this->newrow($h);
                $this->hline(10,200,$this->liney,'C'); 
                $this->newpage(True,True);
            }   
            else
            {
              
                $this->newrow($h);
               
            }
            $i=0;
          
            $last_row_2=$group_row_2;
        }
        	

    }
    
    function groupfooter_1(&$group_row_1)
    { 
        
        if ($this->isnewpage(15))
            {
                $this->newrow();
                $this->hline(10,200,$this->liney-2,'C'); 
                $this->newpage(True);
            }   
            else
            {
                //$this->newrow($h);
                //$this->hline(10,200,$this->liney-2,'C'); 
            }

        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(100);
        $this->textbox('',$this->w,$this->x,'S','L',1,'verdana',10,'','Y');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        if( $this->pocategory==4)
    {
        $this->setfieldwidth(25);       
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Value',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

           if($this->amount!=0)
            {
                $this->setfieldwidth(25);
                $this->textbox($this->amount,$this->w,$this->x,'S','R',1,'verdana',10);
                $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
            }
            else
            {
                $this->setfieldwidth(25);
                $this->textbox('At Actual',$this->w,$this->x,'S','L',1,'verdana',10);
                $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
            }

    }

        else {
            $this->setfieldwidth(25);
            $this->textbox('Total Order',$this->w,$this->x,'S','L',1,'verdana',10);
            $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
    
            $this->setfieldwidth(25);
            $this->textbox('Value',$this->w,$this->x,'S','L',1,'verdana',10);
            $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

            if($this->amount!='')
            {
            $this->setfieldwidth(25);
            $this->textbox($this->amount,$this->w,$this->x,'S','R',1,'verdana',10);
            $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
            }
            else
         {   
            $this->setfieldwidth(25);
            $this->textbox('At Actual',$this->w,$this->x,'S','R',1,'verdana',10);
            $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        }
    
        }
       

        $this->newrow(6);
        $this->hline(10,200,$this->liney,'C'); 
       
        if($this->gstappicable==1)
        {
        //------------------------------------
        //$this->newrow();
        $cond=" 1=1";
        if ($this->transactionnumber!='')
        {
                $cond=$cond." and t.transactionnumber=".$this->transactionnumber;
        }
       
        $group_query_1 =" select 
        nvl(sum(t.discountamount),0)dis
        ,nvl(sum(t.packingforwardingamount),0)pandf
        ,nvl(sum(t.totalgstamount),0)gstamt
        ,nvl(sum(t.itemtotal),0)total
        from purchesorderitemdetail t
        where {$cond}";
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
       // $this->newpage(true);
        if ($group_row_2 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
        //summary
        $this->newrow(3);
       // $this->hline(140,200,$this->liney-2,'C');
        $this->setfieldwidth(30,142);
        $this->vline($this->liney-2,$this->liney+6,$this->x-2);
        $this->textbox('Discount Amt',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->setfieldwidth(28);
        $this->textbox($group_row_2['DIS'],$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);

        /* $this->newrow();
       // $this->hline(140,200,$this->liney-2,'C');
        $this->setfieldwidth(30,142);
        $this->vline($this->liney-2,$this->liney+6,$this->x-2);
        $this->textbox(' Amt',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->setfieldwidth(28);
        $this->textbox($this->amount,$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);
        */

        $this->newrow();
        $this->hline(140,200,$this->liney-2,'C');
        $this->setfieldwidth(30,142);
        $this->vline($this->liney-2,$this->liney+6,$this->x-2);
        $this->textbox('Taxable Amt',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->setfieldwidth(28);
        $this->textbox($this->amount-$group_row_2['DIS'],$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);

           if ($this->isnewpage(50))
            {
                $this->newrow();
                $this->hline(10,200,$this->liney-2,'C'); 
                $this->newpage(True,True);
            }   

            else
            {$this->newrow();}
       
        $this->setfieldwidth(30,142);
        $this->vline($this->liney-2,$this->liney+6,$this->x-2);
        $this->textbox('Total p&f',$this->w,$this->x,'S','L',1,'verdana',10);        
        $this->setfieldwidth(28);
        $this->textbox($group_row_2['PANDF'],$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);

        if ($this->isnewpage(50))
            {
                $this->newrow();
                $this->hline(10,200,$this->liney-2,'C'); 
                $this->newpage(True,True);
            }

        else
        {$this->newrow();}
        $this->setfieldwidth(30,142);
        $this->vline($this->liney-2,$this->liney+6,$this->x-2);
        $this->textbox('Total GST',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->setfieldwidth(28);
        $this->textbox($group_row_2['GSTAMT'],$this->w,$this->x,'S','R',1,'verdana',10,'','','','');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);
        $this->newrow();

        /* 
        $this->setfieldwidth(350,320);
        $this->vline($this->liney-2,$this->liney+6,$this->x-2);
        $this->textbox('Round Amount',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->setfieldwidth(360,55);
        $this->textbox(  $group_row_2['ROUNDOFF'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',15);
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);
        $this->newrow(); */
        $this->setfieldwidth(30,142);
        $this->vline($this->liney-2,$this->liney+6,$this->x-2);
        $this->textbox('Invoice Amount',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->setfieldwidth(28);
        $this->textbox($group_row_2['TOTAL'],$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);
              
        if( $this->pocategory==4)
            {
            $this->newrow();
            $this->setfieldwidth(30,170);
          
            $this->vline($this->liney-2,$this->liney+6,$this->x-2);

           
            $this->setfieldwidth(28);
            $this->textbox('At Actual',$this->w,$this->x,'S','R',1,'verdana',10);
            $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);
            
            }
       // $this->newrow();
       // $this->hline(140,200,$this->liney-1,'C');
        }
        //----------------------------------------
    }
       // $this->newrow();

        $workorderdetails='';
        $query = "select workorderdetails
        from purchesorderheader where 
        transactionnumber= :var1";
        $stmt = oci_parse ($this->connection, $query);
        oci_bind_by_name($stmt, ':var1', $group_row_1['TRANSACTIONNUMBER']);
        oci_execute($stmt, OCI_DEFAULT);
        while (($r = oci_fetch_array($stmt, OCI_ASSOC))) 
        {
            $workorderdetails = $r["WORKORDERDETAILS"]->load();
            $r["WORKORDERDETAILS"]->free(); // cleanup before next fetch
        }
      
       // $this->pdf->setY($this->liney);
        
       /*  $html3 = '<table border="1" style="width: 100%;"><span style="text-align:justify;">'. $workorderdetails .'</span></table>';
        $this->pdf->SetFont('verdana', '', 10, '', true);
        $this->pdf->writeHTML($html3, true, 0, true, true);
		$this->pdf->lastPage();
        $this->liney = $this->pdf->getY()+4; */

        if ($this->isnewpage(25))
            {
                $this->newrow();
                $this->newpage(True);
            }   
            else
            {
                //$this->newrow($h);
                //$this->hline(10,200,$this->liney-2,'C'); 
            }
       
                    $group_query_3 = "  select row_number() over (order by termcategorycode,term) srno,term,termvalue from (
                select 1 termcategorycode,'Payment ' term,
                (paymentamountterm||' '||paymentpaytermnameeng ||' '||
                substr(advancetermnameeng, 1, instr(advancetermnameeng, ' ') - 1)||''||t.paymentperiodterm
                ||  substr(advancetermnameeng, instr(advancetermnameeng, ' ') + 1)) termvalue
                from purchesorderpaymentdetail t ,paymentpayterm p ,advanceterm a
                where t.paymentpaytermcode=p.paymentpaytermcode
                and t.advancetermcode=a.advancetermcode and t.transactionnumber =".$group_row_1['TRANSACTIONNUMBER']."
                 union all
                select  2 termcategorycode,p.generaltermnameeng,v.generaltermvaluenameeng
                from purchaseordergeneraltermdetail t ,generalterm p,generaltermvalue v 
                where t.generaltermcode=p.generaltermcode
                and t.generaltermvaluecode=v.generaltermvaluecode and t.transactionnumber =".$group_row_1['TRANSACTIONNUMBER']."
                union all
                 select 3 termcategorycode,'Period ' term,
                 (paymentamountterm||' '||
                 substr(advancetermnameeng, 1, instr(advancetermnameeng, ' ') - 1)||' '||t.paymentperiodterm
                 ||' '||  substr(advancetermnameeng, instr(advancetermnameeng, ' ') + 1)) termvalue
                 from purchesorderdurationdetail t,advanceterm a
                 where t.advancetermcode=a.advancetermcode and t.transactionnumber =".$group_row_1['TRANSACTIONNUMBER']."
                 union all
                 select 4 termcategorycode,'Period' Term,h.deliveryperiod value from purchesorderheader h
                 where h.transactionnumber=".$group_row_1['TRANSACTIONNUMBER']." and h.deliveryperiod is not null 
                  union all
                 select 5 termcategorycode,'Payment' Term,h.paymentterm value from purchesorderheader h
                 where h.transactionnumber=".$group_row_1['TRANSACTIONNUMBER']." and h.paymentterm is not null  ) 
                 " ;       
        $group_result_3 = oci_parse($this->connection, $group_query_3);
        $r = oci_execute($group_result_3);

        $this->setfieldwidth(50,10);
        $this->textbox('Terms and Conditions',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->newrow();
        $this->hline(10,200,$this->liney-1,'C'); 

        while ($group_row_3 = oci_fetch_array($group_result_3,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($this->isnewpage(50))
            {
                $this->newrow();
                $this->hline(10,200,$this->liney-2,'C'); 
                $this->newpage(True,'E');
            }   
            else
            {
                //$this->newrow($h);
                //$this->hline(10,200,$this->liney-2,'C'); 
            }
            if($group_row_3['SRNO']!=5)
            {
            $ht = $this->height($group_row_3['TERMVALUE'],125); 
            $this->setfieldwidth(5,10);
            $this->vline($this->liney-1,$this->liney+$ht,$this->x);
            $this->textbox($group_row_3['SRNO'],$this->w,$this->x,'S','L',1,'verdana',10);
            $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

            $this->setfieldwidth(60);
            $this->textbox($group_row_3['TERM'],$this->w,$this->x,'S','L',1,'verdana',10,'','');
            $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

            $this->setfieldwidth(125);
            $this->textbox($group_row_3['TERMVALUE'],$this->w,$this->x,'N','L',1,'verdana',10,'','');
            $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

            $this->newrow($ht);
            }
            else
            {
                $ht = $this->height($group_row_3['TERMVALUE'],125); 
                $this->setfieldwidth(5,10);
                $this->vline($this->liney-1,$this->liney+$ht,$this->x);
                $this->textbox($group_row_3['SRNO'],$this->w,$this->x,'S','L',1,'verdana',10);
                $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);
    
                $this->setfieldwidth(60);
                $this->textbox($group_row_3['TERM'],$this->w,$this->x,'S','L',1,'verdana',10,'','');
                $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);
    
                $this->setfieldwidth(125);
                $this->textbox($group_row_3['TERMVALUE'],$this->w,$this->x,'N','L',1,'verdana',10,'','');
                $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);
    
                $this->newrow($ht);
                /* $ht = $this->height($group_row_3['TERMVALUE'],125); 
                $ht=$ht+4;
                $this->setfieldwidth(125,10);
                $this->vline($this->liney-1,$this->liney+$ht,$this->x);
                $this->textbox($group_row_3['SRNO'].'  '.$group_row_3['TERM'].''.$group_row_3['TERMVALUE'],$this->w,$this->x,'N','L',1,'verdana',10);
                $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w+65);
    
              
                $this->newrow($ht); */
            }
        }
        if ($this->isnewpage(15))
            {
                $this->newrow();
                $this->hline(10,200,$this->liney-2,'C'); 
                $this->newpage(True,'E');
            }   
            else
            {
                $this->newrow(2);
                $this->hline(10,200,$this->liney-2,'C'); 
            }
         //  $this->newrow(2);
        $this->setfieldwidth(145,10);
        $this->textbox('Please send order acceptance letter by return post/email',$this->w,$this->x,'S','L',1,'verdana',10);
        
        if ($this->isnewpage(50))
            {
                $this->newrow();
                $this->hline(10,200,$this->liney-2,'C'); 
                $this->newpage(True,true);
            }   
            else
            {
                //$this->newrow($h);
                //$this->hline(10,200,$this->liney-2,'C'); 
            }
        
        $this->newrow();    
        $this->hline(10,200,$this->liney-1,'C');
        $this->setfieldwidth(45,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Factory Site :',$this->w,$this->x,'S','L',1,'verdana',10,'','Y');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(145);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->textbox('Nasik Sahakari Sakhar Karkhana Ltd., Palase',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(5);

        $this->setfieldwidth(45,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('',$this->w,$this->x,'S','L',1,'verdana',10,'','Y');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(145);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->textbox('Dwara Ashtalaxmi Sugar Ethanol and Energy, Nashikroad',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(5);

        $this->setfieldwidth(45,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('',$this->w,$this->x,'S','L',1,'verdana',10,'','Y');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(145);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->textbox('Sant Janardan Swami Nagar, A/p-Palse, Tal & Dist - Nashik, Pin - 422106',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(5);

        $this->setfieldwidth(45,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('',$this->w,$this->x,'S','L',1,'verdana',10,'','Y');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(145);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->textbox('Contact Person [Purchase Department] Jadhav S K - 9527114455',$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(5);
        $this->hline(10,200,$this->liney+1,'C');
    
        $this->newrow();
        if ($this->isnewpage(15))
            {
                $this->newrow();
                $this->hline(10,200,$this->liney-2,'C'); 
                $this->newpage(True,'E');
            }      
        $this->setfieldwidth(45,150);
        $this->textbox('Yours Faithfully,',$this->w,$this->x,'S','L',1,'verdana',11,'','Y');
        $this->textbox('Yours Faithfully,',$this->w,$this->x,'S','L',1,'verdana',11,'','Y');
        $this->newrow(15);    

        $this->setfieldwidth(45,145);
      //  $this->textbox('Kailas K Jare',$this->w,$this->x,'S','L',1,'verdana',11,'','Y');
       // $this->textbox('Kailas K Jare',$this->w,$this->x,'S','L',1,'verdana',11,'','Y');
        $this->newrow(5); 
        
        $this->setfieldwidth(45,10);
        $this->textbox('[P.O.]',$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
      
        $this->setfieldwidth(60);
        $this->textbox('[H.O.D]',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
      
        $this->setfieldwidth(30);
       // $this->textbox('[G.M. Tech]',$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
      
        $this->setfieldwidth(45);
        $this->textbox('[Managing Director]',$this->w,$this->x,'S','L',1,'verdana',11,'','Y');
        $this->textbox('[Managing Director]',$this->w,$this->x,'S','L',1,'verdana',11,'','Y');
        $this->newrow(5); 

        //$this->newpage(True);

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
