<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a5_l.php");
    include_once("../info/routine.php");

    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class otherweightslip extends swappreport
{
    public $transactionnumber;
    public $isgatepass;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7,$isgatepass)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueType', '', 32);
    	//if ($isgatepass=='')
            $pageLayout = array(210, 148);
        //else
        //    $pageLayout = array(254, 304.8);
        // create new PDF document
	    $this->pdf = new MYPDF('L', PDF_UNIT, $pageLayout, true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Other Weight Slip');
        $this->pdf->SetKeywords('OTWTSLP_000.MR');
                // Display image on full page
               // Render the image
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));
       // $title = str_pad(' ', 30).'Rajaramnagar, Tal - Dindori Dist - Nashik';
    	//$this->pdf->SetHeaderData('', 0,str_pad(' ', 21).'Twentyone Sugars Limited' ,$title);
	// set margins
        $this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        // set auto page breaks
        $this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        // set default monospaced font
        $this->pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        
        // set auto page breaks
        //$this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language dependent data:
        $lg = Array();
        $lg['a_meta_charset'] = 'UTF-8';
        $lg['a_meta_dir'] = 'ltr';
        $lg['a_meta_language'] = 'mr';
        $lg['w_page'] = 'Page - ';
        // set some language-dependent strings (optional)
	    $this->pdf->setLanguageArray($lg);
	}
    function startreport()
    {
        $this->newpage(true);
        $this->detail(1);
        $this->detail(2);
        /* if ($this->isgatepass==1)
        {
            $this->gatepassdetail(1);
        } */
    }
    function endreport()
    {
        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output('OTWTSLP_000.pdf', 'I');
    }
	function pageheader()
    {
        //$this->pdf->Image("../img/kadwawatermark.png", 60, 35, 70, 70, '', '', '', false, 300, '', false, false, 0);
        $this->liney = 15;
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        //$this->drawlines($limit);
    }
   
    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        $liney = $this->liney;
        $this->liney = 85;
        $this->hline(10,240,$this->liney-12);
        $this->vline($this->liney-12,$this->liney+$limit,10);
        $this->vline($this->liney-12,$this->liney+$limit,20);
        $this->vline($this->liney-12,$this->liney+$limit,75);
        $this->vline($this->liney-12,$this->liney+$limit,90);
        $this->vline($this->liney-12,$this->liney+$limit,110);
        $this->vline($this->liney-12,$this->liney+$limit,140);
        $this->vline($this->liney-5,$this->liney+$limit,150);
        $this->vline($this->liney-12,$this->liney+$limit,170);
        $this->vline($this->liney-5,$this->liney+$limit,180);
        $this->vline($this->liney-12,$this->liney+$limit,240);
        $this->hline(10,240,$this->liney+$limit);
        $this->hline(10,240,$this->liney+$limit);
        $this->hline(140,240,$this->liney-5);
        $this->liney = $liney;
    }

    function pagefooter($islastpage=false)
    {
        if ($islastpage==false)
        { 
            //$this->drawlines(130-48);
        }
        else
        {
            //$this->drawlines($this->liney-48);
        }
    }

    function detail($copycode)
    {
        //$this->newrow();
        if ($copycode==1)
        $this->liney = 15;
        else
        $this->liney = 80;
        $this->textbox('वजन स्लिप',190,10,'S','C',1,'siddhanta',13);
        $this->newrow(7);
        $this->hline(10,240,$this->liney,'C');
        $this->newrow(3);
        $query = "select transactionnumber, yearperiodcode, receiptnumber,m.othmatcategorycode
        ,to_char(w.weighmentdate,'dd/MM/yyyy HH24:mi:ss') as weighmentdate
        ,to_char(w.loaddatetime,'dd/MM/yyyy HH24:mi:ss') as loaddatetime
        ,to_char(w.emptydatetime,'dd/MM/yyyy HH24:mi:ss') as emptydatetime
        ,partycustomer, place, vehiclenumber
        ,loadweight, emptyweight, netweight, userid, narration
        ,m.othmatcategorynameuni
        from otherweight w,othermaterial m
        where w.othmatcategorycode=m.othmatcategorycode and w.transactionnumber=".$this->transactionnumber;
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
            $this->textbox('हंगाम : '.$row['YEARPERIODCODE'],50,10,'S','L',1,'siddhanta',11);
            $this->textbox('पावती नंबर : '.$row['RECEIPTNUMBER'],50,80,'S','L',1,'siddhanta',11);
            $this->textbox('पावती दिनांक : '.$row['WEIGHMENTDATE'],80,130,'S','L',1,'siddhanta',11);
            $this->newrow(7);
            $this->textbox('पुरवठादार / ग्राहक : '.$row['PARTYCUSTOMER'],80,10,'S','L',1,'siddhanta',11);
            $this->textbox('ठिकाण : '.$row['PLACE'],40,130,'S','L',1,'siddhanta',11);
            $this->newrow(7);
            $this->textbox('वाहन नंबर : '.$row['VEHICLENUMBER'],70,10,'S','L',1,'siddhanta',11);
            $this->textbox('माल :'.$row['OTHMATCATEGORYNAMEUNI'],80,80,'S','L',1,'siddhanta',13,'','B');
            $this->textbox('शेरा :'.$row['NARRATION'],80,130,'S','L',1,'siddhanta',11);
            $this->newrow(8);
            
            $this->textbox('भरगाडी वजन (मे.टन)',50,30,'S','L',1,'siddhanta',11);
            $this->textbox('रिकामी गाडी वजन (मे.टन)',50,80,'S','L',1,'siddhanta',11);
            $this->textbox('निव्वळ वजन (मे.टन)',50,130,'S','L',1,'siddhanta',11);

            $this->newrow(+4);
            $this->textbox('('.$row['LOADDATETIME'].')',50,30,'S','L',1,'siddhanta',8);
            $this->textbox('('.$row['EMPTYDATETIME'].')',50,80,'S','L',1,'siddhanta',8);
            $this->newrow(-5);
            $this->newrow(-2);
            $this->hline(10,240,$this->liney);
            $this->newrow(11);
            $this->hline(10,240,$this->liney);
            $this->newrow(1);
            
            $this->textbox(number_format($row['LOADWEIGHT'],3,'.',''),50,30,'N','L',1,'SakalMarathiNormal922',11);
            $this->textbox(number_format($row['EMPTYWEIGHT'],3,'.',''),50,80,'N','L',1,'SakalMarathiNormal922',11);
            $this->textbox(number_format($row['NETWEIGHT'],3,'.',''),80,130,'N','L',1,'SakalMarathiNormal922',11);
            
            if ($row['OTHMATCATEGORYCODE']==1)
            {
                $bags = ROUND($row['NETWEIGHT']*1000/50.165,0);
                $this->textbox($bags.' Bags',30,160,'N','R',1,'SakalMarathiNormal922',11);
            }
            $this->newrow(7);
            $this->hline(10,240,$this->liney);
            $this->newrow(5);
            $this->textbox('क्लार्क',50,80,'S','L',1,'siddhanta',11);
            $this->textbox('केनयार्ड इनचार्ज',50,130,'S','L',1,'siddhanta',11);
            if ($copycode==1)
            {
                $this->newrow(8);
                $this->hline(10,240,$this->liney,'D');
                $this->newrow(-8);
            }

        }
    }
    function gatepassdetail($copycode)
    {
        //$this->newrow();
        $this->liney = 115;
        $this->newrow(5);
        $this->hline(10,240);
        $this->newrow(2);
        $this->textbox('ट्वेन्टीवन शुगर्स लिमिटेड.',240,10,'S','C',1,'siddhanta',15);
        $this->newrow();
        $this->textbox('मळवटी, ता.जि.लातूर',240,10,'S','C',1,'siddhanta',11);
        $this->newrow();

        $this->textbox('गेटपास',240,10,'S','C',1,'siddhanta',13);
        $this->newrow(7);
        $this->hline(10,240,$this->liney,'C');
        $this->newrow(3);
        $query = "select transactionnumber, yearperiodcode, receiptnumber
        ,to_char(w.weighmentdate,'dd/MM/yyyy HH24:mi:ss') as weighmentdate
        ,to_char(w.loaddatetime,'dd/MM/yyyy HH24:mi:ss') as loaddatetime
        ,to_char(w.emptydatetime,'dd/MM/yyyy HH24:mi:ss') as emptydatetime
        ,partycustomer, place, vehiclenumber
        ,loadweight, emptyweight, netweight, userid, narration
        ,m.othmatcategorynameuni
        from otherweight w,othermaterial m
        where w.othmatcategorycode=m.othmatcategorycode and w.transactionnumber=".$this->transactionnumber;
        $result = oci_parse($this->connection, $query);
        $r = oci_execute($result);
        if ($row = oci_fetch_array($result,oci_assoc+OCI_RETURN_NULLS))
        {
            $this->textbox('हंगाम : '.$row['YEARPERIODCODE'],50,10,'S','L',1,'siddhanta',11);
            $this->newrow(7);
            $this->textbox('गेटकिपर यांस',80,10,'S','L',1,'siddhanta',10);
            $this->newrow(7);
            $this->textbox('वाहन नंबर -'.$row['VEHICLENUMBER'],80,20,'S','L',1,'siddhanta',10);
            $this->textbox('जीएसटी गेटपास नंबर -'.$row['RECEIPTNUMBER'],80,70,'S','L',1,'siddhanta',10);
            $this->textbox('दिनांक -'.$row['WEIGHMENTDATE'],80,150,'S','L',1,'siddhanta',10);
            $this->newrow(7);
            $this->textbox('यांचे बरोबर पुढील माल बाहेर जाऊ देणे.',80,20,'S','L',1,'siddhanta',10);
            $this->newrow(7);
            $this->textbox('पुरवठादार / ग्राहक : '.$row['PARTYCUSTOMER'],80,10,'S','L',1,'siddhanta',11);
            $this->textbox('ठिकाण : '.$row['PLACE'],40,130,'S','L',1,'siddhanta',11);
            $this->newrow(7);
            $this->textbox('माल :'.$row['OTHMATCATEGORYNAMEUNI'],80,10,'S','L',1,'siddhanta',11);
            $this->textbox('निव्वळ वजन :'.number_format($row['NETWEIGHT'],3,'.',''),80,90,'S','L',1,'siddhanta',11);
            $this->newrow(7);
            $this->hline(10,240,$this->liney,'C');
            $this->newrow(15);
            $this->textbox('क्लार्क',50,80,'S','L',1,'siddhanta',11);
            $this->textbox('केनयार्ड इनचार्ज',50,130,'S','L',1,'siddhanta',11);
        }
    }
    function username($userid)
    {
        $username="root";
        $password="sandee1976";
        $database="Twentyoneu1_db";
        $hostname = "localhost";
        // Opens a connection to a MySQL server
        $connection1=mysqli_connect($hostname, $username, $password, $database);
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Communication Error";
            exit;
        }
        $connection1->autocommit(FALSE);
        $query = "SELECT m.misuserid,m.misusername FROM misuser m WHERE m.misuserid=".$userid;
        $result = mysqli_query($connection1,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            $name=$row['misusername'];
        }
        else
        {
            $name='';
        }
        return $name;
    }
}    
?>