<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();

$pdo = db();
$stats = [
    'clients' => (int) $pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn(),
    'quotes'  => (int) $pdo->query("SELECT COUNT(*) FROM quotes")->fetchColumn(),
    'invoices'=> (int) $pdo->query("SELECT COUNT(*) FROM invoices")->fetchColumn(),
    'leads'   => (int) $pdo->query("SELECT COUNT(*) FROM leads")->fetchColumn(),
];

$myTasks = [];
if (!empty($_SESSION['admin_user_id'])) {
    $uid = (int) $_SESSION['admin_user_id'];
    $stmt = $pdo->prepare("
        SELECT t.id, t.title, t.due_date, t.status, t.priority, p.id AS project_id, p.name AS project_name
        FROM project_tasks t
        JOIN projects p ON p.id = t.project_id
        WHERE t.assigned_to = ? AND t.status != 'done'
        ORDER BY t.due_date IS NULL, t.due_date, t.id
        LIMIT 15
    ");
    $stmt->execute([$uid]);
    $myTasks = $stmt->fetchAll();
}

$pageTitle = 'Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> – <?= htmlspecialchars(SITE_NAME) ?> Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-dashboard">
    <div class="admin-layout">
        <?php $currentNav = 'dashboard'; include __DIR__ . '/includes/sidebar.php'; ?>
        <div class="admin-main-wrap">
            <header class="admin-topbar">
                <h1>Dashboard</h1>
                <p class="welcome">Welcome, <strong><?= htmlspecialchars($_SESSION['admin_username'] ?? '') ?></strong></p>
            </header>
            <main class="admin-main">
                <div class="admin-content">
                    <p class="page-subtitle">Overview and quick access.</p>

                    <div class="dashboard-stats">
                        <a href="/admin/clients.php" class="card card-stat">
                            <div class="card-stat-value"><?= $stats['clients'] ?></div>
                            <div class="card-stat-label">Clients</div>
                        </a>
                        <a href="/admin/quotes.php" class="card card-stat">
                            <div class="card-stat-value"><?= $stats['quotes'] ?></div>
                            <div class="card-stat-label">Quotes</div>
                        </a>
                        <a href="/admin/invoices.php" class="card card-stat">
                            <div class="card-stat-value"><?= $stats['invoices'] ?></div>
                            <div class="card-stat-label">Invoices</div>
                        </a>
                        <a href="/admin/leads.php" class="card card-stat">
                            <div class="card-stat-value"><?= $stats['leads'] ?></div>
                            <div class="card-stat-label">Leads</div>
                        </a>
                    </div>

                    <?php if (!empty($myTasks)): ?>
                    <div class="admin-content-card dashboard-my-tasks">
                        <div class="admin-content-card-body">
                            <h3 class="card-title">My tasks</h3>
                            <p class="muted">Tasks assigned to you (not done).</p>
                            <ul class="dashboard-task-list">
                                <?php foreach ($myTasks as $t): ?>
                                <li>
                                    <a href="project-view.php?id=<?= (int)$t['project_id'] ?>&edit_task=<?= (int)$t['id'] ?>"><?= htmlspecialchars($t['title']) ?></a>
                                    <span class="task-project"><?= htmlspecialchars($t['project_name']) ?></span>
                                    <?php if (!empty($t['due_date'])): ?>
                                    <span class="task-due <?= (strtotime($t['due_date']) < strtotime('today')) ? 'overdue' : '' ?>">Due <?= htmlspecialchars($t['due_date']) ?></span>
                                    <?php endif; ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <p><a href="/admin/projects.php">View all projects</a></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="dashboard-cards">
                        <div class="card">
                            <h3><svg class="card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg> Site</h3>
                            <p><a href="/" target="_blank">View site</a> — Homepage, Services, Contact, Web design &amp; development.</p>
                        </div>
                        <div class="card">
                            <h3><svg class="card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg> Pages</h3>
                            <p>Quick links to main pages.</p>
                            <div class="card-links">
                                <a href="/" target="_blank">Home</a>
                                <a href="/services.php" target="_blank">Services</a>
                                <a href="/contact.php" target="_blank">Book a call</a>
                                <a href="/web-design-development.php" target="_blank">Web design</a>
                            </div>
                        </div>
                        <div class="card">
                            <h3><svg class="card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Scheduling &amp; contact</h3>
                            <p>Contact page includes a calendar and quote request form. Submissions appear under <a href="/admin/leads.php">Leads</a>.</p>
                        </div>
                        <div class="card">
                            <h3><svg class="card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg> Settings</h3>
                            <p>Company details and quote/invoice defaults in <a href="/admin/settings.php">Settings</a>. Config: <code>config/config.php</code>. Change admin password in <code>config/auth.php</code>.</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
