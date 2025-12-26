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
class contract_10
{	
	public $contractid;
    public $connection;
    
	public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

    function printpageheader(&$pdf,&$liney,$contractid)
    {
        $contract1 = new contract($this->connection);

		if ($contract1->fetch($contractid))
		{
            $liney = 200;
            $pdf->SetFont('siddhanta', '', 15, '', true);
            $pdf->multicell(150,10,'ट्रकचा / ट्रॅक्टर करारनामा',0,'L',false,1,65,$liney,true,0,false,true,10);
            $liney = $liney+10;
            $pdf->SetFont('siddhanta', '', 13, '', true);
            $pdf->multicell(70,10,'करार लिहून घेणार पार्टी नंबर - १,',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->SetFont('siddhanta', '', 11, '', true);
            $liney = $liney+7;
            $pdf->multicell(100,10,'मा. कार्यकारी संचालक',0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->multicell(150,10,'नाशिक सहकारी साखर कारखाना लि., पळसे',0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->multicell(150,10,'श्री संत जनार्दन स्वामी नगर ता. नाशिक जि. नाशिक',0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $contracttransportdetail1 = new contracttransportdetail($this->connection);
			$contracttransportdetail1 = $this->contracttransportdetail($this->connection,$contract1->contractid);
			$servicecontractor1 = new servicecontractor($this->connection);
			$servicecontractor1->fetch($contract1->servicecontractorid);
            $pdf->SetFont('siddhanta', '', 13, '', true);
            $pdf->multicell(70,10,'करार लिहून देणार पार्टी नंबर - २,',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+5;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $pdf->multicell(100,10,'श्री '.$servicecontractor1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
			$liney = $liney+5;
            $pdf->multicell(25,10,'वय: '.$contract1->age,0,'L',false,1,30,$liney,true,0,false,true,10);
			$pdf->multicell(10,10,'धंदा:',0,'L',false,1,100,$liney,true,0,false,true,10);
			$pdf->multicell(30,10,'ठेकेदारी व शेती',0,'L',false,1,120,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(37,$liney,100,$liney);
            $pdf->line(110,$liney,200,$liney);
            $liney = $liney+2;
            /* $area1 = new area($this->connection);
			$area1->fetch($contract1->areaid); */
            
            
            $pdf->multicell(15,10,'मु.पो.:',0,'L',false,1,30,$liney,true,0,false,true,10);
			$pdf->multicell(120,10,$contract1->address,0,'L',false,1,45,$liney,true,0,false,true,10);

			/* $pdf->multicell(10,10,'ता.:',0,'L',false,1,100,$liney,true,0,false,true,10);
			$pdf->multicell(30,10,$area1->subdistrictname_unicode,0,'L',false,1,110,$liney,true,0,false,true,10);
			$pdf->multicell(10,10,'जि.:',0,'L',false,1,150,$liney,true,0,false,true,10);
			$pdf->multicell(40,10,$area1->districtname_unicode,0,'L',false,1,160,$liney,true,0,false,true,10);
            */
            $liney = $liney+7;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $curdate = $contract1->contractdatetime;
			$pdf->multicell(180,10,'आज तारीख '.$contract1->contractdatetime.' रोज चे दिवशी मौजे श्री संत जनार्दन स्वामी नगर ता. नाशिक जि. नाशिक',0,'L',false,1,15,$liney,true,0,false,true,10);
            $pdf->line(43,$liney,200,$liney);
            $liney = $liney+5;
            $liney = $liney+2;
            $liney = $liney+10;
            $pdf->AddPage();
            $html = '<span style="text-align:justify;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;मी पार्टी नंबर २ कारणे करारनामा लिहून देतो की, 
            मी ऊसतोड व वाहतूक ठेकेदार म्हणून बरेच वर्षापासून काम पाहत आहे. लिहून घेणार कारखान्याचे सन '.$contract1->seasonname_unicode.' गळीत हंगामासाठी ऊस तोड कामगार व वाहतुकीसाठी बैलगाडी आणून सदरचे काम ठेकेदारीने करणार आहे. मी व माझे सहभागीदार ऊस तोडीचे व वाहतुकीचे काम करत असतो. आपणाकडे विनंती केल्यावरून कारखान्यामध्ये कार्यक्षेत्रातील ऊस सभासदांचा / ऊस उत्पादकांच्या उसाची तोडणी व वाहतुकीचे कामकाज तुम्ही मला सदरचे काम खालील अटी व शर्तीसर देत आहात.
            </span>';
            $pdf->writeHTML($html, true, 0, true, true);
            //$liney = $liney+60;

            /*$pdf->SetFont('siddhanta', '', 13, '', true);
            $pdf->multicell(70,10,'कराराच्या शर्ती व अटी',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->SetFont('siddhanta', '', 11, '', true);*/
			
            $html0 = '<span style="text-align:center;">
            <br>कराराच्या शर्ती व अटी<br></span>';
			$html1 = '<span style="text-align:justify;">
            १)&nbsp;पार्टी नंबर १ कारखान्याच्या सन <u>'.$contract1->seasonname_unicode.'</u> च्या गळीत हंगामासाठी पार्टी नंबर २ ची स्वतःच्या मालकीची ट्रक नंबर <u>'.$contracttransportdetail1->vehiclenumber.'</u> ही पार्टी नंबर १ या कारखान्याच्या गळीत हंगाम पूर्ण होईपर्यंत ऊस वाहतुकीसाठी कराराने देत असून त्या काळात अन्य कोणाशीही ट्रकच्या करार करणार नाही.<br><br>
            २)&nbsp;पार्टी नंबर १ या कारखान्याच्या गळीत हंगामासाठी पार्टी नंबर २ संप किंवा कारखान्याचा दैनिक गाळप क्षमतेचा पेक्षा कमी गाळप होईल असे कोणतेही कृत्य करणार नाही. तसेच अशा प्रकारचे कृत्य करण्यास पार्टी नंबर २ हे कोणासही प्रोत्साहन देणार नाही किंवा कोणत्याही युनियनचा आदेश मानणार नाही. तसेच इतर कोणावर दडपण आणणार नाही. हे पार्टी नंबर २ ने स्वखुषीने मान्य व कबूल केले आहे.<br><br>
            3)&nbsp;पार्टी नंबर २ मालकीची ट्रक ही पार्टी नंबर १ कारखान्याचा गळीत हंगाम काळात वाहतुकीस योग्य अशी ठेवीन तसेच ट्रक मध्ये बिघाड झाल्यास तो त्वरित दुरुस्त करून पार्टी नंबर १ या कारखान्याचे कार्यकारी संचालक किंवा त्यांनी अधिकार दिलेले अधिकारी हे ज्या ठिकाणी वाहतुकीसाठी च्या कामासाठी पाठवतील त्या ठिकाणी ट्रक घेऊन जाण्याचे बंधन पार्टी नंबर २ यांचेवर असेल.<br><br>
            ४)&nbsp;पार्टी नंबर १ कारखान्याचे कार्यकारी संचालक ज्यावेळी कामावर बोलावतील त्यावेळी पार्टी नंबर २ च्या मालकीची ट्रक कामावर हजर केली जाईल. तसेच ट्रक कामावर हजर करण्यास पार्टी नंबर २ ने विलंब केल्यास त्याची प्रतिदिनी रु. ५००/- प्रमाणे नुकसान भरपाई म्हणून पार्टी नंबर १ ला देण्याचे ही पार्टी नंबर २ ने मान्य व कबूल केलेले आहे.<br><br>
            ५)&nbsp;पार्टी नंबर १ कारखाना क्लीनिंगसाठी अथवा कोणत्याही कारणास्तव बंद असल्यास पार्टी नंबर २ च्या मालकीची ट्रक माननीय कार्यकारी संचालक त्याप्रमाणे पाहिजे त्यामुळे व ठिकाणी पार्टी नंबर २ थांबवून ठेवली व पार्टी नंबर १चे अधिकारी ज्यावेळी ट्रक ऊस वाहतुकीसाठी हजर करण्यासाठी त्यावेळी पार्टी नंबर २ हे ट्रक कामावर हजर ठेवतील.<br><br>
            ६)&nbsp;पार्टी नंबर १ अधिकारी अथवा त्यांनी अधिकार दिलेले कारखान्याचे कामगार यांचे सूचनेनुसार किंवा त्यांनी सांगितलेल्या मार्गानेच ट्रकची वाहतूक पार्टी नंबर २ करतील तसेच पार्टी नंबर २ हे ज्या ऊस मालकाचा कारखान्यावर गळीतासाठी वाहतूक करतील अशा उस मालकास विनामुल्य कारखान्यापर्यंत जाऊ दिले जाईल.<br><br>
            ७)&nbsp;ऊस वाहतूक करताना पार्टी नंबर २ च्या मालकीच्या ट्रकमध्ये काही बिघाड झाल्यास पार्टी नंबर २ ने २४ तासाच्या आत सदर ट्रक मधील ऊस पार्टी नंबर १ च्या कारखान्यावर जबाबदारीने पोहोच करीन.<br><br>
            ८)&nbsp;पार्टी नंबर १ कारखान्यावर पार्टी नंबर २ च्या मालकीची ट्रक ऊस भरून आल्यानंतर केनयार्डात पार्टी नंबर १ अधिकारी ज्या ठिकाणी सांगतील त्या ठिकाणी ट्रक लावला जाईल व  पार्टी नंबर १ कारखान्याचा अधिकारी त्यांच्या कोणतीही सूचनेने उल्लंघन पार्टी नंबर २ करणार नाही.<br><br>
            ९)&nbsp;पार्टी नंबर १ व पार्टी नंबर २ यांचेत वाहतुकीचा दर सन <u>'.$contract1->seasonname_unicode.'</u> च्या गळीत हंगामासाठी खालील प्रमाणे ठरलेले असून त्यात ऊस वाहतुकीचे दर व त्या अनुषंगाने जे दर ठरले आहेत. ते पार्टी नंबर २ बंधनकारक आहेत. दर ठरतील ते दोन्हीही पार्टीजना मान्य व कबूल राहतील. सदरचे दर खालीलप्रमाणे ठरले आहेत.<br>
            </span>';
            $html2 = '<span style="text-align:justify;border-width:1px;">
            <table class="tg">
            <tr>
                <th>अंतर (किलोमीटर मध्ये)</th>
                <th>वाहतुकीचे दर रुपये पैसे</th>
            </tr>
            <tr>
            <td></td>
            <td></td>
            </tr>
            <tr>
                <td>१ ते आठ किमी दर मे. टनास</td>
                <td>२०</td>
                </tr>
                <tr>
                <td>९ ते १६ किमी दर मे टनास</td>
                <td>३०</td>
            </tr>
            <tr>
                <td>१७ ते २४ किमी दर मे टनास</td>
                <td>४०</td>
            </tr>
            <tr>
                <td>२५ ते ३२ किमी दर मे टनास</td>
                <td>५०</td>
            </tr>
            <tr>
                <td>३३ ते ४० किमी दर मे टनास</td>
                <td>६०</td>
            </tr>
            <tr>
                <td>४१ ते ४८ किमी दर मे टनास</td>
                <td>७०</td>
            </tr>
            <tr>
                <td>४८ किमीचे पुढील प्रत्येकी १ कि.मी. चे २ दर मे टनास</td>
                <td>१०</td>
            </tr>
            </table>
            <br><br>तसेच ऊस वाहतूक व तोडणी बिलातून शे.१०% प्रमाणे डिपॉझिट कपात करणे ट्रकने ऊस तोडणी लेबर वाहतूक दर खेपेस पाथर्डी, मालेगाव, बीड, परभणी, उस्मानाबाद, औरंगाबाद, धुळे, चाळीसगाव, जळगाव, यवतमाळ, नांदेड, नांदगाव इतर ठिकाणी मालेगाव भाग दर किलोमीटर रुपये______ प्रमाणे भाडे ठरले असून ते पार्टी नंबर २ ला मान्य आहे. ट्रक गटातील ऊस तोडणी लेबर वाहतूक ट्रक लेबर घेऊन नेमलेल्या ठिकाणी सोडून तेथे ऊस भरून कारखान्यावर आल्यास किंवा अंतरानुसार दराप्रमाणे लेबर वाहतूक भाडे किंवा रिकामा परत आल्यास ट्रकने नेमलेल्या ठिकाणी लेबर सोडून ती ट्रक परत रिकामी लेबर भरून गेलेल्या ठिकाणी ऊस भरणेस आल्यास लेबर वाहतूक दराप्रमाणे ठरलेले उभय पार्टीजने मान्य व कबूल केले आहे.प्रेसमड वाहतुकीचे दर टनास रु___ व साखर वाहतुकीचे दर प्रति पोत्यास रु.___ ठरविण्यात आले असून ते उभय पार्टीजने मान्य व कबूल केले आहेत.<br><br>
            </span>';
            $html3 = '<span style="text-align:justify;">
            १०)&nbsp;पार्टी नंबर २ ते ऊस वाहतूक बिलातून शे.१0% रक्कम कपात केली जाईल. तसेच पार्टी नंबर १ कारखान्याच्या गळीत हंगामात पार्टी नंबर १ कारखाना पार्टी नंबर २ यास जे सामान पुरवील ते गळीत हंगाम संपल्यानंतर लगेचच जमा केले जाईल. सदर चे सामान गहाळ झाल्यास अगर हरवल्यास किंवा खराब झाल्यास पार्टी नंबर २ च्या वाहतूक बिलातून किंवा त्यांच्या जमा असलेल्या डिपॉझिट मधून त्याची रक्कम वसूल करण्यात येईल. तसेच पार्टी नंबर २ ला लागणारे पेट्रोल, डिझेल, ऑईल पार्टी नंबर १ ने दिल्यास त्याचे होणारे बिल हे पार्टी नंबर २च्या वेळोवेळी होणाऱ्या वाहतुकीच्या बिलातून परस्पर वसूल करण्यात करण्यास पार्टी नंबर २ ची संमती आहे.<br><br>
            ११)&nbsp;सदर कराराचा भंग झाल्यास योग्य त्या न्यायालयात दाद मागण्याचा व न्यायालयाने दिलेला निर्णय मान्य करण्याचे बंधन उभय पार्टीजवर राहील.<br><br>
            १२)&nbsp;पार्टी नंबर १ कारखान्याचा गळीत हंगाम चालू असताना पार्टी नंबर २ या ट्रक मालकांने इतरत्र वाहतुकीचे काम केल्यास तसेच त्याचा ड्रायव्हर क्लीनरच्या हलगर्जीपणामुळे काही नुकसान झाल्यास पार्टी नंबर १ कारखान्याचे संचालक मंडळ जो दंड ठरवतील तो पार्टी नंबर २ ने मान्य व कबूल केला असून पार्टी नंबर २ने  चांगल्या वर्तणुकीचे ड्रायव्हर व क्लीनर ठेवावे असेही उभय पार्टीजमध्ये ठरले आहे.<br><br>
            १३)&nbsp;पार्टी नंबर २ च्या मालकीच्या ट्रक वर लायसन धारक ड्रायव्हर क्लीनर ठेवण्याचे तसेच पार्टी नंबर १ कारखान्याच्या गेटच्या आत फक्त ड्रायव्हरनेच ट्रक चालवावी व कॅन यार्डात पार्टी नंबर १ कारखान्याचे अधिकारी सांगतील त्या सूचनांचे व आदेशाचे पालन न करण्याबद्दलच्या सक्त सूचना पार्टी नंबर २ त्या ट्रक वर असलेल्या ड्रायव्हर क्लीनरला देण्याचे मान्य केले आहे.<br><br>
            १४)&nbsp;पार्टी नंबर १ कारखान्याचे शेतकी अधिकारी किंवा त्यांनी अधिकार दिलेले इसम सांगतील त्याप्रमाणे पार्टी नंबर २ लेबर आणण्याची व्यवस्था करील तसेच पार्टी नंबर १ ठरवील तितक्या मुदतीपर्यंत काम करण्याचे पार्टी नंबर २ ने मान्य व कबूल केले आहे. तसेच पार्टी नंबर १ ला पार्टी नंबर २ ची ट्रक गरज नसल्यास बंद करावयाची असल्यास त्याबाबतची २४ तास अगोदर नोटीस पार्टी नंबर २ ला देण्यात येईल व त्याबद्दलची कोणतीही नुकसानभरपाई मागण्याचा अधिकार पार्टी नंबर २ ला राहणार नाही. सदरची अट ही पार्टी नंबर २ ने स्वखुशीने मान्य व कबूल केली आहे.<br><br>
            १५)&nbsp;पार्टी नंबर २ च्या मालकीच्या ट्रक वरील ड्रायव्हर क्लीनर यांनी ट्रक मध्ये १३ (तेरा) मे. टनापेक्षा ज्यादा उस भरू नये, कामावर असताना दारू पिऊ नये, रस्त्याने जाताना कोणत्याही प्रकारचे सार्वजनिक अथवा वैयक्तिक नुकसान होईल असे वर्तन करू नये. अशा प्रकारच्या सूचना पार्टी नंबर २ ने त्यांचे ड्रायव्हर, क्लीनर यांना देण्याचे मान्य व कबूल केले आहे. १३ (तेरा) मे. टनापेक्षा जादा जादा उस आणल्यास जादा येणाऱ्या उसाच्या प्रत्येक क्विंटलला रु.५/- प्रमाणे दंडाची रक्कम पार्टी नंबर १ ने वसूल करावी असेही पार्टी नंबर २ मान्य व कबूल केले आहे. तसेच सदरच्या दंडाची रक्कम परत मागण्याचा अधिकार पार्टी नंबर २ ला किंवा तिच्या युनियनला राहणार नाही.<br><br>
            १६)&nbsp;पार्टी नंबर २ च्या ट्रकने ऊस वाहतूक करताना ड्रायव्हरच्या हलगर्जीपणामुळे ट्रक मधून रस्त्याने पाऊस पडला असल्याचे पार्टी नंबर १ चे केनयार्ड विभागातील स्टाफचे अगर शेतकी अधिकारी यांचे निदर्शनास आल्यास पडलेल्या उसाचे अंदाजे वजन धरून पार्टी नंबर १ कारखाना नियमाप्रमाणे किंमत पार्टी नंबर २ च्या वाहतुकीच्या बिलातून कपात करून ऊस मालकास अदा करील व त्यासंबंधीची कोणतीही तक्रार पार्टी नंबर २ यांना राहणार नाही.<br><br>
            १७)&nbsp;पार्टी नंबर २ च्या मालकीची ट्रक ऊस वाहतूक करीत असताना रस्त्याने असणाऱ्या इलेक्ट्रिकच्या तारांना अगर टेलिफोनच्या तारांना उस अडकून अगर पोलाला ट्रकचा धक्का लागून त्याचे काही नुकसान झाल्यास व तशी इलेक्ट्रिक स्पार्किंग होऊन कोणतेही खाजगी स्वरूप नुकसान झाल्यास किंवा मनुष्य हानी झाल्यास व तशी इलेक्ट्रिक अगर टेलिफोन विभागाने अगर खाजगी स्वरूपाची नुकसान भरपाई ची तक्रार आल्यास झालेल्या नुकसानी भरपाई पार्टी नंबर २च्या ऊस वाहतूक बिलातून पार्टी नंबर १ कारखान्याने परस्पर करून दिल्यास पार्टी नंबर २ त्यास कोणतीही हरकत घेणार नाही.<br><br>
            १८)&nbsp;पार्टी नंबर १ कारखाना गेटचे आत मध्ये पार्टी नंबर २च्या ड्रायव्हरच्या बेजबाबदारपणामुळे काही अपघात होऊन नुकसान किंवा मनुष्य हानी झाल्यास फॅक्टरी अॅक्टनुसार नुसार पार्टी नंबर २ वर जबाबदार राहील.<br><br>
            १९)&nbsp;पार्टी नंबर २ च्या मालकीच्या ट्रकचे ऊस वाहतूक बिलातून आयकर कायद्याच्या तरतुदीप्रमाणे आयकर कपात करून घेण्यास पार्टी नंबर २ची संमती राहील.<br><br>
            २०)&nbsp;व्यवसाय कर अधिकारी व उत्पन्न कर अधिकारी यांनी कराची वसुली कळविल्यास ऊस वाहतूक बिलातून कराची वसुली करून घेण्यास पार्टी नंबर ३ ची संमती राहील.<br><br>
            २१)&nbsp;पार्टी नंबर २ ट्रक मालकाने ऊस वाहतुकीसाठी पार्टी नंबर १ कारखान्याकडून परतीच्या बोलीवर घेतलेले वायर रोप पार्टी नंबर २ कारखान्याच्या गळीत हंगाम बंद होताच स्टोअर विभागाकडे जमा करील पावती घेईल तसेच सदरचे वायर रोप गळीत हंगाम बंद झाले नंतर आठ दिवसांच्या आत जमा न केल्यास पुढील प्रत्येक दिवसास रुपये पाच प्रमाणे दंड देण्यास पार्टी नंबर २ जबाबदार राहील.<br><br>
            २२)&nbsp;पार्टी नंबर २ च्या नावाने करारनामा असल्याने ऊस वाहतुकीचे पेमेंट हे पार्टी नंबर २ च्या नावाने अदा केले जाईल. सदरचा करारनामा पार्टी नंबर १ व पार्टी नंबर २ यांनी आज दिनांक '.$contract1->contractdatetime.' रोजी स्वखुशीने कोणत्याही मोहाला व दडपणाला बळी न पडता तसेच करारनाम्यातील अटी व शर्ती मान्य व कबूल करून त्यावर पार्टी नंबर १ पार्टी नंबर २ यांनी साक्षीदारांना समक्ष सह्या केलेल्या आहेत.<br><br>
            </span>';
            
            $pdf->SetFont('siddhanta', '', 12, '', true);
			$pdf->writeHTML($html0, true, 0, true, true);
			$pdf->SetFont('siddhanta', '', 12, '', true);
			$pdf->writeHTML($html1, true, 0, true, true);
            //$liney = $liney+60;
            $pdf->writeHTML($html2, true, 0, true, true);
            //$liney = $liney+60;
            $pdf->writeHTML($html3, true, 0, true, true);
            //$liney = $liney+60;
            //$pdf->writeHTML($html4, true, 0, true, true);
            //$liney = $liney+60;
            //$pdf->writeHTML($html5, true, 0, true, true);
            //$liney = $liney+60;
            //$pdf->writeHTML($html6, true, 0, true, true);
            //$liney = $liney+60;
            //$liney = 160;

            $pdf->SetFont('siddhanta', '', 11, '', true);
            //$pdf->addpage();
            $liney = 80;
			$pdf->multicell(40,10,'स्थळ: श्री संत जनार्दन स्वामी नगर',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+5;
			$curdate = date('d/m/Y');
            $pdf->multicell(50,10,'दिनांक:'.$contract1->contractdatetime,0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+5;
            
			$contractphotodetail1 = new contractphotodetail($this->connection);
			$contractphotodetail1 = $this->contracttransportphotodetail($this->connection,$contract1->contractid,1);

			$imgdata = $contractphotodetail1->photo;
			$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
			$pdf->setJPEGQuality(90);
			$pdf->Image('@'.$imgdata,120,$liney,25,25);

			$contractfingerprintdetail1 = new contractfingerprintdetail($this->connection);
			$contractfingerprintdetail1 = $this->contracttransportfingerprintdetail($this->connection,$contract1->contractid,1);

			$fingerprintdata = $contractfingerprintdetail1->fingerprint;
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
			$liney = $liney+10;

			//QRCODE,H : QR-CODE Best error correction
			//$pdf->write2DBarcode('www.swapp.co.in', 'QRCODE,H', 140, 210, 25, 25, $style, 'N');
			//$pdf->Text(140, 205, 'Nigade Software Technologies (opc) Private Limited');

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

            /* $area2 = new area($this->connection);
            $area2->fetch($servicecontractor_guarantor2->areaid); */

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

            $pdf->SetFont('siddhanta', '', 11, '', true);
            $pdf->multicell(35,10,'जामीनदार',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+10;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            
            $pdf->multicell(40,10,'१)सही',0,'L',false,1,15,$liney,true,0,false,true,10);
			//$liney = $liney+5;
            $pdf->rect(50,$liney,10,10);
            $liney = $liney+14;
			$pdf->multicell(100,10,'नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
            $pdf->multicell(100,10,$servicecontractor_guarantor1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+2;

            $pdf->multicell(100,10,'पत्ता:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$contractguarantordetail1->address,0,'L',false,1,30,$liney,true,0,false,true,10);
            
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+10;

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

            $pdf->multicell(100,10,'२)सही',0,'L',false,1,15,$liney,true,0,false,true,10);
			//$liney = $liney+5;
            $pdf->rect(50,$liney,10,10);
            $liney = $liney+14;
            $pdf->multicell(100,10,'नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$servicecontractor_guarantor2->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+2;
            $pdf->multicell(100,10,'पत्ता:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(100,10,$contractguarantordetail1->address,0,'L',false,1,30,$liney,true,0,false,true,10);
            
            $liney = $liney+5;
            $pdf->line(30,$liney,100,$liney);
            $liney = $liney+2;
            $liney = $liney+10;
            
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
			$pdf->multicell(100,10,'शेतकी अधिकारी',0,'L',false,1,45,$liney,true,0,false,true,10);
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

    function contractguarantordetail(&$connection,$contractid,$sequencenumber)
    {
        $contractguarantordetail1 = new contractguarantordetail($connection);
        $query = "select d.contractguarantordetailid from contract c,contractguarantordetail d,servicecontractor t where c.active=1 and d.active=1 and t.active=1 and c.contractid=d.contractid and d.servicecontractorid=t.servicecontractorid and c.contractid=".$contractid;
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contractguarantordetail1->fetch($row['CONTRACTGUARANTORDETAILID'],1);
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