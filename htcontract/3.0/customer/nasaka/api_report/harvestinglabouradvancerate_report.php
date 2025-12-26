<?php
    include_once("../api_oracle/harvestinglabouradvancerate_db_oracle.php");
    class harvestinglabouradvancearatereport
    {	
        public $connection;
        
        public function __construct(&$connection)
        {
            $this->connection = $connection;
        }

        function printpageheader(&$pdf,&$liney)
        {
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $liney = $liney+10;
            $pdf->multicell(50,10,'हंगाम : '.$_SESSION['finalreportperiodid'],0,'L',false,1,10,$liney,true,0,false,true,10);
            $liney = $liney+10;
            $pdf->line(10,$liney,200,$liney);
            $pdf->multicell(15,10,'अ नं.',0,'L',false,1,10,$liney,true,0,false,true,10);
            $pdf->multicell(30,10,'लेबर प्रकार',0,'L',false,1,25,$liney,true,0,false,true,10);
            $pdf->multicell(30,10,'लेबर भरती संख्या',0,'L',false,1,55,$liney,true,0,false,true,10);
            $pdf->multicell(25,20,'पहिला अॅड. हप्ता (ला.)',0,'L',false,1,80,$liney,true,0,false,true,10);
            $pdf->multicell(25,20,'दुसरा अॅड. हप्ता (ला.)',0,'L',false,1,110,$liney,true,0,false,true,10);
            $pdf->multicell(25,20,'तिसरा अॅड. हप्ता (ला.)',0,'L',false,1,135,$liney,true,0,false,true,10);
            $pdf->multicell(25,20,'एकूण अॅड. हप्ता (ला.)',0,'L',false,1,160,$liney,true,0,false,true,10);
            $pdf->multicell(30,20,'एकूण अॅड. रक्कम',0,'L',false,1,185,$liney,true,0,false,true,10);
            $pdf->line(10,$liney+10,200,$liney+10);
            $liney = $liney+10;
            $harvestinglabouradvancerate1 = new harvestinglabouradvancerate($this->connection);
            $result=$harvestinglabouradvancerate1->fetchall($_SESSION['finalreportperiodid']);
            $i=1;
            $nooflabours=0;
            $totaladvance=0;
            while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                $liney = $liney+5;
                $pdf->SetFont('siddhanta', '', 11, '', true);
                $pdf->multicell(15,10,$i++,0,'L',false,1,10,$liney,true,0,false,true,10);
                $pdf->multicell(30,10,$row['NAME_UNICODE'],0,'L',false,1,25,$liney,true,0,false,true,10);
                $pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
                $pdf->multicell(30,10,$row['NOOFLABOURS'].' x '.$row['LABOURPERGROUP'],0,'L',false,1,55,$liney,true,0,false,true,10);
                $pdf->multicell(25,20,number_format($row['FIRSTINSTALLMENTPERHEAD']/100000,2),0,'R',false,1,70,$liney,true,0,false,true,10);
                $pdf->multicell(25,20,number_format($row['SECONDINSTALLMENTPERHEAD']/100000,2),0,'R',false,1,100,$liney,true,0,false,true,10);
                $pdf->multicell(25,20,number_format($row['THIRDINSTALLEMENTPERHEAD']/100000,2),0,'R',false,1,125,$liney,true,0,false,true,10);
                $totaladv=$row['FIRSTINSTALLMENTPERHEAD']+$row['SECONDINSTALLMENTPERHEAD']+$row['THIRDINSTALLEMENTPERHEAD'];
                $pdf->multicell(25,20,number_format($totaladv/100000,2),0,'R',false,1,150,$liney,true,0,false,true,10);
                $pdf->multicell(30,20,$totaladv*$row['NOOFLABOURS'],0,'R',false,1,170,$liney,true,0,false,true,10);    
                $liney = $liney+10;
                $pdf->line(10,$liney,200,$liney);
                $nooflabours+=$row['NOOFLABOURS']*$row['LABOURPERGROUP'];
                $totaladvance+=$totaladv*$row['NOOFLABOURS'];
            }
            $liney = $liney+5;
            $pdf->multicell(30,10,'एकूण',0,'L',false,1,25,$liney,true,0,false,true,10);
            $pdf->multicell(30,10,$nooflabours,0,'L',false,1,55,$liney,true,0,false,true,10);
            $pdf->multicell(30,20,$totaladvance,0,'R',false,1,170,$liney,true,0,false,true,10);    
            $liney = $liney+10;
            $pdf->line(10,$liney,200,$liney);
        }
    }
?>