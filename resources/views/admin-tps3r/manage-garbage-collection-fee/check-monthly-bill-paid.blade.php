@extends('layouts.master')

@section('title')
    Dashboard
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <select class="form-control" id="month_payment" name="month">
                                        <option value="">Pilih Bulan</option>
                                        <option value="Januari">Januari</option>
                                        <option value="Februari">Februari</option>
                                        <option value="Maret">Maret</option>
                                        <option value="April">April</option>
                                        <option value="Mei">Mei</option>
                                        <option value="Juni">Juni</option>
                                        <option value="Juli">Juli</option>
                                        <option value="Agustus">Agustus</option>
                                        <option value="September">September</option>
                                        <option value="Oktober">Oktober</option>
                                        <option value="November">November</option>
                                        <option value="Desember">Desember</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="year_payment" autocomplete="off"
                                        placeholder="Masukan Tahun">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <button onclick="checkMonthlyBillPaid()" class="btn btn-info">Cek Tagihan
                                    Lunas</button>
                                <button type="button" id="resetData" class="btn btn-danger">Reset</button>
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
                                    <th scope="col">RT</th>
                                    <th scope="col">RW</th>
                                    <th scope="col">Nominal</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Tanggal Bayar</th>
                                    <th scope="col">Action</th>
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
        let tablePaid, tableUnpaid;
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
                    {
                        "targets": 7,
                        "className": "text-center"
                    },
                    {
                        "targets": 8,
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
                        data: 'customer_neighborhood',
                    },
                    {
                        data: 'customer_community_association',
                    },
                    {
                        data: 'rubbish_fee',
                    },
                    {
                        data: 'badge_success',
                    },
                    {
                        data: 'paid_date',
                    },
                    {
                        data: 'action',
                    },
                ]
            })
        })

        function downloadProofOfPyament(customerId) {
            console.log(customerId)
        }

        function checkMonthlyBillPaid() {
            $.ajax({
                url: "{{ route('monthlyBillPaid') }}",
                type: "GET",
                data: {
                    month_payment: $("#month_payment").val(),
                    year_payment: $("#year_payment").val()
                },
                success: function(response) {
                    if (response.status == "Failed") {
                        swal({
                            title: "Danger!",
                            text: response.message,
                            icon: "warning",
                            button: "Ok!",
                            dangerMode: true,
                        });
                    } else if (response.status == "Not Found") {
                        swal({
                            title: "Danger!",
                            text: response.message,
                            icon: "warning",
                            button: "Ok!",
                            dangerMode: true,
                        });
                    } else {
                        table.clear().draw();
                        table.rows.add(response.data).draw();
                    }
                },
                error: function(response) {
                    console.log(response)
                }
            })
        }

        $('#resetData').click(function() {
            $("#month_payment").val("")
            $("#year_payment").val("")
            table.clear().draw(); // Mengosongkan DataTable
        });
    </script>
@endpush