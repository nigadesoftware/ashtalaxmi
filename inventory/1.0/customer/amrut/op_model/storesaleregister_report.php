<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class storesaleregister extends reportbox
{	
     
    public $fromdate;
    public $todate;
    public $circlecode;
    public $storesalecategorycode;
    public $centersmry;
    public $cashtypesmry;
    public $smry;
    public $storesalestorecode;
    public $recoverycrushingyearcode;
   
   
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
        $this->groupfield1='TOTALAMT';
       
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
        $this->textbox('-इतर खते/औषधे वाटप वसुली यादी-',180,10,'S','C',1,'siddhanta',13);
        $this->newrow();
        
        if ($this->fromdate!='' and $this->todate!='')
        {
            //$this->newrow(7);
            $this->textbox('हंगाम :'.$this->recoverycrushingyearcode.'दिनांक '.$this->fromdate.' पासून दिनांक '.$this->todate.' पर्यंत',150,50,'S','L',1,'siddhanta',10);
        }
       // $this->hline(10,200,$this->liney+6,'C');
        $this->newrow();
        $this->hline(10,200,$this->liney,'C'); 
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
        $this->smry['CNT']=0;
        $this->totalgroupcount=5;
        $cond="1=1";
        if ($this->circlecode!=0)
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
        if ($this->storesalecategorycode!='')
        {
            $cond=$cond." and h.salecategorycode=".$this->storesalecategorycode;
        }
        
        if ($this->storesalestorecode!='')
        {
            $cond=$cond." and h.storecode=".$this->storesalestorecode;
        }
        if ($this->recoverycrushingyearcode!='')
        {
            $cond=$cond." and h.recoverycrushingyearcode=".$this->recoverycrushingyearcode;
        }
        $group_query_1 =" 
        select h.recoverycrushingyearcode
        ,h.salecategorycode
        ,v.circlecode
        ,h.storesaletypecode
        ,h.storesalenumber
        ,sl.salecategorynameuni
        ,c.circlenameuni
        ,ss.storenameuni
        ,f.villagecode
        ,v.villagenameuni
        ,h.financialyear
        ,h.storesalenumber
        ,to_char(h.storesaledate,'dd/MM/yyyy')storesaledate
        ,h.referencecode farmercode
        ,f.farmernameuni
        ,d.itemcode
        ,s.finishedgoodsnameuni
        ,d.quantity
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
        order by h.recoverycrushingyearcode,h.salecategorycode,c.circlecode,h.storesaletypecode,h.storesalenumber
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
    function groupheader_1(&$group_row)
    {
    }
    function groupheader_2(&$group_row_1)
    {
        $this->cashtypesmry['TOTAL']=0;
        $this->cashtypesmry['CNT']=0;
    }

    function groupheader_3(&$group_row_1)
    {
        if ($this->isnewpage(15))
        {
            //$this->newrow();
        //    $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }
        $this->centersmry['TOTAL']=0;
        $this->centersmry['CNT']=0;
        $this->setfieldwidth(190,10);
       
        $this->textbox('गट    : '.$group_row_1['CIRCLENAMEUNI'].'              प्रकार: '.$group_row_1['SALECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','',''); 
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
        if ($this->isnewpage(15))
        {
            //$this->newrow();
        //    $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }
        $this->resetgroupsummary(3);
       
        $this->setfieldwidth(190,10);
       // $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('शॉप   :'.$group_row_1['STORENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','',''); 
        $this->newrow();
        $this->hline(10,200,$this->liney,'C'); 
        
        $this->setfieldwidth(25,10);   
        $this->vline($this->liney,$this->liney+6,$this->x);   
        $this->textbox('कोड नं',$this->w,$this->x,'S','L',1,'siddhanta',11);
       // $this->vline($this->liney,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(70);
      //  $this->vline($this->liney,$this->liney+6,$this->x);
        $this->textbox('शेतकऱ्याचे नाव',$this->w,$this->x,'S','L',1,'siddhanta',11);
       // $this->vline($this->liney,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox('गाव',$this->w,$this->x,'S','L',1,'siddhanta',11);
       // $this->vline($this->liney,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(55);
        $this->textbox('रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney,$this->liney+6,$this->x+$this->w+10);


        
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow(); 


        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }
    }

    function groupheader_5(&$group_row_1)
    {  
        /* if ($this->isnewpage(30))
        {
            $this->newrow();
            //$this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }  */
        if ($this->isnewpage(15))
        {
            //$this->newrow();
        //    $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }
        $this->centersmry['CNT']+=1;
        $this->cashtypesmry['CNT']+=1;
        $this->smry['CNT']+=1;
        $this->sr=0;
        $this->setfieldwidth(20,10);
        $this->vline($this->liney-1,$this->liney+10,$this->x);
        $this->textbox(''.$group_row_1['FARMERCODE'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B'); 
        $this->setfieldwidth(65);
        $this->textbox($group_row_1['FARMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B'); 
        $this->setfieldwidth(40);
        $this->textbox( $group_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B'); 
        $this->setfieldwidth(65);
        $this->textbox(number_format($group_row_1['TOTALAMT'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','B');

        $this->vline($this->liney-1,$this->liney+10,$this->x+$this->w);
        $this->setfieldwidth(200,10);
        $this->newrow(4);
        if ($this->isnewpage(15))
        {
            //$this->newrow();
        //    $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }
        $this->textbox('बिल नं.  '.$group_row_1['STORESALENUMBER'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','');
        $this->newrow();

         if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }       

       

        
        $this->setfieldwidth(10,90);   
        $this->textbox('नग',$this->w,$this->x,'S','R',1,'siddhanta',11);
       
        $this->setfieldwidth(30);
        $this->textbox('रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11);
      
        $this->newrow();

    }

    
    function groupheader_6(&$group_row)
    {
    }
    function groupheader_7(&$group_row)
    {
    }
    function detail_1(&$group_row_1)
    {   
        if ($this->isnewpage(15))
        {
            //$this->newrow();
        //    $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }
        $this->setfieldwidth(80,10);
        $this->vline($this->liney-8,$this->liney+10,$this->x);
        $this->textbox($group_row_1['FINISHEDGOODSNAMEUNI'],$this->w,$this->x,'N','L',1,'siddhanta',9);
      
        $this->setfieldwidth(15);
        $this->textbox('   '.$group_row_1['QUANTITY'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',9);
        
        $this->setfieldwidth(25);
        $this->textbox(number_format($group_row_1['AMOUNT'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
       
        $this->vline($this->liney-8,$this->liney+10,$this->x+$this->w+70);

       
        $this->centersmry['TOTAL']+=$group_row_1['AMOUNT'];
        $this->cashtypesmry['TOTAL']+=$group_row_1['AMOUNT'];
        $this->smry['TOTAL']+=$group_row_1['AMOUNT'];

        

        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();          
        } 
    }
 
    function groupfooter_1(&$group_row)
    {
    }
    function groupfooter_2(&$group_row_1)
    { 
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }
        else
        {
          //  $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);  
        }
        //$this->vline($this->liney-1,$this->liney+7,$this->x+40);
        $this->newrow();
        $this->setfieldwidth(110,20); 
        $this->textbox('शेतकरी संख्या :',$this->w,$this->x,'S','R',1,'siddhanta',10);
        $this->setfieldwidth(15); 
        $this->textbox(number_format($this->cashtypesmry['CNT'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->setfieldwidth(25); 
        $this->textbox($group_row_1['SALECATEGORYNAMEUNI'].' एकूण :',$this->w,$this->x,'S','R',1,'siddhanta',10);
        //$this->setfieldwidth(25); 
       // $this->textbox(number_format($this->cashtypesmry['CNT'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->setfieldwidth(25); 
        $this->textbox(number_format($this->cashtypesmry['TOTAL'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        //$this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        $this->newrow();
        $this->hline(200,100,$this->liney,'C'); 
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }
        else
        {
          //  $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);  
        }

    }

    function groupfooter_3(&$group_row_1)
    {  
        if ($this->isnewpage(15))
        {
            //$this->newrow();
        //    $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }
        //$this->vline($this->liney-1,$this->liney+7,$this->x+40);
        $this->setfieldwidth(110,15); 
        
        $this->textbox('शेतकरी संख्या :',$this->w,$this->x,'S','R',1,'siddhanta',10);
        $this->setfieldwidth(15); 
        $this->textbox(number_format($this->centersmry['CNT'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->setfieldwidth(25); 
        $this->textbox('गट एकूण :',$this->w,$this->x,'S','R',1,'siddhanta',10);
        //$this->setfieldwidth(25); 
        //$this->textbox(number_format($this->centersmry['CNT'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->setfieldwidth(25); 
        $this->textbox(number_format($this->centersmry['TOTAL'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
       // $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        $this->newrow();
        $this->hline(200,100,$this->liney,'C'); 
        

        $this->newrow(10);
        //$this->newrow();        
        $this->setfieldwidth(40,10);         
        $this->textbox('तयार करणार',$this->w,$this->x,'S','R',1,'siddhanta',10);
       
        $this->setfieldwidth(10);         
        //$this->textbox('गट अधिकारी',$this->w,$this->x,'S','R',1,'siddhanta',10);
       
        $this->setfieldwidth(150,25);         
        $this->textbox('ऊस विकास अधिकारी',$this->w,$this->x,'S','R',1,'siddhanta',10);
       

        if ($this->isnewpage(15))
        {
            $this->newrow();
        //    $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }
        else
        {
          //  $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);  
        }
    }

    function groupfooter_4(&$group_row_1)
    {     
    }
    function groupfooter_5(&$group_row_1)
    {
        $this->newrow(4);          
        $this->hline(10,200,$this->liney-1,'C');         
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
            //$this->newrow();
        //    $this->hline(10,200,$this->liney,'C'); 
            $this->newpage(True);
        }
        
        $this->newrow();
        $this->hline(200,145,$this->liney,'C'); 
        $this->setfieldwidth(110,20); 
        $this->textbox('शेतकरी संख्या :',$this->w,$this->x,'S','R',1,'siddhanta',10);
        $this->setfieldwidth(15); 
        $this->textbox(number_format($this->smry['CNT'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->setfieldwidth(25); 
        $this->textbox('एकूण एकंदर :',$this->w,$this->x,'S','R',1,'siddhanta',10);
        //$this->setfieldwidth(25); 
        //$this->textbox(number_format($this->smry['CNT'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->setfieldwidth(25); 
        $this->textbox(number_format($this->smry['TOTAL'],2,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        //$this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        $this->newrow();
        $this->hline(200,100,$this->liney,'C'); 

        //$this->newrow();   
        $this->newrow(20);     
        $this->setfieldwidth(40,10);         
        $this->textbox('तयार करणार',$this->w,$this->x,'S','R',1,'siddhanta',10);
       
        $this->setfieldwidth(50);         
        $this->textbox('स्टोअर किपर',$this->w,$this->x,'S','R',1,'siddhanta',10);
       
        $this->setfieldwidth(150,25);         
        $this->textbox('ऊस विकास अधिकारी',$this->w,$this->x,'S','R',1,'siddhanta',10);
       

    }

}    
?>
