<?php

declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;
use InvalidArgumentException;

/**
 * Modello User: gestisce registrazione, login e profilo utente.
 */
class User
{
    private PDO $db;

    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?string $email = null,
        private ?string $password = null,
        public ?string $created_at = null,
    ) {
        $this->db = Database::getConnection();
    }

    /**
     * Registra un nuovo utente. Restituisce l'id creato o lancia un'eccezione.
     */
    public function register(string $name, string $email, string $plainPassword): int
    {
        if ($this->emailExists($email)) {
            throw new InvalidArgumentException("L'email '{$email}' è già registrata.");
        }

        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
        $now = date('Y-m-d H:i:s');

        $stmt = $this->db->prepare(
            'INSERT INTO users (name, email, password, created_at, updated_at) VALUES (:name, :email, :password, :created_at, :updated_at)'
        );

        $stmt->execute([
            ':name'     => $name,
            ':email'    => $email,
            ':password' => $hashedPassword,
            ':created_at' => $now,
            ':updated_at' => $now,
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Verifica le credenziali di login.
     * Restituisce i dati dell'utente (array) se valide, altrimenti null.
     */
    public function login(string $email, string $plainPassword): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user === false) {
            return null;
        }

        if (!password_verify($plainPassword, $user['password'])) {
            return null;
        }

        unset($user['password']); // non esporre mai l'hash

        return $user;
    }

    /**
     * Recupera un utente tramite id.
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, name, email, created_at FROM users WHERE id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();

        return $user !== false ? $user : null;
    }

    /**
     * Aggiorna nome ed email del profilo utente.
     */
    public function updateProfile(int $id, string $name, string $email): bool
    {
        if ($this->emailExistsForAnotherUser($id, $email)) {
            throw new InvalidArgumentException("L'email '{$email}' è già registrata.");
        }

        $stmt = $this->db->prepare(
            'UPDATE users SET name = :name, email = :email, updated_at = :updated_at WHERE id = :id'
        );

        return $stmt->execute([
            ':name'  => $name,
            ':email' => $email,
            ':id'    => $id,
            ':updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Aggiorna la password dell'utente.
     */
    public function updatePassword(int $id, string $newPlainPassword): bool
    {
        $hashed = password_hash($newPlainPassword, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare('UPDATE users SET password = :password WHERE id = :id');

        return $stmt->execute([
            ':password' => $hashed,
            ':id'       => $id,
        ]);
    }

    private function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);

        return $stmt->fetch() !== false;
    }

    private function emailExistsForAnotherUser(int $id, string $email): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = :email AND id <> :id LIMIT 1');
        $stmt->execute([
            ':email' => $email,
            ':id' => $id,
        ]);

        return $stmt->fetch() !== false;
    }
}
