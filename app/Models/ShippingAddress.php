<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    public function getAddress(): string
    {
        return $this->prefectures.$this->municipalities.$this->address_code.$this->building;
    }

    public function apply()
    {
        return $this->hasOne(Apply::class);
    }

    public function deliveryTime()
    {
        return $this->belongsTo(DeliveryTime::class);
    }

    protected $guarded = [
        "id", "created_at", "updated_at",
    ];
}
