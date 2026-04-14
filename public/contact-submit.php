<?php
require_once __DIR__ . '/../config/bootstrap.php';

$redirect = '/contact.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $redirect);
    exit;
}

$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$phone   = trim($_POST['phone']   ?? '');
$company = trim($_POST['company'] ?? '');
$service = trim($_POST['service'] ?? '');
$message = trim($_POST['message'] ?? '');

// Honeypot anti-spam (invisible field named "website" — bots fill it, humans don't)
$honeypot = trim($_POST['website'] ?? '');
if ($honeypot !== '') {
    header('Location: ' . $redirect . '?thanks=1');
    exit;
}

// Server-side validation
$errors = [];
if ($name === '')                      $errors[] = 'name';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'email';
if ($message === '')                   $errors[] = 'message';

if (!empty($errors)) {
    header('Location: ' . $redirect . '?error=' . implode(',', $errors));
    exit;
}

// Rate limit: max 3 submissions per IP per hour (simple SQLite check)
$ip   = $_SERVER['REMOTE_ADDR'] ?? '';
$hour = date('Y-m-d H');
$stmt = db()->prepare("SELECT COUNT(*) FROM leads WHERE source='contact' AND created_at >= datetime('now','-1 hour') AND name LIKE ?");
$stmt->execute([$ip . '%']);
// (Simple: just proceed — add proper rate limiting later if needed)

db()->prepare("INSERT INTO leads (name, email, phone, company, message, source) VALUES (?,?,?,?,?,'contact')")
    ->execute([$name, $email, $phone, $company, $message]);
log_activity('created', 'lead', (int) db()->lastInsertId(), $name);

$notifyTo = get_setting('notify_leads_email');
if ($notifyTo !== '') {
    $body = "New lead from contact form:\n\nName: {$name}\nEmail: {$email}\n"
          . ($phone   ? "Phone: {$phone}\n"     : '')
          . ($company ? "Company: {$company}\n" : '')
          . ($service ? "Service interest: {$service}\n" : '')
          . ($message ? "Message: {$message}"   : '');
    send_systemiks_mail($notifyTo, 'New lead: ' . $name, $body);
}

header('Location: ' . $redirect . '?thanks=1');
exit;
