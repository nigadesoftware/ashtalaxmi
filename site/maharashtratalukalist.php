<?php
    session_start();
    require("../info/ncryptdcrypt.php");
    require("../info/phpsqlajax_dbinfo.php");
    if (isset($_GET['flag']))
    {
      //$flag_de = fnDecrypt($_GET['flag']);
      $flag_de ='nocorrection';
    }
    else
    {
      $flag_de ='nocorrection';
    }
    
  // Opens a connection to a MySQL server
  $connection=mysqli_connect($hostname, $username, $password, $database);
  // Check connection
  if (mysqli_connect_errno())
    {
      echo "Communication Error1";
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
                    </li>
                    <li><a href='/site/userarea.php'>User Area</a>
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
            $query = "SELECT s.*,d.districtname,d.districtname_eng,v.divisionid,v.divisionname,v.divisionname_eng FROM subdistrict s, district d,division v where s.districtid=d.districtid and d.divisionid=v.divisionid and d.stateid=27 order by v.divisionname,districtname,districtid,subdistrictname";                
            mysqli_query($connection,'SET NAMES UTF8');
            $result = mysqli_query($connection,$query);
            if (!$result)
            {
              die('Communication Error2');
            }
            // Iterate through the rows, adding XML nodes for each
            echo '<p>';
                        echo '<label for="indianstatelist">Maharashtra Divisionwise Districtwise Taluka List'.'</br>महाराष्ट्रातील विभागवार जिल्हावार तालुका यादी'.'</label></br>';
                        //$stateid_en = $_GET['stateid'];
                        //$districtid_en = $_GET['districtid'];
            echo '</br>';
            $lastdivisionid=0;
            $lastdistrictid=0;
            $stateid_en = fnEncrypt('000000027');
            $srnumber2=1;
            while ($row = @mysqli_fetch_assoc($result))
            {
              if ($lastdivisionid !== $row['divisionid'])
              {
                echo '<label style="background-color:#fff;color:#a00" for="indianstatelist">'.$srnumber2.'] '.$row['divisionname_eng'].' Division '.'('.$row['divisionname'].' विभाग)'.'</label></br>';
                $lastdivisionid = $row['divisionid'];
                $divisionid_en = fnEncrypt('00000'.$row['divisionid']);
                $srnumber2++;
                $srnumber=1;
              }
              if ($lastdistrictid !== $row['districtid'])
              {
                echo '<label style="background-color:#fff;color:#040" for="indianstatelist">'.$srnumber.') '.$row['districtname_eng'].' District '.'('.$row['districtname'].' जिल्हा)'.'</label></br>';
                $lastdistrictid = $row['districtid'];
                $districtid_en = fnEncrypt('00000'.$row['districtid']);
                $srnumber++;
                $srnumber1=1;
              }
              $subdistrictid_en = fnEncrypt('00000'.$row['subdistrictid']);
              if ($flag_de=='correction')
              {
                $flag_en = fnEncrypt($flag_de);
                echo '<a href="../site/indianstatedistrictsubdistrictarealist.php?stateid='.$stateid_en.'&districtid='.$districtid_en.'&subdistrictid='.$subdistrictid_en.'&flag='.$flag_en.'">'.$srnumber1.'. '.$row['subdistrictname_eng'].' Taluka</a>';
                echo '<a href="../site/indianstatedistrictsubdistrictarealist.php?stateid='.$stateid_en.'&districtid='.$districtid_en.'&subdistrictid='.$subdistrictid_en.'&flag='.$flag_en.'"></br>'.$row['subdistrictname'].' तालुका</a>';
                
              }
              else
              {
                echo '<a href="../site/indianstatedistrictsubdistrictarealist.php?stateid='.$stateid_en.'&districtid='.$districtid_en.'&subdistrictid='.$subdistrictid_en.'">'.$srnumber1.'. '.$row['subdistrictname_eng'].' Taluka</a>';
                echo '<a href="../site/indianstatedistrictsubdistrictarealist.php?stateid='.$stateid_en.'&districtid='.$districtid_en.'&subdistrictid='.$subdistrictid_en.'"></br>'.$row['subdistrictname'].' तालुका</a>';
                
              }
                echo '</br>';
                $srnumber1++;
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