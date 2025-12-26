<?Php
   $db_connection = pg_connect("host=localhost dbname=trade user=postgres password=swapp123");
   //echo "pggress data";
   $result=pg_query($db_connection, "SELECT * FROM item");
   while ($row = pg_fetch_array($result)) 
   {
      //do stuff with $row
      echo $row['itemname'].'</br>';
   }

?>