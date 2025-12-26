<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/ncryptdcrypt.php");
    session_start();
    set_time_limit(0);
    define ("OUTPUT_FILE", "sitemap111.xml");
    error_reporting (E_ERROR | E_WARNING | E_PARSE);
    $pf = fopen (OUTPUT_FILE, "w");
    if (!$pf)
    {
        echo "Cannot create " . OUTPUT_FILE . "!" . NL;
        return;
    }

    fwrite ($pf, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" .
                 "<!-- Created with Plop PHP XML Sitemap Generator " . VERSION . " https://www.plop.at -->\n" .
                 "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n" .
                 "        xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n" .
                 "        xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n" .
                 "        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n" .
                 "  <url>\n" .
                 "    <loc>http://www.swapp.co.in</loc>\n" .
                 "    <changefreq>weekly</changefreq>\n" .
                 "    <priority>0.5</priority>\n" .
                 "  </url>\n");

    // Opens a connection to a MySQL server
    $connection=mysqli_connect($hostname, $username, $password, $database);
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Communication Error";
    }
    mysqli_query($connection,'SET NAMES UTF8');
    $connection ->autocommit(FALSE);

    $query = "SELECT s.stateid,s.statename_eng FROM state s where flag=0 order by s.stateid";						  	
    mysqli_query($connection,'SET NAMES UTF8');
    $result = mysqli_query($connection,$query);
    if (!$result)
    {
        die('Communication Error');
    }
    while ($row = @mysqli_fetch_assoc($result))
    {
        echo 'State: '.$row['statename_eng']."</br>";
        $stateid_en = fnEncrypt($row['stateid']);
        fwrite ($pf,"<url>\n");
        fwrite ($pf, "  <loc>http://www.swapp.co.in/indiandistrictlist.php?stateid=".$stateid_en."</loc>\n");
        fwrite ($pf,"  <changefreq>weekly</changefreq>\n");
        fwrite ($pf,"  <priority>0.5</priority>\n");
        fwrite ($pf,"</url>\n");
        $query1 = "SELECT d.districtid,d.districtname_eng FROM district d where stateid=".$row['stateid']." order by d.districtid";						  	
        mysqli_query($connection,'SET NAMES UTF8');
        $result1 = mysqli_query($connection,$query1);
        while ($row1 = @mysqli_fetch_assoc($result1))
        {
            echo '  District: '.$row1['districtname_eng']."</br>";
            $districtid_en = fnEncrypt($row1['districtid']);
            fwrite ($pf,"<url>\n");
            fwrite ($pf, "  <loc>http://www.swapp.co.in/site/indiandistrictsubdistrictlist.php?stateid=".$stateid_en."&amp;districtid=".$districtid_en."</loc>\n");
            fwrite ($pf,"  <changefreq>weekly</changefreq>\n");
            fwrite ($pf,"  <priority>0.5</priority>\n");
            fwrite ($pf,"</url>\n");

            $query2 = "SELECT d.subdistrictid,d.subdistrictname_eng FROM subdistrict d where districtid=".$row1['districtid']." order by d.subdistrictid";						  	
            mysqli_query($connection,'SET NAMES UTF8');
            $result2 = mysqli_query($connection,$query2);
            while ($row2 = @mysqli_fetch_assoc($result2))
            {
                echo '    SubDistrict: '.$row2['subdistrictname_eng']."</br>";
                $subdistrictid_en = fnEncrypt($row2['subdistrictid']);
                fwrite ($pf,"<url>\n");
                fwrite ($pf, "  <loc>http://www.swapp.co.in/site/indiandistrictsubdistrictarealist.php?stateid=".$stateid_en."&amp;districtid=".$districtid_en."&amp;subdistrictid=".$subdistrictid_en."</loc>\n");
                fwrite ($pf,"  <changefreq>weekly</changefreq>\n");
                fwrite ($pf,"  <priority>0.5</priority>\n");
                fwrite ($pf,"</url>\n");

                $query3 = "SELECT d.areaid,d.areaname_eng FROM area d where subdistrictid=".$row2['subdistrictid']." order by d.areaid";						  	
                mysqli_query($connection,'SET NAMES UTF8');
                $result3 = mysqli_query($connection,$query3);
                while ($row3 = @mysqli_fetch_assoc($result3))
                {
                    echo '      Area: '.$row3['areaname_eng']."</br>";
                    $areaid_en = fnEncrypt($row3['areaid']);
                    fwrite ($pf,"<url>\n");
                    fwrite ($pf, "  <loc>http://www.swapp.co.in/site/newvillage.php?stateid=".$stateid_en."&amp;districtid=".$districtid_en."&amp;subdistrictid=".$subdistrictid_en."&amp;areaid=".$areaid_en."</loc>\n");
                    fwrite ($pf,"  <changefreq>weekly</changefreq>\n");
                    fwrite ($pf,"  <priority>0.5</priority>\n");
                    fwrite ($pf,"</url>\n");
                }
            }
        }
    }

    fwrite ($pf, "</urlset>");
    fclose ($pf);

    echo "Done." . NL;
    echo OUTPUT_FILE . " created." . NL;
?>
