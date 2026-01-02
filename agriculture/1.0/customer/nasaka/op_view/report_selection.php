<?php
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetlogin.php");
    include("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require("../ip_model/report_db_oracle.php");
    require("../ip_model/reportparameterdetail_db_oracle.php");
    //
    /*if (isaccessible(123479028)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    $reportcode_de = fnDecrypt($_GET['reportcode']); 
    $connection = swapp_connection();
    $report1 = new report($connection);
    $report1->reportcode = $reportcode_de;
    $report1->fetch();
    if ($report1->found==false)
    {
        header('location: ../mis/entitymenu.php');
        exit();
    }
    if ($_SESSION['lng']=="English")
    {     
        $title = $report1->reportname_eng;
    }
    else
    {
        $title = $report1->reportname_uni;
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"></meta>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/w3.css">
        <link rel="stylesheet" href="../css/swapp123.css">
        <?php
            echo '<title>'.$title.'</title>';
        ?>
        <!-- <style type="text/css">
            body
            {
                background-color: #fff;
            }
            header
            {
                background-color: #fff;
                min-height: 38px;
                color: #070;
                font-family: Arial;
                font-size: 19px;
            }
            nav
            {
                width: 300px;
                float: left;
                list-style-type: none;
                font-family: verdana;
                font-size: 15px;
                color: #f48;
                line-height: 30px;
            }
            a
            {
                color: #f48;
            }
            article
            {
                background-color: #fff;
                display: table;
                margin-left: 0px;
                padding-left: 10px;
                font-family: Verdana;
                font-size: 15px;
            }
            section
            {
                margin-left: 0px;
                margin-right: 15px;
                float: left;
                text-align: justify;
                color: #000;
                line-height: 23px;
            }
            footer
            {
                float: bottom;
                color: #000;
                font-family: verdana;
                font-size: 12px;
            }
            div
            {
                float:left;
            }
            input, textarea
            {
                outline: none;
            }
            button
            {
                width:200px;
                height:35px;
                color:#000;
                border-radius: 5px;
            }
            input:focus, textarea:focus
            {
                border-radius: 5px;
                outline: none;
            }
            label
            {
                color: #000;
            }
        </style> -->
        <script src="../css/ui/1.11.4/themes/smoothness/jquery-ui.css">
         </script>
         <style>
            .hidden{
                display: none;
            }
            </style>
         <script>
             $(window).bind("pageshow", function(event) {
                $("#busy").hide();
            });
         </script>
         <script>
            function startTimer()
            {
                var counter = 0;
                setInterval(function() {
                    counter++;
                    if (counter >= 0) {
                    span = document.getElementById("count");
                    span.innerHTML = counter;
                    }
                }, 1000);
                }
                function start()
                {
                    document.getElementById("count").style="color:green;";
                    startTimer();
                };   
            </script>
         <script>
            $(document).ready(function(){
             setInterval(function(){cache_clear()},360000);
             });
             function cache_clear()
            {
             window.location.reload(true);
            }
        </script>
 </script>
        <link rel="stylesheet" href="../css/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="..//js/jquery-1.10.2.js"></script>
        <script src="..//js/ui/1.11.4/jquery-ui.js"></script>
    </head>
    <body>
        <nav "w3-container">
            <ul class="navbar">
                <li><a class="navbar" href="../mis/entitymenu.php">Entity Menu</a>
                <?php
                    echo '<li><a  class="navbar" href="../sqlproc/logout.php">Log Out</a><br/>';
                ?>
            </ul>
        </nav>
        <article class="w3-container">
            <?php
                echo '<section>';
                //if ($categorycode_de==1)
                //{
                    echo '<form method="post" action="../op_controller/'.$report1->reportfile.'_action.php">';
                //}
                echo '<table border="0" >';
                $reportparameterdetail1 = new reportparameterdetail($connection);
                $result1=$reportparameterdetail1->fetchall($reportcode_de);
                $r = oci_execute($result1);
                while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
                {
                   
                    echo '<tr>';
                    if ($_SESSION['lng']=="English")
                    {
                        //echo '<tr>';
                        echo '<td><label for="'.$row1['REPORTPARAMETERNAME_ENG'].'">'.$row1['REPORTPARAMETERNAME_ENG'].'</label></td>';
                        //echo '</tr>';
                    }
                    else
                    {
                        //echo '<tr>';
                        echo '<td><label for="'.$row1['REPORTPARAMETERNAME_ENG'].'">'.$row1['REPORTPARAMETERNAME_UNI'].'</label></td>';
                        //echo '</tr>';
                    }
                    if($row1['INPUTCONTROLTYPECODE']==1)
                    {
                        echo '<tr>';
                        echo '<td><label for="'.$row1['REPORTPARAMETERNAME_ENG'].'"></label></td>';
                        echo '</tr>';
                        echo '<tr>';
                        if ($row1['REPORTPARAMETERCODE']==1)
                        {
                            echo '<td><input type="text" autocomplete="off" style="font-size:12pt;height:30px" name="'.$row1['REPORTPARAMETERNAME_ENG'].'" id="'.$row1['REPORTPARAMETERNAME_ENG'].'" value="'.$_SESSION['yearperiodcode'].'"></td>';
                        }
                        elseif ($row1['REPORTCODE']==48 and $row1['REPORTPARAMETERCODE']==20)
                        {
                            echo '<td><input type="text" autocomplete="off" style="font-size:12pt;height:30px" name="'.$row1['REPORTPARAMETERNAME_ENG'].'" id="'.$row1['REPORTPARAMETERNAME_ENG'].'" value="'.currentdate().'"></td>';
                        }
                        else 
                        {
                            echo '<td><input type="text" autocomplete="off" style="font-size:12pt;height:30px" name="'.$row1['REPORTPARAMETERNAME_ENG'].'" id="'.$row1['REPORTPARAMETERNAME_ENG'].'" ></td>';
                        }
                        if ($row1['OPTIONAL']==1)
                        {
                           echo '<td><label for="'.$row1['REPORTPARAMETERNAME_ENG'].'">*</label></td>';  
                        }
                        echo '</tr>';
                    }
                    elseif($row1['INPUTCONTROLTYPECODE']==2)
                    {
                        if ($reportcode_de==23)
                        {
                            filllistcombo($connection,$row1['COMBOTABLEOWNER'].'.'.$row1['COMBOTABLE'],$row1['COMBODATANAMEENG'],$row1['COMBODATANAMEUNI'],$row1['COMBODATACODE'],$row1['OPTIONAL'],$found=false,$value='',$condition='payeecategorycode=1');
                        }
                        elseif ($reportcode_de==39)
                        {
                            filllistcombo($connection,$row1['COMBOTABLEOWNER'].'.'.$row1['COMBOTABLE'],$row1['COMBODATANAMEENG'],$row1['COMBODATANAMEUNI'],$row1['COMBODATACODE'],$row1['OPTIONAL'],$found=false,$value='',$condition='payeecategorycode=2');
                        }
                        else
                        {
                            filllistcombo($connection,$row1['COMBOTABLEOWNER'].'.'.$row1['COMBOTABLE'],$row1['COMBODATANAMEENG'],$row1['COMBODATANAMEUNI'],$row1['COMBODATACODE'],$row1['OPTIONAL'],$found=false,$value='');
                        }
                    }
                    elseif($row1['INPUTCONTROLTYPECODE']==3)
                    {
                        if($row1['DEPENDENTREPORTPARAMETERCODE']==0)
                        {
                            echo '<script>';
                            echo '    $(function () {';
                            echo '        $(" #'.$row1['REPORTPARAMETERNAME_ENG'].' ").autocomplete({';
                            if ($reportcode_de==16)
                                echo "        source: '../ip_view/vwfarmerbillperiodprocess_search.php',";
                            elseif ($reportcode_de==37)
                                echo "        source: '../ip_view/vwhtbillperiodprocess_search.php',";
                            else
                            echo "        source: '../ip_view/".strtolower($row1['COMBOTABLE'])."_search.php',";
                            echo '        minLength:2,';
                            echo '        delay:200,';
                            echo '        select:function(event,ui)';
                            echo '        {var v = ui.item.value;';
                            echo '        var i = ui.item.id;';
                            echo '        this.value = v;';
                            echo "        $('#".$row1['COMBODATACODE']."').val(i);";
                            echo '        return false;}';
                            echo '        });';
                            echo '        });';
                            echo '</script>';
                            echo '<tr>';
                            echo '<td><input type="text" autocomplete="off" style="font-size:12pt;height:30px" name="'.$row1['REPORTPARAMETERNAME_ENG'].'" id="'.$row1['REPORTPARAMETERNAME_ENG'].'" ></td>';
                            if ($row1['OPTIONAL']==1)
                            {
                                echo '<td><label for="'.$row1['REPORTPARAMETERNAME_ENG'].'">*</label></td>';  
                            }
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td><input readonly="readonly" tabindex="-1" type="text" style="font-size:12pt;height:30px" name="'.$row1['COMBODATACODE'].'" id="'.$row1['COMBODATACODE'].'" ></td>';
                            echo '</tr>';
                            
                        }
                        elseif($row1['DEPENDENTREPORTPARAMETERCODE']>0)
                        {
                            echo '<script>';
                            echo '$(function()';
                            echo '{';
                            echo '$("#'.$row1['REPORTPARAMETERNAME_ENG'].'").autocomplete({';
                            echo 'source: function(request, response) {';
                            echo "$.getJSON('../ip_view/".strtolower($row1['COMBOTABLE'])."_search.php', { term: request.term,".$row1['D_COMBODATACODE'].":$('#".$row1['D_COMBODATACODE']."').val()},";
                            echo 'response);';
                            echo '},';
                            echo 'minLength:1,';
                            echo 'delay:200,';
                            echo 'select:function(event,ui)';
                            echo '{var v = ui.item.value;';
                            echo 'var i = ui.item.id;';
                            echo "$('#".$row1['COMBODATACODE']."').val(i);";
                            echo 'this.value = v;';
                            echo 'return false;}';
                            echo '});';
                            echo '});';
                            echo '</script>';
                            echo '<tr>';
                            echo '<td><input type="text" autocomplete="off" style="font-size:12pt;height:30px" name="'.$row1['REPORTPARAMETERNAME_ENG'].'" id="'.$row1['REPORTPARAMETERNAME_ENG'].'" ></td>';
                            if ($row1['OPTIONAL']==1)
                            {
                                echo '<td><label for="'.$row1['REPORTPARAMETERNAME_ENG'].'">*</label></td>';  
                            }
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td><input readonly="readonly" tabindex="-1" type="text" style="font-size:12pt;height:30px" name="'.$row1['COMBODATACODE'].'" id="'.$row1['COMBODATACODE'].'" ></td>';
                            echo '</tr>';
                        }
                    }
                }
                echo '<tr>';
                echo '<td></td>';
                echo '<td><input type="hidden" style="font-size:12pt;height:30px" name="reportcode" id="reportcode" value ="'.$reportcode_de.'"></td>';
                echo '</tr>';
                if ($reportcode_de==16)
                {
                echo '<tr>';
                    echo '<td><input type="checkbox" id="isunpostdata" name="isunpostdata" value="1">Unpost Processed Data</td>';
                    echo '</tr>';
                }
                    if ($reportcode_de==32 or $reportcode_de==42)
                {
                    echo '<tr>';
                    echo '<td><input type="checkbox" id="exportcsvfile" name="exportcsvfile" value="1">Export All CSV File</td>';
                    echo '<td><input type="checkbox" id="exportcsvfile" name="exportcsvfile" value="2">Export Below2lac CSV File</td>';
                    echo '<td><input type="checkbox" id="exportcsvfile" name="exportcsvfile" value="3">Export Above2lac CSV File</td>';
                    echo '<td><input type="checkbox" id="exportcsvfile" name="exportcsvfile" value="4">Export All CSV File New Type</td>';
                    echo '<td><input type="checkbox" id="exportcsvfile" name="exportcsvfile" value="5">Export All CSV File New Format</td>';
                    echo '</tr>';
                    echo '<tr>';
                }
                if ($reportcode_de==49 or $reportcode_de==152 or $reportcode_de==122 or $reportcode_de==173 or $reportcode_de==3)
                {
                    echo '<tr>';
                    echo '<td><input type="checkbox" id="exportcsvfile" name="exportcsvfile" value="1">Export CSV File</td>';
                    echo '</tr>';
                    echo '<tr>';
                }
                if ($reportcode_de==48 or $reportcode_de==107)
                {
                    echo '<tr>';
                    echo '<td><input type="checkbox" id="exportcsvfile" name="exportcsvfile" value="1">Without Shares</td>';
                    echo '</tr>';
                    echo '<tr>';
                }
                if ($reportcode_de==100)
                {
                    echo '<tr>';
                    echo '<td><input type="checkbox" id="exportcsvfile" name="exportcsvfile" value="1">Export CSV File</td>';
                    echo '</tr>';
                    echo '<tr>';
                }
                if ($reportcode_de==37)
                {
                    echo '<tr>';
                    echo '<td><input type="checkbox" id="unpost" name="unpost" value="1">Un Post Processed Data</td>';
                    echo '</tr>';
                    echo '<tr>';
                }
                //echo '<td><input class="button123" type="submit" name="btn" value="View" style="width:100px"></td>';;
                if ($report1->responsibilitytype==3)
                {
                    echo '<td><input class="button123" type="submit" name="btn" id="btn" value="Report" onclick="start()" style="width:100px"></td>';;
                }
                elseif ($report1->responsibilitytype==4)
                {
                    echo '<td><input class="button123" type="submit" name="btn" id="btn" value="Process" style="width:100px" onclick="start()"></td>';;
                }
                echo '</tr>';
                echo '<tr>';
                echo '<td><span id="count">0</span> seconds</td>';
                echo '</tr>';
                echo '</table>';
                echo '</form>';
            ?>
            
    <!-- <img src="../img/ajax-loader.gif" class="hidden" id="busy"> -->
    <img src="../img/3dgifmaker23.gif" class="hidden" id="busy">                
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->
    <script>
      $.fn.center = function () {
        this.css("position","absolute");
        this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop()) + "px");
        this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window).scrollLeft()) + "px");
        return this;
      }

      $("#btn").on("click", function(){
        $("#busy").fadeIn().center();
        setTimeout(function(){
          $("#busy").fadeOut()
        }, 1000000);
      });
    </script>
    <script>
        $("#btn").on("onfocus", function(){
           $("#busy").hide()
       });
    </script>
        </script>
        </article>
        <footer>
        </footer>
    </body>
</html>