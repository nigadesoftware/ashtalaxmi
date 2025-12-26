<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_P.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class issuelist extends reportbox
{	
    public $transactionnumber;
    public $ftransactionnumber;
    public $fissuedate;
    public $fissuenumber;
    public $fsection;
    public $femployee;
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
        $this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

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
        $this->pdf->SetFont('verdana', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
       // $this->textbox($this->ftransactionnumber['FARMERCOUNT'],175,10,'S','C',1,'siddhanta',13);
       
        $this->newrow(10);
        $this->textbox('Cane Harvesting Material Status',200,10,'S','C',1,'verdana',11);
        $this->newrow();
        $this->hline(10,205,$this->liney-1,'C');
       
        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Sr.No.',$this->w,$this->x,'S','C',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
       
        $this->setfieldwidth(60);
        $this->textbox('Contractor Name',$this->w,$this->x,'S','L',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('Knife',$this->w,$this->x,'S','L',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('Bamboo',$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('Tent',$this->w,$this->x,'S','L',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('B-Cart',$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('T-Cart',$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('B-Wirerope',$this->w,$this->x,'S','L',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('T-Wirerope',$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox('Wirerope',$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        
  
         $this->hline(10,205,$this->liney-1,'C');
        $this->newrow();
        $this->hline(10,205,$this->liney-1,'C');

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
        $cond="1=1";
       
        if ($this->fromdate!='' and $this->todate !='')
        {
            $frdt=DateTime::createFromFormat('Y-m-d',$this->fromdate)->format('d-M-Y');
            $todt=DateTime::createFromFormat('Y-m-d',$this->todate)->format('d-M-Y');
  
            $cond = $cond." and h.issuedate>='".$frdt."' and h.issuedate<='".$todt."'";
        }
         if ($this->contractorcode!=0)
        {
            if ($cond=="")
                $cond="h.issuetohtcontractorcode=".$this->contractorcode;
            else
                $cond=$cond."  and h.issuetohtcontractorcode=".$this->contractorcode;
        } 

      
        $group_query_1 ="select row_number() over( order by contractorcode)sr_no
        ,contractorcode
        ,contractornameeng
        ,sum(wirerope_24_24)wirerope_24_24
        ,sum(waterproof_tent)waterproof_tent
        ,sum(knives)knives
        ,sum(bamboo_10_inch)bamboo_10_inch
        ,sum(tractor_cart_size12)tractor_cart_size12
        ,sum(bullock_wirerope)bullock_wirerope
        ,sum(tractor_wirerope)tractor_wirerope
        ,sum(bullockcart)bullockcart
        from(
        select  h.issuetohtcontractorcode contractorcode
        ,c.contractornameeng
        ,case when d.itemcode=10801003 then d.quantity else 0 end wirerope_24_24
        ,case when d.itemcode=10801008 then d.quantity else 0 end waterproof_tent
        ,case when d.itemcode=10801009 then d.quantity else 0 end knives
        ,case when d.itemcode=10805001 then d.quantity else 0 end bamboo_10_inch
        ,case when d.itemcode=12307001 then d.quantity else 0 end tractor_cart_size12
        ,case when d.itemcode=10801010 then d.quantity else 0 end bullock_wirerope
        ,case when d.itemcode=10801002 then d.quantity else 0 end tractor_wirerope
        ,case when d.itemcode=12307002 then d.quantity else 0 end bullockcart
        from nst_nasaka_inventory.issueheader h
        ,nst_nasaka_inventory.issueitemdetail d
        ,nst_nasaka_agriculture.contractor c
        where {$cond} and
        h.transactionnumber=d.transactionnumber
        and h.issuetohtcontractorcode=c.contractorcode
        and nvl(h.issuetohtcontractorcode,0)>0
        )group by contractorcode
        ,contractornameeng order by contractorcode
        
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
        $this->setfieldwidth(15,10);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox($group_row_1['SR_NO'],$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
       
        $this->setfieldwidth(60);
        $this->textbox($group_row_1['CONTRACTORNAMEENG'],$this->w,$this->x,'S','L',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['KNIVES'],$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['BAMBOO_10_INCH'],$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['WATERPROOF_TENT'],$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['BULLOCKCART'],$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['TRACTOR_CART_SIZE12'],$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['BULLOCK_WIREROPE'],$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['TRACTOR_WIREROPE'],$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['BULLOCKCART'],$this->w,$this->x,'S','R',1,'verdana',10);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        

       
        if ($this->isnewpage(15))
        {
            $this->newrow();
            $this->hline(10,205,$this->liney-1,'C'); 
            $this->newpage(True);
        }   
        else
        {
            $this->newrow();
            $this->hline(10,205,$this->liney-1,'C'); 
        }
    }
    
    function groupfooter_1(&$group_row_1)
    {
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
