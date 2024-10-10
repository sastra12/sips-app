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
                        <button onclick="createDataCustomerByTPS3R()" class="btn btn-success">Tambah Data
                            Pelanggan</button>
                        <button id="downloadDataCustomer" class="btn btn-info">Download Data
                            Pelanggan</button>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama TPS3R</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">RT</th>
                                    <th scope="col">RW</th>
                                    <th scope="col">Nominal</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
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
    @includeIf('admin-tps3r.manage-customer.form')
@endsection

@push('script')
    <script>
        let table;
        $("#save-project-btn").click(function(e) {
            e.preventDefault()
            if ($("#update_id").val() == null || $("#update_id").val() == "") {
                storeDataCustomerByTPS3R()
            } else {
                updateDataCustomerByTPS3R()
            }
        })

        function createDataCustomerByTPS3R() {
            // untuk menampilkan modal dan ganti title
            $("#modal-form").modal("show")
            $("#modal-form .modal-title").html("Tambah Data Pelanggan")

            // Untuk membuat form isian null
            $("#customer_name").val("")
            $("#customer_address").val("")
            $("#customer_neighborhood").val("")
            $("#customer_community_association").val("")
            $("#rubbish_fee").val("")

            // Membersihkan list error
            $('#error_list').html('')
            $('#error_list').removeClass('alert alert-danger')
        }

        function editDataCustomerByTPS3R(id) {
            $.ajax({
                url: "{{ route('admin-tps3r-customer.show', '') }}/" + id,
                type: "GET",
                success: function(response) {
                    $("#update_id").val(response.customer_id)
                    $("#customer_name").val(response.customer_name)
                    $("#customer_address").val(response.customer_address)
                    $("#customer_neighborhood").val(response.customer_neighborhood)
                    $("#customer_community_association").val(response.customer_community_association)
                    $("#rubbish_fee").val(response.rubbish_fee)

                    $("#modal-form").modal("show")
                    $("#modal-form .modal-title").html("Edit Data Desa Pendamping")
                    $('#error_list').html('')
                    $('#error_list').removeClass('alert alert-danger')
                },
                error: function(response) {
                    console.log(response)
                }
            })
        }

        function updateDataCustomerByTPS3R() {
            const id = $("#update_id").val()
            $.ajax({
                url: "{{ route('admin-tps3r-customer.update', '') }}/" + id,
                type: "PUT",
                data: {
                    customer_name: $("#customer_name").val(),
                    customer_address: $("#customer_address").val(),
                    customer_neighborhood: $("#customer_neighborhood").val(),
                    customer_community_association: $("#customer_community_association").val(),
                    rubbish_fee: $("#rubbish_fee").val(),
                    customer_status: $("#customer_status").val(),
                },
                success: function(response) {
                    if (response.status == "Success") {
                        $('#modal-form').modal('hide');
                        swal({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            button: "Ok!",
                        });
                        table.ajax.reload()
                        $("#update_id").val("")
                    } else if (response.status = "Error") {
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

        function storeDataCustomerByTPS3R() {
            $.ajax({
                url: "{{ route('admin-tps3r-customer.store') }}",
                type: "POST",
                data: {
                    customer_name: $("#customer_name").val(),
                    customer_address: $("#customer_address").val(),
                    customer_neighborhood: $("#customer_neighborhood").val(),
                    customer_community_association: $("#customer_community_association").val(),
                    rubbish_fee: $("#rubbish_fee").val(),
                    customer_status: $("#customer_status").val(),
                },
                success: function(response) {
                    if (response.status == "Success") {
                        $('#modal-form').modal('hide');
                        swal({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            button: "Ok!",
                        });
                        table.ajax.reload()
                    } else if (response.status = "Error") {
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

        function deleteDataCustomerByTPS3R(entry_id) {
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                                url: "{{ route('admin-tps3r-customer.destroy', '') }}/" + entry_id,
                                method: 'DELETE',
                            })
                            .done((response) => {
                                swal("Success data has been deleted!", {
                                    icon: "success",
                                });
                                table.ajax.reload();
                            })
                            .fail((errors) => {
                                swal("Failed deleted data!", {
                                    icon: "warning",
                                });
                                return;
                            });

                    } else {
                        swal("Data is safe!");
                    }
                });
        }

        $("#downloadDataCustomer").click(function(e) {
            e.preventDefault();

            let downloadUrl = "{{ route('export-customer-tps3r') }}";
            // Redirect browser ke URL download
            window.location.href = downloadUrl;
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
                    url: "{{ route('admin-tps3r-customers.data') }}",
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
                    {
                        "targets": 6,
                        "className": "text-center"
                    },
                    {
                        "targets": 7,
                        "className": "text-center"
                    }
                ],
                columns: [{
                        // buat penomoran
                        data: 'DT_RowIndex',
                    },
                    {
                        // buat penomoran
                        data: 'waste_name',
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
                        data: 'customer_status',
                    },
                    {
                        data: 'action',
                    },
                ],

            });
        });
    </script>
@endpush
