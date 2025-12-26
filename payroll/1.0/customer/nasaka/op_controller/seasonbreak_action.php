<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/seasonbreak_process.php');
    require("../info/phpsqlajax_dbinfo.php");
    /*if (isaccessible(451278369852145)==0)
    {
        echo 'Communication Error';
        exit;
    }*/
    /* $_POST['farmercode']=202;
    $_POST['billperiodtransnumber']=1;
    $_POST['circlecode']=0;
    $_POST['villagecode']=0; */
    $connection = swapp_connection();
    $seasonbreak1 = new seasonbreak($connection);
    $seasonbreak1->employeecategorycode = $_POST['employeecategorycode'];
    $seasonbreak1->date = $_POST['Date'];
    $ret = $seasonbreak1->seasonbreakprocess();
    if ($ret == 1)
      {
          oci_commit($connection);
          $flag_en = fnEncrypt('Display');
          echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Season break processed Successfully</span></br>';
          //echo '<a href="../ip_view/saleinvoiceheader.php?goodscategorycode='.$goodscategorycode_en.'&flag='.$flag_en.'">Find Sale Header</a></br>';
          header("Location: ../mis/entitymenu.php");
      }
      else
      {
          oci_rollback($connection);
          echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$seasonbreak1->Get_invalidmessagetext().'</span></br>';
          //echo '<a href="../ip_view/saleinvoiceheader.php?goodscategorycode='.$goodscategorycode_en.'">Find saleinvoiceheader</a></br>';
          echo '<button onclick="history.go(-1);">Back </button>';
      }
  
?>