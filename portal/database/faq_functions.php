<?php
// faq_editor.php - Script to handle FAQ editing and saving

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)) {
    // Return error response
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'You must be logged in to perform this action.']);
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "icb_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Check if it's a POST request to save FAQs
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get parameters
    $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $faqs = isset($_POST['faqs']) ? $_POST['faqs'] : [];
    
    // Validate product ID
    if ($productId <= 0) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
        exit;
    }
    
    // Convert FAQs to JSON format for storage
    $faqJson = json_encode($faqs);
    
    // Update the product's FAQ field
    $sql = "UPDATE product SET faq = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Prepare statement failed: ' . $conn->error]);
        exit;
    }
    
    $stmt->bind_param("si", $faqJson, $productId);
    $result = $stmt->execute();
    
    if ($result) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'FAQs updated successfully']);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Update failed: ' . $stmt->error]);
    }
    
    $stmt->close();
} 
// GET request to fetch FAQs
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['product_id'])) {
    $productId = intval($_GET['product_id']);
    
    // Fetch the product's FAQ data
    $sql = "SELECT faq FROM product WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Prepare statement failed: ' . $conn->error]);
        exit;
    }
    
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $stmt->bind_result($faqContent);
    $stmt->fetch();
    $stmt->close();
    
    // Parse FAQ content
    $faqs = parseFaqContent($faqContent);
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'faqs' => $faqs]);
}
else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request method or missing parameters']);
}

// Function to parse FAQ content from database format (same as in the main file)
function parseFaqContent($faqContent) {
    $faqs = [];
    
    // First try to parse as JSON (most reliable format for multiple FAQs)
    $jsonData = json_decode($faqContent, true);
    if (is_array($jsonData) && !empty($jsonData) && isset($jsonData[0]['question'])) {
        return $jsonData; // Already in the right format
    }
    
    // Try to parse Q: format
    $pattern = '/Q: (.*?)(?:\r\n|\r|\n)(.*?)(?=(?:\r\n|\r|\n){2}Q: |$)/s';
    preg_match_all($pattern, $faqContent, $matches, PREG_SET_ORDER);
    
    if (!empty($matches)) {
        foreach ($matches as $match) {
            $faqs[] = [
                'question' => trim($match[1]),
                'answer' => trim($match[2])
            ];
        }
        if (!empty($faqs)) {
            return $faqs;
        }
    }
    
    // Try to split by double newlines and parse each block
    $blocks = preg_split('/(\r\n|\r|\n){2,}/', $faqContent);
    if (count($blocks) > 1) {
        foreach ($blocks as $block) {
            if (!empty(trim($block))) {
                // Try to split question/answer by question mark
                if (preg_match('/^(.*?\?)(.*)$/s', trim($block), $parts)) {
                    $faqs[] = [
                        'question' => trim($parts[1]),
                        'answer' => trim($parts[2])
                    ];
                } else {
                    // If no question mark, use the first line as question
                    $lines = preg_split('/\r\n|\r|\n/', trim($block), 2);
                    if (count($lines) > 1) {
                        $faqs[] = [
                            'question' => trim($lines[0]),
                            'answer' => trim($lines[1])
                        ];
                    } else {
                        // Just one line, use as question with empty answer
                        $faqs[] = [
                            'question' => trim($block),
                            'answer' => ''
                        ];
                    }
                }
            }
        }
        if (!empty($faqs)) {
            return $faqs;
        }
    }
    
    // Fallback: Check if it's a single question-answer pair
    if (preg_match('/^(.*?\?)(.*)$/s', trim($faqContent), $parts)) {
        return [
            [
                'question' => trim($parts[1]),
                'answer' => trim($parts[2])
            ]
        ];
    }
    
    // Last resort: Use the whole content as a single FAQ
    if (!empty(trim($faqContent))) {
        // Try to split into question/answer if there's at least one newline
        $lines = preg_split('/\r\n|\r|\n/', trim($faqContent), 2);
        if (count($lines) > 1) {
            return [
                [
                    'question' => trim($lines[0]),
                    'answer' => trim($lines[1])
                ]
            ];
        } else {
            // No newlines, use everything as question with default answer
            return [
                [
                    'question' => trim($faqContent),
                    'answer' => 'No information available.'
                ]
            ];
        }
    }
    
    // Empty content, return empty array
    return [];
}

$conn->close();
?>