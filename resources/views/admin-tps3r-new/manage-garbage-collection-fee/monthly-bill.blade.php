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
                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">Nominal</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @includeIf('admin-tps3r-new.manage-garbage-collection-fee.form')
@endsection

@push('script')
    <script>
        let table;
        $("#save-project").click(function(e) {
            e.preventDefault()
            storeDataPayment()
        })

        function addWastePayment(idCustomer, rubbishFee) {
            // untuk menampilkan modal dan ganti title
            $("#modal-form-monthly-bill").modal("show")
            $("#modal-form-monthly-bill .modal-title").html("Tambah Data Pembayaran")

            // Untuk membuat form isian null
            $("#month_monthly_bill").val("")
            $("#year_waste_payment").val("")
            $("#amount_due").val(rubbishFee)
            $("#customerId").val(idCustomer)

            // Membersihkan list error
            $('#error_list').html('')
            $('#error_list').removeClass('alert alert-danger')
        }

        function storeDataPayment() {
            $.ajax({
                url: "{{ route('store-payment') }}",
                type: "POST",
                data: {
                    customerId: $("#customerId").val(),
                    amount_due: $("#amount_due").val(),
                    month: $("#month_monthly_bill").val(),
                    year: $("#year_waste_payment").val(),
                },
                success: function(response) {
                    if (response.status == "Success") {
                        $('#modal-form-monthly-bill').modal('hide');
                        swal({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            button: "Ok!",
                        });
                    } else if (response.status == "Failed") {
                        swal({
                            title: "Danger!",
                            text: response.message,
                            icon: "warning",
                            button: "Ok!",
                            dangerMode: true,
                        });
                    } else if (response.status == "Error") {
                        $('#error_list').html('')
                        $('#error_list').addClass('alert alert-danger')
                        $.each(response.errors, function(key, value) {
                            $('#error_list').append('<li>' + value + '</li>')
                        })
                    }
                },
                error: function(response) {
                    console.log(response)
                }
            })
        }

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
                    {
                        "targets": 5,
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
                    {
                        data: 'action',
                    },
                ],
                createdRow: function(row, data, dataIndex) {
                    $("#rubbish_fee").val(data.rubbish_fee)
                }
            });
        });
    </script>
@endpush
