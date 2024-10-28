@extends('layouts-new.master')

@section('content')
    <div class="main-title">
        <span class="material-icons-outlined"> space_dashboard </span>
        <span class="title">Dashboard</span>
    </div>
    @if (Auth::user()->role_id == 1)
        <div class="main-cards">
            <div class="card-box">
                <div class="card-inner">
                    <span class="card-box-title">Desa Dampingan</span>
                    <span class="material-icons-outlined"> inventory_2 </span>
                </div>
                <span class="card-box-subtitle">{{ $yrpw['villages'] }}</span>
            </div>
            <div class="card-box">
                <div class="card-inner">
                    <span class="card-box-title">TPS3R</span>
                    <span class="material-icons-outlined"> inventory_2 </span>
                </div>
                <span class="card-box-subtitle">{{ $yrpw['waste_banks'] }}</span>
            </div>
            <div class="card-box">
                <div class="card-inner">
                    <span class="card-box-title">Pelanggan</span>
                    <span class="material-icons-outlined"> inventory_2 </span>
                </div>
                <span class="card-box-subtitle">{{ $yrpw['customers'] }}</span>
            </div>
            <div class="card-box">
                <div class="card-inner">
                    <span class="card-box-title">Jumlah Admin</span>
                    <span class="material-icons-outlined"> inventory_2 </span>
                </div>
                <span class="card-box-subtitle">{{ $yrpw['users'] }}</span>
            </div>
        </div>
        {{-- Chart Tonase --}}
        <div class="charts-js">
            <div class="charts">
                <canvas id="myChart"></canvas>
            </div>
            <div class="charts">
                <canvas id="myChart1"></canvas>
            </div>
        </div>
    @elseif (Auth::user()->role_id == 2)
        <div class="main-cards-second">
            <div class="card-box">
                <div class="card-inner">
                    <span class="card-box-title">Jumlah Pelanggan</span>
                    <span class="material-icons-outlined"> inventory_2 </span>
                </div>
                <span class="card-box-subtitle">{{ $tps3r['customers'] }}</span>
            </div>
            <div class="card-box">
                <div class="card-inner">
                    <span class="card-box-title">Pelanggan Lunas {{ $tps3r['current_month'] }}</span>
                    <span class="material-icons-outlined"> inventory_2 </span>
                </div>
                <span class="card-box-subtitle">{{ $tps3r['paid'] }}</span>
            </div>
            <div class="card-box">
                <div class="card-inner">
                    <span class="card-box-title">Pelanggan Belum Lunas {{ date('F') }}</span>
                    <span class="material-icons-outlined"> inventory_2 </span>
                </div>
                <span class="card-box-subtitle">{{ $tps3r['unpaid'] }}</span>
            </div>
        </div>
    @endif

    <div class="content">

    </div>
@endsection

@push('script')
    <script>
        function formatTanggal() {
            const tanggal = new Date();

            // Array bulan dalam bahasa Indonesia
            const bulan = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];

            // Mendapatkan hari, bulan, dan tahun
            const hari = tanggal.getDate();
            const bulanIndex = tanggal.getMonth(); // 0 - 11
            const tahun = tanggal.getFullYear();

            // Mengembalikan format tanggal
            return `${hari} ${bulan[bulanIndex]} ${tahun}`;
        }

        const ctx = document.getElementById('myChart');
        const ctx1 = document.getElementById('myChart1');

        function fetchAverageWasteByCurrentDate() {
            $.ajax({
                url: "{{ route('average-tonase-by-current-date') }}", // URL route yang telah Anda buat
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Membuat chart setelah mendapatkan data
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Organik (kg)', 'Anorganik (kg)',
                                'Residu (kg)'
                            ],
                            datasets: [{
                                label: `Rata-rata tonase per ${formatTanggal()}`,
                                data: [data.avg_organic, data.avg_anorganic, data.avg_residue],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        }

        function fetchAverageTonase() {
            $.ajax({
                url: "{{ route('average-tonase') }}", // URL route yang telah Anda buat
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Membuat chart setelah mendapatkan data
                    new Chart(ctx1, {
                        type: 'line',
                        data: {
                            labels: ['Organik (kg)', 'Anorganik (kg)',
                                'Residu (kg)'
                            ],
                            datasets: [{
                                label: `Rata-rata tonase dari tanggal 1 - ${formatTanggal()}`,
                                data: [data.avg_organic, data.avg_anorganic, data.avg_residue],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            },
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        }

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            fetchAverageWasteByCurrentDate();
            fetchAverageTonase();
        });
    </script>
@endpush
