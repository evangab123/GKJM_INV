@extends('layouts.admin')
@section('title', __('Daftar Penghapusan | Inventaris GKJM'))

@section('main-content')
    @php
        use App\Helpers\PermissionHelper;
        $hasAccess = PermissionHelper::AnyCanAccessPenghapusan();
        $hasDelete = PermissionHelper::AnyCanDeletePenghapusan();
    @endphp

    <div class="container-fluid">

        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header pt-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    {{-- Search Form --}}
                    <form action="{{ route('penghapusan.index') }}" method="GET" class="form-inline">
                        <input type="text" name="search" class="form-control" placeholder="{{ __('Cari ...') }}"
                            value="{{ request('search') }}" style="max-width: 200px;">
                        <button type="submit" class="btn btn-primary ml-2">{{ __('Cari') }}</button>
                        <a href="{{ route('penghapusan.index') }}" class="btn btn-secondary ml-2">
                            <i class="fa-solid fa-arrows-rotate"></i> {{ __('Refresh') }}
                        </a>
                    </form>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">{{ __('No') }}</th>
                                <th scope="col">{{ __('Kode Barang') }}</th>
                                <th scope="col">{{ __('Tanggal Penghapusan') }}</th>
                                <th scope="col">{{ __('Alasan Penghapusan') }}</th>
                                <th scope="col">{{ __('Nilai Sisa') }}</th>
                                @if ($hasDelete['delete'])
                                    <th scope="col">{{ __('Aksi') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penghapusan as $item)
                                <tr>
                                    <td scope="row">{{ ($penghapusan->currentPage() - 1) * $penghapusan->perPage() + $loop->iteration }}</td>
                                    <td>{{ $item->kode_barang }}</td>
                                    <td>{{ $item->tanggal_penghapusan }}</td>
                                    <td>{{ $item->alasan_penghapusan }}</td>
                                    <td>{{ number_format($item->nilai_sisa, 2) }}</td>
                                    @if ($hasDelete['delete'])
                                    <td style="width: 200px;">
                                        <form action="{{ route('penghapusan.destroy', $item->penghapusan_id) }}"
                                            method="POST" style="display:inline;"
                                            onsubmit="return confirm('{{ __('Apakah Anda yakin ingin menghapus?') }}');">
                                            @csrf
                                            @method('DELETE')

                                            @php
                                                $dateDiff = \Carbon\Carbon::parse($item->created_at)->diffInDays(
                                                    now(),
                                                );
                                            @endphp

                                            <button type="submit" class="btn btn-danger"
                                                {{ $dateDiff > (int) env('DELETE_PERIOD_DAYS', 7) ? 'disabled' : '' }}>
                                                <i class="fas fa-trash"></i> {{ __(' Hapus!') }}
                                            </button>

                                        </form>
                                    </td>
                                @endif

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{ $penghapusan->links() }}
    </div>

@endsection

@push('notif')
    @if (session('success'))
        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning border-left-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success border-left-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
@endpush
