<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_legal_l.php");
    include_once("../info/routine.php");
    include_once("../ip_model/accounthead_db_oracle.php");
    include_once("../ip_model/accountsubledger_db_oracle.php");
//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class ledger extends swappreport
{	
    public $fromdate;
    public $todate;
    public $accountcode;    
    public $yearcode;
  
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
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
        $this->pdf->SetSubject('Ledger');
        $this->pdf->SetKeywords('LED_000.MR');
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));

        $title = str_pad(' ', 30).'श्री संत जनार्दन स्वामी नगर, ता.नाशिक, जि.नाशिक';
    	$this->pdf->SetHeaderData('', 0,str_pad(' ', 21).'नाशिक स.सा.का.लि.,' ,$title);
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
        $lg['w_page'] = 'पान - ';
        // set some language-dependent strings (optional)
	    $this->pdf->setLanguageArray($lg);
	}
    function endreport()
    {
        try
        {
            // reset pointer to the last page*/
            ob_end_clean();
            $this->pdf->lastPage();
            // ---------------------------------------------------------
            //Close and output PDF document
            $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
            // set array for viewer preferences
$preferences = array(
    'HideToolbar' => true,
    'HideMenubar' => true,
    'HideWindowUI' => true,
    'FitWindow' => true,
    'CenterWindow' => true,
    'DisplayDocTitle' => true,
    'NonFullScreenPageMode' => 'UseNone', // UseNone, UseOutlines, UseThumbs, UseOC
    'ViewArea' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
    'ViewClip' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
    'PrintArea' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
    'PrintClip' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
    'PrintScaling' => 'AppDefault', // None, AppDefault
    'Duplex' => 'DuplexFlipLongEdge', // Simplex, DuplexFlipShortEdge, DuplexFlipLongEdge
    'PickTrayByPDFSize' => true,
    'PrintPageRange' => array(1,1,2,3),
    'NumCopies' => 2
);

// Check the example n. 60 for advanced page settings

// set pdf viewer preferences
$this->pdf->setViewerPreferences($preferences);

            $this->pdf->Output('LED_000.pdf', 'I');
        }
        catch(Exception $e) 
        {
            echo 'Message: ' .$e->getMessage();
        }
    }
	function pageheader()
    {
        $this->liney = 15;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        $this->textbox('खतावणी',180,10,'S','C',1,'siddhanta',13);
        $this->newrow();
        $frdt = DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y');
        $todt = DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y');
        $this->textbox('दिनांक '.$frdt.' पासुन '.$todt.' पर्यंत',200,10,'S','C',1,'siddhanta',10);
        $this->newrow();
        $accounthead1 = new accounthead($this->connection);
        $accounthead1->accountcode = $this->accountcode;
        $accounthead1->fetch();
        $this->textbox('खाते :'.$accounthead1->accountcode.' '.$accounthead1->accountnameuni,150,10,'S','L',1,'siddhanta',12);
        $this->newrow(7);
        $this->hline(10,200);
        $this->textbox('व्हा.दि./नं.',18,10,'S','L',1,'siddhanta',11);
        $this->textbox('तपशील',70,30,'S','L',1,'siddhanta',11);
        $this->textbox('नावे',25,125,'S','R',1,'siddhanta',11);
        $this->textbox('जमा',25,150,'S','R',1,'siddhanta',11);
        $this->textbox('शिल्लक',25,175,'S','R',1,'siddhanta',11);
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow();
        //$this->drawlines($limit);
    }

    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        $liney = $this->liney;
        $this->liney = 48;
        $this->vline($this->liney-12,$this->liney+$limit,10);
        $this->vline($this->liney-12,$this->liney+$limit,30,'D');
        //$this->vline($this->liney-12,$this->liney+$limit,55,'D');
        $this->vline($this->liney-12,$this->liney+$limit,120,'D');
        $this->vline($this->liney-12,$this->liney+$limit,125,'D');
        $this->vline($this->liney-12,$this->liney+$limit,150,'D');
        $this->vline($this->liney-12,$this->liney+$limit,175,'D');
        $this->vline($this->liney-12,$this->liney+$limit,200,'D');
        $this->hline(10,200,$this->liney+$limit);
        $this->hline(10,200,$this->liney+$limit);
        $this->liney = $liney;
    }

    function pagefooter($islastpage=false)
    {
        if ($islastpage==false)
        {
            $this->drawlines($this->maxlines-48);
        }
        else
        {
            $this->drawlines($this->liney-48);
        }
        
    }

    function detail()
    {
        $query_openingbalance = "select 
        accountopeningbalance(".$this->yearcode.
        ",".$this->accountcode.
        ",'".$this->fromdate."') as openingbalance from dual";
        $result_openingbalance = oci_parse($this->connection, $query_openingbalance);
        $r = oci_execute($result_openingbalance);
        if ($row_openingbalance = oci_fetch_array($result_openingbalance,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $frdt = DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y');
            $this->textbox($frdt,25,10,'S','L',1,'SakalMarathiNormal922',9);
            $this->textbox('आरंभीची शिल्लक',70,30,'S','L',1,'siddhanta',10);
            $closingbalance=$row_openingbalance['OPENINGBALANCE'];
            if ($row_openingbalance['OPENINGBALANCE']>=0)
            {
                $this->textbox($row_openingbalance['OPENINGBALANCE'].'Dr',25,175,'S','R',1,'SakalMarathiNormal922',9);   
            }
            else
            {
                $this->textbox(abs($row_openingbalance['OPENINGBALANCE']).'Cr',25,175,'S','R',1,'SakalMarathiNormal922',9);   
            }
        }
        $query ="select h.transactionnumber,h.vouchernumberprefixsufix
        ,h.voucherdate
        ,v.vouchertypenameeng
        ,h.vouchersubtypecode
        ,h.narration
        ,h.description
        ,nvl(d.credit,0) as credit
        ,nvl(d.debit,0) as debit
        ,case when h.vouchersubtypecode<>19 then
        oppaccountheadledger(h.transactionnumber,d.accountcode) else '' end as oppaccountheadnameuni
        from voucherdetail d,voucherheader h,vouchersubtype s,vouchertype v
        where d.transactionnumber=h.transactionnumber
        and h.vouchersubtypecode=s.vouchersubtypecode
        and s.vouchertypecode=v.vouchertypecode
        and h.voucherdate>='".$this->fromdate."'
        and h.voucherdate<='".$this->todate."'
        and h.approvalstatus=9
        and d.accountcode = ".$this->accountcode.
        " order by h.voucherdate,h.vouchernumberprefixsufix";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $srno=1;
        $credittotal=0;
        $debittotal=0;
        $height2=5;
        $this->newrow($height2);
        $this->hline(10,200,$this->liney,'D');
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            //$this->newrow($height2);
            $this->pdf->SetFont('siddhanta', '', 10, '', true);
            $height1 = $this->height($row['OPPACCOUNTHEADNAMEUNI'],90);
            $this->pdf->SetFont('siddhanta', '', 8, '', true);
            $height2 = $this->height($row['DESCRIPTION'].' '.$row['NARRATION'],90);
            $height=$height1+$height2;
            if ($this->isnewpage($height))
            {
                $this->newpage($height);
            }
            $this->textbox($row['OPPACCOUNTHEADNAMEUNI'],90,30,'S','L',1,'siddhanta',10,'','Y');
            $this->pdf->SetFont('siddhanta', '', 7, '', true);
            $this->textbox(substr($row['VOUCHERTYPENAMEENG'],0,3),15,111,'S','R',1,'SakalMarathiNormal922',8);
            $vdt = DateTime::createFromFormat('d-M-y',$row['VOUCHERDATE'])->format('d/m/Y');
            $this->textbox($vdt,22,10,'S','L',1,'SakalMarathiNormal922',9);
            $this->textbox($row['CREDIT'],25,150,'S','R',1,'SakalMarathiNormal922',9); 
            $this->textbox($row['DEBIT'],25,125,'S','R',1,'SakalMarathiNormal922',9); 
            $closingbalance=$closingbalance+$row['DEBIT']-$row['CREDIT'];
            $credittotal=$credittotal+$row['CREDIT'];
            $debittotal=$debittotal+$row['DEBIT'];
            if ($closingbalance>0)
            {
                $this->textbox($closingbalance."Dr",25,175,'S','R',1,'SakalMarathiNormal922',9);   
            }
            else
            {
                $this->textbox(abs($closingbalance)."Cr",25,175,'S','R',1,'SakalMarathiNormal922',9);   
            }
            $this->newrow($height1);
            $this->pdf->SetFont('siddhanta', '', 7, '', true);
            $this->textbox($row['VOUCHERNUMBERPREFIXSUFIX'],20,10,'S','L',1,'siddhanta',10);
            $height2 = $this->textbox($row['DESCRIPTION'].' '.$row['NARRATION'],90,30,'S','L',1,'siddhanta',8,'','Y');
            //$this->newrow(2);
            $this->newrow($height2);

            if ($row['VOUCHERSUBTYPECODE']==2 or $row['VOUCHERSUBTYPECODE']==5)
            {
                $query1 ="select funddocumentnameuni
                ,v.funddocumentnumber
                ,to_char(funddocumentdate,'dd/mm/yyyy') as funddocumentdate
                ,funddocumentamount
                ,draweebank
                from voucherchequedddetail v,funddocumenttype t
                where v.funddocumentcode=t.funddocumentcode 
                and v.transactionnumber=".$row['TRANSACTIONNUMBER'];
                $result1 = oci_parse($this->connection, $query1);
                $r1 = oci_execute($result1);
                while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    $height3=$this->textbox($row1['FUNDDOCUMENTNAMEUNI'].' नंबर: '.$row1['FUNDDOCUMENTNUMBER'].' दि. '.$row1['FUNDDOCUMENTDATE'].' रक्कम: '.$row1['FUNDDOCUMENTAMOUNT'].' ड्रायी बँक: '.$row1['DRAWEEBANK'],90,30,'S','L',1,'siddhanta',8,'','Y');
                    $this->newrow($height3);
                }        
            }
            $this->hline(10,200,$this->liney,'D');
            $this->newrow(1);
        }
          $this->newrow($height2);
          $this->hline(10,200,$this->liney);
          $this->textbox($credittotal,25,150,'S','R',1,'SakalMarathiNormal922',9); 
          $this->textbox($debittotal,25,125,'S','R',1,'SakalMarathiNormal922',9);
          $this->newrow();
          $this->hline(10,200,$this->liney);
          $this->pagefooter(true);
    }

    function export()
    {
        header('Content-Encoding: UTF-8');
        header('Content-Type: text/csv; charset=utf-8' );
        header(sprintf( 'Content-Disposition: attachment; filename=ledger-%s.csv', date( 'dmY_hisA' ) ) );
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        $fp1=fopen('php://memory', 'w');
        fputs( $fp1, "\xEF\xBB\xBF" ); // UTF-8 BOM !!!!!
        $accounthead1 = new accounthead($this->connection);
        $accounthead1->accountcode = $this->accountcode;
        $accounthead1->fetch();

        fputcsv($fp1, array('#','खाते कोड','खाते नाव','व्हा.दि.','व्हा.नं.','नावे','जमा','शिल्लक','शि.प्रकार','तपशील1','तपशील2','तपशील3','#'));
        $query_openingbalance = "select 
        accountopeningbalance(".$this->yearcode.
        ",".$this->accountcode.
        ",'".$this->fromdate."') as openingbalance from dual";
        $result_openingbalance = oci_parse($this->connection, $query_openingbalance);
        $r = oci_execute($result_openingbalance);
        if ($row_openingbalance = oci_fetch_array($result_openingbalance,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $frdt = DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y');
            //$this->textbox($frdt,25,10,'S','L',1,'SakalMarathiNormal922',9);
            //$this->textbox('आरंभीची शिल्लक',70,30,'S','L',1,'siddhanta',10);
            $closingbalance=$row_openingbalance['OPENINGBALANCE'];
            if ($row_openingbalance['OPENINGBALANCE']>=0)
            {
                //$this->textbox($row_openingbalance['OPENINGBALANCE'].'Dr',25,175,'S','R',1,'SakalMarathiNormal922',9);   
                fputcsv($fp1, array('#',$this->accountcode,$accounthead1->accountnameuni,$vdt,'',$row['OPENINGBALANCE'],0,$row_openingbalance['OPENINGBALANCE'],'Dr','आरंभीची शिल्लक','','','#'));
            }
            else
            {
                //$this->textbox(abs($row_openingbalance['OPENINGBALANCE']).'Cr',25,175,'S','R',1,'SakalMarathiNormal922',9);   
                fputcsv($fp1, array('#',$this->accountcode,$accounthead1->accountnameuni,$vdt,'',0,$row['OPENINGBALANCE'],$row_openingbalance['OPENINGBALANCE'],'Cr','आरंभीची शिल्लक','','','#'));
            }
        }
        $query ="select h.transactionnumber,h.vouchernumberprefixsufix
        ,h.voucherdate
        ,v.vouchertypenameeng
        ,h.vouchersubtypecode
        ,nvl(h.narration,' ') narration
        ,nvl(h.description,' ') description
        ,nvl(d.credit,0) as credit
        ,nvl(d.debit,0) as debit
        ,nvl(case when h.vouchersubtypecode<>19 then
        oppaccountheadledger(h.transactionnumber,d.accountcode) else ' ' end,' ') as oppaccountheadnameuni
        from voucherdetail d,voucherheader h,vouchersubtype s,vouchertype v
        where d.transactionnumber=h.transactionnumber
        and h.vouchersubtypecode=s.vouchersubtypecode
        and s.vouchertypecode=v.vouchertypecode
        and h.voucherdate>='".$this->fromdate."'
        and h.voucherdate<='".$this->todate."'
        and h.approvalstatus=9
        and d.accountcode = ".$this->accountcode.
        " order by h.voucherdate,h.vouchernumberprefixsufix";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $srno=1;
        $credittotal=0;
        $debittotal=0;
        $height2=5;
        //$this->newrow($height2);
        //$this->hline(10,200,$this->liney,'D');
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            //$this->newrow($height2);
            //$this->pdf->SetFont('siddhanta', '', 10, '', true);
            //$height1 = $this->height($row['OPPACCOUNTHEADNAMEUNI'],90);
            //$this->pdf->SetFont('siddhanta', '', 8, '', true);
            //$height2 = $this->height($row['DESCRIPTION'].' '.$row['NARRATION'],90);
            //$height=$height1+$height2;
            //if ($this->isnewpage($height))
            //{
                //$this->newpage($height);
            //}
            //$this->textbox($row['OPPACCOUNTHEADNAMEUNI'],90,30,'S','L',1,'siddhanta',10,'','Y');
            //$this->pdf->SetFont('siddhanta', '', 7, '', true);
            //$this->textbox(substr($row['VOUCHERTYPENAMEENG'],0,3),15,111,'S','R',1,'SakalMarathiNormal922',8);
            $vdt = DateTime::createFromFormat('d-M-y',$row['VOUCHERDATE'])->format('d/m/Y');
            //$this->textbox($vdt,22,10,'S','L',1,'SakalMarathiNormal922',9);
            //$this->textbox($row['CREDIT'],25,150,'S','R',1,'SakalMarathiNormal922',9); 
            //$this->textbox($row['DEBIT'],25,125,'S','R',1,'SakalMarathiNormal922',9); 
            $closingbalance=$closingbalance+$row['DEBIT']-$row['CREDIT'];
            $credittotal=$credittotal+$row['CREDIT'];
            $debittotal=$debittotal+$row['DEBIT'];
            if ($closingbalance>0)
            {
                //$this->textbox($closingbalance."Dr",25,175,'S','R',1,'SakalMarathiNormal922',9); 
                fputcsv($fp1, array('#',$this->accountcode,$accounthead1->accountnameuni,$vdt,$row['VOUCHERNUMBERPREFIXSUFFIX'],$row['DEBIT'],$row['CREDIT'],$closingbalance,'Dr',$row['OPPACCOUNTHEADNAMEUNI'],$row['DESCRIPTION'],$row['NARRATION'],'#'));
  
            }
            else
            {
                //$this->textbox(abs($closingbalance)."Cr",25,175,'S','R',1,'SakalMarathiNormal922',9);  
                fputcsv($fp1, array($this->accountcode,$accounthead1->accountnameuni,$vdt,$row['VOUCHERNUMBERPREFIXSUFFIX'],$row['DEBIT'],$row['CREDIT'],abs($closingbalance),'Cr',$row['OPPACCOUNTHEADNAMEUNI'],$row['DESCRIPTION'],$row['NARRATION'],'#')); 
            }
            //$this->newrow($height1);


            /* $this->pdf->SetFont('siddhanta', '', 7, '', true);
            $this->textbox($row['VOUCHERNUMBERPREFIXSUFIX'],20,10,'S','L',1,'siddhanta',10);
            $height2 = $this->textbox($row['DESCRIPTION'].' '.$row['NARRATION'],90,30,'S','L',1,'siddhanta',8,'','Y');
            //$this->newrow(2);
            $this->newrow($height2); */
            /* if ($row['VOUCHERSUBTYPECODE']==2 or $row['VOUCHERSUBTYPECODE']==5)
            {
                $query1 ="select funddocumentnameuni
                ,v.funddocumentnumber
                ,to_char(funddocumentdate,'dd/mm/yyyy') as funddocumentdate
                ,funddocumentamount
                ,draweebank
                from voucherchequedddetail v,funddocumenttype t
                where v.funddocumentcode=t.funddocumentcode 
                and v.transactionnumber=".$row['TRANSACTIONNUMBER'];
                $result1 = oci_parse($this->connection, $query1);
                $r1 = oci_execute($result1);
                while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    $height3=$this->textbox($row1['FUNDDOCUMENTNAMEUNI'].' नंबर: '.$row1['FUNDDOCUMENTNUMBER'].' दि. '.$row1['FUNDDOCUMENTDATE'].' रक्कम: '.$row1['FUNDDOCUMENTAMOUNT'].' ड्रायी बँक: '.$row1['DRAWEEBANK'],90,30,'S','L',1,'siddhanta',8,'','Y');
                    $this->newrow($height3);
                }        
            }
            $this->hline(10,200,$this->liney,'D');
            $this->newrow(1); */
        }
          //$this->newrow($height2);
          //$this->hline(10,200,$this->liney);
          //$this->textbox($credittotal,25,150,'S','R',1,'SakalMarathiNormal922',9); 
          //$this->textbox($debittotal,25,125,'S','R',1,'SakalMarathiNormal922',9);
          //$this->newrow();
          //$this->hline(10,200,$this->liney);
          //$this->pagefooter(true);
          // reset the file pointer to the start of the file
            fputcsv($fp1, array('#',$this->accountcode,$accounthead1->accountnameuni,'','',$debittotal,$credittotal,'','','एकूण','','','#'));

          fseek($fp1, 0);
          // tell the browser it's going to be a csv file
          //header('Content-Type: application/csv');
          // tell the browser we want to save it instead of displaying it
          //header('Content-Disposition: attachment; filename="ledger.csv";');
          // make php send the generated csv lines to the browser
          fpassthru($fp1); 
          //fclose($fp1);
    }


}    
?>