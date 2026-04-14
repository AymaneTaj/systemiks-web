<?php
/**
 * Activity logging for admin actions.
 */

function log_activity(string $action, string $entityType, ?int $entityId = null, ?string $details = null): void {
    if (!function_exists('db')) return;
    $adminId = isset($_SESSION['admin_user_id']) ? (int) $_SESSION['admin_user_id'] : null;
    try {
        db()->prepare("INSERT INTO activity_log (action, entity_type, entity_id, details, admin_user_id) VALUES (?, ?, ?, ?, ?)")
            ->execute([$action, $entityType, $entityId, $details, $adminId]);
    } catch (Throwable $e) {
        // Don't break the app if logging fails
    }
}
