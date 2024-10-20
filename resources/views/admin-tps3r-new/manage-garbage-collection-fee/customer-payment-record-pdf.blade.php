<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <!------ Include the above in your HEAD tag ---------->
    <style>
        .invoice-title {
            margin-top: 24px
        }

        .invoice-title h2,
        .invoice-title h3 {
            display: inline-block;
        }

        .table>tbody>tr>.no-line {
            border-top: none;
        }

        .table>thead>tr>.no-line {
            border-bottom: none;
        }

        .table>tbody>tr>.thick-line {
            border-top: 2px solid;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="invoice-title">
                    <img width="100" height="75" src="{{ asset('images/yrpw.jpg') }}">
                    <h3 class="pull-right">Yayasan "Rijig Pradana Wetan"</h3>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-6">
                        <address>
                            <strong>Pelanggan:</strong><br>
                            {{ $customer->customer_name }}.<br>
                            {{ $customer->customer_address }}<br>
                            {{ $customer->waste_bank->waste_name }}<br>
                        </address>
                    </div>
                    <div class="col-xs-6 text-right">
                        <address>
                            <strong>Admin TPS3R:</strong><br>
                            {{ Auth::user()->name }}<br>
                            {{ $customer->waste_bank->waste_name }}<br>
                        </address>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Detail Pembayaran</strong></h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-condensed">
                                <thead>
                                    <tr>
                                        <td><strong>Tanggal Bayar</strong></td>
                                        <td class="text-center"><strong>Bulan</strong></td>
                                        <td class="text-center"><strong>Tahun</strong></td>
                                        <td class="text-right"><strong>Nominal</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total = 0;
                                    @endphp
                                    <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                    @foreach ($customer->waste_payments as $payment)
                                        <tr>
                                            <td> {{ date('d F Y', strtotime($payment->created_at)) }}</td>
                                            <td class="text-center">{{ $payment->month_payment }}</td>
                                            <td class="text-center">{{ $payment->year_payment }}</td>
                                            <td class="text-right">
                                                {{ number_format($payment->amount_due, 2, ',', '.') }}</td>
                                        </tr>
                                        @php
                                            $total = $total + $payment->amount_due;
                                        @endphp
                                    @endforeach

                                    {{-- Total --}}
                                    <tr>
                                        <td class="no-line"></td>
                                        <td class="no-line"></td>
                                        <td class="no-line text-center"><strong>Total</strong></td>
                                        <td class="no-line text-right">{{ number_format($total, 2, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>
