<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleProforma extends Model
{
    protected $guarded = [];
    
    public function items(){
        return $this->morphMany(Item::class, 'itemable');
    }

    public function sale(){
        return $this->hasOne(Sale::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }
}
