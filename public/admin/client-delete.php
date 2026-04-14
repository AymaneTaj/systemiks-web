<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();
admin_require_role(['admin', 'editor']);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id) {
    log_activity('deleted', 'client', $id);
    db()->prepare("DELETE FROM clients WHERE id = ?")->execute([$id]);
}
header('Location: /admin/clients.php');
exit;
