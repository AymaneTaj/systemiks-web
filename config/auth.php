<?php
/**
 * Systemiks - Admin authentication
 */
if (!defined('SITE_NAME')) {
    require_once __DIR__ . '/config.php';
}
require_once __DIR__ . '/database.php';

function admin_session_start(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_name(ADMIN_SESSION_NAME);
        session_set_cookie_params(['lifetime' => ADMIN_SESSION_LIFETIME, 'path' => '/', 'httponly' => true]);
        session_start();
    }
}

function admin_is_logged_in(): bool {
    admin_session_start();
    return !empty($_SESSION['admin_user_id']) && !empty($_SESSION['admin_username']);
}

function admin_require_login(): void {
    if (!admin_is_logged_in()) {
        header('Location: ' . ADMIN_LOGIN_PAGE . '?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

function admin_login(string $username, string $password): bool {
    init_db();
    $pdo = db();
    $stmt = $pdo->prepare("SELECT id, username, password_hash, role FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password_hash'])) {
        return false;
    }
    admin_session_start();
    $_SESSION['admin_user_id'] = (int) $user['id'];
    $_SESSION['admin_username'] = $user['username'];
    $_SESSION['admin_role'] = $user['role'] ?? 'admin';
    return true;
}

/** Current user role: admin, editor, or viewer */
function admin_role(): string {
    admin_session_start();
    return $_SESSION['admin_role'] ?? 'admin';
}

/** Require one of the given roles (e.g. ['admin', 'editor']). Call after admin_require_login(). */
function admin_require_role(array $allowedRoles): void {
    if (!in_array(admin_role(), $allowedRoles, true)) {
        header('Location: ' . ADMIN_DASHBOARD);
        exit;
    }
}

/** Can manage Users and sensitive Settings (SMTP, etc.) */
function admin_can_manage_users(): bool {
    return admin_role() === 'admin';
}

/** Can edit Settings (company info, defaults). Admin only for SMTP/password; editor can view. */
function admin_can_edit_settings(): bool {
    return admin_role() === 'admin';
}

/** Can create/edit/delete (not read-only). */
function admin_can_edit(): bool {
    return in_array(admin_role(), ['admin', 'editor'], true);
}

function admin_logout(): void {
    admin_session_start();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

/** CSRF token for admin forms. Output in a hidden input: <input type="hidden" name="_csrf" value="<?= csrf_token() ?>"> */
function csrf_token(): string {
    admin_session_start();
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

/** Validate CSRF token from POST. Call at start of POST handling; redirects back if invalid. */
function csrf_validate(): void {
    admin_session_start();
    $token = $_POST['_csrf'] ?? '';
    if ($token === '' || !hash_equals($_SESSION['_csrf'] ?? '', $token)) {
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? ADMIN_DASHBOARD));
        exit;
    }
    $_SESSION['_csrf'] = bin2hex(random_bytes(32));
}
