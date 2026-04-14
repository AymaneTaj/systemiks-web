<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$client = null;
if ($id) {
    $stmt = db()->prepare("SELECT * FROM clients WHERE id = ?");
    $stmt->execute([$id]);
    $client = $stmt->fetch();
}
if (!$client) { header('Location: clients.php'); exit; }

$pdo = db();
$projects = $pdo->prepare("SELECT * FROM projects WHERE client_id = ? ORDER BY status <> 'done', started_at DESC, name");
$projects->execute([$id]);
$projects = $projects->fetchAll();

$openProjectIds = array_filter(array_map(function ($p) { return ($p['status'] ?? '') !== 'done' ? (int) $p['id'] : null; }, $projects));
$openProjectIds = array_values($openProjectIds);

$clientTasks = [];
if (!empty($openProjectIds)) {
    $placeholders = implode(',', array_fill(0, count($openProjectIds), '?'));
    $stmt = $pdo->prepare("
        SELECT t.*, p.name AS project_name, p.id AS project_id, u.username AS assigned_name
        FROM project_tasks t
        JOIN projects p ON p.id = t.project_id
        LEFT JOIN admin_users u ON t.assigned_to = u.id
        WHERE t.project_id IN ($placeholders) AND t.status != 'done'
        ORDER BY t.due_date IS NULL, t.due_date, t.priority = 'high' DESC, t.id
    ");
    $stmt->execute($openProjectIds);
    $clientTasks = $stmt->fetchAll();
}

$quotes = $pdo->prepare("SELECT id, quote_number, status FROM quotes WHERE client_id = ? ORDER BY created_at DESC LIMIT 10");
$quotes->execute([$id]);
$quotes = $quotes->fetchAll();
$invoices = $pdo->prepare("SELECT id, invoice_number, status FROM invoices WHERE client_id = ? ORDER BY created_at DESC LIMIT 10");
$invoices->execute([$id]);
$invoices = $invoices->fetchAll();

$pageTitle = $client['contact_name'];
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
                <h1><?= htmlspecialchars($client['contact_name']) ?></h1>
                <p class="welcome">
                    <?php if (admin_can_edit()): ?><a href="client-form.php?id=<?= $id ?>" class="btn btn-primary">Edit client</a><?php endif; ?>
                    <a href="clients.php">← Clients</a>
                </p>
            </header>
            <main class="admin-main">
                <div class="admin-content">
                    <div class="admin-content-card">
                        <div class="admin-content-card-body">
                            <?php if (!empty($client['company'])): ?><p><strong>Company</strong> <?= htmlspecialchars($client['company']) ?></p><?php endif; ?>
                            <p><strong>Email</strong> <a href="mailto:<?= htmlspecialchars($client['email']) ?>"><?= htmlspecialchars($client['email']) ?></a></p>
                            <?php if (!empty($client['phone'])): ?><p><strong>Phone</strong> <a href="tel:<?= htmlspecialchars($client['phone']) ?>"><?= htmlspecialchars($client['phone']) ?></a></p><?php endif; ?>
                            <?php if (!empty($client['address'])): ?><p><strong>Address</strong><br><?= nl2br(htmlspecialchars($client['address'])) ?></p><?php endif; ?>
                            <?php if (!empty($client['notes'])): ?><p><strong>Notes</strong><br><?= nl2br(htmlspecialchars($client['notes'])) ?></p><?php endif; ?>
                            <?php if (function_exists('admin_can_edit_settings') && admin_can_edit_settings()): ?>
                            <p><strong>Client dashboard</strong> <?= !empty($client['portal_enabled']) && !empty($client['portal_token']) ? 'Enabled' : 'Not enabled' ?></p>
                            <?php if (!empty($client['portal_enabled']) && !empty($client['portal_token'])): 
                                $portalUrl = rtrim(defined('SITE_URL') ? SITE_URL : ('http' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')), '/') . '/client-dashboard.php?token=' . urlencode($client['portal_token']);
                            ?>
                            <div class="portal-link-wrap" style="margin-top:0.5rem;">
                                <input type="text" id="client-view-portal-link" readonly value="<?= htmlspecialchars($portalUrl) ?>" class="portal-link-input">
                                <button type="button" class="btn btn-secondary btn-sm" id="client-view-copy-btn" onclick="var el=document.getElementById('client-view-portal-link'); navigator.clipboard.writeText(el.value); var b=document.getElementById('client-view-copy-btn'); b.textContent='Copied!'; setTimeout(function(){ b.textContent='Copy'; }, 2000);">Copy</button>
                            </div>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="admin-content-card">
                        <div class="admin-content-card-body">
                            <h3 class="card-title">Projects</h3>
                            <?php if (empty($projects)): ?>
                            <p class="muted">No projects for this client.</p>
                            <?php else: ?>
                            <ul class="client-project-list">
                                <?php foreach ($projects as $p): $isOpen = ($p['status'] ?? '') !== 'done'; ?>
                                <li>
                                    <a href="project-view.php?id=<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['name']) ?></a>
                                    <span class="badge badge-<?= htmlspecialchars($p['status']) ?>"><?= htmlspecialchars(project_status_label($p['status'] ?? '')) ?></span>
                                    <?php if ($isOpen): ?><span class="muted">(open)</span><?php endif; ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($openProjectIds)): ?>
                    <div class="admin-content-card" id="todo">
                        <div class="admin-content-card-body">
                            <h3 class="card-title">Todo list (open projects)</h3>
                            <p class="muted">All tasks from projects that are not done. Click a task’s project to open it.</p>
                            <?php if (empty($clientTasks)): ?>
                            <p class="muted">No open tasks.</p>
                            <?php else: ?>
                            <ul class="project-task-list client-todo-list">
                                <?php foreach ($clientTasks as $t): ?>
                                <li class="project-task-item">
                                    <span class="task-title"><?= htmlspecialchars($t['title']) ?></span>
                                    <span class="task-meta">
                                        <a href="project-view.php?id=<?= (int)$t['project_id'] ?>"><?= htmlspecialchars($t['project_name']) ?></a>
                                        <?= $t['due_date'] ? ' · Due ' . htmlspecialchars($t['due_date']) : '' ?>
                                        <?= $t['priority'] ? ' · ' . htmlspecialchars($t['priority']) : '' ?>
                                        <?= $t['assigned_name'] ? ' · ' . htmlspecialchars($t['assigned_name']) : '' ?>
                                    </span>
                                    <?php if (admin_can_edit()): ?>
                                    <span class="task-actions">
                                        <a href="project-view.php?id=<?= (int)$t['project_id'] ?>&edit_task=<?= (int)$t['id'] ?>">Edit</a>
                                    </span>
                                    <?php endif; ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($quotes) || !empty($invoices)): ?>
                    <div class="admin-content-card">
                        <div class="admin-content-card-body">
                            <h3 class="card-title">Recent quotes &amp; invoices</h3>
                            <?php if (!empty($quotes)): ?>
                            <p><strong>Quotes</strong> <?php foreach ($quotes as $q): ?>
                                <a href="quote-view.php?id=<?= (int)$q['id'] ?>"><?= htmlspecialchars($q['quote_number']) ?></a> <span class="badge badge-<?= $q['status'] ?>"><?= $q['status'] ?></span><?= $q !== end($quotes) ? ', ' : '' ?>
                            <?php endforeach; ?></p>
                            <?php endif; ?>
                            <?php if (!empty($invoices)): ?>
                            <p><strong>Invoices</strong> <?php foreach ($invoices as $inv): ?>
                                <a href="invoice-view.php?id=<?= (int)$inv['id'] ?>"><?= htmlspecialchars($inv['invoice_number']) ?></a> <span class="badge badge-<?= $inv['status'] ?>"><?= $inv['status'] ?></span><?= $inv !== end($invoices) ? ', ' : '' ?>
                            <?php endforeach; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
