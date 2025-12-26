<?php
require_once('../tcpdf/examples/tcpdf_include.php');
// Extend the TCPDF class to create custom Header and Footer
	class MYPDF extends TCPDF 
    {
    	//Page header
		public function Header() {
			// Logo
			/*$image_file = K_PATH_IMAGES.'Twentyoneu1.jpg';
			$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
			*/// Set font
			$this->SetFont('siddhanta', 'B', 15);
			// Title
			//$this->Cell(0, 15, 'Twentyone Sugars Ltd., Unit-1', 0, false, 'C', 0, '', 0, false, 'M', 'M');
			/* $this->multicell(0,15,'Twentyone Sugars Limited',0,'C',false,1,0,6,true,0,false,true,10);
            $this->SetFont('siddhanta', 'B', 11);
            $this->multicell(0,15,'     Palase, Tal - Dindori, Dist - Nashik',0,'C',false,1,0,12,true,0,false,true,10);
 */		
$this->multicell(0,15,'Nashik Sahakari Sakhar Karkhana Limited',0,'C',false,1,0,4,true,0,false,true,10);
$this->SetFont('siddhanta', 'B', 11);
$this->multicell(0,15,'Dwara Ashtalaxmi Sugar Ethanol and Energy Nashikroad',0,'C',false,1,0,10,true,0,false,true,10);
$this->SetFont('siddhanta', 'B', 10);
//$this->Cell(0, 15, 'तालुका - नेवासा, जिल्हा - अहमदनगर', 0, false, 'C', 0, '', 0, false, 'M', 'M');
$this->multicell(0,15,'    Janardan Swami Nagar, Palase, Tal-Dist-Nasik',0,'C',false,1,0,15,true,0,false,true,10);
}

		// Page footer
		public function Footer() {
			// Position at 15 mm from bottom
			$this->SetY(-15);
			// Set font
			$this->SetFont('siddhanta', 'I', 10);
			// Page number
			$this->Cell(0, 10, 'Page No - '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		}
	}
    ?>