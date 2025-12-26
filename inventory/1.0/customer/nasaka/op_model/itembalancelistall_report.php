<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_P.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class itembalancelist extends reportbox
{	
    Public $mainstorecode;
    Public $substorecode;
    Public $fromdate;
    Public $todate;
    
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
        $this->pdf->Output('ITBALLST_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 20;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        //$this->drawlines($limit);
        $this->setfieldwidth(60,80);
        $this->textbox('Item Balance List',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->newrow(7);
        $this->setfieldwidth(100,50);
        $frdt=DateTime::createFromFormat('Y-m-d',$this->fromdate)->format('d-M-Y');
        $todt=DateTime::createFromFormat('Y-m-d',$this->todate)->format('d-M-Y');
        $this->textbox('for the period from '.$frdt.' to '.$todt,$this->w,$this->x,'S','L',1,'verdana',9);
        $this->newrow(7);
        $this->hline(10,200,$this->liney-1,'C'); 
        //$this->hline(10,200,$this->liney,'D'); 
        $this->setfieldwidth(20,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Code',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(90);
        $this->textbox('Item',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(20);
        $this->textbox('Opening',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('Receipt',$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('Issue',$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('Closing',$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow(7);
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
        $this->totalgroupcount=2;
        $cond='1=1';
        if ($this->mainstorecode!='' and $this->mainstorecode!='0')
        {
            $cond = $cond.' and m.mainstorecode='.$this->mainstorecode;
        }
        if ($this->substorecode!='' and $this->substorecode!='0')
        {
            $cond = $cond.' and s.substorecode='.$this->substorecode;
        }
        $frdt=DateTime::createFromFormat('Y-m-d',$this->fromdate)->format('d-M-Y');
        $todt=DateTime::createFromFormat('Y-m-d',$this->todate)->format('d-M-Y');
        $group_query_1 ="select * from (select m.mainstorecode,s.substorecode,t.itemcode
        ,m.mainstorenameeng,s.substorenameeng,t.itemnameeng,u.unitnameeng
        ,itemstock(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$frdt."',p_flag => 1) openingstock
        ,itemstock(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$todt."',p_flag => 2,p_fromdate => '".$frdt."') receipt
        ,itemstock(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$todt."',p_flag => 3,p_fromdate => '".$frdt."') issue
        ,itemstock(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$todt."',p_flag => 4) closingstock
        from item t,unit u,substore s,mainstore m
        where t.unitcode=u.unitcode 
        and t.substorecode=s.substorecode
        and s.mainstorecode=m.mainstorecode
        and ".$cond.") k
      --  where nvl(openingstock,0)>0 or nvl(receipt,0)>0 or nvl(issue,0)>0 or nvl(closingstock,0)>0
        order by mainstorecode,substorecode,itemcode";
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
        if ($this->isnewpage(30))
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
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($group_row_1['MAINSTORECODE'].' '.$group_row_1['MAINSTORENAMEENG'],$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+190);
        $this->newrow();
        $this->hline(10,200,$this->liney-1,'C'); 
    }


    function groupheader_2(&$group_row_1)
    {
        if ($this->isnewpage(30))
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
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('     '.$group_row_1['SUBSTORECODE'].' '.$group_row_1['SUBSTORENAMEENG'],$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+190);
        $this->newrow();
        $this->hline(10,200,$this->liney-1,'C'); 
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
        $y=$this->height($group_row_1['ITEMNAMEENG'],90);
        $y=$y+3;
        if ($y<6)
            $r=6;
        else
           $r=$y;

        $this->setfieldwidth(20,10);
        $this->vline($this->liney-1,$this->liney+$r,$this->x);
        $this->textbox($group_row_1['ITEMCODE'],$this->w,$this->x,'S','L',1,'verdana',9,'','Y');
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);
        $this->setfieldwidth(90);
        $this->textbox($group_row_1['ITEMNAMEENG'].' '.$group_row_1['UNITNAMEENG'],$this->w,$this->x,'S','L',1,'verdana',9,'','Y');
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $y=$this->textbox($group_row_1['OPENINGSTOCK'],$this->w,$this->x,'N','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['RECEIPT'],$this->w,$this->x,'N','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['ISSUE'],$this->w,$this->x,'N','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['CLOSINGSTOCK'],$this->w,$this->x,'N','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

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
        $this->setfieldwidth(20,10);
        $this->vline($this->liney-1,$this->liney+$r,$this->x);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(90);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);
        
        $this->newrow(7);
        $this->hline(10,200,$this->liney-1,'D'); 

    }
    function groupfooter_2(&$group_row_2)
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
        $this->setfieldwidth(20,10);
        $this->vline($this->liney-1,$this->liney+$r,$this->x);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(90);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);
        
        $this->newrow(7);
        $this->hline(10,200,$this->liney-1,'D');  
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
