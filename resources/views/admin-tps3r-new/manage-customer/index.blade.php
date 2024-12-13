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
                    <div class="card-header">
                        <button onclick="createDataCustomerByTPS3R()" class="btn btn-sm custom-btn-sm btn-success">Tambah Data
                            Pelanggan</button>
                        <button id="downloadDataCustomer" class="btn btn-sm custom-btn-sm btn-info">Download Data
                            Pelanggan</button>
                        <button onclick="uploadDataCustomer()" class="btn btn-sm custom-btn-sm btn-primary">Upload Data
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
        </div>
    </div>
    @includeIf('admin-tps3r-new.manage-customer.form')
    @includeIf('admin-tps3r-new.manage-customer.form-file')
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

        $("#save-file-btn").click(function(e) {
            e.preventDefault()

            let formData = new FormData()
            const file = $("#customer_file").prop('files')[0]
            formData.append('file', file)

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: "{{ route('file-customer-tps3r') }}",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                success: function(response) {
                    if (response.status == "Error") {
                        $('#error_list_file').html('')
                        $('#error_list_file').addClass('alert alert-danger')
                        $.each(response.errors, function(key, value) {
                            $('#error_list_file').append('<li>' + value + '</li>')
                        })
                    } else {
                        window.location.href = "{{ route('progress-view-tps3r') }}";
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            })
        })

        function uploadDataCustomer() {
            $("#modal-form-file").modal("show")
            $("#modal-form-file .modal-title").html("Tambah Data Pelanggan")
        }

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
                    $("#customer_status").val(response.customer_status)

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

        function deleteDataCustomerByTPS3R(entry_id) {
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
                                url: "{{ route('admin-tps3r-customer.destroy', '') }}/" + entry_id,
                                method: 'DELETE',
                            })
                            .done((response) => {
                                swal("Sukses menghapus data", {
                                    icon: "success",
                                });
                                table.ajax.reload();
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
                        data: 'waste_bank.waste_name',
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
                initComplete: function() {
                    if (table.data().count() === 0) {
                        $("#downloadDataCustomer").prop('disabled', true)
                    } else {
                        $("#downloadDataCustomer").prop('disabled', false)
                    }
                }
            });
        });
        // cek length
    </script>
@endpush
