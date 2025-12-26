<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a6_l.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class employeesugarcard extends reportbox
{
    public $farmercode;
    public $villagecode;
    public $circlecode;
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
	    $this->pdf = new MYPDF('L', PDF_UNIT, 'A6', true, 'UTF-8', false);
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
        $this->pdf->Image("../img/kadwawatermark.png", 40, 30, 50, 50, '', '', '', false, 300, '', false, false, 0);
        $this->pdf->SetFont('helvetica', 'I', 8);
        $code=$_SESSION['yearperiodcode'].'2'.str_pad($this->customercode, 6, '0', STR_PAD_LEFT);
        $code_en = nigsimencrypt($code);
        $code_de = nigsimdecrypt($code_en);
        $this->pdf->write2DBarcode($code_en, 'QRCODE,H', 110, 25, 25, 30, $style, 'N');
        //$this->pdf->Text(170, 40, nigsimencrypt($_SESSION['yearperiodcode'].'1'.str_pad($this->customercode, 6, '0', STR_PAD_LEFT)));
        $this->liney = 12;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->textbox('कामगार साखर वाटप कार्ड वर्ष : '.substr($_SESSION['yearperiodcode'],0,4),130,10,'S','C',1,'siddhanta',12);
        $this->newrow(5);
    }
   

    function group()
    {
        $this->totalgroupcount=0;
        $this->summary['NETWEIGHT']=0;

        $cond = "t.yearcode=".$_SESSION['yearperiodcode'];

        

        $group_query_1 = "
        with deptdata as (select t.customercode,p.departmentnameuni,d.ndept_code
        from customer t,pay_sub_dept_master@oldpayroll d,department p,pay_emp_master@oldpayroll e
        where t.customercategorycode=2
        and e.nsect_code=d.nsub_dept_code
        and d.ndept_code=p.departmentcode(+) and t.refcode=e.nemp_code)
        select c.customercode, c.customernameuni,c.refcode,d.departmentnameuni
        from customer c,deptdata d
        where c.customercategorycode=2
        and c.customercode=d.customercode(+) 
        order by d.ndept_code,refcode";
        
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
        //$this->periodnameunicode=$group_row_1['PERIODNAME_UNICODE'];
        $this->customercode = $group_row_1['CUSTOMERCODE'];
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
        $this->textbox('नांव : ('.$group_row_1['REFCODE'].') '.$group_row_1['CUSTOMERNAMEUNI'].' - '.$group_row_1['DEPARTMENTNAMEUNI'],180,30,'S','L',1,'siddhanta',12);

        //$this->liney=$y;
        $this->newrow(7);
        $this->hline(10,100,$this->liney,'C');
        $this->setfieldwidth(20,15);
        $this->textbox('महिना',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox('दिनांक',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(20);
        $this->textbox('साखर (कि.)',$this->w,$this->x,'S','L',1,'siddhanta',11,'','Y','','');
        $this->setfieldwidth(30);
        $this->textbox('विक्रेता सही',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->newrow(10);

        $this->hline(10,100,$this->liney,'C');
        $this->setfieldwidth(20,15);
        $this->textbox('जानेवारी',$this->w+2,$this->x-4,'S','L',1,'siddhanta',9);
        $this->newrow(5);
        $this->hline(10,100,$this->liney,'C');
        $this->setfieldwidth(20,15);
        $this->textbox('फेब्रुवारी',$this->w+2,$this->x-4,'S','L',1,'siddhanta',9);
        $this->newrow(5);
        $this->hline(10,100,$this->liney,'C');
        $this->setfieldwidth(20,15);
        $this->textbox('मार्च',$this->w+2,$this->x-4,'S','L',1,'siddhanta',9);
        $this->newrow(5);
        $this->hline(10,100,$this->liney,'C');
        $this->setfieldwidth(20,15);
        $this->textbox('एप्रिल',$this->w+2,$this->x-4,'S','L',1,'siddhanta',9);
        $this->newrow(5);
        $this->hline(10,100,$this->liney,'C');
        $this->setfieldwidth(20,15);
        $this->textbox('मे',$this->w+2,$this->x-4,'S','L',1,'siddhanta',9);
        $this->newrow(5);
        $this->hline(10,100,$this->liney,'C');
        $this->setfieldwidth(20,15);
        $this->textbox('जून',$this->w+2,$this->x-4,'S','L',1,'siddhanta',9);
        $this->newrow(5);
        $this->hline(10,100,$this->liney,'C');
        $this->setfieldwidth(20,15);
        $this->textbox('जुलै',$this->w+2,$this->x-4,'S','L',1,'siddhanta',9);
        $this->newrow(5);
       // $this->setfieldwidth(35,100);
       // $this->textbox('हेड टाईम किपर',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->hline(10,100,$this->liney,'C');
        $this->setfieldwidth(20,15);
        $this->textbox('ऑगस्ट',$this->w+2,$this->x-4,'S','L',1,'siddhanta',9);
        $this->newrow(5);
        $this->hline(10,100,$this->liney,'C');
        $this->setfieldwidth(20,15);
        $this->textbox('सप्टेंबर',$this->w+2,$this->x-4,'S','L',1,'siddhanta',9);
        $this->newrow(5);
        $this->hline(10,100,$this->liney,'C');
        $this->setfieldwidth(20,15);
        $this->textbox('ऑक्टोबर',$this->w+2,$this->x-4,'S','L',1,'siddhanta',9);
        $this->newrow(5);
        $this->hline(10,100,$this->liney,'C');
        $this->setfieldwidth(20,15);
        $this->textbox('नोव्हेंबर',$this->w+2,$this->x-4,'S','L',1,'siddhanta',9);
        $this->newrow(5);
        $this->hline(10,100,$this->liney,'C');
        $this->setfieldwidth(20,15);
        $this->textbox('डिसेंबर',$this->w+2,$this->x-4,'S','L',1,'siddhanta',9);
        $this->setfieldwidth(35,105);
        $this->textbox('हेड टाईम     सचिव',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->newrow(5);
        $this->hline(10,100,$this->liney,'C');
        $this->setfieldwidth(30,90);
        $this->textbox(' किपर',$this->w,$this->x,'S','R',1,'siddhanta',11);

        //$this->newrow();
        //$this->hline(10,100,$this->liney,'C');

        $this->vline($this->liney,$this->liney-70,10,'C');
        $this->vline($this->liney,$this->liney-70,30,'C');
        $this->vline($this->liney,$this->liney-70,50,'C');
        $this->vline($this->liney,$this->liney-70,70,'C');
        $this->vline($this->liney,$this->liney-70,100,'C'); 
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,100,$this->liney,'D');  
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