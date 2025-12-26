<?php
/* 
A domain Class to demonstrate RESTful web services
*/
Class todslipforfieldslip
{
	public $connection;
    public $slipboyuserid;
	public function __construct(&$connection)
	{
        $this->connection = $connection;
		$this->slipboyuserid=0;
	}

	public function getpendingtodslip()
	{
        $query = "select t.*,p.gutnumber as gatsurveno,f.farmernameuni,v.villagenameuni,s.subvillagenameuni,h.vehiclenumber
        ,tr.subcontractornameuni as transporternameuni,hr.subcontractornameuni as harvesternameuni,hrtr.subcontractornameuni as harvestertransporternameuni
        ,(select  mod(nvl(max(tt.fieldslipnumber),0),1000) as lastserialnumber
        from fieldslip tt 
        where tt.seasoncode=t.seasoncode and tt.todslipnumber=t.todslipnumber) as lastserialnumber
        from todslip t,plantationheader p,farmer f, village v,subvillage s,vehicle h,subcontractor tr,subcontractor hr,subcontractor hrtr
        where t.seasoncode=p.seasoncode
        and t.plotnumber=p.plotnumber 
        and t.farmercode=f.farmercode
        and t.villagecode=v.villagecode
        and t.subvillagecode=s.subvillagecode(+)
        and t.seasoncode=h.seasoncode(+)
        and t.vehiclecode=h.vehiclecode(+)
        and t.seasoncode=tr.seasoncode(+)
        and t.trsubcontractorcode=tr.subcontractorcode(+)
        and t.seasoncode=hr.seasoncode(+)
        and t.hrsubcontractorcode=hr.subcontractorcode(+)
        and t.seasoncode=hrtr.seasoncode(+)
        and t.hrtrsubcontractorcode=hrtr.subcontractorcode(+)
        and t.slipboycode=".$this->slipboyuserid."
        and t.isharvestingcompleted is null 
		order by t.seasoncode,t.todslipnumber";
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
            $row_array = array();
            $row_array['TRANSACTIONNUMBER'] = $row['TRANSACTIONNUMBER'];
            $row_array['SEASONCODE'] = $row['SEASONCODE'];
            $row_array['TODSLIPNUMBER'] = $row['TODSLIPNUMBER'];
            $row_array['TODSLIPDATE'] = $row['TODSLIPDATE'];
            $row_array['PLOTNUMBER'] = $row['PLOTNUMBER'];
            $row_array['FARMERCATEGORYCODE'] = $row['FARMERCATEGORYCODE'];
            $row_array['FARMERCODE'] = $row['FARMERCODE'];
            $row_array['VILLAGECODE'] = $row['VILLAGECODE'];
            $row_array['SUBVILLAGECODE'] = $row['SUBVILLAGECODE'];
            $row_array['VEHICLECATEGORYCODE'] = $row['VEHICLECATEGORYCODE'];
            $row_array['HRSUBCONTRACTORCODE'] = $row['HRSUBCONTRACTORCODE'];
            $row_array['HRTRSUBCONTRACTORCODE'] = $row['HRTRSUBCONTRACTORCODE'];
            $row_array['TRSUBCONTRACTORCODE'] = $row['TRSUBCONTRACTORCODE'];
            $row_array['CANECONDITIONCODE'] = $row['CANECONDITIONCODE'];
            $row_array['SLIPBOYCODE'] = $row['SLIPBOYCODE'];
            $row_array['DISTANCE'] = $row['DISTANCE'];
            $row_array['VIADISTANCE'] = $row['VIADISTANCE'];
            $row_array['ISHARVESTINGCOMPLETED'] = $row['ISHARVESTINGCOMPLETED'];
            $row_array['GATSURVENO'] = $row['GATSURVENO'];
            $row_array['FARMERNAMEUNI'] = $row['FARMERNAMEUNI'];
            $row_array['VILLAGENAMEUNI'] = $row['VILLAGENAMEUNI'];
            $row_array['SUBVILLAGENAMEUNI'] = $row['SUBVILLAGENAMEUNI'];
            $row_array['VEHICLENUMBER'] = $row['VEHICLENUMBER'];
            $row_array['TRANSPORTERNAMEUNI'] = $row['TRANSPORTERNAMEUNI'];
            $row_array['HARVESTERNAMEUNI'] = $row['HARVESTERNAMEUNI'];
            $row_array['HARVESTERTRANSPORTERNAMEUNI'] = $row['HARVESTERTRANSPORTERNAMEUNI'];
            $row_array['LASTSERIALNUMBER'] = $row['LASTSERIALNUMBER'];
            $row_array['VEHICLES'] = array();
            $query1="select t.seasoncode,t.todslipnumber,t.vehiclecategorycode,t.vehiclecode,null as tyregadicode,v.vehiclenumber,null as gadiwannameuni,null as tyregadinumber
            from todslip t,vehicle v
            where t.seasoncode=v.seasoncode and t.vehiclecode=v.vehiclecode 
            and  t.seasoncode=".$row['SEASONCODE']." 
            and  t.todslipnumber=".$row['TODSLIPNUMBER']." 
            and t.vehiclecategorycode not in (3,4)
            union all
            select t.seasoncode,t.todslipnumber,t.vehiclecategorycode,v.vehiclecode,null as tyregadicode,v.vehiclenumber,null as gadiwannameuni,null as tyregadinumber
            from todslip t,vehicle v
            where t.seasoncode=v.seasoncode and t.hrtrsubcontractorcode=v.subcontractorcode 
            and  t.seasoncode=".$row['SEASONCODE']." 
            and  t.todslipnumber=".$row['TODSLIPNUMBER']." 
            and t.vehiclecategorycode in (4)
            union all
            select t.seasoncode,t.todslipnumber,t.vehiclecategorycode
            ,null as vehiclecode,y.tyregadicode,null as vehiclenumber,y.gadiwannameuni,y.tyregadinumber
            from todslip t,tyregadi y 
            where t.seasoncode=y.seasoncode and t.hrtrsubcontractorcode=y.subcontractorcode 
            and  t.seasoncode=".$row['SEASONCODE']." 
            and  t.todslipnumber=".$row['TODSLIPNUMBER']." 
            and t.vehiclecategorycode in (3)";
            $result1 = oci_parse($this->connection, $query1);
            $r1 = oci_execute($result1);
            while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
            {
                $row_array['VEHICLES'][] = array(
                    'SEASONCODE' => $row1['SEASONCODE'],
                    'TODSLIPNUMBER' => $row1['TODSLIPNUMBER'],
                    'VEHICLECATEGORYCODE' => $row1['VEHICLECATEGORYCODE'],
                    'VEHICLECODE' => $row1['VEHICLECODE'],
                    'TYREGADICODE' => $row1['TYREGADICODE'],
                    'VEHICLENUMBER' => $row1['VEHICLENUMBER'],
                    'GADIWANNAMEUNI' => $row1['GADIWANNAMEUNI'],
                    'TYREGADINUMBER' => $row1['TYREGADINUMBER']
                );
            }
            array_push($data, $row_array); //push the values in the array
            //$data[] = $row;
		}
		return $data;
    }
    public function updatetodslip($seasoncode,$todslipnumber)
    {
        $query="update todslip t 
        set isharvestingcompleted=1
        where t.seasoncode={$seasoncode}
        and t.todslipnumber={$todslipnumber}";
        $result = oci_parse($this->connection, $query);
        if (oci_execute($result,OCI_NO_AUTO_COMMIT))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
}
?>