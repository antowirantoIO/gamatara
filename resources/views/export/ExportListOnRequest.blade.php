<h3>List On Request</h3>
<table border="1">
    <thead>
        <tr>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Kode Project</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">Nama Project</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">Nama Customer</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">Tanggal Request</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">Displacement Kapal</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">Jenis Kapal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
            <tr>
                <td style="border: 1px solid black;">{{ $item->code }}</td>
                <td style="border: 1px solid black;">{{ $item->nama_project }}</td>
                <td style="border: 1px solid black;">{{ $item->customer->name }}</td>
                <td style="border: 1px solid black;">{{ $item->created_at->format('d-m-Y H:i') }}</td>
                <td style="border: 1px solid black;">{{ $item->displacement }}</td>
                <td style="border: 1px solid black;">{{ $item->kapal->name }}</td>
            </tr>
       @endforeach
    </tbody>
</table>