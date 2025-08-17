<h3>Project Admin Report</h3>
<table border="1">
    <thead>
        <tr>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Project Name</th>
            @foreach($projectAdmins as $pa)
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">{{ $pa->karyawan->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($data as $project)
            <tr>
                <td style="border: 1px solid black;">{{ $project->nama_project }}</td>
                @foreach($projectAdmins as $pa)
                <td style="border: 1px solid black; text-align: center;">
                    @if($project->pa_id == $pa->id)
                        @if($project->status == 1)
                            ●
                        @elseif($project->status == 2)
                            ✓
                        @else
                            ○
                        @endif
                    @endif
                </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="background-color: #d1ecf1;">
            <th style="border: 1px solid black;"><strong>Total Progress</strong></th>
            @foreach($projectAdmins as $pa)
            <th style="border: 1px solid black; text-align: center;">
                {{ $data->where('pa_id', $pa->id)->where('status', 1)->count() }}
            </th>
            @endforeach
        </tr>
        <tr style="background-color: #d4edda;">
            <th style="border: 1px solid black;"><strong>Total Completed</strong></th>
            @foreach($projectAdmins as $pa)
            <th style="border: 1px solid black; text-align: center;">
                {{ $data->where('pa_id', $pa->id)->where('status', 2)->count() }}
            </th>
            @endforeach
        </tr>
    </tfoot>
</table>