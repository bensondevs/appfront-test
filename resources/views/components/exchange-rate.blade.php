@php use App\Support\ExchangeRate; @endphp
@props([
    'rate' => null,
    'style' => null,
])

@php $rate ??= ExchangeRate::getUsdToEurRate(); @endphp

<p @if(empty($style)) class="exchange-rate" @else style="{{ $style }}" @endif>
    Exchange Rate: 1 USD = {{ number_format($rate, 4) }} EUR
</p>