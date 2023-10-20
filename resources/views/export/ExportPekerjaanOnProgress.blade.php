<table>
    <tr style="font-size: 14px;">
        <td rowspan="4"></td>
        <td align="right" rowspan="4" style="width: 600px;">
            {{-- <img src="{{asset('assets/images/logo.png')}}" width="120px"> --}}
        </td>
        <td colspan="8" style="font-weight:600;">PT. GAMATARA TRANS OCEAN SHIPYARD</td>
    </tr>
    <tr style="font-size: 14px;">
        <td colspan="8">Kantor : Jl. Tanjung Tengah No. 1B</td>
    </tr>
    <tr style="font-size: 14px;">
        <td colspan="8">Pelabuhan Cirebon 45112 - Jawa Barat</td>
    </tr>
    <tr style="font-size: 14px;">
        <td colspan="8">Telp. (0231) 226435 Fax. (0231) 226436</td>
    </tr>
    <tr align="center">
        <td colspan="11" style="font-weight: 600;font-size: 14px;">
            <strong>SATISFACTION NOTE</strong>
        </td>
    </tr>
    <tr style="font-size: 12px;font-weight: bold;">
        <td colspan="3">Tanggal 03 Juli 2023</td>
        <td>Project</td>
        <td colspan="7">TK. PULAU TIGA 3017 - 2023</td>
    </tr>
    <tr align="center" style="font-size: 12px;font-weight: bold;">
        <td>No</td>
        <td colspan="10">Uraian Pekerjaan</td>
    </tr>
    <tr style="font-size: 10px;">
        <th></th>
        <th>Pekerjaan</th>
        <th>Location / Area</th>
        <th>Detail / Other</th>
        <th>Length (mm)</th>
        <th>Width (mm)</th>
        <th>Thick (mm)</th>
        <th>Qty/Days/%</th>
        <th>Amount</th>
        <th>Unit</th>
        <th>Vendor</th>
    </tr>
    @php
        $letters = range('A', 'Z');
        $count = 0;
        $prevSub = '';
    @endphp
    @foreach ($data as $index => $item)
        <tr style="font-size: 11px;">
            <td style="font-weight: bold;">{{ $letters[$count] }}.</td>
            <td colspan="10" style="font-weight: bold;">{{ $index }}</td>
        </tr>
        @foreach ($item as $indexs => $value)
            @php
                $subkategori = $value->subKategori->name;
            @endphp
            @if ($letters[$count] !== 'A')
                <tr style="font-size: 11px;">
                    @if ($subkategori !== $prevSub)
                        <td class="text-center">{{ $letters[$count] }}.{{ $indexs + 1 }}.</td>
                        <td colspan="10">{{ $value->subKategori->name }}</td>
                    @endif
                </tr>
                <tr style="font-size: 11px;">
                    <td></td>
                    <td>{{ $value->pekerjaan->name }}</td>
                    <td>{{ $value->lokasi->name }}</td>
                    <td>{{ $value->detail }}</td>
                    <td>{{ $value->length }}</td>
                    <td>{{ $value->width }}</td>
                    <td>{{ $value->thick }}</td>
                    <td>{{ $value->qty }}</td>
                    <td>{{ $value->amount }}</td>
                    <td>{{ $value->unit }}</td>
                    <td>{{ $value->vendors->name }}</td>
                </tr>
            @endif
            @php
                $prevSub = $subkategori;
            @endphp
        @endforeach
        @php
            $count++
        @endphp
    @endforeach
    {!! str_repeat('<tr></tr>', 2) !!}
    <tr align="center" style="font-size: 14px;">
        <td colspan="11">Cirebon, {{ formatTanggal() }}</td>
    </tr>
    {!! str_repeat('<tr></tr>', 3) !!}
    <tr>
        <td colspan="9" align="right">Hormat Kami</td>
        <td colspan="2"></td>
    </tr>
    {!! str_repeat('<tr></tr>', 6) !!}
    <tr align="center">
        <td style="font-size: 12px;" colspan="5"><span class="text-decoration-underline"><strong>SUGIARTO SANTOSO, S. KOM</strong></span></td>
        <td ></td>
        <td style="font-size: 12px;" colspan="5"><span class="text-decoration-underline"><strong>UJANG WIJIANTORO</strong></span></td>
    </tr>
    <tr align="center">
        <td style="font-size: 12px;" colspan="5"><span class="">DIREKTUR</span></td>
        <td></td>
        <td style="font-size: 12px;" colspan="5"><span class="">KEPALA PROYEK</span></td>
    </tr>
    <tr align="center">
        <td style="font-size: 12px;" colspan="5"><span class="">PT. GAMATARA TRANS OCEAN SHIPYARD</span></td>
        <td></td>
        <td colspan="5"></td>
    </tr>
    {!! str_repeat('<tr></tr>', 6) !!}
    <tr align="center" style="font-size: 12px;font-weight: bold;">
        <td colspan="11"><strong>HANAFI SANTOSO, ST</strong></td>
    </tr>
    <tr align="center" style="font-size: 12px;">
        <td colspan="11"><span class="">DIREKTUR</span></td>
    </tr>
    <tr align="center" style="font-size: 12px;">
        <td colspan="11"><span class="">PT. GAMATARA TRANS OCEAN SHIPYARD</span></td>
    </tr>
    {!! str_repeat('<tr></tr>', 2) !!}
    <tr align="center" style="font-size: 12px;">
        <td colspan="11"><span class="">MENGETAHUI,</span></td>
    </tr>
    {!! str_repeat('<tr></tr>', 6) !!}
    <tr align="center" style="font-size: 12px;font-weight: bold;">
        <td colspan="11">.....................................</td>
    </tr>
    <tr align="center" style="font-size: 12px;font-weight: bold;">
        <td colspan="11">PT. PULAU SEROJA JAYA</td>
    </tr>
</table>
