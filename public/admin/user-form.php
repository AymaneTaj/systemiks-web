<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();
admin_require_role(['admin']);

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$user = null;
if ($id) {
    $stmt = db()->prepare("SELECT id, username, email, role FROM admin_users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    if (!$user) { header('Location: users.php'); exit; }
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validate();
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? 'admin');
    $password = $_POST['password'] ?? '';

    if ($username === '') $errors[] = 'Username is required.';
    if (!in_array($role, ['admin', 'editor', 'viewer'], true)) $role = 'admin';

    if (empty($errors)) {
        $pdo = db();
        if ($id) {
            if ($password !== '') {
                $pdo->prepare("UPDATE admin_users SET username=?, email=?, role=?, password_hash=? WHERE id=?")
                    ->execute([$username, $email, $role, password_hash($password, PASSWORD_DEFAULT), $id]);
            } else {
                $pdo->prepare("UPDATE admin_users SET username=?, email=?, role=? WHERE id=?")
                    ->execute([$username, $email, $role, $id]);
            }
        } else {
            if ($password === '') $errors[] = 'Password is required for new user.';
            else {
                $pdo->prepare("INSERT INTO admin_users (username, password_hash, email, role) VALUES (?,?,?,?)")
                    ->execute([$username, password_hash($password, PASSWORD_DEFAULT), $email, $role]);
            }
        }
        if (empty($errors)) {
            header('Location: users.php');
            exit;
        }
    }
    $user = array_merge($user ?? [], compact('username', 'email', 'role'));
}

$pageTitle = $user ? 'Edit user' : 'Add user';
$currentNav = 'users';
$user = $user ?? [];
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
                <p class="welcome"><a href="users.php">← Back to users</a></p>
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
                            <label>Username *</label>
                            <input type="text" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                        </div>
                        <div class="form-row">
                            <label>Email</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                        </div>
                        <div class="form-row">
                            <label>Role</label>
                            <select name="role">
                                <option value="admin" <?= ($user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin (full access)</option>
                                <option value="editor" <?= ($user['role'] ?? '') === 'editor' ? 'selected' : '' ?>>Editor (CRM, no users/settings)</option>
                                <option value="viewer" <?= ($user['role'] ?? '') === 'viewer' ? 'selected' : '' ?>>Viewer (read-only)</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <label>Password <?= $id ? '(leave blank to keep)' : '*' ?></label>
                            <input type="password" name="password" value="" <?= $id ? '' : 'required' ?> autocomplete="new-password">
                        </div>
                        <div class="form-actions">
                            <button type="submit"><?= $id ? 'Save' : 'Add user' ?></button>
                            <a href="users.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
