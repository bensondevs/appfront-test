<x-layouts.admin title="Add New Product">
    <h1>Add New Product</h1>

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
        action="{{ route('admin.add.product.submit') }}"
        submit-label="Add Product"
    />
</x-layouts.admin>
