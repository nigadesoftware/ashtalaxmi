<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class bankbranchlist extends swappreport
{	
    public $bankcode;
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
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        $this->newrow(7);
        $this->textbox('बँक ब्रांच यादी ',170,10,'S','C',1,'siddhanta',13);
        $this->newrow(2);
        $this->hline(10,150,$this->liney+6,'C');
        $this->newrow(7);
        $this->textbox('बँक कोड नं ',20,10,'S','L',1,'siddhanta',11);
        $this->textbox('बँक नाव',70,30,'S','L',1,'siddhanta',11);
        $this->textbox('IFSC',20,95,'S','L',1,'siddhanta',11);
        $this->hline(10,150,$this->liney+6,'C');
        $this->newrow();
        //$this->drawlines($limit);
    }
   
    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        $liney = $this->liney;
        $this->liney = 85;
        $this->hline(10,1150,$this->liney-12);
        $this->vline($this->liney-12,$this->liney+$limit,10);
        $this->vline($this->liney-12,$this->liney+$limit,20);
        $this->vline($this->liney-12,$this->liney+$limit,75);
        $this->vline($this->liney-12,$this->liney+$limit,90);
        $this->vline($this->liney-12,$this->liney+$limit,110);
        $this->hline(10,150,$this->liney+$limit);
        $this->hline(10,150,$this->liney+$limit);
        $this->hline(140,150,$this->liney-5);
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
        $cond ="";
        if ($this->bankcode>0 )
        {
            if ($cond=="")
            {
                $cond= $cond.'bankcode='.$this->bankcode;
            }
            else
            {
                $cond= $cond.' and bankcode='.$this->bankcode;
            }

        }
        if ( $cond=="")
        {
            $query1 ="select t.bankcode,t.banknameeng,t.banknameuni 
            from nst_nasaka_agriculture.bank t order by t.bankcode";
        }
        else
        {
            $query1 ="select t.bankcode,t.banknameeng,t.banknameuni 
            from nst_nasaka_agriculture.bank t 
            where {$cond} order by t.bankcode";
        }  
            $result1 = oci_parse($this->connection, $query1);
            $r1 = oci_execute($result1);
            while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                //$this->textbox('विभाग :'.$row1['DIVISIONNAMEUNI'].' गट : '.$row2['CIRCLENAMEUNI'].' गाव : '.$row3['VILLAGENAMEUNI'].' सभासदत्व : '.$row4['FARMERCATEGORYNAMEUNI'],200,10,'S','L',1,'siddhanta',12);
                $query2 ="select t.bankbranchcode
                ,t.bankbranchnameuni
                ,t.bankbranchnameeng
                ,t.ifsc 
                from nst_nasaka_agriculture.bankbranch t,nst_nasaka_agriculture.bank b
                where t.bankcode=b.bankcode
                and t.bankcode={$row1['BANKCODE']}
                order by bankbranchnameeng ";
                $result2 = oci_parse($this->connection, $query2);
                $r2 = oci_execute($result2);
                $cnt =0;
                while ($row2 = oci_fetch_array($result2,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    if($cnt++==0)
                    {
                        $this->textbox('बँक :'.$row1['BANKNAMEUNI'],100,10,'S','L',1,'siddhanta',12);
                        $this->textbox($row1['BANKNAMEENG'],200,130,'S','L',1,'siddhanta',12);
                        $this->newrow(5);
                    }
                    $this->textbox($row2['BANKBRANCHCODE'],15,10,'S','R',1,'SakalMarathiNormal922',10);
                    $this->textbox($row2['BANKBRANCHNAMEUNI'],70,25,'S','L',1,'siddhanta',11);
                    $this->textbox($row2['IFSC'],50,95,'S','L',1,'SakalMarathiNormal922',10);
                    $this->textbox($row2['BANKBRANCHNAMEENG'],50,140,'S','L',1,'SakalMarathiNormal922',10);
                    $this->newrow(5); 
                    
                }  
                if ($cnt>0)
                {
                    $this->newpage(True);       
                }   
            }
               
       }
        
        //$this->hline(10,200,$this->liney+$limit);

     function export()
    {
        $cond ="1=1";
        if ($this->bankcode>0 )
        {
            if ($cond=="")
            {
                $cond= $cond.'bankcode='.$this->bankcode;
            }
            else
            {
                $cond= $cond.' and bankcode='.$this->bankcode;
            }

        }

            $group_query_1 =" select b.bankcode
            ,b.banknameeng
            ,b.banknameuni
            ,t.bankbranchcode
            ,t.bankbranchnameuni
            ,t.bankbranchnameeng
            ,t.ifsc 
            from nst_nasaka_agriculture.bankbranch t,nst_nasaka_agriculture.bank b
            where {$cond} and
            t.bankcode=b.bankcode
            order by b.bankcode,t.bankbranchcode";
           $result = oci_parse($this->connection, $group_query_1);
           $r = oci_execute($result);
           $response = array();
           $filename='BankBranchList.csv';
           //$fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');
           $fp1=fopen('php://memory', 'w');
           fputcsv($fp1, array('#','Bank Code','Bank Name','Banch Code','Branch Name','IFSC Code','#'));
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
               // $acno="'".$row['ACCOUNTNUMBER'];
                fputcsv($fp1, array('#',$row['BANKCODE'],$row['BANKNAMEENG'],$row['BANKBRANCHCODE'],$row['BANKBRANCHNAMEENG'],$row['IFSC'],'#'), $delimiter = ',', $enclosure = '"');
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
