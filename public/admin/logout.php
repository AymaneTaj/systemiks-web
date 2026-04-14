<?php
require_once __DIR__ . '/../../config/bootstrap.php';
admin_logout();
header('Location: /admin/login.php');
exit;
