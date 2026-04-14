<?php
/**
 * Client dashboard — accessible only with token when admin has enabled portal for this client.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
init_db();

$token = trim($_GET['token'] ?? '');
$client = null;
if ($token !== '') {
    $stmt = db()->prepare("SELECT id, contact_name, company, email FROM clients WHERE portal_token = ? AND portal_enabled = 1");
    $stmt->execute([$token]);
    $client = $stmt->fetch();
}

if (!$client) {
    header('HTTP/1.1 403 Forbidden');
    $pageTitle = 'Access denied';
} else {
    $cid = (int) $client['id'];
    $pdo = db();

    $projects = $pdo->prepare("SELECT id, name, status FROM projects WHERE client_id = ? ORDER BY status = 'done', name");
    $projects->execute([$cid]);
    $projects = $projects->fetchAll();

    $clientTasks = [];
    $pids = array_column($projects, 'id');
    if (!empty($pids)) {
        $placeholders = implode(',', array_fill(0, count($pids), '?'));
        $stmt = $pdo->prepare("
            SELECT t.id, t.title, t.due_date, t.description, p.name AS project_name, p.id AS project_id
            FROM project_tasks t
            JOIN projects p ON p.id = t.project_id
            WHERE t.project_id IN ($placeholders) AND t.client_action = 1 AND t.status != 'done'
            ORDER BY t.due_date IS NULL, t.due_date, t.id
        ");
        $stmt->execute($pids);
        $clientTasks = $stmt->fetchAll();
    }

    $quotes = $pdo->prepare("SELECT id, quote_number, status FROM quotes WHERE client_id = ? ORDER BY created_at DESC LIMIT 5");
    $quotes->execute([$cid]);
    $quotes = $quotes->fetchAll();
    $invoices = $pdo->prepare("SELECT id, invoice_number, status FROM invoices WHERE client_id = ? ORDER BY created_at DESC LIMIT 5");
    $invoices->execute([$cid]);
    $invoices = $invoices->fetchAll();

    $pageTitle = 'Dashboard';
}

function project_status_label(string $status): string {
    $labels = ['lead' => 'Lead', 'in_progress' => 'In progress', 'on_hold' => 'On hold', 'done' => 'Closed'];
    return $labels[$status] ?? $status;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> – <?= htmlspecialchars(SITE_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: "Inter", -apple-system, sans-serif; background: #f8fafc; color: #0f172a; min-height: 100vh; padding: 2rem; }
        .container { max-width: 720px; margin: 0 auto; }
        h1 { font-size: 1.5rem; margin: 0 0 0.5rem; }
        .sub { color: #64748b; font-size: 0.95rem; margin-bottom: 2rem; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem 1.5rem; margin-bottom: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
        .card h2 { font-size: 1.1rem; margin: 0 0 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0; }
        .card ul { list-style: none; margin: 0; padding: 0; }
        .card li { padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9; }
        .card li:last-child { border-bottom: none; }
        .badge { display: inline-block; padding: 0.15rem 0.45rem; border-radius: 6px; font-size: 0.75rem; font-weight: 500; margin-left: 0.5rem; }
        .badge-done { background: #f1f5f9; color: #64748b; }
        .badge-lead, .badge-in_progress, .badge-on_hold { background: #e0e7ff; color: #3730a3; }
        .task-due { font-size: 0.85rem; color: #64748b; }
        .denied { text-align: center; padding: 4rem 2rem; }
        .denied h1 { color: #b91c1c; }
    </style>
</head>
<body>
    <div class="container">
<?php if (!$client): ?>
        <div class="denied">
            <h1>Access denied</h1>
            <p>This link is invalid or access has not been enabled. Please contact us if you need access.</p>
        </div>
<?php else: ?>
        <h1>Welcome, <?= htmlspecialchars($client['contact_name']) ?></h1>
        <p class="sub"><?= htmlspecialchars(SITE_NAME) ?> – your dashboard</p>

        <?php if (!empty($clientTasks)): ?>
        <div class="card">
            <h2>Your to-do</h2>
            <p style="margin:0 0 0.75rem 0; font-size:0.9rem; color:#64748b;">Tasks for you to complete:</p>
            <ul>
                <?php foreach ($clientTasks as $t): ?>
                <li>
                    <strong><?= htmlspecialchars($t['title']) ?></strong>
                    <span class="badge"><?= htmlspecialchars($t['project_name']) ?></span>
                    <?php if (!empty($t['due_date'])): ?><span class="task-due">Due <?= htmlspecialchars($t['due_date']) ?></span><?php endif; ?>
                    <?php if (!empty($t['description'])): ?><br><span style="font-size:0.9rem; color:#64748b;"><?= nl2br(htmlspecialchars($t['description'])) ?></span><?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="card">
            <h2>Projects</h2>
            <?php if (empty($projects)): ?>
            <p style="margin:0; color:#64748b;">No projects yet.</p>
            <?php else: ?>
            <ul>
                <?php foreach ($projects as $p): ?>
                <li>
                    <?= htmlspecialchars($p['name']) ?>
                    <span class="badge badge-<?= htmlspecialchars($p['status']) ?>"><?= htmlspecialchars(project_status_label($p['status'])) ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>

        <?php if (!empty($quotes) || !empty($invoices)): ?>
        <div class="card">
            <h2>Quotes &amp; invoices</h2>
            <?php if (!empty($quotes)): ?>
            <p style="margin:0 0 0.5rem 0;"><strong>Quotes</strong></p>
            <ul>
                <?php foreach ($quotes as $q): ?>
                <li><?= htmlspecialchars($q['quote_number']) ?> – <?= htmlspecialchars($q['status']) ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <?php if (!empty($invoices)): ?>
            <p style="margin:0.75rem 0 0.5rem 0;"><strong>Invoices</strong></p>
            <ul>
                <?php foreach ($invoices as $inv): ?>
                <li><?= htmlspecialchars($inv['invoice_number']) ?> – <?= htmlspecialchars($inv['status']) ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <p style="margin-top:0.75rem; font-size:0.9rem; color:#64748b;">Contact us for details or to request documents.</p>
        </div>
        <?php endif; ?>
<?php endif; ?>
    </div>
</body>
</html>
