<?php
// ---------- PHP form handler (same file) ----------
header_remove(); // remove any default headers (we'll set JSON only for AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax']) && $_POST['ajax'] === '1') {
  header('Content-Type: application/json; charset=utf-8');

  // Basic rate-limiting / spam defense could be added here.
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $message = trim($_POST['message'] ?? '');

  if ($name === '' || $email === '' || $message === '') {
    echo json_encode(['success' => false, 'error' => 'Please fill all fields.']);
    exit;
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'Invalid email address.']);
    exit;
  }

  $to = 'igeboremmanuel17@gmail.com'; // <-- email recipient
  $subject = 'New message from NEXT GENERATION website';
  $body  = "You have a new message from the website:\n\n";
  $body .= "Name: $name\n";
  $body .= "Email: $email\n";
  $body .= "Message:\n$message\n\n";
  $body .= "Sent on: " . date('Y-m-d H:i:s') . " (server time)";

  $headers = [];
  $headers[] = 'MIME-Version: 1.0';
  $headers[] = 'Content-type: text/plain; charset=utf-8';
  $headers[] = 'From: NEXT GENERATION <no-reply@' . ($_SERVER['SERVER_NAME'] ?? 'yourdomain.com') . '>';
  $headers[] = "Reply-To: $name <$email>";
  $headers[] = 'X-Mailer: PHP/' . phpversion();

  // Try mail()
  $sent = @mail($to, $subject, $body, implode("\r\n", $headers));

  if ($sent) {
    echo json_encode(['success' => true]);
  } else {
    // Helpful fallback message
    echo json_encode([
      'success' => false,
      'error' => 'Server could not send email (mail() disabled). Ask your host to enable PHP mail or set up SMTP/PHPMailer.'
    ]);
  }
  exit;
}
// ---------- End PHP form handler ---------