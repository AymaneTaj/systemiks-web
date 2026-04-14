<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();
admin_require_role(['admin', 'editor']);

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$client = null;
if ($id) {
    $stmt = db()->prepare("SELECT * FROM clients WHERE id = ?");
    $stmt->execute([$id]);
    $client = $stmt->fetch();
    if (!$client) { header('Location: clients.php'); exit; }
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validate();
    $company = trim($_POST['company'] ?? '');
    $contact_name = trim($_POST['contact_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $portal_enabled = isset($_POST['portal_enabled']) && $_POST['portal_enabled'] === '1' ? 1 : 0;

    if ($contact_name === '') $errors[] = 'Contact name is required.';
    if ($email === '') $errors[] = 'Email is required.';
    if (!empty($errors)) {
        $client = array_merge($client ?? [], compact('company', 'contact_name', 'email', 'phone', 'address', 'notes', 'portal_enabled'));
    } else {
        $pdo = db();
        $portal_token = null;
        if ($portal_enabled) {
            if ($id && !empty($client['portal_token'])) {
                $portal_token = $client['portal_token'];
            } else {
                $portal_token = bin2hex(random_bytes(24));
            }
        } elseif ($id && !empty($client['portal_token'])) {
            $portal_token = $client['portal_token'];
        }
        if ($id) {
            $pdo->prepare("UPDATE clients SET company=?, contact_name=?, email=?, phone=?, address=?, notes=?, portal_enabled=?, portal_token=?, updated_at=CURRENT_TIMESTAMP WHERE id=?")
                ->execute([$company, $contact_name, $email, $phone, $address, $notes, $portal_enabled, $portal_token, $id]);
            log_activity('updated', 'client', $id, $contact_name);
        } else {
            $pdo->prepare("INSERT INTO clients (company, contact_name, email, phone, address, notes, portal_enabled, portal_token) VALUES (?,?,?,?,?,?,?,?)")
                ->execute([$company, $contact_name, $email, $phone, $address, $notes, $portal_enabled, $portal_token]);
            $newId = (int) $pdo->lastInsertId();
            log_activity('created', 'client', $newId, $contact_name);
            if ($portal_enabled) {
                header('Location: client-view.php?id=' . $newId);
                exit;
            }
        }
        header('Location: clients.php');
        exit;
    }
}

$pageTitle = $client ? 'Edit client' : 'Add client';
$currentNav = 'clients';
$client = $client ?? [];
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
                <p class="welcome"><a href="clients.php">← Back to clients</a></p>
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
                            <label>Contact name *</label>
                            <input type="text" name="contact_name" value="<?= htmlspecialchars($client['contact_name'] ?? '') ?>" required>
                        </div>
                        <div class="form-row">
                            <label>Company</label>
                            <input type="text" name="company" value="<?= htmlspecialchars($client['company'] ?? '') ?>">
                        </div>
                        <div class="form-row">
                            <label>Email *</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($client['email'] ?? '') ?>" required>
                        </div>
                        <div class="form-row">
                            <label>Phone</label>
                            <input type="text" name="phone" value="<?= htmlspecialchars($client['phone'] ?? '') ?>">
                        </div>
                        <div class="form-row">
                            <label>Address</label>
                            <textarea name="address" rows="2"><?= htmlspecialchars($client['address'] ?? '') ?></textarea>
                        </div>
                        <div class="form-row">
                            <label>Notes</label>
                            <textarea name="notes" rows="3"><?= htmlspecialchars($client['notes'] ?? '') ?></textarea>
                        </div>
                        <?php if (admin_can_edit_settings()): ?>
                        <div class="form-row client-portal-row">
                            <label><input type="checkbox" name="portal_enabled" value="1" <?= !empty($client['portal_enabled']) ? 'checked' : '' ?>> Enable client dashboard</label>
                            <p class="form-hint">The customer can access their dashboard only when this is enabled. You can send them the dashboard link after saving.</p>
                        </div>
                        <?php if ($id && !empty($client['portal_enabled']) && !empty($client['portal_token'])): 
                            $portalUrl = rtrim(defined('SITE_URL') ? SITE_URL : ('http' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')), '/') . '/client-dashboard.php?token=' . urlencode($client['portal_token']);
                        ?>
                        <div class="form-row">
                            <label>Client dashboard link</label>
                            <div class="portal-link-wrap">
                                <input type="text" id="portal-link" readonly value="<?= htmlspecialchars($portalUrl) ?>" class="portal-link-input">
                                <button type="button" class="btn btn-secondary btn-sm" id="copy-portal-btn" onclick="var el=document.getElementById('portal-link'); navigator.clipboard.writeText(el.value); var b=document.getElementById('copy-portal-btn'); b.textContent='Copied!'; setTimeout(function(){ b.textContent='Copy'; }, 2000);">Copy</button>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
                        <div class="form-actions">
                            <button type="submit"><?= $id ? 'Save changes' : 'Add client' ?></button>
                            <a href="clients.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
