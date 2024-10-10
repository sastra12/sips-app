@extends('layouts.master')

@section('title')
    Dashboard
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <ul id="error_list">

                </ul>
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
                                <button onclick="checkMonthlyBillUnpaid()" class="btn btn-info">Cek Tagihan
                                    Belum Lunas</button>
                                <button id="downloadData" class="btn btn-success">Download Data</button>
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

        function checkMonthlyBillUnpaid() {
            $.ajax({
                url: "{{ route('monthlyBillUnpaid') }}",
                type: "GET",
                data: {
                    month_payment: $("#month_payment").val(),
                    year_payment: $("#year_payment").val()
                },
                success: function(response) {
                    if (response.status == "Error") {
                        $('#error_list').empty()
                        $("#error_list").addClass('alert alert-danger')
                        $.each(response.errors, function(key, value) {
                            $('#error_list').append('<li>' + value + '</li>')
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
                        $('#error_list').empty()
                        $('#error_list').removeClass('alert alert-danger')
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
            // Hapus list errornya
            $('#error_list').empty()
            $('#error_list').removeClass('alert alert-danger')

            $("#month_payment").val("")
            $("#year_payment").val("")
            table.clear().draw(); // Mengosongkan DataTable
        });

        $("#downloadData").click(function(e) {
            e.preventDefault();
            // Ambil nilai input
            let month_payment = $('#month_payment').val();
            let year_payment = $('#year_payment').val();

            // Validasi inputan tidak boleh kosong
            if (!month_payment || !year_payment) {
                alert('Semua input harus diisi sebelum mendownload file!');
                return;
            }
            let downloadUrl = "{{ route('export-customer-unpaid') }}?month_payment=" + month_payment +
                "&year_payment=" +
                year_payment;
            // Redirect browser ke URL download
            window.location.href = downloadUrl;
        })
    </script>
@endpush
