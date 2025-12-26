<?php
    session_start();
    require("../info/ncryptdcrypt.php");
    require("../info/ncryptdcrypt_old.php");
    require("../info/phpsqlajax_dbinfo.php");
    if (isset($_GET['factoryid'])==true)
    {
      $factoryid_de =(int) fnDecrypt($_GET['factoryid']); 
    }
    else
    {
      $factoryid_de = 0;
    }
    if (!isset($factoryid_de) or $factoryid_de==0)
    {
      $factoryid_de =(int) fnDecrypt_old($_GET['factoryid']); 
    }
  // Opens a connection to a MySQL server
  $connection=mysqli_connect($hostname, $username, $password, $database);
  // Check connection
  if (mysqli_connect_errno())
    {
      echo "Communication Error1";
    }

    function factoryname(&$connection,$factoryid,$lng)
    {
      $query = "SELECT s.id,s.namelocal,s.name FROM markers s where s.id=".$factoryid;               
    $result = mysqli_query($connection,$query);
    if ($row = @mysqli_fetch_assoc($result))
    {
      if ($lng == 0)
      {
        return $row['name'];
      }
      elseif ($lng == 1)
      {
        return $row['namelocal'];
      }
    }
    else
    {
      return '';
    }
    }

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
          <?php
            require("../info/phpsqlajax_dbinfo.php");
            // Opens a connection to a MySQL server
            $connection=mysqli_connect($hostname, $username, $password, $database);
            // Check connection
            if (mysqli_connect_errno())
              {
                echo "Communication Error";
              }
            $query = "SELECT m.id,m.lat,m.lng,m.name,m.namelocal,m.type,m.address,s.subdistrictid,s.subdistrictname,s.subdistrictname_eng,d.districtid,d.districtname,d.districtname_eng,t.stateid,t.statename,t.statename_eng 
                FROM subdistrict s,district d, state t,markers m
                where s.districtid=d.districtid and d.stateid=t.stateid and s.subdistrictid=m.subdistrictid and m.id=".$factoryid_de." order by d.districtname_eng,statename_eng,districtname_eng,subdistrictname_eng,m.name";  
              //$query = "SELECT * FROM markers where type='Co-Operative Sugar Factory' order by name";               
            //echo $query;
            mysqli_query($connection,'SET NAMES UTF8');
            $result = mysqli_query($connection,$query);
            if (!$result)
            {
              die('Communication Error');
            }
            if ($row = @mysqli_fetch_assoc($result))
            {
                if ($row['type'] == 'Co-Operative Sugar Factory')
              {
                $factname =$row['name']." Co-Operative Sugar Factory "."(".$row['namelocal']." सहकारी साखर कारखाना ".")";
              }
              else
              {
                $factname =$row['name']." Sugar Factory "."(".$row['namelocal']." साखर कारखाना )";
              }
                $scr = '<script async src="http://maps.googleapis.com/maps/api/js?key=AIzaSyD_JEA6eU_nHfVwvmmJCMSDfB_mwj9LPOQ"></script>
            <script async>
            function initialize() {
              var mapProp = {
            center:new google.maps.LatLng('.$row['lat'].','.$row['lng'].'),
            zoom:14,
            mapTypeId:google.maps.MapTypeId.ROADMAP
            };
            var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
            }
            google.maps.event.addDomListener(window, "load", initialize);
            </script>';
                echo $scr;
            }
    ?>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta name="description" content="Swapp Software Application">
	    <meta name="author" content="Swapp Software Application">
	    <?php
      echo '<title>'.factoryname($connection,$factoryid_de,0).' ('.factoryname($connection,$factoryid_de,1).')'.'</title>';
	    ?>
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
		<title>Sugar Factory Map</title>
			
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
	<script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58db35533dec30001259e677&product=sticky-share-buttons"></script>
</head>
	<body>
  <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-format="autorelaxed"
     data-ad-client="ca-pub-1879145555378586"
     data-ad-slot="5534798952"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script> 
		<script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58db35533dec30001259e677&product=sticky-share-buttons"></script>
 
    <header>
			<!--<div><img src="../img/swapp_namelogo.png" width="150px" height="50px">
				</div>-->
			<div class="row">
            <div class="box">
                <div class="col-lg-121">
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
                    <div id="wrapper">
                    <nav id='cssmenu'>
                    <img  src="../img/wwwswappcoin1.png" alt="">
                    <div id="head-mobile"></div>
                    <div class="button"></div>
                    <ul>
                    <li><a href='/index.php'>Home</a>
                    </li>
                    <?php
                      if (isset($_SESSION['fb_access_token'])==true)
                      {
                        echo '<li><a href="/site/userarea.php">User Area</a><li>';
                      }
                    echo '<li><a href="/site/maharashtradistrictsugarfactorylist.php?districtid='.fnEncrypt('000000'.$row['districtid']).'">'.districtname($connection,$row['districtid'],0).' District Sugar Factory List</a>';
                    echo '<li>';
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
					<?php
            require("../info/phpsqlajax_dbinfo.php");
            /*if (isset($_SESSION['fb_access_token'])==true)
              {
                echo '<label for="">Welcome Facebook User '.$_SESSION['fb_name'].'!</label>';
              }
              else
              {
                echo '<label><a style="color:#000" href="../site/login.php">
                <img border="0" alt="Login" src="../img/userlogin.png"></br>Portal Login
                </a></label>';
                //exit;   
              }*/
            // Opens a connection to a MySQL server
            $connection=mysqli_connect($hostname, $username, $password, $database);
            // Check connection
            if (mysqli_connect_errno())
              {
                echo "Communication Error";
              }
            $query = "SELECT m.id,m.lat,m.lng,m.name,m.namelocal,m.address,m.type,s.subdistrictid,s.subdistrictname,s.subdistrictname_eng,d.districtid,d.districtname,d.districtname_eng,t.stateid,t.statename,t.statename_eng 
                FROM subdistrict s,district d, state t,markers m
                where s.districtid=d.districtid and d.stateid=t.stateid and s.subdistrictid=m.subdistrictid and m.id=".$factoryid_de." order by d.districtname_eng,statename_eng,districtname_eng,subdistrictname_eng,m.name";  
              //$query = "SELECT * FROM markers where type='Co-Operative Sugar Factory' order by name";               
            //echo $query;
            mysqli_query($connection,'SET NAMES UTF8');
            $result = mysqli_query($connection,$query);
            if (!$result)
            {
              die('Communication Error');
            }
            // Iterate through the rows, adding XML nodes for each
            if ($row = @mysqli_fetch_assoc($result))
            {
            echo '<p>';
            echo '<label for="FactoryMap" >Sugar Factory Map </label></br>';
            if ($row['type'] == 'Co-Operative Sugar Factory')
              {
                echo '<label for="FactoryMap" >'.$row['name'].' Co-Operative Sugar Factory (ssk)</label></br>';
              }
              else
              {
                echo '<label for="FactoryMap" >'.$row['name'].' Sugar Factory </label></br>';
              }
              echo '<label for="FactoryMap" >Place - '.$row['address'].'</label></br>';
              echo '<label for="FactoryMap" >Taluka - '.$row['subdistrictname_eng'].'</label></br>';
              echo '<label for="FactoryMap" >District - '.$row['districtname_eng'].'</label></br>';
              echo '<label for="FactoryMap" >State - '.$row['statename_eng'].'</label></br>';
              echo '<label for="FactoryMap" >Country - India</label></br>';
              echo '</p>';
              echo '<div class="fb-share-button" data-href="../site/factorynewmap.php?factoryid='.fnEncrypt($factoryid_de).'" data-layout="button" data-size="large" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.swapp.co.in%2F&amp;src=sdkpreparse">Share</a></div>';
            echo '</div>';
              ?>
              <div>
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
              <div id="googleMap"></div>
              <label style="font-size:9px" for="indianstatelist">* Map is based on address, result may show different place beacuase of spelling or similar name or other reason beyond control. Please verify upon other parameters. We are not resonsible for any kind of loss.</label>
          <?php    
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
              <div class="copyright">Web Portal is developed and maintained by Swapp Software Application.</div>
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