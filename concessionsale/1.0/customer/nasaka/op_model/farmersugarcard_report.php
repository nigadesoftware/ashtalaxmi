<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a5_l_card.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class farmersugarcard extends reportbox
{
    public $farmercode;
    public $villagecode;
    public $circlecode;
    Public $centrecode;
    public $customercode;
    public $periodnameunicode;
    public $upddate;
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
	    $this->pdf = new MYPDF('L', PDF_UNIT, 'A5', true, 'UTF-8', false);
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
        $this->group();
        $this->reportfooter();
    }

	function pageheader()
    {
        $this->pdf->Image("../img/kadwawatermark.png", 60, 35, 70, 70, '', '', '', false, 300, '', false, false, 0);
        $this->pdf->SetFont('helvetica', 'I', 8);
        $this->pdf->write2DBarcode(nigsimencrypt($_SESSION['yearperiodcode'].'1'.str_pad($this->customercode, 6, '0', STR_PAD_LEFT)), 'QRCODE,H', 170, 15, 25, 25, $style, 'N');
        //$this->pdf->Text(170, 40, nigsimencrypt($_SESSION['yearperiodcode'].'1'.str_pad($this->customercode, 6, '0', STR_PAD_LEFT)));
        $this->liney = 15;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->textbox('   सभासद साखर वाटप कार्ड',180,10,'S','C',1,'siddhanta',12);
        $this->newrow(7);
        $this->textbox('वाटप वर्ष : '.$this->periodnameunicode,180,10,'S','C',1,'siddhanta',11);
        $this->newrow(7);
    }
   

    function group()
    {
        $this->totalgroupcount=3;
        $this->summary['NETWEIGHT']=0;

        $cond = "d.yearcode=".$_SESSION['yearperiodcode'];

        if ($this->farmercode!='' and $this->farmercode!=0)
            $cond = $cond." and r.farmercode=".$this->farmercode;

        if ($this->villagecode!='' and $this->villagecode!=0)
            $cond = $cond." and v.villagecode=".$this->villagecode;

        if ($this->circlecode!='' and $this->circlecode!=0)
            $cond = $cond." and c.circlecode=".$this->circlecode;   

        if ($this->centrecode!='' and $this->centrecode!=0)
            $cond = $cond." and centrecode=".$this->centrecode;   

        if ($this->upddate !='')
            $cond1 = "trunc(d.upddate) = '".DateTime::createFromFormat('d/m/Y',$this->upddate)->format('d-M-Y')."'";
        else
            $cond1 = "1=1";

        $group_query_1 = "with data as (select t.yearcode,c.customercode,c.refcode farmercode,sum(sharesugar) sharesugar,sum(canesugar) canesugar,sum(benesugar) benesugar
        from (
        select yearcode,customercode
        ,case when concessioncategorycode=1 then quantity else 0 end sharesugar
        ,case when concessioncategorycode=2 then quantity else 0 end canesugar
        ,case when concessioncategorycode=4 then quantity else 0 end benesugar
        from (
        select t.yearcode,d.customercode,t.concessioncategorycode,sum(d.quantity) quantity
        from ALLOTMENTHEADER t,allotmentdetail d,customer c
        where t.customercategorycode=1 and t.transactionnumber=d.transactionnumber and d.customercode=c.customercode
        and duesflag is null and {$cond1}
        group by t.yearcode,d.customercode,t.concessioncategorycode))t,customer c
        where t.customercode=c.customercode
        group by t.yearcode,c.customercode,c.refcode)
        select c.circlecode,v.villagecode,d.customercode,d.farmercode,y.periodname_unicode,sharesugar,canesugar,benesugar,nvl(sharesugar,0)+nvl(canesugar,0)+nvl(benesugar,0) totalsugar,f.numberofshares,f.numberofbeneguntha,f.suppliedtonnage,c.circlenameuni,v.villagenameuni,r.farmernameuni 
        from data d,farmersugarbase f,nst_nasaka_agriculture.farmer r,nst_nasaka_agriculture.village v,nst_nasaka_agriculture.circle c
        ,nst_nasaka_db.yearperiod y
        where d.farmercode=r.farmercode and r.villagecode=v.villagecode and v.circlecode=c.circlecode
        and {$cond}
        and d.yearcode=f.yearcode(+) and d.farmercode=f.farmercode(+)
        and d.yearcode=y.yearperiodcode
        order by c.circlecode,v.villagenameuni,v.villagecode,r.farmernameuni,f.farmercode
        ";
        
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
    }

    function groupheader_2(&$group_row_1)
    {

    }

    function groupheader_3(&$group_row_1)
    {
        $this->farmercode=$group_row_1['FARMERCODE'];
        $this->customercode=$group_row_1['CUSTOMERCODE'];
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
        $this->periodnameunicode=$group_row_1['PERIODNAME_UNICODE'];
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
            $this->newpage(True);
        }
        $this->textbox('सभासद : ('.$group_row_1['FARMERCODE'].') '.$group_row_1['FARMERNAMEUNI'],180,10,'S','C',1,'siddhanta',12);
        $this->newrow(7);
        $this->textbox('गट : '.$group_row_1['CIRCLENAMEUNI'].' गाव : '.$group_row_1['VILLAGENAMEUNI'],180,10,'S','C',1,'siddhanta',11);
        $this->newrow(7);
        $y=$this->liney;
        $this->hline(10,100,$this->liney,'C');
        $this->setfieldwidth(30,10);
        $this->textbox('   तपशिल',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(30);
        $this->textbox('',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->setfieldwidth(30);
        $this->textbox('साखर (कि.)',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->newrow();

        $this->hline(10,100,$this->liney,'C');
        $this->setfieldwidth(30,10);
        $this->textbox('धारण केलेले भाग',$this->w,$this->x,'S','L',1,'siddhanta',10);
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['NUMBEROFSHARES'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['SHARESUGAR'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'B');
        $this->newrow();
        $this->hline(10,100,$this->liney,'C');
        $this->setfieldwidth(30,10);
        $this->textbox('ऊस पुरवठा ('.floor(($_SESSION['yearperiodcode']-10001)/10000).'-'.floor(($_SESSION['yearperiodcode']-10001)%10000).')',$this->w,$this->x,'S','L',1,'siddhanta',10,'','Y');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['SUPPLIEDTONNAGE'].' (मे.टन)',$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['CANESUGAR'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11,'B');
        $this->newrow(10);
        $this->setfieldwidth(30,10);
        $this->textbox('बेणे पुरवठा ('.floor(($_SESSION['yearperiodcode']-10001)/10000).'-'.floor(($_SESSION['yearperiodcode']-10001)%10000).')',$this->w,$this->x,'S','L',1,'siddhanta',10,'','Y');
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['NUMBEROFBENEGUNTHA'].' (आर)',$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',12);
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['BENESUGAR'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',12,'B');
        $this->hline(10,100,$this->liney,'C');
        $this->newrow(10);
        $this->hline(10,100,$this->liney-2,'C'); 
        $this->setfieldwidth(30,10);
        $this->textbox('एकूण',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['TOTALSUGAR'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',12,'B');
        $this->hline(10,100,$this->liney-2,'C'); 
        $this->newrow();
        $this->hline(10,100,$this->liney-2,'C'); 
        $this->vline($this->liney-2,$this->liney-41,10,'C');
        $this->vline($this->liney-2,$this->liney-41,40,'C');
        $this->vline($this->liney-2,$this->liney-41,70,'C');
        $this->vline($this->liney-2,$this->liney-41,100,'C');
        $this->newrow(10);
        $this->setfieldwidth(90,10);
        $this->textbox('कार्यकारी संचालक',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','');
        $this->newrow();
        $this->textbox('सुचना :',$this->w,$this->x,'S','L',1,'siddhanta',8,'','','','');
        $this->newrow(4);
        $this->setfieldwidth(100,10);
        $this->textbox('१) साखर कार्डशिवाय साखर मिळणार नाही .',$this->w,$this->x,'S','L',1,'siddhanta',8,'','','','');
        $this->newrow(4);
        $this->setfieldwidth(100,10);
        $this->textbox('२) साखर कार्ड खराब झाल्यास / हरवल्यास रु.१००(शंभर) भरुन नविन कार्ड मिळेल.',$this->w,$this->x,'S','L',1,'siddhanta',8,'','Y','','');
        $this->newrow(4);
        $this->setfieldwidth(100,10);
        $this->textbox('३) कार्डावरील पुर्ण साखर घेतल्यास कार्ड दुकानात जमा करुन घेतले जाईल.',$this->w,$this->x,'S','L',1,'siddhanta',8,'','Y','','');
        $this->newrow(4);
        $this->setfieldwidth(100,10);
        $this->textbox('४) ज्या सभासदांची शेअर्स रक्कम अपूर्ण आहे व कारखाना इतर थकबाकी आहे.       त्यांनी सदर रकमेचा भरणा केल्या नंतर कार्ड दिले जाईल.',$this->w,$this->x,'S','L',1,'siddhanta',8,'','Y','','');
        $this->newrow(8);
        $this->setfieldwidth(100,10);
        $this->textbox('५) सभासद साखर घेण्याची अंतिम मुदत दि.३0 एप्रिल २०२३ पर्यंत राहील.            मुदत वाढ दिली जाणार नाही.',$this->w,$this->x,'S','L',1,'siddhanta',8,'','Y','','');
        $this->newrow(8);
        $this->setfieldwidth(100,10);
        $this->textbox('६) शेअर्सची किंमत रु.१५०००/- असून देय रक्कम भरणा करणेचे सहकार्य करावे.',$this->w,$this->x,'S','L',1,'siddhanta',8,'','Y','','');
        $this->liney=$y;

        $this->hline(110,200,$this->liney,'C');
        $this->setfieldwidth(25,115);
        $this->textbox('दिनांक',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(30);
        $this->textbox('प्रत्यक्ष घेतलेली साखर (किलो)',$this->w,$this->x,'S','R',1,'siddhanta',11,'','Y','','');
        $this->setfieldwidth(30);
        $this->textbox('विक्रेता सही',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->newrow(10);

        $this->hline(110,200,$this->liney,'C');
        $this->newrow();
        $this->hline(110,200,$this->liney,'C');
        $this->newrow();
        $this->hline(110,200,$this->liney,'C');
        $this->newrow();
        $this->hline(110,200,$this->liney,'C');
        $this->newrow();
        $this->hline(110,200,$this->liney,'C');
        $this->newrow();
        $this->hline(110,200,$this->liney,'C');
        $this->newrow();
        $this->hline(110,200,$this->liney,'C');
        $this->newrow();
        $this->hline(110,200,$this->liney,'C');
        $this->newrow();
        $this->hline(110,200,$this->liney,'C');
        $this->newrow();
        $this->hline(110,200,$this->liney,'C');
        $this->newrow();
        $this->hline(110,200,$this->liney,'C');
        $this->newrow();
        $this->hline(110,200,$this->liney,'C');
        //$this->newrow();
        //$this->hline(110,200,$this->liney,'C');

        $this->vline($this->liney,$this->liney-87,110,'C');
        $this->vline($this->liney,$this->liney-87,140,'C');
        $this->vline($this->liney,$this->liney-87,170,'C');
        $this->vline($this->liney,$this->liney-87,200,'C'); 
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