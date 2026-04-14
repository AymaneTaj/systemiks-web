<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();
admin_require_role(['admin', 'editor']);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$lead = null;
if ($id) {
    $stmt = db()->prepare("SELECT * FROM leads WHERE id = ?");
    $stmt->execute([$id]);
    $lead = $stmt->fetch();
}
if (!$lead) { header('Location: leads.php'); exit; }

$pdo = db();
// Find or create client
$stmt = $pdo->prepare("SELECT id FROM clients WHERE email = ?");
$stmt->execute([$lead['email']]);
$client = $stmt->fetch();
if ($client) {
    $clientId = (int) $client['id'];
} else {
    $pdo->prepare("INSERT INTO clients (company, contact_name, email, phone) VALUES (?,?,?,?)")
        ->execute([$lead['company'] ?? '', $lead['name'], $lead['email'], $lead['phone'] ?? '']);
    $clientId = (int) $pdo->lastInsertId();
}

$quoteNumber = next_quote_number();
$validDays = (int) get_setting('quote_validity_days', '30');
$pdo->prepare("INSERT INTO quotes (client_id, quote_number, status, valid_until, notes) VALUES (?,?,?,?,?)")
    ->execute([
        $clientId,
        $quoteNumber,
        'draft',
        date('Y-m-d', strtotime("+{$validDays} days")),
        'Converted from lead #' . $lead['id'] . '. ' . ($lead['message'] ? substr($lead['message'], 0, 500) : '')
    ]);
$quoteId = (int) $pdo->lastInsertId();

db()->prepare("UPDATE leads SET status = 'converted', updated_at = CURRENT_TIMESTAMP WHERE id = ?")->execute([$id]);
log_activity('converted_to_client', 'lead', $id, 'client_id=' . $clientId . ' quote_id=' . $quoteId);

header('Location: /admin/quote-form.php?id=' . $quoteId);
exit;
