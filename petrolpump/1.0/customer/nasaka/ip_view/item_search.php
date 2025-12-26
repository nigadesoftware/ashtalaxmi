<?php
    //This is geneperd by Swapp Software Application on 23/10/2018 06:50:03 PM for PHP
    require("../info/phpsqlajax_dbinfo.php");
    require("../info/phpgetloginview.php");
    require("../info/routine.php");
    $connection =petrolpump_connection();
    $searchTerm = $_GET['term'];
    $invoicedate = $_GET['invoicedate'];
    $customertypecode = $_GET['customertypecode'];
    if (preg_match('/^[0-9]*$/', $searchTerm))
    {
    }
    elseif (strlen($searchTerm)<3)
    {
        exit;
    }
   if ($searchTerm == '**')
   {
       $query = "select itemcode,
       itemnameuni,
       itemnameeng
       from item where rownum <= 10";
   }
   else
   {
       $query = "select itemcode,
       itemnameuni,
       itemnameeng
       from item where upper(itemnameeng) like upper('%".$searchTerm."%')
       or itemnameuni like '%".$searchTerm."%'
       or to_char(itemcode) like '".$searchTerm."' and rownum <= 10";
   }
   $result = oci_parse($connection, $query);
   $r = oci_execute($result);
   while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
   {
       $id = $row['ITEMCODE'];
       if ($_SESSION['lng'] == "English")
       {
           $name = $row['ITEMNAMEENG'];
       }
       else
       {
           $name = $row['ITEMNAMEUNI'];
       }
       $rate = itemrate($connection,$row['ITEMCODE'],$invoicedate,$customertypecode);
       $vatper=0;
       itemtax($connection,$row['ITEMCODE'],$invoicedate,$vatper);
       $data[] = array( 
           'id' => $id
           ,'label' => $name
           ,'value' => $name
           ,'rate' => $rate
           ,'vatper' => $vatper
           );
   }
   //return json data
   echo json_encode($data);
?>

