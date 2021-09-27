<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{
    protected $table="bill";

    public function bill_detail(){
        return $this->hasMany('App\BillDetail','id_bill','id');
    }

    public function customer(){
        return $this->belongsTo('App\Customer','id_customer','id');
    }
}
