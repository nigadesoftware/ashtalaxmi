<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a3_l.php");
    include_once("../info/routine.php");
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class crushinglicense extends swappreport
{
    public $seasoncode;
    public $farmercategorycode;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7)
	{
        $this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueType', '', 32);
    	// create new PDF document
	    $this->pdf = new MYPDF('L', PDF_UNIT, 'A3', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('plantation register');
        $this->pdf->SetKeywords('PLREG_000.MR');
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));
       // $title = str_pad(' ', 30).'Rajaramnagar, Tal - Dindori Dist - Nashik';
    	//$this->pdf->SetHeaderData('', 0,str_pad(' ', 21).'Kadwa S.S.K. Ltd.' ,$title);
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

    function endreport()
    {
        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output('PLREG_000.pdf', 'I');
    }
	function pageheader()
    {

    }
   
    function drawlines($limit)
    {
    }

    function pagefooter($islastpage=false)
    {
        
    }

    function detail()
    { 

    }
    function export()
    {
           if ($this->farmercategorycode==1)
           {
                $filename='crushinglicense_2a';
           }
           elseif ($this->farmercategorycode==2)
           {
                $filename='crushinglicense_2b';
           }
           elseif ($this->farmercategorycode==3)
           {
                $filename='crushinglicense_2c';
           }
           //$fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');
           $fp1=fopen('php://memory', 'w');
           $query = "select count(*) over (partition by seasoncode order by c.talukacode,v.villagecode,p.plantationdate,p.plotnumber) as srno
           ,c.talukanameuni,v.villagenameuni,f.farmernameuni,f.farmercode,f.adharnumber,f.mobilenumber,f.accountnumber,p.gutnumber,p.area,h.commissinorhangamcode as plantationhangamcode,vv.varietynameuni,to_char(p.plantationdate,'dd/mm/yyyy') as plantationdate,to_char(p.plantationdate+vv.maturitydays,'dd/mm/yyyy') as expectedharvestingdate 
           from plantationheader p,farmer f,village v,taluka c
           ,variety vv,plantationhangam h
           where p.farmercode=f.farmercode
           and f.villagecode=v.villagecode
           and v.talukacode=c.talukacode(+)
           and p.varietycode=vv.varietycode(+)
           and p.plantationhangamcode=h.plantationhangamcode(+)
           and p.seasoncode=".$this->seasoncode."
           and f.farmercategorycode=".$this->farmercategorycode."
           order by c.talukacode,v.villagecode,p.plantationdate,p.plotnumber";
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $response = array();
           if ($this->farmercategorycode==1)
           {
               fputcsv($fp1, array('अ.क्र.','तालुका','गाव','सभासदाचे नाव','सभासद (कोड / नंबर) ','आधार क्रमांक','मोबाईल क्रमांक','बँक खाते क्रमांक','सर्वे नंबर','गाळपासाठी नोंद झालेले एकूण ऊस क्षेत्र (हेक्टर)','लागवड प्रकार','जात','लागवड दिनांक','अंदाजीत तोडणी दिनांक'));
           }
           elseif ($this->farmercategorycode==2)
           {
                fputcsv($fp1, array('अ.क्र.','तालुका','गाव','बिगर सभासद ऊस उत्पादकाचे नाव','बिगर सभासद (कोड / नंबर) ','आधार क्रमांक','मोबाईल क्रमांक','बँक खाते क्रमांक','सर्वे नंबर','गाळपासाठी नोंद झालेले एकूण ऊस क्षेत्र (हेक्टर)','लागवड प्रकार','जात','लागवड दिनांक','अंदाजीत तोडणी दिनांक'));
           }
           elseif ($this->farmercategorycode==3)
           {
                fputcsv($fp1, array('अ.क्र.','तालुका','गाव','कारखाना कार्यक्षेत्राबाहेरील ऊस उत्पादकांचे नाव','बिगर सभासद (कोड / नंबर)  ','आधार क्रमांक','मोबाईल क्रमांक','बँक खाते क्रमांक','सर्वे नंबर','गाळपासाठी नोंद झालेले एकूण ऊस क्षेत्र (हेक्टर)','लागवड प्रकार','जात','लागवड दिनांक','अंदाजीत तोडणी दिनांक'));
           }
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                fputcsv($fp1, $row, $delimiter = ',', $enclosure = '"');
           }
           // reset the file pointer to the start of the file
            fseek($fp1, 0);
            // tell the browser it's going to be a csv file
            header('Content-Type: application/csv');
            // tell the browser we want to save it instead of displaying it
            header('Content-Disposition: attachment; filename="'.$filename.'";');
            // make php send the generated csv lines to the browser
            fpassthru($fp1); 
            //fclose($fp1);
    }
}    
?>