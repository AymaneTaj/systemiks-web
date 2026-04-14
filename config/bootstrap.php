<?php
/**
 * Load from public/admin/* or public/*: use project root.
 */
$projectRoot = realpath(__DIR__ . '/..');
define('SYSTEMIKS_ROOT', $projectRoot);
require_once $projectRoot . '/config/config.php';
require_once $projectRoot . '/config/database.php';
require_once $projectRoot . '/config/auth.php';
require_once $projectRoot . '/config/email.php';
require_once $projectRoot . '/config/activity.php';
init_db();
