<?php
include 'connection.php';
/**
 * Function to generate JSON data from the database
 *
 * @param int $reportPeriodId The report period ID
 * @return string|null The JSON representation of the data or null on failure
 */
function getDataJson(&$conn,$reportPeriodId) {
    
    // Prepare to collect JSON data
    $jsonData = [];

    // Fetch settings
    $settingsQuery = "SELECT f.settingsid, f.approvalid, f.swsid, f.projectnumber FROM settings f,reportperiod r
where f.factoryunitcode=r.factoryunitcode and r.reportperiodid=".$reportPeriodId;
    $settingsStmt = oci_parse($conn, $settingsQuery);
    oci_execute($settingsStmt);

    while ($setting = oci_fetch_array($settingsStmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $settingData = [
            'approvalId' => $setting['APPROVALID'],
            'swsId' => $setting['SWSID'],
            'projectNumber' => $setting['PROJECTNUMBER'],
            'forms' => []
        ];

        // Fetch forms for the current setting
        $formQuery = "SELECT f.formid, f.formname FROM formdefinition f WHERE f.settingsid = :settingsid";
        $formStmt = oci_parse($conn, $formQuery);
        oci_bind_by_name($formStmt, ':settingsid', $setting['SETTINGSID']);
        oci_execute($formStmt);

        while ($form = oci_fetch_array($formStmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $formSections = [];

            // Fetch sections for the current form
            $sectionQuery = "SELECT s.sectionid, s.sectionname,ismultiplefieldresponse FROM sectiondefinition s WHERE s.formid = :formid";
            $sectionStmt = oci_parse($conn, $sectionQuery);
            oci_bind_by_name($sectionStmt, ':formid', $form['FORMID']);
            oci_execute($sectionStmt);

            while ($section = oci_fetch_array($sectionStmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
                $fieldResponses = [];

                // Fetch fields for the current section
                $fieldQuery = "
                    SELECT f.fieldid, f.fieldname, t.inputvalue
                    , NVL(f.issubfields, 0) AS issubfields,nvl(f.ismultiplesubfieldresponse,0) ismultiplesubfieldresponse
                    FROM fieldresponse t
                    JOIN fielddefinition f ON t.fieldid = f.fieldid
                    JOIN sectiondefinition s ON f.sectionid = s.sectionid
                    WHERE s.formid = :formid AND t.reportperiodid = :reportPeriodId 
                    and f.sectionid=:sectionId";
                $fieldStmt = oci_parse($conn, $fieldQuery);
                oci_bind_by_name($fieldStmt, ':formid', $form['FORMID']);
                oci_bind_by_name($fieldStmt, ':reportPeriodId', $reportPeriodId);
                oci_bind_by_name($fieldStmt, ':sectionId', $section['SECTIONID']);
                oci_execute($fieldStmt);

                while ($field = oci_fetch_array($fieldStmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    if ($field['ISSUBFIELDS'] == 0) {
                        if ($section['ISMULTIPLEFIELDRESPONSE']==1)
                        {
                            $fieldResponses[] = [[
                                'fieldName' => $field['FIELDNAME'],
                                'inputValue' => stripslashes($field['INPUTVALUE'])
                            ]];
                        }
                        else 
                        {
                            $fieldResponses[] = [
                                'fieldName' => $field['FIELDNAME'],
                                'inputValue' => stripslashes($field['INPUTVALUE'])
                            ];    

                        }
                    } 
                     else {
                        if ($field['ISMULTIPLESUBFIELDRESPONSE'] == 0)
                        {
                            $subFields = [];

                            // Fetch subfields for the current field
                            $subfieldQuery = "
                                SELECT f.subfieldname, t.inputvalue
                                    FROM subfieldresponse t, subfielddefinition f, fieldresponse r
                                    WHERE t.subfieldid = f.subfieldid
                                    AND t.fieldresponseid = r.responseid
                                    AND r.reportperiodid = :reportPeriodId
                                    AND f.fieldid = :fieldid
                                    ORDER BY f.sequencenumber";
                            $subfieldStmt = oci_parse($conn, $subfieldQuery);
                            oci_bind_by_name($subfieldStmt, ':fieldid', $field['FIELDID']);
                            oci_bind_by_name($subfieldStmt, ':reportPeriodId', $reportPeriodId);
                            oci_execute($subfieldStmt);

                            while ($subfield = oci_fetch_array($subfieldStmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
                                $subFields[] = [
                                    'fieldName' => $subfield['SUBFIELDNAME'],
                                    'inputValue' => $subfield['INPUTVALUE']
                                ];
                            }
                            if ($section['ISMULTIPLEFIELDRESPONSE']==1)
                            {
                                $fieldResponses[] = [[
                                    'fieldName' => $field['FIELDNAME'],
                                    'subFields' => $subFields
                                ]];
                            }
                            else 
                            {
                                $fieldResponses[] = [
                                    'fieldName' => $field['FIELDNAME'],
                                    'subFields' => $subFields
                                ];    

                            }
                        }    
                        else
                        {
                            $serialsubfieldQuery = "
                                SELECT t.serialnumber
                                FROM subfieldresponse t
                                JOIN subfielddefinition f ON t.subfieldid = f.subfieldid
                                JOIN fieldresponse r ON t.fieldresponseid = r.responseid
                                WHERE f.fieldid = :fieldid AND r.reportperiodid = :reportPeriodId
                                group by t.serialnumber";
                            $serialsubfieldStmt = oci_parse($conn, $serialsubfieldQuery);
                            oci_bind_by_name($serialsubfieldStmt, ':fieldid', $field['FIELDID']);
                            oci_bind_by_name($serialsubfieldStmt, ':reportPeriodId', $reportPeriodId);
                            oci_execute($serialsubfieldStmt);

                            while ($serialsubfield = oci_fetch_array($serialsubfieldStmt, OCI_ASSOC + OCI_RETURN_NULLS)) 
                            {
                                $subFields = [];
                                // Fetch subfields for the current field
                                $subfieldQuery = "
                                    SELECT f.subfieldname, t.inputvalue
                                    FROM subfieldresponse t, subfielddefinition f, fieldresponse r
                                    WHERE t.subfieldid = f.subfieldid
                                    AND t.fieldresponseid = r.responseid
                                    AND r.reportperiodid = :reportPeriodId
                                    AND f.fieldid = :fieldid
                                    and t.serialnumber = :serialNumber
                                    ORDER BY f.sequencenumber";
                                $subfieldStmt = oci_parse($conn, $subfieldQuery);
                                oci_bind_by_name($subfieldStmt, ':fieldid', $field['FIELDID']);
                                oci_bind_by_name($subfieldStmt, ':reportPeriodId', $reportPeriodId);
                                oci_bind_by_name($subfieldStmt, ':serialNumber', $serialsubfield['SERIALNUMBER']);
                                oci_execute($subfieldStmt);

                                while ($subfield = oci_fetch_array($subfieldStmt, OCI_ASSOC + OCI_RETURN_NULLS)) 
                                {
                                    $subFields[] = [
                                        'fieldName' => $subfield['SUBFIELDNAME'],
                                        'inputValue' => $subfield['INPUTVALUE']
                                    ];
                                }
                                if ($section['ISMULTIPLEFIELDRESPONSE']==1)
                                {
                                    $fieldResponses[] = [[
                                        'fieldName' => $field['FIELDNAME'],
                                        'serialNumber' => $serialsubfield['SERIALNUMBER'],
                                        'subFields' => $subFields
                                    ]];
                                }
                                else 
                                {
                                    $fieldResponses[] = [
                                        'fieldName' => $field['FIELDNAME'],
                                        'serialNumber' => $serialsubfield['SERIALNUMBER'],
                                        'subFields' => $subFields
                                    ];    

                                }
                            }
                        }
                    }
                }

                $formSections[] = [
                    'sectionName' => $section['SECTIONNAME'] ,
                    'fieldResponses' => $fieldResponses 
                ];
            }

            $settingData['forms'][] = [
                'name' => $form['FORMNAME'],
                'sections' => $formSections
            ];
        }

        $jsonData[] = $settingData;
    }

    // Free resources
    oci_free_statement($settingsStmt);
    oci_free_statement($formStmt);
    oci_free_statement($sectionStmt);
    //oci_free_statement($fieldStmt);
    //oci_free_statement($subfieldStmt);
    oci_close($conn);
    // Return JSON encoded data
    return json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}
// Example usage
$reportPeriodId = $_GET['reportperiodid']; // Set your report period ID
// Fetch settings
$reportperiodQuery = "SELECT r.season, r.month,r.factoryunitcode
                      FROM reportperiod r 
                      WHERE r.reportperiodid = :reportperiodid";
$reportperiodStmt = oci_parse($conn, $reportperiodQuery);

// Check if the statement was parsed successfully
if (!$reportperiodStmt) {
    $e = oci_error($conn); // Get the connection error
    echo "Error parsing statement: " . htmlentities($e['message']);
    exit;
}

// Bind the reportperiodid to the query
oci_bind_by_name($reportperiodStmt, ':reportperiodid', $reportPeriodId);

// Execute the query and check for errors
if (!oci_execute($reportperiodStmt)) {
    $e = oci_error($reportperiodStmt); // Get the statement error
    echo "Error executing query: " . htmlentities($e['message']);
    exit;
}

// Fetch the report period details
if ($reportperiod = oci_fetch_array($reportperiodStmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
    // Safely build the file path using DIRECTORY_SEPARATOR for cross-platform compatibility
    $file = "upload" . DIRECTORY_SEPARATOR . htmlspecialchars($reportperiod['FACTORYUNITCODE']) . "_". htmlspecialchars($reportperiod['SEASON']) . "_" . htmlspecialchars($reportperiod['MONTH']) . ".json";

    // Now $file contains the full path to the file
    //echo "File path: " . $file; // Optional: Output the file path for debugging
} else {
    // Handle the case where no result was found
    echo "No report period found for the provided reportperiodid.";
}

$jsonResult = getDataJson($conn,$reportPeriodId);

// Read the JSON data from the file
//$jsonData = file_get_contents($file);
$jsonData = $jsonResult;
// Decode the JSON into a PHP array (or object if preferred)
$data = json_decode($jsonData, true); // true means array, false means object

// Check if the decoding was successful
if ($data === null) {
    echo "Error decoding JSON data.";
} else {
    // Encode the data back to JSON with pretty print
    $formattedJson = json_encode($data, JSON_PRETTY_PRINT);

    // Output the formatted JSON
    //echo $formattedJson;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formatted JSON Display</title>
    <style>
        /* Optional styling for better readability */
        pre {
            background-color: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            white-space: pre-wrap;
            word-wrap: break-word;
            font-family: 'Courier New', monospace;
            font-size: 18px;
        }
        /* Styling for the back button */
        .back-button {
            background-color: #4CAF50; /* Blue background */
            color: white; /* White text */
            padding: 10px 20px; /* Padding */
            text-decoration: none; /* Remove underline */
            border-radius: 5px; /* Rounded corners */
            font-size: 16px;
        }

        .back-button:hover {
            background-color: #45a049; /* Darker blue on hover */
        }
    </style>
</head>
<body>
        <?php
        if (file_put_contents($file, $jsonResult)) {
            echo "JSON data successfully written to the file.";
        } else {
            echo "Error writing JSON data to the file.";
        }
        ?>
    <h3>Generated JSON File Data</h3>
    <!-- Back button -->
    <button class="back-button" onclick="window.history.back()">Back</button>

    <pre><?php echo htmlspecialchars($formattedJson); ?></pre>
</body>
</html>


