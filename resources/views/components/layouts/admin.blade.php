@props(['title' => ''])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ($title ?? null) }}</title>

    @vite(['resources/css/app.css', 'resources/css/admin.css'])
</head>
<body>
<div class="admin-container">
    {{ $slot }}
</div>
</body>
</html>
