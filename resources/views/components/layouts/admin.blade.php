@props(['title' => ''])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ($title ?? null ? "{$title} - " : '') . config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/css/admin.css'])
</head>
<body>
<div class="admin-container">
    <div class="admin-header">
        <h1>{{ ($title ?? null ? "{$title} - " : '') . config('app.name') }}</h1>
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

    {{ $slot }}
</div>
</body>
</html>
