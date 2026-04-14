<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$invoice = null;
$client = null;
$lines = [];
if ($id) {
    $stmt = db()->prepare("SELECT * FROM invoices WHERE id = ?");
    $stmt->execute([$id]);
    $invoice = $stmt->fetch();
    if ($invoice) {
        $client = db()->prepare("SELECT * FROM clients WHERE id = ?");
        $client->execute([$invoice['client_id']]);
        $client = $client->fetch();
        $lines = db()->prepare("SELECT * FROM invoice_lines WHERE invoice_id = ? ORDER BY sort_order, id");
        $lines->execute([$id]);
        $lines = $lines->fetchAll();
    }
}
if (!$invoice || !$client) { header('Location: invoices.php'); exit; }

$total = 0;
foreach ($lines as $l) $total += (float)$l['quantity'] * (float)$l['unit_price'];

$emailSent = isset($_GET['sent']) ? (int) $_GET['sent'] : null;
$fromQuotePaid = isset($_GET['paid']) && $_GET['paid'] === '1';
$companyName = get_setting('company_name', SITE_NAME);
$companyAddress = get_setting('company_address');
$companyEmail = get_setting('company_email');
$companyPhone = get_setting('company_phone');
$print = isset($_GET['print']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?= htmlspecialchars($invoice['invoice_number']) ?> – <?= htmlspecialchars(SITE_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
    <?php if ($print): ?>
    <style>.no-print{display:none !important;} body{padding:2rem;}</style>
    <?php endif; ?>
</head>
<body class="<?= $print ? 'print-doc' : 'admin-dashboard' ?>">
<?php if (!$print): ?>
    <div class="admin-layout">
        <?php $currentNav = 'invoices'; include __DIR__ . '/includes/sidebar.php'; ?>
        <div class="admin-main-wrap">
            <header class="admin-topbar no-print">
                <h1>Invoice <?= htmlspecialchars($invoice['invoice_number']) ?></h1>
                <p class="welcome">
                    <?php if (admin_can_edit()): ?>
                    <a href="invoice-form.php?id=<?= $id ?>" class="btn btn-secondary">Edit</a>
                    <form method="post" action="invoice-send-email.php" style="display:inline;"><input type="hidden" name="id" value="<?= $id ?>"><button type="submit" class="btn btn-secondary">Send by email</button></form>
                    <?php if ($invoice['status'] !== 'paid'): ?>
                    <a href="invoice-paid.php?id=<?= $id ?>">Mark paid</a>
                    <?php endif; ?>
                    <?php endif; ?>
                    <a href="invoice-view.php?id=<?= $id ?>&print=1" target="_blank" class="btn btn-primary">Print / Save as PDF</a>
                    <a href="invoices.php">← Invoices</a>
                </p>
            </header>
            <main class="admin-main">
                <div class="admin-content doc-preview">
                    <?php if ($fromQuotePaid): ?><div class="alert alert-success">Quote marked as paid. Invoice created and set to paid.</div><?php elseif ($emailSent === 1): ?><div class="alert alert-success">Invoice sent by email to client.</div><?php elseif ($emailSent === 0): ?><div class="alert alert-error">Failed to send email. Check Settings → Email.</div><?php endif; ?>
<?php endif; ?>
    <div class="doc doc-invoice">
        <div class="doc-header">
            <div class="doc-company">
                <strong><?= htmlspecialchars($companyName) ?></strong>
                <?php if ($companyAddress): ?><br><?= nl2br(htmlspecialchars($companyAddress)) ?><?php endif; ?>
                <?php if ($companyEmail): ?><br><?= htmlspecialchars($companyEmail) ?><?php endif; ?>
                <?php if ($companyPhone): ?><br><?= htmlspecialchars($companyPhone) ?><?php endif; ?>
            </div>
            <div class="doc-title">INVOICE <?= htmlspecialchars($invoice['invoice_number']) ?></div>
        </div>
        <div class="doc-parties">
            <div class="doc-bill-to">
                <strong>Bill to</strong><br>
                <?= htmlspecialchars($client['contact_name']) ?><br>
                <?php if ($client['company']): ?><?= htmlspecialchars($client['company']) ?><br><?php endif; ?>
                <?= htmlspecialchars($client['email']) ?><br>
                <?php if ($client['phone']): ?><?= htmlspecialchars($client['phone']) ?><br><?php endif; ?>
                <?php if ($client['address']): ?><?= nl2br(htmlspecialchars($client['address'])) ?><?php endif; ?>
            </div>
            <div class="doc-meta">
                Issue date: <?= $invoice['issue_date'] ? date('Y-m-d', strtotime($invoice['issue_date'])) : '—' ?><br>
                Due date: <?= $invoice['due_date'] ? date('Y-m-d', strtotime($invoice['due_date'])) : '—' ?><br>
                <?php if ($invoice['payment_terms']): ?>Payment: <?= htmlspecialchars($invoice['payment_terms']) ?><?php endif; ?>
            </div>
        </div>
        <table class="doc-lines">
            <thead><tr><th>Description</th><th>Qty</th><th>Unit price</th><th>Amount</th></tr></thead>
            <tbody>
                <?php foreach ($lines as $l): $amt = (float)$l['quantity'] * (float)$l['unit_price']; ?>
                <tr>
                    <td><?= htmlspecialchars($l['description']) ?></td>
                    <td><?= htmlspecialchars($l['quantity']) ?></td>
                    <td><?= number_format((float)$l['unit_price'], 2) ?> $</td>
                    <td><?= number_format($amt, 2) ?> $</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="doc-total">Total: <strong><?= number_format($total, 2) ?> $</strong></div>
        <?php if (!empty($invoice['notes'])): ?>
        <div class="doc-notes"><strong>Notes</strong><br><?= nl2br(htmlspecialchars($invoice['notes'])) ?></div>
        <?php endif; ?>
    </div>
<?php if (!$print): ?>
                </div>
            </main>
        </div>
    </div>
<?php endif; ?>
<?php if ($print): ?>
<script>window.onload = function() { window.print(); }</script>
<?php endif; ?>
</body>
</html>
