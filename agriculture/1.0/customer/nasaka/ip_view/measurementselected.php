<?php
        require_once("../info/phpsqlajax_dbinfo.php");
        //require_once("../info/phpgetloginform.php");
        require_once("../info/ncryptdcrypt.php");
        require_once("../info/routine.php");
        require_once("../ip_model/plantationheader_db_oracle.php");
        include("../mis/list.php");
        echo '<nav "w3-container">';
        echo '    <ul class="navbar">';
        echo '    <li><a class="navbar" href="../mis/entitymenu.php">मेनू</a><br/>';
        $flag_en = fnEncrypt('Query');
        echo '    <li><a class="navbar" href="../ip_view/plantationheader.php">नविन ऊस नोंद</a>';
        echo '    <li><a class="navbar" href="../ip_view/plantationheader.php?flag='.$flag_en.'">शोधा ऊस नोंद</a>';
        echo '<li><a  class="navbar" href="../../../../../sqlproc/logout.php">बाहेर पडणे (Logout)</a><br/>';
        echo '    </ul>';
        echo '  </nav>';
        echo '<section>';
        //echo '<form method="post" action="../ip_controller/plantationheader_action.php">';
        echo '<table border="0" width="500px">';
        echo '<tr>';
        //echo '<td><label for="" class="thick">List of Plot for Selection</label></td>';
        //echo '<td><input class="button123" type="submit" name="btn" value="Selected" style="width:100px"></td>';
        echo '</tr>';
        echo '<tr>';
              //echo '<td><a href="../ip_view/plantationheader.php?seasoncode='.fnEncrypt($row1['SEASONCODE']).'&plotnumber='.fnEncrypt($row1['PLOTNUMBER']).'&flag='.$flag_en.'">'.$row1['SEASONCODE'].' '.$row1['PLOTNUMBER'].'</td>';
              echo '<th style="width: 20%;">क्षेत्र क्रं</th>';
              echo '<th style="width: 20%;">गाव</th>';
              echo '<th style="width: 50%;">शेतकरी</th>';
              echo '<th style="width: 10%;">गट सर्व्हे</th>';
              echo '</tr>';
        $connection=swapp_connection();
        $plantationheader1 = new plantationheader($connection);
        $result1 = $plantationheader1->selectedlist();
        if ($plantationheader1->Get_invalidid()==0)
        {
          $flag_en = fnEncrypt('Select');
          while ($row1 = oci_fetch_array($result1,OCI_ASSOC+OCI_RETURN_NULLS))
          {
            if ($_SESSION['lng']=='English')
            {
              echo '<tr>';
              //echo '<td><a href="../ip_view/plantationheader.php?seasoncode='.fnEncrypt($row1['SEASONCODE']).'&plotnumber='.fnEncrypt($row1['PLOTNUMBER']).'&flag='.$flag_en.'">'.$row1['SEASONCODE'].' '.$row1['PLOTNUMBER'].'</td>';
              echo '<td>'.$row1["PLOTNUMBER"].'</td>';
              echo '<td>'.$row1["VILLAGENAMEENG"].'</td>';
              echo '<td>'.$row1["FARMERNAMEENG"].'></td>';
              echo '<td>'. $row1["GUTNUMBER"].'></td>';
              echo '</tr>';
            }
            else
            {
              echo '<tr>';
              //echo '<td><a href="../ip_view/plantationheader.php?seasoncode='.fnEncrypt($row1['SEASONCODE']).'&plotnumber='.fnEncrypt($row1['PLOTNUMBER']).'&flag='.$flag_en.'">'.$row1['SEASONCODE'].' '.$row1['PLOTNUMBER'].'</td>';
              //echo '<td><input type="checkbox" name="plots[]" value="'.$row1["PLOTNUMBER"].'">'.$row1["PLOTNUMBER"].'</td>';
              echo '<td>'.$row1["PLOTNUMBER"].'</td>';
              echo '<td align="left">'.$row1["VILLAGENAMEUNI"].'</td>';
              echo '<td>'.$row1["FARMERNAMEUNI"].'</td>';
              echo '<td>'.$row1["GUTNUMBER"].'</td>';
              echo '</tr>';
            }
          }
        }
        else
        {
          echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$plantationheader1->Get_invalidmessagetext().'</span></br>';
          echo '<button onclick="history.go(-1);">Back </button>';
        }
?>