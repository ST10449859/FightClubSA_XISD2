<?php

header('Content-Type: application/json; charset=utf-8');

$headers = function_exists('getallheaders')
    ? getallheaders()
    : [];

$authorization = $_SERVER['HTTP_AUTHORIZATION']
    ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
    ?? '';

if ($authorization === '') {
    foreach ($headers as $name => $value) {
        if (strtolower($name) === 'authorization') {
            $authorization = $value;
            break;
        }
    }
}

echo json_encode([
    'http_authorization_exists' =>
        !empty($_SERVER['HTTP_AUTHORIZATION']),

    'redirect_authorization_exists' =>
        !empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION']),

    'authorization_header_received' =>
        $authorization !== '',

    'bearer_format_valid' =>
        preg_match(
            '/^Bearer\s+[a-f0-9]{64}$/i',
            trim($authorization)
        ) === 1
], JSON_PRETTY_PRINT);
