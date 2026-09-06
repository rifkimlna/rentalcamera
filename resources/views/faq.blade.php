@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
<x-flash-messages />
<section class="section-dim">
    <div class="max-w-3xl mx-auto px-5 sm:px-8 py-24 lg:py-32">
        <p class="text-sm font-medium text-[#86868b] mb-4 tracking-wide">FAQ</p>
        <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#1d1d1f] mb-12">Pertanyaan Umum</h1>

        @forelse($faqs as $faq)
            <div x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }" class="border-b border-[#e5e5e7]">
                <button @click="open = !open" class="w-full flex items-center justify-between py-5 text-left group">
                    <span class="text-base font-medium text-[#1d1d1f] group-hover:text-[#0071e3] transition-colors pr-4">{{ $faq['question'] }}</span>
                    <svg :class="open ? 'rotate-45' : ''" class="w-5 h-5 text-[#86868b] shrink-0 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
                </button>
                <div x-show="open" x-collapse x-cloak>
                    <p class="text-sm text-[#6e6e73] leading-relaxed pb-5">{{ $faq['answer'] }}</p>
                </div>
            </div>
        @empty
            <p class="text-sm text-[#86868b] text-center py-12">Belum ada pertanyaan.</p>
        @endforelse

        <div class="mt-12 text-center">
            <p class="text-sm text-[#6e6e73] mb-3">Tidak menemukan jawaban?</p>
            <a href="{{ route('contact') }}" class="btn-outline-apple">Hubungi Kami</a>
        </div>
    </div>
</section>
@endsection
