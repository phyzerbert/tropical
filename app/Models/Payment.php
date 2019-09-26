<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function proforma()
    {
        return $this->belongsTo(Proforma::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function sale_proforma()
    {
        return $this->belongsTo(SaleProforma::class);
    }

}
