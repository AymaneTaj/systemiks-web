<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();

$errors = [];
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validate();
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($new !== $confirm) $errors[] = 'New passwords do not match.';
    if (strlen($new) < 6) $errors[] = 'New password must be at least 6 characters.';

    if (empty($errors)) {
        $stmt = db()->prepare("SELECT password_hash FROM admin_users WHERE id = ?");
        $stmt->execute([$_SESSION['admin_user_id']]);
        $row = $stmt->fetch();
        if (!$row || !password_verify($current, $row['password_hash'])) {
            $errors[] = 'Current password is incorrect.';
        } else {
            db()->prepare("UPDATE admin_users SET password_hash = ? WHERE id = ?")
                ->execute([password_hash($new, PASSWORD_DEFAULT), $_SESSION['admin_user_id']]);
            $success = true;
        }
    }
}

$pageTitle = 'Change password';
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
                <p class="welcome"><a href="users.php">← Users</a></p>
            </header>
            <main class="admin-main">
                <div class="admin-content">
                    <?php if ($success): ?>
                    <div class="alert alert-success">Password updated.</div>
                    <p><a href="users.php" class="btn btn-primary">Back to users</a></p>
                    <?php else: ?>
                    <?php if (!empty($errors)): ?>
                    <div class="alert alert-error"><?= implode(' ', array_map('htmlspecialchars', $errors)) ?></div>
                    <?php endif; ?>
                    <div class="admin-form-card">
                    <form method="post" class="admin-form">
                        <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
                        <div class="form-row">
                            <label>Current password *</label>
                            <input type="password" name="current_password" required autocomplete="current-password">
                        </div>
                        <div class="form-row">
                            <label>New password *</label>
                            <input type="password" name="new_password" required minlength="6" autocomplete="new-password">
                        </div>
                        <div class="form-row">
                            <label>Confirm new password *</label>
                            <input type="password" name="confirm_password" required minlength="6" autocomplete="new-password">
                        </div>
                        <div class="form-actions">
                            <button type="submit">Update password</button>
                            <a href="users.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                    </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
