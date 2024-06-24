<?php
// Set the content type to application/json
header('Content-Type: application/json');

$fileName = 'logfile.json';

// Get the input data from the request
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Extract the log message and type
if (isset($data['log']) && isset($data['type'])) {
    $logMessage = $data['log'];
    $logType = $data['type'];

    // Define the log file path
    $logFile = $fileName;

    // Format the log message with a timestamp and type
    $formattedMessage = date('Y-m-d H:i:s') . " [$logType] - " . $logMessage . PHP_EOL;

    // Write the log message to the file
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);

    // Return a success response
    echo json_encode(['status' => 'success', 'message' => 'Log message recorded.']);
} else {
    // Return an error response
    echo json_encode(['status' => 'error', 'message' => 'Log message or type not provided.']);
}
?>

