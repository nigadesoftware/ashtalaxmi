<?php
    include_once("../api_oracle/harvestinglabouradvancerate_db_oracle.php");
    class harcontadvance_report
    {	
        public $connection;
        public $installment;
        public function __construct(&$connection,$installment)
        {
            $this->connection = $connection;
            $this->installment = $installment;
        }
        function seasonyear()
        {
            $query = "select name_unicode
            from season t
            where t.seasonid=".$_SESSION['finalreportperiodid'];
            $result = oci_parse($this->connection, $query);             
            $r = oci_execute($result);
            if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                return $row['NAME_UNICODE'];
            }
            else
            {
                return '';
            }
        }
        function reportheader(&$pdf,&$liney)
        {
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $pdf->multicell(100,10,'प्रति,',0,'L',false,1,10,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->multicell(100,10,'मा. कार्यकारी संचालक साहेब,',0,'L',false,1,10,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->multicell(100,10,'नाशिक स.सा.का.लि., पळसे',0,'L',false,1,10,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->multicell(200,10,'         विषय : गळीत हंगाम सन '.$this->seasonyear().' चे लेबर भरती अॅडव्हान्स बाबत',0,'L',false,1,10,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->multicell(100,10,'महाशय, ',0,'L',false,1,10,$liney,true,0,false,true,10);
            $liney = $liney+7;
            if ($this->installment==0)
	        {
                $html = '<span style="text-align:justify;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                विनंतीपूर्वक कळवू इच्छितो की, गळीत हंगाम सन '.$this->seasonyear().' साठी नांदगाव, चाळीसगाव, कळवण, निफाड विभागात टायर गाडी, ट्रॅक्टर टायर, वाहन टोळ्यांचे, डोके सेंटरचे पहिला हफ्ता, दुसरा हफ्ता व तिसऱ्या हफ्त्याची रक्कम खालीलप्रमाणे
                </span>';
                $pdf->writeHTML($html, true, 0, true, true);
                $liney = $liney+5;
            }
            elseif ($this->installment==1)
	        {
                $html = '<span style="text-align:justify;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                विनंतीपूर्वक कळवू इच्छितो की, गळीत हंगाम सन '.$this->seasonyear().' साठी नांदगाव, चाळीसगाव, कळवण, निफाड विभागात टायर गाडी, ट्रॅक्टर टायर, वाहन टोळ्यांचे, डोके सेंटरचे पहिल्या हफ्त्याची रक्कम खालीलप्रमाणे
                </span>';
                $pdf->writeHTML($html, true, 0, true, true);
                $liney = $liney+10;
            }
            elseif ($this->installment==2)
	        {
                $html = '<span style="text-align:justify;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                विनंतीपूर्वक कळवू इच्छितो की, गळीत हंगाम सन '.$this->seasonyear().' साठी नांदगाव, चाळीसगाव, कळवण, निफाड विभागात टायर गाडी, ट्रॅक्टर टायर, वाहन टोळ्यांचे, डोके सेंटरचे दुसऱ्या हफ्त्याची रक्कम खालीलप्रमाणे
                </span>';
                $pdf->writeHTML($html, true, 0, true, true);
                $liney = $liney+10;
            }
            elseif ($this->installment==3)
	        {
                $html = '<span style="text-align:justify;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                विनंतीपूर्वक कळवू इच्छितो की, गळीत हंगाम सन '.$this->seasonyear().' साठी नांदगाव, चाळीसगाव, कळवण, निफाड विभागात टायर गाडी, ट्रॅक्टर टायर, वाहन टोळ्यांचे, डोके सेंटरचे तिसऱ्या हफ्त्याची रक्कम खालीलप्रमाणे
                </span>';
                $pdf->writeHTML($html, true, 0, true, true);
                $liney = $liney+10;
            }
        }
        function printpageheader(&$pdf,&$liney)
        {
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $liney = $liney+10;
            $pdf->line(10,$liney,290,$liney);
            $pdf->multicell(15,10,'अ नं.',0,'L',false,1,10,$liney,true,0,false,true,10);
            $pdf->multicell(15,10,'कोड नं.',0,'L',false,1,25,$liney,true,0,false,true,10);
            $pdf->multicell(45,10,'कंत्राटदाराचे नाव',0,'L',false,1,40,$liney,true,0,false,true,10);
            $pdf->multicell(25,20,'गाव',0,'L',false,1,85,$liney,true,0,false,true,10);
            $pdf->multicell(25,20,'दिलेला करार',0,'L',false,1,130,$liney,true,0,false,true,10);
            $pdf->multicell(20,20,'एकूण कोयता संख्या',0,'L',false,1,170,$liney,true,0,false,true,10);
            if ($this->installment==0)
            {
                $pdf->multicell(25,20,'पहिला अॅड. हप्ता',0,'L',false,1,190,$liney,true,0,false,true,10);
                $pdf->multicell(25,20,'दुसरा अॅड. हप्ता',0,'L',false,1,215,$liney,true,0,false,true,10);
                $pdf->multicell(25,20,'तिसरा अॅड. हप्ता',0,'L',false,1,240,$liney,true,0,false,true,10);
                $pdf->multicell(25,20,'एकूण रक्कम',0,'L',false,1,265,$liney,true,0,false,true,10);
            }
            elseif ($this->installment==1)
            {
                $pdf->multicell(25,20,'पहिला अॅड. हप्ता',0,'L',false,1,185,$liney,true,0,false,true,10);
            }
            elseif ($this->installment==2)
            {
                $pdf->multicell(25,20,'दुसरा अॅड. हप्ता',0,'L',false,1,185,$liney,true,0,false,true,10);
            }
            elseif ($this->installment==3)
            {
                $pdf->multicell(25,20,'तिसरा अॅड. हप्ता',0,'L',false,1,185,$liney,true,0,false,true,10);
            }
            $liney = $liney+10;
            $pdf->line(110,$liney,165,$liney);
            $pdf->SetFont('siddhanta', '', 10, '', true);
            $pdf->multicell(15,20,'बैल टायर',0,'R',false,1,105,$liney,true,0,false,true,10);
            $pdf->multicell(15,20,'ट्रॅक्टर टायर',0,'R',false,1,120,$liney,true,0,false,true,10);
            $pdf->multicell(15,20,'वाहन टोळी',0,'R',false,1,135,$liney,true,0,false,true,10);
            $pdf->multicell(15,20,'डोकी सेंटर',0,'R',false,1,150,$liney,true,0,false,true,10);
            $liney = $liney+10;
            $pdf->line(10,$liney,290,$liney);
            
        }
        function detail(&$pdf,&$liney)
        {
            $query ="select n.*
            from
            (select z.contractorzonecode 
            from contractorzone z,contract c 
            where z.contractorzonecode=c.contractorzonecode
            and c.seasonid=".$_SESSION['finalreportperiodid']."
            group by z.contractorzonecode)z,contractorzone n
            where z.contractorzonecode=n.contractorzonecode
            order by n.contractorzonecode";
            $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
            $g_nooflabours=0;
            $g_totaladvance=0;
            $g_firstinsttot=0;
            $g_secondinsttot=0;
            $g_thirdinsttot=0;
            $g_totaladv=0;
            $g_bulluck=0;
            $g_tractor=0;
            $g_truck=0;
            $g_doki=0;
            $g_koyata=0;
            $i=0;
            while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                if ($i!=0)
                {
                    $pdf->line(10,$liney+5,290,$liney+5);
                    $pdf->addpage();
                    $liney = 20;
                    $this->printpageheader($pdf,$liney);
                }
                $i++;
                if ($this->installment==0 and $liney >= 170)
                {
                    $pdf->line(10,$liney+5,290,$liney+5);
                    $pdf->addpage();
                    $liney = 20;
                    $this->printpageheader($pdf,$liney);
                }
                elseif ($this->installment>0 and $liney >= 260)
                {
                    $pdf->line(10,$liney+5,290,$liney+5);
                    $pdf->addpage();
                    $liney = 20;
                    $this->printpageheader($pdf,$liney);
                }
                $pdf->SetFont('siddhanta', '', 11, '', true);
                $pdf->multicell(50,10,'विभाग : '.$row['CONTRACTORZONENAME_UNICODE'],0,'L',false,1,25,$liney,true,0,false,true,10);
                //$liney = $liney+7;
                $query1 = "select t.seasonid,substr(s.refcode,1,1) as type,t.servicecontractorid,s.NAME_UNICODE,s.refcode
                ,sum(doki) as doki
                ,sum(truck) as truck
                ,sum(bulluck) as bulluck
                ,sum(tractor) as tractor
                ,sum((doki+truck+bulluck+tractor)*t.firstinstallmentperhead) as firstinstallment
                ,sum((doki+truck+bulluck+tractor)*t.secondinstallmentperhead) as secondinstallment
                ,sum((doki+truck+bulluck+tractor)*t.thirdinstallementperhead) as thirdinstallment
                ,sum(koyata) as koyata
                from (
                select t.seasonid
                ,d.servicecontractorid
                ,case when d.transportationuptovehicleid=248769412 then d.noofvehicles else 0 end doki
                ,case when d.transportationuptovehicleid=248769559 then d.noofvehicles else 0 end truck
                ,case when d.transportationuptovehicleid=125478451 then d.noofvehicles else 0 end bulluck
                ,case when d.transportationuptovehicleid=248769560 then d.noofvehicles else 0 end tractor
                ,d.noofvehicles*r.labourpergroup as koyata
                ,r.firstinstallmentperhead
                ,r.secondinstallmentperhead
                ,r.thirdinstallementperhead
                from contract t,contractharvestdetail d,harvestinglabouradvancerate r
                where t.contractid=d.contractid 
                and t.active=1 and d.active=1
                and d.transportationuptovehicleid=r.labourcategorycode
                and r.yearperiodcode=t.seasonid
                and isadvance=1 
                and t.seasonid=".$_SESSION['finalreportperiodid']."
                and t.contractorzonecode=".$row['CONTRACTORZONECODE']."
                )t,servicecontractor s
                where t.servicecontractorid=s.SERVICECONTRACTORID
                group by t.seasonid,substr(s.refcode,1,1),t.servicecontractorid,s.NAME_UNICODE,s.refcode
                order by substr(s.refcode,1,1),s.refcode,s.NAME_UNICODE";
                $result1 = oci_parse($this->connection, $query1);             $r = oci_execute($result1);
                $i=1;
                $nooflabours=0;
                $totaladvance=0;
                $firstinsttot=0;
                $secondinsttot=0;
                $thirdinsttot=0;
                $totaladv=0;
                $bulluck=0;
                $tractor=0;
                $truck=0;
                $doki=0;
                $koyata=0;
                $type='';
                $t_nooflabours=0;
                $t_totaladvance=0;
                $t_firstinsttot=0;
                $t_secondinsttot=0;
                $t_thirdinsttot=0;
                $t_totaladv=0;
                $t_bulluck=0;
                $t_tractor=0;
                $t_truck=0;
                $t_doki=0;
                $t_koyata=0;
                while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    if ($type!='')
                    {
                        if ($row1['TYPE']!=$type)
                        {
                            $liney = $liney+7;
                            $pdf->line(10,$liney,290,$liney);
                            $liney = $liney+3;
                            $pdf->SetFont('siddhanta', '', 11, '', true);
                            $pdf->multicell(100,10,$type.' एकूण',0,'L',false,1,25,$liney,true,0,false,true,10);
                            $pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
                            $pdf->multicell(15,10,$t_bulluck,0,'R',false,1,105,$liney,true,0,false,true,10);
                            $pdf->multicell(15,10,$t_tractor,0,'R',false,1,120,$liney,true,0,false,true,10);
                            $pdf->multicell(15,10,$t_truck,0,'R',false,1,135,$liney,true,0,false,true,10);
                            $pdf->multicell(15,10,$t_doki,0,'R',false,1,150,$liney,true,0,false,true,10);
                            $pdf->multicell(20,10,$t_koyata,0,'R',false,1,160,$liney,true,0,false,true,10);
                            
                            if ($this->installment==0)
                            {
                                $pdf->multicell(25,10,$t_firstinsttot,0,'R',false,1,185,$liney,true,0,false,true,10);
                                $pdf->multicell(25,10,$t_secondinsttot,0,'R',false,1,210,$liney,true,0,false,true,10);
                                $pdf->multicell(25,10,$t_thirdinsttot,0,'R',false,1,235,$liney,true,0,false,true,10);
                                $pdf->multicell(25,10,$t_totaladv,0,'R',false,1,265,$liney,true,0,false,true,10);    
                            }
                            elseif ($this->installment==1)
                            {
                                $pdf->multicell(25,10,$t_firstinsttot,0,'R',false,1,180,$liney,true,0,false,true,10);
                            }
                            elseif ($this->installment==2)
                            {
                                $pdf->multicell(25,10,$t_secondinsttot,0,'R',false,1,180,$liney,true,0,false,true,10);
                            }
                            elseif ($this->installment==3)
                            {
                                $pdf->multicell(25,10,$t_thirdinsttot,0,'R',false,1,180,$liney,true,0,false,true,10);
                            }
                            $t_nooflabours=0;
                            $t_totaladvance=0;
                            $t_firstinsttot=0;
                            $t_secondinsttot=0;
                            $t_thirdinsttot=0;
                            $t_totaladv=0;
                            $t_bulluck=0;
                            $t_tractor=0;
                            $t_truck=0;
                            $t_doki=0;
                            $t_koyata=0;
                            $pdf->line(10,$liney+5,290,$liney+5);
                            $pdf->addpage();
                            $liney = 20;
                            $this->printpageheader($pdf,$liney);
                        }
                    }
                    $type=$row1['TYPE'];
                    $liney = $liney+5;
                    $pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
                    $pdf->multicell(15,10,$i++,0,'L',false,1,10,$liney,true,0,false,true,10);
                    $pdf->multicell(15,10,$row1['REFCODE'],0,'L',false,1,25,$liney,true,0,false,true,10);
                    $pdf->SetFont('siddhanta', '', 11, '', true);
                    $pdf->multicell(50,10,$row1['NAME_UNICODE'],0,'L',false,1,40,$liney,true,0,false,true,10);
                    //$pdf->multicell(25,5,$row1['ADDRESS'],0,'L',false,1,85,$liney,true,0,false,true,5);
                    $pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
                    $pdf->multicell(15,10,$row1['BULLUCK'],0,'R',false,1,105,$liney,true,0,false,true,10);
                    $pdf->multicell(15,10,$row1['TRACTOR'],0,'R',false,1,120,$liney,true,0,false,true,10);
                    $pdf->multicell(15,10,$row1['TRUCK'],0,'R',false,1,135,$liney,true,0,false,true,10);
                    $pdf->multicell(15,10,$row1['DOKI'],0,'R',false,1,150,$liney,true,0,false,true,10);
                    $pdf->multicell(20,10,$row1['KOYATA'],0,'R',false,1,160,$liney,true,0,false,true,10);
                    $bulluck+=$row1['BULLUCK'];
                    $tractor+=$row1['TRACTOR'];
                    $truck+=$row1['TRUCK'];
                    $doki+=$row1['DOKI'];
                    $koyata+=$row1['KOYATA'];
                    $t_bulluck+=$row1['BULLUCK'];
                    $t_tractor+=$row1['TRACTOR'];
                    $t_truck+=$row1['TRUCK'];
                    $t_doki+=$row1['DOKI'];
                    $t_koyata+=$row1['KOYATA'];
                    if ($this->installment==0)
                    {
                        $pdf->multicell(20,10,$row1['FIRSTINSTALLMENT'],0,'R',false,1,190,$liney,true,0,false,true,10);
                        $pdf->multicell(20,10,$row1['SECONDINSTALLMENT'],0,'R',false,1,215,$liney,true,0,false,true,10);
                        $pdf->multicell(20,10,$row1['THIRDINSTALLMENT'],0,'R',false,1,240,$liney,true,0,false,true,10);
                        $firstinsttot+=$row1['FIRSTINSTALLMENT'];
                        $secondinsttot+=$row1['SECONDINSTALLMENT'];
                        $thirdinsttot+=$row1['THIRDINSTALLMENT'];
                        $adv=$row1['FIRSTINSTALLMENT']+$row1['SECONDINSTALLMENT']+$row1['THIRDINSTALLMENT'];
                        $totaladv+=$adv;
                        $t_firstinsttot+=$row1['FIRSTINSTALLMENT'];
                        $t_secondinsttot+=$row1['SECONDINSTALLMENT'];
                        $t_thirdinsttot+=$row1['THIRDINSTALLMENT'];
                        $t_adv=$row1['FIRSTINSTALLMENT']+$row1['SECONDINSTALLMENT']+$row1['THIRDINSTALLMENT'];
                        $t_totaladv+=$adv;
                        $pdf->multicell(25,10,$adv,0,'R',false,1,265,$liney,true,0,false,true,10);
                    }
                    elseif ($this->installment==1)
                    {
                        $pdf->multicell(20,10,$row1['FIRSTINSTALLMENT'],0,'R',false,1,185,$liney,true,0,false,true,10);
                        $firstinsttot+=$row1['FIRSTINSTALLMENT'];
                        $t_firstinsttot+=$row1['FIRSTINSTALLMENT'];
                    }
                    elseif ($this->installment==2)
                    {
                        $pdf->multicell(20,10,$row1['SECONDINSTALLMENT'],0,'R',false,1,185,$liney,true,0,false,true,10);
                        $secondinsttot+=$row1['SECONDINSTALLMENT'];
                        $t_secondinsttot+=$row1['SECONDINSTALLMENT'];
                    }
                    elseif ($this->installment==3)
                    {
                        $pdf->multicell(20,10,$row1['THIRDINSTALLMENT'],0,'R',false,1,185,$liney,true,0,false,true,10);
                        $thirdinsttot+=$row1['THIRDINSTALLMENT'];
                        $t_thirdinsttot+=$row1['THIRDINSTALLMENT'];
                    }
                    $pdf->line(10,$liney,290,$liney);
                    if ($this->installment==0 and $liney >= 170)
                    {
                        $pdf->line(10,$liney+5,290,$liney+5);
                        $pdf->addpage();
                        $liney = 20;
                        $this->printpageheader($pdf,$liney);
                    }
                    elseif ($this->installment>0 and $liney >= 260)
                    {
                        $pdf->line(10,$liney+5,290,$liney+5);
                        $pdf->addpage();
                        $liney = 20;
                        $this->printpageheader($pdf,$liney);
                    }
                }

                if ($this->installment==0 and $liney >= 170)
                {
                    $pdf->line(10,$liney+5,290,$liney+5);
                    $pdf->addpage();
                    $liney = 20;
                    $this->printpageheader($pdf,$liney);
                }
                elseif ($this->installment>0 and $liney >= 260)
                {
                    $pdf->line(10,$liney+5,290,$liney+5);
                    $pdf->addpage();
                    $liney = 20;
                    $this->printpageheader($pdf,$liney);
                }
                $liney = $liney+7;
                $pdf->line(10,$liney,290,$liney);
                $liney = $liney+3;
                $pdf->SetFont('siddhanta', '', 11, '', true);
                $pdf->multicell(100,10,$type.' एकूण',0,'L',false,1,25,$liney,true,0,false,true,10);
                $pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
                $pdf->multicell(15,10,$t_bulluck,0,'R',false,1,105,$liney,true,0,false,true,10);
                $pdf->multicell(15,10,$t_tractor,0,'R',false,1,120,$liney,true,0,false,true,10);
                $pdf->multicell(15,10,$t_truck,0,'R',false,1,135,$liney,true,0,false,true,10);
                $pdf->multicell(15,10,$t_doki,0,'R',false,1,150,$liney,true,0,false,true,10);
                $pdf->multicell(20,10,$t_koyata,0,'R',false,1,160,$liney,true,0,false,true,10);
                
                if ($this->installment==0)
                {
                    $pdf->multicell(25,10,$t_firstinsttot,0,'R',false,1,185,$liney,true,0,false,true,10);
                    $pdf->multicell(25,10,$t_secondinsttot,0,'R',false,1,210,$liney,true,0,false,true,10);
                    $pdf->multicell(25,10,$t_thirdinsttot,0,'R',false,1,235,$liney,true,0,false,true,10);
                    $pdf->multicell(25,10,$t_totaladv,0,'R',false,1,265,$liney,true,0,false,true,10);    
                }
                elseif ($this->installment==1)
                {
                    $pdf->multicell(25,10,$t_firstinsttot,0,'R',false,1,180,$liney,true,0,false,true,10);
                }
                elseif ($this->installment==2)
                {
                    $pdf->multicell(25,10,$t_secondinsttot,0,'R',false,1,180,$liney,true,0,false,true,10);
                }
                elseif ($this->installment==3)
                {
                    $pdf->multicell(25,10,$t_thirdinsttot,0,'R',false,1,180,$liney,true,0,false,true,10);
                }
                $t_nooflabours=0;
                $t_totaladvance=0;
                $t_firstinsttot=0;
                $t_secondinsttot=0;
                $t_thirdinsttot=0;
                $t_totaladv=0;
                $t_bulluck=0;
                $t_tractor=0;
                $t_truck=0;
                $t_doki=0;
                $t_koyata=0;
                $liney = $liney+7;
                $pdf->line(10,$liney,290,$liney);
                $liney = $liney+3;
                if ($this->installment==0 and $liney >= 170)
                {
                    $pdf->line(10,$liney+5,290,$liney+5);
                    $pdf->addpage();
                    $liney = 20;
                    $this->printpageheader($pdf,$liney);
                }
                elseif ($this->installment>0 and $liney >= 260)
                {
                    $pdf->line(10,$liney+5,290,$liney+5);
                    $pdf->addpage();
                    $liney = 20;
                    $this->printpageheader($pdf,$liney);
                }
                $pdf->SetFont('siddhanta', '', 11, '', true);
                $pdf->multicell(100,10,$row['CONTRACTORZONENAME_UNICODE'].' विभाग एकूण',0,'L',false,1,25,$liney,true,0,false,true,10);
                if ($this->installment==0 and $liney >= 170)
            {
                $pdf->line(10,$liney+5,290,$liney+5);
                $pdf->addpage();
                $liney = 20;
                $this->printpageheader($pdf,$liney);
            }
            elseif ($this->installment>0 and $liney >= 260)
            {
                $pdf->line(10,$liney+5,290,$liney+5);
                $pdf->addpage();
                $liney = 20;
                $this->printpageheader($pdf,$liney);
            }
                $pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
                $pdf->multicell(15,10,$bulluck,0,'R',false,1,105,$liney,true,0,false,true,10);
                $pdf->multicell(15,10,$tractor,0,'R',false,1,120,$liney,true,0,false,true,10);
                $pdf->multicell(15,10,$truck,0,'R',false,1,135,$liney,true,0,false,true,10);
                $pdf->multicell(15,10,$doki,0,'R',false,1,150,$liney,true,0,false,true,10);
                $pdf->multicell(20,10,$koyata,0,'R',false,1,160,$liney,true,0,false,true,10);

                if ($this->installment==0)
                {
                    $pdf->multicell(25,10,$firstinsttot,0,'R',false,1,185,$liney,true,0,false,true,10);
                    $pdf->multicell(25,10,$secondinsttot,0,'R',false,1,210,$liney,true,0,false,true,10);
                    $pdf->multicell(25,10,$thirdinsttot,0,'R',false,1,235,$liney,true,0,false,true,10);
                    $pdf->multicell(25,10,$totaladv,0,'R',false,1,265,$liney,true,0,false,true,10);    
                }
                elseif ($this->installment==1)
                {
                    $pdf->multicell(25,10,$firstinsttot,0,'R',false,1,180,$liney,true,0,false,true,10);
                }
                elseif ($this->installment==2)
                {
                    $pdf->multicell(25,10,$secondinsttot,0,'R',false,1,180,$liney,true,0,false,true,10);
                }
                elseif ($this->installment==3)
                {
                    $pdf->multicell(25,10,$thirdinsttot,0,'R',false,1,180,$liney,true,0,false,true,10);
                }
                $liney = $liney+7;
                $pdf->line(10,$liney,290,$liney);
                $g_nooflabours+=$nooflabours;
                $g_totaladvance+=$totaladvance;
                $g_firstinsttot+=$firstinsttot;
                $g_secondinsttot+=$secondinsttot;
                $g_thirdinsttot+=$thirdinsttot;
                $g_totaladv+=$totaladv;
                $g_bulluck+=$bulluck;
                $g_tractor+=$tractor;
                $g_truck+=$truck;
                $g_doki+=$doki;
                $g_koyata+=$koyata;
            }
            if ($this->installment==0 and $liney >= 170)
            {
                $pdf->line(10,$liney+5,290,$liney+5);
                $pdf->addpage();
                $liney = 20;
                $this->printpageheader($pdf,$liney);
            }
            elseif ($this->installment>0 and $liney >= 260)
            {
                $pdf->line(10,$liney+5,290,$liney+5);
                $pdf->addpage();
                $liney = 20;
                $this->printpageheader($pdf,$liney);
            }
            //$liney = $liney+7;
            //$pdf->line(10,$liney,290,$liney);
            //$liney = $liney+3;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $pdf->multicell(30,10,'एकूण एकंदर',0,'L',false,1,25,$liney,true,0,false,true,10);
            $pdf->SetFont('SakalMarathiNormal922', '', 10, '', true);
            $pdf->multicell(15,10,$g_bulluck,0,'R',false,1,105,$liney,true,0,false,true,10);
            $pdf->multicell(15,10,$g_tractor,0,'R',false,1,120,$liney,true,0,false,true,10);
            $pdf->multicell(15,10,$g_truck,0,'R',false,1,135,$liney,true,0,false,true,10);
            $pdf->multicell(15,10,$g_doki,0,'R',false,1,150,$liney,true,0,false,true,10);
            $pdf->multicell(20,10,$g_koyata,0,'R',false,1,160,$liney,true,0,false,true,10);

            if ($this->installment==0)
            {
                $pdf->multicell(25,10,$g_firstinsttot,0,'R',false,1,185,$liney,true,0,false,true,10);
                $pdf->multicell(25,10,$g_secondinsttot,0,'R',false,1,210,$liney,true,0,false,true,10);
                $pdf->multicell(25,10,$g_thirdinsttot,0,'R',false,1,235,$liney,true,0,false,true,10);
                $pdf->multicell(25,10,$g_totaladv,0,'R',false,1,265,$liney,true,0,false,true,10);    
            }
            elseif ($this->installment==1)
            {
                $pdf->multicell(25,10,$g_firstinsttot,0,'R',false,1,180,$liney,true,0,false,true,10);
            }
            elseif ($this->installment==2)
            {
                $pdf->multicell(25,10,$g_secondinsttot,0,'R',false,1,180,$liney,true,0,false,true,10);
            }
            elseif ($this->installment==3)
            {
                $pdf->multicell(25,10,$g_thirdinsttot,0,'R',false,1,180,$liney,true,0,false,true,10);
            }
            $liney = $liney+7;
            $pdf->line(10,$liney,290,$liney);
        }
    }
?>