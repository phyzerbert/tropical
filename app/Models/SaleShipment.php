<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleShipment extends Model
{
    protected $guarded = [];

    protected $with = ['items'];

    public function sale_proforma(){
        return $this->belongsTo(SaleProforma::class);
    }

    public function sale(){
        return $this->hasOne(Sale::class);
    }

    public function items(){
        return $this->morphMany(Item::class, 'itemable');
    }
}
