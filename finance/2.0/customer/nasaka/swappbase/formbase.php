<?php
require_once("../swappbase/operationmode.php");
class swappform
{
	protected $connection;
	protected $invalidid;
	protected $invalidmessagetext;
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
	
	protected function checkrequired($data,$datalabel)
	{
		if ($this->beingvalidation==true)
		{
			if ($data == '')
			{
				$this->invalidid=-2;
				$this->invalidmessagetext=$datalabel.' is required to enter';
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

}
?>