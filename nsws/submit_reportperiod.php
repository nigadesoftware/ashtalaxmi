<?php
include 'connection.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if an existing report period was selected
    if (!empty($_POST['reportperiodid'])) {
        $reportperiodid = $_POST['reportperiodid'];

        // Redirect to updatadata.php with the selected reportperiodid
        header("Location: updatedata.php?reportperiodid=" . $reportperiodid);
        exit;
    } else {
        // Add new report period
        $newFactoryunit = $_POST['new_factoryunitcode'];
        $newSeason = $_POST['new_season'];
        $newMonth = $_POST['new_month'];

        // Insert the new report period into the database
        $insertQuery = "INSERT INTO reportperiod (season, month,factoryunitcode) VALUES (:season, :month,:factoryunitcode) RETURNING reportperiodid INTO :reportperiodid";
        $insertStmt = oci_parse($conn, $insertQuery);
        oci_bind_by_name($insertStmt, ":season", $newSeason);
        oci_bind_by_name($insertStmt, ":month", $newMonth);
        oci_bind_by_name($insertStmt, ":factoryunitcode", $newFactoryunit);
        oci_bind_by_name($insertStmt, ":reportperiodid", $newReportPeriodId, -1, SQLT_INT); // Fetch new ID

        // Execute and check for success
        if (oci_execute($insertStmt)) {
            // Redirect to updatadata.php with the new reportperiodid
            header("Location: updatedata.php?reportperiodid=" . $newReportPeriodId);
            exit;
        } else {
            echo "Failed to add the new report period.";
        }

        // Cleanup
        oci_free_statement($insertStmt);
    }

    // Close the connection
    oci_close($conn);
}
?>
