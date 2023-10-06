<h3>List Setting Pekerjaan</h3>
<table border="1">
    <thead>
        <tr>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Nama Kategori</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Nama Sub Kategori</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Nama Pekerjaan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
            <tr>
                <td style="border: 1px solid black;">{{ $item->subkategori->kategori->name ?? ''}}</td>
                <td style="border: 1px solid black;">{{ $item->subkategori->name ?? ''}}</td>
                <td style="border: 1px solid black;">{{ $item->pekerjaan->name ?? ''}}</td>
            </tr>
       @endforeach
    </tbody>
</table>