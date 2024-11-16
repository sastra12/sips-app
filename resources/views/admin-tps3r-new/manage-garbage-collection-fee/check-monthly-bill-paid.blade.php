@extends('layouts-new.master')

@section('content')
    <div class="main-title">
        <span class="material-icons-outlined"> space_dashboard </span>
        <span class="title">Dashboard</span>
    </div>
    <div class="content">
        <h6>Estimasi Total Iuran Tiap Bulan Rp. {{ number_format($paymentTotal, 2, ',', '.') }}</h6>
        <h6 style="margin: 16px 0 16px 0" id="information"></h6>
        <div class="row">
            <div class="col-md-12">
                <ul id="error_list">

                </ul>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-12 col-lg-3">
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
                            <div class="col-sm-12 col-lg-3">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" id="year_payment"
                                        autocomplete="off" placeholder="Masukan Tahun">
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-3">
                                <button onclick="checkMonthlyBillPaid()" class="btn btn-info btn-sm w-100 mb-2">Cek Data
                                    Lunas</button>

                            </div>
                            <div class="col-sm-12 col-lg-3">
                                <button type="button" id="resetData"
                                    class="btn btn-danger btn-sm w-100 mb-2">Reset</button>
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
                                    <th scope="col">Aksi</th>
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
        let totalPaidThisMonth = 0;
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
                        data: 'total_due_this_month',
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

        function checkMonthlyBillPaid() {
            $.ajax({
                url: "{{ route('monthlyBillPaid') }}",
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
                        // Jika kosong maka reset informationnya
                        $("#information").text("")
                    } else {
                        // Hapus list errornya
                        $('#error_list').empty()
                        $('#error_list').removeClass('alert alert-danger')

                        table.clear().draw();
                        table.rows.add(response.data).draw();

                        response.data.map(function(e) {
                            totalPaidThisMonth += parseInt(e.total_due_this_month)
                        })

                        if (totalPaidThisMonth > 0) {
                            $("#information").text("Total Iuran Bulan " + $("#month_payment").val() +
                                " sebesar Rp. " +
                                totalPaidThisMonth.toLocaleString('id-ID', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }))
                            totalPaidThisMonth = 0;
                        }
                    }
                },
                error: function(response) {
                    console.log(response)
                }
            })
        }

        $('#resetData').click(function() {
            $('#error_list').empty()
            $('#error_list').removeClass('alert alert-danger')
            $("#month_payment").val("")
            $("#year_payment").val("")
            $("#information").text("")
            table.clear().draw(); // Mengosongkan DataTable
        });

        function deleteDataPayment(payment_id) {
            swal({
                    title: "Apakah kamu yakin?",
                    text: "Setelah dihapus, Anda tidak akan dapat memulihkan data ini!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                                url: "{{ route('deletePayment', '') }}/" + payment_id,
                                method: 'DELETE',
                            })
                            .done((response) => {
                                swal("Sukses menghapus data", {
                                        icon: "success",
                                        button: "Ok!",
                                    })
                                    .then(willDelete => {
                                        if (willDelete) {
                                            table.clear().draw();
                                            checkMonthlyBillPaid()
                                        }
                                    });;

                            })
                            .fail((errors) => {
                                swal("Gagal menghapus data", {
                                    icon: "warning",
                                });
                                return;
                            });

                    } else {
                        swal("Data tetap aman");
                    }
                });
        }
    </script>
@endpush
