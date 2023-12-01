<table border="1">
    <thead>
        <tr>
            <th colspan="4" height="30" align="center"><strong>Report Project Location</strong></th>
        </tr>
        <tr>
            <th width="200px" height="30" align="center" style="border: 20px medium black;">Project Location Name</th>
            <th width="200px" height="30" align="center" style="border: 20px medium black;">Project Total</th>
            <th width="200px" height="30" align="center" style="border: 20px medium black;">Project Value</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
            <tr>
                <td height="20" style="border: 20px medium black;">{{ $item->name ?? '-' }}</td>
                <td height="20" style="border: 20px medium black;">{{ $item->total_project ?? '0' }}</td>
                <td height="20" style="border: 20px medium black;">{{ $item->totalHargaCustomer ?? '0' }}</td>
            </tr>
       @endforeach
    </tbody>
</table>

