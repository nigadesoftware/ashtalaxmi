<?php
    session_start();
    require("../info/ncryptdcrypt.php");
    $stateid_de = 27;

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
	    <title>Maharashtra Districtwise Sugar Factory List</title>
      <meta property="og:image" content="http://www.swapp.co.in/img/indiandistricts.png">
      <meta property="og:image:type" content="image/png">
      <meta property="og:image:width" content="200">
      <meta property="og:image:height" content="200">
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
  <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-format="autorelaxed"
     data-ad-client="ca-pub-1879145555378586"
     data-ad-slot="5534798952"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script> 
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
                    <li><a href='/site/indianstatelist.php'>Indian State List</a>
                    <li><a href='http://cloudsugarerp.blogspot.in'>Sugar Blog</a></li>
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
            require("../info/phpsqlajax_dbinfo.php");
            echo '<img border="0" alt="Indian Districts" src="../img/indiandistricts.png">';
            // Opens a connection to a MySQL server
            $connection=mysqli_connect($hostname, $username, $password, $database);
            // Check connection
            if (mysqli_connect_errno())
              {
                echo "Communication Error1";
              }
              $query = "SELECT d.districtid,d.districtname,d.districtname_eng 
                FROM subdistrict s,district d, state t,markers m
                where s.districtid=d.districtid and d.stateid=t.stateid and s.subdistrictid=m.subdistrictid and t.stateid=".$stateid_de." group by d.districtid,d.districtname_eng order by d.districtname_eng";                
            mysqli_query($connection,'SET NAMES UTF8');
            $result = mysqli_query($connection,$query);
            if (!$result)
            {
              die('Communication Error2');
            }
            echo '<p>';
            // Iterate through the rows, adding XML nodes for each
            echo '<label for="indianstatelist">Country - India</br>State - '.statename($connection,$stateid_de,0).'</br>Districtwise Sugar Factories List</br> </label></br>';
            echo '<label for="indianstatelist">Country - India</br>देश - भारत</br>State - '.statename($connection,$stateid_de,0).'</br>राज्य - '.statename($connection,$stateid_de,1).'</br>Districtwise Sugar Factories List</br>जिल्हा / जिला साखर कारखाना यादी</br></label></br>';
            $stateid_en = fnEncrypt($stateid_de);
            $srnumber =1;
            while ($row = @mysqli_fetch_assoc($result))
            {
              $districtid_en = fnEncrypt('00000'.$row['districtid']);
              echo '<a href="../site/maharashtradistrictsugarfactorylist.php?districtid='.$districtid_en.'">'.$srnumber.'. '.$row['districtname_eng'].'</a></br>';
              echo '<a href="../site/maharashtradistrictsugarfactorylist.php?districtid='.$districtid_en.'">'.$row['districtname'].'</a></br>';
                      $srnumber++;
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
    <script async src="../js/bootstrap.min.js"></script>
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