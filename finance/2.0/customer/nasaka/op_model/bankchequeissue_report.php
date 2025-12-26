<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    include_once("../ip_model/accounthead_db_oracle.php");
    include_once("../ip_model/accountsubledger_db_oracle.php");
//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class bankchequeissue extends swappreport
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
        $this->pdf->SetSubject('Bank Cheque Issue Register');
        $this->pdf->SetKeywords('BNCHISRG_000.MR');
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
        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output('BNCHISRG_000.pdf', 'I');
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
        $this->textbox('बँक चेक जावक रजिस्टर',200,10,'S','C',1,'siddhanta',13);
        $this->newrow();
        $frdt = DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y');
        $todt = DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y');
        $this->textbox('दिनांक '.$frdt.' पासुन '.$todt.' पर्यंत',200,10,'S','C',1,'siddhanta',10);
        $this->newrow();
        $accounthead1 = new accounthead($this->connection);
        $accounthead1->accountcode = $this->bankcode;
        $accounthead1->fetch();
        $this->textbox('बँक खाते :'.$accounthead1->accountcode.' '.$accounthead1->accountnameuni,100,10,'S','L',1,'siddhanta',12);
        $this->newrow(7);
        $this->hline(10,200);
        $this->textbox('चे.दिनांक',20,10,'S','L',1,'siddhanta',11);
        $this->textbox('चेक नंबर',30,30,'S','L',1,'siddhanta',11);
        $this->textbox('तपशील',70,55,'S','L',1,'siddhanta',11);
        $this->textbox('रक्कम',25,125,'S','R',1,'siddhanta',11);
        $this->textbox('खाते',30,150,'S','R',1,'siddhanta',11);
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
        $this->vline($this->liney-12,$this->liney+$limit,24,'D');
        $this->vline($this->liney-12,$this->liney+$limit,53,'D');
        $this->vline($this->liney-12,$this->liney+$limit,125,'D');
        $this->vline($this->liney-12,$this->liney+$limit,150,'D');
        //$this->vline($this->liney-12,$this->liney+$limit,175,'D');
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
        
            $query = "select h.transactionnumber,t.bankaccountcode
            ,f.funddocumentnameuni
            ,t.funddocumentdate
            ,t.funddocumentnumber
            ,t.funddocumentamount
            ,t.draweebank
            ,oppaccountheadledger(h.transactionnumber,t.bankaccountcode) as oppaccountheadnameuni
            from voucherheader h,voucherchequedddetail t,funddocumenttype f
            where h.transactionnumber=t.transactionnumber 
            and t.funddocumentcode=f.funddocumentcode
            and h.voucherdate>='".$this->fromdate."'
            and h.voucherdate<='".$this->todate."'
            and h.approvalstatus=9
            and h.vouchersubtypecode=5
            and h.yearperiodcode=".$this->yearcode." 
            and t.bankaccountcode = ".$this->bankcode.
            " order by t.funddocumentdate,t.funddocumentnumber";
            $result = oci_parse($this->connection, $query);
            $r = oci_execute($result);
            $total=0;
            while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
            {  
                $vdt = DateTime::createFromFormat('d-M-y',$row['FUNDDOCUMENTDATE'])->format('d/m/y');
                $this->textbox($vdt,20,10,'S','L',1,'siddhanta',8);
                $height1=$this->textbox($row['FUNDDOCUMENTNUMBER'],30,25,'S','L',1,'siddhanta',8,'','Y');
                $this->textbox($this->payee($row['TRANSACTIONNUMBER']),70,55,'S','L',1,'siddhanta',9); 
                $this->textbox($row['FUNDDOCUMENTAMOUNT'],25,125,'S','R',1,'SakalMarathiNormal922',9); 
                $this->textbox($row['OPPACCOUNTHEADNAMEUNI'],50,150,'S','L',1,'siddhanta',9); 
                $total=$total+$row['FUNDDOCUMENTAMOUNT'];
                if ($this->isnewpage($height1))
                {
                    $this->newpage(5);
                }
                $this->newrow($height1+2);
            }
            $this->hline(10,200,$this->liney);
            $this->textbox($total,25,125,'S','R',1,'SakalMarathiNormal922',9); 
            if ($this->isnewpage(5))
            {
                $this->newpage(5);
            }
            $this->newrow(5);
            $this->drawlines($this->liney-48);
    }
    public function payee($transactionnumber)
    {
        $query1 = "select s.subledgernameeng,s.subledgernameuni 
        from voucherdetail t,accountsubledger s 
        where t.accountcode=s.accountcode
        and t.subledgercode=s.subledgercode
        and t.subledgercode is  not null 
        and t.transactionnumber =".$transactionnumber.
        " and t.debit>0 
        order by t.detailserialnumber";
        $result1 = oci_parse($this->connection, $query1);
        $r1 = oci_execute($result1);
        if ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $payeename = $row1['SUBLEDGERNAMEUNI'];
        }
        else
        {
            $query1 = "select t.description  
            from voucherheader t
            where t.transactionnumber =".$transactionnumber;
            $result1 = oci_parse($this->connection, $query1);
            $r1 = oci_execute($result1);
            if ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                $payeename = $row1['DESCRIPTION'];
            }
            else
            {
                $payeename = '';
            }
        }
        return $payeename;
    }
    function export()
    {
           $filename='bankchequelist';
           //$fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');
           $fp1=fopen('php://memory', 'w');
           $query = "select h.transactionnumber,t.bankaccountcode
            ,f.funddocumentnameuni
            ,t.funddocumentdate
            ,t.funddocumentnumber
            ,t.funddocumentamount
            ,t.draweebank
            ,oppaccountheadledger(h.transactionnumber,t.bankaccountcode) as oppaccountheadnameuni
            from voucherheader h,voucherchequedddetail t,funddocumenttype f
            where h.transactionnumber=t.transactionnumber 
            and t.funddocumentcode=f.funddocumentcode
            and h.voucherdate>='".$this->fromdate."'
            and h.voucherdate<='".$this->todate."'
            and h.approvalstatus=9
            and h.vouchersubtypecode=5
            and h.yearperiodcode=".$this->yearcode." 
            and t.bankaccountcode = ".$this->bankcode.
            " order by t.funddocumentdate,t.funddocumentnumber";
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $response = array();
           fputcsv($fp1, array('Cheque Date','Cheque Number','Narration','Amount','Account'));
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                $vdt = DateTime::createFromFormat('d-M-y',$row['FUNDDOCUMENTDATE'])->format('d/m/Y');
                fputcsv($fp1, array($vdt,$row['FUNDDOCUMENTNUMBER'],$this->payee($row['TRANSACTIONNUMBER']),$row['FUNDDOCUMENTAMOUNT'],$row['OPPACCOUNTHEADNAMEUNI']), $delimiter = ',', $enclosure = '"');
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