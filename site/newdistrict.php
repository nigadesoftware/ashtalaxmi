<?php
  require("../info/ncryptdcrypt.php");
  require("../info/phpsqlajax_dbinfo.php");
  // Opens a connection to a MySQL server
  $connection=mysqli_connect($hostname, $username, $password, $database);
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

    if (districtaddresscount($connection,districtaddress($connection,$districtid_de,0),$stateid_de,0)==1)
    {
      $areaaddress1 = districtname($connection,$districtid_de,0).', '.statename($connection,$stateid_de,0).', India';
      $single=true;
    }
    else
    {
      $areaaddress1 = districtname($connection,$districtid_de,0).', '.statename($connection,$stateid_de,0).', India';
      $single=false;
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
    
    function districtaddress (&$connection,$districtid,$lng)
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

    function districtaddresscount (&$connection,$districtname,$stateid,$lng)
    {
      $query = "SELECT count(*) as cnt FROM district d, state s where s.stateid=d.stateid and s.stateid=".$stateid." and s.districtname_eng = '".$districtname."'";    
      $result = mysqli_query($connection,$query);
      if ($row = @mysqli_fetch_assoc($result))
      {
        if ($lng == 0)
        {
          return $row['cnt'];
        }
        elseif ($lng == 1)
        {
          return $row['cnt'];
        }
      }
      else
      {
        return '';
      }
    }

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
?>
<html>
<head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="Swapp Software Application">
      <meta name="author" content="Swapp Software Application">
      <title>District</title>
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

<script async type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyD_JEA6eU_nHfVwvmmJCMSDfB_mwj9LPOQ&sensor=false"></script>
<script async type="text/javascript">
  var geocoder;
  var map;
  <?php
  echo 'var address = "'.$areaaddress1.'";';
  ?>
  function initialize() {
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(18, 74);
    var myOptions = {
      zoom: 14,
      center: latlng,
    mapTypeControl: true,
    mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
    navigationControl: true,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    if (geocoder) {
      geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
          map.setCenter(results[0].geometry.location);

            var infowindow = new google.maps.InfoWindow(
                { content: '<b>'+address+'</b>',
                  size: new google.maps.Size(150,50)
                });

            var marker = new google.maps.Marker({
                position: results[0].geometry.location,
                map: map, 
                title:address
            }); 
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(map,marker);
            });

          } else {
            alert("No results found");
          }
        } else {
          alert("Geocode was not successful for the following reason: " + status);
        }
      });
    }
  }
</script>
<script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58db35533dec30001259e677&product=sticky-share-buttons"></script>
</head>
<body onload="initialize()">
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
                    <?php
                      echo '<li><a href="/site/indianstatedistrictlist.php?stateid='.$stateid_en.'">District List</a></li>';
                    ?>
                    <li><a href='http://cloudsugarerp.blogspot.in'>Sugar Blogs</a></li>
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
                echo '<p>';
                echo '<label for="indianstatelist">Country - India</br>State - '.statename($connection,$stateid_de,0).'</br>District - '.districtname($connection,$districtid_de,0).'</br></label></br>';
                echo '<label for="indianstatelist">'.districtaddress($connection,$districtid_de,0).' City Map</label></br>';
                echo '</p>';
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
                  <div id="map_canvas" style="width:100%; height:100%">
                  </div>
                  <label style="font-size:9px" for="indianstatelist">* Map is based on address, result may show different place beacuase of spelling or similar name or other reason beyond control. Please verify upon other parameters. We are not resonsible for any kind of loss.</label>              
                  <?php
                    if ($single==true)
                    {
                      //$lgurl = "http://www.".areaaddress($connection,$subdistrictid_de,0).".mahapanchayat.gov.in";
                      //if (isDomainAvailible($lgurl)==True)
                      //{
                      //  echo "<a href='".$lgurl."' target='_blank'>".areaaddress($connection,$areaid_de,0)." Panchayat</a></br>";  
                      //  echo "<a href='".$lgurl."' target='_blank'>".strtolower($lgurl)."</a>";  
                      //}
                    } 
                  ?>
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
</body>
</html>