<h3>List User</h3>
<table border="1">
    <thead>
        <tr>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Nama</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">Jabatan</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">Nomor Telpon</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">Email</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
            <tr>
                <td style="border: 1px solid black;">{{ $item->name }}</td>
                <td style="border: 1px solid black;">{{ $item->role->name ?? '' }}</td>
                <td style="border: 1px solid black;">{{ $item->nomor_telpon }}</td>
                <td style="border: 1px solid black;">{{ $item->email }}</td>
            </tr>
       @endforeach
    </tbody>
</table>