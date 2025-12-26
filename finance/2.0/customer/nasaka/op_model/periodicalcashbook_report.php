<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    include_once("../ip_model/accounthead_db_oracle.php");
    include_once("../ip_model/voucherheader_db_oracle.php");
  
class periodicalcashbook extends swappreport
{	
    public $connection;
    public $liney;
    public $maxlines;
    public $pdf;
    public $cashbookdate;
    public $firstpage;
    public $totalpages;
    public $currentpage;
    public $cashopening;
    public $cashcredit;
    public $bankcredit;
    public $totalcredit;
    public $lastcreditpage;
    public $lastcreditlocation;
    public $cashclosing;
    public $cashdebit;
    public $bankdebit;
    public $totaldebit;
    public $lastdebitpage;
    public $lastdebitlocation;
    //
    public $transactionid;
 
    public function __construct(&$connection,$maxlines)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
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
        $this->pdf->SetSubject('Cashbook');
        $this->pdf->SetKeywords('CASHBOOK_000.MR');
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

	public function isnewpage($projected)
    {
        if ($this->liney+$projected>=$this->maxlines)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function newpage($force=false)
    {
        if ($force==false)
        {
            if ($this->liney >= $this->maxlines)
            {
                $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
                //$this->pdf->line(15,$this->liney,80,$this->liney);
                $this->liney = 20;
                //$resolution= array(80, 100);
                if ($this->currentpage==$this->totalpages)
                {
                    $this->pdf->line(105,30,105,278);   
                    $this->pdf->addpage('P',$resolution);
                    $this->totalpages = $this->pdf->getNumPages();
                    $this->currentpage = $this->totalpages;
                }
                else
                {
                    $this->pdf->line(105,30,105,278);   
                    $this->currentpage++;
                    $this->pdf->setpage($this->currentpage);
                    $this->liney = 38;
                }
                
                // set color for background
                $this->pdf->SetFillColor(0, 0, 0);
                // set color for text
                $this->pdf->SetTextColor(0, 0, 0);
                $this->pageheader();
            }
        }
        else
        {
            $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
            //$this->pdf->line(15,$this->liney,100,$this->liney);
            $this->liney = 20;
            //$resolution= array(80, 100);
            if ($this->currentpage==$this->totalpages)
            {
                $this->pdf->line(105,30,105,278);   
                $this->pdf->addpage();
                $this->totalpages = $this->pdf->getNumPages();
                $this->currentpage = $this->totalpages;
            }
            else
            {
                $this->pdf->line(105,30,105,278);   
                $this->currentpage++;
                $this->pdf->setpage($this->currentpage);
                $this->liney = 38;
            }
            // set color for background
            $this->pdf->SetFillColor(0, 0, 0);
            // set color for text
            $this->pdf->SetTextColor(0, 0, 0);
            $this->pageheader();
        }
    }
    function endreport()
    {
        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output('DAYBOOK_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 15;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->pdf->SetFont('siddhanta', '', 14, '', true);
        $this->pdf->multicell(30,10,'रोखकिर्द',0,'R',false,1,90,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+7;
        $this->pdf->SetFont('siddhanta', '', 13, '', true);
        $this->pdf->multicell(30,10,'जमा',0,'R',false,1,30,$this->liney,true,0,false,true,10);
        $dt = DateTime::createFromFormat('d-M-Y',$this->cashbookdate)->format('d/m/Y');
        $this->pdf->multicell(60,10,'दिनांक '.$dt,0,'R',false,1,70,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(30,10,'नावे',0,'R',false,1,150,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+7;
        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
        $this->pdf->line(10,$this->liney,205,$this->liney);
        $this->liney = $this->liney+2;
        $this->pdf->SetFont('siddhanta', '', 12, '', true);
        $this->pdf->multicell(30,10,'वाउचर नं',0,'L',false,1,10,$this->liney,true,0,false,true,10);
       	$this->pdf->multicell(60,10,'तपशील',0,'L',false,1,40,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(35,10,'रक्कम',0,'R',false,1,70,$this->liney,true,0,false,true,10);

        $this->pdf->multicell(30,10,'वाउचर नं',0,'L',false,1,105,$this->liney,true,0,false,true,10);
       	$this->pdf->multicell(60,10,'तपशील',0,'L',false,1,130,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(35,10,'रक्कम',0,'R',false,1,170,$this->liney,true,0,false,true,10);

        $this->liney = $this->liney+7;
        //$this->pdf->line(115,$this->liney-9,115,$this->liney+240);   
        $this->pdf->line(10,$this->liney,205,$this->liney);
        $this->pdf->line(10,$this->liney+240,205,$this->liney+240);
    }
function pagefooter($islastpage = false)
    {
        $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
        $this->pdf->line(105,30,105,$this->liney);   
        $this->liney = $this->liney+15; 
        $this->pdf->SetFont('siddhanta', '', 12, '', true);
        $this->pdf->multicell(200,10,'तयार करणार/रोखपाल       तपासणार        चिफ अकौंटंट          जनरल मॅनेजर ',0,'L',false,1,5,$this->liney,true,0,false,true,10);
    }


	function creditdetail()
    {
        $query = "select *
            from vw_cashbook_credit_account_sum where 
            voucherdate = '".$this->cashbookdate."'";
            $result = oci_parse($this->connection, $query);
            $r = oci_execute($result);
            $cashtotal = 0;
            $banktotal = 0;
            $total = 0;
            $accounthead1=new accounthead($this->connection);
            $voucherheader1=new voucherheader($this->connection);
            $accounthead1->accountcode=$accounthead1->cashaccountcode();
            $accounthead1->fetch();
            $cashopening=$accounthead1->openingbalance($this->cashbookdate);
            $cashclosing=$accounthead1->closingbalance($this->cashbookdate);
/*             $this->pdf->SetFont('siddhanta', '', 12, '', true);
            $this->pdf->multicell(60,10,'आरंभीची रोख शिल्लक',0,'L',false,1,40,$this->liney,true,0,false,true,10);
            $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
            $this->pdf->multicell(35,10,$this->moneyFormatIndia($cashopening),0,'R',false,1,70,$this->liney,true,0,false,true,10);
            //$this->pdf->multicell(35,10,$this->moneyFormatIndia($cashopening),0,'R',false,1,40,$this->liney,true,0,false,true,10);
            $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
            $this->liney = $this->liney+5;
            $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
            $this->pdf->line(10,$this->liney,115,$this->liney);
 */         /*    while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
            { */
                /* $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
                $this->pdf->SetFont('siddhanta', '', 11, '', true);
                $height = $this->height($row['ACCOUNTNAMEUNI'],60);
                if ($this->isnewpage($height))
                {
                    $this->newpage(True);
                }
                $this->pdf->multicell(30,10,$row['ACCOUNTCODE'],0,'L',false,1,10,$this->liney,true,0,false,true,10);
                if ($this->isnewpage($height))
                {
                    $this->newpage(True);
                }
                $this->pdf->multicell(60,10,$row['ACCOUNTNAMEUNI'],0,'L',false,1,35,$this->liney,true,0,false,true,$height);
                $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
                $this->pdf->multicell(35,10,$this->moneyFormatIndia($row['CASH']),0,'R',false,1,80,$this->liney,true,0,false,true,10);
                $this->liney = $this->liney+$height;
                $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
                $this->pdf->line(37,$this->liney,115,$this->liney); */
                //$cashtotal = $cashtotal+$row['CASH'];
                $query1 = "select *
                from vw_cashbook_credit_detail where 
                voucherdate = '".$this->cashbookdate."' order by vouchernumber";
                //and accountcode=".$row['ACCOUNTCODE'];
                $result1 = oci_parse($this->connection, $query1);
                $r1 = oci_execute($result1);
                $ptno=0;
                while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    if ($ptno==0)
                    {
                        $i=oci_num_rows($result1);
                    }
                    $i--;
                    if (($ptno==$row1['TRANSACTIONNUMBER'] or $ptno==0) and $i>=1)
                    {
                        $narration='';
                        $ptno=$row1['TRANSACTIONNUMBER'];
                    }
                    elseif ($ptno==0 and $i>=1)
                    {
                        $narration='';
                        $ptno=$row1['TRANSACTIONNUMBER'];
                    }
                    else
                    {
                        if ($ptno==0)
                        {
                            $voucherheader1->transactionnumber = $row1['TRANSACTIONNUMBER'];
                        }
                        else
                        {
                            $voucherheader1->transactionnumber = $ptno;
                        }
                        $voucherheader1->fetch();
                        $narration=$voucherheader1->narration;
                        $ptno=$row1['TRANSACTIONNUMBER'];
                    }
                    $this->pdf->SetFont('siddhanta', '', 11, '', true);
                    $account = $row1['ACCOUNTNAMEUNI'];
                    $height = $this->height($account,60);
                    if ($row1['SUBLEDGERCODE']!='')
                    {
                        $haste = $row1['SUBLEDGERNAMEUNI'];
                    }
                    else
                    {
                        $haste = 'हस्ते-'.$row1['DESCRIPTION'];
                    }
                    //$height = $this->height($haste,60);
                    //$narration = stripslashes(str_replace(array("\r","\n"),"", $narration));
                    $height1 = $this->height($haste,60);
                    if ($this->isnewpage($height+$height1))
                    {
                        $this->newpage(True);
                    }
                    $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
                    $this->pdf->multicell(30,10,$row1['VOUCHERNUMBERPREFIXSUFIX'],0,'L',false,1,10,$this->liney,true,0,false,true,10);
                    $this->pdf->SetFont('siddhanta', '', 11, '', true);
                    $this->pdf->multicell(60,10,$account,0,'L',false,1,30,$this->liney,true,0,false,true,$height);
                    $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
                    $this->pdf->multicell(35,10,$this->moneyFormatIndia($row1['CASH']),0,'R',false,1,70,$this->liney,true,0,false,true,10);
                    $this->liney = $this->liney+$height;
                    if ($narration != '')
                    {
                        $this->pdf->SetFont('siddhanta', '', 10, '', true);
                        if ($this->isnewpage($height1))
                        {
                            $this->newpage(True);
                        }
                        $this->pdf->SetFont('siddhanta', '', 10, '', true);
                        $this->pdf->multicell(60,10,$haste,0,'L',false,1,30,$this->liney,true,0,false,true,$height1);
                        $this->liney = $this->liney+$height1;
                    }
                    $this->liney = $this->liney+2;
                    $cashtotal = $cashtotal+$row1['CASH'];
                }
                $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
                //$this->liney = $this->liney+2;
                $this->pdf->line(10,$this->liney,105,$this->liney);
                $this->liney = $this->liney+2;
            /* }
            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            if ($this->isnewpage(10))
            {
                $this->newpage(True);
            } */

            $this->cashopening = $cashopening;
            $this->cashcredit = $cashtotal;
            $this->lastcreditpage = $this->currentpage;
            $this->lastcreditlocation = $this->liney;


    }

    function debitdetail()
    {
        $query = "select *
            from vw_cashbook_debit_account_sum where 
            voucherdate = '".$this->cashbookdate."'";
            $result = oci_parse($this->connection, $query);
            $r = oci_execute($result);
            $cashtotal = 0;
            $banktotal = 0;
            $total = 0;
            $accounthead1=new accounthead($this->connection);
            $voucherheader1=new voucherheader($this->connection);
            $accounthead1->accountcode=$accounthead1->cashaccountcode();
            $accounthead1->fetch();
            $cashopening=$accounthead1->openingbalance($this->cashbookdate);
            $cashclosing=$accounthead1->closingbalance($this->cashbookdate);
            $this->pdf->SetFont('siddhanta', '', 12, '', true);
            if ($this->totalpages>=1)
            {
                $this->currentpage=$this->firstpage;
            }
            $this->pdf->setpage($this->currentpage);
            $this->liney = 38;
                $query1 = "select *
                from vw_cashbook_debit_detail where 
                voucherdate = '".$this->cashbookdate."' order by vouchernumber";
                //and accountcode=".$row['ACCOUNTCODE'];
                $result1 = oci_parse($this->connection, $query1);
                $r1 = oci_execute($result1);
                $ptno=0;
                while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    if ($ptno==0)
                    {
                        $i=oci_num_rows($result1);
                    }
                    $i--;
                    if (($ptno==$row1['TRANSACTIONNUMBER'] or $ptno==0) and $i>=1)
                    {
                        $narration='';
                        $ptno=$row1['TRANSACTIONNUMBER'];
                    }
                    elseif ($ptno==0 and $i>=1)
                    {
                        $narration='';
                        $ptno=$row1['TRANSACTIONNUMBER'];
                    }
                    else
                    {
                        if ($ptno==0)
                        {
                            $voucherheader1->transactionnumber = $row1['TRANSACTIONNUMBER'];
                        }
                        else
                        {
                            $voucherheader1->transactionnumber = $ptno;
                        }
                        $voucherheader1->fetch();
                        $narration=$voucherheader1->narration;
                        $ptno=$row1['TRANSACTIONNUMBER'];
                    }
                    $this->pdf->SetFont('siddhanta', '', 11, '', true);
                    $account = $row1['ACCOUNTNAMEUNI'];
                    $height = $this->height($account,60);
                    if ($row1['SUBLEDGERCODE']!='')
                    {
                        $haste = $row1['SUBLEDGERNAMEUNI'];
                    }
                    else
                    {
                        $haste = 'हस्ते-'.$row1['DESCRIPTION'];
                    }
                    $height1 = $this->height($haste,60);
                    //$narration = stripslashes(str_replace(array("\r","\n"),"", $narration));
                    //$height1 = $this->height($narration,60);
                    if ($this->isnewpage($height+$height1))
                    {
                        $this->newpage(True);
                    }
                    $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
                    $this->pdf->multicell(30,10,$row1['VOUCHERNUMBERPREFIXSUFIX'],0,'L',false,1,105,$this->liney,true,0,false,true,10);
                    $this->pdf->SetFont('siddhanta', '', 11, '', true);
                    $this->pdf->multicell(50,10,$account,0,'L',false,1,125,$this->liney,true,0,false,true,$height);
                    $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
                    $this->pdf->multicell(35,10,$this->moneyFormatIndia($row1['CASH']),0,'R',false,1,170,$this->liney,true,0,false,true,10);
                    $this->liney = $this->liney+$height;
                    if ($haste != '')
                    {
                        $this->pdf->SetFont('siddhanta', '', 10, '', true);
                        if ($this->isnewpage($height1))
                        {
                            $this->newpage(True);
                        }
                        $this->pdf->SetFont('siddhanta', '', 10, '', true);
                        $this->pdf->multicell(60,10,$haste,0,'L',false,1,125,$this->liney,true,0,false,true,$height1);
                        $this->liney = $this->liney+$height1;
                    }
                    $this->liney = $this->liney+2;
                    $cashtotal = $cashtotal+$row1['CASH'];
                }
                $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0,0,0)));
                //$this->liney = $this->liney+2;
                $this->pdf->line(105,$this->liney,205,$this->liney);
                $this->liney = $this->liney+2;
            /* }
            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            if ($this->isnewpage(10))
            {
                $this->newpage(True);
            } */ 
            $this->cashclosing = $cashclosing;
            $this->cashdebit = $cashtotal;
            $this->lastdebitpage = $this->currentpage;
            $this->lastdebitlocation = $this->liney;
            $isnewpage1 = 0;
            if ($this->isnewpage(50))
            {
                $isnewpage1 = 1;
            }
            if ($this->lastcreditpage>$this->lastdebitpage)
            {
                $this->currentpage = $this->lastcreditpage;
                if ($isnewpage1==1)
                {
                    $this->pdf->setpage($this->currentpage);
                    $this->newpage(True);
                    $this->pdf->setpage($this->currentpage);
                }
                else
                {
                    $this->pdf->setpage($this->lastcreditpage);
                    $this->liney = $this->lastcreditlocation;
                }
            }
            elseif ($this->lastcreditpage<$this->lastdebitpage)
            {
                $this->pdf->setpage($this->lastdebitpage);
                $this->liney = $this->lastdebitlocation;
            }
            elseif ($this->lastcreditpage==$this->lastdebitpage and $this->lastcreditlocation>=$this->lastdebitlocation)
            {
                $this->pdf->setpage($this->lastcreditpage);
                $this->liney = $this->lastcreditlocation;
            }
            elseif ($this->lastcreditpage==$this->lastdebitpage and $this->lastcreditlocation<$this->lastdebitlocation)
            {
                $this->pdf->setpage($this->lastdebitpage);
                $this->liney = $this->lastdebitlocation;
            }
            
            $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
            $this->pdf->line(10,$this->liney,205,$this->liney);
            $this->liney = $this->liney+2;
            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            $this->pdf->multicell(60,10,'एकूण नावे',0,'L',false,1,35,$this->liney,true,0,false,true,10);
            $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
            $this->pdf->multicell(35,10,$this->moneyFormatIndia($this->cashcredit),0,'R',false,1,70,$this->liney,true,0,false,true,10);
            
            $this->pdf->multicell(60,10,'एकूण नावे',0,'L',false,1,120,$this->liney,true,0,false,true,10);
            $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
            $this->pdf->multicell(35,10,$this->moneyFormatIndia($this->cashdebit),0,'R',false,1,170,$this->liney,true,0,false,true,10);

            $this->liney = $this->liney+7;
            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            $this->pdf->multicell(60,10,'आरंभीची रोख शिल्लक',0,'L',false,1,35,$this->liney,true,0,false,true,10);
            $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
            $this->pdf->multicell(35,10,$this->moneyFormatIndia($this->cashopening),0,'R',false,1,70,$this->liney,true,0,false,true,10);
            //$this->pdf->multicell(35,10,0,0,'R',false,1,130,$this->liney,true,0,false,true,10);
            //$this->pdf->multicell(35,10,$this->moneyFormatIndia($this->cashopening+$this->totalcredit),0,'R',false,1,165,$this->liney,true,0,false,true,10);

            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            $this->pdf->multicell(60,10,'अखेरची रोख शिल्लक',0,'L',false,1,120,$this->liney,true,0,false,true,10);
            $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
            $this->pdf->multicell(35,10,$this->moneyFormatIndia($this->cashclosing),0,'R',false,1,170,$this->liney,true,0,false,true,10);
            //$this->pdf->multicell(35,10,0,0,'R',false,1,330,$this->liney,true,0,false,true,10);
            //$this->pdf->multicell(35,10,$this->moneyFormatIndia($this->cashclosing+$this->totaldebit),0,'R',false,1,365,$this->liney,true,0,false,true,10);
            $this->liney = $this->liney+5;
            $this->pdf->SetLineStyle(array('width' => 0.10, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
            $this->pdf->line(10,$this->liney,205,$this->liney);
            $this->pdf->line(105,30,105,$this->liney);   
            $this->liney = $this->liney+2;
            $this->pdf->SetFont('siddhanta', '', 12, '', true);
            $this->pdf->multicell(150,10,'अक्षरी:'.NumberToWords($this->cashclosing,1),0,'R',false,1,60,$this->liney,true,0,false,true,10);
            $this->liney = $this->liney+15; 
            $this->pdf->SetFont('siddhanta', '', 12, '', true);
            $this->pdf->multicell(400,10,'रोखपाल           तपासणार             चिफ अकौंटंट             जनरल मॅनेजर ',0,'L',false,1,10,$this->liney,true,0,false,true,10);
    }

    public function isbankaccountcode($accountcode)
    {
        $query = "select count(*) cnt
       from accounthead a,accountcontroltable t 
       where ((a.groupcode=t.bankgroupcode
       and a.subgroupcode=t.banksubgroupcode) 
       or (a.groupcode=t.bankgroupcode1)
       or (a.groupcode=t.bankgroupcode2))
       and a.accountcode=".$accountcode ;
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {  
            return $row['CNT'];
        }
        else
        {
            return 0;
        }
    }

    public function iscashbankexists()
    {
        $query = "select nvl(sum(total),0) as total
        from (
        select voucherdate,total
        from vw_cashbook_credit_account_sum 
        union all
        select voucherdate,total
        from vw_cashbook_debit_account_sum)
        where voucherdate='".$this->cashbookdate."'";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {  
            return $row['TOTAL'];
        }
        else
        {
            return 0;
        }
    }

        public function height($data,$width)
    {
        // store current object
        $this->pdf->startTransaction();
        // store starting values
        $start_y = $this->pdf->GetY();
        $start_page = $this->pdf->getPage();
        // call your printing functions with your parameters
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        $this->pdf->MultiCell($w=$width, $h=0, $data, $border=1, $align='L', $fill=false, $ln=1, $x='', $y='',     $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0);
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // get the new Y
        $end_y = $this->pdf->GetY();
        $end_page = $this->pdf->getPage();
        // calculate height
        $height = 0;
        if ($end_page == $start_page) {
            $height = $end_y - $start_y;
        } else {
            for ($page=$start_page; $page <= $end_page; ++$page) {
                $this->pdf->setPage($page);
                if ($page == $start_page) {
                    // first page
                    $height = $this->pdf->h - $start_y - $this->pdf->bMargin;
                } elseif ($page == $end_page) {
                    // last page
                    $height = $end_y - $this->pdf->tMargin;
                } else {
                    $height = $this->pdf->h - $this->pdf->tMargin - $this->pdf->bMargin;
                }
            }
        }
        // restore previous object
        $this->pdf = $this->pdf->rollbackTransaction();
        return $height;
    }


    //NumberToWords(number,1)
    function moneyFormatIndia($num)
    {
        $explrestunits = "" ;
        $num=preg_replace('/,+/', '', $num);
        $words = explode(".", $num);
        $des="00";
        if(count($words)<=2)
        {
            $num=$words[0];
            if(count($words)>=2)
            {
                $des=$words[1];
            }
            if(strlen($des)<2)
            {
                $des=$des."0";
            }
            else
            {
                $des=substr($des,0,2);
            }
        }
        if(strlen($num)>3)
        {
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++)
            {
                // creates each of the 2's group and adds a comma to the end
                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
                }
                else
                {
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } 
        else 
        {
            $thecash = $num;
        }
        return "$thecash.$des"; // writes the final format where $currency is the currency symbol.
    }
}    
?>