@extends('layouts.export')

@section('content-export')
<table class="table">
    <thead>
        <tr>
            <th colspan="6" style="font-size: 24px; height: 30px;">
                List Data On Progres
            </th>
        </tr>
        <tr>
            <th style="height: 30px;">Kode Project</th>
            <th style="height: 30px;">Nama Project</th>
            <th style="height: 30px;">Nama Customer</th>
            <th style="height: 30px;">Project Manager</th>
            <th style="height: 30px;">Tanggal Mulai</th>
            <th style="height: 30px;">Tanggal Selesai</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td style="height: 30px;">{{ $item->code }}</td>
                <td style="height: 30px;">{{ $item->nama_project ?? '' }}</td>
                <td style="height: 30px;">{{ $item->customer->name ?? '' }}</td>
                <td style="height: 30px;">{{ $item->pm ? ($item->pm->karyawan ? $item->pm->karyawan->name : '') : '' }}</td>
                <td style="height: 30px;">{{ $item->created_at ?? '' }}</td>
                <td style="height: 30px;">{{ $item->target_selesai ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
