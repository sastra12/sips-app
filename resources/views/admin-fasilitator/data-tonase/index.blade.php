{{-- make extend parent view --}}
@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="container-fluid">
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-2">
                                <input id="start_date" type="date" class="form-control" aria-label="Sizing example input"
                                    aria-describedby="inputGroup-sizing-sm" placeholder="Tanggal Awal">
                            </div>
                            <div class="col-sm-2">
                                <input id="end_date" type="date" class="form-control" aria-label="Sizing example input"
                                    aria-describedby="inputGroup-sizing-sm" placeholder="Tanggal Akhir">
                            </div>
                            <div class="col-sm-3">
                                <select class="form-control" id="waste_id" name="waste_id">
                                    <option value="">Pilih TPS3R</option>
                                    @foreach ($waste_banks as $item)
                                        <option value="{{ $item->waste_bank_id }}">{{ $item->waste_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <button type="button" id="filterDataTonase" class="btn btn-primary">Search</button>
                                <button type="button" id="downloadData" class="btn btn-success">Download Excel</button>
                                <button type="button" id="resetDataTonase" class="btn btn-danger">Reset</button>
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
            <!-- /.col -->
        </div>
        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
    @includeIf('admin-yrpw.manage-tonase.form')
@endsection

@push('script')
    <script>
        let table;

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
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.waste_id = $('#waste_id').val();
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
                ],
                createdRow: function(row, data, dataIndex) {
                    $("#waste_bank_id").val(data.waste_id)
                }
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
            $('#waste_id').val("");
            table.ajax.reload()
        })

        $("#downloadData").click(function(e) {
            e.preventDefault();
            // Ambil nilai input
            let start_date = $('#start_date').val();
            let end_date = $('#end_date').val();
            let waste_id = $('#waste_id').val();

            // Validasi inputan tidak boleh kosong
            if (!start_date || !end_date || !waste_id) {
                alert('Semua input harus diisi sebelum mendownload file!');
                return;
            }
            let downloadUrl = "{{ route('export.data') }}?start_date=" + start_date + "&end_date=" +
                end_date + "&waste_id=" + waste_id;
            // Redirect browser ke URL download
            window.location.href = downloadUrl;
        })
    </script>
@endpush
