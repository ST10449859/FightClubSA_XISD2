<?php
require_once __DIR__ . '/bootstrap.php';

requireMethod('POST');

$user = requireUser();
$token = bearerToken();

try {
    $stmt = db()->prepare(
        'DELETE FROM api_tokens
         WHERE user_id = ? AND token_hash = ?'
    );

    $stmt->execute([
        $user['id'],
        hash('sha256', $token)
    ]);

    respond(200, [
        'success' => true,
        'message' => 'Logged out successfully.'
    ]);

} catch (PDOException $e) {
    error_log('Logout API error: ' . $e->getMessage());

    respond(500, [
        'success' => false,
        'message' => 'Unable to log out.'
    ]);
}
