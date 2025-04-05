<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExchangeRate
{
    public static function getUsdToEurRate(): float
    {
        $fallbackRate = config('services.exchange_rate.fallback_exchange_rate');

        return Cache::remember('exchange_rate_usd_to_eur', now()->addHour(), function () use ($fallbackRate) {
            try {
                $response = Http::timeout(5)->get(config('services.exchange_rate.api_url'));

                if (! $response->successful()) {
                    return $fallbackRate;
                }

                $data = $response->json();

                return $data['rates']['EUR'] ?? $fallbackRate;
            } catch (\Exception $e) {
                Log::error('Exchange rate fetch failed: ' . $e->getMessage());
                return $fallbackRate;
            }
        });
    }
}