<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();

$pdo = db();
$clients = $pdo->query("
    SELECT c.*,
           (SELECT COUNT(*) FROM project_tasks t
            JOIN projects p ON p.id = t.project_id
            WHERE p.client_id = c.id AND p.status != 'done' AND t.status != 'done') AS open_tasks
    FROM clients c
    ORDER BY c.contact_name
")->fetchAll();

$pageTitle = 'Clients';
$currentNav = 'clients';
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
                <p class="welcome"><?php if (admin_can_edit()): ?><a href="client-form.php" class="btn btn-primary">+ Add client</a> <?php endif; ?></p>
            </header>
            <main class="admin-main">
                <div class="admin-content">
                    <p class="page-subtitle">Manage clients for quotes and invoices.</p>
                    <div class="admin-content-card">
                        <div class="admin-content-card-body">
                            <?php if (empty($clients)): ?>
                            <div class="empty-state">
                                <div class="empty-state-icon" aria-hidden="true">👤</div>
                                <p>No clients yet.</p>
                                <p><a href="client-form.php">Add your first client</a></p>
                            </div>
                            <?php else: ?>
                            <div class="table-wrap">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Company</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th class="col-tasks">Open tasks</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($clients as $c): ?>
                                        <tr>
                                            <td><a href="client-view.php?id=<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['contact_name']) ?></a></td>
                                            <td><?= htmlspecialchars($c['company'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($c['email']) ?></td>
                                            <td><?= htmlspecialchars($c['phone'] ?? '') ?></td>
                                            <td class="col-tasks"><?php $n = (int)($c['open_tasks'] ?? 0); if ($n > 0): ?><a href="client-view.php?id=<?= (int)$c['id'] ?>#todo" class="badge badge-tasks"><?= $n ?></a><?php else: ?>—<?php endif; ?></td>
                                            <td class="actions">
                                                <a href="client-view.php?id=<?= (int)$c['id'] ?>">View</a>
                                                <a href="client-form.php?id=<?= (int)$c['id'] ?>">Edit</a>
                                                <?php if (admin_can_edit()): ?>
                                                <a href="client-delete.php?id=<?= (int)$c['id'] ?>" class="danger" onclick="return confirm('Delete this client?');">Delete</a>
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
