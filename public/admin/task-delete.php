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
if ($id) {
    $stmt = db()->prepare("SELECT id, title FROM project_tasks WHERE id = ?");
    $stmt->execute([$id]);
    $task = $stmt->fetch();
    if ($task) {
        log_activity('deleted', 'project_task', $id, $task['title']);
        db()->prepare("DELETE FROM project_tasks WHERE id = ?")->execute([$id]);
    }
}
$redirect = $projectId ? 'project-view.php?id=' . $projectId : 'projects.php';
if ($projectId) {
    $q = [];
    if (!empty($_POST['view']) && $_POST['view'] === 'board') $q['view'] = 'board';
    if (isset($_POST['assignee']) && $_POST['assignee'] !== '') $q['assignee'] = $_POST['assignee'];
    if (!empty($_POST['due_from'])) $q['due_from'] = $_POST['due_from'];
    if (!empty($_POST['due_to'])) $q['due_to'] = $_POST['due_to'];
    if (!empty($_POST['priority']) && in_array($_POST['priority'], ['low', 'medium', 'high'], true)) $q['priority'] = $_POST['priority'];
    if (!empty($q)) $redirect .= '&' . http_build_query($q);
}
header('Location: ' . $redirect);
exit;
