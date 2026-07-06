<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /** Categorie ammesse, in linea con l'ENUM del database */
    public const CATEGORIES = ['Affitto', 'Stipendio', 'Spese Generali', 'Altro'];

    protected $fillable = [
        'user_id',
        'description',
        'date',
        'amount',
        'category',
        'receipt',
    ];

    protected $casts = [
        'date'   => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Scope per filtrare solo le transazioni dell'utente autenticato.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function isEntrata(): bool
    {
        return $this->category === 'Stipendio';
    }
}