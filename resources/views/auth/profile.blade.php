@extends('layouts.customer')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="card bg-base-100 shadow-md dashboard-card">
            <div class="card-body text-center">
                <div class="avatar mb-3">
                    @if(auth()->user()->foto_profil)
                        <div class="rounded-full w-28">
                            <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="Foto Profil" class="w-full h-full object-cover rounded-full">
                        </div>
                    @else
                        <div class="bg-base-200 text-base-content/60 rounded-full w-28">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                        </div>
                    @endif
                </div>
                <h4>{{ auth()->user()->nama }}</h4>
                <p class="text-base-content/60">{{ auth()->user()->email }}</p>
                <p class="mb-0">
                    <span class="badge badge-success">{{ auth()->user()->role }}</span>
                    <span class="badge badge-info ml-1">{{ auth()->user()->status }}</span>
                </p>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md dashboard-card mt-6">
            <div class="card-body">
                <h5 class="card-title flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Informasi Akun
                </h5>
                <ul class="list-none space-y-2">
                    <li class="flex justify-between items-center">
                        <strong class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            Telepon:
                        </strong>
                        <span>{{ auth()->user()->telepon ?? '-' }}</span>
                    </li>
                    <li class="flex justify-between items-center">
                        <strong class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            Bergabung:
                        </strong>
                        <span>{{ auth()->user()->created_at->format('d M Y') }}</span>
                    </li>
                    <li class="flex justify-between items-center">
                        <strong class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h8V3a1 1 0 112 0v1h1a2 2 0 012 2v10a2 2 0 01-2 2H3a2 2 0 01-2-2V6a2 2 0 012-2h1V3a1 1 0 112 0v1h8V3a1 1 0 011 1v1H6V3a1 1 0 011-1h8a2 2 0 012 2h1a2 2 0 012 2v10a2 2 0 01-2 2H3a2 2 0 01-2-2V6a2 2 0 012-2h1V3a1 1 0 011-1zM5 8h10a1 1 0 010 2H5a1 1 0 010-2zm0 4h6a1 1 0 010 2H5a1 1 0 010-2z" clip-rule="evenodd"/>
                            </svg>
                            Poin Reward:
                        </strong>
                        <span class="text-warning font-bold">{{ auth()->user()->poin_reward }} poin</span>
                    </li>
                    <li class="flex justify-between items-center">
                        <strong class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            Login Terakhir:
                        </strong>
                        <span>{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('d M Y H:i') : '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="card bg-base-100 shadow-md dashboard-card">
            <div class="card-body">
                <h5 class="card-title flex items-center gap-2 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                    </svg>
                    Edit Profil
                </h5>

                <form method="POST" action="{{ route('profile') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="foto_profil" class="label">
                            <span class="label-text">Foto Profil</span>
                        </label>
                        <input type="file" class="file-input file-input-bordered w-full @error('foto_profil') input-error @enderror"
                               id="foto_profil" name="foto_profil" accept="image/*">
                        @error('foto_profil')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                        @if(auth()->user()->foto_profil)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="Foto Profil" class="w-20 h-20 object-cover rounded-lg">
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="mb-3">
                            <label for="nama" class="label">
                                <span class="label-text">Nama Lengkap *</span>
                            </label>
                            <input type="text" class="input input-bordered w-full @error('nama') input-error @enderror"
                                   id="nama" name="nama" value="{{ old('nama', auth()->user()->nama) }}" required>
                            @error('nama')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="label">
                                <span class="label-text">Email *</span>
                            </label>
                            <input type="email" class="input input-bordered w-full @error('email') input-error @enderror"
                                   id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="telepon" class="label">
                                <span class="label-text">Telepon *</span>
                            </label>
                            <input type="tel" class="input input-bordered w-full @error('telepon') input-error @enderror"
                                   id="telepon" name="telepon" value="{{ old('telepon', auth()->user()->telepon) }}" required>
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
                                <option value="L" {{ old('jenis_kelamin', auth()->user()->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', auth()->user()->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
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
                                  id="alamat" name="alamat" rows="3">{{ old('alamat', auth()->user()->alamat) }}</textarea>
                        @error('alamat')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="mb-3">
                            <label for="kota" class="label">
                                <span class="label-text">Kota</span>
                            </label>
                            <input type="text" class="input input-bordered w-full @error('kota') input-error @enderror"
                                   id="kota" name="kota" value="{{ old('kota', auth()->user()->kota) }}">
                            @error('kota')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="provinsi" class="label">
                                <span class="label-text">Provinsi</span>
                            </label>
                            <input type="text" class="input input-bordered w-full @error('provinsi') input-error @enderror"
                                   id="provinsi" name="provinsi" value="{{ old('provinsi', auth()->user()->provinsi) }}">
                            @error('provinsi')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kode_pos" class="label">
                                <span class="label-text">Kode Pos</span>
                            </label>
                            <input type="text" class="input input-bordered w-full @error('kode_pos') input-error @enderror"
                                   id="kode_pos" name="kode_pos" value="{{ old('kode_pos', auth()->user()->kode_pos) }}">
                            @error('kode_pos')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_lahir" class="label">
                            <span class="label-text">Tanggal Lahir</span>
                        </label>
                        <input type="date" class="input input-bordered w-full @error('tanggal_lahir') input-error @enderror"
                               id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', auth()->user()->tanggal_lahir) }}">
                        @error('tanggal_lahir')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md dashboard-card mt-6">
            <div class="card-body">
                <h5 class="card-title flex items-center gap-2 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Ubah Password
                </h5>

                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    @if(auth()->user()->auth_provider)
                        <div role="alert" class="alert alert-info mb-4 text-sm">
                            <span>Kamu mendaftar menggunakan {{ auth()->user()->auth_provider }}, kamu bisa langsung membuat password baru tanpa memasukkan password saat ini.</span>
                        </div>
                    @else
                        <div class="mb-3">
                            <label for="current_password" class="label">
                                <span class="label-text">Password Saat Ini *</span>
                            </label>
                            <label class="input input-bordered flex items-center gap-2">
                                <input type="password" class="grow @error('current_password') input-error @enderror"
                                       id="current_password" name="current_password" required>
                                <button class="btn btn-ghost btn-sm btn-square" type="button" id="toggleCurrentPassword">
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
                            @error('current_password')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="password" class="label">
                            <span class="label-text">Password Baru *</span>
                        </label>
                        <label class="input input-bordered flex items-center gap-2">
                            <input type="password" class="grow @error('password') input-error @enderror"
                                   id="password" name="password" required>
                            <button class="btn btn-ghost btn-sm btn-square" type="button" id="toggleNewPassword">
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
                            <span class="label-text">Konfirmasi Password Baru *</span>
                        </label>
                        <input type="password" class="input input-bordered w-full @error('password_confirmation') input-error @enderror"
                               id="password_confirmation" name="password_confirmation" required>
                        @error('password_confirmation')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                            </svg>
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('toggleCurrentPassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('current_password');
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

    document.getElementById('toggleNewPassword').addEventListener('click', function() {
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
</script>
@endpush
@endsection
