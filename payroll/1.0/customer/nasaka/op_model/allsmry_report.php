<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a3_l_eng.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class paysheet extends reportbox
{	
   public $summary;
  
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
       
         $this->newrow(10);
        $this->textbox('SectionWise summary-'.$this->monthname.' '.$_SESSION['yearperiodcode'],280,10,'S','C',1,'verdana',13);
                
        $this->newrow(10);
        $this->hline(10,295,$this->liney-1,'C'); 
        $this->setfieldwidth(15,10);   
        $this->vline($this->liney-1,$this->liney+6,$this->x);   
        $this->textbox('Sr.No',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(50);
        $this->textbox('Section Name',$this->w,$this->x,'S','C',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('No. Of Employee',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
       
        $this->setfieldwidth(25);
        $this->textbox('Gross Amt',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('PF',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('PT',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('House Rent',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
        $this->setfieldwidth(25);
        $this->textbox('Light Bill',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Advance',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Other Ded',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox('Net Amount',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        

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
        $this->summary["employee"]=0;
        $this->summary["gross"]=0;
        $this->summary["netsa"]=0;
        $this->summary["ppf"]=0;
        $this->summary["ppt"]=0;
        $this->summary["houserent"]=0;
        $this->summary["elebill"]=0;
        $this->summary["otherded"]=0;
        $this->summary["advance"]=0;
        

        $this->totalgroupcount=1;
        $cond="1=1";
        $cond=" p.financialyearcode=".$_SESSION['yearperiodcode'];  
        if ($this->monthcode!=0)
        {
            if ($cond=="")
                $cond="p.monthcode=".$this->monthcode;
            else
                $cond=$cond." and p.monthcode=".$this->monthcode;
        }
        if ($this->employeecategorycode!=0)
        {
            if ($cond=="")
                $cond="p.employeecategorycode=".$this->employeecategorycode;
            else
                $cond=$cond." and p.employeecategorycode=".$this->employeecategorycode;
        }
        
        $group_query_1 ="
        select
        row_number() over( order by s.sectioncode)sr_no
        ,s.sectioncode,s.sectionnameeng
        ,sum(employee)employee
        ,sum(gross)gross
        ,sum(netsa)netsa
        ,sum(ppf)ppf
        ,sum(ppt)ppt
        ,sum(houserent)houserent
        ,sum(elebill)elebill
        ,sum(otherded)otherded
        ,sum(advance)advance
        from(
        select e.sectioncode,0 employee,0 gross,0 netsa
        ,case when sa.deductioncode=1 then nvl(sa.deductionamount,0) else 0 end ppf     
        ,case when sa.deductioncode=2 then nvl(sa.deductionamount,0) else 0 end ppt
        ,case when sa.deductioncode=5 then nvl(sa.deductionamount,0) else 0 end houserent
        ,case when sa.deductioncode=6 then nvl(sa.deductionamount,0) else 0 end elebill
        ,case when sa.deductioncode=7 then nvl(sa.deductionamount,0) else 0 end otherded
        ,case when sa.deductioncode=8 then nvl(sa.deductionamount,0) else 0 end advance 
        from salarytransactionheader h,salaryperiod p,employeetransaction e
        ,salarytransactiondeductiondet sa,deduction ea
        where {$cond} and
        h.salaryperiodtransactionnumber=p.transactionnumber
        and h.employeecode=e.employeecode
        and p.employeecategorycode=e.employeecategorycode
        and h.transactionnumber=sa.transactionnumber
        and sa.deductioncode=ea.deductioncode  
        union all                
        select mm.sectioncode
        ,case when h.employeecode>0 then 1 else 0 end employee
        ,h.grosssalary gross,h.netsalary netsa
        ,0 ppf,0 ppt,0 houserent,0 elebill,0 otherded ,0 advance 
        from salarytransactionheader h,salaryperiod p
        ,(select tt.employeecode,tt.sectioncode from 
        (select max(t.transactionnumber)trans,t.employeecode from EMPLOYEETRANSACTION t group by t.employeecode
        )m,EMPLOYEETRANSACTION tt where m.trans=tt.transactionnumber)mm
        where {$cond} and
        h.salaryperiodtransactionnumber=p.transactionnumber
        and h.employeecode=mm.employeecode 
        ) m,sectionmaster s
        where m.sectioncode=s.sectioncode
        group by s.sectioncode,s.sectionnameeng
        order by s.sectioncode
         
       ";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        $this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
             $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
        
    }
    
    function groupheader_1(&$group_row_1)
    { 
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
        $this->hline(10,295,$this->liney-1,'C'); 
        $this->setfieldwidth(15,10);       
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox($group_row_1['SR_NO'],$this->w,$this->x,'S','C',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(50);
        $this->textbox($group_row_1['SECTIONNAMEENG'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['EMPLOYEE'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
       
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['GROSS'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($group_row_1['PPF'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
       
        $this->setfieldwidth(20);
        $this->textbox($group_row_1['PPT'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['HOUSERENT'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
       
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['ELEBILL'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($group_row_1['OTHERDED'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
       
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['ADVANCE'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox($group_row_1['NETSA'],$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
       
       
        $this->summary["employee"]+=$group_row_1['EMPLOYEE'];
        $this->summary["gross"]+=$group_row_1['GROSS'];
        $this->summary["netsa"]+=$group_row_1['NETSA'];
        $this->summary["ppf"]+=$group_row_1['PPF'];
        $this->summary["ppt"]+=$group_row_1['PPT'];
        $this->summary["houserent"]+=$group_row_1['HOUSERENT'];
        $this->summary["elebill"]+=$group_row_1['ELEBILL'];
        $this->summary["otherded"]+=$group_row_1['OTHERDED'];
        $this->summary["advance"]+=$group_row_1['ADVANCE'];

        if ($this->isnewpage(20))
        {
            $this->newrow();
            $this->hline(10,295,$this->liney-1,'C'); 
            $this->newpage(True);
         
        }   
        else
        {           
            $this->newrow();
            $this->hline(10,295,$this->liney-1,'C'); 
        }
    }
    
    function groupfooter_1(&$group_row_1)
    { 
    }
    function groupfooter_2(&$group_row_1)
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
       
        $this->setfieldwidth(50,25);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('Total ',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','','','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
     
        $this->setfieldwidth(25);    
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox(number_format($this->summary["employee"],0,'.',''),$this->w,$this->x,'S','R',1,'verdana',11,'','','','','','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(25);    
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox(number_format($this->summary["gross"],0,'.',''),$this->w,$this->x,'S','R',1,'verdana',11,'','','','','','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

       
        $this->setfieldwidth(20);    
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox(number_format($this->summary["ppf"],0,'.',''),$this->w,$this->x,'S','R',1,'verdana',11,'','','','','','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);    
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox(number_format($this->summary["ppt"],0,'.',''),$this->w,$this->x,'S','R',1,'verdana',11,'','','','','','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);    
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox(number_format($this->summary["houserent"],0,'.',''),$this->w,$this->x,'S','R',1,'verdana',11,'','','','','','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);    
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox(number_format($this->summary["elebill"],0,'.',''),$this->w,$this->x,'S','R',1,'verdana',11,'','','','','','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);    
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox(number_format($this->summary["otherded"],0,'.',''),$this->w,$this->x,'S','R',1,'verdana',11,'','','','','','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);    
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox(number_format($this->summary["advance"],0,'.',''),$this->w,$this->x,'S','R',1,'verdana',11,'','','','','','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);    
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox(number_format($this->summary["netsa"],0,'.',''),$this->w,$this->x,'S','R',1,'verdana',11,'','','','','','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);



        $this->hline(25,295,$this->liney+7,'C');  
    }

}    
?>
