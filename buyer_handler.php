<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $property_id = intval($_POST['property_id'] ?? 0);
    $property_name = trim($_POST['property_name'] ?? '');
    $buyer_name = trim($_POST['buyer_name'] ?? '');
    $buyer_email = trim($_POST['buyer_email'] ?? '');
    $buyer_phone = trim($_POST['buyer_phone'] ?? '');
    $buyer_address = trim($_POST['buyer_address'] ?? '');
    $buyer_message = trim($_POST['buyer_message'] ?? '');
    
    // Validate required inputs
    if (empty($property_id) || empty($property_name) || empty($buyer_name) || empty($buyer_email) || empty($buyer_phone)) {
        echo json_encode([
            'success' => false,
            'message' => 'All required fields must be filled.'
        ]);
        exit;
    }
    
    // Validate email
    if (!filter_var($buyer_email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email address.'
        ]);
        exit;
    }
    
    // Sanitize inputs
    $property_name = htmlspecialchars($property_name, ENT_QUOTES, 'UTF-8');
    $buyer_name = htmlspecialchars($buyer_name, ENT_QUOTES, 'UTF-8');
    $buyer_email = htmlspecialchars($buyer_email, ENT_QUOTES, 'UTF-8');
    $buyer_phone = htmlspecialchars($buyer_phone, ENT_QUOTES, 'UTF-8');
    $buyer_address = htmlspecialchars($buyer_address, ENT_QUOTES, 'UTF-8');
    $buyer_message = htmlspecialchars($buyer_message, ENT_QUOTES, 'UTF-8');
    
    // Insert into database
    $conn = getDBConnection();
    $stmt = $conn->prepare("INSERT INTO property_interests (property_id, property_name, buyer_name, buyer_email, buyer_phone, buyer_address, buyer_message) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $property_id, $property_name, $buyer_name, $buyer_email, $buyer_phone, $buyer_address, $buyer_message);
    
    if ($stmt->execute()) {
        // Optional: Send email notification to admin
        $to = "info@awanassociates.com";
        $subject = "New Property Interest - $property_name";
        $message = "New property interest received:\n\n";
        $message .= "Property: $property_name\n";
        $message .= "Property ID: $property_id\n\n";
        $message .= "Buyer Details:\n";
        $message .= "Name: $buyer_name\n";
        $message .= "Email: $buyer_email\n";
        $message .= "Phone: $buyer_phone\n";
        $message .= "Address: $buyer_address\n\n";
        $message .= "Message: $buyer_message\n\n";
        $message .= "Submitted: " . date('Y-m-d H:i:s');
        $headers = "From: noreply@awanassociates.com";
        
        // Uncomment to enable email notifications
        // mail($to, $subject, $message, $headers);
        
        // Optional: Send confirmation email to buyer
        $buyer_subject = "Thank You for Your Interest - Awan Associates";
        $buyer_message_text = "Dear $buyer_name,\n\n";
        $buyer_message_text .= "Thank you for your interest in $property_name.\n\n";
        $buyer_message_text .= "Our team will contact you within 24 hours at $buyer_phone or $buyer_email.\n\n";
        $buyer_message_text .= "Best regards,\nAwan Associates Team";
        $buyer_headers = "From: info@awanassociates.com";
        
        // Uncomment to enable buyer confirmation email
        // mail($buyer_email, $buyer_subject, $buyer_message_text, $buyer_headers);
        
        echo json_encode([
            'success' => true,
            'message' => "Thank you, $buyer_name! Your interest in \"$property_name\" has been received. Our team will contact you at $buyer_phone or $buyer_email within 24 hours."
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to submit your interest. Please try again.'
        ]);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}
?>