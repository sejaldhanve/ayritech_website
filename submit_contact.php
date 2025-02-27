<?php
// Include the Composer autoloader
require 'vendor/autoload.php';

// Set your SendGrid API key (consider using environment variables for security)
$sendgrid = new \SendGrid(getenv('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')); // Replace with your actual API key, ideally stored in an environment variable

// Check if form data is present
if (isset($_POST['name'], $_POST['email'], $_POST['subject'], $_POST['message'])) {
    // Sanitize form data to avoid XSS or other security issues
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);
    
    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Create the email content
    $email_content = "Name: $name\nEmail: $email\nSubject: $subject\nMessage: $message";

    // Create a new SendGrid Mail object
    $emailObj = new \SendGrid\Mail\Mail();
    $emailObj->setFrom($email, $name);  // Use the email from the form and the name
    $emailObj->setSubject("New Contact Form Submission");
    $emailObj->addTo("ej2021.sejal.dhanve@ves.ac.in", "Admin");  // Admin's email address
    $emailObj->addContent("text/plain", $email_content);

    // Send the email
    try {
        $response = $sendgrid->send($emailObj);
        echo "Your message has been sent successfully.";
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo "All fields are required.";
}
?>
