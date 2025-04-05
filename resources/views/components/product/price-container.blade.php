@props([
    'usd' => '',
    'eur' => '',
])

<div class="price-container">
    <span class="price-usd">{{ $usd }}</span>
    <span class="price-eur">{{ $eur }}</span>
</div>