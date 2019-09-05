<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

    public function paymentable()
    {
        return $this->morphTo();
    }

    public function invoice()
    {
        return $this->morphedByMany('App\Models\Invoice', 'paymentable');
    }

    public function proforma()
    {
        return $this->morphedByMany('App\Models\Proforma', 'paymentable');
    }

}
