<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_a4_p.php");
    include_once("../info/routine.php");
    ini_set('max_execution_time', '0'); // for infinite time of execution 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
    //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
    //hline($startcol,$endcol,$row='',$type='')
    //vline($startrow,$endrow,$col,$type='')
class circlemembercrushing extends reportbox
{
    public $circlecode;
    public $fromdate;
    public $todate;
    public $divisioncode;
    public $farmercategorycode;
    public $opareasummary;
    public $farmercategorysummary;
    public $summary;


    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7,$subject='',$pdffilename='',$papersize='A4',$orientation='P')
	{
        $this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $this->subject= $subject;
        $this->pdffilename= $pdffilename;
        $this->papersize=strtoupper($papersize);
        $this->orientation=strtoupper($orientation);
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueType', '', 32);
        // create new PDF document
	    $this->pdf = new MYPDF($this->orientation, PDF_UNIT, $this->papersize, true, 'UTF-8', false);
      	// set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor(PDF_AUTHOR);
        $this->pdf->SetTitle(PDF_HEADER_TITLE);
        $this->pdf->SetSubject($this->subject);
        if ($this->languagecode==0)
        {
            $this->pdf->SetKeywords(strtoupper($this->pdffilename).'.EN');
        }
        elseif ($this->languagecode==1)
        {
            $this->pdf->SetKeywords(strtoupper($this->pdffilename).'.MR');
        }
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
        if ($this->languagecode==0)
        {
            $lg['a_meta_language'] = 'en';
        }
        elseif ($this->languagecode==1)
        {
            $lg['a_meta_language'] = 'mr';
        }
        $lg['w_page'] = 'Page - ';
        // set some language-dependent strings (optional)
	    $this->pdf->setLanguageArray($lg);
    }
    
    function startreport()
    {
        $this->newpage(True);
        $this->group();
        $this->reportfooter();
    }

    function reportheader()
    {
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->newrow(2);
        $this->textbox('गटनिहाय सभासदत्व हंगामवार ऊसनोंद गोषवारा',180,10,'S','C',1,'siddhanta',13);
        $this->newrow(5);
        if ($this->fromdate!='' and $this->todate!='')
        {
            $this->textbox('हंगाम: '.$_SESSION['yearperiodcode'].' दिनांक '.$this->fromdate.' पासून ते दिनांक '.$this->todate.' पर्यंत',180,10,'S','C',1,'siddhanta',12);
        }
        else
        {
            $this->textbox('हंगाम: '.$_SESSION['yearperiodcode'],180,10,'S','C',1,'siddhanta',12);
        }
        $this->summary['NETCANETONNAGE']=0;
        $this->summary['CNT']=0;
        $this->summary['ADSALI']=0;
        $this->summary['PRESEASONAL']=0;
        $this->summary['SURU']=0;
        $this->summary['KHODWA']=0;
        $this->summary['PRSURU']=0;
        $this->summary['TOTAL']=0;
        $this->summary['CNT_TON']=0;
        $this->summary['ADSALI_TON']=0;
        $this->summary['PRESEASONAL_TON']=0;
        $this->summary['SURU_TON']=0;
        $this->summary['KHODWA_TON']=0;
        $this->summary['PRSURU_TON']=0;
        $this->summary['TOTAL_TON']=0;

        $this->opareasummary['NETCANETONNAGE']=0;
        $this->opareasummary['CNT']=0;
        $this->opareasummary['ADSALI']=0;
        $this->opareasummary['PRESEASONAL']=0;
        $this->opareasummary['SURU']=0;
        $this->opareasummary['KHODWA']=0;
        $this->opareasummary['PRSURU']=0;
        $this->opareasummary['TOTAL']=0;
        $this->opareasummary['CNT_TON']=0;
        $this->opareasummary['ADSALI_TON']=0;
        $this->opareasummary['PRESEASONAL_TON']=0;
        $this->opareasummary['SURU_TON']=0;
        $this->opareasummary['KHODWA_TON']=0;
        $this->opareasummary['PRSURU_TON']=0;
        $this->opareasummary['TOTAL_TON']=0;
    }

	function pageheader()
    {
        ob_flush();
        ob_start();
        $this->liney = 15;
        if ($this->pdf->getPage()==1)
        {
            $this->reportheader();
        }
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        $this->newrow(7);
        $this->hline(10,195,$this->liney,'C');
        $this->setfieldwidth(40,10);
        $this->vline($this->liney,$this->liney+7,$this->x);
        $this->textbox('गट',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('उ.संख्या',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('आडसाली',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('पु.हंगामी',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox('सुरू',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('खोडवा',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox('मा.व.सुरू',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('एकूण',$this->w,$this->x,'S','R',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney,$this->liney+7,$this->x+$this->w);

        $this->newrow(9);
        $this->hline(10,195,$this->liney-2,'C');

    }

    function group()
    {
        $this->totalgroupcount=2;

        $cond=" and 1=1 and p.seasoncode=".$_SESSION['yearperiodcode'];
        $cond1= " and 1=1";
/* 
        if ($this->fromdate!='' and $this->todate!='')
        {
            $fdt = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $tdt = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
            $cond=$cond." and plantationdate>='".$fdt."' 
            and plantationdate<='".$tdt."'";
        } */
        if ($this->divisioncode!='' and $this->divisioncode!=0 )
        {
            $cond1=$cond1." and d.divisioncode=".$this->divisioncode;
            
        }
        if ($this->farmercategorycode!='' and $this->farmercategorycode!=0 )
        {
            $cond1=$cond1." and f.farmercategorycode=".$this->farmercategorycode;
            
        }

        $group_query_1 = "SELECT d.divisioncode
        ,vvv.circlecode,farmercategorycode,d.divisionnameuni,farmercategorynameuni,vvv.circlenameuni,sum(cnt) as cnt
        ,sum(preseasonal) preseasonal
        ,sum(suru) suru,sum(adsali) adsali,sum(khodwa) khodwa,sum(prsuru) prsuru,sum(total) total
        ,sum(cnt_ton) as cnt_ton
        ,sum(preseasonal_ton) preseasonal_ton
        ,sum(suru_ton) suru_ton,sum(adsali_ton) adsali_ton,sum(khodwa_ton) khodwa_ton
        ,sum(prsuru_ton) prsuru_ton,sum(total_ton) total_ton
        from (
        SELECT ff.farmercategorycode,ff.farmercategorynameuni,v.circlecode,1 as cnt
        ,case when p.plantationhangamcode =3 then p.area else 0 end as adsali
        ,case when p.plantationhangamcode =1 then p.area else 0 end as preseasonal
        ,case when p.plantationhangamcode =2 then p.area else 0 end as suru
        ,case when p.plantationhangamcode =4 then p.area else 0 end as khodwa
        ,case when p.plantationhangamcode >=5 then p.area else 0 end as prsuru
        ,p.area as total
        ,0 as cnt_ton
        ,0 as preseasonal_ton
        ,0 as suru_ton
        ,0 as adsali_ton
        ,0 as khodwa_ton
        ,0 as prsuru_ton
        ,0 as total_ton
        FROM plantationheader p,village v, farmercategory ff,farmer f
        where p.villagecode=v.villagecode
        and p.farmercode=f.farmercode
        and f.farmercategorycode=ff.farmercategorycode 
          {$cond}
         
        union all
        select farmercategorycode,farmercategorynameuni,t.circlecode,0 as cnt 
        ,0 as preseasonal
        ,0 as suru
        ,0 as adsali
        ,0 as khodwa
        ,0 as prsuru
        ,0 as total
        ,0 as cnt_ton
        ,0 as preseasonal_ton
        ,0 as suru_ton
        ,0 as adsali_ton
        ,0 as khodwa_ton
        ,0 as prsuru_ton
        ,0 as total_ton
        from 
        (select fff.farmercategorycode,fff.farmercategorynameuni,vv.circlecode,cc.circlenameuni,p.farmercode 
        from plantationheader p,village vv,circle cc, farmercategory fff,farmer f
        where p.villagecode=vv.villagecode 
        and p.farmercode=f.farmercode
        and f.farmercategorycode=fff.farmercategorycode 
        and vv.circlecode=cc.circlecode
          {$cond}
        group by fff.farmercategorycode,fff.farmercategorynameuni,vv.circlecode,cc.circlenameuni,p.farmercode)t 
        group by farmercategorycode,farmercategorynameuni,t.circlecode,t.circlenameuni
        
        union all
        SELECT farmercategorycode,farmercategorynameuni,circlecode,0 as cnt
        ,sum(preseasonal) preseasonal
        ,sum(suru) suru
        ,sum(adsali) adsali
        ,sum(khodwa) khodwa
        ,sum(prsuru) prsuru
        ,sum(total) total
        ,sum(cnt_ton) cnt_ton
        ,sum(preseasonal_ton) preseasonal_ton
        ,sum(suru_ton) suru_ton
        ,sum(adsali_ton) adsali_ton
        ,sum(khodwa_ton) khodwa_ton
        ,sum(prsuru_ton) prsuru_ton
        ,sum(total_ton) total_ton
        from (
        SELECT farmercategorycode,farmercategorynameuni,circlecode,farmercode,0 as cnt
        ,sum(preseasonal) preseasonal
        ,sum(suru) suru
        ,sum(adsali) adsali
        ,sum(khodwa) khodwa
        ,sum(prsuru) prsuru
        ,sum(total) total
        ,1 cnt_ton
        ,sum(preseasonal_ton) preseasonal_ton
        ,sum(suru_ton) suru_ton
        ,sum(adsali_ton) adsali_ton
        ,sum(khodwa_ton) khodwa_ton
        ,sum(prsuru_ton) prsuru_ton
        ,sum(total_ton) total_ton
        from (
        SELECT ff.farmercategorycode,ff.farmercategorynameuni,v.circlecode,p.farmercode,0 as cnt
        ,0 as preseasonal
        ,0 as suru
        ,0 as adsali
        ,0 as khodwa
        ,0 as prsuru
        ,0 as total
        ,0 as cnt_ton
        ,case when p.plantationhangamcode =3 then w.netweight else 0 end as adsali_ton
        ,case when p.plantationhangamcode =1 then w.netweight else 0 end as preseasonal_ton
        ,case when p.plantationhangamcode =2 then w.netweight else 0 end as suru_ton
        ,case when p.plantationhangamcode =4 then w.netweight else 0 end as khodwa_ton
        ,case when p.plantationhangamcode >=5 then w.netweight else 0 end as prsuru_ton
        ,netweight as total_ton
        FROM weightslip w,fieldslip fl,plantationheader p,village v, farmercategory ff,farmer f
        where p.villagecode=v.villagecode
        and p.farmercode=f.farmercode
        and f.farmercategorycode=ff.farmercategorycode 
        and w.seasoncode=fl.seasoncode
        and w.fieldslipnumber=fl.fieldslipnumber
        and fl.seasoncode=p.seasoncode
        and fl.plotnumber=p.plotnumber
         {$cond}
         ) group by farmercategorycode,farmercategorynameuni,circlecode,farmercode
         ) group by farmercategorycode,farmercategorynameuni,circlecode
        )ttt,circle vvv,division d
        where ttt.circlecode=vvv.circlecode
        and vvv.divisioncode=d.divisioncode
          {$cond1}
        group by d.divisioncode,d.divisionnameuni,farmercategorycode,farmercategorynameuni,vvv.circlecode,vvv.circlenameuni
        order by d.divisioncode,vvv.circlecode,farmercategorycode,farmercategorynameuni";
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);
            //$this->hline(10,405,$this->liney,'D'); 
            $this->opareasummary['NETCANETONNAGE']+=$group_row_1['NETCANETONNAGE'];
            $this->opareasummary['CNT']+=$group_row_1['CNT'];
            $this->opareasummary['ADSALI']+=$group_row_1['ADSALI'];
            $this->opareasummary['PRESEASONAL']+=$group_row_1['PRESEASONAL'];
            $this->opareasummary['SURU']+=$group_row_1['SURU'];
            $this->opareasummary['KHODWA']+=$group_row_1['KHODWA'];
            $this->opareasummary['PRSURU']+=$group_row_1['PRSURU'];
            $this->opareasummary['TOTAL']+=$group_row_1['TOTAL'];

            $this->opareasummary['CNT_TON']+=$group_row_1['CNT_TON'];
            $this->opareasummary['ADSALI_TON']+=$group_row_1['ADSALI_TON'];
            $this->opareasummary['PRESEASONAL_TON']+=$group_row_1['PRESEASONAL_TON'];
            $this->opareasummary['SURU_TON']+=$group_row_1['SURU_TON'];
            $this->opareasummary['KHODWA_TON']+=$group_row_1['KHODWA_TON'];
            $this->opareasummary['PRSURU_TON']+=$group_row_1['PRSURU_TON'];
            $this->opareasummary['TOTAL_TON']+=$group_row_1['TOTAL_TON'];

            $this->farmercategorysummary['NETCANETONNAGE']+=$group_row_1['NETCANETONNAGE'];
            $this->farmercategorysummary['CNT']+=$group_row_1['CNT'];
            $this->farmercategorysummary['ADSALI']+=$group_row_1['ADSALI'];
            $this->farmercategorysummary['PRESEASONAL']+=$group_row_1['PRESEASONAL'];
            $this->farmercategorysummary['SURU']+=$group_row_1['SURU'];
            $this->farmercategorysummary['KHODWA']+=$group_row_1['KHODWA'];
            $this->farmercategorysummary['PRSURU']+=$group_row_1['PRSURU'];
            $this->farmercategorysummary['TOTAL']+=$group_row_1['TOTAL'];
            
            $this->farmercategorysummary['CNT_TON']+=$group_row_1['CNT_TON'];
            $this->farmercategorysummary['ADSALI_TON']+=$group_row_1['ADSALI_TON'];
            $this->farmercategorysummary['PRESEASONAL_TON']+=$group_row_1['PRESEASONAL_TON'];
            $this->farmercategorysummary['SURU_TON']+=$group_row_1['SURU_TON'];
            $this->farmercategorysummary['KHODWA_TON']+=$group_row_1['KHODWA_TON'];
            $this->farmercategorysummary['PRSURU_TON']+=$group_row_1['PRSURU_TON'];
            $this->farmercategorysummary['TOTAL_TON']+=$group_row_1['TOTAL_TON'];


            $this->summary['NETCANETONNAGE']+=$group_row_1['NETCANETONNAGE'];
            $this->summary['CNT']+=$group_row_1['CNT'];
            $this->summary['ADSALI']+=$group_row_1['ADSALI'];
            $this->summary['PRESEASONAL']+=$group_row_1['PRESEASONAL'];
            $this->summary['SURU']+=$group_row_1['SURU'];
            $this->summary['KHODWA']+=$group_row_1['KHODWA'];
            $this->summary['PRSURU']+=$group_row_1['PRSURU'];
            $this->summary['TOTAL']+=$group_row_1['TOTAL'];

            $this->summary['CNT_TON']+=$group_row_1['CNT_TON'];
            $this->summary['ADSALI_TON']+=$group_row_1['ADSALI_TON'];
            $this->summary['PRESEASONAL_TON']+=$group_row_1['PRESEASONAL_TON'];
            $this->summary['SURU_TON']+=$group_row_1['SURU_TON'];
            $this->summary['KHODWA_TON']+=$group_row_1['KHODWA_TON'];
            $this->summary['PRSURU_TON']+=$group_row_1['PRSURU_TON'];
            $this->summary['TOTAL_TON']+=$group_row_1['TOTAL_TON'];

            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
    }

    function groupheader_1(&$group_row_1)
    {
        //$this->divisionname=$group_row_1['DIVISIONNAMEUNI'];
        $this->opareasummary['NETCANETONNAGE']=0;
        $this->opareasummary['CNT']=0;
        $this->opareasummary['ADSALI']=0;
        $this->opareasummary['PRESEASONAL']=0;
        $this->opareasummary['SURU']=0;
        $this->opareasummary['KHODWA']=0;
        $this->opareasummary['PRSURU']=0;
        $this->opareasummary['TOTAL']=0;

        $this->opareasummary['CNT_TON']=0;
        $this->opareasummary['ADSALI_TON']=0;
        $this->opareasummary['PRESEASONAL_TON']=0;
        $this->opareasummary['SURU_TON']=0;
        $this->opareasummary['KHODWA_TON']=0;
        $this->opareasummary['PRSURU_TON']=0;
        $this->opareasummary['TOTAL_TON']=0;

        $this->setfieldwidth(40,10);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox($group_row_1['DIVISIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        
        $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20); 
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->setfieldwidth(25);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            if ($this->isnewpage(10))
            {
                $this->newrow();
                $this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            else
            {
                $this->newrow();
                $this->hline(10,195,$this->liney-2,'C'); 
            }
    }
    function groupheader_2(&$group_row_1)
    {
        $this->farmercategorysummary['NETCANETONNAGE']=0;
        $this->farmercategorysummary['CNT']=0;
        $this->farmercategorysummary['ADSALI']=0;
        $this->farmercategorysummary['PRESEASONAL']=0;
        $this->farmercategorysummary['SURU']=0;
        $this->farmercategorysummary['KHODWA']=0;
        $this->farmercategorysummary['PRSURU']=0;
        $this->farmercategorysummary['TOTAL']=0;

        $this->farmercategorysummary['NETCANETONNAGE']=0;
        $this->farmercategorysummary['CNT_TON']=0;
        $this->farmercategorysummary['ADSALI_TON']=0;
        $this->farmercategorysummary['PRESEASONAL_TON']=0;
        $this->farmercategorysummary['SURU_TON']=0;
        $this->farmercategorysummary['KHODWA_TON']=0;
        $this->farmercategorysummary['PRSURU_TON']=0;
        $this->farmercategorysummary['TOTAL_TON']=0;

        $this->setfieldwidth(40,10);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox('  '.$group_row_1['CIRCLENAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        
        $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20); 
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(20);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->setfieldwidth(25);
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            if ($this->isnewpage(10))
            {
                $this->newrow();
                $this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            else
            {
                $this->newrow();
                $this->hline(10,195,$this->liney-2,'C'); 
            }
    }
    function groupheader_3(&$group_row_1)
    {
    }
    function groupheader_4(&$group_row_1)
    {
    }
    function groupheader_5(&$group_row_1)
    {
    }
    function groupheader_6(&$group_row_1)
    {
    }
    function groupheader_7(&$group_row_1)
    {
    }
    function detail_1($group_row_1)
    { 
            $this->setfieldwidth(40,10);
            $this->vline($this->liney-2,$this->liney+5,$this->x);
            $this->textbox('    '.$group_row_1['FARMERCATEGORYNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($group_row_1['CNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($group_row_1['ADSALI'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($group_row_1['PRESEASONAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20); 
            $this->textbox($group_row_1['SURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($group_row_1['KHODWA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(20);
            $this->textbox($group_row_1['PRSURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->setfieldwidth(25);
            $this->textbox($group_row_1['TOTAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->newrow(4);

            $this->setfieldwidth(40,10);
            $this->vline($this->liney-2,$this->liney+5,$this->x);
            $this->textbox('',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox($group_row_1['CNT_TON'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox(number_format_indian($group_row_1['ADSALI_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox(number_format_indian($group_row_1['PRESEASONAL_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20); 
            $this->textbox(number_format_indian($group_row_1['SURU_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
    
            $this->setfieldwidth(20);
            $this->textbox(number_format_indian($group_row_1['KHODWA_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
            
            $this->setfieldwidth(20);
            $this->textbox(number_format_indian($group_row_1['PRSURU_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            $this->setfieldwidth(25);
            $this->textbox(number_format_indian($group_row_1['TOTAL_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','');
            $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

            if ($this->isnewpage(10))
            {
                $this->newrow();
                $this->hline(10,195,$this->liney-2,'C'); 
                $this->newpage(True);
            }
            else
            {
                $this->newrow();
                $this->hline(10,195,$this->liney-2,'C'); 
            }
    }
    function groupfooter_1(&$group_row)
    {
        $this->setfieldwidth(40,10);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox('एकूण '.$group_row['DIVISIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->opareasummary['CNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->opareasummary['ADSALI'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->opareasummary['PRESEASONAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox($this->opareasummary['SURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->opareasummary['KHODWA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox($this->opareasummary['PRSURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($this->opareasummary['TOTAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->newrow(4);

        $this->setfieldwidth(40,10);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox('',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(number_format_indian($this->opareasummary['CNT_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(number_format_indian($this->opareasummary['ADSALI_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(number_format_indian($this->opareasummary['PRESEASONAL_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox(number_format_indian($this->opareasummary['SURU_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(number_format_indian($this->opareasummary['KHODWA_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox(number_format_indian($this->opareasummary['PRSURU_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox(number_format_indian($this->opareasummary['TOTAL_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);


        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
        }
    }
    function groupfooter_2(&$group_row)
    {
        $this->setfieldwidth(40,10);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox('  एकूण गट ',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->farmercategorysummary['CNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->farmercategorysummary['ADSALI'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->farmercategorysummary['PRESEASONAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox($this->farmercategorysummary['SURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->farmercategorysummary['KHODWA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox($this->farmercategorysummary['PRSURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($this->farmercategorysummary['TOTAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->newrow(4);

        $this->setfieldwidth(40,10);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox('',$this->w,$this->x,'S','L',1,'siddhanta',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->farmercategorysummary['CNT_TON'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(number_format_indian($this->farmercategorysummary['ADSALI_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(number_format_indian($this->farmercategorysummary['PRESEASONAL_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox(number_format_indian($this->farmercategorysummary['SURU_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(number_format_indian($this->farmercategorysummary['KHODWA_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox(number_format_indian($this->farmercategorysummary['PRSURU_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox(number_format_indian($this->farmercategorysummary['TOTAL_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
        }
    }
    function groupfooter_3(&$group_row)
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
    
    function pagefooter($islastpage=false)
    {
    }

    function reportfooter()
    {
        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        $this->setfieldwidth(40,10);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox('एकूण एकंदर'.$group_row['DIVISIONNAMEUNI'],$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->summary['CNT'],$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->summary['ADSALI'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->summary['PRESEASONAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox($this->summary['SURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox($this->summary['KHODWA'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox($this->summary['PRSURU'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox($this->summary['TOTAL'],$this->w,$this->x,'C','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->newrow(4);

        $this->setfieldwidth(40,10);
        $this->vline($this->liney-2,$this->liney+5,$this->x);
        $this->textbox('',$this->w,$this->x,'S','L',1,'siddhanta',11,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(number_format_indian($this->summary['CNT_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(number_format_indian($this->summary['ADSALI_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(number_format_indian($this->summary['PRESEASONAL_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20); 
        $this->textbox(number_format_indian($this->summary['SURU_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox(number_format_indian($this->summary['KHODWA_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);
        
        $this->setfieldwidth(20);
        $this->textbox(number_format_indian($this->summary['PRSURU_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox(number_format_indian($this->summary['TOTAL_TON'],3),$this->w,$this->x,'N','R',1,'SakalMarathiNormal922',10,'','','','B');
        $this->vline($this->liney-2,$this->liney+5,$this->x+$this->w);

        if ($this->isnewpage(10))
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
            $this->newpage(True);
        }
        else
        {
            $this->newrow();
            $this->hline(10,195,$this->liney-2,'C'); 
        }

    }

    function endreport()
    {
        // reset pointer to the last page*/
        ob_end_clean();
	    $this->pdf->lastPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        $this->pdf->SetDisplayMode($zoom='default', $layout='OneColumn', $mode='UseThumbs');
        $this->pdf->Output($this->pdffilename.'-'.currentindiandatetimenamestamp().'.pdf', 'I');
    }

    function export()
    {
        $cond=" and 1=1 and seasoncode=".$_SESSION['yearperiodcode'];
        $cond1= " and 1=1";

        if ($this->fromdate!='' and $this->todate!='')
        {
            $cond=$cond." and plantationdate>='".$this->fromdate."'
            and plantationdate<='".$this->todate."'";
        }
        if ($this->divisioncode!='' and $this->divisioncode!=0 )
        {
            $cond1=$cond1." and d.divisioncode=".$this->divisioncode;
            
        }
        if ($this->farmercategorycode!='' and $this->farmercategorycode!=0 )
        {
            $cond1=$cond1." and farmercategorycode=".$this->farmercategorycode;
            
        }

        $group_query_1 = "SELECT d.divisioncode
        ,vvv.circlecode,farmercategorycode,d.divisionnameuni,farmercategorynameuni,vvv.circlenameuni,sum(cnt) as cnt
        ,sum(preseasonal) preseasonal
        ,sum(suru) suru,sum(adsali) adsali,sum(khodwa) khodwa,sum(total) total
        from (
        SELECT ff.farmercategorycode,ff.farmercategorynameuni,v.circlecode,0 as cnt
        ,case when p.plantationhangamcode =1 then p.area else 0 end as adsali
        ,case when p.plantationhangamcode =2 then p.area else 0 end as preseasonal
        ,case when p.plantationhangamcode =3 then p.area else 0 end as suru
        ,case when p.plantationhangamcode >=4 then p.area else 0 end as khodwa
        ,p.area as total
        FROM plantationheader p,village v, farmercategory ff
        where p.villagecode=v.villagecode
        and p.farmercategorycode=ff.farmercategorycode 
          {$cond}
         
        union all
        select farmercategorycode,farmercategorynameuni,t.circlecode,count(*) as cnt 
        ,0 as preseasonal
        ,0 as suru
        ,0 as adsali
        ,0 as khodwa
        ,0 as total
        from 
        (select fff.farmercategorycode,fff.farmercategorynameuni,vv.circlecode,cc.circlenameuni,pp.farmercode 
        from plantationheader pp,village vv,circle cc, farmercategory fff
        where pp.villagecode=vv.villagecode 
        and pp.farmercategorycode=fff.farmercategorycode
        and vv.circlecode=cc.circlecode
          {$cond}
        group by fff.farmercategorycode,fff.farmercategorynameuni,vv.circlecode,cc.circlenameuni,pp.farmercode)t 
        group by farmercategorycode,farmercategorynameuni,t.circlecode,t.circlenameuni
        )ttt,circle vvv,division d
        where ttt.circlecode=vvv.circlecode
        and vvv.divisioncode=d.divisioncode
          {$cond1}
        group by d.divisioncode,d.divisionnameuni,farmercategorycode,farmercategorynameuni,vvv.circlecode,vvv.circlenameuni
        order by d.divisioncode,vvv.circlecode,farmercategorycode,farmercategorynameuni";
           $result = oci_parse($this->connection, $group_query_1);
           $r = oci_execute($result);
           $response = array();
           $filename='circlewisesummary.csv';
           //$fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');
           $fp1=fopen('php://memory', 'w');
           fputcsv($fp1, array('Area','Circle','Membership','Member Count','Adsali','Purv Hangami','Suru','Khodva','Last Season','Total',));
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
               fputcsv($fp1, array($row['DIVISIONNAMEUNI'],$row['CIRCLENAMEUNI'],$row['FARMERCATEGORYNAMEUNI'],$row['CNT'],$row['ADSALI'],$row['PRESEASONAL'],$row['SURU'],$row['KHODWA'],$row['TOTAL']), $delimiter = ',', $enclosure = '"');
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