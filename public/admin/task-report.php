<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();

$pdo = db();
$projectId = isset($_GET['project_id']) && $_GET['project_id'] !== '' ? (int) $_GET['project_id'] : null;
$clientId = isset($_GET['client_id']) && $_GET['client_id'] !== '' ? (int) $_GET['client_id'] : null;
$assigneeId = isset($_GET['assignee']) && $_GET['assignee'] !== '' ? (int) $_GET['assignee'] : null;
$dueFrom = isset($_GET['due_from']) && $_GET['due_from'] !== '' ? trim($_GET['due_from']) : null;
$dueTo = isset($_GET['due_to']) && $_GET['due_to'] !== '' ? trim($_GET['due_to']) : null;
$statusFilter = isset($_GET['status']) && $_GET['status'] !== '' ? trim($_GET['status']) : null;
$exportCsv = isset($_GET['export']) && $_GET['export'] === 'csv';

$sql = "SELECT t.id, t.title, t.status, t.due_date, t.priority, t.client_action, t.completed_at,
        p.name AS project_name, p.id AS project_id, p.client_id, c.contact_name AS client_name, c.company AS client_company,
        u.username AS assigned_name
        FROM project_tasks t
        JOIN projects p ON p.id = t.project_id
        LEFT JOIN clients c ON c.id = p.client_id
        LEFT JOIN admin_users u ON u.id = t.assigned_to
        WHERE 1=1";
$params = [];
if ($projectId !== null) { $sql .= " AND t.project_id = ?"; $params[] = $projectId; }
if ($clientId !== null) { $sql .= " AND p.client_id = ?"; $params[] = $clientId; }
if ($assigneeId !== null) { $sql .= " AND t.assigned_to = ?"; $params[] = $assigneeId; }
if ($dueFrom !== null) { $sql .= " AND t.due_date >= ?"; $params[] = $dueFrom; }
if ($dueTo !== null) { $sql .= " AND t.due_date <= ?"; $params[] = $dueTo; }
if ($statusFilter !== null && in_array($statusFilter, ['todo', 'in_progress', 'done'], true)) { $sql .= " AND t.status = ?"; $params[] = $statusFilter; }
$sql .= " ORDER BY t.due_date IS NULL, t.due_date, p.name, t.id";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

$projects = $pdo->query("SELECT id, name FROM projects ORDER BY name")->fetchAll();
$clients = $pdo->query("SELECT id, contact_name, company FROM clients ORDER BY contact_name")->fetchAll();
$adminUsers = $pdo->query("SELECT id, username FROM admin_users ORDER BY username")->fetchAll();

if ($exportCsv) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="tasks-report-' . date('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Task', 'Project', 'Client', 'Status', 'Due date', 'Priority', 'Assigned to', 'Customer to do', 'Completed at']);
    foreach ($rows as $r) {
        fputcsv($out, [
            $r['title'],
            $r['project_name'],
            $r['client_name'] . ($r['client_company'] ? ' (' . $r['client_company'] . ')' : ''),
            $r['status'],
            $r['due_date'] ?? '',
            $r['priority'] ?? '',
            $r['assigned_name'] ?? '',
            !empty($r['client_action']) ? 'Yes' : 'No',
            $r['completed_at'] ?? '',
        ]);
    }
    fclose($out);
    exit;
}

$pageTitle = 'Task report';
$currentNav = 'reports';
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
                <p class="welcome">
                    <?php
                    $exportUrl = 'task-report.php?' . http_build_query(array_merge($_GET, ['export' => 'csv']));
                    ?>
                    <a href="<?= htmlspecialchars($exportUrl) ?>" class="btn btn-primary">Export CSV</a>
                </p>
            </header>
            <main class="admin-main">
                <div class="admin-content">
                    <form method="get" class="task-filters task-report-filters" action="task-report.php">
                        <span class="filter-group">
                            <label>Project</label>
                            <select name="project_id" onchange="this.form.submit()">
                                <option value="">All</option>
                                <?php foreach ($projects as $pr): ?>
                                <option value="<?= (int)$pr['id'] ?>" <?= $projectId === (int)$pr['id'] ? 'selected' : '' ?>><?= htmlspecialchars($pr['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </span>
                        <span class="filter-group">
                            <label>Client</label>
                            <select name="client_id" onchange="this.form.submit()">
                                <option value="">All</option>
                                <?php foreach ($clients as $cl): ?>
                                <option value="<?= (int)$cl['id'] ?>" <?= $clientId === (int)$cl['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cl['contact_name'] . ($cl['company'] ? ' (' . $cl['company'] . ')' : '')) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </span>
                        <span class="filter-group">
                            <label>Assignee</label>
                            <select name="assignee" onchange="this.form.submit()">
                                <option value="">All</option>
                                <?php foreach ($adminUsers as $u): ?>
                                <option value="<?= (int)$u['id'] ?>" <?= $assigneeId === (int)$u['id'] ? 'selected' : '' ?>><?= htmlspecialchars($u['username']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </span>
                        <span class="filter-group">
                            <label>Status</label>
                            <select name="status" onchange="this.form.submit()">
                                <option value="">All</option>
                                <option value="todo" <?= $statusFilter === 'todo' ? 'selected' : '' ?>>To do</option>
                                <option value="in_progress" <?= $statusFilter === 'in_progress' ? 'selected' : '' ?>>In progress</option>
                                <option value="done" <?= $statusFilter === 'done' ? 'selected' : '' ?>>Done</option>
                            </select>
                        </span>
                        <span class="filter-group">
                            <label>Due from</label>
                            <input type="date" name="due_from" value="<?= htmlspecialchars($dueFrom ?? '') ?>" onchange="this.form.submit()">
                        </span>
                        <span class="filter-group">
                            <label>Due to</label>
                            <input type="date" name="due_to" value="<?= htmlspecialchars($dueTo ?? '') ?>" onchange="this.form.submit()">
                        </span>
                        <?php if ($projectId !== null || $clientId !== null || $assigneeId !== null || $dueFrom !== null || $dueTo !== null || $statusFilter !== null): ?>
                        <a href="task-report.php" class="filter-clear">Clear filters</a>
                        <?php endif; ?>
                    </form>

                    <div class="admin-content-card">
                        <div class="admin-content-card-body">
                            <p class="muted"><?= count($rows) ?> task(s)</p>
                            <?php if (empty($rows)): ?>
                            <div class="empty-state">
                                <p>No tasks match the filters.</p>
                            </div>
                            <?php else: ?>
                            <div class="table-wrap">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>Task</th>
                                            <th>Project</th>
                                            <th>Client</th>
                                            <th>Status</th>
                                            <th>Due</th>
                                            <th>Priority</th>
                                            <th>Assigned to</th>
                                            <th>Customer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rows as $r): ?>
                                        <tr>
                                            <td><a href="project-view.php?id=<?= (int)$r['project_id'] ?>&edit_task=<?= (int)$r['id'] ?>"><?= htmlspecialchars($r['title']) ?></a></td>
                                            <td><a href="project-view.php?id=<?= (int)$r['project_id'] ?>"><?= htmlspecialchars($r['project_name']) ?></a></td>
                                            <td><?= $r['client_id'] ? '<a href="client-view.php?id=' . (int)$r['client_id'] . '">' . htmlspecialchars($r['client_name'] ?? '') . '</a>' : '—' ?></td>
                                            <td><span class="badge badge-<?= htmlspecialchars($r['status']) ?>"><?= htmlspecialchars($r['status']) ?></span></td>
                                            <td><?= htmlspecialchars($r['due_date'] ?? '—') ?></td>
                                            <td><?= htmlspecialchars($r['priority'] ?? '—') ?></td>
                                            <td><?= htmlspecialchars($r['assigned_name'] ?? '—') ?></td>
                                            <td><?= !empty($r['client_action']) ? 'Yes' : '—' ?></td>
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
