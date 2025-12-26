<?php
    require("../info/phpgetloginview.php");
    require("../info/ncryptdcrypt.php");
    require("../info/routine.php");
    require_once('../tcpdf/examples/tcpdf_include.php');
    require_once('../op_model/salary_process.php');
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
    $salary1 = new salary($connection,285,1,7,'Salary','SAL_000','A5','L');
    $salary1->financialyearcode = $_POST['Season'];
    $salary1->calendermonthcode = $_POST['calendermonthcode'];
    $salary1->employeecategorycode = $_POST['employeecategorycode'];
    $salary1->paymentcategorycode = $_POST['paymentcategorycode'];
    $ret = $salary1->salaryprocess();
    if ($ret == 1)
      {
          oci_commit($connection);
          $flag_en = fnEncrypt('Display');
          echo '<span class="w3-container" style="background-color:#0a0;color:#ff8;text-align:left;">Sale Header is Deleted Successfully</span></br>';
          //echo '<a href="../ip_view/saleinvoiceheader.php?goodscategorycode='.$goodscategorycode_en.'&flag='.$flag_en.'">Find Sale Header</a></br>';
          header("Location: ../mis/entitymenu.php");
      }
      else
      {
          oci_rollback($connection1);
          echo '<span style="background-color:#f44;color:#ff8;text-align:left;">'.$saleinvoiceheader1->Get_invalidmessagetext().'</span></br>';
          echo '<a href="../ip_view/saleinvoiceheader.php?goodscategorycode='.$goodscategorycode_en.'">Find saleinvoiceheader</a></br>';
          echo '<button onclick="history.go(-1);">Back </button>';
      }
  
?>