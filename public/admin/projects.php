<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();

$pdo = db();
$projects = $pdo->query("
    SELECT p.*, c.contact_name AS client_name, c.company AS client_company
    FROM projects p
    LEFT JOIN clients c ON c.id = p.client_id
    ORDER BY p.created_at DESC
")->fetchAll();

$pageTitle = 'Projects';
$currentNav = 'projects';
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
                <p class="welcome"><?php if (admin_can_edit()): ?><a href="project-form.php" class="btn btn-primary">+ Add project</a><?php endif; ?></p>
            </header>
            <main class="admin-main">
                <div class="admin-content">
                    <p class="page-subtitle">Group work by project and link quotes/invoices.</p>
                    <div class="admin-content-card">
                        <div class="admin-content-card-body">
                            <?php if (empty($projects)): ?>
                            <div class="empty-state">
                                <div class="empty-state-icon" aria-hidden="true">📁</div>
                                <p>No projects yet.</p>
                                <p><a href="project-form.php">Add a project</a></p>
                            </div>
                            <?php else: ?>
                            <div class="table-wrap">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>Project</th>
                                            <th>Client</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($projects as $p): ?>
                                        <tr>
                                            <td><a href="project-view.php?id=<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['name']) ?></a></td>
                                            <td><?= htmlspecialchars($p['client_name'] ?? $p['client_company'] ?? '—') ?></td>
                                            <td><span class="badge badge-<?= htmlspecialchars($p['status']) ?>"><?= htmlspecialchars(project_status_label($p['status'] ?? '')) ?></span></td>
                                            <td class="actions">
                                                <a href="project-view.php?id=<?= (int)$p['id'] ?>">View</a>
                                                <a href="project-form.php?id=<?= (int)$p['id'] ?>">Edit</a>
                                                <?php if (admin_can_edit()): ?>
                                                <a href="project-delete.php?id=<?= (int)$p['id'] ?>" class="danger" onclick="return confirm('Delete this project?');">Delete</a>
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
