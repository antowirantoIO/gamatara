<h3>List Pekerjaan</h3>
<table border="1">
    <thead>
        <tr>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Nama Pekerjaan</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Unit</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Konversi</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Harga Vendor</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Harga Customer</th>
           
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
            <tr>
                <td style="border: 1px solid black;">{{ $item->name }}</td>
                <td style="border: 1px solid black;">{{ $item->unit ?? '-' }}</td>
                <td style="border: 1px solid black;">{{ $item->konversi }}</td>
                <td style="border: 1px solid black;">{{ $item->harga_vendor }}</td>
                <td style="border: 1px solid black;">{{ $item->harga_customer }}</td>
            </tr>
       @endforeach
    </tbody>
</table>