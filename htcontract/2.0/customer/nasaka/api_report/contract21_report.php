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
    include_once("../api_oracle/contractmortgagedetail_db_oracle.php");
    include_once("../api_oracle/contractphotodetail_db_oracle.php");
    include_once("../api_oracle/contractfingerprintdetail_db_oracle.php");
    
class contract_21
{	
	public $contractid;
    public $connection;
    
	public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

    function printpageheader(&$pdf,&$liney,$contractid,$contractadvancedetailid)
    {
        $contract1 = new contract($this->connection);
		if ($contract1->fetch($contractid))
		{
            $liney = 190;
            $pdf->SetFont('siddhanta', '', 13, '', true);
            $pdf->multicell(200,10,'(ऊस उत्पादक सभासद / बिगर सभासदाचे वतीने घ्यावयाचे ऊस तोडणी व वाहतुकीचा करारनामा)',0,'L',false,1,20,$liney,true,0,false,true,10);
            $liney = $liney+10;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $curdate = $contract1->contractdatetime;
			$pdf->multicell(180,10,'(दि.         ते         अखेरच्या मुदती करिता',0,'L',false,1,60,$liney,true,0,false,true,10);
			$liney = $liney+7;
			$contracttransportdetail1 = new contracttransportdetail($this->connection);
			$contracttransportdetail1 = $this->contracttransportdetail($this->connection,$contract1->contractid);
            $servicecontractor1 = new servicecontractor($this->connection);
			$servicecontractor1->fetch($contract1->servicecontractorid); 
            /* $servicecontractor_transportor1 = new servicecontractor($this->connection);
			$servicecontractor_transportor1->fetch($contracttransportdetail1->servicecontractorid); */
            
			$contractharvestdetail1 = new contractharvestdetail($this->connection);
			$contractharvestdetail1 = $this->contractharvestdetail($this->connection,$contract1->contractid);
/* 			$servicecontractor_harvester1 = new servicecontractor($this->connection);
			$servicecontractor_harvester1->fetch($contractharvestdetail1->servicecontractorid);
 */			
            $contractreceiptdetail1 = new contractreceiptdetail($this->connection);
			$contractreceiptdetail1 = $this->contractreceiptdetail($this->connection,$contractid,1);
			$pdf->SetFont('siddhanta', '', 13, '', true);
            $pdf->multicell(70,10,'करार लिहून घेणार,',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->SetFont('siddhanta', '', 11, '', true);
            $liney = $liney+7;
            $pdf->multicell(100,10,'मा. कार्यकारी संचालक',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->multicell(150,10,'नाशिक सहकारी साखर कारखाना लि., श्री संत जनार्दन स्वामी नगर',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->multicell(150,10,'पो.पळसे ता. नाशिक जि. नाशिक',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+7;
			$pdf->SetFont('siddhanta', '', 13, '', true);
            $pdf->multicell(70,10,'लिहून देणार,',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+7;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $pdf->multicell(100,10,'श्री '.$servicecontractor1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->line(30,$liney-2,200,$liney-2);
            $pdf->multicell(25,10,'वय: '.$contract1->age,0,'L',false,1,30,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,'धंदा: ठेकेदारी व शेती',0,'L',false,1,100,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(37,$liney,100,$liney);
            $pdf->line(110,$liney,200,$liney);
            $liney = $liney+2;
            //$area1 = new area($this->connection);
			//$area1->fetch($contract1->areaid);
            
            
            $pdf->multicell(15,10,'मु.पो.:',0,'L',false,1,30,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$contract1->address,0,'L',false,1,45,$liney,true,0,false,true,10);
            $pdf->multicell(20,10,'मोबाईल:',0,'L',false,1,145,$liney,true,0,false,true,10);
			$pdf->multicell(30,10,$servicecontractor1->mobileno,0,'L',false,1,165,$liney,true,0,false,true,10);

			//$pdf->multicell(10,10,'ता.:',0,'L',false,1,100,$liney,true,0,false,true,10);
			//$pdf->multicell(30,10,$area1->subdistrictname_unicode,0,'L',false,1,110,$liney,true,0,false,true,10);

			//$pdf->multicell(10,10,'जि.:',0,'L',false,1,150,$liney,true,0,false,true,10);
			//$pdf->multicell(40,10,$area1->districtname_unicode,0,'L',false,1,160,$liney,true,0,false,true,10);

   			$liney = $liney+5;
			$pdf->line(43,$liney,200,$liney);
            $liney = $liney+2;

			/* $pdf->SetFont('siddhanta', '', 13, '', true);
            $pdf->multicell(70,10,'लिहून देणार,',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+7;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $pdf->multicell(100,10,'श्री '.$servicecontractor_harvester1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
			$liney = $liney+7;
            $pdf->multicell(25,10,'वय: '.$servicecontractor_harvester1->age,0,'L',false,1,30,$liney,true,0,false,true,10);
			$pdf->multicell(10,10,'धंदा:',0,'L',false,1,100,$liney,true,0,false,true,10);
			$pdf->multicell(30,10,$servicecontractor_harvester1->professionname_unicode,0,'L',false,1,120,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(37,$liney,100,$liney);
            $pdf->line(110,$liney,200,$liney);
            $liney = $liney+2;
            $area2 = new area($this->connection);
			$area2->fetch($servicecontractor_harvester1->areaid);
            
            
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
 */
			/* $contractmortgagedetail1 = new contractmortgagedetail($this->connection);
			$contractmortgagedetail1 = $this->contractmortgagedetail($this->connection,$contractid,1);
			$area3 = new area($this->connection);
			$area3->fetch($contractmortgagedetail1->areaid);

			$contractmortgagedetail2 = new contractmortgagedetail($this->connection);
			$contractmortgagedetail2 = $this->contractmortgagedetail($this->connection,$contractid,2);

			if ($contractmortgagedetail1->propertycategoryid == 248796545)
			{
				$property1 = ' घर मिळकत नंबर - <u>'.$contractmortgagedetail1->propertynumber.'</u>';
			}
			elseif ($contractmortgagedetail1->propertycategoryid == 248796692)
			{
				$property1 = ' शेतजमीन मिळकत गट नंबर - <u>'.$contractmortgagedetail1->propertynumber.'</u>';
			}

			if ($contractmortgagedetail2->propertycategoryid == 248796545)
			{
				$property2 = ' घर मिळकत नंबर - <u>'.$contractmortgagedetail2->propertynumber.'</u>';
			}
			elseif ($contractmortgagedetail2->propertycategoryid == 248796692)
			{
				$property2 = ' शेतजमीन मिळकत गट नंबर - <u>'.$contractmortgagedetail2->propertynumber.'</u>';
			}
 */
			$liney = 20;
            $pdf->SetFont('siddhanta', '', 13, '', true);
            
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $pdf->AddPage();
            $html = '<span style="text-align:justify;">
            '.$contract1->vehiclelist().' '.$contract1->harvesteuptolist().'
            </span>';
            $pdf->writeHTML($html, true, 0, true, true);
            $html = '<span style="text-align:justify;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            मी खालील सही करणार ट्रक / ट्रॅक्टर / बैलगाडी नेऊन वाहतुकीचा व तोडणीचा करारनामा खालील अटी व शर्तीवर माझे स्वतःकरिता व माझे सहभागीदाराचे वतीने लिहून देतो की, 
            </span>';
            $pdf->writeHTML($html, true, 0, true, true);
            //$liney = $liney+60;
            $html0 = '<span style="text-align:center;">
            <br>कराराच्या शर्ती व अटी<br></span>';
			$html1 = '<span style="text-align:justify;">
            १)ऊस उत्पादक सभासद / बिगर सभासद कारखान्यास ऊस पुरविताना ऊसाची तोड वाहतूक करून कारखान्यावर पोहोचता करण्याची जबाबदारी त्यांची आहे. ऊसाची तोड वाहतूक यासाठी संचालक मंडळ ठरवतील त्या दराने खर्च कमिशन सभासदांना / बिगर सभासदांना मिळते. ठरवून दिलेल्या कोट्याप्रमाणे काही प्रसंगी सभासदांना / बिगर सभासदांना ऊस तोड वाहतूक करणे अवघड होते. त्यांना ऐनवेळी आमचेबरोबर संपर्क साधणे अवघड जाते. तसेच आम्हास दररोज ऊस वाहतुकीचे काम सभासदांकडे / बिगर सभासदांकडे जाऊन मिळवणे त्रासाचे होते. सभासदांकडे / बिगर सभासदाकडे ठरवून दिलेल्या कोठ्याप्रमाणे ऊस तोड व वाहतूक करून पोहोचता केला नाही तर त्यासाठी ऊस तोड व वाहतूक करणारी यंत्रणा ट्रक / ट्रॅक्टर / बैलगाडी लावून या सभासदास बिगर सभासदाचे मिळणारी खर्चाची व कमिशनची रक्कम अशा ऊस तोड वाहतूक करणाऱ्या यंत्रणेस सभासदाचे / बिगर सभासदाचे वतीने त्यांनी तुम्हाला अधिकार दिलेला आहे. हे मला माहित आहे.<br><br>
            २)हा करार तुम्हास तुमचे सभासद / बिगर सभासदाचे वतीने व त्यांचे साठी लिहून देत आहे.<br><br>
            ३)ऊस तोड / वाहतुकीचे कामासाठी आपण सांगेल त्यावेळी मी ट्रक / ट्रॅक्टर / बैलगाडी चालू स्थितीत हजर ठेवीन मी व माझे सहभागीदार या करारान्वये कारखान्याचे नोकर / कामगार होत नाहीत व होणार नाहीत वा कराराने कारखाना व आमच्यामते मालक व नोकर कामगार असे संबंध निर्माण होत नाही व होणार नाही.<br><br>
            ४)कारखान्याच्या कोणत्याही गटात व गावात कोणत्याही ठिकाणाहून आपण अनुमती दिलेल्या ऊस पुरवठादाराचे ऊसाची नुकसान न होऊ देता तोड / वाहतूक करीन. दुसरीकडचा ऊस मी परस्पर आणणार नाही. जर तसे झाले तर माझे ट्रक / ट्रॅक्टर / बैलगाडी कोणाचाही ऊस आणण्यास परवानगी न देण्याचा अधिकार राहील.<br><br>
            ५)ऊस उत्पादक सभासद / बिगर सभासदास ठरवून दिलेल्या ऊसाचा कोठा तोडून कारखान्यावर वाहतूक करता आली नाही तर त्या प्रसंगी मी व माझे सहभागीदार / तोडणी मजूर / बैलगाड्या /ट्रक / ट्रॅक्टर तोडुून देऊन ऊसतोड वाहतुकीची व्यवस्था करू.<br><br>
            ६)कारखान्याचे संचालक मंडळ ऊसतोड / वाहतूक व कमिशनचे दर ठरवील त्याप्रमाणे सभासद / बिगर सभासदास कारखाना जो खर्च देईल ती रक्कम आम्ही तोड / वाहतूक करून त्यांचेसाठी व करिता तसेच त्यांचे वतीने तुम्ही आम्हास त्यांचा अधिकार पत्राद्वारे द्यावी ती खर्चाची रक्कम अशा सभासदांना / बिगर सभासदास तुम्ही परस्पर देऊ नये.<br><br>
            ७)माझ्या ड्रायव्हरच्या क्लीनरच्या व सहभागीदाराच्या वर्तणुकीबद्दल मी जबाबदार राहीन. मला ऊस उत्पादक सभासद / बिगर सभासदासाठी आणि शिस्तीबाबत द्यावयाच्या सूचना माझे ड्रायव्हरला व क्लीनरला द्याव्यात. त्या माझ्यावर बंधनकारक राहतील. अचानक व अनपेक्षित कारखाना बंद पडल्यास कोणासही नुकसानभरपाई मागणार नाही. केनयार्डातील पार्किंग लाईनमध्ये माझी ट्रक / ट्रॅक्टर / बैलगाडी कितीही वेळ थांबली तर तक्रार करणार नाही. नुकसान भरपाई / भाडे कोणासही मागणार नाही.<br><br>
            ८)आम्ही ऊस भुईसपाट तोडून, साळून त्यांच्या मोळ्या बांधून तसेच थळामधून पाचटाच्या ओळी लावून देऊन संबंधितांचे ऊसाचे कोणत्याही प्रकारचे नुकसान होऊ देणार नाही. त्यांची तक्रार असल्यास त्याचे आम्ही निवारण करू यांची तक्रार येऊ देणार नाही.<br><br>
            ९)मी कारखान्यात मजूर मागणार नाही तसेच ऊस तोडणीसाठी हंगाम सुरू वेळी त्यांच्या गावापासून आणणे व बंद नंतर त्यांचे गावी मजूर सोडणेसाठी कारखाना व्यतिरिक्त लेबर वाहतूक भाडेपोटी रक्कम मागणार नाही. मजूर टोळीची उपलब्धता मी स्वतः माझ्या जिम्मेदारी वर करून घेईन.<br><br>
            १०)ऊस प्लॉट पासून मुख्यता रस्ता / वाहनापर्यंत आणण्याचे मजुरांमार्फत वाहतूक करण्यात येईल त्यास वेगळा मोबदला लागणार नाही. ऊसतोड / वाहतुकीचे काम करताना कारखान्याच्या तसेच ऊस उत्पादकांच्या देखरेखेखाली काम करू.<br><br>
            ११)आपल्या ऊस उत्पादक सभासदाचे / बिगर सभासदाचे वतीने आपण बोलवाल तेव्हापासून आपण सांगाल तोपर्यंत काम करीन. मध्येच सोडून गेलो तर नुकसान भरपाई करून देणेस पात्र राहील. माझी ट्रक / ट्रॅक्टर / बैलगाडी बंद पडल्यास ऊस पुरवठादाराचे वाहतुकीवर ताबडतोब दुसरी ट्रक / ट्रॅक्टर / बैलगाडी लावीन. त्याचप्रमाणे ट्रक / ट्रॅक्टर विकल्यास किंवा बिघडल्यास दुरुस्तीस पाठवल्यास दुसरी ट्रक / ट्रॅक्टर लावीन.<br><br>
            १२)ऊसाची निम्मी बांडी/वाढे ऊस मालकास देऊ. निम्मी आमच्यासाठी  ठेवू व कामी काही वाद झाल्यास  तुम्ही मध्यस्थी करून आम्हास साहाय्य करावे.<br><br>
            १३)प्रत्येक क्लीनिंग नंतर लगेच ट्रक / ट्रॅक्टर / बैलगाडी कामावर आला नाही किंवा दिलेल्या बदली प्रमाणे ऊस पुरवठा दाराचे ऊस वाहतुकीवर गेला नाही तर शेतकऱ्यांची नुकसानीस जबाबदार धरून त्यांचे वतीने आपण मला जी नुकसानभरपाई अगर दंड आकारला तो मान्य राहील.<br><br>
            १४)मी व माझे सहभागीदारांचे आम्ही राहण्याची व्यवस्था परस्पर करू तुम्ही सभासदांना / बिगर सभासदांना ऊस तोड / वाहतूक करणाऱ्या त्यांच्या मदतनिसांसाठी कोपीचे काही सामान देता ते सभासदास / बिगर सभासदास देऊन नंतर आम्हास त्यांचेकडून मिळण्याऐवजी सभासदांसाठी / बिगर सभासदांसाठी व तर्फे तुमच्याकडून त्याचे अधिकार पत्राआधारे आम्हांस मिळावे. त्यामुळे आमची सोय होऊन वेळेची बचत होईल हे सामान आम्ही तोडणी वाहतुकीचे काम करेपर्यंत व्यवस्थित सांभाळू व सिझन बंद झाले नंतर आम्ही सर्व समान घरी घेवून जाऊ व त्याची होणारी रक्कम आमचे बिलातून कारखान्याचे नियमाप्रमाणे कपात करावी व ते आम्हास मान्य राहील.<br><br>
            १५)ऊस मालकीचे तोडणीचे क्षेत्र संपल्यानंतर ट्रक / ट्रॅक्टर / बैलगाडी भरती पेक्षा कमी ऊस आला असला तरी तो नेण्याची व्यवस्था करेन व जादा भाडे मागणार नाही. <br><br>
            १६)मी माझे सहभागीदाराचे वर्तवणुकीस जबाबदार राहीन. आमचेकडून गैरवर्तणूक झाल्यास आम्हास काम द्यावयाचे की नाही हे त्या त्या सभासदांच्या / बिगर सभासदांच्या इच्छेवर अवलंबून राहील. तसेच आम्हास ऊसतोड / वाहतूक द्यावयाची की नाही हे त्या त्या सभासदांच्या / बिगर सभासदांच्या इच्छेवर अवलंबून राहील कारण  त्यांच्याकरिता आपण हा करार केला आहे.<br><br>
            १७)ऊस उत्पादकाचे ऊसाचे प्लॉटमध्ये जास्तीतजास्त जवळ ट्रक / ट्रॅक्टर / बैलगाडी घेऊन जाईन.<br><br>
            १८)अचानक व अनपेक्षित कारणाने किंवा साफसफाईसाठी कारखाना बंद राहिल्यास ज्या ज्या वेळी सभासदांचा कोठा  तहकूब होईल त्यावेळी व अशा प्रसंगी आम्ही आमचे कामे थांबवून सभासद / बिगर सभासद सांगतील त्या वेळी परत चालू करू यासाठी उत्पादक सभासद / बिगर सभासदांकडून नुकसान भरपाई घेण्यास आम्हास अधिकार नाही. तसेच हंगाम वेळी कारखाना साइटवर मजूर टोळी येऊन थांबल्यास कारखाना चालू करण्यास काही कारणाने विलंब झालेस अथवा वरीलप्रमाणे कारखान्यास बस पाळी मागितली जाणार नाही.<br><br>
            १९)शेतातून ट्रक / ट्रॅक्टर / बैलगाडी भरलेवर आठ तासाच्या आत कारखान्यावर पोहोचविन तसेच ऊस पुरवठादाराचे ऊस वाहतुकीच्या कामाशिवाय इतर मालवाहतुकीचे भाडे वगैरे मागणी करणार नाही.<br><br>
            २०)मी माझे सहभागीदार सभासदांचे ऊस तोड वाहतुकीचे जे काम करतील त्याची रक्कम माझे सहभागीदाराचे वतीने व त्यांच्यासाठी सभासद / बिगर सभासदांतर्फे व त्यांचेसाठी त्यांना तुम्हास दिलेल्या अधिकारपत्राप्रमाणे तुमचे कडून घेऊन माझे सहभागीदारांना वाटून देईन. त्याची तक्रार तुमच्याकडे येऊ देणार नाही माझे सहभागीदार मी रक्कम न दिल्यास त्याची जबाबदारी तुमच्यावर ऊस उत्पादक सभासद / बिगर सभासदांवर राहणार नाही. त्याची मी संपूर्ण एकटा जबाबदार राहीन. या अटींची पूर्ण कल्पना व माहिती मी माझ्या सहभागीदारांना दिलेली आहे.<br><br>
            २१)ट्रक / ट्रॅक्टर / बैलगाडी मध्ये ऊस भरल्यावर तो व्यवस्थित बांधण्यासाठी लागणारी रस्सी किंवा दोर माझे मी  पुरवीन वाटेत नुकसान होऊ नये म्हणून मागील बाजूस ताडपत्री अगर किलता बांधीन.<br><br>
            २२)मी गळीत हंगामाच्या सुरुवातीस येऊन हंगाम अखेर पर्यंत तुमच्या ऊस उत्पादक सभासदांचे / बिगर सभासदाचे त्यांचे जरूरीप्रमाणे शेवटपर्यंत काम करून देईन. आम्ही उत्पादक सभासदाची / बिगर सभासदाची गैरसोय करणार नाही. मध्ये काम टाकून दिल्याने ऊस उत्पादक सभासदाची / बिगर सभासदाची जी नुकसान भरपाई होईल ती आम्ही भरून देऊ. <br><br>
            २३)मी अगर ड्रायव्हर प्लॉटमध्ये ट्रक / ट्रॅक्टर / बैलगाडी भरताना गव्हाणीवर गाडी खाली होत असताना सतत हजर राहीन. तसेच  केनयार्डमध्ये ट्रक / ट्रॅक्टर / बैलगाडी यार्डावर आपले सर्व नियम पाळीन.<br><br>
            २४)मी हा करार माझ्यासाठी व सहभागीदारासाठी करून देत आहे. माझ्या सहभागीदाराचा हिशोब देण्याची संपूर्ण जबाबदारी माझ्यावर आहे. ऊस उत्पादक सभासद / बिगर सभासद जे कमिशन देण्यात येईल ते आम्ही तोड वाहतूक केल्यास अधिकार पत्राद्वारे तुमच्याकडून जमा करण्यात यावे. सभासदांना / बिगर सभासदांना परस्पर देऊ नये. करार पाळण्यासाठी मी नियमाप्रमाणे डिपॉझिट भरीन. सभासदांच्या / बिगर सभासदांच्या नुकसानीच्या रकमा सभासदांसाठी / बिगर सभासदांसाठी माझे परस्पर या रकमेतून कापून घ्याव्यात तसा तुम्हास अधिकार दिलेला आहे.<br><br>
            २५)ऊस पुरवठादाराचे ऊसतोड वाहतुकीचे कामापोटी त्यांचे वतीने व त्यासाठी आपणाकडून करारापोटी व नंतर वेळोवेळी घेतलेली रक्कम ट्रक / ट्रॅक्टर / बैलगाडी  साठीची रक्कम उचल घेतली आहे.ती उचल/पुढेही उचललेली रक्कम सभासद / बिगर सभासद कारखान्याकडून ऊसतोड वाहतूकीनिमित्त  मिळणाऱ्या खर्चातून त्यांचे अधिकार पत्रान्वये मला देताना कारखान्याने वसूल करून घ्यावी. ही रक्कम जर वसूल झाली नाही तर मी बँक दराने दरसाल दर शेकडा १२% किंवा प्रचलित दराने रक्कम कारखान्याकडे भरणा करीन किंवा वाहन विक्री करून येणे असलेली रक्कम वसूल करण्याचा ट्रक / ट्रॅक्टर / बैलगाडी ताब्यात घेण्याचा अधिकार कारखान्यास राहील. याबद्दल माझी कोणत्याही प्रकारे तक्रार राहणार नाही.<br><br>
            २६)तुमच्या सभासदांचे / बिगर सभासदांची जे काम आम्ही करू त्या कामाचे बिल अधिकार पत्रान्वये सभासदांसाठी व बिगर सभासदांसाठी व तर्फे तुम्ही दर पंधरवड्यास घ्यावे. त्यांनी तुमच्याकडे शेकडा १०% प्रमाणे डिपॉझिट म्हणून रक्कम कापून ठेवावी. या करारातील शर्ती पूर्ण झाल्याशिवाय मला ही रक्कम मागता येणार नाही. डिपॉझिटची रक्कम व कारखाना नियमाप्रमाणे देय कमिशन रक्कम परत करावयाच्या कामी मला  एकट्यास तुम्ही जबाबदार रहाल. माझ्या सहभागीदाराने रक्कम तुमच्याकडे मागण्याचा हक्क त्यांना राहणार नाही व ठेवलेला नाही.<br><br>
            २७)करारातील मुदतीत मी माझा ट्रक / ट्रॅक्टर कारखान्याचे परवानगीशिवाय विकणार नाही. सदर कराराची जबाबदारी माझे वारसदारावर देखील राहीन. गाडीच्या कागदपत्रांच्या प्रती तुमच्याकडे दिल्या आहेत.<br><br>
            २८)मला अगर माझ्या सहभागीदारांना अपघात झाल्यास त्याची कोणतीही जबाबदारी तुमच्यावर अथवा तुमच्या सभासद / बिगर सभासदांवर राहणार नाही. त्याचप्रमाणे माझ्या सहभागीदारांचा जोखमीचा विमा मी स्वतःहून काढून घेईन.<br><br>
            २९)ऊस उत्पादकांना माझे ट्रक / ट्रॅक्टर / बैलगाडी ने करावयाचे ऊस वाहतुकीसाठी कारखान्याकडून दिल्या जाणाऱ्या व त्याचे अधिकार पत्राआधारे मला मिळणाऱ्या रकमेतून प्रत्येक वेळी १०% डिपॉझिट कापून घेण्यास माझी संमती आहे.<br><br>
            ३०)आपल्या कारखान्याच्या डिझेल पंपावर मी डिझेल अथवा तेल उधार घेतल्यास त्याची रक्कम मला उत्पादकाचे अधिकारान्वये होणाऱ्या ऊस वाहतूक रकमेतून परस्पर कपात करून घ्यावी.<br><br>
            ३१)कारखान्याच्या भुईकाट्यावर भरतीच्या व रिकाम्या काट्याचे वजन तुमच्या नियमाप्रमाणे शिस्तीत करून व त्याच्या वजन पत्रिका घेऊन त्यात दाखवलेले वजन अखेरचे समजण्यात येईल. त्याबद्दल तक्रार राहणार नाही.<br><br>
            ३२)मी आपल्या स्टोअरमधून आपल्या ऊसपुरवठादारांच्या काही लोन / उधार स्टोअर माल घेतलेस अथवा वर्कशॉपमधून काम करून घेतले तर त्यांचे बिल देण्यात मला येणाऱ्या रकमेतून परस्पर कपात करून घ्यावे.<br><br>
            ३३)प्रत्येक गाडीमागे भरावी लागणारी लायसन्स फी तसेच इतर मी अगर माझे सहभागीदार यांनी भरावयाचा आहे. त्याची जबाबदारी तुमच्या ऊस उत्पादक सभासद / बिगर सभासदांवर राहणार नाही.<br><br>
            ३४)माझे ट्रक / ट्रॅक्टर चे ऊस वाहतूक बिलातून कपात झालेली डिपॉझिट रक्कम व कारखान्याचे नियमाप्रमाणे देण्यात येणारे कमिशन हंगाम बंद नंतर करण्यात यावे.<br><br>
            ३५)मी अगर माझे प्रतिनिधी सभासदांच्या / बिगर सभासदांच्या ऊसाच्या थळातून प्रत्यक्ष हजर राहून तोडलेला सर्व माल गाडीतून / थळातून आमच्या देखरेखीखाली भरून पाठवू. कारखान्याची व सभासदांची / बिगर सभासदांची ऊस गळतीची नुकसान व गैरसोय होऊ देणार नाही. तसेच ऊसाचे नुकसान होऊ देणार नाही.<br><br>
            ३६)कामावर असताना ड्रायव्हर क्लीनर यास अगर इतरास काही अपघात झाल्यास मी जबाबदार राहीन. त्याची तोशिष कारखान्यावर लागू देणार नाही. त्याचप्रमाणे योग्य त्या प्रकारचा विमा उतरून घेईल.<br><br>
            ३७)कारखाना सुरू होण्याच्या वेळी माझे टोळीतील सहकारी परवानगी दिलेल्या बैलगाड्या / तोडणी मजूर वेळेवर हजर ठेवू. आम्ही वेळेवर बैलगाड्या / तोडणी मजुर न आणल्यास सभासदाची / बिगर सभासदाची ऊसतोड वाहतूक आम्हास देणे अगर न देणे तुमच्या मर्जीवर राहील.<br><br>
            ३८)मी व माझे सहभागीदार यांनी संप व हरताळ करून कारखान्याचे कामकाजात व्यत्यय आणून काम बंद पाडले त्यामुळे कारखान्यास व सभासदास / बिगर सभासदास कोणत्याही प्रकारचे नुकसान व तोशिष सोसावी लागल्यास मी व माझे सहभागीदार ती भरून देऊ.<br><br>
            ३९)वर नमूद केलेप्रमाणे भागीदार व माझे मार्फत बैलगाडी न आल्यास अगर कामावर न आल्यास कराराप्रमाणे मजूर ट्रक / ट्रॅक्टर / बैलगाडी वाहन टोळी न पुरवल्यास, काम सोडून गेल्यास सदरचे कृत्य हे मी व जामीनदारांनी कागदपत्रांवर सह्या केले प्रमाणे तुम्ही विश्वास ठेवून रक्कम दिली. सदर बाब ही जाणून-बुजून प्रामाणिकपणे मी व जामीनदारांनी  तुम्हास फसविले आहे. व अप्रमानिकपणे संस्थेकडून रक्कम घेतली असे समजून इ पी कोड कलम 420 व 34 प्रमाणे अगर भारतीय दंडविधान संहितेच्या अनुसरून फिर्याद करावी अगर न्यायालयामार्फत योग्य ती कारवाई करावी.<br><br>
            ४०)सदर कराराने व्यवहाराबाबत अगर शर्तीबाबत सभासदाचे / बिगर सभासदाचे अगर आमचे दरम्यान अगर तुमचे आमचे काही वाद झाल्यास व महाराष्ट्र को.ऑप. सोसायटी अॅक्ट 1960 कलम ९१/१०१ प्रमाणे लावून तो निर्णय माझ्यावर बंधनकारक राहीन.<br><br>
            ४१)या कराराबाबत काही वाद निर्माण झाल्यास सदरचा वाद नाशिक / नाशिक या कोर्टाचे न्याय कक्षेत राहील.<br><br>
            ४२)मी आपल्या कारखान्याचा नाममात्र सभासद आहे / होईल त्यासाठी दिनांक  ________________ट्रक / ट्रॅक्टर / बैलगाडी पावती क्रमांक ___ अन्वये रुपये १००/- सभासद प्रवेश फी भरलेली आहे.<br><br>
            ४३)कारखान्याचे सध्या नियम व वेळोवेळी बदलणारे नियम मला व माझ्या सहभागी दारांना बंधनकारक राहतील.<br><br>
            ४४)वरील अटीवर दररोज ट्रक / ट्रॅक्टर / बैलगाडी मे.टन ऊस पुरवण्यासाठी सोबत माझ्या टोळीतील सहभागीदारांची यादी दिलेली आहे.<br><br>
            1) बैल टायर  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;         २) ट्रॅक्टर टायर &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;     ३) वाहन टोळी &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;             ४) डोकी सेंटर <br><br>
            करिता हा करारनामा मी व माझेसाठी व माझे सहभागिदारांच्या वतीने व त्यांचे साठी वाचून समजावून करून दिला आहे. <br><br>
            <br><br>          

</span>';
            
            $pdf->SetFont('siddhanta', '', 13, '', true);
			$pdf->writeHTML($html0, true, 0, true, true);
            $pdf->SetFont('siddhanta', '', 12, '', true);
            $pageno1=$pdf->getPage();
            $pdf->writeHTML($html1, true, 0, true, true);
            $pageno2=$pdf->getPage();
            //$liney = $liney+60;
            //$pdf->writeHTML($html2, true, 0, true, true);
            //$liney = $liney+60;
            //$pdf->writeHTML($html3, true, 0, true, true);
            //$liney = $liney+60;
            //$pdf->writeHTML($html4, true, 0, true, true);
            //$liney = $liney+60;
            //$pdf->writeHTML($html5, true, 0, true, true);
            //$liney = $liney+60;
            //$pdf->writeHTML($html6, true, 0, true, true);
            //$liney = $liney+60;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $pdf->addpage();
            $liney = 20;
            
            
			$pdf->multicell(40,10,'स्थळ: श्री संत जनार्दन स्वामी नगर',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+5;
			$curdate = date('d/m/Y');
            $pdf->multicell(50,10,'दिनांक:'.$contract1->contractdatetime,0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+5;
            
			$contractphotodetail1 = new contractphotodetail($this->connection);
			$contractphotodetail1 = $this->contracttransportphotodetail($this->connection,$contract1->contractid,1);
            if ($contractphotodetail1!=null)
            {
                $imgdata = $contractphotodetail1->photo;
            }
            else
            {
                $contractphotodetail1 = $this->contractharvestphotodetail($this->connection,$contract1->contractid,1);
                $imgdata = $contractphotodetail1->photo;
            }
			
			$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
			$pdf->setJPEGQuality(90);
			$pdf->Image('@'.$imgdata,120,$liney,25,25);

			$contractfingerprintdetail1 = new contractfingerprintdetail($this->connection);
			$contractfingerprintdetail1 = $this->contracttransportfingerprintdetail($this->connection,$contract1->contractid,1);

            if ($contractfingerprintdetail1!=null)
            {
                $fingerprintdata = $contractfingerprintdetail1->fingerprint;
            }
            else
            {
                $contractfingerprintdetail1 = $this->contractharvestfingerprintdetail($this->connection,$contract1->contractid,1);
                $fingerprintdata = $contractfingerprintdetail1->fingerprint;
            }

			
            
            $pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
			$pdf->setJPEGQuality(90);
			$pdf->Image('@'.$fingerprintdata,160,$liney,25,25);

			$pdf->multicell(60,10,'करारनामा लिहून देणार',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->multicell(40,10,'सही',0,'L',false,1,15,$liney,true,0,false,true,10);
            //$liney = $liney+5;
            $pdf->rect(50,$liney,10,10);
            $liney = $liney+14;
            $pdf->multicell(100,10,'नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,$servicecontractor1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+2;
            $pdf->multicell(100,10,'पत्ता:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$contract1->address,0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+5;           

			//QRCODE,H : QR-CODE Best error correction
			//$pdf->write2DBarcode('www.swapp.co.in', 'QRCODE,H', 140, 210, 25, 25, $style, 'N');
			//$pdf->Text(140, 205, 'Swapp Software Application');

            $contractguarantordetail1 = new contractguarantordetail($this->connection);
			$contractguarantordetail1 = $this->contractguarantordetail($this->connection,$contract1->contractid,1);
			$servicecontractor_guarantor1 = new servicecontractor($this->connection);
			$servicecontractor_guarantor1->fetch($contractguarantordetail1->servicecontractorid);
            $garcontractid1=$contract1->findcontractbyservicecontractid($contract1->seasonid,$contractguarantordetail1->servicecontractorid);
            $contract2 = new contract($this->connection);
            $contract2->fetch($garcontractid1);
                        
            if ($servicecontractor_guarantor1->servicecontractorid>0)
            {
                $contractphotodetail2 = new contractphotodetail($this->connection);
                $contractphotodetail2 = $this->contractguarantorphotodetail($this->connection,$contract1->contractid,$contractguarantordetail1->servicecontractorid);

                $imgdata2 = $contractphotodetail2->photo;
                $pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
                $pdf->setJPEGQuality(90);
                $pdf->Image('@'.$imgdata2,120,$liney,25,25);

                $contractfingerprintdetail2 = new contractfingerprintdetail($this->connection);
                $contractfingerprintdetail2 = $this->contractguarantorfingerprintdetail($this->connection,$contract1->contractid,$contractguarantordetail1->servicecontractorid);

                $fingerprintdata2 = $contractfingerprintdetail2->fingerprint;
                $pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
                $pdf->setJPEGQuality(90);
                $pdf->Image('@'.$fingerprintdata2,160,$liney,25,25);
            }

            $pdf->SetFont('siddhanta', '', 11, '', true);
            $pdf->multicell(35,10,'जामीनदार',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+10;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $j=1;
            if ($servicecontractor_guarantor1->servicecontractorid>0)
            {
            $pdf->multicell(40,10,$j++.')सही',0,'L',false,1,15,$liney,true,0,false,true,10);
			//$liney = $liney+5;
            $pdf->rect(50,$liney,10,10);
            $liney = $liney+14;
			$pdf->multicell(100,10,'नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,$servicecontractor_guarantor1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+2;

            $pdf->multicell(100,10,'पत्ता:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$contract2->address,0,'L',false,1,30,$liney,true,0,false,true,10);
            
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+10;
            }

            $contractguarantordetail2 = new contractguarantordetail($this->connection);
			$contractguarantordetail2 = $this->contractguarantordetail($this->connection,$contract1->contractid,2);
			$servicecontractor_guarantor2 = new servicecontractor($this->connection);
			$servicecontractor_guarantor2->fetch($contractguarantordetail2->servicecontractorid);
            $garcontractid2=$contract1->findcontractbyservicecontractid($contract1->seasonid,$contractguarantordetail2->servicecontractorid);
            $contract3 = new contract($this->connection);
            $contract3->fetch($garcontractid2);
            if ($servicecontractor_guarantor2->servicecontractorid>0)
            {
            $contractphotodetail3 = new contractphotodetail($this->connection);
			$contractphotodetail3 = $this->contractguarantorphotodetail($this->connection,$contract1->contractid,$contractguarantordetail2->servicecontractorid);

			$imgdata3 = $contractphotodetail3->photo;
			$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
			$pdf->setJPEGQuality(90);
			$pdf->Image('@'.$imgdata3,120,$liney,25,25);

			$contractfingerprintdetail3 = new contractfingerprintdetail($this->connection);
			$contractfingerprintdetail3 = $this->contractguarantorfingerprintdetail($this->connection,$contract1->contractid,$contractguarantordetail2->servicecontractorid);

			$fingerprintdata3 = $contractfingerprintdetail3->fingerprint;
			$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
			$pdf->setJPEGQuality(90);
			$pdf->Image('@'.$fingerprintdata3,160,$liney,25,25);

            $pdf->multicell(100,10,$j++.')सही',0,'L',false,1,15,$liney,true,0,false,true,10);
			//$liney = $liney+5;
            $pdf->rect(50,$liney,10,10);
            $liney = $liney+14;
            $pdf->multicell(100,10,'नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$servicecontractor_guarantor2->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+2;
            $pdf->multicell(100,10,'पत्ता:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$contract3->address,0,'L',false,1,30,$liney,true,0,false,true,10);
            
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+2;
            }

            $contractguarantordetail2 = new contractguarantordetail($this->connection);
			$contractguarantordetail2 = $this->contractguarantordetail($this->connection,$contract1->contractid,3);
			$servicecontractor_guarantor2 = new servicecontractor($this->connection);
			$servicecontractor_guarantor2->fetch($contractguarantordetail2->servicecontractorid);
            $garcontractid2=$contract1->findcontractbyservicecontractid($contract1->seasonid,$contractguarantordetail2->servicecontractorid);
            $contract3 = new contract($this->connection);
            $contract3->fetch($garcontractid2);
            if ($servicecontractor_guarantor2->servicecontractorid>0)
            {
            $contractphotodetail3 = new contractphotodetail($this->connection);
			$contractphotodetail3 = $this->contractguarantorphotodetail($this->connection,$contract1->contractid,$contractguarantordetail2->servicecontractorid);

			$imgdata3 = $contractphotodetail3->photo;
			$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
			$pdf->setJPEGQuality(90);
			$pdf->Image('@'.$imgdata3,120,$liney,25,25);

			$contractfingerprintdetail3 = new contractfingerprintdetail($this->connection);
			$contractfingerprintdetail3 = $this->contractguarantorfingerprintdetail($this->connection,$contract1->contractid,$contractguarantordetail2->servicecontractorid);

			$fingerprintdata3 = $contractfingerprintdetail3->fingerprint;
			$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
			$pdf->setJPEGQuality(90);
			$pdf->Image('@'.$fingerprintdata3,160,$liney,25,25);

            $pdf->multicell(100,10,$j++.')सही',0,'L',false,1,15,$liney,true,0,false,true,10);
			//$liney = $liney+5;
            $pdf->rect(50,$liney,10,10);
            $liney = $liney+14;
            $pdf->multicell(100,10,'नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$servicecontractor_guarantor2->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+2;
            $pdf->multicell(100,10,'पत्ता:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$contract3->address,0,'L',false,1,30,$liney,true,0,false,true,10);
            
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+2;
            }

            $k=1;
            $contractguarantordetail_cult1 = new contractguarantordetail($this->connection);
			$contractguarantordetail_cult1 = $this->contractguarantordetail($this->connection,$contract1->contractid,$k,2);
			$servicecontractor_guarantor_cult1 = new cultivator($this->connection);
			$servicecontractor_guarantor_cult1->fetch($contractguarantordetail_cult1->servicecontractorid);
            if ($contractguarantordetail_cult1->servicecontractorid>0)
            {
                $contractphotodetail_cult1 = new contractphotodetail($this->connection);
                $contractphotodetail_cult1 = $this->contractguarantorphotodetail_cult($this->connection,$contract1->contractid,$contractguarantordetail_cult1->servicecontractorid);

                $imgdata_cult1 = $contractphotodetail_cult1->photo;
                $pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
                $pdf->setJPEGQuality(90);
                $pdf->Image('@'.$imgdata_cult1,120,$liney,25,25);

                $contractfingerprintdetail_cult1 = new contractfingerprintdetail($this->connection);
                $contractfingerprintdetail_cult1 = $this->contractguarantorfingerprintdetail_cult($this->connection,$contract1->contractid,$contractguarantordetail_cult1->servicecontractorid);

                $fingerprintdata_cult1 = $contractfingerprintdetail_cult1->fingerprint;
                $pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
                $pdf->setJPEGQuality(90);
                $pdf->Image('@'.$fingerprintdata_cult1,160,$liney,25,25);

                $pdf->multicell(100,10,$j++.')सही',0,'L',false,1,15,$liney,true,0,false,true,10);
                //$liney = $liney+5;
                $pdf->rect(50,$liney,10,10);
                $liney = $liney+14;
                $pdf->multicell(100,10,'नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(100,10,$servicecontractor_guarantor_cult1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(30,$liney,100,$liney);
                $liney = $liney+2;
                $pdf->multicell(100,10,'पत्ता:',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(100,10,$contractguarantordetail_cult1->address_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
                
                $liney = $liney+5;
                $pdf->line(30,$liney,100,$liney);
                $liney = $liney+2;
                $liney = $liney+5;
                $k++;
            }

            $contractguarantordetail_cult1 = new contractguarantordetail($this->connection);
			$contractguarantordetail_cult1 = $this->contractguarantordetail($this->connection,$contract1->contractid,$k,2);
			$servicecontractor_guarantor_cult1 = new cultivator($this->connection);
			$servicecontractor_guarantor_cult1->fetch($contractguarantordetail_cult1->servicecontractorid);
            if ($contractguarantordetail_cult1->servicecontractorid>0)
            {
                $contractphotodetail_cult1 = new contractphotodetail($this->connection);
                $contractphotodetail_cult1 = $this->contractguarantorphotodetail_cult($this->connection,$contract1->contractid,$contractguarantordetail_cult1->servicecontractorid);

                $imgdata_cult1 = $contractphotodetail_cult1->photo;
                $pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
                $pdf->setJPEGQuality(90);
                $pdf->Image('@'.$imgdata_cult1,120,$liney,25,25);

                $contractfingerprintdetail_cult1 = new contractfingerprintdetail($this->connection);
                $contractfingerprintdetail_cult1 = $this->contractguarantorfingerprintdetail_cult($this->connection,$contract1->contractid,$contractguarantordetail_cult1->servicecontractorid);

                $fingerprintdata_cult1 = $contractfingerprintdetail_cult1->fingerprint;
                $pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
                $pdf->setJPEGQuality(90);
                $pdf->Image('@'.$fingerprintdata_cult1,160,$liney,25,25);

                $pdf->multicell(100,10,$j++.')सही',0,'L',false,1,15,$liney,true,0,false,true,10);
                //$liney = $liney+5;
                $pdf->rect(50,$liney,10,10);
                $liney = $liney+14;
                $pdf->multicell(100,10,'नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(100,10,$servicecontractor_guarantor_cult1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(30,$liney,100,$liney);
                $liney = $liney+2;
                $pdf->multicell(100,10,'पत्ता:',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(100,10,$contractguarantordetail_cult1->address_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
                
                $liney = $liney+5;
                $pdf->line(30,$liney,100,$liney);
                $liney = $liney+2;
                $liney = $liney+5;
                $k++;
            }

            $contractguarantordetail_cult1 = new contractguarantordetail($this->connection);
			$contractguarantordetail_cult1 = $this->contractguarantordetail($this->connection,$contract1->contractid,$k,2);
			$servicecontractor_guarantor_cult1 = new cultivator($this->connection);
			$servicecontractor_guarantor_cult1->fetch($contractguarantordetail_cult1->servicecontractorid);
            if ($contractguarantordetail_cult1->servicecontractorid>0)
            {
                $contractphotodetail_cult1 = new contractphotodetail($this->connection);
                $contractphotodetail_cult1 = $this->contractguarantorphotodetail_cult($this->connection,$contract1->contractid,$contractguarantordetail_cult1->servicecontractorid);

                $imgdata_cult1 = $contractphotodetail_cult1->photo;
                $pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
                $pdf->setJPEGQuality(90);
                $pdf->Image('@'.$imgdata_cult1,120,$liney,25,25);

                $contractfingerprintdetail_cult1 = new contractfingerprintdetail($this->connection);
                $contractfingerprintdetail_cult1 = $this->contractguarantorfingerprintdetail_cult($this->connection,$contract1->contractid,$contractguarantordetail_cult1->servicecontractorid);

                $fingerprintdata_cult1 = $contractfingerprintdetail_cult1->fingerprint;
                $pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
                $pdf->setJPEGQuality(90);
                $pdf->Image('@'.$fingerprintdata_cult1,160,$liney,25,25);

                $pdf->multicell(100,10,$j++.')सही',0,'L',false,1,15,$liney,true,0,false,true,10);
                //$liney = $liney+5;
                $pdf->rect(50,$liney,10,10);
                $liney = $liney+14;
                $pdf->multicell(100,10,'नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(100,10,$servicecontractor_guarantor_cult1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(30,$liney,100,$liney);
                $liney = $liney+2;
                $pdf->multicell(100,10,'पत्ता:',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(100,10,$contractguarantordetail_cult1->address_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
                
                $liney = $liney+5;
                $pdf->line(30,$liney,100,$liney);
                $liney = $liney+2;
                $liney = $liney+5;
            }



            $pdf->multicell(100,10,'साक्षीदार',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $list1 = $contract1->witnesslist();
            $i=0;
            $contractwitnessdetail1 = new contractwitnessdetail($this->connection);
            $servicecontractor_witness1 = new servicecontractor($this->connection);
            foreach ($list1 as $value)
            {
                $val = intval($list1[$i]);
                $contractwitnessdetail1->fetch($val);
                //$servicecontractor_witness1->fetch($contractwitnessdetail1->servicecontractorid);
            
                $pdf->multicell(100,10,++$i.')नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(100,10,$contractwitnessdetail1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
                $pdf->multicell(10,10,'सही:',0,'L',false,1,120,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(30,$liney,100,$liney);
                $pdf->line(120,$liney,200,$liney);
                $liney = $liney+5;

            }
            $liney = $liney+10;
            $servicecontractor_ao1 = new servicecontractor($this->connection);
			$servicecontractor_ao1 = $this->contractagriofficerdetail($this->connection);
            $lineypos=$liney;
			/* $pdf->multicell(60,10,'करारनामा लिहून घेणार',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+7; */
			$pdf->multicell(100,10,'सही',0,'L',false,1,15,$liney,true,0,false,true,10);
			//$liney = $liney+5;
			$pdf->rect(50,$liney,10,10);
            $liney = $liney+14;
			//$pdf->multicell(100,10,'नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
			//$pdf->multicell(100,10,$servicecontractor_ao1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            //$liney = $liney+5;
			$pdf->line(30,$liney,100,$liney);
			$liney = $liney+2;
			$pdf->multicell(100,10,'मुख्य शेतकी अधिकारी',0,'L',false,1,45,$liney,true,0,false,true,10);
			$liney = $liney+7;
			$pdf->multicell(100,10,'नाशिक सहकारी साखर कारखाना लि. श्री संत जनार्दन स्वामी नगर',0,'L',false,1,70,$liney,true,0,false,true,10);
			$liney = $liney+7;
			$liney = $liney+5;

            $liney=$lineypos;

            $servicecontractor_ao1 = new servicecontractor($this->connection);
			$servicecontractor_ao1 = $this->contractagriofficerdetail($this->connection);

			$pdf->multicell(60,10,'सही',0,'L',false,1,110,$liney,true,0,false,true,10);
			//$liney = $liney+5;
            $pdf->rect(150,$liney,10,10);
            $liney = $liney+14;
			//$pdf->multicell(100,10,'नाव:',0,'L',false,1,110,$liney,true,0,false,true,10);
			//$pdf->multicell(100,10,$servicecontractor_ao1->name_unicode,0,'L',false,1,130,$liney,true,0,false,true,10);
            //$liney = $liney+5;
			$pdf->line(120,$liney,200,$liney);
			$liney = $liney+2;
			$pdf->multicell(100,10,'कार्यकारी संचालक',0,'L',false,1,140,$liney,true,0,false,true,10);
			//$liney = $liney+7;
			//$pdf->multicell(100,10,'नाशिक सहकारी साखर कारखाना लि.',0,'L',false,1,110,$liney,true,0,false,true,10);

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

    function contractguarantordetail(&$connection,$contractid,$sequencenumber,$gaurantorcategory=1)
    {
        $contractguarantordetail1 = new contractguarantordetail($connection);
        if ($gaurantorcategory==1)
        {
            $query = "select d.contractguarantordetailid 
            from contract c,contractguarantordetail d 
            where c.active=1 and d.active=1 
            and c.contractid=d.contractid and nvl(iscultivator,0)=0
            and c.contractid=".$contractid."";
        }
        elseif ($gaurantorcategory==2)
        {
            $query = "select d.contractguarantordetailid 
            from contract c,contractguarantordetail d 
            where c.active=1 and d.active=1 
            and c.contractid=d.contractid and nvl(iscultivator,0)=1
            and c.contractid=".$contractid."";
        }
        
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contractguarantordetail1->fetch($row['CONTRACTGUARANTORDETAILID'],$gaurantorcategory);
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

    function contracttransportphotodetail(&$connection,$contractid,$sequencenumber)
    {
        $contractphotodetail1 = new contractphotodetail($connection);
        $query = "select d.contractphotodetailid from contract c,contracttransportdetail t,contractphotodetail d where c.active=1 and t.active=1 and d.active=1 and c.contractid=t.contractid and c.contractid=d.contractid and t.contracttransportdetailid=d.contractreferencedetailid and c.contractid=".$contractid." and d.contractreferencecategoryid=584251658 order by d.contractphotodetailid";
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contractphotodetail1->fetch($row['CONTRACTPHOTODETAILID']);
                return $contractphotodetail1;
            }
            else
            {
                $i++;
            }
        }
    }

    function contractharvestphotodetail(&$connection,$contractid,$sequencenumber)
    {
        $contractphotodetail1 = new contractphotodetail($connection);
        $query = "select d.contractphotodetailid from contract c,contractharvestdetail t,contractphotodetail d where c.active=1 and t.active=1 and d.active=1 and c.contractid=t.contractid and c.contractid=d.contractid and t.contractharvestdetailid=d.contractreferencedetailid and c.contractid=".$contractid." and d.contractreferencecategoryid=254156358 order by d.contractphotodetailid";
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contractphotodetail1->fetch($row['CONTRACTPHOTODETAILID']);
                return $contractphotodetail1;
            }
            else
            {
                $i++;
            }
        }
    }

    function contractguarantorphotodetail(&$connection,$contractid,$servicecontractorid)
    {
        $contractphotodetail1 = new contractphotodetail($connection);
        $query = "select getphotobyservicecontractorid(c.seasonid,t.servicecontractorid) as contractphotodetailid from contract c,contractguarantordetail t 
        where c.active=1 and t.active=1 
        and c.contractid=t.contractid 
        and c.contractid=".$contractid.
        " and t.servicecontractorid=".$servicecontractorid;
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==1)
            {
                $contractphotodetail1->fetch($row['CONTRACTPHOTODETAILID']);
                return $contractphotodetail1;
            }
            else
            {
                $i++;
            }
        }
    }

    function contractguarantorphotodetail_cult(&$connection,$contractid,$cultivatorid)
    {
        $contractphotodetail1 = new contractphotodetail($connection);
        $query = "select getphotobycultivatorid(c.seasonid,t.servicecontractorid) as contractphotodetailid from contract c,contractguarantordetail t 
        where c.active=1 and t.active=1 
        and c.contractid=t.contractid 
        and c.contractid=".$contractid.
        " and t.servicecontractorid=".$cultivatorid;
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==1)
            {
                $contractphotodetail1->fetch($row['CONTRACTPHOTODETAILID']);
                return $contractphotodetail1;
            }
            else
            {
                $i++;
            }
        }
    }

    function contracttransportfingerprintdetail(&$connection,$contractid,$sequencenumber)
    {
        $contractfingerprintdetail1 = new contractfingerprintdetail($connection);
        $query = "select d.contractfingerprintdetailid from contract c,contracttransportdetail t,contractfingerprintdetail d where c.active=1 and t.active=1 and d.active=1 and c.contractid=t.contractid and c.contractid=d.contractid and t.contracttransportdetailid=d.contractreferencedetailid and c.contractid=".$contractid." and d.contractreferencecategoryid=584251658 order by d.contractfingerprintdetailid";
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contractfingerprintdetail1->fetch($row['CONTRACTFINGERPRINTDETAILID']);
                return $contractfingerprintdetail1;
            }
            else
            {
                $i++;
            }
        }
    }
    function contractharvestfingerprintdetail(&$connection,$contractid,$sequencenumber)
    {
        $contractfingerprintdetail1 = new contractfingerprintdetail($connection);
        $query = "select d.contractfingerprintdetailid from contract c,contractharvestdetail t,contractfingerprintdetail d where c.active=1 and t.active=1 and d.active=1 and c.contractid=t.contractid and c.contractid=d.contractid and t.contractharvestdetailid=d.contractreferencedetailid and c.contractid=".$contractid." and d.contractreferencecategoryid=254156358 order by d.contractfingerprintdetailid";
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contractfingerprintdetail1->fetch($row['CONTRACTFINGERPRINTDETAILID']);
                return $contractfingerprintdetail1;
            }
            else
            {
                $i++;
            }
        }
    }

    function contractguarantorfingerprintdetail(&$connection,$contractid,$servicecontractorid)
    {
        $contractfingerprintdetail1 = new contractfingerprintdetail($connection);
        //$query = "select d.contractfingerprintdetailid from contract c,contractguarantordetail t,contractfingerprintdetail d where c.active=1 and t.active=1 and d.active=1 and c.contractid=t.contractid and c.contractid=d.contractid and t.contractguarantordetailid=d.contractreferencedetailid and c.contractid=".$contractid." and d.contractreferencecategoryid=753621495 order by d.contractfingerprintdetailid";
        $query = "select getfingerbyservicecontractorid(c.seasonid,t.servicecontractorid) as contractfingerprintdetailid from contract c,contractguarantordetail t 
        where c.active=1 and t.active=1 
        and c.contractid=t.contractid 
        and c.contractid=".$contractid.
        " and t.servicecontractorid=".$servicecontractorid;
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==1)
            {
                $contractfingerprintdetail1->fetch($row['CONTRACTFINGERPRINTDETAILID']);
                return $contractfingerprintdetail1;
            }
            else
            {
                $i++;
            }
        }
    }

    function contractguarantorfingerprintdetail_cult(&$connection,$contractid,$cultivatorid)
    {
        $contractfingerprintdetail1 = new contractfingerprintdetail($connection);
        //$query = "select d.contractfingerprintdetailid from contract c,contractguarantordetail t,contractfingerprintdetail d where c.active=1 and t.active=1 and d.active=1 and c.contractid=t.contractid and c.contractid=d.contractid and t.contractguarantordetailid=d.contractreferencedetailid and c.contractid=".$contractid." and d.contractreferencecategoryid=753621495 order by d.contractfingerprintdetailid";
        $query = "select getfingerbycultivatorid(c.seasonid,t.servicecontractorid) as contractfingerprintdetailid from contract c,contractguarantordetail t 
        where c.active=1 and t.active=1 
        and c.contractid=t.contractid 
        and c.contractid=".$contractid.
        " and t.servicecontractorid=".$cultivatorid;
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==1)
            {
                $contractfingerprintdetail1->fetch($row['CONTRACTFINGERPRINTDETAILID']);
                return $contractfingerprintdetail1;
            }
            else
            {
                $i++;
            }
        }
    }

    function contractmddetail(&$connection)
    {
        $servicecontractor1 = new servicecontractor($connection);
        $query = "select t.servicecontractorid from servicecontractor t where t.active=1 and t.servicecontractorcategoryid = 874536268";
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        $sequencenumber =1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $servicecontractor1->fetch($row['SERVICECONTRACTORID']);
                return $servicecontractor1;
            }
            else
            {
                $i++;
            }
        }
    }

    function contractagriofficerdetail(&$connection)
    {
        /* $servicecontractor1 = new servicecontractor($connection);
        $query = "select t.servicecontractorid from servicecontractor t where t.active=1 and t.servicecontractorcategoryid = 439715246";
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        $sequencenumber =1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $servicecontractor1->fetch($row['SERVICECONTRACTORID']);
                return $servicecontractor1;
            }
            else
            {
                $i++;
            }
        } */
    }

}
?>