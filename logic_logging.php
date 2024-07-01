<?php
// Include the logging functions
//include 'path/to/log_functions.php'; // Adjust this path to where log_functions.php is located

// Set the content type to application/json
header('Content-Type: application/json');

// Get the input data from the request
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Extract the log message and type
if (isset($data['log']) && isset($data['type'])) {
    $logMessage = $data['log'];
    $logType = $data['type'];
    $formattedMessage = date('Y-m-d H:i:s') . " - [$logType] - $logMessage" . PHP_EOL;

    // Log the message using the function
    //logMessage($logMessage, $logType);
    file_put_contents('logfile.json', $formattedMessage, FILE_APPEND);

    // Return a success response
    echo json_encode(['status' => 'success']);
} else {
    // Return an error response
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
?>
