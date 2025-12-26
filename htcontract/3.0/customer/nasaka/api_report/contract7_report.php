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
    include_once("../api_oracle/contractwitnessdetail_db_oracle.php");
class contract_7
{	
	public $contractid;
    public $connection;
    
	public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

    function printpageheader(&$pdf,&$liney,$contractid)
    {
    	require("../info/phpsqlajax_dbinfo.php");
    	// Opens a this->connection to a MySQL server
        //$this->connection=mysqli_connect($hostname_rawmaterial, $username_rawmaterial, $password_rawmaterial, $database_rawmaterial);
        // Check this->connection
        /* if (mysqli_connect_errno())
        {
            echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication error1</span>';
            exit;
        }
        mysqli_query($this->connection,'SET NAMES UTF8'); */
        //$this->connection = new rawmaterial_connection();
        $contract1 = new contract($this->connection);

		if ($contract1->fetch($contractid))
		{
            $servicecontractor1 = new servicecontractor($this->connection);
			$servicecontractor1->fetch($contract1->servicecontractorid);
            $pdf->SetFont('siddhanta', '', 15, '', true);
            $pdf->multicell(150,10,'जामिन रोखा',0,'L',false,1,85,$liney,true,0,false,true,10);
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $curdate = date('d/m/Y');
            $liney = $liney+15;
            $pdf->multicell(180,10,'जामीन रोखा आज तारीख '.$curdate.' रोज चे दिवशी मौजे श्री संत जनार्दन स्वामी नगर, ता.नाशिक, जि.नाशिक',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+7;
            $pdf->SetFont('siddhanta', '', 13, '', true);
            $pdf->multicell(70,10,'लिहून घेणार,',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->SetFont('siddhanta', '', 11, '', true);
            $liney = $liney+5;
            $pdf->multicell(100,10,'मा. कार्यकारी संचालक',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->multicell(150,10,'नाशिक सहकारी साखर कारखाना लि., पळसे',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->multicell(150,10,'श्री संत जनार्दन स्वामी नगर, ता.नाशिक, जि.नाशिक',0,'L',false,1,15,$liney,true,0,false,true,10);
            $liney = $liney+10;
            //$contractguarantordetail1 = new contractguarantordetail($this->connection);
			//$contractguarantordetail1 = $this->contractguarantordetail($this->connection,$contract1->contractid,1);
            /* $servicecontractor_guarantor1 = new servicecontractor($this->connection);
			$servicecontractor_guarantor1->fetch($contractguarantordetail1->servicecontractorid);
             */
            $pdf->SetFont('siddhanta', '', 13, '', true);
            $pdf->multicell(70,10,'लिहून देणार,',0,'L',false,1,15,$liney,true,0,false,true,10);
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $liney = $liney+7;
            $contractguarantordetail1 = new contractguarantordetail($this->connection);
            $servicecontractor_guarantor1 = new servicecontractor($this->connection);
            
            $list1 = $contract1->guarantorcontractorlist();
            $i=0;
            foreach ($list1 as $value)
            {
                $val = intval($list1[$i]);
                $contractguarantordetail1->fetch($val,1);
                $servicecontractor_guarantor1->fetch($contractguarantordetail1->servicecontractorid);
                $pdf->multicell(30,10,'जामीनदार '.++$i.')',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(100,10,'श्री '.$servicecontractor_guarantor1->name_unicode,0,'L',false,1,40,$liney,true,0,false,true,10);
                $pdf->multicell(25,10,'वय: '.$contractguarantordetail1->age,0,'L',false,1,130,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(30,$liney,130,$liney);
                $pdf->line(135,$liney,200,$liney);
                $liney = $liney+2;

                $pdf->multicell(10,10,'धंदा:',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(50,10,'शेती व ठेकेदारी',0,'L',false,1,30,$liney,true,0,false,true,10);
                $pdf->multicell(20,10,'मु.पो.:',0,'L',false,1,80,$liney,true,0,false,true,10);
                $pdf->multicell(120,10,$contractguarantordetail1->address,0,'L',false,1,95,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(30,$liney,80,$liney);
                $pdf->line(95,$liney,200,$liney);
                $liney = $liney+2;
            }
            $liney = $liney+7;
            $list1 = $contract1->guarantorcultivatorlist();
            $servicecontractor_guarantor1 = new cultivator($this->connection);
            $j=0;
            foreach ($list1 as $value)
            {
                $val = intval($list1[$j++]);
                $contractguarantordetail1->fetch($val,2);
                $servicecontractor_guarantor1->fetch($contractguarantordetail1->servicecontractorid);
                $pdf->multicell(30,10,'जामीनदार '.++$i.')',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(100,10,'श्री '.$servicecontractor_guarantor1->name_unicode,0,'L',false,1,40,$liney,true,0,false,true,10);
                $pdf->multicell(25,10,'वय: '.$contractguarantordetail1->age,0,'L',false,1,130,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(30,$liney,130,$liney);
                $pdf->line(135,$liney,200,$liney);
                $liney = $liney+2;

                $pdf->multicell(10,10,'धंदा:',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(50,10,'शेती व ठेकेदारी',0,'L',false,1,30,$liney,true,0,false,true,10);
                $pdf->multicell(20,10,'मु.पो.:',0,'L',false,1,80,$liney,true,0,false,true,10);
                $pdf->multicell(120,10,$contractguarantordetail1->address,0,'L',false,1,95,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(30,$liney,80,$liney);
                $pdf->line(95,$liney,200,$liney);
                $liney = $liney+2;
            }

            //$contractreceiptdetail1 = new contractreceiptdetail($this->connection);
			//$contractreceiptdetail1 = $this->contractreceiptdetail($this->connection,$contractid,763589425,1);
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $wrdamt = NumberToWords(number_format_indian($contractreceiptdetail1->chequeamount,0,false,false),1);
            if ($contract1->contractcategoryid != 654784125)
            $html = '<span style="text-align:justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            कारण जामीन रोखा लिहून देतो की करारदार <u>'.$servicecontractor1->name_unicode.'</u> यांनी आपल्या संस्थेस सन <u>'.$contract1->seasonname_unicode.'</u> या ऊस गळीत हंगामात ऊस तोडणी व वाहतुकीचे मजूर पुरवण्याचा करारनामा आपणास करून दिलेला आहे. सदर करारनाम्यानुसार नाशिक सहकारी साखर कारखाना लि पळसे सन <u>'.$contract1->seasonname_unicode.'</u> चे गळीत हंगाम अखेर म्हणजेच काम संपतेपावेतो करारदार यांनी ऊस तोडणीसाठी व ऊस वाहतुकीसाठी मजूर / सहभागीदार पुरवायचे आहेत. तसे त्यांचेवर बंधन आहे.
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;करारदार यांना सदर कामासाठी जामीन रोखा लिहून घेणार करारदारास रक्कम रुपये _________इतके रक्कमापर्यंत ॲडव्हान्स देण्याचे कबूल केले आहे / ठरले आहे. ती त्यांनी घेतली आहे / घेणार आहे. कराराप्रमाणे सदर रकमेची फेड ही करारदाराने त्याची तोडणी वाहतूक मजुरीचे बिलातून करून द्यायची आहे. कराराच्या मुदततील वरील रक्कमे व्यतिरिक्त आणखी काही जादा रक्कम ॲडव्हान्स म्हणून जरूर तर करारदार यांनी घेतल्यास त्याची फेड करावयाची आहे. करारदार यांनी तुम्हास दिनांक __ रोजी करारनामा करून दिला आहे. त्यावर आम्हीही त्यांचे माहितीसाठी सह्या केल्या आहेत. करारदार यांनी घेतलेल्या ॲडव्हान्स ची रक्कम करार ठरल्याप्रमाणे परत करावयाची आहे. करारातील अटी व शर्ती प्रमाणे त्यांनी वागावयाचे आहे. त्यासाठी आम्ही करारदारांसाठी हमी घेऊन जामीनदार राहत आहोत. व त्यासाठी हा करारनामा करून देत आहोत. करारदराने करार मोडल्यास, त्यामुळे आपले नुकसान झाल्यास ते फेडण्याची जबाबदारी पत्करीत आहोत. तसेच करारदाराने आपणाकडून वरील गळीत हंगामात जो काही ॲडव्हान्स घेतला आहे / घेणार आहे. त्या संपूर्ण रकमेची फेड करण्याची जबाबदारी आम्ही जामीनदार म्हणून पत्करीत आहोत. 
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;करारदार व आमची जबाबदारी वैयक्तिक व सामुदायिक असून तुम्ही रकमेची आम्हा सर्वांकडून अगर तुम्हास वाटेल त्या एकाकडून अगर आमच्यापैकी काही जणांकडून वसूल करण्याचा अधिकार तुम्हास राहील. 
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;करारदाराने ॲडव्हान्स रकमेची फेड न केल्यास सदरची रक्कम आम्ही जमीनदारांच्या नावावरून परस्पर वसूल करण्याची आमची संमती आहे त्यात ऊस बिल, पगार, बक्षीस, डिपॉझिट व कमिशन इतर कारखान्यांकडून मिळणाऱ्या कोणत्याही रक्कमा गोठवून त्या  परस्पर करारदाराच्या येणे बाकीपोटी जमा करण्याचा तुम्हास अधिकार राहील.रक्कम गोठविणेबाबत व जमा करणेबाबत कुठल्याही कोर्टाची व अधिकाराची हुकमाची आवश्यकता राहणार नाही. त्याबाबत कोणतीही तक्रार कोठेही करणार नाही.
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ॲडव्हान्स घेऊन करारदार कामावर न आल्यास अगर कामावर आल्यानंतर ॲडव्हान्स न फेड करता सिझन संपण्याचे आत काम सोडून गेल्यास ॲडव्हान्सची रक्कमेची आम्ही जामीनदार व करारदार संगनमताने केलेली फसवणूक ठरेल. त्यावेळी आम्ही फौजदारी गुन्ह्यास पात्र राहू व आमचेविरुद्ध फसवणुकीबाबत फौजदारी करण्याचा तुम्हाला अधिकार राहील.
            करारभंग झाल्यास त्याची संपूर्ण जबाबदारी आम्ही जामीनदार स्वीकारीत आहोत.
            </p><p></p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            येणेप्रमाणे जामीन पत्र आम्ही / आमचे स्वखुशीने करून दिलेले आहे.</span>';
            elseif ($contract1->contractcategoryid == 654784125)
            $html = '<span style="text-align:justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            कारणे जामीन पत्र लिहून देतो की, वरील करारवर हे कराराप्रमाणे वर्तवणूक करतील, 
            करार पाळीतील ॲडव्हान्सचे हप्त्याने व वेळोवेळी चालू हंगामात 
            घेतलेली …………………………. पर्यंतची रक्कम कबूल केले प्रमाणे करून देतील. 
            करारापोटी दिलेली ॲडव्हान्सची रक्कम व पुढे त्या जरुरीप्रमाणे तुम्ही जी रक्कम ॲडव्हान्स 
            म्हणून द्याल त्या सर्व रकमेची आम्ही हमी घेऊन आम्ही जामीनदार आहोत. 
            करारदाराने करार मोडला तर नुकसान भरपाई म्हणून रक्कम देण्याची जबाबदारी जामीनदार 
            म्हणून पत्कारीत आहोत. तसेच संपूर्ण ॲडव्हान्सचे रकमेचे करारदाराने फेड न केल्यास ती 
            रक्कम फेडण्याची जबाबदारी आम्ही पत्कारीत आहोत. 
            वरील सर्व बाबींसाठी करार व आमची जबाबदारी संयुक्त व एकट्याची राहील. 
            आम्हा सर्वांकडून अगर तुम्ही वाटेल त्याचे एकाकडून अगर काही जणाकडून ही 
            रक्कम वसूल करण्याचा तुम्हास अधिकार राहील. 
            आमचे भरवश्यावरच जामीनीकारक तुम्ही करारदाराकडून केवळ ॲडव्हान्स उपटनेसाठी 
            आम्ही जामीनदार झालेलो नाहीत. करारदार कराराप्रमाणे काम करण्यास सहभागीदारासह 
            आला अगर ॲडव्हान्स रक्कम फेडताच कराराप्रमाणे काम सोडून गेला तर तो तुमचा करारदार 
            व आम्ही सर्वांनी मिळून जाणून बुजून केलेली मोठी फसवणूक होईल . 
            त्यावेळी करारदाराबरोबर आम्ही गुन्ह‌्यास पात्र राहू.
             त्याचप्रमाणे आम्ही तुमची उचल रक्कम रुपये …………… पर्यंतची पूर्णपणे परतफेड होईपर्यंत 
             जामीन राहत आहोत. आम्ही असेही लिहून देतो की करारदाराने तुमची रक्कम परत 
             न केल्यास आणि त्या संबंधित तुम्ही वसुल रक्कम न्यायालयाचे 
             वसूली आदेश प्राप्त केल्यास त्याचे त्याची अंमलबजावणी तुम्हाला 
             आमच्याविरुद्ध कडक कारवाई करण्याचा अधिकार राहील अशा परिस्थितीत 
             आम्ही कर्जदाराकडून प्रथम वसुली करा असे म्हणणार नाही.</span>';

            $pdf->writeHTML($html, true, 0, true, true);
            if ($contract1->contractcategoryid != 654784125)
            $liney = $liney+90;
            elseif ($contract1->contractcategoryid == 654784125)
            $liney = $liney;
			/* $contractwitnessdetail1 = new contractwitnessdetail($this->connection);
			$contractwitnessdetail1 = $this->contractwitnessdetail($this->connection,$contractid,1);
			$servicecontractor_witness1 = new servicecontractor($this->connection);
			$servicecontractor_witness1->fetch($contractwitnessdetail1->witnessid);
             */
            
            
        
            $pdf->SetFont('siddhanta', '', 11);
            $list1 = $contract1->guarantorcontractorlist();
            $i=0;
            $contractguarantordetail1 = new contractguarantordetail($this->connection);
            $servicecontractor_guarantor1 = new servicecontractor($this->connection);
            foreach ($list1 as $value)
            {
                $pdf->SetPrintHeader(false);
                $pdf->addpage();
                $liney = 120;
                $pdf->multicell(40,10,'स्थळ: ',0,'L',false,1,15,$liney,true,0,false,true,10);
                $curdate = date('d/m/Y');
                $pdf->multicell(50,10,'दिनांक:'.$curdate,0,'L',false,1,15,$liney+10,true,0,false,true,10);
                $liney = $liney+20;
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
                $pdf->multicell(100,10,'श्री नाशिक सह. सा.का.लि.',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+10;
                $liney = $liney+5;
                $pdf->multicell(100,10,'जामीनरोखा करुन देणार',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+5;
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
                $pdf->multicell(100,10,'साक्षीदार',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $list2 = $contract1->witnesslist();
                $j=0;
                $contractwitnessdetail1 = new contractwitnessdetail($this->connection);
                $servicecontractor_witness1 = new servicecontractor($this->connection);
                foreach ($list2 as $value)
                {
                    $val = intval($list2[$j]);
                    $contractwitnessdetail1->fetch($val);
                    //$servicecontractor_witness1->fetch($contractwitnessdetail1->servicecontractorid);
                
                    $pdf->multicell(100,10,++$j.')नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
                    $pdf->multicell(100,10,$contractwitnessdetail1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
                    $pdf->multicell(10,10,'सही:',0,'L',false,1,120,$liney,true,0,false,true,10);
                    $liney = $liney+5;
                    $pdf->line(30,$liney,100,$liney);
                    $pdf->line(120,$liney,200,$liney);
                    $liney = $liney+5;
                }
            }

            $list1 = $contract1->guarantorcultivatorlist();
            $i=0;
            $contractguarantordetail1 = new contractguarantordetail($this->connection);
            $servicecontractor_guarantor1 = new cultivator($this->connection);
            foreach ($list1 as $value)
            {
                $pdf->SetPrintHeader(false);
                $pdf->addpage();
                $liney = 120;
                $pdf->multicell(40,10,'स्थळ: ',0,'L',false,1,15,$liney,true,0,false,true,10);
                $curdate = date('d/m/Y');
                $pdf->multicell(50,10,'दिनांक:'.$curdate,0,'L',false,1,15,$liney+10,true,0,false,true,10);
                $liney = $liney+20;
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
                $pdf->multicell(100,10,'श्री नाशिक सह. सा.का.लि.',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+10;
                $liney = $liney+5;
                $pdf->multicell(100,10,'जामीनरोखा करुन देणार',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $val = intval($list1[$i]);
                $contractguarantordetail1->fetch($val,2);
                $servicecontractor_guarantor1->fetch($contractguarantordetail1->servicecontractorid);
            
                $pdf->multicell(100,10,++$i.')नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
                $pdf->multicell(100,10,$servicecontractor_guarantor1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
                $pdf->multicell(10,10,'सही:',0,'L',false,1,120,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $pdf->line(30,$liney,100,$liney);
                $pdf->line(120,$liney,200,$liney);
                $liney = $liney+5;
                $pdf->multicell(100,10,'साक्षीदार',0,'L',false,1,15,$liney,true,0,false,true,10);
                $liney = $liney+5;
                $list2 = $contract1->witnesslist();
                $j=0;
                $contractwitnessdetail1 = new contractwitnessdetail($this->connection);
                $servicecontractor_witness1 = new servicecontractor($this->connection);
                foreach ($list2 as $value)
                {
                    $val = intval($list2[$j]);
                    $contractwitnessdetail1->fetch($val);
                    //$servicecontractor_witness1->fetch($contractwitnessdetail1->servicecontractorid);
                
                    $pdf->multicell(100,10,++$j.')नाव:',0,'L',false,1,15,$liney,true,0,false,true,10);
                    $pdf->multicell(100,10,$contractwitnessdetail1->name_unicode,0,'L',false,1,30,$liney,true,0,false,true,10);
                    $pdf->multicell(10,10,'सही:',0,'L',false,1,120,$liney,true,0,false,true,10);
                    $liney = $liney+5;
                    $pdf->line(30,$liney,100,$liney);
                    $pdf->line(120,$liney,200,$liney);
                    $liney = $liney+5;
                }
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

    function contractreceiptdetail(&$connection,$contractid,$receiptcategoryid,$sequencenumber)
    {
        $contractreceiptdetail1 = new contractreceiptdetail($connection);
        $query = "select d.contractreceiptdetailid from contract c,contractreceiptdetail d where c.active=1 and d.active=1 and c.contractid=d.contractid and d.receiptcategoryid=".$receiptcategoryid." and c.contractid=".$contractid." order by d.contractreceiptdetailid";
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

    function contractwitnessdetail(&$connection,$contractid,$sequencenumber)
    {
        $contractwitnessdetail1 = new contractwitnessdetail($connection);
        $query = "select d.contractwitnessdetailid from contract c,contractwitnessdetail d,servicecontractor t where c.active=1 and d.active=1 and t.active=1 and c.contractid=d.contractid and d.witnessid=t.servicecontractorid and c.contractid=".$contractid." order by d.contractwitnessdetailid";
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contractwitnessdetail1->fetch($row['CONTRACTWITNESSDETAILID']);
                return $contractwitnessdetail1;
            }
            else
            {
                $i++;
            }
        }
    }

}
?>