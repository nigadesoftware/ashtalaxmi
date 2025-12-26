<?php
    require_once("../info/phpgetloginview.php");
    require_once("../info/ncryptdcrypt.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
	//require_once("../info/swapproutine.php");
	require_once("../info/rawmaterialroutine.php");
    include("../api_report/contract1_report.php");
    include("../api_report/contract2_report.php");
	include("../api_report/contract3_report.php");
	include("../api_report/contract4_report.php");
    include("../api_report/contract5_report.php");
	include("../api_report/contract6_report.php");
	include("../api_report/contract7_report.php");
    include("../api_report/contract8_report.php");
	include("../api_report/contract9_report.php");
	include("../api_report/contract10_report.php");
	include("../api_report/contract11_report.php");
	include("../api_report/contract20_report.php");
	include("../api_report/contract21_report.php");
	include("../api_report/contract51_report.php");
	include("../api_report/contract52_report.php");
	include("../api_report/contract53_report.php");
    //Raw Material HT Master Addition or HT Master Alteration
    if (isaccessible(451230287895415)==0 and isaccessible(475124562358965)==0)
    {
    	echo 'Communication Error';
    	exit;
    }
	$contractid_de = fnDecrypt($_GET['contractid']);
	$contractcategoryid_de = fnDecrypt($_GET['contractcategoryid']);
	$reportid_de = (int) fnDecrypt($_GET['reportid']);

	$headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	$fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);

	// create new PDF document
	$pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor(PDF_AUTHOR);
	$pdf->SetTitle(PDF_HEADER_TITLE);
	$pdf->SetSubject('Contract');
	$pdf->SetKeywords('contract_000.MR');

    // set font
	// set header and footer fonts
	$pdf->setHeaderFont(Array($headerfontname, '', 12));
	$pdf->setFooterFont(Array($headerfontname, '', 12));
	// set default header data
	$title = str_pad(' ', 30).'श्री संत जनार्दन स्वामी नगर, ता.नाशिक, जि.नाशिक';
	//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH,'श्री सोमेश्वर सर्व सेवा संघ' ,$title);
	$pdf->SetHeaderData('', 0,str_pad(' ', 21).'नाशिक सहकारी साखर कारखाना लि., पळसे' ,$title);
	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    
	// set auto page breaks
	//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// set some language dependent data:
	$lg = Array();
	$lg['a_meta_charset'] = 'UTF-8';
	$lg['a_meta_dir'] = 'ltr';
	$lg['a_meta_language'] = 'mr';
	$lg['w_page'] = 'पान - ';

	// set some language-dependent strings (optional)
	$pdf->setLanguageArray($lg);

	// ---------------------------------------------------------

	// add a page
	

	// set color for background
	$pdf->SetFillColor(0, 0, 0);
	// set color for text
	$pdf->SetTextColor(0, 0, 0);
	$srno =1;
	$liney=20;


    require("../info/phpsqlajax_dbinfo.php");
    
	$connection = rawmaterial_connection();
	//transporter
	if ($contractcategoryid_de == 521478963)
	{
		switch($reportid_de)
		{
			case 1:
					$pdf->SetPrintHeader(true);
					$pdf->AddPage();
					$liney=20;
					$contract20 = new contract_3($connection);
					$contract20->printpageheader($pdf,$liney,$contractid_de);
					$pdf->lastPage();
					$pdf->Output('contract3_000.pdf', 'I');
					break;
			case 2:
					$pdf->SetPrintHeader(true);
					$pdf->AddPage();
					$liney=20;
					$contract4 = new contract_4($connection);
					$contract4->printpageheader($pdf,$liney,$contractid_de);
					$pdf->lastPage();
					$pdf->Output('contract4_000.pdf', 'I');
					break;
			case 3:
					$pdf->SetPrintHeader(true);
					$pdf->AddPage();
					$liney=20;
					$contract20 = new contract_7($connection);
					$contract20->printpageheader($pdf,$liney,$contractid_de);
					$pdf->lastPage();
					$pdf->Output('contract7_000.pdf', 'I');
					break;
			case 9:
					$pdf->SetPrintHeader(false);
					$pdf->AddPage();
					$pdf->SetPrintHeader(true);
					$liney=20;
					$contract10 = new contract_10($connection);
					$contract10->printpageheader($pdf,$liney,$contractid_de);
					$pdf->lastPage();
					$pdf->Output('contract10_000.pdf', 'I');
					break;
		}
	}
	//Bulluckcart
	elseif ($contractcategoryid_de == 785415263)
	{
		switch($reportid_de)
		{
			case 1:
					$pdf->SetPrintHeader(true);
					$pdf->AddPage();
					$liney=20;
					$contract20 = new contract_3($connection);
					$contract20->printpageheader($pdf,$liney,$contractid_de);
					$pdf->lastPage();
					$pdf->Output('contract3_000.pdf', 'I');
					break;
			case 2:
					$pdf->SetPrintHeader(true);
					$pdf->AddPage();
					$liney=20;
					$contract4 = new contract_4($connection);
					$contract4->printpageheader($pdf,$liney,$contractid_de);
					$pdf->lastPage();
					$pdf->Output('contract4_000.pdf', 'I');
					break;
			case 3:
					$pdf->SetPrintHeader(true);
					$pdf->AddPage();
					$liney=20;
					$contract20 = new contract_7($connection);
					$contract20->printpageheader($pdf,$liney,$contractid_de);
					$pdf->lastPage();
					$pdf->Output('contract7_000.pdf', 'I');
					break;
			case 4:
					$pdf->SetPrintHeader(true);
					$pdf->AddPage();
					$liney=20;
					$contract53 = new contract_53($connection);
					$contract53->printpageheader($pdf,$liney,$contractid_de);
					$pdf->lastPage();
					$pdf->Output('contract53_000.pdf', 'I');
					break;
			case 5:
					$pdf->SetPrintHeader(true);
					$pdf->AddPage();
					$liney=20;
					$contract2 = new contract_2($connection);
					$contract2->printpageheader($pdf,$liney,$contractid_de);
					$pdf->lastPage();
					$pdf->Output('contract2_000.pdf', 'I');
					break;
			case 9:
					$pdf->SetPrintHeader(false);
					$pdf->AddPage();
					$pdf->SetPrintHeader(true);
					$liney=20;
					$contract20 = new contract_20($connection);
					$contract20->printpageheader($pdf,$liney,$contractid_de);
					$pdf->lastPage();
					$pdf->Output('contract20_000.pdf', 'I');
					break;
		}
	}
	//Harvester
	elseif ($contractcategoryid_de == 947845153)
	{
		switch($reportid_de)
		{
			case 1:
				$pdf->SetPrintHeader(true);
				$pdf->AddPage();
				$liney=20;
				$contract3 = new contract_3($connection);
				$contract3->printpageheader($pdf,$liney,$contractid_de);
				$pdf->lastPage();
				$pdf->Output('contract3_000.pdf', 'I');
				break;
			case 2:
				$pdf->SetPrintHeader(true);
				$pdf->AddPage();
				$liney=20;
				$contract4 = new contract_4($connection);
				$contract4->printpageheader($pdf,$liney,$contractid_de);
				$pdf->lastPage();
				$pdf->Output('contract4_000.pdf', 'I');
				break;
			case 3:
				$pdf->SetPrintHeader(true);
				$pdf->AddPage();
				$liney=20;
				$contract7 = new contract_7($connection);
				$contract7->printpageheader($pdf,$liney,$contractid_de);
				$pdf->lastPage();
				$pdf->Output('contract7_000.pdf', 'I');
				break;
			case 5:
				$pdf->SetPrintHeader(true);
				$pdf->AddPage();
				$liney=20;
				$contract2 = new contract_2($connection);
				$contract2->printpageheader($pdf,$liney,$contractid_de);
				$pdf->lastPage();
				$pdf->Output('contract2_000.pdf', 'I');
				break;
			case 9:
					$pdf->SetPrintHeader(false);
					$pdf->AddPage();
					$pdf->SetPrintHeader(true);
					$liney=20;
					$contract21 = new contract_21($connection);
					$contract21->printpageheader($pdf,$liney,$contractid_de);
					$pdf->lastPage();
					$pdf->Output('contract21_000.pdf', 'I');
					break;
		}
	}		
?>
