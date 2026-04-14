<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$project = null;
$client = null;
$tasks = [];
$assigneeFilter = null;
$dueFrom = null;
$dueTo = null;
$priorityFilter = null;
if ($id) {
    $stmt = db()->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $project = $stmt->fetch();
    if (!$project) { header('Location: projects.php'); exit; }
    if (!empty($project['client_id'])) {
        $c = db()->prepare("SELECT id, contact_name, company, email FROM clients WHERE id = ?");
        $c->execute([$project['client_id']]);
        $client = $c->fetch();
    }
    $assigneeFilter = isset($_GET['assignee']) && $_GET['assignee'] !== '' ? (int) $_GET['assignee'] : null;
    $dueFrom = isset($_GET['due_from']) && $_GET['due_from'] !== '' ? trim($_GET['due_from']) : null;
    $dueTo = isset($_GET['due_to']) && $_GET['due_to'] !== '' ? trim($_GET['due_to']) : null;
    $priorityFilter = isset($_GET['priority']) && $_GET['priority'] !== '' ? trim($_GET['priority']) : null;
    $sql = "SELECT t.*, u.username AS assigned_name FROM project_tasks t LEFT JOIN admin_users u ON t.assigned_to = u.id WHERE t.project_id = ?";
    $params = [$id];
    if ($assigneeFilter !== null) { $sql .= " AND t.assigned_to = ?"; $params[] = $assigneeFilter; }
    if ($dueFrom !== null) { $sql .= " AND t.due_date >= ?"; $params[] = $dueFrom; }
    if ($dueTo !== null) { $sql .= " AND t.due_date <= ?"; $params[] = $dueTo; }
    if ($priorityFilter !== null && in_array($priorityFilter, ['low', 'medium', 'high'], true)) { $sql .= " AND t.priority = ?"; $params[] = $priorityFilter; }
    $sql .= " ORDER BY t.status = 'done', t.sort_order, t.due_date IS NULL, t.due_date, t.id";
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $tasks = $stmt->fetchAll();
    $taskIds = array_column($tasks, 'id');
    $itemsByTask = [];
    if (!empty($taskIds)) {
        $placeholders = implode(',', array_fill(0, count($taskIds), '?'));
        $stItems = db()->prepare("SELECT * FROM project_task_items WHERE project_task_id IN ($placeholders) ORDER BY sort_order, id");
        $stItems->execute($taskIds);
        while ($row = $stItems->fetch(PDO::FETCH_ASSOC)) {
            $tid = (int) $row['project_task_id'];
            if (!isset($itemsByTask[$tid])) $itemsByTask[$tid] = [];
            $itemsByTask[$tid][] = $row;
        }
    }
    foreach ($tasks as &$t) { $t['items'] = $itemsByTask[$t['id']] ?? []; }
    unset($t);
}

if (!$project) { header('Location: projects.php'); exit; }

$pdo = db();
$quotes = $pdo->prepare("SELECT id, quote_number, status FROM quotes WHERE project_id = ? ORDER BY created_at DESC");
$quotes->execute([$id]);
$quotes = $quotes->fetchAll();
$invoices = $pdo->prepare("SELECT id, invoice_number, status FROM invoices WHERE project_id = ? ORDER BY created_at DESC");
$invoices->execute([$id]);
$invoices = $invoices->fetchAll();

$adminUsers = $pdo->query("SELECT id, username FROM admin_users ORDER BY username")->fetchAll();

$errors = [];
$taskIdEdit = isset($_GET['edit_task']) ? (int) $_GET['edit_task'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_can_edit()) {
    csrf_validate();
    if (isset($_POST['add_task'])) {
        $title = trim($_POST['task_title'] ?? '');
        if ($title !== '') {
            $desc = trim($_POST['task_description'] ?? '');
            $status = in_array($_POST['task_status'] ?? '', ['todo', 'in_progress', 'done'], true) ? $_POST['task_status'] : 'todo';
            $due = !empty($_POST['task_due_date']) ? trim($_POST['task_due_date']) : null;
            $priority = in_array($_POST['task_priority'] ?? '', ['low', 'medium', 'high'], true) ? $_POST['task_priority'] : null;
            $assignedTo = !empty($_POST['task_assigned_to']) ? (int) $_POST['task_assigned_to'] : null;
            $clientAction = isset($_POST['task_client_action']) && $_POST['task_client_action'] === '1' ? 1 : 0;
            $maxOrder = $pdo->prepare("SELECT COALESCE(MAX(sort_order), -1) + 1 FROM project_tasks WHERE project_id = ?");
            $maxOrder->execute([$id]);
            $sortOrder = (int) $maxOrder->fetchColumn();
            $pdo->prepare("INSERT INTO project_tasks (project_id, title, description, status, sort_order, due_date, priority, assigned_to, completed_at, client_action) VALUES (?,?,?,?,?,?,?,?,?,?)")
                ->execute([$id, $title, $desc, $status, $sortOrder, $due, $priority, $assignedTo, $status === 'done' ? date('Y-m-d H:i:s') : null, $clientAction]);
            $tid = (int) $pdo->lastInsertId();
            log_activity('created', 'project_task', $tid, $title);
            $viewParam = isset($_POST['view']) && $_POST['view'] === 'board' ? '&view=board' : '';
            header('Location: project-view.php?id=' . $id . $viewParam);
            exit;
        }
        $errors[] = 'Task title is required.';
    }
    if (isset($_POST['edit_task'])) {
        $taskId = (int) ($_POST['task_id'] ?? 0);
        $title = trim($_POST['task_title'] ?? '');
        if ($taskId && $title !== '') {
            $stmt = $pdo->prepare("SELECT id FROM project_tasks WHERE project_id = ? AND id = ?");
            $stmt->execute([$id, $taskId]);
            if ($stmt->fetch()) {
                $desc = trim($_POST['task_description'] ?? '');
                $status = in_array($_POST['task_status'] ?? '', ['todo', 'in_progress', 'done'], true) ? $_POST['task_status'] : 'todo';
                $due = !empty($_POST['task_due_date']) ? trim($_POST['task_due_date']) : null;
                $priority = in_array($_POST['task_priority'] ?? '', ['low', 'medium', 'high'], true) ? $_POST['task_priority'] : null;
                $assignedTo = !empty($_POST['task_assigned_to']) ? (int) $_POST['task_assigned_to'] : null;
                $completedAt = $status === 'done' ? date('Y-m-d H:i:s') : null;
                $clientAction = isset($_POST['task_client_action']) && $_POST['task_client_action'] === '1' ? 1 : 0;
                $pdo->prepare("UPDATE project_tasks SET title=?, description=?, status=?, due_date=?, priority=?, assigned_to=?, completed_at=?, client_action=?, updated_at=CURRENT_TIMESTAMP WHERE id=?")
                    ->execute([$title, $desc, $status, $due, $priority, $assignedTo, $completedAt, $clientAction, $taskId]);
                $subtaskTitles = $_POST['subtask_title'] ?? [];
                $subtaskDone = $_POST['subtask_done'] ?? [];
                $pdo->prepare("DELETE FROM project_task_items WHERE project_task_id = ?")->execute([$taskId]);
                $insItem = $pdo->prepare("INSERT INTO project_task_items (project_task_id, title, is_done, sort_order) VALUES (?,?,?,?)");
                foreach ($subtaskTitles as $idx => $stTitle) {
                    $stTitle = trim($stTitle);
                    if ($stTitle === '') continue;
                    $isDone = isset($subtaskDone[$idx]) && $subtaskDone[$idx] === '1' ? 1 : 0;
                    $insItem->execute([$taskId, $stTitle, $isDone, $idx]);
                }
                log_activity('updated', 'project_task', $taskId, $title);
                $viewParam = isset($_POST['view']) && $_POST['view'] === 'board' ? '&view=board' : '';
                header('Location: project-view.php?id=' . $id . $viewParam);
                exit;
            }
        }
        if ($taskId && $title === '') $errors[] = 'Task title is required.';
    }
}

$viewMode = isset($_GET['view']) && $_GET['view'] === 'board' ? 'board' : 'list';
$tasksByStatus = ['todo' => [], 'in_progress' => [], 'done' => []];
foreach ($tasks as $t) {
    $s = $t['status'] ?? 'todo';
    if (!isset($tasksByStatus[$s])) $tasksByStatus[$s] = [];
    $tasksByStatus[$s][] = $t;
}
$taskCountTodo = count($tasksByStatus['todo']);
$taskCountInProgress = count($tasksByStatus['in_progress']);
$taskCountDone = count($tasksByStatus['done']);
$taskTotal = count($tasks);
$taskProgressPct = $taskTotal > 0 ? round((count($tasksByStatus['done']) / $taskTotal) * 100) : 0;

$pageTitle = $project['name'];
$currentNav = 'projects';
$viewQuery = $viewMode === 'board' ? '&view=board' : '';
$filterQuery = http_build_query(array_filter([
    'assignee' => $assigneeFilter !== null ? (string)$assigneeFilter : (isset($_GET['assignee']) ? $_GET['assignee'] : ''),
    'due_from' => $dueFrom ?? (isset($_GET['due_from']) ? $_GET['due_from'] : ''),
    'due_to' => $dueTo ?? (isset($_GET['due_to']) ? $_GET['due_to'] : ''),
    'priority' => $priorityFilter ?? (isset($_GET['priority']) ? $_GET['priority'] : ''),
]));
if ($filterQuery !== '') $filterQuery = '&' . $filterQuery;
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
            <header class="admin-topbar admin-topbar-project">
                <div class="admin-topbar-project-head">
                    <a href="projects.php" class="project-back">← Projects</a>
                    <h1><?= htmlspecialchars($project['name']) ?></h1>
                    <p class="welcome">
                        <?php if (admin_can_edit()): ?><a href="project-form.php?id=<?= $id ?>" class="btn btn-primary">Edit project</a><?php endif; ?>
                    </p>
                </div>
            </header>
            <main class="admin-main admin-main-project">
                <div class="admin-content">
                    <?php if (!empty($errors)): ?>
                    <div class="alert alert-error"><?= implode(' ', array_map('htmlspecialchars', $errors)) ?></div>
                    <?php endif; ?>

                    <div class="project-summary-strip">
                        <div class="project-summary-meta">
                            <span class="badge badge-<?= htmlspecialchars($project['status']) ?>"><?= htmlspecialchars(project_status_label($project['status'] ?? '')) ?></span>
                            <?php if ($client): ?>
                            <span class="project-summary-client"><a href="client-view.php?id=<?= (int)$client['id'] ?>"><?= htmlspecialchars($client['contact_name']) ?><?= $client['company'] ? ' · ' . htmlspecialchars($client['company']) : '' ?></a></span>
                            <?php endif; ?>
                            <?php if (!empty($project['started_at'])): ?><span class="project-summary-date">Started <?= htmlspecialchars($project['started_at']) ?></span><?php endif; ?>
                        </div>
                        <div class="project-summary-stats">
                            <span class="project-stat"><strong><?= $taskCountTodo ?></strong> To do</span>
                            <span class="project-stat"><strong><?= $taskCountInProgress ?></strong> In progress</span>
                            <span class="project-stat project-stat-done"><strong><?= $taskCountDone ?></strong> Done</span>
                            <?php if ($taskTotal > 0): ?>
                            <div class="project-progress-bar" role="progressbar" aria-valuenow="<?= $taskProgressPct ?>" aria-valuemin="0" aria-valuemax="100" title="<?= $taskProgressPct ?>% complete">
                                <div class="project-progress-fill" style="width:<?= $taskProgressPct ?>%"></div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php $hasProjectInfo = !empty($project['ended_at']) || (($project['status'] ?? '') === 'done' && !empty($project['closed_at'])) || !empty($project['notes']) || !empty($quotes) || !empty($invoices); if ($hasProjectInfo): ?>
                    <div class="admin-content-card project-info-card">
                        <div class="admin-content-card-body project-info-grid">
                            <div class="project-info-block">
                                <?php if (!empty($project['ended_at'])): ?><p><strong>Ended</strong> <?= htmlspecialchars($project['ended_at']) ?></p><?php endif; ?>
                                <?php if (($project['status'] ?? '') === 'done' && !empty($project['closed_at'])): ?><p><strong>Closed</strong> <?= htmlspecialchars(date('Y-m-d H:i', strtotime($project['closed_at']))) ?></p><?php endif; ?>
                                <?php if (!empty($project['notes'])): ?><p class="project-notes"><strong>Notes</strong><br><?= nl2br(htmlspecialchars($project['notes'])) ?></p><?php endif; ?>
                            </div>
                            <?php if (!empty($quotes) || !empty($invoices)): ?>
                            <div class="project-info-block project-info-links">
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
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="admin-content-card project-tasks-card">
                        <div class="admin-content-card-body">
                            <div class="task-section-header">
                                <h3 class="card-title">Tasks</h3>
                                <div class="task-section-controls">
                                    <form method="get" class="task-filters" action="project-view.php">
                                        <input type="hidden" name="id" value="<?= $id ?>">
                                        <?php if ($viewMode === 'board'): ?><input type="hidden" name="view" value="board"><?php endif; ?>
                                        <select name="assignee" onchange="this.form.submit()" class="task-filter-select" title="Assignee">
                                            <option value="">All assignees</option>
                                            <?php foreach ($adminUsers as $u): ?>
                                            <option value="<?= (int)$u['id'] ?>" <?= $assigneeFilter === (int)$u['id'] ? 'selected' : '' ?>><?= htmlspecialchars($u['username']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <select name="priority" onchange="this.form.submit()" class="task-filter-select" title="Priority">
                                            <option value="">All priorities</option>
                                            <option value="low" <?= $priorityFilter === 'low' ? 'selected' : '' ?>>Low</option>
                                            <option value="medium" <?= $priorityFilter === 'medium' ? 'selected' : '' ?>>Medium</option>
                                            <option value="high" <?= $priorityFilter === 'high' ? 'selected' : '' ?>>High</option>
                                        </select>
                                        <input type="date" name="due_from" value="<?= htmlspecialchars($dueFrom ?? '') ?>" onchange="this.form.submit()" class="task-filter-date" title="Due from">
                                        <input type="date" name="due_to" value="<?= htmlspecialchars($dueTo ?? '') ?>" onchange="this.form.submit()" class="task-filter-date" title="Due to">
                                        <?php if ($assigneeFilter !== null || $dueFrom !== null || $dueTo !== null || $priorityFilter !== null): ?>
                                        <a href="project-view.php?id=<?= $id ?><?= $viewQuery ?>" class="filter-clear">Clear</a>
                                        <?php endif; ?>
                                    </form>
                                    <span class="view-switch">
                                        <a href="project-view.php?id=<?= $id ?><?= $filterQuery ?>" class="<?= $viewMode === 'list' ? 'active' : '' ?>">List</a>
                                        <a href="project-view.php?id=<?= $id ?>&view=board<?= $filterQuery ?>" class="<?= $viewMode === 'board' ? 'active' : '' ?>">Board</a>
                                    </span>
                                </div>
                            </div>

                            <?php if (admin_can_edit()): ?>
                            <div class="add-task-card">
                                <div class="add-task-card-head">Add task</div>
                                <form method="post" class="project-task-form add-task-form">
                                    <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
                                    <input type="hidden" name="add_task" value="1">
                                    <?php if ($viewMode === 'board'): ?><input type="hidden" name="view" value="board"><?php if ($assigneeFilter !== null): ?><input type="hidden" name="assignee" value="<?= (int)$assigneeFilter ?>"><?php endif; ?><?php if ($dueFrom !== null): ?><input type="hidden" name="due_from" value="<?= htmlspecialchars($dueFrom) ?>"><?php endif; ?><?php if ($dueTo !== null): ?><input type="hidden" name="due_to" value="<?= htmlspecialchars($dueTo) ?>"><?php endif; ?><?php if ($priorityFilter !== null): ?><input type="hidden" name="priority" value="<?= htmlspecialchars($priorityFilter) ?>"><?php endif; ?><?php endif; ?>
                                    <div class="add-task-row add-task-row-main">
                                        <input type="text" name="task_title" placeholder="What needs to be done?" required class="add-task-title">
                                        <button type="submit" class="btn btn-primary add-task-btn">Add task</button>
                                    </div>
                                    <div class="add-task-row add-task-row-options">
                                        <span class="add-task-options-label">Details</span>
                                        <select name="task_status" class="add-task-field">
                                            <option value="todo">To do</option>
                                            <option value="in_progress">In progress</option>
                                            <option value="done">Done</option>
                                        </select>
                                        <input type="date" name="task_due_date" class="add-task-field add-task-due" title="Due date">
                                        <select name="task_priority" class="add-task-field">
                                            <option value="">Priority</option>
                                            <option value="low">Low</option>
                                            <option value="medium">Medium</option>
                                            <option value="high">High</option>
                                        </select>
                                        <select name="task_assigned_to" class="add-task-field">
                                            <option value="">Assignee</option>
                                            <?php foreach ($adminUsers as $u): ?>
                                            <option value="<?= (int)$u['id'] ?>"><?= htmlspecialchars($u['username']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label class="add-task-checkbox"><input type="checkbox" name="task_client_action" value="1"> Customer to do</label>
                                    </div>
                                    <div class="add-task-row add-task-row-desc">
                                        <label class="add-task-desc-label">Description <span class="add-task-optional">(optional)</span></label>
                                        <textarea name="task_description" rows="2" placeholder="Add more context…" class="add-task-desc"></textarea>
                                    </div>
                                </form>
                            </div>
                            <?php endif; ?>

                            <?php if (empty($tasks)): ?>
                            <div class="project-empty-state">
                                <div class="project-empty-icon" aria-hidden="true">📋</div>
                                <p class="project-empty-title">No tasks yet</p>
                                <p class="project-empty-text">Add your first task above to get started, or switch to <a href="project-view.php?id=<?= $id ?>&view=board<?= $filterQuery ?>">Board view</a>.</p>
                            </div>
                            <?php elseif ($viewMode === 'board'): ?>
                            <div class="kanban-board">
                                <div class="kanban-column">
                                    <h4 class="kanban-column-title">To do</h4>
                                    <div class="kanban-cards">
                                        <?php foreach ($tasksByStatus['todo'] as $t): ?>
                                        <div class="kanban-card">
                                            <div class="kanban-card-title"><?= htmlspecialchars($t['title']) ?><?php if (!empty($t['client_action'])): ?><span class="badge badge-client">Customer</span><?php endif; ?></div>
                                            <?php if (!empty($t['items'])): $done = count(array_filter($t['items'], function($i){ return !empty($i['is_done']); })); ?><div class="kanban-card-checklist"><?= $done ?>/<?= count($t['items']) ?></div><?php endif; ?>
                                            <?php if (!empty($t['description'])): ?><div class="kanban-card-desc"><?= htmlspecialchars(mb_substr($t['description'], 0, 80)) ?><?= mb_strlen($t['description']) > 80 ? '…' : '' ?></div><?php endif; ?>
                                            <div class="kanban-card-meta"><?= $t['due_date'] ? 'Due ' . htmlspecialchars($t['due_date']) : '' ?> <?= $t['priority'] ? '· ' . $t['priority'] : '' ?> <?= $t['assigned_name'] ? '· ' . htmlspecialchars($t['assigned_name']) : '' ?></div>
                                            <?php if (admin_can_edit()): ?>
                                            <div class="kanban-card-actions">
                                                <a href="project-view.php?id=<?= $id ?>&edit_task=<?= (int)$t['id'] ?><?= $viewQuery ?>">Edit</a>
                                                <form method="post" action="task-status.php" style="display:inline;"><input type="hidden" name="_csrf" value="<?= csrf_token() ?>"><input type="hidden" name="id" value="<?= (int)$t['id'] ?>"><input type="hidden" name="project_id" value="<?= $id ?>"><input type="hidden" name="view" value="board"><?php if ($assigneeFilter !== null): ?><input type="hidden" name="assignee" value="<?= (int)$assigneeFilter ?>"><?php endif; ?><?php if ($dueFrom !== null): ?><input type="hidden" name="due_from" value="<?= htmlspecialchars($dueFrom) ?>"><?php endif; ?><?php if ($dueTo !== null): ?><input type="hidden" name="due_to" value="<?= htmlspecialchars($dueTo) ?>"><?php endif; ?><?php if ($priorityFilter !== null): ?><input type="hidden" name="priority" value="<?= htmlspecialchars($priorityFilter) ?>"><?php endif; ?><input type="hidden" name="status" value="in_progress"><button type="submit">→ In progress</button></form>
                                                <form method="post" action="task-status.php" style="display:inline;"><input type="hidden" name="_csrf" value="<?= csrf_token() ?>"><input type="hidden" name="id" value="<?= (int)$t['id'] ?>"><input type="hidden" name="project_id" value="<?= $id ?>"><input type="hidden" name="view" value="board"><?php if ($assigneeFilter !== null): ?><input type="hidden" name="assignee" value="<?= (int)$assigneeFilter ?>"><?php endif; ?><?php if ($dueFrom !== null): ?><input type="hidden" name="due_from" value="<?= htmlspecialchars($dueFrom) ?>"><?php endif; ?><?php if ($dueTo !== null): ?><input type="hidden" name="due_to" value="<?= htmlspecialchars($dueTo) ?>"><?php endif; ?><?php if ($priorityFilter !== null): ?><input type="hidden" name="priority" value="<?= htmlspecialchars($priorityFilter) ?>"><?php endif; ?><input type="hidden" name="status" value="done"><button type="submit">→ Done</button></form>
                                                <form method="post" action="task-delete.php" style="display:inline;" onsubmit="return confirm('Delete?');"><input type="hidden" name="_csrf" value="<?= csrf_token() ?>"><input type="hidden" name="id" value="<?= (int)$t['id'] ?>"><input type="hidden" name="project_id" value="<?= $id ?>"><button type="submit" class="danger">Delete</button></form>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="kanban-column">
                                    <h4 class="kanban-column-title">In progress</h4>
                                    <div class="kanban-cards">
                                        <?php foreach ($tasksByStatus['in_progress'] as $t): ?>
                                        <div class="kanban-card">
                                            <div class="kanban-card-title"><?= htmlspecialchars($t['title']) ?><?php if (!empty($t['client_action'])): ?><span class="badge badge-client">Customer</span><?php endif; ?></div>
                                            <?php if (!empty($t['items'])): $done = count(array_filter($t['items'], function($i){ return !empty($i['is_done']); })); ?><div class="kanban-card-checklist"><?= $done ?>/<?= count($t['items']) ?></div><?php endif; ?>
                                            <?php if (!empty($t['description'])): ?><div class="kanban-card-desc"><?= htmlspecialchars(mb_substr($t['description'], 0, 80)) ?><?= mb_strlen($t['description']) > 80 ? '…' : '' ?></div><?php endif; ?>
                                            <div class="kanban-card-meta"><?= $t['due_date'] ? 'Due ' . htmlspecialchars($t['due_date']) : '' ?> <?= $t['priority'] ? '· ' . $t['priority'] : '' ?> <?= $t['assigned_name'] ? '· ' . htmlspecialchars($t['assigned_name']) : '' ?></div>
                                            <?php if (admin_can_edit()): ?>
                                            <div class="kanban-card-actions">
                                                <a href="project-view.php?id=<?= $id ?>&edit_task=<?= (int)$t['id'] ?><?= $viewQuery ?>">Edit</a>
                                                <form method="post" action="task-status.php" style="display:inline;"><input type="hidden" name="_csrf" value="<?= csrf_token() ?>"><input type="hidden" name="id" value="<?= (int)$t['id'] ?>"><input type="hidden" name="project_id" value="<?= $id ?>"><input type="hidden" name="view" value="board"><?php if ($assigneeFilter !== null): ?><input type="hidden" name="assignee" value="<?= (int)$assigneeFilter ?>"><?php endif; ?><?php if ($dueFrom !== null): ?><input type="hidden" name="due_from" value="<?= htmlspecialchars($dueFrom) ?>"><?php endif; ?><?php if ($dueTo !== null): ?><input type="hidden" name="due_to" value="<?= htmlspecialchars($dueTo) ?>"><?php endif; ?><?php if ($priorityFilter !== null): ?><input type="hidden" name="priority" value="<?= htmlspecialchars($priorityFilter) ?>"><?php endif; ?><input type="hidden" name="status" value="todo"><button type="submit">← To do</button></form>
                                                <form method="post" action="task-status.php" style="display:inline;"><input type="hidden" name="_csrf" value="<?= csrf_token() ?>"><input type="hidden" name="id" value="<?= (int)$t['id'] ?>"><input type="hidden" name="project_id" value="<?= $id ?>"><input type="hidden" name="view" value="board"><?php if ($assigneeFilter !== null): ?><input type="hidden" name="assignee" value="<?= (int)$assigneeFilter ?>"><?php endif; ?><?php if ($dueFrom !== null): ?><input type="hidden" name="due_from" value="<?= htmlspecialchars($dueFrom) ?>"><?php endif; ?><?php if ($dueTo !== null): ?><input type="hidden" name="due_to" value="<?= htmlspecialchars($dueTo) ?>"><?php endif; ?><?php if ($priorityFilter !== null): ?><input type="hidden" name="priority" value="<?= htmlspecialchars($priorityFilter) ?>"><?php endif; ?><input type="hidden" name="status" value="done"><button type="submit">→ Done</button></form>
                                                <form method="post" action="task-delete.php" style="display:inline;" onsubmit="return confirm('Delete?');"><input type="hidden" name="_csrf" value="<?= csrf_token() ?>"><input type="hidden" name="id" value="<?= (int)$t['id'] ?>"><input type="hidden" name="project_id" value="<?= $id ?>"><button type="submit" class="danger">Delete</button></form>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="kanban-column">
                                    <h4 class="kanban-column-title">Done</h4>
                                    <div class="kanban-cards">
                                        <?php foreach ($tasksByStatus['done'] as $t): ?>
                                        <div class="kanban-card kanban-card-done">
                                            <div class="kanban-card-title"><?= htmlspecialchars($t['title']) ?><?php if (!empty($t['client_action'])): ?><span class="badge badge-client">Customer</span><?php endif; ?></div>
                                            <?php if (!empty($t['items'])): $done = count(array_filter($t['items'], function($i){ return !empty($i['is_done']); })); ?><div class="kanban-card-checklist"><?= $done ?>/<?= count($t['items']) ?></div><?php endif; ?>
                                            <?php if (!empty($t['description'])): ?><div class="kanban-card-desc"><?= htmlspecialchars(mb_substr($t['description'], 0, 80)) ?><?= mb_strlen($t['description']) > 80 ? '…' : '' ?></div><?php endif; ?>
                                            <div class="kanban-card-meta"><?= $t['due_date'] ? 'Due ' . htmlspecialchars($t['due_date']) : '' ?> <?= $t['assigned_name'] ? '· ' . htmlspecialchars($t['assigned_name']) : '' ?></div>
                                            <?php if (admin_can_edit()): ?>
                                            <div class="kanban-card-actions">
                                                <a href="project-view.php?id=<?= $id ?>&edit_task=<?= (int)$t['id'] ?><?= $viewQuery ?>">Edit</a>
                                                <form method="post" action="task-status.php" style="display:inline;"><input type="hidden" name="_csrf" value="<?= csrf_token() ?>"><input type="hidden" name="id" value="<?= (int)$t['id'] ?>"><input type="hidden" name="project_id" value="<?= $id ?>"><input type="hidden" name="view" value="board"><?php if ($assigneeFilter !== null): ?><input type="hidden" name="assignee" value="<?= (int)$assigneeFilter ?>"><?php endif; ?><?php if ($dueFrom !== null): ?><input type="hidden" name="due_from" value="<?= htmlspecialchars($dueFrom) ?>"><?php endif; ?><?php if ($dueTo !== null): ?><input type="hidden" name="due_to" value="<?= htmlspecialchars($dueTo) ?>"><?php endif; ?><?php if ($priorityFilter !== null): ?><input type="hidden" name="priority" value="<?= htmlspecialchars($priorityFilter) ?>"><?php endif; ?><input type="hidden" name="status" value="todo"><button type="submit">← To do</button></form>
                                                <form method="post" action="task-status.php" style="display:inline;"><input type="hidden" name="_csrf" value="<?= csrf_token() ?>"><input type="hidden" name="id" value="<?= (int)$t['id'] ?>"><input type="hidden" name="project_id" value="<?= $id ?>"><input type="hidden" name="view" value="board"><?php if ($assigneeFilter !== null): ?><input type="hidden" name="assignee" value="<?= (int)$assigneeFilter ?>"><?php endif; ?><?php if ($dueFrom !== null): ?><input type="hidden" name="due_from" value="<?= htmlspecialchars($dueFrom) ?>"><?php endif; ?><?php if ($dueTo !== null): ?><input type="hidden" name="due_to" value="<?= htmlspecialchars($dueTo) ?>"><?php endif; ?><?php if ($priorityFilter !== null): ?><input type="hidden" name="priority" value="<?= htmlspecialchars($priorityFilter) ?>"><?php endif; ?><input type="hidden" name="status" value="in_progress"><button type="submit">← In progress</button></form>
                                                <form method="post" action="task-delete.php" style="display:inline;" onsubmit="return confirm('Delete?');"><input type="hidden" name="_csrf" value="<?= csrf_token() ?>"><input type="hidden" name="id" value="<?= (int)$t['id'] ?>"><input type="hidden" name="project_id" value="<?= $id ?>"><button type="submit" class="danger">Delete</button></form>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <ul class="project-task-list">
                                <?php foreach ($tasks as $t): $isDone = ($t['status'] ?? '') === 'done'; ?>
                                <li class="project-task-item <?= $isDone ? 'task-done' : '' ?>">
                                    <?php if ($taskIdEdit === (int)$t['id'] && admin_can_edit()): ?>
                                    <form method="post" class="project-task-edit-form">
                                        <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
                                        <input type="hidden" name="edit_task" value="1">
                                        <input type="hidden" name="task_id" value="<?= (int)$t['id'] ?>">
                                        <?php if ($viewMode === 'board'): ?><input type="hidden" name="view" value="board"><?php if ($assigneeFilter !== null): ?><input type="hidden" name="assignee" value="<?= (int)$assigneeFilter ?>"><?php endif; ?><?php if ($dueFrom !== null): ?><input type="hidden" name="due_from" value="<?= htmlspecialchars($dueFrom) ?>"><?php endif; ?><?php if ($dueTo !== null): ?><input type="hidden" name="due_to" value="<?= htmlspecialchars($dueTo) ?>"><?php endif; ?><?php if ($priorityFilter !== null): ?><input type="hidden" name="priority" value="<?= htmlspecialchars($priorityFilter) ?>"><?php endif; ?><?php endif; ?>
                                        <input type="text" name="task_title" value="<?= htmlspecialchars($t['title']) ?>" required>
                                        <select name="task_status">
                                            <option value="todo" <?= $t['status'] === 'todo' ? 'selected' : '' ?>>To do</option>
                                            <option value="in_progress" <?= $t['status'] === 'in_progress' ? 'selected' : '' ?>>In progress</option>
                                            <option value="done" <?= $t['status'] === 'done' ? 'selected' : '' ?>>Done</option>
                                        </select>
                                        <input type="date" name="task_due_date" value="<?= htmlspecialchars($t['due_date'] ?? '') ?>">
                                        <select name="task_priority">
                                            <option value="">Priority</option>
                                            <option value="low" <?= ($t['priority'] ?? '') === 'low' ? 'selected' : '' ?>>Low</option>
                                            <option value="medium" <?= ($t['priority'] ?? '') === 'medium' ? 'selected' : '' ?>>Medium</option>
                                            <option value="high" <?= ($t['priority'] ?? '') === 'high' ? 'selected' : '' ?>>High</option>
                                        </select>
                                        <select name="task_assigned_to">
                                            <option value="">Assignee</option>
                                            <?php foreach ($adminUsers as $u): ?>
                                            <option value="<?= (int)$u['id'] ?>" <?= (int)($t['assigned_to'] ?? 0) === (int)$u['id'] ? 'selected' : '' ?>><?= htmlspecialchars($u['username']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <textarea name="task_description" rows="2"><?= htmlspecialchars($t['description'] ?? '') ?></textarea>
                                        <label class="checkbox-label"><input type="checkbox" name="task_client_action" value="1" <?= !empty($t['client_action']) ? 'checked' : '' ?>> Customer to do</label>
                                        <div class="task-checklist-edit">
                                            <strong>Checklist</strong>
                                            <?php
                                            $items = $t['items'] ?? [];
                                            $maxRows = max(count($items) + 2, 3);
                                            for ($i = 0; $i < $maxRows; $i++):
                                                $item = $items[$i] ?? null;
                                                $tit = $item ? $item['title'] : '';
                                                $done = $item && !empty($item['is_done']);
                                            ?>
                                            <div class="checklist-row">
                                                <input type="text" name="subtask_title[<?= $i ?>]" value="<?= htmlspecialchars($tit) ?>" placeholder="Subtask">
                                                <label class="checkbox-label"><input type="checkbox" name="subtask_done[<?= $i ?>]" value="1" <?= $done ? 'checked' : '' ?>> Done</label>
                                            </div>
                                            <?php endfor; ?>
                                        </div>
                                        <div class="task-edit-actions">
                                            <button type="submit">Save</button>
                                            <a href="project-view.php?id=<?= $id ?><?= $viewQuery ?>">Cancel</a>
                                        </div>
                                    </form>
                                    <?php else: ?>
                                    <span class="task-title"><?= htmlspecialchars($t['title']) ?></span><?php if (!empty($t['client_action'])): ?><span class="badge badge-client">Customer</span><?php endif; ?>
                                    <?php if (!empty($t['items'])): $done = count(array_filter($t['items'], function($i){ return !empty($i['is_done']); })); $total = count($t['items']); ?>
                                    <span class="task-checklist-summary"><?= $done ?>/<?= $total ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($t['description'])): ?><span class="task-desc"><?= nl2br(htmlspecialchars($t['description'])) ?></span><?php endif; ?>
                                    <span class="task-meta">
                                        <?= $t['due_date'] ? 'Due ' . htmlspecialchars($t['due_date']) . ' · ' : '' ?>
                                        <?= $t['priority'] ? htmlspecialchars($t['priority']) . ' · ' : '' ?>
                                        <?= $t['assigned_name'] ? htmlspecialchars($t['assigned_name']) : '' ?>
                                    </span>
                                    <?php if (admin_can_edit()): ?>
                                    <span class="task-actions">
                                        <a href="project-view.php?id=<?= $id ?>&edit_task=<?= (int)$t['id'] ?><?= $viewQuery ?>">Edit</a>
                                        <?php if (!$isDone): ?>
                                        <form method="post" action="task-complete.php" style="display:inline;">
                                            <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
                                            <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                                            <input type="hidden" name="project_id" value="<?= $id ?>">
                                            <?php if ($assigneeFilter !== null): ?><input type="hidden" name="assignee" value="<?= (int)$assigneeFilter ?>"><?php endif; ?>
                                            <?php if ($dueFrom !== null): ?><input type="hidden" name="due_from" value="<?= htmlspecialchars($dueFrom) ?>"><?php endif; ?>
                                            <?php if ($dueTo !== null): ?><input type="hidden" name="due_to" value="<?= htmlspecialchars($dueTo) ?>"><?php endif; ?>
                                            <?php if ($priorityFilter !== null): ?><input type="hidden" name="priority" value="<?= htmlspecialchars($priorityFilter) ?>"><?php endif; ?>
                                            <button type="submit">Complete</button>
                                        </form>
                                        <?php endif; ?>
                                        <form method="post" action="task-delete.php" style="display:inline;" onsubmit="return confirm('Delete this task?');">
                                            <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
                                            <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                                            <input type="hidden" name="project_id" value="<?= $id ?>">
                                            <?php if ($assigneeFilter !== null): ?><input type="hidden" name="assignee" value="<?= (int)$assigneeFilter ?>"><?php endif; ?>
                                            <?php if ($dueFrom !== null): ?><input type="hidden" name="due_from" value="<?= htmlspecialchars($dueFrom) ?>"><?php endif; ?>
                                            <?php if ($dueTo !== null): ?><input type="hidden" name="due_to" value="<?= htmlspecialchars($dueTo) ?>"><?php endif; ?>
                                            <?php if ($priorityFilter !== null): ?><input type="hidden" name="priority" value="<?= htmlspecialchars($priorityFilter) ?>"><?php endif; ?>
                                            <button type="submit" class="danger">Delete</button>
                                        </form>
                                    </span>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
