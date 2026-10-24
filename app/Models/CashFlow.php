<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;

class CashFlow extends Model
{
    use Multitenantable;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
    ];
}
