<?php
require_once __DIR__ . '/../../config/bootstrap.php';

if (admin_is_logged_in()) {
    header('Location: ' . ADMIN_DASHBOARD);
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validate();
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';
    if ($user === '' || $pass === '') {
        $error = 'Please enter username and password.';
    } elseif (admin_login($user, $pass)) {
        $redirect = $_GET['redirect'] ?? ADMIN_DASHBOARD;
        header('Location: ' . $redirect);
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login – <?= htmlspecialchars(SITE_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-login-page">
    <div class="admin-login-card">
        <h1><?= htmlspecialchars(SITE_NAME) ?> Admin</h1>
        <p class="login-sub">Sign in to the dashboard</p>
        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post" action="">
            <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" autocomplete="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autofocus>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" autocomplete="current-password" required>
            <button type="submit">Log in</button>
        </form>
        <p class="hint">Default: admin / admin123 — change after first login.</p>
    </div>
</body>
</html>
