<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();

$pdo = db();
$leads = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC")->fetchAll();

$pageTitle = 'Leads';
$currentNav = 'leads';
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
                <p class="welcome">Contact form submissions and leads.</p>
            </header>
            <main class="admin-main">
                <div class="admin-content">
                    <p class="page-subtitle">View leads and convert to client + quote.</p>
                    <div class="admin-content-card">
                        <div class="admin-content-card-body">
                            <?php if (empty($leads)): ?>
                            <div class="empty-state">
                                <div class="empty-state-icon" aria-hidden="true">💬</div>
                                <p>No leads yet.</p>
                                <p>Submissions from the contact form will appear here.</p>
                            </div>
                            <?php else: ?>
                            <div class="table-wrap">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Company</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($leads as $l): ?>
                                        <tr>
                                            <td><?= date('Y-m-d H:i', strtotime($l['created_at'])) ?></td>
                                            <td><?= htmlspecialchars($l['name']) ?></td>
                                            <td><?= htmlspecialchars($l['email']) ?></td>
                                            <td><?= htmlspecialchars($l['company'] ?? '') ?></td>
                                            <td><span class="badge badge-<?= htmlspecialchars($l['status']) ?>"><?= htmlspecialchars($l['status']) ?></span></td>
                                            <td class="actions">
                                                <a href="lead-view.php?id=<?= (int)$l['id'] ?>">View</a>
                                                <?php if (admin_can_edit()): ?>
                                                <a href="lead-convert.php?id=<?= (int)$l['id'] ?>">Convert to quote</a>
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
