<h3>List Vendor</h3>
<table border="1">
    <thead>
        <tr>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Vendor Name</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">Address</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">Contact Person</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">Contact Person Phone</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">Email</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">NPWP</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">Category Vendor</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
            <tr>
                <td style="border: 1px solid black;">{{ $item->name }}</td>
                <td style="border: 1px solid black;">{{ $item->alamat }}</td>
                <td style="border: 1px solid black;">{{ $item->contact_person }}</td>
                <td style="border: 1px solid black;">{{ $item->nomor_contact_person }}</td>
                <td style="border: 1px solid black;">{{ $item->email }}</td>
                <td style="border: 1px solid black;">{{ $item->npwp }}</td>
                <td style="border: 1px solid black;">{{ $item->kategori_vendor->name ?? '' }}</td>
            </tr>
       @endforeach
    </tbody>
</table>