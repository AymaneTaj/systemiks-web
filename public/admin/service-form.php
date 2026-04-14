<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();
admin_require_role(['admin', 'editor']);

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$service = null;
if ($id) {
    $stmt = db()->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $service = $stmt->fetch();
    if (!$service) { header('Location: services.php'); exit; }
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validate();
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $default_price = (float) ($_POST['default_price'] ?? 0);
    $sort_order = (int) ($_POST['sort_order'] ?? 0);

    if ($name === '') $errors[] = 'Name is required.';

    if (empty($errors)) {
        $pdo = db();
        if ($id) {
            $pdo->prepare("UPDATE services SET name=?, description=?, default_price=?, sort_order=? WHERE id=?")
                ->execute([$name, $description, $default_price, $sort_order, $id]);
        } else {
            $pdo->prepare("INSERT INTO services (name, description, default_price, sort_order) VALUES (?,?,?,?)")
                ->execute([$name, $description, $default_price, $sort_order]);
        }
        header('Location: services.php');
        exit;
    }
    $service = array_merge($service ?? [], compact('name', 'description', 'default_price', 'sort_order'));
}

$pageTitle = $service ? 'Edit service' : 'Add service';
$currentNav = 'services';
$service = $service ?? [];
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
                <p class="welcome"><a href="services.php">← Back to services</a></p>
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
                            <label>Name *</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($service['name'] ?? '') ?>" required>
                        </div>
                        <div class="form-row">
                            <label>Description</label>
                            <textarea name="description" rows="3"><?= htmlspecialchars($service['description'] ?? '') ?></textarea>
                        </div>
                        <div class="form-row">
                            <label>Default price (CAD)</label>
                            <input type="number" name="default_price" value="<?= htmlspecialchars($service['default_price'] ?? '0') ?>" min="0" step="0.01">
                        </div>
                        <div class="form-row">
                            <label>Sort order</label>
                            <input type="number" name="sort_order" value="<?= htmlspecialchars($service['sort_order'] ?? '0') ?>">
                        </div>
                        <div class="form-actions">
                            <button type="submit"><?= $id ? 'Save' : 'Add service' ?></button>
                            <a href="services.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
