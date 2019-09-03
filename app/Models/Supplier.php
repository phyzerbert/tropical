<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }    
    
    public function proformas(){
        return $this->hasMany(Proforma::class);
    }
}
