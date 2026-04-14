<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();

$pdo = db();
$invoices = $pdo->query("
    SELECT i.*, c.contact_name AS client_name, c.company AS client_company
    FROM invoices i
    LEFT JOIN clients c ON c.id = i.client_id
    ORDER BY i.created_at DESC
")->fetchAll();

$pageTitle = 'Invoices';
$currentNav = 'invoices';
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
                <p class="welcome"><?php if (admin_can_edit()): ?><a href="invoice-form.php" class="btn btn-primary">+ New invoice</a><?php endif; ?></p>
            </header>
            <main class="admin-main">
                <div class="admin-content">
                    <p class="page-subtitle">Create invoices, print or save as PDF, mark as paid.</p>
                    <div class="admin-content-card">
                        <div class="admin-content-card-body">
                            <?php if (empty($invoices)): ?>
                            <div class="empty-state">
                                <div class="empty-state-icon" aria-hidden="true">🧾</div>
                                <p>No invoices yet.</p>
                                <p><a href="invoice-form.php">Create an invoice</a> or convert an accepted quote.</p>
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
                                    <th>Due date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($invoices as $inv):
                                    $lines = $pdo->prepare("SELECT quantity, unit_price FROM invoice_lines WHERE invoice_id = ?");
                                    $lines->execute([$inv['id']]);
                                    $total = 0;
                                    while ($l = $lines->fetch()) $total += (float)$l['quantity'] * (float)$l['unit_price'];
                                ?>
                                <tr>
                                    <td><a href="invoice-view.php?id=<?= (int)$inv['id'] ?>"><?= htmlspecialchars($inv['invoice_number']) ?></a></td>
                                    <td><?= htmlspecialchars($inv['client_name'] ?? $inv['client_company'] ?? '—') ?></td>
                                    <td><?= number_format($total, 2) ?> $</td>
                                    <td><span class="badge badge-<?= htmlspecialchars($inv['status']) ?>"><?= htmlspecialchars($inv['status']) ?></span></td>
                                    <td><?= $inv['due_date'] ? date('Y-m-d', strtotime($inv['due_date'])) : '—' ?></td>
                                    <td class="actions">
                                        <a href="invoice-view.php?id=<?= (int)$inv['id'] ?>">View</a>
                                        <?php if (admin_can_edit()): ?>
                                        <a href="invoice-form.php?id=<?= (int)$inv['id'] ?>">Edit</a>
                                        <?php if ($inv['status'] !== 'paid'): ?>
                                        <a href="invoice-paid.php?id=<?= (int)$inv['id'] ?>">Mark paid</a>
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
