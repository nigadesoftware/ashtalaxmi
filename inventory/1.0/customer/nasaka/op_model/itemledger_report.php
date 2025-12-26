<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_P.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class itemledger extends reportbox
{	
    Public $itemcode;
    Public $fromdate;
    Public $todate;
    Public $balance;
    Public $debitqty;
    Public $creditqty;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
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
        $this->pdf->SetSubject('Item Ledger');
        $this->pdf->SetKeywords('ITLED_000.EN');
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
        $this->pdf->Output('ITLED_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 20;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('Y-m-d h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        //$this->drawlines($limit);
        $this->setfieldwidth(60,80);
        $this->textbox('Item Ledger',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->newrow(7);
        $this->setfieldwidth(100,50);
        $frdt=DateTime::createFromFormat('Y-m-d',$this->fromdate)->format('d-M-Y');
        $todt=DateTime::createFromFormat('Y-m-d',$this->todate)->format('d-M-Y');
        $this->textbox('for the period from '.$frdt.' to '.$todt,$this->w,$this->x,'S','L',1,'verdana',9);
        $this->newrow(7);

        if ($this->pdf->PageNo()>1)
        {
            $this->hline(10,200,$this->liney-1,'C'); 
            //$this->hline(10,200,$this->liney,'D'); 
            $this->setfieldwidth(25,10);
            $this->vline($this->liney-1,$this->liney+6,$this->x);
            $this->textbox('Date',$this->w,$this->x,'S','L',1,'verdana',11);
            $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

            $this->setfieldwidth(90);
            $this->textbox('Narration',$this->w,$this->x,'S','L',1,'verdana',11);
            $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

            $this->setfieldwidth(25);
            $this->textbox('Receipt',$this->w,$this->x,'S','L',1,'verdana',11);
            $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

            $this->setfieldwidth(25);
            $this->textbox('Issue',$this->w,$this->x,'S','R',1,'verdana',10);
            $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

            $this->setfieldwidth(25);
            $this->textbox('Balance',$this->w,$this->x,'S','R',1,'verdana',10);
            $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
            $this->newrow(7);
            $this->hline(10,200,$this->liney-1,'C'); 
        }
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
        $frdt=DateTime::createFromFormat('Y-m-d',$this->fromdate)->format('d-M-Y');
        $todt=DateTime::createFromFormat('Y-m-d',$this->todate)->format('d-M-Y');
        $group_query_1 ="select t.itemcode,i.itemnameeng,transdate,t.flag,narration,debitqty,creditqty
        from 
        (
            select openingdate transdate,itemcode,1 flag,'Year Op Bal' narration,sum(debitqty) debitqty,sum(creditqty) creditqty
            from (
            select to_date('01-apr-'||trunc(".$_SESSION['yearperiodcode']."/10000)) openingdate
            ,i.itemcode,'' narration,0 debitqty,0 creditqty
            from item i,openingstocks st
            where i.itemcode=st.itemcode(+)
            union all
            select to_date('01-apr-'||trunc(st.financialyear/10000)) openingdate
            ,st.itemcode,'' narration,st.quantity debitqty,0 creditqty
            from item i,openingstocks st
            where i.itemcode=st.itemcode
            and st.financialyear=".$_SESSION['yearperiodcode']."
            )
            group by openingdate,itemcode
            union all
            select gh.goodreceiptdate,gd.itemcode,2 flag,'Goods Received from '||s.suppliernameeng||' vide GRN No-'||gh.goodsreceiptnoteprefixnumber narration
            ,gd.acceptedquantity debitqty,0 creditqty
            from GOODSRECEIPTHEADER gh,goodsreceiptitemdetail gd,supplier s
            where gh.transactionnumber=gd.transactionnumber
            and gh.suppliercode=s.suppliercode
            and gh.financialyear=".$_SESSION['yearperiodcode']."
            union all
            select ih.issuedate,id.itemcode,3 flag,'Issued to '||sm.sectionnameeng||' vide Idt No-'||ih.issuenumber narration
            ,0 debitqty,id.quantity creditqty
            from issueheader ih,issueitemdetail id,nst_nasaka_payroll.sectionmaster sm
            where ih.transactionnumber=id.transactionnumber
            and ih.issuetosectioncode=sm.sectioncode(+)
            and ih.financialyear=".$_SESSION['yearperiodcode']."
            )t,item i,substore s,mainstore m 
            where t.itemcode=i.itemcode 
            and i.substorecode=s.substorecode
            and s.mainstorecode=m.mainstorecode 
            and t.itemcode=".$this->itemcode." 
            and t.transdate>='{$frdt}' 
            and t.transdate<='{$todt}' 
            order by t.itemcode,t.transdate,t.flag";
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        //$this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            //$this->hline(10,200,$this->liney,'D'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
    }
    
    function groupheader_1(&$group_row_1)
    {
        $frdt=DateTime::createFromFormat('Y-m-d',$this->fromdate)->format('d-M-Y');
        $todt=DateTime::createFromFormat('Y-m-d',$this->todate)->format('d-M-Y');
         $group_query_2 ="select nvl(sum(debitqty),0)-nvl(sum(creditqty),0) balance
        from 
        (
            select openingdate transdate,itemcode,1 flag,'Year Op Bal' narration,sum(debitqty) debitqty,sum(creditqty) creditqty
            from (
            select to_date('01-apr-'||trunc(".$_SESSION['yearperiodcode']."/10000)) openingdate
            ,i.itemcode,'' narration,0 debitqty,0 creditqty
            from item i,openingstocks st
            where i.itemcode=st.itemcode(+)
            union all
            select to_date('01-apr-'||trunc(st.financialyear/10000)) openingdate
            ,st.itemcode,'' narration,st.quantity debitqty,0 creditqty
            from item i,openingstocks st
            where i.itemcode=st.itemcode
            and st.financialyear=".$_SESSION['yearperiodcode']."
            )
            group by openingdate,itemcode
            union all
            select gh.goodreceiptdate,gd.itemcode,2 flag,'Goods Received from '||s.suppliernameeng||' vide GRN No-'||gh.goodsreceiptnoteprefixnumber narration
            ,gd.acceptedquantity debitqty,0 creditqty
            from GOODSRECEIPTHEADER gh,goodsreceiptitemdetail gd,supplier s
            where gh.transactionnumber=gd.transactionnumber
            and gh.suppliercode=s.suppliercode
            and gh.financialyear=".$_SESSION['yearperiodcode']."
            union all
            select ih.issuedate,id.itemcode,3 flag,'Issued to '||sm.sectionnameeng||' section' narration
            ,0 debitqty,id.quantity creditqty
            from issueheader ih,issueitemdetail id,nst_nasaka_payroll.sectionmaster sm
            where ih.transactionnumber=id.transactionnumber
            and ih.issuetosectioncode=sm.sectioncode(+)
            and ih.financialyear=".$_SESSION['yearperiodcode']."
            )t,item i,substore s,mainstore m 
            where t.itemcode=i.itemcode 
            and i.substorecode=s.substorecode
            and s.mainstorecode=m.mainstorecode 
            and t.itemcode=".$this->itemcode." 
            and t.transdate<'{$frdt}' 
            order by t.itemcode,t.transdate,t.flag";
        $group_result_2 = oci_parse($this->connection, $group_query_2);
        $r2 = oci_execute($group_result_2);
        $i=0;
        $this->newpage(true);
        if ($group_row_2 = oci_fetch_array($group_result_2,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $opbal = $group_row_2['BALANCE'];
        }
        else
        {
            $opbal = 0;
        }
        $this->balance = $opbal;
        $this->debitqty = 0;
        $this->creditqty = 0;
        if ($this->isnewpage(15))
        {
            //$this->newrow();
            $this->hline(10,200,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            //$this->hline(10,200,$this->liney-2,'C'); 
        }
        $this->setfieldwidth(120,10);
        $this->textbox($group_row_1['ITEMCODE'].' '.$group_row_1['ITEMNAMEENG'],$this->w,$this->x,'S','L',1,'verdana',11);
        $this->newrow();
        $this->hline(10,200,$this->liney-1,'C'); 
        //$this->hline(10,200,$this->liney,'D'); 
        $this->setfieldwidth(25,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Date',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(90);
        $this->textbox('Narration',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Receipt',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Issue',$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Balance',$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $balance=$opbal;
        $this->newrow();
        $this->hline(10,200,$this->liney-1,'C'); 
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
        $this->setfieldwidth(25,10);
        $y=$this->height($group_row_1['NARRATION'],90);
        $y=$y+3;
        if ($y<6)
            $r=6;
        else
            $r=$y;
        $this->vline($this->liney-1,$this->liney+$r,$this->x);
        $dt=DateTime::createFromFormat('d-M-y',$group_row_1['TRANSDATE'])->format('d/m/Y');
        $this->textbox($dt,$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(90);
        $y=$this->textbox($group_row_1['NARRATION'],$this->w,$this->x,'S','L',1,'verdana',9,'','Y');
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['DEBITQTY'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['CREDITQTY'],$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);
        $this->balance=$this->balance+$group_row_1['DEBITQTY']-$group_row_1['CREDITQTY'];

        $this->setfieldwidth(25);
        $this->textbox($this->balance,$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->debitqty=$this->debitqty+$group_row_1['DEBITQTY'];
        $this->creditqty=$this->creditqty+$group_row_1['CREDITQTY'];
        
        $this->newrow($r);
 
        if ($this->isnewpage(15))
        {
            $this->newrow(2);
            $this->hline(10,200,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            $this->hline(10,200,$this->liney-2,'D'); 
        }
    }
    
    function groupfooter_1(&$group_row_1)
    { 
        if ($this->isnewpage(15))
        {
            //$this->newrow(4);
            $this->hline(10,200,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            $this->hline(10,200,$this->liney-2,'D'); 
        }
        $r=6;
        $this->setfieldwidth(25,10);
        $this->vline($this->liney-1,$this->liney+$r,$this->x);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(90);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($this->debitqty,$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($this->creditqty,$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);
        $this->newrow(7);
        $this->hline(10,200,$this->liney-1,'D'); 

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
