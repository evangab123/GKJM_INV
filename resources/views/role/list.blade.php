@extends('layouts.admin')
@section('title', 'List Roles | Inventaris GKJM')
@section('main-content')
    {{-- <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ $title ?? __('Blank Page') }}</h1> --}}

    <!-- Main Content goes here -->

    <a href="{{ route('role.create') }}" class="btn btn-primary mb-3">Buat Role Pengguna!</a>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <table class="table table-bordered table-stripped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Permission</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($Roles as $Role)
                <tr>
                    <td scope="row">{{ $loop->iteration }}</td>
                    <td>{{ $Role->name }}</td>
                    <td>

                    </td>
                    <td>
                        <div class="d-flex">
                            <a href="#" class="btn btn-sm btn-primary mr-2">
                                <i class="fa-solid fa-pen-to-square"></i>Edit
                            </a>
                            <form action="#" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this?')">
                                    <i class="fas fa-trash"></i>Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $Roles->links() }}

    <!-- End of Main Content -->
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
