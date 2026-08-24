<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans bg-[#f5f5f7] min-h-screen flex items-center justify-center">
    <div class="text-center px-4">
        <p class="text-8xl font-light text-[#d1d5db] tracking-tight">404</p>
        <h1 class="text-2xl font-light text-[#1d1d1f] mt-4 mb-2">Halaman Tidak Ditemukan</h1>
        <p class="text-sm text-[#6e6e73] mb-8">Halaman yang Anda cari mungkin telah dipindahkan atau tidak tersedia.</p>
        <a href="{{ route('home') }}" class="btn-dark-apple inline-flex">Kembali ke Beranda</a>
    </div>
</body>
</html>
