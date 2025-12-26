<?php
    function contractharvestdetailcount(&$connection,$contractid)
    {
        $query = "select count(*) as cnt from contract c, contractharvestdetail d 
        where c.active=1 and d.active=1 and c.contractid=d.contractid and 
        c.contractid=".$contractid;
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            return $row['CNT']; 
        }
    }
    
    function contracttransportdetailcount(&$connection,$contractid)
    {
        $query = "select count(*) as cnt from contract c, contracttransportdetail d 
        where c.active=1 and d.active=1 and c.contractid=d.contractid and 
        c.contractid=".$contractid;
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            return $row['CNT']; 
        }
    }

    function contracttransporttrailerdetailcount(&$connection,$contractid,$contracttransportdetailid)
    {
        $query = "select count(*) as cnt from contract c, contracttransportdetail d, 
        contracttransporttrailerdetail t
        where c.active=1 and d.active=1 and t.active=1 and c.contractid=d.contractid
         and d.contracttransportdetailid=t.contracttransportdetailid and 
         c.contractid=".$contractid." and d.contracttranpsortdetailid=".$contracttranpsortdetailid;
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            return $row['CNT']; 
        }
    }

    function contractperformancedetailcount(&$connection,$contractid)
    {
        $query = "select count(*) as cnt from contract c, contractperformancedetail d 
        where c.active=1 and d.active=1 and c.contractid=d.contractid and 
        c.contractid=".$contractid;
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            return $row['CNT']; 
        }
    }

    function contractguarantordetailcount(&$connection,$contractid)
    {
        $query = "select count(*) as cnt from contract c, contractguarantordetail d 
        where c.active=1 and d.active=1 and c.contractid=d.contractid and 
        c.contractid=".$contractid;
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
           return $row['CNT']; 
        }
    }

    function contractnomineedetailcount(&$connection,$contractid)
    {
        $query = "select count(*) as cnt from contract c, contractnomineedetail d 
        where c.active=1 and d.active=1 and c.contractid=d.contractid and 
        c.contractid=".$contractid;
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            return $row['CNT']; 
        }
    }

    function contractwitnessdetailcount(&$connection,$contractid)
    {
        $query = "select count(*) as cnt from contract c, contractwitnessdetail d 
        where c.active=1 and d.active=1 and c.contractid=d.contractid and 
        c.contractid=".$contractid;
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            return $row['CNT']; 
        }
    }

    function contractdocumentdetailcount(&$connection,$contractid)
    {
        $query = "select count(*) as cnt from contract c, contractdocumentdetail d 
        where c.active=1 and d.active=1 and c.contractid=d.contractid and 
        c.contractid=".$contractid;
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            return $row['CNT']; 
        }
    }

    function contractmortgagedetailcount(&$connection,$contractid)
    {
        $query = "select count(*) as cnt from contract c, contractmortgagedetail d 
        where c.active=1 and d.active=1 and c.contractid=d.contractid and 
        c.contractid=".$contractid;
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            return $row['CNT']; 
        }
    }

    function contractadvancedetailcount(&$connection,$contractid)
    {
        $query = "select count(*) as cnt from contract c, contractadvancedetail d 
        where c.active=1 and d.active=1 and c.contractid=d.contractid and 
        c.contractid=".$contractid;
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            return $row['CNT']; 
        }
    }

    function contractreceiptdetailcount(&$connection,$contractid)
    {
        $query = "select count(*) as cnt from contract c, contractreceiptdetail d 
        where c.active=1 and d.active=1 and c.contractid=d.contractid and 
        c.contractid=".$contractid;
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            return $row['CNT']; 
        }
    }

    function contractitemloandetailcount(&$connection,$contractid)
    {
        $query = "select count(*) as cnt from contract c, contractitemloandetail d 
        where c.active=1 and d.active=1 and c.contractid=d.contractid and 
        c.contractid=".$contractid;
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            return $row['CNT']; 
        }
    }

    function contractapproverdetailcount(&$connection,$contractid)
    {
        $query = "select count(*) as cnt from contract c, contractapproverdetail d 
        where c.active=1 and d.active=1 and c.contractid=d.contractid and 
        c.contractid=".$contractid;
        $result = oci_parse($connection, $query); $r = oci_execute($result);
        if ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
        {
            return $row['CNT']; 
        }
    }

    function tick($cnt)
    {
        $str = '';
        for($i=0;$i<$cnt;$i++)
        {
            $str = $str.'&#10004;';
        }
        return $str;
    }
?>