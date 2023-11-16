<h3>List Report Vendor</h3>
<table border="1">
    <thead>
        <tr>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Vendor Name</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Project Total</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Bill Value</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td style="border: 1px solid black;">{{ $item->name ?? '' }}</td>
            <td style="border: 1px solid black;">{{ $item->total_project ?? '0' }}</td>
            <td style="border: 1px solid black;">{{ $item->nilai_tagihan ?? '0' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>