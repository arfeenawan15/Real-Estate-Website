<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($phone)) {
        echo json_encode([
            'success' => false,
            'message' => 'All fields are required.'
        ]);
        exit;
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email address.'
        ]);
        exit;
    }
    
    // Sanitize inputs
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
    
    // Insert into database
    $conn = getDBConnection();
    $stmt = $conn->prepare("INSERT INTO contact_submissions (name, email, phone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $phone);
    
    if ($stmt->execute()) {
        // Optional: Send email notification to admin
        $to = "info@awanassociates.com";
        $subject = "New Contact Form Submission - Awan Associates";
        $message = "New contact form submission:\n\n";
        $message .= "Name: $name\n";
        $message .= "Email: $email\n";
        $message .= "Phone: $phone\n";
        $message .= "Submitted: " . date('Y-m-d H:i:s');
        $headers = "From: noreply@awanassociates.com";
        
        // Uncomment to enable email notifications
        // mail($to, $subject, $message, $headers);
        
        echo json_encode([
            'success' => true,
            'message' => "Thank you, $name! We'll contact you at $email or $phone shortly."
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to submit form. Please try again.'
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