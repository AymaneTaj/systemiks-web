<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();
admin_require_role(['admin', 'editor']);

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$fromQuote = isset($_GET['from']) && $_GET['from'] === 'quote';
$invoice = null;
$lines = [];
if ($id) {
    $stmt = db()->prepare("SELECT * FROM invoices WHERE id = ?");
    $stmt->execute([$id]);
    $invoice = $stmt->fetch();
    if (!$invoice) { header('Location: invoices.php'); exit; }
    $lines = db()->prepare("SELECT * FROM invoice_lines WHERE invoice_id = ? ORDER BY sort_order, id");
    $lines->execute([$id]);
    $lines = $lines->fetchAll();
}

$clients = db()->query("SELECT id, contact_name, company FROM clients ORDER BY contact_name")->fetchAll();
$projects = db()->query("SELECT id, name FROM projects ORDER BY name")->fetchAll();
$servicesCatalog = db()->query("SELECT id, name, description, default_price FROM services ORDER BY sort_order, name")->fetchAll();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validate();
    $client_id = (int) ($_POST['client_id'] ?? 0);
    $project_id = !empty($_POST['project_id']) ? (int) $_POST['project_id'] : null;
    $invoice_number = trim($_POST['invoice_number'] ?? '');
    $status = trim($_POST['status'] ?? 'draft');
    $issue_date = trim($_POST['issue_date'] ?? '') ?: null;
    $due_date = trim($_POST['due_date'] ?? '') ?: null;
    $payment_terms = trim($_POST['payment_terms'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if ($client_id <= 0) $errors[] = 'Select a client.';
    if ($invoice_number === '') $errors[] = 'Invoice number is required.';
    if (!in_array($status, ['draft', 'sent', 'paid', 'overdue'], true)) $status = 'draft';

    $line_descriptions = $_POST['line_description'] ?? [];
    $line_quantities = $_POST['line_quantity'] ?? [];
    $line_prices = $_POST['line_unit_price'] ?? [];
    $line_rows = [];
    for ($i = 0; $i < count($line_descriptions); $i++) {
        $desc = trim($line_descriptions[$i] ?? '');
        if ($desc === '') continue;
        $line_rows[] = [
            'description' => $desc,
            'quantity' => (float)($line_quantities[$i] ?? 1),
            'unit_price' => (float)($line_prices[$i] ?? 0),
        ];
    }

    if (empty($errors)) {
        $pdo = db();
        if ($id) {
            $pdo->prepare("UPDATE invoices SET client_id=?, project_id=?, invoice_number=?, status=?, issue_date=?, due_date=?, payment_terms=?, notes=?, updated_at=CURRENT_TIMESTAMP WHERE id=?")
                ->execute([$client_id, $project_id, $invoice_number, $status, $issue_date, $due_date, $payment_terms, $notes, $id]);
            $pdo->prepare("DELETE FROM invoice_lines WHERE invoice_id = ?")->execute([$id]);
            log_activity('updated', 'invoice', $id, $invoice_number);
        } else {
            $pdo->prepare("INSERT INTO invoices (client_id, project_id, invoice_number, status, issue_date, due_date, payment_terms, notes) VALUES (?,?,?,?,?,?,?,?)")
                ->execute([$client_id, $project_id, $invoice_number, $status, $issue_date, $due_date, $payment_terms, $notes]);
            $invId = (int) $pdo->lastInsertId();
            log_activity('created', 'invoice', $invId, $invoice_number);
        }
        $invId = $id ?? $invId;
        $ins = $pdo->prepare("INSERT INTO invoice_lines (invoice_id, sort_order, description, quantity, unit_price) VALUES (?,?,?,?,?)");
        foreach ($line_rows as $idx => $row) {
            $ins->execute([$invId, $idx, $row['description'], $row['quantity'], $row['unit_price']]);
        }
        header('Location: invoices.php');
        exit;
    }
    $invoice = array_merge($invoice ?? [], compact('client_id', 'project_id', 'invoice_number', 'status', 'issue_date', 'due_date', 'payment_terms', 'notes'));
    $lines = [];
    foreach ($line_descriptions as $i => $d) {
        $lines[] = ['description' => $d, 'quantity' => $line_quantities[$i] ?? 1, 'unit_price' => $line_prices[$i] ?? 0];
    }
}

if (empty($lines)) $lines = [['description' => '', 'quantity' => 1, 'unit_price' => 0]];
if (!$invoice) {
    $invoice = [
        'invoice_number' => next_invoice_number(),
        'status' => 'draft',
        'issue_date' => date('Y-m-d'),
        'due_date' => date('Y-m-d', strtotime('+30 days')),
        'payment_terms' => get_setting('payment_terms', 'Net 30'),
    ];
}

$pageTitle = $id ? 'Edit invoice' : 'New invoice';
$currentNav = 'invoices';
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
                <p class="welcome">
                    <?php if ($fromQuote): ?><span class="badge badge-sent">From quote</span> <?php endif; ?>
                    <a href="invoices.php">← Back to invoices</a>
                </p>
            </header>
            <main class="admin-main">
                <div class="admin-content">
                    <?php if (!empty($errors)): ?>
                    <div class="alert alert-error"><?= implode(' ', array_map('htmlspecialchars', $errors)) ?></div>
                    <?php endif; ?>
                    <form method="post" class="admin-form" id="invoice-form">
                        <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
                        <div class="form-row">
                            <label>Client *</label>
                            <select name="client_id" required>
                                <option value="">— Select —</option>
                                <?php foreach ($clients as $c): ?>
                                <option value="<?= (int)$c['id'] ?>" <?= (int)($invoice['client_id'] ?? 0) === (int)$c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['company'] ? $c['company'] . ' (' . $c['contact_name'] . ')' : $c['contact_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-row">
                            <label>Project</label>
                            <select name="project_id">
                                <option value="">— None —</option>
                                <?php foreach ($projects as $p): ?>
                                <option value="<?= (int)$p['id'] ?>" <?= (int)($invoice['project_id'] ?? 0) === (int)$p['id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-row two-cols">
                            <div><label>Invoice number *</label><input type="text" name="invoice_number" value="<?= htmlspecialchars($invoice['invoice_number'] ?? '') ?>" required></div>
                            <div><label>Status</label><select name="status"><?php foreach (['draft'=>'Draft','sent'=>'Sent','paid'=>'Paid','overdue'=>'Overdue'] as $v=>$l): ?><option value="<?= $v ?>" <?= ($invoice['status'] ?? '') === $v ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
                        </div>
                        <div class="form-row two-cols">
                            <div><label>Issue date</label><input type="date" name="issue_date" value="<?= htmlspecialchars($invoice['issue_date'] ?? '') ?>"></div>
                            <div><label>Due date</label><input type="date" name="due_date" value="<?= htmlspecialchars($invoice['due_date'] ?? '') ?>"></div>
                        </div>
                        <div class="form-row"><label>Payment terms</label><input type="text" name="payment_terms" value="<?= htmlspecialchars($invoice['payment_terms'] ?? '') ?>" placeholder="e.g. Net 30"></div>
                        <div class="form-row">
                            <label>Line items</label>
                            <?php if (!empty($servicesCatalog)): ?>
                            <div class="add-from-catalog" style="margin-bottom:8px;">
                                <select id="inv-catalog-select">
                                    <option value="">— Add from catalog —</option>
                                    <?php foreach ($servicesCatalog as $sv): ?>
                                    <option value="" data-name="<?= htmlspecialchars($sv['name']) ?>" data-price="<?= htmlspecialchars($sv['default_price']) ?>"><?= htmlspecialchars($sv['name']) ?> — <?= number_format((float)$sv['default_price'], 2) ?> $</option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="btn btn-secondary btn-sm" id="inv-add-from-catalog">Add</button>
                            </div>
                            <?php endif; ?>
                            <div class="line-items">
                                <table class="line-items-table">
                                    <thead><tr><th>Description</th><th>Qty</th><th>Unit price</th><th></th></tr></thead>
                                    <tbody id="line-items-tbody">
                                        <?php foreach ($lines as $l): ?>
                                        <tr>
                                            <td><input type="text" name="line_description[]" value="<?= htmlspecialchars($l['description']) ?>" placeholder="Description"></td>
                                            <td><input type="number" name="line_quantity[]" value="<?= htmlspecialchars($l['quantity']) ?>" min="0" step="0.01" style="width:70px"></td>
                                            <td><input type="number" name="line_unit_price[]" value="<?= htmlspecialchars($l['unit_price']) ?>" min="0" step="0.01" style="width:90px"></td>
                                            <td><button type="button" class="btn-remove-line">×</button></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-secondary btn-sm" id="add-line">+ Add line</button>
                            </div>
                        </div>
                        <div class="form-row"><label>Notes</label><textarea name="notes" rows="2"><?= htmlspecialchars($invoice['notes'] ?? '') ?></textarea></div>
                        <div class="form-actions">
                            <button type="submit"><?= $id ? 'Save invoice' : 'Create invoice' ?></button>
                            <a href="invoices.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
    <script>
    function addInvoiceLine(desc, qty, price) {
        var tbody = document.getElementById('line-items-tbody');
        var tr = document.createElement('tr');
        var descVal = (desc || '').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        var qtyVal = (qty == null || qty === '') ? 1 : parseFloat(qty);
        var priceVal = (price == null || price === '') ? 0 : parseFloat(price);
        tr.innerHTML = '<td><input type="text" name="line_description[]" placeholder="Description" value="' + descVal + '"></td><td><input type="number" name="line_quantity[]" value="' + qtyVal + '" min="0" step="0.01" style="width:70px"></td><td><input type="number" name="line_unit_price[]" value="' + priceVal + '" min="0" step="0.01" style="width:90px"></td><td><button type="button" class="btn-remove-line">×</button></td>';
        tbody.appendChild(tr);
        tr.querySelector('.btn-remove-line').onclick = function() { tr.remove(); };
    }
    document.getElementById('add-line').onclick = function() { addInvoiceLine('', 1, 0); };
    var invCatalogBtn = document.getElementById('inv-add-from-catalog');
    if (invCatalogBtn) {
        invCatalogBtn.onclick = function() {
            var sel = document.getElementById('inv-catalog-select');
            var opt = sel.options[sel.selectedIndex];
            if (!opt || !opt.getAttribute('data-name')) return;
            addInvoiceLine(opt.getAttribute('data-name'), 1, opt.getAttribute('data-price'));
            sel.selectedIndex = 0;
        };
    }
    document.getElementById('line-items-tbody').querySelectorAll('.btn-remove-line').forEach(function(btn) {
        btn.onclick = function() { btn.closest('tr').remove(); };
    });
    </script>
</body>
</html>
