<table>
    <thead>
        <tr>
            <th align="center" colspan="12">REKAPITULASI TAGIHAN SUB. KONTRAKTOR</th>
        </tr>
        <tr>
            <th align="center" colspan="12">PT. SAMUDRA AMERTA KONSTRUKSI</th>
        </tr>
        {!! str_repeat('<tr></tr>', 3) !!}
        <tr>
            <th colspan="2">
                PROJECT : TK. PULAU TIGA 3017
            </th>
        </tr>
        {!! str_repeat('<tr></tr>', 2) !!}
        <tr>
            <th>No.</th>
            <th>Pekerjaan</th>
            <th>Location</th>
            <th>Detail / Other</th>
            <th>Length (mm)</th>
            <th>Width (mm)</th>
            <th>Thick (mm)</th>
            <th>Qty/Days/%</th>
            <th>Amount</th>
            <th>Unit</th>
            <th colspan="2">Tagihan Subcont</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td align="center" style="border-right: 20px medium black;">B</td>
                <td colspan="5" style="border: 20px medium black;">{{ $key }}</td>
                <td></td>
                <td></td>
            </tr>
            @foreach ($item as $value)
                <tr>
                    <td align="center">{{ $loop->iteration }}</td>
                    <td>{{ $value->pekerjaan->name ?? ' ' }}</td>
                    <td>{{ $value->id_lokasi ?? ' ' }}</td>
                    <td>{{ $value->detail ?? ' ' }}</td>
                    <td>{{ $value->length ?? ' ' }}</td>
                    <td>{{ $value->width ?? ' ' }}</td>
                    <td>{{ $value->thick ?? ' ' }}</td>
                    <td>{{ $value->qty ?? ' ' }}</td>
                    <td>{{ $value->amount ?? ' ' }}</td>
                    <td>{{ $value->unit ?? ' ' }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
