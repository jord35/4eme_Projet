<?php

class AuthenticationService
{
    public function requireUserId(): array
    {
        if (empty($_SESSION['user_id'])) {
            return [
                'success' => false,
                'error' => 'Authentication required.',
                'data' => null
            ];
        }

        return [
            'success' => true,
            'error' => null,
            'data' => [
                'user_id' => (int) $_SESSION['user_id']
            ]
        ];
    }
}