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
			$this->SetFont('siddhanta', 'B', 15);
			// Title
			$this->Cell(0, 15, 'Kadwa S.S.K. Ltd.,', 0, false, 'C', 0, '', 0, false, 'M', 'M');
            $this->SetFont('siddhanta', 'B', 12);
            $this->multicell(0,15,'     Rajaramnagar, Tal-Dindori, Dist-Nashik',0,'C',false,1,0,10,true,0,false,true,10);
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