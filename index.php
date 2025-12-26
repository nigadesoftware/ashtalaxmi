<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="msvalidate.01" content="34F40E5F5064AC6207F991E677AC6747" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Information Technology (IT) Consultancy Services">
    <meta name="author" content="Swapp Software Application">
    <meta property="og:image" content="http://www.swapp.co.in/img/swappcoinwebsite.png"/>
    <title>Peak Point</title>
    <link rel="shortcut icon" href="apple-touch-icon.png?v=<?php echo md5_file('apple-touch-icon.png') ?>" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <!-- <link rel="icon" type="image/png" sizes="36x36 48x48 96x96 144x144 192x192 310x310" href="../favicon-192x192.png"> -->
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/business-casual.css" rel="stylesheet">
    <!-- Fonts -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Josefin+Slab:100,300,400,600,700,100italic,300italic,400italic,600italic,700italic" rel="stylesheet" type="text/css"> -->
    <link rel="stylesheet" href="ism/css/my-slider.css"/>
    <script src="ism/js/ism-2.2.min.js"></script>
    <!-- <script async src="https://apis.google.com/js/platform.js"></script> -->
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
    <![endif]
    -->
  <!--<script async type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58db35533dec30001259e677&product=sticky-share-buttons"></script>-->
</head>
<body>
    <div id="fb-root"></div>
<script async>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_IN/sdk.js#xfbml=1&version=v2.8&appId=1299772940069870";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
    <header>
        <div class="row">
          <div style="background-color:#fff">
                    <div style="float:right;padding:20px;"><a href='/mis/login.php' style="font-size:18px"><img src="../img/user.png" width="40px" height="40px">Login</a></div>
                    <div style="float:right;padding:20px;"><a href='/agriculture/1.0/customer/nasaka/op_view/shiftwisetonnage.php' style="font-size:18px"><img src="../img/user.png" width="40px" height="40px">Crushing</a></div>
                    <div style="background-color:#fff;padding-left:20px">
                    <img width="40px" height="40px" src="../img/nigadesoftwaretechnologies_logo_3.png" alt="">
                    <img width="150px" height="33px" src="../img/nigadesoftwaretechnologies_logo.png" alt="">
                    <a href='' style="font-size:13px"> - Web-Based ERP for Sugar Industry</a><br>
                    <a href='https://www.nigadesoftware.com' style="font-size:14px">www.nigadesoftware.com</a>
                    </div>
                    </br>
                     </div>
                    <div id="wrapper">
                        </div>
                    </h4>
                </div>
          </div>
          </div>
          </div>
          </div>
    </header>  
    <div class="container">
        <div class="row">
            <div class="box">
                <div class="col-lg-121">
                  <div align="right" style="background-color:#fff;color:#000">
                      <?php
                      if (isset($_SESSION['fb_access_token'])==true)
                        {
                          
                          /*echo '<label><a style="color:#080" href="../site/userarea.php">
                      <img border="0" alt="Facebook Login " src="http://graph.facebook.com/'.$_SESSION['fb_id'].'/picture?type=square&redirect=true&width=20&height=20">Welcome! '.$_SESSION['fb_name'].'
                      </a></label>';*/
                      echo '<label><a style="color:#800" href="../site/logout.php"></br>&nbspLogout</a></label></br>';
                        }
                        else
                        {
                          /*echo '<label><a style="color:#000"href="../site/login.php">
                      Login using Facebook(फेसबुक आधारित लॉगीन)
                      </a><label>';*/
                        }
                      ?>
                      </div>
                      <div  align="center" style="color:#fff;background-color:#a00;font-family: 'Siddhanta';font-size:25px;">Nashik Sahakari Sakhar Karkhana Ltd.
                      <!--<div class="fb-like" data-href="https://www.facebook.com/swapp.co.in" data-layout="button" data-action="like" data-size="large" data-show-faces="true"></div>-->
                      </div>
                      
                      <div  align="center"  style="color:#a00;background-color:#fff;font-family: 'Siddhanta';font-size:16px;">
                      <a href='https://www.nasakasugar.com' style="font-size:16px">www.nasakasugar.com</a>
                      </div>
                    
                    <div class="ism-slider" data-play_type="loop" data-image_fx="zoompan" id="my-slider">
      <ol>
        <?php
          $rn=rand(1,5);
          $k=1;
          for ($i=$rn;$k<=5;)
          {
            echo '<li>';
            echo '  <img src="ism/image/slides/'.$i.'.jpg">';
            echo '  <div class="ism-caption ism-caption-0">Enterprise Resources Planning(ERP) Software</div>';
            echo '</li>';
            $i++;
            if ($i>5)
            {
              $i=1;
            }
            $k++;
          }
        ?>
      </ol>
    </div>
            </div>
            <div  align="center" style="color:#fff;background-color:#680;font-family: 'Siddhanta';font-size:14px;">Peak Point - The Indian ERP for Sugar Industry
                      <!--<div class="fb-like" data-href="https://www.facebook.com/swapp.co.in" data-layout="button" data-action="like" data-size="large" data-show-faces="true"></div>-->
                      </div>
            <div class="clearfix"></div>
            </div>
        
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script async src="js/jquery.js"></script>
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
</body>
</html>