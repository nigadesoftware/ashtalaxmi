<?php
// Include connection.php for Oracle DB connection
include 'connection.php';

// Step 1: Retrieve the report period ID
$reportperiodid = isset($_POST['reportperiodid']) ? intval($_POST['reportperiodid']) : 0;

// Step 2: Update Fields
if (isset($_POST['fieldid']) && isset($_POST['field_inputvalue'])) {
    foreach ($_POST['fieldid'] as $index => $fieldId) {
        $inputValue = $_POST['field_inputvalue'][$index];
        $updateFieldQuery = "UPDATE fieldresponse SET inputvalue = :inputvalue WHERE fieldid = :fieldid AND reportperiodid = :reportperiodid";
        
        $updateFieldStid = oci_parse($conn, $updateFieldQuery);
        oci_bind_by_name($updateFieldStid, ":inputvalue", $inputValue);
        oci_bind_by_name($updateFieldStid, ":fieldid", $fieldId);
        oci_bind_by_name($updateFieldStid, ":reportperiodid", $reportperiodid);
        oci_execute($updateFieldStid);
        oci_free_statement($updateFieldStid);
    }
}

// Step 3: Update Subfields
if (isset($_POST['subfieldid']) && isset($_POST['subfield_inputvalue'])) {
    foreach ($_POST['subfieldid'] as $responseId => $responseIds) {
        foreach ($responseIds as $serialNumber => $index) {
            foreach($index as $innerIndex => $subfieldId){
                if (isset($_POST['subfield_inputvalue'][$responseId][$serialNumber][$innerIndex])) {
                    $inputValue = $_POST['subfield_inputvalue'][$responseId][$serialNumber][$innerIndex]; // Assuming the input value is in the first element
                    
                    $updateSubfieldQuery = "UPDATE subfieldresponse 
                    SET inputvalue = :inputvalue 
                    WHERE fieldresponseid = :fieldresponseid AND serialnumber = :serialnumber 
                    and subfieldid= :subfieldid";
                    
                    $updateSubfieldStid = oci_parse($conn, $updateSubfieldQuery);
                    oci_bind_by_name($updateSubfieldStid, ":inputvalue", $inputValue);
                    oci_bind_by_name($updateSubfieldStid, ":fieldresponseid", $responseId);
                    oci_bind_by_name($updateSubfieldStid, ":serialnumber", $serialNumber);
                    oci_bind_by_name($updateSubfieldStid, ":subfieldid", $subfieldId);
                    oci_execute($updateSubfieldStid);
                    oci_free_statement($updateSubfieldStid);
                }
            }
        }
    }
}

// Step 4: Redirect or display a success message
echo "Records updated successfully.";
oci_close($conn);
?>
