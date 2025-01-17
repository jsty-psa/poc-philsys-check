<?php

// Current timestamp in ISO format
$date = date(DATE_ISO8601);
$type = "Finger";
$count = 1;
$port = "4501";

// Parameters to send in the request
$params = [
    "env" => "Staging",
    "purpose" => "Auth",
    "specVersion" => "0.9.5.1.5",
    "timeout" => "300000",
    "captureTime" => $date,
    "domainUri" => "https://api.apps-external.uat2.phylsys.gov.ph",
    "transactionId" => "1234567890",
    "bio" => [
        [
            "type" => $type, // Set `$type` variable
            "count" => $count, // Set `$input` variable
            "bioSubType" => ["UNKNOWN"],
            "requestedScore" => "60",
            "deviceId" => "2147000102",
            "deviceSubId" => "1",
            "previousHash" => ""
        ]
    ],
    "customOpts" => [
        [
            "name" => "name1",
            "value" => "value1"
        ]
    ]
];

// Set the fingerprint URL and port (you must set `$port` accordingly)
$fingerprint_url = "http://127.0.0.1:" . $port . "/capture";

// Prepare headers
$headers = [
    "Content-Type: application/json",
    "Accept: */*"
];

// Initialize cURL session
$ch = curl_init();

// Set cURL options for the custom HTTP method (CAPTURE)
curl_setopt($ch, CURLOPT_URL, $fingerprint_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "CAPTURE"); // Use CAPTURE method
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

// Execute the cURL request and capture the response
$response = curl_exec($ch);

// Check if there was an error with the cURL request
if (curl_errno($ch)) {
    echo "Error: " . curl_error($ch);
    curl_close($ch);
    exit;
}

// Close the cURL session
curl_close($ch);

// Decode the JSON response
$data = json_decode($response, true);

// Reset authentication result (this depends on your own application logic)
// resetAuthenticationResult(); // You need to define this function elsewhere

// Process the response data
$data = $data['biometrics'];
$result = json_encode($data, JSON_PRETTY_PRINT);

// Output or use the result (this depends on your application's needs)
echo $result;

?>