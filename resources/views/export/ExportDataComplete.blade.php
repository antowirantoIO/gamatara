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
            <th style="height: 30px;">Code</th>
            <th style="height: 30px;">Project Name</th>
            <th style="height: 30px;">Customer Name</th>
            <th style="height: 30px;">Project Manager</th>
            <th style="height: 30px;">Contact Person Phone</th>
            <th style="height: 30px;">Project Range</th>
            <th style="height: 30px;">Deadline</th>
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
