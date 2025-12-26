<?php
    session_start();
    require("../info/ncryptdcrypt.php");
    require("../info/phpsqlajax_dbinfo.php");
  // Opens a connection to a MySQL server
  $connection=mysqli_connect($hostname, $username, $password, $database);
  mysqli_query($connection,'SET NAMES UTF8');
  // Check connection
  if (mysqli_connect_errno())
    {
      echo "Communication Error1";
    }
        if (isset($_GET['stateid'])==true)
        {
            $stateid_de =(int) fnDecrypt($_GET['stateid']); 
        }
        else
        {
          $stateid_de = 27;
        }

        if (isset($_GET['divisionid'])==true)
        {
          $divisionid_de =(int) fnDecrypt($_GET['divisionid']); 
        }
        else
        {
          $divisionid_de = 0;
        }

        if (isset($_GET['districtid'])==true)
        {
          $districtid_de =(int) fnDecrypt($_GET['districtid']); 
        }
        else
        {
          $districtid_de = 521;
        }

    if ($stateid_de == 0)
    {
      $stateid_de = 27;
    }
    if ($districtid_de == 0)
    {
      $districtid_de = 521;
    }
    $stateid_de = stateid($connection,$districtid_de);
    $stateid_en = fnEncrypt($stateid_de);
    $districtid_en = fnEncrypt($districtid_de);

    function statedataready(&$connection,$stateid)
    {
      $query = "SELECT s.dataready FROM state s where s.stateid=".$stateid;               
    $result = mysqli_query($connection,$query);
    if ($row = @mysqli_fetch_assoc($result))
    {
      return $row['dataready'];
    }
    else
    {
      return '0';
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

    function statename (&$connection,$stateid,$lng)
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
?>
<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta name="description" content="Swapp Software Application">
	    <meta name="author" content="Swapp Software Application">
	    <?php
      echo '<title>'.districtname($connection,$districtid_de,0).' District Taluka List '.'('.districtname($connection,$districtid_de,1).' जिल्हा तालुका / तहसील यादी)'.'</title>';
      ?>
      <meta property="og:image" content="http://www.swapp.co.in/img/indiantalukas.png">
      <meta property="og:image:type" content="image/png">
      <meta property="og:image:width" content="200">
      <meta property="og:image:height" content="200">
	    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
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
	<script async type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58db35533dec30001259e677&product=sticky-share-buttons"></script>
</head>
	<body>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-1879145555378586",
    enable_page_level_ads: true
  });
</script>
    <script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58db35533dec30001259e677&product=sticky-share-buttons"></script>
 
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
                    $divisionid_en = fnEncrypt($divisionid_de);
                    echo '<li><a href="/site/indianstatedistrictlist.php?stateid='.$stateid_en.'&divisionid='.$divisionid_en.'">District List</a></li>';
                    ?>
                    </li>
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
            echo '<img border="0" alt="Indian Talukas" src="../img/indiantalukas.png">';
            $query = "SELECT * FROM subdistrict where districtid=".$districtid_de." order by subdistrictname";                
            mysqli_query($connection,'SET NAMES UTF8');
            $result = mysqli_query($connection,$query);
            if (!$result)
            {
              die('Communication Error2');
            }
            // Iterate through the rows, adding XML nodes for each
            echo '<p>';
                        echo '<label for="indianstatelist">Country - India</br>State - '.statename($connection,$stateid_de,0).'</br>'.'</br>District - '.districtname($connection,$districtid_de,0).'</br>Taluka / Tehsil List</br></label></br>';
                        /*echo '<label for="indianstatelist">Country - India</br>देश - भारत</br>State - '.statename($connection,$stateid_de,0).'</br>राज्य / प्रदेश - '.statename($connection,$stateid_de,1).'</br>District - '.districtname($connection,$districtid_de,0).'</br> जिल्हा / जिला - '.districtname($connection,$districtid_de,1).'</br>Taluka / Tehsil List</br>तालुका / तहसील यादी / सूची</br></label></br>';*/
                        $srnumber =1;
                        //$stateid_en = $_GET['stateid'];
                        //$districtid_en = $_GET['districtid'];
            echo '</br>';
            while ($row = @mysqli_fetch_assoc($result))
            {
              $subdistrictid_en = fnEncrypt('00000'.$row['subdistrictid']);
              if (statedataready($connection,$stateid_de) == 1)
              {
                echo '<a href="../site/indianstatedistrictsubdistrictarealist.php?stateid='.$stateid_en.'&districtid='.$districtid_en.'&subdistrictid='.$subdistrictid_en.'"></br>'.$srnumber.'. '.$row['subdistrictname_eng'].' Taluka / Tehsil Village List</a>';
                echo '<a href="../site/indianstatedistrictsubdistrictarealist.php?stateid='.$stateid_en.'&districtid='.$districtid_en.'&subdistrictid='.$subdistrictid_en.'"></br>'.$row['subdistrictname'].' तालुका / तहसील गाव यादी</a>';
                /*echo '<a href="../site/newsubdistrict.php?stateid='.$stateid_en.'&districtid='.$districtid_en.'&subdistrictid='.$subdistrictid_en.'"></br>City Map</a>';
                echo '<a href="../site/newsubdistrict.php?stateid='.$stateid_en.'&districtid='.$districtid_en.'&subdistrictid='.$subdistrictid_en.'"></br>शहर नकाशा</a></br>';
                echo '</br>';*/
              }
              else
              {
                echo '<a >'.$srnumber.'. '.$row['subdistrictname_eng'].' '.'</a></br>';
              }
                      $srnumber++;
            }
            echo '<a style="color:#080;" href="http://www.'.districtname($connection,$districtid_de,0).'.nic.in"></br>'.districtname($connection,$districtid_de,0).' District Collector Office Website</br>'/*.districtname($connection,$districtid_de,1).' जिल्हाधिकारी कार्यालय संकेतस्थळ'*/.'</a>';
            if ($stateid_de==27)
            {
              echo '<a style="color:#080;" href="https://mahabhulekh.maharashtra.gov.in"></br>7/12 '.'( सात बारा ७/१२)'.' </a>';
            }
            echo '</p>';
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