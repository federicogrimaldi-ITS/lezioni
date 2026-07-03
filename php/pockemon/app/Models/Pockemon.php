<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pockemon extends Model
{
    protected $table = 'pokemon';

    protected $primaryKey = 'Id';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;
}
