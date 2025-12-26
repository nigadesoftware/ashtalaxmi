<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
   // include_once("../swappbase/mypdf_A4_P.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class sharedemandregister extends swappreport
{	
    Public $letterdate;
    Public $farmercode;
    Public $circlecode;
    Public $reference;
    Public $summary;
    public $shares;
    public $circlename;
    public $villagecode;
    public $villagename;

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
	    $this->pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        //$resolution= array(80, 100);
        //$this->pdf->addpage('P',$resolution);
        //$this->pdf = $this->pdf;
        //$this->liney = $this->liney;
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject('Sale Register');
        $this->pdf->SetKeywords('SLRG_000.EN');
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
        $this->pdf->Output('SLRG_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 10;
        $this->pdf->SetFont('siddhanta', '', 12, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        $this->newrow(7);
        $this->textbox('जा.क्र./शेअर्स अकौंट/११०९/२२-२३ नुसार दि.११/०७/२०२२ अखेर शेअर्स येणे बाकी यादी ',187,10,'S','C',1,'siddhanta',11);
        $this->newrow(2);
        $this->newrow(5);
         $this->textbox('गट:'.$this->circlecode.' '.$this->circlename,60,40,'S','L',1,'siddhanta',12);
        $this->textbox('गाव:'.$this->villagecode.' '.$this->villagename,60,140,'S','L',1,'siddhanta',12);
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow(7);
        $this->textbox('अ.न.',20,10,'S','L',1,'siddhanta',12);
        $this->textbox('कोड नंबर',20,30,'S','L',1,'siddhanta',12);
        $this->textbox('सभासदाचे नाव',60,50,'S','L',1,'siddhanta',12);
        $this->textbox('भाग संख्या',20,110,'S','R',1,'siddhanta',12);
        $this->textbox('रक्कम',20,130,'S','R',1,'siddhanta',12);
        $this->textbox('मिळाल्याची सही',70,150,'S','L',1,'siddhanta',12);
        $this->hline(10,200,$this->liney+6,'C');
        $this->newrow();
        //$this->drawlines($limit);
    }
   
    function drawlines($limit)
    {
        //hline($startcol,$endcol,$row='',$type='')
        //vline($startrow,$endrow,$col,$type='')
        $liney = $this->liney;
        $this->liney = 85;
        $this->hline(10,200,$this->liney-12);
        $this->vline($this->liney-12,$this->liney+$limit,10);
        $this->vline($this->liney-12,$this->liney+$limit,40);
        $this->vline($this->liney-12,$this->liney+$limit,75);
        $this->vline($this->liney-12,$this->liney+$limit,110);
        $this->vline($this->liney-12,$this->liney+$limit,170);
        $this->vline($this->liney-5,$this->liney+$limit,180);
        $this->hline(10,200,$this->liney+$limit);
        $this->hline(10,200,$this->liney+$limit);
        $this->hline(140,200,$this->liney-5);
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

    function detail()
    {
        $dt=DateTime::createFromFormat('d/m/Y',$this->letterdate)->format('d-M-Y');
        $dt=DateTime::createFromFormat('d/m/Y',$this->letterdate)->format('d-M-Y');
        
        //if ($this->shares==1)
            $cond1=" union all
            select 0 accountcode,'शेअर्स' accountnameuni,(v.nno_of_shares*15000-nvl(v.nshare_amt,0)) as closingbalance
            ,fd.farmercode,fd.farmernameeng,fd.farmernameuni
            ,fd.villagenameeng,fd.villagenameuni,fd.talukanameeng,fd.talukanameuni,fd.districtnameuni 
            from sugarshare.sha_daily_trans_header_vw1@orcllink v,farmerdata fd 
            where v.nfarmer_code=fd.farmercode ";
        //else
        //    $cond1="";

        if ($this->farmercode!='')
        {
            $cond = " and f.farmercode=".$this->farmercode;
        }
        elseif ($this->circlecode!='')
        {
            $cond = "  and v.circlecode=".$this->circlecode;
        }
        else
        {
            $cond='';
        }
        if ($cond=='')
        {

        }
        else
        {
               $group_query_1 ="with farmerdata as (select f.farmercode,f.farmernameeng,f.farmernameuni
               ,v.villagenameeng,v.villagenameuni,t.talukanameeng,t.talukanameuni
               ,d.districtnameeng,d.districtnameuni,c.circlenameeng,c.circlenameuni,v.villagecode,c.circlecode
               from FARMER f, village v, taluka t,district d,circle c
               where f.villagecode=v.villagecode
               and v.talukacode=t.talukacode
               and t.districtcode=d.districtcode 
               and v.circlecode=c.circlecode
                 {$cond})
                select t.farmercode,accountcode,accountnameuni,nno_of_shares,nshare_amt,closingbalance,rownum as serialnumber
               ,t.farmernameeng,t.farmernameuni
               ,villagenameeng,villagenameuni,talukanameeng,talukanameuni,districtnameuni
               ,c.farmercategorynameuni,t.circlenameuni,t.circlecode,t.villagecode,t.villagenameuni
               ,row_number() over (partition by t.circlecode,t.circlenameuni,t.villagecode,t.villagenameuni
                order by t.circlecode,t.circlenameuni,t.villagecode,t.villagenameuni,t.farmernameuni,t.farmercode) serialnumber
               from (
                   select 0 accountcode,'शेअर्स' accountnameuni,v.nno_of_shares,v.nshare_amt,(v.nno_of_shares*15000-nvl(v.nshare_amt,0)) as closingbalance
                   ,fd.farmercode,fd.farmernameeng,fd.farmernameuni
                   ,fd.villagenameeng,fd.villagenameuni,fd.talukanameeng,fd.talukanameuni,fd.districtnameuni
                   ,fd.circlenameeng,fd.circlenameuni,fd.villagecode,fd.circlecode
                   from sugarshare.sha_daily_trans_header_vw1@orcllink v,farmerdata fd 
                   where v.nfarmer_code=fd.farmercode
               )t,farmer f,farmercategory c
               where t.farmercode=f.farmercode and f.farmercategorycode=c.farmercategorycode
               and closingbalance>0
               order by t.circlecode,t.circlenameuni,t.villagecode,t.villagenameuni,t.farmernameuni,t.farmercode";
        
            $result = oci_parse($this->connection, $group_query_1);
            $r = oci_execute($result);
            $lastvillagecode=0;
            $circnt=0;
            $cirquantity=0;
            $cnt=0;
            $quantity=0;
            $i=1;
            $lastcirclecode=0;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($lastvillagecode==0 or $lastvillagecode!=$row['VILLAGECODE'])
            {
                if ($lastvillagecode!=0 and $lastvillagecode!=$row['VILLAGECODE'])
                {
                    $circnt=0;
                    $cirquantity=0;
                    $this->circlecode=$row['CIRCLECODE'];
                    $this->circlename=$row['CIRCLENAMEUNI'];
                    $this->villagecode=$row['VILLAGECODE'];
                    $this->villagename=$row['VILLAGENAMEUNI'];
                    $this->hline(10,200,$this->liney);
                    $this->newpage(True);
                }
                else if ($lastvillagecode==0)
                {
                    $this->circlecode=$row['CIRCLECODE'];
                    $this->circlename=$row['CIRCLENAMEUNI'];
                    $this->villagecode=$row['VILLAGECODE'];
                    $this->villagename=$row['VILLAGENAMEUNI'];
                    $this->newpage(True);
                }
                
                //$this->newpage(true);
            }
            if ($this->isnewpage(15))
            {
                //$this->newrow();
                $this->hline(10,200,$this->liney,'C');  
                $this->newpage(True);
            }
            else
            {
                //$this->newrow();
                //$this->hline(10,195,$this->liney-2,'C'); 
                //$this->newpage(True);
            }
            $this->textbox($row['SERIALNUMBER'],20,10,'N','L',1,'SakalMarathiNormal922',12);
            $this->textbox($row['FARMERCODE'],20,30,'N','L',1,'SakalMarathiNormal922',12);
            $this->textbox($row['FARMERNAMEUNI'],70,50,'S','L',1,'siddhanta',12,'','Y');
            $this->textbox($row['NNO_OF_SHARES'],20,110,'N','R',1,'SakalMarathiNormal922',12);
            $this->textbox($row['CLOSINGBALANCE'],20,130,'N','R',1,'SakalMarathiNormal922',12);
            //$this->setfieldwidth(40);
            //$this->textbox($this->summary['CLOSINGBALANCE'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',11);
            $this->hline(10,200,$this->liney);
            $this->newrow(12);
            if ($lastcirclecode!=$row['CIRCLECODE'] and $lastcirclecode!=0)
            {
                $i=1;
            }
            $lastvillagecode=$row['VILLAGECODE'];
            $lastcirclecode=$row['CIRCLECODE'];
        
        }
        /* $this->hline(10,200,$this->liney);
        $this->textbox($circnt,20,111,'N','R',1,'SakalMarathiNormal922',12);
        $this->textbox($cirquantity,30,131,'N','R',1,'SakalMarathiNormal922',12);
        $this->newrow(5);
        $this->hline(10,200,$this->liney);

        //$this->newpage(True);
        //$this->newrow(5);
        $this->hline(10,200,$this->liney);
        $this->hline(10,200,$this->liney);
        $this->textbox($cnt,20,113,'N','R',1,'SakalMarathiNormal922',12);
        $this->textbox($quantity,30,132,'N','R',1,'SakalMarathiNormal922',12);
        $this->textbox('एकुण एकंदर ',35,50,'N','R',1,'SakalMarathiNormal922',10);
        $this->newrow(5);
        $this->hline(10,200,$this->liney); */
        
    }
    }
}
  
?>
