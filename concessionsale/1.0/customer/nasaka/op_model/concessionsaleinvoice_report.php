<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a5_l.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class concessionsaleinvoice extends reportbox
{
    public $seasonyear;
    public $yearperiod;
    public $invoicenumber;
    public $transactionnumber;
    public $copycode;
    Public $startrec;
    public $customercategorycode;

    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7,$subject='',$pdffilename='',$papersize='A3',$orientation='P')
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $this->pdffilename= $pdffilename;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueType', '', 32);
    	// create new PDF document
	    $this->pdf = new MYPDF('P', PDF_UNIT, 'A5', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Farmer Sugar Card');
        $this->pdf->SetKeywords('FSUGCRD_000.MR');
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
        $this->copycode = 1;
        $this->startrec = 18;
        $this->group();
        //$this->reportfooter();
        $this->hline(0,205,$this->liney+25,'D'); 
        $this->copycode = 2;
        $this->startrec = 120;
        $this->liney = 120;
        $this->pageheader();
        //$this->isendofreport=1;
        //$this->newpage(true,true);
        $this->group();
        //$this->reportfooter();

    }

	function pageheader()
    {
        $this->pdf->Image("../img/kadwawatermark.png", 40, 25, 70, 70, '', '', '', false, 300, '', false, false, 0);
        $this->liney = $this->startrec-8;
        if ($this->copycode==1 and $this->customercategorycode==1)
        {
            $this->textbox('सभासद काॅपी',50,90,'S','R',1,'siddhanta',10); 
        }
        else if ($this->copycode==1 and $this->customercategorycode==2)
        {
            $this->textbox('कामगार काॅपी',50,90,'S','R',1,'siddhanta',10); 
        }
        if ($this->copycode==2)
        {
            $this->textbox('कारखाना काॅपी',50,90,'S','R',1,'siddhanta',10); 
        }
        //$this->pdf->SetFont('helvetica', 'I', 8);
        //$this->pdf->write2DBarcode($_SESSION['yearperiodcode'].str_pad($this->farmercode, 5, '0', STR_PAD_LEFT), 'QRCODE,H', 170, 15, 25, 25, $style, 'N');
        //$this->pdf->Text(170, 40, $_SESSION['yearperiodcode'].str_pad($this->farmercode, 5, '0', STR_PAD_LEFT));
        $this->liney = $this->startrec;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        if ($this->customercategorycode==1)
            $this->textbox('सभासद साखर रोख मेमो',120,10,'S','C',1,'siddhanta',12);
        else if ($this->customercategorycode==2)
            $this->textbox('कामगार साखर रोख मेमो',120,10,'S','C',1,'siddhanta',12);

        $this->newrow(7);
        $this->textbox('वाटप वर्ष : '.$this->yearperiod,120,10,'S','C',1,'siddhanta',11);
        $this->newrow(7);
    }
   

    function group()
    {
        $this->totalgroupcount=1;
        $this->summary['NETWEIGHT']=0;

        $cond = "t.yearcode=".$_SESSION['yearperiodcode'];

        if ($this->invoicenumber!='' and $this->invoicenumber!=0)
            $cond = $cond." and invoicenumber=".$this->invoicenumber;
        
        if ($this->transactionnumber!='' and $this->transactionnumber!=0)
            $cond = $cond." and t.transactionnumber=".$this->transactionnumber;
            
        $group_query_1 = "select y.periodname_unicode,v.villagenameuni,c.refcode,c.customernameuni,c.refcode,t.invoicenumberprefix,to_char(t.invoicedate,'dd/mm/YYYY') invoicedate,s.quantity,s.rate,s.amount,s.bagquantity,s.bagrate,t.invoiceamount
        ,c.customercategorycode
        from CONCESSIONSALEHEADER t,concessionsaledetail s,customer c
        ,nst_nasaka_db.yearperiod y,nst_nasaka_agriculture.farmer f
        ,nst_nasaka_agriculture.village v
        where t.transactionnumber=s.transactionnumber and t.customercode=c.customercode
        and t.yearcode = y.yearperiodcode and c.refcode=f.farmercode 
        and {$cond}
        and f.villagecode=v.villagecode";
        
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
        $this->yearperiod = $group_row_1['PERIODNAME_UNICODE'];
        $this->customercategorycode = $group_row_1['CUSTOMERCATEGORYCODE'];
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,205,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        $this->setfieldwidth(80,10);
        $this->textbox('नंबर : '.$group_row_1['INVOICENUMBERPREFIX'],$this->w,$this->x,'N','L',1,'siddhanta',12);
        $this->setfieldwidth(80);
        $this->textbox('दिनांक‌ : '.$group_row_1['INVOICEDATE'],$this->w,$this->x,'N','L',1,'siddhanta',12);
        $this->newrow(7);
        $this->setfieldwidth(80,10);
        $this->textbox('नाव : '.$group_row_1['REFCODE'].' '.$group_row_1['CUSTOMERNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->setfieldwidth(80);
        $this->textbox('गाव : '.$group_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->newrow(7);

        $this->hline(10,135,$this->liney,'C');
        $this->setfieldwidth(30,10);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('तपशिल',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('वजन',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(15);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('एकक',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(20);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('दर',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);
        $this->newrow();
        //$this->hline(10,135,$this->liney,'C');
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
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,205,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
            //$this->newpage(True);
        }

        $this->hline(10,135,$this->liney,'C');
        $this->setfieldwidth(30,10);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('साखर',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(30);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox($group_row_1['QUANTITY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(15);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('कि.ग्रॅ.',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox($group_row_1['RATE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(30);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox($group_row_1['AMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);
        $this->newrow();
        $this->hline(10,135,$this->liney,'D');
        if ($group_row_1['BAGQUANTITY']>0)
        {
            //$this->hline(10,135,$this->liney,'C');
            $this->setfieldwidth(30,10);
            $this->vline($this->liney,$this->liney+7,$this->x);
            $this->textbox('बारदाणा',$this->w,$this->x,'S','L',1,'siddhanta',11);
            $this->setfieldwidth(30);
            $this->vline($this->liney,$this->liney+7,$this->x);
            $this->textbox($group_row_1['BAGQUANTITY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(15);
            $this->vline($this->liney,$this->liney+7,$this->x);
            $this->textbox('नग',$this->w,$this->x,'S','L',1,'siddhanta',11);
            $this->setfieldwidth(20);
            $this->vline($this->liney,$this->liney+7,$this->x);
            $this->textbox($group_row_1['BAGRATE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->setfieldwidth(30);
            $this->vline($this->liney,$this->liney+7,$this->x);
            $this->textbox($group_row_1['BAGQUANTITY']*$group_row_1['BAGRATE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
            $this->vline($this->liney,$this->liney+7,$this->x+$this->w);
            $this->newrow();
            $this->hline(10,135,$this->liney,'D');
        }
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,405,$this->liney,'D');  
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
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,205,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
            //$this->newpage(True);
        }

        $this->hline(10,135,$this->liney,'C');
        $this->setfieldwidth(30,10);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('एकूण',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(30);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->setfieldwidth(15);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->setfieldwidth(20);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->setfieldwidth(30);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox($group_row_1['INVOICEAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);
        $this->newrow();
        $this->hline(10,135,$this->liney,'C'); 
        $this->newrow();
        $this->setfieldwidth(30,100);
        $this->textbox('सेल्समन',$this->w,$this->x,'S','L',1,'siddhanta',11);
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