<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class storesaleinvoice extends reportbox
{
    public $transactionnumber;
    public $storesaledate;	
    public $farmercode;
    public $farmernameuni;
    public $storesaleprefixnumber;
    public $villagenameuni;
    public $circlenameuni;
    public $storenameuni;
    public $amount;
    public $quantity;
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
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        $this->newrow(8);
        $this->textbox('GSTN No : 27AAXCA2984P1ZJ PAN No : AAXCA2984P',180,10,'S','C',1,'verdana',8);
        $this->newrow(8);
        $this->setfieldwidth(100,60);
        $this->textbox('सी.डी.ओ.  - स्टोअर्स विक्री डिलिव्हरी चलन ',$this->w,$this->x,'S','L',1,'siddhanta',13);
       
        //$this->textbox('PAN No : AAXCA2984P',80,150,'S','L',1,'verdana',10);
        $this->newrow(8);
        $this->hline(10,200);
        $this->newrow(2);
      
        //$this->textbox($saleinvoiceheader1->salecategorynameuni.' परमीट बिल',180,10,'S','C',1,'siddhanta',14);
        //$this->textbox($saleinvoiceheader1->salecategorynameuni.' परमीट बिल',180,10,'S','C',1,'siddhanta',14);
        //$this->newrow(5);
        $this->textbox('बिल नं: '.$this->storesaleprefixnumber,100,20,'S','L',1,'siddhanta',10);
        $this->textbox('ठिकाण: '.$this->storenameuni,80,70,'S','L',1,'siddhanta',10);
        $this->textbox('सिझण: '.$this->recoverycrushingyearcode,80,120,'S','L',1,'siddhanta',10);
        $this->textbox('दिनांक: '.$this->storesaledate,80,160,'S','L',1,'siddhanta',10);
        $this->newrow(5);
        $this->textbox('प्रति,',100,10,'S','L',1,'siddhanta',12);
        //$this->textbox('सिझन -'.$saleinvoiceheader1->crushingseasonyear,80,150,'S','L',1,'siddhanta',10);
        
        $this->newrow(8);
        $this->setfieldwidth(150,10);
        $this->textbox('    '.$this->farmercode.'  - '.$this->farmernameuni.'  गाव - '.$this->villagenameuni.', गट - '.$this->circlenameuni,$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');
       // $this->setfieldwidth(150,60);
        //$this->textbox('सेंटर : '.$this->centrenameuni,$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        //$this->newrow(5);
        //$this->textbox('M/s- ',150,15,'S','L',1,'siddhanta',10);
        //$this->textbox('रोख पावती नं.- '.$saleinvoiceheader1->cashreceiptnumber,150,150,'S','L',1,'siddhanta',10);
        /* $this->newrow(7);
        $this->textbox('Please recieve the undermentioned material in good order and condition ',150,15,'S','L',1,'siddhanta',10);
        $this->newrow(5);
        $this->textbox('and return the duplicate copy of the challen duly acknowledged',150,15,'S','L',1,'siddhanta',10);
        $this->newrow(5); */
       
        $this->newrow(12);
        //$this->hline(10,205,$this->liney+6,'C');
        $this->setfieldwidth(25,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('कोड',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(60);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('मालाचे नाव ',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(25);
        $this->textbox('युनिट ',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(20);
        $this->textbox('नग',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(25);
        $this->textbox('दर ',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(25);
        $this->textbox('रक्कम ',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(15);
        $this->textbox('रेफ.',$this->w,$this->x,'S','C',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->hline(10,205,$this->liney-1,'C');
        $this->newrow();
        //$this->drawlines($limit);
    }

    

    function group()
    {
        $this->totalgroupcount=2;
        
        $group_query_1 ="select t.transactionnumber
        ,f.farmercode
        ,t.financialyear
        ,t.storesaleprefixnumber
        ,t.storesaledate
        ,t.employeecode
        ,t.storesaletypecode
        ,t.referencecode
        ,t.permittransactionnumber
        ,f.farmernameuni
        ,d.itemcode
        ,i.itemnameuni
        ,u.unitnameuni
        ,d.quantity
        ,d.rate
        ,d.amount
        ,d.gstrate
        ,d.cgstamount
        ,d.sgstamount
        ,d.totaltax
        ,d.itemamount
        ,vv.villagecode,vv.villagenameuni
        ,cc.circlecode,cc.circlenameuni
        ,st.storenameuni
        ,t.recoverycrushingyearcode
        from STORESALEHEADER t, storesaleitemdetail d, item i, unit u
        ,nst_amrut_agriculture.farmer f
        ,nst_amrut_agriculture.village vv
        ,nst_amrut_agriculture.circle cc
        ,storesalestoremaster st
        where t.transactionnumber=d.transactionnumber
        and t.transactionnumber=".$this->transactionnumber."
        and d.itemcode=i.itemcode
        and i.unitcode=u.unitcode
        and t.referencecode=f.farmercode
        and f.villagecode=vv.villagecode
        and vv.circlecode=cc.circlecode
        and t.storecode=st.storecode";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        //$this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->hline(10,205,$this->liney,'C'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
    }
    
    function groupheader_1(&$group_row_1)
    {
        $this->farmercode = $group_row_1['FARMERCODE'];
        $this->farmernameuni = $group_row_1['FARMERNAMEUNI'];
        $this->storesaleprefixnumber = $group_row_1['STORESALEPREFIXNUMBER'];
        $this->storesaledate = $group_row_1['STORESALEDATE'];
        $this->villagenameuni = $group_row_1['VILLAGENAMEUNI'];
        $this->circlenameuni = $group_row_1['CIRCLENAMEUNI'];
        $this->storenameuni = $group_row_1['STORENAMEUNI'];
        $this->recoverycrushingyearcode = $group_row_1['RECOVERYCRUSHINGYEARCODE'];
        $this->amount=0;
        $this->quantity=0;
        //$this->centrenameuni = $group_row_1['CENTRENAMEUNI'];
        $this->newpage(True);
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,205,$this->liney-2,'C'); 
            $this->newpage(True);
            $this->pageadded=1;
        }
        else
        {
            $this->hline(10,205,$this->liney-2,'C'); 
        }
        
        
        if ($this->pdf->getNumPages()==0)
        {
            $this->newpage(True);
            $this->pageadded=1;
        }
    }


    function groupheader_2(&$group_row_1)
    {
        $this->farmercode = $group_row_1['FARMERCODE'];
        $this->farmernameuni = $group_row_1['FARMERNAMEUNI'];
        $this->storesaleprefixnumber = $group_row_1['STORESALEPREFIXNUMBER'];
        $this->storesaledate = $group_row_1['STORESALEDATE'];
        $this->villagenameuni = $group_row_1['VILLAGENAMEUNI'];
        $this->circlenameuni = $group_row_1['CIRCLENAMEUNI'];
        $this->storenameuni = $group_row_1['STORENAMEUNI'];
       
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,205,$this->liney-2,'C'); 
            $this->newpage(True);
            $this->pageadded=1;
        }
        else
        {
            $this->hline(10,205,$this->liney-2,'C'); 
        }
        
        
        if ($this->pdf->getNumPages()==0)
        {
            $this->newpage(True);
            $this->pageadded=1;
        }
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
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($group_row_1['ITEMCODE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(60);
        $this->textbox($group_row_1['ITEMNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['UNITNAMEUNI'],$this->w,$this->x,'S','C',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['QUANTITY'],$this->w,$this->x,'S','C',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->quantity+=$group_row_1['QUANTITY'];
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['RATE'],$this->w,$this->x,'S','C',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['AMOUNT'],$this->w,$this->x,'S','C',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->amount+=$group_row_1['AMOUNT'];
        $this->setfieldwidth(15);
       // $this->textbox($group_row_1['QUANTITY'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,205,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            //$this->hline(10,400,$this->liney-2,'C'); 
        }
    }
    
    function groupfooter_1(&$group_row_1)
    { 
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,205,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
           // $this->newrow();
            //$this->hline(10,400,$this->liney-2,'C'); 
        }
        $this->setfieldwidth(25,10);
        //$this->vline($this->liney-1,$this->liney+6,$this->x);
        //$this->textbox($group_row_1['ITEMCODE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        //$this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(60);
        //$this->textbox($group_row_1['ITEMNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        //$this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        //$this->textbox($group_row_1['UNITNAMEUNI'],$this->w,$this->x,'S','C',1,'siddhanta',11);
        //$this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->quantity,$this->w,$this->x,'S','C',1,'siddhanta',11);
        //$this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(25);
        //$this->textbox($this->quantity,$this->w,$this->x,'S','C',1,'siddhanta',11);
        //$this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(25);
        $this->textbox($this->amount,$this->w,$this->x,'S','C',1,'siddhanta',11);
        //$this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(15);
        $this->newrow();
        $this->hline(10,205,$this->liney,'C'); 
       // $this->textbox($group_row_1['QUANTITY'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        //$this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,205,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            //$this->hline(10,400,$this->liney-2,'C'); 
        }
        
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
    function subreportgroupheader(&$subreportgroup_row,$subreportnumber,$groupnumber)
    {
    }

    function subreportgroupfooter(&$subreportlast_row,$subreportnumber,$groupnumber)
    {
    }

    function reportfooter()
    {
        $this->newrow(20);    
        $this->textbox('क्लर्क',70,10,'S','L',1,'siddhanta',10);  
        $this->textbox('स्टोअर किपर ',70,60,'S','L',1,'siddhanta',10); 
        $this->textbox('मानेजिंग डायरेक्टर',70,105,'S','L',1,'siddhanta',10);  
        $this->textbox('माल घेणाऱ्याची सही',70,160,'S','L',1,'siddhanta',10);  
        
          
    }

}    
?>
