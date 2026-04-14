<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();
admin_require_role(['admin', 'editor']);

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$project = null;
if ($id) {
    $stmt = db()->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $project = $stmt->fetch();
    if (!$project) { header('Location: projects.php'); exit; }
}

$clients = db()->query("SELECT id, contact_name, company FROM clients ORDER BY contact_name")->fetchAll();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validate();
    $name = trim($_POST['name'] ?? '');
    $client_id = !empty($_POST['client_id']) ? (int) $_POST['client_id'] : null;
    $status = trim($_POST['status'] ?? 'lead');
    $started_at = trim($_POST['started_at'] ?? '') ?: null;
    $ended_at = trim($_POST['ended_at'] ?? '') ?: null;
    $notes = trim($_POST['notes'] ?? '');

    if ($name === '') $errors[] = 'Project name is required.';
    if (!in_array($status, ['lead', 'in_progress', 'on_hold', 'done'], true)) $status = 'lead';

    if (empty($errors)) {
        $pdo = db();
        $closed_at = null;
        if ($status === 'done') {
            if ($id) {
                $row = $pdo->prepare("SELECT closed_at FROM projects WHERE id = ?");
                $row->execute([$id]);
                $closed_at = $row->fetchColumn();
            }
            if ($closed_at === null || $closed_at === '') $closed_at = date('Y-m-d H:i:s');
        }
        if ($id) {
            $pdo->prepare("UPDATE projects SET name=?, client_id=?, status=?, started_at=?, ended_at=?, closed_at=?, notes=?, updated_at=CURRENT_TIMESTAMP WHERE id=?")
                ->execute([$name, $client_id, $status, $started_at, $ended_at, $closed_at, $notes, $id]);
            log_activity('updated', 'project', $id, $name);
        } else {
            if ($status === 'done') $closed_at = date('Y-m-d H:i:s');
            $pdo->prepare("INSERT INTO projects (name, client_id, status, started_at, ended_at, closed_at, notes) VALUES (?,?,?,?,?,?,?)")
                ->execute([$name, $client_id, $status, $started_at, $ended_at, $closed_at ?? null, $notes]);
            log_activity('created', 'project', (int) $pdo->lastInsertId(), $name);
        }
        header('Location: projects.php');
        exit;
    }
    $project = array_merge($project ?? [], compact('name', 'client_id', 'status', 'started_at', 'ended_at', 'notes'));
}

$pageTitle = $project ? 'Edit project' : 'Add project';
$currentNav = 'projects';
$project = $project ?? [];
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
                <p class="welcome"><a href="projects.php">← Back to projects</a></p>
            </header>
            <main class="admin-main">
                <div class="admin-content">
                    <?php if (!empty($errors)): ?>
                    <div class="alert alert-error"><?= implode(' ', array_map('htmlspecialchars', $errors)) ?></div>
                    <?php endif; ?>
                    <div class="admin-form-card">
                    <form method="post" class="admin-form">
                        <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
                        <div class="form-row">
                            <label>Project name *</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($project['name'] ?? '') ?>" required>
                        </div>
                        <div class="form-row">
                            <label>Client</label>
                            <select name="client_id">
                                <option value="">— Select —</option>
                                <?php foreach ($clients as $c): ?>
                                <option value="<?= (int)$c['id'] ?>" <?= (isset($project['client_id']) && (int)$project['client_id'] === (int)$c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['company'] ? $c['company'] . ' (' . $c['contact_name'] . ')' : $c['contact_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-row">
                            <label>Status</label>
                            <select name="status">
                                <option value="lead" <?= ($project['status'] ?? '') === 'lead' ? 'selected' : '' ?>>Lead</option>
                                <option value="in_progress" <?= ($project['status'] ?? '') === 'in_progress' ? 'selected' : '' ?>>In progress</option>
                                <option value="on_hold" <?= ($project['status'] ?? '') === 'on_hold' ? 'selected' : '' ?>>On hold</option>
                                <option value="done" <?= ($project['status'] ?? '') === 'done' ? 'selected' : '' ?>>Closed</option>
                            </select>
                        </div>
                        <div class="form-row two-cols">
                            <div><label>Started</label><input type="date" name="started_at" value="<?= htmlspecialchars($project['started_at'] ?? '') ?>"></div>
                            <div><label>Ended</label><input type="date" name="ended_at" value="<?= htmlspecialchars($project['ended_at'] ?? '') ?>"></div>
                        </div>
                        <div class="form-row">
                            <label>Notes</label>
                            <textarea name="notes" rows="3"><?= htmlspecialchars($project['notes'] ?? '') ?></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit"><?= $id ? 'Save changes' : 'Add project' ?></button>
                            <a href="projects.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
