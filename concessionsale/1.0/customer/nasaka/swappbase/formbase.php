<?php
require_once("../swappbase/operationmode.php");
class swappform
{
	public $connection;
	protected $invalidid;
	public $invalidmessagetext;
	protected $dataoperationmode;
	private $beingvalidation;
	public  $found;

	public function __construct(&$connection)
	{
		$this->connection = $connection;
		$this->invalidid=0;
		$this->invalidmessagetext='No Validation';
		$this->beingvalidation=false;
		$this->found=false;
	}

	protected function start_validation()
	{
		$this->beingvalidation=true;
		$this->invalidid=0;
	}
	protected function end_validation()
	{
		$this->beingvalidation=false;
	}

	public function Get_invalidid()
	{
		return $this->invalidid;
	}

	public function Get_invalidmessagetext()
	{
		return $this->invalidmessagetext;
	}
	
	protected function checkrequired($data,$datalabel,$datalabeluni='')
	{
		if ($this->beingvalidation==true)
		{
			if ($data == '' or $data == '0')
			{
				$this->invalidid=-2;
				if ($_SESSION['lng']=="English")
				{
					$this->invalidmessagetext=$datalabel.' is required to enter';
				}
				else
				{
					if ($datalabeluni=='')
					{
						$datalabeluni=$datalabel;
					}
					$this->invalidmessagetext=$datalabeluni.' माहिती भरणे आवश्यक आहे';
				}
				$this->end_validation();	
				return $this->invalidid;
			}
			else
			{
				$this->invalidid=0;
				$this->invalidmessagetext='';
				return $this->invalidid;
			}
		}
	}
	protected function isdate($data,$datalabel) 
	{
		if ($this->beingvalidation==true)
		{
			$matches = array();
			$pattern = '/^([0-9]{1,2})\\/([0-9]{1,2})\\/([0-9]{4})$/';
			if (!preg_match($pattern, $data, $matches)) 
			{
				$this->invalidid=-2;
				$this->invalidmessagetext=$datalabel.' is Invalid';
				$this->end_validation();	
				return $this->invalidid;
			}
			if (!checkdate($matches[2], $matches[1], $matches[3])) 
			{
				$this->invalidid=-2;
				$this->invalidmessagetext=$datalabel.' is Invalid';
				$this->end_validation();	
				return $this->invalidid;
			}
			else
			{
				$this->invalidid=0;
				$this->invalidmessagetext='';
				return $this->invalidid;
			}
		}
    }
	protected function englishtextonly($data,$datalabel)
	{
		if ($this->beingvalidation==true)
		{
			if (!preg_match('/^[a-z A-Z]*$/', $data))
			{
				$this->invalidid=-3;
				$this->invalidmessagetext='Invalid English text in '.$datalabel;
				$this->end_validation();
				return $this->invalidid;
			}
			else
			{
				$this->invalidid=0;
				$this->invalidmessagetext='';
				return $this->invalidid;
			}
		}
		return $this->invalidid;
	}

	protected function unicodedevanagaritextonly($data,$datalabel)
	{
		if ($this->beingvalidation==true)
		{
			if (!preg_match('/^[\p{Devanagari}\s]+$/u', $data))
			{
				$this->invalidid=-4;
				$this->invalidmessagetext='Invalid Unicode Devanagari text in '.$datalabel;
				$this->end_validation();
				return $this->invalidid;
			}
			else
			{
				$this->invalidid=0;
				$this->invalidmessagetext='';
				return $this->invalidid;
			}
		}
	}

	protected function englishtextdigitonly($data,$datalabel)
	{
		if ($this->beingvalidation==true)
		{
			if (!preg_match('/^[a-z A-Z0-9]*$/', $data))
			{
				$this->invalidid=-3;
				$this->invalidmessagetext='Invalid English text in '.$datalabel;
				$this->end_validation();
				return $this->invalidid;
			}
			else
			{
				$this->invalidid=0;
				$this->invalidmessagetext='';
				return $this->invalidid;
			}
		}
		return $this->invalidid;
	}

	protected function englishtextdigitspecialonly($data,$datalabel)
	{
		if ($this->beingvalidation==true)
		{
			if (!preg_match('/^[a-z A-Z0-9._,\-]*$/', $data))
			{
				$this->invalidid=-3;
				$this->invalidmessagetext='Invalid English text/digit/special character in '.$datalabel;
				$this->end_validation();
				return $this->invalidid;
			}
			else
			{
				$this->invalidid=0;
				$this->invalidmessagetext='';
				return $this->invalidid;
			}
		}
		return $this->invalidid;
	}
	protected function englishtextpecialonly($data,$datalabel)
	{
		if ($this->beingvalidation==true)
		{
			if (!preg_match('/^[a-z A-Z(\(\))]*$/', $data))
			{
				$this->invalidid=-3;
				$this->invalidmessagetext='Invalid English text/digit/special character in '.$datalabel;
				$this->end_validation();
				return $this->invalidid;
			}
			else
			{
				$this->invalidid=0;
				$this->invalidmessagetext='';
				return $this->invalidid;
			}
		}
		return $this->invalidid;
	}
	protected function unicodedevanagaritextdigitonly($data,$datalabel)
	{
		if ($this->beingvalidation==true)
		{
			if (!preg_match('/^[\p{Devanagari}\p{N}\s]+$/u', $data))
			{
				$this->invalidid=-4;
				$this->invalidmessagetext='Invalid Unicode Devanagari text in '.$datalabel;
				$this->end_validation();
				return $this->invalidid;
			}
			else
			{
				$this->invalidid=0;
				$this->invalidmessagetext='';
				return $this->invalidid;
			}
		}
	}
	protected function unicodedevanagaritextspecialonly($data,$datalabel)
	{
		if ($this->beingvalidation==true)
		{
			if (!preg_match('/^[\p{Devanagari}\p{Ps}\p{Pe}\s]+$/u', $data))
			{
				$this->invalidid=-4;
				$this->invalidmessagetext='Invalid Unicode Devanagari text in '.$datalabel;
				$this->end_validation();
				return $this->invalidid;
			}
			else
			{
				$this->invalidid=0;
				$this->invalidmessagetext='';
				return $this->invalidid;
			}
		}
	}
	protected function checkfixedlength($data,$datalabel,$datalength)
	{
		if ($this->beingvalidation==true)
		{
			if (strlen($data)!=$datalength)
			{
				$this->invalidid=-3;
				$this->invalidmessagetext='Invalid Data in '.$datalabel;
				$this->end_validation();
				return $this->invalidid;
			}
			else
			{
				$this->invalidid=0;
				$this->invalidmessagetext='';
				return $this->invalidid;
			}
		}
		return $this->invalidid;
	}
	protected function checkemail($email)
{
    // First, we check that there's one @ symbol, and that the lengths are right
    if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) 
	{
        // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
        $this->invalidid=-301;
		$this->invalidmessagetext='Invalid E-Mail Id';
		return $this->invalidid;;
    }
    // Split it into sections to make life easier
    $email_array = explode("@", $email);
    $local_array = explode(".", $email_array[0]);
    for ($i = 0; $i < sizeof($local_array); $i++) 
	{
        if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
            $this->invalidid=-301;
			$this->invalidmessagetext='Invalid E-Mail Id';
			return $this->invalidid;;
        }
    }
    if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) 
	{ // Check if domain is IP. If not, it should be valid domain name
        $domain_array = explode(".", $email_array[1]);
        if (sizeof($domain_array) < 2) {
            $this->invalidid=-301;
			$this->invalidmessagetext='Invalid E-Mail Id';
			return $this->invalidid; // Not enough parts to domain
        }
        for ($i = 0; $i < sizeof($domain_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                $this->invalidid=-301;
				$this->invalidmessagetext='Invalid E-Mail Id';
				return $this->invalidid;
            }
        }
    }

    $this->invalidid=0;
	$this->invalidmessagetext='';
	return $this->invalidid;
}
protected function invl($data,$isnumber=true,$makezeronull=false,$relopr="")
{
	if (isset($relopr) and $relopr!="")
	{
		$opr=' '.$relopr.' ';
	}
	else
	{
		$opr="";
	}
	if (isset($data) and $data != "" and $makezeronull==false)
	{
		if ($isnumber == true)
		{
			return $opr.$data;
		}
		else
		{
			return $opr."'".$data."'";
		}
	}
	elseif (isset($data) and $data != "" and $makezeronull==true)
	{
		if ($data=='0' or $data==0)
		{
			if (isset($relopr) and $relopr!="")
			{
				return ' is Null';
			}
			else
			{
				return 'Null';
			}
		}
		else
		{
			if ($isnumber == true)
			{
				return $opr.$data;
			}
			else
			{
				return $opr."'".$data."'";
			}
		}
	}
	else
	{
		if (isset($relopr) and $relopr!="")
		{
			return ' is Null';
		}
		else
		{
			return 'Null';
		}
	}
}

protected function isnvl($data)
{
	if (isset($data) and $data != "")
	{
		return false;
	}
	else
	{
		return true;
	}
}
protected function englishdigitonly($data,$datalabel)
	{
		if ($this->beingvalidation==true)
		{
			if (!preg_match('/^[0-9]*$/', $data))
			{
				$this->invalidid=-3;
				$this->invalidmessagetext='Invalid English Digits in '.$datalabel;
				$this->end_validation();
				return $this->invalidid;
			}
			else
			{
				$this->invalidid=0;
				$this->invalidmessagetext='';
				return $this->invalidid;
			}
		}
		return $this->invalidid;
	}
protected function checkmaxdatalength($data,$datalabel,$datalength)
	{
		if ($this->beingvalidation==true)
		{
			if (strlen($data)>$datalength)
			{
				$this->invalidid=-3;
				$this->invalidmessagetext='Violating Max Data Length in '.$datalabel;
				$this->end_validation();
				return $this->invalidid;
			}
			else
			{
				$this->invalidid=0;
				$this->invalidmessagetext='';
				return $this->invalidid;
			}
		}
		return $this->invalidid;
	}

protected function checkmindatalength($data,$datalabel,$datalength)
	{
		if ($this->beingvalidation==true)
		{
			if (strlen($data)<$datalength)
			{
				$this->invalidid=-3;
				$this->invalidmessagetext='Violating Min Data Length in '.$datalabel;
				$this->end_validation();
				return $this->invalidid;
			}
			else
			{
				$this->invalidid=0;
				$this->invalidmessagetext='';
				return $this->invalidid;
			}
		}
		return $this->invalidid;
	}
	public function listcombo($fieldname,&$connection,$sourcetable,$combocodefield,$codevalue,$nameeng,$nameuni,$englishlabel,$unicodelabel,$width=300,$height=30,$iscompulsory=False,$isreadonly=False,$found=false,$isautofocus=false)
    {
		$this->label($fieldname,$englishlabel,$unicodelabel);
		echo '<tr>';
		$query1 = "select {$combocodefield} as code,{$nameeng} as name_eng,{$nameuni} as name_unicode 
		from {$sourcetable}" ;
		$result1 = oci_parse($connection, $query1);
		$r = oci_execute($result1);
		echo '<tr>';
		if ($isautofocus==true)
		{
			if ($isreadonly==true)
			{
				echo '<td><select name="'.$fieldname.'" id="'.$fieldname.'" style="height:'.$height.'px;width:'.$width.'px;font-size:12px;">';
			}
			else
			{
				echo '<td><select name="'.$fieldname.'" id="'.$fieldname.'" style="height:'.$height.'px;width:'.$width.'px;font-size:12px;">';
			}
		}
		else
		{
			if ($isreadonly==true)
			{
				echo '<td><select name="'.$fieldname.'" id="'.$fieldname.'" style="height:'.$height.'px;width:'.$width.'px;font-size:12px;">';
			}
			else
			{
				echo '<td><select name="'.$fieldname.'" id="'.$fieldname.'" style="height:'.$height.'px;width:'.$width.'px;font-size:12px;">';
			}
		}
		if ($_SESSION['lng']=="English")
		{
			if ($isreadonly==true)
			echo '<option disabled value="0">[Select]</option>';
			else
			echo '<option value="0">[Select]</option>';
		}
		else
		{
			if ($isreadonly==true)
			echo '<option disabled value="0">[निवडा]</option>';
			else
			echo '<option value="0">[निवडा]</option>';
		}
		while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			if ($_SESSION['lng']=="English")
			{
				if ($found==true and $row1['CODE']==$codevalue)
				{
					echo '<option value="'.$row1['CODE'].'" Selected>'.$row1['NAME_ENG'].'</option>';
				}
				else
				{
					if ($isreadonly==true)
					echo '<option disabled value="'.$row1['CODE'].'">'.$row1['NAME_ENG'].'</option>';
					else
					echo '<option value="'.$row1['CODE'].'">'.$row1['NAME_ENG'].'</option>';
				}
			}
			else
			{
				if ($found==true and $row1['CODE']==$codevalue)
				{
					echo'<option value="'.$row1['CODE'].'" Selected>'.$row1['NAME_UNICODE'].'</option>';
				}
				else
				{
					if ($isreadonly==true)
					echo '<option disabled value="'.$row1['CODE'].'">'.$row1['NAME_UNICODE'].'</option>';
					else
					echo '<option value="'.$row1['CODE'].'">'.$row1['NAME_UNICODE'].'</option>';
				}
			}
		}
		echo '</select>';
		if($iscompulsory==true)
		{
			echo '<td><label for="'.$fieldname.'">*</label></td>';
		}
		echo '</tr>';
    }
    
    public function label($fieldname,$englishlabel,$unicodelabel)
    {
        if ($_SESSION['lng']=="English")
        {
            echo '<tr>';
            echo '<td><label for="'.$fieldname.'" class="thick">'.$englishlabel.'</label></td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td><label for="'.$fieldname.'" class="thick">'.$unicodelabel.'</label></td>';
            echo '</tr>';
        }
	}
    public function textcombobox($fieldname,$combosearchfield,$combocodefield,$englishlabel,$unicodelabel,$value='',$codevalue='',$width=300,$height=150,$iscompulsory=False,$isreadonly=False,$found=false,$flag='',$js='',$isautofocus=false,$searchtextbox='')
    {
		if ($searchtextbox=='')
		{
			$this->textbox($combosearchfield,$englishlabel,$unicodelabel,$value,$width,$height,'left',$iscompulsory,$isreadonly,$found,$flag,$js,$isautofocus,'','','advancedSearchTextbox');
		}
		else
		{
			$this->textbox($searchtextbox,$englishlabel,$unicodelabel,$value,$width,$height,'left',$iscompulsory,$isreadonly,$found,$flag,$js,$isautofocus,'','','advancedSearchTextbox');
		}
        if ($found==false)
        {
            echo '<tr>';
            echo '<td><input type="textbox" readonly="readonly" tabindex ="-1" style="font-size:12pt;height:'.$height.'px;width:'.$width.'px;" name="'.$fieldname.'" id="'.$fieldname.'"'.$js.'></td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td><input type="textbox" readonly="readonly" tabindex ="-1" style="font-size:12pt;height:'.$height.'px;width:'.$width.'px;" name="'.$fieldname.'" id="'.$fieldname.'" value="'.$codevalue.'"'.$js.'></td>';
            echo '</tr>';
        }
    }
    public function textbox($fieldname,$englishlabel,$unicodelabel,$value='',$width=300,$height=150,$align='left',$iscompulsory=False,$isreadonly=False,$found=false,$flag='',$js='',$isautofocus=false,$isplaceholder=false,$placeholder='',$class='')
    {
		$this->label($fieldname,$englishlabel,$unicodelabel);
		if ($isplaceholder==true)
		{
			if ($placeholder=='')
			{
				if ($_SESSION['lng']=="English")
				{
					$ph='Please Enter '.$englishlabel;
				}
				else
				{
					$ph='कृपया भरा '.$unicodelabel;
				}
			}
			else
			{
				$ph=$placeholder;
			}
		}
		if ($class=='')
			$cls='';
		else
			$cls='class="'.$class.'"';
        if ($isreadonly==True and $flag!='Query')
        {
			echo '<tr>';
			echo '<td><input '.$cls.' autocomplete="off" type="text" readonly="readonly" tabindex="-1" style="font-size:12pt;height:'.$height.'px;width:'.$width.'px;'.'text-align:'.$align.';" name="'.$fieldname.'" id="'.$fieldname.'" value="'.$value.'" placeholder="'.$ph.'"></td>';
		}
		elseif ($isreadonly==True and $flag=='Query')
        {
			echo '<tr>';
			if ($isautofocus==true)
			{
				echo '<td><input '.$cls.' autocomplete="off" autofocus type="text" style="font-size:12pt;height:'.$height.'px;width:'.$width.'px;'.'text-align:'.$align.';" name="'.$fieldname.'" id="'.$fieldname.'" value="'.$value.'" placeholder="'.$ph.'"></td>';
			}
			else
			{
				echo '<td><input '.$cls.' autocomplete="off" type="text" style="font-size:12pt;height:'.$height.'px;width:'.$width.'px;'.'text-align:'.$align.';" name="'.$fieldname.'" id="'.$fieldname.'" value="'.$value.'" placeholder="'.$ph.'"></td>';
			}
		}
        else
        {
            if ($js=='')
            {
                echo '<tr>';
				if ($isautofocus==true)
				{
					echo '<td><input '.$cls.' autocomplete="off" autofocus type="text" style="font-size:12pt;height:'.$height.'px;width:'.$width.'px;'.'text-align:'.$align.';" name="'.$fieldname.'" id="'.$fieldname.'" value="'.$value.'" placeholder="'.$ph.'"></td>';
				}
				else
				{
					echo '<td><input '.$cls.' autocomplete="off" type="text" style="font-size:12pt;height:'.$height.'px;width:'.$width.'px;'.'text-align:'.$align.';" name="'.$fieldname.'" id="'.$fieldname.'" value="'.$value.'" placeholder="'.$ph.'"></td>';
				}
            }
            else
            {
                echo '<tr>';
				if ($isautofocus==true)
				{
					echo '<td><input '.$cls.' autocomplete="off" autofocus type="text" style="font-size:12pt;height:'.$height.'px;width:'.$width.'px;'.'text-align:'.$align.';" name="'.$fieldname.'" id="'.$fieldname.'" value="'.$value.'" placeholder="'.$ph.'" '.$js.'></td>';
				}
				else
				{
					echo '<td><input '.$cls.' autocomplete="off" type="text" style="font-size:12pt;height:'.$height.'px;width:'.$width.'px;'.'text-align:'.$align.';" name="'.$fieldname.'" id="'.$fieldname.'" value="'.$value.'" placeholder="'.$ph.'" '.$js.'></td>';
				}
            }
        }
        if ($iscompulsory==True)
        {
            echo '<td><label for="'.$fieldname.'">*</label></td>';
            echo '</tr>';
        }
        else
        {
            echo '</tr>';
        }
    }
	public function booleanlistcombo($fieldname,&$connection,$codevalue,$englishlabel,$unicodelabel,$width=300,$height=30,$iscompulsory=False,$isreadonly=False,$found=false,$isautofocus=false)
    {
		$this->label($fieldname,$englishlabel,$unicodelabel);
		echo '<tr>';
		$query1 = "select 1 as code,'Yes' as name_eng,'होय' as name_unicode from dual
		union all
		select 2 as code,'No' as name_eng,'नाही' as name_unicode from dual" ;
		$result1 = oci_parse($connection, $query1);
		$r = oci_execute($result1);
		echo '<tr>';
		if ($isautofocus==true)
		{
			if ($isreadonly==true)
			{
				echo '<td><select name="'.$fieldname.'" id="'.$fieldname.'" style="height:'.$height.'px;width:'.$width.'px;font-size:12px;">';
			}
			else
			{
				echo '<td><select name="'.$fieldname.'" id="'.$fieldname.'" style="height:'.$height.'px;width:'.$width.'px;font-size:12px;">';
			}
		}
		else
		{
			if ($isreadonly==true)
			{
				echo '<td><select name="'.$fieldname.'" id="'.$fieldname.'" style="height:'.$height.'px;width:'.$width.'px;font-size:12px;">';
			}
			else
			{
				echo '<td><select name="'.$fieldname.'" id="'.$fieldname.'" style="height:'.$height.'px;width:'.$width.'px;font-size:12px;">';
			}
		}
		if ($_SESSION['lng']=="English")
		{
			echo '<option value="0">[Select]</option>';
		}
		else
		{
			echo '<option value="0">[निवडा]</option>';
		}
		while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			if ($_SESSION['lng']=="English")
			{
				if ($found==true and $row1['CODE']==$codevalue)
				{
					echo '<option value="'.$row1['CODE'].'" Selected>'.$row1['NAME_ENG'].'</option>';
				}
				else
				{
					echo '<option value="'.$row1['CODE'].'">'.$row1['NAME_ENG'].'</option>';
				}
			}
			else
			{
				if ($found==true and $row1['CODE']==$codevalue)
				{
					echo'<option value="'.$row1['CODE'].'" Selected>'.$row1['NAME_UNICODE'].'</option>';
				}
				else
				{
					echo '<option value="'.$row1['CODE'].'">'.$row1['NAME_UNICODE'].'</option>';
				}
			}
		}
		echo '</select>';
		if($iscompulsory==true)
		{
			echo '<td><label for="'.$fieldname.'">*</label></td>';
		}
		echo '</tr>';
    }
	public function textcomboname(&$connection,$table,$datafield,$datafieldvalue,$engfield,$unicodefield)
	{
		if ($_SESSION['lng']=="English")
		{ 
			$query = "select ".$engfield." as displayfield from ".$table." where ".$datafield."=".$datafieldvalue;
		}
		else
		{
			$query = "select ".$unicodefield." as displayfield from ".$table." where ".$datafield."=".$datafieldvalue;
		}
		$result = oci_parse($connection, $query);
		$r = oci_execute($result);
		if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
		{
			return $row['DISPLAYFIELD'];
		}
		else
		{
			return '';
		}
	}
}
?>