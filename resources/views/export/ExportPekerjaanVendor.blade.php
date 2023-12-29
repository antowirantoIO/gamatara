@extends('layouts.export')

@section('content-export')

<table class="table">
    <thead>
        <tr>
            <th colspan="9" style="font-size: 24px; height: 50px;">
                List Data Pekerjaan {{ $nama_project }} ({{ $nama_vendor }})
            </th>
        </tr>
        <tr>
            <th style="height: 30px; font-weight: 500;">Pekerjaan</th>
            <th style="height: 30px; font-weight: 500;">Lokasi</th>
            <th style="height: 30px; font-weight: 500;">Detail / Other</th>
            <th style="height: 30px; font-weight: 500;">Length (mm)</th>
            <th style="height: 30px; font-weight: 500;">Width (mm)</th>
            <th style="height: 30px; font-weight: 500;">Thick (mm)</th>
            <th style="height: 30px; font-weight: 500;">Qty</th>
            <th style="height: 30px; font-weight: 500;">Amount</th>
            <th style="height: 30px; font-weight: 500;">Unit</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td style="height: 30px;">{{ $item->pekerjaan->name }}</td>
                <td style="height: 30px;">{{ $item->id_lokasi }}</td>
                <td style="height: 30px;">{{ $item->detail }}</td>
                <td style="height: 30px;">{{ $item->length }}</td>
                <td style="height: 30px;">{{ $item->width }}</td>
                <td style="height: 30px;">{{ $item->thick }}</td>
                <td style="height: 30px;">{{ $item->qty }}</td>
                <td style="height: 30px;">{{ $item->amount }}</td>
                <td style="height: 30px;">{{ $item->unit }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
