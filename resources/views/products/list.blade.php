<x-layouts.app title="Products">
    <h1>Products</h1>

    <div class="products-grid">
        @forelse ($products as $product)
            <div class="product-card">
                <img src="{{ asset($product->getImage()) }}" alt="{{ $product->name }}">

                <div class="product-info">
                    <h2 class="product-title">{{ $product->name }}</h2>
                    <p class="product-description">{{ $product->description }}</p>
                    <x-product.price-container
                        usd="{{ $product->getPrice() }}"
                        eur="{{ $product->getEuroPrice() }}"
                    />
                    <a href="{{ route('products.show', ['product' => $product]) }}"
                       class="btn btn-primary">
                        View Details
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-message">
                <p>No products found.</p>
            </div>
        @endforelse
    </div>

    <x-exchange-rate rate="{{ $exchangeRate }}" />
</x-layouts.app>