<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();
admin_require_role(['admin', 'editor']);

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$quote = null;
$lines = [];
$selectedClient = null;
if ($id) {
    $stmt = db()->prepare("SELECT * FROM quotes WHERE id = ?");
    $stmt->execute([$id]);
    $quote = $stmt->fetch();
    if (!$quote) { header('Location: quotes.php'); exit; }
    $lines = db()->prepare("SELECT * FROM quote_lines WHERE quote_id = ? ORDER BY sort_order, id");
    $lines->execute([$id]);
    $lines = $lines->fetchAll();
    if ($quote['client_id']) {
        $st = db()->prepare("SELECT * FROM clients WHERE id = ?");
        $st->execute([$quote['client_id']]);
        $selectedClient = $st->fetch();
    }
}

$clients = db()->query("SELECT id, contact_name, company, email, phone, address FROM clients ORDER BY contact_name")->fetchAll();
$projects = db()->query("SELECT id, name FROM projects ORDER BY name")->fetchAll();
$servicesCatalog = db()->query("SELECT id, name, description, default_price FROM services ORDER BY sort_order, name")->fetchAll();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validate();
    $client_id = (int) ($_POST['client_id'] ?? 0);
    $project_id = !empty($_POST['project_id']) ? (int) $_POST['project_id'] : null;
    $quote_number = trim($_POST['quote_number'] ?? '');
    $status = trim($_POST['status'] ?? 'draft');
    $valid_until = trim($_POST['valid_until'] ?? '') ?: null;
    $tax_rate = trim($_POST['tax_rate'] ?? '');
    $tax_rate = $tax_rate !== '' ? (float) $tax_rate : null;
    $notes = trim($_POST['notes'] ?? '');

    if ($client_id <= 0) $errors[] = 'Select a client.';
    if ($quote_number === '') $errors[] = 'Quote number is required.';
    if (!in_array($status, ['draft', 'sent', 'accepted', 'declined', 'paid'], true)) $status = 'draft';

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
            $pdo->prepare("UPDATE quotes SET client_id=?, project_id=?, quote_number=?, status=?, valid_until=?, tax_rate=?, notes=?, updated_at=CURRENT_TIMESTAMP WHERE id=?")
                ->execute([$client_id, $project_id, $quote_number, $status, $valid_until, $tax_rate, $notes, $id]);
            $pdo->prepare("DELETE FROM quote_lines WHERE quote_id = ?")->execute([$id]);
            log_activity('updated', 'quote', $id, $quote_number);
        } else {
            $pdo->prepare("INSERT INTO quotes (client_id, project_id, quote_number, status, valid_until, tax_rate, notes) VALUES (?,?,?,?,?,?,?)")
                ->execute([$client_id, $project_id, $quote_number, $status, $valid_until, $tax_rate, $notes]);
            $quoteId = (int) $pdo->lastInsertId();
            log_activity('created', 'quote', $quoteId, $quote_number);
        }
        $quoteId = $id ?? $quoteId;
        $ins = $pdo->prepare("INSERT INTO quote_lines (quote_id, sort_order, description, quantity, unit_price) VALUES (?,?,?,?,?)");
        foreach ($line_rows as $idx => $row) {
            $ins->execute([$quoteId, $idx, $row['description'], $row['quantity'], $row['unit_price']]);
        }
        header('Location: quotes.php');
        exit;
    }
    $quote = array_merge($quote ?? [], compact('client_id', 'project_id', 'quote_number', 'status', 'valid_until', 'tax_rate', 'notes'));
    $lines = array_map(function ($i) use ($line_descriptions, $line_quantities, $line_prices) {
        return [
            'description' => $line_descriptions[$i] ?? '',
            'quantity' => $line_quantities[$i] ?? 1,
            'unit_price' => $line_prices[$i] ?? 0,
        ];
    }, array_keys($line_descriptions ?: []));
    if (empty($lines)) $lines = [['description' => '', 'quantity' => 1, 'unit_price' => 0]];
    if ($quote && !empty($quote['client_id'])) {
        $st = db()->prepare("SELECT * FROM clients WHERE id = ?");
        $st->execute([$quote['client_id']]);
        $selectedClient = $st->fetch();
    }
}

if (empty($lines)) $lines = [['description' => '', 'quantity' => 1, 'unit_price' => 0]];
if (!$quote) $quote = ['quote_number' => next_quote_number(), 'status' => 'draft', 'valid_until' => date('Y-m-d', strtotime('+' . get_setting('quote_validity_days', '30') . ' days')), 'tax_rate' => get_setting('quote_default_tax_rate', '0')];

$companyName = get_setting('company_name', SITE_NAME);
$companyAddress = get_setting('company_address');
$companyEmail = get_setting('company_email');
$companyPhone = get_setting('company_phone');
$companyTaxId = get_setting('company_tax_id');
$quoteDate = $id && !empty($quote['created_at']) ? date('Y-m-d', strtotime($quote['created_at'])) : date('Y-m-d');

$pageTitle = $id ? 'Edit quote' : 'New quote';
$currentNav = 'quotes';
$clientsJson = json_encode(array_map(function ($c) {
    return ['id' => (int)$c['id'], 'contact_name' => $c['contact_name'], 'company' => $c['company'] ?? '', 'email' => $c['email'], 'phone' => $c['phone'] ?? '', 'address' => $c['address'] ?? ''];
}, $clients));
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
    <link rel="stylesheet" href="quote-template.css">
</head>
<body class="admin-dashboard">
    <div class="admin-layout">
        <?php include __DIR__ . '/includes/sidebar.php'; ?>
        <div class="admin-main-wrap">
            <header class="admin-topbar">
                <h1><?= htmlspecialchars($pageTitle) ?></h1>
                <p class="welcome"><a href="quotes.php">← Back to quotes</a></p>
            </header>
            <main class="admin-main">
                <div class="admin-content admin-content--quote">
                    <?php if (!empty($errors)): ?>
                    <div class="alert alert-error"><?= implode(' ', array_map('htmlspecialchars', $errors)) ?></div>
                    <?php endif; ?>
                    <form method="post" id="quote-form" class="quote-template-form">
                        <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
                        <div class="quote-template">
                            <!-- Company header -->
                            <div class="qt-header">
                                <div class="qt-company">
                                    <span class="qt-logo">S</span>
                                    <strong class="qt-company-name"><?= htmlspecialchars($companyName) ?></strong>
                                    <?php if ($companyAddress): ?><br><span class="qt-company-detail"><?= nl2br(htmlspecialchars($companyAddress)) ?></span><?php endif; ?>
                                    <?php if ($companyTaxId): ?><br><span class="qt-company-detail"><?= htmlspecialchars($companyTaxId) ?></span><?php endif; ?>
                                    <?php if ($companyEmail || $companyPhone): ?>
                                    <br><span class="qt-company-detail"><?= htmlspecialchars($companyEmail) ?><?= $companyEmail && $companyPhone ? ' | ' : '' ?><?= htmlspecialchars($companyPhone) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="qt-doc-box">
                                    <div class="qt-doc-title">Quote</div>
                                    <input type="text" name="quote_number" class="qt-doc-number" value="<?= htmlspecialchars($quote['quote_number'] ?? '') ?>" placeholder="SQ-2026-001" required>
                                    <div class="qt-doc-meta">
                                        <div class="qt-meta-row"><label>Date</label><span class="qt-meta-value"><?= htmlspecialchars($quoteDate) ?></span></div>
                                        <div class="qt-meta-row"><label>Valid until</label><input type="date" name="valid_until" class="qt-input-inline" value="<?= htmlspecialchars($quote['valid_until'] ?? '') ?>"></div>
                                        <div class="qt-meta-row"><label>TVA %</label><input type="number" name="tax_rate" class="qt-input-inline" value="<?= isset($quote['tax_rate']) && $quote['tax_rate'] !== '' && $quote['tax_rate'] !== null ? htmlspecialchars($quote['tax_rate']) : '' ?>" placeholder="<?= htmlspecialchars(get_setting('quote_default_tax_rate', '0')) ?>" min="0" max="100" step="0.01"></div>
                                        <div class="qt-meta-row"><label>Status</label>
                                            <select name="status" class="qt-select-inline">
                                                <?php foreach (['draft'=>'Draft','sent'=>'Sent','accepted'=>'Accepted','declined'=>'Declined','paid'=>'Paid'] as $v=>$l): ?>
                                                <option value="<?= $v ?>" <?= ($quote['status'] ?? '') === $v ? 'selected' : '' ?>><?= $l ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bill to -->
                            <div class="qt-section qt-billto">
                                <div class="qt-section-label">Bill to</div>
                                <div class="qt-billto-select">
                                    <label>Client *</label>
                                    <select name="client_id" id="qt-client-id" required>
                                        <option value="">— Select client —</option>
                                        <?php foreach ($clients as $c): ?>
                                        <option value="<?= (int)$c['id'] ?>" <?= (int)($quote['client_id'] ?? 0) === (int)$c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['company'] ? $c['company'] . ' · ' . $c['contact_name'] : $c['contact_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="qt-billto-details" id="qt-billto-details">
                                    <?php if ($selectedClient): ?>
                                    <div class="qt-billto-name"><?= htmlspecialchars($selectedClient['contact_name']) ?></div>
                                    <?php if ($selectedClient['company']): ?><div><?= htmlspecialchars($selectedClient['company']) ?></div><?php endif; ?>
                                    <div><?= htmlspecialchars($selectedClient['email']) ?></div>
                                    <?php if ($selectedClient['phone']): ?><div><?= htmlspecialchars($selectedClient['phone']) ?></div><?php endif; ?>
                                    <?php if ($selectedClient['address']): ?><div class="qt-billto-address"><?= nl2br(htmlspecialchars($selectedClient['address'])) ?></div><?php endif; ?>
                                    <?php else: ?>
                                    <div class="qt-billto-placeholder">Select a client above.</div>
                                    <?php endif; ?>
                                </div>
                                <div class="qt-project-row">
                                    <label>Project</label>
                                    <select name="project_id" class="qt-select-inline">
                                        <option value="">— None —</option>
                                        <?php foreach ($projects as $p): ?>
                                        <option value="<?= (int)$p['id'] ?>" <?= (int)($quote['project_id'] ?? 0) === (int)$p['id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Line items -->
                            <div class="qt-section qt-items">
                                <div class="qt-section-label">Items</div>
                                <?php if (!empty($servicesCatalog)): ?>
                                <div class="add-from-catalog" style="margin-bottom:10px;">
                                    <label>Add from catalog:</label>
                                    <select id="qt-catalog-select">
                                        <option value="">— Select a service —</option>
                                        <?php foreach ($servicesCatalog as $sv): ?>
                                        <option value="<?= (int)$sv['id'] ?>" data-name="<?= htmlspecialchars($sv['name']) ?>" data-price="<?= htmlspecialchars($sv['default_price']) ?>"><?= htmlspecialchars($sv['name']) ?> — <?= number_format((float)$sv['default_price'], 2) ?> $</option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="button" class="btn btn-secondary btn-sm" id="qt-add-from-catalog">Add</button>
                                </div>
                                <?php endif; ?>
                                <table class="qt-table">
                                    <thead>
                                        <tr>
                                            <th class="qt-col-desc">Description</th>
                                            <th class="qt-col-qty">Qty</th>
                                            <th class="qt-col-price">Unit price (CAD)</th>
                                            <th class="qt-col-amount">Amount (CAD)</th>
                                            <th class="qt-col-action"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="qt-line-tbody">
                                        <?php foreach ($lines as $l): $amt = (float)($l['quantity'] ?? 0) * (float)($l['unit_price'] ?? 0); ?>
                                        <tr class="qt-line">
                                            <td><input type="text" name="line_description[]" value="<?= htmlspecialchars($l['description']) ?>" placeholder="Service or deliverable"></td>
                                            <td><input type="number" name="line_quantity[]" class="qt-input-qty" value="<?= htmlspecialchars($l['quantity']) ?>" min="0" step="0.01"></td>
                                            <td><input type="number" name="line_unit_price[]" class="qt-input-price" value="<?= htmlspecialchars($l['unit_price']) ?>" min="0" step="0.01" placeholder="0.00"></td>
                                            <td class="qt-amount-cell"><span class="qt-amount"><?= number_format($amt, 2) ?></span> $</td>
                                            <td><button type="button" class="qt-btn-remove">×</button></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <button type="button" class="qt-add-line" id="qt-add-line">+ Add line</button>
                            </div>

                            <!-- Total -->
                            <div class="qt-total-row">
                                <span class="qt-total-label">Total (CAD)</span>
                                <span class="qt-total-value" id="qt-total-value">0.00</span>
                                <span class="qt-currency">$</span>
                            </div>

                            <!-- Notes -->
                            <div class="qt-notes">
                                <label>Notes</label>
                                <textarea name="notes" rows="3" placeholder="Terms, validity, or additional notes."><?= htmlspecialchars($quote['notes'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <div class="quote-template-actions">
                            <button type="submit" class="btn btn-primary"><?= $id ? 'Save quote' : 'Create quote' ?></button>
                            <a href="quotes.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
    <script>
    (function() {
        var clients = <?= $clientsJson ?>;
        var clientSelect = document.getElementById('qt-client-id');
        var detailsEl = document.getElementById('qt-billto-details');

        function renderBillTo(client) {
            if (!client) {
                detailsEl.innerHTML = '<div class="qt-billto-placeholder">Select a client above.</div>';
                return;
            }
            var html = '<div class="qt-billto-name">' + escapeHtml(client.contact_name) + '</div>';
            if (client.company) html += '<div>' + escapeHtml(client.company) + '</div>';
            html += '<div>' + escapeHtml(client.email) + '</div>';
            if (client.phone) html += '<div>' + escapeHtml(client.phone) + '</div>';
            if (client.address) html += '<div class="qt-billto-address">' + escapeHtml(client.address).replace(/\n/g, '<br>') + '</div>';
            detailsEl.innerHTML = html;
        }
        function escapeHtml(s) { var d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

        clientSelect.addEventListener('change', function() {
            var id = parseInt(this.value, 10);
            var client = clients.find(function(c) { return c.id === id; });
            renderBillTo(client || null);
        });

        function updateLineAmounts() {
            var total = 0;
            document.querySelectorAll('#qt-line-tbody .qt-line').forEach(function(tr) {
                var qty = parseFloat(tr.querySelector('.qt-input-qty').value) || 0;
                var price = parseFloat(tr.querySelector('.qt-input-price').value) || 0;
                var amt = qty * price;
                total += amt;
                tr.querySelector('.qt-amount').textContent = amt.toFixed(2);
            });
            document.getElementById('qt-total-value').textContent = total.toFixed(2);
        }

        document.getElementById('qt-line-tbody').addEventListener('input', updateLineAmounts);

        function addQuoteLine(desc, qty, price) {
            var tbody = document.getElementById('qt-line-tbody');
            var tr = document.createElement('tr');
            tr.className = 'qt-line';
            var descVal = (desc || '').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            var qtyVal = (qty == null || qty === '') ? 1 : parseFloat(qty);
            var priceVal = (price == null || price === '') ? 0 : parseFloat(price);
            tr.innerHTML = '<td><input type="text" name="line_description[]" placeholder="Service or deliverable" value="' + descVal + '"></td><td><input type="number" name="line_quantity[]" class="qt-input-qty" value="' + qtyVal + '" min="0" step="0.01"></td><td><input type="number" name="line_unit_price[]" class="qt-input-price" value="' + priceVal + '" min="0" step="0.01" placeholder="0.00"></td><td class="qt-amount-cell"><span class="qt-amount">' + (qtyVal * priceVal).toFixed(2) + '</span> $</td><td><button type="button" class="qt-btn-remove">×</button></td>';
            tbody.appendChild(tr);
            tr.querySelector('.qt-btn-remove').onclick = function() { tr.remove(); updateLineAmounts(); };
            updateLineAmounts();
        }

        document.getElementById('qt-add-line').onclick = function() { addQuoteLine('', 1, 0); };

        var addFromCatalogBtn = document.getElementById('qt-add-from-catalog');
        if (addFromCatalogBtn) {
            addFromCatalogBtn.onclick = function() {
                var sel = document.getElementById('qt-catalog-select');
                var opt = sel.options[sel.selectedIndex];
                if (!opt || !opt.value) return;
                addQuoteLine(opt.getAttribute('data-name'), 1, opt.getAttribute('data-price'));
                sel.selectedIndex = 0;
            };
        }

        document.getElementById('qt-line-tbody').addEventListener('click', function(e) {
            if (e.target.classList.contains('qt-btn-remove')) {
                e.target.closest('tr').remove();
                updateLineAmounts();
            }
        });

        updateLineAmounts();
    })();
    </script>
</body>
</html>
