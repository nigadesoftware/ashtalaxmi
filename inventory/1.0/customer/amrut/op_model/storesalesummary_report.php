<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class storesalesummary extends reportbox
{	
     
    public $fromdate;
    public $todate;
    public $circlecode;
    public $storesalestorecode;
    public $storesalecategorycode;
    public $recoverycrushingyearcode;
    public $circlesmry;
    public $itemsmry;
    public $cashtypesmry;
    public $shoptypesmry;
    public $smry;

    public $qtycirclesmry;
    public $qtyitemsmry;
    public $qtycashtypesmry;
    public $qtyshoptypesmry;

   
   
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
       
        $this->groupfield1='QUANTITY';
        $this->groupfield1='AMOUNT';

        $this->resetgroupsummary(0);
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
       
        $this->newrow();
        $this->textbox('-योजनावाईज गट खत समरी-',180,10,'S','C',1,'siddhanta',13);
        $this->newrow();
        
        if ($this->fromdate!='' and $this->todate!='')
        {
           // $this->newrow(7);$this->textbox('हंगाम :'.$this->recoverycrushingyearcode,$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B'); 
            $this->textbox('हंगाम :'.$this->recoverycrushingyearcode. ' दिनांक '.$this->fromdate.' पासून दिनांक '.$this->todate.' पर्यंत',150,50,'S','L',1,'siddhanta',10);
        }
       // $this->hline(10,200,$this->liney+6,'C');
        $this->newrow();
        $this->hline(10,200,$this->liney+5,'C'); 
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
       $this->smry['TOTAL']=0;

        $this->totalgroupcount=5;
        $cond="1=1";
        if ($this->centercode!=0)
        {
            if ($cond=="")
                $cond="v.circlecode=".$this->circlecode;
            else
                $cond=$cond." and v.circlecode=".$this->circlecode;
        }

        if ($this->fromdate!='' and $this->todate!='')
        {
            $frdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $todt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $cond=$cond." and h.storesaledate>='".$frdt."' and h.storesaledate<='".$todt."'";
        }
        if ($this->storesalestorecode!='')
        {
            $cond=$cond." and h.storecode=".$this->storesalestorecode;
        }
        if ($this->recoverycrushingyearcode!='')
        {
            $cond=$cond." and h.recoverycrushingyearcode=".$this->recoverycrushingyearcode;
        }
        if ($this->storesalecategorycode !='')
        {
            $cond=$cond." and h.salecategorycode=".$this->storesalecategorycode;
        }
        $group_query_1 =" 
        select 
        h.recoverycrushingyearcode
        ,h.salecategorycode
        ,h.storesaletypecode
        ,d.itemcode
        ,v.circlecode       
        ,sl.salecategorynameuni
        ,ss.storenameuni
        ,s.finishedgoodsnameuni
        ,c.circlenameuni
        ,d.quantity
        ,d.rate
        ,d.amount
        ,h.storecode
        ,(select sum(sd.amount) from storesaleitemdetail sd where sd.transactionnumber=h.transactionnumber)totalamt
        from STORESALEHEADER h
        ,storesaleitemdetail d
        ,storesalecategory sl
        ,storesalestoremaster ss
        ,nst_amrut_agriculture.farmer f
        ,nst_amrut_agriculture.village v
        ,nst_amrut_agriculture.circle c
        ,item i
        ,nst_amrut_canedev.finishedgoods s
        where h.transactionnumber=d.transactionnumber(+)
        and h.salecategorycode=sl.salecategorycode(+)
        and h.storecode=ss.storecode(+)
        and h.referencecode=f.farmercode(+)
        and f.villagecode=v.villagecode(+)
        and v.circlecode=c.circlecode
        and d.itemcode=i.itemcode(+)       
        and i.itemcode=s.storeitemcode       
         and {$cond}
           order by h.recoverycrushingyearcode,h.salecategorycode,h.storesaletypecode,d.itemcode,c.circlecode 
        ";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        //$this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->recoverycrushingyearcode = $group_row_1['RECOVERYCRUSHINGYEARCODE'];
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
        $this->cashtypesmry['TOTAL']=0;
        $this->qtycashtypesmry['QTY']=0;
    }

    function groupheader_3(&$group_row_1)
    {       
        $this->shoptypesmry['TOTAL']=0;
        $this->qtyshoptypesmry['QTY']=0;
      
        $this->setfieldwidth(190,10);       
        $this->textbox('शॉप    : '.$group_row_1['STORENAMEUNI']
        .'                      प्रकार: '.$group_row_1['SALECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B'); 
        $this->newrow();

        $this->setfieldwidth(25,100);   
        $this->vline($this->liney,$this->liney+6,$this->x-90);   
        $this->textbox('संख्या(नग)',$this->w,$this->x,'S','L',1,'siddhanta',11);

        $this->setfieldwidth(30);
        $this->textbox('दर',$this->w,$this->x,'S','R',1,'siddhanta',11);

        $this->setfieldwidth(45);
        $this->textbox('रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney,$this->liney+6,$this->x+$this->w); 

       

        $this->hline(10,200,$this->liney,'C'); 
        $this->newrow();
          
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }
    }

    function groupheader_4(&$group_row_1)
    {
        $this->itemsmry['TOTAL']=0;
        $this->qtyitemsmry['QTY']=0;

        $this->setfieldwidth(190,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox(' '.$group_row_1['FINISHEDGOODSNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B'); 
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->hline(10,200,$this->liney,'C'); 
       
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }
    }

    function groupheader_5(&$group_row_1)
    {  
        $this->circlesmry['TOTAL']=0;
        $this->qtycirclesmry['QTY']=0;
    }

    function groupheader_6(&$group_row)
    {
    }
    function groupheader_7(&$group_row)
    {
    }
    function detail_1(&$group_row_1)
    {  
         $this->cashtypesmry['TOTAL']+=$group_row_1['AMOUNT'];
        $this->itemsmry['TOTAL']+=$group_row_1['AMOUNT'];
        $this->shoptypesmry['TOTAL']+=$group_row_1['AMOUNT']; 
        $this->circlesmry['TOTAL']+=$group_row_1['AMOUNT'];

        $this->qtycashtypesmry['QTY']+=$group_row_1['QUANTITY'];
        $this->qtyitemsmry['QTY']+=$group_row_1['QUANTITY'];
        $this->qtyshoptypesmry['QTY']+=$group_row_1['QUANTITY']; 
        $this->qtycirclesmry['QTY']+=$group_row_1['QUANTITY'];

    }
    function groupfooter_1(&$group_row_1)
    {
        $this->newpage(True);
    }
    function groupfooter_2(&$group_row_1)
    { 
       // $this->newrow();
        $this->hline(10,200,$this->liney,'C');        
        $this->setfieldwidth(50,10);
        $this->vline($this->liney-1,$this->liney+7,$this->x);        
        $this->textbox('योजना एकंदर :',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B'); 
      
         $this->setfieldwidth(50);
         $this->textbox($this->numformat($this->qtycashtypesmry['QTY']),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10); 
        $this->setfieldwidth(45);
       // $this->textbox('         '.$group_row_1['QUANTITY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->setfieldwidth(45);
        $this->textbox($this->numformat($this->cashtypesmry['TOTAL']),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);   
       
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        $this->newrow();
        $this->hline(10,200,$this->liney,'C'); 


        $this->newrow(15);
        $this->setfieldwidth(40,10);         
        $this->textbox('तयार करणार',$this->w,$this->x,'S','R',1,'siddhanta',10);
       
        $this->setfieldwidth(50);         
        $this->textbox('स्टोअर किपर',$this->w,$this->x,'S','R',1,'siddhanta',10);
       
        $this->setfieldwidth(150,25);         
        $this->textbox('ऊस विकास अधिकारी',$this->w,$this->x,'S','R',1,'siddhanta',10);

        
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }  
        else
        {
            $this->newrow(); 
           // $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        } 
        
    }

    function groupfooter_3(&$group_row_1)
    { 
    }

    function groupfooter_4(&$group_row_1)
    {   
        $this->newrow();
        $this->hline(10,200,$this->liney,'C');        
        $this->setfieldwidth(50,10);
        $this->vline($this->liney-1,$this->liney+7,$this->x);        
        $this->textbox('योजना एकूण :',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B'); 
      
         $this->setfieldwidth(50);
         $this->textbox($this->numformat($this->qtyitemsmry['QTY']),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10); 
        $this->setfieldwidth(45);
      //  $this->textbox('         '.$group_row_1['QUANTITY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->setfieldwidth(45);
        $this->textbox($this->numformat($this->itemsmry['TOTAL']),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);   
 
        $this->vline($this->liney-1,$this->liney+10,$this->x+$this->w);
        
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }  
        else
        {
            $this->newrow(); 
        } 
        
        
    }
    function groupfooter_5(&$group_row_1)
    {
        
        $this->newrow();
       
        $this->setfieldwidth(50,30);
        $this->vline($this->liney-1,$this->liney+7,$this->x-20);        
        $this->textbox(''.$group_row_1['CIRCLENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','',''); 
      
         $this->setfieldwidth(30);
         $this->textbox($this->numformat($this->qtycirclesmry['QTY']),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10); 
        $this->setfieldwidth(45);
        $this->textbox('         '.$group_row_1['RATE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->setfieldwidth(45);
        $this->textbox($this->numformat($this->circlesmry['TOTAL']),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);   
 
        $this->vline($this->liney-1,$this->liney+10,$this->x+$this->w);
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }  
       
     
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
