<h3>Project Engineer Report</h3>
<table border="1">
    <thead>
        <tr>
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="200px">Project Name</th>
            @foreach($projectEngineers as $pe)
            <th style="border: 1px solid black;background-color: #2c83ca; color:white;" width="150px">{{ $pe->karyawan->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($data as $project)
            <tr>
                <td style="border: 1px solid black;">{{ $project->nama_project }}</td>
                @foreach($projectEngineers as $pe)
                <td style="border: 1px solid black; text-align: center;">
                    @if($project->pe_id_1 == $pe->id)
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
            @foreach($projectEngineers as $pe)
            <th style="border: 1px solid black; text-align: center;">
                {{ $data->where('pe_id_1', $pe->id)->where('status', 1)->count() }}
            </th>
            @endforeach
        </tr>
        <tr style="background-color: #d4edda;">
            <th style="border: 1px solid black;"><strong>Total Completed</strong></th>
            @foreach($projectEngineers as $pe)
            <th style="border: 1px solid black; text-align: center;">
                {{ $data->where('pe_id_1', $pe->id)->where('status', 2)->count() }}
            </th>
            @endforeach
        </tr>
    </tfoot>
</table>