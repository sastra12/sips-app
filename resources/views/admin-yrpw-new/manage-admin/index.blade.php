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
                        <button onclick="createDataAdmin()" class="btn btn-sm btn-success">Tambah</button>
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
        </div>
    </div>
    @includeIf('admin-yrpw-new.manage-admin.form')
    @includeIf('admin-yrpw-new.manage-admin.form-edit')
@endsection

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
            $("#username").val("")
            $("#password").val("")
            $("#role_user").val("")

            // Membersihkan list error
            $('#error_list').html('')
            $('#error_list').removeClass('alert alert-danger')
        }

        function editDataAdmin(id) {
            // Kosongkan select ketika modal ditutup
            $("#waste_name_group_edit").hide()
            $("#waste_name_edit").empty();

            // Membersihkan list error
            $('#error_list_edit').html('')
            $('#error_list_edit').removeClass('alert alert-danger')

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

        function updateDataAdmin() {
            const id = $("#update_id").val()
            let data = {
                role: $("#role_user_form").val(),
            }
            if (data.role == 2) {
                data.waste_name = $("#waste_name_edit").val()
            }
            $.ajax({
                url: "{{ route('user.update', '') }}/" + id,
                type: "PUT",
                data: data,
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
                    } else if (response.status = "Error") {
                        $('#error_list_edit').html('')
                        $('#error_list_edit').addClass('alert alert-danger')
                        $.each(response.errors, function(key, value) {
                            $('#error_list_edit').append('<li>' + value + '</li>')
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
                        swal("Data tetap aman");
                    }
                });
        }

        // Buat Edit data admin
        $("#role_user_form").change(function() {
            let userRole = $(this).val()
            if (userRole == 2) {
                $.ajax({
                    url: "{{ route('waste-bank.unassigned') }}",
                    type: 'GET',
                    success: function(response) {
                        $("#waste_name_group_edit").show()
                        $("#waste_name_edit").empty();
                        $("#waste_name_edit").append('<option value="">Pilih TPS3R</option>');

                        response.data.map(function(value) {
                            $("#waste_name_edit").append('<option value="' + value
                                .waste_bank_id +
                                '">' + value
                                .waste_name + '</option>')
                        })
                    },
                    error: function(response) {
                        console.log(response)
                    }
                })
            } else {
                $("#waste_name_group_edit").hide();
                $("#waste_name_edit").empty();
            }
        })


        // Buat store data admin
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
            } else {
                $("#waste_name_group").hide(); // Sembunyikan select option
                $("#waste_name").empty(); // Kosongkan select option
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
                    }
                ],
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
        });
    </script>
@endpush
