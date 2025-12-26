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
class contract_20
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
            $pdf->multicell(150,10,'ऊस तोडणीचा व वाहतुकीचा करारनामा',0,'L',false,1,65,$liney,true,0,false,true,10);
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
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;मी पार्टी नंबर २ कारणे करारनामा लिहून देतो की, 
            मी ऊसतोड व वाहतूक ठेकेदार म्हणून बरेच वर्षापासून काम पाहत आहे. लिहून घेणार कारखान्याचे सन <u>'.$contract1->seasonname_unicode.'</u> गळीत हंगामासाठी ऊस तोड कामगार व वाहतुकीसाठी बैलगाडी आणून सदरचे काम ठेकेदारीने करणार आहे. मी व माझे सहभागीदार ऊस तोडीचे व वाहतुकीचे काम करत असतो. आपणाकडे विनंती केल्यावरून कारखान्यामध्ये कार्यक्षेत्रातील ऊस सभासदांचा / ऊस उत्पादकांच्या उसाची तोडणी व वाहतुकीचे कामकाज तुम्ही मला सदरचे काम खालील अटी व शर्तीसर देत आहात.
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
            १)&nbsp;मी सभासद व शेतकरी ऊस तोडणी व वाहतुकीसाठी <u>'.$contractharvestdetail1->transportationuptovehiclename_unicode.'</u> साठी एकूण सहभागीदार <u>'.$contractharvestdetail1->noofharvesterlabour.'</u> व <u>'.$contractharvestdetail1->noofvehicles.'</u> बैलगाडी घेऊन कारखान्याचे कार्यक्षेत्रात ऊस तोडणीसाठी व वाहतुकीसाठी काम करण्याचे कबूल केले आहे. कारखाना सुरू करणेचे तुम्ही मला कळविलेनंतर तुम्ही सांगाल त्या ठिकाणी मी माझे सहभागीदार नेऊन कामास सुरुवात करील म्हणजेच ऊसतोड व वाहतूक करण्याचे काम मी करणार आहे व सभासदांकडून / ऊस उत्पादक ऊसतोड वाहतुकीची बिले त्यांच्याकरिता तुम्ही द्यावयाची आहे. <br><br>
            २)&nbsp;मी केलेल्या ऊस तोडीची व वाहतुकीच्या कामाची बिले तुम्ही त्या त्या उत्पादकांकडून घेऊन मला वेळोवेळी तुम्ही ठरविलेल्या नियमाप्रमाणे द्यावी. याकामी मदत करावी ही बाब तुमच्या सदिच्छेतील राहील. या कामासाठी तुम्हास जो मोबदला अगर खर्चासाठी रक्कम द्यावयाची ती रक्कम आपण व ऊस उत्पादक मिळून ठरवू.<br><br>
            3)&nbsp;मी ऊस उत्पादकांबरोबर संपूर्ण सीझनसाठी त्यांना जसजशी ऊसतोड मिळेल त्यावेळी ऊसतोड वाहतूक करण्याचा करार करीत आहे. सिझन संपेपर्यंत कामाचा ठेका मी घेतला आहे.<br><br>
            ४)&nbsp;झालेले कामाचे बिलातून सुरक्षिततेचे दृष्टिकोनातून १० % रक्कम तुम्हास कपात करून देईल. कपात केलेली रक्कम तुम्हास माझे येणे बाकी पोटी परस्पर तुम्हास घेता येईल, त्यास या कराराने माझी संमती आहे.<br><br>
            ५)&nbsp;ऊस तोडीचे व राहण्याचे साहित्य कोयते, बांबू, चटया, वायररोप, टायरगाड्या वगैरे सामान मला द्यावे. सिझन संपल्यावर हे सर्व सामान तुम्हास परत करू. हे गहाळ झाल्यास अगर अफरातफर झाल्यास तुम्ही ठरवाल तेवढी रक्कम नुकसान भरपाई तुम्हास भरून देऊ ती तुम्ही आमचे कामाचे बिलातून, डिपॉझिटमधून, कमिशनचे रकमेतून परस्पर कापून घ्यावी. त्यास आम्ही संमती देत आहोत.<br><br>
            ६)&nbsp;मी व माझे सहभागीदार याने केलेल्या कामाचे बिल माझेकडे देण्यात यावे. माझेकडे सहभागीदार केलेल्या कामाचे प्रमाणात त्याची वाटणी करून देऊ तुमचेकडे तक्रार येऊ देणार नाही. अशी तक्रार आल्यास त्याचे परस्पर निवारण करील. आपण माझे होणाऱ्या बिलाच्या डिपॉझिट अगर इतर कोणत्याही जमा असलेल्या रकमेतून प्रथम संस्थेचे येणे वसूल करून राहिलेल्या रकमेतून गाडी वाहनांचे पैसे द्यावेत व ते पेमेंट माझेवर बंधनकारक राहील. तुम्हास तोशिष लागल्यास त्याची नुकसान भरपाई करून देईल. आमच्यात ज्या  ऊस उत्पादकांचे ऊसतोड वाहतुकीचे काम आम्ही करत असू त्याचे कामाचे बाबतीत मतभेद अगर वादविवाद निर्माण झाल्यास त्याकामी मध्यस्थी करावी. मध्यस्थ म्हणून तुम्ही दिलेला निर्णय आम्हास मान्य राहील.<br><br>
            ७)&nbsp;आम्ही ज्या ऊस उत्पादकांचे काम करू, त्यांचेकडून मिळणारे कमिशनची व मर्जीमधील अगर इच्छेप्रमाणे आमचे साठी दिलेली रक्कम तुमचेकडून सीझन संपल्यावर घेऊ. त्या अगोदर अशी रक्कम मागण्याचा अधिकार मला नाही.<br><br>
            ८)&nbsp;तुम्ही आम्हास टायरच्या गाड्या भाड्याने दिल्यास रीतसर तुम्ही ठरवाल ते भाडे तुम्हास देऊन आमचे होणारे ऊसतोड वाहतुकीचे बिलातून ते परस्पर कापून घ्यावे.<br><br>
            ९)&nbsp;ऊस तोडीचे काम आपले देखरेखेखाली व शेतकरी सांगतील त्याप्रमाणे मी करील. शेतकऱ्यांची कोणत्याही प्रकारची नुकसान होऊ देणार नाही.<br><br>
            १०)&nbsp;सदरचा करारनामा हा मी संपूर्ण हंगाम काम करण्यासाठीचा करून दिलेला आहे. काम नियमित व अखेरपर्यंत करेन.<br><br>
            ११)&nbsp;ऊस तोडणीनंतर निघणाऱ्या उसाच्या बांड्या मी माझे गुरांसाठी घेईन. निम्म्या शेतकरी उत्पादकांस देईन.<br><br> 
            १२)&nbsp;मला सहभागीदारांना ॲडव्हान्सच्या रकमा वाटावयाच्या आहेत. त्याप्रमाणे मलाही रकमेची जरुरी आहे. तसेच कामावर येणेसाठी म्हणजेच पूर्वतयारीसाठी मला रकमेची जरुरी आहे. या करारापोटी तुम्ही मला खालील प्रमाणे ॲडव्हान्स द्यावयाचा आहे. त्याचा तपशील खालीलप्रमाणे :<br><br> 
            </span>';
            $html2 = '<span style="text-align:justify;border-width:1px;">
            <table class="tg">
            <tr>
                <th>अ.नं.</th>
                <th>हप्ते/वर्णन</th>
                <th>रु.पैसे</th>
                <th>रक्कम बँकेत वर्ग</th>
                <th>चेक नंबर</th>
            </tr>
            <tr>
            <td></td>
            <td></td>
            </tr>
            <tr>
            <td>१)</td>
            <td>पहिला हप्ता</td>
            </tr>
            <tr>
            <td>२)</td>
            <td>दुसरा हप्ता</td>
            </tr>
            <tr>
            <td>३)</td>
            <td>तिसरा हप्ता</td>
            </tr>
            <tr>
            <td>४)</td>
            <td>वाटचाल खर्च</td>
            </tr>
            <tr>
                <td>५)</td>
                <td>इतर</td>
            </tr>
            </table><br><br></span>';
            $html3 = '<span style="text-align:justify;">
            येणेप्रमाणे अॅडव्हान्स रक्कम तुम्ही माझे खात्यावर परस्पर वर्ग केलेली आहे व करणार आहे. वरील रकमा माझे खात्यावर जमा झाले नंतर मी स्वतः काढून घेतली आहे व घेणार आहे. या करारनाम्यानुसार वरील प्रमाणे रक्कम मला मिळाली आहे. मिळणार आहे. येणेप्रमाणे रकमेचा भरणा पावला, तक्रार नाही.<br><br>
            १३&nbsp;अॅडव्हान्स ची रक्कम मी कामावर आले नंतर होणाऱ्या कामाच्या बिलातून वेळोवेळी कापून घेण्याचा अधिकार तुम्हाला दिला आहे. अॅडव्हान्सची रक्कम कामाचे बिलातून परस्पर तुम्ही वसूल करून घ्यावी. व त्यास माझी संमती आहे.<br><br>
            १४&nbsp;वर नमूद केलेप्रमाणे सहभागीदार व बैलगाडी न आल्यास अगर कामावर न आल्यास, कराराप्रमाणे मजूर न पुरविल्यास काम सोडून गेल्यास, सदरचे कृत्य हे मी जमीनदारांनी सही करून देऊन तुम्ही विश्वास ठेवून रक्कम दिली. सदर बाब ही जाणून-बुजून अप्रामाणिकपणे मी व जामीनदाराने तुम्हास फसविले आहे. व प्रामाणिकपणे संस्थेकडून रक्कम घेतली असे समजून इ पी कोड कलम ४२० व ३४ प्रमाणे अगर भारतीय दंडविधान संहितेच्या अनुसरून फिर्यादी करावी अगर लवादामार्फत योग्य तो उपयोग करावा अगर दोन्ही एकदम करावे.<br><br>
            १५&nbsp;मी जी अॅडव्हान्सची रक्कम तुमचेकडून घेतली आहे व घेणार आहे परंतु माझे कडून वरीलपैकी कोणत्याही अटीचा शर्तीचा भंग झाल्यास अॅडव्हान्स घेतलेल्या तारखेपासून रक्कम देई पावेतो द.सा.द.शे. १२ टक्के दराने अगर प्रचलित दराने नुकसानी दाखल व्याज तुम्हास मी देईल. तसेच याखेरीज करारभंग केला म्हणून जनरल स्पेशल व लिक्विडेटेड डॅमेजेस म्हणून उक्ती रक्कम रुपये   तुम्हास मी देईल.<br><br>
            १६&nbsp;या कराराबाबत काही वाद निर्माण झाल्यास सदरचा वाद हा नाशिक / नाशिक या कोर्टाचे न्यायकक्षेत राहील.<br><br>
            १७&nbsp;मी जी अॅडव्हान्सची रक्कम तुमचेकडून वेळोवेळी घेतली आहे. ती फेडण्यासाठी मी आपणास दोन जामीनदार देत आहे. जामीनदारांनी माझेसाठी जमीन, करारनामा लिहून दिला आहे. अॅडव्हान्सची रक्कम मुद्दल, नुकसानी दाखल, व्याज, नुकसान भरपाई अशी होणारी संपूर्ण रक्कम फेडण्याची माझी व जामीनदारांची वैयक्तिक व संयुक्तिक जबाबदारी आहे.<br><br>
            १८&nbsp;मी तुमचेकडून घेतलेला अॅडव्हान्स रकमेचा बोजा माझे मिळकतीवर नोंदवून दिला आहे.<br><br>
            १९&nbsp;मी फक्त आपले कारखान्याचेच ठेकेदार म्हणून काम करणार आहे.इतर  कारखान्याचा ऊस तोडणीचा अगर वाहतुकीचा ठेका घेणार नाही.<br><br>
            २०&nbsp;मी आपले कारखान्याचा ‘क’ वर्ग होणेसाठी आवश्यक ती कागदपत्रांची पूर्तता करून दिली आहे.<br><br>
            २१&nbsp;ठेकेदार म्हणून मी व सहभागीदार यांना काही दुखापत,इजा, अपघात इत्यादी बाबतची सर्व जबाबदारी माझेवर राहील. सहभागीदार व कारखान्याचा काही एक संबंध राहणार नाही. त्याचप्रमाणे माझाही कारखान्याशी ‘क’ वर्ग सभासद व ठेकेदार म्हणून संबंध राहील. इतर कोणत्याही प्रकारचा हक्क, हितसंबंध राहणार नाही.<br><br>
            २२&nbsp;कारखाना काही अपरिहार्य कारणामुळे बिघडल्यास बंद झाल्यास त्याबद्दल मी कोणतीही तक्रार करणार नाही. अगर नुकसान भरपाई मागणार नाही.<br><br>
            २३&nbsp;मी व माझे सहभागीदार तुम्ही सांगाल त्याप्रमाणे काम करू व तुमचे आदेशाचे पालन करण्याची या कराराची अटी व शर्ती आहे.<br><br>
            २४&nbsp;कराराच्या कालावधीत शेतमजूर अगर मी कोणत्याही प्रकारची कामबंदी, संप, मोर्चा,मंदगती काम, वाहतुकीस अडथळा आणणे किंवा अन्य कोणत्याही प्रकारचा अनुचित प्रथेचा अवलंब करणार नाही.<br><br>
            २५&nbsp;मी कराराचे मुदतीतील ऊसतोड किंवा वाहतूक करून स्थळात कायम हजर राहून देखरेख करील.<br><br>
            २६&nbsp;मी संस्थेकडून ऊस तोडणी मजूर पुरवणेसाठी अगर अन्य कारणांसाठी जी काही उचल होईल. किंवा उधारित घेईल ती सर्व रक्कम माझे बिलातून अगर हक्काने परस्पर वसूल करण्याची माझी संमती आहे. त्याबद्दल माझे काहीएक तक्रार नाही.<br><br>
            २७&nbsp;ऊसतोडणी सहभागीदारांचे दररोज हजेरी बुक व हिशोब ठेवील.<br><br>
            २८&nbsp;मी स्वतःहून अगर कोणत्याही शेतकऱ्यांचे सांगणेवरून कोठेही परस्पर तोडी लावणार नाही. फक्त कारखान्याचे अधिकारी सांगतील तेथेच तोडी करील.<br><br>
            २९&nbsp;माझे कामात कोणत्याही प्रकारची हाईगाई झाल्यास माझे काम केव्हाही बंद करण्याचा आपल्याला अधिकार आहे.<br><br>
            ३०&nbsp;करारातील अटी व शर्तीचे पालन माझेकडून न झाल्यास माझे नावावरील डिपॉझिटची अगर अन्य काही रक्कम जप्त करण्याचा तुम्हास अधिकार आहे. त्याबाबत मी तक्रार करणार नाही.<br><br>
            ३१&nbsp;ऊस तोडणीचे व वाहतुकीचे रेट संचालक ठरवतील ते मला मान्य व कबूल आहे.<br><br>
            ३२&nbsp;सरकारी नियमाप्रमाणे ऊस तोडणीचे व्यवसायावर कायद्यातील तरतुदीप्रमाणे जादा परिणाम भरणा झाल्यास इन्कम टॅक्स कपात मला मान्य आहे. जर मी कपात करण्याचे सर्टिफिकेट इन्कम टॅक्स डिपारमेंटकडून सादर करू शकलो नाही तर आपण माझे बिलातून  %कपात करून देण्यास माझी संपूर्ण संमती आहे.<br><br>
            ३३&nbsp;तुमचे संस्थेचे पोट नियमाप्रमाणे ऊसतोडणीची व वाहतुकीची जबाबदारी उत्पादक शेतकऱ्यांवर आहे. परंतु सदरचा करारनामा तुम्ही सभासदांना मदत करण्याचे हेतूने केलेला आहे. त्यात तुमचा कोणत्याही पैसे मिळवण्याचा हेतू नाही.<br><br>
            ३४&nbsp;येणेप्रमाणे करारनामा मी आज रोजी स्वसंतोषाने, नशापाणी न करता लिहून दिला आहे. सदरचा करारनामा मी वाचून पाहिला / वाचून दाखवला त्यावेळी माझे जामीनदार हजर होते. जमीनदारांनी या करारावर साक्षीदार म्हणून सह्या केल्या आहेत. व स्वतंत्र जामीन करारनामाही करून दिला आहे.<br><br>
            </span>';
            $pdf->SetFont('siddhanta', '', 13, '', true);
			$pdf->writeHTML($html0, true, 0, true, true);
			$pdf->SetFont('siddhanta', '', 11, '', true);
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
            $pdf->addpage();
            $liney=20;
            $pdf->SetFont('siddhanta', '', 11, '', true);
            //$liney = 50;
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