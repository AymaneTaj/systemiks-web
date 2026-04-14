<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();
admin_require_role(['admin']);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id && $id !== (int)($_SESSION['admin_user_id'] ?? 0)) {
    db()->prepare("DELETE FROM admin_users WHERE id = ?")->execute([$id]);
}
header('Location: /admin/users.php');
exit;
