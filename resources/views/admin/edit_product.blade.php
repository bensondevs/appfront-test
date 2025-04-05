<x-layouts.admin title="Edit Product">
    <h1>Edit Product</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-form.product-form
        :product="$product"
        action="{{ route('admin.update.product', $product->getKey()) }}"
        method="PUT"
        submit-label="Update Product"
    />
</x-layouts.admin>
