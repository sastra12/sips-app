{{-- make extend parent view --}}
@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
    @parent

    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{-- <button onclick="addForm('{{ route('user.store') }}')" class="btn btn-success btn-xs"><i
                                class="fa fa-plus-circle">Tambah</i></button> --}}
                        <button onclick="createDataAdmin()" class="btn btn-success btn-xs"><i
                                class="fa fa-plus-circle">Tambah</i></button>

                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Role User</th>
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
    @includeIf('admin.form')
    @includeIf('admin.form-edit')
@endsection

@push('styles')
    <style>

    </style>
@endpush

@push('script')
    <script>
        let table;
        // Ketika tombol save di klik
        $("#save-btn").click(function(e) {
            e.preventDefault()
            storeDataAdmin()
        })

        $("#save-project-btn").click(function(e) {
            e.preventDefault()
            updateDataAdmin()
        })

        // Value for username and password
        $("#name").on("keyup", function() {
            let inputName = $("#name").val().toLowerCase().split(" ").join("")
            $("#username").val(inputName);
            $("#password").val(inputName);
        })

        function createDataAdmin() {
            // untuk menampilkan modal dan ganti title
            $("#modal-form").modal("show")
            $("#modal-form .modal-title").html("Tambah Data Admin")

            // Untuk membuat form isian null
            $("#name").val("")

            // Membersihkan list error
            $('#error_list').html('')
            $('#error_list').removeClass('alert alert-danger')
        }

        function editDataAdmin(id) {
            $.ajax({
                url: "{{ route('user.show', '') }}/" + id,
                type: "GET",
                success: function(response) {
                    $("#update_id").val(id)
                    $('#role_user_form').val(response.role_id)

                    $("#modal-form-edit").modal("show")
                    $("#modal-form-edit .modal-title").html("Edit Data Admin")
                    $('#error_list').html('')
                    $('#error_list').removeClass('alert alert-danger')
                },
                error: function(response) {
                    console.log(response)
                }
            })
        }

        function storeDataAdmin() {
            // definisikan objek data 
            let data = {
                name: $("#name").val(),
                username: $("#username").val(),
                password: $("#password").val(),
                role_user: $("#role_user").val(),
            }

            if (data.role_user == 2) {
                // tambahkan properties waste_name ke data
                data.waste_name = $("#waste_name").val()
            }

            $.ajax({
                url: "{{ route('user.store') }}",
                type: "POST",
                data: data,
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
                    } else if (response.status = "Failed added") {
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

        function updateDataAdmin() {
            const id = $("#update_id").val()
            $.ajax({
                url: "{{ route('user.update', '') }}/" + id,
                type: "PUT",
                data: {
                    role: $("#role_user_form").val(),
                },
                success: function(response) {
                    if (response.status == "Success") {
                        $('#modal-form-edit').modal('hide');
                        swal({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            button: "Ok!",
                        });
                        table.ajax.reload()
                        $("#update_id").val("")
                    } else if (response.status = "Failed updated") {
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
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this data!",
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

        $("#role_user").change(function() {
            let userRole = $(this).val()
            if (userRole == 2) {
                $.ajax({
                    url: "{{ route('waste-bank.unassigned') }}",
                    type: 'GET',
                    success: function(response) {
                        $("#waste_name_group").show()
                        $("#waste_name").empty(); // Kosongkan select sebelum menambahkan opsi baru
                        $("#waste_name").append('<option value="">Pilih TPS3R</option>');

                        response.data.map(function(value) {
                            $("#waste_name").append('<option value="' + value.waste_bank_id +
                                '">' + value
                                .waste_name + '</option>')
                        })
                    },
                    error: function(response) {
                        console.log(response)
                    }
                })
            }
        })

        $('#modal-form').on('hidden.bs.modal', function() {
            // Kosongkan select ketika modal ditutup
            $("#waste_name_group").hide()
            $("#waste_name").empty();

        });


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
                    url: "{{ route('user.data') }}",
                    type: 'GET'
                },
                columnDefs: [{
                    className: "dt-center",
                    targets: "_all"
                }],
                columns: [{
                        // buat penomoran
                        data: 'DT_RowIndex',
                    },
                    {
                        data: 'name',
                    },
                    {
                        data: 'username',
                    },
                    {
                        data: 'role_name',
                    },
                    {
                        data: 'action',
                    },
                ]

            });

            // Response when success or failed when submit button
            $('#modal-form form').on('submit', function(e) {
                e.preventDefault()
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        if (response.message == 'Success Added Data') {
                            $('#modal-form').modal('hide');
                            swal({
                                title: "Success!",
                                text: response.message,
                                icon: "success",
                                button: "Ok!",
                            });
                            table.ajax.reload()
                        } else if (response.status == 'Failed added') {
                            $('#error_list').html('')
                            $('#error_list').addClass('alert alert-danger')
                            $.each(response.errors, function(key, value) {
                                $('#error_list').append('<li>' + value + '</li>')
                            })
                        }
                    })
            })

            $('#modal-form-edit form').on('submit', function(e) {
                e.preventDefault()
                $.post($('#modal-form-edit form').attr('action'), $('#modal-form-edit form').serialize())
                    .done((response) => {
                        if (response.message == 'Success Updated Data') {
                            $('#modal-form-edit').modal('hide');
                            swal({
                                title: "Success!",
                                text: response.message,
                                icon: "success",
                                button: "Ok!",
                            });
                            table.ajax.reload()
                        } else if (response.status == 'Failed Updated Data') {
                            $('#error_list').html('')
                            $('#error_list').addClass('alert alert-danger')
                            $.each(response.errors, function(key, value) {
                                $('#error_list').append('<li>' + value + '</li>')
                            })
                        }
                    })
            })




        });
    </script>
@endpush
