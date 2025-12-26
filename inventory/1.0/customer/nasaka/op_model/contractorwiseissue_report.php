<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class contractor extends reportbox
{	
    public $sr_no;
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
        $this->pdf->SetSubject('Sale Register');
        $this->pdf->SetKeywords('SLRG_000.EN');
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
        $this->pdf->Output('SLRG_000.pdf', 'I');
    }
	function pageheader()
    {
        $this->liney = 10;
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        $this->newrow(10);
      
       
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
        $this->textbox(' Cane Harvesting Material Status (Season- '.substr($this->season,0,4).'-'.substr($this->season,6,2).')',410,10,'S','C',1,'verdana',11,'','','','B');
        
       
        $this->hline(10,410,$this->liney+6,'C'); 
        $this->newrow();
              

        $this->setfieldwidth(270,10);
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox('Issue',$this->w,$this->x,'S','C',1,'verdana',13,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(130);        
        $this->textbox('Credit',$this->w,$this->x,'S','C',1,'verdana',13,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->hline(10,410,$this->liney+6,'C'); 
        $this->newrow();
       

        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+7,$this->x);
      //  $this->textbox('Sr.No',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
       // $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        

        $this->setfieldwidth(120);        
        $this->textbox('Item Code',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('1009',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('5001',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('1008 ',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('1010',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('1003 ',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('1002',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('5005 ',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('7002',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('7001 ',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(45);
        //$this->textbox('PO ',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('7002',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('7001',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(55);
       // $this->textbox('7001',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);


        $this->hline(10,410,$this->liney+6,'C');
        $this->newrow();
        

        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox('Sr.No',$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        

        $this->setfieldwidth(75);        
        $this->textbox('Contractor Name',$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Date',$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('Issue No',$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('knife',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('Bamboo',$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox(' Tent',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('24X21',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('24X24',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('28X28',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('Stand',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('B-Cart',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('T-Cart',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Date',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        $this->textbox('Slip no',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);


        $this->setfieldwidth(15);
        $this->textbox('B-Cart',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('T-Cart',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        $this->textbox('Jugad',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->hline(10,410,$this->liney+7,'C');
        $this->newrow();
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

        $this->totalmm["knife"]=0;
        $this->totalmm["bamboo"]=0;
        $this->totalmm["tent"]=0;
        $this->totalmm["wire_rope_2421"]=0;
        $this->totalmm["wire_rope_2424"]=0;
        $this->totalmm["wire_rope_2828"]=0;
        $this->totalmm["stand"]=0;
        $this->totalmm["bullockkart"]=0;
        $this->totalmm["tractorcart"]=0;

        $cond="1=1";
         if ($this->fromdate!='' and $this->todate!='')
        {
            
            $frdt=DateTime::createFromFormat('Y-m-d',$this->fromdate)->format('d-M-Y');
            $todt=DateTime::createFromFormat('Y-m-d',$this->todate)->format('d-M-Y');
            $cond= $cond."and h.purchesorderdate>='".$frdt."' and h.purchesorderdate<='".$todt."'";

        } 
       
       /*  if ($this->mainstorecode!=0)
        {
            if ($cond=="")
                $cond="i.mainstorecode=".$this->mainstorecode;
            else
                $cond=$cond." and i.mainstorecode=".$this->mainstorecode;
        } */
        
        $group_query_1 ="
        select 
        contractorcode
        ,contractornameeng
        ,issudate
        ,issuenumber
        ,sum(knife)knife
        ,sum(bamboo)bamboo
        ,sum(tent)tent
        ,sum(wire_rope_2421)wire_rope_2421
        ,sum(wire_rope_2424)wire_rope_2424
        ,sum(wire_rope_2828)wire_rope_2828
        ,sum(stand)stand
        ,sum(bullockkart)bullockkart
        ,sum(tractorcart)tractorcart
        from
        (select 
        c.contractorcode
        ,c.contractornameeng
        ,to_char(t.issuedate,'dd/MM/yyyy')issudate
        ,t.issuenumber
        ,t.financialyear
        ,case when d.itemcode=10801009 then nvl(d.quantity,0) else 0 end knife
        ,case when d.itemcode=10805001 then nvl(d.quantity,0) else 0 end bamboo
        ,case when d.itemcode=10801008 then nvl(d.quantity,0) else 0 end tent
        ,case when d.itemcode=10801010 then nvl(d.quantity,0) else 0 end wire_rope_2421
        ,case when d.itemcode=10801003 then nvl(d.quantity,0) else 0 end wire_rope_2424
        ,case when d.itemcode=10801002 then nvl(d.quantity,0) else 0 end wire_rope_2828
        ,case when d.itemcode=11005005 then nvl(d.quantity,0) else 0 end stand
        ,case when d.itemcode=12307002 then nvl(d.quantity,0) else 0 end bullockkart
        ,case when d.itemcode=12307001 then nvl(d.quantity,0) else 0 end tractorcart
        from issueheader t
        ,issueitemdetail d
        ,nst_nasaka_agriculture.contractor c
        where 
        t.transactionnumber=d.transactionnumber
        and t.issuetohtcontractorcode>0
        and t.issuetohtcontractorcode=c.contractorcode
        )where financialyear=".$this->season."
        group by contractorcode,contractornameeng
        ,issudate,issuenumber
        order by contractorcode

        "; 
        
        $group_result_1 = oci_parse($this->connection, $group_query_1);
        $r = oci_execute($group_result_1);
        $i=0;
        $this->newpage(true);
        while ($group_row_1 = oci_fetch_array($group_result_1,OCI_BOTH+OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $this->grouptrigger($group_row_1,$last_row);
            $this->detail_1($group_row_1);          
            $last_row=$group_row_1;
        }
        $this->grouptrigger($group_row_1,$last_row,'E');
        $this->reportfooter();
    }
    
    function groupheader_1(&$group_row_1)
    {
        $this->sr_no=1;
       $this->total["knife"]=0;
       $this->total["bamboo"]=0;
       $this->total["tent"]=0;
       $this->total["wire_rope_2421"]=0;
       $this->total["wire_rope_2424"]=0;
       $this->total["wire_rope_2828"]=0;
       $this->total["stand"]=0;
       $this->total["bullockkart"]=0;
       $this->total["tractorcart"]=0;

       $this->setfieldwidth(400,10);         
       $this->vline($this->liney-1,$this->liney+7,$this->x);       
        $this->textbox('               '. $group_row_1['CONTRACTORNAMEENG'],$this->w,$this->x,'S','L',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        $this->newrow();
        $this->hline(10,410,$this->liney-1,'C'); 

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
        $ht=7;
        
        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x);       
        $this->textbox( $this->sr_no,$this->w,$this->x,'S','R',1,'verdana',11);
       // $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);
        
        $this->setfieldwidth(75);
        
        $this->setfieldwidth(25);       
       $this->textbox($group_row_1['ISSUDATE'],$this->w,$this->x,'S','C',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(20);        
        $this->textbox($group_row_1['ISSUENUMBER'],$this->w,$this->x,'S','C',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['KNIFE'],$this->w,$this->x,'S','R',1,'verdana',11);        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['BAMBOO'],$this->w,$this->x,'S','R',1,'verdana',11);        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['TENT'],$this->w,$this->x,'S','R',1,'verdana',11);        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['WIRE_ROPE_2421'],$this->w,$this->x,'S','R',1,'verdana',11);        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['WIRE_ROPE_2424'],$this->w,$this->x,'S','R',1,'verdana',11);        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['WIRE_ROPE_2828'],$this->w,$this->x,'S','R',1,'verdana',11);        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['STAND'],$this->w,$this->x,'S','R',1,'verdana',11);        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['BULLOCKKART'],$this->w,$this->x,'S','R',1,'verdana',11);        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['TRACTORCART'],$this->w,$this->x,'S','R',1,'verdana',11);        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        //$this->textbox('Date',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
        //$this->textbox('Slip no',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);


        $this->setfieldwidth(15);
        //$this->textbox('B-Cart',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
       // $this->textbox('T-Cart',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        //$this->textbox('Jugad',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->sr_no= $this->sr_no+1;


        $this->total["knife"]= $this->total["knife"]+$group_row_1['KNIFE'];
        $this->total["bamboo"]=$this->total["bamboo"]+$group_row_1['BAMBOO'];
        $this->total["tent"]=$this->total["tent"]+$group_row_1['TENT'];
        $this->total["wire_rope_2421"]=$this->total["wire_rope_2421"]+$group_row_1['WIRE_ROPE_2421'];
        $this->total["wire_rope_2424"]=$this->total["wire_rope_2424"]+$group_row_1['WIRE_ROPE_2424'];
        $this->total["wire_rope_2828"]=$this->total["wire_rope_2828"]+$group_row_1['WIRE_ROPE_2828'];
        $this->total["stand"]=$this->total["stand"]+$group_row_1['STAND'];
        $this->total["bullockkart"]=$this->total["bullockkart"]+$group_row_1['BULLOCKKART'];
        $this->total["tractorcart"]=$this->total["tractorcart"]+$group_row_1['TRACTORCART'];

        $this->totalmm["knife"]= $this->totalmm["knife"]+$group_row_1['KNIFE'];
        $this->totalmm["bamboo"]=$this->totalmm["bamboo"]+$group_row_1['BAMBOO'];
        $this->totalmm["tent"]=$this->totalmm["tent"]+$group_row_1['TENT'];
        $this->totalmm["wire_rope_2421"]=$this->totalmm["wire_rope_2421"]+$group_row_1['WIRE_ROPE_2421'];
        $this->totalmm["wire_rope_2424"]=$this->totalmm["wire_rope_2424"]+$group_row_1['WIRE_ROPE_2424'];
        $this->totalmm["wire_rope_2828"]=$this->totalmm["wire_rope_2828"]+$group_row_1['WIRE_ROPE_2828'];
        $this->totalmm["stand"]=$this->totalmm["stand"]+$group_row_1['STAND'];
        $this->totalmm["bullockkart"]=$this->totalmm["bullockkart"]+$group_row_1['BULLOCKKART'];
        $this->totalmm["tractorcart"]=$this->totalmm["tractorcart"]+$group_row_1['TRACTORCART'];

        if ($this->isnewpage(15))
        {
            $this->newrow($ht);
            $this->hline(10,410,$this->liney,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow($ht);
            $this->hline(10,410,$this->liney,'C');
        }
    }
    
    function groupfooter_1(&$group_row_1)
    { 
        $ht=7;
        
        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x);       
      //  $this->textbox( $this->sr_no,$this->w,$this->x,'S','R',1,'verdana',11);
       // $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);
        
        $this->setfieldwidth(75);
        
        $this->setfieldwidth(25);       
      // $this->textbox($group_row_1['ISSUDATE'],$this->w,$this->x,'S','C',1,'verdana',10);
       // $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(20);        
        $this->textbox('Total ',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($this->total["knife"],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox( $this->total["bamboo"],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox( $this->total["tent"],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox( $this->total["wire_rope_2421"],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox( $this->total["wire_rope_2424"],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox( $this->total["wire_rope_2828"],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox( $this->total["stand"],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox( $this->total["bullockkart"],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox( $this->total["tractorcart"],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        //$this->textbox('Date',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
       // $this->textbox('Slip no',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);


        $this->setfieldwidth(15);
        //$this->textbox('B-Cart',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        //$this->textbox('T-Cart',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        //$this->textbox('Jugad',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->newrow($ht);
        $this->hline(10,410,$this->liney,'C');
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

        $ht=7;
        
        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+$ht,$this->x);       
      //  $this->textbox( $this->sr_no,$this->w,$this->x,'S','R',1,'verdana',11);
       // $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);
        
        $this->setfieldwidth(75);
        
        $this->setfieldwidth(25);       
      // $this->textbox($group_row_1['ISSUDATE'],$this->w,$this->x,'S','C',1,'verdana',10);
       // $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(20);        
        $this->textbox('Grand Total ',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+$ht,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($this->totalmm["knife"],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox( $this->totalmm["bamboo"],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox( $this->totalmm["tent"],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox( $this->totalmm["wire_rope_2421"],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox( $this->totalmm["wire_rope_2424"],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox( $this->totalmm["wire_rope_2828"],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox( $this->totalmm["stand"],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox( $this->totalmm["bullockkart"],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox( $this->totalmm["tractorcart"],$this->w,$this->x,'S','R',1,'verdana',11,'','','','B');        
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        //$this->textbox('Date',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(20);
       // $this->textbox('Slip no',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);


        $this->setfieldwidth(15);
        //$this->textbox('B-Cart',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        //$this->textbox('T-Cart',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(25);
        //$this->textbox('Jugad',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(30);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->newrow($ht);
        $this->hline(10,410,$this->liney,'C');

        $this->newrow(30);
        $this->setfieldwidth(40,290,);
        $this->textbox('Store Keeper',$this->w,$this->x,'S','C',1,'verdana',11,'','','','B');

    }

}    
?>
