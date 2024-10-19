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
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ __('Daftar Penghapusan Barang') }}</h6>
                <form action="{{ route('penghapusan.index') }}" method="GET" class="form-inline mt-3">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('Cari...') }}"
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary ml-2">{{ __('Cari') }}</button>
                </form>
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
                                    <td scope="row">{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode_barang }}</td>
                                    <td>{{ $item->tanggal_penghapusan }}</td>
                                    <td>{{ $item->alasan_penghapusan }}</td>
                                    <td>{{ number_format($item->nilai_sisa, 2) }}</td>
                                    @if (
                                        $hasDelete['delete'] &&
                                            \Carbon\Carbon::parse($item->created_at)->diffInDays(now()) <= (int) env('DELETE_PERIOD_DAYS', 7))
                                        <td style="width:120px">
                                            <div class="d-flex">
                                                <form action="{{ route('penghapusan.destroy', $item->penghapusan_id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('{{ __('Are you sure to delete this record?') }}')">
                                                        <i class="fas fa-trash"></i> {{ __('Hapus') }}
                                                    </button>
                                                </form>
                                            </div>
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
