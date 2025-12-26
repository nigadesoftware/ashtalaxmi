<?php
	function addtodictionary(&$connection,$name_eng,$name_unicode,$unicodelanguageid,$categoryid)
	{
		// Opens a connection to a MySQL server
		$result1 = mysqli_query($connection, "select count(*) as cnt from namedictionary where active=1 and name_eng='".$name_eng."' and name_unicode='".$name_unicode."' and unicodelanguageid=".$unicodelanguageid." and categoryid=".$categoryid);
		$row1 = mysqli_fetch_assoc($result1);
		if ($row1["cnt"] == 0)
		{
			$result2 = mysqli_query($connection, "select nvl(max(namedictionaryid),354125485)+179 as namedictionaryid from namedictionary");
			$row2 = mysqli_fetch_assoc($result2);
			$namedictionaryid = $row2["namedictionaryid"];
			$query = "insert into namedictionary(namedictionaryid,name_eng,name_unicode,unicodelanguageid,categoryid,active,cruserid,crdatetime) values ($namedictionaryid,'$name_eng','$name_unicode',$unicodelanguageid,$categoryid,1,'auto','".currentdatetime()."')";
			//echo $query;
			if (mysqli_query($connection, $query))
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return 0;
		}
	}
?>