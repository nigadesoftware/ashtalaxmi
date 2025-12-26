<?php
    session_start();
    require("../info/ncryptdcrypt.php");
    if (isset($_GET['stateid'])==true)
    {
      $stateid_de =(int) fnDecrypt($_GET['stateid']); 
    }
    else
    {
      $stateid_de = 27;
    }

    if ($stateid_de == 0)
    {
      $stateid_de = 27;
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
?>
<!DOCTYPE html>
<html lang="en">
	<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Swapp Software Application">
    <meta name="author" content="Swapp Software Application">
    <title>Indian States and Union Territories List  (भारतातील राज्ये व केंद्र शासित प्रदेश यादी) </title>
    <meta property="og:image" content="http://www.swapp.co.in/img/indianstates.png">
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
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script async src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script async src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58db35533dec30001259e677&product=sticky-share-buttons"></script>
<script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58db35533dec30001259e677&product=sticky-share-buttons"></script>
</head>
	<body>
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-1879145555378586",
    enable_page_level_ads: true
  });
</script>
    <header>
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
                    <?php  
                      if (isset($_SESSION['fb_access_token'])==true)
                      {
                        echo '<li><a href="/site/userarea.php">User Area</a><li>';
                      }
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
            require("../info/phpsqlajax_dbinfo.php");
                echo '<img border="0" alt="Indian States" src="../img/indianstates.png">';
                //exit;   
            // Opens a connection to a MySQL server
            $connection=mysqli_connect($hostname, $username, $password, $database);
            // Check connection
            if (mysqli_connect_errno())
              {
                echo "Communication Error1";
              }
              $query = "SELECT * FROM state order by statename_eng asc";                
            mysqli_query($connection,'SET NAMES UTF8');
            $result = mysqli_query($connection,$query);
            if (!$result)
            {
              die('Communication Error2');
            }
            echo '<p>';
            // Iterate through the rows, adding XML nodes for each
            echo '<label for="indianstatelist">Indian States and their Capitals List</br> </label></br>';
            $stateid_en = fnEncrypt($stateid_de);
            $srnumber =1;
            while ($row = @mysqli_fetch_assoc($result))
            {
              $stateid_en = fnEncrypt('00000'.$row['stateid']);
              echo '<a href="../site/indianstatedistrictlist.php?stateid='.$stateid_en.'"">'.$srnumber.'. '.$row['statename_eng'].' - '.$row['capital_eng'].'</a></br>';
              echo '<a lang="hi" href="../site/indianstatedistrictlist.php?stateid='.$stateid_en.'"">'.$row['statename'].' - '.$row['capital'].'</a></br>';
              $srnumber++;
            }
            echo '</br>Indian Union Territories and their Capitals List</br>';
            echo '<a >1. Andman and Nicobar - Port Blair</a></br>';
            echo '<a >अंदमान आणि निकोबार - पोर्ट ब्लेअर</a></br>';
            echo '<a >2. Chandigarh - Chandigarh</a></br>';
            echo '<a >चंडीगड - चंडीगड</a></br>';
            echo '<a >3. Dadara Nagar Haveli - Silvassa</a></br>';
            echo '<a >दादरा नगर हवेली - सिल्वासा</a></br>';
            echo '<a >4. Daman and Diu - Daman</a></br>';
            echo '<a >दमन दिव - दमन</a></br>';
            echo '<a >5. Lakshadweep - Kavaratti</a></br>';
            echo '<a >लक्षद्वीप - कवरत्ती</a></br>';
            echo '<a >6. National Capital Territory of Delhi - Delhi</a></br>';
            echo '<a >राष्टीय राजधानी प्रदेश - दिल्ली</a></br>';
            echo '<a >7.Pondicherry - Puducherry</a></br>';
            echo '<a >पांडिचेरी - पुदुचेरू</a></br>';
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