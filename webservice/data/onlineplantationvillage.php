<?php
/* 
A domain Class to demonstrate RESTful web services
*/
Class onlineplantation
{
	public $connection;
	public $seasonyear;
	public $sectioncode;
	public $villagecode;
    public $offset;
    public $rowsperpage;

	public function __construct(&$connection)
	{
		$this->connection = $connection;
	}

	public function getonlineplantationrecord()
	{
		$query = "select * from 
		(select t.nplot_no,dplantation_date,row_number() over (order by dplantation_date,t.nplot_no) as rn,
		t.nfarmer_code,vfarmer_name_uni,narea,
		r.navariety_code,vvariety_name,h.nhangam_code,vhangam_name_uni,
		vgat_sarve_no,'0' as vsarve_no_detail,
		s.vsect_name_uni,v.vvill_name_uni,
		TO_CHAR(t.dcreate_date, 'DD/MM/YYYY HH24:MI:SS') as plottime
		from agri_plantation@agrilink t,com_farmer_master f,
		com_village_master v,com_section_master s,
		agri_variety_master@agrilink r,com_hangam_master h
    	where t.nfarmer_code=f.nfarmer_code 
		and t.nvill_shivar_code=v.nvill_code and v.nsect_code=s.nsect_code 
		and t.nvariety_code=r.navariety_code
		and t.nhangam_code=h.nhangam_code
		and t.vseason_year='".$this->seasonyear."' 
		and v.nvill_code=".$this->villagecode."
		)
        where rn >".$this->offset." 
		and rn <=".($this->offset+$this->rowsperpage);
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		while ($row = oci_fetch_array($result,OCI_ASSOC))
		{
			$data[] = $row;
		}
		return $data;
	}
	public function gettotalrecords()
	{
		$query = "select v.vvill_name_uni,count(*) as cnt  
        from agri_plantation@agrilink t,com_farmer_master f,
		com_village_master v,com_section_master s,
		agri_variety_master@agrilink r,com_hangam_master h
    	where t.nfarmer_code=f.nfarmer_code 
		and t.nvill_shivar_code=v.nvill_code and v.nsect_code=s.nsect_code 
		and t.nvariety_code=r.navariety_code
		and t.nhangam_code=h.nhangam_code 
		and t.vseason_year='".$this->seasonyear."' 
		and v.nvill_code=".$this->villagecode." group by v.vvill_name_uni";
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		if ($row = oci_fetch_array($result,OCI_ASSOC))
		{
			$data[] = $row;
		}
		return $data;
	}
	
	public function getsectvillhanrecord()
	{
		$query = "select * from (select nvill_code,vvill_name_uni,
		sum(case when nhangam_code=1 then narea else 0 end) as aadsali,
		sum(case when nhangam_code=3 then narea else 0 end) as purva,
		sum(case when nhangam_code=2 then narea else 0 end) as suru,
		sum(case when nhangam_code=4 then narea else 0 end) as khodava,
		sum(narea) as total,
		sum(cnt) as cnt  
		from (
		select t.vseason_year,s.nsect_code,s.vsect_name_uni,v.nvill_code,v.vvill_name_uni,h.nhangam_code,h.vhangam_name_uni,sum(narea) as narea,count(*) as cnt
		from agri_plantation@agrilink t,com_farmer_master f,
		com_village_master v,com_village_master s,
		agri_variety_master@agrilink r,com_hangam_master h
		  where t.nfarmer_code=f.nfarmer_code 
		and t.nvill_shivar_code=v.nvill_code and v.nsect_code=s.nsect_code 
		and t.nvariety_code=r.navariety_code
		and t.nhangam_code=h.nhangam_code
		group by t.vseason_year,s.nsect_code,s.vsect_name_uni,v.nvill_code,v.vvill_name_uni,h.nhangam_code,vhangam_name_uni
		having t.vseason_year='".$this->seasonyear."'
		and s.nvill_code=".$this->villagecode.")
		group by nvill_code,vvill_name_uni)
		order by nvill_code";
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		while ($row = oci_fetch_array($result,OCI_ASSOC))
		{
			$data[] = $row;
		}
		return $data;
	}
	
	public function getsecthanrecord()
	{
		$query = "select * from (select nsect_code,vsect_name_uni,
		sum(case when nhangam_code=1 then narea else 0 end) as aadsali,
		sum(case when nhangam_code=3 then narea else 0 end) as purva,
		sum(case when nhangam_code=2 then narea else 0 end) as suru,
		sum(case when nhangam_code=4 then narea else 0 end) as khodava,
		sum(narea) as total,
		sum(cnt) as cnt  
		from (
		select t.vseason_year,s.nsect_code,s.vsect_name_uni,h.nhangam_code,h.vhangam_name_uni,sum(narea) as narea,count(*) as cnt
		from agri_plantation@agrilink t,com_farmer_master f,
		com_village_master v,com_village_master s,
		agri_variety_master@agrilink r,com_hangam_master h
		  where t.nfarmer_code=f.nfarmer_code 
		and t.nvill_shivar_code=v.nvill_code and v.nsect_code=s.nsect_code 
		and t.nvariety_code=r.navariety_code
		and t.nhangam_code=h.nhangam_code
		group by t.vseason_year,s.nsect_code,s.vsect_name_uni,h.nhangam_code,vhangam_name_uni
		having t.vseason_year='".$this->seasonyear."')
		group by nsect_code,vsect_name_uni)
		order by nsect_code";
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		while ($row = oci_fetch_array($result,OCI_ASSOC))
		{
			$data[] = $row;
		}
		return $data;
	}

	public function getvillagelist()
	{
		$query = "select nvill_code,vvill_name_uni  
		from com_village_master s order by nsect_code,nvill_code";
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		while ($row = oci_fetch_array($result,OCI_ASSOC))
		{
			$data[] = $row;
		}
		return $data;
	}
	public function getvillagebysectionlist()
	{
		$query = "select nvill_code,vvill_name_uni  
		from com_village_master s where nsect_code=".$this->sectioncode." order by nsect_code,nvill_code";
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		while ($row = oci_fetch_array($result,OCI_ASSOC))
		{
			$data[] = $row;
		}
		return $data;
	}
	public function getseasonyearlist()
	{
		$query = "select vyear_code  
		from com_year_master s where dseason_star_date>='01-oct-2017' order by dseason_star_date desc";
		$result = oci_parse($this->connection, $query);
		$r = oci_execute($result);
		$data = array();
		while ($row = oci_fetch_array($result,OCI_ASSOC))
		{
			$data[] = $row;
		}
		return $data;
	}
}
?>