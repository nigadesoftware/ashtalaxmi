<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class nonmoveing extends reportbox
{
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
        $this->pdf->SetSubject('Circle');
        $this->pdf->SetKeywords('CIRCRUSH_000.MR');
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));
       // $title = str_pad(' ', 25).'Rajaramnagar, Tal - Dindori Dist - Nashik';
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
        $lg['a_meta_language'] = 'mr';
        $lg['w_page'] = 'Page - ';
        // set some language-dependent strings (optional)
	    $this->pdf->setLanguageArray($lg);
	}

    function startreport()
    {
        $this->group();
        $this->reportfooter();
    }

	function pageheader()
    {
        $this->liney = 18;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->textbox(' Non Moving Item List '.$_SESSION['yearperiodcode'],180,10,'S','C',1,'siddhanta',13);
        if ($this->fromdate!='' and $this->todate!='')
        {
            $this->newrow(7);
            $this->textbox('From Date '.$this->fromdate.' To Date '.$this->todate. '',100,50,'S','L',1,'siddhanta',12);
        }
        
        $this->newrow(7);
        
        $this->hline(10,200,$this->liney,'C');
        $this->setfieldwidth(30,10);
        $this->textbox('Sr.No',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(25);
        $this->textbox('Item Code',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(80);
        $this->textbox('Item Name',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->setfieldwidth(40);
        $this->textbox('Unit',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
       
        $this->newrow(7);
        $this->hline(10,200,$this->liney-2,'C'); 
    }
   
    function group()
    { $cond="1=1";
        $this->totalgroupcount=1;
         
        /* if ($this->fromdate!='' and $this->todate!='')
        {
            $this->fromdate = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $this->todate = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
           
        } */

        $cond="1=1";
        if ($this->mainstorecode!=0)
        {
            if ($cond=="")
                $cond="m.mainstorecode=".$this->mainstorecode;
            else
                $cond=$cond." and m.mainstorecode=".$this->mainstorecode;
        }
      

        $group_query_1 = "select 
        m.mainstorecode
       ,m.mainstorenameeng
       ,row_number()over(partition by m.mainstorecode order by itemcode)sr_no
       ,i.itemcode,i.itemnameeng,u.unitnameeng
       from item i
       ,mainstore m
       ,unit u
       where i.mainstorecode=m.mainstorecode
       and i.unitcode=u.unitcode
       and i.itemcode not in
       (
       select  distinct(itemcode)
       from (
       select o.itemcode
       ,o.quantity qty 
       from openingstocks o 
       where financialyear = ".$_SESSION['yearperiodcode']."
       union all
       select d.itemcode itemcode
       ,d.quantity qty
       from
       issueheader h
       ,issueitemdetail d
       where h.transactionnumber=d.transactionnumber
       and h.issuedate>='".$this->fromdate."'
       and h.issuedate<='".$this->todate."' 
       union all
       select d.itemcode
       ,d.acceptedquantity qty 
       from goodsreceiptheader h
       ,goodsreceiptitemdetail d
       where h.transactionnumber=d.transactionnumber
       and h.goodreceiptdate>='".$this->fromdate."'
       and h.goodreceiptdate<='".$this->todate."' 
       ))
       order by mainstorecode";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
    }
    function groupheader_1(&$group_row_1)
    {
       
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,80,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        $this->setfieldwidth(190,10);
       //$this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($group_row_1['MAINSTORENAMEENG'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
       // $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->hline(10,200,$this->liney,'D');
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
        ob_flush();
        ob_start();
       
        $this->setfieldwidth(15,10);
        $this->textbox($group_row_1['SR_NO'],$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(15);
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['ITEMCODE'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->setfieldwidth(110);
        $this->textbox($group_row_1['ITEMNAMEENG'],$this->w,$this->x,'S','L',1,'siddhanta',7,'','','','');
        $this->setfieldwidth(20);       
        $this->textbox($group_row_1['UNITNAMEENG'],$this->w,$this->x,'S','L',1,'siddhanta',10);
        
        $this->newrow(5);
        $this->hline(10,200,$this->liney,'D'); 
        if ($this->isnewpage(15))
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
        
        
    }


    function groupfooter_1(&$group_row_1)
    {     
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            $this->hline(10,405,$this->liney-2,'C'); 
        }

       
    }

    function groupfooter_2(&$group_row_1)
    {      
    }
    function groupfooter_3(&$group_row_2)
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

    function pagefooter($islastpage=false)
    {
    }

    function reportfooter()
    {
        if ($this->isnewpage(20))
        {
            //$this->newrow();
            //$this->hline(10,405,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
        }

       
    }

    function endreport()
    {

        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output($this->pdffilename.'-'.currentindiandatetimenamestamp().'.pdf', 'I');
    }



}    
?>