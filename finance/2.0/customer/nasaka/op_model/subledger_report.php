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
class subledger extends swappreport
{	
    public $fromdate;
    public $todate;
    public $accountcode;    
    public $yearcode;
    public $subledgercode;
  
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
        $this->pdf->SetSubject('Sub Ledger');
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
        $this->pdf->Output('SUBLED_000.pdf', 'I');
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
        $this->textbox('वैयक्तिक खतावणी',200,10,'S','C',1,'siddhanta',13);
        $this->newrow();
        $frdt = DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y');
        $todt = DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y');
        $this->textbox('दिनांक '.$frdt.' पासुन '.$todt.' पर्यंत',200,10,'S','C',1,'siddhanta',12);
        $this->newrow();
         $accounthead1 = new accounthead($this->connection);
        $accounthead1->accountcode = $this->accountcode;
        $accounthead1->fetch();
        $this->textbox('खाते :'.$accounthead1->accountcode.' '.$accounthead1->accountnameuni,150,10,'S','L',1,'siddhanta',12);
        $accountsubledger1 = new accountsubledger($this->connection);
        $accountsubledger1->accountcode = $this->accountcode;
        $accountsubledger1->subledgercode = $this->subledgercode;
        $accountsubledger1->fetch();
        $this->newrow(7);
        $this->textbox('वैयक्तिक खाते :'.$accountsubledger1->subledgercode.' '.$accountsubledger1->subledgernameuni,150,10,'S','L',1,'siddhanta',12);
        $this->newrow(7);
        $this->hline(10,200);
        $this->newrow(2);
        $this->textbox('व्हा.नंबर',20,10,'S','L',1,'siddhanta',11);
        $this->textbox('व्हा.दिनांक',25,30,'S','L',1,'siddhanta',11);
        $this->textbox('तपशील',70,55,'S','L',1,'siddhanta',11);
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
        $this->liney = 55;
        $this->vline($this->liney-12,$this->liney+$limit,10);
        $this->vline($this->liney-12,$this->liney+$limit,30,'D');
        $this->vline($this->liney-12,$this->liney+$limit,55,'D');
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
        subledgeropeningbalance(".$this->yearcode.
        ",".$this->accountcode.
        ",".$this->subledgercode.
        ",'".$this->fromdate."') as openingbalance from dual";
        $result_openingbalance = oci_parse($this->connection, $query_openingbalance);
        $r = oci_execute($result_openingbalance);
        if ($row_openingbalance = oci_fetch_array($result_openingbalance,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $frdt = DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y');
            $this->textbox($frdt,25,30,'S','L',1,'siddhanta',10);
            $this->textbox('आरंभीची शिल्लक',70,55,'S','L',1,'siddhanta',10);
            $closingbalance=$row_openingbalance['OPENINGBALANCE'];
            if ($row_openingbalance['OPENINGBALANCE']>=0)
            {
                $this->textbox($this->moneyFormatIndia($row_openingbalance['OPENINGBALANCE'],2).'Dr',25,175,'S','R',1,'SakalMarathiNormal922',9);   
            }
            else
            {
                $this->textbox($this->moneyFormatIndia(abs($row_openingbalance['OPENINGBALANCE']),2).'Cr',25,175,'S','R',1,'SakalMarathiNormal922',9);   
            }
        }
        $query ="select h.transactionnumber,h.vouchersubtypecode,h.vouchernumberprefixsufix
        ,h.voucherdate
        ,case when d.accountcode in (11080520,11080530) and nvl(d.credit,0)>0 and  refbilltrnno is not null then
        1
        when d.accountcode in (11080520,11080530) and nvl(d.credit,0)>0 and  refbilltrnno is null then
        0
        else 0 end iscaneledger
        ,case when d.accountcode in (11080520,11080530) and nvl(d.credit,0)>0 and  refbilltrnno is not null then
        --getfarmerbilldesc(p_refbilltrnno => d.refbilltrnno)
        null
        when d.accountcode in (11080520,11080530) and nvl(d.credit,0)>0 and  refbilltrnno is null then
        'बिलातून ॲडव्हान्स कपात'
        else h.narration end narration
        ,nvl(d.credit,0) as credit
        ,nvl(d.debit,0) as debit
        ,oppaccounthead(h.transactionnumber,d.accountcode,d.subledgercode) as oppaccountheadnameuni
        ,costcentrecode
        ,case when d.refdate is not null then to_char(d.refdate,'dd/mm/yyyy') end refdate
        from voucherdetail d,voucherheader h
        where d.transactionnumber=h.transactionnumber
        and h.voucherdate>='".$this->fromdate."'
        and h.voucherdate<='".$this->todate."'
        and d.accountcode = ".$this->accountcode."
        and d.subledgercode=".$this->subledgercode."
        and h.approvalstatus=9
         order by h.voucherdate asc,h.vouchernumber asc,nvl(d.credit,0)-nvl(d.debit,0) desc";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $srno=1;
        $credittotal=0;
        $debiittotal=0;
        $height2=7;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->newrow($height2);
            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            $height1 = $this->height($row['OPPACCOUNTHEADNAMEUNI'],70);
            $this->pdf->SetFont('siddhanta', '', 9, '', true);
            if ($row['ISCANELEDGER']==0)
            {
                $height2 = $this->height($row['NARRATION'],70);
            }
            else
            {
                $height2 = $this->height($row['NARRATION'],125);
            }    
            $height=$height1+$height2;
            if ($this->isnewpage($height))
            {
                $this->newpage($height);
            }
            $this->textbox($row['OPPACCOUNTHEADNAMEUNI'],70,55,'S','L',1,'siddhanta',11,'','Y');
            $this->textbox($row['VOUCHERNUMBERPREFIXSUFIX'],20,10,'S','L',1,'siddhanta',9);
            $vdt = DateTime::createFromFormat('d-M-Y',$row['VOUCHERDATE'])->format('d/m/Y');
            $this->textbox($vdt,25,30,'S','L',1,'SakalMarathiNormal922',9);
            $this->textbox($this->moneyFormatIndia($row['CREDIT'],2),25,150,'S','R',1,'SakalMarathiNormal922',9); 
            $this->textbox($this->moneyFormatIndia($row['DEBIT'],2),25,125,'S','R',1,'SakalMarathiNormal922',9); 
            $closingbalance=$closingbalance+$row['DEBIT']-$row['CREDIT'];
            $credittotal=$credittotal+$row['CREDIT'];
            $debittotal=$debittotal+$row['DEBIT'];
            if ($closingbalance>0)
            {
                $this->textbox($this->moneyFormatIndia($closingbalance,2)."Dr",25,175,'S','R',1,'SakalMarathiNormal922',9);   
            }
            else
            {
                $this->textbox($this->moneyFormatIndia(abs($closingbalance),2)."Cr",25,175,'S','R',1,'SakalMarathiNormal922',9);   
            }
            $this->newrow($height1);
            if ($row['ISCANELEDGER']==0)
            {
                $height2 = $this->textbox($row['NARRATION'].' ('.$row['COSTCENTRECODE'].')'.$row['REFDATE'],70,55,'S','L',1,'siddhanta',9,'','Y');
            }
            else
            {
                $height2 = $this->textbox($row['NARRATION'].' ('.$row['COSTCENTRECODE'].')'.$row['REFDATE'],125,10,'S','L',1,'siddhanta',9,'','Y');
            }
            
            if ($row['VOUCHERSUBTYPECODE']==2 or $row['VOUCHERSUBTYPECODE']==5)
            {
                $this->newrow($height2);
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
                    $height3=$this->textbox($row1['FUNDDOCUMENTNAMEUNI'].' नंबर: '.$row1['FUNDDOCUMENTNUMBER'].' दि. '.$row1['FUNDDOCUMENTDATE'].' रक्कम: '.$row1['FUNDDOCUMENTAMOUNT'].' ड्रायी बँक: '.$row1['DRAWEEBANK'],70,55,'S','L',1,'siddhanta',8,'','Y');
                    $this->newrow($height3);
                }        
            }
            $this->newrow(2);
        }
        
        //
        if ($this->accountcode==1214002)
        {
            if ($this->isnewpage($height2))
            {
                $this->newpage($height2);
            }
            //$this->newrow($height2);
            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            /* $pending = $this->balance();
            $this->textbox($pending,25,150,'S','R',1,'SakalMarathiNormal922',9); 
            $closingbalance=$closingbalance+$pending-$row['CREDIT'];
            $credittotal=$credittotal+$row['CREDIT'];
            $debittotal=$debittotal+$pending;
            if ($closingbalance>0)
            {
                $this->textbox($this->moneyFormatIndia($closingbalance,2)."Dr",25,175,'S','R',1,'SakalMarathiNormal922',9);   
            }
            else
            {
                $this->textbox($this->moneyFormatIndia(abs($closingbalance),2)."Cr",25,175,'S','R',1,'SakalMarathiNormal922',9);   
            }
                $this->textbox('पेंडींग मेमो',70,55,'S','L',1,'siddhanta',11,'','Y');
                $this->newrow(2); */

            $query=    "select 'खुली साखर पेंडींग मेमो' as OPPACCOUNTHEADNAMEUNI,'Pending memo no-'||h.memonumberpresuf as narration,h.memodate as voucherdate,nvl((h.grossamount),0) as debit, 0 as credit 
            from nst_nasaka_sale.salememoheader h
            ,nst_nasaka_sale.goodspurchaser p
            ,nst_nasaka_finance.accountsubledger g 
            where h.purchasercode=p.purchasercode
            and g.accountcode=1214002 
            and to_number(substr(g.referencecode,2,10))=p.purchasercode
            and g.subledgercode=".$this->subledgercode."
            and g.accountcode=1214002
            and h.grossamount>0
            and h.transactionnumber not in (select s.salememotransactionnumber 
            from nst_nasaka_sale.saleinvoiceheader s where nvl(s.grossamount,0)>0
            and s.purchasercode=p.purchasercode)
           order by memodate,memonumber";
            $result = oci_parse($this->connection, $query);
            $r = oci_execute($result);
            //$srno=1;
            //$credittotal=0;
            //$debiittotal=0;
            //$height2=7;
            while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                $this->newrow($height2);
                $this->pdf->SetFont('siddhanta', '', 11, '', true);
                $height1 = $this->height($row['OPPACCOUNTHEADNAMEUNI'],70);
                $this->pdf->SetFont('siddhanta', '', 9, '', true);
                $height2 = $this->height($row['NARRATION'],70);
                $height=$height1+$height2;
                if ($this->isnewpage($height))
                {
                    $this->newpage($height);
                }
                $this->textbox($row['OPPACCOUNTHEADNAMEUNI'],70,55,'S','L',1,'siddhanta',11,'','Y');
                $this->textbox($row['VOUCHERNUMBERPREFIXSUFIX'],20,10,'S','L',1,'siddhanta',9);
                $vdt = DateTime::createFromFormat('d-M-Y',$row['VOUCHERDATE'])->format('d/m/Y');
                $this->textbox($vdt,25,30,'S','L',1,'SakalMarathiNormal922',9);
                $this->textbox($this->moneyFormatIndia($row['CREDIT'],2),25,150,'S','R',1,'SakalMarathiNormal922',9); 
                $this->textbox($this->moneyFormatIndia($row['DEBIT'],2),25,125,'S','R',1,'SakalMarathiNormal922',9); 
                $closingbalance=$closingbalance+$row['DEBIT']-$row['CREDIT'];
                $credittotal=$credittotal+$row['CREDIT'];
                $debittotal=$debittotal+$row['DEBIT'];
                if ($closingbalance>0)
                {
                    $this->textbox($this->moneyFormatIndia($closingbalance,2)."Dr",25,175,'S','R',1,'SakalMarathiNormal922',9);   
                }
                else
                {
                    $this->textbox($this->moneyFormatIndia(abs($closingbalance),2)."Cr",25,175,'S','R',1,'SakalMarathiNormal922',9);   
                }
                $this->newrow($height1);
                $height2 = $this->textbox($row['NARRATION'],70,55,'S','L',1,'siddhanta',9,'','Y');
                $this->newrow(2);
            }
        }
        else
        {
            $pending = 0;
            $closingbalance=$closingbalance+$pending-$row['CREDIT'];
            $credittotal=$credittotal+$row['CREDIT'];
            $debittotal=$debittotal+$pending;
        }
        //
          $this->newrow($height2);
          $this->hline(10,200,$this->liney);
          $this->textbox($this->moneyFormatIndia($credittotal,2),25,150,'S','R',1,'SakalMarathiNormal922',9); 
          $this->textbox($this->moneyFormatIndia($debittotal,2),25,125,'S','R',1,'SakalMarathiNormal922',9);
          $this->newrow();
          $this->hline(10,200,$this->liney);
          $this->pagefooter(true);
    }
    public function balance()
    {
        $this->dataoperationmode = operationmode::Select;
        $query = "select subledger,sum(uncleardebit) as balance 
        from (
        select subledgercode as subledger,nst_nasaka_finance.subaccountclosingbalance(p_yearcode => ".$_SESSION['yearperiodcode'].",p_accountcode => 1214002,p_subledgercode => p.subledgercode) as clearbalance,0 as uncleardebit
        from (select t.subledgercode 
        from nst_nasaka_finance.accountsubledger t
        where t.accountcode=1214002 and t.subledgercode=".$this->subledgercode.")p
        union all
        select g.subledgercode,0 as clearbalance,nvl(sum(h.grossamount),0) as uncleardebit 
        from nst_nasaka_sale.salememoheader h
        ,nst_nasaka_sale.goodspurchaser p
        ,nst_nasaka_finance.accountsubledger g 
        where h.purchasercode=p.purchasercode
        and g.accountcode=1214002 
        and to_number(substr(g.referencecode,2,10))=p.purchasercode
        and g.subledgercode=".$this->subledgercode."
        and h.transactionnumber not in (select s.salememotransactionnumber 
        from nst_nasaka_sale.saleinvoiceheader s where nvl(s.grossamount,0)>0
        and s.purchasercode=p.purchasercode)
        group by g.subledgercode) t
        group by subledger";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result,OCI_NO_AUTO_COMMIT);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            return $row['BALANCE'];
        }
        else
        {
            return 0;
        }
    }
}    
?>