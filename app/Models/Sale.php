<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $guarded = [];
    
    public function items(){
        return $this->morphMany(Item::class, 'itemable');
    }

    public function proforma(){
        return $this->belongsTo(SaleProforma::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }
}
