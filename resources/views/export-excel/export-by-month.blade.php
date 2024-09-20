<table>
    <thead>
        <tr>
            <th colspan="8" style="text-align: center">{{ $waste_entries[0]->waste_bank->waste_name }}</th>
        </tr>
        <tr>
            <th style="text-align: center;">No</th>
            <th style="text-align: center;">Tanggal</th>
            <th style="text-align: center;">Sampah Organik (Kg)</th>
            <th style="text-align: center;">Sampah Anorganik (Kg)</th>
            <th style="text-align: center;">Sampah Residu (Kg)</th>
            <th style="text-align: center;">Total Tonase (Kg)</th>
            <th style="text-align: center;">Pengurangan Sampah (%)</th>
            <th style="text-align: center;">Pengurangan Residu (%)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($waste_entries as $value)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td style="text-align: center;">{{ date('d F Y', strtotime($value->created_at)) }}</td>
                <td style="text-align: center;">{{ $value->waste_organic }}</td>
                <td style="text-align: center;">{{ $value->waste_anorganic }}</td>
                <td style="text-align: center;">{{ $value->waste_residue }}</td>
                <td style="text-align: center;">{{ $value->waste_total }}</td>
                <td style="text-align: center;">
                    {{ round((($value->waste_organic + $value->waste_anorganic) / $value->waste_total) * 100) }}%
                </td>
                <td style="text-align: center;">
                    {{ round(($value->waste_residue / $value->waste_total) * 100) }}%</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td><strong>TOTAL / RERATA</strong></td>
            <td></td>
            <td style="text-align: center"><strong>{{ $wasteOrganicTotal }}</strong></td>
            <td style="text-align: center"><strong>{{ $wasteAnorganicTotal }}</strong></td>
            <td style="text-align: center"><strong>{{ $wasteResidueTotal }}</strong></td>
            <td style="text-align: center"><strong>{{ $tonaseTotal }}</strong></td>
            <td style="text-align: center"><strong>{{ $wasteReductionTotal }}%</strong></td>
            <td style="text-align: center"><strong>{{ $residueDisposeTotal }}%</strong></td>
        </tr>
    </tfoot>
</table>
