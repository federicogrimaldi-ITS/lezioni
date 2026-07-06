<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Centralizza la gestione della sessione utente.
 * I dati vengono salvati sotto le chiavi 'user_id', 'user_name', 'user_email'
 * (le stesse lette direttamente in resources/views/layouts/app.blade.php).
 */
final class UserSession
{
    private function __construct()
    {
        // Classe statica, non istanziabile
    }

    /**
     * Salva i dati dell'utente in sessione dopo login o registrazione.
     * Accetta un array con almeno le chiavi 'id', 'name', 'email'.
     */
    public static function login(array $user): void
    {
        session([
            'user_id'    => $user['id'],
            'user_name'  => $user['name'],
            'user_email' => $user['email'],
        ]);

        // Previene session fixation dopo un login riuscito
        request()->session()->regenerate();
    }

    public static function logout(): void
    {
        session()->forget(['user_id', 'user_name', 'user_email']);
        session()->regenerate();
    }

    public static function isLoggedIn(): bool
    {
        return session()->has('user_id');
    }

    // --- Alias 'id' (usato da TransactionController) ---
    public static function id(): ?int
    {
        return session('user_id');
    }

    // --- Alias 'userId' (usato da UserController) ---
    public static function userId(): ?int
    {
        return self::id();
    }

    public static function userName(): ?string
    {
        return session('user_name');
    }

    public static function userEmail(): ?string
    {
        return session('user_email');
    }
}