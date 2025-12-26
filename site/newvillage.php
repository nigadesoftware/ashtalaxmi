<?php
  session_start();
  require("../info/ncryptdcrypt.php");
  require("../info/ncryptdcrypt_old.php");
  require("../info/phpsqlajax_dbinfo.php");
  // Opens a connection to a MySQL server
  $connection=mysqli_connect($hostname, $username, $password, $database);
  mysqli_query($connection,'SET NAMES UTF8');
  // Check connection
  if (mysqli_connect_errno())
    {
      echo "Communication Error1";
    }
    
  try
    {
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

    if (isset($_GET['subdistrictid'])==true)
    {
      $subdistrictid_de =(int) fnDecrypt($_GET['subdistrictid']); 
    }
    else
    {
      $subdistrictid_de = 4196;
    }

    if (isset($_GET['areaid'])==true)
    {
      $areaid_de =(int) fnDecrypt($_GET['areaid']); 
    }
    else
    {
      $areaid_de = 556878;
    }
    /*if (!isset($stateid_de) or $stateid_de==0)
    {
      $stateid_de =(int) fnDecrypt_old($_GET['stateid']);
      $districtid_de = 521;
      $subdistrictid_de = 4196; 
      $areaid_de = 556534; 
    }
    elseif (!isset($districtid_de) or $districtid_de==0)
    {
      $districtid_de =(int) fnDecrypt_old($_GET['districtid']); 
      $stateid_de = 27;
      $subdistrictid_de = 4196; 
      $areaid_de = 556534;
    }
    elseif (!isset($subdistrictid_de) or $subdistrictid_de==0)
    {
      $subdistrictid_de =(int) fnDecrypt_old($_GET['subdistrictid']); 
      $stateid_de = 27;
      $districtid_de = 521;
      $areaid_de = 556534;
    }
    elseif (!isset($areaid_de) or $areaid_de==0)
    {
      $areaid_de =(int) fnDecrypt_old($_GET['areaid']); 
      $stateid_de = 27;
      $districtid_de = 521;
      $subdistrictid_de = 4196; 
    }*/
    }
    catch (Exeception $Ex)
    {
      $stateid_de = 27;
      $districtid_de = 521;
      $subdistrictid_de = 4196; 
      $areaid_de = 556878;
    }
    if ($stateid_de == 0)
    {
      $stateid_de = 27;
      $districtid_de = 521;
      $subdistrictid_de = 4196;
      $areaid_de = 556878;
    }
    else
    {
      $subdistrictid_de = subdistrictid($connection,$areaid_de);
      $districtid_de = districtid($connection,$subdistrictid_de);
      $stateid_de = stateid($connection,$districtid_de);
      $areaid = 556878;
    }
    /*if ($districtid_de == 0)
    {
      $districtid_de = 521;
    }
    if ($subdistrictid_de == 0)
    {
      $subdistrictid_de = 4196;
    }*/

    
    
    $stateid_en = fnEncrypt($stateid_de);
    $districtid_en = fnEncrypt($districtid_de);
    $subdistrictid_en = fnEncrypt($subdistrictid_de);
    $areaid_en = fnEncrypt($areaid_de);

    $areaaddress = areaaddress($connection,$areaid_de,0);

    if (areaaddresscount($connection,$areaaddress,$districtid_de,0)==1)
    {
      $areaaddress1 = $areaaddress.', '.districtname($connection,$districtid_de,0).', '.statename($connection,$stateid_de,0).', India';
      $single=true;
    }
    else
    {
      $areaaddress1 = $areaaddress.', '.districtname($connection,$districtid_de,0).', '.statename($connection,$stateid_de,0).', India';
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

    function areaaddress (&$connection,$areaid,$lng)
    {
      $query = "SELECT s.areaid,s.areaname,s.areaname_eng,s.areaname_cor FROM area s where s.areaid=".$areaid;               
      $result = mysqli_query($connection,$query);
      if ($row = @mysqli_fetch_assoc($result))
      {
        if ($lng == 0)
        {
          return $row['areaname_eng'];
        }
        elseif ($lng == 1)
        {
          if (strlen($row['areaname_cor'])>0)
          {
            return '<del>'.$row['areaname'].'</del> <b>'.$row['areaname_cor'].'</b>';  
          }
          elseif (strlen($row['areaname'])>0)
          {
            return $row['areaname'];   
          }
          else
          {
            return '';
          }
        }
      }
      else
      {
        return '';
      }
    }

    function entrycredits (&$connection,$areaid)
    {
      $query = "SELECT crfbuser FROM area s where cruserid<>0 and s.areaid=".$areaid;               
      $result = mysqli_query($connection,$query);
      if ($row = @mysqli_fetch_assoc($result))
      {
        return $row['crfbuser'];
      }
      else
      {
        return '';
      }
    }

    function isalreadyedited (&$connection,$areaid)
    {
      $query = "SELECT 1 as edited FROM area s where length(areaname_cor)>0 and s.areaid=".$areaid;               
      $result = mysqli_query($connection,$query);
      if ($row = @mysqli_fetch_assoc($result))
      {
        return $row['edited'];
      }
      else
      {
        return 0;
      }
    }
    function areaaddresscount (&$connection,$areaname,$districtid,$lng)
    {
      $query = "SELECT count(*) as cnt FROM area a,subdistrict s where a.subdistrictid=s.subdistrictid and s.districtid=".$districtid." and a.areaname_eng = '".$areaname."'";    
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
               try
               {
                  $response = curl_exec($curlInit);
               }
               catch (Exception $e)
               {
                 echo $e->getmessage();
               }
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
      <?php
      //echo '<title>'.$areaaddress.' Taluka '.subdistrictname($connection,$subdistrictid_de,0).' District '.districtname($connection,$districtid_de,0).' ( '.areaaddress($connection,$areaid_de,1).' तालुका '.subdistrictname($connection,$subdistrictid_de,1).' जिल्हा '.districtname($connection,$districtid_de,1).' ) </title>';
      echo '<title>'.$areaaddress.' Village, Taluka '.subdistrictname($connection,$subdistrictid_de,0).' ( '.areaaddress($connection,$areaid_de,1).' गाव, तालुका '.subdistrictname($connection,$subdistrictid_de,1).' )'.' </title>';
      /*echo '<title>'.$areaaddress.' Village, Taluka '.subdistrictname($connection,$subdistrictid_de,0).' ( '.areaaddress($connection,$areaid_de,1).' गाव, तालुका '.subdistrictname($connection,$subdistrictid_de,1).' ) </title>';*/
      ?>
      <meta property="og:image" content="http://www.swapp.co.in/img/indianvillages.png">
      <meta property="og:image:type" content="image/png">
      <meta property="og:image:width" content="200">
      <meta property="og:image:height" content="200">
      <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->
      <script src="../js/2.1.3/jquery.min.js"></script>
      <script async type="text/javascript" src="../js/menu.js" ></script>
      <link href="../css/cssmenu.css" rel="stylesheet" type="text/css" /> 
      <!-- Bootstrap Core CSS -->
      <link href="../css/bootstrap.min.css" rel="stylesheet">
      <!-- Custom CSS -->
      <link href="../css/business-casual.css" rel="stylesheet">
      <!-- Fonts -->
      <!-- <link href="../font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
      <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
      <link href="http://fonts.googleapis.com/css?family=Josefin+Slab:100,300,400,600,700,100italic,300italic,400italic,600italic,700italic" rel="stylesheet" type="text/css">
      <style type="text/css">
      @font-face {
          font-family: siddhanta;
          src: url("../fonts/siddhanta.ttf");
          font-weight: normal;
      }
    </style> -->
    <style>
del { 
    text-decoration: line-through;
    background-color: #fbb6c2;
    color: #555;
}
</style>
<script async type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58db35533dec30001259e677&product=sticky-share-buttons"></script>

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

</head>
<body onload="initialize()">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-format="autorelaxed"
     data-ad-client="ca-pub-1879145555378586"
     data-ad-slot="5534798952"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script> 
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
                      /*if (isset($_SESSION['fb_access_token'])==true)
                      {
                        echo '<li><a href="/site/userarea.php">User Area</a><li>';
                      }*/
                      

                      echo '<li><a href="/site/indianstatedistrictsubdistrictarealist.php?stateid='.$stateid_en.'&districtid='.$districtid_en.'&subdistrictid='.$subdistrictid_en.'">Village List</a>';
                      
                    ?>
                    <li>
                    <?php
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
                echo '<img border="0" alt="Indian Villages" src="../img/indianvillages.png" width="100px" height="100px">';
                echo '<p>';
                echo '<label for="indianstatelist">'.$areaaddress.' Village Map</label></br>';
                /*echo '<label for="indianstatelist">'.areaaddress($connection,$areaid_de,1).' गाव नकाशा</label></br>';*/
                echo '<label for="indianstatelist">Village : '.$areaaddress.', Taluka : '.subdistrictname($connection,$subdistrictid_de,0).', District : '.districtname($connection,$districtid_de,0).', State : '.statename($connection,$stateid_de,0).'</label></br>';
                if (areaaddress($connection,$areaid_de,1)!='')
                {
                  echo '<label for="indianstatelist">गाव : '.areaaddress($connection,$areaid_de,1).', तालुका : '.subdistrictname($connection,$subdistrictid_de,1).', जिल्हा : '.districtname($connection,$districtid_de,1).', राज्य : '.statename($connection,$stateid_de,1).'</label></br>';
                }
                echo '</p';
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
                      $lgurl = "http://www.".$areaaddress.".mahapanchayat.gov.in";
                      //if (isDomainAvailible($lgurl)==True)
                      //{
                        if (strlen(areaaddress($connection,$areaid_de,1))>0)
                        {
                          echo '</br>गाव '.areaaddress($connection,$areaid_de,1).'चे संकेतस्थळ '; 
                        }
                        else
                        {
                          echo '</br>Village '.$areaaddress.' Website '; 
                        }
                        echo "<a href='".$lgurl."' target='_blank'></br>Village ".$areaaddress." Panchayat</a></br>";  
                        echo "<a href='".$lgurl."' target='_blank'>".strtolower($lgurl)."</a>"; 
                        if ($stateid_de==27)
                        {
                            echo '<a href="https://mahabhulekh.maharashtra.gov.in"></br>7/12 Extract '.'( सात बारा ७/१२)'.' </a>';
                        }
                        echo '</br><li><a style="color:#008" href="/site/userarea.php">महाराष्ट्रातील जिल्हे, तालुके आणि गावे (Administrative Districts, Talukas and Villages of Maharashtra)</a></br>';
                        echo '<li><a style="color:#008" href="/site/maharashtradistrictlist.php">महाराष्ट्रातील जिल्हावार साखर कारखाने (Districtwise Sugar Factories)</a></br>';
                        echo '<li><a style="color:#008" href="https://sugarcircle.blogspot.com">साखर उद्योग ब्लॉग (Sugar Industry Blog)</a></br>';
                        echo '<li><a style="color:#008" href="/index.php">स्वॅप सॉफ्टवेअर अॅप्पलीकेशन (Swapp Software Application)</a></br>';
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