<table border="1">
    <thead>
        <tr>
            <th colspan="4" height="30" align="center"><strong>Report Project Location Detail - {{ ucwords($data->first()->lokasi->name ?? '') }}</strong></th>
        </tr>
        <tr>
            <th width="200px" height="30" align="center" style="border: 20px medium black;">Code Project</th>
            <th width="200px" height="30" align="center" style="border: 20px medium black;">Project Name</th>
            <th width="200px" height="30" align="center" style="border: 20px medium black;">Start Date</th>
            <th width="200px" height="30" align="center" style="border: 20px medium black;">End Date</th>
            <th width="200px" height="30" align="center" style="border: 20px medium black;">Project Value</th>
            <th width="200px" height="30" align="center" style="border: 20px medium black;">Project Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td height="20" style="border: 20px medium black;">{{ $item->code ?? '' }}</td>
            <td height="20" style="border: 20px medium black;">{{ $item->nama_project ?? '' }}</td>
            <td height="20" style="border: 20px medium black;">{{ $item->created_at->format('d M Y') ?? '' }}</td>
            <td height="20" style="border: 20px medium black;">{{ $item->actual_selesai ?? '' }}</td>
            <td height="20" style="border: 20px medium black;">{{ $item->nilai_project ?? '0' }}</td>
            <td height="20" style="border: 20px medium black;">{{ $item->stat }}</td>
        </tr>
        @endforeach
    </tbody>
</table>