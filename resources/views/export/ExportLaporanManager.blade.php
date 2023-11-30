<table>
    <thead>
        <tr>
            <th colspan="4" height="30" align="center"><strong>Report Project Manager</strong></th>
        </tr>
        <tr>
            <th height="30" align="center" style="border: 20px medium black;">No</th>
            <th height="30" align="center" style="border: 20px medium black;">Project Manager Name</th>
            <th height="30" align="center" style="border: 20px medium black;">On Progress</th>
            <th height="30" align="center" style="border: 20px medium black;">Complete</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td  height="20" style="border: 20px medium black;" align="center">{{ $loop->iteration }}</td>
                <td  height="20" style="border: 20px medium black;">{{ $item->karyawan->name }}</td>
                <td  height="20" style="border: 20px medium black;" align="center">{{ $item->projects->where('status', 1)->count() }}</td>
                <td  height="20" style="border: 20px medium black;" align="center">{{ $item->projects->where('status', 2)->count() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
