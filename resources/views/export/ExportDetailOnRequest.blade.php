<h3> Detail Project Request {{ $data->code }}</h3>
<table border="1">
    <thead>
        <tr>
            <th width="500px"></th>
            <th width="500px"></th>
        </tr>
        <tr>
            <th width="500px">Nama Project : {{ $data->nama_project ?? ''}}</th>
            <th width="500px">Nama Project Manager : {{ $data->pm->name ?? ''}}</th>
        </tr>
        <tr>
            <th width="500px">Nama Customer : {{ $data->customer->name ?? ''}}</th>
            <th width="500px">Lokasi Project : {{ $data->lokasi->name ?? '' }}</th>
        </tr>
        <tr>
            <th width="500px">Contact Person : {{ $data->contact_person ?? ''}}</th>
            <th width="500px">Nomor Contact Person : {{ $data->nomor_contact_person ?? ''}}</th>
        </tr>
        <tr>
            <th width="500px">Alamat Customer : {{ $data->customer->alamat ?? ''}}</th>
            <th width="500px">NPWP : {{ $data->customer->npwp ?? ''}}</th>
        </tr>
        <tr>
            <th width="500px">Displacement Kapal : {{ $data->displacement ?? ''}}</th>
            <th width="500px">Jenis Kapal : {{ $data->kapal->name ?? ''}}</th>
        </tr>
    </thead>
    <tbody>
        <tr></tr>
    </tbody>
</table>

<h3>Project Request</h3>
<table border="1">
    <thead>
        <tr>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">No.</th>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">Keluhan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data->keluhan as $key => $item)
            <tr>
                <td style="border: 1px solid black;">{{ $key+1 }}</td>
                <td style="border: 1px solid black;">{{ $item->keluhan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>