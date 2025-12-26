<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A5_L.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class acletter extends reportbox
{	
    Public $letterdate;
    Public $farmercode;
    Public $circlecode;
    Public $reference;
    Public $summary;
    public $shares;
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
	    $this->pdf = new MYPDF('L', PDF_UNIT, 'A5', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('AC Letter');
        $this->pdf->SetKeywords('ACLET_000.MR');
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));
      
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
        //$this->reportfooter();
    }
    function endreport()
    {
        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output('ACLET_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 10;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  


        //$this->drawlines($limit);
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

    function group()
    {
        $this->totalgroupcount=1;
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
                select t.farmercode,accountcode,accountnameuni,closingbalance,rownum as serialnumber
                ,t.farmernameeng,t.farmernameuni
                ,villagenameeng,villagenameuni,talukanameeng,talukanameuni,districtnameuni
                ,c.farmercategorynameuni
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
                )t,farmer f,farmercategory c
                where t.farmercode=f.farmercode and f.farmercategorycode=c.farmercategorycode
                and closingbalance>0
                order by f.villagecode,f.farmernameuni,rownum";
                
                $group_result_1 = oci_parse($this->connection, $group_query_1);
                $r = oci_execute($group_result_1);
                $i=0;
                //$this->newpage(true);
                while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    $this->grouptrigger($group_row_1,$last_row);
                    $this->detail_1($group_row_1);
                    $last_row=$group_row_1;
                }
                $this->grouptrigger($group_row_1,$last_row,'E');
                $this->reportfooter();
            }
    }
    
    function groupheader_1(&$group_row_1)
    {
        $this->newrow(6);
        $this->summary['CLOSINGBALANCE']=0;
        $this->setfieldwidth(120,24);
        $this->textbox('पत्र क्र./केन अकौंट/१०००/२२-२३',$this->w,$this->x,'S','L',1,'siddhanta',11);
 
        //$this->newrow(10);
        $this->setfieldwidth(65,160);
        $this->textbox('दिनांक '.$this->letterdate,$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->newrow(7);

        $this->setfieldwidth(65,20);
        $this->textbox('प्रती,',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->newrow(6);

        $this->setfieldwidth(150,20);
        $this->textbox('श्री/श्रीमती-'.$group_row_1['FARMERNAMEUNI'].' - '.$group_row_1['FARMERCATEGORYNAMEUNI'].' कोड-'.$group_row_1['FARMERCODE'],$this->w,$this->x,'S','L',1,'siddhanta',13);
        $this->newrow(7);

        $this->setfieldwidth(65,20);
        $this->textbox('मु./पो. -'.$group_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(65);
        $this->textbox('ता. -'.$group_row_1['TALUKANAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(65);
        $this->textbox('जिल्हा -'.$group_row_1['DISTRICTNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->newrow(6);

        $this->setfieldwidth(65,20);
        $this->textbox('विषय - येणे रक्कम भरणा करणेबाबत...',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->newrow(6);

        $this->setfieldwidth(120,20);
       // $this->textbox('संदर्भ -'.$this->reference,$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->textbox('संदर्भ - केन अकौंट/१४५६/२०२०-२१/दि.२७/०७/२०२०',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->newrow(5);

        $this->setfieldwidth(65,20);
        $this->textbox('महाशय,',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->newrow(7);

        $this->setfieldwidth(180,30);
        $this->textbox('वरील विषयास अनुसरून कळविण्यात येते कि,आपल्याकडे कारखान्याची दि.'.$this->letterdate.' अखेर खालील प्रमाणे येणे बाकी आहे.',$this->w,$this->x,'S','L',1,'siddhanta',11,'','Y');
       
        $this->newrow(5);

        $this->hline(35,175,$this->liney+5,'C');
        $this->newrow(6);
        $this->setfieldwidth(15,20);
        //$this->vline($this->liney-1,$this->liney+5,$this->x);
        //$this->textbox('अ.नं.',$this->w,$this->x,'S','L',1,'siddhanta',11);
        //$this->newrow(10);
        //$this->hline(10,325,$this->liney+5,'C');
        //$this->newrow(7);

        $this->setfieldwidth(60);
        $this->vline($this->liney-1,$this->liney+5,$this->x);
        $this->textbox('तपशील',$this->w,$this->x,'S','L',1,'siddhanta',11);
        //$this->newrow(2);
        //$this->hline(10,200,$this->liney+5,'C');
        //$this->newrow(7);

        $this->setfieldwidth(40);
        $this->vline($this->liney-1,$this->liney+5,$this->x);
        $this->textbox('येणे रक्कम रु.',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+5,$this->x+$this->w);
        
        $this->setfieldwidth(40);
        $this->vline($this->liney-1,$this->liney+5,$this->x);
        $this->textbox('शेरा',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+5,$this->x+$this->w);
        $this->hline(35,175,$this->liney+5,'C');
        $this->newrow(6);


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
        $this->setfieldwidth(15,20);
        //$this->vline($this->liney-1,$this->liney+5,$this->x);
        //$this->textbox($group_row_1['SERIALNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(60);
        $this->textbox($group_row_1['ACCOUNTNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->textbox($group_row_1['CLOSINGBALANCE'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(40);
        if ($group_row_1['ACCOUNTCODE']!='0')
        $this->textbox('+व्याज',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+5,$this->x+$this->w);
        $this->summary['CLOSINGBALANCE']=$this->summary['CLOSINGBALANCE']+$group_row_1['CLOSINGBALANCE'];
        if ($this->isnewpage(15))
        {
            $this->newrow(6);
            $this->hline(35,175,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow(6);
            $this->hline(35,175,$this->liney,'D'); 
        }
    }
    
    function groupfooter_1(&$group_row_1)
    {
        if ($this->isnewpage(15))
        {
            $this->newrow(6);
            $this->hline(35,175,$this->liney-2,'C'); 
            $this->newpage(True);
        } 
        $this->setfieldwidth(15,20);
        //$this->vline($this->liney-1,$this->liney+5,$this->x);
        $this->vline($this->liney-1,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(60);
        $this->textbox('एकूण',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->textbox($this->summary['CLOSINGBALANCE'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->textbox('+व्याज',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+5,$this->x+$this->w);
        if ($this->isnewpage(25))
        {
            $this->newrow(6);
            $this->hline(35,175,$this->liney-1,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow(6);
            $this->hline(35,175,$this->liney-1,'C');
        }

        $this->newrow(2);
        $this->setfieldwidth(180,20);
       // $this->textbox('वरील प्रमाणे येणे रक्कम दि. '.$this->letterdate.' अखेर कारखान्याचे अकौंट विभागाकडे भरून सहकार्य करावे व तशी रितसर पावती घ्यावी ही विनंती.(वरील पैकी बाकी भरली असल्यास अकौंट विभागाकडे खात्री करून घ्यावी.)',$this->w,$this->x,'S','L',1,'siddhanta',11,'','Y');
       $this->textbox('वरील प्रमाणे येणे रक्कम दि.१२/०८/२०२२ अखेर कारखान्याचे अकौंट विभागाकडे भरून सहकार्य करावे व तशी रितसर पावती घ्यावी ही विनंती.(वरील पैकी बाकी भरली असल्यास अकौंट विभागाकडे खात्री करून घ्यावी.)',$this->w,$this->x,'S','L',1,'siddhanta',11,'','Y'); 
        $this->newrow(10);
        //$this->setfieldwidth(200,30);
        //$this->textbox('घ्यावी ही विनंती.(वरील पैकी बाकी भरली असल्यास अकौंट विभागाकडे खात्री करून घ्यावी.',$this->w,$this->x,'S','L',1,'siddhanta',11);

        //$this->newrow(6);
        $this->setfieldwidth(180,30);
        $this->textbox('कळावे.',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(55);
        $this->textbox('आपला विश्वासू',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $image_file = '..\img\mdsign.jpg';
        //$image_file = K_PATH_IMAGES.'mdsign.jpg';
        $y=$this->liney;
        $this->pdf->Image($image_file, 160, $y, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        
        $this->newrow(10);
        $this->setfieldwidth(40,152);
        $this->textbox('जनरल मॅनेजर',$this->w,$this->x,'S','L',1,'siddhanta',11);

        $this->newrow(4);
        $this->setfieldwidth(60,141);
        $this->textbox('नाशिक सह.सा.का.लि.पळसे',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->summary['CLOSINGBALANCE']=0;
        $this->newpage(True);
    }
    function groupfooter_2(&$group_row_2)
    {  
    }

    function groupfooter_3(&$group_row_3)
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

    function reportfooter()
    {
    }

}    
?>
