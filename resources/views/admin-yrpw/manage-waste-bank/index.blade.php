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
                        <button onclick="createDataTPS3R()" class="btn btn-success btn-xs"><i
                                class="fa fa-plus-circle">Tambah</i></button>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama TPS3R</th>
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
    @includeIf('admin-yrpw.manage-waste-bank.form')
    @includeIf('admin-yrpw.manage-waste-bank.form-edit')
    @includeIf('admin-yrpw.manage-waste-bank.waste-entries')
@endsection

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
                    } else if (response.status == "Failed added") {
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

        // Ketika klik button save tonase
        $("#save-project-tonase").click(function(e) {
            e.preventDefault()
            storeDataTonase()
        })

        // Ketika tombol Tambah Tonase di klik
        function createDataTonase(waste_bank_id) {
            $("#waste_bank_id").val(waste_bank_id)

            $("#modal-form-tonase").modal("show")
            $("#modal-form-tonase .modal-title").html("Tambah Data Tonase Sampah")

            $("#waste_organic").val("")
            $("#waste_anorganic").val("")
            $("#waste_residue").val("")
            $("#date_entri").val("")

            $('#error_list_tonase').html('')
            $('#error_list_tonase').removeClass('alert alert-danger')
        }

        function storeDataTonase() {
            let id = $("#waste_bank_id").val()
            let data = {
                waste_organic: $("#waste_organic").val(),
                waste_anorganic: $("#waste_anorganic").val(),
                waste_residue: $("#waste_residue").val(),
                date_entri: $("#date_entri").val(),
                waste_bank_id: id
            }
            $.ajax({
                url: "{{ route('waste-entri.store') }}",
                type: "POST",
                data: data,
                success: function(response) {
                    if (response.status == "Success") {
                        $('#modal-form-tonase').modal('hide');
                        window.location.href = "{{ route('waste-entri.index') }}";
                    } else if (response.status = "Failed added") {
                        $('#error_list_tonase').html('')
                        $('#error_list_tonase').addClass('alert alert-danger')
                        $.each(response.errors, function(key, value) {
                            $('#error_list_tonase').append('<li>' + value + '</li>')
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
                    url: "{{ route('waste-bank.data') }}",
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
                    }
                ],
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
        });
    </script>
@endpush
