<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class vehiclepumpdiesel extends reportbox
{
    public $fromdate;
    public $todate;
    public $summary;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7,$subject='',$pdffilename='',$papersize='A3',$orientation='P')
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
        $this->pdf->SetSubject('Vehicle Diesel');
        $this->pdf->SetKeywords('VEHDIES_000.MR');
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));
       // $title = str_pad(' ', 30).'Rajaramnagar, Tal - Dindori Dist - Nashik';
    	//$this->pdf->SetHeaderData('', 0,str_pad(' ', 21).'Twentyone Sugars Limited' ,$title);
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
        $this->reportfooter();
    }

	function pageheader()
    {
        $this->liney = 15;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->textbox('वाहनवार डिझेल',180,10,'S','C',1,'siddhanta',12);
        $this->newrow(7);
        if ($this->fromdate!='' and $this->fromdate !='')
        {
            $fromdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $todt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $this->textbox('हंगाम : '.$_SESSION['yearperiodcode'].' दिनांक '.$this->fromdate.' पासून दिनांक '.$this->todate.' पर्यंत',180,10,'S','C',1,'siddhanta',11);
        }
        else
        {
            $this->textbox('हंगाम : '.$_SESSION['yearperiodcode'],180,10,'S','C',1,'siddhanta',11);
        }

        $this->newrow(7);
        $this->hline(10,200,$this->liney,'C');
        $this->setfieldwidth(60,10);
        $this->textbox('कंत्राटदार',$this->w,$this->x,'S','L',1,'siddhanta',11); 
        $this->setfieldwidth(50);
        $this->textbox('वाहन',$this->w,$this->x,'S','L',1,'siddhanta',11); 
        $this->setfieldwidth(30);
        $this->textbox('लिटर',$this->w,$this->x,'S','R',1,'siddhanta',11);
        $this->setfieldwidth(30);
        $this->textbox('वि.रक्कम',$this->w,$this->x,'S','R',1,'siddhanta',11); 
        $this->newrow();
        $this->hline(10,200,$this->liney-2,'C');
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        //$this->drawlines($limit);
    }
   

    function group()
    {
        $this->totalgroupcount=0;
        $this->summary['NETWEIGHT']=0;
        $summary['QUANTITY']=0;
        $summary['PURCHASEAMOUNT']=0;
        $summary['SALEAMOUNT']=0;
        $cond = '1=1';
        if ($this->fromdate!='' and $this->todate !='')
        {
            $fromdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $todt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $cond = $cond." and t.transactiondate>='".$fromdt."' and t.transactiondate<='".$todt."'";
        }
        
            $cond = $cond." and t.seasoncode=".$_SESSION['yearperiodcode'];

        $group_query_1 = "select vehiclecode,contractorcategorycode,contractorcode,null subcontractornameuni
        ,contractorcategorynameuni,contractornameuni
        ,vehiclenumber,sum(quantity) quantity
        ,sum(purchaseamount) purchaseamount
        ,sum(saleamount) saleamount
        from (select pumpcode,contractorcategorycode,contractorcode,vehiclecode,null subcontractornameuni
        ,contractorcategorynameuni,contractornameuni
        ,vehiclenumber,transactiondate,quantity,purchaserate
        ,purchaseamount,salerate,saleamount,slipnumber
        from  (select d.contractorcategorycode,d.contractorcode,d.vehiclecode,null subcontractornameuni
        ,c.contractorcategorynameuni,d.contractornameuni
        ,d.vehiclenumber,t.transactiondate,t.quantity,t.purchaserate
        ,t.purchaseamount,t.salerate,t.saleamount,t.pumpcode,t.slipnumber
        from PUMPDIESELTRANSACTION t,ht_contract_data d,contractorcategory c
        where t.seasoncode=d.seasoncode and t.vehiclecode=d.vehiclecode 
        and d.contractorcategorycode=c.contractorcategorycode
        and {$cond} 
        union all
        select d.contractorcategorycode,d.contractorcode,null vehiclecode,d.subcontractornameuni
        ,c.contractorcategorynameuni,d.contractornameuni
        ,d.vehiclenumber,t.transactiondate,t.quantity,t.purchaserate
        ,t.purchaseamount,t.salerate,t.saleamount,t.pumpcode,t.slipnumber
        from PUMPDIESELTRANSACTION t,ht_contract_data d,contractorcategory c
        where t.seasoncode=d.seasoncode and t.subcontractorcode=d.subcontractorcode 
        and d.contractorcategorycode=c.contractorcategorycode
        and {$cond}))t
        group by vehiclecode,contractorcategorycode,contractorcode
        ,contractorcategorynameuni,contractornameuni
        ,vehiclenumber
        order by vehiclecode";
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
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
        ob_flush();
        ob_start();
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
        
        //$dt=DateTime::createFromFormat('d-M-y',$group_row_1['TRANSACTIONDATE'])->format('d/m/y');

        $this->setfieldwidth(60,10);
        $this->textbox($group_row_1['CONTRACTORNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(50);
        $this->textbox($group_row_1['VEHICLECODE'].' - '.$group_row_1['VEHICLENUMBER'],$this->w,$this->x,'N','L',1,'SakalMarathiNormal922',9); 
        $this->setfieldwidth(30);
        $this->textbox($group_row_1['QUANTITY'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11);
        $this->setfieldwidth(30);
        $this->textbox($this->numformat($group_row_1['SALEAMOUNT'],2),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11); 
        $this->newrow(5);
        $this->pumpsummary['QUANTITY']=$this->pumpsummary['QUANTITY']+$group_row_1['QUANTITY'];
        $this->pumpsummary['PURCHASEAMOUNT']=$this->pumpsummary['PURCHASEAMOUNT']+$group_row_1['PURCHASEAMOUNT'];
        $this->pumpsummary['SALEAMOUNT']=$this->pumpsummary['SALEAMOUNT']+$group_row_1['SALEAMOUNT'];
        $this->summary['QUANTITY']=$this->summary['QUANTITY']+$group_row_1['QUANTITY'];
        $this->summary['PURCHASEAMOUNT']=$this->summary['PURCHASEAMOUNT']+$group_row_1['PURCHASEAMOUNT'];
        $this->summary['SALEAMOUNT']=$this->summary['SALEAMOUNT']+$group_row_1['SALEAMOUNT'];
        
        $this->hline(10,200,$this->liney,'D');  
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,200,$this->liney,'D');  
            $this->newpage(True);
        }
        else
        {
            //$this->newrow();
            //$this->hline(10,195,$this->liney-2,'C'); 
        }
    }


    function groupfooter_1(&$group_row_1)
    {     
        

    }

    function groupfooter_2(&$group_row_1)
    {      
        
    }
    function groupfooter_3(&$group_row_2)
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

    function pagefooter($islastpage=false)
    {
    }

    function reportfooter()
    {
        
        if ($this->isnewpage(25))
        {
            //$this->newrow();
            //$this->hline(10,200,$this->liney-2,'C'); 
            $this->newpage(True,True);
        }   
        else
        {
            //$this->newrow();
            //$this->hline(10,200,$this->liney-2,'C'); 
        }

        

        $this->setfieldwidth(90,10);
        $this->textbox('एकूण एकंदर',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->setfieldwidth(30);
        $this->textbox($this->numformat($this->summary['QUANTITY'],0),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11); 
        $this->setfieldwidth(30);
        $this->textbox($this->numformat($this->summary['SALEAMOUNT'],2),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11); 
        $this->hline(10,200,$this->liney,'C'); 
        $this->newrow(5);

        if ($this->isnewpage(25))
        {
            //$this->newrow();
            //$this->hline(10,200,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            //$this->newrow();
            //$this->hline(10,200,$this->liney-2,'C'); 
        }
        $this->newrow(15);
        $this->setfieldwidth(20,60);
        $this->textbox('क्लार्क',$this->w,$this->x,'S','L',1,'siddhanta',12,'','','','');
        $this->setfieldwidth(70);
        $this->textbox('केन अकौंटंट',$this->w,$this->x,'S','R',1,'siddhanta',12,'','','','');
    
    }

    function endreport()
    {

        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output($this->pdffilename.'-'.currentindiandatetimenamestamp().'.pdf', 'I');
    }

    function pumpdieselexport()
    {
        $cond = '1=1';
        if ($this->fromdate!='' and $this->todate !='')
        {
            $fromdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $todt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $cond = $cond." and t.transactiondate>='".$fromdt."' and t.transactiondate<='".$todt."'";
        }
        
        if ($this->pumpcode!='' and $this->pumpcode!=0)
        {
            $cond = $cond." and t.pumpcode=".$this->pumpcode;
        }
            $cond = $cond." and t.seasoncode=".$_SESSION['yearperiodcode'];

        $group_query_1 = "select t.pumpcode,contractorcategorycode,contractorcode,vehiclecode,null subcontractornameuni
        ,contractorcategorynameuni,contractornameuni,contractornameeng
        ,vehiclenumber,transactiondate,quantity,purchaserate
        ,purchaseamount,salerate,saleamount,slipnumber 
        ,pumpnameuni
        from (select pumpcode,contractorcategorycode,contractorcode,vehiclecode,null subcontractornameuni
        ,contractorcategorynameuni,contractornameuni,contractornameeng
        ,vehiclenumber,transactiondate,quantity,purchaserate
        ,purchaseamount,salerate,saleamount,slipnumber
        from  (select d.contractorcategorycode,d.contractorcode,d.vehiclecode,null subcontractornameuni
        ,c.contractorcategorynameuni,d.contractornameuni,d.contractornameeng
        ,d.vehiclenumber,t.transactiondate,t.quantity,t.purchaserate
        ,t.purchaseamount,t.salerate,t.saleamount,t.pumpcode,t.slipnumber
        from PUMPDIESELTRANSACTION t,ht_contract_data d,contractorcategory c
        where t.seasoncode=d.seasoncode and t.vehiclecode=d.vehiclecode 
        and d.contractorcategorycode=c.contractorcategorycode
        and {$cond} 
        union all
        select d.contractorcategorycode,d.contractorcode,null vehiclecode,d.subcontractornameuni
        ,c.contractorcategorynameuni,d.contractornameuni,d.contractornameeng
        ,d.vehiclenumber,t.transactiondate,t.quantity,t.purchaserate
        ,t.purchaseamount,t.salerate,t.saleamount,t.pumpcode,t.slipnumber
        from PUMPDIESELTRANSACTION t,ht_contract_data d,contractorcategory c
        where t.seasoncode=d.seasoncode and t.subcontractorcode=d.subcontractorcode 
        and d.contractorcategorycode=c.contractorcategorycode
        and {$cond}))t,pump p
        where t.pumpcode=p.pumpcode
        order by t.pumpcode,transactiondate,slipnumber";
        $result = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($result);
        $response = array();
        $filename='pumpdatesummary.csv';
        //$fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');
        $fp1=fopen('php://memory', 'w');
        $deductionnamelist="Start,Date,Purchase Rate,Purchase Amount,Slip Number,Contractor,Vehicle,Quantity,Sale Amount,End";
        fputcsv($fp1, array($deductionnamelist), $delimiter = ',', $enclosure = '#');
        $srno=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $dt=DateTime::createFromFormat('d-M-y',$row['TRANSACTIONDATE'])->format('d/m/Y');
            $rowstring=",".$dt.",".$row['PURCHASERATE'].",".$row['PURCHASEAMOUNT'].",".$row['SLIPNUMBER'].",".$row['CONTRACTORNAMEENG'].",".$row['VEHICLENUMBER'].",".$row['QUANTITY'].",".$row['SALEAMOUNT'].",";
            fputcsv($fp1, array($rowstring), $delimiter = ',', $enclosure = '#');
            $srno++;
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