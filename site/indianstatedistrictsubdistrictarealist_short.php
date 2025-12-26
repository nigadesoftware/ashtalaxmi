<?php
    session_start();
    require("../info/ncryptdcrypt.php");
    require("../info/phpsqlajax_dbinfo.php");
    //returns true, if domain is availible, false if not
       function isDomainAvailible($domain)
       {
               //check, if a valid url is provided
               if(!filter_var($domain, FILTER_VALIDATE_URL))
               {
                       return false;
               }

               //initialize curl
               $curlInit = curl_init($domain);
               curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
               curl_setopt($curlInit,CURLOPT_HEADER,true);
               curl_setopt($curlInit,CURLOPT_NOBODY,true);
               curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

               //get answer
               $response = curl_exec($curlInit);

               curl_close($curlInit);

               if ($response) return true;

               return false;
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
    function fbusername(&$connection,$userid)
  {
    $query1 = "SELECT crfbuser
                FROM area a
                where a.cruserid=$userid
                order by crdatetime desc
                limit 1";
    mysqli_query($connection,'SET NAMES UTF8');
    $result = mysqli_query($connection,$query1);
    if (!$result)
    {
      die('Communication Error22');
    }
    if ($row1 = @mysqli_fetch_assoc($result))
    {
      return $row1['crfbuser'];
    }
    else
    {
      return '';
    }
  }
  // Opens a connection to a MySQL server
  $connection=mysqli_connect($hostname, $username, $password, $database);
  mysqli_query($connection,'SET NAMES UTF8');
  // Check connection
  if (mysqli_connect_errno())
    {
      echo "Communication Error1";
    }
    
    if (isset($_POST['state'])==true)
    {
      $stateid_de =$_POST['state']; 
      $_SESSION['stateid'] = $stateid_de;
    }
    elseif (isset($_GET['stateid'])==true)
    {
      $stateid_de =(int) fnDecrypt($_GET['stateid']); 
    }
    else
    {
      $stateid_de = 27;
    }

    if (isset($_POST['district'])==true)
    {
      $districtid_de =$_POST['district']; 
      $_SESSION['districtid'] = $districtid_de;
    }
    elseif (isset($_GET['districtid'])==true)
    {
      $districtid_de =(int) fnDecrypt($_GET['districtid']); 
    }
    else
    {
      $districtid_de = 521;
    }

    if (isset($_POST['subdistrict'])==true)
    {
      $subdistrictid_de =$_POST['subdistrict']; 
      $_SESSION['subdistrictid'] = $subdistrictid_de;
    }
    elseif (isset($_GET['subdistrictid'])==true)
    {
      $subdistrictid_de =(int) fnDecrypt($_GET['subdistrictid']); 
    }
    else
    {
      $subdistrictid_de = 4196;
    }

    if ($stateid_de == 0)
    {
      $stateid_de = 27;
    }
    if ($districtid_de == 0)
    {
      $districtid_de = 521;
    }
    if ($subdistrictid_de == 0)
    {
      $subdistrictid_de = 4196;
    }
    if (isset($_GET['user'])==true)
    {
      $userid =fnDecrypt($_GET['user']); 
    }
    $districtid_de = districtid($connection,$subdistrictid_de);
    $stateid_de = stateid($connection,$districtid_de);

    $stateid_en = fnEncrypt($stateid_de);
    $districtid_en = fnEncrypt($districtid_de);
    $subdistrictid_en = fnEncrypt($subdistrictid_de);

    function statename(&$connection,$stateid,$lng)
    {
      $query = "SELECT s.stateid,s.statename,s.statename_eng FROM state s where s.stateid=".$stateid;               
    $result = mysqli_query($connection,$query);
    if ($row = @mysqli_fetch_assoc($result))
    {
      if ($lng == 0)
      {
        return $row['statename_eng'];
      }
      elseif ($lng == 1)
      {
        return $row['statename'];
      }
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
		<title>Indian State List</title>
			
		</style>
    <style type="text/css">
    del { 
        text-decoration: line-through;
        background-color: #fbb6c2;
        color: #555;
    }
    </style>
    
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
                    <li><a href='/index.php'>Home</a></li>
                    <?php  
                      if (isset($_SESSION['fb_access_token'])==true)
                      {
                        echo '<li><a href="/site/userarea.php">User Area</a><li>';
                      }
                    ?>
                    <?php
                    echo '<li><a href="/site/villagenametranslation.php">Village Name Translation</a></li>';
                    ?>
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
					       <div class="mobile_header_adv">
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
                  <div style="float:left">
					<?php
            if (isset($_SESSION['fb_access_token'])==true)
              {
                echo '<label for="">Welcome Facebook User '.$_SESSION['fb_name'].'!</label>';
              }
              else
              {
                $_SESSION['preloginurl'] = $_SERVER['REQUEST_URI'];
                echo '<label><a style="color:#000" href="../site/login.php">
                <img border="0" alt="Login" src="../img/userlogin.png"></br>Portal Login
                </a></label>';
                exit;   
              }                  
            $query1 = "SELECT a.transid,a.cruserid,a.areaid,a.areaname,a.areaname_eng,a.areaname_cor,s.subdistrictid,s.subdistrictname,s.subdistrictname_eng,d.districtid,d.districtname,d.districtname_eng,t.stateid,t.statename,t.statename_eng 
                FROM area a,subdistrict s,district d, state t
                where a.subdistrictid=s.subdistrictid and s.districtid=d.districtid and d.stateid=t.stateid and length(areaname)=0 and s.subdistrictid=".$subdistrictid_de." and (TIMESTAMPDIFF(MINUTE,lockdatetime,sysdate()) is null or TIMESTAMPDIFF(MINUTE,lockdatetime,sysdate())>10) order by areaid limit 5";
            mysqli_query($connection,'SET NAMES UTF8');
            $result = mysqli_query($connection,$query1);
            if (!$result)
            {
              die('Communication Error2');
            }
            // Iterate through the rows, adding XML nodes for each
            echo '<p>';
            echo '<label for="indianstatelist">District - '.districtname($connection,$districtid_de,0).'</br>जिल्हा - '.districtname($connection,$districtid_de,1).'</br>Taluka - '.subdistrictname($connection,$subdistrictid_de,0).'</br>तालुका - '.subdistrictname($connection,$subdistrictid_de,1).'</br>Village List</br>गावांची यादी</label></br>';
            echo '<label for="indianstatelist">Select village from the list</br>खालील यादीतील गाव निवडा</label></br>';
            $srnumber =1;
            while ($row1 = @mysqli_fetch_assoc($result))
            {
              $areaid_en = fnEncrypt('00000'.$row1['areaid']);
                echo '<a href="../site/updatevillagename.php?stateid='.$stateid_en.'&districtid='.$districtid_en.'&subdistrictid='.$subdistrictid_en.'&areaid='.$areaid_en.'" onclick="return showProgressCursor();">'.$srnumber.'. '.' '.$row1['areaname_eng'].' </a></br>';
              $srnumber++;
            }
            echo '</p>';
          echo '<tr style="font-size:13px">';      
          echo '<div id="progressMessage" style="display:none; padding:5px; border:0px Solid #000">';
          echo '<div id="activityIndicator">&nbsp;</div>';
          echo '<label style="font:bold; display:block; padding:5px; text-align: centre;"
               id="progressMessageLbl">Loading...</label>';
          echo '</div>';
          echo '</tr>';
          ?>
					</div>
          <div class="sky_scr_adv">
            <script async async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- swapp_skyscr_1 -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:300px;height:600px"
                 data-ad-client="ca-pub-1879145555378586"
                 data-ad-slot="9452155754"></ins>
            <script async>
            (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
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