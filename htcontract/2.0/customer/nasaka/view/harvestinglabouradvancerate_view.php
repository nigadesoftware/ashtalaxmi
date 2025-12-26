<?php
    require_once("../info/phpgetloginview.php");
    require_once("../info/ncryptdcrypt.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
	require_once("../info/rawmaterialroutine.php");
    include("../api_report/harvestinglabouradvancerate_report.php");

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
	$pdf->SetSubject('Harvesting Labour Advance Rate');
	$pdf->SetKeywords('HRLBADRT_000.MR');

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
	$srno = 1;
	$liney= 20;
    require("../info/phpsqlajax_dbinfo.php");
	$connection = rawmaterial_connection();
    $pdf->SetPrintHeader(true);
    $pdf->AddPage();
    $liney=20;
    $harvestinglabouradvancearatereport1 = new harvestinglabouradvancearatereport($connection);
    $harvestinglabouradvancearatereport1->printpageheader($pdf,$liney);
    $pdf->lastPage();
    ob_end_clean();
    $pdf->Output('harlabadvrate_000.pdf', 'I');
?>