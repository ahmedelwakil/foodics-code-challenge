<?php

namespace App\Utils;

class OrderStatusUtil
{
    const PENDING = 'pending';
    const CONFIRMED = 'confirmed';
    const REJECTED = 'rejected';

    public static function getAllStatuses()
    {
        return [
            self::PENDING,
            self::CONFIRMED,
            self::REJECTED,
        ];
    }
}