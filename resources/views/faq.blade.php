@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
<section class="bg-base-100">
    <div class="max-w-3xl mx-auto px-4 py-16">
        <p class="text-xs text-base-content/40 uppercase tracking-wider mb-2">FAQ</p>
        <h1 class="text-3xl font-light tracking-tight mb-8">Pertanyaan Umum</h1>

        <div class="space-y-2">
            @forelse($faqs as $faq)
            <div class="collapse collapse-arrow bg-base-100 border border-base-300 rounded-box">
                <input type="radio" name="faq-accordion" {{ $loop->first ? 'checked="checked"' : '' }}>
                <div class="collapse-title text-sm font-medium min-h-0 py-3">
                    {{ $faq['question'] }}
                </div>
                <div class="collapse-content">
                    <p class="text-sm text-base-content/60 leading-relaxed">{{ $faq['answer'] }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-base-content/40 text-center py-8">Belum ada pertanyaan.</p>
            @endforelse
        </div>

        <div class="mt-10 text-center">
            <p class="text-sm text-base-content/50 mb-3">Tidak menemukan jawaban?</p>
            <a href="{{ route('contact') }}" class="btn btn-outline btn-neutral btn-sm">Hubungi Kami</a>
        </div>
    </div>
</section>
@endsection
