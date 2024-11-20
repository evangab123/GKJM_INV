@extends('layouts.admin')

@section('title', 'Dashboard | Inventaris GKJM')

@section('main-content')

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Dashboard') }}</h1>

    @if (session('success'))
    <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
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

    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xl font-weight-bold text-primary text-uppercase mb-1">Jenis Barang</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$jenisBarang}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-th-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Jumlah Barang </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$totalBarang}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Barang yang Dipinjam</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$barangPinjam}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-handshake fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{ __('Users') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $widget['users'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <br>
                    <h4>Status Barang</h4>
                    <canvas id="statusChart" style="width:100%; height:300px;  max-width: 500px; max-height: 300px;"></canvas>
                </div>
                <div class="col-md-6">
                    <br>
                    <h4>Kategori Barang</h4>
                    <canvas id="katChart" style="width:100%; height:300px;  max-width: 500px; max-height: 300px;"></canvas>
                </div>
                <div class="col-md-6">
                    <br>
                    <h4>Peminjaman dan Penghapusan</h4>
                    <canvas id="tren" style="width:100%; height:300px;  max-width: 500px; max-height: 300px;"></canvas>
                </div>
                <div class="col-md-6">
                    <br>
                    <h4>Persentase Perolehan</h4>
                    <canvas id="perolehanChart" style="width:100%; height:300px;  max-width: 500px; max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const chart1 = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(chart1, {
        type: 'doughnut',
        data: {
            labels: @json($statusLabels),
            datasets: [{
                label: 'Jumlah',
                data: @json($statusData),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    'rgba(255, 90, 94, 0.7)'
                ],
                borderColor: 'rgba(255, 255, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
    console.log(@json($katLabels));
    console.log(@json($katCounts));
    const chart2 = document.getElementById('katChart').getContext('2d')
    const katChart = new Chart(chart2, {
        type: 'bar',
        data: {
            labels: @json($katLabels), 
            datasets: [{
                label: 'Jumlah Barang',
                data: @json($katCounts), 
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { 
                    beginAtZero: true 
                }
            }
        }
    });

    const chart3 = document.getElementById('tren').getContext('2d');
    const Baptis = new Chart(chart3, {
        type: 'line',
        data: {
            labels: @json($penghapusanLabel), // Labels for X-axis (Months)
            datasets: [
                {
                    label: 'Penghapusan',
                    data: @json($penghapusanData), // Data for deletion trends
                    backgroundColor: 'rgba(255, 153, 153, 0.5)',
                    borderColor: 'rgba(255, 153, 153, 1)',
                    borderWidth: 2,
                    fill: true,
                },
                {
                    label: 'Peminjaman',
                    data: @json($peminjamanData), // Data for loan trends
                    backgroundColor: 'rgba(255, 222, 41, 0.5)',
                    borderColor: 'rgba(255, 222, 41, 1)',
                    borderWidth: 2,
                    fill: true,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah',
                    },
                },
                x: {
                    title: {
                        display: true,
                        text: 'Bulan',
                    },
                },
            },
        },
    });
    const chart4 = document.getElementById('perolehanChart').getContext('2d');
        const perolehanChart = new Chart(chart4, {
            type: 'pie',
            data: {
                labels: @json($perolehanLabels),
                datasets: [{
                    label: 'Jumlah',
                    data: @json($perolehanData),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(255, 90, 94, 0.7)'
                    ],
                    borderColor: 'rgba(255, 255, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });

</script>
@endpush