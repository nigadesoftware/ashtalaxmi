<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class loadingslip extends reportbox
{	
   
    public $fromdate;
    public $todate;
    public $farmercategorycode;

   
   
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
       
        $this->newrow();
        $this->textbox('Truck Loading Slip',180,10,'S','C',1,'siddhanta',13);
        $this->newrow();
        
      
      
       /*  $this->setfieldwidth(15,10);   
        $this->vline($this->liney-1,$this->liney+6,$this->x);   
        $this->textbox('अनु क्र.',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('जात',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('शेतकरी संख्या',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('आडसाली',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);


        $this->setfieldwidth(25);
        $this->textbox('पूर्व हंगामी',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(25);
        $this->textbox('सुरु',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

      
        $this->setfieldwidth(25);
        $this->textbox('खोडवा',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

               
        $this->setfieldwidth(25);
        $this->textbox('एकूण टनेज',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
       
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow(); */
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
      
        $cond=$cond." and h.transactionnumber=".$this->transactionnumber;
      
        $group_query_1 =" 
        select 
        h.transactionnumber
        ,h.memonumber
        ,to_char(h.memodate,'dd/MM/yyyy')memodate
        ,h.vehiclenumber
        ,g.purchasernameeng
        ,f.shortname
        ,f.finishedgoodsnameeng
        ,d.salequantity quantity
        ,(d.salequantity*100)/50 bags
        from SALEMEMOHEADER h
        ,SALEMEMODETAIL d
        ,finishedgoods f
        ,GOODSPURCHASER g
        where
        {$cond}
        and  h.transactionnumber=d.transactionnumber
        and h.goodscategorycode=g.goodscategorycode
        and h.purchasercode=g.purchasercode
        and h.goodscategorycode=1    
        and d.finishedgoodscode=f.finishedgoodscode
        
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
    function groupheader_4(&$group_row_1)
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
              
        $this->setfieldwidth(50,150);
        //$this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Truck No.    '.$group_row_1['VEHICLENUMBER'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->newrow();
        $this->hline(150,200,$this->liney-2,'C'); 

        $this->setfieldwidth(50,150);
        $this->textbox('Memo No.    '.$group_row_1['MEMONUMBER'],$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
        $this->newrow();
        $this->hline(150,200,$this->liney-2,'C'); 

        $this->setfieldwidth(50,150);
        $this->textbox('Memo Date   '.$group_row_1['MEMODATE'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->newrow();
        $this->hline(150,200,$this->liney-2,'C'); 

        $this->setfieldwidth(150,10);
        $this->textbox('Name of party : '.$group_row_1['PURCHASERNAMEENG'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->newrow();
        $this->hline(10,190,$this->liney-2,'C'); 

        $this->setfieldwidth(20,10);
        $this->vline($this->liney-2,$this->liney+6,$this->x);
        $this->textbox('Sr.No',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-2,$this->liney+6,$this->x);
        $this->textbox('Grade',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(70);
        $this->vline($this->liney-2,$this->liney+6,$this->x);
        $this->textbox('Description of Sugar',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-2,$this->liney+6,$this->x);
        $this->textbox('Qty.in qtl ',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-2,$this->liney+6,$this->x);
        $this->textbox('Qty in bags',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);

        $this->newrow();
        $this->hline(10,190,$this->liney-1,'C'); 

        $this->setfieldwidth(20,10);
        $this->vline($this->liney-2,$this->liney+6,$this->x);
        $this->textbox('1',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-2,$this->liney+6,$this->x);
        $this->textbox($group_row_1['SHORTNAME'],$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(70);
        $this->vline($this->liney-2,$this->liney+6,$this->x);
        $this->textbox($group_row_1['FINISHEDGOODSNAMEENG'],$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-2,$this->liney+6,$this->x);
        $this->textbox($group_row_1['QUANTITY'],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-2,$this->liney+6,$this->x);
        $this->textbox($group_row_1['BAGS'],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);

        $this->newrow();
        $this->hline(10,190,$this->liney-1,'C');

        $this->setfieldwidth(180,10);
        $this->vline($this->liney-2,$this->liney+6,$this->x);
        $this->textbox('Quantity in Wrod:  '.getStringOfAmount($group_row_1['BAGS']).' Bags',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->hline(10,190,$this->liney-1,'C');
    }
    
    function groupfooter_1(&$group_row_1)
    { 
    }
    function groupfooter_2(&$group_row_1)
    {  
    }

    function groupfooter_3(&$group_row_1)
    {     
    }
    function groupfooter_4(&$group_row_1)
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
        $this->newrow(15);
        $this->setfieldwidth(50,150);
        $this->textbox('Godown Keeper',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->newrow(15);

        $this->setfieldwidth(70,10);
        $this->textbox('गाडी भरल्याचा तपशील',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(90);
        $this->textbox('थप्पी विगत',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');

        $this->newrow();   
        $this->setfieldwidth(190,10);
        $this->textbox('गोडाऊन नं.                                  .......x.........=......... ',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
       
        $this->newrow();   
        $this->setfieldwidth(190,10);
        $this->textbox('ग्रेड                                         .......x.........=.........',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
       
        $this->newrow();   
        $this->setfieldwidth(190,10);
        $this->textbox('गाडी भरणाराची सही                           .......x.........=.........',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');

        $this->newrow();   
        $this->setfieldwidth(80,10);
        $this->textbox('गोडाऊन क्लार्क .....................',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(80);
        $this->textbox('एकूण .....................',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');

        $this->newrow();   
        $this->setfieldwidth(80,10);
        $this->textbox('सुरक्षा गार्ड .....................',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
        $this->setfieldwidth(80);
        $this->textbox('गाडी भरलेली वेळ .....................',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');

 
    }

}    
?>
