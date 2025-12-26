<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_P.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class farmerpaysheetsummarymm extends reportbox
{	
    Public $fromdate;
    Public $todate;
    Public $suppliercode;
    Public $employeecode;
    public $summery;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueType', '', 32);
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
        $this->pdf->Output('SLRG_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 10;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        $this->newrow(10);
        $this->setfieldwidth(180,10);
        $this->textbox('गटवार ऊस पेमेंट रजिस्टर समरी',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B');
        $this->newrow(7);
        if ($this->fromdate!='' and $this->todate!='')
        {
            $this->textbox('हंगाम: '.$_SESSION['yearperiodcode'].' दिनांक '.DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y').' पासून ते दिनांक '.DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y').' पर्यंत',180,10,'S','C',1,'siddhanta',12);
        }
        else
        {
            $this->textbox('हंगाम: '.$_SESSION['yearperiodcode'],180,10,'S','C',1,'siddhanta',12);
        }
        $this->hline(10,194,$this->liney+6,'C');
        $this->newrow();       
        
        $this->setfieldwidth(25,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('अनु क्र',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(40);
        $this->textbox('गट',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(30);
        $this->textbox('टनेज',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(30);
        $this->textbox('रक्कम',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(30);
        $this->textbox('कपात रक्कम',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(30);
        $this->textbox('देय रक्कम',$this->w,$this->x,'S','C',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        //$this->newrow();
        $this->hline(10,194,$this->liney+6,'C');
        $this->newrow();
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
        $this->summery['NETCANETONNAGE']=0;
        $this->summery['GROSSAMOUNT']=0;
        $this->summery['GROSSDEDUCTION']=0;
        $this->summery['NETAMOUNT']=0;

        $this->totalgroupcount=0;
        $this->amount = 0;
        $cond='1=1';
        if ($this->fromdate != '' and $this->todate != '')
        {
         /*    $fdt=DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $tdt=DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
         */ 
        $frdt=DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
        $todt=DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
        $cond= $cond."and h.fromdate>='".$frdt."' and h.todate<='".$todt."'";
        }
            
        $group_query_1 ="select r.circlecode,r.circlenameuni
        ,row_number()over(order by r.circlecode)Sr_no
        ,sum(t.netcanetonnage) as netcanetonnage
        ,sum(t.grossamount) as grossamount
        ,nvl(sum(t.grossdeduction),0) as grossdeduction
        ,sum(t.netamount) as netamount
        from farmerbillheader t,farmer f, village v
        ,billperiodheader h,farmercategory c,circle r
        where  {$cond} and
        t.farmercode=f.farmercode
        and f.villagecode=v.villagecode
        and t.billperiodtransnumber=h.billperiodtransnumber
        and f.farmercategorycode=c.farmercategorycode
        and v.circlecode=r.circlecode  
        and h.billperiodnumber not in(10 ,11)   
        having nvl(sum(t.netcanetonnage),0)>0
        group by r.circlecode,r.circlenameuni
        order by r.circlecode"; 
        
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
        $this->setfieldwidth(25,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($group_row_1['SR_NO'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->textbox($group_row_1['CIRCLENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox($group_row_1['NETCANETONNAGE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox($group_row_1['GROSSAMOUNT'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);


        $this->setfieldwidth(30);
        $this->textbox($group_row_1['GROSSDEDUCTION'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox($group_row_1['NETAMOUNT'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->summery['NETCANETONNAGE']=$this->summery['NETCANETONNAGE']+$group_row_1['NETCANETONNAGE'];
        $this->summery['GROSSAMOUNT']=$this->summery['GROSSAMOUNT']+$group_row_1['GROSSAMOUNT'];
        $this->summery['GROSSDEDUCTION']=$this->summery['GROSSDEDUCTION']+$group_row_1['GROSSDEDUCTION'];
        $this->summery['NETAMOUNT']= $this->summery['NETAMOUNT']+$group_row_1['NETAMOUNT'];
       

        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-1,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-1,'C');  
        }
       
       
    }
   
    function groupfooter_1(&$group_row_1)
    { 
        

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
        $this->setfieldwidth(25,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
      //  $this->textbox($group_row_1['SR_NO'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
       // $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(40);
        $this->textbox('Total:',$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox($this->summery['NETCANETONNAGE'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox($this->summery['GROSSAMOUNT'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);


        $this->setfieldwidth(30);
        $this->textbox($this->summery['GROSSDEDUCTION'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->textbox($this->summery['NETAMOUNT'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->hline(10,195,$this->liney-1,'C'); 
    }
    function export()
    {
        $cond='1=1';
        if ($this->fromdate != '' and $this->todate != '')
        {
          
            $fdt=DateTime::createFromFormat('Y-m-d',$this->fromdate)->format('d-M-Y');
            $tdt=DateTime::createFromFormat('Y-m-d',$this->todate)->format('d-M-Y');
            $cond= $cond.'and purchesorderdate>='.$fdt.' and purchesorderdate<='.$tdt;
        }

            $group_query_1 ="  select t.departmentnameeng,c.purchaseordercategorynameeng
            ,h.purchesordernumber
            ,to_char(h.purchesorderdate,'dd/mm/yyyy') purchesorderdate
            ,s.suppliernameeng
            ,sum(d.amount)amount
            from purchesorderheader h,supplier s
           ,purchaseordercategory c,purchesorderitemdetail d
           ,nst_nasaka_payroll.department t
           where 
            h.suppliercode=s.suppliercode and purchesorderdate>='".$fdt."'
           and purchesorderdate<='".$tdt."'
           and h.purchaseordercategorycode=c.purchaseordercategorycode
           and h.transactionnumber=d.transactionnumber
           and h.departmentcode=t.departmentcode(+)
           group by t.departmentnameeng,c.purchaseordercategorynameeng,h.purchesordernumber
           ,h.purchesorderdate,s.suppliernameeng
           order by t.departmentnameeng,h.purchesordernumber,h.purchesorderdate";
           $result = oci_parse($this->connection, $group_query_1);
           $r = oci_execute($result);
           $response = array();
           $filename='PurchaseOrderList.csv';
           //$fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');
           $fp1=fopen('php://memory', 'w');
           fputcsv($fp1, array('#','Department','PO Order Number','PO Order Date','Suppiler Name','Amount','#'));
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
               // $acno="'".$row['ACCOUNTNUMBER'];
                fputcsv($fp1, array('#',$row['DEPARTMENTNAMEENG'],$row['PURCHESORDERNUMBER'],$row['PURCHESORDERDATE'],$row['SUPPLIERNAMEENG'],$row['AMOUNT'],'#'), $delimiter = ',', $enclosure = '"');
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
