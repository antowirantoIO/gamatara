<h3>List Pekerjaan</h3>
<table border="1">
    <thead>
        <tr>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Nama Pekerjaan</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Length</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Width</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Thick</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Unit</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Conversion</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
            <tr>
                <td style="border: 1px solid black;">{{ $item->name }}</td>
                <td style="border: 1px solid black;">{{ $item->length }}</td>
                <td style="border: 1px solid black;">{{ $item->width }}</td>
                <td style="border: 1px solid black;">{{ $item->thick }}</td>
                <td style="border: 1px solid black;">{{ $item->unit }}</td>
                <td style="border: 1px solid black;">{{ $item->conversion }}</td>
            </tr>
       @endforeach
    </tbody>
</table>