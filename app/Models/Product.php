<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Container;
use App\Models\Item;

class Product extends Model
{
    protected $guarded = [];

    public function items(){
        return $this->hasMany(Item::class);
    }

    public function quantity(){
        $income = 0;
        $expense = $this->items()->where('itemable_type', 'App\Models\Sale')->sum('quantity');

        $containers = Container::all();
        foreach ($containers as $container) {
            $income += $container->product_quantity($this->id);
        }

        $quantity = $income - $expense;

        return $quantity;
    }
}
