<?php
header('Content-Type: application/json');

// For debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create a log file to debug the incoming data
$log_file = __DIR__ . '/form_debug.log';
file_put_contents($log_file, "Request received at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
file_put_contents($log_file, "Method: " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    file_put_contents($log_file, "Error: Invalid request method\n\n", FILE_APPEND);
    exit;
}

// Read the raw input
$input = file_get_contents('php://input');
file_put_contents($log_file, "Raw input: " . $input . "\n", FILE_APPEND);

// Decode the JSON data
$data = json_decode($input, true);

// Check if JSON decoding was successful
if ($data === null) {
    echo json_encode([
        'success' => false, 
        'message' => 'No data received or invalid JSON: ' . json_last_error_msg(),
        'raw_input' => $input
    ]);
    file_put_contents($log_file, "Error: JSON decode error - " . json_last_error_msg() . "\n\n", FILE_APPEND);
    exit;
}

// Log the received data
file_put_contents($log_file, "Decoded data: " . print_r($data, true) . "\n", FILE_APPEND);

// Get the values from the data with fallbacks
// Important: Use company_id = 101 instead of the session ID
$company_id = 101; // Fixed value instead of using $data['id']
$vision = $data['vision'] ?? '';
$mission = $data['mission'] ?? '';
$objective = $data['objective'] ?? '';
$tagline = $data['tagline'] ?? '';
$state = $data['State'] ?? '';
$city = $data['city'] ?? '';
$pin = $data['pin'] ?? '';
$add_line_1 = $data['Add_line_1'] ?? ''; 
$add_line_2 = $data['Add_line_2'] ?? ''; 
$customer_care_name = $data['Customer_Care_Name'] ?? '';
$customer_care_phone_1 = $data['Customer_Care_phone_num_1'] ?? '';
$customer_care_phone_2 = $data['Customer_Care_phone_num_2'] ?? '';
$customer_care_email = $data['Customer_Care_Email'] ?? '';
$poc_name = $data['POC_Name'] ?? '';
$poc_email = $data['Poc_Email'] ?? '';
$poc_phone = $data['Poc_Phone'] ?? '';

// Database connection
try {
    require_once(__DIR__ . '/../config.php'); // Adjusted path
    
    // If config.php doesn't set up the connection, use these default values
    if (!isset($conn)) {
        $host = "localhost";
        $user = "root";
        $password = "";
        $dbname = "icb_portal";
        $conn = new mysqli($host, $user, $password, $dbname);
    }
    
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }
    
    // Check if record exists with the company_id
    $check_sql = "SELECT COUNT(*) as count FROM company_details WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $company_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $row = $result->fetch_assoc();
    $exists = $row['count'] > 0;
    $check_stmt->close();
    
    if ($exists) {
        // Update existing record
        $sql = "UPDATE company_details SET 
                vision = ?, 
                mission = ?, 
                objective = ?, 
                tagline = ?,
                State = ?, 
                city = ?, 
                pin = ?, 
                Add_line_1 = ?, 
                Add_line_2 = ?,
                Customer_Care_Name = ?,
                Customer_Care_phone_num_1 = ?,
                Customer_Care_phone_num_2 = ?,
                Customer_Care_Email = ?,
                POC_Name = ?,
                Poc_Email = ?,
                Poc_Phone = ?
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssssssssi", 
            $vision, $mission, $objective, $tagline, 
            $state, $city, $pin, $add_line_1, $add_line_2, 
            $customer_care_name, $customer_care_phone_1, $customer_care_phone_2, 
            $customer_care_email, $poc_name, $poc_email, $poc_phone, $company_id);
    } else {
        // Insert new record
        $sql = "INSERT INTO company_details (
                id, vision, mission, objective, tagline, 
                State, city, pin, Add_line_1, Add_line_2,
                Customer_Care_Name, Customer_Care_phone_num_1, Customer_Care_phone_num_2, 
                Customer_Care_Email, POC_Name, Poc_Email, Poc_Phone) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssssssssssssss", 
            $company_id, $vision, $mission, $objective, $tagline, 
            $state, $city, $pin, $add_line_1, $add_line_2, 
            $customer_care_name, $customer_care_phone_1, $customer_care_phone_2, 
            $customer_care_email, $poc_name, $poc_email, $poc_phone);
    }
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    file_put_contents($log_file, "Query executed. Affected rows: " . $stmt->affected_rows . "\n", FILE_APPEND);
    
    // Update successful
    echo json_encode(['success' => true, 'message' => 'Company details updated successfully']);
    file_put_contents($log_file, "Success: Company details updated\n\n", FILE_APPEND);
    
    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    file_put_contents($log_file, "Error: " . $e->getMessage() . "\n\n", FILE_APPEND);
}
?>