<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class paysheet extends reportbox
{	
    public $financialyearcode;
    public $monthcode;
    public $monthname;
    public $paymentcategorycode;
    public $employeecategorycode;
    public $employeecode;

  
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
        $this->textbox('पगार पत्रक सेक्शनवाईज समरी माहे-'.$this->monthname.' '.$_SESSION['yearperiodcode'],290,10,'S','C',1,'siddhanta',13);
      
       // $this->hline(10,200,$this->liney+6,'C');
        $this->newrow(10); 
        
       // $this->newrow(7);


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
        $this->totalgroupcount=2;
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

        if ($this->employeecode!=0)
        {
            if ($cond=="")
                $cond="h.employeecode=".$this->employeecode;
            else
                $cond=$cond." and h.employeecode=".$this->employeecode;
        }
        if ($this->sectioncode!=0)
        {
            if ($cond=="")
                $cond="et.sectioncode=".$this->sectioncode;
            else
                $cond=$cond." and et.sectioncode=".$this->sectioncode;
        }
        
        $group_1 ="
        select s.departmentcode
       ,et.sectioncode
       ,d.departmentnameuni
       ,s.sectionnameuni 
       from salarytransactionheader h
       ,salaryperiod p,employeetransaction et,sectionmaster s,department d
       where {$cond}
       and h.salaryperiodtransactionnumber=p.transactionnumber
       and h.employeecode=et.employeecode
       and et.sectioncode=s.sectioncode
       and s.departmentcode=d.departmentcode
        order by et.sectioncode
       ";
        
        $group_result_1 = oci_parse($this->connection, $group_1);
        $r = oci_execute($group_result_1);
        $i=0;
        $this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
           // $this->hline(10,200,$this->liney,'D'); 
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
        
    }
    
    function groupheader_1(&$group_row_1)
    { 
      $this->setfieldwidth(290,10);
      $this->textbox('विभाग कोड : '.$group_row_1['DEPARTMENTCODE'].'    नाव  :'.$group_row_1['DEPARTMENTNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B');  
      $this->hline(10,290,$this->liney+6,'C');
     
      $this->setfieldwidth(70,100);
      $this->textbox('मिळकत ',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B'); 
      $this->setfieldwidth(65);
     $this->textbox('कपात ',$this->w,$this->x,'S','C',1,'siddhanta',12,'','','','B');  
      $this->setfieldwidth(65);
      $this->textbox('एकूण ',$this->w,$this->x,'S','C',1,'siddhanta',12,'','','','B');  
      
    }

    function groupheader_2(&$group_row_1)
    {
        $this->newrow();
        $this->setfieldwidth(70,10);
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox('खाते कोड : '.$group_row_1['SECTIONCODE'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B'); 
        $this->newrow();
        $this->textbox('खाते नाव:-'.$group_row_1['SECTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B'); 
       // $this->vline($this->liney-2,$this->liney+7,$this->x+$this->W+90);
        $this->newrow();
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
     
    }
    
    function groupfooter_1(&$group_row_1)
    { 
        $this->newrow(4);
        /* $query = " 
        select 
        h.grosssalary,h.grossdeduction,h.netsalary
        from SALARYTRANSACTIONHEADER h,salaryperiod p       
        where --{$cond}
        h.salaryperiodtransactionnumber=p.transactionnumber
        and p.financialyearcode=20222023
        and p.monthcode=4 and p.employeecategorycode=1
        and h.employeecode='".$group_row_1['EMPLOYEECODE']."'
        ";
    
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $this->setfieldwidth(15,10);
       
        while ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
          $this->setfieldwidth(47,10);
          $this->vline($this->liney-2,$this->liney+7,$this->x);
          $this->textbox('एकूण पगार',$this->w,$this->x,'S','R',1,'siddhanta',11); 
          //$this->vline($this->liney-1,$this->liney+12,$this->x+$this->w);
          $this->setfieldwidth(47);
          $this->textbox($row['GROSSSALARY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11); 
          $this->setfieldwidth(41);
          $this->textbox('एकूण कपात',$this->w,$this->x,'S','R',1,'siddhanta',11); 
          $this->setfieldwidth(49);
          $this->textbox($row['GROSSDEDUCTION'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11); 
          $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w+6);
          $this->newrow();
          $this->setfieldwidth(90,10);
          $this->vline($this->liney-2,$this->liney+7,$this->x);
          $this->textbox('निव्वळ अदा :-'.$row['NETSALARY'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
          $this->setfieldwidth(90,70);
          $this->textbox('अक्षरी रु.:-'.getStringOfAmount($row['NETSALARY']).' फक्त',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
          $this->vline($this->liney-2,$this->liney+7,$this->x+$this->w+40);
 
          $this->hline(10,200,$this->liney+7,'C');
          $this->newrow();
          
          $this->newrow();
          
          if ($this->isnewpage(15))
         {
           $this->newrow();
           $this->newpage(True);
          }   
          else
          {
              $this->newrow();
              $this->newpage(True);
         }
         
         } */
       
    }
    function groupfooter_2(&$group_row_1)
    {  
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

        if ($this->employeecode!=0)
        {
            if ($cond=="")
                $cond="h.employeecode=".$this->employeecode;
            else
                $cond=$cond." and h.employeecode=".$this->employeecode;
        }
        if ($this->sectioncode!=0)
        {
            if ($cond=="")
                $cond="et.sectioncode=".$this->sectioncode;
            else
                $cond=$cond." and et.sectioncode=".$this->sectioncode;
        }
                   
       // allowance
       $this->newrow(-15);    
       // $this->newrow();                  
         $query = " 
         select 
         ea.allowancecategorynameuni
         ,sum(sa.allowanceamount)allowanceamount
         from SALARYTRANSACTIONHEADER h,salaryperiod p
         ,employeetransaction et     
         ,salarytransactionallowancedet sa,allowancecategory ea
         where {$cond} and
          h.transactionnumber=sa.transactionnumber
         and sa.allowancecategorycode=ea.allowancecategorycode
         and h.employeecode=et.employeecode
          and et.sectioncode='".$group_row_1['SECTIONCODE']."'
         group by ea.allowancecategorynameuni
         ";
     
         $result = oci_parse($this->connection, $query);
         $r = oci_execute($result);
         while ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
         {
           $this->setfieldwidth(25,85);
           $this->vline($this->liney,$this->liney+10,$this->x);
           $this->textbox($row['ALLOWANCECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11); 
           $this->setfieldwidth(25);
           $this->textbox($row['ALLOWANCEAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11); 
           $this->vline($this->liney,$this->liney+12,$this->x+$this->w+20);
           $this->newrow();
         }
        
            ////total deduction
          $this->newrow(-20);
         $query = " 
         select 
         ea.deductionnameuni
          ,sum(sa.deductionamount)deductionamount
          from SALARYTRANSACTIONHEADER h,salaryperiod p  
          ,employeetransaction et     
          ,salarytransactiondeductiondet sa,deduction ea
          where h.transactionnumber=sa.transactionnumber
          and h.employeecode=et.employeecode
          and sa.deductioncode=ea.deductioncode
         and p.financialyearcode=20222023
         and p.monthcode=4 and p.employeecategorycode=1
         and et.sectioncode='".$group_row_1['SECTIONCODE']."'
         group by ea.deductionnameuni
          ";
      
          $result = oci_parse($this->connection, $query);
          $r = oci_execute($result);
         
          while ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
          {
            $this->newrow();           
            $this->setfieldwidth(25,180);
            $this->textbox($row['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11); 
            $this->setfieldwidth(25);
            $this->textbox($row['DEDUCTIONAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11); 
            $this->vline($this->liney-1,$this->liney+16,$this->x+$this->w+10);
          }
          
          ////total amount
           $this->newrow(-10);
           $this->newrow(2);
         $query = " 
        select 
        sum(h.grosssalary)grosssalary
        ,sum(h.grossdeduction)grossdeduction
        ,sum(h.netsalary)netsalary
        from SALARYTRANSACTIONHEADER h,salaryperiod p   
        ,employeetransaction et         
        where --{$cond}
        h.salaryperiodtransactionnumber=p.transactionnumber
        and h.employeecode=et.employeecode
        and p.financialyearcode=20222023
        and p.monthcode=4 and p.employeecategorycode=1
        and et.sectioncode='".$group_row_1['SECTIONCODE']."'
       
        ";
    
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
       
        while ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
          $this->setfieldwidth(20,240);
          $this->textbox('एकूण पगार',$this->w,$this->x,'S','L',1,'siddhanta',11); 
          $this->setfieldwidth(25);
          $this->textbox($row['GROSSSALARY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11); 
          $this->newrow();
          $this->setfieldwidth(25,240);
          $this->textbox('एकूण कपात',$this->w,$this->x,'S','L',1,'siddhanta',11); 
          $this->setfieldwidth(25);
          $this->textbox($row['GROSSDEDUCTION'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11); 
         
          $this->newrow();
          $this->setfieldwidth(25,240);
          $this->vline($this->liney-2,$this->liney+7,$this->x);
          $this->textbox('निव्वळ अदा ',$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
          $this->setfieldwidth(25);
          $this->textbox($row['NETSALARY'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11); 
          $this->newrow(5);
          $this->hline(10,290,$this->liney+4,'C');
          $this->newrow(2);         
        } 
       // $this->newrow(5);
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
