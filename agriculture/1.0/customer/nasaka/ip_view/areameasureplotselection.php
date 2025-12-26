<?php
require_once("../info/phpsqlajax_dbinfo.php");
require_once("../info/routine.php");
require_once("../info/phpgetloginform.php");
require_once("../info/ncryptdcrypt.php");
$connection=swapp_connection();
?>
<html>
<head>
<title>Users List</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
<!-- <script language="javascript" src="users.js" type="text/javascript"></script> -->
</head>
<body>
<form name="frmUser" method="post" action="../ip_controller/areameasureplotselection_action.php">
<div style="width:500px;">
<table border="0" cellpadding="10" cellspacing="1" width="500" class="tblListForm">
<tr class="listheader">
<td></td>
<td>Plot Number</td>
<td>Village</td>
<td>Farmer Name</td>
<td>Gat / Surve</td>
</tr>
<?php
$i=0;
$query = "SELECT p.plotnumber,v.villagenameuni,f.farmernameuni,p.gutnumber FROM plantationheader p, farmer f,village v,measurementplot m where p.seasoncode=m.seasoncode and p.plotnumber=m.plotnumber and p.farmercode=f.farmercode and p.villagecode=v.villagecode and nvl(p.areabygps,0)=0 and p.seasoncode=".$_SESSION['yearperiodcode']." and m.measurementuserid=".$_SESSION["usersid"];
$result = oci_parse($connection, $query);
$r = oci_execute($result);
while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
{
    if($i%2==0)
    $classname="evenRow";
    else
    $classname="oddRow";
    ?>
    <tr class="<?php if(isset($classname)) echo $classname;?>">
    <td><input type="checkbox" name="plots[]" value="<?php echo $row["PLOTNUMBER"]; ?>" ></td>
    <td><?php echo $row["PLOTNUMBER"]; ?></td>
    <td><?php echo $row["VILLAGENAMEUNI"]; ?></td>
    <td><?php echo $row["FARMERNAMEUNI"]; ?></td>
    <td><?php echo $row["GUTNUMBER"]; ?></td>
    </tr>
<?php
$i++;
}
?>
<tr class="listheader">
<td colspan="4"><input type="submit" name="update" value="Update"/> </td>
</tr>
</table>
</form>
</div>
</body></html>