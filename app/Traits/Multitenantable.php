<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait Multitenantable
{
    protected static function bootMultitenantable()
    {
        static::creating(function ($model) {
            if (empty($model->store_id)) {
                if (Auth::check() && !empty(Auth::user()->store_id)) {
                    $model->store_id = Auth::user()->store_id;
                } else {
                    $model->store_id = 1;
                }
            }
        });

        if (Auth::check() && Auth::user()->role !== 'super_admin') {
            static::addGlobalScope('store_id', function (Builder $builder) {
                if (Auth::check() && !empty(Auth::user()->store_id)) {
                    $builder->where($builder->getQuery()->from . '.store_id', Auth::user()->store_id);
                }
            });
        }
    }
}
