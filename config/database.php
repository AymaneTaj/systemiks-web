<?php
/**
 * Systemiks - Database (SQLite for admin)
 */
$configDir = dirname(__DIR__);
$dataDir = $configDir . DIRECTORY_SEPARATOR . 'data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}
define('DB_PATH', $dataDir . DIRECTORY_SEPARATOR . 'systemiks.sqlite');

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO('sqlite:' . DB_PATH, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}

function init_db(): void {
    $pdo = db();
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS admin_users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username VARCHAR(64) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            email VARCHAR(255),
            role VARCHAR(32) DEFAULT 'admin',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    try {
        $pdo->exec("ALTER TABLE admin_users ADD COLUMN role VARCHAR(32) DEFAULT 'admin'");
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'duplicate') === false) throw $e;
    }
    $pdo->exec("UPDATE admin_users SET role = 'admin' WHERE role IS NULL OR role = ''");
    $stmt = $pdo->query("SELECT COUNT(*) FROM admin_users");
    if ((int) $stmt->fetchColumn() === 0) {
        $pdo->prepare("INSERT INTO admin_users (username, password_hash, email, role) VALUES (?, ?, ?, 'admin')")
            ->execute(['admin', password_hash('admin123', PASSWORD_DEFAULT), ADMIN_EMAIL]);
    }

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS clients (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            company VARCHAR(255),
            contact_name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(64),
            address TEXT,
            notes TEXT,
            portal_enabled INTEGER DEFAULT 0,
            portal_token VARCHAR(64) UNIQUE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    try { $pdo->exec("ALTER TABLE clients ADD COLUMN portal_enabled INTEGER DEFAULT 0"); } catch (PDOException $e) { if (strpos($e->getMessage(), 'duplicate') === false) throw $e; }
    try { $pdo->exec("ALTER TABLE clients ADD COLUMN portal_token VARCHAR(64)"); } catch (PDOException $e) { if (strpos($e->getMessage(), 'duplicate') === false) throw $e; }
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS projects (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            client_id INTEGER,
            name VARCHAR(255) NOT NULL,
            status VARCHAR(32) DEFAULT 'lead',
            started_at DATE,
            ended_at DATE,
            closed_at DATETIME,
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (client_id) REFERENCES clients(id)
        )
    ");
    try { $pdo->exec("ALTER TABLE projects ADD COLUMN closed_at DATETIME"); } catch (PDOException $e) { if (strpos($e->getMessage(), 'duplicate') === false) throw $e; }
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS project_tasks (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            project_id INTEGER NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            status VARCHAR(32) DEFAULT 'todo',
            sort_order INTEGER DEFAULT 0,
            due_date DATE,
            priority VARCHAR(16),
            assigned_to INTEGER,
            completed_at DATETIME,
            client_action INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
            FOREIGN KEY (assigned_to) REFERENCES admin_users(id)
        )
    ");
    try { $pdo->exec("ALTER TABLE project_tasks ADD COLUMN client_action INTEGER DEFAULT 0"); } catch (PDOException $e) { if (strpos($e->getMessage(), 'duplicate') === false) throw $e; }
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS project_task_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            project_task_id INTEGER NOT NULL,
            title VARCHAR(255) NOT NULL,
            is_done INTEGER DEFAULT 0,
            sort_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (project_task_id) REFERENCES project_tasks(id) ON DELETE CASCADE
        )
    ");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS quotes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            client_id INTEGER NOT NULL,
            project_id INTEGER,
            quote_number VARCHAR(32) NOT NULL UNIQUE,
            status VARCHAR(32) DEFAULT 'draft',
            valid_until DATE,
            tax_rate REAL,
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (client_id) REFERENCES clients(id),
            FOREIGN KEY (project_id) REFERENCES projects(id)
        )
    ");
    try {
        $pdo->exec("ALTER TABLE quotes ADD COLUMN tax_rate REAL");
    } catch (Throwable $e) { /* column may already exist */ }
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS quote_lines (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            quote_id INTEGER NOT NULL,
            sort_order INTEGER DEFAULT 0,
            description TEXT NOT NULL,
            quantity REAL NOT NULL DEFAULT 1,
            unit_price REAL NOT NULL DEFAULT 0,
            FOREIGN KEY (quote_id) REFERENCES quotes(id) ON DELETE CASCADE
        )
    ");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS invoices (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            client_id INTEGER NOT NULL,
            project_id INTEGER,
            quote_id INTEGER,
            invoice_number VARCHAR(32) NOT NULL UNIQUE,
            status VARCHAR(32) DEFAULT 'draft',
            issue_date DATE,
            due_date DATE,
            paid_at DATETIME,
            payment_terms VARCHAR(128),
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (client_id) REFERENCES clients(id),
            FOREIGN KEY (project_id) REFERENCES projects(id),
            FOREIGN KEY (quote_id) REFERENCES quotes(id)
        )
    ");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS invoice_lines (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            invoice_id INTEGER NOT NULL,
            sort_order INTEGER DEFAULT 0,
            description TEXT NOT NULL,
            quantity REAL NOT NULL DEFAULT 1,
            unit_price REAL NOT NULL DEFAULT 0,
            FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE
        )
    ");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS leads (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(64),
            company VARCHAR(255),
            message TEXT,
            source VARCHAR(64) DEFAULT 'contact',
            status VARCHAR(32) DEFAULT 'new',
            admin_notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS services (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            default_price REAL NOT NULL DEFAULT 0,
            sort_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS activity_log (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            action VARCHAR(64) NOT NULL,
            entity_type VARCHAR(32) NOT NULL,
            entity_id INTEGER,
            details TEXT,
            admin_user_id INTEGER,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (admin_user_id) REFERENCES admin_users(id)
        )
    ");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS settings (
            key_name VARCHAR(64) PRIMARY KEY,
            value_text TEXT
        )
    ");
    // Default settings
    $defaults = [
        'company_name' => SITE_NAME,
        'company_address' => '',
        'company_legal_form' => '',
        'company_email' => defined('ADMIN_EMAIL') ? ADMIN_EMAIL : '',
        'company_phone' => '',
        'company_tax_id' => '',
        'company_vat_number' => '',
        'company_website' => '',
        'company_bank_details' => '',
        'quote_validity_days' => '30',
        'quote_default_tax_rate' => '0',
        'quote_footer_legal' => '',
        'payment_terms' => 'Net 30',
        'invoice_prefix' => 'INV',
        'quote_prefix' => 'SQ',
        'company_currency' => 'CAD',
    ];
    foreach ($defaults as $k => $v) {
        $pdo->prepare("INSERT OR IGNORE INTO settings (key_name, value_text) VALUES (?, ?)")->execute([$k, $v]);
    }
}

function get_setting(string $key, string $default = ''): string {
    $pdo = db();
    $stmt = $pdo->prepare("SELECT value_text FROM settings WHERE key_name = ?");
    $stmt->execute([$key]);
    $row = $stmt->fetch();
    return $row ? (string) $row['value_text'] : $default;
}

function set_setting(string $key, string $value): void {
    db()->prepare("INSERT OR REPLACE INTO settings (key_name, value_text) VALUES (?, ?)")->execute([$key, $value]);
}

function next_quote_number(): string {
    $prefix = get_setting('quote_prefix', 'SQ');
    $year = date('Y');
    $pdo = db();
    $stmt = $pdo->prepare("SELECT quote_number FROM quotes WHERE quote_number LIKE ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$prefix . '-' . $year . '-%']);
    $row = $stmt->fetch();
    if (!$row) return $prefix . '-' . $year . '-001';
    $num = (int) preg_replace('/^.*-(\d+)$/', '$1', $row['quote_number']);
    return $prefix . '-' . $year . '-' . str_pad((string) ($num + 1), 3, '0', STR_PAD_LEFT);
}

function project_status_label(string $status): string {
    $labels = ['lead' => 'Lead', 'in_progress' => 'In progress', 'on_hold' => 'On hold', 'done' => 'Closed'];
    return $labels[$status] ?? $status;
}

function next_invoice_number(): string {
    $prefix = get_setting('invoice_prefix', 'INV');
    $year = date('Y');
    $pdo = db();
    $stmt = $pdo->prepare("SELECT invoice_number FROM invoices WHERE invoice_number LIKE ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$prefix . '-' . $year . '-%']);
    $row = $stmt->fetch();
    if (!$row) return $prefix . '-' . $year . '-001';
    $num = (int) preg_replace('/^.*-(\d+)$/', '$1', $row['invoice_number']);
    return $prefix . '-' . $year . '-' . str_pad((string) ($num + 1), 3, '0', STR_PAD_LEFT);
}
