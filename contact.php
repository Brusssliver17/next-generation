<?php
header('Content-Type: application/json');

// Basic anti-spam: allow only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success' => false, 'error' => 'Invalid request.']);
  exit;
}

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $message === '') {
  echo json_encode(['success' => false, 'error' => 'Please fill all fields.']);
  exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(['success' => false, 'error' => 'Invalid email address.']);
  exit;
}

// CHANGE THIS TO YOUR EMAIL
$to = 'igeboremmanuel17@gmail.com';
$subject = 'New message from NEXT GENERATION website';
$body  = "You have a new message:\n\n";
$body .= "Name: $name\n";
$body .= "Email: $email\n";
$body .= "Message:\n$message\n\n";
$body .= "Sent on: " . date('Y-m-d H:i:s');

$headers   = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/plain; charset=utf-8';
$headers[] = 'From: NEXT GENERATION <no-reply@yourdomain.com>';
$headers[] = "Reply-To: $name <$email>";
$headers[] = 'X-Mailer: PHP/' . phpversion();

$sent = @mail($to, $subject, $body, implode("\r\n", $headers));

if ($sent) {
  echo json_encode(['success' => true]);
} else {
  // Helpful fallback instruction
  echo json_encode([
    'success' => false,
    'error' => 'Server could not send email (mail() disabled). Ask your host to enable PHP mail or set up SMTP/PHPMailer.'
  ]);
}
