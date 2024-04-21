<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Http\API\Service;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ApiTokenService implements ApiTokenInterface
{

    public function generateToken(int $user_id): string
    {
        $secret_key = 'your-secret-key';
        $issued_at = time();
        $expiration_time = $issued_at + 3600; // token valid for 1 hour
        $payload = array(
            'user_id' => $user_id,
            'iat' => $issued_at,
            'exp' => $expiration_time
        );
        return JWT::encode($payload, $secret_key, 'HS256');
    }

    public function verifyToken(string $token): bool
    {
        $secret_key = 'your-secret-key';
        try {
            JWT::decode($token, new Key($secret_key, 'HS256'));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}