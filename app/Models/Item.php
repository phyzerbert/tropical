<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $guarded = [];

    protected $with = ['product'];

    public function itemable()
    {
        return $this->morphTo();
    }

    public function invoice()
    {
        return $this->morphedByMany(Invoice::class, 'itemable');
    }

    public function proforma()
    {
        return $this->morphedByMany(Proforma::class, 'itemable');
    }

    public function shipment()
    {
        return $this->morphedByMany(Shipment::class, 'itemable');
    }

    public function sale_shipment()
    {
        return $this->morphedByMany(SaleShipment::class, 'itemable');
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
