<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, Multitenantable;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
