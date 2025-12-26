<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class ledger extends reportbox
{	
    public $dieselsmry;
   
    public $fromdate;
    public $todate;
    public $farmercategorycode;

   
   
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
	    $this->pdf = new MYPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
        
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
        $this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

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
        $this->groupfield1='SLIPCOUNT';
        $this->groupfield2='NETWEIGHT';
        $this->groupfield3='MGROSS';
        $this->groupfield4='MDED';
        $this->groupfield5='MNET';
        $this->groupfield6='MADVANCE';
        $this->groupfield7='MDIESEL';
        $this->groupfield8='MDEPOSIT';
        $this->groupfield9='MTYRERENT';
        $this->groupfield10='MSTOREITEM';
        $this->groupfield11='OTHERDEDUCTION';
        

        $this->resetgroupsummary(0);
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
        $this->pdf->Output('SLRG_000.pdf', 'I');
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
       // $this->textbox($this->ftransactionnumber['FARMERCOUNT'],175,10,'S','C',1,'siddhanta',13);
       
        $this->newrow();
        $this->textbox('वाहतूकदार लेजर',250,10,'S','C',1,'siddhanta',13);
        $this->newrow();
       
        $this->textbox('हंगाम : '.$_SESSION['yearperiodcode'],250,10,'S','L',1,'siddhanta',13,'','','','B');
       
        $this->hline(10,285,$this->liney+6,'C');
        $this->newrow();
      
        $this->setfieldwidth(15,10);   
        $this->vline($this->liney-1,$this->liney+6,$this->x);   
        $this->textbox('पंध.क्र',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('खेपा',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('टनेज',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('एकूण रक्कम',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);


        $this->setfieldwidth(25);
        $this->textbox('एकूण कपात',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(25);
        $this->textbox('निव्वळ देय',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

      
        $this->setfieldwidth(25);
        $this->textbox('ऍ़डव्हान्स',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

               
        $this->setfieldwidth(25);
        $this->textbox('डिझेल',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('डिपॉझीट',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

               
        $this->setfieldwidth(25);
        $this->textbox('टायर भाडे',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('स्टोअर मटेरियल',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('इतर कपात',$this->w,$this->x,'S','L',1,'siddhanta',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->hline(10,285,$this->liney+6,'C');
        $this->newrow();
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
       

        $this->totalgroupcount=2;
        $cond="1=1";       
        $cond=$cond." and mmm.mseason=".$_SESSION['yearperiodcode'];
        if ($this->contractorcode!=0)
        {
            if ($cond=="")
                $cond="mmm.mcode=".$this->contractorcode;
            else
                $cond=$cond." and mmm.mcode=".$this->contractorcode;
        }

        if ($this->billcategorycode!=0)
        {
            if ($cond=="")
                $cond="billcategorycode=".$this->billcategorycode;
            else
                $cond=$cond." and billcategorycode=".$this->billcategorycode;
        }
      
        $group_query_1 =" 
        
        select mcode,v.vehiclecode,s.subcontractornameuni mname,v.vehiclenumber,mseason,payeecategorycode, billcategorycode,mperiod,count(slipcount)slipcount,sum(netweight)netweight,
        sum(mgross)mgross, sum(mded)mded, sum(mnet)mnet, sum(mdeposit)mdeposit
        ,sum(mdiesel)mdiesel, sum(madvance)madvance, sum(mtyrerent)mtyrerent, sum(mstoreitem)mstoreitem, sum(otherdeduction)otherdeduction 
        from
        (
         select h.seasoncode mseason,h.subcontractorcode mcode,case when h.servicehrtrcategorycode=1 then h.vehiclecode else null end vehiclecode
         ,b.billperiodnumber mperiod,h.netgrossamount mgross,h.grossdeduction mded,h.netamount mnet
         ,0 mdeposit 
         ,0 mdiesel
         ,0 madvance
         ,0 mtyrerent
         ,0 mstoreitem
         ,0 otherdeduction
         ,0 slipcount,0 netweight,b.payeecategorycode, b.billcategorycode       
         from htbillheader h,BILLPERIODHEADER b
         where h.billperiodtransnumber=b.billperiodtransnumber
        union all

        select h.seasoncode mseason,h.subcontractorcode mcode,
        case when h.servicehrtrcategorycode=1 then h.vehiclecode else null end vehiclecode
        ,b.billperiodnumber mperiod,0 mgross,0 mded,0 mnet
        ,case when d.deductioncode=2001 then d.deductionamount else 0 end mdeposit 
        ,case when d.deductioncode=2002 then d.deductionamount else 0 end mdiesel
        ,case when d.deductioncode=2003 then d.deductionamount else 0 end madvance
        ,case when d.deductioncode=2004 then d.deductionamount else 0 end mtyrerent
        ,case when d.deductioncode=2005 then d.deductionamount else 0 end mstoreitem
        ,case when d.deductioncode not in (2001,2002,2003,2004,2005) then d.deductionamount else 0 end otherdeduction
        ,0 slipcount,0 netweight,b.payeecategorycode, b.billcategorycode       
        from htbillheader h ,HTBILLDEDUCTIONDETAIL d ,BILLPERIODHEADER b
        where h.billperiodtransnumber=b.billperiodtransnumber
        and h.transactionnumber=d.billtransactionnumber 
        union all

        select seasoncode mseason,subcontractore mcode,vehiclecode,billperiodnumber mperiod,0 mgross,0 mded,0 mnet
        ,0 mdeposit,0 mdiesel,0 madvance ,0 mtyrerent,0 mstoreitem, 0 otherdeduction ,slipcount,netweight,payeecategorycode, billcategorycode
        from
        (
        select f.seasoncode,billperiodnumber,f.trsubcontractorcode subcontractore,f.vehiclecode,payeecategorycode, billcategorycode
        ,f.fieldslipnumber slipcount,w.netweight netweight from weightslip w,fieldslip f,
        (select distinct(b.fromdate)fromdate,b.todate,b.billperiodnumber,b.payeecategorycode, b.billcategorycode
         from billperiodheader b )m
         where w.seasoncode=f.seasoncode and f.vehiclecategorycode in(1,2) 
         and w.fieldslipnumber=f.fieldslipnumber and w.netweight>0
         and w.weighmentdate>=fromdate and w.weighmentdate<=todate    
         union all
         select f.seasoncode,billperiodnumber,f.hrtrsubcontractorcode subcontractore,null vehiclecode,payeecategorycode, billcategorycode
        ,f.fieldslipnumber slipcount,w.netweight netweight from weightslip w,fieldslip f,
        (select distinct(b.fromdate)fromdate,b.todate,b.billperiodnumber,b.payeecategorycode, b.billcategorycode
         from billperiodheader b )m
         where w.seasoncode=f.seasoncode and f.vehiclecategorycode in(3,4) 
         and w.fieldslipnumber=f.fieldslipnumber and w.netweight>0
         and w.weighmentdate>=fromdate and w.weighmentdate<=todate
         union all
         select f.seasoncode,billperiodnumber,f.hrsubcontractorcode subcontractore,null vehiclecode,payeecategorycode, billcategorycode
        ,f.fieldslipnumber slipcount,w.netweight netweight from weightslip w,fieldslip f,
        (select distinct(b.fromdate)fromdate,b.todate,b.billperiodnumber,b.payeecategorycode, b.billcategorycode
         from billperiodheader b )m
         where w.seasoncode=f.seasoncode and f.vehiclecategorycode in(1,2) 
         and w.fieldslipnumber=f.fieldslipnumber and w.netweight>0
         and w.weighmentdate>=fromdate and w.weighmentdate<=todate  
         )
         )mmm,subcontractor s,vehicle v
         where {$cond} and mmm.mseason=s.seasoncode and billcategorycode=1 and mmm.mcode=s.subcontractorcode
          and mmm.mseason=v.seasoncode(+)
          and mmm.vehiclecode=v.vehiclecode(+)
          /* and mcode in(400,184,446,67,215)
           and mseason=20212022  and  billcategorycode=1
 */          group by mseason,mcode,s.subcontractornameuni,v.vehiclecode,v.vehiclenumber,mperiod,payeecategorycode, billcategorycode
          order by mcode,vehiclecode,mperiod
        
        ";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        $this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $this->sumgroupsummary($group_row_1,1);
            $this->sumgroupsummary($group_row_1,2);
            $this->sumgroupsummary($group_row_1,0);
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
        
    }
    
    function groupheader_1(&$group_row_1)
    {
        if ($this->isnewpage(25))
        {
            $this->newrow();
            $this->hline(10,285,$this->liney,'D');  
            $this->newpage(True);
        }
       
        
        $this->resetgroupsummary(1);
        $this->setfieldwidth(150,10);  
        $this->vline($this->liney-1,$this->liney+7,$this->x);  
        $this->textbox($group_row_1['MCODE'].' - '.$group_row_1['MNAME'],$this->w,$this->x,'S','L',1,'siddhanta',13,'','','','B'); 
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w+125);
        if ($this->isnewpage(25))
        {
            $this->newrow();
            $this->hline(10,285,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(10,285,$this->liney-1,'C'); 
        }    
              
    }

    function groupheader_2(&$group_row_1)
    {
        if ($this->isnewpage(25))
        {
            $this->newrow();
            $this->hline(10,285,$this->liney,'D');  
            $this->newpage(True);
        }
      
        $this->resetgroupsummary(2);
        $this->setfieldwidth(150,10);  
        $this->vline($this->liney-1,$this->liney+7,$this->x);  
        $this->textbox($group_row_1['VEHICLECODE'].' - '.$group_row_1['VEHICLENUMBER'],$this->w,$this->x,'S','L',1,'siddhanta',13,'','','','B'); 
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w+125);
        if ($this->isnewpage(105))
        {
            $this->newrow();
            $this->hline(10,285,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(10,285,$this->liney-1,'C'); 
        }   
    }

    function groupheader_3(&$group_row_1)
    {
    }
    function groupheader_4(&$group_row_1)
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
        if ($this->isnewpage(50))
        {
            $this->newrow();
            $this->hline(10,285,$this->liney,'D');  
            $this->newpage(True);
        }
              
        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($group_row_1['MPERIOD'],$this->w,$this->x,'S','C',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
      

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['SLIPCOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(25);
        $this->textbox($this->numformat($group_row_1['NETWEIGHT'],3),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

       
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['MGROSS'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['MDED'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['MNET'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['MADVANCE'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['MDIESEL'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);       
          
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['MDEPOSIT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['MTYRERENT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['MSTOREITEM'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);       
         
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['OTHERDEDUCTION'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);       
        
      
        if ($this->isnewpage(100))
        {
            $this->newrow();
            $this->hline(10,285,$this->liney-2,'D'); 
            $this->newpage(True);
         
        }   
        else
        {
            $this->newrow();
           $this->hline(10,285,$this->liney-2,'D'); 
        }
    }
    
    function groupfooter_1(&$group_row_1)
    { 
        if ($this->isnewpage(25))
        {
            $this->newrow();
            $this->hline(10,285,$this->liney,'D');  
            $this->newpage(True);
        }
       
        $this->setfieldwidth(15,10);
        
        $this->vline($this->liney-1,$this->liney+6,$this->x); 
        $this->textbox('एकूण ',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
       
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15); 
        $this->textbox($this->numformat($this->showgroupsummary(1,'SLIPCOUNT'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox(number_format($this->showgroupsummary(1,'NETWEIGHT'),3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(1,'MGROSS'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(1,'MDED'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(1,'MNET'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(1,'MADVANCE'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(1,'MDIESEL'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(1,'MDEPOSIT'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(1,'MTYRERENT'),0,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox($this->numformat($this->showgroupsummary(1,'MSTOREITEM'),1,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(1,'OTHERDEDUCTION'),1,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

       // $this->newrow();
       // $this->hline(10,285,$this->liney-1,'C'); 
       
        if ($this->isnewpage(50))
        {
            $this->newrow();
            $this->hline(10,285,$this->liney-2,'D'); 
            $this->newpage(True);
         
        }   
        else
        {
            $this->newrow();
           $this->hline(10,285,$this->liney-2,'C'); 
        }
      
    }
    function groupfooter_2(&$group_row_1)
    {  
        if ($this->isnewpage(25))
        {
            $this->newrow();
            $this->hline(10,285,$this->liney,'D');  
            $this->newpage(True);
        }
        
        $this->setfieldwidth(15,10);
        
        $this->vline($this->liney-1,$this->liney+6,$this->x); 
        $this->textbox('एकूण ',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
       
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15); 
        $this->textbox($this->numformat($this->showgroupsummary(2,'SLIPCOUNT'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox(number_format($this->showgroupsummary(2,'NETWEIGHT'),3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(2,'MGROSS'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(2,'MDED'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(2,'MNET'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(2,'MADVANCE'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(2,'MDIESEL'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(2,'MDEPOSIT'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(2,'MTYRERENT'),0,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox($this->numformat($this->showgroupsummary(2,'MSTOREITEM'),1,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(2,'OTHERDEDUCTION'),1,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->newrow();
        $this->hline(10,285,$this->liney-1,'C'); 
       
        if ($this->isnewpage(100))
        {
            $this->newrow();
            $this->hline(10,285,$this->liney-2,'D'); 
            $this->newpage(True);
         
        }   
        else
        {
           // $this->newrow();
           $this->hline(10,285,$this->liney-2,'D'); 
        }
    }

    function groupfooter_3(&$group_row_1)
    {     
    }
    function groupfooter_4(&$group_row_1)
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
       /*  $this->setfieldwidth(25,10);
        
        $this->vline($this->liney-1,$this->liney+6,$this->x); 
        $this->textbox('एकंदर एकूण ',$this->w,$this->x,'S','R',1,'siddhanta',10,'','','','B');
       
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(0,'SLIPCOUNT'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox(number_format($this->showgroupsummary(0,'NETWEIGHT'),3,'.',''),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11,'','','','');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(0,'MGROSS'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(0,'MDED'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(0,'MNET'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(0,'MADVANCE'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(0,'MDIESEL'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(0,'MDEPOSIT'),0),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(0,'MTYRERENT'),0,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25); 
        $this->textbox($this->numformat($this->showgroupsummary(0,'MSTOREITEM'),1,'.',''),$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);


        $this->newrow();
        $this->hline(10,285,$this->liney-1,'C');  */ 
    }

}    
?>
