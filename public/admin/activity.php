<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();

$pdo = db();
$filter = isset($_GET['entity']) && $_GET['entity'] !== '' ? trim($_GET['entity']) : null;

$sql = "SELECT a.*, u.username FROM activity_log a LEFT JOIN admin_users u ON a.admin_user_id = u.id WHERE 1=1";
$params = [];
if ($filter) {
    $sql .= " AND a.entity_type = ?";
    $params[] = $filter;
}
$sql .= " ORDER BY a.created_at DESC LIMIT 500";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$entries = $stmt->fetchAll();

$pageTitle = 'Activity log';
$currentNav = 'activity';
$entityTypes = ['client', 'project', 'project_task', 'quote', 'invoice', 'lead'];
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
            </header>
            <main class="admin-main">
                <div class="admin-content">
                    <form method="get" class="filter-form" style="margin-bottom:1rem;">
                        <label>Filter by type:</label>
                        <select name="entity" onchange="this.form.submit()">
                            <option value="">All</option>
                            <?php foreach ($entityTypes as $t): ?>
                            <option value="<?= htmlspecialchars($t) ?>" <?= $filter === $t ? 'selected' : '' ?>><?= ucfirst($t) ?>s</option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                    <div class="admin-content-card">
                        <div class="admin-content-card-body">
                            <?php if (empty($entries)): ?>
                            <div class="empty-state">
                                <p>No activity yet.</p>
                            </div>
                            <?php else: ?>
                            <div class="table-wrap">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>When</th>
                                            <th>Action</th>
                                            <th>Entity</th>
                                            <th>Details</th>
                                            <th>User</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($entries as $e): ?>
                                        <tr>
                                            <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($e['created_at']))) ?></td>
                                            <td><?= htmlspecialchars($e['action']) ?></td>
                                            <td><?= htmlspecialchars($e['entity_type']) ?><?= $e['entity_id'] ? ' #' . (int)$e['entity_id'] : '' ?></td>
                                            <td><?= $e['details'] ? htmlspecialchars(mb_substr($e['details'], 0, 120)) . (mb_strlen($e['details']) > 120 ? '…' : '') : '—' ?></td>
                                            <td><?= htmlspecialchars($e['username'] ?? '—') ?></td>
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
