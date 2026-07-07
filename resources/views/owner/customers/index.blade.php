@extends('layouts.admin')

@section('title', 'Kelola Pembeli')
@section('page-title', 'Kelola Pembeli')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card rounded-4 shadow-sm p-4 mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                <div>
                    <h5 class="mb-1">Daftar Pembeli</h5>
                    <p class="text-muted mb-0">Owner dapat menghapus atau membanned pembeli di sini.</p>
                </div>
                <a href="{{ route('owner.dashboard') }}" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success rounded-4 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="card rounded-4 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Terdaftar</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>
                                    @if($customer->is_banned)
                                        <span class="badge bg-danger">Banned</span>
                                    @else
                                        <span class="badge bg-success">Aktif</span>
                                    @endif
                                </td>
                                <td>{{ $customer->created_at->format('d M Y') }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                                        <form action="{{ route('owner.customers.ban', $customer) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $customer->is_banned ? 'btn-success' : 'btn-warning' }} rounded-pill">
                                                {{ $customer->is_banned ? 'Unban' : 'Ban' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('owner.customers.destroy', $customer) }}" method="POST" class="m-0" onsubmit="return confirm('Yakin ingin menghapus pembeli ini? Aksi ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger rounded-pill">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada pembeli yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
