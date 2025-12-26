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
    include_once("../api_oracle/contractphotodetail_db_oracle.php");
    include_once("../api_oracle/contractfingerprintdetail_db_oracle.php");
class contract_54
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
            $liney = 200;
            $pdf->SetFont('siddhanta', '', 15, '', true);
            $pdf->multicell(150,10,'ऊस तोडणी यंत्र तोडणीचा व वाहतुकीचा करारनामा',0,'L',false,1,65,$liney,true,0,false,true,10);
            $liney = $liney+10;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $curdate = date('d/m/Y');
			$pdf->multicell(180,10,'आज तारीख '.$curdate.' रोज चे दिवशी मौजे श्री संत जनार्दन स्वामी नगर, ता.नाशिक, जि.नाशिक',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+7;
            $pdf->SetFont('siddhanta', '', 13, '', true);
            $pdf->multicell(70,10,'लिहून घेणार,',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->SetFont('siddhanta', '', 11, '', true);
            $liney = $liney+7;
            $pdf->multicell(100,10,'मा. कार्यकारी संचालक / सेक्रेटरी',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->multicell(150,10,'नाशिक सहकारी साखर कारखाना लि., पळसे',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->multicell(150,10,'श्री संत जनार्दन स्वामी नगर, ता.नाशिक, जि.नाशिक',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+7;
            
			//$contracttransportdetail1 = new contracttransportdetail($this->connection);
			//$contracttransportdetail1 = $this->contracttransportdetail($this->connection,$contract1->contractid);
			$servicecontractor1 = new servicecontractor($this->connection);
			$servicecontractor1->fetch($contract1->servicecontractorid);
			$contractharvestdetail1 = new contractharvestdetail($this->connection);
			$contractharvestdetail1 = $this->contractharvestdetail($this->connection,$contract1->contractid);
            $pdf->SetFont('siddhanta', '', 13, '', true);
            $pdf->multicell(70,10,'लिहून देणार,',0,'L',false,1,15,$liney,true,0,false,true,10);
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $liney = $liney+7;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $pdf->multicell(100,10,'श्री '.$servicecontractor1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $pdf->multicell(25,10,'वय:'.$contract1->age,0,'L',false,1,30,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,'धंदा: ठेकेदारी व शेती',0,'L',false,1,100,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $pdf->line(110,$liney,200,$liney);
            $liney = $liney+2;
           /*  $area1 = new area($this->connection);
			$area1->fetch($contract1->areaid); */
            
            
            $pdf->multicell(15,10,'मु.पो.:',0,'L',false,1,30,$liney,true,0,false,true,10);
			$pdf->multicell(30,10,$contract1->address,0,'L',false,1,45,$liney,true,0,false,true,10);

			/* $pdf->multicell(10,10,'ता.:',0,'L',false,1,100,$liney,true,0,false,true,10);
			$pdf->multicell(30,10,$area1->subdistrictname_unicode,0,'L',false,1,110,$liney,true,0,false,true,10);

			$pdf->multicell(10,10,'जि.:',0,'L',false,1,150,$liney,true,0,false,true,10);
			$pdf->multicell(40,10,$area1->districtname_unicode,0,'L',false,1,160,$liney,true,0,false,true,10);
 */
   			$liney = $liney+5;
			$pdf->line(43,$liney,100,$liney);
			/* $pdf->line(105,$liney,145,$liney);
            $pdf->line(157,$liney,200,$liney); */
            $liney = $liney+2;
            $liney = $liney+5;
            $pdf->SetFont('siddhanta', '', 13, '', true);
            
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $pdf->AddPage();
            $html = '<span style="text-align:justify;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;वाहन प्रकार - ऊस तोडणी यंत्रासह आवश्यक मशीन / ट्रेलर इ.( ट्रेलर /  ट्रॅक्टर )
            </span>';
            $pdf->writeHTML($html, true, 0, true, true);
            $html = '<span style="text-align:justify;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;मी खाली सही करणार ऊस तोडणी यंत्रासह ट्रॅक्टर्स /  ट्रेलरने /  ट्रक /  ट्रॅक्टर  ने ऊस वाहतुकीचा व तोडणीचा करारनामा खालील अटी शर्तींवर माझे स्वत:करिता व माझे वतीने लिहून देतो की ,
            </span>';
            $pdf->writeHTML($html, true, 0, true, true);
            //$liney = $liney+60;

            /*$pdf->SetFont('siddhanta', '', 13, '', true);
            $pdf->multicell(70,10,'कराराच्या शर्ती व अटी',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->SetFont('siddhanta', '', 11, '', true);*/
			
			$html1 = '<span style="text-align:justify;">
            १.&nbsp;ऊस उत्पादक सभासद / बिगर सभासद कारखान्यास ऊस पुरविताना ऊसाची तोड वाहतूक करून कारखान्यावर पोहोचता करण्याची जबाबदारी त्यांची आहे . ऊसाची तोड वाहतूक यासाठी संचालक मंडळ ठरवील  त्या दराने खर्च व कमीशन सभासदांना /  बिगर सभासदांना मिळते . ठरवून दिलेल्या कोट्याप्रमाणे काही प्रसंगी सभासदांना /  बिगर सभासदांना ऊसतोड वाहतूक करणे अवघड जाते . त्यांना ऐनवेळी आमच्या बरोबर संपर्क साधणे अवघड जाते तसेच आम्ही दररोज तोड-वाहतुकीचे काम सभासदांकडे /  बिगर सभासदांकडे जाऊन मिळवणे त्रासाचे होते . सभासदा कडील बिगर सभासदांकडे ठरवून दिलेल्या कोट्याप्रमाणे ऊस तोडणी व वाहतूक करून पोहोचता केले नाही तर त्यासाठी ऊस तोडणी वाहतूक करणारी यंत्रणा ( ट्रक /  ट्रॅक्टर ) लावून या सभासदास /  बिगर  सभासदाचे मिळणारी खर्चाची व कमिशनची रक्कम अशा ऊस तोड वाहतूक करणार्‍या यंत्रणेस सभासदाचे /  बिगर सभासदाचे वतीने त्यांनी तुम्हास अधिकार दिलेला आहे . हे मला माहित आहे .<br><br>
            २.&nbsp;हा करार तुम्हास तुमचे सभासद /  बिगर सभासदाचे वतीने व त्यांचेसाठी लिहून देत आहे  .<br><br>
            ३.&nbsp;ऊस तोड /  वाहतुकीचे कामसाठी आपण सांगेल त्या वेळी मी ऊस तोडणी यंत्रासह ट्रॅक्टर्स ट्रेलर्स चालू स्थितीत हजर ठेवेन . मी व माझे सहभागीदार या करारान्वये कारखान्याचे नोकर होत नाहीत व होणार नाहीत व कराराने कारखाना व आमच्यामध्ये मालक व नोकर असे नाते निर्माण होत नाही आणि होणार नाही .<br><br>
            ४.&nbsp;कारखान्याच्या कोणत्याही गटात व गावात तसेच गेटकेन मध्ये कोणत्याही ठिकाणाहून आपण अनुमती दिलेल्या ऊस पुरवठा दरानेच ऊसाची बिन तक्रार नुकसान न होऊ देता तोड /  वाहतूक करीन . दुसरीकडचा ऊस मी परस्पर आणणार नाही . जर तसे झाले तर माझे ऊस तोडणी यंत्र  (ट्रक /  ट्रॅक्टर सह ) कोणाचाही ऊस आणण्यास परवानगी न देण्याचा अधिकार राहील .<br><br>
            ५.&nbsp;ऊस उत्पादक सभासद /  बिगर सभासदास ठरवून दिलेल्या ऊसाचा कोटा तोडून कारखान्यावर वाहतूक करता आली नाही तर त्याप्रसंगी मी व माझे सहभागीदार ऊस तोडणी यंत्रासह ट्रॅक्टर ट्रेलर्स  ने तोडून देवून ऊस तोड /  वाहतुकीची व्यवस्था करू .<br>
            ६.&nbsp;कारखान्याचे संचालक मंडळ ऊस तोड /  वाहतूक व कमीशनचे जे दर ठरवील त्याप्रमाणे सभासद /  बिगर सभासदास कारखाना जो खर्च देईल ती रक्कम आम्ही तोड /  वाहतूक करून त्याचेसाठी व करिता तसेच त्याचे वतीने तुम्ही आम्हास त्यांच्या अधिकार पत्राआधारे द्यावी ती खर्चाची रक्कम अशी सभासदांना /  बिगर सभासदास तुम्ही परस्पर देऊ नये . आम्हास खर्चाची रक्कम अशा सभासदांकडून / बिगर सभासदांकडून मिळावी .<br><br>
            ७.&nbsp;माझ्या ड्रायव्हरच्या ,  क्लीनरच्या व सहभागीदाराच्या वर्तनुकीबद्दल मी जबाबदार राहीन . मला ऊस उत्पादक सभासद / बिगर सभासदासाठी आणि शिस्ती बाबत द्यायच्या सूचना माझे ड्रायव्हरला व क्लिनरला द्याव्यात . त्या माझ्यावर बंधनकारक राहतील . अचानक अनपेक्षित कारखाना बंद पडल्यास  कोणतीही नुकसान भरपाई मागणार नाही . केनयार्डातील पार्किंग लाईनमध्ये माझे ट्रक /  ट्रेलर्स कितीही वेळ थांबली तरी तक्रार करणार नाही . नुकसान भरपाई / अतीरीक्त भाडे मागणार नाही .<br><br>
            ८.&nbsp;आम्ही ऊस तोडणी यंत्राने ऊस भुईसपाट तोडून साळून , देऊन संबंधीतांचे ऊसाचे कोणत्याही प्रकारे नुकसान होऊ देणार नाही .  त्याची तक्रार असल्यास त्याचे आम्ही निवारण करून त्याची तक्रार येऊ देणार नाही .<br><br>
            ९.&nbsp;मी कारखान्यास मजूर मागणार नाही तसेच ऊस तोडणीसाठी हंगाम सुरू होते वेळी त्यांच्या गावापासून आणणे व बंद नंतर त्यांचे गावी मजूर सोडण्यासाठी कारखाना व्यतिरिक्त लेबर वाहतूक भाडेपोटी रक्कम मागणार नाही . मजूर टोळीची उपलब्धता मी स्वतः माझ्या जिम्मेदारीवर करून घेईन .<br><br>
            १०.&nbsp;ऊस प्लॉट पासून मुख्य रस्ता / वाहनापर्यंत आणण्याचे मजुरामार्फत वहातूक करण्यात येईल ज्यास वेगळा  मोबदला लागणार नाही . ऊस तोड /  वाहतुकीचे काम करतांना कारखान्याच्या तसेच ऊस उत्पादकांच्या देखरेखी खाली काम करू .<br><br>
            ११.&nbsp;आपल्या ऊस उत्पादक सभासदाचे /  बिगर सभासदाचे वतीने आपण बोलवाल तेव्हापासून आपण सांगाल तो पर्यंत काम करीन . मध्येच सोडून गेलो तर नुकसान भरपाई करून देण्यास पात्र राहील . माझी  तोडणी यंत्रामागील  ट्रक / ट्रॅक्टर बंद पडल्यास ऊस पुरवठादाराचे वाहतुकीवर ताबडतोब दुसरी ट्रक /  ट्रॅक्टर लावीन . त्याच प्रमाणे ट्रक / ट्रॅक्टर विकल्यास किंवा बिघडल्यास दुरुस्तीस पाठविल्यास दुसरी ट्रक /  ट्रॅक्टर लावीन .<br><br>
            १२.&nbsp;प्रत्येक क्लिनिंग नंतर लगेच ट्रक /  ट्रॅक्टर सह तोडणी यंत्र कामावर आले नाही किंवा दिलेल्या बदली प्रमाणे ऊस पुरवठादाराचे ऊस वाहतुकीवर गेलो नाही तर शेतकऱ्याचे नुकसानीस जबाबदार धरून त्याचे वतीने आपण मला जी नुकसान भरपाई अगर दंड आकारला तो मान्य राहील .<br><br>
            १३.&nbsp;मी माझे सहभागीदारांचे वर्तनुकीस जबाबदार राहीण. आमचेकडून गैरवर्तणूक झाल्यास आम्हास काम द्यायचे की नाही हे त्या त्या सभासदाच्या /  बिगर सभासदांच्या वतीने आपले इच्छेवर अवलंबून राहील . तसेच आम्हास ऊस तोड वाहतूक द्यायची की नाही हे त्या त्या सभासदांचे /  बिगर सभासदाच्या इच्छेवर अवलंबून राहील कारण त्या त्यांच्याकरिता आपण हा करार केलेला आहे .<br><br>
            १४.&nbsp;ऊस उत्पादकाचे ऊसाचे प्लॉटमध्ये जास्तीत जास्त जवळ ट्रक /  ट्रॅक्टर घेऊन जाईन .<br><br>
            १५.&nbsp;अचानक व अनपेक्षित कारणाने किंवा साफसफाईसाठी कारखाना बंद राहिल्यास ज्या ज्या वेळी सभासदाचा कोटा तहकूब होईल त्यावेळी व तशा प्रसंगी आम्ही आमचे कामे थांबून सभासद /  बिगर सभासद सांगतील त्या वेळी परत चालू करू यासाठी उत्पादक सभासद /  बिगर सभासदांकडून नुकसान भरपाई घेण्याचा आम्हास अधिकार नाही .<br><br>
            १६.&nbsp;शेतातून ट्रॅक्टर भरलेवर तीन (३) तासाच्या आत कारखान्यावर पोहोचवीन तसेच ऊस पुरवठादाराचे ऊस वाहतुकीच्या कामाशिवाय इतर माल वाहतुकीचे भाडे वगैरे करणार नाही .<br><br>
            १७.&nbsp;मी माझे सहभागीदार सभासदांचे ऊसतोड वाहतुकीचे जे काम करतील त्याची रक्कम माझे सहभागीदाराचे वतीने व त्यांच्यासाठी सभासद /  बिगर सभासदातर्फे व त्यांच्यासाठी त्यांनी तुम्हास दिलेल्या अधिकार पत्राप्रमाणे संबंधित कर्ज घेतलेल्या बँकेस आपण पाठवून देण्यात यावी . त्याची तक्रार तुमच्याकडे येऊ देणार नाही . या अटींची पूर्ण कल्पना व माहिती मी माझ्या सहभागीदारांना दिलेली आहे .<br><br>
            १८.&nbsp;मी गळीत हंगामाच्या सुरुवातीस येऊन हंगाम अखेरपर्यंत तुमच्या ऊस उत्पादक सभासदाचे / बिगर सभासदाचे त्यांचे जरुरीप्रमाणे शेवटपर्यंत काम करून देईन आम्ही उत्पादक सभासदाची /  बिगर सभासदाची गैरसोय करणार  नाही . मध्ये काम टाकुन दिल्याने ऊस उत्पादक सभासदाची / बिगर  सभासदाची जी नुकसान भरपाई होईल ती आम्ही भरून देवू .<br><br>
            १९.&nbsp;मी अगर ड्रायव्हर प्लॉटमध्ये ट्रॅक्टर भरतांना गव्हाणीनीवर गाडी खाली होत असतांना सतत हजर राहीन . तसेच केनयार्डमध्ये ट्रॅक्टर  यार्डावर  आपले सर्व नियम पाळीन .<br><br>
            २0.&nbsp;तुमच्या सभासदांचे /  बिगर सभासदांचे जे काम आम्ही करू त्या कामाचे बील अधिकार पत्रान्वये सभासदांसाठी व बिगर सभासदांसाठी व तर्फे तुम्ही दर पंधरवाड्यास संबंधित कर्ज देणाऱ्या बँकेस पाठवावे . तुमच्याकडे शेकडा १0 टक्के प्रमाणे डिपॉझीट म्हणून रक्कम कापून ठेवावी . या कारारतील शर्ती पूर्ण झाल्याशिवाय  मला ही रक्कम मागता येणार नाही .  डिपॉझीटची रक्कम व कारखाना नियमाप्रमाणे कमीशन रक्कम संबंधित कर्जदार बँकेस पाठविण्यात यावी .<br><br>
            २१.&nbsp;करारातील मुदतीत मी माझा ( ट्रक /  ट्रॅक्टर ) ऊस तोडणी यंत्र व ट्रक /  ट्रॉलीस इ. कारखान्याचे परवानगी शिवाय विकणार नाही .  सदर कराराची जबाबदारी माझ्या वारसदारावर देखील राहील . ऊस तोडणी यंत्राचे कागदपत्राच्या प्रति तुमच्याकडे दिल्या आहेत .<br><br>
            २२.&nbsp;मला अगर माझ्या सहभागीदारांना अपघात झाल्यास त्याची कोणतीही जबाबदारी तुमच्यावर अथवा तुमच्या सभासद /  बिगर सभासदांवर राहणार नाही . त्याचप्रमाणे माझ्या सहभागीदारांच्या जोखमीचा विमा मी स्वताहून  काढून घेईन . <br><br>
            २३.&nbsp;ऊस उत्पादकांना माझे ट्रॅक्टर ने करवायचे ऊस तोडणी वाहतुकीसाठी कारखान्यांकडून दिल्या जाणाऱ्या व  त्याचे अधिकार पत्राआधारे मला मिळणाऱ्या रकमेतून प्रत्येक वेळी दहा टक्के डिपॉझीट कापून घेण्यास माझी  संमती आहे .<br><br>
            २४.&nbsp;आपल्या कारखान्याच्या डिझेल पंपावर मी डिझेल अथवा तेल उधार घेतल्यास त्याची रक्कम मला उत्पादकाचे अधिकारान्वये होणाऱ्या ऊस वाहतूक तोड रकमेतून परस्पर कपात करून घ्यावी .<br><br>
            २५.&nbsp;कारखान्याच्या भुईकाट्यावर भरतीच्या व रिकाम्या काट्याचे वजन तुमच्या नियमाप्रमाणे शिस्तीत करून व त्याच्या वजन पत्रिका घेऊन त्यात दाखविलेले वजन अखेरचे समजण्यात येईल . त्याबद्दल तक्रार राहणार नाही .<br><br>
            २६.&nbsp;मी आपल्या स्टोअरमधून आपल्या ऊस पुरवठादाराचे काही लोन /  उधार स्टोअर माल घेतलेस अथवा वर्कशॉपमधून काम करून घेतले तर त्याचे बिल मला देण्यात येणाऱ्या रकमेतून परस्पर कपात करून घ्यावे .<br><br>
            २७.&nbsp;प्रत्येक गाडी मागे भरावी लागणारी लायसन्स फी इ. मी अगर माझे सहभागीदार यांनी भरवायचे आहे . त्याची जबाबदारी तुमचेवर व तुमच्या ऊस उत्पादक  सभासद /  बिगर सभासदांवर नाही .<br><br>
            २८.&nbsp;माझे तोडणी व ट्रॅक्टरचे ऊस वाहतूक बिलातून कपात झालेली डिपॉझीट रक्कम व कारखान्याचे नियमाप्रमाणे देण्यात येणारे कमीशन हंगाम बंद नंतर भरल्यास संबंधित कर्ज देणाऱ्या बँकेस देण्यात यावी .<br><br>
            २९.&nbsp;मी अगर माझे प्रतिनिधी सभासदांच्या /  बिगर सभासदाच्या ऊसाच्या थळातून प्रत्यक्ष हजर राहून तोडलेला सर्व माल ट्रक ट्रॅक्टरने आमच्या देखरेखीखाली भरून पाठवू .  कारखान्याची व सभासदांची /  बिगर सभासदाची ऊस गळीताची  नुकसान गैरसोय होऊन देणार नाही .  तसेच ऊसाचे नुकसान होऊ देणार नाही .<br><br>
            ३0.&nbsp;कामावर असताना ड्रायव्हर /  क्लीनर यास अगर इतर काही अपघात झाल्यास मी जबाबदार राहीन . त्याची तोशीष कारखान्यावर लागू देणार नाही . त्याचप्रमाणे योग्य त्या प्रकारचा विमा उतरून घेईन .<br><br>
            ३१.&nbsp;कारखाना सुरू होण्याच्या वेळी मी माझे टोळीतील सहकारी परवानगी दिलेल्या ऊस तोडणी यंत्रासाह ट्रॅक्टर ट्रेलर्स वेळेवर हजर ठेवू .  आम्ही वेळेवर हजर न केल्यास सभासदाची / बिगर सभासदाची ऊस तोड वाहतूक आम्हास देणे अगर न देणे तुमच्या मर्जीवर राहील .<br><br>
            ३२.&nbsp;मी व माझे सहभागीदार यांना संप व हरताळ करून कारखान्याचे कामकाजात व्यत्यय आणून काम बंद पाडले त्यामुळे कारखान्यास व सभासदास /  बिगर सभासदसाच कोणत्याही प्रकारचे नुकसान व तोशीष सोसावी लागल्यास मी व माझे सहभागीदार ती भरून देऊ .<br><br>
            ३३.&nbsp;सदर कराराने व्यवहाराबाबत अगर शर्तीबाबत सभासदाचे /  बिगर सभासदाचे अगर आमचे दरम्यान अगर तुमचे आमचे काही वाद झाल्यास व महाराष्ट्र को-ऑप सोसायटी ॲक्ट सन १९६० कलम ९१ प्रमाणे लावून तो निर्णय माझे सहभागीदारावर व माझेवर बंधनकारक राहीन .<br><br>
            ३४.&nbsp;&nbsp;&nbsp;&nbsp;मी आपल्या कारखान्याचा नाममात्र सभासद आहे /  होईल त्यासाठी दिनांक …………………... पावती क्रमांक …………………..  अन्वये १००/- सभासद प्रवेश फी भरलेली आहे .<br><br>
            ३५.&nbsp;मी कारखान्याकडुन तोडणी वाहतुक कामापोटी सभासद/बिगर सभासद याकडुन व मशिनदुरुस्ती कामासाठी ड्रायव्हर पगार बँक हप्ता इत्यादींसाठी अँडव्हांस मागणार नाही.<br><br>
            ३६.&nbsp;यंत्राद्वारे तोडलेल्या ऊसामध्ये कारखाना नियमाप्रमाणे बांडीपाचट आल्यास त्याची होणारी वजावट मला मान्य राहील.त्यापेक्षा जादा पाचट आल्यास मी दंडास पात्र राहील..<br><br>
            ३७.&nbsp;तोडलेल्या ऊसामध्ये दगड,लोखंड,बांडी इ.येणार नाही, आल्यास व त्यामुळे कारखाना यंत्रसामुग्रीमध्ये बिघाड झाल्यास त्याची भरपाई करुन देणेस मी जबाबदार राहील.<br><br>
            ३८.&nbsp;कारखान्याचे सध्या नियम व वेळोवेळी बदलणारे नियम मला व माझ्या सहभागीदारांना बंधनकारक राहतील .<br><br>
                             (अ) ऊस तोडणी यंत्र <br><br>
            करीता हा करारनामा मी व  माझे सहभागीदारांच्या वतीने व त्यांच्यासाठी वाचून समजावून करून दिला आहे .<br><br>
            </span>';
			$pdf->SetFont('siddhanta', '', 11, '', true);
			$pdf->writeHTML($html1, true, 0, true, true);
            //$pdf->addpage();
            //$liney=20;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $liney = 70;
            $pdf->multicell(40,10,'स्थळ: श्री संत जनार्दन स्वामी नगर',0,'L',false,1,15,$liney,true,0,false,true,10);
			$curdate = date('d/m/Y');
            $pdf->multicell(50,10,'दिनांक:'.$curdate,0,'L',false,1,100,$liney,true,0,false,true,10);
			$liney = $liney+5;
            
			$contractphotodetail1 = new contractphotodetail($this->connection);
			$contractphotodetail1 = $this->contractharvestphotodetail($this->connection,$contract1->contractid,1);

			$imgdata = $contractphotodetail1->photo;
			$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
			$pdf->setJPEGQuality(90);
			$pdf->Image('@'.$imgdata,120,$liney,25,25);

			$contractfingerprintdetail1 = new contractfingerprintdetail($this->connection);
			$contractfingerprintdetail1 = $this->contractharvestfingerprintdetail($this->connection,$contract1->contractid,1);

			$fingerprintdata = $contractfingerprintdetail1->fingerprint;
			$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
			$pdf->setJPEGQuality(90);
			$pdf->Image('@'.$fingerprintdata,160,$liney,25,25);

			$pdf->multicell(60,10,'करारनामा लिहून देणार',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->multicell(40,10,'सही',0,'L',false,1,15,$liney,true,0,false,true,10);
            //$liney = $liney+5;
            $pdf->rect(50,$liney,10,10);
            $liney = $liney+10;
            $pdf->multicell(100,10,'नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,$servicecontractor1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+2;
			$liney = $liney+5;

			//QRCODE,H : QR-CODE Best error correction
			//$pdf->write2DBarcode('www.swapp.co.in', 'QRCODE,H', 140, 210, 25, 25, $style, 'N');
			//$pdf->Text(140, 205, 'Nigade Software Technologies (opc) Private Limited');

            $servicecontractor_md1 = new servicecontractor($this->connection);
			$servicecontractor_md1 = $this->contractmddetail($this->connection);

			$pdf->multicell(60,10,'करारनामा लिहून घेणार',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+7;
			$pdf->multicell(100,10,'सही',0,'L',false,1,15,$liney,true,0,false,true,10);
			//$liney = $liney+5;
			$pdf->rect(50,$liney,10,10);
            $liney = $liney+10;
			$pdf->multicell(100,10,'नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$servicecontractor_md1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+5;
			$pdf->line(30,$liney,100,$liney);
			$liney = $liney+2;
			$pdf->multicell(100,10,'मा. कार्यकारी संचालक',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+7;
			$pdf->multicell(100,10,'नाशिक सह. सा.का.लि.',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+10;
			$liney = $liney+5;

            $pdf->multicell(100,10,'जामीनदार',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+5;
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

    function contractguarantorphotodetail(&$connection,$contractid,$sequencenumber)
    {
        $contractphotodetail1 = new contractphotodetail($connection);
        $query = "select d.contractphotodetailid from contract c,contractguarantordetail t,contractphotodetail d where c.active=1 and t.active=1 and d.active=1 and c.contractid=t.contractid and c.contractid=d.contractid and t.contractguarantordetailid=d.contractreferencedetailid and c.contractid=".$contractid." and d.contractreferencecategoryid=753621495 order by d.contractphotodetailid";
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

    function contractguarantorfingerprintdetail(&$connection,$contractid,$sequencenumber)
    {
        $contractfingerprintdetail1 = new contractfingerprintdetail($connection);
        $query = "select d.contractfingerprintdetailid from contract c,contractguarantordetail t,contractfingerprintdetail d where c.active=1 and t.active=1 and d.active=1 and c.contractid=t.contractid and c.contractid=d.contractid and t.contractguarantordetailid=d.contractreferencedetailid and c.contractid=".$contractid." and d.contractreferencecategoryid=753621495 order by d.contractfingerprintdetailid";
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
        $servicecontractor1 = new servicecontractor($connection);
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
        }
    }
}
?>