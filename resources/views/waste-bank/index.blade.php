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
                        <button onclick="createDataTPS3R()" class="btn btn-success btn-xs"><i
                                class="fa fa-plus-circle">Tambah</i></button>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Bank Sampah</th>
                                    <th scope="col">Desa</th>
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
    @includeIf('waste-bank.form')
    @includeIf('waste-bank.form-edit')
@endsection

@push('styles')
    <style>

    </style>
@endpush

@push('script')
    <script>
        let table;
        // Ketika tombol save di klik
        $("#save-project-btn").click(function(e) {
            e.preventDefault()
            storeDataTPS3R()
        })
        // Ketika tombol form edit di klik
        $("#save-project-edit-btn").click(function(e) {
            e.preventDefault()
            updateDataTPS3R()
        })

        function createDataTPS3R() {
            // untuk menampilkan modal dan ganti title
            $("#modal-form").modal("show")
            $("#modal-form .modal-title").html("Tambah Data TPS3R")

            // Untuk membuat form isian null
            $("#waste_bank_name").val("")

            // Membersihkan list error
            $('#error_list').html('')
            $('#error_list').removeClass('alert alert-danger')
        }

        function storeDataTPS3R() {
            $.ajax({
                url: "{{ route('waste-bank.store') }}",
                type: "POST",
                data: {
                    waste_bank_name: $("#waste_bank_name").val(),
                    village_id: $("#village_id").val(),
                },
                success: function(response) {
                    if (response.status == "Success") {
                        $('#modal-form').modal('hide');
                        location.reload();
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

        function editDataTPS3R(id) {
            $.ajax({
                url: "{{ route('waste-bank.show', '') }}/" + id,
                type: "GET",
                success: function(response) {
                    $("#update_id").val(id)
                    $('#waste_bank_name_edit').val(response.waste_name)

                    $("#modal-form-edit").modal("show")
                    $("#modal-form-edit .modal-title").html("Edit Data TPS3R")
                    $('#error_list').html('')
                    $('#error_list').removeClass('alert alert-danger')
                },
                error: function(response) {
                    console.log(response)
                }
            })
        }

        function updateDataTPS3R() {
            const id = $("#update_id").val()
            $.ajax({
                url: "{{ route('waste-bank.update', '') }}/" + id,
                type: "PUT",
                data: {
                    waste_bank_name_edit: $("#waste_bank_name_edit").val(),
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
                                location.reload();
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

        function editForm(url) {
            // buat mengosongkan error listnya terlebih dahulu
            $('#error_list').html('')
            $('#error_list').removeClass('alert alert-danger')

            // buat menampilkan modal
            $('#modal-form-edit').modal('show')
            $('#modal-form-edit .modal-title').html('Edit Data Bank Sampah')

            // buat aksi ke method update
            $('#modal-form-edit form').attr('action', url);
            $('#modal-form-edit [name=_method]').val('put');

            $.get(url)
                .done((response) => {
                    $('#waste_bank_name_edit').val(response.waste_name)
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
                    url: "{{ route('waste-bank.data') }}",
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
                        data: 'waste_name',
                    },
                    {
                        data: 'waste_bank_village',
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
                            }).then((result) => {
                                location.reload();

                            });
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
                            $('#error_list_edit').html('')
                            $('#error_list_edit').addClass('alert alert-danger')
                            $.each(response.errors, function(key, value) {
                                $('#error_list_edit').append('<li>' + value + '</li>')
                            })
                        }
                    })
            })
        });
    </script>
@endpush
