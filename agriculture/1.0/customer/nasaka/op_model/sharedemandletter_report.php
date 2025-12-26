<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_P.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class sharedemandletter extends reportbox
{	
    Public $letterdate;
    Public $farmercode;
    Public $circlecode;
    Public $reference;
    Public $summary;
    public $shares;
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
        $this->pdf->SetSubject('AC Letter');
        $this->pdf->SetKeywords('ACLET_000.MR');
        // set font
        // set header and footer fonts
        $this->pdf->setHeaderFont(Array($headerfontname, '', 12));
        $this->pdf->setFooterFont(Array($headerfontname, '', 12));
      
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
        $this->group();
        //$this->reportfooter();
    }
    function endreport()
    {
        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output('ACLET_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 10;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  


        //$this->drawlines($limit);
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

    function group()
    {
        $this->totalgroupcount=1;
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
                ,d.districtnameeng,d.districtnameuni
                from FARMER f, village v, taluka t,district d
                where f.villagecode=v.villagecode
                and v.talukacode=t.talukacode
                and t.districtcode=d.districtcode 
                {$cond})
                 select t.farmercode,accountcode,accountnameuni,nno_of_shares,nshare_amt,closingbalance,rownum as serialnumber
                ,t.farmernameeng,t.farmernameuni
                ,villagenameeng,villagenameuni,talukanameeng,talukanameuni,districtnameuni
                ,c.farmercategorynameuni
                from (
                    select 0 accountcode,'शेअर्स' accountnameuni,v.nno_of_shares,v.nshare_amt,(v.nno_of_shares*15000-nvl(v.nshare_amt,0)) as closingbalance
                    ,fd.farmercode,fd.farmernameeng,fd.farmernameuni
                    ,fd.villagenameeng,fd.villagenameuni,fd.talukanameeng,fd.talukanameuni,fd.districtnameuni 
                    from sugarshare.sha_daily_trans_header_vw1@orcllink v,farmerdata fd 
                    where v.nfarmer_code=fd.farmercode
                )t,farmer f,farmercategory c
                where t.farmercode=f.farmercode and f.farmercategorycode=c.farmercategorycode
                and closingbalance>0
                order by f.villagecode,f.farmernameuni,rownum";
                
                $group_result_1 = oci_parse($this->connection, $group_query_1);
                $r = oci_execute($group_result_1);
                $i=0;
                //$this->newpage(true);
                while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
                {
                    $this->grouptrigger($group_row_1,$last_row);
                    $this->detail_1($group_row_1);
                    $last_row=$group_row_1;
                }
                $this->grouptrigger($group_row_1,$last_row,'E');
                $this->reportfooter();
            }
    }
    
    function groupheader_1(&$group_row_1)
    {
        $this->newrow(10);
        $this->setfieldwidth(165,24);
        $this->textbox('फोन नं.(०२५५७) २३७१८१ ते २३७१८४',$this->w,$this->x,'S','C',1,'siddhanta',12);
        $this->newrow(7);
        $this->setfieldwidth(165,24);
        $this->textbox('ईमेल - kadwasakar@yahoo.com    kadwasugar@gmail.com',$this->w,$this->x,'S','C',1,'siddhanta',12);
        $this->newrow(7);
        $this->hline(20,190,$this->liney,'C'); 
        $this->newrow(7);
        $this->summary['CLOSINGBALANCE']=0;
        $this->setfieldwidth(20,100);
        $this->textbox('नोटीस',$this->w,$this->x,'S','L',1,'siddhanta',15,'','B');
        $this->newrow(10);
        $this->setfieldwidth(120,24);
        $this->textbox('जा.क्र./शेअर्स अकौंट/1109/२०२२-२०२३',$this->w,$this->x,'S','L',1,'siddhanta',12);
 
        //$this->newrow(10);
        $this->setfieldwidth(65,152);
        $this->textbox('दिनांक : ११/०७/२०२२',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->newrow(15);

        $this->setfieldwidth(65,20);
        $this->textbox('प्रती,',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->newrow(6);

        $this->setfieldwidth(150,20);
        $this->textbox('श्री/श्रीमती- '.$group_row_1['FARMERNAMEUNI'].' - '.$group_row_1['FARMERCATEGORYNAMEUNI'].' कोड-'.$group_row_1['FARMERCODE'],$this->w,$this->x,'S','L',1,'siddhanta',13);
        $this->newrow(7);

        $this->setfieldwidth(65,20);
        $this->textbox('मु./पो. -'.$group_row_1['VILLAGENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(65);
        $this->textbox('ता. -'.$group_row_1['TALUKANAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->setfieldwidth(65,110);
        $this->textbox('जिल्हा -'.$group_row_1['DISTRICTNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11);
        $this->newrow(15);

        $this->setfieldwidth(120,40);
        $this->textbox('विषय- भागाची(शेअर्सची) येणे रक्कम भरणा करणेबाबत...',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->newrow(6);

        $this->setfieldwidth(120,40);
        $this->textbox('संदर्भ- शासन आदेश क्र.ससाका २०२०/प्र.क्र-८५/३-स दि.१८/०५/२०२१',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->newrow(10);
        $this->setfieldwidth(65,20);
        $this->textbox('महोदय/महोदया,',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->newrow(6);

        $this->setfieldwidth(180,20);
       // $this->textbox('संदर्भ -'.$this->reference,$this->w,$this->x,'S','L',1,'siddhanta',11);
        $ht=$this->textbox('                मा. उपसचिव महाराष्ट्र शासन, सहकार,पणन व वस्त्रोद्योग विभाग, मंत्रालय, मुंबई यांचेकडील शासन आदेश क्र. ससाका २०२०/प्र.क्र ८५/३-स  दि.१८/०५/२०२१  नुसार  सर्व  सहकारी साखर  कारखान्यांची  भागाची  दर्शनी  मुल्यामध्ये रु.१०,०००/-  वरुन रु.१५,०००/- वाढ करण्याबाबत  आदेश झालेला आहे. तसेच कारखान्यास दि.३१/०८/२०२१ रोजीचे मा.अधिमंडळाचे वार्षिक सर्वसाधारण सभेतील ठराव क्र.१९ नुसार पोटनियम दुरुस्ती करणेस मान्यता मिळालेली आहे.त्यानुसार कारखान्याच्या भागाची दर्शनी किमंत रु.१०,०००/- वरुन रु.१५,०००/- करण्यात आलेली आहे. त्यानुसार आपले कडील भागाची येणे रक्कमेचा तपशिल खालील प्रमाणे
',$this->w,$this->x,'S','L',1,'siddhanta',12,'','Y');
        $this->newrow($ht);

       
        $this->newrow(5);

        $this->hline(20,190,$this->liney+5,'C');
        $this->newrow(6);
        //$this->setfieldwidth(15,20);
        //$this->vline($this->liney-1,$this->liney+10,$this->x);
        //$this->textbox('अ.नं.',$this->w,$this->x,'S','L',1,'siddhanta',11);
        //$this->newrow(10);
        //$this->hline(10,325,$this->liney+10,'C');
        //$this->newrow(7);

        $this->setfieldwidth(30,20);
        $this->vline($this->liney-1,$this->liney+10,$this->x);
        $this->textbox('भागाची संख्या',$this->w,$this->x,'S','C',1,'siddhanta',12,'','B');
        $this->textbox('भागाची संख्या',$this->w,$this->x,'S','C',1,'siddhanta',12,'','B');
        //$this->newrow(2);
        //$this->hline(10,200,$this->liney+10,'C');
        //$this->newrow(7);

        $this->setfieldwidth(50);
        $this->vline($this->liney-1,$this->liney+10,$this->x);
        $this->textbox('भरणा केलेली भागाची रक्कम',$this->w,$this->x,'S','C',1,'siddhanta',12);
        $this->textbox('भरणा केलेली भागाची रक्कम',$this->w,$this->x,'S','C',1,'siddhanta',12);
        $this->vline($this->liney-1,$this->liney+10,$this->x+$this->w);
        
        $this->setfieldwidth(100);
        $this->vline($this->liney-1,$this->liney+10,$this->x);
        $this->textbox('रु.१५,०००/-ची दर्शनी मुल्यानुसार येणे बाकी रक्कम',$this->w,$this->x-5,'S','C',1,'siddhanta',12,'','Y');
        $this->textbox('रु.१५,०००/-ची दर्शनी मुल्यानुसार येणे बाकी रक्कम',$this->w,$this->x-5,'S','C',1,'siddhanta',12,'','Y');
        $this->vline($this->liney-1,$this->liney+10,$this->x-10+$this->w);
        $this->hline(20,190,$this->liney+10,'C');
        $this->newrow(10);


    }


    function groupheader_2(&$group_row_1)
    {
    }

    function groupheader_3(&$group_row_1)
    {
    }
    function groupheader_4(&$group_row)
    {
    }
    function groupheader_5(&$group_row)
    {
    }
    function groupheader_6(&$group_row)
    {
    }
    function groupheader_7(&$group_row)
    {
    }
    function detail_1(&$group_row_1)
    {
        //$this->setfieldwidth(15,20);
        //$this->vline($this->liney-1,$this->liney+10,$this->x);
        //$this->textbox($group_row_1['SERIALNUMBER'],$this->w,$this->x,'S','L',1,'SakalMarathiNormal922',11);
        
        $this->setfieldwidth(30,20);
        $this->vline($this->liney-1,$this->liney+10,$this->x);

        $this->textbox($group_row_1['NNO_OF_SHARES'],$this->w,$this->x,'N','C',1,'siddhanta',14,'','B');
        $this->vline($this->liney-1,$this->liney+10,$this->x+$this->w);
        $this->setfieldwidth(50);
        $this->textbox($group_row_1['NSHARE_AMT'].'/-',$this->w,$this->x,'N','C',1,'siddhanta',14,'','B');
        $this->vline($this->liney-1,$this->liney+10,$this->x+$this->w);
     
        $this->setfieldwidth(100);
        //if ($group_row_1['ACCOUNTCODE']!='0')
        $this->textbox($group_row_1['CLOSINGBALANCE'].'/-',$this->w,$this->x,'N','C',1,'siddhanta',14,'','B');
        $this->vline($this->liney-1,$this->liney+10,$this->x-10+$this->w);
        $this->summary['CLOSINGBALANCE']=$this->summary['CLOSINGBALANCE']+$group_row_1['CLOSINGBALANCE'];
        
        if ($this->isnewpage(15))
        {
            $this->newrow(6);
            $this->hline(35,175,$this->liney-2,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow(10);
            $this->hline(20,190,$this->liney,'C'); 
        }
    }
    
    function groupfooter_1(&$group_row_1)
    {
        
        
        if ($this->isnewpage(25))
        {
            $this->newrow(6);
            $this->hline(35,175,$this->liney-1,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow(6);
            //$this->hline(35,175,$this->liney-1,'C');
        }

        $this->newrow(2);
        $this->setfieldwidth(180,20);
        // $this->textbox('वरील प्रमाणे येणे रक्कम दि. '.$this->letterdate.' अखेर कारखान्याचे अकौंट विभागाकडे भरून सहकार्य करावे व तशी रितसर पावती घ्यावी ही विनंती.(वरील पैकी बाकी भरली असल्यास अकौंट विभागाकडे खात्री करून घ्यावी.)',$this->w,$this->x,'S','L',1,'siddhanta',11,'','Y');
        $ht=$this->textbox('         उपरोक्त शासन आदेश व पोटनियमातील  तरतुदीनुसार  भागाची येणे  बाकी रक्कम भरणे  क्रमप्राप्त असल्याने भागाची येणेबाकी  रक्कम  दि ३१/०८/२०२२  पर्यंत कारखान्याचे शेअर्स अकौंट विभागाकडे  भरणा करून सहकार्य करावे व तशी रितसर पावती घ्यावी. '
        ,$this->w,$this->x,'S','L',1,'siddhanta',12,'','Y'); 
        $this->newrow(16);
        $this->setfieldwidth(180,20);
        $this->textbox('(वरील पैकी बाकी भरलेली असल्यास अकौंट विभागाकडे खात्री करून घ्यावी.)',$this->w,$this->x,'S','L',1,'siddhanta',12);
        //$this->newrow(10);
        $this->newrow($ht);
        $this->setfieldwidth(125,30);
        $this->textbox('कळावे.',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->setfieldwidth(55);
        $this->textbox('आपला विश्वासू',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $image_file = '..\img\mdsign.jpg';
        //$image_file = K_PATH_IMAGES.'mdsign.jpg';
        $y=$this->liney+10;
        $this->pdf->Image($image_file, 155, $y, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        
        $this->newrow(20);
        $this->setfieldwidth(40,152);
        $this->textbox('जनरल मॅनेजर',$this->w,$this->x,'S','L',1,'siddhanta',12);

        $this->newrow(4);
        $this->setfieldwidth(60,141);
        $this->textbox('नाशिक सह.सा.का.लि.पळसे',$this->w,$this->x,'S','L',1,'siddhanta',12);
        $this->summary['CLOSINGBALANCE']=0;
        $this->newpage(True);
    }
    function groupfooter_2(&$group_row_2)
    {  
    }

    function groupfooter_3(&$group_row_3)
    {     
    }
    function groupfooter_4(&$group_row)
    {
    }
    function groupfooter_5(&$group_row)
    {
    }
    function groupfooter_6(&$group_row)
    {
    }
    function groupfooter_7(&$group_row)
    {
    }

    function subreportgroupheader(&$subreportgroup_row,$subreportnumber,$groupnumber)
    {
    }

    function subreportgroupfooter(&$subreportlast_row,$subreportnumber,$groupnumber)
    {
    }

    function reportfooter()
    {
    }

}    
?>
