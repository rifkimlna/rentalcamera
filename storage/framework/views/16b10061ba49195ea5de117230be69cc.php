<?php $__env->startSection('title', 'Tentang Kami'); ?>

<?php $__env->startSection('content'); ?>

<section class="section-dim">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-24 lg:py-32">
        <p class="text-sm font-medium text-[#86868b] mb-4 tracking-wide">Tentang Kami</p>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight text-[#1d1d1f] leading-tight mb-6">
            Stekpro Multimedia<br>& Broadcast
        </h1>
        <div class="max-w-2xl">
            <p class="text-lg text-[#6e6e73] leading-relaxed mb-4">
                Stekpro Multimedia & Broadcast adalah platform penyewaan perlengkapan fotografi dan videografi yang berkomitmen memberikan layanan terbaik bagi para kreator, fotografer profesional, dan hobiis di seluruh Indonesia.
            </p>
            <p class="text-lg text-[#6e6e73] leading-relaxed mb-4">
                Berdiri sejak 2020, kami telah melayani ribuan pelanggan dengan berbagai kebutuhan — dari dokumentasi pernikahan, produksi film, hingga konten kreatif. Setiap peralatan menjalani perawatan rutin dan pengecekan kualitas sebelum sampai ke tangan Anda.
            </p>
            <p class="text-lg text-[#6e6e73] leading-relaxed">
                Kami percaya bahwa akses terhadap peralatan berkualitas tidak harus mahal. Dengan sistem sewa yang fleksibel dan transparan, siapa pun bisa berkarya tanpa batasan.
            </p>
        </div>
    </div>
</section>


<section class="section-light">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-24 lg:py-32">
        <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-[#1d1d1f] mb-12">Mengapa Memilih Kami</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php
                $advantages = [
                    ['title' => 'Peralatan Terawat', 'desc' => 'Setiap unit dicek dan dirawat rutin. Kebersihan dan performa adalah prioritas utama kami.'],
                    ['title' => 'Harga Transparan', 'desc' => 'Tidak ada biaya tersembunyi. Harga yang tercantum adalah harga yang Anda bayar.'],
                    ['title' => 'Ambil di Toko', 'desc' => 'Ambil dan kembalikan langsung di toko kami. Proses cepat dan mudah.'],
                    ['title' => 'Support 24/7', 'desc' => 'Tim customer service siap membantu kapan pun melalui chat, telepon, atau email.'],
                    ['title' => 'Asuransi Sewa', 'desc' => 'Tenang selama menyewa dengan opsi asuransi yang melindungi peralatan Anda.'],
                    ['title' => 'Fleksibel', 'desc' => 'Sewa harian, mingguan, atau bulanan. Perpanjangan mudah tanpa ribet.'],
                ];
            ?>
            <?php $__currentLoopData = $advantages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $adv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card-apple-static p-6 lg:p-8">
                    <div class="w-10 h-10 rounded-2xl bg-[#f5f5f7] flex items-center justify-center mb-4">
                        <span class="text-lg font-bold text-[#1d1d1f]"><?php echo e(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></span>
                    </div>
                    <h3 class="text-base font-semibold text-[#1d1d1f] mb-2"><?php echo e($adv['title']); ?></h3>
                    <p class="text-sm text-[#6e6e73] leading-relaxed"><?php echo e($adv['desc']); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="section-dim">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-24 lg:py-32">
        <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-[#1d1d1f] mb-4">Hasil Karya Terbaik</h2>
        <p class="text-base text-[#6e6e73] mb-12">Lihat hasil jepretan dan video dari kamera dan studio kami</p>

        <?php if($portfolios->count() > 0): ?>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php $__currentLoopData = $portfolios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="group relative overflow-hidden rounded-2xl bg-[#e5e5e7] aspect-[4/3] cursor-pointer" onclick="openPortfolioModal('<?php echo e($item->id); ?>')">
                        <?php if($item->tipe === 'foto'): ?>
                            <?php if($item->embed_url): ?>
                                <iframe src="<?php echo e($item->embed_url); ?>" class="w-full h-full object-cover" loading="lazy" title="<?php echo e($item->judul); ?>"></iframe>
                            <?php elseif($item->gambar_url): ?>
                                <img src="<?php echo e($item->gambar_url); ?>" alt="<?php echo e($item->judul); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#e5e5e7] to-[#d1d1d6]">
                                    <svg class="h-10 w-10 text-[#c7c7cc]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if($item->embed_url): ?>
                                <iframe src="<?php echo e($item->embed_url); ?>" class="w-full h-full" loading="lazy" allowfullscreen title="<?php echo e($item->judul); ?>"></iframe>
                            <?php elseif($item->gambar_url): ?>
                                <img src="<?php echo e($item->gambar_url); ?>" alt="<?php echo e($item->judul); ?>" class="w-full h-full object-cover" loading="lazy">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#e5e5e7] to-[#d1d1d6]">
                                    <svg class="h-10 w-10 text-[#c7c7cc]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            <?php endif; ?>
                            <?php if($item->embed_url): ?>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-black/40 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-3 text-white">
                                <p class="text-sm font-medium"><?php echo e($item->judul); ?></p>
                                <?php if($item->deskripsi): ?>
                                    <p class="text-xs text-white/60 truncate"><?php echo e($item->deskripsi); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="text-center py-16">
                <p class="text-[#86868b]">Belum ada portfolio.</p>
            </div>
        <?php endif; ?>
    </div>
</section>


<dialog id="portfolioModal" class="backdrop:bg-black/60">
    <div class="bg-transparent max-w-4xl w-full mx-4 p-0">
        <div class="relative">
            <button onclick="closePortfolioModal()" class="absolute -top-12 right-0 w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white hover:bg-white/30 transition-colors z-10">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div id="portfolioModalContent" class="w-full aspect-video bg-[#1d1d1f] rounded-2xl overflow-hidden"></div>
            <div id="portfolioModalInfo" class="mt-3 text-white text-center"></div>
        </div>
    </div>
</dialog>


<section class="section-light">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-24 lg:py-32">
        <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-[#1d1d1f] mb-12">Tim Kami</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php
                $team = [
                    ['name' => 'Ahmad Rizki', 'role' => 'Founder & CEO'],
                    ['name' => 'Dinda Pratama', 'role' => 'Operations Manager'],
                    ['name' => 'Fajar Nugroho', 'role' => 'Technical Lead'],
                    ['name' => 'Sari Indah', 'role' => 'Customer Service'],
                ];
            ?>
            <?php $__currentLoopData = $team; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="text-center">
                    <div class="w-20 h-20 rounded-full bg-[#f5f5f7] mx-auto mb-4 flex items-center justify-center">
                        <span class="text-2xl font-bold text-[#d1d1d6]"><?php echo e(substr($member['name'], 0, 1)); ?></span>
                    </div>
                    <p class="text-sm font-semibold text-[#1d1d1f]"><?php echo e($member['name']); ?></p>
                    <p class="text-xs text-[#86868b] mt-0.5"><?php echo e($member['role']); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="section-darker">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-24 text-center">
        <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-white mb-4">Siap menyewa?</h2>
        <p class="text-base text-white/50 mb-8">Daftar sekarang dan mulai karya terbaik Anda.</p>
        <?php if(auth()->guard()->guest()): ?>
            <a href="<?php echo e(route('register')); ?>" class="btn-primary-apple">Daftar Sekarang</a>
        <?php else: ?>
            <a href="<?php echo e(route('customer.products.index')); ?>" class="btn-primary-apple">Sewa Sekarang</a>
        <?php endif; ?>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script>
    const portfolios = <?php echo json_encode($portfolios, 15, 512) ?>;

    function openPortfolioModal(id) {
        const item = portfolios.find(p => p.id == id);
        if (!item) return;
        const content = document.getElementById('portfolioModalContent');
        const info = document.getElementById('portfolioModalInfo');
        const isInstagram = item.platform === 'instagram';
        const aspectClass = isInstagram ? 'aspect-[9/16] max-w-sm mx-auto' : 'aspect-video';

        if (item.embed_url) {
            content.className = 'w-full ' + aspectClass + ' bg-[#1d1d1f] rounded-2xl overflow-hidden';
            content.innerHTML = '<iframe src="' + item.embed_url + '" class="w-full h-full" allowfullscreen title="' + item.judul + '" loading="lazy"></iframe>';
        } else if (item.gambar_url) {
            content.className = 'w-full aspect-video bg-[#1d1d1f] rounded-2xl overflow-hidden';
            content.innerHTML = '<img src="' + item.gambar_url + '" alt="' + item.judul + '" class="w-full h-full object-contain" loading="lazy">';
        } else {
            content.className = 'w-full aspect-video bg-[#1d1d1f] rounded-2xl overflow-hidden';
            content.innerHTML = '<div class="w-full h-full flex items-center justify-center text-[#86868b]">Tidak ada media</div>';
        }

        info.innerHTML = '<p class="text-lg font-medium">' + item.judul + '</p>' +
            (item.deskripsi ? '<p class="text-sm text-white/60">' + item.deskripsi + '</p>' : '');

        document.getElementById('portfolioModal').showModal();
    }

    function closePortfolioModal() {
        document.getElementById('portfolioModal').close();
        document.getElementById('portfolioModalContent').innerHTML = '';
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views/about.blade.php ENDPATH**/ ?>