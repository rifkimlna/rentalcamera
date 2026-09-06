<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
</head>
<body class="font-sans bg-white min-h-screen flex items-center justify-center">
    <div class="relative flex flex-col w-full justify-center min-h-screen p-6 md:p-10">
        <div class="relative max-w-5xl mx-auto w-full">
            
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 362 145" class="absolute inset-0 w-full h-[50vh] opacity-[0.04] text-black pointer-events-none" fill="currentColor" aria-hidden="true">
                <path d="M62.6 142c-2.133 0-3.2-1.067-3.2-3.2V118h-56c-2 0-3-1-3-3V92.8c0-1.333.4-2.733 1.2-4.2L58.2 4c.8-1.333 2.067-2 3.8-2h28c2 0 3 1 3 3v85.4h11.2c.933 0 1.733.333 2.4 1 .667.533 1 1.267 1 2.2v21.2c0 .933-.333 1.733-1 2.4-.667.533-1.467.8-2.4.8H93v20.8c0 2.133-1.067 3.2-3.2 3.2H62.6zM33 90.4h26.4V51.2L33 90.4zM181.67 144.6c-7.333 0-14.333-1.333-21-4-6.666-2.667-12.866-6.733-18.6-12.2-5.733-5.467-10.266-13-13.6-22.6-3.333-9.6-5-20.667-5-33.2 0-12.533 1.667-23.6 5-33.2 3.334-9.6 7.867-17.133 13.6-22.6 5.734-5.467 11.934-9.533 18.6-12.2 6.667-2.8 13.667-4.2 21-4.2 7.467 0 14.534 1.4 21.2 4.2 6.667 2.667 12.8 6.733 18.4 12.2 5.734 5.467 10.267 13 13.6 22.6 3.334 9.6 5 20.667 5 33.2 0 12.533-1.666 23.6-5 33.2-3.333 9.6-7.866 17.133-13.6 22.6-5.6 5.467-11.733 9.533-18.4 12.2-6.666 2.667-13.733 4-21.2 4zm0-31c9.067 0 15.6-3.733 19.6-11.2 4.134-7.6 6.2-17.533 6.2-29.8s-2.066-22.2-6.2-29.8c-4.133-7.6-10.666-11.4-19.6-11.4-8.933 0-15.466 3.8-19.6 11.4-4 7.6-6 17.533-6 29.8s2 22.2 6 29.8c4.134 7.467 10.667 11.2 19.6 11.2zM316.116 142c-2.134 0-3.2-1.067-3.2-3.2V118h-56c-2 0-3-1-3-3V92.8c0-1.333.4-2.733 1.2-4.2l56.6-84.6c.8-1.333 2.066-2 3.8-2h28c2 0 3 1 3 3v85.4h11.2c.933 0 1.733.333 2.4 1 .666.533 1 1.267 1 2.2v21.2c0 .933-.334 1.733-1 2.4-.667.533-1.467.8-2.4.8h-11.2v20.8c0 2.133-1.067 3.2-3.2 3.2h-27.2zm-29.6-51.6h26.4V51.2l-26.4 39.2z"/>
            </svg>
            <div class="relative text-center z-[1]">
                <h1 class="mt-4 text-balance text-5xl font-semibold tracking-tight text-black sm:text-7xl">Halaman tidak ditemukan</h1>
                <p class="mt-6 text-pretty text-lg font-medium text-neutral-500">Halaman yang Anda cari mungkin telah dipindahkan atau tidak tersedia.</p>
                <form action="<?php echo e(route('customer.products.index')); ?>" method="GET" class="mt-10 flex flex-col sm:flex-row gap-2 mx-auto sm:max-w-sm">
                    <div class="relative w-full">
                        <svg class="absolute left-3 top-3 h-4 w-4 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
                        <input name="search" placeholder="Cari equipment…" class="flex h-10 w-full rounded-full border border-neutral-300 bg-white pl-9 pr-4 py-2 text-sm text-black placeholder:text-neutral-400 focus-visible:border-black focus-visible:outline-none">
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-full border border-neutral-300 bg-white px-4 h-10 text-sm font-medium text-black hover:border-black hover:bg-neutral-50 transition-colors">Cari</button>
                </form>
                <div class="mt-10 flex flex-col sm:flex-row sm:items-center sm:justify-center gap-3">
                    <button onclick="history.back()" class="inline-flex items-center justify-center whitespace-nowrap rounded-full bg-neutral-100 px-4 h-10 text-sm font-medium text-black hover:bg-neutral-200 transition-colors">
                        <svg class="me-2 h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
                        Kembali
                    </button>
                    <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center justify-center whitespace-nowrap rounded-full bg-black px-4 h-10 text-sm font-medium text-white hover:bg-neutral-800 transition-colors">Ke beranda</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\PROJECT KP\resources\views/errors/404.blade.php ENDPATH**/ ?>