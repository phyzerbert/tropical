<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proforma extends Model
{
    protected $guarded = [];

    public function items(){
        return $this->morphMany(Item::class, 'itemable');
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function containers(){
        return $this->hasMany(Container::class);
    }
}
