<x-layouts.app title="{{ $product->name }}">
    <div class="product-detail">
        <div>
            @if ($product->image)
                <img
                    src="{{ asset($product->getImage()) }}"
                    alt="{{ $product->name }}"
                    class="product-detail-image"
                >
            @endif
        </div>
        <div class="product-detail-info">
            <h1 class="product-detail-title">{{ $product->name }}</h1>
            <p class="product-id">Product ID: {{ $product->getKey() }}</p>

            <x-product.price-container
                usd="{{ $product->getPrice() }}"
                eur="{{ $product->getEuroPrice() }}"
            />

            <div class="divider"></div>

            <div class="product-detail-description">
                <h4 class="description-title">Description</h4>
                <p>{{ $product->description }}</p>
            </div>

            <div class="action-buttons">
                <a href="{{ route('index') }}" class="btn btn-secondary">Back to Products</a>
                <button class="btn btn-primary">Add to Cart</button>
            </div>

            <x-exchange-rate
                rate="{{ $exchangeRate ?? null }}"
                style="margin-top: 20px; font-size: 0.9rem; color: #7f8c8d;"
            />
        </div>
    </div>
</x-layouts.app>