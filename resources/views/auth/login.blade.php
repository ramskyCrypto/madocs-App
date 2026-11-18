<!DOCTYPE html>
<html lang="id">
<head>
    <title>Masuk &middot; MaDocs</title>
    @include('partials.head')
</head>
<body class="min-h-[100dvh] bg-canvas text-ink antialiased">
<div class="mx-auto flex min-h-[100dvh] max-w-5xl flex-col justify-center px-4 py-8 sm:px-6">
    <div class="mb-6 flex items-center gap-2.5">
        <span class="grid h-9 w-9 place-items-center rounded-lg bg-brand text-white"><x-icon name="file" class="h-5 w-5" /></span>
        <span class="text-xl font-semibold tracking-tight">MaDocs</span>
    </div>

    <div class="card grid overflow-hidden md:grid-cols-2">
        <div class="hidden bg-[#EEEEEF] md:block">
            <img src="{{ asset('images/login-siswa.jpg') }}" alt="Ilustrasi siswa MaDocs" class="block h-auto w-full mix-blend-multiply">
        </div>

        <div class="flex flex-col justify-center p-6 sm:p-10" x-data="{ email: {{ json_encode((string) old('email')) }}, pw: '', show: false }">
            <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl">Masuk ke MaDocs</h1>
            <p class="mt-2 text-sm text-mute">Satu pintu untuk dokumen akademik: peminjaman rapor, legalisir, dan pengambilan ijazah.</p>

            <form method="POST" action="{{ route('login.attempt') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="email" class="label">Alamat email</label>
                    <input id="email" name="email" type="email" x-model="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@sekolah.sch.id" class="input">
                    @error('email')<p class="err" role="alert">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password" class="label">Password</label>
                    <div class="relative">
                        <input id="password" name="password" :type="show ? 'text' : 'password'" type="password" x-model="pw" required autocomplete="current-password" placeholder="Masukkan password" class="input" style="padding-right:4.5rem">
                        <button type="button" @click="show=!show" class="absolute right-2 top-1/2 -translate-y-1/2 rounded-md px-2 py-1 text-xs font-medium text-brand-text hover:bg-soft" x-text="show ? 'Sembunyikan' : 'Tampilkan'">Tampilkan</button>
                    </div>
                    @error('password')<p class="err" role="alert">{{ $message }}</p>@enderror
                </div>
                <button class="btn btn-primary btn-block">Masuk</button>
            </form>

            <div class="mt-6 rounded-xl border border-line bg-soft p-3.5">
                <div class="text-xs font-medium">Hanya mencoba? Pakai akun demo</div>
                <p class="mt-0.5 text-xs text-mute">Klik salah satu untuk mengisi email dan password otomatis, lalu tekan Masuk.</p>
                <div class="mt-2.5 flex flex-wrap gap-2">
                    <button type="button" class="chip" @click="email='admin@madocs.test'; pw='password'">Admin</button>
                    <button type="button" class="chip" @click="email='petugas@madocs.test'; pw='password'">Petugas</button>
                    <button type="button" class="chip" @click="email='siswa1@madocs.test'; pw='password'">Siswa</button>
                </div>
                <p class="mt-2.5 text-xs text-mute">Password akun demo: <code class="rounded bg-card px-1 text-ink">password</code></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
