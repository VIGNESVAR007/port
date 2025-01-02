<?php
// Start the response as JSON
header('Content-Type: application/json');

// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if all fields are set and not empty
    if (empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["message"])) {
        echo json_encode(["message" => "Please fill in all the fields.", "success" => false]);
        exit();
    }

    // Collect and sanitize form data
    $name = htmlspecialchars(trim($_POST["name"])); // Sanitize name
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL); // Sanitize email
    $message = htmlspecialchars(trim($_POST["message"])); // Sanitize message

    // Validate the email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["message" => "Invalid email address.", "success" => false]);
        exit();
    }

    // Email to send the message to
    $to = "Vignesvar31@gmail.com"; // Replace with your email address
    $subject = "New Message from Contact Form";

    // Create the email content (using a simple structure)
    $body = "
    <html>
    <head>
      <title>Contact Form Message</title>
    </head>
    <body>
      <h2>You have a new message from the contact form:</h2>
      <p><strong>Name:</strong> $name</p>
      <p><strong>Email:</strong> $email</p>
      <p><strong>Message:</strong></p>
      <p>$message</p>
    </body>
    </html>
    ";

    // Set email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: $email" . "\r\n";  // Sender email
    $headers .= "Reply-To: $email" . "\r\n";  // Reply to sender email

    // Send the email and check if it was successful
    if (mail($to, $subject, $body, $headers)) {
        // Return success response in JSON
        echo json_encode(["message" => "Thank you for your message. We will get back to you shortly.", "success" => true]);
    } else {
        // Return failure response in JSON
        echo json_encode(["message" => "Sorry, something went wrong. Please try again later.", "success" => false]);
    }

} else {
    // Return error response if request method is not POST
    echo json_encode(["message" => "Invalid request.", "success" => false]);
}
?>
