<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();
admin_require_role(['admin', 'editor']);
csrf_validate();

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$quote = null;
if ($id) {
    $stmt = db()->prepare("SELECT * FROM quotes WHERE id = ?");
    $stmt->execute([$id]);
    $quote = $stmt->fetch();
}
if (!$quote) {
    header('Location: quotes.php');
    exit;
}

$pdo = db();

// If an invoice already exists for this quote, redirect to it
$existing = $pdo->prepare("SELECT id FROM invoices WHERE quote_id = ? LIMIT 1");
$existing->execute([$id]);
$existingId = $existing->fetchColumn();
if ($existingId) {
    header('Location: invoice-view.php?id=' . (int) $existingId . '&from=quote');
    exit;
}

$invNum = next_invoice_number();
$issueDate = date('Y-m-d');
$dueDate = date('Y-m-d', strtotime('+' . (int) get_setting('quote_validity_days', '30') . ' days'));
$paymentTerms = get_setting('payment_terms', 'Net 30');
$notes = $quote['notes'] ?? '';

$pdo->prepare("
    INSERT INTO invoices (client_id, project_id, quote_id, invoice_number, status, issue_date, due_date, payment_terms, notes, paid_at)
    VALUES (?, ?, ?, ?, 'paid', ?, ?, ?, ?, CURRENT_TIMESTAMP)
")->execute([
    $quote['client_id'],
    $quote['project_id'] ?: null,
    $quote['id'],
    $invNum,
    $issueDate,
    $dueDate,
    $paymentTerms,
    $notes,
]);
$invId = (int) $pdo->lastInsertId();

$quoteLines = $pdo->prepare("SELECT sort_order, description, quantity, unit_price FROM quote_lines WHERE quote_id = ? ORDER BY sort_order, id");
$quoteLines->execute([$id]);
$ins = $pdo->prepare("INSERT INTO invoice_lines (invoice_id, sort_order, description, quantity, unit_price) VALUES (?, ?, ?, ?, ?)");
while ($row = $quoteLines->fetch()) {
    $ins->execute([$invId, $row['sort_order'], $row['description'], $row['quantity'], $row['unit_price']]);
}

$pdo->prepare("UPDATE quotes SET status = 'paid', updated_at = CURRENT_TIMESTAMP WHERE id = ?")->execute([$id]);

log_activity('marked_paid_quote', 'quote', $id, $quote['quote_number']);
log_activity('created', 'invoice', $invId, $invNum);

header('Location: invoice-view.php?id=' . $invId . '&from=quote&paid=1');
exit;
