@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<div class="h-full flex items-center justify-center py-8">
    <div class="w-full max-w-2xl">
            <div class="card bg-base-100 shadow-md">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="font-bold text-primary flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                            </svg>
                            Sewa Kamera Pro
                        </h2>
                        <p class="text-base-content/60">Buat akun baru untuk mulai menyewa</p>
                    </div>
                    
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="mb-3">
                                <label for="nama" class="label">
                                    <span class="label-text">Nama Lengkap *</span>
                                </label>
                                <label class="input input-bordered flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-70 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    <input type="text" class="grow @error('nama') input-error @enderror" 
                                           id="nama" name="nama" value="{{ old('nama') }}" required 
                                           placeholder="John Doe">
                                </label>
                                @error('nama')
                                    <span class="text-error text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="label">
                                    <span class="label-text">Email Address *</span>
                                </label>
                                <label class="input input-bordered flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-70 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                    <input type="email" class="grow @error('email') input-error @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required 
                                           placeholder="nama@example.com">
                                </label>
                                @error('email')
                                    <span class="text-error text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        
                            <div class="mb-3">
                                <label for="password" class="label">
                                    <span class="label-text">Password *</span>
                                </label>
                                <label class="input input-bordered flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-70 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <input type="password" class="grow @error('password') input-error @enderror" 
                                           id="password" name="password" required placeholder="••••••••">
                                    <button class="btn btn-ghost btn-sm btn-square" type="button" id="togglePassword">
                                        <svg class="h-4 w-4 eye-icon-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <svg class="h-4 w-4 eye-icon-closed hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                                            <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
                                        </svg>
                                    </button>
                                </label>
                                @error('password')
                                    <span class="text-error text-sm">{{ $message }}</span>
                                @enderror
                                <span class="text-base-content/60 text-sm">Minimal 6 karakter</span>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password_confirmation" class="label">
                                    <span class="label-text">Konfirmasi Password *</span>
                                </label>
                                <label class="input input-bordered flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-70 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <input type="password" class="grow" 
                                           id="password_confirmation" name="password_confirmation" required 
                                           placeholder="••••••••">
                                </label>
                            </div>
                        
                            <div class="mb-3">
                                <label for="telepon" class="label">
                                    <span class="label-text">Nomor Telepon *</span>
                                </label>
                                <label class="input input-bordered flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-70 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                    <input type="tel" class="grow @error('telepon') input-error @enderror" 
                                           id="telepon" name="telepon" value="{{ old('telepon') }}" required 
                                           placeholder="08123456789">
                                </label>
                                @error('telepon')
                                    <span class="text-error text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="label">
                                    <span class="label-text">Jenis Kelamin</span>
                                </label>
                                <select class="select select-bordered w-full @error('jenis_kelamin') select-error @enderror" 
                                        id="jenis_kelamin" name="jenis_kelamin">
                                    <option value="">Pilih...</option>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <span class="text-error text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="alamat" class="label">
                                <span class="label-text">Alamat</span>
                            </label>
                            <textarea class="textarea textarea-bordered w-full @error('alamat') textarea-error @enderror" 
                                      id="alamat" name="alamat" rows="2" 
                                      placeholder="Jl. Contoh No. 123">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="mb-3">
                                <label for="kota" class="label">
                                    <span class="label-text">Kota</span>
                                </label>
                                <input type="text" class="input input-bordered w-full @error('kota') input-error @enderror" 
                                       id="kota" name="kota" value="{{ old('kota') }}" 
                                       placeholder="Jakarta">
                                @error('kota')
                                    <span class="text-error text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="provinsi" class="label">
                                    <span class="label-text">Provinsi</span>
                                </label>
                                <input type="text" class="input input-bordered w-full @error('provinsi') input-error @enderror" 
                                       id="provinsi" name="provinsi" value="{{ old('provinsi') }}" 
                                       placeholder="DKI Jakarta">
                                @error('provinsi')
                                    <span class="text-error text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="label">
                                <span class="label-text">Tanggal Lahir</span>
                            </label>
                            <input type="date" class="input input-bordered w-full @error('tanggal_lahir') input-error @enderror" 
                                   id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                            @error('tanggal_lahir')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="flex items-start gap-2 cursor-pointer">
                                <input type="checkbox" class="checkbox mt-1 @error('agree_terms') checkbox-error @enderror" 
                                       id="agree_terms" name="agree_terms" required>
                                <span class="label-text">
                                    Saya setuju dengan <a href="#" class="no-underline text-primary">Syarat & Ketentuan</a> 
                                    dan <a href="#" class="no-underline text-primary">Kebijakan Privasi</a>
                                </span>
                            </label>
                            @error('agree_terms')
                                <span class="text-error text-sm block">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="divider">atau daftar dengan</div>

                        <div class="flex justify-center gap-3 mb-4">
                            <a href="{{ route('auth.google') }}" class="btn btn-outline btn-primary flex-1">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                </svg>
                                Google
                            </a>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-full mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z"/>
                                <path fill-rule="evenodd" d="M16 7a1 1 0 00-1 1v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Daftar Akun
                        </button>
                        
                        <div class="text-center">
                            <p class="mb-0">Sudah punya akun? 
                                <a href="{{ route('login') }}" class="no-underline text-primary">Masuk di sini</a>
                            </p>
                            <p class="mt-2">
                                <a href="{{ route('home') }}" class="no-underline text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    Kembali ke beranda
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const openIcon = this.querySelector('.eye-icon-open');
        const closedIcon = this.querySelector('.eye-icon-closed');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            openIcon.classList.add('hidden');
            closedIcon.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            openIcon.classList.remove('hidden');
            closedIcon.classList.add('hidden');
        }
    });
    
    const today = new Date();
    const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
    document.getElementById('tanggal_lahir').max = maxDate.toISOString().split('T')[0];
</script>
@endpush
@endsection
