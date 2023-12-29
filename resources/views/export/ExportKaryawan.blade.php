<h3>List Employee</h3>
<table border="1">
    <thead>
        <tr>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Employee Name</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">Job Title</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">Address</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">Phone</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">Email</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
            <tr>
                <td style="border: 1px solid black;">{{ $item->name }}</td>
                <td style="border: 1px solid black;">{{ $item->jabatan }}</td>
                <td style="border: 1px solid black;">{{ $item->alamat }}</td>
                <td style="border: 1px solid black;">{{ $item->nomor_telpon }}</td>
                <td style="border: 1px solid black;">{{ $item->email }}</td>
            </tr>
       @endforeach
    </tbody>
</table>