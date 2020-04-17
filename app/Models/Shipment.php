<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $guarded = [];

    protected $with = ['items'];
    
    protected $appends = ["proforma_date"];
    
    public function getProformaDateAttribute()
    {
        return $this->proforma->date ?? '';
    }

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
