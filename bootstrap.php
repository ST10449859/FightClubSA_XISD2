<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function respond(int $status, array $data): void
{
    http_response_code($status);

    echo json_encode(
        $data,
        JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE
    );

    exit;
}

function requireMethod(string $method): void
{
    if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '')
        !== strtoupper($method)) {

        header('Allow: ' . strtoupper($method));

        respond(405, [
            'success' => false,
            'message' => 'Method not allowed.'
        ]);
    }
}

function readJson(): array
{
    $raw = file_get_contents('php://input');

    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $data = json_decode($raw, true);

    if (!is_array($data)) {
        respond(400, [
            'success' => false,
            'message' => 'Request body must contain valid JSON.'
        ]);
    }

    return $data;
}

function bearerToken(): ?string
{
    $header = $_SERVER['HTTP_AUTHORIZATION']
        ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
        ?? '';

    if ($header === '' && function_exists('getallheaders')) {
        foreach (getallheaders() as $name => $value) {
            if (strtolower((string) $name) === 'authorization') {
                $header = (string) $value;
                break;
            }
        }
    }

    if (!preg_match(
        '/^Bearer\s+([a-f0-9]{64})$/i',
        trim($header),
        $matches
    )) {
        return null;
    }

    return $matches[1];
}

function requireUser(): array
{
    $token = bearerToken();

    if ($token === null) {
        respond(401, [
            'success' => false,
            'message' => 'Authorization token missing or invalid.'
        ]);
    }

    try {
        $pdo = db();

        $stmt = $pdo->prepare(
            'SELECT u.id, u.full_name, u.email, u.role
             FROM api_tokens AS t
             INNER JOIN users AS u ON u.id = t.user_id
             WHERE t.token_hash = ?
               AND t.expires_at > NOW()
             LIMIT 1'
        );

        $stmt->execute([hash('sha256', $token)]);
        $user = $stmt->fetch();

        if (!$user) {
            respond(401, [
                'success' => false,
                'message' => 'Token expired or invalid. Please log in again.'
            ]);
        }

        return $user;

    } catch (PDOException $e) {
        error_log('Token validation error: ' . $e->getMessage());

        respond(500, [
            'success' => false,
            'message' => 'Database error while validating the login token.'
        ]);
    }
}
