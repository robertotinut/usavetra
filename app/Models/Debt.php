<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;

class Debt extends Model
{
    use Multitenantable;

    protected $guarded = [];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function payments()
    {
        return $this->hasMany(DebtPayment::class);
    }

    public function getRemainingAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }
}
