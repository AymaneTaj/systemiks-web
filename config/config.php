<?php
/**
 * Systemiks - Site & app config
 */
define('SITE_NAME', 'Systemiks');

// Environment-aware config — override in config/config.local.php (never committed)
$_localConfig = __DIR__ . '/config.local.php';
if (file_exists($_localConfig)) {
    require $_localConfig;
} else {
    define('SITE_URL', 'https://systemiks.tech');
    define('ADMIN_EMAIL', 'hello@systemiks.ca');
}
unset($_localConfig);

// Session name for admin
define('ADMIN_SESSION_NAME', 'systemiks_admin');
define('ADMIN_SESSION_LIFETIME', 86400); // 24 hours
define('ADMIN_LOGIN_PAGE', '/admin/login.php');
define('ADMIN_DASHBOARD', '/admin/');
