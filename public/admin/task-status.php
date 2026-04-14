<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();
admin_require_role(['admin', 'editor']);

$projectId = (int) ($_POST['project_id'] ?? $_GET['project_id'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . ($projectId ? 'project-view.php?id=' . $projectId : 'projects.php'));
    exit;
}
csrf_validate();

$id = (int) ($_POST['id'] ?? 0);
$status = trim($_POST['status'] ?? '');
if (!in_array($status, ['todo', 'in_progress', 'done'], true)) $status = 'todo';

if ($id) {
    $stmt = db()->prepare("SELECT id, title FROM project_tasks WHERE id = ?");
    $stmt->execute([$id]);
    $task = $stmt->fetch();
    if ($task) {
        $completedAt = $status === 'done' ? date('Y-m-d H:i:s') : null;
        db()->prepare("UPDATE project_tasks SET status = ?, completed_at = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?")
            ->execute([$status, $completedAt, $id]);
        log_activity($status === 'done' ? 'completed' : 'updated', 'project_task', $id, $task['title']);
    }
}

$q = [];
if (!empty($_POST['view']) && $_POST['view'] === 'board') $q['view'] = 'board';
if (isset($_POST['assignee']) && $_POST['assignee'] !== '') $q['assignee'] = $_POST['assignee'];
if (!empty($_POST['due_from'])) $q['due_from'] = $_POST['due_from'];
if (!empty($_POST['due_to'])) $q['due_to'] = $_POST['due_to'];
if (!empty($_POST['priority']) && in_array($_POST['priority'], ['low', 'medium', 'high'], true)) $q['priority'] = $_POST['priority'];
$query = $q ? '&' . http_build_query($q) : '';
header('Location: project-view.php?id=' . $projectId . $query);
exit;
