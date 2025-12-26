<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_l.php");
    include_once("../info/routine.php");
    include_once("../ip_model/accounthead_db_oracle.php");
    include_once("../ip_model/accountsubledger_db_oracle.php");
//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class subledgerbalancelist extends swappreport
{	
    public $yearcode;
    public $accountcode;    
    public $todate;
  
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
    	// create new PDF document
	    $this->pdf = new MYPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('SubLedger');
        $this->pdf->SetKeywords('SUBLED_000.MR');
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
        $this->pdf->Output('LED_000.pdf', 'I');
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
        $this->textbox('सबलेजर शिल्लकेची यादी',270,10,'S','C',1,'siddhanta',13);
        $this->newrow();
        //$frdt = DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y');
        $todt = DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y');
        $this->textbox('दिनांक '.$todt.' अखेर',270,10,'S','C',1,'siddhanta',10);
        $this->newrow();
        $accounthead1 = new accounthead($this->connection);
        $accounthead1->accountcode = $this->accountcode;
        $accounthead1->fetch();
        $this->textbox('खाते :'.$accounthead1->accountcode.' '.$accounthead1->accountnameuni,150,10,'S','L',1,'siddhanta',12);
        $this->newrow(7);
        $this->hline(10,290);
        $this->textbox('अ.नं.',10,10,'S','L',1,'siddhanta',11);
        $this->textbox('कोड',20,20,'S','L',1,'siddhanta',11);
        $this->textbox('सबलेजर',50,50,'S','L',1,'siddhanta',11);
        $this->textbox('आरंभीची शिल्लक',35,130,'S','R',1,'siddhanta',11);
        $this->textbox('कालावधीतील',35,180,'S','R',1,'siddhanta',11);
        $this->textbox('अखेरची शिल्लक',35,245,'S','R',1,'siddhanta',11);
        $this->newrow();
        $this->textbox('नावे',35,100,'S','R',1,'siddhanta',11);
        $this->textbox('जमा',35,130,'S','R',1,'siddhanta',11);
        $this->textbox('नावे',35,160,'S','R',1,'siddhanta',11);
        $this->textbox('जमा',35,190,'S','R',1,'siddhanta',11);
        $this->textbox('नावे',35,220,'S','R',1,'siddhanta',11);
        $this->textbox('जमा',35,250,'S','R',1,'siddhanta',11);
        $this->hline(10,290,$this->liney+6,'C');
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
        $this->vline($this->liney-12,$this->liney+$limit,20);
        $this->vline($this->liney-12,$this->liney+$limit,35);
        $this->vline($this->liney-12,$this->liney+$limit,140);
        $this->vline($this->liney-5,$this->liney+$limit,170);
        $this->vline($this->liney-12,$this->liney+$limit,290);
        $this->hline(140,290,$this->liney-5);
        $this->hline(10,290,$this->liney+$limit);
        $this->hline(10,290,$this->liney+$limit);
        $this->liney = $liney;
    }

    function pagefooter($islastpage=false)
    {
        if ($islastpage==false)
        {
            //$this->drawlines($this->maxlines-48);
        }
        else
        {
            //$this->drawlines($this->liney-48);
        }
        
    }

    function detail()
    {
        $query = "select a.yearperiodcode,a.accountcode,a.subledgercode,s.subledgernameuni
        ,nvl(sum(opcredit),0) opcredit,nvl(sum(opdebit),0) opdebit
        ,nvl(sum(credit),0) credit,nvl(sum(debit),0) debit 
        ,case when (nvl(sum(opdebit),0)+nvl(sum(debit),0)-(nvl(sum(opcredit),0)+nvl(sum(credit),0)))<0 then abs((nvl(sum(opdebit),0)+nvl(sum(debit),0)-(nvl(sum(opcredit),0)+nvl(sum(credit),0)))) else 0 end clcredit
        ,case when (nvl(sum(opdebit),0)+nvl(sum(debit),0)-(nvl(sum(opcredit),0)+nvl(sum(credit),0)))>0 then (nvl(sum(opdebit),0)+nvl(sum(debit),0)-(nvl(sum(opcredit),0)+nvl(sum(credit),0))) else 0 end cldebit
        from (
        select a.yearperiodcode,a.accountcode,a.subledgercode,sum(nvl(a.creditbalance,0)) as opcredit,sum(nvl(a.debitbalance,0)) as opdebit,0 credit,0 debit
        from accountopening a
        where a.yearperiodcode=".$this->yearcode." and a.accountcode=".$this->accountcode." and a.subledgercode is not null
        group by a.yearperiodcode,a.accountcode,a.subledgercode
        union all
        select t.yearperiodcode,d.accountcode,d.subledgercode,0,0,nvl(sum(d.credit),0) as credit,nvl(sum(d.debit),0) as debit
        from voucherheader t,voucherdetail d
        where t.transactionnumber=d.transactionnumber
        and t.yearperiodcode=".$this->yearcode." and t.voucherdate<='".$this->todate."'
        and d.accountcode=".$this->accountcode." 
        and d.subledgercode is not null
        and t.approvalstatus=9
        group by t.yearperiodcode,d.accountcode,d.subledgercode)a,accountsubledger s
        where a.accountcode=s.accountcode
        and a.subledgercode=s.subledgercode
        group by a.yearperiodcode,a.accountcode,a.subledgercode,s.subledgernameuni
        order by s.subledgernameuni";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $srno=1;
        $opdebit=0;
        $opcredit=0;
        $debit=0;
        $credit=0;
        $cldebit=0;
        $clcredit=0;
        $accountsubledger1 = new accountsubledger($this->connection);
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            $accountsubledger1->accountcode = $this->accountcode;
            $accountsubledger1->subledgercode = $row['SUBLEDGERCODE'];
            $accountsubledger1->fetch();
            //$accountsubledger1->fetchwithreferencename();
            $this->textbox($srno,10,10,'S','L',1,'siddhanta',10); 
            $this->textbox($accountsubledger1->subledgercode,20,20,'S','L',1,'siddhanta',10,'','Y');         
            $this->textbox($accountsubledger1->subledgernameuni.' ('.$accountsubledger1->referencecode.')',110,35,'S','L',1,'siddhanta',10,'','Y');
            $this->textbox($this->moneyFormatIndia($row['OPDEBIT']),35,100,'S','R',1,'SakalMarathiNormal922',9);   
            $this->textbox($this->moneyFormatIndia(abs($row['OPCREDIT'])),35,130,'S','R',1,'SakalMarathiNormal922',9);   
            $this->textbox($this->moneyFormatIndia($row['DEBIT']),35,160,'S','R',1,'SakalMarathiNormal922',9);   
            $this->textbox($this->moneyFormatIndia(abs($row['CREDIT'])),35,190,'S','R',1,'SakalMarathiNormal922',9);   
            $this->textbox($this->moneyFormatIndia($row['CLDEBIT']),35,220,'S','R',1,'SakalMarathiNormal922',9);   
            $this->textbox($this->moneyFormatIndia(abs($row['CLCREDIT'])),35,250,'S','R',1,'SakalMarathiNormal922',9);   
            $opdebit+=$row['OPDEBIT'];
            $opcredit+=$row['OPCREDIT'];
            $debit+=$row['DEBIT'];
            $credit+=$row['CREDIT'];
            $cldebit+=$row['CLDEBIT'];
            $clcredit+=$row['CLCREDIT'];
            $this->newrow(6);
            $this->hline(10,290,$this->liney-1,'D');
            $srno++;
        }
        $this->newrow($height2);
        $this->hline(10,290,$this->liney);
        $this->textbox('',20,20,'S','L',1,'siddhanta',10,'','Y');         
        $this->textbox('',110,35,'S','L',1,'siddhanta',10,'','Y');
        $this->textbox($this->moneyFormatIndia($opdebit),35,100,'S','R',1,'SakalMarathiNormal922',9);   
        $this->textbox($this->moneyFormatIndia($opcredit),35,130,'S','R',1,'SakalMarathiNormal922',9);   
        $this->textbox($this->moneyFormatIndia($debit),35,160,'S','R',1,'SakalMarathiNormal922',9);   
        $this->textbox($this->moneyFormatIndia($credit),35,190,'S','R',1,'SakalMarathiNormal922',9);   
        $this->textbox($this->moneyFormatIndia($cldebit),35,220,'S','R',1,'SakalMarathiNormal922',9);   
        $this->textbox($this->moneyFormatIndia($clcredit),35,250,'S','R',1,'SakalMarathiNormal922',9);   
        
        $this->newrow();
        $this->hline(10,290,$this->liney);
        $this->pagefooter(true);
    }
    function export()
    {
           $filename='subledgerlist.csv';
           //$fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');
           $fp1=fopen('php://memory', 'w');
           $query = "select a.yearperiodcode,a.accountcode,a.subledgercode,s.subledgernameeng
        ,nvl(sum(opcredit),0) opcredit,nvl(sum(opdebit),0) opdebit
        ,nvl(sum(credit),0) credit,nvl(sum(debit),0) debit 
        ,case when (nvl(sum(opdebit),0)+nvl(sum(debit),0)-(nvl(sum(opcredit),0)+nvl(sum(credit),0)))<0 then abs((nvl(sum(opdebit),0)+nvl(sum(debit),0)-(nvl(sum(opcredit),0)+nvl(sum(credit),0)))) else 0 end clcredit
        ,case when (nvl(sum(opdebit),0)+nvl(sum(debit),0)-(nvl(sum(opcredit),0)+nvl(sum(credit),0)))>0 then (nvl(sum(opdebit),0)+nvl(sum(debit),0)-(nvl(sum(opcredit),0)+nvl(sum(credit),0))) else 0 end cldebit
        from (
        select a.yearperiodcode,a.accountcode,a.subledgercode,sum(nvl(a.creditbalance,0)) as opcredit,sum(nvl(a.debitbalance,0)) as opdebit,0 credit,0 debit
        from accountopening a
        where a.yearperiodcode=".$this->yearcode." and a.accountcode=".$this->accountcode." and a.subledgercode is not null
        group by a.yearperiodcode,a.accountcode,a.subledgercode
        union all
        select t.yearperiodcode,d.accountcode,d.subledgercode,0,0,nvl(sum(d.credit),0) as credit,nvl(sum(d.debit),0) as debit
        from voucherheader t,voucherdetail d
        where t.transactionnumber=d.transactionnumber
        and t.yearperiodcode=".$this->yearcode." and t.voucherdate<='".$this->todate."'
        and d.accountcode=".$this->accountcode." 
        and d.subledgercode is not null
        and t.approvalstatus=9
        group by t.yearperiodcode,d.accountcode,d.subledgercode)a,accountsubledger s
        where a.accountcode=s.accountcode
        and a.subledgercode=s.subledgercode
        group by a.yearperiodcode,a.accountcode,a.subledgercode,s.subledgernameeng
        order by s.subledgernameeng";
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $response = array();
           fputcsv($fp1, array('Subledger Code','Subledger Name','Op Bal (dr)','Op Bal(Cr)','Dr','Cr','Cl bal(dr)','Cl bal(Cr)'));
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                fputcsv($fp1, array($row['SUBLEDGERCODE'],$row['SUBLEDGERNAMEENG'],$row['OPDEBIT'],$row['OPCREDIT'],$row['DEBIT'],$row['CREDIT'],$row['CLDEBIT'],$row['CLCREDIT']), $delimiter = ',', $enclosure = '"');
                
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