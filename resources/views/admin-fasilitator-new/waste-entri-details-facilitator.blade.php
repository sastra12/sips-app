@extends('layouts-new.master')

@section('content')
    <div class="main-title">
        <span class="material-icons-outlined"> space_dashboard </span>
        <span class="title">Dashboard</span>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    @if (session()->has('failed'))
                        <div class="alert alert-danger text-center mb-2 alert-dismissible fade show" role="alert">
                            {{ session()->get('failed') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <input id="start_date" type="date" class="form-control" aria-label="Sizing example input"
                                    aria-describedby="inputGroup-sizing-sm" placeholder="Tanggal Awal">
                            </div>
                            <div class="col-12 mb-2">
                                <input id="end_date" type="date" class="form-control" aria-label="Sizing example input"
                                    aria-describedby="inputGroup-sizing-sm" placeholder="Tanggal Akhir">
                            </div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-12 mb-2">
                                        <button type="button" id="filterDataTonase"
                                            class="btn btn-primary btn-sm w-100">Search</button>
                                    </div>
                                    <div class="col-lg-4 col-sm-12 mb-2">
                                        <button type="button" id="downloadData"
                                            class="btn btn-success btn-sm w-100">Download
                                            Excel</button>
                                    </div>
                                    <div class="col-lg-4 col-sm-12 mb-2">
                                        <button type="button" id="resetDataTonase"
                                            class="btn btn-danger btn-sm w-100">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama TPS3R</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Sampah Organik(kg)</th>
                                    <th scope="col">Sampah Anorganik(kg)</th>
                                    <th scope="col">Sampah Residu(kg)</th>
                                    <th scope="col">Total Tonase(kg)</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let table;
        let urlParams = new URLSearchParams(window.location.search);
        let bankId = urlParams.get('bankId');

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            table = $('.table').DataTable({
                ordering: false,
                processing: true,
                autowidth: false,
                ajax: {
                    url: "{{ route('waste-entri-facilitator.data') }}",
                    type: 'GET',
                    data: function(d) {
                        d.bankId = bankId;
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
                columnDefs: [{
                        "targets": 0,
                        "className": "text-center"
                    }, {
                        "targets": 1,
                        "className": "text-center"
                    },
                    {
                        "targets": 2,
                        "className": "text-center"
                    },
                    {
                        "targets": 3,
                        "className": "text-center"
                    },
                    {
                        "targets": 4,
                        "className": "text-center"
                    },
                    {
                        "targets": 5,
                        "className": "text-center"
                    },
                    {
                        "targets": 6,
                        "className": "text-center"
                    },
                ],
                columns: [{
                        data: 'DT_RowIndex',
                    },
                    {
                        data: 'waste_name',
                    },
                    {
                        data: 'tanggal_input',
                    },
                    {
                        data: 'waste_organic',
                    },
                    {
                        data: 'waste_anorganic',
                    },
                    {
                        data: 'waste_residue',
                    },
                    {
                        data: 'waste_total',
                    },
                ]

            });
        });

        $("#filterDataTonase").click(function(e) {
            e.preventDefault();
            table.ajax.reload()
        })

        $("#resetDataTonase").click(function(e) {
            e.preventDefault();
            $('#start_date').val("");
            $('#end_date').val("");
            table.ajax.reload()
        })

        $("#downloadData").click(function(e) {
            e.preventDefault();
            // Ambil nilai input
            let start_date = $('#start_date').val();
            let end_date = $('#end_date').val();
            let waste_id = bankId

            // Validasi inputan tidak boleh kosong
            if (!start_date || !end_date) {
                alert('Semua input harus diisi sebelum mendownload file!');
                return;
            }
            let downloadUrl = "{{ route('export-tonase-facilitator.data') }}?start_date=" + start_date +
                "&end_date=" +
                end_date + "&waste_id=" + bankId;
            // Redirect browser ke URL download
            window.location.href = downloadUrl;
        })
    </script>
@endpush
