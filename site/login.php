<?php
session_unset();
session_start();
require_once '../facebook-sdk-v5/src/Facebook/autoload.php';
?>
<!DOCTYPE html>
<html lang="en">
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
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script async src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script async src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58db35533dec30001259e677&product=sticky-share-buttons"></script>
</head>
	<body>
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
                    <div style="float:left">
					<?php
					$fb = new Facebook\Facebook([
					  'app_id' => '658808974295074', // Replace {app-id} with your app id
					  'app_secret' => '9bb2e91dc9cd5d28dc5b3f78a46d1fc4',
					  'default_graph_version' => 'v2.2',
					  ]);

					$helper = $fb->getRedirectLoginHelper();

					$permissions = ['email']; // Optional permissions
          $loginUrl = $helper->getLoginUrl('http://'.$_SERVER["SERVER_NAME"].'/site/fb-callback.php', $permissions);
					echo '<a style="color:#fff;background-color:#004;font-size:16px;" href="' . htmlspecialchars($loginUrl) . '">Please Login by using your Facebook Account
                                   </a>';
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