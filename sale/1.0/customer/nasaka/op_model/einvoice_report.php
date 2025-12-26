<?php
    ob_start();
    include_once("../swappbase/reportbase.php");
    include_once("../swappbase/mypdf_a4_l.php");
    include_once("../info/routine.php");
    include_once("../ip_model/saleinvoiceheader_db_oracle.php");
    include_once("../ip_model/goodspurchaser_db_oracle.php");
//textbox($data,$width,$col,$datatype='',$alighment='',$languagecode='',$fontname='',$fontsize='',$newline='',$cangrow='',$currencysymbol='')  
//hline($startcol,$endcol,$row='',$type='')
//vline($startrow,$endrow,$col,$type='')
class einvoice
{	
    public $transactionnumber;
    Public $connection;
    Public $invoicenumber;

    function export(&$connection,$invoicenumber)
    {
           
           $this->connection=$connection;
           $this->invoicenumber=$invoicenumber;
           $query = "select * from trandtls where invoicenumber={$this->invoicenumber} and yearperiodcode={$_SESSION['yearperiodcode']}";
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
           $this->transactionnumber=$row['TRANSACTIONNUMBER'];
           $name=str_replace('/','_',$row['INVOICENUMBERPRESUF']).'_'.$this->transactionnumber;
           $fp = fopen('../../../../../../exportb2b/'.$name.'.json', 'w');
           $query = "select * from trandtls where transactionnumber={$this->transactionnumber}";
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $jsonlist_data = array();
           $json_data = array();
           $json_tran_data = array();
           $json_data['Version']="1.1";
           $json_data['TranDtls']=array(); 
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                $json_tran_data['TaxSch']=$row['TAXSCH'];  
                $json_tran_data['SupTyp']=$row['SUPTYP'];  
                $json_tran_data['IgstOnIntra']=$row['IGSTONINTRA'];  
                $json_tran_data['RegRev']=$row['REGREV']; 
                $json_tran_data['EcmGstin']=$row['ECMGSTIN']; 
                //here pushing the values in to an array  
                array_push($json_data['TranDtls'],$json_tran_data);  
           }
           $query = "select * from DocDtls where transactionnumber={$this->transactionnumber}";
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $json_doc_data = array();
           $json_data['DocDtls']=array(); 
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                $json_doc_data['Typ']=$row['TYP'];  
                $json_doc_data['No']=$row['NO'];  
                $json_doc_data['Dt']=$row['DT'];  
                //here pushing the values in to an array  
                array_push($json_data['DocDtls'],$json_doc_data);  
           }

           $query = "select * from SellerDtls where transactionnumber={$this->transactionnumber}";
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $json_sellerdtls_data = array();
           $json_data['SellerDtls']=array();
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                $json_sellerdtls_data['Gstin']=$row['GSTIN'];  
                $json_sellerdtls_data['LglNm']=$row['LGLNM'];  
                $json_sellerdtls_data['TrdNm']=$row['TRDNM'];  
                $json_sellerdtls_data['Addr1']=$row['ADDR1'];  
                $json_sellerdtls_data['Addr2']=$row['ADDR2'];  
                $json_sellerdtls_data['Loc']=$row['LOC'];  
                $json_sellerdtls_data['Pin']=$row['PIN'];  
                $json_sellerdtls_data['Stcd']=$row['STCD']; 
                $json_sellerdtls_data['Ph']=$row['PH']; 
                $json_sellerdtls_data['Em']=$row['EM']; 
                //here pushing the values in to an array  
                array_push($json_data['SellerDtls'],$json_sellerdtls_data);  
           }

           $query = "select * from sellerdtls";
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $json_sellerdtls_data = array();
           $json_data['SellerDtls']=array();
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                $json_sellerdtls_data['Gstin']=$row['GSTIN'];  
                $json_sellerdtls_data['LglNm']=$row['LGLNM'];  
                $json_sellerdtls_data['TrdNm']=$row['TRDNM'];  
                $json_sellerdtls_data['Addr1']=$row['ADDR1'];  
                $json_sellerdtls_data['Addr2']=$row['ADDR2'];  
                $json_sellerdtls_data['Loc']=$row['LOC'];  
                $json_sellerdtls_data['Pin']=$row['PIN'];  
                $json_sellerdtls_data['Stcd']=$row['STCD']; 
                $json_sellerdtls_data['Ph']=$row['PH']; 
                $json_sellerdtls_data['Em']=$row['EM']; 
                //here pushing the values in to an array  
                array_push($json_data['SellerDtls'],$json_sellerdtls_data);  
           }

           $query = "select * from buyerdtls where transactionnumber={$this->transactionnumber}";
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $json_buyerdtls_data = array();
           $json_data['BuyerDtls']=array();
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                $json_buyerdtls_data['Gstin']=$row['GSTIN'];  
                $json_buyerdtls_data['LglNm']=$row['LGLNM'];  
                $json_buyerdtls_data['TrdNm']=$row['TRDNM'];
                $json_buyerdtls_data['Pos']=$row['POS'];
                $json_buyerdtls_data['Addr1']=$row['ADDR1'];  
                $json_buyerdtls_data['Addr2']=$row['ADDR2'];  
                $json_buyerdtls_data['Loc']=$row['LOC'];  
                $json_buyerdtls_data['Pin']=$row['PIN'];  
                $json_buyerdtls_data['Stcd']=$row['STCD']; 
                $json_buyerdtls_data['Ph']=$row['PH']; 
                $json_buyerdtls_data['Em']=$row['EM']; 
                //here pushing the values in to an array  
                array_push($json_data['BuyerDtls'],$json_buyerdtls_data);  
           }

           $query = "select * from ShipDtls where transactionnumber={$this->transactionnumber}";
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $json_shipdtls_data = array();
           $json_data['ShipDtls']=array();
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                $json_shipdtls_data['Gstin']=$row['GSTIN'];  
                $json_shipdtls_data['LglNm']=$row['LGLNM'];  
                $json_shipdtls_data['TrdNm']=$row['TRDNM'];
                $json_shipdtls_data['Addr1']=$row['ADDR1'];  
                $json_shipdtls_data['Addr2']=$row['ADDR2'];  
                $json_shipdtls_data['Loc']=$row['LOC'];  
                $json_shipdtls_data['Pin']=$row['PIN'];  
                $json_shipdtls_data['Stcd']=$row['STCD']; 
                //here pushing the values in to an array  
                array_push($json_data['ShipDtls'],$json_shipdtls_data);  
           }

           $query = "select * from ValDtls where transactionnumber={$this->transactionnumber}";
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $json_valdtls_data = array();
           $json_data['ValDtls']=array();
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                $json_valdtls_data['Gstin']=$row['GSTIN'];  
                $json_valdtls_data['LglNm']=$row['LGLNM'];  
                $json_valdtls_data['TrdNm']=$row['TRDNM'];
                $json_valdtls_data['Addr1']=$row['ADDR1'];  
                $json_valdtls_data['Addr2']=$row['ADDR2'];  
                $json_valdtls_data['Loc']=$row['LOC'];  
                $json_valdtls_data['Pin']=$row['PIN'];  
                $json_valdtls_data['Stcd']=$row['STCD']; 
                //here pushing the values in to an array  
                array_push($json_data['ValDtls'],$json_valdtls_data);  
           }

           $query = "select * from ExpDtls where transactionnumber={$this->transactionnumber}";
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $json_expdtls_data = array();
           $json_data['ExpDtls']=array();
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                $json_expdtls_data['Gstin']=$row['GSTIN'];  
                $json_expdtls_data['LglNm']=$row['LGLNM'];  
                $json_expdtls_data['TrdNm']=$row['TRDNM'];
                $json_expdtls_data['Addr1']=$row['ADDR1'];  
                $json_expdtls_data['Addr2']=$row['ADDR2'];  
                $json_expdtls_data['Loc']=$row['LOC'];  
                $json_expdtls_data['Pin']=$row['PIN'];  
                $json_expdtls_data['Stcd']=$row['STCD']; 
                //here pushing the values in to an array  
                array_push($json_data['ExpDtls'],$json_expdtls_data);  
           }

           $query = "select * from EwbDtls where transactionnumber={$this->transactionnumber}";
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $json_ewbdtls_data = array();
           $json_data['EwbDtls']=array();
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                $json_ewbdtls_data['TransId']=$row['TRANSID'];  
                $json_ewbdtls_data['TransName']=$row['TRANSNAME'];  
                $json_ewbdtls_data['TransMode']=$row['TRANSMODE'];
                $json_ewbdtls_data['Distance']=$row['DISTANCE'];  
                $json_ewbdtls_data['TransDocNo']=$row['TRANSDOCNO'];  
                $json_ewbdtls_data['TransDocDt']=$row['TRANSDOCDATE'];  
                $json_ewbdtls_data['VehNo']=$row['VEHNO'];  
                $json_ewbdtls_data['VehType']=$row['VEHTYPE']; 
                //here pushing the values in to an array  
                array_push($json_data['EwbDtls'],$json_ewbdtls_data);  
           }
           
           $query = "select * from ItemList where transactionnumber={$this->transactionnumber}";
           $result = oci_parse($this->connection, $query);
           $r = oci_execute($result);
           $json_itemlist_data = array();
           $json_data['ItemList']=array();
           while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
           {
                $json_itemlist_data['SlNo']=$row['SLNO'];  
                $json_itemlist_data['PrdDesc']=$row['PRDDESC'];  
                $json_itemlist_data['IsServc']=$row['ISSERVC'];
                $json_itemlist_data['HsnCd']=$row['HSNCD'];  
                $json_itemlist_data['Qty']=$row['QTY'];  
                $json_itemlist_data['Unit']=$row['UNIT'];  
                $json_itemlist_data['UnitPrice']=$row['UNITPRICE'];  
                $json_itemlist_data['TotAmt']=$row['TOTAMT']; 
                $json_itemlist_data['Discount']=$row['DISCOUNT']; 
                $json_itemlist_data['PreTaxVal']=$row['PRETAXVAL']; 
                $json_itemlist_data['AssAmt']=$row['ASSAMT']; 
                $json_itemlist_data['GstRt']=$row['GSTRT']; 
                $json_itemlist_data['IgstAmt']=$row['IGSTAMT']; 
                $json_itemlist_data['CgstAmt']=$row['CGSTAMT']; 
                $json_itemlist_data['SgstAmt']=$row['SGSTAMT']; 
                $json_itemlist_data['CesRt']=$row['CESRT']; 
                $json_itemlist_data['CesAmt']=$row['CESAMT']; 
                $json_itemlist_data['CesNonAdvlAmt']=$row['CESNONADVLAMT']; 
                $json_itemlist_data['StateCesRt']=$row['STATECESRT']; 
                $json_itemlist_data['StateCesAmt']=$row['STATECESAMT']; 
                $json_itemlist_data['StateCesNonAdvlAmt']=$row['STATECESNONADVLAMT']; 
                $json_itemlist_data['OthChrg']=$row['OTHCHRG']; 
                $json_itemlist_data['TotItemVal']=$row['TOTITEMVAL']; 
                //here pushing the values in to an array  
                array_push($json_data['ItemList'],$json_itemlist_data);  
           }

           array_push($jsonlist_data,$json_data);  
           fwrite($fp, json_encode($jsonlist_data,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

           fclose($fp);
    }
    function exportcsv(&$connection,$invoicenumber)
    {
          //$fp1 = fopen('../../../../../../exportb2b/'.$name.'.csv', 'w');
          $query = "select * from einvoiceexcel where invoicenumber={$this->invoicenumber} and yearperiodcode={$_SESSION['yearperiodcode']}";
          $result = oci_parse($this->connection, $query);
          $r = oci_execute($result);
          $row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS);
          $this->transactionnumber=$row['TRANSACTIONNUMBER'];
          $name=str_replace('/','_',$row['INVOICENUMBERPRESUF']).'_'.$this->transactionnumber.".csv";
          $fp=fopen('php://memory', 'w');
          $query = "select suptyp, regrev, c, igstonintra, typ, no, dt, gstin, lglnm, trdnm, pos, addr1, addr2, loc, pin, stcd, ph, em, s, t, u, v, w, x
          , gstin_p, lglnm_p, trdnm_p, addr1_p, addr2_p, loc_p, pin_p, stcd_p, slno, prddesc, ai, hsncd, ak, qty, am, unit, unitprice, totamt, discount, pretaxval, totamt_i, gstrt, sgstamt, cgstamt, igstamt, cesrt, cesamt, cesnonadvlamt, statecesrt, statecesadv, statecesnonadvlamt, othchrg, totitemval, bf, bg, bh, assval, sgstval, cgstval, igstval, cesval, stcesval, discount_i, othchrg_i, rndoffamt, totinvval, shipbno, shipbdt, port, refclm, forcur, cntcode, expduty, transid, transname, transmode, distance, no_1, dt_1, vehno, vehtype, transactionnumber, yearperiodcode, invoicenumber, invoicenumberpresuf 
          from trandtls 
          where transactionnumber={$this->transactionnumber}";
          $result = oci_parse($this->connection, $query);
          $r = oci_execute($result);
          $jsonlist_data = array();
          $json_data = array();
          $json_tran_data = array();
          $json_data['Version']="1.1";
          $json_data['TranDtls']=array(); 
          while ($row = oci_fetch_array($result,OCI_ASSOC+OCI_RETURN_NULLS))
          {
               fputcsv($fp, array($row['SUPTYP'],$row['REGREV'],$row['C'],$row['IGSTONINTRA'],$row['TYP'],$row['NO'],$row['DT']
               ,$row['GSTIN'],$row['LGLNM'],$row['TRDNM'],$row['POS'],$row['ADDR1'],$row['ADDR2'],$row['LOC'],$row['PIN']
               ,$row['STCD'],$row['PH'],$row['EM'],$row['S'],$row['T'],$row['U'],$row['V'],$row['W'],$row['X'],$row['GSTIN_P']
               ,$row['LGLNM_P'],$row['TRDNM_P'],$row['ADDR1_P'],$row['ADDR2_P'],$row['LOC_P'],$row['PIN_P'],$row['STCD_P']
               ,$row['SLNO'],$row['PRDDESC'],$row['AI'],$row['HSNCD'],$row['AK'],$row['QTY'],$row['AM'],$row['UNIT']
               ,$row['UNITPRICE'],$row['TOTAMT'],$row['DISCOUNT'],$row['PRETAXVAL'],$row['TOTAMT_I'],$row['GSTRT']
               ,$row['SGSTAMT'],$row['CGSTAMT'],$row['IGSTAMT'],$row['CESRT'],$row['CESAMT'],$row['CESNONADVLAMT']
               ,$row['STATECESRT'],$row['STATECESADV'],$row['STATECESNONADVLAMT'],$row['OTHCHRG'],$row['TOTITEMVAL']
               ,$row['BF'],$row['BG'],$row['BH'],$row['ASSVAL'],$row['SGSTVAL'],$row['CGSTVAL'],$row['IGSTVAL']
               ,$row['CESVAL'],$row['STCESVAL'],$row['DISCOUNT_I'],$row['OTHCHRG_I'],$row['RNDOFFAMT'],$row['TOTINVVAL']
               ,$row['SHIPBNO'],$row['SHIPBDT'],$row['PORT'],$row['REFCLM'],$row['FORCUR'],$row['CNTCODE'],$row['EXPDUTY']
               ,$row['TRANSID'],$row['TRANSNAME'],$row['TRANSMODE'],$row['DISTANCE'],$row['NO_1'],$row['DT_1'],$row['VEHNO']
               ,$row['VEHTYPE'],$row['TRANSACTIONNUMBER'],$row['YEARPERIODCODE'],$row['INVOICENUMBER'],$row['INVOICENUMBERPRESUF']), $delimiter = ',', $enclosure = '"');
          }
          // reset the file pointer to the start of the file
          fseek($fp, 0);
          // tell the browser it's going to be a csv file
          header('Content-Type: application/csv');
          // tell the browser we want to save it instead of displaying it
          header('Content-Disposition: attachment; filename="'.$filename.'";');
          // make php send the generated csv lines to the browser
          fpassthru($fp); 
          //fclose($fp1);
    }
}    
?>
