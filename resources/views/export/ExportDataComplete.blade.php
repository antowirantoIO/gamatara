@extends('layouts.export')

@section('content-export')
<table class="table">
    <thead>
        <tr>
            <th colspan="6" style="font-size: 24px; height: 30px;">
                List Data Complete
            </th>
        </tr>
        <tr>
            <th style="height: 30px;">Kode Project</th>
            <th style="height: 30px;">Nama Project</th>
            <th style="height: 30px;">Nama Customer</th>
            <th style="height: 30px;">Project Manager</th>
            <th style="height: 30px;">No Kontak Person</th>
            <th style="height: 30px;">Rentang Project</th>
            <th style="height: 30px;">Tengat Waktu</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td style="height: 30px;">{{ $item->code }}</td>
                <td style="height: 30px;">{{ $item->nama_project }}</td>
                <td style="height: 30px;">{{ $item->customer->name }}</td>
                <td style="height: 30px;">{{ $item->pm->karyawan->name }}</td>
                <td style="height: 30px;">{{ $item->customer->nomor_contact_person }}</td>
                <td style="height: 30px;">{{ $item->start_project }} - {{ $item->target_selesai }}</td>
                <td style="height: 30px;">{{ $item->target_selesai }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
