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
                        <button onclick="createDataTonaseByTPS3R()" class="btn btn-success btn-xs"><i
                                class="fa fa-plus-circle">Tambah</i></button>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama TPS3R</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Sampah Organik(kg)</th>
                                    <th scope="col">Sampah Anorganik(kg)</th>
                                    <th scope="col">Sampah Residu(kg)</th>
                                    <th scope="col">Total Tonase(kg)</th>
                                    <th scope="col">Aksi</th>
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
    @includeIf('user-tonase.form')
@endsection

@push('styles')
    <style>

    </style>
@endpush

@push('script')
    <script>
        let table;
        // Ketika tombol save di klik
        $("#save-project-tonase").click(function(e) {
            e.preventDefault()
            if ($("#waste_entry_id").val() == null || $("#waste_entry_id").val() == "") {
                storeDataTonaseByTPS3R()
            } else {
                updateDataTonaseByTPS3R()
            }

        })

        function createDataTonaseByTPS3R() {
            console.log($("#waste_bank_id").val())
            $("#modal-form-tonase").modal("show")
            $("#modal-form-tonase .modal-title").html("Tambah Data Tonase Sampah")

            $("#waste_organic").val("")
            $("#waste_anorganic").val("")
            $("#waste_residue").val("")
            $("#date_entri").val("")

            $('#error_list_tonase').html('')
            $('#error_list_tonase').removeClass('alert alert-danger')
        }

        function storeDataTonaseByTPS3R() {
            let data = {
                waste_organic: $("#waste_organic").val(),
                waste_anorganic: $("#waste_anorganic").val(),
                waste_residue: $("#waste_residue").val(),
                date_entri: $("#date_entri").val(),
                waste_bank_id: $("#waste_bank_id").val()
            }
            $.ajax({
                url: "{{ route('waste-entri-user.store') }}",
                type: "POST",
                data: data,
                success: function(response) {
                    if (response.status == "Success") {
                        $('#modal-form-tonase').modal('hide');
                        $('#modal-form').modal('hide');
                        swal({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            button: "Ok!",
                        });
                        table.ajax.reload()
                    } else if (response.status == "Failed added") {
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

        function editDataTonaseByTPS3R(entry_id) {
            $("#waste_entry_id").val(entry_id)
            $.ajax({
                url: "{{ route('waste-entri-user.show', '') }}/" + entry_id,
                type: "GET",
                success: function(response) {
                    console.log(response)
                    $("#waste_organic").val(response.waste_organic)
                    $("#waste_anorganic").val(response.waste_anorganic)
                    $("#waste_residue").val(response.waste_residue)
                    if (response.created_at) {
                        let dateISO = response.created_at
                        let formatDate = dateISO.substring(0, 10);
                        $("#date_entri").val(formatDate);
                    }

                    $("#modal-form-tonase").modal("show")
                    $("#modal-form-tonase .modal-title").html("Edit Data Tonase")
                    $('#error_list_tonase').html('')
                    $('#error_list_tonase').removeClass('alert alert-danger')
                },
                error: function(response) {
                    console.log(response)
                }
            })
        }

        function updateDataTonaseByTPS3R() {
            let id = $("#waste_entry_id").val()
            let data = {
                waste_organic: $("#waste_organic").val(),
                waste_anorganic: $("#waste_anorganic").val(),
                waste_residue: $("#waste_residue").val(),
                date_entri: $("#date_entri").val(),
                waste_bank_id: $("#waste_bank_id").val()
            }
            $.ajax({
                url: "{{ route('waste-entri-user.update', '') }}/" + id,
                type: "PUT",
                data: data,
                success: function(response) {
                    if (response.status == "Success") {
                        $('#modal-form-tonase').modal('hide');
                        swal({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            button: "Ok!",
                        });
                        table.ajax.reload()
                    } else if (response.status = "Failed updated") {
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

        function deleteDataTonaseByTPS3R(entry_id) {
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
                                url: "{{ route('waste-entri-user.destroy', '') }}/" + entry_id,
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
                    url: "{{ route('waste-entri-user.data') }}",
                    type: 'GET'
                },
                columnDefs: [{
                    className: "dt-center",
                    targets: "_all"
                }],
                columns: [{
                        data: 'DT_RowIndex',
                    },
                    {
                        data: 'waste_name'
                    },
                    {
                        data: 'tanggal_input',
                    },
                    {
                        data: 'waste_organic',
                    },
                    {
                        data: 'waste_anorganic',
                    },
                    {
                        data: 'waste_residue',
                    },
                    {
                        data: 'waste_total',
                    },
                    {
                        data: 'action',
                    },
                ],
                createdRow: function(row, data, dataIndex) {
                    $("#waste_bank_id").val(data.waste_id)
                }
            });
        });
    </script>
@endpush
