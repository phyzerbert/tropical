<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proforma extends Model
{
    protected $guarded = [];

    public function items(){
        return $this->morphMany(Item::class, 'itemable');
    }
    
    public function payments(){
        return $this->hasMany(Payment::class);
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function containers(){
        return $this->hasMany(Container::class);
    }

    public function invoice(){
        return $this->hasOne(Invoice::class);
    }

    public function shipment(){
        return $this->hasOne(Shipment::class);
    }
}
