<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    include_once("../ip_model/saleinvoiceheader_db_oracle.php");
    include_once("../ip_model/goodspurchaser_db_oracle.php");
    include_once("../ip_model/godowninsurance_db_oracle.php");
//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class godowninsurancereport extends swappreport
{	
    public $goodscategorycode;
    public $godownnumber;
    public $fromdate;
    public $todate;
    public $pagetop;
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
        $this->pdf->SetSubject('Godown Insurance Declaraiton');
        $this->pdf->SetKeywords('GODWNINSCL_000.EN');
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
        $lg['a_meta_language'] = 'en';
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
        $this->pdf->Output('GODWNINSCL_000.pdf', 'I');
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
        $this->newrow(5);
        $this->textbox('Godown Insurance Declaration Statement',180,10,'S','C',1,'verdana',12);
        $this->newrow(5);
        $this->textbox('for the period from '.DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y').' to '.DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y'),180,10,'S','C',1,'verdana',10);
        $this->newrow(3);
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow(2);
        $godowninsurance1 = new godowninsurance($this->connection);
        $result = $godowninsurance1->fetchbygodown($this->godownnumber,$this->fromdate,$this->todate);
        if ($godowninsurance1->Get_invalidid()==0)
        {
            $i=0;
            while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                $this->newrow(5);
                if ($i==0)
                {
                    $this->textbox('Godown :'.$row['GODOWNNUMBER'],50,15,'S','L',1,'verdana',11);
                    $this->textbox('Month:'.DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('F'),50,100,'S','L',1,'verdana',11);
                    $this->newrow(5);
                }
                $this->textbox('Insurance Policy No.:'.$row['POLICYNUMBER'],80,15,'S','L',1,'verdana',9);
                $this->textbox('Sum Insured:'.$row['INSUREDAMOUNT'],60,105,'S','L',1,'verdana',9);
                $this->textbox('Valid Upto:'.DateTime::createFromFormat('d-M-y',$row['VALIDUPTODATE'])->format('d/m/Y'),50,155,'S','L',1,'verdana',9);
                $i++;
            }
        }
        $this->newrow(5);
        $this->pagetop=$this->liney;

        $this->textbox('Date',30,15,'S','L',1,'verdana',11);
        $this->textbox('Quantity(qtl)',35,40,'S','L',1,'verdana',11);
        $this->textbox('Avg.Market Price',35,75,'S','R',1,'verdana',11);
        $this->textbox('Value of Stock',35,115,'S','R',1,'verdana',11);
        $this->hline(10,160,$this->liney+7,'C');
        $this->newrow();
        //$this->drawlines($limit);
    }
   
    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        $liney = $this->liney;
        $this->liney = $this->pagetop+7;
        $this->hline(10,200,$this->liney-7);
        $this->vline($this->liney-7,$this->liney+$limit,10);
        $this->vline($this->liney-7,$this->liney+$limit,40);
        $this->vline($this->liney-7,$this->liney+$limit,70);
        $this->vline($this->liney-7,$this->liney+$limit,110);
        $this->vline($this->liney-7,$this->liney+$limit,160);
        //$this->hline(10,200,$this->liney+$limit);
        //$this->hline(10,200,$this->liney+$limit);
        //$this->hline(140,200,$this->liney-5);
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
    function godownstock($godownnumber,$todate)
    {
        $query ="select * from (select godownnumber
        ,nvl(sum(closingdebitquantity),0)-nvl(sum(closingcreditquantity),0) as closingstock
        from (        
        select t.godownnumber
        ,case when nvl(sum(t.debitquantity),0)-nvl(sum(t.creditquantity),0)>=0 then nvl(sum(t.debitquantity),0)-nvl(sum(t.creditquantity),0) end as closingdebitquantity
        ,case when nvl(sum(t.debitquantity),0)-nvl(sum(t.creditquantity),0)<0 then nvl(abs(sum(t.debitquantity)),0)-nvl(sum(t.creditquantity),0) end as closingcreditquantity
        from godownalltransactions t,finishedgoods f
        where t.transactiondate<='".$todate."'
        and f.goodscategorycode=".$this->goodscategorycode."
        and t.godownnumber=".$godownnumber."
        and t.finishedgoodscode=f.finishedgoodscode
        group by t.godownnumber)
        group by godownnumber)
        order by godownnumber asc";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            return $row['CLOSINGSTOCK'];
        }
        else 
        {
            return 0;
        }    
    }
    function averagerate()
    {
        $query ="select round(sum(d.amount)/sum(d.salequantity),2) as avgrate 
        from saleinvoiceheader h, saleinvoicedetail d
        where h.transactionnumber=d.transactionnumber
        and h.invoicedate>='".$this->fromdate."'
        and h.invoicedate<='".$this->todate."'
        and h.goodscategorycode=".$this->goodscategorycode." 
        and salecategorycode=1";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            return $row['AVGRATE'];
        }
        else 
        {
            return 0;
        }    
    }
    function detail()
    {
        $this->hline(10,160,$this->liney); 
        $query ="select godownnumber 
        from godown g
        order by godownnumber asc";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $openingquantity=0;
        $debitquantity=0;
        $creditquantity=0;
        $closingquantity=0;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->godownnumber=$row['GODOWNNUMBER'];
            $this->newpage(true);
            $dt=date('Y-m-d', strtotime($this->fromdate));
            $avgrate=$this->averagerate();
            while ($dt<=date('Y-m-d', strtotime($this->todate)))
            {
                $stk=$this->godownstock($row['GODOWNNUMBER'],date('d-M-Y', strtotime($dt)));
                $amt=$avgrate*$stk;
                $this->textbox(date('d/m/Y', strtotime($dt)),30,10,'S','C',1,'verdana',10);
                $this->textbox($stk,30,35,'S','R',1,'verdana',10);
                $this->textbox($avgrate,35,70,'S','R',1,'verdana',9);
                $this->textbox($amt,35,115,'S','R',1,'verdana',9);
                /*$this->textbox($row['CLOSINGQUANTITY'],30,130,'S','R',1,'verdana',9); */
                $this->newrow(6);
                $this->hline(10,160,$this->liney);     
                /* $openingquantity = $openingquantity + $row['OPENINGQUANTITY'];
                $debitquantity = $debitquantity + $row['DEBITQUANTITY'];
                $creditquantity = $creditquantity + $row['CREDITQUANTITY'];
                $closingquantity = $closingquantity + $row['CLOSINGQUANTITY']; */
                $dt=date('Y-m-d', strtotime("+1 day", strtotime($dt)));
            }
            $this->drawlines($this->liney-$this->pagetop-7);
            $this->newrow(15);
            $this->textbox('Godown Clerk',40,10,'S','L',1,'verdana',10);
            $this->textbox('Godown Keeper',40,70,'S','L',1,'verdana',10);
            $this->textbox('Chief Chemist',40,140,'S','L',1,'verdana',10);
            $this->newrow(5);
            $this->textbox('Copy to Chairman Saheb/M.D./C.A./C.C./B.R.O./S.G.',150,10,'S','L',1,'verdana',8);
        }
        //$this->newpage(true);
    }
}    
?>
