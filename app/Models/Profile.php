<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory, Multitenantable;

    protected $guarded = [];
}
