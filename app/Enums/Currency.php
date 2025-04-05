<?php

namespace App\Enums;

enum Currency: string
{
    case USD = 'usd';
    case EUR = 'eur';

    public function isUSD(): bool
    {
        return $this === self::USD;
    }

    public function isEUR(): bool
    {
        return $this === self::EUR;
    }
}
