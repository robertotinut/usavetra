<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;

class Category extends Model
{
    use Multitenantable;

    protected $guarded = [];
}
