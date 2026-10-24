<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;

class StockMovement extends Model
{
    use HasFactory, Multitenantable;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
