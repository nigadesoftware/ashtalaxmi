<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_P.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class itemvaluationlist extends reportbox
{	
    Public $mainstorecode;
    Public $substorecode;
    Public $fromdate;
    Public $todate;
    Public $substoresummary;
    Public $mainstoresummary;
    Public $summary;
    
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
        $this->pdf->SetSubject('Item Valuation');
        $this->pdf->SetKeywords('ITVAL_000.EN');
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
        $this->pdf->Output('ITVALLST_000.pdf', 'I');
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
        $this->textbox('Item Valuation List',$this->w,$this->x,'S','L',1,'verdana',11);
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
        $this->summary['VOPENINGSTOCK']=0;
        $this->summary['VRECEIPT']=0;
        $this->summary['VISSUE']=0;
        $this->summary['VCLOSINGSTOCK']=0;

        $sql = 'BEGIN allitemtransactionproc(:p_financialyear); END;';
        $result = oci_parse($this->connection,$sql);
        oci_bind_by_name($result,':p_financialyear',$_SESSION['yearperiodcode'],20,SQLT_INT);
        oci_execute($result);
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
        $group_query_1 ="select m.mainstorecode,s.substorecode,t.itemcode
        ,m.mainstorenameeng,s.substorenameeng,t.itemnameeng,u.unitnameeng
        ,itemvaluation(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$frdt."',p_flag => 1) vopeningstock
        ,itemvaluation(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$todt."',p_flag => 2,p_fromdate => '".$frdt."') vreceipt
        ,itemvaluation(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$todt."',p_flag => 3,p_fromdate => '".$frdt."') vissue
        ,itemvaluation(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$todt."',p_flag => 4) vclosingstock
        ,itemstock(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$frdt."',p_flag => 1) qopeningstock
        ,itemstock(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$todt."',p_flag => 2,p_fromdate => '".$frdt."') qreceipt
        ,itemstock(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$todt."',p_flag => 3,p_fromdate => '".$frdt."') qissue
        ,itemstock(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$todt."',p_flag => 4) qclosingstock
        from item t,unit u,substore s,mainstore m
        where t.unitcode=u.unitcode 
        and t.substorecode=s.substorecode
        and s.mainstorecode=m.mainstorecode
        and ".$cond." 
        order by m.mainstorecode,s.substorecode,t.itemcode";
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
        $this->mainstoresummary['VOPENINGSTOCK']=0;
        $this->mainstoresummary['VRECEIPT']=0;
        $this->mainstoresummary['VISSUE']=0;
        $this->mainstoresummary['VCLOSINGSTOCK']=0;
       // $this->setfieldwidth(120,10);
      //  $this->vline($this->liney-1,$this->liney+6,$this->x);
      //  $this->textbox($group_row_1['MAINSTORECODE'].' '.$group_row_1['MAINSTORENAMEENG'],$this->w,$this->x,'S','L',1,'verdana',11);
      //  $this->vline($this->liney-1,$this->liney+6,$this->x+190);
       // $this->newrow();
       // $this->hline(10,200,$this->liney-1,'C'); 
    }


    function groupheader_2(&$group_row_1)
    {
       /*  if ($this->isnewpage(30))
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
        $this->substoresummary['VOPENINGSTOCK']=0;
        $this->substoresummary['VRECEIPT']=0;
        $this->substoresummary['VISSUE']=0;
        $this->substoresummary['VCLOSINGSTOCK']=0;
        $this->setfieldwidth(120,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('     '.$group_row_1['SUBSTORECODE'].' '.$group_row_1['SUBSTORENAMEENG'],$this->w,$this->x,'S','L',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+190);
        $this->newrow();
        $this->hline(10,200,$this->liney-1,'C');  */
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
       /*  $y=$this->height($group_row_1['ITEMNAMEENG'],90);
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
        $y=$this->textbox($group_row_1['VOPENINGSTOCK'],$this->w,$this->x,'N','R',1,'verdana',8);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['VRECEIPT'],$this->w,$this->x,'N','R',1,'verdana',8);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['VISSUE'],$this->w,$this->x,'N','R',1,'verdana',8);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        if ($group_row_1['QCLOSINGSTOCK']!=0)
            $this->textbox($group_row_1['VCLOSINGSTOCK'],$this->w,$this->x,'N','R',1,'verdana',8);
        else
            $this->textbox(0,$this->w,$this->x,'N','R',1,'verdana',8);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);
        
        $this->newrow(4);

        $this->setfieldwidth(20,10);
        $this->vline($this->liney-1,$this->liney+$r,$this->x);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);
        $this->setfieldwidth(90);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $y=$this->textbox('['.$group_row_1['QOPENINGSTOCK'].']',$this->w,$this->x,'N','R',1,'verdana',8);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('['.$group_row_1['QRECEIPT'].']',$this->w,$this->x,'N','R',1,'verdana',8);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('['.$group_row_1['QISSUE'].']',$this->w,$this->x,'N','R',1,'verdana',8);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('['.$group_row_1['QCLOSINGSTOCK'].']',$this->w,$this->x,'N','R',1,'verdana',8);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);


        $this->newrow($r);
        if ($group_row_1['QCLOSINGSTOCK']!=0)
            $vcls=$group_row_1['VCLOSINGSTOCK'];
        else
            $vcls=0; */

        $this->mainstoresummary['VOPENINGSTOCK']=$this->mainstoresummary['VOPENINGSTOCK']+$group_row_1['VOPENINGSTOCK'];
        $this->mainstoresummary['VRECEIPT']=$this->mainstoresummary['VRECEIPT']+$group_row_1['VRECEIPT'];
        $this->mainstoresummary['VISSUE']=$this->mainstoresummary['VISSUE']+$group_row_1['VISSUE'];
        $this->mainstoresummary['VCLOSINGSTOCK']=$this->mainstoresummary['VCLOSINGSTOCK']+$vcls;
        
       /*  $this->substoresummary['VOPENINGSTOCK']=$this->substoresummary['VOPENINGSTOCK']+$group_row_1['VOPENINGSTOCK'];
        $this->substoresummary['VRECEIPT']=$this->substoresummary['VRECEIPT']+$group_row_1['VRECEIPT'];
        $this->substoresummary['VISSUE']=$this->substoresummary['VISSUE']+$group_row_1['VISSUE'];
        $this->substoresummary['VCLOSINGSTOCK']=$this->substoresummary['VCLOSINGSTOCK']+$vcls; */

        $this->summary['VOPENINGSTOCK']=$this->summary['VOPENINGSTOCK']+$group_row_1['VOPENINGSTOCK'];
        $this->summary['VRECEIPT']=$this->summary['VRECEIPT']+$group_row_1['VRECEIPT'];
        $this->summary['VISSUE']=$this->summary['VISSUE']+$group_row_1['VISSUE'];
        $this->summary['VCLOSINGSTOCK']=$this->summary['VCLOSINGSTOCK']+$vcls;

       /*  if ($this->isnewpage(15))
        {
            $this->newrow(2);
            $this->hline(10,200,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            $this->hline(10,200,$this->liney-2,'D'); 
        } */
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
            $this->hline(10,200,$this->liney-2,'C'); 
        }
        $r=6;
        $this->setfieldwidth(20,10);
        $this->vline($this->liney-1,$this->liney+$r,$this->x);
        $this->textbox($group_row_1['MAINSTORECODE'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(90);
        $this->textbox($group_row_1['MAINSTORENAMEENG'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->mainstoresummary['VOPENINGSTOCK'],$this->w,$this->x,'N','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->mainstoresummary['VRECEIPT'],$this->w,$this->x,'N','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->mainstoresummary['VISSUE'],$this->w,$this->x,'N','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox($this->mainstoresummary['VCLOSINGSTOCK'],$this->w,$this->x,'N','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);
        
        $this->newrow(7);
      //  $this->hline(10,200,$this->liney-1,'D'); 

    }
    function groupfooter_2(&$group_row_2)
    { /* 
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
        $this->textbox($group_row_2['SUBSTORENAMEENG'].' Sub Store Total',$this->w,$this->x,'N','R',1,'verdana',8);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->substoresummary['VOPENINGSTOCK'],$this->w,$this->x,'N','R',1,'verdana',8);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->substoresummary['VRECEIPT'],$this->w,$this->x,'N','R',1,'verdana',8);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->substoresummary['VISSUE'],$this->w,$this->x,'N','R',1,'verdana',8);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox($this->substoresummary['VCLOSINGSTOCK'],$this->w,$this->x,'N','R',1,'verdana',8);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);
        
        $this->newrow(7);
        $this->hline(10,200,$this->liney-1,'D');   */
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
        $this->textbox('Grand Total',$this->w,$this->x,'N','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(number_format_indian($this->summary['VOPENINGSTOCK'],2),$this->w,$this->x,'N','R',1,'verdana',7);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(number_format_indian($this->summary['VRECEIPT'],2),$this->w,$this->x,'N','R',1,'verdana',7);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(number_format_indian($this->summary['VISSUE'],2),$this->w,$this->x,'N','R',1,'verdana',7);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox(number_format_indian($this->summary['VCLOSINGSTOCK'],2),$this->w,$this->x,'N','R',1,'verdana',7);
        $this->vline($this->liney-1,$this->liney+$r,$this->x+$this->w);
        
        $this->newrow(7);
        $this->hline(10,200,$this->liney-1,'D'); 
    }

    function export()
    {
        $sql = 'BEGIN allitemtransactionproc(:p_financialyear); END;';
        $result = oci_parse($this->connection,$sql);
        oci_bind_by_name($result,':p_financialyear',$_SESSION['yearperiodcode'],20,SQLT_INT);
        oci_execute($result);
        $this->totalgroupcount=2;
        $cond='1=1';
        if ($this->mainstorecode!='' and $this->mainstorecode!='0')
        {
            $cond = $cond.' and m.mainstorecode='.$this->mainstorecode;
        }
        if ($this->substorecode!='' and $this->substorecode!='0')
        {
            $cond = $cond.' and m.substorecode='.$this->substorecode;
        }
        $frdt=DateTime::createFromFormat('Y-m-d',$this->fromdate)->format('d-M-Y');
        $todt=DateTime::createFromFormat('Y-m-d',$this->todate)->format('d-M-Y');
        $group_query_1 ="select m.mainstorecode,s.substorecode,t.itemcode
        ,m.mainstorenameeng,s.substorenameeng,t.itemnameeng,u.unitnameeng
        ,itemvaluation(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$frdt."',p_flag => 1) vopeningstock
        ,itemvaluation(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$todt."',p_flag => 2,p_fromdate => '".$frdt."') vreceipt
        ,itemvaluation(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$todt."',p_flag => 3,p_fromdate => '".$frdt."') vissue
        ,itemvaluation(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$todt."',p_flag => 4) vclosingstock
        ,itemstock(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$frdt."',p_flag => 1) qopeningstock
        ,itemstock(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$todt."',p_flag => 2,p_fromdate => '".$frdt."') qreceipt
        ,itemstock(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$todt."',p_flag => 3,p_fromdate => '".$frdt."') qissue
        ,itemstock(p_yearcode => ".$_SESSION['yearperiodcode'].",p_itemcode => t.itemcode,p_date => '".$todt."',p_flag => 4) qclosingstock
        from item t,unit u,substore s,mainstore m
        where t.unitcode=u.unitcode 
        and t.substorecode=s.substorecode
        and s.mainstorecode=m.mainstorecode
        and ".$cond." 
        order by m.mainstorecode,s.substorecode,t.itemcode";
           $result = oci_parse($this->connection, $group_query_1);
           $r = oci_execute($result);
           $response = array();
           $filename='itemvaluationlist.csv';
           //$fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');
           $fp1=fopen('php://memory', 'w');
           $deductionnamelist="Start,SrNo,Mainstore,Substore,Item Code,Item Name,Opening Qty,Opening Value,Receipt Qty,Receipt Value,Issue Qty,Issue Value,Closing Qty,Closing Value,End";
           fputcsv($fp1, array($deductionnamelist), $delimiter = ',', $enclosure = '#');
           $srno=1;
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                $item=str_replace("."," ",$row['ITEMNAMEENG']);
                $item=str_replace(","," ",$row['ITEMNAMEENG']);
                $rowstring="".",".$srno.",".$row['MAINSTORENAMEENG'].",".$row['SUBSTORENAMEENG'].",".$row['ITEMCODE'].",".$item.",".$row['QOPENINGSTOCK'].",".$row['VOPENINGSTOCK'].",".$row['QRECEIPT'].",".$row['VRECEIPT'].",".$row['QISSUE'].",".$row['VISSUE'].",".$row['QCLOSINGSTOCK'].",".$row['VCLOSINGSTOCK'].",";
                fputcsv($fp1, array($rowstring), $delimiter = ',', $enclosure = '#');
                $srno++;
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
