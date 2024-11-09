@extends('layouts-new.master')

@section('content')
    <div class="main-title">
        <span class="material-icons-outlined"> space_dashboard </span>
        <span class="title">Dashboard</span>
    </div>
    <div class="content">
        <h6>Estimasi Total Iuran Tiap Bulan {{ $waste_name->waste_name }} : Rp.
            {{ number_format($paymentTotal, 2, ',', '.') }}
        </h6>
        <h6 style="margin: 16px 0 16px 0" id="information"></h6>
        <div class="row">
            <div class="col-md-12">
                <h6>Daftar Pelanggan Yang Sudah Bayar</h6>
                <ul id="error_list_paid">

                </ul>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-12 col-lg-3">
                                <div class="form-group">
                                    <select class="form-control form-control-sm" id="month_payment_paid" name="month">
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
                            <div class="col-sm-12 col-lg-3">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" id="year_payment_paid"
                                        autocomplete="off" placeholder="Masukan Tahun">
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-3">
                                <button onclick="checkMonthlyBillPaid()" class="btn btn-info btn-sm w-100 mb-2">Cek Data
                                    Lunas</button>

                            </div>
                            <div class="col-sm-12 col-lg-3">
                                <button type="button" id="resetDataPaid"
                                    class="btn btn-danger btn-sm w-100 mb-2">Reset</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-paid">
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
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 42px">
            <div class="col-md-12">
                <h6>Daftar Pelanggan Yang Belum Bayar</h6>
                <ul id="error_list_unpaid">

                </ul>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <select class="form-control form-control-sm" id="month_payment" name="month">
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
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" id="year_payment"
                                        autocomplete="off" placeholder="Masukan Tahun">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-12 mb-2">
                                        <button onclick="checkMonthlyBillUnpaid()" class="btn btn-info btn-sm w-100">Cek
                                            Data
                                            Belum Lunas</button>
                                    </div>
                                    <div class="col-lg-4 col-sm-12 mb-2">
                                        <button type="button" id="resetDataUnpaid"
                                            class="btn btn-danger btn-sm w-100">Reset</button>
                                    </div>
                                    <div class="col-lg-4 col-sm-12 mb-2">
                                        <button id="downloadDataUnpaid" class="btn btn-success btn-sm w-100">Download
                                            Data</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-unpaid">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">RT</th>
                                    <th scope="col">RW</th>
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
@push('script')
    <script>
        let tablePaid, tableUnpaid;
        let urlParams = new URLSearchParams(window.location.search);
        let bankId = urlParams.get('bankId');
        let totalPaidThisMonth = 0;

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            tablePaid = $('.table-paid').DataTable({
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
                ]
            })

            tableUnpaid = $('.table-unpaid').DataTable({
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
                        data: 'badge_danger',
                    },
                ]
            })
        })

        function checkMonthlyBillPaid() {
            $.ajax({
                url: "{{ route('monthlyBillPaidYrpw') }}",
                type: "GET",
                data: {
                    bankId: bankId,
                    month_payment: $("#month_payment_paid").val(),
                    year_payment: $("#year_payment_paid").val()
                },
                success: function(response) {
                    if (response.status == "Error") {
                        $('#error_list_paid').empty()
                        $("#error_list_paid").addClass('alert alert-danger')
                        $.each(response.errors, function(key, value) {
                            $('#error_list_paid').append('<li>' + value + '</li>')
                        })
                    } else if (response.status == "Not Found") {
                        swal({
                            title: "Danger!",
                            text: response.message,
                            icon: "warning",
                            button: "Ok!",
                            dangerMode: true,
                        });
                    } else {
                        // Hapus list errornya
                        $('#error_list_paid').empty()
                        $('#error_list_paid').removeClass('alert alert-danger')

                        tablePaid.clear().draw();
                        tablePaid.rows.add(response.data).draw();

                        response.data.map(function(e) {
                            totalPaidThisMonth += parseInt(e.total_due_this_month)
                        })

                        if (totalPaidThisMonth > 0) {
                            $("#information").text("Total Iuran Bulan " + $("#month_payment_paid").val() +
                                " sebesar Rp. " +
                                totalPaidThisMonth.toLocaleString('id-ID', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }))
                            totalPaidThisMonth = 0;
                        } else if (totalPaidThisMonth == 0) {
                            $("#information").text("Total Iuran Bulan " + $("#month_payment_paid").val() +
                                " sebesar Rp. 0")
                        }
                    }
                },
                error: function(response) {
                    console.log(response)
                }
            })
        }

        console.log(totalPaidThisMonth)


        $('#resetDataPaid').click(function() {
            $('#error_list_paid').empty()
            $('#error_list_paid').removeClass('alert alert-danger')
            $("#month_payment_paid").val("")
            $("#year_payment_paid").val("")
            $("#information").text("")
            tablePaid.clear().draw(); // Mengosongkan DataTablePaid
        });


        function checkMonthlyBillUnpaid() {
            $.ajax({
                url: "{{ route('monthlyBillUnpaidYrpw') }}",
                type: "GET",
                data: {
                    bankId: bankId,
                    month_payment: $("#month_payment").val(),
                    year_payment: $("#year_payment").val()
                },
                success: function(response) {
                    if (response.status == "Error") {
                        $('#error_list_unpaid').empty()
                        $("#error_list_unpaid").addClass('alert alert-danger')
                        $.each(response.errors, function(key, value) {
                            $('#error_list_unpaid').append('<li>' + value + '</li>')
                        })
                    } else if (response.status == "Not Found") {
                        swal({
                            title: "Danger!",
                            text: response.message,
                            icon: "warning",
                            button: "Ok!",
                            dangerMode: true,
                        });
                    } else {
                        // Hapus list errornya
                        $('#error_list_unpaid').empty()
                        $('#error_list_unpaid').removeClass('alert alert-danger')
                        tableUnpaid.clear().draw();
                        tableUnpaid.rows.add(response.data).draw();
                    }
                },
                error: function(response) {
                    console.log(response)
                }
            })
        }

        $('#resetDataUnpaid').click(function() {
            // Hapus list errornya
            $('#error_list_unpaid').empty()
            $('#error_list_unpaid').removeClass('alert alert-danger')

            $("#month_payment").val("")
            $("#year_payment").val("")
            tableUnpaid.clear().draw(); // Mengosongkan DataTable
        });

        $("#downloadDataUnpaid").click(function(e) {
            e.preventDefault();
            // Ambil nilai input
            let month_payment = $('#month_payment').val();
            let year_payment = $('#year_payment').val();

            // Validasi inputan tidak boleh kosong
            if (!month_payment || !year_payment) {
                alert('Semua input harus diisi sebelum mendownload file!');
                return;
            }
            let downloadUrl = "{{ route('export-customer-unpaid-yrpw') }}?month_payment=" + month_payment +
                "&year_payment=" + year_payment + "&bankId=" + bankId;
            // Redirect browser ke URL download
            window.location.href = downloadUrl;
        })
    </script>
@endpush
