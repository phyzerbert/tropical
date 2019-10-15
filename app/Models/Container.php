<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    protected $guarded = [];

    public function proforma(){
        return $this->belongsTo(Proforma::class);
    }

    public function product_quantity($id) {
        $quantity = 0;
        $product_array = json_decode($this->product_list, true);
        if(isset($product_array[$id])) {
            $quantity = $product_array[$id];
        }
        return $quantity;
    }
}
