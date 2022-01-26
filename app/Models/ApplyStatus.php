<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplyStatus extends Model
{
    public const APPLYING = 1;
    public const WAITING_LOTTERY = 11;
    public const LOST_LOTTERY = 12;
    public const WAITING_ADDRESS = 13;
    public const CANCEL = 21;
    public const SENT_PRODUCT = 31;
    public const DEFECTING_ADDRESS = 32;

    /** @var array */
    public const canEditAddress = [
        self::WAITING_ADDRESS,
    ];

    /** @var array */
    public const canCancel = [
        self::WAITING_LOTTERY,
        self::WAITING_ADDRESS,
    ];
}
