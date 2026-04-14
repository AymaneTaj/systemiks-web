<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();

$id = isset($_POST['id']) ? (int) $_POST['id'] : (int) ($_GET['id'] ?? 0);
$quote = null;
$client = null;
if ($id) {
    $stmt = db()->prepare("SELECT * FROM quotes WHERE id = ?");
    $stmt->execute([$id]);
    $quote = $stmt->fetch();
    if ($quote) {
        $c = db()->prepare("SELECT * FROM clients WHERE id = ?");
        $c->execute([$quote['client_id']]);
        $client = $c->fetch();
    }
}
if (!$quote || !$client) {
    header('Location: /admin/quotes.php');
    exit;
}

$companyName = get_setting('company_name', SITE_NAME);
$subject = 'Quote ' . $quote['quote_number'] . ' from ' . $companyName;
$body = "Hello " . $client['contact_name'] . ",\n\nPlease find your quote " . $quote['quote_number'] . " from " . $companyName . ".\n\nYou can print or save as PDF from the link we send, or reply to this email for any questions.\n\nBest regards,\n" . $companyName;
$html = '<p>Hello ' . htmlspecialchars($client['contact_name']) . ',</p><p>Please find your quote <strong>' . htmlspecialchars($quote['quote_number']) . '</strong> from ' . htmlspecialchars($companyName) . '.</p><p>Reply to this email for any questions.</p><p>Best regards,<br>' . htmlspecialchars($companyName) . '</p>';

$sent = send_systemiks_mail($client['email'], $subject, $body, $html);
log_activity('sent_email', 'quote', $id, $quote['quote_number']);
header('Location: /admin/quote-view.php?id=' . $id . '&sent=' . ($sent ? '1' : '0'));
exit;
