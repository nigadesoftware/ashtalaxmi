<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a5_l.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class salaryslip extends reportbox
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
	    $this->pdf = new MYPDF('L', PDF_UNIT, 'A5', true, 'UTF-8', false);
        
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
       
       /*  $this->newrow(10);
        $this->textbox('पगार स्लिप माहे-'.$_SESSION['yearperiodcode'],170,10,'S','C',1,'siddhanta',13);
      
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow(10); */
        
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
        $this->totalgroupcount=1;
        $cond="1=1";
        $cond1=$cond." and p.financialyearcode=".$_SESSION['yearperiodcode']
        ." and monthcode = ".$this->monthcode
        ." and employeecategorycode=".$this->employeecategorycode
        ." and paymentcategorycode=".$this->paymentcategorycode;  

        if ($this->employeecode!=0 and $this->employeecode!='')
        {
            if ($cond=="")
                $cond="h.employeecode=".$this->employeecode;
            else
                $cond=$cond." and h.employeecode=".$this->employeecode;
        }
      
        $group_query_1 ="
        select 
        h.transactionnumber
        ,h.employeecode
        ,e.employeenameuni
        ,c.employeecategorynameuni
        ,e.uan
        ,e.bankbranchname
        ,e.accountnumber
        ,sd.presentdays  
        ,s.sectionnameuni
        ,dp.departmentnameuni
        ,h.grosssalary
        ,h.grossdeduction
        ,h.netsalary
       from SALARYTRANSACTIONHEADER h,EMPLOYEEMASTER e
       ,salaryperiod p,employeecategory c,salarydays sd
       ,EMPLOYEETRANSACTION et,sectionmaster s,department dp
        where {$cond} 
        and  h.employeecode=e.employeecode and h.salaryperiodtransactionnumber=p.transactionnumber
       and p.employeecategorycode=c.employeecategorycode
       and h.salaryperiodtransactionnumber=sd.salaryperiodtransactionnumber(+)
       and h.employeecode=sd.employeecode(+)
       and h.employeecode=et.employeecode
       and et.sectioncode=s.sectioncode
       and s.departmentcode=dp.departmentcode
       order by h.transactionnumber
       ";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        //$this->newpage(true);
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
      $this->newpage(True);
      $this->newrow(10);
      $this->textbox('पगार स्लिप माहे - '.$this->monthname." ".$_SESSION['yearperiodcode'],170,10,'S','C',1,'siddhanta',13);
    
      $this->hline(10,200,$this->liney+6,'C');
      $this->newrow(10);
      $this->hline(10,200,$this->liney-4,'C');
      $this->setfieldwidth(190,10);
      $this->vline($this->liney-4,$this->liney+7,$this->x);
      $this->textbox('कोड व नाव: '.$group_row_1['EMPLOYEECODE'].' - '.$group_row_1['EMPLOYEENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','B'); 
      $this->newrow();
      $this->vline($this->liney-4,$this->liney+7,$this->x);    
      $this->textbox('प्रकार     : '.$group_row_1['EMPLOYEECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',12); 
      $this->vline($this->liney-11,$this->liney+21,$this->x+$this->W+95);  
     

      $this->newrow();
      $this->setfieldwidth(190,10);
      $this->vline($this->liney-4,$this->liney+7,$this->x);    
      $this->textbox('सेक्शन    : '.$group_row_1['SECTIONNAMEUNI'].'    विभाग :- '.$group_row_1['DEPARTMENTNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',12); 
      $this->newrow();
      $this->vline($this->liney-4,$this->liney+7,$this->x);    
      $this->textbox('UAN    : '.$group_row_1['UAN'],$this->w,$this->x,'S','L',1,'siddhanta',12); 
      $this->vline($this->liney-25,$this->liney+7,$this->x+$this->W+190);  
      $this->newrow(-21);
      $this->setfieldwidth(60,110);
      $this->textbox('हजेरी दिवस  : '.$group_row_1['PRESENTDAYS'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','',''); 
      $this->newrow();
      $this->textbox('बँकेचे नाव   :'.$group_row_1['BANKBRANCHNAME'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','',''); 
      $this->newrow();
      $this->textbox('बँकेचे खाते नं:  '.$group_row_1['ACCOUNTNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',8,'','','',''); 
      $this->newrow(7);
      $this->hline(10,200,$this->liney+7,'C');
      
      if ($this->isnewpage(15))
      {
          $this->newrow();
          $this->newpage(True);
      }   
      else
      {
          $this->newrow();
      }
      
      $this->setfieldwidth(95,10);
      $this->vline($this->liney,$this->liney+7,$this->x);
      $this->textbox('मिळकत',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','B'); 
      $this->vline($this->liney,$this->liney+7,$this->x+$this->w);
      $this->setfieldwidth(95);
      $this->vline($this->liney,$this->liney+7,$this->x+$this->w);
      $this->textbox('कपाती',$this->w,$this->x,'S','C',1,'siddhanta',10,'','','','B'); 
      $this->vline($this->liney,$this->liney+7,$this->x+$this->w);
      //$this->newrow();
      $this->hline(10,200,$this->liney+7,'C');
      
      $query = " 
      select 
      ea.allowancecategorynameuni
      ,sa.allowanceamount
      from SALARYTRANSACTIONHEADER h,salaryperiod p     
      ,salarytransactionallowancedet sa,allowancecategory ea
      where h.transactionnumber=sa.transactionnumber
      and sa.allowancecategorycode=ea.allowancecategorycode(+)
      and h.transactionnumber=".$group_row_1['TRANSACTIONNUMBER']
      ." order by ea.allowancecategorycode,sa.allowancecategorycode";
   
       $result = oci_parse($this->connection, $query);
       $r = oci_execute($result);
       $this->setfieldwidth(15,10);
       
       $this->newrow();
       $y1 = $this->liney;
       $i=0;
       while ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
       {
         if ($i%2==0)
          $this->setfieldwidth(25,10);
          else
          $this->setfieldwidth(25); 
         //$this->vline($this->liney-1,$this->liney+10,$this->x);
         $this->textbox($row['ALLOWANCECATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11); 
         $this->setfieldwidth(20);
         $this->textbox($row['ALLOWANCEAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11); 
         
         
          if ($i%2==1)
          {
            $this->vline($this->liney-1,$this->liney+5,10);
            $this->vline($this->liney-1,$this->liney+5,$this->x+$this->w+5);
            $this->vline($this->liney-1,$this->liney+5,200);    
            $this->newrow(5);
          }
          else
          {
            $this->vline($this->liney,$this->liney+5,$this->x+$this->w,'D');
          }
          $i++;     
       }
        $this->vline($this->liney-1,$this->liney+5,10);
        $this->vline($this->liney-1,$this->liney+5,105);
        $this->vline($this->liney-1,$this->liney+5,200);    
        $this->liney = $y1;
        $query = " 
        select 
        ea.deductionnameuni
        ,sa.deductionamount
        from SALARYTRANSACTIONHEADER h,salaryperiod p     
        ,salarytransactiondeductiondet sa,deduction ea
        where h.transactionnumber=sa.transactionnumber
        and sa.deductioncode=ea.deductioncode(+)
        and h.transactionnumber=".$group_row_1['TRANSACTIONNUMBER'];
        
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $this->setfieldwidth(15,10);
        $i=0;
        while ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
            if ($i%2==0)
                $this->setfieldwidth(25,110);
            else
                $this->setfieldwidth(25);
            //$this->vline($this->liney-1,$this->liney+12,$this->x+$this->w-47);
            $this->textbox($row['DEDUCTIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11); 
        
            $this->setfieldwidth(20);
            $this->textbox($row['DEDUCTIONAMOUNT'],$this->w,$this->x,'S','R',1,'SakalMarathiNormal922',11); 
            
            if ($i%2==1)
            {
                $this->vline($this->liney-1,$this->liney+5,$this->x+$this->w);
                $this->newrow(5);
            }
            else
            {
              $this->vline($this->liney,$this->liney+5,$this->x+$this->w,'D');
            }
  
            $i++;     
        }
        $i--; 
        if ($i%2==0)
        {
            $this->vline($this->liney-1,$this->liney+5,200);
            $this->newrow(5);
        } 
        else
        {
            $this->newrow(5);
            $this->vline($this->liney,$this->liney+5,$this->x+$this->w,'D');
        }  
        $this->vline($this->liney-1,$this->liney+5,200);
        if ($y1<$this->liney)
            $y2=  $this->liney;
        else
            $y2=$y1;

        $y=$y1;
        while ($y<=$y2)
        {
            $this->vline($y-1,$y+5,10);
            $this->vline($y-1,$y+5,105);
            $this->vline($y-1,$y+5,200);
            $y=$y+5;
            $this->vline($y,$y+5,55,'D');
            $this->vline($y,$y+5,155,'D');
        }  
        $this->liney=$y;  
        //$this->newrow();
        $this->hline(10,200,$this->liney+5,'C');
        $this->newrow(7);
        $this->vline($this->liney-2,$this->liney+7,10);
        //$this->vline($this->liney-1,$this->liney+5,105);
        $this->vline($this->liney-2,$this->liney+7,200);
        $this->setfieldwidth(25,25);
        $this->textbox('एकूण मिळकत',$this->w,$this->x,'S','R',1,'siddhanta',11); 
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['GROSSSALARY'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11); 
        $this->setfieldwidth(25);
        $this->textbox('एकूण कपात',$this->w,$this->x,'S','R',1,'siddhanta',11); 
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['GROSSDEDUCTION'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11); 
        $this->setfieldwidth(25);
        $this->textbox('निव्वळ रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11); 
        $this->setfieldwidth(25);
        $this->textbox($group_row_1['NETSALARY'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11); 
        $this->hline(10,200,$this->liney+7,'C');
        $this->newrow(7);
        $this->vline($this->liney-2,$this->liney+7,10);
        //$this->vline($this->liney-1,$this->liney+5,105);
        $this->vline($this->liney-2,$this->liney+7,200);
        $this->setfieldwidth(150,25);
        $this->textbox('निव्वळ रक्कम अक्षरी :'.NumberToWords($group_row_1['NETSALARY'],1),$this->w,$this->x,'S','L',1,'siddhanta',11); 
        $this->hline(10,200,$this->liney+7,'C');
        $this->newrow(15);
        $this->setfieldwidth(25,50);
        $this->textbox('लेबर ऑफिसर',$this->w,$this->x,'S','R',1,'siddhanta',11); 
        $this->setfieldwidth(100);
        $this->textbox('जनरल मॅनेजर',$this->w,$this->x,'S','R',1,'siddhanta',11); 
        
        
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
     
    }
    
    function groupfooter_1(&$group_row_1)
    { 
       $query = " 
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
        
        }
       
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
       
    }

}    
?>
