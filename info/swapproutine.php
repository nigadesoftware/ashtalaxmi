<?php

        function isdateinperiod($dt)
        {
            require("../info/phpsqlajax_dbinfo.php");
            // Opens a connection to a MySQL server
            $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
            // Check connection
            if (mysqli_connect_errno())
            {
                echo "Communication Error";
            }
            $query = "SELECT count(*) as cnt FROM finalreportperiod f where f.active=1 and finalreportperiodid=".$_SESSION['finalreportperiodid']." and '$dt' between perioddatetimefrom and perioddatetimeto";                          
            mysqli_query($connection,'SET NAMES UTF8');
            $result = mysqli_query($connection,$query);
            if ($row = @mysqli_fetch_assoc($result))
            {
                if ($row['cnt'] == 0)
                {
                    return 0;
                }
                elseif ($row['cnt'] == 1)
                {
                    return 1;
                }
            }
            else
            {
                echo "Communication Error";
                return 0;
            }
        }


    function getcurrentworkingday()
    {
        require("../info/phpsqlajax_dbinfo.php");
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
        {
          echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Communication Error</span>';
          exit;
        }
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

    function isaccessible($responsibilityid)
    {
        require('../info/phpsqlajax_dbinfo.php');
        if ($_SESSION["responsibilitycode"] == $responsibilityid and ($_SESSION["factorycode"]==$customerid or $_SESSION["responsibilitycode"]==621478512368915 or $_SESSION["responsibilitycode"]==785236954125917))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    function currentdate()
    {
        date_default_timezone_set("Asia/Kolkata");
        $dt = time();
        $dt = date('d/m/Y',$dt);
        date_default_timezone_set("UTC");
        return $dt;
    }

    function currentdatetime()
    {
        date_default_timezone_set("UTC");
        $dt = time();
        $dt = date('Y-m-d H:i:s',$dt);
        return $dt;
    }

    function resetpassword(&$connection,$userid)
    {
        //require_once("../info/ncryptdcrypt.php");
        require_once("../info/crypto.php");
        $passgen = rand(1000000,9999999);
        if ($_SESSION["usersid"] == $userid)
        {
            echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Invalid Operation</span>';
            exit;
        }
        $query = "update misuserpassword set misactive=0,dluserid=".$_SESSION["usersid"].",dldatetime='".currentdatetime()."' where misuserid=".$userid;
        if (mysqli_query($connection, $query)) 
            {
                $otp=''.$passgen;
                //$passgen = fnEncrypt($otp);
                $dcpass = new crypto;
		        $dcrpass = $dcpass->Encrypt($otp);
                $querypass = "insert into misuserpassword(misuserid,mispassword,isotppassword,misactive,cruserid,crdatetime) values (".$userid.",'$dcrpass',1,1,".$_SESSION["usersid"].",'".currentdatetime()."')";
                if (mysqli_query($connection, $querypass)) 
                    {
                        return $otp;
                    }
                else
                    {
                        return '';
                    }
            } 
        else 
            {
                return '';
            }
    }

    function entityledgerclosingbalance(&$connection,$entityledgeraccountid)
    {
        $entityglobalgroupid = $_SESSION['entityglobalgroupid'];
        $finalreportperiodid = $_SESSION['finalreportperiodid'];
        $query = "select closingbalance from entitymainledger e,finalreportperiod f where e.active=1 and f.active=1 and e.finalreportperiodid=f.finalreportperiodid and e.entityglobalgroupid=".$entityglobalgroupid." and e.finalreportperiodid=".$finalreportperiodid." and e.entityledgeraccountid=".$entityledgeraccountid." and e.mainledgerstartdatetime<=f.perioddatetimeto and e.mainledgerenddatetime>=f.perioddatetimeto";
        $result=mysqli_query($connection,$query);
        $row = mysqli_fetch_assoc($result);
        //echo $query;
        if (isset($row['closingbalance']))
        {
            return $row['closingbalance'];
        }
        else
        {
            return 0;
        }
    }
    
function entitysubledgerclosingbalance(&$connection,$entitysubledgeraccountid)
    {
        $entityglobalgroupid = $_SESSION['entityglobalgroupid'];
        $finalreportperiodid = $_SESSION['finalreportperiodid'];
        $query = "select closingbalance from entitysubledger e,finalreportperiod f where e.active=1 and f.active=1 and e.finalreportperiodid=f.finalreportperiodid and e.entityglobalgroupid=".$entityglobalgroupid." and e.finalreportperiodid=".$finalreportperiodid." and e.entitysubledgeraccountid=".$entitysubledgeraccountid." and e.subledgerstartdatetime<=f.perioddatetimeto and e.subledgerenddatetime>=f.perioddatetimeto";
        $result=mysqli_query($connection,$query);
        $row = mysqli_fetch_assoc($result);
        //echo $query;
        if (isset($row['closingbalance']))
        {
            return $row['closingbalance'];
        }
        else
        {
            return 0;
        }
    }


    function isentriesallowed()
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
        {
            //echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication error</span>';
            exit;
        }
        mysqli_query($connection,'SET NAMES UTF8');
        $entityglobalgroupid = $_SESSION['entityglobalgroupid'];
        $query = "select isentriesallowed from financialyear e where e.active=1 and finalreportperiodid=".$_SESSION['finalreportperiodid'];
        $result=mysqli_query($connection,$query);
        $row = mysqli_fetch_assoc($result);
        //echo $query;
        if (isset($row['isentriesallowed']))
        {
            return $row['isentriesallowed'];
        }
        else
        {
            return 0;
        }
    }

    function subledgerclosingbalance($entityledgeraccountid,$entitysubledgeraccountid,$closingdate)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
        {
            //echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication error</span>';
            exit;
        }
        mysqli_query($connection,'SET NAMES UTF8');
        $entityglobalgroupid = $_SESSION['entityglobalgroupid'];
        $query = "select closingbalance from entitysubledger e where e.active=1 and entityglobalgroupid=".$entityglobalgroupid." and entityledgeraccountid=".$entityledgeraccountid." and entitysubledgeraccountid=".$entitysubledgeraccountid." and subledgerstartdatetime<='".$closingdate."' and subledgerenddatetime>='".$closingdate."'";
        $result=mysqli_query($connection,$query);
        $row = mysqli_fetch_assoc($result);
        //echo $query;
        if (isset($row['closingbalance']))
        {
            return $row['closingbalance'];
        }
        else
        {
            return 0;
        }
    }

    function ledgerclosingbalance($entityledgeraccountid,$closingdate)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
        {
            //echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication error</span>';
            exit;
        }
        mysqli_query($connection,'SET NAMES UTF8');
        $entityglobalgroupid = $_SESSION['entityglobalgroupid'];
        $query = "select closingbalance from entitymainledger e where e.active=1 and entityglobalgroupid=".$entityglobalgroupid." and entityledgeraccountid=".$entityledgeraccountid." and mainledgerstartdatetime<='".$closingdate."' and mainledgerenddatetime>='".$closingdate."'";
        $result=mysqli_query($connection,$query);
        $row = mysqli_fetch_assoc($result);
        //echo $query;
        if (isset($row['closingbalance']))
        {
            return $row['closingbalance'];
        }
        else
        {
            return 0;
        }
    }     

    function carryforwardbalance(&$connection,$entityglobalgroupid,$finalreportperiodid,$finalreportperiodid_new)
    {
        $query = "select r.finalreportperiodid,p.perioddatetimefrom,p.periodname_eng from financialyear r,finalreportperiod p where r.active=1 and p.active=1 and r.finalreportperiodid=p.finalreportperiodid and r.entityglobalgroupid=".$entityglobalgroupid." and r.finalreportperiodid=".$finalreportperiodid_new." and r.isbalancecarriedforward=-1 order by p.finalreportperiodid";                          
        //echo $query;
        //mysqli_query($connection,'SET NAMES UTF8');
        $result22 = mysqli_query($connection,$query);
        while ($row22 = @mysqli_fetch_assoc($result22))
        {
            //echo 'Period ='.$row22['periodname_eng'].'</br>';
            $query = "select l.entityledgeraccountid,l.entityledgeraccountname,l.subledgerallowed from entityledgeraccount l where l.active=1 order by entityledgeraccountid";                          
            mysqli_query($connection,'SET NAMES UTF8');
            $result11 = mysqli_query($connection,$query);
            while ($row11 = @mysqli_fetch_assoc($result11))
            {
                //echo $row11['entityledgeraccountname'].' ';
                if (isplledger($row11['entityledgeraccountid']) == 0)
                {
                    $retcode = carryforwardmainledgerbalance($connection,$entityglobalgroupid,$row22['finalreportperiodid'],$row11['entityledgeraccountid'],$row22['perioddatetimefrom']);
                    if ($retcode == 1)
                    {
                        if ($row11['subledgerallowed'] == 1)
                        {
                            $query = "select s.entitysubledgeraccountid,entitysubledgeraccountname from entitysubledgeraccount s where s.active=1 and entityledgeraccountid=".$row11['entityledgeraccountid']." order by entityledgeraccountid,entitysubledgeraccountid";                          
                            
                            mysqli_query($connection,'SET NAMES UTF8');
                            $result12 = mysqli_query($connection,$query);
                            while ($row12 = @mysqli_fetch_assoc($result12))
                            {
                                $retcode1 = carryforwardsubledgerbalance($connection,$entityglobalgroupid,$row22['finalreportperiodid'],$row11['entityledgeraccountid'],$row12['entitysubledgeraccountid'],$row22['perioddatetimefrom']);
                                if ($retcode1 == 0)
                                {
                                    //$connection->rollback();
                                    echo 'Communication Error1';
                                    return 0;
                                    exit;
                                }
                                else
                                {
                                    $closingdate = date('Y-m-d',strtotime($row22['perioddatetimefrom'].' - 1 days'));
                                    $openingbalance = subledgerclosingbalance($row11['entityledgeraccountid'],$row12['entitysubledgeraccountid'],$closingdate);
                                    $query = "update entitysubledger m set m.openingbalance=".$openingbalance.",m.closingbalance=".$openingbalance." where finalreportperiodid=".$row22['finalreportperiodid']." and entityledgeraccountid=".$row11['entityledgeraccountid']." and entitysubledgeraccountid=".$row12['entitysubledgeraccountid'];
                                    if (mysqli_query($connection, $query)) 
                                    {

                                    }
                                    else
                                    {
                                        //$connection->rollback();
                                        echo 'Communication Error2';
                                        return 0;
                                        exit;
                                    }
                                }
                            }
                        }
                        $closingdate = date('Y-m-d',strtotime($row22['perioddatetimefrom'].' - 1 days'));
                        $openingbalance = ledgerclosingbalance($row11['entityledgeraccountid'],$closingdate);
                        //echo 'date '.$closingdate.' ';
                        //echo 'bal '.$openingbalance.'</br>';
                        $query = "update entitymainledger m set m.openingbalance=".$openingbalance.",m.closingbalance=".$openingbalance." where m.active=1 and finalreportperiodid=".$row22['finalreportperiodid']." and entityledgeraccountid=".$row11['entityledgeraccountid'];
                        if (mysqli_query($connection, $query)) 
                        {
                            
                        }
                        else
                        {
                            //$connection->rollback();
                            echo 'Communication Error3';
                            return 0;
                            exit;
                        }
                    }
                    else
                    {
                        //$connection->rollback();
                        echo 'Communication Error4';
                        return 0;
                        exit;
                    }
                }
            }

            $query = "select a.entityledgeraccountid from standardhead s,standardheadaccount a where s.standardheadid=a.standardheadid and s.active=1 and a.active=1 and s.standardheadid=745896357";                          
            mysqli_query($connection,'SET NAMES UTF8');
            $result33 = mysqli_query($connection,$query);
            if ($row33 = @mysqli_fetch_assoc($result33))
            {
                $standardheadaccountid_profitloss = $row33['entityledgeraccountid'];
            }
            else
            {
                $standardheadaccountid_profitloss = 0;
            }

            $retcode = carryforwardmainledgerbalance($connection,$entityglobalgroupid,$row22['finalreportperiodid'],0,$row22['perioddatetimefrom']);
            $retcode1 = carryforwardmainledgerbalance($connection,$entityglobalgroupid,$row22['finalreportperiodid'],$standardheadaccountid_profitloss,$row22['perioddatetimefrom']);                
            if ($retcode == 1 and $retcode1 == 1)
            {
                $closingdate = date('Y-m-d',strtotime($row22['perioddatetimefrom'].' - 1 days'));
                $closingbalance_current_profit_loss = ledgerclosingbalance(0,$closingdate);
                $openingaccured_profitloss = ledgerclosingbalance($standardheadaccountid_profitloss,$closingdate);
                $netprofitloss = $openingaccured_profitloss + ($closingbalance_current_profit_loss*-1);
                $query = "update entitymainledger m set m.openingbalance=".$netprofitloss.",m.closingbalance=".$netprofitloss." where m.active=1 and entityglobalgroupid=".$entityglobalgroupid." and finalreportperiodid=".$row22['finalreportperiodid']." and entityledgeraccountid=".$standardheadaccountid_profitloss;
                if (mysqli_query($connection, $query)) 
                {
                    $query = "update financialyear r set isbalancecarriedforward=0 where r.active=1 and entityglobalgroupid=".$entityglobalgroupid." and finalreportperiodid=".$finalreportperiodid;                          
                    if (mysqli_query($connection, $query)) 
                    {
                        //echo 'Records carried forward to New Financial Year'.'</br>';
                        //$connection->commit();
                        return 1;
                    }
                    else
                    {
                        //$connection->rollback();
                        echo 'Communication Error7';
                        return 0;
                    }
                }
                else
                {
                    //$connection->rollback();
                    echo 'Communication Error5';
                    return 0;
                    exit;
                }
            }
            else
            {
                //$connection->rollback();
                echo 'Communication Error6';
                return 0;
                exit;
            }
        }   
    }

    function carryforwardmainledgerbalance(&$connection,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entityvoucherdatetime)
    {
        $query = "select * from finalreportperiod f where f.active=1 and f.finalreportperiodid=".$finalreportperiodid;                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result11 = mysqli_query($connection,$query);
        while ($row11 = @mysqli_fetch_assoc($result11))
        {
            $retcode = ismainledgerpresent($entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entityvoucherdatetime);
            if ($retcode == 0)
            {
                $retcode1=insertmainledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entityvoucherdatetime);
            }
            elseif ($retcode == 1)
            {
                $retcode1=insertmainledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entityvoucherdatetime);
            }
            elseif ($retcode == 2)
            {
                $retcode1=insertmainledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entityvoucherdatetime);
            }
            if ($retcode1 == 0)
            {
                return 0;
                exit;
            }
        }
        return 1;
    }

    function carryforwardsubledgerbalance(&$connection,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entitysubledgeraccountid,$entityvoucherdatetime)
    {
        $query = "select * from finalreportperiod f where f.active=1 and f.finalreportperiodid=".$finalreportperiodid;                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result11 = mysqli_query($connection,$query);
        while ($row11 = @mysqli_fetch_assoc($result11))
        {
            $retcode = issubledgerpresent($entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entitysubledgeraccountid,$entityvoucherdatetime);
            if ($retcode == 0)
            {
                $retcode1=insertsubledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entitysubledgeraccountid,$entityvoucherdatetime);
            }
            elseif ($retcode == 1)
            {
                $retcode1=insertsubledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entitysubledgeraccountid,$entityvoucherdatetime);
            }
            elseif ($retcode == 2)
            {
                $retcode1=insertsubledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entitysubledgeraccountid,$entityvoucherdatetime);
            }
            if ($retcode1 == 0)
            {
                return 0;
                exit;
            }
        }
        return 1;
    }
        
    function isentriesallowedforfinancialyear($finalreportperiodid)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Communication Error";
            return 0;
        }
        $query = "select isentriesallowed from finalreportperiod f where f.active=1 and f.finalreportperiodid=".$finalreportperiodid;
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);

        if ($row = @mysqli_fetch_assoc($result))
        {
            return $row['isentriesallowed'];
        }
        else
        {
            return 0;
        }
    }
    
    function isplledger($entityledgeraccountid)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Communication Error";
            return 0;
        }
        $query = "select g.finalreportid from entityledgeraccount l,entitymaingroup g where l.entitymaingroupid=g.entitymaingroupid and g.active=1 and l.active=1 and l.entityledgeraccountid=".$entityledgeraccountid;
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);

        if ($row = @mysqli_fetch_assoc($result))
        {
            if ($row['finalreportid'] == 227845168)
            {
                return 1;
            }
            else
            {
                return 0;
            }
        }
    }

    function getmonthdate($monthno,$startendflag)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Communication Error";
            return 0;
        }
        $query = "select perioddatetimefrom,perioddatetimeto from finalreportperiod where active=1 and finalreportperiodid=".$_SESSION['finalreportperiodid'];
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);
        //$dt = date('Y-1-1',strtotime($row['perioddatetimefrom']));
        if ($row = @mysqli_fetch_assoc($result))
        {
            if ($monthno >= 4 and $monthno <= 12)
            {
                if ($startendflag == 0)
                {
                    $dt = date('Y-1-1',strtotime($row['perioddatetimefrom'])); 
                    $dt = date('Y-m-d',strtotime($dt.' + '.($monthno-1).' months'));    
                }
                elseif ($startendflag == 1)
                {
                    $dt = date('Y-1-1',strtotime($row['perioddatetimefrom']));    
                    $dt = date('Y-m-d',strtotime($dt.' + '.$monthno.' months')); 
                    $dt = date('Y-m-d',strtotime($dt.' - 1 days'));    
                }
            }
            else
            {
                if ($startendflag == 0)
                {
                    $dt = date('Y-1-1',strtotime($row['perioddatetimeto'])); 
                    $dt = date('Y-m-d',strtotime($dt.' + '.($monthno-1).' months'));    
                }
                elseif ($startendflag == 1)
                {
                    $dt = date('Y-1-1',strtotime($row['perioddatetimeto']));    
                    $dt = date('Y-m-d',strtotime($dt.' + '.$monthno.' months')); 
                    $dt = date('Y-m-d',strtotime($dt.' - 1 days'));    
                }
            }
        return $dt;
        }
    }

    function getledgername($entityledgeraccountid,$lng=0)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Communication Error";
            return 0;
        }
        $query = "SELECT d.entityledgeraccountname,d.entityledgeraccountname_eng FROM entityledgeraccount d where d.active=1 and entityledgeraccountid=".$entityledgeraccountid." order by entityledgeraccountname";                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            if ($lng==0)
            {
                return $row['entityledgeraccountname_eng'];
            }
            else
            {
                return $row['entityledgeraccountname'];   
            }
        }
        else
        {
            echo "Communication Error";
            return 0;
        }
    }

    function getsubledgername($entitysubledgeraccountid,$lng=0)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Communication Error";
            return 0;
        }
        $query = "SELECT d.entitysubledgeraccountname,d.entitysubledgeraccountname_eng FROM entitysubledgeraccount d where d.active=1 and entitysubledgeraccountid=".$entitysubledgeraccountid." order by entitysubledgeraccountname";                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            if ($lng==0)
            {
                return $row['entitysubledgeraccountname_eng'];
            }
            else
            {
                return $row['entitysubledgeraccountname'];   
            }
            
        }
        else
        {
            echo "Communication Error";
            return 0;
        }
    }

    function monthname($monthno,$lng)
    {
        if ($lng==0)
        {
            if ($monthno == 1)
            {
                $monthname = "January";
            }
            elseif ($monthno == 2)
            {
                $monthname = "February";
            }
            elseif ($monthno == 3)
            {
                $monthname = "March";
            }
            elseif ($monthno == 4)
            {
                $monthname = "April";
            }
            elseif ($monthno == 5)
            {
                $monthname = "May";
            }
            elseif ($monthno == 6)
            {
                $monthname = "June";
            }
            elseif ($monthno == 7)
            {
                $monthname = "July";
            }
            elseif ($monthno == 8)
            {
                $monthname = "August";
            }
            elseif ($monthno == 9)
            {
                $monthname = "September";
            }
            elseif ($monthno == 10)
            {
                $monthname = "October";
            }
            elseif ($monthno == 11)
            {
                $monthname = "November";
            }
            elseif ($monthno == 12)
            {
                $monthname = "December";
            }
        }
        elseif ($lng==1)
        {
            if ($monthno == 1)
            {
                $monthname = "जानेवारी";
            }
            elseif ($monthno == 2)
            {
                $monthname = "फेब्रुवारी";
            }
            elseif ($monthno == 3)
            {
                $monthname = "मार्च";
            }
            elseif ($monthno == 4)
            {
                $monthname = "एप्रिल";
            }
            elseif ($monthno == 5)
            {
                $monthname = "मे";
            }
            elseif ($monthno == 6)
            {
                $monthname = "जून";
            }
            elseif ($monthno == 7)
            {
                $monthname = "जुलै";
            }
            elseif ($monthno == 8)
            {
                $monthname = "ऑगस्ट";
            }
            elseif ($monthno == 9)
            {
                $monthname = "सप्टेंबर";
            }
            elseif ($monthno == 10)
            {
                $monthname = "ऑक्टोबर";
            }
            elseif ($monthno == 11)
            {
                $monthname = "नोव्हेंबर";
            }
            elseif ($monthno == 12)
            {
                $monthname = "डिसेंबर";
            }   
        }
        return $monthname;
    }
    
    function ismainledgerpresent($entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entityvoucherdatetime)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
          {
            echo "Communication Error";
          }
        $query = "select mainledgerstartdatetime,mainledgerenddatetime from entitymainledger where active=1 and entityglobalgroupid = ".$entityglobalgroupid." and finalreportperiodid=".$finalreportperiodid." and entityledgeraccountid=".$entityledgeraccountid." and mainledgerstartdatetime ='".$entityvoucherdatetime."' and mainledgerenddatetime ='".$entityvoucherdatetime."'";
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            return 1;
        }
        else
        {
            $query = "select mainledgerstartdatetime,mainledgerenddatetime from entitymainledger where active=1 and entityglobalgroupid = ".$entityglobalgroupid." and finalreportperiodid=".$finalreportperiodid." and entityledgeraccountid=".$entityledgeraccountid." and mainledgerstartdatetime <='".$entityvoucherdatetime."' and mainledgerenddatetime >='".$entityvoucherdatetime."'";
            mysqli_query($connection,'SET NAMES UTF8');
            $result = mysqli_query($connection,$query);
            if ($row = @mysqli_fetch_assoc($result))
            {
                return 2;
            }
            else
            {
                return 0;
            }
        }
    }

    function issubledgerpresent($entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entitysubledgeraccountid,$entityvoucherdatetime)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
          {
            echo "Communication Error";
          }
        $query = "select subledgerstartdatetime,subledgerenddatetime from entitysubledger where active=1 and entityglobalgroupid = ".$entityglobalgroupid." and finalreportperiodid=".$finalreportperiodid." and entityledgeraccountid=".$entityledgeraccountid." and entitysubledgeraccountid=".$entitysubledgeraccountid." and subledgerstartdatetime ='".$entityvoucherdatetime."' and subledgerenddatetime ='".$entityvoucherdatetime."'";
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            return 1;
        }
        else
        {
            $query = "select subledgerstartdatetime,subledgerenddatetime from entitysubledger where active=1 and entityglobalgroupid = ".$entityglobalgroupid." and finalreportperiodid=".$finalreportperiodid." and entityledgeraccountid=".$entityledgeraccountid." and entitysubledgeraccountid=".$entitysubledgeraccountid." and subledgerstartdatetime <='".$entityvoucherdatetime."' and subledgerenddatetime >='".$entityvoucherdatetime."'";
            mysqli_query($connection,'SET NAMES UTF8');
            $result = mysqli_query($connection,$query);
            if ($row = @mysqli_fetch_assoc($result))
            {
                return 2;
            }
            else
            {
                return 0;
            }
        }
    }

    function dayinmonthflag($dt)
    {
        if (date('d',strtotime($dt)) == 1)
        {
            return 0;
        }
        elseif (date('d',strtotime($dt.' + 1 days')) == 1)
        {
            return 2;
        }
        else
        {
            return 1;
        }
    }

    function insled(&$connection,$i,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$fromdate,$todate,$opbal,$closbal)
    {
        $query = "insert into entitymainledger(entitymainledgerid,entityglobalgroupid,finalreportperiodid,entityledgeraccountid,mainledgerstartdatetime,mainledgerenddatetime,openingbalance,closingbalance,active,cruserid,crdatetime) value (".$i.",".$entityglobalgroupid.",".$finalreportperiodid.",".$entityledgeraccountid.",'$fromdate','$todate',".$opbal.",".$closbal.",1,".$_SESSION['usersid'].",'".currentdatetime()."')";
        if (mysqli_query($connection, $query))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    function delled(&$connection,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$transactionid)
    {
        $query = "update entitymainledger set active=0 where active=1 and entityglobalgroupid=$entityglobalgroupid and finalreportperiodid=$finalreportperiodid and entityledgeraccountid=$entityledgeraccountid and transactionid=$transactionid";
        //echo $query;
        if (mysqli_query($connection, $query))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    function inssubled(&$connection,$i,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entitysubledgeraccountid,$fromdate,$todate,$opbal,$closbal)
    {
        $query = "insert into entitysubledger(entitysubledgerid,entityglobalgroupid,finalreportperiodid,entityledgeraccountid,entitysubledgeraccountid,subledgerstartdatetime,subledgerenddatetime,openingbalance,closingbalance,active,cruserid,crdatetime) value (".$i.",".$entityglobalgroupid.",".$finalreportperiodid.",".$entityledgeraccountid.",".$entitysubledgeraccountid.",'$fromdate','$todate',".$opbal.",".$closbal.",1,".$_SESSION['usersid'].",'".currentdatetime()."')";
        if (mysqli_query($connection, $query))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    function delsubled(&$connection,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entitysubledgeraccountid,$transactionid)
    {
        $query = "update entitysubledger set active=0 where active=1 and entityglobalgroupid=$entityglobalgroupid and finalreportperiodid=$finalreportperiodid and entityledgeraccountid=$entityledgeraccountid and entitysubledgeraccountid=$entitysubledgeraccountid and transactionid=$transactionid";
        //echo $query;
        if (mysqli_query($connection, $query))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    function insertmainledger(&$connection,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entityvoucherdatetime)
    {
        require_once ("../info/swapproutine.php");
        $retcode = ismainledgerpresent($entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entityvoucherdatetime);
        //No record
        if ($retcode == 0)
        {
            $query = "select perioddatetimefrom,perioddatetimeto from finalreportperiod where active=1 and finalreportperiodid=".$finalreportperiodid;                          
            mysqli_query($connection,'SET NAMES UTF8');
            $connection -> autocommit(FALSE);
            $result = mysqli_query($connection,$query);
            
            if ($row = @mysqli_fetch_assoc($result))
            {
                $ledfromdate = $row['perioddatetimefrom'];
                $ledtodate = $row['perioddatetimeto'];
                $vdate = $entityvoucherdatetime;
                $opbal = 0;
                $i=1;
                //first
                if ($ledfromdate == $vdate)
                {
                    $resid = 1;
                }
                else
                {
                    $fromdate = $ledfromdate;
                    $todate = date('Y-m-d H:i:s',strtotime($entityvoucherdatetime.' - 1 days'));
                    $resid = insled($connection,$i,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$fromdate,$todate,$opbal,$opbal);
                }
                if ($resid == 1)
                {
                    //second
                    if ($entityvoucherdatetime == $ledfromdate and $entityvoucherdatetime ==$ledtodate)
                    {
                        $resid = 1;
                    }
                    else
                    {
                        $fromdate = $entityvoucherdatetime;
                        $todate = $entityvoucherdatetime;
                        $i++;
                        $resid = insled($connection,$i,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$fromdate,$todate,$opbal,$opbal);
                    }
                    if ($resid == 1)
                    {
                        //third
                        if ($entityvoucherdatetime == $ledtodate)
                        {
                            $resid = 1;
                        }
                        else
                        {
                            $fromdate = date('Y-m-d H:i:s',strtotime($entityvoucherdatetime.' + 1 days'));
                            $todate = $ledtodate;
                            $i++;
                            $resid = insled($connection,$i,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$fromdate,$todate,$opbal,$opbal);
                        }
                        if ($resid == 1)
                        {
                            return 1;
                        }
                        else
                        {
                            return 0;
                        }
                    }
                    else
                    {
                        return 0;
                    }
                }
                else
                {
                    return 0;
                }
            }
            else
            {
                return 0;
            }
        }
        //voucher date record present
        elseif ($retcode == 1)
        {
            return 1;
        }
        //voucher date belongs to period record present
        elseif ($retcode == 2)
        {
            $query = "select e.transactionid,entitymainledgerid,e.entityledgeraccountid,mainledgerstartdatetime,mainledgerenddatetime,f.perioddatetimefrom,f.perioddatetimeto,e.openingbalance,e.closingbalance from entitymainledger e,finalreportperiod f where e.active=1 and f.active=1 and e.finalreportperiodid=f.finalreportperiodid and e.entityglobalgroupid = ".$entityglobalgroupid." and e.finalreportperiodid=".$finalreportperiodid." and entityledgeraccountid=".$entityledgeraccountid." and mainledgerstartdatetime <='".$entityvoucherdatetime."' and mainledgerenddatetime >='".$entityvoucherdatetime."'";
            mysqli_query($connection,'SET NAMES UTF8');
            $connection -> autocommit(FALSE);
            $result = mysqli_query($connection,$query);
            if ($row = @mysqli_fetch_assoc($result))
            {
                $ledfromdate = $row['mainledgerstartdatetime'];
                $ledtodate = $row['mainledgerenddatetime'];
                $vdate = $entityvoucherdatetime;
                $opbal = $row['openingbalance'];
                $i=$row['entitymainledgerid'];
                $transactionid=$row['transactionid'];
                //delete existing record
                $resid = delled($connection,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$transactionid);
                if ($resid == 1)
                {
                    //first
                    $i--;
                    if ($entityvoucherdatetime == $ledfromdate)
                    {
                        $resid = 1;
                    }
                    else
                    {
                        $i++;
                        $fromdate = $ledfromdate;
                        $todate = date('Y-m-d H:i:s',strtotime($entityvoucherdatetime.' - 1 days'));
                        $resid = insled($connection,$i,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$fromdate,$todate,$opbal,$opbal);
                    }
                    if ($resid == 1)
                    {
                        //second
                        if ($entityvoucherdatetime == $ledfromdate and $entityvoucherdatetime ==$ledtodate)
                        {
                            $resid = 1;
                        }
                        else
                        {
                            $fromdate = $entityvoucherdatetime;
                            $todate = $entityvoucherdatetime;
                            $i++;
                            $resid = insled($connection,$i,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$fromdate,$todate,$opbal,$opbal);
                        }
                        if ($resid == 1)
                        {
                            //third
                            if ($entityvoucherdatetime == $ledtodate)
                            {
                                $resid = 1;
                            }
                            else
                            {
                                $fromdate = date('Y-m-d H:i:s',strtotime($entityvoucherdatetime.' + 1 days'));
                                $todate = $ledtodate;
                                $i++;
                                $resid = insled($connection,$i,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$fromdate,$todate,$opbal,$opbal);
                            }
                            if ($resid == 1)
                            {
                                return 1;
                            }
                            else
                            {
                                return 0;
                            }
                        }
                        else
                        {
                            return 0;
                        }
                    }
                    else
                    {
                       return 0; 
                    }
                }
                else
                {
                    return 0;
                }
            }
        }
    }

    function insertsubledger(&$connection,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entitysubledgeraccountid,$entityvoucherdatetime)
    {
        require_once ("../info/swapproutine.php");
        $retcode = issubledgerpresent($entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entitysubledgeraccountid,$entityvoucherdatetime);
        //No record
        if ($retcode == 0)
        {
            $query = "select perioddatetimefrom,perioddatetimeto from finalreportperiod where active=1 and finalreportperiodid=".$finalreportperiodid;                          
            mysqli_query($connection,'SET NAMES UTF8');
            $connection -> autocommit(FALSE);
            $result = mysqli_query($connection,$query);
            
            if ($row = @mysqli_fetch_assoc($result))
            {
                $ledfromdate = $row['perioddatetimefrom'];
                $ledtodate = $row['perioddatetimeto'];
                $vdate = $entityvoucherdatetime;
                $opbal = 0;
                $i=0;
                //first
                if ($entityvoucherdatetime == $ledfromdate)
                {
                    $resid = 1;
                }
                else
                {
                    $fromdate = $ledfromdate;
                    $todate = date('Y-m-d H:i:s',strtotime($entityvoucherdatetime.' - 1 days'));
                    $i++;
                    $resid = inssubled($connection,$i,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entitysubledgeraccountid,$fromdate,$todate,$opbal,$opbal);
                }
                if ($resid == 1)
                {
                    //second
                    if ($entityvoucherdatetime == $ledfromdate and $entityvoucherdatetime ==$ledtodate)
                    {
                        $resid = 1;
                    }
                    else
                    {
                        $fromdate = $entityvoucherdatetime;
                        $todate = $entityvoucherdatetime;
                        $i++;
                        $resid = inssubled($connection,$i,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entitysubledgeraccountid,$fromdate,$todate,$opbal,$opbal);
                    }
                    if ($resid == 1)
                    {
                        //third
                        if ($entityvoucherdatetime == $ledtodate)
                        {
                            $resid = 1;
                        }
                        else
                        {
                            $fromdate = date('Y-m-d H:i:s',strtotime($entityvoucherdatetime.' + 1 days'));
                            $todate = $ledtodate;
                            $i++;
                            $resid = inssubled($connection,$i,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entitysubledgeraccountid,$fromdate,$todate,$opbal,$opbal);
                        }
                        if ($resid == 1)
                        {
                            return 1;
                        }
                        else
                        {
                            return 0;
                        }
                    }
                    else
                    {
                        return 0;
                    }
                }
                else
                {
                    return 0;
                }
            }
            else
            {
                return 0;
            }
        }
        //voucher date record present
        elseif ($retcode == 1)
        {
            return 1;
        }
        //voucher date belongs to period record present
        elseif ($retcode == 2)
        {
            $query = "select e.transactionid,entitysubledgerid,e.entityledgeraccountid,e.entitysubledgeraccountid,subledgerstartdatetime,subledgerenddatetime,f.perioddatetimefrom,f.perioddatetimeto,e.openingbalance,e.closingbalance from entitysubledger e,finalreportperiod f where e.active=1 and f.active=1 and e.finalreportperiodid=f.finalreportperiodid and e.entityglobalgroupid = ".$entityglobalgroupid." and e.finalreportperiodid=".$finalreportperiodid." and entityledgeraccountid=".$entityledgeraccountid." and entitysubledgeraccountid=".$entitysubledgeraccountid." and subledgerstartdatetime <='".$entityvoucherdatetime."' and subledgerenddatetime >='".$entityvoucherdatetime."'";
            //echo $query;
            mysqli_query($connection,'SET NAMES UTF8');
            $connection -> autocommit(FALSE);
            $result = mysqli_query($connection,$query);
            if ($row = @mysqli_fetch_assoc($result))
            {
                $ledfromdate = $row['subledgerstartdatetime'];
                $ledtodate = $row['subledgerenddatetime'];
                $vdate = $entityvoucherdatetime;
                $opbal = $row['openingbalance'];
                $i=$row['entitysubledgerid'];
                $transactionid=$row['transactionid'];
                //delete existing record
                $resid = delsubled($connection,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entitysubledgeraccountid,$transactionid);
                if ($resid == 1)
                {
                    //first
                    $i--;
                    if ($entityvoucherdatetime == $ledfromdate)
                    {
                        $resid = 1;
                    }
                    else
                    {
                        $i++;
                        $fromdate = $ledfromdate;
                        $todate = date('Y-m-d H:i:s',strtotime($entityvoucherdatetime.' - 1 days'));
                        $resid = inssubled($connection,$i,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entitysubledgeraccountid,$fromdate,$todate,$opbal,$opbal);
                    }
                    if ($resid == 1)
                    {
                        //second
                        if ($entityvoucherdatetime == $ledfromdate and $entityvoucherdatetime ==$ledtodate)
                        {
                            $resid = 1;
                        }
                        else
                        {
                            $fromdate = $entityvoucherdatetime;
                            $todate = $entityvoucherdatetime;
                            $i++;
                            $resid = inssubled($connection,$i,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entitysubledgeraccountid,$fromdate,$todate,$opbal,$opbal);
                        }
                        if ($resid == 1)
                        {
                            //third
                            if ($entityvoucherdatetime == $ledtodate)
                            {
                                $resid = 1;
                            }
                            else
                            {
                                $fromdate = date('Y-m-d H:i:s',strtotime($entityvoucherdatetime.' + 1 days'));
                                $todate = $ledtodate;
                                $i++;
                                $resid = inssubled($connection,$i,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entitysubledgeraccountid,$fromdate,$todate,$opbal,$opbal);
                            }
                            if ($resid == 1)
                            {
                                return 1;
                            }
                            else
                            {
                                return 0;
                            }
                        }
                        else
                        {
                            return 0;
                        }
                    }
                    else
                    {
                       return 0; 
                    }
                }
                else
                {
                    return 0;
                }
            }
        }
    }

    /*function insertsubledger(&$connection,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entitysubledgeraccountid,$entityvoucherdatetime)
    {
        require_once ("../info/swapproutine.php");
        
        $retcode = issubledgerpresent($entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entitysubledgeraccountid,$entityvoucherdatetime);
        if ($retcode == 0)
        {
            $query = "select perioddatetimefrom,perioddatetimeto from finalreportperiod where active=1 and finalreportperiodid=".$finalreportperiodid;                          
            mysqli_query($connection,'SET NAMES UTF8');
            $connection -> autocommit(FALSE);
            $result = mysqli_query($connection,$query);
            if ($row = @mysqli_fetch_assoc($result))
            {
                $fromdate = $row['perioddatetimefrom'];
                $todate = $row['perioddatetimefrom'];
                $i=1;
                $query = "insert into entitysubledger(entitysubledgerid,entityglobalgroupid,finalreportperiodid,entityledgeraccountid,entitysubledgeraccountid,subledgerstartdatetime,subledgerenddatetime,cruserid,crdatetime) value (".$i.",".$entityglobalgroupid.",".$finalreportperiodid.",".$entityledgeraccountid.",".$entitysubledgeraccountid.",'$fromdate','$todate',".$_SESSION['usersid'].",'".currentdatetime()."')";
                if (mysqli_query($connection, $query))
                {
                    if ($entityvoucherdatetime != $row['perioddatetimefrom'])
                    {
                        if ($entityvoucherdatetime != $row['perioddatetimeto'])
                        {
                            $fromdate = date('Y-m-d H:i:s',strtotime($row['perioddatetimefrom'].' + 1 days'));
                            $todate = date('Y-m-d H:i:s',strtotime($entityvoucherdatetime.' - 1 days'));
                            $i++;
                            $query = "insert into entitysubledger(entitysubledgerid,entityglobalgroupid,finalreportperiodid,entityledgeraccountid,entitysubledgeraccountid,subledgerstartdatetime,subledgerenddatetime,cruserid,crdatetime) value (".$i.",".$entityglobalgroupid.",".$finalreportperiodid.",".$entityledgeraccountid.",".$entitysubledgeraccountid.",'$fromdate','$todate',".$_SESSION['usersid'].",'".currentdatetime()."')";
                            if (mysqli_query($connection, $query))
                            {
                                $fromdate = $entityvoucherdatetime;
                                $todate = $entityvoucherdatetime;

                                $i++;
                                $query = "insert into entitysubledger(entitysubledgerid,entityglobalgroupid,finalreportperiodid,entityledgeraccountid,entitysubledgeraccountid,subledgerstartdatetime,subledgerenddatetime,cruserid,crdatetime) value (".$i.",".$entityglobalgroupid.",".$finalreportperiodid.",".$entityledgeraccountid.",".$entitysubledgeraccountid.",'$fromdate','$todate',".$_SESSION['usersid'].",'".currentdatetime()."')";
                                if (mysqli_query($connection, $query))
                                {
                                    $fromdate = date('Y-m-d H:i:s',strtotime($entityvoucherdatetime.' + 1 days'));
                                    $todate = $row['perioddatetimeto'];
                                    $i++;
                                    $query = "insert into entitysubledger(entitysubledgerid,entityglobalgroupid,finalreportperiodid,entityledgeraccountid,entitysubledgeraccountid,subledgerstartdatetime,subledgerenddatetime,cruserid,crdatetime) value (".$i.",".$entityglobalgroupid.",".$finalreportperiodid.",".$entityledgeraccountid.",".$entitysubledgeraccountid.",'$fromdate','$todate',".$_SESSION['usersid'].",'".currentdatetime()."')";
                                    if (mysqli_query($connection, $query))
                                    {
                                        return 1;
                                    }
                                    else
                                    {
                                        return 0;
                                    }
                                }
                                else
                                {
                                    return 0;
                                }
                            }
                            else
                            {
                                return 0;
                            }
                        }
                        else
                        {
                            $fromdate = date('Y-m-d H:i:s',strtotime($row['perioddatetimefrom'].' + 1 days'));
                            $todate = date('Y-m-d H:i:s',strtotime($entityvoucherdatetime.' - 1 days'));
                            $i++;
                            $query = "insert into entitysubledger(entitysubledgerid,entityglobalgroupid,finalreportperiodid,entityledgeraccountid,entitysubledgeraccountid,subledgerstartdatetime,subledgerenddatetime,cruserid,crdatetime) value (".$i.",".$entityglobalgroupid.",".$finalreportperiodid.",".$entityledgeraccountid.",".$entitysubledgeraccountid.",'$fromdate','$todate',".$_SESSION['usersid'].",'".currentdatetime()."')";
                            if (mysqli_query($connection, $query))
                            {
                                $fromdate = $entityvoucherdatetime;
                                $todate = $row['perioddatetimeto'];
                                $i++;
                                $query = "insert into entitysubledger(entitysubledgerid,entityglobalgroupid,finalreportperiodid,entityledgeraccountid,entitysubledgeraccountid,subledgerstartdatetime,subledgerenddatetime,cruserid,crdatetime) value (".$i.",".$entityglobalgroupid.",".$finalreportperiodid.",".$entityledgeraccountid.",".$entitysubledgeraccountid.",'$fromdate','$todate',".$_SESSION['usersid'].",'".currentdatetime()."')";
                                if (mysqli_query($connection, $query))
                                {
                                    //$connection->commit();
                                    return 1;
                                }
                            }
                        }
                    }
                    else
                    {
                        $fromdate = date('Y-m-d H:i:s',strtotime($row['perioddatetimefrom'].' + 1 days'));
                        $todate = $row['perioddatetimeto'];
                        $i++;
                        $query = "insert into entitysubledger(entitysubledgerid,entityglobalgroupid,finalreportperiodid,entityledgeraccountid,entitysubledgeraccountid,subledgerstartdatetime,subledgerenddatetime,cruserid,crdatetime) value (".$i.",".$entityglobalgroupid.",".$finalreportperiodid.",".$entityledgeraccountid.",".$entitysubledgeraccountid.",'$fromdate','$todate',".$_SESSION['usersid'].",'".currentdatetime()."')";
                        if (mysqli_query($connection, $query))
                        {
                            return 1;
                        }
                    }    
                }
                else
                {
                    return 0;
                }
            }
            else
            {
                return 0;
            }
        }
        elseif ($retcode == 1)
        {
            return 1;
        }
        elseif ($retcode == 2)
        {
            $query = "select entitysubledgerid,subledgerstartdatetime,subledgerenddatetime,f.perioddatetimefrom,f.perioddatetimeto,e.openingbalance,e.closingbalance from entitysubledger e,finalreportperiod f where e.active=1 and f.active=1 and e.finalreportperiodid=f.finalreportperiodid and e.entityglobalgroupid = ".$entityglobalgroupid." and e.finalreportperiodid=".$finalreportperiodid." and entityledgeraccountid=".$entityledgeraccountid." and entitysubledgeraccountid=".$entitysubledgeraccountid." and subledgerstartdatetime <='".$entityvoucherdatetime."' and subledgerenddatetime >='".$entityvoucherdatetime."'";
            mysqli_query($connection,'SET NAMES UTF8');
            $connection -> autocommit(FALSE);
            $result = mysqli_query($connection,$query);
            if ($row = @mysqli_fetch_assoc($result))
            {
                $fromdate = $row['subledgerstartdatetime'];
                if ($entityvoucherdatetime>$fromdate)
                {
                    if ($entityvoucherdatetime ==$row['subledgerenddatetime'])
                    {
                        $todate = date('Y-m-d H:i:s',strtotime($row['subledgerenddatetime'].' - 1 days'));
                    }
                    else
                    {
                        $todate = date('Y-m-d H:i:s',strtotime($entityvoucherdatetime.' - 1 days'));
                    }
                }
                elseif ($entityvoucherdatetime==$fromdate)
                {
                    $todate = $entityvoucherdatetime;
                }
                
                $i = $row['entitysubledgerid'];
                $query = "select closingbalance from entitysubledger e where e.active=1 and e.entityglobalgroupid = ".$entityglobalgroupid." and e.finalreportperiodid=".$finalreportperiodid." and entityledgeraccountid=".$entityledgeraccountid." and entitysubledgeraccountid=".$entitysubledgeraccountid." and subledgerstartdatetime <='".$entityvoucherdatetime."' and subledgerenddatetime >='".$entityvoucherdatetime."'";                   
                $result1 = mysqli_query($connection,$query);
                if ($row1 = @mysqli_fetch_assoc($result1))
                {
                    $closingbalance = $row1['closingbalance'];
                }
                else
                {
                    $closingbalance = 0;
                }
                $query = "update entitysubledger e set subledgerenddatetime='".$todate."' where e.active=1 and e.entityglobalgroupid = ".$entityglobalgroupid." and e.finalreportperiodid=".$finalreportperiodid." and entityledgeraccountid=".$entityledgeraccountid." and entitysubledgeraccountid=".$entitysubledgeraccountid." and subledgerstartdatetime <='".$entityvoucherdatetime."' and subledgerenddatetime >='".$entityvoucherdatetime."'";
                if (mysqli_query($connection, $query))
                {
                    if ($fromdate<=$todate)
                    {
                        $fromdate = $entityvoucherdatetime;
                        $todate = $entityvoucherdatetime;
                        $i++;
                        $query = "insert into entitysubledger(entitysubledgerid,entityglobalgroupid,finalreportperiodid,entityledgeraccountid,entitysubledgeraccountid,subledgerstartdatetime,subledgerenddatetime,openingbalance,closingbalance,cruserid,crdatetime) value (".$i.",".$entityglobalgroupid.",".$finalreportperiodid.",".$entityledgeraccountid.",".$entitysubledgeraccountid.",'$fromdate','$todate',".$closingbalance.",".$closingbalance.",".$_SESSION['usersid'].",'".currentdatetime()."')";
                        if (mysqli_query($connection, $query))
                        {
                            if (date('Y-m-d H:i:s',strtotime($entityvoucherdatetime.' + 1 days'))<$row['subledgerenddatetime'])
                            {
                                $fromdate = date('Y-m-d H:i:s',strtotime($entityvoucherdatetime.' + 1 days'));
                                $todate = $row['subledgerenddatetime'];
                                if ($fromdate <$todate)
                                {
                                    $i++;
                                    $query = "insert into entitysubledger(entitysubledgerid,entityglobalgroupid,finalreportperiodid,entityledgeraccountid,entitysubledgeraccountid,subledgerstartdatetime,subledgerenddatetime,openingbalance,closingbalance,cruserid,crdatetime) value (".$i.",".$entityglobalgroupid.",".$finalreportperiodid.",".$entityledgeraccountid.",".$entitysubledgeraccountid.",'$fromdate','$todate',".$closingbalance.",".$closingbalance.",".$_SESSION['usersid'].",'".currentdatetime()."')";
                                    if (mysqli_query($connection, $query))
                                    {
                                       return 1;
                                    }
                                    else
                                    {
                                        return 0;
                                    } 
                                }
                                else
                                {
                                    $fromdate = date('Y-m-d H:i:s',strtotime($entityvoucherdatetime.' - 1 days'));
                                    $todate = $row['subledgerenddatetime'];
                                    $i++;
                                    $query = "insert into entitysubledger(entitysubledgerid,entityglobalgroupid,finalreportperiodid,entityledgeraccountid,entitysubledgeraccountid,subledgerstartdatetime,subledgerenddatetime,openingbalance,closingbalance,cruserid,crdatetime) value (".$i.",".$entityglobalgroupid.",".$finalreportperiodid.",".$entityledgeraccountid.",".$entitysubledgeraccountid.",'$fromdate','$todate',".$closingbalance.",".$closingbalance.",".$_SESSION['usersid'].",'".currentdatetime()."')";
                                    if (mysqli_query($connection, $query))
                                    {
                                       return 1;
                                    }
                                    else
                                    {
                                        return 0;
                                    } 
                                }
                            }
                        }
                        else
                        {
                            return 0;
                        }
                    }
                    else
                    {
                        return 0;
                    }
                }
            }
        }
    }
*/
    function updatepl(&$connection,$entityglobalgroupid,$finalreportperiodid,$entityvoucherdatetime)
    {
        $query = "select * from finalreportperiod r where r.active=1 and r.finalreportperiodid=".$finalreportperiodid;                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result11 = mysqli_query($connection,$query);
        while ($row11 = @mysqli_fetch_assoc($result11))
        {
            $retcode = ismainledgerpresent($entityglobalgroupid,$row11['finalreportperiodid'],0,$entityvoucherdatetime);
            if ($retcode == 0)
            {
                $retcode1=insertmainledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],0,$entityvoucherdatetime);
            }
            elseif ($retcode == 1)
            {
                $retcode1=insertmainledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],0,$entityvoucherdatetime);
            }
            elseif ($retcode == 2)
            {
                $retcode1=insertmainledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],0,$entityvoucherdatetime);
            }
        }
        $query = "select coalesce(sum(debit)*-1,0) as credit,coalesce(sum(credit)*-1,0) as debit from entitymainledger v,entityledgeraccount l,entitymaingroup m,accountsubgroupcategory s,accountgroupcategory a where v.active=1 and l.active=1 and m.active=1 and s.active=1 and a.active=1 and v.entityledgeraccountid=l.entityledgeraccountid and l.entitymaingroupid=m.entitymaingroupid and m.accountsubgroupcategoryid=s.accountsubgroupcategoryid and s.accountgroupcategoryid=a.accountgroupcategoryid and a.finalreportid=227845168 and v.entityglobalgroupid=".$entityglobalgroupid." and v.finalreportperiodid=".$finalreportperiodid." and v.entityledgeraccountid<>0 and v.mainledgerstartdatetime='".$entityvoucherdatetime."' and v.mainledgerenddatetime='".$entityvoucherdatetime."'";                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            $query = "select openingbalance,debit,credit,closingbalance from entitymainledger v where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.finalreportperiodid=".$finalreportperiodid." and v.entityledgeraccountid=0 and v.mainledgerstartdatetime='".$entityvoucherdatetime."' and v.mainledgerenddatetime='".$entityvoucherdatetime."'";
            mysqli_query($connection,'SET NAMES UTF8');
            $result1 = mysqli_query($connection,$query);
            if ($row1 = @mysqli_fetch_assoc($result1))
            {
                if (isset($row['debit']))
                {
                    $cur_debit = $row['debit'];
                }
                else
                {
                    $cur_debit = 0;
                }
                if (isset($row['credit']))
                {
                    $cur_credit = $row['credit'];
                }
                else
                {
                    $cur_credit = 0;
                }
                $diff = $cur_debit - $row1['debit'] + $cur_credit - $row1['credit'];
                $query = "update entitymainledger v set debit=".$cur_debit.",credit=".$cur_credit.",closingbalance=closingbalance+".$diff." where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=0 and v.mainledgerstartdatetime='".$entityvoucherdatetime."' and v.mainledgerenddatetime='".$entityvoucherdatetime."' and finalreportperiodid=".$finalreportperiodid;
                if (mysqli_query($connection, $query))
                {
                    $query = "update entitymainledger v set openingbalance=openingbalance+".$diff.",closingbalance=closingbalance+".$diff." where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=0 and v.mainledgerstartdatetime>'".$entityvoucherdatetime."' and finalreportperiodid=".$finalreportperiodid;
                    if (mysqli_query($connection, $query))
                    {
                        return 1;
                    }
                    else
                    {
                        return 0;
                    }
                }
                else
                {
                    return 0;
                }
            }
        }
    }
    function updatemainledgerdebitcredit(&$connection,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entityvoucherdatetime,$yearfinalreportperiodid)
    {
        $query = "select * from finalreportperiod r where r.active=1 and r.finalreportperiodid=".$finalreportperiodid;                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result11 = mysqli_query($connection,$query);
        while ($row11 = @mysqli_fetch_assoc($result11))
        {
            $retcode = ismainledgerpresent($entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entityvoucherdatetime);
            if ($retcode == 0)
            {
                $retcode1 =insertmainledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entityvoucherdatetime);
            }
            elseif ($retcode == 1)
            {
                $retcode1 = insertmainledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entityvoucherdatetime);
            }
            elseif ($retcode == 2)
            {
                $retcode1 = insertmainledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entityvoucherdatetime);
            }
        }
        //$connection->commit();
        $isplledger = isplledger($entityledgeraccountid);

        $query = "select sum(case when d.debitcredit = 157489650 then d.amount else 0 end) as debit, sum(case when d.debitcredit = 357481241 then d.amount * -1 else 0 end) as credit from entityvoucher v, entityvoucherledgeraccountdetail d where v.entityvoucherid=d.entityvoucherid and v.active=1 and d.active=1 and v.isvalid=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.finalreportperiodid=".$yearfinalreportperiodid." and d.entityledgeraccountid=".$entityledgeraccountid." and v.entityvoucherdatetime='".$entityvoucherdatetime."'";                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            $query = "select entitymainledgerid,openingbalance,debit,credit,closingbalance from entitymainledger v where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.finalreportperiodid=".$finalreportperiodid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.mainledgerstartdatetime='".$entityvoucherdatetime."' and v.mainledgerenddatetime='".$entityvoucherdatetime."'";
            mysqli_query($connection,'SET NAMES UTF8');
            $result1 = mysqli_query($connection,$query);
            if ($row1 = @mysqli_fetch_assoc($result1))
            {
                if (isset($row['debit']))
                {
                    $cur_debit = $row['debit'];    
                }
                else
                {
                    $cur_debit = 0;
                }
                if (isset($row['credit']))
                {
                    $cur_credit = $row['credit'];
                }
                else
                {
                    $cur_credit = 0;
                }
                $diff = $cur_debit - $row1['debit'] + $cur_credit - $row1['credit'];
                if ($isplledger == 0)
                {
                    $query = "update entitymainledger v set debit=".$cur_debit.",credit=".$cur_credit.",closingbalance=closingbalance+".$diff." where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.mainledgerstartdatetime='".$entityvoucherdatetime."' and v.mainledgerenddatetime='".$entityvoucherdatetime."' and finalreportperiodid=".$finalreportperiodid;
                }
                else
                {
                    $query = "update entitymainledger v set debit=".$cur_debit.",credit=".$cur_credit.",closingbalance=closingbalance+".$diff." where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.mainledgerstartdatetime='".$entityvoucherdatetime."' and v.mainledgerenddatetime='".$entityvoucherdatetime."' and finalreportperiodid=".$finalreportperiodid;                        
                }              
                if (mysqli_query($connection, $query))
                {
                    if ($isplledger == 0)
                    {
                        if ($finalreportperiodid == $yearfinalreportperiodid)
                        {
                            $query = "update entitymainledger v set openingbalance=openingbalance+".$diff.",closingbalance=closingbalance+".$diff." where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.mainledgerstartdatetime>'".$entityvoucherdatetime."' and v.finalreportperiodid in (select f.finalreportperiodid from finalreportperiod f where f.active=1 and finalreportperiodcategoryid=134578598)";
                        }
                        else
                        {
                            $query = "update entitymainledger v set openingbalance=openingbalance+".$diff.",closingbalance=closingbalance+".$diff." where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.mainledgerstartdatetime>'".$entityvoucherdatetime."' and v.finalreportperiodid in (select f.finalreportperiodid from finalreportperiod f where f.active=1 and finalreportperiodcategoryid=235425471)";
                        }
                    }
                    else
                    {
                        $query = "update entitymainledger v set openingbalance=openingbalance+".$diff.",closingbalance=closingbalance+".$diff." where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.mainledgerstartdatetime>'".$entityvoucherdatetime."' and finalreportperiodid=".$finalreportperiodid;
                    }

                    if (mysqli_query($connection, $query))
                    {
                        return 1;
                    }
                    else
                    {
                        return 0;
                    }
                }
                else
                {
                    return 0;
                }
            }
        }
    }

    function updatesubledgerdebitcredit(&$connection,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entitysubledgeraccountid,$entityvoucherdatetime,$yearfinalreportperiodid)
    {
        $query = "select * from finalreportperiod r where r.active=1 and r.finalreportperiodid=".$finalreportperiodid;                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result11 = mysqli_query($connection,$query);
        while ($row11 = @mysqli_fetch_assoc($result11))
        {
            $retcode = issubledgerpresent($entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entitysubledgeraccountid,$entityvoucherdatetime);
            if ($retcode == 0)
            {
                $retcode1=insertsubledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entitysubledgeraccountid,$entityvoucherdatetime);
            }
            elseif ($retcode == 1)
            {
                $retcode1=insertsubledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entitysubledgeraccountid,$entityvoucherdatetime);
            }
            elseif ($retcode == 2)
            {
                $retcode1=insertsubledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entitysubledgeraccountid,$entityvoucherdatetime);
            }
        }
        $isplledger = isplledger($entityledgeraccountid);
        //$connection->commit();
        $query = "select sum(case when s.debitcredit = 157489650 then s.amount else 0 end) as debit, sum(case when s.debitcredit = 357481241 then s.amount * -1 else 0 end) as credit from entityvoucher v, entityvoucherledgeraccountdetail d,entityvouchersubledgeraccountdetail s where d.entityvoucherledgeraccountdetailid=s.entityvoucherledgeraccountdetailid and v.entityvoucherid=d.entityvoucherid and v.active=1 and d.active=1 and v.isvalid=1 and s.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.finalreportperiodid=".$yearfinalreportperiodid." and s.entityledgeraccountid=".$entityledgeraccountid." and s.entitysubledgeraccountid=".$entitysubledgeraccountid." and v.entityvoucherdatetime='".$entityvoucherdatetime."'";                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {

            $query = "select entitysubledgerid,openingbalance,debit,credit,closingbalance from entitysubledger v where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.finalreportperiodid=".$finalreportperiodid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.entitysubledgeraccountid=".$entitysubledgeraccountid." and v.subledgerstartdatetime='".$entityvoucherdatetime."' and v.subledgerenddatetime='".$entityvoucherdatetime."'";
            mysqli_query($connection,'SET NAMES UTF8');
            $result1 = mysqli_query($connection,$query);
            if ($row1 = @mysqli_fetch_assoc($result1))
            {
                if (isset($row['debit']))
                {
                    $cur_debit = $row['debit'];    
                }
                else
                {
                    $cur_debit = 0;
                }
                if (isset($row['credit']))
                {
                    $cur_credit = $row['credit'];    
                }
                else
                {
                    $cur_credit = 0;
                }
                $diff = $cur_debit - $row1['debit'] + $cur_credit - $row1['credit'];
                if ($isplledger == 0)
                {
                    if ($finalreportperiodid == $yearfinalreportperiodid)
                    {
                        $query = "update entitysubledger v set debit=".$cur_debit.",credit=".$cur_credit.",closingbalance=closingbalance+".$diff." where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.entitysubledgeraccountid=".$entitysubledgeraccountid." and v.subledgerstartdatetime='".$entityvoucherdatetime."' and v.subledgerenddatetime='".$entityvoucherdatetime."' and finalreportperiodid in (select f.finalreportperiodid from finalreportperiod f where finalreportperiodcategoryid=134578598)";
                    }
                    else
                    {
                        $query = "update entitysubledger v set debit=".$cur_debit.",credit=".$cur_credit.",closingbalance=closingbalance+".$diff." where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.entitysubledgeraccountid=".$entitysubledgeraccountid." and v.subledgerstartdatetime='".$entityvoucherdatetime."' and v.subledgerenddatetime='".$entityvoucherdatetime."' and finalreportperiodid in (select f.finalreportperiodid from finalreportperiod f where finalreportperiodcategoryid=235425471)";
                    }
                }
                else
                {
                    $query = "update entitysubledger v set debit=".$cur_debit.",credit=".$cur_credit.",closingbalance=closingbalance+".$diff." where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.entitysubledgeraccountid=".$entitysubledgeraccountid." and v.subledgerstartdatetime='".$entityvoucherdatetime."' and v.subledgerenddatetime='".$entityvoucherdatetime."' and finalreportperiodid=".$finalreportperiodid;                    
                }
                
                if (mysqli_query($connection, $query))
                {
                    if ($isplledger == 0)
                    {
                        if ($finalreportperiodid == $yearfinalreportperiodid)
                        {
                            $query = "update entitysubledger v set openingbalance=openingbalance+".$diff.",closingbalance=closingbalance+".$diff." where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.entitysubledgeraccountid=".$entitysubledgeraccountid." and v.subledgerstartdatetime>'".$entityvoucherdatetime."' and finalreportperiodid in (select f.finalreportperiodid from finalreportperiod f where finalreportperiodcategoryid=134578598)";
                        }
                        else
                        {
                            $query = "update entitysubledger v set openingbalance=openingbalance+".$diff.",closingbalance=closingbalance+".$diff." where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.entitysubledgeraccountid=".$entitysubledgeraccountid." and v.subledgerstartdatetime>'".$entityvoucherdatetime."' and finalreportperiodid in (select f.finalreportperiodid from finalreportperiod f where finalreportperiodcategoryid=235425471)";
                        }
                    }
                    else
                    {
                        $query = "update entitysubledger v set openingbalance=openingbalance+".$diff.",closingbalance=closingbalance+".$diff." where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.entitysubledgeraccountid=".$entitysubledgeraccountid." and v.subledgerstartdatetime>'".$entityvoucherdatetime."' and finalreportperiodid=".$finalreportperiodid;                        
                    }
                    if (mysqli_query($connection, $query))
                    {
                        return 1;
                    }
                    else
                    {
                        return 0;
                    }
                }
                else
                {
                    return 0;
                }
            }
        }
    }

    function isvoucherverified($entityvoucherid)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
          {
            echo "Communication Error1";
          }
        $query = "select coalesce(balance,0) balance from (select entityvoucherid,sum(case when debitcredit=157489650 then amount when debitcredit=357481241 then amount * -1 end) balance from (select entityvoucherid,debitcredit,sum(amount) amount from entityvoucherledgeraccountdetail l where active=1 group by entityvoucherid,debitcredit)k group by entityvoucherid) j where j.entityvoucherid=".$entityvoucherid;                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            if ($row['balance'] == 0)
            {
                return 1;
            }
            else
            {
                return 0;
            }
        }
        else
        {
            return 0;
        }
    }

    function isvouchervalid($entityvoucherid)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
          {
            echo "Communication Error1";
          }
        $query = "select isvalid from entityvoucher where active=1 and j.entityvoucherid=".$entityvoucherid;                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            return $row['isvalid'];
        }
        else
        {
            return 0;
        }
    }

function updateopeningbalancemainledgerdebitcredit(&$connection,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entityopeningdatetime)
    {
        $query = "select * from finalreportperiod r where r.active=1 and r.finalreportperiodid=".$finalreportperiodid;                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result11 = mysqli_query($connection,$query);
        while ($row11 = @mysqli_fetch_assoc($result11))
        {
            $retcode = ismainledgerpresent($entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entityopeningdatetime);
            if ($retcode == 0)
            {
                $retcode1=insertmainledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entityopeningdatetime);
            }
            elseif ($retcode == 1)
            {
                $retcode1=insertmainledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entityopeningdatetime);
            }
            elseif ($retcode == 2)
            {
                $retcode1=insertmainledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entityopeningdatetime);
            }
        }
        $isplledger = isplledger($entityledgeraccountid);
        $query = "select sum(case when v.debitcredit = 157489650 then v.amount else 0 end) as debit, sum(case when v.debitcredit = 357481241 then v.amount * -1 else 0 end) as credit from entityledgeraccountopening v where v.active=1 and v.isvalid=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.finalreportperiodid=".$finalreportperiodid." and v.entityledgeraccountid=".$entityledgeraccountid;                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            $query = "select openingbalance,debit,credit,closingbalance from entitymainledger v where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.finalreportperiodid=".$finalreportperiodid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.mainledgerstartdatetime='".$entityopeningdatetime."' and v.mainledgerenddatetime='".$entityopeningdatetime."'";
            mysqli_query($connection,'SET NAMES UTF8');
            $result1 = mysqli_query($connection,$query);
            if ($row1 = @mysqli_fetch_assoc($result1))
            {
                if (isset($row['debit']))
                {
                    $cur_debit = $row['debit'];    
                }
                else
                {
                    $cur_debit = 0;
                }
                if (isset($row['credit']))
                {
                    $cur_credit = $row['credit'];    
                }
                else
                {
                    $cur_credit = 0;
                }
                $diff = $cur_debit + $cur_credit - $row1['openingbalance'];
                if ($isplledger == 0)
                {
                    $query = "update entitymainledger v set openingbalance=".($cur_debit + $cur_credit).",closingbalance=".($cur_debit + $cur_credit)."+debit+credit where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.mainledgerstartdatetime='".$entityopeningdatetime."' and v.mainledgerenddatetime='".$entityopeningdatetime."'";                    
                }
                else
                {
                    $query = "update entitymainledger v set openingbalance=".($cur_debit + $cur_credit).",closingbalance=".($cur_debit + $cur_credit)."+debit+credit where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.mainledgerstartdatetime='".$entityopeningdatetime."' and v.mainledgerenddatetime='".$entityopeningdatetime."' and finalreportperiodid=".$finalreportperiodid;                                        
                }
                if (mysqli_query($connection, $query))
                {
                    if ($isplledger == 0)
                    {
                        $query = "update entitymainledger v set openingbalance=openingbalance+".$diff.",closingbalance=closingbalance+".$diff." where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.mainledgerstartdatetime>'".$entityopeningdatetime."'";                
                    }
                    else
                    {
                        $query = "update entitymainledger v set openingbalance=openingbalance+".$diff.",closingbalance=closingbalance+".$diff." where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.mainledgerstartdatetime>'".$entityopeningdatetime."' and finalreportperiodid=".$finalreportperiodid;                
                    }
                    if (mysqli_query($connection, $query))
                    {
                        return 1;
                    }
                    else
                    {
                        return 0;
                    }
                }
                else
                {
                    return 0;
                }
            }
        }
    }


function updateopeningbalancesubledgerdebitcredit(&$connection,$entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid,$entitysubledgeraccountid,$entityopeningdatetime)
    {
        $query = "select * from finalreportperiod r where r.active=1 and r.finalreportperiodid=".$finalreportperiodid;                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result11 = mysqli_query($connection,$query);
        while ($row11 = @mysqli_fetch_assoc($result11))
        {
            $retcode = issubledgerpresent($entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entitysubledgeraccountid,$entityopeningdatetime);
            if ($retcode == 0)
            {
                $retcode1=insertsubledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entitysubledgeraccountid,$entityopeningdatetime);
            }
            elseif ($retcode == 1)
            {
                $retcode1=insertsubledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entitysubledgeraccountid,$entityopeningdatetime);
            }
            elseif ($retcode == 2)
            {
                $retcode1=insertsubledger($connection,$entityglobalgroupid,$row11['finalreportperiodid'],$entityledgeraccountid,$entitysubledgeraccountid,$entityopeningdatetime);
            }
        }
        $isplledger = isplledger($entityledgeraccountid);
        $query = "select sum(case when v.debitcredit = 157489650 then v.amount else 0 end) as debit, sum(case when v.debitcredit = 357481241 then v.amount * -1 else 0 end) as credit from entitysubledgeraccountopening v,entityledgeraccountopening l where v.entityledgeraccountopeningid=l.entityledgeraccountopeningid and  v.active=1 and v.isvalid=1 and l.active=1 and l.entityglobalgroupid=".$entityglobalgroupid." and l.finalreportperiodid=".$finalreportperiodid." and l.entityledgeraccountid=".$entityledgeraccountid." and v.entitysubledgeraccountid=".$entitysubledgeraccountid;                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            $query = "select openingbalance,debit,credit,closingbalance from entitysubledger v where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.finalreportperiodid=".$finalreportperiodid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.entitysubledgeraccountid=".$entitysubledgeraccountid." and v.subledgerstartdatetime='".$entityopeningdatetime."' and v.subledgerenddatetime='".$entityopeningdatetime."'";
            mysqli_query($connection,'SET NAMES UTF8');
            $result1 = mysqli_query($connection,$query);
            if ($row1 = @mysqli_fetch_assoc($result1))
            {
                if (isset($row['debit']))
                {
                    $cur_debit = $row['debit'];    
                }
                else
                {
                    $cur_debit = 0;
                }
                if (isset($row['credit']))
                {
                    $cur_credit = $row['credit'];    
                }
                else
                {
                    $cur_credit = 0;
                }
                $diff = $cur_debit + $cur_credit - $row1['openingbalance'];
                if ($isplledger == 0)
                {
                    $query = "update entitysubledger v set openingbalance=".($cur_debit + $cur_credit).",closingbalance=".($cur_debit + $cur_credit)."+debit+credit where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.entitysubledgeraccountid=".$entitysubledgeraccountid." and v.subledgerstartdatetime='".$entityopeningdatetime."' and v.subledgerenddatetime='".$entityopeningdatetime."'";                
                }
                else
                {
                    $query = "update entitysubledger v set openingbalance=".($cur_debit + $cur_credit).",closingbalance=".($cur_debit + $cur_credit)."+debit+credit where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.entitysubledgeraccountid=".$entitysubledgeraccountid." and v.subledgerstartdatetime='".$entityopeningdatetime."' and v.subledgerenddatetime='".$entityopeningdatetime."' and finalreportperiodid=".$finalreportperiodid;                                    
                }
                if (mysqli_query($connection, $query))
                {
                    if ($isplledger == 0)
                    {
                        $query = "update entitysubledger v set openingbalance=openingbalance+".$diff.",closingbalance=closingbalance+".$diff." where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.entitysubledgeraccountid=".$entitysubledgeraccountid." and v.subledgerstartdatetime>'".$entityopeningdatetime."'";                
                    }
                    else
                    {
                        $query = "update entitysubledger v set openingbalance=openingbalance+".$diff.",closingbalance=closingbalance+".$diff." where v.active=1 and v.entityglobalgroupid=".$entityglobalgroupid." and v.entityledgeraccountid=".$entityledgeraccountid." and v.entitysubledgeraccountid=".$entitysubledgeraccountid." and v.subledgerstartdatetime>'".$entityopeningdatetime."' and finalreportperiodid=".$finalreportperiodid;                                        
                    }
                    if (mysqli_query($connection, $query))
                    {
                        return 1;
                    }
                    else
                    {
                        return 0;
                    }
                }
                else
                {
                    return 0;
                }
            }
        }
    }

    function getsubledgeropbalsum($entityledacid)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
          {
            echo "Communication Error";
          }
        $query = "SELECT sum(case when e.debitcredit = 157489650 then e.amount when e.debitcredit = 357481241 then e.amount*-1 end) sumamt FROM entitysubledgeraccountopening e,entityledgeraccountopening b,entityledgeraccount a where b.entityledgeraccountid=a.entityledgeraccountid and a.active=1 and e.active=1 and b.active=1 and e.entityledgeraccountopeningid=b.entityledgeraccountopeningid and b.entityledgeraccountid=".$entityledacid." and b.finalreportperiodid=".$_SESSION["finalreportperiodid"];                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            return $row['sumamt'];
        }
        else
        {
            echo "Communication Error76";
            return 0;
        }
    }

    function getledgersubledgeropeningdiff($entityglobalgroupid,$finalreportperiodid,$entityledgeraccountid)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
          {
            echo "Communication Error";
          }
        $query = "SELECT sum(case when e.debitcredit = 157489650 then e.amount when e.debitcredit = 357481241 then e.amount*-1 else 0 end) sumamt from entityledgeraccountopening e where active=1 and e.globalgroupid and e.entityledgeraccountid=".$entityledgeraccountid." and e.finalreportperiodid=".$_SESSION["finalreportperiodid"];                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            $diff = $row['sumamt'] - getopeningsumwithsign($entityledacid);
            return diff;
        }
        else
        {
            echo "Communication Error77";
            return 0;
        }
    }

    function isopeningbalanceverified($entityledgeraccountopeningid)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
          {
            echo "Communication Error1";
          }
        $query = "select a.entityledgeraccountid,a.subledgerallowed from entityledgeraccount a,entityledgeraccountopening b where a.active=1 and a.entityledgeraccountid=b.entityledgeraccountid and b.entityledgeraccountopeningid=".$entityledgeraccountopeningid;                          
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection, $query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            if ($row['subledgerallowed'] == 1)
            {
                return 1;
            }
            else
            {
                return 1;
            }
        }
    }

    function isopeningbalancevalid($entityledgeraccountopeningid)
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
          {
            echo "Communication Error1";
          }
        $query = "select isvalid from entityledgeraccountopening where active=1 and j.entityledgeraccountopeningid=".$entityledgeraccountopeningid;
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            return $row['isvalid'];
        }
        else
        {
            return 0;
        }
    }

    function openingbalancevalidation()
    {
        require("../info/phpsqlajax_dbinfo.php");
        $connection = mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        $connection -> autocommit(FALSE);
        mysqli_query($connection,'SET NAMES UTF8');
        $query = "select v.*,p.perioddatetimefrom from entityledgeraccountopening v,finalreportperiod p where v.finalreportperiodid=p.finalreportperiodid and p.active=1 and ((v.active=1 and v.isattended = 0) or (v.active=0 and v.isattended = 1)) order by transactionid";
        $result = mysqli_query($connection, $query);
        while ($row = @mysqli_fetch_assoc($result))
        {
            if ((isopeningbalanceverified($row['entityledgeraccountopeningid']) == 1 and $row['active'] == 1) or ($row['active'] == 0))
            {
                if ((isopeningbalancevalid($row['entityledgeraccountopeningid']) == 0 and $row['active'] == 1) or ($row['active'] == 0))
                {
                    $query = "update entityledgeraccountopening set isvalid=1,isattended=isattended+1 where transactionid=".$row['transactionid'];
                    if (mysqli_query($connection,$query))
                    {
                        if ($row['active'] == 0)
                        {
                            $query = "select transactionid,entityledgeraccountid from entityledgeraccountopening l where transactionid = ".$row['transactionid'];    
                        }
                        elseif ($row['active'] == 1)
                        {
                            $query = "select transactionid,entityledgeraccountid from entityledgeraccountopening l where active=1 and transactionid = ".$row['transactionid'];    
                        }
                        
                        mysqli_query($connection,'SET NAMES UTF8');
                        $result1 = mysqli_query($connection,$query);
                        $status = 0;
                        while ($row1 = @mysqli_fetch_assoc($result1))
                        {
                            if (updateopeningbalancemainledgerdebitcredit($connection,$row['entityglobalgroupid'],$row['finalreportperiodid'],$row1['entityledgeraccountid'],$row['perioddatetimefrom'])==1)
                            {    
                                $status = 1;
                            }
                            else
                            {
                                $status = 0;
                                exit;
                            }
                            $query = "select s.*,p.perioddatetimefrom from entitysubledgeraccountopening s,entityledgeraccountopening v,finalreportperiod p where s.entityledgeraccountopeningid=v.entityledgeraccountopeningid and v.finalreportperiodid=p.finalreportperiodid and p.active=1 and ((s.active=1 and s.isattended = 0) or (s.active=0 and s.isattended = 1)) order by transactionid";
                            $result2 = mysqli_query($connection, $query);
                            while ($row2 = @mysqli_fetch_assoc($result2))
                            {
                                $query = "update entitysubledgeraccountopening set isvalid=1,isattended=isattended+1 where transactionid=".$row2['transactionid'];      
                                if (mysqli_query($connection,$query))
                                {
                                    if ($row2['active'] == 0)
                                    {
                                        $query = "select transactionid,entitysubledgeraccountid from entitysubledgeraccountopening l where transactionid = ".$row2['transactionid'];    
                                    }
                                    elseif ($row2['active'] == 1)
                                    {
                                        $query = "select transactionid,entitysubledgeraccountid from entitysubledgeraccountopening l where active=1 and transactionid = ".$row2['transactionid'];    
                                    }
                                    $result3 = mysqli_query($connection,$query);
                                    $status = 0;
                                    while ($row3 = @mysqli_fetch_assoc($result3))
                                    {
                                        if (updateopeningbalancesubledgerdebitcredit($connection,$row['entityglobalgroupid'],$row['finalreportperiodid'],$row['entityledgeraccountid'],$row3['entitysubledgeraccountid'],$row['perioddatetimefrom'])==1)
                                        {    
                                            $status = 1;
                                        }
                                        else
                                        {
                                            $status = 0;
                                            exit;
                                        }
                                    }
                                }
                            }
                        }
                        if ($status == 1)
                        {
                            if (updatepl($connection,$row['entityglobalgroupid'],$row['finalreportperiodid'],$row['perioddatetimefrom'])==1)
                            {    
                                $status = 1;
                            }
                            else
                            {
                                $status = 0;
                                exit;
                            }    
                        }
                    }
                    else
                    {
                        $status = 0;
                    }

                    if ($status == 1)
                    {
                        $connection -> commit();
                    }
                    else
                    {
                        $connection -> rollback();
                    }
                }
            }
            else
            {
                $query = "update entityledgeraccountopening set isvalid=0,isattended=1 where active=1 and transactionid=".$row['transactionid'];
                if (mysqli_query($connection, $query))
                {
                    $connection -> commit();
                }
                else
                {
                    $connection -> rollback();  
                }
            }
        }
    }

    function quarterfinalreportperiod(&$connection,$entityglobalgroupid,$yearfinalreportperiodid,$entityvoucherdatetime)
    {
        $query = "select f.finalreportperiodid from quarter q, finalreportperiod f where q.active=1 and f.active=1 and q.entityglobalgroupid=$entityglobalgroupid and q.yearfinalreportperiodid=$yearfinalreportperiodid and q.quarterfinalreportperiodid=f.finalreportperiodid and perioddatetimefrom<='$entityvoucherdatetime' and perioddatetimeto>='$entityvoucherdatetime'";
        //echo $query;
        $result = mysqli_query($connection, $query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            return $row['finalreportperiodid'];
        }
        else
        {
            return 0;
        }
    }   

    function isfirstfinancialyear(&$connection,$entityglobalgroupid,$finalreportperiodid)
    {
        $query = "select count(*) as cnt from financialyear f where f.active=1 and f.entityglobalgroupid=".$entityglobalgroupid." and f.finalreportperiodid <=".$finalreportperiodid;
        $result = mysqli_query($connection,$query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            if ($row['cnt']==1)
            {
                return 1;
            }
            else
            {
                return 0;
            }
        }
        else
        {
            echo 'Communication Error';
            return -1;
        }
    }

    function checkquarterexists(&$connection,$entityglobalgroupid,$yearfinalreportperiodid)
    {
        $query = "select count(*) as cnt from quarter f where f.active=1 and f.entityglobalgroupid=".$entityglobalgroupid." and f.yearfinalreportperiodid=".$yearfinalreportperiodid;
        //echo $query;
        $result = mysqli_query($connection, $query);
        if ($row = mysqli_fetch_assoc($result))
        {
            if ($row['cnt']>0)
            {
                return 1;
            }
            else
            {
                return 0;
            }
        }
    }
    
    function insertquarterperiod(&$connection,$newperioddatetimefrom,$newperioddatetimeto,$yearfinalreportperiodid,$quarterfinalreportperiodid,$quarterid,$periodno)
    {
        $newperiodname_eng = date('FY',strtotime($newperioddatetimefrom)).'-'.date('FY',strtotime($newperioddatetimeto));
        $quarterfinalreportperiodid = $quarterfinalreportperiodid+21457;
        $quarterid = $quarterid+635417;
        $query = "select count(*) as cnt from finalreportperiod f where f.active=1 and f.finalreportperiodid=".$quarterfinalreportperiodid;
        $result = mysqli_query($connection, $query);
        if ($row = mysqli_fetch_assoc($result))
        {
            if ($row['cnt']>0)
            {
                $query = "insert into quarter(quarterid,entityglobalgroupid,yearfinalreportperiodid,quarterfinalreportperiodid,active,cruserid,crdatetime) values (".$quarterid.",".$_SESSION['entityglobalgroupid'].",$yearfinalreportperiodid,$quarterfinalreportperiodid,1,".$_SESSION["usersid"].",'".currentdatetime()."')";
                //echo $query;
                if (mysqli_query($connection, $query)) 
                {
                    return 1;
                }
                else
                {
                    return 0;
                }
            }
            else
            {
                $query = "insert into finalreportperiod(finalreportperiodid,finalreportperiodcategoryid,perioddatetimefrom,perioddatetimeto,periodname_eng,periodnumber,active,cruserid,crdatetime) values (".$quarterfinalreportperiodid.",235425471,'".$newperioddatetimefrom."','".$newperioddatetimeto."','".$newperiodname_eng."',".$periodno.",1,".$_SESSION["usersid"].",'".currentdatetime()."')";
                //echo $query;
                if (mysqli_query($connection, $query)) 
                {
                    $query = "insert into quarter(quarterid,entityglobalgroupid,yearfinalreportperiodid,quarterfinalreportperiodid,active,cruserid,crdatetime) values (".$quarterid.",".$_SESSION['entityglobalgroupid'].",$yearfinalreportperiodid,$quarterfinalreportperiodid,1,".$_SESSION["usersid"].",'".currentdatetime()."')";
                    //echo $query;
                    if (mysqli_query($connection, $query)) 
                    {
                        return 1;
                    }
                    else
                    {
                        return 0;
                    }
                }
                else
                {
                    return 0;
                }
            }
        }
    }

    function insertquarters(&$connection,$yearfinalreportperiodid)
    {
        $result1 = mysqli_query($connection, "select coalesce(max(quarterid),657451254) as quarterid from quarter where active=1");
        $row1 = mysqli_fetch_assoc($result1);
        if ($row1["quarterid"] == 0)
        {
            echo'<span style="background-color:#f44;color:#ff8;text-align:left;">Communication Error2</span>';
            exit;
        }
        $query2 = "select * from finalreportperiod f where f.active=1 and f.finalreportperiodid=".$yearfinalreportperiodid;
        //echo $query2;
        $result2 = mysqli_query($connection, $query2);
        if ($row2 = mysqli_fetch_assoc($result2))
        {
            $newperioddatetimefrom = $row2['perioddatetimefrom'];
            $newperioddatetimeto = date('Y-m-d H:i:s',strtotime($row2['perioddatetimefrom'].' + 3 months'));
            $newperioddatetimeto = date('Y-m-d H:i:s',strtotime($newperioddatetimeto.' - 1 days'));
            $newperiodname_eng = date('FY',strtotime($newperioddatetimefrom)).'-'.date('FY',strtotime($newperioddatetimeto));
            $quarterfinalreportperiodid = $yearfinalreportperiodid+21457;
            $quarterid = $row1['quarterid']+635417;
            $periodno=1;
            $retcode = insertquarterperiod($connection,$newperioddatetimefrom,$newperioddatetimeto,$yearfinalreportperiodid,$quarterfinalreportperiodid,$quarterid,$periodno);
            //echo 'quarter retcode='.$retcode;
            if ($retcode == 1) 
            {
                $newperioddatetimefrom = date('Y-m-d H:i:s',strtotime($newperioddatetimeto.' + 1 days'));
                $newperioddatetimeto = date('Y-m-d H:i:s',strtotime($newperioddatetimefrom.' + 3 months'));
                $newperioddatetimeto = date('Y-m-d H:i:s',strtotime($newperioddatetimeto.' - 1 days'));
                $newperiodname_eng = date('FY',strtotime($newperioddatetimefrom)).'-'.date('FY',strtotime($newperioddatetimeto));
                $quarterfinalreportperiodid = $quarterfinalreportperiodid+21457;
                $quarterid = $row1['quarterid']+635417;
                $periodno++;
                $retcode = insertquarterperiod($connection,$newperioddatetimefrom,$newperioddatetimeto,$yearfinalreportperiodid,$quarterfinalreportperiodid,$quarterid,$periodno);
                if ($retcode == 1) 
                {
                    $newperioddatetimefrom = date('Y-m-d H:i:s',strtotime($newperioddatetimeto.' + 1 days'));
                    $newperioddatetimeto = date('Y-m-d H:i:s',strtotime($newperioddatetimefrom.' + 3 months'));
                    $newperioddatetimeto = date('Y-m-d H:i:s',strtotime($newperioddatetimeto.' - 1 days'));
                    $newperiodname_eng = date('FY',strtotime($newperioddatetimefrom)).'-'.date('FY',strtotime($newperioddatetimeto));
                    $quarterfinalreportperiodid = $quarterfinalreportperiodid+21457;
                    $quarterid = $row1['quarterid']+635417;
                    $periodno++;
                    $retcode = insertquarterperiod($connection,$newperioddatetimefrom,$newperioddatetimeto,$yearfinalreportperiodid,$quarterfinalreportperiodid,$quarterid,$periodno);
                    if ($retcode == 1) 
                    {
                        $newperioddatetimefrom = date('Y-m-d H:i:s',strtotime($newperioddatetimeto.' + 1 days'));
                        $newperioddatetimeto = date('Y-m-d H:i:s',strtotime($newperioddatetimefrom.' + 3 months'));
                        $newperioddatetimeto = date('Y-m-d H:i:s',strtotime($newperioddatetimeto.' - 1 days'));
                        $newperiodname_eng = date('FY',strtotime($newperioddatetimefrom)).'-'.date('FY',strtotime($newperioddatetimeto));
                        $quarterfinalreportperiodid = $quarterfinalreportperiodid+21457;
                        $quarterid = $row1['quarterid']+635417;
                        $periodno++;
                        $retcode = insertquarterperiod($connection,$newperioddatetimefrom,$newperioddatetimeto,$yearfinalreportperiodid,$quarterfinalreportperiodid,$quarterid,$periodno);
                        if ($retcode == 1) 
                        {
                            return 1;
                        }
                        else
                        {
                            return 0;
                        }
                    }
                    else
                    {
                        return 0;
                    }
                }
                else
                {
                    return 0;
                }
            }
            else
            {
                return 0;
            }
        }
        else
        {
            return 0;
        }
    }

    function vouchervalidation()
    {
        require("../info/phpsqlajax_dbinfo.php");
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        $connection -> autocommit(FALSE);
        mysqli_query($connection,'SET NAMES UTF8');
        $retcode = isfirstfinancialyear($connection,$_SESSION['entityglobalgroupid'],$_SESSION['finalreportperiodid']);
        //echo 'isfirstfinancialyear='.$retcode;
        if ($retcode==1)
        {
            if (checkquarterexists($connection,$_SESSION['entityglobalgroupid'],$_SESSION['finalreportperiodid'])==0)
            {
                $retcode = insertquarters($connection,$_SESSION['finalreportperiodid']);
            }
            else
            {
                $retcode = 1;
            }
        }
        openingbalancevalidation();
        $query = "select * from entityvoucher v where ((active=1 and v.isattended = 0) or (active=0 and v.isattended=1)) and vouchernumber is not null order by transactionid";
        $result = mysqli_query($connection, $query);
        while ($row = @mysqli_fetch_assoc($result))
        {
            if ((isvoucherverified($row['entityvoucherid']) == 1 and $row['active'] == 1) or ($row['active'] == 0))
            {
                if ((isvouchervalid($row['entityvoucherid']) == 0 and $row['active'] == 1) or ($row['active'] == 0))
                {
                    $query = "update entityvoucher set isvalid=1,isattended=isattended+1 where transactionid=".$row['transactionid'];
                    if (mysqli_query($connection, $query))
                    {
                        if ($row['active'] == 0)
                        {
                            $query="select entityledgeraccountid from entityvoucherledgeraccountdetail l where active=0 and entityvoucherid = ".$row['entityvoucherid']." group by l.entityledgeraccountid";    
                        }
                        else
                        {
                            $query="select entityledgeraccountid from entityvoucherledgeraccountdetail l where active=1 and entityvoucherid = ".$row['entityvoucherid']." group by entityledgeraccountid";    
                        }
                        mysqli_query($connection,'SET NAMES UTF8');
                        $result1 = mysqli_query($connection,$query);
                        $status = 0;
                        $quarterfinalreportperiodid = quarterfinalreportperiod($connection,$row['entityglobalgroupid'],$row['finalreportperiodid'],$row['entityvoucherdatetime']);
                        //echo 'entityvoucherdatetime'.$row['entityvoucherdatetime'].'&quarterfinalreportperiodid'.$quarterfinalreportperiodid.'</br>';

                        while ($row1 = @mysqli_fetch_assoc($result1))
                        {
                            if (updatemainledgerdebitcredit($connection,$row['entityglobalgroupid'],$row['finalreportperiodid'],$row1['entityledgeraccountid'],$row['entityvoucherdatetime'],$row['finalreportperiodid'])==1)
                            {    
                                if (updatemainledgerdebitcredit($connection,$row['entityglobalgroupid'],$quarterfinalreportperiodid,$row1['entityledgeraccountid'],$row['entityvoucherdatetime'],$row['finalreportperiodid'])==1)
                                {
                                    $status = 1;
                                }
                                else
                                {
                                    $status = 0;   
                                }
                            }
                            else
                            {
                                $status = 0;
                                exit;
                            }
                        }

                        if ($row['active'] == 0)
                        {
                            $query="select l.entityledgeraccountid,l.entitysubledgeraccountid from entityvouchersubledgeraccountdetail l,entityvoucherledgeraccountdetail e where l.active=0 and l.entityvoucherledgeraccountdetailid=e.entityvoucherledgeraccountdetailid and entityvoucherid = ".$row['entityvoucherid']." group by l.entityledgeraccountid,l.entitysubledgeraccountid";    
                        }
                        else
                        {
                            $query="select l.entityledgeraccountid,l.entitysubledgeraccountid from entityvouchersubledgeraccountdetail l,entityvoucherledgeraccountdetail e where l.active=1 and l.entityvoucherledgeraccountdetailid=e.entityvoucherledgeraccountdetailid and entityvoucherid = ".$row['entityvoucherid']." group by l.entityledgeraccountid,l.entitysubledgeraccountid";    
                        }
                        //echo $query.'</br>';
                        mysqli_query($connection,'SET NAMES UTF8');
                        $result1 = mysqli_query($connection,$query);
                        
                        while ($row1 = @mysqli_fetch_assoc($result1))
                        {
                            $status = 0;
                            if (updatesubledgerdebitcredit($connection,$row['entityglobalgroupid'],$row['finalreportperiodid'],$row1['entityledgeraccountid'],$row1['entitysubledgeraccountid'],$row['entityvoucherdatetime'],$row['finalreportperiodid'])==1)
                            {    
                                if (updatesubledgerdebitcredit($connection,$row['entityglobalgroupid'],$quarterfinalreportperiodid,$row1['entityledgeraccountid'],$row1['entitysubledgeraccountid'],$row['entityvoucherdatetime'],$row['finalreportperiodid'])==1)
                                {
                                    $status = 1;
                                }
                                else
                                {
                                    $status = 0;   
                                }
                            }
                            else
                            {
                                $status = 0;
                                exit;
                            }
                        }

                        if ($status == 1)
                        {
                            if (updatepl($connection,$row['entityglobalgroupid'],$row['finalreportperiodid'],$row['entityvoucherdatetime'])==1)
                            {    
                                if (updatepl($connection,$row['entityglobalgroupid'],$quarterfinalreportperiodid,$row['entityvoucherdatetime'])==1)    
                                {
                                    $status = 1;
                                }
                                else
                                {
                                    $status = 0;
                                }
                            }
                            else
                            {
                                $status = 0;
                                exit;
                            } 
                        }
                    }
                    else
                    {
                        $status = 0;
                    }

                    if ($status == 1)
                    {
                        $connection -> commit();
                    }
                    else
                    {
                        $connection -> rollback();
                    }
                }
            }
            else
            {
                $query = "update entityvoucher set isvalid=0,isattended=1 where active=1 and entityvoucherid=".$row['entityvoucherid'];
                if (mysqli_query($connection, $query))
                {
                    $connection -> commit();
                }
                else
                {
                    $connection -> rollback();  
                }
            }
        }
    }

    function mainledgerunpost(&$connection,$entityglobalgroupid,$finalreportperiodid)
    {
        $query = "update entitymainledger set active=0 where active=1 and entityglobalgroupid=".$entityglobalgroupid." and finalreportperiodid=".$finalreportperiodid;
        if (mysqli_query($connection, $query))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    function subledgerunpost(&$connection,$entityglobalgroupid,$finalreportperiodid)
    {
        $query = "update entitysubledger set active=0 where active=1 and entityglobalgroupid=".$entityglobalgroupid." and finalreportperiodid=".$finalreportperiodid;
        if (mysqli_query($connection, $query))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    function finyearbalancecarryforward($entityglobalgroupid,$finalreportperiodid)
    {
        require("../info/phpsqlajax_dbinfo.php");
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        $connection -> autocommit(FALSE);
        mysqli_query($connection,'SET NAMES UTF8');
        $query = "update financialyear set isbalancecarriedforward=-1 where entityglobalgroupid=".$entityglobalgroupid." and finalreportperiodid=".$finalreportperiodid;
        if (mysqli_query($connection, $query))
        {
            $retcode = carryforwardbalance($connection,$entityglobalgroupid);
            if ($retcode == 1)
            {
                $connection -> commit();
                echo 'Successfully Fin Year Balance carryforwared</br>';
            }
            else
            {
                $connection->rollback();
                echo 'Communication error';
            }
        }
    }

    function voucherrepost($entityglobalgroupid,$finalreportperiodid)
    {
        require("../info/phpsqlajax_dbinfo.php");
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        $connection -> autocommit(FALSE);
        mysqli_query($connection,'SET NAMES UTF8');
        $query = "update entityvoucher set isattended=0 where entityglobalgroupid=".$entityglobalgroupid." and finalreportperiodid=".$finalreportperiodid;
        if (mysqli_query($connection, $query))
        {
            $connection -> commit();
            vouchervalidation();
            echo 'Successfully Voucher Reposted</br>';
        }
        else
        {
            $connection->rollback();
            echo 'Communication error';
        }
    }



    function ledgeraccountopeningbalance($entityledgeraccountid,$opdate)
    {
        require("../info/phpsqlajax_dbinfo.php");

        $query = "select perioddatetimefrom,perioddatetimeto from finalreportperiod where active=1 and finalreportperiodid=".$_SESSION['finalreportperiodid'];
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);
        if (isset($row['perioddatetimefrom']))
        {
            $perioddatetimefrom = $row['perioddatetimefrom'];
            $perioddatetimeto = $row['perioddatetimeto'];
            $query1 = "select debitcredit,amount from entityledgeraccountopening where active=1 and finalreportperiodid=".$_SESSION['finalreportperiodid']." and entityledgeraccountid=".$entityledgeraccountid;
            $result1 = mysqli_query($connection, $query1);
            $row1 = mysqli_fetch_assoc($result1);
            $opbal = 0;
            if (isset($row1['debitcredit']))
            {
                if ($debitcredit == 157489650)
                {
                    $opbal = $row1['amount'];
                }
                elseif ($debitcredit == 357481241)
                {
                    $opbal = $row1['amount'] * -1;
                }
            }
            $query2 = "select sum(case when d.debitcredit=157489650 then d.amount when d.debitcredit=357481241 then d.amount *-1 end) amount from entityvoucher v, entityvoucherledgeraccountdetail d where v.active=1 and d.active=1 and v.entityvoucherid=d.entityvoucherid and v.finalreportperiodid=".$_SESSION['finalreportperiodid']." and d.entityledgeraccountid=".$entityledgeraccountid." and entityvoucherdatetime >='".$perioddatetimefrom."' and entityvoucherdatetime <'".$opdate."' and entityvoucherdatetime <='".$perioddatetimeto."'";
            $result2 = mysqli_query($connection, $query2);
            $row2 = mysqli_fetch_assoc($result2);
            if (isset($row2['amount']))
            {
                $opbal = $opbal + $row2['amount'];
            }
            return $opbal;
        }
        else
        {
            return 0;
        }
    }
    
    function ledgeraccountclosingbalance($entityledgeraccountid,$closdate)
    {
        require("../info/phpsqlajax_dbinfo.php");

        $query = "select perioddatetimefrom,perioddatetimeto from finalreportperiod where active=1 and finalreportperiodid=".$_SESSION['finalreportperiodid'];
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        mysqli_query($connection,'SET NAMES UTF8');
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);
        if (isset($row['perioddatetimefrom']))
        {
            $perioddatetimefrom = $row['perioddatetimefrom'];
            $perioddatetimeto = $row['perioddatetimeto'];
            $query1 = "select debitcredit,amount from entityledgeraccountopening where active=1 and finalreportperiodid=".$_SESSION['finalreportperiodid']." and entityledgeraccountid=".$entityledgeraccountid;
            $result1 = mysqli_query($connection, $query1);
            $row1 = mysqli_fetch_assoc($result1);
            $closbal = 0;
            if (isset($row1['debitcredit']))
            {
                if ($debitcredit == 157489650)
                {
                    $closbal = $row1['amount'];
                }
                elseif ($debitcredit == 357481241)
                {
                    $closbal = $row1['amount'] * -1;
                }
            }
            $query2 = "select sum(case when d.debitcredit=157489650 then d.amount when d.debitcredit=357481241 then d.amount *-1 end) amount from entityvoucher v, entityvoucherledgeraccountdetail d where v.active=1 and d.active=1 and v.entityvoucherid=d.entityvoucherid and v.finalreportperiodid=".$_SESSION['finalreportperiodid']." and d.entityledgeraccountid=".$entityledgeraccountid." and entityvoucherdatetime >='".$perioddatetimefrom."' and entityvoucherdatetime <='".$closdate."' and entityvoucherdatetime <='".$perioddatetimeto."'";
            //echo $query2;
            $result2 = mysqli_query($connection, $query2);
            $row2 = mysqli_fetch_assoc($result2);
            if (isset($row2['amount']))
            {
                $closbal = $closbal + $row2['amount'];
            }
            return $closbal;
        }
        else
        {
            return 0;
        }
    }

    function financial_year($lang)
    {
        require("../info/phpsqlajax_dbinfo.php");
        $query = "select finalreportperiodid,periodname_eng from finalreportperiod where active=1 and finalreportperiodid=".$_SESSION['finalreportperiodid'];
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        mysqli_query($connection,'SET NAMES UTF8');
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

    function fixednumberformat($no,$pad,$iscurr=true)
    {
		setlocale(LC_ALL, '');
		$locale =localeconv();
        //$locale['currency_symbol'];
        if ($iscurr==false)
        {
            $no = str_pad(number_format($no,2),$pad,".",STR_PAD_LEFT);
        }
        else
        {
            $no = str_pad('Rs'.number_format($no,2),$pad,".",STR_PAD_LEFT);
        }
		return $no;
	}

    function number_format_indian($no='',$decplac=2,$iscurr=false,$commsep=false)
    {
        $no = str_replace(',', '', $no);
        $no = str_replace('Rs', '', $no);
        $decpos = strpos($no,'.');
        if (empty($decpos))
        {
            $decpos = strlen($no);
        }
        $intno = substr($no, 0,$decpos);
        
        $frano = substr($no,$decpos+1,strlen($no));
        $l=-3;
        $ln=strlen($intno);
        $strprn='';
        if ($commsep == true)
        {
            for ($i=$ln;$i>1;)
            {
                if ($strprn == '')
                {
                    $strprn = substr($intno,$l);    
                }
                else
                {
                    $strprn = substr($intno,$l).','.$strprn;
                }
                
                $intno = substr($intno,0,strlen($intno)+$l);
                $l=-2;
                $i=$i+$l;
            }
        }
        else
        {
            $strprn = $intno;
        }
        if ($intno == '0')
        {
            $strprn = '0';
        }
        $frano = substr($frano, 0, $decplac);
        $frano = str_pad($frano, $decplac,"0", STR_PAD_RIGHT);
        if (!empty($frano))
        {
            $strprn = $strprn.'.'.$frano;
        }
        if ($iscurr == true)
        {
            $strprn = 'Rs'.$strprn;
        }
        return $strprn;
    }

	function ntw_eng($number)
	{
        //A function to convert numbers into Indian readable words with Cores, Lakhs and Thousands.
        $words = array(
        '0'=> '' ,'1'=> 'one' ,'2'=> 'two' ,'3' => 'three','4' => 'four','5' => 'five',
        '6' => 'six','7' => 'seven','8' => 'eight','9' => 'nine','10' => 'ten',
        '11' => 'eleven','12' => 'twelve','13' => 'thirteen','14' => 'fouteen','15' => 'fifteen',
        '16' => 'sixteen','17' => 'seventeen','18' => 'eighteen','19' => 'nineteen','20' => 'twenty',
        '30' => 'thirty','40' => 'fourty','50' => 'fifty','60' => 'sixty','70' => 'seventy',
        '80' => 'eighty','90' => 'ninty');
       
        //First find the length of the number
        $number_length = strlen($number);
        //Initialize an empty array
        $number_array = array(0,0,0,0,0,0,0,0,0);       
        $received_number_array = array();
       
        //Store all received numbers into an array
        for($i=0;$i<$number_length;$i++){    $received_number_array[$i] = substr($number,$i,1);    }

        //Populate the empty array with the numbers received - most critical operation
        for($i=9-$number_length,$j=0;$i<9;$i++,$j++){ $number_array[$i] = $received_number_array[$j]; }
        $number_to_words_string = "";       
        //Finding out whether it is teen ? and then multiplying by 10, example 17 is seventeen, so if 1 is preceeded with 7 multiply 1 by 10 and add 7 to it.
        for($i=0,$j=1;$i<9;$i++,$j++){
            if($i==0 || $i==2 || $i==4 || $i==7){
                if($number_array[$i]=="1"){
                    $number_array[$j] = 10+$number_array[$j];
                    $number_array[$i] = 0;
                }       
            }
        }
       
        $value = "";
        for($i=0;$i<9;$i++){
            if($i==0 || $i==2 || $i==4 || $i==7){    $value = $number_array[$i]*10; }
            else{ $value = $number_array[$i];    }           
            if($value!=0){ $number_to_words_string.= $words["$value"]." "; }
            if($i==1 && $value!=0){    $number_to_words_string.= "Crores "; }
            if($i==3 && $value!=0){    $number_to_words_string.= "Lakhs ";    }
            if($i==5 && $value!=0){    $number_to_words_string.= "Thousand "; }
            if($i==6 && $value!=0 && $number%100!=0){    $number_to_words_string.= "Hundred and "; }
            elseif($i==6 && $value!=0 && $number%100==0){    $number_to_words_string.= "Hundred "; }
        }
        if($number_length>9){ $number_to_words_string = "Sorry This does not support more than 99 Crores"; }
        return ucwords(strtolower($number_to_words_string));
    }

function ntw_mar($number)
	{
        //A function to convert numbers into Indian readable words with Cores, Lakhs and Thousands.
        $words = array(
        '0'=> '' ,'1'=> 'एक' ,'2'=> 'दोन' ,'3' => 'तीन','4' => 'चार','5' => 'पाच',
        '6' => 'सहा','7' => 'सात','8' => 'आठ','9' => 'नऊ','10' => 'दहा',
        '11' => 'अकरा','12' => 'बारा','13' => 'तेरा','14' => 'चौदा','15' => 'पंधरा',
        '16' => 'सोळा','17' => 'सतरा','18' => 'अठरा','19' => 'एकोणीस','20' => 'वीस',
        '21' => 'एकवीस','22' => 'बावीस','23' => 'तेवीस','24' => 'चोवीस','25' => 'पंचवीस',
        '26' => 'सव्वीस','27' => 'सत्तावीस','28' => 'अठठावीस','29' => 'एकोणतीस','30' => 'तीस',
		'31' => 'एकतीस','32' => 'बत्तीस','33' => 'तेहतीस','34' => 'चौतीस','35' => 'पस्तीस',
        '36' => 'छत्तीस','37' => 'सदतीस','38' => 'अडतीस','39' => 'एकोणचाळीस','40' => 'चाळीस',
        '41' => 'एक्केचाळीस','42' => 'बेचाळीस','43' => 'त्रेचाळीस','44' => 'चव्वेचाळीस','45' => 'पंचेचाळीस',
        '46' => 'शेचाळीस','47' => 'सत्तेचाळीस','48' => 'अठ्ठेचाळीस','49' => 'एकोणपन्नास','50' => 'पन्नास',
		'51' => 'एक्कावन','52' => 'बावन्न','53' => 'त्रेपन्नास','54' => 'चौपन्न','55' => 'पन्नास',
        '56' => 'छपन्न','57' => 'सत्तापन्न','58' => 'अठ्ठावन','59' => 'एकोणसाठ','60' => 'साठ',
        '61' => 'एकसष्ठ','62' => 'बासष्ठ','63' => 'त्रेसष्ठ','64' => 'चौसष्ठ','65' => 'पासष्ठ',
        '66' => 'सहासष्ठ','67' => 'सदूसष्ठ','68' => 'अडूसष्ठ','69' => 'एकोणसत्तर','70' => 'सत्तर',
        '71' => 'एक्काहत्तर','72' => 'बाहत्तर','73' => 'त्र्याहत्तर','74' => 'चौऱ्याहत्तर','75' => 'पंच्याहत्तर',
        '76' => 'शाहत्तर','77' => 'सत्त्याहत्तर','78' => 'अष्टयाहत्तर','79' => 'एकोणऐंशी','80' => 'ऐंशी',
        '81' => 'एक्क्याऐंशी','82' => 'ब्याऐंशी','83' => 'त्र्याऐंशी','84' => 'चौऱ्याऐंशी','85' => 'पंच्याऐंशी',
        '86' => 'शहाऐंशी','87' => 'सत्त्याऐंशी','88' => 'अठ्ठ्याऐंशी','89' => 'एकोणनव्वद','90' => 'नव्वद',
		'91' => 'एक्क्यानौ','92' => 'ब्यानौ','93' => 'त्र्यानौ','94' => 'चौऱ्यानौ','95' => 'पंच्यानौ',
        '96' => 'शहानौ','97' => 'सत्त्यानौ','98' => 'अठ्ठ्यानौ','99' => 'नव्यानौ');
       
        //First find the length of the number
        $number_length = strlen($number);
        //Initialize an empty array
        $number_array = array(0,0,0,0,0,0,0,0,0);       
        $received_number_array = array();
       
        //Store all received numbers into an array
        for($i=0;$i<$number_length;$i++){    $received_number_array[$i] = substr($number,$i,1);    }

        //Populate the empty array with the numbers received - most critical operation
        for($i=9-$number_length,$j=0;$i<9;$i++,$j++){ $number_array[$i] = $received_number_array[$j]; }
        $number_to_words_string = "";       
        //Finding out whether it is teen ? and then multiplying by 10, example 17 is seventeen, so if 1 is preceeded with 7 multiply 1 by 10 and add 7 to it.
        for($i=0,$j=1;$i<9;$i++,$j++){
            if($i==0 || $i==2 || $i==4 || $i==7){
                if($number_array[$i]=="1")
                {
                    $number_array[$j] = 10+$number_array[$j];
                    $number_array[$i] = 0;
                }
                else if($number_array[$i]=="2")
                {
                    $number_array[$j] = 20+$number_array[$j];
                    $number_array[$i] = 0;
                }
                else if($number_array[$i]=="3")
                {
                    $number_array[$j] = 30+$number_array[$j];
                    $number_array[$i] = 0;
                }
                else if($number_array[$i]=="4")
                {
                    $number_array[$j] = 40+$number_array[$j];
                    $number_array[$i] = 0;
                }
                else if($number_array[$i]=="5")
                {
                    $number_array[$j] = 50+$number_array[$j];
                    $number_array[$i] = 0;
                }
                else if($number_array[$i]=="6")
                {
                    $number_array[$j] = 60+$number_array[$j];
                    $number_array[$i] = 0;
                }
                else if($number_array[$i]=="7")
                {
                    $number_array[$j] = 70+$number_array[$j];
                    $number_array[$i] = 0;
                }
                else if($number_array[$i]=="8")
                {
                    $number_array[$j] = 80+$number_array[$j];
                    $number_array[$i] = 0;
                }
                else if($number_array[$i]=="9")
                {
                    $number_array[$j] = 90+$number_array[$j];
                    $number_array[$i] = 0;
                }       
            }
        }
       
        $value = "";
        for($i=0;$i<9;$i++){
            if($i==0 || $i==2 || $i==4 || $i==7)
            	{ 
            		$value = $number_array[$i]*10; 
            	}
            else
            	{ 
            		$value = $number_array[$i]; 
            	}           
            if($value!=0 && $i !=6)
            	{ 
            		$number_to_words_string.= $words["$value"]." "; 
        		}
        	elseif($value!=0 && $i ==6)
	        	{
					if (($number % 100 ==0) and ((int)$number / 100) ==1)
	            	{
	            		$number_to_words_string.= "शंभर "; 
	            	}
	            	else
	            	{
	            		$no = (int)($number / 100);
                        if ($no <10)
                        {
                            $number_to_words_string.= $words["$no"]."शे ";     
                        }
                        else
                        {
                            $no = $no % 10;
                            $number_to_words_string.= $words["$no"]."शे ";     
                        }
	            	}
	        	}
            if($i==1 && $value!=0){    $number_to_words_string.= "कोटी "; }
            if($i==3 && $value!=0){    $number_to_words_string.= "लाख ";    }
            if($i==5 && $value!=0){    $number_to_words_string.= "हजार "; }
        }
        if($number_length>9){ $number_to_words_string = "Sorry This does not support more than 99 Crores"; }
        return $number_to_words_string;
    }

    function NumberToWords($number,$lang)
    {
    	$number=abs($number);
        if ($lang == 0)
    	{
	    	if ($number < 10000000)
	    	{
	    		return "Rs ".ntw_eng($number)."Only";
	    	}
	    	else
	    	{
	    		$acrore = floor($number/10000000);
	    		//echo $acrore.'</br>';
	    		$bcrore = fmod($number,10000000);
	    		//echo $bcrore.'</br>';
	    		return "Rs ".ntw_eng($acrore).'Crores '.ntw_eng($bcrore)."Only";
	    	}
    	}
    	else if ($lang == 1)
    	{
    		if ($number < 10000000)
	    	{
	    		return "Rs ".ntw_mar($number)."फक्त";
	    	}
	    	else
	    	{
	    		$acrore = floor($number/10000000);
	    		//echo $acrore.'</br>';
	    		$bcrore = fmod($number,10000000);
	    		//echo $bcrore.'</br>';
	    		return "Rs ".ntw_mar($acrore)."करोड ".ntw_mar($bcrore)."फक्त";
	    	}
    	}
    }
    function phpAlert($msg)
    {
        echo '<script type="text/javascript">alert("' . $msg . '")</script>';
    }


    function getentityvoucherledgeraccountdetailid(&$connection,$entvouid,$entledaccid,$drcr)
    {
        $query = "select entityvoucherledgeraccountdetailid from entityvoucherledgeraccountdetail where active=1 and entityledgeraccountid=".$entledaccid." and debitcredit=".$drcr." and entityvoucherid=".$entvouid;

        $result = mysqli_query($connection, $query);

        if ($row = @mysqli_fetch_assoc($result))
        {
            return $row['entityvoucherledgeraccountdetailid'];
        }
        else
        {
            return 0;
        }
    }

    function getcashledgeraccountid()
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Communication Error1";
        }
        $query = "select * from standardheadaccount where active=1 and standardheadid = 213784569";
        $result = mysqli_query($connection, $query);
        if ($row = @mysqli_fetch_assoc($result))
        {
            return $row['entityledgeraccountid'];
            exit;
        }
        else
        {
            echo "Communication Error2";
            return 0;
        }

    }

    function generateentityvoucherledgeraccountdetailid()
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Communication Error3";
        }
        $no0=rand(0,1);
        if ($no0 == 0)
        {
            $no1=rand(100,999);
            while ($no1 % 6 != 0) 
            {
                $no1=rand(100,999);
            }
            $no2=rand(100,999);
            while ($no2 % 9 != 0) 
            {
                $no2=rand(100,999);
            }
            $no3=rand(100,999);
            while ($no3 % 5 != 0) 
            {
                $no3=rand(100,999);
            }
            $no4=rand(100,999);
            while ($no4 % 2 != 0) 
            {
                $no4=rand(100,999);
            }
            $no5=rand(100,999);
            return $no1.$no2.$no3.$no4.$no5;
        }
        elseif ($no0 == 1)
        {
            $no1=rand(100,999);
            while ($no1 % 3 != 0) 
            {
                $no1=rand(100,999);
            }
            $no2=rand(100,999);
            while ($no2 % 8 != 0) 
            {
                $no2=rand(100,999);
            }
            $no3=rand(100,999);
            while ($no3 % 5 != 0) 
            {
                $no3=rand(100,999);
            }
            $no4=rand(100,999);
            while ($no4 % 7 != 0) 
            {
                $no4=rand(100,999);
            }
            $no5=rand(100,999);
            return $no1.$no2.$no3.$no4.$no5;
        }
    }

function generateentityvouchersubledgeraccountdetailid()
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Communication Error4";
        }
        $no0=rand(0,1);
        if ($no0 == 0)
        {
            $no1=rand(100,999);
            while ($no1 % 6 != 0) 
            {
                $no1=rand(100,999);
            }
            $no2=rand(100,999);
            while ($no2 % 9 != 0) 
            {
                $no2=rand(100,999);
            }
            $no3=rand(100,999);
            while ($no3 % 5 != 0) 
            {
                $no3=rand(100,999);
            }
            $no4=rand(100,999);
            while ($no4 % 2 != 0) 
            {
                $no4=rand(100,999);
            }
            $no5=rand(100,999);
            return $no1.$no2.$no3.$no4.$no5;
        }
        elseif ($no0 == 1)
        {
            $no1=rand(100,999);
            while ($no1 % 3 != 0) 
            {
                $no1=rand(100,999);
            }
            $no2=rand(100,999);
            while ($no2 % 8 != 0) 
            {
                $no2=rand(100,999);
            }
            $no3=rand(100,999);
            while ($no3 % 5 != 0) 
            {
                $no3=rand(100,999);
            }
            $no4=rand(100,999);
            while ($no4 % 7 != 0) 
            {
                $no4=rand(100,999);
            }
            $no5=rand(100,999);
            return $no1.$no2.$no3.$no4.$no5;
        }
    }

function insert_voucher_detail(&$connection,$vouchercategoryid,$entityvoucherseriesid,$entityvoucherid,$debitcredit,$entityledgeraccountid,$amount,&$query1,&$query2,&$query3,&$query4)
{
    $pos = strpos($entityledgeraccountid, '-');
    if ($pos > 0)
    {
        $entitysubledgeraccountid = substr($entityledgeraccountid, $pos+1);
        $entityledgeraccountid = substr($entityledgeraccountid, 0,$pos);
    }

    if (isset($amount) == false)
    {
        return 0;
        exit;
    }
    if (strpos($entityledgeraccountid, "-") == True)
    {
        $subentityledgeraccountid = substr($entityledgeraccountid, strpos($entityledgeraccountid, "-")+1);
        $entityledgeraccountid = substr($entityledgeraccountid,0,strpos($entityledgeraccountid, "-"));
        if (isset($debitcredit) == false or isset($entityvoucherid) == false or isset($entityledgeraccountid) == false or isset($subentityledgeraccountid) == false or isset($amount) == false)
        {
            return 0;
            exit;
        }
    }
    else
    {
        $entityledgeraccountid = $entityledgeraccountid;
        unset($subentityledgeraccountid);
        if (isset($debitcredit) == false or isset($entityvoucherid) == false or isset($entityledgeraccountid) == false or isset($amount) == false)
        {
            return 0;
            exit;
        }
    }

    if (isset($entitysubledgeraccountid))
    {
        $cnt = 1;
        while ($cnt != 0)
        {
            $entityvouchersubledgeraccountdetailid = generateentityvouchersubledgeraccountdetailid();
            $result1 = mysqli_query($connection, "select count(*) as cnt from entityvouchersubledgeraccountdetail where active=1 and entityvouchersubledgeraccountdetailid=".$entityvouchersubledgeraccountdetailid);
            $row1 = mysqli_fetch_assoc($result1);
            $cnt = $row1["cnt"];
        }
    }

    if ($entityvoucherseriesid == 478936533 or $entityvoucherseriesid == 479064300 or $entityvoucherseriesid == 478468054 or $entityvoucherseriesid == 478766177)
    {
        $cashledgeraccountid = getcashledgeraccountid();
        if ($cashledgeraccountid == 0)
        {
            return 0;
            exit;
        }
    }
    $entityvoucherledgeraccountdetailid = getentityvoucherledgeraccountdetailid($connection,$entityvoucherid,$entityledgeraccountid,$debitcredit);
    if ($entityvoucherledgeraccountdetailid!=0)
    {
        $query1 = "update entityvoucherledgeraccountdetail set amount=amount+".$amount." where active=1 and entityledgeraccountid=".$entityledgeraccountid." and debitcredit=".$debitcredit." and entityvoucherledgeraccountdetailid=".$entityvoucherledgeraccountdetailid;           
        if (mysqli_query($connection, $query1))
        {
            if (isset($cashledgeraccountid))
            {
                if ($debitcredit == 157489650)              
                {
                    $entityvoucherledgeraccountdetailid_cash = getentityvoucherledgeraccountdetailid($connection,$entityvoucherid,$cashledgeraccountid,357481241);
                    if ($entityvoucherledgeraccountdetailid_cash!=0)
                    {
                        $query2 = "update entityvoucherledgeraccountdetail set amount=amount+".$amount." where active=1 and entityledgeraccountid=".$cashledgeraccountid." and debitcredit=357481241 and entityvoucherledgeraccountdetailid=".$entityvoucherledgeraccountdetailid_cash;                                 
                    }
                    else
                    {
                        $cnt = 1;
                        while ($cnt != 0)
                        {
                            $entityvoucherledgeraccountdetailid_cash = generateentityvoucherledgeraccountdetailid();
                            $result3 = mysqli_query($connection, "select count(*) as cnt from entityvoucherledgeraccountdetail where active=1 and entityvoucherledgeraccountdetailid=".$entityvoucherledgeraccountdetailid_cash);
                            $row3 = mysqli_fetch_assoc($result3);
                            $cnt = $row3["cnt"];
                        }
                        $query2 = "insert into entityvoucherledgeraccountdetail(entityvoucherledgeraccountdetailid,entityvoucherid,entityledgeraccountid,debitcredit,amount,active,cruserid) values ($entityvoucherledgeraccountdetailid_cash,$entityvoucherid,$cashledgeraccountid,357481241,$amount,1,".$_SESSION["usersid"].")";
                    }
                }
                elseif ($debitcredit == 357481241)
                {
                    $entityvoucherledgeraccountdetailid_cash = getentityvoucherledgeraccountdetailid($connection,$entityvoucherid,$cashledgeraccountid,157489650);
                    if ($entityvoucherledgeraccountdetailid_cash!=0)
                    {
                        $query2 = "update entityvoucherledgeraccountdetail set amount=amount+".$amount." where active=1 and entityledgeraccountid=".$cashledgeraccountid." and debitcredit=157489650 and entityvoucherledgeraccountdetailid=".$entityvoucherledgeraccountdetailid_cash;         
                    }
                    else
                    {
                        $cnt = 1;
                        while ($cnt != 0)
                        {
                            $entityvoucherledgeraccountdetailid_cash = generateentityvoucherledgeraccountdetailid();
                            $result3 = mysqli_query($connection, "select count(*) as cnt from entityvoucherledgeraccountdetail where active=1 and entityvoucherledgeraccountdetailid=".$entityvoucherledgeraccountdetailid_cash);
                            $row3 = mysqli_fetch_assoc($result3);
                            $cnt = $row3["cnt"];
                        }
                        $query2 = "insert into entityvoucherledgeraccountdetail(entityvoucherledgeraccountdetailid,entityvoucherid,entityledgeraccountid,debitcredit,amount,active,cruserid) values ($entityvoucherledgeraccountdetailid_cash,$entityvoucherid,$cashledgeraccountid,157489650,$amount,1,".$_SESSION["usersid"].")";
                    }
                }
                if (mysqli_query($connection, $query2))
                {

                }
                else
                {
                    return 0;
                    exit;
                }
            }
            if (isset($entitysubledgeraccountid))
            {
                $result2 = mysqli_query($connection, "select entityvouchersubledgeraccountdetailid from entityvouchersubledgeraccountdetail where active=1 and entityledgeraccountid=".$entityledgeraccountid." and entitysubledgeraccountid=".$entitysubledgeraccountid." and debitcredit=".$debitcredit." and entityvoucherledgeraccountdetailid=".$entityvoucherledgeraccountdetailid);
                $row2 = mysqli_fetch_assoc($result2);
                if (isset($row1["entityvouchersubledgeraccountdetailid"]))
                {
                    echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Record already exists</span>';
                    return 0;
                    exit;
                }
                else
                {
                    $query3 = "insert into entityvouchersubledgeraccountdetail(entityvouchersubledgeraccountdetailid,entityvoucherledgeraccountdetailid,entityledgeraccountid,entitysubledgeraccountid,debitcredit,amount,active,cruserid) values ($entityvouchersubledgeraccountdetailid,$entityvoucherledgeraccountdetailid,$entityledgeraccountid,$entitysubledgeraccountid,$debitcredit,$amount,1,".$_SESSION["usersid"].")";
                    if (mysqli_query($connection, $query3))
                    {
                        $query4 = "update entityvoucher set isvalid=0,isattended=0 where active=1 and entityvoucherid=".$entityvoucherid;
                        if (mysqli_query($connection, $query4))
                        {
                            return 1;
                            exit;
                        }
                        else
                        {
                            return 0;
                            exit;
                        }
                    }
                    else
                    {
                        return 0;
                        exit;
                    }
                }
            }
            else
            {
                $query3 = "update entityvoucher set isvalid=0,isattended=0 where active=1 and entityvoucherid=".$entityvoucherid;
                if (mysqli_query($connection, $query3))
                {
                    return 1;
                    exit;
                }
                else
                {
                    return 0;
                    exit;
                }
            }
        }
        else
        {
            return 0;
            exit;
        }   
    }
    else
    {
        $cnt = 1;
        while ($cnt != 0)
        {
            $entityvoucherledgeraccountdetailid = generateentityvoucherledgeraccountdetailid();
            $result1 = mysqli_query($connection, "select count(*) as cnt from entityvoucherledgeraccountdetail where active=1 and entityvoucherledgeraccountdetailid=".$entityvoucherledgeraccountdetailid);
            $row1 = mysqli_fetch_assoc($result1);
            $cnt = $row1["cnt"];
        }
        $query1 = "insert into entityvoucherledgeraccountdetail(entityvoucherledgeraccountdetailid,entityvoucherid,entityledgeraccountid,debitcredit,amount,active,cruserid) values ($entityvoucherledgeraccountdetailid,$entityvoucherid,$entityledgeraccountid,$debitcredit,$amount,1,".$_SESSION["usersid"].")";
        if (mysqli_query($connection, $query1))
        {
            if (isset($cashledgeraccountid))
            {
                if ($debitcredit == 157489650)              
                {
                    $entityvoucherledgeraccountdetailid_cash = getentityvoucherledgeraccountdetailid($connection,$entityvoucherid,$cashledgeraccountid,357481241);
                    if ($entityvoucherledgeraccountdetailid_cash!=0)
                    {
                        $query2 = "update entityvoucherledgeraccountdetail set amount=amount+".$amount." where active=1 and entityledgeraccountid=".$cashledgeraccountid." and debitcredit=357481241 and entityvoucherledgeraccountdetailid=".$entityvoucherledgeraccountdetailid_cash;                                 
                    }
                    else
                    {
                        $cnt = 1;
                        while ($cnt != 0)
                        {
                            $entityvoucherledgeraccountdetailid_cash = generateentityvoucherledgeraccountdetailid();
                            $result1 = mysqli_query($connection, "select count(*) as cnt from entityvoucherledgeraccountdetail where active=1 and entityvoucherledgeraccountdetailid=".$entityvoucherledgeraccountdetailid_cash);
                            $row1 = mysqli_fetch_assoc($result1);
                            $cnt = $row1["cnt"];
                        }
                        $query2 = "insert into entityvoucherledgeraccountdetail(entityvoucherledgeraccountdetailid,entityvoucherid,entityledgeraccountid,debitcredit,amount,active,cruserid) values ($entityvoucherledgeraccountdetailid_cash,$entityvoucherid,$cashledgeraccountid,357481241,$amount,1,".$_SESSION["usersid"].")";
                    }
                }
                elseif ($debitcredit == 357481241)
                {
                    $entityvoucherledgeraccountdetailid_cash = getentityvoucherledgeraccountdetailid($connection,$entityvoucherid,$cashledgeraccountid,157489650);
                    if ($entityvoucherledgeraccountdetailid_cash!=0)
                    {
                        $query2 = "update entityvoucherledgeraccountdetail set amount=amount+".$amount." where active=1 and entityledgeraccountid=".$cashledgeraccountid." and debitcredit=157489650 and entityvoucherledgeraccountdetailid=".$entityvoucherledgeraccountdetailid_cash;         
                    }
                    else
                    {
                        $cnt = 1;
                        while ($cnt != 0)
                        {
                            $entityvoucherledgeraccountdetailid_cash = generateentityvoucherledgeraccountdetailid();
                            $result1 = mysqli_query($connection, "select count(*) as cnt from entityvoucherledgeraccountdetail where active=1 and entityvoucherledgeraccountdetailid=".$entityvoucherledgeraccountdetailid_cash);
                            $row1 = mysqli_fetch_assoc($result1);
                            $cnt = $row1["cnt"];
                        }
                        $query2 = "insert into entityvoucherledgeraccountdetail(entityvoucherledgeraccountdetailid,entityvoucherid,entityledgeraccountid,debitcredit,amount,active,cruserid) values ($entityvoucherledgeraccountdetailid_cash,$entityvoucherid,$cashledgeraccountid,157489650,$amount,1,".$_SESSION["usersid"].")";
                    }
                }
                if (mysqli_query($connection, $query2))
                {

                }
                else
                {
                    return 0;
                    exit;
                }
            }
            if (isset($entitysubledgeraccountid))
            {
                $query = "select entityvouchersubledgeraccountdetailid from entityvouchersubledgeraccountdetail where active=1 and entityledgeraccountid=".$entityledgeraccountid." and entitysubledgeraccountid=".$entitysubledgeraccountid." and debitcredit=".$debitcredit." and entityvoucherledgeraccountdetailid=".$entityvoucherledgeraccountdetailid;
                $result2 = mysqli_query($connection, $query);
                $row2 = mysqli_fetch_assoc($result2);
                if (isset($row2["entityvouchersubledgeraccountdetailid"]))
                {
                    echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Record already exists</span>';
                    return 0;
                    exit;
                }
                $query3 = "insert into entityvouchersubledgeraccountdetail(entityvouchersubledgeraccountdetailid,entityvoucherledgeraccountdetailid,entityledgeraccountid,entitysubledgeraccountid,debitcredit,amount,active,cruserid) values ($entityvouchersubledgeraccountdetailid,$entityvoucherledgeraccountdetailid,$entityledgeraccountid,$entitysubledgeraccountid,$debitcredit,$amount,1,".$_SESSION["usersid"].")";
                if (mysqli_query($connection, $query3))
                {
                    $query4 = "update entityvoucher set isvalid=0,isattended=0 where active=1 and entityvoucherid=".$entityvoucherid;
                    if (mysqli_query($connection, $query4))
                    {
                        return 1;
                        exit;
                    }
                    else
                    {
                        return 0;
                        exit;
                    }
                }
                else
                {
                    return 0;
                    exit;
                }
            }
            else
            {
                $query3 = "update entityvoucher set isvalid=0,isattended=0 where active=1 and entityvoucherid=".$entityvoucherid;
                if (mysqli_query($connection, $query3))
                {
                    return 1;
                    exit;
                }
                else
                {
                    return 0;
                    exit;
                }
            }
        }
        else
        {
            return 0;
            exit;
        }   
    }
}

function getvoucherseriesname($vouserid)
{
    require("../info/phpsqlajax_dbinfo.php");
    // Opens a connection to a MySQL server
    $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
    // Check connection
    if (mysqli_connect_errno())
      {
        echo "Communication Error";
      }
    $query = "SELECT v.entityvoucherseriesname,v.entityvoucherseriesname_eng FROM entityvoucherseries v where v.active=1 and entityvoucherseriesid=".$vouserid." order by entityvoucherseriesname";                          
    mysqli_query($connection,'SET NAMES UTF8');
    $result = mysqli_query($connection,$query);
    if ($row = @mysqli_fetch_assoc($result))
    {
        return $row['entityvoucherseriesname'].' ('.$row['entityvoucherseriesname_eng'].')';
    }
    else
    {
        echo "Communication Error";
        return 0;
    }
}

function getvoucherledgercount($vouid,$drcr)
{
    require("../info/phpsqlajax_dbinfo.php");
    // Opens a connection to a MySQL server
    $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
    // Check connection
    if (mysqli_connect_errno())
      {
        echo "Communication Error";
      }
    $query = "SELECT count(*) as cnt FROM entityvoucherledgeraccountdetail v where v.active=1 and entityvoucherid=".$vouid." and debitcredit=".$drcr;                          
    mysqli_query($connection,'SET NAMES UTF8');
    $result = mysqli_query($connection,$query);
    if ($row = @mysqli_fetch_assoc($result))
    {
        return $row['cnt'];
    }
    else
    {
        echo "Communication Error";
        return 0;
    }
}

function getfiltervalue($vouserid,$drcr)
{
    require("../info/phpsqlajax_dbinfo.php");
    // Opens a connection to a MySQL server
    $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Communication Error";
    }
    $query = "SELECT filtervalue FROM entityvoucherseriesselectionfilter f where active=1 and entityvoucherseriesid=".$vouserid." and debitcredit=".$drcr;                          
    mysqli_query($connection,'SET NAMES UTF8');
    $result = mysqli_query($connection,$query);
    if ($row = @mysqli_fetch_assoc($result))
    {
        return $row['filtervalue'];
    }
    else
    {
        return '1000000000';
    }
}

function getfirmfiltervalue($bussmodeid)
{
    require("../info/phpsqlajax_dbinfo.php");
    // Opens a connection to a MySQL server
    $connection=mysqli_connect($hostname_finance, $username_finance, $password_finance, $database_finance);
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Communication Error";
    }
    $query = "SELECT filtervalue FROM businessmodeselectionfilter f where active=1 and businessmodeselectionfilterid=".$bussmodeid;                          
    mysqli_query($connection,'SET NAMES UTF8');
    $result = mysqli_query($connection,$query);
    if ($row = @mysqli_fetch_assoc($result))
    {
        return $row['filtervalue'];
    }
    else
    {
        return '10000000';
    }
}
function quarterfinalperiodfromdatelimit(&$connection,$finalreportperiodid)
    {
        $query = "select f.perioddatetimefrom,f.perioddatetimeto from quarter e,finalreportperiod f where e.active=1 and f.active=1 and e.quarterfinalreportperiodid=f.finalreportperiodid and f.finalreportperiodid=".$finalreportperiodid." order by f.periodnumber";
        //echo $query;
        $result=mysqli_query($connection, $query);
        if ($row = mysqli_fetch_assoc($result))
        {
            return $row['perioddatetimefrom'];
        }

    }
    function quarterfinalperiodtodatelimit(&$connection,$finalreportperiodid)
    {
        $query = "select f.perioddatetimefrom,f.perioddatetimeto from quarter e,finalreportperiod f where e.active=1 and f.active=1 and e.quarterfinalreportperiodid=f.finalreportperiodid and f.finalreportperiodid=".$finalreportperiodid." order by f.periodnumber";
        $result=mysqli_query($connection, $query);
        if ($row = mysqli_fetch_assoc($result))
        {
            return $row['perioddatetimeto'];
        }
    }
    function yearfinalperiodfromdatelimit(&$connection,$finalreportperiodid)
    {
        $query = "select f.perioddatetimefrom,f.perioddatetimeto from finalreportperiod f where f.active=1 and f.finalreportperiodid=".$finalreportperiodid." order by f.periodnumber";
        $result=mysqli_query($connection, $query);
        if ($row = mysqli_fetch_assoc($result))
        {
            return $row['perioddatetimefrom'];
        }
    }
    function yearfinalperiodtodatelimit(&$connection,$finalreportperiodid)
    {
        $query = "select f.perioddatetimefrom,f.perioddatetimeto from finalreportperiod f where f.active=1 and f.finalreportperiodid=".$finalreportperiodid." order by f.periodnumber";
        $result=mysqli_query($connection, $query);
        if ($row = mysqli_fetch_assoc($result))
        {
            return $row['perioddatetimeto'];
        }
    }
    function db_connection()
    {
        require("../info/phpsqlajax_dbinfo.php");
        // Opens a connection to a MySQL server
        $connection=mysqli_connect($hostname, $username, $password, $database);
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Communication Error";
        }
        mysqli_query($connection,'SET NAMES UTF8');
        $connection->autocommit(FALSE);
        return $connection;
    }
?>