<?php
//convert();
/*$input = 'संदीप गणपतराव निगडे';
$fullname = convertfullnamemar2eng($input);
echo $fullname['1'].' '.$fullname['2'].' '.$fullname['3'].'</br>';
echo $fullname['3'].' '.$fullname['1'].' '.$fullname['2'].'</br>';
*/
function addtodictionaryfromtextfile($filepath)
{
	session_start();
	require("../info/dictionary.php");
	$noofsplits = 6;
	$lines = file($filepath);
	if (isset($_SESSION['lastrecord']))
	{
		$lastrecord=$_SESSION['lastrecord']+1;
	}
	else
	{
		$lastrecord=0;	
	}
	for ($i=$lastrecord;$i<count($lines);$i++)
	{
		$unicodesent = sentencesplit($lines[$i],$noofsplits);
		//print_r($unicodesent);
		echo $i.'#';
		echo $unicodesent[0].' '.$unicodesent[1].' '.$unicodesent[2].' '.$unicodesent[3].' '.$unicodesent[4].' '.$unicodesent[5].'</br>';
		$engfullname = convertfullnamemar2eng($unicodesent[3].' '.$unicodesent[4].' '.$unicodesent[5]);	
		echo $unicodesent[0].' '.$unicodesent[1].' '.$unicodesent[2].' '.$engfullname['1'].' '.$engfullname['2'].' '.$engfullname['3'].'</br>';
		addtodictionary($engfullname['1'],$unicodesent[3],1,0);
		addtodictionary($engfullname['2'],$unicodesent[4],1,0);
		addtodictionary($engfullname['3'],$unicodesent[5],1,1);
		$_SESSION['lastrecord']=$i;
	}
	$_SESSION['lastrecord']=0;
}

/*for ($i=0;$i<count($sent);$i++)
{
	echo $sent[$i].'#';
}*/

function sentencesplit($sentence,$noofsplits)
{
	$output = preg_split( "/[\s]/",$sentence, -1, PREG_SPLIT_NO_EMPTY);	
	$sent=array();
	for ($i=0;$i<$noofsplits;$i++)
	{
		if ($i>=count($output))
		{
			$i=$noofsplits;
		}
		else	
		{
			array_push($sent,$output[$i]);
		}
	}
	return $sent;
}
function convertfullnamemar2eng($fullname)
{
	$output = preg_split( "/[\s]/",$fullname, -1, PREG_SPLIT_NO_EMPTY);
	$firstname='';
	$secondname='';
	$lastname='';
	for ($i=0;$i<count($output);$i++)
	{
		if ($i>0)
		{
			$output1 = preg_split( "/[\s|\(|\)]/",$output[$i],-1, PREG_SPLIT_NO_EMPTY);
			if (count($output1)>=2)
			{
				for ($j=0;$j<count($output1);$j++)
				{
					if ($j==1)
					{
						if ($i==1)
						{
							$secondname .= '('.mar2eng($output1[$j]).')';
						}
						else
						{
							$lastname .= '('.mar2eng($output1[$j]).')';
						}
					}
					else
					{
						if ($i==1)
						{
							$secondname .= mar2eng($output1[$j]);
						}
						else
						{
							$lastname .= mar2eng($output1[$j]);
						}
					}
				}
			}
			else
			{
				$output2 = preg_split( "/[\-]/",$output[$i],-1, PREG_SPLIT_NO_EMPTY);
				if (count($output2)>=2)
				{
					for ($k=0;$k<count($output2);$k++)
					{
						if ($k==1)
						{
							if ($i==1)
							{
								$secondname .= '-'.mar2eng($output2[$k]);
							}
							else
							{
								$lastname .= '-'.mar2eng($output2[$k]);
							}
						}
						else
						{
							if ($i==1)
							{
								$secondname .= mar2eng($output2[$k]);
							}
							else
							{
								$lastname .= mar2eng($output2[$k]);
							}
						}
					}
				}
				else
				{
					if ($i==1)
					{
						$secondname .= mar2eng($output[$i]);
					}
					else
					{
						$lastname .= mar2eng($output[$i]);
					}
				}
			}
		}
		else
		{
			$output1 = preg_split( "/[\s|\(|\)]/",$output[$i],-1, PREG_SPLIT_NO_EMPTY);
			if (count($output1)>=2)
			{
				for ($j=0;$j<count($output1);$j++)
				{
					if ($j==1)
					{
						$firstname .= '('.mar2eng($output1[$j]).')';
					}
					else
					{
						$firstname .= mar2eng($output1[$j]);
					}
				}
			}
			else
			{
				$output2 = preg_split( "/[\-]/",$output[$i],-1, PREG_SPLIT_NO_EMPTY);
				if (count($output2)>=2)
				{
					for ($k=0;$k<count($output2);$k++)
					{
						if ($k==1)
						{
							$firstname .= '-'.mar2eng($output2[$k]);
						}
						else
						{
							$firstname .= mar2eng($output2[$k]);
						}
					}
				}
				else
				{
					$firstname .= mar2eng($output[$i]);	
				}
			}
		}
	}
		$name = array(1=>$firstname,2=>$secondname,3=>$lastname);
		return $name;
}

function convert()
{
	require("../info/phpsqlajax_dbinfo.php");
	require("../info/rawmaterialroutine.php");
	$connection=db_connection();
	mysqli_query($connection,'SET NAMES UTF8');
	$query = "select secondname from table21 group by secondname";
	$result=mysqli_query($connection, $query);
    echo 'मराठी नाव => English Name</br>';
    while ($row = @mysqli_fetch_assoc($result)) 
    {
		$nm = $row['secondname'];
		$engname = mar2eng($nm);
		echo $nm.' => '.$engname.'</br>';
		$query1 = "update table21 set secondname_eng='".$engname."' where secondname='".$row['secondname']."'";
		if (mysqli_query($connection, $query1))
		{
			$connection->commit();
		}
	}
}


function mar2eng($marname)
{
	$retstr='';
	$totalakshar = countakshar($marname);
	//echo 'total-'.$totalakshar;
	$lastakshar=0;
	$a=1;
	$islastaksharconsonent=1;
	for ($i=0;$i<strlen($marname);$i=$i+3)
	{
		$curakshar = countakshar(substr($marname,0,$i+3));
		if ($curakshar==$lastakshar)
		{
			$a++;
		}
		else
		{
			$a=1;
		}
		$iscuraksharconsonent = isconsonent(substr($marname,$i,3));
		//echo 'cur-'.$curakshar;
		switch (substr($marname,$i,3))
		{
			case 'अ': case 'आ':
				$retstr .='a';
				break;
			case 'ा': case 'ॅ':
				$retstr =substr($retstr, 0,strlen($retstr)-1).'a';
				break;
			case 'इ': case 'ई':
				$retstr .='i';
				break;
			case 'ि': case 'ी':
				$retstr =substr($retstr, 0,strlen($retstr)-1).'i';
				break;
			case 'उ': case 'ऊ':
				$retstr .='u';
				break;
			case 'ु': case 'ू':
				$retstr =substr($retstr, 0,strlen($retstr)-1).'u';
				break;
			case 'ए': 
				$retstr .='e';
				break;
			case 'े':
				$retstr =substr($retstr, 0,strlen($retstr)-1).'e';
				break;
			case 'ऐ': 
				$retstr .='ai';
				break;
			case 'ै':
				$retstr =substr($retstr, 0,strlen($retstr)-1).'ai';
				break;
			case 'ऑ': case 'ओ':
				$retstr .='o';
				break;
			case 'ॉ': case 'ो':
				$retstr =substr($retstr, 0,strlen($retstr)-1).'o';
				break;	
			case 'औ':
				$retstr .='au';
				break;
			case 'ौ':
				$retstr =substr($retstr, 0,strlen($retstr)-1).'au';
				break;
			case 'ं': case 'ँ':
				switch (substr($marname,$i+3,3))
				{
				 	case 'प': case 'फ': case 'ब': case 'भ': case 'म': case 'क': case 'ख':
				 		$retstr .='m';
				 		break;
				 	default:
				 		$retstr .='n';
				 		break;
				 }
				break;
			case 'ृ':
				$retstr =substr($retstr, 0,strlen($retstr)-1).'ru';
				break;
			case 'ः':
				$retstr .='h';
				break;
			case 'क':
				$retstr .='ka';
				break;
			case 'ख':
				$retstr .='kha';
				break;				
			case 'ग':
				$retstr .='ga';
				break;				
			case 'घ':
				$retstr .='gha';
				break;				
			case 'ङ':
				$retstr .='ga';
				break;
			case 'च':
				$retstr .='cha';
				break;
			case 'छ':
				$retstr .='chha';
				break;				
			case 'ज':
				$retstr .='ja';
				break;				
			case 'झ':
				$retstr .='za';
				break;				
			case 'ञ':
				$retstr .='';
				break;
			case 'ट':
				$retstr .='ta';
				break;
			case 'ठ':
				$retstr .='tha';
				break;				
			case 'ड':
				$retstr .='da';
				break;				
			case 'ढ':
				$retstr .='dha';
				break;				
			case 'ण':
				$retstr .='na';
				break;
			case 'त':
				$retstr .='ta';
				break;
			case 'थ':
				$retstr .='tha';
				break;				
			case 'द':
				$retstr .='da';
				break;				
			case 'ध':
				$retstr .='dha';
				break;				
			case "न":
				$retstr .= 'na';
				break;
			case 'प':
				$retstr .='pa';
				break;
			case 'फ':
				$retstr .='pha';
				break;				
			case 'ब':
				$retstr .='ba';
				break;				
			case 'भ':
				$retstr .='bha';
				break;				
			case 'म':
				$retstr .='ma';
				break;	
			case 'य':
				$retstr .='ya';
				break;
			case 'र':
				$retstr .='ra';
				break;				
			case 'ल':
				$retstr .='la';
				break;				
			case 'ळ':
				$retstr .='la';
				break;				
			case 'व':
				$retstr .='va';	
				break;
			case 'श':
				$retstr .='sha';
				break;
			case 'ष':
				$retstr .='sha';
				break;				
			case 'स':
				$retstr .='sa';
				break;				
			case 'ह':
				$retstr .='ha';
				break;				
			case '़':
				$retstr .='';
				break;
			case 'ऽ':
				$retstr .='';
				break;
			case '्':
				$retstr = substr($retstr, 0,strlen($retstr)-1);
				break;	
			case 'ॐ':
				$retstr .='om';
				break;				
			case '॒':
				$retstr .='';
				break;				
			case 'ज़':
				$retstr .='ja';
				break;				
			case 'ड़':
				$retstr .='da';
				break;	
			case 'ढ़':
				$retstr .='dha';
				break;
			case 'फ़':
				$retstr .='fa';
				break;				
			case 'ॠ':
				$retstr .='kri';
				break;	
			case 'ऋ':
				$retstr .='ri';
				break;		
			case 'ज्ञ':
				$retstr .='Dny';
				break;
			case '॰':
				$retstr .='.';
				break;				
			default:
				$retstr .= $marname[$i];
				break;
		}
		if ($totalakshar==4)
		{
			if ($curakshar==2 and isconsonent(substr($marname,$i+3,3))==1)
			{
				//echo '!'.countlastardhakshar($marname,$curakshar);
				if (substr($retstr,strlen($retstr)-1,1)=='a' and substr($marname,$i,3)!=='ा' and countlastardhakshar($marname,$curakshar)==0)
				{
					//echo '@'.$retstr.'&'.(substr($marname,$i+3,3));	
					$retstr = substr($retstr, 0,strlen($retstr)-1);
				}
				else
				{
				//echo '#'.$retstr;	
				}
			}
		}
		if ($totalakshar==5)
		{
			if ($curakshar==3 and isconsonent(substr($marname,$i+3,3))==1)
			{
				if (substr($retstr,strlen($retstr)-1,1)=='a' and substr($marname,$i,3)!=='ा' and countlastardhakshar($marname,$curakshar)==0)

				{
					$retstr = substr($retstr, 0,strlen($retstr)-1);
				}
			}
		}
		if ($totalakshar==6)
		{
			if ($curakshar==2 and isconsonent(substr($marname,$i+3,3))==1)
			{
				if (substr($retstr,strlen($retstr)-1,1)=='a' and substr($marname,$i,3)!=='ा' and countlastardhakshar($marname,$curakshar)==0)

				{
					$retstr = substr($retstr, 0,strlen($retstr)-1);
				}
			}
			elseif ($curakshar==4 and isconsonent(substr($marname,$i+3,3))==1)
			{
				if (substr($retstr,strlen($retstr)-1,1)=='a' and substr($marname,$i,3)!=='ा' and countlastardhakshar($marname,$curakshar)==0)

				{
					$retstr = substr($retstr, 0,strlen($retstr)-1);
				}
			}
		}
		if ($totalakshar==7)
		{
			if ($curakshar==3 and isconsonent(substr($marname,$i+3,3))==1)
			{
				if (substr($retstr,strlen($retstr)-1,1)=='a' and substr($marname,$i,3)!=='ा' and countlastardhakshar($marname,$curakshar)==0)

				{
					$retstr = substr($retstr, 0,strlen($retstr)-1);
				}
			}
			elseif ($curakshar==5 and isconsonent(substr($marname,$i+3,3))==1)
			{
				if (substr($retstr,strlen($retstr)-1,1)=='a' and substr($marname,$i,3)!=='ा' and countlastardhakshar($marname,$curakshar)==0)

				{
					$retstr = substr($retstr, 0,strlen($retstr)-1);
				}
			}
		}
		if ($totalakshar==8)
		{
			if ($curakshar==2 and isconsonent(substr($marname,$i+3,3))==1)
			{
				if (substr($retstr,strlen($retstr)-1,1)=='a' and substr($marname,$i,3)!=='ा' and countlastardhakshar($marname,$curakshar)==0)

				{
					$retstr = substr($retstr, 0,strlen($retstr)-1);
				}
			}
			elseif ($curakshar==5 and isconsonent(substr($marname,$i+3,3))==1)
			{
				if (substr($retstr,strlen($retstr)-1,1)=='a' and substr($marname,$i,3)!=='ा' and countlastardhakshar($marname,$curakshar)==0)

				{
					$retstr = substr($retstr, 0,strlen($retstr)-1);
				}
			}
		}
		//echo '.'.$retstr.'&'.$iscuraksharconsonent.'*'.$islastaksharconsonent.'</br>';
		$lastakshar =$curakshar;
		$islastaksharconsonent=$iscuraksharconsonent;
	}
	if (substr($marname,strlen($marname)-3,3) !== 'ा' and substr($retstr,strlen($retstr)-1,1)=='a' and countlastardhakshar($marname,$curakshar)==0)
	{
		$retstr = substr($retstr, 0,strlen($retstr)-1);
	}

	$retstr = preg_replace('/gava/','gaon',$retstr,-1);
	$retstr = preg_replace('/gav/','gaon',$retstr,-1);
	$retstr = preg_replace('/gawa/','gaon',$retstr,-1);
	$retstr = preg_replace('/gaw/','gaon',$retstr,-1);
	$retstr = preg_replace('/rava/','rao',$retstr,-1);
	$retstr = preg_replace('/rav/','rao',$retstr,-1);
	$retstr = preg_replace('/rawa/','rao',$retstr,-1);
	$retstr = preg_replace('/raw/','rao',$retstr,-1);
	$retstr = preg_replace('/gaya/','gai',$retstr,-1);
	$retstr = preg_replace('/gay/','gai',$retstr,-1);
	$retstr = preg_replace('/aw/','av',$retstr,-1);
	$retstr = preg_replace('/pavar/','pawar',$retstr,-1);
	$retstr = preg_replace('/deva/','deo',$retstr,-1);
	$retstr = preg_replace('/dev/','deo',$retstr,-1);
	$retstr = preg_replace('/gaonhane/','gavhane',$retstr,-1);
	$retstr = preg_replace('/gurao/','gurav',$retstr,-1);
	$retstr = preg_replace('/vagh/','wagh',$retstr,-1);
	$retstr = preg_replace('/vant/','want',$retstr,-1);
	$retstr = preg_replace('/vane/','wane',$retstr,-1);
	$retstr = preg_replace('/val/','wal',$retstr,-1);
	$retstr = preg_replace('/vat/','wat',$retstr,-1);
	$retstr = preg_replace('/vade/','wade',$retstr,-1);
	$retstr = preg_replace('/wate/','vate',$retstr,-1);
	$retstr = preg_replace('/gaonde/','gavade',$retstr,-1);
	$retstr = preg_replace('/raoi/','ravi',$retstr,-1);
	$retstr = preg_replace('/ndr$/','ndra',$retstr,-1);
	$retstr = preg_replace('/shekh/','sheikh',$retstr,-1);
	$retstr = preg_replace('/deoi/','devi',$retstr,-1);
	$retstr = preg_replace('/shvar/','shwar',$retstr,-1);
	return ucfirst($retstr);
}

function countakshar($marname)
{
	$cnt=0;
	for ($i=0;$i<strlen($marname);$i=$i+3)
	{
		switch (substr($marname,$i,3))
		{
			case 'अ': case 'आ':
				$cnt++;
				break;
			case 'उ': case 'ऊ':
				$cnt++;
				break;
			case 'ए': 
				$cnt++;
				break;
			case 'ऐ': 
				$cnt++;
				break;
			case 'ऑ': case 'ओ':
				$cnt++;
				break;
			case 'औ':
				$cnt++;
				break;
			case 'क':
				$cnt++;
				break;
			case 'ख':
				$cnt++;
				break;				
			case 'ग':
				$cnt++;
				break;				
			case 'घ':
				$cnt++;
				break;				
			case 'ङ':
				$cnt++;
				break;
			case 'च':
				$cnt++;
				break;
			case 'छ':
				$cnt++;
				break;				
			case 'ज':
				$cnt++;
				break;				
			case 'झ':
				$cnt++;
				break;				
			case 'ञ':
				$cnt++;
				break;
			case 'ट':
				$cnt++;
				break;
			case 'ठ':
				$cnt++;
				break;				
			case 'ड':
				$cnt++;
				break;				
			case 'ढ':
				$cnt++;
				break;				
			case 'ण':
				$cnt++;
				break;
			case 'त':
				$cnt++;
				break;
			case 'थ':
				$cnt++;
				break;				
			case 'द':
				$cnt++;
				break;				
			case 'ध':
				$cnt++;
				break;				
			case "न":
				$cnt++;
				break;
			case 'प':
				$cnt++;
				break;
			case 'फ':
				$cnt++;
				break;				
			case 'ब':
				$cnt++;
				break;				
			case 'भ':
				$cnt++;
				break;				
			case 'म':
				$cnt++;
				break;	
			case 'य':
				$cnt++;
				break;
			case 'र':
				$cnt++;
				break;				
			case 'ल':
				$cnt++;
				break;				
			case 'ळ':
				$cnt++;
				break;				
			case 'व':
				$cnt++;
				break;
			case 'श':
				$cnt++;
				break;
			case 'ष':
				$cnt++;
				break;				
			case 'स':
				$cnt++;
				break;				
			case 'ह':
				$cnt++;
				break;				
			case 'ॐ':
				$cnt++;
				break;				
			case 'ज़':
				$cnt++;
				break;				
			case 'ड़':
				$cnt++;
				break;	
			case 'ढ़':
				$cnt++;
				break;
			case 'फ़':
				$cnt++;
				break;				
			case 'ॠ':
				$cnt++;
				break;				
			default:
				break;
		}
		if (substr($marname,$i+3,3)=='्')
		{
			$cnt--;
		}
	}
	return $cnt;
}

function countlastardhakshar($marname,$curcnt)
{
	$cnt=0;
	$acnt=0;
	for ($i=0;$i<strlen($marname);$i=$i+3)
	{
		switch (substr($marname,$i,3))
		{
			case 'अ': case 'आ':
				$cnt++;
				break;
			case 'उ': case 'ऊ':
				$cnt++;
				break;
			case 'ए': 
				$cnt++;
				break;
			case 'ऐ': 
				$cnt++;
				break;
			case 'ऑ': case 'ओ':
				$cnt++;
				break;
			case 'औ':
				$cnt++;
				break;
			case 'क':
				$cnt++;
				break;
			case 'ख':
				$cnt++;
				break;				
			case 'ग':
				$cnt++;
				break;				
			case 'घ':
				$cnt++;
				break;				
			case 'ङ':
				$cnt++;
				break;
			case 'च':
				$cnt++;
				break;
			case 'छ':
				$cnt++;
				break;				
			case 'ज':
				$cnt++;
				break;				
			case 'झ':
				$cnt++;
				break;				
			case 'ञ':
				$cnt++;
				break;
			case 'ट':
				$cnt++;
				break;
			case 'ठ':
				$cnt++;
				break;				
			case 'ड':
				$cnt++;
				break;				
			case 'ढ':
				$cnt++;
				break;				
			case 'ण':
				$cnt++;
				break;
			case 'त':
				$cnt++;
				break;
			case 'थ':
				$cnt++;
				break;				
			case 'द':
				$cnt++;
				break;				
			case 'ध':
				$cnt++;
				break;				
			case "न":
				$cnt++;
				break;
			case 'प':
				$cnt++;
				break;
			case 'फ':
				$cnt++;
				break;				
			case 'ब':
				$cnt++;
				break;				
			case 'भ':
				$cnt++;
				break;				
			case 'म':
				$cnt++;
				break;	
			case 'य':
				$cnt++;
				break;
			case 'र':
				$cnt++;
				break;				
			case 'ल':
				$cnt++;
				break;				
			case 'ळ':
				$cnt++;
				break;				
			case 'व':
				$cnt++;
				break;
			case 'श':
				$cnt++;
				break;
			case 'ष':
				$cnt++;
				break;				
			case 'स':
				$cnt++;
				break;				
			case 'ह':
				$cnt++;
				break;				
			case 'ॐ':
				$cnt++;
				break;				
			case 'ज़':
				$cnt++;
				break;				
			case 'ड़':
				$cnt++;
				break;	
			case 'ढ़':
				$cnt++;
				break;
			case 'फ़':
				$cnt++;
				break;				
			case 'ॠ':
				$cnt++;
				break;				
			default:
				break;
		}
		if (substr($marname,$i+3,3)=='्' and ($cnt==$curcnt))
		{
			$acnt++;
		}
	}
	return $acnt;
}


function isconsonent($marname)
{
	$cnt=0;
	for ($i=0;$i<1;$i=$i+1)
	{
		switch (substr($marname,$i,3))
		{
			case 'अ': case 'आ':
				$cnt++;
				break;
			case 'उ': case 'ऊ':
				$cnt++;
				break;
			case 'ए': 
				$cnt++;
				break;
			case 'ऐ': 
				$cnt++;
				break;
			case 'ऑ': case 'ओ':
				$cnt++;
				break;
			case 'औ':
				$cnt++;
				break;
			case 'क':
				$cnt++;
				break;
			case 'ख':
				$cnt++;
				break;				
			case 'ग':
				$cnt++;
				break;				
			case 'घ':
				$cnt++;
				break;				
			case 'ङ':
				$cnt++;
				break;
			case 'च':
				$cnt++;
				break;
			case 'छ':
				$cnt++;
				break;				
			case 'ज':
				$cnt++;
				break;				
			case 'झ':
				$cnt++;
				break;				
			case 'ञ':
				$cnt++;
				break;
			case 'ट':
				$cnt++;
				break;
			case 'ठ':
				$cnt++;
				break;				
			case 'ड':
				$cnt++;
				break;				
			case 'ढ':
				$cnt++;
				break;				
			case 'ण':
				$cnt++;
				break;
			case 'त':
				$cnt++;
				break;
			case 'थ':
				$cnt++;
				break;				
			case 'द':
				$cnt++;
				break;				
			case 'ध':
				$cnt++;
				break;				
			case "न":
				$cnt++;
				break;
			case 'प':
				$cnt++;
				break;
			case 'फ':
				$cnt++;
				break;				
			case 'ब':
				$cnt++;
				break;				
			case 'भ':
				$cnt++;
				break;				
			case 'म':
				$cnt++;
				break;	
			case 'य':
				$cnt++;
				break;
			case 'र':
				$cnt++;
				break;				
			case 'ल':
				$cnt++;
				break;				
			case 'ळ':
				$cnt++;
				break;				
			case 'व':
				$cnt++;
				break;
			case 'श':
				$cnt++;
				break;
			case 'ष':
				$cnt++;
				break;				
			case 'स':
				$cnt++;
				break;				
			case 'ह':
				$cnt++;
				break;				
			case 'ॐ':
				$cnt++;
				break;				
			case 'ज़':
				$cnt++;
				break;				
			case 'ड़':
				$cnt++;
				break;	
			case 'ढ़':
				$cnt++;
				break;
			case 'फ़':
				$cnt++;
				break;				
			case 'ॠ':
				$cnt++;
				break;				
			default:
				break;
		}
	}
	if ($cnt==0)
	{
		return 0;
	}
	else
	{
		return 1;
	}
}


?>