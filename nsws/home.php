<?php
// Step 1: Include the connection file to connect to the Oracle database
include 'connection.php';

// Step 2: Fetch the existing report periods from the database
$reportperiodQuery = "SELECT reportperiodid, season, month,f.factoryunitname
FROM reportperiod r,factoryunit f 
where r.factoryunitcode=f.factoryunitcode 
order by reportperiodid desc";
$reportperiodStmt = oci_parse($conn, $reportperiodQuery);
oci_execute($reportperiodStmt);

$reportPeriods = [];
while ($row = oci_fetch_array($reportperiodStmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
    $reportPeriods[] = $row;
}

$factoryunitQuery = "SELECT factoryunitcode, factoryunitname FROM factoryunit";
$factoryunitStmt = oci_parse($conn, $factoryunitQuery);
oci_execute($factoryunitStmt);

$factoryunits = [];
while ($row = oci_fetch_array($factoryunitStmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
    $factoryunits[] = $row;
}

// Cleanup the statement
oci_free_statement($reportperiodStmt);

// Step 3: Close the database connection (optional, depends on your application design)
oci_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select or Add Report Period</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        select, input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 3px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .form-section {
            margin-top: 20px;
        }
    </style>

    <script type="text/javascript">
        // Function to handle redirection when an existing report period is selected
        function redirectToUpdate() {
            var reportperiodid = document.getElementById('reportperiod').value;
            if (reportperiodid) {
                // Redirect to updatadata.php with the selected reportperiodid as a URL parameter
                window.location.href = "updatedata.php?reportperiodid=" + reportperiodid;
            }
        }
    </script>
</head>
<body>
    <h2>Select or Add a Report Period</h2>
    
    <!-- Step 4: Create the form to select an existing report period or add a new one -->
    <form method="post" action="submit_reportperiod.php">
        <label for="reportperiod">Select an Existing Report Period:</label>
        <select name="reportperiodid" id="reportperiod" onchange="redirectToUpdate()">
            <option value="">-- Select Report Period --</option>
            <?php foreach ($reportPeriods as $period): ?>
                <option value="<?php echo htmlspecialchars($period['REPORTPERIODID']); ?>">
                    <?php echo $period['FACTORYUNITNAME'].' - '.htmlspecialchars($period['SEASON']) . " - " . htmlspecialchars($period['MONTH']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="form-section">
            <h3>Or Add a New Report Period</h3>
            <label for="factoryunitcode">Factory Unit</label>
            <select name="new_factoryunitcode" id="new_factoryunitcode" >
            <option value="">Select Factory Unit</option>
            <?php foreach ($factoryunits as $factoryunit): ?>
                <option value="<?php echo htmlspecialchars($factoryunit['FACTORYUNITCODE']); ?>">
                    <?php echo htmlspecialchars($factoryunit['FACTORYUNITNAME']); ?>
                </option>
            <?php endforeach; ?>
        </select>
            <label for="season">Enter Season:</label>
            <input type="text" id="season" name="new_season" placeholder="e.g., 2024-25" required>

            <label for="month">Enter Month:</label>
            <input type="text" id="month" name="new_month" placeholder="e.g., 10" required>
            
        </div>

        <input type="submit" value="Submit New Report Period">
    </form>
</body>
</html>
