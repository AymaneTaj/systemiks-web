<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();

$id = isset($_POST['id']) ? (int) $_POST['id'] : (int) ($_GET['id'] ?? 0);
$invoice = null;
$client = null;
if ($id) {
    $stmt = db()->prepare("SELECT * FROM invoices WHERE id = ?");
    $stmt->execute([$id]);
    $invoice = $stmt->fetch();
    if ($invoice) {
        $c = db()->prepare("SELECT * FROM clients WHERE id = ?");
        $c->execute([$invoice['client_id']]);
        $client = $c->fetch();
    }
}
if (!$invoice || !$client) {
    header('Location: /admin/invoices.php');
    exit;
}

$companyName = get_setting('company_name', SITE_NAME);
$subject = 'Invoice ' . $invoice['invoice_number'] . ' from ' . $companyName;
$body = "Hello " . $client['contact_name'] . ",\n\nPlease find your invoice " . $invoice['invoice_number'] . " from " . $companyName . ".\n\nReply to this email for any questions or payment details.\n\nBest regards,\n" . $companyName;
$html = '<p>Hello ' . htmlspecialchars($client['contact_name']) . ',</p><p>Please find your invoice <strong>' . htmlspecialchars($invoice['invoice_number']) . '</strong> from ' . htmlspecialchars($companyName) . '.</p><p>Reply to this email for any questions or payment details.</p><p>Best regards,<br>' . htmlspecialchars($companyName) . '</p>';

$sent = send_systemiks_mail($client['email'], $subject, $body, $html);
log_activity('sent_email', 'invoice', $id, $invoice['invoice_number']);
header('Location: /admin/invoice-view.php?id=' . $id . '&sent=' . ($sent ? '1' : '0'));
exit;
