<section>
    <header class="mb-3">
        <h2 class="h5 mb-1">Perbarui Kata Sandi</h2>
        <p class="text-muted small mb-0">Pastikan kata sandi Anda panjang dan aman.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-3">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">Kata Sandi Saat Ini</label>
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="form-text text-danger" />
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label">Kata Sandi Baru</label>
            <x-text-input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="form-text text-danger" />
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="form-text text-danger" />
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-dark">Simpan Password</button>
            @if (session('status') === 'password-updated')
                <div class="text-success ms-2">{{ __('Saved.') }}</div>
            @endif
        </div>
    </form>
</section>
