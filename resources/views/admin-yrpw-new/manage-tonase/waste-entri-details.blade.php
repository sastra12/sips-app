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
                    @if (session()->has('failed'))
                        <div class="alert alert-danger text-center mb-2 alert-dismissible fade show" role="alert">
                            {{ session()->get('failed') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label for="start_date">Tanggal awal</label>
                                <input id="start_date" type="date"
                                    class="form-control @error('start_date') is-invalid @enderror"
                                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
                                    placeholder="Tanggal Awal" name="start_date" value="{{ old('start_date') }}">
                                @error('start_date')
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ $message }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 mb-2">
                                <label for="end_date">Tanggal akhir</label>
                                <input id="end_date" type="date"
                                    class="form-control @error('end_date') is-invalid @enderror"
                                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
                                    placeholder="Tanggal Akhir" name="end_date" value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ $message }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-md-6 col-12 mb-2">
                                        <button type="button" id="filterDataTonase"
                                            class="btn btn-primary w-100">Search</button>
                                    </div>
                                    <div class="col-md-6 col-12 mb-2">
                                        <button onclick="createDataTonaseYRPW()" class="btn btn-info w-100">Tambah
                                            Tonase</button>
                                    </div>
                                    <div class="col-md-6 col-12 mb-2">
                                        <button type="button" id="downloadData" class="btn btn-success w-100">Download
                                            Excel</button>
                                    </div>
                                    <div class="col-md-6 col-12 mb-2">
                                        <button type="button" id="resetDataTonase"
                                            class="btn btn-danger w-100">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>
    </div>
    @includeIf('admin-yrpw-new.manage-tonase.form')
    @includeIf('admin-yrpw-new.manage-tonase.edit')
@endsection

@push('script')
    <script>
        let table;
        let urlParams = new URLSearchParams(window.location.search);
        let bankId = urlParams.get('bankId');

        // Simpan data tonase
        $("#save-project-tonase").click(function(e) {
            e.preventDefault()
            storeDataTonaseYRPW()
        })

        // Update data tonase
        $("#update-project-tonase").click(function(e) {
            e.preventDefault()
            updateDataTonaseYRPW()
        })

        function createDataTonaseYRPW() {
            $("#modal-form-tonase").modal("show")
            $("#modal-form-tonase .modal-title").html("Tambah Data Tonase Sampah")

            $("#waste_organic").val("")
            $("#waste_anorganic").val("")
            $("#waste_residue").val("")
            $("#date_entri").val("")

            $('#error_list_tonase').html('')
            $('#error_list_tonase').removeClass('alert alert-danger')
        }

        function editDataTonaseYRPW(id) {
            $("#waste_entry_id").val(id)
            $.ajax({
                url: "{{ route('waste-entri.show', '') }}/" + id,
                type: "GET",
                success: function(response) {
                    $("#waste_organic_edit").val(response.waste_organic)
                    $("#waste_anorganic_edit").val(response.waste_anorganic)
                    $("#waste_residue_edit").val(response.waste_residue)
                    if (response.created_at) {
                        let dateISO = response.created_at
                        let formatDate = dateISO.substring(0, 10);
                        $("#date_entri_edit").val(formatDate);
                    }

                    $("#modal-edit-tonase").modal("show")
                    $("#modal-edit-tonase .modal-title").html("Edit Data Tonase")
                    $('#error_list_tonase_edit').html('')
                    $('#error_list_tonase_edit').removeClass('alert alert-danger')
                },
                error: function(response) {
                    console.log(response)
                }
            })
        }

        function storeDataTonaseYRPW() {
            let data = {
                waste_organic: $("#waste_organic").val(),
                waste_anorganic: $("#waste_anorganic").val(),
                waste_residue: $("#waste_residue").val(),
                date_entri: $("#date_entri").val(),
                waste_bank_id: bankId
            }
            $.ajax({
                url: "{{ route('waste-entri.store') }}",
                type: "POST",
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
                    } else if (response.status == "Error") {
                        $('#error_list_tonase').html('')
                        $('#error_list_tonase').addClass('alert alert-danger')
                        $.each(response.errors, function(key, value) {
                            $('#error_list_tonase').append('<li>' + value + '</li>')
                        })
                    } else if (response.status == "Failed") {
                        swal({
                            title: "Danger!",
                            text: response.message,
                            icon: "warning",
                            button: "Ok!",
                        });
                    }
                },
                error: function(response) {
                    console.log(response)
                }
            })
        }

        function updateDataTonaseYRPW() {
            let id = $("#waste_entry_id").val()
            let data = {
                waste_organic: $("#waste_organic_edit").val(),
                waste_anorganic: $("#waste_anorganic_edit").val(),
                waste_residue: $("#waste_residue_edit").val(),
                // date_entri: $("#date_entri").val(),
                // waste_bank_id: $("#waste_bank_id").val()
            }
            $.ajax({
                url: "{{ route('waste-entri.update', '') }}/" + id,
                type: "PUT",
                data: data,
                success: function(response) {
                    if (response.status == "Success") {
                        $('#modal-edit-tonase').modal('hide');
                        swal({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            button: "Ok!",
                        });
                        table.ajax.reload()
                    } else if (response.status = "Error") {
                        $('#error_list_tonase_edit').html('')
                        $('#error_list_tonase_edit').addClass('alert alert-danger')
                        $.each(response.errors, function(key, value) {
                            $('#error_list_tonase_edit').append('<li>' + value + '</li>')
                        })
                    }
                },
                error: function(response) {
                    console.log(response)
                }
            })
        }

        function deleteDataTonaseYRPW(entry_id) {
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
                                url: "{{ route('waste-entri.destroy', '') }}/" + entry_id,
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
                    url: "{{ route('waste-entri-data-by-waste-bank.data') }}",
                    type: 'GET',
                    data: function(d) {
                        d.bankId = bankId;
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
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
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'waste_name',
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
                ]
            });
        });

        $("#filterDataTonase").click(function(e) {
            e.preventDefault();
            table.ajax.reload()
        })

        $("#resetDataTonase").click(function(e) {
            e.preventDefault();
            $('#start_date').val("");
            $('#end_date').val("");
            table.ajax.reload()
        })

        $("#downloadData").click(function(e) {
            e.preventDefault();
            // Ambil nilai input
            let start_date = $('#start_date').val();
            let end_date = $('#end_date').val();
            let waste_id = bankId

            let downloadUrl = "{{ route('export-tonase-yrpw.data') }}?start_date=" + start_date + "&end_date=" +
                end_date + "&waste_id=" + bankId;
            // Redirect browser ke URL download
            window.location.href = downloadUrl;
        })
    </script>
@endpush
