<?php
                require("../info/rawmaterialroutine.php");
                $connection=rawmaterial_connection();
                $query = "select f.* from contractphotodetail f where f.active=1 and f.contractid =125487035";
                //echo $query;
                $result = oci_parse($connection, $query); $r = oci_execute($result);
                if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS+OCI_RETURN_NULLS))
                {
                    header("Content-type: image/JPEG");
                    $result = $row['PHOTO']->load();
                    print $result;
                }
            ?>
            