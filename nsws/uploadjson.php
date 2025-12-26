<?php
include 'connection.php';
//include_once('phpqrcode/qrlib.php');
//unlink('qr.png');
/* curl --location 'https://uat-nsws-mnstrportal.investindia.gov.in/nsws_license/saveP2Data' \
--header 'access-id: SaveP2Data' \
--header 'access-secret: SaveP2Data#0605_NSWS@1252' \
--header 'api-key: SaveP2Data#0605@AK1161' \
--header 'Content-Type: application/json' \
--header 'Cookie: JSESSIONID=87C2405550A874E21D02299E47E6A504' \ */

/* Plant Name - Balrampur
Plant Code - 8901
SWSID - SW4267788494
Approval Id - M009_D001_A076
Name of the Undertaking/Group - DELIGHT PROTEINS LIMITED
State - UTTAR PRADESH
Capacity (In TCD for sugar mills/Tons Per Day (TPD) for refineries) - 15000
Project No. - P_19 */
/* access-id - SaveP2Data
access-secret - SaveP2Data#0605_NSWS@1252
api-key - SaveP2Data#0605@AK1161
Cookie - JSESSIONID=87C2405550A874E21D02299E47E6A504 */

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
    $file = "upload" . DIRECTORY_SEPARATOR . htmlspecialchars($reportperiod['FACTORYUNITCODE']) . "_" . htmlspecialchars($reportperiod['SEASON']) . "_" . htmlspecialchars($reportperiod['MONTH']) . ".json";

    // Now $file contains the full path to the file
    //echo "File path: " . $file; // Optional: Output the file path for debugging
} else {
    // Handle the case where no result was found
    echo "No report period found for the provided reportperiodid.";
}

$accessid='SaveP2Data';
$accesssecret='SaveP2Data#0605_NSWS@1252';
$apikey='SaveP2Data#0605@AK1161';
$cookie='JSESSIONID=87C2405550A874E21D02299E47E6A504';


// Read the JSON file 
//$json = file_get_contents('SUG_2223_00194_12769.json');
$json = file_get_contents($file);
  
// Decode the JSON file
$json_data = json_decode($json,true);
//$request_url = 'https://uat-nsws-mnstrportal.investindia.gov.in/nsws_license/saveP2Data';
 //$request_url = 'https://www.ppe-nodal-authority.nsws.gov.in/nsws_license/saveP2Data';
//$request_url = 'https://13.126.86.80/nsws_license/saveP2Data';
//$request_url = 'https://13.126.86.80/nsws_license/saveP2Data';
$request_url = 'https://api.nsws.gov.in/nsws_license/saveP2Data';

$headers[]='Accept: application/json';
$headers[]='Content-Type: application/json';
$headers[]='access-id:'.$accessid;
$headers[]='access-secret:'.$accesssecret;
$headers[]='api-key:'.$apikey;
$headers[]='Cookie:'.$cookie;

$curl = curl_init($request_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //Return response as string instead of display
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE );
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE );
curl_setopt($curl, CURLOPT_USERAGENT,"Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 7.1; Trident/5.0)");

curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); //Specify our header
curl_setopt($curl, CURLOPT_HEADER, true);//Include headers in response
curl_setopt($curl, CURLOPT_POST, 1); //Specify that this is a GET request
curl_setopt($curl, CURLOPT_POSTFIELDS,$json);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl); //Execute cURL and collect the response
$curl_info = curl_getinfo($curl); //Retrieve it's info
$curl_error = curl_error($curl); //Retrieve it's error
curl_close($curl); //Close the cURL
if ($curl_error || !$response) {
    if ($curl_error) {
        //An error occurred requesting authorisation from the API!
        echo 'error';
    }
    if (!$response) {
        //The response was empty when requesting authorisation from the API!
        echo 'empty';
    }
} else {
    //echo $response;
    // Extracting the JSON part from the response
// Extracting the JSON part from the response
$parts = explode("\r\n\r\n", $response, 2);
if (count($parts) < 2) {
    echo "Invalid response format.\n";
    exit;
}

$body = $parts[1];

//echo "Raw JSON: $body\n";

// Locate the position of {"status"
$statusKey = '{"status';
$statusPosition = strpos($body, $statusKey);

if ($statusPosition !== false) {
    // Extract substring starting from {"status"
    $extractedJson = substr($body, $statusPosition);
    //echo "Extracted JSON: $extractedJson\n";
    
    // Decode the extracted JSON
    $jsonResponse = json_decode($extractedJson, true);
    
    // Check for JSON decoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Failed to decode JSON: " . json_last_error_msg() . "\n";
        // Optionally log the raw JSON for further inspection
        file_put_contents('json_log.txt', $body);
        exit;
    }

    // Check if 'status' key exists in the decoded JSON response
    if (array_key_exists('status', $jsonResponse)) {
        if ($jsonResponse['status'] == '200') {
            // Extract the message
            $message = $jsonResponse['message'];
    
            // Success case
            if ($message == 'Success') {
                echo '<div style="padding: 15px; color: white; background-color: green; border-radius: 5px; margin-bottom: 10px;">';
                echo $message;
                echo '</div>';
    
                echo '<div style="padding: 15px; color: black; background-color: lightgreen; border-radius: 5px; margin-bottom: 10px;">';
                echo 'Generated Unique Id: ' . htmlentities($jsonResponse['uniqueId']);
                echo '</div>';
            }
            // Error case
            else {
                echo '<div style="padding: 15px; color: brown; background-color: #F5F5DC; border-radius: 5px; margin-bottom: 10px;">';
                echo htmlentities($message);
                echo '</div>';
            }
    
            // Add the back button in both cases
            echo '<div style="text-align: center; margin-top: 15px;">';
            echo '<button onclick="history.back()" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Back</button>';
            echo '</div>';
        }
    } else {
        echo "'status' key not found in the JSON response.\n";
    }
} else {
    echo "'{\"status\"' not found in the response.\n";
}
//echo $jsonResponse['status'];
//echo $jsonResponse['message'];
    
}
?>

