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
class subledgeryearbalancelist extends swappreport
{	
    public $yearcode;
    public $accountcode;    
    public $todate;
    public $seasoncode;
  
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
        $this->textbox('सबलेजर हंगाम शिल्लकेची यादी',185,10,'S','C',1,'siddhanta',13);
        $this->newrow();
        //$frdt = DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y');
        $todt = DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y');
        $this->textbox('हंगाम '.$this->seasoncode.' दिनांक '.$todt.' अखेर',185,10,'S','C',1,'siddhanta',10);
        $this->newrow();
        $accounthead1 = new accounthead($this->connection);
        $accounthead1->accountcode = $this->accountcode;
        $accounthead1->fetch();
        $this->textbox('खाते :'.$accounthead1->accountcode.' '.$accounthead1->accountnameuni,150,10,'S','L',1,'siddhanta',12);
        $this->newrow(7);
        $this->hline(10,200);
        $this->textbox('अ.नं.',10,10,'S','L',1,'siddhanta',11);
        $this->textbox('कोड',20,20,'S','L',1,'siddhanta',11);
        $this->textbox('सबलेजर',90,70,'S','L',1,'siddhanta',11);
        $this->textbox('शिल्लक',25,150,'S','R',1,'siddhanta',11);
        $this->newrow();
        $this->textbox('नावे',35,130,'S','R',1,'siddhanta',11);
        $this->textbox('जमा',35,160,'S','R',1,'siddhanta',11);
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
        $this->vline($this->liney-12,$this->liney+$limit,20);
        $this->vline($this->liney-12,$this->liney+$limit,35);
        $this->vline($this->liney-12,$this->liney+$limit,140);
        $this->vline($this->liney-5,$this->liney+$limit,170);
        $this->vline($this->liney-12,$this->liney+$limit,200);
        $this->hline(140,200,$this->liney-5);
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
        
        if ($this->seasoncode!='')
            $cond=' seasonyear='.$this->seasoncode;
        else    
            $cond='1=1';

        $query ="select yearperiodcode,accountcode,seasonyear,circlecode,circlenameuni,villagecode,villagenameuni,farmercode,contractorcode,subledgercode,subledgernameuni,sum(balance) balance
        from 
        (select a.yearperiodcode,a.accountcode,seasonyear,c.circlecode,circlenameuni,v.villagecode,v.villagenameuni,f.farmercode,null contractorcode,a.subledgercode,s.subledgernameuni,nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) as balance
        from (
        select a.yearperiodcode,a.accountcode,a.subledgercode,a.seasonyear,sum(nvl(a.creditbalance,0)) as creditbalance,sum(nvl(a.debitbalance,0)) as debitbalance
        from accountopening a
        where a.yearperiodcode=".$this->yearcode." and a.accountcode=".$this->accountcode." and a.subledgercode is not null
        group by a.yearperiodcode,a.accountcode,a.subledgercode,a.seasonyear
        union all
        select t.yearperiodcode,d.accountcode,d.subledgercode,d.costcentrecode,nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance
        from voucherheader t,voucherdetail d
        where t.transactionnumber=d.transactionnumber
        and t.yearperiodcode=".$this->yearcode." 
        and t.voucherdate<='".$this->todate."'
        and d.accountcode=".$this->accountcode." 
        and d.subledgercode is not null
        and t.approvalstatus=9
        group by t.yearperiodcode,d.accountcode,d.subledgercode,d.costcentrecode)a
        ,accountsubledger s
        ,nst_nasaka_agriculture.farmer f
        ,nst_nasaka_agriculture.village v
        ,nst_nasaka_agriculture.circle c
        where a.accountcode=s.accountcode
        and a.subledgercode=s.subledgercode
        and s.referencecode='F'||f.farmercode
        and f.villagecode=v.villagecode
        and v.circlecode=c.circlecode
        and ".$cond." 
        group by a.yearperiodcode,a.accountcode,seasonyear,c.circlecode,circlenameuni,v.villagecode,v.villagenameuni,f.farmercode,a.subledgercode,s.subledgernameuni
        having nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0)<>0
        union all
        select a.yearperiodcode,a.accountcode,seasonyear,null circlecode,null circlenameuni,null villagecode,null villagenameuni,null farmercode,f.contractorcode,a.subledgercode,s.subledgernameuni,nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) as balance
        from (
        select a.yearperiodcode,a.accountcode,a.subledgercode,a.seasonyear,sum(nvl(a.creditbalance,0)) as creditbalance,sum(nvl(a.debitbalance,0)) as debitbalance
        from accountopening a
        where a.yearperiodcode=".$this->yearcode." and a.accountcode=".$this->accountcode." and a.subledgercode is not null
        group by a.yearperiodcode,a.accountcode,a.subledgercode,a.seasonyear
        union all
        select t.yearperiodcode,d.accountcode,d.subledgercode,d.costcentrecode,nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance
        from voucherheader t,voucherdetail d
        where t.transactionnumber=d.transactionnumber
        and t.yearperiodcode=".$this->yearcode." 
        and t.voucherdate<='".$this->todate."'
        and d.accountcode=".$this->accountcode." 
        and d.subledgercode is not null
        and t.approvalstatus=9
        group by t.yearperiodcode,d.accountcode,d.subledgercode,d.costcentrecode)a
        ,accountsubledger s
        ,nst_nasaka_agriculture.contractor f
        where a.accountcode=s.accountcode
        and a.subledgercode=s.subledgercode
        and s.referencecode='Q'||f.contractorcode
        and ".$cond." 
        group by a.yearperiodcode,a.accountcode,seasonyear,f.contractorcode,a.subledgercode,s.subledgernameuni
        having nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0)<>0
        ) group by yearperiodcode,accountcode,seasonyear,circlecode,circlenameuni,villagecode,villagenameuni,farmercode,contractorcode,subledgercode,subledgernameuni
        order by seasonyear,circlecode,circlenameuni,villagecode,villagenameuni,subledgernameuni";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $srno=1;
        $debit=0;
        $credit=0;
        $seasondebit=0;
        $seasoncredit=0;
        $lastseasonyear='';
        $lastcirclecode='';
        $lastvillagecode='';
        $accountsubledger1 = new accountsubledger($this->connection);
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($lastseasonyear=='')
            {
                $this->seasoncode=$row['SEASONYEAR'];
                $this->newpage(true);
            }
            if ($lastcirclecode=='' or $lastcirclecode!=$row['CIRCLECODE'])
            {
                $this->pdf->SetFont('siddhanta', '', 11, '', true);
                $this->textbox($row['CIRCLECODE'],10,20,'S','L',1,'siddhanta',9); 
                $this->textbox('गट:'.$row['CIRCLENAMEUNI'],60,35,'S','L',1,'siddhanta',12,'B','Y');             
                $this->newrow(6);
                $this->hline(10,200,$this->liney);
            }
            if ($lastvillagecode=='' or $lastvillagecode!=$row['VILLAGECODE'])
            {
                $this->pdf->SetFont('siddhanta', '', 11, '', true);
                $this->textbox($row['VILLAGECODE'],10,20,'S','L',1,'siddhanta',9); 
                $this->textbox('गाव:'.$row['VILLAGENAMEUNI'],60,40,'S','L',1,'siddhanta',11,'B','Y');             
                $this->newrow(6);
                $this->hline(10,200,$this->liney);
            }

            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            $accountsubledger1->accountcode = $this->accountcode;
            $accountsubledger1->subledgercode = $row['SUBLEDGERCODE'];
            $accountsubledger1->fetch();
            //$accountsubledger1->fetchwithreferencename();
            $this->textbox($srno,10,10,'S','L',1,'siddhanta',9); 
            $this->textbox($accountsubledger1->subledgercode,20,20,'S','L',1,'siddhanta',10,'','Y');         
            $this->textbox($accountsubledger1->subledgernameuni.' ('.$accountsubledger1->referencecode.')',110,35,'S','L',1,'siddhanta',10,'','Y');
            if ($row['BALANCE']<0)
            {
                $this->textbox($this->moneyFormatIndia(abs($row['BALANCE'])),35,165,'S','R',1,'SakalMarathiNormal922',9);   
                $credit=$credit+abs($row['BALANCE']);
                $seasoncredit=$seasoncredit+abs($row['BALANCE']);
            }
            else
            {
                $this->textbox($this->moneyFormatIndia($row['BALANCE']),35,135,'S','R',1,'SakalMarathiNormal922',9);   
                $debit=$debit+$row['BALANCE'];
                $seasondebit=$seasondebit+$row['BALANCE'];
            }
            $this->newrow(6);
            $this->hline(10,200,$this->liney-1,'D');
            if ($lastseasonyear=='' or $lastseasonyear==$row['SEASONYEAR'])
                $lastseasonyear=$row['SEASONYEAR'];
            else
                {
                    //$this->newrow($height2);
                    $this->hline(10,200,$this->liney);
                    $this->textbox('एकूण ('.$this->seasoncode.')',100,35,'S','R',1,'siddhanta',10,'','Y');
                    $this->textbox($seasoncredit,35,165,'S','R',1,'SakalMarathiNormal922',9); 
                    $this->textbox($seasondebit,35,135,'S','R',1,'SakalMarathiNormal922',9);
                    $this->newrow();
                    $this->hline(10,200,$this->liney);
                    $seasondebit=0;
                    $seasoncredit=0;
                    $this->seasoncode=$row['SEASONYEAR'];
                    $this->newpage(true);
                    $lastseasonyear=$row['SEASONYEAR'];
                    $srno=0;
                }
            $lastcirclecode=$row['CIRCLECODE'];
            $lastvillagecode=$row['VILLAGECODE'];
            $srno++;
        }
        $this->hline(10,200,$this->liney);
        $this->textbox('एकूण ('.$this->seasoncode.')',100,35,'S','R',1,'siddhanta',10,'','Y');
        $this->textbox($seasoncredit,35,165,'S','R',1,'SakalMarathiNormal922',9); 
        $this->textbox($seasondebit,35,135,'S','R',1,'SakalMarathiNormal922',9);
        $this->newrow();
        $this->hline(10,200,$this->liney);

        //$this->newrow($height2);
        $this->hline(10,200,$this->liney);
        $this->textbox($credit,35,165,'S','R',1,'SakalMarathiNormal922',9); 
        $this->textbox($debit,35,135,'S','R',1,'SakalMarathiNormal922',9);
        $this->newrow();
        $this->hline(10,200,$this->liney);
        $this->pagefooter(true);
    }
    function export()
    {
           $filename='subledgeryearbalancelist.csv';
           //$fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');
           $fp1=fopen('php://memory', 'w');
           if ($this->seasoncode!='')
            $cond=' seasonyear='.$this->seasoncode;
            else    
            $cond='1=1';

        $query ="select a.yearperiodcode,a.accountcode,seasonyear,c.circlecode,circlenameuni,v.villagecode,v.villagenameuni,f.farmercode,a.subledgercode,s.subledgernameuni,nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) as balance
        from (
        select a.yearperiodcode,a.accountcode,a.subledgercode,a.seasonyear,sum(nvl(a.creditbalance,0)) as creditbalance,sum(nvl(a.debitbalance,0)) as debitbalance
        from accountopening a
        where a.yearperiodcode=".$this->yearcode." and a.accountcode=".$this->accountcode." and a.subledgercode is not null
        group by a.yearperiodcode,a.accountcode,a.subledgercode,a.seasonyear
        union all
        select t.yearperiodcode,d.accountcode,d.subledgercode,d.costcentrecode,nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance
        from voucherheader t,voucherdetail d
        where t.transactionnumber=d.transactionnumber
        and t.yearperiodcode=".$this->yearcode." 
        and t.voucherdate<='".$this->todate."'
        and d.accountcode=".$this->accountcode." 
        and d.subledgercode is not null
        and t.approvalstatus=9
        group by t.yearperiodcode,d.accountcode,d.subledgercode,d.costcentrecode)a
        ,accountsubledger s
        ,nst_nasaka_agriculture.farmer f
        ,nst_nasaka_agriculture.village v
        ,nst_nasaka_agriculture.circle c
        where a.accountcode=s.accountcode
        and a.subledgercode=s.subledgercode
        and s.referencecode='F'||f.farmercode(+)
        and f.villagecode=v.villagecode
        and v.circlecode=c.circlecode
        and ".$cond." 
        group by a.yearperiodcode,a.accountcode,seasonyear,c.circlecode,circlenameuni,v.villagecode,v.villagenameuni,f.farmercode,a.subledgercode,s.subledgernameuni
        having nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0)<>0
        order by seasonyear,c.circlecode,circlenameuni,v.villagecode,v.villagenameuni,s.subledgernameuni";
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $response = array();
           fputcsv($fp1, array('Account Code','Season Year','Circle Code','Circle Name','Village Code','Village Name','Farmer Code','Subledger Code','Subledger Name','Cl Bal(Cr)','Cl Bal(Dr)'));
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                if ($row['BALANCE']>=0)
                {
                    fputcsv($fp1, array($row['ACCOUNTCODE'],$row['SEASONYEAR'],$row['CIRCLECODE'],$row['CIRCLENAMEUNI'],$row['VILLAGECODE'],$row['VILLAGENAMEUNI'],$row['FARMERCODE'],$row['SUBLEDGERCODE'],$row['SUBLEDGERNAMEUNI'],0,$row['BALANCE']), $delimiter = ',', $enclosure = '"');
                }
                else
                {
                    fputcsv($fp1, array($row['ACCOUNTCODE'],$row['SEASONYEAR'],$row['CIRCLENAMEUNI'],$row['VILLAGECODE'],$row['VILLAGENAMEUNI'],$row['FARMERCODE'],$row['SUBLEDGERCODE'],$row['SUBLEDGERNAMEUNI'],abs($row['BALANCE']),0), $delimiter = ',', $enclosure = '"');
                }
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