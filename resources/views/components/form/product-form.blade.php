@props([
    'product' => null,
    'action' => '#',
    'method' => 'POST',
    'submitLabel' => '',
])

<form action="{{ $action }}"
      method="POST"
      enctype="multipart/form-data"
>
    @csrf

    @if (strtoupper($method) !== 'POST')
        @method($method)
    @endif

    <div class="form-group">
        <label for="name">Product Name</label>
        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $product?->name) }}" required>
        @error('name')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" class="form-control" required>{{ old('description', $product?->description) }}</textarea>
    </div>

    <div class="form-group">
        <label for="price">Price</label>
        <input type="number"
               id="price"
               name="price"
               step="0.01"
               class="form-control"
               value="{{ old('price', $product?->price) }}"
               required
        />
    </div>

    <div class="form-group">
        <label for="image">
            @if ($product)
                Current Image
            @else
                Product Image
            @endif
        </label>
        @if ($product)
            <img src="{{ asset($product?->getImage()) }}" class="product-image" alt="{{ $product?->name }}">
        @endif
        <input type="file" id="image" name="image" class="form-control">
        <small>Leave empty to keep current image</small>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('admin.products') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>