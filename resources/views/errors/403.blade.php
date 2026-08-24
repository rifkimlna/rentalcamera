<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>403 - Akses Ditolak</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans bg-[#f5f5f7] min-h-screen flex items-center justify-center">
    <div class="text-center px-4">
        <p class="text-8xl font-light text-[#d1d5db] tracking-tight">403</p>
        <h1 class="text-2xl font-light text-[#1d1d1f] mt-4 mb-2">Akses Ditolak</h1>
        <p class="text-sm text-[#6e6e73] mb-8">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        @auth
            <a href="{{ route('dashboard') }}" class="btn-dark-apple inline-flex">Kembali ke Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn-dark-apple inline-flex">Masuk</a>
        @endauth
    </div>
</body>
</html>
