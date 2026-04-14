<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();

$pdo = db();
$quotes = $pdo->query("
    SELECT q.*, c.contact_name AS client_name, c.company AS client_company
    FROM quotes q
    LEFT JOIN clients c ON c.id = q.client_id
    ORDER BY q.created_at DESC
")->fetchAll();

$invoicesByQuote = [];
$invRows = $pdo->query("SELECT quote_id, id AS invoice_id, invoice_number FROM invoices WHERE quote_id IS NOT NULL");
while ($row = $invRows->fetch()) {
    $invoicesByQuote[$row['quote_id']] = $row;
}

$pageTitle = 'Quotes';
$currentNav = 'quotes';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> – <?= htmlspecialchars(SITE_NAME) ?> Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-dashboard">
    <div class="admin-layout">
        <?php include __DIR__ . '/includes/sidebar.php'; ?>
        <div class="admin-main-wrap">
            <header class="admin-topbar">
                <h1><?= htmlspecialchars($pageTitle) ?></h1>
                <p class="welcome"><?php if (admin_can_edit()): ?><a href="quote-form.php" class="btn btn-primary">+ New quote</a> <?php endif; ?></p>
            </header>
            <main class="admin-main">
                <div class="admin-content">
                    <p class="page-subtitle">Create and manage quotes. Print or convert to invoice when accepted.</p>
                    <div class="admin-content-card">
                        <div class="admin-content-card-body">
                            <?php if (empty($quotes)): ?>
                            <div class="empty-state">
                                <div class="empty-state-icon" aria-hidden="true">📄</div>
                                <p>No quotes yet.</p>
                                <p><a href="quote-form.php">Create a quote</a></p>
                            </div>
                            <?php else: ?>
                            <div class="table-wrap">
                                <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Number</th>
                                    <th>Client</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Valid until</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($quotes as $q):
                                    $lines = $pdo->prepare("SELECT quantity, unit_price FROM quote_lines WHERE quote_id = ?");
                                    $lines->execute([$q['id']]);
                                    $total = 0;
                                    while ($l = $lines->fetch()) $total += (float)$l['quantity'] * (float)$l['unit_price'];
                                ?>
                                <tr>
                                    <td><a href="quote-view.php?id=<?= (int)$q['id'] ?>"><?= htmlspecialchars($q['quote_number']) ?></a></td>
                                    <td><?= htmlspecialchars($q['client_name'] ?? $q['client_company'] ?? '—') ?></td>
                                    <td><?= number_format($total, 2) ?> $</td>
                                    <td><span class="badge badge-<?= htmlspecialchars($q['status']) ?>"><?= htmlspecialchars($q['status']) ?></span></td>
                                    <td><?= $q['valid_until'] ? date('Y-m-d', strtotime($q['valid_until'])) : '—' ?></td>
                                    <td class="actions">
                                        <a href="quote-view.php?id=<?= (int)$q['id'] ?>">View</a>
                                        <?php if (admin_can_edit()): ?>
                                        <a href="quote-form.php?id=<?= (int)$q['id'] ?>">Edit</a>
                                        <?php
                                        $invForQuote = $invoicesByQuote[$q['id']] ?? null;
                                        if ($invForQuote): ?>
                                        <a href="invoice-view.php?id=<?= (int)$invForQuote['invoice_id'] ?>">View invoice</a>
                                        <?php elseif (in_array($q['status'], ['accepted', 'sent'], true)): ?>
                                        <form method="post" action="quote-mark-paid.php" style="display:inline;"><input type="hidden" name="_csrf" value="<?= csrf_token() ?>"><input type="hidden" name="id" value="<?= (int)$q['id'] ?>"><button type="submit" class="btn-link">Mark as paid</button></form>
                                        <?php endif; ?>
                                        <?php if ($q['status'] === 'accepted' && !$invForQuote): ?>
                                        <a href="quote-convert.php?id=<?= (int)$q['id'] ?>">Convert to invoice</a>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
