<?php
    include_once("../api_oracle/contract_db_oracle.php");
	//include_once("../api_oracle/area_db_oracle.php");
	include_once("../api_oracle/contracttransportdetail_db_oracle.php");
	include_once("../api_oracle/contractharvestdetail_db_oracle.php");
	include_once("../api_oracle/contracttransporttrailerdetail_db_oracle.php");
	include_once("../api_oracle/contractguarantordetail_db_oracle.php");
	include_once("../api_oracle/servicecontractor_db_oracle.php");
	include_once("../api_oracle/contractperformancedetail_db_oracle.php");
    include_once("../api_oracle/contractnomineedetail_db_oracle.php");
    include_once("../api_oracle/contractadvancedetail_db_oracle.php");
    include_once("../api_oracle/contractreceiptdetail_db_oracle.php");
    include_once("../api_oracle/contractguarantordetail_db_oracle.php");
class contract_8
{	
	public $contractid;
    public $connection;
    
	public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

    function printpageheader(&$pdf,&$liney,$contractid)
    {
    	/* require("../info/phpsqlajax_dbinfo.php");
    	// Opens a this->connection to a MySQL server
        $this->connection=mysqli_connect($hostname_rawmaterial, $username_rawmaterial, $password_rawmaterial, $database_rawmaterial);
        // Check this->connection
        if (mysqli_connect_errno())
        {
            echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication error1</span>';
            exit;
        }
        mysqli_query($this->connection,'SET NAMES UTF8'); */
        $contract1 = new contract($this->connection);

		if ($contract1->fetch($contractid))
		{
			$pdf->SetFont('siddhanta', '', 15, '', true);
            $pdf->multicell(150,10,'पावती',0,'L',false,1,85,$liney,true,0,false,true,10);
            $liney = $liney+15;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $curdate = date('d/m/Y');
			$pdf->multicell(180,10,'आज तारीख '.$curdate.' रोज चे दिवशी मौजे , ता.बारामती, जि.पुणे',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+7;

            if ($contract1->contractcategoryid == 521478963)
            {
                $pdf->SetFont('siddhanta', '', 13, '', true);
                $pdf->multicell(70,10,'लिहून घेणार,',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->SetFont('siddhanta', '', 11, '', true);
                $liney = $liney+7;
                $pdf->multicell(150,10,'नाशिक सहकारी साखर कारखाना लि., पळसे, ',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+7;
                $pdf->multicell(150,10,', ता.बारामती, जि.पुणे',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+7;
                $contracttransportdetail1 = new contracttransportdetail($this->connection);
                $contracttransportdetail1 = $this->contracttransportdetail($this->connection,$contract1->contractid);
                $servicecontractor1 = new servicecontractor($this->connection);
                $servicecontractor1->fetch($contract1->servicecontractorid);
                $pdf->SetFont('siddhanta', '', 13, '', true);
                $pdf->multicell(70,10,'लिहून देणार,',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+7;
                $pdf->SetFont('siddhanta', '', 11, '', true);
                $pdf->multicell(100,10,'श्री '.$servicecontractor1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(30,$liney,200,$liney);
                $liney = $liney+2;
                $pdf->multicell(25,10,'वय: '.$servicecontractor1->age,0,'L',false,1,30,$liney,true,0,false,true,10);
                $pdf->multicell(10,10,'धंदा:',0,'L',false,1,100,$liney,true,0,false,true,10);
                $pdf->multicell(30,10,$servicecontractor1->professionname_unicode,0,'L',false,1,120,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(37,$liney,100,$liney);
                $pdf->line(110,$liney,200,$liney);
                $liney = $liney+2;
                /* $area2 = new area($this->connection);
                $area2->fetch($servicecontractor1->areaid); */
                
                
                $pdf->multicell(15,10,'मु.पो.:',0,'L',false,1,30,$liney,true,0,false,true,10);
                $pdf->multicell(120,10,$servicecontractor1->address_unicode,0,'L',false,1,45,$liney,true,0,false,true,10);

                /* $pdf->multicell(10,10,'ता.:',0,'L',false,1,100,$liney,true,0,false,true,10);
                $pdf->multicell(30,10,$area2->subdistrictname_unicode,0,'L',false,1,110,$liney,true,0,false,true,10);

                $pdf->multicell(10,10,'जि.:',0,'L',false,1,150,$liney,true,0,false,true,10);
                $pdf->multicell(40,10,$area2->districtname_unicode,0,'L',false,1,160,$liney,true,0,false,true,10);
 */
                $liney = $liney+5;
                $pdf->line(43,$liney,200,$liney);
                /* $pdf->line(105,$liney,145,$liney);
                $pdf->line(157,$liney,200,$liney); */
                $liney = $liney+2;
                $liney = $liney+10;
                $contractreceiptdetail1 = new contractreceiptdetail($this->connection);
                $contractreceiptdetail1 = $this->contractreceiptdetail($this->connection,$contractid,1);
                $pdf->SetFont('siddhanta', '', 11, '', true);
                
                $wrdamt = NumberToWords(number_format_indian($contractreceiptdetail1->chequeamount,0,false,false),1);
                $html = '<span style="text-align:justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                वरील पावती लिहून देतो की, मी व माझे सहभागीदार सन <u>'.$contract1->seasonname_unicode.'</u> 
                च्या गळीत हंगामात ऊस तोड वाहतूक करण्यासाठी श्री छत्रपति सहकारी साखर कारखाना लि. ,
                येथे येणार आहोत. ऊस उत्पादकाकडून ऊस तोड वाहतुकीचे काम मिळवणार आहोत 
                ऊस तो हे काम मिळवून देण्याचे काम तुम्ही आम्हाला करणार आहात.ऊस तोड वाहतुकीस येण्यासाठी बैलगाड्या, वाहन,
                व कोयते वगैरेची पूर्व तयारी करण्यासाठी मला पैशाची गरज आहे. यासाठी तुमचेकडून 
                अॅडव्हान्स <u>Rs'.$contractreceiptdetail1->chequeamount.' 
                (अक्षरी '.$wrdamt.')</u> आज रोजी रोख / चेकने मिळाला. श्री छत्रपति सहकारी साखर कारखाना लि. , ला ऊस पुरवणाऱ्या शेतकऱ्यांचा 
                ऊस तोडून त्याच्या तोडणी वाहतूक बिलामधून याच हंगामात अॅडव्हान्सची परतफेड करावयाची आहे.
                त्यासाठी तुमच्याबरोबर वेगळा करार केलेला आहे. येणेप्रमाणे 
                अॅडव्हान्स मिळाला तक्रार नाही ही पावती</p></span>';
                $pdf->writeHTML($html, true, 0, true, true);
                $liney = $liney+50;

                $pdf->multicell(40,10,'स्थळ: ',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(40,10,'सही',0,'L',false,1,135,$liney,true,0,false,true,10);
                $liney = $liney+7;
                $curdate = date('d/m/Y');
                $pdf->multicell(50,10,'दिनांक:'.$curdate,0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->rect(155,$liney,10,10);
                $liney = $liney+10;
            }
            elseif ($contract1->contractcategoryid == 785415263)
            {
                $pdf->SetFont('siddhanta', '', 13, '', true);
                $pdf->multicell(70,10,'लिहून घेणार,',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->SetFont('siddhanta', '', 11, '', true);
                $liney = $liney+7;
                $pdf->multicell(150,10,'नाशिक सहकारी साखर कारखाना लि., पळसे',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+7;
                $pdf->multicell(150,10,', ता.बारामती, जि.पुणे',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+7;
                $contractharvestdetail1 = new contractharvestdetail($this->connection);
                $contractharvestdetail1 = $this->contractharvestdetail($this->connection,$contract1->contractid);
                $servicecontractor1 = new servicecontractor($this->connection);
                $servicecontractor1->fetch($contractharvestdetail1->servicecontractorid);
                $pdf->SetFont('siddhanta', '', 13, '', true);
                $pdf->multicell(70,10,'लिहून देणार,',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+7;
                $pdf->SetFont('siddhanta', '', 11, '', true);
                $pdf->multicell(100,10,'श्री '.$servicecontractor1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(30,$liney,200,$liney);
                $liney = $liney+2;
                $pdf->multicell(25,10,'वय: '.$servicecontractor1->age,0,'L',false,1,30,$liney,true,0,false,true,10);
                $pdf->multicell(10,10,'धंदा:',0,'L',false,1,100,$liney,true,0,false,true,10);
                $pdf->multicell(30,10,$servicecontractor1->professionname_unicode,0,'L',false,1,120,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(37,$liney,100,$liney);
                $pdf->line(110,$liney,200,$liney);
                $liney = $liney+2;
                /* $area2 = new area($this->connection);
                $area2->fetch($servicecontractor1->areaid); */
                
                
                $pdf->multicell(15,10,'मु.पो.:',0,'L',false,1,30,$liney,true,0,false,true,10);
                $pdf->multicell(120,10,$servicecontractor1->address_unicode,0,'L',false,1,45,$liney,true,0,false,true,10);

                
                $liney = $liney+5;
                $pdf->line(43,$liney,200,$liney);
                $liney = $liney+2;
                $liney = $liney+10;

                $contractreceiptdetail1 = new contractreceiptdetail($this->connection);
                $contractreceiptdetail1 = $this->contractreceiptdetail($this->connection,$contractid,1);
                $pdf->SetFont('siddhanta', '', 11, '', true);
                
                $wrdamt = NumberToWords(number_format_indian($contractreceiptdetail1->chequeamount,0,false,false),1);
                $html = '<span style="text-align:justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                कारणे पावती लिहून देतो की, मी व माझे सहभागीदार सन <u>'.$contract1->seasonname_unicode.'</u> 
                च्या गळीत हंगामात ऊस तोड वाहतूक करण्यासाठी श्री छत्रपति सहकारी साखर कारखाना लि. ,
                येथे येणार आहोत. ऊस उत्पादकाकडून ऊस तोड वाहतुकीचे काम मिळवणार आहोत. ऊस तोडणीसाठी येणारे मजुर
                व कोयते वगैरेची पूर्व तयारी करण्यासाठी मला पैशाची गरज आहे. यासाठी तुमचेकडून 
                अॅडव्हान्स <u>Rs'.$contractreceiptdetail1->chequeamount.' 
                (अक्षरी '.$wrdamt.')</u> आज रोजी रोख / चेकने मिळाला.<u>'.$contractreceiptdetail1->bankbranchname_unicode.'
                </u> बँकेवरील चेक नंबर <u>'.$contractreceiptdetail1->chequenumber.'</u> 
                दिनांक <u>'.$contractreceiptdetail1->chequedatetime.'</u> ने प्राप्त झाला / यादीने 
                बँक खाती वर्ग झाला. श्री छत्रपति सहकारी साखर कारखाना लि. , ला ऊस पुरवणाऱ्या शेतकऱ्यांचा 
                ऊस तोडून त्याच्या तोडणी वाहतूक बिलामधून याच हंगामात अॅडव्हान्सची परतफेड करावयाची आहे.
                त्यासाठी तुमच्याबरोबर वेगळा करार केलेला आहे. त्या करारामधील शर्ती अटीनुसार येणेप्रमाणे 
                अॅडव्हान्स मिळाला तक्रार नाही ही पावती</p></span>';
                $pdf->writeHTML($html, true, 0, true, true);
                $liney = $liney+50;

                $pdf->multicell(40,10,'स्थळ: ',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(40,10,'सही',0,'L',false,1,135,$liney,true,0,false,true,10);
                $liney = $liney+7;
                $curdate = date('d/m/Y');
                $pdf->multicell(50,10,'दिनांक:'.$curdate,0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->rect(155,$liney,10,10);
                $liney = $liney+10;
            }
            elseif ($contract1->contractcategoryid == 947845153)
            {
                $contracttransportdetail1 = new contracttransportdetail($this->connection);
                $contracttransportdetail1 = $this->contracttransportdetail($this->connection,$contract1->contractid);
                $servicecontractor1 = new servicecontractor($this->connection);
                $servicecontractor1->fetch($contract1->servicecontractorid);
                $pdf->SetFont('siddhanta', '', 13, '', true);
                $pdf->multicell(70,10,'लिहून घेणार,',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+7;
                $pdf->SetFont('siddhanta', '', 11, '', true);
                $pdf->multicell(100,10,'श्री '.$servicecontractor1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(30,$liney,200,$liney);
                $liney = $liney+2;
                $pdf->multicell(25,10,'वय: '.$servicecontractor1->age,0,'L',false,1,30,$liney,true,0,false,true,10);
                $pdf->multicell(10,10,'धंदा:',0,'L',false,1,100,$liney,true,0,false,true,10);
                $pdf->multicell(30,10,$servicecontractor1->professionname_unicode,0,'L',false,1,120,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(37,$liney,100,$liney);
                $pdf->line(110,$liney,200,$liney);
                $liney = $liney+2;
                $area1 = new area($this->connection);
                $area1->fetch($servicecontractor1->areaid);
                
                
                $pdf->multicell(15,10,'मु.पो.:',0,'L',false,1,30,$liney,true,0,false,true,10);
                $pdf->multicell(30,10,$area1->areaname_unicode,0,'L',false,1,45,$liney,true,0,false,true,10);

                $pdf->multicell(10,10,'ता.:',0,'L',false,1,100,$liney,true,0,false,true,10);
                $pdf->multicell(30,10,$area1->subdistrictname_unicode,0,'L',false,1,110,$liney,true,0,false,true,10);

                $pdf->multicell(10,10,'जि.:',0,'L',false,1,150,$liney,true,0,false,true,10);
                $pdf->multicell(40,10,$area1->districtname_unicode,0,'L',false,1,160,$liney,true,0,false,true,10);

                $liney = $liney+5;
                $pdf->line(43,$liney,100,$liney);
                $pdf->line(105,$liney,145,$liney);
                $pdf->line(157,$liney,200,$liney);
                $liney = $liney+2;
                $liney = $liney+10;

                $contractharvestdetail1 = new contractharvestdetail($this->connection);
                $contractharvestdetail1 = $this->contractharvestdetail($this->connection,$contract1->contractid);
                $servicecontractor1 = new servicecontractor($this->connection);
                $servicecontractor1->fetch($contractharvestdetail1->servicecontractorid);
                $pdf->SetFont('siddhanta', '', 13, '', true);
                $pdf->multicell(70,10,'लिहून देणार,',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+7;
                $pdf->SetFont('siddhanta', '', 11, '', true);
                $pdf->multicell(100,10,'श्री '.$servicecontractor1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(30,$liney,200,$liney);
                $liney = $liney+2;
                $pdf->multicell(25,10,'वय: '.$servicecontractor1->age,0,'L',false,1,30,$liney,true,0,false,true,10);
                $pdf->multicell(10,10,'धंदा:',0,'L',false,1,100,$liney,true,0,false,true,10);
                $pdf->multicell(30,10,$servicecontractor1->professionname_unicode,0,'L',false,1,120,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(37,$liney,100,$liney);
                $pdf->line(110,$liney,200,$liney);
                $liney = $liney+2;
                $area2 = new area($this->connection);
                $area2->fetch($servicecontractor1->areaid);
                
                
                $pdf->multicell(15,10,'मु.पो.:',0,'L',false,1,30,$liney,true,0,false,true,10);
                $pdf->multicell(30,10,$area2->areaname_unicode,0,'L',false,1,45,$liney,true,0,false,true,10);

                $pdf->multicell(10,10,'ता.:',0,'L',false,1,100,$liney,true,0,false,true,10);
                $pdf->multicell(30,10,$area2->subdistrictname_unicode,0,'L',false,1,110,$liney,true,0,false,true,10);

                $pdf->multicell(10,10,'जि.:',0,'L',false,1,150,$liney,true,0,false,true,10);
                $pdf->multicell(40,10,$area2->districtname_unicode,0,'L',false,1,160,$liney,true,0,false,true,10);

                $liney = $liney+5;
                $pdf->line(43,$liney,100,$liney);
                $pdf->line(105,$liney,145,$liney);
                $pdf->line(157,$liney,200,$liney);
                $liney = $liney+2;
                $liney = $liney+10;

                $contractreceiptdetail1 = new contractreceiptdetail($this->connection);
                $contractreceiptdetail1 = $this->contractreceiptdetail($this->connection,$contractid,1);
                $pdf->SetFont('siddhanta', '', 11, '', true);
                
                $wrdamt = NumberToWords(number_format_indian($contractreceiptdetail1->chequeamount,0,false,false),1);
                $html = '<span style="text-align:justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                कारणे पावती लिहून देतो की, मी व माझे सहभागीदार सन <u>'.$contract1->seasonname_unicode.'</u> 
                च्या गळीत हंगामात श्री.'.$servicecontractor1->name_unicode.' रा.'.$area1->areaname_unicode.' 
                यांचा '.$contractharvestdetail1->transportationvehiclename_unicode.' क्रमांक '.$contracttransportdetail1->vehiclenumber.' या 
                वाहनावर ऊस तोडणी कामासाठी येणार असून  त्या कामापोटी मी व माझे तोडणी मजुर 
                यांनी सहभागीदारांच्या वतीने उचल (अॅडव्हान्स) <u>Rs'.$contractreceiptdetail1->chequeamount.' 
                (अक्षरी '.$wrdamt.')</u> घेणेचे निश्चित झालेचे असून त्यापैकी प्रथम / द्वितीय  / तृतीय हप्ता <u>'.$contractreceiptdetail1->bankbranchname_unicode.'
                </u> बँकेवरील चेक/रोख नंबर <u>'.$contractreceiptdetail1->chequenumber.'</u> 
                दिनांक <u>'.$contractreceiptdetail1->chequedatetime.'</u> ने प्राप्त झाला.</p></span>';
                $pdf->writeHTML($html, true, 0, true, true);
                $liney = $liney+50;

            }
            


            $contractguarantordetail1 = new contractguarantordetail($this->connection);
			$contractguarantordetail1 = $this->contractguarantordetail($this->connection,$contract1->contractid,1);
			$servicecontractor_guarantor1 = new servicecontractor($this->connection);
			$servicecontractor_guarantor1->fetch($contractguarantordetail1->servicecontractorid);

            /* $area1 = new area($this->connection);
            $area1->fetch($servicecontractor_guarantor1->areaid); */

            $contractguarantordetail2 = new contractguarantordetail($this->connection);
			$contractguarantordetail2 = $this->contractguarantordetail($this->connection,$contract1->contractid,2);
			$servicecontractor_guarantor2 = new servicecontractor($this->connection);
			$servicecontractor_guarantor2->fetch($contractguarantordetail2->servicecontractorid);

            $pdf->multicell(40,10,'पावती लिहून देणार',0,'L',false,1,110,$liney,true,0,false,true,10);
			$liney = $liney+10;
            $pdf->rect(130,$liney,10,10);
            $liney = $liney+5;
            $pdf->multicell(40,10,'सही',0,'L',false,1,110,$liney,true,0,false,true,10);
            $liney = $liney+10;
			$pdf->multicell(100,10,'नाव:',0,'L',false,1,110,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,$servicecontractor1->name_unicode,0,'L',false,1,125,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(125,$liney,160,$liney);
            
            $liney = $liney+7;
            /* $area2 = new area($this->connection);
            $area2->fetch($servicecontractor_guarantor2->areaid); */
            //$list1 = $contract1->guarantorcontractorlist();
            $i=0;
            $pdf->SetFont('siddhanta', '', 13, '', true);
            $pdf->multicell(35,10,'साक्षीदार',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+10;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $list1 = $contract1->guarantorcontractorlist();
            $i=0;
            $contractguarantordetail1 = new contractguarantordetail($this->connection);
            $servicecontractor_guarantor1 = new servicecontractor($this->connection);
            foreach ($list1 as $value)
            {
                $val = intval($list1[$i]);
                $contractguarantordetail1->fetch($val,1);
                $servicecontractor_guarantor1->fetch($contractguarantordetail1->servicecontractorid);
            
                $pdf->multicell(100,10,++$i.')नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(100,10,$servicecontractor_guarantor1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
                $pdf->multicell(10,10,'सही:',0,'L',false,1,120,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(30,$liney,100,$liney);
                $pdf->line(120,$liney,200,$liney);
                $liney = $liney+5;

            }
		}
    }

    function contracttransportdetail(&$connection,$contractid)
    {
        $contracttransportdetail1 = new contracttransportdetail($connection);
        $query = "select d.contracttransportdetailid from contract c,contracttransportdetail d where c.active=1 and d.active=1 and c.contractid=d.contractid and c.contractid=".$contractid;
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $contracttransportdetail1->fetch($row['CONTRACTTRANSPORTDETAILID']);
            return $contracttransportdetail1;
        }
    }

    function contractharvestdetail(&$connection,$contractid)
    {
        $contractharvestdetail1 = new contractharvestdetail($connection);
        $query = "select d.contractharvestdetailid from contract c,contractharvestdetail d where c.active=1 and d.active=1 and c.contractid=d.contractid and c.contractid=".$contractid;
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $contractharvestdetail1->fetch($row['CONTRACTHARVESTDETAILID']);
            return $contractharvestdetail1;
        }
    }

    function contracttransporttrailerdetail(&$connection,$contracttransportdetailid,$sequencenumber)
    {
        $contracttransporttrailerdetail1 = new contracttransporttrailerdetail($connection);
        $query = "select t.contracttransporttrailerdetailid from contract c,contracttransportdetail d, contracttransporttrailerdetail t where c.active=1 and d.active=1 and t.active=1 and c.contractid=d.contractid and d.contracttransportdetailid=t.contracttransportdetailid and t.contracttransportdetailid=".$contracttransportdetailid;
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contracttransporttrailerdetail1->fetch($row['CONTRACTTRANSPORTTRAILERDETAILID']);
                return $contracttransporttrailerdetail1;
                exit;
            }
            else
            {
                $i++;	
            }
        }
    }

    function contractperformancedetail(&$connection,$contractid)
    {
        $contractperformancedetail1 = new contractperformancedetail($connection);
        $query = "select d.contractperformancedetailid from contract c,contractperformancedetail d where c.active=1 and d.active=1 and c.contractid=d.contractid and c.contractid=".$contractid;
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $contractperformancedetail1->fetch($row['CONTRACTPERFORMANCEDETAILID']);
            return $contractperformancedetail1;
        }
    }

    function contractguarantordetail(&$connection,$contractid,$sequencenumber)
    {
        $contractguarantordetail1 = new contractguarantordetail($connection);
        $query = "select d.contractguarantordetailid from contract c,contractguarantordetail d,servicecontractor t where c.active=1 and d.active=1 and t.active=1 and c.contractid=d.contractid and d.servicecontractorid=t.servicecontractorid and c.contractid=".$contractid." order by t.servicecontractorcategoryid desc,d.contractguarantordetailid";
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contractguarantordetail1->fetch($row['CONTRACTGUARANTORDETAILID']);
                return $contractguarantordetail1;
            }
            else
            {
                $i++;
            }
        }
    }

    function contractnomineedetail(&$connection,$contractid,$sequencenumber)
    {
        $contractnomineedetail1 = new contractnomineedetail($connection);
        $query = "select d.contractnomineedetailid from contract c,contractnomineedetail d,servicecontractor t where c.active=1 and d.active=1 and t.active=1 and c.contractid=d.contractid and d.nomineeid=t.servicecontractorid and c.contractid=".$contractid." order by d.contractnomineedetailid";
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contractnomineedetail1->fetch($row['CONTRACTNOMINEEDETAILID']);
                return $contractnomineedetail1;
            }
            else
            {
                $i++;
            }
        }
    }

    function contractadvancedetail(&$connection,$contractid,$sequencenumber)
    {
        $contractadvancedetail1 = new contractadvancedetail($connection);
        $query = "select d.contractadvancedetailid from contract c,contractadvancedetail d where c.active=1 and d.active=1 and c.contractid=d.contractid and c.contractid=".$contractid." order by d.contractadvancedetailid";
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contractadvancedetail1->fetch($row['CONTRACTADVANCEDETAILID']);
                return $contractadvancedetail1;
            }
            else
            {
                $i++;
            }
        }
    }

    function contractreceiptdetail(&$connection,$contractid,$sequencenumber)
    {
        $contractreceiptdetail1 = new contractreceiptdetail($connection);
        $query = "select d.contractreceiptdetailid from contract c,contractreceiptdetail d where c.active=1 and d.active=1 and c.contractid=d.contractid and c.contractid=".$contractid." order by d.contractreceiptdetailid";
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contractreceiptdetail1->fetch($row['CONTRACTRECEIPTDETAILID']);
                return $contractreceiptdetail1;
            }
            else
            {
                $i++;
            }
        }
    }

    
}
?>