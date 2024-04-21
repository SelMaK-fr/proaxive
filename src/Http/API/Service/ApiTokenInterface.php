<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Http\API\Service;

interface ApiTokenInterface
{
    /**
     * Permet de générer un token JWT
     * @param int $user_id
     * @return string
     */
    public function generateToken(int $user_id): string;

    /**
     * Permet de vérifier un token JWT
     * @param string $token
     * @return bool
     */
    public function verifyToken(string $token): bool;
}