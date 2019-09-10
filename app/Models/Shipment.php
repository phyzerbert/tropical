<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $guarded = [];

    public function proforma(){
        return $this->belongsTo(Proforma::class);
    }

    public function invoice(){
        return $this->hasOne(Invoice::class);
    }

    public function items(){
        return $this->morphMany(Item::class, 'itemable');
    }
}
