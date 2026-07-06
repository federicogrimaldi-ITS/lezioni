<?php

declare(strict_types=1);

namespace App\Config;

use Illuminate\Support\Facades\DB;
use PDO;

final class Database
{
    public static function getConnection(): PDO
    {
        return DB::connection()->getPdo();
    }
}