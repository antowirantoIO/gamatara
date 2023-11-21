<h3>List Sub Category</h3>
<table border="1">
    <thead>
        <tr>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Category Name</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Sub Category Name</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
            <tr>
                <td style="border: 1px solid black;">{{ $item->kategori->name }}</td>
                <td style="border: 1px solid black;">{{ $item->name }}</td>
            </tr>
       @endforeach
    </tbody>
</table>