<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;

class Sale extends Model
{
    use HasFactory, Multitenantable;

    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
