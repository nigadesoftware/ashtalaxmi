<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    include_once("../ip_model/saleinvoiceheader_db_oracle.php");
    include_once("../ip_model/goodspurchaser_db_oracle.php");
//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class godownstocksum extends swappreport
{	
    public $goodscategorycode;
    public $fromdate;
    public $todate;
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
        $this->pdf->SetSubject('Godown Stock Summary');
        $this->pdf->SetKeywords('GODWNSTSUM_000.EN');
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
        $this->pdf->Output('GDSTSM_000.pdf', 'I');
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
        $this->newrow(8);
        $this->textbox('Godown Stock Summary',180,10,'S','C',1,'verdana',12);
        $this->newrow(4);
        $this->textbox('for the period from '.DateTime::createFromFormat('d-M-Y',$this->fromdate)->format('d/m/Y').' to '.DateTime::createFromFormat('d-M-Y',$this->todate)->format('d/m/Y'),180,10,'S','C',1,'verdana',10);
        $this->newrow(3);
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow(7);
        //$this->drawlines($limit);
    }
   
    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        /* $liney = $this->liney;
        $this->liney = 85;
        $this->hline(10,200,$this->liney-12);
        $this->vline($this->liney-12,$this->liney+$limit,10);
        $this->vline($this->liney-12,$this->liney+$limit,20);
        $this->vline($this->liney-12,$this->liney+$limit,75);
        $this->vline($this->liney-12,$this->liney+$limit,90);
        $this->vline($this->liney-12,$this->liney+$limit,110);
        $this->vline($this->liney-12,$this->liney+$limit,140);
        $this->vline($this->liney-5,$this->liney+$limit,150);
        $this->vline($this->liney-12,$this->liney+$limit,170);
        $this->vline($this->liney-5,$this->liney+$limit,180);
        $this->vline($this->liney-12,$this->liney+$limit,200);
        $this->hline(10,200,$this->liney+$limit);
        $this->hline(10,200,$this->liney+$limit);
        $this->hline(140,200,$this->liney-5);
        $this->liney = $liney; */
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
        $this->textbox('Grade',50,15,'S','L',1,'verdana',10);
        $this->textbox('Godown',20,45,'S','L',1,'verdana',10);
        $this->textbox('Lot',10,65,'S','R',1,'verdana',10);
        $this->textbox('Op.Bal',20,75,'S','R',1,'verdana',10);
        $this->textbox('Prod.',20,95,'S','R',1,'verdana',10);
        $this->textbox('Trf.I/d',20,115,'S','R',1,'verdana',10);
        $this->textbox('Trf.O/w',20,135,'S','R',1,'verdana',10);
        $this->textbox('Sale',20,155,'S','R',1,'verdana',10);
        $this->textbox('Cl.Bal',20,175,'S','R',1,'verdana',10);
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow();

        $query ="select productionyearcode from (        
        select t.productionyearcode,t.godownnumber,t.finishedgoodscode,t.lotnumber,f.shortname
        ,case when sum(t.debitquantity)-sum(t.creditquantity)>=0 then sum(t.debitquantity)-sum(t.creditquantity) end as openingdebitquantity
        ,case when sum(t.debitquantity)-sum(t.creditquantity)<0 then abs(sum(t.debitquantity)-sum(t.creditquantity)) end as openingcreditquantity
        ,0 as debitquantity
        ,0 as trfdebitquantity
        ,0 as creditquantity
        ,0 as closingdebitquantity
        ,0 as closingcreditquantity
        from godownalltransactions t,finishedgoods f
        where t.transactiondate<'".$this->fromdate."'
        and f.goodscategorycode=".$this->goodscategorycode."
        and t.finishedgoodscode=f.finishedgoodscode
        group by t.productionyearcode,t.godownnumber,t.finishedgoodscode,t.lotnumber,f.shortname
        union all
        select t.productionyearcode,t.godownnumber,t.finishedgoodscode,t.lotnumber,f.shortname
        ,0 as openingdebitquantity
        ,0 as openingcreditquantity
        ,sum(t.debitquantity) as debitquantity
        ,sum(trfdebitquantity) trfdebitquantity
        ,sum(t.creditquantity) as creditquantity
        ,0 as closingdebitquantity
        ,0 as closingcreditquantity
        from godownalltransactions t,finishedgoods f
        where t.transactiondate>='".$this->fromdate."'
        and t.transactiondate<='".$this->todate."'
        and f.goodscategorycode=".$this->goodscategorycode."
        and t.finishedgoodscode=f.finishedgoodscode
        group by t.productionyearcode,t.godownnumber,t.finishedgoodscode,t.lotnumber,f.shortname
        union all
        select t.productionyearcode,t.godownnumber,t.finishedgoodscode,t.lotnumber,f.shortname
        ,0 as openingdebitquantity
        ,0 as openingcreditquantity
        ,0 as debitquantity
        ,0 as trfdebitquantity
        ,0 as creditquantity
        ,case when sum(t.debitquantity)-sum(t.creditquantity)>=0 then sum(t.debitquantity)-sum(t.creditquantity) end as closingdebitquantity
        ,case when sum(t.debitquantity)-sum(t.creditquantity)<0 then abs(sum(t.debitquantity)-sum(t.creditquantity)) end as closingcreditquantity
        from godownalltransactions t,finishedgoods f
        where t.transactiondate<='".$this->todate."'
        and f.goodscategorycode=".$this->goodscategorycode."
        and t.finishedgoodscode=f.finishedgoodscode
        group by t.productionyearcode,t.godownnumber,t.finishedgoodscode,t.lotnumber,f.shortname)
        where openingdebitquantity>0 or openingcreditquantity>0 or debitquantity>0 or trfdebitquantity>0 or creditquantity>0 or closingdebitquantity>0 or closingcreditquantity>0
        group by productionyearcode
        order by productionyearcode asc";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $openingquantity=0;
        $debitquantity=0;
        $trfdebitquantity=0;
        $creditquantity=0;
        $closingquantity=0;
        $trfcreditquantity=0;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->textbox('Production Season:'.$row['PRODUCTIONYEARCODE'],80,10,'S','L',1,'verdana',10);
            $this->newrow();
            
            $query1 ="select * from (select productionyearcode,SEQUENCEORDER,finishedgoodscode,shortname
            ,sum(openingdebitquantity)-sum(openingcreditquantity) as openingquantity
            ,sum(debitquantity) as debitquantity
            ,sum(trfdebitquantity) trfdebitquantity
            ,sum(creditquantity) as creditquantity
            ,sum(closingdebitquantity)-sum(closingcreditquantity) as closingquantity
            from (        
            select t.productionyearcode,t.godownnumber,f.SEQUENCEORDER,t.finishedgoodscode,t.lotnumber,f.shortname
            ,case when sum(t.debitquantity)-sum(t.creditquantity)>=0 then sum(t.debitquantity)-sum(t.creditquantity) end as openingdebitquantity
            ,case when sum(t.debitquantity)-sum(t.creditquantity)<0 then abs(sum(t.debitquantity)-sum(t.creditquantity)) end as openingcreditquantity
            ,0 as debitquantity
            ,0 trfdebitquantity
            ,0 as creditquantity
            ,0 as closingdebitquantity
            ,0 as closingcreditquantity
            ,0 trfcreditquantity
            from godownalltransactions t,finishedgoods f
            where t.transactiondate<'".$this->fromdate."'
            and f.goodscategorycode=".$this->goodscategorycode."
            and t.finishedgoodscode=f.finishedgoodscode
            group by t.productionyearcode,t.godownnumber,f.SEQUENCEORDER,t.finishedgoodscode,t.lotnumber,f.shortname,t.transactiontypecode
            union all
            select t.productionyearcode,t.godownnumber,f.SEQUENCEORDER,t.finishedgoodscode,t.lotnumber,f.shortname
            ,0 as openingdebitquantity
            ,0 as openingcreditquantity
            ,case when t.transactiontypecode <> 4 then sum(t.debitquantity) else 0 end as debitquantity
            ,case when t.transactiontypecode = 4 then sum(t.debitquantity) else 0 end trfdebitquantity
            ,case when t.transactiontypecode <> 3 then sum(t.creditquantity) else 0 end as creditquantity
            ,0 as closingdebitquantity
            ,0 as closingcreditquantity
            ,case when t.transactiontypecode = 3 then sum(t.creditquantity) else 0 end trfcreditquantity
            from godownalltransactions t,finishedgoods f
            where t.transactiondate>='".$this->fromdate."'
            and t.transactiondate<='".$this->todate."'
            and f.goodscategorycode=".$this->goodscategorycode."
            and t.finishedgoodscode=f.finishedgoodscode
            group by t.productionyearcode,t.godownnumber,f.SEQUENCEORDER,t.finishedgoodscode,t.lotnumber,f.shortname,t.transactiontypecode
            union all
            select t.productionyearcode,t.godownnumber,f.SEQUENCEORDER,t.finishedgoodscode,t.lotnumber,f.shortname
            ,0 as openingdebitquantity
            ,0 as openingcreditquantity
            ,0 as debitquantity
            ,0 trfdebitquantity
            ,0 as creditquantity
            ,case when sum(t.debitquantity)-sum(t.creditquantity)>=0 then sum(t.debitquantity)-sum(t.creditquantity) end as closingdebitquantity
            ,case when sum(t.debitquantity)-sum(t.creditquantity)<0 then abs(sum(t.debitquantity)-sum(t.creditquantity)) end as closingcreditquantity
            ,0 trfcreditquantity
            from godownalltransactions t,finishedgoods f
            where t.transactiondate<='".$this->todate."'
            and f.goodscategorycode=".$this->goodscategorycode."
            and t.finishedgoodscode=f.finishedgoodscode
            group by t.productionyearcode,t.godownnumber,f.SEQUENCEORDER,t.finishedgoodscode,t.lotnumber,f.shortname,t.transactiontypecode)
            group by productionyearcode,SEQUENCEORDER,finishedgoodscode,shortname
            having productionyearcode=".$row['PRODUCTIONYEARCODE'].
            ")
            where openingquantity>0 or debitquantity>0 or trfdebitquantity>0 or creditquantity>0 or closingquantity>0 
            order by SEQUENCEORDER,finishedgoodscode asc,shortname asc";
            $result1 = oci_parse($this->connection, $query1);
            $r1 = oci_execute($result1);
            $openingquantity1=0;
            $debitquantity1=0;
            $trfdebitquantity1=0;
            $creditquantity1=0;
            $closingquantity1=0;
            $trfcreditquantity1=0;
            while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                $query2 ="select * 
                from (
                select productionyearcode,finishedgoodscode,shortname,godownnumber,lotnumber
                ,sum(openingdebitquantity)-sum(openingcreditquantity) as openingquantity
                ,sum(debitquantity) as debitquantity
                ,sum(trfdebitquantity) trfdebitquantity
                ,sum(creditquantity) as creditquantity
                ,sum(closingdebitquantity)-sum(closingcreditquantity) as closingquantity
                ,sum(trfcreditquantity) trfcreditquantity
                from (        
                select t.productionyearcode,t.godownnumber,t.finishedgoodscode,t.lotnumber,f.shortname
                ,case when sum(t.debitquantity)-sum(t.creditquantity)>=0 then sum(t.debitquantity)-sum(t.creditquantity) end as openingdebitquantity
                ,case when sum(t.debitquantity)-sum(t.creditquantity)<0 then abs(sum(t.debitquantity)-sum(t.creditquantity)) end as openingcreditquantity
                ,0 as debitquantity
                ,0 trfdebitquantity
                ,0 as creditquantity
                ,0 as closingdebitquantity
                ,0 as closingcreditquantity
                ,0 trfcreditquantity
                from godownalltransactions t,finishedgoods f
                where t.transactiondate<'".$this->fromdate."'
                and f.goodscategorycode=".$this->goodscategorycode."
                and t.finishedgoodscode=f.finishedgoodscode
                group by t.productionyearcode,t.godownnumber,t.finishedgoodscode,t.lotnumber,f.shortname,t.transactiontypecode
                union all
                select t.productionyearcode,t.godownnumber,t.finishedgoodscode,t.lotnumber,f.shortname
                ,0 as openingdebitquantity
                ,0 as openingcreditquantity
                ,case when t.transactiontypecode <> 4 then sum(t.debitquantity) else 0 end as debitquantity
                ,case when t.transactiontypecode = 4 then sum(t.debitquantity) else 0 end trfdebitquantity
                ,case when t.transactiontypecode <> 3 then sum(t.creditquantity) else 0 end as creditquantity
                ,0 as closingdebitquantity
                ,0 as closingcreditquantity
                ,case when t.transactiontypecode = 3 then sum(t.creditquantity) else 0 end trfcreditquantity
                from godownalltransactions t,finishedgoods f
                where t.transactiondate>='".$this->fromdate."'
                and t.transactiondate<='".$this->todate."'
                and f.goodscategorycode=".$this->goodscategorycode."
                and t.finishedgoodscode=f.finishedgoodscode
                group by t.productionyearcode,t.godownnumber,t.finishedgoodscode,t.lotnumber,f.shortname,t.transactiontypecode
                union all
                select t.productionyearcode,t.godownnumber,t.finishedgoodscode,t.lotnumber,f.shortname
                ,0 as openingdebitquantity
                ,0 as openingcreditquantity
                ,0 as debitquantity
                ,0 trfdebitquantity
                ,0 as creditquantity
                ,case when sum(t.debitquantity)-sum(t.creditquantity)>=0 then sum(t.debitquantity)-sum(t.creditquantity) end as closingdebitquantity
                ,case when sum(t.debitquantity)-sum(t.creditquantity)<0 then abs(sum(t.debitquantity)-sum(t.creditquantity)) end as closingcreditquantity
                ,0 trfcreditquantity
                from godownalltransactions t,finishedgoods f
                where t.transactiondate<='".$this->todate."'
                and f.goodscategorycode=".$this->goodscategorycode."
                and t.finishedgoodscode=f.finishedgoodscode
                group by t.productionyearcode,t.godownnumber,t.finishedgoodscode,t.lotnumber,f.shortname,t.transactiontypecode)
                group by productionyearcode,finishedgoodscode,shortname,godownnumber,lotnumber
                having productionyearcode=".$row['PRODUCTIONYEARCODE'].
                " and finishedgoodscode=".$row1['FINISHEDGOODSCODE'].
                " and shortname='".$row1['SHORTNAME'].
                "') 
                where openingquantity>0 or debitquantity>0 or trfdebitquantity>0 or creditquantity>0 or closingquantity>0 
                order by finishedgoodscode asc,shortname asc,godownnumber asc,lotnumber asc";
                $result2 = oci_parse($this->connection, $query2);
                $r2 = oci_execute($result2);
                $openingquantity2=0;
                $debitquantity2=0;
                $trfdebitquantity2=0;
                $trfcreditquantity2=0;
                $creditquantity2=0;
                $closingquantity2=0;
                while ($row2 = oci_fetch_array($result2,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    $this->textbox($row1['SHORTNAME'],50,15,'S','L',1,'verdana',9);
                    $this->textbox($row2['GODOWNNUMBER'],20,45,'S','C',1,'verdana',9);
                    $this->textbox($row2['LOTNUMBER'],10,65,'S','C',1,'verdana',9);
                    $this->textbox($row2['OPENINGQUANTITY'],20,75,'S','R',1,'verdana',9);
                    $this->textbox($row2['DEBITQUANTITY'],20,95,'S','R',1,'verdana',9);
                    $this->textbox($row2['TRFDEBITQUANTITY'],20,115,'S','R',1,'verdana',9);
                    $this->textbox($row2['TRFCREDITQUANTITY'],20,135,'S','R',1,'verdana',9);
                    $this->textbox($row2['CREDITQUANTITY'],20,155,'S','R',1,'verdana',9);
                    $this->textbox($row2['CLOSINGQUANTITY'],20,175,'S','R',1,'verdana',9);
                    $this->newrow(5);
                    $openingquantity2 = $openingquantity2 + $row2['OPENINGQUANTITY'];
                    $debitquantity2 = $debitquantity2 + $row2['DEBITQUANTITY'];
                    $trfdebitquantity2 = $trfdebitquantity2 + $row2['TRFDEBITQUANTITY'];
                    $trfcreditquantity2 = $trfcreditquantity2 + $row2['TRFCREDITQUANTITY'];
                    $creditquantity2 = $creditquantity2 + $row2['CREDITQUANTITY'];
                    $closingquantity2 = $closingquantity2 + $row2['CLOSINGQUANTITY'];
                }
                $this->hline(10,200,$this->liney,'C');
                $this->textbox('Total '.$row1['SHORTNAME'],100,15,'S','L',1,'verdana',9); 
                $this->textbox($openingquantity2,20,75,'S','R',1,'verdana',9);
                $this->textbox($debitquantity2,20,95,'S','R',1,'verdana',9);
                $this->textbox($trfdebitquantity2,20,115,'S','R',1,'verdana',9);
                $this->textbox($trfcreditquantity2,20,135,'S','R',1,'verdana',9);
                $this->textbox($creditquantity2,20,155,'S','R',1,'verdana',9);
                $this->textbox($closingquantity2,20,175,'S','R',1,'verdana',9);
                $this->newrow(5);
                $this->hline(10,200,$this->liney,'C');  
                $openingquantity1 = $openingquantity1 + $openingquantity2;
                $debitquantity1 = $debitquantity1 + $debitquantity2;
                $trfdebitquantity1 = $trfdebitquantity1 + $trfdebitquantity2;
                $trfcreditquantity1 = $trfcreditquantity1 + $trfcreditquantity2;
                $creditquantity1 = $creditquantity1 + $creditquantity2;
                $closingquantity1 = $closingquantity1 + $closingquantity2;
                $this->hline(10,200,$this->liney,'C'); 
            }
            $this->hline(10,200,$this->liney,'C'); 
            $this->textbox('Total '.$row['PRODUCTIONYEARCODE'],100,15,'S','L',1,'verdana',9); 
            $this->textbox($openingquantity1,20,75,'S','R',1,'verdana',9);
            $this->textbox($debitquantity1,20,95,'S','R',1,'verdana',9);
            $this->textbox($trfdebitquantity1,20,115,'S','R',1,'verdana',9);
            $this->textbox($trfcreditquantity1,20,135,'S','R',1,'verdana',9);
            $this->textbox($creditquantity1,20,155,'S','R',1,'verdana',9);
            $this->textbox($closingquantity1,20,175,'S','R',1,'verdana',9);
            $this->newrow();
            $this->hline(10,200,$this->liney,'C');
            $openingquantity = $openingquantity + $openingquantity1;
            $debitquantity = $debitquantity + $debitquantity1;
            $trfdebitquantity = $trfdebitquantity + $trfdebitquantity1;
            $trfcreditquantity = $trfcreditquantity + $trfcreditquantity1;
            $creditquantity = $creditquantity + $creditquantity1;
            $closingquantity = $closingquantity + $closingquantity1; 
        }
        $this->hline(10,200,$this->liney,'C'); 
        $this->textbox('Grand Total',100,10,'S','L',1,'verdana',10); 
        $this->textbox($openingquantity,20,75,'S','R',1,'verdana',9);
        $this->textbox($debitquantity,20,95,'S','R',1,'verdana',9);
        $this->textbox($trfdebitquantity,20,115,'S','R',1,'verdana',9);
        $this->textbox($trfcreditquantity,20,135,'S','R',1,'verdana',9);
        $this->textbox($creditquantity,20,155,'S','R',1,'verdana',9);
        $this->textbox($closingquantity,20,175,'S','R',1,'verdana',9);
        $this->newrow();
        $this->hline(10,200,$this->liney,'C');
        
        //$this->newpage(true);
        $this->newrow();
        $this->textbox('Godownwise Stock',100,10,'S','L',1,'verdana',12); 
        $this->newrow();
        $blockstartliney = $this->liney;
        $this->hline(10,190,$this->liney);    
        $this->textbox('Godown',20,10,'S','L',1,'verdana',11);
        $this->textbox('Op. Bal',30,30,'S','R',1,'verdana',11);
        $this->textbox('Prod.',30,60,'S','R',1,'verdana',11);
        $this->textbox('Trf I/w.',30,90,'S','R',1,'verdana',11);
        $this->textbox('Trf O/w',30,110,'S','R',1,'verdana',11);
        $this->textbox('Sale',30,130,'S','R',1,'verdana',11);
        $this->textbox('Cl.Bal',30,160,'S','R',1,'verdana',11);
        $this->newrow();
        $this->hline(10,190,$this->liney);    
        $query ="select * from (select godownnumber
        ,sum(openingdebitquantity)-sum(openingcreditquantity) as openingquantity
        ,sum(debitquantity) as debitquantity
        ,sum(trfdebitquantity) as trfdebitquantity
        ,sum(creditquantity) as creditquantity
        ,sum(closingdebitquantity)-sum(closingcreditquantity) as closingquantity
        ,sum(trfcreditquantity) trfcreditquantity
        from (        
        select t.productionyearcode,t.godownnumber,t.finishedgoodscode,t.lotnumber,f.shortname
        ,case when sum(t.debitquantity)-sum(t.creditquantity)>=0 then sum(t.debitquantity)-sum(t.creditquantity) end as openingdebitquantity
        ,case when sum(t.debitquantity)-sum(t.creditquantity)<0 then abs(sum(t.debitquantity)-sum(t.creditquantity)) end as openingcreditquantity
        ,0 as debitquantity
        ,0 as trfdebitquantity
        ,0 as creditquantity
        ,0 as closingdebitquantity
        ,0 as closingcreditquantity
        ,0 as trfcreditquantity
        from godownalltransactions t,finishedgoods f
        where t.transactiondate<'".$this->fromdate."'
        and f.goodscategorycode=".$this->goodscategorycode."
        and t.finishedgoodscode=f.finishedgoodscode
        group by t.productionyearcode,t.godownnumber,t.finishedgoodscode,t.lotnumber,f.shortname,t.transactiontypecode
        union all
        select t.productionyearcode,t.godownnumber,t.finishedgoodscode,t.lotnumber,f.shortname
        ,0 as openingdebitquantity
        ,0 as openingcreditquantity
        ,case when t.transactiontypecode <> 4 then sum(t.debitquantity) else 0 end as debitquantity
        ,case when t.transactiontypecode = 4 then sum(t.debitquantity) else 0 end trfdebitquantity
        ,case when t.transactiontypecode <> 3 then sum(t.creditquantity) else 0 end as creditquantity
        ,0 as closingdebitquantity
        ,0 as closingcreditquantity
        ,case when t.transactiontypecode = 3 then sum(t.creditquantity) else 0 end trfcreditquantity
        from godownalltransactions t,finishedgoods f
        where t.transactiondate>='".$this->fromdate."'
        and t.transactiondate<='".$this->todate."'
        and f.goodscategorycode=".$this->goodscategorycode."
        and t.finishedgoodscode=f.finishedgoodscode
        group by t.productionyearcode,t.godownnumber,t.finishedgoodscode,t.lotnumber,f.shortname,t.transactiontypecode
        union all
        select t.productionyearcode,t.godownnumber,t.finishedgoodscode,t.lotnumber,f.shortname
        ,0 as openingdebitquantity
        ,0 as openingcreditquantity
        ,0 as debitquantity
        ,0 as trfdebitquantity
        ,0 as creditquantity
        ,case when sum(t.debitquantity)-sum(t.creditquantity)>=0 then sum(t.debitquantity)-sum(t.creditquantity) end as closingdebitquantity
        ,case when sum(t.debitquantity)-sum(t.creditquantity)<0 then abs(sum(t.debitquantity)-sum(t.creditquantity)) end as closingcreditquantity
        ,0 trfcreditquantity
        from godownalltransactions t,finishedgoods f
        where t.transactiondate<='".$this->todate."'
        and f.goodscategorycode=".$this->goodscategorycode."
        and t.finishedgoodscode=f.finishedgoodscode
        group by t.productionyearcode,t.godownnumber,t.finishedgoodscode,t.lotnumber,f.shortname,t.transactiontypecode)
        group by godownnumber)
        where openingquantity>0 or debitquantity>0 or creditquantity>0 or closingquantity>0 
        order by godownnumber asc";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $openingquantity=0;
        $debitquantity=0;
        $trfdebitquantity=0;
        $creditquantity=0;
        $closingquantity=0;
        $trfcreditquantity=0;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->textbox($row['GODOWNNUMBER'],20,10,'S','C',1,'verdana',9);
            $this->textbox($row['OPENINGQUANTITY'],30,30,'S','R',1,'verdana',9);
            $this->textbox($row['DEBITQUANTITY'],30,60,'S','R',1,'verdana',9);
            $this->textbox($row['TRFDEBITQUANTITY'],30,90,'S','R',1,'verdana',9);
            $this->textbox($row['TRFCREDITQUANTITY'],30,110,'S','R',1,'verdana',9);
            $this->textbox($row['CREDITQUANTITY'],30,130,'S','R',1,'verdana',9);
            $this->textbox($row['CLOSINGQUANTITY'],30,160,'S','R',1,'verdana',9);
            $this->newrow(5);
            $this->hline(10,190,$this->liney);    
            $openingquantity = $openingquantity + $row['OPENINGQUANTITY'];
            $debitquantity = $debitquantity + $row['DEBITQUANTITY'];
            $trfdebitquantity = $trfdebitquantity + $row['TRFDEBITQUANTITY'];
            $trfcreditquantity = $trfcreditquantity + $row['TRFCREDITQUANTITY'];
            $creditquantity = $creditquantity + $row['CREDITQUANTITY'];
            $closingquantity = $closingquantity + $row['CLOSINGQUANTITY'];
        }
        $this->textbox('Total',20,10,'S','C',1,'verdana',9);
        $this->textbox($openingquantity,30,30,'S','R',1,'verdana',9);
        $this->textbox($debitquantity,30,60,'S','R',1,'verdana',9);
        $this->textbox($trfdebitquantity,30,90,'S','R',1,'verdana',9);
        $this->textbox($trfcreditquantity,30,110,'S','R',1,'verdana',9);
        $this->textbox($creditquantity,30,130,'S','R',1,'verdana',9);
        $this->textbox($closingquantity,30,160,'S','R',1,'verdana',9);
        $this->newrow(5);
        $this->hline(10,190,$this->liney);
        $liney = $this->liney;
        // $this->liney = $blockstartliney;
        // $this->vline($this->liney,$liney,10);
        // $this->vline($this->liney,$liney,40);
        // $this->vline($this->liney,$liney,70);
        // $this->vline($this->liney,$liney,100);
        // $this->vline($this->liney,$liney,130);
        // $this->vline($this->liney,$liney,160);
        $this->hline(10,160,$liney);
        $this->liney = $liney;

        if ($this->liney >230) 
        {
            $this->newpage(true);
        }
        $this->newrow();
        $this->textbox('Production Seasonwise Stock',100,10,'S','L',1,'verdana',12); 
        $this->newrow();
        $blockstartliney = $this->liney;
        $this->hline(10,190,$this->liney);    
        $this->textbox('Prod.Season',20,10,'S','L',1,'verdana',11);
        $this->textbox('Op.Bal',20,30,'S','R',1,'verdana',11);
        $this->textbox('Inward',40,50,'S','R',1,'verdana',11);
        $this->textbox('Outward',80,90,'S','C',1,'verdana',11);
        $this->textbox('Cl.Bal',20,170,'S','R',1,'verdana',11);
        $this->newrow();
        $this->textbox('Prod.',20,50,'S','L',1,'verdana',11);
        $this->textbox('Trf.I/w',20,70,'S','L',1,'verdana',11);
        $this->textbox('Free',20,90,'S','L',1,'verdana',11);
        $this->textbox('Gatesale',20,110,'S','L',1,'verdana',11);
        $this->textbox('Export',20,130,'S','L',1,'verdana',11);
        $this->textbox('Trf/Repr',20,150,'S','L',1,'verdana',11);
        $this->newrow();
        $this->hline(10,190,$this->liney);    
        $query ="select * from (select productionyearcode
        ,sum(openingquantity) as openingquantity
        ,sum(debitquantity) as debitquantity
        ,sum(trfdebitquantity) trfdebitquantity
        ,sum(free) as free
        ,sum(levy) as levy
        ,sum(export) as export
        ,sum(trf) as trf
        ,sum(closingquantity) as closingquantity
        ,sum(trfcreditquantity) trfcreditquantity
        from (select productionyearcode
        ,sum(openingdebitquantity)-sum(openingcreditquantity) as openingquantity
        ,sum(debitquantity) as debitquantity
        ,sum(trfdebitquantity) trfdebitquantity
        ,sum(trfcreditquantity) trfcreditquantity
        ,case when salecategorycode=1 then sum(creditquantity) else 0 end free
        ,case when salecategorycode=4 then sum(creditquantity) else 0 end levy
        ,case when salecategorycode=3 then sum(creditquantity) else 0 end export
        ,case when salecategorycode=-1 then sum(creditquantity) else 0 end trf
        ,sum(closingdebitquantity)-sum(closingcreditquantity) as closingquantity
        from (        
        select t.productionyearcode,
        0 as salecategorycode
        ,case when sum(t.debitquantity)-sum(t.creditquantity)>=0 then sum(t.debitquantity)-sum(t.creditquantity) end as openingdebitquantity
        ,case when sum(t.debitquantity)-sum(t.creditquantity)<0 then abs(sum(t.debitquantity)-sum(t.creditquantity)) end as openingcreditquantity
        ,0 as debitquantity
        ,0 as trfdebitquantity
        ,0 as creditquantity
        ,0 as closingdebitquantity
        ,0 as closingcreditquantity
        ,0 as trfcreditquantity
        from godownalltransactions t,finishedgoods f
        where t.transactiondate<'".$this->fromdate."'
        and f.goodscategorycode=".$this->goodscategorycode."
        and t.finishedgoodscode=f.finishedgoodscode
        group by t.productionyearcode
        union all
        select t.productionyearcode,
        0 as salecategorycode
        ,0 as openingdebitquantity
        ,0 as openingcreditquantity
        ,case when t.transactiontypecode <> 4 then sum(t.debitquantity) else 0 end as debitquantity
        ,case when t.transactiontypecode = 4 then sum(t.debitquantity) else 0 end trfdebitquantity
        ,0 as creditquantity
        ,0 as closingdebitquantity
        ,0 as closingcreditquantity
        ,0 as trfcreditquantity
        from godownalltransactions t,finishedgoods f
        where t.transactiondate>='".$this->fromdate."'
        and t.transactiondate<='".$this->todate."'
        and f.goodscategorycode=".$this->goodscategorycode."
        and t.finishedgoodscode=f.finishedgoodscode
        group by t.productionyearcode,t.transactiontypecode
        union all
        select t.productionyearcode,
        case when t.transactiontypecode in (3,5) then 
        -1 else 0 end as salecategorycode
        ,0 as openingdebitquantity
        ,0 as openingcreditquantity
        ,0 as debitquantity
        ,0 trfdebitquantity
        ,sum(t.creditquantity) as creditquantity
        ,0 as closingdebitquantity
        ,0 as closingcreditquantity
        ,case when t.transactiontypecode = 3 then sum(t.creditquantity) else 0 end trfcreditquantity
        from godownalltransactions t,finishedgoods f
        where t.transactiondate>='".$this->fromdate."'
        and t.transactiondate<='".$this->todate."'
        and f.goodscategorycode=".$this->goodscategorycode."
        and t.finishedgoodscode=f.finishedgoodscode
        group by t.productionyearcode,transactiontypecode
        union all
        select d.productionyearcode
        ,h.salecategorycode
        ,0 as openingdebitquantity
        ,0 as openingcreditquantity
        ,0 as debitquantity
        ,0 trfdebitquantity
        ,sum(d.salequantity) as creditquantity
        ,0 as closingdebitquantity
        ,0 as closingcreditquantity
        ,0 trfcreditquantity
        from saleinvoiceheader h,saleinvoicedetail d 
        where h.transactionnumber=d.transactionnumber
        and h.invoicedate>='".$this->fromdate."'
        and h.invoicedate<='".$this->todate."'
        and h.goodscategorycode=".$this->goodscategorycode."
        group by d.productionyearcode
        ,h.salecategorycode
        union all
        select t.productionyearcode
        ,0 as salecategorycode
        ,0 as openingdebitquantity
        ,0 as openingcreditquantity
        ,0 as debitquantity
        ,0 trfdebitquantity
        ,0 as creditquantity
        ,case when sum(t.debitquantity)-sum(t.creditquantity)>=0 then sum(t.debitquantity)-sum(t.creditquantity) end as closingdebitquantity
        ,case when sum(t.debitquantity)-sum(t.creditquantity)<0 then abs(sum(t.debitquantity)-sum(t.creditquantity)) end as closingcreditquantity
        ,0 trfcreditquantity
        from godownalltransactions t,finishedgoods f
        where t.transactiondate<='".$this->todate."'
        and f.goodscategorycode=".$this->goodscategorycode."
        and t.finishedgoodscode=f.finishedgoodscode
        group by t.productionyearcode)
        group by productionyearcode,salecategorycode)
        group by productionyearcode)
        where openingquantity>0 or debitquantity>0 or trfdebitquantity>0 or trfcreditquantity>0 or free>0 or levy>0 or export>0 or trf>0 or closingquantity>0 
        order by productionyearcode asc";
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        $openingquantity=0;
        $debitquantity=0;
        $trfdebitquantity=0;
        $creditquantity=0;
        $closingquantity=0;
        $trfcreditquantity=0;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->textbox($row['PRODUCTIONYEARCODE'],20,10,'S','R',1,'verdana',9);
            $this->textbox($row['OPENINGQUANTITY'],20,30,'S','R',1,'verdana',9);
            $this->textbox($row['DEBITQUANTITY'],20,50,'S','R',1,'verdana',9);
            $this->textbox($row['TRFDEBITQUANTITY'],20,70,'S','R',1,'verdana',9);
            $this->textbox($row['TRFCREDITQUANTITY'],20,70,'S','R',1,'verdana',9);
            $this->textbox($row['FREE'],20,90,'S','R',1,'verdana',9);
            $this->textbox($row['LAVY'],20,110,'S','R',1,'verdana',9);
            $this->textbox($row['EXPORT'],20,130,'S','R',1,'verdana',9);
            $this->textbox($row['TRF'],20,150,'S','R',1,'verdana',9);
            $this->textbox($row['CLOSINGQUANTITY'],20,170,'S','R',1,'verdana',9);
            $this->newrow(5);
            $this->hline(10,190,$this->liney);    
            $openingquantity = $openingquantity + $row['OPENINGQUANTITY'];
            $debitquantity = $debitquantity + $row['DEBITQUANTITY'];
            $trfdebitquantity = $trfdebitquantity + $row['TRFDEBITQUANTITY'];
            $trfcreditquantity = $trfcreditquantity + $row['TRFCREDITQUANTITY'];
           
            $free = $free + $row['FREE'];
            $levy = $levy + $row['LEVY'];
            $export = $export + $row['EXPORT'];
            $trf = $trf + $row['TRF'];
            $closingquantity = $closingquantity + $row['CLOSINGQUANTITY'];
        }
        $this->textbox('Total',20,10,'S','C',1,'verdana',9);
        $this->textbox($openingquantity,20,30,'S','R',1,'verdana',9);
        $this->textbox($debitquantity,20,50,'S','R',1,'verdana',9);
        $this->textbox($trfdebitquantity,20,70,'S','R',1,'verdana',9);
        $this->textbox($trfcreditquantity,20,70,'S','R',1,'verdana',9);
        $this->textbox($free,20,90,'S','R',1,'verdana',9);
        $this->textbox($levy,20,100,'S','R',1,'verdana',9);
        $this->textbox($export,20,130,'S','R',1,'verdana',9);
        $this->textbox($trf,20,150,'S','R',1,'verdana',9);
        $this->textbox($closingquantity,20,170,'S','R',1,'verdana',9);
        $this->newrow(5);
        $this->hline(10,190,$this->liney);
        $liney = $this->liney;
        $this->liney = $blockstartliney;
        $this->vline($this->liney,$liney,10);
        $this->vline($this->liney,$liney,30);
        $this->vline($this->liney,$liney,50);
        $this->vline($this->liney+7,$liney,70);
        $this->hline(50,170,$this->liney+7);
        $this->vline($this->liney,$liney,90);
        $this->vline($this->liney+7,$liney,110);
        $this->vline($this->liney+7,$liney,130);
        $this->vline($this->liney+7,$liney,150);
        $this->vline($this->liney,$liney,170);
        $this->vline($this->liney,$liney,190);
        $this->hline(10,190,$liney);
        $this->liney = $liney;
        /* $this->newrow();
        $this->textbox('Biss Quantity',100,10,'S','L',1,'verdana',12); 
        $this->textbox('Godown No- 1',100,50,'S','L',1,'verdana',11); 
        $this->newrow();
        $blockstartliney = $this->liney;
        $this->hline(10,190,$this->liney);    
        $this->textbox('Prod.Season',20,10,'S','L',1,'verdana',11);
        $this->textbox('Op.Bal',20,30,'S','R',1,'verdana',11);
        $this->textbox('Inward',40,50,'S','R',1,'verdana',11);
        $this->textbox('Outward',80,90,'S','C',1,'verdana',11);
        $this->textbox('Cl.Bal',20,170,'S','R',1,'verdana',11);
        $this->newrow();
        $this->textbox('Prod.',20,50,'S','L',1,'verdana',11);
        $this->textbox('Trf.I/w',20,70,'S','L',1,'verdana',11);
        $this->textbox('Free',20,90,'S','L',1,'verdana',11);
        $this->textbox('Gatesale',20,110,'S','L',1,'verdana',11);
        $this->textbox('Export',20,130,'S','L',1,'verdana',11);
        $this->textbox('Trf/Repr',20,150,'S','L',1,'verdana',11);
        $this->newrow(5);
        $this->hline(10,190,$this->liney);
        $liney = $this->liney;
        $this->liney = $blockstartliney;
        $this->vline($this->liney,$liney,10);
        $this->vline($this->liney,$liney,30);
        $this->vline($this->liney,$liney,50);
        $this->vline($this->liney+7,$liney,70);
        $this->hline(50,170,$this->liney+7);
        $this->vline($this->liney,$liney,90);
        $this->vline($this->liney+7,$liney,110);
        $this->vline($this->liney+7,$liney,130);
        $this->vline($this->liney+7,$liney,150);
        $this->vline($this->liney,$liney,170);
        $this->vline($this->liney,$liney,190);
       
        $this->hline(10,190,$liney);
        $this->liney = $liney;
        $this->textbox('20222023',20,10,'S','L',1,'verdana',10);
        $this->textbox('1300',20,30,'S','R',1,'verdana',10);
        $this->textbox('1300',20,170,'S','R',1,'verdana',10);
        $this->newrow();
        $this->hline(10,190,$this->liney); 
        $this->vline($this->liney,$liney,10);
        $this->vline($this->liney,$liney,30);
        $this->vline($this->liney,$liney,50);
        //$this->vline($this->liney+7,$liney,70);
        //$this->hline(50,170,$this->liney+7);
        //$this->vline($this->liney,$liney,90);
        //$this->vline($this->liney+7,$liney,110);
        //$this->vline($this->liney+7,$liney,130);
        //$this->vline($this->liney+7,$liney,150);
        $this->vline($this->liney,$liney,170);
        $this->vline($this->liney,$liney,190);  */
        
        $this->newrow(15);
        $this->textbox('Prepared By',40,10,'S','L',1,'verdana',10);
        $this->textbox('Godown Clerk',40,70,'S','L',1,'verdana',10);
        $this->textbox('Godown Keeper',40,140,'S','L',1,'verdana',10);
        $this->newrow(10);
       // $this->textbox('Copy to Chairman Saheb/M.D./C.A./C.C./B.R.O./S.G.',150,10,'S','L',1,'verdana',8);
    }
}    
?>
