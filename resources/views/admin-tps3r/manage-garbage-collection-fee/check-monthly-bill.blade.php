@extends('layouts.master')

@section('title')
    Dashboard
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-2">
                                <input id="start_date" name="start_monthly_bill" type="date" class="form-control"
                                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
                                    placeholder="Tanggal Awal">
                            </div>
                            <div class="col-sm-2">
                                <input id="end_date" name="end_monthly_bill" type="date" class="form-control"
                                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
                                    placeholder="Tanggal Akhir">
                            </div>
                            <div class="col-sm-6">
                                <button onclick="checkMonthlyBill()" class="btn btn-info">Cek Tagihan
                                    Bulanan</button>
                                <button type="button" id="resetDataTonase" class="btn btn-danger">Reset</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">Nominal</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
