<?php
require_once('../tcpdf/examples/tcpdf_include.php');
// Extend the TCPDF class to create custom Header and Footer
	class MYPDF extends TCPDF 
    {
    	//Page header
		public function Header() {
			/*$img_file = K_PATH_IMAGES.'kskback.jpg';
        	$this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
			// Logo
			$image_file = K_PATH_IMAGES.'ksklogo.jpg';
			$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
			*/// Set font
			$this->SetFont('siddhanta', 'B', 14);
			// Title
			$this->Cell(0, 5, 'नाशिक स.सा.का.लि.,', 0, false, 'C', 0, '', 0, false, 'M', 'M');
            $this->SetFont('siddhanta', 'B', 10);
            //$this->Cell(0, 15, ',ता.नाशिक,जि.नाशिक', 0, false, 'C', 0, '', 0, false, 'M', 'M');
            $this->multicell(0,30,'नाशिक,स.सा.का.लि.श्री संत जनार्दन स्वामी नगर',0,'L',false,1,30,10,true,0,false,true,10);
		}

		// Page footer
		public function Footer() {
			// Position at 15 mm from bottom
			$this->SetY(-15);
			// Set font
			$this->SetFont('siddhanta', 'I', 10);
			// Page number
			//$this->Cell(0, 10, 'पान क्रं - '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		}
	}
    ?>