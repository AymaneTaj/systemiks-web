<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();
admin_require_role(['admin']);

$pdo = db();
$users = $pdo->query("SELECT id, username, email, role, created_at FROM admin_users ORDER BY username")->fetchAll();

$pageTitle = 'Users';
$currentNav = 'users';
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
                <p class="welcome"><a href="user-form.php" class="btn btn-primary">+ Add user</a></p>
            </header>
            <main class="admin-main">
                <div class="admin-content">
                    <p class="page-subtitle">Admin, editor, and viewer accounts. Only admins can manage users.</p>
                    <div class="admin-content-card">
                        <div class="admin-content-card-body">
                            <div class="table-wrap">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Created</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $u): ?>
                                        <tr>
                                            <td><a href="user-form.php?id=<?= (int)$u['id'] ?>"><?= htmlspecialchars($u['username']) ?></a></td>
                                            <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
                                            <td><span class="badge badge-<?= htmlspecialchars($u['role'] ?? 'admin') ?>"><?= htmlspecialchars($u['role'] ?? 'admin') ?></span></td>
                                            <td><?= date('Y-m-d', strtotime($u['created_at'])) ?></td>
                                            <td class="actions">
                                                <a href="user-form.php?id=<?= (int)$u['id'] ?>">Edit</a>
                                                <?php if ((int)$u['id'] !== (int)($_SESSION['admin_user_id'] ?? 0)): ?>
                                                <a href="user-delete.php?id=<?= (int)$u['id'] ?>" class="danger" onclick="return confirm('Remove this user?');">Delete</a>
                                                <?php else: ?>
                                                <a href="password-change.php">Change my password</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
