<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DebtPayment extends Model
{
    protected $guarded = [];

    public function debt()
    {
        return $this->belongsTo(Debt::class);
    }
}
