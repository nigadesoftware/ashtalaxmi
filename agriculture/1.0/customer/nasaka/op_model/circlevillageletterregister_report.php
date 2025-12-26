<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
   // include_once("../swappbase/mypdf_A4_P.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class circlevillagesugarregister extends swappreport
{	
    Public $letterdate;
    Public $farmercode;
    Public $circlecode;
    Public $reference;
    Public $summary;
    public $shares;
    public $circlename;
    public $villagecode;
    public $villagename;

    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueType', '', 32);
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
        $this->pdf->SetSubject('Sale Register');
        $this->pdf->SetKeywords('SLRG_000.EN');
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

    function endreport()
    {
        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output('SLRG_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 10;
        $this->pdf->SetFont('siddhanta', '', 12, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        $this->newrow(7);
        $this->textbox('पत्र क्र./केन अकौंट/१०००/२२-२३ नुसार दि.२८/०६/२०२२ अखेर येणे बाकी यादी ',187,10,'S','C',1,'siddhanta',11);
        $this->newrow(2);
        $this->newrow(5);
         $this->textbox('गट:'.$this->circlecode.' '.$this->circlename,60,40,'S','L',1,'siddhanta',12);
        $this->textbox('गाव:'.$this->villagecode.' '.$this->villagename,60,140,'S','L',1,'siddhanta',12);
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow(7);
        $this->textbox('अ.न.',20,10,'S','L',1,'siddhanta',12);
        $this->textbox('कोड नंबर',20,30,'S','L',1,'siddhanta',12);
        $this->textbox('सभासदाचे नाव',60,50,'S','L',1,'siddhanta',12);
        $this->textbox('रक्कम',30,110,'S','R',1,'siddhanta',12);
        $this->textbox('मिळाल्याची सही',70,160,'S','L',1,'siddhanta',12);
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow();
        //$this->drawlines($limit);
    }
   
    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        $liney = $this->liney;
        $this->liney = 85;
        $this->hline(10,200,$this->liney-12);
        $this->vline($this->liney-12,$this->liney+$limit,10);
        $this->vline($this->liney-12,$this->liney+$limit,40);
        $this->vline($this->liney-12,$this->liney+$limit,75);
        $this->vline($this->liney-12,$this->liney+$limit,110);
        $this->vline($this->liney-12,$this->liney+$limit,170);
        $this->vline($this->liney-5,$this->liney+$limit,180);
        $this->hline(10,200,$this->liney+$limit);
        $this->hline(10,200,$this->liney+$limit);
        $this->hline(140,200,$this->liney-5);
        $this->liney = $liney;
    }

    function pagefooter($islastpage=false)
    {
        if ($islastpage==false)
        { 
            //$this->drawlines(130-48);
        }
        else
        {
            //$this->drawlines($this->liney-48);
        }
    }

    function detail()
    {
        $dt=DateTime::createFromFormat('d/m/Y',$this->letterdate)->format('d-M-Y');
        if ($this->shares==1)
            $cond1=" union all
            select 0 accountcode,'शेअर्स' accountnameuni,(v.nno_of_shares*15000-nvl(v.nshare_amt,0)) as closingbalance
            ,fd.farmercode,fd.farmernameeng,fd.farmernameuni
            ,fd.villagenameeng,fd.villagenameuni,fd.talukanameeng,fd.talukanameuni,fd.districtnameuni 
            from sugarshare.sha_daily_trans_header_vw1@orcllink v,farmerdata fd 
            where v.nfarmer_code=fd.farmercode ";
        else
            $cond1="";

        if ($this->farmercode!='')
        {
            $cond = " and f.farmercode=".$this->farmercode;
        }
        elseif ($this->circlecode!='')
        {
            $cond = "  and v.circlecode=".$this->circlecode;
        }
        else
        {
            $cond='';
        }
        if ($cond=='')
        {

        }
        else
        {
               $group_query_1 ="with farmerdata as (select f.farmercode,f.farmernameeng,f.farmernameuni
                ,v.villagenameeng,v.villagenameuni,t.talukanameeng,t.talukanameuni
                ,d.districtnameeng,d.districtnameuni
                from FARMER f, village v, taluka t,district d
                where f.villagecode=v.villagecode
                and v.talukacode=t.talukacode
                and t.districtcode=d.districtcode 
                {$cond})
                ,subledgerbalance as (select accountcode,subledgercode,nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) closing_balance
                from (
                select accountcode,subledgercode,nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance
                from nst_nasaka_finance.accountopening a
                where a.yearperiodcode=".$_SESSION['yearperiodcode']." 
                union all
                select d.accountcode,d.subledgercode,nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance
                from nst_nasaka_finance.voucherheader t,nst_nasaka_finance.voucherdetail d
                where t.transactionnumber=d.transactionnumber
                and t.yearperiodcode=".$_SESSION['yearperiodcode']." and t.voucherdate<='".$dt."'
                and t.approvalstatus=9
                group by d.accountcode,d.subledgercode)
                group by accountcode,subledgercode)
                select c.circlecode,c.circlenameuni,f.villagecode,v.villagenameuni,f.farmercode,f.farmernameuni
                ,row_number() over (partition by c.circlecode,c.circlenameuni,f.villagecode,v.villagenameuni
                order by c.circlecode,c.circlenameuni,f.villagecode,v.villagenameuni,f.farmernameuni,f.farmercode) serialnumber
                ,sum(closingbalance) closingbalance
                from (
                select h.accountcode,h.accountnameuni,s.closing_balance as closingbalance
                ,fd.farmercode,fd.farmernameeng,fd.farmernameuni
                ,fd.villagenameeng,fd.villagenameuni,fd.talukanameeng,fd.talukanameuni,fd.districtnameuni
                from nst_nasaka_finance.ACCOUNTSUBLEDGER t,nst_nasaka_finance.accounthead h,farmerdata fd,subledgerbalance s
                where t.accountcode=h.accountcode and t.referencecode='F'||fd.farmercode
                and t.accountcode=s.accountcode and t.subledgercode=s.subledgercode
                {$cond1}
                union all
                select 0 accountcode,b.bankbranchnameuni accountnameuni,balance closingbalance
                ,fd.farmercode,fd.farmernameeng,fd.farmernameuni
                ,fd.villagenameeng,fd.villagenameuni,fd.talukanameeng,fd.talukanameuni,fd.districtnameuni 
                from 
                (select farmercode,deductioncode,branchcode,seasonyearded,sum(nvl(balance,0)) as balance
                    from (
                    select h.seasonyearcode,h.farmercode,h.deductioncode,h.branchcode,h.seasonyearded,h.claimamount balance
                        from farmerdeductionclaim h,deduction d
                        where  h.deductioncode=d.deductioncode and d.balancesourcecategorycode=2
                        and h.claimdate<='".$dt."' and nvl(h.claimamount,0)>0
                    union all
                    select h.seasonyearcode,h.farmercode,h.deductioncode,h.branchcode,h.seasonyearded,h.creditedamount*-1
                        from farmerdeductionclaim h,deduction d
                        where  h.deductioncode=d.deductioncode and d.balancesourcecategorycode=2
                        and h.claimdate<='".$dt."' and nvl(h.creditedamount,0)>0
                    union all
                    select b.seasonyear,h.farmercode,t.deductioncode,t.branchcode,t.dedseasonyear,t.deductionamount*-1
                    from farmerbillheader h,farmerbilldeductiondetail t,billperiodheader b,deduction d
                    where h.transactionnumber=t.billtransactionnumber and h.billperiodtransnumber=b.billperiodtransnumber
                    and t.deductioncode=d.deductioncode and d.balancesourcecategorycode=2
                    and b.paymentdate<='".$dt."' and b.billperiodtransnumber<>0)
                    group by seasonyearcode,farmercode,deductioncode,branchcode,seasonyearded
                    having sum(nvl(balance,0))>0 and deductioncode=1007)t,bankbranch b,farmerdata fd
                    where t.branchcode=b.bankbranchcode and t.farmercode=fd.farmercode
                )t,farmer f,farmercategory c,village v,circle c
                where t.farmercode=f.farmercode and f.farmercategorycode=c.farmercategorycode
                and f.villagecode=v.villagecode and v.circlecode=c.circlecode 
                and closingbalance>0 
                group by c.circlecode,c.circlenameuni,f.villagecode,v.villagenameuni,f.farmercode,f.farmernameuni
                ";
            $result = oci_parse($this->connection, $group_query_1);
            $r = oci_execute($result);
            $lastvillagecode=0;
            $circnt=0;
            $cirquantity=0;
            $cnt=0;
            $quantity=0;
            $i=1;
            $lastcirclecode=0;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($lastvillagecode==0 or $lastvillagecode!=$row['VILLAGECODE'])
            {
                if ($lastvillagecode!=0 and $lastvillagecode!=$row['VILLAGECODE'])
                {
                    $circnt=0;
                    $cirquantity=0;
                    $this->circlecode=$row['CIRCLECODE'];
                    $this->circlename=$row['CIRCLENAMEUNI'];
                    $this->villagecode=$row['VILLAGECODE'];
                    $this->villagename=$row['VILLAGENAMEUNI'];
                    $this->hline(10,200,$this->liney);
                    $this->newpage(True);
                }
                else if ($lastvillagecode==0)
                {
                    $this->circlecode=$row['CIRCLECODE'];
                    $this->circlename=$row['CIRCLENAMEUNI'];
                    $this->villagecode=$row['VILLAGECODE'];
                    $this->villagename=$row['VILLAGENAMEUNI'];
                    $this->newpage(True);
                }
                
                //$this->newpage(true);
            }
            if ($this->isnewpage(15))
            {
                //$this->newrow();
                $this->hline(10,200,$this->liney,'C');  
                $this->newpage(True);
            }
            else
            {
                //$this->newrow();
                //$this->hline(10,195,$this->liney-2,'C'); 
                //$this->newpage(True);
            }
            $this->textbox($row['SERIALNUMBER'],20,10,'N','L',1,'SakalMarathiNormal922',12);
            $this->textbox($row['FARMERCODE'],20,30,'N','L',1,'SakalMarathiNormal922',12);
            $this->textbox($row['FARMERNAMEUNI'],60,50,'S','L',1,'siddhanta',12);
            $this->textbox($row['CLOSINGBALANCE'],30,110,'N','R',1,'SakalMarathiNormal922',12);
            //$this->setfieldwidth(40);
            //$this->textbox($this->summary['CLOSINGBALANCE'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11);
            $this->hline(10,200,$this->liney);
            $this->newrow(12);
            if ($lastcirclecode!=$row['CIRCLECODE'] and $lastcirclecode!=0)
            {
                $i=1;
            }
            $lastvillagecode=$row['VILLAGECODE'];
            $lastcirclecode=$row['CIRCLECODE'];
        
        }
        /* $this->hline(10,200,$this->liney);
        $this->textbox($circnt,20,111,'N','R',1,'SakalMarathiNormal922',12);
        $this->textbox($cirquantity,30,131,'N','R',1,'SakalMarathiNormal922',12);
        $this->newrow(5);
        $this->hline(10,200,$this->liney);

        //$this->newpage(True);
        //$this->newrow(5);
        $this->hline(10,200,$this->liney);
        $this->hline(10,200,$this->liney);
        $this->textbox($cnt,20,113,'N','R',1,'SakalMarathiNormal922',12);
        $this->textbox($quantity,30,132,'N','R',1,'SakalMarathiNormal922',12);
        $this->textbox('एकुण एकंदर ',35,50,'N','R',1,'SakalMarathiNormal922',10);
        $this->newrow(5);
        $this->hline(10,200,$this->liney); */
        
    }
    }
}
  
?>
