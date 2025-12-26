<?php
    session_start();
    require("../info/phpgetlogin.php");
    require("../info/phpsqlajax_dbinfo.php");
    include("../info/ncryptdcrypt.php");
    $_SESSION['preloginurl'] = $_SERVER['REQUEST_URI'];
    if (!isset($_SESSION['fb_access_token']))
    {
        header('Location: http://www.swapp.co.in/site/login.php');
        exit;
    }
    if (isset($_GET['stateid'])==true)
    {
      $stateid_de =(int) fnDecrypt($_GET['stateid']); 
    }
    elseif (isset($_POST["state"]) && !empty($_POST["state"]))
    {
      $stateid_de =$_POST['state']; 
    }
    else
    {
      $stateid_de = 27;
    }
    
    if (isset($_GET['districtid'])==true)
    {
      $districtid_de =(int) fnDecrypt($_GET['districtid']); 
    }
    elseif (isset($_POST['district'])==true)
    {
      $districtid_de =$_POST['district']; 
    }
    else
    {
      $districtid_de = 521;
    }
    if (isset($_GET['subdistrictid'])==true)
    {
      $subdistrictid_de =(int) fnDecrypt($_GET['subdistrictid']); 
    }
    elseif (isset($_POST['subdistrict'])==true)
    {
      $subdistrictid_de =$_POST['subdistrict']; 
    }
    else
    {
      $subdistrictid_de = 4196;
    }
    
    if (isset($_GET['areaid'])==true)
    {
      $areaid_de =(int) fnDecrypt($_GET['areaid']); 
    }
    elseif (isset($_POST['areaid'])==true)
    {
      $areaid_de =$_POST['areaid']; 
    }
    else
    {
      $areaid_de = 0;
    }
    // Opens a connection to a MySQL server
    $connection=mysqli_connect($hostname, $username, $password, $database);
    mysqli_query($connection,'SET NAMES UTF8');

    function fedvillagename(&$connection,$villagename)
    {
      $query = "SELECT areaname FROM area where length(areaname)>0 and areaname_eng like '%".$villagename."%' order by length(areaname_eng) limit 1";
      $result = mysqli_query($connection,$query);
      if ($row = @mysqli_fetch_assoc($result))
      {
        return $row['areaname'];
      }
      else
      {
        return '';
      }
    }

    function stateid (&$connection,$districtid)
    {
      $query = "SELECT s.stateid FROM district s where s.districtid=".$districtid;                
    $result = mysqli_query($connection,$query);
    if ($row = @mysqli_fetch_assoc($result))
    {
      return $row['stateid'];
    }
    else
    {
      return 27;
    }
    }
    
    function districtid (&$connection,$subdistrictid)
    {
      $query = "SELECT s.districtid FROM subdistrict s where s.subdistrictid=".$subdistrictid;                
    $result = mysqli_query($connection,$query);
    if ($row = @mysqli_fetch_assoc($result))
    {
      return $row['districtid'];
    }
    else
    {
      return 521;
    }
    }

    function subdistrictid (&$connection,$areaid)
    {
      $query = "SELECT s.subdistrictid FROM area s where s.areaid=".$areaid;
      $result = mysqli_query($connection,$query);
      if ($row = @mysqli_fetch_assoc($result))
      {
        return $row['subdistrictid'];
      }
      else
      {
        return 4196;
      }
    }

    function districtname (&$connection,$districtid,$lng)
    {
      $query = "SELECT s.districtid,s.districtname,s.districtname_eng FROM district s where s.districtid=".$districtid;               
    $result = mysqli_query($connection,$query);
    if ($row = @mysqli_fetch_assoc($result))
    {
      if ($lng == 0)
      {
        return $row['districtname_eng'];
      }
      elseif ($lng == 1)
      {
        return $row['districtname'];
      }
    }
    else
    {
      return '';
    }
    }

    function subdistrictname (&$connection,$subdistrictid,$lng)
    {
      $query = "SELECT s.subdistrictid,s.subdistrictname,s.subdistrictname_eng FROM subdistrict s where s.subdistrictid=".$subdistrictid;               
      $result = mysqli_query($connection,$query);
      if ($row = @mysqli_fetch_assoc($result))
      {
        if ($lng == 0)
        {
          return $row['subdistrictname_eng'];
        }
        elseif ($lng == 1)
        {
          return $row['subdistrictname'];
        }
      }
      else
      {
        return '';
      }
    }
    function nextvillagecode(&$connection,&$subdistrictid_de)
    {
      $query1 = "SELECT a.areaid 
                  FROM area a,subdistrict s
                  where a.subdistrictid=s.subdistrictid and s.subdistrictid=".$subdistrictid_de." and length(areaname)=0 and (TIMESTAMPDIFF(MINUTE,lockdatetime,sysdate()) is null or TIMESTAMPDIFF(MINUTE,lockdatetime,sysdate())>10) order by areaname,areaname_eng limit 1";
              mysqli_query($connection,'SET NAMES UTF8');
              $result = mysqli_query($connection,$query1);
              if (!$result)
              {
                die('Communication Error2');
              }
              $areaid=0;
              if ($row1 = @mysqli_fetch_assoc($result))
              {
                $areaid = $row1['areaid'];
              } 
              return $areaid;
    }

    function nextvillageaddress(&$connection,$areaid,$lng_code)
    {
      $query1 = "SELECT areaname_eng,subdistrictname_eng,districtname_eng,areaname,subdistrictname,districtname
                FROM area a,subdistrict s,district d
                where a.subdistrictid=s.subdistrictid and s.districtid=d.districtid and areaid=".$areaid." limit 1";
    
      mysqli_query($connection,'SET NAMES UTF8');
      $result = mysqli_query($connection,$query1);
      if (!$result)
      {
        die('Communication Error2');
      }
      $area='';
      if ($row1 = @mysqli_fetch_assoc($result))
      {
        if ($lng_code==0)
        {
          $area = $row1['areaname_eng'].' Taluka - '.$row1['subdistrictname_eng'].' District - '.$row1['districtname_eng'];
        } 
        else
        {
          $area = $row1['areaname'].' तालुका - '.$row1['subdistrictname'].' जिल्हा - '.$row1['districtname'];
        } 
      } 
      return $area;
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <script async async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <script async>
          (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-1879145555378586",
            enable_page_level_ads: true
          });
        </script>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Swapp Software Application">
        <meta name="author" content="Swapp Software Application">
        <title>Swapp Software Application</title>
        <script async src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script async type="text/javascript" src="../js/menu.js" ></script>
        <link href="../css/cssmenu.css" rel="stylesheet" type="text/css" /> 
        <!-- Bootstrap Core CSS -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="../css/business-casual.css" rel="stylesheet">
        <!-- Fonts -->
        <link href="../font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
        <link href="http://fonts.googleapis.com/css?family=Josefin+Slab:100,300,400,600,700,100italic,300italic,400italic,600italic,700italic" rel="stylesheet" type="text/css">
        <style type="text/css">
        @font-face {
            font-family: siddhanta;
            src: url("../fonts/siddhanta.ttf");
            font-weight: normal;
        }
        </style>

        <!--<link rel="stylesheet" href="../css/w3.css">-->
        <script async src="../js/1.11.0/jquery.min.js">
         </script>
         <script async>
            $(document).ready(function(){
             setInterval(function(){cache_clear()},360000);
             });
             function cache_clear()
            {
             window.location.reload(true);
            }
        </script>
        <script async>
          function showProgressCursor()
          {
             $("#progressMessageLbl").html("");
             $("#progressMessage").show();
          }
        </script>
        <script async>
              $(document).ready(function () 
              {
              $('#areaname').bind("cut copy paste",function(e) {
                e.preventDefault();
              });
              });
        </script>
    <script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58db35533dec30001259e677&product=sticky-share-buttons"></script>
</head>
    <body>
        <header>
            <!--<div><img src="../img/swapp_namelogo.png" width="150px" height="50px">
                </div>-->
            <div class="row">
            <div class="box">
                <div align="center">
                    <script async async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                  <!-- swapp_fp_topic_footer -->
                  <ins class="adsbygoogle"
                       style="display:block"
                       data-ad-client="ca-pub-1879145555378586"
                       data-ad-slot="7623998956"
                       data-ad-format="auto"></ins>
                  <script async>
                  (adsbygoogle = window.adsbygoogle || []).push({});
                  </script>
                    </div>
                
                    <div class="col-lg-121">
                    <div id="wrapper">
                    <nav id='cssmenu'>
                    <img  src="../img/wwwswappcoin1.png" alt="">
                    <div id="head-mobile"></div>
                    <div class="button"></div>
                    <ul>
                    <li><a href='/index.php'>Home</a>
                    <li><a href='../site/villagenametranslation.php'>Village Name Translation site</a>
                    </ul>
                    </nav>
                        </div>
                    </h4>
                </div>
                </div>
            </div>
        </div>  
        </header>
        <div class="container">
        <div class="row">
            <div class="box">
                <div class="col-lg-121">
                    <div style="float:left">
                        <?php
                             //echo 'site1';
                             $islockacq = false;
                                if ($islockacq==false)
                                {
                                  $query = "update area set lockdatetime=sysdate() where length(areaname)=0 and (TIMESTAMPDIFF(MINUTE,lockdatetime,sysdate()) is null or TIMESTAMPDIFF(MINUTE,lockdatetime,sysdate())>=10) and areaid=".$areaid_de;
                                  if (mysqli_query($connection, $query))
                                  {
                                    if (mysqli_affected_rows($connection)==0)
                                    {
                                      $islockacq=false;
                                    }
                                    else
                                    {
                                      $islockacq = true;
                                      $connection -> commit();
                                    }
                                  }  
                                  else
                                  {
                                    $islockacq=false;
                                  }    
                                }
                            //echo 'site2';  
                            if ($islockacq==false)
                            {
                              $connection ->rollback();
                              $query1 = "update area set lockdatetime=null where length(areaname)=0 and areaid=".$areaid;
                                    if (mysqli_query($connection, $query1))
                                    {
                                      $connection->commit();
                                    }
                                    else
                                    {
                                      $connection ->rollback();
                                    }
                              echo '<tr style="font-size:13px">';
                              echo '<span style="background-color:#f44;color:#ff8;text-align:left;">Communication Error34</span>';
                              echo '</tr>';
                              $stateid_en = fnEncrypt($stateid_de);
                              $districtid_en = fnEncrypt($districtid_de);
                              $subdistrictid_en = fnEncrypt($subdistrictid_de);
                              $areaid_en = fnEncrypt($areaid_de);
                              echo '<a href="../site/indianstatedistrictsubdistrictarealist_short.php?stateid='.$stateid_en.'&districtid='.$districtid_en.'&subdistrictid='.$subdistrictid_en.'">Click to Continue... </a>';
                              echo 'site3';
                              exit;
                            }
                            //echo 'site4';
                            $stateid_en = fnEncrypt($stateid_de);
                            $districtid_en = fnEncrypt($districtid_de);
                            $subdistrictid_en = fnEncrypt($subdistrictid_de);
                            $areaid_en = fnEncrypt($areaid_de);
                            $query = "select e.* from area e where e.areaid=".$areaid_de;
                            $result=mysqli_query($connection, $query);
                            if ($row = @mysqli_fetch_assoc($result)) 
                            {
                                echo '<section>';
                                echo '<form method="post" action="../site/villagename_update.php">';
                                echo '<table border="0" >';
                                    $areaname_eng = nextvillageaddress($connection,$areaid_de,0);
                                    $areaname = nextvillageaddress($connection,$areaid_de,1);
                                    
                                    echo '<tr>';
                                    echo '<td><label for="businesstypename_eng">User : '.$_SESSION['fb_name'].'</label></td>';
                                    echo '</tr>';

                                    echo '<tr>';
                                    echo '<td><label for="businesstypename_eng">'.$areaname_eng.'</label></td>';
                                    //echo '<td><label for="businesstypename_eng">गाव : '.$areaname.'</label></td>';
                                    echo '</tr>';

                                    echo '<tr>';
                                    echo '<td><label for="businesstypename_eng">Village Name</label></td>';
                                    echo '</tr>';
                                    echo '<tr>';    
                                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="areaname_eng" id="areaname_eng"  value="'.$row['areaname_eng'].'" readonly="readonly"></td>';
                                    echo '</tr>';

                                    echo '<tr>';
                                    echo '<td><label for="areaname">गावाचे नाव</label></td>';
                                    echo '</tr>';
                                    echo '<tr>';  
                                    if (stristr($row['areaname_eng'],'wad'))
                                    {
                                      $defstr = 'वाड';
                                    }
                                    elseif (stristr($row['areaname_eng'],'vadi'))
                                    {
                                      $defstr = 'वडी';
                                    }
                                    if (stristr($row['areaname_eng'],'wada'))
                                    {
                                      $defstr = 'वाडा';
                                    }
                                    elseif (stristr($row['areaname_eng'],'wadi'))
                                    {
                                      $defstr = 'वाडी';
                                    }

                                    if (stristr($row['areaname_eng'],'gaon'))
                                    {
                                      $defstr = 'गाव';
                                    }
                                    elseif (stristr($row['areaname_eng'],'nagar'))
                                    {
                                      $defstr = 'नगर';
                                    }
                                    elseif (stristr($row['areaname_eng'],'vasti') or stristr($row['areaname_eng'],'wasti'))
                                    {
                                      $defstr = 'वस्ती';
                                    }
                                    elseif (stristr($row['areaname_eng'],'ghar'))
                                    {
                                      $defstr = 'घर';
                                    }
                                    elseif (stristr($row['areaname_eng'],'puri'))
                                    {
                                      $defstr = 'पुरी';
                                    }
                                    elseif (stristr($row['areaname_eng'],'pur'))
                                    {
                                      $defstr = 'पूर';
                                    }
                                    elseif (stristr($row['areaname_eng'],'vali') or stristr($row['areaname_eng'],'wali'))
                                    {
                                      $defstr = 'वली';
                                    }
                                    elseif (stristr($row['areaname_eng'],'rale'))
                                    {
                                      $defstr = 'राळे';
                                    }
                                    elseif (stristr($row['areaname_eng'],'lumb'))
                                    {
                                      $defstr = 'ळंुब';
                                    }
                                    
                                    elseif (stristr($row['areaname_eng'],'shwar'))
                                    {
                                      $defstr = 'श्वर';
                                    }
                                    elseif (stristr($row['areaname_eng'],'halli'))
                                    {
                                      $defstr = 'हळ्ळी';
                                    }
                                    elseif (stristr($row['areaname_eng'],'nur') or stristr($row['areaname_eng'],'noor'))
                                    {
                                      $defstr = 'नूर';
                                    }
                                    elseif (stristr($row['areaname_eng'],'giri'))
                                    {
                                      $defstr = 'गिरी';
                                    }
                                    elseif (stristr($row['areaname_eng'],'peth'))
                                    {
                                      $defstr = 'पेठ';
                                    }
                                    elseif (stristr($row['areaname_eng'],'tanda'))
                                    {
                                      $defstr = 'तांडा';
                                    }
                                    elseif (stristr($row['areaname_eng'],'lagi'))
                                    {
                                      $defstr = 'लगी';
                                    }
                                    elseif (stristr($row['areaname_eng'],'nal'))
                                    {
                                      $defstr = 'नाळ';
                                    }
                                    elseif (stristr($row['areaname_eng'],'rgi'))
                                    {
                                      $defstr = 'र्गी';
                                    }
                                    elseif (stristr($row['areaname_eng'],'lyal'))
                                    {
                                      $defstr = 'ल्याळ';
                                    }
                                    elseif (stristr($row['areaname_eng'],'vade'))
                                    {
                                      $defstr = 'वडे';
                                    }
                                    elseif (stristr($row['areaname_eng'],'gad'))
                                    {
                                      $defstr = 'गड';
                                    }
                                    elseif (stristr($row['areaname_eng'],'bad'))
                                    {
                                      $defstr = 'बाद';
                                    }
                                    elseif (stristr($row['areaname_eng'],'appa'))
                                    {
                                      $defstr = 'अ्प्पा';
                                    }
                                    elseif (stristr($row['areaname_eng'],'khop'))
                                    {
                                      $defstr = 'खोप';
                                    }
                                    elseif (stristr($row['areaname_eng'],'lage') or stristr($row['areaname_eng'],'lge'))
                                    {
                                      $defstr = 'लगे';
                                    }
                                    elseif (stristr($row['areaname_eng'],'devi'))
                                    {
                                      $defstr = 'देवी';
                                    }
                                    elseif (stristr($row['areaname_eng'],'vane'))
                                    {
                                      $defstr = 'वणे';
                                    }
                                    elseif (stristr($row['areaname_eng'],'goli'))
                                    {
                                      $defstr = 'गोळी';
                                    }
                                    elseif (stristr($row['areaname_eng'],'hatti'))
                                    {
                                      $defstr = 'हट्टी';
                                    }
                                    elseif (stristr($row['areaname_eng'],'kuppi'))
                                    {
                                      $defstr = 'कुप्पी';
                                    }
                                    elseif (stristr($row['areaname_eng'],'guppi'))
                                    {
                                      $defstr = 'गुप्पी';
                                    }
                                    elseif (stristr($row['areaname_eng'],'guchhi'))
                                    {
                                      $defstr = 'गुच्ची';
                                    }
                                    elseif (stristr($row['areaname_eng'],'kud'))
                                    {
                                      $defstr = 'कूड';
                                    }
                                    elseif (stristr($row['areaname_eng'],'katti'))
                                    {
                                      $defstr = 'कट्टी';
                                    }
                                    elseif (stristr($row['areaname_eng'],'kop'))
                                    {
                                      $defstr = 'कोप';
                                    }
                                    elseif (stristr($row['areaname_eng'],'bbal'))
                                    {
                                      $defstr = 'ब्बळ';
                                    }
                                    elseif (stristr($row['areaname_eng'],'dugi'))
                                    {
                                      $defstr = 'दूगी';
                                    }
                                    elseif (stristr($row['areaname_eng'],'nali'))
                                    {
                                      $defstr = 'नाळी';
                                    }
                                    elseif (stristr($row['areaname_eng'],'yal'))
                                    {
                                      $defstr = '्याळ';
                                    }
                                    elseif (stristr($row['areaname_eng'],'gavan') or stristr($row['areaname_eng'],'ghavan') or stristr($row['areaname_eng'],'gavhan'))
                                    {
                                      $defstr = 'गव्हाण';
                                    }
                                    elseif (stristr($row['areaname_eng'],'shinge') )
                                    {
                                      $defstr = 'शिंगे';
                                    }
                                    elseif (stristr($row['areaname_eng'],'ner'))
                                    {
                                      $defstr = 'नेर';
                                    }
                                    elseif (stristr($row['areaname_eng'],'shiras'))
                                    {
                                      $defstr = 'शिरस';
                                    }
                                    elseif (stristr($row['areaname_eng'],'bhavi'))
                                    {
                                      $defstr = 'भावी';
                                    }
                                    elseif (stristr($row['areaname_eng'],'bavi'))
                                    {
                                      $defstr = 'बावी';
                                    }
                                    elseif (stristr($row['areaname_eng'],'patta'))
                                    {
                                      $defstr = 'पट्टा';
                                    }
                                    elseif (stristr($row['areaname_eng'],'chinch'))
                                    {
                                      $defstr = 'चिंच';
                                    }
                                    elseif (stristr($row['areaname_eng'],'limb'))
                                    {
                                      $defstr = 'लिंब';
                                    }
                                    elseif (stristr($row['areaname_eng'],'rni'))
                                    {
                                      $defstr = 'र्णी';
                                    }
                                    elseif (stristr($row['areaname_eng'],'fal'))
                                    {
                                      $defstr = 'फळ';
                                    }
                                    elseif (stristr($row['areaname_eng'],'than'))
                                    {
                                      $defstr = 'ठाण';
                                    }
                                    elseif (stristr($row['areaname_eng'],'sangi'))
                                    {
                                      $defstr = 'संगी';
                                    }
                                    elseif (stristr($row['areaname_eng'],'hal'))
                                    {
                                      $defstr = 'हाळ';
                                    }
                                    elseif (stristr($row['areaname_eng'],'kot'))
                                    {
                                      $defstr = 'कोट';
                                    }
                                    elseif (stristr($row['areaname_eng'],'sond'))
                                    {
                                      $defstr = 'सोंड';
                                    }
                                    elseif (stristr($row['areaname_eng'],'wandi'))
                                    {
                                      $defstr = 'वंडी';
                                    }
                                    elseif (stristr($row['areaname_eng'],'wande'))
                                    {
                                      $defstr = 'वंडे';
                                    }
                                    elseif (stristr($row['areaname_eng'],'shet'))
                                    {
                                      $defstr = 'शेत';
                                    }
                                    elseif (stristr($row['areaname_eng'],'vihire'))
                                    {
                                      $defstr = 'विहीरे';
                                    }
                                    elseif (stristr($row['areaname_eng'],'vihire'))
                                    {
                                      $defstr = 'हिरे';
                                    }
                                    elseif (stristr($row['areaname_eng'],'vire'))
                                    {
                                      $defstr = 'विरे';
                                    }
                                    elseif (stristr($row['areaname_eng'],'dara'))
                                    {
                                      $defstr = 'दरा';
                                    }
                                    elseif (stristr($row['areaname_eng'],'dare'))
                                    {
                                      $defstr = 'दरे';
                                    }
                                    elseif (stristr($row['areaname_eng'],'khel'))
                                    {
                                      $defstr = 'खेल';
                                    }
                                    elseif (stristr($row['areaname_eng'],'gan'))
                                    {
                                      $defstr = 'गण';
                                    }
                                    elseif (stristr($row['areaname_eng'],'wedhe') or stristr($row['areaname_eng'],'vedhe'))
                                    {
                                      $defstr = 'वेढे';
                                    }
                                    elseif (stristr($row['areaname_eng'],'kute'))
                                    {
                                      $defstr = 'कुटे';
                                    }
                                    elseif (stristr($row['areaname_eng'],'van'))
                                    {
                                      $defstr = 'वन';
                                    }
                                    elseif (stristr($row['areaname_eng'],'lane'))
                                    {
                                      $defstr = 'ळणे';
                                    }
                                    elseif (stristr($row['areaname_eng'],'mal'))
                                    {
                                      $defstr = 'माळ';
                                    }
                                    elseif (stristr($row['areaname_eng'],'kup'))
                                    {
                                      $defstr = 'कुप';
                                    }
                                    elseif (stristr($row['areaname_eng'],'khindi'))
                                    {
                                      $defstr = 'खिंडी';
                                    }
                                    elseif (stristr($row['areaname_eng'],'rai'))
                                    {
                                      $defstr = 'राई';
                                    }
                                    elseif (stristr($row['areaname_eng'],'takali'))
                                    {
                                      $defstr = 'टाकळी';
                                    }


                                    if (stristr($row['areaname_eng'],'khalasa') or stristr($row['areaname_eng'],'khalsa'))
                                    {
                                      $defstr = ' खालसा';
                                    }
                                    elseif (stristr($row['areaname_eng'],'dumala') or stristr($row['areaname_eng'],'dhumala'))
                                    {
                                      $defstr = ' दुमाला';
                                    }
                                    elseif (stristr($row['areaname_eng'],'pathar'))
                                    {
                                      $defstr = ' पठार';
                                    }
                                    elseif (stristr($row['areaname_eng'],' bk') or stristr($row['areaname_eng'],' bk.'))
                                    {
                                      $defstr = ' बु';
                                    }
                                    elseif (stristr($row['areaname_eng'],' kh') or stristr($row['areaname_eng'],' kh.'))
                                    {
                                      $defstr = ' खु';
                                    }
                                    elseif (stristr($row['areaname_eng'],' k.') or stristr($row['areaname_eng'],' kasaba'))
                                    {
                                      $defstr = ' कसबा';
                                    }
                                    elseif (stristr($row['areaname_eng'],'mauje') or stristr($row['areaname_eng'],'mouje'))
                                    {
                                      $defstr = 'मौजे';
                                    }
                                    elseif (stristr($row['areaname_eng'],'kasabe') or stristr($row['areaname_eng'],'kasbe'))
                                    {
                                      $defstr = 'कसबे';
                                    }
                                    elseif (stristr($row['areaname_eng'],'k.'))
                                    {
                                      $defstr = 'कसबा';
                                    }elseif (stristr($row['areaname_eng'],'takali'))
                                    {
                                      $defstr = ' टाकळी';
                                    }
                                    if (stristr($row['areaname_eng'],'tarf') or stristr($row['areaname_eng'],'t.'))
                                    {
                                      $defstr = ' तर्फ ';
                                    }
                                    /*$villnm = fedvillagename($connection,$row['areaname_eng']);
                                    if (strlen($villnm)>0)
                                    {
                                      $defstr = $villnm;
                                    }*/
                                    echo '<td><input type="text" style="font-size:12pt;height:30px" name="areaname" id="areaname"  value="'.$row['areaname'].$defstr.'" autofocus="autofocus" autocomplete="off"></td>';
                                    echo '</tr>';

                                    echo '<tr>';
                                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="areaid" id="areaid" value ="'.$areaid_de.'"></td>';
                                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="stateid" id="areaid" value ="'.$stateid_de.'"></td>';
                                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="districtid" id="areaid" value ="'.$districtid_de.'"></td>';
                                    echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="subdistrictid" id="areaid" value ="'.$subdistrictid_de.'"></td>';
                                    echo '</tr>';

                                    echo '<td></td>';  
                                    echo '</tr>';

                                    echo '<tr>';
                                    echo '<td><input type="submit" style="width:200px;font-size:13pt;" onclick="showProgressCursor();" value="Save"/>';
                                    echo '</tr>';

                                    echo '<tr style="font-size:13px">';      
                                    echo '<div id="progressMessage" style="display:none; padding:5px; border:0px Solid #000">';
                                    echo '<div id="activityIndicator">&nbsp;</div>';
                                    echo '<label style="font:bold; display:block; padding:5px; text-align: centre;"
                                         id="progressMessageLbl">Updating...</label>';
                                    echo '</div>';
                                    echo '</tr>';

                                    echo '<tr>';
                                    echo '<td><lable for="">If timeout after submit, please Reload Page. (Do not select Back Option) </lable>';
                                    echo '</tr>';

                                echo '</table>';
                                echo '</form>';
                                echo '</section>';
                            }
                        ?>

                    </div>
                    <div class="clearfix"></div>
        </div>
        </div>
        </div>
    </div>
        <footer>
      <div class="container">
        <div class="row">
            <div align="center">
                      <script async async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- swapp_fp_topic_footer -->
                    <ins class="adsbygoogle"
                         style="display:block"
                         data-ad-client="ca-pub-1879145555378586"
                         data-ad-slot="7623998956"
                         data-ad-format="auto"></ins>
                    <script async>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                    </div>
            <div class="col-lg-121 text-center">
              <div class="copyright">Web Portal is developed and maintained by Swapp Software Application. Copyright &copy;2016 Swapp Software Application</div>
            </div>
        </div>
      </div>
    </footer>
        <!-- jQuery -->
    <script async src="../js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script async src="js/bootstrap.min.js"></script>
    <script async type="application/ld+json">
    {
      "@context" : "http://schema.org",
      "@type" : "Organization",
            "name" : ,
            "url" : ,
            "sameAs" : [ ,         ]
            "contactPoint" : [{
        "@type" : "ContactPoint",
        "telephone" : ,
        "contactType" : "customer service"
      }]
          }
</script>
    </body>
</html>