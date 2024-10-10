<table>
    <thead>
        <tr>
            <th colspan="8" style="text-align: center">{{ $customers[0]['waste_name'] }}</th>
        </tr>
        <tr>
            <th style="text-align: center;">No</th>
            <th style="text-align: center;">Nama TPS3R</th>
            <th style="text-align: center;">Nama Pelanggan</th>
            <th style="text-align: center;">Alamat</th>
            <th style="text-align: center;">RT</th>
            <th style="text-align: center;">RW</th>
            <th style="text-align: center;">Iuran</th>
            <th style="text-align: center;">Status Pembayaran</th>
            <th style="text-align: center;">Tanggal Join</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customers as $customer)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td style="text-align: center;">{{ $customer['waste_name'] }}</td>
                <td style="text-align: center;">{{ $customer['customer_name'] }}</td>
                <td style="text-align: center;">{{ $customer['customer_address'] }}</td>
                <td style="text-align: center;">{{ $customer['customer_neighborhood'] }}</td>
                <td style="text-align: center;">{{ $customer['customer_community_association'] }}</td>
                <td style="text-align: center;">{{ $customer['rubbish_fee'] }}</td>
                <td style="text-align: center;">{{ $customer['monthly_bill_status'] }}</td>
                <td style="text-align: center;">{{ date('d F Y', strtotime($customer['created_at'])) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
