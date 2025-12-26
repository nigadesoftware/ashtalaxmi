<?php
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a5_l.php");
    //include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    include_once("../ip_model/accounthead_db_oracle.php");
class voucher extends swappreport
{	
    public $connection;
    public $liney;
    public $maxlines;
    public $pdf;
    public $ispagebig;
    //
    public $transactionid;
 
    public function __construct(&$connection,$maxlines,$isbig=0)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        // create new PDF document
        $this->ispagebig = $isbig;
        if ($this->ispagebig == 0)
        {
            $this->pdf = new MYPDF('L', PDF_UNIT, 'A5', true, 'UTF-8', false);
        }
        else
        {
            $this->pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        }
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Voucher');
        $this->pdf->SetKeywords('VOUCHER_000.MR');
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
                if ($this->ispagebig == 0)
                    $this->pdf->addpage('P','A5');
                else
                    $this->pdf->addpage('P','A4');
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
            $this->pdf->addpage();
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
	    $this->pdf->lastPage();

        // ---------------------------------------------------------
        ob_end_clean();
        //Close and output PDF document
        $this->pdf->Output('VOUCHER_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 15;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->liney = $this->liney+5;
    	$this->pdf->line(5,$this->liney,200,$this->liney);
        $this->liney = $this->liney+2;
        $query = "select h.transactionnumber
        ,s.vouchersubtypenameuni
        ,s.vouchersubtypenameeng
        ,h.vouchernumberprefixsufix
        ,h.voucherdate
        ,h.description
        ,h.narration
        ,t.vouchertypecode
        from voucherheader h,vouchersubtype s,vouchertype t
        where h.vouchersubtypecode=s.vouchersubtypecode
        and s.vouchertypecode= t.vouchertypecode
        and h.transactionnumber=".$this->transactionnumber;
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        //shift
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->pdf->SetFont('siddhanta', '', 13, '', true);
            //$dt = $fromdate = DateTime::createFromFormat('d-M-y',$this->fromdate)->format('d/m/Y');
            $this->pdf->multicell(0,15,$row['VOUCHERSUBTYPENAMEUNI'],0,'C',false,1,0,$this->liney-7,true,0,false,true,10);
            $this->pdf->SetFont('siddhanta', '', 12, '', true);
            $this->pdf->multicell(100,10,'व्यवहार नं:'.$row['TRANSACTIONNUMBER'],0,'L',false,1,5,$this->liney,true,0,false,true,10);
            if ($row['VOUCHERDATE']!='')
            {
                $dt = DateTime::createFromFormat('d-M-y',$row['VOUCHERDATE'])->format('d/m/Y');	
            }
            else
            {
                $dt='';
            }
            $this->pdf->multicell(55,10,'वाउचर नं:'.$row['VOUCHERNUMBERPREFIXSUFIX'],0,'L',false,1,70,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(32,10,'वाउचर दिनांक:',0,'L',false,1,153,$this->liney,true,0,false,true,10);            
            $this->pdf->multicell(30,10,$dt,0,'L',false,1,178,$this->liney,true,0,false,true,10);
            $this->liney = $this->liney+5;
            $this->pdf->SetFont('siddhanta', '', 10, '', true);
            if ($row['VOUCHERTYPECODE']==1 or $row['VOUCHERTYPECODE']==2)
            {
                $this->pdf->multicell(80,10,'हस्ते: '.$row['DESCRIPTION'],0,'L',false,1,5,$this->liney,true,0,false,true,10);
            }
            $this->liney = $this->liney+5;
            $this->pdf->line(5,$this->liney,200,$this->liney);
            $this->liney = $this->liney+2;
            $this->pdf->SetFont('siddhanta', '', 12, '', true);
            $this->pdf->multicell(20,10,'अ.नं',0,'L',false,1,5,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(60,10,'खाते/पोटखाते नाव',0,'L',false,1,20,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(30,10,'रक्कम',0,'R',false,1,153,$this->liney,true,0,false,true,10);
            $this->liney = $this->liney+5;
            $this->pdf->line(130,$this->liney,200,$this->liney);
            $this->pdf->multicell(30,10,'नावे',0,'R',false,1,135,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(30,10,'जमा',0,'R',false,1,170,$this->liney,true,0,false,true,10);
            $this->liney = $this->liney+5;
            $this->pdf->line(5,$this->liney,200,$this->liney);
            $this->liney = $this->liney+2;
        } 
    }


function pagefooter($islastpage = false)
    {
        $query = "select h.narration
        from voucherheader h
        where h.transactionnumber=".$this->transactionnumber;
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        //shift
        $height1=5;
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            //$this->pdf->SetFont('siddhanta', '', 8, '', true);
            //$height = $this->pdf->multicell(200,10,'तपशील:'.$row['NARRATION'],0,'L',false,1,5,$this->liney,true,0,false,true,30);
            $height1 = $this->textbox('तपशील:'.$row['NARRATION'],150,10,'S','L',1,'siddhanta',8,'');
        }
        if ($this->isnewpage($height1+5))
        {
            $this->newpage();
        }
        $this->newrow($height1+5); 
        //$this->pdf->multicell(200,10,'घेणाराची सही : ',0,'L',false,1,5,$this->liney,true,0,false,true,30);
        //$this->textbox('घेणाराची सही',200,10,'S','L',1,'siddhanta',11,'','Y');
        $this->hline(170,180,$this->liney-5);
        $this->hline(170,180,$this->liney+5);
        $this->vline($this->liney+5,$this->liney-5,170);
        $this->vline($this->liney+5,$this->liney-5,180);

        $this->liney = $this->liney+15; 
        $this->pdf->SetFont('siddhanta', '', 12, '', true);
        $this->pdf->multicell(200,10,'तयार करणार/रोखपाल       अकौंटंट       डे.चिफ अकौंटंट       चिफ अकौंटंट     जनरल मॅनेजर ',0,'L',false,1,5,$this->liney,true,0,false,true,10);
    }


	function detail()
    {
        $query = "select * from (select drcr
        ,t.accountcode
        ,null refnumber
        ,t.accountnameuni
        ,t.issubledgerallowed
        ,t.detailserialnumber
        ,sum(t.credit)credit
        ,sum(t.debit) debit
        from (
        select  case when credit <>0 and v.vouchertypecode in (1,3) then 0 
             when debit <>0 and v.vouchertypecode in (1,3) then 1
             when credit <>0 and v.vouchertypecode in (2,4,5,6) then 1 
             when debit <>0 and v.vouchertypecode in (2,4,5,6) then 0
             else 1 end as drcr
        ,t.accountcode
        ,h.accountnameuni
        ,case when isvoucherdetaildisplay=248805611 then 248805611 else h.issubledgerallowed end issubledgerallowed
        ,case when isvoucherdetaildisplay=248805611 then 0 else detailserialnumber end detailserialnumber
        ,t.credit
        ,t.debit
        from voucherdetail t,accounthead h,voucherheader r,vouchersubtype s,vouchertype v
        where t.transactionnumber=r.transactionnumber
        and r.vouchersubtypecode=s.vouchersubtypecode
        and s.vouchertypecode=v.vouchertypecode
        and t.accountcode=h.accountcode 
        and t.transactionnumber=".$this->transactionnumber."
        and (h.issubledgerallowed=248805611 or isvoucherdetaildisplay=248805611)
        )t
        group by drcr,t.accountcode
        ,t.accountnameuni
        ,t.issubledgerallowed
        ,t.detailserialnumber
        union all
        select drcr
        ,t.accountcode
        ,t.refnumber
        ,t.accountnameuni
        ,t.issubledgerallowed
        ,0 as detailserialnumber
        ,sum(t.credit)credit
        ,sum(t.debit) debit
        from (
        select  case when credit <>0 and v.vouchertypecode in (1,3) then 0 
             when debit <>0 and v.vouchertypecode in (1,3) then 1
             when credit <>0 and v.vouchertypecode in (2,4,5,6) then 1 
             when debit <>0 and v.vouchertypecode in (2,4,5,6) then 0
             else 1 end as drcr
        ,t.accountcode
        ,t.refnumber
        ,h.accountnameuni
        ,h.issubledgerallowed
        ,t.detailserialnumber
        ,t.credit
        ,t.debit
        from voucherdetail t,accounthead h,voucherheader r,vouchersubtype s,vouchertype v
        where t.transactionnumber=r.transactionnumber
        and r.vouchersubtypecode=s.vouchersubtypecode
        and s.vouchertypecode=v.vouchertypecode
        and t.accountcode=h.accountcode 
        and t.transactionnumber=".$this->transactionnumber."
        and h.issubledgerallowed=248805511 and isvoucherdetaildisplay!=248805611
        )t
        group by drcr,t.accountcode,t.refnumber
        ,t.accountnameuni
        ,t.issubledgerallowed
        )t
        order by drcr,t.accountcode,t.refnumber
        ,t.accountnameuni
        ,t.issubledgerallowed
        ,t.detailserialnumber";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $mainsrno=1;
        $totalcredit=0;
        $totaldebit=0;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {  
            $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
            $this->pdf->multicell(20,10,$mainsrno,0,'L',false,1,5,$this->liney,true,0,false,true,10);
            $this->pdf->multicell(20,10,$row['ACCOUNTCODE'],0,'R',false,1,15,$this->liney,true,0,false,true,10);
            $this->pdf->SetFont('siddhanta', '', 11, '', true);
            $this->pdf->multicell(100,10,$row['ACCOUNTNAMEUNI'],0,'L',false,1,35,$this->liney,true,0,false,true,10);
            $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
            if ($row['CREDIT']!=0)
            {
                $this->pdf->multicell(30,10,$this->moneyFormatIndia($row['CREDIT']),0,'R',false,1,170,$this->liney,true,0,false,true,10);
                $totalcredit+=$row['CREDIT'];
            }
            if ($row['DEBIT']!=0)
            {
                $this->pdf->multicell(30,10,$this->moneyFormatIndia($row['DEBIT']),0,'R',false,1,135,$this->liney,true,0,false,true,10);
                $totaldebit+=$row['DEBIT'];
            }
            $accounthead11=new accounthead($this->connection);
            $accounthead11->accountcode = $row['ACCOUNTCODE'];
            $accounthead11->fetch();
            $height1=0;
            if ($row['ISSUBLEDGERALLOWED']== 248805511 and $accounthead11->isvoucherdetaildisplay!=248805611)
            {
                $query1 = "select * from (select case when credit <>0 and v.vouchertypecode in (1,3) then 0 
                when debit <>0 and v.vouchertypecode in (1,3) then 1
                when credit <>0 and v.vouchertypecode in (2,4,5,6) then 1 
                when debit <>0 and v.vouchertypecode in (2,4,5,6) then 0
                else 1 end as drcr,
                t.detailserialnumber
                   ,t.accountcode
                   ,h.accountnameuni
                   ,t.subledgercode
                   ,s.subledgernameuni
                   ,t.credit
                   ,t.debit
                   ,h.issubledgerallowed
                   ,r.vouchersubtypecode
                   ,s.referencecode
                   ,t.costcentrecode
                   ,to_char(t.refdate,'dd/MM/YYYY') refdate
                   from voucherdetail t,accounthead h,accountsubledger s,voucherheader r,vouchersubtype s,vouchertype v
                   where t.accountcode=h.accountcode 
                   and t.subledgercode=s.subledgercode
                   and h.accountcode=s.accountcode
                   and t.accountcode=".$row['ACCOUNTCODE']."
                   and nvl(t.refnumber,0)=nvl(".$this->invl($row['REFNUMBER']).",0)
                   and t.transactionnumber=r.transactionnumber
                   and r.vouchersubtypecode=s.vouchersubtypecode
                   and s.vouchertypecode=v.vouchertypecode
                   and t.transactionnumber=".$this->transactionnumber.")
                   where drcr=".$row['DRCR']."
                   order by detailserialnumber";
                $result1 = oci_parse($this->connection, $query1);
                $r1 = oci_execute($result1);
                $subsrno=1;
                $height=7;
                while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                {  
                    if ($this->isnewpage(5))
                    {
                        $this->newpage();
                    }
                    $this->liney = $this->liney+$height;
                    $this->pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
                    $this->pdf->multicell(15,10,$mainsrno.".".$subsrno++,0,'L',false,1,8,$this->liney,true,0,false,true,10);
                    $this->pdf->multicell(20,10,$row1['SUBLEDGERCODE'],0,'R',false,1,20,$this->liney,true,0,false,true,10);
                    $this->pdf->SetFont('siddhanta', '', 10, '', true);
                    
                    if ($row1['COSTCENTRECODE']=='')
                    {
                        $height=4;
                        $this->pdf->multicell(90,10,$row1['SUBLEDGERNAMEUNI'].'- '.$row1['REFERENCECODE'],0,'L',false,1,40,$this->liney,true,0,false,true,10);
                    }
                    else
                    {
                        $height=4;
                        $this->pdf->multicell(90,10,$row1['SUBLEDGERNAMEUNI'].'- '.$row1['REFERENCECODE'].' ('.$row1['COSTCENTRECODE'].')',0,'L',false,1,40,$this->liney,true,0,false,true,10);
                    }
                    if ($row1['REFDATE']!='')
                    {
                        $this->pdf->multicell(30,10,'['.$row1['REFDATE'].']',0,'R',false,1,110,$this->liney,true,0,false,true,7);
                    }
                    
                        $this->pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
                    if ($row['CREDIT']!=0)
                    {
                        $this->pdf->multicell(30,10,$this->moneyFormatIndia($row1['CREDIT']),0,'R',false,1,165,$this->liney,true,0,false,true,10);
                        //$totalcredit+=$row1['CREDIT'];
                    }
                    if ($row['DEBIT']!=0)    
                    {
                        $this->pdf->multicell(30,10,$this->moneyFormatIndia($row1['DEBIT']),0,'R',false,1,130,$this->liney,true,0,false,true,10);
                       //$totaldebit+=$row1['DEBIT'];
                    }
                }
                $this->liney = $this->liney+$height;
            }
            else
            {
                if ($this->isnewpage($height1+5))
                {
                    $this->newpage();
                }
                $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
                if ($row['CREDIT']!=0)
                {
                    $this->pdf->multicell(30,10,$this->moneyFormatIndia($row['CREDIT']),0,'R',false,1,170,$this->liney,true,0,false,true,10);
                    //$totalcredit+=$row['CREDIT'];
                }
                if ($row['DEBIT']!=0)
                {
                    $this->pdf->multicell(30,10,$this->moneyFormatIndia($row['DEBIT']),0,'R',false,1,135,$this->liney,true,0,false,true,10);
                    //$totaldebit+=$row['DEBIT'];
                }
                $this->liney = $this->liney+5;
                if ($this->isbankaccountcode($row['ACCOUNTCODE'])==1)
                {
                    if ($this->isnewpage($height1+5))
                    {
                        $this->newpage();
                    }
                    //$this->pdf->line(5,$this->liney,200,$this->liney);
                    $this->pdf->SetFont('siddhanta', '', 10, '', true);
                    $this->pdf->multicell(25,10,'दिनांक',0,'L',false,1,5,$this->liney,true,0,false,true,10);
                    $this->pdf->multicell(25,10,'रक्कम',0,'R',false,1,30,$this->liney,true,0,false,true,10);
                    $this->pdf->multicell(25,10,'नंबर',0,'L',false,1,55,$this->liney,true,0,false,true,10);
                    $this->pdf->multicell(25,10,'प्रकार',0,'L',false,1,80,$this->liney,true,0,false,true,10);
                    $this->pdf->multicell(30,10,'ड्रा. बँक',0,'L',false,1,105,$this->liney,true,0,false,true,10);
                    $this->pdf->line(5,$this->liney+5,130,$this->liney+5);
                    $query2 = "select t.bankaccountcode
                    ,h.accountnameuni
                    ,f.funddocumentnameuni
                    ,t.funddocumentdate
                    ,t.funddocumentnumber
                    ,t.funddocumentamount
                    ,t.draweebank
                    from voucherchequedddetail t,funddocumenttype f,accounthead h
                    where t.funddocumentcode=f.funddocumentcode
                    and t.bankaccountcode=h.accountcode
                    and t.transactionnumber =".$this->transactionnumber;
                    $result2 = oci_parse($this->connection, $query2);
                    $r2 = oci_execute($result2);
                    while ($row2 = oci_fetch_array($result2,OCI_ASSOC+OCI_RETURN_NULLS))
                    {  
                        if ($this->isnewpage($height1+5))
                        {
                            $this->newpage();
                        }
                        $this->liney = $this->liney+5;
                        //$this->pdf->SetFont('siddhanta', '', 11, '', true);
                        $this->pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
                            $dts = DateTime::createFromFormat('d-M-y',$row2['FUNDDOCUMENTDATE'])->format('d/m/Y');	
                        $this->pdf->multicell(25,10,$dts,0,'L',false,1,5,$this->liney,true,0,false,true,10);
                        $this->pdf->multicell(25,10,$row2['FUNDDOCUMENTAMOUNT'],0,'R',false,1,30,$this->liney,true,0,false,true,10);
                        $this->pdf->multicell(30,10,$row2['FUNDDOCUMENTNUMBER'],0,'L',false,1,55,$this->liney,true,0,false,true,10);
                        $this->pdf->SetFont('siddhanta', '', 11, '', true);
                        $this->pdf->multicell(25,10,$row2['FUNDDOCUMENTNAMEUNI'],0,'L',false,1,80,$this->liney,true,0,false,true,10);
                        $this->pdf->multicell(25,5,$row2['DRAWEEBANK'],0,'L',false,1,105,$this->liney,true,0,false,true,5);
                    }
                    $this->liney = $this->liney+4;
                }
            }

            $mainsrno++;
            if ($this->isnewpage(5))
            {
                $this->newpage();
            }
        }
        if ($this->isnewpage(60))
        {
            $this->newpage();
        }
        $this->liney = $this->liney+5;
        $this->pdf->line(5,$this->liney,200,$this->liney);
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->pdf->multicell(15,10,'एकूण',0,'L',false,1,120,$this->liney,true,0,false,true,10);
        $this->pdf->SetFont('SakalMarathiNormal922', '', 11, '', true);
        $this->pdf->multicell(30,10,$this->moneyFormatIndia($totalcredit),0,'R',false,1,170,$this->liney,true,0,false,true,10);
        $this->pdf->multicell(30,10,$this->moneyFormatIndia($totaldebit),0,'R',false,1,135,$this->liney,true,0,false,true,10);
        $this->liney = $this->liney+5;
        $this->pdf->line(5,$this->liney,200,$this->liney);
        $this->liney = $this->liney+2;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $whole = floor($totalcredit);      // 1
        $fraction = round(($totalcredit-$whole)*100,0); // .25
        $v=round($totalcredit,2)-round($totaldebit,2);
        if ($v!=0)
        {
            $this->pdf->SetFont('siddhanta', '', 15, '', true);
            $this->pdf->multicell(200,10,' XXX   जमा रक्कम नावे रक्कम जुळत नाही  XXX ',0,'L',false,1,5,$this->liney,true,0,false,true,10);
        }
        elseif ($fraction==0)
        {
            $this->pdf->multicell(200,10,'अक्षरी:रुपये '.NumberToWords($whole,1).' फक्त',0,'L',false,1,5,$this->liney,true,0,false,true,10);
        }
        else
        {
            $a=NumberToWords($whole,1);
            $b=NumberToWords($fraction,1);
            $this->pdf->multicell(200,10,'अक्षरी:रुपये '.$a.' आणि '.$b.' पैसे फक्त',0,'L',false,1,5,$this->liney,true,0,false,true,10);
        }
        $this->liney = $this->liney+5;
        $this->pagefooter();
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