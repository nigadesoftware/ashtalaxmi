<?php
// Step 1: Include connection.php for Oracle DB connection
include 'connection.php';

// Step 2: Assume reportperiodid is passed as a parameter or fetched from session
$reportperiodid = $_GET['reportperiodid'];

// Step 3: Fetch Forms
$reportperiodQuery = "SELECT season, month, f.factoryunitname
FROM reportperiod r,factoryunit f 
where r.factoryunitcode=f.factoryunitcode and reportperiodid=".$reportperiodid;
$reportperiodStid = oci_parse($conn, $reportperiodQuery);
oci_execute($reportperiodStid);
$reportperiod='';
if ($period = oci_fetch_array($reportperiodStid, OCI_ASSOC)) 
{
    $reportperiod = 'Unit:'.$period['FACTORYUNITNAME'].' Season:'.htmlspecialchars($period['SEASON']) . " Month: " . htmlspecialchars($period['MONTH']);
}

// Step 3: Fetch Forms
$formQuery = "SELECT formid, formname FROM formdefinition";
$formStid = oci_parse($conn, $formQuery);
oci_execute($formStid);

$forms = [];
while ($row = oci_fetch_array($formStid, OCI_ASSOC)) {
    $forms[] = $row; // Collect all forms
}

// Step 4: Fetch Sections for each form
$sections = [];
foreach ($forms as $form) {
    $sectionQuery = "SELECT sectionid, sectionname FROM sectiondefinition WHERE formid = :formid";
    $sectionStid = oci_parse($conn, $sectionQuery);
    oci_bind_by_name($sectionStid, ":formid", $form['FORMID']);
    oci_execute($sectionStid);
    
    $sections[$form['FORMID']] = [];
    while ($row = oci_fetch_array($sectionStid, OCI_ASSOC)) {
        $sections[$form['FORMID']][] = $row; // Collect all sections for this form
    }
}

// Step 5: Fetch Fields, Subfields, and their responses for each section
$fields = [];
$subfieldResponses = [];
$fieldResponses = []; // To hold existing responses
$subfieldResponsesBySerial = []; // To hold existing subfield responses grouped by serial number

foreach ($sections as $formId => $formSections) {
    foreach ($formSections as $section) {
        // Fetch fields for this section
        $fieldQuery = "SELECT fieldid, fieldname,nvl(issubfields,0) issubfields,nvl(ismultiplesubfieldresponse,0) ismultiplesubfieldresponse FROM fielddefinition WHERE sectionid = :sectionid and isactive=1 order by sequencenumber";
        $fieldStid = oci_parse($conn, $fieldQuery);
        oci_bind_by_name($fieldStid, ":sectionid", $section['SECTIONID']);
        oci_execute($fieldStid);

        while ($row = oci_fetch_array($fieldStid, OCI_ASSOC)) {
            $fields[$section['SECTIONID']][] = $row; // Collect all fields for this section

            // Fetch existing field responses with reportperiodid
            $responseQuery = "SELECT responseid, inputvalue,isdefaultdata FROM fieldresponse WHERE fieldid = :fieldid AND reportperiodid = :reportperiodid";
            $responseStid = oci_parse($conn, $responseQuery);
            oci_bind_by_name($responseStid, ":fieldid", $row['FIELDID']);
            oci_bind_by_name($responseStid, ":reportperiodid", $reportperiodid);
            oci_execute($responseStid);

            if ($responseRow = oci_fetch_array($responseStid, OCI_ASSOC)) {
                $fieldResponses[$row['FIELDID']] = $responseRow;
                if ($row['FIELDID']==10)
                {
                    $a=1;
                }
                // Fetch subfields for each field
                $subfieldserialQuery = "SELECT serialnumber 
                FROM subfielddefinition s,subfieldresponse r,fieldresponse f 
                WHERE s.subfieldid=r.subfieldid and r.fieldresponseid=f.responseid 
                and f.reportperiodid= :reportperiodid 
                and f.responseid = :responseid  
                group by serialnumber";
                $subfieldserialStid = oci_parse($conn, $subfieldserialQuery);
                oci_bind_by_name($subfieldserialStid, ":responseid", $responseRow['RESPONSEID']);
                oci_bind_by_name($subfieldserialStid, ":reportperiodid", $reportperiodid);
                oci_execute($subfieldserialStid);

                while ($subfieldserialRow = oci_fetch_array($subfieldserialStid, OCI_ASSOC)) {
                    $subfieldResponsesBySerial[$responseRow['RESPONSEID']][] = $subfieldserialRow['SERIALNUMBER']; // Collect subfields under each field

                    $subfieldresponseQuery = "SELECT s.subfieldid,subfieldname,r.inputvalue 
                    FROM subfielddefinition s,subfieldresponse r,fieldresponse f 
                    WHERE s.subfieldid=r.subfieldid 
                    and r.fieldresponseid=f.responseid 
                    and f.responseid = :responseid 
                    and r.serialnumber=:serialnumber 
                    and f.reportperiodid= :reportperiodid";
                    $subfieldresponseStid = oci_parse($conn, $subfieldresponseQuery);
                    oci_bind_by_name($subfieldresponseStid, ":responseid", $responseRow['RESPONSEID']);
                    oci_bind_by_name($subfieldresponseStid, ":serialnumber", $subfieldserialRow['SERIALNUMBER']);
                    oci_bind_by_name($subfieldresponseStid, ":reportperiodid", $reportperiodid);
                    oci_execute($subfieldresponseStid);
                    while ($subfieldresponseRow = oci_fetch_array($subfieldresponseStid, OCI_ASSOC)) 
                    {
                        $subfieldResponses[$responseRow['RESPONSEID']][$subfieldserialRow['SERIALNUMBER']][] = $subfieldresponseRow; // Collect subfields under each field
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSWS Data></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5; /* Light gray background */
            margin: 20px;
            color: #333;
        }

        h2 {
            color: #003366; /* Dark blue */
            margin-bottom: 20px;
        }

        .form-section {
            background-color: #ffffff; /* White for sections */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .section-title {
            cursor: pointer;
            padding: 10px;
            background-color: #003366; /* Dark blue */
            color: white;
            border-radius: 3px;
            transition: background-color 0.3s;
        }

        .section-title:hover {
            background-color: #00509e; /* Lighter blue on hover */
        }

        table {
            width: 100%;
            max-width: 800px;
            border-collapse: collapse;
            margin: auto;
        }

        table, th, td {
            border: 1px dotted #555; /* Dotted border */
        }

        th, td {
            padding: 10px;
            text-align: left;
            word-wrap: break-word;
        }

        th {
            background-color: #003366; /* Dark blue for headers */
            color: white;
        }

        td {
            background-color: #ffffff; /* White for cells */
        }

        .input-field {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s;
        }

        .input-field:focus {
            border-color: #00509e; /* Change border color on focus */
            outline: none; /* Remove default outline */
        }

        input[readonly] {
            background-color: #e9ecef; /* Light gray for readonly fields */
            color: #333;
            cursor: not-allowed; /* Change cursor to indicate it's readonly */
        }
        
        .button-container {
            margin-bottom: 20px;
        }

        .button-container button {
            padding: 10px 20px;
            margin-right: 10px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            border: none;
        }

        .home-button {
            background-color: #4CAF50; /* Green */
            color: white;
        }

        .generate-json-button {
            background-color: #4CAF50; /* Blue */
            color: white;
        }
        .upload-json-button {
            background-color: #4CAF50; /* Blue */
            color: white;
        }
        .generate-json-button:hover,
        .home-button:hover {
            background-color: #45a049; /* Darker green */
        }
        /* Responsive styles */
        @media (max-width: 600px) {
            table {
                width: 100%;
            }

            th, td {
                font-size: 14px;
                padding: 8px;
            }
        }
    </style>
    
    <script>
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section.style.display === "none") {
                section.style.display = "block";
            } else {
                section.style.display = "none";
            }
        }
    </script>
    <script type="text/javascript">
        // Function to handle JSON generation
        function generateJson(reportperiodid) {
            // Redirect to generate_json.php with the reportperiodid parameter
            window.location.href = "generatejson.php?reportperiodid=" + reportperiodid;
        }
    </script>
    <script type="text/javascript">
        // Function to handle JSON generation
        function uploadJson(reportperiodid) {
            // Redirect to generate_json.php with the reportperiodid parameter
            window.location.href = "uploadjson.php?reportperiodid=" + reportperiodid;
        }
    </script>
</head>
<body>
    <h2>NSWS Data [<?php echo $reportperiod ?>]</h2>
    <div class="button-container">
        <!-- Home Button -->
        <button class="home-button" onclick="window.location.href='home.php'">Home</button>

        <!-- Generate JSON Button -->
        <button class="generate-json-button" onclick="generateJson(<?php echo htmlspecialchars($reportperiodid); ?>)">Generate JSON</button>
        <!-- Generate JSON Button -->
        <button class="upload-json-button" onclick="uploadJson(<?php echo htmlspecialchars($reportperiodid); ?>)">Upload JSON</button>
    </div>
    <form method="post" action="submitdata.php">
        <input type="hidden" name="reportperiodid" value="<?php echo htmlspecialchars($reportperiodid); ?>">

        <!-- Loop through forms -->
        <?php foreach ($forms as $form): ?>
            <div class="form-section">
                <h3>Form: <?php echo htmlspecialchars($form['FORMNAME']); ?></h3>

                <!-- Loop through sections in this form -->
                <?php if (isset($sections[$form['FORMID']])): ?>
                    <?php foreach ($sections[$form['FORMID']] as $section): ?>
                        <div>
                            <div class="section-title" onclick="toggleSection('section-<?php echo $section['SECTIONID']; ?>')">
                                Section: <?php echo htmlspecialchars($section['SECTIONNAME']); ?>
                            </div>
                            <div id="section-<?php echo $section['SECTIONID']; ?>" style="display: none;">
                                <!-- Loop through fields in this section -->
                                <?php if (isset($fields[$section['SECTIONID']])): ?>
                                    <?php foreach ($fields[$section['SECTIONID']] as $field): ?>
                                        <div class="field-section">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th style="width: 70%;">Field Name</th>
                                                        <th style="width: 30%;">Input Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($field['FIELDNAME']); ?></td>
                                                        <td>
                                                            <?php $fieldres = $fieldResponses[$field['FIELDID']]; ?>
                                                            <input type="hidden" name="fieldid[]" value="<?php echo $field['FIELDID']; ?>">
                                                            <input type="text" name="field_inputvalue[]" class="input-field" 
                                                                <?php if ($fieldres['ISDEFAULTDATA'] == 1 or $field['ISSUBFIELDS']==1): ?> readonly <?php endif; ?> 
                                                                value="<?php echo htmlspecialchars($fieldres['INPUTVALUE'] ?? ''); ?>">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                           
                                            <!-- Loop through subfields for this field (if any) -->
                                            <?php if (isset($subfieldResponsesBySerial[$fieldres['RESPONSEID']])): ?>
                                                <?php foreach ($subfieldResponsesBySerial[$fieldres['RESPONSEID']] as $serialNumber => $responses): ?>
                                                    <div style="margin-top: 20px; border: 1px solid #ccc; padding: 10px;">
                                                        <?php if ($field['ISMULTIPLESUBFIELDRESPONSE'] == 1): ?>
                                                        <strong>Serial Number: <?php echo htmlspecialchars($responses); ?></strong>
                                                        <?php endif; ?>
                                                        <table>
                                                            <thead>
                                                                <tr class="subfield-heading">
                                                                    <th style="width: 70%;">Subfield Name</th>
                                                                    <th style="width: 30%;">Subfield Input Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (isset($subfieldResponses[$fieldres['RESPONSEID']][$responses]) && !empty($subfieldResponses[$fieldres['RESPONSEID']][$responses])): ?>
                                                                    <?php foreach ($subfieldResponses[$fieldres['RESPONSEID']][$responses] as $subfieldID => $subfield): ?>
    <tr>
        <td><?php echo htmlspecialchars($subfield['SUBFIELDNAME']); ?></td>
        <td>
            <input type="hidden" name="subfieldid[<?php echo $fieldres['RESPONSEID']; ?>][<?php echo $responses; ?>][]" value="<?php echo $subfield['SUBFIELDID']; ?>">
            <input type="text" name="subfield_inputvalue[<?php echo $fieldres['RESPONSEID']; ?>][<?php echo $responses; ?>][]" 
                   class="input-field" 
                   value="<?php echo htmlspecialchars($subfield['INPUTVALUE']); ?>" 
                   <?php if (($subfield['ISREADONLY'] ?? 0) == 1): ?> readonly <?php endif; ?>>
        </td>
    </tr>
<?php endforeach; ?>
                                                                <?php endif; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <input type="submit" value="Update Records" style="background-color: #00509e; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
    </form>
</body>
</html>





<?php
// Cleanup resources
oci_free_statement($formStid);
oci_free_statement($sectionStid);
oci_free_statement($fieldStid);
oci_free_statement($subfieldserialStid);
oci_free_statement($subfieldresponseStid);
oci_close($conn);
?>

