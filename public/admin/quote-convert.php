<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();
admin_require_role(['admin', 'editor']);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$quote = null;
if ($id) {
    $stmt = db()->prepare("SELECT * FROM quotes WHERE id = ?");
    $stmt->execute([$id]);
    $quote = $stmt->fetch();
}
if (!$quote) { header('Location: quotes.php'); exit; }

$pdo = db();
$invNum = next_invoice_number();
$pdo->prepare("INSERT INTO invoices (client_id, project_id, quote_id, invoice_number, status, issue_date, due_date, payment_terms) VALUES (?,?,?,?,?,?,?,?)")
    ->execute([
        $quote['client_id'],
        $quote['project_id'] ?: null,
        $quote['id'],
        $invNum,
        'draft',
        date('Y-m-d'),
        date('Y-m-d', strtotime('+' . (int)get_setting('quote_validity_days', '30') . ' days')),
        get_setting('payment_terms', 'Net 30')
    ]);
$invId = (int) $pdo->lastInsertId();

$quoteLines = $pdo->prepare("SELECT sort_order, description, quantity, unit_price FROM quote_lines WHERE quote_id = ? ORDER BY sort_order, id");
$quoteLines->execute([$id]);
$ins = $pdo->prepare("INSERT INTO invoice_lines (invoice_id, sort_order, description, quantity, unit_price) VALUES (?,?,?,?,?)");
while ($row = $quoteLines->fetch()) {
    $ins->execute([$invId, $row['sort_order'], $row['description'], $row['quantity'], $row['unit_price']]);
}

header('Location: /admin/invoice-form.php?id=' . $invId . '&from=quote');
exit;
