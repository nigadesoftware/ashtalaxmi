<?php
require_once('../tcpdf/examples/tcpdf_include.php');
// Extend the TCPDF class to create custom Header and Footer
	class MYPDF extends TCPDF 
    {
    	//Page header
		public function Header() {
			// Logo
			/*$image_file = K_PATH_IMAGES.'kadwa.jpg';
			$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
			*/// Set font
			$image_file = K_PATH_IMAGES.'kadwa.jpg';
			//$image_file = K_PATH_IMAGES.'kadwa.jpg';
			$this->Image($image_file, 10, 5, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
			// Set font
			$this->SetFont('siddhanta', 'B', 16);
			// Title
			//$this->Cell(0, 15, 'Nashik Sahakari Sakhar Ltd.,', 0, false, 'C', 0, '', 0, false, 'M', 'M');
			$this->multicell(0,15,'Nashik Sahakari Sakhar Karkhana Ltd., Palse',0,'C',false,1,0,4,true,0,false,true,10);
			$this->SetFont('siddhanta', 'B', 13);
            //$this->Cell(0, 15, 'तालुका - नेवासा, जिल्हा - अहमदनगर', 0, false, 'C', 0, '', 0, false, 'M', 'M');
            $this->multicell(0,15,'Leased By Ashtalaxmi Sugar Ethanol and Energy Nashik Road',0,'C',false,1,0,10,true,0,false,true,10);
			$this->SetFont('siddhanta', 'B', 10);
            //$this->Cell(0, 15, 'तालुका - नेवासा, जिल्हा - अहमदनगर', 0, false, 'C', 0, '', 0, false, 'M', 'M');
            $this->multicell(0,15,'Shree Sant Janardan Swaminagar, Tal & Dist Nashik',0,'C',false,1,0,15,true,0,false,true,10);
		}

		// Page footer
		public function Footer() {
			// Position at 15 mm from bottom
			$this->SetY(-15);
			// Set font
			$this->SetFont('siddhanta', 'I', 10);
			// Page number
			$this->Cell(0, 10, 'पान क्रं - '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		}
	}
    ?>