<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class mainstorewisesmry extends reportbox
{	
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
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->newrow();
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        $this->textbox(' MainStoreWise Supplierwise Summary ',150,10,'S','C',1,'verdana',11);
        if ($this->fromdate!='' and $this->todate!='')
        {
            $this->newrow(7);
            $this->textbox('From Date '.$this->fromdate.' To Date '.$this->todate. '',100,50,'S','L',1,'verdana',9);
        }
       
        $this->hline(20,100,$this->liney+6,'C'); 
        $this->newrow();
              
        $this->setfieldwidth(25,20);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Sr.No',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        

        $this->setfieldwidth(25);        
        $this->textbox('GRN No',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox('Amount',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->hline(20,100,$this->liney+6,'C');
        //$this->drawlines($limit);
        $this->newrow();
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
        $this->totalgroupcount=2;

        $cond="1=1";
         if ($this->fromdate!='' and $this->todate!='')
        {
            
            $frdt=DateTime::createFromFormat('Y-m-d',$this->fromdate)->format('d-M-Y');
            $todt=DateTime::createFromFormat('Y-m-d',$this->todate)->format('d-M-Y');
            $cond= $cond."and h.purchesorderdate>='".$frdt."' and h.purchesorderdate<='".$todt."'";

        } 
       
        if ($this->mainstorecode!=0)
        {
            if ($cond=="")
                $cond="i.mainstorecode=".$this->mainstorecode;
            else
                $cond=$cond." and i.mainstorecode=".$this->mainstorecode;
        }
        
        $group_query_1 ="
        select mainstorecode
        ,suppliercode
        ,mainstorenameeng
        ,suppliernameeng
        ,row_number() over(partition by suppliercode order by goodsreceiptnotenumber)sr_no
        ,goodsreceiptnotenumber
        ,sum(amount)amount from
         (select i.mainstorecode
          ,s.suppliercode
          ,m.mainstorenameeng
          ,s.suppliernameeng        
          ,v.goodsreceiptnotenumber
          ,nvl(d.rate,0)*nvl(v.acceptedquantity,0) amount 
          from purchesorderheader h
          ,purchesorderitemdetail d
          ,item i,mainstore m
          ,supplier s ,VW_PO_QTY v
          where {$cond} and
          h.transactionnumber=d.transactionnumber
          and d.itemcode=i.itemcode(+)
          and h.transactionnumber=v.purchaseordertransnumber
          and d.itemcode=v.itemcode
          and i.mainstorecode=m.mainstorecode(+)       
          and h.suppliercode=s.suppliercode      
          order by i.mainstorecode,s.suppliercode,h.purchesordernumber
          )group by  mainstorecode,suppliercode,mainstorenameeng,suppliernameeng
          ,goodsreceiptnotenumber
          order by mainstorecode,suppliercode,goodsreceiptnotenumber
        
        "; 
        
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
        $this->totalamt=0;
        $this->setfieldwidth(80,20);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($group_row_1['MAINSTORECODE'].' '.$group_row_1['MAINSTORENAMEENG'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);       
        $this->newrow();
        $this->hline(20,100,$this->liney-2,'C'); 
       
    }


    function groupheader_2(&$group_row_1)
    {
        $this->setfieldwidth(80,20);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Party Name:'.$group_row_1['SUPPLIERCODE'].' '.$group_row_1['SUPPLIERNAMEENG'],$this->w,$this->x,'S','L',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);       
        $this->newrow();
        $this->hline(20,100,$this->liney-2,'C'); 
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
        $this->setfieldwidth(25,20);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($group_row_1['SR_NO'],$this->w,$this->x,'S','C',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['GOODSRECEIPTNOTENUMBER'],$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox($group_row_1['AMOUNT'],$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->totalamt=$this->totalamt+$group_row_1['AMOUNT'];



        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(20,100,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            $this->hline(20,100,$this->liney-2,'C'); 
        }
    }
    
    function groupfooter_1(&$group_row_1)
    {         $this->setfieldwidth(50,20);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        //$this->textbox($group_row_1['SR_NO'],$this->w,$this->x,'S','C',1,'verdana',11);
      //  $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

       // $this->setfieldwidth(25);
        $this->textbox('Mainstore Total:',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox($this->totalamt,$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->hline(20,100,$this->liney-2,'C'); 

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
