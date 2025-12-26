<?php
require_once('../tcpdf/examples/tcpdf_include.php');
// Extend the TCPDF class to create custom Header and Footer
	class MYPDF extends TCPDF 
    {
    	//Page header
		public function Header() {
			// Logo
			$image_file = K_PATH_IMAGES.'logo mula bajar.jpg';
			$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
			// Set font
			$this->SetFont('siddhanta', 'B', 18);
			// Title
			$this->Cell(0, 15, 'मुळा मध्यवर्ती सहकारी ग्राहक संस्था, सोनई', 0, false, 'C', 0, '', 0, false, 'M', 'M');
            $this->SetFont('siddhanta', 'B', 14);
            //$this->Cell(0, 15, 'तालुका - नेवासा, जिल्हा - अहमदनगर', 0, false, 'C', 0, '', 0, false, 'M', 'M');
            $this->multicell(200,10,'तालुका - नेवासा, जिल्हा - अहमदनगर',0,'C',false,1,50,13,true,0,false,true,10);
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