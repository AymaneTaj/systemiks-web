<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_require_login();
admin_require_role(['admin']);

$keys = [
    'company_name' => 'Company name',
    'company_address' => 'Company address',
    'company_legal_form' => 'Legal form (e.g. SARL, SAS, SASU)',
    'company_email' => 'Company email',
    'company_phone' => 'Company phone',
    'company_tax_id' => 'Tax ID / SIRET (14 digits)',
    'company_vat_number' => 'VAT number (TVA)',
    'company_website' => 'Website URL',
    'company_bank_details' => 'Bank details (IBAN, BIC, bank name – for quote/invoice footer)',
    'company_currency' => 'Currency code (e.g. CAD, EUR, USD)',
    'quote_validity_days' => 'Quote validity (days)',
    'quote_default_tax_rate' => 'Default quote tax rate % (e.g. 20 for 20% TVA)',
    'quote_footer_legal' => 'Legal mention (quote/invoice footer, optional)',
    'payment_terms' => 'Default payment terms',
    'quote_prefix' => 'Quote number prefix',
    'invoice_prefix' => 'Invoice number prefix',
];

$smtpKeys = [
    'smtp_host' => 'SMTP host',
    'smtp_port' => 'SMTP port',
    'smtp_user' => 'SMTP username',
    'smtp_password' => 'SMTP password (leave blank to keep current)',
    'smtp_encryption' => 'Encryption (tls, ssl, or leave empty)',
    'smtp_from_email' => 'From email',
    'smtp_from_name' => 'From name',
    'notify_leads_email' => 'Notify this email on new lead (optional)',
];

$saved = false;
$testEmailResult = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validate();
    if (isset($_POST['test_email_to'])) {
        $testTo = trim($_POST['test_email_to'] ?? '');
        if ($testTo !== '') {
            $ok = send_systemiks_mail(
                $testTo,
                'Test email from ' . SITE_NAME,
                'This is a test email. If you received it, SMTP/email is working.',
                '<p>This is a test email. If you received it, SMTP/email is working.</p>'
            );
            $testEmailResult = $ok ? 'success' : 'error';
        }
    } else {
        foreach (array_keys($keys) as $key) {
            $value = trim($_POST[$key] ?? '');
            set_setting($key, $value);
        }
        foreach (array_keys($smtpKeys) as $key) {
            if ($key === 'smtp_password') {
                $v = $_POST[$key] ?? '';
                if ($v !== '') set_setting($key, $v);
            } else {
                set_setting($key, trim($_POST[$key] ?? ''));
            }
        }
        $saved = true;
        $activeTab = preg_match('/^panel-(company|quotes|email)$/', $_POST['active_tab'] ?? '') ? $_POST['active_tab'] : 'panel-company';
        header('Location: /admin/settings.php?saved=1&tab=' . urlencode($activeTab));
        exit;
    }
}

$activeTab = isset($_GET['tab']) && preg_match('/^panel-(company|quotes|email)$/', $_GET['tab']) ? $_GET['tab'] : 'panel-company';
if (isset($_GET['saved'])) $saved = true;

$settings = [];
foreach (array_keys($keys) as $key) {
    $settings[$key] = get_setting($key);
}
foreach (array_keys($smtpKeys) as $key) {
    if ($key !== 'smtp_password') $settings[$key] = get_setting($key);
}

$pageTitle = 'Settings';
$currentNav = 'settings';
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
                <p class="welcome">Company info and defaults for quotes &amp; invoices.</p>
            </header>
            <main class="admin-main">
                <div class="admin-content">
                    <?php if ($saved): ?>
                    <div class="alert alert-success">Settings saved.</div>
                    <?php endif; ?>
                    <div class="settings-tabs" role="tablist">
                        <button type="button" class="settings-tab<?= $activeTab === 'panel-company' ? ' active' : '' ?>" role="tab" id="tab-company" aria-selected="<?= $activeTab === 'panel-company' ? 'true' : 'false' ?>" aria-controls="panel-company" data-panel="panel-company">Company</button>
                        <button type="button" class="settings-tab<?= $activeTab === 'panel-quotes' ? ' active' : '' ?>" role="tab" id="tab-quotes" aria-selected="<?= $activeTab === 'panel-quotes' ? 'true' : 'false' ?>" aria-controls="panel-quotes" data-panel="panel-quotes">Quotes &amp; invoices</button>
                        <button type="button" class="settings-tab<?= $activeTab === 'panel-email' ? ' active' : '' ?>" role="tab" id="tab-email" aria-selected="<?= $activeTab === 'panel-email' ? 'true' : 'false' ?>" aria-controls="panel-email" data-panel="panel-email">Email</button>
                    </div>
                    <div class="admin-form-card">
                    <form method="post" class="admin-form">
                        <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
                        <input type="hidden" name="active_tab" id="settings-active-tab" value="<?= htmlspecialchars($activeTab) ?>">
                        <div id="panel-company" class="settings-panel<?= $activeTab === 'panel-company' ? ' active' : '' ?>" role="tabpanel" aria-labelledby="tab-company"<?= $activeTab !== 'panel-company' ? ' hidden' : '' ?>>
                            <h3 class="settings-panel-title">Company (quote &amp; invoice header)</h3>
                            <?php foreach (['company_name','company_address','company_legal_form','company_tax_id','company_vat_number','company_email','company_phone','company_website','company_bank_details'] as $key): ?>
                            <div class="form-row">
                                <label><?= htmlspecialchars($keys[$key]) ?></label>
                                <?php if (strpos($key, 'address') !== false || $key === 'company_bank_details'): ?>
                                <textarea name="<?= htmlspecialchars($key) ?>" rows="<?= $key === 'company_bank_details' ? '3' : '2' ?>"><?= htmlspecialchars($settings[$key] ?? '') ?></textarea>
                                <?php else: ?>
                                <input type="text" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($settings[$key] ?? '') ?>">
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div id="panel-quotes" class="settings-panel<?= $activeTab === 'panel-quotes' ? ' active' : '' ?>" role="tabpanel" aria-labelledby="tab-quotes"<?= $activeTab !== 'panel-quotes' ? ' hidden' : '' ?>>
                            <h3 class="settings-panel-title">Quote &amp; invoice defaults</h3>
                            <?php foreach (['company_currency','quote_validity_days','quote_default_tax_rate','payment_terms','quote_prefix','invoice_prefix','quote_footer_legal'] as $key): ?>
                            <div class="form-row">
                                <label><?= htmlspecialchars($keys[$key]) ?></label>
                                <?php if ($key === 'quote_footer_legal'): ?>
                                <textarea name="<?= htmlspecialchars($key) ?>" rows="2"><?= htmlspecialchars($settings[$key] ?? '') ?></textarea>
                                <?php else: ?>
                                <input type="text" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($settings[$key] ?? '') ?>">
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div id="panel-email" class="settings-panel<?= $activeTab === 'panel-email' ? ' active' : '' ?>" role="tabpanel" aria-labelledby="tab-email"<?= $activeTab !== 'panel-email' ? ' hidden' : '' ?>>
                            <h3 class="settings-panel-title">Email (SMTP)</h3>
                            <p class="form-hint">Leave SMTP host empty to use PHP <code>mail()</code>. Otherwise outgoing email is sent via SMTP.</p>
                            <?php foreach (['smtp_host','smtp_port','smtp_user','smtp_encryption','smtp_from_email','smtp_from_name','notify_leads_email'] as $key): ?>
                            <div class="form-row">
                                <label><?= htmlspecialchars($smtpKeys[$key]) ?></label>
                                <?php if ($key === 'smtp_port'): ?>
                                <input type="number" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($settings[$key] ?? '587') ?>" placeholder="587">
                                <?php elseif ($key === 'smtp_encryption'): ?>
                                <select name="<?= htmlspecialchars($key) ?>">
                                    <option value="">None</option>
                                    <option value="tls" <?= ($settings[$key] ?? '') === 'tls' ? 'selected' : '' ?>>TLS</option>
                                    <option value="ssl" <?= ($settings[$key] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                                </select>
                                <?php else: ?>
                                <input type="text" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($settings[$key] ?? '') ?>">
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                            <div class="form-row">
                                <label>SMTP password</label>
                                <input type="password" name="smtp_password" value="" autocomplete="new-password" placeholder="Leave blank to keep current">
                            </div>
                            <div class="settings-test-email">
                                <h4 class="settings-panel-title">Test email</h4>
                                <?php if ($testEmailResult === 'success'): ?>
                                <div class="alert alert-success">Test email sent. Check the inbox (and spam).</div>
                                <?php elseif ($testEmailResult === 'error'): ?>
                                <div class="alert alert-error">Failed to send test email. Check SMTP settings and server logs.</div>
                                <?php endif; ?>
                                <form method="post" class="admin-form">
                                    <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
                                    <input type="hidden" name="test_email" value="1">
                                    <div class="form-row">
                                        <label>Send test to</label>
                                        <input type="email" name="test_email_to" required placeholder="your@email.com">
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit">Send test email</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="form-actions settings-save-bar">
                            <button type="submit">Save settings</button>
                        </div>
                    </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
    (function() {
        var tabs = document.querySelectorAll('.settings-tab');
        var panels = document.querySelectorAll('.settings-panel');
        var activeTabInput = document.getElementById('settings-active-tab');
        function showPanel(panelId) {
            tabs.forEach(function(t) { t.classList.remove('active'); t.setAttribute('aria-selected', 'false'); });
            panels.forEach(function(p) { p.classList.remove('active'); p.hidden = true; });
            var tab = document.querySelector('.settings-tab[data-panel="' + panelId + '"]');
            var panel = document.getElementById(panelId);
            if (tab) { tab.classList.add('active'); tab.setAttribute('aria-selected', 'true'); }
            if (panel) { panel.classList.add('active'); panel.hidden = false; }
            if (activeTabInput) activeTabInput.value = panelId;
        }
        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() { showPanel(tab.getAttribute('data-panel')); });
        });
        var hash = window.location.hash.replace('#', '');
        if (hash && (hash === 'panel-company' || hash === 'panel-quotes' || hash === 'panel-email')) showPanel(hash);
    })();
    </script>
</body>
</html>
