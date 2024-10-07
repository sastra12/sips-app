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
                <div class="alert alert-danger alert-dismissible fade show error_list" id="error_list" role="alert">
                    <p id="text_error"></p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-2">
                                <input id="date" name="date_monthly_bill" type="date" class="form-control"
                                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
                                    placeholder="Tanggal Akhir">
                            </div>
                            <div class="col-sm-6">
                                <button onclick="checkMonthlyBill()" class="btn btn-info">Cek Tagihan
                                    Bulanan</button>
                                <button type="button" id="resetDataTonase" class="btn btn-danger">Reset</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">Nominal</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .error_list {
            display: none
        }
    </style>
@endpush

@push('script')
    <script>
        let table;

        function checkMonthlyBill() {

        }
        // checked selected
        $("#select_all").on('click', function() {
            if ($(this).is(':checked')) {
                $(".checkMultiple").prop('checked', true)
            } else {
                $(".checkMultiple").prop('checked', false)
            }
        })

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
                    url: "{{ route('monthly-bill.data') }}",
                    type: 'GET',
                },
                columnDefs: [{
                        "targets": 0,
                        "className": "text-center"
                    },
                    {
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
                ],
                columns: [{
                        data: 'DT_RowIndex',
                    },
                    {
                        data: 'customer_name',
                    },
                    {
                        data: 'customer_address',
                    },
                    {
                        data: 'rubbish_fee',
                    },
                    {
                        data: 'customer_status',
                    },
                ],

            });
        });
    </script>
@endpush
