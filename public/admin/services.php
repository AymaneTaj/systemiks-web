<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();
admin_require_role(['admin', 'editor']);

$pdo = db();
$services = $pdo->query("SELECT * FROM services ORDER BY sort_order, name")->fetchAll();

$pageTitle = 'Services';
$currentNav = 'services';
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
                <p class="welcome"><a href="service-form.php" class="btn btn-primary">+ Add service</a></p>
            </header>
            <main class="admin-main">
                <div class="admin-content">
                    <p class="page-subtitle">Catalog of services to add to quotes and invoices.</p>
                    <div class="admin-content-card">
                        <div class="admin-content-card-body">
                            <?php if (empty($services)): ?>
                            <div class="empty-state">
                                <div class="empty-state-icon" aria-hidden="true">📋</div>
                                <p>No services yet.</p>
                                <p><a href="service-form.php">Add a service</a></p>
                            </div>
                            <?php else: ?>
                            <div class="table-wrap">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Default price (CAD)</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($services as $s): ?>
                                        <tr>
                                            <td><a href="service-form.php?id=<?= (int)$s['id'] ?>"><?= htmlspecialchars($s['name']) ?></a></td>
                                            <td><?= htmlspecialchars(mb_substr($s['description'] ?? '', 0, 80)) ?><?= mb_strlen($s['description'] ?? '') > 80 ? '…' : '' ?></td>
                                            <td><?= number_format((float)$s['default_price'], 2) ?> $</td>
                                            <td class="actions">
                                                <a href="service-form.php?id=<?= (int)$s['id'] ?>">Edit</a>
                                                <a href="service-delete.php?id=<?= (int)$s['id'] ?>" class="danger" onclick="return confirm('Delete this service?');">Delete</a>
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
