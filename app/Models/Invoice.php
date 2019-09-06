<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
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

    public function proforma(){
        return $this->belongsTo(Proforma::class);
    }
}
