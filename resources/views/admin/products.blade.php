<x-layouts.admin title="Admin - Products">
    <div class="admin-header">
        <h1>Admin - Products</h1>
        <div>
            <a href="{{ route('admin.add.product') }}" class="btn btn-primary">Add New Product</a>

            <form action="{{ route('logout') }}"
                  method="POST"
                  style="display: inline;"
            >
                @csrf
                <button type="submit" class="btn btn-secondary">Logout</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <table class="admin-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->getKey() }}</td>
                <td>
                    <img src="{{ asset($product->getImage()) }}"
                         width="50"
                         height="50"
                         alt="{{ $product->name }}">
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->getPrice() }}</td>
                <td>
                    <a href="{{ route('admin.edit.product', ['product' => $product]) }}" class="btn btn-primary">
                        Edit
                    </a>

                    <form action="{{ route('admin.delete.product', ['product' => $product]) }}"
                          method="POST"
                          style="display: inline"
                          onsubmit="return confirm('Are you sure you want to delete this product?');"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</x-layouts.admin>
