<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$quote = null;
$client = null;
$lines = [];
if ($id) {
    $stmt = db()->prepare("SELECT * FROM quotes WHERE id = ?");
    $stmt->execute([$id]);
    $quote = $stmt->fetch();
    if ($quote) {
        $client = db()->prepare("SELECT * FROM clients WHERE id = ?");
        $client->execute([$quote['client_id']]);
        $client = $client->fetch();
        $lines = db()->prepare("SELECT * FROM quote_lines WHERE quote_id = ? ORDER BY sort_order, id");
        $lines->execute([$id]);
        $lines = $lines->fetchAll();
    }
}
if (!$quote || !$client) { header('Location: quotes.php'); exit; }

$invoiceFromQuote = null;
$invStmt = db()->prepare("SELECT id, invoice_number FROM invoices WHERE quote_id = ? LIMIT 1");
$invStmt->execute([$id]);
$invoiceFromQuote = $invStmt->fetch();

$subtotalHT = 0;
foreach ($lines as $l) $subtotalHT += (float)$l['quantity'] * (float)$l['unit_price'];
$taxRate = isset($quote['tax_rate']) && $quote['tax_rate'] !== '' && $quote['tax_rate'] !== null
    ? (float) $quote['tax_rate']
    : (float) get_setting('quote_default_tax_rate', '0');
$taxAmount = $subtotalHT * ($taxRate / 100);
$totalTTC = $subtotalHT + $taxAmount;

$companyName = get_setting('company_name', SITE_NAME);
$companyAddress = get_setting('company_address');
$companyLegalForm = get_setting('company_legal_form');
$companyEmail = get_setting('company_email');
$companyPhone = get_setting('company_phone');
$companyTaxId = get_setting('company_tax_id');
$companyVatNumber = get_setting('company_vat_number');
$companyWebsite = get_setting('company_website');
$companyBankDetails = get_setting('company_bank_details');
$paymentTerms = get_setting('payment_terms', 'Net 30');
$currency = get_setting('company_currency', 'CAD');
$footerLegal = get_setting('quote_footer_legal');
$print = isset($_GET['print']);
$emailSent = isset($_GET['sent']) ? (int) $_GET['sent'] : null;
$pageTitle = 'Quote ' . $quote['quote_number'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> – <?= htmlspecialchars(SITE_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="quote-template.css">
    <style>
    @media print {
        @page { size: A4; margin: 15mm; }
        body { background: #fff !important; padding: 0 !important; }
        .no-print, .admin-layout .admin-sidebar, .admin-topbar, .admin-main-wrap > header { display: none !important; }
        .admin-layout { display: block !important; }
        .admin-main-wrap { margin-left: 0 !important; }
        .quote-template { box-shadow: none !important; border: none !important; border-radius: 0 !important; }
    }
    </style>
    <?php if ($print): ?>
    <style>.no-print{display:none !important;} body{padding:0;}</style>
    <?php endif; ?>
</head>
<body class="<?= $print ? 'print-doc' : 'admin-dashboard' ?>">
<?php if (!$print): ?>
    <div class="admin-layout">
        <?php $currentNav = 'quotes'; include __DIR__ . '/includes/sidebar.php'; ?>
        <div class="admin-main-wrap">
            <header class="admin-topbar admin-topbar-quote no-print">
                <div class="quote-view-head">
                    <a href="quotes.php" class="quote-view-back">← Quotes</a>
                    <h1>Quote <?= htmlspecialchars($quote['quote_number']) ?></h1>
                </div>
                <div class="quote-view-actions">
                    <?php if (admin_can_edit()): ?>
                    <a href="quote-form.php?id=<?= $id ?>" class="btn btn-secondary">Edit</a>
                    <form method="post" action="quote-send-email.php" class="quote-view-action-form"><input type="hidden" name="id" value="<?= $id ?>"><button type="submit" class="btn btn-secondary">Send by email</button></form>
                    <?php if ($invoiceFromQuote): ?>
                    <a href="invoice-view.php?id=<?= (int) $invoiceFromQuote['id'] ?>" class="btn btn-primary">View invoice <?= htmlspecialchars($invoiceFromQuote['invoice_number']) ?></a>
                    <?php elseif (in_array($quote['status'] ?? '', ['accepted', 'sent'], true)): ?>
                    <form method="post" action="quote-mark-paid.php" class="quote-view-action-form"><input type="hidden" name="_csrf" value="<?= csrf_token() ?>"><input type="hidden" name="id" value="<?= $id ?>"><button type="submit" class="btn btn-primary">Mark as paid (create invoice)</button></form>
                    <?php endif; ?>
                    <?php endif; ?>
                    <a href="quote-view.php?id=<?= $id ?>&print=1" target="_blank" class="btn btn-secondary">Print / Save as PDF</a>
                </div>
            </header>
            <main class="admin-main">
                <div class="admin-content admin-content--quote doc-preview">
                    <?php if ($emailSent === 1): ?><div class="alert alert-success">Quote sent by email to client.</div><?php elseif ($emailSent === 0): ?><div class="alert alert-error">Failed to send email. Check Settings → Email.</div><?php endif; ?>
<?php endif; ?>
    <div class="quote-template quote-template--view">
        <div class="qt-doc-title-bar">
            <h2 class="qt-doc-title-text">DEVIS</h2>
        </div>
        <div class="qt-header qt-header-two-col">
            <div class="qt-our-info">
                <div class="qt-our-info-head">
                    <span class="qt-logo">S</span>
                    <strong class="qt-company-name"><?= htmlspecialchars($companyName) ?></strong>
                </div>
                <?php if ($companyEmail): ?><div class="qt-our-info-line qt-our-email"><?= htmlspecialchars($companyEmail) ?></div><?php endif; ?>
            </div>
            <div class="qt-doc-client">
                <div class="qt-doc-ref">
                    <span class="qt-doc-ref-label">DEVIS N°</span>
                    <span class="qt-doc-ref-number"><?= htmlspecialchars($quote['quote_number']) ?></span>
                </div>
                <div class="qt-doc-meta">
                    <div class="qt-meta-row"><span class="qt-meta-label">Date</span><span class="qt-meta-value"><?= date('d/m/Y', strtotime($quote['created_at'])) ?></span></div>
                    <?php if ($quote['valid_until']): ?>
                    <div class="qt-meta-row"><span class="qt-meta-label">Valid until</span><span class="qt-meta-value"><?= date('d/m/Y', strtotime($quote['valid_until'])) ?></span></div>
                    <?php endif; ?>
                </div>
                <div class="qt-client-info">
                    <div class="qt-client-info-label">CLIENT</div>
                    <div class="qt-client-row"><span class="qt-client-field">Raison sociale:</span><span class="qt-client-value"><?= htmlspecialchars($client['company'] ?: $client['contact_name']) ?></span></div>
                    <div class="qt-client-row"><span class="qt-client-field">Contact:</span><span class="qt-client-value"><?= htmlspecialchars($client['contact_name']) ?></span></div>
                    <?php if (!empty($client['address'])): ?><div class="qt-client-row"><span class="qt-client-field">Adresse:</span><span class="qt-client-value"><?= nl2br(htmlspecialchars($client['address'])) ?></span></div><?php endif; ?>
                    <div class="qt-client-row"><span class="qt-client-field">Email:</span><span class="qt-client-value"><?= htmlspecialchars($client['email']) ?></span></div>
                    <?php if (!empty($client['phone'])): ?><div class="qt-client-row"><span class="qt-client-field">Tél:</span><span class="qt-client-value"><?= htmlspecialchars($client['phone']) ?></span></div><?php endif; ?>
                </div>
            </div>
        </div>
        <div class="qt-section qt-items">
            <div class="qt-section-label">DÉTAIL DES PRESTATIONS</div>
            <table class="qt-table">
                <thead>
                    <tr>
                        <th class="qt-col-desc">DESCRIPTION</th>
                        <th class="qt-col-qty">QTÉ</th>
                        <th class="qt-col-price">PRIX UNITAIRE HT (<?= htmlspecialchars($currency) ?>)</th>
                        <th class="qt-col-amount">TOTAL HT (<?= htmlspecialchars($currency) ?>)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lines as $l): $amt = (float)$l['quantity'] * (float)$l['unit_price']; ?>
                    <tr>
                        <td><?= htmlspecialchars($l['description']) ?></td>
                        <td><?= htmlspecialchars($l['quantity']) ?></td>
                        <td><?= number_format((float)$l['unit_price'], 2) ?></td>
                        <td class="qt-amount-cell"><?= number_format($amt, 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="qt-totals-block">
            <div class="qt-total-row qt-total-subtotal">
                <span class="qt-total-label">Total HT (<?= htmlspecialchars($currency) ?>)</span>
                <span class="qt-total-value"><?= number_format($subtotalHT, 2) ?></span>
            </div>
            <?php if ($taxRate > 0): ?>
            <div class="qt-total-row qt-total-tax">
                <span class="qt-total-label">TVA (<?= number_format($taxRate, 1) ?>%)</span>
                <span class="qt-total-value"><?= number_format($taxAmount, 2) ?></span>
            </div>
            <div class="qt-total-row qt-total-ttc">
                <span class="qt-total-label">Total TTC (<?= htmlspecialchars($currency) ?>)</span>
                <span class="qt-total-value"><?= number_format($totalTTC, 2) ?></span>
            </div>
            <?php else: ?>
            <div class="qt-total-row qt-total-ttc">
                <span class="qt-total-label">Total (<?= htmlspecialchars($currency) ?>)</span>
                <span class="qt-total-value"><?= number_format($subtotalHT, 2) ?></span>
            </div>
            <?php endif; ?>
        </div>
        <?php if ($paymentTerms): ?>
        <div class="qt-payment-terms">
            <span class="qt-payment-label">Conditions de paiement:</span>
            <span class="qt-payment-value"><?= htmlspecialchars($paymentTerms) ?></span>
        </div>
        <?php endif; ?>
        <?php if (!empty($quote['notes'])): ?>
        <div class="qt-notes">
            <label>Notes</label>
            <div><?= nl2br(htmlspecialchars($quote['notes'])) ?></div>
        </div>
        <?php endif; ?>
        <footer class="qt-footer">
            <div class="qt-footer-divider"></div>
            <div class="qt-footer-inner qt-footer-centered">
                <div class="qt-footer-company"><?= htmlspecialchars($companyName) ?></div>
                <?php if ($companyEmail): ?><div class="qt-footer-email"><?= htmlspecialchars($companyEmail) ?></div><?php endif; ?>
                <div class="qt-footer-thanks">Merci pour votre confiance.</div>
            </div>
        </footer>
    </div>
<?php if (!$print): ?>
                </div>
            </main>
        </div>
    </div>
<?php endif; ?>
<?php if ($print): ?>
<script>window.onload = function() { window.print(); }</script>
<?php endif; ?>
</body>
</html>
