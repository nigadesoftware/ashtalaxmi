<?php
    include_once("../api_oracle/contract_db_oracle.php");
	//include_once("../api_oracle/area_db_oracle.php");
	include_once("../api_oracle/contract_db_oracle.php");
	//include_once("../api_oracle/area_db_oracle.php");
	include_once("../api_oracle/contract_db_oracle.php");
	//include_once("../api_oracle/area_db_oracle.php");
	include_once("../api_oracle/contracttransportdetail_db_oracle.php");
	include_once("../api_oracle/contractharvestdetail_db_oracle.php");
	include_once("../api_oracle/contracttransporttrailerdetail_db_oracle.php");
	include_once("../api_oracle/contractguarantordetail_db_oracle.php");
	include_once("../api_oracle/servicecontractor_db_oracle.php");
	include_once("../api_oracle/contractperformancedetail_db_oracle.php");
    include_once("../api_oracle/contractnomineedetail_db_oracle.php");
    
    
class contract_4
{	
	public $contractid;
    public $connection;
    
	public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

	function printpageheader(&$pdf,&$liney,$contractid)
    {
        $contract1 = new contract($this->connection);

		if ($contract1->fetch($contractid))
		{
			$pdf->SetFont('siddhanta', '', 11, '', true);
            $curdate = date('d/m/Y');
			$pdf->multicell(50,10,'दिनांक:'.$curdate,0,'L',false,1,160,$liney,true,0,false,true,10);
			$liney = $liney+7;
            $pdf->multicell(70,10,'मे. कार्यकारी संचालक साहेब',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+7;
            $pdf->multicell(150,10,'नाशिक सहकारी साखर कारखाना लि., पळसे (श्री संत जनार्दन स्वामी नगर) ता. नाशिक जि. नाशिक',0,'L',false,1,15,$liney,true,0,false,true,10);
			$liney = $liney+7;
			$contracttransportdetail1 = new contracttransportdetail($this->connection);
			$contracttransportdetail1 = $this->contracttransportdetail($this->connection,$contract1->contractid);
			$servicecontractor1 = new servicecontractor($this->connection);
			$servicecontractor1->fetch($contract1->servicecontractorid);
            
            $pdf->multicell(100,10,'मी: '.$servicecontractor1->name_unicode,0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(25,10,'वय: '.$contract1->age,0,'L',false,1,150,$liney,true,0,false,true,10);
            $liney = $liney+5;
            $pdf->line(20,$liney,130,$liney);
            $pdf->line(150,$liney,200,$liney);
            $liney = $liney+2;
            /* $area1 = new area($this->connection);
			$area1->fetch($contract1->areaid); */
            $pdf->multicell(10,10,'धंदा:',0,'L',false,1,15,$liney,true,0,false,true,10);
			$pdf->multicell(30,10,'शेती व ठेकेदारी',0,'L',false,1,25,$liney,true,0,false,true,10);
            
            $pdf->multicell(15,10,'मु.पो.:',0,'L',false,1,70,$liney,true,0,false,true,10);
			$pdf->multicell(120,10,$contract1->address,0,'L',false,1,85,$liney,true,0,false,true,10);

			/* $pdf->multicell(10,10,'ता.:',0,'L',false,1,105,$liney,true,0,false,true,10);
			$pdf->multicell(30,10,$area1->subdistrictname_unicode,0,'L',false,1,115,$liney,true,0,false,true,10);

			$pdf->multicell(10,10,'जि.:',0,'L',false,1,150,$liney,true,0,false,true,10);
			$pdf->multicell(40,10,$area1->districtname_unicode,0,'L',false,1,160,$liney,true,0,false,true,10);
 */
   			$liney = $liney+5;
			$pdf->line(23,$liney,65,$liney);
			$pdf->line(85,$liney,200,$liney);
            //$liney = $liney+7;
            $contractnomineedetail1 = new contractnomineedetail($this->connection);
			$contractnomineedetail1 = $this->contractnomineedetail($this->connection,$contract1->contractid,1);
            $pdf->SetFont('siddhanta', '', 11, '', true);
            $html = '<span style="text-align:justify;"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            विनंती अर्ज करतो कि,<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;मी आपल्या या कारखान्याचा नाममात्र सभासद होऊ इच्छितो त्याकरिता
             मी नाममात्र सभासद फी रु १००/- भरले आहेत. कारखान्याचे सर्व पोटनियम वाचले / वाचून 
             घेतले असून ते मी समजून घेतलेले आहेत. ते मला मान्य आहेत व ते माझ्यावर 
             बंधनकारक राहतील, नाममात्र सभासद या नात्याने कारखान्याचे भागीदारी भागावरील
              डिव्हीडंड तसेच कारखान्याची एकंदर व्यवस्था यात माझा कोणत्याही प्रकारचा हक्क नाही.
               व मला मतदानाचा अधिकार नाही हे मला मान्य आहे तरी मला कारखान्याचे नाममात्र 
               सभासद करून घेण्यात यावे अशी विनंती आहे.
               <br><br>
               मे. कळावे,       तारीख 
               </p></span>';
            $pdf->writeHTML($html, true, 0, true, true);
            $liney = $liney+60;
			$pdf->multicell(100,10,'अर्जदाराची सही',0,'L',false,1,100,$liney,true,0,false,true,10);
			$liney = $liney+5;
            $pdf->line(17,$liney,80,$liney);
			$pdf->line(125,$liney,200,$liney);
			$liney = $liney+15;
			
            $pdf->line(30,$liney,200,$liney);
            $liney = $liney+2;
            // set UTF-8 Unicode font
			$pdf->SetFont('siddhanta', '', 11);
            $html = '<span style="text-align:justify;">मा.संचालक मंडळ सभा तारीख _________
            रोजी भरलेले सभेतील ठराव नं            नुसार अर्जदारास सभासद नाममात्र करुन घेतले आहे व त्याची
            नोंद नाममात्र सभासदाचे रजिस्टर मध्ये राजिस्टरने केली आहे.
			</span>';
            // output the HTML content
			$pdf->writeHTML($html, true, 0, true, true);
			$liney = $liney+30;
			$pdf->multicell(100,10,'आपला विश्वासू',0,'L',false,1,135,$liney,true,0,false,true,10);
            $liney = $liney+14;
            $pdf->multicell(100,10,'कार्यकारी संचालक',0,'L',false,1,135,$liney,true,0,false,true,10);
			$liney = $liney+7;
			$pdf->multicell(100,10,'नाशिक सहकारी साखर कारखाना लि., पळसे',0,'L',false,1,115,$liney,true,0,false,true,10);
		}
    }

    function contracttransportdetail(&$connection,$contractid)
    {
        $contracttransportdetail1 = new contracttransportdetail($connection);
        $query = "select d.contracttransportdetailid from contract c,contracttransportdetail d where c.active=1 and d.active=1 and c.contractid=d.contractid and c.contractid=".$contractid;
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $contracttransportdetail1->fetch($row['CONTRACTTRANSPORTDETAILID']);
            return $contracttransportdetail1;
        }
    }

    function contractharvestdetail(&$connection,$contractid)
    {
        $contractharvestdetail1 = new contractharvestdetail($connection);
        $query = "select d.contractharvestdetailid from contract c,contractharvestdetail d where c.active=1 and d.active=1 and c.contractid=d.contractid and c.contractid=".$contractid;
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $contractharvestdetail1->fetch($row['CONTRACTHARVESTDETAILID']);
            return $contractharvestdetail1;
        }
    }

    function contracttransporttrailerdetail(&$connection,$contracttransportdetailid,$sequencenumber)
    {
        $contracttransporttrailerdetail1 = new contracttransporttrailerdetail($connection);
        $query = "select t.contracttransporttrailerdetailid from contract c,contracttransportdetail d, contracttransporttrailerdetail t where c.active=1 and d.active=1 and t.active=1 and c.contractid=d.contractid and d.contracttransportdetailid=t.contracttransportdetailid and t.contracttransportdetailid=".$contracttransportdetailid;
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contracttransporttrailerdetail1->fetch($row['CONTRACTTRANSPORTTRAILERDETAILID']);
                return $contracttransporttrailerdetail1;
                exit;
            }
            else
            {
                $i++;	
            }
        }
    }

    function contractperformancedetail(&$connection,$contractid)
    {
        $contractperformancedetail1 = new contractperformancedetail($connection);
        $query = "select d.contractperformancedetailid from contract c,contractperformancedetail d where c.active=1 and d.active=1 and c.contractid=d.contractid and c.contractid=".$contractid;
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            $contractperformancedetail1->fetch($row['CONTRACTPERFORMANCEDETAILID']);
            return $contractperformancedetail1;
        }
    }

    function contractguarantordetail(&$connection,$contractid,$sequencenumber)
    {
        $contractguarantordetail1 = new contractguarantordetail($connection);
        $query = "select d.contractguarantordetailid from contract c,contractguarantordetail d,servicecontractor t where c.active=1 and d.active=1 and t.active=1 and c.contractid=d.contractid and d.servicecontractorid=t.servicecontractorid and c.contractid=".$contractid." order by t.servicecontractorcategoryid desc,d.contractguarantordetailid";
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contractguarantordetail1->fetch($row['CONTRACTGUARANTORDETAILID']);
                return $contractguarantordetail1;
            }
            else
            {
                $i++;
            }
        }
    }

    function contractnomineedetail(&$connection,$contractid,$sequencenumber)
    {
        $contractnomineedetail1 = new contractnomineedetail($connection);
        $query = "select d.contractnomineedetailid from contract c,contractnomineedetail d where c.active=1 and d.active=1 and c.contractid=d.contractid and c.contractid=".$contractid." order by d.contractnomineedetailid";
        $result = oci_parse($this->connection, $query);             $r = oci_execute($result);
        $i=1;
        while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            if ($i==$sequencenumber)
            {
                $contractnomineedetail1->fetch($row['CONTRACTNOMINEEDETAILID']);
                return $contractnomineedetail1;
            }
            else
            {
                $i++;
            }
        }
    }


   
}
?>