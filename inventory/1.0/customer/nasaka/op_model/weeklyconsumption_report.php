<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class consumption extends reportbox
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
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->newrow(10);
      
        $frdt=DateTime::createFromFormat('Y-m-d',$this->fromdate)->format('d-M-Y');
        $todt=DateTime::createFromFormat('Y-m-d',$this->todate)->format('d-M-Y');
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        $this->textbox(' Weekly Consumtion Report (Season- '.$_SESSION["yearperiodcode"].')',410,10,'S','C',1,'verdana',11,'','','','B');
        if ($this->fromdate!='' and $this->todate!='')
        {
            $this->newrow(7);
            $this->textbox('From the period  From Date '.$frdt.' To Date '. $todt. '',410,10,'S','C',1,'verdana',10,'','','','B');
        }
       
        $this->hline(10,410,$this->liney+6,'C'); 
        $this->newrow();
              
        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox('Sr.No',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        

        $this->setfieldwidth(25);        
        $this->textbox('Item Code',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(85);
        $this->textbox('Item Name',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('Unit',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('Previous ',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('PO Quantity',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('Opening ',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(50);
        $this->textbox('Receipt',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Total ',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(50);
        $this->textbox('Issue',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Closing ',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('PO ',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox('Remark',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->hline(210,260,$this->liney+6,'C');
        $this->hline(285,335,$this->liney+6,'C');
        $this->newrow();
        

        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+7,$this->x);
       // $this->textbox('Sr.No',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        

        $this->setfieldwidth(25);        
       // $this->textbox('Item Code',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(85);
       // $this->textbox('Item Name',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        //$this->textbox('Unit',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('Year Bal',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('Quantity',$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(' Balance',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('Receipt',$this->w,$this->x,'S','C',1,'verdana',11,'','','','');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox('Rec Todate',$this->w,$this->x,'S','C',1,'verdana',11,'','','','');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox(' Stock',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('Issue',$this->w,$this->x,'S','C',1,'verdana',11,'','','','');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox('Issu Todate',$this->w,$this->x,'S','C',1,'verdana',11,'','','','');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox(' Stock',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(' Balance',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);      
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->hline(10,410,$this->liney+7,'C');
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
        $this->totalgroupcount=0;

        $cond="1=1";
         if ($this->fromdate!='' and $this->todate!='')
        {
            
            $frdt=DateTime::createFromFormat('Y-m-d',$this->fromdate)->format('d-M-Y');
            $todt=DateTime::createFromFormat('Y-m-d',$this->todate)->format('d-M-Y');
            $cond= $cond."and h.purchesorderdate>='".$frdt."' and h.purchesorderdate<='".$todt."'";

        } 
       
       /*  if ($this->mainstorecode!=0)
        {
            if ($cond=="")
                $cond="i.mainstorecode=".$this->mainstorecode;
            else
                $cond=$cond." and i.mainstorecode=".$this->mainstorecode;
        } */
        
        $group_query_1 ="
        select 
        ROW_NUMBER() Over (Order by m.itemcode) sr_no
        ,m.itemcode
        ,ii.itemnameeng
        ,u.unitnameeng
        ,sum(pre_yrs_bal)pre_yrs_bal
        ,sum(po_qty)po_qty
        ,sum(opening_bal)opening_bal
        ,sum(receipt)receipt
        ,sum(rec_todate)rec_todate
        ,sum(iss_qty)iss_qty
        ,sum(issue_todate)issue_todate
        ,sum(pre_yrs_bal)+sum(rec_todate) TOTAL_STOCK
        ,(sum(pre_yrs_bal)+sum(rec_todate))-sum(issue_todate) stock
        ,sum(po_qty)-sum(rec_todate) po_balance
        from
        (select o.itemcode,nvl(o.quantity,0)pre_yrs_bal,0 po_qty,0 opening_bal,0 receipt,0 rec_todate,0 iss_qty,0 issue_todate
        from openingstocks o where o.financialyear=".$_SESSION["yearperiodcode"]."      
        union all
        select d.itemcode,0 pre_yrs_bal,nvl(d.quantity,0)po_qty,0 opening_bal,0 receipt,0 rec_todate,0 iss_qty,0 issue_todate
        from purchesorderheader p,purchesorderitemdetail d
        where p.transactionnumber=d.transactionnumber       
        and p.financialyear=".$_SESSION["yearperiodcode"]." 
        union all
        select t.itemcode,0pre_yrs_bal,0 po_qty, nvl(t.balance,0) opening_bal,0 receipt,0 rec_todate,0 iss_qty,0 issue_todate
        from INV_CURRENT_BALANCE_QTY t
        where t.transdate>='".$frdt."' and t.transdate<='".$todt."'       
        union all
        select d.itemcode,0 pre_yrs_bal,0 po_qty,0 opening_bal,nvl(d.acceptedquantity,0)receipt,0 rec_todate,0 iss_qty,0 issue_todate
        from goodsreceiptheader g,goodsreceiptitemdetail d
        where g.transactionnumber=d.transactionnumber
        and g.goodreceiptdate>='".$frdt."' and g.goodreceiptdate<='".$todt."'        
        union all
        select d.itemcode,0 pre_yrs_bal,0 po_qty,0 opening_bal,0 receipt,nvl(d.acceptedquantity,0) rec_todate,0 iss_qty,0 issue_todate
        from goodsreceiptheader g,goodsreceiptitemdetail d
        where g.transactionnumber=d.transactionnumber
        and g.financialyear=".$_SESSION["yearperiodcode"]."       
        union all
        select idd.itemcode,0 pre_yrs_bal,0 po_qty,0 opening_bal,0 receipt,0 rec_todate,idd.quantity iss_qty,0 issue_todate
        from issueheader i,issueitemdetail idd
        where i.transactionnumber=idd.transactionnumber
        and i.issuedate>='".$frdt."' and i.issuedate<='".$todt."'       
        union all
        select idd.itemcode,0 pre_yrs_bal,0 po_qty,0 opening_bal,0 receipt,0 rec_todate,0 iss_qty,idd.quantity issue_todate
        from issueheader i,issueitemdetail idd
        where i.transactionnumber=idd.transactionnumber
        and i.financialyear=".$_SESSION["yearperiodcode"]."  
        
        )m,item ii,unit u
        where m.itemcode=ii.itemcode
        and ii.unitcode=u.unitcode
        and ii.conzumption=1
        group by m.itemcode,ii.itemnameeng,u.unitnameeng
        order by m.itemcode
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
        $ht=$this->height($group_row_1['ITEMNAMEENG'],85);
        
        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x);       
        $this->textbox($group_row_1['SR_NO'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);
        

        $this->setfieldwidth(25);                
        $this->textbox($group_row_1['ITEMCODE'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(85);       
       $this->textbox($group_row_1['ITEMNAMEENG'],$this->w,$this->x,'N','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(15);        
        $this->textbox($group_row_1['UNITNAMEENG'],$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(20);        
        $this->textbox($group_row_1['PRE_YRS_BAL'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(20);       
        $this->textbox($group_row_1['PO_QTY'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['OPENING_BAL'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['RECEIPT'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(30);        
        $this->textbox($group_row_1['REC_TODATE'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(25);       
        $this->textbox($group_row_1['TOTAL_STOCK'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['ISS_QTY'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox($group_row_1['ISSUE_TODATE'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(25);        
        $this->textbox($group_row_1['STOCK'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(20);      
        $this->textbox($group_row_1['PO_BALANCE'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(30);      
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        if ($this->isnewpage(15))
        {
            $this->newrow($ht);
            $this->hline(10,410,$this->liney,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow($ht);
            $this->hline(10,410,$this->liney,'C');
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
        $this->newrow(25);

        $this->setfieldwidth(350,10);      
        $this->textbox('Store Keeper',$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

    }

}    
?>
