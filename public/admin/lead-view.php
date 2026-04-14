<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$lead = null;
if ($id) {
    $stmt = db()->prepare("SELECT * FROM leads WHERE id = ?");
    $stmt->execute([$id]);
    $lead = $stmt->fetch();
}

if (!$lead) { header('Location: leads.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    csrf_validate();
    admin_require_role(['admin', 'editor']);
    $status = trim($_POST['status']);
    $admin_notes = trim($_POST['admin_notes'] ?? '');
    if (in_array($status, ['new', 'contacted', 'converted', 'archived'], true)) {
        db()->prepare("UPDATE leads SET status = ?, admin_notes = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?")
            ->execute([$status, $admin_notes, $id]);
        log_activity('status_updated', 'lead', $id, 'status=' . $status);
        $lead['status'] = $status;
        $lead['admin_notes'] = $admin_notes;
    }
}

$pageTitle = 'Lead #' . $lead['id'];
$currentNav = 'leads';
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
                <h1>Lead: <?= htmlspecialchars($lead['name']) ?></h1>
                <p class="welcome">
                    <?php if (admin_can_edit()): ?>
                    <a href="lead-convert.php?id=<?= $id ?>" class="btn btn-primary">Convert to quote</a>
                    <?php endif; ?>
                    <a href="leads.php">← Leads</a>
                </p>
            </header>
            <main class="admin-main">
                <div class="admin-content">
                    <div class="admin-content-card">
                        <div class="admin-content-card-body">
                        <p><strong>Date</strong> <?= date('Y-m-d H:i', strtotime($lead['created_at'])) ?></p>
                        <p><strong>Name</strong> <?= htmlspecialchars($lead['name']) ?></p>
                        <p><strong>Email</strong> <a href="mailto:<?= htmlspecialchars($lead['email']) ?>"><?= htmlspecialchars($lead['email']) ?></a></p>
                        <?php if (!empty($lead['phone'])): ?>
                        <p><strong>Phone</strong> <a href="tel:<?= htmlspecialchars($lead['phone']) ?>"><?= htmlspecialchars($lead['phone']) ?></a></p>
                        <?php endif; ?>
                        <?php if (!empty($lead['company'])): ?>
                        <p><strong>Company</strong> <?= htmlspecialchars($lead['company']) ?></p>
                        <?php endif; ?>
                        <p><strong>Source</strong> <?= htmlspecialchars($lead['source'] ?? 'contact') ?></p>
                        <?php if (!empty($lead['message'])): ?>
                        <p><strong>Message</strong><br><?= nl2br(htmlspecialchars($lead['message'])) ?></p>
                        <?php endif; ?>
                        </div>
                    </div>
                    <div class="admin-form-card" style="max-width: 640px;">
                    <?php if (admin_can_edit()): ?>
                    <form method="post" class="admin-form">
                        <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
                        <div class="form-row">
                            <label>Status</label>
                            <select name="status">
                                <?php foreach (['new'=>'New','contacted'=>'Contacted','converted'=>'Converted','archived'=>'Archived'] as $v=>$l): ?>
                                <option value="<?= $v ?>" <?= ($lead['status'] ?? '') === $v ? 'selected' : '' ?>><?= $l ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-row">
                            <label>Admin notes</label>
                            <textarea name="admin_notes" rows="3"><?= htmlspecialchars($lead['admin_notes'] ?? '') ?></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit">Update</button>
                        </div>
                    </form>
                    <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
