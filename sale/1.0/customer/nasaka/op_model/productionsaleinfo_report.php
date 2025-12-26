<?php
    ob_start();
    include_once("../swappbase/reportbox.php");
    include_once("../swappbase/mypdf_A4_L.php");
    include_once("../info/routine.php");

//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class production extends reportbox
{	
   public $fromdate;
   public $todate;
   public $summary;
   public $P_s_30;
   public $P_m_30;
   public $P_Ss_30;
   public $total_pro;

   public $sf_s30;
   public $sf_m30;
   public $sf_ss30;
   public $total_free_sale;

   public $exportqty;
   public $grand_sale_total;

   public $inn_s_30;
   public $inn_m_30;
   public $inn_ss_30;
   public $total_inword;

   public $out_s_30;
   public $out_m_30;
   public $out_ss_30;
   public $total_outword;
    public function __construct(&$connection,$maxlines,$languagecode=1,$rowheight=7)
	{
		$this->connection = $connection;
        $this->maxlines = $maxlines;
        $this->languagecode = $languagecode;
        $this->rowheight= $rowheight;
        $headerfontname = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/siddhanta.ttf', 'TrueTypeUnicode', '', 96);
	    $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/SakalMarathiNormal9.22.ttf', 'TrueTypeUnicode', '', 32);
        $fontname1 = TCPDF_FONTS::addTTFfont('../tcpdf/fonts/verdana.ttf', 'TrueType', '', 32);
    	// create new PDF document
	    $this->pdf = new MYPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
        
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
        $this->pdf->SetFont('siddhanta', '', 11, '', true);
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y h:i:sa',$dt);
        date_default_timezone_set("UTC");
        //textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
       // $this->textbox($this->ftransactionnumber['FARMERCOUNT'],175,10,'S','C',1,'siddhanta',13);
       
         $this->newrow(10);
        $this->textbox('DateWise Production Sale Register...',250,10,'S','C',1,'siddhanta',13);
        if ($this->fromdate!='' and $this->todate!='')
        {
            $this->newrow(7);
            $this->textbox('From Date '.$this->fromdate.' To Date '.$this->todate. '',250,10,'S','C',1,'verdana',12);
        }       
        $this->newrow(10);
        $this->hline(5,295,$this->liney-1,'C'); 

        $this->setfieldwidth(25,5);   
        $this->vline($this->liney-1,$this->liney+21,$this->x);   
        $this->textbox('Date',$this->w,$this->x,'S','C',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+21,$this->x+$this->w);

        $this->setfieldwidth(55);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Production Datewise',$this->w,$this->x,'S','C',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w+5);
      
        $this->setfieldwidth(60);
        $this->textbox('Dispatches Gradewise',$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w+34);
        $this->newrow();
        $this->hline(90,179,$this->liney-1,'C'); 

        $this->setfieldwidth(60,90);
        $this->vline($this->liney-1,$this->liney+6,$this->x);
        $this->textbox('Domestic',$this->w,$this->x,'S','C',1,'verdana',11);  
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(13);
        $this->textbox('Export',$this->w,$this->x,'S','L',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->setfieldwidth(16);
        $this->textbox('Total',$this->w,$this->x,'S','C',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+6,$this->x+$this->w);
        $this->newrow();
        $this->hline(30,295,$this->liney-1,'C');

        $this->setfieldwidth(15,30);
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox('S-30',$this->w,$this->x,'S','C',1,'verdana',11);  
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        $this->setfieldwidth(15);
        $this->textbox('M-30',$this->w,$this->x,'S','C',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        $this->setfieldwidth(15);
        $this->textbox('SS-30',$this->w,$this->x,'S','C',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        $this->setfieldwidth(15);
        $this->textbox('Total',$this->w,$this->x,'S','C',1,'verdana',11);
      //$this->vline($this->liney-1,$this->liney+7,$this->x+$this->w-5);

        $this->setfieldwidth(15);
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox('S-30',$this->w,$this->x,'S','C',1,'verdana',11);  
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        $this->setfieldwidth(15);
        $this->textbox('M-30',$this->w,$this->x,'S','C',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        $this->setfieldwidth(15);
        $this->textbox('SS-30',$this->w,$this->x,'S','C',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        $this->setfieldwidth(15);
        $this->textbox('Total',$this->w,$this->x,'S','C',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15,179);
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox('S-30',$this->w,$this->x,'S','C',1,'verdana',11);  
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        $this->setfieldwidth(15);
        $this->textbox('M-30',$this->w,$this->x,'S','C',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        $this->setfieldwidth(15);
        $this->textbox('SS-30',$this->w,$this->x,'S','C',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        $this->setfieldwidth(13);
        $this->textbox('Total',$this->w,$this->x,'S','C',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox('S-30',$this->w,$this->x,'S','C',1,'verdana',11);  
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        $this->setfieldwidth(15);
        $this->textbox('M-30',$this->w,$this->x,'S','C',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        $this->setfieldwidth(15);
        $this->textbox('SS-30',$this->w,$this->x,'S','C',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        $this->setfieldwidth(13);
        $this->textbox('Total',$this->w,$this->x,'S','C',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->newrow();
        $this->hline(5,295,$this->liney,'C');


        $this->newrow(-21);
        $this->setfieldwidth(60,170);
        $this->textbox('Transfered Inword',$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+13,$this->x+$this->w+5);

        $this->setfieldwidth(60);
        $this->textbox('Transfered Outword',$this->w,$this->x,'S','R',1,'verdana',11);
        $this->vline($this->liney-1,$this->liney+13,$this->x+$this->w+5);

        $this->newrow(22);

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
        $this->summary["total"]=0; $this->P_s_30["total"]=0;  $this->P_m_30["total"]=0;   $this->P_Ss_30["total"]=0;           
        $this->total_pro["total"]=0;  $this->sf_s30["total"]=0; $this->sf_m30["total"]=0;$this->sf_ss30["total"]=0;          
        $this->total_free_sale["total"]=0;  $this->exportqty["total"]=0;     
        $this->grand_sale_total["total"]=0;   
        $this->inn_s_30["total"]=0;   
        $this->inn_m_30["total"]=0;   
        $this->inn_ss_30["total"]=0;   
        $this->total_inword["total"]=0;   
        $this->out_s_30["total"]=0;   
        $this->out_m_30["total"]=0;   
        $this->out_ss_30["total"]=0;   
        $this->total_outword["total"]=0;
        $this->totalgroupcount=1;
        $cond="1=1";
         if ($this->fromdate!='' and $this->todate!='')
        {
            $this->fromdate = DateTime::createFromFormat('d/m/Y',$this->fromdate)->format('d-M-Y');
            $this->todate = DateTime::createFromFormat('d/m/Y',$this->todate)->format('d-M-Y');
        }
       
        $group_query_1 ="
        select to_char(ddate,'dd/mm/yyyy')ddate
        ,row_number()over( order by ddate)Sr_no
        ,nvl(sum(P_s_30),0)P_s_30,nvl(sum(P_m_30),0)P_m_30,nvl(sum(P_ss_30),0)P_Ss_30
        ,nvl(nvl(sum(P_s_30),0)+nvl(sum(P_m_30),0)+nvl(sum(P_ss_30),0),0)total_pro
        
        ,nvl(sum(sf_s30),0)sf_s30,nvl(sum(sf_m30),0)sf_m30,nvl(sum(sf_ss30),0)sf_ss30
        ,nvl(nvl(sum(sf_s30),0)+nvl(sum(sf_m30),0)+nvl(sum(sf_ss30),0),0)total_free_sale
        
        ,nvl(sum(exportqty),0)exportqty
        ,nvl(nvl(sum(sf_s30),0)+nvl(sum(sf_m30),0)+nvl(sum(sf_ss30),0)+nvl(sum(exportqty),0),0) grand_sale_total
               
        ,nvl(sum(inn_s_30),0)inn_s_30,nvl(sum(inn_m_30),0)inn_m_30,nvl(sum(inn_ss_30),0)inn_ss_30 
        ,nvl(nvl(sum(inn_s_30),0)+nvl(sum(inn_m_30),0)+nvl(sum(inn_ss_30),0),0)total_inword
        
        ,nvl(sum(out_s_30),0)out_s_30,nvl(sum(out_m_30),0)out_m_30,nvl(sum(out_ss_30),0)out_ss_30 
        ,nvl(nvl(sum(out_s_30),0)+nvl(sum(out_m_30),0)+nvl(sum(out_ss_30),0),0)total_outword
        
       ,nvl(sum(inn_s_30_lotnumbet),0)inn_s_30_lotnumbet 
       ,nvl(sum(inn_m_30_lotnumbet),0)inn_m_30_lotnumbet
       ,nvl(sum(inn_ss_30_lotnumbet),0)inn_ss_30_lotnumbet
       ,nvl(sum(out_s_30_lotnumbet),0)out_s_30_lotnumbet
       ,nvl(sum(out_m_30_lotnumbet),0)out_m_30_lotnumbet
       ,nvl(sum(out_ss_30_lotnumbet),0)out_ss_30_lotnumbet    
     

        from (select t.transactiondate ddate
        ,case when t.finishedgoodscode=1 then t.quantity end P_s_30
        ,case when t.finishedgoodscode=2 then t.quantity end P_m_30
        ,case when t.finishedgoodscode=3 then t.quantity end P_ss_30 
        ,0 sf_s30,0 sf_m30,0 sf_ss30,0 exportqty,0 inn_s_30,0 inn_m_30,0 inn_ss_30
        ,0 out_s_30,0 out_m_30,0 out_ss_30
         ,0 inn_s_30_lotnumbet,0 inn_m_30_lotnumbet,0 inn_ss_30_lotnumbet        
        ,0 out_s_30_lotnumbet,0 out_m_30_lotnumbet,0 out_ss_30_lotnumbet
        from GODOWNTRANSACTION t where transactiontypecode = 1
     union all
        select h.invoicedate ddate,0 P_s_30,0 P_m_30,0 P_ss_3
        ,case when d.finishedgoodscode=1 then d.salequantity end sf_s30
        ,case when d.finishedgoodscode=2 then d.salequantity end sf_m30
        ,case when d.finishedgoodscode=3 then d.salequantity end sf_ss30
        ,0 exportqty,0 inn_s_30,0 inn_m_30,0 inn_ss_30
        ,0 out_s_30,0 out_m_30,0 out_ss_30
         ,0 inn_s_30_lotnumbet,0 inn_m_30_lotnumbet,0 inn_ss_30_lotnumbet        
        ,0 out_s_30_lotnumbet,0 out_m_30_lotnumbet,0 out_ss_30_lotnumbet
        from saleinvoiceheader h,saleinvoicedetail d
        where h.transactionnumber=d.transactionnumber
        and h.goodscategorycode=1 and h.salecategorycode=1
     union all
        select h.invoicedate ddate,0 P_s_30,0 P_m_30,0 P_ss_3,0 sf_s30,0 sf_m30,0 sf_ss30
        ,d.salequantity exportqty
        ,0 inn_s_30,0 inn_m_30,0 inn_ss_30,0 out_s_30,0 out_m_30,0 out_ss_30
        ,0 inn_s_30_lotnumbet,0 inn_m_30_lotnumbet,0 inn_ss_30_lotnumbet        
        ,0 out_s_30_lotnumbet,0 out_m_30_lotnumbet,0 out_ss_30_lotnumbet
        from saleinvoiceheader h,saleinvoicedetail d
        where h.transactionnumber=d.transactionnumber
        and h.goodscategorycode=1 and h.salecategorycode=3
     union all
        select t.transactiondate ddate,0 P_s_30,0 P_m_30,0 P_ss_3,0 sf_s30,0 sf_m30,0 sf_ss30
        ,0 exportqty
        ,case when t.finishedgoodscode=1 then t.quantity end inn_s_30
        ,case when t.finishedgoodscode=2 then t.quantity end inn_m_30
        ,case when t.finishedgoodscode=3 then t.quantity end inn_ss_30
        ,0 out_s_30,0 out_m_30,0 out_ss_30
        
        ,case when t.finishedgoodscode=1 then t.lotnumber end inn_s_30_lotnumbet
        ,case when t.finishedgoodscode=2 then t.lotnumber end inn_m_30_lotnumbet
        ,case when t.finishedgoodscode=3 then t.lotnumber end inn_ss_30_lotnumbet        
        ,0 out_s_30_lotnumbet,0 out_m_30_lotnumbet,0 out_ss_30_lotnumbet      
        from GODOWNTRANSACTION t where t.transactiontypecode=4 
     union all      
        select t.transactiondate ddate,0 P_s_30,0 P_m_30,0 P_ss_3,0 sf_s30,0 sf_m30,0 sf_ss30
        ,0 exportqty,0 inn_s_30,0 inn_m_30,0 inn_ss_30      
        ,case when t.finishedgoodscode=1 then t.quantity end out_s_30
        ,case when t.finishedgoodscode=2 then t.quantity end out_m_30
        ,case when t.finishedgoodscode=3 then t.quantity end out_ss_30
        
        ,0 inn_s_30_lotnumbet,0 inn_m_30_lotnumbet,0 inn_ss_30_lotnumbet
        ,case when t.finishedgoodscode=1 then t.lotnumber end out_s_30_lotnumbet
        ,case when t.finishedgoodscode=2 then t.lotnumber end out_m_30_lotnumbet
        ,case when t.finishedgoodscode=3 then t.lotnumber end out_ss_30_lotnumbet
      
        from GODOWNTRANSACTION t where t.transactiontypecode=3
        ) where ddate>='".$this->fromdate."'
         and ddate<='".$this->todate."'
        group by ddate order by Sr_no
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
       
        $this->setfieldwidth(25,5);
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox($group_row_1['DDATE'],$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['P_S_30'],$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['P_M_30'],$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
       
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['P_SS_30'],$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['TOTAL_PRO'],$this->w,$this->x,'S','R',1,'verdana',9,'','','','');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['SF_S30'],$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
       
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['SF_M30'],$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['SF_SS30'],$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['TOTAL_FREE_SALE'],$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(13);
        $this->textbox($group_row_1['EXPORTQTY'],$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
       
        $this->setfieldwidth(16);
        $this->textbox($group_row_1['GRAND_SALE_TOTAL'],$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['INN_S_30'].'-'.$group_row_1['INN_S_30_LOTNUMBET'],$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['INN_M_30'].'-'.$group_row_1['INN_M_30_LOTNUMBET'],$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
       
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['INN_SS_30'].'-'.$group_row_1['INN_SS_30_LOTNUMBET'],$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(13);
        $this->textbox($group_row_1['TOTAL_INWORD'],$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['OUT_S_30'].'-'.$group_row_1['OUT_S_30_LOTNUMBET'],$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
       
        $this->setfieldwidth(15);
        $this->textbox($group_row_1['OUT_M_30'].'-'.$group_row_1['OUT_M_30_LOTNUMBET'],$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($group_row_1['OUT_SS_30'].'-'.$group_row_1['OUT_SS_30_LOTNUMBET'],$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
       
        $this->setfieldwidth(13);
        $this->textbox($group_row_1['TOTAL_OUTWORD'],$this->w,$this->x,'S','R',1,'verdana',9);
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->P_s_30["total"]+=$group_row_1['P_S_30'];   
        $this->P_m_30["total"]+=$group_row_1['P_M_30'];   
        $this->P_Ss_30["total"]+=$group_row_1['P_SS_30'];   
        $this->total_pro["total"]+=$group_row_1['TOTAL_PRO'];

        $this->sf_s30["total"]+=$group_row_1['SF_S30'];   
        $this->sf_m30["total"]+=$group_row_1['SF_M30'];   
        $this->sf_ss30["total"]+=$group_row_1['SF_SS30'];   
        $this->total_free_sale["total"]+=$group_row_1['TOTAL_FREE_SALE']; 

        $this->exportqty["total"]+=$group_row_1['EXPORTQTY'];  
        $this->grand_sale_total["total"]+=$group_row_1['GRAND_SALE_TOTAL'];  

        $this->inn_s_30["total"]+=$group_row_1['INN_S_30'];  
        $this->inn_m_30["total"]+=$group_row_1['INN_M_30'];   
        $this->inn_ss_30["total"]+=$group_row_1['INN_SS_30'];   
        $this->total_inword["total"]+=$group_row_1['TOTAL_INWORD'];   
        $this->out_s_30["total"]+=$group_row_1['OUT_S_30'];   
        $this->out_m_30["total"]+=$group_row_1['OUT_M_30'];   
        $this->out_ss_30["total"]+=$group_row_1['OUT_SS_30'];   
        $this->total_outword["total"]+=$group_row_1['TOTAL_OUTWORD']; 
        if ($this->isnewpage(110))
        {
            $this->newrow();
            $this->hline(5,295,$this->liney,'C'); 
            $this->newpage(True);
         
        }   
        else
        {           
            $this->newrow();
            $this->hline(5,295,$this->liney,'C'); 
        }
    }
    
    function groupfooter_1(&$group_row_1)
    { 
    }
    function groupfooter_2(&$group_row_1)
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
        $this->setfieldwidth(25,5);
        $this->vline($this->liney-1,$this->liney+7,$this->x);
        $this->textbox('Grand Total',$this->w,$this->x,'S','R',1,'verdana',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox( $this->P_s_30["total"],$this->w,$this->x,'S','R',1,'verdana',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(15);
        $this->textbox( $this->P_m_30["total"],$this->w,$this->x,'S','R',1,'verdana',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
       
        $this->setfieldwidth(15);
        $this->textbox($this->P_Ss_30["total"],$this->w,$this->x,'S','R',1,'verdana',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($this->total_pro["total"],$this->w,$this->x,'S','R',1,'verdana',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(15);
        $this->textbox($this->sf_s30["total"],$this->w,$this->x,'S','R',1,'verdana',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
       
        $this->setfieldwidth(15);
        $this->textbox($this->sf_m30["total"],$this->w,$this->x,'S','R',1,'verdana',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($this->sf_ss30["total"],$this->w,$this->x,'S','R',1,'verdana',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox( $this->total_free_sale["total"],$this->w,$this->x,'S','R',1,'verdana',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(13);
        $this->textbox($this->exportqty["total"],$this->w,$this->x,'S','R',1,'verdana',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
       
        $this->setfieldwidth(16);
        $this->textbox($this->grand_sale_total["total"],$this->w,$this->x,'S','R',1,'verdana',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox( $this->inn_s_30["total"],$this->w,$this->x,'S','R',1,'verdana',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(15);
        $this->textbox( $this->inn_m_30["total"],$this->w,$this->x,'S','R',1,'verdana',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
       
        $this->setfieldwidth(15);
        $this->textbox( $this->inn_ss_30["total"],$this->w,$this->x,'S','R',1,'verdana',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(13);
        $this->textbox($this->total_inword["total"],$this->w,$this->x,'S','R',1,'verdana',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        
        $this->setfieldwidth(15);
        $this->textbox($this->out_s_30["total"],$this->w,$this->x,'S','R',1,'verdana',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
       
        $this->setfieldwidth(15);
        $this->textbox($this->out_m_30["total"],$this->w,$this->x,'S','R',1,'verdana',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);

        $this->setfieldwidth(15);
        $this->textbox($this->out_ss_30["total"],$this->w,$this->x,'S','R',1,'verdana',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
       
        $this->setfieldwidth(13);
        $this->textbox($this->total_outword["total"],$this->w,$this->x,'S','R',1,'verdana',9,'','','','B');
        $this->vline($this->liney-1,$this->liney+7,$this->x+$this->w);
        
        $this->hline(5,295,$this->liney+7,'C');  
    }

}    
?>
