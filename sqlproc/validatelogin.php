<?php
	require_once('../info/phpsqlajax_dbinfo.php');
    require_once("../info/ncryptdcrypt.php");
    require_once("../info/aadhar.php");
    require_once("../info/crypto.php");
	require_once("../info/swapproutine.php");
	require_once("../sqlproc/defaultusersettings.php");
	$aadharnumber = $_POST["userid"];
	$pass = $_POST["users_pass"];
	$userid_def = $_POST["userid_def"];
	$yearcode_def = $_POST["yearcode_def"];
	$capcode=$_POST["capcode"];
	if (isset($_POST["changedefaultusersettings"]))
	{
		$changedefaultusersettings = $_POST["changedefaultusersettings"];
	}
	else
	{
		$changedefaultusersettings = 'off';
	}
	
	$usedefaultusersettings = $_POST["usedefaultusersettings"];
	/*function isotpalreadyissued($connection,$misuserid)
	{
		$query = "select count(*) as cnt from misuserlogininformation where misuserid=".$misuserid." and date(sessionstartdatetime)=date(now())";
		$result = mysqli_query($connection,$query);
		if ($row = @mysqli_fetch_assoc($result))
		{

		}
	}*/
	session_start();
	if ($capcode!=$_SESSION["capcode"])
	{
		//echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Incomplete Login Information</span>';
	  	header("location: ../mis/login.php?flag=7");
	  	exit;	
	}
	if (isset($aadharnumber) == false or isset($pass) == false or $aadharnumber=='' or $pass=='')
	{
		//echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Incomplete Login Information</span>';
	  	header("location: ../mis/login.php?flag=4");
	  	exit;	
	}
	$useraddhar = new aadhar();
	$valid = $useraddhar->isAadharValid($aadharnumber);
	$valid = 1;
	$isValid = false;
	if ($valid == 1) {
	    $isValid = true;
	}
	if ( $isValid==false)
	{
		//echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Incomplete Login Information</span>';
	  	header("location: ../mis/login.php?flag=6");
	  	exit;
	}
	// Opens a connection to a MySQL server
	$connection=mysqli_connect($hostname, $username, $password, $database);
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Communication Error";
	  	exit;
	}
	$connection ->autocommit(FALSE);
    $query = "SELECT m.misuserid,m.misusername,p.mispassword,m.miscustomerid,p.isotppassword FROM misuser m,misuserpassword p WHERE m.misuserid=p.misuserid and m.misuseractive=1 and p.misactive=1 and (m.aadharnumber = ".$aadharnumber." or m.misuserid = ".$aadharnumber.")";
    //echo $query;
    //exit;
    $result = mysqli_query($connection,$query);
    //$enc = fnEncryptpass($pass);
    //echo $enc.'</br>';
	//echo fnDecryptpass($enc).'</br>';
    //exit;
	if ($row = @mysqli_fetch_assoc($result))
	{
		$dcpass = new crypto;
		$dcrpass = $dcpass->Decrypt($row["mispassword"],1);
		if($pass==$dcrpass)
		{
			session_start();
			$sessid=session_id();

			/*$query11 = "SELECT sessionid FROM misuserlogininformation m WHERE sessionenddatetime is null and sessionid='".$sessid."'";
		    //echo $query;
		    $result11 = mysqli_query($connection,$query11);
			if ($row11 = @mysqli_fetch_assoc($result11))
			{
				if (isset($row11["sessionid"]))
				{
					header("location: ../sqlproc/logout.php");
					exit;
				}
			}
			else
			{*/
				session_regenerate_id(FALSE);
				session_unset();
				/*session is started if you don't write this line can't use $_Session  global variable*/
				$_SESSION["cursession"]=$sessid;
				$_SESSION["usersid"]=$row["misuserid"];
				$_SESSION["usersname"]=$row["misusername"];
/*				$_SESSION["factorycode"]=$customerid;				
				$_SESSION["factoryname"]=$row["miscustomername"];
*/				$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
				$_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
				setdefaultUserValue($connection,$row["misuserid"],$userid_def,$yearcode_def);
				$query = "insert into misuserlogininformation(miscustomerid,misuserid,sessionid,ip_address,sessionstartdatetime) values (0,".$row['misuserid'].",'".$sessid."'".",'".$_SERVER['REMOTE_ADDR']."','".currentdatetime()."')";
				if (mysqli_query($connection, $query)) 
				{
		    		$connection -> commit();
					$_SESSION['changedefaultusersettings'] = $changedefaultusersettings;
					if ($_SESSION['changedefaultusersettings'] == 'on')
					{
						$_SESSION['usedefaultusersettings'] = 'off';
					}
					else
					{
						$_SESSION['usedefaultusersettings'] = $usedefaultusersettings;
					}
					
					if ($_SESSION['usedefaultusersettings'] == 'on')
					{
                        $custid = new crypto;
                            
						if ($_SESSION['usersid'] == 621754328954127)
						{
							$query1 = "SELECT c.miscustomerid,c.miscustomername,c.basefolder 
							FROM miscustomer c
							group by c.miscustomerid,c.miscustomername,c.basefolder";
						}
						else
						{
							$query1 = "SELECT c.miscustomerid,c.miscustomername,c.basefolder 
							FROM miscustomer c,misuserresponsibility r,misuser u 
							WHERE c.misactive=1 and r.misactive=1 and c.miscustomerid=r.misfactoryid
							and r.misuserid=u.misuserid and u.misuserid=".$_SESSION['usersid']."
							group by c.miscustomerid,c.miscustomername,c.basefolder";
						}
						
						$defaultfactorycode = getdefaultfactorycode($connection);
						$result1 = mysqli_query($connection,$query1);
						$i = 1 ;
						while ($row1 = @mysqli_fetch_assoc($result1))
						{
							if ($defaultfactorycode == $row1["miscustomerid"] and $_SESSION['usedefaultusersettings'] == 'on')
							{
								$customerid_en = $custid->Encrypt($row1["miscustomerid"]);
								$basefolder_en = $custid->Encrypt($row1["basefolder"]);
								$factoryname = factoryname($connection,$row1["miscustomerid"]);
								$_SESSION["factorycode"]=$row1["miscustomerid"];
								$_SESSION["factoryname"]=$factoryname;
								$query2 = "SELECT m.mismoduleid,mismodulename_eng,m.modulefolder,c.moduleversionfolder FROM miscustomermodules c, mismodule m where c.active=1 and m.active=1 and c.mismoduleid=m.mismoduleid and c.miscustomerid=".$row1["miscustomerid"];
								$result2 = mysqli_query($connection,$query2);
								$defaultmoduleid = getdefaultmoduleid($connection);                            
								$i=1;
								while ($row2 = @mysqli_fetch_assoc($result2))
								{
									if ($defaultmoduleid == $row2["mismoduleid"] )
									{
										$_SESSION["mismoduleid"] = $row2["mismoduleid"];
										$mismoduleid_en = $custid->Encrypt($row2["mismoduleid"]);
										if ($row2['modulefolder'] == '*' and $row2['moduleversionfolder'] =='*')
										{
											$path = '..';
										}
										else
										{
											$path = '../'.$row2['modulefolder'].'/'.$row2['moduleversionfolder'].'/customer/'.$row1['basefolder'];
										}
										$query3 ="select * from misuserresponsibility m,misresponsibility r,mismoduleresponsibility b where m.misactive=1 and r.misactive=1 and b.active=1 and m.misresponsibilityid=r.misresponsibilityid and r.misresponsibilityid=b.misresponsibilityid and misuserid=".$_SESSION["usersid"]." and b.mismoduleid=".$row2["mismoduleid"]." and (misfactoryid=0 or misfactoryid=".$row1["miscustomerid"].') order by r.misresponsibilityname';
										$defaultresponsibilityid = getdefaultresponsibilityid($connection);
										$result3=mysqli_query($connection, $query3);
										while ($row3 = mysqli_fetch_assoc($result3))
										{
											if ($defaultresponsibilityid == $row3["misresponsibilityid"])
											{
												$_SESSION["responsibilitycode"] = $defaultresponsibilityid;
    											$_SESSION["responsibilityname"] = getresponsibilityname($defaultresponsibilityid);
												$_SESSION["lng"] = 'English';
												$defaultentityid = getdefaultentityid($connection);
												
												//$financepath = getfinancepath($connection,$row1["miscustomerid"]).'/customer/'.$row1['basefolder'];
												$financeconnection = getfinanceconnection($path);
												$query4 = "SELECT g.entityglobalgroupid,g.globalgroupid,e.entityid,e.entityname,e.entityname_eng FROM vw_entity e,entityglobalgroup g where e.entityid=g.entityid and e.active=1 and g.active=1 order by e.entityname asc";						  	
												$result4=mysqli_query($financeconnection, $query4);

												while ($row4 = @mysqli_fetch_assoc($result4))
												{
													$entityglobalgroupid_en = fnEncrypt($row4['entityglobalgroupid']);
													$globalgroupid_en = fnEncrypt($row4['globalgroupid']);
													$entityid_en = fnEncrypt($row4['entityid']);
													if ($defaultentityid == $row4['entityid'])
													{
														$_SESSION["entityid"] = $row4['entityid'];
														$_SESSION["globalgroupid"] = $row4['globalgroupid'];
														$_SESSION["entityglobalgroupid"] = $row4['entityglobalgroupid'];
														$_SESSION["entityname"] = getentityname($financeconnection,$row4['entityid'],0);
														$isrecordpreseent = 0;
														$defaultsubentityid = getdefaultsubentityid($connection);
														$query5 = "SELECT e.subentityid,e.subentityname,e.subentityname_eng FROM subentity e where active=1 and entityid=".$row4['entityid']." order by e.subentityname asc";						  	
														$result5 = mysqli_query($financeconnection,$query5);
														
														while ($row5 = @mysqli_fetch_assoc($result5))
														{
															$subentityid_en = fnEncrypt($row5['subentityid']);
															if ($defaultsubentityid == $row5['subentityid'])
															{
																$_SESSION["subentityid"] = $row5['subentityid'];
																$isrecordpreseent = 1;
																break;
															}
														}
														$defaultfinalreportperiodid = getdefaultfinalreportperiodid($connection);
														$query6 = "SELECT d.finalreportperiodid,d.periodname_eng FROM finalreportperiod d where d.active=1 and finalreportperiodcategoryid=134578598 order by perioddatetimefrom desc";						  	
														$result6 = mysqli_query($financeconnection,$query6);
														while ($row6 = @mysqli_fetch_assoc($result6))
														{
															$finalreportperiodid_en = fnEncrypt($row6['finalreportperiodid']);
															if ($defaultfinalreportperiodid == $row6['finalreportperiodid'])
															{
																$_SESSION["finalreportperiodid"] =  $row6['finalreportperiodid'];
																$_SESSION["financialyear"] = financial_year_1($financeconnection,$row6['finalreportperiodid'],0);
																$dt = getcurrentworkingday_1($financeconnection);
																if ($dt!='')
																{
																	$_SESSION["currentworkingday"] = $dt;
																}
																else
																{
																	unset($_SESSION["currentworkingday"]);
																}
																//setdefaultdate($financeconnection,$finalreportperiodid_de);
																setdefaultdate($financeconnection,$defaultfinalreportperiodid);
																if (!isset($_SESSION['financialyear']))
																{
																	header("location: ".$path."/mis/usermenu.php");
																	exit;
																}
																else
																{
																	header("location: ".$path."/data/entitymenu.php?finalreportperiodid=".fnEncrypt($_SESSION['finalreportperiodid']));
																	exit;
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
   				}
				else
				{
					header("location: ../mis/selectcustomer.php");
					exit;
				}
					/*if ($row['isotppassword']==1)
					{
						header("location: ../mis/changepassword.php");
					}
					else
					{
						header("location: ../mis/selectresponsibility.php");
					}*/
			}
			else
			{
				echo "Communication Error2";
				echo $query;
			}
			//}
		}
		else
		{
			header("location: ../mis/login.php?flag=5");
		  	exit;
		}
	}
	else
	{
		header("location: ../mis/login.php?flag=5");
		exit;
	}


	function factoryname(&$connection, $customerid)
    {
        $query = "SELECT c.miscustomername FROM miscustomer c where c.misactive=1 and c.miscustomerid=".$customerid;
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            return $row['miscustomername'];
        }
        else
        {
            return '';
        }
    }

	function getresponsibilityname($resid)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname, $username, $password, $database);
        // Check connection
        if (mysqli_connect_errno())
          {
            echo "Communication Error";
          }
        $query = "SELECT m.misresponsibilityname FROM misresponsibility  m where m.misactive=1 and misresponsibilityid=".$resid." order by misresponsibilityname";                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            return $row['misresponsibilityname'];
        }
        else
        {
            echo "Communication Error";
            return 0;
        }
    }
	function getentityname($connection,$entityid,$lng)
	{
        $query = "select entityname,entityname_eng from vw_entity where active=1 and entityid=".$entityid;
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            if ($lng == 0)
            {
            	return $row['entityname_eng'];
            }
            else
            {
            	return $row['entityname'];
            }
        }
        else
        {
            echo "Communication Error77";
            return '';
        }
	}
	function setdefaultdate($connection,$finalreportperiodid)
    {
        $entityglobalgroupid = $_SESSION['entityglobalgroupid'];
        $finalreportperiodid = $_SESSION['finalreportperiodid'];

        $query = "select perioddatetimefrom,perioddatetimeto from finalreportperiod f where f.active=1 and f.finalreportperiodid=".$finalreportperiodid;
        //echo $query;
        $result = mysqli_query($connection,$query);
        if ($row = mysqli_fetch_assoc($result))
        {
            $_SESSION['fromdate'] = $row['perioddatetimefrom'];
            $_SESSION['todate'] = $row['perioddatetimeto'];
        }
	}
	
	function setdefaultUserValue($connection,$userid,$userid_def,$yearcode_def)
    {
		if ($userid==$userid_def)
		{
			$query = "select u.misresponsibilityid
			from mismoduleresponsibility m,misuserresponsibility u
			where m.misresponsibilityid=u.misresponsibilityid 
			and u.misuserid=".$userid." 
			and m.mismoduleid=421632541 
			and m.misresponsibilityid in (123980930)";
			//echo $query;
			$result = mysqli_query($connection,$query);
			if ($row = mysqli_fetch_assoc($result))
			{
				$_SESSION['defmoduleid'] = 421632541;
				$_SESSION['defresponsibilityid'] = $row['misresponsibilityid'];
				$_SESSION['defyearcode'] = $yearcode_def;
			}
			else
			{
				$_SESSION['defmoduleid'] = 0;
				$_SESSION['defresponsibilityid'] = 0;
				$_SESSION['defyearcode'] = 0;
			}
		}
		else
		{
			$_SESSION['defmoduleid'] = 0;
			$_SESSION['defresponsibilityid'] = 0;
			$_SESSION['defyearcode'] = 0;
		}
    }

	function getfinanceconnection($path)
	{
		require($path."/info/phpsqlajax_dbinfo.php");
		// Opens a connection to a MySQL server
		$connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
		// Check connection
		if (mysqli_connect_errno())
		{
			echo "Communication Error";
			exit;
		}
		$connection ->autocommit(FALSE);
		return $connection;
	}

	function financial_year_1(&$connection,$finalreportperiodid,$lang)
    {
        $query = "select finalreportperiodid,periodname_eng from finalreportperiod where active=1 and finalreportperiodid=".$finalreportperiodid;
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);
        if (isset($row['finalreportperiodid']))
        {
            if ($lang == 0)
            {
                return $row['periodname_eng'];
            }
            else
            {
                return $row['periodname_eng'];
            }
        }
        else
        {
            return 0;
        }
    }

	function getcurrentworkingday_1(&$connection)
    {
        $query = "select * from workingday where active=1 and dateclosed=0 order by workingdate";
        $result = mysqli_query($connection, $query);

        if ($row = mysqli_fetch_assoc($result))
        {
            $currentdate = date('d/m/Y',strtotime($row['workingdate']));    
        }
        else
        {
            $currentdate = '';
        }
        return $currentdate;
	}
?>