<h3>List Report Detail Vendor</h3>
<table border="1">
    <thead>
        <tr>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Code Project</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Project Name</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Start Date</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">End Date</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Bill Value</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Status Project</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td style="border: 1px solid black;">{{ $item->projects->code ?? '' }}</td>
            <td style="border: 1px solid black;">{{ $item->projects->nama_project ?? '' }}</td>
            <td style="border: 1px solid black;">{{ $item->projects->created_at ?? '' }}</td>
            <td style="border: 1px solid black;">{{ $item->projects->actual_selesai ?? '' }}</td>
            <td style="border: 1px solid black;">{{ $item->nilai_tagihan ?? '0' }}</td>
            <td style="border: 1px solid black;">{{ $item->status ?? '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>