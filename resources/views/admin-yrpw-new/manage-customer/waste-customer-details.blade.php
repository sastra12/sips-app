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
                        @foreach ($waste_banks as $item)
                            @if ($item->waste_bank_id == request()->query('bankId'))
                                <h6>Data Pelanggan {{ $item->waste_name }}</h6>
                            @endif
                        @endforeach
                        <button onclick="createDataCustomer()" class="btn btn-sm custom-btn-sm btn-success">Tambah Data
                            Pelanggan</button>
                        <button onclick="uploadDataCustomer()" class="btn btn-sm custom-btn-sm btn-info">Upload Data
                            Pelanggan</button>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Pelanggan</th>
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
    @includeIf('admin-yrpw-new.manage-customer.form')
    @includeIf('admin-yrpw-new.manage-customer.form-file')
@endsection

@push('script')
    <script>
        let table;
        let urlParams = new URLSearchParams(window.location.search);
        let bankId = urlParams.get('bankId');

        $("#save-project-btn").click(function(e) {
            e.preventDefault()
            if ($("#update_id").val() == null || $("#update_id").val() == "") {
                storeDataCustomer()
            } else {
                updateDataCustomer()
            }
        })

        $("#save-file-btn").click(function(e) {
            e.preventDefault()

            let formData = new FormData()
            const file = $("#customer_file").prop('files')[0]
            formData.append('file', file)
            formData.append('bankId', bankId)

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: "{{ route('file-customer-yrpw') }}",
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
                        window.location.href = "{{ route('progress-view-yrpw') }}";
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

            // Membersihkan list error
            $('#error_list_file').html('')
            $('#error_list_file').removeClass('alert alert-danger')
        }

        function createDataCustomer() {
            // untuk menampilkan modal dan ganti title
            $("#modal-form").modal("show")
            $("#modal-form .modal-title").html("Tambah Data Pelanggan")

            // Untuk membuat form isian null
            $("#customer_name").val("")
            $("#customer_address").val("")
            $("#customer_neighborhood").val("")
            $("#customer_community_association").val("")
            $("#rubbish_fee").val("")
            $("#customer_status").val("")

            // Membersihkan list error
            $('#error_list').html('')
            $('#error_list').removeClass('alert alert-danger')
        }

        function editDataCustomer(id) {
            $.ajax({
                url: "{{ route('customer.show', '') }}/" + id,
                type: "GET",
                success: function(response) {
                    console.log(response)
                    $("#update_id").val(response.customer_id)
                    $("#customer_name").val(response.customer_name)
                    $("#customer_address").val(response.customer_address)
                    $("#customer_neighborhood").val(response.customer_neighborhood)
                    $("#customer_community_association").val(response.customer_community_association)
                    $("#rubbish_fee").val(response.rubbish_fee)
                    $("#customer_status").val(response.customer_status)

                    $("#modal-form").modal("show")
                    $("#modal-form .modal-title").html("Edit Data Pelanggan")
                    $('#error_list').html('')
                    $('#error_list').removeClass('alert alert-danger')
                },
                error: function(response) {
                    console.log(response)
                }
            })
        }


        function storeDataCustomer() {
            $.ajax({
                url: "{{ route('customer.store') }}",
                type: "POST",
                data: {
                    customer_name: $("#customer_name").val(),
                    customer_address: $("#customer_address").val(),
                    customer_neighborhood: $("#customer_neighborhood").val(),
                    customer_community_association: $("#customer_community_association").val(),
                    rubbish_fee: $("#rubbish_fee").val(),
                    customer_status: $("#customer_status").val(),
                    waste_id: bankId,
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

        function updateDataCustomer() {
            const id = $("#update_id").val()
            $.ajax({
                url: "{{ route('customer.update', '') }}/" + id,
                type: "PUT",
                data: {
                    customer_name: $("#customer_name").val(),
                    customer_address: $("#customer_address").val(),
                    customer_neighborhood: $("#customer_neighborhood").val(),
                    customer_community_association: $("#customer_community_association").val(),
                    rubbish_fee: $("#rubbish_fee").val(),
                    customer_status: $("#customer_status").val(),
                    waste_id: bankId,
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

        function deleteData(url) {
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
                                url: url,
                                method: 'DELETE',
                            })
                            .done((response) => {
                                if (response.status == "Success") {
                                    swal({
                                        title: "Success!",
                                        text: response.message,
                                        icon: "success",
                                        button: "Ok!",
                                    });
                                    table.ajax.reload();
                                } else if (response.status == "False") {
                                    swal({
                                        title: "Failed!",
                                        text: response.message,
                                        icon: "error",
                                        button: "Ok!",
                                    });
                                }
                            })
                    } else {
                        swal("Data tetap aman", {
                            icon: "success"
                        });
                    }
                });
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
                serverSide: true,
                autowidth: false,
                ajax: {
                    url: "{{ route('customer-by-waste-bank.data') }}",
                    type: 'GET',
                    data: {
                        bankId: bankId
                    }
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
                    }, {
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
                ]

            });
        });
    </script>
@endpush
